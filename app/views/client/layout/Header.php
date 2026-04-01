<?php
$navLinks = [
	['href' => '/', 'label' => 'Trang chủ'],
	['href' => '/about', 'label' => 'Giới thiệu'],
	['href' => '/products', 'label' => 'Sản phẩm'],
	['href' => '/pricing', 'label' => 'Bảng giá'],
	['href' => '/news', 'label' => 'Tin tức'],
	['href' => '/faq', 'label' => 'Hỏi đáp'],
	['href' => '/contact', 'label' => 'Liên hệ'],
];

$appBaseUrl = $appBaseUrl ?? '/';
$appBaseUrl = rtrim($appBaseUrl, '/');

$toUrl = static function (string $path) use ($appBaseUrl): string {
	if ($path === '/') {
		return $appBaseUrl === '' ? '/' : $appBaseUrl . '/';
	}
	return ($appBaseUrl === '' ? '' : $appBaseUrl) . $path;
};

$requestPath = parse_url($_SERVER['REQUEST_URI'] ?? '/', PHP_URL_PATH) ?: '/';
$normalizedPath = $requestPath;
if ($appBaseUrl !== '' && str_starts_with($normalizedPath, $appBaseUrl . '/')) {
	$normalizedPath = substr($normalizedPath, strlen($appBaseUrl));
} elseif ($appBaseUrl !== '' && $normalizedPath === $appBaseUrl) {
	$normalizedPath = '/';
}

$segments = array_values(array_filter(explode('/', trim($normalizedPath, '/'))));
$currentPath = empty($segments) ? '/' : '/' . $segments[0];

$cartItems = (int)($_SESSION['cart_total_items'] ?? 0);
?>

<header class="sticky top-0 z-50 border-b border-slate-200 bg-white/95 backdrop-blur-md">
	<style>
		.gold-text {
			background: linear-gradient(135deg, #f6e3a1 0%, #d9b461 45%, #b5852d 100%);
			-webkit-background-clip: text;
			background-clip: text;
			color: transparent;
		}
	</style>

	<div class="bg-slate-900 text-amber-50 text-xs py-1.5">
		<div class="container mx-auto px-4 flex justify-between items-center gap-4">
			<span class="flex items-center gap-1.5">
				<svg xmlns="http://www.w3.org/2000/svg" class="w-3 h-3" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true">
					<path d="M22 16.92v3a2 2 0 0 1-2.18 2 19.8 19.8 0 0 1-8.63-3.07 19.5 19.5 0 0 1-6-6A19.8 19.8 0 0 1 2.12 4.18 2 2 0 0 1 4.11 2h3a2 2 0 0 1 2 1.72c.12.89.35 1.76.68 2.59a2 2 0 0 1-.45 2.11L8.09 9.91a16 16 0 0 0 6 6l1.49-1.25a2 2 0 0 1 2.11-.45c.83.33 1.7.56 2.59.68A2 2 0 0 1 22 16.92z"></path>
				</svg>
				Hotline: 1900 xxxx
			</span>
			<span class="hidden sm:block">Miễn phí vận chuyển đơn hàng từ 5.000.000đ</span>
		</div>
	</div>

	<div class="container mx-auto px-4 flex items-center justify-between h-16 md:h-20">
		<a href="<?= htmlspecialchars($toUrl('/'), ENT_QUOTES, 'UTF-8') ?>" class="flex items-center gap-2">
			<span class="text-2xl md:text-3xl font-bold tracking-wide gold-text">AURELIA</span>
			<span class="hidden md:block text-[10px] uppercase tracking-[0.3em] text-slate-500">Fine Jewelry</span>
		</a>

		<nav class="hidden lg:flex items-center gap-8">
			<?php foreach ($navLinks as $link): ?>
				<?php $active = $currentPath === $link['href']; ?>
				<a
					href="<?= htmlspecialchars($toUrl($link['href']), ENT_QUOTES, 'UTF-8') ?>"
					class="text-sm font-medium tracking-wide transition-colors hover:text-amber-600 <?= $active ? 'text-amber-600' : 'text-slate-700/80' ?>"
				>
					<?= htmlspecialchars($link['label'], ENT_QUOTES, 'UTF-8') ?>
				</a>
			<?php endforeach; ?>
		</nav>

		<div class="flex items-center gap-3">
			<button type="button" class="hidden md:flex h-10 w-10 items-center justify-center rounded-md hover:bg-slate-100 transition-colors" aria-label="Tìm kiếm">
				<svg xmlns="http://www.w3.org/2000/svg" class="w-4 h-4" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true">
					<circle cx="11" cy="11" r="8"></circle>
					<path d="m21 21-4.3-4.3"></path>
				</svg>
			</button>

			<a href="<?= htmlspecialchars($toUrl('/login'), ENT_QUOTES, 'UTF-8') ?>" class="h-10 w-10 flex items-center justify-center rounded-md hover:bg-slate-100 transition-colors" aria-label="Đăng nhập">
				<svg xmlns="http://www.w3.org/2000/svg" class="w-4 h-4" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true">
					<path d="M19 21v-2a4 4 0 0 0-4-4H9a4 4 0 0 0-4 4v2"></path>
					<circle cx="12" cy="7" r="4"></circle>
				</svg>
			</a>

			<a href="<?= htmlspecialchars($toUrl('/cart'), ENT_QUOTES, 'UTF-8') ?>" class="relative h-10 w-10 flex items-center justify-center rounded-md hover:bg-slate-100 transition-colors" aria-label="Giỏ hàng">
				<svg xmlns="http://www.w3.org/2000/svg" class="w-4 h-4" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true">
					<path d="M6 2 3 6v14a2 2 0 0 0 2 2h14a2 2 0 0 0 2-2V6l-3-4Z"></path>
					<path d="M3 6h18"></path>
					<path d="M16 10a4 4 0 0 1-8 0"></path>
				</svg>
				<?php if ($cartItems > 0): ?>
					<span class="absolute -top-1 -right-1 h-5 min-w-5 px-1 rounded-full bg-amber-600 text-white text-[10px] flex items-center justify-center font-bold">
						<?= $cartItems ?>
					</span>
				<?php endif; ?>
			</a>

			<button type="button" class="lg:hidden h-10 w-10 flex items-center justify-center rounded-md hover:bg-slate-100 transition-colors" aria-label="Mở menu" data-mobile-toggle>
				<svg xmlns="http://www.w3.org/2000/svg" class="w-5 h-5 mobile-icon-open" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true">
					<line x1="4" x2="20" y1="12" y2="12"></line>
					<line x1="4" x2="20" y1="6" y2="6"></line>
					<line x1="4" x2="20" y1="18" y2="18"></line>
				</svg>
				<svg xmlns="http://www.w3.org/2000/svg" class="w-5 h-5 mobile-icon-close hidden" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true">
					<path d="M18 6 6 18"></path>
					<path d="m6 6 12 12"></path>
				</svg>
			</button>
		</div>
	</div>

	<div class="lg:hidden border-t border-slate-200 bg-white px-4 py-4 space-y-3 hidden" data-mobile-nav>
		<?php foreach ($navLinks as $link): ?>
			<?php $active = $currentPath === $link['href']; ?>
			<a
				href="<?= htmlspecialchars($toUrl($link['href']), ENT_QUOTES, 'UTF-8') ?>"
				class="block py-2 text-sm font-medium <?= $active ? 'text-amber-600' : 'text-slate-700/80' ?>"
			>
				<?= htmlspecialchars($link['label'], ENT_QUOTES, 'UTF-8') ?>
			</a>
		<?php endforeach; ?>
	</div>

	<script>
		(function () {
			const toggleButton = document.querySelector('[data-mobile-toggle]');
			const mobileNav = document.querySelector('[data-mobile-nav]');
			if (!toggleButton || !mobileNav) {
				return;
			}

			const openIcon = toggleButton.querySelector('.mobile-icon-open');
			const closeIcon = toggleButton.querySelector('.mobile-icon-close');

			toggleButton.addEventListener('click', function () {
				const isHidden = mobileNav.classList.contains('hidden');
				mobileNav.classList.toggle('hidden', !isHidden);
				if (openIcon && closeIcon) {
					openIcon.classList.toggle('hidden', isHidden);
					closeIcon.classList.toggle('hidden', !isHidden);
				}
			});
		})();
	</script>
</header>
