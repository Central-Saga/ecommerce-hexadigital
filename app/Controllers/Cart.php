<?php

namespace App\Controllers;

use App\Models\Keranjang;
use App\Models\Produk;
use CodeIgniter\HTTP\ResponseInterface;

class Cart extends BaseController
{
    protected $keranjangModel;
    protected $produkModel;

    public function __construct()
    {
        $this->keranjangModel = new Keranjang();
        $this->produkModel = new Produk();
    }

    public function index()
    {
        // Ambil cart dari cookie (sinkron dengan localStorage)
        $cart = [];
        if (isset($_COOKIE['cart'])) {
            $cart = json_decode($_COOKIE['cart'], true) ?? [];
        } elseif (session()->has('cart')) {
            $cart = session('cart');
        }
        $products = [];
        $total = 0;
        if (!empty($cart)) {
            $products = $this->produkModel->whereIn('id', array_keys($cart))->findAll();
            foreach ($products as &$product) {
                $product['qty'] = $cart[$product['id']];
                $product['subtotal'] = $product['qty'] * $product['harga'];
                $total += $product['subtotal'];
            }
        }
        return view('pages/cart', [
            'products' => $products,
            'total' => $total
        ]);
    }

    public function add()
    {
        $productId = $this->request->getPost('product_id');
        $qty = $this->request->getPost('qty') ?? 1;
        $cart = session()->get('cart') ?? [];
        if (isset($cart[$productId])) {
            $cart[$productId] += $qty;
        } else {
            $cart[$productId] = $qty;
        }
        session()->set('cart', $cart);
        return $this->response->setJSON(['success' => true, 'cart_count' => array_sum($cart)]);
    }

    public function update()
    {
        $productId = $this->request->getPost('product_id');
        $qty = $this->request->getPost('qty');
        $cart = session()->get('cart') ?? [];
        if (isset($cart[$productId])) {
            $cart[$productId] = $qty;
            session()->set('cart', $cart);
        }
        return $this->response->setJSON(['success' => true]);
    }

    public function remove()
    {
        $productId = $this->request->getPost('product_id');
        $cart = session()->get('cart') ?? [];
        if (isset($cart[$productId])) {
            unset($cart[$productId]);
            session()->set('cart', $cart);
        }
        return $this->response->setJSON(['success' => true]);
    }
}
