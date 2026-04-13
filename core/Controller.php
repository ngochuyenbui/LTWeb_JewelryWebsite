<?php
// core/Controller.php
class Controller {
    public function __construct() {
        $this->checkSessionTimeout();
    }

    // Hàm gọi Model
    public function model($model) {
        require_once "../app/models/" . $model . ".php";
        return new $model;
    }

    // Hàm gọi View và truyền dữ liệu
    public function view($view, $data = []) {
        $viewFile = "../app/views/" . $view . ".php";
        if (!file_exists($viewFile)) {
            die("View does not exist: " . htmlspecialchars($view, ENT_QUOTES, 'UTF-8'));
        }

        // Cho phép view truy cập dữ liệu bằng biến trực tiếp, ví dụ: $title, $products...
        if (!empty($data) && is_array($data)) {
            extract($data, EXTR_SKIP);
        }

        $layoutFile = "../app/views/client/layout/layout.php";
        if (strpos($view, 'admin/') === 0) {
            $layoutFile = "../app/views/admin/layout/layout.php";
        }
        $contentView = $viewFile;
        if (file_exists($layoutFile)) {
            require_once $layoutFile;
        } else {
            require_once $viewFile;
        }
        //require_once "../app/views/client/layout/layout.php";
        
    }

    public function checkSessionTimeout(){
        if (isset($_SESSION['user_id'])) {
            $currentTime = time();
            if (isset($_SESSION['last_activity'])) {
                $duration = $currentTime - $_SESSION['last_activity'];
                if ($duration > SESSION_TIMEOUT) {
                    session_unset();    
                    session_destroy();
                    header("Location: " . URLROOT . "/Home");
                    exit();
                }
            }
            $_SESSION['last_activity'] = $currentTime;
        }
    }

    protected function requireLogin(){
        if (!isset($_SESSION['user_id'])) {
            header("Location: " . URLROOT . "/Auth/login");
            exit();
        }
    }

    protected function requireAdmin(){
        if (!isset($_SESSION['user_id']) || $_SESSION['user_role'] != ROLE_ADMIN) {
            header("Location: " . URLROOT . "/Home");
            exit();
        }
    }
}