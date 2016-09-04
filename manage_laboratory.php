<!DOCTYPE HTML>
<?PHP
	require 'functions.php';
	$fO=new functions();
	$fO->checkLogin();
?>
<html>
	<?PHP $fO->includeHead('Laboratory Module',1); ?>
	<body class="container">
		<?PHP
				$fO->includeMenu(4);
		?>
		<div id="menu_main">
			<a href="manage_laboratory.php" id="item_selected">Patient Queue</a>
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
      <th>Remarks</th>
			<th>Prescription</th>
    </tr>
    <?php
    if(isset($_REQUEST['srch-term'])){
      $patients=$fO->getLaboratoryQueue($_SESSION['log_user'],$_REQUEST['srch-term']);
			$i=0;
			foreach($patients as $patient){
				$i=$i+1;
				echo "<tr>";
				echo "<td>".$patient['patient_id']."</td>";
				echo "<td>".$patient['p_name']."</td>";
				echo "<td>".$patient['remarks']."</td>";
				echo "<td><a href=".'laboratory_billing.php?selectedPatient='.$patient['patient_id'].
				"&selectedPrescription=".$patient['prescription_id'].">Bill</a></td>";
				echo "</tr>";
      }
    }
    else{
      $patients=$fO->getAllLabQueue();
			$i=0;
			foreach($patients as $patient){
				$i=$i+1;
				echo "<tr>";
				echo "<td>".$patient['patient_id']."</td>";
				echo "<td>".$patient['p_name']."</td>";
				echo "<td>".$patient['observation']."</td>";
				echo "<td><a href=".'laboratory_billing.php?selectedPatient='.$patient['patient_id'].
				"&selectedPrescription=".$patient['prescription_id'].">Bill</a></td>";
				echo "</tr>";
      }
    }
     ?>
    </table>
      </div>
    </body>
    </html>
