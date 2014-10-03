<?php

	$id = $form->POST("id");
	//	Get Post Request
	$stock = $form->POST("stock");
	$unit = $form->POST("unit");
	$price = $form->POST("price");

	//	Add / Edit process
	if($id&&$stock&&$unit&&$price){
	
		$query="SELECT SQL_BUFFER_RESULT SQL_CACHE id,stocks,unit,unit_price FROM ".DBPREFIX."ingredients WHERE id='$id'";
		$sql->select($query);
		$data=$sql->fetch_array();
		if($data["id"]){
			
			$stock = $global->unit_conversion($stock,$unit,$data["unit"]);
			
			// Update LOg
			$cost = ($price/$stock);
			$price_status = "norm";
			if($cost<$data["unit_price"]){
				$price_status = "down";
			} 
			if($cost>$data["unit_price"]){
				$price_status = "up";
			}
			
			$insert = "INSERT INTO ".DBPREFIX."stocks_sale_log (action,iid,stocks,cost) VALUES ('ADD','".$id."','".$stock."','".$cost."')";
			$sql->query($insert,$insert);
			
			$stock_final= $stock + $data["stocks"];
			// Edit Account
			$query="UPDATE ".DBPREFIX."ingredients SET stocks='$stock_final',price_status='$price_status' WHERE id='$id'";
			$sql->query($query);
			
			print $stock_final.$data["unit"].':'.$cost.':'.$price_status;
		}
	} 
	
	exit;
	
?>
