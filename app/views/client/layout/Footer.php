<?php
$footerLinks = [
	['href' => '/products', 'label' => 'Bộ sưu tập'],
	['href' => '/about', 'label' => 'Về chúng tôi'],
	['href' => '/pricing', 'label' => 'Bảng giá vàng'],
	['href' => '/news', 'label' => 'Tin tức'],
	['href' => '/faq', 'label' => 'Câu hỏi thường gặp'],
];

$appBaseUrl = $appBaseUrl ?? '/';
$appBaseUrl = rtrim($appBaseUrl, '/');

$toUrl = static function (string $path) use ($appBaseUrl): string {
	if ($path === '/') {
		return $appBaseUrl === '' ? '/' : $appBaseUrl . '/';
	}
	return ($appBaseUrl === '' ? '' : $appBaseUrl) . $path;
};
?>

<footer class="bg-slate-900 text-amber-50/80">
	<div class="container mx-auto px-4 py-12 md:py-16">
		<div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-10">
			<div>
				<h3 class="text-2xl font-bold text-amber-50 mb-4 tracking-wide">AURELIA</h3>
				<p class="text-sm leading-relaxed text-amber-50/60">
					Thương hiệu trang sức vàng bạc cao cấp, mang đến vẻ đẹp vĩnh cửu cho mỗi khoảnh khắc đặc biệt trong cuộc sống.
				</p>
			</div>

			<div>
				<h4 class="font-semibold text-amber-50 mb-4">Khám phá</h4>
				<div class="space-y-2">
					<?php foreach ($footerLinks as $link): ?>
						<a
							href="<?= htmlspecialchars($toUrl($link['href']), ENT_QUOTES, 'UTF-8') ?>"
							class="block text-sm text-amber-50/60 hover:text-amber-400 transition-colors"
						>
							<?= htmlspecialchars($link['label'], ENT_QUOTES, 'UTF-8') ?>
						</a>
					<?php endforeach; ?>
				</div>
			</div>

			<div>
				<h4 class="font-semibold text-amber-50 mb-4">Liên hệ</h4>
				<div class="space-y-3 text-sm text-amber-50/60">
					<p class="flex items-start gap-2">
						<svg xmlns="http://www.w3.org/2000/svg" class="w-4 h-4 mt-0.5 shrink-0 text-amber-400" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true">
							<path d="M20 10c0 4.993-5.539 10.193-7.399 11.799a1 1 0 0 1-1.202 0C9.539 20.193 4 14.993 4 10a8 8 0 1 1 16 0"></path>
							<circle cx="12" cy="10" r="3"></circle>
						</svg>
						123 Đường Lê Lợi, Quận 1, TP. Hồ Chí Minh
					</p>
					<p class="flex items-center gap-2">
						<svg xmlns="http://www.w3.org/2000/svg" class="w-4 h-4 shrink-0 text-amber-400" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true">
							<path d="M22 16.92v3a2 2 0 0 1-2.18 2 19.8 19.8 0 0 1-8.63-3.07 19.5 19.5 0 0 1-6-6A19.8 19.8 0 0 1 2.12 4.18 2 2 0 0 1 4.11 2h3a2 2 0 0 1 2 1.72c.12.89.35 1.76.68 2.59a2 2 0 0 1-.45 2.11L8.09 9.91a16 16 0 0 0 6 6l1.49-1.25a2 2 0 0 1 2.11-.45c.83.33 1.7.56 2.59.68A2 2 0 0 1 22 16.92z"></path>
						</svg>
						1900 xxxx
					</p>
					<p class="flex items-center gap-2">
						<svg xmlns="http://www.w3.org/2000/svg" class="w-4 h-4 shrink-0 text-amber-400" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true">
							<rect width="20" height="16" x="2" y="4" rx="2"></rect>
							<path d="m22 7-8.97 5.7a2 2 0 0 1-2.06 0L2 7"></path>
						</svg>
						contact@aurelia.vn
					</p>
					<p class="flex items-center gap-2">
						<svg xmlns="http://www.w3.org/2000/svg" class="w-4 h-4 shrink-0 text-amber-400" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true">
							<circle cx="12" cy="12" r="10"></circle>
							<polyline points="12 6 12 12 16 14"></polyline>
						</svg>
						8:00 - 21:00, T2 - CN
					</p>
				</div>
			</div>

			<div>
				<h4 class="font-semibold text-amber-50 mb-4">Nhận tin mới</h4>
				<p class="text-sm text-amber-50/60 mb-3">
					Đăng ký để nhận ưu đãi độc quyền và tin tức mới nhất.
				</p>
				<form class="flex gap-2" action="#" method="post" onsubmit="return false;">
					<input
						type="email"
						placeholder="Email của bạn"
						class="flex-1 px-3 py-2 text-sm bg-white/10 border border-white/20 rounded text-amber-50 placeholder:text-amber-50/40 focus:outline-none focus:border-amber-400"
					/>
					<button type="submit" class="px-4 py-2 text-sm font-medium text-slate-900 rounded bg-gradient-to-r from-amber-200 via-amber-400 to-amber-600 hover:opacity-90 transition-opacity">
						Gửi
					</button>
				</form>
			</div>
		</div>

		<div class="border-t border-amber-50/10 mt-10 pt-6 flex flex-col md:flex-row justify-between items-center gap-4 text-xs text-amber-50/40">
			<p>&copy; 2024 AURELIA Fine Jewelry. All rights reserved.</p>
			<div class="flex gap-4">
				<span>Chính sách bảo mật</span>
				<span>Điều khoản sử dụng</span>
				<span>Chính sách đổi trả</span>
			</div>
		</div>
	</div>
</footer>
