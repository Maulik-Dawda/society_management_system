<?php

class Database {
    private $host;
    private $db_name;
    private $username;
    private $password;
    private $conn = null;

    public function __construct() {
        $this->loadEnv();

        $this->host = getenv('DB_HOST') ?: (defined('DB_HOST') ? DB_HOST : 'localhost');
        $this->db_name = getenv('DB_NAME') ?: (defined('DB_NAME') ? DB_NAME : 'society_management_db');
        $this->username = getenv('DB_USER') ?: (defined('DB_USER') ? DB_USER : 'root');
        $this->password = getenv('DB_PASS') !== false ? getenv('DB_PASS') : (defined('DB_PASS') ? DB_PASS : '');
    }

    private function loadEnv() {
        $envFile = __DIR__ . '/../.env';
        if (file_exists($envFile)) {
            $lines = file($envFile, FILE_IGNORE_NEW_LINES | FILE_SKIP_EMPTY_LINES);
            foreach ($lines as $line) {
                if (strpos(trim($line), '#') === 0) continue;
                if (strpos($line, '=') !== false) {
                    list($name, $value) = explode('=', $line, 2);
                    $name = trim($name);
                    $value = trim($value, " \t\n\r\0\x0B\"'");
                    if (!array_key_exists($name, $_SERVER) && !array_key_exists($name, $_ENV)) {
                        putenv("{$name}={$value}");
                        $_ENV[$name] = $value;
                        $_SERVER[$name] = $value;
                    }
                }
            }
        }
    }

    public function getConnection() {
        if ($this->conn !== null) {
            return $this->conn;
        }

        $options = [
            PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
            PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
            PDO::ATTR_EMULATE_PREPARES => false,
        ];

        try {
            // Direct connection with dbname (Works on Hostinger & Shared Hosting)
            $dsn = "mysql:host=" . $this->host . ";dbname=" . $this->db_name . ";charset=utf8mb4";
            $pdo = new PDO($dsn, $this->username, $this->password, $options);
            $this->createTablesIfNotExist($pdo);
            $this->conn = $pdo;
        } catch (PDOException $exception) {
            // Secondary attempt for local development: create DB if missing & privileged
            try {
                $dsnNoDb = "mysql:host=" . $this->host . ";charset=utf8mb4";
                $pdo = new PDO($dsnNoDb, $this->username, $this->password, $options);
                $pdo->exec("CREATE DATABASE IF NOT EXISTS `{$this->db_name}` DEFAULT CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci");
                $pdo->exec("USE `{$this->db_name}`");
                $this->createTablesIfNotExist($pdo);
                $this->conn = $pdo;
            } catch (PDOException $e2) {
                throw new Exception("MySQL Connection Failed for host [{$this->host}], user [{$this->username}], database [{$this->db_name}]: " . $exception->getMessage());
            }
        }

        return $this->conn;
    }

    private function createTablesIfNotExist($pdo) {
        $queries = [
            "CREATE TABLE IF NOT EXISTS `users` (
                `id` INT AUTO_INCREMENT PRIMARY KEY,
                `name` VARCHAR(100) NOT NULL,
                `society_name` VARCHAR(150) NOT NULL,
                `mobile_number` VARCHAR(15) NOT NULL UNIQUE,
                `password_hash` VARCHAR(255) NULL,
                `status` ENUM('pending_otp', 'pending_password', 'active') DEFAULT 'pending_otp',
                `created_at` TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
                `updated_at` TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
            ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;",

            "CREATE TABLE IF NOT EXISTS `otps` (
                `id` INT AUTO_INCREMENT PRIMARY KEY,
                `mobile_number` VARCHAR(15) NOT NULL,
                `otp_code` VARCHAR(6) NOT NULL,
                `expires_at` DATETIME NOT NULL,
                `is_verified` TINYINT(1) DEFAULT 0,
                `created_at` TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
                INDEX (`mobile_number`)
            ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;",

            "CREATE TABLE IF NOT EXISTS `password_tokens` (
                `id` INT AUTO_INCREMENT PRIMARY KEY,
                `user_id` INT NOT NULL,
                `token` VARCHAR(64) NOT NULL UNIQUE,
                `expires_at` DATETIME NOT NULL,
                `is_used` TINYINT(1) DEFAULT 0,
                `created_at` TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
                FOREIGN KEY (`user_id`) REFERENCES `users`(`id`) ON DELETE CASCADE
            ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;"
        ];

        foreach ($queries as $query) {
            try {
                $pdo->exec($query);
            } catch (PDOException $e) {
                // Table creation silent catch if exists or restricted
            }
        }
    }
}
