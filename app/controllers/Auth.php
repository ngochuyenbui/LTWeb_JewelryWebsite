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
                header('Content-Type: application/json');
                echo json_encode(['error' => 'Vui lòng điền đầy đủ thông tin']);
                exit();
            }
            
            $user = $this->userModel->getUserByUsername($username);
            if ($user && password_verify($password, $user['pwd_hash'])) {
                $_SESSION['user_id'] = $user['userId'];
                $_SESSION['username'] = $user['username'];
                $_SESSION['user_role'] = $user['role'];
                $_SESSION['last_activity'] = time();
                if ($user['role'] == ROLE_ADMIN) {
                    header('Content-Type: application/json');
                    echo json_encode(['success' => true, 'redirect' => URLROOT . '/admin/Dashboard']);
                    exit();
                } else {
                    header('Content-Type: application/json');
                    echo json_encode(['success' => true, 'redirect' => URLROOT . '/Home']);
                    exit();
                }
            }
            else{
                header('Content-Type: application/json');
                echo json_encode(['error' => 'Sai tài khoản hoặc mật khẩu']);
                exit();
            }
        }
        else{
            //$data = ['error' => $_SESSION['error'] ?? '',];
            //unset($_SESSION['error']);
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
                $_SESSION['error'] = 'Đăng ký thất bại. Vui lòng thử lại sau.';
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
        if ($_SERVER['REQUEST_METHOD'] == 'GET') {
            if (!isset($_SESSION['temp_user']) || !isset($_SESSION['otp'])) {
                header("Location: " . URLROOT . "/Auth/register");
                exit();
            }
            else{
                $this->view("client/VerifyEmail");
            }
        }
        if (!isset($_SESSION['attempts'])) {
            $_SESSION['attempts'] = 0;
        }
        if ($_SESSION['attempts'] >= 5) {
            unset($_SESSION['temp_user'], $_SESSION['otp'], $_SESSION['otp_exp'], $_SESSION['attempts']);
            header('Content-Type: application/json');
            echo json_encode(['error' => 'Bạn đã nhập sai quá 5 lần. Vui lòng đăng ký lại.',
            'redirect' => URLROOT . '/Auth/register']);
            exit();
        }
        if ($_SERVER['REQUEST_METHOD'] == 'POST') {
            $_SESSION['attempts']++;
            $otp = trim($_POST['otp']);
            if (empty($otp) || !preg_match('/^\d{6}$/', $otp)) {
                header('Content-Type: application/json');
                echo json_encode(['error'=> 'Mã OTP không hợp lệ!']);
                exit();
            }

            if (time() > $_SESSION['otp_exp']) {
                $error = 'Mã OTP đã hết hạn!';
            } elseif ($otp != $_SESSION['otp']) {
                $error = 'Mã OTP không chính xác!';
            } 
            if (!empty($error)) {
                header('Content-Type: application/json');
                echo json_encode(['error' => $error]);
                exit();
            }
            try{
                if ($this->userModel->addUser($_SESSION['temp_user'])) {
                    unset($_SESSION['temp_user'], $_SESSION['otp'], $_SESSION['otp_exp']);
                    header('Content-Type: application/json');
                    echo json_encode(['success' => true, 'redirect' => URLROOT . '/Auth/login?register=success']);
                    exit;
                }
            } catch (Exception $e){
                header('Content-Type: application/json');
                echo json_encode(['error' => 'Đăng ký thất bại. Vui lòng thử lại.',
                'redirect' => URLROOT . '/Auth/register']);
                exit();
            }
        }
            
        //} else {
            //$data['error'] = $_SESSION['error'] ?? '';
            //unset($_SESSION['error']);
            //$this->view("client/VerifyEmail");
            //header("Location: " . URLROOT . "/Auth/register");
            //exit();
        //}
    }
    public function resendOTP(){
        if (!isset($_SESSION['temp_user'])) {
            header("Location: " . URLROOT . "/Auth/register");
            exit();
        }
        if (isset($_SESSION['last_sent'])){
            $timePass = time() - $_SESSION['last_sent'];
            if ($timePass < 60){
                $remain = 60 - $timePass;
                header('Content-Type: application/json');
                echo json_encode(['error' => "Gửi lại sau {$remain}s"]);
                exit();
            }
        }
        if ($_SERVER['REQUEST_METHOD'] == 'POST') {
            $_SESSION['last_otp_sent'] =time();
            unset($_SESSION['attempts']);
            $otp = rand(100000, 999999);
            $_SESSION['otp'] = $otp;
            $_SESSION['otp_exp'] = time() + 300;
            if(Mail::sendOTP($_SESSION['temp_user']['email'], $otp)){
                //header('Location: ' . URLROOT . '/Auth/verify_email?resend=success');
                header('Content-Type: application/json');
                echo json_encode(['success' => true]);
                //exit();
            } else {
                //header('Location: ' . URLROOT . '/Auth/verify_email?resend=fail');
                header('Content-Type: application/json');
                echo json_encode(['error' => 'Không thể gửi mã OTP. Vui lòng thử lại.']);
                //exit();
            }
        }
        else{
            header("Location: " . URLROOT . "/Auth/register");
            exit();
        }
    }
}
?>