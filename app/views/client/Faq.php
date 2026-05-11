<?php
$faqs = $faqs ?? [];
$form = $form ?? ['name' => '', 'email' => '', 'phone' => '', 'question' => ''];
$errors = $errors ?? [];
$success = $success ?? '';
$e = static fn($value) => htmlspecialchars((string)$value, ENT_QUOTES, 'UTF-8');

$icon = static function (string $name, string $class = 'w-5 h-5'): string {
    $attrs = 'xmlns="http://www.w3.org/2000/svg" class="' . $class . '" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true"';
    $paths = [
        'chevron-down' => '<path d="m6 9 6 6 6-6"></path>',
        'chevron-up' => '<path d="m18 15-6-6-6 6"></path>',
        'send' => '<path d="m22 2-7 20-4-9-9-4Z"></path><path d="M22 2 11 13"></path>',
        'help-circle' => '<circle cx="12" cy="12" r="10"></circle><path d="M9.09 9a3 3 0 1 1 5.82 1c0 2-3 2-3 4"></path><path d="M12 17h.01"></path>',
    ];

    return '<svg ' . $attrs . '>' . ($paths[$name] ?? '') . '</svg>';
};
?>

<style>
    .faq-section {
        padding: 5rem 0 6rem;
        background:
            radial-gradient(circle at top center, rgba(217, 180, 97, 0.08), transparent 34rem),
            linear-gradient(180deg, #fbfaf7 0%, #ffffff 72%);
        color: #211c16;
    }
    .faq-shell {
        max-width: 48rem;
    }
    .faq-eyebrow {
        color: #d97706;
        font-size: 0.875rem;
        font-weight: 500;
        letter-spacing: 0.36em;
        line-height: 1.5;
    }
    .faq-title {
        color: #211c16;
        /* font-family: ui-serif, Georgia, Cambria, "Times New Roman", Times, serif; */
        font-size: 2.25rem;
        font-weight: 700;
        letter-spacing: 0;
        line-height: 1.16;
    }
    .faq-heading,
    .faq-question-intro {
        display: flex;
        flex-direction: column;
        align-items: center;
        text-align: center;
        margin-bottom: 1rem;
        margin-left: auto;
        margin-right: auto;
    }
    .faq-heading {
        margin-bottom: 0;
    }
    .faq-heading p,
    .faq-heading h1,
    .faq-question-intro h2,
    .faq-question-intro p {
        width: 100%;
        text-align: center;
    }
    .faq-question-intro p {
        max-width: 36rem;
        margin-left: auto;
        margin-right: auto;
    }
    @media (min-width: 768px) {
        .faq-title { font-size: 3rem; }
    }
    .faq-list {
        margin-top: 1rem;
        display: grid;
        gap: 1rem;
    }
    .faq-card {
        border: 0.5px solid rgba(217, 180, 97, 0.22);
        border-radius: 0.5rem;
        background: rgba(255, 253, 247, 0.94);
        box-shadow: 0 1rem 3.5rem rgba(33, 28, 22, 0.08);
        overflow: hidden;
        transition: border-color 180ms ease, box-shadow 180ms ease, transform 180ms ease;
    }
    .faq-card:hover {
        border-color: rgba(181, 133, 45, 0.36);
        box-shadow: 0 1.5rem 4rem rgba(33, 28, 22, 0.12);
        transform: translateY(-1px);
    }
    .faq-trigger {
        min-height: 3rem;
        padding: 1rem 1rem;
        color: #211c16;
        line-height: 1.5;
    }
    .faq-question {
        padding-right: 1.5rem;
        /* font-family: ui-serif, Georgia, Cambria, Times, serif; */
        font-size: 1.05rem;
        font-weight: 600;
        line-height: 1.45;
    }
    .faq-answer {
        border-top: 1px solid rgba(217, 180, 97, 0.2);
        padding: 0.25rem 2rem 1.75rem;
        color: #64748b;
        font-size: 0.875rem;
        line-height: 1.75;
    }
    .faq-question-card {
        border: 0.5px solid rgba(217, 180, 97, 0.26);
        border-radius: 0.5rem;
        background: rgba(255, 253, 247, 0.94);
        box-shadow: 0 1.35rem 3rem rgba(33, 28, 22, 0.09);
    }
    .faq-form {
        display: grid;
        gap: 1.25rem;
        max-width: 36rem;
        margin-left: auto;
        margin-right: auto;
    }
    .faq-form-row {
        display: grid;
        gap: 1.25rem;
    }
    @media (min-width: 640px) {
        .faq-form-row {
            grid-template-columns: repeat(2, minmax(0, 1fr));
        }
    }
    .faq-input {
        width: 100%;
        border: 1px solid #ded6ca;
        border-radius: 0.375rem;
        background: #fff;
        padding: 0.85rem 1rem;
        font-size: 0.875rem;
        line-height: 1.7;
        outline: none;
        transition: border-color 160ms ease, box-shadow 160ms ease;
    }
    .faq-input:focus {
        border-color: #d69e2e;
        box-shadow: 0 0 0 3px rgba(214, 158, 46, 0.16);
    }
    .faq-input.is-invalid {
        border-color: #dc2626;
        box-shadow: 0 0 0 3px rgba(220, 38, 38, 0.12);
    }
    .faq-form-errors {
        border: 1px solid #fecaca;
        border-radius: 0.5rem;
        background: #fef2f2;
        color: #b91c1c;
        padding: 0.85rem 1rem;
        font-size: 0.875rem;
        line-height: 1.65;
    }
    .faq-form-errors.hidden {
        display: none;
    }
    .faq-submit {
        display: inline-flex;
        width: 100%;
        align-items: center;
        justify-content: center;
        gap: 0.5rem;
        border-radius: 0.375rem;
        background: linear-gradient(135deg, #f6e3a1 0%, #d9b461 45%, #b5852d 100%);
        padding: 0.95rem 1.25rem;
        color: #211c16;
        font-weight: 700;
        transition: transform 160ms ease, box-shadow 160ms ease;
    }
    .faq-submit:hover {
        transform: translateY(-1px);
        box-shadow: 0 0.8rem 1.5rem rgba(181, 133, 45, 0.22);
    }
    .faq-toast {
        position: fixed;
        right: 1rem;
        bottom: 1rem;
        z-index: 60;
        max-width: min(24rem, calc(100vw - 2rem));
        border: 1px solid #d9b461;
        border-radius: 0.75rem;
        background: #fffdf7;
        padding: 1rem;
        color: #211c16;
        box-shadow: 0 1rem 2rem rgba(33, 28, 22, 0.18);
    }
    @media (max-width: 640px) {
        .faq-section { padding: 3.5rem 0 4rem; }
        .faq-list { margin-top: 3rem; gap: 0.9rem; }
        .faq-trigger { min-height: 4.75rem; padding: 1.25rem; }
        .faq-question { font-size: 1rem; }
        .faq-answer { padding: 0 1.25rem 1.35rem; }
        .faq-toast { left: 1rem; right: 1rem; }
    }
</style>

<section class="faq-section">
    <div class="container mx-auto px-4 faq-shell">
        <div class="faq-heading text-center">
            <p class="faq-eyebrow uppercase mb-3">Hỗ trợ</p>
            <h1 class="faq-title">Câu Hỏi Thường Gặp</h1>
        </div>

        <div class="faq-list" data-faq-list>
            <?php foreach ($faqs as $index => $faq): ?>
                <?php
                    $isOpen = $index === 0;
                    $answerId = 'faq-answer-' . (int)($faq->faqId ?? $index);
                ?>
                <article class="faq-card">
                    <button
                        type="button"
                        class="faq-trigger w-full flex items-center justify-between text-left"
                        data-faq-trigger
                        aria-expanded="<?= $isOpen ? 'true' : 'false' ?>"
                        aria-controls="<?= $e($answerId) ?>"
                    >
                        <span class="faq-question"><?= $e($faq->question ?? '') ?></span>
                        <span class="shrink-0 text-amber-600 <?= $isOpen ? '' : 'hidden' ?>" data-faq-icon-open><?= $icon('chevron-up', 'w-5 h-5') ?></span>
                        <span class="shrink-0 text-stone-500 <?= $isOpen ? 'hidden' : '' ?>" data-faq-icon-closed><?= $icon('chevron-down', 'w-5 h-5') ?></span>
                    </button>
                    <div id="<?= $e($answerId) ?>" class="faq-answer <?= $isOpen ? '' : 'hidden' ?>" data-faq-panel>
                        <?= nl2br($e($faq->answer ?? '')) ?>
                    </div>
                </article>
            <?php endforeach; ?>
        </div>

        <div id="faq-question-form" class="mt-16 faq-question-card p-6 md:p-8 gap-2 md:gap-2">
            <div class="faq-question-intro text-center">
                <div class="w-10 h-10 rounded-full bg-amber-100 flex items-center justify-center mx-auto mb-4 text-amber-600">
                    <?= $icon('help-circle', 'w-4 h-4') ?>
                </div>
                <h2 class="text-2xl md:text-3xl font-serif font-bold mb-2">Bạn có câu hỏi khác?</h2>
                <p class="text-slate-600 text-sm leading-relaxed">Hãy gửi câu hỏi của bạn, đội ngũ tư vấn AURELIA sẽ liên hệ lại sớm nhất.</p>
            </div>

            <?php if (!empty($errors)): ?>
                <div class="mb-5 rounded-md border border-red-200 bg-red-50 px-4 py-3 text-sm text-red-700">
                    <?php foreach ($errors as $error): ?>
                        <div><?= $e($error) ?></div>
                    <?php endforeach; ?>
                </div>
            <?php endif; ?>

            <form action="<?= URLROOT ?>/faq/submit" method="POST" class="faq-form" data-faq-form novalidate>
                <div class="faq-form-errors hidden" data-faq-form-errors></div>
                <div class="faq-form-row">
                    <input class="faq-input" type="text" name="name" placeholder="Họ và tên" value="<?= $e($form['name'] ?? '') ?>" minlength="2" maxlength="120" required>
                    <input class="faq-input" type="email" name="email" placeholder="Email" value="<?= $e($form['email'] ?? '') ?>" maxlength="150" required>
                </div>
                <input class="faq-input" type="tel" name="phone" placeholder="Số điện thoại" value="<?= $e($form['phone'] ?? '') ?>" pattern="[0-9+\-\s().]{8,30}" maxlength="30">
                <textarea class="faq-input resize-none" name="question" placeholder="Nhập câu hỏi của bạn..." rows="4" minlength="10" maxlength="2000" required><?= $e($form['question'] ?? '') ?></textarea>
                <button class="faq-submit" type="submit">
                    <?= $icon('send', 'w-4 h-4') ?>
                    Gửi câu hỏi
                </button>
            </form>
        </div>
    </div>
</section>

<?php if (!empty($success)): ?>
    <div class="faq-toast" role="status" data-faq-toast>
        <p class="font-semibold mb-1">Gửi câu hỏi thành công!</p>
        <p class="text-sm text-slate-600"><?= $e($success) ?></p>
    </div>
<?php endif; ?>

<script>
    (function () {
        document.querySelectorAll('[data-faq-trigger]').forEach(function (button) {
            button.addEventListener('click', function () {
                const card = button.closest('.faq-card');
                const panel = card ? card.querySelector('[data-faq-panel]') : null;
                const openIcon = card ? card.querySelector('[data-faq-icon-open]') : null;
                const closedIcon = card ? card.querySelector('[data-faq-icon-closed]') : null;
                if (!panel) {
                    return;
                }

                const isOpen = button.getAttribute('aria-expanded') === 'true';
                button.setAttribute('aria-expanded', isOpen ? 'false' : 'true');
                panel.classList.toggle('hidden', isOpen);
                if (openIcon && closedIcon) {
                    openIcon.classList.toggle('hidden', isOpen);
                    closedIcon.classList.toggle('hidden', !isOpen);
                }
            });
        });

        const faqForm = document.querySelector('[data-faq-form]');
        if (faqForm) {
            const errorBox = faqForm.querySelector('[data-faq-form-errors]');
            const fields = {
                name: faqForm.querySelector('[name="name"]'),
                email: faqForm.querySelector('[name="email"]'),
                phone: faqForm.querySelector('[name="phone"]'),
                question: faqForm.querySelector('[name="question"]')
            };
            const textLength = function (value) {
                return Array.from((value || '').trim()).length;
            };
            const showErrors = function (errors) {
                if (!errorBox) {
                    return;
                }
                errorBox.innerHTML = errors.map(function (error) {
                    return '<div>' + error + '</div>';
                }).join('');
                errorBox.classList.remove('hidden');
            };
            const clearErrors = function () {
                if (errorBox) {
                    errorBox.innerHTML = '';
                    errorBox.classList.add('hidden');
                }
                Object.keys(fields).forEach(function (key) {
                    if (fields[key]) {
                        fields[key].classList.remove('is-invalid');
                    }
                });
            };
            const markInvalid = function (field) {
                if (field) {
                    field.classList.add('is-invalid');
                }
            };

            faqForm.addEventListener('submit', function (event) {
                const errors = [];
                clearErrors();

                const name = fields.name ? fields.name.value.trim() : '';
                const email = fields.email ? fields.email.value.trim() : '';
                const phone = fields.phone ? fields.phone.value.trim() : '';
                const question = fields.question ? fields.question.value.trim() : '';
                const emailPattern = /^[^\s@]+@[^\s@]+\.[^\s@]+$/;
                const phonePattern = /^[0-9+\-\s().]{8,30}$/;

                if (textLength(name) < 2) {
                    errors.push('Vui lòng nhập họ và tên từ 2 ký tự trở lên.');
                    markInvalid(fields.name);
                } else if (textLength(name) > 120) {
                    errors.push('Họ và tên không được vượt quá 120 ký tự.');
                    markInvalid(fields.name);
                }

                if (!emailPattern.test(email)) {
                    errors.push('Vui lòng nhập email hợp lệ.');
                    markInvalid(fields.email);
                } else if (textLength(email) > 150) {
                    errors.push('Email không được vượt quá 150 ký tự.');
                    markInvalid(fields.email);
                }

                if (phone !== '' && !phonePattern.test(phone)) {
                    errors.push('Số điện thoại chỉ gồm số, khoảng trắng và ký tự + - ( ), dài 8-30 ký tự.');
                    markInvalid(fields.phone);
                }

                if (textLength(question) < 10) {
                    errors.push('Vui lòng nhập câu hỏi từ 10 ký tự trở lên.');
                    markInvalid(fields.question);
                } else if (textLength(question) > 2000) {
                    errors.push('Câu hỏi không được vượt quá 2000 ký tự.');
                    markInvalid(fields.question);
                }

                if (errors.length > 0) {
                    event.preventDefault();
                    showErrors(errors);
                    const firstInvalid = faqForm.querySelector('.is-invalid');
                    if (firstInvalid) {
                        firstInvalid.focus();
                    }
                }
            });
        }

        const toast = document.querySelector('[data-faq-toast]');
        if (toast) {
            window.setTimeout(function () {
                toast.remove();
            }, 5200);
        }
    })();
</script>
