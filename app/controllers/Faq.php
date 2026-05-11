<?php

class Faq extends Controller
{
    private $faqModel;

    public function __construct()
    {
        parent::__construct();
        $this->faqModel = $this->model('FaqModel');
        $this->faqModel->ensureTables();
    }

    public function index()
    {
        $data = [
            'faqs' => $this->faqModel->getActiveFaqs(),
            'form' => $_SESSION['faq_form'] ?? ['name' => '', 'email' => '', 'phone' => '', 'question' => ''],
            'errors' => $_SESSION['faq_errors'] ?? [],
            'success' => $_SESSION['faq_success'] ?? '',
        ];

        unset($_SESSION['faq_form'], $_SESSION['faq_errors'], $_SESSION['faq_success']);

        $this->view('client/Faq', $data);
    }

    public function submit()
    {
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            header('Location: ' . URLROOT . '/faq');
            exit();
        }

        $form = $this->collectForm();
        $errors = $this->validate($form);
        if (!empty($errors)) {
            $_SESSION['faq_form'] = $form;
            $_SESSION['faq_errors'] = $errors;
            header('Location: ' . URLROOT . '/faq#faq-question-form');
            exit();
        }

        if ($this->faqModel->addQuestionSubmission($form)) {
            $_SESSION['faq_success'] = 'Gửi câu hỏi thành công! Chúng tôi sẽ trả lời bạn qua email hoặc điện thoại trong thời gian sớm nhất.';
        } else {
            $_SESSION['faq_form'] = $form;
            $_SESSION['faq_errors'] = ['Không thể gửi câu hỏi lúc này. Vui lòng thử lại sau.'];
        }

        header('Location: ' . URLROOT . '/faq#faq-question-form');
        exit();
    }

    private function collectForm()
    {
        return [
            'name' => $this->cleanText($_POST['name'] ?? ''),
            'email' => strtolower($this->cleanText($_POST['email'] ?? '')),
            'phone' => $this->cleanText($_POST['phone'] ?? ''),
            'question' => $this->cleanText($_POST['question'] ?? '', true),
        ];
    }

    private function validate(array $form)
    {
        $errors = [];

        if ($form['name'] === '') {
            $errors[] = 'Vui lòng nhập họ và tên.';
        }

        if ($form['name'] !== '' && $this->textLength($form['name']) < 2) {
            $errors[] = 'Vui lòng nhập họ và tên từ 2 ký tự trở lên.';
        }

        if ($this->textLength($form['name']) > 120) {
            $errors[] = 'Họ và tên không được vượt quá 120 ký tự.';
        }

        if ($form['email'] === '' || !filter_var($form['email'], FILTER_VALIDATE_EMAIL)) {
            $errors[] = 'Vui lòng nhập email hợp lệ.';
        }

        if ($form['email'] !== '' && $this->textLength($form['email']) > 150) {
            $errors[] = 'Email không được vượt quá 150 ký tự.';
        }

        if ($form['question'] === '') {
            $errors[] = 'Vui lòng nhập câu hỏi.';
        }

        if ($form['question'] !== '' && $this->textLength($form['question']) < 10) {
            $errors[] = 'Vui lòng nhập câu hỏi từ 10 ký tự trở lên.';
        }

        if ($this->textLength($form['question']) > 2000) {
            $errors[] = 'Câu hỏi không được vượt quá 2000 ký tự.';
        }

        if ($form['phone'] !== '' && !preg_match('/^[0-9+\-\s().]{8,30}$/', $form['phone'])) {
            $errors[] = 'Số điện thoại chưa đúng định dạng.';
        }

        return $errors;
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

    private function textLength($value)
    {
        return function_exists('mb_strlen') ? mb_strlen((string)$value, 'UTF-8') : strlen((string)$value);
    }
}
