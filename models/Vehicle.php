<?php

require_once __DIR__ . '/../core/Model.php';

class Vehicle extends Model {

    public function getAll() {
        $stmt = $this->db->query("SELECT * FROM vehicles ORDER BY flat_number ASC, id DESC");
        return $stmt->fetchAll();
    }

    public function create($data) {
        $stmt = $this->db->prepare("INSERT INTO vehicles (
            flat_number, vehicle_number, make_model, vehicle_type, colour, parking_slot, status
        ) VALUES (
            :flat_number, :vehicle_number, :make_model, :vehicle_type, :colour, :parking_slot, :status
        )");

        return $stmt->execute([
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
