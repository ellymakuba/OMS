<?php
Class Cart {
	var $LineItems;
	var $total;
	var $LineCounter;
	var $ItemsOrdered;
	var $entryDate;
	var $currency;
	var $DelAdd;
	var $PhoneNo;
	var $Email;
	var $merchantNo;
	var $TransID;
	Var $OrderNo;
	function Cart(){
		$this->LineItems = array();
		$this->total=0;
		$this->ItemsOrdered=0;
		$this->LineCounter=0;
	}
	function add_to_cart($StockID,$Qty,$Descr,$Price,$Disc,$dose,$duration,$LineNumber=-1){
		if (isset($StockID) AND $StockID!="" AND $Qty>0 AND isset($Qty)){
			if ($Price<0){
				$Price=0;
			}
			if ($LineNumber==-1){
				$LineNumber = $this->LineCounter;
			}
			$this->LineItems[$LineNumber] = new LineDetails($LineNumber,$StockID,$Descr,$Qty,$Price,$Disc,$dose,$duration);
			$this->ItemsOrdered++;
			$this->LineCounter = $LineNumber + 1;
			Return 1;
		}
		Return 0;
	}

	function update_cart_dose($UpdateLineNumber,$dose){
		$this->LineItems[$UpdateLineNumber]->dose= $dose;
	}
	function update_cart_duration($UpdateLineNumber,$duration){
		$this->LineItems[$UpdateLineNumber]->duration= $duration;
	}

	function remove_from_cart($LineNumber){
		if (!isset($LineNumber) || $LineNumber=='' || $LineNumber < 0){
			//prnMsg(_('No Line Number passed to remove_from_cart, so nothing has been removed.'), 'error');
			return;
		}
		unset($this->LineItems[$LineNumber]);
		$this->ItemsOrdered--;
	}//remove_from_cart()
	function Get_StockID_List(){
		$StockID_List="";
		foreach ($this->LineItems as $StockItem) {
			$StockID_List .= ",'" . $StockItem->StockID . "'";
		}
		return substr($StockID_List, 1);
	}
} /* end of cart class defintion */
Class LineDetails {
	Var $LineNumber;
	Var $StockID;
	Var $ItemDescription;
	Var $Quantity;
	Var $Price;
	Var $Discount;
	Var $Units;
	Var $date;
	Var $cost;
	Var $ItemDue;
	Var $POLine;
	var $dose;
	var $duration;

	function LineDetails ($LineNumber,$StockItem,$Descr,$Qty,$Prc,$Disc,$dose,$duration){
/* Constructor function to add a new LineDetail object with passed params */
		$this->LineNumber = $LineNumber;
		$this->StockID =$StockItem;
		$this->ItemDescription = $Descr;
		$this->Quantity = $Qty;
		$this->Price = $Prc;
		$this->Discount= $Disc;
		$this->dose=$dose;
		$this->duration=$duration;
	} //end constructor function for LineDetails
}
?>
