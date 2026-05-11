<?php
$companyImages = $companyImages ?? [];
$testimonials = $testimonials ?? [];
$branches = $branches ?? [];
$jobs = $jobs ?? [];
$stats = $stats ?? [];
$values = $values ?? [];

$e = static fn($value) => htmlspecialchars((string)$value, ENT_QUOTES, 'UTF-8');

$icon = static function (string $name, string $class = 'w-6 h-6'): string {
    $attrs = 'xmlns="http://www.w3.org/2000/svg" class="' . $class . '" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true"';
    $paths = [
        'award' => '<circle cx="12" cy="8" r="6"></circle><path d="M15.477 12.89 17 22l-5-3-5 3 1.523-9.11"></path>',
        'heart' => '<path d="M19 14c1.49-1.46 3-3.21 3-5.5A5.5 5.5 0 0 0 16.5 3c-1.76 0-3 .5-4.5 2-1.5-1.5-2.74-2-4.5-2A5.5 5.5 0 0 0 2 8.5c0 2.29 1.51 4.04 3 5.5l7 7Z"></path>',
        'users' => '<path d="M16 21v-2a4 4 0 0 0-4-4H6a4 4 0 0 0-4 4v2"></path><circle cx="9" cy="7" r="4"></circle><path d="M22 21v-2a4 4 0 0 0-3-3.87"></path><path d="M16 3.13a4 4 0 0 1 0 7.75"></path>',
        'sparkles' => '<path d="M9.937 15.5A2 2 0 0 0 8.5 14.063l-6.135-1.582a.5.5 0 0 1 0-.962L8.5 9.936A2 2 0 0 0 9.937 8.5l1.582-6.135a.5.5 0 0 1 .962 0L14.064 8.5A2 2 0 0 0 15.5 9.937l6.135 1.582a.5.5 0 0 1 0 .962L15.5 14.064a2 2 0 0 0-1.437 1.436l-1.582 6.135a.5.5 0 0 1-.962 0Z"></path><path d="M20 3v4"></path><path d="M22 5h-4"></path><path d="M4 17v2"></path><path d="M5 18H3"></path>',
        'map-pin' => '<path d="M20 10c0 4.993-5.539 10.193-7.399 11.799a1 1 0 0 1-1.202 0C9.539 20.193 4 14.993 4 10a8 8 0 1 1 16 0"></path><circle cx="12" cy="10" r="3"></circle>',
        'phone' => '<path d="M22 16.92v3a2 2 0 0 1-2.18 2 19.8 19.8 0 0 1-8.63-3.07 19.5 19.5 0 0 1-6-6A19.8 19.8 0 0 1 2.12 4.18 2 2 0 0 1 4.11 2h3a2 2 0 0 1 2 1.72c.12.89.35 1.76.68 2.59a2 2 0 0 1-.45 2.11L8.09 9.91a16 16 0 0 0 6 6l1.49-1.25a2 2 0 0 1 2.11-.45c.83.33 1.7.56 2.59.68A2 2 0 0 1 22 16.92z"></path>',
        'briefcase' => '<path d="M16 20V4a2 2 0 0 0-2-2h-4a2 2 0 0 0-2 2v16"></path><rect width="20" height="14" x="2" y="6" rx="2"></rect>',
        'quote' => '<path d="M16 3a2 2 0 0 0-2 2v6a2 2 0 0 0 2 2 1 1 0 0 1 1 1v1a2 2 0 0 1-2 2 1 1 0 0 0 0 2 4 4 0 0 0 4-4V5a2 2 0 0 0-2-2z"></path><path d="M5 3a2 2 0 0 0-2 2v6a2 2 0 0 0 2 2 1 1 0 0 1 1 1v1a2 2 0 0 1-2 2 1 1 0 0 0 0 2 4 4 0 0 0 4-4V5a2 2 0 0 0-2-2z"></path>',
        'star' => '<path d="M11.525 2.295a.53.53 0 0 1 .95 0l2.31 4.679a2.12 2.12 0 0 0 1.595 1.16l5.166.751a.53.53 0 0 1 .294.904l-3.736 3.644a2.12 2.12 0 0 0-.611 1.878l.882 5.14a.53.53 0 0 1-.77.56l-4.618-2.428a2.12 2.12 0 0 0-1.974 0L6.396 21.01a.53.53 0 0 1-.77-.56l.881-5.139a2.12 2.12 0 0 0-.611-1.879L2.16 9.79a.53.53 0 0 1 .294-.904l5.165-.752a2.12 2.12 0 0 0 1.596-1.16z"></path>',
        'chevron-left' => '<path d="m15 18-6-6 6-6"></path>',
        'chevron-right' => '<path d="m9 18 6-6-6-6"></path>',
    ];

    return '<svg ' . $attrs . '>' . ($paths[$name] ?? '') . '</svg>';
};
?>

<style>
    .about-section { padding: 4rem 0; }
    @media (min-width: 768px) { .about-section { padding: 6rem 0; } }
    .gold-gradient { background: linear-gradient(135deg, #f6e3a1 0%, #d9b461 45%, #b5852d 100%); }
    .about-hero-brown { background: #211c16; }
    .about-soft-gold-section { background: #fffdf7; }
    .about-section-heading {
        display: flex;
        flex-direction: column;
        align-items: center;
        justify-content: center;
        text-align: center;
        margin-bottom: 2rem;
        margin-left: auto;
        margin-right: auto;
    }
    .about-section-heading p,
    .about-section-heading h2 {
        text-align: center;
        width: 100%;
    }

    .company-marquee {
        margin-left: calc(50% - 50vw);
        margin-right: calc(50% - 50vw);
        overflow: hidden;
        padding: 1.5rem 0;
    }
    .company-marquee-track {
        display: flex;
        width: max-content;
        animation: company-marquee-scroll 42s linear infinite;
        will-change: transform;
    }
    .company-marquee-group {
        display: flex;
        align-items: center;
        gap: 1.5rem;
        padding-right: 1.5rem;
    }
    .company-marquee-card {
        position: relative;
        flex: 0 0 auto;
        width: clamp(240px, 30vw, 460px);
        height: clamp(150px, 17vw, 260px);
        overflow: hidden;
        border: 2px solid #211c16;
        border-radius: 0.5rem;
        background: #f8fafc;
    }
    .company-marquee-card:nth-child(3n) {
        width: clamp(280px, 34vw, 520px);
    }
    .company-marquee-card:nth-child(4n) {
        width: clamp(220px, 24vw, 360px);
        height: clamp(190px, 24vw, 340px);
    }
    .company-marquee-card img {
        width: 100%;
        height: 100%;
        object-fit: cover;
    }
    .company-marquee-caption {
        position: absolute;
        inset: auto 0 0 0;
        padding: 1rem;
        color: #fff7ed;
        background: linear-gradient(to top, rgba(15, 23, 42, 0.8), rgba(15, 23, 42, 0));
    }
    @keyframes company-marquee-scroll {
        from { transform: translateX(0); }
        to { transform: translateX(-50%); }
    }

    .about-slider-shell {
        position: relative;
    }
    .about-slider-viewport { overflow: hidden; }
    .about-slider-track {
        display: flex;
        gap: 1.5rem;
        transition: transform 360ms ease;
        will-change: transform;
    }
    .about-slider-slide {
        flex: 0 0 100%;
        min-width: 0;
    }
    .about-slider-dot {
        width: 0.625rem;
        height: 0.625rem;
        border-radius: 9999px;
        background: #d6cfc6;
        transition: width 180ms ease, background-color 180ms ease;
    }
    .about-slider-dot.is-active {
        width: 1.75rem;
        background: #d9b461;
    }
    .about-slider-nav {
        position: absolute;
        top: 50%;
        z-index: 5;
        display: flex;
        width: 2.75rem;
        height: 2.75rem;
        align-items: center;
        justify-content: center;
        border: 1px solid #e2e8f0;
        border-radius: 9999px;
        background: #ffffff;
        color: #0f172a;
        box-shadow: 0 0.75rem 1.75rem rgba(15, 23, 42, 0.14);
        transform: translateY(-50%);
        transition: background-color 180ms ease, border-color 180ms ease, color 180ms ease;
    }
    .about-slider-nav:hover {
        border-color: #d9b461;
        background: #fffaf0;
        color: #b5852d;
    }
    .about-slider-nav-prev {
        left: -1.375rem;
    }
    .about-slider-nav-next {
        right: -1.375rem;
    }
    .testimonial-quote-icon,
    .testimonial-quote-icon svg {
        width: 1.25rem;
        height: 1.25rem;
        max-width: 1.25rem;
        max-height: 1.25rem;
        flex: 0 0 auto;
    }
    .job-briefcase-icon,
    .job-briefcase-icon svg {
        width: 1.5rem;
        height: 1.5rem;
        max-width: 1.5rem;
        max-height: 1.5rem;
        flex: 0 0 auto;
    }
    .about-branches-grid {
        display: grid;
        grid-template-columns: minmax(0, 1fr);
        gap: 1.5rem;
    }
    .about-branch-card {
        background: #fffdf7;
        border-color: #eadfcb;
    }
    .about-stats-section {
        background: #fffdf7;
    }
    .about-stat-card {
        min-height: 11rem;
        display: flex;
        flex-direction: column;
        justify-content: space-between;
        border: 1px solid #e8e1d7;
        border-radius: 1rem;
        background: #ffffff;
        padding: 1.5rem;
        text-align: left;
        box-shadow: 0 0.5rem 1.5rem rgba(33, 28, 22, 0.08);
    }
    .about-stat-label {
        color: #6f6a62;
        font-size: 1.05rem;
        font-weight: 600;
        line-height: 1.35;
    }
    .about-stat-number {
        color: #24302e;
        font-size: clamp(2.25rem, 3vw, 3rem);
        font-weight: 800;
        line-height: 1;
        letter-spacing: 0;
    }
    .about-apply-modal {
        position: fixed;
        inset: 0;
        z-index: 1000;
        display: flex;
        align-items: center;
        justify-content: center;
        padding: 1.25rem;
        opacity: 0;
        pointer-events: none;
        transition: opacity 180ms ease;
    }
    .about-apply-modal.is-open {
        opacity: 1;
        pointer-events: auto;
    }
    .about-apply-backdrop {
        position: absolute;
        inset: 0;
        background: rgba(15, 23, 42, 0.62);
    }
    .about-apply-panel {
        position: relative;
        width: min(100%, 46rem);
        max-height: min(92vh, 52rem);
        overflow-y: auto;
        border: 1px solid #eadfcb;
        border-radius: 0.75rem;
        background: #fffdf7;
        box-shadow: 0 1.5rem 4rem rgba(15, 23, 42, 0.24);
        transform: scale(0.96) translateY(0.75rem);
        transition: transform 180ms ease;
    }
    .about-apply-modal.is-open .about-apply-panel {
        transform: scale(1) translateY(0);
    }
    .about-apply-header,
    .about-apply-footer {
        padding: 1.25rem 1.5rem;
    }
    .about-apply-header {
        border-bottom: 1px solid #eadfcb;
    }
    .about-apply-body {
        display: grid;
        grid-template-columns: minmax(0, 1fr);
        gap: 1rem;
        padding: 1.5rem;
    }
    .about-apply-field label {
        display: block;
        margin-bottom: 0.4rem;
        color: #334155;
        font-size: 0.875rem;
        font-weight: 700;
    }
    .about-apply-field input,
    .about-apply-field textarea {
        width: 100%;
        border: 1px solid #d8cfbf;
        border-radius: 0.45rem;
        background: #ffffff;
        padding: 0.7rem 0.85rem;
        color: #0f172a;
        outline: none;
        transition: border-color 160ms ease, box-shadow 160ms ease;
    }
    .about-apply-field input:focus,
    .about-apply-field textarea:focus {
        border-color: #d9b461;
        box-shadow: 0 0 0 3px rgba(217, 180, 97, 0.22);
    }
    .about-apply-field input[readonly] {
        background: #f8f2e6;
    }
    .about-apply-field textarea {
        min-height: 6rem;
        resize: vertical;
    }
    .about-apply-error {
        min-height: 1.05rem;
        margin-top: 0.32rem;
        color: #dc2626;
        font-size: 0.8rem;
    }
    .about-apply-field.is-invalid input,
    .about-apply-field.is-invalid textarea {
        border-color: #dc2626;
    }
    .about-apply-status {
        min-height: 1.25rem;
        padding: 0 1.5rem;
        color: #166534;
        font-size: 0.9rem;
    }
    .about-apply-status.is-error {
        color: #dc2626;
    }
    .about-apply-footer {
        display: flex;
        flex-wrap: wrap;
        justify-content: flex-end;
        gap: 0.75rem;
        border-top: 1px solid #eadfcb;
    }
    .about-apply-primary,
    .about-apply-secondary {
        border-radius: 0.45rem;
        padding: 0.7rem 1.15rem;
        font-weight: 700;
        transition: background-color 160ms ease, border-color 160ms ease, color 160ms ease;
    }
    .about-apply-primary {
        border: 1px solid #b5852d;
        background: #b5852d;
        color: #ffffff;
    }
    .about-apply-primary:hover {
        background: #95691f;
        border-color: #95691f;
    }
    .about-apply-primary:disabled {
        opacity: 0.65;
        cursor: not-allowed;
    }
    .about-apply-primary {
        display: inline-flex;
        align-items: center;
        justify-content: center;
        gap: 0.5rem;
    }
    .about-submit-spinner {
        display: none;
        width: 1rem;
        height: 1rem;
        border: 2px solid rgba(255, 255, 255, 0.42);
        border-top-color: #ffffff;
        border-radius: 9999px;
        animation: about-spin 760ms linear infinite;
    }
    .about-apply-primary.is-submitting .about-submit-spinner {
        display: inline-block;
    }
    @keyframes about-spin {
        to { transform: rotate(360deg); }
    }
    .about-apply-secondary {
        border: 1px solid #cbd5e1;
        background: #ffffff;
        color: #334155;
    }
    .about-apply-secondary:hover {
        border-color: #94a3b8;
        background: #f8fafc;
    }
    .about-apply-toast {
        position: fixed;
        top: 1.25rem;
        right: 1.25rem;
        z-index: 1200;
        max-width: min(90vw, 24rem);
        border: 1px solid #bbf7d0;
        border-radius: 0.65rem;
        background: #f0fdf4;
        color: #166534;
        padding: 0.85rem 1rem;
        box-shadow: 0 1rem 2.5rem rgba(15, 23, 42, 0.16);
        opacity: 0;
        pointer-events: none;
        transform: translateY(-0.75rem) scale(0.98);
        transition: opacity 180ms ease, transform 180ms ease;
    }
    .about-apply-toast.is-visible {
        opacity: 1;
        transform: translateY(0) scale(1);
    }
    .about-apply-toast.is-error {
        border-color: #fecaca;
        background: #fef2f2;
        color: #b91c1c;
    }

    @media (prefers-reduced-motion: reduce) {
        .about-slider-track { transition: none; }
        .company-marquee-track { animation-duration: 120s; }
    }
    @media (min-width: 640px) {
        .about-branches-grid {
            grid-template-columns: repeat(2, minmax(0, 1fr));
        }
    }
    @media (min-width: 1024px) {
        .about-branches-grid {
            grid-template-columns: repeat(3, minmax(0, 1fr));
        }
    }
    @media (min-width: 768px) {
        .about-apply-body {
            grid-template-columns: repeat(2, minmax(0, 1fr));
        }
        .about-apply-field-wide {
            grid-column: 1 / -1;
        }
    }
    @media (max-width: 767px) {
        .company-marquee {
            padding: 0.5rem 0;
        }
        .company-marquee-group {
            gap: 1rem;
            padding-right: 1rem;
        }
        .company-marquee-card {
            width: 260px;
            height: 170px;
        }
        .company-marquee-card:nth-child(4n) {
            width: 220px;
            height: 240px;
        }
        .about-slider-nav {
            width: 2.35rem;
            height: 2.35rem;
        }
        .about-slider-nav-prev {
            left: -0.5rem;
        }
        .about-slider-nav-next {
            right: -0.5rem;
        }
    }
</style>

<section class="relative about-hero-brown text-amber-50 about-section">
    <div class="container mx-auto px-4 text-center max-w-3xl">
        <p class="text-amber-400 text-sm uppercase tracking-[0.3em] mb-3">Câu chuyện của chúng tôi</p>
        <h1 class="text-4xl md:text-5xl font-serif font-bold mb-6">
            Nghệ Thuật Trang Sức <span class="gold-text">Đích Thực</span>
        </h1>
        <p class="text-amber-50/60 text-lg leading-relaxed">
            AURELIA được thành lập với sứ mệnh mang đến những tác phẩm trang sức tinh xảo, kết hợp giữa kỹ thuật chế tác truyền thống và thiết kế hiện đại.
        </p>
    </div>
</section>

<section class="about-section">
    <div class="container mx-auto px-4">
        <div class="about-section-heading mb-10">
            <p class="text-amber-600 text-sm uppercase tracking-[0.3em] mb-3">Hình ảnh công ty</p>
            <h2 class="text-3xl font-serif font-bold">Không Gian AURELIA</h2>
        </div>

        <div class="company-marquee" aria-label="Hình ảnh công ty AURELIA">
            <div class="company-marquee-track">
                <?php for ($loop = 0; $loop < 2; $loop++): ?>
                    <div class="company-marquee-group" aria-hidden="<?= $loop === 1 ? 'true' : 'false' ?>">
                        <?php foreach ($companyImages as $img): ?>
                            <article class="company-marquee-card">
                                <img src="<?= $e($img->src ?? '') ?>" alt="<?= $loop === 0 ? $e($img->title ?? '') : '' ?>">
                                <div class="company-marquee-caption">
                                    <h3 class="font-serif font-bold text-lg"><?= $e($img->title ?? '') ?></h3>
                                    <p class="text-sm text-amber-50/80"><?= $e($img->desc ?? '') ?></p>
                                </div>
                            </article>
                        <?php endforeach; ?>
                    </div>
                <?php endfor; ?>
            </div>
        </div>
    </div>
</section>

<section class="about-section about-soft-gold-section">
    <div class="container mx-auto px-4">
        <div class="grid md:grid-cols-2 gap-12 items-center">
            <div>
                <p class="text-amber-600 text-sm uppercase tracking-[0.3em] mb-3">Ý nghĩa thương hiệu</p>
                <h2 class="text-3xl md:text-4xl font-serif font-bold mb-6">
                    <span class="gold-text">AURELIA</span> - Ánh Sáng Của Vàng
                </h2>
                <div class="space-y-4 text-slate-600 leading-relaxed">
                    <p>Cái tên <strong class="text-slate-950">AURELIA</strong> bắt nguồn từ tiếng Latin <em>"Aurum"</em> - nghĩa là <strong class="text-slate-950">vàng</strong>, kim loại quý biểu trưng cho sự vĩnh cửu, thuần khiết và quyền quý.</p>
                    <p>Hậu tố <em>"-lia"</em> mang sắc thái nữ tính, mềm mại, gợi liên tưởng tới ánh sáng dịu dàng phát ra từ một viên trang sức được chế tác tinh tế.</p>
                    <p>Khi gọi tên <strong class="text-slate-950">AURELIA</strong>, chúng tôi nhắc nhớ chính mình về sứ mệnh: <em>thắp lên ánh sáng vĩnh cửu trong từng khoảnh khắc đáng nhớ của khách hàng.</em></p>
                </div>
            </div>
            <div class="relative">
                <div class="absolute -inset-4 gold-gradient opacity-20 blur-2xl rounded-full"></div>
                <img src="<?= URLROOT ?>/assets/images/about/store-1.jpg" alt="Ý nghĩa thương hiệu AURELIA" class="relative rounded-lg w-full aspect-square object-cover">
            </div>
        </div>
    </div>
</section>

<section class="about-section">
    <div class="container mx-auto px-4">
        <div class="about-section-heading mb-16 md:mb-20">
            <p class="text-amber-600 text-sm uppercase tracking-[0.3em] mb-3">Giá trị</p>
            <h2 class="text-3xl font-serif font-bold">Giá Trị Cốt Lõi</h2>
        </div>
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6">
            <?php foreach ($values as $item): ?>
                <article class="text-center rounded-lg border border-slate-200 bg-white shadow-sm">
                    <div class="p-6">
                        <div class="mx-auto mb-4 text-amber-600 w-10 h-10"><?= $icon($item->icon ?? 'sparkles', 'w-10 h-10') ?></div>
                        <h3 class="font-serif font-semibold text-lg mb-2"><?= $e($item->title ?? '') ?></h3>
                        <p class="text-sm text-slate-600"><?= $e($item->desc ?? '') ?></p>
                    </div>
                </article>
            <?php endforeach; ?>
        </div>
    </div>
</section>

<section class="about-section about-soft-gold-section">
    <div class="container mx-auto px-4">
        <div class="about-section-heading mb-10">
            <p class="text-amber-600 text-sm uppercase tracking-[0.3em] mb-3">Khách hàng</p>
            <h2 class="text-3xl font-serif font-bold">Lời Chia Sẻ Từ Khách Hàng</h2>
        </div>
        <div class="relative max-w-6xl mx-auto" data-about-slider data-mobile="1" data-tablet="2" data-desktop="3">
            <div class="about-slider-shell">
                <div class="about-slider-viewport">
                <div class="about-slider-track" data-slider-track>
                    <?php foreach ($testimonials as $t): ?>
                        <article class="about-slider-slide rounded-lg border border-slate-200 bg-white shadow-sm">
                            <div class="p-6 flex flex-col h-full">
                                <div class="testimonial-quote-icon text-amber-600 mb-4"><?= $icon('quote', 'w-5 h-5') ?></div>
                                <p class="text-slate-600 italic mb-6 flex-1">"<?= $e($t->content ?? '') ?>"</p>
                                <div class="flex gap-1 mb-4 text-amber-500">
                                    <?php for ($i = 0, $rating = (int)($t->rating ?? 5); $i < $rating; $i++): ?>
                                        <?= str_replace('fill="none"', 'fill="currentColor"', $icon('star', 'w-4 h-4')) ?>
                                    <?php endfor; ?>
                                </div>
                                <div class="flex items-center gap-3">
                                    <img src="<?= $e($t->avatar ?? '') ?>" alt="<?= $e($t->name ?? '') ?>" class="h-6 rounded-full object-cover">
                                    <div>
                                        <p class="font-serif font-semibold"><?= $e($t->name ?? '') ?></p>
                                        <p class="text-xs text-slate-500"><?= $e($t->role ?? '') ?></p>
                                    </div>
                                </div>
                            </div>
                        </article>
                    <?php endforeach; ?>
                </div>
            </div>
            <button type="button" class="about-slider-nav about-slider-nav-prev" data-slider-btn="prev" aria-label="Đánh giá trước"><?= $icon('chevron-left', 'w-5 h-5') ?></button>
            <button type="button" class="about-slider-nav about-slider-nav-next" data-slider-btn="next" aria-label="Đánh giá tiếp theo"><?= $icon('chevron-right', 'w-5 h-5') ?></button>
            </div>
            <div class="mt-5 flex justify-center gap-2" data-slider-dots></div>
        </div>
    </div>
</section>

<section class="about-section">
    <div class="container mx-auto px-4">
        <div class="about-section-heading mb-10">
            <p class="text-amber-600 text-sm uppercase tracking-[0.3em] mb-3">Hệ thống cửa hàng</p>
            <h2 class="text-3xl font-serif font-bold">Chi Nhánh Trên Toàn Quốc</h2>
        </div>
        <div class="about-branches-grid">
            <?php foreach ($branches as $branch): ?>
                <article class="about-branch-card rounded-lg border shadow-sm hover:shadow-lg transition-shadow">
                    <div class="p-6">
                        <div class="flex items-start gap-2 mb-3">
                            <div class="text-amber-600 mt-1 shrink-0"><?= $icon('map-pin', 'w-5 h-5') ?></div>
                            <div>
                                <h3 class="font-serif font-semibold text-lg"><?= $e($branch->city ?? '') ?></h3>
                                <p class="text-sm text-slate-600"><?= $e($branch->address ?? '') ?></p>
                            </div>
                        </div>
                        <div class="flex items-center gap-2 text-sm text-slate-600 pl-8">
                            <span class="text-amber-600"><?= $icon('phone', 'w-4 h-4') ?></span>
                            <?= $e($branch->phone ?? '') ?>
                        </div>
                    </div>
                </article>
            <?php endforeach; ?>
        </div>
    </div>
</section>

<section class="about-section about-soft-gold-section">
    <div class="container mx-auto px-4">
        <div class="about-section-heading mb-10">
            <p class="text-amber-600 text-sm uppercase tracking-[0.3em] mb-3">Tuyển dụng</p>
            <h2 class="text-3xl font-serif font-bold mb-3">Cơ Hội Nghề Nghiệp</h2>
            <p class="text-slate-600 max-w-xl mx-auto">Gia nhập AURELIA để cùng kiến tạo những tác phẩm trang sức để đời.</p>
        </div>
        <div class="relative max-w-6xl mx-auto" data-about-slider data-mobile="1" data-tablet="2" data-desktop="3">
            <div class="about-slider-shell">
                <div class="about-slider-viewport">
                <div class="about-slider-track" data-slider-track>
                    <?php foreach ($jobs as $job): ?>
                        <article class="about-slider-slide rounded-lg border border-slate-200 bg-white shadow-sm hover:border-amber-500 transition-colors">
                            <div class="p-6 flex flex-col h-full">
                                <div class="job-briefcase-icon text-amber-600 mb-4"><?= $icon('briefcase', 'w-6 h-6') ?></div>
                                <h3 class="font-serif font-semibold text-lg mb-3"><?= $e($job->title ?? '') ?></h3>
                                <div class="flex flex-wrap gap-2 mb-4">
                                    <span class="rounded-md bg-slate-100 px-2.5 py-1 text-xs font-medium text-slate-700"><?= $e($job->location ?? '') ?></span>
                                    <span class="rounded-md bg-slate-100 px-2.5 py-1 text-xs font-medium text-slate-700"><?= $e($job->type ?? '') ?></span>
                                </div>
                                <p class="text-sm text-slate-600 mb-6 flex-1"><?= $e($job->level ?? '') ?></p>
                                <button
                                    type="button"
                                    class="w-full rounded-md border border-slate-300 px-4 py-2 text-center text-sm font-semibold text-slate-800 hover:border-amber-500 hover:text-amber-700 transition-colors"
                                    data-apply-job
                                    data-job-title="<?= $e($job->title ?? '') ?>"
                                    data-job-location="<?= $e($job->location ?? '') ?>"
                                >Ứng tuyển ngay</button>
                            </div>
                        </article>
                    <?php endforeach; ?>
                </div>
            </div>
            <button type="button" class="about-slider-nav about-slider-nav-prev" data-slider-btn="prev" aria-label="Việc làm trước"><?= $icon('chevron-left', 'w-5 h-5') ?></button>
            <button type="button" class="about-slider-nav about-slider-nav-next" data-slider-btn="next" aria-label="Việc làm tiếp theo"><?= $icon('chevron-right', 'w-5 h-5') ?></button>
            </div>
            <div class="mt-5 flex justify-center gap-2" data-slider-dots></div>
        </div>
    </div>
</section>

<div class="about-apply-modal" data-apply-modal aria-hidden="true">
    <div class="about-apply-backdrop" data-apply-close></div>
    <div class="about-apply-panel" role="dialog" aria-modal="true" aria-labelledby="apply-modal-title">
        <div class="about-apply-header">
            <p class="text-amber-600 text-sm uppercase tracking-[0.24em] mb-2">Ứng tuyển</p>
            <h3 id="apply-modal-title" class="text-2xl font-serif font-bold text-slate-950">Gửi hồ sơ ứng tuyển</h3>
        </div>

        <form id="aboutApplyForm" action="<?= URLROOT ?>/About/apply" method="POST" enctype="multipart/form-data" novalidate>
            <div class="about-apply-body">
                <div class="about-apply-field" data-field="fullname">
                    <label for="apply-fullname">Họ và tên</label>
                    <input type="text" id="apply-fullname" name="fullname" autocomplete="name">
                    <div class="about-apply-error" data-error-for="fullname"></div>
                </div>

                <div class="about-apply-field" data-field="email">
                    <label for="apply-email">Email</label>
                    <input type="email" id="apply-email" name="email" autocomplete="email">
                    <div class="about-apply-error" data-error-for="email"></div>
                </div>

                <div class="about-apply-field" data-field="phone">
                    <label for="apply-phone">Số điện thoại</label>
                    <input type="tel" id="apply-phone" name="phone" inputmode="numeric" autocomplete="tel" maxlength="10">
                    <div class="about-apply-error" data-error-for="phone"></div>
                </div>

                <div class="about-apply-field" data-field="location">
                    <label for="apply-location">Chi nhánh / địa điểm làm việc</label>
                    <input type="text" id="apply-location" name="location" readonly>
                    <div class="about-apply-error" data-error-for="location"></div>
                </div>

                <div class="about-apply-field about-apply-field-wide" data-field="position">
                    <label for="apply-position">Vị trí ứng tuyển</label>
                    <input type="text" id="apply-position" name="position" readonly>
                    <div class="about-apply-error" data-error-for="position"></div>
                </div>

                <div class="about-apply-field about-apply-field-wide" data-field="cv_file">
                    <label for="apply-cv">Upload CV</label>
                    <input type="file" id="apply-cv" name="cv_file" accept=".pdf,.doc,.docx,application/pdf,application/msword,application/vnd.openxmlformats-officedocument.wordprocessingml.document">
                    <div class="about-apply-error" data-error-for="cv_file"></div>
                </div>

                <div class="about-apply-field about-apply-field-wide" data-field="cover_letter">
                    <label for="apply-cover-letter">Thư giới thiệu ngắn</label>
                    <textarea id="apply-cover-letter" name="cover_letter" rows="4"></textarea>
                    <div class="about-apply-error" data-error-for="cover_letter"></div>
                </div>
            </div>

            <div class="about-apply-status" data-apply-status></div>

            <div class="about-apply-footer">
                <button type="button" class="about-apply-secondary" data-apply-close>Đóng</button>
                <button type="submit" class="about-apply-primary" data-apply-submit>
                    <span class="about-submit-spinner" aria-hidden="true"></span>
                    <span data-apply-submit-label>Gửi hồ sơ</span>
                </button>
            </div>
        </form>
    </div>
</div>

<div class="about-apply-toast" data-apply-toast role="status" aria-live="polite"></div>

<section class="about-section about-stats-section">
    <div class="container mx-auto px-4">
        <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-6">
            <?php foreach ($stats as $stat): ?>
                <article class="about-stat-card">
                    <p class="about-stat-label"><?= $e($stat->label ?? '') ?></p>
                    <div class="about-stat-number"><?= $e($stat->number ?? '') ?></div>
                </article>
            <?php endforeach; ?>
        </div>
    </div>
</section>

<script>
    (function () {
        const getVisibleCount = function (slider) {
            if (window.matchMedia('(min-width: 1024px)').matches) {
                return parseInt(slider.dataset.desktop || '1', 10);
            }
            if (window.matchMedia('(min-width: 768px)').matches) {
                return parseInt(slider.dataset.tablet || '1', 10);
            }
            return parseInt(slider.dataset.mobile || '1', 10);
        };

        document.querySelectorAll('[data-about-slider]').forEach(function (slider) {
            const track = slider.querySelector('[data-slider-track]');
            const slides = Array.from(slider.querySelectorAll('.about-slider-slide'));
            const dots = slider.querySelector('[data-slider-dots]');
            if (!track || slides.length === 0) {
                return;
            }

            let index = 0;
            let maxIndex = 0;

            const renderDots = function () {
                if (!dots) {
                    return;
                }

                dots.innerHTML = '';
                for (let i = 0; i <= maxIndex; i++) {
                    const dot = document.createElement('button');
                    dot.type = 'button';
                    dot.className = 'about-slider-dot' + (i === index ? ' is-active' : '');
                    dot.setAttribute('aria-label', 'Chuyển đến slide ' + (i + 1));
                    dot.addEventListener('click', function () {
                        index = i;
                        update();
                    });
                    dots.appendChild(dot);
                }
            };

            const update = function () {
                const styles = window.getComputedStyle(track);
                const gap = parseFloat(styles.columnGap || styles.gap || '0') || 0;
                const visible = Math.max(1, Math.min(getVisibleCount(slider), slides.length));

                maxIndex = Math.max(0, slides.length - visible);
                index = Math.max(0, Math.min(index, maxIndex));

                const slideWidth = (track.clientWidth - gap * (visible - 1)) / visible;
                slides.forEach(function (slide) {
                    slide.style.flexBasis = slideWidth + 'px';
                });

                track.style.transform = 'translateX(-' + (index * (slideWidth + gap)) + 'px)';

                if (!dots || dots.children.length !== maxIndex + 1) {
                    renderDots();
                }

                if (dots) {
                    Array.from(dots.children).forEach(function (dot, dotIndex) {
                        dot.classList.toggle('is-active', dotIndex === index);
                    });
                }
            };

            slider.querySelectorAll('[data-slider-btn]').forEach(function (button) {
                button.addEventListener('click', function () {
                    const direction = button.getAttribute('data-slider-btn') === 'prev' ? -1 : 1;
                    index += direction;
                    if (index < 0) {
                        index = maxIndex;
                    } else if (index > maxIndex) {
                        index = 0;
                    }
                    update();
                });
            });

            update();
            window.addEventListener('resize', update);
        });

        const modal = document.querySelector('[data-apply-modal]');
        const form = document.getElementById('aboutApplyForm');
        if (!modal || !form) {
            return;
        }

        const status = modal.querySelector('[data-apply-status]');
        const submitButton = modal.querySelector('[data-apply-submit]');
        const toast = document.querySelector('[data-apply-toast]');
        let toastTimer = null;
        const fields = {
            fullname: form.elements.fullname,
            email: form.elements.email,
            phone: form.elements.phone,
            position: form.elements.position,
            location: form.elements.location,
            cv_file: form.elements.cv_file,
            cover_letter: form.elements.cover_letter,
        };

        const allowedCvExtensions = ['pdf', 'doc', 'docx'];

        const setStatus = function (message, isError) {
            if (!status) {
                return;
            }
            status.textContent = message || '';
            status.classList.toggle('is-error', Boolean(isError));
        };

        const showToast = function (message, isError) {
            if (!toast) {
                return;
            }

            window.clearTimeout(toastTimer);
            toast.textContent = message || '';
            toast.classList.toggle('is-error', Boolean(isError));
            toast.classList.add('is-visible');
            toastTimer = window.setTimeout(function () {
                toast.classList.remove('is-visible');
            }, 3200);
        };

        const setSubmitting = function (isSubmitting) {
            submitButton.disabled = isSubmitting;
            submitButton.classList.toggle('is-submitting', isSubmitting);
            submitButton.innerHTML = '<span class="about-submit-spinner" aria-hidden="true"></span><span data-apply-submit-label>' + (isSubmitting ? 'Đang gửi...' : 'Gửi hồ sơ') + '</span>';
        };

        const setFieldError = function (fieldName, message) {
            const wrapper = form.querySelector('[data-field="' + fieldName + '"]');
            const errorNode = form.querySelector('[data-error-for="' + fieldName + '"]');
            if (wrapper) {
                wrapper.classList.toggle('is-invalid', Boolean(message));
            }
            if (errorNode) {
                errorNode.textContent = message || '';
            }
        };

        const validateField = function (fieldName) {
            let error = '';

            if (fieldName === 'fullname' && fields.fullname.value.trim() === '') {
                error = 'Vui lòng nhập họ và tên.';
            }

            if (fieldName === 'email') {
                const email = fields.email.value.trim();
                if (!/^[^\s@]+@[^\s@]+\.[^\s@]+$/.test(email)) {
                    error = 'Email chưa đúng định dạng.';
                }
            }

            if (fieldName === 'phone') {
                fields.phone.value = fields.phone.value.replace(/\D+/g, '').slice(0, 10);
                if (!/^[0-9]{10}$/.test(fields.phone.value)) {
                    error = 'Số điện thoại phải gồm đúng 10 số.';
                }
            }

            if (fieldName === 'position' && fields.position.value.trim() === '') {
                error = 'Vui lòng chọn vị trí ứng tuyển.';
            }

            if (fieldName === 'cv_file') {
                const file = fields.cv_file.files && fields.cv_file.files[0];
                if (!file) {
                    error = 'Vui lòng upload CV.';
                } else {
                    const extension = file.name.split('.').pop().toLowerCase();
                    if (!allowedCvExtensions.includes(extension)) {
                        error = 'CV chỉ chấp nhận file pdf, doc hoặc docx.';
                    }
                }
            }

            setFieldError(fieldName, error);
            return error === '';
        };

        const validateForm = function () {
            let isValid = true;
            ['fullname', 'email', 'phone', 'position', 'cv_file'].forEach(function (fieldName) {
                isValid = validateField(fieldName) && isValid;
            });
            return isValid;
        };

        const openModal = function (jobTitle, jobLocation) {
            form.reset();
            Object.keys(fields).forEach(function (fieldName) {
                setFieldError(fieldName, '');
            });
            setStatus('', false);

            fields.position.value = jobTitle || '';
            fields.location.value = jobLocation || '';
            modal.classList.add('is-open');
            modal.setAttribute('aria-hidden', 'false');
            document.body.style.overflow = 'hidden';
            window.setTimeout(function () {
                fields.fullname.focus();
            }, 120);
        };

        const closeModal = function () {
            modal.classList.remove('is-open');
            modal.setAttribute('aria-hidden', 'true');
            document.body.style.overflow = '';
        };

        document.querySelectorAll('[data-apply-job]').forEach(function (button) {
            button.addEventListener('click', function () {
                openModal(button.dataset.jobTitle || '', button.dataset.jobLocation || '');
            });
        });

        modal.querySelectorAll('[data-apply-close]').forEach(function (button) {
            button.addEventListener('click', closeModal);
        });

        document.addEventListener('keydown', function (event) {
            if (event.key === 'Escape' && modal.classList.contains('is-open')) {
                closeModal();
            }
        });

        ['fullname', 'email', 'phone'].forEach(function (fieldName) {
            fields[fieldName].addEventListener('input', function () {
                validateField(fieldName);
            });
            fields[fieldName].addEventListener('blur', function () {
                validateField(fieldName);
            });
        });

        fields.cv_file.addEventListener('change', function () {
            validateField('cv_file');
        });

        form.addEventListener('submit', function (event) {
            event.preventDefault();
            setStatus('', false);

            if (!validateForm()) {
                showToast('Vui lòng kiểm tra lại thông tin ứng tuyển.', true);
                setStatus('Vui lòng kiểm tra lại thông tin ứng tuyển.', true);
                return;
            }

            setSubmitting(true);

            fetch(form.action, {
                method: 'POST',
                body: new FormData(form),
                headers: {
                    'X-Requested-With': 'XMLHttpRequest',
                },
            })
                .then(function (response) {
                    return response.json().then(function (payload) {
                        return { ok: response.ok, payload: payload };
                    });
                })
                .then(function (result) {
                    if (!result.ok || !result.payload.success) {
                        const errors = result.payload.errors || {};
                        Object.keys(errors).forEach(function (fieldName) {
                            setFieldError(fieldName, errors[fieldName]);
                        });
                        showToast(errors.general || 'Không thể gửi hồ sơ. Vui lòng kiểm tra lại.', true);
                        setStatus(errors.general || 'Không thể gửi hồ sơ. Vui lòng kiểm tra lại.', true);
                        return;
                    }

                    showToast('Nộp hồ sơ thành công', false);
                    setStatus(result.payload.message || 'Hồ sơ của bạn đã được gửi thành công.', false);
                    form.reset();
                    closeModal();
                })
                .catch(function () {
                    showToast('Không thể kết nối máy chủ. Vui lòng thử lại.', true);
                    setStatus('Không thể kết nối máy chủ. Vui lòng thử lại.', true);
                })
                .finally(function () {
                    setSubmitting(false);
                });
        });
    })();
</script>
