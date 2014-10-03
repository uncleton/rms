<?php

	$id = $form->REQUEST("id");

	//	Add / Edit process
	if($id){
	
		$query="SELECT SQL_BUFFER_RESULT SQL_CACHE U.type FROM ".DBPREFIX."ingredients AS I JOIN ".DBPREFIX."unit_conversion AS U ON U.unit=I.unit WHERE I.id='$id'";
		$sql->select($query);
		$data=$sql->fetch_array();
		
		$query = "SELECT * FROM ".DBPREFIX."unit_conversion";
		$sql->query($query);
		
		$array_loop[0]["unit"] = 0;
		$array_loop[0]["name"] = "Select Unit";
		$i=0;
		while($data2 = $sql->fetch_array()){
			if($data["type"]==$data2["type"]){
				$array_loop[] = array(
					"unit" => $data2["unit"],
					"name" => $data2["name"]
				);
			}
		}
		$template->loop($array_loop,"units_record");
		
		$array = array(
			"id" => $id
		);
		
		$template->merge($array);
		
		print $template->output;
	} 
	
	exit;
	
?>
