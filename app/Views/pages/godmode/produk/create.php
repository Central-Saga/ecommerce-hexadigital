<!-- filepath: c:\laragon\www\ecommerce-hexadigital\app\Views\pages\godmode\produk\create.php -->
<?php /** @var \CodeIgniter\View\View $this */ ?>
<?= $this->extend('layouts/admin') ?>

<?= $this->section('title') ?>Tambah Produk Baru<?= $this->endSection() ?>

<?= $this->section('content') ?>
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
            <h5 class="mb-0">Tambah Produk Baru</h5>
        </div>
        <div class="card-body">
            <form action="<?= site_url('godmode/produk/store') ?>" method="post" enctype="multipart/form-data" class="needs-validation" novalidate>
                <?= csrf_field() ?>

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
                                value="<?= old('nama_produk') ?>"
                                required>
                            <div class="invalid-feedback">
                                <?= session('errors.nama_produk') ?>
                            </div>
                        </div>

                        <div class="form-group">
                            <label for="kategori_id" class="form-label">
                                <i class="bi bi-tags me-1"></i>Kategori Produk
                            </label>
                            <select class="form-select <?= session('errors.kategori_id') ? 'is-invalid' : '' ?>"
                                id="kategori_id"
                                name="kategori_id"
                                required>
                                <option value="" selected disabled>Pilih Kategori</option>
                                <?php if (isset($kategoris) && count($kategoris) > 0): ?>
                                    <?php foreach ($kategoris as $kategori): ?>
                                        <option value="<?= $kategori['id'] ?>" <?= old('kategori_id') == $kategori['id'] ? 'selected' : '' ?>>
                                            <?= esc($kategori['nama_kategori']) ?>
                                        </option>
                                    <?php endforeach ?>
                                <?php else: ?>
                                    <option value="" disabled>Tidak ada kategori, silakan tambah kategori dulu.</option>
                                <?php endif ?>
                            </select>
                            <div class="invalid-feedback">
                                <?= session('errors.kategori_id') ?>
                            </div>
                        </div>

                        <div class="row">
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="harga" class="form-label">
                                        <i class="bi bi-currency-dollar me-1"></i>Harga (Rp)
                                    </label>
                                    <input type="text"
                                        class="form-control <?= session('errors.harga') ? 'is-invalid' : '' ?>"
                                        id="harga"
                                        name="harga"
                                        value="<?= old('harga') ?>"
                                        required>
                                    <div class="invalid-feedback">
                                        <?= session('errors.harga') ?>
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="stok" class="form-label">
                                        <i class="bi bi-boxes me-1"></i>Stok
                                    </label>
                                    <input type="number"
                                        class="form-control <?= session('errors.stok') ? 'is-invalid' : '' ?>"
                                        id="stok"
                                        name="stok"
                                        value="<?= old('stok', 0) ?>"
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
                                rows="5"
                                required><?= old('deskripsi') ?></textarea>
                            <div class="invalid-feedback">
                                <?= session('errors.deskripsi') ?>
                            </div>
                        </div>
                    </div>

                    <div class="col-md-4">
                        <div class="form-group">
                            <label class="form-label">
                                <i class="bi bi-image me-1"></i>Gambar Produk
                            </label>
                            <div class="card border">
                                <div class="card-body text-center">
                                    <img id="preview"
                                        src="<?= base_url('assets/img/placeholder-image.png') ?>"
                                        class="image-preview mb-3">

                                    <div class="input-group">
                                        <input type="file"
                                            class="form-control <?= session('errors.gambar') ? 'is-invalid' : '' ?>"
                                            id="gambar"
                                            name="gambar"
                                            accept="image/*"
                                            required>
                                        <label class="input-group-text" for="gambar">
                                            <i class="bi bi-upload"></i>
                                        </label>
                                    </div>
                                    <div class="invalid-feedback">
                                        <?php
                                        $errors = session('errors') ?? [];
                                        $gambarError = isset($errors['gambar']) ? $errors['gambar'] : (isset($validation) && $validation->hasError('gambar') ? $validation->getError('gambar') : null);
                                        echo $gambarError ?? 'Gambar produk harus diupload';
                                        ?>
                                    </div>
                                    <small class="text-muted d-block mt-2">
                                        Format: JPG, PNG, GIF. Maks 2MB
                                    </small>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="d-flex justify-content-end gap-2 mt-4">
                    <a href="<?= site_url('godmode/produk') ?>" class="btn btn-secondary">
                        <i class="bi bi-arrow-left me-1"></i>Kembali
                    </a>
                    <button type="submit" class="btn btn-primary">
                        <i class="bi bi-save me-1"></i>Simpan Produk
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

        // Preview gambar yang diupload
        const gambarInput = document.getElementById('gambar');
        const previewImg = document.getElementById('preview');

        gambarInput.addEventListener('change', function() {
            const file = this.files[0];
            if (file) {
                const reader = new FileReader();
                reader.onload = function(e) {
                    previewImg.src = e.target.result;
                }
                reader.readAsDataURL(file);
            } else {
                previewImg.src = '<?= base_url('assets/img/placeholder-image.png') ?>';
            }
        });

        // Format input harga dengan separator ribuan
        const hargaInput = document.getElementById('harga');
        hargaInput.addEventListener('input', function(e) {
            // Ambil hanya digit angka
            let value = this.value.replace(/[^0-9]/g, '');
            if (value.length > 0) {
                // Format ribuan
                this.value = parseInt(value, 10).toLocaleString('id-ID');
            } else {
                this.value = '';
            }
        });

        // Pastikan harga hanya angka sebelum submit
        const form = document.querySelector('form');
        form.addEventListener('submit', function(e) {
            const hargaInput = document.getElementById('harga');
            hargaInput.value = hargaInput.value.replace(/[^0-9]/g, '');
        });
    });
</script>
<?= $this->endSection() ?>