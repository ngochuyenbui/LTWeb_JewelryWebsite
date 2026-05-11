<!-- Phần chi tiết bên dưới (Mô tả & Thông số) -->
<div class="mt-20 pt-16 border-t border-slate-200">
    <div class="grid grid-cols-1 md:grid-cols-2 gap-16 lg:gap-24">
        <!-- Mô tả chi tiết -->
        <div>
            <h2 class="text-2xl font-serif font-bold text-slate-900 mb-6">Chi tiết sản phẩm</h2>
            <div class="text-slate-600 leading-relaxed space-y-4">
                <?= nl2br(htmlspecialchars($p_desc ?? '')) ?>
            </div>
        </div>

        <!-- Thông số kỹ thuật -->
        <div>
            <h2 class="text-2xl font-serif font-bold text-slate-900 mb-6">Thông số kỹ thuật</h2>
            <dl class="divide-y divide-slate-200 text-sm">
                <div class="py-4 flex justify-between">
                    <dt class="text-slate-500">Màu sắc</dt>
                    <dd class="font-medium text-slate-900"><?= htmlspecialchars($p_color ?? '') ?></dd>
                </div>
                <div class="py-4 flex justify-between">
                    <dt class="text-slate-500">Chất liệu</dt>
                    <dd class="font-medium text-slate-900"><?= htmlspecialchars($p_material ?? '') ?></dd>
                </div>
                <div class="py-4 flex justify-between">
                    <dt class="text-slate-500">Kích cỡ chi tiết</dt>
                    <dd class="font-medium text-slate-900 w-2/3 text-right"><?= htmlspecialchars($p_size_dim ?? '') ?></dd>
                </div>
                <div class="py-4 flex justify-between items-start">
                    <dt class="text-slate-500 w-1/3">Cách bảo quản</dt>
                    <dd class="font-medium text-slate-900 w-2/3 text-right"><?= htmlspecialchars($p_usage ?? '') ?></dd>
                </div>
            </dl>
        </div>
    </div>
</div>