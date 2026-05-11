<div class="row">
    <div class="col-12 mt-4">
        <div class="card">
            <div class="card-body">
                <h4 class="header-title">Thêm Bài viết mới</h4>
                
                <?php if(isset($_GET['error'])): ?>
                    <div class="alert alert-danger">
                        <?= $_GET['error'] === 'db' ? 'Lỗi cơ sở dữ liệu, vui lòng thử lại!' : 'Có lỗi xảy ra, vui lòng kiểm tra lại!' ?>
                    </div>
                <?php endif; ?>

                <?php if (!empty($errors)): ?>
                    <div class="alert alert-danger">
                        <strong>⚠️ Vui lòng kiểm tra lại các trường sau:</strong>
                        <ul class="mb-0 mt-1">
                            <?php foreach ($errors as $err): ?>
                                <li><?= htmlspecialchars($err) ?></li>
                            <?php endforeach; ?>
                        </ul>
                    </div>
                <?php endif; ?>

                <form action="<?= URLROOT ?>/AdminArticle/store" method="POST" enctype="multipart/form-data" onsubmit="return validateForm()">
                    <div class="form-group">
                        <label for="title" class="col-form-label">Tiêu đề bài viết <span class="text-danger">*</span></label>
                        <input class="form-control <?= !empty($errors['title']) ? 'is-invalid' : '' ?>" 
                               type="text" id="title" name="title" 
                               value="<?= htmlspecialchars($old['title'] ?? '') ?>" required>
                        <?php if (!empty($errors['title'])): ?>
                            <div class="invalid-feedback"><?= htmlspecialchars($errors['title']) ?></div>
                        <?php endif; ?>
                    </div>

                    <div class="form-group">
                        <label for="cateId" class="col-form-label">Danh mục <span class="text-danger">*</span></label>
                        <select class="custom-select <?= !empty($errors['cateId']) ? 'is-invalid' : '' ?>" name="cateId" required>
                            <option value="">-- Chọn danh mục --</option>
                            <?php foreach($categories as $cate): ?>
                                <option value="<?= $cate->cateId ?>" 
                                    <?= (isset($old['cateId']) && $old['cateId'] == $cate->cateId) ? 'selected' : '' ?>>
                                    <?= htmlspecialchars($cate->name) ?>
                                </option>
                            <?php endforeach; ?>
                        </select>
                        <?php if (!empty($errors['cateId'])): ?>
                            <div class="invalid-feedback"><?= htmlspecialchars($errors['cateId']) ?></div>
                        <?php endif; ?>
                    </div>

                    <div class="form-group">
                        <label for="thumbnail" class="col-form-label">Ảnh đại diện (Thumbnail)</label>
                        <input class="form-control <?= !empty($errors['thumbnail']) ? 'is-invalid' : '' ?>" 
                               type="file" id="thumbnail" name="thumbnail" accept="image/*">
                        <small class="form-text text-muted">Hỗ trợ các định dạng: JPG, PNG, GIF, WEBP. Tối đa 5MB.</small>
                        <?php if (!empty($errors['thumbnail'])): ?>
                            <div class="invalid-feedback d-block"><?= htmlspecialchars($errors['thumbnail']) ?></div>
                        <?php endif; ?>
                    </div>

                    <div class="form-group">
                        <label for="content" class="col-form-label">Nội dung bài viết <span class="text-danger">*</span></label>
                        <textarea class="form-control <?= !empty($errors['content']) ? 'is-invalid' : '' ?>" 
                                  id="content" name="content" rows="10"><?= htmlspecialchars($old['content'] ?? '') ?></textarea>
                        <?php if (!empty($errors['content'])): ?>
                            <div class="invalid-feedback d-block"><?= htmlspecialchars($errors['content']) ?></div>
                        <?php else: ?>
                            <small id="contentError" class="text-danger d-none">Vui lòng nhập nội dung bài viết.</small>
                        <?php endif; ?>
                    </div>

                    <button type="submit" class="btn btn-primary mt-4 pr-4 pl-4">Lưu bài viết</button>
                    <a href="<?= URLROOT ?>/AdminArticle" class="btn btn-secondary mt-4 pr-4 pl-4 ml-2">Hủy</a>
                </form>
            </div>
        </div>
    </div>
</div>

<!-- Tích hợp CKEditor (hoặc TinyMCE) cho WYSIWYG -->
<script src="https://cdn.ckeditor.com/4.16.2/standard/ckeditor.js"></script>
<script>
    CKEDITOR.replace('content');

    function validateForm() {
        // Update lại nội dung từ CKEditor vào textarea
        for (instance in CKEDITOR.instances) {
            CKEDITOR.instances[instance].updateElement();
        }
        var content = document.getElementById('content').value.trim();
        if (content.length === 0) {
            document.getElementById('contentError').classList.remove('d-none');
            return false;
        }
        document.getElementById('contentError').classList.add('d-none');
        return true;
    }
</script>
