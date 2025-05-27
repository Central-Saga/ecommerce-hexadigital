<?php

namespace App\Models;

use CodeIgniter\Model;

class DetailPemesanan extends Model
{
    protected $table            = 'detail_pemesanan';
    protected $primaryKey       = 'id';
    protected $useAutoIncrement = true;
    protected $returnType       = 'array';
    protected $useSoftDeletes   = false;
    protected $protectFields    = true;
    protected $allowedFields    = [
        'pemesanan_id',
        'produk_id',
        'jumlah',
        'harga',
        'subtotal',
        'created_at',
        'updated_at',
    ];

    protected $useTimestamps = true;
    protected $dateFormat    = 'datetime';
    protected $createdField  = 'created_at';
    protected $updatedField  = 'updated_at';

    protected $validationRules = [
        'pemesanan_id' => 'required|numeric|is_not_unique[pemesanan.id]',
        'produk_id'    => 'required|numeric|is_not_unique[produk.id]',
        'jumlah'       => 'required|numeric|greater_than[0]',
        'harga'        => 'required|decimal',
        'subtotal'     => 'required|decimal',
    ];

    protected $validationMessages = [
        'pemesanan_id' => [
            'required' => 'Pemesanan harus diisi',
            'numeric' => 'Pemesanan harus berupa angka',
            'is_not_unique' => 'Pemesanan tidak ditemukan'
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
        'harga' => [
            'required' => 'Harga harus diisi',
            'decimal' => 'Harga harus berupa desimal'
        ],
        'subtotal' => [
            'required' => 'Subtotal harus diisi',
            'decimal' => 'Subtotal harus berupa desimal'
        ]
    ];

    protected $skipValidation       = false;
    protected $cleanValidationRules = true;
}
