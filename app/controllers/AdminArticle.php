<?php
// app/controllers/AdminArticle.php

class AdminArticle extends Controller {

    private $articleModel;

    public function __construct() {
        parent::__construct();
        // Yêu cầu quyền admin
        $this->requireAdmin();
        $this->articleModel = $this->model('ArticleModel');
    }

    // Danh sách bài viết
    public function index() {
        $limit = 10;
        $page = isset($_GET['page']) ? (int)$_GET['page'] : 1;
        if ($page < 1) $page = 1;
        $offset = ($page - 1) * $limit;

        $searchKeyword = isset($_GET['search']) ? trim($_GET['search']) : '';

        $articles = $this->articleModel->getAllArticles($limit, $offset, $searchKeyword);
        $totalArticles = $this->articleModel->getTotalArticles($searchKeyword);
        $totalPages = ceil($totalArticles / $limit);

        $data = [
            'title' => 'Quản lý Bài viết',
            'articles' => $articles,
            'currentPage' => $page,
            'totalPages' => $totalPages,
            'searchKeyword' => $searchKeyword
        ];

        $this->view('admin/article/index', $data);
    }

    // Form thêm bài viết
    public function create() {
        // Lấy danh sách category
        $this->db = new Database();
        $this->db->query("SELECT * FROM category WHERE type = 'article'");
        $categories = $this->db->resultSet();

        $data = [
            'title' => 'Thêm Bài viết mới',
            'categories' => $categories
        ];

        $this->view('admin/article/create', $data);
    }

    // Xử lý thêm
    public function store() {
        if ($_SERVER['REQUEST_METHOD'] == 'POST') {
            $title = trim($_POST['title']);
            $content = trim($_POST['content']);
            $cateId = (int)$_POST['cateId'];
            $slug = strtolower(trim(preg_replace('/[^A-Za-z0-9-]+/', '-', $title)));

            $thumbnail = '';
            
            // Upload hình ảnh
            if (isset($_FILES['thumbnail']) && $_FILES['thumbnail']['error'] == 0) {
                $target_dir = APPROOT . "/../public/assets/uploads/articles/";
                if (!is_dir($target_dir)) {
                    mkdir($target_dir, 0777, true);
                }
                
                $fileName = time() . '_' . basename($_FILES["thumbnail"]["name"]);
                $target_file = $target_dir . $fileName;
                
                $imageFileType = strtolower(pathinfo($target_file,PATHINFO_EXTENSION));
                $allowed = ['jpg', 'jpeg', 'png', 'gif', 'webp'];
                
                if (in_array($imageFileType, $allowed)) {
                    if (move_uploaded_file($_FILES["thumbnail"]["tmp_name"], $target_file)) {
                        $thumbnail = 'assets/uploads/articles/' . $fileName;
                    }
                }
            }

            // Tạo contentId trước
            $this->db = new Database();
            $this->db->query("INSERT INTO content () VALUES ()");
            $this->db->execute();
            $contentId = $this->db->lastInsertId();

            $data = [
                'title' => $title,
                'content' => $content,
                'thumbnail' => $thumbnail,
                'published_at' => date('Y-m-d H:i:s'),
                'slug' => $slug,
                'authorId' => $_SESSION['user_id'],
                'cateId' => $cateId,
                'contentId' => $contentId
            ];

            if ($this->articleModel->addArticle($data)) {
                header("Location: " . URLROOT . "/AdminArticle?success=1");
            } else {
                header("Location: " . URLROOT . "/AdminArticle/create?error=1");
            }
            exit();
        }
    }

    // Form sửa bài viết
    public function edit($id) {
        $article = $this->articleModel->getArticleById($id);
        if (!$article) {
            header("Location: " . URLROOT . "/AdminArticle?error=notfound");
            exit();
        }

        $this->db = new Database();
        $this->db->query("SELECT * FROM category WHERE type = 'article'");
        $categories = $this->db->resultSet();

        $data = [
            'title' => 'Sửa Bài viết',
            'article' => $article,
            'categories' => $categories
        ];

        $this->view('admin/article/edit', $data);
    }

    // Xử lý cập nhật
    public function update($id) {
        if ($_SERVER['REQUEST_METHOD'] == 'POST') {
            $title = trim($_POST['title']);
            $content = trim($_POST['content']);
            $cateId = (int)$_POST['cateId'];
            $slug = strtolower(trim(preg_replace('/[^A-Za-z0-9-]+/', '-', $title)));

            $thumbnail = '';
            
            // Upload hình ảnh mới nếu có
            if (isset($_FILES['thumbnail']) && $_FILES['thumbnail']['error'] == 0) {
                $target_dir = APPROOT . "/../public/assets/uploads/articles/";
                if (!is_dir($target_dir)) {
                    mkdir($target_dir, 0777, true);
                }
                
                $fileName = time() . '_' . basename($_FILES["thumbnail"]["name"]);
                $target_file = $target_dir . $fileName;
                
                $imageFileType = strtolower(pathinfo($target_file,PATHINFO_EXTENSION));
                $allowed = ['jpg', 'jpeg', 'png', 'gif', 'webp'];
                
                if (in_array($imageFileType, $allowed)) {
                    if (move_uploaded_file($_FILES["thumbnail"]["tmp_name"], $target_file)) {
                        $thumbnail = 'assets/uploads/articles/' . $fileName;
                    }
                }
            }

            $data = [
                'title' => $title,
                'content' => $content,
                'slug' => $slug,
                'cateId' => $cateId
            ];
            
            if ($thumbnail !== '') {
                $data['thumbnail'] = $thumbnail;
            }

            if ($this->articleModel->updateArticle($id, $data)) {
                header("Location: " . URLROOT . "/AdminArticle?success=updated");
            } else {
                header("Location: " . URLROOT . "/AdminArticle/edit/" . $id . "?error=1");
            }
            exit();
        }
    }

    // Xóa bài viết
    public function delete($id) {
        if ($this->articleModel->deleteArticle($id)) {
            header("Location: " . URLROOT . "/AdminArticle?success=deleted");
        } else {
            header("Location: " . URLROOT . "/AdminArticle?error=delete");
        }
        exit();
    }
}
