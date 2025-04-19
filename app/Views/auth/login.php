<?= $this->extend('layouts/login') ?>

<?= $this->section('title') ?>Login<?= $this->endSection() ?>

<?= $this->section('content') ?>
<div class="login-container">
    <div class="login-header">
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
            <input type="email" class="form-control" id="email" name="email" required>
        </div>

        <div class="mb-3">
            <label for="password" class="form-label">Password</label>
            <input type="password" class="form-control" id="password" name="password" required>
        </div>

        <div class="mb-3 form-check">
            <input type="checkbox" class="form-check-input" id="remember" name="remember">
            <label class="form-check-label" for="remember">Ingat saya</label>
        </div>

        <div class="d-grid gap-2">
            <button type="submit" class="btn btn-login">
                <i class="fas fa-sign-in-alt me-2"></i>Masuk
            </button>
        </div>

        <div class="text-center mt-3">
            <a href="<?= route_to('magic-link') ?>" class="forgot-password">
                Lupa password?
            </a>
        </div>
    </form>
</div>
<?= $this->endSection() ?>