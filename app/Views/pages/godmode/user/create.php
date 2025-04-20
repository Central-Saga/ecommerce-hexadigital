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

<div class="user-form">
    <div class="card">
        <div class="card-header d-flex align-items-center gap-2">
            <i class="bi bi-person-plus-fill text-primary fs-4"></i>
            <h5 class="mb-0">Tambah User Baru</h5>
        </div>
        <div class="card-body">
            <form action="/godmode/user/store" method="POST" class="needs-validation" novalidate>
                <?= csrf_field() ?>

                <div class="row g-4">
                    <div class="col-md-6">
                        <div class="form-group">
                            <label for="username" class="form-label">
                                <i class="bi bi-person me-1"></i>Username
                            </label>
                            <input type="text"
                                class="form-control <?= session('errors.username') ? 'is-invalid' : '' ?>"
                                id="username"
                                name="username"
                                value="<?= old('username') ?>"
                                required>
                            <div class="invalid-feedback">
                                <?= session('errors.username') ?>
                            </div>
                        </div>
                    </div>

                    <div class="col-md-6">
                        <div class="form-group">
                            <label for="email" class="form-label">
                                <i class="bi bi-envelope me-1"></i>Email
                            </label>
                            <input type="email"
                                class="form-control <?= session('errors.email') ? 'is-invalid' : '' ?>"
                                id="email"
                                name="email"
                                value="<?= old('email') ?>"
                                required>
                            <div class="invalid-feedback">
                                <?= session('errors.email') ?>
                            </div>
                        </div>
                    </div>

                    <div class="col-md-6">
                        <div class="form-group">
                            <label for="password" class="form-label">
                                <i class="bi bi-key me-1"></i>Password
                            </label>
                            <div class="input-group">
                                <input type="password"
                                    class="form-control <?= session('errors.password') ? 'is-invalid' : '' ?>"
                                    id="password"
                                    name="password"
                                    required>
                                <button class="btn btn-outline-secondary" type="button" id="togglePassword">
                                    <i class="bi bi-eye"></i>
                                </button>
                                <div class="invalid-feedback">
                                    <?= session('errors.password') ?>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="col-md-6">
                        <div class="form-group">
                            <label for="password_confirm" class="form-label">
                                <i class="bi bi-key-fill me-1"></i>Konfirmasi Password
                            </label>
                            <div class="input-group">
                                <input type="password"
                                    class="form-control <?= session('errors.password_confirm') ? 'is-invalid' : '' ?>"
                                    id="password_confirm"
                                    name="password_confirm"
                                    required>
                                <button class="btn btn-outline-secondary" type="button" id="togglePasswordConfirm">
                                    <i class="bi bi-eye"></i>
                                </button>
                                <div class="invalid-feedback">
                                    <?= session('errors.password_confirm') ?>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="col-md-6">
                        <div class="form-group">
                            <label for="role" class="form-label">
                                <i class="bi bi-shield me-1"></i>Role
                            </label>
                            <select class="form-select <?= session('errors.role') ? 'is-invalid' : '' ?>"
                                id="role"
                                name="role"
                                required>
                                <option value="" selected disabled>Pilih Role</option>
                                <option value="admin" <?= old('role') === 'admin' ? 'selected' : '' ?>>Admin</option>
                                <option value="pegawai" <?= old('role') === 'pegawai' ? 'selected' : '' ?>>Pegawai</option>
                                <option value="pelanggan" <?= old('role') === 'pelanggan' ? 'selected' : '' ?>>Pelanggan</option>
                            </select>
                            <div class="invalid-feedback">
                                <?= session('errors.role') ?>
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
                    <a href="/godmode/user" class="btn btn-secondary">
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

        // Toggle password visibility
        const togglePassword = document.querySelector('#togglePassword');
        const password = document.querySelector('#password');
        togglePassword.addEventListener('click', function() {
            const type = password.getAttribute('type') === 'password' ? 'text' : 'password';
            password.setAttribute('type', type);
            this.querySelector('i').classList.toggle('bi-eye');
            this.querySelector('i').classList.toggle('bi-eye-slash');
        });

        const togglePasswordConfirm = document.querySelector('#togglePasswordConfirm');
        const passwordConfirm = document.querySelector('#password_confirm');
        togglePasswordConfirm.addEventListener('click', function() {
            const type = passwordConfirm.getAttribute('type') === 'password' ? 'text' : 'password';
            passwordConfirm.setAttribute('type', type);
            this.querySelector('i').classList.toggle('bi-eye');
            this.querySelector('i').classList.toggle('bi-eye-slash');
        });

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