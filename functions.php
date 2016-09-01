<?PHP
class functions{
    private $conn;
	function __construct() {
		require_once 'DB_Connect.php';
		$db=new DB_Connect();
		$this->conn=$db->connect();
	}
  function showMessage($text) {
  echo '<script language=javascript>
          alert(\''.$text.'\')
        </script>';
}
public function getUserByUsernameAndPassword($username, $password){
 		$password=sha1($password);
        $stmt = $this->conn->prepare("SELECT * FROM user, ugroup WHERE user.ugroup_id = ugroup.ugroup_id AND user_name = ? AND user_pw LIKE ?");
		$data=array($username, $password);
		$stmt->execute($data);
        $stmt->setFetchMode(PDO::FETCH_ASSOC);
		return $stmt->fetch();
    }
/**
	* Generate unique fingerprint for every user session
	* @return string fingerprint : Unique fingerprint generated from remote server address, random string, and user agent
	*/
public function fingerprint(){
		return $fingerprint = md5($_SERVER['REMOTE_ADDR'].'jikI/20Y,!'.$_SERVER['HTTP_USER_AGENT']);
	}

/**
	* Check if current user is logged in
	*/
	 function checkLogin() {
		$fingerprint=$this->fingerprint();
		session_start();
		if (!isset($_SESSION['log_user']) || $_SESSION['log_fingerprint'] != $fingerprint) $this->logout();
		session_regenerate_id();
	}

/**
	* Check if current user logged out properly last time
	*/
	function checkLogout(){
		if ($_SESSION['logrec_logout'] == 0){
			$this->showMessage("You forgot to logout last time. Please remember to log out properly.");
			$_SESSION['logrec_logout'] = 1;
		}
	}

/**
	* Check if current user has Admin permission
	*/
	function checkPermissionAdmin() {
		if ($_SESSION['log_admin']!=='1'){
			header('Location: start.php');
			die();
		}
	}

/**
	* Check if current user has Delete permission
	*/
	function checkPermissionDelete() {
		if ($_SESSION['log_delete']!=='1'){
			header('Location: start.php');
			die();
		}
	}

/**
	* Check if current user has permission to access Reports
	*/
	function checkPermissionReport() {
		if ($_SESSION['log_report']!=='1'){
			header('Location: start.php');
			die();
		}
	}

	/**
	* Logout procedure: Delete session variables
	* and cookies, destroy user session.
	*/
	function logout(){
		/* Delete all Session Variables */
		$_SESSION = array();

		/* If a session cookie was used, delete it */
		if (ini_get("session.use_cookies")) {
			$params = session_get_cookie_params();
			setcookie(session_name(), '', time() - 86400, $params["path"], $params["domain"], $params["secure"], $params["httponly"]);
		}

		/* Finally, delete the Session */
		session_destroy();

		/* Forward to logout_success.php */
		header('Location: login.php');
		die;
	}

/**
	* Check if an SQL statement has succeded
	*/
	function checkSQL($sqlquery){
		if (!$sqlquery) die ('SQL-Statement failed: '.mysql_error());
	}

/**
	* Pushing system settings into session variables
	*/
	public function getSettings(){
	    $stmt = $this->conn->prepare("SELECT * FROM settings");
		$stmt->execute();
        $stmt->setFetchMode(PDO::FETCH_ASSOC);
		while($row_settings = $stmt->fetch()){

			switch ($row_settings['set_id']){
				case 1:
					$_SESSION['set_msb'] = $row_settings['set_value'];
					break;
				case 2:
					$_SESSION['set_minlp'] = $row_settings['set_value'];
					break;
				case 3:
					$_SESSION['set_maxlp'] = $row_settings['set_value'];
					break;
				case 4:
					$_SESSION['set_cur'] = $row_settings['set_value'];
					break;
				case 5:
					$_SESSION['set_auf'] = $row_settings['set_value'];
					break;
				case 6:
					$_SESSION['set_deact'] = $row_settings['set_value'];
					break;
				case 7:
					$_SESSION['set_dashl'] = $row_settings['set_value'];
					break;
				case 8:
					$_SESSION['set_dashr'] = $row_settings['set_value'];
					break;
				case 9:
					$_SESSION['set_intcalc'] = $row_settings['set_value'];
					break;
				case 10:
					$_SESSION['set_maxguar'] = $row_settings['set_value'];
					break;
				case 11:
					$_SESSION['set_minmemb'] = $row_settings['set_value'];
					break;
				case 12:
					$_SESSION['set_maxpsr'] = $row_settings['set_value'];
					break;
			}
		}
	}
	function getShareValue(){
	try{
	$stmt->$this->conn->prepare("SELECT shareval_value FROM shareval WHERE shareval_id IN (SELECT MAX(shareval_id) FROM shareval)");
	$stmt->execute();
	$_SESSION['share_value'] = $result_shareval['shareval_value'];
	return $_SESSION['share_value'];
	}
	catch(PDOException $e){
	echo $e->getMessage();
	}
	}
	function sanitize($var) {
		$var=filter_var($var, FILTER_SANITIZE_STRING);
		return $var;
	}

	function convertDays($days){
		return $seconds = $days * 86400;
	}

	function convertMonths($months){
		return $seconds = $months * 2635200; // Seconds for 30.5 days
	}

/**
	* Check if a GET parameter with a Customer ID has been set
	* If not, return to start page.
	*/
	function getCustID(){
		if (isset($_GET['cust'])) $_SESSION['cust_id'] = $_GET['cust'];
		else header('Location: start.php');
	}
	function getLoanID(){
		if (isset($_GET['lid'])) $_SESSION['loan_id'] = sanitize($_GET['lid']);
		else header('Location: customer.php?cust='.$_SESSION['cust_id']);
	}

	function includeHead($title, $endFlag = 1) {
		echo '<head>
			<meta http-equiv="Content-Script-Type" content="text/javascript">
			<meta http-equiv="Content-Style-Type" content="text/css">
			<meta name="robots" content="noindex, nofollow">
      <meta charset="utf-8">
      <meta name="viewport" content="width=device-width, initial-scale=1">
      <script src="jquery/jquery-2.2.1.min.js"></script>
			<script src="jquery/jquery-ui-1.11.4/jquery-ui.min.js"></script>
      <link rel="stylesheet" href="dist/css/bootstrap.min.css">
      <script src="dist/js/bootstrap.min.js"></script>
      <script src="bootbox.min.js"></script>
			<title>webafriq digital partners | '.$title.'</title>
			<link rel="shortcut icon" href="ico/favicon.ico" type="image/x-icon">
			<link rel="stylesheet" href="css/mangoo.css" />
      <link rel="stylesheet" href="jquery/jquery-ui-1.11.4/jquery-ui.min.css">
			<script>
        $("form input").focus(function() {
            var titleText = $(this).attr("placeholder");
            $(this).tooltip({
              title: titleText,
              trigger: "focus",
              container: "form"
            });
      });
			</script>
      <style type="text/css">
      	#size
      	{
      	width:100px;
      	height:150px;
      	}
          .form-signin input,.form-signin select
        	{
            display: block;
            margin-bottom: 1em;
            }
      	.form-signin
      	{
          padding: 15px;
          margin: 0 auto;
          }
      	.form-signin .form-signin-heading,
          .form-signin .checkbox
      	{
           margin-bottom: 10px;
          }
         .form-signin .form-control:focus
         {
          z-index: 2;
         }
         .form-signin .prescription
    	   {
    			position: relative;
    			height: 100px;
    			width : 100%;
    			-webkit-box-sizing: border-box;
    			-moz-box-sizing: border-box;
    			box-sizing: border-box;
    			padding: 10px;
    			font-size: 16px;
    	   }
      	</style>
			';
		if ($endFlag == 1) echo '</head>';
	}

/**
	* Generate Menu bar
	* @param int tab_no : Number of currently selected menu tab.
	*/
	function includeMenu($tab_no){
		echo '
		<!-- MENU HEADER -->
		<div id="menu_header">
			<div id="menu_logout">
				<ul>
					<li>'.$_SESSION['log_user'].'
						<ul>
							<li><a href="logout.php"><i class="fa fa-sign-out fa-fw"></i> Logout</a></li>
						</ul>
					</li>
				</ul>
			</div>
		</div>';

		echo '
		<!-- MENU TABS -->
		<div id="menu_tabs">
			<ul>
				<li';
				if ($tab_no == 1) echo ' id="tab_selected"';
				echo '><a href="patient_list.php">Admission</a></li>
				<li';
				if ($tab_no == 2) echo ' id="tab_selected"';
				echo '><a href="nurse_patients.php">Nurse</a></li>
				<li';
				if ($tab_no == 3) echo ' id="tab_selected"';
				echo '><a href="manage_doctor.php">Doctor</a></li>
				<li';
				if ($tab_no == 4) echo ' id="tab_selected"';
				echo '><a href="manage_laboratory.php">Laboratory</a></li>
        <li';
				if ($tab_no == 5) echo ' id="tab_selected"';
				echo '><a href="manage_pharmacy.php">Pharmacy</a></li>
				<li';
				if ($tab_no == 6) echo ' id="tab_selected"';
				echo '><a href="rep_incomes.php">Billing</a></li>
				<li';
				if ($tab_no == 7) echo ' id="tab_selected"';
				echo '><a href="manage_settings.php">Reports</a></li>
        <li';
				if ($tab_no == 8) echo ' id="tab_selected"';
				echo '><a href="manage_inventory.php">inventory</a></li>
        <li';
        if ($tab_no == 9) echo ' id="tab_selected"';
        echo '><a href="manage_settings.php">Settings</a></li>';
			echo '</ul>
		</div>';
	}

/**
	* Alternate table rows background color for improved readability
	* @param int row_color : Indicator for row color
	*/
	function tr_colored(&$row_color) {
		if ($row_color == 0){
			echo '<tr>';
			$row_color = 1;
		}
		else {
			echo '<tr class="alt">';
			$row_color = 0;
		}
	}

  function getAllDrugs(){
    try{
      $stmt=$this->conn->prepare("SELECT * FROM drug");
      $stmt->execute();
      $resultSet=$stmt->fetchALL();
  		return $resultSet;
    }
    catch(PDOException $e){
			echo $e->getMessage();
		}
  }
  function getDrugName($drugName){
    try{
      $stmt=$this->conn->prepare("SELECT * FROM drug WHERE name LIKE ?");
      $drugName="%".$drugName."%";
      $params=array($drugName);
      $stmt->execute($params);
      $resultSet=$stmt->fetchALL();
  		return $resultSet;
    }
    catch(PDOException $e){
			echo $e->getMessage();
		}
  }
  function getDrugById($drugId){
    try{
      $stmt=$this->conn->prepare("SELECT * FROM drug WHERE id=?");
      $params=array($drugId);
      $stmt->execute($params);
      $stmt->setFetchMode(PDO::FETCH_ASSOC);
  		return $stmt->fetch();
    }
    catch(PDOException $e){
			echo $e->getMessage();
		}
  }
  function updateDrug($drugId,$name,$bPrice,$sPrice,$description,$company,$category){
		try{
			$stmt=$this->conn->prepare("UPDATE drug SET name=?,buying_price=?,selling_price=?
      description=?,company=?,category=? WHERE id=?");
			$params=array($name,$bPrice,$sPrice,$description,$company,$category,$drugId);
			$stmt->execute($params);
		}
		catch(PDOException $e){
			echo $e->getMessage();
		}
	}
  function getAllPatients(){
    try{
      $stmt=$this->conn->prepare("SELECT * FROM patient");
      $stmt->execute();
      $resultSet=$stmt->fetchALL();
  		return $resultSet;
    }
    catch(PDOException $e){
			echo $e->getMessage();
		}
  }
  function getNewPatientEncounters(){
    try{
      $stmt=$this->conn->prepare("SELECT enc.*,pa.p_name FROM encounter enc
      INNER JOIN patient pa ON enc.patient_id=pa.patient_id
      WHERE enc.open=1");
      $stmt->execute();
      $resultSet=$stmt->fetchALL();
  		return $resultSet;
    }
    catch(PDOException $e){
			echo $e->getMessage();
		}
  }
  function getPatientByName($name){
    try{
      $stmt=$this->conn->prepare("SELECT * FROM patient WHERE p_name LIKE ?");
      $names="%".$name."%";
      $params=array($names);
      $stmt->execute($params);
      $resultSet=$stmt->fetchALL();
  		return $resultSet;
    }
    catch(PDOException $e){
			echo $e->getMessage();
		}
  }
  function getPatientByID($id){
    try{
      $stmt=$this->conn->prepare("SELECT * FROM patient WHERE patient_id LIKE ?");
      $params=array($id);
      $stmt->execute($params);
      $stmt->setFetchMode(PDO::FETCH_ASSOC);
  		return $stmt->fetch();
    }
    catch(PDOException $e){
			echo $e->getMessage();
		}
  }
  function getNurseAllPatients($userName){
    try{
      $stmt=$this->conn->prepare("SELECT patient.p_name,patient.patient_id,nurse_work.shift_start,
      nurse_work.shift_end,nurse_work.food,consultation.observation
      FROM nurse_work
      INNER JOIN patient on nurse_work.patient_id=patient.patient_id
      INNER JOIN consultation on patient.patient_id=consultation.patient_id
      WHERE nurse_work.username LIKE ?
      AND nurse_work.patient_id=consultation.patient_id");
      $params=array($userName);
      $stmt->execute($params);
      $resultSet=$stmt->fetchALL();
      return $resultSet;
    }
    catch(PDOException $e){
    echo $e->getMessage();
    }
  }
  function getNurseSinglePatient($userName,$patientName){
    try{
      $stmt=$this->conn->prepare("SELECT patient.p_name,patient.patient_id,nurse_work.shift_start,
      nurse_work.shift_end,nurse_work.food,consultation.observation
      FROM patient
      INNER JOIN nurse_work  on patient.patient_id=nurse_work.patient_id
      INNER JOIN consultation on patient.patient_id=consultation.patient_id
      WHERE nurse_work.username LIKE ? and patient.p_name LIKE ?");
      $patientName="%".$patientName."%";
      $params=array($userName,$patientName);
      $stmt->execute($params);
      $resultSet=$stmt->fetchALL();
      return $resultSet;
    }
    catch(PDOException $e){
    echo $e->getMessage();
    }
  }
  function getDoctorAppointments($userName){
    try{
      $stmt=$this->conn->prepare("SELECT cons.*,pat.p_name FROM consultation cons
      INNER JOIN patient pat on cons.patient_id=pat.patient_id
      WHERE cons.username LIKE ?");
      $params=array($userName);
      $stmt->execute($params);
      $resultSet=$stmt->fetchALL();
      return $resultSet;
    }
    catch(PDOException $e){
      echo $e->getMessage();
    }
  }
  function getDoctorAppointmentByPatientName($userName,$patientName){
    try{
      $stmt=$this->conn->prepare("SELECT cons.*,pat.p_name FROM consultation cons
      INNER JOIN patient pat on cons.patient_id=pat.patient_id
      WHERE cons.username LIKE ? AND pat.p_name LIKE ?");
      $patientName="%".$patientName."%";
      $params=array($userName,$patientName);
      $stmt->execute($params);
      $resultSet=$stmt->fetchALL();
      return $resultSet;
    }
    catch(PDOException $e){
      echo $e->getMessage();
    }
  }
  function getDrugByName($name){
    try{
      $stmt=$this->conn->prepare("SELECT * FROM drug WHERE name Like ?");
      $name='%'.$name.'%';
      $params=array($name);
      $stmt->execute($params);
      $resultSet=$stmt->fetchALL();
      return $resultSet;
    }
    catch(PDOException $e){
      echo $e->getMessage();
    }
  }
  function getSingleDrugByName($name){
    try{
      $stmt=$this->conn->prepare("SELECT id,name FROM drug WHERE name Like ? LIMIT 1");
      $params=array($name);
      $stmt->execute($params);
      $stmt->setFetchMode(PDO::FETCH_ASSOC);
      return $stmt->fetch();
    }
    catch(PDOException $e){
      echo $e->getMessage();
    }
  }
  function getAllDose(){
    try{
      $stmt=$this->conn->prepare("SELECT * FROM dosage");
      $stmt->execute();
      $resultSet=$stmt->fetchALL();
  		return $resultSet;
    }
    catch(PDOExcption $e){
      echo $e->getMessage();
    }
  }
  function getAllDurations(){
    try{
      $stmt=$this->conn->prepare("SELECT * FROM duration");
      $stmt->execute();
      $resultSet=$stmt->fetchALL();
  		return $resultSet;
    }
    catch(PDOExcption $e){
      echo $e->getMessage();
    }
  }
  function savePrescription($patient,$observation,$user){
    try{
      $time=date("d-m-Y:h:i:sa");
      $stmt=$this->conn->prepare("INSERT INTO consultation (patient_id,observation,con_time,username) VALUES (?,?,?,?)");
      $params=array($patient,$observation,$time,$user);
      $stmt->execute($params);
      return $this->conn->lastInsertId();
    }
    catch(PDOException $e){
      echo $e->getMessage();
    }
  }
  function savePrescriptionDetails($lastId,$drug,$dose,$duration){
    try{
      $medicine=$this->getSingleDrugByName($drug);
      $stmt=$this->conn->prepare("INSERT INTO prescription_order (prescription_id,drug_id,dose_id,duration_id)
      VALUES(?,?,?,?)");
      $params=array($lastId,$medicine['med_id'],$dose,$duration);
      $stmt->execute($params);
    }
    catch(PDOException $e){
      echo $e->getMessage();
    }
  }
  function getAllPharmacyQueue($userName){
    try{
      $stmt=$this->conn->prepare("SELECT c.*,p.p_name FROM consultation c
      INNER JOIN patient p ON c.patient_id=p.patient_id
      where username=? AND completed=?");
      $params=array($userName,0);
      $stmt->execute($params);
      $resultSet=$stmt->fetchALL();
  		return $resultSet;
    }
    catch(PDOException $e){
      echo $e->getMessage();
    }
  }
  function getPharmacyQueue($userName,$patientName){
    try{
      $stmt=$this->conn->prepare("SELECT c.* FROM consultation c
      INNER JOIN patient p ON c.patient_id=p.patient_id
      where username=? AND completed=? AND p.p_name LIKE ?");
      $patientName='%'.$patientName.'%';
      $params=array($userName,0,$patientName);
      $stmt->execute($params);
      $resultSet=$stmt->fetchALL();
  		return $resultSet;
    }
    catch(PDOException $e){
      echo $e->getMessage();
    }
  }
  function getPrescriptionById($id){
    try{
      $stmt=$this->conn->prepare("SELECT * FROM consultation WHERE prescription_id=?");
      $params=array($id);
      $stmt->execute($params);
      $stmt->setFetchMode(PDO::FETCH_ASSOC);
      return $stmt->fetch();
    }
    catch(PDOException $e){
      echo $e->getMessage();
    }
  }
  function getPrescriptionDetailsById($id){
    try{
      $stmt=$this->conn->prepare("SELECT pha.med_id,pha.medicine_name,pha.stock_available,pha.cost,dos.symbol,dos.id as dosId,
      dur.symbol,dur.id as durId,dos.name as doseName,dur.name as durName FROM prescription_order p
      INNER JOIN pharmacy pha on p.drug_id=pha.med_id
      INNER JOIN dosage dos on p.dose_id=dos.id
      INNER JOIN duration dur on p.duration_id=dur.id
      WHERE p.prescription_id=?");
      $params=array($id);
      $stmt->execute($params);
      return $stmt->fetchALL();
    }
    catch(PDOException $e){
      echo $e->getMessage();
    }
  }
  function dispensePrescription($prescriptionId,$drug,$dose,$duration,$quantity,$amount,$user){
    try{
      $stmt=$this->conn->prepare("INSERT INTO prescription_dispensed (prescription_id,drug_id,dose,duration,quantity,amount_paid,username)
      VALUES (?,?,?,?,?,?,?)");
      $params=array($prescriptionId,$drug,$dose,$duration,$quantity,$amount,$user);
      $stmt->execute($params);
    }
    catch(PDOException $e){
      echo $e->getMessage();
    }
  }
  function addNewDrug($name,$bPrice,$sPrice,$description,$company,$category){
    try{
      $stmt=$this->conn->prepare("INSERT INTO drug (name,buying_price,selling_price,description,company,category)
      VALUES(?,?,?,?,?,?)");
      $params=array($name,$bPrice,$sPrice,$description,$company,$category);
      $stmt->execute($params);
      }
    catch(PDOException $e){
      echo $e->getMessage();
    }
  }
  function getAllDrugCategory(){
    try{
      $stmt=$this->conn->prepare("SELECT * FROM drug_category");
      $stmt->execute();
      return $stmt->fetchALL();
    }
    catch(PDOException $e){
      echo $e->getMessage();
    }
  }
  function getAllSuppliesrs(){
    try{
      $stmt=$this->conn->prepare("SELECT * FROM supplier");
      $stmt->execute();
      return $stmt->fetchALL();
    }
    catch(PDOException $e){
      echo $e->getMessage();
    }
  }
  function savePurchaseOrder($entry_date,$supplier){
    try{
      $stmt=$this->conn->prepare("INSERT INTO purchase_order (entry_date,supplier_id) VALUES (?,?)");
      $params=array($entry_date,$supplier);
      $stmt->execute($params);
      return $this->conn->lastInsertId();
    }
    catch(PDOException $e){
      echo $e->getMessage();
    }
  }
  function saveInventory($lastId,$drugName,$quantity,$expiry_date,$batch_no){
    try{
      $drug=$this->getSingleDrugByName($drugName);
      $stmt=$this->conn->prepare("INSERT INTO inventory (purchase_order_id,drug_id,quantity,expiry_date,batch_no) VALUES (?,?,?,?,?)");
      $params=array($lastId,$drug['id'],$quantity,$expiry_date,$batch_no);
      $stmt->execute($params);
    }
    catch(PDOException $e){
      echo $e->getMessage();
    }
  }
  function getAllBloodGroups(){
    try{
      $stmt=$this->conn->prepare("SELECT * FROM blood_group");
      $stmt->execute();
      return $stmt->fetchALL();
    }
    catch(PDOException $e){
      echo $e->getMessage();
    }
  }
  function savePatient($name,$age,$bloodGroup,$phone,$address){
    try{
      $stmt=$this->conn->prepare("INSERT INTO patient (p_name,age,blood_group,phno,address) VALUES (?,?,?,?,?)");
      $params=array($name,$age,$bloodGroup,$phone,$address);
      $stmt->execute($params);
    }
    catch(PDOException $e){
      echo $e->getMessage();
    }
  }
  function updatePatient($id,$name,$age,$bloodGroup,$phone,$address){
    try{
      $stmt=$this->conn->prepare("UPDATE patient SET p_name=?,age=?,blood_group=?,phno=?,address=? WHERE patient_id=?");
      $params=array($name,$age,$bloodGroup,$phone,$address,$id);
      $stmt->execute($params);
    }
    catch(PDOException $e){
      echo $e->getMessage();
    }
  }
  function saveEncounter($weight,$temperature,$bloodPressure,$pulse,$respiration,$user){
    try{
      $stmt=$this->conn->prepare("INSERT INTO encounter (weight,temperature,blood_pressure,pulse,respiration,added_by,time_created)
      VALUES(?,?,?,?,?,?)");
      $params=array($weight,$temperature,$bloodPressure,$pulse,$respiration,$user);
      $stmt->execute($params);
    }
    catch(PDOException $e){
      echo $e->getMessage();
    }
  }
  function getAllEncounters(){
    try{
      $stmt=$this->conn->prepare("SELECT enc.*,pa.p_name FROM encounter enc
      INNER JOIN patient pa ON enc.patient_id=pa.patient_id");
      $stmt->execute();
      return $stmt->fetchALL();
    }
    catch(PDOException $e){
      echo $e->getMessage();
    }
  }
  function getPatientEncounters($nameOrId){
    try{
      $stmt=$this->conn->prepare("SELECT enc.*,pa.p_name FROM encounter enc
      INNER JOIN patient pa ON pa.patient_id=enc.patient_id
      WHERE pa.p_name LIKE ? OR pa.patient_id=?
      ORDER BY enc.encounter_id DESC");
      $params=array($nameOrId.'%',$nameOrId);
      $stmt->execute();
      return $stmt->fetchALL();
    }
    catch(PDOException $e){
      echo $e->getMessage();
    }
  }
  function getPatientPendingEncounters($nameOrId){
    try{
      $stmt=$this->conn->prepare("SELECT enc.*,pa.p_name FROM encounter enc
      INNER JOIN patient pa ON pa.patient_id=enc.patient_id
      WHERE pa.p_name LIKE ? OR pa.patient_id=?
      AND enc.open=1
      ORDER BY enc.encounter_id DESC");
      $params=array('%'.$nameOrId.'%',$nameOrId);
      $stmt->execute();
      return $stmt->fetchALL();
    }
    catch(PDOException $e){
      echo $e->getMessage();
    }
  }
  function getEncounterByID($id){
    try{
      $stmt=$this->conn->prepare("SELECT enc.*,pa.p_name FROM encounter enc
      INNER JOIN patient pa ON enc.patient_id=enc.patient_id WHERE encounter_id=?");
      $params=array($id);
      $stmt->execute($params);
      $stmt->setFetchMode(PDO::FETCH_ASSOC);
      return $stmt->fetch();
    }
    catch(PDOException $e){
      echo $e->getMessage();
    }
  }
  function updateEncounter($weight,$temperature,$bloodPressure,$pulse,$respiration,$user,$id){
    try{
      $stmt=$this->conn->prepare("UPDATE encounter SET weight=?,temperature=?,blood_pressure=?,pulse=?,respiration=?,updated_by=? WHERE encounter_id=?");
      $params=array($weight,$temperature,$bloodPressure,$pulse,$respiration,$user,$id);
      $stmt->execute($params);
    }
    catch(PDOException $e){
      echo $e->getMessage();
    }
  }
}
?>
