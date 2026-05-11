<?php
class Dashboard extends Controller {
    private $productModel;
    private $userModel;

    public function __construct() {
        parent::__construct();
        $this->requireAdmin(); // Chặn user bình thường, chỉ admin mới được vào
        $this->productModel = $this->model('ProductModel');
        $this->userModel = $this->model('UserModel');
    }

    public function index() {
        $totalProducts = $this->productModel->getTotalProducts();
        $totalUsers = $this->userModel->getTotalUsers();
        $recentProducts = $this->productModel->getProducts([], 5, 0); // Lấy 5 sản phẩm mới nhất

        $this->view('admin/dashboard/index', [
            'totalProducts' => $totalProducts,
            'totalUsers' => $totalUsers,
            'totalOrders' => 0, // Hiện tại hệ thống chưa có tính năng đơn hàng (Order) nên tạm để 0
            'recentProducts' => $recentProducts
        ]);
    }
}