<div class="bg-slate-50 py-12 min-h-screen">
    <div class="container mx-auto px-4 max-w-6xl">
        <h1 class="text-3xl font-serif font-bold text-slate-900 mb-8">Giỏ hàng của bạn</h1>
        
        <?php if (empty($data['cartItems'])): ?>
            <div class="py-6 bg-white p-12 text-center rounded-lg shadow-sm border border-slate-200">
                <svg xmlns="http://www.w3.org/2000/svg" class="h-16 w-16 mx-auto text-slate-300 mb-4" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M3 3h2l.4 2M7 13h10l4-8H5.4M7 13L5.4 5M7 13l-2.293 2.293c-.63.63-.184 1.707.707 1.707H17m0 0a2 2 0 100 4 2 2 0 000-4zm-8 2a2 2 0 11-4 0 2 2 0 014 0z" /></svg>
                <p class="text-slate-600 mb-6 text-lg">Giỏ hàng của bạn hiện đang trống.</p>
                <a href="<?= URLROOT ?>/client/Products" class="inline-block bg-amber-600 text-white font-medium px-8 py-3 rounded hover:bg-amber-700 transition-colors uppercase tracking-wide">Tiếp tục mua sắm</a>
            </div>
        <?php else: ?>
            <div class="flex flex-col lg:flex-row -mx-4 items-start lg:flex-nowrap">
                <!-- Danh sách sản phẩm -->
                <div class="w-full lg:w-3/12 px-4 mb-8 lg:mb-0 flex-none">
                    <div class="bg-white rounded-lg shadow-sm border border-slate-200 overflow-hidden">
                    <ul class="divide-y divide-slate-200">
                        <?php foreach ($data['cartItems'] as $item): ?>
                            <li class="p-6 flex flex-col sm:flex-row items-center gap-6">
                                <a href="<?= URLROOT ?>/client/Products/detail/<?= $item['productId'] ?>" class="border border-slate-200 rounded overflow-hidden" style="width: 100px; height: 100px; min-width: 100px; flex-shrink: 0; display: block;">
                                    <img src="<?= htmlspecialchars(strpos($item['image'], 'http') === 0 ? $item['image'] : URLROOT . $item['image']) ?>" alt="<?= htmlspecialchars($item['name']) ?>" style="width: 100%; height: 100%; object-fit: contain;">
                                </a>
                                <div class="flex-1 text-left sm:text-left min-w-0">
                                    <a href="<?= URLROOT ?>/client/Products/detail/<?= $item['productId'] ?>" class="font-medium text-slate-900 hover:text-amber-600 transition-colors block mb-1 line-clamp-2" title="<?= htmlspecialchars($item['name']) ?>"><?= htmlspecialchars($item['name']) ?></a>
                                    <div class="text-sm text-slate-500 mb-2">Size: <?= htmlspecialchars($item['size']) ?></div>
                                    <div class="font-bold text-amber-600"><?= number_format($item['price'], 0, ',', '.') ?> đ</div>
                                </div>
                                <div class="flex items-center gap-4">
                                    <div class="flex items-center border border-slate-300 rounded h-10 w-24">
                                        <button type="button" class="px-5 w-8 h-full flex justify-center items-center text-slate-500 hover:bg-slate-50 transition-colors btn-decrease" data-key="<?= $item['key'] ?>">-</button>
                                        <input type="text" class="w-10 h-full text-center border-none p-0 text-sm font-medium focus:ring-0 qty-input" value="<?= $item['quantity'] ?>" readonly data-key="<?= $item['key'] ?>" data-max="<?= $item['stock'] ?>">
                                        <button type="button" class="px-5 w-8 h-full flex justify-center items-center text-slate-500 hover:bg-slate-50 transition-colors btn-increase" data-key="<?= $item['key'] ?>">+</button>
                                    </div>
                                    <button type="button" class="text-slate-400 hover:text-red-500 transition-colors p-2 btn-remove" data-key="<?= $item['key'] ?>" title="Xóa">
                                        <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" viewBox="0 0 20 20" fill="currentColor"><path fill-rule="evenodd" d="M9 2a1 1 0 00-.894.553L7.382 4H4a1 1 0 000 2v10a2 2 0 002 2h8a2 2 0 002-2V6a1 1 0 100-2h-3.382l-.724-1.447A1 1 0 0011 2H9zM7 8a1 1 0 012 0v6a1 1 0 11-2 0V8zm5-1a1 1 0 00-1 1v6a1 1 0 102 0V8a1 1 0 00-1-1z" clip-rule="evenodd" /></svg>
                                    </button>
                                </div>
                            </li>
                        <?php endforeach; ?>
                    </ul>
                    </div>
                </div>

                <!-- Tóm tắt đơn hàng -->
                <div class="w-full lg:w-5/12 px-4 flex-none">
                    <div class="bg-white p-6 rounded-lg shadow-sm border border-slate-200 sticky top-24">
                        <h2 class="text-lg font-bold text-slate-900 mb-6 border-b border-slate-200 pb-4">Tóm tắt đơn hàng</h2>
                        <div class="flex justify-between items-center mb-4 text-slate-600">
                            <span>Tạm tính</span>
                            <span class="font-medium"><?= number_format($data['totalPrice'], 0, ',', '.') ?> đ</span>
                        </div>
                        <div class="flex justify-between items-center mb-6 text-slate-600">
                            <span>Phí vận chuyển</span>
                            <?php if ($data['shippingFee'] == 0): ?>
                                <span class="font-medium text-emerald-600">Miễn phí</span>
                            <?php else: ?>
                                <span class="font-medium"><?= number_format($data['shippingFee'], 0, ',', '.') ?> đ</span>
                            <?php endif; ?>
                        </div>
                        <div class="flex justify-between items-center mb-8 border-t border-slate-200 pt-4 gap-12">
                            <span class="text-slate-900 font-bold">Tổng tiền</span>
                            <span class="text-2xl font-bold text-amber-600"><?= number_format($data['finalTotal'], 0, ',', '.') ?> đ</span>
                        </div>
                        
                        <a href="<?= URLROOT ?>/client/Cart/checkout" class="block w-full bg-slate-900 text-white text-center font-bold py-4 px-2 rounded hover:bg-amber-600 transition-colors uppercase tracking-wide">
                            Tiến hành thanh toán
                        </a>
                    </div>
                </div>
            </div>
        <?php endif; ?>
    </div>
</div>

<script>
document.addEventListener('DOMContentLoaded', function() {
    function updateCartItem(key, quantity) {
        $.ajax({
            url: '<?= URLROOT ?>/client/Cart/update', type: 'POST',
            data: { key: key, quantity: quantity }, dataType: 'json',
            success: function(res) {
                if (res.success) location.reload();
                else alert(res.message || 'Lỗi cập nhật số lượng.');
            }
        });
    }

    $('.btn-increase').on('click', function() {
        let input = $(this).siblings('.qty-input');
        let currentVal = parseInt(input.val());
        let maxVal = parseInt(input.data('max'));
        if (currentVal < maxVal) updateCartItem(input.data('key'), currentVal + 1);
        else alert('Đã đạt số lượng tồn kho tối đa.');
    });

    $('.btn-decrease').on('click', function() {
        let input = $(this).siblings('.qty-input');
        let currentVal = parseInt(input.val());
        if (currentVal > 1) updateCartItem(input.data('key'), currentVal - 1);
    });

    $('.btn-remove').on('click', function() {
        if (confirm('Bạn có chắc muốn xóa sản phẩm này khỏi giỏ hàng?')) {
            $.ajax({
                url: '<?= URLROOT ?>/client/Cart/remove', type: 'POST',
                data: { key: $(this).data('key') }, dataType: 'json',
                success: function(res) { if (res.success) location.reload(); }
            });
        }
    });
});
</script>