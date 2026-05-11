<?php
class Orders extends Controller {
    private $orderModel;
    private $productModel;

    public function __construct() {
        parent::__construct();
        $this->requireAdmin();
        $this->orderModel = $this->model('OrderModel');
        $this->productModel = $this->model('ProductModel');
    }

    public function index() {
        $limit = 15;
        $page = isset($_GET['page']) ? max(1, (int)$_GET['page']) : 1;
        $offset = ($page - 1) * $limit;

        $status = $_GET['status'] ?? '';
        $payment = $_GET['payment'] ?? '';
        $sortBy = $_GET['sort_by'] ?? 'date_desc';

        $orders = $this->orderModel->getAllOrdersPaginated($limit, $offset, $status, $payment, $sortBy);
        $totalItems = $this->orderModel->getTotalOrders($status, $payment);
        $totalPages = ceil($totalItems / $limit);

        // Tính tổng tiền cho mỗi đơn hàng để hiển thị
        foreach ($orders as $order) {
            if (!is_object($order)) continue;
            $items = $this->orderModel->getOrderItems($order->orderId);
            $totalPrice = 0;
            foreach ($items as $item) {
                $totalPrice += (is_object($item) ? $item->purchase_price : $item['purchase_price']) * (is_object($item) ? $item->quantity : $item['quantity']);
            }
            $shippingFee = ($totalPrice >= 5000000 || $totalPrice == 0) ? 0 : 100000;
            $order->totalAmount = $totalPrice + $shippingFee;
        }

        $this->view('admin/orders/index', [
            'orders' => $orders,
            'currentPage' => $page,
            'totalPages' => $totalPages,
            'filters' => [
                'status' => $status,
                'payment' => $payment,
                'sort_by' => $sortBy
            ]
        ]);
    }

    public function detail($orderId = null) {
        if (!$orderId) {
            header('Location: ' . URLROOT . '/admin/Orders');
            exit;
        }

        $order = $this->orderModel->getOrderDetails($orderId);
        if (!$order) {
            header('Location: ' . URLROOT . '/admin/Orders');
            exit;
        }
        if (is_object($order)) $order = (array)$order;

        $items = $this->orderModel->getOrderItems($orderId);
        
        $totalPrice = 0;
        foreach ($items as $item) {
            $totalPrice += (is_object($item) ? $item->purchase_price : $item['purchase_price']) * (is_object($item) ? $item->quantity : $item['quantity']);
        }
        $shippingFee = ($totalPrice >= 5000000 || $totalPrice == 0) ? 0 : 100000;
        $finalTotal = $totalPrice + $shippingFee;

        $this->view('admin/orders/detail', [
            'order' => $order,
            'items' => $items,
            'totalPrice' => $totalPrice,
            'shippingFee' => $shippingFee,
            'finalTotal' => $finalTotal,
        ]);
    }

    public function updateStatus($orderId = null) {
        if (!$orderId || $_SERVER['REQUEST_METHOD'] !== 'POST') {
            header('Location: ' . URLROOT . '/admin/Orders');
            exit;
        }

        $newStatus = $_POST['status'] ?? '';
        $order = $this->orderModel->getOrderDetails($orderId);

        if ($order && $newStatus) {
            if (is_object($order)) $order = (array)$order;
            
            // Ngăn chặn cập nhật nếu trạng thái hiện tại đã là delivered hoặc cancelled
            if (in_array($order['status'], ['delivered', 'cancelled'])) {
                $_SESSION['error'] = 'Đơn hàng đã chốt trạng thái, không thể thay đổi!';
                header('Location: ' . URLROOT . '/admin/Orders/detail/' . $orderId);
                exit;
            }

            // LOGIC HOÀN KHO: Nếu trạng thái mới là "cancelled"
            if ($newStatus === 'cancelled' && $order['status'] !== 'cancelled') {
                $orderItems = $this->orderModel->getOrderItems($orderId);
                foreach ($orderItems as $item) {
                    if (is_object($item)) $item = (array)$item;
                    // Hoàn lại số lượng
                    $this->productModel->increaseStock($item['productId'], $item['quantity']);
                }
            }

            // Logic cộng điểm thưởng khi đơn hàng hoàn tất
            if ($newStatus === 'delivered' && $order['status'] !== 'delivered') {
                $items = $this->orderModel->getOrderItems($orderId);
                $totalPrice = 0;
                foreach ($items as $item) {
                    if (is_object($item)) $item = (array)$item;
                    $totalPrice += $item['purchase_price'] * $item['quantity'];
                }
                $shippingFee = ($totalPrice >= 5000000 || $totalPrice == 0) ? 0 : 100000;
                $finalTotal = $totalPrice + $shippingFee;
                $rewardPoints = floor($finalTotal / 100000);

                if ($rewardPoints > 0) {
                    $this->orderModel->addRewardPoints($order['memberId'], $rewardPoints);
                }
            }

            $this->orderModel->updateOrderStatus($orderId, $newStatus);
            $_SESSION['success'] = 'Cập nhật trạng thái đơn hàng thành công!';
        } else {
            $_SESSION['error'] = 'Có lỗi xảy ra.';
        }

        header('Location: ' . URLROOT . '/admin/Orders/detail/' . $orderId);
        exit;
    }
}