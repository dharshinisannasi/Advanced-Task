<?php
namespace App\Controllers;

use App\Models\user_model;

class Viewpage extends BaseController
{
    public function __construct()
    {
        $this->user_model = new user_model();
    }

    public function index($id = null)
    {
        // If ID is not passed, get the logged-in user's ID
        if ($id === null) {
            $id = session()->get('id');
        }

        $user = $this->user_model->find($id);

        if (!$user) {
            return redirect()->to(base_url('loginpage'));
        }

        $data = [
            'profileimage' => $user['profile_image'] ?? '',
            'username'     => $user['username'] ?? '',
            'fullname'     => $user['full_name'] ?? '',
            'role'         => $user['role'] ?? '',
            'status'       => $user['status'] ?? '',
            'mobilenumber' => $user['mobile'] ?? '',
            'email'        => $user['email'] ?? '',
            'id'           => $id,
        ];

        return view('viewpage', $data);
    }
    
    public function updatephoto($id)
    {
        $file = $this->request->getFile('profileimage');

        if (!$file || !$file->isValid()) {
            return $this->response->setJSON([
                'status' => false,
                'message' => 'Please select a valid photo'
            ]);
        }

        $newName = $file->getRandomName();
        $file->move(FCPATH . 'uploads', $newName);
        $data = [
            'profile_image' => $newName
        ];

        $this->user_model->updateuser($data, $id);
        return $this->response->setJSON([
            'status' => true,
            'photo' => $newName
        ]);
    }
    
}