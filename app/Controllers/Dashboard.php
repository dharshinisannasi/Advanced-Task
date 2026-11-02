<?php
namespace App\Controllers;
use App\Models\Product_model;
class Dashboard extends BaseController
{
    public function index()
    {
        $session = session();

        if (!$session->get('LoggedIn')) {
            return redirect()->to(base_url('loginpage'));
        }

        $username = $session->get('username');
        $role = $session->get('role');
        

        $productModel = new Product_model();

        if ($role == "Employee") {
            $product_quantity = $productModel->getEmployeeProductQuantity($username);
            $product_count = $productModel->getEmployeeProductCount($username);
        } else {
            $product_quantity = $productModel->getTotalProductQuantity();
            $product_count = $productModel->countAll();
            
        }
        $data = [
            'MobileNumber' => $session->get('mobilenumber'),
            'Username' => $username,
            'Email' => $session->get('email'),
            'Role' => $role,
            'Photo' => $session->get('photo'),
            'product_quantity' => $product_quantity,
            'product_count' => $product_count
        ];
        $userModel = new \App\Models\user_model();

        $data['totalEmployees'] = $userModel->getTotalEmployees();
        $data['activeUsers'] = $userModel->getActiveUsers();
        $data['inactiveUsers'] = $userModel->getInactiveUsers();

        return view('dashboardview', $data);
    }
    
}
