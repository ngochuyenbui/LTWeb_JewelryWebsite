<div class="row">
    <div class="col-12 mt-4">
        <div class="card">
            <div class="card-body">
                <h4 class="header-title">Cập nhật Bài viết</h4>
                
                <?php if(isset($_GET['error'])): ?>
                    <div class="alert alert-danger">Có lỗi xảy ra trong quá trình cập nhật. Vui lòng kiểm tra lại!</div>
                <?php endif; ?>

                <form action="<?= URLROOT ?>/AdminArticle/update/<?= $article->articleId ?>" method="POST" enctype="multipart/form-data" onsubmit="return validateForm()">
                    <div class="form-group">
                        <label for="title" class="col-form-label">Tiêu đề bài viết <span class="text-danger">*</span></label>
                        <input class="form-control" type="text" id="title" name="title" value="<?= htmlspecialchars($article->title) ?>" required>
                    </div>

                    <div class="form-group">
                        <label for="cateId" class="col-form-label">Danh mục <span class="text-danger">*</span></label>
                        <select class="custom-select" name="cateId" required>
                            <option value="">-- Chọn danh mục --</option>
                            <?php foreach($categories as $cate): ?>
                                <option value="<?= $cate->cateId ?>" <?= ($cate->cateId == $article->cateId) ? 'selected' : '' ?>>
                                    <?= htmlspecialchars($cate->name) ?>
                                </option>
                            <?php endforeach; ?>
                        </select>
                    </div>

                    <div class="form-group">
                        <label for="thumbnail" class="col-form-label">Ảnh đại diện (Thumbnail mới)</label>
                        <?php if (!empty($article->thumbnail)): ?>
                            <div class="mb-2">
                                <img src="<?= URLROOT . '/' . htmlspecialchars($article->thumbnail) ?>" alt="Current Thumbnail" width="100">
                                <br><small>Ảnh hiện tại</small>
                            </div>
                        <?php endif; ?>
                        <input class="form-control" type="file" id="thumbnail" name="thumbnail" accept="image/*">
                        <small class="form-text text-muted">Nếu không chọn ảnh mới, ảnh cũ sẽ được giữ nguyên.</small>
                    </div>

                    <div class="form-group">
                        <label for="content" class="col-form-label">Nội dung bài viết <span class="text-danger">*</span></label>
                        <textarea class="form-control" id="content" name="content" rows="10"><?= htmlspecialchars($article->content) ?></textarea>
                        <small id="contentError" class="text-danger d-none">Vui lòng nhập nội dung bài viết.</small>
                    </div>

                    <button type="submit" class="btn btn-primary mt-4 pr-4 pl-4">Cập nhật bài viết</button>
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
