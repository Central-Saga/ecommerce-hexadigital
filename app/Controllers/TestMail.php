<?php

namespace App\Controllers;

use App\Controllers\BaseController;
use CodeIgniter\HTTP\ResponseInterface;

class TestMail extends BaseController
{
    public function getSend()
    {
        $email = \Config\Services::email();
        $email->setTo('test@gmail.com');
        $email->setSubject('Tes Email Improved Routing');
        $email->setMessage('Coba improved auto routing!');

        if ($email->send()) {
            return "Email terkirim!";
        } else {
            return $email->printDebugger(['headers']);
        }
    }
}
