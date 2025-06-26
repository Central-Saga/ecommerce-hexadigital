<?= $this->extend('layouts/wrapper') ?>

<?= $this->section('content') ?>
<!-- Hero Section -->
<section class="hero-section">
    <div class="hero-pattern"></div>
    <div class="content-container">
        <div class="row align-items-center">
            <div class="col-lg-6 hero-content">
                <div class="hero-badge">
                    <span class="badge-icon"><i class="bi bi-shield-check"></i></span>
                    <span class="badge-text">Platform E-Commerce Terpercaya</span>
                </div>
                <h1 class="hero-title">Belanja Digital Lebih Mudah</h1>
                <p class="hero-description">Temukan berbagai produk digital berkualitas untuk kebutuhan Anda dengan harga terbaik dan pelayanan terpercaya.</p>
                <div class="hero-buttons">
                    <a href="<?= base_url('produk') ?>" class="btn btn-primary btn-lg">
                        <i class="bi bi-cart-plus"></i>
                        Mulai Belanja
                    </a>
                    <a href="<?= base_url('kategori') ?>" class="btn btn-outline-primary btn-lg">
                        <i class="bi bi-grid"></i>
                        Lihat Kategori
                    </a>
                </div>

                <!-- Quick Stats -->
                <div class="quick-stats">
                    <div class="stat-item">
                        <div class="stat-value">10K+</div>
                        <div class="stat-label">Produk Digital</div>
                    </div>
                    <div class="stat-item">
                        <div class="stat-value">50K+</div>
                        <div class="stat-label">Pelanggan Puas</div>
                    </div>
                    <div class="stat-item">
                        <div class="stat-value">24/7</div>
                        <div class="stat-label">Dukungan</div>
                    </div>
                </div>
            </div>
            <div class="col-lg-6 hero-image">
                <div class="floating-card card-1">
                    <i class="bi bi-shield-check"></i>
                    <span>Pembayaran Aman</span>
                </div>
                <div class="floating-card card-2">
                    <i class="bi bi-lightning-charge"></i>
                    <span>Pengiriman Cepat</span>
                </div>
                <img src="<?= base_url('assets/images/hero-illustration.svg') ?>" alt="Hero Illustration" class="img-fluid">
            </div>
        </div>
    </div>
</section>

<!-- Categories Section -->
<section class="categories-section">
    <div class="content-container">
        <div class="section-header text-center">
            <h6 class="section-subtitle">Kategori Pilihan</h6>
            <h2 class="section-title">Jelajahi Kategori Produk</h2>
            <p class="section-description">Temukan berbagai kategori produk digital yang sesuai dengan kebutuhan Anda</p>
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
                            <p><?= substr($category['deskripsi'] ?? 'Lihat koleksi produk kami', 0, 100) ?>...</p>
                            <a href="<?= base_url('kategori/detail/' . $category['id']) ?>" class="stretched-link"></a>
                        </div>
                    </div>
                <?php endforeach; ?>
            <?php else: ?>
                <div class="empty-state">
                    <img src="<?= base_url('assets/images/empty-category.svg') ?>" alt="No Categories">
                    <p>Belum ada kategori tersedia</p>
                </div>
            <?php endif; ?>
        </div>
    </div>
</section>

<!-- Featured Products -->
<section class="products-section">
    <div class="content-container">
        <div class="section-header text-center">
            <h6 class="section-subtitle">Produk Unggulan</h6>
            <h2 class="section-title">Rekomendasi Untuk Anda</h2>
            <p class="section-description">Produk terbaik yang telah kami pilih khusus untuk Anda</p>
        </div>

        <div class="row">
            <?php if (isset($featured_products) && !empty($featured_products)): ?>
                <?php foreach ($featured_products as $product): ?>
                    <div class="col-12 col-sm-6 col-md-4 col-lg-3 mb-4">
                        <div class="product-card h-100">
                            <div class="product-image">
                                <img src="<?= base_url('uploads/produk/' . ($product['gambar'] ?? 'default.jpg')) ?>"
                                    alt="<?= esc($product['nama']) ?>"
                                    onerror="this.src='<?= base_url('assets/images/product-placeholder.png') ?>'">
                                <?php if (isset($product['discount']) && $product['discount'] > 0): ?>
                                    <div class="product-badge">-<?= $product['discount'] ?>%</div>
                                <?php endif; ?>
                                <div class="product-actions">
                                    <button class="action-btn" onclick="addToCart(<?= $product['id'] ?>)">
                                        <i class="bi bi-cart-plus"></i>
                                    </button>
                                    <a href="<?= base_url('produk/detail/' . $product['id']) ?>" class="action-btn" title="Lihat Detail">
                                        <i class="bi bi-eye"></i>
                                    </a>
                                </div>
                            </div>
                            <div class="product-info">
                                <div class="product-category">
                                    <i class="bi bi-tag"></i>
                                    <?= $product['kategori'] ?? 'Uncategorized' ?>
                                </div>
                                <h3 class="product-title"><?= esc($product['nama']) ?></h3>
                                <p class="product-description"><?= substr($product['deskripsi_singkat'] ?? '', 0, 100) ?>...</p>
                                <div class="product-footer">
                                    <div class="product-price">
                                        <?php if (isset($product['harga_asli']) && $product['harga_asli'] > $product['harga']): ?>
                                            <del class="original-price">Rp <?= number_format($product['harga_asli'], 0, ',', '.') ?></del>
                                        <?php endif; ?>
                                        <span class="current-price">Rp <?= number_format($product['harga'], 0, ',', '.') ?></span>
                                    </div>
                                    <div class="product-rating">
                                        <i class="bi bi-star-fill"></i>
                                        <span>4.5</span>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                <?php endforeach; ?>
            <?php else: ?>
                <div class="empty-state">
                    <img src="<?= base_url('assets/images/empty-products.svg') ?>" alt="No Products">
                    <p>Belum ada produk unggulan</p>
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

<script>
    function addToCart(productId) {
        fetch('<?= site_url('cart/cart') ?>', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/x-www-form-urlencoded'
                },
                body: 'product_id=' + productId + '&qty=1'
            })
            .then(res => res.json())
            .then(data => {
                if (data.success) {
                    Swal.fire({
                        icon: 'success',
                        title: 'Berhasil',
                        text: 'Produk ditambahkan ke keranjang!',
                        showConfirmButton: false,
                        timer: 1200
                    });
                    // Update cart count
                    if (window.updateCartCount) {
                        updateCartCount();
                    }
                } else {
                    // Handle login required
                    if (data.message && data.message.includes('login')) {
                        Swal.fire({
                            icon: 'warning',
                            title: 'Login Diperlukan',
                            text: 'Anda harus login terlebih dahulu untuk menambahkan produk ke keranjang',
                            showCancelButton: true,
                            confirmButtonText: 'Login Sekarang',
                            cancelButtonText: 'Batal',
                            confirmButtonColor: '#3085d6',
                            cancelButtonColor: '#6c757d'
                        }).then((result) => {
                            if (result.isConfirmed) {
                                window.location.href = '<?= site_url('login') ?>';
                            }
                        });
                    } else {
                        Swal.fire({
                            icon: 'error',
                            title: 'Gagal',
                            text: data.message || 'Gagal menambah ke keranjang'
                        });
                    }
                }
            })
            .catch(error => {
                console.error('Error:', error);
                Swal.fire({
                    icon: 'error',
                    title: 'Gagal',
                    text: 'Terjadi kesalahan saat menambah ke keranjang'
                });
            });
    }

    function addToWishlist(productId) {
        // Implementasi logika menambah ke wishlist
        alert('Produk ditambahkan ke wishlist!');
    }
</script>

<?= $this->endSection() ?>