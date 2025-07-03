<?php

namespace App\Controllers;

use App\Services\EmailService;
use App\Models\Pemesanan;
use App\Models\Pelanggan;
use CodeIgniter\Controller;
use CodeIgniter\Config\Services;

class TestEmail extends Controller
{
    public function getIndex()
    {
        $email = Services::email();

        // Test konfigurasi email
        $email->setTo('test@example.com');
        $email->setSubject('Test Email dari E-Commerce Hexadigital');
        $email->setMessage('Ini adalah test email untuk memverifikasi konfigurasi SMTP.');

        if ($email->send()) {
            echo "Email test berhasil dikirim!";
        } else {
            echo "Gagal mengirim email test:<br>";
            echo $email->printDebugger(['headers']);
        }
    }

    public function getTestInvoiceEmail($orderId = null)
    {
        if (!$orderId) {
            echo "Parameter order ID diperlukan. Contoh: /testemail/test-invoice-email/1";
            return;
        }

        try {
            $emailService = new \App\Services\EmailService();
            $result = $emailService->sendInvoiceEmail($orderId);

            if ($result) {
                echo "✅ Email invoice berhasil dikirim untuk order ID: " . $orderId;
            } else {
                echo "❌ Gagal mengirim email invoice untuk order ID: " . $orderId;
            }
        } catch (\Exception $e) {
            echo "❌ Error: " . $e->getMessage();
        }
    }

    public function getTestOrderNotification($orderId = null)
    {
        if (!$orderId) {
            echo "Parameter order ID diperlukan. Contoh: /testemail/test-order-notification/1";
            return;
        }

        try {
            $emailService = new \App\Services\EmailService();
            $result = $emailService->sendOrderNotification($orderId);

            if ($result) {
                echo "✅ Email notifikasi pesanan berhasil dikirim untuk order ID: " . $orderId;
            } else {
                echo "❌ Gagal mengirim email notifikasi pesanan untuk order ID: " . $orderId;
            }
        } catch (\Exception $e) {
            echo "❌ Error: " . $e->getMessage();
        }
    }

    public function getTestSMTP()
    {
        try {
            $email = \CodeIgniter\Config\Services::email();

            // Test konfigurasi SMTP
            $email->setTo('test@example.com');
            $email->setSubject('Test SMTP Configuration - E-Commerce Hexadigital');
            $email->setMessage('
                <html>
                <body>
                    <h2>Test Email SMTP</h2>
                    <p>Ini adalah test email untuk memverifikasi konfigurasi SMTP.</p>
                    <p>Waktu test: ' . date('Y-m-d H:i:s') . '</p>
                    <p>Jika email ini diterima, berarti konfigurasi SMTP sudah benar.</p>
                </body>
                </html>
            ');

            if ($email->send()) {
                echo "✅ Test SMTP berhasil! Email test telah dikirim.";
                echo "<br><br>Debug info:<br>";
                echo "<pre>" . $email->printDebugger(['headers']) . "</pre>";
            } else {
                echo "❌ Test SMTP gagal!";
                echo "<br><br>Error info:<br>";
                echo "<pre>" . $email->printDebugger(['headers']) . "</pre>";
            }
        } catch (\Exception $e) {
            echo "❌ Error saat test SMTP: " . $e->getMessage();
            echo "<br><br>Stack trace:<br>";
            echo "<pre>" . $e->getTraceAsString() . "</pre>";
        }
    }

    public function getShowEmailConfig()
    {
        $config = new \Config\Email();

        echo "<h2>Konfigurasi Email</h2>";
        echo "<table border='1' style='border-collapse: collapse;'>";
        echo "<tr><th>Parameter</th><th>Nilai</th></tr>";
        echo "<tr><td>Protocol</td><td>" . $config->protocol . "</td></tr>";
        echo "<tr><td>SMTP Host</td><td>" . $config->SMTPHost . "</td></tr>";
        echo "<tr><td>SMTP Port</td><td>" . $config->SMTPPort . "</td></tr>";
        echo "<tr><td>SMTP User</td><td>" . $config->SMTPUser . "</td></tr>";
        echo "<tr><td>SMTP Pass</td><td>***HIDDEN***</td></tr>";
        echo "<tr><td>SMTP Crypto</td><td>" . $config->SMTPCrypto . "</td></tr>";
        echo "<tr><td>From Email</td><td>" . $config->fromEmail . "</td></tr>";
        echo "<tr><td>From Name</td><td>" . $config->fromName . "</td></tr>";
        echo "<tr><td>Mail Type</td><td>" . $config->mailType . "</td></tr>";
        echo "<tr><td>Charset</td><td>" . $config->charset . "</td></tr>";
        echo "</table>";

        echo "<br><p><strong>Catatan:</strong> Jika email tidak terkirim, kemungkinan password SMTP sudah expired atau tidak valid.</p>";
        echo "<p>Untuk Gmail, Anda perlu menggunakan App Password, bukan password akun biasa.</p>";
    }

    public function testPaymentConfirmation($orderId = null)
    {
        if (!$orderId) {
            return "ID pesanan diperlukan";
        }

        $emailService = new EmailService();
        $result = $emailService->sendPaymentConfirmation($orderId);

        if ($result) {
            return "Email konfirmasi pembayaran berhasil dikirim!";
        } else {
            return "Gagal mengirim email konfirmasi pembayaran.";
        }
    }

    public function listOrders()
    {
        $pemesananModel = new Pemesanan();
        $pelangganModel = new Pelanggan();

        $orders = $pemesananModel->findAll();
        $result = [];

        foreach ($orders as $order) {
            $pelanggan = $pelangganModel->find($order['pelanggan_id']);
            $result[] = [
                'id' => $order['id'],
                'pelanggan' => $pelanggan['username'] ?? 'N/A',
                'email' => $pelanggan['email'] ?? 'N/A',
                'total' => $order['total_harga'],
                'status' => $order['status_pemesanan'],
                'tanggal' => $order['tanggal_pemesanan']
            ];
        }

        return view('test/email_test', ['orders' => $result]);
    }
}
