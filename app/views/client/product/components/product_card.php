<?php
// Lấy dữ liệu từ biến $product trong vòng lặp (hỗ trợ cả dạng Object và Mảng)
$p_id = $product->productId ?? 0;
$p_name = $product->name ?? '';
$p_price = $product->price ?? 0;
$p_image = $product->image_url ?? '/assets/default-product.jpg';
$p_image = strpos($p_image, 'http') === 0 ? $p_image : URLROOT . $p_image;
$p_stock = $product->stock_quantity ?? 0;

// Lấy dữ liệu đánh giá thật từ Database
$p_rating = isset($product->rating) ? round($product->rating) : 0; 
$p_review_count = $product->review_count ?? 0;

// Khởi tạo URL Chi tiết sản phẩm
$detailUrl = ($appBaseUrl ?? '') . '/client/Products/detail/' . $p_id;
?>
<article class="group w-full bg-white border border-slate-200 overflow-hidden hover:shadow-lg transition-all duration-300 flex flex-col h-full">
    <!-- Hình ảnh -->
    <a href="<?= htmlspecialchars($detailUrl) ?>" class="relative block overflow-hidden aspect-[4/5] bg-slate-50/30">
        <img src="<?= htmlspecialchars($p_image) ?>" alt="<?= htmlspecialchars($p_name) ?>" class="object-contain w-full h-full p-2 transition-transform duration-500 <?= $p_stock <= 0 ? 'opacity-50 grayscale' : 'group-hover:scale-105' ?>" loading="lazy">
        <?php if ($p_stock <= 0): ?>
            <div class="absolute top-3 left-3 bg-red-600 text-white text-[10px] font-bold px-2 py-1 rounded shadow-sm z-10 uppercase tracking-widest">
                Hết hàng
            </div>
        <?php endif; ?>
    </a>
    
    <!-- Thông tin -->
    <div class="p-5 flex flex-col flex-1">
        <!-- Tên sản phẩm -->
        <a href="<?= htmlspecialchars($detailUrl) ?>" class="text-slate-900 font-serif font-medium text-lg leading-tight hover:text-amber-600 transition-colors mb-2 line-clamp-2 min-h-[3rem]">
            <?= htmlspecialchars($p_name) ?>
        </a>

        <!-- Đánh giá (Star Rating) -->
        <div class="flex items-center mb-3">
            <div class="flex text-amber-400">
                <?php for ($i = 1; $i <= 5; $i++): ?>
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 <?= $i <= $p_rating ? 'fill-current' : 'text-slate-300 fill-current' ?>" viewBox="0 0 24 24">
                        <path d="M12 17.27L18.18 21l-1.64-7.03L22 9.24l-7.19-.61L12 2 9.19 8.63 2 9.24l5.46 4.73L5.82 21z"/>
                    </svg>
                <?php endfor; ?>
            </div>
            <span class="text-xs text-slate-500 ml-2">(<?= $p_review_count ?>)</span>
        </div>

        <!-- Giá -->
        <div class="mt-auto font-bold text-amber-600 text-lg">
            <?= number_format($p_price, 0, ',', '.') ?> đ
        </div>
    </div>
</article>