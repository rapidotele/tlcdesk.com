<?php

namespace App\Controllers\Driver;

use App\Core\View;
use App\Core\Session;
use App\Repositories\TransactionRepository;
use App\Repositories\CategoryRepository;

class BookkeepingController
{
    protected $transactionRepo;
    protected $categoryRepo;

    public function __construct()
    {
        $this->transactionRepo = new TransactionRepository();
        $this->categoryRepo = new CategoryRepository();
    }

    public function index()
    {
        $orgId = Session::get('organization_id');
        $userId = Session::get('user_id');

        $this->transactionRepo->setOrganizationId($orgId);
        $this->categoryRepo->setOrganizationId($orgId);

        $categories = $this->categoryRepo->findAllByOrg($orgId);

        View::render('driver/dashboard', [
            'user_name' => Session::get('user_name'),
            'categories' => $categories
        ]);
    }

    public function add()
    {
         if (!Session::verifyCsrf($_POST['csrf_token'] ?? '')) {
            die("Invalid CSRF");
        }

        $orgId = Session::get('organization_id');
        $userId = Session::get('user_id');

        $amount = $_POST['amount'];
        $type = $_POST['type'];
        $categoryId = $_POST['category_id'];
        $date = $_POST['date'];
        $desc = $_POST['description'];

        // Validation: Ensure category belongs to org
        $this->categoryRepo->setOrganizationId($orgId);
        $cat = $this->categoryRepo->find($categoryId);

        if (!$cat) {
             Session::flash('error', 'Invalid Category');
             header('Location: /driver/dashboard');
             exit;
        }

        $this->transactionRepo->create($orgId, $userId, $categoryId, $type, $amount, $date, $desc);

        Session::flash('success', 'Transaction added');
        header('Location: /driver/dashboard');
    }
}
