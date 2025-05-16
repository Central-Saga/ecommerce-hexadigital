<?php

namespace App\Controllers\Godmode;

use App\Controllers\BaseController;
use App\Models\Pemesanan as PemesananModel;
use App\Models\Pelanggan as PelangganModel;
use CodeIgniter\HTTP\ResponseInterface;

class Pemesanan extends BaseController
{
    protected $helpers = ['form'];
    protected $pemesananModel;
    protected $pelangganModel;

    public function __construct()
    {
        $this->pemesananModel = new PemesananModel();
        $this->pelangganModel = new PelangganModel();
    }

    public function getIndex()
    {
        // Aktifkan debugging sementara untuk melihat data
        error_reporting(E_ALL);
        ini_set('display_errors', 1);
        
        // Ambil semua pemesanan dari database dengan relasi pelanggan
        $pemesanans = $this->pemesananModel->findAll();
        
        // Debug: Tambahkan log
        log_message('debug', 'Pemesanans raw: ' . json_encode($pemesanans));
        
        // SQL Query debug
        log_message('debug', 'Last Query: ' . $this->pemesananModel->db->getLastQuery());
        
        // Check if database has data
        log_message('debug', 'Database count rows: ' . $this->pemesananModel->countAllResults());
        
        // Format data pemesanan untuk view
        $formattedPemesanans = [];
        foreach ($pemesanans as $pemesanan) {
            // Dapatkan data pelanggan dengan informasi user
            $pelanggan = $this->pelangganModel->withUser()->find($pemesanan['pelanggan_id']);
            
            // Debug: Log data pelanggan
            log_message('debug', 'Pelanggan data: ' . json_encode($pelanggan));
            
            $formattedPemesanans[] = [
                'id' => $pemesanan['id'],
                'pelanggan_nama' => $pelanggan ? ($pelanggan['username'] ?? 'Tidak ada nama') : 'Pelanggan tidak ditemukan',
                'email' => $pelanggan ? ($pelanggan['email'] ?? '-') : '-',
                'tanggal_pemesanan' => $pemesanan['tanggal_pemesanan'],
                'total_harga' => $pemesanan['total_harga'],
                'status_pemesanan' => $pemesanan['status_pemesanan'],
                'created_at' => $pemesanan['created_at'],
                'updated_at' => $pemesanan['updated_at']
            ];
        }

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
            'total_harga' => 'required|decimal',
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
                'total_harga' => $totalHarga,
                'status_pemesanan' => $this->request->getPost('status_pemesanan'),
                'catatan' => $this->request->getPost('catatan')
            ];
            
            // Debug data yang akan disimpan
            log_message('debug', 'Data to be inserted: ' . json_encode($data));

            // Pre-insert debugging
            log_message('debug', 'Allowed fields in model: ' . json_encode($this->pemesananModel->allowedFields));
            
            $insertId = $this->pemesananModel->insert($data);
            
            // Post-insert debugging
            log_message('debug', 'Insert result: ' . ($insertId ? 'Success with ID: ' . $insertId : 'Failed'));
            
            // Debug hasil insert
            log_message('debug', 'Insert ID: ' . $insertId);

            session()->setFlashdata('success', 'Pemesanan berhasil ditambahkan');
            return redirect()->to('/godmode/pemesanan');
        } catch (\Exception $e) {
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
            'pelanggan_id' => 'required|integer',
            'tanggal_pemesanan' => 'required|valid_date',
            'total_harga' => 'required|decimal',
            'status_pemesanan' => 'required|in_list[menunggu,diproses,selesai,dibatalkan]'
        ];

        if (!$this->validate($rules)) {
            return redirect()->back()
                ->withInput()
                ->with('errors', $this->validator->getErrors());
        }

        try {
            // Persiapkan data, pastikan hilangkan format angka ribuan
            $totalHarga = str_replace([',', '.'], '', $this->request->getPost('total_harga'));
            
            $data = [
                'pelanggan_id' => $this->request->getPost('pelanggan_id'),
                'tanggal_pemesanan' => $this->request->getPost('tanggal_pemesanan'),
                'total_harga' => $totalHarga,
                'status_pemesanan' => $this->request->getPost('status_pemesanan'),
                'catatan' => $this->request->getPost('catatan')
            ];

            $this->pemesananModel->update($id, $data);

            return redirect()->to('/godmode/pemesanan')
                ->with('success', 'Pemesanan berhasil diperbarui');
        } catch (\Exception $e) {
            return redirect()->back()
                ->withInput()
                ->with('error', 'Gagal memperbarui pemesanan: ' . $e->getMessage());
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
        
        return view('pages/godmode/pemesanan/detail', [
            'pemesanan' => $pemesanan,
            'pelanggan' => $pelanggan
        ]);
    }
}
