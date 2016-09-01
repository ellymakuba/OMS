<?PHP
	require 'functions.php';
	$fO=new functions();
	$fO->checkLogin();
	if (isset($_POST['create'])){
	}
  ?>
  <html>
  <?PHP $fO->includeHead('Drug List',0) ?>
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
    <a href="new_drug.php" class="btn btn-default btn-primary">New Drug</a>
  </div>
  <table class="table table-striped">
  <tr>
  <form action="#list_drug" method="POST">
            <th>#</th>
            <th>Name</th>
            <th>Description</th>
            <th>Cost</th>
            <th>Company</th>
            <th>Units in pack</th>
  </form>
  </tr>
  <?php
  if(isset($_REQUEST['srch-term'])){
    $drugs=$fO->getDrugName($_REQUEST['srch-term']);
    foreach($drugs as $drug){
      printf("<tr><td><a href=\"new_drug.php?SelectedDrug=%s\">" .$drug['id'] . "</a></td>
    <td>%s</td>
    <td>%s</td>
    <td>%s</td>
    <td>%s</td>
    <td>%s</td>
    </tr>",
  $drug['id'],
  $drug['name'],
  $drug['description'],
  $drug['buying_price'],
  $drug['selling_price'],
  $drug['id']
    );
    }
  }
  else{
    $drugs=$fO->getAllDrugs();
    foreach($drugs as $drug){
      printf("<tr><td><a href=\"new_drug.php?SelectedDrug=%s\">" .$drug['id'] . "</a></td>
    <td>%s</td>
    <td>%s</td>
    <td>%s</td>
    <td>%s</td>
    <td>%s</td>
    </tr>",
		$drug['id'],
	  $drug['name'],
	  $drug['description'],
	  $drug['buying_price'],
	  $drug['selling_price'],
	  $drug['id']
    );
    }
  }
   ?>
  </table>
    </div>
  </body>
  </html>
