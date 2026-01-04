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
        $router->group(['prefix' => '/driver', 'middleware' => [\App\Middleware\AuthMiddleware::class]], function($r) {
            $r->get('/dashboard', [\App\Controllers\Driver\DashboardController::class, 'index']);
            $r->post('/bookkeeping/add', [\App\Controllers\Driver\BookkeepingController::class, 'add']);
        });

        // Owner Routes
        $router->group(['prefix' => '/owner', 'middleware' => [\App\Middleware\AuthMiddleware::class]], function($r) {
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
        $router->group(['prefix' => '/admin', 'middleware' => [\App\Middleware\AuthMiddleware::class]], function($r) {
            $r->get('/dashboard', [\App\Controllers\Admin\DashboardController::class, 'index']);
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
