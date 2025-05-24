<?php

namespace App\Controllers\Godmode;

use App\Controllers\BaseController;
use App\Models\Pembayaran as PembayaranModel;
use App\Models\Pemesanan as PemesananModel;
use CodeIgniter\HTTP\ResponseInterface;

class Pembayaran extends BaseController
{
    protected $helpers = ['form'];
    protected $pembayaranModel;
    protected $pemesananModel;

    public function __construct()
    {
        $this->pembayaranModel = new PembayaranModel();
        $this->pemesananModel = new PemesananModel();
    }

    public function getIndex()
    {
        $pembayarans = $this->pembayaranModel->findAll();
        $formattedPembayarans = [];
        foreach ($pembayarans as $pembayaran) {
            $pemesanan = $this->pemesananModel->withPelanggan()->where('pemesanan.id', $pembayaran['pesanan_id'])->first();
            $formattedPembayarans[] = [
                'id' => $pembayaran['id'],
                'pesanan_id' => $pembayaran['pesanan_id'],
                'nama_pelanggan' => $pemesanan['nama_pelanggan'] ?? '-',
                'nama_pengirim' => $pembayaran['nama_pengirim'],
                'metode_pembayaran' => $pembayaran['metode_pembayaran'],
                'bukti_pembayaran' => $pembayaran['bukti_pembayaran'],
                'total_harga' => $pembayaran['total_harga'],
                'tanggal_pembayaran' => $pembayaran['tanggal_pembayaran'],
                'status' => $pembayaran['status'],
                'catatan' => $pembayaran['catatan'],
                'created_at' => $pembayaran['created_at'],
                'updated_at' => $pembayaran['updated_at'],
                'pemesanan' => $pemesanan,
            ];
        }
        return view('pages/godmode/pembayaran/index', [
            'pembayarans' => $formattedPembayarans
        ]);
    }

    public function getCreate()
    {
        $pemesanans = $this->pemesananModel->findAll();
        return view('pages/godmode/pembayaran/create', [
            'pemesanans' => $pemesanans
        ]);
    }

    public function postStore()
    {
        $rules = [
            'pesanan_id' => 'required|integer',
            'metode_pembayaran' => 'required|string',
            'total_harga' => 'required|numeric',
            'status' => 'required|in_list[pending,diterima,ditolak]',
            'nama_pengirim' => 'permit_empty|string',
            'catatan' => 'permit_empty|string',
        ];
        if (!$this->validate($rules)) {
            return redirect()->back()
                ->withInput()
                ->with('errors', $this->validator->getErrors());
        }
        $data = [
            'pesanan_id' => $this->request->getPost('pesanan_id'),
            'metode_pembayaran' => $this->request->getPost('metode_pembayaran'),
            'bukti_pembayaran' => $this->request->getPost('bukti_pembayaran'),
            'total_harga' => $this->request->getPost('total_harga'),
            'tanggal_pembayaran' => $this->request->getPost('tanggal_pembayaran'),
            'status' => $this->request->getPost('status'),
            'catatan' => $this->request->getPost('catatan'),
            'nama_pengirim' => $this->request->getPost('nama_pengirim'),
        ];
        $insertId = $this->pembayaranModel->insert($data);
        if (!$insertId) {
            return redirect()->back()
                ->withInput()
                ->with('error', 'Gagal menambahkan pembayaran');
        }
        session()->setFlashdata('success', 'Pembayaran berhasil ditambahkan');
        return redirect()->to('/godmode/pembayaran');
    }

    public function getEdit($id)
    {
        $pembayaran = $this->pembayaranModel->find($id);
        if (!$pembayaran) {
            return redirect()->to('/godmode/pembayaran')
                ->with('error', 'Pembayaran tidak ditemukan');
        }
        $pemesanans = $this->pemesananModel->findAll();
        return view('pages/godmode/pembayaran/edit', [
            'pembayaran' => $pembayaran,
            'pemesanans' => $pemesanans
        ]);
    }

    public function putUpdate($id)
    {
        $pembayaran = $this->pembayaranModel->find($id);
        if (!$pembayaran) {
            return redirect()->to('/godmode/pembayaran')
                ->with('error', 'Pembayaran tidak ditemukan');
        }
        $rules = [
            'status' => 'required|in_list[pending,diterima,ditolak]',
            'catatan' => 'permit_empty|string',
        ];
        if (!$this->validate($rules)) {
            return redirect()->back()
                ->withInput()
                ->with('errors', $this->validator->getErrors());
        }
        $data = [
            'status' => $this->request->getPost('status'),
            'catatan' => $this->request->getPost('catatan'),
        ];
        $this->pembayaranModel->update($id, $data);
        return redirect()->to('/godmode/pembayaran')
            ->with('success', 'Status pembayaran berhasil diperbarui');
    }

    public function deletePembayaran($id)
    {
        $pembayaran = $this->pembayaranModel->find($id);
        if (!$pembayaran) {
            if ($this->request->isAJAX()) {
                return $this->response->setJSON([
                    'success' => false,
                    'message' => 'Pembayaran tidak ditemukan'
                ]);
            }
            return redirect()->back()
                ->with('error', 'Pembayaran tidak ditemukan');
        }
        $this->pembayaranModel->delete($id);
        if ($this->request->isAJAX()) {
            return $this->response->setJSON([
                'success' => true,
                'message' => 'Pembayaran berhasil dihapus'
            ]);
        }
        return redirect()->to('/godmode/pembayaran')
            ->with('success', 'Pembayaran berhasil dihapus');
    }

    public function getDetail($id)
    {
        $pembayaran = $this->pembayaranModel->find($id);
        if (!$pembayaran) {
            return redirect()->to('/godmode/pembayaran')
                ->with('error', 'Pembayaran tidak ditemukan');
        }
        $pemesanan = $this->pemesananModel->find($pembayaran['pesanan_id']);
        return view('pages/godmode/pembayaran/detail', [
            'pembayaran' => $pembayaran,
            'pemesanan' => $pemesanan
        ]);
    }
}
