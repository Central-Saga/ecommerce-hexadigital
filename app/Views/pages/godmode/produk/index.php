<!-- filepath: c:\laragon\www\ecommerce-hexadigital\app\Views\pages\godmode\produk\index.php -->
<?php /** @var \CodeIgniter\View\View $this */ ?>
<?= $this->extend('layouts/admin'); ?>

<?= $this->section('title') ?>Kelola Produk<?= $this->endSection() ?>

<?= $this->section('content'); ?>
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

<div class="product-management">
    <div class="card">
        <div class="card-header d-flex justify-content-between align-items-center">
            <div class="d-flex align-items-center">
                <i class="bi bi-box-seam me-2 text-primary fs-4"></i>
                <h5 class="mb-0">Daftar Produk</h5>
            </div>
            <a href="<?= site_url('godmode/produk/create') ?>" class="btn btn-primary">
                <i class="bi bi-plus-circle me-1"></i> Tambah Produk
            </a>
        </div>
        <div class="card-body">
            <?php if (empty($produks)) : ?>
                <div class="text-center py-5">
                    <i class="bi bi-inbox fs-1 text-muted"></i>
                    <p class="mt-3 text-muted">Belum ada data produk</p>
                </div>
            <?php else : ?>
                <div class="table-responsive">
                    <table class="table table-hover align-middle">
                        <thead>
                            <tr>
                                <th class="text-center" style="width: 60px">ID</th>
                                <th style="width: 100px">Gambar</th>
                                <th>Nama Produk</th>
                                <th style="width: 120px">Harga</th>
                                <th class="text-center" style="width: 80px">Stok</th>
                                <th style="width: 140px">Kategori</th>
                                <th class="text-center" style="width: 160px">Aksi</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php foreach ($produks as $produk): ?>
                                <tr>
                                    <td class="text-center"><?= esc($produk['id']) ?></td>
                                    <td class="text-center">
                                        <?php if (!empty($produk['gambar']) && file_exists(ROOTPATH . 'public/uploads/produk/' . $produk['gambar'])): ?>
                                            <img src="<?= base_url('uploads/produk/' . $produk['gambar']) ?>"
                                                alt="<?= esc($produk['nama']) ?>"
                                                class="product-image"
                                                onerror="this.src='<?= base_url('assets/img/no-image.png') ?>'">
                                        <?php else: ?>
                                            <img src="<?= base_url('assets/img/no-image.png') ?>"
                                                alt="No Image"
                                                class="product-image">
                                        <?php endif; ?>
                                    </td>
                                    <td>
                                        <div class="fw-semibold"><?= esc($produk['nama']) ?></div>
                                    </td>
                                    <td>Rp <?= number_format($produk['harga'], 0, ',', '.') ?></td>
                                    <td class="text-center">
                                        <span class="badge bg-<?= $produk['stok'] > 0 ? 'success' : 'danger' ?>">
                                            <?= $produk['stok'] ?>
                                        </span>
                                    </td>
                                    <td>
                                        <?php if (isset($produk['kategori_nama']) && !empty($produk['kategori_nama'])): ?>
                                            <span class="badge bg-info"><?= esc($produk['kategori_nama']) ?></span>
                                        <?php else: ?>
                                            <span class="badge bg-secondary">Tidak ada kategori</span>
                                        <?php endif; ?>
                                    </td>
                                    <td>
                                        <div class="d-flex justify-content-center gap-1">
                                            <a href="<?= site_url('godmode/produk/detail/' . $produk['id']) ?>"
                                                class="btn btn-sm btn-info" data-bs-toggle="tooltip" title="Detail">
                                                <i class="bi bi-eye"></i>
                                            </a>
                                            <a href="<?= site_url('godmode/produk/edit/' . $produk['id']) ?>"
                                                class="btn btn-sm btn-warning" data-bs-toggle="tooltip" title="Edit">
                                                <i class="bi bi-pencil"></i>
                                            </a>
                                            <button type="button" class="btn btn-sm btn-danger"
                                                onclick="deleteProduk(<?= $produk['id'] ?>, '<?= esc($produk['nama']) ?>')"
                                                data-bs-toggle="tooltip" title="Hapus">
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
    function deleteProduk(id, name) {
        Swal.fire({
            title: 'Apakah Anda yakin?',
            text: `Produk "${name}" akan dihapus!`,
            icon: 'warning',
            showCancelButton: true,
            confirmButtonColor: '#d33',
            cancelButtonColor: '#6c757d',
            confirmButtonText: 'Ya, hapus!',
            cancelButtonText: 'Batal'
        }).then((result) => {
            if (result.isConfirmed) {
                fetch(`/godmode/produk/produk/${id}`, {
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
                            text: 'Terjadi kesalahan saat menghapus produk',
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
<?= $this->endSection(); ?>