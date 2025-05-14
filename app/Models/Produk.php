<?php

namespace App\Models;

use CodeIgniter\Model;

class Produk extends Model
{
    protected $table            = 'produk'; // Nama tabel sesuai migration
    protected $primaryKey       = 'id';
    protected $useAutoIncrement = true;
    protected $returnType       = 'array';
    protected $useSoftDeletes   = false;
    protected $protectFields    = true;
    protected $allowedFields    = [
        'nama_produk',
        'harga',
        'deskripsi',
        'stok',
        'kategori_id'
    ];

    protected bool $allowEmptyInserts = false;
    protected bool $updateOnlyChanged = true;

    protected array $casts = [
        'harga' => 'decimal',
        'stok' => 'integer',
        'kategori_id' => 'integer'
    ];

    // Dates
    protected $useTimestamps = true; // Aktifkan timestamps
    protected $dateFormat    = 'datetime';
    protected $createdField  = 'created_at';
    protected $updatedField  = 'updated_at';

    // Validation
    protected $validationRules = [
        'nama_produk' => 'required|min_length[3]|max_length[255]',
        'harga' => 'required|numeric',
        'stok' => 'required|integer',
    ];

    protected $validationMessages = [
        'nama_produk' => [
            'required' => 'Nama produk harus diisi',
            'min_length' => 'Nama produk minimal 3 karakter',
            'max_length' => 'Nama produk maksimal 255 karakter'
        ],
        'harga' => [
            'required' => 'Harga produk harus diisi',
            'numeric' => 'Harga produk harus berupa angka'
        ],
        'stok' => [
            'required' => 'Stok produk harus diisi',
            'integer' => 'Stok produk harus berupa angka bulat'
        ]
    ];

    protected $skipValidation = false;
    protected $cleanValidationRules = true;

    /**
     * Get produk with kategori relation
     */
    public function withKategori()
    {
        return $this->select('produk.*, kategori.nama_kategori as kategori_nama')
            ->join('kategori', 'kategori.id = produk.kategori_id', 'left');
    }

    /**
     * Get produk by kategori
     */
    public function getByKategori($kategori_id)
    {
        return $this->where('kategori_id', $kategori_id)->findAll();
    }
}
