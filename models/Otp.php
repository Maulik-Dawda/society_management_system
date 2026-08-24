<?php

require_once __DIR__ . '/../core/Model.php';

class Otp extends Model {

    public function createOtp($mobile, $code, $expiryMinutes = 10) {
        $expiresAt = date('Y-m-d H:i:s', strtotime("+{$expiryMinutes} minutes"));

        $stmt = $this->db->prepare("INSERT INTO otps (mobile_number, otp_code, expires_at) VALUES (:mobile, :code, :expires_at)");
        $stmt->execute([
            ':mobile' => $mobile,
            ':code' => $code,
            ':expires_at' => $expiresAt
        ]);

        return $code;
    }

    public function verifyOtp($mobile, $code) {
        $stmt = $this->db->prepare("SELECT * FROM otps WHERE mobile_number = :mobile AND otp_code = :code AND is_verified = 0 AND expires_at >= NOW() ORDER BY id DESC LIMIT 1");
        $stmt->execute([
            ':mobile' => $mobile,
            ':code' => $code
        ]);
        $record = $stmt->fetch();

        if ($record) {
            $update = $this->db->prepare("UPDATE otps SET is_verified = 1 WHERE id = :id");
            $update->execute([':id' => $record['id']]);
            return true;
        }

        return false;
    }

    public function getLatestOtp($mobile) {
        $stmt = $this->db->prepare("SELECT * FROM otps WHERE mobile_number = :mobile ORDER BY id DESC LIMIT 1");
        $stmt->execute([':mobile' => $mobile]);
        return $stmt->fetch();
    }
}
