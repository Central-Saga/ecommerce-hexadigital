<?php

namespace App\Controllers\Godmode;

use App\Controllers\BaseController;
use App\Models\Pengiriman as PengirimanModel;

class Pengiriman extends BaseController
{
    protected $pengirimanModel;

    public function __construct()
    {
        $this->pengirimanModel = new PengirimanModel();
    }

    /**
     * Display a listing of the pengiriman.
     *
     * @return mixed
     */
    public function getIndex()
    {
        $data = [
            'title' => 'Daftar Pengiriman',
            'pengiriman' => $this->pengirimanModel->withPemesanan()->findAll()
        ];

        return view('pages/godmode/pengiriman/index', $data);
    }

    /**
     * Show the form for creating a new pengiriman.
     *
     * @return mixed
     */
    public function getCreate()
    {
        $pemesananModel = new \App\Models\Pemesanan();
        $pemesanan = $pemesananModel->findAll();

        $data = [
            'title' => 'Tambah Pengiriman Baru',
            'pemesanan' => $pemesanan,
            'validation' => \Config\Services::validation()
        ];

        return view('pages/godmode/pengiriman/create', $data);
    }

    /**
     * Store a newly created pengiriman in storage.
     *
     * @return mixed
     */
    public function postStore()
    {
        // Validasi input
        $rules = $this->pengirimanModel->getValidationRules();
        if (!$this->validate($rules)) {
            return redirect()->to('godmode/pengiriman/create')->withInput()
                ->with('validation', service('validation'));
        }

        // Simpan data
        $data = [
            'pemesanan_id' => $this->request->getVar('pemesanan_id'),
            'tanggal_kirim' => $this->request->getVar('tanggal_kirim'),
            'tanggal_terima' => $this->request->getVar('tanggal_terima'),
            'deskripsi' => $this->request->getVar('deskripsi'),
            'status' => $this->request->getVar('status')
        ];

        $this->pengirimanModel->insert($data);
        session()->setFlashdata('success', 'Pengiriman berhasil ditambahkan');

        return redirect()->to('godmode/pengiriman');
    }

    /**
     * Show the form for editing the specified pengiriman.
     *
     * @param int $id
     * @return mixed
     */
    public function getEdit($id = null)
    {
        $pengiriman = $this->pengirimanModel->find($id);

        if (empty($pengiriman)) {
            session()->setFlashdata('error', 'Pengiriman tidak ditemukan');
            return redirect()->to('godmode/pengiriman');
        }

        $pemesananModel = new \App\Models\Pemesanan();
        $pemesanan = $pemesananModel->findAll();

        $data = [
            'title' => 'Edit Pengiriman',
            'validation' => \Config\Services::validation(),
            'pengiriman' => $pengiriman,
            'pemesanan' => $pemesanan
        ];

        return view('pages/godmode/pengiriman/edit', $data);
    }

    /**
     * Update the specified pengiriman in storage.
     *
     * @param int $id
     * @return mixed
     */
    public function putUpdate($id = null)
    {
        // Validasi input
        $rules = $this->pengirimanModel->getValidationRules();
        if (!$this->validate($rules)) {
            return redirect()->to("godmode/pengiriman/edit/$id")->withInput()
                ->with('validation', service('validation'));
        }

        // Update data
        $data = [
            'pemesanan_id' => $this->request->getVar('pemesanan_id'),
            'tanggal_kirim' => $this->request->getVar('tanggal_kirim'),
            'tanggal_terima' => $this->request->getVar('tanggal_terima'),
            'deskripsi' => $this->request->getVar('deskripsi'),
            'status' => $this->request->getVar('status')
        ];

        $this->pengirimanModel->update($id, $data);
        session()->setFlashdata('success', 'Pengiriman berhasil diperbarui');

        return redirect()->to('godmode/pengiriman');
    }

    /**
     * Remove the specified pengiriman from storage.
     *
     * @param int $id
     * @return mixed
     */
    public function postDelete($id = null)
    {
        $pengiriman = $this->pengirimanModel->find($id);

        if (empty($pengiriman)) {
            session()->setFlashdata('error', 'Pengiriman tidak ditemukan');
            return redirect()->to('godmode/pengiriman');
        }

        $this->pengirimanModel->delete($id);
        session()->setFlashdata('success', 'Pengiriman berhasil dihapus');

        return redirect()->to('godmode/pengiriman');
    }

    /**
     * Tampilkan detail pengiriman
     */
    public function getDetail($id = null)
    {
        $pengiriman = $this->pengirimanModel->withPemesanan()->where('pengiriman.id', $id)->first();
        if (!$pengiriman) {
            session()->setFlashdata('error', 'Pengiriman tidak ditemukan');
            return redirect()->to('godmode/pengiriman');
        }
        // Ambil data pemesanan terkait
        $pemesananModel = new \App\Models\Pemesanan();
        $pemesanan = $pemesananModel->find($pengiriman['pemesanan_id']);
        return view('pages/godmode/pengiriman/detail', [
            'pengiriman' => $pengiriman,
            'pemesanan' => $pemesanan
        ]);
    }
}
