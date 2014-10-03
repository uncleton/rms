<?php
//	Set page permission
#################################################################################
//$temp_permission = ($global->numeric($_GET["id"])) ? PERMIT_EDIT : PERMIT_ADD;
if(!$global->set_permission(PERMIT_VIEW,true,$MODULEFOLDER)){
	header("Location: index.php");
};
	
	
	//	Get POST Action
	
	$name = $form->POST("name","ARRAY");
	$value = $form->POST("value","ARRAY");
	$varid = $form->POST("varid","ARRAY");
	//print_r($value);
	$saved = "";
	//	Add / Edit process
	if(is_array($name)){
		
		foreach($name as $id => $val){
			$query="UPDATE ".DBPREFIX."settings SET 
			value='".$global->anti_inject_high($value[$id])."',
			varid='".$global->anti_inject_high($varid[$id])."'
			WHERE id='".$global->anti_inject_high($val)."'";
			$sql->query($query);
		}
		$saved = "Changes have been successfully saved!";
		
	}
		$query="SELECT SQL_BUFFER_RESULT SQL_CACHE name,id,varid,value FROM ".DBPREFIX."settings";
		$sql->select($query);
		$layout = "";
		while($data=$sql->fetch_array()){
			
			if(preg_match("/Custom/i",$data["name"])){
				$readonly = '';	
			}else{
				$readonly='readonly';	
			}
			$layout .='
			<div class="form-group">
            <label class="control-label col-md-2">'.$data["name"].'</label>
            <div class="col-md-4">
              <input class="form-control" placeholder="Variable Name" value="'.$data["id"].'" name="name[]" type="hidden"><input class="form-control" placeholder="Variable Name" value="'.$data["varid"].'" name="varid[]" type="text">
            </div>
            <div class="col-md-4">
              <input class="form-control" placeholder="Value" value="'.$data["value"].'" name="value[]" type="text">
            </div>
          </div>';
			
					
		}

	//	Generate output
	$array=array(
		"layout" => $layout,
		"alert" => $saved
	);
	$template->merge($array);



?>