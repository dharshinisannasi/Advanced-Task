<?php
namespace App\Database\Seeds;

use CodeIgniter\Database\Seeder;

class CategorySeeder extends Seeder
{
    public function run()
    {
        $categories = [
            [
                'category_name' => 'Electronics',
                'status'        => 'Active',
            ],
            [
                'category_name' => 'Clothing',
                'status'        => 'Active',
            ],
        ];

        foreach ($categories as $category) {
            $exists = $this->db->table('categories')
                ->where('category_name', $category['category_name'])
                ->countAllResults();

            if ($exists == 0) {
                $category['created_at'] = date('Y-m-d H:i:s');
                $category['updated_at'] = date('Y-m-d H:i:s');

                $this->db->table('categories')->insert($category);
            }
        }
    }
}
