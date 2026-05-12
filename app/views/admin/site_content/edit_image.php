<div class="row">
    <div class="col-12 mt-4">
        <div class="card">
            <div class="card-body">
                <h4 class="header-title">Upload hình ảnh mới</h4>
                <div class="detail-list mb-3">
                    <p><strong>Khóa ảnh:</strong> <code><?= htmlspecialchars($image->image_key, ENT_QUOTES, 'UTF-8') ?></code></p>
                    <p><strong>Vị trí:</strong> <?= htmlspecialchars($image->location_tag, ENT_QUOTES, 'UTF-8') ?></p>
                    <p><strong>Đường dẫn hiện tại:</strong> <?= htmlspecialchars($image->filepath, ENT_QUOTES, 'UTF-8') ?></p>
                </div>

                <div class="mb-3">
                    <img src="<?= URLROOT . '/' . ltrim(htmlspecialchars($image->filepath, ENT_QUOTES, 'UTF-8'), '/') ?>" alt="<?= htmlspecialchars($image->image_key, ENT_QUOTES, 'UTF-8') ?>" class="admin-preview">
                </div>

                <form action="<?= URLROOT ?>/AdminSiteContent/updateImage/<?= (int)$image->imageId ?>" method="POST" enctype="multipart/form-data" data-image-form>
                    <div class="form-group">
                        <label for="image" class="col-form-label">Chọn ảnh từ máy <span class="text-danger">*</span></label>
                        <input class="form-control" type="file" id="image" name="image" accept=".jpg,.jpeg,.png,.gif,.webp,image/*" required>
                        <small class="form-text text-muted">File tối đa 2MB. Chỉ dùng ảnh upload lên server của website.</small>
                        <?php if (!empty($errors['image'])): ?>
                            <small class="text-danger"><?= htmlspecialchars($errors['image'], ENT_QUOTES, 'UTF-8') ?></small>
                        <?php endif; ?>
                    </div>

                    <button type="submit" class="btn btn-primary mt-3">Lưu hình ảnh</button>
                    <a href="<?= URLROOT ?>/AdminSiteContent/media" class="btn btn-secondary mt-3 ml-2">Hủy</a>
                </form>
            </div>
        </div>
    </div>
</div>
