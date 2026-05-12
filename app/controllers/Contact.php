<?php
class Contact extends Controller
{
    private $contactModel;
    private $siteContentModel;

    public function __construct()
    {
        parent::__construct();
        $this->contactModel = $this->model('ContactModel');
        $this->siteContentModel = $this->model('SiteContentModel');
        $managerId = (($_SESSION['user_role'] ?? null) === ROLE_ADMIN) ? ($_SESSION['user_id'] ?? null) : null;
        $this->siteContentModel->ensureDefaults($managerId);
    }

    public function index()
    {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $this->store();
            return;
        }

        $this->view('client/Contact', [
            'content' => $this->siteContentModel->getContentMap(),
            'images' => $this->siteContentModel->getImageMap(),
            'errors' => [],
            'old' => [],
            'success' => isset($_GET['success']),
        ]);
    }

    private function store()
    {
        $old = [
            'name' => trim($_POST['name'] ?? ''),
            'email' => trim($_POST['email'] ?? ''),
            'phone' => trim($_POST['phone'] ?? ''),
            'subject' => trim($_POST['subject'] ?? ''),
            'message' => trim($_POST['message'] ?? ''),
        ];

        $errors = $this->validate($old);

        if (!empty($errors)) {
            $this->view('client/Contact', [
                'content' => $this->siteContentModel->getContentMap(),
                'images' => $this->siteContentModel->getImageMap(),
                'errors' => $errors,
                'old' => $old,
                'success' => false,
            ]);
            return;
        }

        $old['memberId'] = (($_SESSION['user_role'] ?? null) === ROLE_USER) ? ($_SESSION['user_id'] ?? null) : null;

        if ($this->contactModel->create($old)) {
            header("Location: " . URLROOT . "/Contact?success=1");
            exit();
        }

        $this->view('client/Contact', [
            'content' => $this->siteContentModel->getContentMap(),
            'images' => $this->siteContentModel->getImageMap(),
            'errors' => ['form' => 'Không thể gửi liên hệ lúc này. Vui lòng thử lại sau.'],
            'old' => $old,
            'success' => false,
        ]);
    }

    private function validate($data)
    {
        $errors = [];

        if ($data['name'] === '' || mb_strlen($data['name'], 'UTF-8') < 2 || mb_strlen($data['name'], 'UTF-8') > 100) {
            $errors['name'] = 'Họ tên phải từ 2 đến 100 ký tự.';
        }

        if (!filter_var($data['email'], FILTER_VALIDATE_EMAIL) || strlen($data['email']) > 100) {
            $errors['email'] = 'Email không hợp lệ.';
        }

        if ($data['phone'] !== '' && !preg_match('/^[0-9+ .-]{8,15}$/', $data['phone'])) {
            $errors['phone'] = 'Số điện thoại không hợp lệ.';
        }

        if ($data['subject'] === '' || mb_strlen($data['subject'], 'UTF-8') > 200) {
            $errors['subject'] = 'Tiêu đề không được trống và tối đa 200 ký tự.';
        }

        if ($data['message'] === '' || mb_strlen($data['message'], 'UTF-8') < 10 || mb_strlen($data['message'], 'UTF-8') > 3000) {
            $errors['message'] = 'Nội dung phải từ 10 đến 3000 ký tự.';
        }

        return $errors;
    }
}
