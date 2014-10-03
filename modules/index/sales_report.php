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
		$select = "SELECT DATE_SUB(CURDATE(),INTERVAL 1 MONTH) as datefrom, DATE_FORMAT(O.date,'%Y-%m-%d') as date, DATE_FORMAT(O.date,'%b %d, %Y') as date2, SUM(R.cost) as cost, SUM(R.subtotal) as subtotal, SUM(R.total) as total, SUM(R.discount) as discount, SUM(R.vat) as vat FROM ".DBPREFIX."revenue as R JOIN ".DBPREFIX."orders AS O ON O.id=R.oid WHERE $date_range GROUP BY DATE_FORMAT(O.date,'%b %d') ";
	break;
	
	case "monthly" :
		if($datefrom&&$dateto){
			$date_range=" DATE_FORMAT(O.date,'%Y-%m-%d') BETWEEN '$datefrom' AND '$dateto'";
		}else{
			$date_range="O.date>=DATE_SUB(CURDATE(),INTERVAL 1 MONTH)";
		}
		$select = "SELECT DATE_SUB(CURDATE(),INTERVAL 1 MONTH) as datefrom, DATE_FORMAT(O.date,'%Y-%m') as date, DATE_FORMAT(O.date,'%b %Y') as date2, SUM(R.cost) as cost, SUM(R.subtotal) as subtotal, SUM(R.total) as total, SUM(R.discount) as discount, SUM(R.vat) as vat FROM ".DBPREFIX."revenue as R JOIN ".DBPREFIX."orders AS O ON O.id=R.oid WHERE $date_range GROUP BY DATE_FORMAT(O.date,'%b') ";
	break;
	
	case "yearly" :
		if($datefrom&&$dateto){
			$date_range=" DATE_FORMAT(O.date,'%Y-%m-%d') BETWEEN '$datefrom' AND '$dateto'";
		}else{
			$date_range="O.date>=DATE_SUB(CURDATE(),INTERVAL 1 MONTH)";
		}
		$select = "SELECT DATE_SUB(CURDATE(),INTERVAL 1 MONTH) as datefrom, DATE_FORMAT(O.date,'%Y') as date, DATE_FORMAT(O.date,'%Y') as date2, SUM(R.cost) as cost, SUM(R.subtotal) as subtotal, SUM(R.total) as total, SUM(R.discount) as discount, SUM(R.vat) as vat FROM ".DBPREFIX."revenue as R JOIN ".DBPREFIX."orders AS O ON O.id=R.oid WHERE $date_range GROUP BY DATE_FORMAT(O.date,'%Y') ";
	break;
}
$sql->query($select);
$orders_array="";
$records="";
$comma="";
while($data=$sql->fetch_array()){
		$records .= $comma."{date:\"".$data["date"]."\",sales:\"".round($data["total"],2)."\",cost:\"".round($data["cost"],2)."\",discount:\"".round($data["discount"],2)."\"}" ;
		$comma = ",";
		
		$data_table[] = array(
			"date" => $data["date2"],
			"sales" => round($data["total"],2),
			"cost" => round($data["cost"],2),
			"discount" => round($data["discount"],2),
		);
		
		$sales = $data["total"] + $sales;
		$cost = $data["cost"] + $cost;
		$discount = $data["total"] + $discount;
		if(!$datefrom){
			$datefrom=$data["datefrom"];
			$dateto = date ( "Y-m-d",time());
		}
}

$data_table[] = array(
			"date" => "TOTAL",
			"sales" => round($sales,2),
			"cost" => round($cost,2),
			"discount" => round($discount,2),
		);
$template->loop($data_table,"data_table");

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
		"data" => $records,
		"datefrom" => $datefrom,
		"dateto" => $dateto,
		"filter" => $filter_options
	);
$template->merge($list);
?>