<?php
require_once "../vendor/autoload.php";
$dotenv = Dotenv\Dotenv::createImmutable(dirname(__DIR__));
$dotenv->load();
// Start session
session_start();

// Tải file cấu hình và các thư viện lõi
require_once "../core/config.php";
require_once "../core/Database.php";
require_once "../core/App.php";
require_once "../core/Controller.php";
require_once "../app/helper/Mail.php";
// Khởi tạo ứng dụng
$myApp = new App();