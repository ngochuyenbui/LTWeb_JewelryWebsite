<?php
class Orders extends Controller {
    private $orderModel;

    public function __construct() {
        parent::__construct();
        if (!isset($_SESSION['user_id']) || ($_SESSION['user_role'] ?? 'member') !== 'member') {
            header('Location: ' . URLROOT . '/Login');
            exit;
        }
        $this->orderModel = $this->model('OrderModel');
    }

    public function index() {
        $userId = $_SESSION['user_id'];

        $limit = 5;
        $page = isset($_GET['page']) ? max(1, (int)$_GET['page']) : 1;
        $offset = ($page - 1) * $limit;

        $totalItems = $this->orderModel->getTotalOrdersByUserId($userId);
        $totalPages = ceil($totalItems / $limit);

        $orders = $this->orderModel->getOrdersByUserIdPaginated($userId, $limit, $offset);

        // Lấy chi tiết từng sản phẩm cho mỗi đơn hàng
        foreach ($orders as $key => $order) {
            $orderId = is_object($order) ? $order->orderId : $order['orderId'];
            $items = $this->orderModel->getOrderItems($orderId);

            if (is_object($orders[$key])) {
                $orders[$key]->items = $items;
            } else {
                $orders[$key]['items'] = $items;
            }
        }

        $this->view('client/orders/index', [
            'title' => 'Đơn hàng',
            'orders' => $orders,
            'currentPage' => $page,
            'totalPages' => $totalPages,
            'totalItems' => $totalItems
        ]);
    }
}