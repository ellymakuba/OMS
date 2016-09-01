<!DOCTYPE HTML>
<?PHP
require 'functions.php';
$fO=new functions();
$fO->checkLogin();

	//Save Changes
	if (isset($_POST['submit'])){
	}
	//Get Settings and Fees
	$fO->getSettings();
?>
<html>
	<?PHP $fO->includeHead('Settings | Add Department',1) ?>
	<body>
		<!-- MENU -->
		<?PHP
				$fO->includeMenu(6);
		?>
		<!-- MENU MAIN -->
		<div id="menu_main">
			<a href="add_labaratory.php">Add Labaratory</a>
			<a href="add_nurse.php" >Add Nurse</a>
			<a href="add_department.php" id="item_selected">Add Department</a>
		</div>
		<!-- LEFT SIDE: Fees -->
		<div class="content_settings">
			<form action="add_department.php" method="post">
				<p class="heading">Add Department</td>
				<table id="tb_set">
					<tr>
						<td>Name</td>
						<td>
							<input type="text" name="name" value=""/>
						</td>
					</tr>
					<tr>
						<td>Head of Department</td>
						<td>
							<input type="text" name="head_of_department" value="" />
						</td>
					</tr>
					<tr>
				</table>
				<input type="submit" name="submit" value="Save Changes">
			</form>
		</div>
	</body>
</html>
