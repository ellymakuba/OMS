<?php
Class LabCart {
	var $LineItems;
	var $total;
	var $LineCounter;
	var $entryDate;
	Var $OrderNo;
	var $ItemsOrdered;
	function Labcart(){
		$this->LineItems = array();
		$this->total=0;
		$this->LineCounter=0;
	}
	function add_to_lab_cart($testID,$Descr,$cost,$LineNumber=-1){
		if (isset($testID) AND $testID!=""){
			if ($cost<0){
				$cost=0;
			}
			if ($LineNumber==-1){
				$LineNumber = $this->LineCounter;
			}
			$this->LineItems[$LineNumber] = new LabLineDetails($LineNumber,$testID,$Descr,$cost);
			$this->ItemsOrdered++;
			$this->LineCounter = $LineNumber + 1;
			Return 1;
		}
		Return 0;
	}

	function update_lab_cart($UpdateLineNumber,$descr,$cost){
		$this->LineItems[$UpdateLineNumber]->ItemDescription= $descr;
		$this->LineItems[$UpdateLineNumber]->cost= $cost;
	}
	function remove_from_lab_cart($LineNumber){
		if (!isset($LineNumber) || $LineNumber=='' || $LineNumber < 0){
			//prnMsg(_('No Line Number passed to remove_from_cart, so nothing has been removed.'), 'error');
			return;
		}
		unset($this->LineItems[$LineNumber]);
		$this->ItemsOrdered--;
	}//remove_from_cart()
} /* end of cart class defintion */
Class LabLineDetails {
	Var $LineNumber;
	Var $testID;
	Var $ItemDescription;
	Var $cost;
	Var $date;
	Var $POLine;

	function LabLineDetails ($LineNumber,$testID,$Descr,$cost){
/* Constructor function to add a new LineDetail object with passed params */
		$this->LineNumber = $LineNumber;
		$this->testID =$testID;
		$this->ItemDescription = $Descr;
		$this->cost = $cost;
	} //end constructor function for LineDetails
}
?>
