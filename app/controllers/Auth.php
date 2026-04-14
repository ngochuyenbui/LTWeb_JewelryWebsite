<?php
class Auth extends Controller{
    private $userModel;
    public function __construct()
    {
        parent::__construct();
        $this->userModel = $this->model('UserModel');
    }
    public function index(){
        $this->view('client/Login');
    }

    public function login()
    {
        if ($_SERVER['REQUEST_METHOD'] == 'POST') {
            $username = trim($_POST['username']);
            $password = trim($_POST['password']);
            
            if (empty($username) || empty($password)) {
                $_SESSION['error'] = 'Vui lòng điền đầy đủ thông tin';
                header('Location: ' . URLROOT . '/Auth/login');
                exit();
            }
            
            $user = $this->userModel->getUserByUsername($username);
            if (!$user) {
                $_SESSION['error'] = 'Tài khoản không tồn tại';
                header('Location: ' . URLROOT . '/Auth/login');
                exit();
            }
            if ($user && password_verify($password, $user['pwd_hash'])) {
                $_SESSION['user_id'] = $user['userId'];
                $_SESSION['username'] = $user['username'];
                $_SESSION['user_role'] = $user['role'];
                $_SESSION['last_activity'] = time();
                if ($user['role'] == ROLE_ADMIN) {
                    header('Location: ' . URLROOT . '/admin/Dashboard');
                    exit();
                } else {
                    header('Location: ' . URLROOT . '/Home');
                    exit();
                }
            }
            else{
                //$data['error'] = 'Sai tài khoản hoặc mật khẩu';
                //$this->view('client/Login', $data);
                $_SESSION['error'] = 'Sai tài khoản hoặc mật khẩu';
                header('Location: ' . URLROOT . '/Auth/login');
            }
        }
        else{
            $data = ['error' => $_SESSION['error'] ?? '',];
            unset($_SESSION['error']);
            $this->view('client/Login', $data);
        }
    }

    public function logout()
    {
        unset($_SESSION['user_id'], $_SESSION['username'], $_SESSION['user_role'], $_SESSION['last_activity']);
        session_destroy();
        header("Location: " . URLROOT . "/Home");
        exit();
    }

    public function register(){
        if ($_SERVER['REQUEST_METHOD'] == 'POST') {
            $data = [
                'username' => trim($_POST['username']),
                'email' => trim($_POST['email']),
                'password' => trim($_POST['password']),
                'confirm_password' => trim($_POST['confirm_password']),
                'error' => ''
            ];
            
            if (empty($data['username']) || empty($data['email']) || 
                empty($data['password']) || empty($data['confirm_password']) ||
                !preg_match('/^[a-zA-Z0-9_]{3,50}$/', $data['username']) ||
                !filter_var($data['email'], FILTER_VALIDATE_EMAIL) ||
                strlen($data['password']) < 6 ||
                $data['password'] !== $data['confirm_password'] ||
                $this->userModel->getUserByUsername($data['username']) ||
                $this->userModel->getUserByEmail($data['email'])) {
                    $_SESSION['input_data'] = $data;
                header('Location: ' . URLROOT . '/Auth/register');
                exit();
                }
            $_SESSION['temp_user'] = [
                'username' => $data['username'],
                'email' => $data['email'],
                'password' => password_hash($data['password'], PASSWORD_DEFAULT)
            ];
            $otp = rand(100000, 999999);
            $_SESSION['otp'] = $otp;
            $_SESSION['otp_exp'] = time() + 300;
            if(Mail::sendOTP($data['email'], $otp)){
                header('Location: ' . URLROOT . '/Auth/verify_email');
                exit();
            } else {
                $_SESSION['error'] = 'Gửi mã OTP thất bại. Vui lòng thử lại sau.';
                header('Location: ' . URLROOT . '/Auth/register');
                exit();
            }
        } else {
            $data = $_SESSION['input_data'] ?? [];
            $data['error'] = $_SESSION['error'] ?? '';
            unset($_SESSION['input_data'], $_SESSION['error']);
            $this->view('client/Register', $data);
        }
    }
    public function checkUsername() {
        if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['username'])) {
            $username = trim($_POST['username']);
            $exists = $this->userModel->getUserByUsername($username);
            header('Content-Type: application/json');
            echo json_encode(['exists' => (bool)$exists]);
            exit();
        }
    }
    public function checkEmail() {
        if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['email'])) {
            $email = trim($_POST['email']);
            $exists = $this->userModel->getUserByEmail($email);
            header('Content-Type: application/json');
            echo json_encode(['exists' => (bool)$exists]);
            exit();
        }
    }



    public function verify_email(){
        if (!isset($_SESSION['temp_user'])) {
            header("Location: " . URLROOT . "/Auth/register");
            exit();
        }
        if ($_SERVER['REQUEST_METHOD'] == 'POST') {
            $otp = trim($_POST['otp']);

            if (time() > $_SESSION['otp_exp']) {
                $_SESSION['error'] = 'Mã OTP đã hết hạn!';
                header("Location: " . URLROOT . "/Auth/verify_email");
                exit();
            } elseif ($otp != $_SESSION['otp']) {
                $_SESSION['error'] = 'Mã OTP không chính xác!';
                header("Location: " . URLROOT . "/Auth/verify_email");
                exit();
            } else {
                try{
                    if ($this->userModel->addUser($_SESSION['temp_user'])) {
                        unset($_SESSION['temp_user'], $_SESSION['otp'], $_SESSION['otp_exp']);
                        header("Location: " . URLROOT . "/Auth/login?register=success");
                        exit;
                    }
                } catch (Exception $e){
                    $_SESSION['error'] = 'Đăng ký thất bại. Vui lòng thử lại.';
                    header("Location: " . URLROOT . "/Auth/register");
                    exit();
                }
            }
        } else {
            $data['error'] = $_SESSION['error'] ?? '';
            unset($_SESSION['error']);
            $this->view("client/VerifyEmail", $data);
            //header("Location: " . URLROOT . "/Auth/register");
            //exit();
        }
    }
    public function resendOTP(){
        if (!isset($_SESSION['temp_user'])) {
            header("Location: " . URLROOT . "/Auth/register");
            exit();
        }
        if ($_SERVER['REQUEST_METHOD'] == 'POST') {
            $otp = rand(100000, 999999);
            $_SESSION['otp'] = $otp;
            $_SESSION['otp_exp'] = time() + 300;
            if(Mail::sendOTP($_SESSION['temp_user']['email'], $otp)){
                header('Location: ' . URLROOT . '/Auth/verify_email?resend=success');
                exit();
            } else {
                header('Location: ' . URLROOT . '/Auth/verify_email?resend=fail');
                exit();
            }
        }
        else{
            header("Location: " . URLROOT . "/Auth/register");
            exit();
        }
    }
}
?>