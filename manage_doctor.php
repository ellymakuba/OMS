<?PHP
	require 'functions.php';
	$fO=new functions();
	$fO->checkLogin();
  //Generate timestamp
	$timestamp = time();
	//CREATE-Button
	if (isset($_POST['create'])){
	}
  ?>
  <html>
  <?PHP $fO->includeHead('Patient List',0) ?>
  </head>
  <body class="container">
  <?PHP $fO->includeMenu(3); ?>
	<div id="menu_main">
    <a href="manage_doctor.php" id="item_selected">Encounter List</a>
		<a href="appointment_list.php">Appointments</a>
    </div>
  <div class="table-responsive">
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
  <div class="col-sm-3 col-md-3 pull-right">
    <a href="new_patient.php" class="btn btn-default btn-primary">New Patient</a>
  </div>
  <table class="table table-striped">
  <tr>
  <form action="#list_drug" method="POST">
    <th>#</th>
          <th>Patient Name</th>
          <th>Temperature</th>
          <th>Blood Pressure</th>
					<th>New Appointment</th>
  </form>
  </tr>
  <?php
  if(isset($_REQUEST['srch-term'])){
    $patients=$fO->getPatientPendingEncounters($_REQUEST['srch-term']);
    foreach($patients as $patient){
      printf("<tr><td><a href=\"new_patient.php?SelectedPatient=%s\">" .$patient['patient_id'] . "</a></td>
	    <td>%s</td>
	    <td>%s</td>
	    <td>%s</td>
			<td><a href=\"new_appointment.php?SelectedPatient=%s&selectedEncounter=%s\">New Appointment</a></td>
	    </tr>",
	    $patient['patient_id'],
	    $patient['p_name'],
	    $patient['temperature'],
	    $patient['blood_pressure'],
			$patient['patient_id'],
			$patient['encounter_id']
	    );
    }
  }
  else{
    $patients=$fO->getNewPatientEncounters();
    foreach($patients as $patient){
			printf("<tr><td><a href=\"new_patient.php?SelectedPatient=%s\">" .$patient['patient_id'] . "</a></td>
	    <td>%s</td>
	    <td>%s</td>
	    <td>%s</td>
			<td><a href=\"new_appointment.php?SelectedPatient=%s&selectedEncounter=%s\">New Appointment</a></td>
	    </tr>",
	    $patient['patient_id'],
	    $patient['p_name'],
	    $patient['temperature'],
	    $patient['blood_pressure'],
			$patient['patient_id'],
			$patient['encounter_id']
	    );
    }
  }
  ?>
  </table>
    </div>
  </body>
  </html>
