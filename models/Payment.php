<?php

require_once __DIR__ . '/../core/Model.php';

class Payment extends Model {

    public function getAll() {
        $stmt = $this->db->query("SELECT * FROM payments ORDER BY payment_date DESC, id DESC");
        return $stmt->fetchAll();
    }

    public function create($data) {
        $receiptNumber = 'RC-' . date('Y') . '-' . str_pad(rand(1, 9999), 4, '0', STR_PAD_LEFT);
        
        $stmt = $this->db->prepare("INSERT INTO payments (
            receipt_number, flat_number, owner_name, amount, payment_mode, payment_date, reference_no, status
        ) VALUES (
            :receipt_number, :flat_number, :owner_name, :amount, :payment_mode, :payment_date, :reference_no, 'Paid'
        )");

        $stmt->execute([
            ':receipt_number' => $receiptNumber,
            ':flat_number' => $data['flat_number'],
            ':owner_name' => $data['owner_name'] ?? 'Resident',
            ':amount' => $data['amount'],
            ':payment_mode' => $data['payment_mode'] ?? 'UPI',
            ':payment_date' => $data['payment_date'] ?? date('Y-m-d'),
            ':reference_no' => $data['reference_no'] ?? null
        ]);

        return $receiptNumber;
    }
}
