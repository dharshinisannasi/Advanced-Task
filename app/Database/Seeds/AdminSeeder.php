<?php
namespace App\Database\Seeds;

use CodeIgniter\Database\Seeder;

class AdminSeeder extends Seeder
{
    public function run()
    {
        $admin = $this->db->table('users')
            ->where('role', 'Admin')
            ->countAllResults();

        if ($admin > 0) {
            echo "Admin account already exists. Skipping insertion.\n";
            return;
        }

        echo "No Admin account found. Please create one through the registration process.\n";
    }
}
