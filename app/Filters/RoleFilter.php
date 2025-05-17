<?php

namespace App\Filters;

use CodeIgniter\HTTP\RequestInterface;
use CodeIgniter\HTTP\ResponseInterface;
use CodeIgniter\Filters\FilterInterface;
use CodeIgniter\Services;

class RoleFilter implements FilterInterface
{
    public function before(RequestInterface $request, $arguments = null)
    {
        // Cek apakah user sudah login
        if (!auth()->loggedIn()) {
            // Redirect ke login jika belum login
            return redirect()->to('/login');
        }

        // Ambil user
        $user = auth()->user();
        if (!$user) {
            return redirect()->to('/login');
        }

        // Ambil group/role user
        $groups = $user->getGroups();
        if (!in_array('admin', $groups) && !in_array('pegawai', $groups)) {
            // Jika bukan admin/pegawai, tampilkan error 403 dengan view khusus
            return service('response')->setStatusCode(403)->setBody(view('errors/html/error_403'));
        }
    }

    public function after(RequestInterface $request, ResponseInterface $response, $arguments = null)
    {
        // Tidak perlu aksi setelah
    }
}
