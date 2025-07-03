<?php

namespace App\Controllers;

use App\Controllers\BaseController;
use App\Models\Keranjang;
use App\Models\DetailPemesanan;
use App\Models\Pemesanan;
use App\Models\Produk;
use App\Services\EmailService;

class Checkout extends BaseController
{
    public function postStore()
    {
        $user = auth()->user();
        if (!$user) {
            return redirect()->to('/login')->with('error', 'Silakan login terlebih dahulu');
        }
        $pelanggan = (new \App\Models\Pelanggan())->where('user_id', $user->id)->first();
        if (!$pelanggan) {
            return redirect()->back()->with('error', 'Akun pelanggan tidak ditemukan');
        }
        $pelanggan_id = $pelanggan['id'];

        $keranjangModel = new Keranjang();
        $detailPemesananModel = new DetailPemesanan();
        $produkModel = new Produk();
        $pemesananModel = new Pemesanan();

        $keranjangItems = $keranjangModel->where('pelanggan_id', $pelanggan_id)->findAll();
        if (empty($keranjangItems)) {
            return redirect()->back()->with('error', 'Keranjang belanja kosong');
        }

        $total_harga = array_sum(array_column($keranjangItems, 'subtotal'));

        $pemesananData = [
            'pelanggan_id' => $pelanggan_id,
            'tanggal_pemesanan' => date('Y-m-d H:i:s'),
            'total_harga' => $total_harga,
            'status_pemesanan' => 'menunggu',
            'catatan' => $this->request->getPost('catatan')
        ];
        $pemesananId = $pemesananModel->insert($pemesananData, true);
        if (!$pemesananId) {
            return redirect()->back()->with('error', 'Gagal membuat pemesanan');
        }

        $detailData = [];
        foreach ($keranjangItems as $item) {
            $produk = $produkModel->find($item['produk_id']);
            $harga = $produk ? $produk['harga'] : $item['subtotal'] / $item['jumlah'];
            $detailData[] = [
                'pemesanan_id' => $pemesananId,
                'produk_id' => $item['produk_id'],
                'jumlah' => $item['jumlah'],
                'harga' => $harga,
                'subtotal' => $harga * $item['jumlah'],
                'created_at' => date('Y-m-d H:i:s'),
                'updated_at' => date('Y-m-d H:i:s'),
            ];
            $produkModel->updateStock($item['produk_id'], $item['jumlah']);
        }
        $detailPemesananModel->insertBatch($detailData);

        $keranjangModel->where('pelanggan_id', $pelanggan_id)->delete();

        // Kirim email notifikasi dan invoice
        $emailService = new EmailService();
        $emailSent = $emailService->sendOrderNotification($pemesananId);
        $invoiceSent = $emailService->sendInvoiceEmail($pemesananId);

        $message = 'Checkout berhasil, pemesanan telah dibuat!';
        if ($emailSent) {
            $message .= ' Email notifikasi telah dikirim.';
        }
        if ($invoiceSent) {
            $message .= ' Invoice telah dikirim ke email Anda.';
        }

        return redirect()->to('/orders')->with('success', $message);
    }
}
