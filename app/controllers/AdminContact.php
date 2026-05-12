<?php
class AdminContact extends Controller
{
    private $contactModel;

    public function __construct()
    {
        parent::__construct();
        $this->requireAdmin();
        $this->contactModel = $this->model('ContactModel');
    }

    public function index()
    {
        $limit = 10;
        $page = max(1, (int)($_GET['page'] ?? 1));
        $search = trim($_GET['search'] ?? '');
        $status = trim($_GET['status'] ?? '');
        $allowed = ['', 'pending', 'read', 'replied'];

        if (!in_array($status, $allowed, true)) {
            $status = '';
        }

        $offset = ($page - 1) * $limit;
        $total = $this->contactModel->countAll($search, $status);

        $this->view('admin/contact/index', [
            'title' => 'Quản lý liên hệ',
            'contacts' => $this->contactModel->getAll($limit, $offset, $search, $status),
            'currentPage' => $page,
            'totalPages' => max(1, (int)ceil($total / $limit)),
            'search' => $search,
            'status' => $status,
        ]);
    }

    public function detail($id)
    {
        $contact = $this->contactModel->getById($id);
        if (!$contact) {
            header("Location: " . URLROOT . "/AdminContact?error=notfound");
            exit();
        }

        if ($contact->status === 'pending') {
            $this->contactModel->updateStatus($id, 'read', $contact->reply_content, $_SESSION['user_id']);
            $contact = $this->contactModel->getById($id);
        }

        $this->view('admin/contact/detail', [
            'title' => 'Chi tiết liên hệ',
            'contact' => $contact,
            'errors' => [],
        ]);
    }

    public function update($id)
    {
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            header("Location: " . URLROOT . "/AdminContact/detail/" . (int)$id);
            exit();
        }

        $contact = $this->contactModel->getById($id);
        if (!$contact) {
            header("Location: " . URLROOT . "/AdminContact?error=notfound");
            exit();
        }

        $status = trim($_POST['status'] ?? '');
        $replyContent = trim($_POST['reply_content'] ?? '');
        $allowed = ['pending', 'read', 'replied'];
        $errors = [];

        if (!in_array($status, $allowed, true)) {
            $errors['status'] = 'Trạng thái không hợp lệ.';
        }

        if ($replyContent !== '' && mb_strlen($replyContent, 'UTF-8') > 3000) {
            $errors['reply_content'] = 'Nội dung phản hồi tối đa 3000 ký tự.';
        }

        if ($status === 'replied' && $replyContent === '') {
            $errors['reply_content'] = 'Cần nhập nội dung phản hồi khi đánh dấu đã phản hồi.';
        }

        if (!empty($errors)) {
            $contact->status = $status;
            $contact->reply_content = $replyContent;
            $this->view('admin/contact/detail', [
                'title' => 'Chi tiết liên hệ',
                'contact' => $contact,
                'errors' => $errors,
            ]);
            return;
        }

        $this->contactModel->updateStatus($id, $status, $replyContent, $_SESSION['user_id']);
        header("Location: " . URLROOT . "/AdminContact/detail/" . (int)$id . "?success=updated");
        exit();
    }

    public function delete($id)
    {
        $this->contactModel->delete($id);
        header("Location: " . URLROOT . "/AdminContact?success=deleted");
        exit();
    }
}
