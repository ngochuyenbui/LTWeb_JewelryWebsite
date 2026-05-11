<?php
class Products extends Controller {
    private $productModel;

    public function __construct() {
        parent::__construct();
        $this->requireAdmin(); // Bắt buộc đăng nhập với quyền Admin
        $this->productModel = $this->model('ProductModel');
    }

    public function index() {
        $filters = [
            'search' => trim($_GET['search'] ?? ''),
            'category' => $_GET['category'] ?? null,
            'size' => !empty($_GET['size']) ? [$_GET['size']] : [],
            'color' => !empty($_GET['color']) ? [$_GET['color']] : [],
            'min_price' => $_GET['min_price'] ?? null,
            'max_price' => $_GET['max_price'] ?? null,
            'sort' => $_GET['sort'] ?? 'default'
        ];

        // Phân trang
        $limit = 6;
        $page = isset($_GET['page']) ? max(1, (int)$_GET['page']) : 1;
        $offset = ($page - 1) * $limit;

        $totalItems = $this->productModel->getTotalProducts($filters);
        $totalPages = ceil($totalItems / $limit);

        $products = $this->productModel->getProducts($filters, $limit, $offset); 
        $categories = $this->productModel->getCategories();
        $colors = $this->productModel->getColors();
        $sizes = $this->productModel->getSizes();
        $priceRange = $this->productModel->getPriceRange();
        $this->view('admin/product/index', [
            'title' => 'Quản lý Sản phẩm',
            'products' => $products,
            'categories' => $categories,
            'colors' => $colors,
            'sizes' => $sizes,
            'filters' => $filters,
            'priceRange' => $priceRange,
            'currentPage' => $page,
            'totalPages' => $totalPages,
            'totalItems' => $totalItems
        ]);     
    }

    private function toSlug($str) {
        $str = mb_strtolower($str, 'UTF-8');
        $str = preg_replace('/(à|á|ạ|ả|ã|â|ầ|ấ|ậ|ẩ|ẫ|ẫ|ă|ằ|ắ|ặ|ẳ|ẵ)/', 'a', $str);
        $str = preg_replace('/(è|é|ẹ|ẻ|ẽ|ê|ề|ế|ệ|ể|ễ)/', 'e', $str);
        $str = preg_replace('/(ì|í|ị|ỉ|ĩ)/', 'i', $str);
        $str = preg_replace('/(ò|ó|ọ|ỏ|õ|ô|ồ|ố|ộ|ổ|ỗ|ơ|ờ|ớ|ợ|ở|ỡ)/', 'o', $str);
        $str = preg_replace('/(ù|ú|ụ|ủ|ũ|ư|ừ|ứ|ự|ử|ữ)/', 'u', $str);
        $str = preg_replace('/(ỳ|ý|ỵ|ỷ|ỹ)/', 'y', $str);
        $str = preg_replace('/(đ)/', 'd', $str);
        $str = preg_replace('/[^a-z0-9\-]+/', '-', $str);
        return trim($str, '-');
    }

    private function uploadImages($files, $maxAllowed) {
        $uploadedUrls = [];
        $uploadDir = '../public/assets/uploads/products/';
        if (!is_dir($uploadDir)) {
            @mkdir($uploadDir, 0777, true); // Tạo thư mục nếu chưa tồn tại
        }

        if (isset($files['name']) && is_array($files['name'])) {
            $count = min(count($files['name']), $maxAllowed);
            for ($i = 0; $i < $count; $i++) {
                if ($files['error'][$i] === UPLOAD_ERR_OK) {
                    $tmpName = $files['tmp_name'][$i];
                    $name = basename($files['name'][$i]);
                    $ext = strtolower(pathinfo($name, PATHINFO_EXTENSION));
                    $allowedExts = ['jpg', 'jpeg', 'png', 'gif', 'webp'];
                    
                    if (in_array($ext, $allowedExts)) {
                        $newName = uniqid() . '_' . time() . '.' . $ext;
                        $destPath = $uploadDir . $newName;
                        if (move_uploaded_file($tmpName, $destPath)) {
                            $uploadedUrls[$name] = '/assets/uploads/products/' . $newName;
                        }
                    }
                }
            }
        }
        return $uploadedUrls;
    }

    public function create() {
        $error = '';
        if ($_SERVER['REQUEST_METHOD'] == 'POST') {
            // Logic tạo danh mục mới
            $cateId = $_POST['cateId'] ?? null;
            if ($cateId === 'new' && !empty($_POST['new_category_name'])) {
                $newCateName = trim($_POST['new_category_name']);
                $slug = $this->toSlug($newCateName);
                $cateId = $this->productModel->addCategory($newCateName, 'product', $slug);
            } else {
                $cateId = !empty($cateId) ? (int)$cateId : null;
            }

            // Logic màu sắc
            $color = trim($_POST['color_select'] ?? '');
            if ($color === 'other') $color = trim($_POST['color_custom'] ?? '');

            // Logic kích cỡ
            $sizes = $_POST['sizes'] ?? [];
            if (!is_array($sizes)) $sizes = [];
            $customSize = trim($_POST['size_custom'] ?? '');
            if (!empty($customSize)) $sizes[] = $customSize;
            $sizeStr = implode(', ', array_filter($sizes));

            // Logic giá tiền (Bỏ dấu chấm)
            $priceRaw = $_POST['price'] ?? '0';
            $price = (float)str_replace('.', '', $priceRaw);

            $data = [
                'sku' => trim($_POST['sku'] ?? ''),
                'name' => trim($_POST['name'] ?? ''),
                'cateId' => $cateId,
                'price' => $price,
                'stock_quantity' => trim($_POST['stock_quantity'] ?? 0),
                'color' => $color,
                'size' => $sizeStr,
                'size_dim' => trim($_POST['size_dim'] ?? ''),
                'material' => trim($_POST['material'] ?? ''),
                'usage_info' => trim($_POST['usage_info'] ?? ''),
                'description' => trim($_POST['description'] ?? '')
            ];
            
            try {
                if ($productId = $this->productModel->addProduct($data)) {
                    $primaryImageRaw = $_POST['primary_image'] ?? '';
                    // Nếu có file upload lên
                    if (!empty($_FILES['images']['name'][0])) {
                        $uploadedUrls = $this->uploadImages($_FILES['images'], 5);
                        
                        $hasPrimary = false;
                        foreach ($uploadedUrls as $origName => $url) {
                            $isPrimary = ($primaryImageRaw === 'new:' . $origName) ? 1 : 0;
                            if ($isPrimary) $hasPrimary = true;
                            $this->productModel->addProductImage($productId, $url, $isPrimary);
                        }
                        
                        // Dự phòng: Nếu quên chọn, tự động lấy ảnh đầu tiên làm ảnh chính
                        if (!$hasPrimary && !empty($uploadedUrls)) {
                            $firstUrl = reset($uploadedUrls);
                            $this->productModel->setPrimaryImage($productId, $firstUrl);
                        }
                    }
                    $_SESSION['success'] = 'Thêm sản phẩm thành công!';
                    header('Location: ' . URLROOT . '/admin/Products');
                    exit;
                }
            } catch (PDOException $e) {
                $error = 'Lỗi truy vấn: ' . $e->getMessage();
            }
        }
        
        $categories = $this->productModel->getCategories();
        $this->view('admin/product/create', ['title' => 'Thêm Sản phẩm', 'categories' => $categories, 'error' => $error]);
    }

    public function edit($id) {
        $error = '';
        if ($_SERVER['REQUEST_METHOD'] == 'POST') {
            // Logic tạo danh mục mới
            $cateId = $_POST['cateId'] ?? null;
            if ($cateId === 'new' && !empty($_POST['new_category_name'])) {
                $newCateName = trim($_POST['new_category_name']);
                $slug = $this->toSlug($newCateName);
                $cateId = $this->productModel->addCategory($newCateName, 'product', $slug);
            } else {
                $cateId = !empty($cateId) ? (int)$cateId : null;
            }

            // Logic màu sắc
            $color = trim($_POST['color_select'] ?? '');
            if ($color === 'other') $color = trim($_POST['color_custom'] ?? '');

            // Logic kích cỡ
            $sizes = $_POST['sizes'] ?? [];
            if (!is_array($sizes)) $sizes = [];
            $customSize = trim($_POST['size_custom'] ?? '');
            if (!empty($customSize)) $sizes[] = $customSize;
            $sizeStr = implode(', ', array_filter($sizes));

            // Logic giá tiền (Bỏ dấu chấm)
            $priceRaw = $_POST['price'] ?? '0';
            $price = (float)str_replace('.', '', $priceRaw);

            $data = [
                'productId' => $id,
                'sku' => trim($_POST['sku'] ?? ''),
                'name' => trim($_POST['name'] ?? ''),
                'cateId' => $cateId,
                'price' => $price,
                'stock_quantity' => trim($_POST['stock_quantity'] ?? 0),
                'color' => $color,
                'size' => $sizeStr,
                'size_dim' => trim($_POST['size_dim'] ?? ''),
                'material' => trim($_POST['material'] ?? ''),
                'usage_info' => trim($_POST['usage_info'] ?? ''),
                'description' => trim($_POST['description'] ?? '')
            ];

            // Xử lý Xóa ảnh
            $deleteUrls = $_POST['delete_images'] ?? [];
            $primaryImageRaw = $_POST['primary_image'] ?? '';
            
            $existingImages = $this->productModel->getProductImages($id);
            $totalExisting = count($existingImages);

            foreach ($deleteUrls as $url) {
                $this->productModel->deleteProductImageByUrl($id, $url);
                $totalExisting--;
            }

            // Cập nhật Ảnh chính từ ảnh đã có sẵn
            $hasPrimary = false;
            if (strpos($primaryImageRaw, 'existing:') === 0) {
                $existingUrl = substr($primaryImageRaw, 9);
                if (!in_array($existingUrl, $deleteUrls)) {
                    $this->productModel->setPrimaryImage($id, $existingUrl);
                    $hasPrimary = true;
                }
            }

            // Upload thêm ảnh mới
            if (!empty($_FILES['images']['name'][0])) {
                $maxAllowed = max(0, 5 - $totalExisting);
                $uploadedUrls = $this->uploadImages($_FILES['images'], $maxAllowed);
                
                foreach ($uploadedUrls as $origName => $url) {
                    $isPrimary = ($primaryImageRaw === 'new:' . $origName) ? 1 : 0;
                    if ($isPrimary) $hasPrimary = true;
                    $this->productModel->addProductImage($id, $url, $isPrimary);
                }
            }

            if (!$hasPrimary && $totalExisting > 0) {
                $remainingImages = $this->productModel->getProductImages($id);
                if (!empty($remainingImages)) {
                    $firstImgUrl = is_object($remainingImages[0]) ? ($remainingImages[0]->image_url ?? '') : ($remainingImages[0]['image_url'] ?? '');
                    $this->productModel->setPrimaryImage($id, $firstImgUrl);
                }
            }

            try {
                if ($this->productModel->updateProduct($data)) {
                    $_SESSION['success'] = 'Cập nhật sản phẩm thành công!';
                    header('Location: ' . URLROOT . '/admin/Products');
                    exit;
                }
            } catch (PDOException $e) {
                $error = 'Lỗi truy vấn: ' . $e->getMessage();
            }
        }

        $product = $this->productModel->getProductById($id);
        if (!$product) {
            header('Location: ' . URLROOT . '/admin/Products');
            exit;
        }
        $categories = $this->productModel->getCategories();
        $images = $this->productModel->getProductImages($id); // Lấy danh sách ảnh hiển thị ra View
        $this->view('admin/product/edit', ['title' => 'Sửa Sản phẩm', 'product' => $product, 'categories' => $categories, 'images' => $images, 'error' => $error]);
    }

    public function outOfStock($id = null) {
        header('Content-Type: application/json');
        if ($id && $_SERVER['REQUEST_METHOD'] == 'POST') {
            $this->productModel->setOutOfStock($id);
            echo json_encode(['success' => true, 'message' => 'Đã đánh dấu sản phẩm là Hết hàng.']);
            exit;
        }
        echo json_encode(['success' => false, 'message' => 'Lỗi yêu cầu.']);
        exit;
    }

    public function delete($id = null) {
        header('Content-Type: application/json');
        if ($id && $_SERVER['REQUEST_METHOD'] == 'POST') {
            try {
                $this->productModel->deleteProduct($id);
                echo json_encode(['success' => true, 'message' => 'Đã xóa (ẩn) sản phẩm thành công.']);
            } catch (PDOException $e) {
                echo json_encode(['success' => false, 'message' => 'Lỗi truy vấn: ' . $e->getMessage()]);
            }
            exit;
        }
        echo json_encode(['success' => false, 'message' => 'Lỗi yêu cầu.']);
        exit;
    }

    public function deleteMultiple() {
        header('Content-Type: application/json');
        if ($_SERVER['REQUEST_METHOD'] == 'POST' && !empty($_POST['ids'])) {
            $ids = explode(',', $_POST['ids']);
            $ids = array_map('intval', $ids);
            
            try {
                $this->productModel->deleteMultipleProducts($ids);
                echo json_encode(['success' => true, 'message' => 'Đã chuyển ' . count($ids) . ' sản phẩm vào Thùng rác thành công.']);
            } catch (PDOException $e) {
                echo json_encode(['success' => false, 'message' => 'Lỗi truy vấn: ' . $e->getMessage()]);
            }
            exit;
        }
        echo json_encode(['success' => false, 'message' => 'Yêu cầu không hợp lệ.']);
        exit;
    }
}