<?php
$filter=$form->POST("filter");
$datefrom=$form->POST("datefrom");
$dateto=$form->POST("dateto");
		
switch($filter){
	default :
		if($datefrom&&$dateto){
			$date_range=" DATE_FORMAT(date,'%Y-%m-%d') BETWEEN '$datefrom' AND '$dateto'";
		}else{
			$date_range="date>=DATE_SUB(CURDATE(),INTERVAL 1 MONTH)";
		}
		$select = "SELECT DATE_SUB(CURDATE(),INTERVAL 1 MONTH) as datefrom, DATE_FORMAT(date,'%Y-%m-%d') as date, DATE_FORMAT(date,'%b %d, %Y') as date2, SUM(price) as price FROM ".DBPREFIX."expenses WHERE $date_range GROUP BY DATE_FORMAT(date,'%b %d') ";
	break;
	
	case "monthly" :
		if($datefrom&&$dateto){
			$date_range=" DATE_FORMAT(date,'%Y-%m-%d') BETWEEN '$datefrom' AND '$dateto'";
		}else{
			$date_range="date>=DATE_SUB(CURDATE(),INTERVAL 1 MONTH)";
		}
		$select = "SELECT DATE_SUB(CURDATE(),INTERVAL 1 MONTH) as datefrom, DATE_FORMAT(date,'%Y-%m') as date, DATE_FORMAT(date,'%b %Y') as date2, SUM(price) as price FROM ".DBPREFIX."expenses WHERE $date_range GROUP BY DATE_FORMAT(date,'%b') ";
	break;
	
	case "yearly" :
		if($datefrom&&$dateto){
			$date_range=" DATE_FORMAT(date,'%Y-%m-%d') BETWEEN '$datefrom' AND '$dateto'";
		}else{
			$date_range="date>=DATE_SUB(CURDATE(),INTERVAL 1 MONTH)";
		}
		$select = "SELECT DATE_SUB(CURDATE(),INTERVAL 1 MONTH) as datefrom, DATE_FORMAT(date,'%Y') as date, DATE_FORMAT(date,'%Y') as date2, SUM(price) as price FROM ".DBPREFIX."expenses WHERE $date_range GROUP BY DATE_FORMAT(date,'%Y') ";
	break;
}
$sql->query($select);
$recs="";
while($data=$sql->fetch_array()){
	
	$recs[$data["date"]]["price"] = $data["price"];
	
	if(!$datefrom){
			$datefrom=$data["datefrom"];
			$dateto = date ( "Y-m-d",time());
	}
}

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
while($data=$sql->fetch_array()){
		
	$recs[$data["date"]]["sales"] = $data["total"];
	$sales = $data["total"] + $sales;
		
}

$orders_array="";
$records="";
$comma="";
foreach($recs as $key => $val){
	
	$records .= $comma."{date:\"".$key."\",price:\"".round($val["price"],2)."\",sales:\"".round($val["sales"],2)."\"}" ;
	$comma = ",";
		
	$data_table[] = array(
		"date" => $key,
		"price" => round($val["price"],2),
		"sales" => round($val["sales"],2),
		"margin" => round(((($val["sales"]-$val["price"])/$val["sales"])*100),2)."%"
	);
		
	$price = $val["price"] + $price;
}


$data_table[] = array(
			"date" => "TOTAL",
			"price" => round($price,2),
			"sales" => round($sales,2),
			"margin" => round(((($sales-$price)/$sales)*100),2)."%"
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