<?php

namespace App\Controllers;

use App\Models\Pelanggan;
use CodeIgniter\Controller;

class Profile extends Controller
{
    public function getIndex()
    {
        $user = auth()->user();
        if (!$user) {
            return redirect()->to('/login')->with('error', 'Silakan login terlebih dahulu');
        }
        $pelanggan = (new Pelanggan())->where('user_id', $user->id)->first();
        return view('pages/profile', [
            'user' => $user,
            'pelanggan' => $pelanggan
        ]);
    }

    public function getEdit()
    {
        $user = auth()->user();
        if (!$user) {
            return redirect()->to('/login')->with('error', 'Silakan login terlebih dahulu');
        }
        $pelanggan = (new Pelanggan())->where('user_id', $user->id)->first();
        return view('pages/profile_edit', [
            'user' => $user,
            'pelanggan' => $pelanggan
        ]);
    }

    public function postEdit()
    {
        $user = auth()->user();
        if (!$user) {
            return redirect()->to('/login')->with('error', 'Silakan login terlebih dahulu');
        }
        $pelangganModel = new Pelanggan();
        $pelanggan = $pelangganModel->where('user_id', $user->id)->first();

        $rules = [
            'username' => 'required|min_length[3]|max_length[30]',
            'email' => 'required|valid_email',
            'alamat' => 'permit_empty',
            'no_telepon' => 'permit_empty',
            'jenis_kelamin' => 'permit_empty|in_list[L,P]',
            'umur' => 'permit_empty|integer',
        ];
        if (!$this->validate($rules)) {
            return redirect()->back()->withInput()->with('errors', $this->validator->getErrors());
        }

        // Update user (username & email)
        $user->username = $this->request->getPost('username');
        $user->email = $this->request->getPost('email');
        $users = auth()->getProvider();
        $users->save($user);

        // Update pelanggan
        $dataPelanggan = [
            'alamat' => $this->request->getPost('alamat'),
            'no_telepon' => $this->request->getPost('no_telepon'),
            'jenis_kelamin' => $this->request->getPost('jenis_kelamin'),
            'umur' => $this->request->getPost('umur'),
        ];
        if ($pelanggan) {
            $pelangganModel->update($pelanggan['id'], $dataPelanggan);
        } else {
            $dataPelanggan['user_id'] = $user->id;
            $pelangganModel->insert($dataPelanggan);
        }

        return redirect()->to('/profile')->with('success', 'Profil berhasil diperbarui');
    }
}
