<?PHP
	require 'functions.php';
	$fO=new functions();
	$fO->checkLogin();
	$pageSecurity=6;
	?>
  <html>
  <?PHP $fO->includeHead('Sales Order List',0) ?>
  </head>
  <body class="container">
  <?PHP $fO->includeMenu(1); ?>
	<div id="menu_main">
		<a href="products.php">Products</a>
		<a href="client_statement.php" id="selected_item">Statement</a>
    </div>
		<?php if(in_array($pageSecurity, $_SESSION['AllowedPageSecurityTokens'])){?>
  <div class="table-responsive">
  <table class="table table-striped">
  <?php
		echo '<tr>';
		echo '<th>#</th>';
		echo '<th>Date</th>';
		echo '<th>Order Amount</th>';
		echo '<th>Amount Paid</th>';
		echo '<th>Balance</th>';
		echo '</tr>';
    $salesOrders=$fO->getClientStatementByUserName($_SESSION['log_user']);
		$totalBalance=0;
		foreach($salesOrders as $order){
			$totalBalance=$totalBalance+$order['balance'];
    printf("<tr><td><a href=\"client_order.php?SelectedOrder=%s\">".$order['sales_order_id']."</a></td>
    <td>%s</td>
		<td>%s</td>
		<td>%s</td>
		<td>%s</td>
    </tr>",
    $order['sales_order_id'],
    $order['date_required'],
    number_format($order['order_amount'],2),
		number_format($order['payment'],2),
		number_format($order['balance'],2)
    );
    }
	echo '<div style="float:right">';
	echo 'Total Balance Ksh. '.number_format($totalBalance,2);
	echo '</div><div style="clear:both">';
  ?>
  </table>
    </div>
	<?php }	else{
			echo '<div class="alert alert-danger">
				<strong>You do not have permission to access this page, please confirm with the system administrator</strong>
			</div>';
		}?>
  </body>
  </html>
