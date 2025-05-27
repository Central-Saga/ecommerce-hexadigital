<?= $this->extend('layouts/auth') ?>

<?= $this->section('title') ?>Login<?= $this->endSection() ?>

<?= $this->section('content') ?>
<div class="auth-container">
    <div class="auth-header">
        <h2>Selamat Datang</h2>
        <p>Silakan masuk ke akun Anda</p>
    </div>

    <form action="<?= route_to('login') ?>" method="post">
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
            <label for="password" class="form-label">Password</label>
            <input type="password" class="form-control" id="password" name="password" placeholder="Masukkan password Anda" required>
        </div>

        <div class="form-check">
            <input type="checkbox" class="form-check-input" id="remember" name="remember">
            <label class="form-check-label" for="remember">Ingat saya</label>
        </div>

        <button type="submit" class="btn btn-auth">
            Masuk
        </button>

        <div class="auth-footer">
            <span>Belum punya akun?</span>
            <a href="<?= route_to('register') ?>" class="auth-link">Daftar</a>
        </div>
    </form>
</div>
<?= $this->endSection() ?>