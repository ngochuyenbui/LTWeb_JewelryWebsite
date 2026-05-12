<?php
class Login extends Controller {
    private $userModel;

    public function __construct() {
        parent::__construct();
        $this->userModel = $this->model('UserModel');
    }

    private function jsonResponse($data) {
        header('Content-Type: application/json');
        echo json_encode($data);
        exit();
    }

    private function isPost() {
        return $_SERVER['REQUEST_METHOD'] === 'POST';
    }

    public function index() {
        if (isset($_SESSION['user_id'])) {
            // Nếu là request AJAX, trả về JSON thay vì redirect HTML
            if ($this->isPost()) {
                $this->jsonResponse(['success' => true, 'redirect' => URLROOT . '/Home']);
            }
            header("Location: " . URLROOT . "/Home");
            exit();
        }

        if (!$this->isPost()) {
            $this->view('client/auth/Login');
            return;
        }
            
        $username = trim($_POST['username'] ?? '');
        $password = trim($_POST['password'] ?? '');
        
        if (empty($username) || empty($password)) {
            $this->jsonResponse(['error' => 'Vui lòng điền đầy đủ thông tin']);
        }
        
        $user = $this->userModel->getUserByUsername($username);
        if ($user) {
            if (is_object($user)) $user = (array)$user;
            
            if (password_verify($password, $user['pwd_hash'])) {
                if ($user['role'] === 'locked') {
                    $this->jsonResponse(['error' => 'Tài khoản của bạn đã bị khóa. Vui lòng liên hệ quản trị viên.']);
                }

                $_SESSION['user_id'] = $user['userId'];
                $_SESSION['username'] = $user['username'];
                $_SESSION['user_role'] = $user['role'];
                $_SESSION['last_activity'] = time();
                
                // Dong bo cart session -> db
                if ($user['role'] !== ROLE_ADMIN) {
                    try {
                        $cartModel = $this->model('CartModel');
                        if (!empty($_SESSION['cart']) && is_array($_SESSION['cart'])) {
                            foreach ($_SESSION['cart'] as $item) {
                                if (!is_array($item) || !isset($item['productId'])) continue;
                                
                                $existingItem = $cartModel->getCartItem($user['userId'], $item['productId'], $item['size']);
                                if ($existingItem) {
                                    $existingQty = is_object($existingItem) ? $existingItem->quantity : $existingItem['quantity'];
                                    $cartModel->updateItemQuantity($user['userId'], $item['productId'], $item['size'], $existingQty + $item['quantity']);
                                } else {
                                    $cartModel->addItem($user['userId'], $item['productId'], $item['size'], $item['quantity']);
                                }
                            }
                            unset($_SESSION['cart']);
                        }
                        $_SESSION['cart_total_items'] = $cartModel->getTotalItems($user['userId']);
                    } catch (Exception $e) {
                        error_log("Lỗi đồng bộ giỏ hàng: " . $e->getMessage());
                    }
                }

                $redirectUrl = ($user['role'] === ROLE_ADMIN) ? '/admin/Dashboard' : '/Home';
                if ($user['role'] !== ROLE_ADMIN && !empty($_SESSION['redirect_after_login'])) {
                    $redirectUrl = $_SESSION['redirect_after_login'];
                    unset($_SESSION['redirect_after_login']);
                }
                $this->jsonResponse(['success' => true, 'redirect' => URLROOT . $redirectUrl]);
            }
        }
        
        $this->jsonResponse(['error' => 'Sai tài khoản hoặc mật khẩu']);
    }

    public function logout() {
        session_unset();
        session_destroy();
        header("Location: " . URLROOT . "/Home");
        exit();
    }
}
