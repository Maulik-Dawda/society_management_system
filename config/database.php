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
        $tables = [
            "CREATE TABLE IF NOT EXISTS `users` (
                `id` INT AUTO_INCREMENT PRIMARY KEY,
                `name` VARCHAR(100) NOT NULL,
                `email` VARCHAR(100) NULL UNIQUE,
                `mobile_number` VARCHAR(15) NULL UNIQUE,
                `password_hash` VARCHAR(255) NOT NULL,
                `is_admin` TINYINT(1) DEFAULT 0,
                `status` ENUM('active', 'inactive') DEFAULT 'active',
                `created_at` TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
                `updated_at` TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
            ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;",

            "CREATE TABLE IF NOT EXISTS `societies` (
                `id` INT AUTO_INCREMENT PRIMARY KEY,
                `name` VARCHAR(150) NOT NULL UNIQUE,
                `registration_number` VARCHAR(100) NULL,
                `registration_date` DATE NULL,
                `registered_address` TEXT NOT NULL,
                `pan_number` VARCHAR(10) NOT NULL,
                `gstin` VARCHAR(15) NULL,
                `total_wings` INT DEFAULT 4,
                `total_flats` INT DEFAULT 84,
                `total_members` INT DEFAULT 84,
                `bank_balance` DECIMAL(15,2) DEFAULT 0.00,
                `cash_in_hand` DECIMAL(15,2) DEFAULT 0.00,
                `bank_name` VARCHAR(100) NULL,
                `account_number` VARCHAR(50) NULL,
                `created_by_admin_id` INT NULL,
                `created_at` TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
                `updated_at` TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
            ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;",

            "CREATE TABLE IF NOT EXISTS `members` (
                `id` INT AUTO_INCREMENT PRIMARY KEY,
                `society_id` INT NOT NULL DEFAULT 1,
                `user_id` INT NULL,
                `flat_number` VARCHAR(20) NOT NULL,
                `area_sqft` INT DEFAULT 0,
                `owner_name` VARCHAR(100) NOT NULL,
                `owner_phone` VARCHAR(15) NOT NULL,
                `owner_email` VARCHAR(100) NULL,
                `is_rented` TINYINT(1) DEFAULT 0,
                `tenant_name` VARCHAR(100) NULL,
                `tenant_phone` VARCHAR(15) NULL,
                `agreement_start` DATE NULL,
                `agreement_end` DATE NULL,
                `id_proof` VARCHAR(50) NULL,
                `committee_role` ENUM('Resident', 'Chairman', 'Secretary', 'Treasurer', 'Committee Member') DEFAULT 'Resident',
                `created_at` TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
                INDEX (`society_id`),
                INDEX (`owner_phone`),
                INDEX (`user_id`)
            ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;",

            "CREATE TABLE IF NOT EXISTS `complaints` (
                `id` INT AUTO_INCREMENT PRIMARY KEY,
                `society_id` INT NOT NULL DEFAULT 1,
                `member_id` INT NOT NULL,
                `flat_number` VARCHAR(20) NOT NULL,
                `title` VARCHAR(200) NOT NULL,
                `category` VARCHAR(50) DEFAULT 'General',
                `description` TEXT NOT NULL,
                `status` ENUM('Open', 'In Progress', 'Resolved', 'Closed') DEFAULT 'Open',
                `created_at` TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
                `updated_at` TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
                INDEX (`society_id`),
                INDEX (`member_id`)
            ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;",

            "CREATE TABLE IF NOT EXISTS `notices` (
                `id` INT AUTO_INCREMENT PRIMARY KEY,
                `society_id` INT NOT NULL DEFAULT 1,
                `created_by_user_id` INT NULL,
                `notice_date` DATE NOT NULL,
                `title` VARCHAR(200) NOT NULL,
                `category` VARCHAR(50) DEFAULT 'General',
                `is_urgent` TINYINT(1) DEFAULT 0,
                `content` TEXT NOT NULL,
                `created_at` TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
                INDEX (`society_id`)
            ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;",

            "CREATE TABLE IF NOT EXISTS `vehicles` (
                `id` INT AUTO_INCREMENT PRIMARY KEY,
                `society_id` INT NOT NULL DEFAULT 1,
                `flat_number` VARCHAR(20) NOT NULL,
                `vehicle_number` VARCHAR(30) NOT NULL,
                `make_model` VARCHAR(100) NULL,
                `vehicle_type` ENUM('Car', 'Two-wheeler') DEFAULT 'Car',
                `colour` VARCHAR(30) NULL,
                `parking_slot` VARCHAR(30) NULL,
                `status` ENUM('Active', 'Guest') DEFAULT 'Active',
                `created_at` TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
                INDEX (`society_id`)
            ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;",

            "CREATE TABLE IF NOT EXISTS `opening_dues` (
                `id` INT AUTO_INCREMENT PRIMARY KEY,
                `society_id` INT NOT NULL DEFAULT 1,
                `flat_number` VARCHAR(20) NOT NULL,
                `member_name` VARCHAR(100) NOT NULL,
                `pending_amount` DECIMAL(12,2) NOT NULL DEFAULT 0.00,
                `created_at` TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
                INDEX (`society_id`)
            ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;",

            "CREATE TABLE IF NOT EXISTS `maintenance_bills` (
                `id` INT AUTO_INCREMENT PRIMARY KEY,
                `society_id` INT NOT NULL DEFAULT 1,
                `flat_number` VARCHAR(20) NOT NULL,
                `billing_cycle` VARCHAR(20) NOT NULL,
                `charge_basis` VARCHAR(50) DEFAULT 'Fixed',
                `amount` DECIMAL(12,2) NOT NULL,
                `due_date` DATE NOT NULL,
                `late_fee_rule` VARCHAR(100) NULL,
                `status` ENUM('Paid', 'Overdue', 'Pending') DEFAULT 'Pending',
                `created_at` TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
                INDEX (`society_id`)
            ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;",

            "CREATE TABLE IF NOT EXISTS `payments` (
                `id` INT AUTO_INCREMENT PRIMARY KEY,
                `society_id` INT NOT NULL DEFAULT 1,
                `receipt_number` VARCHAR(50) NOT NULL UNIQUE,
                `flat_number` VARCHAR(20) NOT NULL,
                `owner_name` VARCHAR(100) NOT NULL,
                `amount` DECIMAL(12,2) NOT NULL,
                `payment_mode` VARCHAR(50) DEFAULT 'UPI',
                `payment_date` DATE NOT NULL,
                `reference_no` VARCHAR(100) NULL,
                `status` ENUM('Paid', 'Pending', 'Failed') DEFAULT 'Paid',
                `created_at` TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
                INDEX (`society_id`)
            ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;",

            "CREATE TABLE IF NOT EXISTS `expenses` (
                `id` INT AUTO_INCREMENT PRIMARY KEY,
                `society_id` INT NOT NULL DEFAULT 1,
                `expense_date` DATE NOT NULL,
                `category` VARCHAR(50) NOT NULL,
                `vendor_name` VARCHAR(100) NOT NULL,
                `bill_number` VARCHAR(50) NULL,
                `amount` DECIMAL(12,2) NOT NULL,
                `gst_pct` DECIMAL(5,2) DEFAULT 18.00,
                `payment_mode` VARCHAR(50) DEFAULT 'Bank transfer',
                `notes` TEXT NULL,
                `status` ENUM('Paid', 'Pending') DEFAULT 'Paid',
                `created_at` TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
                INDEX (`society_id`)
            ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;"
        ];

        foreach ($tables as $query) {
            try {
                $pdo->exec($query);
            } catch (PDOException $e) {
                // Table creation catch if error
            }
        }

        // Auto-migration logic for existing tables with old schema
        $this->runMigrations($pdo);
    }

    private function runMigrations($pdo) {
        try {
            // Check 'users' table columns
            if (!$this->columnExists($pdo, 'users', 'email')) {
                $pdo->exec("ALTER TABLE `users` ADD COLUMN `email` VARCHAR(100) NULL AFTER `name`");
            }
            if (!$this->columnExists($pdo, 'users', 'is_admin')) {
                $pdo->exec("ALTER TABLE `users` ADD COLUMN `is_admin` TINYINT(1) DEFAULT 0 AFTER `password_hash`");
            }
            try {
                $pdo->exec("ALTER TABLE `users` MODIFY `mobile_number` VARCHAR(15) NULL");
            } catch (PDOException $e) {}

            // Check 'societies' table columns
            if (!$this->columnExists($pdo, 'societies', 'registration_number')) {
                $pdo->exec("ALTER TABLE `societies` ADD COLUMN `registration_number` VARCHAR(100) NULL");
            }
            if (!$this->columnExists($pdo, 'societies', 'registration_date')) {
                $pdo->exec("ALTER TABLE `societies` ADD COLUMN `registration_date` DATE NULL");
            }
            if (!$this->columnExists($pdo, 'societies', 'registered_address')) {
                $pdo->exec("ALTER TABLE `societies` ADD COLUMN `registered_address` TEXT NULL");
            }
            if (!$this->columnExists($pdo, 'societies', 'pan_number')) {
                $pdo->exec("ALTER TABLE `societies` ADD COLUMN `pan_number` VARCHAR(10) NULL");
            }
            if (!$this->columnExists($pdo, 'societies', 'gstin')) {
                $pdo->exec("ALTER TABLE `societies` ADD COLUMN `gstin` VARCHAR(15) NULL");
            }
            if (!$this->columnExists($pdo, 'societies', 'total_wings')) {
                $pdo->exec("ALTER TABLE `societies` ADD COLUMN `total_wings` INT DEFAULT 4");
            }
            if (!$this->columnExists($pdo, 'societies', 'total_flats')) {
                $pdo->exec("ALTER TABLE `societies` ADD COLUMN `total_flats` INT DEFAULT 84");
            }
            if (!$this->columnExists($pdo, 'societies', 'total_members')) {
                $pdo->exec("ALTER TABLE `societies` ADD COLUMN `total_members` INT DEFAULT 84");
            }
            if (!$this->columnExists($pdo, 'societies', 'bank_balance')) {
                $pdo->exec("ALTER TABLE `societies` ADD COLUMN `bank_balance` DECIMAL(15,2) DEFAULT 0.00");
            }
            if (!$this->columnExists($pdo, 'societies', 'cash_in_hand')) {
                $pdo->exec("ALTER TABLE `societies` ADD COLUMN `cash_in_hand` DECIMAL(15,2) DEFAULT 0.00");
            }
            if (!$this->columnExists($pdo, 'societies', 'bank_name')) {
                $pdo->exec("ALTER TABLE `societies` ADD COLUMN `bank_name` VARCHAR(100) NULL");
            }
            if (!$this->columnExists($pdo, 'societies', 'account_number')) {
                $pdo->exec("ALTER TABLE `societies` ADD COLUMN `account_number` VARCHAR(50) NULL");
            }
            if (!$this->columnExists($pdo, 'societies', 'created_by_admin_id')) {
                $pdo->exec("ALTER TABLE `societies` ADD COLUMN `created_by_admin_id` INT NULL");
            }

            // Check 'members' table columns
            if (!$this->columnExists($pdo, 'members', 'society_id')) {
                $pdo->exec("ALTER TABLE `members` ADD COLUMN `society_id` INT NOT NULL DEFAULT 1 AFTER `id`");
            }
            if (!$this->columnExists($pdo, 'members', 'committee_role')) {
                $pdo->exec("ALTER TABLE `members` ADD COLUMN `committee_role` ENUM('Resident', 'Chairman', 'Secretary', 'Treasurer', 'Committee Member') DEFAULT 'Resident'");
            }
            if (!$this->columnExists($pdo, 'members', 'user_id')) {
                $pdo->exec("ALTER TABLE `members` ADD COLUMN `user_id` INT NULL");
            }
            if (!$this->columnExists($pdo, 'members', 'area_sqft')) {
                $pdo->exec("ALTER TABLE `members` ADD COLUMN `area_sqft` INT DEFAULT 0");
            }
            if (!$this->columnExists($pdo, 'members', 'owner_email')) {
                $pdo->exec("ALTER TABLE `members` ADD COLUMN `owner_email` VARCHAR(100) NULL");
            }
            if (!$this->columnExists($pdo, 'members', 'is_rented')) {
                $pdo->exec("ALTER TABLE `members` ADD COLUMN `is_rented` TINYINT(1) DEFAULT 0");
            }
            if (!$this->columnExists($pdo, 'members', 'tenant_name')) {
                $pdo->exec("ALTER TABLE `members` ADD COLUMN `tenant_name` VARCHAR(100) NULL");
            }
            if (!$this->columnExists($pdo, 'members', 'tenant_phone')) {
                $pdo->exec("ALTER TABLE `members` ADD COLUMN `tenant_phone` VARCHAR(15) NULL");
            }
            if (!$this->columnExists($pdo, 'members', 'agreement_start')) {
                $pdo->exec("ALTER TABLE `members` ADD COLUMN `agreement_start` DATE NULL");
            }
            if (!$this->columnExists($pdo, 'members', 'agreement_end')) {
                $pdo->exec("ALTER TABLE `members` ADD COLUMN `agreement_end` DATE NULL");
            }
            if (!$this->columnExists($pdo, 'members', 'id_proof')) {
                $pdo->exec("ALTER TABLE `members` ADD COLUMN `id_proof` VARCHAR(50) NULL");
            }

            // Check 'notices' table columns
            if (!$this->columnExists($pdo, 'notices', 'society_id')) {
                $pdo->exec("ALTER TABLE `notices` ADD COLUMN `society_id` INT NOT NULL DEFAULT 1 AFTER `id`");
            }
            if (!$this->columnExists($pdo, 'notices', 'created_by_user_id')) {
                $pdo->exec("ALTER TABLE `notices` ADD COLUMN `created_by_user_id` INT NULL");
            }

            // Check 'complaints' table columns
            if (!$this->columnExists($pdo, 'complaints', 'society_id')) {
                $pdo->exec("ALTER TABLE `complaints` ADD COLUMN `society_id` INT NOT NULL DEFAULT 1 AFTER `id`");
            }

            // Check 'vehicles' table columns
            if (!$this->columnExists($pdo, 'vehicles', 'society_id')) {
                $pdo->exec("ALTER TABLE `vehicles` ADD COLUMN `society_id` INT NOT NULL DEFAULT 1 AFTER `id`");
            }

            // Check 'maintenance_bills' table columns
            if (!$this->columnExists($pdo, 'maintenance_bills', 'society_id')) {
                $pdo->exec("ALTER TABLE `maintenance_bills` ADD COLUMN `society_id` INT NOT NULL DEFAULT 1 AFTER `id`");
            }

            // Check 'payments' table columns
            if (!$this->columnExists($pdo, 'payments', 'society_id')) {
                $pdo->exec("ALTER TABLE `payments` ADD COLUMN `society_id` INT NOT NULL DEFAULT 1 AFTER `id`");
            }

            // Check 'expenses' table columns
            if (!$this->columnExists($pdo, 'expenses', 'society_id')) {
                $pdo->exec("ALTER TABLE `expenses` ADD COLUMN `society_id` INT NOT NULL DEFAULT 1 AFTER `id`");
            }

            // Auto-seed default Admin Account if missing
            $stmt = $pdo->prepare("SELECT id FROM `users` WHERE `email` = ? OR `is_admin` = 1 LIMIT 1");
            $stmt->execute(['admin@society.com']);
            if (!$stmt->fetch()) {
                $hash = password_hash('AdminPassword123!', PASSWORD_BCRYPT);
                $pdo->prepare("INSERT INTO `users` (`name`, `email`, `mobile_number`, `password_hash`, `is_admin`, `status`) VALUES (?, ?, ?, ?, 1, 'active')")
                    ->execute(['System Admin', 'admin@society.com', '9999999999', $hash]);
            }
        } catch (PDOException $e) {
            // Migration error catch
        }
    }

    private function columnExists($pdo, $table, $column) {
        try {
            $stmt = $pdo->prepare("SHOW COLUMNS FROM `{$table}` LIKE ?");
            $stmt->execute([$column]);
            return (bool) $stmt->fetch();
        } catch (PDOException $e) {
            return false;
        }
    }
}
