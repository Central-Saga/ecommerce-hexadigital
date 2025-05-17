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
                <a href="/godmode/pemesanan/edit/<?= $pemesanan['id'] ?>" class="btn btn-primary btn-sm">
                    <i class="bi bi-pencil me-1"></i>Edit
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
                            <tr>                                <td class="fw-bold">Tanggal Pesanan</td>
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
                                    switch($pemesanan['status_pemesanan']) {
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
                                    ?>                                    <span class="badge <?= $statusClass ?> py-1 px-2">
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
            </div>
        </div>
    </div>
</div>
<?= $this->endSection() ?>
