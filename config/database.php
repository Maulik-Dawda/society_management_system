<?php

class Database {
    private $host = "localhost";
    private $db_name = "society_management_db";
    private $username = "root";
    private $password = "";
    private $conn = null;

    public function getConnection() {
        if ($this->conn !== null) {
            return $this->conn;
        }

        try {
            $dsn = "mysql:host=" . $this->host . ";charset=utf8mb4";
            $options = [
                PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
                PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
                PDO::ATTR_EMULATE_PREPARES => false,
            ];
            
            $pdo = new PDO($dsn, $this->username, $this->password, $options);
            
            // Ensure database exists
            $pdo->exec("CREATE DATABASE IF NOT EXISTS `{$this->db_name}` DEFAULT CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci");
            $pdo->exec("USE `{$this->db_name}`");
            
            // Create tables if they do not exist
            $this->createTablesIfNotExist($pdo);

            $this->conn = $pdo;
        } catch (PDOException $exception) {
            // Fallback for file-based array session/mock if DB server is offline during initial test
            throw new Exception("Database Connection Error: " . $exception->getMessage());
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
            $pdo->exec($query);
        }
    }
}
