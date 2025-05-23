<?= $this->extend('layouts/wrapper') ?>
<?= $this->section('content') ?>
<section class="kategori-detail-section pb-5">
    <div class="content-container">
        <div class="section-header text-center">
            <h6 class="section-subtitle mt-5">Kategori</h6>
            <h2 class="section-title"><?= esc($category['nama_kategori']) ?></h2>
            <p class="section-description"><?= esc($category['deskripsi_kategori'] ?? '') ?></p>
        </div>
        <div class="products-grid">
            <?php if (isset($products) && !empty($products)): ?>
                <?php foreach ($products as $product): ?>
                    <div class="product-card emerald-border">
                        <div class="product-image">
                            <img src="<?= base_url('uploads/produk/' . ($product['gambar'] ?? 'default.jpg')) ?>" alt="<?= esc($product['nama']) ?>" onerror="this.src='<?= base_url('assets/images/product-placeholder.jpg') ?>'">
                        </div>
                        <div class="product-info">
                            <h3 class="product-title"><?= esc($product['nama']) ?></h3>
                            <p class="product-description"><?= substr($product['deskripsi'] ?? '', 0, 100) ?>...</p>
                            <div class="product-footer">
                                <div class="product-price">
                                    <span class="current-price">Rp <?= number_format($product['harga'], 0, ',', '.') ?></span>
                                </div>
                            </div>
                            <a href="<?= base_url('produk/detail/' . $product['id']) ?>" class="btn btn-outline-primary btn-sm mt-2">Lihat Detail</a>
                        </div>
                    </div>
                <?php endforeach; ?>
            <?php else: ?>
                <div class="empty-state">
                    <img src="<?= base_url('assets/images/empty-products.svg') ?>" alt="Tidak ada Produk">
                    <p>Belum ada produk pada kategori ini</p>
                </div>
            <?php endif; ?>
        </div>
    </div>
</section>
<?= $this->endSection() ?>