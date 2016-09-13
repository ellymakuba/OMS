<?php
require 'functions.php';
$fO=new functions();
$fO->checkLogin();
$q=$_GET['term'];
$products=$fO->getProductsByName($q);
foreach($products as $product)
{
 $obj[]=array('id' => $product['id'],'name' => $product['name'],'cost' => $product['buying_price']);
}
print json_encode($obj);
?>
