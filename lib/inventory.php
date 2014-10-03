<?php


class inventory {
	var $orderid;
	
	function deduct_product_stocks($pid,$qty,$type=''){
		global $sql;
		
		// Product
		$query = "SELECT stocks FROM ".DBPREFIX."products WHERE id='$pid'";
		$sql->query($query);
		$data = $sql->fetch_array();
		if($data["stocks"]){
			if($qty>$data["stocks"]){
				$diff = $qty - $data["stocks"];
				
				$query_stock="UPDATE ".DBPREFIX."products SET stocks='0' WHERE id='$pid'";
				$sql->query($query_stock,$query_stock);
				
				// Deduct Ingredient on DIFF
				$this->deduct_ingredient_stocks($pid,$diff,$type);
				// Deduct Add-on;
				$this->deduct_addon_stocks($pid,$qty,$type);
			}else{
				$query_stock="UPDATE ".DBPREFIX."products SET stocks=(stocks-$qty) WHERE id='$pid'";
				$sql->query($query_stock,$query_stock);	
			}
		}else{
			$this->deduct_ingredient_stocks($pid,$qty,$type);
			$this->deduct_addon_stocks($pid,$qty,$type);	
		}	
	}
	function deduct_ingredient_stocks($pid,$qty,$type='SOLD'){
		global $sql;
		$query = "SELECT PD.iid,PD.qty,I.unit_price FROM ".DBPREFIX."product_details AS PD JOIN ".DBPREFIX."ingredients AS I ON PD.iid=I.id WHERE PD.pid='$pid'";
		$sql->query($query,$query);
		while($data = $sql->fetch_array($query)){
			
			$ings .= $comma ."('$type','".$this->orderid."','".$data["iid"]."','-".($data["qty"]*$qty)."','".(($data["qty"]*$qty)*$data["unit_price"])."')";
			$comma = ",";
			
			$update = "UPDATE ".DBPREFIX."ingredients SET stocks = (stocks-".($data["qty"]*$qty).") WHERE id='".$data["iid"]."'";
			$sql->query($update,$update);
				
		}
		
		// Log
		
		$insert = "INSERT INTO ".DBPREFIX."stocks_sale_log (action,oid,iid,stocks,cost) VALUES ".$ings;
		$sql->query($insert,$insert);
				
	}
	
	function deduct_addon_stocks($pid,$qty,$type='SOLD'){
		global $sql;
		// Deduct Add-on on DIFF
		$select = "SELECT pid FROM ".DBPREFIX."product_addons WHERE pid='$pid'";
		$sql->query($select,$select);
		while($data = $sql->fetch_array($select)){
			$this->deduct_product_stocks($data["pid"],$qty,$type);	
		}
	}
}

$inventory = new inventory();
?>
