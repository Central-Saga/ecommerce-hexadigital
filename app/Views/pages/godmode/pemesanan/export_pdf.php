<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Daftar Pemesanan</title>
    <style>
        @page {
            margin: 2cm;
            font-family: 'DejaVu Sans', sans-serif;
        }

        body {
            font-family: 'DejaVu Sans', Arial, sans-serif;
            font-size: 10px;
            line-height: 1.4;
            color: #333;
            margin: 0;
            padding: 0;
        }

        .header {
            text-align: center;
            margin-bottom: 20px;
            border-bottom: 2px solid #333;
            padding-bottom: 10px;
        }

        .header h1 {
            margin: 0;
            color: #333;
            font-size: 18px;
            font-weight: bold;
        }

        .header p {
            margin: 3px 0;
            color: #666;
            font-size: 10px;
        }

        table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 15px;
            font-size: 9px;
        }

        th,
        td {
            border: 1px solid #ddd;
            padding: 6px;
            text-align: left;
            vertical-align: top;
        }

        th {
            background-color: #f2f2f2;
            font-weight: bold;
            text-align: center;
            font-size: 9px;
        }

        .text-center {
            text-align: center;
        }

        .text-right {
            text-align: right;
        }

        .status-menunggu {
            color: #856404;
            font-weight: bold;
        }

        .status-diproses {
            color: #0c5460;
            font-weight: bold;
        }

        .status-selesai {
            color: #155724;
            font-weight: bold;
        }

        .status-dibatalkan {
            color: #721c24;
            font-weight: bold;
        }

        .footer {
            margin-top: 20px;
            text-align: right;
            font-size: 8px;
            color: #666;
            border-top: 1px solid #ddd;
            padding-top: 10px;
        }

        .no-data {
            text-align: center;
            font-style: italic;
            color: #666;
            padding: 20px;
        }
    </style>
</head>

<body>
    <div class="header">
        <h1>DAFTAR PEMESANAN</h1>
        <p>Sistem E-Commerce Hexadigital</p>
        <p>Tanggal Export: <?= $tanggal_export ?></p>
    </div>

    <table>
        <thead>
            <tr>
                <th style="width: 5%">No</th>
                <th style="width: 10%">ID</th>
                <th style="width: 20%">Pelanggan</th>
                <th style="width: 20%">Email</th>
                <th style="width: 15%">Tanggal</th>
                <th style="width: 15%">Total Harga</th>
                <th style="width: 15%">Status</th>
            </tr>
        </thead>
        <tbody>
            <?php if (empty($pemesanans)) : ?>
                <tr>
                    <td colspan="7" class="no-data">Tidak ada data pemesanan</td>
                </tr>
            <?php else : ?>
                <?php foreach ($pemesanans as $index => $pemesanan): ?>
                    <tr>
                        <td class="text-center"><?= $index + 1 ?></td>
                        <td class="text-center">#<?= $pemesanan['id'] ?></td>
                        <td><?= htmlspecialchars($pemesanan['pelanggan_nama'], ENT_QUOTES, 'UTF-8') ?></td>
                        <td><?= htmlspecialchars($pemesanan['email'], ENT_QUOTES, 'UTF-8') ?></td>
                        <td class="text-center"><?= date('d/m/Y', strtotime($pemesanan['tanggal_pemesanan'])) ?></td>
                        <td class="text-right">Rp <?= number_format($pemesanan['total_harga'], 0, ',', '.') ?></td>
                        <td class="text-center status-<?= $pemesanan['status_pemesanan'] ?>">
                            <?php
                            switch ($pemesanan['status_pemesanan']) {
                                case 'menunggu':
                                    echo 'Menunggu';
                                    break;
                                case 'diproses':
                                    echo 'Diproses';
                                    break;
                                case 'selesai':
                                    echo 'Selesai';
                                    break;
                                case 'dibatalkan':
                                    echo 'Dibatalkan';
                                    break;
                                default:
                                    echo ucfirst($pemesanan['status_pemesanan']);
                            }
                            ?>
                        </td>
                    </tr>
                <?php endforeach; ?>
            <?php endif; ?>
        </tbody>
    </table>

    <div class="footer">
        <p>Dicetak pada: <?= date('d/m/Y H:i:s') ?></p>
        <p>Total Data: <?= count($pemesanans) ?> pemesanan</p>
    </div>
</body>

</html>