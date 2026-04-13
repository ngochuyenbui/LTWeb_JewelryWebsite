<?php
class Auth extends Controller{
    private $userModel;
    public function __construct()
    {
        parent::__construct();
        $this->userModel = $this->model('UserModel');
    }

    public function login()
    {
        if ($_SERVER['REQUEST_METHOD'] == 'POST') {
            $username = trim($_POST['username']);
            $password = trim($_POST['password']);
            
            if (empty($username) || empty($password)) {
                $this->view('client/Login', ['error' => 'Vui lòng điền đầy đủ thông tin']);
                return;
            }
            
            $user = $this->userModel->getUserByUsername($username);
            if ($user && password_verify($password, $user['password'])) {
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
                $this->view('client/Login', ['error' => 'Sai tài khoản hoặc mật khẩu']);
            }
        }
        else{
            $this->view('client/Login');
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
            
            if (empty($data['username']) || empty($data['email']) || empty($data['password']) || empty($data['confirm_password'])) {
                $data['error'] = 'Vui lòng điền đầy đủ thông tin';
                $this->view('client/Register', $data);
                return;
            }
            if (strlen($data['username']) < 3 || strlen($data['username']) > 50) {
                $data['error'] = 'Tên đăng nhập phải từ 3-50 ký tự';
                $this->view('client/Register', $data);
                return;
            }
            if (!filter_var($data['email'], FILTER_VALIDATE_EMAIL)) {
                $data['error'] = 'Email không hợp lệ';
                $this->view('client/Register', $data);
                return;
            }
            if ($data['password'] !== $data['confirm_password']) {
                $data['error'] = 'Mật khẩu xác nhận không khớp';
                $this->view('client/Register', $data);
                return;
            }
            if (strlen($data['password']) < 6) {
                $data['error'] = 'Mật khẩu phải có ít nhất 6 ký tự';
                $this->view('client/Register', $data);
                return;
            }
            if ($this->userModel->getUserByUsername($data['username'])) {
                $data['error'] = 'Tên đăng nhập đã tồn tại';
                $this->view('client/Register', $data);
                return;
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
                $data['error'] = 'Gửi mã OTP thất bại. Vui lòng thử lại sau.';
                $this->view('client/Register', $data);
            }
            
            // $this->userModel->addUser([
            //     'username' => $username,
            //     'email' => $email,
            //     'password' => password_hash($password, PASSWORD_DEFAULT)
            // ]);
        } else {
            $this->view('client/Register');
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
                $this->view("client/VerifyEmail", ['error' =>'Mã OTP đã hết hạn!']);
            } elseif ($otp != $_SESSION['otp']) {
                $this->view("client/VerifyEmail", ['error' =>'Mã OTP không chính xác!']);
            } else {
                if ($this->userModel->addUser($_SESSION['temp_user'])) {
                    unset($_SESSION['temp_user'], $_SESSION['otp'], $_SESSION['otp_exp']);
                    header("Location: " . URLROOT . "/Auth/login?register=success");
                    exit;
                }
            }
        } else {
            header("Location: " . URLROOT . "/Auth/register");
            exit();
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