<?php
class Carts extends Controller {
    private $cartModel;

    public function __construct() {
        parent::__construct();
        $this->requireAdmin();
        $this->cartModel = $this->model('CartModel');
    }

    public function index() {
        $limit = 10;
        $page = isset($_GET['page']) ? max(1, (int)$_GET['page']) : 1;
        $offset = ($page - 1) * $limit;

        $carts = $this->cartModel->getAllCartsPaginated($limit, $offset);
        $totalItems = $this->cartModel->getTotalActiveCarts();
        $totalPages = ceil($totalItems / $limit);

        foreach ($carts as $cart) {
            if (!is_object($cart)) continue;
            $cart->items = $this->cartModel->getItemsForCart($cart->cartId);
            $cart->totalValue = 0;
            if (is_array($cart->items)) {
                foreach ($cart->items as $item) {
                    $price = is_object($item) ? $item->price : $item['price'];
                    $quantity = is_object($item) ? $item->quantity : $item['quantity'];
                    $cart->totalValue += $price * $quantity;
                }
            }
        }

        $this->view('admin/carts/index', [
            'carts' => $carts,
            'currentPage' => $page,
            'totalPages' => $totalPages
        ]);
    }
}