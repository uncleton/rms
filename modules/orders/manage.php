<?php

/*
$select = "SELECT id FROM ".DBPREFIX."orders ORDER BY date ";
$sql->query($select,$select);
$num=0;
while($data = $sql->fetch_array($select)){
	$num++;
	$update = "UPDATE ".DBPREFIX."orders SET order_number='$num' WHERE id='".$data["id"]."'";
	$sql->query($update,$update);
}*/

$datefrom = $form->POST("datefrom");
$dateto=$form->POST("dateto");

if(!$global->set_permission(PERMIT_VIEW,true,$MODULEFOLDER)){
	header("Location: index.php");
};

$del = $form->GET("del");
if($del){
	// Delete Orders
	$delete = "DELETE FROM ".DBPREFIX."orders WHERE id='$del'";
	$sql->query($delete);
	
	// Delete Order Details
	$delete = "DELETE FROM ".DBPREFIX."order_details WHERE oid='$del'";
	$sql->query($delete);
	
	// Delete Revenue
	$delete = "DELETE FROM ".DBPREFIX."revenue WHERE oid='$del'";
	$sql->query($delete);
	
	// Adjust Stocks then delete Stock log
	$select = "SELECT * FROM ".DBPREFIX."stocks_sale_log WHERE oid = '$del'";
	$sql->query($select,$select);
	while($data = $sql->fetch_array($select)){
		$update = "UPDATE ".DBPREFIX."ingredients SET stocks=(stocks+".($data["stocks"]*-1).") WHERE id = '".$data["iid"]."'";
		$sql->query($update);	
	}
	// Delete Stock Log
	$delete = "DELETE FROM ".DBPREFIX."stocks_sale_log WHERE oid='$del'";
	$sql->query($delete);
	
	
	header("Location: orders-manage.html");
}

$array_data = array(
	"datefrom" => $datefrom,
	"dateto" => $dateto
);
$template->merge($array_data);

//$query = "SELECT * FROM ".DBPREFIX."patients ORDER BY lname ";
//$sql->query($query);
//$array_loop=array();
//while($data = $sql->fetch_array()){
//	$edit = "";
//	$delete = "";
//
//	
//	//$edit = '<a class="table-actions" href="orders-add.html?id='.$data["oid"].'"><i class="icon-pencil"></i></a>';
//	$delete = '<a class="table-actions" href="records-patient.html?del='.$data["id"].'"><i class="icon-trash"></i></a>';
//	
//	$array_loop[] = array(
//		"pid" => $data["pid_no"],
//		"lname" => $data["lname"],
//		"fname" => $data["fname"],
//		"mname" => $data["mname"],
//		"gender" => $data["gender"],
//		"doctor" => $data["doctor"],
//		"location" => $data["location"],
//		"bday" => date('M j Y ', strtotime($data["bday"])),
//		"edit" => $edit,
//		"delete" => $delete
//	); 
//}
//$template->loop($array_loop,"records");

?>