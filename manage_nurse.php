<!DOCTYPE HTML>
<?PHP
	require 'functions.php';
	$fO=new functions();
	$fO->checkLogin();
?>
<html>
	<?PHP $fO->includeHead('Nurse Module',1); ?>
	<body>
		<?PHP
				$fO->includeMenu(2);
		?>
		<div id="menu_main">
			<a href="nurse_patients.php">Patients</a>
      <a href="cust_search.php">Enter Vitals</a>
      <a href="cust_search.php">Admit Patient</a>
			<a href="manage_nurse.php" id="item_selected">manage_nurse</a>
		</div>
		<div class="content_center">
		</div>
	</body>
</html>
