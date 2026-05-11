<?php
$product = $data['product'] ?? null;
if (!$product) return;

// Ép kiểu mảng thành object (vì hàm single() trong Database đang trả về mảng kết hợp FETCH_ASSOC)
if (is_array($product)) {
    $product = (object) $product;
}

// Lấy dữ liệu an toàn (Hỗ trợ cả Object từ PDO FETCH_OBJ)
$p_name = $product->name ?? '';
$p_sku = $product->sku ?? 'Đang cập nhật';
$p_price = $product->price ?? 0;
$p_size_raw = $product->size ?? 'ONESIZE';
$p_color = $product->color ?? 'Đang cập nhật';
$p_material = $product->material ?? 'Đang cập nhật';
$p_usage = $product->usage_info ?? 'Đang cập nhật';
$p_size_dim = $product->size_dim ?? 'Đang cập nhật';
$p_desc = $product->description ?? 'Đang cập nhật';
$p_image = $product->image_url ?? $product->image ?? ''; // Dự phòng trường hợp cột DB tên là 'image' thay vì 'image_url'
$p_rating = isset($product->rating) ? round($product->rating) : 0; 
$p_review_count = $product->review_count ?? 0;
$p_stock = $product->stock_quantity ?? 0;

// Xử lý mảng hình ảnh (Gom ảnh chính và các ảnh phụ từ bảng product_image)
$galleryImages = [];
if (!empty($p_image)) {
    $galleryImages[] = strpos($p_image, 'http') === 0 ? $p_image : URLROOT . $p_image;
}
foreach ($data['images'] ?? [] as $imgObj) {
    $imgUrl = is_object($imgObj) ? ($imgObj->image_url ?? $imgObj->image ?? '') : ($imgObj['image_url'] ?? $imgObj['image'] ?? '');
    if (!empty($imgUrl)) {
        $fullUrl = strpos($imgUrl, 'http') === 0 ? $imgUrl : URLROOT . $imgUrl;
        if (!in_array($fullUrl, $galleryImages)) {
            $galleryImages[] = $fullUrl;
        }
    }
}

// Ảnh mặc định nếu DB chưa có ảnh
if (empty($galleryImages)) {
    $galleryImages[] = "https://placehold.co/800x800/f8fafc/64748b?text=" . urlencode($p_name);
}

// Xử lý chuỗi Size thành mảng
$sizes = array_map('trim', explode(',', $p_size_raw));
if (empty($sizes) || $sizes[0] === '') {
    $sizes = ['ONESIZE'];
}
?>

<!-- Nhúng thư viện CSS Fotorama -->
<link href="https://cdnjs.cloudflare.com/ajax/libs/fotorama/4.6.4/fotorama.css" rel="stylesheet">

<div class="bg-white py-12">
    <div class="container mx-auto px-4">
        
        <!-- Breadcrumb (Tùy chọn cho UX) -->
        <nav class="flex text-sm text-slate-500 mb-8">
            <a href="<?= htmlspecialchars(($appBaseUrl ?? '') . '/') ?>" class="hover:text-amber-600 transition-colors">Trang chủ</a>
            <span class="mx-2">/</span>
            <a href="<?= htmlspecialchars(($appBaseUrl ?? '') . '/client/Products') ?>" class="hover:text-amber-600 transition-colors">Sản phẩm</a>
            <span class="mx-2">/</span>
            <span class="text-slate-900 truncate"><?= htmlspecialchars($p_name) ?></span>
        </nav>

        <?php require __DIR__ . '/components/detail_info.php'; ?>
        <?php require __DIR__ . '/components/detail_specs.php'; ?>
        <?php require __DIR__ . '/components/detail_reviews.php'; ?>
        <?php require __DIR__ . '/components/detail_related.php'; ?>
    </div>
</div>

<?php require __DIR__ . '/components/detail_scripts.php'; ?>