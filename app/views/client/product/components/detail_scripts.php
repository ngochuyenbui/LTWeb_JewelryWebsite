<!-- Nhúng JS Fotorama -->
<script src="https://cdnjs.cloudflare.com/ajax/libs/fotorama/4.6.4/fotorama.js"></script>

<!-- Script Xử lý -->
<script>
document.addEventListener('DOMContentLoaded', function() {
    const decreaseBtn = document.getElementById('decrease-qty');
    const increaseBtn = document.getElementById('increase-qty');
    const qtyInput = document.getElementById('product-qty');

    if (decreaseBtn && increaseBtn && qtyInput) {
        decreaseBtn.addEventListener('click', function() {
            let currentValue = parseInt(qtyInput.value) || 1;
            if (currentValue > 1) qtyInput.value = currentValue - 1;
        });
        increaseBtn.addEventListener('click', function() {
            let currentValue = parseInt(qtyInput.value) || 1;
            let maxStock = parseInt(qtyInput.getAttribute('max')) || 1;
            if (currentValue < maxStock) qtyInput.value = currentValue + 1;
        });
        qtyInput.addEventListener('change', function() {
            let currentValue = parseInt(qtyInput.value) || 1;
            let maxStock = parseInt(qtyInput.getAttribute('max')) || 1;
            if (currentValue < 1) qtyInput.value = 1;
            else if (currentValue > maxStock) qtyInput.value = maxStock;
        });
    }

    // --- Logic Kết hợp Fotorama & Magic Zoom ---
    const $fotoramaDiv = $('.fotorama').fotorama();
    const fotorama = $fotoramaDiv.data('fotorama');

    const thumbBtns = document.querySelectorAll('.thumbnail-btn');
    thumbBtns.forEach(btn => {
        btn.addEventListener('click', function() {
            const index = this.getAttribute('data-index');
            fotorama.show(parseInt(index));
        });
    });

    $fotoramaDiv.on('fotorama:show', function (e, fotorama, extra) {
        thumbBtns.forEach(b => b.classList.replace('border-amber-500', 'border-transparent'));
        if (thumbBtns[fotorama.activeIndex]) {
            thumbBtns[fotorama.activeIndex].classList.replace('border-transparent', 'border-amber-500');
            thumbBtns[fotorama.activeIndex].scrollIntoView({ behavior: 'smooth', block: 'nearest' });
        }
    });

    const container = document.getElementById('fotorama-container');
    const lens = document.getElementById('zoom-lens');
    const ZOOM_LEVEL = 2.5;

    if(container && lens) {
        container.addEventListener('mousemove', function(e) {
            if (e.target.closest('.fotorama__arr') || e.target.closest('.fotorama__fullscreen-icon')) {
                lens.classList.add('hidden');
                return;
            }
            if (window.matchMedia('(pointer: fine)').matches) {
                lens.classList.remove('hidden');
                const activeImg = fotorama.activeFrame.img;
                lens.style.backgroundImage = `url('${activeImg}')`;

                const rect = container.getBoundingClientRect();
                const x = e.clientX - rect.left;
                const y = e.clientY - rect.top;

                lens.style.left = (x - lens.offsetWidth / 2) + 'px';
                lens.style.top = (y - lens.offsetHeight / 2) + 'px';
                lens.style.backgroundPosition = `${(x / rect.width) * 100}% ${(y / rect.height) * 100}%`;
                lens.style.backgroundSize = (rect.width * ZOOM_LEVEL) + 'px ' + (rect.height * ZOOM_LEVEL) + 'px';
            }
        });
        container.addEventListener('mouseleave', function() {
            lens.classList.add('hidden');
        });
    }

    // --- Owl Carousel ---
    if ($('.related-carousel').length > 0) {
        $('.related-carousel').owlCarousel({
            loop: false, margin: 24, nav: true, dots: false,
            navText: [
                '<div class="w-10 h-10 flex items-center justify-center text-slate-600 hover:text-amber-600 bg-white transition-colors border border-slate-200 hover:border-amber-500 rounded-full shadow-sm"><svg xmlns="http://www.w3.org/2000/svg" class="w-5 h-5" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="m15 18-6-6 6-6"/></svg></div>',
                '<div class="w-10 h-10 flex items-center justify-center text-slate-600 hover:text-amber-600 bg-white transition-colors border border-slate-200 hover:border-amber-500 rounded-full shadow-sm"><svg xmlns="http://www.w3.org/2000/svg" class="w-5 h-5" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="m9 18 6-6-6-6"/></svg></div>'
            ],
            responsive: { 0: { items: 1 }, 640: { items: 2 }, 1024: { items: 3 }, 1280: { items: 4 } }
        });
    }

    // --- AJAX Cart ---
    function handleCartAction(btn, isBuyNow) {
        const productId = $('#btn-add-to-cart').data('id');
        const quantity = $('#product-qty').val();
        const size = $('input[name="selected_size"]:checked').val() || '';

        const originalHtml = btn.html();
        btn.prop('disabled', true).html('<svg class="animate-spin h-5 w-5 mr-2 inline-block" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24"><circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle><path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path></svg> Đang xử lý...');

        $.ajax({
            url: '<?= URLROOT ?>/client/Cart/add', type: 'POST',
            data: { productId: productId, quantity: quantity, size: size },
            dataType: 'json',
            success: function(res) {
                if (res.success) {
                    if (isBuyNow) {
                        window.location.href = '<?= URLROOT ?>/client/Cart';
                    } else {
                        btn.prop('disabled', false).html(originalHtml);
                        const cartBadge = $('a[aria-label="Giỏ hàng"] span');
                        if (cartBadge.length) cartBadge.text(res.totalItems);
                        else $('a[aria-label="Giỏ hàng"]').append(`<span class="absolute -top-1 -right-1 h-5 min-w-5 px-1 rounded-full bg-amber-600 text-white text-[10px] flex items-center justify-center font-bold">${res.totalItems}</span>`);
                        showToast(res.message, 'success');
                    }
                } else {
                    btn.prop('disabled', false).html(originalHtml);
                    showToast(res.message, 'error');
                }
            },
            error: function() {
                btn.prop('disabled', false).html(originalHtml);
                showToast('Có lỗi xảy ra.', 'error');
            }
        });
    }

    $('#btn-add-to-cart').on('click', function() { handleCartAction($(this), false); });
    $('#btn-buy-now').on('click', function() { handleCartAction($(this), true); });

    function showToast(message, type = 'success') {
        const bgColor = type === 'success' ? 'bg-emerald-500' : 'bg-red-500';
        const toast = $(`<div class="fixed bottom-4 right-4 ${bgColor} text-white px-6 py-3 rounded shadow-lg transform transition-all duration-300 translate-y-10 opacity-0 z-[100]">${message}</div>`);
        $('body').append(toast);
        setTimeout(() => toast.removeClass('translate-y-10 opacity-0'), 10);
        setTimeout(() => { toast.addClass('translate-y-10 opacity-0'); setTimeout(() => toast.remove(), 300); }, 3000);
    }
});
</script>

<style>
.related-carousel .owl-nav {
    position: absolute; top: 40%; transform: translateY(-50%); width: calc(100% + 4rem); left: -2rem; display: flex; justify-content: space-between; pointer-events: none; margin-top: 0 !important;
}
.related-carousel .owl-nav button { pointer-events: auto; background: transparent !important; }
.related-carousel .owl-stage { display: flex; }
.related-carousel .owl-item { display: flex; flex: 1 0 auto; }
.related-carousel .item { display: flex; flex-direction: column; width: 100%; }

/* CSS Trick cho Star Rating */
.star-rating input:checked ~ label,
.star-rating label:hover,
.star-rating label:hover ~ label { color: #fbbf24 !important; }
</style>