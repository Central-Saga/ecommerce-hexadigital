<?php

namespace App\Controllers;

class Homepage extends BaseController
{
    public function getIndex()
    {
        return view('pages/homepage');
    }
}
