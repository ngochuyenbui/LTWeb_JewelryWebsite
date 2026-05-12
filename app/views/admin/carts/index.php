<div class="main-content-inner">
    <div class="row">
        <div class="col-12 mt-5">
            <div class="card">
                <div class="card-body">
                    <h4 class="header-title text-primary">Quản lý Giỏ hàng của người dùng</h4>

                    <div id="accordion1" class="according accordion-s2 mt-4">
                        <?php if (empty($data['carts'])): ?>
                            <div class="alert alert-info">Không có giỏ hàng nào đang hoạt động.</div>
                        <?php else: ?>
                            <?php foreach ($data['carts'] as $cart): ?>
                                <div class="card">
                                    <div class="card-header">
                                        <a class="card-link" data-bs-toggle="collapse" href="#collapse-<?= $cart->cartId ?>">
                                            <div class="d-flex justify-content-between w-100">
                                                <span>
                                                    <i class="ti-user me-2"></i>
                                                    <strong><?= htmlspecialchars($cart->fullname) ?></strong> (<?= htmlspecialchars($cart->username) ?>)
                                                </span>
                                                <span>
                                                    <i class="ti-shopping-cart-full me-2"></i>
                                                    Tổng giá trị: <strong class="text-danger"><?= number_format($cart->totalValue, 0, ',', '.') ?> đ</strong>
                                                    <span class="mx-2">|</span>
                                                    <i class="ti-time me-2"></i>
                                                    Cập nhật: <?= date('d/m/Y H:i', strtotime($cart->updated_at)) ?>
                                                </span>
                                            </div>
                                        </a>
                                    </div>
                                    <div id="collapse-<?= $cart->cartId ?>" class="collapse" data-bs-parent="#accordion1">
                                        <div class="card-body">
                                            <table class="table table-sm">
                                                <tbody>
                                                <?php foreach ($cart->items as $item): ?>
                                                    <tr>
                                                        <td style="width: 70px;">
                                                            <img src="<?= URLROOT . htmlspecialchars($item->image_url) ?>" style="width: 50px; height: 50px; object-fit: cover;" class="rounded">
                                                        </td>
                                                        <td>
                                                            <div><?= htmlspecialchars($item->name) ?></div>
                                                            <small class="text-muted">Size: <?= htmlspecialchars($item->size) ?></small>
                                                        </td>
                                                        <td class="text-end">
                                                            <?= number_format($item->price, 0, ',', '.') ?> đ x <?= $item->quantity ?>
                                                        </td>
                                                    </tr>
                                                <?php endforeach; ?>
                                                </tbody>
                                            </table>
                                        </div>
                                    </div>
                                </div>
                            <?php endforeach; ?>
                        <?php endif; ?>
                    </div>

                    <!-- Phân trang -->
                    <?php if (($data['totalPages'] ?? 0) > 1): ?>
                    <nav aria-label="Page navigation" class="mt-4">
                        <ul class="pagination justify-content-center">
                            <li class="page-item <?= (($data['currentPage'] ?? 1) <= 1) ? 'disabled' : '' ?>">
                                <a class="page-link" href="?page=<?= max(1, ($data['currentPage'] ?? 1) - 1) ?>">Trước</a>
                            </li>
                            <?php for ($i = 1; $i <= ($data['totalPages'] ?? 1); $i++): ?>
                                <li class="page-item <?= (($data['currentPage'] ?? 1) == $i) ? 'active' : '' ?>">
                                    <a class="page-link" href="?page=<?= $i ?>"><?= $i ?></a>
                                </li>
                            <?php endfor; ?>
                            <li class="page-item <?= (($data['currentPage'] ?? 1) >= ($data['totalPages'] ?? 1)) ? 'disabled' : '' ?>">
                                <a class="page-link" href="?page=<?= min(($data['totalPages'] ?? 1), ($data['currentPage'] ?? 1) + 1) ?>">Sau</a>
                            </li>
                        </ul>
                    </nav>
                    <?php endif; ?>
                </div>
            </div>
        </div>
    </div>
</div>