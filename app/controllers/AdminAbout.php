<?php

class AdminAbout extends Controller
{
    private $aboutAdminModel;

    public function __construct()
    {
        parent::__construct();
        $this->requireAdmin();
        $this->aboutAdminModel = $this->model('AdminAboutModel');
        $this->aboutAdminModel->ensureTables();
    }

    public function index($sectionKey = 'company-images')
    {
        $section = $this->sectionOrRedirect($sectionKey);
        $filters = $this->collectFilters($section);
        $limit = 10;
        $totalRows = $this->aboutAdminModel->countRows($sectionKey, $filters);
        $totalPages = max(1, (int)ceil($totalRows / $limit));
        $currentPage = isset($_GET['page']) ? (int)$_GET['page'] : 1;
        $currentPage = max(1, min($currentPage, $totalPages));
        $offset = ($currentPage - 1) * $limit;
        $rows = $this->aboutAdminModel->getRows($sectionKey, $limit, $offset, $filters);

        $this->view('admin/about/index', [
            'title' => 'Quản lý About - ' . $section['label'],
            'sections' => $this->aboutAdminModel->getSections(),
            'sectionKey' => $sectionKey,
            'section' => $section,
            'rows' => $rows,
            'currentPage' => $currentPage,
            'totalPages' => $totalPages,
            'totalRows' => $totalRows,
            'limit' => $limit,
            'filters' => $filters,
        ]);
    }

    public function create($sectionKey = 'company-images')
    {
        $section = $this->sectionOrRedirect($sectionKey);

        $this->renderForm($sectionKey, $section, 'create', (object)$this->defaultValues($section));
    }

    public function store($sectionKey = 'company-images')
    {
        $section = $this->sectionOrRedirect($sectionKey);
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            header('Location: ' . URLROOT . '/AdminAbout/create/' . $sectionKey);
            exit();
        }

        [$data, $errors] = $this->collectInput($section);
        $this->applyFileUploads($section, $data, $errors);
        if (!empty($errors)) {
            $this->renderForm($sectionKey, $section, 'create', (object)$data, $errors);
            return;
        }

        $success = $this->aboutAdminModel->create($sectionKey, $data);
        header('Location: ' . URLROOT . '/AdminAbout/index/' . $sectionKey . ($success ? '?success=created' : '?error=store'));
        exit();
    }

    public function edit($sectionKey = 'company-images', $id = null)
    {
        $section = $this->sectionOrRedirect($sectionKey);
        $row = $this->aboutAdminModel->getRow($sectionKey, (int)$id);
        if (!$row) {
            header('Location: ' . URLROOT . '/AdminAbout/index/' . $sectionKey . '?error=notfound');
            exit();
        }

        $this->renderForm($sectionKey, $section, 'edit', $row);
    }

    public function update($sectionKey = 'company-images', $id = null)
    {
        $section = $this->sectionOrRedirect($sectionKey);
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            header('Location: ' . URLROOT . '/AdminAbout/edit/' . $sectionKey . '/' . (int)$id);
            exit();
        }

        $row = $this->aboutAdminModel->getRow($sectionKey, (int)$id);
        if (!$row) {
            header('Location: ' . URLROOT . '/AdminAbout/index/' . $sectionKey . '?error=notfound');
            exit();
        }

        [$data, $errors] = $this->collectInput($section, $row);
        $this->applyFileUploads($section, $data, $errors);
        if (!empty($errors)) {
            $rowData = array_merge([$section['primaryKey'] => (int)$id], $data);
            $this->renderForm($sectionKey, $section, 'edit', (object)$rowData, $errors);
            return;
        }

        $success = $this->aboutAdminModel->update($sectionKey, (int)$id, $data);
        header('Location: ' . URLROOT . '/AdminAbout/index/' . $sectionKey . ($success ? '?success=updated' : '?error=update'));
        exit();
    }

    public function delete($sectionKey = 'company-images', $id = null)
    {
        $this->sectionOrRedirect($sectionKey);
        $success = $this->aboutAdminModel->delete($sectionKey, (int)$id);
        header('Location: ' . URLROOT . '/AdminAbout/index/' . $sectionKey . ($success ? '?success=deleted' : '?error=delete'));
        exit();
    }

    private function renderForm($sectionKey, array $section, $mode, $row, array $errors = [])
    {
        $this->view('admin/about/form', [
            'title' => ($mode === 'edit' ? 'Cập nhật ' : 'Thêm ') . $section['label'],
            'sections' => $this->aboutAdminModel->getSections(),
            'sectionKey' => $sectionKey,
            'section' => $section,
            'mode' => $mode,
            'row' => $row,
            'errors' => $errors,
        ]);
    }

    private function sectionOrRedirect($sectionKey)
    {
        $section = $this->aboutAdminModel->getSection($sectionKey);
        if (!$section) {
            header('Location: ' . URLROOT . '/AdminAbout?error=section');
            exit();
        }
        return $section;
    }

    private function defaultValues(array $section)
    {
        $data = [];
        foreach ($section['fields'] as $fieldName => $field) {
            $data[$fieldName] = $field['default'] ?? '';
        }
        return $data;
    }

    private function collectFilters(array $section)
    {
        $filters = [
            'q' => $this->cleanFilter($_GET['q'] ?? ''),
            'status' => $this->cleanFilter($_GET['status'] ?? ''),
        ];

        $statusOptions = $section['fields']['status']['options'] ?? [];
        if ($filters['status'] !== '' && !array_key_exists($filters['status'], $statusOptions)) {
            $filters['status'] = '';
        }

        return $filters;
    }

    private function cleanFilter($value)
    {
        $value = is_array($value) ? '' : (string)$value;
        return trim(preg_replace('/\s+/', ' ', strip_tags($value)));
    }

    private function collectInput(array $section, $row = null)
    {
        $data = [];
        $errors = [];

        foreach ($section['fields'] as $fieldName => $field) {
            $type = $field['type'] ?? 'text';
            if ($type === 'file') {
                $currentValue = $_POST[$fieldName . '_current'] ?? ($row->{$fieldName} ?? ($field['default'] ?? ''));
                $data[$fieldName] = $this->cleanPath($currentValue);
                continue;
            }

            $value = $_POST[$fieldName] ?? ($field['default'] ?? '');
            $value = is_array($value) ? '' : trim((string)$value);

            if ($type === 'tel') {
                $value = preg_replace('/\D+/', '', $value);
            }

            if ($type === 'number') {
                $value = $value === '' ? (int)($field['default'] ?? 0) : (int)$value;
                if (isset($field['min'])) {
                    $value = max((int)$field['min'], $value);
                }
                if (isset($field['max'])) {
                    $value = min((int)$field['max'], $value);
                }
            }

            if ($type === 'select') {
                $options = $field['options'] ?? [];
                if (!array_key_exists($value, $options)) {
                    $optionKeys = array_keys($options);
                    $value = $field['default'] ?? ($optionKeys[0] ?? '');
                }
            }

            if (!empty($field['required']) && $value === '') {
                $errors[] = 'Vui lòng nhập ' . $field['label'] . '.';
            }

            if ($value !== '' && $type === 'email' && !filter_var($value, FILTER_VALIDATE_EMAIL)) {
                $errors[] = 'Email chưa đúng định dạng.';
            }

            if ($value !== '' && $type === 'tel' && !preg_match('/^[0-9]{10}$/', $value)) {
                $errors[] = 'Số điện thoại phải gồm đúng 10 số.';
            }

            $data[$fieldName] = $value;
        }

        return [$data, $errors];
    }

    private function applyFileUploads(array $section, array &$data, array &$errors)
    {
        foreach ($section['fields'] as $fieldName => $field) {
            if (($field['type'] ?? 'text') !== 'file') {
                continue;
            }

            $file = $_FILES[$fieldName] ?? null;
            $hasUpload = is_array($file) && ($file['error'] ?? UPLOAD_ERR_NO_FILE) !== UPLOAD_ERR_NO_FILE;

            if (!$hasUpload) {
                if (!empty($field['required']) && empty($data[$fieldName])) {
                    $errors[] = 'Vui lòng chọn ' . $field['label'] . '.';
                }
                continue;
            }

            if (($file['error'] ?? UPLOAD_ERR_OK) !== UPLOAD_ERR_OK) {
                $errors[] = 'Không thể tải lên ' . $field['label'] . '.';
                continue;
            }

            $maxSize = (int)($field['maxSize'] ?? (100 * 1024 * 1024));
            if (($file['size'] ?? 0) > $maxSize) {
                $errors[] = $field['label'] . ' không được vượt quá ' . (int)round($maxSize / 1024 / 1024) . 'MB.';
                continue;
            }

            $tmpName = (string)($file['tmp_name'] ?? '');
            $fileKind = $field['fileKind'] ?? 'image';
            $uploadDir = trim((string)($field['uploadDir'] ?? 'about'), "/\\");
            $uploadDir = $uploadDir !== '' ? $uploadDir : 'about';
            $imageInfo = $fileKind === 'document' ? ['mime' => 'application/octet-stream'] : ($tmpName !== '' ? @getimagesize($tmpName) : false);
            if ($fileKind !== 'document' && $imageInfo === false) {
                $errors[] = $field['label'] . ' phải là file hình ảnh hợp lệ.';
                continue;
            }

            $extension = strtolower(pathinfo((string)($file['name'] ?? ''), PATHINFO_EXTENSION));
            $allowedExtensions = $fileKind === 'document' ? ($field['allowedExtensions'] ?? ['pdf', 'doc', 'docx']) : ['jpg', 'jpeg', 'png', 'gif', 'webp'];
            $allowedMimes = ['image/jpeg', 'image/png', 'image/gif', 'image/webp'];
            if ($fileKind === 'document' && !in_array($extension, $allowedExtensions, true)) {
                $errors[] = $field['label'] . ' chỉ chấp nhận file PDF, DOC hoặc DOCX.';
                continue;
            }
            if ($fileKind !== 'document' && (!in_array($extension, $allowedExtensions, true) || !in_array((string)($imageInfo['mime'] ?? ''), $allowedMimes, true))) {
                $errors[] = $field['label'] . ' chỉ hỗ trợ JPG, PNG, GIF hoặc WEBP.';
                continue;
            }

            $targetDir = APPROOT . DIRECTORY_SEPARATOR . 'public' . DIRECTORY_SEPARATOR . 'assets' . DIRECTORY_SEPARATOR . 'uploads' . DIRECTORY_SEPARATOR . $uploadDir;
            if (!is_dir($targetDir) && !mkdir($targetDir, 0777, true)) {
                $errors[] = 'Không thể tạo thư mục upload hình ảnh.';
                continue;
            }

            $fileName = $uploadDir . '_' . date('YmdHis') . '_' . bin2hex(random_bytes(4)) . '.' . $extension;
            $targetPath = $targetDir . DIRECTORY_SEPARATOR . $fileName;
            if (!move_uploaded_file($tmpName, $targetPath)) {
                $errors[] = 'Không thể lưu ' . $field['label'] . ' lên server.';
                continue;
            }

            $pathPrefix = $field['pathPrefix'] ?? ('assets/uploads/' . $uploadDir . '/');
            $data[$fieldName] = rtrim((string)$pathPrefix, '/') . '/' . $fileName;
        }
    }

    private function cleanPath($value)
    {
        $value = is_array($value) ? '' : (string)$value;
        $value = trim(preg_replace('/\s+/', ' ', strip_tags($value)));
        if ($value !== '' && str_starts_with($value, URLROOT . '/')) {
            return ltrim(substr($value, strlen(URLROOT)), '/');
        }
        if (preg_match('/^https?:\/\//i', $value)) {
            return '';
        }
        return $value;
    }
}
