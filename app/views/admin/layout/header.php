<div class="header-area">
    <div class="row align-items-center">
        <div class="col-md-6 col-sm-8 clearfix">
            <div class="nav-btn float-start">
                <span></span>
                <span></span>
                <span></span>
            </div>
        </div>
        <div class="col-md-6 col-sm-4 clearfix">
            <ul class="notification-area float-end">
                <li id="full-view"><i class="ti-fullscreen"></i></li>
                <li id="full-view-exit"><i class="ti-zoom-out"></i></li>
            </ul>
        </div>
    </div>
</div>

<div class="page-title-area">
    <div class="row align-items-center">
        <div class="col-sm-6">
            <div class="breadcrumbs-area clearfix">
                <h4 class="page-title float-start"><?= isset($title) ? htmlspecialchars($title) : 'Dashboard' ?></h4>
            </div>
        </div>
        <div class="col-sm-6 clearfix">
            <div class="user-profile float-end">
                <img class="avatar user-thumb" src="https://ui-avatars.com/api/?name=<?= urlencode($_SESSION['username'] ?? 'Admin') ?>&background=random&color=fff" alt="avatar" style="border-radius: 50%; width: 35px; height: 35px; margin-right: 15px;">
                <h4 class="user-name dropdown-toggle" data-bs-toggle="dropdown" aria-expanded="false">
                    <?= htmlspecialchars($_SESSION['username'] ?? 'Admin') ?> <i class="fa fa-angle-down"></i>
                </h4>
                <div class="dropdown-menu">
                    <a class="dropdown-item" href="<?= URLROOT ?>/admin/Dashboard">Trang chủ Admin</a>
                    <a class="dropdown-item" href="<?= URLROOT ?>/Login/logout">Đăng xuất</a>
                </div>
            </div>
        </div>
    </div>
</div>