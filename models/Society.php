<?php

require_once __DIR__ . '/../core/Model.php';
require_once __DIR__ . '/../core/Session.php';

class Society extends Model {

    public function getAll() {
        $stmt = $this->db->query("SELECT * FROM societies ORDER BY name ASC");
        return $stmt->fetchAll();
    }

    public function findById($id) {
        $stmt = $this->db->prepare("SELECT * FROM societies WHERE id = :id LIMIT 1");
        $stmt->execute([':id' => $id]);
        return $stmt->fetch();
    }

    public function findByUserId($userId) {
        if (Session::has('active_society_id') && Session::get('active_society_id')) {
            $society = $this->findById(Session::get('active_society_id'));
            if ($society) {
                return $society;
            }
        }

        // Check if user is linked to any society via member table
        try {
            $stmt = $this->db->prepare("SELECT s.* FROM societies s
                JOIN members m ON s.id = m.society_id
                JOIN users u ON u.id = :u_user_id
                WHERE m.user_id = :m_user_id OR (u.mobile_number IS NOT NULL AND (m.owner_phone LIKE CONCAT('%', u.mobile_number) OR m.tenant_phone LIKE CONCAT('%', u.mobile_number)))
                LIMIT 1");
            $stmt->execute([':u_user_id' => $userId, ':m_user_id' => $userId]);
            $society = $stmt->fetch();
            if ($society) {
                return $society;
            }
        } catch (PDOException $e) {}

        // Check if user created any society (Admin)
        try {
            $stmt = $this->db->prepare("SELECT s.* FROM societies s WHERE s.created_by_admin_id = :user_id ORDER BY s.id DESC LIMIT 1");
            $stmt->execute([':user_id' => $userId]);
            $society = $stmt->fetch();
            if ($society) {
                return $society;
            }
        } catch (PDOException $e) {}

        // Fallback: return first created society in database if exists
        try {
            $stmt = $this->db->query("SELECT * FROM societies ORDER BY id ASC LIMIT 1");
            $result = $stmt->fetch();
            return $result ?: null;
        } catch (PDOException $e) {
            return null;
        }
    }

    public function create($data, $adminId = null) {
        $stmt = $this->db->prepare("INSERT INTO societies (
            name, registration_number, registration_date, registered_address,
            pan_number, gstin, total_wings, total_flats, total_members,
            bank_balance, cash_in_hand, bank_name, account_number, created_by_admin_id
        ) VALUES (
            :name, :registration_number, :registration_date, :registered_address,
            :pan_number, :gstin, :total_wings, :total_flats, :total_members,
            :bank_balance, :cash_in_hand, :bank_name, :account_number, :admin_id
        )");

        $stmt->execute([
            ':name' => $data['society_name'],
            ':registration_number' => $data['registration_number'] ?? null,
            ':registration_date' => !empty($data['registration_date']) ? $data['registration_date'] : null,
            ':registered_address' => $data['registered_address'],
            ':pan_number' => strtoupper($data['pan_number']),
            ':gstin' => !empty($data['gstin']) ? strtoupper($data['gstin']) : null,
            ':total_wings' => intval($data['total_wings'] ?? 4),
            ':total_flats' => intval($data['total_flats'] ?? 84),
            ':total_members' => intval($data['total_members'] ?? 84),
            ':bank_balance' => floatval($data['bank_balance'] ?? 0),
            ':cash_in_hand' => floatval($data['cash_in_hand'] ?? 0),
            ':bank_name' => $data['bank_name'] ?? null,
            ':account_number' => $data['account_number'] ?? null,
            ':admin_id' => $adminId
        ]);

        return $this->db->lastInsertId();
    }
}
