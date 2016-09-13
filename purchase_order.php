<?PHP
	require 'functions.php';
	require 'purchaseOrderCart.php';
	$fO=new functions();
	$fO->checkLogin();
	if (isset($_POST['save'])){
		if(isset($_POST['entry_date']) && isset($_POST['supplier'])){
			$entry_date=date('Y-m-d',strtotime($_POST['entry_date']));
			$lastId=$fO->savePurchaseOrder($entry_date,$_POST['supplier']);
		$i=0;
		foreach($_POST['product'] as $value) {
			if(isset($_POST['product'][$i]) && isset($_POST['quantity'][$i]) && isset($_POST['expiry_date'][$i]) && isset($_POST['batch_no'][$i])){
				$expiry_date=date('Y-m-d',strtotime($_POST['expiry_date'][$i]));
				$fO->saveInventory($lastId,$_POST['product'][$i],$_POST['quantity'][$i],$expiry_date,$_POST['batch_no'][$i]);
			}
			$i++;
		}
	}
	}
  ?>
  <html>
  <?PHP $fO->includeHead('Purchase Order',0) ?>
	<script>
	$(document).ready(function(){
		var today = new Date();
		var dd = today.getDate();
		var mm = today.getMonth()+1; //January is 0!
		var yyyy = today.getFullYear();
		if(dd<10) {
		    dd='0'+dd
		}
		if(mm<10) {
		    mm='0'+mm
		}
		today = mm+'/'+dd+'/'+yyyy;

    $(document).on('focus','.myDate', function(){
      var $jthis = $(this);
      if(!$jthis.data('datepicker')) {
        $jthis.removeClass("hasDatepicker");
        $jthis.datepicker();
        $jthis.datepicker("show");
      }
    });
		$(document).on("click", "#product_search", function() {
		$(this).autocomplete({
	 		source:function(request,response){
	 	$.getJSON("autocomplete_product.php?term="+request.term,function(result){
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
		 $.ajax({
	    type:"GET",
		  url:"purchase_order.php?add_cart="+ui.item.value,
		  async:false,
		  success:function(result){
		  $("#purchase_order_cart_form").submit();
		  }
		});
	 },
	 minLength:3,
	  messages: {
	      noResults: '',
	      results: function(){}
	    }
	  });
		return false;
	});
$("#entry_date").datepicker();
$("#entry_date").change(function(){
	$.ajax({
	 type:"GET",
	 url:"purchase_order.php?set_date="+$(this).val(),
	 async:false,
	 success:function(result){}
 });
});
$(".batchNo").change(function(){
	var extract=$(this).attr("id");
	var id=extract.substring(extract.indexOf("_")+1);
	var value=$(this).val();
	$.ajax({
	 type:"GET",
	 url:"purchase_order.php?set_batch="+id+"&batchNo="+$(this).val(),
	 async:false,
	 success:function(result){}
 });
});
$(".mydate").change(function(){
	var extract=$(this).attr("id");
	var id=extract.substring(extract.indexOf("_")+1);
	var value=$(this).val();
	$.ajax({
	 type:"GET",
	 url:"purchase_order.php?set_expiry_date="+id+"&expiry_date="+value,
	 async:false,
	 success:function(result){}
 });
});
$("#supplier").change(function(){
	$.ajax({
	 type:"GET",
	 url:"purchase_order.php?set_supplier="+$(this).val(),
	 async:false,
	 success:function(result){}
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
	var value=$(this).val();
	$.ajax({
	 type:"GET",
	 url:"purchase_order.php?update_cart="+id+"&quantity="+value,
	 success:function(result){
	 }
 });
	document.getElementById("total").value=totalAmount;
});
$(document).on("click", "#save", function(e) {
		bootbox.confirmation("Are you sure?", function() {
		});
});
var tAmount=0;
var $lineTotal=0;
$(".amount").each(function(){
	$lineTotal=0;
	var amountId=$(this).attr("id");
	var id=amountId.substring(amountId.indexOf("_")+1);
	$lineTotal=parseInt($("#quantity_"+id).val())*parseInt($("#price_"+id).val());
	document.getElementById("amount_"+id).value=$lineTotal;
	tAmount +=$lineTotal;
});
document.getElementById("total").value=tAmount;
});
	</script>
  </head>
  <body class="container">
    <?PHP $fO->includeMenu(2);
    ?>
  	<div id="menu_main">
			<a href="manage_inventory.php">Product List</a>
			<a href="product_details.php">Product Details</a>
			<a href="purchase_order_list.php">Purchase Order List</a>
			<a href="purchase_order.php" id="item_selected">Purchase Order</a>
      </div>
			<?php
			echo '<form class="form-signin" method="POST"  action="'.$_SERVER['PHP_SELF'].'" id="purchase_order_cart_form">';
				if (!isset($_SESSION['purchaseOrder'])){
					 $_SESSION['purchaseOrder'] = new PurchaseOrderCart();
				}
				if(isset($_GET['edit_cart'])){
				}
				if(isset($_GET['add_cart'])){
				$SearchString =$_GET['add_cart'];
				$product=$fO->getProductByName($SearchString);
				$AlreadyOnThisCart =0;
				echo 'product is '.$product['name'];
				$quantity=1;
				if (count($_SESSION['purchaseOrder']->LineItems)>0){
					   foreach ($_SESSION['purchaseOrder']->LineItems AS $OrderItem)
						    {
								$LineNumber = $_SESSION['purchaseOrder']->LineCounter;
						    if ($OrderItem->productID ==$product['id'])
								 {
							     $AlreadyOnThisCart = 1;
									 $fO->showMessage("product already added on this list");
							    }
						   }
						 }
						if ($AlreadyOnThisCart!=1)
						{
							$_SESSION['purchaseOrder']->add_to_cart($product['id'],$quantity,$product['name'],$product['buying_price'],'','ABD',-1);
						}
				}//end of if(isset($_POST['add_cart]))
				echo '</form>';
				if (isset($_GET['Delete']))
				{
					$_SESSION['purchaseOrder']->remove_from_cart($_GET['Delete']);
				}
				if (isset($_GET['update_cart']) && isset($_SESSION['purchaseOrder']))
				{
					$_SESSION['purchaseOrder']->update_cart($_GET['update_cart'],$_GET['quantity']);
				}
				if (isset($_GET['set_date']) && isset($_SESSION['purchaseOrder']))
				{
					$_SESSION['purchaseOrder']->setDeliveryDate($_GET['set_date']);
				}
				if (isset($_GET['set_supplier']) && isset($_SESSION['purchaseOrder']))
				{
					$_SESSION['purchaseOrder']->setSupplier($_GET['set_supplier']);
				}
				if (isset($_GET['set_batch']) && isset($_SESSION['purchaseOrder']))
				{
					$_SESSION['purchaseOrder']->setLineItemBatchNo($_GET['set_batch'],$_GET['batchNo']);
				}
				if (isset($_GET['set_expiry_date']) && isset($_SESSION['purchaseOrder']))
				{
					$_SESSION['purchaseOrder']->setLineItemExpiryDate($_GET['set_expiry_date'],$_GET['expiry_date']);
				}
				if(isset($_GET['SelectedOrder'])){
					$client_username=$fO->getClientByOrderId($_GET['SelectedOrder']);
					$_SESSION['client']=$client_username['client'];
					$_SESSION['existing_order']=$_GET['SelectedOrder'];
					if(!isset($_SESSION['purchaseOrder'])){
					$_SESSION['purchaseOrder'] = new PurchaseOrderCart();
					$purchaseOrder=$fO->getPurchaseOrderById($_GET['SelectedOrder']);
					$orderProducts=$fO->getSalesOderDetailsByOrderId($purchaseOrder['purchase_order_id']);
					$_SESSION['purchaseOrder']->orderDate=$purchaseOrder['date_required'];
					foreach($orderProducts as $product){
						$_SESSION['purchaseOrder']->add_to_cart($product['id'],0,$product['name'],$product['price'],0,$product['quantity_delivered'],$product['quantity'],-1);
					}
				}
				}

			 ?>
      <form class="form-signin" method="POST"  action="<?php echo $_SERVER['PHP_SELF']?>">
        <h2 class="form-signin-heading">Purchase Order</h2>
        <label for="entry_date">Delivery Date</label>
        <input type="text"  class="form-control" placeholder="Entry Date" value="<?php echo $_SESSION['purchaseOrder']->orderDate; ?>"
					 name="entry_date" id="entry_date" style="margin-right:20px;margin-top:10px;"  required=""/>
					 <label for="supplier">Supplier</label>
        <select  name="supplier" id="supplier" class="form-control" required>
          <option disabled selected>Select Supplier</option>
					<?php
					$suppliers=$fO->getAllSuppliesrs();
					foreach($suppliers as $supplier){
						if($_SESSION['purchaseOrder']->supplier==$supplier['id']){
							echo '<option selected value="'.$supplier['id'].'">'.$supplier['name'].'</option>';
						}
						else{
							echo '<option value="'.$supplier['id'].'">'.$supplier['name'].'</option>';
						}
						}
					 ?>
        </select>
				<?php
					if (count($_SESSION['purchaseOrder']->LineItems)>0)
					{
				?>
				<table id="purchase_order_table" style="border-spacing:2px;border-collapse:separate;width:100%;">
					<thead>
            <tr><th>Product</th>
							<th>Expiry Date</th>
							<th>Batch No</th>
							<th>Quantity</th>
							<th>Price</th>
							<th>Amount</th>
						</tr>
          </head>
        <tbody>
					<?php
					foreach ($_SESSION['purchaseOrder']->LineItems as $order)
					{
					?>
        <tr id="<?php echo $order->LineNumber ?>">
        <input type="hidden"  class="form-control" placeholder="Product" name="product[]"  value="<?php echo $order->productID ?>"/>
				<td><input type="text"  class="form-control drug" placeholder="Product"
 				name="name[]" style="margin-right:20px;margin-top:10px;" value="<?php echo $order->ItemDescription ?>"
 				required readonly/>
 				 </td>
				 <td><input type="text"  class="form-control myDate expiryDate" placeholder="Expiry Date" value="<?php echo $order->expiryDate ?>"
 					 name="expiry_date[]" id="expiry_<?php echo $order->LineNumber ?>" style="margin-right:20px;margin-top:10px;" readonly=""/>
 				 </td>
         <td><input type="text"  class="form-control batchNo" placeholder="Batch No" value="<?php echo $order->batchNo?>"
 					 name="batch_no[]" id="batch_<?php echo $order->LineNumber ?>" style="margin-right:20px;margin-top:10px;"/>
 				 </td>
				 <td><input type="number"  class="form-control quantity" placeholder="Quantity"
 					 name="quantity[]" id="quantity_<?php echo $order->LineNumber ?>" style="margin-right:20px;margin-top:10px;"
					 value="<?php echo $order->Quantity ?>" required="" />
 				 </td>
				 <td><input type="text"  class="form-control" placeholder="Price"
 					 name="price[]" id="price_<?php echo $order->LineNumber ?>" style="margin-right:20px;margin-top:10px;" value="<?php echo $order->Price ?>" readonly/>
 				 </td>
				 <td><input type="text"  class="form-control amount" placeholder="Amount"
 					 name="amount[]" id="amount_<?php echo $order->LineNumber ?>" style="margin-right:20px;margin-top:10px;" required=""/>
 				 </td>
				 <?php
				 echo "<td><a href='".$_SERVER['PHP_SELF']."?"."Delete=".$order->LineNumber ."'><span class='glyphicon glyphicon-trash'></span></a></td>";
				echo '</tr>';
			 }?>
        </tbody>
        </table>
				<?php }
				echo '<div class="form-inline" style="float:right;">
				<label for="total">Total Amount</label>
				<input type="number" class="form-control" name="total" id="total" value="0" readonly=""/>
				</div>
				<div style="clear:both;"></div>';
				echo '<br><input type="text" class="form-control" name="product_search" id="product_search"
				placeholder="Type three characters to display product" />
				<div id="result"></div>';
				?>
				<br><br>
				<button type="submit" name="receive" id="order" class="btn btn-lg btn-primary"
					style="display: block; margin: 0 auto;width:200px;"><span class="fa fa-times"></span>Receive Order</button>
      </form>
  </body>
  </html>
