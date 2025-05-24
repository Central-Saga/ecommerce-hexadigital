<?= $this->extend('layouts/admin') ?>
<?= $this->section('content') ?>
<div class="container-fluid">
    <h1 class="mb-4">Detail Pembayaran</h1>
    <div class="card mb-3">
        <div class="card-body">
            <dl class="row">
                <dt class="col-sm-3">ID</dt>
                <dd class="col-sm-9">#<?= esc($pembayaran['id']) ?></dd>
                <dt class="col-sm-3">Pesanan</dt>
                <dd class="col-sm-9">#<?= esc($pembayaran['pesanan_id']) ?></dd>
                <dt class="col-sm-3">Nama Pengirim</dt>
                <dd class="col-sm-9"><?= esc($pembayaran['nama_pengirim']) ?></dd>
                <dt class="col-sm-3">Metode Pembayaran</dt>
                <dd class="col-sm-9"><?= esc($pembayaran['metode_pembayaran']) ?></dd>
                <dt class="col-sm-3">Bukti Pembayaran</dt>
                <dd class="col-sm-9"><?= esc($pembayaran['bukti_pembayaran']) ?></dd>
                <dt class="col-sm-3">Total Harga</dt>
                <dd class="col-sm-9">Rp<?= number_format($pembayaran['total_harga'], 0, ',', '.') ?></dd>
                <dt class="col-sm-3">Tanggal Pembayaran</dt>
                <dd class="col-sm-9"><?= esc($pembayaran['tanggal_pembayaran']) ?></dd>
                <dt class="col-sm-3">Status</dt>
                <dd class="col-sm-9"><span class="badge bg-<?= $pembayaran['status'] == 'diterima' ? 'success' : ($pembayaran['status'] == 'ditolak' ? 'danger' : 'warning') ?>"><?= esc($pembayaran['status']) ?></span></dd>
                <dt class="col-sm-3">Catatan</dt>
                <dd class="col-sm-9"><?= esc($pembayaran['catatan']) ?></dd>
            </dl>
        </div>
    </div>
    <a href="<?= base_url('godmode/pembayaran') ?>" class="btn btn-secondary">Kembali</a>
</div>
<?= $this->endSection() ?>