<?php
require_once 'BaseModel.php';
class CategoryModel extends BaseModel {
    public function getCategories() {
        $this->db->query("SELECT * FROM category ORDER BY cateId DESC");
        return $this->db->resultSet();
    }

    public function getCategoryById($id) {
        $this->db->query("SELECT * FROM category WHERE cateId = :id");
        $this->db->bind(':id', $id);
        return $this->db->single();
    }

    public function addCategory($data) {
        $this->db->query("INSERT INTO category (name, image_url, type, slug, is_hidden) VALUES (:name, :image_url, :type, :slug, :is_hidden)");
        $this->db->bind(':name', $data['name']);
        $this->db->bind(':image_url', $data['image_url']);
        $this->db->bind(':type', $data['type']);
        $this->db->bind(':slug', $data['slug']);
        $this->db->bind(':is_hidden', $data['is_hidden'] ?? 0);
        return $this->db->execute();
    }

    public function updateCategory($data) {
        $this->db->query("UPDATE category SET name = :name, image_url = :image_url, type = :type, slug = :slug, is_hidden = :is_hidden WHERE cateId = :id");
        $this->db->bind(':id', $data['cateId']);
        $this->db->bind(':name', $data['name']);
        $this->db->bind(':image_url', $data['image_url']);
        $this->db->bind(':type', $data['type']);
        $this->db->bind(':slug', $data['slug']);
        $this->db->bind(':is_hidden', $data['is_hidden'] ?? 0);
        return $this->db->execute();
    }

    public function countActiveItemsByCategory($cateId) {
        $total = 0;
        $this->db->query("SELECT COUNT(*) as total FROM product WHERE cateId = :cateId AND is_deleted = 0");
        $this->db->bind(':cateId', $cateId);
        $row = $this->db->single();
        if ($row) $total += (int)(is_object($row) ? $row->total : $row['total']);

        try {
            $this->db->query("SELECT COUNT(*) as total FROM article WHERE cateId = :cateId");
            $this->db->bind(':cateId', $cateId);
            $row2 = $this->db->single();
            if ($row2) $total += (int)(is_object($row2) ? $row2->total : $row2['total']);
        } catch (PDOException $e) {}

        return $total;
    }

    public function toggleHidden($cateId, $isHidden) {
        $this->db->query("UPDATE category SET is_hidden = :is_hidden WHERE cateId = :cateId");
        $this->db->bind(':is_hidden', $isHidden);
        $this->db->bind(':cateId', $cateId);
        return $this->db->execute();
    }

    public function getTotalCategories() {
        $this->db->query("SELECT COUNT(*) as total FROM category");
        $row = $this->db->single();
        return $row ? (int)(is_object($row) ? $row->total : $row['total']) : 0;
    }

    public function getCategoriesPaginated($limit, $offset) {
        $this->db->query("SELECT * FROM category ORDER BY cateId DESC LIMIT :limit OFFSET :offset");
        $this->db->bind(':limit', $limit, PDO::PARAM_INT);
        $this->db->bind(':offset', $offset, PDO::PARAM_INT);
        return $this->db->resultSet();
    }

}