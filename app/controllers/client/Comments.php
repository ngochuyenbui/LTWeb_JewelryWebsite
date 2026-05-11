<?php
class Comments extends Controller {
    private $commentModel;

    public function __construct() {
        parent::__construct();
        $this->commentModel = $this->model('CommentModel');
    }

    public function add() {
        if ($_SERVER['REQUEST_METHOD'] == 'POST') {
            $referer = $_SERVER['HTTP_REFERER'] ?? '/';

            if (!isset($_SESSION['user_id'])) {
                header('Location: ' . $referer);
                exit;
            }
            
            $data = [
                'contentId' => $_POST['contentId'] ?? '',
                'userId' => $_SESSION['user_id'],
                'content' => trim($_POST['content'] ?? ''),
                'rating' => (int)($_POST['rating'] ?? 5)
            ];

            if (!empty($data['contentId']) && !empty($data['content'])) {
                $this->commentModel->addComment($data);
            }
            
            header('Location: ' . $referer);
            exit;
        }
    }
}