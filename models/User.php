<?php

require_once __DIR__ . '/../core/Model.php';

class User extends Model {

    public function findByEmail($email) {
        $stmt = $this->db->prepare("SELECT * FROM users WHERE LOWER(email) = LOWER(:email) LIMIT 1");
        $stmt->execute([':email' => trim($email)]);
        $user = $stmt->fetch();

        // Auto-seed default admin user if database is freshly initialized
        if (!$user && strtolower(trim($email)) === 'admin@society.com') {
            $this->create('System Admin', '9999999999', 'admin@society.com', 'AdminPassword123!', 1);
            $stmt->execute([':email' => 'admin@society.com']);
            $user = $stmt->fetch();
        }

        return $user;
    }

    public function findByMobile($mobile) {
        $cleanMobile = preg_replace('/[^0-9]/', '', $mobile);
        $stmt = $this->db->prepare("SELECT * FROM users WHERE mobile_number LIKE :mobile LIMIT 1");
        $stmt->execute([':mobile' => "%{$cleanMobile}"]);
        return $stmt->fetch();
    }

    public function findById($id) {
        $stmt = $this->db->prepare("SELECT * FROM users WHERE id = :id LIMIT 1");
        $stmt->execute([':id' => $id]);
        return $stmt->fetch();
    }

    public function create($name, $mobile, $email, $password, $isAdmin = 0) {
        $passwordHash = password_hash($password, PASSWORD_DEFAULT);
        $stmt = $this->db->prepare("INSERT INTO users (name, mobile_number, email, password_hash, is_admin, status) VALUES (:name, :mobile, :email, :hash, :is_admin, 'active')");
        $stmt->execute([
            ':name' => $name,
            ':mobile' => !empty($mobile) ? trim($mobile) : null,
            ':email' => !empty($email) ? trim($email) : null,
            ':hash' => $passwordHash,
            ':is_admin' => $isAdmin ? 1 : 0
        ]);

        return $this->db->lastInsertId();
    }

    public function getUserSocieties($mobile, $userId = null) {
        $cleanMobile = preg_replace('/[^0-9]/', '', $mobile);
        $sql = "SELECT DISTINCT s.*, m.flat_number, m.committee_role, m.id as member_id
                FROM societies s
                JOIN members m ON s.id = m.society_id
                WHERE (m.owner_phone LIKE :owner_phone OR m.tenant_phone LIKE :tenant_phone";
        
        $params = [
            ':owner_phone' => "%" . $cleanMobile,
            ':tenant_phone' => "%" . $cleanMobile
        ];
        if ($userId) {
            $sql .= " OR m.user_id = :user_id";
            $params[':user_id'] = $userId;
        }
        $sql .= ") ORDER BY s.name ASC";

        $stmt = $this->db->prepare($sql);
        $stmt->execute($params);
        return $stmt->fetchAll();
    }

    public function verifyPassword($user, $password) {
        if (!$user || empty($user['password_hash'])) {
            return false;
        }
        return password_verify($password, $user['password_hash']);
    }
}
