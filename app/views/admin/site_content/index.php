<div class="row">
    <div class="col-12 mt-4">
        <div class="card">
            <div class="card-body">
                <h4 class="header-title d-flex justify-content-between align-items-center">
                    Nội dung trang chủ và liên hệ
                    <a href="<?= URLROOT ?>/AdminSiteContent/media" class="btn btn-secondary btn-sm"><span class="ti-image"></span> Quản lý hình ảnh</a>
                </h4>

                <?php if (isset($_GET['success'])): ?>
                    <div class="alert alert-success">Cập nhật thành công.</div>
                <?php endif; ?>
                <?php if (isset($_GET['error'])): ?>
                    <div class="alert alert-danger">Không tìm thấy nội dung cần xử lý.</div>
                <?php endif; ?>

                <form action="<?= URLROOT ?>/AdminSiteContent/index" method="GET" class="form-inline mt-3 mb-3">
                    <input type="text" name="search" class="form-control mr-2" placeholder="Tìm theo khóa, nhóm hoặc nội dung..." value="<?= htmlspecialchars($search, ENT_QUOTES, 'UTF-8') ?>">
                    <button type="submit" class="btn btn-secondary btn-sm">Tìm kiếm</button>
                </form>

                <div class="single-table">
                    <div class="table-responsive">
                        <table class="table table-hover">
                            <thead class="text-uppercase bg-light">
                                <tr>
                                    <th>ID</th>
                                    <th>Nhóm</th>
                                    <th>Khóa nội dung</th>
                                    <th>Nội dung</th>
                                    <th>Cập nhật</th>
                                    <th>Hành động</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php if (empty($items)): ?>
                                    <tr><td colspan="6" class="text-center">Không có nội dung phù hợp.</td></tr>
                                <?php else: ?>
                                    <?php foreach ($items as $item): ?>
                                        <tr>
                                            <td><?= (int)$item->pageId ?></td>
                                            <td><?= htmlspecialchars($item->section, ENT_QUOTES, 'UTF-8') ?></td>
                                            <td><code><?= htmlspecialchars($item->page_key, ENT_QUOTES, 'UTF-8') ?></code></td>
                                            <td><?= htmlspecialchars(mb_substr($item->content, 0, 120, 'UTF-8'), ENT_QUOTES, 'UTF-8') ?><?= mb_strlen($item->content, 'UTF-8') > 120 ? '...' : '' ?></td>
                                            <td><?= htmlspecialchars($item->updated_at, ENT_QUOTES, 'UTF-8') ?></td>
                                            <td><a href="<?= URLROOT ?>/AdminSiteContent/edit/<?= (int)$item->pageId ?>" class="btn btn-info btn-sm">Sửa</a></td>
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
                                    <a class="page-link" href="<?= URLROOT ?>/AdminSiteContent/index?page=<?= $i ?><?= $search !== '' ? '&search=' . urlencode($search) : '' ?>"><?= $i ?></a>
                                </li>
                            <?php endfor; ?>
                        </ul>
                    </nav>
                <?php endif; ?>
            </div>
        </div>
    </div>
</div>
