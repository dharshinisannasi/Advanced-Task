<?php
namespace App\Controllers;

use App\Models\product_model;
use CodeIgniter\API\ResponseTrait;

class Productapi extends BaseController
{
    use ResponseTrait;

    private function authenticateApi()
    {
        $header = $this->request->getHeaderLine('Authorization');

        if (!preg_match('/Bearer\s+(\S+)/i', $header, $matches)) {
            return null;
        }

        $token = $matches[1];

        $db = \Config\Database::connect();

        $tokenData = $db->table('api_tokens')
            ->where('token_hash', hash('sha256', $token))
            ->where('expires_at >', date('Y-m-d H:i:s'))
            ->get()
            ->getRowArray();

        if (!$tokenData) {
            return null;
        }

        $user = $db->table('users')
            ->where('id', $tokenData['user_id'])
            ->get()
            ->getRowArray();

        if (!$user || ($user['status'] ?? '') !== 'Active') {
            return null;
        }

        return $user;
    }

    /**
     * Get dynamic dropdown options based on logged-in user's role.
     */
    public function filterOptions()
    {
        $user = $this->authenticateApi();

        if (!$user) {
            return $this->respond([
                'status' => false,
                'message' => 'Unauthorized. Invalid or expired token.'
            ], 401);
        }

        if (!in_array($user['role'], ['Admin', 'Employee'], true)) {
            return $this->respond([
                'status' => false,
                'message' => 'You are not authorized.'
            ], 403);
        }

        $db = \Config\Database::connect();

        // Base query
        $builder = $db->table('products');

        // Employee can access only their own product options
        if ($user['role'] === 'Employee') {
            $builder->where('created_by', $user['username']);
        }

        // Product names
        $nameBuilder = clone $builder;

        $productNames = $nameBuilder
            ->distinct()
            ->select('product_name')
            ->where('product_name !=', '')
            ->orderBy('product_name', 'ASC')
            ->get()
            ->getResultArray();

        // Product codes
        $codeBuilder = clone $builder;

        $productCodes = $codeBuilder
            ->distinct()
            ->select('product_code')
            ->where('product_code !=', '')
            ->orderBy('product_code', 'ASC')
            ->get()
            ->getResultArray();

        // Dates
        $dateBuilder = clone $builder;

        $dates = $dateBuilder
            ->distinct()
            ->select('DATE(created_at) AS product_date')
            ->where('created_at IS NOT NULL')
            ->orderBy('product_date', 'DESC')
            ->get()
            ->getResultArray();

        // Categories
        $categoryBuilder = clone $builder;

        $categories = $categoryBuilder
            ->distinct()
            ->select('category')
            ->where('category IS NOT NULL')
            ->where('category !=', '')
            ->orderBy('category', 'ASC')
            ->get()
            ->getResultArray();

        // Statuses
        $statusBuilder = clone $builder;

        $statuses = $statusBuilder
            ->distinct()
            ->select('status')
            ->where('status IS NOT NULL')
            ->where('status !=', '')
            ->orderBy('status', 'ASC')
            ->get()
            ->getResultArray();

        // Users - Admin only
        $users = [];

        if ($user['role'] === 'Admin') {
            $users = $db->table('products')
                ->distinct()
                ->select('created_by')
                ->where('created_by !=', '')
                ->orderBy('created_by', 'ASC')
                ->get()
                ->getResultArray();
        }

        return $this->respond([
            'status' => true,
            'message' => 'Filter options retrieved successfully.',
            'data' => [
                'users' => array_column($users, 'created_by'),
                'product_names' => array_column($productNames, 'product_name'),
                'product_codes' => array_column($productCodes, 'product_code'),
                'dates' => array_column($dates, 'product_date'),
                'categories' => array_column($categories, 'category'),
                'statuses' => array_column($statuses, 'status')
            ]
        ], 200);
    }

    /**
     * Return all products to Admin.
     * Return only the logged-in Employee's products to Employee.
     */
    public function index()
    {
        // 1. Authenticate the API user
        $user = $this->authenticateApi();

        if (!$user) {
            return $this->respond([
                'status' => false,
                'message' => 'Unauthorized. Invalid or expired token.'
            ], 401);
        }

        // 2. Check user role
        if (!in_array($user['role'], ['Admin', 'Employee'], true)) {
            return $this->respond([
                'status' => false,
                'message' => 'You are not authorized.'
            ], 403);
        }

        $productModel = new product_model();
        $builder = $productModel->builder();

        // 3. Apply role-based product access
        if ($user['role'] === 'Employee') {
            $builder->where('created_by', $user['username']);
        }

        // 4. Search
        $search = trim((string) $this->request->getGet('search'));

        if (!empty($search)) {
            $builder->groupStart()
                ->like('product_code', $search)
                ->orLike('product_name', $search)
                ->orLike('category', $search)
                ->orLike('description', $search)
                ->orLike('status', $search)
                ->orLike('created_by', $search)
                ->orLike('price', $search)
                ->orLike('quantity', $search)
                ->groupEnd();
        }

        // 5. Filtering

        // Admin only: Filter by Created By
        if ($user['role'] === 'Admin') {
            $createdBy = $this->request->getGet('created_by');

            if ($createdBy !== null && $createdBy !== '') {
                $builder->where('created_by', $createdBy);
            }
        }

        // Filter by Product Name
        $productName = $this->request->getGet('product_name');

        if ($productName !== null && $productName !== '') {
            $builder->where('product_name', $productName);
        }

        // Filter by Product Code
        $productCode = $this->request->getGet('product_code');

        if ($productCode !== null && $productCode !== '') {
            $builder->where('product_code', $productCode);
        }

        // Filter by Date
        $date = $this->request->getGet('date');

        if ($date !== null && $date !== '') {
            $builder->where('DATE(created_at)', $date);
        }

        // Filter by Category
        $category = $this->request->getGet('category');

        if ($category !== null && $category !== '') {
            $builder->where('category', $category);
        }

        // Filter by Status
        $status = $this->request->getGet('status');

        if ($status !== null && $status !== '') {
            $builder->where('status', $status);
        }

        // 6. Count matching products before pagination
        $totalRecords = $builder->countAllResults(false);

        // 7. Pagination
        $page = max(1, (int) $this->request->getGet('page'));
        $limit = (int) $this->request->getGet('limit');

        if (!in_array($limit, [5, 10, 20, 50], true)) {
            $limit = 10;
        }

        $offset = ($page - 1) * $limit;
        $totalPages = (int) ceil($totalRecords / $limit);

        // 8. Sorting - allow only approved column names
        $allowedSortColumns = [
            'id',
            'product_code',
            'product_name',
            'category',
            'price',
            'quantity',
            'status',
            'created_at'
        ];

        $sortBy = $this->request->getGet('sort_by') ?? 'id';
        $order = strtoupper($this->request->getGet('order') ?? 'DESC');

        if (!in_array($sortBy, $allowedSortColumns, true)) {
            $sortBy = 'id';
        }

        if (!in_array($order, ['ASC', 'DESC'], true)) {
            $order = 'DESC';
        }

        $builder->orderBy($sortBy, $order);

        // 9. Get paginated results
        $products = $builder->get($limit, $offset)->getResultArray();

        // 10. Return JSON response
        return $this->respond([
            'status' => true,
            'message' => 'Products retrieved successfully',
            'data' => $products,
            'pagination' => [
                'current_page' => $page,
                'per_page' => $limit,
                'total_records' => $totalRecords,
                'total_pages' => $totalPages
            ]
        ], 200);
    }

    /**
     * Retrieve a single product.
     * Employee can retrieve only their own product.
     */
    public function singledata($id)
    {
        $user = $this->authenticateApi();

        if (!$user) {
            return $this->respond([
                'status' => false,
                'message' => 'Unauthorized. Invalid or expired token.'
            ], 401);
        }

        $productModel = new product_model();
        $product = $productModel->find($id);

        if (!$product) {
            return $this->respond([
                'status' => false,
                'message' => 'Product not found',
                'data' => null
            ], 404);
        }

        if (
            ($user['role'] ?? '') !== 'Admin' &&
            (string) ($product['created_by'] ?? '') !==
            (string) ($user['username'] ?? '')
        ) {
            return $this->respond([
                'status' => false,
                'message' => 'Product not found',
                'data' => null
            ], 404);
        }

        return $this->respond([
            'status' => true,
            'message' => 'Product retrieved successfully',
            'data' => $product
        ], 200);
    }

    /**
     * Admin and Employee can create products.
     * The server assigns created_by and role.
     */
    public function insert()
    {
        $user = $this->authenticateApi();

        if (!$user) {
            return $this->respond([
                'status' => false,
                'message' => 'Unauthorized. Invalid or expired token.'
            ], 401);
        }

        $productModel = new product_model();

        $rules = [
            'product_code' => 'required',
            'product_name' => 'required',
            'price' => 'required|decimal',
            'quantity' => 'required|integer'
        ];

        if (!$this->validate($rules)) {
            return $this->respond([
                'status' => false,
                'message' => 'Validation failed.',
                'errors' => $this->validator->getErrors()
            ], 400);
        }

        $file = $this->request->getFile('product_image');
        $imageName = null;

        if ($file && $file->isValid() && !$file->hasMoved()) {
            $allowedTypes = [
                'image/jpeg',
                'image/png',
                'image/webp'
            ];

            if (!in_array($file->getMimeType(), $allowedTypes, true)) {
                return $this->respond([
                    'status' => false,
                    'message' => 'Only JPG, PNG and WEBP images are allowed.'
                ], 400);
            }

            if ($file->getSize() > 2 * 1024 * 1024) {
                return $this->respond([
                    'status' => false,
                    'message' => 'Image size must not exceed 2 MB.'
                ], 400);
            }

            $uploadPath = FCPATH . 'uploads/';

            if (
                !is_dir($uploadPath) &&
                !mkdir($uploadPath, 0755, true) &&
                !is_dir($uploadPath)
            ) {
                return $this->respond([
                    'status' => false,
                    'message' => 'Unable to create upload directory.'
                ], 500);
            }

            $imageName = $file->getRandomName();

            if (!$file->move($uploadPath, $imageName)) {
                return $this->respond([
                    'status' => false,
                    'message' => 'Image upload failed.'
                ], 500);
            }
        } elseif ($file && $file->getError() !== UPLOAD_ERR_NO_FILE) {
            return $this->respond([
                'status' => false,
                'message' => 'Image upload failed.'
            ], 400);
        }

        $insertData = [
            'product_code' => $this->request->getPost('product_code'),
            'category' => $this->request->getPost('category') ?? '',
            'product_name' => $this->request->getPost('product_name'),
            'description' => $this->request->getPost('description') ?? '',
            'price' => $this->request->getPost('price'),
            'quantity' => $this->request->getPost('quantity'),
            'product_image' => $imageName,
            'status' => $this->request->getPost('status') ?? 'Active',
            'role' => $user['role'],
            'created_by' => $user['username'],
            'created_at' => date('Y-m-d H:i:s'),
            'updated_at' => date('Y-m-d H:i:s')
        ];

        if ($productModel->insert($insertData)) {
            return $this->respond([
                'status' => true,
                'message' => 'Product inserted successfully',
                'product_image' => $imageName,
                'id' => $productModel->getInsertID()
            ], 201);
        }

        return $this->respond([
            'status' => false,
            'message' => 'Product insertion failed',
            'errors' => $productModel->errors()
        ], 500);
    }

    /**
     * Admin can delete any product.
     * Employee can delete only their own products.
     */
    public function delete($id)
    {
        $user = $this->authenticateApi();

        if (!$user) {
            return $this->respond([
                'status' => false,
                'message' => 'Unauthorized. Invalid or expired token.'
            ], 401);
        }

        $productModel = new product_model();
        $product = $productModel->find($id);

        if (!$product) {
            return $this->respond([
                'status' => false,
                'message' => 'Product not found'
            ], 404);
        }

        if (
            ($user['role'] ?? '') !== 'Admin' &&
            (string) ($product['created_by'] ?? '') !==
            (string) ($user['username'] ?? '')
        ) {
            return $this->respond([
                'status' => false,
                'message' => 'Forbidden. You can delete only your own products.'
            ], 403);
        }

        if (!$productModel->delete($id)) {
            return $this->respond([
                'status' => false,
                'message' => 'Product deletion failed',
                'errors' => $productModel->errors()
            ], 500);
        }

        return $this->respond([
            'status' => true,
            'message' => 'Product deleted successfully'
        ], 200);
    }

    /**
     * Admin can update any product.
     * Employee can update only their own products.
     *
     * Supports multipart FormData sent using POST.
     */
    public function update($id)
    {
        $user = $this->authenticateApi();

        if (!$user) {
            return $this->respond([
                'status' => false,
                'message' => 'Unauthorized. Invalid or expired token.'
            ], 401);
        }

        $productModel = new product_model();
        $product = $productModel->find($id);

        if (!$product) {
            return $this->respond([
                'status' => false,
                'message' => 'Product not found.'
            ], 404);
        }

        if (
            ($user['role'] ?? '') !== 'Admin' &&
            (string) ($product['created_by'] ?? '') !==
            (string) ($user['username'] ?? '')
        ) {
            return $this->respond([
                'status' => false,
                'message' => 'Forbidden. You can update only your own products.'
            ], 403);
        }

        // Read multipart FormData.
        $data = $this->request->getPost();

        $rules = [
            'product_code' => 'required|max_length[100]',
            'product_name' => 'required|max_length[255]',
            'price' => 'required|decimal',
            'quantity' => 'required|integer'
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
            'category' => $data['category'] ?? '',
            'product_name' => $data['product_name'],
            'description' => $data['description'] ?? '',
            'price' => $data['price'],
            'quantity' => $data['quantity'],
            'status' => $data['status'] ?? 'Active',
            'updated_at' => date('Y-m-d H:i:s')
        ];

        // Do not change created_by or role during an update.
        $image = $this->request->getFile('product_image');

        if ($image && $image->isValid() && !$image->hasMoved()) {
            $allowedTypes = [
                'image/jpeg',
                'image/png',
                'image/webp'
            ];

            if (!in_array($image->getMimeType(), $allowedTypes, true)) {
                return $this->respond([
                    'status' => false,
                    'message' => 'Only JPG, PNG and WEBP images are allowed.'
                ], 400);
            }

            if ($image->getSize() > 2 * 1024 * 1024) {
                return $this->respond([
                    'status' => false,
                    'message' => 'Image size must not exceed 2 MB.'
                ], 400);
            }

            $uploadPath = FCPATH . 'uploads/';

            if (
                !is_dir($uploadPath) &&
                !mkdir($uploadPath, 0755, true) &&
                !is_dir($uploadPath)
            ) {
                return $this->respond([
                    'status' => false,
                    'message' => 'Unable to create upload directory.'
                ], 500);
            }

            $newName = $image->getRandomName();

            if (!$image->move($uploadPath, $newName)) {
                return $this->respond([
                    'status' => false,
                    'message' => 'Failed to upload product image.'
                ], 500);
            }

            $updateData['product_image'] = $newName;

        } elseif ($image && $image->getError() !== UPLOAD_ERR_NO_FILE) {
            return $this->respond([
                'status' => false,
                'message' => 'Image upload failed.'
            ], 400);
        }

        if (!$productModel->update($id, $updateData)) {
            return $this->respond([
                'status' => false,
                'message' => 'Failed to update product.',
                'errors' => $productModel->errors()
            ], 500);
        }

        $updatedProduct = $productModel->find($id);

        return $this->respond([
            'status' => true,
            'message' => 'Product updated successfully.',
            'data' => $updatedProduct
        ], 200);
    }

    
}
