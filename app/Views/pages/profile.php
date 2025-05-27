<?= $this->extend('layouts/wrapper') ?>

<?= $this->section('content') ?>
<div class="profile-page">
    <div class="profile-card card">
        <div class="profile-header">
            <div class="profile-avatar">
                <i class="bi bi-person-circle"></i>
            </div>
            <div class="profile-name"><?= esc($user->username ?? '-') ?></div>
            <div class="profile-email"><?= esc($user->email ?? '-') ?></div>
        </div>
        <div class="card-body">
            <h5 class="card-title mb-3">Informasi Akun</h5>
            <ul class="list-group list-group-flush mb-3">
                <li class="list-group-item"><strong>Username:</strong> <?= esc($user->username ?? '-') ?></li>
                <li class="list-group-item"><strong>Email:</strong> <?= esc($user->email ?? '-') ?></li>
            </ul>
            <?php if ($pelanggan): ?>
                <h6 class="mt-4 mb-2">Data Pelanggan</h6>
                <ul class="list-group list-group-flush">
                    <li class="list-group-item"><strong>Alamat:</strong> <?= esc($pelanggan['alamat'] ?? '-') ?></li>
                    <li class="list-group-item"><strong>No. Telepon:</strong> <?= esc($pelanggan['no_telepon'] ?? '-') ?></li>
                    <li class="list-group-item"><strong>Jenis Kelamin:</strong> <?= esc($pelanggan['jenis_kelamin'] ?? '-') ?></li>
                    <li class="list-group-item"><strong>Umur:</strong> <?= esc($pelanggan['umur'] ?? '-') ?></li>
                    <li class="list-group-item"><strong>Status:</strong> <span class="badge-status"><?= esc($pelanggan['status'] ?? '-') ?></span></li>
                </ul>
            <?php else: ?>
                <div class="alert alert-warning mt-3 mb-0">Data pelanggan belum tersedia.</div>
            <?php endif; ?>
        </div>
    </div>
</div>
<?= $this->endSection() ?>