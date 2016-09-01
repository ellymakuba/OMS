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
  <?PHP $fO->includeHead('Appointment List',0) ?>
  </head>
  <body class="container">
  <?PHP $fO->includeMenu(3); ?>
	<div id="menu_main">
    <a href="manage_doctor.php">Patient List</a>
		<a href="appointment_list.php" id="item_selected">Appointments</a>
		<a href="new_appointment.php">New Appointment</a>
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
    <a href="new_appointment.php" class="btn btn-default btn-primary">New Appointment</a>
  </div>
  <table class="table table-striped">
  <tr>
  <form action="#list_drug" method="POST">
    <th>#</th>
		<th>Patient Name</th>
		<th>Remarks</th>
		<th>Prescription</th>
		<th>Cosultation Time</th>
		<th>Date</th>
  </form>
  </tr>
  <?php
  if(isset($_REQUEST['srch-term'])){
		$appointments=$fO->getDoctorAppointmentByPatientName($_SESSION['log_user'],$_REQUEST['srch-term']);
		$i=0;
		foreach($appointments as $appointment){
			$i=$i+1;
			echo "<tr>";
			echo "<td>".$appointment['prescription_id']."</td>";
			echo "<td>".$appointment['p_name']."</td>";
			echo "<td>".$appointment['remarks']."</td>";
			echo '<td data-toggle="modal" data-target="#myModal'.$i.'">
			<button type="button" id="btn'.$i.'" class="btn btn-default"
			data-dismiss="modal">Show</button></td>';
			echo "<td>".$appointment['con_time']."</td>";
			echo "<td>".$appointment['appointment_date']."</td>";
			echo "</tr>";
			echo '<div class="modal fade" id="myModal'.$i.'" tabindex="-1" role="dialog"
			aria-labelledby="myModalLabel" aria-hidden="true">
		<div class="modal-dialog">
		<div class="modal-content">
		<div class="modal-header">
			<button type="button" class="close" data-dismiss="modal" aria-label="Close">
			<span aria-hidden="true">&times;</span></button>
			<h4 class="modal-title" id="myModalLabel">Prescription</h4>
		</div>
		<div class="modal-body" id="modal-display">

			<pre>'.$appointment['prescription'].'<pre>

		</div>
		<div class="modal-footer">
			<button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
		</div>
		</div>
	</div>
	</div>';
		}
  }
  else{
		$appointments=$fO->getDoctorAppointments($_SESSION['log_user']);
		$i=0;
		foreach($appointments as $appointment){
			$i=$i+1;
			echo "<tr>";
			echo "<td>".$appointment['prescription_id']."</td>";
			echo "<td>".$appointment['p_name']."</td>";
			echo "<td>".$appointment['remarks']."</td>";
			echo '<td data-toggle="modal" data-target="#myModal'.$i.'">
			<button type="button" id="btn'.$i.'" class="btn btn-default"
			data-dismiss="modal">Show</button></td>';
			echo "<td>".$appointment['con_time']."</td>";
			echo "<td>".$appointment['appointment_date']."</td>";
			echo "</tr>";
			echo '<div class="modal fade" id="myModal'.$i.'" tabindex="-1" role="dialog"
			aria-labelledby="myModalLabel" aria-hidden="true">
		<div class="modal-dialog">
		<div class="modal-content">
		<div class="modal-header">
			<button type="button" class="close" data-dismiss="modal" aria-label="Close">
			<span aria-hidden="true">&times;</span></button>
			<h4 class="modal-title" id="myModalLabel">Prescription</h4>
		</div>
		<div class="modal-body" id="modal-display">

			<pre>'.$appointment['prescription'].'<pre>

		</div>
		<div class="modal-footer">
			<button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
		</div>
		</div>
	</div>
	</div>';
		}
  }
  ?>
  </table>
    </div>
  </body>
  </html>
