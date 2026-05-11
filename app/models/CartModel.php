<?php
require_once 'BaseModel.php';

class CartModel extends BaseModel {
    // Hàm trợ trợ: Lấy cartId của member, nếu chưa có thì tự động tạo mới
    private function getCartId($memberId) {
        $this->db->query("SELECT cartId FROM cart WHERE memberId = :memberId");
        $this->db->bind(':memberId', $memberId);
        $row = $this->db->single();
        
        if ($row) {
            return $row['cartId'];
        }
        
        // Tạo cart mới nếu chưa có
        $this->db->query("INSERT INTO cart (memberId) VALUES (:memberId)");
        $this->db->bind(':memberId', $memberId);
        $this->db->execute();
        return $this->db->lastInsertId();
    }

    public function getCartItems($userId) {
        $cartId = $this->getCartId($userId);
        $this->db->query("SELECT * FROM cart_item WHERE cartId = :cartId");
        $this->db->bind(':cartId', $cartId);
        return $this->db->resultSet();
    }

    public function getCartItem($userId, $productId, $size) {
        $cartId = $this->getCartId($userId);
        $this->db->query("SELECT * FROM cart_item WHERE cartId = :cartId AND productId = :productId AND size = :size");
        $this->db->bind(':cartId', $cartId);
        $this->db->bind(':productId', $productId);
        $this->db->bind(':size', $size);
        return $this->db->single();
    }

    public function addItem($userId, $productId, $size, $quantity) {
        $cartId = $this->getCartId($userId);
        $this->db->query("INSERT INTO cart_item (cartId, productId, size, quantity) VALUES (:cartId, :productId, :size, :quantity)");
        $this->db->bind(':cartId', $cartId);
        $this->db->bind(':productId', $productId);
        $this->db->bind(':size', $size);
        $this->db->bind(':quantity', $quantity);
        return $this->db->execute();
    }

    public function updateItemQuantity($userId, $productId, $size, $quantity) {
        $cartId = $this->getCartId($userId);
        $this->db->query("UPDATE cart_item SET quantity = :quantity WHERE cartId = :cartId AND productId = :productId AND size = :size");
        $this->db->bind(':cartId', $cartId);
        $this->db->bind(':productId', $productId);
        $this->db->bind(':size', $size);
        $this->db->bind(':quantity', $quantity);
        return $this->db->execute();
    }

    public function removeItem($userId, $productId, $size) {
        $cartId = $this->getCartId($userId);
        $this->db->query("DELETE FROM cart_item WHERE cartId = :cartId AND productId = :productId AND size = :size");
        $this->db->bind(':cartId', $cartId);
        $this->db->bind(':productId', $productId);
        $this->db->bind(':size', $size);
        return $this->db->execute();
    }

    public function clearCart($userId) {
        $cartId = $this->getCartId($userId);
        $this->db->query("DELETE FROM cart_item WHERE cartId = :cartId");
        $this->db->bind(':cartId', $cartId);
        return $this->db->execute();
    }

    public function getTotalItems($userId) {
        $cartId = $this->getCartId($userId);
        $this->db->query("SELECT SUM(quantity) as total FROM cart_item WHERE cartId = :cartId");
        $this->db->bind(':cartId', $cartId);
        $row = $this->db->single();
        return $row ? (int)$row['total'] : 0;
    }
    // === ADMIN FUNCTIONS ===
    public function getAllCartsPaginated($limit, $offset) {
        $this->db->query("SELECT c.cartId, c.memberId, c.updated_at, u.username, u.fullname 
                          FROM cart c 
                          JOIN user u ON c.memberId = u.userId 
                          WHERE (SELECT COUNT(*) FROM cart_item WHERE cartId = c.cartId) > 0
                          ORDER BY c.updated_at DESC
                          LIMIT :limit OFFSET :offset");
        $this->db->bind(':limit', $limit, PDO::PARAM_INT);
        $this->db->bind(':offset', $offset, PDO::PARAM_INT);
        return $this->db->resultSet();
    }

    public function getTotalActiveCarts() {
        $this->db->query("SELECT COUNT(*) as total 
                          FROM cart c 
                          WHERE (SELECT COUNT(*) FROM cart_item WHERE cartId = c.cartId) > 0");
        $row = $this->db->single();
        return $row['total'] ?? 0;
    }

    public function getItemsForCart($cartId) {
        $this->db->query("SELECT ci.*, p.name, p.price, 
                                 (SELECT image_url FROM product_image WHERE product_image.productId = p.productId AND is_primary = 1 LIMIT 1) as image_url 
                          FROM cart_item ci 
                          JOIN product p ON ci.productId = p.productId 
                          WHERE ci.cartId = :cartId");
        $this->db->bind(':cartId', $cartId);
        return $this->db->resultSet();
    }
}

    