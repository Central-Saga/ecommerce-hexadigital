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

    // Helper untuk ambil pelanggan_id dari user login Shield
    private function getPelangganId()
    {
        $user = auth()->user();
        if (!$user) return null;
        $pelanggan = (new \App\Models\Pelanggan())->where('user_id', $user->id)->first();
        return $pelanggan['id'] ?? null;
    }

    // Tampilkan isi keranjang dari database
    public function getIndex()
    {
        $pelanggan_id = $this->getPelangganId();
        if (!$pelanggan_id) {
            return redirect()->to('/login');
        }
        $items = $this->keranjangModel->where('pelanggan_id', $pelanggan_id)->findAll();
        $products = [];
        $total = 0;
        if (!empty($items)) {
            foreach ($items as &$item) {
                $produk = $this->produkModel->find($item['produk_id']);
                if ($produk) {
                    $item['nama'] = $produk['nama'];
                    $item['harga'] = $produk['harga'];
                    $item['gambar'] = $produk['gambar'];
                    $item['subtotal'] = $item['jumlah'] * $produk['harga'];
                    $total += $item['subtotal'];
                    $products[] = $item;
                }
            }
        }
        return view('pages/cart', [
            'products' => $products,
            'total' => $total
        ]);
    }

    // Tambah produk ke keranjang (database)
    public function postCart()
    {
        $productId = $this->request->getPost('product_id');
        $qty = (int) ($this->request->getPost('qty') ?? 1);
        if ($qty < 1) $qty = 1;
        $pelanggan_id = $this->getPelangganId();
        if (!$pelanggan_id) {
            return $this->response->setJSON(['success' => false, 'message' => 'Anda harus login']);
        }
        $product = $this->produkModel->find($productId);
        if (!$product || $product['stok'] < $qty) {
            return $this->response->setJSON(['success' => false, 'message' => 'Stok tidak cukup atau produk tidak ditemukan']);
        }
        $item = $this->keranjangModel->where(['pelanggan_id' => $pelanggan_id, 'produk_id' => $productId])->first();
        if ($item) {
            $newQty = $item['jumlah'] + $qty;
            if ($newQty > $product['stok']) $newQty = $product['stok'];
            $this->keranjangModel->update($item['id'], [
                'jumlah' => $newQty,
                'subtotal' => $newQty * $product['harga']
            ]);
        } else {
            $this->keranjangModel->insert([
                'pelanggan_id' => $pelanggan_id,
                'produk_id' => $productId,
                'jumlah' => $qty,
                'subtotal' => $qty * $product['harga']
            ]);
        }
        $cart_count = $this->keranjangModel->where('pelanggan_id', $pelanggan_id)->selectSum('jumlah')->first()['jumlah'] ?? 0;
        return $this->response->setJSON(['success' => true, 'cart_count' => $cart_count]);
    }

    // Update jumlah produk di keranjang (database)
    public function postUpdate()
    {
        $productId = $this->request->getPost('product_id');
        $qty = (int) $this->request->getPost('qty');
        $pelanggan_id = $this->getPelangganId();
        if (!$pelanggan_id) {
            return $this->response->setJSON(['success' => false, 'message' => 'Anda harus login']);
        }
        $product = $this->produkModel->find($productId);
        if (!$product || $qty < 1 || $qty > $product['stok']) {
            return $this->response->setJSON(['success' => false, 'message' => 'Jumlah tidak valid atau stok tidak cukup']);
        }
        $item = $this->keranjangModel->where(['pelanggan_id' => $pelanggan_id, 'produk_id' => $productId])->first();
        if ($item) {
            $this->keranjangModel->update($item['id'], [
                'jumlah' => $qty,
                'subtotal' => $qty * $product['harga']
            ]);
        }
        return $this->response->setJSON(['success' => true]);
    }

    // Hapus produk dari keranjang (database)
    public function postRemove()
    {
        $productId = $this->request->getPost('product_id');
        $pelanggan_id = $this->getPelangganId();
        if (!$pelanggan_id) {
            return $this->response->setJSON(['success' => false, 'message' => 'Anda harus login']);
        }
        $item = $this->keranjangModel->where(['pelanggan_id' => $pelanggan_id, 'produk_id' => $productId])->first();
        if ($item) {
            $this->keranjangModel->delete($item['id']);
        }
        return $this->response->setJSON(['success' => true]);
    }
}
