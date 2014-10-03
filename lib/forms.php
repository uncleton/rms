<?php

# Form Method Constants
define("GET","GET");
define("POST","POST");

# Define Data Type Constant
define("NUMERIC","NUMERIC");
define("BOOL","BOOL");
define("ARRAY","ARRAY");
define("STRING","STRING");
define("MIXED","MIXED");
define("RTF","RTF");
define("HTML","HTML");
define("DOCUMENT","DOCUMENT");
define("IMAGE","IMAGE");
define("EMAIL","EMAIL");
define("ZIP","ZIP");

class formsfunctions extends globalfunctions {
	
	var $title;
	var $details;
	var $paging;
	var $errorMsg;
	var $dataReq;
	var $data;
	var $menutemplate;
		
	# Input Form Handlers
	# TextField, TextArea, HiddenField, CheckBox, List Menu, Jump Menu, Button, Image Field and Radio Button

	//textField
	function textField($name,$value=0){
		if($name){
			if( is_array( $args = func_get_args() ) )
			{
				array_shift($args);
				array_shift($args);
				
				reset($args);
				while ( key ($args) !== NULL ){
					$options .= current($args)." ";
					next($args);
				}
				//Default Value
				if($this->data[$name]){
					$value = $this->data[$name];
				}
				$this->errorMsg[$name] ? $asterisk = " <font face=\"Tahoma\" size=\"-1\" color=\"#FF0000\">*</font>" : $asterisk ="";
				return	$name = "<input type=\"text\" name=\"$name\" id=\"$name\" value=\"".$this->anti_inject_no($value)."\" ".$options."> $asterisk ";
			}
		} else {
			print 'ERROR: $form->textField() requires form name';
		}
	}
	
	// FILE
	function fileField($name){
		if($name){
			if( is_array( $args = func_get_args() ) )
			{
				array_shift($args);
				array_shift($args);
				
				reset($args);
				while ( key ($args) !== NULL ){
					$options .= current($args)." ";
					next($args);
				}
				$this->errorMsg[$name] ? $asterisk = " <font face=\"Tahoma\" size=\"-1\" color=\"#FF0000\">*</font>" : $asterisk ="";
				return	$name = "<input type=\"File\" name=\"$name\" id=\"$name\" ".$options."> $asterisk ";
			}
		} else {
			print 'ERROR: $form->File() requires form name';
		}
	}
	// TextArea
	function textArea($name,$value=0){
		if($name){
			if( is_array( $args = func_get_args() ) )
			{
				array_shift($args);
				array_shift($args);
				
				reset($args);
				while ( key ($args) !== NULL ){
					$options .= current($args)." ";
					next($args);
				}
				//Default Value
				if($this->data[$name]){
					$value = $this->data[$name];
				}
				
				
				$this->errorMsg[$name] ? $asterisk = " <font face=\"Tahoma\" size=\"-1\" color=\"#FF0000\">*</font>" : $asterisk ="";
				return	$name = "<textarea type=\"text\" name=\"$name\" id=\"$name\" ".$options.">".$this->anti_inject_no($value)."</textarea> $asterisk ";
			}
		} else {
			print 'ERROR: $form->textArea() requires form name';
		}
	}
	// Select
	function select($name,$opts,$value=0){
		if($name&&is_array($opts)){
			if( is_array( $args = func_get_args() ) )
			{
				array_shift($args);
				array_shift($args);
				array_shift($args);
				
				reset($args);
				while ( key ($args) !== NULL ){
					$options .= current($args)." ";
					next($args);
				}
				
				// Set Values
				if(is_array($value)){
					foreach($opts as $key => $val){
						if(!is_array($val)){
							$select="";
							foreach($value as $vkey => $vval){
								$val == $vval ? $select = "selected=\"selected\"" : $select;
							}
							
							$opt .= "<option value=\"{$val}\" $select>{$key}</option>\n";
						}else{
							$opt .= "<optgroup label='$key'></optgroup>";
						}
					}
				} else {
					foreach($opts as $key => $val){
						if(!is_array($val)){
							$val == $value ? $select = "selected=\"selected\"" : $select=""; 
							$opt .= "<option value=\"{$val}\" $select>{$key}</option>\n";
						}else{
							$opt .= "<optgroup label='$key'></optgroup>";
						}
					}
				}
				
				// Check is Multiple Enabled
				if( in_array("multiple",$args) || in_array("multiple=multiple",$args) || in_array('multiple="multiple"',$args)){
					return	$name = "<select name=\"{$name}[]\" id=\"$name\" ".$options.">$opt</select>";
				} else {
					return	$name = "<select name=\"$name\" id=\"$name\" ".$options.">$opt</select>";
				}
			}
		} else {
			print 'ERROR: $form->select() requires form "name", "options" and "value"';
		}
	}		
	// hiddenField
	function hiddenField($name,$value=0){
		if($name){
			if( is_array( $args = func_get_args() ) )
			{
				array_shift($args);
				array_shift($args);
				
				reset($args);
				while ( key ($args) !== NULL ){
					$options .= current($args)." ";
					next($args);
				}
			//Default Value
			if($this->data[$name]){
				$value = $this->data[$name];
			}
			return	$name = "<input type=\"hidden\" name=\"$name\" id=\"$name\" value=\"".$this->anti_inject_no($value)."\" ".$options.">";
			}
		} else {
			print 'ERROR: $form->hiddenField() requires form name';
		}
	}
	// checkbox
	function checkBox($name,$value){
		if($name&&$value){
			if( is_array( $args = func_get_args() ) )
			{
				array_shift($args);
				array_shift($args);
				
				in_array("multiple",$args);
				
				reset($args);
				while ( key ($args) !== NULL ){
					$options .= current($args)." ";
					next($args);
			}

			$checked = $this->data[$name] == $value ? "checked=\"checked\"" : "";
				
			return	$name = "<input type=\"checkbox\" name=\"$name\" id=\"$name\" value=\"$value\" $checked ".$options.">";
			}
		} else {
			print 'ERROR: $form->checkBox() requires form "name" and "value"';
		}
	}
	// Radio 
	
	function radioButton($name,$value){
		if($name&&$value){
			if( is_array( $args = func_get_args() ) )
			{
				array_shift($args);
				array_shift($args);

				
				reset($args);
				while ( key ($args) !== NULL ){
					$options .= current($args)." ";
					next($args);
			}

			$checked = $this->data[$name] == $value ? "checked=\"checked\"" : "";
				
			return	$name = "<input type=\"radio\" name=\"$name\" id=\"$name\" value=\"$value\" $checked ".$options.">";
			}
		} else {
			print 'ERROR: $form->radioButton() requires form "name" and "value"';
		}
	}	
	// Button
	function button($name,$value="",$type="submit"){
		if($name&&$value&&$type){
			if( is_array( $args = func_get_args() ) )
			{
				array_shift($args);
				array_shift($args);
				array_shift($args);
				
				reset($args);
				while ( key ($args) !== NULL ){
					$options .= current($args)." ";
					next($args);
				}
				
				return	$name = "<input type=\"$type\" name=\"$name\" id=\"$name\" value=\"$value\" {$options}>";
			}
		} else {
			$type == "button" ? print 'ERROR: $form->button() Check form "name", "value" and "type"' : print 'ERROR: $form->button() requires form "name" and "value"';
			
		}
	}


	//  GENERATE HTML AREA
	//	CONFIGURATIONS
	//	SET SKIN : VALUES : "default" , "office2003", "silver"
	var $skin='office2003';
	//	SET DIRECTORY PATH
	var $sBasePath='/editor/';
	//	SET TOOLBAR : CAN BE "Default" or "Basic"
	var $toolbarset='Default';
	
	function RTFArea($name,$value,$height=400){	
		$fckeditor = new FCKeditor();
		$fckeditor->FCKeditor($name);
		$fckeditor->BasePath = $this->get_editor_path().$this->sBasePath ;
		$fckeditor->Height=$height;
		$fckeditor->Config['SkinPath'] = $this->get_editor_path().$this->sBasePath . 'editor/skins/'.htmlspecialchars($this->skin).'/';
		$fckeditor->ToolbarSet = htmlspecialchars($this->toolbarset);  
		$fckeditor->Value = $this->anti_inject_no($value);
		return $fckeditor->Create_editor();
	}	
	function get_editor_path(){
		$dir=(explode("/",$_SERVER['PHP_SELF']));
		$num_dir= count($dir)-3;
		for($i=1; $i<=$num_dir; $i++){
		$root_dir.="/".$dir[$i];
		}
			return DOMAIN_NAME.'admin/lib';

	}	
	
	
		
	# HTTP Request Handlers
	# POST, GET, REQUEST, and special handler RTF's are declared here
	# also inlcudes form data validations and error Handlers

	//	Get Data
	function GET($form,$type="STRING",$msg=NULL){
		global $GET;
		if(isset($_GET[$form])){
		
			$this->data[$form] = $this->anti_inject_high( $this->anti_xss_high($_GET[$form]) );
		}
		if(isset($GET[$form])){
			$this->data[$form] = $this->anti_inject_high( $this->anti_xss_high($GET[$form]) );
		}
		$this->validate($form,$type,$msg);
		if(isset($this->data[$form])){
			return $this->data[$form];
		}
	}

	//	Get Data
	function FILE($form,$type="DOCUMENT",$msg=NULL,$size=2097152){
		//print_r($_FILES[$form]);
		if($_FILES[$form]["name"]){
			$this->data[$form] = $_FILES[$form];
		}
		$this->validate($form,$type,$msg,$size);
		return $this->data[$form];
	}

	//	POST Data
	function POST($form,$type="STRING",$msg=NULL){
		global $global;
		$this->data[$form]="";
		if($type!="EMAIL"){
			$res = "";
			if(!empty($_POST[$form])){
				$res = $_POST[$form];
				if(is_array($res)){
					if($type!='RTF'){
						$i=0;
						foreach($res as $key => $val){
							$values[] = $this->anti_inject_high( $this->anti_xss_high($val) );	
							$i++;
						}
					}else{
						$i=0;
						foreach($res as $key => $val){
							$values[] = $this->anti_inject_low( $this->anti_xss_low($val) );	
							$i++;
						}
					}
					$this->data[$form] = $values;
				} else {
					if($type!='RTF'){
						$this->data[$form] = $this->anti_inject_high( $this->anti_xss_high($res) );
					}else{
						$this->data[$form] = $this->anti_inject_low( $this->anti_xss_low($res) );
					}
				}
				
			}
			if(is_array($res)){
				$type=="MIXED" || $type=="ARRAY" ? $this->validate($form,$type,$msg) : print "ERROR: Data type mismatch. ARRAY cannot be declared as {$type}. Try using \"ARRAY\" or \"MIXED\"";
			}else{
				$this->validate($form,$type,$msg);
			}
		}else{
			if($global->email($_POST[$form])){
				$this->data[$form] = $_POST[$form];
			}else{
				$this->validate($form,$type,$msg);
			}
		}
		return $this->data[$form];
	}

	//	REQUEST Data
	function REQUEST($form,$type="STRING",$msg=NULL){
		global $GET;
		if(isset($_REQUEST[$form])){
			$this->data[$form] = $this->anti_inject_high( $this->anti_xss_high($_REQUEST[$form]) );
		}
		if(isset($GET[$form])){
			$this->data[$form] = $this->anti_inject_high( $this->anti_xss_high($GET[$form]) );
		}
		$this->validate($form,$type,$msg);
		
		return $this->data[$form];
	}
	
	//	Get RTF
	function RTF($form,$type="STRING",$msg=NULL){
		if($_POST[$form]){
			if($type=="HTML"){
				$this->data[$form] = $_POST[$form];
			} else {
				$this->data[$form] = $this->anti_inject_low( $this->anti_xss_low($_POST[$form]) );
			}
		}
		$this->validate($form,$type,$msg);
		
		return $this->data[$form];
	}
	
	// GET URI
	function SERVER($elements){
		return $this->anti_xss_low($_SERVER["$elements"]);
	}
			
	function validate($form,$type,&$msg,$size=2097152){

		switch ($type) {
			case "NUMERIC" :
				 is_numeric($this->data[$form])==true ? $this->data[$form] : $this->data[$form] = '';
			break ;
			case "DOCUMENT" :
			//print $this->data[$form]["type"];
				if($this->data[$form]["type"]!="application/pdf"&&$this->data[$form]["type"]!="application/msword"&&$this->data[$form]["type"]!='application/vnd.openxmlformats-officedocument.wordprocessingml.document'&&$this->data[$form]["type"]!='application/vnd.oasis.opendocument.spreadsheet'){
					$this->data[$form] = "";
				}
			break ;
			case "IMAGE" :
				// Validate File Type
				
				if( $this->data[$form]["type"] && strtolower($this->data[$form]["type"])!="image/jpeg" &&  strtolower($this->data[$form]["type"])!="image/gif" &&  strtolower($this->data[$form]["type"]) !="image/png" ){
					$msg = "* Only Jpeg, Gif and PNG images are allowed.";
					$this->data[$form] = 0;
				}
				
				//check File size not more than 2MB
				//print $size;
				if($this->data[$form]["name"] && ($this->data[$form]["size"]> $size)){
					$msg = "* Image file size must not be more than 2Mb.";
					$this->data[$form] = 0;
				}
			break;				
			case "BOOL" :
				 $data !== "" ? $this->data[$form] = $this->data[$form] : false;
			break ;
			case "STRING" :
				if(isset($this->data[$form])){
					$this->data[$form] = $this->data[$form];
				}
			break ;
			case "MIXED" :
				if(is_array($this->data[$form])){
					count($this->data[$form])>0 ? $this->data[$form] : $this->data[$form]='';
				} else {
					$this->data[$form] = $this->data[$form];
				}
			break ;			
			case "ARRAY" :
				if(is_array($this->data[$form])){
					count($this->data[$form])>0 ? $this->data[$form] : $this->data[$form]='';
				}
			break ;
			case "RTF":
				$this->data[$form] = $this->data[$form];
			break ;
			case "HTML":
				$this->data[$form] = $this->data[$form];
			break ;
			case "EMAIL":
				if(!$this->email($this->data[$form])){ $this->data[$form]='';}
			break ;
			case "ZIP" :
				if($this->data[$form]["type"]!="application/zip"){
					$this->data[$form] = "";
				}
			break;
		}
		// Check if Data and error message is present then alert
		$msg != NULL ? $this->dataReq[$form] = $msg : "" ;				
		$msg != NULL ? $this->data[$form] ? "" : $this->errorMsg[$form] = $msg : "";
		
	}
	
	function error(){
		if($_SERVER['REQUEST_METHOD']=="POST"){
			if($this->errorMsg == $this->dataReq ){
				$this->errorMsg["DEFAULT"] ? $error = $this->errorMsg["DEFAULT"] : $error = "All Fields Marked With (*) are required.";
			} else {
				if(is_array($this->errorMsg)){
					while (key($this->errorMsg) !== NULL ){
						 current($this->errorMsg) ? $error .= "* " . current($this->errorMsg) . "<br>" : "" ;
						next($this->errorMsg);
					}
				}
			}
			return '<div style="padding:10px;">'.$error.'</div>';
		}
	}
	
	function isvalidated(){
		return count($this->errorMsg)>0 ? false : true;
	}
	
	function method($method=true){

		switch ($method){
			default :
				return "post";
			break;
			case "GET" :
				return "get";
			break ;
		}
	}
	
	function dataReload($dataset=false){
		global $sql;
		
		// Get key value pairs
		if($dataset){
			reset($sql->key);
			while( key($sql->key) !== NULL ){
				$wherekeys .= $and.key($sql->key)." = '".current($sql->key)."'";
				$and = " and ";
				next($sql->key);
			}
			$query = "SELECT * FROM ".DBPREFIX."{$sql->table} WHERE {$wherekeys}";	
			$sql->select($query,0,"RELOAD EXISTING DATA");
			$dataset = $sql->fetch_array();
		}
		foreach($this->data as $key => $val){
			if(!$val){
				$this->data[$key] = $dataset[$key];
				$this->errorMsg[$key] = NULL;
			}
		}
	}
	
	function menu($array){	
		// TEMPLATE DURING THE LOOP
		//ereg("<loop menu>(.*)</loop menu>",$this->menutemplate, $tloop);
		preg_match("/<fort:loop\s*?name\s*=\s*\"menu\"\s*>(.*)<\/fort\s*>/imsU",$this->menutemplate, $tloop);
		if (is_array($array)) {
			foreach($array as $key => $arr) {
					$temp = $tloop[1];
					foreach ($arr as $arkey => $arval) {
						if($arkey=="active"){
							$arval ? $arval = "color:#CCCCCC;" : $arval = "color:#FFFFFF;";
						}
						$temp = str_replace("{".$arkey."}", $arval, $temp);
					}
					$ret .= $temp;
			}
		} else {
			$ret="";
		}		
		$this->menutemplate = str_replace($tloop[0], $ret, $this->menutemplate);  		 	  
	}

	function button_add($url,$title="Add Record"){	
		// TEMPLATE DURING THE LOOP
		$butt_add = '<a href="'.$url.'"><img src="templates/default/Add_small.png" title="'.$title.'" border="0" width="32" height="32"/></a> ';
		$this->menutemplate = str_replace("{butt_add}", $butt_add, $this->menutemplate);		 	  
	}
	function button_addmodule($url,$title="Add Module"){	
		// TEMPLATE DURING THE LOOP
		$butt_add = '<a href="'.$url.'"><img src="templates/default/add_module.png" title="'.$title.'" border="0" width="32" height="32"/></a> ';
		$this->menutemplate = str_replace("{butt_mod}", $butt_add, $this->menutemplate);		 	  
	}
	function button_addlink($url,$title="Add Link"){	
		// TEMPLATE DURING THE LOOP
		$butt_add = '<a href="'.$url.'"><img src="templates/default/link_add.png" title="'.$title.'" border="0" width="32" height="32"/></a> ';
		$this->menutemplate = str_replace("{butt_link}", $butt_add, $this->menutemplate);		 	  
	}		
	function button_delete($url,$title="Delete Record"){	
		// TEMPLATE DURING THE LOOP
		$butt_delete = '<a href="'.$url.'"><img src="templates/default/Delete.png"  title="'.$title.'" border="0" width="32" height="32"/></a> ';
		$this->menutemplate = str_replace("{butt_delete}", $butt_delete, $this->menutemplate);		 	  
	}
	function button_edit($url,$title="Edit Record"){	
		// TEMPLATE DURING THE LOOP
		$butt_edit = '<a href="'.$url.'"><img src="templates/default/edit_small.png"  title="'.$title.'" border="0" width="32" height="32"/></a> ';
		$this->menutemplate = str_replace("{butt_edit}", $butt_edit, $this->menutemplate);		 	  
	}		
	function button_install($url,$title="Install"){	
		// TEMPLATE DURING THE LOOP
		$butt_install = '<a href="'.$url.'"><img src="templates/default/install.png"  title="'.$title.'" border="0" width="32" height="32"/></a> ';
		$this->menutemplate = str_replace("{butt_install}", $butt_install, $this->menutemplate);		 	  
	}		
	function button_config($url,$title="Settings"){	
		// TEMPLATE DURING THE LOOP
		$butt_config = '<a href="'.$url.'"><img src="templates/default/Config.png"  title="'.$title.'" border="0" width="32" height="32"/></a> ';
		$this->menutemplate = str_replace("{butt_config}", $butt_config, $this->menutemplate);		 	  
	}
	function cleantags(){
		$this->menutemplate = str_replace("{name}", "", $this->menutemplate);
		$this->menutemplate = str_replace("{butt_add}", "", $this->menutemplate);
		$this->menutemplate = str_replace("{butt_edit}", "", $this->menutemplate);
		$this->menutemplate = str_replace("{butt_mod}", "", $this->menutemplate);
		$this->menutemplate = str_replace("{butt_link}", "", $this->menutemplate);
		$this->menutemplate = str_replace("{butt_install}", "", $this->menutemplate);
		$this->menutemplate = str_replace("{butt_delete}", "", $this->menutemplate);
		$this->menutemplate = str_replace("{butt_config}", "", $this->menutemplate);
		return  $this->menutemplate;
	}
	
}

$form = new formsfunctions;
?>