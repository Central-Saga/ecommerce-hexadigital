<?= $this->extend('layouts/wrapper') ?>
<?= $this->section('content') ?>
<section class="categories-list-section" style="min-height: 70vh; display: flex; flex-direction: column; justify-content: flex-start;">
    <div class="content-container">
        <div class="section-header text-center" style="margin-top: 3rem;">
            <h6 class="section-subtitle">Semua Kategori</h6>
            <h2 class="section-title">Daftar Kategori Produk</h2>
            <p class="section-description">Pilih kategori untuk melihat produk terkait</p>
        </div>
        <div class="categories-grid">
            <?php if(isset($categories) && !empty($categories)): ?>
                <?php foreach($categories as $category): ?>
                <div class="category-card">
                    <div class="card-icon">
                        <i class="bi <?= $category['icon'] ?? 'bi-collection' ?>"></i>
                    </div>
                    <h3><?= esc($category['nama_kategori']) ?></h3>
                    <p><?= substr($category['deskripsi_kategori'] ?? 'Lihat koleksi produk kami', 0, 100) ?>...</p>
                    <a href="<?= base_url('kategori/' . $category['id']) ?>" class="stretched-link"></a>
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
<?= $this->endSection() ?>
