<?php
$p = $data['product'] ?? [];
// Xử lý dữ liệu phòng trường hợp là mảng hay object
$p_id = is_object($p) ? ($p->productId ?? '') : ($p['productId'] ?? '');
$p_sku = is_object($p) ? ($p->sku ?? '') : ($p['sku'] ?? '');
$p_name = is_object($p) ? ($p->name ?? '') : ($p['name'] ?? '');
$p_cateId = is_object($p) ? ($p->cateId ?? '') : ($p['cateId'] ?? '');
$p_price = is_object($p) ? ($p->price ?? 0) : ($p['price'] ?? 0);
$p_stock = is_object($p) ? ($p->stock_quantity ?? 0) : ($p['stock_quantity'] ?? 0);
$p_color = is_object($p) ? ($p->color ?? '') : ($p['color'] ?? '');
$p_size = is_object($p) ? ($p->size ?? '') : ($p['size'] ?? '');
$p_material = is_object($p) ? ($p->material ?? '') : ($p['material'] ?? '');
$p_desc = is_object($p) ? ($p->description ?? '') : ($p['description'] ?? '');
$p_size_dim = is_object($p) ? ($p->size_dim ?? '') : ($p['size_dim'] ?? '');
$p_usage = is_object($p) ? ($p->usage_info ?? '') : ($p['usage_info'] ?? '');

$stdColors = ['Vàng', 'Bạc', 'Bạch kim', 'Vàng hồng', 'Trắng', 'Đen'];
$isCustomColor = !empty($p_color) && !in_array($p_color, $stdColors);

$p_sizes_arr = array_filter(array_map('trim', explode(',', $p_size)));
$stdSizes = ['S', 'M', 'L', 'ONESIZE'];
$customSizes = array_diff($p_sizes_arr, $stdSizes);
$customSizeStr = implode(', ', $customSizes);
?>
<div class="main-content-inner">
    <div class="row">
        <div class="col-12 mt-5">
            <div class="card">
                <div class="card-body">
                    <h4 class="header-title text-primary">Chỉnh sửa Sản Phẩm</h4>
                    <?php if (!empty($data['error'])): ?>
                        <div class="alert alert-danger font-weight-bold"><?= htmlspecialchars($data['error']) ?></div>
                    <?php endif; ?>
                    <form action="<?= URLROOT ?>/admin/Products/edit/<?= htmlspecialchars($p_id) ?>" method="POST" enctype="multipart/form-data">
                        <div class="form-row">
                            <div class="col-md-6 mb-3">
                                <label for="name" class="font-weight-bold">Tên sản phẩm <span class="text-danger">(*)</span></label>
                                <input type="text" class="form-control" id="name" name="name" value="<?= htmlspecialchars($p_name) ?>" required>
                            </div>
                            <div class="col-md-6 mb-3">
                                <label for="sku" class="font-weight-bold">Mã SKU <span class="text-danger">(*)</span></label>
                                <input type="text" class="form-control" id="sku" name="sku" value="<?= htmlspecialchars($p_sku) ?>" required>
                            </div>
                        </div>
                        
                        <div class="form-row">
                            <div class="col-md-4 mb-3">
                                <label for="cateId" class="font-weight-bold">Danh mục <span class="text-danger">(*)</span></label>
                                <select class="form-control" id="cateId" name="cateId" required onchange="toggleNewCategory()" style="height: auto;">
                                    <?php foreach ($data['categories'] ?? [] as $cate): ?>
                                        <?php $c_id = is_object($cate) ? $cate->cateId : $cate['cateId']; ?>
                                        <option value="<?= $c_id ?>" <?= $c_id == $p_cateId ? 'selected' : '' ?>>
                                            <?= htmlspecialchars(is_object($cate) ? $cate->name : $cate['name']) ?>
                                        </option>
                                    <?php endforeach; ?>
                                    <option value="new" class="font-weight-bold text-primary">+ Khác (Thêm danh mục mới)</option>
                                </select>
                                <input type="text" class="form-control mt-2 d-none" id="new_category_name" name="new_category_name" placeholder="Nhập tên danh mục mới">
                            </div>
                            <div class="col-md-4 mb-3">
                                <label for="price" class="font-weight-bold">Giá (VNĐ) <span class="text-danger">(*)</span></label>
                                <input type="text" class="form-control" id="price" name="price" value="<?= number_format($p_price, 0, '', '.') ?>" required oninput="formatPrice(this)">
                            </div>
                            <div class="col-md-4 mb-3">
                                <label for="stock_quantity" class="font-weight-bold">Số lượng trong kho</label>
                                <input type="number" class="form-control" id="stock_quantity" name="stock_quantity" value="<?= htmlspecialchars($p_stock) ?>" min="0" required>
                            </div>
                        </div>

                        <div class="form-row">
                            <div class="col-md-4 mb-3">
                                <label for="color" class="font-weight-bold">Màu sắc</label>
                                <select class="form-control" id="color_select" name="color_select" onchange="toggleCustomColor()" style="height: auto;">
                                    <option value="" <?= empty($p_color) ? 'selected' : '' ?>>-- Chọn màu --</option>
                                    <?php foreach ($stdColors as $c): ?>
                                        <option value="<?= $c ?>" <?= (!$isCustomColor && $p_color === $c) ? 'selected' : '' ?>><?= $c ?></option>
                                    <?php endforeach; ?>
                                    <option value="other" class="font-weight-bold text-primary" <?= $isCustomColor ? 'selected' : '' ?>>+ Khác...</option>
                                </select>
                                <input type="text" class="form-control mt-2 <?= $isCustomColor ? '' : 'd-none' ?>" id="color_custom" name="color_custom" value="<?= $isCustomColor ? htmlspecialchars($p_color) : '' ?>" placeholder="Nhập màu sắc khác" <?= $isCustomColor ? 'required' : '' ?>>
                            </div>
                            <div class="col-md-4 mb-3">
                                <label for="size" class="font-weight-bold">Kích cỡ (Size)</label>
                                <div class="mb-2">
                                    <?php foreach ($stdSizes as $s): ?>
                                    <div class="custom-control custom-checkbox custom-control-inline">
                                        <input type="checkbox" class="custom-control-input" id="size_<?= $s ?>" name="sizes[]" value="<?= $s ?>" <?= in_array($s, $p_sizes_arr) ? 'checked' : '' ?>>
                                        <label class="custom-control-label" for="size_<?= $s ?>"><?= $s ?></label>
                                    </div>
                                    <?php endforeach; ?>
                                </div>
                                <input type="text" class="form-control" id="size_custom" name="size_custom" value="<?= htmlspecialchars($customSizeStr) ?>" placeholder="Khác (VD: 55, 56)">
                            </div>
                            
                        </div>
                        <div class="form-group">
                                <label for="material" class="font-weight-bold">Chất liệu</label>
                                <input type="text" class="form-control" id="material" name="material" value="<?= htmlspecialchars($p_material) ?>">
                            </div>
                        <div class="form-group">
                                <label for="size_dim" class="font-weight-bold">Kích cỡ chi tiết</label>
                                <input type="text" class="form-control" id="size_dim" name="size_dim" value="<?= htmlspecialchars($p_size_dim) ?>" placeholder="VD: Dây dài 40cm, mặt dây 1cm...">
                        </div>
                        <div class="form-group">                          
                                <label for="usage_info" class="font-weight-bold">Cách bảo quản</label>
                                <input type="text" class="form-control" id="usage_info" name="usage_info" value="<?= htmlspecialchars($p_usage) ?>" placeholder="VD: Tránh tiếp xúc hóa chất...">
                        </div>
                        <div class="form-group">
                            <label for="description" class="font-weight-bold">Mô tả sản phẩm</label>
                            <textarea class="form-control" id="description" name="description" rows="4"><?= htmlspecialchars($p_desc) ?></textarea>
                        </div>

                        <div class="form-group border p-3 bg-light rounded">
                            <label class="font-weight-bold">Hình ảnh hiện tại</label>
                            <div class="d-flex flex-wrap mb-3" style="gap: 10px;">
                                <?php
                                $allImages = [];
                                foreach ($data['images'] ?? [] as $imgObj) {
                                    $imgUrl = is_object($imgObj) ? ($imgObj->image_url ?? $imgObj->image ?? '') : ($imgObj['image_url'] ?? $imgObj['image'] ?? '');
                                    $isPrimary = is_object($imgObj) ? ($imgObj->is_primary ?? 0) : ($imgObj['is_primary'] ?? 0);
                                    if (!empty($imgUrl)) {
                                        $allImages[] = ['url' => $imgUrl, 'is_primary' => $isPrimary];
                                    }
                                }
                                foreach ($allImages as $index => $img):
                                ?>
                                    <div class="position-relative border rounded p-1 bg-white" style="width: 100px; height: 100px;">
                                        <img src="<?= htmlspecialchars((strpos($img['url'], 'http') === 0 ? '' : URLROOT) . $img['url']) ?>" class="w-100 h-100" style="object-fit: contain;">
                                        <div class="position-absolute" style="top: -8px; right: -8px;">
                                            <label class="bg-danger text-white rounded-circle d-flex align-items-center justify-content-center mb-0 shadow" style="width: 24px; height: 24px; cursor: pointer;" title="Đánh dấu xóa ảnh này">
                                                <input type="checkbox" name="delete_images[]" value="<?= htmlspecialchars($img['url']) ?>" class="d-none image-delete-cb">
                                                <i class="ti-close" style="font-size: 10px;"></i>
                                            </label>
                                        </div>
                                        <div class="position-absolute w-100 h-100 delete-overlay d-none" style="top: 0; left: 0; background: rgba(255,0,0,0.5); pointer-events: none; border-radius: 4px;"></div>
                                        <div class="position-absolute w-100 text-center" style="bottom: 0; left: 0; background: rgba(255,255,255,0.8);">
                                            <label class="mb-0 w-100" style="font-size: 11px; cursor:pointer;">
                                                <input type="radio" name="primary_image" value="existing:<?= htmlspecialchars($img['url']) ?>" <?= $img['is_primary'] ? 'checked' : '' ?>> Ảnh chính
                                            </label>
                                        </div>
                                    </div>
                                <?php endforeach; ?>
                            </div>
                            <?php if(!empty($allImages)): ?><small class="text-danger mb-3 d-block"><i class="ti-info-alt"></i> Click vào dấu X màu đỏ để đánh dấu xóa ảnh khi Cập nhật.</small><?php endif; ?>

                            <label class="font-weight-bold">Thêm ảnh mới (Tổng cộng tối đa 5 ảnh)</label>
                            <div class="custom-file mb-3">
                                <input type="file" class="custom-file-input" id="images" multiple accept="image/*" onchange="handleFileSelect(event)">
                                <label class="custom-file-label" for="images">Chọn tệp...</label>
                            </div>
                            <input type="file" id="real-images-input" name="images[]" multiple class="d-none">
                            <div id="new-image-preview-container" class="d-flex flex-wrap" style="gap: 10px;"></div>
                        </div>

                        

                        <button class="btn btn-primary" type="submit"><i class="ti-save"></i> Cập nhật sản phẩm</button>
                        <a href="<?= URLROOT ?>/admin/Products" class="btn btn-secondary"><i class="ti-back-left"></i> Quay lại</a>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>

<script>
    function toggleNewCategory() {
        var cateSelect = document.getElementById('cateId');
        var newCateInput = document.getElementById('new_category_name');
        if (cateSelect.value === 'new') {
            newCateInput.classList.remove('d-none');
            newCateInput.required = true;
        } else {
            newCateInput.classList.add('d-none');
            newCateInput.required = false;
            newCateInput.value = '';
        }
    }

    function toggleCustomColor() {
        var colorSelect = document.getElementById('color_select');
        var customColorInput = document.getElementById('color_custom');
        if (colorSelect.value === 'other') {
            customColorInput.classList.remove('d-none');
            customColorInput.required = true;
        } else {
            customColorInput.classList.add('d-none');
            customColorInput.required = false;
            customColorInput.value = '';
        }
    }

    function formatPrice(input) {
        let val = input.value.replace(/\D/g, '');
        if (val === '') {
            input.value = '';
            return;
        }
        val = parseInt(val, 10).toString();
        input.value = val.replace(/\B(?=(\d{3})+(?!\d))/g, ".");
    }

    let totalExisting = <?= isset($allImages) ? count($allImages) : 0 ?>;
    let deletedUrls = [];
    let dataTransfer = new DataTransfer();

    document.querySelectorAll('.image-delete-cb').forEach(function(cb) {
        cb.addEventListener('change', function() {
            const url = this.value;
            const container = this.closest('.position-relative');
            const overlay = container.querySelector('.delete-overlay');
            const radio = container.querySelector('input[type="radio"]');
            if (this.checked) {
                overlay.classList.remove('d-none');
                deletedUrls.push(url);
                if (radio && radio.checked) {
                    radio.checked = false;
                }
            } else {
                overlay.classList.add('d-none');
                deletedUrls = deletedUrls.filter(u => u !== url);
            }
            updateValidation();
        });
    });

    function handleFileSelect(e) {
        const files = e.target.files;
        if (!files || files.length === 0) return;

        let currentCount = totalExisting - deletedUrls.length + dataTransfer.files.length;
        
        for (let i = 0; i < files.length; i++) {
            if (currentCount >= 5) {
                Swal.fire('Thông báo', 'Tối đa 5 ảnh!', 'warning');
                break;
            }
            dataTransfer.items.add(files[i]);
            currentCount++;
        }
        
        document.getElementById('real-images-input').files = dataTransfer.files;
        document.getElementById('images').value = ''; 
        
        renderPreviews();
    }

    function removeNewFile(fileName) {
        let dt = new DataTransfer();
        for (let i = 0; i < dataTransfer.files.length; i++) {
            if (dataTransfer.files[i].name !== fileName) {
                dt.items.add(dataTransfer.files[i]);
            }
        }
        dataTransfer = dt;
        document.getElementById('real-images-input').files = dataTransfer.files;
        renderPreviews();
    }

    function renderPreviews() {
        const container = document.getElementById('new-image-preview-container');
        if (!container) return;
        container.innerHTML = '';
        
        let hasExistingPrimary = document.querySelector('input[name="primary_image"]:checked') !== null;

        for (let i = 0; i < dataTransfer.files.length; i++) {
            const file = dataTransfer.files[i];
            var reader = new FileReader();
            
            reader.addEventListener("load", function() {
                var div = document.createElement('div');
                div.className = 'position-relative border rounded p-1 bg-white';
                div.style.width = '100px';
                div.style.height = '100px';
                
                let isChecked = (!hasExistingPrimary && i === 0) ? 'checked' : '';

                div.innerHTML = `
                    <img src="${this.result}" class="w-100 h-100" style="object-fit: contain;">
                    <div class="position-absolute" style="top: -8px; right: -8px;">
                        <button type="button" class="bg-danger text-white rounded-circle border-0 d-flex align-items-center justify-content-center shadow" style="width: 24px; height: 24px; cursor: pointer;" data-filename="${file.name.replace(/"/g, '&quot;')}" onclick="removeNewFile(this.getAttribute('data-filename'))">
                            <i class="ti-close" style="font-size: 10px;"></i>
                        </button>
                    </div>
                    <div class="position-absolute w-100 text-center" style="bottom: 0; left: 0; background: rgba(255,255,255,0.9);">
                        <label class="mb-0 w-100" style="font-size: 11px; cursor:pointer;">
                            <input type="radio" name="primary_image" value="new:${file.name}" ${isChecked}> Ảnh chính
                        </label>
                    </div>
                `;
                container.appendChild(div);
            });
            reader.readAsDataURL(file);
        }
        updateValidation();
    }

    function updateValidation() {
        let count = dataTransfer.files.length;
        document.querySelector('.custom-file-label').innerHTML = count > 0 ? count + ' tệp mới' : 'Chọn tệp...';
    }
</script>