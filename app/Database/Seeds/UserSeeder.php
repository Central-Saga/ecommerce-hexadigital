<?php

namespace App\Database\Seeds;

use CodeIgniter\Database\Seeder;
use CodeIgniter\Shield\Entities\User as ShieldUser;

class UserSeeder extends Seeder
{
    public function run()
    {
        $users = auth()->getProvider();

        // Data user yang ingin di-seed
        $data = [
            [
                'username' => 'admin',
                'email'    => 'admin@example.com',
                'password' => 'admin1234',
                'active'   => 1,
                'role'     => 'admin'
            ],
            [
                'username' => 'pegawai',
                'email'    => 'pegawai@example.com',
                'password' => 'pegawai1234',
                'active'   => 1,
                'role'     => 'pegawai'
            ],
            [
                'username' => 'pelanggan',
                'email'    => 'pelanggan@example.com',
                'password' => 'pelanggan1234',
                'active'   => 1,
                'role'     => 'pelanggan'
            ],
            [
                'username' => 'pelanggan2',
                'email'    => 'pelanggan2@example.com',
                'password' => 'pelanggan1234',
                'active'   => 1,
                'role'     => 'pelanggan'
            ],
            [
                'username' => 'pelanggan3',
                'email'    => 'pelanggan3@example.com',
                'password' => 'pelanggan1234',
                'active'   => 1,
                'role'     => 'pelanggan'
            ],
            [
                'username' => 'pelanggan4',
                'email'    => 'pelanggan4@example.com',
                'password' => 'pelanggan1234',
                'active'   => 1,
                'role'     => 'pelanggan'
            ],
            [
                'username' => 'pelanggan5',
                'email'    => 'pelanggan5@example.com',
                'password' => 'pelanggan1234',
                'active'   => 1,
                'role'     => 'pelanggan'
            ],
        ];

        foreach ($data as $userData) {
            // Cek apakah user sudah ada berdasarkan username di tabel users
            $existing = $users->where('username', $userData['username'])->first();
            if ($existing) {
                continue; // Skip jika username sudah ada
            }            // Cek apakah email sudah ada di tabel auth_identities
            $db = db_connect();
            $emailExists = $db->table('auth_identities')
                ->where('type', 'email_password')
                ->where('secret', $userData['email'])
                ->get()
                ->getRow();
            if ($emailExists) {
                continue; // Skip jika email sudah ada
            }

            $user = new ShieldUser([
                'username' => $userData['username'],
                'email'    => $userData['email'],
                'password' => $userData['password'],
                'active'   => $userData['active'],
            ]);
            $users->save($user);

            $savedUser = $users->findById($users->getInsertID());
            if ($savedUser) {
                $savedUser->addGroup($userData['role']);
                // Jika role pelanggan, buat juga data di tabel pelanggans
                if ($userData['role'] === 'pelanggan') {
                    $db->table('pelanggans')->insert([
                        'user_id' => $savedUser->id,
                        'alamat' => 'Alamat default',
                        'no_telepon' => '08xxxxxxxxxx',
                        'jenis_kelamin' => 'L',
                        'umur' => 20,
                        'status' => 'active'
                    ]);
                }
            }
        }
    }
}
