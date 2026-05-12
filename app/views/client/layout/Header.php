<?php
$content = $content ?? [];
$images = $images ?? [];
$siteBrand = (string)($content['site_brand_name'] ?? 'AURELIA');
$siteTagline = (string)($content['site_tagline'] ?? 'Fine Jewelry');
$sitePhone = (string)($content['site_phone'] ?? '1900 xxxx');

$navLinks = [
	['href' => '/', 'label' => 'Trang chủ'],
	['href' => '/about', 'label' => 'Giới thiệu'],
	['href' => '/client/Products', 'label' => 'Sản phẩm'],
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

$logoPath = isset($images['logo_main']) ? str_replace('\\', '/', trim((string)($images['logo_main']->filepath ?? ''))) : '';
$logoUrl = '';
if ($logoPath !== '') {
	$logoUrl = preg_match('#^https?://#i', $logoPath) ? $logoPath : $toUrl('/' . ltrim($logoPath, '/'));
}

$requestPath = parse_url($_SERVER['REQUEST_URI'] ?? '/', PHP_URL_PATH) ?: '/';
$normalizedPath = $requestPath;
if ($appBaseUrl !== '' && str_starts_with($normalizedPath, $appBaseUrl . '/')) {
	$normalizedPath = substr($normalizedPath, strlen($appBaseUrl));
} elseif ($appBaseUrl !== '' && $normalizedPath === $appBaseUrl) {
	$normalizedPath = '/';
}

$segments = array_values(array_filter(explode('/', trim($normalizedPath, '/'))));
if (empty($segments)) {
	$currentPath = '/';
} else {
	$currentPath = '/' . $segments[0];
	if (strtolower($segments[0]) === 'client' && isset($segments[1])) $currentPath .= '/' . $segments[1];
}

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
				Hotline: <?= htmlspecialchars($sitePhone, ENT_QUOTES, 'UTF-8') ?>
			</span>
			<span class="hidden sm:block">Miễn phí vận chuyển đơn hàng từ 5.000.000đ</span>
		</div>
	</div>

	<div class="container mx-auto px-4 flex items-center justify-between h-16 md:h-20">
		<a href="<?= htmlspecialchars($toUrl('/'), ENT_QUOTES, 'UTF-8') ?>" class="flex items-center gap-2">
			<?php if ($logoUrl !== ''): ?>
				<img src="<?= htmlspecialchars($logoUrl, ENT_QUOTES, 'UTF-8') ?>" alt="<?= htmlspecialchars($siteBrand, ENT_QUOTES, 'UTF-8') ?>" class="h-10 w-10 rounded-full object-cover">
			<?php endif; ?>
			<span class="text-2xl md:text-3xl font-bold tracking-wide gold-text"><?= htmlspecialchars($siteBrand, ENT_QUOTES, 'UTF-8') ?></span>
			<span class="hidden md:block text-[10px] uppercase tracking-[0.3em] text-slate-500"><?= htmlspecialchars($siteTagline, ENT_QUOTES, 'UTF-8') ?></span>
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
			<div class="relative">
				<?php if (isset($_SESSION['user_id'])): ?>
					<button type="button" class="h-10 w-10 flex items-center justify-center rounded-md hover:bg-slate-100 transition-colors" aria-label="Tài khoản" data-user-menu-btn>
						<svg xmlns="http://www.w3.org/2000/svg" class="w-4 h-4" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true">
							<path d="M19 21v-2a4 4 0 0 0-4-4H9a4 4 0 0 0-4 4v2"></path>
							<circle cx="12" cy="7" r="4"></circle>
						</svg>
					</button>
					<div class="absolute right-0 mt-2 w-48 bg-white rounded-md shadow-lg border border-slate-200 py-1 hidden" data-user-menu-dropdown>
						<div class="px-4 py-2 border-b border-slate-100 mb-1">
							<p class="text-sm font-medium text-slate-900 truncate">Xin chào, <?= htmlspecialchars($_SESSION['username'] ?? 'Bạn') ?></p>
						</div>
						<?php if (($_SESSION['user_role'] ?? $_SESSION['role'] ?? 'member') === 'admin'): ?>
							<a href="<?= htmlspecialchars($toUrl('/admin/Dashboard'), ENT_QUOTES, 'UTF-8') ?>" class="block px-4 py-2 text-sm text-slate-700 hover:bg-amber-50 hover:text-amber-600 transition-colors">Trang quản lý</a>
						<?php else: ?>
							<a href="<?= htmlspecialchars($toUrl('/client/Profile'), ENT_QUOTES, 'UTF-8') ?>" class="block px-4 py-2 text-sm text-slate-700 hover:bg-amber-50 hover:text-amber-600 transition-colors">Thông tin tài khoản</a>
						<?php endif; ?>
						<a href="<?= htmlspecialchars($toUrl('/Login/logout'), ENT_QUOTES, 'UTF-8') ?>" class="block px-4 py-2 text-sm text-red-600 hover:bg-red-50 transition-colors">Đăng xuất</a>
					</div>
				<?php else: ?>
					<a href="<?= htmlspecialchars($toUrl('/Login'), ENT_QUOTES, 'UTF-8') ?>" class="h-10 w-10 flex items-center justify-center rounded-md hover:bg-slate-100 transition-colors" aria-label="Tài khoản">
						<svg xmlns="http://www.w3.org/2000/svg" class="w-4 h-4" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true">
							<path d="M19 21v-2a4 4 0 0 0-4-4H9a4 4 0 0 0-4 4v2"></path>
							<circle cx="12" cy="7" r="4"></circle>
						</svg>
					</a>
				<?php endif; ?>
			</div>

			<a href="<?= htmlspecialchars($toUrl('/client/Cart'), ENT_QUOTES, 'UTF-8') ?>" class="relative h-10 w-10 flex items-center justify-center rounded-md hover:bg-slate-100 transition-colors" aria-label="Giỏ hàng">
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

			// Logic cho Dropdown Tài khoản
			const userMenuBtn = document.querySelector('[data-user-menu-btn]');
			const userMenuDropdown = document.querySelector('[data-user-menu-dropdown]');
			if (userMenuBtn && userMenuDropdown) {
				userMenuBtn.addEventListener('click', function(e) {
					e.stopPropagation();
					userMenuDropdown.classList.toggle('hidden');
				});
				document.addEventListener('click', function(e) {
					if (!userMenuBtn.contains(e.target) && !userMenuDropdown.contains(e.target)) {
						userMenuDropdown.classList.add('hidden');
					}
				});
			}
		})();
	</script>
</header>
