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
            'featured_products' => array_slice($this->produkModel->getProducts(), 0, 8),
            'categories' => $this->kategoriModel->findAll()
        ];

        return view('pages/home', $data);
    }
}
