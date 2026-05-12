<div class="main-content-inner">
    <div class="row">
        <div class="col-12 mt-5">
            <div class="card">
                <div class="card-body">
                    <h4 class="header-title text-primary">Quản lý sản phẩm</h4>

                    <?php if (isset($_SESSION['success'])): ?>
                        <div class="alert alert-success alert-dismissible fade show" role="alert">
                            <strong>Thành công!</strong> <?= htmlspecialchars($_SESSION['success']) ?>
                            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                        </div>
                        <?php unset($_SESSION['success']); ?>
                    <?php endif; ?>

                    <!-- Bộ lọc và tìm kiếm -->
                    <form method="GET" action="<?= URLROOT ?>/admin/Products" class="mb-4 bg-light p-3 rounded border">
                        <div class="mb-3">
                            <label class="fw-bold">Tìm kiếm</label>
                            <input type="text" name="search" class="form-control" placeholder="Tên hoặc SKU..." value="<?= htmlspecialchars($data['filters']['search'] ?? '') ?>">
                        </div>
                    <div class="row align-items-end">

                            <div class="col-md-2 mb-3">
                                <label class="fw-bold">Danh mục</label>
                                <select name="category" class="form-control" style="height: auto;">
                                    <option value="">-- Tất cả --</option>
                                    <?php foreach($data['categories'] ?? [] as $c): ?>
                                        <option value="<?= is_object($c) ? $c->cateId : $c['cateId'] ?>" <?= (($data['filters']['category'] ?? '') == (is_object($c) ? $c->cateId : $c['cateId'])) ? 'selected' : '' ?>><?= htmlspecialchars(is_object($c) ? $c->name : $c['name']) ?></option>
                                    <?php endforeach; ?>
                                </select>
                            </div>
                            <div class="col-md-2 mb-3">
                                <label class="fw-bold">Màu sắc</label>
                                <select name="color" class="form-control" style="height: auto;">
                                    <option value="">-- Tất cả --</option>
                                    <?php
                                    $allColors = [];
                                    foreach($data['colors'] ?? [] as $c) {
                                        $colorVal = is_object($c) ? $c->color : $c['color'];
                                        if (!empty($colorVal) && !in_array($colorVal, $allColors)) {
                                            $allColors[] = $colorVal;
                                        }
                                    }
                                    foreach($allColors as $cVal): ?>
                                        <option value="<?= htmlspecialchars($cVal) ?>" <?= (in_array($cVal, $data['filters']['color'] ?? [])) ? 'selected' : '' ?>><?= htmlspecialchars($cVal) ?></option>
                                    <?php endforeach; ?>
                                </select>
                            </div>
                            <div class="col-md-2 mb-3">
                                <label class="fw-bold">Kích cỡ</label>
                                <select name="size" class="form-control" style="height: auto;">
                                    <option value="">-- Tất cả --</option>
                                    <?php
                                    $allSizes = [];
                                    foreach($data['sizes'] ?? [] as $s) {
                                        $sizeVal = is_object($s) ? $s->size : $s['size'];
                                        $parts = array_map('trim', explode(',', $sizeVal));
                                        foreach ($parts as $p) {
                                            if (!empty($p) && !in_array($p, $allSizes)) {
                                                $allSizes[] = $p;
                                            }
                                        }
                                    }
                                    sort($allSizes);
                                    foreach($allSizes as $sVal): ?>
                                        <option value="<?= htmlspecialchars($sVal) ?>" <?= (in_array($sVal, $data['filters']['size'] ?? [])) ? 'selected' : '' ?>><?= htmlspecialchars($sVal) ?></option>
                                    <?php endforeach; ?>
                                </select>
                            </div>
                            <div class="col-md-3 mb-3">
                                <label class="fw-bold">Sắp xếp</label>
                                <select name="sort" class="form-control" style="height: auto;">
                                    <option value="default" <?= (($data['filters']['sort'] ?? '') == 'default') ? 'selected' : '' ?>>Mới nhất</option>
                                    <option value="price_asc" <?= (($data['filters']['sort'] ?? '') == 'price_asc') ? 'selected' : '' ?>>Giá tăng dần</option>
                                    <option value="price_desc" <?= (($data['filters']['sort'] ?? '') == 'price_desc') ? 'selected' : '' ?>>Giá giảm dần</option>
                                </select>
                            </div>
                        </div>
                        <div class="row align-items-center mt-2">
                            <div class="col-md-6 mb-3">
                                <label class="fw-bold">Khoảng giá</label>
                                <input type="hidden" name="min_price" id="filter_min_price" value="<?= htmlspecialchars($data['filters']['min_price'] ?? '') ?>">
                                <input type="hidden" name="max_price" id="filter_max_price" value="<?= htmlspecialchars($data['filters']['max_price'] ?? '') ?>">
                                <input type="text" id="price_slider" value="" />
                            </div>
                            <div class="col-md-6 mb-3 text-end">
                                <button type="submit" class="btn btn-secondary"><i class="ti-filter"></i> Lọc & Tìm kiếm</button>
                                <a href="<?= URLROOT ?>/admin/Products" class="btn btn-light ms-2"><i class="ti-reload"></i> Xóa lọc</a>
                            </div>
                        </div>
                    </form>

                    <div class="d-flex justify-content-between align-items-center mb-3">
                        <div>
                            <button type="button" class="btn btn-danger" id="btnMassDelete">
                                <i class="ti-trash"></i> Xóa đã chọn
                            </button>
                            <a href="<?= URLROOT ?>/admin/Products/create" class="btn btn-primary ms-2">
                                <i class="ti-plus"></i> Thêm sản phẩm mới
                            </a>
                        </div>
                        <span class="text-muted">Đang hiển thị: <?= count($data['products'] ?? []) ?> / <?= $data['totalItems'] ?? 0 ?> sản phẩm</span>
                    </div>

                    <form id="massDeleteForm" action="<?= URLROOT ?>/admin/Products/deleteMultiple" method="POST" class="d-none">
                        <input type="hidden" name="ids" id="massDeleteIds">
                    </form>

                    <div class="data-tables">
                        <table id="dataTable" class="text-center table table-bordered table-hover">
                            <thead class="bg-light text-capitalize">
                                <tr>
                                    <th style="width: 5%"><input type="checkbox" id="selectAll"></th>
                                    <th>Mã SKU</th>
                                    <th>Tên sản phẩm</th>
                                    <th>Giá (VNĐ)</th>
                                    <th>Kho</th>
                                    <th>Hành động</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php foreach ($data['products'] ?? [] as $product): ?>
                                    <?php
                                    $p_id = is_object($product) ? ($product->productId ?? '') : ($product['productId'] ?? '');
                                    $p_sku = is_object($product) ? ($product->sku ?? '') : ($product['sku'] ?? '');
                                    $p_name = is_object($product) ? ($product->name ?? '') : ($product['name'] ?? '');
                                    $p_price = is_object($product) ? ($product->price ?? 0) : ($product['price'] ?? 0);
                                    $p_stock = is_object($product) ? ($product->stock_quantity ?? 0) : ($product['stock_quantity'] ?? 0);
                                    ?>
                                    <tr>
                                        <td><input type="checkbox" class="checkItem" value="<?= $p_id ?>"></td>
                                        <td><?= htmlspecialchars($p_sku) ?></td>
                                        <td><?= htmlspecialchars($p_name) ?></td>
                                        <td><?= number_format($p_price, 0, ',', '.') ?></td>
                                        <td><?= htmlspecialchars($p_stock) ?></td>
                                        <td>
                                            <a href="<?= URLROOT ?>/admin/Products/edit/<?= $p_id ?>" class="btn btn-success btn-sm text-white"><i class="ti-pencil"></i> Sửa</a>
                                            <form action="<?= URLROOT ?>/admin/Products/outOfStock/<?= $p_id ?>" method="POST" class="d-inline ajax-action-form" data-confirm="Bạn muốn đánh dấu sản phẩm này là Hết hàng?">
                                                <button type="submit" class="btn btn-secondary btn-sm text-white" <?= $p_stock <= 0 ? 'disabled' : '' ?>><i class="ti-na"></i> Hết hàng</button>
                                            </form>
                                            <form action="<?= URLROOT ?>/admin/Products/delete/<?= $p_id ?>" method="POST" class="d-inline ajax-action-form" data-confirm="Bạn có chắc chắn muốn Xóa (Ẩn) sản phẩm này không? Dữ liệu lịch sử đơn hàng cũ vẫn sẽ được giữ lại.">
                                                <button type="submit" class="btn btn-danger btn-sm text-white"><i class="ti-trash"></i> Xóa</button>
                                            </form>
                                        </td>
                                    </tr>
                                <?php endforeach; ?>
                            </tbody>
                        </table>
                    </div>

                    <!-- Phân trang -->
                    <?php if (($data['totalPages'] ?? 0) > 1): ?>
                    <div class="mt-4 d-flex justify-content-center">
                        <nav aria-label="Page navigation">
                            <ul class="pagination">
                                <?php
                                    $currentPage = $data['currentPage'] ?? 1;
                                    $totalPages = $data['totalPages'] ?? 1;

                                    // Build query string for pagination links
                                    $query = $_GET;
                                    unset($query['page'], $query['url']);
                                    $queryString = http_build_query($query);
                                    $baseUrl = URLROOT . '/admin/Products?';
                                ?>
                                <li class="page-item <?= $currentPage <= 1 ? 'disabled' : '' ?>">
                                    <a class="page-link" href="<?= $baseUrl . $queryString . '&page=' . ($currentPage - 1) ?>" aria-label="Previous">
                                        <span aria-hidden="true"><</span>
                                    </a>
                                </li>
                                <?php for ($i = 1; $i <= $totalPages; $i++): ?>
                                    <li class="page-item <?= $i == $currentPage ? 'active' : '' ?>">
                                        <a class="page-link" href="<?= $baseUrl . $queryString . '&page=' . $i ?>"><?= $i ?></a>
                                    </li>
                                <?php endfor; ?>
                                <li class="page-item <?= $currentPage >= $totalPages ? 'disabled' : '' ?>">
                                    <a class="page-link" href="<?= $baseUrl . $queryString . '&page=' . ($currentPage + 1) ?>" aria-label="Next">
                                        <span aria-hidden="true">></span>
                                    </a>
                                </li>
                            </ul>
                        </nav>
                    </div>
                    <?php endif; ?>
                </div>
            </div>
        </div>
    </div>
</div>

<script>
document.addEventListener("DOMContentLoaded", function() {
    // Logic Xóa nhiều sản phẩm
    const selectAll = document.getElementById('selectAll');
    const checkItems = document.querySelectorAll('.checkItem');
    const btnMassDelete = document.getElementById('btnMassDelete');

    if (selectAll) {
        selectAll.addEventListener('change', function() {
            checkItems.forEach(cb => cb.checked = this.checked);
        });
    }

    if (btnMassDelete) {
        btnMassDelete.addEventListener('click', function() {
            let checked = document.querySelectorAll('.checkItem:checked');
            if (checked.length === 0) {
                Swal.fire('Thông báo', 'Vui lòng chọn ít nhất một sản phẩm để xóa.', 'warning');
                return;
            }

            Swal.fire({
                title: 'Xác nhận xóa',
                text: 'Bạn có chắc chắn muốn xóa ' + checked.length + ' sản phẩm đã chọn không?',
                icon: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#3085d6',
                cancelButtonColor: '#d33',
                confirmButtonText: 'Đồng ý',
                cancelButtonText: 'Hủy'
            }).then((result) => {
                if (result.isConfirmed) {
                    let ids = Array.from(checked).map(cb => cb.value).join(',');

                    $.ajax({
                        url: document.getElementById('massDeleteForm').action,
                        type: 'POST',
                        data: { ids: ids },
                        dataType: 'json',
                        success: function(res) {
                            if (res.success) {
                                Swal.fire('Thành công!', res.message, 'success');
                                // Xóa các dòng đã chọn ra khỏi bảng
                                checked.forEach(cb => cb.closest('tr').remove());
                            } else {
                                Swal.fire('Lỗi!', res.message, 'error');
                            }
                        },
                        error: function() {
                            Swal.fire('Lỗi!', 'Có lỗi xảy ra, vui lòng thử lại.', 'error');
                        }
                    });
                }
            });
        });
    }

    // Xử lý Ajax cho các form Sửa/Xóa từng sản phẩm
    $('.ajax-action-form').on('submit', function(e) {
        e.preventDefault();
        let form = $(this);
        let confirmMsg = form.attr('data-confirm');

        Swal.fire({
            title: 'Xác nhận',
            text: confirmMsg,
            icon: 'warning',
            showCancelButton: true,
            confirmButtonColor: '#d33',
            cancelButtonColor: '#3085d6',
            confirmButtonText: 'Đồng ý',
            cancelButtonText: 'Hủy'
        }).then((result) => {
            if (result.isConfirmed) {
                $.ajax({
                    url: form.attr('action'),
                    type: 'POST',
                    dataType: 'json',
                    success: function(res) {
                        if (res.success) {
                            Swal.fire('Thành công!', res.message, 'success');
                            if (form.attr('action').includes('delete')) {
                                form.closest('tr').remove();
                            } else if (form.attr('action').includes('outOfStock')) {
                                let tr = form.closest('tr');
                                tr.find('td:eq(4)').text('0'); // Chuyển cột Kho thành số 0
                                form.find('button').prop('disabled', true); // Vô hiệu hóa nút
                            }
                        } else {
                            Swal.fire('Lỗi!', res.message, 'error');
                        }
                    },
                    error: function() {
                        Swal.fire('Lỗi!', 'Có lỗi xảy ra, vui lòng thử lại.', 'error');
                    }
                });
            }
        });
    });

    // Đợi jQuery và IonRangeSlider sẵn sàng
    let checkJq = setInterval(function() {
        if (window.jQuery && $.fn.ionRangeSlider) {
            clearInterval(checkJq);

            let dbMin = <?= $data['priceRange']['min_price'] ?? 0 ?>;
            let dbMax = <?= $data['priceRange']['max_price'] ?? 50000000 ?>;

            if (dbMin >= dbMax) {
                dbMax = dbMin + 10000; // Tránh lỗi hiển thị khi kho chỉ có duy nhất 1 mức giá
            }

            let currentMin = <?= (isset($data['filters']['min_price']) && $data['filters']['min_price'] !== '') ? (int)$data['filters']['min_price'] : 'dbMin' ?>;
            let currentMax = <?= (isset($data['filters']['max_price']) && $data['filters']['max_price'] !== '') ? (int)$data['filters']['max_price'] : 'dbMax' ?>;

            $("#price_slider").ionRangeSlider({
                type: "double",
                min: dbMin,
                max: dbMax,
                from: currentMin,
                to: currentMax,
                step: 10000,
                prettify_enabled: true,
                prettify_separator: ".",
                postfix: "đ",
                onChange: function (data) {
                    $("#filter_min_price").val(data.from);
                    $("#filter_max_price").val(data.to);
                }
            });
        }
    }, 100);
});
</script>