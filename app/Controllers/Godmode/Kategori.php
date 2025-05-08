<?php

namespace App\Controllers\Godmode;

use App\Controllers\BaseController;
use App\Models\Kategori as KategoriModel;
use CodeIgniter\HTTP\ResponseInterface;

class Kategori extends BaseController
{
    protected $helpers = ['form'];
    protected $kategoriModel;

    public function __construct()
    {
        $this->kategoriModel = new KategoriModel();
    }

    public function getIndex()
    {
        // Ambil semua kategori dari database
        $kategoris = $this->kategoriModel->findAll();

        // Format data kategori untuk view
        $formattedKategoris = [];
        foreach ($kategoris as $kategori) {
            $formattedKategoris[] = [
                'id' => $kategori['id'],
                'nama_kategori' => $kategori['nama_kategori'],
                'deskripsi_kategori' => $kategori['deskripsi_kategori'],
                'created_at' => $kategori['created_at'],
                'updated_at' => $kategori['updated_at']
            ];
        }

        return view('pages/godmode/kategori/index', [
            'kategoris' => $formattedKategoris
        ]);
    }

    public function getCreate()
    {
        return view('pages/godmode/kategori/create');
    }

    public function postStore()
    {
        $rules = [
            'nama_kategori' => 'required|min_length[3]|max_length[100]',
            'deskripsi_kategori' => 'permit_empty'
        ];

        if (!$this->validate($rules)) {
            return redirect()->back()
                ->withInput()
                ->with('errors', $this->validator->getErrors());
        }

        try {
            $data = [
                'nama_kategori' => $this->request->getPost('nama_kategori'),
                'deskripsi_kategori' => $this->request->getPost('deskripsi_kategori')
            ];

            $this->kategoriModel->insert($data);

            session()->setFlashdata('success', 'Kategori berhasil ditambahkan');
            return redirect()->to('/godmode/kategori');
        } catch (\Exception $e) {
            return redirect()->back()
                ->withInput()
                ->with('error', 'Gagal menambahkan kategori: ' . $e->getMessage());
        }
    }

    public function getEdit($id)
    {
        $kategori = $this->kategoriModel->find($id);
        if (!$kategori) {
            return redirect()->to('/godmode/kategori')
                ->with('error', 'Kategori tidak ditemukan');
        }

        return view('pages/godmode/kategori/edit', [
            'kategori' => $kategori
        ]);
    }

    public function putUpdate($id)
    {
        $kategori = $this->kategoriModel->find($id);
        if (!$kategori) {
            return redirect()->to('/godmode/kategori')
                ->with('error', 'Kategori tidak ditemukan');
        }

        $rules = [
            'nama_kategori' => 'required|min_length[3]|max_length[100]',
            'deskripsi_kategori' => 'permit_empty'
        ];

        if (!$this->validate($rules)) {
            return redirect()->back()
                ->withInput()
                ->with('errors', $this->validator->getErrors());
        }

        try {
            $data = [
                'nama_kategori' => $this->request->getPost('nama_kategori'),
                'deskripsi_kategori' => $this->request->getPost('deskripsi_kategori')
            ];

            $this->kategoriModel->update($id, $data);

            return redirect()->to('/godmode/kategori')
                ->with('success', 'Kategori berhasil diperbarui');
        } catch (\Exception $e) {
            return redirect()->back()
                ->withInput()
                ->with('error', 'Gagal memperbarui kategori: ' . $e->getMessage());
        }
    }

    public function deleteKategori($id)
    {
        try {
            $kategori = $this->kategoriModel->find($id);
            if (!$kategori) {
                if ($this->request->isAJAX()) {
                    return $this->response->setJSON([
                        'success' => false,
                        'message' => 'Kategori tidak ditemukan'
                    ]);
                }
                return redirect()->back()
                    ->with('error', 'Kategori tidak ditemukan');
            }

            $this->kategoriModel->delete($id);

            if ($this->request->isAJAX()) {
                return $this->response->setJSON([
                    'success' => true,
                    'message' => 'Kategori berhasil dihapus'
                ]);
            }
            return redirect()->to('/godmode/kategori')
                ->with('success', 'Kategori berhasil dihapus');
        } catch (\Exception $e) {
            if ($this->request->isAJAX()) {
                return $this->response->setJSON([
                    'success' => false,
                    'message' => 'Gagal menghapus kategori: ' . $e->getMessage()
                ]);
            }
            return redirect()->back()
                ->with('error', 'Gagal menghapus kategori: ' . $e->getMessage());
        }
    }
}
