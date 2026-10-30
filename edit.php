<?php
require_once __DIR__ . '/includes/bootstrap.php';


$id = filter_input(INPUT_GET, 'id', FILTER_VALIDATE_INT);

if (!$id) redirect('index.php');

$s = $pdo->prepare("SELECT * FROM products WHERE id = ?");
$s->execute([$id]);

$product = $s->fetch();

if (!$product) {
    flash('error', 'Product not found.');
    redirect('index.php');
}

$pageTitle = 'Edit product';
$activePage = 'inventory';
$errors = [];

$d = [
    'name' => $product['name'],
    'sku' => $product['sku'],
    'category' => $product['category'],
    'price' => $product['price'],
    'stock' => (string)$product['stock'],
    'description' => $product['description'] ?? ''
];

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    verify_csrf();

    foreach ($d as $k => $v) {
        $d[$k] = trim((string)($_POST[$k] ?? ''));
    }

    if ($d['name'] === '') $errors[] = 'Product name is required.';
    if ($d['sku'] === '') $errors[] = 'SKU is required.';
    if ($d['category'] === '') $errors[] = 'Choose a category.';

    if (!is_numeric($d['price']) || (float)$d['price'] < 0) {
        $errors[] = 'Enter a valid price.';
    }

    if (
        filter_var($d['stock'], FILTER_VALIDATE_INT) === false ||
        (int)$d['stock'] < 0
    ) {
        $errors[] = 'Stock must be a whole number of 0 or more.';
    }

    if (!$errors) {
        try {
            $u = $pdo->prepare("
                UPDATE products
                SET name = ?, sku = ?, category = ?,
                    price = ?, stock = ?, description = ?
                WHERE id = ?
            ");

            $u->execute([
                $d['name'],
                $d['sku'],
                $d['category'],
                $d['price'],
                $d['stock'],
                $d['description'],
                $id
            ]);

            flash('success', 'Product changes have been saved.');
            redirect('index.php');

        } catch (PDOException $e) {
            $errorCode = (int)($e->errorInfo[1] ?? 0);

            if ($e->getCode() === '23000' || $errorCode === 19 || $errorCode === 1062) {
                $errors[] = 'That SKU is already used.';
            } else {
                $errors[] = 'Unable to update product.';
            }
        }
    }
}

require __DIR__ . '/includes/header.php';
?>

<div class="form-heading">
    <a href="index.php">← Back to inventory</a>
    <div class="eyebrow">PRODUCT MANAGEMENT · EDITING #<?= (int)$id ?></div>
    <h1>Update product<span>.</span></h1>
    <p>Make changes to the product information below.</p>
</div>

<section class="form-panel">
    <div class="form-title">
        <div>
            <h2>Product information</h2>
            <p>Update the fields you want to change.</p>
        </div>
        <small>EDIT / <?= (int)$id ?></small>
    </div>

    <?php if ($errors): ?>
        <div class="notice error">
            <?php foreach ($errors as $e): ?>
                <div>• <?= e($e) ?></div>
            <?php endforeach; ?>
        </div>
    <?php endif; ?>

    <form method="post">
        <?= csrf_field() ?>

        <div class="form-grid">
            <div class="field wide">
                <label>Product name *</label>
                <input name="name" required maxlength="120"
                    value="<?= e($d['name']) ?>">
            </div>

            <div class="field">
                <label>SKU / Product code *</label>
                <input name="sku" required maxlength="40"
                    value="<?= e($d['sku']) ?>">
            </div>

            <div class="field">
                <label>Category *</label>
                <select name="category" required>
                    <option value="">Choose a category</option>
                    <?php foreach (['Electronics','Accessories','Office supplies','Furniture','Food & beverage','Other'] as $c): ?>
                        <option <?= $d['category'] === $c ? 'selected' : '' ?>>
                            <?= e($c) ?>
                        </option>
                    <?php endforeach; ?>
                </select>
            </div>

            <div class="field">
                <label>Unit price (IDR) *</label>
                <div class="affix">
                    <span>Rp</span>
                    <input name="price" type="number" min="0" step="1"
                        required value="<?= e($d['price']) ?>">
                </div>
            </div>

            <div class="field">
                <label>Current stock *</label>
                <div class="affix">
                    <input name="stock" type="number" min="0" step="1"
                        required value="<?= e($d['stock']) ?>">
                    <span>units</span>
                </div>
            </div>

            <div class="field wide">
                <label>Description <small>(optional)</small></label>
                <textarea name="description" rows="4" maxlength="1000"><?= e($d['description']) ?></textarea>
            </div>
        </div>

        <div class="form-actions">
            <a class="btn light" href="index.php">Cancel</a>
            <button class="btn primary">Save changes →</button>
        </div>
    </form>
</section>

<?php require __DIR__ . '/includes/footer.php'; ?>

