<?php

namespace App\Controllers\Godmode;

use App\Controllers\BaseController;
use CodeIgniter\HTTP\ResponseInterface;
use CodeIgniter\Shield\Entities\User as ShieldUser;

class User extends BaseController
{
    protected $helpers = ['form'];

    public function getIndex()
    {
        // Ambil semua user dari database
        $users = auth()->getProvider()->findAll();

        // Format data user untuk view
        $formattedUsers = [];
        foreach ($users as $user) {
            $formattedUsers[] = [
                'id' => $user->id,
                'name' => $user->username,
                'email' => $user->email,
                'role' => $user->getGroups()[0] ?? 'user',
                'status' => $user->active ? 'active' : 'inactive'
            ];
        }

        return view('pages/godmode/user/index', [
            'users' => $formattedUsers
        ]);
    }

    public function getCreate()
    {
        return view('pages/godmode/user/create');
    }

    public function postStore()
    {
        $rules = [
            'username' => 'required|min_length[3]|max_length[30]|is_unique[auth_identities.secret]',
            'email' => 'required|valid_email|is_unique[auth_identities.secret]',
            'password' => 'required|min_length[8]',
            'password_confirm' => 'required|matches[password]',
            'role' => 'required|in_list[admin,user]',
            'status' => 'required|in_list[active,inactive]'
        ];

        if (!$this->validate($rules)) {
            return redirect()->back()
                ->withInput()
                ->with('errors', $this->validator->getErrors());
        }

        try {
            // Buat user baru
            $users = model('UserModel');

            $user = new ShieldUser([
                'username' => $this->request->getPost('username'),
                'email' => $this->request->getPost('email'),
                'password' => $this->request->getPost('password'),
                'active' => $this->request->getPost('status') === 'active' ? 1 : 0
            ]);

            $users->save($user);

            // Tambahkan email identity
            $identities = model('IdentityModel');
            $identities->insert([
                'user_id' => $user->id,
                'type' => 'email_password',
                'secret' => $this->request->getPost('email'),
                'secret2' => $user->password_hash,
            ]);

            // Tambahkan role
            $user->addGroup($this->request->getPost('role'));

            // Set flash message dan redirect
            return redirect()->to('/godmode/user')
                ->with('success', 'User berhasil ditambahkan');
        } catch (\Exception $e) {
            log_message('error', '[User::postStore] Error: ' . $e->getMessage());
            return redirect()->back()
                ->withInput()
                ->with('error', 'Gagal menambahkan user: ' . $e->getMessage());
        }
    }

    public function getEdit($id)
    {
        $user = auth()->getProvider()->findById($id);
        if (!$user) {
            return redirect()->to('/godmode/user')
                ->with('error', 'User tidak ditemukan');
        }

        $formattedUser = [
            'id' => $user->id,
            'name' => $user->username,
            'email' => $user->email,
            'role' => $user->getGroups()[0] ?? 'user',
            'status' => $user->active ? 'active' : 'inactive'
        ];

        return view('pages/godmode/user/edit', [
            'user' => $formattedUser
        ]);
    }

    public function putUpdate($id)
    {
        $user = auth()->getProvider()->findById($id);
        if (!$user) {
            return redirect()->to('/godmode/user')
                ->with('error', 'User tidak ditemukan');
        }

        $rules = [
            'username' => "required|min_length[3]|max_length[30]|is_unique[auth_identities.secret,id,{$id}]",
            'email' => "required|valid_email|is_unique[auth_identities.secret,id,{$id}]",
            'role' => 'required|in_list[admin,user]',
            'status' => 'required|in_list[active,inactive]'
        ];

        if ($this->request->getPost('password')) {
            $rules['password'] = 'min_length[8]';
            $rules['password_confirm'] = 'matches[password]';
        }

        if (!$this->validate($rules)) {
            return redirect()->back()
                ->withInput()
                ->with('errors', $this->validator->getErrors());
        }

        try {
            $users = model('UserModel');

            // Update user data
            $user->username = $this->request->getPost('username');
            $user->email = $this->request->getPost('email');
            $user->active = $this->request->getPost('status') === 'active' ? 1 : 0;

            if ($this->request->getPost('password')) {
                $user->password = $this->request->getPost('password');
            }

            $users->save($user);

            // Update email identity
            $identities = model('IdentityModel');
            $identity = $identities->where('user_id', $user->id)->first();
            if ($identity) {
                $identities->update($identity->id, [
                    'secret' => $this->request->getPost('email'),
                    'secret2' => $user->password_hash,
                ]);
            }

            // Update role
            $currentRole = $user->getGroups()[0] ?? '';
            if ($currentRole) {
                $user->removeGroup($currentRole);
            }
            $user->addGroup($this->request->getPost('role'));

            return redirect()->to('/godmode/user')
                ->with('success', 'User berhasil diperbarui');
        } catch (\Exception $e) {
            log_message('error', '[User::putUpdate] Error: ' . $e->getMessage());
            return redirect()->back()
                ->withInput()
                ->with('error', 'Gagal memperbarui user: ' . $e->getMessage());
        }
    }

    public function deleteUser($id)
    {
        if ($id == auth()->id()) {
            if ($this->request->isAJAX()) {
                return $this->response->setJSON([
                    'success' => false,
                    'message' => 'Tidak dapat menghapus akun sendiri'
                ]);
            }
            return redirect()->back()
                ->with('error', 'Tidak dapat menghapus akun sendiri');
        }

        try {
            $users = auth()->getProvider();
            $user = $users->findById($id);

            if (!$user) {
                if ($this->request->isAJAX()) {
                    return $this->response->setJSON([
                        'success' => false,
                        'message' => 'User tidak ditemukan'
                    ]);
                }
                return redirect()->back()
                    ->with('error', 'User tidak ditemukan');
            }

            // Hapus user
            $users->delete($id, true);

            if ($this->request->isAJAX()) {
                return $this->response->setJSON([
                    'success' => true,
                    'message' => 'User berhasil dihapus'
                ]);
            }
            return redirect()->to('/godmode/user')
                ->with('success', 'User berhasil dihapus');
        } catch (\Exception $e) {
            log_message('error', '[User::delete] Error: ' . $e->getMessage());
            if ($this->request->isAJAX()) {
                return $this->response->setJSON([
                    'success' => false,
                    'message' => 'Gagal menghapus user: ' . $e->getMessage()
                ]);
            }
            return redirect()->back()
                ->with('error', 'Gagal menghapus user: ' . $e->getMessage());
        }
    }
}
