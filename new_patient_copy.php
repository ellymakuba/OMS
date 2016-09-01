<?PHP
	require 'functions.php';
	$fO=new functions();
	$fO->checkLogin();
  //Generate timestamp
	$timestamp = time();
	//CREATE-Button
	if (isset($_POST['add_patient'])){
	}
  if (isset($_POST['edit_patient'])){
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
      <?php if(isset($_REQUEST['SelectedPatient'])){
        $drug=$fO->getDrugById($_REQUEST['SelectedPatient']);
        $_SESSION['patient_id']=$_REQUEST['SelectedPatient'];
      ?>
      <div class="row">
        <form class="form-horizontal" style="display: block; margin: 0 auto;">
          <h2 style="text-align:center;">Edit Patient</h2>
      <div class="row" style="padding-bottom : 10px">
          <div class="col-lg-1 col-lg-offset-2">
              <label class="control-label pull-right">Name:</label>
          </div>
          <div class="col-lg-3">
              <input type="text"  name="pat_name" class="form-control" placeholder="Patient Name" required>
          </div>
          <div class="col-lg-1 col-lg-offset-0">
              <label class="control-label pull-right">Age:</label>
          </div>
          <div class="col-lg-3">
              <input type="text" name="pat_age" class="form-control" placeholder="Age" required>
          </div>
      </div>
      <div class="row" style="padding-bottom : 10px">
          <div class="col-lg-2 col-lg-offset-1">
              <label class="control-label pull-right">Email:</label>
          </div>
          <div class="col-lg-3">
              <input type="email" name="pat_email" class="form-control" placeholder="Email" required>
          </div>
          <div class="col-lg-1 col-lg-offset-0">
              <label class="control-label pull-right">Blood Group:</label>
          </div>
          <div class="col-lg-3">
            <select  name="pat_blood" class="form-control" required>
              <option disabled selected>Blood Group</option>
              <option>A+</option>
              <option>A-</option>
              <option>B+</option>
              <option>B-</option>
              <option>AB+</option>
              <option>AB-</option>
              <option>O+</option>
              <option>O-</option>
          </select>
          </div>
      </div>
      <div class="row" style="padding-bottom : 10px">
          <div class="col-lg-1 col-lg-offset-2">
              <label class="control-label pull-right">History:</label>
          </div>
          <div class="col-lg-3">
              <input type="file"  name="pat_history" class="form-control" placeholder="History"  required>
          </div>
          <div class="col-lg-1 col-lg-offset-0">
              <label class="control-label pull-right">Phone:</label>
          </div>
          <div class="col-lg-3">
              <input type="text"  name="pat_phone" class="form-control" placeholder="Phone"  required>
          </div>
      </div>
      <div class="row" style="padding-bottom : 10px">
          <div class="col-lg-1 col-lg-offset-2">
              <label class="control-label pull-right">Address:</label>
          </div>
          <div class="col-lg-3">
                <input type="text"  name="pat_add" class="form-control" placeholder="Address" required>
          </div>
          <div class="col-lg-1 col-lg-offset-0">
              <label class="control-label pull-right">Date:</label>
          </div>
          <div class="col-lg-3">
              <input type="date"  name="pat_admission" class="form-control" placeholder="Admission Date" required>
          </div>
      </div>
      <div class="row" style="padding-bottom : 10px">
          <div class="col-lg-1 col-lg-offset-2">
              <label class="control-label pull-right">Patient Type:</label>
          </div>
          <div class="col-lg-3">
            <select id="drop1p" name="pat_type" class="form-control" title="Please Select Type of Patient" required>
              <option disabled selected>Type of Patient</option>
              <option>IP</option>
              <option>OP</option>
            </select>
          </div>
          <div class="col-lg-1 col-lg-offset-0">
              <label class="control-label pull-right">Room Type:</label>
          </div>
          <div class="col-lg-3">
            <select id="drop2p" name="pat_room" class="form-control" title="Room Type" onchange="fetch_room_details()" required>
              <option disabled selected>Room Type</option>
            </select>
          </div>
      </div>
      <div class="row" style="padding-bottom : 10px">
          <div class="col-lg-1 col-lg-offset-2">
              <label class="control-label pull-right">Room No:</label>
          </div>
          <div class="col-lg-3">
                <input type="text"  id="room_number" name="room_number" class="form-control"
                  placeholder="Room Number" readonly>
          </div>
          <div class="col-lg-1 col-lg-offset-0">
              <label class="control-label pull-right">Room Cost:</label>
          </div>
          <div class="col-lg-3">
              <input type="text"  id="room_cost" name="room_cost" class="form-control"
               placeholder="Room Cost" readonly>
          </div>
      </div>
      <div>
      <input type="submit" name="submit" class="btn btn-lg btn-primary" value="Edit Patient"
      style="display: block; margin: 0 auto;width:200px;"></input>
    </div>
  </form>
  </div>
  <?php }
  else{?>
    <div class="row">
      <form class="form-horizontal" style="display: block; margin: 0 auto;">
        <h2 style="text-align:center;">Add Patient</h2>
    <div class="row" style="padding-bottom : 10px">
        <div class="col-lg-1 col-lg-offset-2">
            <label class="control-label pull-right">Name:</label>
        </div>
        <div class="col-lg-3">
            <input type="text"  name="pat_name" class="form-control" placeholder="Patient Name" required>
        </div>
        <div class="col-lg-1 col-lg-offset-0">
            <label class="control-label pull-right">Age:</label>
        </div>
        <div class="col-lg-3">
            <input type="text" name="pat_age" class="form-control" placeholder="Age" required>
        </div>
    </div>
    <div class="row" style="padding-bottom : 10px">
        <div class="col-lg-2 col-lg-offset-1">
            <label class="control-label pull-right">Email:</label>
        </div>
        <div class="col-lg-3">
            <input type="email" name="pat_email" class="form-control" placeholder="Email" required>
        </div>
        <div class="col-lg-1 col-lg-offset-0">
            <label class="control-label pull-right">Blood Group:</label>
        </div>
        <div class="col-lg-3">
          <select  name="pat_blood" class="form-control" required>
            <option disabled selected>Blood Group</option>
            <option>A+</option>
            <option>A-</option>
            <option>B+</option>
            <option>B-</option>
            <option>AB+</option>
            <option>AB-</option>
            <option>O+</option>
            <option>O-</option>
        </select>
        </div>
    </div>
    <div class="row" style="padding-bottom : 10px">
        <div class="col-lg-1 col-lg-offset-2">
            <label class="control-label pull-right">History:</label>
        </div>
        <div class="col-lg-3">
            <input type="file"  name="pat_history" class="form-control" placeholder="History"  required>
        </div>
        <div class="col-lg-1 col-lg-offset-0">
            <label class="control-label pull-right">Phone:</label>
        </div>
        <div class="col-lg-3">
            <input type="text"  name="pat_phone" class="form-control" placeholder="Phone"  required>
        </div>
    </div>
    <div class="row" style="padding-bottom : 10px">
        <div class="col-lg-1 col-lg-offset-2">
            <label class="control-label pull-right">Address:</label>
        </div>
        <div class="col-lg-3">
              <input type="text"  name="pat_add" class="form-control" placeholder="Address" required>
        </div>
        <div class="col-lg-1 col-lg-offset-0">
            <label class="control-label pull-right">Date:</label>
        </div>
        <div class="col-lg-3">
            <input type="date"  name="pat_admission" class="form-control" placeholder="Admission Date" required>
        </div>
    </div>
    <div class="row" style="padding-bottom : 10px">
        <div class="col-lg-1 col-lg-offset-2">
            <label class="control-label pull-right">Patient Type:</label>
        </div>
        <div class="col-lg-3">
          <select id="drop1p" name="pat_type" class="form-control" title="Please Select Type of Patient" required>
            <option disabled selected>Type of Patient</option>
            <option>IP</option>
            <option>OP</option>
          </select>
        </div>
        <div class="col-lg-1 col-lg-offset-0">
            <label class="control-label pull-right">Room Type:</label>
        </div>
        <div class="col-lg-3">
          <select id="drop2p" name="pat_room" class="form-control" title="Room Type" onchange="fetch_room_details()" required>
            <option disabled selected>Room Type</option>
          </select>
        </div>
    </div>
    <div class="row" style="padding-bottom : 10px">
        <div class="col-lg-1 col-lg-offset-2">
            <label class="control-label pull-right">Room No:</label>
        </div>
        <div class="col-lg-3">
              <input type="text"  id="room_number" name="room_number" class="form-control"
                placeholder="Room Number" readonly>
        </div>
        <div class="col-lg-1 col-lg-offset-0">
            <label class="control-label pull-right">Room Cost:</label>
        </div>
        <div class="col-lg-3">
            <input type="text"  id="room_cost" name="room_cost" class="form-control"
             placeholder="Room Cost" readonly>
        </div>
    </div>
    <div>
    <input type="submit" name="submit" class="btn btn-lg btn-primary" value="Add Patient"
    style="display: block; margin: 0 auto;width:200px;"></input>
  </div>
</form>
</div>
      <?php }?>
  </body>
  </html>
