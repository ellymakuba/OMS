<?PHP
	require 'functions.php';
	$fO=new functions();
	$fO->checkLogin();
  //Generate timestamp
	$timestamp = time();
	//CREATE-Button
	if (isset($_POST['add_drug'])){
		if(isset($_POST['name']) && isset($_POST['bPrice']) && isset($_POST['sPrice'])
		&& isset($_POST['description']) && isset($_POST['company']) && isset($_POST['category'])){
		$fO->addNewDrug($_POST['name'],$_POST['bPrice'],$_POST['sPrice'],$_POST['description'],
		$_POST['company'],$_POST['category']);
		}
	}
  if (isset($_POST['edit_drug'])){
		if(isset($_POST['name']) && isset($_POST['bPrice']) && isset($_POST['sPrice'])
		&& isset($_POST['description']) && isset($_POST['company']) && isset($_POST['category'])){
		$fO->updateDrug($_SESSION['drug_id'],$_POST['name'],$_POST['bPrice'],$_POST['sPrice'],$_POST['description'],
		$_POST['company'],$_POST['category']);
		}
	}
  ?>
  <html>
  <?PHP $fO->includeHead('New Drug',0) ?>
	<script>
	 $(document).ready(function(){
		 $('#expiry_date').datepicker();
	 });
	</script>
  </head>
  <body class="container">
    <?PHP $fO->includeMenu(9); ?>
  	<div id="menu_main">
      <a href="cust_search.php">Users</a>
      <a href="cust_search.php">Roles</a>
      <a href="cust_search.php">Privileges</a>
      <a href="drug_list.php" id="item_selected">Drugs</a>
      <a href="cust_search.php">Lab Tests</a>
      <a href="cust_search.php">Operations</a>
      <a href="cust_search.php">Nurses</a>
      <a href="cust_search.php">Doctors</a>
      <a href="cust_search.php">Labs</a>
      <a href="cust_search.php">Departments</a>
      <a href="cust_search.php">Rooms</a>
      </div>
      <?php if(isset($_REQUEST['SelectedDrug'])){
        $drug=$fO->getDrugById($_REQUEST['SelectedDrug']);
        $_SESSION['drug_id']=$_REQUEST['SelectedDrug'];
      ?>
      <form class="form-signin" method="POST"  action="<?php echo $_SERVER['PHP_SELF']; ?>">
        <h2 class="form-signin-heading">Edit Drug</h2>
        <input type="text"  class="form-control"  name="name" value="<?php echo $drug['name']?>" required>
				<select  name="category" class="form-control" required>
					<option disabled selected>category</option>
					<?php
					$categories=$fO->getAllDrugCategory();
					foreach($categories as $category){
						if($category['id']==$drug['category']){
							echo '<option selected value="'.$category['id'].'">'.$category['name'].'</option>';
						}
						else{
							echo '<option value="'.$category['id'].'">'.$category['name'].'</option>';
						}
					}
					?>
				</select>
        <input type="text"  class="form-control" value="<?php echo $drug['description']?>" name="description"required>
        <input type="text"  class="form-control" value="<?php echo $drug['buying_price']?>"  name="bPrice"  required>
				<input type="text"  class="form-control" value="<?php echo $drug['selling_price']?>"  name="sPrice"  required>
        <input type="text"  class="form-control" value="<?php echo $drug['company']?>" name="company" required>
        <input type="submit" class="btn btn-lg btn-primary btn-block" value="Edit Drug" name="edit_drug"></input>
      </form>
      <?php }
      else{?>
        <form class="form-signin" method="POST"  action="<?php echo $_SERVER['PHP_SELF'];?>">
          <h2 class="form-signin-heading">Add Drug</h2>
          <input type="text"  class="form-control" placeholder="Name" name="name" required>
					<select  name="category" class="form-control" required>
						<option disabled selected>category</option>
						<?php
						$categories=$fO->getAllDrugCategory();
						foreach($categories as $category){
						echo '<option value="'.$category['id'].'">'.$category['name'].'</option>';
						}
						?>
					</select>
          <input type="text"  class="form-control" placeholder="Description" name="description"  required>
          <input type="text"  class="form-control" placeholder="Buying Price"  name="bPrice"  required>
					<input type="text"  class="form-control" placeholder="Selling Price" name="sPrice" required>
          <input type="text"  class="form-control" placeholder="Manufacturing Company" name="company" required>
          <input type="submit" class="btn btn-lg btn-primary btn-block" value="Add Drug" name="add_drug"></input>
        </form>
      <?php }?>
  </body>
  </html>
