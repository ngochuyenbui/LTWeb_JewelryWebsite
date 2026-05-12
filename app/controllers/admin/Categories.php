<?php
class Categories extends Controller {
    private $categoryModel;

    public function __construct() {
        parent::__construct();
        $this->requireAdmin();
        $this->categoryModel = $this->model('CategoryModel');
    }

    public function index() {
        $limit = 5;
        $page = isset($_GET['page']) ? max(1, (int)$_GET['page']) : 1;
        $offset = ($page - 1) * $limit;

        $totalItems = $this->categoryModel->getTotalCategories();
        $totalPages = ceil($totalItems / $limit);

        $categories = $this->categoryModel->getCategoriesPaginated($limit, $offset);

        $this->view('admin/category/index', [
            'title' => 'Quản lý Danh mục',
            'categories' => $categories,
            'currentPage' => $page,
            'totalPages' => $totalPages,
            'totalItems' => $totalItems
        ]);
    }

    private function toSlug($str) {
        $str = mb_strtolower($str, 'UTF-8');
        $str = preg_replace('/(à|á|ạ|ả|ã|â|ầ|ấ|ậ|ẩ|ẫ|ẫ|ă|ằ|ắ|ặ|ẳ|ẵ)/', 'a', $str);
        $str = preg_replace('/(è|é|ẹ|ẻ|ẽ|ê|ề|ế|ệ|ể|ễ)/', 'e', $str);
        $str = preg_replace('/(ì|í|ị|ỉ|ĩ)/', 'i', $str);
        $str = preg_replace('/(ò|ó|ọ|ỏ|õ|ô|ồ|ố|ộ|ổ|ỗ|ơ|ờ|ớ|ợ|ở|ỡ)/', 'o', $str);
        $str = preg_replace('/(ù|ú|ụ|ủ|ũ|ư|ừ|ứ|ự|ử|ữ)/', 'u', $str);
        $str = preg_replace('/(ỳ|ý|ỵ|ỷ|ỹ)/', 'y', $str);
        $str = preg_replace('/(đ)/', 'd', $str);
        $str = preg_replace('/[^a-z0-9\-]+/', '-', $str);
        return trim($str, '-');
    }

    private function uploadImage($file) {
        $uploadDir = '../public/assets/uploads/categories/';
        if (!is_dir($uploadDir)) {
            @mkdir($uploadDir, 0777, true);
        }
        if ($file['error'] === UPLOAD_ERR_OK) {
            $ext = strtolower(pathinfo($file['name'], PATHINFO_EXTENSION));
            $allowedExts = ['jpg', 'jpeg', 'png', 'gif', 'webp'];
            if (in_array($ext, $allowedExts)) {
                $newName = uniqid() . '_' . time() . '.' . $ext;
                if (move_uploaded_file($file['tmp_name'], $uploadDir . $newName)) {
                    return '/assets/uploads/categories/' . $newName;
                }
            }
        }
        return null;
    }

    public function create() {
        $error = '';
        if ($_SERVER['REQUEST_METHOD'] == 'POST') {
            $name = trim($_POST['name'] ?? '');
            $type = trim($_POST['type'] ?? '');
            $slug = trim($_POST['slug'] ?? '');
            if (empty($slug)) $slug = $this->toSlug($name);
            $imageUrl = '';

            if (!empty($_FILES['image']['name'])) {
                $imageUrl = $this->uploadImage($_FILES['image']);
            }

            if (empty($name)) {
                $error = 'Tên danh mục không được để trống.';
            } else {
                try {
                    $this->categoryModel->addCategory([
                        'name' => $name,
                        'image_url' => $imageUrl,
                        'type' => $type,
                        'slug' => $slug,
                        'is_hidden' => 0
                    ]);
                    $_SESSION['success'] = 'Thêm danh mục thành công!';
                    header('Location: ' . URLROOT . '/admin/Categories');
                    exit;
                } catch (PDOException $e) {
                    $error = 'Lỗi truy vấn: ' . $e->getMessage();
                }
            }
        }
        $this->view('admin/category/create', ['title' => 'Thêm Danh mục', 'error' => $error]);
    }

    public function edit($id = null) {
        if (!$id) { header('Location: ' . URLROOT . '/admin/Categories'); exit; }
        $error = '';
        $category = $this->categoryModel->getCategoryById($id);
        if (!$category) { header('Location: ' . URLROOT . '/admin/Categories'); exit; }

        if ($_SERVER['REQUEST_METHOD'] == 'POST') {
            $name = trim($_POST['name'] ?? '');
            $type = trim($_POST['type'] ?? '');
            $slug = trim($_POST['slug'] ?? '');
            if (empty($slug)) $slug = $this->toSlug($name);
            $isHidden = isset($_POST['is_hidden']) ? 1 : 0;
            $imageUrl = is_object($category) ? ($category->image_url ?? '') : ($category['image_url'] ?? '');

            if (!empty($_FILES['image']['name'])) {
                $newImage = $this->uploadImage($_FILES['image']);
                if ($newImage) $imageUrl = $newImage;
            }

            if (empty($name)) {
                $error = 'Tên danh mục không được để trống.';
            } else {
                try {
                    $this->categoryModel->updateCategory([
                        'cateId' => $id,
                        'name' => $name,
                        'image_url' => $imageUrl,
                        'type' => $type,
                        'slug' => $slug,
                        'is_hidden' => $isHidden
                    ]);
                    $_SESSION['success'] = 'Cập nhật danh mục thành công!';
                    header('Location: ' . URLROOT . '/admin/Categories');
                    exit;
                } catch (PDOException $e) {
                    $error = 'Lỗi truy vấn: ' . $e->getMessage();
                }
            }
        }
        $this->view('admin/category/edit', ['title' => 'Sửa Danh mục', 'category' => $category, 'error' => $error]);
    }

    public function toggleHide($id = null) {
        header('Content-Type: application/json');
        if ($id && $_SERVER['REQUEST_METHOD'] == 'POST') {
            $isHidden = (int)$_POST['is_hidden'];

            // Nếu muốn ẨN, kiểm tra xem có sản phẩm nào không
            if ($isHidden === 1) {
                $activeItems = $this->categoryModel->countActiveItemsByCategory($id);
                if ($activeItems > 0) {
                    echo json_encode(['success' => false, 'message' => "Không thể ẨN. Danh mục này đang chứa $activeItems sản phẩm/bài viết đang hoạt động."]);
                    exit;
                }
            }

            $this->categoryModel->toggleHidden($id, $isHidden);
            echo json_encode(['success' => true, 'message' => 'Cập nhật trạng thái thành công.']);
            exit;
        }
        echo json_encode(['success' => false, 'message' => 'Lỗi yêu cầu.']);
        exit;
    }
}