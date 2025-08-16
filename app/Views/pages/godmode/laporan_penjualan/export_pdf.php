<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Laporan Penjualan <?= $namaBulan ?> <?= $tahun ?></title>
    <style>
        @page {
            margin: 1cm;
            size: A4;
        }

        body {
            font-family: 'Arial', sans-serif;
            margin: 0;
            padding: 20px;
            font-size: 12px;
            line-height: 1.4;
        }

        .header {
            text-align: center;
            border-bottom: 2px solid #333;
            padding-bottom: 20px;
            margin-bottom: 30px;
        }

        .header h1 {
            margin: 0;
            color: #333;
            font-size: 24px;
            font-weight: bold;
        }

        .header p {
            margin: 5px 0;
            color: #666;
            font-size: 14px;
        }

        .summary {
            margin-bottom: 30px;
        }

        .summary h3 {
            margin: 0 0 15px 0;
            color: #333;
            font-size: 16px;
        }

        .summary table {
            width: 100%;
            border-collapse: collapse;
            margin-bottom: 20px;
        }

        .summary td {
            padding: 10px;
            border: 1px solid #ddd;
            font-size: 12px;
        }

        .summary td:first-child {
            font-weight: bold;
            background-color: #f5f5f5;
            width: 200px;
        }

        .data-table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 20px;
        }

        .data-table th,
        .data-table td {
            border: 1px solid #ddd;
            padding: 8px;
            text-align: left;
            font-size: 11px;
            vertical-align: top;
        }

        .data-table th {
            background-color: #f5f5f5;
            font-weight: bold;
            text-align: center;
        }

        .text-center {
            text-align: center;
        }

        .text-right {
            text-align: right;
        }

        .footer {
            margin-top: 40px;
            text-align: right;
            font-size: 11px;
            color: #666;
            border-top: 1px solid #ddd;
            padding-top: 20px;
        }

        .page-break {
            page-break-before: always;
        }

        @media print {
            body {
                margin: 0;
            }

            .header {
                page-break-after: avoid;
            }

            .summary {
                page-break-after: avoid;
            }

            .data-table {
                page-break-inside: auto;
            }
        }
    </style>
</head>

<body>
    <div class="header">
        <h1>LAPORAN PENJUALAN</h1>
        <p>Periode: <?= $namaBulan ?> <?= $tahun ?></p>
        <p>Tanggal Cetak: <?= date('d/m/Y H:i') ?></p>
    </div>

    <div class="summary">
        <h3>Ringkasan Penjualan</h3>
        <table>
            <tr>
                <td>Periode</td>
                <td><?= $namaBulan ?> <?= $tahun ?></td>
            </tr>
            <tr>
                <td>Total Pesanan</td>
                <td><?= number_format($statistikTotal['total_pesanan'] ?? 0) ?> pesanan</td>
            </tr>
            <tr>
                <td>Total Penjualan</td>
                <td>Rp <?= number_format($statistikTotal['total_penjualan'] ?? 0, 0, ',', '.') ?></td>
            </tr>
            <tr>
                <td>Rata-rata Penjualan</td>
                <td>Rp <?= number_format($statistikTotal['rata_rata_penjualan'] ?? 0, 0, ',', '.') ?></td>
            </tr>
        </table>
    </div>

    <div class="detail">
        <h3>Detail Penjualan</h3>
        <?php if (empty($laporanPenjualan)) : ?>
            <p>Tidak ada data penjualan untuk periode yang dipilih.</p>
        <?php else : ?>
            <table class="data-table">
                <thead>
                    <tr>
                        <th style="width: 30px">No</th>
                        <th style="width: 120px">Pelanggan</th>
                        <th style="width: 150px">Email</th>
                        <th style="width: 80px">Tanggal</th>
                        <th style="width: 100px">Total Harga</th>
                        <th style="width: 80px">Status</th>
                        <th style="width: 150px">Catatan</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($laporanPenjualan as $index => $pemesanan): ?>
                        <tr>
                            <td class="text-center"><?= $index + 1 ?></td>
                            <td><?= esc($pemesanan['nama_pelanggan']) ?></td>
                            <td><?= esc($pemesanan['email_pelanggan']) ?></td>
                            <td class="text-center"><?= date('d/m/Y', strtotime($pemesanan['tanggal_pemesanan'])) ?></td>
                            <td class="text-right">Rp <?= number_format($pemesanan['total_harga'], 0, ',', '.') ?></td>
                            <td class="text-center">
                                <?php
                                $statusText = [
                                    'menunggu' => 'Menunggu',
                                    'diproses' => 'Diproses',
                                    'selesai' => 'Selesai',
                                    'dibatalkan' => 'Dibatalkan'
                                ];
                                ?>
                                <?= $statusText[$pemesanan['status_pemesanan']] ?? $pemesanan['status_pemesanan'] ?>
                            </td>
                            <td><?= !empty($pemesanan['catatan']) ? esc($pemesanan['catatan']) : '-' ?></td>
                        </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        <?php endif; ?>
    </div>

    <div class="footer">
        <p>Dicetak pada: <?= date('d/m/Y H:i:s') ?></p>
        <p>Oleh: Sistem E-commerce HexaDigital</p>
    </div>
</body>

</html>