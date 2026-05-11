<?php
$order = $data['order'] ?? [];
$items = $data['items'] ?? [];
$statusOptions = ['pending', 'processing', 'shipping', 'delivered', 'cancelled'];
$statusLabels = ['pending' => 'Chờ xác nhận', 'processing' => 'Đang xử lý', 'shipping' => 'Đang giao', 'delivered' => 'Hoàn tất', 'cancelled' => 'Đã hủy'];
?>
<div class="main-content-inner">
    <div class="row">
        <div class="col-12 mt-5">
            <div class="card">
                <div class="card-body">
                    <div class="d-flex justify-content-between align-items-center">
                        <h4 class="header-title text-primary">Chi tiết Đơn hàng #<?= htmlspecialchars($order['orderId']) ?></h4>
                        <a href="<?= URLROOT ?>/admin/Orders" class="btn btn-secondary"><i class="ti-back-left"></i> Quay lại</a>
                    </div>
                    <hr>

                    <?php if (isset($_SESSION['success'])): ?>
                        <div class="alert alert-success"><strong>Thành công!</strong> <?= htmlspecialchars($_SESSION['success']); unset($_SESSION['success']); ?></div>
                    <?php endif; ?>
                    <?php if (isset($_SESSION['error'])): ?>
                        <div class="alert alert-danger"><strong>Lỗi!</strong> <?= htmlspecialchars($_SESSION['error']); unset($_SESSION['error']); ?></div>
                    <?php endif; ?>
                        <!-- Cập nhật trạng thái -->
                    <div class="row align-items-end">
                        <div class="col-md-6">
                            <h5 class="mb-3">Cập nhật trạng thái đơn hàng</h5>
                            <form action="<?= URLROOT ?>/admin/Orders/updateStatus/<?= $order['orderId'] ?>" method="POST">
                                <div class="mb-3">
                                    <?php $isFinalStatus = in_array($order['status'], ['delivered', 'cancelled']); ?>
                                    <select name="status" class="form-control" style="height: auto;" <?= $isFinalStatus ? 'disabled' : '' ?>>
                                        <?php foreach ($statusOptions as $status): ?>
                                            <option value="<?= $status ?>" <?= ($order['status'] == $status) ? 'selected' : '' ?>>
                                                <?= $statusLabels[$status] ?>
                                            </option>
                                        <?php endforeach; ?>
                                    </select>
                                </div>
                                <?php if (!$isFinalStatus): ?>
                                    <button type="submit" class="btn btn-success"><i class="ti-save"></i> Cập nhật</button>
                                <?php else: ?>
                                    <div class="text-danger mt-2"><small><i class="ti-lock"></i> Đơn hàng đã chốt trạng thái, không thể thay đổi.</small></div>
                                <?php endif; ?>
                            </form>
                        </div>
                    </div>
                    <hr>
                    <div class="row">
                        <!-- Thông tin khách hàng -->
                        <div class="col-md-6">
                            <h5>Thông tin khách hàng</h5>
                            <p><strong>Họ tên:</strong> <?= htmlspecialchars($order['fullname']) ?></p>
                            <p><strong>Tài khoản:</strong> <?= htmlspecialchars($order['username']) ?></p>
                            <p><strong>Email:</strong> <?= htmlspecialchars($order['email']) ?></p>
                            <p><strong>Số điện thoại:</strong> <?= htmlspecialchars($order['phone']) ?></p>
                        </div>
                        <!-- Thông tin giao hàng -->
                        <div class="col-md-6">
                            <h5>Thông tin giao hàng & Thanh toán</h5>
                            <p><strong>Địa chỉ:</strong> <?= htmlspecialchars($order['shipping_addr']) ?></p>
                            <p><strong>Ngày đặt:</strong> <?= date('d/m/Y H:i', strtotime($order['created_at'])) ?></p>
                            <p><strong>Phương thức thanh toán:</strong> <?= htmlspecialchars($order['payment']) ?></p>
                        </div>
                    </div>

                    <hr>

                    <!-- Danh sách sản phẩm -->
                    <h5>Danh sách sản phẩm</h5>
                    <div class="table-responsive">
                        <table class="table table-bordered">
                            <thead class="thead-light">
                                <tr>
                                
                                    <th>Sản phẩm</th>
                                    <th class="text-center">Size</th>
                                    <th class="text-right">Đơn giá</th>
                                    <th class="text-center">Số lượng</th>
                                    <th class="text-right">Thành tiền</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php foreach ($items as $item): 
                                    $i_name = is_object($item) ? $item->name : $item['name'];
                                    $i_img = is_object($item) ? $item->image_url : $item['image_url'];
                                    $i_size = is_object($item) ? $item->size : $item['size'];
                                    $i_price = is_object($item) ? $item->purchase_price : $item['purchase_price'];
                                    $i_qty = is_object($item) ? $item->quantity : $item['quantity'];
                                ?>
                                <tr>
                                    <td>
                                        <div class="d-flex align-items-center">
                                            <img src="<?= htmlspecialchars(strpos($i_img, 'http') === 0 ? $i_img : URLROOT . $i_img) ?>" style="width: 50px; height: 50px; object-fit: cover;" class="mr-3 rounded">
                                            <span><?= htmlspecialchars($i_name) ?></span>
                                        </div>
                                    </td>
                                    <td class="text-center"><?= htmlspecialchars($i_size) ?></td>
                                    <td class="text-right"><?= number_format($i_price, 0, ',', '.') ?> đ</td>
                                    <td class="text-center"><?= htmlspecialchars($i_qty) ?></td>
                                    <td class="text-right font-weight-bold"><?= number_format($i_price * $i_qty, 0, ',', '.') ?> đ</td>
                                </tr>
                                <?php endforeach; ?>
                            </tbody>
                            <tfoot>
                                <tr>
                                    <td colspan="4" class="text-right">Tạm tính</td>
                                    <td class="text-right"><?= number_format($data['totalPrice'] ?? 0, 0, ',', '.') ?> đ</td>
                                </tr>
                                <tr>
                                    <td colspan="4" class="text-right">Phí vận chuyển</td>
                                    <td class="text-right"><?= number_format($data['shippingFee'] ?? 0, 0, ',', '.') ?> đ</td>
                                </tr>
                                <tr class="font-weight-bold">
                                    <td colspan="4" class="text-right">Tổng cộng</td>
                                    <td class="text-right h5 text-danger"><?= number_format($data['finalTotal'] ?? 0, 0, ',', '.') ?> đ</td>
                                </tr>
                            </tfoot>
                        </table>
                    </div>


                    

                </div>
            </div>
        </div>
    </div>
</div>