<?php

/** @var \CodeIgniter\View\View $this */ ?>
<?= $this->extend('layouts/admin'); ?>

<?= $this->section('title') ?>Edit Pengiriman<?= $this->endSection() ?>

<?= $this->section('content'); ?>
<!-- Toast Container -->
<div class="toast-container position-fixed top-0 end-0 p-3">
    <?php if (session()->has('errors')) : ?>
        <div class="toast align-items-center text-white bg-danger border-0" role="alert" aria-live="assertive" aria-atomic="true">
            <div class="d-flex">
                <div class="toast-body">
                    <i class="bi bi-x-circle me-2"></i>
                    <?php
                    $errors = session('errors');
                    if (is_array($errors)) {
                        echo implode('<br>', $errors);
                    } else {
                        echo $errors;
                    }
                    ?>
                </div>
                <button type="button" class="btn-close btn-close-white me-2 m-auto" data-bs-dismiss="toast" aria-label="Close"></button>
            </div>
        </div>
    <?php endif; ?>
</div>

<div class="pengiriman-management">
    <div class="card">
        <div class="card-header">
            <div class="d-flex align-items-center gap-2">
                <i class="bi bi-truck text-warning fs-4"></i>
                <h5 class="mb-0">Edit Pengiriman</h5>
            </div>
        </div>
        <div class="card-body">
            <form action="<?= base_url('godmode/pengiriman/update/' . $pengiriman['id']) ?>" method="post" class="needs-validation" novalidate>
                <?= csrf_field() ?>
                <input type="hidden" name="_method" value="PUT">
                <div class="row g-4">
                    <div class="col-md-6">
                        <div class="form-group mb-3">
                            <label for="pemesanan_id" class="form-label">Nomor Pemesanan</label>
                            <select class="form-select <?= session('errors.pemesanan_id') ? 'is-invalid' : '' ?>" id="pemesanan_id" name="pemesanan_id" required>
                                <option value="" selected disabled>Pilih Nomor Pemesanan</option>
                                <?php if (isset($pemesanan) && !empty($pemesanan)) : ?>
                                    <?php foreach ($pemesanan as $p) : ?>
                                        <option value="<?= $p['id'] ?>" <?= (old('pemesanan_id', $pengiriman['pemesanan_id']) == $p['id']) ? 'selected' : '' ?>>
                                            <?= esc($p['nomor_pemesanan'] ?? 'Pemesanan #' . $p['id']) ?>
                                        </option>
                                    <?php endforeach; ?>
                                <?php endif; ?>
                            </select>
                            <div class="invalid-feedback">
                                <?= session('errors.pemesanan_id') ?>
                            </div>
                        </div>
                        <div class="form-group mb-3">
                            <label for="tanggal_kirim" class="form-label">Tanggal Kirim</label>
                            <input type="date" class="form-control <?= session('errors.tanggal_kirim') ? 'is-invalid' : '' ?>" id="tanggal_kirim" name="tanggal_kirim" value="<?= old('tanggal_kirim', $pengiriman['tanggal_kirim']) ?>" required>
                            <div class="invalid-feedback">
                                <?= session('errors.tanggal_kirim') ?>
                            </div>
                        </div>
                        <div class="form-group mb-3">
                            <label for="tanggal_terima" class="form-label">Tanggal Terima</label>
                            <input type="date" class="form-control <?= session('errors.tanggal_terima') ? 'is-invalid' : '' ?>" id="tanggal_terima" name="tanggal_terima" value="<?= old('tanggal_terima', $pengiriman['tanggal_terima']) ?>">
                            <div class="invalid-feedback">
                                <?= session('errors.tanggal_terima') ?>
                            </div>
                        </div>
                        <div class="form-group mb-3">
                            <label for="status" class="form-label">Status Pengiriman</label>
                            <select class="form-select <?= session('errors.status') ? 'is-invalid' : '' ?>" id="status" name="status" required>
                                <option value="" selected disabled>Pilih Status</option>
                                <option value="menunggu" <?= old('status', $pengiriman['status']) == 'menunggu' ? 'selected' : '' ?>>Menunggu</option>
                                <option value="dikirim" <?= old('status', $pengiriman['status']) == 'dikirim' ? 'selected' : '' ?>>Dikirim</option>
                                <option value="diterima" <?= old('status', $pengiriman['status']) == 'diterima' ? 'selected' : '' ?>>Diterima</option>
                                <option value="dibatalkan" <?= old('status', $pengiriman['status']) == 'dibatalkan' ? 'selected' : '' ?>>Dibatalkan</option>
                            </select>
                            <div class="invalid-feedback">
                                <?= session('errors.status') ?>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="form-group mb-3">
                            <label for="deskripsi" class="form-label">Deskripsi Pengiriman</label>
                            <textarea class="form-control <?= session('errors.deskripsi') ? 'is-invalid' : '' ?>" id="deskripsi" name="deskripsi" rows="10" placeholder="Detail pengiriman, catatan khusus, dll..."><?= old('deskripsi', $pengiriman['deskripsi']) ?></textarea>
                            <?php if (session('errors.deskripsi')): ?>
                                <div class="invalid-feedback">
                                    <?= session('errors.deskripsi') ?>
                                </div>
                            <?php endif; ?>
                        </div>
                    </div>
                </div>
                <div class="d-flex justify-content-end gap-2 mt-4">
                    <a href="<?= base_url('godmode/pengiriman') ?>" class="btn btn-secondary">
                        <i class="bi bi-arrow-left me-1"></i>Batal
                    </a>
                    <button type="submit" class="btn btn-warning">
                        <i class="bi bi-save me-1"></i>Update Pengiriman
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

<script>
    document.addEventListener('DOMContentLoaded', function() {
        // Inisialisasi toast
        const toastElList = document.querySelectorAll('.toast');
        const toastList = [...toastElList].map(toastEl => new bootstrap.Toast(toastEl, {
            autohide: true,
            delay: 3000
        }));
        toastList.forEach(toast => toast.show());

        // Form validation
        const forms = document.querySelectorAll('.needs-validation');
        Array.from(forms).forEach(form => {
            form.addEventListener('submit', event => {
                if (!form.checkValidity()) {
                    event.preventDefault();
                    event.stopPropagation();
                }
                form.classList.add('was-validated');
            }, false);
        });
    });
</script>
<?= $this->endSection(); ?>