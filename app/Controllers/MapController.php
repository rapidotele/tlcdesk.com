<?php

namespace App\Controllers;

use App\Core\View;
use App\Core\Session;

class MapController
{
    public function index()
    {
        View::render('maps/index', [
            'user_name' => Session::get('user_name')
        ]);
    }
}
