<?php
function isActive($path)
{
    $currentPath = $_SERVER['REQUEST_URI'];
    return strpos($currentPath, $path) !== false ? 'active' : '';
}
?>
<nav class="admin-sidebar">
    <div class="admin-sidebar-header">
        <h3>HexaDigital</h3>
    </div>

    <ul class="nav flex-column">
        <li class="nav-item">
            <a class="nav-link <?= isActive('/godmode/dashboard') ?>" href="/godmode/dashboard">
                <div class="icon-wrapper">
                    <i class="bi bi-speedometer2"></i>
                </div>
                <span>Dashboard</span>
            </a>
        </li>

        <!-- Manajemen Pengguna -->
        <li class="nav-item">
            <a class="nav-link dropdown-toggle sidebar-dropdown <?= isActive('/godmode/user') || isActive('/godmode/pelanggan') ? 'active' : '' ?>" data-bs-toggle="collapse" href="#userManagement">
                <div class="icon-wrapper">
                    <i class="bi bi-people"></i>
                </div>
                <span>Manajemen Pengguna</span>
            </a>
            <div class="collapse <?= isActive('/godmode/user') || isActive('/godmode/pelanggan') ? 'show' : '' ?>" id="userManagement">
                <ul class="nav flex-column">
                    <li class="nav-item">
                        <a class="nav-link <?= isActive('/godmode/user') ?>" href="/godmode/user">
                            <div class="icon-wrapper">
                                <i class="bi bi-person"></i>
                            </div>
                            <span>Kelola User</span>
                        </a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link <?= isActive('/godmode/pelanggan') ?>" href="/godmode/pelanggan">
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
            <a class="nav-link dropdown-toggle sidebar-dropdown <?= isActive('/godmode/produk') || isActive('/godmode/kategori') || isActive('/godmode/inventory') ? 'active' : '' ?>" data-bs-toggle="collapse" href="#productManagement">
                <div class="icon-wrapper">
                    <i class="bi bi-box"></i>
                </div>
                <span>Manajemen Produk</span>
            </a>
            <div class="collapse <?= isActive('/godmode/produk') || isActive('/godmode/kategori') || isActive('/godmode/inventory') ? 'show' : '' ?>" id="productManagement">
                <ul class="nav flex-column">
                    <li class="nav-item">
                        <a class="nav-link <?= isActive('/godmode/kategori') ?>" href="/godmode/kategori">
                            <div class="icon-wrapper">
                                <i class="bi bi-tags"></i>
                            </div>
                            <span>Kelola Kategori</span>
                        </a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link <?= isActive('/godmode/produk') ?>" href="/godmode/produk">
                            <div class="icon-wrapper">
                                <i class="bi bi-box-seam"></i>
                            </div>
                            <span>Kelola Produk</span>
                        </a>
                    </li>
                </ul>
            </div>
        </li>

        <!-- Manajemen Pesanan -->
        <li class="nav-item">
            <a class="nav-link dropdown-toggle sidebar-dropdown <?= isActive('/godmode/pemesanan') || isActive('/godmode/transactions') || isActive('/godmode/shipping') ? 'active' : '' ?>" data-bs-toggle="collapse" href="#orderManagement">
                <div class="icon-wrapper">
                    <i class="bi bi-cart"></i>
                </div>
                <span>Manajemen Pesanan</span>
            </a>
            <div class="collapse <?= isActive('/godmode/pemesanan') || isActive('/godmode/transactions') || isActive('/godmode/shipping') ? 'show' : '' ?>" id="orderManagement">
                <ul class="nav flex-column">
                    <li class="nav-item">
                        <a class="nav-link <?= isActive('/godmode/pemesanan') ?>" href="/godmode/pemesanan">
                            <div class="icon-wrapper">
                                <i class="bi bi-cart-check"></i>
                            </div>
                            <span>Kelola Pesanan</span>
                        </a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link <?= isActive('/godmode/transactions') ?>" href="/godmode/transactions">
                            <div class="icon-wrapper">
                                <i class="bi bi-credit-card"></i>
                            </div>
                            <span>Kelola Transaksi</span>
                        </a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link <?= isActive('/godmode/shipping') ?>" href="/godmode/shipping">
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
            <a class="nav-link <?= isActive('/godmode/settings') ?>" href="/godmode/settings">
                <div class="icon-wrapper">
                    <i class="bi bi-gear"></i>
                </div>
                <span>Settings</span>
            </a>
        </li>
    </ul>
</nav>