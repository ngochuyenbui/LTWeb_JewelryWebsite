<?php
class Register extends Controller {
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
        if (!$this->isPost()) {
            $data = $_SESSION['input_data'] ?? [];
            $data['error'] = $_SESSION['error'] ?? '';
            unset($_SESSION['input_data'], $_SESSION['error']);
            $this->view('client/auth/Register', $data);
            return;
        }
        
        $data = [
            'username' => trim($_POST['username'] ?? ''),
            'email' => trim($_POST['email'] ?? ''),
            'password' => trim($_POST['password'] ?? ''),
            'confirm_password' => trim($_POST['confirm_password'] ?? ''),
            'error' => ''
        ];
        
        $isInvalid = empty($data['username']) || empty($data['email']) || 
                     empty($data['password']) || empty($data['confirm_password']) ||
                     !preg_match('/^[a-zA-Z0-9_]{3,50}$/', $data['username']) ||
                     !filter_var($data['email'], FILTER_VALIDATE_EMAIL) ||
                     strlen($data['password']) < 6 ||
                     $data['password'] !== $data['confirm_password'] ||
                     $this->userModel->getUserByUsername($data['username']) ||
                     $this->userModel->getUserByEmail($data['email']);

        if ($isInvalid) {
            $this->jsonResponse(['error' => 'Thông tin đăng ký không hợp lệ!']);
        }

        $_SESSION['temp_user'] = [
            'username' => $data['username'],
            'email' => $data['email'],
            'password' => password_hash($data['password'], PASSWORD_DEFAULT)
        ];
        $otp = rand(100000, 999999);
        $_SESSION['otp'] = $otp;
        $_SESSION['otp_exp'] = time() + 300;

        if(Mail::sendOTP($data['email'], $otp)) {
            $this->jsonResponse(['redirect' => URLROOT . '/Register/verify_email']);
        } else {
            $this->jsonResponse(['error' => 'Đăng ký thất bại. Vui lòng thử lại sau.']);
        }
    }

    public function checkUsername() {
        if ($this->isPost() && isset($_POST['username'])) {
            $username = trim($_POST['username']);
            $exists = $this->userModel->getUserByUsername($username);
            $this->jsonResponse(['exists' => (bool)$exists]);
        }
    }

    public function checkEmail() {
        if ($this->isPost() && isset($_POST['email'])) {
            $email = trim($_POST['email']);
            $exists = $this->userModel->getUserByEmail($email);
            $this->jsonResponse(['exists' => (bool)$exists]);
        }
    }

    public function verify_email() {
        if (!$this->isPost()) {
            if (!isset($_SESSION['temp_user']) || !isset($_SESSION['otp'])) {
                header("Location: " . URLROOT . "/Register");
                exit();
            }
            $this->view("client/auth/VerifyEmail");
            return;
        }

        if (!isset($_SESSION['attempts'])) {
            $_SESSION['attempts'] = 0;
        }

        if ($_SESSION['attempts'] >= 5) {
            unset($_SESSION['temp_user'], $_SESSION['otp'], $_SESSION['otp_exp'], $_SESSION['attempts']);
            $this->jsonResponse([
                'error' => 'Bạn đã nhập sai quá 5 lần. Vui lòng đăng ký lại.',
                'redirect' => URLROOT . '/Register'
            ]);
        }

        $_SESSION['attempts']++;
        $otp = trim($_POST['otp'] ?? '');

        if (empty($otp) || !preg_match('/^\d{6}$/', $otp)) {
            $this->jsonResponse(['error'=> 'Mã OTP không hợp lệ!']);
        }

        if (time() > $_SESSION['otp_exp']) {
            $this->jsonResponse(['error' => 'Mã OTP đã hết hạn!']);
        } elseif ($otp != $_SESSION['otp']) {
            $this->jsonResponse(['error' => 'Mã OTP không chính xác!']);
        } 

        try {
            if ($this->userModel->addUser($_SESSION['temp_user'])) {
                unset($_SESSION['temp_user'], $_SESSION['otp'], $_SESSION['otp_exp'], $_SESSION['attempts']);
                $this->jsonResponse(['success' => true, 'redirect' => URLROOT . '/Login?register=success']);
            }
        } catch (Exception $e) {
            $this->jsonResponse([
                'error' => 'Đăng ký thất bại. Vui lòng thử lại.',
                'redirect' => URLROOT . '/Register'
            ]);
        }
    }

    public function resendOTP() {
        if (!isset($_SESSION['temp_user']) || !$this->isPost()) {
            header("Location: " . URLROOT . "/Register");
            exit();
        }

        if (isset($_SESSION['last_sent'])){
            $timePass = time() - $_SESSION['last_sent'];
            if ($timePass < 60){
                $remain = 60 - $timePass;
                $this->jsonResponse(['error' => "Gửi lại sau {$remain}s"]);
            }
        }

        $_SESSION['last_sent'] = time();
        unset($_SESSION['attempts']);
        $otp = rand(100000, 999999);
        $_SESSION['otp'] = $otp;
        $_SESSION['otp_exp'] = time() + 300;

        if(Mail::sendOTP($_SESSION['temp_user']['email'], $otp)){
            $this->jsonResponse(['success' => true]);
        } else {
            $this->jsonResponse(['error' => 'Không thể gửi mã OTP. Vui lòng thử lại.']);
        }
    }
}