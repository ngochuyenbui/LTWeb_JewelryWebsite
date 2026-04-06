<?php
// Start session
session_start();

// Tải file cấu hình và các thư viện lõi
require_once "../core/config.php";
require_once "../core/Database.php";
require_once "../core/App.php";
require_once "../core/Controller.php";
// Khởi tạo ứng dụng
$myApp = new App();