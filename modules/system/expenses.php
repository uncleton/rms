<?php
if(!$global->set_permission(PERMIT_VIEW,true,$MODULEFOLDER)){
	header("Location: index.php");
};

$del = $form->GET("del");
if($del){
	$delete = "DELETE FROM ".DBPREFIX."expenses WHERE id='$del'";
	$sql->query($delete);
	header("Location: system-expenses.html");
}


?>