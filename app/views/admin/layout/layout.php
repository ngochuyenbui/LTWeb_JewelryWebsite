<?php
if (!isset($contentView) || !file_exists($contentView)) {
    die('Không tìm thấy nội dung trang để hiển thị.');
}

// Thuật toán lấy đường dẫn tuyệt đối (Giống Client Layout) để tránh lỗi 404
$scriptName = str_replace('\\', '/', $_SERVER['SCRIPT_NAME'] ?? '');
$publicBaseUrl = rtrim(dirname($scriptName), '/');
$publicBaseUrl = $publicBaseUrl === '' ? '/' : $publicBaseUrl;
$appBaseUrl = preg_replace('#/public$#', '', $publicBaseUrl);
$appBaseUrl = $appBaseUrl === '' ? '/' : $appBaseUrl;
$assetBaseUrl = $appBaseUrl === '/' ? '' : $appBaseUrl;
?>
<!doctype html>
<html class="no-js" lang="vi">
<head>
    <meta charset="utf-8">
    <meta http-equiv="x-ua-compatible" content="ie=edge">
    <title>Quản trị - Aurelia</title>
    <meta name="viewport" content="width=device-width, initial-scale=1">
    
    <!-- 1. Sử dụng CDN cho các thư viện Vendor (Đảm bảo 100% không bị lỗi Icon) -->
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.3.1/css/bootstrap.min.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/gh/lykmapipo/themify-icons@0.1.2/css/themify-icons.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/metisMenu/2.7.0/metisMenu.min.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/OwlCarousel2/2.2.1/assets/owl.carousel.min.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/SlickNav/1.0.10/slicknav.min.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/ion-rangeslider/2.3.1/css/ion.rangeSlider.min.css"/>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/sweetalert2@11/dist/sweetalert2.min.css">
    
    <!-- 2. CSS Core của Srtdash (Dùng đường dẫn tuyệt đối động giống Client) -->
    <link rel="stylesheet" href="<?= htmlspecialchars($assetBaseUrl) ?>/assets/admin/css/typography.css">
    <link rel="stylesheet" href="<?= htmlspecialchars($assetBaseUrl) ?>/assets/admin/css/default-css.css">
    <link rel="stylesheet" href="<?= htmlspecialchars($assetBaseUrl) ?>/assets/admin/css/styles.css">
    <link rel="stylesheet" href="<?= htmlspecialchars($assetBaseUrl) ?>/assets/admin/css/responsive.css">
    
    <script src="https://cdnjs.cloudflare.com/ajax/libs/modernizr/2.8.3/modernizr.min.js"></script>
</head>
<body>
    
    <div class="page-container">
        <!-- Sidebar -->
        <?php require __DIR__ . '/sidebar.php'; ?>
        
        <div class="main-content">
            <!-- Header -->
            <?php require __DIR__ . '/header.php'; ?>
            
            <!-- Nội dung trang (Views) -->
            <?php require $contentView; ?>
        </div>
        
        <footer>
            <div class="footer-area"><p>© Copyright 2024. All right reserved.</p></div>
        </footer>
    </div>
    
    <!-- 3. CDN cho Jquery và các thư viện JS của Srtdash -->
    <script src="https://code.jquery.com/jquery-2.2.4.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.14.3/umd/popper.min.js"></script>
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.3.1/js/bootstrap.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/metisMenu/2.7.0/metisMenu.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jQuery-slimScroll/1.3.8/jquery.slimscroll.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/ion-rangeslider/2.3.1/js/ion.rangeSlider.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    
    <!-- Các file JS tự code của Template -->
    <script src="<?= htmlspecialchars($assetBaseUrl) ?>/assets/admin/js/plugins.js"></script>
    <script src="<?= htmlspecialchars($assetBaseUrl) ?>/assets/admin/js/scripts.js"></script>

    <!-- Fallback an toàn: Tự động chạy Menu nếu scripts.js của bạn bị lỗi hoặc thiếu -->
    <script>
    $(document).ready(function() {
        if ($.fn.metisMenu) {
            $('#menu').metisMenu();
        }
        $('.nav-btn').on('click', function() {
            $('.page-container').toggleClass('sbar_collapsed');
        });
    });
    </script>
</body>
</html>