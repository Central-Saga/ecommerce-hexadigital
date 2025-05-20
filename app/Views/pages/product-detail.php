<?= $this->extend('layouts/wrapper') ?>

<?= $this->section('content') ?>

<div class="product-detail">
    <div class="product-container">
        <div class="product-image">
            <?php if ($product['gambar']): ?>
                <img src="<?= base_url('uploads/produk/' . $product['gambar']) ?>" alt="<?= $product['nama_produk'] ?>">
            <?php else: ?>
                <img src="<?= base_url('assets/images/no-image.jpg') ?>" alt="No Image">
            <?php endif; ?>
        </div>

        <div class="product-info">
            <h1 class="product-title"><?= esc($product['nama_produk']) ?></h1>
            <div class="product-price">Rp <?= number_format($product['harga'], 0, ',', '.') ?></div>
            
            <div class="product-description">
                <?= esc($product['deskripsi']) ?>
            </div>

            <div class="product-meta">
                <div class="meta-item">
                    <span class="label">Kategori</span>
                    <span class="value"><?= esc($kategori['nama_kategori'] ?? '-') ?></span>
                </div>
                <div class="meta-item">
                    <span class="label">Stok</span>
                    <span class="value"><?= $product['stok'] ?> unit</span>
                </div>
            </div>

            <div class="product-actions">
                <?php if ($product['stok'] > 0): ?>
                    <button class="btn btn-primary add-to-cart" data-product-id="<?= $product['id'] ?>">
                        <i class="fas fa-shopping-cart"></i>
                        Tambah ke Keranjang
                    </button>
                <?php else: ?>
                    <button class="btn btn-secondary" disabled>
                        <i class="fas fa-times"></i>
                        Stok Habis
                    </button>
                <?php endif; ?>
            </div>
        </div>
    </div>
</div>

<?= $this->endSection() ?>
