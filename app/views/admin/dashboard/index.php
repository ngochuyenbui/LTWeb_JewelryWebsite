<div class="main-content-inner">
    <div class="row">
        <div class="col-12 mt-5">
            <div class="card">
                <div class="card-body">
                    <h4 class="header-title">Tổng quan (Dashboard)</h4>
                    <p>Chào mừng Admin quay trở lại!</p>
                    
                    <!-- Thống kê nhanh sử dụng Srtdash UI -->
                    <div class="row mt-4">
                        <div class="col-md-4 mb-3">
                            <div class="card bg-primary text-white">
                                <div class="card-body">
                                    <h5 class="card-title text-white">Tổng sản phẩm</h5>
                                    <h2 class="font-weight-bold mt-2"><?= htmlspecialchars($data['totalProducts'] ?? 0) ?></h2>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-4 mb-3">
                            <div class="card bg-success text-white">
                                <div class="card-body">
                                    <h5 class="card-title text-white">Đơn hàng mới</h5>
                                    <h2 class="font-weight-bold mt-2"><?= htmlspecialchars($data['totalOrders'] ?? 0) ?></h2>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-4 mb-3">
                            <div class="card bg-danger text-white">
                                <div class="card-body">
                                    <h5 class="card-title text-white">Người dùng</h5>
                                    <h2 class="font-weight-bold mt-2"><?= htmlspecialchars($data['totalUsers'] ?? 0) ?></h2>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="row mt-4">
                        <div class="col-12">
                            <div class="card border-light shadow-sm">
                                <div class="card-body">
                                    <h4 class="header-title">Sản phẩm mới thêm gần đây</h4>
                                    <div class="data-tables">
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
                                                        <td><?= htmlspecialchars($p_sku) ?></td>
                                                        <td><a href="<?= URLROOT ?>/admin/Products/edit/<?= $p_id ?>" class="text-primary font-weight-bold"><?= htmlspecialchars($p_name) ?></a></td>
                                                        <td><?= number_format($p_price, 0, ',', '.') ?></td>
                                                        <td><?= htmlspecialchars($p_stock) ?></td>
                                                    </tr>
                                                <?php endforeach; ?>
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