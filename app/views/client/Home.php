<?php
$appBaseUrl = $appBaseUrl ?? '/';
$appBaseUrl = rtrim($appBaseUrl, '/');

$toUrl = static function (string $path) use ($appBaseUrl): string {
	if ($path === '/') {
		return $appBaseUrl === '' ? '/' : $appBaseUrl . '/';
	}
	return ($appBaseUrl === '' ? '' : $appBaseUrl) . $path;
};
?>

<section class="relative overflow-hidden bg-gradient-to-br from-amber-50 via-white to-slate-100">
	<div class="container mx-auto px-4 py-16 md:py-24">
		<div class="max-w-3xl">
			<p class="inline-flex items-center rounded-full bg-amber-100 text-amber-800 text-xs font-semibold px-3 py-1 mb-4">
				Bộ sưu tập mới 2026
			</p>
			<h1 class="text-3xl md:text-5xl font-bold tracking-tight text-slate-900 leading-tight">
				Tinh hoa trang sức cho những khoảnh khắc đáng nhớ
			</h1>
			<p class="mt-5 text-slate-600 text-base md:text-lg leading-relaxed">
				Đây là trang chủ mẫu để kiểm tra luồng layout dùng chung. Nếu bạn đang nhìn thấy phần Header phía trên và Footer phía dưới, nghĩa là hệ thống đã ghép layout thành công.
			</p>
			<div class="mt-8 flex flex-wrap gap-3">
				<a href="<?= htmlspecialchars($toUrl('/products'), ENT_QUOTES, 'UTF-8') ?>" class="px-5 py-3 rounded-md text-sm font-semibold text-slate-900 bg-gradient-to-r from-amber-200 via-amber-400 to-amber-600 hover:opacity-90 transition-opacity">
					Khám phá sản phẩm
				</a>
				<a href="<?= htmlspecialchars($toUrl('/about'), ENT_QUOTES, 'UTF-8') ?>" class="px-5 py-3 rounded-md text-sm font-semibold text-slate-700 bg-white border border-slate-300 hover:bg-slate-50 transition-colors">
					Tìm hiểu thương hiệu
				</a>
			</div>
		</div>
	</div>
</section>

<section class="container mx-auto px-4 py-12 md:py-16">
	<div class="grid grid-cols-1 md:grid-cols-3 gap-6">
		<article class="rounded-xl border border-slate-200 bg-white p-6">
			<h2 class="text-lg font-semibold text-slate-900">Thiết kế thủ công</h2>
			<p class="mt-2 text-sm text-slate-600">Mỗi mẫu được hoàn thiện tỉ mỉ bởi đội ngũ nghệ nhân lành nghề.</p>
		</article>
		<article class="rounded-xl border border-slate-200 bg-white p-6">
			<h2 class="text-lg font-semibold text-slate-900">Chất liệu cao cấp</h2>
			<p class="mt-2 text-sm text-slate-600">Cam kết vàng, bạc và đá quý đạt tiêu chuẩn kiểm định minh bạch.</p>
		</article>
		<article class="rounded-xl border border-slate-200 bg-white p-6">
			<h2 class="text-lg font-semibold text-slate-900">Bảo hành uy tín</h2>
			<p class="mt-2 text-sm text-slate-600">Hỗ trợ bảo hành, đánh bóng và vệ sinh định kỳ sau khi mua hàng.</p>
		</article>
	</div>
</section>
