<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class FixPemesananTotalHarga extends Migration
{
    public function up()
    {
        // Update semua record yang memiliki total_harga null menjadi 0
        $this->db->query("UPDATE pemesanan SET total_harga = 0 WHERE total_harga IS NULL");

        // Pastikan kolom total_harga tidak boleh null
        $this->forge->modifyColumn('pemesanan', [
            'total_harga' => [
                'type'       => 'DECIMAL',
                'constraint' => '15,2',
                'default'    => 0,
                'null'       => false,
            ],
        ]);
    }

    public function down()
    {
        // Revert perubahan jika diperlukan
        $this->forge->modifyColumn('pemesanan', [
            'total_harga' => [
                'type'       => 'DECIMAL',
                'constraint' => '15,2',
                'default'    => 0,
                'null'       => true,
            ],
        ]);
    }
}
