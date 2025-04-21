<!-- filepath: c:\laragon\www\ecommerce-hexadigital\app\Views\pages\godmode\produk\index.php -->
<?= $this->extend('layouts/admin') ?>

<?= $this->section('title') ?>Kelola Produk<?= $this->endSection() ?>

<?= $this->section('content') ?>
<div class="container-fluid px-4">
    <h1 class="mt-4">Kelola Produk</h1>
    <ol class="breadcrumb mb-4">
        <li class="breadcrumb-item"><a href="<?= site_url('godmode/dashboard') ?>">Dashboard</a></li>
        <li class="breadcrumb-item active">Produk</li>
    </ol>

    <!-- Alert Messages -->
    <?php if (session()->has('success')): ?>
        <div class="alert alert-success alert-dismissible fade show" role="alert">
            <i class="fas fa-check-circle me-2"></i> <?= session('success') ?>
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>
    <?php endif; ?>
    
    <?php if (session()->has('error')): ?>
        <div class="alert alert-danger alert-dismissible fade show" role="alert">
            <i class="fas fa-exclamation-circle me-2"></i> <?= session('error') ?>
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>
    <?php endif; ?>

    <div class="card mb-4">
        <div class="card-header d-flex justify-content-between align-items-center">
            <div>
                <i class="fas fa-box-open me-1"></i>
                Daftar Produk
            </div>
            <div>
                <a href="<?= site_url('godmode/produk/create') ?>" class="btn btn-primary btn-sm">
                    <i class="fas fa-plus me-1"></i> Tambah Produk
                </a>
            </div>
        </div>
        <div class="card-body">
            <div class="table-responsive">
                <table id="dataProduk" class="table table-striped table-bordered" width="100%">
                    <thead class="table-dark">
                        <tr>
                            <th width="70">ID</th>
                            <th width="100">Gambar</th>
                            <th>Nama Produk</th>
                            <th width="120">Harga</th>
                            <th width="80">Stok</th>
                            <th width="140">Kategori</th>
                            <th width="160">Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach ($produks as $produk): ?>
                            <tr>
                                <td><?= esc($produk['id']) ?></td>
                                <td class="text-center">
                                    <?php if (!empty($produk['gambar']) && file_exists(ROOTPATH . 'public/uploads/produk/' . $produk['gambar'])): ?>
                                        <img src="<?= base_url('uploads/produk/' . $produk['gambar']) ?>" alt="<?= esc($produk['nama_produk']) ?>" 
                                            class="img-thumbnail" style="max-height: 50px;">
                                    <?php else: ?>
                                        <img src="<?= base_url('assets/img/no-image.png') ?>" alt="No Image" 
                                            class="img-thumbnail" style="max-height: 50px;">
                                    <?php endif; ?>
                                </td>
                                <td><?= esc($produk['nama_produk']) ?></td>
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
                                <td class="text-center">
                                    <div class="btn-group" role="group">
                                        <a href="<?= site_url('godmode/produk/detail/' . $produk['id']) ?>" 
                                           class="btn btn-info btn-sm" title="Detail">
                                            <i class="fas fa-eye"></i>
                                        </a>
                                        <a href="<?= site_url('godmode/produk/edit/' . $produk['id']) ?>" 
                                           class="btn btn-warning btn-sm" title="Edit">
                                            <i class="fas fa-edit"></i>
                                        </a>
                                        <button type="button" class="btn btn-danger btn-sm delete-btn" 
                                                data-id="<?= $produk['id'] ?>"
                                                data-name="<?= esc($produk['nama_produk']) ?>"
                                                title="Hapus">
                                            <i class="fas fa-trash"></i>
                                        </button>
                                    </div>
                                </td>
                            </tr>
                        <?php endforeach; ?>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>

<!-- Delete Confirmation Modal -->
<div class="modal fade" id="deleteModal" tabindex="-1" aria-labelledby="deleteModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="deleteModalLabel">Konfirmasi Hapus</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <p>Apakah Anda yakin ingin menghapus produk <strong id="delete-produk-name"></strong>?</p>
                <p class="text-danger"><small>Tindakan ini tidak dapat dibatalkan.</small></p>
            </div>
            <div class="modal-footer">
                <form id="deleteForm" action="" method="post">
                    <?= csrf_field() ?>
                    <input type="hidden" name="_method" value="DELETE">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Batal</button>
                    <button type="submit" class="btn btn-danger">Hapus</button>
                </form>
            </div>
        </div>
    </div>
</div>

<script>
    document.addEventListener('DOMContentLoaded', function() {
        // Initialize DataTable
        $('#dataProduk').DataTable({
            responsive: true,
            language: {
                url: '//cdn.datatables.net/plug-ins/1.10.25/i18n/Indonesian.json'
            }
        });
        
        // Set up delete confirmation
        const deleteButtons = document.querySelectorAll('.delete-btn');
        const deleteForm = document.getElementById('deleteForm');
        const deleteProdukName = document.getElementById('delete-produk-name');
        
        deleteButtons.forEach(button => {
            button.addEventListener('click', function() {
                const id = this.getAttribute('data-id');
                const name = this.getAttribute('data-name');
                
                deleteProdukName.textContent = name;
                deleteForm.action = `<?= site_url('godmode/produk/') ?>${id}`;
                
                // Show modal
                const deleteModal = new bootstrap.Modal(document.getElementById('deleteModal'));
                deleteModal.show();
            });
        });
    });
</script>
<?= $this->endSection() ?>