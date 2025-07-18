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
                                            <img src="<?= base_url('uploads/produk/' . ($detail['produk_gambar'] ?? 'default.jpg')) ?>" alt="<?= esc($detail['produk_nama']) ?>" width="50" class="me-2">
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
                    <div class="d-flex justify-content-between align-items-center mt-3">
                        <strong>Total: Rp <?= number_format($order['total_harga'], 0, ',', '.') ?></strong>
                        <span>
                            <strong>Status Pembayaran:</strong>
                            <?php if (isset($order['pembayaran_status'])): ?>
                                <?php if ($order['pembayaran_status'] === 'pending'): ?>
                                    <span class="badge bg-warning">Pending</span>
                                <?php elseif ($order['pembayaran_status'] === 'diterima'): ?>
                                    <span class="badge bg-success">Diterima</span>
                                <?php elseif ($order['pembayaran_status'] === 'ditolak'): ?>
                                    <span class="badge bg-danger">Ditolak</span>
                                <?php else: ?>
                                    <span class="badge bg-secondary"><?= esc($order['pembayaran_status']) ?></span>
                                <?php endif; ?>
                            <?php else: ?>
                                <span class="badge bg-secondary">Belum Ada Data</span>
                            <?php endif; ?>
                        </span>
                    </div>
                    <?php if (!empty($order['catatan'])): ?>
                        <div class="mt-2"><strong>Catatan:</strong> <?= esc($order['catatan']) ?></div>
                    <?php endif; ?>
                    <?php if (
                        $order['status_pemesanan'] !== 'selesai' &&
                        $order['status_pemesanan'] !== 'dibatalkan' &&
                        (
                            !isset($order['pembayaran_status']) || $order['pembayaran_status'] === null || $order['pembayaran_status'] === 'pending'
                        )
                    ): ?>
                        <a href="<?= site_url('orders/konfirmasi-pembayaran/' . $order['id']) ?>" class="btn btn-primary mt-3">Konfirmasi Pembayaran</a>
                    <?php endif; ?>
                    <a href="<?= site_url('orders/download-invoice/' . $order['id']) ?>" class="btn btn-success mt-3 ms-2">
                        <i class="bi bi-download"></i> Download Invoice PDF
                    </a>
                    <a href="<?= site_url('orders/send-invoice-email/' . $order['id']) ?>" class="btn btn-info mt-3 ms-2">
                        <i class="bi bi-envelope"></i> Kirim Invoice via Email
                    </a>
                </div>
            </div>
        <?php endforeach; ?>
    <?php else: ?>
        <div class="alert alert-info">Belum ada pesanan.</div>
    <?php endif; ?>
</div>
<?= $this->endSection() ?>