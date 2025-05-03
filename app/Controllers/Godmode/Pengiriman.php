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
    public function index()
    {
        $data = [
            'title' => 'Daftar Pengiriman',
            'pengiriman' => $this->pengirimanModel->withPesanan()->findAll()
        ];

        return view('pages/godmode/pengiriman/index', $data);
    }

    /**
     * Show the form for creating a new pengiriman.
     *
     * @return mixed
     */
    public function create()
    {
        // Load model pesanan jika diperlukan untuk dropdown
        // $pesananModel = model('App\Models\Pesanan');
        // $data['pesanan'] = $pesananModel->findAll();

        $data = [
            'title' => 'Tambah Pengiriman Baru',
            'validation' => \Config\Services::validation()
        ];

        return view('pages/godmode/pengiriman/create', $data);
    }

    /**
     * Store a newly created pengiriman in storage.
     *
     * @return mixed
     */
    public function store()
    {
        // Validasi input
        $rules = $this->pengirimanModel->getValidationRules();
        if (!$this->validate($rules)) {
            return redirect()->to('godmode/pengiriman/create')->withInput()
                             ->with('validation', service('validation'));
        }

        // Simpan data
        $data = [
            'pesanan_id' => $this->request->getVar('pesanan_id'),
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
    public function edit($id = null)
    {
        $pengiriman = $this->pengirimanModel->find($id);

        if (empty($pengiriman)) {
            session()->setFlashdata('error', 'Pengiriman tidak ditemukan');
            return redirect()->to('godmode/pengiriman');
        }

        // Load model pesanan jika diperlukan untuk dropdown
        // $pesananModel = model('App\Models\Pesanan');
        // $data['pesanan'] = $pesananModel->findAll();

        $data = [
            'title' => 'Edit Pengiriman',
            'validation' => \Config\Services::validation(),
            'pengiriman' => $pengiriman
        ];

        return view('pages/godmode/pengiriman/edit', $data);
    }

    /**
     * Update the specified pengiriman in storage.
     *
     * @param int $id
     * @return mixed
     */
    public function update($id = null)
    {
        // Validasi input
        $rules = $this->pengirimanModel->getValidationRules();
        if (!$this->validate($rules)) {
            return redirect()->to("godmode/pengiriman/edit/$id")->withInput()
                             ->with('validation', service('validation'));
        }

        // Update data
        $data = [
            'pesanan_id' => $this->request->getVar('pesanan_id'),
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
    public function delete($id = null)
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
}