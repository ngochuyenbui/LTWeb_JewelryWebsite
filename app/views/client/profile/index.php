<div class="bg-slate-50 py-12 min-h-screen">
    <div class="container mx-auto px-4 max-w-6xl">
        <h1 class="text-3xl font-serif font-bold text-slate-900 mb-8">Tài khoản của bạn</h1>
        
        <?php if (isset($_SESSION['success'])): ?>
            <div class="bg-emerald-100 border border-emerald-400 text-emerald-700 px-4 py-3 rounded mb-6 font-medium shadow-sm">
                <?= htmlspecialchars($_SESSION['success']) ?>
                <?php unset($_SESSION['success']); ?>
            </div>
        <?php endif; ?>
        <?php if (isset($_SESSION['error'])): ?>
            <div class="bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded mb-6 font-medium shadow-sm">
                <?= htmlspecialchars($_SESSION['error']) ?>
                <?php unset($_SESSION['error']); ?>
            </div>
        <?php endif; ?>

        <div class="grid grid-cols-1 lg:grid-cols-5 gap-8 items-start">
            <!-- Sidebar / Tabs -->
            <div class="lg:col-span-1 mb-8 lg:mb-0">
                <div class="bg-white rounded-lg shadow-sm border border-slate-200 overflow-hidden sticky top-24">               
                <ul class="flex flex-col py-2 items-start">
                    <li><button type="button" class="w-full text-left px-6 py-3 text-slate-700 hover:bg-slate-50 hover:text-amber-600 font-medium transition-colors tab-btn" data-target="tab-profile">Thông tin tài khoản</button></li>
                    <li><button type="button" class="w-full text-left px-6 py-3 text-slate-700 hover:bg-slate-50 hover:text-amber-600 font-medium transition-colors tab-btn" data-target="tab-orders">Lịch sử đơn hàng</button></li>
                    <li><button type="button" class="w-full text-left px-6 py-3 text-slate-700 hover:bg-slate-50 hover:text-amber-600 font-medium transition-colors tab-btn" data-target="tab-password">Đổi mật khẩu</button></li>
                    <li><a href="<?= URLROOT ?>/Login/logout" class="block px-6 py-3 !text-red-600 !hover:bg-red-50 font-medium transition-colors">Đăng xuất</a></li>
                </ul>
                </div>
            </div>

            <!-- Content Area -->
            <div class="lg:col-span-4">
                <!-- Tab Profile -->
                <div id="tab-profile" class="tab-content hidden bg-white p-6 sm:p-8 rounded-lg shadow-sm border border-slate-200">
                    <h2 class="text-xl font-bold text-slate-900 mb-6 border-b border-slate-200 pb-4">Thông tin cá nhân</h2>
                    <div class="p-6 text-center border-b border-slate-200">
                    <div class="relative mx-auto group mb-3 rounded-full shadow-md border-4 border-white overflow-hidden" style="width: 100px; height: 100px; min-width: 100px; display: block;">
                        <?php 
                        $u = $data['user'] ?? null;
                        $avatar = is_object($u) ? ($u->avatar ?? '') : ($u['avatar'] ?? '');
                        $username = is_object($u) ? $u->username : $u['username'];
                        $avatarSrc = empty($avatar) ? "https://ui-avatars.com/api/?name=" . urlencode($username) . "&background=random&color=fff" : (strpos($avatar, 'http') === 0 ? $avatar : URLROOT . $avatar);
                        ?>
                        <img src="<?= htmlspecialchars($avatarSrc) ?>" alt="Avatar" style="width: 100%; height: 100%; object-fit: cover; object-position: center;">
                        <form action="<?= URLROOT ?>/client/Profile/updateAvatar" method="POST" enctype="multipart/form-data" class="absolute inset-0 flex items-center justify-center bg-black/50 opacity-0 group-hover:opacity-100 transition-opacity cursor-pointer">
                            <label for="avatar-upload" class="cursor-pointer text-white flex flex-col items-center w-full h-full justify-center" title="Đổi ảnh đại diện">
                                <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 9a2 2 0 012-2h.93a2 2 0 001.664-.89l.812-1.22A2 2 0 0110.07 4h3.86a2 2 0 011.664.89l.812 1.22A2 2 0 0018.07 7H19a2 2 0 012 2v9a2 2 0 01-2 2H5a2 2 0 01-2-2V9z" /><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 13a3 3 0 11-6 0 3 3 0 016 0z" /></svg>
                            </label>
                            <input type="file" id="avatar-upload" name="avatar" class="hidden" accept="image/*" onchange="this.form.submit()">
                        </form>
                    </div>
                    <h3 class="font-bold text-slate-900 text-lg"><?= htmlspecialchars($username) ?></h3>
                    <p class="text-sm text-amber-600 font-medium mt-1">Điểm thưởng: <?= is_object($u) ? ($u->rewardPoint ?? 0) : ($u['rewardPoint'] ?? 0) ?> điểm</p>
                </div>
                    <form action="<?= URLROOT ?>/client/Profile/update" method="POST" class="space-y-5">
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                            <div class="pt-2">
                                <label class="block text-sm font-medium text-slate-700 mb-2">Tên đăng nhập</label>
                                <input type="text" class="w-full border border-slate-200 bg-slate-50 rounded p-3 text-slate-500 cursor-not-allowed" value="<?= htmlspecialchars(is_object($u) ? $u->username : $u['username']) ?>" disabled>
                            </div>
                            <div class="pt-2">
                                <label class="block text-sm font-medium text-slate-700 mb-2">Email</label>
                                <input type="text" class="w-full border border-slate-200 bg-slate-50 rounded p-3 text-slate-500 cursor-not-allowed" value="<?= htmlspecialchars(is_object($u) ? $u->email : $u['email']) ?>" disabled>
                            </div>
                            <div>
                                <label class="block text-sm font-medium text-slate-700 mb-2">Họ và tên</label>
                                <input type="text" name="fullname" class="w-full border border-slate-300 rounded p-3 focus:outline-none focus:ring-1 focus:ring-amber-500 focus:border-amber-500" value="<?= htmlspecialchars(is_object($u) ? ($u->fullname ?? '') : ($u['fullname'] ?? '')) ?>">
                            </div>
                            <div>
                                <label class="block text-sm font-medium text-slate-700 mb-2">Số điện thoại</label>
                                <input type="tel" name="phone" class="w-full border border-slate-300 rounded p-3 focus:outline-none focus:ring-1 focus:ring-amber-500 focus:border-amber-500" value="<?= htmlspecialchars(is_object($u) ? ($u->phone ?? '') : ($u['phone'] ?? '')) ?>">
                            </div>
                            <div class="md:col-span-2">
                                <label class="block text-sm font-medium text-slate-700 mb-2">Địa chỉ mặc định</label>
                                <textarea name="address" rows="3" class="w-full border border-slate-300 rounded p-3 focus:outline-none focus:ring-1 focus:ring-amber-500 focus:border-amber-500"><?= htmlspecialchars(is_object($u) ? ($u->address ?? '') : ($u['address'] ?? '')) ?></textarea>
                            </div>
                        </div>
                        <div class="pt-4">
                            <button type="submit" class="bg-amber-600 text-white font-medium px-8 py-3 rounded hover:bg-amber-700 transition-colors">Lưu thay đổi</button>
                        </div>
                    </form>
                </div>

                <!-- Tab Orders -->
                <div id="tab-orders" class="tab-content hidden">
                    <div class="bg-white p-6 sm:p-8 rounded-lg shadow-sm border border-slate-200 mb-6">
                        <h2 class="text-xl font-bold text-slate-900 border-b border-slate-200 pb-4">Lịch sử mua hàng</h2>
                    </div>
                    <?php if (empty($data['orders'])): ?>
                        <div class="bg-white p-12 text-center rounded-lg shadow-sm border border-slate-200">
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-16 w-16 mx-auto text-slate-300 mb-4" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2" /></svg>
                            <p class="text-slate-600 text-lg">Bạn chưa có đơn hàng nào.</p>
                            <a href="<?= URLROOT ?>/client/Products" class="inline-block mt-4 text-amber-600 font-medium hover:underline">Bắt đầu mua sắm ngay</a>
                        </div>
                    <?php else: ?>
                        <div class="space-y-5">
                            <?php foreach ($data['orders'] as $order): 
                                $o_id = is_object($order) ? $order->orderId : $order['orderId'];
                                $o_date = is_object($order) ? $order->created_at : $order['created_at'];
                                $o_status = is_object($order) ? $order->status : $order['status'];
                                $items = is_object($order) ? $order->items : $order['items'];
                                
                                $totalOrderPrice = 0;
                                foreach ($items as $item) {
                                    $price = is_object($item) ? $item->purchase_price : $item['purchase_price'];
                                    $qty = is_object($item) ? $item->quantity : $item['quantity'];
                                    $totalOrderPrice += ($price * $qty);
                                }
                                $shippingFee = ($totalOrderPrice >= 5000000 || $totalOrderPrice == 0) ? 0 : 100000;
                                $finalTotal = $totalOrderPrice + $shippingFee;
                                $rewardPoints = floor($finalTotal / 100000);

                                $statusColors = ['pending' => 'bg-amber-100 text-amber-800', 'processing' => 'bg-blue-100 text-blue-800', 'shipping' => 'bg-indigo-100 text-indigo-800', 'delivered' => 'bg-emerald-100 text-emerald-800', 'cancelled' => 'bg-red-100 text-red-800'];
                                $statusLabels = ['pending' => 'Chờ xác nhận', 'processing' => 'Đang xử lý', 'shipping' => 'Đang giao', 'delivered' => 'Hoàn tất', 'cancelled' => 'Đã hủy'];
                            ?>
                                <div class="bg-white rounded-lg shadow-sm border border-slate-200 overflow-hidden mb-4">
                                    <div class=" bg-slate-100/50 px-6 py-4 border-b border-slate-200 flex flex-wrap justify-between items-center gap-4 cursor-pointer hover:bg-slate-200/50 transition-colors" onclick="document.getElementById('order-details-<?= $o_id ?>').classList.toggle('hidden'); document.getElementById('icon-<?= $o_id ?>').classList.toggle('rotate-180');">
                                        <div class="flex flex-wrap items-center gap-2 sm:gap-0">
                                            <span class="text-slate-500 text-sm">Đơn hàng</span>
                                            <span class="font-bold text-slate-900 ml-2 mr-2">#<?= str_pad($o_id, 5, '0', STR_PAD_LEFT) ?></span>
                                            <span class="hidden sm:inline text-slate-300 text-sm mr-2">|</span>
                                            <span class="text-slate-600 text-sm mr-2"><?= date('d/m/Y H:i', strtotime($o_date)) ?></span>
                                            <span class="hidden sm:inline text-slate-300 text-sm mr-2">|</span>
                                            <span class="text-amber-600 font-bold text-sm"><?= number_format($finalTotal, 0, ',', '.') ?> đ</span>
                                        </div>
                                        <div class="flex items-center gap-4">
                                            <span class="px-3 py-1 rounded-full text-xs font-bold uppercase tracking-wider <?= $statusColors[$o_status] ?? 'bg-slate-100 text-slate-800' ?>"><?= $statusLabels[$o_status] ?? 'Không rõ' ?></span>
                                            <svg id="icon-<?= $o_id ?>" xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 text-slate-400 transition-transform duration-200" viewBox="0 0 20 20" fill="currentColor"><path fill-rule="evenodd" d="M5.293 7.293a1 1 0 011.414 0L10 10.586l3.293-3.293a1 1 0 111.414 1.414l-4 4a1 1 0 01-1.414 0l-4-4a1 1 0 010-1.414z" clip-rule="evenodd" /></svg>
                                        </div>
                                    </div>
                                    <div id="order-details-<?= $o_id ?>" class="hidden">
                                        <div class="p-6 divide-y divide-slate-100">
                                            <?php foreach ($items as $item): 
                                                $i_name = is_object($item) ? $item->name : $item['name'];
                                                $i_img = is_object($item) ? $item->image_url : $item['image_url'];
                                                $i_size = is_object($item) ? $item->size : $item['size'];
                                                $i_price = is_object($item) ? $item->purchase_price : $item['purchase_price'];
                                                $i_qty = is_object($item) ? $item->quantity : $item['quantity'];
                                            ?>
                                                <div class="py-4 first:pt-0 last:pb-0 flex items-center gap-4">
                                                    <img src="<?= htmlspecialchars(strpos($i_img, 'http') === 0 ? $i_img : URLROOT . $i_img) ?>" class="w-16 h-16 object-contain border border-slate-200 rounded">
                                                    <div class="flex-1 min-w-0"><div class="font-medium text-slate-900 line-clamp-2"><?= htmlspecialchars($i_name) ?></div><div class="text-sm text-slate-500">Size: <?= htmlspecialchars($i_size) ?> x <?= $i_qty ?></div></div>
                                                    <div class="font-medium text-slate-900"><?= number_format($i_price * $i_qty, 0, ',', '.') ?> đ</div>
                                                </div>
                                            <?php endforeach; ?>
                                        </div>
                                        <div class="bg-slate-50 px-6 py-4 border-t border-slate-200 flex flex-wrap justify-between items-center gap-4">
                                            <div class="text-sm text-amber-600 font-medium flex items-center gap-1"><svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" viewBox="0 0 20 20" fill="currentColor"><path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z"/></svg> Điểm thưởng nhận được: +<?= $rewardPoints ?> điểm</div>
                                            <div class="text-slate-600">Tổng thanh toán: <span class="text-xl font-bold text-amber-600 ml-2"><?= number_format($finalTotal, 0, ',', '.') ?> đ</span></div>
                                        </div>
                                        
                                        <?php if (in_array($o_status, ['pending', 'processing'])): ?>
                                            <div class="bg-slate-50 px-6 py-4 border-t border-slate-200 flex justify-end">
                                                <form action="<?= URLROOT ?>/client/Profile/cancelOrder/<?= $o_id ?>" method="POST" onsubmit="return confirm('Bạn có chắc chắn muốn hủy đơn hàng này không?');">
                                                    <button type="submit" class="inline-flex items-center gap-2 px-4 py-2 bg-white border border-red-200 text-red-600 hover:bg-red-50 hover:border-red-300 rounded-md font-medium text-sm transition-colors">
                                                        <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" viewBox="0 0 20 20" fill="currentColor"><path fill-rule="evenodd" d="M4.293 4.293a1 1 0 011.414 0L10 8.586l4.293-4.293a1 1 0 111.414 1.414L11.414 10l4.293 4.293a1 1 0 01-1.414 1.414L10 11.414l-4.293 4.293a1 1 0 01-1.414-1.414L8.586 10 4.293 5.707a1 1 0 010-1.414z" clip-rule="evenodd" /></svg>
                                                        Hủy đơn hàng
                                                    </button>
                                                </form>
                                            </div>
                                        <?php endif; ?>
                                    </div>
                                </div>
                            <?php endforeach; ?>
                        </div>

                        <?php if (($data['totalPages'] ?? 0) > 1): ?>
                            <div class="mt-8 flex justify-center">
                                <nav class="relative z-0 inline-flex rounded-md shadow-sm -space-x-px" aria-label="Pagination">
                                    <!-- Previous Button -->
                                    <a href="?tab=orders&page=<?= max(1, $data['currentPage'] - 1) ?>" 
                                    class="relative inline-flex items-center px-2 py-2 rounded-l-md border border-slate-300 bg-white text-sm font-medium text-slate-500 hover:bg-slate-50 <?= ($data['currentPage'] <= 1) ? 'pointer-events-none opacity-50' : '' ?>">
                                        <span class="sr-only">Trước</span>
                                        <svg class="h-5 w-5" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor" aria-hidden="true"><path fill-rule="evenodd" d="M12.707 5.293a1 1 0 010 1.414L9.414 10l3.293 3.293a1 1 0 01-1.414 1.414l-4-4a1 1 0 010-1.414l4-4a1 1 0 011.414 0z" clip-rule="evenodd" /></svg>
                                    </a>
                                    
                                    <?php for ($i = 1; $i <= $data['totalPages']; $i++): ?>
                                        <a href="?tab=orders&page=<?= $i ?>" aria-current="page" class="relative inline-flex items-center px-4 py-2 border text-sm font-medium <?= ($i == $data['currentPage']) ? 'z-10 bg-amber-50 border-amber-500 text-amber-600' : 'bg-white border-slate-300 text-slate-500 hover:bg-slate-50' ?>">
                                            <?= $i ?>
                                        </a>
                                    <?php endfor; ?>

                                    <!-- Next Button -->
                                    <a href="?tab=orders&page=<?= min($data['totalPages'], $data['currentPage'] + 1) ?>" class="relative inline-flex items-center px-2 py-2 rounded-r-md border border-slate-300 bg-white text-sm font-medium text-slate-500 hover:bg-slate-50 <?= ($data['currentPage'] >= $data['totalPages']) ? 'pointer-events-none opacity-50' : '' ?>">
                                        <span class="sr-only">Sau</span>
                                        <svg class="h-5 w-5" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor" aria-hidden="true"><path fill-rule="evenodd" d="M7.293 14.707a1 1 0 010-1.414L10.586 10 7.293 6.707a1 1 0 011.414-1.414l4 4a1 1 0 010 1.414l-4 4a1 1 0 01-1.414 0z" clip-rule="evenodd" /></svg>
                                    </a>
                                </nav>
                            </div>
                        <?php endif; ?>
                    <?php endif; ?>
                </div>

                <!-- Tab Password -->
                <div id="tab-password" class="tab-content hidden bg-white p-6 sm:p-8 rounded-lg shadow-sm border border-slate-200">
                    <h2 class="text-xl font-bold text-slate-900 mb-6 border-b border-slate-200 pb-4">Đổi mật khẩu</h2>
                    <form action="<?= URLROOT ?>/client/Profile/updatePassword" method="POST" class="space-y-5 max-w-md">
                        <div class="pt-2"><label class="block text-sm font-medium text-slate-700 mb-2">Mật khẩu hiện tại</label><input type="password" name="current_password" required class="w-full border border-slate-300 rounded p-3 focus:outline-none focus:ring-1 focus:ring-amber-500 focus:border-amber-500"></div>
                        <div class="pt-2"><label class="block text-sm font-medium text-slate-700 mb-2">Mật khẩu mới</label><input type="password" name="new_password" required minlength="6" class="w-full border border-slate-300 rounded p-3 focus:outline-none focus:ring-1 focus:ring-amber-500 focus:border-amber-500"></div>
                        <div class="pt-2"><label class="block text-sm font-medium text-slate-700 mb-2">Xác nhận mật khẩu mới</label><input type="password" name="confirm_password" required minlength="6" class="w-full border border-slate-300 rounded p-3 focus:outline-none focus:ring-1 focus:ring-amber-500 focus:border-amber-500"></div>
                        <div class="pt-6"><button type="submit" class="bg-slate-900 text-white font-medium px-8 py-3 rounded hover:bg-amber-600 transition-colors">Cập nhật mật khẩu</button></div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>

<script>
document.addEventListener('DOMContentLoaded', function() {
    const tabs = document.querySelectorAll('.tab-btn');
    const contents = document.querySelectorAll('.tab-content');
    const urlParams = new URLSearchParams(window.location.search);
    let activeTab = urlParams.get('tab') ? 'tab-' + urlParams.get('tab') : 'tab-profile';

    function activateTab(tabId) {
        let found = false;
        tabs.forEach(t => {
            if (t.dataset.target === tabId) {
                t.classList.add('text-amber-600', 'bg-amber-50', 'border-l-4', 'border-amber-600');
                t.classList.remove('text-slate-700'); found = true;
            } else {
                t.classList.remove('text-amber-600', 'bg-amber-50', 'border-l-4', 'border-amber-600');
                t.classList.add('text-slate-700');
            }
        });
        if (!found) tabId = 'tab-profile';
        contents.forEach(c => c.classList.toggle('hidden', c.id !== tabId));
    }

    activateTab(activeTab);

    tabs.forEach(tab => {
        tab.addEventListener('click', function() {
            activateTab(this.dataset.target);
            const url = new URL(window.location);
            url.searchParams.set('tab', this.dataset.target.replace('tab-', ''));
            window.history.pushState({}, '', url);
        });
    });
});
</script>