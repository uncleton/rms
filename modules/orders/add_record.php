<?php
//	Set page permission
#################################################################################
if(!$global->set_permission(PERMIT_VIEW,true,$MODULEFOLDER)){
	header("Location: index.php");
};

	//	Get Post Request
	$id = $global->numeric($form->REQUEST("id"));
	if(!$id){$id = $global->gen_id();}
	$products = $form->POST("products","ARRAY");
	$packages = $form->POST("packages","ARRAY");
	$package_products = $form->POST("package_products","ARRAY");
	$table = $form->REQUEST("tbl");
	$customer_name = $form->POST("name");
	$phone = $form->POST("phone");
	$address = $form->POST("address");
	$date = $form->POST("date");
	
	$vat = $form->POST("vat");
	$cost = $form->POST("cost");
	$subtotal = $form->POST("subtotal");
	$total_payment = $form->POST("total_payment");
	$discount = $form->POST("discount");
	

//print_r($_POST);
	
	// Get Table Que
	if(preg_match("/d/i",$table)){
		$t = "Delivery ".str_replace("d","",$table);
	}
	if(preg_match("/p/i",$table)){
		$t = "Pick-up ".str_replace("p","",$table);
	}
	if(preg_match("/g/i",$table)){
		$t = "Take Out ".str_replace("g","",$table);
	}
	if(preg_match("/t/i",$table)){
		$t = "Table ".str_replace("t","",$table);
	}

	//	Add / Edit process
	$select = "SELECT * FROM ".DBPREFIX."orders WHERE id = '$id'";
	$sql->query($select);
	$data = $sql->fetch_array();
	
	
	if($date){	
			
		if(!$data["id"]){
			$q = "INSERT INTO ".DBPREFIX."orders (id,tbl,customer_name,phone,address,date,status) VALUES 
			('$id','$table','$customer_name','$phone','$address','$date 00:00:00','1')";
			$sql->query($q);
		}else{
			$q = "UPDATE ".DBPREFIX."orders SET 
			customer_name='$customer_name',
			phone='$phone',
			address='$address',
			status='1' WHERE id='$id'";
			$sql->query($q);	
		}
			
		if($products||$packages||$package_products){		
			$prods="";
			$coma="";
			
			// Delete Order Details first Before Insert
			$delete = "DELETE FROM ".DBPREFIX."order_details WHERE oid='$id'";
			$sql->query($delete);
			
			// Adjust Stocks then delete Stock log
			$select = "SELECT * FROM ".DBPREFIX."stocks_sale_log WHERE oid = '$id'";
			$sql->query($select,$select);
			while($data = $sql->fetch_array($select)){
				$update = "UPDATE ".DBPREFIX."ingredients SET stocks=(stocks+".($data["stocks"]*-1).") WHERE id = '".$data["iid"]."'";
				$sql->query($update);	
			}
			// Delete Stock Log
			$delete = "DELETE FROM ".DBPREFIX."stocks_sale_log WHERE oid='$id'";
			$sql->query($delete);
			
			
			
			// Re-insert orders
			$query="INSERT INTO ".DBPREFIX."order_details (
			oid,pid,type,grp,seq,status) VALUES ";
			$pids="";
			if($products){
				foreach($products as $key => $val){
					$v = explode(":",$val);
					$prods .= $coma."('$id','".$v[0]."','1','".$v[1]."','1','1')";
					$coma=",";
					$pids[]=$v[0];
				}
			}
			if($packages){
				foreach($packages as $key => $val){
					$v = explode(":",$val);
					$prods .= $coma."('$id','".$v[0]."','2','".$v[1]."','1','1')";
					$coma=",";
				}
			}
			if($package_products){
				$seq = 1;
				foreach($package_products as $key => $val){
					$v = explode(":",$val);
					$prods .= $coma."('$id','".$v[0]."','3','".$v[1]."','".$seq++."','1')";
					$coma=",";
					$pids[]=$v[0];
				}
			}
			//print $query.$prods;
			$sql->query($query.$prods);
			
			$inventory->orderid=$id;
			foreach($pids as $key => $values){
				$inventory->deduct_product_stocks($values,1,'SOLD');
			}
		}
		
		// Update Revenue Data
		$select = "SELECT id FROM ".DBPREFIX."revenue WHERE oid = '$id'";
		$sql->query($select);
		$data = $sql->fetch_array();
		if(!$data["id"]){
			$insert = "INSERT INTO ".DBPREFIX."revenue (oid,vat,cost,subtotal,total,discount) 
				VALUES ('$id','$vat','$cost','$subtotal','$total_payment','$discount')";
			$sql->query($insert);	
		}else{
			$update = "UPDATE ".DBPREFIX."revenue SET 
				vat='$vat',
				cost='$cost',
				total='$total_payment',
				subtotal='$subtotal',
				discount='$discount' WHERE oid='$id'";
			$sql->query($update);	
		}
		
		header("Location: orders-manage.html");
		exit;
	}
	$customer_name = $data["customer_name"];
	$phone = $data["phone"];
	$address =  $data["address"];
	$date_temp = explode(" ",$data["date"]);
	$date =  $date_temp[0];
	
	//	Package Menu
	$query="SELECT SQL_BUFFER_RESULT SQL_CACHE P.id,name,P.image,P.price,P.cid,C.alias FROM ".DBPREFIX."packages AS P JOIN ".DBPREFIX."category AS C ON P.cid=C.id  WHERE P.status='1'  ORDER BY P.name";
	$sql->select($query,$query);
	$menu = "";
	while($data = $sql->fetch_array($query)){
		$image = "no-image.jpg";
		if($data["image"]){
			$image = $data["image"];	
		}
		$pkg_menu[$data["alias"]][] = array(
			"id" => $data["id"],
			"name" => $data["name"],
			"image" => $image,
			"price" => moneyFormat($data["price"]),
			"a" => 'onclick="packages(\''.$data["id"].'\',\''.addslashes($data["name"]).'\',this)" data-toggle="modal" href="#myModal"'
		);
	}
	$template->loop($pkg_menu["packages"],"package");
	
	//	Pizza Menu
	$query="SELECT SQL_BUFFER_RESULT SQL_CACHE P.id,P.name,P.image,P.price FROM ".DBPREFIX."products AS P JOIN ".DBPREFIX."category AS C ON P.cid=C.id WHERE C.alias='pizza' AND P.status='1' ORDER BY P.name";
	$sql->select($query,$query);
	$menu = $pkg_menu["pizza"];
	while($data = $sql->fetch_array($query)){
		$image = "no-image.jpg";
		if($data["image"]){
			$image = $data["image"];	
		}
		$menu[] = array(
			"id" => $data["id"],
			"name" => $data["name"],
			"image" => $image,
			"price" => moneyFormat($data["price"]),
			"a" => 'href="Javascript:void(0);" onclick="add(\''.$data["id"].'\',this)"'
		);
	}
	$template->loop(aasort($menu,"name"),"pizza");
	
	//	pasta Menu
	$query="SELECT SQL_BUFFER_RESULT SQL_CACHE P.id,P.name,P.image,P.price FROM ".DBPREFIX."products AS P JOIN ".DBPREFIX."category AS C ON P.cid=C.id WHERE C.alias='pasta' AND P.status='1'  ORDER BY P.name";
	$sql->select($query,$query);
	$menu = $pkg_menu["pasta"];
	while($data = $sql->fetch_array($query)){
		$image = "no-image.jpg";
		if($data["image"]){
			$image = $data["image"];	
		}
		$menu[] = array(
			"id" => $data["id"],
			"name" => $data["name"],
			"image" => $image,
			"price" => moneyFormat($data["price"]),
			"a" => 'href="Javascript:void(0);" onclick="add(\''.$data["id"].'\',this)"'
		);
	}
	$template->loop(aasort($menu,"name"),"pasta");
	
	//	Grill Menu
	$query="SELECT SQL_BUFFER_RESULT SQL_CACHE P.id,P.name,P.image,P.price FROM ".DBPREFIX."products AS P JOIN ".DBPREFIX."category AS C ON P.cid=C.id WHERE C.alias='grill' AND P.status='1'  ORDER BY P.name";
	$sql->select($query,$query);
	$menu = $pkg_menu["grill"];
	while($data = $sql->fetch_array($query)){
		$image = "no-image.jpg";
		if($data["image"]){
			$image = $data["image"];	
		}
		$menu[] = array(
			"id" => $data["id"],
			"name" => $data["name"],
			"image" => $image,
			"price" => moneyFormat($data["price"]),
			"a" => 'href="Javascript:void(0);" onclick="add(\''.$data["id"].'\',this)"'
		);
	}
	$template->loop(aasort($menu,"name"),"grill");
	
	//	Sides Menu
	$query="SELECT SQL_BUFFER_RESULT SQL_CACHE P.id,P.name,P.image,P.price FROM ".DBPREFIX."products AS P JOIN ".DBPREFIX."category AS C ON P.cid=C.id WHERE C.alias='sides' AND P.status='1'  ORDER BY P.name";
	$sql->select($query,$query);
	$menu = $pkg_menu["sides"];
	while($data = $sql->fetch_array($query)){
		$image = "no-image.jpg";
		if($data["image"]){
			$image = $data["image"];	
		}
		$menu[] = array(
			"id" => $data["id"],
			"name" => $data["name"],
			"image" => $image,
			"price" => moneyFormat($data["price"]),
			"a" => 'href="Javascript:void(0);" onclick="add(\''.$data["id"].'\',this)"'
		);
	}
	$template->loop(aasort($menu,"name"),"sides");

	
	
	//	Drinks Menu
	$query="SELECT SQL_BUFFER_RESULT SQL_CACHE P.id,P.name,P.image,P.price FROM ".DBPREFIX."products AS P JOIN ".DBPREFIX."category AS C ON P.cid=C.id WHERE C.alias='drinks' AND P.status='1'  ORDER BY P.name";
	$sql->select($query,$query);
	$menu = $pkg_menu["drinks"];
	while($data = $sql->fetch_array($query)){
		$image = "no-image.jpg";
		if($data["image"]){
			$image = $data["image"];	
		}
		$menu[] = array(
			"id" => $data["id"],
			"name" => $data["name"],
			"image" => $image,
			"price" => moneyFormat($data["price"]),
			"a" => 'href="Javascript:void(0);" onclick="add(\''.$data["id"].'\',this)"'
		);
	}
	$template->loop(aasort($menu,"name"),"drinks");
	
	//	Drinks Menu
	$query="SELECT SQL_BUFFER_RESULT SQL_CACHE P.id,P.name,P.image,P.price FROM ".DBPREFIX."products AS P JOIN ".DBPREFIX."category AS C ON P.cid=C.id WHERE C.alias='freebies' AND P.status='1'  ORDER BY P.name";
	$sql->select($query,$query);
	$menu = $pkg_menu["freebies"];
	while($data = $sql->fetch_array($query)){
		$image = "no-image.jpg";
		if($data["image"]){
			$image = $data["image"];	
		}
		$menu[] = array(
			"id" => $data["id"],
			"name" => $data["name"],
			"image" => $image,
			"price" => moneyFormat($data["price"]),
			"a" => 'href="Javascript:void(0);" onclick="add(\''.$data["id"].'\',this)"'
		);
	}
	$template->loop(aasort($menu,"name"),"freebies");
	

	// IF ORder Exists
	$billout = $form->GET("id");
	if($billout){
		// Product
		$select = "SELECT O.customer_name,O.phone,O.address,O.pickup_time,P.name,P.price,P.cost,O.tbl,O.order_number,OD.grp,OD.pid FROM ".DBPREFIX."orders AS O JOIN ".DBPREFIX."order_details AS OD ON O.id=OD.oid JOIN ".DBPREFIX."products AS P ON P.id=OD.pid WHERE O.id='$billout' AND OD.type='1'";
		$sql->query($select);
		$orders = "";
		$customer_name = "";
		$order_number = "";
		$phone = "";
		$address = "";
		$pickup_time = "";
		$total=0;
		$cost=0;
		$tbl = "";
		while($data = $sql->fetch_array()){
			//$orders .= '<li><span class="pull-left">'.$data["name"].'</span><span class="pull-right" style="width:100px;">Php '.less_vat($data["price"]).'</span><span style="clear:both; display:block;"></span></li>'; 
			
			$orders .='<li><label>'.$data["name"].' <a href="Javascript:void(0);" class="btn btn-xs btn-danger-outline pull-right product" onclick="del(this)">Cancel<input type="hidden" class="price-ordered" value="'.$data["price"].'"></a></label><input type="hidden" name="products[]" value="'.$data["pid"].':'.$data["grp"].'"></li>';
			$customer_name = $data["customer_name"];
			$phone = $data["phone"];
			$address = $data["address"];
			$pickup_time = $data["pickup_time"];
			$total = $total+$data["price"];
			$cost = $cost+$data["cost"];
			$tbl = $data["tbl"];
			$order_number = $data["order_number"];
		}
		
		// Package
		$pkg = false;
		$select = "SELECT P.price,P.name,OD.pid,O.tbl,OD.grp FROM ".DBPREFIX."orders AS O JOIN ".DBPREFIX."order_details AS OD ON O.id=OD.oid JOIN ".DBPREFIX."packages AS P ON P.id=OD.pid WHERE OD.oid='$billout' AND OD.type='2'";
		$sql->query($select,$select);
		while($data = $sql->fetch_array($select)){
			
			$select2 = "SELECT P.name,OD.pid,OD.grp FROM ".DBPREFIX."orders AS O JOIN ".DBPREFIX."order_details AS OD ON O.id=OD.oid JOIN ".DBPREFIX."products AS P ON P.id=OD.pid WHERE OD.oid='$billout' AND OD.type='3'";
			$sql->query($select2,$select2);
			$comma="";
			$order_pkg="";
			while($data2 = $sql->fetch_array($select2)){
				$order_pkg .= $comma.$data2["name"].'<input type="hidden" name="package_products[]" value="'.$data2["pid"].':'.$data2["grp"].'">'; 	
			}
			
			$orders .= '<li><label>'.$data["name"].' <span>' . $order_pkg . '</span><a href="Javascript:void(0);" class="btn btn-xs btn-danger-outline pull-right product" onclick="del(this)">Cancel<input type="hidden" class="price-ordered" value="'.$data["price"].'"></a></label><input type="hidden" name="packages[]" value="'.$data["pid"].':'.$data["grp"].'"></li>';
			$total = $total+$data["price"];
			$pkg = true;
			$tbl = $data["tbl"];
			$comma = ",";
		}
		// Get Cost of Package Products
		if($pkg){
			$select = "SELECT sum(P.cost) as cost FROM ".DBPREFIX."order_details AS OD JOIN pfi_products AS P ON P.id=OD.pid WHERE type ='3' and oid='$billout'";
			$sql->query($select);
			$data = $sql->fetch_array();
			$cost = $cost+$data["cost"];
		}
	}

	
	
	if($customer_name){
		$ci = 'block';	
	}else{
		$ci = 'none';	
	}
	
	if($order_number){
		$order_number = "#".$order_number;	
	}
	if(!$discount){
		$discount='0.00';	
	}
	if(!vat($total)){
		$vat='0.00';	
	}else{
		$vat=vat($total);	
	}
	if(!$orders){
		$orders='<li class="np"><label>No Product Added</label></li>';	
	}
	$array=array(
	"id" => $id,
	"ci" => $ci,
	"t" => $t,
	"table" => $table,
	"url" => $_SERVER['REQUEST_URI'],
	"name" => $customer_name,
	"phone" => $phone,
	"address" => $address,
	"time" => $pickup_time,
	"customer_name" => $customer_name,
	"address" => $address,
	"table" => $table,
	"order_number" => $order_number,
	"phone" => $phone,
	"orders" => $orders,
	"subtotal" => moneyFormat($total),
	"vat" => moneyFormat($vat),
	"discount" => moneyFormat($discount),
	"total_payment" => moneyFormat($total-$discount),
	"cost" => moneyFormat($cost),
	"date" => $date
	);
	$template->merge($array);



function less_vat($amt){
	return $amt;
	//return round(($amt/1.12),2);
}
function vat($amt){
	return 0;
	//return ($amt-less_vat($amt));
}

function moneyFormat($amt){
	return number_format((float)$amt, 2, '.', ',');
}
function aasort($array, $key){
    $sorter=array();
    $ret=array();
	if($array){
		reset($array);
		foreach ($array as $ii => $va) {
			$sorter[$ii]=$va[$key];
		}
		asort($sorter);
		foreach ($sorter as $ii => $va) {
			$ret[$ii]=$array[$ii];
		}
	}
    return $ret;
}
?>