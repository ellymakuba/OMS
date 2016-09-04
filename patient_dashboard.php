<?PHP
	require 'functions.php';
	$fO=new functions();
	$fO->checkLogin();
	if (isset($_POST['admit'])){
	}
  if (isset($_POST['edit'])){
	}
  ?>
  <html>
  <?PHP $fO->includeHead('Patient Dashboard',0) ?>
	<script>
	$(document).ready(function(){
		$('#company').hide();
		$('#insurance_patient').click(function(){
      if(this.checked)
        $('#company').show();
      else
       $('#company').hide();
    });
	});
	</script>
  </head>
  <body class="container">
    <?PHP $fO->includeMenu(2); ?>
  	<div id="menu_main">
      <a href="manage_nurse.php">Active Encounters</a>
			<a href="admission_list.php">Admission List</a>
  		<a href="patient_admission.php">Admission</a>
			<a href="patient_dashboard.php" id="item_selected">Patient Dashboard</a>
      </div>
      <?php
			if(isset($_REQUEST['selectedEncounter'])){
				$_SESSION['encounter']=$_REQUEST['selectedEncounter'];
			}
			if(isset($_SESSION['encounter'])){
				$encounter=$fO->getEncounterByID($_SESSION['encounter']);
				$patient=$fO->getPatientByID($encounter['patient_id']);
				?>
			<form class="form-signin" method="POST"  action="<?php echo $_SERVER['PHP_SELF']; ?>">
			<h2 class="form-signin-heading">Patient Dashboard</h2>
			<div class="form-inline">
				<label for="patient_id">Patient ID:</label>
				<input type="text"  name="patient_id" class="form-control" style="width:90%;float:right;"
				placeholder="Patient Name" required value="<?php echo $patient['patient_id']?>" readonly="">
			</div>
			<div style="clear:both;"></div>
			<div class="form-inline">
				<label for="patient_name">Patient Name:</label>
				<input type="text"  name="patient_name" class="form-control" style="width:90%;float:right;"
				placeholder="Patient Name" required value="<?php echo $patient['p_name']; ?>" readonly="">
			</div>
			<div style="clear:both;"></div>
			<div class="form-inline">
				<label for="encounter_date">Encounter Date:</label>
				<input type="text"  name="encounter_date" class="form-control" style="width:90%;float:right;"
				placeholder="Encounter Date" required value="<?php echo $encounter['time_created']; ?>" readonly="">
			</div>
			<div style="clear:both;"></div>
			<?php
			$encounterList=$fO->getPatientHistory($patient['patient_id']);
			if(count($encounterList)>0){
				echo '<h2 class="form-signin-heading">Patient History</h2>';
				echo '<table class="table table-striped" style="border-spacing:2px;border-collapse:separate;width:100%;">';
				echo '<tr><th>View</th><th>Encounter Date</th><th>Blood Pressure</th><th>Admitted</th></tr>';
			foreach($encounterList as $encounter){
				echo '<tr>';
				echo '<td>'.$encounter['encounter_id'].'</td>';
				echo '<td>'.$encounter['time_created'].'</td>';
				echo '<td>'.$encounter['blood_pressure'].'</td>';
				echo '<td>'.$encounter['admitted'].'</td>';
				echo '</tr>';
			}
			echo '</table>';
		}
			$consultations=$fO->getPharmacyOrdersByEncounterId($_SESSION['encounter']);
			if(count($consultations)>0){
				echo '<h2 class="form-signin-heading">Doctor Review</h2>';
				echo '<table class="table table-striped" style="border-spacing:2px;border-collapse:separate;width:100%;">';
				echo '<tr><th>View</th><th>Consultation Time</th><th>Doctor</th><th>Billed</th><th>Issued</th></tr>';
			foreach($consultations as $consultation){
				$billed='No';
				if($consultation['pharmacy_billed']==1){
					$billed='Yes';
				}
				echo '<tr>';
				echo '<td>'.$consultation['prescription_id'].'</td>';
				echo '<td>'.$consultation['con_time'].'</td>';
				echo '<td>'.$consultation['username'].'</td>';
				echo '<td>'.$billed.'</td>';
				if($consultation['pharmacy_billed']==1){
				echo '<td><input type="checkbox" name="issued"></td>';
			}
			else{
				echo '<td></td>';
			}
				echo '</tr>';
			}
			echo '</table>';
		}
			 ?>
			</form>
    <?php }?>
  </body>
  </html>
