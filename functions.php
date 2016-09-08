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
	 function checkLogin() {
		$fingerprint=$this->fingerprint();
		session_start();
		if (!isset($_SESSION['log_user']) || $_SESSION['log_fingerprint'] != $fingerprint) $this->logout();
		session_regenerate_id();
	}
	function checkLogout(){
		if ($_SESSION['logrec_logout'] == 0){
			$this->showMessage("You forgot to logout last time. Please remember to log out properly.");
			$_SESSION['logrec_logout'] = 1;
		}
	}
	function checkPermissionAdmin() {
		if ($_SESSION['log_admin']!=='1'){
			header('Location: start.php');
			die();
		}
	}
	function checkPermissionDelete() {
		if ($_SESSION['log_delete']!=='1'){
			header('Location: start.php');
			die();
		}
	}
	function checkPermissionReport() {
		if ($_SESSION['log_report']!=='1'){
			header('Location: start.php');
			die();
		}
	}
	function logout(){
		$_SESSION = array();
		if (ini_get("session.use_cookies")) {
			$params = session_get_cookie_params();
			setcookie(session_name(), '', time() - 86400, $params["path"], $params["domain"], $params["secure"], $params["httponly"]);
		}
		session_destroy();
		header('Location: login.php');
		die;
	}
	function checkSQL($sqlquery){
		if (!$sqlquery) die ('SQL-Statement failed: '.mysql_error());
	}
	public function getHospitalCharges(){
	  $stmt = $this->conn->prepare("SELECT * FROM hospital_charges");
		$stmt->execute();
    $stmt->setFetchMode(PDO::FETCH_ASSOC);
		while($row = $stmt->fetch()){
			switch ($row['id']){
				case 1:
					$_SESSION['consultation_fee'] = $row['cost'];
					break;
			}
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
				echo '><a href="manage_nurse.php">Nurse</a></li>
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
				echo '><a href="manage_billing.php">Billing</a></li>
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
  function getInventoryByName($name){
    try{
      $stmt=$this->conn->prepare("SELECT drug.*,SUM(inv.quantity) as stock FROM drug
      INNER JOIN inventory inv ON drug.id=inv.drug_id
      WHERE name Like ?
      GROUP BY inv.drug_id");
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
  function getLabTestByName($name){
    try{
      $stmt=$this->conn->prepare("SELECT * FROM laboratory
      WHERE test Like ?");
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
      $stmt=$this->conn->prepare("SELECT * FROM drug WHERE name Like ? LIMIT 1");
      $params=array($name);
      $stmt->execute($params);
      $stmt->setFetchMode(PDO::FETCH_ASSOC);
      return $stmt->fetch();
    }
    catch(PDOException $e){
      echo $e->getMessage();
    }
  }
  function getInventoryItemByName($name){
    try{
      $stmt=$this->conn->prepare("SELECT drug.*,SUM(inv.quantity) as stock FROM drug
      INNER JOIN inventory inv ON drug.id=inv.drug_id
      WHERE name Like ?
      GROUP BY inv.drug_id LIMIT 1");
      $params=array($name);
      $stmt->execute($params);
      $stmt->setFetchMode(PDO::FETCH_ASSOC);
      return $stmt->fetch();
    }
    catch(PDOException $e){
      echo $e->getMessage();
    }
  }
  function getSingleLabTestByName($name){
    try{
      $stmt=$this->conn->prepare("SELECT * FROM laboratory
      WHERE test Like ? LIMIT 1");
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
  function savePrescription($patient,$observation,$user,$encounter){
    try{
      $date=$date=date('Y-m-d h:i:sa');
      $stmt=$this->conn->prepare("INSERT INTO consultation (patient_id,observation,con_time,username,encounter_id) VALUES (?,?,?,?,?)");
      $params=array($patient,$observation,$date,$user,$encounter);
      $stmt->execute($params);
      return $this->conn->lastInsertId();
    }
    catch(PDOException $e){
      echo $e->getMessage();
    }
  }
  function savePharmacyPrescriptionOrder($lastId,$drug,$dose,$duration){
    try{
      $medicine=$this->getSingleDrugByName($drug);
      $stmt=$this->conn->prepare("INSERT INTO prescription_order (prescription_id,drug_id,dose,duration)
      VALUES(?,?,?,?)");
      $params=array($lastId,$medicine['id'],$dose,$duration);
      $stmt->execute($params);
    }
    catch(PDOException $e){
      echo $e->getMessage();
    }
  }
  function saveLabTestPrescriptionOrder($lastId,$testId){
    try{
      $test=$this->getSingleLabTestByName($testId);
      $stmt=$this->conn->prepare("INSERT INTO lab_test_order (prescription_id,lab_test_id)
      VALUES(?,?)");
      $params=array($lastId,$test['lab_id']);
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
      where pharmacy_billed=? AND c.pharmacy_patient=?");
      $params=array(0,1);
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
      where c.pharmacy_billed=? AND c.pharmacy_patient=? AND p.p_name LIKE ?");
      $patientName='%'.$patientName.'%';
      $params=array(0,1,$patientName);
      $stmt->execute($params);
      $resultSet=$stmt->fetchALL();
  		return $resultSet;
    }
    catch(PDOException $e){
      echo $e->getMessage();
    }
  }
  function getLaboratoryQueue($userName,$patientName){
    try{
      $stmt=$this->conn->prepare("SELECT c.* FROM consultation c
      INNER JOIN patient p ON c.patient_id=p.patient_id
      where c.lab_billed=? AND c.lab_patient=? AND p.p_name LIKE ?");
      $patientName='%'.$patientName.'%';
      $params=array(0,1,$patientName);
      $stmt->execute($params);
      $resultSet=$stmt->fetchALL();
  		return $resultSet;
    }
    catch(PDOException $e){
      echo $e->getMessage();
    }
  }
  function getAllLabQueue(){
    try{
      $stmt=$this->conn->prepare("SELECT c.*,p.p_name FROM consultation c
      INNER JOIN patient p ON c.patient_id=p.patient_id
      where lab_billed=? AND c.lab_patient=?");
      $params=array(0,1);
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
      $stmt=$this->conn->prepare("SELECT d.id,d.name,SUM(inv.quantity) as quantity,d.buying_price,d.selling_price,p.dose,p.duration
      FROM prescription_order p
      INNER JOIN drug d on p.drug_id=d.id
      INNER JOIN inventory inv ON d.id=inv.drug_id
      WHERE p.prescription_id=?
      GROUP BY inv.drug_id");
      $params=array($id);
      $stmt->execute($params);
      return $stmt->fetchALL();
    }
    catch(PDOException $e){
      echo $e->getMessage();
    }
  }
  function getLabOrderByPrescriptionId($id){
    try{
      $stmt=$this->conn->prepare("SELECT lab.*
      FROM lab_test_order lto
      INNER JOIN laboratory lab ON lto.lab_test_id=lab.lab_id
      WHERE lto.prescription_id=?");
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
  function billPharmacyPrescription($prescriptionId,$drug,$dose,$duration,$quantity,$cost,$amount,$user,$collectedByNurse){
    try{
      $date=date('Y-m-d');
      $stmt=$this->conn->prepare("INSERT INTO prescription_dispensed (prescription_id,drug_id,dose,duration,quantity,cost,amount,username,date_dispensed,collected_by_nurse)
      VALUES (?,?,?,?,?,?,?,?,?,?)");
      $params=array($prescriptionId,$drug,$dose,$duration,$quantity,$cost,$amount,$user,$date,$collectedByNurse);
      $stmt->execute($params);
    }
    catch(PDOException $e){
      echo $e->getMessage();
    }
  }
  function billLabTestOrder($prescriptionId,$test,$cost,$result,$user){
    try{
      $stmt=$this->conn->prepare("INSERT INTO lab_order_dispensed (prescription_id,lab_test_id,cost,result,username)
      VALUES (?,?,?,?,?)");
      $params=array($prescriptionId,$test,$cost,$result,$user);
      $stmt->execute($params);
    }
    catch(PDOException $e){
      echo $e->getMessage();
    }
  }
  function pharmacyBilled($prescriptionId){
    try{
      $stmt=$this->conn->prepare("UPDATE consultation SET pharmacy_billed=? WHERE prescription_id=?");
      $status=1;
      $params=array($status,$prescriptionId);
      $stmt->execute($params);
    }
    catch(PDOException $e){
      echo $e->getMessage();
    }
  }
  function laboratoryBilled($prescriptionId){
    try{
      $stmt=$this->conn->prepare("UPDATE consultation SET lab_billed=? WHERE prescription_id=?");
      $status=1;
      $params=array($status,$prescriptionId);
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
  function saveEncounter($weight,$temperature,$bloodPressure,$pulse,$respiration,$user,$patient){
    try{
      $date=date('Y-m-d h:i:sa');
      $stmt=$this->conn->prepare("INSERT INTO encounter (weight,temperature,blood_pressure,pulse,respiration,added_by,time_created,patient_id)
      VALUES(?,?,?,?,?,?,?,?)");
      $params=array($weight,$temperature,$bloodPressure,$pulse,$respiration,$user,$date,$patient);
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
      $stmt->execute($params);
      return $stmt->fetchALL();
    }
    catch(PDOException $e){
      echo $e->getMessage();
    }
  }
  function getPatientActiveEncounter($nameOrId){
    try{
      $stmt=$this->conn->prepare("SELECT enc.*,pa.p_name FROM encounter enc
      INNER JOIN patient pa ON pa.patient_id=enc.patient_id
      WHERE pa.p_name LIKE ? OR pa.patient_id=?
      AND enc.open=?
      ORDER BY enc.encounter_id DESC");
      $params=array('%'.$nameOrId.'%',$nameOrId,1);
      $stmt->execute($params);
      return $stmt->fetchALL();
    }
    catch(PDOException $e){
      echo $e->getMessage();
    }
  }
  function getPatientActiveEncounterList(){
    try{
      $stmt=$this->conn->prepare("SELECT enc.*,pa.p_name FROM encounter enc
      INNER JOIN patient pa ON pa.patient_id=enc.patient_id
      WHERE enc.open=?
      ORDER BY enc.encounter_id DESC");
      $params=array(1);
      $stmt->execute($params);
      return $stmt->fetchALL();
    }
    catch(PDOException $e){
      echo $e->getMessage();
    }
  }
  function getAdmissionList(){
    try{
      $stmt=$this->conn->prepare("SELECT ad.*,pa.p_name FROM admission ad
      INNER JOIN patient pa ON ad.patient_id=pa.patient_id
      WHERE ad.discharged=?
      ORDER BY ad.id DESC");
      $params=array(0);
      $stmt->execute($params);
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
  function seenDoctor($encounter,$prescriptionId){
    try{
      $stmt=$this->conn->prepare("UPDATE encounter SET seen_doctor=?,prescription_id=? WHERE encounter_id=?");
      $status=1;
      $params=array($status,$prescriptionId,$encounter);
      $stmt->execute($params);
    }
    catch(PDOException $e){
      echo $e->getMessage();
    }
  }
  function getDrugActiveBatches($id){
    try{
      $stmt=$this->conn->prepare("SELECT batch_no,id FROM inventory
      WHERE drug_id=? AND quantity>?
      ORDER BY expiry_date");
      $quantity=0;
      $params=array($id,$quantity);
      $stmt->execute($params);
      return $stmt->fetchALL();
    }
    catch(PDOException $e){
      echo $e->getMessage();
    }
  }
  function deductStockFromInventory($inventoryId,$requiredQuantity,$drug_id){
    try{
      $stmt=$this->conn->prepare("SELECT quantity FROM inventory WHERE id=?");
      $params=array($inventoryId);
      $stmt->execute($params);
      $stmt->setFetchMode(PDO::FETCH_ASSOC);
      $result=$stmt->fetch();

      $batchQuantity=$result['quantity'];
      if($requiredQuantity<$batchQuantity || $requiredQuantity==$batchQuantity){
        $stmt=$this->conn->prepare("UPDATE inventory SET quantity=quantity-?
        WHERE id=?");
        $params=array($requiredQuantity,$inventoryId);
        $stmt->execute($params);
      }
      else{
        $stmt=$this->conn->prepare("UPDATE inventory SET quantity=quantity-?
        WHERE id=?");
        $params=array($batchQuantity,$inventoryId);
        $stmt->execute($params);
        $balance=$requiredQuantity-$batchQuantity;
        $batches=$this->getDrugActiveBatches($drug_id);
        foreach($batches as $batch){
          if($balance>0){
          if($batch['quantity']){}
          $stmt=$this->conn->prepare("UPDATE inventory SET quantity=quantity-?
          WHERE id=?");
          $params=array($batchQuantity,$inventoryId);
          $stmt->execute($params);
        }
        }
      }
    }
    catch(PDOException $e){
      echo $e->getMessage();
    }
  }
  function updatePharmacyPatientStatus($id){
    try{
      $stmt=$this->conn->prepare("UPDATE consultation SET pharmacy_patient=? WHERE prescription_id=?");
      $params=array(1,$id);
      $stmt->execute($params);
    }
    catch(PDOException $e){
      echo $e->getMessage();
    }
  }
  function updateLabPatientStatus($id){
    try{
      $stmt=$this->conn->prepare("UPDATE consultation SET lab_patient=? WHERE prescription_id=?");
      $params=array(1,$id);
      $stmt->execute($params);
    }
    catch(PDOException $e){
      echo $e->getMessage();
    }
  }
  function doctorAllowAdmission($id){
    try{
      $stmt=$this->conn->prepare("UPDATE encounter SET admit=? WHERE encounter_id=?");
      $params=array(1,$id);
      $stmt->execute($params);
    }
    catch(PDOException $e){
      echo $e->getMessage();
    }
  }
  function getAllInsuranceCompanies(){
    try{
      $stmt=$this->conn->prepare("SELECT * FROM insurance_company");
      $stmt->execute();
      return $stmt->fetchALL();
    }
    catch(PDOException $e){
      echo $e->getMessage();
    }
  }
  function getAllAllergies(){
    try{
      $stmt=$this->conn->prepare("SELECT * FROM allergy");
      $stmt->execute();
      return $stmt->fetchALL();
    }
    catch(PDOException $e){
      echo $e->getMessage();
    }
  }
  function getAllWards(){
    try{
      $stmt=$this->conn->prepare("SELECT * FROM ward");
      $stmt->execute();
      return $stmt->fetchALL();
    }
    catch(PDOException $e){
      echo $e->getMessage();
    }
  }
  function updateToInpatient($encounterId){
    try{
      $stmt=$this->conn->prepare("UPDATE encounter SET admitted=? WHERE encounter_id=?");
      $params=array(1,$encounterId);
      $stmt->execute($params);
    }
    catch(PDOException $e){
      echo $e->getMessage();
    }
  }
  function admitPatient($encounterId,$patient_id,$allergy,$insurance,$noOfAdmissions,$ward,$bedNo,$insurancePatient){
    try{
      $date=date('Y-m-d');
      $stmt=$this->conn->prepare("INSERT INTO admission (encounter_id,patient_id,allergy,insurance,no_of_admissions,ward,bed_no,admission_date,insurance_patient)
      VALUES(?,?,?,?,?,?,?,?,?)");
      $params=array($encounterId,$patient_id,$allergy,$insurance,$noOfAdmissions,$ward,$bedNo,$date,$insurancePatient);
      $stmt->execute($params);
    }
    catch(PDOException $e){
      echo $e->getMessage();
    }
  }
  function getAllergyById($id){
    try{
      $stmt=$this->conn->prepare("SELECT * FROM allergy WHERE id=?");
      $params=array($id);
      $stmt->execute($params);
      $stmt->setFetchMode(PDO::FETCH_ASSOC);
      return $stmt->fetch();
      }
    catch(PDOException $e){
      echo $e->getMessage();
    }
  }
  function getWardById($id){
    try{
      $stmt=$this->conn->prepare("SELECT * FROM ward WHERE id=?");
      $params=array($id);
      $stmt->execute($params);
      $stmt->setFetchMode(PDO::FETCH_ASSOC);
      return $stmt->fetch();
      }
    catch(PDOException $e){
      echo $e->getMessage();
    }
  }
  function getPharmacyOrdersByEncounterId($encounterId){
    try{
      $stmt=$this->conn->prepare("SELECT * FROM consultation WHERE encounter_id=? AND pharmacy_patient=?");
      $params=array($encounterId,1);
      $stmt->execute($params);
      return $stmt->fetchALL();
    }
    catch(PDOException $e){
      echo $e->getMessage();
    }
  }
  function getPharmacyDispensedByEncounterId($encounterId){
    try{
      $stmt=$this->conn->prepare("SELECT pd.*,d.name as dname FROM prescription_dispensed pd
      INNER JOIN consultation c ON pd.prescription_id=c.prescription_id
      INNER JOIN drug d ON pd.drug_id=d.id
      WHERE c.encounter_id=? AND pharmacy_patient=?");
      $params=array($encounterId,1);
      $stmt->execute($params);
      return $stmt->fetchALL();
    }
    catch(PDOException $e){
      echo $e->getMessage();
    }
  }
  function getPharmacyOrdersDispensedByPrescriptionId($prescriptionId){
    try{
      $stmt=$this->conn->prepare("SELECT pd.prescription_id,SUM(pd.amount) as amount,d.name as dname,d.id as drugId FROM prescription_dispensed pd
      INNER JOIN drug d ON pd.drug_id=d.id
      WHERE pd.prescription_id=? GROUP BY pd.drug_id");
      $params=array($prescriptionId);
      $stmt->execute($params);
      return $stmt->fetchALL();
    }
    catch(PDOException $e){
      echo $e->getMessage();
    }
  }
  function getLabOrdersByEncounterId($encounterId){
    try{
      $stmt=$this->conn->prepare("SELECT * FROM consultation WHERE encounter_id=? AND lab_patient=?");
      $params=array($encounterId,1);
      $stmt->execute($params);
      return $stmt->fetchALL();
    }
    catch(PDOException $e){
      echo $e->getMessage();
    }
  }
  function getLabOrdersDispensedByPrescriptionId($prescriptionId){
    try{
      $stmt=$this->conn->prepare("SELECT lab.lab_id,lab.cost as cost,lab.test as test,lod.prescription_id FROM lab_order_dispensed lod
      INNER JOIN laboratory lab ON lod.lab_test_id=lab.lab_id
      WHERE lod.prescription_id=?");
      $params=array($prescriptionId);
      $stmt->execute($params);
      return $stmt->fetchALL();
    }
    catch(PDOException $e){
      echo $e->getMessage();
    }
  }
  function getLabTestsByEncounterId($encounterId){
    try{
      $stmt=$this->conn->prepare("SELECT lod.*,lab.test as test FROM lab_order_dispensed lod
      INNER JOIN consultation c ON lod.prescription_id=c.prescription_id
      INNER JOIN laboratory lab ON lod.lab_test_id=lab.lab_id
      WHERE c.encounter_id=? AND lab_patient=?");
      $params=array($encounterId,1);
      $stmt->execute($params);
      return $stmt->fetchALL();
    }
    catch(PDOException $e){
      echo $e->getMessage();
    }
  }
  function getPatientHistory($patientId){
    try{
      $stmt=$this->conn->prepare("SELECT * FROM encounter WHERE patient_id=?");
      $params=array($patientId);
      $stmt->execute($params);
      return $stmt->fetchALL();
    }
    catch(PDOException $e){
      echo $e->getMessage();
    }
  }
  function receivePharmacyPayment($prescriptionId,$itemId,$cost,$user){
    try{
    $date=date('Y-m-d');
    $stmt=$this->conn->prepare("INSERT INTO pharmacy_payment (prescription_id,item_id,amount,date_paid,received_by)
    VALUES(?,?,?,?,?)");
    $params=array($prescriptionId,$itemId,$cost,$date,$user);
    $stmt->execute($params);
  }
  catch(PDOException $e){
    echo $e->getMessage();
  }
  }
  function receiveLabTestPayment($prescriptionId,$itemId,$cost,$user){
    try{
    $date=date('Y-m-d');
    $stmt=$this->conn->prepare("INSERT INTO lab_test_payment (prescription_id,test_id,cost,date_paid,received_by)
    VALUES(?,?,?,?,?)");
    $params=array($prescriptionId,$itemId,$cost,$date,$user);
    $stmt->execute($params);
  }
  catch(PDOException $e){
    echo $e->getMessage();
  }
  }
  function chargeConsultationFee($prescriptionId,$itemId,$cost,$user){
    try{
    $date=date('Y-m-d');
    $stmt=$this->conn->prepare("INSERT INTO hospital_charges_payment (prescription_id,item_id,cost,date_paid,received_by)
    VALUES(?,?,?,?,?)");
    $params=array($prescriptionId,$itemId,$cost,$date,$user);
    $stmt->execute($params);
  }
  catch(PDOException $e){
    echo $e->getMessage();
  }
  }
  function clearBill($encounterId){
    try{
      $stmt=$this->conn->prepare("UPDATE encounter SET bill_cleared=? WHERE encounter_id=?");
      $params=array(1,$encounterId);
      $stmt->execute($params);
    }
    catch(PDOException $e){
      echo $e->getMessage();
    }
  }
}
?>
