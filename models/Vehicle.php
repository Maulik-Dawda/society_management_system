<?php

require_once __DIR__ . '/../core/Model.php';

class Vehicle extends Model {

    public function getAll($societyId = 1) {
        $stmt = $this->db->prepare("SELECT * FROM vehicles WHERE society_id = :society_id ORDER BY flat_number ASC, id DESC");
        $stmt->execute([':society_id' => $societyId]);
        return $stmt->fetchAll();
    }

    public function create($data) {
        $stmt = $this->db->prepare("INSERT INTO vehicles (
            society_id, flat_number, vehicle_number, make_model, vehicle_type, colour, parking_slot, status
        ) VALUES (
            :society_id, :flat_number, :vehicle_number, :make_model, :vehicle_type, :colour, :parking_slot, :status
        )");

        return $stmt->execute([
            ':society_id' => $data['society_id'] ?? 1,
            ':flat_number' => $data['flat_number'],
            ':vehicle_number' => strtoupper($data['vehicle_number']),
            ':make_model' => $data['make_model'] ?? null,
            ':vehicle_type' => $data['vehicle_type'] ?? 'Car',
            ':colour' => $data['colour'] ?? null,
            ':parking_slot' => $data['parking_slot'] ?? null,
            ':status' => $data['status'] ?? 'Active'
        ]);
    }

    public function delete($id) {
        $stmt = $this->db->prepare("DELETE FROM vehicles WHERE id = :id");
        return $stmt->execute([':id' => $id]);
    }
}
