<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class Migration_2024_03_21_000003_create_pelanggans_table extends Migration
{
    public function up()
    {
        $this->forge->addField([
            'id' => [
                'type'           => 'INT',
                'constraint'     => 11,
                'unsigned'       => true,
                'auto_increment' => true,
            ],
            'user_id' => [
                'type'       => 'INT',
                'constraint' => 11,
                'unsigned'   => true,
            ],
            'alamat' => [
                'type'       => 'TEXT',
                'null'       => false,
            ],
            'no_telepon' => [
                'type'       => 'VARCHAR',
                'constraint' => 15,
                'null'       => false,
            ],
            'jenis_kelamin' => [
                'type'       => 'ENUM',
                'constraint' => ['L', 'P'],
                'null'       => false,
            ],
            'umur' => [
                'type'       => 'INT',
                'constraint' => 3,
                'null'       => false,
            ],
            'status' => [
                'type'       => 'ENUM',
                'constraint' => ['active', 'inactive'],
                'default'    => 'active',
                'null'       => false,
            ],
            'created_at' => [
                'type' => 'DATETIME',
                'null' => true,
            ],
            'updated_at' => [
                'type' => 'DATETIME',
                'null' => true,
            ],
            'deleted_at' => [
                'type' => 'DATETIME',
                'null' => true,
            ],
        ]);

        $this->forge->addKey('id', true);
        // Removed foreign key constraint to users table
        $this->forge->createTable('pelanggans');
    }

    public function down()
    {
        $this->forge->dropTable('pelanggans');
    }
}
