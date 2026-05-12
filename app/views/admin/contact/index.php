<div class="row">
    <div class="col-12 mt-4">
        <div class="card">
            <div class="card-body">
                <h4 class="header-title">Quản lý liên hệ khách hàng</h4>

                <?php if (isset($_GET['success'])): ?>
                    <div class="alert alert-success">Thao tác thành công.</div>
                <?php endif; ?>
                <?php if (isset($_GET['error'])): ?>
                    <div class="alert alert-danger">Không tìm thấy liên hệ cần xử lý.</div>
                <?php endif; ?>

                <form action="<?= URLROOT ?>/AdminContact/index" method="GET" class="form-inline mt-3 mb-3">
                    <input type="text" name="search" class="form-control mr-2" placeholder="Tên, email, điện thoại, tiêu đề..." value="<?= htmlspecialchars($search, ENT_QUOTES, 'UTF-8') ?>">
                    <select name="status" class="custom-select mr-2">
                        <option value="" <?= $status === '' ? 'selected' : '' ?>>Tất cả trạng thái</option>
                        <option value="pending" <?= $status === 'pending' ? 'selected' : '' ?>>Chưa đọc</option>
                        <option value="read" <?= $status === 'read' ? 'selected' : '' ?>>Đã đọc</option>
                        <option value="replied" <?= $status === 'replied' ? 'selected' : '' ?>>Đã phản hồi</option>
                    </select>
                    <button type="submit" class="btn btn-secondary btn-sm">Lọc</button>
                </form>

                <div class="single-table">
                    <div class="table-responsive">
                        <table class="table table-hover">
                            <thead class="text-uppercase bg-light">
                                <tr>
                                    <th>ID</th>
                                    <th>Khách hàng</th>
                                    <th>Liên hệ</th>
                                    <th>Tiêu đề</th>
                                    <th>Trạng thái</th>
                                    <th>Hành động</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php if (empty($contacts)): ?>
                                    <tr><td colspan="6" class="text-center">Không có liên hệ phù hợp.</td></tr>
                                <?php else: ?>
                                    <?php foreach ($contacts as $contact): ?>
                                        <tr>
                                            <td><?= (int)$contact->contactId ?></td>
                                            <td>
                                                <strong><?= htmlspecialchars($contact->name, ENT_QUOTES, 'UTF-8') ?></strong>
                                                <?php if (!empty($contact->member_name)): ?>
                                                    <br><small class="text-muted">Thành viên: <?= htmlspecialchars($contact->member_name, ENT_QUOTES, 'UTF-8') ?></small>
                                                <?php endif; ?>
                                            </td>
                                            <td>
                                                <?= htmlspecialchars($contact->email ?? '', ENT_QUOTES, 'UTF-8') ?>
                                                <?php if (!empty($contact->phone)): ?>
                                                    <br><small><?= htmlspecialchars($contact->phone, ENT_QUOTES, 'UTF-8') ?></small>
                                                <?php endif; ?>
                                            </td>
                                            <td><?= htmlspecialchars($contact->subject ?? '', ENT_QUOTES, 'UTF-8') ?></td>
                                            <td>
                                                <?php if ($contact->status === 'replied'): ?>
                                                    <span class="badge badge-success">Đã phản hồi</span>
                                                <?php elseif ($contact->status === 'read'): ?>
                                                    <span class="badge badge-info">Đã đọc</span>
                                                <?php else: ?>
                                                    <span class="badge badge-warning">Chưa đọc</span>
                                                <?php endif; ?>
                                            </td>
                                            <td>
                                                <a href="<?= URLROOT ?>/AdminContact/detail/<?= (int)$contact->contactId ?>" class="btn btn-info btn-sm">Xem</a>
                                                <a href="<?= URLROOT ?>/AdminContact/delete/<?= (int)$contact->contactId ?>" class="btn btn-danger btn-sm" data-confirm="Xóa liên hệ này?">Xóa</a>
                                            </td>
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
                                    <a class="page-link" href="<?= URLROOT ?>/AdminContact/index?page=<?= $i ?><?= $search !== '' ? '&search=' . urlencode($search) : '' ?><?= $status !== '' ? '&status=' . urlencode($status) : '' ?>"><?= $i ?></a>
                                </li>
                            <?php endfor; ?>
                        </ul>
                    </nav>
                <?php endif; ?>
            </div>
        </div>
    </div>
</div>
