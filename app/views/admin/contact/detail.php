<div class="row">
    <div class="col-md-6 mt-4">
        <div class="card">
            <div class="card-body">
                <h4 class="header-title">Thông tin liên hệ</h4>

                <?php if (isset($_GET['success'])): ?>
                    <div class="alert alert-success">Cập nhật thành công.</div>
                <?php endif; ?>

                <div class="detail-list">
                    <p><strong>Mã liên hệ:</strong> #<?= (int)$contact->contactId ?></p>
                    <p><strong>Khách hàng:</strong> <?= htmlspecialchars($contact->name, ENT_QUOTES, 'UTF-8') ?></p>
                    <p><strong>Email:</strong> <?= htmlspecialchars($contact->email ?? '', ENT_QUOTES, 'UTF-8') ?></p>
                    <p><strong>Điện thoại:</strong> <?= htmlspecialchars($contact->phone ?? 'Chưa cung cấp', ENT_QUOTES, 'UTF-8') ?></p>
                    <p><strong>Tiêu đề:</strong> <?= htmlspecialchars($contact->subject ?? '', ENT_QUOTES, 'UTF-8') ?></p>
                    <p><strong>Nội dung:</strong></p>
                    <div class="message-box"><?= nl2br(htmlspecialchars($contact->message, ENT_QUOTES, 'UTF-8')) ?></div>
                </div>
            </div>
        </div>
    </div>

    <div class="col-md-6 mt-4">
        <div class="card">
            <div class="card-body">
                <h4 class="header-title">Xử lý liên hệ</h4>
                <form action="<?= URLROOT ?>/AdminContact/update/<?= (int)$contact->contactId ?>" method="POST" data-admin-form>
                    <div class="form-group">
                        <label for="status" class="col-form-label">Trạng thái</label>
                        <select class="custom-select" id="status" name="status" required>
                            <option value="pending" <?= $contact->status === 'pending' ? 'selected' : '' ?>>Chưa đọc</option>
                            <option value="read" <?= $contact->status === 'read' ? 'selected' : '' ?>>Đã đọc</option>
                            <option value="replied" <?= $contact->status === 'replied' ? 'selected' : '' ?>>Đã phản hồi</option>
                        </select>
                        <?php if (!empty($errors['status'])): ?>
                            <small class="text-danger"><?= htmlspecialchars($errors['status'], ENT_QUOTES, 'UTF-8') ?></small>
                        <?php endif; ?>
                    </div>

                    <div class="form-group">
                        <label for="reply_content" class="col-form-label">Nội dung phản hồi</label>
                        <textarea class="form-control" id="reply_content" name="reply_content" rows="8" maxlength="3000"><?= htmlspecialchars($contact->reply_content ?? '', ENT_QUOTES, 'UTF-8') ?></textarea>
                        <?php if (!empty($errors['reply_content'])): ?>
                            <small class="text-danger"><?= htmlspecialchars($errors['reply_content'], ENT_QUOTES, 'UTF-8') ?></small>
                        <?php endif; ?>
                    </div>

                    <button type="submit" class="btn btn-primary mt-3">Lưu xử lý</button>
                    <a href="<?= URLROOT ?>/AdminContact" class="btn btn-secondary mt-3 ml-2">Quay lại</a>
                </form>
            </div>
        </div>
    </div>
</div>
