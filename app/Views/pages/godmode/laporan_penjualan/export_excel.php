<table border="1">
    <tr>
        <td colspan="7" style="text-align: center; font-size: 16px; font-weight: bold; background-color: #f0f0f0;">
            LAPORAN PENJUALAN <?= strtoupper($namaBulan) ?> <?= $tahun ?>
        </td>
    </tr>
    <tr>
        <td colspan="7" style="text-align: center; font-size: 12px; color: #666;">
            Periode: <?= $namaBulan ?> <?= $tahun ?> | Tanggal Cetak: <?= date('d/m/Y H:i') ?>
        </td>
    </tr>
    <tr>
        <td colspan="7" style="background-color: #e8f4fd; font-weight: bold; font-size: 14px;">
            RINGKASAN PENJUALAN
        </td>
    </tr>
    <tr>
        <td style="font-weight: bold; background-color: #f5f5f5;">Periode</td>
        <td colspan="6"><?= $namaBulan ?> <?= $tahun ?></td>
    </tr>
    <tr>
        <td style="font-weight: bold; background-color: #f5f5f5;">Total Pesanan</td>
        <td colspan="6"><?= number_format($statistikTotal['total_pesanan'] ?? 0) ?> pesanan</td>
    </tr>
    <tr>
        <td style="font-weight: bold; background-color: #f5f5f5;">Total Penjualan</td>
        <td colspan="6">Rp <?= number_format($statistikTotal['total_penjualan'] ?? 0, 0, ',', '.') ?></td>
    </tr>
    <tr>
        <td style="font-weight: bold; background-color: #f5f5f5;">Rata-rata Penjualan</td>
        <td colspan="6">Rp <?= number_format($statistikTotal['rata_rata_penjualan'] ?? 0, 0, ',', '.') ?></td>
    </tr>
    <tr>
        <td colspan="7"></td>
    </tr>
    <tr>
        <td colspan="7" style="background-color: #e8f4fd; font-weight: bold; font-size: 14px;">
            DETAIL PENJUALAN
        </td>
    </tr>
    <tr style="background-color: #f0f0f0; font-weight: bold;">
        <td style="text-align: center; border: 1px solid #000;">No</td>
        <td style="border: 1px solid #000;">Pelanggan</td>
        <td style="border: 1px solid #000;">Email</td>
        <td style="border: 1px solid #000;">Tanggal Pemesanan</td>
        <td style="border: 1px solid #000;">Total Harga</td>
        <td style="border: 1px solid #000;">Status</td>
        <td style="border: 1px solid #000;">Catatan</td>
    </tr>
    <?php if (empty($laporanPenjualan)) : ?>
        <tr>
            <td colspan="7" style="text-align: center; font-style: italic; color: #666;">
                Tidak ada data penjualan untuk periode yang dipilih
            </td>
        </tr>
    <?php else : ?>
        <?php foreach ($laporanPenjualan as $index => $pemesanan): ?>
            <tr>
                <td style="text-align: center; border: 1px solid #000;"><?= $index + 1 ?></td>
                <td style="border: 1px solid #000;"><?= esc($pemesanan['nama_pelanggan']) ?></td>
                <td style="border: 1px solid #000;"><?= esc($pemesanan['email_pelanggan']) ?></td>
                <td style="border: 1px solid #000;"><?= date('d/m/Y H:i', strtotime($pemesanan['tanggal_pemesanan'])) ?></td>
                <td style="border: 1px solid #000; text-align: right;">Rp <?= number_format($pemesanan['total_harga'], 0, ',', '.') ?></td>
                <td style="border: 1px solid #000; text-align: center;">
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
                <td style="border: 1px solid #000;"><?= !empty($pemesanan['catatan']) ? esc($pemesanan['catatan']) : '-' ?></td>
            </tr>
        <?php endforeach; ?>
    <?php endif; ?>
    <tr>
        <td colspan="7"></td>
    </tr>
    <tr>
        <td colspan="7" style="text-align: right; font-size: 11px; color: #666;">
            Dicetak pada: <?= date('d/m/Y H:i:s') ?> | Oleh: Sistem E-commerce HexaDigital
        </td>
    </tr>
</table>
