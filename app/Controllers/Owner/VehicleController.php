<?php

namespace App\Controllers\Owner;

use App\Core\View;
use App\Core\Session;
use App\Repositories\VehicleRepository;

class VehicleController
{
    protected $vehicleRepo;

    public function __construct()
    {
        $this->vehicleRepo = new VehicleRepository();
    }

    public function index()
    {
        $orgId = Session::get('organization_id');
        $this->vehicleRepo->setOrganizationId($orgId);
        $vehicles = $this->vehicleRepo->findAll();

        View::render('owner/vehicles/index', [
            'user_name' => Session::get('user_name'),
            'vehicles' => $vehicles
        ]);
    }

    public function create()
    {
        View::render('owner/vehicles/create', [
            'user_name' => Session::get('user_name')
        ]);
    }

    public function store()
    {
        if (!Session::verifyCsrf($_POST['csrf_token'] ?? '')) {
            die("Invalid CSRF");
        }

        $orgId = Session::get('organization_id');
        $plate = $_POST['plate'];
        $year = $_POST['year'];
        $make = $_POST['make'];
        $model = $_POST['model'];

        $this->vehicleRepo->create($orgId, $plate, $year, $make, $model);

        Session::flash('success', 'Vehicle added successfully');
        header('Location: /owner/vehicles');
    }
}
