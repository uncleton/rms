<?php
if(!$global->set_permission(PERMIT_VIEW,true,$MODULEFOLDER)){
	header("Location: index.php");
};

$del = $form->GET("del");
if($del){
	$delete = "DELETE FROM ".DBPREFIX."ingredients WHERE id='$del'";
	$sql->query($delete);
	header("Location: inventory-ingredients.html");
}


$query = "SELECT * FROM ".DBPREFIX."unit_conversion";
$sql->query($query);

$array_loop[0]["unit"] = 0;
$array_loop[0]["name"] = "Select Unit";
$i=0;
while($data = $sql->fetch_array()){
	$i++;
	$array_loop[] = array(
		"unit" => $data["unit"],
		"name" => $data["name"]
	); 
}
$template->loop($array_loop,"units_record");

?>