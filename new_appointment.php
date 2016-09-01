<?PHP
	require 'functions.php';
	$fO=new functions();
	$fO->checkLogin();
	if (isset($_POST['save'])){
		$i=0;
		$lastId=$fO->savePrescription($_POST['patient_id'],$_POST['prescription'],$_SESSION['log_user'],$_SESSION['encounter']);
		foreach($_POST['drug'] as $value) {
			if(isset($_POST['drug'][$i]) && isset($_POST['dose'][$i]) && isset($_POST['duration'][$i])){
				$fO->savePrescriptionDetails($lastId,$_POST['drug'][$i],$_POST['dose'][$i],$_POST['duration'][$i]);
			}
			$i++;
		}
	}
  ?>
  <html>
  <?PHP $fO->includeHead('New Appointment',0) ?>
	<script>
	var autocomp_opt={
	 source:function(request,response){
		 $.getJSON("searchDrug.php?term="+request.term,function(result){
		response($.map(result,function(item){
		 return{
		 id:item.id,
		 value:item.name,
		 cost:item.cost
		 }
		}))
	 })
	 },
	 select:function(event,ui){
	 },
	 minLength:3,
		messages: {
					noResults: '',
					results: function() {}
			}
	};
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
	$(document).on("click", ".drug", function() {
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
	 //$(this).attr('readonly', true);
	 var drugId=$(this).attr('id');
	 var id=drugId.substring(drugId.indexOf("_")+1);
	 document.getElementById("quantity_"+id).value=ui.item.stock;
 },
 minLength:3,
  messages: {
        noResults: '',
        results: function() {}
    }
  });
});
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
      <?php if(isset($_GET['SelectedPatient']) && isset($_GET['selectedEncounter'])){
        $selectedPatientId=$_GET['SelectedPatient'];
        $patient=$fO->getPatientByID($selectedPatientId);
				$_SESSION['encounter']=$_GET['selectedEncounter'];
				$encounter=$fO->getEncounterByID($_SESSION['encounter']);
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
        </textarea><br>
				<table id="prescription_table" style="border-spacing:2px;border-collapse:separate;width:100%;">
        <tbody>
        <tr>
        <td><input type="text"  class="form-control drug" placeholder="Drug"
					 name="drug[]" id="drug_0" style="margin-right:20px;margin-top:10px;" required />
				 </td>
				 <td><input type="text"  class="form-control" placeholder="Quantity in stock"
 					 name="quantity[]" id="quantity_0" style="margin-right:20px;margin-top:10px;" readonly=""/>
 				 </td>
				 <?php
				 echo '<td>';
				 $doses=$fO->getAllDose();
				 echo '<select class="form-control" id="dose[]" name="dose[]" style="margin-right:20px;margin-top:10px;">';
				 foreach($doses as $dose){
					 echo '<option value="'.$dose['id'].'">'.$dose['name'].'</option>';
				 }
				 echo '</select></td>';
				 echo '<td>';
 				 $durations=$fO->getAllDurations();
 				 echo '<select class="form-control" id="duration[]" name="duration[]" style="margin-right:20px;margin-top:10px;">';
 				 foreach($durations as $duration){
 					 echo '<option value="'.$duration['id'].'">'.$duration['name'].'</option>';
 				 }
 				 echo '</select></td>';
				 ?>
				</tr>
        </tbody>
        </table>
				<div class="col-sm-3 col-md-3 pull-left" style="margin-top:10px;">
				<span class="btn btn-default btn-primary btn-block"  onclick="addrow('prescription_table')">
					<span class="glyphicon glyphicon-plus"></span></span>
				</div>
					<div class="col-sm-3 col-md-3 pull-right" style="margin-top:10px;">
					<span class="btn btn-default btn-primary btn-block"  onclick="remrow('prescription_table')">
						<span class="glyphicon glyphicon-minus"></span> </span>
				</div>
				<br><br>
        <input type="submit" name="save" class="btn btn-lg btn-primary"
				value="Save Prescription" style="display: block; margin: 0 auto;width:200px;"></input>
      </form>
      <?php } ?>
  </body>
  </html>
