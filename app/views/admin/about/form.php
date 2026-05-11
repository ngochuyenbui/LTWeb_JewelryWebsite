<?php
$e = static fn($value) => htmlspecialchars((string)$value, ENT_QUOTES, 'UTF-8');
$primaryKey = $section['primaryKey'];
$isEdit = $mode === 'edit';
$formAction = $isEdit
    ? URLROOT . '/AdminAbout/update/' . $sectionKey . '/' . (int)($row->{$primaryKey} ?? 0)
    : URLROOT . '/AdminAbout/store/' . $sectionKey;
$fieldValue = static function ($row, $fieldName, $field) {
    if (isset($row->{$fieldName})) {
        return $row->{$fieldName};
    }
    return $field['default'] ?? '';
};
$assetUrl = static function ($path): string {
    $raw = trim((string)$path);
    if ($raw === '') {
        return '';
    }
    if (str_starts_with($raw, URLROOT)) {
        return $raw;
    }
    if (preg_match('/^https?:\/\//i', $raw)) {
        return '';
    }
    return URLROOT . '/' . ltrim($raw, '/');
};
?>

<style>
    .about-admin-tabs .nav-link {
        color: #444;
        border: 1px solid #e5e9f2;
        margin-right: 8px;
        margin-bottom: 8px;
        background: #fff;
    }
    .about-admin-tabs .nav-link.active {
        color: #fff;
        background: #4caf50;
        border-color: #4caf50;
    }
    .about-admin-preview {
        max-width: 180px;
        max-height: 120px;
        object-fit: cover;
        border: 1px solid #e5e9f2;
        border-radius: 4px;
        background: #f8f9fa;
    }
</style>

<div class="row">
    <div class="col-12 mt-4">
        <div class="card">
            <div class="card-body">
                <div class="d-flex flex-wrap justify-content-between align-items-center mb-3">
                    <h4 class="header-title mb-2">
                        <i class="<?= $e($section['icon']) ?>"></i>
                        <?= $isEdit ? 'Cập nhật ' : 'Thêm ' ?><?= $e($section['label']) ?>
                    </h4>
                    <a href="<?= URLROOT ?>/AdminAbout/index/<?= $e($sectionKey) ?>" class="btn btn-secondary btn-sm mb-2">
                        <i class="ti-arrow-left"></i> Quay lại
                    </a>
                </div>

                <ul class="nav nav-pills about-admin-tabs mb-4">
                    <?php foreach ($sections as $key => $item): ?>
                        <li class="nav-item">
                            <a class="nav-link <?= $key === $sectionKey ? 'active' : '' ?>" href="<?= URLROOT ?>/AdminAbout/index/<?= $e($key) ?>">
                                <i class="<?= $e($item['icon']) ?>"></i> <?= $e($item['label']) ?>
                            </a>
                        </li>
                    <?php endforeach; ?>
                </ul>

                <?php if (!empty($errors)): ?>
                    <div class="alert alert-danger">
                        <?php foreach ($errors as $error): ?>
                            <div><?= $e($error) ?></div>
                        <?php endforeach; ?>
                    </div>
                <?php endif; ?>

                <form action="<?= $e($formAction) ?>" method="POST" enctype="multipart/form-data">
                    <div class="row">
                        <?php foreach ($section['fields'] as $fieldName => $field): ?>
                            <?php
                                $type = $field['type'] ?? 'text';
                                $fileKind = $field['fileKind'] ?? 'image';
                                $value = $fieldValue($row, $fieldName, $field);
                                $previewUrl = $assetUrl($value);
                                $required = !empty($field['required']) && !($type === 'file' && $previewUrl !== '') ? 'required' : '';
                                $isWide = $type === 'textarea' || $type === 'file' || strpos($fieldName, 'url') !== false || $fieldName === 'description';
                                $fileHelpText = $fileKind === 'document'
                                    ? 'Chỉ dùng file PDF, DOC hoặc DOCX, tối đa 100MB.'
                                    : 'Chỉ dùng file ảnh upload lên server, tối đa 100MB.';
                            ?>
                            <div class="<?= $isWide ? 'col-md-12' : 'col-md-6' ?>">
                                <div class="form-group">
                                    <label for="<?= $e($fieldName) ?>" class="col-form-label">
                                        <?= $e($field['label']) ?>
                                        <?php if (!empty($field['required'])): ?><span class="text-danger">*</span><?php endif; ?>
                                    </label>

                                    <?php if ($type === 'textarea'): ?>
                                        <textarea class="form-control" id="<?= $e($fieldName) ?>" name="<?= $e($fieldName) ?>" rows="4" <?= $required ?>><?= $e($value) ?></textarea>
                                    <?php elseif ($type === 'select'): ?>
                                        <select class="custom-select" id="<?= $e($fieldName) ?>" name="<?= $e($fieldName) ?>" <?= $required ?>>
                                            <?php foreach (($field['options'] ?? []) as $optionValue => $optionLabel): ?>
                                                <option value="<?= $e($optionValue) ?>" <?= (string)$value === (string)$optionValue ? 'selected' : '' ?>>
                                                    <?= $e($optionLabel) ?>
                                                </option>
                                            <?php endforeach; ?>
                                        </select>
                                    <?php elseif ($type === 'file'): ?>
                                        <input type="hidden" name="<?= $e($fieldName) ?>_current" value="<?= $e($value) ?>">
                                        <input
                                            class="form-control-file"
                                            type="file"
                                            id="<?= $e($fieldName) ?>"
                                            name="<?= $e($fieldName) ?>"
                                            accept="<?= $e($field['accept'] ?? 'image/*') ?>"
                                            <?= $required ?>
                                        >
                                        <small class="form-text text-muted"><?= $e($fileHelpText) ?></small>
                                    <?php else: ?>
                                        <input
                                            class="form-control"
                                            type="<?= $e($type) ?>"
                                            id="<?= $e($fieldName) ?>"
                                            name="<?= $e($fieldName) ?>"
                                            value="<?= $e($value) ?>"
                                            <?= isset($field['min']) ? 'min="' . $e($field['min']) . '"' : '' ?>
                                            <?= isset($field['max']) ? 'max="' . $e($field['max']) . '"' : '' ?>
                                            <?= $required ?>
                                        >
                                    <?php endif; ?>

                                    <?php if (in_array($fieldName, ['image_url', 'avatar_url'], true) && $previewUrl !== ''): ?>
                                        <div class="mt-2">
                                            <img src="<?= $e($previewUrl) ?>" alt="Preview" class="about-admin-preview">
                                        </div>
                                    <?php endif; ?>
                                    <?php if ($type === 'file' && $fileKind === 'document' && $previewUrl !== ''): ?>
                                        <div class="mt-2">
                                            <a href="<?= $e($previewUrl) ?>" target="_blank" rel="noopener" class="btn btn-outline-primary btn-sm">
                                                <i class="fa fa-file-text-o"></i> Xem CV hiện tại
                                            </a>
                                        </div>
                                    <?php endif; ?>
                                </div>
                            </div>
                        <?php endforeach; ?>
                    </div>

                    <button type="submit" class="btn btn-primary mt-3 pr-4 pl-4">
                        <i class="ti-save"></i> <?= $isEdit ? 'Cập nhật' : 'Lưu mới' ?>
                    </button>
                    <a href="<?= URLROOT ?>/AdminAbout/index/<?= $e($sectionKey) ?>" class="btn btn-secondary mt-3 pr-4 pl-4 ml-2">Hủy</a>
                </form>
            </div>
        </div>
    </div>
</div>
