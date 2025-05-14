<!-- filepath: c:\laragon\www\ecommerce-hexadigital\app\Views\pages\godmode\produk\edit.php -->
<?php /** @var \CodeIgniter\View\View $this */ ?>
<?= $this->extend('layouts/admin'); ?>

<?= $this->section('title') ?>Edit Produk<?= $this->endSection() ?>

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

<div class="product-form">
    <div class="card">
        <div class="card-header d-flex align-items-center gap-2">
            <i class="bi bi-box-seam text-primary fs-4"></i>
            <h5 class="mb-0">Edit Produk: <?= esc($produk['nama_produk']) ?></h5>
        </div>
        <div class="card-body">
            <form action="<?= site_url('godmode/produk/update/' . $produk['id']) ?>" method="post" enctype="multipart/form-data" class="needs-validation" novalidate>
                <?= csrf_field() ?>
                <input type="hidden" name="_method" value="PUT">

                <div class="row g-4">
                    <div class="col-md-8">
                        <div class="form-group">
                            <label for="nama_produk" class="form-label">
                                <i class="bi bi-box me-1"></i>Nama Produk
                            </label>
                            <input type="text"
                                class="form-control <?= session('errors.nama_produk') ? 'is-invalid' : '' ?>"
                                id="nama_produk"
                                name="nama_produk"
                                value="<?= old('nama_produk', $produk['nama_produk']) ?>"
                                required>
                            <div class="invalid-feedback">
                                <?= session('errors.nama_produk') ?>
                            </div>
                        </div>

                        <div class="form-group">
                            <label for="kategori_id" class="form-label">
                                <i class="bi bi-tags me-1"></i>Kategori
                            </label>
                            <select class="form-select <?= session('errors.kategori_id') ? 'is-invalid' : '' ?>"
                                id="kategori_id"
                                name="kategori_id">
                                <option value="">Pilih Kategori</option>
                                <?php foreach ($kategoris as $kategori): ?>
                                    <option value="<?= $kategori['id'] ?>" <?= (old('kategori_id', $produk['kategori_id']) == $kategori['id']) ? 'selected' : '' ?>>
                                        <?= esc($kategori['nama_kategori']) ?>
                                    </option>
                                <?php endforeach; ?>
                            </select>
                            <div class="invalid-feedback">
                                <?= session('errors.kategori_id') ?>
                            </div>
                        </div>

                        <div class="row">
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="harga" class="form-label">
                                        <i class="bi bi-currency-dollar me-1"></i>Harga
                                    </label>
                                    <div class="input-group">
                                        <span class="input-group-text">Rp</span>
                                        <input type="number"
                                            class="form-control <?= session('errors.harga') ? 'is-invalid' : '' ?>"
                                            id="harga"
                                            name="harga"
                                            value="<?= old('harga', $produk['harga']) ?>"
                                            step="0.01"
                                            min="0"
                                            required>
                                    </div>
                                    <div class="invalid-feedback">
                                        <?= session('errors.harga') ?>
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="stok" class="form-label">
                                        <i class="bi bi-box2 me-1"></i>Stok
                                    </label>
                                    <input type="number"
                                        class="form-control <?= session('errors.stok') ? 'is-invalid' : '' ?>"
                                        id="stok"
                                        name="stok"
                                        value="<?= old('stok', $produk['stok']) ?>"
                                        min="0"
                                        required>
                                    <div class="invalid-feedback">
                                        <?= session('errors.stok') ?>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="form-group">
                            <label for="deskripsi" class="form-label">
                                <i class="bi bi-card-text me-1"></i>Deskripsi Produk
                            </label>
                            <textarea class="form-control <?= session('errors.deskripsi') ? 'is-invalid' : '' ?>"
                                id="deskripsi"
                                name="deskripsi"
                                rows="4"><?= old('deskripsi', $produk['deskripsi']) ?></textarea>
                            <div class="invalid-feedback">
                                <?= session('errors.deskripsi') ?>
                            </div>
                        </div>
                    </div>

                    <div class="col-md-4">
                        <div class="card">
                            <div class="card-header bg-light">
                                <h5 class="card-title mb-0">
                                    <i class="bi bi-image me-1"></i>Gambar Produk
                                </h5>
                            </div>
                            <div class="card-body">
                                <div class="mb-3 text-center">
                                    <?php if (!empty($produk['gambar']) && file_exists(ROOTPATH . 'public/uploads/produk/' . $produk['gambar'])): ?>
                                        <img id="preview-image"
                                            src="<?= base_url('uploads/produk/' . $produk['gambar']) ?>"
                                            alt="<?= esc($produk['nama_produk']) ?>"
                                            class="img-thumbnail mb-2"
                                            style="max-height: 200px;">
                                    <?php else: ?>
                                        <img id="preview-image"
                                            src="<?= base_url('assets/img/no-image.png') ?>"
                                            alt="No Image"
                                            class="img-thumbnail mb-2"
                                            style="max-height: 200px;">
                                    <?php endif; ?>

                                    <div class="mt-2">
                                        <input type="file"
                                            class="form-control <?= session('errors.gambar') ? 'is-invalid' : '' ?>"
                                            id="gambar"
                                            name="gambar"
                                            accept="image/*">
                                    </div>
                                    <div class="form-text">
                                        Format: JPG, PNG. Maks: 2MB<br>
                                        Biarkan kosong jika tidak ingin mengubah gambar
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="card mt-3">
                            <div class="card-header bg-light">
                                <h5 class="card-title mb-0">
                                    <i class="bi bi-info-circle me-1"></i>Informasi Tambahan
                                </h5>
                            </div>
                            <div class="card-body">
                                <p class="mb-1"><strong>Dibuat pada:</strong></p>
                                <p class="text-muted"><?= date('d M Y H:i', strtotime($produk['created_at'])) ?></p>

                                <p class="mb-1"><strong>Terakhir diupdate:</strong></p>
                                <p class="text-muted"><?= date('d M Y H:i', strtotime($produk['updated_at'])) ?></p>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="d-flex justify-content-end gap-2 mt-4">
                    <a href="<?= site_url('godmode/produk') ?>" class="btn btn-secondary">
                        <i class="bi bi-arrow-left me-1"></i>Kembali
                    </a>
                    <button type="submit" class="btn btn-primary">
                        <i class="bi bi-save me-1"></i>Simpan Perubahan
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

        // Preview image before upload
        const gambarInput = document.getElementById('gambar');
        const previewImg = document.getElementById('preview-image');

        gambarInput.addEventListener('change', function() {
            const file = this.files[0];
            if (file) {
                const reader = new FileReader();
                reader.onload = function(e) {
                    previewImg.src = e.target.result;
                }
                reader.readAsDataURL(file);
            } else {
                <?php if (!empty($produk['gambar']) && file_exists(ROOTPATH . 'public/uploads/produk/' . $produk['gambar'])): ?>
                    previewImg.src = '<?= base_url('uploads/produk/' . $produk['gambar']) ?>';
                <?php else: ?>
                    previewImg.src = '<?= base_url('assets/img/no-image.png') ?>';
                <?php endif; ?>
            }
        });
    });
</script>
<?= $this->endSection(); ?>