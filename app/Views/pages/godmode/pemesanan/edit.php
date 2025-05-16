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
                            <label for="pelanggan_id" class="form-label">
                                <i class="bi bi-person me-1"></i>Pelanggan
                            </label>
                            <select class="form-select <?= session('errors.pelanggan_id') ? 'is-invalid' : '' ?>"
                                id="pelanggan_id"
                                name="pelanggan_id"
                                required>
                                <option value="" disabled>Pilih Pelanggan</option>
                                <?php if (isset($pelanggans) && count($pelanggans) > 0): ?>
                                    <?php foreach ($pelanggans as $pelanggan): ?>
                                        <option value="<?= $pelanggan['id'] ?>" <?= old('pelanggan_id', $pemesanan['pelanggan_id']) == $pelanggan['id'] ? 'selected' : '' ?>>
                                            <?= esc($pelanggan['name']) ?> (<?= esc($pelanggan['email']) ?>)
                                        </option>
                                    <?php endforeach; ?>
                                <?php endif; ?>
                            </select>
                            <div class="invalid-feedback">
                                <?= session('errors.pelanggan_id') ?>
                            </div>
                        </div>
                    </div>
                    
                    <div class="col-md-6">
                        <div class="form-group">
                            <label for="tanggal_pemesanan" class="form-label">
                                <i class="bi bi-calendar me-1"></i>Tanggal Pemesanan
                            </label>
                            <input type="date"
                                class="form-control <?= session('errors.tanggal_pemesanan') ? 'is-invalid' : '' ?>"
                                id="tanggal_pemesanan"
                                name="tanggal_pemesanan"
                                value="<?= old('tanggal_pemesanan', $pemesanan['tanggal_pemesanan']) ?>"
                                required>
                            <div class="invalid-feedback">
                                <?= session('errors.tanggal_pemesanan') ?>
                            </div>
                        </div>
                    </div>

                    <div class="col-md-6">
                        <div class="form-group">
                            <label for="total_harga" class="form-label">
                                <i class="bi bi-currency-dollar me-1"></i>Total Harga (Rp)
                            </label>
                            <input type="text"
                                class="form-control <?= session('errors.total_harga') ? 'is-invalid' : '' ?>"
                                id="total_harga"
                                name="total_harga"
                                value="<?= old('total_harga', number_format($pemesanan['total_harga'], 0, ',', '.')) ?>"
                                required>
                            <div class="invalid-feedback">
                                <?= session('errors.total_harga') ?>
                            </div>
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
                                <option value="pending" <?= old('status_pemesanan', $pemesanan['status_pemesanan']) === 'pending' ? 'selected' : '' ?>>Pending</option>
                                <option value="proses" <?= old('status_pemesanan', $pemesanan['status_pemesanan']) === 'proses' ? 'selected' : '' ?>>Proses</option>
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
                            <label for="catatan" class="form-label">
                                <i class="bi bi-card-text me-1"></i>Catatan (Opsional)
                            </label>
                            <textarea 
                                class="form-control <?= session('errors.catatan') ? 'is-invalid' : '' ?>"
                                id="catatan"
                                name="catatan"
                                rows="4"><?= old('catatan', $pemesanan['catatan']) ?></textarea>
                            <div class="invalid-feedback">
                                <?= session('errors.catatan') ?>
                            </div>
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
        
        // Format input total harga dengan separator ribuan
        const hargaInput = document.getElementById('total_harga');
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
            const hargaInput = document.getElementById('total_harga');
            hargaInput.value = hargaInput.value.replace(/[^0-9]/g, '');
        });
    });
</script>
<?= $this->endSection() ?>