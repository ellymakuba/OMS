<?PHP
	require 'functions.php';
	$fO=new functions();
	$fO->checkLogin();
  //Generate timestamp
	$timestamp = time();
	//CREATE-Button
	if (isset($_POST['save'])){
		if(isset($_POST['name']) && isset($_POST['age']) && isset($_POST['blood_group']) && isset($_POST['phone']) && isset($_POST['address'])){
			$fO->savePatient($_POST['name'],$_POST['age'],$_POST['blood_group'],$_POST['phone'],$_POST['address']);
		}
	}
  if (isset($_POST['edit'])){
		if(isset($_POST['name']) && isset($_POST['age']) && isset($_POST['blood_group']) && isset($_POST['phone']) && isset($_POST['address'])){
			$fO->updatePatient($_SESSION['patient_id'],$_POST['name'],$_POST['age'],$_POST['blood_group'],$_POST['phone'],$_POST['address']);
		}
	}
  ?>
  <html>
  <?PHP $fO->includeHead('Patient',0) ?>
  </head>
  <body class="container">
    <?PHP $fO->includeMenu(1); ?>
  	<div id="menu_main">
      <a href="patient_list.php">Patient List</a>
      <a href="new_patient.php" id="item_selected">Patient</a>
      </div>
      <?php
			$bloodGroups=$fO->getAllBloodGroups();
			if(isset($_REQUEST['SelectedPatient'])){
        $patient=$fO->getPatientByID($_REQUEST['SelectedPatient']);
        $_SESSION['patient_id']=$_REQUEST['SelectedPatient'];
      ?>
			<form class="form-signin" method="POST"  action="<?php echo $_SERVER['PHP_SELF']; ?>">
				<h2 class="form-signin-heading">Edit Patient</h2>
              <input type="text"  name="name" class="form-control" placeholder="Patient Name" value="<?php echo $patient['p_name'] ?>" required>
              <input type="text" name="age" class="form-control" placeholder="Age" value="<?php echo $patient['age'] ?>" required>
              <input type="email" name="email" class="form-control" placeholder="Email" value="<?php echo $patient['email'] ?>">
            <select  name="blood_group" class="form-control" required>
              <option disabled selected>Blood Group</option>
							<?php
							foreach($bloodGroups as $bloodGroup){
								if($bloodGroup['id']==$patient['blood_group']){
									echo '<option selected value="'.$bloodGroup['id'].'">'.$bloodGroup['name'].'</option>';
								}
								else{
								echo '<option value="'.$bloodGroup['id'].'">'.$bloodGroup['name'].'</option>';
							}
							}
							?>
          </select>
              <input type="text"  name="phone" class="form-control" placeholder="Phone" value="<?php echo $patient['phno'] ?>" required>
                <input type="text"  name="address" class="form-control" value="<?php echo $patient['address'] ?>" placeholder="Address" required>
      <input type="submit" name="edit" class="btn btn-lg btn-primary" value="Edit Patient"
      style="display: block; margin: 0 auto;width:200px;"></input>
  </form>
  <?php }
  else{
		?>
		<form class="form-signin" method="POST"  action="<?php echo $_SERVER['PHP_SELF']; ?>">
			<h2 class="form-signin-heading">Add New Patient</h2>
            <input type="text"  name="name" class="form-control" placeholder="Patient Name" required>
            <input type="text" name="age" class="form-control" placeholder="Age" required>
            <input type="email" name="email" class="form-control" placeholder="Email">
          <select  name="blood_group" class="form-control" required>
            <option disabled selected>Blood Group</option>
          <?php
					foreach($bloodGroups as $bloodGroup){
						echo '<option value="'.$bloodGroup['id'].'">'.$bloodGroup['name'].'</option>';
					}
					?>
        </select>
            <input type="text"  name="phone" class="form-control" placeholder="Phone"  required>
            <input type="text"  name="address" class="form-control" placeholder="Address" required>
			    <input type="submit" name="save" class="btn btn-lg btn-primary" value="Add Patient"
			    style="display: block; margin: 0 auto;width:200px;"></input>
			</form>
</div>
      <?php }?>
  </body>
  </html>
