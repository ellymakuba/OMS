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
			<div class="form-inline">
				<label>Temperature:</label>
				<input type="text" class="form-control" readonly="" value="<?php echo $encounter['temperature']?>">
				<label>Blood Pressure:</label>
				<input type="text" class="form-control" readonly="" value="<?php echo $encounter['blood_pressure']?>">
				<label>Weight:</label>
				<input type="text" class="form-control" readonly="" value="<?php echo $encounter['weight']?>">
				<label>Pulse:</label>
				<input type="text" class="form-control" readonly="" value="<?php echo $encounter['pulse']?>">
			</div>
			<?php
			$encounterList=$fO->getPatientHistory($patient['patient_id']);
			if(count($encounterList)>0){
				echo '<h2 class="form-signin-heading">Patient History</h2>';
				echo '<table class="table table-striped" style="border-spacing:2px;border-collapse:separate;width:100%;">';
				echo '<tr><th>View</th><th>Encounter Date</th><th>Blood Pressure</th><th>Admitted</th></tr>';
			foreach($encounterList as $encounter){
				$admitted='No';
				if($encounter['admitted']==1){
					$admitted='Yes';
				}
				echo '<tr>';
				echo '<td><a href="patient_dashboard.php?selectedEncounter='.$encounter['encounter_id'].'">View</a></td>';
				echo '<td>'.$encounter['time_created'].'</td>';
				echo '<td>'.$encounter['blood_pressure'].'</td>';
				echo '<td>'.$admitted.'</td>';
				echo '</tr>';
			}
			echo '</table>';
		}
			$consultations=$fO->getPharmacyOrdersByEncounterId($_SESSION['encounter']);
			if(count($consultations)>0){
				echo '<h2 class="form-signin-heading">Doctor Review</h2>';
				echo '<table class="table table-striped" style="border-spacing:2px;border-collapse:separate;width:100%;">';
				echo '<tr><th>View</th><th>Consultation Time</th><th>Doctor</th></tr>';
				$i=0;
			foreach($consultations as $consultation){
				$i=$i+1;
				$billed='No';
				if($consultation['pharmacy_billed']==1){
					$billed='Yes';
				}
				echo '<tr>';
				echo '<td data-toggle="modal" data-target="#myModal'.$i.'">
				<button type="button" id="btn'.$i.'" class="btn btn-default"
				data-dismiss="modal">View</button></td>';
				echo '<td>'.$consultation['con_time'].'</td>';
				echo '<td>'.$consultation['username'].'</td>';
				echo '</tr>';
				echo '<div class="modal fade" id="myModal'.$i.'" tabindex="-1" role="dialog"
				aria-labelledby="myModalLabel" aria-hidden="true">
  		<div class="modal-dialog">
    	<div class="modal-content">
      <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
				<span aria-hidden="true">&times;</span></button>
        <h4 class="modal-title" id="myModalLabel">Prescritpion Details</h4>
      </div>
      <div class="modal-body" id="modal-display">
        <pre>'.$consultation['observation'].'<pre>';
				$prescriptions=$fO->getPharmacyDispensedByEncounterId($consultation['encounter_id']);
				if(count($prescriptions)){
					echo '<h2>Pharmacy</h2>';
				foreach($prescriptions as $prescription){
					echo '<pre>'.$prescription['dname'].' - '.$prescription['dose'].'-'.$prescription['duration'].'</pre>';
				}
				}
				$labTests=$fO->getLabTestsByEncounterId($consultation['encounter_id']);
				if(count($labTests)){
					echo '<h2>Lab Tests</h2>';
				foreach($labTests as $labTest){
					echo '<pre>'.$labTest['test'].' - '.$labTest['result'].'</pre>';
				}
			}
      echo '</div>
      <div class="modal-footer">
        <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
      </div>
    	</div>
  	</div>
		</div>';
			}
			echo '</table>';
		}
				$dispensedOrders=$fO->getPharmacyDispensedByEncounterId($_SESSION['encounter']);
				if(count($dispensedOrders)>0){
					echo '<h2 class="form-signin-heading">Pharmacy List</h2>';
					echo '<table class="table table-striped" style="border-spacing:2px;border-collapse:separate;width:100%;">';
					echo '<tr><th>Date Dispensed</th><th>Order Item</th><th>Quantity</th><th>Collected By Nurse</th></tr>';
				foreach($dispensedOrders as $order){
					$collectedByNurse='No';
					if($order['collected_by_nurse']==1){
						$collectedByNurse='Yes';
					}
					echo '<tr>';
					echo '<td>'.$order['date_dispensed'].'</td>';
					echo '<td>'.$order['dname'].'</td>';
					echo '<td>'.$order['quantity'].'</td>';
					echo '<td>'.$collectedByNurse.'</td>';
					echo '</tr>';
				}
				echo '</table>';
			}
			 ?>
			</form>
    <?php }?>
  </body>
  </html>
