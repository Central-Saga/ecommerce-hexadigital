<nav class="admin-sidebar">
    <div class="admin-sidebar-header">
        <h3>HexaDigital</h3>
    </div>

    <ul class="nav flex-column">
        <li class="nav-item">
            <a class="nav-link active" href="/admin/dashboard">
                <div class="icon-wrapper">
                    <i class="bi bi-speedometer2"></i>
                </div>
                <span>Dashboard</span>
            </a>
        </li>

        <!-- Manajemen Pengguna -->
        <li class="nav-item">
            <a class="nav-link dropdown-toggle" data-bs-toggle="collapse" href="#userManagement">
                <div class="icon-wrapper">
                    <i class="bi bi-people"></i>
                </div>
                <span>Manajemen Pengguna</span>
            </a>
            <div class="collapse" id="userManagement">
                <ul class="nav flex-column">
                    <li class="nav-item">
                        <a class="nav-link" href="/godmode/user">
                            <div class="icon-wrapper">
                                <i class="bi bi-person"></i>
                            </div>
                            <span>Kelola User</span>
                        </a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="/godmode/pelanggan">
                            <div class="icon-wrapper">
                                <i class="bi bi-person-badge"></i>
                            </div>
                            <span>Kelola Customer</span>
                        </a>
                    </li>
                </ul>
            </div>
        </li>

        <!-- Manajemen Produk -->
        <li class="nav-item">
            <a class="nav-link dropdown-toggle" data-bs-toggle="collapse" href="#productManagement">
                <div class="icon-wrapper">
                    <i class="bi bi-box"></i>
                </div>
                <span>Manajemen Produk</span>
            </a>
            <div class="collapse" id="productManagement">
                <ul class="nav flex-column">
                    <li class="nav-item">
                        <a class="nav-link" href="/admin/products">
                            <div class="icon-wrapper">
                                <i class="bi bi-box-seam"></i>
                            </div>
                            <span>Kelola Produk</span>
                        </a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="/admin/categories">
                            <div class="icon-wrapper">
                                <i class="bi bi-tags"></i>
                            </div>
                            <span>Kelola Kategori</span>
                        </a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="/admin/inventory">
                            <div class="icon-wrapper">
                                <i class="bi bi-boxes"></i>
                            </div>
                            <span>Kelola Stok</span>
                        </a>
                    </li>
                </ul>
            </div>
        </li>

        <!-- Manajemen Pesanan -->
        <li class="nav-item">
            <a class="nav-link dropdown-toggle" data-bs-toggle="collapse" href="#orderManagement">
                <div class="icon-wrapper">
                    <i class="bi bi-cart"></i>
                </div>
                <span>Manajemen Pesanan</span>
            </a>
            <div class="collapse" id="orderManagement">
                <ul class="nav flex-column">
                    <li class="nav-item">
                        <a class="nav-link" href="/admin/orders">
                            <div class="icon-wrapper">
                                <i class="bi bi-cart-check"></i>
                            </div>
                            <span>Kelola Pesanan</span>
                        </a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="/admin/transactions">
                            <div class="icon-wrapper">
                                <i class="bi bi-credit-card"></i>
                            </div>
                            <span>Kelola Transaksi</span>
                        </a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="/admin/shipping">
                            <div class="icon-wrapper">
                                <i class="bi bi-truck"></i>
                            </div>
                            <span>Kelola Pengiriman</span>
                        </a>
                    </li>
                </ul>
            </div>
        </li>

        <li class="nav-item">
            <a class="nav-link" href="/admin/settings">
                <div class="icon-wrapper">
                    <i class="bi bi-gear"></i>
                </div>
                <span>Settings</span>
            </a>
        </li>
    </ul>
</nav>