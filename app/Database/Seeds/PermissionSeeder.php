<?php

namespace App\Database\Seeds;

use CodeIgniter\Database\Seeder;
use CodeIgniter\Shield\Authorization\Groups;

class PermissionSeeder extends Seeder
{
    public function run()
    {
        // Inisialisasi Groups
        $groups = new Groups();

        // Daftar permissions yang akan dibuat
        $permissions = [
            ['name' => 'users.manage', 'description' => 'Mengizinkan pengelolaan data pengguna'],
            ['name' => 'roles.manage', 'description' => 'Mengizinkan pengelolaan peran pengguna'],
            ['name' => 'products.manage', 'description' => 'Mengizinkan pengelolaan produk'],
            ['name' => 'customers.manage', 'description' => 'Mengizinkan pengelolaan data pelanggan'],
            ['name' => 'categories.manage', 'description' => 'Mengizinkan pengelolaan kategori produk'],
            ['name' => 'orders.manage', 'description' => 'Mengizinkan pengelolaan pesanan'],
            ['name' => 'transactions.manage', 'description' => 'Mengizinkan pengelolaan transaksi'],
            ['name' => 'shipments.manage', 'description' => 'Mengizinkan pengelolaan pengiriman'],
            ['name' => 'stocks.manage', 'description' => 'Mengizinkan pengelolaan stok barang'],
        ];

        // Ambil konfigurasi permissions yang ada
        $existingPermissions = setting('AuthGroups.permissions') ?? [];

        // Gabungkan permissions baru dengan yang sudah ada
        foreach ($permissions as $permission) {
            $existingPermissions[$permission['name']] = $permission['description'];
        }

        // Simpan konfigurasi permissions yang baru
        setting('AuthGroups.permissions', $existingPermissions);

        echo "Permissions telah berhasil ditambahkan ke database.\n";
    }
}
