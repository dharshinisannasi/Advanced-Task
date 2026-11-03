<?php
namespace App\Controllers;

use App\Models\Productapi_model;
use CodeIgniter\API\ResponseTrait;

class Productapi extends BaseController
{
    use ResponseTrait;

    protected $productApiModel;

    public function __construct()
    {
        $this->productApiModel = new Productapi_model();
    }

    // Authenticate API token
    private function authenticateApi()
    {
        $header = $this->request->getHeaderLine('Authorization');

        if (!preg_match('/^Bearer\s+(\S+)$/i', $header, $matches)) {
            return null;
        }

        return $this->productApiModel->authenticateToken($matches[1]);
    }

    // Check authentication and role
    private function getAuthenticatedUser()
    {
        $user = $this->authenticateApi();

        if (!$user) {
            return $this->respond([
                'status' => false,
                'message' => 'Unauthorized. Invalid or expired token.'
            ], 401);
        }

        if (!in_array($user['role'] ?? '', ['Admin', 'Employee'], true)) {
            return $this->respond([
                'status' => false,
                'message' => 'You are not authorized.'
            ], 403);
        }

        return $user;
    }

    // Upload product image
    private function uploadProductImage($file)
    {
        if (!$file || $file->getError() === UPLOAD_ERR_NO_FILE) {
            return [
                'success' => true,
                'filename' => null
            ];
        }

        if (!$file->isValid() || $file->hasMoved()) {
            return [
                'success' => false,
                'message' => 'Image upload failed.',
                'httpCode' => 400
            ];
        }

        $allowedTypes = [
            'image/jpeg',
            'image/png',
            'image/webp'
        ];

        if (!in_array($file->getMimeType(), $allowedTypes, true)) {
            return [
                'success' => false,
                'message' => 'Only JPG, PNG and WEBP images are allowed.',
                'httpCode' => 400
            ];
        }

        if ($file->getSize() > 2 * 1024 * 1024) {
            return [
                'success' => false,
                'message' => 'Image size must not exceed 2 MB.',
                'httpCode' => 400
            ];
        }

        $uploadPath = FCPATH . 'uploads/';

        if (
            !is_dir($uploadPath) &&
            !mkdir($uploadPath, 0755, true) &&
            !is_dir($uploadPath)
        ) {
            return [
                'success' => false,
                'message' => 'Unable to create upload directory.',
                'httpCode' => 500
            ];
        }

        $filename = $file->getRandomName();

        if (!$file->move($uploadPath, $filename)) {
            return [
                'success' => false,
                'message' => 'Image upload failed.',
                'httpCode' => 500
            ];
        }

        return [
            'success' => true,
            'filename' => $filename
        ];
    }

    // Filter options API
    public function filterOptions()
    {
        $user = $this->getAuthenticatedUser();

        if ($user instanceof \CodeIgniter\HTTP\ResponseInterface) {
            return $user;
        }

        $options = $this->productApiModel->getFilterOptions($user);

        return $this->respond([
            'status' => true,
            'message' => 'Filter options retrieved successfully.',
            'data' => $options
        ], 200);
    }

    // List products with search, filters, sorting and pagination
    public function index()
    {
        $user = $this->getAuthenticatedUser();

        if ($user instanceof \CodeIgniter\HTTP\ResponseInterface) {
            return $user;
        }

        $filters = [
            'search'       => trim((string) $this->request->getGet('search')),
            'created_by'   => $this->request->getGet('created_by'),
            'product_name' => $this->request->getGet('product_name'),
            'product_code' => $this->request->getGet('product_code'),
            'date'         => $this->request->getGet('date'),
            'category'     => $this->request->getGet('category'),
            'status'       => $this->request->getGet('status'),
            'page'         => $this->request->getGet('page'),
            'limit'        => $this->request->getGet('limit'),
            'sort_by'      => $this->request->getGet('sort_by'),
            'order'        => $this->request->getGet('order')
        ];

        $result = $this->productApiModel->getProductsForApi(
            $user,
            $filters
        );

        return $this->respond([
            'status' => true,
            'message' => 'Products retrieved successfully.',
            'data' => $result['data'],
            'pagination' => $result['pagination']
        ], 200);
    }

    // Retrieve one product
    public function singledata($id)
    {
        $user = $this->getAuthenticatedUser();

        if ($user instanceof \CodeIgniter\HTTP\ResponseInterface) {
            return $user;
        }

        $product = $this->productApiModel->getProductById($id);

        if (!$product) {
            return $this->respond([
                'status' => false,
                'message' => 'Product not found.',
                'data' => null
            ], 404);
        }

        // Employees can access only their own products
        if (
            $user['role'] !== 'Admin' &&
            (string) $product['created_by'] !==
            (string) $user['username']
        ) {
            return $this->respond([
                'status' => false,
                'message' => 'Product not found.',
                'data' => null
            ], 404);
        }

        return $this->respond([
            'status' => true,
            'message' => 'Product retrieved successfully.',
            'data' => $product
        ], 200);
    }

    // Insert product
    public function insert()
    {
        $user = $this->getAuthenticatedUser();

        if ($user instanceof \CodeIgniter\HTTP\ResponseInterface) {
            return $user;
        }

        $rules = [
            'product_code' => 'required|max_length[100]',
            'product_name' => 'required|max_length[255]',
            'price'        => 'required|decimal',
            'quantity'     => 'required|integer'
        ];

        if (!$this->validate($rules)) {
            return $this->respond([
                'status' => false,
                'message' => 'Validation failed.',
                'errors' => $this->validator->getErrors()
            ], 400);
        }

        $upload = $this->uploadProductImage(
            $this->request->getFile('product_image')
        );

        if (!$upload['success']) {
            return $this->respond([
                'status' => false,
                'message' => $upload['message']
            ], $upload['httpCode']);
        }

        $now = date('Y-m-d H:i:s');

        $insertData = [
            'product_code'  => $this->request->getPost('product_code'),
            'product_name'  => $this->request->getPost('product_name'),
            'category'      => $this->request->getPost('category') ?? '',
            'description'   => $this->request->getPost('description') ?? '',
            'price'         => $this->request->getPost('price'),
            'quantity'      => $this->request->getPost('quantity'),
            'product_image' => $upload['filename'],
            'status'        => $this->request->getPost('status') ?? 'Active',
            'role'          => $user['role'],
            'created_by'    => $user['username'],
            'created_at'    => $now,
            'updated_at'    => $now
        ];

        if (!$this->productApiModel->insert($insertData)) {
            return $this->respond([
                'status' => false,
                'message' => 'Product insertion failed.',
                'errors' => $this->productApiModel->errors()
            ], 500);
        }

        return $this->respond([
            'status' => true,
            'message' => 'Product inserted successfully.',
            'id' => $this->productApiModel->getInsertID(),
            'product_image' => $upload['filename']
        ], 201);
    }

    // Delete product
    public function delete($id)
    {
        $user = $this->getAuthenticatedUser();

        if ($user instanceof \CodeIgniter\HTTP\ResponseInterface) {
            return $user;
        }

        $product = $this->productApiModel->getProductById($id);

        if (!$product) {
            return $this->respond([
                'status' => false,
                'message' => 'Product not found.'
            ], 404);
        }

        // Employees can delete only their own products
        if (
            $user['role'] !== 'Admin' &&
            (string) $product['created_by'] !==
            (string) $user['username']
        ) {
            return $this->respond([
                'status' => false,
                'message' => 'Forbidden. You can delete only your own products.'
            ], 403);
        }

        if (!$this->productApiModel->delete($id)) {
            return $this->respond([
                'status' => false,
                'message' => 'Product deletion failed.',
                'errors' => $this->productApiModel->errors()
            ], 500);
        }

        return $this->respond([
            'status' => true,
            'message' => 'Product deleted successfully.'
        ], 200);
    }

    // Update product
    public function update($id)
    {
        $user = $this->getAuthenticatedUser();

        if ($user instanceof \CodeIgniter\HTTP\ResponseInterface) {
            return $user;
        }

        $product = $this->productApiModel->getProductById($id);

        if (!$product) {
            return $this->respond([
                'status' => false,
                'message' => 'Product not found.'
            ], 404);
        }

        // Employees can update only their own products
        if (
            $user['role'] !== 'Admin' &&
            (string) $product['created_by'] !==
            (string) $user['username']
        ) {
            return $this->respond([
                'status' => false,
                'message' => 'Forbidden. You can update only your own products.'
            ], 403);
        }

        $data = $this->request->getPost();

        $rules = [
            'product_code' => 'required|max_length[100]',
            'product_name' => 'required|max_length[255]',
            'price'        => 'required|decimal',
            'quantity'     => 'required|integer'
        ];

        if (!$this->validateData($data, $rules)) {
            return $this->respond([
                'status' => false,
                'message' => 'Validation failed.',
                'errors' => $this->validator->getErrors()
            ], 400);
        }

        $updateData = [
            'product_code' => $data['product_code'],
            'product_name' => $data['product_name'],
            'category'     => $data['category'] ?? '',
            'description'  => $data['description'] ?? '',
            'price'        => $data['price'],
            'quantity'     => $data['quantity'],
            'status'       => $data['status'] ?? 'Active',
            'updated_at'   => date('Y-m-d H:i:s')
        ];

        // Replace image only when a new image is uploaded
        $image = $this->request->getFile('product_image');

        if ($image && $image->getError() !== UPLOAD_ERR_NO_FILE) {
            $upload = $this->uploadProductImage($image);

            if (!$upload['success']) {
                return $this->respond([
                    'status' => false,
                    'message' => $upload['message']
                ], $upload['httpCode']);
            }

            $updateData['product_image'] = $upload['filename'];
        }

        // Preserve original role and created_by
        if (!$this->productApiModel->update($id, $updateData)) {
            return $this->respond([
                'status' => false,
                'message' => 'Failed to update product.',
                'errors' => $this->productApiModel->errors()
            ], 500);
        }

        $updatedProduct = $this->productApiModel->getProductById($id);

        return $this->respond([
            'status' => true,
            'message' => 'Product updated successfully.',
            'data' => $updatedProduct
        ], 200);
    }
}
