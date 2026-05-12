<div class="row">
    <div class="col-12 mt-4">
        <div class="card">
            <div class="card-body">
                <h4 class="header-title d-flex justify-content-between align-items-center">
                    Danh sách Bài viết
                    <a href="<?= URLROOT ?>/AdminArticle/create" class="btn btn-primary btn-sm"><i class="ti-plus"></i>
                        Thêm bài viết mới</a>
                </h4>

                <?php if (isset($_GET['success'])): ?>
                    <div class="alert alert-success">Thao tác thành công!</div>
                <?php endif; ?>
                <?php if (isset($_GET['error'])): ?>
                    <div class="alert alert-danger">Có lỗi xảy ra, vui lòng thử lại!</div>
                <?php endif; ?>

                <div class="mt-3 mb-3">
                    <form action="<?= URLROOT ?>/AdminArticle/index" method="GET" class="form-inline">
                        <input type="text" name="search" class="form-control mr-2" placeholder="Tìm kiếm bài viết..."
                            value="<?= htmlspecialchars($searchKeyword) ?>">
                        <button type="submit" class="btn btn-secondary btn-sm">Tìm kiếm</button>
                    </form>
                </div>

                <div class="single-table">
                    <div class="table-responsive">
                        <table class="table table-hover progress-table text-center">
                            <thead class="text-uppercase bg-light">
                                <tr>
                                    <th scope="col">ID</th>
                                    <th scope="col">Ảnh</th>
                                    <th scope="col" class="text-left">Tiêu đề</th>
                                    <th scope="col">Danh mục</th>
                                    <th scope="col">Tác giả</th>
                                    <th scope="col">Ngày đăng</th>
                                    <th scope="col">Hành động</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php if (empty($articles)): ?>
                                    <tr>
                                        <td colspan="7">Không có bài viết nào.</td>
                                    </tr>
                                <?php else: ?>
                                    <?php foreach ($articles as $article): ?>
                                        <tr>
                                            <th scope="row">
                                                <?= $article->articleId ?>
                                            </th>
                                            <td>
                                                <?php if (!empty($article->thumbnail)): ?>
                                                    <img src="<?= URLROOT . '/' . htmlspecialchars($article->thumbnail) ?>"
                                                        alt="img" width="50" height="50" style="object-fit:cover;">
                                                <?php else: ?>
                                                    <span class="text-muted">N/A</span>
                                                <?php endif; ?>
                                            </td>
                                            <td class="text-left">
                                                <a href="<?= URLROOT ?>/Article/detail/<?= $article->articleId ?>"
                                                    target="_blank">
                                                    <?= htmlspecialchars($article->title) ?>
                                                </a>
                                            </td>
                                            <td>
                                                <?= htmlspecialchars($article->category_name ?? '') ?>
                                            </td>
                                            <td>
                                                <?= htmlspecialchars($article->author_name ?? '') ?>
                                            </td>
                                            <td>
                                                <?= date('d/m/Y', strtotime($article->published_at)) ?>
                                            </td>
                                            <td>
                                                <a href="<?= URLROOT ?>/AdminArticle/edit/<?= $article->articleId ?>" class="btn btn-sm btn-info" title="Sửa">
                                                    <i class="fa fa-edit"></i>
                                                </a>
                                                <a href="<?= URLROOT ?>/AdminArticle/delete/<?= $article->articleId ?>" class="btn btn-sm btn-danger" onclick="return confirm('Bạn có chắc chắn muốn xóa bài viết này?');" title="Xóa">
                                                    <i class="fa fa-trash"></i>
                                                </a>
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
                                    <a class="page-link"
                                        href="<?= URLROOT ?>/AdminArticle/index?page=<?= $i ?><?= !empty($searchKeyword) ? '&search=' . urlencode($searchKeyword) : '' ?>">
                                        <?= $i ?>
                                    </a>
                                </li>
                            <?php endfor; ?>
                        </ul>
                    </nav>
                <?php endif; ?>
            </div>
        </div>
    </div>
</div>