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
	$pickup_time = $form->POST("time");

	
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
	
	
	if($pickup_time){	
			
			if(!$data["id"]){
				$q = "INSERT INTO ".DBPREFIX."orders (id,tbl,customer_name,phone,address,pickup_time) VALUES 
				('$id','$table','$customer_name','$phone','$address','$pickup_time')";
				$sql->query($q);
			}else{
				$q = "UPDATE ".DBPREFIX."orders SET 
				customer_name='$customer_name',
				phone='$phone',
				address='$address',
				pickup_time='$pickup_time' WHERE id='$id'";
				$sql->query($q);	
			}
		if($products||$packages||$package_products){		
			$prods="";
			$coma="";
			
			$query="INSERT INTO ".DBPREFIX."order_details (
			oid,pid,type,grp,seq) VALUES ";
			if($products){
				foreach($products as $key => $val){
					$v = explode(":",$val);
					$prods .= $coma."('$id','".$v[0]."','1','".$v[1]."','1')";
					$coma=",";
				}
			}
			if($packages){
				foreach($packages as $key => $val){
					$v = explode(":",$val);
					$prods .= $coma."('$id','".$v[0]."','2','".$v[1]."','1')";
					$coma=",";
				}
			}
			if($package_products){
				$seq = 1;
				foreach($package_products as $key => $val){
					$v = explode(":",$val);
					$prods .= $coma."('$id','".$v[0]."','3','".$v[1]."','".$seq++."')";
					$coma=",";
				}
			}
			//print $query.$prods;
			$sql->query($query.$prods);
			
		}
		header("Location: index.html");
		exit;
	}
	$customer_name = $data["customer_name"];
	$phone = $data["phone"];
	$address =  $data["address"];
	$pickup_time =  $data["pickup_time"];
	
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
			"a" => 'onclick="packages(\''.$data["id"].'\',\''.addslashes($data["name"]).'\')" data-toggle="modal" href="#myModal"'
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
	
	
	if($customer_name){
		$ci = 'block';	
	}else{
		$ci = 'none';	
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
	"time" => $pickup_time
	);
	$template->merge($array);

function moneyFormat($amt){
	return number_format((float)$amt, 2, '.', '');
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