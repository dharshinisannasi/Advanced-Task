<?php
	namespace App\Controllers;
	use App\Models\user_model;
	class Adminregistercontroller extends BaseController {
		public function __construct() {
			$this->user_model = new user_model();
		}
        public function index(){
            if (
                session()->get('LoggedIn') !== true ||
                session()->get('role') !== 'Admin'
            ) {
                return redirect()->to(base_url('loginpage'));
            }

            return view('adminregisterview');
        }
        
        public function insertadmindata(){
            if (
                session()->get('LoggedIn') !== true ||
                session()->get('role') !== 'Admin'
            ) {
                return $this->response->setJSON([
                    'status' => false,
                    'message' => 'Unauthorized access'
                ]);
            }
            $fullname=$this->request->getPost('fullname');
            $username=$this->request->getPost('username');
            $email=$this->request->getPost('email');
            $mobilenumber=$this->request->getPost('mobilenumber');
            $password=$this->request->getPost('password');
            $confirmpassword=$this->request->getPost("confirmpassword");
            $photo=$this->request->getFile('photo');
            
            if (empty($fullname)|| empty($username) || empty($email) || empty($mobilenumber) || empty($password) || empty($confirmpassword)){
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
                'role'=> 'Admin',
                'profile_image'=> $photoname,
                'updated_at' => date('Y-m-d H:i:s'),
                'created_at' => date('Y-m-d H:i:s'),
                'created_by' => session()->get('username')
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
	}