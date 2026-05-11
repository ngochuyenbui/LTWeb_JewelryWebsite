<?php
class Profile extends Controller {
    private $profileModel;
    private $orderModel;

    public function __construct() {
        parent::__construct();
        // Yêu cầu đăng nhập mới được vào trang này
        if (!isset($_SESSION['user_id']) || ($_SESSION['user_role'] ?? $_SESSION['role'] ?? 'member') !== 'member') {
            header('Location: ' . URLROOT . '/Login');
            exit;
        }
        $this->profileModel = $this->model('ProfileModel');
        $this->orderModel = $this->model('OrderModel'); // Tận dụng model order cũ để lấy danh sách
    }

    public function index() {
        $userId = $_SESSION['user_id'];
        $userProfile = $this->profileModel->getUserProfile($userId);
        
        // Phân trang cho Lịch sử đơn hàng
        $limit = 5; // 5 đơn hàng mỗi trang
        $page = isset($_GET['page']) ? max(1, (int)$_GET['page']) : 1;
        $offset = ($page - 1) * $limit;

        $totalOrders = $this->orderModel->getTotalOrdersByUserId($userId);
        $totalPages = ceil($totalOrders / $limit);

        // Lấy Lịch sử đơn hàng
        $orders = $this->orderModel->getOrdersByUserIdPaginated($userId, $limit, $offset);
        foreach ($orders as $key => $order) {
            $orderId = is_object($order) ? $order->orderId : $order['orderId'];
            $items = $this->orderModel->getOrderItems($orderId);
            if (is_object($orders[$key])) {
                $orders[$key]->items = $items;
            } else {
                $orders[$key]['items'] = $items;
            }
        }

        $this->view('client/profile/index', [
            'user' => $userProfile,
            'orders' => $orders,
            'currentPage' => $page,
            'totalPages' => $totalPages
        ]);
    }

    public function update() {
        if ($_SERVER['REQUEST_METHOD'] == 'POST') {
            $userId = $_SESSION['user_id'];
            $fullname = trim($_POST['fullname'] ?? '');
            $phone = trim($_POST['phone'] ?? '');
            $address = trim($_POST['address'] ?? '');

            try {
                $this->profileModel->updateProfile($userId, $fullname, $phone, $address);
                $_SESSION['success'] = 'Cập nhật thông tin thành công!';
            } catch (Exception $e) {
                $_SESSION['error'] = 'Lỗi cập nhật: ' . $e->getMessage();
            }
            header('Location: ' . URLROOT . '/client/Profile?tab=profile');
            exit;
        }
    }

    public function updatePassword() {
        if ($_SERVER['REQUEST_METHOD'] == 'POST') {
            $userId = $_SESSION['user_id'];
            $currentPassword = $_POST['current_password'] ?? '';
            $newPassword = $_POST['new_password'] ?? '';
            $confirmPassword = $_POST['confirm_password'] ?? '';

            $currentHash = $this->profileModel->getPasswordHash($userId);

            if (!password_verify($currentPassword, $currentHash)) {
                $_SESSION['error'] = 'Mật khẩu hiện tại không đúng!';
            } elseif ($newPassword !== $confirmPassword) {
                $_SESSION['error'] = 'Mật khẩu mới không khớp!';
            } elseif (strlen($newPassword) < 6) {
                $_SESSION['error'] = 'Mật khẩu mới phải có ít nhất 6 ký tự!';
            } else {
                $newHash = password_hash($newPassword, PASSWORD_DEFAULT);
                $this->profileModel->updatePassword($userId, $newHash);
                $_SESSION['success'] = 'Đổi mật khẩu thành công!';
            }
            header('Location: ' . URLROOT . '/client/Profile?tab=password');
            exit;
        }
    }

    public function updateAvatar() {
        if ($_SERVER['REQUEST_METHOD'] == 'POST' && !empty($_FILES['avatar']['name'])) {
            $userId = $_SESSION['user_id'];
            $file = $_FILES['avatar'];
            $uploadDir = '../public/assets/uploads/avatars/';
            
            if (!is_dir($uploadDir)) @mkdir($uploadDir, 0777, true);

            if ($file['error'] === UPLOAD_ERR_OK) {
                $ext = strtolower(pathinfo($file['name'], PATHINFO_EXTENSION));
                if (in_array($ext, ['jpg', 'jpeg', 'png', 'gif', 'webp'])) {
                    $newName = 'avatar_' . $userId . '_' . time() . '.' . $ext;
                    if (move_uploaded_file($file['tmp_name'], $uploadDir . $newName)) {
                        $avatarUrl = '/assets/uploads/avatars/' . $newName;
                        $this->profileModel->updateAvatar($userId, $avatarUrl);
                        $_SESSION['success'] = 'Cập nhật ảnh đại diện thành công!';
                    } else $_SESSION['error'] = 'Lỗi khi tải ảnh lên.';
                } else $_SESSION['error'] = 'Định dạng ảnh không hợp lệ.';
            } else $_SESSION['error'] = 'Có lỗi xảy ra trong quá trình tải lên.';
        }
        header('Location: ' . URLROOT . '/client/Profile?tab=profile');
        exit;
    }

    public function cancelOrder($orderId = null) {
        if (!$orderId || $_SERVER['REQUEST_METHOD'] !== 'POST') {
            header('Location: ' . URLROOT . '/client/Profile?tab=orders');
            exit;
        }

        $userId = $_SESSION['user_id'];
        // Lấy các sản phẩm trong đơn hàng TRƯỚC KHI HỦY
        $orderItems = $this->orderModel->getOrderItems($orderId);

        $affectedRows = $this->orderModel->cancelOrderAsUser($orderId, $userId);
        
        if ($affectedRows > 0) {
            // Hoàn lại số lượng vào kho
            $productModel = $this->model('ProductModel');
            foreach ($orderItems as $item) {
                $pId = is_object($item) ? $item->productId : $item['productId'];
                $qty = is_object($item) ? $item->quantity : $item['quantity'];
                $productModel->increaseStock($pId, $qty);
            }
            $_SESSION['success'] = 'Hủy đơn hàng thành công!';
        } else {
            $_SESSION['error'] = 'Không thể hủy đơn hàng. Đơn hàng có thể đã được xử lý hoặc không tồn tại.';
        }

        header('Location: ' . URLROOT . '/client/Profile?tab=orders');
        exit;
    }
}