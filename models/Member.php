<?php

require_once __DIR__ . '/../core/Model.php';

class Member extends Model {

    public function getAll($societyId = 1) {
        $stmt = $this->db->prepare("SELECT * FROM members WHERE society_id = :society_id AND (owner_email IS NULL OR LOWER(TRIM(owner_email)) != 'maulik@septixtechnologies.com') ORDER BY flat_number ASC");
        $stmt->execute([':society_id' => $societyId]);
        return $stmt->fetchAll();
    }

    public function getCommitteeMembers($societyId = 1) {
        $stmt = $this->db->prepare("SELECT * FROM members WHERE society_id = :society_id AND (committee_role LIKE '%Chairman%' OR committee_role LIKE '%Secretary%' OR committee_role LIKE '%Treasurer%' OR committee_role LIKE '%Committee Member%') AND (owner_email IS NULL OR LOWER(TRIM(owner_email)) != 'maulik@septixtechnologies.com') ORDER BY flat_number ASC");
        $stmt->execute([':society_id' => $societyId]);
        return $stmt->fetchAll();
    }

    public function getResidents($societyId = 1) {
        $stmt = $this->db->prepare("SELECT * FROM members WHERE society_id = :society_id AND (owner_email IS NULL OR LOWER(TRIM(owner_email)) != 'maulik@septixtechnologies.com') ORDER BY flat_number ASC");
        $stmt->execute([':society_id' => $societyId]);
        return $stmt->fetchAll();
    }

    public function findById($id) {
        $stmt = $this->db->prepare("SELECT * FROM members WHERE id = :id");
        $stmt->execute([':id' => $id]);
        return $stmt->fetch();
    }

    public function getMemberByPhoneAndSociety($phone, $societyId) {
        $cleanPhone = preg_replace('/[^0-9]/', '', $phone);
        if (empty($cleanPhone)) return null;
        $last10 = (strlen($cleanPhone) >= 10) ? substr($cleanPhone, -10) : $cleanPhone;

        $stmt = $this->db->prepare("SELECT * FROM members WHERE society_id = :society_id AND (owner_phone LIKE :owner_phone OR tenant_phone LIKE :tenant_phone) LIMIT 1");
        $stmt->execute([
            ':society_id' => $societyId,
            ':owner_phone' => "%{$last10}",
            ':tenant_phone' => "%{$last10}"
        ]);
        $member = $stmt->fetch();

        if (!$member) {
            $stmt2 = $this->db->prepare("SELECT * FROM members WHERE owner_phone LIKE :owner_phone OR tenant_phone LIKE :tenant_phone LIMIT 1");
            $stmt2->execute([
                ':owner_phone' => "%{$last10}",
                ':tenant_phone' => "%{$last10}"
            ]);
            $member = $stmt2->fetch();
        }

        return $member;
    }

    public function updateCommitteeRole($memberId, $role) {
        $member = $this->findById($memberId);
        if (!$member) return false;

        $cleanRole = trim(str_replace('Resident', '', $role));
        $cleanRole = trim(str_replace(',', '', $cleanRole));

        if (empty($cleanRole) || $cleanRole === 'Resident') {
            $finalRole = 'Resident';
        } else {
            $finalRole = "Resident, {$cleanRole}";
        }

        // Reset existing bearer if assigning single-person office bearer
        if (in_array($cleanRole, ['Chairman', 'Secretary', 'Treasurer'])) {
            $resetStmt = $this->db->prepare("UPDATE members SET committee_role = 'Resident' WHERE society_id = :society_id AND committee_role LIKE :rolePattern");
            $resetStmt->execute([
                ':society_id' => $member['society_id'],
                ':rolePattern' => "%{$cleanRole}%"
            ]);
        }

        $stmt = $this->db->prepare("UPDATE members SET committee_role = :role WHERE id = :id");
        return $stmt->execute([
            ':role' => $finalRole,
            ':id' => $memberId
        ]);
    }

    public function create($data) {
        $inputRole = trim($data['committee_role'] ?? 'Resident');
        $cleanRole = trim(str_replace('Resident', '', $inputRole));
        $cleanRole = trim(str_replace(',', '', $cleanRole));

        if (empty($cleanRole) || $cleanRole === 'Resident') {
            $finalRole = 'Resident';
        } else {
            $finalRole = "Resident, {$cleanRole}";
        }

        $stmt = $this->db->prepare("INSERT INTO members (
            society_id, user_id, flat_number, area_sqft, owner_name, owner_phone, owner_email,
            is_rented, tenant_name, tenant_phone, agreement_start, agreement_end, id_proof, committee_role
        ) VALUES (
            :society_id, :user_id, :flat_number, :area_sqft, :owner_name, :owner_phone, :owner_email,
            :is_rented, :tenant_name, :tenant_phone, :agreement_start, :agreement_end, :id_proof, :committee_role
        )");

        $stmt->execute([
            ':society_id' => $data['society_id'] ?? 1,
            ':user_id' => $data['user_id'] ?? null,
            ':flat_number' => $data['flat_number'],
            ':area_sqft' => $data['area_sqft'] ?? 0,
            ':owner_name' => $data['owner_name'],
            ':owner_phone' => $data['owner_phone'],
            ':owner_email' => $data['owner_email'] ?? null,
            ':is_rented' => !empty($data['is_rented']) ? 1 : 0,
            ':tenant_name' => $data['tenant_name'] ?? null,
            ':tenant_phone' => $data['tenant_phone'] ?? null,
            ':agreement_start' => !empty($data['agreement_start']) ? $data['agreement_start'] : null,
            ':agreement_end' => !empty($data['agreement_end']) ? $data['agreement_end'] : null,
            ':id_proof' => $data['id_proof'] ?? null,
            ':committee_role' => $finalRole
        ]);

        return $this->db->lastInsertId();
    }
}
