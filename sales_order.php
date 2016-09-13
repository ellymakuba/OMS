<?PHP
	require 'functions.php';
	require 'salesOrderCart.php';
	$fO=new functions();
	$fO->checkLogin();
	if (isset($_POST['dispatch'])){
		if(isset($_POST['entry_date']) && count($_SESSION['salesOrder'])>0 && isset($_SESSION['existing_order'])){
			$entry_date=date('Y-m-d',strtotime($_POST['entry_date']));
			$i=0;
			foreach($_POST['product'] as $value) {
			$totalDelivered=$_POST['quantity_delivered'][$i]+$_POST['quantity'][$i];
			$fO->dispatchSalesOrderProducts($_SESSION['existing_order'],$totalDelivered,$_POST['product'][$i]);
			$fO->deductFromStockFromInventory($_POST['quantity'][$i],$_POST['product'][$i]);
			$i++;
			}
			unset($_SESSION['salesOrder']);
			unset($_SESSION['existing_order']);
			header('Location:manage_orders.php');
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

    $(document).on('focus','.myDate', function() {
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
		  url:"sales_order.php?add_cart="+ui.item.value,
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
	 url:"sales_order.php?set_date="+$(this).val(),
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
	 url:"sales_order.php?update_cart="+id+"&quantity="+value,
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
			<a href="manage_orders.php">Sales Order List</a>
	    <a href="sales_order.php" id="item_selected">Sales Order</a>
      </div>
			<?php
				if(isset($_GET['SelectedOrder'])){
					$client_username=$fO->getClientByOrderId($_GET['SelectedOrder']);
					$_SESSION['client']=$client_username['client'];
					$_SESSION['existing_order']=$_GET['SelectedOrder'];
					if(!isset($_SESSION['salesOrder'])){
					$_SESSION['salesOrder'] = new Cart();
					$salesOrder=$fO->getSalesOrderById($_GET['SelectedOrder']);
					$orderProducts=$fO->getSalesOderDetailsByOrderId($salesOrder['sales_order_id']);
					$_SESSION['salesOrder']->orderDate=$salesOrder['date_required'];
					foreach($orderProducts as $product){
						$_SESSION['salesOrder']->add_to_cart($product['id'],0,$product['name'],$product['price'],0,$product['quantity_delivered'],$product['quantity'],-1);
					}
				}
				}

			 ?>
      <form class="form-signin" method="POST"  action="<?php echo $_SERVER['PHP_SELF']?>">
        <h2 class="form-signin-heading"><?php echo $_SESSION['client'].' Order';?></h2>
        <label for="entry_date">Delivery Date</label>
        <input type="text"  class="form-control" placeholder="Entry Date" value="<?php echo $_SESSION['salesOrder']->orderDate; ?>"
					 name="entry_date" id="entry_date" style="margin-right:20px;margin-top:10px;"  required=""/>
				<?php
					if (count($_SESSION['salesOrder']->LineItems)>0)
					{
				?>
				<table id="sales_order_table" style="border-spacing:2px;border-collapse:separate;width:100%;">
					<thead>
            <tr><th>Product</th><th>Quantity Requested</th><th>Delivered</th><th>Dispatch</th><th>Price</th><th>Discount</th><th>Amount</th></tr>
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
				 <td><input type="number"  class="form-control" placeholder="Quantity Requested"
 					 name="requested[]" id="requested_<?php echo $order->LineNumber ?>" style="margin-right:20px;margin-top:10px;"
					  value="<?php echo $order->requested ?>" required="" readonly=""/>
 				 </td>
				 <td><input type="number"  class="form-control" placeholder="Quantity Already Delivered"
 					 name="quantity_delivered[]" id="quantity_delivered_<?php echo $order->LineNumber ?>" style="margin-right:20px;margin-top:10px;"
					 value="<?php echo $order->quantityDelivered ?>" required="" readonly=""/>
 				 </td>
				 <?php $maximumDispatch=$order->requested-$order->quantityDelivered;?>
				 <td><input type="number"  class="form-control quantity" placeholder="Quantity"  max="<?php echo $maximumDispatch ?>"
 					 name="quantity[]" id="quantity_<?php echo $order->LineNumber ?>" style="margin-right:20px;margin-top:10px;"
					 value="<?php echo $order->Quantity ?>" required="" />
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
				<button type="submit" name="dispatch" id="order" class="btn btn-lg btn-primary"
					style="display: block; margin: 0 auto;width:200px;"><span class="fa fa-times"></span>Dispatch Order</button>
      </form>
  </body>
  </html>
