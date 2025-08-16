<?= $this->extend('layouts/wrapper') ?>

<?= $this->section('content') ?>
<!-- Categories Section -->
<section class="categories-section">
    <div class="content-container">
        <div class="section-header text-center">
            <?php if (isset($keyword) && !empty($keyword)): ?>
                <h6 class="section-subtitle">Hasil Pencarian</h6>
                <h2 class="section-title">Kategori untuk: "<?= esc($keyword) ?>"</h2>
                <p class="section-description">Ditemukan <?= count($categories) ?> kategori yang sesuai dengan pencarian Anda</p>
            <?php else: ?>
                <h6 class="section-subtitle">Kategori Pilihan</h6>
                <h2 class="section-title">Jelajahi Kategori Produk</h2>
                <p class="section-description">Temukan berbagai kategori produk digital yang sesuai dengan kebutuhan Anda</p>
            <?php endif; ?>
        </div>

        <!-- Search Form -->
        <div class="search-container text-center mb-4">
            <form method="GET" action="<?= base_url('kategori') ?>" class="search-form">
                <div class="input-group mx-auto" style="max-width: 500px;">
                    <input type="text" name="search" class="form-control" placeholder="Cari kategori atau deskripsi..." value="<?= $keyword ?? '' ?>">
                    <button type="submit" class="btn btn-primary">
                        <i class="bi bi-search"></i> Cari
                    </button>
                    <?php if (!empty($keyword)) : ?>
                        <a href="<?= base_url('kategori') ?>" class="btn btn-outline-secondary">
                            <i class="bi bi-x-circle"></i> Clear
                        </a>
                    <?php endif; ?>
                </div>
            </form>
        </div>

        <div class="row">
            <?php if (isset($categories) && !empty($categories)): ?>
                <?php foreach ($categories as $category): ?>
                    <div class="col-12 col-sm-6 col-md-4 mb-4">
                        <div class="category-card h-100">
                            <div class="card-icon">
                                <i class="bi <?= $category['icon'] ?? 'bi-collection' ?>"></i>
                            </div>
                            <h3><?= esc($category['nama_kategori']) ?></h3>
                            <p><?= substr($category['deskripsi_kategori'] ?? 'Lihat koleksi produk kami', 0, 100) ?>...</p>
                            <a href="<?= base_url('kategori/detail/' . $category['id']) ?>" class="stretched-link"></a>
                        </div>
                    </div>
                <?php endforeach; ?>
            <?php else: ?>
                <div class="empty-state">
                    <?php if (isset($keyword) && !empty($keyword)): ?>
                        <img src="<?= base_url('assets/images/empty-category.svg') ?>" alt="No Categories">
                        <p>Tidak ada kategori yang ditemukan untuk "<?= esc($keyword) ?>"</p>
                        <a href="<?= base_url('kategori') ?>" class="btn btn-primary">Lihat Semua Kategori</a>
                    <?php else: ?>
                        <img src="<?= base_url('assets/images/empty-category.svg') ?>" alt="No Categories">
                        <p>Belum ada kategori tersedia</p>
                    <?php endif; ?>
                </div>
            <?php endif; ?>
        </div>
    </div>
</section>

<!-- Features Section -->
<section class="features-section">
    <div class="content-container">
        <div class="features-grid">
            <div class="feature-card">
                <div class="feature-icon">
                    <i class="bi bi-lightning-charge"></i>
                </div>
                <h3>Pengiriman Cepat</h3>
                <p>Produk digital langsung ke email Anda setelah pembayaran berhasil</p>
            </div>
            <div class="feature-card">
                <div class="feature-icon">
                    <i class="bi bi-shield-check"></i>
                </div>
                <h3>Transaksi Aman</h3>
                <p>Sistem pembayaran yang aman dan terpercaya untuk setiap transaksi</p>
            </div>
            <div class="feature-card">
                <div class="feature-icon">
                    <i class="bi bi-headset"></i>
                </div>
                <h3>Layanan 24/7</h3>
                <p>Tim dukungan pelanggan siap membantu Anda setiap saat</p>
            </div>
            <div class="feature-card">
                <div class="feature-icon">
                    <i class="bi bi-arrow-repeat"></i>
                </div>
                <h3>Garansi Produk</h3>
                <p>Jaminan kualitas terbaik untuk setiap produk digital</p>
            </div>
        </div>
    </div>
</section>
<?= $this->endSection() ?>