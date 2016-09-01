<?PHP
	require 'functions.php';
	$fO=new functions();
	$fO->checkLogin();
  //Generate timestamp
	$timestamp = time();
	//CREATE-Button
	if (isset($_POST['dispense'])){
		$i=0;
		foreach($_POST['drug'] as $value) {
			if(isset($_POST['drug'][$i]) && isset($_POST['dose'][$i]) && isset($_POST['duration'][$i])
			&& isset($_POST['quantity'][$i]) && isset($_POST['amount'][$i]) && isset($_SESSION['prescription'])){
				$fO->dispensePrescription($_SESSION['prescription'],$_POST['drug'][$i],$_POST['dose'][$i],$_POST['duration'][$i],
			$_POST['quantity'][$i],$_POST['amount'][$i],$_SESSION['log_user']);
			}
			$i++;
		}
	}
  ?>
  <html>
  <?PHP $fO->includeHead('Dispense Drugs',0) ?>
	<script>
	function addrow(tableID) {
		var table = document.getElementById(tableID);
		var rowCount = table.rows.length;
		var row = table.insertRow(rowCount);
		var colCount = table.rows[0].cells.length;
		for(var i=0; i<colCount; i++) {
			var newcell = row.insertCell(i);
			newcell.innerHTML = table.rows[0].cells[i].innerHTML;
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
		value:item.name
		}
	 }))
	})
 },
 select:function(event,ui){
	 //$(this).attr('readonly', true);
 },
 minLength:3,
  messages: {
        noResults: '',
        results: function() {}
    }
  });
});
$(".quantity").change(function(){
	var totalAmount=0;
	var quantityId=$(this).attr("id");
	var id=quantityId.substring(quantityId.indexOf("_")+1);
	document.getElementById("amount_"+id).value=parseInt($(this).val())*parseInt($("#price_"+id).val());
	$(".amount").each(function(){
		totalAmount +=Number($(this).val());
	});
	document.getElementById("total").value=totalAmount;
	document.getElementById("balance").value=parseInt($("#paid").val())-parseInt($("#total").val());
});
$("#paid").change(function(){
	document.getElementById("balance").value=parseInt($(this).val())-parseInt($("#total").val());
});

});
	</script>
  </head>
  <body class="container">
    <?PHP $fO->includeMenu(5);
    ?>
  	<div id="menu_main">
      <a href="manage_pharmacy.php">Patient Queue</a>
  		<a href="pharmacy_dispense.php" id="item_selected">Dispense</a>
      </div>
      <?php if(isset($_GET['selectedPrescription']) && isset($_GET['selectedPatient'])){
        $selectedPatientId=$_GET['selectedPatient'];
        $patient=$fO->getPatientByID($selectedPatientId);
        $observation=$fO->getPrescriptionById($_GET['selectedPrescription']);
        $drugs=$fO->getPrescriptionDetailsById($_GET['selectedPrescription']);
				$_SESSION['prescription']=$_GET['selectedPrescription'];
      ?>
      <form class="form-signin" method="POST"  action="<?php echo $_SERVER['PHP_SELF']?>">
        <h2 class="form-signin-heading">Dispense Prescription</h2>
				<input type="hidden" name="patient_id" value="<?php echo $patient['patient_id']?>"/>
        <input type="text"  name="patient_name"  class="form-control"
        value="<?php echo $patient['patient_id'].' : '.$patient['p_name']; ?>" readonly=""><br>
				<h3 style="margin-top:0px;">Observation</h3>
        <textarea   name="observation" class="prescription"  cols="15" rows="5" readonly="">
          <?php echo $observation['observation']?>
        </textarea><br>
				<table id="prescription_table" style="border-spacing:2px;border-collapse:separate;width:100%;">
        <tbody>
        <?php if(sizeof($drugs)>0){
					$i=0;
        foreach($drugs as $drug){
        ?>
        <tr>
        <td><input type="text"  class="form-control drug" placeholder="Drug" value="<?php echo $drug['medicine_name']?>"
					 style="margin-right:20px;margin-top:10px;" required readonly=""/></td>
					<input type="hidden" name="drug[]"  value="<?php echo $drug['med_id']?>" />
					<td><input type="text"  class="form-control" value="<?php echo $drug['doseName']?>"
						 name="dose[]" readonly required style="margin-right:20px;margin-top:10px;"/></td>
					<td><input type="text" class="form-control" value="<?php echo $drug['durName']?>"
		 				 name="duration[]" readonly required style="margin-right:20px;margin-top:10px;"/></td>
				<td><input type="text" class="form-control" value="<?php echo $drug['stock_available'] ?>"
					style="margin-right:20px;margin-top:10px;" readonly=""/></td>
					<td><input type="number"  class="form-control quantity" placeholder="Quantity"
  					 name="quantity[]" id='quantity_<?php echo $i ?>' style="margin-right:20px;margin-top:10px;" required /></td>
        <td><input type="text"  class="form-control" placeholder="Price" value="<?php echo $drug['cost'] ?>"
  					 id='price_<?php echo $i ?>' style="margin-right:20px;margin-top:10px;" readonly="" /></td>
				<td><input type="number"  class="form-control amount" placeholder="Amount" id='amount_<?php echo $i ?>'
				name="amount[]" style="margin-right:20px;margin-top:10px;" required /></td>
				</tr>
        </tbody>
        <?php $i++;} }?>
        </table>
				<div class="form-inline" style="float:right;">
					<label for="total">Total</label>
					<input type="number" class="form-control" name="total" id="total" value="0" readonly=""/>
					<label for="total">Amount Paid</label>
					<input type="number" class="form-control" id="paid" name="paid"  />
					<label for="total">Balance</label>
					<input type="number" class="form-control" id="balance" name="balance" readonly=""/>
				</div>
				<div style="clear: both;">
				</div>
				<br><br>
        <input type="submit" name="dispense" class="btn btn-lg btn-primary"
				value="Dispense" style="display: block; margin: 0 auto;width:200px;"></input>
      </form>
      <?php } ?>
  </body>
  </html>
