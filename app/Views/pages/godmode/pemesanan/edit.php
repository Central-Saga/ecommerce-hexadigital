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

<div class="pemesanan-form">
    <div class="card">
        <div class="card-header d-flex align-items-center gap-2">
            <i class="bi bi-cart-fill text-primary fs-4"></i>
            <h5 class="mb-0">Edit Pemesanan</h5>
        </div>
        <div class="card-body">
            <form action="/godmode/pemesanan/update/<?= $pemesanan['id'] ?>" method="POST" class="needs-validation" novalidate>
                <?= csrf_field() ?>
                <input type="hidden" name="_method" value="PUT">

                <div class="row g-4">
                    <div class="col-md-6">
                        <div class="form-group">
                            <label class="form-label">
                                <i class="bi bi-person me-1"></i>Pelanggan
                            </label>
                            <input type="text" class="form-control" value="<?= esc($pelanggans[array_search($pemesanan['pelanggan_id'], array_column($pelanggans, 'id'))]['name'] ?? '-') ?> (<?= esc($pelanggans[array_search($pemesanan['pelanggan_id'], array_column($pelanggans, 'id'))]['email'] ?? '-') ?>)" readonly>
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="form-group">
                            <label class="form-label">
                                <i class="bi bi-calendar me-1"></i>Tanggal Pemesanan
                            </label>
                            <input type="date" class="form-control" value="<?= esc(date('Y-m-d', strtotime($pemesanan['tanggal_pemesanan']))) ?>" readonly>
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="form-group">
                            <label class="form-label">
                                <i class="bi bi-currency-dollar me-1"></i>Total Harga (Rp)
                            </label>
                            <input type="text" class="form-control" value="<?= number_format($pemesanan['total_harga'], 0, ',', '.') ?>" readonly>
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="form-group">
                            <label for="status_pemesanan" class="form-label">
                                <i class="bi bi-tag me-1"></i>Status Pemesanan
                            </label>
                            <select class="form-select <?= session('errors.status_pemesanan') ? 'is-invalid' : '' ?>"
                                id="status_pemesanan"
                                name="status_pemesanan"
                                required>
                                <option value="menunggu" <?= old('status_pemesanan', $pemesanan['status_pemesanan']) === 'menunggu' ? 'selected' : '' ?>>Menunggu</option>
                                <option value="diproses" <?= old('status_pemesanan', $pemesanan['status_pemesanan']) === 'diproses' ? 'selected' : '' ?>>Diproses</option>
                                <option value="selesai" <?= old('status_pemesanan', $pemesanan['status_pemesanan']) === 'selesai' ? 'selected' : '' ?>>Selesai</option>
                                <option value="dibatalkan" <?= old('status_pemesanan', $pemesanan['status_pemesanan']) === 'dibatalkan' ? 'selected' : '' ?>>Dibatalkan</option>
                            </select>
                            <div class="invalid-feedback">
                                <?= session('errors.status_pemesanan') ?>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-12">
                        <div class="form-group">
                            <label class="form-label">
                                <i class="bi bi-card-text me-1"></i>Catatan (Opsional)
                            </label>
                            <textarea class="form-control <?= session('errors.catatan') ? 'is-invalid' : '' ?>" id="catatan" name="catatan" rows="4"><?= old('catatan', $pemesanan['catatan']) ?></textarea>
                            <?php if (session('errors.catatan')): ?>
                                <div class="invalid-feedback">
                                    <?= session('errors.catatan') ?>
                                </div>
                            <?php endif; ?>
                        </div>
                    </div>
                </div>

                <div class="d-flex justify-content-end gap-2 mt-4">
                    <a href="/godmode/pemesanan" class="btn btn-secondary">
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
    });
</script>
<?= $this->endSection() ?>