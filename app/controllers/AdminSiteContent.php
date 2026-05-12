<?php
class AdminSiteContent extends Controller
{
    private $siteContentModel;

    public function __construct()
    {
        parent::__construct();
        $this->requireAdmin();
        $this->siteContentModel = $this->model('SiteContentModel');
        $this->siteContentModel->ensureDefaults($_SESSION['user_id'] ?? null);
    }

    public function index()
    {
        $limit = 8;
        $page = max(1, (int)($_GET['page'] ?? 1));
        $search = trim($_GET['search'] ?? '');
        $offset = ($page - 1) * $limit;
        $total = $this->siteContentModel->countContentItems($search);

        $this->view('admin/site_content/index', [
            'title' => 'Quản lý nội dung trang',
            'items' => $this->siteContentModel->getContentItems($limit, $offset, $search),
            'currentPage' => $page,
            'totalPages' => max(1, (int)ceil($total / $limit)),
            'search' => $search,
        ]);
    }

    public function edit($id)
    {
        $item = $this->siteContentModel->getContentById($id);
        if (!$item) {
            header("Location: " . URLROOT . "/AdminSiteContent?error=notfound");
            exit();
        }

        $this->view('admin/site_content/edit', [
            'title' => 'Cập nhật nội dung',
            'item' => $item,
            'errors' => [],
        ]);
    }

    public function update($id)
    {
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            header("Location: " . URLROOT . "/AdminSiteContent/edit/" . (int)$id);
            exit();
        }

        $item = $this->siteContentModel->getContentById($id);
        $content = trim($_POST['content'] ?? '');
        $errors = [];

        if (!$item) {
            header("Location: " . URLROOT . "/AdminSiteContent?error=notfound");
            exit();
        }

        if ($content === '' || mb_strlen($content, 'UTF-8') > 3000) {
            $errors['content'] = 'Nội dung không được trống và tối đa 3000 ký tự.';
        }

        if (!empty($errors)) {
            $item->content = $content;
            $this->view('admin/site_content/edit', [
                'title' => 'Cập nhật nội dung',
                'item' => $item,
                'errors' => $errors,
            ]);
            return;
        }

        $this->siteContentModel->updateContent($id, $content, $_SESSION['user_id']);
        header("Location: " . URLROOT . "/AdminSiteContent?success=updated");
        exit();
    }

    public function media()
    {
        $limit = 6;
        $page = max(1, (int)($_GET['page'] ?? 1));
        $offset = ($page - 1) * $limit;
        $total = $this->siteContentModel->countImages();

        $this->view('admin/site_content/media', [
            'title' => 'Quản lý hình ảnh',
            'images' => $this->siteContentModel->getImages($limit, $offset),
            'currentPage' => $page,
            'totalPages' => max(1, (int)ceil($total / $limit)),
        ]);
    }

    public function editImage($id)
    {
        $image = $this->siteContentModel->getImageById($id);
        if (!$image) {
            header("Location: " . URLROOT . "/AdminSiteContent/media?error=notfound");
            exit();
        }

        $this->view('admin/site_content/edit_image', [
            'title' => 'Cập nhật hình ảnh',
            'image' => $image,
            'errors' => [],
        ]);
    }

    public function updateImage($id)
    {
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            header("Location: " . URLROOT . "/AdminSiteContent/editImage/" . (int)$id);
            exit();
        }

        $image = $this->siteContentModel->getImageById($id);
        if (!$image) {
            header("Location: " . URLROOT . "/AdminSiteContent/media?error=notfound");
            exit();
        }

        $result = $this->handleUpload('image');
        if (isset($result['error'])) {
            $this->view('admin/site_content/edit_image', [
                'title' => 'Cập nhật hình ảnh',
                'image' => $image,
                'errors' => ['image' => $result['error']],
            ]);
            return;
        }

        $this->siteContentModel->updateImage($id, $result['path'], $_SESSION['user_id']);
        header("Location: " . URLROOT . "/AdminSiteContent/media?success=updated");
        exit();
    }

    private function handleUpload($field)
    {
        if (!isset($_FILES[$field]) || $_FILES[$field]['error'] !== UPLOAD_ERR_OK) {
            return ['error' => 'Vui lòng chọn hình ảnh để tải lên.'];
        }

        if ($_FILES[$field]['size'] > 2 * 1024 * 1024) {
            return ['error' => 'Hình ảnh tối đa 2MB.'];
        }

        $tmp = $_FILES[$field]['tmp_name'];
        $original = $_FILES[$field]['name'];
        $extension = strtolower(pathinfo($original, PATHINFO_EXTENSION));
        $allowed = ['jpg', 'jpeg', 'png', 'gif', 'webp'];

        if (!in_array($extension, $allowed, true) || !@getimagesize($tmp)) {
            return ['error' => 'Chỉ chấp nhận file ảnh jpg, jpeg, png, gif hoặc webp.'];
        }

        $uploadDir = APPROOT . '/public/uploads/site/';
        if (!is_dir($uploadDir)) {
            mkdir($uploadDir, 0775, true);
        }

        $safeName = preg_replace('/[^A-Za-z0-9._-]/', '_', pathinfo($original, PATHINFO_FILENAME));
        $fileName = date('YmdHis') . '_' . $safeName . '.' . $extension;
        $target = $uploadDir . $fileName;

        if (!move_uploaded_file($tmp, $target)) {
            return ['error' => 'Không thể lưu hình ảnh lên server.'];
        }

        return ['path' => 'uploads/site/' . $fileName];
    }
}
