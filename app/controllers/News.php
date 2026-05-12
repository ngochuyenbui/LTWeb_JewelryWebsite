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

    // Trang danh sách bài viết (Hiển thị theo danh mục hoặc tìm kiếm)
    public function index() {
        $searchKeyword = isset($_GET['search']) ? trim($_GET['search']) : '';
        
        // Nếu có tìm kiếm thì hiện list phẳng
        if (!empty($searchKeyword)) {
            $limit = 6;
            $page = isset($_GET['page']) ? (int)$_GET['page'] : 1;
            if ($page < 1) $page = 1;
            $offset = ($page - 1) * $limit;

            $articles = $this->articleModel->getAllArticles($limit, $offset, $searchKeyword);
            $totalArticles = $this->articleModel->getTotalArticles($searchKeyword);
            $totalPages = ceil($totalArticles / $limit);

            $data = [
                'title' => 'Kết quả tìm kiếm: ' . $searchKeyword,
                'articles' => $articles,
                'currentPage' => $page,
                'totalPages' => $totalPages,
                'searchKeyword' => $searchKeyword,
                'isGrouped' => false
            ];
        } else {
            // Không tìm kiếm -> Hiện theo nhóm danh mục
            $categories = $this->articleModel->getAllCategories();
            $groupedArticles = [];
            
            foreach ($categories as $cat) {
                // Lấy 3 bài mới nhất mỗi danh mục
                $catArticles = $this->articleModel->getArticlesByCategory($cat->cateId, 3, 0);
                if (!empty($catArticles)) {
                    $groupedArticles[] = [
                        'category' => $cat,
                        'articles' => $catArticles
                    ];
                }
            }
            
            $data = [
                'title' => 'Tin tức & Bài viết',
                'groupedArticles' => $groupedArticles,
                'searchKeyword' => '',
                'isGrouped' => true
            ];
        }

        $this->view('client/article/index', $data);
    }

    // Trang xem tất cả bài viết của 1 danh mục
    public function category($cateId) {
        $limit = 6;
        $page = isset($_GET['page']) ? (int)$_GET['page'] : 1;
        if ($page < 1) $page = 1;
        $offset = ($page - 1) * $limit;

        // Lấy thông tin danh mục từ DB (để lấy tên). Giả lập lấy tên từ 1 bài đầu tiên hoặc viết hàm lấy Category by Id
        // Vì chưa có model Category nên tạm thời lấy tên từ bài đầu tiên
        $articles = $this->articleModel->getArticlesByCategory($cateId, $limit, $offset);
        $totalArticles = $this->articleModel->getTotalArticlesByCategory($cateId);
        $totalPages = ceil($totalArticles / $limit);
        
        $categoryName = (!empty($articles)) ? $articles[0]->category_name : 'Danh mục';

        $data = [
            'title' => 'Chuyên mục: ' . $categoryName,
            'articles' => $articles,
            'currentPage' => $page,
            'totalPages' => $totalPages,
            'searchKeyword' => '',
            'isGrouped' => false,
            'cateId' => $cateId
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

        // Lấy bài viết liên quan
        $relatedArticles = $this->articleModel->getRelatedArticles($article->cateId, $article->articleId, 3);

        $data = [
            'title' => $article->title,
            'article' => $article,
            'comments' => $comments,
            'relatedArticles' => $relatedArticles
        ];

        $this->view('client/article/detail', $data);
    }

    // Xử lý gửi bình luận
    public function postComment() {
        if ($_SERVER['REQUEST_METHOD'] == 'POST') {
            // Kiểm tra đăng nhập
            if (!isset($_SESSION['user_id'])) {
                header("Location: " . URLROOT . "/Login");
                exit();
            }

            $articleId = (int)$_POST['articleId'];
            $contentId = (int)$_POST['contentId'];
            $rating = (int)$_POST['rating'];
            $content = trim($_POST['content']);

            $memberId = $_SESSION['user_id'];
            $isAdmin = isset($_SESSION['user_role']) && $_SESSION['user_role'] === 'admin';
            
            // Fix lỗi Foreign Key: Nếu là Admin, ta đặt memberId = null 
            // vì ID của Admin không tồn tại trong bảng 'member'
            if ($isAdmin) {
                $memberId = null;
            }
            
            if (strlen($content) < 5) {
                header("Location: " . URLROOT . "/News/detail/" . $articleId . "?error=length");
                exit();
            }

            $data = [
                'contentId' => $contentId,
                'memberId' => $memberId,
                'guest_name' => $isAdmin ? ($_SESSION['username'] . ' (Admin)') : null,
                'guest_email' => null,
                'content' => $content,
                'rating' => $rating,
                'status' => 'approved'
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
