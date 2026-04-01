<?php
if (!isset($contentView) || !file_exists($contentView)) {
	die('Không tìm thấy nội dung trang để hiển thị.');
}

$scriptName = str_replace('\\', '/', $_SERVER['SCRIPT_NAME'] ?? '');
$publicBaseUrl = rtrim(dirname($scriptName), '/');
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
	<link rel="stylesheet" href="<?= htmlspecialchars($assetBaseUrl . '/assets/css/output.css', ENT_QUOTES, 'UTF-8') ?>">
</head>

<body class="min-h-screen flex flex-col bg-white text-slate-900">
	<?php require_once __DIR__ . '/Header.php'; ?>

	<main class="flex-1">
		<?php require $contentView; ?>
	</main>

	<?php require_once __DIR__ . '/Footer.php'; ?>
</body>

</html>
