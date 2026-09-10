-- Database Schema for Society Management System (Multi-Society, API-First, Hostinger & MySQL Compatible)

-- Users table (Supports both Admin Login via Email and Regular User Login via Mobile Number)
CREATE TABLE IF NOT EXISTS `users` (
    `id` INT AUTO_INCREMENT PRIMARY KEY,
    `name` VARCHAR(100) NOT NULL,
    `email` VARCHAR(100) NULL UNIQUE,
    `mobile_number` VARCHAR(15) NULL UNIQUE,
    `password_hash` VARCHAR(255) NOT NULL,
    `is_admin` TINYINT(1) DEFAULT 0,
    `status` ENUM('active', 'inactive') DEFAULT 'active',
    `created_at` TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    `updated_at` TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- Societies table (Registered by System Admin)
CREATE TABLE IF NOT EXISTS `societies` (
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
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- Members table (Links users to flats and specific societies with per-society committee roles)
CREATE TABLE IF NOT EXISTS `members` (
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
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- Complaints table (Registered by residents, tracked by committee)
CREATE TABLE IF NOT EXISTS `complaints` (
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
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- Notices table (Created BY CHAIRMAN ONLY or Admin)
CREATE TABLE IF NOT EXISTS `notices` (
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
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- Vehicles table
CREATE TABLE IF NOT EXISTS `vehicles` (
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
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- Opening Dues table
CREATE TABLE IF NOT EXISTS `opening_dues` (
    `id` INT AUTO_INCREMENT PRIMARY KEY,
    `society_id` INT NOT NULL DEFAULT 1,
    `flat_number` VARCHAR(20) NOT NULL,
    `member_name` VARCHAR(100) NOT NULL,
    `pending_amount` DECIMAL(12,2) NOT NULL DEFAULT 0.00,
    `created_at` TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    INDEX (`society_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- Maintenance Bills table
CREATE TABLE IF NOT EXISTS `maintenance_bills` (
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
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- Payments table
CREATE TABLE IF NOT EXISTS `payments` (
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
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- Expenses table
CREATE TABLE IF NOT EXISTS `expenses` (
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
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;
