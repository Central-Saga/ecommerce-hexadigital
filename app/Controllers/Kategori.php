<?php

namespace App\Controllers;

use App\Models\Produk;
use App\Models\Kategori as KategoriModel;

class Kategori extends BaseController
{
    protected $produkModel;
    protected $kategoriModel;

    public function __construct()
    {
        $this->produkModel = new Produk();
        $this->kategoriModel = new KategoriModel();
    }

    // Untuk /kategori
    public function getIndex()
    {
        $data = [
            'title' => 'Semua Kategori',
            'categories' => $this->kategoriModel->findAll()
        ];
        return view('pages/kategori', $data);
    }

    // Untuk /kategori/1 dst
    public function getDetail($id)
    {
        $category = $this->kategoriModel->find($id);
        if (!$category) {
            throw new \CodeIgniter\Exceptions\PageNotFoundException('Kategori tidak ditemukan');
        }
        $products = $this->produkModel->where('kategori_id', $id)->findAll();
        $data = [
            'title' => 'Kategori: ' . $category['nama_kategori'],
            'products' => $products,
            'category' => $category
        ];
        return view('pages/kategori-detail', $data);
    }
}
