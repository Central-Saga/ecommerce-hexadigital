<?= $this->extend('layouts/wrapper') ?>

<?= $this->section('content') ?>
<!-- Hero Section -->
<section class="hero-section">
    <div class="hero-pattern"></div>
    <div class="container">
        <div class="row align-items-center">
            <div class="col-lg-6 hero-content">
                <div class="hero-badge">
                    <span class="badge-icon"><i class="bi bi-shield-check"></i></span>
                    <span class="badge-text">Platform E-Commerce Terpercaya</span>
                </div>
                <h1 class="hero-title">Belanja Digital Lebih Mudah</h1>
                <p class="hero-description">Temukan berbagai produk digital berkualitas untuk kebutuhan Anda dengan harga terbaik dan pelayanan terpercaya.</p>
                <div class="hero-buttons">
                    <a href="<?= base_url('products') ?>" class="btn btn-primary btn-lg">
                        <i class="bi bi-cart-plus"></i>
                        Mulai Belanja
                    </a>
                    <a href="<?= base_url('categories') ?>" class="btn btn-outline-primary btn-lg">
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
    <div class="container">
        <div class="section-header text-center">
            <h6 class="section-subtitle">Kategori Pilihan</h6>
            <h2 class="section-title">Jelajahi Kategori Produk</h2>
            <p class="section-description">Temukan berbagai kategori produk digital yang sesuai dengan kebutuhan Anda</p>
        </div>
        
        <div class="categories-grid">
            <?php if(isset($categories) && !empty($categories)): ?>
                <?php foreach($categories as $category): ?>
                <div class="category-card">
                    <div class="card-icon">
                        <i class="bi <?= $category['icon'] ?? 'bi-collection' ?>"></i>
                    </div>
                    <h3><?= esc($category['nama_kategori']) ?></h3>
                    <p><?= substr($category['deskripsi'] ?? 'Lihat koleksi produk kami', 0, 100) ?>...</p>
                    <a href="<?= base_url('category/' . $category['id']) ?>" class="stretched-link"></a>
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
    <div class="container">
        <div class="section-header text-center">
            <h6 class="section-subtitle">Produk Unggulan</h6>
            <h2 class="section-title">Rekomendasi Untuk Anda</h2>
            <p class="section-description">Produk terbaik yang telah kami pilih khusus untuk Anda</p>
        </div>

        <div class="products-grid">
            <?php if(isset($featured_products) && !empty($featured_products)): ?>
                <?php foreach($featured_products as $product): ?>
                <div class="product-card">
                    <div class="product-image">
                        <img src="<?= base_url('uploads/produk/' . ($product['gambar'] ?? 'default.jpg')) ?>" 
                             alt="<?= esc($product['nama_produk']) ?>"
                             onerror="this.src='<?= base_url('assets/images/product-placeholder.jpg') ?>'">
                        <?php if(isset($product['discount']) && $product['discount'] > 0): ?>
                            <div class="product-badge">-<?= $product['discount'] ?>%</div>
                        <?php endif; ?>
                        <div class="product-actions">
                            <button class="action-btn" onclick="addToCart(<?= $product['id'] ?>)">
                                <i class="bi bi-cart-plus"></i>
                            </button>
                            <button class="action-btn" onclick="addToWishlist(<?= $product['id'] ?>)">
                                <i class="bi bi-heart"></i>
                            </button>
                            <a href="<?= base_url('product/' . $product['id']) ?>" class="action-btn">
                                <i class="bi bi-eye"></i>
                            </a>
                        </div>
                    </div>
                    <div class="product-info">
                        <div class="product-category">
                            <i class="bi bi-tag"></i>
                            <?= $product['kategori'] ?? 'Uncategorized' ?>
                        </div>
                        <h3 class="product-title"><?= esc($product['nama_produk']) ?></h3>
                        <p class="product-description"><?= substr($product['deskripsi_singkat'] ?? '', 0, 100) ?>...</p>
                        <div class="product-footer">
                            <div class="product-price">
                                <?php if(isset($product['harga_asli']) && $product['harga_asli'] > $product['harga']): ?>
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
    <div class="container">
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

<!-- Newsletter Section -->
<section class="newsletter-section">
    <div class="container">
        <div class="newsletter-content">
            <div class="row align-items-center">
                <div class="col-lg-6">
                    <h2>Dapatkan Update & Penawaran Terbaru</h2>
                    <p>Berlangganan newsletter kami untuk mendapatkan informasi produk terbaru dan penawaran eksklusif</p>
                </div>
                <div class="col-lg-6">
                    <form class="newsletter-form">
                        <div class="input-group">
                            <input type="email" class="form-control" placeholder="Masukkan email Anda">
                            <button class="btn btn-primary" type="submit">Berlangganan</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</section>

<style>
/* Base Styles */
:root {
    --primary-color: #10B981;
    --primary-dark: #059669;
    --primary-light: #D1FAE5;
    --secondary-color: #4F46E5;
    --text-dark: #1F2937;
    --text-light: #6B7280;
    --white: #FFFFFF;
    --gray-50: #F9FAFB;
    --gray-100: #F3F4F6;
    --gray-200: #E5E7EB;
    --success: #059669;
    --warning: #FBBF24;
    --danger: #DC2626;
}

/* Hero Section */
.hero-section {
    position: relative;
    padding: 80px 0;
    background: linear-gradient(135deg, #ECFDF5 0%, #D1FAE5 100%);
    overflow: hidden;
}

.hero-pattern {
    position: absolute;
    top: 0;
    left: 0;
    width: 100%;
    height: 100%;
    opacity: 0.4;
    background-image: url("data:image/svg+xml,%3Csvg width='60' height='60' viewBox='0 0 60 60' xmlns='http://www.w3.org/2000/svg'%3E%3Cg fill='none' fill-rule='evenodd'%3E%3Cg fill='%2399f6e4' fill-opacity='0.4'%3E%3Cpath d='M36 34v-4h-2v4h-4v2h4v4h2v-4h4v-2h-4zm0-30V0h-2v4h-4v2h4v4h2V6h4V4h-4zM6 34v-4H4v4H0v2h4v4h2v-4h4v-2H6zM6 4V0H4v4H0v2h4v4h2V6h4V4H6z'/%3E%3C/g%3E%3C/g%3E%3C/svg%3E");
}

.hero-content {
    position: relative;
    z-index: 1;
}

.hero-badge {
    display: inline-flex;
    align-items: center;
    padding: 8px 16px;
    background: var(--white);
    border-radius: 50px;
    margin-bottom: 24px;
    box-shadow: 0 2px 4px rgba(0,0,0,0.05);
}

.badge-icon {
    color: var(--primary-color);
    margin-right: 8px;
}

.badge-text {
    color: var(--text-dark);
    font-weight: 500;
}

.hero-title {
    font-size: 3.5rem;
    font-weight: 700;
    color: var(--text-dark);
    margin-bottom: 24px;
    line-height: 1.2;
}

.hero-description {
    font-size: 1.25rem;
    color: var(--text-light);
    margin-bottom: 32px;
    max-width: 600px;
}

.hero-buttons {
    display: flex;
    gap: 16px;
    margin-bottom: 48px;
}

.btn-lg {
    padding: 12px 24px;
    font-weight: 500;
}

.btn-primary {
    background: var(--primary-color);
    border-color: var(--primary-color);
}

.btn-primary:hover {
    background: var(--primary-dark);
    border-color: var(--primary-dark);
    transform: translateY(-2px);
}

.btn-outline-primary {
    color: var(--primary-color);
    border-color: var(--primary-color);
}

.btn-outline-primary:hover {
    background: var(--primary-color);
    color: var(--white);
    transform: translateY(-2px);
}

.quick-stats {
    display: flex;
    gap: 48px;
    margin-top: 48px;
}

.stat-item {
    text-align: center;
}

.stat-value {
    font-size: 2rem;
    font-weight: 700;
    color: var(--primary-color);
    margin-bottom: 8px;
}

.stat-label {
    color: var(--text-light);
    font-size: 0.875rem;
}

.hero-image {
    position: relative;
}

.floating-card {
    position: absolute;
    background: var(--white);
    padding: 16px 24px;
    border-radius: 12px;
    box-shadow: 0 8px 24px rgba(0,0,0,0.1);
    display: flex;
    align-items: center;
    gap: 12px;
    animation: float 6s ease-in-out infinite;
}

.floating-card i {
    color: var(--primary-color);
    font-size: 1.5rem;
}

.floating-card span {
    color: var(--text-dark);
    font-weight: 500;
}

.card-1 {
    top: 20%;
    left: 0;
    animation-delay: 1s;
}

.card-2 {
    bottom: 20%;
    right: 0;
    animation-delay: 2s;
}

@keyframes float {
    0% { transform: translateY(0); }
    50% { transform: translateY(-20px); }
    100% { transform: translateY(0); }
}

/* Categories Section */
.categories-section {
    padding: 80px 0;
    background: var(--gray-50);
}

.section-header {
    margin-bottom: 48px;
}

.section-subtitle {
    color: var(--primary-color);
    font-weight: 600;
    text-transform: uppercase;
    letter-spacing: 1px;
    margin-bottom: 16px;
}

.section-title {
    color: var(--text-dark);
    font-size: 2.5rem;
    font-weight: 700;
    margin-bottom: 16px;
}

.section-description {
    color: var(--text-light);
    max-width: 600px;
    margin: 0 auto;
}

.categories-grid {
    display: grid;
    grid-template-columns: repeat(auto-fit, minmax(300px, 1fr));
    gap: 24px;
    margin: 0 auto;
    max-width: 100%;
}

.category-card {
    background: var(--white);
    padding: 32px;
    border-radius: 16px;
    text-align: center;
    transition: all 0.3s ease;
    cursor: pointer;
    position: relative;
    width: 100%;
    display: flex;
    flex-direction: column;
    align-items: center;
}

.category-card:hover {
    transform: translateY(-8px);
    box-shadow: 0 12px 24px rgba(0,0,0,0.1);
}

.card-icon {
    width: 64px;
    height: 64px;
    background: var(--primary-light);
    border-radius: 50%;
    display: flex;
    align-items: center;
    justify-content: center;
    margin: 0 auto 24px;
}

.card-icon i {
    font-size: 1.75rem;
    color: var(--primary-color);
}

.category-card h3 {
    color: var(--text-dark);
    font-size: 1.25rem;
    font-weight: 600;
    margin-bottom: 12px;
}

.category-card p {
    color: var(--text-light);
    font-size: 0.875rem;
    margin: 0;
}

/* Products Section */
.products-section {
    padding: 80px 0;
}

.products-grid {
    display: grid;
    grid-template-columns: repeat(auto-fit, minmax(320px, 1fr));
    gap: 24px;
    margin: 0 auto;
    max-width: 100%;
}

.product-card {
    background: var(--white);
    border-radius: 16px;
    overflow: hidden;
    transition: all 0.3s ease;
    width: 100%;
    display: flex;
    flex-direction: column;
    height: 100%;
}

.product-card:hover {
    transform: translateY(-8px);
    box-shadow: 0 12px 24px rgba(0,0,0,0.1);
}

.product-image {
    position: relative;
    height: 200px;
    overflow: hidden;
}

.product-image img {
    width: 100%;
    height: 100%;
    object-fit: cover;
    transition: transform 0.3s ease;
}

.product-card:hover .product-image img {
    transform: scale(1.1);
}

.product-badge {
    position: absolute;
    top: 12px;
    right: 12px;
    background: var(--danger);
    color: var(--white);
    padding: 4px 12px;
    border-radius: 20px;
    font-weight: 500;
    font-size: 0.875rem;
}

.product-actions {
    position: absolute;
    bottom: -60px;
    left: 0;
    right: 0;
    background: rgba(0,0,0,0.8);
    padding: 12px;
    display: flex;
    justify-content: center;
    gap: 12px;
    transition: bottom 0.3s ease;
}

.product-card:hover .product-actions {
    bottom: 0;
}

.action-btn {
    width: 40px;
    height: 40px;
    border-radius: 50%;
    background: var(--white);
    border: none;
    display: flex;
    align-items: center;
    justify-content: center;
    color: var(--text-dark);
    transition: all 0.3s ease;
    cursor: pointer;
}

.action-btn:hover {
    background: var(--primary-color);
    color: var(--white);
    transform: translateY(-2px);
}

.product-info {
    padding: 24px;
}

.product-category {
    font-size: 0.875rem;
    color: var(--text-light);
    margin-bottom: 8px;
}

.product-category i {
    color: var(--primary-color);
    margin-right: 4px;
}

.product-title {
    font-size: 1.125rem;
    font-weight: 600;
    color: var(--text-dark);
    margin-bottom: 8px;
}

.product-description {
    color: var(--text-light);
    font-size: 0.875rem;
    margin-bottom: 16px;
}

.product-footer {
    display: flex;
    justify-content: space-between;
    align-items: center;
}

.product-price {
    display: flex;
    flex-direction: column;
}

.original-price {
    font-size: 0.875rem;
    color: var(--text-light);
    text-decoration: line-through;
}

.current-price {
    font-size: 1.25rem;
    font-weight: 600;
    color: var(--primary-color);
}

.product-rating {
    display: flex;
    align-items: center;
    gap: 4px;
}

.product-rating i {
    color: var(--warning);
}

/* Features Section */
.features-section {
    padding: 80px 0;
    background: var(--gray-50);
}

.features-grid {
    display: grid;
    grid-template-columns: repeat(auto-fit, minmax(250px, 1fr));
    gap: 24px;
}

.feature-card {
    background: var(--white);
    padding: 32px;
    border-radius: 16px;
    text-align: center;
    transition: all 0.3s ease;
}

.feature-card:hover {
    transform: translateY(-8px);
    box-shadow: 0 12px 24px rgba(0,0,0,0.1);
}

.feature-icon {
    width: 64px;
    height: 64px;
    background: var(--primary-light);
    border-radius: 50%;
    display: flex;
    align-items: center;
    justify-content: center;
    margin: 0 auto 24px;
}

.feature-icon i {
    font-size: 1.75rem;
    color: var(--primary-color);
}

.feature-card h3 {
    color: var(--text-dark);
    font-size: 1.25rem;
    font-weight: 600;
    margin-bottom: 12px;
}

.feature-card p {
    color: var(--text-light);
    font-size: 0.875rem;
    margin: 0;
}

/* Newsletter Section */
.newsletter-section {
    padding: 80px 0;
    background: linear-gradient(135deg, var(--primary-color) 0%, var(--secondary-color) 100%);
    color: var(--white);
}

.newsletter-content {
    padding: 48px;
    border-radius: 16px;
    background: rgba(255,255,255,0.1);
}

.newsletter-content h2 {
    font-size: 2rem;
    font-weight: 700;
    margin-bottom: 16px;
}

.newsletter-content p {
    opacity: 0.9;
    margin-bottom: 0;
}

.newsletter-form .input-group {
    background: var(--white);
    border-radius: 8px;
    overflow: hidden;
    padding: 4px;
}

.newsletter-form .form-control {
    border: none;
    padding: 12px 16px;
    font-size: 1rem;
}

.newsletter-form .form-control:focus {
    box-shadow: none;
}

.newsletter-form .btn {
    padding: 12px 24px;
    font-weight: 500;
}

/* Empty State */
.empty-state {
    text-align: center;
    padding: 48px 0;
}

.empty-state img {
    width: 200px;
    margin-bottom: 24px;
    opacity: 0.5;
}

.empty-state p {
    color: var(--text-light);
    font-size: 1.125rem;
}

/* Responsive Styles */
@media (max-width: 991px) {
    .hero-title {
        font-size: 2.5rem;
    }

    .hero-description {
        font-size: 1.125rem;
    }

    .quick-stats {
        gap: 24px;
    }

    .floating-card {
        display: none;
    }

    .newsletter-content {
        text-align: center;
    }

    .newsletter-form {
        margin-top: 24px;
    }
}

@media (max-width: 767px) {
    .hero-section {
        padding: 40px 0;
        text-align: center;
    }

    .hero-buttons {
        justify-content: center;
    }

    .quick-stats {
        justify-content: center;
    }

    .section-title {
        font-size: 2rem;
    }

    .categories-grid,
    .products-grid,
    .features-grid {
        grid-template-columns: repeat(auto-fit, minmax(200px, 1fr));
        gap: 16px;
    }
}
</style>

<script>
function addToCart(productId) {
    // Implementasi logika menambah ke keranjang
    alert('Produk ditambahkan ke keranjang!');
}

function addToWishlist(productId) {
    // Implementasi logika menambah ke wishlist
    alert('Produk ditambahkan ke wishlist!');
}
</script>

<?= $this->endSection() ?>