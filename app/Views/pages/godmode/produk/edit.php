<!-- filepath: c:\laragon\www\ecommerce-hexadigital\app\Views\pages\godmode\produk\edit.php -->
<?php /** @var \CodeIgniter\View\View $this */ ?>
<?= $this->extend('layouts/admin'); ?>

<?= $this->section('title') ?>Edit Produk<?= $this->endSection() ?>

<?= $this->section('content'); ?>
<div class="container-fluid px-4">
    <h1 class="mt-4">Edit Produk</h1>
    <ol class="breadcrumb mb-4">
        <li class="breadcrumb-item"><a href="<?= site_url('godmode/dashboard') ?>">Dashboard</a></li>
        <li class="breadcrumb-item"><a href="<?= site_url('godmode/produk') ?>">Produk</a></li>
        <li class="breadcrumb-item active">Edit Produk</li>
    </ol>

    <div class="card mb-4">
        <div class="card-header">
            <i class="fas fa-edit me-1"></i>
            Edit Produk: <?= esc($produk['nama_produk']) ?>
        </div>
        <div class="card-body">
            <?php if (session()->has('errors')): ?>
                <div class="alert alert-danger alert-dismissible fade show" role="alert">
                    <strong>Error!</strong>
                    <ul>
                        <?php foreach (session('errors') as $error): ?>
                            <li><?= esc($error) ?></li>
                        <?php endforeach; ?>
                    </ul>
                    <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                </div>
            <?php endif; ?>

            <?php if (session()->has('error')): ?>
                <div class="alert alert-danger alert-dismissible fade show" role="alert">
                    <?= session('error') ?>
                    <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                </div>
            <?php endif; ?>

            <form action="<?= site_url('godmode/produk/update/' . $produk['id']) ?>" method="post" enctype="multipart/form-data">
                <?= csrf_field() ?>
                <input type="hidden" name="_method" value="PUT">

                <div class="row">
                    <div class="col-md-8">
                        <div class="mb-3">
                            <label for="nama_produk" class="form-label">Nama Produk <span class="text-danger">*</span></label>
                            <input type="text" class="form-control" id="nama_produk" name="nama_produk" 
                                value="<?= old('nama_produk', $produk['nama_produk']) ?>" required>
                        </div>

                        <div class="mb-3">
                            <label for="kategori_id" class="form-label">Kategori</label>
                            <select class="form-select" id="kategori_id" name="kategori_id">
                                <option value="">Pilih Kategori</option>
                                <?php foreach ($kategoris as $kategori): ?>
                                    <option value="<?= $kategori['id'] ?>" <?= (old('kategori_id', $produk['kategori_id']) == $kategori['id']) ? 'selected' : '' ?>>
                                        <?= esc($kategori['nama_kategori']) ?>
                                    </option>
                                <?php endforeach; ?>
                            </select>
                        </div>

                        <div class="row">
                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label for="harga" class="form-label">Harga <span class="text-danger">*</span></label>
                                    <div class="input-group">
                                        <span class="input-group-text">Rp</span>
                                        <input type="number" class="form-control" id="harga" name="harga" 
                                            value="<?= old('harga', $produk['harga']) ?>" step="0.01" min="0" required>
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label for="stok" class="form-label">Stok <span class="text-danger">*</span></label>
                                    <input type="number" class="form-control" id="stok" name="stok" 
                                        value="<?= old('stok', $produk['stok']) ?>" min="0" required>
                                </div>
                            </div>
                        </div>

                        <div class="mb-3">
                            <label for="deskripsi" class="form-label">Deskripsi Produk</label>
                            <textarea class="form-control" id="deskripsi" name="deskripsi" rows="4"><?= old('deskripsi', $produk['deskripsi']) ?></textarea>
                        </div>
                    </div>

                    <div class="col-md-4">
                        <div class="card">
                            <div class="card-header bg-light">
                                <h5 class="card-title mb-0">Gambar Produk</h5>
                            </div>
                            <div class="card-body">
                                <div class="mb-3 text-center">
                                    <?php if (!empty($produk['gambar']) && file_exists(ROOTPATH . 'public/uploads/produk/' . $produk['gambar'])): ?>
                                        <img id="preview-image" src="<?= base_url('uploads/produk/' . $produk['gambar']) ?>" 
                                            alt="<?= esc($produk['nama_produk']) ?>" class="img-thumbnail mb-2" style="max-height: 200px;">
                                    <?php else: ?>
                                        <img id="preview-image" src="<?= base_url('assets/img/no-image.png') ?>" 
                                            alt="No Image" class="img-thumbnail mb-2" style="max-height: 200px;">
                                    <?php endif; ?>
                                    
                                    <div class="mt-2">
                                        <input type="file" class="form-control" id="gambar" name="gambar" accept="image/*">
                                    </div>
                                    <div class="form-text">
                                        Format: JPG, PNG. Maks: 2MB<br>
                                        Biarkan kosong jika tidak ingin mengubah gambar
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="card mt-3">
                            <div class="card-header bg-light">
                                <h5 class="card-title mb-0">Informasi Tambahan</h5>
                            </div>
                            <div class="card-body">
                                <p class="mb-1"><strong>Dibuat pada:</strong></p>
                                <p class="text-muted"><?= date('d M Y H:i', strtotime($produk['created_at'])) ?></p>
                                
                                <p class="mb-1"><strong>Terakhir diupdate:</strong></p>
                                <p class="text-muted"><?= date('d M Y H:i', strtotime($produk['updated_at'])) ?></p>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="d-flex justify-content-end mt-3">
                    <a href="<?= site_url('godmode/produk') ?>" class="btn btn-secondary me-2">Kembali</a>
                    <button type="submit" class="btn btn-primary">Update Produk</button>
                </div>
            </form>
        </div>
    </div>
</div>

<script>
    // Preview image before upload
    document.getElementById('gambar').addEventListener('change', function() {
        const preview = document.getElementById('preview-image');
        const file = this.files[0];
        
        if (file) {
            const reader = new FileReader();
            reader.onload = function(e) {
                preview.src = e.target.result;
            }
            reader.readAsDataURL(file);
        } else {
            <?php if (!empty($produk['gambar']) && file_exists(ROOTPATH . 'public/uploads/produk/' . $produk['gambar'])): ?>
                preview.src = '<?= base_url('uploads/produk/' . $produk['gambar']) ?>';
            <?php else: ?>
                preview.src = '<?= base_url('assets/img/no-image.png') ?>';
            <?php endif; ?>
        }
    });

    // Add rich text editor for description
    document.addEventListener('DOMContentLoaded', function() {
        if (typeof ClassicEditor !== 'undefined') {
            ClassicEditor
                .create(document.querySelector('#deskripsi'))
                .catch(error => {
                    console.error(error);
                });
        }
    });
</script>
<?= $this->endSection(); ?>