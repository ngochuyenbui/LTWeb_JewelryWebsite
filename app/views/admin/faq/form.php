<?php
$e = static fn($value) => htmlspecialchars((string)$value, ENT_QUOTES, 'UTF-8');
$primaryKey = $section['primaryKey'];
$isEdit = $mode === 'edit';
$formAction = $isEdit
    ? URLROOT . '/AdminFaq/update/' . $sectionKey . '/' . (int)($row->{$primaryKey} ?? 0)
    : URLROOT . '/AdminFaq/store/' . $sectionKey;
$fieldValue = static function ($row, $fieldName, $field) {
    if (isset($row->{$fieldName})) {
        return $row->{$fieldName};
    }
    return $field['default'] ?? '';
};
$inputValue = static function ($value, $type) {
    if ($type !== 'datetime-local' || empty($value)) {
        return $value;
    }
    $timestamp = strtotime((string)$value);
    return $timestamp ? date('Y-m-d\TH:i', $timestamp) : $value;
};
?>

<style>
    .faq-admin-tabs .nav-link {
        color: #444;
        border: 1px solid #e5e9f2;
        margin-right: 8px;
        margin-bottom: 8px;
        background: #fff;
    }
    .faq-admin-tabs .nav-link.active {
        color: #fff;
        background: #4caf50;
        border-color: #4caf50;
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
                    <a href="<?= URLROOT ?>/AdminFaq/index/<?= $e($sectionKey) ?>" class="btn btn-secondary btn-sm mb-2">
                        <i class="ti-arrow-left"></i> Quay lại
                    </a>
                </div>

                <ul class="nav nav-pills faq-admin-tabs mb-4">
                    <?php foreach ($sections as $key => $item): ?>
                        <li class="nav-item">
                            <a class="nav-link <?= $key === $sectionKey ? 'active' : '' ?>" href="<?= URLROOT ?>/AdminFaq/index/<?= $e($key) ?>">
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

                <form action="<?= $e($formAction) ?>" method="POST">
                    <div class="row">
                        <?php foreach ($section['fields'] as $fieldName => $field): ?>
                            <?php
                                $type = $field['type'] ?? 'text';
                                $value = $inputValue($fieldValue($row, $fieldName, $field), $type);
                                $required = !empty($field['required']) ? 'required' : '';
                                $isWide = $type === 'textarea' || $fieldName === 'question' || $fieldName === 'answer';
                                $inputType = in_array($type, ['email', 'tel', 'number', 'datetime-local'], true) ? $type : 'text';
                            ?>
                            <div class="<?= $isWide ? 'col-md-12' : 'col-md-6' ?>">
                                <div class="mb-3">
                                    <label for="<?= $e($fieldName) ?>" class="col-form-label">
                                        <?= $e($field['label']) ?>
                                        <?php if (!empty($field['required'])): ?><span class="text-danger">*</span><?php endif; ?>
                                    </label>

                                    <?php if ($type === 'textarea'): ?>
                                        <textarea
                                            class="form-control"
                                            id="<?= $e($fieldName) ?>"
                                            name="<?= $e($fieldName) ?>"
                                            rows="<?= $fieldName === 'answer' || $fieldName === 'question' ? '5' : '4' ?>"
                                            <?= isset($field['minLength']) ? 'minlength="' . $e($field['minLength']) . '"' : '' ?>
                                            <?= isset($field['maxLength']) ? 'maxlength="' . $e($field['maxLength']) . '"' : '' ?>
                                            <?= $required ?>
                                        ><?= $e($value) ?></textarea>
                                    <?php elseif ($type === 'select'): ?>
                                        <select class="custom-select" id="<?= $e($fieldName) ?>" name="<?= $e($fieldName) ?>" <?= $required ?>>
                                            <?php foreach (($field['options'] ?? []) as $optionValue => $optionLabel): ?>
                                                <option value="<?= $e($optionValue) ?>" <?= (string)$value === (string)$optionValue ? 'selected' : '' ?>>
                                                    <?= $e($optionLabel) ?>
                                                </option>
                                            <?php endforeach; ?>
                                        </select>
                                    <?php else: ?>
                                        <input
                                            class="form-control"
                                            type="<?= $e($inputType) ?>"
                                            id="<?= $e($fieldName) ?>"
                                            name="<?= $e($fieldName) ?>"
                                            value="<?= $e($value) ?>"
                                            <?= isset($field['min']) ? 'min="' . $e($field['min']) . '"' : '' ?>
                                            <?= isset($field['max']) ? 'max="' . $e($field['max']) . '"' : '' ?>
                                            <?= isset($field['minLength']) ? 'minlength="' . $e($field['minLength']) . '"' : '' ?>
                                            <?= isset($field['maxLength']) ? 'maxlength="' . $e($field['maxLength']) . '"' : '' ?>
                                            <?= $type === 'tel' ? 'pattern="[0-9+\\-\\s().]{8,30}"' : '' ?>
                                            <?= $required ?>
                                        >
                                    <?php endif; ?>
                                </div>
                            </div>
                        <?php endforeach; ?>
                    </div>

                    <button type="submit" class="btn btn-primary mt-3 pr-4 pl-4">
                        <i class="ti-save"></i> <?= $isEdit ? 'Cập nhật' : 'Lưu mới' ?>
                    </button>
                    <a href="<?= URLROOT ?>/AdminFaq/index/<?= $e($sectionKey) ?>" class="btn btn-secondary mt-3 pr-4 pl-4 ml-2">Hủy</a>
                </form>
            </div>
        </div>
    </div>
</div>
