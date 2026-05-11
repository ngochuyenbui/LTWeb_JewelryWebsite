<?php
// app/models/ArticleModel.php

require_once 'BaseModel.php';

class ArticleModel extends BaseModel {

    public function __construct() {
        parent::__construct();
    }

    // Lấy danh sách bài viết (có phân trang và tìm kiếm)
    public function getAllArticles($limit = 10, $offset = 0, $searchKeyword = '') {
        $sql = "SELECT a.*, c.name as category_name, u.fullname as author_name 
                FROM article a
                LEFT JOIN category c ON a.cateId = c.cateId
                LEFT JOIN user u ON a.authorId = u.userId";
        
        if (!empty($searchKeyword)) {
            $sql .= " WHERE a.title LIKE :keyword";
        }
        
        $sql .= " ORDER BY a.published_at DESC, a.articleId DESC LIMIT :limit OFFSET :offset";
        
        $this->db->query($sql);
        
        if (!empty($searchKeyword)) {
            $this->db->bind(':keyword', '%' . $searchKeyword . '%');
        }
        
        $this->db->bind(':limit', $limit, PDO::PARAM_INT);
        $this->db->bind(':offset', $offset, PDO::PARAM_INT);
        
        return $this->db->resultSet();
    }

    // Đếm tổng số bài viết để phân trang
    public function getTotalArticles($searchKeyword = '') {
        $sql = "SELECT COUNT(*) as total FROM article a";
        
        if (!empty($searchKeyword)) {
            $sql .= " WHERE a.title LIKE :keyword";
        }
        
        $this->db->query($sql);
        
        if (!empty($searchKeyword)) {
            $this->db->bind(':keyword', '%' . $searchKeyword . '%');
        }
        
        $result = $this->db->single();
        return $result ? $result->total : 0;
    }

    // Lấy danh sách danh mục có bài viết
    public function getAllCategories() {
        $this->db->query("SELECT DISTINCT c.* FROM category c INNER JOIN article a ON c.cateId = a.cateId ORDER BY c.cateId ASC");
        return $this->db->resultSet();
    }

    // Lấy bài viết theo danh mục (có phân trang)
    public function getArticlesByCategory($cateId, $limit = 10, $offset = 0) {
        $this->db->query("SELECT a.*, c.name as category_name, u.fullname as author_name 
                          FROM article a
                          LEFT JOIN category c ON a.cateId = c.cateId
                          LEFT JOIN user u ON a.authorId = u.userId
                          WHERE a.cateId = :cateId
                          ORDER BY a.published_at DESC, a.articleId DESC 
                          LIMIT :limit OFFSET :offset");
        $this->db->bind(':cateId', $cateId, PDO::PARAM_INT);
        $this->db->bind(':limit', $limit, PDO::PARAM_INT);
        $this->db->bind(':offset', $offset, PDO::PARAM_INT);
        return $this->db->resultSet();
    }

    // Đếm tổng số bài viết theo danh mục
    public function getTotalArticlesByCategory($cateId) {
        $this->db->query("SELECT COUNT(*) as total FROM article WHERE cateId = :cateId");
        $this->db->bind(':cateId', $cateId, PDO::PARAM_INT);
        $result = $this->db->single();
        return $result ? $result->total : 0;
    }

    // Lấy chi tiết bài viết theo ID
    public function getArticleById($id) {
        $this->db->query("SELECT a.*, c.name as category_name, u.fullname as author_name 
                          FROM article a
                          LEFT JOIN category c ON a.cateId = c.cateId
                          LEFT JOIN user u ON a.authorId = u.userId
                          WHERE a.articleId = :id");
        $this->db->bind(':id', $id);
        return $this->db->single();
    }

    // Lấy chi tiết bài viết theo Slug
    public function getArticleBySlug($slug) {
        $this->db->query("SELECT a.*, c.name as category_name, u.fullname as author_name 
                          FROM article a
                          LEFT JOIN category c ON a.cateId = c.cateId
                          LEFT JOIN user u ON a.authorId = u.userId
                          WHERE a.slug = :slug");
        $this->db->bind(':slug', $slug);
        return $this->db->single();
    }

    // Lấy danh sách bài viết liên quan (cùng danh mục)
    public function getRelatedArticles($cateId, $currentArticleId, $limit = 3) {
        $this->db->query("SELECT a.*, c.name as category_name, u.fullname as author_name 
                          FROM article a
                          LEFT JOIN category c ON a.cateId = c.cateId
                          LEFT JOIN user u ON a.authorId = u.userId
                          WHERE a.cateId = :cateId AND a.articleId != :currentId
                          ORDER BY a.published_at DESC, a.articleId DESC
                          LIMIT :limit");
        $this->db->bind(':cateId', $cateId);
        $this->db->bind(':currentId', $currentArticleId);
        $this->db->bind(':limit', $limit, PDO::PARAM_INT);
        return $this->db->resultSet();
    }

    // Thêm bài viết mới
    public function addArticle($data) {
        $this->db->query("INSERT INTO article (title, content, thumbnail, published_at, slug, authorId, cateId, contentId) 
                          VALUES (:title, :content, :thumbnail, :published_at, :slug, :authorId, :cateId, :contentId)");
        
        $this->db->bind(':title', $data['title']);
        $this->db->bind(':content', $data['content']);
        $this->db->bind(':thumbnail', $data['thumbnail']);
        $this->db->bind(':published_at', $data['published_at']);
        $this->db->bind(':slug', $data['slug']);
        $this->db->bind(':authorId', $data['authorId']);
        $this->db->bind(':cateId', $data['cateId']);
        $this->db->bind(':contentId', $data['contentId']);
        
        if ($this->db->execute()) {
            return $this->db->lastInsertId();
        } else {
            return false;
        }
    }

    // Cập nhật bài viết
    public function updateArticle($id, $data) {
        $sql = "UPDATE article SET 
                title = :title, 
                content = :content, 
                slug = :slug, 
                cateId = :cateId";
                
        if (isset($data['thumbnail']) && $data['thumbnail'] != '') {
            $sql .= ", thumbnail = :thumbnail";
        }
        
        $sql .= " WHERE articleId = :id";
        
        $this->db->query($sql);
        
        $this->db->bind(':title', $data['title']);
        $this->db->bind(':content', $data['content']);
        $this->db->bind(':slug', $data['slug']);
        $this->db->bind(':cateId', $data['cateId']);
        $this->db->bind(':id', $id);
        
        if (isset($data['thumbnail']) && $data['thumbnail'] != '') {
            $this->db->bind(':thumbnail', $data['thumbnail']);
        }
        
        return $this->db->execute();
    }

    // Xoá bài viết
    public function deleteArticle($id) {
        $this->db->query("DELETE FROM article WHERE articleId = :id");
        $this->db->bind(':id', $id);
        return $this->db->execute();
    }
}
