<!DOCTYPE HTML>
<?PHP
	require 'functions.php';
	$fO=new functions();
	$fO->checkLogin();
?>
<html>
	<?PHP $fO->includeHead('Nurse Module',1); ?>
	<body class="container">
		<?PHP
				$fO->includeMenu(2);
		?>
		<div id="menu_main">
			<a href="nurse_patients.php" id="item_selected">Patients</a>
      <a href="admission_list.php">Admission List</a>
      <a href="manage_nurse.php">Manage Nurse</a>
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
      <th>#</th>
      <th>Patient name</th>
      <th>Food and supplements</th>
			<th>Remarks</th>
			<th>Prescription</th>
      <th>Shift start</th>
      <th>Shift end</th>
    </tr>
    <?php
    if(isset($_REQUEST['srch-term'])){
      $patients=$fO->getNurseSinglePatient($_SESSION['log_user'],$_REQUEST['srch-term']);
			$i=0;
			foreach($patients as $patient){
				$i=$i+1;
				echo "<tr>";
				echo "<td>".$patient['patient_id']."</td>";
				echo "<td>".$patient['p_name']."</td>";
				echo "<td>".$patient['food']."</td>";
				echo "<td>".$patient['observation']."</td>";
				echo '<td data-toggle="modal" data-target="#myModal'.$i.'">
	      <button type="button" id="btn'.$i.'" class="btn btn-default"
	      data-dismiss="modal">Show</button></td>';
				echo "<td>".$patient['shift_start']."</td>";
				echo "<td>".$patient['shift_end']."</td>";
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

        <pre>'.$patient['observation'].'<pre>

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
      $patients=$fO->getNurseAllPatients($_SESSION['log_user']);
			$i=0;
			foreach($patients as $patient){
				$i=$i+1;
				echo "<tr>";
				echo "<td>".$patient['patient_id']."</td>";
				echo "<td>".$patient['p_name']."</td>";
				echo "<td>".$patient['food']."</td>";
				echo "<td>".$patient['observation']."</td>";
				echo '<td data-toggle="modal" data-target="#myModal'.$i.'">
	      <button type="button" id="btn'.$i.'" class="btn btn-default"
	      data-dismiss="modal">Show</button></td>';
				echo "<td>".$patient['shift_start']."</td>";
				echo "<td>".$patient['shift_end']."</td>";
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

        <pre>'.$patient['observation'].'<pre>

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
