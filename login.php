<?PHP
session_start();
	require 'functions.php';
	$functionObject=new functions();
	if(isset($_POST['login'])){
		// Sanitize user input
		$username = $functionObject->sanitize($_POST['log_user']);
		$password = $functionObject->sanitize($_POST['log_pw']);
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
			$_SESSION['AllowedPageSecurityTokens']=array();
			$tokens=$functionObject->getPrivilegesByRole($_SESSION['secroleId']);
			$i=0;
			foreach($tokens as $token){
				$_SESSION['AllowedPageSecurityTokens'][$i]=$token['tokenid'];
				$i++;
			}
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
	<body class="container">
		<div id="login">
			<form class="form-signin" action="login.php" method="post">
				<div class="form-inline">
					<label for="user_name">Username:</label>
					<input type="text" name="log_user" style="width:70%;float:right;" class="form-control" required=""/>
				</div>
				<div style="clear:both;"></div>
				<div class="form-inline">
					<label for="log_pw">Password:</label>
					<input type="password" name="log_pw" style="width:70%;float:right;" class="form-control" required=""/>
				</div>
				<div style="clear:both;"></div>
				<input type="submit" name="login" class="btn btn-default btn-primary"
				value="Login" style="display: block; margin: 0 auto;width:200px;"></input>
			</form>
		</div>
	</body>
</html>
