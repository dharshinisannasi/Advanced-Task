<?php
namespace App\Models;
use CodeIgniter\Model;
 
class product_model extends Model
{
    protected $table   = 'products';
    protected $allowedFields = [
        'product_code',
        'category',
        'product_name',
        'description',
        'price',
        'quantity',
        'product_image',
        'status',
        'role',
        'created_by',
        'created_at',
        'updated_at'
    ];
 
    public function __construct()
    {
        parent::__construct();
        $this->db = \Config\Database::connect();
    }
    //insert the employee product
    public function insertemployeeproduct($data){
        $builder=$this->db->table('products');
        $result=$builder->insert($data);
        return $result;
    }
    //select employee data for table display
    public function selectemployeeproduct($page = 0, $limit = 'all', $searchuser = '',$name='',$status='',$date='', $code= '', $category='')
    {
        $builder = $this->db->table('products');
        $builder->where('role', 'Employee');

        if ($searchuser !== '') {
            $builder->groupStart()
                ->Like('product_code', $searchuser)
                ->orlike('product_name', $searchuser)
                ->orLike('category', $searchuser)
                ->orLike('description', $searchuser)
                ->orLike('price', $searchuser)
                ->orLike('quantity', $searchuser)
                ->orLike('status', $searchuser)
                ->orLike('created_by', $searchuser)
                ->orLike('created_at', $searchuser)
                ->groupEnd();
        }
        if ($name !== '') {
            $builder->where('product_name', $name);
        }
        if ($status !== '') {
            $builder->where('status', $status);
        }
        if ($code !== '') {
            $builder->where('product_code', $code);
        }
        if ($category !== '') {
            $builder->where('category', $category);
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
            'id, product_code, product_name, category, description, price, quantity, status, created_at, updated_at, role, created_by'
        );

        $data = $builder->get()->getResultArray();

        return [
            'totalrecords' => $totalrecords,
            'totalpages'   => $totalpages,
            'limit'        => $limit,
            'data'         => $data
        ];
    }
    
    public function getemployeeproductsforexport(
        $searchuser = '',
        $name = '',
        $status = '',
        $date = '',
        $code = '',
        $category = '',
        $username = ''
    ) {
        $builder = $this->db->table('products');

        // Get only the logged-in employee's products
        $builder->where('role', 'Employee');
        $builder->where('created_by', $username);

        // Search filter
        if ($searchuser !== '') {
            $builder->groupStart()
                ->like('product_code', $searchuser)
                ->orLike('product_name', $searchuser)
                ->orLike('category', $searchuser)
                ->orLike('description', $searchuser)
                ->orLike('price', $searchuser)
                ->orLike('quantity', $searchuser)
                ->orLike('status', $searchuser)
                ->orLike('created_by', $searchuser)
                ->orLike('created_at', $searchuser)
                ->groupEnd();
        }

        // Product name filter
        if ($name !== '') {
            $builder->where('product_name', $name);
        }

        // Status filter
        if ($status !== '') {
            $builder->where('status', $status);
        }

        // Product code filter
        if ($code !== '') {
            $builder->where('product_code', $code);
        }

        // Category filter
        if ($category !== '') {
            $builder->where('category', $category);
        }

        // Date filter
        if ($date !== '') {
            $builder->where('created_at >=', $date . ' 00:00:00');
            $builder->where('created_at <=', $date . ' 23:59:59');
        }

        // Select product details including image
        $builder->select(
            'id, product_code, product_name, category, description,
            price, quantity, status, created_at, updated_at, role,
            created_by, product_image'
        );

        return $builder->get()->getResultArray();
    }

    //productcode dropdown
    public function getProductcode($createdBy)
    {
        return $this->db->table($this->table)
            ->select('product_code')
            ->where('created_by', $createdBy)
            ->where('role', 'Employee')
            ->distinct()
            ->orderBy('product_code', 'ASC')
            ->get()
            ->getResultArray();
    }
    //category dropdown
    public function getCategory($createdBy)
    {
        return $this->db->table($this->table)
            ->select('category')
            ->where('created_by', $createdBy)
            ->where('role', 'Employee')
            ->distinct()
            ->orderBy('category', 'ASC')
            ->get()
            ->getResultArray();
    }
    //productname dropdown
    public function getProduct($createdBy)
    {
        return $this->db->table($this->table)
            ->select('product_name')
            ->where('created_by', $createdBy)
            ->where('role', 'Employee')
            ->distinct()
            ->orderBy('product_name', 'ASC')
            ->get()
            ->getResultArray();
    }
    //status dropdown
    public function getstatusNames($createdBy)
    {
        return $this->db->table($this->table)
            ->select('status')
            ->where('created_by', $createdBy)
            ->where('role', 'Employee')
            ->distinct()
            ->orderBy('status', 'ASC')
            ->get()
            ->getResultArray();
    }
    //date dropdown
    public function getDate($createdBy)
    {
        return $this->db->table($this->table)
            ->select('DATE(created_at) AS created_at', false)
            ->where('created_by', $createdBy)
            ->where('role', 'Employee')
            ->distinct()
            ->orderBy('created_at', 'ASC')
            ->get()
            ->getResultArray();
    }
    //update employee data
    public function updateproduct($data, $id, $username)
    {
        $builder = $this->db->table('products');
        $builder->where('role', 'Employee')
                ->where('id', $id)
                ->where('created_by', $username);
                
        $query=$builder->update($data);

        return $query;
    }
    
    //delete employee product
    public function deleteproduct($id,$username)
    {
        $builder= $this->db->table('products');
        $builder->where('role', 'Employee')
            ->where('id', $id)
            ->where('created_by', $username);
        $query= $builder->delete();
        return $query;

    }
    //clear all employee data
    public function clearproduct($username)
    {
        $builder=$this->db->table('products');
        $builder->where('role', 'Employee')
                ->where('created_by', $username);
        $query=$builder->delete();
        return $query;
    }
    //check duplicate of product code
    public function checkProductCode($productcode)
    {
        return $this->db->table('products')
                        ->where('product_code', $productcode)
                        ->countAllResults() > 0;
    }


    //insert data by admin
    public function insertadminproduct($data){
        $builder=$this->db->table('products');
        $result=$builder->insert($data);
        return $result;
    }
    //select data for table display
    public function selectadminproduct($page = 0, $limit = 'all', $searchuser = '',$name='',$status='',$date='', $code= '', $category='', $username='')
    {
        $builder = $this->db->table('products');

        if ($searchuser !== '') {
            $builder->groupStart()
                ->Like('product_code', $searchuser)
                ->orlike('product_name', $searchuser)
                ->orLike('category', $searchuser)
                ->orLike('description', $searchuser)
                ->orLike('price', $searchuser)
                ->orLike('quantity', $searchuser)
                ->orLike('status', $searchuser)
                ->orLike('created_by', $searchuser)
                ->orLike('created_at', $searchuser)
                ->groupEnd();
        }
        if ($name !== '') {
            $builder->where('product_name', $name);
        }
        if ($status !== '') {
            $builder->where('status', $status);
        }
        if ($code !== '') {
            $builder->where('product_code', $code);
        }
        if ($category !== '') {
            $builder->where('category', $category);
        }
        if ($username !== '') {
            $builder->where('created_by', $username);
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
            'id, product_code, product_name, category, description, price, quantity, status, created_at, updated_at, role, created_by'
        );

        $data = $builder->get()->getResultArray();

        return [
            'totalrecords' => $totalrecords,
            'totalpages'   => $totalpages,
            'limit'        => $limit,
            'data'         => $data
        ];
    }

    public function getadminproductsforexport(
        $searchuser = '',
        $name = '',
        $status = '',
        $date = '',
        $code = '',
        $category = '',
        $username = ''
    ) {
        $builder = $this->db->table('products');

        if ($searchuser !== '') {
            $builder->groupStart()
                ->like('product_code', $searchuser)
                ->orLike('product_name', $searchuser)
                ->orLike('category', $searchuser)
                ->orLike('description', $searchuser)
                ->orLike('price', $searchuser)
                ->orLike('quantity', $searchuser)
                ->orLike('status', $searchuser)
                ->orLike('created_by', $searchuser)
                ->orLike('created_at', $searchuser)
                ->groupEnd();
        }

        if ($name !== '') {
            $builder->where('product_name', $name);
        }

        if ($status !== '') {
            $builder->where('status', $status);
        }

        if ($code !== '') {
            $builder->where('product_code', $code);
        }

        if ($category !== '') {
            $builder->where('category', $category);
        }

        if ($username !== '') {
            $builder->where('created_by', $username);
        }

        if ($date !== '') {
            $builder->where('created_at >=', $date . ' 00:00:00');
            $builder->where('created_at <=', $date . ' 23:59:59');
        }

        // Added product_image
        $builder->select(
            'id, product_code, product_name, category, description,
            price, quantity, status, created_at, updated_at,
            role, created_by, product_image'
        );

        return $builder->get()->getResultArray();
    }
    //product code dropdown
    public function getallProductcode()
    {
        return $this->db->table($this->table)
            ->select('product_code')
            ->distinct()
            ->orderBy('product_code', 'ASC')
            ->get()
            ->getResultArray();
    }
    //users dropdown
    public function getallUsers()
    {
        return $this->db->table($this->table)
            ->select('created_by')
            ->distinct()
            ->orderBy('created_by', 'ASC')
            ->get()
            ->getResultArray();
    }
    //category dropdown
    public function getallCategory()
    {
        return $this->db->table($this->table)
            ->select('category')
            ->distinct()
            ->orderBy('category', 'ASC')
            ->get()
            ->getResultArray();
    }
    //product name dropdown
    public function getallProduct()
    {
        return $this->db->table($this->table)
            ->select('product_name')
            ->distinct()
            ->orderBy('product_name', 'ASC')
            ->get()
            ->getResultArray();
    }
    //status dropdown
    public function getallstatusNames()
    {
        return $this->db->table($this->table)
            ->select('status')
            ->distinct()
            ->orderBy('status', 'ASC')
            ->get()
            ->getResultArray();
    }
    //date dropdown
    public function getallDate()
    {
        return $this->db->table($this->table)
            ->select('DATE(created_at) AS created_at', false)
            ->distinct()
            ->orderBy('created_at', 'ASC')
            ->get()
            ->getResultArray();
    }
    //update admin data
    public function updateallproduct($data, $id)
    {
        $builder = $this->db->table('products');

        $builder->where('id', $id);

        $query=$builder->update($data);

        return $query;
    }
    //delete admin data
    public function deleteallproduct($id)
    {
        $builder= $this->db->table('products');
        $builder->where('id',$id);
        $query= $builder->delete();
        return $query;

    }
    //clear all data
    public function clearallproduct()
    {
        $builder=$this->db->table('products');
        $query=$builder->delete();
        return $query;
    }
    //employee product count for dashboard
    public function getEmployeeProductCount($username)
    {
        return $this->db->table('products')
            ->where('role', 'Employee')
            ->where('created_by', $username)
            ->countAllResults();
    }
    //employee product quantity for dashboard
    public function getEmployeeProductQuantity($username)
    {
        return $this->where('role', 'Employee')
                    ->where('created_by', $username)
                    ->selectSum('quantity')
                    ->first()['quantity'] ?? 0;
    }
    //all product quantity for dashboard
    public function getTotalProductQuantity()
    {
        return $this->selectSum('quantity')
                    ->first()['quantity'] ?? 0;
    }
}