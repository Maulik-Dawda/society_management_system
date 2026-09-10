<?php

require_once __DIR__ . '/../core/Model.php';

class Notice extends Model {

    public function getAll($societyId = 1) {
        $societyId = intval($societyId ?: 1);
        try {
            $stmt = $this->db->prepare("SELECT * FROM notices WHERE society_id = :society_id ORDER BY id DESC");
            $stmt->execute([':society_id' => $societyId]);
            $results = $stmt->fetchAll();
            if (!empty($results)) {
                return $results;
            }
            
            // Fallback: If no notice matches specific society_id, fetch all created notices
            $stmtAll = $this->db->query("SELECT * FROM notices ORDER BY id DESC");
            return $stmtAll ? $stmtAll->fetchAll() : [];
        } catch (PDOException $e) {
            return [];
        }
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
