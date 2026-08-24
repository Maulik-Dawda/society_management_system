<?php

require_once __DIR__ . '/../core/Model.php';

class Society extends Model {

    public function findByUserId($userId) {
        $stmt = $this->db->prepare("SELECT s.* FROM societies s JOIN users u ON u.society_name = s.name WHERE u.id = :user_id LIMIT 1");
        $stmt->execute([':user_id' => $userId]);
        return $stmt->fetch();
    }

    public function saveDetails($data) {
        $stmt = $this->db->prepare("INSERT INTO societies (
            name, registration_number, registration_date, registered_address, pan_number, gstin,
            total_wings, total_flats, total_members, bank_balance, cash_in_hand, bank_name, account_number
        ) VALUES (
            :name, :reg_no, :reg_date, :address, :pan, :gstin,
            :wings, :flats, :members, :bank_bal, :cash_bal, :bank_name, :acc_no
        ) ON DUPLICATE KEY UPDATE 
            registration_number = VALUES(registration_number),
            registration_date = VALUES(registration_date),
            registered_address = VALUES(registered_address),
            pan_number = VALUES(pan_number),
            gstin = VALUES(gstin),
            total_wings = VALUES(total_wings),
            total_flats = VALUES(total_flats),
            total_members = VALUES(total_members),
            bank_balance = VALUES(bank_balance),
            cash_in_hand = VALUES(cash_in_hand),
            bank_name = VALUES(bank_name),
            account_number = VALUES(account_number)");

        return $stmt->execute([
            ':name' => $data['society_name'],
            ':reg_no' => $data['registration_number'] ?? null,
            ':reg_date' => !empty($data['registration_date']) ? $data['registration_date'] : null,
            ':address' => $data['registered_address'],
            ':pan' => strtoupper($data['pan_number']),
            ':gstin' => strtoupper($data['gstin'] ?? ''),
            ':wings' => $data['total_wings'] ?? 4,
            ':flats' => $data['total_flats'] ?? 84,
            ':members' => $data['total_members'] ?? 84,
            ':bank_bal' => $data['bank_balance'] ?? 0,
            ':cash_bal' => $data['cash_in_hand'] ?? 0,
            ':bank_name' => $data['bank_name'] ?? null,
            ':acc_no' => $data['account_number'] ?? null
        ]);
    }
}
