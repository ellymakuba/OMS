<?php
require 'functions.php';
$fO=new functions();
$fO->checkLogin();
$q=$_GET['term'];
$tests=$fO->getLabTestByName($q);
foreach($tests as $test)
{
 $obj[]=array('id' => $test['lab_id'],'test' => $test['test'],'cost' => $test['cost']);
}
print json_encode($obj);
?>
