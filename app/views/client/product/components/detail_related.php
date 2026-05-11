<!-- Sản phẩm liên quan (Có thể bạn cũng sẽ thích) -->
<?php if (!empty($data['relatedProducts'])): ?>
<div class="mt-24 pt-16 border-t border-slate-200">
    <h2 class="text-2xl md:text-3xl font-serif font-bold text-slate-900 mb-10 text-center">Có thể bạn cũng sẽ thích</h2>
    <div class="relative px-8 md:px-16">
        <div class="owl-carousel owl-theme related-carousel">
            <?php 
            $originalProduct = $product ?? null; // Lưu tạm sản phẩm hiện tại
            foreach ($data['relatedProducts'] as $relProduct): 
                $product = $relProduct; // Gán lại để component product_card có thể dùng
            ?>
                <div class="item py-2">
                    <?php require __DIR__ . '/product_card.php'; ?>
                </div>
            <?php endforeach; 
            $product = $originalProduct; // Trả lại như cũ sau vòng lặp
            ?>
        </div>
    </div>
</div>
<?php endif; ?>