<?php

namespace App\Controllers\Godmode;

use App\Controllers\BaseController;
use CodeIgniter\HTTP\ResponseInterface;
use App\Models\Produk;
use App\Models\Pemesanan;
use App\Models\Pelanggan;

class Dashboard extends BaseController
{
    public function getIndex()
    {
        try {
            $produkModel = new Produk();
            $pemesananModel = new Pemesanan();
            $pelangganModel = new Pelanggan();
            $userProvider = auth()->getProvider();

            // Statistik dasar
            $totalProducts = $produkModel->countAllResults();
            $totalOrders = $pemesananModel->countAllResults();
            $totalUsers = count($userProvider->findAll());

            // Total revenue - dengan query yang lebih aman
            $db = \Config\Database::connect();
            $revenueQuery = $db->table('pemesanan')
                ->selectSum('total_harga')
                ->where('status_pemesanan', 'selesai');
            $revenueResult = $revenueQuery->get()->getRowArray();
            $totalRevenue = $revenueResult['total_harga'] ?? 0;

            // Recent Orders - dengan query yang lebih sederhana
            $recentOrdersQuery = $db->table('pemesanan')
                ->select('
                    pemesanan.id,
                    pemesanan.pelanggan_id,
                    pemesanan.tanggal_pemesanan,
                    pemesanan.total_harga,
                    pemesanan.status_pemesanan,
                    pemesanan.catatan,
                    pemesanan.created_at,
                    pemesanan.updated_at,
                    COALESCE(users.username, "Unknown") as nama_pelanggan
                ')
                ->join('pelanggans', 'pelanggans.id = pemesanan.pelanggan_id', 'left')
                ->join('users', 'users.id = pelanggans.user_id', 'left')
                ->orderBy('pemesanan.created_at', 'DESC')
                ->limit(5);

            $recentOrders = $recentOrdersQuery->get()->getResultArray();

            // Pastikan semua data valid
            foreach ($recentOrders as &$order) {
                $order['total_harga'] = $order['total_harga'] ?? 0;
                $order['nama_pelanggan'] = $order['nama_pelanggan'] ?? 'Unknown';
                $order['status_pemesanan'] = $order['status_pemesanan'] ?? 'menunggu';
                $order['created_at'] = $order['created_at'] ?? date('Y-m-d H:i:s');
            }

            // Top Products
            $topProductsQuery = $db->table('detail_pemesanan')
                ->select('produk_id, SUM(jumlah) as total_sold')
                ->groupBy('produk_id')
                ->orderBy('total_sold', 'DESC')
                ->limit(5);

            $topProducts = $topProductsQuery->get()->getResultArray();

            // Ambil nama produk
            foreach ($topProducts as &$tp) {
                $produk = $produkModel->find($tp['produk_id']);
                $tp['nama'] = $produk['nama'] ?? 'Unknown Product';
            }

            return view('pages/godmode/dashboard', [
                'totalProducts' => $totalProducts,
                'totalOrders' => $totalOrders,
                'totalUsers' => $totalUsers,
                'totalRevenue' => $totalRevenue,
                'recentOrders' => $recentOrders,
                'topProducts' => $topProducts,
            ]);
        } catch (\Exception $e) {
            log_message('error', 'Dashboard error: ' . $e->getMessage());
            log_message('error', 'Stack trace: ' . $e->getTraceAsString());

            // Return view dengan data default jika terjadi error
            return view('pages/godmode/dashboard', [
                'totalProducts' => 0,
                'totalOrders' => 0,
                'totalUsers' => 0,
                'totalRevenue' => 0,
                'recentOrders' => [],
                'topProducts' => [],
            ]);
        }
    }
}
