<?php
require_once __DIR__ . "/../vendor/autoload.php";
$dotenv = Dotenv\Dotenv::createImmutable(dirname(__DIR__));
$dotenv->load();
// Start session
session_start();

// Tải file cấu hình và các thư viện lõi
require_once __DIR__ . "/../core/config.php";
require_once __DIR__ . "/../core/Database.php";
require_once __DIR__ . "/../core/App.php";
require_once __DIR__ . "/../core/Controller.php";
require_once __DIR__ . "/../app/helper/Mail.php";
// Khởi tạo ứng dụng
$myApp = new App();
