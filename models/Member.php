<?php

require_once __DIR__ . '/../core/Model.php';

class Member extends Model {

    public function getAll() {
        $stmt = $this->db->query("SELECT * FROM members WHERE owner_email IS NULL OR LOWER(TRIM(owner_email)) != 'maulik@septixtechnologies.com' ORDER BY flat_number ASC");
        return $stmt->fetchAll();
    }

    public function getCommitteeMembers() {
        $stmt = $this->db->query("SELECT * FROM members WHERE committee_role != 'Resident' AND (owner_email IS NULL OR LOWER(TRIM(owner_email)) != 'maulik@septixtechnologies.com') ORDER BY FIELD(committee_role, 'Chairman', 'Secretary', 'Treasurer', 'Committee Member') ASC, flat_number ASC");
        return $stmt->fetchAll();
    }

    public function getResidents() {
        $stmt = $this->db->query("SELECT * FROM members WHERE (committee_role IS NULL OR committee_role = 'Resident') AND (owner_email IS NULL OR LOWER(TRIM(owner_email)) != 'maulik@septixtechnologies.com') ORDER BY flat_number ASC");
        return $stmt->fetchAll();
    }

    public function findById($id) {
        $stmt = $this->db->prepare("SELECT * FROM members WHERE id = :id");
        $stmt->execute([':id' => $id]);
        return $stmt->fetch();
    }

    public function updateCommitteeRole($memberId, $role) {
        $validRoles = ['Resident', 'Chairman', 'Secretary', 'Treasurer', 'Committee Member'];
        if (!in_array($role, $validRoles)) {
            $role = 'Resident';
        }

        // If assigning a single-person office bearer (Chairman, Secretary, Treasurer), reset existing bearer to Resident
        if (in_array($role, ['Chairman', 'Secretary', 'Treasurer'])) {
            $resetStmt = $this->db->prepare("UPDATE members SET committee_role = 'Resident' WHERE committee_role = :role");
            $resetStmt->execute([':role' => $role]);
        }

        $stmt = $this->db->prepare("UPDATE members SET committee_role = :role WHERE id = :id");
        return $stmt->execute([
            ':role' => $role,
            ':id' => $memberId
        ]);
    }

    public function create($data) {
        $stmt = $this->db->prepare("INSERT INTO members (
            flat_number, area_sqft, owner_name, owner_phone, owner_email,
            is_rented, tenant_name, tenant_phone, agreement_start, agreement_end, id_proof, committee_role
        ) VALUES (
            :flat_number, :area_sqft, :owner_name, :owner_phone, :owner_email,
            :is_rented, :tenant_name, :tenant_phone, :agreement_start, :agreement_end, :id_proof, :committee_role
        )");

        $stmt->execute([
            ':flat_number' => $data['flat_number'],
            ':area_sqft' => $data['area_sqft'] ?? 0,
            ':owner_name' => $data['owner_name'],
            ':owner_phone' => $data['owner_phone'] ?? null,
            ':owner_email' => $data['owner_email'] ?? null,
            ':is_rented' => !empty($data['is_rented']) ? 1 : 0,
            ':tenant_name' => $data['tenant_name'] ?? null,
            ':tenant_phone' => $data['tenant_phone'] ?? null,
            ':agreement_start' => !empty($data['agreement_start']) ? $data['agreement_start'] : null,
            ':agreement_end' => !empty($data['agreement_end']) ? $data['agreement_end'] : null,
            ':id_proof' => $data['id_proof'] ?? null,
            ':committee_role' => $data['committee_role'] ?? 'Resident'
        ]);

        return $this->db->lastInsertId();
    }

    public function delete($id) {
        $stmt = $this->db->prepare("DELETE FROM members WHERE id = :id");
        return $stmt->execute([':id' => $id]);
    }
}
