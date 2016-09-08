<?php
Class BillPaymentCart {
	var $lineItems;
	var $lineCounter;
	var $itemsIssued;
	var $totalBill;
	function BillPaymentCart(){
		$this->lineItems = array();
		$this->lineCounter=0;
		$this->totalBill=0;
	}
	function add_to_cart($itemId,$prescriptionId,$cost,$lineNumber=-1){
		if (isset($itemId) AND $itemId!=""){
			if ($lineNumber==-1){
				$lineNumber = $this->lineCounter;
			}
			$this->lineItems[$lineNumber] = new BillPaymentLineDetails($lineNumber,$itemId,$prescriptionId,$cost);
			$this->lineCounter = $lineNumber + 1;
			$this->itemsIssued++;
			Return 1;
		}
		Return 0;
	}
	function remove_from_cart($lineNumber){
		if (!isset($lineNumber) || $lineNumber=='' || $lineNumber < 0){
			return;
		}
		unset($this->lineItems[$lineNumber]);
		$this->itemsIssued--;
	}
}
Class BillPaymentLineDetails {
	Var $lineNumber;
	Var $itemId;
	Var $prescriptionId;
	Var $cost;
	function BillPaymentLineDetails ($lineNumber,$itemId,$prescriptionId,$cost){
		$this->lineNumber = $lineNumber;
		$this->itemId =$itemId;
		$this->prescriptionId = $prescriptionId;
		$this->cost = $cost;
	}
}
?>
