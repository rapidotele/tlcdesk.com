<?php

namespace App\Controllers\Admin;

use App\Core\View;
use App\Core\Session;
use App\Repositories\VehicleRepository;
use App\Repositories\OrganizationRepository;

class VehicleController
{
    protected $vehicleRepo;
    protected $orgRepo;

    public function __construct()
    {
        $this->vehicleRepo = new VehicleRepository();
        $this->orgRepo = new OrganizationRepository();
    }

    public function index()
    {
        $vehicles = $this->vehicleRepo->findAllWithOrg();
        View::render('admin/vehicles/index', [
            'user_name' => Session::get('user_name'),
            'vehicles' => $vehicles
        ]);
    }

    public function create()
    {
        $orgs = $this->orgRepo->findAll();
        View::render('admin/vehicles/create', [
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
        $plate = $_POST['plate'];
        $year = $_POST['year'];
        $make = $_POST['make'];
        $model = $_POST['model'];

        $this->vehicleRepo->create($orgId, $plate, $year, $make, $model);

        Session::flash('success', 'Vehicle created successfully');
        header('Location: /admin/vehicles');
    }

    public function edit($id)
    {
        $vehicle = $this->vehicleRepo->find($id);
        if (!$vehicle) {
            die("Vehicle not found");
        }
        $orgs = $this->orgRepo->findAll();

        View::render('admin/vehicles/edit', [
            'user_name' => Session::get('user_name'),
            'vehicle' => $vehicle,
            'organizations' => $orgs
        ]);
    }

    public function update($id)
    {
        if (!Session::verifyCsrf($_POST['csrf_token'] ?? '')) {
            die("Invalid CSRF");
        }

        $data = [
            'plate' => $_POST['plate'],
            'year' => $_POST['year'],
            'make' => $_POST['make'],
            'model' => $_POST['model'],
            'organization_id' => $_POST['organization_id']
        ];

        $this->vehicleRepo->update($id, $data);

        Session::flash('success', 'Vehicle updated successfully');
        header('Location: /admin/vehicles');
    }

    public function delete($id)
    {
         $this->vehicleRepo->delete($id);
         Session::flash('success', 'Vehicle deleted');
         header('Location: /admin/vehicles');
    }
}
