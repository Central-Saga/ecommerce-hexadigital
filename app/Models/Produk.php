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
        'kategori_id',
        'gambar',
        'slug'
    ];

    protected bool $allowEmptyInserts = false;
    protected bool $updateOnlyChanged = true;

    protected array $casts = [
        'harga' => 'float',
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
        'slug' => 'required|is_unique[produk.slug,id,{id}]'
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

    // Fungsi untuk menghasilkan slug saat menyimpan/update produk
    protected function beforeInsert(array $data)
    {
        if (!isset($data['data']['slug'])) {
            $data['data']['slug'] = $this->createSlug($data['data']['nama_produk']);
        }
        return $data;
    }

    protected function beforeUpdate(array $data)
    {
        if (isset($data['data']['nama_produk']) && !isset($data['data']['slug'])) {
            $data['data']['slug'] = $this->createSlug($data['data']['nama_produk']);
        }
        return $data;
    }

    // Fungsi untuk membuat slug unik
    protected function createSlug($title)
    {
        $slug = url_title($title, '-', true);
        $count = 1;

        // Cek apakah slug sudah ada
        while ($this->where('slug', $slug)
                   ->where('id !=', $this->getID())
                   ->countAllResults() > 0) {
            $slug = url_title($title . '-' . $count, '-', true);
            $count++;
        }

        return $slug;
    }

    // Fungsi untuk mendapatkan produk berdasarkan slug
    public function getProductBySlug($slug)
    {
        return $this->where('slug', $slug)
                   ->first();
    }
}
