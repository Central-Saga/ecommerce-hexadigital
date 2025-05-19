<?php

namespace App\Controllers;

class Product extends BaseController
{
    protected $produkModel;
    protected $kategoriModel;

    public function __construct()
    {
        $this->produkModel = new \App\Models\Produk();
        $this->kategoriModel = new \App\Models\Kategori();
    }

    public function detail($id)
    {
        $product = $this->produkModel->withKategori()->find($id);
        
        if (!$product) {
            throw \CodeIgniter\Exceptions\PageNotFoundException::forPageNotFound('Produk tidak ditemukan');
        }

        return view('pages/product-detail', [
            'product' => $product
        ]);
    }
}
