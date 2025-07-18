<?php

namespace App\Controllers;

use App\Models\Produk as ProdukModel;
use App\Models\Kategori;

class Produk extends BaseController
{
    protected $produkModel;
    protected $kategoriModel;

    public function __construct()
    {
        $this->produkModel = new ProdukModel();
        $this->kategoriModel = new Kategori();
    }

    public function getIndex()
    {
        $data = [
            'title' => 'Products',
            'products' => $this->produkModel->getProducts(),
            'categories' => $this->kategoriModel->findAll()
        ];

        return view('pages/products', $data);
    }

    public function getDetail($id)
    {
        $product = $this->produkModel->find($id);
        if (empty($product)) {
            throw new \CodeIgniter\Exceptions\PageNotFoundException('Product not found');
        }

        // Ambil data kategori
        $kategori = null;
        if (!empty($product['kategori_id'])) {
            $kategori = $this->kategoriModel->find($product['kategori_id']);
        }

        $data = [
            'title' => 'Product Detail',
            'product' => $product,
            'kategori' => $kategori
        ];

        return view('pages/product-detail', $data);
    }

    public function getCategory($id)
    {
        $category = $this->kategoriModel->find($id);

        if (!$category) {
            throw new \CodeIgniter\Exceptions\PageNotFoundException('Category not found');
        }

        $data = [
            'title' => 'Kategori: ' . $category['nama_kategori'],
            'products' => $this->produkModel->where('kategori_id', $id)->findAll(),
            'category' => $category
        ];

        return view('pages/kategori-detail', $data);
    }

    public function getSearch()
    {
        $keyword = $this->request->getGet('q');

        $data = [
            'title' => 'Search Results for: ' . $keyword,
            'products' => $this->produkModel->searchProducts($keyword),
            'categories' => $this->kategoriModel->findAll(),
            'keyword' => $keyword
        ];

        return view('pages/products', $data);
    }
}
