<?php
$c = $data['category'] ?? [];
$c_id = is_object($c) ? ($c->cateId ?? '') : ($c['cateId'] ?? '');
$c_name = is_object($c) ? ($c->name ?? '') : ($c['name'] ?? '');
$c_type = is_object($c) ? ($c->type ?? '') : ($c['type'] ?? '');
$c_slug = is_object($c) ? ($c->slug ?? '') : ($c['slug'] ?? '');
$c_hidden = is_object($c) ? ($c->is_hidden ?? 0) : ($c['is_hidden'] ?? 0);
$c_image = is_object($c) ? ($c->image_url ?? '') : ($c['image_url'] ?? '');
$imgSrc = empty($c_image) ? "https://placehold.co/200x200?text=No+Image" : (strpos($c_image, 'http') === 0 ? $c_image : URLROOT . $c_image);
?>
<div class="main-content-inner">
    <div class="row">
        <div class="col-12 mt-5">
            <div class="card">
                <div class="card-body">
                    <h4 class="header-title text-primary">Chỉnh sửa Danh Mục</h4>
                    <?php if (!empty($data['error'])): ?>
                        <div class="alert alert-danger font-weight-bold"><?= htmlspecialchars($data['error']) ?></div>
                    <?php endif; ?>
                    <form action="<?= URLROOT ?>/admin/Categories/edit/<?= htmlspecialchars($c_id) ?>" method="POST" enctype="multipart/form-data">
                        <div class="form-group">
                            <label for="name" class="font-weight-bold">Tên danh mục <span class="text-danger">(*)</span></label>
                            <input type="text" class="form-control" id="name" name="name" value="<?= htmlspecialchars($c_name) ?>" required>
                        </div>
                        <div class="form-group">
                            <label for="type" class="font-weight-bold">Phân loại (Type)</label>
                            <select class="form-control" id="type" name="type" style="height: auto;">
                                <option value="product" <?= $c_type === 'product' ? 'selected' : '' ?>>Sản phẩm (Product)</option>
                                <option value="article" <?= $c_type === 'article' ? 'selected' : '' ?>>Bài viết (Article)</option>
                            </select>
                        </div>
                        <div class="form-group">
                            <label for="slug" class="font-weight-bold">Đường dẫn tĩnh (Slug)</label>
                            <input type="text" class="form-control" id="slug" name="slug" value="<?= htmlspecialchars($c_slug) ?>">
                        </div>
                        <div class="form-group">
                            <div class="custom-control custom-switch">
                                <input type="checkbox" class="custom-control-input" id="is_hidden" name="is_hidden" <?= $c_hidden ? 'checked' : '' ?>>
                                <label class="custom-control-label text-danger font-weight-bold" for="is_hidden">Đánh dấu ẨN danh mục này</label>
                            </div>
                        </div>
                        <div class="form-group">
                            <label class="font-weight-bold">Đổi hình ảnh mới</label>
                            <div class="custom-file mb-3">
                                <input type="file" class="custom-file-input" id="image" name="image" accept="image/*" onchange="previewImage(event)">
                                <label class="custom-file-label" for="image">Chọn tệp... (Bỏ trống nếu giữ nguyên ảnh cũ)</label>
                            </div>
                            <div id="preview-container" class="mt-2">
                                <img id="image-preview" src="<?= htmlspecialchars($imgSrc) ?>" alt="Preview" class="border rounded shadow-sm" style="max-width: 200px; max-height: 200px; object-fit: contain;">
                            </div>
                        </div>
                        <button class="btn btn-success" type="submit"><i class="ti-save"></i> Cập nhật</button>
                        <a href="<?= URLROOT ?>/admin/Categories" class="btn btn-secondary"><i class="ti-back-left"></i> Quay lại</a>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
<script>
function previewImage(event) {
    if(event.target.files.length > 0) {
        document.getElementById('image-preview').src = URL.createObjectURL(event.target.files[0]);
        document.getElementById('preview-container').classList.remove('d-none');
        event.target.nextElementSibling.innerText = event.target.files[0].name;
    }
}
</script>