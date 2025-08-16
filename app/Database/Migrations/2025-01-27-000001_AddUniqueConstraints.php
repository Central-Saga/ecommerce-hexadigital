<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class AddUniqueConstraints extends Migration
{
    public function up()
    {
        // Tambah constraint unik untuk nama_kategori
        $this->db->query("ALTER TABLE kategori ADD CONSTRAINT uk_nama_kategori UNIQUE (nama_kategori)");

        // Tambah constraint unik untuk nama produk
        $this->db->query("ALTER TABLE produk ADD CONSTRAINT uk_nama_produk UNIQUE (nama)");
    }

    public function down()
    {
        // Hapus constraint unik
        $this->db->query("ALTER TABLE kategori DROP CONSTRAINT uk_nama_kategori");
        $this->db->query("ALTER TABLE produk DROP CONSTRAINT uk_nama_produk");
    }
}
