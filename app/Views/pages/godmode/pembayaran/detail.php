<?= $this->extend('layouts/admin') ?>
<?= $this->section('content') ?>
<div class="pembayaran-detail">
    <div class="card mb-4">
        <div class="card-header d-flex justify-content-between align-items-center">
            <div class="d-flex align-items-center gap-2">
                <i class="bi bi-credit-card-2-front-fill text-primary fs-4"></i>
                <h5 class="mb-0">Detail Pembayaran #<?= esc($pembayaran['id']) ?></h5>
            </div>
            <div>
                <a href="<?= base_url('godmode/pembayaran') ?>" class="btn btn-secondary btn-sm">
                    <i class="bi bi-arrow-left me-1"></i>Kembali
                </a>
            </div>
        </div>
        <div class="card-body">
            <div class="row">
                <div class="col-md-6 mb-4">
                    <div class="pembayaran-info">
                        <h6 class="fw-bold mb-3">Informasi Pembayaran</h6>
                        <table class="table table-borderless">
                            <tr>
                                <td width="40%" class="fw-bold">ID Pembayaran</td>
                                <td width="60%">#<?= esc($pembayaran['id']) ?></td>
                            </tr>
                            <tr>
                                <td class="fw-bold">ID Pemesanan</td>
                                <td>#<?= esc($pembayaran['pemesanan_id']) ?></td>
                            </tr>
                            <tr>
                                <td class="fw-bold">Nama Pengirim</td>
                                <td><?= esc($pembayaran['nama_pengirim']) ?></td>
                            </tr>
                            <tr>
                                <td class="fw-bold">Metode Pembayaran</td>
                                <td><?= esc($pembayaran['metode_pembayaran']) ?></td>
                            </tr>
                            <tr>
                                <td class="fw-bold">Total Dibayar</td>
                                <td>Rp <?= number_format($pembayaran['total_harga'], 0, ',', '.') ?></td>
                            </tr>
                            <tr>
                                <td class="fw-bold">Tanggal Pembayaran</td>
                                <td><?= !empty($pembayaran['tanggal_pembayaran']) ? date('d M Y H:i', strtotime($pembayaran['tanggal_pembayaran'])) : '-' ?></td>
                            </tr>
                            <tr>
                                <td class="fw-bold">Status</td>
                                <td>
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
                        </table>
                    </div>
                </div>
                <div class="col-md-6 mb-4">
                    <div class="catatan-info">
                        <h6 class="fw-bold mb-3">Catatan</h6>
                        <div class="p-3 bg-light rounded">
                            <?= empty($pembayaran['catatan']) ? '<em>Tidak ada catatan</em>' : nl2br(esc($pembayaran['catatan'])) ?>
                        </div>
                    </div>
                </div>
                <div class="col-md-12 mb-4">
                    <div class="bukti-info">
                        <h6 class="fw-bold mb-3">Bukti Pembayaran</h6>
                        <?php if (!empty($pembayaran['bukti_pembayaran'])): ?>
                            <a href="/<?= esc($pembayaran['bukti_pembayaran']) ?>" target="_blank">
                                <img src="/<?= esc($pembayaran['bukti_pembayaran']) ?>" alt="Bukti Pembayaran" style="max-width:150px;max-height:150px;object-fit:contain;border:1px solid #ddd;border-radius:6px;">
                            </a>
                        <?php else: ?>
                            <em>Tidak ada bukti pembayaran</em>
                        <?php endif; ?>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
<?= $this->endSection() ?>