
<?PHP
 include 'header.inc';
 	require 'functions.php';
 	$fO=new functions();
 	$fO->checkLogin();
?>
    <h1 class="sub-header">Manage settings</h1><br>
      <ul class="nav nav-pills">
        <li class="active"><a data-toggle="tab" href="#list_users">view Users</a></li>
        <li><a data-toggle="tab" href="#add_user">Add User</a></li>
        <li><a data-toggle="tab" href="#view_roles">View Roles</a></li>
        <li><a data-toggle="tab" href="#add_role">Add Roles</a></li>
        <li><a data-toggle="tab" href="#list_drug">View Drugs</a></li>
        <li><a data-toggle="tab" href="#add_drug">Add Drug</a></li>
        <li><a data-toggle="tab" href="#view_lab_tests">View Lab Tests</a></li>
        <li><a data-toggle="tab" href="#add_lab_test">Add Lab Test</a></li>
        <li><a data-toggle="tab" href="#view_operation_types">View Operation Types</a></li>
        <li><a data-toggle="tab" href="#add_operation_type">Add Operation</a></li>
        <li><a data-toggle="tab" href="#list_nurse">Nurse List</a></li>
        <li><a data-toggle="tab" href="#add_nurse">Add Nurse</a></li>
        <li><a data-toggle="tab" href="#list_doc">Doctor List</a></li>
        <li><a data-toggle="tab" href="#add_doc">Add Doctor</a></li>
        <li><a data-toggle="tab" href="#list_lab">Laboratory List</a></li>
  			<li><a data-toggle="tab" href="#add_lab">Add Laboratory</a></li>
        <li><a data-toggle="tab"  href="#list_dept">Department List</a></li>
        <li><a data-toggle="tab" href="#add_dept">Add Department</a></li>
        <li><a data-toggle="tab"  href="#list_rooms">View Rooms</a></li>
        <li><a data-toggle="tab" href="#add_room">Add Room</a></li>
    </ul>
  <div class="tab-content">
        <div id="list_nurse" class="tab-pane fade in active">
      <label>Search: <input type="search" id="tags_nurse" class="form-control" ></input></label><br><br>
        <div class="table-responsive">
        <table class="table table-striped" id="nurse_table_body">
        <tr>
        <form id="nurse_form" method="POST">
                  <th>#</th>
                  <th>Nurse Name</th>
                  <th>Nurse Age</th>
                  <th>Qualification</th>
                  <th>DOJ</th>
                  <th>Salary</th>
          <th><span class="glyphicon glyphicon-wrench"></span>  Manage</th>
                  <input type="hidden" id="nurse_hidden" name="nurse_hidden"></input>
        </form>
                </tr>

            </table>
          </div>
        </div>
        <div id="add_nurse" class="tab-pane fade">
      <form class="form-signin" method="POST"  action="admin_add_nurse.php" enctype="multipart/form-data">
        <h2 class="form-signin-heading">Add Nurse</h2>


        <input type="text" name="nur_name"  class="form-control" placeholder="Nurse Name" required>
        <input type="file" name="nur_pic"  class="form-control" placeholder="Nurse Pic" title="Nurse Pic" required>
        <input type="text"  name="nur_age" class="form-control" placeholder="Age"   required>
        <input type="email"  name="nur_email" class="form-control" placeholder="Email" required>
        <input type="text" name="nur_qual" class="form-control" placeholder="Qualification" required>
        <select id="drop1n" name="nur_dept" class="form-control" required>
      <option disabled selected>Name Of Department</option>


    </select>
      <input type="date"  name="nur_doj" class="form-control" placeholder="DOJ" title="DOJ" required>
      <input type="text"  name="nur_sal" class="form-control" placeholder="Salary"  maxlength="10" required>
        <br>
    <input type="submit" name="submit" class="btn btn-lg btn-primary btn-block" value="Add Nurse" ></input>
      </form>
        </div>
        <div id="list_doc" class="tab-pane fade">
    <label>Search: <input type="search" id="tags_doc" class="form-control"  ></input></label><br><br>
        <div class="table-responsive">
        <table class="table table-striped" id="doc_table_body">
        <tr>
        <form id="doc_form" method="POST">
                  <th>#</th>
                  <th>Doctor Name</th>
                  <th>Doctor Age</th>
                  <th>Doctor Address</th>
                  <th>Department</th>
                  <th>Consultation Type</th>
          <th><span class="glyphicon glyphicon-wrench"></span>  Manage</th>
                  <input type="hidden" id="doc_hidden" name="doc_hidden"></input>
        </form>
                </tr>

            </table>
          </div>
        </div>
        <div id="add_doc" class="tab-pane fade">
           <form class="form-signin" method="POST"  action="admin_add_doc.php" enctype="multipart/form-data">
        <h2 class="form-signin-heading">Add Doctor</h2>


        <input type="text"  name="doc_name" class="form-control" placeholder="Name" required>
        <input type="file"  name="doc_pic" class="form-control" title="Doctors Picture" required>
        <input type="text"  name="doc_age" class="form-control" onkeypress="return isnum(event)" maxlength="3" placeholder="Age" required>
        <input type="email"  name="doc_email" class="form-control" placeholder="Email" required>
        <input type="file"  name="doc_qual" class="form-control" title="Upload Qualification" required>
        <input type="text"  name="doc_add" class="form-control" placeholder="Address" required>
        <input type="text"  name="doc_phone" class="form-control" onkeypress="return isnum(event)" maxlength="10" placeholder="Phone" required>
        <input type="text"  name="doc_sal" class="form-control" placeholder="Salary" onkeypress="return isnum(event)" maxlength="10" required>
        <input type="date"  name="doc_doj" class="form-control" placeholder="DOJ" required>
        <input type="time"  name="doc_start" class="form-control" placeholder="Shift Start Time" required>
        <input type="time"  name="doc_end" class="form-control" placeholder="Shift End Time" required>
        <input type="text"  name="doc_fees" class="form-control" onkeypress="return isnum(event)" maxlength="10" placeholder="Consultation Fees" required>
        <select id="drop1" name="doc_dept" class="form-control" required>
      <option disabled selected>Name Of Department</option>


    </select>
        <select id="drop2" name="doc_vtype" class="form-control" required>
      <option disabled selected>Consultation Type</option>
      <option>FT</option>
      <option>PT</option>

    </select>
    <br>
    <input type="submit" name="submit" class="btn btn-lg btn-primary btn-block" value="Add Doctor" ></input>
      </form>
        </div>
        <div id="list_lab" class="tab-pane fade">
  		<label>Search: <input type="search" id="tags_lab" class="form-control" onblur="dept_hilight(this)" ></input></label><br><br>
  				<div class="table-responsive">
  					<table class="table table-striped" id="lab_table_body">
  							<tr>
  			<form id="lab_form" method="POST">
  								<th>#</th>
  								<th>Lab Name</th>
  								<th>Head</th>
  								<th>Cost</th>
  				<th><span class="glyphicon glyphicon-wrench"></span>  Manage</th>
  								<input type="hidden" id="lab_hidden" name="lab_hidden"></input>
  			</form>
  							</tr>

  					</table>
  				</div>
  			</div>
  			<div id="add_lab" class="tab-pane fade">
  		<form class="form-signin" method="POST"  action="admin_add_lab.php">
  			<h2 class="form-signin-heading">Add Laboratory</h2>


  			<input type="text" name="lab_name" class="form-control" placeholder="Lab Name" required>
  			<select id="drop1c" name="lab_head" class="form-control" placeholder="Lab Head" required>
  		<option disabled selected> Lab In Charge</option>

  	</select>
  	<select id="drop1l" name="lab_dept" class="form-control" required>
  		<option disabled selected>Name Of Department</option>
  	</select>
  	<input type="text" name="lab_cost"  class="form-control" placeholder="Cost" onkeypress="return isnum(event)" maxlength="10" required>

  			<br>
  	<input type="submit" name="submit" class="btn btn-lg btn-primary btn-block" value="Add Laboratory" ></input>
  		</form>
  			 </div>
         <div id="list_dept" class="tab-pane fade">
    <label>Search: <input type="search" id="tags_dept" class="form-control" onblur="dept_hilight(this)"></input></label><br><br>
          <div class="table-responsive">
            <table class="table table-striped" id="dept_table_body">
                <tr>
        <form id="dept_form" method="POST">
                  <th>#</th>
                  <th>Department Name</th>
                  <th>HOD</th>
                  <th>No Of Employees</th>
          <th><span class="glyphicon glyphicon-wrench"></span>  Manage</th>
                  <input type="hidden" id="dept_hidden" name="dept_hidden"></input>
        </form>
                </tr>
            </table>
          </div>
        </div>
        <div id="add_dept" class="tab-pane fade">
           <form class="form-signin" method="POST"  action="admin_add_dept.php">
        <h2 class="form-signin-heading">Add Department</h2>
        <input type="text"  name="dept_name" id="dept_name" class="form-control" placeholder="Department Name" required>
        <input type="text"  name="HOD" id="HOD" class="form-control" placeholder="Head Of Department" required>
        <br>
    <input type="submit" name="submit" id="submit" class="btn btn-lg btn-primary btn-block" value="Add Department" ></input>
      </form>
        </div>
        <div id="list_drug" class="tab-pane fade">
        <div class="table-responsive">
        <table class="table table-striped">
        <tr>
        <form action="#list_drug" method="POST">
          <label><input type="text" class="form-control" name="search_string">
            <input  type="submit" name="search_drug" value="Search"></lable><br><br>
                  <th>#</th>
                  <th>Name</th>
                  <th>Description</th>
                  <th>Cost</th>
                  <th>Supplier</th>
                  <th>Units in pack</th>
        </form>
        </tr>
        <?php
        if(isset($_POST['search_drug'])){
          $drugs=$fO->getDrugName($_POST['search_string']);
          foreach($drugs as $drug){
            printf("<tr><td><a data-toggle=\"tab\" href=\"#add_drug?SelectedDrug=%s\">" .$drug['med_id'] . "</a></td>
					<td>%s</td>
					<td>%s</td>
					<td>%s</td>
					<td>%s</td>
					<td>%s</td>
					</tr>",
        $drug['med_id'],
        $drug['medicine_name'],
        $drug['description'],
        $drug['cost'],
        $drug['manufacturing_company'],
        $drug['med_id']
          );
          }
        }
        else{
          $drugs=$fO->getAllDrugs();
          foreach($drugs as $drug){
            printf("<tr><td><a data-toggle=\"tab\" href=\"#add_drug?SelectedDrug=%s\">" .$drug['med_id'] . "</a></td>
					<td>%s</td>
					<td>%s</td>
					<td>%s</td>
					<td>%s</td>
					<td>%s</td>
					</tr>",
        $drug['med_id'],
        $drug['medicine_name'],
        $drug['description'],
        $drug['cost'],
        $drug['manufacturing_company'],
        $drug['med_id']
          );
          }
        }
         ?>
       </table>
          </div>
        </div>
        <div id="add_drug" class="tab-pane fade in active">
                      <form class="form-signin" method="POST"  action="#add_drug">
                        <h2 class="form-signin-heading">Add Drug</h2>
                        <input type="text"  class="form-control" placeholder="Medicine Name" name="medicine_name" id="medicine_name" required>
                        <input type="text"  class="form-control" placeholder="Medicine Category" name="medicine_category" id="medicine_category" required>
                        <input type="text"  class="form-control" placeholder="Description" name="description" id="description" required>
                        <input type="number"  class="form-control" placeholder="Price"  name="price" id="price" required>
                        <input type="date"  class="form-control" placeholder="Manufacturing Date" name="manufacturing_date" id="manufacturing_date" required>
                        <input type="date"  class="form-control" placeholder="Expiry Date"  name="expiry_date" id="expiry_date" required>
                        <input type="text"  class="form-control" placeholder="Manufacturing Company" name="manufacturing_company" id="manufacturing_company" required>
                        <input type="number"  class="form-control" placeholder="Status" name="status" id="status" required>
                        <br>
                        <input type="submit" class="btn btn-lg btn-primary btn-block" value="Add Medicine" ></input>
                      </form>
                    </div>
    </div>
    </body>
    </html>






















    <header>
      <!--<link rel="stylesheet" href="dist/css/bootstrap-theme.min.css">
      <script src="jquery-3.1.0.min.js"></script>
      <script src="dist/js/bootstrap.min.js"></script> -->
      <!DOCTYPE html>
      <html lang="en">
      <head>
        <title>Hospital Management Software</title>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <link rel="stylesheet" href="dist/css/bootstrap.min.css">
        <script src="jquery-3.1.0.min.js"></script>
        <script src="dist/js/bootstrap.min.js"></script>
      <style type="text/css">
      	#size
      	{
      	width:100px;
      	height:150px;
      	}
      	.form-signin
      	{
          max-width: 330px;
          padding: 15px;
          margin: 0 auto;
          }
      	.form-signin .form-signin-heading,
          .form-signin .checkbox
      	{
           margin-bottom: 10px;
          }
         .form-signin .checkbox
         {
          font-weight: normal;
         }

         .form-signin .form-control
         {
          position: relative;
          height: auto;
          -webkit-box-sizing: border-box;
          -moz-box-sizing: border-box;
          box-sizing: border-box;
          padding: 10px;
          font-size: 16px;
         }
         .form-signin .form-control:focus
         {
          z-index: 2;
         }
      	</style>
        <script>
        $(document).ready(function() {
          if(location.hash) {
              $('a[href="' + location.hash + '"]').tab('show');
          }
          $(document.body).on("click", "a[data-toggle]", function(event) {
              location.hash = this.getAttribute("href");
          });
      });
      $(window).on('popstate', function() {
          var anchor = location.hash || $("a[data-toggle=tab]").first().attr("href");
          $('a[href=' + anchor + ']').tab('show');
      });
        </script>
      </head>
      <body class="container">
      <nav class="navbar navbar-default">
        <div class="container-fluid">
          <ul class="nav nav-pills">
            <li class="active"><a  href="manage_patient.php">Dashboard</a></li>
            <li><a href="manage_nurse.php">Nurse</a></li>
            <li><a href="manage_doctor.php">Doctor</a></li>
            <li><a href="manage_laboratory.php">Labaratory</a></li>
            <li><a href="manage_pharmacy.php">Pharmacy</a></li>
            <li><a href="reports.php">Reports</a></li>
            <li><a href="manage_settings.php">Settings</a></li>
            <li><a href="logout.php">Logout</a></li>
          </ul>
        </div>
      </nav>

    </header>
