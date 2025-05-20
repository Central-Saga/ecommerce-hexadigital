<?php
$cart = [];
if (isset($_COOKIE['cart'])) {
    $cart = json_decode($_COOKIE['cart'], true) ?? [];
} elseif (isset($_SESSION['cart'])) {
    $cart = $_SESSION['cart'];
}
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
                                <img src="<?= base_url('uploads/produk/' . ($product['gambar'] ?? 'default.jpg')) ?>" alt="<?= esc($product['nama_produk']) ?>" width="60" class="me-2">
                                <?= esc($product['nama_produk']) ?>
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
<script>
function renderCart() {
    let cart = JSON.parse(localStorage.getItem('cart') || '{}');
    document.cookie = 'cart=' + JSON.stringify(cart) + ';path=/';
}
renderCart();
// Update quantity
$(document).on('change', '.qty-input', function() {
    var id = $(this).data('id');
    var qty = $(this).val();
    let cart = JSON.parse(localStorage.getItem('cart') || '{}');
    cart[id] = parseInt(qty);
    localStorage.setItem('cart', JSON.stringify(cart));
    renderCart();
    location.reload();
});
// Remove item
$(document).on('click', '.remove-btn', function() {
    var id = $(this).data('id');
    let cart = JSON.parse(localStorage.getItem('cart') || '{}');
    delete cart[id];
    localStorage.setItem('cart', JSON.stringify(cart));
    renderCart();
    location.reload();
});
</script>
<?= $this->endSection() ?>
