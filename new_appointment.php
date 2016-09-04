<?PHP
	require 'functions.php';
	include('cartClass.php');
	include('lab_test_cart.php');
	$fO=new functions();
	$fO->checkLogin();
	if (isset($_POST['save'])){
		$i=0;
		$j=0;
		if(isset($_POST['admission'])){
			$fO->doctorAllowAdmission($_SESSION['encounter']);
		}
		$fO->seenDoctor($_SESSION['encounter']);
		$lastId=$fO->savePrescription($_POST['patient_id'],$_POST['prescription'],$_SESSION['log_user'],$_SESSION['encounter']);
		if(count($_POST['drug'])>0){
		foreach($_POST['drug'] as $value) {
			if(isset($_POST['drug'][$i]) && isset($_POST['dose'][$i]) && isset($_POST['duration'][$i])){
				$fO->savePharmacyPrescriptionOrder($lastId,$_POST['drug'][$i],$_POST['dose'][$i],$_POST['duration'][$i]);
			}
			$i++;
		}
		$fO->updatePharmacyPatientStatus($lastId);
		}
		if(count($_POST['test'])>0){
		foreach($_POST['test'] as $value){
			if(isset($_POST['test'][$j])){
				$fO->saveLabTestPrescriptionOrder($lastId,$_POST['test'][$j]);
			}
			$j++;
		}
		$fO->updateLabPatientStatus($lastId);
		}
		unset($_SESSION['encounter']);
		unset($_SESSION['patient']);
		unset($_SESSION['drugs']);
		unset($_SESSION['tests']);
		header('Location: manage_doctor.php');
	}
  ?>
  <html>
  <?PHP $fO->includeHead('New Appointment',0) ?>
	<script>
	function addrow(tableID) {
		var table = document.getElementById(tableID);
		var rowCount = table.rows.length;
		var row = table.insertRow(rowCount);
		var colCount = table.rows[0].cells.length;
		for(var i=0; i<colCount; i++) {
			var newcell = row.insertCell(i);
			newcell.innerHTML = table.rows[0].cells[i].innerHTML;
			if(i==0){
				newcell.childNodes[0].id="drug_"+rowCount;
			}
			if(i==1){
				newcell.childNodes[0].id="quantity_"+rowCount;
			}
		}
	}
	function remrow(tableID) {
		var table = document.getElementById(tableID);
		var rowCount = table.rows.length;
		if(rowCount>1)
		table.deleteRow(rowCount-1);
	}
	$(document).ready(function(){
	$(document).on("click", "#drug_search", function() {
	$(this).autocomplete({
 		source:function(request,response){
 	$.getJSON("searchDrug.php?term="+request.term,function(result){
	 response($.map(result,function(item){
	 	return{
		id:item.id,
		value:item.name,
		cost:item.cost,
		stock:item.stock
		}
	 }))
	})
 },
 select:function(event,ui){
	 $.ajax({
    type:"GET",
	  url:"new_appointment.php?add_prescription_drug="+ui.item.value,
	  async:false,
	  success:function(result){
	  $("#cart_form").submit();
	  }
	});
 },
 minLength:3,
  messages: {
        noResults: '',
        results: function() {}
    }
  });
	return false;
});
$('.dose').change(function(){
	var id=$(this).closest('tr').attr('id');
	var value=$(this).find('option:selected').text();
	$.ajax({
	 type:"GET",
	 url:"new_appointment.php?update_dose="+id+"&dose="+value,
	 success:function(result){
	 }
 });
});
$('.duration').change(function(){
	var id=$(this).closest('tr').attr('id');
	var value=$(this).find('option:selected').text();
	$.ajax({
	 type:"GET",
	 url:"new_appointment.php?update_duration="+id+"&duration="+value,
	 success:function(result){
	 }
 });
});
$(document).on("click", "#test_search", function() {
$(this).autocomplete({
	source:function(request,response){
$.getJSON("searchLabTest.php?term="+request.term,function(result){
 response($.map(result,function(item){
	return{
	id:item.id,
	value:item.test,
	cost:item.cost
	}
 }))
})
},
select:function(event,ui){
 $.ajax({
	type:"GET",
	url:"new_appointment.php?add_prescription_test="+ui.item.value,
	async:false,
	success:function(result){
	$("#cart_form").submit();
	}
});
},
minLength:3,
messages: {
			noResults: '',
			results: function() {}
	}
});
return false;
});
if(location.hash) {
		$('a[href="' + location.hash + '"]').tab('show');
}
$(document.body).on("click", "a[data-toggle]", function(event) {
		location.hash = this.getAttribute("href");
});
});
$(window).on('popstate', function() {
		var anchor = location.hash || $("a[data-toggle=tab]").first().attr("href");
		$('a[href=' + anchor + ']').tab('show');
});
	</script>
  </head>
  <body class="container">
    <?PHP $fO->includeMenu(3);
    ?>
  	<div id="menu_main">
      <a href="manage_doctor.php">Patient List</a>
  		<a href="appointment_list.php">Appointments</a>
  		<a href="new_appointment.php" id="item_selected">New Appointment</a>
      </div>
      <?php
			if(isset($_GET['SelectedPatient']) && isset($_GET['selectedEncounter']) && isset($_GET['patientType'])){
				$_SESSION['encounter']=$_GET['selectedEncounter'];
				$_SESSION['patient']=$_GET['SelectedPatient'];
				$_SESSION['patient_type']=$_GET['patientType'];
			}
			if(isset($_SESSION['patient']) && isset($_SESSION['encounter']) && isset($_SESSION['patient_type'])){
        $patient=$fO->getPatientByID($_SESSION['patient']);
				$encounter=$fO->getEncounterByID($_SESSION['encounter']);
				echo '<form class="form-signin" method="POST"  action="'.$_SERVER['PHP_SELF'].'" id="cart_form">';
				if (!isset($_SESSION['drugs'])){
					 $_SESSION['drugs'] = new Cart();
				}
				if(isset($_GET['edit_prescription'])){
				}
				if(isset($_GET['add_prescription_drug'])){
				$SearchString =$_GET['add_prescription_drug'];
				$drug=$fO->getSingleDrugByName($SearchString);
				$AlreadyOnThisCart =0;
				if (count($_SESSION['drugs']->LineItems)>0){
					   foreach ($_SESSION['drugs']->LineItems AS $OrderItem)
						    {
								$LineNumber = $_SESSION['drugs']->LineCounter;
						    if ($OrderItem->StockID ==$drug['id'])
								 {
							     $AlreadyOnThisCart = 1;
									 $fO->showMessage("drug already added on this list");
							    }
						   }
						 }
						if ($AlreadyOnThisCart!=1)
						{
							$_SESSION['drugs']->add_to_cart($drug['id'],$drug['stock'],$drug['name'],$drug['id'],$drug['id'],"","",-1);
						}

				}//end of if(isset($_POST['add_prescription_drug']))
				if (!isset($_SESSION['tests'])){
					 $_SESSION['tests'] = new LabCart ;
				}
				if(isset($_GET['add_prescription_test'])){
				$SearchString =$_GET['add_prescription_test'];
				$test=$fO->getSingleLabTestByName($SearchString);
				$AlreadyOnThisCart =0;
				if (count($_SESSION['tests']->LineItems)>0){
					   foreach ($_SESSION['tests']->LineItems AS $OrderItem)
						    {
								$LineNumber = $_SESSION['tests']->LineCounter;
						    if ($OrderItem->testID ==$test['lab_id'])
								 {
							     $AlreadyOnThisCart = 1;
									 $fO->showMessage("test already added on this list");
							    }
						   }
						 }
						if ($AlreadyOnThisCart!=1)
						{
							$_SESSION['tests']->add_to_lab_cart($test['lab_id'],$test['test'],$test['cost'],-1);
						}

				}//end of if(isset($_POST['add_prescription_test']))
				echo '</form>';
				if (isset($_GET['Delete']))
				{
					$_SESSION['drugs']->remove_from_cart($_GET['Delete']);
				}
				if (isset($_GET['update_dose']))
				{
					$_SESSION['drugs']->update_cart_dose($_GET['update_dose'],$_GET['dose']);
				}
				if (isset($_GET['update_duration']))
				{
					$_SESSION['drugs']->update_cart_duration($_GET['update_duration'],$_GET['duration']);
				}
				if (isset($_GET['delete_test']))
				{
					$_SESSION['tests']->remove_from_lab_cart($_GET['delete_test']);
				}
      ?>
      <form class="form-signin" method="POST"  action="<?php echo $_SERVER['PHP_SELF']?>">
        <h2 class="form-signin-heading">New Prescription</h2>
				<input type="hidden" name="patient_id" value="<?php echo $patient['patient_id']?>"/>
        <input type="text"  name="patient_name"  class="form-control"
        value="<?php echo $patient['patient_id'].' : '.$patient['p_name']; ?>" readonly=""><br>
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
				<h3 style="margin-top:0px;">Observation</h3>
        <textarea   name="prescription" class="prescription"  cols="15" rows="5" required>
        </textarea><br><br>
				<div class="form-inline">
					<ul class="nav nav-pills">
						<li class="active"><a data-toggle="tab" href="#pharmacy"><span class="glyphicon glyphicon-plus">Drug Order</span></a></li>
						<li><a data-toggle="tab" href="#lab"><span class="glyphicon glyphicon-plus">Lab Test Order</span></a></li>
						<li><div class="checkbox-inline"><label><input type="checkbox" name="admission" value="1">Admit Patient</label></div></li>
					</ul>
				</div>
				<div class="tab-content">
					<div id="pharmacy" class="tab-pane fade in active">
					<?php
					if (count($_SESSION['drugs']->LineItems)>0)
					{
					?>
				<table id="prescription_table" style="border-spacing:2px;border-collapse:separate;width:100%;">
        <tbody>
					<?php
					foreach ($_SESSION['drugs']->LineItems as $drugOrder)
					{
					?>
        <tr id="<?php echo $drugOrder->LineNumber ?>">
        <td><input type="text"  class="form-control drug" placeholder="Drug"
					 name="drug[]" id="drug_0" style="margin-right:20px;margin-top:10px;" value="<?php echo $drugOrder->ItemDescription ?>"
					 required readonly/>
				 </td>
				 <td><input type="text"  class="form-control" placeholder="Quantity in stock"
 					 name="quantity[]" id="quantity_0" style="margin-right:20px;margin-top:10px;" value="<?php echo $drugOrder->Quantity ?>" readonly/>
 				 </td>
				 <?php
				 echo '<td>';
				 $doses=$fO->getAllDose();
				 echo '<select class="form-control dose" id="dose[]" name="dose[]" style="margin-right:20px;margin-top:10px;">';
				 foreach($doses as $dose){
					 if($dose['name']==$drugOrder->dose){
						 echo '<option selected value="'.$dose['name'].'">'.$dose['name'].'</option>';
					 }
					 else{
						 echo '<option value="'.$dose['name'].'">'.$dose['name'].'</option>';
					 }

				 }
				 echo '</select></td>';
				 echo '<td>';
 				 $durations=$fO->getAllDurations();
 				 echo '<select class="form-control duration" id="duration[]" name="duration[]" style="margin-right:20px;margin-top:10px;">';
 				 foreach($durations as $duration){
					 if($duration['name']==$drugOrder->duration){
						 echo '<option selected value="'.$duration['name'].'">'.$duration['name'].'</option>';
					 }
					 else{
						 echo '<option value="'.$duration['name'].'">'.$duration['name'].'</option>';
					 }
 				 }
 				 echo '</select></td>';
				echo "<td><a href='".$_SERVER['PHP_SELF']."?"."Delete=".$drugOrder->LineNumber ."'><span class='glyphicon glyphicon-trash'></span></a></td>";
				echo '</tr>';
				 }?>
        </tbody>
        </table>
				<?php }
				echo '<br><input type="text" class="form-control" name="drug_search" id="drug_search"
				placeholder="Type the first three characters to display drug" />
				<div id="result"></div>';
				?>
			</div>
			<div id="lab" class="tab-pane fade">
				<?php
				if (count($_SESSION['tests']->LineItems)>0)
				{
					echo '<table id="lab_test_table" style="border-spacing:2px;border-collapse:separate;width:100%;">
	        <tbody>';
					foreach ($_SESSION['tests']->LineItems as $testOrder)
					{
						echo '<tr id="<?php echo $testOrder->LineNumber ?>">';
		        echo '<td><input type="text"  class="form-control drug" placeholder="Tets"
							 name="test[]" id="test_0" style="margin-right:20px;margin-top:10px;" value="'.$testOrder->ItemDescription.'"
							 required readonly/>
						 </td>';
						 echo '<td><input type="text"  class="form-control" placeholder="Cost"
		 					 name="cost[]" id="cost_0" style="margin-right:20px;margin-top:10px;" value="'. $testOrder->cost.'" readonly/>
		 				 </td>';
						 	echo "<td><a href='".$_SERVER['PHP_SELF']."?"."delete_test=".$testOrder->LineNumber ."'>
								<span class='glyphicon glyphicon-trash'></span></a></td>";
					 echo '</tr>';
					}
					echo '</tbody></table>';
				}
				echo '<br><input type="text" class="form-control" name="test_search" id="test_search"
				placeholder="Type the first three characters to display lab test" />
				<div id="result"></div>';
				?>
			</div>
		</div>
		<br>
		<input type="submit" name="save" class="btn btn-lg btn-primary"
		value="Save Prescription" style="display: block; margin: 0 auto;width:200px;"></input>
      </form>
      <?php } ?>
  </body>
  </html>
