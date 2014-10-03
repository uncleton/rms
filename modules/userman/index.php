<?php
if(!$global->set_permission(PERMIT_VIEW,true,$MODULEFOLDER)){
	header("Location: index.php");
};

$del = $form->GET("del");
if($del){
	$delete = "DELETE FROM ".DBPREFIX."users WHERE uid='$del'";
	$sql->query($delete);
	header("Location: userman.html");
}


$query = "SELECT P.lname,P.fname,U.uid,U.username,U.email,G.group_name FROM ".DBPREFIX."users as U JOIN ".DBPREFIX."usergroup AS G ON G.id=U.user_group JOIN ".DBPREFIX."profile AS P ON P.uid=U.uid ORDER BY P.lname ";
$sql->query($query);
$array_loop=array();
while($data = $sql->fetch_array()){
	$edit = "";
	$delete = "";

	
	$edit = '<a class="table-actions" href="userman-add.html?uid='.$data["uid"].'"><i class="icon-pencil"></i></a>';
	$delete = '<a class="table-actions" href="userman.html?del='.$data["uid"].'"><i class="icon-trash"></i></a>';
	
	$array_loop[] = array(
		"username" => $data["username"],
		"lname" => $data["lname"],
		"fname" => $data["fname"],
		"user_group" => $data["group_name"],
		"email" => $data["email"],
		"edit" => $edit,
		"delete" => $delete
	); 
}
$template->loop($array_loop,"records");

?>