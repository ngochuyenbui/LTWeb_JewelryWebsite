<div class="row">
    <div class="col-12 mt-4">
        <div class="card">
            <div class="card-body">
                <h4 class="header-title">Thêm Bài viết mới</h4>
                
                <?php if(isset($_GET['error'])): ?>
                    <div class="alert alert-danger">Có lỗi xảy ra trong quá trình thêm bài viết. Vui lòng kiểm tra lại!</div>
                <?php endif; ?>

                <form action="<?= URLROOT ?>/AdminArticle/store" method="POST" enctype="multipart/form-data" onsubmit="return validateForm()">
                    <div class="form-group">
                        <label for="title" class="col-form-label">Tiêu đề bài viết <span class="text-danger">*</span></label>
                        <input class="form-control" type="text" id="title" name="title" required>
                    </div>

                    <div class="form-group">
                        <label for="cateId" class="col-form-label">Danh mục <span class="text-danger">*</span></label>
                        <select class="custom-select" name="cateId" required>
                            <option value="">-- Chọn danh mục --</option>
                            <?php foreach($categories as $cate): ?>
                                <option value="<?= $cate->cateId ?>"><?= htmlspecialchars($cate->name) ?></option>
                            <?php endforeach; ?>
                        </select>
                    </div>

                    <div class="form-group">
                        <label for="thumbnail" class="col-form-label">Ảnh đại diện (Thumbnail)</label>
                        <input class="form-control" type="file" id="thumbnail" name="thumbnail" accept="image/*">
                        <small class="form-text text-muted">Hỗ trợ các định dạng: jpg, jpeg, png, gif, webp.</small>
                    </div>

                    <div class="form-group">
                        <label for="content" class="col-form-label">Nội dung bài viết <span class="text-danger">*</span></label>
                        <!-- Textarea cho WYSIWYG editor -->
                        <textarea class="form-control" id="content" name="content" rows="10"></textarea>
                        <small id="contentError" class="text-danger d-none">Vui lòng nhập nội dung bài viết.</small>
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
