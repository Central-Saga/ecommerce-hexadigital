<?= $this->extend('layouts/admin') ?>
<?= $this->section('content') ?>
<div class="container-fluid">
    <h1 class="mb-4">Edit Pembayaran</h1>
    <form action="<?= base_url('godmode/pembayaran/update/' . $pembayaran['id']) ?>" method="post">
        <?= csrf_field() ?>
        <input type="hidden" name="_method" value="PUT">
        <div class="mb-3">
            <label for="status" class="form-label">Status</label>
            <select name="status" id="status" class="form-control" required>
                <option value="pending" <?= $pembayaran['status'] == 'pending' ? 'selected' : '' ?>>Pending</option>
                <option value="diterima" <?= $pembayaran['status'] == 'diterima' ? 'selected' : '' ?>>Diterima</option>
                <option value="ditolak" <?= $pembayaran['status'] == 'ditolak' ? 'selected' : '' ?>>Ditolak</option>
            </select>
        </div>
        <div class="mb-3">
            <label for="catatan" class="form-label">Catatan</label>
            <textarea name="catatan" id="catatan" class="form-control"><?= esc($pembayaran['catatan']) ?></textarea>
        </div>
        <button type="submit" class="btn btn-primary">Update</button>
        <a href="<?= base_url('godmode/pembayaran') ?>" class="btn btn-secondary">Batal</a>
    </form>
</div>
<?= $this->endSection() ?>