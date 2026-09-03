<?php

require_once __DIR__ . '/../core/Model.php';

class Notice extends Model {

    public function getAll() {
        $stmt = $this->db->query("SELECT * FROM notices ORDER BY notice_date DESC, id DESC");
        return $stmt->fetchAll();
    }

    public function create($data) {
        $stmt = $this->db->prepare("INSERT INTO notices (
            notice_date, title, category, is_urgent, content
        ) VALUES (
            :notice_date, :title, :category, :is_urgent, :content
        )");

        return $stmt->execute([
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
