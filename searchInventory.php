<?php
require 'functions.php';
$fO=new functions();
$fO->checkLogin();
$q=$_GET['term'];
$inventories=$fO->getInventoryByName($q);
foreach($inventories as $inventory)
{
 $obj[]=array('id' => $inventory['id'],'name' => $inventory['name'],'stock' => $inventory['stock']);
}
print json_encode($obj);
?>
