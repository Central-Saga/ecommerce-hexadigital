<?= $this->extend('layouts/admin') ?>

<?= $this->section('content') ?>
<div class="dashboard-stats-row row gx-4 gy-4 mb-5">
    <div class="col-6 col-md-3">
        <div class="card dashboard-stat-card bg-gradient-primary text-white">
            <div class="card-body d-flex flex-column justify-content-between p-3">
                <div>
                    <h6 class="mb-1" style="font-size:0.92rem;">Total Produk</h6>
                    <h2 class="fw-bold mb-0" style="font-size:1.3rem;line-height:1.1;"><?= esc($totalProducts) ?></h2>
                </div>
                <i class="bi bi-box fs-4 opacity-75 align-self-end"></i>
            </div>
        </div>
    </div>
    <div class="col-6 col-md-3">
        <div class="card dashboard-stat-card bg-gradient-success text-white">
            <div class="card-body d-flex flex-column justify-content-between p-3">
                <div>
                    <h6 class="mb-1" style="font-size:0.92rem;">Total Order</h6>
                    <h2 class="fw-bold mb-0" style="font-size:1.3rem;line-height:1.1;"><?= esc($totalOrders) ?></h2>
                </div>
                <i class="bi bi-cart fs-4 opacity-75 align-self-end"></i>
            </div>
        </div>
    </div>
    <div class="col-6 col-md-3">
        <div class="card dashboard-stat-card bg-gradient-warning text-white">
            <div class="card-body d-flex flex-column justify-content-between p-3">
                <div>
                    <h6 class="mb-1" style="font-size:0.92rem;">Total User</h6>
                    <h2 class="fw-bold mb-0" style="font-size:1.3rem;line-height:1.1;"><?= esc($totalUsers) ?></h2>
                </div>
                <i class="bi bi-people fs-4 opacity-75 align-self-end"></i>
            </div>
        </div>
    </div>
    <div class="col-6 col-md-3">
        <div class="card dashboard-stat-card bg-gradient-info text-white">
            <div class="card-body d-flex flex-column justify-content-between p-3">
                <div>
                    <h6 class="mb-1" style="font-size:0.92rem;">Total Revenue</h6>
                    <h2 class="fw-bold mb-0" style="font-size:1.3rem;line-height:1.1;">Rp<?= number_format($totalRevenue, 0, ',', '.') ?></h2>
                </div>
                <i class="bi bi-currency-dollar fs-4 opacity-75 align-self-end"></i>
            </div>
        </div>
    </div>
</div>

<div class="row gx-5 gy-4 align-items-stretch dashboard-main-row">
    <div class="col-12 col-lg-8 mb-4 mb-lg-0">
        <div class="card shadow border-0 h-100 dashboard-main-card">
            <div class="card-header bg-white border-bottom-0 pb-3">
                <h5 class="card-title mb-0">Order Terbaru</h5>
            </div>
            <div class="card-body p-0">
                <div class="table-responsive">
                    <table class="table table-dashboard align-middle mb-0">
                        <thead>
                            <tr>
                                <th>ID</th>
                                <th>Pelanggan</th>
                                <th>Status</th>
                                <th>Total</th>
                                <th>Tanggal</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php if (!empty($recentOrders)): foreach ($recentOrders as $order): ?>
                                    <tr>
                                        <td>#<?= esc($order['id']) ?></td>
                                        <td><?= esc($order['nama_pelanggan'] ?? '-') ?></td>
                                        <td>
                                            <span class="badge badge-status bg-<?= $order['status_pemesanan'] === 'selesai' ? 'success' : ($order['status_pemesanan'] === 'diproses' ? 'warning' : 'secondary') ?>">
                                                <?php if ($order['status_pemesanan'] === 'selesai'): ?>
                                                    <i class="bi bi-check-circle"></i>
                                                <?php elseif ($order['status_pemesanan'] === 'diproses'): ?>
                                                    <i class="bi bi-hourglass-split"></i>
                                                <?php else: ?>
                                                    <i class="bi bi-clock"></i>
                                                <?php endif; ?>
                                                <?= esc(ucfirst($order['status_pemesanan'])) ?>
                                            </span>
                                        </td>
                                        <td>Rp<?= number_format($order['total_harga'] ?? 0, 0, ',', '.') ?></td>
                                        <td><?= date('d M Y', strtotime($order['created_at'])) ?></td>
                                    </tr>
                                <?php endforeach;
                            else: ?>
                                <tr>
                                    <td colspan="5" class="text-center text-muted">Belum ada order <span style="font-size:1.2em">🛒</span></td>
                                </tr>
                            <?php endif; ?>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
    <div class="col-12 col-lg-4">
        <div class="card shadow border-0 h-100 dashboard-main-card">
            <div class="card-header bg-white border-bottom-0 pb-3">
                <h5 class="card-title mb-0">Top Produk</h5>
            </div>
            <div class="card-body p-0">
                <ul class="list-group list-group-flush top-product-list">
                    <?php if (!empty($topProducts)): foreach ($topProducts as $tp): ?>
                            <li class="list-group-item">
                                <span class="product-icon"><i class="bi bi-box"></i></span>
                                <span style="font-weight:700; letter-spacing:0.01em; color:#223;"> <?= esc($tp['nama']) ?> </span>
                                <span class="badge bg-primary ms-auto"> <?= esc($tp['total_sold']) ?> terjual </span>
                            </li>
                        <?php endforeach;
                    else: ?>
                        <li class="list-group-item text-center text-muted">Belum ada data <span style="font-size:1.2em">📦</span></li>
                    <?php endif; ?>
                </ul>
            </div>
        </div>
    </div>
</div>
<?= $this->endSection() ?>