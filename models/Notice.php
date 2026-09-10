<?php

require_once __DIR__ . '/../core/Model.php';

class Notice extends Model {

    public function getAll($societyId = 1) {
        $stmt = $this->db->prepare("SELECT * FROM notices WHERE society_id = :society_id ORDER BY notice_date DESC, id DESC");
        $stmt->execute([':society_id' => $societyId]);
        return $stmt->fetchAll();
    }

    public function create($data) {
        $stmt = $this->db->prepare("INSERT INTO notices (
            society_id, created_by_user_id, notice_date, title, category, is_urgent, content
        ) VALUES (
            :society_id, :user_id, :notice_date, :title, :category, :is_urgent, :content
        )");

        return $stmt->execute([
            ':society_id' => $data['society_id'] ?? 1,
            ':user_id' => $data['created_by_user_id'] ?? null,
            ':notice_date' => $data['notice_date'] ?? date('Y-m-d'),
            ':title' => $data['title'],
            ':category' => $data['category'] ?? 'General',
            ':is_urgent' => !empty($data['is_urgent']) ? 1 : 0,
            ':content' => $data['content']
        ]);
    }

    public function delete($id) {
        $stmt = $this->db->prepare("DELETE FROM notices WHERE id = :id");
        return $stmt->execute([':id' => $id]);
    }
}
