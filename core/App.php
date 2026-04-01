<?php
class App {
    protected $controller = "Home"; // Controller mặc định
    protected $action = "index";
    protected $params = [];

    public function __construct() {
        $url = $this->urlProcess();

        // Kiểm tra Controller tồn tại trong app/controllers/ [cite: 100]
        if (isset($url[0]) && file_exists("../app/controllers/" . ucfirst($url[0]) . ".php")) {
            $this->controller = ucfirst($url[0]);
            unset($url[0]);
        }
        require_once "../app/controllers/" . $this->controller . ".php";
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