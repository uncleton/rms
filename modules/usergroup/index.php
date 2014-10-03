<?php
//	Set page permission
#################################################################################
if(!$global->set_permission(PERMIT_VIEW,true,$MODULEFOLDER)){
	header("Location: index.php");
};
	//	Delete user group
	$did=$form->REQUEST("del");
	if($did){
		
			$query="DELETE FROM ".DBPREFIX."usergroup WHERE id='$did' AND id!='1'";
			$sql->query($query);
			header("Location: usergroup.html");
			exit;
		
	}


	
	
	//	process
	$query="SELECT SQL_BUFFER_RESULT SQL_CACHE id,group_name FROM ".DBPREFIX."usergroup ";
	$sql->query($query,$query);
	while($data=$sql->fetch_array($query)){
		
		
		$edit = '<a class="table-actions" href="usergroup-add.html?id='.$data["id"].'"><i class="icon-pencil"></i></a>';
		$delete = '<a class="table-actions" href="usergroup.html?del='.$data["id"].'"><i class="icon-trash"></i></a>';
		$perm = '<a class="table-actions" href="userpermission.html?id='.$data["id"].'"><i class="icon-lock"></i></a>';
		
		if($data["id"]){

			$array_group[]=array(
			"group" => $data["group_name"],
			"edit" => $edit,
			"delete" => $delete,
			"perm" => $perm,
			);
		}
	}
	$template->loop($array_group,"records");

?>