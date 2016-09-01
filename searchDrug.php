<?php
require 'functions.php';
$fO=new functions();
$fO->checkLogin();
$q=$_GET['term'];
$drugs=$fO->getDrugByName($q);
foreach($drugs as $drug)
{
 $obj[]=array('id' => $drug['id'], 'name' => $drug['name']);
}
print json_encode($obj);
?>
