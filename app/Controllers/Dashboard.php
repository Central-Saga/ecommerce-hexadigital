<?php

namespace App\Controllers;

use App\Controllers\BaseController;
use CodeIgniter\HTTP\ResponseInterface;

class Dashboard extends BaseController
{
    public function getIndex()
    {
        return view('pages/godmode/dashboard');
    }
}
