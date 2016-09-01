<!DOCTYPE HTML>
<?PHP
	require 'functions.php';
	$functionObject=new functions();
	$functionObject->checkLogin();
	$functionObject->getSettings();
?>
<html>
	<?PHP $functionObject->includeHead('Hospital Management',1); ?>
	<body>
		<?PHP
				$functionObject->includeMenu(1);
		?>
		<!-- MENU MAIN -->
		<div id="menu_main">
			<a href="cust_search.php">Search Patient</a>
			<a href="patient_registration.php">Patient Registration</a>
			<a href="cust_new.php">Record Vitals</a>
		</div>
		<!-- Left Side of Dashboard -->
		<div class="content_left" style="width:50%;">
			<?PHP include $_SESSION['set_dashl']; ?>
		</div>

		<!-- Right Side of Dashboard -->
		<div  class="content_right" style="width:50%;">
			<?PHP include $_SESSION['set_dashr']; ?>
		</div>

		<!-- Logout Reminder Message -->
		<?PHP	$functionObject->checkLogout();	?>

	</body>
</html>
