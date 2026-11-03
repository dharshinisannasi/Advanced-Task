<?php
namespace App\Models;

use CodeIgniter\Model;
use Config\Database;

class Productapi_model extends Model
{
    protected $table = 'products';
    protected $primaryKey = 'id';

    protected $allowedFields = [
        'product_code',
        'product_name',
        'category',
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

    // Verify API token and return the active user
    public function authenticateToken($token)
    {
        $db = Database::connect();

        $tokenData = $db->table('api_tokens')
            ->where('token_hash', hash('sha256', $token))
            ->where('expires_at >', date('Y-m-d H:i:s'))
            ->get()
            ->getRowArray();

        if (!$tokenData) {
            return null;
        }

        $user = $db->table('users')
            ->where('id', $tokenData['user_id'])
            ->get()
            ->getRowArray();

        if (!$user || ($user['status'] ?? '') !== 'Active') {
            return null;
        }

        return $user;
    }

    // Get one product
    public function getProductById($id)
    {
        return $this->find($id);
    }

    // Get product filter options for the logged-in user
    public function getFilterOptions($user)
    {
        $builder = $this->builder();

        if ($user['role'] === 'Employee') {
            $builder->where('created_by', $user['username']);
        }

        $products = $builder->get()->getResultArray();

        $productNames = [];
        $productCodes = [];
        $dates = [];
        $categories = [];
        $statuses = [];
        $users = [];

        foreach ($products as $product) {
            if (!empty($product['product_name'])) {
                $productNames[] = $product['product_name'];
            }

            if (!empty($product['product_code'])) {
                $productCodes[] = $product['product_code'];
            }

            if (!empty($product['created_at'])) {
                $dates[] = date(
                    'Y-m-d',
                    strtotime($product['created_at'])
                );
            }

            if (!empty($product['category'])) {
                $categories[] = $product['category'];
            }

            if (!empty($product['status'])) {
                $statuses[] = $product['status'];
            }

            if (
                $user['role'] === 'Admin' &&
                !empty($product['created_by'])
            ) {
                $users[] = $product['created_by'];
            }
        }

        return [
            'users' => array_values(array_unique($users)),
            'product_names' => array_values(array_unique($productNames)),
            'product_codes' => array_values(array_unique($productCodes)),
            'dates' => array_values(array_unique($dates)),
            'categories' => array_values(array_unique($categories)),
            'statuses' => array_values(array_unique($statuses))
        ];
    }

    // Return products with search, filters, sorting and pagination
    public function getProductsForApi($user, $filters)
    {
        $builder = $this->builder();

        if ($user['role'] === 'Employee') {
            $builder->where('created_by', $user['username']);
        }

        if (!empty($filters['search'])) {
            $search = $filters['search'];

            $builder->groupStart()
                ->like('product_code', $search)
                ->orLike('product_name', $search)
                ->orLike('category', $search)
                ->orLike('description', $search)
                ->orLike('status', $search)
                ->orLike('created_by', $search)
                ->orLike('price', $search)
                ->orLike('quantity', $search)
                ->groupEnd();
        }

        // Only Admin can filter by another user's products
        if (
            $user['role'] === 'Admin' &&
            !empty($filters['created_by'])
        ) {
            $builder->where('created_by', $filters['created_by']);
        }

        foreach ([
            'product_name',
            'product_code',
            'category',
            'status'
        ] as $field) {
            if (!empty($filters[$field])) {
                $builder->where($field, $filters[$field]);
            }
        }

        if (!empty($filters['date'])) {
            $builder->where(
                'DATE(created_at)',
                $filters['date']
            );
        }

        $totalRecords = $builder->countAllResults(false);

        $page = max(1, (int) ($filters['page'] ?? 1));
        $limit = (int) ($filters['limit'] ?? 10);

        if (!in_array($limit, [5, 10, 20, 50], true)) {
            $limit = 10;
        }

        $allowedSortColumns = [
            'id',
            'product_code',
            'product_name',
            'category',
            'price',
            'quantity',
            'status',
            'created_at'
        ];

        $sortBy = $filters['sort_by'] ?? 'id';
        $order = strtoupper($filters['order'] ?? 'DESC');

        if (!in_array($sortBy, $allowedSortColumns, true)) {
            $sortBy = 'id';
        }

        if (!in_array($order, ['ASC', 'DESC'], true)) {
            $order = 'DESC';
        }

        $offset = ($page - 1) * $limit;

        $products = $builder
            ->orderBy($sortBy, $order)
            ->get($limit, $offset)
            ->getResultArray();

        return [
            'data' => $products,
            'pagination' => [
                'current_page' => $page,
                'per_page' => $limit,
                'total_records' => $totalRecords,
                'total_pages' => (int) ceil($totalRecords / $limit)
            ]
        ];
    }
}
