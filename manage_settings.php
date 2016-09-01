<!DOCTYPE HTML>
<?PHP
	require 'functions.php';
	$functionObject=new functions();
	$functionObject->checkLogin();
?>
<html>
	<?PHP $functionObject->includeHead('System Administration',1); ?>
	<body class="container">
		<?PHP
				$functionObject->includeMenu(9);
		?>
		<div id="menu_main">
			<a href="cust_search.php" id="item_selected">Users</a>
      <a href="cust_search.php">Roles</a>
      <a href="cust_search.php">Privileges</a>
      <a href="drug_list.php">Drugs</a>
      <a href="cust_search.php">Lab Tests</a>
      <a href="cust_search.php">Operations</a>
      <a href="cust_search.php">Nurses</a>
      <a href="cust_search.php">Doctors</a>
      <a href="cust_search.php">Labs</a>
      <a href="cust_search.php">Departments</a>
      <a href="cust_search.php">Rooms</a>
		</div>
		<div class="content_center">
		</div>
	</body>
</html>
