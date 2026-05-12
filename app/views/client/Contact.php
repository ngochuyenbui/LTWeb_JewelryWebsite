<?php
$content = $content ?? [];
$images = $images ?? [];
$errors = $errors ?? [];
$old = $old ?? [];

$e = static function ($value) {
    return htmlspecialchars((string)$value, ENT_QUOTES, 'UTF-8');
};

$text = static function ($key, $default = '') use ($content, $e) {
    return $e($content[$key] ?? $default);
};

$value = static function ($key) use ($old, $e) {
    return $e($old[$key] ?? '');
};

$imageUrl = static function ($key, $default = '') use ($images, $e) {
    $path = isset($images[$key]) ? $images[$key]->filepath : $default;
    $path = str_replace('\\', '/', ltrim((string)$path, '/'));
    return $e(URLROOT . '/' . $path);
};

$contactOptions = [
    'Tư vấn chọn sản phẩm',
    'Đặt lịch thử nhẫn',
    'Hỏi bảo hành / làm sạch',
    'Yêu cầu khác',
];
?>

<style>
    .contact-page {
        --ink: #17120d;
        --muted: #6f665c;
        --line: #e7dfd4;
        --paper: #fffaf3;
        --soft: #f7f1e8;
        --gold: #b7791f;
        background: #fff;
        color: var(--ink);
    }

    .contact-container {
        width: min(1180px, calc(100% - 40px));
        margin: 0 auto;
    }

    .contact-eyebrow {
        color: var(--gold);
        font-size: 12px;
        font-weight: 800;
        letter-spacing: .16em;
        text-transform: uppercase;
    }

    .contact-hero {
        border-bottom: 1px solid var(--line);
        background: linear-gradient(135deg, #fffaf3 0%, #f5eadc 52%, #ffffff 100%);
    }

    .contact-hero-inner {
        display: grid;
        grid-template-columns: minmax(0, 1fr) minmax(360px, 480px);
        gap: 52px;
        align-items: center;
        padding: 58px 0;
    }

    .contact-hero h1 {
        max-width: 720px;
        margin: 14px 0 0;
        font-size: clamp(38px, 5vw, 64px);
        line-height: 1.05;
        font-weight: 750;
        letter-spacing: 0;
    }

    .contact-hero p {
        max-width: 660px;
        margin: 20px 0 0;
        color: var(--muted);
        font-size: 16px;
        line-height: 1.8;
    }

    .contact-hero-actions {
        display: flex;
        flex-wrap: wrap;
        gap: 12px;
        margin-top: 28px;
    }

    .contact-btn {
        display: inline-flex;
        align-items: center;
        justify-content: center;
        min-height: 46px;
        padding: 0 22px;
        border-radius: 8px;
        font-size: 14px;
        font-weight: 800;
        transition: .2s ease;
    }

    .contact-btn-primary {
        background: var(--ink);
        color: #fff;
    }

    .contact-btn-primary:hover {
        background: var(--gold);
    }

    .contact-btn-secondary {
        border: 1px solid #cfc2b4;
        background: rgba(255,255,255,.72);
        color: var(--ink);
    }

    .contact-btn-secondary:hover {
        border-color: var(--gold);
        color: var(--gold);
    }

    .contact-hero-image {
        overflow: hidden;
        border: 1px solid var(--line);
        border-radius: 16px;
        background: #fff;
        box-shadow: 0 18px 50px rgba(23,18,13,.08);
    }

    .contact-hero-image img {
        width: 100%;
        height: 380px;
        object-fit: cover;
    }

    .contact-promise {
        border-bottom: 1px solid var(--line);
        background: #fff;
    }

    .contact-promise-grid {
        display: grid;
        grid-template-columns: repeat(4, minmax(0, 1fr));
        gap: 18px;
        padding: 24px 0;
    }

    .contact-promise-item {
        min-height: 112px;
        border: 1px solid var(--line);
        border-radius: 12px;
        background: #fff;
        padding: 18px;
    }

    .contact-promise-item strong {
        display: block;
        font-size: 15px;
    }

    .contact-promise-item span {
        display: block;
        margin-top: 7px;
        color: var(--muted);
        font-size: 14px;
        line-height: 1.6;
    }

    .contact-main {
        padding: 68px 0;
    }

    .contact-layout {
        display: grid;
        grid-template-columns: minmax(300px, .82fr) minmax(0, 1.18fr);
        gap: 26px;
        align-items: start;
    }

    .contact-panel {
        border: 1px solid var(--line);
        border-radius: 14px;
        background: #fff;
        padding: 26px;
    }

    .contact-panel-soft {
        background: var(--soft);
    }

    .contact-panel h2 {
        margin: 0;
        font-size: 24px;
        line-height: 1.25;
        font-weight: 760;
    }

    .contact-panel-intro {
        margin: 10px 0 0;
        color: var(--muted);
        font-size: 14px;
        line-height: 1.7;
    }

    .contact-info-list {
        display: grid;
        gap: 14px;
        margin-top: 22px;
    }

    .contact-info-row {
        border-bottom: 1px solid var(--line);
        padding-bottom: 14px;
    }

    .contact-info-row:last-child {
        border-bottom: 0;
        padding-bottom: 0;
    }

    .contact-info-row span {
        display: block;
        color: #887d70;
        font-size: 12px;
        font-weight: 800;
        letter-spacing: .1em;
        text-transform: uppercase;
    }

    .contact-info-row strong,
    .contact-info-row a {
        display: block;
        margin-top: 5px;
        color: var(--ink);
        font-size: 15px;
        line-height: 1.6;
        font-weight: 700;
    }

    .contact-note {
        margin-top: 22px;
        border-radius: 12px;
        background: #fff;
        padding: 18px;
    }

    .contact-note strong {
        display: block;
        font-size: 15px;
    }

    .contact-note p {
        margin: 8px 0 0;
        color: var(--muted);
        font-size: 14px;
        line-height: 1.6;
    }

    .contact-option-grid {
        display: grid;
        grid-template-columns: repeat(2, minmax(0, 1fr));
        gap: 10px;
        margin-top: 18px;
    }

    .contact-option {
        min-height: 44px;
        border: 1px solid var(--line);
        border-radius: 9px;
        background: #fffaf3;
        padding: 0 14px;
        color: var(--ink);
        font-size: 13px;
        font-weight: 750;
        cursor: pointer;
        transition: .2s ease;
    }

    .contact-option:hover,
    .contact-option.is-active {
        border-color: var(--gold);
        background: #fff;
        color: var(--gold);
    }

    .contact-alert {
        margin-top: 18px;
        border-radius: 10px;
        padding: 14px 16px;
        font-size: 14px;
        line-height: 1.6;
    }

    .contact-alert-success {
        border: 1px solid #bbf7d0;
        background: #f0fdf4;
        color: #166534;
    }

    .contact-alert-error {
        border: 1px solid #fecaca;
        background: #fef2f2;
        color: #b91c1c;
    }

    .contact-form {
        display: grid;
        gap: 16px;
        margin-top: 22px;
    }

    .contact-form-grid {
        display: grid;
        grid-template-columns: repeat(2, minmax(0, 1fr));
        gap: 14px;
    }

    .contact-field label {
        display: block;
        margin-bottom: 7px;
        color: var(--ink);
        font-size: 14px;
        font-weight: 750;
    }

    .contact-field input,
    .contact-field textarea {
        width: 100%;
        border: 1px solid #d8d0c7;
        border-radius: 9px;
        background: #fff;
        padding: 12px 13px;
        color: var(--ink);
        font-size: 14px;
        line-height: 1.45;
        outline: none;
        transition: .2s ease;
    }

    .contact-field input:focus,
    .contact-field textarea:focus {
        border-color: var(--gold);
        box-shadow: 0 0 0 3px rgba(183,121,31,.12);
    }

    .contact-field textarea {
        min-height: 150px;
        resize: vertical;
    }

    .contact-error {
        margin: 7px 0 0;
        color: #dc2626;
        font-size: 13px;
        line-height: 1.5;
    }

    .contact-submit-row {
        display: flex;
        flex-wrap: wrap;
        align-items: center;
        gap: 14px;
        margin-top: 4px;
    }

    .contact-submit-note {
        color: var(--muted);
        font-size: 13px;
        line-height: 1.5;
    }

    .contact-faq {
        padding: 0 0 68px;
    }

    .contact-faq-grid {
        display: grid;
        grid-template-columns: repeat(3, minmax(0, 1fr));
        gap: 18px;
    }

    .contact-faq-item {
        border: 1px solid var(--line);
        border-radius: 12px;
        background: #fff;
        padding: 20px;
    }

    .contact-faq-item h3 {
        margin: 0;
        font-size: 16px;
        font-weight: 760;
    }

    .contact-faq-item p {
        margin: 9px 0 0;
        color: var(--muted);
        font-size: 14px;
        line-height: 1.65;
    }

    @media (max-width: 1023px) {
        .contact-hero-inner,
        .contact-layout {
            grid-template-columns: 1fr;
        }

        .contact-promise-grid,
        .contact-faq-grid {
            grid-template-columns: repeat(2, minmax(0, 1fr));
        }
    }

    @media (max-width: 700px) {
        .contact-container {
            width: min(100% - 28px, 1180px);
        }

        .contact-hero-inner {
            padding: 42px 0;
        }

        .contact-hero-image img {
            height: 260px;
        }

        .contact-promise-grid,
        .contact-form-grid,
        .contact-option-grid,
        .contact-faq-grid {
            grid-template-columns: 1fr;
        }
    }
</style>

<div class="contact-page">
    <section class="contact-hero">
        <div class="contact-container contact-hero-inner">
            <div>
                <p class="contact-eyebrow"><?= $text('site_brand_name', 'AURELIA') ?></p>
                <h1><?= $text('contact_heading', 'Liên hệ Aurelia') ?></h1>
                <p><?= $text('contact_intro', 'Đội ngũ tư vấn luôn sẵn sàng hỗ trợ chọn mẫu, đặt lịch thử nhẫn và giải đáp chính sách bảo hành.') ?></p>

                <div class="contact-hero-actions">
                    <a href="#contact-form" class="contact-btn contact-btn-primary">Gửi yêu cầu tư vấn</a>
                    <a href="tel:<?= $e(preg_replace('/\s+/', '', $content['site_phone'] ?? '19008868')) ?>" class="contact-btn contact-btn-secondary">Gọi hotline</a>
                </div>
            </div>

            <div class="contact-hero-image">
                <img src="<?= $imageUrl('contact_banner', 'assets/news/topthuonghieutrangsucuytintaiv.webp') ?>" alt="<?= $text('contact_heading', 'Liên hệ Aurelia') ?>">
            </div>
        </div>
    </section>

    <section class="contact-promise">
        <div class="contact-container contact-promise-grid">
            <div class="contact-promise-item">
                <strong>Phản hồi trong 24h</strong>
                <span>Yêu cầu mới được ghi nhận và chuyển đến đội ngũ tư vấn trong giờ làm việc.</span>
            </div>
            <div class="contact-promise-item">
                <strong>Tư vấn size miễn phí</strong>
                <span>Hỗ trợ chọn size nhẫn, vòng tay và gợi ý kiểu dáng phù hợp.</span>
            </div>
            <div class="contact-promise-item">
                <strong>Hỗ trợ bảo hành</strong>
                <span>Tiếp nhận yêu cầu làm sạch, đánh bóng và kiểm tra sản phẩm.</span>
            </div>
            <div class="contact-promise-item">
                <strong>Bảo mật thông tin</strong>
                <span>Thông tin liên hệ chỉ được dùng để hỗ trợ yêu cầu của khách hàng.</span>
            </div>
        </div>
    </section>

    <section class="contact-main">
        <div class="contact-container contact-layout">
            <aside class="contact-panel contact-panel-soft">
                <h2>Thông tin cửa hàng</h2>
                <p class="contact-panel-intro"><?= $text('site_brand_name', 'AURELIA') ?> <?= $text('site_tagline', 'Fine Jewelry') ?> luôn sẵn sàng hỗ trợ qua hotline, email hoặc tại showroom.</p>

                <div class="contact-info-list">
                    <div class="contact-info-row">
                        <span>Showroom</span>
                        <strong><?= $text('site_address', '123 Lê Lợi, Quận 1, TP. Hồ Chí Minh') ?></strong>
                    </div>
                    <div class="contact-info-row">
                        <span>Hotline</span>
                        <a href="tel:<?= $e(preg_replace('/\s+/', '', $content['site_phone'] ?? '19008868')) ?>"><?= $text('site_phone', '1900 8868') ?></a>
                    </div>
                    <div class="contact-info-row">
                        <span>Email</span>
                        <a href="mailto:<?= $text('site_email', 'contact@aurelia.vn') ?>"><?= $text('site_email', 'contact@aurelia.vn') ?></a>
                    </div>
                    <div class="contact-info-row">
                        <span>Giờ mở cửa</span>
                        <strong><?= $text('contact_working_hours', '8:00 - 21:00, Thứ 2 đến Chủ nhật') ?></strong>
                    </div>
                </div>

                <div class="contact-note">
                    <strong>Muốn được gọi lại?</strong>
                    <p>Chọn nhu cầu, để lại số điện thoại và thời gian thuận tiện trong phần nội dung. Aurelia sẽ chủ động liên hệ lại.</p>
                </div>
            </aside>

            <div class="contact-panel" id="contact-form">
                <p class="contact-eyebrow">Gửi yêu cầu</p>
                <h2>Cho Aurelia biết bạn đang cần hỗ trợ gì</h2>
                <p class="contact-panel-intro">Chọn nhanh một nhu cầu bên dưới hoặc tự nhập tiêu đề. Form vẫn được xử lý bởi hệ thống liên hệ hiện tại.</p>

                <div class="contact-option-grid" aria-label="Chọn nhu cầu liên hệ">
                    <?php foreach ($contactOptions as $option): ?>
                        <button type="button" class="contact-option" data-contact-subject="<?= $e($option) ?>"><?= $e($option) ?></button>
                    <?php endforeach; ?>
                </div>

                <?php if (!empty($success)): ?>
                    <div class="contact-alert contact-alert-success">
                        Cảm ơn bạn đã liên hệ. Aurelia sẽ phản hồi trong thời gian sớm nhất.
                    </div>
                <?php endif; ?>

                <?php if (!empty($errors['form'])): ?>
                    <div class="contact-alert contact-alert-error">
                        <?= $e($errors['form']) ?>
                    </div>
                <?php endif; ?>

                <form action="<?= $e(URLROOT . '/Contact') ?>" method="POST" class="contact-form" data-contact-form>
                    <div class="contact-field">
                        <label for="name">Họ tên</label>
                        <input id="name" name="name" type="text" value="<?= $value('name') ?>" maxlength="100" required>
                        <?php if (!empty($errors['name'])): ?><p class="contact-error"><?= $e($errors['name']) ?></p><?php endif; ?>
                    </div>

                    <div class="contact-form-grid">
                        <div class="contact-field">
                            <label for="email">Email</label>
                            <input id="email" name="email" type="email" value="<?= $value('email') ?>" maxlength="100" required>
                            <?php if (!empty($errors['email'])): ?><p class="contact-error"><?= $e($errors['email']) ?></p><?php endif; ?>
                        </div>
                        <div class="contact-field">
                            <label for="phone">Số điện thoại</label>
                            <input id="phone" name="phone" type="tel" value="<?= $value('phone') ?>" maxlength="15" pattern="[0-9+ .-]{8,15}">
                            <?php if (!empty($errors['phone'])): ?><p class="contact-error"><?= $e($errors['phone']) ?></p><?php endif; ?>
                        </div>
                    </div>

                    <div class="contact-field">
                        <label for="subject">Tiêu đề</label>
                        <input id="subject" name="subject" type="text" value="<?= $value('subject') ?>" maxlength="200" required>
                        <?php if (!empty($errors['subject'])): ?><p class="contact-error"><?= $e($errors['subject']) ?></p><?php endif; ?>
                    </div>

                    <div class="contact-field">
                        <label for="message">Nội dung</label>
                        <textarea id="message" name="message" rows="6" maxlength="3000" required><?= $value('message') ?></textarea>
                        <?php if (!empty($errors['message'])): ?><p class="contact-error"><?= $e($errors['message']) ?></p><?php endif; ?>
                    </div>

                    <div class="contact-submit-row">
                        <button type="submit" class="contact-btn contact-btn-primary">Gửi liên hệ</button>
                        <span class="contact-submit-note">Thông tin của bạn được dùng để phản hồi yêu cầu tư vấn.</span>
                    </div>
                </form>
            </div>
        </div>
    </section>

    <section class="contact-faq">
        <div class="contact-container contact-faq-grid">
            <div class="contact-faq-item">
                <h3>Có tư vấn online không?</h3>
                <p>Có. Bạn có thể gửi yêu cầu kèm ngân sách, dịp sử dụng và kiểu dáng yêu thích để được gợi ý mẫu phù hợp.</p>
            </div>
            <div class="contact-faq-item">
                <h3>Có hỗ trợ đo size nhẫn không?</h3>
                <p>Aurelia có thể hướng dẫn đo size tại nhà hoặc hỗ trợ đặt lịch thử trực tiếp tại showroom.</p>
            </div>
            <div class="contact-faq-item">
                <h3>Bao lâu nhận được phản hồi?</h3>
                <p>Yêu cầu thường được phản hồi trong 24h làm việc. Với trường hợp cần gấp, bạn có thể gọi hotline.</p>
            </div>
        </div>
    </section>
</div>

<script>
    (function () {
        var form = document.querySelector('[data-contact-form]');
        var subjectInput = document.getElementById('subject');
        var messageInput = document.getElementById('message');
        var optionButtons = document.querySelectorAll('[data-contact-subject]');

        optionButtons.forEach(function (button) {
            button.addEventListener('click', function () {
                var subject = button.getAttribute('data-contact-subject') || '';
                optionButtons.forEach(function (item) {
                    item.classList.remove('is-active');
                });
                button.classList.add('is-active');
                if (subjectInput) {
                    subjectInput.value = subject;
                    subjectInput.focus();
                }
                if (messageInput && !messageInput.value.trim()) {
                    messageInput.value = 'Tôi muốn được hỗ trợ về: ' + subject + '.';
                }
            });
        });

        if (!form) {
            return;
        }

        form.addEventListener('submit', function (event) {
            var name = form.querySelector('[name="name"]').value.trim();
            var email = form.querySelector('[name="email"]').value.trim();
            var subject = form.querySelector('[name="subject"]').value.trim();
            var message = form.querySelector('[name="message"]').value.trim();

            if (name.length < 2 || email.indexOf('@') === -1 || subject.length === 0 || message.length < 10) {
                event.preventDefault();
                alert('Vui lòng nhập đầy đủ thông tin hợp lệ trước khi gửi.');
            }
        });
    })();
</script>
