<?PHP
	require 'functions.php';
	$fO=new functions();
	$fO->checkLogin();
	if (isset($_POST['admit'])){
		if(isset($_SESSION['encounter']) && isset($_SESSION['patient']) && isset($_POST['no_of_admissions'])
		&& isset($_POST['ward']) && isset($_POST['bed_no'])){
			if(isset($_POST['insurance_patient'])){
				$fO->admitPatient($_SESSION['encounter'],$_SESSION['patient'],$_POST['allergy'],$_POST['insurance'],
				$_POST['no_of_admissions'],$_POST['ward'],$_POST['bed_no'],1);
			}
			else{
				$fO->admitPatient($_SESSION['encounter'],$_SESSION['patient'],$_POST['allergy'],'',
				$_POST['no_of_admissions'],$_POST['ward'],$_POST['bed_no'],0);
			}
	}
	$fO->updateToInpatient($_SESSION['encounter']);
	unset($_SESSION['encounter']);
	unset($_SESSION['patient']);
	header('Location:manage_nurse.php');
	}
  if (isset($_POST['edit'])){
	}
  ?>
  <html>
  <?PHP $fO->includeHead('Patient Admission',0) ?>
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
  		<a href="patient_admission.php" id="item_selected">Admission</a>
      </div>
      <?php
			if(isset($_REQUEST['SelectedPatient']) && isset($_REQUEST['selectedEncounter'])){
				$_SESSION['patient']=$_REQUEST['SelectedPatient'];
				$_SESSION['encounter']=$_REQUEST['selectedEncounter'];
			}
			if(isset($_SESSION['patient'])){
				$encounter=$fO->getEncounterByID($_SESSION['encounter']);
				$patient=$fO->getPatientByID($_SESSION['patient']);
				if(isset($_REQUEST['SelectedAdmission'])){
        $_SESSION['patient_id']=$_REQUEST['SelectedPatient'];
      ?>
			<form class="form-signin" method="POST"  action="<?php echo $_SERVER['PHP_SELF']; ?>">
				<h2 class="form-signin-heading">Edit Admission</h2>
				<div class="form-inline">
					<label for="patient_name">Patient Name:</label>
					<input type="text"  name="patient_name" class="form-control" style="width:90%;float:right;"
					placeholder="Patient Name" required value="<?php echo $patient['p_name'] ?>" readonly="">
				</div>
				<div style="clear:both;"></div>
				<div class="form-inline">
					<label for="insurance">Insurance Patient:</label>
	      	<input type="checkbox"  name="insurance" class="form-control" style="width:90%;float:right;">
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
  <?php }
  else{
		?>
		<form class="form-signin" method="POST"  action="<?php echo $_SERVER['PHP_SELF']; ?>">
			<h2 class="form-signin-heading">New Admission</h2>
			<div class="form-inline">
				<label for="patient_name">Patient Name:</label>
				<input type="text"  name="patient_name" class="form-control" style="width:90%;float:right;"
				placeholder="Patient Name" required value="<?php echo $patient['p_name'] ?>" readonly="">
			</div>
			<div style="clear:both;"></div>
			<div class="form-inline">
				<label for="insurance_patient">Insurance Patient:</label>
				<input type="checkbox"  id="insurance_patient" name="insurance_patient" class="form-control">
			</div>
			<div class="form-inline" id="company">
				<label for="insurance">Company:</label>
			<select name="insurance" class="form-control" style="width:90%;float:right;" >
				<option disabled selected>Select Insurance</option>
			<?php
			$insurance_companies=$fO->getAllInsuranceCompanies();
			foreach($insurance_companies as $company){
				echo '<option value='.$company['id'].'>'.$company['name'].'</option>';
			}
			echo '</select>'
			?>
		</div>
		<div style="clear:both;"></div>
		<div class="form-inline">
			<label for="no_of_admissions"># of Admissions:</label>
			<input type="text" name="no_of_admissions" class="form-control" style="width:90%;float:right;" placeholder="Number of Previous Admissions" required>
		</div>
		<div style="clear:both;"></div>
		<div class="form-inline">
			<label for="allergy">Allergy:</label>
			<select  name="allergy" class="form-control" style="width:90%;float:right;" placeholder="Allergy">
			<option disabled selected>Select Allergy</option>
			<?php
			$allergies=$fO->getAllAllergies();
			foreach($allergies as $allergy){
				echo '<option value='.$allergy['id'].'>'.$allergy['name'].'</option>';
			}
			echo '</select>'
			?>
		</div>
		<div style="clear:both;"></div>
		<div class="form-inline">
			<label for="ward">Ward:</label>
			<select  name="ward" class="form-control"  style="width:90%;float:right;" placeholder="ward" required>
				<option disabled selected>Select Ward</option>
			<?php
			$wards=$fO->getAllWards();
			foreach($wards as $ward){
				echo '<option value='.$ward['id'].'>'.$ward['name'].'</option>';
			}
			echo '</select>'
			?>
		</div>
		<div style="clear:both;"></div>
		<div class="form-inline">
			<label for="bed_no">Bed Number:</label>
			<input type="number"  name="bed_no" class="form-control"  style="width:90%;float:right;" placeholder="Bed Number" required>
		</div>
		<div style="clear:both;"></div>
		<?php if($encounter['admitted']==0){?>
			<input type="submit" name="admit" class="btn btn-lg btn-primary" value="Admit Patient"
					style="display: block; margin: 0 auto;width:200px;"></input>
		<?php } ?>
			</form>
</div>
      <?php }}?>
  </body>
  </html>
