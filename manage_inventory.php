<?PHP
	require 'functions.php';
	$fO=new functions();
	$fO->checkLogin();
	if (isset($_POST['create'])){
	}
  ?>
  <html>
  <?PHP $fO->includeHead('Product List',0) ?>
  </head>
  <body class="container">
  <?PHP $fO->includeMenu(2); ?>
	<div id="menu_main">
		<a href="manage_inventory.php" id="item_selected">Product List</a>
		<a href="product_details.php">Product Details</a>
		<a href="purchase_order_list.php">Purchase Order List</a>
		<a href="purchase_order.php">Purchase Order</a>
    </div>
  <div class="table-responsive">
    <div class="col-sm-3 col-md-3 pull-left">
          <form class="navbar-form" role="search">
          <div class="input-group">
              <input type="text" class="form-control" placeholder="Search" name="srch-term" id="srch-term">
              <div class="input-group-btn">
                  <button class="btn btn-default" type="submit"><i class="glyphicon glyphicon-search"></i></button>
              </div>
          </div>
          </form>
  </div>
  <div class="col-sm-3 col-md-3 pull-right">
    <a href="product_details.php" class="btn btn-default btn-primary">New Product</a>
  </div>
  <table class="table table-striped">
  <tr>
  <form method="POST">
            <th>Edit</th>
            <th>Name</th>
            <th>Description</th>
            <th>Cost</th>
            <th>Company</th>
						<th>Stock</th>
  </form>
  </tr>
  <?php
  if(isset($_REQUEST['srch-term'])){
    $products=$fO->getProductByName($_REQUEST['srch-term']);
    foreach($products as $product){
      printf("<tr><td><a href=\"product_details.php?SelectedProduct=%s\">" .$product['id'] . "</a></td>
    <td>%s</td>
    <td>%s</td>
    <td>%s</td>
    <td>%s</td>
    <td>%s</td>
		<td>%s</td>
    </tr>",
  $product['id'],
  $product['name'],
  $product['description'],
  $product['buying_price'],
  $product['selling_price'],
	$product['quantity']
    );
    }
  }
  else{
    $products=$fO->getAllProducts();
    foreach($products as $product){
      printf("<tr><td><a href=\"product_details.php?SelectedProduct=%s\">" .$product['id'] . "</a></td>
    <td>%s</td>
    <td>%s</td>
    <td>%s</td>
    <td>%s</td>
		<td>%s</td>
    </tr>",
		$product['id'],
	  $product['name'],
	  $product['description'],
	  $product['buying_price'],
	  $product['selling_price'],
		$product['quantity']
    );
    }
  }
   ?>
  </table>
    </div>
  </body>
  </html>
