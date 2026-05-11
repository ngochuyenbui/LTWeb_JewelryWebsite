<!-- Khung thông tin chính -->
<div class="flex flex-col lg:flex-row gap-12 lg:gap-10">
    
    <!-- Cột trái: Hình ảnh (Thumbnails trái, Ảnh chính dùng Fotorama + Kính lúp) -->
    <div class="w-full lg:w-1/2 flex flex-col-reverse md:flex-row gap-4 h-fit">
        <!-- Danh sách ảnh nhỏ (Thumbnails custom để nằm bên trái) -->
        <div class="w-full md:w-20 lg:w-24 flex md:flex-col gap-3 overflow-x-auto md:overflow-y-auto md:max-h-[600px] custom-scrollbar shrink-0 pb-2 md:pb-0" id="custom-thumbnails">
            <?php foreach ($galleryImages ?? [] as $index => $img): ?>
                <button type="button" class="thumbnail-btn w-20 md:w-full shrink-0 border-2 <?= $index === 0 ? 'border-amber-500' : 'border-transparent' ?> hover:border-amber-500 transition-colors" data-index="<?= $index ?>">
                    <img src="<?= htmlspecialchars($img) ?>" class="w-full h-auto" alt="Thumbnail">
                </button>
            <?php endforeach; ?>
        </div>

        <!-- Khung chứa Fotorama và Kính lúp -->
        <div class="flex-1 relative cursor-crosshair group overflow-hidden bg-white" id="fotorama-container">
            <div class="fotorama" data-nav="false" data-allowfullscreen="true" data-width="100%" data-fit="contain" data-transition="crossfade" data-keyboard="true">
                <?php foreach ($galleryImages ?? [] as $img): ?>
                    <img src="<?= htmlspecialchars($img) ?>">
                <?php endforeach; ?>
            </div>
            
            <!-- Kính lúp Magic Zoom -->
            <div id="zoom-lens" class="absolute hidden pointer-events-none rounded-full border-4 border-white shadow-2xl w-48 h-48 bg-no-repeat bg-white z-[100]" style="box-shadow: 0 10px 25px rgba(0,0,0,0.3);"></div>
        </div>
    </div>

    <!-- Cột phải: Thông tin & Đặt hàng -->
    <div class="w-full lg:w-1/2 flex flex-col pt-2 lg:pt-6">
        <h1 class="text-3xl md:text-4xl font-serif font-bold text-slate-900 mb-3 leading-tight">
            <?= htmlspecialchars($p_name ?? '') ?>
        </h1>

        <!-- Đánh giá (Star Rating) -->
        <div class="flex items-center mb-4">
            <div class="flex text-amber-400">
                <?php for ($i = 1; $i <= 5; $i++): ?>
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 <?= $i <= ($p_rating ?? 0) ? 'fill-current' : 'text-slate-300 fill-current' ?>" viewBox="0 0 24 24">
                        <path d="M12 17.27L18.18 21l-1.64-7.03L22 9.24l-7.19-.61L12 2 9.19 8.63 2 9.24l5.46 4.73L5.82 21z"/>
                    </svg>
                <?php endfor; ?>
            </div>
            <span class="text-sm text-slate-500 ml-2">(<?= $p_review_count ?? 0?> đánh giá)</span>
        </div>
        
        <div class="flex items-center gap-4 text-sm text-slate-500 mb-6 pb-6 border-b border-slate-200">
            <span>Mã sản phẩm: <span class="font-medium text-slate-900"><?= htmlspecialchars($p_sku ?? 'Đang cập nhật') ?></span></span>
        </div>

        <!-- Tình trạng kho -->
        <?php if (($p_stock ?? 0) <= 0): ?>
            <div class="mb-4">
                <span class="inline-block bg-red-100 text-red-700 text-sm font-bold px-3 py-1 rounded-sm uppercase tracking-wide border border-red-200">
                    Đã hết hàng
                </span>
            </div>
        <?php else: ?>
             <div class="mb-4">
                <span class="text-sm text-slate-500">Tình trạng: <span class="text-emerald-600 font-medium">Còn hàng</span></span>
             </div>
        <?php endif; ?>

        <div class="text-3xl font-bold text-amber-600 mb-8">
            <?= number_format($p_price ?? 0, 0, ',', '.') ?> đ
        </div>

        <!-- Phần Chọn Size -->
        <div class="mb-8">
            <h3 class="mb-3 text-sm font-semibold text-slate-900 uppercase tracking-wide">Kích cỡ</h3>
            <div class="flex flex-wrap gap-3">
                <?php foreach ($sizes ?? [] as $index => $s): ?>
                    <label class="cursor-pointer">
                        <input type="radio" name="selected_size" value="<?= htmlspecialchars($s) ?>" class="peer sr-only" <?= $index === 0 ? 'checked' : '' ?>>
                        <div class="min-w-12 h-11 px-4 flex items-center justify-center border border-slate-300 text-sm font-medium text-slate-700 peer-checked:bg-slate-900 peer-checked:text-white peer-checked:border-slate-900 hover:border-slate-900 transition-colors">
                            <?= htmlspecialchars($s) ?>
                        </div>
                    </label>
                <?php endforeach; ?>
            </div>
        </div>

        <!-- Số lượng -->
        <div class="mb-8">
            <h3 class="text-sm font-semibold text-slate-900 uppercase tracking-wide mb-3">Số lượng</h3>
            <div class="flex items-center border border-slate-300 w-32 h-12 <?= ($p_stock ?? 0) <= 0 ? 'opacity-50 pointer-events-none' : '' ?>">
                <button type="button" id="decrease-qty" class="w-10 h-full flex items-center justify-center text-slate-500 hover:text-slate-900 hover:bg-slate-50 transition-colors focus:outline-none" <?= ($p_stock ?? 0) <= 0 ? 'disabled' : '' ?>>
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 12H4" /></svg>
                </button>
                <input type="number" id="product-qty" name="quantity" value="<?= ($p_stock ?? 0) <= 0 ? 0 : 1 ?>" min="1" max="<?= $p_stock ?? 0 ?>" class="w-full h-full text-center text-slate-900 font-medium focus:outline-none border-none p-0 appearance-none [-moz-appearance:_textfield] [&::-webkit-outer-spin-button]:appearance-none [&::-webkit-inner-spin-button]:appearance-none" <?= ($p_stock ?? 0) <= 0 ? 'disabled' : '' ?>>
                <button type="button" id="increase-qty" class="w-10 h-full flex items-center justify-center text-slate-500 hover:text-slate-900 hover:bg-slate-50 transition-colors focus:outline-none" <?= ($p_stock ?? 0) <= 0 ? 'disabled' : '' ?>>
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4" /></svg>
                </button>
            </div>
        </div>

        <!-- Nút thao tác -->
        <div class="flex flex-col sm:flex-row gap-4 ">
            <button type="button" id="btn-add-to-cart" data-id="<?= htmlspecialchars($product->productId ?? 0) ?>" class="flex-1 border border-slate-900 text-slate-900 h-14 font-semibold uppercase tracking-wide transition-colors flex items-center justify-center gap-2 <?= ($p_stock ?? 0) <= 0 ? 'bg-slate-100 text-slate-400 border-slate-200 cursor-not-allowed' : 'bg-white hover:bg-slate-50' ?>" <?= ($p_stock ?? 0) <= 0 ? 'disabled' : '' ?>>
                <svg xmlns="http://www.w3.org/2000/svg" class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 3h2l.4 2M7 13h10l4-8H5.4M7 13L5.4 5M7 13l-2.293 2.293c-.63.63-.184 1.707.707 1.707H17m0 0a2 2 0 100 4 2 2 0 000-4zm-8 2a2 2 0 11-4 0 2 2 0 014 0z" />
                </svg>
                Thêm vào giỏ
            </button>
            <button type="button" id="btn-buy-now" class="flex-1 text-white h-14 font-semibold uppercase tracking-wide transition-colors <?= ($p_stock ?? 0) <= 0 ? 'bg-slate-300 cursor-not-allowed' : 'bg-amber-600 hover:bg-amber-700' ?>" <?= ($p_stock ?? 0) <= 0 ? 'disabled' : '' ?>>
                Mua ngay
            </button>
        </div>
    </div>
</div>