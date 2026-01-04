<?php

namespace App\Controllers\Admin;

use App\Core\View;
use App\Core\Session;
use App\Repositories\UserRepository;
use App\Repositories\OrganizationRepository;

class UserController
{
    protected $userRepo;
    protected $orgRepo;

    public function __construct()
    {
        $this->userRepo = new UserRepository();
        $this->orgRepo = new OrganizationRepository();
    }

    public function index()
    {
        $users = $this->userRepo->findAllWithOrgAndProfile();
        View::render('admin/users/index', [
            'user_name' => Session::get('user_name'),
            'users' => $users
        ]);
    }

    public function create()
    {
        $orgs = $this->orgRepo->findAll();
        View::render('admin/users/create', [
            'user_name' => Session::get('user_name'),
            'organizations' => $orgs
        ]);
    }

    public function store()
    {
        if (!Session::verifyCsrf($_POST['csrf_token'] ?? '')) {
            die("Invalid CSRF");
        }

        $orgId = $_POST['organization_id'];
        $name = $_POST['name'];
        $email = $_POST['email'];
        $role = $_POST['role'];
        $status = $_POST['status'];
        $password = $_POST['password'];

        $hash = password_hash($password, PASSWORD_BCRYPT);
        $userId = $this->userRepo->create($orgId, $name, $email, $hash, $role, $status);

        if ($role === 'driver') {
            $this->userRepo->saveProfile(
                $userId,
                $_POST['tlc_license'] ?? null,
                $_POST['dmv_license'] ?? null,
                $_POST['dmv_expiration'] ?? null
            );
        }

        Session::flash('success', 'User created successfully');
        header('Location: /admin/users');
    }

    public function edit($id)
    {
        $user = $this->userRepo->findWithProfile($id);
        if (!$user) {
            die("User not found");
        }
        $orgs = $this->orgRepo->findAll();
        View::render('admin/users/edit', [
            'user_name' => Session::get('user_name'),
            'user' => $user,
            'organizations' => $orgs
        ]);
    }

    public function update($id)
    {
        if (!Session::verifyCsrf($_POST['csrf_token'] ?? '')) {
            die("Invalid CSRF");
        }

        $data = [
            'name' => $_POST['name'],
            'email' => $_POST['email'],
            'role' => $_POST['role'],
            'status' => $_POST['status'],
            'organization_id' => $_POST['organization_id']
        ];

        if (!empty($_POST['password'])) {
            $data['password_hash'] = password_hash($_POST['password'], PASSWORD_BCRYPT);
        }

        $this->userRepo->update($id, $data);

        if ($_POST['role'] === 'driver') {
            $this->userRepo->saveProfile(
                $id,
                $_POST['tlc_license'] ?? null,
                $_POST['dmv_license'] ?? null,
                $_POST['dmv_expiration'] ?? null
            );
        }

        Session::flash('success', 'User updated successfully');
        header('Location: /admin/users');
    }
}
