<!-- filepath: c:\laragon\www\ecommerce-hexadigital\app\Views\pages\godmode\produk\create.php -->
<?php /** @var \CodeIgniter\View\View $this */ ?>
<?= $this->extend('layouts/admin') ?>

<?= $this->section('title') ?>Tambah Produk Baru<?= $this->endSection() ?>

<?= $this->section('content') ?>
<div class="container-fluid px-4 py-3">
    <div class="row">
        <div class="col-12">
            <div class="card shadow border-0">
                <div class="card-header bg-gradient-primary text-white">
                    <h5 class="card-title mb-0">
                        <i class="bi bi-plus-circle-fill me-2"></i>Tambah Produk Baru
                    </h5>
                </div>
                <div class="card-body">
                    <?php if (session()->has('errors')): ?>
                        <div class="alert alert-danger">
                            <ul class="mb-0">
                                <?php foreach (session('errors') as $error): ?>
                                    <li><?= esc($error) ?></li>
                                <?php endforeach ?>
                            </ul>
                        </div>
                    <?php endif; ?>
                    
                    <form action="<?= site_url('godmode/produk/store') ?>" method="post" enctype="multipart/form-data">
                        <?= csrf_field() ?>
                        
                        <div class="row mb-3">
                            <div class="col-md-8">
                                <div class="form-floating mb-3">
                                    <input type="text" class="form-control" id="nama_produk" name="nama_produk" 
                                           placeholder="Nama Produk" value="<?= old('nama_produk') ?>">
                                    <label for="nama_produk">Nama Produk</label>
                                </div>
                                
                                <div class="form-floating mb-3">
                                    <select class="form-select" id="kategori_id" name="kategori_id">
                                        <option value="" selected disabled>Pilih Kategori</option>
                                        <?php if (isset($kategoriList)): ?>
                                            <?php foreach ($kategoriList as $kategori): ?>
                                                <option value="<?= $kategori['id'] ?>" <?= old('kategori_id') == $kategori['id'] ? 'selected' : '' ?>>
                                                    <?= esc($kategori['nama']) ?>
                                                </option>
                                            <?php endforeach ?>
                                        <?php endif ?>
                                    </select>
                                    <label for="kategori_id">Kategori Produk</label>
                                </div>
                                
                                <div class="row">
                                    <div class="col-md-6">
                                        <div class="form-floating mb-3">
                                            <input type="number" class="form-control" id="harga" name="harga" 
                                                   placeholder="Harga Produk" value="<?= old('harga') ?>" min="0" step="0.01">
                                            <label for="harga">Harga (Rp)</label>
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        <div class="form-floating mb-3">
                                            <input type="number" class="form-control" id="stok" name="stok" 
                                                   placeholder="Stok" value="<?= old('stok', 0) ?>" min="0">
                                            <label for="stok">Stok</label>
                                        </div>
                                    </div>
                                </div>
                                
                                <div class="mb-3">
                                    <label for="deskripsi" class="form-label">Deskripsi Produk</label>
                                    <textarea class="form-control" id="deskripsi" name="deskripsi" 
                                              rows="5" placeholder="Deskripsi lengkap produk..."><?= old('deskripsi') ?></textarea>
                                </div>
                            </div>
                            
                            <div class="col-md-4">
                                <div class="card border">
                                    <div class="card-header bg-light">
                                        <h6 class="mb-0">Gambar Produk</h6>
                                    </div>
                                    <div class="card-body text-center">
                                        <div class="mb-3">
                                            <img id="preview" src="<?= base_url('assets/img/placeholder-image.png') ?>" 
                                                 class="img-fluid img-thumbnail mb-2" style="max-height: 200px;">
                                            
                                            <div class="input-group">
                                                <input type="file" class="form-control" id="gambar" name="gambar" accept="image/*">
                                                <label class="input-group-text" for="gambar">Upload</label>
                                            </div>
                                            <small class="text-muted">Format: JPG, PNG, GIF. Maks 2MB</small>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        
                        <hr>
                        
                        <div class="d-flex justify-content-between">
                            <a href="<?= site_url('godmode/produk') ?>" class="btn btn-secondary">
                                <i class="bi bi-arrow-left me-1"></i> Kembali
                            </a>
                            <button type="submit" class="btn btn-primary">
                                <i class="bi bi-save me-1"></i> Simpan Produk
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>

<script>
document.addEventListener('DOMContentLoaded', function() {
    // Preview gambar yang diupload
    const gambarInput = document.getElementById('gambar');
    const previewImg = document.getElementById('preview');
    
    gambarInput.addEventListener('change', function() {
        const file = this.files[0];
        if (file) {
            const reader = new FileReader();
            reader.onload = function(e) {
                previewImg.src = e.target.result;
            }
            reader.readAsDataURL(file);
        } else {
            previewImg.src = '<?= base_url('assets/img/placeholder-image.png') ?>';
        }
    });
    
    // Format input harga dengan separator ribuan
    const hargaInput = document.getElementById('harga');
    hargaInput.addEventListener('input', function(e) {
        const value = this.value.replace(/,/g, '');
        if (!isNaN(value) && value !== '') {
            // Format hanya jika angka valid
            this.value = parseFloat(value).toLocaleString('id-ID');
        }
    });
});
</script>
<?= $this->endSection() ?>