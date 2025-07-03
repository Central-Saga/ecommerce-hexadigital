<!DOCTYPE html>
<html>

<head>
    <title>Test Email - E-Commerce Hexadigital</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.7.2/font/bootstrap-icons.css" rel="stylesheet">
</head>

<body>
    <div class="container py-5">
        <h1 class="mb-4">Test Email System</h1>

        <?php if (!empty($orders)): ?>
            <div class="table-responsive">
                <table class="table table-striped">
                    <thead>
                        <tr>
                            <th>ID Pesanan</th>
                            <th>Pelanggan</th>
                            <th>Email</th>
                            <th>Total</th>
                            <th>Status</th>
                            <th>Tanggal</th>
                            <th>Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach ($orders as $order): ?>
                            <tr>
                                <td>#<?= $order['id'] ?></td>
                                <td><?= esc($order['pelanggan']) ?></td>
                                <td><?= esc($order['email']) ?></td>
                                <td>Rp <?= number_format($order['total'], 0, ',', '.') ?></td>
                                <td>
                                    <span class="badge bg-<?= $order['status'] === 'selesai' ? 'success' : ($order['status'] === 'dibatalkan' ? 'danger' : 'warning') ?>">
                                        <?= ucfirst($order['status']) ?>
                                    </span>
                                </td>
                                <td><?= date('d/m/Y H:i', strtotime($order['tanggal'])) ?></td>
                                <td>
                                    <div class="btn-group" role="group">
                                        <a href="<?= site_url('test-email/test-order-notification/' . $order['id']) ?>"
                                            class="btn btn-sm btn-primary"
                                            onclick="return confirm('Kirim email notifikasi pesanan?')">
                                            <i class="bi bi-bell"></i> Notifikasi
                                        </a>
                                        <a href="<?= site_url('test-email/test-invoice-email/' . $order['id']) ?>"
                                            class="btn btn-sm btn-success"
                                            onclick="return confirm('Kirim email invoice?')">
                                            <i class="bi bi-file-earmark-text"></i> Invoice
                                        </a>
                                        <a href="<?= site_url('test-email/test-payment-confirmation/' . $order['id']) ?>"
                                            class="btn btn-sm btn-info"
                                            onclick="return confirm('Kirim email konfirmasi pembayaran?')">
                                            <i class="bi bi-check-circle"></i> Konfirmasi
                                        </a>
                                    </div>
                                </td>
                            </tr>
                        <?php endforeach; ?>
                    </tbody>
                </table>
            </div>
        <?php else: ?>
            <div class="alert alert-info">
                Tidak ada pesanan yang tersedia untuk testing.
            </div>
        <?php endif; ?>

        <div class="mt-4">
            <a href="<?= site_url('/') ?>" class="btn btn-secondary">
                <i class="bi bi-arrow-left"></i> Kembali ke Beranda
            </a>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/js/bootstrap.bundle.min.js"></script>
</body>

</html>