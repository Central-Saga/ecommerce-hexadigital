<?php

namespace App\Controllers\Godmode;

use App\Controllers\BaseController;
use CodeIgniter\HTTP\ResponseInterface;

class Dashboard extends BaseController
{
    public function getIndex()
    {
        return view('pages/godmode/dashboard');
    }
}
