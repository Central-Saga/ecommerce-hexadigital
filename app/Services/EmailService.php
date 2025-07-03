<?php

namespace App\Services;

use CodeIgniter\Config\Services;
use App\Models\Pemesanan;
use App\Models\DetailPemesanan;
use App\Models\Produk;
use App\Models\Pelanggan;

class EmailService
{
    protected $email;
    protected $pemesananModel;
    protected $detailPemesananModel;
    protected $produkModel;
    protected $pelangganModel;

    public function __construct()
    {
        $this->email = Services::email();

        // Set konfigurasi email tambahan untuk debugging
        $this->email->setMailType('html');

        $this->pemesananModel = new Pemesanan();
        $this->detailPemesananModel = new DetailPemesanan();
        $this->produkModel = new Produk();
        $this->pelangganModel = new Pelanggan();
    }

    /**
     * Kirim email notifikasi pesanan baru
     */
    public function sendOrderNotification($orderId)
    {
        try {
            // Ambil data pesanan
            $order = $this->pemesananModel->find($orderId);
            if (!$order) {
                throw new \Exception('Pesanan tidak ditemukan');
            }

            // Ambil data pelanggan dengan email
            $pelanggan = $this->pelangganModel->withUser()->where('pelanggans.id', $order['pelanggan_id'])->first();
            if (!$pelanggan) {
                throw new \Exception('Data pelanggan tidak ditemukan');
            }

            // Ambil detail pesanan
            $details = $this->detailPemesananModel->where('pemesanan_id', $orderId)->findAll();
            foreach ($details as &$detail) {
                $produk = $this->produkModel->find($detail['produk_id']);
                $detail['produk_nama'] = $produk['nama'] ?? '-';
            }

            // Generate HTML email
            $html = view('emails/order_notification', [
                'order' => $order,
                'details' => $details,
                'pelanggan' => $pelanggan
            ]);

            // Kirim email
            $this->email->setTo($pelanggan['email']);
            $this->email->setSubject('Pesanan Berhasil - E-Commerce Hexadigital #' . $orderId);
            $this->email->setMessage($html);

            if ($this->email->send()) {
                log_message('info', 'Email notifikasi pesanan berhasil dikirim ke: ' . $pelanggan['email']);
                return true;
            } else {
                log_message('error', 'Gagal mengirim email notifikasi pesanan: ' . $this->email->printDebugger(['headers']));
                return false;
            }
        } catch (\Exception $e) {
            log_message('error', 'Error mengirim email notifikasi pesanan: ' . $e->getMessage());
            return false;
        }
    }

    /**
     * Kirim email invoice
     */
    public function sendInvoiceEmail($orderId)
    {
        try {
            log_message('info', 'Memulai pengiriman email invoice untuk order ID: ' . $orderId);

            // Ambil data pesanan
            $order = $this->pemesananModel->find($orderId);
            if (!$order) {
                throw new \Exception('Pesanan tidak ditemukan');
            }
            log_message('info', 'Data pesanan ditemukan: ' . json_encode($order));

            // Ambil data pelanggan dengan email
            $pelanggan = $this->pelangganModel->withUser()->where('pelanggans.id', $order['pelanggan_id'])->first();
            if (!$pelanggan) {
                throw new \Exception('Data pelanggan tidak ditemukan');
            }
            log_message('info', 'Data pelanggan ditemukan: ' . json_encode($pelanggan));

            // Debug: cek data email
            if (empty($pelanggan['email'])) {
                log_message('error', 'Email pelanggan kosong untuk order ID: ' . $orderId);
                throw new \Exception('Email pelanggan tidak ditemukan');
            }

            // Ambil detail pesanan
            $details = $this->detailPemesananModel->where('pemesanan_id', $orderId)->findAll();
            foreach ($details as &$detail) {
                $produk = $this->produkModel->find($detail['produk_id']);
                $detail['produk_nama'] = $produk['nama'] ?? '-';
            }
            log_message('info', 'Detail pesanan ditemukan: ' . count($details) . ' item');

            // Generate HTML email
            $html = view('emails/invoice', [
                'order' => $order,
                'details' => $details,
                'pelanggan' => $pelanggan
            ]);
            log_message('info', 'HTML email berhasil dibuat');

            // Kirim email
            $this->email->setTo($pelanggan['email']);
            $this->email->setSubject('Invoice Pesanan #' . $orderId . ' - E-Commerce Hexadigital');
            $this->email->setMessage($html);

            if ($this->email->send()) {
                log_message('info', 'Email invoice berhasil dikirim ke: ' . $pelanggan['email']);
                return true;
            } else {
                log_message('error', 'Gagal mengirim email invoice: ' . $this->email->printDebugger(['headers']));
                return false;
            }
        } catch (\Exception $e) {
            log_message('error', 'Error mengirim email invoice: ' . $e->getMessage());
            log_message('error', 'Stack trace: ' . $e->getTraceAsString());
            return false;
        }
    }

    /**
     * Kirim email konfirmasi pembayaran
     */
    public function sendPaymentConfirmation($orderId)
    {
        try {
            // Ambil data pesanan
            $order = $this->pemesananModel->find($orderId);
            if (!$order) {
                throw new \Exception('Pesanan tidak ditemukan');
            }

            // Ambil data pelanggan dengan email
            $pelanggan = $this->pelangganModel->withUser()->where('pelanggans.id', $order['pelanggan_id'])->first();
            if (!$pelanggan) {
                throw new \Exception('Data pelanggan tidak ditemukan');
            }

            // Generate HTML email konfirmasi pembayaran
            $html = $this->generatePaymentConfirmationHTML($order, $pelanggan);

            // Kirim email
            $this->email->setTo($pelanggan['email']);
            $this->email->setSubject('Konfirmasi Pembayaran Diterima - E-Commerce Hexadigital #' . $orderId);
            $this->email->setMessage($html);

            if ($this->email->send()) {
                log_message('info', 'Email konfirmasi pembayaran berhasil dikirim ke: ' . $pelanggan['email']);
                return true;
            } else {
                log_message('error', 'Gagal mengirim email konfirmasi pembayaran: ' . $this->email->printDebugger(['headers']));
                return false;
            }
        } catch (\Exception $e) {
            log_message('error', 'Error mengirim email konfirmasi pembayaran: ' . $e->getMessage());
            return false;
        }
    }

    /**
     * Generate HTML untuk email konfirmasi pembayaran
     */
    private function generatePaymentConfirmationHTML($order, $pelanggan)
    {
        return '
        <!DOCTYPE html>
        <html>
        <head>
            <meta charset="utf-8">
            <title>Konfirmasi Pembayaran #' . $order['id'] . '</title>
            <style>
                body { font-family: Arial, sans-serif; margin: 0; padding: 20px; background-color: #f4f4f4; }
                .container { max-width: 600px; margin: 0 auto; background-color: white; padding: 30px; border-radius: 10px; box-shadow: 0 0 10px rgba(0,0,0,0.1); }
                .header { text-align: center; margin-bottom: 30px; border-bottom: 2px solid #28a745; padding-bottom: 20px; }
                .header h1 { color: #28a745; margin: 0; }
                .success-info { background-color: #d4edda; padding: 15px; border-radius: 5px; border-left: 4px solid #28a745; margin-bottom: 20px; }
                .order-info { background-color: #f8f9fa; padding: 15px; border-radius: 5px; margin-bottom: 20px; }
                .footer { margin-top: 30px; text-align: center; font-size: 12px; color: #666; border-top: 1px solid #ddd; padding-top: 20px; }
            </style>
        </head>
        <body>
            <div class="container">
                <div class="header">
                    <h1>✅ Pembayaran Diterima!</h1>
                    <h2>E-Commerce Hexadigital</h2>
                </div>

                <div class="success-info">
                    <h3>Terima kasih!</h3>
                    <p>Pembayaran untuk pesanan #' . $order['id'] . ' telah berhasil diterima dan sedang diproses.</p>
                </div>

                <div class="order-info">
                    <h3>Detail Pesanan:</h3>
                    <p><strong>Order ID:</strong> #' . $order['id'] . '</p>
                    <p><strong>Tanggal Pesanan:</strong> ' . date('d/m/Y H:i', strtotime($order['tanggal_pemesanan'])) . '</p>
                    <p><strong>Total Pembayaran:</strong> Rp ' . number_format($order['total_harga'], 0, ',', '.') . '</p>
                    <p><strong>Status:</strong> <span style="background-color: #28a745; color: white; padding: 5px 10px; border-radius: 15px; font-size: 12px;">Pembayaran Diterima</span></p>
                </div>

                <div style="background-color: #e7f3ff; padding: 15px; border-radius: 5px; border-left: 4px solid #007bff; margin: 20px 0;">
                    <h4>Langkah Selanjutnya:</h4>
                    <ol>
                        <li>Tim kami akan memverifikasi pembayaran Anda</li>
                        <li>Pesanan akan diproses dan disiapkan untuk pengiriman</li>
                        <li>Anda akan menerima notifikasi pengiriman</li>
                        <li>Pesanan akan dikirim sesuai alamat yang terdaftar</li>
                    </ol>
                </div>

                <div class="footer">
                    <p>Terima kasih telah berbelanja di E-Commerce Hexadigital</p>
                    <p>Email ini dikirim secara otomatis pada ' . date('d/m/Y H:i:s') . '</p>
                    <p>Untuk pertanyaan lebih lanjut, silakan hubungi customer service kami</p>
                </div>
            </div>
        </body>
        </html>';
    }
}
