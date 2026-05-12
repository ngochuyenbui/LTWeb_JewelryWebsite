<?php
require_once 'BaseModel.php';

class OrderModel extends BaseModel {
    public function beginTransaction() {
        return $this->db->beginTransaction();
    }

    public function commit() {
        return $this->db->commit();
    }

    public function rollBack() {
        return $this->db->rollBack();
    }

    // Hàm tạo Đơn hàng mới
    public function createOrder($memberId, $shippingAddr, $payment) {
        $this->db->query("INSERT INTO `order` (memberId, shipping_addr, payment) VALUES (:memberId, :shipping_addr, :payment)");
        $this->db->bind(':memberId', $memberId);
        $this->db->bind(':shipping_addr', $shippingAddr);
        $this->db->bind(':payment', $payment);

        $this->db->execute();
        return $this->db->lastInsertId(); // Trả về orderId vừa tạo
    }

    // Hàm thêm Sản phẩm vào Chi tiết Đơn hàng
    public function addOrderItem($orderId, $productId, $size, $purchasePrice, $quantity) {
        $this->db->query("INSERT INTO `order_item` (orderId, productId, size, purchase_price, quantity) VALUES (:orderId, :productId, :size, :purchase_price, :quantity)");
        $this->db->bind(':orderId', $orderId);
        $this->db->bind(':productId', $productId);
        $this->db->bind(':size', $size);
        $this->db->bind(':purchase_price', $purchasePrice);
        $this->db->bind(':quantity', $quantity);
        return $this->db->execute();
    }

    // Cập nhật thông tin khách hàng vào bảng user và member
    public function updateMemberInfo($userId, $fullname, $phone) {
        // 1. Cập nhật Họ Tên vào bảng user
        $this->db->query("UPDATE user SET fullname = :fullname WHERE userId = :userId");
        $this->db->bind(':fullname', $fullname);
        $this->db->bind(':userId', $userId);
        $this->db->execute();

        // 2. Cập nhật SĐT vào bảng member (Tự động thêm mới nếu chưa có record member)
        $this->db->query("INSERT INTO member (userId, phonenum) VALUES (:userId, :phone) ON DUPLICATE KEY UPDATE phonenum = :phone");
        $this->db->bind(':userId', $userId);
        $this->db->bind(':phone', $phone);

        return $this->db->execute();
    }

    // Lấy thông tin khách hàng kết hợp từ bảng user và member
    public function getMemberInfo($userId) {
        $this->db->query("SELECT u.fullname, m.phonenum as phone, m.address, m.rewardPoint FROM user u LEFT JOIN member m ON u.userId = m.userId WHERE u.userId = :userId");
        $this->db->bind(':userId', $userId);
        return $this->db->single();
    }

    // Lấy danh sách Đơn hàng của 1 User
    public function getOrdersByUserId($userId) {
        $this->db->query("SELECT * FROM `order` WHERE memberId = :userId ORDER BY created_at DESC");
        $this->db->bind(':userId', $userId);
        return $this->db->resultSet();
    }

    public function getOrdersByUserIdPaginated($userId, $limit, $offset) {
        $this->db->query("SELECT * FROM `order` WHERE memberId = :userId ORDER BY created_at DESC LIMIT :limit OFFSET :offset");
        $this->db->bind(':userId', $userId);
        $this->db->bind(':limit', $limit, PDO::PARAM_INT);
        $this->db->bind(':offset', $offset, PDO::PARAM_INT);
        return $this->db->resultSet();
    }

    public function getTotalOrdersByUserId($userId) {
        $this->db->query("SELECT COUNT(*) as total FROM `order` WHERE memberId = :userId");
        $this->db->bind(':userId', $userId);
        $row = $this->db->single();
        return $row ? (is_object($row) ? $row->total : $row['total']) : 0;
    }

    // Lấy danh sách Sản phẩm của 1 Đơn hàng
    public function getOrderItems($orderId) {
        $this->db->query("SELECT oi.*, p.name,
                                 (SELECT image_url FROM product_image WHERE product_image.productId = p.productId AND is_primary = 1 LIMIT 1) as image_url
                          FROM order_item oi
                          JOIN product p ON oi.productId = p.productId WHERE oi.orderId = :orderId");
        $this->db->bind(':orderId', $orderId);
        return $this->db->resultSet();
    }

    // Khách hàng tự hủy đơn hàng
    public function cancelOrderAsUser($orderId, $userId) {
        $this->db->query("UPDATE `order` SET status = 'cancelled' WHERE orderId = :orderId AND memberId = :userId AND status IN ('pending', 'processing')");
        $this->db->bind(':orderId', $orderId);
        $this->db->bind(':userId', $userId);
        $this->db->execute();
        return $this->db->rowCount(); // Trả về số dòng bị ảnh hưởng để biết hủy thành công hay không
    }
    // === ADMIN FUNCTIONS ===
    public function getAllOrdersPaginated($limit, $offset, $status = '', $payment = '', $sortBy = 'date_desc') {
        $sql = "SELECT o.*, u.fullname, u.username,
                (SELECT COALESCE(SUM(purchase_price * quantity), 0) FROM order_item WHERE orderId = o.orderId) as sub_total
                FROM `order` o
                JOIN user u ON o.memberId = u.userId
                WHERE 1=1 ";

        if (!empty($status)) {
            $sql .= " AND o.status = :status ";
        }
        if (!empty($payment)) {
            $sql .= " AND o.payment = :payment ";
        }

        if ($sortBy === 'date_asc') {
            $sql .= " ORDER BY o.created_at ASC ";
        } elseif ($sortBy === 'total_desc') {
            $sql .= " ORDER BY (sub_total + CASE WHEN sub_total >= 5000000 OR sub_total = 0 THEN 0 ELSE 100000 END) DESC ";
        } elseif ($sortBy === 'total_asc') {
            $sql .= " ORDER BY (sub_total + CASE WHEN sub_total >= 5000000 OR sub_total = 0 THEN 0 ELSE 100000 END) ASC ";
        } else {
            $sql .= " ORDER BY o.created_at DESC ";
        }

        $sql .= " LIMIT :limit OFFSET :offset";

        $this->db->query($sql);

        if (!empty($status)) $this->db->bind(':status', $status);
        if (!empty($payment)) $this->db->bind(':payment', $payment);
        $this->db->bind(':limit', $limit, PDO::PARAM_INT);
        $this->db->bind(':offset', $offset, PDO::PARAM_INT);
        return $this->db->resultSet();
    }

    public function getTotalOrders($status = '', $payment = '') {
        $sql = "SELECT COUNT(*) as total FROM `order` WHERE 1=1 ";
        if (!empty($status)) $sql .= " AND status = :status ";
        if (!empty($payment)) $sql .= " AND payment = :payment ";

        $this->db->query($sql);
        if (!empty($status)) $this->db->bind(':status', $status);
        if (!empty($payment)) $this->db->bind(':payment', $payment);
        $row = $this->db->single();
        return $row ? (is_object($row) ? $row->total : $row['total']) : 0;
    }

    public function getOrderDetails($orderId) {
        $this->db->query("SELECT o.*, u.fullname, u.username, u.email, m.phonenum as phone
                          FROM `order` o
                          JOIN user u ON o.memberId = u.userId
                          LEFT JOIN member m ON u.userId = m.userId
                          WHERE o.orderId = :orderId");
        $this->db->bind(':orderId', $orderId);
        return $this->db->single();
    }

    public function updateOrderStatus($orderId, $status) {
        $this->db->query("UPDATE `order` SET status = :status WHERE orderId = :orderId");
        $this->db->bind(':status', $status);
        $this->db->bind(':orderId', $orderId);
        return $this->db->execute();
    }

    public function addRewardPoints($userId, $points) {
        $this->db->query("INSERT INTO member (userId, rewardPoint) VALUES (:userId, :points)
                          ON DUPLICATE KEY UPDATE rewardPoint = rewardPoint + :points");
        $this->db->bind(':userId', $userId);
        $this->db->bind(':points', $points, PDO::PARAM_INT);
        return $this->db->execute();
    }
}

    