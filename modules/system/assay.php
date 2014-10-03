<?php
if(!$global->set_permission(PERMIT_VIEW,true,$MODULEFOLDER)){
	header("Location: index.php");
};

$del = $form->GET("del");
if($del){
	$delete = "DELETE FROM ".DBPREFIX."tests WHERE id='$del'";
	$sql->query($delete);
	header("Location: system-assay.html");
}


$query = "SELECT * FROM ".DBPREFIX."tests ORDER BY name ";
$sql->query($query);
$array_loop=array();
while($data = $sql->fetch_array()){
	$edit = "";
	$delete = "";

	
	$edit = '<a class="table-actions" href="system-assayadd.html?id='.$data["id"].'"><i class="icon-pencil"></i></a>';
	$delete = '<a class="table-actions" href="system-assay.html?del='.$data["id"].'"><i class="icon-trash"></i></a>';
	
	$array_loop[] = array(
		"code" => $data["code"],
		"test_code" => $data["test_code"],
		"name" => $data["name"],
		"specimen" => $data["specimen"],
		"instrument" => $data["instrument"],
		"dilution0" => $data["dilution0"],
		"dilution1" => $data["dilution1"],
		"dilution2" => $data["dilution2"],
		"value_normal" => $data["value_normal"],
		"value_val" => $data["value_val"],
		"value_critical" => $data["value_critical"],
		"value_qc" => $data["value_qc"],
		"edit" => $edit,
		"delete" => $delete
	); 
}
$template->loop($array_loop,"records");

?>