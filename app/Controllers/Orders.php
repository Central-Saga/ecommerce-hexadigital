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
        $pembayaranModel = new \App\Models\Pembayaran();

        // Ambil semua pesanan milik pelanggan
        $orders = $pemesananModel->where('pelanggan_id', $pelanggan_id)->orderBy('created_at', 'DESC')->findAll();

        // Ambil detail produk & status pembayaran untuk setiap pesanan
        foreach ($orders as &$order) {
            $details = $detailPemesananModel->where('pemesanan_id', $order['id'])->findAll();
            foreach ($details as &$detail) {
                $produk = $produkModel->find($detail['produk_id']);
                $detail['produk_nama'] = $produk['nama'] ?? '-';
                $detail['produk_gambar'] = $produk['gambar'] ?? null;
            }
            $order['details'] = $details;

            // Ambil status pembayaran
            $pembayaran = $pembayaranModel->where('pesanan_id', $order['id'])->orderBy('id', 'DESC')->first();
            $order['pembayaran_status'] = $pembayaran['status'] ?? null;
        }

        return view('pages/orders', [
            'orders' => $orders
        ]);
    }

    public function postUploadPembayaran($id)
    {
        $file = $this->request->getFile('bukti_pembayaran');
        if (!$file || !$file->isValid()) {
            return redirect()->back()->with('error', 'File tidak valid atau belum dipilih.');
        }

        // Ambil total_harga dari pesanan
        $pemesananModel = new \App\Models\Pemesanan();
        $order = $pemesananModel->find($id);
        if (!$order) {
            return redirect()->back()->with('error', 'Pesanan tidak ditemukan.');
        }
        $total_harga = $order['total_harga'] ?? 0;

        // Pastikan folder uploads/pembayaran ada
        $uploadPath = FCPATH . 'uploads/pembayaran/';
        if (!is_dir($uploadPath)) {
            mkdir($uploadPath, 0777, true);
        }

        $newName = $file->getRandomName();
        $file->move($uploadPath, $newName);

        // Simpan data pembayaran ke database
        $pembayaranModel = new \App\Models\Pembayaran();
        $pembayaranModel->save([
            'pesanan_id'        => $id,
            'metode_pembayaran' => 'manual', // Bisa diganti sesuai kebutuhan
            'bukti_pembayaran'  => 'uploads/pembayaran/' . $newName,
            'total_harga'       => $total_harga, // Diisi dari pesanan
            'status'            => 'pending',
            'tanggal_pembayaran' => date('Y-m-d H:i:s'),
        ]);

        return redirect()->back()->with('success', 'Bukti pembayaran berhasil diupload.');
    }

    public function getKonfirmasiPembayaran($id)
    {
        $user = auth()->user();
        if (!$user) {
            return redirect()->to('/login')->with('error', 'Silakan login untuk melakukan konfirmasi pembayaran');
        }
        $pemesananModel = new \App\Models\Pemesanan();
        $order = $pemesananModel->find($id);
        if (!$order) {
            return redirect()->to('/orders')->with('error', 'Pesanan tidak ditemukan.');
        }
        $rekeningToko = [
            'BCA' => [
                'nomor' => '1234567890',
                'nama' => 'PT Hexadigital Indonesia'
            ],
            'Mandiri' => [
                'nomor' => '9876543210',
                'nama' => 'PT Hexadigital Indonesia'
            ],
            'BRI' => [
                'nomor' => '1122334455',
                'nama' => 'PT Hexadigital Indonesia'
            ],
        ];
        return view('pages/orders_konfirmasi_pembayaran', [
            'order' => $order,
            'rekeningToko' => $rekeningToko
        ]);
    }

    public function postKonfirmasiPembayaran($id)
    {
        $user = auth()->user();
        if (!$user) {
            return redirect()->to('/login')->with('error', 'Silakan login untuk melakukan konfirmasi pembayaran');
        }
        $file = $this->request->getFile('bukti_pembayaran');
        if (!$file || !$file->isValid()) {
            return redirect()->back()->with('error', 'File tidak valid atau belum dipilih.');
        }
        $bank = $this->request->getPost('metode_pembayaran');
        $nama_pengirim = $this->request->getPost('nama_pengirim');
        $catatan_user = $this->request->getPost('catatan');
        if (!$bank || !$nama_pengirim) {
            return redirect()->back()->with('error', 'Semua field wajib diisi.');
        }
        $rekeningToko = [
            'BCA' => [
                'nomor' => '1234567890',
                'nama' => 'PT Hexadigital Indonesia'
            ],
            'Mandiri' => [
                'nomor' => '9876543210',
                'nama' => 'PT Hexadigital Indonesia'
            ],
            'BRI' => [
                'nomor' => '1122334455',
                'nama' => 'PT Hexadigital Indonesia'
            ],
        ];
        $detailRek = $rekeningToko[$bank] ?? null;
        if (!$detailRek) {
            return redirect()->back()->with('error', 'Rekening tujuan tidak valid.');
        }
        $catatan = "Rekening tujuan: $bank - {$detailRek['nomor']} a.n {$detailRek['nama']}";
        if ($catatan_user) {
            $catatan .= "\nCatatan: $catatan_user";
        }
        // Ambil total_harga dari pesanan
        $pemesananModel = new \App\Models\Pemesanan();
        $order = $pemesananModel->find($id);
        if (!$order) {
            return redirect()->back()->with('error', 'Pesanan tidak ditemukan.');
        }
        $total_harga = $order['total_harga'] ?? 0;

        $uploadPath = FCPATH . 'uploads/pembayaran/';
        if (!is_dir($uploadPath)) {
            mkdir($uploadPath, 0777, true);
        }
        $newName = $file->getRandomName();
        $file->move($uploadPath, $newName);
        $pembayaranModel = new \App\Models\Pembayaran();
        $pembayaranModel->save([
            'pesanan_id'        => $id,
            'metode_pembayaran' => $bank,
            'bukti_pembayaran'  => 'uploads/pembayaran/' . $newName,
            'total_harga'       => $total_harga, // Diisi dari pesanan
            'status'            => 'pending',
            'tanggal_pembayaran' => date('Y-m-d H:i:s'),
            'catatan'           => $catatan,
            'nama_pengirim'     => $nama_pengirim,
        ]);
        return redirect()->to('/orders')->with('success', 'Konfirmasi pembayaran berhasil dikirim.');
    }
}
