<?PHP
	require 'functions.php';
	$fO=new functions();
	$fO->checkLogin();
  ?>
  <html>
  <?PHP $fO->includeHead('Encounter List',0) ?>
  </head>
  <body class="container">
  <?PHP $fO->includeMenu(1); ?>
  <div id="menu_main">
    <a href="patient_list.php">Patient List</a>
    <a href="new_patient.php">Patient</a>
    <a href="encounter_list.php" id="item_selected">Encounter List</a>
    <a href="new_encounter.php">New Encounter</a>
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
  <form action="#list_drug" method="POST">
    <th>#</th>
          <th>Patient Name</th>
          <th>Weight</th>
          <th>Temperature</th>
					<th>Blood Pressure</th>
          <th>Pulse</th>
  </form>
  </tr>
  <?php
  if(isset($_REQUEST['srch-term'])){
    $encounters=$fO->getPatientEncounters($_REQUEST['srch-term']);
    foreach($encounters as $encounter){
      printf("<tr><td><a href=\"new_encounter.php?SelectedEncounter=%s\">" .$encounter['encounter_id'] . "</a></td>
    <td>%s</td>
    <td>%s</td>
    <td>%s</td>
    <td>%s</td>
    <td>%s</td>
    </tr>",
    $encounter['encounter_id'],
    $encounter['p_name'],
    $encounter['weight'],
    $encounter['temperature'],
		$encounter['blood_pressure'],
    $encounter['blood_pressure']
    );
    }
  }
  else{
    $encounters=$fO->getAllEncounters();
    foreach($encounters as $encounter){
      printf("<tr><td><a href=\"new_encounter.php?SelectedEncounter=%s\">" .$encounter['encounter_id'] . "</a></td>
    <td>%s</td>
    <td>%s</td>
    <td>%s</td>
    <td>%s</td>
    <td>%s</td>
    </tr>",
    $encounter['encounter_id'],
    $encounter['p_name'],
    $encounter['weight'],
    $encounter['temperature'],
		$encounter['blood_pressure'],
    $encounter['blood_pressure']
    );
    }
  }
  ?>
  </table>
    </div>
  </body>
  </html>
