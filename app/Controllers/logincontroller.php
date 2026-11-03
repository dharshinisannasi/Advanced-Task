<?php
	namespace App\Controllers;
	use App\Models\user_model;
    use CodeIgniter\API\ResponseTrait;
	class Logincontroller extends BaseController {
        use ResponseTrait;
		public function __construct() {
			$this->user_model = new user_model();
		}
        public function index(){
            return view('loginview');
        }
        public function getloginuser(){
            $username= $this->request->getPost('username');
            $password= $this->request->getPost('password');
            if (empty($username) || empty($password)){
                return $this->response->setJson([
                    'status'=> false,
                    'message'=> 'All fields should be filed',
                ]);
            }
            $result= $this->user_model->getuserdata($username);
            if (!$result) {
                return $this->response->setJSON([
                    'status' => false,
                    'message' => 'User not found'
                ]);
            }

            if (!password_verify($password, $result['password'])) {
                return $this->response->setJSON([
                    'status' => false,
                    'message'=>'Password incorrect'
                ]);
            }
            if ($result['status'] !== 'Active') {
                return $this->response->setJSON([
                    'status' => false,
                    'message' => 'Your account has been deactivated.'
                ]);
            }
            $session= session();
            $session->set([
                'id'            =>$result['id'],
                'username'      =>$result['username'],
                'email'         =>$result['email'],
                'mobilenumber'  =>$result['mobile'],
                'role'          =>$result['role'],
                'fullname'      =>$result['full_name'],
                'photo'         =>$result['profile_image'],
                'status'        =>$result['status'],
                'LoggedIn'      => true  
            ]);
            // Generate a secure API token
            $token = bin2hex(random_bytes(32));

            $this->user_model->insertapitoken([
                'user_id'    => $result['id'],
                'role'       => $result['role'],
                'token_hash' => hash('sha256', $token),
                'expires_at' => date('Y-m-d H:i:s', time() + 3600),
                'created_at' => date('Y-m-d H:i:s'),
            ]);
            return $this->response->setJSON([
                'status'  => true,
                'message' => 'Login successful',
                'token'   => $token,
                'role'    => $result['role'],
                'username' => $result['username']
                
            ]);
        }
    }
                