<?php

require_once __DIR__ . '/../core/Model.php';

class Expense extends Model {

    public function getAll($societyId = 1) {
        $stmt = $this->db->prepare("SELECT * FROM expenses WHERE society_id = :society_id ORDER BY expense_date DESC, id DESC");
        $stmt->execute([':society_id' => $societyId]);
        return $stmt->fetchAll();
    }

    public function create($data) {
        $stmt = $this->db->prepare("INSERT INTO expenses (
            society_id, expense_date, category, vendor_name, bill_number, amount, gst_pct, payment_mode, notes, status
        ) VALUES (
            :society_id, :expense_date, :category, :vendor_name, :bill_number, :amount, :gst_pct, :payment_mode, :notes, 'Paid'
        )");

        return $stmt->execute([
            ':society_id' => $data['society_id'] ?? 1,
            ':expense_date' => $data['expense_date'] ?? date('Y-m-d'),
            ':category' => $data['category'],
            ':vendor_name' => $data['vendor_name'],
            ':bill_number' => $data['bill_number'] ?? null,
            ':amount' => $data['amount'],
            ':gst_pct' => $data['gst_pct'] ?? 18.00,
            ':payment_mode' => $data['payment_mode'] ?? 'Bank transfer',
            ':notes' => $data['notes'] ?? null
        ]);
    }
}
