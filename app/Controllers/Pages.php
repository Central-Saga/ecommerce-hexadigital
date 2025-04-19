<?php

namespace App\Controllers;

class Pages extends BaseController
{
    public function getHomepage()
    {
        return view('pages/homepage');
    }
}
