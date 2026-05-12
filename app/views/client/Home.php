<?php
$content = $content ?? [];
$images = $images ?? [];
$featuredProducts = $featuredProducts ?? [];
$homeCategories = $homeCategories ?? [];

$e = static function ($value) {
    return htmlspecialchars((string)$value, ENT_QUOTES, 'UTF-8');
};

$text = static function ($key, $default = '') use ($content, $e) {
    return $e($content[$key] ?? $default);
};

$mediaUrl = static function ($path, $fallback = '') {
    $path = str_replace('\\', '/', trim((string)$path));
    $fallback = str_replace('\\', '/', trim((string)$fallback));

    if ($path !== '' && preg_match('#^https?://#i', $path)) {
        return $path;
    }

    $publicPath = $path !== '' ? ltrim($path, '/') : '';
    if ($publicPath !== '' && defined('APPROOT') && is_file(APPROOT . '/public/' . $publicPath)) {
        return URLROOT . '/' . $publicPath;
    }

    return URLROOT . '/' . ltrim($fallback, '/');
};

$imagePath = static function ($key) use ($images) {
    return isset($images[$key]) ? ($images[$key]->filepath ?? '') : '';
};

$money = static function ($price) {
    $amount = (float)$price;
    return $amount > 0 ? number_format($amount, 0, ',', '.') . 'đ' : 'Liên hệ';
};

$shortText = static function ($value, $limit = 88) {
    $value = trim((string)$value);
    if ($value === '') {
        return '';
    }
    return mb_strlen($value, 'UTF-8') > $limit
        ? mb_substr($value, 0, $limit, 'UTF-8') . '...'
        : $value;
};

$categoryFallbacks = [
    'engagement-rings' => [
        'label' => 'Nhẫn đính hôn',
        'copy' => 'Tâm điểm cho khoảnh khắc ngỏ lời.',
        'image' => 'assets/products/showcase/ring-mesmera.jpg',
    ],
    'diamond-necklaces' => [
        'label' => 'Dây chuyền',
        'copy' => 'Dáng mảnh, sáng và dễ phối hằng ngày.',
        'image' => 'assets/products/showcase/necklace-idyllia.jpg',
    ],
    'luxury-watches' => [
        'label' => 'Đồng hồ',
        'copy' => 'Phụ kiện thời gian mang tinh thần trang sức.',
        'image' => 'assets/products/showcase/watch-millenia.jpg',
    ],
    'gold-bracelets' => [
        'label' => 'Vòng tay',
        'copy' => 'Lấp lánh vừa đủ cho mọi chuyển động.',
        'image' => 'assets/products/showcase/bracelet-matrix.jpg',
    ],
    'earing-bongtai' => [
        'label' => 'Bông tai',
        'copy' => 'Ánh sáng pha lê cho đường nét khuôn mặt.',
        'image' => 'assets/products/showcase/earrings-lucent.jpg',
    ],
];

$defaultCategories = [
    (object)['slug' => 'engagement-rings', 'name' => 'Nhẫn đính hôn', 'product_count' => 0],
    (object)['slug' => 'diamond-necklaces', 'name' => 'Dây chuyền', 'product_count' => 0],
    (object)['slug' => 'earing-bongtai', 'name' => 'Bông tai', 'product_count' => 0],
    (object)['slug' => 'gold-bracelets', 'name' => 'Vòng tay', 'product_count' => 0],
];

$categoryCards = !empty($homeCategories) ? array_slice($homeCategories, 0, 4) : $defaultCategories;

$fallbackByCategory = [
    'engagement-rings' => 'assets/products/showcase/ring-mesmera.jpg',
    'diamond-necklaces' => 'assets/products/showcase/necklace-idyllia.jpg',
    'luxury-watches' => 'assets/products/showcase/watch-millenia.jpg',
    'gold-bracelets' => 'assets/products/showcase/bracelet-matrix.jpg',
    'earing-bongtai' => 'assets/products/showcase/earrings-lucent.jpg',
];

$fallbackImages = array_values($fallbackByCategory);
$heroMain = $mediaUrl($imagePath('home_hero'), 'assets/products/showcase/ring-mesmera.jpg');
$heroTop = $mediaUrl('', 'assets/products/showcase/necklace-idyllia.jpg');
$heroBottom = $mediaUrl('', 'assets/products/showcase/earrings-lucent.jpg');
?>

<style>
    .hp {
        --ink: #17120d;
        --muted: #6f665c;
        --line: #e7dfd4;
        --paper: #fffaf3;
        --soft: #f7f1e8;
        --gold: #b7791f;
        background: #fff;
        color: var(--ink);
    }

    .hp-container {
        width: min(1180px, calc(100% - 40px));
        margin: 0 auto;
    }

    .hp-eyebrow {
        color: var(--gold);
        font-size: 12px;
        font-weight: 700;
        letter-spacing: .16em;
        text-transform: uppercase;
    }

    .hp-hero {
        background: linear-gradient(135deg, #fffaf3 0%, #f5eadc 48%, #ffffff 100%);
        border-bottom: 1px solid var(--line);
    }

    .hp-hero-inner {
        display: grid;
        grid-template-columns: minmax(0, 1fr) minmax(380px, 500px);
        gap: 54px;
        align-items: center;
        min-height: 620px;
        padding: 58px 0;
    }

    .hp-hero h1 {
        max-width: 720px;
        margin: 16px 0 0;
        font-size: clamp(38px, 5vw, 70px);
        line-height: 1.04;
        font-weight: 700;
        letter-spacing: 0;
    }

    .hp-hero-copy {
        max-width: 640px;
        margin-top: 22px;
        color: var(--muted);
        font-size: 16px;
        line-height: 1.8;
    }

    .hp-actions {
        display: flex;
        flex-wrap: wrap;
        gap: 12px;
        margin-top: 28px;
    }

    .hp-btn {
        display: inline-flex;
        align-items: center;
        justify-content: center;
        min-height: 46px;
        padding: 0 22px;
        border-radius: 8px;
        font-size: 14px;
        font-weight: 700;
        transition: .2s ease;
    }

    .hp-btn-primary {
        background: var(--ink);
        color: #fff;
    }

    .hp-btn-primary:hover {
        background: var(--gold);
    }

    .hp-btn-secondary {
        border: 1px solid #cfc2b4;
        background: rgba(255,255,255,.72);
        color: var(--ink);
    }

    .hp-btn-secondary:hover {
        border-color: var(--gold);
        color: var(--gold);
    }

    .hp-stats {
        display: grid;
        grid-template-columns: repeat(3, minmax(0, 1fr));
        gap: 12px;
        max-width: 560px;
        margin-top: 34px;
    }

    .hp-stat {
        min-height: 86px;
        border: 1px solid var(--line);
        border-radius: 10px;
        background: rgba(255,255,255,.78);
        padding: 16px;
    }

    .hp-stat strong {
        display: block;
        font-size: 24px;
        line-height: 1;
    }

    .hp-stat span {
        display: block;
        margin-top: 7px;
        color: var(--muted);
        font-size: 13px;
        line-height: 1.4;
    }

    .hp-hero-art {
        display: grid;
        grid-template-columns: 1.1fr .82fr;
        gap: 16px;
    }

    .hp-art-panel {
        display: flex;
        align-items: center;
        justify-content: center;
        overflow: hidden;
        border: 1px solid var(--line);
        border-radius: 14px;
        background: rgba(255,255,255,.82);
        box-shadow: 0 18px 50px rgba(23,18,13,.08);
    }

    .hp-art-panel img {
        width: 100%;
        height: 100%;
        object-fit: contain;
        padding: 22px;
    }

    .hp-art-main {
        height: 440px;
    }

    .hp-art-small {
        height: 212px;
    }

    .hp-art-stack {
        display: grid;
        gap: 16px;
    }

    .hp-trust {
        border-bottom: 1px solid var(--line);
        background: #fff;
    }

    .hp-trust-grid {
        display: grid;
        grid-template-columns: repeat(3, minmax(0, 1fr));
        gap: 26px;
        padding: 24px 0;
    }

    .hp-trust h2 {
        margin: 0;
        font-size: 15px;
        font-weight: 700;
    }

    .hp-trust p {
        margin: 6px 0 0;
        color: var(--muted);
        font-size: 14px;
        line-height: 1.6;
    }

    .hp-section {
        padding: 68px 0;
    }

    .hp-section-soft {
        background: var(--soft);
    }

    .hp-section-head {
        display: flex;
        align-items: flex-end;
        justify-content: space-between;
        gap: 24px;
        margin-bottom: 30px;
    }

    .hp-section-head h2 {
        max-width: 680px;
        margin: 10px 0 0;
        font-size: clamp(28px, 3vw, 42px);
        line-height: 1.15;
        font-weight: 700;
    }

    .hp-section-head p {
        max-width: 700px;
        margin: 12px 0 0;
        color: var(--muted);
        font-size: 15px;
        line-height: 1.7;
    }

    .hp-link {
        color: var(--gold);
        font-size: 14px;
        font-weight: 700;
        white-space: nowrap;
    }

    .hp-product-grid {
        display: grid;
        grid-template-columns: repeat(4, minmax(0, 1fr));
        gap: 18px;
    }

    .hp-product {
        overflow: hidden;
        border: 1px solid var(--line);
        border-radius: 12px;
        background: #fff;
        transition: .2s ease;
    }

    .hp-product:hover {
        border-color: #d8a23d;
        transform: translateY(-2px);
    }

    .hp-product-media {
        height: 250px;
        background: #fbf8f3;
        display: flex;
        align-items: center;
        justify-content: center;
        padding: 22px;
    }

    .hp-product-media img {
        width: 100%;
        height: 100%;
        object-fit: contain;
    }

    .hp-product-body {
        border-top: 1px solid var(--line);
        padding: 18px;
    }

    .hp-product-kicker {
        color: #887d70;
        font-size: 11px;
        font-weight: 800;
        letter-spacing: .12em;
        text-transform: uppercase;
    }

    .hp-product h3 {
        margin: 9px 0 0;
        font-size: 17px;
        line-height: 1.45;
        font-weight: 700;
    }

    .hp-product p {
        margin: 8px 0 0;
        color: var(--muted);
        font-size: 14px;
        line-height: 1.6;
    }

    .hp-product-footer {
        display: flex;
        align-items: center;
        justify-content: space-between;
        gap: 12px;
        margin-top: 16px;
    }

    .hp-price {
        color: var(--gold);
        font-size: 17px;
        font-weight: 800;
    }

    .hp-mini-btn {
        border: 1px solid #d8d0c7;
        border-radius: 7px;
        padding: 8px 12px;
        color: var(--ink);
        font-size: 12px;
        font-weight: 700;
    }

    .hp-story {
        display: grid;
        grid-template-columns: .95fr 1.05fr;
        gap: 44px;
        align-items: center;
    }

    .hp-story-gallery {
        display: grid;
        grid-template-columns: repeat(2, minmax(0, 1fr));
        gap: 16px;
        border-radius: 14px;
        background: var(--soft);
        padding: 16px;
    }

    .hp-story-image {
        height: 320px;
        border-radius: 10px;
        background: #fff;
        padding: 24px;
    }

    .hp-story-image img {
        width: 100%;
        height: 100%;
        object-fit: contain;
    }

    .hp-story h2 {
        margin: 10px 0 0;
        font-size: clamp(28px, 3vw, 42px);
        line-height: 1.16;
        font-weight: 700;
    }

    .hp-story p {
        color: var(--muted);
        font-size: 15px;
        line-height: 1.75;
    }

    .hp-feature-list {
        display: grid;
        grid-template-columns: repeat(2, minmax(0, 1fr));
        gap: 14px;
        margin-top: 24px;
    }

    .hp-feature {
        border: 1px solid var(--line);
        border-radius: 10px;
        padding: 18px;
    }

    .hp-feature strong {
        display: block;
        font-size: 15px;
    }

    .hp-feature span {
        display: block;
        margin-top: 7px;
        color: var(--muted);
        font-size: 14px;
        line-height: 1.6;
    }

    .hp-consult {
        background: var(--ink);
        color: #fff;
    }

    .hp-consult-inner {
        display: flex;
        align-items: center;
        justify-content: space-between;
        gap: 28px;
        padding: 56px 0;
    }

    .hp-consult h2 {
        max-width: 760px;
        margin: 10px 0 0;
        font-size: clamp(28px, 3vw, 42px);
        line-height: 1.18;
        font-weight: 700;
    }

    .hp-consult p {
        max-width: 680px;
        margin: 14px 0 0;
        color: rgba(255,255,255,.72);
        font-size: 15px;
        line-height: 1.7;
    }

    @media (max-width: 1023px) {
        .hp-hero-inner,
        .hp-story {
            grid-template-columns: 1fr;
        }

        .hp-hero-inner {
            min-height: 0;
            padding: 46px 0;
        }

        .hp-product-grid {
            grid-template-columns: repeat(2, minmax(0, 1fr));
        }
    }

    @media (max-width: 700px) {
        .hp-container {
            width: min(100% - 28px, 1180px);
        }

        .hp-stats,
        .hp-trust-grid,
        .hp-product-grid,
        .hp-feature-list {
            grid-template-columns: 1fr;
        }

        .hp-hero-art {
            grid-template-columns: 1fr;
        }

        .hp-art-main,
        .hp-art-small,
        .hp-product-media,
        .hp-story-image {
            height: 240px;
        }

        .hp-section-head,
        .hp-consult-inner {
            display: block;
        }

        .hp-link,
        .hp-consult .hp-btn {
            margin-top: 18px;
        }
    }
</style>

<div class="hp">
    <section class="hp-hero">
        <div class="hp-container hp-hero-inner">
            <div>
                <p class="hp-eyebrow"><?= $text('home_eyebrow', 'Bộ sưu tập cưới 2026') ?></p>
                <h1><?= $text('home_title', 'Trang sức chế tác riêng cho khoảnh khắc đáng nhớ') ?></h1>
                <p class="hp-hero-copy">
                    <?= $text('home_intro', 'Aurelia tuyển chọn kim cương, vàng và đá quý đạt chuẩn kiểm định để tạo nên những thiết kế tinh tế, bền vững và giàu cảm xúc.') ?>
                </p>

                <div class="hp-actions">
                    <a href="<?= $e(URLROOT . '/client/Products') ?>" class="hp-btn hp-btn-primary"><?= $text('home_primary_cta', 'Khám phá bộ sưu tập') ?></a>
                    <a href="<?= $e(URLROOT . '/Contact') ?>" class="hp-btn hp-btn-secondary"><?= $text('home_secondary_cta', 'Liên hệ tư vấn') ?></a>
                </div>

                <div class="hp-stats">
                    <div class="hp-stat"><strong>120+</strong><span>mẫu trang sức chọn lọc</span></div>
                    <div class="hp-stat"><strong>48h</strong><span>tư vấn size và phối mẫu</span></div>
                    <div class="hp-stat"><strong>12 tháng</strong><span>bảo hành làm sạch định kỳ</span></div>
                </div>
            </div>

            <div class="hp-hero-art">
                <div class="hp-art-panel hp-art-main">
                    <img src="<?= $e($heroMain) ?>" alt="Nhẫn pha lê trắng">
                </div>
                <div class="hp-art-stack">
                    <div class="hp-art-panel hp-art-small">
                        <img src="<?= $e($heroTop) ?>" alt="Dây chuyền pha lê">
                    </div>
                    <div class="hp-art-panel hp-art-small">
                        <img src="<?= $e($heroBottom) ?>" alt="Bông tai pha lê">
                    </div>
                </div>
            </div>
        </div>
    </section>

    <section class="hp-trust">
        <div class="hp-container hp-trust-grid">
            <div>
                <h2>Kiểm định minh bạch</h2>
                <p>Thông tin chất liệu, đá và bảo hành rõ ràng trước khi đặt mua.</p>
            </div>
            <div>
                <h2>Tư vấn theo dịp</h2>
                <p>Chọn nhẫn, dây chuyền, bông tai theo phong cách và ngân sách.</p>
            </div>
            <div>
                <h2>Đóng gói chỉn chu</h2>
                <p>Mỗi món trang sức được trình bày rõ ảnh, chất liệu và mức giá để khách dễ cân nhắc.</p>
            </div>
        </div>
    </section>

    <section class="hp-section">
        <div class="hp-container">
            <div class="hp-section-head">
                <div>
                    <p class="hp-eyebrow">Bộ sưu tập</p>
                    <h2>Chọn nhanh theo phong cách</h2>
                    <p>Khám phá những nhóm trang sức được khách hàng yêu thích: bông tai, vòng cổ, nhẫn, vòng tay và đồng hồ.</p>
                </div>
                <a class="hp-link" href="<?= $e(URLROOT . '/client/Products') ?>">Xem tất cả sản phẩm</a>
            </div>

            <div class="hp-product-grid">
                <?php foreach ($categoryCards as $index => $category): ?>
                    <?php
                        $slug = $category->slug ?? '';
                        $meta = $categoryFallbacks[$slug] ?? null;
                        $label = $meta['label'] ?? ($category->name ?? 'Trang sức');
                        $copy = $meta['copy'] ?? 'Thiết kế chọn lọc cho phong cách riêng.';
                        $image = $mediaUrl('', $meta['image'] ?? $fallbackImages[$index % count($fallbackImages)]);
                    ?>
                    <a href="<?= $e(URLROOT . '/client/Products') ?>" class="hp-product">
                        <div class="hp-product-media">
                            <img src="<?= $e($image) ?>" alt="<?= $e($label) ?>">
                        </div>
                        <div class="hp-product-body">
                            <h3><?= $e($label) ?></h3>
                            <p><?= $e($copy) ?></p>
                            <?php if ((int)($category->product_count ?? 0) > 0): ?>
                                <p class="hp-product-kicker"><?= (int)$category->product_count ?> sản phẩm</p>
                            <?php endif; ?>
                        </div>
                    </a>
                <?php endforeach; ?>
            </div>
        </div>
    </section>

    <section class="hp-section hp-section-soft">
        <div class="hp-container">
            <div class="hp-section-head">
                <div>
                    <p class="hp-eyebrow">Sản phẩm nổi bật</p>
                    <h2>Những thiết kế đang được quan tâm</h2>
                    <p>Gợi ý các mẫu trang sức nổi bật để khách có thể xem nhanh kiểu dáng, chất liệu và mức giá trước khi cần tư vấn thêm.</p>
                </div>
            </div>

            <div class="hp-product-grid">
                <?php foreach (array_slice($featuredProducts, 0, 4) as $index => $product): ?>
                    <?php
                        $slug = $product->category_slug ?? '';
                        $fallback = $fallbackByCategory[$slug] ?? $fallbackImages[$index % count($fallbackImages)];
                        $productImage = $mediaUrl($product->image_url ?? '', $fallback);
                        $productName = $product->name ?? 'Aurelia Jewelry';
                        $categoryName = $product->category_name ?? 'Trang sức';
                        $description = $shortText($product->description ?? $product->material ?? '', 86);
                    ?>
                    <article class="hp-product">
                        <a href="<?= $e(URLROOT . '/client/Products') ?>" class="hp-product-media">
                            <img src="<?= $e($productImage) ?>" alt="<?= $e($productName) ?>">
                        </a>
                        <div class="hp-product-body">
                            <p class="hp-product-kicker"><?= $e($categoryName) ?></p>
                            <h3><?= $e($productName) ?></h3>
                            <?php if ($description !== ''): ?>
                                <p><?= $e($description) ?></p>
                            <?php endif; ?>
                            <div class="hp-product-footer">
                                <span class="hp-price"><?= $e($money($product->price ?? 0)) ?></span>
                                <a href="<?= $e(URLROOT . '/Contact') ?>" class="hp-mini-btn">Tư vấn</a>
                            </div>
                        </div>
                    </article>
                <?php endforeach; ?>
            </div>
        </div>
    </section>

    <section class="hp-section">
        <div class="hp-container hp-story">
            <div class="hp-story-gallery">
                <div class="hp-story-image">
                    <img src="<?= $e($mediaUrl('', 'assets/products/showcase/ring-mesmera.jpg')) ?>" alt="Nhẫn pha lê trắng">
                </div>
                <div class="hp-story-image">
                    <img src="<?= $e($mediaUrl('', 'assets/products/showcase/watch-millenia.jpg')) ?>" alt="Đồng hồ trang sức">
                </div>
            </div>
            <div>
                <p class="hp-eyebrow">Trải nghiệm mua sắm</p>
                <h2>Từ cảm hứng đến món trang sức dành riêng cho bạn</h2>
                <p>Aurelia tập trung vào trải nghiệm chọn mẫu rõ ràng: hình ảnh sắc nét, thông tin chất liệu dễ hiểu và tư vấn theo phong cách cá nhân.</p>

                <div class="hp-feature-list">
                    <div class="hp-feature">
                        <strong>Chất liệu dễ so sánh</strong>
                        <span>Màu sắc, đá, lớp mạ và mức giá được trình bày rõ ràng.</span>
                    </div>
                    <div class="hp-feature">
                        <strong>Tư vấn đúng nhu cầu</strong>
                        <span>Đội ngũ hỗ trợ chọn mẫu theo dịp, ngân sách và thời gian nhận hàng.</span>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <section class="hp-consult">
        <div class="hp-container hp-consult-inner">
            <div>
                <p class="hp-eyebrow"><?= $text('site_brand_name', 'AURELIA') ?> <?= $text('site_tagline', 'Fine Jewelry') ?></p>
                <h2>Cần chọn mẫu cho một dịp cụ thể?</h2>
                <p>Gửi yêu cầu tư vấn, đội ngũ Aurelia sẽ gợi ý sản phẩm theo ngân sách, phong cách, chất liệu và thời gian bạn cần nhận hàng.</p>
            </div>
            <a href="<?= $e(URLROOT . '/Contact') ?>" class="hp-btn hp-btn-primary">Đặt lịch tư vấn</a>
        </div>
    </section>
</div>
