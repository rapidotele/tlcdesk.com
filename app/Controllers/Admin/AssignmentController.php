<?php

namespace App\Controllers\Admin;

use App\Core\View;
use App\Core\Session;
use App\Repositories\AssignmentRepository;
use App\Repositories\UserRepository;
use App\Repositories\VehicleRepository;
use App\Repositories\OrganizationRepository;

class AssignmentController
{
    protected $assignmentRepo;
    protected $userRepo;
    protected $vehicleRepo;
    protected $orgRepo;

    public function __construct()
    {
        $this->assignmentRepo = new AssignmentRepository();
        $this->userRepo = new UserRepository();
        $this->vehicleRepo = new VehicleRepository();
        $this->orgRepo = new OrganizationRepository();
    }

    public function index()
    {
        $assignments = $this->assignmentRepo->findAllWithDetails();
        View::render('admin/assignments/index', [
            'user_name' => Session::get('user_name'),
            'assignments' => $assignments
        ]);
    }

    public function create()
    {
        $orgs = $this->orgRepo->findAll();
        // In a real app we would load drivers/vehicles via AJAX based on selected Org.
        // For MVP, we load ALL and maybe filter with JS or just list all (grouped).
        // Let's just pass all for now.
        $drivers = $this->userRepo->findAllWithOrg();
        $vehicles = $this->vehicleRepo->findAllWithOrg();

        View::render('admin/assignments/create', [
            'user_name' => Session::get('user_name'),
            'organizations' => $orgs,
            'drivers' => $drivers,
            'vehicles' => $vehicles
        ]);
    }

    public function store()
    {
        if (!Session::verifyCsrf($_POST['csrf_token'] ?? '')) {
            die("Invalid CSRF");
        }

        $orgId = $_POST['organization_id'];
        $driverId = $_POST['driver_user_id'];
        $vehicleId = $_POST['vehicle_id'];
        $startDate = $_POST['start_date'];

        $this->assignmentRepo->create($orgId, $driverId, $vehicleId, $startDate);

        Session::flash('success', 'Assignment created');
        header('Location: /admin/assignments');
    }

    public function end($id)
    {
        $this->assignmentRepo->endAssignment($id, date('Y-m-d'));
        Session::flash('success', 'Assignment ended');
        header('Location: /admin/assignments');
    }
}
