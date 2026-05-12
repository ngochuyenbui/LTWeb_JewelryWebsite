<div class="row">
    <div class="col-12 mt-4">
        <div class="card">
            <div class="card-body">
                <h4 class="header-title">Cập nhật nội dung</h4>
                <div class="detail-list mb-3">
                    <p><strong>Nhóm:</strong> <?= htmlspecialchars($item->section, ENT_QUOTES, 'UTF-8') ?></p>
                    <p><strong>Khóa:</strong> <code><?= htmlspecialchars($item->page_key, ENT_QUOTES, 'UTF-8') ?></code></p>
                </div>

                <form action="<?= URLROOT ?>/AdminSiteContent/update/<?= (int)$item->pageId ?>" method="POST" data-admin-form>
                    <div class="form-group">
                        <label for="content" class="col-form-label">Nội dung <span class="text-danger">*</span></label>
                        <textarea class="form-control" id="content" name="content" rows="7" maxlength="3000" required><?= htmlspecialchars($item->content, ENT_QUOTES, 'UTF-8') ?></textarea>
                        <?php if (!empty($errors['content'])): ?>
                            <small class="text-danger"><?= htmlspecialchars($errors['content'], ENT_QUOTES, 'UTF-8') ?></small>
                        <?php endif; ?>
                    </div>

                    <button type="submit" class="btn btn-primary mt-3">Lưu thay đổi</button>
                    <a href="<?= URLROOT ?>/AdminSiteContent" class="btn btn-secondary mt-3 ml-2">Hủy</a>
                </form>
            </div>
        </div>
    </div>
</div>
