<?php

namespace App\Controllers;

use App\Models\product_model;

class Viewproduct extends BaseController
{
    protected $product_model;

    public function __construct()
    {
        $this->product_model = new product_model();
    }

    public function index($id = null)
    {
        if (!session()->get('LoggedIn')) {
            return redirect()->to(base_url('loginpage'));
        }

        // Use the given product ID
        if ($id === null) {
            $id = $this->request->getGet('id');
        }

        if (!$id) {
            return redirect()->to(base_url('dashboardpage'));
        }

        // Get product details
        $product = $this->product_model->find($id);

        if (!$product) {
            return redirect()->to(base_url('dashboardpage'));
        }

        $data = [
            'id'           => $product['id'] ?? $id,
            'productimage' => $product['product_image'] ?? '',
            'productname'  => $product['product_name'] ?? '',
            'productcode'  => $product['product_code'] ?? '',
            'category'     => $product['category'] ?? '',
            'status'       => $product['status'] ?? '',
            'price'        => $product['price'] ?? '',
            'quantity'     => $product['quantity'] ?? ''
        ];

        return view('viewproductpage', $data);
    }

    public function updateproduct($id)
    {
        if (!session()->get('LoggedIn')) {
            return $this->response->setJSON([
                'status'  => false,
                'message' => 'Please log in again.'
            ])->setStatusCode(401);
        }

        $product = $this->product_model->find($id);

        if (!$product) {
            return $this->response->setJSON([
                'status'  => false,
                'message' => 'Product not found.'
            ])->setStatusCode(404);
        }

        $file = $this->request->getFile('product_image');

        if (!$file || $file->getError() !== UPLOAD_ERR_OK || !$file->isValid()) {
            return $this->response->setJSON([
                'status'  => false,
                'message' => 'Please select a valid image.'
            ]);
        }

        // Validate image type
        $allowedTypes = [
            'image/jpeg',
            'image/png',
            'image/webp'
        ];

        if (!in_array($file->getMimeType(), $allowedTypes, true)) {
            return $this->response->setJSON([
                'status'  => false,
                'message' => 'Please select a JPG, PNG, or WebP image.'
            ]);
        }

        // Validate image size (2 MB maximum)
        if ($file->getSizeByUnit('mb') > 2) {
            return $this->response->setJSON([
                'status'  => false,
                'message' => 'Image size must not exceed 2 MB.'
            ]);
        }

        $uploadPath = FCPATH . 'uploads' . DIRECTORY_SEPARATOR;

        if (!is_dir($uploadPath) && !mkdir($uploadPath, 0755, true)) {
            return $this->response->setJSON([
                'status'  => false,
                'message' => 'Unable to create the uploads folder.'
            ]);
        }

        try {
            $newName = $file->getRandomName();
            $file->move($uploadPath, $newName);

            $data = [
                'product_image' => $newName
            ];

            $username = session()->get('username');

            // Update the product image in the database
            $result = $this->product_model->updateallproduct(
                $data,
                $id,
                $username
            );

            if (!$result) {
                return $this->response->setJSON([
                    'status'  => false,
                    'message' => 'Image uploaded, but the database update failed.'
                ]);
            }

            return $this->response->setJSON([
                'status' => true,
                'message' => 'Product image updated successfully.',
                'photo'  => $newName
            ]);

        } catch (\Throwable $e) {
            log_message('error', 'Product image update failed: ' . $e->getMessage());

            return $this->response->setJSON([
                'status'  => false,
                'message' => 'Unable to update the product image.'
            ]);
        }
    }
}