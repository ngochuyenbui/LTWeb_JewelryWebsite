<?php
// app/controllers/AdminComment.php

class AdminComment extends Controller {

    private $commentModel;

    public function __construct() {
        parent::__construct();
        $this->requireAdmin();
        $this->commentModel = $this->model('CommentModel');
    }

    // Danh sách bình luận
    public function index() {
        $limit = 10;
        $page = isset($_GET['page']) ? (int)$_GET['page'] : 1;
        if ($page < 1) $page = 1;
        $offset = ($page - 1) * $limit;

        $searchKeyword = isset($_GET['search']) ? trim($_GET['search']) : '';

        $comments = $this->commentModel->getAllComments($limit, $offset, $searchKeyword);
        $totalComments = $this->commentModel->getTotalComments($searchKeyword);
        $totalPages = ceil($totalComments / $limit);

        $data = [
            'title' => 'Quản lý Bình luận',
            'comments' => $comments,
            'currentPage' => $page,
            'totalPages' => $totalPages,
            'searchKeyword' => $searchKeyword
        ];

        $this->view('admin/comment/index', $data);
    }

    // Cập nhật trạng thái duyệt/ẩn
    public function updateStatus() {
        if ($_SERVER['REQUEST_METHOD'] == 'POST') {
            $commentId = (int)$_POST['commentId'];
            $status = trim($_POST['status']);
            
            $allowed_status = ['approved', 'pending', 'hidden'];
            if (in_array($status, $allowed_status)) {
                $this->commentModel->updateCommentStatus($commentId, $status);
            }
            
            header("Location: " . URLROOT . "/AdminComment?success=updated");
            exit();
        }
    }

    // Xóa bình luận
    public function delete($id) {
        if ($this->commentModel->deleteComment($id)) {
            header("Location: " . URLROOT . "/AdminComment?success=deleted");
        } else {
            header("Location: " . URLROOT . "/AdminComment?error=delete");
        }
        exit();
    }
}
