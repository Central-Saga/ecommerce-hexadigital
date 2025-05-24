<?= $this->extend('layouts/wrapper') ?>

<?= $this->section('content') ?>
<div class="container py-5 orders-page">
    <h2 class="mb-4">Pesanan Saya</h2>
    <?php if (!empty($orders)): ?>
        <?php foreach ($orders as $order): ?>
            <div class="card mb-4">
                <div class="card-header d-flex justify-content-between align-items-center">
                    <div>
                        <strong>ID Pesanan:</strong> <?= $order['id'] ?>
                        <span class="badge bg-<?= $order['status_pemesanan'] === 'selesai' ? 'success' : ($order['status_pemesanan'] === 'dibatalkan' ? 'danger' : 'warning') ?> ms-2">
                            <?= ucfirst($order['status_pemesanan']) ?>
                        </span>
                    </div>
                    <div><strong>Tanggal:</strong> <?= date('d M Y', strtotime($order['tanggal_pemesanan'])) ?></div>
                </div>
                <div class="card-body">
                    <div class="table-responsive">
                        <table class="table align-middle mb-0">
                            <thead>
                                <tr>
                                    <th>Produk</th>
                                    <th>Harga</th>
                                    <th>Jumlah</th>
                                    <th>Subtotal</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php foreach ($order['details'] as $detail): ?>
                                    <tr>
                                        <td>
                                            <img src="<?= base_url(($detail['produk_gambar'] ?? 'default.jpg')) ?>" alt="<?= esc($detail['produk_nama']) ?>" width="50" class="me-2">
                                            <?= esc($detail['produk_nama']) ?>
                                        </td>
                                        <td>Rp <?= number_format($detail['harga'], 0, ',', '.') ?></td>
                                        <td><?= $detail['jumlah'] ?></td>
                                        <td>Rp <?= number_format($detail['subtotal'], 0, ',', '.') ?></td>
                                    </tr>
                                <?php endforeach; ?>
                            </tbody>
                        </table>
                    </div>
                    <div class="d-flex justify-content-end mt-3">
                        <strong>Total: Rp <?= number_format($order['total_harga'], 0, ',', '.') ?></strong>
                    </div>
                    <?php if (!empty($order['catatan'])): ?>
                        <div class="mt-2"><strong>Catatan:</strong> <?= esc($order['catatan']) ?></div>
                    <?php endif; ?>
                    <?php if ($order['status_pemesanan'] !== 'selesai' && $order['status_pemesanan'] !== 'dibatalkan'): ?>
                        <a href="<?= site_url('orders/konfirmasi-pembayaran/' . $order['id']) ?>" class="btn btn-primary mt-3">Konfirmasi Pembayaran</a>
                    <?php endif; ?>
                </div>
            </div>
        <?php endforeach; ?>
    <?php else: ?>
        <div class="alert alert-info">Belum ada pesanan.</div>
    <?php endif; ?>
</div>
<?= $this->endSection() ?>