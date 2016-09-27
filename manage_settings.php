<!DOCTYPE HTML>
<?PHP
	require 'functions.php';
	$fO=new functions();
	$fO->checkLogin();
	$users=$fO->getAllUsers();
?>
<html>
	<?PHP $fO->includeHead('Settings | Users',1); ?>
	<body class="container">
		<?PHP
				$fO->includeMenu(4);
		?>
		<div id="menu_main">
			<a href="manage_settings.php" id="item_selected">Users List</a>
			<a href="user.php" >User</a>
      <a href="roles.php">Roles</a>
      <a href="privileges.php">Privileges</a>
			<a href="client_list.php">Client List</a>
			<a href="client.php">Client</a>
		</div>
		<div class="container">
			<div class="col-sm-3 col-md-3 pull-left">
	          <form class="navbar-form" role="search">
	          <div class="input-group">
	              <input type="text" class="form-control" placeholder="Search" name="srch-term" id="srch-term">
	              <div class="input-group-btn">
	                  <button class="btn btn-default" type="submit"><i class="glyphicon glyphicon-search"></i></button>
	              </div>
	          </div>
	          </form>
	  </div>
			<form action="set_ugroup.php" method="post">
				<table class="table table-striped">
					<tr>
						<th>User Name</th>
						<th>Security Role</th>
						<th>Changed</th>
					</tr>
					<?PHP
					foreach ($users as $user){
						echo '<td><a href="user.php?selectedUser='.$user['user_name'].'">'.$user['user_name'].'</a></td>
									<td>'.$user['role'].'</td>
									<td>'.$user['date_created'].'</td>
								</tr>';
					}
					?>
				</table>
			</form>
		</div>
	</body>
</html>
