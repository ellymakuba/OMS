<?PHP
	require 'functions.php';
	$fO=new functions();
	$fO->checkLogin();
	?>
  <html>
  <?PHP $fO->includeHead('Sales Order List',0) ?>
  </head>
  <body class="container">
  <?PHP $fO->includeMenu(1); ?>
	<div id="menu_main">
		<a href="manage_orders.php">sales Order List</a>
		<a href="sales_order.php">Sales Order</a>
		<a href="client_orders_list.php">Client Orders List</a>
		<a href="client_statement.php" id="item_selected">Client Statement</a>
    </div>
  <div class="table-responsive">
  <table class="table table-striped">
  <?php
  if(isset($_GET['selectedClient'])){
		echo '<tr>';
		echo '<th>#</th>';
		echo '<th>Date</th>';
		echo '<th>Order Amount</th>';
		echo '<th>Amount Paid</th>';
		echo '<th>Balance</th>';
		echo '</tr>';
    $salesOrders=$fO->getClientStatementByUserName($_GET['selectedClient']);
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
  }
	echo '<div style="float:right">';
	echo 'Total Balance Ksh. '.number_format($totalBalance,2);
	echo '</div><div style="clear:both">';
  ?>
  </table>
    </div>
  </body>
  </html>
