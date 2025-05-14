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

<div class="customer-management">
    <div class="card">
        <div class="card-header d-flex justify-content-between align-items-center">
            <div class="d-flex align-items-center">
                <i class="bi bi-people-fill me-2 text-primary fs-4"></i>
                <h5 class="mb-0">Daftar Customer</h5>
            </div>
            <a href="/godmode/pelanggan/create" class="btn btn-primary">
                <i class="bi bi-plus-circle me-1"></i> Tambah Customer
            </a>
        </div>
        <div class="card-body">
            <?php if (empty($customers)) : ?>
                <div class="text-center py-5">
                    <i class="bi bi-inbox fs-1 text-muted"></i>
                    <p class="mt-3 text-muted">Belum ada data customer</p>
                </div>
            <?php else : ?>
                <div class="table-responsive">
                    <table class="table table-hover align-middle">
                        <thead>
                            <tr>
                                <th class="text-center" style="width: 60px">No</th>
                                <th>Nama</th>
                                <th>Email</th>
                                <th>No. Telepon</th>
                                <th class="text-center">Status</th>
                                <th class="text-center" style="width: 120px">Aksi</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php foreach ($customers as $index => $customer): ?>
                                <tr>
                                    <td class="text-center"><?= $index + 1 ?></td>
                                    <td>
                                        <div class="d-flex align-items-center">
                                            <div class="user-avatar me-2">
                                                <i class="bi bi-person-circle fs-4 text-muted"></i>
                                            </div>
                                            <div>
                                                <div class="fw-semibold"><?= $customer['name'] ?></div>
                                            </div>
                                        </div>
                                    </td>
                                    <td><?= $customer['email'] ?></td>
                                    <td><?= $customer['phone'] ?></td>
                                    <td class="text-center">
                                        <span class="badge bg-<?= $customer['status'] === 'active' ? 'success' : 'danger' ?> rounded-pill">
                                            <i class="bi bi-<?= $customer['status'] === 'active' ? 'check-circle' : 'x-circle' ?> me-1"></i>
                                            <?= $customer['status'] === 'active' ? 'Aktif' : 'Nonaktif' ?>
                                        </span>
                                    </td>
                                    <td>
                                        <div class="d-flex justify-content-center gap-1">
                                            <a href="/godmode/pelanggan/edit/<?= $customer['id'] ?>" class="btn btn-sm btn-warning" data-bs-toggle="tooltip" title="Edit">
                                                <i class="bi bi-pencil"></i>
                                            </a>
                                            <button type="button" class="btn btn-sm btn-danger" onclick="deleteCustomer(<?= $customer['id'] ?>)" data-bs-toggle="tooltip" title="Hapus">
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
    function deleteCustomer(id) {
        Swal.fire({
            title: 'Apakah Anda yakin?',
            text: "Data customer yang dihapus tidak dapat dikembalikan!",
            icon: 'warning',
            showCancelButton: true,
            confirmButtonColor: '#d33',
            cancelButtonColor: '#6c757d',
            confirmButtonText: 'Ya, hapus!',
            cancelButtonText: 'Batal'
        }).then((result) => {
            if (result.isConfirmed) {
                fetch(`/godmode/pelanggan/pelanggan/${id}`, {
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
                            text: 'Terjadi kesalahan saat menghapus customer',
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