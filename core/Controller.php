<?php
// core/Controller.php
class Controller {
    // Hàm gọi Model
    public function model($model) {
        require_once "../app/models/" . $model . ".php";
        return new $model;
    }

    // Hàm gọi View và truyền dữ liệu
    public function view($view, $data = []) {
        $viewFile = "../app/views/client/" . $view . ".php";
        if (!file_exists($viewFile)) {
            die("View does not exist: " . htmlspecialchars($view, ENT_QUOTES, 'UTF-8'));
        }

        // Cho phép view truy cập dữ liệu bằng biến trực tiếp, ví dụ: $title, $products...
        if (!empty($data) && is_array($data)) {
            extract($data, EXTR_SKIP);
        }

        $contentView = $viewFile;
        require_once "../app/views/client/layout/layout.php";
    }
}