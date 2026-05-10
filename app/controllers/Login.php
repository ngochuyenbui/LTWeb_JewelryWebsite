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
        if ($user && password_verify($password, $user['pwd_hash'])) {
            $_SESSION['user_id'] = $user['userId'];
            $_SESSION['username'] = $user['username'];
            $_SESSION['user_role'] = $user['role'];
            $_SESSION['last_activity'] = time();
            
            $redirectUrl = ($user['role'] == ROLE_ADMIN) ? '/admin/Dashboard' : '/Home';
            $this->jsonResponse(['success' => true, 'redirect' => URLROOT . $redirectUrl]);
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