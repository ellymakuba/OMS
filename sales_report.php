<?PHP
	require 'functions.php';
	$fO=new functions();
	$fO->checkLogin();
	$pageSecurity=5;
	if (isset($_POST['create'])){
	}
  ?>
  <html>
  <?PHP $fO->includeHead('Product List',0) ?>
	<script>
	$(document).ready(function(){
		$("#start_date").datepicker();
		$("#end_date").datepicker();
	});
	</script>
  </head>
  <body class="container">
  <?PHP $fO->includeMenu(3); ?>
	<div id="menu_main">
		<a href="manage_inventory.php">Inventory Value</a>
		<a href="sales_report.php" id="item_selected">Sales</a>
    </div>
		<?php if(in_array($pageSecurity, $_SESSION['AllowedPageSecurityTokens'])){?>
  <div class="table-responsive">
    <div class=" pull-left">
          <form class="navbar-form" role="search">
						<div class="form-inline">
              <input type="text" class="form-control" placeholder="Start Date" name="start_date" id="start_date">
							<input type="text" class="form-control" placeholder="End Date" name="end_date" id="end_date">
              <button class="btn btn-default" type="submit"><i class="glyphicon glyphicon-search"></i></button>
          </div>
          </form>
  </div>
  <?php
	$sales=0;
	$discount=0;
	$totalBalance=0;
	$payment=0;
	if(isset($_REQUEST['start_date']) && isset($_REQUEST['end_date'])){
		$_SESSION['start_date']=$_REQUEST['start_date'];
		$_SESSION['end_date']=$_REQUEST['end_date'];
	}
  if(isset($_SESSION['start_date']) && isset($_SESSION['end_date'])){
		?>
		<table class="table table-striped">
			<tr><th colspan="5">
				<h2 class="form-signin-heading">Sales Report Between <?php echo $_SESSION['start_date'] ?> and <?php echo $_SESSION['end_date'] ?></h2>
				</th></tr>
		  <tr>
		      <th>Product Name</th>
		      <th>Sales</th>
		      <th>Discount</th>
		      <th>Payment</th>
					<th>Balance</th>
		  </tr>
			<?php
    $products=$fO->getSalesReport($_SESSION['start_date'],$_SESSION['end_date']);
    foreach($products as $product){
			$balance=$product['sales']-$product['discount']-$product['payment'];
			$sales=$sales+$product['sales'];
			$discount=$discount+$product['discount'];
			$totalBalance=$totalBalance+$balance;
			$payment=$payment+$product['payment'];
      printf("<tr>
    <td>%s</td>
    <td>%s</td>
    <td>%s</td>
    <td>%s</td>
		<td>%s</td>
    </tr>",
	  $product['name'],
	  $product['sales'],
	  $product['discount'],
	  $product['payment'],
	  $balance
    );
    }
		echo '<tr><td>Totals KSH:</td><td>'.number_format($sales,2).'</td>';
		echo '<td>'.number_format($discount,2).'</td>';
		echo '<td>'.number_format($payment,2).'</td>';
		echo '<td>'.number_format($totalBalance,2).'</td>';
		echo '</tr>';
	  echo '</table>';
  }
	 ?>
    </div>
		<?php }
		else{
			echo '<div class="alert alert-danger">
				<strong>You do not have permission to access this page, please confirm with the system administrator</strong>
			</div>';
		}?>
  </body>
  </html>
