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

        // Lấy lỗi validation từ session (nếu có)
        $errors = $_SESSION['form_errors'] ?? [];
        $old    = $_SESSION['form_old']    ?? [];
        unset($_SESSION['form_errors'], $_SESSION['form_old']);

        $data = [
            'title'      => 'Thêm Bài viết mới',
            'categories' => $categories,
            'errors'     => $errors,
            'old'        => $old
        ];

        $this->view('admin/article/create', $data);
    }

    // Xử lý thêm
    public function store() {
        if ($_SERVER['REQUEST_METHOD'] == 'POST') {
            $title   = trim($_POST['title']   ?? '');
            $content = trim($_POST['content'] ?? '');
            $cateId  = (int)($_POST['cateId'] ?? 0);

            // ===== VALIDATE SERVER-SIDE =====
            $errors = [];

            if (empty($title)) {
                $errors['title'] = 'Tiêu đề không được để trống.';
            } elseif (mb_strlen($title) > 255) {
                $errors['title'] = 'Tiêu đề không được vượt quá 255 ký tự.';
            }

            if (empty($content) || strip_tags($content) === '') {
                $errors['content'] = 'Nội dung bài viết không được để trống.';
            }

            if ($cateId <= 0) {
                $errors['cateId'] = 'Vui lòng chọn danh mục.';
            }

            // Kiểm tra file ảnh (nếu có upload)
            if (isset($_FILES['thumbnail']) && $_FILES['thumbnail']['error'] !== UPLOAD_ERR_NO_FILE) {
                if ($_FILES['thumbnail']['error'] !== UPLOAD_ERR_OK) {
                    $errors['thumbnail'] = 'Lỗi khi tải ảnh lên. Vui lòng thử lại.';
                } else {
                    $allowedTypes = ['image/jpeg', 'image/png', 'image/gif', 'image/webp'];
                    $allowedExts  = ['jpg', 'jpeg', 'png', 'gif', 'webp'];
                    $maxSize      = 5 * 1024 * 1024; // 5MB
                    $ext = strtolower(pathinfo($_FILES['thumbnail']['name'], PATHINFO_EXTENSION));

                    if (!in_array($_FILES['thumbnail']['type'], $allowedTypes) || !in_array($ext, $allowedExts)) {
                        $errors['thumbnail'] = 'Định dạng ảnh không hợp lệ. Chỉ chấp nhận: JPG, PNG, GIF, WEBP.';
                    } elseif ($_FILES['thumbnail']['size'] > $maxSize) {
                        $errors['thumbnail'] = 'Kích thước ảnh không được vượt quá 5MB.';
                    }
                }
            }

            // Nếu có lỗi → lưu vào session và quay lại form
            if (!empty($errors)) {
                $_SESSION['form_errors'] = $errors;
                $_SESSION['form_old']    = ['title' => $title, 'content' => $content, 'cateId' => $cateId];
                header('Location: ' . URLROOT . '/AdminArticle/create');
                exit();
            }
            // ===== END VALIDATE =====

            $slug = strtolower(trim(preg_replace('/[^A-Za-z0-9-]+/', '-', $title)));
            $thumbnail = '';

            // Upload hình ảnh
            if (isset($_FILES['thumbnail']) && $_FILES['thumbnail']['error'] == UPLOAD_ERR_OK) {
                $target_dir = APPROOT . "/public/assets/uploads/articles/";
                if (!is_dir($target_dir)) {
                    mkdir($target_dir, 0777, true);
                }
                $fileName    = time() . '_' . basename($_FILES['thumbnail']['name']);
                $target_file = $target_dir . $fileName;
                if (move_uploaded_file($_FILES['thumbnail']['tmp_name'], $target_file)) {
                    $thumbnail = 'assets/uploads/articles/' . $fileName;
                }
            }

            // Tạo contentId trước
            $this->db = new Database();
            $this->db->query('INSERT INTO content () VALUES ()');
            $this->db->execute();
            $contentId = $this->db->lastInsertId();

            $data = [
                'title'        => $title,
                'content'      => $content,
                'thumbnail'    => $thumbnail,
                'published_at' => date('Y-m-d H:i:s'),
                'slug'         => $slug,
                'authorId'     => $_SESSION['user_id'],
                'cateId'       => $cateId,
                'contentId'    => $contentId
            ];

            if ($this->articleModel->addArticle($data)) {
                header('Location: ' . URLROOT . '/AdminArticle?success=created');
            } else {
                header('Location: ' . URLROOT . '/AdminArticle/create?error=db');
            }
            exit();
        }
    }

    // Form sửa bài viết
    public function edit($id) {
        $article = $this->articleModel->getArticleById($id);
        if (!$article) {
            header('Location: ' . URLROOT . '/AdminArticle?error=notfound');
            exit();
        }

        $this->db = new Database();
        $this->db->query("SELECT * FROM category WHERE type = 'article'");
        $categories = $this->db->resultSet();

        // Lấy lỗi validation từ session (nếu có)
        $errors = $_SESSION['form_errors'] ?? [];
        $old    = $_SESSION['form_old']    ?? [];
        unset($_SESSION['form_errors'], $_SESSION['form_old']);

        // Merge old input vào article để form hiện lại giá trị người dùng đã nhập
        if (!empty($old)) {
            $article->title   = $old['title']   ?? $article->title;
            $article->content = $old['content'] ?? $article->content;
            $article->cateId  = $old['cateId']  ?? $article->cateId;
        }

        $data = [
            'title'      => 'Sửa Bài viết',
            'article'    => $article,
            'categories' => $categories,
            'errors'     => $errors
        ];

        $this->view('admin/article/edit', $data);
    }

    // Xử lý cập nhật
    public function update($id) {
        if ($_SERVER['REQUEST_METHOD'] == 'POST') {
            $title   = trim($_POST['title']   ?? '');
            $content = trim($_POST['content'] ?? '');
            $cateId  = (int)($_POST['cateId'] ?? 0);

            // ===== VALIDATE SERVER-SIDE =====
            $errors = [];

            if (empty($title)) {
                $errors['title'] = 'Tiêu đề không được để trống.';
            } elseif (mb_strlen($title) > 255) {
                $errors['title'] = 'Tiêu đề không được vượt quá 255 ký tự.';
            }

            if (empty($content) || strip_tags($content) === '') {
                $errors['content'] = 'Nội dung bài viết không được để trống.';
            }

            if ($cateId <= 0) {
                $errors['cateId'] = 'Vui lòng chọn danh mục.';
            }

            // Kiểm tra file ảnh (nếu có upload)
            if (isset($_FILES['thumbnail']) && $_FILES['thumbnail']['error'] !== UPLOAD_ERR_NO_FILE) {
                if ($_FILES['thumbnail']['error'] !== UPLOAD_ERR_OK) {
                    $errors['thumbnail'] = 'Lỗi khi tải ảnh lên. Vui lòng thử lại.';
                } else {
                    $allowedTypes = ['image/jpeg', 'image/png', 'image/gif', 'image/webp'];
                    $allowedExts  = ['jpg', 'jpeg', 'png', 'gif', 'webp'];
                    $maxSize      = 5 * 1024 * 1024; // 5MB
                    $ext = strtolower(pathinfo($_FILES['thumbnail']['name'], PATHINFO_EXTENSION));

                    if (!in_array($_FILES['thumbnail']['type'], $allowedTypes) || !in_array($ext, $allowedExts)) {
                        $errors['thumbnail'] = 'Định dạng ảnh không hợp lệ. Chỉ chấp nhận: JPG, PNG, GIF, WEBP.';
                    } elseif ($_FILES['thumbnail']['size'] > $maxSize) {
                        $errors['thumbnail'] = 'Kích thước ảnh không được vượt quá 5MB.';
                    }
                }
            }

            // Nếu có lỗi → lưu vào session và quay lại form
            if (!empty($errors)) {
                $_SESSION['form_errors'] = $errors;
                $_SESSION['form_old']    = ['title' => $title, 'content' => $content, 'cateId' => $cateId];
                header('Location: ' . URLROOT . '/AdminArticle/edit/' . $id);
                exit();
            }
            // ===== END VALIDATE =====

            $slug      = strtolower(trim(preg_replace('/[^A-Za-z0-9-]+/', '-', $title)));
            $thumbnail = '';

            // Upload hình ảnh mới nếu có
            if (isset($_FILES['thumbnail']) && $_FILES['thumbnail']['error'] == UPLOAD_ERR_OK) {
                $target_dir = APPROOT . "/public/assets/uploads/articles/";
                if (!is_dir($target_dir)) {
                    mkdir($target_dir, 0777, true);
                }
                $fileName    = time() . '_' . basename($_FILES['thumbnail']['name']);
                $target_file = $target_dir . $fileName;
                if (move_uploaded_file($_FILES['thumbnail']['tmp_name'], $target_file)) {
                    $thumbnail = 'assets/uploads/articles/' . $fileName;
                }
            }

            $data = [
                'title'   => $title,
                'content' => $content,
                'slug'    => $slug,
                'cateId'  => $cateId
            ];

            if ($thumbnail !== '') {
                $data['thumbnail'] = $thumbnail;
            }

            if ($this->articleModel->updateArticle($id, $data)) {
                header('Location: ' . URLROOT . '/AdminArticle?success=updated');
            } else {
                header('Location: ' . URLROOT . '/AdminArticle/edit/' . $id . '?error=db');
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
