<?php
// app/views/admin/layout/layout.php

if (!isset($contentView) || !file_exists($contentView)) {
	die('Không tìm thấy nội dung trang để hiển thị.');
}
?>
<!DOCTYPE html>
<html class="no-js" lang="vi">

<head>
    <meta charset="utf-8">
    <meta http-equiv="x-ua-compatible" content="ie=edge">
    <title><?= isset($title) ? htmlspecialchars($title) : 'Quản trị' ?> - Dashboard</title>
    <meta name="viewport" content="width=device-width, initial-scale=1">
    
    <!-- Các file CSS của Srtdash template (Bạn cần tải thư mục Srtdash vào public/assets/admin) -->
    <!-- Tạm thời dùng Bootstrap CDN để hiển thị UI nếu chưa có file local -->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@4.6.2/dist/css/bootstrap.min.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.4/css/all.min.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/themify-icons/0.1.2/css/themify-icons.css">
    
    <style>
        .page-container { display: flex; }
        .sidebar-menu { width: 250px; background: #303641; min-height: 100vh; color: #fff; }
        .main-content { flex: 1; background: #f3f8fb; }
        .header-area { background: #fff; padding: 20px; box-shadow: 0 2px 5px rgba(0,0,0,0.1); }
        .page-title-area { padding: 20px; background: #fff; border-bottom: 1px solid #ddd; }
        .main-content-inner { padding: 20px; }
        .sidebar-menu a { color: #a4a9b3; padding: 15px; display: block; border-bottom: 1px solid #3c4452; text-decoration: none; }
        .sidebar-menu a:hover { color: #fff; background: #2b303a; }
        .sidebar-menu .active { color: #fff; background: #4caf50; }
    </style>
</head>

<body>
    <!-- page container area start -->
    <div class="page-container">
        
        <!-- sidebar menu area start -->
        <div class="sidebar-menu">
            <div class="sidebar-header" style="padding: 20px; text-align: center; font-size: 24px; font-weight: bold;">
                Admin Panel
            </div>
            <div class="main-menu">
                <div class="menu-inner">
                    <nav>
                        <ul class="list-unstyled">
                            <li><a href="<?= URLROOT ?>/AdminArticle"><i class="ti-receipt"></i> Quản lý Bài viết</a></li>
                            <li><a href="<?= URLROOT ?>/AdminComment"><i class="ti-comments"></i> Quản lý Bình luận</a></li>
                            <li><a href="<?= URLROOT ?>/AdminAbout"><i class="ti-layout-grid2"></i> Quản lý About</a></li>
                            <li><a href="<?= URLROOT ?>/AdminFaq"><i class="ti-help-alt"></i> Quản lý FAQ</a></li>
                            <li><a href="<?= URLROOT ?>/Home"><i class="ti-home"></i> Xem Website</a></li>
                        </ul>
                    </nav>
                </div>
            </div>
        </div>
        <!-- sidebar menu area end -->

        <!-- main content area start -->
        <div class="main-content">
            <!-- header area start -->
            <div class="header-area">
                <div class="row align-items-center">
                    <div class="col-md-6 col-sm-8 clearfix">
                        <div class="nav-btn pull-left" style="margin-top: 5px;">
                            <span><i class="ti-menu"></i></span>
                        </div>
                    </div>
                    <div class="col-md-6 col-sm-4 clearfix text-right">
                        <span>Xin chào, <?= htmlspecialchars($_SESSION['username'] ?? 'Admin') ?></span>
                    </div>
                </div>
            </div>
            <!-- header area end -->

            <!-- page title area start -->
            <div class="page-title-area">
                <div class="row align-items-center">
                    <div class="col-sm-6">
                        <div class="breadcrumbs-area clearfix">
                            <h4 class="page-title pull-left"><?= isset($title) ? htmlspecialchars($title) : 'Dashboard' ?></h4>
                        </div>
                    </div>
                </div>
            </div>
            <!-- page title area end -->

            <div class="main-content-inner">
                <!-- Nội dung các trang View được chèn vào đây -->
                <?php require $contentView; ?>
            </div>
        </div>
        <!-- main content area end -->

    </div>
    <!-- page container area end -->

    <!-- JS -->
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@4.6.2/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
