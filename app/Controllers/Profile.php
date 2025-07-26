<?php

namespace App\Controllers;

use App\Models\Pelanggan;
use App\Controllers\BaseController;

class Profile extends BaseController
{
    public function getIndex()
    {
        $user = auth()->user();
        if (!$user) {
            return redirect()->to('/login')->with('error', 'Silakan login terlebih dahulu');
        }

        $pelanggan = (new Pelanggan())->where('user_id', $user->id)->first();

        // Deteksi role pengguna
        $groups = $user->getGroups();
        $isAdmin = in_array('admin', $groups) || in_array('pegawai', $groups);

        // Pilih layout berdasarkan role
        $layout = $isAdmin ? 'layouts/admin' : 'layouts/wrapper';

        return view('pages/profile', [
            'user' => $user,
            'pelanggan' => $pelanggan,
            'layout' => $layout,
            'isAdmin' => $isAdmin
        ]);
    }

    public function getEdit()
    {
        $user = auth()->user();
        if (!$user) {
            return redirect()->to('/login')->with('error', 'Silakan login terlebih dahulu');
        }

        $pelanggan = (new Pelanggan())->where('user_id', $user->id)->first();

        // Deteksi role pengguna
        $groups = $user->getGroups();
        $isAdmin = in_array('admin', $groups) || in_array('pegawai', $groups);

        // Pilih layout berdasarkan role
        $layout = $isAdmin ? 'layouts/admin' : 'layouts/wrapper';

        return view('pages/profile_edit', [
            'user' => $user,
            'pelanggan' => $pelanggan,
            'layout' => $layout,
            'isAdmin' => $isAdmin
        ]);
    }

    public function postEdit()
    {
        $user = auth()->user();
        if (!$user) {
            return redirect()->to('/login')->with('error', 'Silakan login terlebih dahulu');
        }

        // Deteksi role pengguna
        $groups = $user->getGroups();
        $isAdmin = in_array('admin', $groups) || in_array('pegawai', $groups);

        $pelangganModel = new Pelanggan();
        $pelanggan = $pelangganModel->where('user_id', $user->id)->first();

        $rules = [
            'username' => 'required|min_length[3]|max_length[30]',
            'email' => 'required|valid_email',
        ];

        // Tambahkan validasi untuk data pelanggan hanya jika bukan admin
        if (!$isAdmin) {
            $rules['alamat'] = 'permit_empty';
            $rules['no_telepon'] = 'permit_empty';
            $rules['jenis_kelamin'] = 'permit_empty|in_list[L,P]';
            $rules['umur'] = 'permit_empty|integer';
        }

        if (!$this->validate($rules)) {
            return redirect()->back()->withInput()->with('errors', $this->validator->getErrors());
        }

        // Update user (username & email)
        $user->username = $this->request->getPost('username');
        $user->email = $this->request->getPost('email');
        $users = auth()->getProvider();
        $users->save($user);

        // Update pelanggan hanya jika bukan admin
        if (!$isAdmin) {
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
        }

        return redirect()->to('/profile')->with('success', 'Profil berhasil diperbarui');
    }
}
