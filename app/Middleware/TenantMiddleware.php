<?php

namespace App\Middleware;

use App\Core\Session;
use App\Core\Database;

class TenantMiddleware
{
    public function handle()
    {
        // 1. Ensure User is Logged In
        if (!Session::has('user_id')) {
            header('Location: /login');
            return false;
        }

        // 2. Validate Session Integrity
        // We ensure that the organization_id in session actually belongs to the user in the DB.
        // This prevents session tampering if we were using client-side cookies for this (we use server-side sessions, but still good practice).

        $userId = Session::get('user_id');
        $orgId = Session::get('organization_id');

        $db = Database::getInstance();
        $stmt = $db->getConnection()->prepare("SELECT organization_id FROM users WHERE id = ?");
        $stmt->execute([$userId]);
        $user = $stmt->fetch();

        if (!$user || $user['organization_id'] != $orgId) {
            // Security Violation or Data Drift
            Session::destroy();
            header('Location: /login');
            return false;
        }

        // 3. (Optional) Check if Org is Active/Enabled if we had that flag

        return true;
    }
}
