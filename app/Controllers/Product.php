<?php

namespace App\Controllers;

use App\Models\Produk;
use App\Models\Kategori;

class Product extends BaseController
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
            'title' => 'Products',
            'products' => $this->produkModel->getProducts(),
            'categories' => $this->kategoriModel->findAll()
        ];

        return view('pages/products', $data);
    }

    public function detail($id)
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

    public function category($id)
    {
        $category = $this->kategoriModel->find($id);
        
        if (!$category) {
            throw new \CodeIgniter\Exceptions\PageNotFoundException('Category not found');
        }

        $data = [
            'title' => 'Products - ' . $category['nama'],
            'products' => $this->produkModel->getProductsByCategory($id),
            'category' => $category,
            'categories' => $this->kategoriModel->findAll()
        ];

        return view('pages/products', $data);
    }

    public function search()
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
