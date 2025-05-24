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
                                        <img src="<?= base_url(($product['gambar'] ?? 'default.jpg')) ?>" alt="<?= esc($product['nama']) ?>" width="60" class="me-2">
                                        <?= esc($product['nama']) ?>
                                    </td>
                                    <td>
                                        <span class="harga-produk" data-harga="<?= $product['harga'] ?>">Rp <?= number_format($product['harga'], 0, ',', '.') ?></span>
                                    </td>
                                    <td>
                                        <input type="number" min="1" value="<?= $product['jumlah'] ?>" class="form-control form-control-sm qty-input" data-id="<?= $product['produk_id'] ?>" data-harga="<?= $product['harga'] ?>" style="width:80px;">
                                    </td>
                                    <td>
                                        <span class="subtotal-produk">Rp <?= number_format($product['subtotal'], 0, ',', '.') ?></span>
                                    </td>
                                    <td>
                                        <button class="btn btn-danger btn-sm remove-btn" data-id="<?= $product['produk_id'] ?>"><i class="bi bi-trash"></i></button>
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
                    <form method="post" action="<?= site_url('checkout/store') ?>">
                        <input type="hidden" name="pelanggan_id" value="<?= esc($pelanggan_id ?? session('pelanggan_id')) ?>">
                        <button type="submit" class="btn btn-success">Checkout</button>
                    </form>
                </div>
            <?php else: ?>
                <div class="alert alert-info">Keranjang belanja Anda kosong.</div>
            <?php endif; ?>
        </div>
    </div>
</div>
<?= $this->endSection() ?>

<?= $this->section('scripts') ?>
<script>
    $(function() {
        // Fungsi untuk format angka ke rupiah
        function formatRupiah(angka) {
            return 'Rp ' + angka.toLocaleString('id-ID');
        }
        // Update subtotal dan total dinamis
        $(document).on('input', '.qty-input', function() {
            var qty = parseInt($(this).val());
            if (isNaN(qty) || qty < 1) qty = 1;
            var harga = parseInt($(this).data('harga'));
            var subtotal = harga * qty;
            $(this).closest('tr').find('.subtotal-produk').text(formatRupiah(subtotal));
            // Update total
            var total = 0;
            $('.subtotal-produk').each(function() {
                var sub = parseInt($(this).text().replace(/[^\d]/g, ''));
                if (!isNaN(sub)) total += sub;
            });
            $('h4:contains("Total")').text('Total: ' + formatRupiah(total));
        });
        // Update quantity ke backend saat blur
        $(document).on('change', '.qty-input', function() {
            var id = $(this).data('id');
            var qty = $(this).val();
            $.post('<?= site_url('cart/update') ?>', {
                product_id: id,
                qty: qty
            }, function(data) {
                if (!data.success) {
                    alert(data.message || 'Gagal update jumlah');
                    location.reload();
                }
            }, 'json');
        });
        // Remove item dengan SweetAlert2
        $(document).on('click', '.remove-btn', function() {
            var id = $(this).data('id');
            Swal.fire({
                title: 'Hapus Produk?',
                text: 'Apakah Anda yakin ingin menghapus produk ini dari keranjang?',
                icon: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#d33',
                cancelButtonColor: '#3085d6',
                confirmButtonText: 'Ya, hapus',
                cancelButtonText: 'Batal'
            }).then((result) => {
                if (result.isConfirmed) {
                    $.post('<?= site_url('cart/remove') ?>', {
                        product_id: id
                    }, function(data) {
                        if (data.success) {
                            location.reload();
                        } else {
                            Swal.fire('Gagal', 'Gagal menghapus produk', 'error');
                        }
                    }, 'json');
                }
            });
        });
    });
</script>
<?= $this->endSection() ?>