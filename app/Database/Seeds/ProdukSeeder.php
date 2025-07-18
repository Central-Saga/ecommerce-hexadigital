<?php

namespace App\Database\Seeds;

use CodeIgniter\Database\Seeder;

class ProdukSeeder extends Seeder
{
    public function run()
    {
        $data = [
            [
                'nama' => 'Laptop',
                'kategori_id' => 1,
                'harga' => 7000000,
                'stok' => 10,
                'deskripsi' => 'Laptop berkualitas tinggi',
                'gambar' => 'laptop.jpg'
            ],
            [
                'nama' => 'Kaos',
                'kategori_id' => 2,
                'harga' => 100000,
                'stok' => 50,
                'deskripsi' => 'Kaos nyaman dipakai',
                'gambar' => 'kaos.jpg'
            ],
            [
                'nama' => 'Keripik',
                'kategori_id' => 3,
                'harga' => 20000,
                'stok' => 100,
                'deskripsi' => 'Keripik renyah dan gurih',
                'gambar' => 'keripik.jpg'
            ],
        ];

        $this->db->table('produk')->insertBatch($data);
    }
}
