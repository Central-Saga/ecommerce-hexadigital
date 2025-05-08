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

<div class="customer-form">
    <div class="card">
        <div class="card-header d-flex align-items-center gap-2">
            <i class="bi bi-person-plus-fill text-primary fs-4"></i>
            <h5 class="mb-0">Tambah Pelanggan Baru</h5>
        </div>
        <div class="card-body">
            <form action="/godmode/customer/store" method="POST" class="needs-validation" novalidate>
                <?= csrf_field() ?>

                <div class="row g-4">
                    <div class="col-md-6">
                        <div class="form-group">
                            <label for="user_id" class="form-label">
                                <i class="bi bi-person me-1"></i>User
                            </label>
                            <select class="form-select <?= session('errors.user_id') ? 'is-invalid' : '' ?>"
                                id="user_id"
                                name="user_id"
                                required>
                                <option value="" selected disabled>Pilih User</option>
                                <?php foreach ($users as $user) : ?>
                                    <option value="<?= $user['id'] ?>" <?= old('user_id') == $user['id'] ? 'selected' : '' ?>>
                                        <?= $user['name'] ?> (<?= $user['email'] ?>)
                                    </option>
                                <?php endforeach; ?>
                            </select>
                            <div class="invalid-feedback">
                                <?= session('errors.user_id') ?>
                            </div>
                        </div>
                    </div>

                    <div class="col-md-6">
                        <div class="form-group">
                            <label for="no_telepon" class="form-label">
                                <i class="bi bi-telephone me-1"></i>Nomor Telepon
                            </label>
                            <input type="text"
                                class="form-control <?= session('errors.no_telepon') ? 'is-invalid' : '' ?>"
                                id="no_telepon"
                                name="no_telepon"
                                value="<?= old('no_telepon') ?>"
                                required>
                            <div class="invalid-feedback">
                                <?= session('errors.no_telepon') ?>
                            </div>
                        </div>
                    </div>

                    <div class="col-md-6">
                        <div class="form-group">
                            <label for="jenis_kelamin" class="form-label">
                                <i class="bi bi-gender-ambiguous me-1"></i>Jenis Kelamin
                            </label>
                            <select class="form-select <?= session('errors.jenis_kelamin') ? 'is-invalid' : '' ?>"
                                id="jenis_kelamin"
                                name="jenis_kelamin"
                                required>
                                <option value="" selected disabled>Pilih Jenis Kelamin</option>
                                <option value="L" <?= old('jenis_kelamin') === 'L' ? 'selected' : '' ?>>Laki-laki</option>
                                <option value="P" <?= old('jenis_kelamin') === 'P' ? 'selected' : '' ?>>Perempuan</option>
                            </select>
                            <div class="invalid-feedback">
                                <?= session('errors.jenis_kelamin') ?>
                            </div>
                        </div>
                    </div>

                    <div class="col-md-6">
                        <div class="form-group">
                            <label for="umur" class="form-label">
                                <i class="bi bi-calendar me-1"></i>Umur
                            </label>
                            <input type="number"
                                class="form-control <?= session('errors.umur') ? 'is-invalid' : '' ?>"
                                id="umur"
                                name="umur"
                                value="<?= old('umur') ?>"
                                required>
                            <div class="invalid-feedback">
                                <?= session('errors.umur') ?>
                            </div>
                        </div>
                    </div>

                    <div class="col-12">
                        <div class="form-group">
                            <label for="alamat" class="form-label">
                                <i class="bi bi-geo-alt me-1"></i>Alamat
                            </label>
                            <textarea class="form-control <?= session('errors.alamat') ? 'is-invalid' : '' ?>"
                                id="alamat"
                                name="alamat"
                                rows="3"
                                required><?= old('alamat') ?></textarea>
                            <div class="invalid-feedback">
                                <?= session('errors.alamat') ?>
                            </div>
                        </div>
                    </div>

                    <div class="col-md-6">
                        <div class="form-group">
                            <label for="status" class="form-label">
                                <i class="bi bi-toggle-on me-1"></i>Status
                            </label>
                            <select class="form-select <?= session('errors.status') ? 'is-invalid' : '' ?>"
                                id="status"
                                name="status"
                                required>
                                <option value="active" <?= old('status') === 'active' ? 'selected' : '' ?>>Aktif</option>
                                <option value="inactive" <?= old('status') === 'inactive' ? 'selected' : '' ?>>Nonaktif</option>
                            </select>
                            <div class="invalid-feedback">
                                <?= session('errors.status') ?>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="d-flex justify-content-end gap-2 mt-4">
                    <a href="/godmode/customer" class="btn btn-secondary">
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