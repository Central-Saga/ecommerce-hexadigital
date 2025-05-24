<?php

namespace App\Models;

use CodeIgniter\Model;

class Pemesanan extends Model
{
    protected $table            = 'pemesanan';
    protected $primaryKey       = 'id';
    protected $useAutoIncrement = true;
    protected $returnType       = 'array';
    protected $useSoftDeletes   = false;
    protected $protectFields    = true;
    protected $allowedFields    = [
        'pelanggan_id',
        'tanggal_pemesanan',
        'total_harga',
        'status_pemesanan',
        'catatan'
    ];

    protected bool $allowEmptyInserts = false;
    protected bool $updateOnlyChanged = true;

    protected array $casts = [
        'pelanggan_id' => 'integer',
        'total_harga' => 'float'
    ];
    protected array $castHandlers = [];

    // Dates
    protected $useTimestamps = true;
    protected $dateFormat    = 'datetime';
    protected $createdField  = 'created_at';
    protected $updatedField  = 'updated_at';
    protected $deletedField  = '';

    // Validation
    protected $validationRules      = [
        'pelanggan_id' => 'required|integer', // Hapus is_not_unique untuk mengurangi ketergantungan
        'tanggal_pemesanan' => 'required|valid_date',
        'total_harga' => 'required|numeric',
        'status_pemesanan' => 'required|in_list[menunggu,diproses,selesai,dibatalkan]'
    ];

    protected $validationMessages   = [
        'pelanggan_id' => [
            'required' => 'Pelanggan harus dipilih',
            'integer' => 'ID Pelanggan harus berupa angka',
            'is_not_unique' => 'Pelanggan tidak ditemukan'
        ],
        'tanggal_pemesanan' => [
            'required' => 'Tanggal pemesanan harus diisi',
            'valid_date' => 'Tanggal pemesanan harus berupa tanggal yang valid'
        ],
        'total_harga' => [
            'required' => 'Total harga harus diisi',
            'numeric' => 'Total harga harus berupa angka'
        ],
        'status_pemesanan' => [
            'required' => 'Status pemesanan harus diisi',
            'in_list' => 'Status pemesanan tidak valid'
        ]
    ];

    protected $skipValidation       = false;
    protected $cleanValidationRules = true;

    // Callbacks
    protected $allowCallbacks = true;
    protected $beforeInsert   = [];
    protected $afterInsert    = [];
    protected $beforeUpdate   = [];
    protected $afterUpdate    = [];
    protected $beforeFind     = [];
    protected $afterFind      = [];
    protected $beforeDelete   = [];
    protected $afterDelete    = [];

    /**
     * Get pemesanan with pelanggan relation
     */
    public function withPelanggan()
    {
        return $this->select('pemesanan.*, pelanggans.username as nama_pelanggan, auth_identities.secret as email_pelanggan')
            ->join('pelanggans', 'pelanggans.id = pemesanan.pelanggan_id', 'left')
            ->join('users', 'users.id = pelanggans.user_id', 'left')
            ->join('auth_identities', 'auth_identities.user_id = users.id AND auth_identities.type = "email_password"', 'left');
    }
}
