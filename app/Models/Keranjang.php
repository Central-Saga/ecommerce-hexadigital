<?php

namespace App\Models;

use CodeIgniter\Model;

class Keranjang extends Model
{
    protected $table            = 'keranjang';
    protected $primaryKey       = 'id';
    protected $useAutoIncrement = true;
    protected $returnType       = 'array';
    protected $useSoftDeletes   = false;
    protected $protectFields    = true;
    protected $allowedFields    = [
        'pelanggan_id',
        'produk_id',
        'jumlah',
        'subtotal',
        'created_at',
        'updated_at',
    ];

    protected bool $allowEmptyInserts = false;
    protected bool $updateOnlyChanged = true;

    protected array $casts = [];
    protected array $castHandlers = [];

    // Dates
    protected $useTimestamps = true;
    protected $dateFormat    = 'datetime';
    protected $createdField  = 'created_at';
    protected $updatedField  = 'updated_at';

    // Validation
    protected $validationRules      = [
        'pelanggan_id' => 'required|numeric|is_not_unique[pelanggans.id]',
        'produk_id'    => 'required|numeric|is_not_unique[produk.id]',
        'jumlah'       => 'required|numeric|greater_than[0]',
        'subtotal'     => 'required|decimal',
    ];

    protected $validationMessages   = [
        'pelanggan_id' => [
            'required' => 'Pelanggan harus diisi',
            'numeric' => 'Pelanggan harus berupa angka',
            'is_not_unique' => 'Pelanggan tidak ditemukan'
        ],
        'produk_id' => [
            'required' => 'Produk harus diisi',
            'numeric' => 'Produk harus berupa angka',
            'is_not_unique' => 'Produk tidak ditemukan'
        ],
        'jumlah' => [
            'required' => 'Jumlah harus diisi',
            'numeric' => 'Jumlah harus berupa angka',
            'greater_than' => 'Jumlah harus lebih dari 0'
        ],
        'subtotal' => [
            'required' => 'Subtotal harus diisi',
            'decimal' => 'Subtotal harus berupa desimal'
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
}
