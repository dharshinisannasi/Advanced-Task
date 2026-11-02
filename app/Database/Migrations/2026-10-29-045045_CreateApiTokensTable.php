<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class CreateApiTokensTable extends Migration
{
    public function up()
    {
        $this->forge->addField([
        'id' => [
            'type'           => 'INT',
            'unsigned'       => true,
            'auto_increment' => true,
        ],
        'user_id' => [
            'type'     => 'INT',
            'unsigned' => true,
        ],
        'role' => [
            'type'       => 'VARCHAR',
            'constraint' => 20,
        ],
        'token_hash' => [
            'type'       => 'VARCHAR',
            'constraint' => 64,
        ],
        'expires_at' => [
            'type' => 'DATETIME',
        ],
        'created_at' => [
            'type' => 'DATETIME',
        ],
    ]);

    $this->forge->addKey('id', true);
    $this->forge->addUniqueKey('token_hash');
    $this->forge->createTable('api_tokens');
    }

    public function down()
    {
        $this->forge->dropTable('api_tokens');
    }
}
