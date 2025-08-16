<?php

namespace App\Controllers\Godmode;

use App\Controllers\BaseController;
use App\Models\Pemesanan as PemesananModel;
use CodeIgniter\HTTP\ResponseInterface;

class LaporanPenjualan extends BaseController
{
    protected $helpers = ['form'];
    protected $pemesananModel;

    public function __construct()
    {
        $this->pemesananModel = new PemesananModel();
    }

    public function getIndex()
    {
        // Ambil parameter tahun dan bulan dari request
        $tahun = $this->request->getGet('tahun') ?: date('Y');
        $bulan = $this->request->getGet('bulan') ?: date('n');

        // Validasi input
        $tahun = (int) $tahun;
        $bulan = (int) $bulan;

        if ($tahun < 2020 || $tahun > 2030) {
            $tahun = date('Y');
        }

        if ($bulan < 1 || $bulan > 12) {
            $bulan = date('n');
        }

        // Ambil data laporan penjualan
        $laporanPenjualan = $this->pemesananModel->getLaporanPenjualanPerBulan($tahun, $bulan)->findAll();

        // Ambil statistik total
        $statistikTotal = $this->pemesananModel->getTotalPenjualanPerBulan($tahun, $bulan);

        // Ambil statistik per bulan untuk chart
        $statistikPerBulan = $this->pemesananModel->getStatistikPenjualanPerBulan($tahun)->findAll();

        // Format data untuk view
        $formattedLaporan = [];
        foreach ($laporanPenjualan as $index => $pemesanan) {
            $formattedLaporan[] = [
                'id' => $pemesanan['id'],
                'nama_pelanggan' => $pemesanan['nama_pelanggan'],
                'email_pelanggan' => $pemesanan['email_pelanggan'],
                'tanggal_pemesanan' => $pemesanan['tanggal_pemesanan'],
                'total_harga' => $pemesanan['total_harga'],
                'status_pemesanan' => $pemesanan['status_pemesanan'],
                'catatan' => $pemesanan['catatan'],
                'created_at' => $pemesanan['created_at'],
                'updated_at' => $pemesanan['updated_at']
            ];
        }

        // Data untuk dropdown tahun dan bulan
        $tahunList = range(date('Y') - 5, date('Y') + 1);
        $bulanList = [
            1 => 'Januari',
            2 => 'Februari',
            3 => 'Maret',
            4 => 'April',
            5 => 'Mei',
            6 => 'Juni',
            7 => 'Juli',
            8 => 'Agustus',
            9 => 'September',
            10 => 'Oktober',
            11 => 'November',
            12 => 'Desember'
        ];

        return view('pages/godmode/laporan_penjualan/index', [
            'laporanPenjualan' => $formattedLaporan,
            'statistikTotal' => $statistikTotal,
            'statistikPerBulan' => $statistikPerBulan,
            'tahunList' => $tahunList,
            'bulanList' => $bulanList,
            'tahunSelected' => $tahun,
            'bulanSelected' => $bulan,
            'namaBulanSelected' => $bulanList[$bulan]
        ]);
    }

    /**
     * Export laporan ke PDF
     */
    public function getExportPdf()
    {
        $tahun = $this->request->getGet('tahun') ?: date('Y');
        $bulan = $this->request->getGet('bulan') ?: date('n');

        // Validasi input
        $tahun = (int) $tahun;
        $bulan = (int) $bulan;

        if ($tahun < 2020 || $tahun > 2030) {
            $tahun = date('Y');
        }

        if ($bulan < 1 || $bulan > 12) {
            $bulan = date('n');
        }

        // Ambil data laporan
        $laporanPenjualan = $this->pemesananModel->getLaporanPenjualanPerBulan($tahun, $bulan)->findAll();
        $statistikTotal = $this->pemesananModel->getTotalPenjualanPerBulan($tahun, $bulan);

        $bulanList = [
            1 => 'Januari',
            2 => 'Februari',
            3 => 'Maret',
            4 => 'April',
            5 => 'Mei',
            6 => 'Juni',
            7 => 'Juli',
            8 => 'Agustus',
            9 => 'September',
            10 => 'Oktober',
            11 => 'November',
            12 => 'Desember'
        ];

        // Set header untuk download PDF
        $this->response->setHeader('Content-Type', 'application/pdf');
        $this->response->setHeader('Content-Disposition', 'attachment; filename="laporan_penjualan_' . $bulanList[$bulan] . '_' . $tahun . '.pdf"');

        return view('pages/godmode/laporan_penjualan/export_pdf', [
            'laporanPenjualan' => $laporanPenjualan,
            'statistikTotal' => $statistikTotal,
            'tahun' => $tahun,
            'bulan' => $bulan,
            'namaBulan' => $bulanList[$bulan]
        ]);
    }

    /**
     * Export laporan ke Excel
     */
    public function getExportExcel()
    {
        $tahun = $this->request->getGet('tahun') ?: date('Y');
        $bulan = $this->request->getGet('bulan') ?: date('n');

        // Validasi input
        $tahun = (int) $tahun;
        $bulan = (int) $bulan;

        if ($tahun < 2020 || $tahun > 2030) {
            $tahun = date('Y');
        }

        if ($bulan < 1 || $bulan > 12) {
            $bulan = date('n');
        }

        // Ambil data laporan
        $laporanPenjualan = $this->pemesananModel->getLaporanPenjualanPerBulan($tahun, $bulan)->findAll();
        $statistikTotal = $this->pemesananModel->getTotalPenjualanPerBulan($tahun, $bulan);

        $bulanList = [
            1 => 'Januari',
            2 => 'Februari',
            3 => 'Maret',
            4 => 'April',
            5 => 'Mei',
            6 => 'Juni',
            7 => 'Juli',
            8 => 'Agustus',
            9 => 'September',
            10 => 'Oktober',
            11 => 'November',
            12 => 'Desember'
        ];

        // Set header untuk download Excel
        $this->response->setHeader('Content-Type', 'application/vnd.ms-excel');
        $this->response->setHeader('Content-Disposition', 'attachment; filename="laporan_penjualan_' . $bulanList[$bulan] . '_' . $tahun . '.xls"');

        return view('pages/godmode/laporan_penjualan/export_excel', [
            'laporanPenjualan' => $laporanPenjualan,
            'statistikTotal' => $statistikTotal,
            'tahun' => $tahun,
            'bulan' => $bulan,
            'namaBulan' => $bulanList[$bulan]
        ]);
    }
}
