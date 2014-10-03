<?php
if(!$global->set_permission(PERMIT_VIEW,true,$MODULEFOLDER)){
	header("Location: index.php");
};

$del = $form->GET("del");
if($del){
	$delete = "DELETE FROM ".DBPREFIX."packages WHERE id='$del'";
	$sql->query($delete);
	header("Location: inventory-packages.html");
}


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