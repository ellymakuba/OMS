<!DOCTYPE HTML>
<?PHP
	require 'functions.php';
	function CryptPass($Password ) {
		return sha1($Password);
    }
		$fO=new functions();
	$fO->checkLogin();
	$user_id = 0;
	$employee = 0;
	//Select all users from USER
	$users = array();
	$user_names = array();
	$roles=$fO->getAllSecurityRoles();
	$users=$fO->getAllUsers();
	//Set heading and variables according to selection
	if(isset($_GET['user'])){
		$user_id = $fO->sanitize($_GET['user']);
		foreach ($users as $row_user){
			if ($row_user['user_id'] == $user_id){
				$user_id = $row_user['user_id'];
				$user_name = $row_user['user_name'];
				$secuiry_role = $row_user['secroleid'];
			}
		}
		$heading = "Edit User";
	}
	else $heading = "Create User";
	//SAVE Button
	if(isset($_POST["save_changes"])){
		//Sanitize user input
		$user_id = $fO->sanitize($_POST['user_id']);
		$user_name = $fO->sanitize($_POST['user_name']);
		$user_pw = CryptPass(($fO->sanitize($_POST['user_pw'])));
		$security_role = $fO->sanitize($_POST['secroleid']);
		if($user_id == 0){
			$fO->saveNewUser($user_name,$user_pw,$security_role);
		}
		else {
			$fO->updateUser($userId,$user_pw,$security_role);
		}
		header('Location:manage_settings.php');
	}
?>
<html>
	<?PHP $fO->includeHead('Settings | Users', 0) ?>
	</head>
	<body class="container">
		<?PHP $fO->includeMenu(9); ?>
		<div id="menu_main">
			<a href="set_user.php" id="item_selected">Users</a>
      <a href="roles.php">Roles</a>
      <a href="cust_search.php">Privileges</a>
		</div>
				<form action="<?php echo $_SERVER['PHP_SELF']; ?>" method="post" class="form-signin">
					<h2 class="form-signin-heading"><?PHP echo $heading; ?></h2>
					<div class="form-inline">
						<label for="user_name">User Name:</label>
						<input type="text" name="user_name" placeholder="Username" value="<?PHP if(isset($user_name)) echo $user_name;?>" />
					</div>
					<div class="form-inline">
						<label for="password">Password:</label>
						<input type="password" name="user_pw" placeholder="Password"/>
					</div>
					<div class="form-inline">
						<label for="user_pw_conf">Repeat Password:</label>
						<input type="password" name="user_pw_conf" placeholder="Repeat Password"/>
					</div>
					<div class="form-inline">
						<label for="secroleid">Security Role:</label>
						<select name="secroleid" size="1" <?PHP if ($user_id == 1) echo ' disabled="disabled"'; ?> >
							<?PHP
							foreach($roles as $role){
									echo '<option value="'.$role['id'].'"';
									if (isset($security_role) and $role['id'] == $security_role) echo ' selected="selected	"';
									echo '>'.$role['secrolename'].'</option>';
								}
							?>
						</select>
					</div>
					<input type="submit" name="save_changes" class="btn btn-lg btn-primary"
        	value="Save Changes" style="display: block; margin: 0 auto;width:200px;"></input>
					<input type="hidden" name="user_id" value="<?PHP echo $user_id; ?>" />
				</form>
		<!-- RIGHT SIDE: List of Users -->
		<div class="content_right">
			<form action="<?php echo $_SERVER['PHP_SELF']; ?>" method="post">
				<table id="tb_table">
					<colgroup>
						<col width="26%">
						<col width="26%">
						<col width="16%">
						<col width="6%">
					</colgroup>
					<tr>
						<th class="title" colspan="5">Existing Users</th>
					</tr>
					<tr>
						<th>User Name</th>
						<th>Security Role</th>
						<th>Changed</th>
						<th>Edit</th>
					</tr>
					<?PHP
					$color=0;
					foreach ($users as $user){
						$fO->tr_colored($color);		//Alternating row colors
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
