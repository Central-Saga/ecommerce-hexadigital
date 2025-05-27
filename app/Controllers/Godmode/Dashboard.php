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
        $produkModel = new Produk();
        $pemesananModel = new Pemesanan();
        $pelangganModel = new Pelanggan();
        $userProvider = auth()->getProvider();

        // Statistik
        $totalProducts = $produkModel->countAllResults();
        $totalOrders = $pemesananModel->countAllResults();
        $totalUsers = count($userProvider->findAll());
        $totalRevenue = $pemesananModel->selectSum('total_harga')->where('status_pemesanan', 'selesai')->first()['total_harga'] ?? 0;

        // Recent Orders (5 terakhir)
        $recentOrders = $pemesananModel->withPelanggan()->orderBy('pemesanan.created_at', 'DESC')->findAll(5);

        // Top Products (berdasarkan jumlah order di detail_pemesanan)
        $db = \Config\Database::connect();
        $topProducts = $db->table('detail_pemesanan')
            ->select('produk_id, SUM(jumlah) as total_sold')
            ->groupBy('produk_id')
            ->orderBy('total_sold', 'DESC')
            ->limit(5)
            ->get()->getResultArray();
        // Ambil nama produk
        foreach ($topProducts as &$tp) {
            $produk = $produkModel->find($tp['produk_id']);
            $tp['nama'] = $produk['nama'] ?? '-';
        }

        return view('pages/godmode/dashboard', [
            'totalProducts' => $totalProducts,
            'totalOrders' => $totalOrders,
            'totalUsers' => $totalUsers,
            'totalRevenue' => $totalRevenue,
            'recentOrders' => $recentOrders,
            'topProducts' => $topProducts,
        ]);
    }
}
