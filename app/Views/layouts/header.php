<header>
    <nav class="navbar navbar-expand-lg">
        <div class="container">
            <!-- Navbar Brand -->
            <a class="navbar-brand" href="/">Hexadigital Store</a>

            <!-- Toggler Button for Mobile -->
            <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarMain" aria-controls="navbarMain" aria-expanded="false" aria-label="Toggle navigation">
                <span class="navbar-toggler-icon"></span>
            </button>

            <!-- Navbar Content -->
            <div class="collapse navbar-collapse" id="navbarMain">
                <!-- Search Form -->
                <form class="d-flex mx-auto my-2 my-lg-0" style="max-width: 500px; width: 100%;">
                    <input class="form-control me-2" type="search" placeholder="Cari produk..." aria-label="Search">
                    <button class="btn btn-light" type="submit">
                        <i class="bi bi-search"></i>
                    </button>
                </form>

                <!-- Navigation Links -->
                <ul class="navbar-nav ms-auto mb-2 mb-lg-0">
                    <li class="nav-item">
                        <a class="nav-link" href="/cart">
                            <i class="bi bi-cart3"></i>Keranjang
                        </a>
                    </li>
                    <li class="nav-item dropdown">
                        <a class="nav-link dropdown-toggle" href="#" role="button" data-bs-toggle="dropdown" aria-expanded="false">
                            <i class="bi bi-person"></i>Akun
                        </a>
                        <ul class="dropdown-menu dropdown-menu-end">
                            <?php if (auth()->loggedIn()): ?>
                                <li><a class="dropdown-item" href="/logout">Logout</a></li>
                            <?php else: ?>
                                <li><a class="dropdown-item" href="/login">Login</a></li>
                                <li><a class="dropdown-item" href="/register">Register</a></li>
                            <?php endif; ?>
                        </ul>
                    </li>
                </ul>
            </div>
        </div>
    </nav>
</header>