<?= $this->extend('layouts/auth') ?>

<?= $this->section('title') ?>Register<?= $this->endSection() ?>

<?= $this->section('content') ?>
<div class="auth-container">
    <div class="auth-header">
        <h2>Register</h2>
        <p>Buat akun baru untuk mulai berbelanja</p>
    </div>

    <form action="<?= route_to('register') ?>" method="post">
        <?= csrf_field() ?>

        <?php if (session('error')) : ?>
            <div class="alert alert-danger" role="alert">
                <?= session('error') ?>
            </div>
        <?php endif ?>

        <div class="mb-3">
            <label for="email" class="form-label">Email</label>
            <input type="email" class="form-control" id="email" name="email" placeholder="Masukkan email Anda" required>
        </div>

        <div class="mb-3">
            <label for="username" class="form-label">Username</label>
            <input type="text" class="form-control" id="username" name="username" placeholder="Pilih username Anda" required>
        </div>

        <div class="mb-3">
            <label for="password" class="form-label">Password</label>
            <input type="password" class="form-control" id="password" name="password" placeholder="Buat password Anda" required>
            <div class="password-requirements">
                Password minimal 8 karakter dan mengandung huruf besar, kecil, dan angka
            </div>
        </div>

        <div class="mb-3">
            <label for="password_confirm" class="form-label">Konfirmasi Password</label>
            <input type="password" class="form-control" id="password_confirm" name="password_confirm" placeholder="Masukkan ulang password Anda" required>
        </div>

        <button type="submit" class="btn btn-auth">
            Daftar
        </button>

        <div class="auth-footer">
            <span>Sudah punya akun?</span>
            <a href="<?= route_to('login') ?>" class="auth-link">Masuk</a>
        </div>
    </form>
</div>
<?= $this->endSection() ?>