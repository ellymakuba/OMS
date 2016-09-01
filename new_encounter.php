<?PHP
	require 'functions.php';
	$fO=new functions();
	$fO->checkLogin();
	if (isset($_POST['save'])){
    if(isset($_POST['weight']) && isset($_POST['temperature']) && isset($_POST['weight'])){
      $fO->saveEncounter($_POST['weight'],$_POST['temperature'],$_POST['blood_pressure'],$_POST['pulse'],
      $_POST['respiration'],$_SESSION['log_user']);
    }
	}
  if (isset($_POST['edit'])){
		if(isset($_POST['weight']) && isset($_POST['temperature']) && isset($_POST['weight'])){
		$fO->updateEncounter($_POST['weight'],$_POST['temperature'],$_POST['blood_pressure'],$_POST['pulse'],
		$_POST['respiration'],$_SESSION['log_user'],$_SESSION['encounter']);
	}
	}
  ?>
  <html>
  <?PHP $fO->includeHead('Patient Encounter',0) ?>
  </head>
  <body class="container">
    <?PHP $fO->includeMenu(1); ?>
  	<div id="menu_main">
      <a href="patient_list.php">Patient List</a>
      <a href="new_patient.php">Patient</a>
      <a href="encounter_list.php">Encounter List</a>
      <a href="new_encounter.php" id="item_selected">New Encounter</a>
      </div>
      <?php
			if(isset($_REQUEST['SelectedEncounter'])){
        $encounter=$fO->getEncounterByID($_REQUEST['SelectedEncounter']);
        $_SESSION['encounter']=$_REQUEST['SelectedEncounter'];
      ?>
			<form class="form-signin" method="POST"  action="<?php echo $_SERVER['PHP_SELF']; ?>">
				<h2 class="form-signin-heading">Edit Encounter</h2>
				<div class="form-inline">
					<label for="patient_name">Patient Name:</label>
					<input type="text"  name="patient_name" class="form-control" style="width:90%;float:right;" placeholder="Patient Name" required value="<?php echo $encounter['p_name'] ?>">
				</div>
				<div style="clear:both;"></div>
				<div class="form-inline">
					<label for="weight">Weight:</label>
					<input type="text"  name="weight" class="form-control" style="width:90%;float:right;" placeholder="Weight" required value="<?php echo $encounter['weight'] ?>">
				</div>
				<div style="clear:both;"></div>
				<div class="form-inline">
					<label for="temperature">Temperature:</label>
				<input type="text" name="temperature" class="form-control" style="width:90%;float:right;" placeholder="Temperature"  required value="<?php echo $encounter['temperature'] ?>">
			</div>
			<div style="clear:both;"></div>
			<div class="form-inline">
				<label for="pulse">Pulse:</label>
				<input type="text" name="pulse" class="form-control" style="width:90%;float:right;" placeholder="Pulse Rate" required value="<?php echo $encounter['pulse'] ?>">
			</div>
			<div style="clear:both;"></div>
			<div class="form-inline">
				<label for="respiration">Respiration:</label>
				<input type="text"  name="respiration" class="form-control" style="width:90%;float:right;" placeholder="Respiration"  required value="<?php echo $encounter['respiration'] ?>">
			</div>
			<div style="clear:both;"></div>
			<div class="form-inline">
				<label for="blood_pressure">Blood Pressure:</label>
				<input type="text"  name="blood_pressure" class="form-control"  style="width:90%;float:right;" placeholder="Blood Pressure" required value="<?php echo $encounter['blood_pressure'] ?>">
			</div>
			<div style="clear:both;"></div>
		  <input type="submit" name="edit" class="btn btn-lg btn-primary" value="Edit Encounter"
      style="display: block; margin: 0 auto;width:200px;"></input>
  </form>
  <?php }
  else{
		?>
		<form class="form-signin" method="POST"  action="<?php echo $_SERVER['PHP_SELF']; ?>">
			<h2 class="form-signin-heading">Add New Encounter</h2>
			<div class="form-inline">
				<label for="weight">Weight:</label>
      	<input type="text"  name="weight" class="form-control" style="width:90%;float:right;" placeholder="Weight" required>
			</div>
			<div style="clear:both;"></div>
			<div class="form-inline">
				<label for="temperature">Temperature:</label>
      <input type="text" name="temperature" class="form-control" style="width:90%;float:right;" placeholder="Temperature"  required>
		</div>
		<div style="clear:both;"></div>
		<div class="form-inline">
			<label for="pulse">Pulse:</label>
      <input type="text" name="pulse" class="form-control" style="width:90%;float:right;" placeholder="Pulse Rate" required>
		</div>
		<div style="clear:both;"></div>
		<div class="form-inline">
			<label for="respiration">Respiration:</label>
      <input type="text"  name="respiration" class="form-control" style="width:90%;float:right;" placeholder="Respiration"  required>
		</div>
		<div style="clear:both;"></div>
		<div class="form-inline">
			<label for="blood_pressure">Blood Pressure:</label>
      <input type="text"  name="blood_pressure" class="form-control"  style="width:90%;float:right;" placeholder="Blood Pressure" required>
		</div>
		<div style="clear:both;"></div>
			<input type="submit" name="save" class="btn btn-lg btn-primary" value="Add Encounter"
			    style="display: block; margin: 0 auto;width:200px;"></input>
			</form>
</div>
      <?php }?>
  </body>
  </html>
