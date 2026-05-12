<?php
class Dashboard extends Controller {
    private $productModel;
    private $userModel;
    private $orderModel;

    public function __construct() {
        parent::__construct();
        $this->requireAdmin(); // Chặn user bình thường, chỉ admin mới được vào
        $this->productModel = $this->model('ProductModel');
        $this->userModel = $this->model('UserModel');
        $this->orderModel = $this->model('OrderModel');
    }

    public function index() {
        $totalProducts = $this->productModel->getTotalProducts();
        $totalUsers = $this->userModel->getTotalUsers();
        $recentProducts = $this->productModel->getProducts([], 5, 0); // Lấy 5 sản phẩm mới nhất

        $totalOrders = $this->orderModel->getTotalOrders('', ''); // Lấy tổng tất cả đơn hàng
        $recentOrders = $this->orderModel->getAllOrdersPaginated(5, 0, '', '', 'date_desc'); // Lấy 5 đơn mới nhất

        $this->view('admin/dashboard/index', [
            'title' => 'Tổng quan (Dashboard)',
            'totalProducts' => $totalProducts,
            'totalUsers' => $totalUsers,
            'totalOrders' => $totalOrders,
            'recentProducts' => $recentProducts,
            'recentOrders' => $recentOrders
        ]);
    }
}