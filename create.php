<?php
require_once __DIR__.'/includes/bootstrap.php';
$pageTitle='Add product';$activePage='create';$errors=[];
$d=['name'=>'','sku'=>'','category'=>'','price'=>'','stock'=>'','description'=>''];
if($_SERVER['REQUEST_METHOD']==='POST'){verify_csrf();foreach($d as $k=>$v)$d[$k]=trim((string)($_POST[$k]??''));
if($d['name']==='')$errors[]='Product name is required.';
if($d['sku']==='')$errors[]='SKU is required.';
if($d['category']==='')$errors[]='Choose a category.';
if(!is_numeric($d['price'])||(float)$d['price']<0)$errors[]='Enter a valid price.';
if(filter_var($d['stock'],FILTER_VALIDATE_INT)===false||(int)$d['stock']<0)$errors[]='Stock must be a whole number of 0 or more.';
if(!$errors){try{$s=$pdo->prepare("INSERT INTO products(name,sku,category,price,stock,description) VALUES(?,?,?,?,?,?)");$s->execute([$d['name'],$d['sku'],$d['category'],$d['price'],$d['stock'],$d['description']]);flash('success','Product successfully added to your inventory.');redirect('index.php');}catch(PDOException $e){$errors[]=$e->getCode()==='23000'?'That SKU is already in use.':'Unable to save product. Please try again.';}}}
require __DIR__.'/includes/header.php'; ?>
<div class="form-heading"><a href="index.php">← Back to inventory</a><div class="eyebrow">PRODUCT MANAGEMENT</div><h1>Add a new product<span>.</span></h1><p>Add product details to keep your inventory accurate and organized.</p></div>
<section class="form-panel"><div class="form-title"><div><h2>Product information</h2><p>Fields marked with * are required.</p></div><small>01 / 01</small></div>
<?php if($errors): ?><div class="notice error"><?php foreach($errors as $e): ?><div>• <?= e($e) ?></div><?php endforeach; ?></div><?php endif; ?>
<form method="post"><?= csrf_field() ?><div class="form-grid">
<div class="field wide"><label>Product name *</label><input name="name" required maxlength="120" placeholder="e.g. Wireless Headphones" value="<?= e($d['name']) ?>"></div>
<div class="field"><label>SKU / Product code *</label><input name="sku" required maxlength="40" placeholder="e.g. AUD-001" value="<?= e($d['sku']) ?>"><small>A unique identifier for this product.</small></div>
<div class="field"><label>Category *</label><select name="category" required><option value="">Choose a category</option><?php foreach(['Electronics','Accessories','Office supplies','Furniture','Food & beverage','Other'] as $c): ?><option <?= $d['category']===$c?'selected':'' ?>><?= e($c) ?></option><?php endforeach; ?></select></div>
<div class="field"><label>Unit price (IDR) *</label><div class="affix"><span>Rp</span><input name="price" type="number" min="0" step="1" required placeholder="150000" value="<?= e($d['price']) ?>"></div></div>
<div class="field"><label>Initial stock *</label><div class="affix"><input name="stock" type="number" min="0" step="1" required placeholder="25" value="<?= e($d['stock']) ?>"><span>units</span></div></div>
<div class="field wide"><label>Description <small>(optional)</small></label><textarea name="description" rows="4" maxlength="1000" placeholder="Add a short description..."><?= e($d['description']) ?></textarea></div>
</div><div class="form-actions"><a class="btn light" href="index.php">Cancel</a><button class="btn primary">Save product →</button></div></form></section>
<?php require __DIR__.'/includes/footer.php'; ?>
