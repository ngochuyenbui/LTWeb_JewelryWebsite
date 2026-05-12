<div class="bg-slate-50 py-16 pt-24">
    <div class="flex flex-col gap-10 container mx-auto p-4">
        <!-- Tiêu đề trang -->
        <div class=" mb-8 flex flex-col items-center">
            <p>BỘ SƯU TẬP</p>
            <h1 class="text-3xl font-bold font-serif text-slate-900">Sản Phẩm Của Chúng Tôi</h1>
        </div>

        <!-- Carousel Danh mục -->
        <div class="mb-8 relative px-2">
            <?php require __DIR__ . '/components/category_carousel.php'; ?>
        </div>
        <div class="flex justify-end">
            <div class="w-full max-w-md">
                <input type="text" id="search-input" value="<?= htmlspecialchars($_GET['search'] ?? '') ?>" placeholder="Tìm tên, mã sản phẩm..." class="w-full px-6 py-2 border border-slate-300 rounded-md focus:outline-none focus:border-amber-500 focus:ring-1 focus:ring-amber-500 text-sm transition-colors">
            </div>
        </div>
        <div class="grid grid-cols-1 lg:grid-cols-5 gap-8 lg:gap-4 items-start">
            <div id="sidebar-wrapper" class="hidden lg:block fixed inset-0 z-50 w-full h-full bg-white p-7 overflow-y-auto lg:relative lg:z-10 lg:w-auto lg:h-[calc(100vh-120px)] lg:col-span-1 lg:border lg:border-slate-200 lg:shadow-sm lg:sticky lg:top-24 lg:shrink-0 custom-scrollbar">
                <button type="button" id="close-sidebar-btn" class="lg:hidden absolute top-4 right-4 p-2 text-slate-500 hover:text-slate-700 focus:outline-none">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" /></svg>
                </button>
                <?php require __DIR__ . '/components/sidebar_filter.php'; ?>
            </div>
            <div class="lg:col-span-4">
                <div class="flex flex-wrap justify-between items-center mb-6 gap-4">

                    <div class="flex items-center gap-4">
                            <button type="button" id="mobile-filter-btn" class="lg:hidden flex items-center gap-2 px-4 py-2 bg-white border border-slate-300 shadow-sm text-sm text-slate-700 hover:bg-slate-50 focus:outline-none focus:ring-2 focus:ring-amber-500">
                                <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 4a1 1 0 011-1h16a1 1 0 011 1v2.586a1 1 0 01-.293.707l-6.414 6.414a1 1 0 00-.293.707V17l-4 4v-6.586a1 1 0 00-.293-.707L3.293 7.293A1 1 0 013 6.586V4z" />
                                </svg>
                                Bộ lọc
                            </button>

                        <div id="category-title-display" class="hidden lg:block text-sm">
                            <?php
                            $selectedCategoryName = '';
                            $categoryId = $_GET['category'] ?? null;
                            if ($categoryId) {
                                foreach ($data['categories'] ?? [] as $cat) {
                                    $c_id = is_object($cat) ? ($cat->cateId ?? '') : ($cat['cateId'] ?? '');
                                    if ($c_id == $categoryId) {
                                        $selectedCategoryName = is_object($cat) ? ($cat->name ?? '') : ($cat['name'] ?? '');
                                        break;
                                    }
                                }
                            }
                            ?>

                            <?php if ($selectedCategoryName): ?>
                                <span class="text-slate-700">Danh mục: <span class="font-bold text-slate-900"><?= htmlspecialchars($selectedCategoryName) ?></span></span>
                                <a href="#" id="show-all-categories-btn" class="ml-2 text-amber-600 hover:text-amber-700 hover:underline border-l border-slate-300 pl-2">Hiển thị tất cả</a>
                            <?php else: ?>
                                <span class="text-slate-500">Tất cả sản phẩm</span>
                            <?php endif; ?>
                        </div>
                    </div>
                    <!-- Cập nhật value để AJAX lấy dữ liệu Sort -->
                    <select id="sort-select" name="sort" class="border-slate-300 bg-white text-sm shadow-sm focus:border-amber-500 focus:ring-amber-500 py-2 pl-3 pr-8 cursor-pointer">
                        <option value="default" <?= ($_GET['sort'] ?? '') === 'default' ? 'selected' : '' ?>>Sắp xếp: Mặc định</option>
                        <option value="price_asc" <?= ($_GET['sort'] ?? '') === 'price_asc' ? 'selected' : '' ?>>Giá: Tăng dần</option>
                        <option value="price_desc" <?= ($_GET['sort'] ?? '') === 'price_desc' ? 'selected' : '' ?>>Giá: Giảm dần</option>
                    </select>
                </div>


                <!-- Gói vùng lưới và phân trang để cập nhật AJAX chung -->
                <div id="products-container" class="transition-opacity duration-300">
                    <div id="products-grid" class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-6">
                        <?php foreach ($data['products'] ?? [] as $product): ?>
                            <?php require __DIR__ . '/components/product_card.php'; ?>
                        <?php endforeach; ?>
                        <?php if (empty($data['products'])): ?>
                            <div class="col-span-full py-10 text-center text-slate-500">Không tìm thấy sản phẩm nào phù hợp.</div>
                        <?php endif; ?>
                    </div>

                    <?php if (($data['totalPages'] ?? 0) > 1): ?>
                        <div class="mt-12 flex justify-center items-center gap-2">
                            <?php for ($i = 1; $i <= ($data['totalPages'] ?? 1); $i++): ?>
                                <button type="button" data-page="<?= $i ?>" class="pagination-btn w-10 h-10 flex items-center justify-center border <?= $i == ($data['currentPage'] ?? 1) ? 'bg-amber-600 text-white border-amber-600' : 'bg-white text-slate-600 border-slate-300 hover:border-amber-500 hover:text-amber-600' ?> transition-colors">
                                    <?= $i ?>
                                </button>
                            <?php endfor; ?>
                        </div>
                    <?php endif; ?>
                </div>
            </div>
        </div>

        <script>
            document.addEventListener('DOMContentLoaded', function() {
                const filterBtn = document.getElementById('mobile-filter-btn');
                const closeBtn = document.getElementById('close-sidebar-btn');
                const sidebarWrapper = document.getElementById('sidebar-wrapper');

                function toggleSidebar() {
                    sidebarWrapper.classList.toggle('hidden');
                }

                if (filterBtn) filterBtn.addEventListener('click', toggleSidebar);
                if (closeBtn) closeBtn.addEventListener('click', toggleSidebar);
            });
        </script>

    </div>
</div>