<?php

namespace App\Controllers\Godmode;

use App\Controllers\BaseController;

class Laporan extends BaseController
{
    public function index()
    {
        // Jika ingin mengirim data ke view

        return view('pages/godmode/laporan/index');
    }
}
