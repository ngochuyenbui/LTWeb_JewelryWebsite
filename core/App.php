<?php
class App {
    protected $controller = "Home"; // Controller mặc định
    protected $action = "index";
    protected $params = [];

    public function __construct() {
        $url = $this->urlProcess();
        $controllerDir = "../app/controllers/";

        // Kiểm tra nếu URL đang truy cập vào thư mục admin
        if (isset($url[0]) && strtolower($url[0]) === 'admin') {
            $controllerDir .= 'admin/';
            $this->controller = 'Dashboard'; // Controller mặc định của khu vực admin
            unset($url[0]);
            $url = array_values($url);
        }
        // Kiểm tra nếu URL đang truy cập vào thư mục client
        elseif (isset($url[0]) && strtolower($url[0]) === 'client') {
            $controllerDir .= 'client/';
            $this->controller = 'Products'; // Controller mặc định của khu vực client
            unset($url[0]);
            $url = array_values($url);
        }

        // Kiểm tra Controller tồn tại
        if (isset($url[0]) && file_exists($controllerDir . ucfirst($url[0]) . ".php")) {
            $this->controller = ucfirst($url[0]);
            unset($url[0]);
        }
        require_once $controllerDir . $this->controller . ".php";
        $this->controller = new $this->controller;

        // Kiểm tra Action (hàm) trong Controller
        if (isset($url[1]) && method_exists($this->controller, $url[1])) {
            $this->action = $url[1];
            unset($url[1]);
        }

        $this->params = $url ? array_values($url) : [];
        call_user_func_array([$this->controller, $this->action], $this->params);
    }

    private function urlProcess() {
        if (isset($_GET["url"])) {
            return explode("/", filter_var(trim($_GET["url"], "/"), FILTER_SANITIZE_URL));
        }
    }
}