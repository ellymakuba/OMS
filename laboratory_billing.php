<?PHP
	require 'functions.php';
	$fO=new functions();
	$fO->checkLogin();
	if (isset($_POST['bill'])){
		$i=0;
		if(isset($_POST['test'])){
		foreach($_POST['test'] as $value) {
			if(isset($_POST['test'][$i]) && isset($_POST['cost'][$i]) && isset($_POST['result'][$i])){
				$fO->billLabTestOrder($_SESSION['prescription'],$_POST['test'][$i],$_POST['cost'][$i],$_POST['result'][$i],$_SESSION['log_user']);
			}
			$i++;
		}
		}
		$fO->laboratoryBilled($_SESSION['prescription']);
		header('Location:manage_laboratory.php');
	}
  ?>
  <html>
  <?PHP $fO->includeHead('Bill Drugs',0) ?>
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
$(".test").click(function(){
	var totalAmount=0;
	$(".test").each(function(){
		if($(this).is(':checked')) {
			var testId=$(this).attr("id");
			var id=testId.substring(testId.indexOf("_")+1);
			totalAmount +=Number($('#cost_'+id).val());
		}
	});
	document.getElementById("total").value=totalAmount;
});
});
	</script>
  </head>
  <body class="container">
    <?PHP $fO->includeMenu(4);
    ?>
  	<div id="menu_main">
      <a href="manage_laboratory.php">Lab Test Orders</a>
  		<a href="laboratory_billing.php" id="item_selected">Billing</a>
      </div>
      <?php if(isset($_GET['selectedPrescription']) && isset($_GET['selectedPatient'])){
        $selectedPatientId=$_GET['selectedPatient'];
        $patient=$fO->getPatientByID($selectedPatientId);
        $observation=$fO->getPrescriptionById($_GET['selectedPrescription']);
        $tests=$fO->getLabOrderByPrescriptionId($_GET['selectedPrescription']);
				$_SESSION['prescription']=$_GET['selectedPrescription'];
      ?>
      <form class="form-signin" method="POST"  action="<?php echo $_SERVER['PHP_SELF']?>">
        <h2 class="form-signin-heading">Laboratory Billing</h2>
				<input type="hidden" name="patient_id" value="<?php echo $patient['patient_id']?>"/>
        <input type="text"  name="patient_name"  class="form-control"
        value="<?php echo $patient['patient_id'].' : '.$patient['p_name']; ?>" readonly=""><br>
				<h3 style="margin-top:0px;">Observation</h3>
        <textarea   name="observation" class="prescription"  cols="15" rows="5" readonly="">
          <?php echo $observation['observation']?>
        </textarea><br>
				<table id="prescription_table" style="border-spacing:2px;border-collapse:separate;width:100%;">
					<thead>
						<tr>
							<th>#</th><th>Test</th><th>Cost</th><th>Result</th>
						</tr>
					</thead>
        <tbody>
        <?php if(count($tests)>0){
					$i=0;
        foreach($tests as $test){
        ?>
        <tr>
        <td><input type="checkbox" name="test[]" id="test_<?php echo $i?>" class="form-control test" value="<?php echo $test['lab_id']?>" /></td>
					<td><input type="text"  class="form-control" placeholder="Test" value="<?php echo $test['test']?>"
					 style="margin-right:20px;margin-top:10px;" required readonly=""/></td>
					<td><input type="text" id="cost_<?php echo $i?>"  class="form-control cost" value="<?php echo $test['cost']?>"
						 name="cost[]" readonly required style="margin-right:20px;margin-top:10px;"/></td>
				<td><input type="text" class="form-control" name="result[]" value="" style="margin-right:20px;margin-top:10px;"/></td>
				</tr>
        </tbody>
        <?php $i++;} }?>
        </table>
				<div class="form-inline" style="float:right;">
					<label for="total">Total</label>
					<input type="number" class="form-control" name="total" id="total" value="0" readonly=""/>
				</div>
				<div style="clear: both;">
				</div>
				<br><br>
        <input type="submit" name="bill" class="btn btn-lg btn-primary"
				value="Bill Lab" style="display: block; margin: 0 auto;width:200px;"></input>
      </form>
      <?php } ?>
  </body>
  </html>
