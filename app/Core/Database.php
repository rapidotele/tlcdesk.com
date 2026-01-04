<?php

namespace App\Core;

use PDO;
use PDOException;

class Database
{
    protected static $instance = null;
    protected $pdo;

    private function __construct()
    {
        $host = Env::get('DB_HOST', 'localhost');
        $db   = Env::get('DB_DATABASE');
        $user = Env::get('DB_USERNAME');
        $pass = Env::get('DB_PASSWORD');
        $charset = 'utf8mb4';

        $dsn = "mysql:host=$host;dbname=$db;charset=$charset";
        $options = [
            PDO::ATTR_ERRMODE            => PDO::ERRMODE_EXCEPTION,
            PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
            PDO::ATTR_EMULATE_PREPARES   => false,
        ];

        try {
            $this->pdo = new PDO($dsn, $user, $pass, $options);
        } catch (PDOException $e) {
            // In a real app, handle this gracefully.
            // For now, if DB connection fails, we might be in install mode or misconfigured.
            if (!str_contains($_SERVER['REQUEST_URI'] ?? '', '/install')) {
                 // Log error but don't expose creds
                 error_log("DB Connection Error: " . $e->getMessage());
            }
            throw $e;
        }
    }

    public static function getInstance()
    {
        if (self::$instance === null) {
            self::$instance = new self();
        }
        return self::$instance;
    }

    public function getConnection()
    {
        return $this->pdo;
    }

    public function query($sql, $params = [])
    {
        $stmt = $this->pdo->prepare($sql);
        $stmt->execute($params);
        return $stmt;
    }

    public function lastInsertId()
    {
        return $this->pdo->lastInsertId();
    }
}
