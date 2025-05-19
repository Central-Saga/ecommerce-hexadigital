<?= $this->extend('layouts/wrapper') ?>

<?= $this->section('content') ?>
<div class="content-container py-5">
    <nav aria-label="breadcrumb" class="mb-4">
        <ol class="breadcrumb">
            <li class="breadcrumb-item"><a href="<?= base_url() ?>">Beranda</a></li>
            <li class="breadcrumb-item"><a href="<?= base_url('products') ?>">Produk</a></li>
            <li class="breadcrumb-item active" aria-current="page"><?= esc($product['nama_produk']) ?></li>
        </ol>
    </nav>

    <section class="product-detail">
        <div class="product-container">
            <div class="product-image">
                <?php if (!empty($product['gambar']) && file_exists(ROOTPATH . 'public/uploads/produk/' . $product['gambar'])): ?>
                    <img src="<?= base_url('uploads/produk/' . $product['gambar']) ?>" 
                         alt="<?= esc($product['nama_produk']) ?>"
                         class="img-fluid"
                         onerror="this.src='<?= base_url('assets/images/product-placeholder.jpg') ?>'">
                <?php else: ?>
                    <img src="<?= base_url('assets/images/product-placeholder.jpg') ?>" 
                         alt="<?= esc($product['nama_produk']) ?>"
                         class="img-fluid">
                <?php endif; ?>
            </div>
            
            <div class="product-info">
                <div class="mb-2">
                    <?php if (isset($product['kategori_nama'])): ?>
                        <span class="badge bg-primary"><?= esc($product['kategori_nama']) ?></span>
                    <?php endif; ?>
                </div>

                <h1 class="product-title"><?= esc($product['nama_produk']) ?></h1>
                <div class="product-price">Rp <?= number_format($product['harga'], 0, ',', '.') ?></div>
                
                <div class="product-description">
                    <?= nl2br(esc($product['deskripsi'])) ?>
                </div>
                
                <div class="product-meta">
                    <div class="meta-item">
                        <span class="label">Kategori</span>
                        <span class="value"><?= esc($product['kategori_nama'] ?? 'Tidak ada kategori') ?></span>
                    </div>
                    <div class="meta-item">
                        <span class="label">Stok</span>
                        <span class="value">
                            <?php if ($product['stok'] > 0): ?>
                                <span class="text-success"><?= $product['stok'] ?> unit</span>
                            <?php else: ?>
                                <span class="text-danger">Stok habis</span>
                            <?php endif; ?>
                        </span>
                    </div>
                </div>
                
                <div class="product-actions">
                    <?php if ($product['stok'] > 0): ?>
                        <button type="button" class="btn btn-primary" onclick="addToCart(<?= $product['id'] ?>)">
                            <i class="bi bi-cart-plus me-2"></i>Tambah ke Keranjang
                        </button>
                    <?php else: ?>
                        <button type="button" class="btn btn-secondary" disabled>
                            <i class="bi bi-x-circle me-2"></i>Stok Habis
                        </button>
                    <?php endif; ?>
                </div>
            </div>
        </div>
    </section>
</div>

<script>
function addToCart(productId) {
    // Implementasi logika menambah ke keranjang
    alert('Produk ditambahkan ke keranjang!');
}
</script>

<?= $this->endSection() ?>
