<?php

namespace App\Controllers\Godmode;

use App\Controllers\BaseController;
use App\Models\Pemesanan as PemesananModel;
use App\Models\Pelanggan as PelangganModel;
use App\Models\Pembayaran;
use CodeIgniter\HTTP\ResponseInterface;

class Pemesanan extends BaseController
{
    protected $helpers = ['form'];
    protected $pemesananModel;
    protected $pelangganModel;
    protected $pembayaranModel;

    public function __construct()
    {
        $this->pemesananModel = new PemesananModel();
        $this->pelangganModel = new PelangganModel();
        $this->pembayaranModel = new Pembayaran();
    }

    public function getIndex()
    {
        // Aktifkan debugging sementara untuk melihat data
        error_reporting(E_ALL);
        ini_set('display_errors', 1);

        // Tampilkan konten tabel pemesanan untuk debugging
        try {
            $query = $this->pemesananModel->db->query("DESCRIBE pemesanan");
            $tableStructure = $query->getResultArray();
            log_message('debug', 'Struktur tabel pemesanan: ' . json_encode($tableStructure));
        } catch (\Exception $e) {
            log_message('error', 'Gagal mengambil struktur tabel: ' . $e->getMessage());
        }

        // Direct query untuk memastikan data diambil
        try {
            $query = $this->pemesananModel->db->query("SELECT * FROM pemesanan");
            $rawPemesanans = $query->getResultArray();
            log_message('debug', 'Raw query pemesanans: ' . json_encode($rawPemesanans));
        } catch (\Exception $e) {
            log_message('error', 'Gagal mengambil data pemesanan: ' . $e->getMessage());
            $rawPemesanans = [];
        }

        // Ambil semua pemesanan dari database dengan model
        $pemesanans = $this->pemesananModel->findAll();

        // Debug: Tambahkan log
        log_message('debug', 'Pemesanans from model: ' . json_encode($pemesanans));

        // Format data pemesanan untuk view
        $formattedPemesanans = [];

        // Jika data dari model kosong, gunakan data dari query langsung
        $dataToProcess = !empty($pemesanans) ? $pemesanans : $rawPemesanans;

        foreach ($dataToProcess as $pemesanan) {
            try {
                // Ambil data pelanggan - tangani jika gagal
                $pelanggan = null;
                if (!empty($pemesanan['pelanggan_id'])) {
                    $pelanggan = $this->pelangganModel->withUser()->find($pemesanan['pelanggan_id']);
                }

                $formattedPemesanans[] = [
                    'id' => $pemesanan['id'],
                    'pelanggan_nama' => $pelanggan ? ($pelanggan['username'] ?? 'Tidak ada nama') : 'Pelanggan tidak ditemukan',
                    'email' => $pelanggan ? ($pelanggan['email'] ?? '-') : '-',
                    'tanggal_pemesanan' => $pemesanan['tanggal_pemesanan'],
                    'total_harga' => $pemesanan['total_harga'],
                    'status_pemesanan' => $pemesanan['status_pemesanan'],
                    'created_at' => $pemesanan['created_at'] ?? date('Y-m-d H:i:s'),
                    'updated_at' => $pemesanan['updated_at'] ?? date('Y-m-d H:i:s')
                ];
            } catch (\Exception $e) {
                log_message('error', 'Error saat memproses pemesanan: ' . $e->getMessage());
                // Tambahkan data minimal untuk mencegah error pada view
                $formattedPemesanans[] = [
                    'id' => $pemesanan['id'] ?? '?',
                    'pelanggan_nama' => 'Error: Pelanggan tidak ditemukan',
                    'email' => '-',
                    'tanggal_pemesanan' => $pemesanan['tanggal_pemesanan'] ?? date('Y-m-d'),
                    'total_harga' => $pemesanan['total_harga'] ?? 0,
                    'status_pemesanan' => $pemesanan['status_pemesanan'] ?? 'menunggu',
                    'created_at' => $pemesanan['created_at'] ?? date('Y-m-d H:i:s'),
                    'updated_at' => $pemesanan['updated_at'] ?? date('Y-m-d H:i:s')
                ];
            }
        }

        log_message('debug', 'Formatted data: ' . json_encode($formattedPemesanans));

        return view('pages/godmode/pemesanan/index', [
            'pemesanans' => $formattedPemesanans
        ]);
    }

    public function getCreate()
    {
        $pelanggans = $this->pelangganModel->withUser()->findAll();

        return view('pages/godmode/pemesanan/create', [
            'pelanggans' => $pelanggans
        ]);
    }

    public function postStore()
    {
        // Aktifkan debugging
        error_reporting(E_ALL);
        ini_set('display_errors', 1);

        $rules = [
            'pelanggan_id' => 'required|integer',
            'tanggal_pemesanan' => 'required|valid_date',
            'total_harga' => 'required', // ubah dari decimal ke general agar bisa menerima format
            'status_pemesanan' => 'required|in_list[menunggu,diproses,selesai,dibatalkan]'
        ];

        // Debug validasi
        log_message('debug', 'Validation rules: ' . json_encode($rules));
        log_message('debug', 'POST input: ' . json_encode($_POST));

        if (!$this->validate($rules)) {
            log_message('debug', 'Validation errors: ' . json_encode($this->validator->getErrors()));
            return redirect()->back()
                ->withInput()
                ->with('errors', $this->validator->getErrors());
        }

        try {
            // Debug
            log_message('debug', 'POST data: ' . json_encode($_POST));

            // Persiapkan data, pastikan hilangkan format angka ribuan
            $totalHarga = str_replace([',', '.'], '', $this->request->getPost('total_harga'));

            $data = [
                'pelanggan_id' => $this->request->getPost('pelanggan_id'),
                'tanggal_pemesanan' => $this->request->getPost('tanggal_pemesanan'),
                'total_harga' => floatval($totalHarga), // Pastikan dikonversi ke float
                'status_pemesanan' => $this->request->getPost('status_pemesanan'),
                'catatan' => $this->request->getPost('catatan')
            ];

            // Debug data yang akan disimpan
            log_message('debug', 'Data to be inserted: ' . json_encode($data));

            // Coba simpan menggunakan query manual jika model tidak berhasil
            try {
                $insertId = $this->pemesananModel->insert($data);
                log_message('debug', 'Insert result menggunakan model: ' . ($insertId ? 'Success with ID: ' . $insertId : 'Failed'));

                // Jika gagal insert dengan model, coba dengan query langsung
                if (!$insertId) {
                    $db = \Config\Database::connect();
                    $builder = $db->table('pemesanan');
                    $result = $builder->insert($data);
                    $insertId = $db->insertID();
                    log_message('debug', 'Insert result menggunakan query langsung: ' . ($result ? 'Success with ID: ' . $insertId : 'Failed'));
                }
            } catch (\Exception $modelException) {
                log_message('error', 'Error saat insert model: ' . $modelException->getMessage());

                // Coba dengan query langsung sebagai fallback
                $db = \Config\Database::connect();
                $builder = $db->table('pemesanan');
                $result = $builder->insert($data);
                $insertId = $db->insertID();
                log_message('debug', 'Insert result fallback: ' . ($result ? 'Success with ID: ' . $insertId : 'Failed'));
            }

            // Jika masih gagal, lempar exception
            if (!$insertId) {
                throw new \Exception('Gagal menyimpan pemesanan');
            }

            session()->setFlashdata('success', 'Pemesanan berhasil ditambahkan');
            return redirect()->to('/godmode/pemesanan');
        } catch (\Exception $e) {
            log_message('error', 'Exception: ' . $e->getMessage());
            return redirect()->back()
                ->withInput()
                ->with('error', 'Gagal menambahkan pemesanan: ' . $e->getMessage());
        }
    }

    public function getEdit($id)
    {
        $pemesanan = $this->pemesananModel->find($id);
        if (!$pemesanan) {
            return redirect()->to('/godmode/pemesanan')
                ->with('error', 'Pemesanan tidak ditemukan');
        }

        $pelanggans = $this->pelangganModel->withUser()->findAll();

        return view('pages/godmode/pemesanan/edit', [
            'pemesanan' => $pemesanan,
            'pelanggans' => $pelanggans
        ]);
    }

    public function putUpdate($id)
    {
        $pemesanan = $this->pemesananModel->find($id);
        if (!$pemesanan) {
            return redirect()->to('/godmode/pemesanan')
                ->with('error', 'Pemesanan tidak ditemukan');
        }

        $rules = [
            'status_pemesanan' => 'required|in_list[menunggu,diproses,selesai,dibatalkan]'
        ];

        if (!$this->validate($rules)) {
            return redirect()->back()
                ->withInput()
                ->with('errors', $this->validator->getErrors());
        }

        try {
            $data = [
                'status_pemesanan' => $this->request->getPost('status_pemesanan'),
                'catatan' => $this->request->getPost('catatan'),
            ];

            $this->pemesananModel->update($id, $data);

            return redirect()->to('/godmode/pemesanan')
                ->with('success', 'Status pemesanan berhasil diperbarui');
        } catch (\Exception $e) {
            return redirect()->back()
                ->withInput()
                ->with('error', 'Gagal memperbarui status pemesanan: ' . $e->getMessage());
        }
    }

    public function deletePemesanan($id)
    {
        try {
            $pemesanan = $this->pemesananModel->find($id);
            if (!$pemesanan) {
                if ($this->request->isAJAX()) {
                    return $this->response->setJSON([
                        'success' => false,
                        'message' => 'Pemesanan tidak ditemukan'
                    ]);
                }
                return redirect()->back()
                    ->with('error', 'Pemesanan tidak ditemukan');
            }

            $this->pemesananModel->delete($id);

            if ($this->request->isAJAX()) {
                return $this->response->setJSON([
                    'success' => true,
                    'message' => 'Pemesanan berhasil dihapus'
                ]);
            }
            return redirect()->to('/godmode/pemesanan')
                ->with('success', 'Pemesanan berhasil dihapus');
        } catch (\Exception $e) {
            if ($this->request->isAJAX()) {
                return $this->response->setJSON([
                    'success' => false,
                    'message' => 'Gagal menghapus pemesanan: ' . $e->getMessage()
                ]);
            }
            return redirect()->back()
                ->with('error', 'Gagal menghapus pemesanan: ' . $e->getMessage());
        }
    }

    public function getDetail($id)
    {
        $pemesanan = $this->pemesananModel->find($id);
        if (!$pemesanan) {
            return redirect()->to('/godmode/pemesanan')
                ->with('error', 'Pemesanan tidak ditemukan');
        }

        $pelanggan = $this->pelangganModel->withUser()->find($pemesanan['pelanggan_id']);

        // Ambil detail pemesanan beserta nama produk
        $db = \Config\Database::connect();
        $details = $db->table('detail_pemesanan')
            ->select('detail_pemesanan.*, produk.nama as nama_produk, produk.gambar as gambar_produk')
            ->join('produk', 'produk.id = detail_pemesanan.produk_id', 'left')
            ->where('detail_pemesanan.pemesanan_id', $id)
            ->get()->getResultArray();

        // Ambil pembayaran terkait pesanan ini (ambil pembayaran terakhir jika ada)
        $pembayaran = $this->pembayaranModel->where('pemesanan_id', $id)->orderBy('id', 'DESC')->first();

        return view('pages/godmode/pemesanan/detail', [
            'pemesanan' => $pemesanan,
            'pelanggan' => $pelanggan,
            'details' => $details,
            'pembayaran' => $pembayaran,
        ]);
    }

    public function postCheckout()
    {
        $keranjangModel = new \App\Models\Keranjang();
        $detailPemesananModel = new \App\Models\DetailPemesanan();
        $produkModel = new \App\Models\Produk();

        $pelanggan_id = $this->request->getPost('pelanggan_id');
        if (!$pelanggan_id) {
            return redirect()->back()->with('error', 'Pelanggan tidak valid');
        }

        // Ambil semua item keranjang milik pelanggan
        $keranjangItems = $keranjangModel->where('pelanggan_id', $pelanggan_id)->findAll();
        if (empty($keranjangItems)) {
            return redirect()->back()->with('error', 'Keranjang belanja kosong');
        }

        // Hitung total harga
        $total_harga = array_sum(array_column($keranjangItems, 'subtotal'));

        // Simpan ke tabel pemesanan
        $pemesananData = [
            'pelanggan_id' => $pelanggan_id,
            'tanggal_pemesanan' => date('Y-m-d'),
            'total_harga' => $total_harga,
            'status_pemesanan' => 'menunggu',
            'catatan' => $this->request->getPost('catatan')
        ];
        $pemesananId = $this->pemesananModel->insert($pemesananData, true);
        if (!$pemesananId) {
            return redirect()->back()->with('error', 'Gagal membuat pemesanan');
        }

        // Siapkan data detail pemesanan
        $detailData = [];
        foreach ($keranjangItems as $item) {
            // Ambil harga produk saat ini (untuk keamanan)
            $produk = $produkModel->find($item['produk_id']);
            $harga = $produk ? $produk['harga'] : $item['subtotal'] / $item['jumlah'];
            $detailData[] = [
                'pemesanan_id' => $pemesananId,
                'produk_id' => $item['produk_id'],
                'jumlah' => $item['jumlah'],
                'harga' => $harga,
                'subtotal' => $harga * $item['jumlah'],
                'created_at' => date('Y-m-d H:i:s'),
                'updated_at' => date('Y-m-d H:i:s'),
            ];
            // Update stok produk
            $produkModel->updateStock($item['produk_id'], $item['jumlah']);
        }
        // Simpan detail pemesanan (batch)
        $detailPemesananModel->insertBatch($detailData);

        // Hapus keranjang setelah checkout
        $keranjangModel->where('pelanggan_id', $pelanggan_id)->delete();

        return redirect()->to('/orders')->with('success', 'Checkout berhasil, pemesanan telah dibuat!');
    }

    /**
     * Export data pemesanan ke PDF
     */
    public function getExportPdf()
    {
        // Ambil data pemesanan yang sama seperti di getIndex
        $pemesanans = $this->pemesananModel->findAll();
        $formattedPemesanans = [];

        foreach ($pemesanans as $pemesanan) {
            try {
                $pelanggan = $this->pelangganModel->withUser()->find($pemesanan['pelanggan_id']);
                $formattedPemesanans[] = [
                    'id' => $pemesanan['id'],
                    'pelanggan_nama' => $pelanggan['username'] ?? 'Pelanggan tidak ditemukan',
                    'email' => $pelanggan['email'] ?? '-',
                    'tanggal_pemesanan' => $pemesanan['tanggal_pemesanan'],
                    'total_harga' => $pemesanan['total_harga'],
                    'status_pemesanan' => $pemesanan['status_pemesanan'],
                    'created_at' => $pemesanan['created_at'],
                    'updated_at' => $pemesanan['updated_at']
                ];
            } catch (\Exception $e) {
                $formattedPemesanans[] = [
                    'id' => $pemesanan['id'],
                    'pelanggan_nama' => 'Error: Pelanggan tidak ditemukan',
                    'email' => '-',
                    'tanggal_pemesanan' => $pemesanan['tanggal_pemesanan'],
                    'total_harga' => $pemesanan['total_harga'],
                    'status_pemesanan' => $pemesanan['status_pemesanan'],
                    'created_at' => $pemesanan['created_at'],
                    'updated_at' => $pemesanan['updated_at']
                ];
            }
        }

        // Load DOMPDF dengan konfigurasi UTF-8
        $dompdf = new \Dompdf\Dompdf();
        $dompdf->setPaper('A4', 'portrait');

        // Konfigurasi untuk mendukung UTF-8
        $options = $dompdf->getOptions();
        $options->setIsHtml5ParserEnabled(true);
        $options->setIsPhpEnabled(true);
        $options->setIsRemoteEnabled(true);
        $dompdf->setOptions($options);

        // Generate HTML content
        $html = view('pages/godmode/pemesanan/export_pdf', [
            'pemesanans' => $formattedPemesanans,
            'tanggal_export' => date('d/m/Y H:i:s')
        ]);

        $dompdf->loadHtml($html, 'UTF-8');
        $dompdf->render();

        // Bersihkan output buffer sebelum mengirim file
        if (ob_get_length()) ob_end_clean();

        // Output PDF, paksa download
        $dompdf->stream('daftar_pemesanan_' . date('Y-m-d_H-i-s') . '.pdf', ['Attachment' => true]);
        exit;
    }

    /**
     * Export data pemesanan ke XLSX
     */
    public function getExportXlsx()
    {
        // Ambil data pemesanan yang sama seperti di getIndex
        $pemesanans = $this->pemesananModel->findAll();
        $formattedPemesanans = [];

        foreach ($pemesanans as $pemesanan) {
            try {
                $pelanggan = $this->pelangganModel->withUser()->find($pemesanan['pelanggan_id']);
                $formattedPemesanans[] = [
                    'id' => $pemesanan['id'],
                    'pelanggan_nama' => $pelanggan['username'] ?? 'Pelanggan tidak ditemukan',
                    'email' => $pelanggan['email'] ?? '-',
                    'tanggal_pemesanan' => $pemesanan['tanggal_pemesanan'],
                    'total_harga' => $pemesanan['total_harga'],
                    'status_pemesanan' => $pemesanan['status_pemesanan'],
                    'created_at' => $pemesanan['created_at'],
                    'updated_at' => $pemesanan['updated_at']
                ];
            } catch (\Exception $e) {
                $formattedPemesanans[] = [
                    'id' => $pemesanan['id'],
                    'pelanggan_nama' => 'Error: Pelanggan tidak ditemukan',
                    'email' => '-',
                    'tanggal_pemesanan' => $pemesanan['tanggal_pemesanan'],
                    'total_harga' => $pemesanan['total_harga'],
                    'status_pemesanan' => $pemesanan['status_pemesanan'],
                    'created_at' => $pemesanan['created_at'],
                    'updated_at' => $pemesanan['updated_at']
                ];
            }
        }

        // Create new Spreadsheet object
        $spreadsheet = new \PhpOffice\PhpSpreadsheet\Spreadsheet();
        $sheet = $spreadsheet->getActiveSheet();

        // Set document properties
        $spreadsheet->getProperties()
            ->setCreator('E-Commerce System')
            ->setLastModifiedBy('E-Commerce System')
            ->setTitle('Daftar Pemesanan')
            ->setSubject('Export Data Pemesanan')
            ->setDescription('Data pemesanan yang diexport dari sistem e-commerce');

        // Set headers
        $headers = [
            'No',
            'ID Pemesanan',
            'Nama Pelanggan',
            'Email',
            'Tanggal Pemesanan',
            'Total Harga',
            'Status',
            'Tanggal Dibuat'
        ];

        $col = 'A';
        foreach ($headers as $header) {
            $sheet->setCellValue($col . '1', $header);
            $sheet->getColumnDimension($col)->setAutoSize(true);
            $col++;
        }

        // Style header
        $headerStyle = [
            'font' => [
                'bold' => true,
                'color' => ['rgb' => 'FFFFFF'],
            ],
            'fill' => [
                'fillType' => \PhpOffice\PhpSpreadsheet\Style\Fill::FILL_SOLID,
                'startColor' => ['rgb' => '4472C4'],
            ],
            'alignment' => [
                'horizontal' => \PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER,
            ],
        ];
        $sheet->getStyle('A1:H1')->applyFromArray($headerStyle);

        // Add data
        $row = 2;
        foreach ($formattedPemesanans as $index => $pemesanan) {
            $sheet->setCellValue('A' . $row, $index + 1);
            $sheet->setCellValue('B' . $row, $pemesanan['id']);
            $sheet->setCellValue('C' . $row, $pemesanan['pelanggan_nama']);
            $sheet->setCellValue('D' . $row, $pemesanan['email']);
            $sheet->setCellValue('E' . $row, date('d/m/Y', strtotime($pemesanan['tanggal_pemesanan'])));
            $sheet->setCellValue('F' . $row, 'Rp ' . number_format($pemesanan['total_harga'], 0, ',', '.'));
            $sheet->setCellValue('G' . $row, ucfirst($pemesanan['status_pemesanan']));
            $sheet->setCellValue('H' . $row, date('d/m/Y H:i', strtotime($pemesanan['created_at'])));
            $row++;
        }

        // Style data rows
        $dataStyle = [
            'borders' => [
                'allBorders' => [
                    'borderStyle' => \PhpOffice\PhpSpreadsheet\Style\Border::BORDER_THIN,
                    'color' => ['rgb' => '000000'],
                ],
            ],
        ];
        $sheet->getStyle('A1:H' . ($row - 1))->applyFromArray($dataStyle);

        // Create Excel file
        $writer = new \PhpOffice\PhpSpreadsheet\Writer\Xlsx($spreadsheet);

        // Set headers for download
        header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
        header('Content-Disposition: attachment;filename="daftar_pemesanan_' . date('Y-m-d_H-i-s') . '.xlsx"');
        header('Cache-Control: max-age=0');

        // Save file to PHP output
        $writer->save('php://output');
        exit;
    }
}
