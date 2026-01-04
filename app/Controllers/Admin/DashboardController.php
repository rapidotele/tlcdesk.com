<?php

namespace App\Controllers\Admin;

use App\Core\View;
use App\Core\Session;

class DashboardController
{
    public function index()
    {
        View::render('admin/dashboard', [
            'user_name' => Session::get('user_name')
        ]);
    }
}
