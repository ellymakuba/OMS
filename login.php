<!DOCTYPE HTML>
<?PHP
	session_start();
	require 'functions.php';
	$functionObject=new functions();

	if(isset($_POST['login'])){
		// Sanitize user input
		$username = $_POST['log_user'];
		$password = $_POST['log_pw'];
		$fingerprint=$functionObject->fingerprint();
		$user=$functionObject->getUserByUsernameAndPassword($username, $password);
		if($user){
			// Define Session Variables for this User
			$_SESSION['log_user'] = $username;
			$_SESSION['log_time'] = time();
			$_SESSION['log_id'] = $user['user_id'];
			$_SESSION['log_ugroup'] = $user['ugroup_name'];
			$_SESSION['log_admin'] = $user['ugroup_admin'];
			$_SESSION['log_delete'] = $user['ugroup_delete'];
			$_SESSION['log_report'] = $user['ugroup_report'];
			$_SESSION['log_fingerprint'] = $fingerprint;
			// Forward to start.php
			$user=$functionObject->getUserByUserName($_SESSION['log_user']);
	    $functionObject->getUserRole($user['user_id']);
			$_SESSION['AllowedPageSecurityTokens']=$functionObject->getPrivilegesByRole($_SESSION['secroleId']);
			if($_SESSION['secroleId']==7){
			header('Location:products.php');
			}
			else{
			header('Location:manage_orders.php');
			}
		}
		else $functionObject->showMessage('Authentification failed!\nWrong Username and/or Password!');
	}
?>
<html>
<head>
	<meta charset="utf-8">
</head>
	<?PHP $functionObject->includeHead('Hospital Management') ?>
	<body>
		<div class="content_center" style="width:50%; padding-left:5em; text-align:left;margin-top:10%;">
			<p class="heading" style="margin:10px; text-align:center;">User Login</p>
			<form action="login.php" method="post">
				<table id="tb_fields" style="margin:0; border-spacing:0em 1.25em;">
					<tr><td style="padding-left: 80px;font-weight:bold;">Username: </td>
						<td>
							<input type="text" name="log_user"  placeholder="Username" />
						</td>
					</tr>
					<tr><td style="padding-left: 80px;font-weight:bold;">Password: </td>
						<td>
							<input type="password" name="log_pw" placeholder="Password" />
						</td>
					</tr>
					<tr>
						<td colspan=2>
							<input style="margin-left:50%;"  type="submit" name="login" value="Login">
						</td>
					</tr>
				</table>
			</form>

		</div>

	</body>
</html>
