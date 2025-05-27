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
}
