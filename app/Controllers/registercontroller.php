<?php
	namespace App\Controllers;
	use App\Models\user_model;
	class Registercontroller extends BaseController {
		public function __construct() {
			$this->user_model = new user_model();
		}
        public function index(){
            return view('registerview');
        }
        
        public function insertregistereddata(){
            $fullname=$this->request->getPost('fullname');
            $username=$this->request->getPost('username');
            $email=$this->request->getPost('email');
            $mobilenumber=$this->request->getPost('mobilenumber');
            $password=$this->request->getPost('password');
            $confirmpassword=$this->request->getPost("confirmpassword");
            $role=$this->request->getPost('role');
            $photo=$this->request->getFile('photo');
            
            if (empty($fullname)|| empty($username) || empty($email) || empty($mobilenumber) || empty($password) || empty($confirmpassword)
                || empty($role)){
                return $this->response->setJSON([
                    'status'=>false,
                    'message'=>'All field are required',
                ]);
            }
            if (!$photo || !$photo->isValid()) {
                return $this->response->setJSON([
                    'status' => false,
                    'message' => 'Please upload a valid image'
                ]);
            }
            if ($password !== $confirmpassword) {
                return $this->response->setJSON([
                    'status' => false,
                    'message' => 'Password and confirm password do not match'
                ]);
            }
            if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
                return $this->response->setJSON([
                    'status'=>false,
                    'message'=>'Invalid mail',
                ]);
            }
            if ($role !== 'Employee') {
                return $this->response->setJSON([
                    'status' => false,
                    'message' => 'Public registration is only allowed for employees.'
                ]);
            } 
            $existingUser = $this->user_model->checkuser($username, $email);
            if ($existingUser) {
                if ($existingUser['username'] == $username) {
                    return $this->response->setJSON([
                        'status' => false,
                        'message' => 'Username already exists'
                    ]);
                }

                if ($existingUser['email'] == $email) {
                    return $this->response->setJSON([
                        'status' => false,
                        'message' => 'Email already exists'
                    ]);
                }
            }
            $allowedExtensions = ['jpg', 'jpeg', 'png'];
            $extension = strtolower($photo->getClientExtension());

            if (!in_array($extension, $allowedExtensions, true)) {
                return $this->response->setJSON([
                    'status' => false,
                    'message' => 'Only JPG, JPEG and PNG images are allowed'
                ]);
            }
            $mimeType = $photo->getMimeType();

            $allowedMimeTypes = ['image/jpeg', 'image/png'];

            if (!in_array($mimeType, $allowedMimeTypes, true)) {
                return $this->response->setJSON([
                    'status' => false,
                    'message' => 'The uploaded file must be a JPG, JPEG or PNG image'
                ]);
            }

            if ($photo->getSizeByUnit('mb') > 2) {
                return $this->response->setJSON([
                    'status' => false,
                    'message' => 'Image size must not exceed 2 MB'
                ]);
            }
            $photoname=$photo->getRandomName();
            $photo->move(FCPATH . 'uploads', $photoname); //images ->included to the project folder 
            
            $data=[
                'full_name'=>$fullname,
                'username'=>$username,
                'email'=>$email,
                'mobile'=>$mobilenumber,
                'password'=> password_hash($password, PASSWORD_DEFAULT),
                'role'=>$role,
                'profile_image'=> $photoname,
                'updated_at' => date('Y-m-d H:i:s'),
                'created_at' => date('Y-m-d H:i:s'),
                'created_by' => $username,
            ];

            $result= $this->user_model->insertdataofuser($data);
            if ($result){
                return $this->response->setJSON([
                    'status'=> true,
                    'message'=> 'Registration Successfull',
                ]);
            }
            return $this->response->setJSON([
                'status'=>false,
                'message'=>'Registration Failed',
            ]);
            

        }
        // public function checkavailability()
        // {
        //     $username = $this->request->getPost('username');
        //     $email = $this->request->getPost('email');

        //     $user = $this->user_model->checkuser($username, $email);

        //     return $this->response->setJSON([
        //         'usernameExists' => !empty($user) && $user['username'] == $username,
        //         'emailExists' => !empty($user) && $user['email'] == $email
        //     ]);
        // }
	}