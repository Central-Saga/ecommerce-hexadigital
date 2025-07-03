<?= $this->extend('layouts/wrapper') ?>
<?= $this->section('content') ?>
<section class="products-section">
    <div class="content-container">
        <div class="section-header text-center" style="margin-top: 3rem;">
            <?php if (isset($keyword) && !empty($keyword)): ?>
                <h6 class="section-subtitle">Hasil Pencarian</h6>
                <h2 class="section-title">Produk untuk: "<?= esc($keyword) ?>"</h2>
                <p class="section-description">Ditemukan <?= count($products) ?> produk yang sesuai dengan pencarian Anda</p>
            <?php else: ?>
                <h6 class="section-subtitle">Semua Produk</h6>
                <h2 class="section-title">Daftar Produk Digital</h2>
                <p class="section-description">Temukan produk digital terbaik untuk kebutuhan Anda</p>
            <?php endif; ?>
        </div>
        <div class="products-grid">
            <?php if (isset($products) && !empty($products)): ?>
                <?php foreach ($products as $product): ?>
                    <div class="product-card">
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
                    <img src="<?= base_url('assets/images/empty-products.svg') ?>" alt="No Products">
                    <?php if (isset($keyword) && !empty($keyword)): ?>
                        <p>Tidak ada produk yang ditemukan untuk "<?= esc($keyword) ?>"</p>
                        <a href="<?= base_url('produk') ?>" class="btn btn-primary">Lihat Semua Produk</a>
                    <?php else: ?>
                        <p>Belum ada produk tersedia</p>
                    <?php endif; ?>
                </div>
            <?php endif; ?>
        </div>
    </div>
</section>
<?= $this->endSection() ?>