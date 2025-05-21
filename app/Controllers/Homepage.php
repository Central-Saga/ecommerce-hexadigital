<?php

namespace App\Controllers;

use App\Models\Produk;
use App\Models\Kategori;

class Homepage extends BaseController
{
    protected $produkModel;
    protected $kategoriModel;

    public function __construct()
    {
        $this->produkModel = new Produk();
        $this->kategoriModel = new Kategori();
    }

    public function index()
    {
        $data = [
            'title' => 'Hexadigital - Home',
            'featured_products' => array_slice($this->produkModel->getProductsWithCategory(), 0, 8),
            'categories' => $this->kategoriModel->findAll()
        ];

        return view('pages/homepage', $data);
    }

    public function getKategori()
    {
        $data = [
            'title' => 'Semua Kategori',
            'categories' => $this->kategoriModel->findAll()
        ];
        return view('pages/kategori', $data);
    }

    public function kategori($id)
    {
        $category = $this->kategoriModel->find($id);

        if (!$category) {
            throw new \CodeIgniter\Exceptions\PageNotFoundException('Kategori tidak ditemukan');
        }

        // Ambil produk berdasarkan kategori
        $products = $this->produkModel->where('kategori_id', $id)->findAll();

        $data = [
            'title' => 'Kategori: ' . $category['nama_kategori'],
            'products' => $products,
            'category' => $category
        ];

        return view('pages/category', $data);
    }
}
