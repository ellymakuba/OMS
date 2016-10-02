<?PHP
	require 'functions.php';
	require 'productClass.php';
	$fO=new functions();
	$fO->checkLogin();
  $pageSecurity=2;
	if (isset($_POST['add_product'])){
		$_SESSION['errors']=array();
		if(isset($_POST['name']) && isset($_POST['bPrice']) && isset($_POST['sPrice'])
		&& isset($_POST['description']) && isset($_POST['company']) && isset($_POST['category']) && isset($_FILES['photo'])){
			$_SESSION['product']=new ProductClass();
			$_SESSION['product']->setName($_POST['name']);
			$_SESSION['product']->setCategory($_POST['category']);
			$_SESSION['product']->setDescription($_POST['description']);
			$_SESSION['product']->setBuyingPrice($_POST['bPrice']);
			$_SESSION['product']->setSellingPrice($_POST['sPrice']);
			$_SESSION['product']->setCompany($_POST['company']);
			$_SESSION['product']->setImage($_FILES['photo']);
		$uploadOk=$fO->checkImage($_FILES['photo']);
		if($uploadOk==1){
			$fO->addNewProduct($_POST['name'],$_POST['bPrice'],$_POST['sPrice'],$_POST['description'],
			$_POST['company'],$_POST['category'],$_FILES["photo"]["name"]);
			unset($_SESSION['product']);
			header("Location:manage_inventory.php");
		}
		}
	}
  if (isset($_POST['edit_product'])){
		$_SESSION['errors']=array();
		if(isset($_POST['name']) && isset($_POST['bPrice']) && isset($_POST['sPrice'])
		&& isset($_POST['description']) && isset($_POST['company']) && isset($_POST['category'])){
		if(!empty($_FILES['photo']['name'])){
			$uploadOk=$fO->checkImage($_FILES['photo']);
			if($uploadOk==1){
			$fO->updateProduct($_SESSION['product_id'],$_POST['name'],$_POST['bPrice'],$_POST['sPrice'],$_POST['description'],
			$_POST['company'],$_POST['category']);
			$fO->updateProductImage($_SESSION['product_id'],$_FILES["photo"]["name"]);
			unset($_SESSION['product_id']);
			unset($_SESSION['product']);
			header("Location:manage_inventory.php");
			}
		}
		else{
		$fO->updateProduct($_SESSION['product_id'],$_POST['name'],$_POST['bPrice'],$_POST['sPrice'],$_POST['description'],
		$_POST['company'],$_POST['category']);
		unset($_SESSION['product_id']);
		unset($_SESSION['product']);
		header("Location:manage_inventory.php");
		}
		}
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
      <?php
			if(!isset($_SESSION['errors'])){
				$_SESSION['errors']=array();
			}
			if(!isset($_SESSION['product'])){
				$_SESSION['product']=new ProductClass();
			}
			if(in_array($pageSecurity, $_SESSION['AllowedPageSecurityTokens'])){
				if(count($_SESSION['errors'])>0){
					$numberOfErrors=count($_SESSION['errors']);
					for($i=0;$i<$numberOfErrors;$i++){
						echo '<div class="alert alert-danger">
							<strong>'.$_SESSION['errors'][$i].'</strong>
						</div>';
					}
				}
			if(isset($_REQUEST['SelectedProduct'])){
				unset($_SESSION['errors']);
				$product=$fO->getProductById($_REQUEST['SelectedProduct']);
        $_SESSION['product_id']=$_REQUEST['SelectedProduct'];
					$_SESSION['product']=new ProductClass();
					$_SESSION['product']->setName($product['name']);
					$_SESSION['product']->setCategory($product['category']);
					$_SESSION['product']->setDescription($product['description']);
					$_SESSION['product']->setBuyingPrice($product['buying_price']);
					$_SESSION['product']->setSellingPrice($product['selling_price']);
					$_SESSION['product']->setCompany($product['company']);
					$_SESSION['product']->setImage($product['pic']);
			}
      ?>
      <form class="form-signin" method="POST"  action="<?php echo $_SERVER['PHP_SELF']; ?>" enctype="multipart/form-data">
        <h2 class="form-signin-heading">Edit Product</h2>
        <input type="text"  class="form-control"  name="name" value="<?php echo $_SESSION['product']->name ?>" required>
				<select  name="category" class="form-control" required>
					<option disabled selected>category</option>
					<?php
					$categories=$fO->getAllProductCategory();
					foreach($categories as $category){
						if($category['id']==$_SESSION['product']->category){
							echo '<option selected value="'.$category['id'].'">'.$category['name'].'</option>';
						}
						else{
							echo '<option value="'.$category['id'].'">'.$category['name'].'</option>';
						}
					}
					?>
				</select>
        <input type="text"  class="form-control" value="<?php echo $_SESSION['product']->description ?>" name="description">
        <input type="text"  class="form-control" value="<?php echo $_SESSION['product']->buyingPrice ?>"  name="bPrice"  required>
				<input type="text"  class="form-control" value="<?php echo $_SESSION['product']->sellingPrice ?>"  name="sPrice"  required>
        <input type="text"  class="form-control" value="<?php echo $_SESSION['product']->company ?>" name="company" required>
				<?php if(isset($_SESSION['product_id']))
							{
								echo '<img src="upload/'.$_SESSION['product']->image.'">';
							}
				?>
				<input type="file" name="photo" id="photo" class="form-control"/>
				<?php if(isset($_SESSION['product_id'])){ ?>
        <input type="submit" class="btn btn-lg btn-primary btn-block" value="Edit Product" name="edit_product"></input>
			<?php }else{ ?>
				<input type="submit" class="btn btn-lg btn-primary btn-block" value="Add Product" name="add_product"></input>
			<?php } ?>
      </form>
      <?php }
			else{
				echo '<div class="alert alert-danger">
					<strong>You do not have permission to access this page, please confirm with the system administrator</strong>
				</div>';
			}?>
  </body>
  </html>
