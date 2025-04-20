<?php

namespace App\Controllers\Godmode;

use App\Controllers\BaseController;
use CodeIgniter\HTTP\ResponseInterface;
use Config\Services;
use CodeIgniter\Shield\Models\GroupModel;

class Role extends BaseController
{
    protected $helpers = ['form'];

    public function getIndex()
    {
        // Gunakan GroupModel untuk mengakses groups
        $groups = model(GroupModel::class);
        $authorization = auth()->getAuthorizer();

        // Format data role untuk view
        $formattedRoles = [];
        foreach ($groups->findAll() as $role) {
            $formattedRoles[] = [
                'id' => $role->id,
                'name' => $role->name,
                'description' => $role->description,
                'permissions' => $authorization->groupPermissions($role->name),
                'status' => $role->active ? 'active' : 'inactive'
            ];
        }

        return view('pages/godmode/role/index', [
            'roles' => $formattedRoles
        ]);
    }

    public function getCreate()
    {
        return view('pages/godmode/role/create');
    }

    public function postStore()
    {
        $rules = [
            'name' => 'required|min_length[3]|max_length[30]|is_unique[auth_groups.name]',
            'description' => 'required|min_length[3]|max_length[255]',
            'permissions' => 'required',
            'status' => 'required|in_list[active,inactive]'
        ];

        if (!$this->validate($rules)) {
            return redirect()->back()
                ->withInput()
                ->with('errors', $this->validator->getErrors());
        }

        try {
            // Buat role baru
            $groups = auth()->getProvider()->getGroups();
            $role = new \CodeIgniter\Shield\Entities\Group([
                'name' => $this->request->getPost('name'),
                'description' => $this->request->getPost('description'),
                'permissions' => $this->request->getPost('permissions'),
                'active' => $this->request->getPost('status') === 'active' ? 1 : 0
            ]);

            if (!$groups->save($role)) {
                throw new \Exception('Gagal menyimpan role');
            }

            session()->setFlashdata('success', 'Role berhasil ditambahkan');
            return redirect()->to('/godmode/role');
        } catch (\Exception $e) {
            return redirect()->back()
                ->withInput()
                ->with('error', 'Gagal menambahkan role: ' . $e->getMessage());
        }
    }

    public function getEdit($id)
    {
        $role = auth()->getProvider()->getGroups()->find($id);
        if (!$role) {
            return redirect()->to('/godmode/role')
                ->with('error', 'Role tidak ditemukan');
        }

        $formattedRole = [
            'id' => $role->id,
            'name' => $role->name,
            'description' => $role->description,
            'permissions' => $role->permissions,
            'status' => $role->active ? 'active' : 'inactive'
        ];

        return view('pages/godmode/role/edit', [
            'role' => $formattedRole
        ]);
    }

    public function putUpdate($id)
    {
        $role = auth()->getProvider()->getGroups()->find($id);
        if (!$role) {
            return redirect()->to('/godmode/role')
                ->with('error', 'Role tidak ditemukan');
        }

        $rules = [
            'name' => "required|min_length[3]|max_length[30]|is_unique[auth_groups.name,id,{$id}]",
            'description' => 'required|min_length[3]|max_length[255]',
            'permissions' => 'required',
            'status' => 'required|in_list[active,inactive]'
        ];

        if (!$this->validate($rules)) {
            return redirect()->back()
                ->withInput()
                ->with('errors', $this->validator->getErrors());
        }

        try {
            $groups = auth()->getProvider()->getGroups();

            // Update role data
            $role->name = $this->request->getPost('name');
            $role->description = $this->request->getPost('description');
            $role->permissions = $this->request->getPost('permissions');
            $role->active = $this->request->getPost('status') === 'active' ? 1 : 0;

            $groups->save($role);

            return redirect()->to('/godmode/role')
                ->with('success', 'Role berhasil diperbarui');
        } catch (\Exception $e) {
            return redirect()->back()
                ->withInput()
                ->with('error', 'Gagal memperbarui role: ' . $e->getMessage());
        }
    }

    public function deleteRole($id)
    {
        if ($this->request->isAJAX()) {
            try {
                $groups = auth()->getProvider()->getGroups();
                $role = $groups->find($id);

                if (!$role) {
                    return $this->response->setJSON([
                        'success' => false,
                        'message' => 'Role tidak ditemukan'
                    ]);
                }

                // Hapus role
                $groups->delete($id, true);

                return $this->response->setJSON([
                    'success' => true,
                    'message' => 'Role berhasil dihapus'
                ]);
            } catch (\Exception $e) {
                return $this->response->setJSON([
                    'success' => false,
                    'message' => 'Gagal menghapus role: ' . $e->getMessage()
                ]);
            }
        }

        return redirect()->back()
            ->with('error', 'Metode tidak diizinkan');
    }
}
