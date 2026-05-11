<div class="main-content-inner">
    <div class="row">
        <div class="col-12 mt-5">
            <div class="card">
                <div class="card-body">
                    <h4 class="header-title text-primary">Thêm Sản Phẩm Mới</h4>
                    <?php if (!empty($data['error'])): ?>
                        <div class="alert alert-danger font-weight-bold"><?= htmlspecialchars($data['error']) ?></div>
                    <?php endif; ?>
                    <form action="<?= URLROOT ?>/admin/Products/create" method="POST" enctype="multipart/form-data">
                        <div class="form-row">
                            <div class="col-md-6 mb-3 ">
                                <label for="name" class="font-weight-bold">Tên sản phẩm <span class="text-danger">(*)</span></label>
                                <input type="text" class="form-control" id="name" name="name" required>
                            </div>
                            <div class="col-md-6 mb-3">
                                <label for="sku" class="font-weight-bold">Mã SKU <span class="text-danger">(*)</span></label>
                                <input type="text" class="form-control" id="sku" name="sku" required>
                            </div>
                        </div>
                        
                        <div class="form-row">
                            <div class="col-md-4 mb-3">
                                <label for="cateId" class="font-weight-bold">Danh mục <span class="text-danger">(*)</span></label>
                                <select class="form-control" id="cateId" name="cateId" required onchange="toggleNewCategory()" style="height: auto;">
                                    <option value="">-- Chọn danh mục --</option>
                                    <?php foreach ($data['categories'] ?? [] as $cate): ?>
                                        <option value="<?= is_object($cate) ? $cate->cateId : $cate['cateId'] ?>">
                                            <?= htmlspecialchars(is_object($cate) ? $cate->name : $cate['name']) ?>
                                        </option>
                                    <?php endforeach; ?>
                                    <option value="new" class="font-weight-bold text-primary">+ Khác (Thêm danh mục mới)</option>
                                </select>
                                <input type="text" class="form-control mt-2 d-none" id="new_category_name" name="new_category_name" placeholder="Nhập tên danh mục mới">
                            </div>
                            <div class="col-md-4 mb-3">
                                <label for="price" class="font-weight-bold">Giá (VNĐ) <span class="text-danger">(*)</span></label>
                                <input type="text" class="form-control" id="price" name="price" value="0" required oninput="formatPrice(this)">
                            </div>
                            <div class="col-md-4 mb-3">
                                <label for="stock_quantity" class="font-weight-bold">Số lượng trong kho</label>
                                <input type="number" class="form-control" id="stock_quantity" name="stock_quantity" value="0" min="0" required>
                            </div>
                        </div>

                        <div class="form-row">
                            <div class="col-md-4 mb-3">
                                <label for="color" class="font-weight-bold">Màu sắc</label>
                                <select class="form-control" id="color_select" name="color_select" onchange="toggleCustomColor()" style="height: auto;">
                                    <option value="">-- Chọn màu --</option>
                                    <option value="Vàng">Vàng</option>
                                    <option value="Bạc">Bạc</option>
                                    <option value="Bạch kim">Bạch kim</option>
                                    <option value="Vàng hồng">Vàng hồng</option>
                                    <option value="Trắng">Trắng</option>
                                    <option value="Đen">Đen</option>
                                    <option value="other" class="font-weight-bold text-primary">+ Khác...</option>
                                </select>
                                <input type="text" class="form-control mt-2 d-none" id="color_custom" name="color_custom" placeholder="Nhập màu sắc khác">
                            </div>
                            <div class="col-md-4 mb-3">
                                <label for="size" class="font-weight-bold">Kích cỡ (Size)</label>
                                <div class="mb-2">
                                    <?php foreach (['S', 'M', 'L', 'ONESIZE'] as $s): ?>
                                    <div class="custom-control custom-checkbox custom-control-inline">
                                        <input type="checkbox" class="custom-control-input" id="size_<?= $s ?>" name="sizes[]" value="<?= $s ?>">
                                        <label class="custom-control-label" for="size_<?= $s ?>"><?= $s ?></label>
                                    </div>
                                    <?php endforeach; ?>
                                </div>
                                <input type="text" class="form-control" id="size_custom" name="size_custom" placeholder="Khác (VD: 55, 56,...)">
                            </div>
                            
                        </div>
                        <div class="form-group">
                                <label for="material" class="font-weight-bold">Chất liệu</label>
                                <input type="text" class="form-control" id="material" name="material">
                            </div>
                        <div class="form-group">
                                <label for="size_dim" class="font-weight-bold">Kích cỡ chi tiết</label>
                                <input type="text" class="form-control" id="size_dim" name="size_dim" >
                            </div>
                        <div class="form-group">                           
                                <label for="usage_info" class="font-weight-bold">Cách bảo quản</label>
                                <input type="text" class="form-control" id="usage_info" name="usage_info">
                        </div>
                        <div class="form-group">
                            <label for="description" class="font-weight-bold">Mô tả sản phẩm</label>
                            <textarea class="form-control" id="description" name="description" rows="4"></textarea>
                        </div>

                        <div class="form-group border p-3 bg-light rounded">
                            <label class="font-weight-bold">Hình ảnh sản phẩm (Tối đa 5 ảnh)</label>
                            <div class="custom-file mb-3">
                                <input type="file" class="custom-file-input" id="images" multiple accept="image/*" onchange="handleFileSelect(event)">
                                <label class="custom-file-label" for="images">Chọn tệp...</label>
                            </div>
                            <input type="file" id="real-images-input" name="images[]" multiple class="d-none">
                            <div id="new-image-preview-container" class="d-flex flex-wrap" style="gap: 10px;">
                            </div>
                        </div>

                        

                        <button class="btn btn-success" type="submit"><i class="ti-save"></i> Lưu sản phẩm</button>
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

    let dataTransfer = new DataTransfer();

    function handleFileSelect(e) {
        const files = e.target.files;
        if (!files || files.length === 0) return;

        let currentCount = dataTransfer.files.length;
        
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
        
        for (let i = 0; i < dataTransfer.files.length; i++) {
            const file = dataTransfer.files[i];
            var reader = new FileReader();
            
            reader.addEventListener("load", function() {
                var div = document.createElement('div');
                div.className = 'position-relative border rounded p-1 bg-white';
                div.style.width = '100px';
                div.style.height = '100px';
                
                let isChecked = i === 0 ? 'checked' : '';

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
        let count = dataTransfer.files.length;
        document.querySelector('.custom-file-label').innerHTML = count > 0 ? count + ' tệp mới' : 'Chọn tệp...';
    }
</script>