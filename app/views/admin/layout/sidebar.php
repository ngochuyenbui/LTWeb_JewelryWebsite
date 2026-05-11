<?php
$uri = $_SERVER['REQUEST_URI'];
$isDashboard = strpos($uri, '/admin/Dashboard') !== false;
$isProducts = strpos($uri, '/admin/Products') !== false;
$isCategories = strpos($uri, '/admin/Categories') !== false;
$isAdmins = strpos($uri, '/admin/Admins') !== false;
$isOrders = strpos($uri, '/admin/Orders') !== false;
$isCarts = strpos($uri, '/admin/Carts') !== false;
$isUsers = strpos($uri, '/admin/Users') !== false;
?>
<div class="sidebar-menu">
    <div class="sidebar-header">
        <div class="logo">
            <a href="<?= URLROOT ?>/admin/Dashboard"><h2 class="text-2xl md:text-3xl font-bold tracking-wide gold-text">AURELIA</h2></a>
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
                        <ul class="collapse <?= $isProducts ? 'in' : '' ?>">
                            <li class="<?= ($isProducts && strpos($uri, 'create') === false) ? 'active' : '' ?>"><a href="<?= URLROOT ?>/admin/Products">Danh sách sản phẩm</a></li>
                            <li class="<?= (strpos($uri, '/admin/Products/create') !== false) ? 'active' : '' ?>"><a href="<?= URLROOT ?>/admin/Products/create">Thêm sản phẩm mới</a></li>
                        </ul>
                    </li>
                    <li class="<?= $isCategories ? 'active' : '' ?>">
                        <a href="javascript:void(0)" aria-expanded="<?= $isCategories ? 'true' : 'false' ?>"><i class="ti-layout-grid2"></i><span>Danh mục</span></a>
                        <ul class="collapse <?= $isCategories ? 'in' : '' ?>">
                            <li class="<?= ($isCategories && strpos($uri, 'create') === false) ? 'active' : '' ?>"><a href="<?= URLROOT ?>/admin/Categories">Danh sách danh mục</a></li>
                            <li class="<?= (strpos($uri, '/admin/Categories/create') !== false) ? 'active' : '' ?>"><a href="<?= URLROOT ?>/admin/Categories/create">Thêm danh mục mới</a></li>
                        </ul>
                    </li>
                    <li class="<?= $isOrders ? 'active' : '' ?>">
                        <a href="<?= URLROOT ?>/admin/Orders"><i class="ti-receipt"></i> <span>Quản lý Đơn hàng</span></a>
                    </li>
                    <li class="<?= $isCarts ? 'active' : '' ?>">
                        <a href="<?= URLROOT ?>/admin/Carts"><i class="ti-shopping-cart-full"></i> <span>Xem Giỏ hàng</span></a>
                    </li>
                    <li class="<?= $isUsers ? 'active' : '' ?>">
                        <a href="<?= URLROOT ?>/admin/Users"><i class="ti-id-badge"></i> <span>Quản lý Khách hàng</span></a>
                    </li>
                    <li class="<?= $isAdmins ? 'active' : '' ?>">
                        <a href="javascript:void(0)" aria-expanded="<?= $isAdmins ? 'true' : 'false' ?>"><i class="ti-user"></i><span>Tài khoản Admin</span></a>
                        <ul class="collapse <?= $isAdmins ? 'in' : '' ?>">
                            <li class="<?= $isAdmins ? 'active' : '' ?>"><a href="<?= URLROOT ?>/admin/Admins">Danh sách Admin</a></li>
                        </ul>
                    </li>
                </ul>
            </nav>
        </div>
    </div>
</div>