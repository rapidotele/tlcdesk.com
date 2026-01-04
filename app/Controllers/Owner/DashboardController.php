<?php

namespace App\Controllers\Owner;

use App\Core\View;
use App\Core\Session;
use App\Repositories\VehicleRepository;
use App\Repositories\UserRepository;

class DashboardController
{
    protected $vehicleRepo;
    protected $userRepo;

    public function __construct()
    {
        $this->vehicleRepo = new VehicleRepository();
        $this->userRepo = new UserRepository();
    }

    public function index()
    {
        $orgId = Session::get('organization_id');
        $this->vehicleRepo->setOrganizationId($orgId);
        $this->userRepo->setOrganizationId($orgId);

        $vehicleCount = $this->vehicleRepo->countByOrg($orgId);
        $driverCount = $this->userRepo->countByOrgAndRole($orgId, 'driver');

        View::render('owner/dashboard', [
            'user_name' => Session::get('user_name'),
            'vehicle_count' => $vehicleCount,
            'driver_count' => $driverCount
        ]);
    }
}
