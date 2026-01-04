<?php

namespace App\Middleware;

use App\Core\Session;

class OwnerMiddleware
{
    public function handle()
    {
        if (!Session::has('user_id')) {
            header('Location: /login');
            return false;
        }

        if (Session::get('user_role') !== 'org_admin') {
            http_response_code(403);
            echo "403 Forbidden - Owner Access Required";
            return false;
        }

        return true;
    }
}
