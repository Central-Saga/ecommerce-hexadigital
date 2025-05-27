<?= $this->extend('layouts/wrapper') ?>

<?= $this->section('content') ?>

<div class="product-detail">
    <div class="product-container two-col">
        <div class="product-image" id="productImageContainer">
            <?php if ($product['gambar']): ?>
                <img src="<?= base_url('uploads/produk/' . $product['gambar']) ?>" alt="<?= $product['nama'] ?>" id="productImage" style="cursor: zoom-in;" onerror="this.src='<?= base_url('assets/images/product-placeholder.png') ?>'" />
            <?php else: ?>
                <img src="<?= base_url('assets/images/product-placeholder.png') ?>" alt="No Image" id="productImage" style="cursor: zoom-in;" onerror="this.src='<?= base_url('assets/images/product-placeholder.png') ?>'" />
            <?php endif; ?>
        </div>
        <div class="product-info">
            <h1 class="product-title mb-1"><?= esc($product['nama']) ?></h1>
            <div class="product-price mb-2">Rp <?= number_format($product['harga'], 0, ',', '.') ?></div>
            <div class="product-meta product-meta-bordered mb-2">
                <div class="meta-item">
                    <span class="label"><i class="fas fa-tags"></i> Kategori</span>
                    <span class="value"><?= esc($kategori['nama_kategori'] ?? '-') ?></span>
                </div>
                <div class="meta-item">
                    <span class="label"><i class="fas fa-box"></i> Stok</span>
                    <span class="value"><?= $product['stok'] ?> unit</span>
                </div>
            </div>
            <div class="product-description mb-2">
                <h2 class="desc-title"><i class="fas fa-info-circle"></i> Detail Produk</h2>
                <div class="desc-content"><?= esc($product['deskripsi']) ?></div>
            </div>
            <?php if ($product['stok'] > 0): ?>
                <div class="mb-2">
                    <label for="qtyInput" class="form-label">Jumlah</label>
                    <input type="number" id="qtyInput" class="form-control" value="1" min="1" max="<?= $product['stok'] ?>" style="width:120px;display:inline-block;">
                </div>
                <button class="btn btn-primary add-to-cart">
                    <i class="fas fa-cart-plus"></i> Tambah ke Keranjang
                </button>
            <?php else: ?>
                <button class="btn btn-secondary" disabled>
                    <i class="fas fa-times"></i> Stok Habis
                </button>
            <?php endif; ?>
        </div>
    </div>
    <!-- Popup Modal for Full Image -->
    <div id="imageModal" style="display:none;position:fixed;z-index:9999;left:0;top:0;width:100vw;height:100vh;background:rgba(0,0,0,0.85);align-items:center;justify-content:center;">
        <span id="closeModal" style="position:absolute;top:32px;right:48px;font-size:3rem;color:#fff;cursor:pointer;font-weight:bold;z-index:10001;">&times;</span>
        <img id="modalImg" src="" alt="Full Image" style="max-width:90vw;max-height:90vh;border-radius:1.2rem;box-shadow:0 8px 32px rgba(0,0,0,0.25);background:#fff;" />
    </div>
</div>
<?= $this->endSection() ?>

<?= $this->section('scripts') ?>
<script>
    const productImage = document.getElementById('productImage');
    const imageModal = document.getElementById('imageModal');
    const modalImg = document.getElementById('modalImg');
    const closeModal = document.getElementById('closeModal');
    if (productImage && imageModal && modalImg && closeModal) {
        productImage.addEventListener('click', function() {
            modalImg.src = this.src;
            imageModal.style.display = 'flex';
            document.body.style.overflow = 'hidden';
        });
        closeModal.addEventListener('click', function() {
            imageModal.style.display = 'none';
            document.body.style.overflow = '';
        });
        imageModal.addEventListener('click', function(e) {
            if (e.target === imageModal) {
                imageModal.style.display = 'none';
                document.body.style.overflow = '';
            }
        });
    }
    document.querySelector('.add-to-cart')?.addEventListener('click', function() {
        let productId = <?= $product['id'] ?>;
        let qty = parseInt(document.getElementById('qtyInput')?.value || '1');
        fetch('<?= site_url('cart/cart') ?>', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/x-www-form-urlencoded'
                },
                body: 'product_id=' + productId + '&qty=' + qty
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
                    }).then(() => {
                        window.location.href = '<?= site_url('cart') ?>';
                    });
                    setTimeout(function() {
                        window.location.href = '<?= site_url('cart') ?>';
                    }, 1300);
                    if (window.updateCartCount) updateCartCount(data.cart_count);
                } else {
                    Swal.fire({
                        icon: 'error',
                        title: 'Gagal',
                        text: data.message || 'Gagal menambah ke keranjang'
                    });
                }
            });
    });
</script>
<?= $this->endSection() ?>