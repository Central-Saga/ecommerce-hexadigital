<?php

namespace App\Controllers\Godmode;

use App\Controllers\BaseController;
use App\Models\Pelanggan as PelangganModel;
use CodeIgniter\HTTP\ResponseInterface;

class Pelanggan extends BaseController
{
    protected $helpers = ['form'];
    protected $pelangganModel;

    public function __construct()
    {
        $this->pelangganModel = new PelangganModel();
    }

    public function getIndex()
    {
        // Ambil semua pelanggan dari database dengan data user
        $pelanggans = $this->pelangganModel->withUser()->findAll();

        // Format data pelanggan untuk view
        $formattedPelanggans = [];
        foreach ($pelanggans as $pelanggan) {
            $formattedPelanggans[] = [
                'id' => $pelanggan['id'],
                'name' => $pelanggan['username'],
                'email' => $pelanggan['email'],
                'phone' => $pelanggan['no_telepon'],
                'status' => $pelanggan['status']
            ];
        }

        return view('pages/godmode/customer/index', [
            'customers' => $formattedPelanggans
        ]);
    }

    public function getCreate()
    {
        $users = auth()->getProvider()->findAll();
        $formattedUsers = [];
        foreach ($users as $user) {
            $formattedUsers[] = [
                'id' => $user->id,
                'name' => $user->username,
                'email' => $user->email
            ];
        }
        return view('pages/godmode/customer/create', [
            'users' => $formattedUsers
        ]);
    }

    public function postStore()
    {
        $rules = [
            'user_id' => 'required|numeric|is_not_unique[users.id]',
            'no_telepon' => 'required|numeric|min_length[10]|max_length[15]',
            'alamat' => 'required',
            'jenis_kelamin' => 'required|in_list[L,P]',
            'umur' => 'required|numeric|greater_than[0]',
            'status' => 'required|in_list[active,inactive]'
        ];

        if (!$this->validate($rules)) {
            return redirect()->back()
                ->withInput()
                ->with('errors', $this->validator->getErrors());
        }

        try {
            $data = [
                'user_id' => $this->request->getPost('user_id'),
                'no_telepon' => $this->request->getPost('no_telepon'),
                'alamat' => $this->request->getPost('alamat'),
                'jenis_kelamin' => $this->request->getPost('jenis_kelamin'),
                'umur' => $this->request->getPost('umur'),
                'status' => $this->request->getPost('status')
            ];

            $this->pelangganModel->insert($data);

            session()->setFlashdata('success', 'Pelanggan berhasil ditambahkan');
            return redirect()->to('/godmode/pelanggan');
        } catch (\Exception $e) {
            return redirect()->back()
                ->withInput()
                ->with('error', 'Gagal menambahkan pelanggan: ' . $e->getMessage());
        }
    }

    public function getEdit($id)
    {
        $pelanggan = $this->pelangganModel->find($id);
        if (!$pelanggan) {
            return redirect()->to('/godmode/pelanggan')
                ->with('error', 'Pelanggan tidak ditemukan');
        }

        $users = auth()->getProvider()->findAll();
        $formattedUsers = [];
        foreach ($users as $user) {
            $formattedUsers[] = [
                'id' => $user->id,
                'name' => $user->username,
                'email' => $user->email
            ];
        }
        return view('pages/godmode/customer/edit', [
            'customer' => $pelanggan,
            'users' => $formattedUsers
        ]);
    }

    public function putUpdate($id)
    {
        $pelanggan = $this->pelangganModel->find($id);
        if (!$pelanggan) {
            return redirect()->to('/godmode/pelanggan')
                ->with('error', 'Pelanggan tidak ditemukan');
        }

        $rules = [
            'user_id' => 'required|numeric|is_not_unique[users.id]',
            'no_telepon' => 'required|numeric|min_length[10]|max_length[15]',
            'alamat' => 'required',
            'jenis_kelamin' => 'required|in_list[L,P]',
            'umur' => 'required|numeric|greater_than[0]',
            'status' => 'required|in_list[active,inactive]'
        ];

        if (!$this->validate($rules)) {
            return redirect()->back()
                ->withInput()
                ->with('errors', $this->validator->getErrors());
        }

        try {
            $data = [
                'user_id' => $this->request->getPost('user_id'),
                'no_telepon' => $this->request->getPost('no_telepon'),
                'alamat' => $this->request->getPost('alamat'),
                'jenis_kelamin' => $this->request->getPost('jenis_kelamin'),
                'umur' => $this->request->getPost('umur'),
                'status' => $this->request->getPost('status')
            ];

            $this->pelangganModel->update($id, $data);

            return redirect()->to('/godmode/pelanggan')
                ->with('success', 'Pelanggan berhasil diperbarui');
        } catch (\Exception $e) {
            return redirect()->back()
                ->withInput()
                ->with('error', 'Gagal memperbarui pelanggan: ' . $e->getMessage());
        }
    }

    public function deletePelanggan($id)
    {
        try {
            $pelanggan = $this->pelangganModel->find($id);
            if (!$pelanggan) {
                if ($this->request->isAJAX()) {
                    return $this->response->setJSON([
                        'success' => false,
                        'message' => 'Pelanggan tidak ditemukan'
                    ]);
                }
                return redirect()->back()
                    ->with('error', 'Pelanggan tidak ditemukan');
            }

            $this->pelangganModel->delete($id);

            if ($this->request->isAJAX()) {
                return $this->response->setJSON([
                    'success' => true,
                    'message' => 'Pelanggan berhasil dihapus'
                ]);
            }
            return redirect()->to('/godmode/pelanggan')
                ->with('success', 'Pelanggan berhasil dihapus');
        } catch (\Exception $e) {
            if ($this->request->isAJAX()) {
                return $this->response->setJSON([
                    'success' => false,
                    'message' => 'Gagal menghapus pelanggan: ' . $e->getMessage()
                ]);
            }
            return redirect()->back()
                ->with('error', 'Gagal menghapus pelanggan: ' . $e->getMessage());
        }
    }
}
