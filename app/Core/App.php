<?php

namespace App\Core;

class App
{
    public static function run()
    {
        // Load Environment
        Env::load(__DIR__ . '/../../config/.env');

        // Start Session
        Session::start();

        // Setup I18n
        $locale = Session::get('locale', 'en');
        I18n::setLocale($locale);

        // Router Setup
        $router = new Router();
        self::registerRoutes($router);

        // Dispatch
        $router->dispatch($_SERVER['REQUEST_URI'], $_SERVER['REQUEST_METHOD']);
    }

    protected static function registerRoutes(Router $router)
    {
        // Lang Switcher
        $router->get('/lang/{locale}', [\App\Controllers\LangController::class, 'switch']);

        // Install Route
        $router->any('/install', [\App\Controllers\Install\InstallController::class, 'index']);
        $router->post('/install/run', [\App\Controllers\Install\InstallController::class, 'install']);

        // Auth Routes
        $router->get('/login', [\App\Controllers\Auth\AuthController::class, 'loginForm']);
        $router->post('/login', [\App\Controllers\Auth\AuthController::class, 'login']);
        $router->get('/register', [\App\Controllers\Auth\AuthController::class, 'registerForm']);
        $router->post('/register', [\App\Controllers\Auth\AuthController::class, 'register']);
        $router->any('/logout', [\App\Controllers\Auth\AuthController::class, 'logout']);

        // Driver Routes
        $router->group(['prefix' => '/driver', 'middleware' => [\App\Middleware\TenantMiddleware::class, \App\Middleware\DriverMiddleware::class]], function($r) {
            $r->get('/dashboard', [\App\Controllers\Driver\DashboardController::class, 'index']);
            $r->post('/bookkeeping/add', [\App\Controllers\Driver\BookkeepingController::class, 'add']);
        });

        // Owner Routes
        $router->group(['prefix' => '/owner', 'middleware' => [\App\Middleware\TenantMiddleware::class, \App\Middleware\OwnerMiddleware::class]], function($r) {
            $r->get('/dashboard', [\App\Controllers\Owner\DashboardController::class, 'index']);

            // Vehicles
            $r->get('/vehicles', [\App\Controllers\Owner\VehicleController::class, 'index']);
            $r->get('/vehicles/create', [\App\Controllers\Owner\VehicleController::class, 'create']);
            $r->post('/vehicles/store', [\App\Controllers\Owner\VehicleController::class, 'store']);

            // Drivers
            $r->get('/drivers', [\App\Controllers\Owner\DriverController::class, 'index']);
            $r->get('/drivers/create', [\App\Controllers\Owner\DriverController::class, 'create']);
            $r->post('/drivers/store', [\App\Controllers\Owner\DriverController::class, 'store']);
        });

        // Admin Routes
        $router->group(['prefix' => '/admin', 'middleware' => [\App\Middleware\TenantMiddleware::class, \App\Middleware\AdminMiddleware::class]], function($r) {
            $r->get('/dashboard', [\App\Controllers\Admin\DashboardController::class, 'index']);

            // Users
            $r->get('/users', [\App\Controllers\Admin\UserController::class, 'index']);
            $r->get('/users/create', [\App\Controllers\Admin\UserController::class, 'create']);
            $r->post('/users/store', [\App\Controllers\Admin\UserController::class, 'store']);
            $r->get('/users/edit/{id}', [\App\Controllers\Admin\UserController::class, 'edit']);
            $r->post('/users/update/{id}', [\App\Controllers\Admin\UserController::class, 'update']);

            // Vehicles (Global Admin View)
            $r->get('/vehicles', [\App\Controllers\Admin\VehicleController::class, 'index']);
            $r->get('/vehicles/create', [\App\Controllers\Admin\VehicleController::class, 'create']);
            $r->post('/vehicles/store', [\App\Controllers\Admin\VehicleController::class, 'store']);
            $r->get('/vehicles/edit/{id}', [\App\Controllers\Admin\VehicleController::class, 'edit']);
            $r->post('/vehicles/update/{id}', [\App\Controllers\Admin\VehicleController::class, 'update']);
            $r->get('/vehicles/delete/{id}', [\App\Controllers\Admin\VehicleController::class, 'delete']);

            // Assignments
            $r->get('/assignments', [\App\Controllers\Admin\AssignmentController::class, 'index']);
            $r->get('/assignments/create', [\App\Controllers\Admin\AssignmentController::class, 'create']);
            $r->post('/assignments/store', [\App\Controllers\Admin\AssignmentController::class, 'store']);
            $r->get('/assignments/end/{id}', [\App\Controllers\Admin\AssignmentController::class, 'end']);

            // Settings
            $r->get('/settings', [\App\Controllers\Admin\SettingController::class, 'index']);
            $r->post('/settings/theme', [\App\Controllers\Admin\SettingController::class, 'updateTheme']);
        });

        // Maps
        $router->group(['prefix' => '/maps', 'middleware' => [\App\Middleware\AuthMiddleware::class]], function($r) {
            $r->get('/', [\App\Controllers\MapController::class, 'index']);
        });

        $router->group(['prefix' => '/api', 'middleware' => [\App\Middleware\AuthMiddleware::class]], function($r) {
            $r->get('/points', [\App\Controllers\Api\PointController::class, 'index']);
        });

        // Default Redirect logic
        $router->get('/', function() {
            if (Session::has('user_id')) {
                 $role = Session::get('user_role');
                 if ($role === 'driver') {
                     header('Location: /driver/dashboard');
                     exit;
                 } elseif ($role === 'org_admin') {
                     header('Location: /owner/dashboard');
                     exit;
                 } elseif ($role === 'superadmin') {
                     header('Location: /admin/dashboard');
                     exit;
                 }
            }
            header('Location: /login');
        });
    }
}
