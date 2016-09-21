<!DOCTYPE HTML>
<?PHP
	require 'functions.php';
	$fO=new functions();
	$fO->checkLogin();
	$users=$fO->getUsersWithClientSecurityRole();
?>
<html>
	<?PHP $fO->includeHead('Settings | Clients', 0) ?>
	</head>
	<body class="container">
		<?PHP $fO->includeMenu(9); ?>
		<div id="menu_main">
			<a href="manage_settings.php">Users List</a>
			<a href="user.php">User</a>
      <a href="roles.php">Roles</a>
      <a href="privileges.php">Privileges</a>
			<a href="client_list.php">Client List</a>
			<a href="client.php" id="item_selected">Client</a>
		</div>
		<?php
		if(isset($_POST["save"])){
			if(isset($_POST['client_name']) && isset($_POST['address']) && isset($_POST['phone']) && isset($_POST['user'])){
				$userAlreadyAddedAsClient=$fO->userAlreadyAddedAsClient($_POST["user"]);
				if($userAlreadyAddedAsClient['count']==0){
					$fO->saveNewClient($_POST['client_name'],$_POST['address'],$_POST['phone'],$_POST['user']);
					unset($_SESSION['client']);
				}
				else{
					echo '<div class="alert alert-danger">
						<strong>User already exists as client</strong>
					</div>';
				}
			}
		}
		if(isset($_POST['update'])){
			if(isset($_POST['client_name']) && isset($_POST['address']) && isset($_POST['phone']) && isset($_POST['user'])){
				$userAlreadyAddedAsClient=$fO->AllowClientUserNameUpdate($_POST["user"],$_SESSION['client']['client_id']);
				if($userAlreadyAddedAsClient['count']==0){
				$fO->updateClient($_SESSION['client']['client_id'],$_POST['client_name'],$_POST['address'],$_POST['phone'],$_POST['user']);
				unset($_SESSION['client']);
			}
			else{
				echo '<div class="alert alert-danger">
					<strong>User already exists as client</strong>
				</div>';
			}
			}
		}
		if(isset($_GET['selectedClient'])){
			$_SESSION['client']=$fO->getClientById($_GET['selectedClient']); ?>
			<form action="<?php echo $_SERVER['PHP_SELF']; ?>" method="post" class="form-signin">
				<h2 class="form-signin-heading">Edit Client</h2>
				<div class="form-inline">
					<label for="user_name">Client Name:</label>
					<input type="text" name="client_name" placeholder="Name" style="width:90%;float:right;" class="form-control"
					value="<?PHP  echo $_SESSION['client']['name'];?>" required=""/>
				</div>
				<div style="clear:both;"></div>
				<div class="form-inline">
					<label for="address">:</label>
					<input type="text" name="address" placeholder="Address" style="width:90%;float:right;" class="form-control" required=""/>
				</div>
				<div style="clear:both;"></div>
				<div class="form-inline">
					<label for="user_pw_conf">Phone Number:</label>
					<input type="number" name="phone" placeholder="Phone Number" style="width:90%;float:right;" class="form-control" required=""/>
				</div>
				<div style="clear:both;"></div>
				<div class="form-inline">
					<label for="user">User:</label>
					<select name="user" style="width:90%;float:right;" class="form-control" required>
						<?PHP
						foreach($users as $user){
							if($user['user_name'] == $_SESSION['user']['user_name']){
								echo '<option selected value='.$user['user_name'].'>'.$user['user_name'].'</option>';
							}
							else{
								echo '<option value='.$user['user_name'].'>'.$user['user_name'].'</option>';
							}
							}
						?>
					</select>
				</div>
				<div style="clear:both;"></div>
				<input type="submit" name="update" class="btn btn-lg btn-primary"
				value="Update User" style="display: block; margin: 0 auto;width:200px;"></input>
			</form>
		<?php }else{ ?>
				<form action="<?php echo $_SERVER['PHP_SELF']; ?>" method="post" class="form-signin">
					<h2 class="form-signin-heading">New Client</h2>
					<div class="form-inline">
						<label for="name">Client Name:</label>
						<input type="text" name="client_name" placeholder="Name" class="form-control" style="width:90%;float:right;" required=""/>
					</div>
					<div style="clear:both;"></div>
					<div class="form-inline">
						<label for="address">Address:</label>
						<input type="text" name="address" placeholder="Address" class="form-control" style="width:90%;float:right;" required=""/>
					</div>
					<div style="clear:both;"></div>
					<div class="form-inline">
						<label for="phone">Phone No:</label>
						<input type="number" name="phone" placeholder="Phone Number"  class="form-control" style="width:90%;float:right;" required=""/>
					</div>
					<div style="clear:both;"></div>
					<div class="form-inline">
						<label for="user">User:</label>
						<select name="user" style="width:90%;float:right;" class="form-control" required="">
							<?PHP
							foreach($users as $user){
									echo '<option value='.$user['user_name'].'>'.$user['user_name'].'</option>';
								}
							?>
						</select>
					</div>
					<div style="clear:both;"></div>
					<input type="submit" name="save" class="btn btn-lg btn-primary"
        	value="Add User" style="display:block; margin: 0 auto;width:200px;"></input>
				</form>
				<?php } ?>
	</body>
</html>
