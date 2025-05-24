<?= $this->extend('layouts/admin') ?>
<?= $this->section('content') ?>
<div class="container-fluid">
    <h1 class="mb-4">Tambah Pembayaran</h1>
    <form action="<?= base_url('godmode/pembayaran/store') ?>" method="post" enctype="multipart/form-data">
        <?= csrf_field() ?>
        <div class="mb-3">
            <label for="pesanan_id" class="form-label">Pesanan</label>
            <select name="pesanan_id" id="pesanan_id" class="form-control" required>
                <option value="">-- Pilih Pesanan --</option>
                <?php foreach ($pemesanans as $pesanan): ?>
                    <option value="<?= $pesanan['id'] ?>">#<?= $pesanan['id'] ?> - <?= esc($pesanan['tanggal_pemesanan']) ?></option>
                <?php endforeach; ?>
            </select>
        </div>
        <div class="mb-3">
            <label for="nama_pengirim" class="form-label">Nama Pengirim</label>
            <input type="text" name="nama_pengirim" id="nama_pengirim" class="form-control">
        </div>
        <div class="mb-3">
            <label for="metode_pembayaran" class="form-label">Metode Pembayaran</label>
            <input type="text" name="metode_pembayaran" id="metode_pembayaran" class="form-control" required>
        </div>
        <div class="mb-3">
            <label for="bukti_pembayaran" class="form-label">Bukti Pembayaran (opsional)</label>
            <input type="text" name="bukti_pembayaran" id="bukti_pembayaran" class="form-control">
        </div>
        <div class="mb-3">
            <label for="total_harga" class="form-label">Total Harga</label>
            <input type="number" name="total_harga" id="total_harga" class="form-control" required>
        </div>
        <div class="mb-3">
            <label for="tanggal_pembayaran" class="form-label">Tanggal Pembayaran</label>
            <input type="date" name="tanggal_pembayaran" id="tanggal_pembayaran" class="form-control">
        </div>
        <div class="mb-3">
            <label for="status" class="form-label">Status</label>
            <select name="status" id="status" class="form-control" required>
                <option value="pending">Pending</option>
                <option value="diterima">Diterima</option>
                <option value="ditolak">Ditolak</option>
            </select>
        </div>
        <div class="mb-3">
            <label for="catatan" class="form-label">Catatan</label>
            <textarea name="catatan" id="catatan" class="form-control"></textarea>
        </div>
        <button type="submit" class="btn btn-primary">Simpan</button>
        <a href="<?= base_url('godmode/pembayaran') ?>" class="btn btn-secondary">Batal</a>
    </form>
</div>
<?= $this->endSection() ?>