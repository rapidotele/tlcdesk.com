<?php

require __DIR__ . '/../app/bootstrap.php';

// Error Reporting
error_reporting(E_ALL);
ini_set('display_errors', 0); // Hide in production
ini_set('log_errors', 1);
ini_set('error_log', __DIR__ . '/../storage/logs/app.log');

// Bootstrap
try {
    App\Core\App::run();
} catch (Throwable $e) {
    error_log($e->getMessage() . "\n" . $e->getTraceAsString());
    http_response_code(500);
    echo "Internal Server Error";
}
