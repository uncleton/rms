<?php
//	Set page permission
#################################################################################
if(!$global->set_permission(PERMIT_VIEW,true,$MODULEFOLDER)){
	header("Location: index.php");
};

	//	Get Post Request
	$id=$form->REQUEST("id");
	if(!$id){$id=$global->gen_id();}
	$user_group=$form->POST("group");

	//	Add / Edit process
	if($user_group){
		$query="SELECT SQL_BUFFER_RESULT SQL_CACHE id FROM ".DBPREFIX."usergroup WHERE id='$id'";
		$sql->select($query);
		$data=$sql->fetch_array();
		if($data["id"]){
			$query="UPDATE ".DBPREFIX."usergroup SET 
			group_name='".$global->anti_inject_high($user_group)."' WHERE id='$id'";
			$sql->query($query);
			header("Location: usergroup.html");
			exit;
		} else {
			$query="INSERT INTO ".DBPREFIX."usergroup (
			id,group_name) VALUES (
			'$id','".$global->anti_inject_high($user_group)."')";
			$sql->query($query);
			header("Location: usergroup.html");
			exit;
		}
	} else {
		$query="SELECT SQL_BUFFER_RESULT SQL_CACHE id,group_name FROM ".DBPREFIX."usergroup WHERE id='$id'";
		$sql->select($query);
		$data=$sql->fetch_array();
		if($data["id"]){
			$id=$data["id"];
			$user_group=$data["group_name"];
		}	
	}
	
	//	Generate output
	$array=array(
	"id" => $id,
	"group" => $user_group
	);
	$template->merge($array);


?>