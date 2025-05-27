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

<div class="pembayaran-management">
    <div class="card">
        <div class="card-header">
            <div class="d-flex align-items-center gap-2">
                <i class="bi bi-credit-card-2-front-fill text-primary fs-4"></i>
                <h5 class="mb-0">Edit Pembayaran</h5>
            </div>
        </div>
        <div class="card-body">
            <form action="<?= base_url('godmode/pembayaran/update/' . $pembayaran['id']) ?>" method="post" class="needs-validation" novalidate>
                <?= csrf_field() ?>
                <input type="hidden" name="_method" value="PUT">
                <div class="row g-4">
                    <div class="col-md-6">
                        <div class="form-group">
                            <label for="status" class="form-label">
                                <i class="bi bi-tag me-1"></i>Status Pembayaran
                            </label>
                            <select name="status" id="status" class="form-select <?= session('errors.status') ? 'is-invalid' : '' ?>" required>
                                <option value="pending" <?= old('status', $pembayaran['status']) == 'pending' ? 'selected' : '' ?>>Pending</option>
                                <option value="diterima" <?= old('status', $pembayaran['status']) == 'diterima' ? 'selected' : '' ?>>Diterima</option>
                                <option value="ditolak" <?= old('status', $pembayaran['status']) == 'ditolak' ? 'selected' : '' ?>>Ditolak</option>
                            </select>
                            <div class="invalid-feedback">
                                <?= session('errors.status') ?>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-12">
                        <div class="form-group">
                            <label for="catatan" class="form-label">
                                <i class="bi bi-card-text me-1"></i>Catatan (Opsional)
                            </label>
                            <textarea name="catatan" id="catatan" class="form-control <?= session('errors.catatan') ? 'is-invalid' : '' ?>" rows="4"><?= old('catatan', $pembayaran['catatan']) ?></textarea>
                            <?php if (session('errors.catatan')): ?>
                                <div class="invalid-feedback">
                                    <?= session('errors.catatan') ?>
                                </div>
                            <?php endif; ?>
                        </div>
                    </div>
                </div>
                <div class="d-flex justify-content-end gap-2 mt-4">
                    <a href="<?= base_url('godmode/pembayaran') ?>" class="btn btn-secondary">
                        <i class="bi bi-arrow-left me-1"></i>Batal
                    </a>
                    <button type="submit" class="btn btn-primary">
                        <i class="bi bi-save me-1"></i>Update
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