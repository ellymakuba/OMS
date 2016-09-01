<?PHP
	require 'functions.php';
	$fO=new functions();
	$fO->checkLogin();
	if (isset($_POST['save'])){
		if(isset($_POST['entry_date']) && isset($_POST['supplier'])){
			$entry_date=date('Y-m-d',strtotime($_POST['entry_date']));
			$lastId=$fO->savePurchaseOrder($entry_date,$_POST['supplier']);
		$i=0;
		foreach($_POST['drug'] as $value) {
			if(isset($_POST['drug'][$i]) && isset($_POST['quantity'][$i]) && isset($_POST['expiry_date'][$i]) && isset($_POST['batch_no'][$i])){
				$expiry_date=date('Y-m-d',strtotime($_POST['expiry_date'][$i]));
				$fO->saveInventory($lastId,$_POST['drug'][$i],$_POST['quantity'][$i],$expiry_date,$_POST['batch_no'][$i]);
			}
			$i++;
		}
	}
	}
  ?>
  <html>
  <?PHP $fO->includeHead('Manage Inventory',0) ?>
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
			newcell.innerHTML = table.rows[1].cells[i].innerHTML;
			if(i==0){
				newcell.childNodes[0].id="drug_"+rowCount;
			}
			if(i==1){
				newcell.childNodes[0].id="quantity_"+rowCount;
			}
      if(i==2){
				newcell.childNodes[0].id="expiry_date_"+rowCount;
			}
		}
	}
	function remrow(tableID) {
		var table = document.getElementById(tableID);
		var rowCount = table.rows.length;
		if(rowCount>2)
		table.deleteRow(rowCount-1);
	}
	$(document).ready(function(){
    $(document).on('focus','.myDate', function() {
      var $jthis = $(this);
      if(!$jthis.data('datepicker')) {
        $jthis.removeClass("hasDatepicker");
        $jthis.datepicker();
        $jthis.datepicker("show");
      }
    });
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
 },
 minLength:3,
  messages: {
        noResults: '',
        results: function() {}
    }
  });
});
$('#purchase_order_table').delegate(' tr td a', 'click', function(e){
    try {
        var table = document.getElementById("purchase_order_table");
        var rowCount = table.rows.length;
        if(rowCount <= 2) {
            alert("Cannot delete all the rows.");
            //break;
        }
        else{
            $(this).closest('tr').remove();
        }
    }catch(e) {
        alert(e);
    }
});
$("#entry_date").datepicker();
});
$(document).on("click", "#save", function(e) {
		bootbox.confirmation("Are you sure?", function() {
		});
});
	</script>
  </head>
  <body class="container">
    <?PHP $fO->includeMenu(8);
    ?>
  	<div id="menu_main">
      <a href="manage_inventory.php" id="item_selected">Purchase Order</a>
      </div>
      <form class="form-signin" method="POST"  action="<?php echo $_SERVER['PHP_SELF']?>">
        <h2 class="form-signin-heading">New Purchase Order</h2>
        <label for="entry_date">Entry Date</label>
        <input type="text"  class="form-control" placeholder="Entry Date"
					 name="entry_date" id="entry_date" style="margin-right:20px;margin-top:10px;" required readonly=""/>
           <label for="supplier">Supplier</label>
        <select  name="supplier" class="form-control" required>
          <option disabled selected>Select Supplier</option>
					<?php
					$suppiers=$fO->getAllSuppliesrs();
					foreach($suppiers as $suppier){
					echo '<option value="'.$suppier['id'].'">'.$suppier['name'].'</option>';
						}
					 ?>
        </select>
				<table id="purchase_order_table" style="border-spacing:2px;border-collapse:separate;width:100%;">
          <thead>
            <tr><th>Drug</th><th>Quantity</th><th>Expiry Date</th><th>Batch No</th><th></th></tr>
          </head>
        <tbody>
        <tr>
        <td><input type="text"  class="form-control drug" placeholder="Drug"
					 name="drug[]" id="drug_0" style="margin-right:20px;margin-top:10px;" required />
				 </td>
				 <td><input type="text"  class="form-control" placeholder="Quantity"
 					 name="quantity[]" id="quantity_0" style="margin-right:20px;margin-top:10px;"/>
 				 </td>
         <td><input type="text"  class="form-control myDate" placeholder="Expiry Date"
 					 name="expiry_date[]" id="expiry_date_0" style="margin-right:20px;margin-top:10px;" readonly=""/>
 				 </td>
         <td><input type="text"  class="form-control" placeholder="Batch No"
 					 name="batch_no[]" style="margin-right:20px;margin-top:10px;"/>
 				 </td>
         <td><a href="#"><span class="glyphicon glyphicon-trash"></span> </span></a></td>
				</tr>
        </tbody>
        </table>
				<div class="col-sm-3 col-md-3 pull-left" style="margin-top:10px;">
				<span class="btn btn-default btn-primary btn-block"  onclick="addrow('purchase_order_table')">
					<span class="glyphicon glyphicon-plus"></span></span>
				</div>
					<div class="col-sm-3 col-md-3 pull-right" style="margin-top:10px;">
					<span class="btn btn-default btn-primary btn-block"  onclick="remrow('purchase_order_table')">
						<span class="glyphicon glyphicon-minus"></span> </span>
				</div>
				<br><br>
        <button type="submit" name="save" id="save" class="btn btn-lg btn-primary"
				value="Save" style="display: block; margin: 0 auto;width:200px;"><span class="fa fa-times"></span>Save</button>
      </form>
  </body>
  </html>
