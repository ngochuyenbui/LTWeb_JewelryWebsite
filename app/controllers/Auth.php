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
                // $data['error'] = 'Vui lòng điền đầy đủ thông tin';
                // $this->view('client/Login', $data);
                // return;
                $_SESSION['error'] = 'Vui lòng điền đầy đủ thông tin';
                header('Location: ' . URLROOT . '/Auth/login');
                exit();
            }
            
            $user = $this->userModel->getUserByUsername($username);
            if (!$user) {
                $_SESSION['error'] = 'Tài khoản không tồn tại';
                header('Location: ' . URLROOT . '/Auth/login');
                exit();
                //$this->view('client/Login', []);
                //return;
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
            
            if (empty($data['username']) || empty($data['email']) || empty($data['password']) || empty($data['confirm_password'])) {
                //$data['error'] = 'Vui lòng điền đầy đủ thông tin';
                //$this->view('client/Register', $data);
                //return;
                $_SESSION['error'] = 'Vui lòng điền đầy đủ thông tin';
                header('Location: ' . URLROOT . '/Auth/register');
                exit();
            }
            if (strlen($data['username']) < 3 || strlen($data['username']) > 50) {
                $_SESSION['error'] = 'Tên đăng nhập phải từ 3-50 ký tự';
                header('Location: ' . URLROOT . '/Auth/register');
                exit();
                // $data['error'] = 'Tên đăng nhập phải từ 3-50 ký tự';
                // $this->view('client/Register', $data);
                // return;
            }
            if (!filter_var($data['email'], FILTER_VALIDATE_EMAIL)) {
                $_SESSION['error'] = 'Email không hợp lệ';
                header('Location: ' . URLROOT . '/Auth/register');
                exit();
                // $data['error'] = 'Email không hợp lệ';
                // $this->view('client/Register', $data);
                // return;
            }
            if ($data['password'] !== $data['confirm_password']) {
                $_SESSION['error'] = 'Mật khẩu xác nhận không khớp';
                header('Location: ' . URLROOT . '/Auth/register');
                exit();
                // $data['error'] = 'Mật khẩu xác nhận không khớp';
                // $this->view('client/Register', $data);
                // return;
            }
            if (strlen($data['password']) < 6) {
                $_SESSION['error'] = 'Mật khẩu phải có ít nhất 6 ký tự';
                header('Location: ' . URLROOT . '/Auth/register');
                exit();
                // $data['error'] = 'Mật khẩu phải có ít nhất 6 ký tự';
                // $this->view('client/Register', $data);
                // return;
            }
            if ($this->userModel->getUserByUsername($data['username'])) {
                $_SESSION['error'] = 'Tên đăng nhập đã tồn tại';
                header('Location: ' . URLROOT . '/Auth/register');
                exit();
                // $data['error'] = 'Tên đăng nhập đã tồn tại';
                // $this->view('client/Register', $data);
                // return;
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
            
            // $this->userModel->addUser([
            //     'username' => $username,
            //     'email' => $email,
            //     'password' => password_hash($password, PASSWORD_DEFAULT)
            // ]);
        } else {
            $data['error'] = $_SESSION['error'] ?? '';
            unset($_SESSION['error']);
            $this->view('client/Register', $data);
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
                //$this->view("client/VerifyEmail", ['error' =>'Mã OTP đã hết hạn!']);
            } elseif ($otp != $_SESSION['otp']) {
                //$this->view("client/VerifyEmail", ['error' =>'Mã OTP không chính xác!']);
                $_SESSION['error'] = 'Mã OTP không chính xác!';
                header("Location: " . URLROOT . "/Auth/verify_email");
                exit();
            } else {
                if ($this->userModel->addUser($_SESSION['temp_user'])) {
                    unset($_SESSION['temp_user'], $_SESSION['otp'], $_SESSION['otp_exp']);
                    header("Location: " . URLROOT . "/Auth/login?register=success");
                    exit;
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