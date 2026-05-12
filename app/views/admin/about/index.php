<?php
$e = static fn($value) => htmlspecialchars((string)$value, ENT_QUOTES, 'UTF-8');
$primaryKey = $section['primaryKey'];
$currentPage = max(1, (int)($currentPage ?? 1));
$totalPages = max(1, (int)($totalPages ?? 1));
$totalRows = max(0, (int)($totalRows ?? 0));
$limit = max(1, (int)($limit ?? 10));
$fromRow = $totalRows > 0 ? (($currentPage - 1) * $limit) + 1 : 0;
$toRow = min($totalRows, $currentPage * $limit);
$filters = is_array($filters ?? null) ? $filters : ['q' => '', 'status' => ''];
$queryParams = array_filter([
    'q' => trim((string)($filters['q'] ?? '')),
    'status' => trim((string)($filters['status'] ?? '')),
], static fn($value) => $value !== '');
$queryString = http_build_query($queryParams);
$pageUrl = static fn($page) => URLROOT . '/AdminAbout/index/' . rawurlencode((string)$sectionKey) . '?' . http_build_query(array_merge($queryParams, ['page' => (int)$page]));
$resetUrl = URLROOT . '/AdminAbout/index/' . rawurlencode((string)$sectionKey);
$statusLabel = static function ($value) use ($section) {
    $sectionLabels = $section['fields']['status']['options'] ?? [];
    if (isset($sectionLabels[$value])) {
        return $sectionLabels[$value];
    }

    $labels = [
        'active' => 'Hiển thị',
        'inactive' => 'Ẩn',
        'open' => 'Đang tuyển',
        'closed' => 'Đã đóng',
    ];
    return $labels[$value] ?? $value;
};
$statusClass = static function ($value) {
    if (in_array($value, ['active', 'open', 'offered'], true)) {
        return 'bg-success text-white';
    }
    if (in_array($value, ['new', 'reviewed', 'interview'], true)) {
        return 'bg-warning text-dark';
    }
    if (in_array($value, ['closed', 'inactive', 'rejected'], true)) {
        return 'bg-danger text-white';
    }
    return 'bg-secondary text-white';
};
$shortText = static function ($value) {
    $value = (string)$value;
    return strlen($value) > 120 ? substr($value, 0, 117) . '...' : $value;
};
$formatDate = static function ($value) {
    $timestamp = strtotime((string)$value);
    return $timestamp ? date('d/m/Y H:i', $timestamp) : (string)$value;
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
    .about-admin-thumb {
        width: 72px;
        height: 48px;
        object-fit: cover;
        border-radius: 4px;
        border: 1px solid #e5e9f2;
        background: #f8f9fa;
    }
    .about-admin-filter {
        border: 1px solid #e5e9f2;
        border-radius: 4px;
        padding: 14px;
        background: #fbfcfe;
    }
</style>

<div class="row">
    <div class="col-12 mt-4">
        <div class="card">
            <div class="card-body">
                <div class="d-flex flex-wrap justify-content-between align-items-center mb-3">
                    <h4 class="header-title mb-2">
                        <i class="<?= $e($section['icon']) ?>"></i>
                        <?= $e($section['label']) ?>
                    </h4>
                    <a href="<?= URLROOT ?>/AdminAbout/create/<?= $e($sectionKey) ?>" class="btn btn-primary btn-sm mb-2">
                        <i class="ti-plus"></i> Thêm mới
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

                <?php if (isset($_GET['success'])): ?>
                    <div class="alert alert-success">Thao tác thành công.</div>
                <?php endif; ?>
                <?php if (isset($_GET['error'])): ?>
                    <div class="alert alert-danger">Có lỗi xảy ra, vui lòng kiểm tra lại.</div>
                <?php endif; ?>

                <form class="about-admin-filter mb-4" method="get" action="<?= URLROOT ?>/AdminAbout/index/<?= $e($sectionKey) ?>">
                    <div class="row align-items-end">
                        <div class="mb-3 col-md-6 mb-2">
                            <label for="about-search" class="small text-muted mb-1">Tìm kiếm</label>
                            <input
                                type="text"
                                id="about-search"
                                name="q"
                                value="<?= $e($filters['q'] ?? '') ?>"
                                class="form-control"
                                placeholder="Nhập từ khoá..."
                            >
                        </div>
                        <?php if (!empty($section['fields']['status']['options'] ?? [])): ?>
                            <div class="mb-3 col-md-3 mb-2">
                                <label for="about-status" class="small text-muted mb-1">Trạng thái</label>
                                <select id="about-status" name="status" class="form-control">
                                    <option value="">Tất cả</option>
                                    <?php foreach ($section['fields']['status']['options'] as $value => $label): ?>
                                        <option value="<?= $e($value) ?>" <?= ($filters['status'] ?? '') === (string)$value ? 'selected' : '' ?>>
                                            <?= $e($label) ?>
                                        </option>
                                    <?php endforeach; ?>
                                </select>
                            </div>
                        <?php endif; ?>
                        <div class="mb-3 col-md-3 mb-2">
                            <button type="submit" class="btn btn-primary">
                                <i class="ti-search"></i> Lọc
                            </button>
                            <?php if ($queryString !== ''): ?>
                                <a href="<?= $e($resetUrl) ?>" class="btn btn-outline-secondary ml-1">Xoá lọc</a>
                            <?php endif; ?>
                        </div>
                    </div>
                </form>

                <div class="single-table">
                    <div class="table-responsive">
                        <table class="table table-hover progress-table">
                            <thead class="text-uppercase bg-light">
                                <tr>
                                    <th scope="col">ID</th>
                                    <?php foreach ($section['columns'] as $fieldName => $column): ?>
                                        <th scope="col"><?= $e($column['label']) ?></th>
                                    <?php endforeach; ?>
                                    <th scope="col" class="text-center">Hành động</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php if (empty($rows)): ?>
                                    <tr>
                                        <td colspan="<?= count($section['columns']) + 2 ?>" class="text-center text-muted">
                                            Chưa có dữ liệu.
                                        </td>
                                    </tr>
                                <?php else: ?>
                                    <?php foreach ($rows as $row): ?>
                                        <tr>
                                            <th scope="row"><?= $e($row->{$primaryKey} ?? '') ?></th>
                                            <?php foreach ($section['columns'] as $fieldName => $column): ?>
                                                <?php $value = $row->{$fieldName} ?? ''; ?>
                                                <td>
                                                    <?php if (($column['type'] ?? '') === 'image'): ?>
                                                        <?php $imageUrl = $assetUrl($value); ?>
                                                        <?php if ($imageUrl !== ''): ?>
                                                            <img src="<?= $e($imageUrl) ?>" alt="<?= $e($row->title ?? $row->customer_name ?? 'image') ?>" class="about-admin-thumb">
                                                        <?php else: ?>
                                                            <span class="text-muted">N/A</span>
                                                        <?php endif; ?>
                                                    <?php elseif (($column['type'] ?? '') === 'file'): ?>
                                                        <?php $fileUrl = $assetUrl($value); ?>
                                                        <?php if ($fileUrl !== ''): ?>
                                                            <a href="<?= $e($fileUrl) ?>" target="_blank" rel="noopener" class="btn btn-outline-primary btn-sm">
                                                                <i class="fa fa-file-text-o"></i> Xem CV
                                                            </a>
                                                        <?php else: ?>
                                                            <span class="text-muted">N/A</span>
                                                        <?php endif; ?>
                                                    <?php elseif (($column['type'] ?? '') === 'datetime'): ?>
                                                        <?= $e($formatDate($value)) ?>
                                                    <?php elseif (($column['type'] ?? '') === 'status'): ?>
                                                        <span class="badge badge-pill <?= $e($statusClass($value)) ?>" style="padding: 8px 12px; font-size: 13px;"><?= $e($statusLabel($value)) ?></span>
                                                    <?php else: ?>
                                                        <?= nl2br($e($shortText($value))) ?>
                                                    <?php endif; ?>
                                                </td>
                                            <?php endforeach; ?>
                                            <td class="text-center">
                                                <a href="<?= URLROOT ?>/AdminAbout/edit/<?= $e($sectionKey) ?>/<?= (int)($row->{$primaryKey} ?? 0) ?>" class="btn btn-sm btn-info" title="Sửa">
                                                    <i class="fa fa-edit"></i>
                                                </a>
                                                <a href="<?= URLROOT ?>/AdminAbout/delete/<?= $e($sectionKey) ?>/<?= (int)($row->{$primaryKey} ?? 0) ?>" class="btn btn-sm btn-danger" onclick="return confirm('Bạn có chắc muốn xóa dữ liệu này?');" title="Xóa">
                                                    <i class="fa fa-trash"></i>
                                                </a>
                                            </td>
                                        </tr>
                                    <?php endforeach; ?>
                                <?php endif; ?>
                            </tbody>
                        </table>
                    </div>
                </div>

                <?php if ($totalPages > 1): ?>
                    <?php
                        $pageStart = max(1, $currentPage - 2);
                        $pageEnd = min($totalPages, $currentPage + 2);
                    ?>
                    <div class="d-flex flex-wrap justify-content-between align-items-center mt-3">
                        <div class="text-muted small mb-2">
                            Hiển thị <?= $fromRow ?>-<?= $toRow ?> / <?= $totalRows ?> bản ghi
                        </div>
                        <nav aria-label="Phân trang About">
                            <ul class="pagination pagination-sm mb-2">
                                <li class="page-item <?= $currentPage <= 1 ? 'disabled' : '' ?>">
                                    <a class="page-link" href="<?= $e($pageUrl($currentPage - 1)) ?>">Trước</a>
                                </li>

                                <?php if ($pageStart > 1): ?>
                                    <li class="page-item"><a class="page-link" href="<?= $e($pageUrl(1)) ?>">1</a></li>
                                    <?php if ($pageStart > 2): ?>
                                        <li class="page-item disabled"><span class="page-link">...</span></li>
                                    <?php endif; ?>
                                <?php endif; ?>

                                <?php for ($page = $pageStart; $page <= $pageEnd; $page++): ?>
                                    <li class="page-item <?= $page === $currentPage ? 'active' : '' ?>">
                                        <a class="page-link" href="<?= $e($pageUrl($page)) ?>"><?= $page ?></a>
                                    </li>
                                <?php endfor; ?>

                                <?php if ($pageEnd < $totalPages): ?>
                                    <?php if ($pageEnd < $totalPages - 1): ?>
                                        <li class="page-item disabled"><span class="page-link">...</span></li>
                                    <?php endif; ?>
                                    <li class="page-item"><a class="page-link" href="<?= $e($pageUrl($totalPages)) ?>"><?= $totalPages ?></a></li>
                                <?php endif; ?>

                                <li class="page-item <?= $currentPage >= $totalPages ? 'disabled' : '' ?>">
                                    <a class="page-link" href="<?= $e($pageUrl($currentPage + 1)) ?>">Sau</a>
                                </li>
                            </ul>
                        </nav>
                    </div>
                <?php elseif ($totalRows > 0): ?>
                    <div class="text-muted small mt-3">Hiển thị <?= $totalRows ?> bản ghi.</div>
                <?php endif; ?>

                <div class="mt-3">
                    <a href="<?= URLROOT ?>/About" class="btn btn-outline-secondary btn-sm" target="_blank">
                        <i class="ti-new-window"></i> Xem trang About
                    </a>
                </div>
            </div>
        </div>
    </div>
</div>
