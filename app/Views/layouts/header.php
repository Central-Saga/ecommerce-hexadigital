<header>
    <!-- Top Navigation -->
    <nav class="navbar navbar-expand-lg">
        <div class="container">
            <!-- Navbar Brand -->
            <a class="navbar-brand" href="<?= base_url() ?>">
                <img src="<?= base_url('assets/images/logo.png') ?>" alt="Hexadigital Store" style="height:42px; width:auto; object-fit:contain;" class="me-2">
                Hexadigital Store
            </a>

            <!-- Toggler Button for Mobile -->
            <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarMain" aria-controls="navbarMain" aria-expanded="false" aria-label="Toggle navigation">
                <span class="navbar-toggler-icon"></span>
            </button>

            <!-- Navbar Content -->
            <div class="collapse navbar-collapse" id="navbarMain">
                <!-- Search Form -->
                <form class="d-flex mx-auto my-2 my-lg-0" style="max-width: 500px; width: 100%;" action="<?= base_url('produk/search') ?>" method="GET">
                    <div class="input-group">
                        <input class="form-control" type="search" name="q" placeholder="Cari produk..." aria-label="Search" value="<?= esc($keyword ?? '') ?>">
                        <button class="btn btn-light" type="submit">
                            <i class="bi bi-search"></i>
                        </button>
                    </div>
                </form>

                <!-- Navigation Links -->
                <ul class="navbar-nav ms-auto mb-2 mb-lg-0 align-items-center">
                    <li class="nav-item">
                        <a class="nav-link" href="<?= base_url('produk') ?>">
                            <i class="bi bi-grid me-1"></i>
                            Semua Produk
                        </a>
                    </li>
                    <li class="nav-item dropdown">
                        <a class="nav-link dropdown-toggle" href="#" role="button" data-bs-toggle="dropdown">
                            <i class="bi bi-tags me-1"></i>
                            Kategori
                        </a>
                        <ul class="dropdown-menu">
                            <li>
                                <a class="dropdown-item" href="<?= base_url('kategori') ?>">
                                    <i class="bi bi-grid me-2"></i>
                                    Semua Kategori
                                </a>
                            </li>
                            <li>
                                <hr class="dropdown-divider">
                            </li>
                            <?php if (isset($categories) && !empty($categories)): ?>
                                <?php foreach ($categories as $category): ?>
                                    <li>
                                        <a class="dropdown-item" href="<?= base_url('kategori/detail/' . $category['id']) ?>">
                                            <i class="bi <?= $category['icon'] ?? 'bi-tag' ?> me-2"></i>
                                            <?= esc($category['nama_kategori']) ?>
                                        </a>
                                    </li>
                                <?php endforeach; ?>
                            <?php else: ?>
                                <li><a class="dropdown-item disabled" href="#">Tidak ada kategori</a></li>
                            <?php endif; ?>
                        </ul>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="<?= base_url('cart') ?>">
                            <i class="bi bi-cart3 me-1"></i>
                            Keranjang
                            <span class="badge bg-light text-dark ms-1" id="cart-count">0</span>
                        </a>
                    </li>
                    <li class="nav-item dropdown">
                        <a class="nav-link dropdown-toggle" href="#" role="button" data-bs-toggle="dropdown" aria-expanded="false">
                            <i class="bi bi-person me-1"></i>
                            Akun
                        </a>
                        <ul class="dropdown-menu dropdown-menu-end">
                            <?php if (auth()->loggedIn()): ?>
                                <li><a class="dropdown-item" href="<?= base_url('profile') ?>"><i class="bi bi-person-circle me-2"></i>Profil Saya</a></li>
                                <li>
                                    <form action="<?= base_url('orders') ?>" method="get" style="margin:0;">
                                        <button type="submit" class="dropdown-item" style="width:100%;text-align:left;">
                                            <i class="bi bi-bag me-2"></i>Pesanan Saya
                                        </button>
                                    </form>
                                </li>
                                <li>
                                    <hr class="dropdown-divider">
                                </li>
                                <li><a class="dropdown-item" href="<?= base_url('logout') ?>"><i class="bi bi-box-arrow-right me-2"></i>Logout</a></li>
                            <?php else: ?>
                                <li><a class="dropdown-item" href="<?= base_url('login') ?>"><i class="bi bi-box-arrow-in-right me-2"></i>Login</a></li>
                                <li><a class="dropdown-item" href="<?= base_url('register') ?>"><i class="bi bi-person-plus me-2"></i>Register</a></li>
                            <?php endif; ?>
                        </ul>
                    </li>
                </ul>
            </div>
        </div>
    </nav>
</header>
<script>
    function updateCartCount() {
        fetch('<?= base_url('cart/count') ?>')
            .then(res => res.json())
            .then(data => {
                document.getElementById('cart-count').textContent = data.count || 0;
            });
    }
    updateCartCount();
    window.addEventListener('storage', updateCartCount);

    // Tutup dropdown saat link di dalamnya diklik
    document.addEventListener('DOMContentLoaded', function() {
        document.querySelectorAll('.dropdown-menu a').forEach(function(link) {
            link.addEventListener('click', function() {
                var dropdown = link.closest('.dropdown-menu');
                if (dropdown) {
                    var parent = dropdown.parentElement;
                    if (parent && parent.classList.contains('dropdown')) {
                        var toggle = parent.querySelector('[data-bs-toggle="dropdown"]');
                        if (toggle) {
                            var dropdownInstance = bootstrap.Dropdown.getOrCreateInstance(toggle);
                            dropdownInstance.hide();
                        }
                    }
                }
            });
        });
    });
</script>