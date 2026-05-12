<div class="main-content-inner">
    <div class="row">
        <div class="col-12 mt-5">
            <div class="card">
                <div class="card-body">
                    <h4 class="header-title text-primary">Thêm Danh Mục Mới</h4>
                    <?php if (!empty($data['error'])): ?>
                        <div class="alert alert-danger fw-bold"><?= htmlspecialchars($data['error']) ?></div>
                    <?php endif; ?>
                    <form action="<?= URLROOT ?>/admin/Categories/create" method="POST" enctype="multipart/form-data">
                        <div class="mb-3">
                            <label for="name" class="fw-bold">Tên danh mục <span class="text-danger">(*)</span></label>
                            <input type="text" class="form-control" id="name" name="name" required>
                        </div>
                        <div class="mb-3">
                            <label for="type" class="fw-bold">Phân loại (Type)</label>
                            <select class="form-control" id="type" name="type" style="height: auto;">
                                <option value="product">Sản phẩm (Product)</option>
                                <option value="article">Bài viết (Article)</option>
                            </select>
                        </div>
                        <div class="mb-3">
                            <label for="slug" class="fw-bold">Đường dẫn tĩnh (Slug) - Tự động tạo nếu để trống</label>
                            <input type="text" class="form-control" id="slug" name="slug" placeholder="VD: vong-co-nu">
                        </div>
                        <div class="mb-3">
                            <label class="fw-bold">Hình ảnh danh mục</label>
                            <input type="file" class="form-control mb-3" id="image" name="image" accept="image/*" onchange="previewImage(event)">
                            <div id="preview-container" class="mt-2 d-none">
                                <img id="image-preview" src="#" alt="Preview" class="border rounded shadow-sm" style="max-width: 200px; max-height: 200px; object-fit: contain;">
                            </div>
                        </div>
                        <button class="btn btn-success" type="submit"><i class="ti-save"></i> Thêm mới</button>
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

// Tự động tạo Slug từ tên
document.getElementById('name').addEventListener('input', function() {
    let name = this.value;
    let slug = name.toLowerCase()
        .replace(/[áàảạãăắằẳẵặâấầẩẫậ]/g, 'a')
        .replace(/[éèẻẽẹêếềểễệ]/g, 'e')
        .replace(/[íìỉĩị]/g, 'i')
        .replace(/[óòỏõọôốồổỗộơớờởỡợ]/g, 'o')
        .replace(/[úùủũụưứừửữự]/g, 'u')
        .replace(/[ýỳỷỹỵ]/g, 'y')
        .replace(/đ/g, 'd')
        .replace(/[^a-z0-9\-]+/g, '-')
        .replace(/\-+/g, '-')
        .replace(/^\-+|\-+$/g, '');

    document.getElementById('slug').value = slug;
});
</script>