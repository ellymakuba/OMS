<?PHP
	require 'functions.php';
	require 'salesOrderCart.php';
	$fO=new functions();
	$fO->checkLogin();
	if (isset($_POST['order'])){
		if(isset($_POST['entry_date']) && count($_SESSION['salesOrder'])>0){
			$entry_date=date('Y-m-d',strtotime($_POST['entry_date']));
			if(isset($_SESSION['existing_order'])){
				$i=0;
				$fO->updateClientOrderById($entry_date,$_SESSION['existing_order']);
				$fO->removeSalesOderDetailsByOrderId($_SESSION['existing_order']);
				foreach($_POST['product'] as $value) {
					if(isset($_POST['product'][$i]) && isset($_POST['quantity'][$i]) && isset($_POST['price'][$i]) && isset($_POST['amount'][$i])){
						$fO->saveClientOrderDetails($_SESSION['existing_order'],$_POST['product'][$i],$_POST['quantity'][$i],$_POST['price'][$i]
						,$_POST['amount'][$i],$_POST['discount'][$i]);
					}
			 	$i++;
		 		}
				unset($_SESSION['existing_order']);
			}
			else{
			$lastId=$fO->saveSalesOrder($entry_date,$_SESSION['log_user']);
			$i=0;
			foreach($_POST['product'] as $value) {
			if(isset($_POST['product'][$i]) && isset($_POST['quantity'][$i]) && isset($_POST['price'][$i]) && isset($_POST['amount'][$i])){
				$fO->saveClientOrderDetails($lastId,$_POST['product'][$i],$_POST['quantity'][$i],$_POST['price'][$i],$_POST['amount'][$i],$_POST['discount'][$i]);
			}
			$i++;
			}
			unset($_SESSION['salesOrder']);
		}
		header('Location:client_orders.php');
	}
	}
  ?>
  <html>
  <?PHP $fO->includeHead('Sales Order',0) ?>
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
		$(document).on("click", "#product_search", function() {
		$(this).autocomplete({
	 		source:function(request,response){
	 	$.getJSON("searchInventory.php?term="+request.term,function(result){
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
		  url:"client_order.php?add_cart="+ui.item.value,
		  async:false,
		  success:function(result){
		  $("#sales_order_cart_form").submit();
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
$("#entry_date").datepicker();
$("#entry_date").change(function(){
	$.ajax({
	 type:"GET",
	 url:"client_order.php?set_date="+$(this).val(),
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
	 url:"client_order.php?update_cart="+id+"&quantity="+value,
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
	$lineTotal=parseInt($("#quantity_"+id).val())*parseInt($("#price_"+id).val())-parseInt($("#discount_"+id).val());
	document.getElementById("amount_"+id).value=$lineTotal;
	tAmount +=$lineTotal;
});
document.getElementById("total").value=tAmount;
var discount_sum=0;
$(".discount").each(function(){
	discount_sum +=Number($(this).val());
});
document.getElementById("discount_sum").value=discount_sum;
});
	</script>
  </head>
  <body class="container">
    <?PHP $fO->includeMenu(1);
    ?>
  	<div id="menu_main">
      <a href="client_orders.php">Client Orders List</a>
	    <a href="client_order.php" id="item_selected">New Order</a>
      </div>
			<?php
			echo '<form class="form-signin" method="POST"  action="'.$_SERVER['PHP_SELF'].'" id="sales_order_cart_form">';
				if (!isset($_SESSION['salesOrder']) && !isset($_GET['SelectedOrder'])){
					 $_SESSION['salesOrder'] = new Cart();
				}
				if(isset($_GET['edit_cart'])){
				}
				if(isset($_GET['add_cart'])){
				$SearchString =$_GET['add_cart'];
				$product=$fO->getInventoryItemByName($SearchString);
				$AlreadyOnThisCart =0;
				$quantity=1;
				if (count($_SESSION['salesOrder']->LineItems)>0){
					   foreach ($_SESSION['salesOrder']->LineItems AS $OrderItem)
						    {
								$LineNumber = $_SESSION['salesOrder']->LineCounter;
						    if ($OrderItem->productID ==$product['id'])
								 {
							     $AlreadyOnThisCart = 1;
									 $fO->showMessage("product already added on this list");
							    }
						   }
						 }
						if ($AlreadyOnThisCart!=1)
						{
							$_SESSION['salesOrder']->add_to_cart($product['id'],$quantity,$product['name'],$product['selling_price'],0,0,0,-1);
						}
				}//end of if(isset($_POST['add_cart]))
				echo '</form>';
				if (isset($_GET['Delete']))
				{
					$_SESSION['salesOrder']->remove_from_cart($_GET['Delete']);
				}
				if (isset($_GET['update_cart']))
				{
					$_SESSION['salesOrder']->update_cart($_GET['update_cart'],$_GET['quantity']);
				}
				if (isset($_GET['set_date']))
				{
					$_SESSION['salesOrder']->setDeliveryDate($_GET['set_date']);
				}
				if(isset($_GET['SelectedOrder'])){
					$_SESSION['existing_order']=$_GET['SelectedOrder'];
					if(!isset($_SESSION['salesOrder'])){
						$_SESSION['salesOrder'] = new Cart();
						$salesOrder=$fO->getSalesOrderById($_GET['SelectedOrder']);
						$orderProducts=$fO->getSalesOderDetailsByOrderId($salesOrder['sales_order_id']);
						$_SESSION['salesOrder']->orderDate=$salesOrder['date_required'];
					foreach($orderProducts as $product){
						$_SESSION['salesOrder']->add_to_cart($product['id'],$product['quantity'],$product['name'],$product['price'],0,0,0,-1);
					}
				}
				}
			 ?>
      <form class="form-signin" method="POST"  action="<?php echo $_SERVER['PHP_SELF']?>">
        <h2 class="form-signin-heading">Order Products</h2>
        <label for="entry_date">Date You Want Delivery</label>
        <input type="text"  class="form-control" placeholder="Entry Date" value="<?php echo $_SESSION['salesOrder']->orderDate; ?>"
					 name="entry_date" id="entry_date" style="margin-right:20px;margin-top:10px;"  required=""/>
				<?php
					if (count($_SESSION['salesOrder']->LineItems)>0)
					{
				?>
				<table id="sales_order_table" style="border-spacing:2px;border-collapse:separate;width:100%;">
					<thead>
            <tr><th>Product</th><th>Quantity</th><th>Delivered</th><th>Price</th><th>Discount</th><th>Amount</th><th></th></tr>
          </head>
        <tbody>
					<?php
					foreach ($_SESSION['salesOrder']->LineItems as $order)
					{
					?>
        <tr id="<?php echo $order->LineNumber ?>">
        <input type="hidden"  class="form-control" placeholder="Product" name="product[]"  value="<?php echo $order->productID ?>"/>
				<td><input type="text"  class="form-control drug" placeholder="Product"
 				name="name[]" id="drug_0" style="margin-right:20px;margin-top:10px;" value="<?php echo $order->ItemDescription ?>"
 				required readonly/>
 				 </td>
				 <td><input type="text"  class="form-control quantity" placeholder="Quantity Ordered"
 					 name="quantity[]" id="quantity_<?php echo $order->LineNumber ?>" style="margin-right:20px;margin-top:10px;" value="<?php echo $order->Quantity ?>" required=""/>
 				 </td>
				 <td><input type="text"  class="form-control" placeholder="Quantity Already Delivered"
 					 name="quantity_delivered[]" id="quantity_delivered_<?php echo $order->LineNumber ?>" style="margin-right:20px;margin-top:10px;"
					 value="<?php echo $order->quantityDelivered ?>" required="" readonly=""/>
 				 </td>
				 <td><input type="text"  class="form-control" placeholder="Price"
 					 name="price[]" id="price_<?php echo $order->LineNumber ?>" style="margin-right:20px;margin-top:10px;" value="<?php echo $order->Price ?>" readonly/>
 				 </td>
				 <td><input type="text"  class="form-control discount" placeholder="Discount"
 					 name="discount[]" id="discount_<?php echo $order->LineNumber ?>" style="margin-right:20px;margin-top:10px;" value="<?php echo $order->Discount ?>"
					 required="" readonly=""/>
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
				<label for="total">Discount Sum</label>
				<input type="number" class="form-control" name="discount_sum" id="discount_sum" value="0" readonly=""/>
			  </div>
				<div style="clear:both;"></div>';
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
				<?php
				if(isset($_SESSION['existing_order'])){
				echo	'<button type="submit" name="order" id="order" class="btn btn-lg btn-primary"
					style="display: block; margin: 0 auto;width:200px;"><span class="fa fa-times"></span>Update Order</button>';
				}
				elseif (count($_SESSION['salesOrder']->LineItems)>0 && !isset($_SESSION['existing_order']))
				{
        echo '<button type="submit" name="order" id="order" class="btn btn-lg btn-primary"
				style="display: block; margin: 0 auto;width:200px;"><span class="fa fa-times"></span>Order Products</button>';
				 } ?>
      </form>
  </body>
  </html>
