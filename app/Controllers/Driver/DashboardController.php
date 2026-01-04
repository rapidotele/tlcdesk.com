<?php

namespace App\Controllers\Driver;

use App\Core\View;
use App\Core\Session;
use App\Repositories\CategoryRepository;
use App\Repositories\TransactionRepository;

class DashboardController
{
    protected $categoryRepo;
    protected $transactionRepo;

    public function __construct()
    {
        $this->categoryRepo = new CategoryRepository();
        $this->transactionRepo = new TransactionRepository();
    }

    public function index()
    {
        $orgId = Session::get('organization_id');

        $this->categoryRepo->setOrganizationId($orgId);
        $this->transactionRepo->setOrganizationId($orgId);

        $categories = $this->categoryRepo->findAllByOrg($orgId);

        // Get Totals
        $month = date('m');
        $year = date('Y');
        $totals = $this->transactionRepo->getMonthlyTotals($orgId, $month, $year);
        $totals['profit'] = $totals['income'] - $totals['expense'];

        View::render('driver/dashboard', [
            'user_name' => Session::get('user_name'),
            'categories' => $categories,
            'totals' => $totals
        ]);
    }
}
