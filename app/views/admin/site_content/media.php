<div class="row">
    <div class="col-12 mt-4">
        <div class="card">
            <div class="card-body">
                <h4 class="header-title d-flex justify-content-between align-items-center">
                    Hình ảnh trang chủ và liên hệ
                    <a href="<?= URLROOT ?>/AdminSiteContent" class="btn btn-secondary btn-sm">Quay lại nội dung</a>
                </h4>

                <?php if (isset($_GET['success'])): ?>
                    <div class="alert alert-success">Cập nhật hình ảnh thành công.</div>
                <?php endif; ?>
                <?php if (isset($_GET['error'])): ?>
                    <div class="alert alert-danger">Không tìm thấy hình ảnh cần xử lý.</div>
                <?php endif; ?>

                <div class="single-table mt-3">
                    <div class="table-responsive">
                        <table class="table table-hover">
                            <thead class="text-uppercase bg-light">
                                <tr>
                                    <th>ID</th>
                                    <th>Ảnh</th>
                                    <th>Khóa ảnh</th>
                                    <th>Vị trí</th>
                                    <th>Đường dẫn local</th>
                                    <th>Hành động</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php if (empty($images)): ?>
                                    <tr><td colspan="6" class="text-center">Không có hình ảnh.</td></tr>
                                <?php else: ?>
                                    <?php foreach ($images as $image): ?>
                                        <tr>
                                            <td><?= (int)$image->imageId ?></td>
                                            <td><img src="<?= URLROOT . '/' . ltrim(htmlspecialchars($image->filepath, ENT_QUOTES, 'UTF-8'), '/') ?>" alt="<?= htmlspecialchars($image->image_key, ENT_QUOTES, 'UTF-8') ?>" class="admin-thumb"></td>
                                            <td><code><?= htmlspecialchars($image->image_key, ENT_QUOTES, 'UTF-8') ?></code></td>
                                            <td><?= htmlspecialchars($image->location_tag, ENT_QUOTES, 'UTF-8') ?></td>
                                            <td><?= htmlspecialchars($image->filepath, ENT_QUOTES, 'UTF-8') ?></td>
                                            <td><a href="<?= URLROOT ?>/AdminSiteContent/editImage/<?= (int)$image->imageId ?>" class="btn btn-info btn-sm">Upload mới</a></td>
                                        </tr>
                                    <?php endforeach; ?>
                                <?php endif; ?>
                            </tbody>
                        </table>
                    </div>
                </div>

                <?php if ($totalPages > 1): ?>
                    <nav class="mt-4">
                        <ul class="pagination justify-content-center">
                            <?php for ($i = 1; $i <= $totalPages; $i++): ?>
                                <li class="page-item <?= $i == $currentPage ? 'active' : '' ?>">
                                    <a class="page-link" href="<?= URLROOT ?>/AdminSiteContent/media?page=<?= $i ?>"><?= $i ?></a>
                                </li>
                            <?php endfor; ?>
                        </ul>
                    </nav>
                <?php endif; ?>
            </div>
        </div>
    </div>
</div>
