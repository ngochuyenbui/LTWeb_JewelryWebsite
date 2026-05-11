<div class="main-content-inner">
    <div class="row">
        <div class="col-12 mt-5">
            <div class="card">
                <div class="card-body">
                    <h4 class="header-title text-primary">Quản lý Đơn hàng</h4>
                    
                    <?php if (isset($_SESSION['success'])): ?>
                        <div class="alert alert-success alert-dismissible fade show" role="alert">
                            <strong>Thành công!</strong> <?= htmlspecialchars($_SESSION['success']) ?>
                            <button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                        </div>
                        <?php unset($_SESSION['success']); ?>
                    <?php endif; ?>
                    
                    <!-- Form Lọc và Sắp xếp -->
                    <form method="GET" action="<?= URLROOT ?>/admin/Orders" class="mb-4 bg-light p-3 rounded border">
                        <div class="form-row align-items-end">
                            <div class="col-md-3">
                                <label class="font-weight-bold">Trạng thái</label>
                                <select name="status" class="form-control" style="height: auto;">
                                    <option value="">-- Tất cả --</option>
                                    <option value="pending" <?= (($data['filters']['status'] ?? '') == 'pending') ? 'selected' : '' ?>>Chờ xác nhận</option>
                                    <option value="processing" <?= (($data['filters']['status'] ?? '') == 'processing') ? 'selected' : '' ?>>Đang xử lý</option>
                                    <option value="shipping" <?= (($data['filters']['status'] ?? '') == 'shipping') ? 'selected' : '' ?>>Đang giao</option>
                                    <option value="delivered" <?= (($data['filters']['status'] ?? '') == 'delivered') ? 'selected' : '' ?>>Hoàn tất</option>
                                    <option value="cancelled" <?= (($data['filters']['status'] ?? '') == 'cancelled') ? 'selected' : '' ?>>Đã hủy</option>
                                </select>
                            </div>
                            <div class="col-md-3">
                                <label class="font-weight-bold">Phương thức thanh toán</label>
                                <select name="payment" class="form-control" style="height: auto;">
                                    <option value="">-- Tất cả --</option>
                                    <?php 
                                    $paymentMethods = ['COD', 'BANK']; 
                                    foreach ($paymentMethods as $pm): ?>
                                        <option value="<?= $pm ?>" <?= (($data['filters']['payment'] ?? '') == $pm) ? 'selected' : '' ?>><?= $pm ?></option>
                                    <?php endforeach; ?>
                                </select>
                            </div>
                            <div class="col-md-4">
                                <label class="font-weight-bold">Sắp xếp theo</label>
                                <select name="sort_by" class="form-control" style="height: auto;">
                                    <option value="date_desc" <?= (($data['filters']['sort_by'] ?? '') == 'date_desc') ? 'selected' : '' ?>>Ngày đặt (Mới nhất)</option>
                                    <option value="date_asc" <?= (($data['filters']['sort_by'] ?? '') == 'date_asc') ? 'selected' : '' ?>>Ngày đặt (Cũ nhất)</option>
                                    <option value="total_desc" <?= (($data['filters']['sort_by'] ?? '') == 'total_desc') ? 'selected' : '' ?>>Tổng tiền (Cao - Thấp)</option>
                                    <option value="total_asc" <?= (($data['filters']['sort_by'] ?? '') == 'total_asc') ? 'selected' : '' ?>>Tổng tiền (Thấp - Cao)</option>
                                </select>
                            </div>
                            <div class="col-md-2 text-right">
                                <button type="submit" class="btn btn-secondary"><i class="ti-filter"></i> Lọc & Tìm kiếm</button>
                            </div>
                        </div>
                    </form>

                    <div class="data-tables">
                        <table class="text-center table table-bordered table-hover">
                            <thead class="bg-light text-capitalize">
                                <tr>
                                    <th>Mã ĐH</th>
                                    <th>Khách hàng</th>
                                    <th>Ngày đặt</th>
                                    <th>Tổng tiền</th>
                                    <th>Thanh toán</th>
                                    <th>Trạng thái</th>
                                    <th>Hành động</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php foreach ($data['orders'] ?? [] as $order): ?>
                                    <?php
                                        $o_id = is_object($order) ? $order->orderId : $order['orderId'];
                                        $o_user = is_object($order) ? $order->fullname : $order['fullname'];
                                        $o_date = is_object($order) ? $order->created_at : $order['created_at'];
                                        $o_total = is_object($order) ? $order->totalAmount : $order['totalAmount'];
                                        $o_payment = is_object($order) ? $order->payment : $order['payment'];
                                        $o_status = is_object($order) ? $order->status : $order['status'];

                                        $statusBadges = [
                                            'pending' => 'badge-warning', 'processing' => 'badge-info',
                                            'shipping' => 'badge-primary', 'delivered' => 'badge-success', 'cancelled' => 'badge-danger'
                                        ];
                                        $statusLabels = [
                                            'pending' => 'Chờ xác nhận', 'processing' => 'Đang xử lý', 'shipping' => 'Đang giao',
                                            'delivered' => 'Hoàn tất', 'cancelled' => 'Đã hủy'
                                        ];
                                    ?>
                                    <tr>
                                        <td><strong>#<?= htmlspecialchars($o_id) ?></strong></td>
                                        <td><?= htmlspecialchars($o_user) ?></td>
                                        <td><?= date('d/m/Y', strtotime($o_date)) ?></td>
                                        <td class="font-weight-bold text-danger"><?= number_format($o_total, 0, ',', '.') ?> đ</td>
                                        <td><?= htmlspecialchars($o_payment) ?></td>
                                        <td><span class="badge <?= $statusBadges[$o_status] ?? 'badge-secondary' ?>"><?= $statusLabels[$o_status] ?? 'Không rõ' ?></span></td>
                                        <td>
                                            <a href="<?= URLROOT ?>/admin/Orders/detail/<?= $o_id ?>" class="btn btn-info btn-sm text-white"><i class="ti-eye"></i> Xem</a>
                                        </td>
                                    </tr>
                                <?php endforeach; ?>
                            </tbody>
                        </table>
                    </div>

                    <!-- Phân trang -->
                    <?php 
                    if (($data['totalPages'] ?? 0) > 1): 
                        $f_status = urlencode($data['filters']['status'] ?? '');
                        $f_payment = urlencode($data['filters']['payment'] ?? '');
                        $f_sort = urlencode($data['filters']['sort_by'] ?? '');
                        $buildLink = fn($p) => "?status={$f_status}&payment={$f_payment}&sort_by={$f_sort}&page={$p}";
                    ?>
                    <nav aria-label="Page navigation" class="mt-4">
                        <ul class="pagination justify-content-center">
                            <li class="page-item <?= (($data['currentPage'] ?? 1) <= 1) ? 'disabled' : '' ?>">
                                <a class="page-link" href="<?= $buildLink(max(1, ($data['currentPage'] ?? 1) - 1)) ?>">Trước</a>
                            </li>
                            <?php for ($i = 1; $i <= ($data['totalPages'] ?? 1); $i++): ?>
                                <li class="page-item <?= (($data['currentPage'] ?? 1) == $i) ? 'active' : '' ?>">
                                    <a class="page-link" href="<?= $buildLink($i) ?>"><?= $i ?></a>
                                </li>
                            <?php endfor; ?>
                            <li class="page-item <?= (($data['currentPage'] ?? 1) >= ($data['totalPages'] ?? 1)) ? 'disabled' : '' ?>">
                                <a class="page-link" href="<?= $buildLink(min(($data['totalPages'] ?? 1), ($data['currentPage'] ?? 1) + 1)) ?>">Sau</a>
                            </li>
                        </ul>
                    </nav>
                    <?php endif; ?>
                </div>
            </div>
        </div>
    </div>
</div>