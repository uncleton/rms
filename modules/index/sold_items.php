<?php
$filter=$form->POST("filter");
$datefrom=$form->POST("datefrom");
$dateto=$form->POST("dateto");

		
switch($filter){
	default :
		if($datefrom&&$dateto){
			$date_range=" DATE_FORMAT(O.date,'%Y-%m-%d') BETWEEN '$datefrom' AND '$dateto'";
		}else{
			$date_range="O.date>=DATE_SUB(CURDATE(),INTERVAL 1 MONTH)";
		}
		$groupby = " OD.pid,DATE_FORMAT(O.date,'%b %d') ";
	break;
	
	case "monthly" :
		if($datefrom&&$dateto){
			$date_range=" DATE_FORMAT(O.date,'%Y-%m-%d') BETWEEN '$datefrom' AND '$dateto'";
		}else{
			$date_range="O.date>=DATE_SUB(CURDATE(),INTERVAL 1 MONTH)";
		}
		$groupby = " OD.pid,DATE_FORMAT(O.date,'%b') ";
	break;
	
	case "yearly" :
		if($datefrom&&$dateto){
			$date_range=" DATE_FORMAT(O.date,'%Y-%m-%d') BETWEEN '$datefrom' AND '$dateto'";
		}else{
			$date_range="O.date>=DATE_SUB(CURDATE(),INTERVAL 1 MONTH)";
		}
		$groupby = " OD.pid,DATE_FORMAT(O.date,'%Y') ";
	break;
}

// PIZZA
$select = "SELECT DATE_SUB(CURDATE(),INTERVAL 1 MONTH) as datefrom, DATE_FORMAT(O.date,'%Y-%m-%d') as date, DATE_FORMAT(O.date,'%b %d, %Y') as date2, COUNT(OD.pid) AS count,P.id,P.name FROM ".DBPREFIX."order_details as OD JOIN ".DBPREFIX."orders AS O ON O.id=OD.oid JOIN pfi_products AS P ON P.id=OD.pid WHERE $date_range AND OD.type='1' AND O.status='1' AND P.cid='1378427683319' GROUP BY $groupby";
$sql->query($select);
$orders_array="";
$records="";
$comma="";
while($data=$sql->fetch_array()){
	
		$pizza_record[$data["date"]][] = "\"".$data["name"]."\":\"".$data["count"]."\"" ;
		
		$pizza_ykeys_labels[$data["name"]]=ucfirst($data["name"]);
		
		/*$data_table[] = array(
			"date" => $data["date2"],
			"product" => $data["name"],
			"count" => $data["count"]
		);*/
		
		if(!$datefrom){
			$datefrom=$data["datefrom"];
			$dateto = date ( "Y-m-d",time());
		}
}
$comma="";
ksort($pizza_record);
foreach($pizza_record as $k => $val){
	$pizza_records .= $comma."{date:\"".$k."\",".implode(",",$val)."}";
	$comma=",";
}
$comma2="";
foreach($pizza_ykeys_labels as $k2 => $val2){
	$pizza_ykeys .= $comma2.'"'.$k2.'"';
	$pizza_ylabels .= $comma2.'"'.$val2.'"';
	$comma2=",";
}

// PASTA
$select = "SELECT DATE_SUB(CURDATE(),INTERVAL 1 MONTH) as datefrom, DATE_FORMAT(O.date,'%Y-%m-%d') as date, DATE_FORMAT(O.date,'%b %d, %Y') as date2, COUNT(OD.pid) AS count,P.id,P.name FROM ".DBPREFIX."order_details as OD JOIN ".DBPREFIX."orders AS O ON O.id=OD.oid JOIN pfi_products AS P ON P.id=OD.pid WHERE $date_range AND OD.type='1'  AND O.status='1' AND P.cid='1378427715353' GROUP BY $groupby";
$sql->query($select);
$orders_array="";
$records="";
$comma="";
while($data=$sql->fetch_array()){
		$pasta_record[$data["date"]][] = "\"".$data["name"]."\":\"".$data["count"]."\"" ;
		
		$pasta_ykeys_labels[$data["name"]]=ucfirst($data["name"]);
		
		/*$data_table[] = array(
			"date" => $data["date2"],
			"product" => $data["name"],
			"count" => $data["count"]
		);*/
		
		if(!$datefrom){
			$datefrom=$data["datefrom"];
			$dateto = date ( "Y-m-d",time());
		}
}
$comma="";
ksort($pasta_record);
foreach($pasta_record as $k => $val){
	$pasta_records .= $comma."{date:\"".$k."\",".implode(",",$val)."}";
	$comma=",";
}
$comma2="";
foreach($pasta_ykeys_labels as $k2 => $val2){
	$pasta_ykeys .= $comma2.'"'.$k2.'"';
	$pasta_ylabels .= $comma2.'"'.$val2.'"';
	$comma2=",";
}

// grill
$select = "SELECT DATE_SUB(CURDATE(),INTERVAL 1 MONTH) as datefrom, DATE_FORMAT(O.date,'%Y-%m-%d') as date, DATE_FORMAT(O.date,'%b %d, %Y') as date2, COUNT(OD.pid) AS count,P.id,P.name FROM ".DBPREFIX."order_details as OD JOIN ".DBPREFIX."orders AS O ON O.id=OD.oid JOIN pfi_products AS P ON P.id=OD.pid WHERE $date_range AND OD.type='3'  AND O.status='1' AND P.cid='2222' GROUP BY $groupby";
$sql->query($select);
$orders_array="";
$records="";
$comma="";
while($data=$sql->fetch_array()){
		$grill_record[$data["date"]][] = "\"".$data["name"]."\":\"".$data["count"]."\"" ;
		
		$grill_ykeys_labels[$data["name"]]=ucfirst($data["name"]);
		
		/*$data_table[] = array(
			"date" => $data["date2"],
			"product" => $data["name"],
			"count" => $data["count"]
		);*/
		
		if(!$datefrom){
			$datefrom=$data["datefrom"];
			$dateto = date ( "Y-m-d",time());
		}
}
$comma="";
ksort($grill_record);
foreach($grill_record as $k => $val){
	$grill_records .= $comma."{date:\"".$k."\",".implode(",",$val)."}";
	$comma=",";
}
$comma2="";
foreach($grill_ykeys_labels as $k2 => $val2){
	$grill_ykeys .= $comma2.'"'.$k2.'"';
	$grill_ylabels .= $comma2.'"'.$val2.'"';
	$comma2=",";
}

// sides
$select = "SELECT DATE_SUB(CURDATE(),INTERVAL 1 MONTH) as datefrom, DATE_FORMAT(O.date,'%Y-%m-%d') as date, DATE_FORMAT(O.date,'%b %d, %Y') as date2, COUNT(OD.pid) AS count,P.id,P.name FROM ".DBPREFIX."order_details as OD JOIN ".DBPREFIX."orders AS O ON O.id=OD.oid JOIN pfi_products AS P ON P.id=OD.pid WHERE $date_range AND OD.type='1'  AND O.status='1' AND P.cid='1378428102254' GROUP BY $groupby";
$sql->query($select);
$orders_array="";
$records="";
$comma="";
while($data=$sql->fetch_array()){
		$sides_record[$data["date"]][] = "\"".$data["name"]."\":\"".$data["count"]."\"" ;
		
		$sides_ykeys_labels[$data["name"]]=ucfirst($data["name"]);
		
		/*$data_table[] = array(
			"date" => $data["date2"],
			"product" => $data["name"],
			"count" => $data["count"]
		);*/
		
		if(!$datefrom){
			$datefrom=$data["datefrom"];
			$dateto = date ( "Y-m-d",time());
		}
}
$comma="";
ksort($sides_record);
foreach($sides_record as $k => $val){
	$sides_records .= $comma."{date:\"".$k."\",".implode(",",$val)."}";
	$comma=",";
}
$comma2="";
foreach($sides_ykeys_labels as $k2 => $val2){
	$sides_ykeys .= $comma2.'"'.$k2.'"';
	$sides_ylabels .= $comma2.'"'.$val2.'"';
	$comma2=",";
}

// drinks
$select = "SELECT DATE_SUB(CURDATE(),INTERVAL 1 MONTH) as datefrom, DATE_FORMAT(O.date,'%Y-%m-%d') as date, DATE_FORMAT(O.date,'%b %d, %Y') as date2, COUNT(OD.pid) AS count,P.id,P.name FROM ".DBPREFIX."order_details as OD JOIN ".DBPREFIX."orders AS O ON O.id=OD.oid JOIN pfi_products AS P ON P.id=OD.pid WHERE $date_range AND OD.type='1'  AND O.status='1' AND P.cid='1378428102253' GROUP BY $groupby";
$sql->query($select);
$orders_array="";
$records="";
$comma="";
while($data=$sql->fetch_array()){
		$drinks_record[$data["date"]][] = "\"".$data["name"]."\":\"".$data["count"]."\"" ;
		
		$drinks_ykeys_labels[$data["name"]]=ucfirst($data["name"]);
		
		/*$data_table[] = array(
			"date" => $data["date2"],
			"product" => $data["name"],
			"count" => $data["count"]
		);*/
		
		if(!$datefrom){
			$datefrom=$data["datefrom"];
			$dateto = date ( "Y-m-d",time());
		}
}
$comma="";
ksort($drinks_record);
foreach($drinks_record as $k => $val){
	$drinks_records .= $comma."{date:\"".$k."\",".implode(",",$val)."}";
	$comma=",";
}
$comma2="";
foreach($drinks_ykeys_labels as $k2 => $val2){
	$drinks_ykeys .= $comma2.'"'.$k2.'"';
	$drinks_ylabels .= $comma2.'"'.$val2.'"';
	$comma2=",";
}

// 
//$template->loop($data_table,"data_table");

$filter_array["Daily"] = "daily";
$filter_array["Monthly"] = "monthly";
$filter_array["Yearly"] = "yearly";
foreach($filter_array as $k => $f){
	if($filter==$f){
		$filter_options .= '<option value="'.$f.'" selected="selected">'.$k.'</option>';
	}else{
		$filter_options .= '<option value="'.$f.'">'.$k.'</option>';
	}
}

$list = array(
		"pizza_data" => $pizza_records,
		"pizza_ykeys" => $pizza_ykeys,
		"pizza_ylabels" => $pizza_ylabels,
		
		"pasta_data" => $pasta_records,
		"pasta_ykeys" => $pasta_ykeys,
		"pasta_ylabels" => $pasta_ylabels,
		
		"grill_data" => $grill_records,
		"grill_ykeys" => $grill_ykeys,
		"grill_ylabels" => $grill_ylabels,
		
		"sides_data" => $sides_records,
		"sides_ykeys" => $sides_ykeys,
		"sides_ylabels" => $sides_ylabels,
		
		"drinks_data" => $drinks_records,
		"drinks_ykeys" => $drinks_ykeys,
		"drinks_ylabels" => $drinks_ylabels,
		
		"datefrom" => $datefrom,
		"dateto" => $dateto,
		"filter" => $filter_options
	);
$template->merge($list);
?>