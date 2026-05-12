<?php
// app/models/CommentModel.php

require_once 'BaseModel.php';

class CommentModel extends BaseModel {

    public function __construct() {
        parent::__construct();
    }

    // Lấy bình luận của 1 content cụ thể (bài viết hoặc sản phẩm)
    public function getCommentsByContentId($contentId, $limit = 10, $offset = 0) {
        $sql = "SELECT c.*, m.fullname as member_name, m.avatar as member_avatar
                FROM comment c
                LEFT JOIN user m ON c.memberId = m.userId
                WHERE c.contentId = :contentId AND c.status = 'approved'
                ORDER BY c.created_at DESC
                LIMIT :limit OFFSET :offset";
                
        $this->db->query($sql);
        $this->db->bind(':contentId', $contentId, PDO::PARAM_INT);
        $this->db->bind(':limit', $limit, PDO::PARAM_INT);
        $this->db->bind(':offset', $offset, PDO::PARAM_INT);
        
        return $this->db->resultSet();
    }

    // Đếm tổng số bình luận được duyệt của 1 content
    public function getTotalCommentsByContentId($contentId) {
        $sql = "SELECT COUNT(*) as total FROM comment WHERE contentId = :contentId AND status = 'approved'";
        $this->db->query($sql);
        $this->db->bind(':contentId', $contentId, PDO::PARAM_INT);
        $result = $this->db->single();
        return $result ? $result->total : 0;
    }

    // Dành cho ADMIN: Lấy tất cả bình luận
    public function getAllComments($limit = 10, $offset = 0, $searchKeyword = '') {
        $sql = "SELECT c.*, m.fullname as member_name, a.title as article_title, a.articleId as article_id, p.name as product_name
                FROM comment c
                LEFT JOIN user m ON c.memberId = m.userId
                LEFT JOIN article a ON c.contentId = a.contentId
                LEFT JOIN product p ON c.contentId = p.contentId";
        
        if (!empty($searchKeyword)) {
            $sql .= " WHERE c.content LIKE :keyword OR c.guest_name LIKE :keyword OR m.fullname LIKE :keyword";
        }
        
        $sql .= " ORDER BY c.created_at DESC LIMIT :limit OFFSET :offset";
        
        $this->db->query($sql);
        
        if (!empty($searchKeyword)) {
            $this->db->bind(':keyword', '%' . $searchKeyword . '%');
        }
        
        $this->db->bind(':limit', $limit, PDO::PARAM_INT);
        $this->db->bind(':offset', $offset, PDO::PARAM_INT);
        
        return $this->db->resultSet();
    }

    // Đếm tổng số bình luận (cho Admin)
    public function getTotalComments($searchKeyword = '') {
        $sql = "SELECT COUNT(*) as total 
                FROM comment c
                LEFT JOIN user m ON c.memberId = m.userId";
        
        if (!empty($searchKeyword)) {
            $sql .= " WHERE c.content LIKE :keyword OR c.guest_name LIKE :keyword OR m.fullname LIKE :keyword";
        }
        
        $this->db->query($sql);
        
        if (!empty($searchKeyword)) {
            $this->db->bind(':keyword', '%' . $searchKeyword . '%');
        }
        
        $result = $this->db->single();
        return $result ? $result->total : 0;
    }

    // Thêm bình luận mới
    public function addComment($data) {
        $sql = "INSERT INTO comment (guest_name, guest_email, content, rating, status, memberId, contentId) 
                VALUES (:guest_name, :guest_email, :content, :rating, :status, :memberId, :contentId)";
                
        $this->db->query($sql);
        
        $this->db->bind(':guest_name', $data['guest_name'] ?? null);
        $this->db->bind(':guest_email', $data['guest_email'] ?? null);
        $this->db->bind(':content', $data['content']);
        $this->db->bind(':rating', $data['rating'] ?? 5);
        $this->db->bind(':status', $data['status'] ?? 'pending'); // Mặc định chờ duyệt
        $this->db->bind(':memberId', $data['memberId'] ?? null);
        $this->db->bind(':contentId', $data['contentId']);
        
        return $this->db->execute();
    }

    // Cập nhật trạng thái bình luận
    public function updateCommentStatus($commentId, $status) {
        $this->db->query("UPDATE comment SET status = :status WHERE commentId = :id");
        $this->db->bind(':status', $status);
        $this->db->bind(':id', $commentId);
        return $this->db->execute();
    }

    // Xóa bình luận
    public function deleteComment($commentId) {
        $this->db->query("DELETE FROM comment WHERE commentId = :id");
        $this->db->bind(':id', $commentId);
        return $this->db->execute();
    }
}
