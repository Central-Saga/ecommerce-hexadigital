<?php

/** @var \CodeIgniter\View\View $this */ ?>
<?= $this->extend('layouts/admin_layout'); ?>

<?= $this->section('title') ?>Tambah Pengiriman Baru<?= $this->endSection() ?>

<?= $this->section('content'); ?>
<div class="container-fluid px-4 py-3">
    <div class="row">
        <div class="col-12">
            <div class="card shadow border-0">
                <div class="card-header bg-gradient-primary text-white">
                    <h5 class="card-title mb-0">
                        <i class="fas fa-plus-circle me-2"></i>Tambah Pengiriman Baru
                    </h5>
                </div>
                <div class="card-body">
                    <?php if (session()->has('errors')): ?>
                        <div class="alert alert-danger">
                            <ul class="mb-0">
                                <?php foreach (session('errors') as $error): ?>
                                    <li><?= esc($error) ?></li>
                                <?php endforeach ?>
                            </ul>
                        </div>
                    <?php endif; ?>

                    <form action="<?= base_url('godmode/pengiriman/store') ?>" method="post">
                        <?= csrf_field() ?>

                        <div class="row mb-3">
                            <div class="col-md-6">
                                <div class="form-floating mb-3">
                                    <select class="form-select" id="pemesanan_id" name="pemesanan_id">
                                        <option value="" selected disabled>Pilih Nomor Pemesanan</option>
                                        <?php if (isset($pemesanan) && !empty($pemesanan)) : ?>
                                            <?php foreach ($pemesanan as $p) : ?>
                                                <option value="<?= $p['id'] ?>" <?= old('pemesanan_id') == $p['id'] ? 'selected' : '' ?>>
                                                    <?= esc($p['nomor_pemesanan'] ?? 'Pemesanan #' . $p['id']) ?>
                                                </option>
                                            <?php endforeach; ?>
                                        <?php endif; ?>
                                    </select>
                                    <label for="pemesanan_id">Nomor Pemesanan</label>
                                </div>

                                <div class="form-floating mb-3">
                                    <input type="date" class="form-control" id="tanggal_kirim" name="tanggal_kirim"
                                        value="<?= old('tanggal_kirim') ?>">
                                    <label for="tanggal_kirim">Tanggal Kirim</label>
                                </div>

                                <div class="form-floating mb-3">
                                    <input type="date" class="form-control" id="tanggal_terima" name="tanggal_terima"
                                        value="<?= old('tanggal_terima') ?>">
                                    <label for="tanggal_terima">Tanggal Terima</label>
                                </div>

                                <div class="form-floating mb-3">
                                    <select class="form-select" id="status" name="status">
                                        <option value="" selected disabled>Pilih Status</option>
                                        <option value="diproses" <?= old('status') == 'diproses' ? 'selected' : '' ?>>Diproses</option>
                                        <option value="dikirim" <?= old('status') == 'dikirim' ? 'selected' : '' ?>>Dikirim</option>
                                        <option value="diterima" <?= old('status') == 'diterima' ? 'selected' : '' ?>>Diterima</option>
                                        <option value="dibatalkan" <?= old('status') == 'dibatalkan' ? 'selected' : '' ?>>Dibatalkan</option>
                                    </select>
                                    <label for="status">Status Pengiriman</label>
                                </div>
                            </div>

                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label for="deskripsi" class="form-label">Deskripsi Pengiriman</label>
                                    <textarea class="form-control" id="deskripsi" name="deskripsi"
                                        rows="10" placeholder="Detail pengiriman, catatan khusus, dll..."><?= old('deskripsi') ?></textarea>
                                </div>
                            </div>
                        </div>

                        <hr>

                        <div class="d-flex justify-content-between">
                            <a href="<?= base_url('godmode/pengiriman') ?>" class="btn btn-secondary">
                                <i class="fas fa-arrow-left me-1"></i> Kembali
                            </a>
                            <button type="submit" class="btn btn-primary">
                                <i class="fas fa-save me-1"></i> Simpan Pengiriman
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
<?= $this->endSection(); ?>