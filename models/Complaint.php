<?php

require_once __DIR__ . '/../core/Model.php';

class Complaint extends Model {

    public function getAll($societyId = 1) {
        $stmt = $this->db->prepare("SELECT c.*, m.owner_name, m.owner_phone FROM complaints c LEFT JOIN members m ON c.member_id = m.id WHERE c.society_id = :society_id ORDER BY c.created_at DESC, c.id DESC");
        $stmt->execute([':society_id' => $societyId]);
        $results = $stmt->fetchAll();

        if (empty($results)) {
            $stmtFallback = $this->db->prepare("SELECT c.*, m.owner_name, m.owner_phone FROM complaints c LEFT JOIN members m ON c.member_id = m.id ORDER BY c.created_at DESC, c.id DESC");
            $stmtFallback->execute();
            $results = $stmtFallback->fetchAll();
        }

        return $results;
    }

    public function getByMember($memberId) {
        $stmt = $this->db->prepare("SELECT * FROM complaints WHERE member_id = :member_id ORDER BY created_at DESC");
        $stmt->execute([':member_id' => $memberId]);
        return $stmt->fetchAll();
    }

    public function create($data) {
        $stmt = $this->db->prepare("INSERT INTO complaints (
            society_id, member_id, flat_number, title, category, description, status
        ) VALUES (
            :society_id, :member_id, :flat_number, :title, :category, :description, 'Open'
        )");

        $stmt->execute([
            ':society_id' => $data['society_id'] ?? 1,
            ':member_id' => $data['member_id'] ?? 1,
            ':flat_number' => $data['flat_number'] ?? 'N/A',
            ':title' => $data['title'] ?? 'Complaint',
            ':category' => $data['category'] ?? 'General',
            ':description' => $data['description'] ?? ''
        ]);

        return $this->db->lastInsertId();
    }

    public function updateStatus($id, $status) {
        $validStatuses = ['Open', 'In Progress', 'Resolved', 'Closed'];
        if (!in_array($status, $validStatuses)) {
            $status = 'Open';
        }

        $stmt = $this->db->prepare("UPDATE complaints SET status = :status WHERE id = :id");
        return $stmt->execute([
            ':status' => $status,
            ':id' => $id
        ]);
    }
}
