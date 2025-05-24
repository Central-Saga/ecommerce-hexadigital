<?php

namespace App\Database\Seeds;

use CodeIgniter\Database\Seeder;

class PelangganSeeder extends Seeder
{
    public function run()
    {
        $data = [
            [
                'user_id' => 3,
                'alamat' => 'Jl. Mawar No. 1',
                'no_telepon' => '08123456789',
                'jenis_kelamin' => 'L',
                'umur' => 22,
                'status' => 'active'
            ],
            [
                'user_id' => 4,
                'alamat' => 'Jl. Melati No. 2',
                'no_telepon' => '08123456780',
                'jenis_kelamin' => 'P',
                'umur' => 25,
                'status' => 'active'
            ],
            [
                'user_id' => 5,
                'alamat' => 'Jl. Kenanga No. 3',
                'no_telepon' => '08123456781',
                'jenis_kelamin' => 'L',
                'umur' => 30,
                'status' => 'active'
            ],
            [
                'user_id' => 6,
                'alamat' => 'Jl. Anggrek No. 4',
                'no_telepon' => '08123456782',
                'jenis_kelamin' => 'P',
                'umur' => 28,
                'status' => 'active'
            ],
            [
                'user_id' => 7,
                'alamat' => 'Jl. Dahlia No. 5',
                'no_telepon' => '08123456783',
                'jenis_kelamin' => 'L',
                'umur' => 35,
                'status' => 'active'
            ],
        ];

        // Filter data agar hanya user_id yang belum ada di pelanggans yang diinsert
        $filteredData = [];
        foreach ($data as $row) {
            $exists = $this->db->table('pelanggans')->where('user_id', $row['user_id'])->get()->getRow();
            if (!$exists) {
                $filteredData[] = $row;
            }
        }
        if (!empty($filteredData)) {
            $this->db->table('pelanggans')->insertBatch($filteredData);
        }
    }
}
