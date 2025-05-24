<?= $this->extend('layouts/admin') ?>
<?= $this->section('content') ?>
<!-- Toast Container -->
<div class="toast-container position-fixed top-0 end-0 p-3">
    <?php if (session()->getFlashdata('success')) : ?>
        <div class="toast align-items-center text-white bg-success border-0" role="alert" aria-live="assertive" aria-atomic="true">
            <div class="d-flex">
                <div class="toast-body">
                    <i class="bi bi-check-circle me-2"></i>
                    <?= session()->getFlashdata('success') ?>
                </div>
                <button type="button" class="btn-close btn-close-white me-2 m-auto" data-bs-dismiss="toast" aria-label="Close"></button>
            </div>
        </div>
    <?php endif; ?>
    <?php if (session()->getFlashdata('error')) : ?>
        <div class="toast align-items-center text-white bg-danger border-0" role="alert" aria-live="assertive" aria-atomic="true">
            <div class="d-flex">
                <div class="toast-body">
                    <i class="bi bi-x-circle me-2"></i>
                    <?= session()->getFlashdata('error') ?>
                </div>
                <button type="button" class="btn-close btn-close-white me-2 m-auto" data-bs-dismiss="toast" aria-label="Close"></button>
            </div>
        </div>
    <?php endif; ?>
</div>

<div class="pembayaran-management">
    <div class="card">
        <div class="card-header d-flex justify-content-between align-items-center">
            <div class="d-flex align-items-center">
                <i class="bi bi-credit-card-2-front-fill me-2 text-primary fs-4"></i>
                <h5 class="mb-0">Daftar Pembayaran</h5>
            </div>
            <a href="<?= base_url('godmode/pembayaran/create') ?>" class="btn btn-primary">
                <i class="bi bi-plus-circle me-1"></i> Tambah Pembayaran
            </a>
        </div>
        <div class="card-body">
            <?php if (empty($pembayarans)) : ?>
                <div class="text-center py-5">
                    <i class="bi bi-inbox fs-1 text-muted"></i>
                    <p class="mt-3 text-muted">Belum ada data pembayaran</p>
                </div>
            <?php else : ?>
                <div class="table-responsive">
                    <table class="table table-hover align-middle">
                        <thead>
                            <tr>
                                <th class="text-center" style="width: 60px">ID</th>
                                <th>Pesanan</th>
                                <th>Nama Pengirim</th>
                                <th>Metode</th>
                                <th class="text-center">Total</th>
                                <th class="text-center">Status</th>
                                <th class="text-center" style="width: 120px">Aksi</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php foreach ($pembayarans as $pembayaran): ?>
                                <tr>
                                    <td class="text-center"><?= esc($pembayaran['id']) ?></td>
                                    <td><?= esc($pembayaran['pesanan_id']) ?></td>
                                    <td><?= esc($pembayaran['nama_pengirim']) ?></td>
                                    <td><?= esc($pembayaran['metode_pembayaran']) ?></td>
                                    <td class="text-center">Rp<?= number_format($pembayaran['total_harga'], 0, ',', '.') ?></td>
                                    <td class="text-center">
                                        <?php $badge_class = '';
                                        switch ($pembayaran['status']) {
                                            case 'diterima':
                                                $badge_class = 'bg-success';
                                                $status_text = 'Diterima';
                                                break;
                                            case 'ditolak':
                                                $badge_class = 'bg-danger';
                                                $status_text = 'Ditolak';
                                                break;
                                            case 'menunggu':
                                                $badge_class = 'bg-warning';
                                                $status_text = 'Menunggu';
                                                break;
                                            default:
                                                $badge_class = 'bg-secondary';
                                                $status_text = ucfirst($pembayaran['status']);
                                        }
                                        ?>
                                        <span class="badge <?= $badge_class ?>"><?= $status_text ?></span>
                                    </td>
                                    <td>
                                        <div class="d-flex justify-content-center gap-1">
                                            <a href="<?= base_url('godmode/pembayaran/detail/' . $pembayaran['id']) ?>" class="btn btn-sm btn-info" data-bs-toggle="tooltip" title="Detail">
                                                <i class="bi bi-eye"></i>
                                            </a>
                                            <a href="<?= base_url('godmode/pembayaran/edit/' . $pembayaran['id']) ?>" class="btn btn-sm btn-warning" data-bs-toggle="tooltip" title="Edit">
                                                <i class="bi bi-pencil"></i>
                                            </a>
                                            <form action="<?= base_url('godmode/pembayaran/delete/' . $pembayaran['id']) ?>" method="post" style="display:inline-block" onsubmit="return confirm('Yakin hapus pembayaran ini?')">
                                                <input type="hidden" name="_method" value="DELETE">
                                                <button type="submit" class="btn btn-sm btn-danger" data-bs-toggle="tooltip" title="Hapus">
                                                    <i class="bi bi-trash"></i>
                                                </button>
                                            </form>
                                        </div>
                                    </td>
                                </tr>
                            <?php endforeach; ?>
                        </tbody>
                    </table>
                </div>
            <?php endif; ?>
        </div>
    </div>
</div>

<script>
    // Inisialisasi toast
    document.addEventListener('DOMContentLoaded', function() {
        const toastElList = document.querySelectorAll('.toast');
        const toastList = [...toastElList].map(toastEl => new bootstrap.Toast(toastEl, {
            autohide: true,
            delay: 3000
        }));
        toastList.forEach(toast => toast.show());

        // Inisialisasi tooltip
        const tooltipTriggerList = document.querySelectorAll('[data-bs-toggle="tooltip"]');
        const tooltipList = [...tooltipTriggerList].map(tooltipTriggerEl => new bootstrap.Tooltip(tooltipTriggerEl));
    });
</script>
<?= $this->endSection() ?>