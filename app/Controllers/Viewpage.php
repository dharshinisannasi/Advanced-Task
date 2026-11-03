<?php
namespace App\Controllers;

use App\Models\user_model;

class Viewpage extends BaseController
{
    protected $user_model;

    public function __construct()
    {
        $this->user_model = new user_model();
    }

    public function index($id = null)
    {
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
                'status'  => false,
                'message' => 'Please select a valid photo.'
            ]);
        }

        // Allow only JPEG and PNG images
        $mimeType = $file->getMimeType();

        if (!in_array($mimeType, ['image/jpeg', 'image/png'])) {
            return $this->response->setJSON([
                'status'  => false,
                'message' => 'Only JPG and PNG images are allowed.'
            ]);
        }

        // Check file size: maximum 2 MB
        if ($file->getSize() > 2 * 1024 * 1024) {
            return $this->response->setJSON([
                'status'  => false,
                'message' => 'Image size must be 2 MB or less.'
            ]);
        }

        $uploadPath = FCPATH . 'uploads';

        if (!is_dir($uploadPath)) {
            mkdir($uploadPath, 0755, true);
        }

        $newName = $file->getRandomName();

        if (!$file->move($uploadPath, $newName)) {
            return $this->response->setJSON([
                'status'  => false,
                'message' => 'Unable to upload the photo.'
            ]);
        }

        $data = [
            'profile_image' => $newName
        ];

        $result = $this->user_model->updateuser($data, $id);

        if (!$result) {
            return $this->response->setJSON([
                'status'  => false,
                'message' => 'Photo could not be updated in the database.'
            ]);
        }

        return $this->response->setJSON([
            'status' => true,
            'photo'  => $newName
        ]);
    }
}
