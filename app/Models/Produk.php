<?php

namespace App\Models;

use CodeIgniter\Model;

class Produk extends Model
{
    protected $table = 'produk';
    protected $primaryKey = 'id';
    protected $useAutoIncrement = true;
    protected $returnType = 'array';
    protected $allowedFields = ['nama', 'slug', 'deskripsi', 'harga', 'stok', 'gambar', 'kategori_id'];

    protected $validationRules = [
        'nama' => 'required|min_length[3]|max_length[255]',
        'harga' => 'required|numeric',
        'stok' => 'required|integer',
        'kategori_id' => 'required|integer'
    ];

    public function getProducts($slug = false)
    {
        if ($slug === false) {
            return $this->findAll();
        }

        return $this->where(['slug' => $slug])->first();
    }

    public function getProductsByCategory($kategori_id)
    {
        return $this->where(['kategori_id' => $kategori_id])->findAll();
    }

    public function searchProducts($keyword)
    {
        return $this->like('nama', $keyword)->orLike('deskripsi', $keyword)->findAll();
    }

    public function updateStock($id, $quantity)
    {
        $product = $this->find($id);
        if ($product) {
            $newStock = $product['stok'] - $quantity;
            if ($newStock >= 0) {
                $this->update($id, ['stok' => $newStock]);
                return true;
            }
        }
        return false;
    }

    // Ambil produk beserta nama kategori
    public function getProductsWithCategory()
    {
        return $this->select('produk.*, kategori.nama_kategori as kategori')
            ->join('kategori', 'kategori.id = produk.kategori_id', 'left')
            ->findAll();
    }
}
