<?php
namespace App\Controllers;
use App\Models\user_model;
class Logout extends BaseController {
    public function index(){
        $session= session();
        $session->destroy();
        return redirect()->to(base_url('loginpage'));
    }
}
        