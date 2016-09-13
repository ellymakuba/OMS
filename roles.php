<?php
$PageSecurity=1;
require 'functions.php';
$fO=new functions();
$fO->checkLogin();
if (isset($_GET['SelectedRole'])){
	$SelectedRole = $_GET['SelectedRole'];
} elseif (isset($_POST['SelectedRole'])){
	$SelectedRole = $_POST['SelectedRole'];
}
?>
<html>
<?PHP $fO->includeHead('User Roles',0) ?>
</head>
<body class="container">
<?PHP $fO->includeMenu(9); ?>
<div id="menu_main">
	<a href="cust_search.php">Users</a>
	<a href="roles.php" id="item_selected">Roles</a>
	<a href="cust_search.php">Privileges</a>
	</div>
<?php
if (isset($_POST['submit']) || isset($_GET['remove']) || isset($_GET['add']) ) {
	$InputError = 0;
	if (isset($_POST['SecRoleName']) && strlen($_POST['SecRoleName'])<4){
		$InputError = 1;
		//prnMsg(_('The role description entered must be at least 4 characters long'),'error');
	}
	unset($sql);
	if (isset($_POST['SecRoleName']) ){ // Update or Add Security Headings
		if(isset($SelectedRole)) {
			$fO->updateSecurityRole($_POST['SecRoleName'],$SelectedRole);
		} else {
			$fO->addNewSecurityRole($_POST['SecRoleName']);
		}
		unset($_POST['SecRoleName']);
		unset($SelectedRole);
	} elseif (isset($SelectedRole) ) {
		$PageTokenId = $_GET['PageToken'];
		if( isset($_GET['add'])) {
			$fO->addNewSecurityGroup($SelectedRole,$PageTokenId);
		} elseif ( isset($_GET['remove']) ) {
			$fO->removeSecurityGroupByTokenId($SelectedRole,$PageTokenId);
		}
		unset($_GET['add']);
		unset($_GET['remove']);
		unset($_GET['PageToken']);
	}
} elseif (isset($_GET['delete'])) {
	$numberOfUsersOnSecurityRole=$fO->numberOfUsersOnSecurityRole($_GET['SelectedRole']);
	if (count($numberOfUsersOnSecurityRole)>0) {
		//prnMsg( _('Cannot delete this role because user accounts are setup using it'),'warn');
	} else {
		$fO->removeSecurityGroup($_GET['SelectedRole']);
		$fO->removeSecurityRole($_GET['SelectedRole']);
	} //end if account group used in GL accounts
	unset($SelectedRole);
	unset($_GET['SecRoleName']);
}
if (!isset($SelectedRole)) {
	$roles=$fO->getAllSecurityRoles();
	echo '<table class="table table-striped">';
	echo "<tr><th>" . _('Role') . "</th></tr>";
	$k=0; //row colour counter
	foreach($roles as $role)
	{
		printf("<td>%s</td>
			<td><a href=\"%s?SelectedRole=%s\">" . _('Edit') . "</a></td>
			<td><a href=\"%s?SelectedRole=%s&delete=1&SecRoleName=%s\">" . _('Delete') . "</a></td>
			</tr>",
			$role['secrolename'],
			$_SERVER['PHP_SELF'],
			$role['secroleid'],
			$_SERVER['PHP_SELF'],
			$role['secroleid'],
			urlencode($role['secrolename']));

	} //END WHILE LIST LOOP
	echo '</table>';
} //end of ifs and buts!
if (isset($SelectedRole)) {
	echo "<br /><div class='btn btn-default btn-primary'><a href='" . $_SERVER['PHP_SELF'] ."'>" . _('Review Existing Roles') . '</a></div>';
}
if (isset($SelectedRole)) {
	$role=$fO->getRoleById($SelectedRole);
	if (count($role)== 0 ) {
		//prnMsg( _('The selected role is no longer available.'),'warn');
	} else {
		$_POST['SelectedRole'] = $role['secroleid'];
		$_POST['SecRoleName'] = $role['secrolename'];
	}
}
echo '<br>';
echo "<form method='post' class='form-signin' action=" . $_SERVER['PHP_SELF']. ">";
if( isset($_POST['SelectedRole'])) {
	echo "<input type=hidden name='SelectedRole' VALUE='" . $_POST['SelectedRole'] . "'>";
}
echo '<table class="table table-striped">';
if (!isset($_POST['SecRoleName'])) {
	$_POST['SecRoleName']='';
}
echo '<tr><td>' . _('Role') . ":</td>
	<td><input type='text' name='SecRoleName' class='form-control' VALUE='" . $_POST['SecRoleName'] . "'></tr>";
echo "</table><br />
	<div class='centre'><input type='Submit' name='submit' value='" . _('Enter Role') . "'></div></form>";
if (isset($SelectedRole)) {
	$privileges=$fO->getAllPrivileges();
	$tokens=$fO->getPrivilegesByRole($SelectedRole);
	$TokensUsed = array();
	$i=0;
	foreach($tokens as $token){
		$TokensUsed[$i] =$token['tokenid'];
		$i++;
	}
	echo '<br /><table class="table table-striped"><tr>';
	if (count($privileges)>0 ) {
		echo "<th colspan=3><div class='centre'>"._('Assigned Privileges')."</div></th>";
		echo "<th colspan=3><div class='centre'>"._('Available Privileges')."</div></th>";
	}
	echo '</tr>';
	$k=0; //row colour counter
	foreach($privileges as $privilege) {
		if (in_array($privilege['tokenid'],$TokensUsed)){
			printf("<td>%s</td><td>%s</td>
				<td><a href=\"%s?SelectedRole=%s&remove=1&PageToken=%s\">" . _('Remove') . "</a></td><td>&nbsp;</td><td>&nbsp;</td><td>&nbsp;</td>",
				$privilege['tokenid'],
				$privilege['tokenname'],
				$_SERVER['PHP_SELF'],
				$SelectedRole,
				$privilege['tokenid']
				);
		} else {
			printf("<td>&nbsp;</td>
				<td>&nbsp;</td>
				<td>&nbsp;</td>
				<td>%s</td>
				<td>%s</td>
				<td><a href=\"%s?SelectedRole=%s&add=1&PageToken=%s\">" . _('Add') . "</a></td>",
				$privilege['tokenid'],
				$privilege['tokenname'],
				$_SERVER['PHP_SELF'],
				$SelectedRole,
				$privilege['tokenid']
				);
		}
		echo '</tr>';
	}
	echo '</table>';
	}
echo '</body>';
echo	'</html>';
?>
