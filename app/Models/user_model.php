<?php
namespace App\Models;
use CodeIgniter\Model;
use CodeIgniter\HTTP\RedirectResponse;
 
class user_model extends Model
{
    protected $table   = 'users';
 
    public function __construct()
    {
        parent::__construct();
        $this->db = \Config\Database::connect();
    }
    //insert data to the db
    public function insertdataofuser($data){
        $builder=$this->db->table('users');
        $result=$builder->insert($data);
        return $result;
    }
    //check the email and username already exists when registering new one
    public function checkuser($username, $email)
    {
        $builder = $this->db->table('users');

        if (!empty($username)) {
            $builder->where('username', $username);
        }

        if (!empty($email)) {
            $builder->orWhere('email', $email);
        }

        return $builder->get()->getRowArray();
    }
    //get users data for login page to access
    public function getuserdata($username)
    {
        $builder=$this->table('users');
        $query= $builder->select('*')
                        ->where('username', $username)
                        ->get()->getRowArray();
        return $query;
    }
    
    //select the employee details from db
    public function selectemployee($page = 0, $limit = 'all', $searchuser = '',$username='',$status='',$date='')
    {
        $builder = $this->db->table('users');
        $builder->where('role', 'Employee');

        if ($searchuser !== '') {
            $builder->groupStart()
                ->like('full_name', $searchuser)
                ->orLike('username', $searchuser)
                ->orLike('email', $searchuser)
                ->orLike('mobile', $searchuser)
                ->orLike('status', $searchuser)
                ->orLike('created_by', $searchuser)
                ->orLike('created_at', $searchuser)
                ->groupEnd();
        }
        if ($username !== '') {
            $builder->where('username', $username);
        }
        if ($status !== '') {
            $builder->where('status', $status);
        }

        if (!empty($date)) {
            $builder->where('created_at >=', $date . ' 00:00:00');
            $builder->where('created_at <=', $date . ' 23:59:59');
        }
        $totalrecords = $builder->countAllResults(false);

        if ($limit !== 'all') {
            $limit = (int) $limit;

            if (!in_array($limit, [5, 10], true)) {
                $limit = 5;
            }

            $builder->limit($limit, max(0, (int) $page) * $limit);
            $totalpages = (int) ceil($totalrecords / $limit);
        } else {
            $totalpages = 1;
        }

        $builder->select(
            'id, full_name, username, email, mobile, role, status, created_at, updated_at, created_by'
        );

        $data = $builder->get()->getResultArray();

        return [
            'totalrecords' => $totalrecords,
            'totalpages'   => $totalpages,
            'limit'        => $limit,
            'data'         => $data
        ];
    }
    //username dropdown
    public function getuserNames()
    {
        return $this->db->table($this->table)
            ->select('username')
            ->where('role', 'Employee')
            ->distinct()
            ->orderBy('username', 'ASC')
            ->get()
            ->getResultArray();
    }
    //status dropdown
    public function getstatusNames()
    {
        return $this->db->table($this->table)
            ->select('status')
            ->where('role', 'Employee')
            ->distinct()
            ->orderBy('status', 'ASC')
            ->get()
            ->getResultArray();
    }
    //date dropdown
    public function getDate()
    {
        return $this->db->table($this->table)
            ->select('DATE(created_at) AS created_at', false)
            ->where('role', 'Employee')
            ->distinct()
            ->orderBy('created_at', 'ASC')
            ->get()
            ->getResultArray();
    }
    //update the employee data for employeemanagementpage
    public function updateuser($data, $id)
    {
        $builder = $this->db->table('users');

        $builder->where('id', $id);

        $query=$builder->update($data);

        return $query;
    }
    //delete the employee data for employeemanagementpage
    public function deleteuser($id)
    {
        $builder= $this->db->table('users');
        $builder->where('id',$id);
        $query= $builder->delete();
        return $query;

    }
    //clear all the employee data for employeemanagementpage
    public function cleardata()
    {
        $builder=$this->db->table('users');
        $builder->where('role', 'Employee');
        $query=$builder->delete();
        return $query;
    }
    //get all users for the userspage
    public function getAllUsers()
    {
        return $this->db->table('users')
            ->get()
            ->getResultArray();
    }
    //select the users data for the userspage
    public function selectallusers($page = 0, $limit = 10)
    {
        $builder = $this->db->table('users');

        $totalrecords = $builder->countAllResults(false);

        $builder = $this->db->table('users');

        if ($limit !== 'all') {
            $limit = (int) $limit;
            $builder->limit($limit, $page * $limit);
            $totalpages = ceil($totalrecords / $limit);
        } else {
            $totalpages = 1;
        }

        $data = $builder->get()->getResultArray();

        return [
            'data' => $data,
            'totalrecords' => $totalrecords,
            'totalpages' => $totalpages
        ];
    }
    //for checking the total number of employees 
    public function getTotalEmployees()
    {
        return $this->db->table('users')
            ->where('role', 'Employee')
            ->countAllResults();
    }
    //for checking the number of active users 
    public function getActiveUsers()
    {
        return $this->db->table('users')
            ->where('role', 'Employee')
            ->where('status', 'Active')
            ->countAllResults();
    }
    //for checking the number of inactive users 
    public function getInactiveUsers()
    {
        return $this->db->table('users')
            ->where('role', 'Employee')
            ->where('status', 'Inactive')
            ->countAllResults();
    }


    public function getemployeeforExport(
        $searchuser = '',
        $username = '',
        $status = '',
        $date = ''
    ) {
        $builder = $this->db->table('users');

        $builder->where('role', 'Employee');

        if ($searchuser !== '') {
            $builder->groupStart()
                ->like('full_name', $searchuser)
                ->orLike('username', $searchuser)
                ->orLike('email', $searchuser)
                ->orLike('mobile', $searchuser)
                ->orLike('status', $searchuser)
                ->orLike('created_by', $searchuser)
                ->orLike('created_at', $searchuser)
                ->groupEnd();
        }

        if ($username !== '') {
            $builder->where('username', $username);
        }

        if ($status !== '') {
            $builder->where('status', $status);
        }

        if ($date !== '') {
            $builder->where('created_at >=', $date . ' 00:00:00');
            $builder->where('created_at <=', $date . ' 23:59:59');
        }

        $builder->select(
            'id, full_name, username, email, mobile, role,
            status, created_at, created_by, updated_at'
        );

        return $builder->get()->getResultArray();
    }
}