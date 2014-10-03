<?php
//	Set page permission
#################################################################################
if(!$global->set_permission(PERMIT_VIEW,true,$MODULEFOLDER)){
	header("Location: index.php");
};
	
	
	//	Get Post Request
	$id = $global->numeric($form->REQUEST("id"));
	if(!$id){$id = $global->gen_id();}
	$name = $form->POST("name");
	$category = $form->POST("category");
	$qty = $form->POST("qty");
	$price = $form->POST("price");
	$supplier = $form->POST("supplier");
	
	//print $global->unit_conversion($stock,$unit,$base_unit);

	//	Add / Edit process
	if($name && $category && $qty && $price && $supplier){
		
		
		$query="SELECT SQL_BUFFER_RESULT SQL_CACHE id,qty,category,supplier,price FROM ".DBPREFIX."expenses WHERE id='$id'";
		$sql->select($query);
		$data=$sql->fetch_array();
		if($data["id"]){
			
			$update = "UPDATE ".DBPREFIX."expenses SET
				name='$name',
				supplier='$supplier',
				category='$category',
				qty='$qty',
				price='$price' WHERE id='$id'
				";
			$sql->query($update,$update);
			
			
		}else{
			
			// Update LOg
			$insert = "INSERT INTO ".DBPREFIX."expenses (id,name,supplier,category,qty,price) VALUES ('$id','$name','$supplier','$category','$qty','$price')";
			$sql->query($insert,$insert);
		}
		header("Location: system-expenses.html");
		exit;
	} else {
		$query="SELECT SQL_BUFFER_RESULT SQL_CACHE * FROM ".DBPREFIX."expenses WHERE id='$id'";
		$sql->select($query);
		$data=$sql->fetch_array();
		if($data["id"]){
			$id=$data["id"];
			$name=$data["name"];
			$supplier=$data["supplier"];
			$category=$data["category"];
			$qty=$data["qty"];
			$price=$data["price"];
		}
	}
	
	if(!isset($error)){
		$error = "";	
	}else{
		$error = "<ul style='padding: 0 15px;'>".$error."</ul>";	
	}
	
	
	$type["1"] = "Production";
	$type["2"] = "Marketing";
	$type["3"] = "Utilities";
	$type["4"] = "Compensation";
	$type["5"] = "Rent / Mortgage";
	$type["6"] = "Improvements / Maintenance";
	$type["7"] = "Administrative Costs";
	
	foreach($type as $k => $v){
		if($k == $category){
			$categories .= '<option value="'.$k.'" selected="selected">'.$v.'</option>';
		}else{
			$categories .= '<option value="'.$k.'">'.$v.'</option>';
		}
	}
	
	$array=array(
	"id" => $id,
	"error" => $error,
	"name" => $name,
	"supplier" => $supplier,
	"price_label" => $price_label,
	"qty" => $qty,
	"category" => $categories,
	"price" => $price,
	"url" => $_SERVER['REQUEST_URI'],
	);
	$template->merge($array);
?>