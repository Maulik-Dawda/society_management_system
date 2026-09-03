<?php

require_once __DIR__ . '/../core/Model.php';

class Expense extends Model {

    public function getAll() {
        $stmt = $this->db->query("SELECT * FROM expenses ORDER BY expense_date DESC, id DESC");
        return $stmt->fetchAll();
    }

    public function create($data) {
        $stmt = $this->db->prepare("INSERT INTO expenses (
            expense_date, category, vendor_name, bill_number, amount, gst_pct, payment_mode, notes, status
        ) VALUES (
            :expense_date, :category, :vendor_name, :bill_number, :amount, :gst_pct, :payment_mode, :notes, 'Paid'
        )");

        return $stmt->execute([
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
