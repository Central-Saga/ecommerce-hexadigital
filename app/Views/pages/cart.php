<?php
$cart = session()->get('cart') ?? [];
$productIds = array_keys($cart);
$products = [];
$total = 0;
if (!empty($productIds)) {
    $db = \Config\Database::connect();
    $builder = $db->table('produk');
    $products = $builder->whereIn('id', $productIds)->get()->getResultArray();
    foreach ($products as &$product) {
        $product['qty'] = $cart[$product['id']];
        $product['subtotal'] = $product['qty'] * $product['harga'];
        $total += $product['subtotal'];
    }
}
?>
<?= $this->extend('layouts/wrapper') ?>

<?= $this->section('content') ?>
<div class="main-cart-wrapper d-flex flex-column min-vh-100">
    <div class="flex-grow-1">
        <div class="container py-5">
            <h2 class="mb-4">Keranjang Belanja</h2>
            <?php if (!empty($products)): ?>
                <div class="table-responsive">
                    <table class="table align-middle">
                        <thead>
                            <tr>
                                <th>Produk</th>
                                <th>Harga</th>
                                <th>Jumlah</th>
                                <th>Subtotal</th>
                                <th></th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php foreach ($products as $product): ?>
                                <tr>
                                    <td>
                                        <img src="<?= base_url('uploads/produk/' . ($product['gambar'] ?? 'default.jpg')) ?>" alt="<?= esc($product['nama']) ?>" width="60" class="me-2">
                                        <?= esc($product['nama']) ?>
                                    </td>
                                    <td>Rp <?= number_format($product['harga'], 0, ',', '.') ?></td>
                                    <td>
                                        <input type="number" min="1" value="<?= $product['qty'] ?>" class="form-control form-control-sm qty-input" data-id="<?= $product['id'] ?>">
                                    </td>
                                    <td>Rp <?= number_format($product['subtotal'], 0, ',', '.') ?></td>
                                    <td>
                                        <button class="btn btn-danger btn-sm remove-btn" data-id="<?= $product['id'] ?>"><i class="bi bi-trash"></i></button>
                                    </td>
                                </tr>
                            <?php endforeach; ?>
                        </tbody>
                    </table>
                </div>
                <div class="d-flex justify-content-end">
                    <h4>Total: Rp <?= number_format($total, 0, ',', '.') ?></h4>
                </div>
                <div class="d-flex justify-content-end mt-3">
                    <a href="#" class="btn btn-success">Checkout</a>
                </div>
            <?php else: ?>
                <div class="alert alert-info">Keranjang belanja Anda kosong.</div>
            <?php endif; ?>
        </div>
    </div>
</div>
<script>
    // Update quantity
    $(document).on('change', '.qty-input', function() {
        var id = $(this).data('id');
        var qty = $(this).val();
        $.post('<?= site_url('cart/update') ?>', {
            product_id: id,
            qty: qty
        }, function(data) {
            if (data.success) {
                location.reload();
            } else {
                alert(data.message || 'Gagal update jumlah');
            }
        }, 'json');
    });
    // Remove item
    $(document).on('click', '.remove-btn', function() {
        var id = $(this).data('id');
        $.post('<?= site_url('cart/remove') ?>', {
            product_id: id
        }, function(data) {
            if (data.success) {
                location.reload();
            } else {
                alert('Gagal menghapus produk');
            }
        }, 'json');
    });
</script>
<?= $this->endSection() ?>
<style>
    .main-cart-wrapper {
        min-height: 100vh;
    }
</style>