<?php
//	Set page permission
#################################################################################
//$temp_permission = ($global->numeric($_GET["id"])) ? PERMIT_EDIT : PERMIT_ADD;
if(!$global->set_permission(PERMIT_VIEW,true,$MODULEFOLDER)){
	header("Location: index.php");
};
	
	
	//	Get POST Action
	$id = $form->REQUEST("id");
	$name = $form->POST("name");
	$code = $form->POST("code");
	$his_code = $form->POST("his_code");
	$specimen = $form->POST("specimen");
	$instrument = $form->POST("instrument");
	$dilution0 = $form->POST("dilution0");
	$dilution1 = $form->POST("dilution1");
	$dilution2 = $form->POST("dilution2");
	$value_normal = $form->POST("value_normal");
	$value_val = $form->POST("value_val");
	$value_critical = $form->POST("value_critical");
	$value_qc = $form->POST("value_qc");
	
	//print_r($value);
	$saved = "";
	//	Add / Edit process
	if($name && $code && $specimen && $instrument && $value_normal){
		
		$select = "SELECT id FROM ".DBPREFIX."tests WHERE id='$id'";
		$sql->query($select);
		$data = $sql->fetch_array();
		if(isset($data["id"])){
		
			$query="UPDATE ".DBPREFIX."tests SET 
				name = '".$form->data["name"]."',
				code = '".$form->data["code"]."',
				his_code = '".$form->data["his_code"]."',
				specimen = '".$form->data["specimen"]."',
				instrument = '".$form->data["instrument"]."',
				dilution0 = '".$form->data["dilution0"]."',
				dilution1 = '".$form->data["dilution1"]."',
				dilution2 = '".$form->data["dilution2"]."',
				value_normal = '".$form->data["value_normal"]."',
				value_val = '".$form->data["value_val"]."',
				value_critical = '".$form->data["value_critical"]."',
				value_qc = '".$form->data["value_qc"]."'
				WHERE id='$id'";
			$sql->query($query);
		
		}else{
			$insert = "INSERT INTO ".DBPREFIX."tests
			(
				name,
				code,
				his_code,
				specimen,
				instrument,
				dilution0,
				dilution1,
				dilution2,
				value_normal,
				value_val,
				value_critical,
				value_qc
			) VALUES (
				'".$form->data["name"]."',
				'".$form->data["code"]."',
				'".$form->data["his_code"]."',
				'".$form->data["specimen"]."',
				'".$form->data["instrument"]."',
				'".$form->data["dilution0"]."',
				'".$form->data["dilution1"]."',
				'".$form->data["dilution2"]."',
				'".$form->data["value_normal"]."',
				'".$form->data["value_val"]."',
				'".$form->data["value_critical"]."',
				'".$form->data["value_qc"]."'
			)";
			$sql->query($insert);
		}
		
		header("Location: system-assay.html");
		
	}else{
		$query="SELECT * FROM ".DBPREFIX."tests WHERE id='$id'";
		$sql->select($query);
		$data = $sql->fetch_array();
		
		if(isset($data["id"])){
			$id = $data["id"];
			$name = $data["name"];
			$code = $data["code"];
			$his_code = $data["his_code"];
			$specimen = $data["specimen"];
			$instrument = $data["instrument"];
			$dilution0 = $data["dilution0"];
			$dilution1 = $data["dilution1"];
			$dilution2 = $data["dilution2"];
			$value_normal = $data["value_normal"];
			$value_val = $data["value_val"];
			$value_critical = $data["value_critical"];
			$value_qc = $data["value_qc"];	
		}
		
	}
	
	$specimen_array = array(
		"Serum" => "serum",
		"Whole blood" => "whole blood"
	);
	$specimens="";
	foreach($specimen_array as $k => $v){
		if($specimen==$v){
				$specimens .= '<option value="'.$v.'" selected="selected">'.$k.'</option>';
		}else{
			$specimens .= '<option value="'.$v.'">'.$k.'</option>';
		}
	}
	
	
	//	Generate output
	$array=array(
		"id" => $id,
		"name" => $name,
		"code" => $code,
		"his_code" => $his_code,
		"specimen" => $specimens,
		"instrument" => $instrument,
		"dilution0" => $dilution0,
		"dilution1" => $dilution1,
		"dilution2" => $dilution2,
		"value_normal" => $value_normal,
		"value_val" => $value_val,
		"value_critical" => $value_critical,
		"value_qc" => $value_qc
	);
	$template->merge($array);

?>