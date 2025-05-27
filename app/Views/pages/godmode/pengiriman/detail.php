<?= $this->extend('layouts/admin_layout'); ?>
<?= $this->section('title') ?>Detail Pengiriman<?= $this->endSection() ?>
<?= $this->section('content'); ?>
<div class="container-fluid px-4 py-3">
    <div class="row">
        <div class="col-12">
            <div class="card shadow border-0">
                <div class="card-header bg-gradient-info text-white d-flex justify-content-between align-items-center">
                    <h5 class="card-title mb-0">
                        <i class="fas fa-truck me-2"></i>Detail Pengiriman #<?= esc($pengiriman['id']) ?>
                    </h5>
                    <a href="<?= base_url('godmode/pengiriman') ?>" class="btn btn-secondary btn-sm">
                        <i class="fas fa-arrow-left me-1"></i> Kembali
                    </a>
                </div>
                <div class="card-body">
                    <div class="row mb-4">
                        <div class="col-md-6">
                            <h6 class="fw-bold mb-3">Info Pengiriman</h6>
                            <table class="table table-borderless">
                                <tr>
                                    <td class="fw-bold">ID Pengiriman</td>
                                    <td>#<?= esc($pengiriman['id']) ?></td>
                                </tr>
                                <tr>
                                    <td class="fw-bold">Nomor Pemesanan</td>
                                    <td><?= esc($pengiriman['nomor_pemesanan'] ?? '-') ?></td>
                                </tr>
                                <tr>
                                    <td class="fw-bold">Tanggal Kirim</td>
                                    <td><?= $pengiriman['tanggal_kirim'] ? date('d M Y', strtotime($pengiriman['tanggal_kirim'])) : '-' ?></td>
                                </tr>
                                <tr>
                                    <td class="fw-bold">Tanggal Terima</td>
                                    <td><?= $pengiriman['tanggal_terima'] ? date('d M Y', strtotime($pengiriman['tanggal_terima'])) : '-' ?></td>
                                </tr>
                                <tr>
                                    <td class="fw-bold">Status</td>
                                    <td>
                                        <?php
                                        $statusClass = '';
                                        switch ($pengiriman['status']) {
                                            case 'menunggu':
                                                $statusClass = 'bg-warning';
                                                break;
                                            case 'dikirim':
                                                $statusClass = 'bg-primary';
                                                break;
                                            case 'diterima':
                                                $statusClass = 'bg-success';
                                                break;
                                            case 'dibatalkan':
                                                $statusClass = 'bg-danger';
                                                break;
                                            default:
                                                $statusClass = 'bg-secondary';
                                        }
                                        ?>
                                        <span class="badge <?= $statusClass ?> py-1 px-2">
                                            <?= ucfirst($pengiriman['status']) ?>
                                        </span>
                                    </td>
                                </tr>
                                <tr>
                                    <td class="fw-bold">Deskripsi</td>
                                    <td><?= nl2br(esc($pengiriman['deskripsi'] ?? '-')) ?></td>
                                </tr>
                                <tr>
                                    <td class="fw-bold">Dibuat Pada</td>
                                    <td><?= $pengiriman['created_at'] ? date('d M Y H:i', strtotime($pengiriman['created_at'])) : '-' ?></td>
                                </tr>
                                <tr>
                                    <td class="fw-bold">Diupdate Pada</td>
                                    <td><?= $pengiriman['updated_at'] ? date('d M Y H:i', strtotime($pengiriman['updated_at'])) : '-' ?></td>
                                </tr>
                            </table>
                        </div>
                        <div class="col-md-6">
                            <h6 class="fw-bold mb-3">Info Pemesanan Terkait</h6>
                            <?php if ($pemesanan): ?>
                                <table class="table table-borderless">
                                    <tr>
                                        <td class="fw-bold">ID Pemesanan</td>
                                        <td>#<?= esc($pemesanan['id']) ?></td>
                                    </tr>
                                    <tr>
                                        <td class="fw-bold">Tanggal Pemesanan</td>
                                        <td><?= $pemesanan['tanggal_pemesanan'] ? date('d M Y', strtotime($pemesanan['tanggal_pemesanan'])) : '-' ?></td>
                                    </tr>
                                    <tr>
                                        <td class="fw-bold">Total Harga</td>
                                        <td>Rp <?= number_format($pemesanan['total_harga'] ?? 0, 0, ',', '.') ?></td>
                                    </tr>
                                    <tr>
                                        <td class="fw-bold">Status Pemesanan</td>
                                        <td>
                                            <?php
                                            $badge_class = '';
                                            switch ($pemesanan['status_pemesanan'] ?? '') {
                                                case 'menunggu':
                                                    $badge_class = 'bg-warning';
                                                    break;
                                                case 'diproses':
                                                    $badge_class = 'bg-info';
                                                    break;
                                                case 'selesai':
                                                    $badge_class = 'bg-success';
                                                    break;
                                                case 'dibatalkan':
                                                    $badge_class = 'bg-danger';
                                                    break;
                                                default:
                                                    $badge_class = 'bg-secondary';
                                            }
                                            ?>
                                            <span class="badge <?= $badge_class ?> py-1 px-2">
                                                <?= ucfirst($pemesanan['status_pemesanan'] ?? '-') ?>
                                            </span>
                                        </td>
                                    </tr>
                                    <tr>
                                        <td class="fw-bold">Catatan</td>
                                        <td><?= nl2br(esc($pemesanan['catatan'] ?? '-')) ?></td>
                                    </tr>
                                </table>
                            <?php else: ?>
                                <div class="alert alert-warning">Data pemesanan tidak ditemukan.</div>
                            <?php endif; ?>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
<?= $this->endSection(); ?>