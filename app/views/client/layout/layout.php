<?php
if (!isset($contentView) || !file_exists($contentView)) {
	die('Không tìm thấy nội dung trang để hiển thị.');
}

$scriptName = str_replace('\\', '/', $_SERVER['SCRIPT_NAME'] ?? '');
$publicBaseUrl = rtrim(str_replace('\\', '/', dirname($scriptName)), '/');
$publicBaseUrl = $publicBaseUrl === '' ? '/' : $publicBaseUrl;

$appBaseUrl = preg_replace('#/public$#', '', $publicBaseUrl);
$appBaseUrl = $appBaseUrl === '' ? '/' : $appBaseUrl;

$assetBaseUrl = $appBaseUrl === '/' ? '' : $appBaseUrl;
?>
<!DOCTYPE html>
<html lang="vi">

<head>
	<meta charset="UTF-8">
	<meta name="viewport" content="width=device-width, initial-scale=1.0">
	<title>AURELIA</title>
	<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.7.1/jquery.min.js"></script>
	<link rel="stylesheet" href="<?= htmlspecialchars($assetBaseUrl . '/assets/css/output.css', ENT_QUOTES, 'UTF-8') ?>">
	<!-- Owl Carousel CSS -->
	<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/OwlCarousel2/2.3.4/assets/owl.carousel.min.css">
	<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/OwlCarousel2/2.3.4/assets/owl.theme.default.min.css">
	<!-- Google Fonts -->
	<link rel="preconnect" href="https://fonts.googleapis.com">
	<link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
	<link href="https://fonts.googleapis.com/css2?family=Montserrat:wght@300;400;500;600;700&family=Playfair+Display:ital,wght@0,400;0,500;0,600;0,700;1,400&display=swap" rel="stylesheet">
	<!-- jQuery UI -->
	<link rel="stylesheet" href="https://code.jquery.com/ui/1.13.2/themes/base/jquery-ui.css">
</head>

<body class="font-sans min-h-screen flex flex-col bg-white text-slate-900">
	<?php require_once __DIR__ . '/Header.php'; ?>

	<main class="flex-1">
		<?php require $contentView; ?>
	</main>

	<?php require_once __DIR__ . '/Footer.php'; ?>
	
	<!-- Owl Carousel JS -->
	<script src="https://cdnjs.cloudflare.com/ajax/libs/OwlCarousel2/2.3.4/owl.carousel.min.js"></script>
	<script src="https://code.jquery.com/ui/1.13.2/jquery-ui.min.js"></script>
</body>

</html>
