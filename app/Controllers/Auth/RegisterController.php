<?php

namespace App\Controllers\Auth;

use CodeIgniter\Shield\Controllers\RegisterController as ShieldRegister;
use App\Models\Pelanggan;

class RegisterController extends ShieldRegister
{
    public function registerAction(): \CodeIgniter\HTTP\RedirectResponse
    {
        // Jalankan proses register bawaan Shield
        $response = parent::registerAction();

        // Jika user berhasil register dan login
        $user = auth()->user();
        if ($user) {
            $pelangganModel = new Pelanggan();
            $pelangganModel->insert([
                'user_id'      => $user->id,
                'alamat'       => $this->request->getPost('alamat'),
                'status'       => 'active',
                'no_telepon'   => $this->request->getPost('no_telepon'),
                'jenis_kelamin' => $this->request->getPost('jenis_kelamin'),
                'umur'         => $this->request->getPost('umur'),
            ]);
        }

        return $response;
    }
}
