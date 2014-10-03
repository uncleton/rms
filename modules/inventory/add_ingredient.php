<?php
//	Set page permission
#################################################################################
if(!$global->set_permission(PERMIT_VIEW,true,$MODULEFOLDER)){
	header("Location: index.php");
};
	
	$del = $form->GET("del");
	if($del){
		$SELECT = "SELECT iid,stocks FROM ".DBPREFIX."stocks_sale_log WHERE id='$del'";
		$sql->query($SELECT);
		$data = $sql->fetch_array();
		
		if($data["iid"]){
			$update = "UPDATE ".DBPREFIX."ingredients SET stocks=(stocks-".$data["stocks"].") WHERE id = '".$data["iid"]."'";
			$sql->query($update);
			
			$delete = "DELETE FROM ".DBPREFIX."stocks_sale_log WHERE id='$del'";
			$sql->query($delete);	
		}
		header("Location: inventory-add_ingredient.html?id=".$data["iid"]);
		exit;	
	}
	
	//	Get Post Request
	$id = $global->numeric($form->REQUEST("id"));
	if(!$id){$id = $global->gen_id();}
	$name = $form->POST("name");
	$stock = $form->POST("stock");
	$unit = $form->POST("unit");
	$price = $form->POST("price");
	
	//print $global->unit_conversion($stock,$unit,$base_unit);

	//	Add / Edit process
	if($name){
		
		
		$query="SELECT SQL_BUFFER_RESULT SQL_CACHE id,stocks,unit_price FROM ".DBPREFIX."ingredients WHERE id='$id'";
		$sql->select($query);
		$data=$sql->fetch_array();
		if($data["id"]){
			
			
			
			// Update LOg
			//$cost = ($stock*$data["unit_price"]);
			//$insert = "INSERT INTO ".DBPREFIX."stocks_sale_log (action,iid,stocks,cost) VALUES ('ADD','".$id."','".$stock."','".$price."')";
			//$sql->query($insert,$insert);
			
			//$stock=($stock+$data["stocks"]);
			// Edit Account
			$query="UPDATE ".DBPREFIX."ingredients SET name='$name', unit='$unit', unit_price='$price' WHERE id='$id'";
			$sql->query($query);
			
			
			
			header("Location: inventory-ingredients.html");
			exit;
		} else {
			
			$stock = $global->unit_conversion($stock,$unit,$unit);
			
			// Update LOg
			$cost = ($price/$stock);
			$insert = "INSERT INTO ".DBPREFIX."stocks_sale_log (action,iid,stocks,cost) VALUES ('ADD','".$id."','".$stock."','".$cost."')";
			$sql->query($insert,$insert);
			
			$cost = ($price/$stock);
			$query="INSERT INTO ".DBPREFIX."ingredients (
			id,name,unit,stocks,unit_price) VALUES (
			'$id','$name','$unit','$stock','$cost')";
			$sql->query($query);
	
			header("Location: inventory-ingredients.html");
			exit;
		}
	} else {
		$query="SELECT SQL_BUFFER_RESULT SQL_CACHE * FROM ".DBPREFIX."ingredients WHERE id='$id'";
		$sql->select($query);
		$data=$sql->fetch_array();
		if($data["id"]){
			$id=$data["id"];
			$name=$data["name"];
			$stock=$data["stocks"];
			$unit=$data["unit"];
			$price=$data["unit_price"];
		}
	}
	
	$query="SELECT SQL_BUFFER_RESULT SQL_CACHE * FROM ".DBPREFIX."unit_conversion";
	$sql->select($query);
	while($data=$sql->fetch_array()){
		$array_units[$data["unit"]] = $data["name"];
	}
	$units = "";
	foreach($array_units as $k => $v){
		if($k == $unit){
			$units .= '<option value="'.$k.'" selected="selected">'.$v.'</option>';
		}else{
			$units .= '<option value="'.$k.'">'.$v.'</option>';
		}
	}
	
	if(!isset($error)){
		$error = "";	
	}else{
		$error = "<ul style='padding: 0 15px;'>".$error."</ul>";	
	}
	if($stock){
		$stock = $stock.' '.$unit;	
	} else {
		$stock = "0";	
	}
	
	if($stock){
		$stock = '<label class="control-label col-md-5" style="text-align:left"> '.$stock.' </label>';	
		$price_label = 'Cost / Unit &nbsp; <a style="cursor:pointer;" data-content="Unit cost used to compute for Ingredient cost in products." data-original-title="Ingredient Unit Cost" data-toggle="popover-right" class="popover-right"><span class="icon-info-sign"></span> &nbsp;</a>';
		$stock_label ="Available Stocks";
	}else{
		$stock = '<div class="col-md-5">
          	 <input class="form-control" placeholder="Quantity" value="" id="stock" name="stock" type="text">
             </div>';
		$price_label = 'Total Item price';
		$stock_label ="Total Stocks";	
	}
	
	
	$query="SELECT SQL_BUFFER_RESULT SQL_CACHE * FROM ".DBPREFIX."stocks_sale_log WHERE iid='$id' AND action='ADD' ORDER BY date DESC LIMIT 20";
	$sql->select($query);
	while($data=$sql->fetch_array()){
		$cost = "--";
		$unit_price = "--";
		if($data["stocks"]>0){
			$stocks = "+".$data["stocks"];
			$cost = "Php ".round(($data["cost"]*$data["stocks"]),2);
			$unit_price = "Php ".$data["cost"]." / ".$unit;	
		}else{
			$stocks = $data["stocks"];		
		}
		$delete = '<a class="table-actions" href="inventory-add_ingredient.html?del='.$data["id"].'"><i class="icon-trash"></i></a>';
		$array_history[]=array(
			"date" => $data["date"],
			"stocks" => $stocks.$unit,
			"unit_price" => $cost,
			"cost" => $unit_price,
			"delete" => $delete
		);
		
	}
	$template->loop($array_history,"history");
	
	
	
	$array=array(
	"id" => $id,
	"error" => $error,
	"name" => $name,
	"stock" => $stock,
	"price_label" => $price_label,
	"units" => $units,
	"price" => $price,
	"stock_label" => $stock_label,
	"url" => $_SERVER['REQUEST_URI'],
	);
	$template->merge($array);
?>