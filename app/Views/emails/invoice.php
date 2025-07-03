<!DOCTYPE html>
<html>

<head>
    <meta charset="utf-8">
    <title>Invoice #<?= $order['id'] ?></title>
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
            border-bottom: 2px solid #007bff;
            padding-bottom: 20px;
        }

        .header h1 {
            color: #007bff;
            margin: 0;
        }

        .header h2 {
            color: #666;
            margin: 10px 0 0 0;
        }

        .invoice-info {
            margin-bottom: 20px;
            background-color: #f8f9fa;
            padding: 15px;
            border-radius: 5px;
        }

        .customer-info {
            margin-bottom: 20px;
            background-color: #e9ecef;
            padding: 15px;
            border-radius: 5px;
        }

        table {
            width: 100%;
            border-collapse: collapse;
            margin-bottom: 20px;
        }

        th,
        td {
            border: 1px solid #ddd;
            padding: 12px;
            text-align: left;
        }

        th {
            background-color: #007bff;
            color: white;
        }

        .total {
            font-weight: bold;
            text-align: right;
            font-size: 18px;
            color: #007bff;
        }

        .footer {
            margin-top: 30px;
            text-align: center;
            font-size: 12px;
            color: #666;
            border-top: 1px solid #ddd;
            padding-top: 20px;
        }

        .status-badge {
            display: inline-block;
            padding: 5px 10px;
            border-radius: 15px;
            font-size: 12px;
            font-weight: bold;
            text-transform: uppercase;
        }

        .status-menunggu {
            background-color: #ffc107;
            color: #000;
        }

        .status-diproses {
            background-color: #17a2b8;
            color: white;
        }

        .status-dikirim {
            background-color: #28a745;
            color: white;
        }

        .status-selesai {
            background-color: #28a745;
            color: white;
        }

        .status-dibatalkan {
            background-color: #dc3545;
            color: white;
        }
    </style>
</head>

<body>
    <div class="container">
        <div class="header">
            <h1>INVOICE</h1>
            <h2>E-Commerce Hexadigital</h2>
        </div>

        <div class="invoice-info">
            <table style="border: none; background: none;">
                <tr>
                    <td style="border: none;"><strong>Invoice No:</strong></td>
                    <td style="border: none;">#<?= $order['id'] ?></td>
                    <td style="border: none;"><strong>Tanggal:</strong></td>
                    <td style="border: none;"><?= date('d/m/Y', strtotime($order['tanggal_pemesanan'])) ?></td>
                </tr>
                <tr>
                    <td style="border: none;"><strong>Status:</strong></td>
                    <td style="border: none;">
                        <span class="status-badge status-<?= $order['status_pemesanan'] ?>">
                            <?= ucfirst($order['status_pemesanan']) ?>
                        </span>
                    </td>
                    <td style="border: none;"></td>
                    <td style="border: none;"></td>
                </tr>
            </table>
        </div>

        <div class="customer-info">
            <h3>Informasi Pelanggan:</h3>
            <p><strong>Nama:</strong> <?= esc($pelanggan['username'] ?? 'N/A') ?><br>
                <strong>Email:</strong> <?= esc($pelanggan['email'] ?? 'N/A') ?><br>
                <strong>Telepon:</strong> <?= esc($pelanggan['no_telepon'] ?? 'N/A') ?><br>
                <strong>Alamat:</strong> <?= esc($pelanggan['alamat'] ?? 'N/A') ?>
            </p>
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
            <tbody>
                <?php $no = 1;
                foreach ($details as $detail): ?>
                    <tr>
                        <td><?= $no++ ?></td>
                        <td><?= esc($detail['produk_nama']) ?></td>
                        <td>Rp <?= number_format($detail['harga'], 0, ',', '.') ?></td>
                        <td><?= $detail['jumlah'] ?></td>
                        <td>Rp <?= number_format($detail['subtotal'], 0, ',', '.') ?></td>
                    </tr>
                <?php endforeach; ?>
            </tbody>
        </table>

        <div class="total">
            <h3>Total: Rp <?= number_format($order['total_harga'], 0, ',', '.') ?></h3>
        </div>

        <?php if ($order['catatan']): ?>
            <div style="margin-top: 20px; background-color: #fff3cd; padding: 15px; border-radius: 5px; border-left: 4px solid #ffc107;">
                <h4>Catatan:</h4>
                <p><?= esc($order['catatan']) ?></p>
            </div>
        <?php endif; ?>

        <div class="footer">
            <p>Terima kasih telah berbelanja di E-Commerce Hexadigital</p>
            <p>Invoice ini dibuat secara otomatis pada <?= date('d/m/Y H:i:s') ?></p>
            <p>Untuk pertanyaan lebih lanjut, silakan hubungi customer service kami</p>
        </div>
    </div>
</body>

</html>