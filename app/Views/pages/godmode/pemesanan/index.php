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

<div class="pemesanan-management">
    <div class="card">
        <div class="card-header d-flex justify-content-between align-items-center">
            <div class="d-flex align-items-center">
                <i class="bi bi-cart-fill me-2 text-primary fs-4"></i>
                <h5 class="mb-0">Daftar Pemesanan</h5>
            </div>
            <!-- <a href="/godmode/pemesanan/create" class="btn btn-primary">
                <i class="bi bi-plus-circle me-1"></i> Tambah Pemesanan
            </a> -->
        </div>
        <div class="card-body">
            <?php if (empty($pemesanans)) : ?>
                <div class="text-center py-5">
                    <i class="bi bi-inbox fs-1 text-muted"></i>
                    <p class="mt-3 text-muted">Belum ada data pemesanan</p>
                </div>
            <?php else : ?>
                <div class="table-responsive">
                    <table class="table table-hover align-middle">
                        <thead>
                            <tr>
                                <th class="text-center" style="width: 60px">No</th>
                                <th>Pelanggan</th>
                                <th>Tanggal Pemesanan</th>
                                <th class="text-center">Total Harga</th>
                                <th class="text-center">Status</th>
                                <th class="text-center" style="width: 120px">Aksi</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php foreach ($pemesanans as $index => $pemesanan): ?>
                                <tr>
                                    <td class="text-center"><?= $index + 1 ?></td>
                                    <td>
                                        <div class="d-flex align-items-center">
                                            <div class="customer-icon me-2">
                                                <i class="bi bi-person-circle fs-4 text-primary"></i>
                                            </div>
                                            <div>
                                                <div class="fw-semibold"><?= $pemesanan['pelanggan_nama'] ?></div>
                                                <small class="text-muted"><?= $pemesanan['email'] ?? '-' ?></small>
                                            </div>
                                        </div>
                                    </td>
                                    <td><?= date('d/m/Y', strtotime($pemesanan['tanggal_pemesanan'])) ?></td>
                                    <td class="text-center">Rp <?= number_format($pemesanan['total_harga'], 0, ',', '.') ?></td>
                                    <td class="text-center">
                                        <?php $badge_class = '';
                                        switch ($pemesanan['status_pemesanan']) {
                                            case 'menunggu':
                                                $badge_class = 'bg-warning';
                                                $status_text = 'Menunggu';
                                                break;
                                            case 'diproses':
                                                $badge_class = 'bg-info';
                                                $status_text = 'Diproses';
                                                break;
                                            case 'selesai':
                                                $badge_class = 'bg-success';
                                                $status_text = 'Selesai';
                                                break;
                                            case 'dibatalkan':
                                                $badge_class = 'bg-danger';
                                                $status_text = 'Dibatalkan';
                                                break;
                                            default:
                                                $badge_class = 'bg-secondary';
                                                $status_text = ucfirst($pemesanan['status_pemesanan']);
                                        }
                                        ?>
                                        <span class="badge <?= $badge_class ?>"><?= $status_text ?></span>
                                    </td>
                                    <td>
                                        <div class="d-flex justify-content-center gap-1">
                                            <a href="/godmode/pemesanan/detail/<?= $pemesanan['id'] ?>" class="btn btn-sm btn-info" data-bs-toggle="tooltip" title="Detail">
                                                <i class="bi bi-eye"></i>
                                            </a>
                                            <a href="/godmode/pemesanan/edit/<?= $pemesanan['id'] ?>" class="btn btn-sm btn-warning" data-bs-toggle="tooltip" title="Edit">
                                                <i class="bi bi-pencil"></i>
                                            </a>
                                            <button type="button" class="btn btn-sm btn-danger" onclick="deletePemesanan(<?= $pemesanan['id'] ?>)" data-bs-toggle="tooltip" title="Hapus">
                                                <i class="bi bi-trash"></i>
                                            </button>
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
    function deletePemesanan(id) {
        Swal.fire({
            title: 'Apakah Anda yakin?',
            text: "Data pemesanan yang dihapus tidak dapat dikembalikan!",
            icon: 'warning',
            showCancelButton: true,
            confirmButtonColor: '#d33',
            cancelButtonColor: '#6c757d',
            confirmButtonText: 'Ya, hapus!',
            cancelButtonText: 'Batal'
        }).then((result) => {
            if (result.isConfirmed) {
                fetch(`/godmode/pemesanan/pemesanan/${id}`, {
                        method: 'DELETE',
                        headers: {
                            'X-Requested-With': 'XMLHttpRequest',
                            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
                        }
                    })
                    .then(response => response.json())
                    .then(data => {
                        if (data.success) {
                            Swal.fire({
                                title: 'Berhasil!',
                                text: data.message,
                                icon: 'success'
                            }).then(() => {
                                window.location.reload();
                            });
                        } else {
                            Swal.fire({
                                title: 'Gagal!',
                                text: data.message,
                                icon: 'error'
                            });
                        }
                    })
                    .catch(error => {
                        Swal.fire({
                            title: 'Error!',
                            text: 'Terjadi kesalahan saat menghapus pemesanan',
                            icon: 'error'
                        });
                    });
            }
        });
    }

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