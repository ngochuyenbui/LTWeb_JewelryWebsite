<div class="bg-slate-50 py-12 min-h-screen">
    <div class="container mx-auto px-4 max-w-5xl">
        <div class="flex items-center justify-between mb-8">
            <h1 class="text-3xl font-serif font-bold text-slate-900">Lịch sử đơn hàng</h1>
            <a href="<?= URLROOT ?>/client/Products" class="text-amber-600 hover:text-amber-700 font-medium">Tiếp tục mua sắm &rarr;</a>
        </div>
        
        <?php if (isset($_SESSION['success'])): ?>
            <div class="bg-emerald-100 border border-emerald-400 text-emerald-700 px-4 py-3 rounded mb-6 font-medium shadow-sm">
                <?= htmlspecialchars($_SESSION['success']) ?>
                <?php unset($_SESSION['success']); ?>
            </div>
        <?php endif; ?>

        <?php if (empty($data['orders'])): ?>
            <div class="bg-white p-12 text-center rounded-lg shadow-sm border border-slate-200">
                <svg xmlns="http://www.w3.org/2000/svg" class="h-16 w-16 mx-auto text-slate-300 mb-4" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2" /></svg>
                <p class="text-slate-600 text-lg">Bạn chưa có đơn hàng nào.</p>
            </div>
        <?php else: ?>
            <div class="space-y-6 gap-5">
                <?php foreach ($data['orders'] as $order): 
                    $o_id = is_object($order) ? $order->orderId : $order['orderId'];
                    $o_date = is_object($order) ? $order->created_at : $order['created_at'];
                    $o_status = is_object($order) ? $order->status : $order['status'];
                    $items = is_object($order) ? $order->items : $order['items'];
                    
                    // Tính tổng tiền đơn hàng
                    $totalOrderPrice = 0;
                    foreach ($items as $item) {
                        $price = is_object($item) ? $item->purchase_price : $item['purchase_price'];
                        $qty = is_object($item) ? $item->quantity : $item['quantity'];
                        $totalOrderPrice += ($price * $qty);
                    }
                    $shippingFee = ($totalOrderPrice >= 5000000 || $totalOrderPrice == 0) ? 0 : 100000;
                    $finalTotal = $totalOrderPrice + $shippingFee;
                    
                    // Quy đổi điểm: 1.000.000đ = 10 điểm => 100.000đ = 1 điểm
                    $rewardPoints = floor($finalTotal / 100000);

                    // Dịch trạng thái
                    $statusColors = [
                        'pending' => 'bg-amber-100 text-amber-800',
                        'processing' => 'bg-blue-100 text-blue-800',
                        'shipping' => 'bg-indigo-100 text-indigo-800',
                        'delivered' => 'bg-emerald-100 text-emerald-800',
                        'cancelled' => 'bg-red-100 text-red-800'
                    ];
                    $statusLabels = [
                        'pending' => 'Chờ xác nhận', 'processing' => 'Đang xử lý', 
                        'shipping' => 'Đang giao', 'delivered' => 'Hoàn tất', 'cancelled' => 'Đã hủy'
                    ];
                ?>
                    <div class="bg-white rounded-lg shadow-sm border border-slate-200 overflow-hidden mt-5">
                        <div class="bg-slate-100/50 px-6 py-4 border-b border-slate-200 flex flex-wrap justify-between items-center gap-4 cursor-pointer hover:bg-slate-200/50 transition-colors" onclick="document.getElementById('order-details-<?= $o_id ?>').classList.toggle('hidden'); document.getElementById('icon-<?= $o_id ?>').classList.toggle('rotate-180');">
                            <div class="flex flex-wrap items-center gap-2 sm:gap-0">
                                <span class="text-slate-500 text-sm">Đơn hàng</span>
                                <span class="font-bold text-slate-900 ml-2 mr-2">#<?= str_pad($o_id, 5, '0', STR_PAD_LEFT) ?></span>
                                <span class="hidden sm:inline text-slate-300 text-sm mr-2">|</span>
                                <span class="text-slate-600 text-sm mr-2"><?= date('d/m/Y H:i', strtotime($o_date)) ?></span>
                                <span class="hidden sm:inline text-slate-300 text-sm mr-2">|</span>
                                <span class="text-amber-600 font-bold text-sm"><?= number_format($finalTotal, 0, ',', '.') ?> đ</span>
                            </div>
                            <div class="flex items-center gap-4">
                                <span class="px-3 py-1 rounded-full text-xs font-bold uppercase tracking-wider <?= $statusColors[$o_status] ?? 'bg-slate-100 text-slate-800' ?>">
                                    <?= $statusLabels[$o_status] ?? 'Không rõ' ?>
                                </span>
                                <svg id="icon-<?= $o_id ?>" xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 text-slate-400 transition-transform duration-200" viewBox="0 0 20 20" fill="currentColor">
                                    <path fill-rule="evenodd" d="M5.293 7.293a1 1 0 011.414 0L10 10.586l3.293-3.293a1 1 0 111.414 1.414l-4 4a1 1 0 01-1.414 0l-4-4a1 1 0 010-1.414z" clip-rule="evenodd" />
                                </svg>
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
                                    <div class="flex-1 min-w-0"><div class="font-medium text-slate-900 truncate"><?= htmlspecialchars($i_name) ?></div><div class="text-sm text-slate-500">Size: <?= htmlspecialchars($i_size) ?> x <?= $i_qty ?></div></div>
                                    <div class="font-medium text-slate-900"><?= number_format($i_price * $i_qty, 0, ',', '.') ?> đ</div>
                                </div>
                            <?php endforeach; ?>
                        </div>
                        <div class="bg-slate-50 px-6 py-4 border-t border-slate-200 flex flex-wrap justify-between items-center gap-4">
                            <div class="text-sm text-amber-600 font-medium flex items-center gap-1"><svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" viewBox="0 0 20 20" fill="currentColor"><path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z"/></svg> Điểm thưởng nhận được: +<?= $rewardPoints ?> điểm</div>
                            <div class="text-slate-600">Tổng thanh toán: <span class="text-xl font-bold text-amber-600 ml-2"><?= number_format($finalTotal, 0, ',', '.') ?> đ</span></div>
                        </div>
                        </div>
                    </div>
                <?php endforeach; ?>
            </div>
        <?php endif; ?>
    </div>
</div>