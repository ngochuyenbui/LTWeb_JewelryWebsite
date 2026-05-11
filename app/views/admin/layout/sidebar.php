<?php
$uri = $_SERVER['REQUEST_URI'];
$isDashboard = strpos($uri, '/admin/Dashboard') !== false;
$isProducts = strpos($uri, '/admin/Products') !== false;
$isCategories = strpos($uri, '/admin/Categories') !== false;
$isAdmins = strpos($uri, '/admin/Admins') !== false;
$isOrders = strpos($uri, '/admin/Orders') !== false;
$isCarts = strpos($uri, '/admin/Carts') !== false;
$isUsers = strpos($uri, '/admin/Users') !== false;
$isAdminArticle = strpos($uri, '/AdminArticle') !== false;
$isAdminComment = strpos($uri, '/AdminComment') !== false;
$isAdminAbout = strpos($uri, '/AdminAbout') !== false;
$isAdminFaq = strpos($uri, '/AdminFaq') !== false;
?>
<div class="sidebar-menu">
    <div class="sidebar-header">
        <div class="logo">
            <a href="<?= URLROOT ?>/admin/Dashboard"><h2 style="color: #fff; font-weight: bold; margin: 0; font-size: 24px; letter-spacing: 2px;">AURELIA</h2></a>
        </div>
    </div>
    <div class="main-menu">
        <div class="menu-inner">
            <nav>
                <ul class="metismenu" id="menu">
                    <li class="<?= $isDashboard ? 'active' : '' ?>">
                        <a href="<?= URLROOT ?>/admin/Dashboard"><i class="ti-dashboard"></i> <span>Dashboard</span></a>
                    </li>
                    <li class="<?= $isProducts ? 'active' : '' ?>">
                        <a href="javascript:void(0)" aria-expanded="<?= $isProducts ? 'true' : 'false' ?>"><i class="ti-palette"></i><span>Sản phẩm</span></a>
                        <ul class="mm-collapse <?= $isProducts ? 'mm-show' : '' ?>">
                            <li class="<?= ($isProducts && strpos($uri, 'create') === false) ? 'active' : '' ?>"><a href="<?= URLROOT ?>/admin/Products">Danh sách sản phẩm</a></li>
                            <li class="<?= (strpos($uri, '/admin/Products/create') !== false) ? 'active' : '' ?>"><a href="<?= URLROOT ?>/admin/Products/create">Thêm sản phẩm mới</a></li>
                        </ul>
                    </li>
                    <li class="<?= $isCategories ? 'active' : '' ?>">
                        <a href="javascript:void(0)" aria-expanded="<?= $isCategories ? 'true' : 'false' ?>"><i class="ti-layout-grid2"></i><span>Danh mục</span></a>
                        <ul class="mm-collapse <?= $isCategories ? 'mm-show' : '' ?>">
                            <li class="<?= ($isCategories && strpos($uri, 'create') === false) ? 'active' : '' ?>"><a href="<?= URLROOT ?>/admin/Categories">Danh sách danh mục</a></li>
                            <li class="<?= (strpos($uri, '/admin/Categories/create') !== false) ? 'active' : '' ?>"><a href="<?= URLROOT ?>/admin/Categories/create">Thêm danh mục mới</a></li>
                        </ul>
                    </li>
                    <li class="<?= $isOrders ? 'active' : '' ?>">
                        <a href="<?= URLROOT ?>/admin/Orders"><i class="ti-receipt"></i> <span>Quản lý Đơn hàng</span></a>
                    </li>
                    <li class="<?= $isCarts ? 'active' : '' ?>">
                        <a href="<?= URLROOT ?>/admin/Carts"><i class="ti-shopping-cart-full"></i> <span>Quản lý Giỏ hàng</span></a>
                    </li>
                    <li class="<?= $isUsers ? 'active' : '' ?>">
                        <a href="<?= URLROOT ?>/admin/Users"><i class="ti-id-badge"></i> <span>Quản lý Khách hàng</span></a>
                    </li>
                    <li class="<?= $isAdminArticle ? 'active' : '' ?>">
                        <a href="<?= URLROOT ?>/AdminArticle"><i class="ti-write"></i> <span>Quản lý Bài viết</span></a>
                    </li>
                    <li class="<?= $isAdminComment ? 'active' : '' ?>">
                        <a href="<?= URLROOT ?>/AdminComment"><i class="ti-comments"></i> <span>Quản lý Bình luận</span></a>
                    </li>
                    <li class="<?= $isAdminAbout ? 'active' : '' ?>">
                        <a href="<?= URLROOT ?>/AdminAbout"><i class="ti-info-alt"></i> <span>Quản lý About</span></a>
                    </li>
                    <li class="<?= $isAdminFaq ? 'active' : '' ?>">
                        <a href="<?= URLROOT ?>/AdminFaq"><i class="ti-help-alt"></i> <span>Quản lý FAQ</span></a>
                    </li>
                    <li>
                        <a href="<?= URLROOT ?>/Home" target="_blank"><i class="ti-world"></i> <span>Xem Website</span></a>
                    </li>
                </ul>
            </nav>
        </div>
    </div>
</div>