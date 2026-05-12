<div class="main-content-inner">
    <div class="row">
        <div class="col-12 mt-5">
            <div class="card">
                <div class="card-body">
                    <h4 class="header-title text-primary">Quản lý Danh Mục</h4>

                    <?php if (isset($_SESSION['success'])): ?>
                        <div class="alert alert-success alert-dismissible fade show" role="alert">
                            <strong>Thành công!</strong> <?= htmlspecialchars($_SESSION['success']) ?>
                            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                        </div>
                        <?php unset($_SESSION['success']); ?>
                    <?php endif; ?>

                    <div class="d-flex justify-content-between align-items-center mb-3">
                        <a href="<?= URLROOT ?>/admin/Categories/create" class="btn btn-primary">
                            <i class="ti-plus"></i> Thêm danh mục mới
                        </a>
                        <span class="text-muted">Đang hiển thị: <?= count($data['categories'] ?? []) ?> / <?= $data['totalItems'] ?? 0 ?> danh mục</span>
                    </div>

                    <div class="data-tables">
                        <table class="text-center table table-bordered table-hover">
                            <thead class="bg-light text-capitalize">
                                <tr>
                                    <th>Hình ảnh</th>
                                    <th>Tên danh mục</th>
                                    <th>Phân loại (Type)</th>
                                    <th>Đường dẫn (Slug)</th>
                                    <th>Trạng thái</th>
                                    <th>Hành động</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php foreach ($data['categories'] ?? [] as $cate): ?>
                                    <?php
                                    $c_id = is_object($cate) ? ($cate->cateId ?? '') : ($cate['cateId'] ?? '');
                                    $c_name = is_object($cate) ? ($cate->name ?? '') : ($cate['name'] ?? '');
                                    $c_image = is_object($cate) ? ($cate->image_url ?? '') : ($cate['image_url'] ?? '');
                                    $c_type = is_object($cate) ? ($cate->type ?? '') : ($cate['type'] ?? '');
                                    $c_slug = is_object($cate) ? ($cate->slug ?? '') : ($cate['slug'] ?? '');
                                    $c_hidden = is_object($cate) ? ($cate->is_hidden ?? 0) : ($cate['is_hidden'] ?? 0);
                                    $imgSrc = empty($c_image) ? "https://placehold.co/100x100?text=No+Image" : (strpos($c_image, 'http') === 0 ? $c_image : URLROOT . $c_image);
                                    ?>
                                    <tr class="<?= $c_hidden ? 'table-secondary text-muted' : '' ?>">
                                        <td><img src="<?= htmlspecialchars($imgSrc) ?>" alt="img" class="rounded" style="width: 50px; height: 50px; object-fit: cover;"></td>
                                        <td class="fw-bold"><?= htmlspecialchars($c_name) ?></td>
                                        <td><?= htmlspecialchars($c_type) ?></td>
                                        <td><?= htmlspecialchars($c_slug) ?></td>
                                        <td>
                                            <?php if ($c_hidden): ?>
                                                <span class="badge badge-pill bg-secondary text-white" style="padding: 8px 12px; font-size: 13px;">Đã ẩn</span>
                                            <?php else: ?>
                                                <span class="badge badge-pill bg-success text-white" style="padding: 8px 12px; font-size: 13px;">Hiển thị</span>
                                            <?php endif; ?>
                                        </td>
                                        <td>
                                            <a href="<?= URLROOT ?>/admin/Categories/edit/<?= $c_id ?>" class="btn btn-warning btn-sm text-white"><i class="ti-pencil"></i> Sửa</a>
                                            <button type="button" class="btn btn-<?= $c_hidden ? 'success' : 'secondary' ?> btn-sm text-white btn-toggle-hide" data-id="<?= $c_id ?>" data-hide="<?= $c_hidden ? 0 : 1 ?>">
                                                <i class="<?= $c_hidden ? 'ti-eye' : 'ti-eye' ?>"></i> <?= $c_hidden ? 'Hiện' : 'Ẩn' ?>
                                            </button>
                                        </td>
                                    </tr>
                                <?php endforeach; ?>
                            </tbody>
                        </table>
                    </div>

                    <!-- Phân trang -->
                    <?php if (($data['totalPages'] ?? 0) > 1): ?>
                    <div class="mt-4 d-flex justify-content-center">
                        <nav aria-label="Page navigation">
                            <ul class="pagination">
                                <?php
                                    $currentPage = $data['currentPage'] ?? 1;
                                    $totalPages = $data['totalPages'] ?? 1;

                                    // Build query string for pagination links
                                    $query = $_GET;
                                    unset($query['page'], $query['url']);
                                    $queryString = http_build_query($query);
                                    $baseUrl = URLROOT . '/admin/Categories?';
                                ?>
                                <li class="page-item <?= $currentPage <= 1 ? 'disabled' : '' ?>">
                                    <a class="page-link" href="<?= $baseUrl . $queryString . '&page=' . ($currentPage - 1) ?>" aria-label="Previous">
                                        <span aria-hidden="true"><</span>
                                    </a>
                                </li>
                                <?php for ($i = 1; $i <= $totalPages; $i++): ?>
                                    <li class="page-item <?= $i == $currentPage ? 'active' : '' ?>">
                                        <a class="page-link" href="<?= $baseUrl . $queryString . '&page=' . $i ?>"><?= $i ?></a>
                                    </li>
                                <?php endfor; ?>
                                <li class="page-item <?= $currentPage >= $totalPages ? 'disabled' : '' ?>">
                                    <a class="page-link" href="<?= $baseUrl . $queryString . '&page=' . ($currentPage + 1) ?>" aria-label="Next">
                                        <span aria-hidden="true">></span>
                                    </a>
                                </li>
                            </ul>
                        </nav>
                    </div>
                    <?php endif; ?>
                </div>
            </div>
        </div>
    </div>
</div>

<script>
document.addEventListener("DOMContentLoaded", function() {
    document.querySelectorAll('.btn-toggle-hide').forEach(function(btn) {
        btn.addEventListener('click', function() {
            let id = this.getAttribute('data-id');
            let isHidden = this.getAttribute('data-hide');
            let actionText = isHidden == '1' ? "ẨN" : "HIỆN";

            Swal.fire({
                title: 'Xác nhận',
                text: "Bạn có muốn " + actionText + " danh mục này không?",
                icon: 'question',
                showCancelButton: true,
                confirmButtonColor: '#3085d6',
                cancelButtonColor: '#d33',
                confirmButtonText: 'Đồng ý',
                cancelButtonText: 'Hủy'
            }).then((result) => {
                if (result.isConfirmed) {
                    fetch('<?= URLROOT ?>/admin/Categories/toggleHide/' + id, {
                        method: 'POST',
                        headers: {
                            'Content-Type': 'application/x-www-form-urlencoded',
                        },
                        body: 'is_hidden=' + isHidden
                    })
                    .then(response => response.json())
                    .then(res => {
                        if (res.success) {
                            Swal.fire({
                                title: 'Thành công!',
                                text: res.message,
                                icon: 'success',
                                timer: 1500,
                                showConfirmButton: false
                            }).then(() => location.reload());
                        } else {
                            Swal.fire('Lỗi!', res.message, 'error');
                        }
                    })
                    .catch(() => Swal.fire('Lỗi!', 'Có lỗi xảy ra, vui lòng thử lại.', 'error'));
                }
            });
        });
    });
});
</script>