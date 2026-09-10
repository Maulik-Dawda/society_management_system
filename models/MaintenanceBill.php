<?php

require_once __DIR__ . '/../core/Model.php';

class MaintenanceBill extends Model {

    public function getAll($societyId = 1) {
        $stmt = $this->db->prepare("SELECT * FROM maintenance_bills WHERE society_id = :society_id ORDER BY due_date DESC, flat_number ASC");
        $stmt->execute([':society_id' => $societyId]);
        return $stmt->fetchAll();
    }

    public function createBatch($cycle, $basis, $amount, $dueDate, $lateFeeRule, $societyId = 1) {
        $stmtMembers = $this->db->prepare("SELECT flat_number FROM members WHERE society_id = :society_id");
        $stmtMembers->execute([':society_id' => $societyId]);
        $flats = $stmtMembers->fetchAll(PDO::FETCH_COLUMN);

        if (empty($flats)) {
            $flats = ['A-101', 'A-102', 'A-201', 'B-101', 'B-102', 'B-201', 'C-101', 'C-102', 'D-101'];
        }

        $stmt = $this->db->prepare("INSERT INTO maintenance_bills (
            society_id, flat_number, billing_cycle, charge_basis, amount, due_date, late_fee_rule, status
        ) VALUES (
            :society_id, :flat_number, :billing_cycle, :charge_basis, :amount, :due_date, :late_fee_rule, 'Pending'
        )");

        $count = 0;
        foreach ($flats as $flat) {
            $stmt->execute([
                ':society_id' => $societyId,
                ':flat_number' => $flat,
                ':billing_cycle' => $cycle,
                ':charge_basis' => $basis,
                ':amount' => $amount,
                ':due_date' => $dueDate,
                ':late_fee_rule' => $lateFeeRule
            ]);
            $count++;
        }

        return $count;
    }
}
