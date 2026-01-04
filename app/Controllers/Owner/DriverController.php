<?php

namespace App\Controllers\Owner;

use App\Core\View;
use App\Core\Session;
use App\Repositories\UserRepository;

class DriverController
{
    protected $userRepo;

    public function __construct()
    {
        $this->userRepo = new UserRepository();
    }

    public function index()
    {
        $orgId = Session::get('organization_id');
        $this->userRepo->setOrganizationId($orgId);
        $drivers = $this->userRepo->findAllByOrgAndRole($orgId, 'driver');

        View::render('owner/drivers/index', [
            'user_name' => Session::get('user_name'),
            'drivers' => $drivers
        ]);
    }

    public function create()
    {
        View::render('owner/drivers/create', [
            'user_name' => Session::get('user_name')
        ]);
    }

    public function store()
    {
        if (!Session::verifyCsrf($_POST['csrf_token'] ?? '')) {
            die("Invalid CSRF");
        }

        $orgId = Session::get('organization_id');
        $name = $_POST['name'];
        $email = $_POST['email'];
        $password = $_POST['password']; // In real app, might send invite email with reset link

        // Validation: Check if email exists?
        // Basic MVP logic
        $hash = password_hash($password, PASSWORD_BCRYPT);

        try {
            $this->userRepo->create($orgId, $name, $email, $hash, 'driver');
            Session::flash('success', 'Driver created successfully');
        } catch (\Exception $e) {
            Session::flash('error', 'Error creating driver (Email might be taken)');
        }

        header('Location: /owner/drivers');
    }
}
