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

<div class="role-form">
    <div class="card">
        <div class="card-header d-flex align-items-center gap-2">
            <i class="bi bi-shield-plus text-primary fs-4"></i>
            <h5 class="mb-0">Tambah Role Baru</h5>
        </div>
        <div class="card-body">
            <form action="/godmode/role/store" method="POST" class="needs-validation" novalidate>
                <?= csrf_field() ?>

                <div class="row g-4">
                    <div class="col-md-6">
                        <div class="form-group">
                            <label for="name" class="form-label">
                                <i class="bi bi-shield me-1"></i>Nama Role
                            </label>
                            <input type="text"
                                class="form-control <?= session('errors.name') ? 'is-invalid' : '' ?>"
                                id="name"
                                name="name"
                                value="<?= old('name') ?>"
                                required>
                            <div class="invalid-feedback">
                                <?= session('errors.name') ?>
                            </div>
                        </div>
                    </div>

                    <div class="col-md-6">
                        <div class="form-group">
                            <label for="description" class="form-label">
                                <i class="bi bi-card-text me-1"></i>Deskripsi
                            </label>
                            <input type="text"
                                class="form-control <?= session('errors.description') ? 'is-invalid' : '' ?>"
                                id="description"
                                name="description"
                                value="<?= old('description') ?>"
                                required>
                            <div class="invalid-feedback">
                                <?= session('errors.description') ?>
                            </div>
                        </div>
                    </div>

                    <div class="col-md-6">
                        <div class="form-group">
                            <label for="permissions" class="form-label">
                                <i class="bi bi-key me-1"></i>Permissions
                            </label>
                            <select class="form-select <?= session('errors.permissions') ? 'is-invalid' : '' ?>"
                                id="permissions"
                                name="permissions[]"
                                multiple
                                required>
                                <?php
                                $authGroups = new \Config\AuthGroups();
                                foreach ($authGroups->permissions as $permission => $description) {
                                    echo '<option value="' . $permission . '" ' .
                                        (in_array($permission, old('permissions', [])) ? 'selected' : '') . '>' .
                                        $description .
                                        '</option>';
                                }
                                ?>
                            </select>
                            <div class="invalid-feedback">
                                <?= session('errors.permissions') ?>
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
                    <a href="/godmode/role" class="btn btn-secondary">
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