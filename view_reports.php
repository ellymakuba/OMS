<?PHP
	require 'functions.php';
	$fO=new functions();
	$fO->checkLogin();
	if (isset($_POST['create'])){
	}
  ?>
  <html>
  <?PHP $fO->includeHead('Inventory Value',0) ?>
  </head>
  <body class="container">
  <?PHP $fO->includeMenu(3); ?>
	<div id="menu_main">
		<a href="manage_inventory.php" id="item_selected">Inventory Value</a>
		<a href="sales_report.php">Sales</a>
    </div>
  <div class="table-responsive">
  <table class="table table-striped">
  <tr>
		  <th>Name</th>
      <th>Unit Price</th>
      <th>Quantity</th>
			<th>Value</th>
  </tr>
  <?php
    $products=$fO->getAllProducts();
		$totalInventory=0;
    foreach($products as $product){
			$productValue=$product['selling_price']*$product['quantity'];
			$totalInventory=$totalInventory+$productValue;
    printf("<tr>
    <td>%s</td>
    <td>%s</td>
    <td>%s</td>
		<td>%s</td>
    </tr>",
	  $product['name'],
	  $product['selling_price'],
		$product['quantity'],
		number_format($productValue,2)
    );
    }
		echo '<div style="float:right">';
		echo 'Stock Value Ksh. '.number_format($totalInventory,2);
		echo '</div><div style="clear:both">';
   ?>
  </table>
    </div>
  </body>
  </html>
