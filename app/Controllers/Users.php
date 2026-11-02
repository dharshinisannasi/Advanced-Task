<?php
namespace App\Controllers;
use App\Models\user_model;
class Users extends BaseController
{
    public function index()
    {
        $session = session();

        if (
            session()->get('LoggedIn') !== true ||
            session()->get('role') !== 'Admin'
        ) {
            return redirect()->to(base_url('loginpage'));
        }
        return view('users');
    }

    
    public function getallusers()
    {
        $session = session();

        if (
            session()->get('LoggedIn') !== true ||
            session()->get('role') !== 'Admin'
        ) {
            return $this->response
                ->setStatusCode(403)
                ->setJSON([
                    'status' => false,
                    'message' => 'Unauthorized access'
                ]);
        }


        $model = new user_model();
        $users = $model->getAllUsers();

        return $this->response->setJSON([
            'status' => true,
            'users' => $users
        ]);
    }

    public function selectuserdata()
    {
        $session = session();

        if (
            session()->get('LoggedIn') !== true ||
            session()->get('role') !== 'Admin'
        ) {
            return $this->response
                ->setStatusCode(403)
                ->setJSON([
                    'status' => false,
                    'message' => 'Unauthorized access'
                ]);
        }

        $page = (int) $this->request->getPost('page');
        $limit = $this->request->getPost('limit') ?? '10';

        $model = new user_model();
        $result = $model->selectallusers($page, $limit);

        return $this->response->setJSON([
            'status' => true,
            'data' => $result['data'],
            'totalpages' => $result['totalpages'],
            'totalrecords' => $result['totalrecords']
        ]);
    }

}
