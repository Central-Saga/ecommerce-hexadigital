<?= $this->extend('layouts/admin') ?>

<?= $this->section('content') ?>
<div class="laporan-penjualan">
    <!-- Header -->
    <div class="card mb-4">
        <div class="card-header">
            <div class="d-flex justify-content-between align-items-center">
                <div class="d-flex align-items-center">
                    <i class="bi bi-graph-up me-2 text-primary fs-4"></i>
                    <h5 class="mb-0">Laporan Penjualan</h5>
                </div>
                <div class="d-flex gap-2">
                    <a href="<?= base_url('godmode/laporan-penjualan/export-excel?tahun=' . $tahunSelected . '&bulan=' . $bulanSelected) ?>" class="btn btn-success btn-sm">
                        <i class="bi bi-file-earmark-excel me-1"></i> Export Excel
                    </a>
                    <a href="<?= base_url('godmode/laporan-penjualan/export-pdf?tahun=' . $tahunSelected . '&bulan=' . $bulanSelected) ?>" class="btn btn-danger btn-sm">
                        <i class="bi bi-file-earmark-pdf me-1"></i> Export PDF
                    </a>
                </div>
            </div>
        </div>
        <div class="card-body">
            <!-- Filter Form -->
            <form method="GET" action="<?= base_url('godmode/laporan-penjualan') ?>" class="row g-3">
                <div class="col-md-3">
                    <label for="tahun" class="form-label">Tahun</label>
                    <select name="tahun" id="tahun" class="form-select">
                        <?php foreach ($tahunList as $tahun): ?>
                            <option value="<?= $tahun ?>" <?= $tahun == $tahunSelected ? 'selected' : '' ?>>
                                <?= $tahun ?>
                            </option>
                        <?php endforeach; ?>
                    </select>
                </div>
                <div class="col-md-3">
                    <label for="bulan" class="form-label">Bulan</label>
                    <select name="bulan" id="bulan" class="form-select">
                        <?php foreach ($bulanList as $key => $nama): ?>
                            <option value="<?= $key ?>" <?= $key == $bulanSelected ? 'selected' : '' ?>>
                                <?= $nama ?>
                            </option>
                        <?php endforeach; ?>
                    </select>
                </div>
                <div class="col-md-3">
                    <label class="form-label">&nbsp;</label>
                    <button type="submit" class="btn btn-primary d-block">
                        <i class="bi bi-search me-1"></i> Tampilkan
                    </button>
                </div>
            </form>
        </div>
    </div>

    <!-- Statistik Cards -->
    <div class="row mb-4">
        <div class="col-md-4">
            <div class="card bg-primary text-white">
                <div class="card-body">
                    <div class="d-flex justify-content-between">
                        <div>
                            <h6 class="card-title">Total Pesanan</h6>
                            <h3 class="mb-0"><?= number_format($statistikTotal['total_pesanan'] ?? 0) ?></h3>
                        </div>
                        <div class="align-self-center">
                            <i class="bi bi-cart-check fs-1"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-md-4">
            <div class="card bg-success text-white">
                <div class="card-body">
                    <div class="d-flex justify-content-between">
                        <div>
                            <h6 class="card-title">Total Penjualan</h6>
                            <h3 class="mb-0">Rp <?= number_format($statistikTotal['total_penjualan'] ?? 0, 0, ',', '.') ?></h3>
                        </div>
                        <div class="align-self-center">
                            <i class="bi bi-currency-dollar fs-1"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-md-4">
            <div class="card bg-info text-white">
                <div class="card-body">
                    <div class="d-flex justify-content-between">
                        <div>
                            <h6 class="card-title">Rata-rata Penjualan</h6>
                            <h3 class="mb-0">Rp <?= number_format($statistikTotal['rata_rata_penjualan'] ?? 0, 0, ',', '.') ?></h3>
                        </div>
                        <div class="align-self-center">
                            <i class="bi bi-graph-up fs-1"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Chart Statistik Per Bulan -->
    <div class="card mb-4">
        <div class="card-header">
            <h6 class="mb-0">
                <i class="bi bi-bar-chart me-2"></i>
                Statistik Penjualan Tahun <?= $tahunSelected ?>
            </h6>
        </div>
        <div class="card-body">
            <canvas id="chartPenjualan" width="400" height="200"></canvas>
        </div>
    </div>

    <!-- Tabel Laporan Penjualan -->
    <div class="card">
        <div class="card-header">
            <h6 class="mb-0">
                <i class="bi bi-table me-2"></i>
                Detail Laporan Penjualan <?= $namaBulanSelected ?> <?= $tahunSelected ?>
            </h6>
        </div>
        <div class="card-body">
            <?php if (empty($laporanPenjualan)) : ?>
                <div class="text-center py-5">
                    <i class="bi bi-inbox fs-1 text-muted"></i>
                    <p class="mt-3 text-muted">Tidak ada data penjualan untuk periode yang dipilih</p>
                </div>
            <?php else : ?>
                <div class="table-responsive">
                    <table class="table table-hover align-middle">
                        <thead>
                            <tr>
                                <th class="text-center" style="width: 60px">No</th>
                                <th>Pelanggan</th>
                                <th>Email</th>
                                <th>Tanggal Pemesanan</th>
                                <th>Total Harga</th>
                                <th>Status</th>
                                <th>Catatan</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php foreach ($laporanPenjualan as $index => $pemesanan): ?>
                                <tr>
                                    <td class="text-center"><?= $index + 1 ?></td>
                                    <td>
                                        <div class="fw-semibold"><?= esc($pemesanan['nama_pelanggan']) ?></div>
                                    </td>
                                    <td><?= esc($pemesanan['email_pelanggan']) ?></td>
                                    <td><?= date('d/m/Y H:i', strtotime($pemesanan['tanggal_pemesanan'])) ?></td>
                                    <td>
                                        <span class="fw-bold text-success">
                                            Rp <?= number_format($pemesanan['total_harga'], 0, ',', '.') ?>
                                        </span>
                                    </td>
                                    <td>
                                        <?php
                                        $statusClass = [
                                            'menunggu' => 'bg-warning',
                                            'diproses' => 'bg-info',
                                            'selesai' => 'bg-success',
                                            'dibatalkan' => 'bg-danger'
                                        ];
                                        $statusText = [
                                            'menunggu' => 'Menunggu',
                                            'diproses' => 'Diproses',
                                            'selesai' => 'Selesai',
                                            'dibatalkan' => 'Dibatalkan'
                                        ];
                                        ?>
                                        <span class="badge <?= $statusClass[$pemesanan['status_pemesanan']] ?? 'bg-secondary' ?>">
                                            <?= $statusText[$pemesanan['status_pemesanan']] ?? $pemesanan['status_pemesanan'] ?>
                                        </span>
                                    </td>
                                    <td>
                                        <?php if (!empty($pemesanan['catatan'])): ?>
                                            <span class="text-muted small"><?= esc($pemesanan['catatan']) ?></span>
                                        <?php else: ?>
                                            <span class="text-muted fst-italic">-</span>
                                        <?php endif; ?>
                                    </td>
                                </tr>
                            <?php endforeach; ?>
                        </tbody>
                    </table>
                </div>
            <?php endif; ?>
        </div>
    </div>
</div>

<!-- Chart.js -->
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>

<script>
    document.addEventListener('DOMContentLoaded', function() {
        // Data untuk chart
        const chartData = <?= json_encode($statistikPerBulan) ?>;

        // Siapkan data untuk chart
        const labels = [];
        const dataPenjualan = [];
        const dataPesanan = [];

        // Buat array untuk 12 bulan
        for (let i = 1; i <= 12; i++) {
            const bulanNames = ['Jan', 'Feb', 'Mar', 'Apr', 'Mei', 'Jun', 'Jul', 'Ags', 'Sep', 'Okt', 'Nov', 'Des'];
            labels.push(bulanNames[i - 1]);

            // Cari data untuk bulan ini
            const bulanData = chartData.find(item => item.bulan == i);
            if (bulanData) {
                dataPenjualan.push(parseFloat(bulanData.total_penjualan));
                dataPesanan.push(parseInt(bulanData.total_pesanan));
            } else {
                dataPenjualan.push(0);
                dataPesanan.push(0);
            }
        }

        // Buat chart
        const ctx = document.getElementById('chartPenjualan').getContext('2d');
        new Chart(ctx, {
            type: 'line',
            data: {
                labels: labels,
                datasets: [{
                        label: 'Total Penjualan (Rp)',
                        data: dataPenjualan,
                        borderColor: 'rgb(75, 192, 192)',
                        backgroundColor: 'rgba(75, 192, 192, 0.2)',
                        yAxisID: 'y',
                        tension: 0.1
                    },
                    {
                        label: 'Total Pesanan',
                        data: dataPesanan,
                        borderColor: 'rgb(255, 99, 132)',
                        backgroundColor: 'rgba(255, 99, 132, 0.2)',
                        yAxisID: 'y1',
                        tension: 0.1
                    }
                ]
            },
            options: {
                responsive: true,
                interaction: {
                    mode: 'index',
                    intersect: false,
                },
                scales: {
                    x: {
                        display: true,
                        title: {
                            display: true,
                            text: 'Bulan'
                        }
                    },
                    y: {
                        type: 'linear',
                        display: true,
                        position: 'left',
                        title: {
                            display: true,
                            text: 'Total Penjualan (Rp)'
                        }
                    },
                    y1: {
                        type: 'linear',
                        display: true,
                        position: 'right',
                        title: {
                            display: true,
                            text: 'Total Pesanan'
                        },
                        grid: {
                            drawOnChartArea: false,
                        },
                    }
                },
                plugins: {
                    title: {
                        display: true,
                        text: 'Grafik Penjualan Tahun <?= $tahunSelected ?>'
                    }
                }
            }
        });
    });
</script>
<?= $this->endSection() ?>