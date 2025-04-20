<?= $this->extend('layouts/admin') ?>

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

<div class="kategori-form">
    <div class="card">
        <div class="card-header d-flex align-items-center gap-2">
            <i class="bi bi-tag-fill text-primary fs-4"></i>
            <h5 class="mb-0">Tambah Kategori</h5>
        </div>
        <div class="card-body">
            <form action="/godmode/kategori/store" method="POST" class="needs-validation" novalidate>
                <?= csrf_field() ?>

                <div class="row g-4">
                    <div class="col-md-12">
                        <div class="form-group">
                            <label for="nama_kategori" class="form-label">
                                <i class="bi bi-tag me-1"></i>Nama Kategori
                            </label>
                            <input type="text"
                                class="form-control <?= session('errors.nama_kategori') ? 'is-invalid' : '' ?>"
                                id="nama_kategori"
                                name="nama_kategori"
                                value="<?= old('nama_kategori') ?>"
                                required>
                            <div class="invalid-feedback">
                                <?= session('errors.nama_kategori') ?>
                            </div>
                            <div class="form-text">Nama kategori harus terdiri dari 3-100 karakter</div>
                        </div>
                    </div>

                    <div class="col-md-12">
                        <div class="form-group">
                            <label for="deskripsi_kategori" class="form-label">
                                <i class="bi bi-card-text me-1"></i>Deskripsi (Opsional)
                            </label>
                            <textarea 
                                class="form-control <?= session('errors.deskripsi_kategori') ? 'is-invalid' : '' ?>"
                                id="deskripsi_kategori"
                                name="deskripsi_kategori"
                                rows="4"><?= old('deskripsi_kategori') ?></textarea>
                            <div class="invalid-feedback">
                                <?= session('errors.deskripsi_kategori') ?>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="d-flex justify-content-end gap-2 mt-4">
                    <a href="/godmode/kategori" class="btn btn-secondary">
                        <i class="bi bi-arrow-left me-1"></i>Kembali
                    </a>
                    <button type="submit" class="btn btn-primary">
                        <i class="bi bi-save me-1"></i>Simpan
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
<?= $this->endSection() ?>