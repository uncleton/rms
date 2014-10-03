<?php
$complete = $form->REQUEST("c");
$cancel = $form->GET("n");

if($complete){
	
	$update = "Update ".DBPREFIX."order_details SET status='1' WHERE id='$complete'";
	$sql->query($update);
	
	header("Location: orders.html");
	exit;
}
if($cancel){
	$update = "Update ".DBPREFIX."order_details SET status='3' WHERE id='$cancel'";
	$sql->query($update);
	
	header("Location: orders.html");
	exit;
}

$select = "SELECT P.name,O.tbl,C.alias as cat, OD.status, OD.id,O.pickup_time FROM ".DBPREFIX."orders AS O JOIN ".DBPREFIX."order_details AS OD ON O.id=OD.oid JOIN ".DBPREFIX."products AS P ON P.id=OD.pid JOIN ".DBPREFIX."category AS C ON P.cid=C.id WHERE OD.status='0' OR OD.status='2' ORDER BY O.pickup_time DESC, OD.id DESC";
$sql->query($select);
$orders_array="";
while($data=$sql->fetch_array()){
		
	$tbl = $data["tbl"];
	
	$time = strtotime($data["pickup_time"]);
	$now  = time()+(60*60*8);
	$diff = $now - $time;

	$time = date('H:i',$diff).' <i style="font-size:12px;">ago</i>';
	if(preg_match("/d/i",$tbl)){
		$table = str_replace("d","Del ",$tbl);
	}
	if(preg_match("/p/i",$tbl)){
		$table = str_replace("p","Pick ",$tbl);
		$time = "at ".date('h:i A',strtotime($data["pickup_time"]));
	}
	if(preg_match("/g/i",$tbl)){
		$table = str_replace("g","TO ",$tbl);
		$time = "at ".date('h:i A',strtotime($data["pickup_time"]));
	}
	if(preg_match("/t/i",$tbl)){
		$table = str_replace("t","Tbl ",$tbl);
	}
	
	switch($data["cat"]){
		case "grill" :
			$type = "info";
		break;
		case "pasta" :
			$type = "success";
		break;
		case "pizza" :
			$type = "warning pizza";
		break;
		
		case "sides" :
			$type = "warning";
		break;
		case "others" :
			$type = "info";
		break;
		
		default :
			$type = "warning drinks";
		break;
	}
	
	$name = $data["name"];
	$action = "complete('".$data["id"]."')";
	if($data["status"]==2){
		$type = "danger";
		$name = $name." &nbsp; <b>(CANCELLED)</b>";	
		$action = "cancel('".$data["id"]."')";
	}
	
	$orders_array[] = array(
		"type" => $type,
		"id" => $data["id"],
		"name" => $name,
		"table" => $table,
		"action" => $action,
		"time" => $time
	); 
}
$template->loop($orders_array,"orders");

$select = "SELECT count(P.name) as subtotal, P.id as pid, P.name,O.tbl,C.alias as cat, OD.status, OD.id,O.pickup_time FROM ".DBPREFIX."orders AS O JOIN ".DBPREFIX."order_details AS OD ON O.id=OD.oid JOIN ".DBPREFIX."products AS P ON P.id=OD.pid JOIN ".DBPREFIX."category AS C ON P.cid=C.id WHERE OD.status='0' OR OD.status='2' GROUP BY P.name, O.tbl ORDER BY O.pickup_time DESC, OD.id DESC";
$sql->query($select);
$orders_array="";
$data_array = "";
while($data=$sql->fetch_array()){
	
	$tbl = $data["tbl"];
	
	if(preg_match("/d/i",$tbl)){
		$table = str_replace("d","Del ",$tbl);
	}
	if(preg_match("/p/i",$tbl)){
		$table = str_replace("p","Pick ",$tbl);
		$time = "Time: ".date('h:i A',strtotime($data["pickup_time"]));
	}
	if(preg_match("/g/i",$tbl)){
		$table = str_replace("g","TO ",$tbl);
		$time = "at ".date('h:i A',strtotime($data["pickup_time"]));
	}
	if(preg_match("/t/i",$tbl)){
		$table = str_replace("t","Tbl ",$tbl);
	}
	
	switch($data["cat"]){
		case "grill" :
			$type = "info";
		break;
		case "pasta" :
			$type = "success";
		break;
		case "pizza" :
			$type = "warning pizza";
		break;
		case "others" :
			$type = "info";
		break;
		case "sides" :
			$type = "warning";
		break;
		
		default :
			$type = "warning drinks";
		break;
	}
	
	$data_array[$data["pid"]]["name"] = $data["name"];
	$data_array[$data["pid"]]["type"] = $type;
	$data_array[$data["pid"]]["tbl"] = $data_array[$data["pid"]]["tbl"].'<div class="badge f-small" style="margin-right:10px; background:#fff; color:#000; font-family:Arial, Helvetica, sans-serif;">'.$table.': '.$data["subtotal"].'</div>';	
	$data_array[$data["pid"]]["total"] = $data_array[$data["pid"]]["total"]+ $data["subtotal"]; 
}
$template->loop($data_array,"order-summary");
?>