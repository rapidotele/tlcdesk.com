<?php

namespace App\Middleware;

use App\Core\Session;

class DriverMiddleware
{
    public function handle()
    {
        if (!Session::has('user_id')) {
            header('Location: /login');
            return false;
        }

        if (Session::get('user_role') !== 'driver') {
            http_response_code(403);
            echo "403 Forbidden - Driver Access Required";
            return false;
        }

        return true;
    }
}
