<?php
require_once __DIR__.'/includes/bootstrap.php';
if($_SERVER['REQUEST_METHOD']!=='POST'){http_response_code(405);exit('Method Not Allowed');}
verify_csrf();$id=filter_input(INPUT_POST,'id',FILTER_VALIDATE_INT);
if(!$id){flash('error','Invalid product ID.');redirect('index.php');}
$s=$pdo->prepare("DELETE FROM products WHERE id=?");$s->execute([$id]);
flash('success',$s->rowCount()?'Product successfully deleted.':'Product not found.');redirect('index.php');
