<?php

class AdminFaq extends Controller
{
    private $faqAdminModel;

    public function __construct()
    {
        parent::__construct();
        $this->requireAdmin();
        $this->faqAdminModel = $this->model('AdminFaqModel');
        $this->faqAdminModel->ensureTables();
    }

    public function index($sectionKey = 'faqs')
    {
        $section = $this->sectionOrRedirect($sectionKey);
        $filters = $this->collectFilters($section);
        $limit = 10;
        $totalRows = $this->faqAdminModel->countRows($sectionKey, $filters);
        $totalPages = max(1, (int)ceil($totalRows / $limit));
        $currentPage = isset($_GET['page']) ? (int)$_GET['page'] : 1;
        $currentPage = max(1, min($currentPage, $totalPages));
        $offset = ($currentPage - 1) * $limit;
        $rows = $this->faqAdminModel->getRows($sectionKey, $limit, $offset, $filters);

        $this->view('admin/faq/index', [
            'title' => 'Quản lý FAQ - ' . $section['label'],
            'sections' => $this->faqAdminModel->getSections(),
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

    public function create($sectionKey = 'faqs')
    {
        $section = $this->sectionOrRedirect($sectionKey);
        $this->renderForm($sectionKey, $section, 'create', (object)$this->defaultValues($section));
    }

    public function store($sectionKey = 'faqs')
    {
        $section = $this->sectionOrRedirect($sectionKey);
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            header('Location: ' . URLROOT . '/AdminFaq/create/' . $sectionKey);
            exit();
        }

        [$data, $errors] = $this->collectInput($sectionKey, $section);
        if (!empty($errors)) {
            $this->renderForm($sectionKey, $section, 'create', (object)$data, $errors);
            return;
        }

        $success = $this->faqAdminModel->create($sectionKey, $data);
        header('Location: ' . URLROOT . '/AdminFaq/index/' . $sectionKey . ($success ? '?success=created' : '?error=store'));
        exit();
    }

    public function edit($sectionKey = 'faqs', $id = null)
    {
        $section = $this->sectionOrRedirect($sectionKey);
        $row = $this->faqAdminModel->getRow($sectionKey, (int)$id);
        if (!$row) {
            header('Location: ' . URLROOT . '/AdminFaq/index/' . $sectionKey . '?error=notfound');
            exit();
        }

        $this->renderForm($sectionKey, $section, 'edit', $row);
    }

    public function update($sectionKey = 'faqs', $id = null)
    {
        $section = $this->sectionOrRedirect($sectionKey);
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            header('Location: ' . URLROOT . '/AdminFaq/edit/' . $sectionKey . '/' . (int)$id);
            exit();
        }

        [$data, $errors] = $this->collectInput($sectionKey, $section);
        if (!empty($errors)) {
            $rowData = array_merge([$section['primaryKey'] => (int)$id], $data);
            $this->renderForm($sectionKey, $section, 'edit', (object)$rowData, $errors);
            return;
        }

        $success = $this->faqAdminModel->update($sectionKey, (int)$id, $data);
        header('Location: ' . URLROOT . '/AdminFaq/index/' . $sectionKey . ($success ? '?success=updated' : '?error=update'));
        exit();
    }

    public function delete($sectionKey = 'faqs', $id = null)
    {
        $this->sectionOrRedirect($sectionKey);
        $success = $this->faqAdminModel->delete($sectionKey, (int)$id);
        header('Location: ' . URLROOT . '/AdminFaq/index/' . $sectionKey . ($success ? '?success=deleted' : '?error=delete'));
        exit();
    }

    private function renderForm($sectionKey, array $section, $mode, $row, array $errors = [])
    {
        $this->view('admin/faq/form', [
            'title' => ($mode === 'edit' ? 'Cập nhật ' : 'Thêm ') . $section['label'],
            'sections' => $this->faqAdminModel->getSections(),
            'sectionKey' => $sectionKey,
            'section' => $section,
            'mode' => $mode,
            'row' => $row,
            'errors' => $errors,
        ]);
    }

    private function sectionOrRedirect($sectionKey)
    {
        $section = $this->faqAdminModel->getSection($sectionKey);
        if (!$section) {
            header('Location: ' . URLROOT . '/AdminFaq?error=section');
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

    private function collectInput($sectionKey, array $section)
    {
        $data = [];
        $errors = [];

        foreach ($section['fields'] as $fieldName => $field) {
            $type = $field['type'] ?? 'text';
            $value = $_POST[$fieldName] ?? ($field['default'] ?? '');
            $value = is_array($value) ? '' : (string)$value;

            if ($type === 'textarea') {
                $value = $this->cleanText($value, true);
            } elseif ($type === 'datetime-local') {
                $value = $this->normalizeDateTime($value, $field['label'], $errors);
            } elseif ($type === 'number') {
                $value = $value === '' ? (int)($field['default'] ?? 0) : (int)$value;
                if (isset($field['min'])) {
                    $value = max((int)$field['min'], $value);
                }
                if (isset($field['max'])) {
                    $value = min((int)$field['max'], $value);
                }
            } else {
                $value = $this->cleanText($value);
            }

            if ($type === 'select') {
                $options = $field['options'] ?? [];
                if (!array_key_exists((string)$value, $options)) {
                    $optionKeys = array_keys($options);
                    $value = $field['default'] ?? ($optionKeys[0] ?? '');
                }
            }

            if (!empty($field['required']) && ($value === '' || $value === null)) {
                $errors[] = 'Vui lòng nhập ' . $field['label'] . '.';
            }

            if ($value !== '' && $value !== null && isset($field['minLength']) && $this->textLength($value) < (int)$field['minLength']) {
                $errors[] = $field['label'] . ' phải có ít nhất ' . (int)$field['minLength'] . ' ký tự.';
            }

            if ($value !== '' && $value !== null && isset($field['maxLength']) && $this->textLength($value) > (int)$field['maxLength']) {
                $errors[] = $field['label'] . ' không được vượt quá ' . (int)$field['maxLength'] . ' ký tự.';
            }

            if ($type === 'email' && $value !== '' && !filter_var($value, FILTER_VALIDATE_EMAIL)) {
                $errors[] = 'Email chưa đúng định dạng.';
            }

            if ($type === 'tel' && $value !== '' && !preg_match('/^[0-9+\-\s().]{10}$/', $value)) {
                $errors[] = 'Số điện thoại chưa đúng định dạng.';
            }

            $data[$fieldName] = $value === '' && $type === 'datetime-local' ? null : $value;
        }

        if ($sectionKey === 'submissions' && ($data['status'] ?? '') === 'answered' && empty($data['answered_at'])) {
            $data['answered_at'] = date('Y-m-d H:i:s');
        }

        return [$data, $errors];
    }

    private function cleanText($value, $multiline = false)
    {
        $value = str_replace(["\r\n", "\r"], "\n", trim(strip_tags((string)$value)));
        if ($multiline) {
            $value = preg_replace("/[ \t]+/", ' ', $value);
            $value = preg_replace("/\n{3,}/", "\n\n", $value);
            return trim($value);
        }

        return trim(preg_replace('/\s+/', ' ', $value));
    }

    private function normalizeDateTime($value, $label, array &$errors)
    {
        $value = trim((string)$value);
        if ($value === '') {
            return null;
        }

        $date = DateTime::createFromFormat('Y-m-d\TH:i', $value);
        if (!$date) {
            $date = DateTime::createFromFormat('Y-m-d H:i:s', $value);
        }

        if (!$date) {
            $errors[] = $label . ' chưa đúng định dạng.';
            return null;
        }

        return $date->format('Y-m-d H:i:s');
    }

    private function textLength($value)
    {
        return function_exists('mb_strlen') ? mb_strlen((string)$value, 'UTF-8') : strlen((string)$value);
    }
}
