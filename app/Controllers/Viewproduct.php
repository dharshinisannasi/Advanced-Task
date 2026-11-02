<?php
namespace App\Controllers;

use App\Models\product_model;

class Viewproduct extends BaseController
{
    public function __construct()
    {
        $this->product_model = new product_model();
    }

    public function index($id = null)
    {
        // If ID is not passed, get the logged-in user's ID
        if ($id === null) {
            $id = session()->get('id');
        }

        $user = $this->product_model->find($id);

        if (!$user) {
            return redirect()->to(base_url('loginpage'));
        }

        $data = [
            'productimage'      => $user['product_image'] ?? '',
            'productname'       => $user['product_name'] ?? '',
            'productcode'       => $user['product_code'] ?? '',
            'category'          => $user['category'] ?? '',
            'status'            => $user['status'] ?? '',
            'price'             => $user['price'] ?? '',
            'quantity'          => $user['quantity'] ?? '',
            'id'                => $id,
        ];

        return view('viewproductpage', $data);
    }
    
    public function updateproduct($id)
    {
        $file = $this->request->getFile('product_image');

        if (!$file || !$file->isValid()) {
            return $this->response->setJSON([
                'status' => false,
                'message' => 'Please select a valid photo'
            ]);
        }

        $newName = $file->getRandomName();
        $file->move(FCPATH . 'uploads', $newName);
        $data = [
            'product_image' => $newName
        ];
        $username = session()->get('username');

        $result = $this->product_model->updateproduct($data, $id, $username);
       
        if ($result) {
            return $this->response->setJSON([
                'status' => true,
                'photo'  => $newName
            ]);
        }

        return $this->response->setJSON([
            'status' => false,
            'message' => 'Photo update failed'
        ]);
    }
    
}