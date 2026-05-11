<?php
require_once 'BaseModel.php';

class CommentModel extends BaseModel {
    public function getCommentsByContentId($contentId) {
        $this->db->query("SELECT c.*, u.fullname FROM comment c LEFT JOIN user u ON c.memberId = u.userId WHERE c.contentId = :contentId ORDER BY c.commentId DESC");
        $this->db->bind(':contentId', $contentId);
        return $this->db->resultSet();
    }

    public function addComment($data) {
        $this->db->query("INSERT INTO comment (contentId, memberId, content, rating) VALUES (:contentId, :memberId, :content, :rating)");
        $this->db->bind(':contentId', $data['contentId']);
        $this->db->bind(':memberId', $data['userId']);
        $this->db->bind(':content', $data['content']);
        $this->db->bind(':rating', $data['rating']);
        return $this->db->execute();
    }
}