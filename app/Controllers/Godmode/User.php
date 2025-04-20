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
                'role' => $user->groups()[0] ?? 'user',
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
        log_message('info', '[User::postStore] Starting user creation process');
        log_message('info', '[User::postStore] Request data: ' . json_encode($this->request->getPost()));

        $rules = [
            'username' => 'required|min_length[3]|max_length[30]|is_unique[auth_identities.secret]',
            'email' => 'required|valid_email|is_unique[auth_identities.secret]',
            'password' => 'required|min_length[8]',
            'password_confirm' => 'required|matches[password]',
            'role' => 'required|in_list[admin,user]',
            'status' => 'required|in_list[active,inactive]'
        ];

        if (!$this->validate($rules)) {
            log_message('error', '[User::postStore] Validation failed: ' . json_encode($this->validator->getErrors()));
            return redirect()->back()
                ->withInput()
                ->with('errors', $this->validator->getErrors());
        }

        try {
            // Buat user baru
            $users = auth()->getProvider();
            log_message('info', '[User::postStore] Creating new user with data: ' . json_encode($this->request->getPost()));

            $user = new ShieldUser([
                'username' => $this->request->getPost('username'),
                'email' => $this->request->getPost('email'),
                'password' => $this->request->getPost('password'),
                'active' => $this->request->getPost('status') === 'active' ? 1 : 0
            ]);

            // Simpan user terlebih dahulu
            if (!$users->save($user)) {
                log_message('error', '[User::postStore] Failed to save user');
                throw new \Exception('Gagal menyimpan user');
            }

            // Dapatkan user yang baru dibuat
            $savedUser = $users->findById($users->getInsertID());
            if (!$savedUser) {
                throw new \Exception('Gagal mendapatkan user yang baru dibuat');
            }

            // Tambahkan role setelah user berhasil disimpan
            if (!$savedUser->addGroup($this->request->getPost('role'))) {
                log_message('error', '[User::postStore] Failed to add role');
                throw new \Exception('Gagal menambahkan role');
            }

            log_message('info', '[User::postStore] User created successfully, redirecting to index');

            // Set flash message dan redirect
            session()->setFlashdata('success', 'User berhasil ditambahkan');
            return redirect()->to('/godmode/user');
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
            'role' => $user->groups()[0] ?? 'user',
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
            $users = auth()->getProvider();

            // Update user data
            $user->username = $this->request->getPost('username');
            $user->email = $this->request->getPost('email');
            $user->active = $this->request->getPost('status') === 'active' ? 1 : 0;

            if ($this->request->getPost('password')) {
                $user->password = $this->request->getPost('password');
            }

            $users->save($user);

            // Update role
            $currentRole = $user->groups()[0] ?? '';
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
