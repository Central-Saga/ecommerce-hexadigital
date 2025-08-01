<?php

namespace App\Controllers;

use App\Models\Pemesanan;
use App\Models\DetailPemesanan;
use App\Models\Produk;
use App\Models\Pelanggan;
use App\Services\EmailService;
use App\Controllers\BaseController;

class Orders extends BaseController
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
            $pembayaran = $pembayaranModel->where('pemesanan_id', $order['id'])->orderBy('id', 'DESC')->first();
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
            'pemesanan_id'        => $id,
            'metode_pembayaran' => 'manual', // Bisa diganti sesuai kebutuhan
            'bukti_pembayaran'  => 'uploads/pembayaran/' . $newName,
            'total_harga'       => $total_harga, // Diisi dari pesanan
            'status'            => 'pending',
            'tanggal_pembayaran' => date('Y-m-d H:i:s'),
        ]);

        // Kirim email konfirmasi pembayaran
        $emailService = new EmailService();
        $emailSent = $emailService->sendPaymentConfirmation($id);

        $message = 'Bukti pembayaran berhasil diupload.';
        if ($emailSent) {
            $message .= ' Email konfirmasi telah dikirim.';
        }

        return redirect()->back()->with('success', $message);
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
            'pemesanan_id'        => $id,
            'metode_pembayaran' => $bank,
            'bukti_pembayaran'  => 'uploads/pembayaran/' . $newName,
            'total_harga'       => $total_harga, // Diisi dari pesanan
            'status'            => 'pending',
            'tanggal_pembayaran' => date('Y-m-d H:i:s'),
            'catatan'           => $catatan,
            'nama_pengirim'     => $nama_pengirim,
        ]);

        // Kirim email konfirmasi pembayaran
        $emailService = new EmailService();
        $emailSent = $emailService->sendPaymentConfirmation($id);

        $message = 'Konfirmasi pembayaran berhasil dikirim.';
        if ($emailSent) {
            $message .= ' Email konfirmasi telah dikirim.';
        }

        return redirect()->to('/orders')->with('success', $message);
    }

    public function getDownloadInvoice($id = null)
    {
        if (!$id) {
            return redirect()->to('/orders')->with('error', 'ID pesanan tidak valid');
        }

        $user = auth()->user();
        if (!$user) {
            return redirect()->to('/login')->with('error', 'Silakan login untuk mengunduh invoice');
        }

        $pelanggan = (new Pelanggan())->withUser()->where('pelanggans.user_id', $user->id)->first();
        $pelanggan_id = $pelanggan['id'] ?? null;
        if (!$pelanggan_id) {
            return redirect()->to('/login')->with('error', 'Akun pelanggan tidak ditemukan');
        }

        $pemesananModel = new Pemesanan();
        $detailPemesananModel = new DetailPemesanan();
        $produkModel = new Produk();
        $pembayaranModel = new \App\Models\Pembayaran();

        // Ambil pesanan dengan validasi kepemilikan
        $order = $pemesananModel->where('id', $id)->where('pelanggan_id', $pelanggan_id)->first();
        if (!$order) {
            return redirect()->to('/orders')->with('error', 'Pesanan tidak ditemukan');
        }

        // Ambil detail pesanan
        $details = $detailPemesananModel->where('pemesanan_id', $order['id'])->findAll();
        foreach ($details as &$detail) {
            $produk = $produkModel->find($detail['produk_id']);
            $detail['produk_nama'] = $produk['nama'] ?? '-';
            $detail['produk_gambar'] = $produk['gambar'] ?? null;
        }

        // Ambil status pembayaran
        $pembayaran = $pembayaranModel->where('pemesanan_id', $order['id'])->orderBy('id', 'DESC')->first();

        // Generate HTML untuk PDF
        $html = $this->generateInvoiceHTML($order, $details, $pelanggan, $pembayaran);

        // Load library TCPDF atau Dompdf
        $dompdf = new \Dompdf\Dompdf();
        $dompdf->loadHtml($html);
        $dompdf->setPaper('A4', 'portrait');
        $dompdf->render();

        // Generate nama file
        $filename = 'Invoice-' . $order['id'] . '-' . date('Y-m-d') . '.pdf';

        ob_end_clean(); 

        // Output PDF
        $dompdf->stream($filename, ['Attachment' => true]);
        exit();
    }

    public function getSendInvoiceEmail($id = null)
    {
        if (!$id) {
            return redirect()->to('/orders')->with('error', 'ID pesanan tidak valid');
        }

        $user = auth()->user();
        if (!$user) {
            return redirect()->to('/login')->with('error', 'Silakan login untuk mengirim invoice');
        }

        $pelanggan = (new Pelanggan())->withUser()->where('pelanggans.user_id', $user->id)->first();
        $pelanggan_id = $pelanggan['id'] ?? null;
        if (!$pelanggan_id) {
            return redirect()->to('/login')->with('error', 'Akun pelanggan tidak ditemukan');
        }

        // Validasi kepemilikan pesanan
        $pemesananModel = new Pemesanan();
        $order = $pemesananModel->where('id', $id)->where('pelanggan_id', $pelanggan_id)->first();
        if (!$order) {
            return redirect()->to('/orders')->with('error', 'Pesanan tidak ditemukan');
        }

        // Kirim email invoice
        $emailService = new EmailService();
        $emailSent = $emailService->sendInvoiceEmail($id);

        if ($emailSent) {
            return redirect()->to('/orders')->with('success', 'Invoice telah dikirim ke email Anda.');
        } else {
            return redirect()->to('/orders')->with('error', 'Gagal mengirim invoice ke email. Silakan coba lagi.');
        }
    }

    private function generateInvoiceHTML($order, $details, $pelanggan, $pembayaran)
    {
        $html = '
        <!DOCTYPE html>
        <html>
        <head>
            <meta charset="utf-8">
            <title>Invoice #' . $order['id'] . '</title>
            <style>
                body { font-family: Arial, sans-serif; margin: 20px; }
                .header { text-align: center; margin-bottom: 30px; border-bottom: 2px solid #333; padding-bottom: 20px; }
                .company-info { margin-bottom: 20px; }
                .invoice-info { margin-bottom: 20px; }
                .customer-info { margin-bottom: 20px; }
                table { width: 100%; border-collapse: collapse; margin-bottom: 20px; }
                th, td { border: 1px solid #ddd; padding: 8px; text-align: left; }
                th { background-color: #f2f2f2; }
                .total { font-weight: bold; text-align: right; }
                .footer { margin-top: 30px; text-align: center; font-size: 12px; color: #666; }
            </style>
        </head>
        <body>
            <div class="header">
                <h1>INVOICE</h1>
                <h2>E-Commerce Hexadigital</h2>
            </div>

            <div class="invoice-info">
                <table style="border: none;">
                    <tr>
                        <td style="border: none;"><strong>Invoice No:</strong></td>
                        <td style="border: none;">#' . $order['id'] . '</td>
                        <td style="border: none;"><strong>Tanggal:</strong></td>
                        <td style="border: none;">' . date('d/m/Y', strtotime($order['tanggal_pemesanan'])) . '</td>
                    </tr>
                </table>
            </div>

            <div class="customer-info">
                <h3>Informasi Pelanggan:</h3>
                <p><strong>Nama:</strong> ' . esc($pelanggan['username'] ?? 'N/A') . '<br>
                <strong>Email:</strong> ' . esc($pelanggan['email'] ?? 'N/A') . '<br>
                <strong>Telepon:</strong> ' . esc($pelanggan['no_telepon'] ?? 'N/A') . '<br>
                <strong>Alamat:</strong> ' . esc($pelanggan['alamat'] ?? 'N/A') . '</p>
            </div>

            <table>
                <thead>
                    <tr>
                        <th>No</th>
                        <th>Produk</th>
                        <th>Harga</th>
                        <th>Jumlah</th>
                        <th>Subtotal</th>
                    </tr>
                </thead>
                <tbody>';

        $no = 1;
        foreach ($details as $detail) {
            $html .= '
                    <tr>
                        <td>' . $no++ . '</td>
                        <td>' . esc($detail['produk_nama']) . '</td>
                        <td>Rp ' . number_format($detail['harga'], 0, ',', '.') . '</td>
                        <td>' . $detail['jumlah'] . '</td>
                        <td>Rp ' . number_format($detail['subtotal'], 0, ',', '.') . '</td>
                    </tr>';
        }

        $html .= '
                </tbody>
            </table>

            <div class="total">
                <h3>Total: Rp ' . number_format($order['total_harga'], 0, ',', '.') . '</h3>
            </div>

            <div style="margin-top: 20px;">
                <p><strong>Status Pemesanan:</strong> ' . ucfirst($order['status_pemesanan']) . '</p>';

        if ($pembayaran) {
            $html .= '<p><strong>Status Pembayaran:</strong> ' . ucfirst($pembayaran['status']) . '</p>';
            if ($pembayaran['tanggal_pembayaran']) {
                $html .= '<p><strong>Tanggal Pembayaran:</strong> ' . date('d/m/Y H:i', strtotime($pembayaran['tanggal_pembayaran'])) . '</p>';
            }
        }

        $html .= '
            </div>

            <div class="footer">
                <p>Terima kasih telah berbelanja di E-Commerce Hexadigital</p>
                <p>Invoice ini dibuat secara otomatis pada ' . date('d/m/Y H:i:s') . '</p>
            </div>
        </body>
        </html>';

        return $html;
    }
}
