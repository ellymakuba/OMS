<!DOCTYPE HTML>
<?PHP
	require 'functions.php';
	$functionObject=new functions();
	$functionObject->checkLogin();
?>
<html>
	<?PHP $functionObject->includeHead('Microfinance Management',1); ?>
	<body>
		<?PHP
				$functionObject->includeMenu(1);
		?>
		<div id="menu_main">
			<a href="cust_search.php" id="item_selected">Patient List</a>
			<a href="cust_new.php">New Patient</a>
		</div>
		<div class="content_left" style="width:50%;">
			<?PHP include $_SESSION['set_dashl']; ?>
		</div>
		<div  class="content_right" style="width:50%;">
			<?PHP include $_SESSION['set_dashr']; ?>
		</div>
		<?PHP	$functionObject->checkLogout();	?>
	</body>
</html>
