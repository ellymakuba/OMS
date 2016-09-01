<!DOCTYPE HTML>
<?PHP
	require 'functions.php';
	$functionObject=new functions();
	$functionObject->checkLogin();
	$_SESSION['module_session']=4;
?>
<html>
	<?PHP $functionObject->includeHead('Laboratory Module',1); ?>
	<body class="container">
		<?PHP
				$functionObject->includeMenu($_SESSION['module_session']);
		?>
		<div id="menu_main">
			<a href="cust_search.php" id="item_selected">Patient Queue</a>
		</div>
		<div class="content_center">
		</div>
	</body>
</html>
