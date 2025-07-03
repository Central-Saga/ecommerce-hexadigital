<!DOCTYPE html>
<html>

<head>
    <meta charset="utf-8">
    <title>Pesanan Baru #<?= $order['id'] ?></title>
    <style>
        body {
            font-family: Arial, sans-serif;
            margin: 0;
            padding: 20px;
            background-color: #f4f4f4;
        }

        .container {
            max-width: 600px;
            margin: 0 auto;
            background-color: white;
            padding: 30px;
            border-radius: 10px;
            box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
        }

        .header {
            text-align: center;
            margin-bottom: 30px;
            border-bottom: 2px solid #28a745;
            padding-bottom: 20px;
        }

        .header h1 {
            color: #28a745;
            margin: 0;
        }

        .header h2 {
            color: #666;
            margin: 10px 0 0 0;
        }

        .order-info {
            margin-bottom: 20px;
            background-color: #d4edda;
            padding: 15px;
            border-radius: 5px;
            border-left: 4px solid #28a745;
        }

        .order-summary {
            margin-bottom: 20px;
            background-color: #f8f9fa;
            padding: 15px;
            border-radius: 5px;
        }

        .product-list {
            margin-bottom: 20px;
        }

        .product-item {
            background-color: #fff;
            border: 1px solid #ddd;
            padding: 10px;
            margin-bottom: 10px;
            border-radius: 5px;
        }

        .total {
            font-weight: bold;
            text-align: right;
            font-size: 18px;
            color: #28a745;
            background-color: #f8f9fa;
            padding: 15px;
            border-radius: 5px;
        }

        .footer {
            margin-top: 30px;
            text-align: center;
            font-size: 12px;
            color: #666;
            border-top: 1px solid #ddd;
            padding-top: 20px;
        }

        .btn {
            display: inline-block;
            padding: 12px 24px;
            background-color: #007bff;
            color: white;
            text-decoration: none;
            border-radius: 5px;
            margin: 10px 5px;
        }

        .btn:hover {
            background-color: #0056b3;
        }

        .status-badge {
            display: inline-block;
            padding: 5px 10px;
            border-radius: 15px;
            font-size: 12px;
            font-weight: bold;
            text-transform: uppercase;
            background-color: #ffc107;
            color: #000;
        }
    </style>
</head>

<body>
    <div class="container">
        <div class="header">
            <h1>🎉 Pesanan Berhasil!</h1>
            <h2>E-Commerce Hexadigital</h2>
        </div>

        <div class="order-info">
            <h3>Terima kasih atas pesanan Anda!</h3>
            <p>Pesanan Anda telah berhasil dibuat dan sedang diproses. Berikut adalah detail pesanan Anda:</p>
        </div>

        <div class="order-summary">
            <h3>Ringkasan Pesanan:</h3>
            <p><strong>Order ID:</strong> #<?= $order['id'] ?></p>
            <p><strong>Tanggal Pesanan:</strong> <?= date('d/m/Y H:i', strtotime($order['tanggal_pemesanan'])) ?></p>
            <p><strong>Status:</strong> <span class="status-badge"><?= ucfirst($order['status_pemesanan']) ?></span></p>
            <p><strong>Total Pembayaran:</strong> Rp <?= number_format($order['total_harga'], 0, ',', '.') ?></p>
        </div>

        <div class="product-list">
            <h3>Produk yang Dipesan:</h3>
            <?php foreach ($details as $detail): ?>
                <div class="product-item">
                    <strong><?= esc($detail['produk_nama']) ?></strong><br>
                    <small>Harga: Rp <?= number_format($detail['harga'], 0, ',', '.') ?> x <?= $detail['jumlah'] ?> = Rp <?= number_format($detail['subtotal'], 0, ',', '.') ?></small>
                </div>
            <?php endforeach; ?>
        </div>

        <div class="total">
            <h3>Total: Rp <?= number_format($order['total_harga'], 0, ',', '.') ?></h3>
        </div>

        <?php if ($order['catatan']): ?>
            <div style="margin-top: 20px; background-color: #fff3cd; padding: 15px; border-radius: 5px; border-left: 4px solid #ffc107;">
                <h4>Catatan Pesanan:</h4>
                <p><?= esc($order['catatan']) ?></p>
            </div>
        <?php endif; ?>

        <div style="text-align: center; margin: 30px 0;">
            <a href="<?= site_url('orders') ?>" class="btn">Lihat Pesanan Saya</a>
            <a href="<?= site_url('orders/download-invoice/' . $order['id']) ?>" class="btn">Download Invoice</a>
        </div>

        <div style="background-color: #e7f3ff; padding: 15px; border-radius: 5px; border-left: 4px solid #007bff; margin: 20px 0;">
            <h4>Langkah Selanjutnya:</h4>
            <ol>
                <li>Tim kami akan memverifikasi pesanan Anda</li>
                <li>Setelah pembayaran dikonfirmasi, pesanan akan diproses</li>
                <li>Anda akan mendapat notifikasi pengiriman</li>
            </ol>
        </div>

        <div class="footer">
            <p>Terima kasih telah berbelanja di E-Commerce Hexadigital</p>
            <p>Email ini dikirim secara otomatis pada <?= date('d/m/Y H:i:s') ?></p>
            <p>Untuk pertanyaan lebih lanjut, silakan hubungi customer service kami</p>
        </div>
    </div>
</body>

</html>