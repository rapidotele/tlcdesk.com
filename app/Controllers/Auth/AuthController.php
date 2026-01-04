<?php

namespace App\Controllers\Auth;

use App\Core\View;
use App\Core\Session;
use App\Repositories\UserRepository;
use App\Repositories\OrganizationRepository;

class AuthController
{
    protected $userRepo;
    protected $orgRepo;

    public function __construct()
    {
        $this->userRepo = new UserRepository();
        $this->orgRepo = new OrganizationRepository();
    }

    public function loginForm()
    {
        if (Session::has('user_id')) {
            header('Location: /');
            exit;
        }
        View::render('auth/login');
    }

    public function login()
    {
        // CSRF Check
        if (!Session::verifyCsrf($_POST['csrf_token'] ?? '')) {
            die("Invalid CSRF Token");
        }

        $email = $_POST['email'] ?? '';
        $password = $_POST['password'] ?? '';

        $user = $this->userRepo->findByEmail($email);

        if ($user && password_verify($password, $user['password_hash'])) {
            Session::set('user_id', $user['id']);
            Session::set('organization_id', $user['organization_id']);
            Session::set('user_role', $user['role']);
            Session::set('user_name', $user['name']);

            // Get Organization theme
            $org = $this->orgRepo->getSettings($user['organization_id']);

            Session::set('theme', $org['theme_id'] ?? 'default');
            Session::set('locale', $user['locale'] ?? $org['locale'] ?? 'en');

            header('Location: /');
            exit;
        }

        Session::flash('error', 'Invalid credentials');
        header('Location: /login');
    }

    public function logout()
    {
        Session::destroy();
        header('Location: /login');
    }

    public function registerForm()
    {
        View::render('auth/register');
    }

    public function register()
    {
        if (!Session::verifyCsrf($_POST['csrf_token'] ?? '')) {
            die("Invalid CSRF Token");
        }

        $email = $_POST['email'];
        $pass = $_POST['password'];
        $name = $_POST['name'];

        // 1. Create Org
        $orgId = $this->orgRepo->create("$name's Team");

        // 2. Create User
        $hash = password_hash($pass, PASSWORD_BCRYPT);
        try {
            $this->userRepo->create($orgId, $name, $email, $hash, 'driver');
            header('Location: /login');
        } catch (\Exception $e) {
             // Rollback logic would be good here
             die("Error: " . $e->getMessage());
        }
    }
}
