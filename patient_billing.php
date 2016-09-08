<?PHP
	require 'functions.php';
  include 'bill_payment_cart.php';
	$fO=new functions();
	$fO->checkLogin();
	$fO->getHospitalCharges();
	if (isset($_POST['receive_payment'])){
		if((isset($_POST['paid']) && isset($_POST['total'])) && ($_POST['paid']>$_POST['total'])){
    if(count($_SESSION['pharmacy_cart']->lineItems)>0){
      foreach($_SESSION['pharmacy_cart']->lineItems as $pharmacyItem){
          $fO->receivePharmacyPayment($pharmacyItem->prescriptionId,$pharmacyItem->itemId,$pharmacyItem->cost,$_SESSION['log_user']);
      }
    }
		if(count($_SESSION['lab_tests']->lineItems)>0){
			foreach($_SESSION['lab_tests']->lineItems as $test){
          $fO->receiveLabTestPayment($test->prescriptionId,$test->itemId,$test->cost,$_SESSION['log_user']);
      }
		}
		if(count($_SESSION['consultation_charges']->lineItems)>0){
			foreach($_SESSION['consultation_charges']->lineItems as $item){
				$fO->chargeConsultationFee($item->prescriptionId,$item->itemId,$item->cost,$_SESSION['log_user']);
			}
		}
		$fO->clearBill($_SESSION['encounter']);
		unset($_SESSION['pharmacy_cart']);
		unset($_SESSION['lab_tests']);
		unset($_SESSION['encounter']);
		unset($_SESSION['consultation_charges']);
		//header('Location:manage_billing.php');
	}
	}
  if (isset($_POST['edit'])){
	}
  ?>
  <html>
  <?PHP $fO->includeHead('Patient Billing',0) ?>
	<script>
	$(document).ready(function(){
    var totalAmount=0;
    $(".amount").each(function(){
  		totalAmount +=Number($(this).val());
  	});
  	document.getElementById("total").value=totalAmount;
    $("#paid").change(function(){
    	document.getElementById("balance").value=parseInt($(this).val())-parseInt($("#total").val());
    });
		$('#company').hide();
		$('#insurance_patient').click(function(){
      if(this.checked)
        $('#company').show();
      else
       $('#company').hide();
    });
	});
	</script>
  </head>
  <body class="container">
    <?PHP $fO->includeMenu(6); ?>
  	<div id="menu_main">
      <a href="manage_billing.php">Manage Billing</a>
			<a href="patient_billing.php" id="item_selected">Patient Billing</a>
      </div>
      <?php
			if(isset($_REQUEST['selectedEncounter'])){
				$_SESSION['encounter']=$_REQUEST['selectedEncounter'];
			}
			if(isset($_SESSION['encounter'])){
				$encounter=$fO->getEncounterByID($_SESSION['encounter']);
				$patient=$fO->getPatientByID($encounter['patient_id']);
				if($encounter['bill_cleared']==1){
					echo '<div class="col-sm-3 col-md-3 pull-right">';
					echo '<span class="btn btn-lg btn-primary">Patient Bill Cleared</span>';
					echo '</div>';
				}
				?>
			<form class="form-signin" method="POST"  action="<?php echo $_SERVER['PHP_SELF']; ?>">
			<h2 class="form-signin-heading">Patient Dashboard</h2>
			<div class="form-inline">
				<label for="patient_id">Patient ID:</label>
				<input type="text"  name="patient_id" class="form-control" style="width:90%;float:right;"
				placeholder="Patient Name" required value="<?php echo $patient['patient_id']?>" readonly="">
			</div>
			<div style="clear:both;"></div>
			<div class="form-inline">
				<label for="patient_name">Patient Name:</label>
				<input type="text"  name="patient_name" class="form-control" style="width:90%;float:right;"
				placeholder="Patient Name" required value="<?php echo $patient['p_name']; ?>" readonly="">
			</div>
			<div style="clear:both;"></div>
			<div class="form-inline">
				<label for="encounter_date">Encounter Date:</label>
				<input type="text"  name="encounter_date" class="form-control" style="width:90%;float:right;"
				placeholder="Encounter Date" required value="<?php echo $encounter['time_created']; ?>" readonly="">
			</div>
			<div style="clear:both;"></div>
			<?php
			$encounterList=$fO->getPatientHistory($patient['patient_id']);
			if(count($encounterList)>0){
				echo '<h2 class="form-signin-heading">Patient History</h2>';
				echo '<table class="table table-striped" style="border-spacing:2px;border-collapse:separate;width:100%;">';
				echo '<tr><th>View</th><th>Encounter Date</th><th>Blood Pressure</th><th>Admitted</th></tr>';
			foreach($encounterList as $encounter){
				$admitted='No';
				if($encounter['admitted']==1){
					$admitted='Yes';
				}
				echo '<tr>';
				echo '<td><a href="patient_billing.php?selectedEncounter='.$encounter['encounter_id'].'">View</a></td>';
				echo '<td>'.$encounter['time_created'].'</td>';
				echo '<td>'.$encounter['blood_pressure'].'</td>';
				echo '<td>'.$admitted.'</td>';
				echo '</tr>';
			}
			echo '</table>';
		  }
			if($encounter['seen_doctor']==1){
				$_SESSION['consultation_charges']=new BillPaymentCart;
				$_SESSION['consultation_charges']->add_to_cart(1,$encounter['prescription_id'],$_SESSION['consultation_fee'],-1);
				echo '<h2 class="form-signin-heading">consultation Fee</h2>';
				echo '<input type="text" name="consultation_fee"  readonly class="form-control amount"
				value="'.$_SESSION['consultation_fee'].'">';
			}
      $patientTotalBill=0;
			$orders=$fO->getPharmacyOrdersByEncounterId($_SESSION['encounter']);
      $pharmacyTotalBill=0;
        $_SESSION['pharmacy_cart']=new BillPaymentCart;
				if(count($orders)>0){
					echo '<h2 class="form-signin-heading">Pharmacy Bill</h2>';
				foreach($orders as $order){
          echo '<div class="form-inline" >';
          echo '<label>consultation Time: </label>';
					echo '<input type="text" name="consultation_time" style="width:90%;float:right;" readonly class="form-control"
          value="'.$order['con_time'].'">';
          echo '</div>';
          echo '<div style="clear:both"></div>';
          $dispensedOrders=$fO->getPharmacyOrdersDispensedByPrescriptionId($order['prescription_id']);
          if(count($dispensedOrders)>0){
            $prescriptionTotal=0;
          foreach($dispensedOrders as $dispensedOrder){
            $_SESSION['pharmacy_cart']->add_to_cart($dispensedOrder['drugId'],$dispensedOrder['prescription_id'],$dispensedOrder['amount'],-1);
            $prescriptionTotal=$prescriptionTotal+$dispensedOrder['amount'];
            echo '<div class="form-inline" >';
            echo '<input type="text" name="drug_name[]" style="width:50%;" readonly class="form-control" value="'.$dispensedOrder['dname'].'">';
            echo '<input type="text" name="amount[]" style="width:50%;" readonly class="form-control amount" value="'.$dispensedOrder['amount'].'">';
            echo '</div>';
            $pharmacyTotalBill=$pharmacyTotalBill+$prescriptionTotal;
          }
          echo '<div class="form-inline" >';
          echo '<label>Prescription Total:</label>';
          echo '<input type="text" name="cost[]" style="width:50%;float:right;" readonly class="form-control" value="'.$prescriptionTotal.'" >';
          echo '</div>';
          echo '<div style="clear:both"></div>';
        }
				}
			}
      $labOrders=$fO->getLabOrdersByEncounterId($_SESSION['encounter']);
      $labTotalBill=0;
				if(count($labOrders)>0){
					echo '<h2 class="form-signin-heading">Laboratory Bill</h2>';
					echo '<table">';
				foreach($labOrders as $order){
          echo '<div class="form-inline" >';
          echo '<label>consultation Time: </label>';
					echo '<input type="text" name="consultation_time" style="width:90%;float:right;" readonly class="form-control"
          value="'.$order['con_time'].'">';
          echo '</div>';
          echo '<div style="clear:both"></div>';
          $dispensedOrders=$fO->getLabOrdersDispensedByPrescriptionId($order['prescription_id']);
          if(count($dispensedOrders)>0){
						$_SESSION['lab_tests']=new BillPaymentCart;
            $prescriptionTotal=0;;
          foreach($dispensedOrders as $dispensedOrder){
						$_SESSION['lab_tests']->add_to_cart($dispensedOrder['lab_id'],$dispensedOrder['prescription_id'],$dispensedOrder['cost'],-1);
            $prescriptionTotal=$prescriptionTotal+$dispensedOrder['cost'];
            echo '<div class="form-inline" >';
            echo '<input type="text" name="test[]" style="width:50%;" readonly class="form-control" value="'.$dispensedOrder['test'].'">';
            echo '<input type="text" name="cost[]" style="width:50%;" readonly class="form-control amount" value="'.$dispensedOrder['cost'].'">';
            echo '</div>';
            $labTotalBill=$labTotalBill+$prescriptionTotal;
          }
          echo '<div class="form-inline" >';
          echo '<label>Prescription Total:</label>';
          echo '<input type="text" name="cost[]" style="width:50%;float:right;" readonly class="form-control" value="'.$prescriptionTotal.'" >';
          echo '</div>';
          echo '<div style="clear:both"></div>';
        }
				}
				echo '</table>';
			}
      $patientTotalBill=$pharmacyTotalBill+$labTotalBill;
			if($encounter['bill_cleared']==0){
			 ?>
       <div class="form-inline" style="float:right;">
         <label for="total">Total</label>
         <input type="number" class="form-control" name="total" id="total" value="0" readonly=""/>
         <label for="total">Amount Paid</label>
         <input type="number" class="form-control" id="paid" name="paid" required="" />
         <label for="total">Balance</label>
         <input type="number" class="form-control" id="balance" name="balance" readonly=""/>
       </div>
       <div style="clear: both;"></br>
         <input type="submit" name="receive_payment" class="btn btn-lg btn-primary"
        value="Receive Payment" style="display: block; margin: 0 auto;width:200px;"></input>
		<?php }
			echo '</form>';
     }?>
  </body>
  </html>
