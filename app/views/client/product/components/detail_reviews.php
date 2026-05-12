<!-- Phần Bình luận & Đánh giá -->
<div class="mt-20 pt-16 border-t border-slate-200">
    <h2 class="text-2xl font-serif font-bold text-slate-900 mb-8">Đánh giá từ khách hàng</h2>

    <div class="grid grid-cols-1 lg:grid-cols-3 gap-12">
        <!-- Cột hiển thị bình luận -->
        <div class="lg:col-span-2 space-y-8">
            <?php if (!empty($data['comments'])): ?>
                <?php foreach ($data['comments'] as $comment): ?>
                    <?php
                    $c_name = is_object($comment) ? ($comment->fullname ?? 'Khách hàng') : ($comment['fullname'] ?? 'Khách hàng');
                    $c_content = is_object($comment) ? ($comment->content ?? '') : ($comment['content'] ?? '');
                    $c_rating = is_object($comment) ? ($comment->rating ?? 5) : ($comment['rating'] ?? 5);
                    $c_date = is_object($comment) ? ($comment->created_at ?? '') : ($comment['created_at'] ?? '');
                    ?>
                    <div class="border-b border-slate-100 pb-6">
                        <div class="flex items-center justify-between mb-2">
                            <div class="font-medium text-slate-900"><?= htmlspecialchars($c_name) ?></div>
                            <?php if($c_date): ?><div class="text-sm text-slate-500"><?= htmlspecialchars($c_date) ?></div><?php endif; ?>
                        </div>
                        <div class="flex text-amber-400 mb-3">
                            <?php for ($i = 1; $i <= 5; $i++): ?>
                                <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 <?= $i <= $c_rating ? 'fill-current' : 'text-slate-300 fill-current' ?>" viewBox="0 0 24 24"><path d="M12 17.27L18.18 21l-1.64-7.03L22 9.24l-7.19-.61L12 2 9.19 8.63 2 9.24l5.46 4.73L5.82 21z"/></svg>
                            <?php endfor; ?>
                        </div>
                        <p class="text-slate-600 text-sm leading-relaxed"><?= nl2br(htmlspecialchars($c_content)) ?></p>
                    </div>
                <?php endforeach; ?>
            <?php else: ?>
                <p class="text-slate-500 italic">Chưa có đánh giá nào cho sản phẩm này. Hãy là người đầu tiên đánh giá!</p>
            <?php endif; ?>
        </div>

        <!-- Cột Form Viết bình luận -->
        <div class="lg:col-span-1">
            <div class="bg-slate-50 p-6 border border-slate-200">
                <h3 class="text-lg font-serif font-bold text-slate-900 mb-4">Viết đánh giá của bạn</h3>

                <?php if (isset($_SESSION['user_id']) && ($_SESSION['user_role'] ?? 'member') === 'member'): ?>
                    <form action="<?= htmlspecialchars(($appBaseUrl ?? '') . '/client/Comments/add') ?>" method="POST" class="flex flex-col gap-4">
                        <input type="hidden" name="contentId" value="<?= htmlspecialchars(is_object($product ?? null) ? ($product->contentId ?? '') : ($product['contentId'] ?? '')) ?>">
                        <input type="hidden" name="productId" value="<?= htmlspecialchars(is_object($product ?? null) ? ($product->productId ?? '') : ($product['productId'] ?? '')) ?>">

                        <!-- Các trường Đánh giá sao, Nội dung, Button (Đã thu gọn cho gọn diff) -->
                        <?php require __DIR__ . '/review_form.php'; ?>
                    </form>
                <?php else: ?>
                    <div class="text-center py-6">
                        <p class="text-sm text-slate-600 mb-4">Bạn cần đăng nhập để đánh giá sản phẩm này.</p>
                        <a href="/Login" class="inline-block px-6 py-2 border border-slate-900 text-slate-900 font-medium hover:bg-slate-900 hover:text-white transition-colors">Đăng nhập ngay</a>
                    </div>
                <?php endif; ?>
            </div>
        </div>
    </div>
</div>