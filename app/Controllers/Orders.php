<?php

namespace App\Controllers;

use App\Models\Pemesanan;
use App\Models\DetailPemesanan;
use App\Models\Produk;
use App\Models\Pelanggan;
use CodeIgniter\Controller;

class Orders extends Controller
{
    public function getIndex()
    {
        $user = auth()->user();
        if (!$user) {
            return redirect()->to('/login')->with('error', 'Silakan login untuk melihat pesanan Anda');
        }
        $pelanggan = (new Pelanggan())->where('user_id', $user->id)->first();
        $pelanggan_id = $pelanggan['id'] ?? null;
        if (!$pelanggan_id) {
            return redirect()->to('/login')->with('error', 'Akun pelanggan tidak ditemukan');
        }

        $pemesananModel = new Pemesanan();
        $detailPemesananModel = new DetailPemesanan();
        $produkModel = new Produk();

        // Ambil semua pesanan milik pelanggan
        $orders = $pemesananModel->where('pelanggan_id', $pelanggan_id)->orderBy('created_at', 'DESC')->findAll();

        // Ambil detail produk untuk setiap pesanan
        foreach ($orders as &$order) {
            $details = $detailPemesananModel->where('pemesanan_id', $order['id'])->findAll();
            foreach ($details as &$detail) {
                $produk = $produkModel->find($detail['produk_id']);
                $detail['produk_nama'] = $produk['nama'] ?? '-';
                $detail['produk_gambar'] = $produk['gambar'] ?? null;
            }
            $order['details'] = $details;
        }

        return view('pages/orders', [
            'orders' => $orders
        ]);
    }
}
