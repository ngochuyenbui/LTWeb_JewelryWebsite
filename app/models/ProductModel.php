<?php
require_once 'BaseModel.php';
class ProductModel extends BaseModel{
    
    public function getProducts($filters = [], $limit = null, $offset = null){
        $sql = "SELECT product.*, 
                       (SELECT image_url FROM product_image WHERE product_image.productId = product.productId AND is_primary = 1 LIMIT 1) as image_url,
                       (SELECT COALESCE(AVG(rating), 0) FROM comment WHERE comment.contentId = product.contentId) as rating,
                       (SELECT COUNT(*) FROM comment WHERE comment.contentId = product.contentId) as review_count
                FROM product WHERE is_deleted = 0";
        $params = [];
        
        // Search
        if (!empty($filters['search'])) {
            $sql .= " AND (product.name LIKE :search OR product.sku LIKE :search)";
            $params[':search'] = '%' . $filters['search'] . '%';
        }

        // Category
        if (!empty($filters['category'])) {
            $sql .= " AND cateId = :category";
            $params[':category'] = $filters['category'];
        }
        
        // Price Range Slider
        if (isset($filters['min_price']) && $filters['min_price'] !== '') {
            $sql .= " AND price >= :min_price";
            $params[':min_price'] = $filters['min_price'];
        }
        
        if (isset($filters['max_price']) && $filters['max_price'] !== '') {
            $sql .= " AND price <= :max_price";
            $params[':max_price'] = $filters['max_price'];
        }
        
        // Kích thước (Size)
        if (!empty($filters['size']) && is_array($filters['size'])) {
            $sizes = [];
            foreach ($filters['size'] as $index => $size) {
                $sizes[] = ":size_$index";
                $params[":size_$index"] = $size;
            }
            $sql .= " AND size IN (" . implode(',', $sizes) . ")";
        }
        
        // Màu sắc (Color)
        if (!empty($filters['color']) && is_array($filters['color'])) {
            $colors = [];
            foreach ($filters['color'] as $index => $color) {
                $colors[] = ":color_$index";
                $params[":color_$index"] = $color;
            }
            $sql .= " AND color IN (" . implode(',', $colors) . ")";
        }

        //  Sort
        if (!empty($filters['sort'])) {
            if ($filters['sort'] === 'price_asc') {
                $sql .= " ORDER BY price ASC";
            } elseif ($filters['sort'] === 'price_desc') {
                $sql .= " ORDER BY price DESC";
            } else {
                $sql .= " ORDER BY productId DESC";
            }
        } else {
            $sql .= " ORDER BY productId DESC";
        }

        if ($limit !== null && $offset !== null) {
            $sql .= " LIMIT :limit OFFSET :offset";
        }

        $this->db->query($sql);       
        foreach ($params as $key => $val) {
            $this->db->bind($key, $val);
        }      
        if ($limit !== null && $offset !== null) {
            $this->db->bind(':limit', (int)$limit, PDO::PARAM_INT);
            $this->db->bind(':offset', (int)$offset, PDO::PARAM_INT);
        }
        return $this->db->resultSet();
    }

    public function getTotalProducts($filters = []){
        $sql = "SELECT COUNT(*) as total FROM product WHERE is_deleted = 0";
        $params = [];
        
        if (!empty($filters['search'])) {
            $sql .= " AND (name LIKE :search OR sku LIKE :search)";
            $params[':search'] = '%' . $filters['search'] . '%';
        }

        if (!empty($filters['category'])) {
            $sql .= " AND cateId = :category";
            $params[':category'] = $filters['category'];
        }
        
        if (isset($filters['min_price']) && $filters['min_price'] !== '') {
            $sql .= " AND price >= :min_price";
            $params[':min_price'] = $filters['min_price'];
        }
        
        if (isset($filters['max_price']) && $filters['max_price'] !== '') {
            $sql .= " AND price <= :max_price";
            $params[':max_price'] = $filters['max_price'];
        }
        
        if (!empty($filters['size']) && is_array($filters['size'])) {
            $sizes = [];
            foreach ($filters['size'] as $index => $size) {
                $sizes[] = ":size_$index";
                $params[":size_$index"] = $size;
            }
            $sql .= " AND size IN (" . implode(',', $sizes) . ")";
        }
        
        if (!empty($filters['color']) && is_array($filters['color'])) {
            $colors = [];
            foreach ($filters['color'] as $index => $color) {
                $colors[] = ":color_$index";
                $params[":color_$index"] = $color;
            }
            $sql .= " AND color IN (" . implode(',', $colors) . ")";
        }

        $this->db->query($sql);
        foreach ($params as $key => $val) {
            $this->db->bind($key, $val);
        }
        $row = $this->db->single();
        return $row ? (int)($row->total ?? 0) : 0;
    }

    public function getCategories(){
        $this->db->query("SELECT * FROM category WHERE is_hidden = 0 AND (type = 'product' OR type IS NULL OR type = '')");
        return $this->db->resultSet();
    }

    public function getSizes(){
        $this->db->query("SELECT DISTINCT size FROM product WHERE size IS NOT NULL AND size != ''");
        return $this->db->resultSet();
    }

    public function getColors(){
        $this->db->query("SELECT DISTINCT color FROM product WHERE color IS NOT NULL AND color != ''");
        return $this->db->resultSet();
    }

    public function getPriceRange() {
        $this->db->query("SELECT MIN(price) as min_price, MAX(price) as max_price FROM product WHERE is_deleted = 0");
        $result = $this->db->single();
        return $result ?: ['min_price' => 0, 'max_price' => 50000000];
    }

    public function getProductsOfCategory($cateId){
        $this->db->query("SELECT P.*, 
                                 (SELECT image_url FROM product_image WHERE product_image.productId = P.productId AND is_primary = 1 LIMIT 1) as image_url,
                                 (SELECT COALESCE(AVG(rating), 0) FROM comment WHERE comment.contentId = P.contentId) as rating,
                                 (SELECT COUNT(*) FROM comment WHERE comment.contentId = P.contentId) as review_count
                          FROM product P JOIN category C ON P.cateId = C.cateId WHERE P.cateId = :cateId AND P.is_deleted = 0");
        $this->db->bind('cateId', $cateId);
        return $this->db->resultSet();
    }

    public function getProductImages($productId){
        $this->db->query("SELECT * FROM product_image WHERE productId = :productId");
        $this->db->bind(':productId', $productId);
        return $this->db->resultSet();
    }

    public function getProductById($productId){
        $this->db->query("SELECT product.*, 
                                 (SELECT image_url FROM product_image WHERE product_image.productId = product.productId AND is_primary = 1 LIMIT 1) as image_url,
                                 (SELECT COALESCE(AVG(rating), 0) FROM comment WHERE comment.contentId = product.contentId) as rating,
                                 (SELECT COUNT(*) FROM comment WHERE comment.contentId= product.contentId) as review_count
                          FROM product WHERE productId = :productId AND is_deleted = 0");
        $this->db->bind(':productId', $productId);
        return $this->db->single();
    }

    public function getRelatedProducts($cateId, $currentProductId, $limit = 8) {
        $this->db->query("SELECT product.*, 
                                 (SELECT image_url FROM product_image WHERE product_image.productId = product.productId AND is_primary = 1 LIMIT 1) as image_url,
                                 (SELECT COALESCE(AVG(rating), 0) FROM comment WHERE comment.contentId= product.contentId) as rating,
                                 (SELECT COUNT(*) FROM comment WHERE comment.contentId= product.contentId) as review_count
                          FROM product WHERE cateId = :cateId AND productId != :currentProductId AND is_deleted = 0 ORDER BY productId DESC LIMIT :limit");
        $this->db->bind(':cateId', $cateId);
        $this->db->bind(':currentProductId', $currentProductId);
        $this->db->bind(':limit', (int)$limit, PDO::PARAM_INT);
        return $this->db->resultSet();
    }

    public function getFeaturedProducts($limit = 4)
    {
        $this->db->query("
            SELECT
                p.productId,
                p.name,
                p.sku,
                p.color,
                p.material,
                p.description,
                p.price,
                p.stock_quantity,
                p.created_at,
                c.name AS category_name,
                c.slug AS category_slug,
                COALESCE(
                    MAX(CASE WHEN pi.is_primary = 1 THEN pi.image_url END),
                    MIN(pi.image_url)
                ) AS image_url
            FROM product p
            LEFT JOIN category c ON c.cateId = p.cateId
            LEFT JOIN product_image pi ON pi.productId = p.productId
            WHERE p.is_deleted = 0
              AND (c.type = 'product' OR c.type IS NULL)
              AND (c.is_hidden = 0 OR c.is_hidden IS NULL)
            GROUP BY
                p.productId,
                p.name,
                p.sku,
                p.color,
                p.material,
                p.description,
                p.price,
                p.stock_quantity,
                p.created_at,
                c.name,
                c.slug
            ORDER BY
                CASE WHEN p.stock_quantity > 0 THEN 0 ELSE 1 END,
                p.created_at DESC,
                p.productId DESC
            LIMIT :limit
        ");
        $this->db->bind(':limit', (int)$limit, PDO::PARAM_INT);
        return $this->db->resultSet();
    }

    public function getHomeCategories($limit = 4)
    {
        $this->db->query("
            SELECT
                c.cateId,
                c.name,
                c.slug,
                COUNT(p.productId) AS product_count
            FROM category c
            LEFT JOIN product p ON p.cateId = c.cateId AND p.is_deleted = 0
            WHERE c.type = 'product'
              AND c.is_hidden = 0
            GROUP BY c.cateId, c.name, c.slug
            ORDER BY product_count DESC, c.cateId ASC
            LIMIT :limit
        ");
        $this->db->bind(':limit', (int)$limit, PDO::PARAM_INT);
        return $this->db->resultSet();
    }

    public function addProduct($data) {
        $this->db->query("INSERT INTO product (sku, name, color, size, size_dim, material, usage_info, description, price, stock_quantity, cateId) 
                          VALUES (:sku, :name, :color, :size, :size_dim, :material, :usage_info, :description, :price, :stock_quantity, :cateId)");
        
        $this->db->bind(':sku', $data['sku']);
        $this->db->bind(':name', $data['name']);
        $this->db->bind(':color', $data['color']);
        $this->db->bind(':size', $data['size']);
        $this->db->bind(':size_dim', $data['size_dim']);
        $this->db->bind(':material', $data['material']);
        $this->db->bind(':usage_info', $data['usage_info']);
        $this->db->bind(':description', $data['description']);
        $this->db->bind(':price', $data['price']);
        $this->db->bind(':stock_quantity', $data['stock_quantity']);
        $this->db->bind(':cateId', $data['cateId']);

        if ($this->db->execute()) {
            return $this->db->lastInsertId(); 
        }
        return false;
    }

    public function updateProduct($data) {
        $this->db->query("UPDATE product SET sku = :sku, name = :name, color = :color, size = :size, size_dim = :size_dim, material = :material, usage_info = :usage_info, description = :description, price = :price, stock_quantity = :stock_quantity, cateId = :cateId WHERE productId = :productId");
        
        $this->db->bind(':productId', $data['productId']);
        $this->db->bind(':sku', $data['sku']);
        $this->db->bind(':name', $data['name']);
        $this->db->bind(':color', $data['color']);
        $this->db->bind(':size', $data['size']);
        $this->db->bind(':size_dim', $data['size_dim']);
        $this->db->bind(':material', $data['material']);
        $this->db->bind(':usage_info', $data['usage_info']);
        $this->db->bind(':description', $data['description']);
        $this->db->bind(':price', $data['price']);
        $this->db->bind(':stock_quantity', $data['stock_quantity']);
        $this->db->bind(':cateId', $data['cateId']);

        return $this->db->execute();
    }

    public function addCategory($name, $type = 'product', $slug = '') {
        $this->db->query("INSERT INTO category (name, type, slug, is_hidden) VALUES (:name, :type, :slug, 0)");
        $this->db->bind(':name', $name);
        $this->db->bind(':type', $type);
        $this->db->bind(':slug', $slug);
        if ($this->db->execute()) {
            return $this->db->lastInsertId();
        }
        return null;
    }

    public function addProductImage($productId, $imageUrl, $isPrimary = 0) {
        $this->db->query("INSERT INTO product_image (productId, image_url, is_primary) VALUES (:productId, :image_url, :is_primary)");
        $this->db->bind(':productId', $productId);
        $this->db->bind(':image_url', $imageUrl);
        $this->db->bind(':is_primary', $isPrimary);
        return $this->db->execute();
    }

    public function setPrimaryImage($productId, $imageUrl) {
        $this->db->query("UPDATE product_image SET is_primary = 0 WHERE productId = :productId");
        $this->db->bind(':productId', $productId);
        $this->db->execute();

        $this->db->query("UPDATE product_image SET is_primary = 1 WHERE productId = :productId AND image_url = :image_url");
        $this->db->bind(':productId', $productId);
        $this->db->bind(':image_url', $imageUrl);
        return $this->db->execute();
    }

    public function deleteProductImageByUrl($productId, $imageUrl) {
        $this->db->query("DELETE FROM product_image WHERE productId = :productId AND image_url = :imageUrl");
        $this->db->bind(':productId', $productId);
        $this->db->bind(':imageUrl', $imageUrl);
        return $this->db->execute();
    }

    public function setOutOfStock($productId) {
        $this->db->query("UPDATE product SET stock_quantity = 0 WHERE productId = :productId");
        $this->db->bind(':productId', $productId);
        return $this->db->execute();
    }

    public function deleteProduct($productId) {
        // Soft Delete: Đánh dấu là đã xóa, bỏ qua việc xóa vĩnh viễn
        $this->db->query("UPDATE product SET is_deleted = 1 WHERE productId = :productId");
        $this->db->bind(':productId', $productId);
        return $this->db->execute();
    }

    public function deleteMultipleProducts($ids) {
        if (empty($ids)) return false;
        
        $placeholders = implode(',', array_fill(0, count($ids), '?'));
        $this->db->query("UPDATE product SET is_deleted = 1 WHERE productId IN ($placeholders)");
        
        foreach ($ids as $index => $id) {
            $this->db->bind($index + 1, $id);
        }
        
        return $this->db->execute();
    }

    public function decreaseStock($productId, $quantity) {
        $this->db->query("UPDATE product SET stock_quantity = stock_quantity - :quantity WHERE productId = :productId AND stock_quantity >= :quantity");
        $this->db->bind(':quantity', $quantity, PDO::PARAM_INT);
        $this->db->bind(':productId', $productId, PDO::PARAM_INT);
        $this->db->execute();
        return $this->db->rowCount();
    }

    public function increaseStock($productId, $quantity) {
        $this->db->query("UPDATE product SET stock_quantity = stock_quantity + :quantity WHERE productId = :productId");
        $this->db->bind(':quantity', $quantity, PDO::PARAM_INT);
        $this->db->bind(':productId', $productId, PDO::PARAM_INT);
        return $this->db->execute();
    }

}
