<div class="main-content-inner">
    <div class="row">
        <div class="col-12 mt-5">
            <div class="card">
                <div class="card-body">
                    <h4 class="header-title">Tổng quan (Dashboard)</h4>
                    <p>Chào mừng Admin quay trở lại!</p>

                    <!-- Thống kê nhanh -->
                    <div class="row mt-4">
                        <div class="col-md-4 mb-3">
                            <div class="card bg-primary text-white h-100">
                                <div class="card-body d-flex justify-content-between align-items-center">
                                    <div>
                                        <h5 class="card-title text-white mb-2">Tổng sản phẩm</h5>
                                        <h2 class="fw-bold mb-0"><?= htmlspecialchars($data['totalProducts'] ?? 0) ?></h2>
                                    </div>
                                    <i class="ti-package" style="font-size: 40px; opacity: 0.5;"></i>
                                </div>
                                <a href="<?= URLROOT ?>/admin/Products" class="card-footer text-white d-flex justify-content-between align-items-center" style="background: rgba(0,0,0,0.1); border-top: none; text-decoration: none;">
                                    <span class="small fw-bold">Xem chi tiết</span>
                                    <i class="ti-arrow-circle-right"></i>
                                </a>
                            </div>
                        </div>
                        <div class="col-md-4 mb-3">
                            <div class="card bg-success text-white h-100">
                                <div class="card-body d-flex justify-content-between align-items-center">
                                    <div>
                                        <h5 class="card-title text-white mb-2">Tổng đơn hàng</h5>
                                        <h2 class="fw-bold mb-0"><?= htmlspecialchars($data['totalOrders'] ?? 0) ?></h2>
                                    </div>
                                    <i class="ti-shopping-cart" style="font-size: 40px; opacity: 0.5;"></i>
                                </div>
                                <a href="<?= URLROOT ?>/admin/Orders" class="card-footer text-white d-flex justify-content-between align-items-center" style="background: rgba(0,0,0,0.1); border-top: none; text-decoration: none;">
                                    <span class="small fw-bold">Xem chi tiết</span>
                                    <i class="ti-arrow-circle-right"></i>
                                </a>
                            </div>
                        </div>
                        <div class="col-md-4 mb-3">
                            <div class="card bg-danger text-white h-100">
                                <div class="card-body d-flex justify-content-between align-items-center">
                                    <div>
                                        <h5 class="card-title text-white mb-2">Người dùng</h5>
                                        <h2 class="fw-bold mb-0"><?= htmlspecialchars($data['totalUsers'] ?? 0) ?></h2>
                                    </div>
                                    <i class="ti-user" style="font-size: 40px; opacity: 0.5;"></i>
                                </div>
                                <a href="<?= URLROOT ?>/admin/Users" class="card-footer text-white d-flex justify-content-between align-items-center" style="background: rgba(0,0,0,0.1); border-top: none; text-decoration: none;">
                                    <span class="small fw-bold">Xem chi tiết</span>
                                    <i class="ti-arrow-circle-right"></i>
                                </a>
                            </div>
                        </div>
                    </div>

                    <!-- Danh sách -->
                    <div class="row mt-4">
                        <!-- Đơn hàng gần đây -->
                        <div class="col-xl-6 col-lg-12 mb-4">
                            <div class="card border-light shadow-sm h-100">
                                <div class="card-body">
                                    <div class="d-flex justify-content-between align-items-center mb-3">
                                        <h4 class="header-title mb-0">Đơn hàng gần đây</h4>
                                        <a href="<?= URLROOT ?>/admin/Orders" class="btn btn-sm btn-outline-success">Xem tất cả</a>
                                    </div>
                                    <div class="table-responsive">
                                        <table class="text-center table table-bordered table-hover">
                                            <thead class="bg-light text-capitalize">
                                                <tr>
                                                    <th>Mã ĐH</th>
                                                    <th>Khách hàng</th>
                                                    <th>Trạng thái</th>
                                                    <th>Thao tác</th>
                                                </tr>
                                            </thead>
                                            <tbody>
                                                <?php foreach ($data['recentOrders'] ?? [] as $order): ?>
                                                    <?php
                                                    $o_id = is_object($order) ? $order->orderId : $order['orderId'];
                                                    $o_user = is_object($order) ? $order->fullname : $order['fullname'];
                                                    $o_status = is_object($order) ? $order->status : $order['status'];

                                                    $statusBadges = [
                                                        'pending' => 'bg-warning text-dark', 'processing' => 'bg-info text-white',
                                                        'shipping' => 'bg-primary text-white', 'delivered' => 'bg-success text-white', 'cancelled' => 'bg-danger text-white'
                                                    ];
                                                    $statusLabels = [
                                                        'pending' => 'Chờ xác nhận', 'processing' => 'Đang xử lý', 'shipping' => 'Đang giao',
                                                        'delivered' => 'Hoàn tất', 'cancelled' => 'Đã hủy'
                                                    ];
                                                    ?>
                                                    <tr>
                                                        <td class="fw-bold">#<?= htmlspecialchars($o_id) ?></td>
                                                        <td><?= htmlspecialchars($o_user) ?></td>
                                                        <td><span class="badge badge-pill <?= $statusBadges[$o_status] ?? 'bg-secondary text-white' ?>"><?= $statusLabels[$o_status] ?? 'Không rõ' ?></span></td>
                                                        <td><a href="<?= URLROOT ?>/admin/Orders/detail/<?= $o_id ?>" class="text-primary"><i class="ti-eye"></i> Xem</a></td>
                                                    </tr>
                                                <?php endforeach; ?>
                                                <?php if (empty($data['recentOrders'])): ?>
                                                    <tr><td colspan="4" class="text-muted">Chưa có đơn hàng nào</td></tr>
                                                <?php endif; ?>
                                            </tbody>
                                        </table>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- Sản phẩm mới thêm -->
                        <div class="col-xl-6 col-lg-12 mb-4">
                            <div class="card border-light shadow-sm h-100">
                                <div class="card-body">
                                    <div class="d-flex justify-content-between align-items-center mb-3">
                                        <h4 class="header-title mb-0">Sản phẩm mới thêm</h4>
                                        <a href="<?= URLROOT ?>/admin/Products" class="btn btn-sm btn-outline-primary">Xem tất cả</a>
                                    </div>
                                    <div class="table-responsive">
                                        <table class="text-center table table-bordered table-hover">
                                            <thead class="bg-light text-capitalize">
                                                <tr>
                                                    <th>Mã SKU</th>
                                                    <th>Tên sản phẩm</th>
                                                    <th>Giá (VNĐ)</th>
                                                    <th>Kho</th>
                                                </tr>
                                            </thead>
                                            <tbody>
                                                <?php foreach ($data['recentProducts'] ?? [] as $product): ?>
                                                    <?php
                                                    $p_id = is_object($product) ? ($product->productId ?? '') : ($product['productId'] ?? '');
                                                    $p_sku = is_object($product) ? ($product->sku ?? '') : ($product['sku'] ?? '');
                                                    $p_name = is_object($product) ? ($product->name ?? '') : ($product['name'] ?? '');
                                                    $p_price = is_object($product) ? ($product->price ?? 0) : ($product['price'] ?? 0);
                                                    $p_stock = is_object($product) ? ($product->stock_quantity ?? 0) : ($product['stock_quantity'] ?? 0);
                                                    ?>
                                                    <tr>
                                                        <td class="fw-bold"><?= htmlspecialchars($p_sku) ?></td>
                                                        <td><a href="<?= URLROOT ?>/admin/Products/edit/<?= $p_id ?>" class="text-primary fw-bold"><?= htmlspecialchars($p_name) ?></a></td>
                                                        <td class="text-danger fw-bold"><?= number_format($p_price, 0, ',', '.') ?> đ</td>
                                                        <td><?= htmlspecialchars($p_stock) ?></td>
                                                    </tr>
                                                <?php endforeach; ?>
                                                <?php if (empty($data['recentProducts'])): ?>
                                                    <tr><td colspan="4" class="text-muted">Chưa có sản phẩm nào</td></tr>
                                                <?php endif; ?>
                                            </tbody>
                                        </table>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>