<?php
class Products extends Controller {
    private $productModel;
    private $commentModel;

    public function __construct() {
        parent::__construct();
        $this->productModel = $this->model('ProductModel');
        $this->commentModel = $this->model('CommentModel');
    }

    public function index() {
        $categories = $this->productModel->getCategories();
        $sizes = $this->productModel->getSizes();
        $colors = $this->productModel->getColors();
        $filters = [
            'search' => isset($_GET['search']) ? trim($_GET['search']) : null,
            'category' => $_GET['category'] ?? null,
            'min_price' => $_GET['min_price'] ?? null,
            'max_price' => $_GET['max_price'] ?? null,
            'size' => $_GET['size'] ?? [],
            'color' => $_GET['color'] ?? [],
            'sort' => $_GET['sort'] ?? 'default'
        ];

        // Pagination logic
        $limit = 6; // 3 cột * 3 hàng
        $page = isset($_GET['page']) ? max(1, (int)$_GET['page']) : 1;
        $offset = ($page - 1) * $limit;

        $totalItems = $this->productModel->getTotalProducts($filters);
        $totalPages = ceil($totalItems / $limit);

        $products = $this->productModel->getProducts($filters, $limit, $offset);
        $priceRange = $this->productModel->getPriceRange();

        $data = [
            'title' => 'Sản phẩm',
            'categories' => $categories,
            'sizes' => $sizes,
            'colors' => $colors,
            'products' => $products,
            'currentPage' => $page,
            'totalPages' => $totalPages,
            'priceRange' => $priceRange
        ];
        $this->view('client/product/Products', $data);     
    }
    
    public function detail($id = null) {
        if (!$id) {
            header('Location: ' . URLROOT . '/client/Products');
            exit;
        }
        
        $product = $this->productModel->getProductById($id);
        if (!$product) {
            die('Không tìm thấy sản phẩm này.');
        }
        
        // Lấy danh sách ảnh từ bảng product_image
        $images = $this->productModel->getProductImages($id);
        
        // Lấy sản phẩm liên quan (cùng danh mục)
        $cateId = is_object($product) ? ($product->cateId ?? 0) : ($product['cateId'] ?? 0);
        $contentId = is_object($product) ? ($product->contentId ?? 0) : ($product['contentId'] ?? 0);
        $relatedProducts = $this->productModel->getRelatedProducts($cateId, $id);
        
        // Lấy danh sách bình luận
        $comments = $this->commentModel->getCommentsByContentId($contentId);

        $this->view('client/product/ProductDetail', [
            'title' => 'Sản phẩm',
            'product' => $product,
            'images' => $images,
            'relatedProducts' => $relatedProducts,
            'comments' => $comments
        ]);
    }

}