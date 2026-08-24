<?php

require_once __DIR__ . '/../core/Model.php';

class User extends Model {

    public function findByMobile($mobile) {
        $stmt = $this->db->prepare("SELECT * FROM users WHERE mobile_number = :mobile LIMIT 1");
        $stmt->execute([':mobile' => $mobile]);
        return $stmt->fetch();
    }

    public function findById($id) {
        $stmt = $this->db->prepare("SELECT * FROM users WHERE id = :id LIMIT 1");
        $stmt->execute([':id' => $id]);
        return $stmt->fetch();
    }

    public function create($name, $societyName, $mobile) {
        // Check if existing user with pending status exists
        $existing = $this->findByMobile($mobile);
        if ($existing) {
            $stmt = $this->db->prepare("UPDATE users SET name = :name, society_name = :society, status = 'pending_otp' WHERE id = :id");
            $stmt->execute([
                ':name' => $name,
                ':society' => $societyName,
                ':id' => $existing['id']
            ]);
            return $existing['id'];
        }

        $stmt = $this->db->prepare("INSERT INTO users (name, society_name, mobile_number, status) VALUES (:name, :society, :mobile, 'pending_otp')");
        $stmt->execute([
            ':name' => $name,
            ':society' => $societyName,
            ':mobile' => $mobile
        ]);

        return $this->db->lastInsertId();
    }

    public function updateStatus($userId, $status) {
        $stmt = $this->db->prepare("UPDATE users SET status = :status WHERE id = :id");
        return $stmt->execute([
            ':status' => $status,
            ':id' => $userId
        ]);
    }

    public function updatePassword($userId, $passwordHash) {
        $stmt = $this->db->prepare("UPDATE users SET password_hash = :hash, status = 'active' WHERE id = :id");
        return $stmt->execute([
            ':hash' => $passwordHash,
            ':id' => $userId
        ]);
    }
}
