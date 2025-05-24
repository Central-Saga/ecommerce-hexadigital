<?= $this->extend('layouts/wrapper') ?>
<?= $this->section('content') ?>
<div class="container py-5 orders-konfirmasi-page">
    <h2 class="mb-4">Konfirmasi Pembayaran</h2>
    <div class="card">
        <div class="card-body">
            <form action="<?= site_url('orders/konfirmasi-pembayaran/' . $order['id']) ?>" method="post" enctype="multipart/form-data">
                <?= csrf_field() ?>
                <div class="mb-3">
                    <label class="form-label">Pilih Metode Pembayaran (Bank Tujuan)</label>
                    <?php foreach ($rekeningToko as $bank => $rek): ?>
                        <div class="form-check">
                            <input class="form-check-input" type="radio" name="metode_pembayaran" id="bank_<?= $bank ?>" value="<?= $bank ?>" required>
                            <label class="form-check-label" for="bank_<?= $bank ?>">
                                <?= $bank ?>
                            </label>
                        </div>
                    <?php endforeach; ?>
                </div>
                <div class="mb-3" id="detail-rekening-tujuan" style="display:none;">
                    <div class="alert alert-info mb-0">
                        <strong>Nomor Rekening:</strong> <span id="nomor-rekening"></span><br>
                        <strong>Atas Nama:</strong> <span id="nama-rekening"></span>
                    </div>
                </div>
                <div class="mb-3">
                    <label for="nama_pengirim" class="form-label">Nama Pengirim</label>
                    <input type="text" name="nama_pengirim" id="nama_pengirim" class="form-control" required>
                </div>
                <div class="mb-3">
                    <label for="bukti_pembayaran" class="form-label">Upload Bukti Pembayaran</label>
                    <input type="file" name="bukti_pembayaran" id="bukti_pembayaran" class="form-control" required accept="image/*,application/pdf">
                </div>
                <div class="mb-3">
                    <label for="catatan" class="form-label">Catatan (Opsional)</label>
                    <textarea name="catatan" id="catatan" class="form-control"></textarea>
                </div>
                <button type="submit" class="btn btn-success">Kirim Konfirmasi</button>
                <a href="<?= site_url('orders') ?>" class="btn btn-secondary ms-2">Kembali</a>
            </form>
        </div>
    </div>
</div>
<script>
    const rekeningToko = <?= json_encode($rekeningToko) ?>;
    document.querySelectorAll('input[name="metode_pembayaran"]').forEach(function(radio) {
        radio.addEventListener('change', function() {
            const bank = this.value;
            const detail = rekeningToko[bank];
            if (detail) {
                document.getElementById('nomor-rekening').textContent = detail.nomor;
                document.getElementById('nama-rekening').textContent = detail.nama;
                document.getElementById('detail-rekening-tujuan').style.display = '';
            } else {
                document.getElementById('detail-rekening-tujuan').style.display = 'none';
            }
        });
    });
</script>
<?= $this->endSection() ?>