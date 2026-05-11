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
        $orders = $this->orderModel->getOrdersByUserId($userId);
        
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

        $this->view('client/orders/index', ['orders' => $orders]);
    }
}