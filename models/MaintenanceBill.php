<?php

require_once __DIR__ . '/../core/Model.php';

class MaintenanceBill extends Model {

    public function getAll() {
        $stmt = $this->db->query("SELECT * FROM maintenance_bills ORDER BY due_date DESC, flat_number ASC");
        return $stmt->fetchAll();
    }

    public function createBatch($cycle, $basis, $amount, $dueDate, $lateFeeRule, $applyTo = 'All') {
        $stmtMembers = $this->db->query("SELECT flat_number FROM members");
        $flats = $stmtMembers->fetchAll(PDO::FETCH_COLUMN);

        if (empty($flats)) {
            // Default flat list if no members added yet
            $flats = ['A-101', 'A-102', 'A-201', 'B-101', 'B-102', 'B-201', 'C-101', 'C-102', 'D-101'];
        }

        $stmt = $this->db->prepare("INSERT INTO maintenance_bills (
            flat_number, billing_cycle, charge_basis, amount, due_date, late_fee_rule, status
        ) VALUES (
            :flat_number, :billing_cycle, :charge_basis, :amount, :due_date, :late_fee_rule, 'Pending'
        )");

        $count = 0;
        foreach ($flats as $flat) {
            $stmt->execute([
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
