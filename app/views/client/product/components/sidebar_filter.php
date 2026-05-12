<div class="flex justify-between items-center mb-6">
    <h3 class="text-lg font-bold font-serif text-slate-900">Bộ Lọc</h3>
    <a id="clear-filter-btn" href="?<?= !empty($_GET['category']) ? 'category=' . urlencode($_GET['category']) : '' ?>" class="text-sm text-amber-600 hover:text-amber-700 underline">Xóa lọc</a>
</div>

<form id="filter-form" action="" method="GET">
    <input type="hidden" name="page" id="page-input" value="<?= htmlspecialchars($_GET['page'] ?? 1) ?>">

    <?php if (!empty($_GET['category'])): ?>
        <input type="hidden" name="category" value="<?= htmlspecialchars($_GET['category']) ?>">
    <?php endif; ?>

    <!-- Price slider -->
    <div class="mb-6">
        <h4 class="font-medium font-serif text-slate-900 mb-3">Mức Giá</h4>
        <?php
        $minLimit = isset($data['priceRange']['min_price']) ? (int)$data['priceRange']['min_price'] : 0;
        $maxLimit = isset($data['priceRange']['max_price']) ? (int)$data['priceRange']['max_price'] : 50000000;

        if ($maxLimit <= $minLimit) {
            $maxLimit = $minLimit + 500000;
        }

        $minPrice = isset($_GET['min_price']) && $_GET['min_price'] !== '' ? (int)$_GET['min_price'] : $minLimit;
        $maxPrice = isset($_GET['max_price']) && $_GET['max_price'] !== '' ? (int)$_GET['max_price'] : $maxLimit;

        $minPrice = max($minLimit, min($minPrice, $maxLimit));
        $maxPrice = max($minLimit, min($maxPrice, $maxLimit));

        $step = ($maxLimit - $minLimit > 10000000) ? 500000 : 100000;
        ?>
        <div class="px-2 pt-2 pb-2">
            <!-- Khung vẽ jQuery UI Slider -->
            <div id="price-slider-range" class="mt-2 mb-4"></div>
            <div class="flex items-center justify-between text-xs text-slate-600 mt-4 font-medium">
                <span id="min-price-display"><?= number_format($minPrice, 0, ',', '.') ?> đ</span>
                <span id="max-price-display"><?= number_format($maxPrice, 0, ',', '.') ?> đ</span>
            </div>
            <!-- Input ẩn dùng để submit lên URL -->
            <input type="hidden" name="min_price" id="min-price-input" value="<?= $minPrice ?>">
            <input type="hidden" name="max_price" id="max-price-input" value="<?= $maxPrice ?>">

            <!-- Lưu trữ dữ liệu cấu hình ban đầu để JS đọc -->
            <div id="price-slider-data"
                 data-min="<?= $minLimit ?>"
                 data-max="<?= $maxLimit ?>"
                 data-step="<?= $step ?>"
                 data-current-min="<?= $minPrice ?>"
                 data-current-max="<?= $maxPrice ?>"
                 class="hidden"></div>
        </div>
    </div>

    <style>
    .ui-slider-horizontal .ui-slider-handle {
        border-radius: 50%;
        background: #fff !important;
        cursor: pointer;
    }
    .ui-widget-content {
        border: none !important;
        background: #e2e8f0 !important;
        height: 0.375rem !important;
    }
    </style>

    <!-- Size -->
    <div class="mb-6">
        <h4 class="font-medium font-serif text-slate-900 mb-3">Kích Thước</h4>

        <?php
        $numericSizes = [];
        $stringSizes = [];
        foreach ($data['sizes'] ?? [] as $sizeObj) {
            $sizeVal = is_object($sizeObj) ? ($sizeObj->size ?? '') : ($sizeObj['size'] ?? '');
            if(empty($sizeVal)) continue;

            // Tách các size được lưu dạng chuỗi (VD: "50, 55, 58") thành mảng các size đơn lẻ
            $parts = array_map('trim', explode(',', $sizeVal));

            foreach ($parts as $part) {
                if (empty($part)) continue;
                if (is_numeric($part)) {
                    if (!in_array($part, $numericSizes)) $numericSizes[] = $part;
                } else {
                    if (!in_array($part, $stringSizes)) $stringSizes[] = $part;
                }
            }
        }

        // Sắp xếp lại theo thứ tự để giao diện gọn gàng hơn
        sort($numericSizes, SORT_NUMERIC);
        sort($stringSizes);
        ?>

        <?php if (!empty($stringSizes)): ?>
            <div class="mb-3">
                <div class="text-xs text-slate-500 uppercase tracking-wider mb-2 font-semibold">Chữ cái</div>
                <div class="flex flex-wrap gap-2">
                    <?php foreach ($stringSizes as $sizeVal): ?>
                        <?php $isChecked = isset($_GET['size']) && is_array($_GET['size']) && in_array($sizeVal, $_GET['size']); ?>
                        <label class="cursor-pointer">
                            <input type="checkbox" name="size[]" value="<?= htmlspecialchars($sizeVal) ?>" class="peer sr-only" <?= $isChecked ? 'checked' : '' ?>>
                            <div class="px-3 py-1.5 border border-slate-300 text-sm text-slate-600 peer-checked:bg-amber-500 peer-checked:text-white peer-checked:border-amber-500 hover:border-amber-500 transition-colors">
                                <?= htmlspecialchars($sizeVal) ?>
                            </div>
                        </label>
                    <?php endforeach; ?>
                </div>
            </div>
        <?php endif; ?>

        <?php if (!empty($numericSizes)): ?>
            <div>
                <div class="text-xs text-slate-500 uppercase tracking-wider mb-2 font-semibold">Số đo</div>
                <div class="flex flex-wrap gap-2">
                    <?php foreach ($numericSizes as $sizeVal): ?>
                        <?php $isChecked = isset($_GET['size']) && is_array($_GET['size']) && in_array($sizeVal, $_GET['size']); ?>
                        <label class="cursor-pointer">
                            <input type="checkbox" name="size[]" value="<?= htmlspecialchars($sizeVal) ?>" class="peer sr-only" <?= $isChecked ? 'checked' : '' ?>>
                            <div class="px-3 py-1.5 border border-slate-300 text-sm text-slate-600 peer-checked:bg-amber-500 peer-checked:text-white peer-checked:border-amber-500 hover:border-amber-500 transition-colors">
                                <?= htmlspecialchars($sizeVal) ?>
                            </div>
                        </label>
                    <?php endforeach; ?>
                </div>
            </div>
        <?php endif; ?>

        <?php if (empty($data['sizes'])): ?>
            <p class="text-sm text-slate-500 italic">Chưa có dữ liệu</p>
        <?php endif; ?>
    </div>

    <!-- Color -->
    <div class="mb-6">
        <h4 class="font-medium font-serif text-slate-900 mb-3">Màu Sắc</h4>
        <div class="space-y-3">
            <?php foreach ($data['colors'] ?? [] as $colorObj): ?>
                <?php
                $colorVal = is_object($colorObj) ? ($colorObj->color ?? '') : ($colorObj['color'] ?? '');
                if(empty($colorVal)) continue;

                $isChecked = isset($_GET['color']) && is_array($_GET['color']) && in_array($colorVal, $_GET['color']);
                ?>
                <label class="flex items-center gap-3 cursor-pointer group">
                    <input type="checkbox" name="color[]" value="<?= htmlspecialchars($colorVal) ?>" class="w-4 h-4 text-amber-500 rounded border-slate-300 focus:ring-amber-500" <?= $isChecked ? 'checked' : '' ?>>
                    <span class="text-slate-600 group-hover:text-amber-600 transition-colors text-sm"><?= htmlspecialchars(ucfirst($colorVal)) ?></span>
                </label>
            <?php endforeach; ?>
            <?php if (empty($data['colors'])): ?>
                <p class="text-sm text-slate-500 italic">Chưa có dữ liệu</p>
            <?php endif; ?>
        </div>
    </div>

</form>

<script>
$(document).ready(function() {
    // Filter
    $(document).on('submit', '#filter-form', function(e) {
        e.preventDefault();
        var form = $(this);

        // Lấy thêm giá trị sort từ select box gộp chung vào form
        var sortVal = $('#sort-select').val();
        var formData = form.serialize();
        if(sortVal) {
            formData += '&sort=' + sortVal;
        }
        var searchVal = $('#search-input').length ? $('#search-input').val() : '';
        if(searchVal) {
            formData += '&search=' + encodeURIComponent(searchVal);
        }

        var url = window.location.pathname + '?' + formData;
        window.history.pushState({path: url}, '', url);

        $('#products-container').css('opacity', '0.5');

        $.ajax({
            url: url,
            type: 'GET',
            success: function(response) {
                var newContent = $(response).find('#products-container').html();
                $('#products-container').html(newContent).css('opacity', '1');

                // Cập nhật lại tiêu đề danh mục sau khi AJAX chạy xong
                var newTitle = $(response).find('#category-title-display').html();
                $('#category-title-display').html(newTitle);
            },
            error: function() {
                alert('Đã xảy ra lỗi khi lọc sản phẩm!');
                $('#products-container').css('opacity', '1');
            }
        });
    });

    // Khởi tạo jQuery UI Slider
    function initPriceSlider() {
        var sliderData = $('#price-slider-data');
        if (!sliderData.length) return;

        var minLimit = parseInt(sliderData.data('min'));
        var maxLimit = parseInt(sliderData.data('max'));
        var step = parseInt(sliderData.data('step'));
        var currentMin = parseInt(sliderData.data('current-min'));
        var currentMax = parseInt(sliderData.data('current-max'));

        $("#price-slider-range").slider({
            range: true,
            min: minLimit,
            max: maxLimit,
            step: step,
            values: [currentMin, currentMax],
            slide: function(event, ui) {
                // Cập nhật giá trị hiển thị liên tục trong lúc đang kéo
                $("#min-price-display").text(ui.values[0].toLocaleString('vi-VN') + ' đ');
                $("#max-price-display").text(ui.values[1].toLocaleString('vi-VN') + ' đ');
            },
            change: function(event, ui) {
                // Chỉ tự động gọi AJAX chạy lọc khi người dùng ĐÃ THẢ CHUỘT
                if (event.originalEvent) {
                    $("#min-price-input").val(ui.values[0]);
                    $("#max-price-input").val(ui.values[1]);
                    $('#page-input').val(1);
                    $('#filter-form').submit();
                }
            }
        });
    }

    initPriceSlider(); // Gọi lần đầu khi vừa vào trang

    // Bắt sự kiện người dùng gõ vào ô tìm kiếm mới
    let searchTimer;
    $(document).on('input', '#search-input', function() {
        clearTimeout(searchTimer);
        searchTimer = setTimeout(() => {
            $('#page-input').val(1);
            $('#filter-form').submit();
        }, 500); // Tự động tìm sau khi ngừng gõ phím 0.5s
    });

    // Tự động submit form khi người dùng tích/bỏ tích bất kỳ Checkbox hay Radio nào
    $(document).on('change', '#filter-form input:not(#page-input)', function() {
        $('#page-input').val(1); // Reset lại trang về 1 khi đổi bộ lọc
        $('#filter-form').submit();
    });

    // Khi người dùng đổi lựa chọn Sắp xếp
    $(document).on('change', '#sort-select', function() {
        $('#page-input').val(1); // Reset lại trang về 1
        $('#filter-form').submit(); // Gọi hàm submit form AJAX bên trên
    });

    // Bắt sự kiện bấm nút Phân trang (AJAX)
    $(document).on('click', '.pagination-btn', function() {
        var page = $(this).data('page');
        $('#page-input').val(page);
        $('#filter-form').submit();
        $('html, body').animate({ scrollTop: 0 }, 300); // Tự động trượt mượt mà lên trên cùng màn hình
    });

    // Khi người dùng click vào danh mục trên Carousel (AJAX hóa)
    $(document).on('click', '.category-link', function(e) {
        e.preventDefault();
        var categoryId = $(this).data('category');
        var form = $('#filter-form');
        var currentCategory = form.find('input[name="category"]').val();

        $('#page-input').val(1); // Reset lại trang về 1
        // Xử lý bật/tắt (Toggle) category
        if (currentCategory == categoryId) {
            form.find('input[name="category"]').remove(); // Hủy chọn danh mục
            $(this).find('.category-border').removeClass('border-amber-500 shadow-md').addClass('border-transparent group-hover:border-amber-300');
        } else {
            if (form.find('input[name="category"]').length === 0) {
                form.append('<input type="hidden" name="category" value="' + categoryId + '">');
            } else {
                form.find('input[name="category"]').val(categoryId);
            }
            // Xóa viền màu tất cả, và tô viền màu cho mục vừa click
            $('.category-border').removeClass('border-amber-500 shadow-md').addClass('border-transparent group-hover:border-amber-300');
            $(this).find('.category-border').removeClass('border-transparent group-hover:border-amber-300').addClass('border-amber-500 shadow-md');
        }

        form.submit(); // Chạy bộ lọc
    });

    // Xóa chọn danh mục khi bấm nút "Hiển thị tất cả"
    $(document).on('click', '#show-all-categories-btn', function(e) {
        e.preventDefault();
        var form = $('#filter-form');
        form.find('input[name="category"]').remove();
        $('#page-input').val(1);
        $('.category-border').removeClass('border-amber-500 shadow-md').addClass('border-transparent group-hover:border-amber-300'); // Xóa viền màu ở Carousel
        form.submit();
    });

    // Clear filter
    $(document).on('click', '#clear-filter-btn', function(e) {
        e.preventDefault();
        var url = $(this).attr('href');
        if (!url || url === '?') url = window.location.pathname;

        window.history.pushState({path: url}, '', url);
        $('#products-container').css('opacity', '0.5');

        $.ajax({
            url: url,
            type: 'GET',
            success: function(response) {
                var newContent = $(response).find('#products-container').html();
                $('#products-container').html(newContent).css('opacity', '1');
                var newForm = $(response).find('#filter-form').html();
                $('#filter-form').html(newForm);
                var newTitle = $(response).find('#category-title-display').html();
                $('#category-title-display').html(newTitle);
                initPriceSlider();
            }
        });
    });
    $(window).on('popstate', function() {
        location.reload();
    });
});
</script>
