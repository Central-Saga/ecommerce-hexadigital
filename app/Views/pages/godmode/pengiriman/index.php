<?php /** @var \CodeIgniter\View\View $this */ ?>
<?= $this->extend('layouts/admin_layout'); ?>

<?= $this->section('title') ?>Daftar Pengiriman<?= $this->endSection() ?>

<?= $this->section('content'); ?>
<div class="container-fluid px-4">
    <h1 class="mt-4"><?= $title ?? 'Daftar Pengiriman' ?></h1>
    <ol class="breadcrumb mb-4">
        <li class="breadcrumb-item"><a href="<?= base_url('godmode') ?>">Dashboard</a></li>
        <li class="breadcrumb-item active">Pengiriman</li>
    </ol>

    <!-- Alert Success -->
    <?php if (session()->getFlashdata('success')) : ?>
        <div class="alert alert-success alert-dismissible fade show" role="alert">
            <?= session()->getFlashdata('success') ?>
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>
    <?php endif; ?>

    <!-- Alert Error -->
    <?php if (session()->getFlashdata('error')) : ?>
        <div class="alert alert-danger alert-dismissible fade show" role="alert">
            <?= session()->getFlashdata('error') ?>
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>
    <?php endif; ?>

    <div class="card mb-4">
        <div class="card-header">
            <i class="fas fa-table me-1"></i>
            Data Pengiriman
            <a href="<?= base_url('godmode/pengiriman/create') ?>" class="btn btn-primary btn-sm float-end">
                <i class="fas fa-plus"></i> Tambah
            </a>
        </div>
        <div class="card-body">
            <table id="datatablesSimple" class="table table-striped">
                <thead>
                    <tr>
                        <th>No.</th>
                        <th>No. Pesanan</th>
                        <th>Tanggal Kirim</th>
                        <th>Tanggal Terima</th>
                        <th>Status</th>
                        <th>Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    <?php $i = 1; ?>
                    <?php foreach ($pengiriman as $p) : ?>
                        <tr>
                            <td><?= $i++ ?></td>
                            <td><?= $p['nomor_pesanan'] ?? 'Tidak Ada' ?></td>
                            <td><?= $p['tanggal_kirim'] ? date('d-m-Y', strtotime($p['tanggal_kirim'])) : '-' ?></td>
                            <td><?= $p['tanggal_terima'] ? date('d-m-Y', strtotime($p['tanggal_terima'])) : '-' ?></td>
                            <td>
                                <?php 
                                    $badge_class = '';
                                    switch ($p['status']) {
                                        case 'diproses':
                                            $badge_class = 'bg-info';
                                            break;
                                        case 'dikirim':
                                            $badge_class = 'bg-primary';
                                            break;
                                        case 'diterima':
                                            $badge_class = 'bg-success';
                                            break;
                                        case 'dibatalkan':
                                            $badge_class = 'bg-danger';
                                            break;
                                        default:
                                            $badge_class = 'bg-secondary';
                                    }
                                ?>
                                <span class="badge <?= $badge_class ?>">
                                    <?= ucfirst($p['status']) ?>
                                </span>
                            </td>
                            <td>
                                <a href="<?= base_url('godmode/pengiriman/edit/' . $p['id']) ?>" class="btn btn-warning btn-sm">
                                    <i class="fas fa-edit"></i> Edit
                                </a>
                                <button type="button" class="btn btn-danger btn-sm" data-bs-toggle="modal" data-bs-target="#deleteModal<?= $p['id'] ?>">
                                    <i class="fas fa-trash"></i> Hapus
                                </button>

                                <!-- Delete Modal -->
                                <div class="modal fade" id="deleteModal<?= $p['id'] ?>" tabindex="-1" aria-labelledby="deleteModalLabel" aria-hidden="true">
                                    <div class="modal-dialog">
                                        <div class="modal-content">
                                            <div class="modal-header">
                                                <h5 class="modal-title" id="deleteModalLabel">Konfirmasi Hapus</h5>
                                                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                                            </div>
                                            <div class="modal-body">
                                                Apakah anda yakin ingin menghapus data pengiriman ini?
                                            </div>
                                            <div class="modal-footer">
                                                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Batal</button>
                                                <form action="<?= base_url('godmode/pengiriman/delete/' . $p['id']) ?>" method="post" class="d-inline">
                                                    <?= csrf_field() ?>
                                                    <button type="submit" class="btn btn-danger">Hapus</button>
                                                </form>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </td>
                        </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        </div>
    </div>
</div>
<?= $this->endSection(); ?>