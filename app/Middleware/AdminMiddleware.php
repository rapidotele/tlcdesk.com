<?php

namespace App\Middleware;

use App\Core\Session;

class AdminMiddleware
{
    public function handle()
    {
        if (!Session::has('user_id')) {
            header('Location: /login');
            return false;
        }

        if (Session::get('user_role') !== 'superadmin') {
            http_response_code(403);
            echo "403 Forbidden - Admin Access Required";
            return false;
        }

        return true;
    }
}
