<?php

namespace App\Controllers\Install;

use App\Core\Env;
use PDO;

class InstallController
{
    public function index()
    {
        if (file_exists(__DIR__ . '/../../../storage/installed.lock')) {
            die("Application already installed. Delete storage/installed.lock to reinstall.");
        }

        // Simple HTML form for installation
        echo '<!DOCTYPE html>
        <html>
        <head>
            <title>TLCDesk Installer</title>
            <script src="https://cdn.tailwindcss.com"></script>
        </head>
        <body class="bg-gray-100 h-screen flex justify-center items-center">
            <div class="bg-white p-8 rounded shadow-md w-full max-w-md">
                <h1 class="text-2xl font-bold mb-4">Install TLCDesk</h1>
                <form action="/install/run" method="POST" class="space-y-4">
                    <div>
                        <label class="block text-sm font-medium text-gray-700">Database Host</label>
                        <input type="text" name="db_host" value="localhost" class="mt-1 block w-full border border-gray-300 rounded p-2" required>
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-700">Database Name</label>
                        <input type="text" name="db_name" class="mt-1 block w-full border border-gray-300 rounded p-2" required>
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-700">Database User</label>
                        <input type="text" name="db_user" class="mt-1 block w-full border border-gray-300 rounded p-2" required>
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-700">Database Password</label>
                        <input type="password" name="db_pass" class="mt-1 block w-full border border-gray-300 rounded p-2">
                    </div>
                    <hr>
                    <div>
                        <label class="block text-sm font-medium text-gray-700">Admin Email</label>
                        <input type="email" name="admin_email" class="mt-1 block w-full border border-gray-300 rounded p-2" required>
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-700">Admin Password</label>
                        <input type="password" name="admin_pass" class="mt-1 block w-full border border-gray-300 rounded p-2" required>
                    </div>
                    <button type="submit" class="w-full bg-blue-600 text-white p-2 rounded hover:bg-blue-700">Install</button>
                </form>
            </div>
        </body>
        </html>';
    }

    public function install()
    {
        if (file_exists(__DIR__ . '/../../../storage/installed.lock')) {
            die("Already installed.");
        }

        $host = $_POST['db_host'];
        $name = $_POST['db_name'];
        $user = $_POST['db_user'];
        $pass = $_POST['db_pass'];
        $adminEmail = $_POST['admin_email'];
        $adminPass = $_POST['admin_pass'];

        // 1. Verify DB Connection
        try {
            $dsn = "mysql:host=$host;dbname=$name;charset=utf8mb4";
            $pdo = new PDO($dsn, $user, $pass, [PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION]);
        } catch (\Exception $e) {
            die("Database connection failed: " . $e->getMessage());
        }

        // 2. Write .env
        $envContent = "DB_HOST=$host\nDB_DATABASE=$name\nDB_USERNAME=$user\nDB_PASSWORD=$pass\nAPP_ENV=production\nAPP_URL=http://" . $_SERVER['HTTP_HOST'];
        file_put_contents(__DIR__ . '/../../../config/.env', $envContent);

        // 3. Run Migrations
        $sql = file_get_contents(__DIR__ . '/../../../database/migrations/001_init.sql');
        try {
            $pdo->exec($sql);
        } catch (\Exception $e) {
            die("Migration failed: " . $e->getMessage());
        }

        // 4. Create Super Admin & Org
        $passHash = password_hash($adminPass, PASSWORD_BCRYPT);

        // Default Org
        $pdo->exec("INSERT INTO organizations (name, theme_id, locale) VALUES ('System Admin', 'default', 'en')");
        $orgId = $pdo->lastInsertId();

        // Admin User
        $stmt = $pdo->prepare("INSERT INTO users (organization_id, name, email, password_hash, role, locale) VALUES (?, 'Super Admin', ?, ?, 'superadmin', 'en')");
        $stmt->execute([$orgId, $adminEmail, $passHash]);

        // 5. Lock
        file_put_contents(__DIR__ . '/../../../storage/installed.lock', date('Y-m-d H:i:s'));

        echo "Installation successful! <a href='/login'>Login here</a>";
    }
}
