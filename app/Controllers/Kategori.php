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

    public function getIndex($id = null)
    {
        if ($id === null) {
            // Tampilkan semua kategori
            $data = [
                'title' => 'Semua Kategori',
                'categories' => $this->kategoriModel->findAll()
            ];
            return view('pages/kategori', $data);
        } else {
            // Tampilkan detail kategori + produk
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
            return view('pages/category', $data);
        }
    }
}
