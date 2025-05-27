<?= $this->extend('layouts/wrapper') ?>

<?= $this->section('content') ?>
<div class="profile-page">
    <div class="profile-card card">
        <div class="profile-header">
            <div class="profile-avatar">
                <i class="bi bi-person-circle"></i>
            </div>
            <div class="profile-name">Edit Profil</div>
            <a href="/profile" class="btn btn-outline-secondary btn-edit-profile">Kembali</a>
        </div>
        <div class="card-body">
            <?php if (session('errors')): ?>
                <div class="alert alert-danger">
                    <?php foreach (session('errors') as $error): ?>
                        <div><?= esc($error) ?></div>
                    <?php endforeach; ?>
                </div>
            <?php endif; ?>
            <form action="/profile/edit" method="post" autocomplete="off">
                <?= csrf_field() ?>
                <div class="mb-3">
                    <label for="username" class="form-label">Username</label>
                    <input type="text" class="form-control" id="username" name="username" value="<?= old('username', $user->username) ?>" required>
                </div>
                <div class="mb-3">
                    <label for="email" class="form-label">Email</label>
                    <input type="email" class="form-control" id="email" name="email" value="<?= old('email', $user->email) ?>" required>
                </div>
                <div class="mb-3">
                    <label for="alamat" class="form-label">Alamat</label>
                    <input type="text" class="form-control" id="alamat" name="alamat" value="<?= old('alamat', $pelanggan['alamat'] ?? '') ?>">
                </div>
                <div class="mb-3">
                    <label for="no_telepon" class="form-label">No. Telepon</label>
                    <input type="text" class="form-control" id="no_telepon" name="no_telepon" value="<?= old('no_telepon', $pelanggan['no_telepon'] ?? '') ?>">
                </div>
                <div class="mb-3">
                    <label for="jenis_kelamin" class="form-label">Jenis Kelamin</label>
                    <select class="form-select" id="jenis_kelamin" name="jenis_kelamin">
                        <option value="" disabled <?= old('jenis_kelamin', $pelanggan['jenis_kelamin'] ?? '') == '' ? 'selected' : '' ?>>Pilih</option>
                        <option value="L" <?= old('jenis_kelamin', $pelanggan['jenis_kelamin'] ?? '') == 'L' ? 'selected' : '' ?>>Laki-laki</option>
                        <option value="P" <?= old('jenis_kelamin', $pelanggan['jenis_kelamin'] ?? '') == 'P' ? 'selected' : '' ?>>Perempuan</option>
                    </select>
                </div>
                <div class="mb-3">
                    <label for="umur" class="form-label">Umur</label>
                    <input type="number" class="form-control" id="umur" name="umur" value="<?= old('umur', $pelanggan['umur'] ?? '') ?>">
                </div>
                <button type="submit" class="btn btn-primary w-100">Simpan Perubahan</button>
            </form>
        </div>
    </div>
</div>
<?= $this->endSection() ?>