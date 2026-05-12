<?php
class Users extends Controller {
    private $userAdminModel;

    public function __construct() {
        parent::__construct();
        $this->requireAdmin();
        $this->userAdminModel = $this->model('UserAdminModel');
    }

    public function index() {
        $limit = 5;
        $page = isset($_GET['page']) ? max(1, (int)$_GET['page']) : 1;
        $offset = ($page - 1) * $limit;
        $search = trim($_GET['search'] ?? '');

        $users = $this->userAdminModel->getAllUsersPaginated($limit, $offset, $search);
        $totalItems = $this->userAdminModel->getTotalUsersCount($search);
        $totalPages = ceil($totalItems / $limit);

        $this->view('admin/users/index', [
            'title' => 'Quản lý Khách hàng',
            'users' => $users,
            'currentPage' => $page,
            'totalPages' => $totalPages,
            'search' => $search
        ]);
    }

    public function detail($userId = null) {
        if (!$userId) {
            echo '<div class="alert alert-danger">ID không hợp lệ</div>';
            exit;
        }
        $user = $this->userAdminModel->getUserDetails($userId);
        if ($user) {
            if (is_object($user)) $user = (array)$user;
            
            $statusBadge = ($user['role'] === 'locked') ? '<span class="badge badge-pill bg-danger text-white" style="padding: 8px 12px; font-size: 13px;">Bị khóa</span>' : '<span class="badge badge-pill bg-success text-white" style="padding: 8px 12px; font-size: 13px;">Hoạt động</span>';
            
            echo '<table class="table table-bordered">';
            echo '<tr><th style="width: 35%;">Mã khách hàng</th><td>#' . htmlspecialchars($user['userId']) . '</td></tr>';
            echo '<tr><th>Tên tài khoản</th><td>' . htmlspecialchars($user['username']) . '</td></tr>';
            echo '<tr><th>Họ tên</th><td>' . htmlspecialchars($user['fullname']) . '</td></tr>';
            echo '<tr><th>Email</th><td>' . htmlspecialchars($user['email']) . '</td></tr>';
            echo '<tr><th>Số điện thoại</th><td>' . htmlspecialchars($user['phonenum'] ?? 'Chưa cập nhật') . '</td></tr>';
            echo '<tr><th>Địa chỉ</th><td>' . htmlspecialchars($user['address'] ?? 'Chưa cập nhật') . '</td></tr>';
            echo '<tr><th>Điểm thưởng</th><td><strong class="text-info">' . htmlspecialchars($user['rewardPoint'] ?? 0) . ' điểm</strong></td></tr>';
            echo '<tr><th>Trạng thái</th><td>' . $statusBadge . '</td></tr>';
            echo '<tr><th>Ngày tham gia</th><td>' . date('d/m/Y H:i', strtotime($user['created_at'])) . '</td></tr>';
            echo '</table>';
        } else {
            echo '<div class="alert alert-warning">Không tìm thấy người dùng</div>';
        }
        exit;
    }

    public function resetPassword($userId = null) {
        if ($_SERVER['REQUEST_METHOD'] === 'POST' && $userId) {
            $newPassword = 'User@123456'; // Mật khẩu mặc định hệ thống tự phát sinh
            $newHash = password_hash($newPassword, PASSWORD_DEFAULT);
            if ($this->userAdminModel->resetPassword($userId, $newHash)) {
                $_SESSION['success'] = 'Đã reset mật khẩu thành công! Mật khẩu mới là: <strong>' . $newPassword . '</strong>';
            } else {
                $_SESSION['error'] = 'Lỗi khi reset mật khẩu.';
            }
        }
        header('Location: ' . URLROOT . '/admin/Users');
        exit;
    }

    public function toggleLock($userId = null) {
        if ($_SERVER['REQUEST_METHOD'] === 'POST' && $userId) {
            $isLocked = isset($_POST['is_locked']) ? (int)$_POST['is_locked'] : 0;
            $this->userAdminModel->toggleLock($userId, $isLocked);
        }
        header('Location: ' . URLROOT . '/admin/Users');
        exit;
    }
}