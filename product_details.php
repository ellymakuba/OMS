<?PHP
	require 'functions.php';
	$fO=new functions();
	$fO->checkLogin();
  //Generate timestamp
	$timestamp = time();
	//CREATE-Button
	if (isset($_POST['add_product'])){
		if(isset($_POST['name']) && isset($_POST['bPrice']) && isset($_POST['sPrice'])
		&& isset($_POST['description']) && isset($_POST['company']) && isset($_POST['category']) && isset($_FILES['image'])){
		$max_file_size = 1024*2048; // 2048kb
		$valid_exts = array('jpeg', 'jpg', 'png', 'tif', 'tiff');
		$path = 'images/'.$_POST['name'].'_';
		$sizes = array(100 => 130, 146 => 190, 230 => 300);
		if( $_FILES['image']['size'] < $max_file_size ){
			$ext = strtolower(pathinfo($_FILES['image']['name'], PATHINFO_EXTENSION));
			if (in_array($ext, $valid_exts)) {
				foreach ($sizes as $width => $height) {
					$files[] = $fO->resizeImage($width, $height, $path);
				}
				$fO->addNewProduct($_POST['name'],$_POST['bPrice'],$_POST['sPrice'],$_POST['description'],
				$_POST['company'],$_POST['category'],$files[1]);
				header("Location:manage_inventory.php");
			}
			//else $error_msg = 'Unsupported file';
		}
		//else $error_msg = 'Please choose an image smaller than 2048kB.';
		}
	}
  if (isset($_POST['edit_product'])){
		if(isset($_POST['name']) && isset($_POST['bPrice']) && isset($_POST['sPrice'])
		&& isset($_POST['description']) && isset($_POST['company']) && isset($_POST['category'])){
		$fO->updateProduct($_SESSION['product_id'],$_POST['name'],$_POST['bPrice'],$_POST['sPrice'],$_POST['description'],
		$_POST['company'],$_POST['category']);
		}
		unset($_SESSION['product_id']);
		header("Location:manage_inventory.php");
	}
  ?>
  <html>
  <?PHP $fO->includeHead('Product Details',0) ?>
	<script>
	 $(document).ready(function(){
		 $('#expiry_date').datepicker();
	 });
	</script>
  </head>
  <body class="container">
    <?PHP $fO->includeMenu(2); ?>
  	<div id="menu_main">
			<a href="manage_inventory.php">Product List</a>
			<a href="product_details.php" id="item_selected">Product Details</a>
			<a href="purchase_order_list.php">Purchase Order List</a>
			<a href="purchase_order.php">Purchase Order</a>
      </div>
      <?php if(isset($_REQUEST['SelectedProduct'])){
        $product=$fO->getProductById($_REQUEST['SelectedProduct']);
        $_SESSION['product_id']=$_REQUEST['SelectedProduct'];
      ?>
      <form class="form-signin" method="POST"  action="<?php echo $_SERVER['PHP_SELF']; ?>" enctype="multipart/form-data">
        <h2 class="form-signin-heading">Edit Product</h2>
        <input type="text"  class="form-control"  name="name" value="<?php echo $product['name']?>" required>
				<select  name="category" class="form-control" required>
					<option disabled selected>category</option>
					<?php
					$categories=$fO->getAllProductCategory();
					foreach($categories as $category){
						if($category['id']==$product['category']){
							echo '<option selected value="'.$category['id'].'">'.$category['name'].'</option>';
						}
						else{
							echo '<option value="'.$category['id'].'">'.$category['name'].'</option>';
						}
					}
					?>
				</select>
        <input type="text"  class="form-control" value="<?php echo $product['description']?>" name="description">
        <input type="text"  class="form-control" value="<?php echo $product['buying_price']?>"  name="bPrice"  required>
				<input type="text"  class="form-control" value="<?php echo $product['selling_price']?>"  name="sPrice"  required>
        <input type="text"  class="form-control" value="<?php echo $product['company']?>" name="company" required>
				<?php  echo '<img src="'.$product['pic'].'">'; ?>
        <input type="submit" class="btn btn-lg btn-primary btn-block" value="Edit Product" name="edit_product"></input>
      </form>
      <?php }
      else{?>
        <form class="form-signin" method="POST"  action="<?php echo $_SERVER['PHP_SELF'];?>" enctype="multipart/form-data">
          <h2 class="form-signin-heading">Add Product</h2>
          <input type="text"  class="form-control" placeholder="Name" name="name" required>
					<select  name="category" class="form-control" required>
						<option disabled selected>category</option>
						<?php
						$categories=$fO->getAllProductCategory();
						foreach($categories as $category){
						echo '<option value="'.$category['id'].'">'.$category['name'].'</option>';
						}
						?>
					</select>
          <input type="text"  class="form-control" placeholder="Description" name="description" >
          <input type="text"  class="form-control" placeholder="Buying Price"  name="bPrice"  required>
					<input type="text"  class="form-control" placeholder="Selling Price" name="sPrice" required>
          <input type="text"  class="form-control" placeholder="Manufacturing Company" name="company" required>
					<input type="file" name="image" id="image" class="form-control" accept="image/*" />
          <input type="submit" class="btn btn-lg btn-primary btn-block" value="Add Product" name="add_product"></input>
        </form>
      <?php }?>
  </body>
  </html>
