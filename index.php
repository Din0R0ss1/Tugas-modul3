<?php
require_once __DIR__.'/includes/bootstrap.php';
$pageTitle='Overview'; $activePage='dashboard';
$q=trim((string)($_GET['q']??'')); $cat=trim((string)($_GET['category']??''));
$stats=$pdo->query("SELECT COUNT(*) total,COALESCE(SUM(stock),0) units,COALESCE(SUM(price*stock),0) value,SUM(CASE WHEN stock<=5 THEN 1 ELSE 0 END) low FROM products")->fetch();
$categories=$pdo->query("SELECT DISTINCT category FROM products ORDER BY category")->fetchAll(PDO::FETCH_COLUMN);
$sql="SELECT * FROM products WHERE 1=1"; $args=[];
if($q!==''){$sql.=" AND (name LIKE :q OR sku LIKE :q OR category LIKE :q)";$args['q']="%$q%";}
if($cat!==''){$sql.=" AND category=:cat";$args['cat']=$cat;}
$sql.=" ORDER BY updated_at DESC,id DESC LIMIT 100";$stmt=$pdo->prepare($sql);$stmt->execute($args);$products=$stmt->fetchAll();
require __DIR__.'/includes/header.php';
?>
<section class="welcome"><div><div class="eyebrow"><?= strtoupper(date('l, F j, Y')) ?></div><h1>Good to see you<span>.</span></h1><p>Here’s what’s happening with your inventory today.</p></div><a class="btn primary" href="create.php">＋ Add product</a></section>
<section class="stats">
<article><label>Total products</label><strong><?= number_format((int)$stats['total']) ?></strong><small>● Products in your catalog</small><em>▤</em></article>
<article><label>Total stock units</label><strong><?= number_format((int)$stats['units']) ?></strong><small>● Units across all products</small><em class="blue">▦</em></article>
<article><label>Inventory value</label><strong class="money">Rp <?= number_format((float)$stats['value'],0,',','.') ?></strong><small>● Based on current stock</small><em class="green">↗</em></article>
<article><label>Low stock alerts</label><strong><?= number_format((int)$stats['low']) ?></strong><small>● Products with 5 units or less</small><em class="orange">!</em></article>
</section>
<section class="panel" id="inventory"><div class="panel-head"><div><h2>Product inventory</h2><p>Manage, track, and organize your products.</p></div><span><?= count($products) ?> records</span></div>
<form class="filters" method="get"><input type="search" name="q" placeholder="⌕  Search product, SKU, category..." value="<?= e($q) ?>"><select name="category"><option value="">All categories</option><?php foreach($categories as $c): ?><option <?= $cat===$c?'selected':'' ?> value="<?= e($c) ?>"><?= e($c) ?></option><?php endforeach; ?></select><button class="btn light">Filter</button><?php if($q!==''||$cat!==''): ?><a href="index.php">Clear</a><?php endif; ?></form>
<div class="table-scroll"><table><thead><tr><th>PRODUCT</th><th>SKU</th><th>CATEGORY</th><th>PRICE</th><th>STOCK</th><th>STATUS</th><th></th></tr></thead><tbody>
<?php if(!$products): ?><tr><td colspan="7" class="empty">No products found. <a href="create.php">Add your first product →</a></td></tr><?php else: foreach($products as $p): $s=(int)$p['stock'];$state=$s===0?'out':($s<=5?'low':'in');$label=$s===0?'Out of stock':($s<=5?'Low stock':'In stock'); ?>
<tr><td><div class="product"><span><?= e(strtoupper(substr($p['name'],0,1))) ?></span><div><b><?= e($p['name']) ?></b><small>Added <?= date('M j, Y',strtotime($p['created_at'])) ?></small></div></div></td><td><code><?= e($p['sku']) ?></code></td><td><label class="category"><?= e($p['category']) ?></label></td><td><b>Rp <?= number_format((float)$p['price'],0,',','.') ?></b></td><td><b><?= number_format($s) ?></b> <small>units</small></td><td><span class="pill <?= $state ?>"><?= $label ?></span></td><td><div class="actions"><a href="edit.php?id=<?= (int)$p['id'] ?>" title="Edit">✎</a><form method="post" action="delete.php" data-confirm="Delete <?= e($p['name']) ?>? This cannot be undone."><?= csrf_field() ?><input type="hidden" name="id" value="<?= (int)$p['id'] ?>"><button title="Delete">⌫</button></form></div></td></tr>
<?php endforeach; endif; ?></tbody></table></div><div class="table-foot">Showing <b><?= count($products) ?></b> matching products <span>Live database records</span></div></section>
<?php require __DIR__.'/includes/footer.php'; ?>
