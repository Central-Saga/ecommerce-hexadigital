<?php

namespace App\Models;

use CodeIgniter\Model;

class Pembayaran extends Model
{
    protected $table            = 'pembayaran';
    protected $primaryKey       = 'id';
    protected $useAutoIncrement = true;
    protected $returnType       = 'array';
    protected $useSoftDeletes   = false;
    protected $protectFields    = true;
    protected $allowedFields    = [
        'pemesanan_id',
        'metode_pembayaran',
        'bukti_pembayaran',
        'total_harga',
        'tanggal_pembayaran',
        'status',
        'created_at',
        'updated_at',
        'catatan',
        'nama_pengirim',
    ];

    protected bool $allowEmptyInserts = false;
    protected bool $updateOnlyChanged = true;

    protected array $casts = [
        'pemesanan_id' => 'integer',
        'total_harga' => 'float',
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
        'pemesanan_id' => 'required|integer',
        'metode_pembayaran' => 'required|string',
        'total_harga' => 'required|numeric',
        'status' => 'required|in_list[pending,diterima,ditolak]',
        'nama_pengirim' => 'permit_empty|string',
        'catatan' => 'permit_empty|string',
    ];

    protected $validationMessages   = [
        'pemesanan_id' => [
            'required' => 'Pemesanan harus dipilih',
            'integer' => 'ID Pemesanan harus berupa angka',
        ],
        'metode_pembayaran' => [
            'required' => 'Metode pembayaran harus diisi',
            'string' => 'Metode pembayaran harus berupa teks',
        ],
        'total_harga' => [
            'required' => 'Total harga harus diisi',
            'numeric' => 'Total harga harus berupa angka',
        ],
        'status' => [
            'required' => 'Status pembayaran harus diisi',
            'in_list' => 'Status pembayaran tidak valid',
        ],
        'nama_pengirim' => [
            'string' => 'Nama pengirim harus berupa teks',
        ],
        'catatan' => [
            'string' => 'Catatan harus berupa teks',
        ],
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
}
