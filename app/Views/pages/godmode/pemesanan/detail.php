<?= $this->extend('layouts/admin') ?>

<?= $this->section('content') ?>
<div class="pemesanan-detail">
    <div class="card mb-4">
        <div class="card-header d-flex justify-content-between align-items-center">
            <div class="d-flex align-items-center gap-2">
                <i class="bi bi-cart-fill text-primary fs-4"></i>
                <h5 class="mb-0">Detail Pemesanan #<?= esc($pemesanan['id']) ?></h5>
            </div>
            <div>
                <a href="/godmode/pemesanan" class="btn btn-secondary btn-sm">
                    <i class="bi bi-arrow-left me-1"></i>Kembali
                </a>
            </div>
        </div>
        <div class="card-body">
            <div class="row">
                <div class="col-md-6 mb-4">
                    <div class="pemesanan-info">
                        <h6 class="fw-bold mb-3">Informasi Pemesanan</h6>
                        <table class="table table-borderless">
                            <tr>
                                <td width="40%" class="fw-bold">ID Pemesanan</td>
                                <td width="60%">#<?= esc($pemesanan['id']) ?></td>
                            </tr>
                            <tr>
                                <td class="fw-bold">Tanggal Pesanan</td>
                                <td><?= date('d M Y', strtotime($pemesanan['tanggal_pemesanan'])) ?></td>
                            </tr>
                            <tr>
                                <td class="fw-bold">Total Harga</td>
                                <td>Rp <?= number_format($pemesanan['total_harga'], 0, ',', '.') ?></td>
                            </tr>
                            <tr>
                                <td class="fw-bold">Status</td>
                                <td>
                                    <?php
                                    $statusClass = '';
                                    switch ($pemesanan['status_pemesanan']) {
                                        case 'menunggu':
                                            $statusClass = 'bg-warning';
                                            break;
                                        case 'diproses':
                                            $statusClass = 'bg-info';
                                            break;
                                        case 'selesai':
                                            $statusClass = 'bg-success';
                                            break;
                                        case 'dibatalkan':
                                            $statusClass = 'bg-danger';
                                            break;
                                    }
                                    ?> <span class="badge <?= $statusClass ?> py-1 px-2">
                                        <?= ucfirst($pemesanan['status_pemesanan']) ?>
                                    </span>
                                </td>
                            </tr>
                            <tr>
                                <td class="fw-bold">Dibuat Pada</td>
                                <td><?= date('d M Y H:i', strtotime($pemesanan['created_at'])) ?></td>
                            </tr>
                            <tr>
                                <td class="fw-bold">Diupdate Pada</td>
                                <td><?= date('d M Y H:i', strtotime($pemesanan['updated_at'])) ?></td>
                            </tr>
                        </table>
                    </div>
                </div>

                <div class="col-md-6 mb-4">
                    <div class="pelanggan-info">
                        <h6 class="fw-bold mb-3">Informasi Pelanggan</h6>
                        <table class="table table-borderless">
                            <tr>
                                <td width="40%" class="fw-bold">Nama</td>
                                <td width="60%"><?= esc($pelanggan['username'] ?? 'Tidak ada nama') ?></td>
                            </tr>
                            <tr>
                                <td class="fw-bold">Email</td>
                                <td><?= esc($pelanggan['email'] ?? 'Tidak ada email') ?></td>
                            </tr>
                            <tr>
                                <td class="fw-bold">No. Telepon</td>
                                <td><?= esc($pelanggan['no_telepon'] ?? '-') ?></td>
                            </tr>
                            <tr>
                                <td class="fw-bold">Alamat</td>
                                <td><?= esc($pelanggan['alamat'] ?? '-') ?></td>
                            </tr>
                        </table>
                    </div>
                </div>

                <div class="col-md-12 mb-4">
                    <div class="catatan-info">
                        <h6 class="fw-bold mb-3">Catatan Pemesanan</h6>
                        <div class="p-3 bg-light rounded">
                            <?= empty($pemesanan['catatan']) ? '<em>Tidak ada catatan</em>' : nl2br(esc($pemesanan['catatan'])) ?>
                        </div>
                    </div>
                </div>

                <!-- Bukti Pembayaran -->
                <div class="col-md-12 mb-4">
                    <div class="pembayaran-info">
                        <h6 class="fw-bold mb-3">Bukti Pembayaran</h6>
                        <?php if (!empty($pembayaran)): ?>
                            <table class="table table-borderless">
                                <tr>
                                    <td class="fw-bold" width="30%">Status</td>
                                    <td width="70%">
                                        <?php
                                        $statusClass = '';
                                        switch ($pembayaran['status']) {
                                            case 'pending':
                                                $statusClass = 'bg-warning';
                                                break;
                                            case 'diterima':
                                                $statusClass = 'bg-success';
                                                break;
                                            case 'ditolak':
                                                $statusClass = 'bg-danger';
                                                break;
                                        }
                                        ?>
                                        <span class="badge <?= $statusClass ?> py-1 px-2">
                                            <?= ucfirst($pembayaran['status']) ?>
                                        </span>
                                    </td>
                                </tr>
                                <tr>
                                    <td class="fw-bold">Metode</td>
                                    <td><?= esc($pembayaran['metode_pembayaran']) ?></td>
                                </tr>
                                <tr>
                                    <td class="fw-bold">Nama Pengirim</td>
                                    <td><?= esc($pembayaran['nama_pengirim'] ?? '-') ?></td>
                                </tr>
                                <tr>
                                    <td class="fw-bold">Tanggal Pembayaran</td>
                                    <td><?= !empty($pembayaran['tanggal_pembayaran']) ? date('d M Y H:i', strtotime($pembayaran['tanggal_pembayaran'])) : '-' ?></td>
                                </tr>
                                <tr>
                                    <td class="fw-bold">Total Dibayar</td>
                                    <td>Rp <?= number_format($pembayaran['total_harga'], 0, ',', '.') ?></td>
                                </tr>
                                <tr>
                                    <td class="fw-bold">Catatan</td>
                                    <td><?= nl2br(esc($pembayaran['catatan'] ?? '-')) ?></td>
                                </tr>
                                <tr>
                                    <td class="fw-bold">Bukti</td>
                                    <td>
                                        <?php if (!empty($pembayaran['bukti_pembayaran'])): ?>
                                            <a href="/<?= esc($pembayaran['bukti_pembayaran']) ?>" target="_blank">
                                                <img src="/<?= esc($pembayaran['bukti_pembayaran']) ?>" alt="Bukti Pembayaran" style="max-width:150px;max-height:150px;object-fit:contain;border:1px solid #ddd;border-radius:6px;">
                                            </a>
                                        <?php else: ?>
                                            <em>Tidak ada bukti pembayaran</em>
                                        <?php endif; ?>
                                    </td>
                                </tr>
                            </table>
                        <?php else: ?>
                            <div class="p-3 bg-light rounded"><em>Belum ada pembayaran</em></div>
                        <?php endif; ?>
                    </div>
                </div>
            </div>
            <!-- Tabel Detail Pemesanan -->
            <div class="row">
                <div class="col-12">
                    <div class="card mt-3">
                        <div class="card-header">
                            <h6 class="fw-bold mb-0">Detail Produk Pemesanan</h6>
                        </div>
                        <div class="card-body p-0">
                            <div class="table-responsive">
                                <table class="table table-bordered mb-0">
                                    <thead class="table-light">
                                        <tr>
                                            <th>No</th>
                                            <th>Produk</th>
                                            <th>Jumlah</th>
                                            <th>Harga</th>
                                            <th>Subtotal</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <?php if (empty($details)): ?>
                                            <tr>
                                                <td colspan="5" class="text-center text-muted">Tidak ada detail pemesanan</td>
                                            </tr>
                                        <?php else: ?>
                                            <?php foreach ($details as $i => $detail): ?>
                                                <tr>
                                                    <td><?= $i + 1 ?></td>
                                                    <td>
                                                        <div class="d-flex align-items-center">
                                                            <?php if (!empty($detail['gambar_produk'])): ?>
                                                                <img src="/uploads/produk/<?= esc($detail['gambar_produk']) ?>" alt="<?= esc($detail['nama_produk']) ?>" width="40" height="40" class="rounded me-2" style="object-fit:cover;">
                                                            <?php else: ?>
                                                                <div class="product-icon me-2">
                                                                    <i class="bi bi-box-seam fs-4 text-primary"></i>
                                                                </div>
                                                            <?php endif; ?>
                                                            <div>
                                                                <div class="fw-semibold mb-0"><?= esc($detail['nama_produk']) ?></div>
                                                                <small class="text-muted">ID: <?= esc($detail['produk_id']) ?></small>
                                                            </div>
                                                        </div>
                                                    </td>
                                                    <td><?= esc($detail['jumlah']) ?></td>
                                                    <td>Rp <?= number_format($detail['harga'], 0, ',', '.') ?></td>
                                                    <td>Rp <?= number_format($detail['subtotal'], 0, ',', '.') ?></td>
                                                </tr>
                                            <?php endforeach; ?>
                                        <?php endif; ?>
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
<?= $this->endSection() ?>