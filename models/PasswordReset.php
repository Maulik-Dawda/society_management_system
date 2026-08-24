<?php

require_once __DIR__ . '/../core/Model.php';

class PasswordReset extends Model {

    public function createToken($userId, $expiryHours = 24) {
        $token = bin2hex(random_bytes(32));
        $expiresAt = date('Y-m-d H:i:s', strtotime("+{$expiryHours} hours"));

        $stmt = $this->db->prepare("INSERT INTO password_tokens (user_id, token, expires_at) VALUES (:user_id, :token, :expires_at)");
        $stmt->execute([
            ':user_id' => $userId,
            ':token' => $token,
            ':expires_at' => $expiresAt
        ]);

        return $token;
    }

    public function findByToken($token) {
        $stmt = $this->db->prepare("SELECT pt.*, u.mobile_number, u.name, u.society_name FROM password_tokens pt JOIN users u ON pt.user_id = u.id WHERE pt.token = :token AND pt.is_used = 0 AND pt.expires_at >= NOW() LIMIT 1");
        $stmt->execute([':token' => $token]);
        return $stmt->fetch();
    }

    public function markUsed($token) {
        $stmt = $this->db->prepare("UPDATE password_tokens SET is_used = 1 WHERE token = :token");
        return $stmt->execute([':token' => $token]);
    }
}
