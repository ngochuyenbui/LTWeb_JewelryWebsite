<?php
class Cart extends Controller {
    private $productModel;
    private $cartModel;

    public function __construct() {
        parent::__construct();
        $this->productModel = $this->model('ProductModel');
        $this->cartModel = $this->model('CartModel');
    }

    public function add() {
        header('Content-Type: application/json');
        if ($_SERVER['REQUEST_METHOD'] == 'POST') {
            $productId = isset($_POST['productId']) ? (int)$_POST['productId'] : 0;
            $quantity = isset($_POST['quantity']) ? (int)$_POST['quantity'] : 1;
            $size = isset($_POST['size']) ? trim($_POST['size']) : '';

            if ($productId <= 0 || $quantity <= 0) {
                echo json_encode(['success' => false, 'message' => 'Dữ liệu không hợp lệ.']);
                exit;
            }

            $product = $this->productModel->getProductById($productId);
            if (!$product) {
                echo json_encode(['success' => false, 'message' => 'Sản phẩm không tồn tại hoặc đã bị xóa.']);
                exit;
            }

            // Ép kiểu mảng thành object (vì hàm single() trả về mảng)
            if (is_array($product)) {
                $product = (object) $product;
            }

            if ($product->stock_quantity < $quantity) {
                echo json_encode(['success' => false, 'message' => 'Số lượng trong kho không đủ.']);
                exit;
            }

            $isLoggedIn = isset($_SESSION['user_id']) && ($_SESSION['user_role'] ?? $_SESSION['role'] ?? 'member') === 'member';
            
            // Gom nhóm sản phẩm theo ID và Size
            $cartKey = $productId . '_' . $size;

            if ($isLoggedIn) {
                try {
                    $userId = $_SESSION['user_id'];
                    $existingItem = $this->cartModel->getCartItem($userId, $productId, $size);
                    
                    if ($existingItem) {
                        $newQty = $existingItem['quantity'] + $quantity;
                        if ($newQty > $product->stock_quantity) {
                            echo json_encode(['success' => false, 'message' => 'Vượt quá số lượng tồn kho cho phép.']);
                            exit;
                        }
                        $this->cartModel->updateItemQuantity($userId, $productId, $size, $newQty);
                    } else {
                        $this->cartModel->addItem($userId, $productId, $size, $quantity);
                    }
                    
                    $totalItems = $this->cartModel->getTotalItems($userId);
                    $_SESSION['cart_total_items'] = $totalItems;
                } catch (PDOException $e) {
                    echo json_encode(['success' => false, 'message' => 'Lỗi CSDL (Có thể chưa tạo bảng cart): ' . $e->getMessage()]);
                    exit;
                }
            } else {
                if (!isset($_SESSION['cart']) || !is_array($_SESSION['cart'])) $_SESSION['cart'] = [];
                
                if (isset($_SESSION['cart'][$cartKey])) {
                    $newQty = $_SESSION['cart'][$cartKey]['quantity'] + $quantity;
                    if ($newQty > $product->stock_quantity) {
                        echo json_encode(['success' => false, 'message' => 'Vượt quá số lượng tồn kho cho phép.']);
                        exit;
                    }
                    $_SESSION['cart'][$cartKey]['quantity'] = $newQty;
                } else {
                    $_SESSION['cart'][$cartKey] = [
                        'productId' => $productId,
                        'size' => $size,
                        'quantity' => $quantity
                    ];
                }

                $totalItems = 0;
                foreach ($_SESSION['cart'] as $item) {
                    $totalItems += $item['quantity'];
                }
                $_SESSION['cart_total_items'] = $totalItems;
            }

            echo json_encode(['success' => true, 'message' => 'Đã thêm vào giỏ hàng.', 'totalItems' => $totalItems]);
            exit;
        }
        echo json_encode(['success' => false, 'message' => 'Yêu cầu không hợp lệ.']);
        exit;
    }

    public function index() {
        $cartItems = [];
        $totalPrice = 0;
        
        $isLoggedIn = isset($_SESSION['user_id']) && ($_SESSION['user_role'] ?? $_SESSION['role'] ?? 'member') === 'member';
        $items = [];

        if ($isLoggedIn) {
            $dbItems = $this->cartModel->getCartItems($_SESSION['user_id']);
            foreach ($dbItems as $dbItem) {
                $pId = is_object($dbItem) ? $dbItem->productId : $dbItem['productId'];
                $size = is_object($dbItem) ? ($dbItem->size ?? '') : ($dbItem['size'] ?? '');
                $quantity = is_object($dbItem) ? $dbItem->quantity : $dbItem['quantity'];
                $items[$pId . '_' . $size] = [
                    'productId' => $pId,
                    'size' => $size,
                    'quantity' => $quantity
                ];
            }
        } else {
            $items = $_SESSION['cart'] ?? [];
        }

        $hasUnavailableItems = false;

        if (!empty($items)) {
            foreach ($items as $key => $item) {
                $product = $this->productModel->getProductById($item['productId']);
                if ($product) {
                    if (is_array($product)) $product = (object)$product;
                    
                    $isDeleted = isset($product->is_deleted) ? (bool)$product->is_deleted : false;
                    $stock = (int)$product->stock_quantity;
                    
                    $isAvailable = !$isDeleted && $stock > 0;
                    
                    if ($isAvailable) {
                        $itemTotal = $product->price * $item['quantity'];
                        $totalPrice += $itemTotal;
                    } else {
                        $itemTotal = 0;
                        $hasUnavailableItems = true;
                    }
                    
                    $cartItems[] = [
                        'key' => $key,
                        'productId' => $product->productId,
                        'name' => $product->name,
                        'image' => $product->image_url,
                        'price' => $product->price,
                        'quantity' => $item['quantity'],
                        'size' => $item['size'],
                        'itemTotal' => $itemTotal,
                        'stock' => $stock,
                        'isDeleted' => $isDeleted,
                        'isAvailable' => $isAvailable,
                        'stockWarning' => $stock > 0 && $stock < $item['quantity']
                    ];
                } else {
                    $hasUnavailableItems = true;
                    $cartItems[] = [
                        'key' => $key,
                        'productId' => $item['productId'],
                        'name' => 'Sản phẩm không còn tồn tại',
                        'image' => 'https://placehold.co/100x100?text=N/A',
                        'price' => 0,
                        'quantity' => $item['quantity'],
                        'size' => $item['size'],
                        'itemTotal' => 0,
                        'stock' => 0,
                        'isDeleted' => true,
                        'isAvailable' => false,
                        'stockWarning' => false
                    ];
                }
            }
        }
        
        // Tính phí vận chuyển
        $shippingFee = ($totalPrice >= 5000000 || $totalPrice == 0) ? 0 : 100000;
        $finalTotal = $totalPrice + $shippingFee;

        $this->view('client/cart/index', [
            'title' => 'Giỏ hàng',
            'cartItems' => $cartItems,
            'totalPrice' => $totalPrice,
            'shippingFee' => $shippingFee,
            'finalTotal' => $finalTotal,
            'hasUnavailableItems' => $hasUnavailableItems
        ]);
    }

    public function update() {
        header('Content-Type: application/json');
        if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['key'], $_POST['quantity'])) {
            $key = $_POST['key'];
            $quantity = (int)$_POST['quantity'];
            
            $isLoggedIn = isset($_SESSION['user_id']) && ($_SESSION['user_role'] ?? $_SESSION['role'] ?? 'member') === 'member';
            
            $parts = explode('_', $key, 2);
            $productId = (int)$parts[0];
            $size = $parts[1] ?? '';

            if ($isLoggedIn) {
                $product = $this->productModel->getProductById($productId);
                if (is_array($product)) $product = (object)$product;
                
                if (!$product || $product->stock_quantity < $quantity) {
                    echo json_encode(['success' => false, 'message' => 'Số lượng tồn kho không đủ.']);
                    exit;
                }

                if ($quantity <= 0) {
                    $this->cartModel->removeItem($_SESSION['user_id'], $productId, $size);
                } else {
                    $this->cartModel->updateItemQuantity($_SESSION['user_id'], $productId, $size, $quantity);
                }
                $this->updateCartTotal();
                echo json_encode(['success' => true]);
                exit;
            } else {
                if (isset($_SESSION['cart'][$key])) {
                    if ($quantity <= 0) {
                        unset($_SESSION['cart'][$key]);
                    } else {
                        $product = $this->productModel->getProductById($_SESSION['cart'][$key]['productId']);
                        if (is_array($product)) $product = (object)$product;
                        
                        if ($product && $product->stock_quantity >= $quantity) {
                            $_SESSION['cart'][$key]['quantity'] = $quantity;
                        } else {
                            echo json_encode(['success' => false, 'message' => 'Số lượng tồn kho không đủ.']);
                            exit;
                        }
                    }
                    $this->updateCartTotal();
                    echo json_encode(['success' => true]);
                    exit;
                }
            }
        }
        echo json_encode(['success' => false]);
        exit;
    }

    public function remove() {
        header('Content-Type: application/json');
        if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['key'])) {
            $key = $_POST['key'];
            $parts = explode('_', $key, 2);
            $productId = (int)$parts[0];
            $size = $parts[1] ?? '';
            
            $isLoggedIn = isset($_SESSION['user_id']) && ($_SESSION['user_role'] ?? $_SESSION['role'] ?? 'member') === 'member';

            if ($isLoggedIn) {
                $this->cartModel->removeItem($_SESSION['user_id'], $productId, $size);
                $this->updateCartTotal();
                echo json_encode(['success' => true]);
                exit;
            } else {
                if (isset($_SESSION['cart'][$key])) {
                    unset($_SESSION['cart'][$key]);
                    $this->updateCartTotal();
                    echo json_encode(['success' => true]);
                    exit;
                }
            }
        }
        echo json_encode(['success' => false]);
        exit;
    }

    private function updateCartTotal() {
        $isLoggedIn = isset($_SESSION['user_id']) && ($_SESSION['user_role'] ?? $_SESSION['role'] ?? 'member') === 'member';
        if ($isLoggedIn) {
            $_SESSION['cart_total_items'] = $this->cartModel->getTotalItems($_SESSION['user_id']);
        } else {
            $totalItems = 0;
            if (isset($_SESSION['cart'])) {
                foreach ($_SESSION['cart'] as $item) {
                    $totalItems += $item['quantity'];
                }
            }
            $_SESSION['cart_total_items'] = $totalItems;
        }
    }

    public function checkout() {
        // Check login
        if (!isset($_SESSION['user_id']) || ($_SESSION['user_role'] ?? 'member') !== 'member') {
            $_SESSION['redirect_after_login'] = '/client/Cart/checkout';
            $_SESSION['error'] = 'Vui lòng đăng nhập để tiến hành thanh toán.';
            header('Location: ' . URLROOT . '/Login');
            exit;
        }

        $userId = $_SESSION['user_id'];
        $dbItems = $this->cartModel->getCartItems($userId);

        if (empty($dbItems)) {
            header('Location: ' . URLROOT . '/client/Cart');
            exit;
        }

        $cartItems = [];
        $totalPrice = 0;
        foreach ($dbItems as $dbItem) {
            $pId = is_object($dbItem) ? $dbItem->productId : $dbItem['productId'];
            $quantity = is_object($dbItem) ? $dbItem->quantity : $dbItem['quantity'];
            $size = is_object($dbItem) ? ($dbItem->size ?? '') : ($dbItem['size'] ?? '');

            $product = $this->productModel->getProductById($pId);
            if ($product) {
                if (is_array($product)) $product = (object)$product;
                $itemTotal = $product->price * $quantity;
                $totalPrice += $itemTotal;
                $cartItems[] = [
                    'productId' => $pId,
                    'name' => $product->name,
                    'price' => $product->price,
                    'quantity' => $quantity,
                    'size' => $size,
                    'itemTotal' => $itemTotal
                ];
            }
        }

        $error = '';
        $successMessage = '';
        if ($_SERVER['REQUEST_METHOD'] == 'POST') {
            $fullname = trim($_POST['fullname'] ?? '');
            $phone = trim($_POST['phone'] ?? '');
            $address = trim($_POST['address'] ?? '');
            $paymentMethod = trim($_POST['payment_method'] ?? 'COD');

            if (empty($fullname) || empty($phone) || empty($address)) {
                $error = 'Vui lòng điền đầy đủ thông tin giao hàng (Họ tên, Số điện thoại, Địa chỉ).';
            } else {
                try {
                    $orderModel = $this->model('OrderModel');
                    // Bắt đầu transaction
                    $orderModel->beginTransaction();
                    
                    // Cập nhật tên và sdt vào bảng member
                    $orderModel->updateMemberInfo($userId, $fullname, $phone);

                    // Chỉ lưu địa chỉ vào bảng order
                    $orderId = $orderModel->createOrder($userId, $address, $paymentMethod);
                    if ($orderId) {
                        foreach ($cartItems as $item) {
                            $orderModel->addOrderItem($orderId, $item['productId'], $item['size'], $item['price'], $item['quantity']);
                            // Trừ kho
                            $updatedRows = $this->productModel->decreaseStock($item['productId'], $item['quantity']);
                            if ($updatedRows === 0) {
                                // Nếu trừ kho thất bại (hết hàng), rollback và báo lỗi
                                throw new Exception("Sản phẩm '{$item['name']}' đã hết hàng hoặc không đủ số lượng.");
                            }
                        }
                    }
                    $this->cartModel->clearCart($userId);
                    $_SESSION['cart_total_items'] = 0;
                    
                    // Hoàn tất, commit transaction
                    $orderModel->commit();

                    $successMessage = 'Đặt hàng thành công! Chúng tôi sẽ sớm liên hệ để giao hàng.';
                    $cartItems = [];
                    $totalPrice = 0;
                } catch (Exception $e) {
                    // Có lỗi, rollback tất cả
                    $orderModel->rollBack();
                    $error = 'Lỗi tạo đơn hàng: ' . $e->getMessage();
                }
            }
        }

        $orderModel = $this->model('OrderModel');
        $userInfo = $orderModel->getMemberInfo($userId);

        // Tính phí vận chuyển
        $shippingFee = ($totalPrice >= 5000000 || $totalPrice == 0) ? 0 : 100000;
        $finalTotal = $totalPrice + $shippingFee;

        $this->view('client/cart/checkout', [
            'title' => 'Thanh toán',
            'cartItems' => $cartItems,
            'totalPrice' => $totalPrice,
            'shippingFee' => $shippingFee,
            'finalTotal' => $finalTotal,
            'userInfo' => $userInfo,
            'error' => $error,
            'successMessage' => $successMessage
        ]);
    }
}