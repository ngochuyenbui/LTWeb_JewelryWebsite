<div class="row">
    <div class="col-12 mt-4">
        <div class="card">
            <div class="card-body">
                <h4 class="header-title">Quản lý Bình luận</h4>
                
                <?php if(isset($_GET['success'])): ?>
                    <div class="alert alert-success">Thao tác thành công!</div>
                <?php endif; ?>
                <?php if(isset($_GET['error'])): ?>
                    <div class="alert alert-danger">Có lỗi xảy ra, vui lòng thử lại!</div>
                <?php endif; ?>

                <div class="mt-3 mb-3">
                    <form action="<?= URLROOT ?>/AdminComment/index" method="GET" class="form-inline">
                        <input type="text" name="search" class="form-control mr-2" placeholder="Tìm kiếm bình luận, người đăng..." value="<?= htmlspecialchars($searchKeyword) ?>">
                        <button type="submit" class="btn btn-secondary btn-sm">Tìm kiếm</button>
                    </form>
                </div>

                <div class="single-table">
                    <div class="table-responsive">
                        <table class="table table-hover text-center">
                            <thead class="text-uppercase bg-light">
                                <tr>
                                    <th scope="col">ID</th>
                                    <th scope="col">Người dùng</th>
                                    <th scope="col" class="text-left">Nội dung / Bài viết</th>
                                    <th scope="col">Rating</th>
                                    <th scope="col">Ngày đăng</th>
                                    <th scope="col">Trạng thái</th>
                                    <th scope="col">Hành động</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php if(empty($comments)): ?>
                                    <tr><td colspan="7">Không có bình luận nào.</td></tr>
                                <?php else: ?>
                                    <?php foreach($comments as $cmt): ?>
                                        <tr>
                                            <th scope="row"><?= $cmt->commentId ?></th>
                                            <td>
                                                <?= htmlspecialchars($cmt->member_name ?? $cmt->guest_name ?? 'Khách') ?>
                                            </td>
                                            <td class="text-left">
                                                <strong>Nội dung:</strong> <?= htmlspecialchars($cmt->content) ?><br>
                                                <small class="text-muted">
                                                    Bài viết: <a href="<?= URLROOT ?>/Article/detail/<?= $cmt->contentId ?>" target="_blank"><?= htmlspecialchars($cmt->article_title ?? 'N/A') ?></a>
                                                </small>
                                            </td>
                                            <td><?= $cmt->rating ?> <i class="fa fa-star text-warning"></i></td>
                                            <td><?= date('d/m/Y H:i', strtotime($cmt->created_at)) ?></td>
                                            <td>
                                                <?php if($cmt->status == 'approved'): ?>
                                                    <span class="badge badge-success">Đã duyệt</span>
                                                <?php elseif($cmt->status == 'pending'): ?>
                                                    <span class="badge badge-warning">Chờ duyệt</span>
                                                <?php else: ?>
                                                    <span class="badge badge-secondary">Đã ẩn</span>
                                                <?php endif; ?>
                                            </td>
                                            <td>
                                                <form action="<?= URLROOT ?>/AdminComment/updateStatus" method="POST" class="d-inline">
                                                    <input type="hidden" name="commentId" value="<?= $cmt->commentId ?>">
                                                    <select name="status" class="custom-select custom-select-sm d-inline-block w-auto" onchange="this.form.submit()">
                                                        <option value="pending" <?= $cmt->status == 'pending' ? 'selected' : '' ?>>Chờ duyệt</option>
                                                        <option value="approved" <?= $cmt->status == 'approved' ? 'selected' : '' ?>>Duyệt</option>
                                                        <option value="hidden" <?= $cmt->status == 'hidden' ? 'selected' : '' ?>>Ẩn</option>
                                                    </select>
                                                </form>
                                                
                                                <a href="<?= URLROOT ?>/AdminComment/delete/<?= $cmt->commentId ?>" class="text-danger ml-2" onclick="return confirm('Bạn có chắc muốn xóa bình luận này?')"><i class="ti-trash"></i></a>
                                            </td>
                                        </tr>
                                    <?php endforeach; ?>
                                <?php endif; ?>
                            </tbody>
                        </table>
                    </div>
                </div>

                <!-- Pagination -->
                <?php if ($totalPages > 1): ?>
                    <nav class="mt-4">
                        <ul class="pagination justify-content-center">
                            <?php for ($i = 1; $i <= $totalPages; $i++): ?>
                                <li class="page-item <?= ($i == $currentPage) ? 'active' : '' ?>">
                                    <a class="page-link" href="<?= URLROOT ?>/AdminComment/index?page=<?= $i ?><?= !empty($searchKeyword) ? '&search='.urlencode($searchKeyword) : '' ?>"><?= $i ?></a>
                                </li>
                            <?php endfor; ?>
                        </ul>
                    </nav>
                <?php endif; ?>
            </div>
        </div>
    </div>
</div>
