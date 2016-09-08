<?php
require 'functions.php';
$fO=new functions();
$fO->checkLogin();
$q=$_GET['term'];
$drugs=$fO->getInventoryByName($q);
foreach($drugs as $drug)
{
 $obj[]=array('id' => $drug['id'],'name' => $drug['name'],'stock' => $drug['stock']);
}
print json_encode($obj);
?>
