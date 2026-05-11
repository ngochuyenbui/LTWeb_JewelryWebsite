<?php

class About extends Controller {
    private $aboutModel;

    public function __construct() {
        parent::__construct();
        $this->aboutModel = $this->model('AboutModel');
        $this->aboutModel->ensureJobApplicationTable();
    }

    public function index() {
        $this->view('client/About', [
            'companyImages' => $this->aboutModel->getCompanyImages(),
            'testimonials' => $this->aboutModel->getTestimonials(),
            'branches' => $this->aboutModel->getBranches(),
            'jobs' => $this->aboutModel->getJobs(),
            'stats' => $this->aboutModel->getStats(),
            'values' => $this->aboutModel->getValues(),
        ]);
    }

    public function apply() {
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            $this->jsonResponse(false, ['general' => 'Phương thức không hợp lệ.'], 405);
            return;
        }

        $data = [
            'fullname' => $this->cleanText($_POST['fullname'] ?? ''),
            'email' => $this->cleanText($_POST['email'] ?? ''),
            'phone' => preg_replace('/\D+/', '', (string)($_POST['phone'] ?? '')),
            'position' => $this->cleanText($_POST['position'] ?? ''),
            'location' => $this->cleanText($_POST['location'] ?? ''),
            'cover_letter' => $this->cleanText($_POST['cover_letter'] ?? '', true),
        ];

        $errors = $this->validateApplication($data);
        $cvPath = $this->handleCvUpload($errors);

        if (!empty($errors)) {
            $this->jsonResponse(false, $errors, 422);
            return;
        }

        $data['cv_path'] = $cvPath;
        if (!$this->aboutModel->saveJobApplication($data)) {
            $this->jsonResponse(false, ['general' => 'Không thể lưu hồ sơ. Vui lòng thử lại.'], 500);
            return;
        }

        $this->jsonResponse(true, [], 200, 'Hồ sơ của bạn đã được gửi thành công.');
    }

    private function validateApplication(array $data): array {
        $errors = [];

        if ($data['fullname'] === '') {
            $errors['fullname'] = 'Vui lòng nhập họ và tên.';
        }

        if ($data['email'] === '' || !filter_var($data['email'], FILTER_VALIDATE_EMAIL)) {
            $errors['email'] = 'Email chưa đúng định dạng.';
        }

        if (!preg_match('/^[0-9]{10}$/', $data['phone'])) {
            $errors['phone'] = 'Số điện thoại phải gồm đúng 10 số.';
        }

        if ($data['position'] === '') {
            $errors['position'] = 'Vui lòng chọn vị trí ứng tuyển.';
        }

        return $errors;
    }

    private function handleCvUpload(array &$errors): string {
        $file = $_FILES['cv_file'] ?? null;
        if (!is_array($file) || ($file['error'] ?? UPLOAD_ERR_NO_FILE) === UPLOAD_ERR_NO_FILE) {
            $errors['cv_file'] = 'Vui lòng upload CV.';
            return '';
        }

        if (($file['error'] ?? UPLOAD_ERR_OK) !== UPLOAD_ERR_OK) {
            $errors['cv_file'] = 'Không thể upload CV. Vui lòng thử lại.';
            return '';
        }

        $extension = strtolower(pathinfo((string)($file['name'] ?? ''), PATHINFO_EXTENSION));
        if (!in_array($extension, ['pdf', 'doc', 'docx'], true)) {
            $errors['cv_file'] = 'CV chỉ chấp nhận file pdf, doc hoặc docx.';
            return '';
        }

        $targetDir = APPROOT . DIRECTORY_SEPARATOR . 'public' . DIRECTORY_SEPARATOR . 'assets' . DIRECTORY_SEPARATOR . 'uploads' . DIRECTORY_SEPARATOR . 'cv';
        if (!is_dir($targetDir) && !mkdir($targetDir, 0777, true)) {
            $errors['cv_file'] = 'Không thể tạo thư mục lưu CV.';
            return '';
        }

        $fileName = 'cv_' . date('YmdHis') . '_' . bin2hex(random_bytes(4)) . '.' . $extension;
        $targetPath = $targetDir . DIRECTORY_SEPARATOR . $fileName;
        if (!move_uploaded_file((string)($file['tmp_name'] ?? ''), $targetPath)) {
            $errors['cv_file'] = 'Không thể lưu CV lên server.';
            return '';
        }

        return 'assets/uploads/cv/' . $fileName;
    }

    private function cleanText($value, bool $multiline = false): string {
        $value = str_replace(["\r\n", "\r"], "\n", trim(strip_tags((string)$value)));
        if ($multiline) {
            $value = preg_replace("/[ \t]+/", ' ', $value);
            $value = preg_replace("/\n{3,}/", "\n\n", $value);
            return trim($value);
        }

        return trim(preg_replace('/\s+/', ' ', $value));
    }

    private function jsonResponse(bool $success, array $errors = [], int $status = 200, string $message = ''): void {
        http_response_code($status);
        header('Content-Type: application/json; charset=utf-8');
        echo json_encode([
            'success' => $success,
            'message' => $message,
            'errors' => $errors,
        ], JSON_UNESCAPED_UNICODE);
        exit();
    }
}
