<?php

namespace App\Controllers\Godmode;

use App\Controllers\BaseController;
use App\Models\Produk as ProdukModel;
use App\Models\Kategori as KategoriModel;
use CodeIgniter\HTTP\ResponseInterface;

class Produk extends BaseController
{
    protected $helpers = ['form'];
    protected $produkModel;
    protected $kategoriModel;

    public function __construct()
    {
        $this->produkModel = new ProdukModel();
        $this->kategoriModel = new KategoriModel();
    }

    public function getIndex()
    {
        // Ambil semua produk dengan kategori dari database
        $produks = $this->produkModel->getProductsWithCategory();

        // Format data produk untuk view
        $formattedProduks = [];
        foreach ($produks as $produk) {
            $formattedProduks[] = [
                'id' => $produk['id'],
                'nama_produk' => $produk['nama'],
                'harga' => $produk['harga'],
                'stok' => $produk['stok'],
                'deskripsi' => $produk['deskripsi'],
                'kategori_nama' => $produk['kategori'] ?? 'Tidak ada kategori',
                'created_at' => $produk['created_at'],
                'updated_at' => $produk['updated_at'],
                'gambar' => $produk['gambar']
            ];
        }

        return view('pages/godmode/produk/index', [
            'produks' => $formattedProduks
        ]);
    }

    public function getCreate()
    {
        // Ambil semua kategori untuk dropdown
        $kategoris = $this->kategoriModel->findAll();

        return view('pages/godmode/produk/create', [
            'kategoris' => $kategoris
        ]);
    }

    public function postStore()
    {
        // Debug: Log request data
        log_message('debug', 'POST Data: ' . json_encode($this->request->getPost()));
        log_message('debug', 'FILES Data: ' . json_encode($this->request->getFiles()));

        $rules = [
            'nama_produk' => 'required|min_length[3]|max_length[255]',
            'harga' => 'required|numeric',
            'stok' => 'required|integer',
            'deskripsi' => 'permit_empty',
            'kategori_id' => 'permit_empty|integer',
            'gambar' => 'uploaded[gambar]|is_image[gambar]|max_size[gambar,2048]|mime_in[gambar,image/jpg,image/jpeg,image/png]'
        ];

        if (!$this->validate($rules)) {
            log_message('error', 'Validation errors: ' . json_encode($this->validator->getErrors()));
            return redirect()->back()
                ->withInput()
                ->with('errors', $this->validator->getErrors());
        }

        try {
            $data = [
                'nama_produk' => $this->request->getPost('nama_produk'),
                'harga' => str_replace(',', '', $this->request->getPost('harga')),
                'stok' => $this->request->getPost('stok'),
                'deskripsi' => $this->request->getPost('deskripsi'),
                'kategori_id' => $this->request->getPost('kategori_id') ?: null
            ];

            // Handle file upload
            $gambar = $this->request->getFile('gambar');
            if ($gambar->isValid() && !$gambar->hasMoved()) {
                $newName = $gambar->getRandomName();
                $gambar->move(ROOTPATH . 'public/uploads/produk', $newName);
                $data['gambar'] = $newName;
            }

            $this->produkModel->insert($data);

            session()->setFlashdata('success', 'Produk berhasil ditambahkan');
            return redirect()->to('/godmode/produk');
        } catch (\Exception $e) {
            log_message('error', 'Exception in postStore: ' . $e->getMessage());
            return redirect()->back()
                ->withInput()
                ->with('errors', ['general' => 'Gagal menambahkan produk: ' . $e->getMessage()]);
        }
    }

    public function getEdit($id)
    {
        $produk = $this->produkModel->find($id);
        if (!$produk) {
            return redirect()->to('/godmode/produk')
                ->with('error', 'Produk tidak ditemukan');
        }

        // Ambil semua kategori untuk dropdown
        $kategoris = $this->kategoriModel->findAll();

        return view('pages/godmode/produk/edit', [
            'produk' => $produk,
            'kategoris' => $kategoris
        ]);
    }

    public function putUpdate($id)
    {
        $produk = $this->produkModel->find($id);
        if (!$produk) {
            return redirect()->to('/godmode/produk')
                ->with('error', 'Produk tidak ditemukan');
        }

        $rules = [
            'nama_produk' => 'required|min_length[3]|max_length[255]',
            'harga' => 'required|numeric',
            'stok' => 'required|integer',
            'deskripsi' => 'permit_empty',
            'kategori_id' => 'permit_empty|integer',
            'gambar' => 'permit_empty|is_image[gambar]|max_size[gambar,2048]|mime_in[gambar,image/jpg,image/jpeg,image/png]'
        ];

        if (!$this->validate($rules)) {
            return redirect()->back()
                ->withInput()
                ->with('errors', validation_errors());
        }

        try {
            $data = [
                'nama_produk' => $this->request->getPost('nama_produk'),
                'harga' => $this->request->getPost('harga'),
                'stok' => $this->request->getPost('stok'),
                'deskripsi' => $this->request->getPost('deskripsi'),
                'kategori_id' => $this->request->getPost('kategori_id') ?: null
            ];

            // Handle file upload
            $gambar = $this->request->getFile('gambar');
            if ($gambar->isValid() && !$gambar->hasMoved()) {
                // Delete old file if exists
                if ($produk['gambar'] && file_exists(ROOTPATH . 'public/uploads/produk/' . $produk['gambar'])) {
                    unlink(ROOTPATH . 'public/uploads/produk/' . $produk['gambar']);
                }

                // Generate random name
                $newName = $gambar->getRandomName();
                // Move file to upload directory
                $gambar->move(ROOTPATH . 'public/uploads/produk', $newName);
                // Add filename to data
                $data['gambar'] = $newName;
            }

            $this->produkModel->update($id, $data);

            return redirect()->to('/godmode/produk')
                ->with('success', 'Produk berhasil diperbarui');
        } catch (\Exception $e) {
            return redirect()->back()
                ->withInput()
                ->with('error', 'Gagal memperbarui produk: ' . $e->getMessage());
        }
    }

    public function deleteProduk($id)
    {
        try {
            $produk = $this->produkModel->find($id);
            if (!$produk) {
                if ($this->request->isAJAX()) {
                    return response()->setJSON([
                        'success' => false,
                        'message' => 'Produk tidak ditemukan'
                    ]);
                }
                return redirect()->back()
                    ->with('error', 'Produk tidak ditemukan');
            }

            // Delete product image if exists
            if ($produk['gambar'] && file_exists(ROOTPATH . 'public/uploads/produk/' . $produk['gambar'])) {
                unlink(ROOTPATH . 'public/uploads/produk/' . $produk['gambar']);
            }

            $this->produkModel->delete($id);

            if ($this->request->isAJAX()) {
                return response()->setJSON([
                    'success' => true,
                    'message' => 'Produk berhasil dihapus'
                ]);
            }
            return redirect()->to('/godmode/produk')
                ->with('success', 'Produk berhasil dihapus');
        } catch (\Exception $e) {
            if ($this->request->isAJAX()) {
                return response()->setJSON([
                    'success' => false,
                    'message' => 'Gagal menghapus produk: ' . $e->getMessage()
                ]);
            }
            return redirect()->back()
                ->with('error', 'Gagal menghapus produk: ' . $e->getMessage());
        }
    }

    public function getDetail($id)
    {
        $produk = $this->produkModel->withKategori()->find($id);

        if (!$produk) {
            return redirect()->to('/godmode/produk')
                ->with('error', 'Produk tidak ditemukan');
        }

        return view('pages/godmode/produk/detail', [
            'produk' => $produk
        ]);
    }
}
