<?php
// app/controllers/News.php

class News extends Controller {

    private $articleModel;
    private $commentModel;

    public function __construct() {
        parent::__construct();
        $this->articleModel = $this->model('ArticleModel');
        $this->commentModel = $this->model('CommentModel');
    }

    // Trang danh sách bài viết
    public function index() {
        $limit = 6; // Số bài viết mỗi trang
        $page = isset($_GET['page']) ? (int)$_GET['page'] : 1;
        if ($page < 1) $page = 1;
        $offset = ($page - 1) * $limit;

        $searchKeyword = isset($_GET['search']) ? trim($_GET['search']) : '';

        $articles = $this->articleModel->getAllArticles($limit, $offset, $searchKeyword);
        $totalArticles = $this->articleModel->getTotalArticles($searchKeyword);
        $totalPages = ceil($totalArticles / $limit);

        $data = [
            'title' => 'Tin tức & Bài viết',
            'articles' => $articles,
            'currentPage' => $page,
            'totalPages' => $totalPages,
            'searchKeyword' => $searchKeyword
        ];

        $this->view('client/article/index', $data);
    }

    // Trang đọc bài viết
    public function detail($id) {
        $article = $this->articleModel->getArticleById($id);
        if (!$article) {
            die("Bài viết không tồn tại!");
        }

        // Lấy bình luận đã duyệt
        $comments = $this->commentModel->getCommentsByContentId($article->contentId);

        $data = [
            'title' => $article->title,
            'article' => $article,
            'comments' => $comments
        ];

        $this->view('client/article/detail', $data);
    }

    // Xử lý gửi bình luận
    public function postComment() {
        if ($_SERVER['REQUEST_METHOD'] == 'POST') {
            $articleId = (int)$_POST['articleId'];
            $contentId = (int)$_POST['contentId'];
            $rating = (int)$_POST['rating'];
            $content = trim($_POST['content']);

            $guest_name = null;
            $guest_email = null;
            $memberId = null;

            if (isset($_SESSION['user_id'])) {
                $memberId = $_SESSION['user_id'];
            } else {
                $guest_name = trim($_POST['guest_name']);
                $guest_email = trim($_POST['guest_email']);
            }

            if (strlen($content) < 5) {
                header("Location: " . URLROOT . "/News/detail/" . $articleId . "?error=length");
                exit();
            }

            $data = [
                'contentId' => $contentId,
                'memberId' => $memberId,
                'guest_name' => $guest_name,
                'guest_email' => $guest_email,
                'content' => $content,
                'rating' => $rating,
                'status' => 'pending' // Chờ duyệt
            ];

            if ($this->commentModel->addComment($data)) {
                header("Location: " . URLROOT . "/News/detail/" . $articleId . "?success=1");
            } else {
                header("Location: " . URLROOT . "/News/detail/" . $articleId . "?error=1");
            }
            exit();
        }
    }
}
