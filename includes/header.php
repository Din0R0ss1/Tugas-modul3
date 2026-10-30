<?php
$pageTitle = $pageTitle ?? 'Overview';
$activePage = $activePage ?? '';
$notice = get_flash();
?>
<!doctype html><html lang="en"><head>
<meta charset="utf-8"><meta name="viewport" content="width=device-width,initial-scale=1">
<title><?= e($pageTitle) ?> · StockWise</title>
<link rel="preconnect" href="https://fonts.googleapis.com"><link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
<link href="https://fonts.googleapis.com/css2?family=DM+Sans:wght@400;500;600;700&family=Manrope:wght@600;700;800&display=swap" rel="stylesheet">
<link rel="stylesheet" href="assets/css/style.css"></head><body>
<div class="shell"><aside class="sidebar">
<a class="brand" href="index.php"><span class="brand-icon">S</span><span>stockwise<small>INVENTORY CONTROL</small></span></a>
<div class="nav-caption">WORKSPACE</div><nav>
<a class="<?= $activePage==='dashboard'?'active':'' ?>" href="index.php"><b>▦</b> Overview</a>
<a href="index.php#inventory"><b>▤</b> Inventory</a><a class="<?= $activePage==='create'?'active':'' ?>" href="create.php"><b>＋</b> Add product</a>
</nav><div class="sidebar-bottom"><div class="help"><b>✦ Need a hand?</b><p>Keep your inventory organized and up to date.</p><a href="create.php">Add your first item →</a></div><div class="admin"><span>SW</span><div><b>Workspace Admin</b><small>Administrator</small></div></div></div>
</aside><main class="main"><header class="topbar"><div>Workspace <i>/</i> <b><?= e($pageTitle) ?></b></div><span class="online">● System operational</span></header><div class="content">
<?php if ($notice): ?><div class="notice <?= e($notice['type']) ?>"><?= e($notice['message']) ?></div><?php endif; ?>
