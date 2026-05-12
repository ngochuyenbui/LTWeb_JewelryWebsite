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
    
    <!-- Srtdash CSS -->
    <link rel="stylesheet" href="<?= URLROOT ?>/assets/admin/css/bootstrap.min.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/gh/lykmapipo/themify-icons@0.1.2/css/themify-icons.css">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/metismenu/dist/metisMenu.min.css">
    <link rel="stylesheet" href="<?= URLROOT ?>/assets/admin/css/owl.carousel.min.css">
    <link rel="stylesheet" href="<?= URLROOT ?>/assets/admin/css/slicknav.min.css">
    <!-- ion.rangeSlider css -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/ion-rangeslider/2.3.1/css/ion.rangeSlider.min.css"/>
    <!-- SweetAlert2 css -->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/sweetalert2@11/dist/sweetalert2.min.css">
    <!-- amchart css -->
    <link rel="stylesheet" href="https://www.amcharts.com/lib/3/plugins/export/export.css" type="text/css" media="all" />
    <!-- others css -->
    <link rel="stylesheet" href="<?= URLROOT ?>/assets/admin/css/typography.css">
    <link rel="stylesheet" href="<?= URLROOT ?>/assets/admin/css/default-css.css">
    <link rel="stylesheet" href="<?= URLROOT ?>/assets/admin/css/styles.css">
    <link rel="stylesheet" href="<?= URLROOT ?>/assets/admin/css/responsive.css">
    <!-- modernizr css -->
    <script src="<?= URLROOT ?>/assets/admin/js/vendor/modernizr-2.8.3.min.js"></script>
    
    <style>
        @media (max-width: 991px) {
            .nav-btn { z-index: 9999 !important; position: relative; }
        }
    </style>
</head>

<body>
    <!-- page container area start -->
    <div class="page-container">
        
        <?php require_once 'sidebar.php'; ?>

        <!-- main content area start -->
        <div class="main-content">
            <?php require_once 'header.php'; ?>

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
    <script src="<?= URLROOT ?>/assets/admin/js/bootstrap.bundle.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <script src="https://cdn.jsdelivr.net/npm/metismenu/dist/metisMenu.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/ion-rangeslider/2.3.1/js/ion.rangeSlider.min.js"></script>
    <script src="<?= URLROOT ?>/assets/admin/js/swiper-bundle.min.js"></script>  
    <script src="<?= URLROOT ?>/assets/admin/js/maps.js"></script>
    <script src="<?= URLROOT ?>/assets/admin/js/scripts.js"></script>

    <script>
        $(document).ready(function() {
            // Đảm bảo MetisMenu chỉ được khởi tạo 1 lần (nếu scripts.js chưa khởi tạo)
            if (!$('#menu').data('metisMenu')) {
                $('#menu').metisMenu({
                    toggle: true,
                    activeClass: 'active'
                });
            }

                const navBtn = document.querySelector('.nav-btn');
                if (navBtn) {
                    const cleanBtn = navBtn.cloneNode(true);
                    navBtn.parentNode.replaceChild(cleanBtn, navBtn);
                    cleanBtn.addEventListener('click', function(e) {
                        e.preventDefault();
                        e.stopPropagation(); 
                        document.querySelector('.page-container').classList.toggle('sbar_collapsed');
                    });
                }
        });
    </script>
</body>
</html>
