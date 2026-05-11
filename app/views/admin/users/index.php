<div class="main-content-inner">
    <div class="row">
        <div class="col-12 mt-5">
            <div class="card">
                <div class="card-body">
                    <h4 class="header-title text-primary">Quản lý Khách hàng</h4>
                    
                    <?php if (isset($_SESSION['success'])): ?>
                        <div class="alert alert-success alert-dismissible fade show" role="alert">
                            <?= $_SESSION['success'] ?>
                            <button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                        </div>
                        <?php unset($_SESSION['success']); ?>
                    <?php endif; ?>

                    <?php if (isset($_SESSION['error'])): ?>
                        <div class="alert alert-danger alert-dismissible fade show" role="alert">
                            <?= htmlspecialchars($_SESSION['error']) ?>
                            <button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                        </div>
                        <?php unset($_SESSION['error']); ?>
                    <?php endif; ?>
                    
                    <!-- Search Form -->
                    <form method="GET" action="<?= URLROOT ?>/admin/Users" class="mb-4">
                        <div class="input-group" style="max-width: 500px;">
                            <input type="text" name="search" class="form-control" style="height: auto;" placeholder="Tìm kiếm theo tên, email, tài khoản..." value="<?= htmlspecialchars($data['search'] ?? '') ?>">
                            <button class="btn btn-primary mx-3"  type="submit"><i class="ti-search"></i> Tìm kiếm</button>
                        </div>
                    </form>

                    <div class="data-tables">
                        <table class="text-center table table-bordered table-hover">
                            <thead class="bg-light text-capitalize">
                                <tr>
                                    <th>ID</th>
                                    <th>Tài khoản</th>
                                    <th>Họ tên</th>
                                    <th>Email</th>
                                    <th>Trạng thái</th>
                                    <th>Hành động</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php foreach ($data['users'] ?? [] as $user): ?>
                                    <?php
                                        $u_id = is_object($user) ? $user->userId : $user['userId'];
                                        $u_name = is_object($user) ? $user->username : $user['username'];
                                        $u_full = is_object($user) ? $user->fullname : $user['fullname'];
                                        $u_email = is_object($user) ? $user->email : $user['email'];
                                        $u_role = is_object($user) ? $user->role : $user['role'];
                                        $is_locked = ($u_role === 'locked');
                                    ?>
                                    <tr>
                                        <td><?= htmlspecialchars($u_id) ?></td>
                                        <td><strong><?= htmlspecialchars($u_name) ?></strong></td>
                                        <td><?= htmlspecialchars($u_full) ?></td>
                                        <td><?= htmlspecialchars($u_email) ?></td>
                                        <td>
                                            <?php if ($is_locked): ?>
                                                <span class="badge badge-danger">Bị khóa</span>
                                            <?php else: ?>
                                                <span class="badge badge-success">Hoạt động</span>
                                            <?php endif; ?>
                                        </td>
                                        <td>
                                            <button type="button" class="btn btn-info btn-sm text-white btn-view-detail" data-id="<?= $u_id ?>" data-toggle="modal" data-target="#userModal" title="Xem chi tiết"><i class="ti-eye"></i></button>
                                            
                                            <form action="<?= URLROOT ?>/admin/Users/resetPassword/<?= $u_id ?>" method="POST" class="d-inline" onsubmit="return confirm('Bạn có chắc chắn muốn đặt lại mật khẩu của người dùng này về mặc định không?');">
                                                <button type="submit" class="btn btn-warning btn-sm text-white" title="Reset Mật khẩu"><i class="ti-key"></i></button>
                                            </form>

                                            <?php if ($is_locked): ?>
                                                <form action="<?= URLROOT ?>/admin/Users/toggleLock/<?= $u_id ?>" method="POST" class="d-inline" onsubmit="return confirm('Bạn muốn mở khóa tài khoản này để người dùng đăng nhập bình thường?');">
                                                    <input type="hidden" name="is_locked" value="0">
                                                    <button type="submit" class="btn btn-success btn-sm text-white" title="Mở khóa"><i class="ti-unlock"></i></button>
                                                </form>
                                            <?php else: ?>
                                                <form action="<?= URLROOT ?>/admin/Users/toggleLock/<?= $u_id ?>" method="POST" class="d-inline" onsubmit="return confirm('Bạn có chắc chắn muốn khóa tài khoản này? Người dùng sẽ không thể tiếp tục đăng nhập hoặc mua hàng.');">
                                                    <input type="hidden" name="is_locked" value="1">
                                                    <button type="submit" class="btn btn-danger btn-sm text-white" title="Khóa tài khoản"><i class="ti-lock"></i></button>
                                                </form>
                                            <?php endif; ?>
                                        </td>
                                    </tr>
                                <?php endforeach; ?>
                                <?php if (empty($data['users'])): ?>
                                    <tr><td colspan="6">Không tìm thấy người dùng nào.</td></tr>
                                <?php endif; ?>
                            </tbody>
                        </table>
                    </div>

                    <!-- Phân trang -->
                    <?php 
                    if (($data['totalPages'] ?? 0) > 1): 
                        $searchParam = urlencode($data['search'] ?? '');
                    ?>
                    <nav aria-label="Page navigation" class="mt-4">
                        <ul class="pagination justify-content-center">
                            <li class="page-item <?= (($data['currentPage'] ?? 1) <= 1) ? 'disabled' : '' ?>">
                                <a class="page-link" href="?search=<?= $searchParam ?>&page=<?= max(1, ($data['currentPage'] ?? 1) - 1) ?>">Trước</a>
                            </li>
                            <?php for ($i = 1; $i <= ($data['totalPages'] ?? 1); $i++): ?>
                                <li class="page-item <?= (($data['currentPage'] ?? 1) == $i) ? 'active' : '' ?>">
                                    <a class="page-link" href="?search=<?= $searchParam ?>&page=<?= $i ?>"><?= $i ?></a>
                                </li>
                            <?php endfor; ?>
                            <li class="page-item <?= (($data['currentPage'] ?? 1) >= ($data['totalPages'] ?? 1)) ? 'disabled' : '' ?>">
                                <a class="page-link" href="?search=<?= $searchParam ?>&page=<?= min(($data['totalPages'] ?? 1), ($data['currentPage'] ?? 1) + 1) ?>">Sau</a>
                            </li>
                        </ul>
                    </nav>
                    <?php endif; ?>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Modal Xem Chi Tiết bằng AJAX -->
<div class="modal fade" id="userModal" tabindex="-1" role="dialog" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Thông tin chi tiết</h5>
            </div>
            <div class="modal-body" id="user-detail-content">
                <div class="text-center"><div class="spinner-border text-primary" role="status"></div></div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-dismiss="modal">Đóng</button>
            </div>
        </div>
    </div>
</div>

<script>
document.addEventListener('DOMContentLoaded', function() {
    const detailButtons = document.querySelectorAll('.btn-view-detail');
    const modalContent = document.getElementById('user-detail-content');

    detailButtons.forEach(btn => {
        btn.addEventListener('click', function() {
            const userId = this.getAttribute('data-id');
            modalContent.innerHTML = '<div class="text-center"><div class="spinner-border text-primary" role="status"></div></div>';
            
            fetch('<?= URLROOT ?>/admin/Users/detail/' + userId)
                .then(response => response.text())
                .then(html => { modalContent.innerHTML = html; })
                .catch(error => { modalContent.innerHTML = '<div class="alert alert-danger">Có lỗi xảy ra khi tải dữ liệu.</div>'; });
        });
    });
});
</script>