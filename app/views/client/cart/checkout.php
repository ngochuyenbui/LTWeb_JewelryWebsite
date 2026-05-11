<div class="bg-slate-50 py-12 min-h-screen">
    <div class="container mx-auto px-4 max-w-5xl">
        <?php if (!empty($data['successMessage'])): ?>
            <!-- Hiển thị khi đặt hàng thành công -->
            <div class="bg-white p-12 text-center rounded-lg shadow-sm border border-slate-200 mt-8 mb-12 max-w-2xl mx-auto py-6">
                <svg xmlns="http://www.w3.org/2000/svg" class="h-20 w-20 mx-auto text-emerald-500 mb-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z" />
                </svg>
                <h2 class="text-2xl font-bold text-slate-900 mb-4"><?= htmlspecialchars($data['successMessage']) ?></h2>
                <p class="text-slate-600 mb-8">Cảm ơn bạn đã tin tưởng và mua sắm tại AURELIA.</p>
                <a href="<?= URLROOT ?>/client/Profile?tab=orders" class="inline-block px-8 py-3 bg-amber-600 text-white font-medium rounded-md hover:bg-amber-700 transition-colors">Xem tất cả đơn hàng</a>
            </div>
        <?php else: ?>
            <h1 class="text-3xl font-serif font-bold text-slate-900 mb-8">Thanh toán</h1>
            
            <?php if (!empty($data['error'])): ?>
                <div class="bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded mb-6 font-medium shadow-sm">
                    <?= htmlspecialchars($data['error']) ?>
                </div>
            <?php endif; ?>

            <form action="<?= URLROOT ?>/client/Cart/checkout" method="POST" class="flex flex-col lg:flex-row -mx-4 items-start">
            <!-- Form thông tin -->
            <div class="w-full lg:w-7/12 px-4 mb-8 lg:mb-0 space-y-6">
                <div class="bg-white p-6 md:p-8 rounded-lg shadow-sm border border-slate-200">
                    <h2 class="text-xl font-bold text-slate-900 mb-6 flex items-center gap-2"><span class="bg-slate-900 text-white w-6 h-6 rounded-full flex items-center justify-center text-sm">1</span> Thông tin giao hàng</h2>
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-5">
                        <div class="md:col-span-2 pt-2">
                            <label class="block text-sm font-medium text-slate-700 mb-2">Họ và tên người nhận (*)</label>
                            <?php $u_name = is_object($data['userInfo'] ?? null) ? ($data['userInfo']->fullname ?? '') : (is_array($data['userInfo'] ?? null) ? ($data['userInfo']['fullname'] ?? '') : ($_SESSION['username'] ?? '')); ?>
                            <input type="text" name="fullname" class="w-full border border-slate-300 rounded p-3 focus:outline-none focus:ring-1 focus:ring-amber-500 focus:border-amber-500" value="<?= htmlspecialchars($u_name) ?>" required>
                        </div>
                        <div class="md:col-span-2 pt-2">
                            <label class="block text-sm font-medium text-slate-700 mb-2">Số điện thoại (*)</label>
                            <?php $u_phone = is_object($data['userInfo'] ?? null) ? ($data['userInfo']->phone ?? '') : (is_array($data['userInfo'] ?? null) ? ($data['userInfo']['phone'] ?? '') : ''); ?>
                            <input type="tel" name="phone" class="w-full border border-slate-300 rounded p-3 focus:outline-none focus:ring-1 focus:ring-amber-500 focus:border-amber-500" value="<?= htmlspecialchars($u_phone) ?>" required>
                        </div>
                        <div class="md:col-span-2 pt-2">
                            <label class="block text-sm font-medium text-slate-700 mb-2">Địa chỉ nhận hàng chi tiết (*)</label>
                            <textarea name="address" rows="3" class="w-full border border-slate-300 rounded p-3 focus:outline-none focus:ring-1 focus:ring-amber-500 focus:border-amber-500" placeholder="Số nhà, tên đường, phường/xã, quận/huyện, tỉnh/thành phố" required></textarea>
                        </div>
                    </div>
                </div>

                <div class="bg-white p-6 md:p-8 rounded-lg shadow-sm border border-slate-200">
                    <h2 class="text-xl font-bold text-slate-900 mb-6 flex items-center gap-2"><span class="bg-slate-900 text-white w-6 h-6 rounded-full flex items-center justify-center text-sm">2</span> Phương thức thanh toán</h2>
                    <div class="space-y-4">
                        <label class="flex items-center p-4 border border-slate-200 rounded cursor-pointer hover:bg-slate-50 transition-colors gap-6">
                            <input type="radio" name="payment_method" value="COD" class="text-amber-600 focus:ring-amber-500 w-4 h-4" checked>
                            <div class="ml-4">
                                <span class="block font-medium text-slate-900">Thanh toán khi nhận hàng (COD)</span>
                                <span class="text-sm text-slate-500">Trả tiền mặt khi nhân viên giao hàng tới</span>
                            </div>
                        </label>
                        <label class="flex items-center p-4 border border-slate-200 rounded cursor-pointer hover:bg-slate-50 transition-colors gap-6">
                            <input type="radio" name="payment_method" value="BANK" class="text-amber-600 focus:ring-amber-500 w-4 h-4">
                            <div class="ml-4">
                                <span class="block font-medium text-slate-900">Chuyển khoản ngân hàng</span>
                                <span class="text-sm text-slate-500">Thông tin tài khoản sẽ hiển thị sau khi hoàn tất đặt hàng</span>
                            </div>
                        </label>
                    </div>
                </div>
            </div>

            <!-- Tóm tắt đơn hàng bên phải -->
            <div class="w-full lg:w-5/12 px-4">
                <div class="bg-white p-6 rounded-lg shadow-sm border border-slate-200 sticky top-24">
                    <h2 class="text-lg font-bold text-slate-900 mb-4 border-b border-slate-200 pb-4">Đơn hàng của bạn</h2>
                    <div class="space-y-4 mb-6 max-h-72 overflow-y-auto pr-2 custom-scrollbar">
                        <?php foreach ($data['cartItems'] ?? [] as $item): ?>
                            <div class="flex justify-between items-start text-sm">
                                <div class="flex-1 pr-4 min-w-0">
                                    <div class="font-medium text-slate-900 line-clamp-2" title="<?= htmlspecialchars($item['name']) ?>"><?= htmlspecialchars($item['name']) ?></div>
                                    <div class="text-slate-500 mt-1">Size: <?= htmlspecialchars($item['size']) ?> <span class="mx-1 text-xs">x</span> <?= $item['quantity'] ?></div>
                                </div>
                                <div class="font-medium text-slate-900 shrink-0"><?= number_format($item['itemTotal'], 0, ',', '.') ?> đ</div>
                            </div>
                        <?php endforeach; ?>
                    </div>
                    
                    <div class="space-y-3 mb-6 border-t border-slate-200 pt-4 mt-4">
                        <div class="flex justify-between items-center text-sm text-slate-600">
                            <span>Tạm tính</span>
                            <span class="font-medium"><?= number_format($data['totalPrice'] ?? 0, 0, ',', '.') ?> đ</span>
                        </div>
                        <div class="flex justify-between items-center text-sm text-slate-600">
                            <span>Phí vận chuyển</span>
                            <?php if (($data['shippingFee'] ?? 0) == 0): ?>
                                <span class="font-medium text-emerald-600">Miễn phí</span>
                            <?php else: ?>
                                <span class="font-medium"><?= number_format($data['shippingFee'] ?? 0, 0, ',', '.') ?> đ</span>
                            <?php endif; ?>
                        </div>
                    </div>
                    
                    <div class="flex justify-between items-center mb-8 border-t border-slate-200 pt-4">
                        <span class="text-slate-900 font-bold">Tổng thanh toán</span>
                        <span class="text-2xl font-bold text-amber-600"><?= number_format($data['finalTotal'] ?? 0, 0, ',', '.') ?> đ</span>
                    </div>
                    
                    <button type="submit" class="w-full bg-amber-600 text-white font-bold py-4 rounded hover:bg-amber-700 transition-colors uppercase tracking-wide">Xác nhận đặt hàng</button>
                </div>
            </div>
        </form>
        <?php endif; ?>
    </div>
</div>