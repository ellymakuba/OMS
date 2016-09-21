<!DOCTYPE HTML>
<?PHP
	require 'functions.php';
	$functionObject=new functions();
	$functionObject->checkLogin();
	$users=$fO->getAllUsers();
?>
<html>
	<?PHP $functionObject->includeHead('Settings',1); ?>
	<body class="container">
		<?PHP
				$functionObject->includeMenu(9);
		?>
		<div id="menu_main">
			<a href="users_list.php" id="item_selected">Users List</a>
		</div>
		<div class="container">
			<form action="set_ugroup.php" method="post">
				<table class="table table-striped">
					<tr>
						<th>User Name</th>
						<th>Security Role</th>
						<th>Changed</th>
						<th>Edit</th>
					</tr>
					<?PHP
					foreach ($users as $user){
						echo '<td>'.$user['user_name'].'</td>
									<td>'.$user['secroleid'].'</td>
									<td>'.$user['date_created'].'</td>
									<td>
										<a href="set_user.php?user='.$user['user_id'].'">
											<i class="fa fa-edit fa-lg"></i>
										</a>
									</td>
								</tr>';
					}
					?>
				</table>
			</form>
		</div>
	</body>
</html>
