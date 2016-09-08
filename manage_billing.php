<?PHP
	require 'functions.php';
	$fO=new functions();
	$fO->checkLogin();
  ?>
  <html>
  <?PHP $fO->includeHead('Active Encounters',0) ?>
  </head>
  <body class="container">
  <?PHP $fO->includeMenu(6); ?>
	<div id="menu_main">
		<a href="manage_billing.php" id="item_selected">Active Encounters</a>
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
  <table class="table table-striped">
  <tr>
  <form  method="POST">
    <th>Dashboard</th>
          <th>Patient Name</th>
          <th>Temperature</th>
          <th>Blood Pressure</th>
					<th>Patient Type</th>
  </form>
  </tr>
  <?php
  if(isset($_REQUEST['srch-term'])){
    $patients=$fO->getPatientActiveEncounter($_REQUEST['srch-term']);
    foreach($patients as $patient){
			$patientType="OP";
			if($patient['admitted']==1){
				$patientType="IP";
			}
      printf("<tr><td><a href=\"patient_billing.php?selectedEncounter=%s\">" .$patient['encounter_id'] . "</a></td>
	    <td>%s</td>
	    <td>%s</td>
	    <td>%s</td>
			<td>%s</td>
	    </tr>",
	    $patient['encounter_id'],
	    $patient['p_name'],
	    $patient['temperature'],
	    $patient['blood_pressure'],
			$patientType,
			$patient['patient_id']
	    );
    }
  }
  else{
    $patients=$fO->getPatientActiveEncounterList();
    foreach($patients as $patient){
			$patientType="OP";
			if($patient['admitted']==1){
				$patientType="IP";
			}
			printf("<tr><td><a href=\"patient_billing.php?selectedEncounter=%s\">" .$patient['encounter_id'] . "</a></td>
	    <td>%s</td>
	    <td>%s</td>
	    <td>%s</td>
			<td>%s</td>
	    </tr>",
	    $patient['encounter_id'],
	    $patient['p_name'],
	    $patient['temperature'],
	    $patient['blood_pressure'],
			$patientType,
			$patient['patient_id']
	    );
    }
  }
  ?>
  </table>
    </div>
  </body>
  </html>
