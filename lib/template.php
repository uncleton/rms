<?PHP
//	FORM OPTION PARAM
define("ALLNUM","allnum");
define("NUMVALUES","numvalues");
//	FORM INPUT PARAM
define("TEXT","text");
define("CHECKBOX","checkbox");
define("HIDDEN","hidden");
define("RADIO","radio");
define("BUTTON","button");
define("SUBMIT","submit");
define("FILE","file");
define("TEXTAREA","textarea");

class templatefunctions  {
	var $body;
	var $header;
	var $footer;
	var $link_tree;
	var $navigator;

//	template process vars	
	var $template_output;
	var $plugin_path;
	var	$output;
	var $body_title;
	var $body_keywords;
	var $body_description;
	var $template_module;
	var $template_system;
	var $template_index;
	var $page_link;
	var $image_src;
	var $template_url;
	
	var $plugins;

			
	function create(){
		$array = array(
			"body_title" => $this->body_title,
			"body_keywords" => $this->body_keywords,
			"body_description" => $this->body_description,
			"image_src" => $this->image_src,
			"page_link" => $this->page_link,
			"base_url" => ABSOLUTE_PATH,
			"template_url" => $this->template_url,
		);
		
		
		if(preg_match("/admin/",$_SERVER['PHP_SELF'])&&!MODREWRITE){
			$out = $this->template_output;
		}else{
			$templates = $this->curly_join($array,$this->template_output);
			//echo($this->fix_internal_paths(preg_replace("/\{[a-zA-Z0-9_]+\}/","", $templates)));
			$out= preg_replace("/\{[a-zA-Z0-9_]+\}/","", $templates);
		}
		
		
		// Clean Fort Tags
		$out = preg_replace("/<fort:.*\/\s*?>/imsU",'',$out);
		$out = preg_replace("/<fort:.*?>.*<\/fort\s*?>/imsU",'',$out);
		
		 echo $out;
		
	}
/*	function navigator($name){
		$this->template_output=str_replace("{".$name."}",$this->output,$this->template_output);
	}*/
	function get_output(){
		return $this-> output;
	}
	function get_template_output(){
		return $this-> template_output;
	}

	function mushroom($tag,$mushroom){
		$this->template_output=str_replace("{".$tag."}",$mushroom,$this->template_output);
	}	
	
	function merge_elements($array){
		$Template=$this->template_output;
			
			if (sizeof($array) > 0) // If the array of vars is not emtpy. 
			{ 
				foreach ($array as $tags => $tag) // Loops through the array. 
				{ 
					foreach ($tag as $modes => $mode) // Loops through the array. 
					{ 
						foreach ($mode as $objs => $obj) // Loops through the array. 
						{ 
						// Replaces {var} with the array value ($content)
						// THIS ALSO LOOPS $TEMPLATE TO MAINTAIN THE CHANGES MADE IN THE 
						// TEMPLATE DURING THE LOOP
							if(!preg_match("/admin/",$_SERVER['PHP_SELF'])){
								$Template=preg_replace("/<fort\:".$tags."\s".$modes."=\"".$objs."\"[^>]*\/>/i",$obj, $Template); 
							}
						}
					} 
				} // End Foreach. 
			}
			$this->template_output=$Template; 
	}		
	
	function html_body(){
		$array=array(
		"header" => $this->header,
		"footer" => $this->footer,
		"navigator" => $this->navigator,
		"link_tree" => $this->link_tree,
		"content_module" => $this->template_module,
		"content_system" => $this->template_system,
		"content_index" => $this->template_index,
		);
		return $this->curly_join($array,$this->body);
	}

	// FUNCTION TO GENERATE TEMPLATE
	function template_combine($array=false,$fix_path=false){
		/*if($fix_path){
			$Template=$this->fix_html_global_paths($this->html_body());
		} else {*/
		$Template=$this->html_body();		
		//}
		if($Template){
			if ($array&&sizeof($array) > 0) // If the array of vars is not emtpy. 
			{ 
				foreach ($array as $var => $content) // Loops through the array. 
				{ 
					// Replaces {var} with the array value ($content)
					// THIS ALSO LOOPS $TEMPLATE TO MAINTAIN THE CHANGES MADE IN THE 
					// TEMPLATE DURING THE LOOP
					$Template=str_replace("{".$var."}", $content, $Template);  
				} // End Foreach. 
				$this->template_output=$Template;
			} else {
				$this->template_output=$this->output;
			}
		} else {
			$this->template_output=$this->output;
		}  	
		
	}

	function curly_join($array,$Template){
			if (sizeof($array) > 0) // If the array of vars is not emtpy. 
			{ 
				foreach ($array as $var => $content) // Loops through the array. 
				{ 
					// Replaces {var} with the array value ($content)
					// THIS ALSO LOOPS $TEMPLATE TO MAINTAIN THE CHANGES MADE IN THE 
					// TEMPLATE DURING THE LOOP
					$Template=str_replace("{".$var."}", $content, $Template);  
				} // End Foreach. 
			}
			return $Template;		  
	}
	
	// NEW FUNCTIONS
	function fort_join($array){
			$Template=$this->output;
			
			if (sizeof($array) > 0) // If the array of vars is not emtpy. 
			{ 
				foreach ($array as $tags => $tag) // Loops through the array. 
				{ 
				
					foreach ($tag as $modes => $mode) // Loops through the array. 
					{ 
					
						foreach ($mode as $objs => $obj) // Loops through the array. 
						{ 
						
						// Replaces {var} with the array value ($content)
						// THIS ALSO LOOPS $TEMPLATE TO MAINTAIN THE CHANGES MADE IN THE 
						// TEMPLATE DURING THE LOOP
							//if(preg_match("/admin/",$_SERVER['PHP_SELF'])){
								
								$Template=preg_replace("/<fort\:".$tags."\s".$modes."=\"".$objs."\"[^>]*\/>/i",$obj, $Template); 
							//}
						}
					} 
				} // End Foreach. 
			}
			$this->output=$Template;  
	}
	
	
	
	function merge($array){
			$Template=$this->output;
			if (sizeof($array) > 0) // If the array of vars is not emtpy. 
			{ 
				foreach ($array as $var => $content) // Loops through the array. 
				{ 
					// Replaces {var} with the array value ($content)
					// THIS ALSO LOOPS $TEMPLATE TO MAINTAIN THE CHANGES MADE IN THE 
					// TEMPLATE DURING THE LOOP
					$Template=str_replace("{".$var."}", $content, $Template);  
				} // End Foreach. 
			}
			$this->output=$Template;  
	}
		
	function merge_section($array,$tag,$display=true){
		$Template=$this->output;
		preg_match_all("/<fort:section\s*?name\s*=\s*\"$tag\s*\"\s*>(.*?)<\/fort\s*>/ims",$Template, $temp);
		//print_r($temp);
			$result=$temp[1][0];
			if($display){
				if (sizeof($array) > 0 && $array) // If the array of vars is not emtpy. 
				{ 
					foreach ($array as $var => $content) // Loops through the array. 
					{ 
						// Replaces {var} with the array value ($content)
						// THIS ALSO LOOPS $TEMPLATE TO MAINTAIN THE CHANGES MADE IN THE 
						// TEMPLATE DURING THE LOOP
						$result=str_replace("{".$var."}", $content, $result);  
					} // End Foreach. 
				}
			} else {
				$result='';
			}		  
	 	$this->output=str_replace($temp[0][0],$result,$Template);
	}
	
	function merge_block($array,$tag,$display=true){
		$Template=$this->output;
		
		preg_match_all("/<fort:block\s*?name\s*=\s*\"$tag\s*\"\s*>(.*?)<\/fort\:block\s*>/ims",$Template, $temp);
		
		//print_r($temp);
			$result=$temp[1][0];
			if($display){
				if (sizeof($array) > 0 && $array) // If the array of vars is not emtpy. 
				{ 
					foreach ($array as $var => $content) // Loops through the array. 
					{ 
						// Replaces {var} with the array value ($content)
						// THIS ALSO LOOPS $TEMPLATE TO MAINTAIN THE CHANGES MADE IN THE 
						// TEMPLATE DURING THE LOOP
						$result=str_replace("{".$var."}", $content, $result);  
					} // End Foreach. 
				}
			} else {
				$result='';
			}		  
	 	$this->output=str_replace($temp[0][0],$result,$Template);
	}
	
	function merge_section_switch($array,$tag1,$tag2,$display=1){
		
		if($display==1){
			$this->merge_section($array,$tag1,1);
			$this->merge_section($array,$tag2,0);
		}elseif($display==2){
			$this->merge_section($array,$tag1,0);
			$this->merge_section($array,$tag2,1);	
		}
		
	}
	
	function loop($array,$tag,$ads=""){
		// TEMPLATE DURING THE LOOP
		//ereg("<loop $tag>(.*)</loop $tag>",$this->output, $tloop);
		preg_match("/<fort:loop\s*?name\s*=\s*\"$tag\s*\"\s*>(.*)<\/fort\s*>/imsU",$this->output, $tloop);
		if (is_array($array)) {
			$ret = "";
			foreach($array as $key => $arr) {
					$temp = $tloop[1];
					foreach ($arr as $arkey => $arval) {
						$temp = str_replace("{".$arkey."}", $arval, $temp);
					}
					$ret .= $temp;
					//	INSERT ADS
					if($ads){
						$i++;
						$d=(count($array)/2);
						if($i==$d||$i==($d+0.5)){
							$ret.=$ads;
						}
					}
			}
		} else {
			$ret=$ads;
		}			
		$this->output=str_replace($tloop[0], $ret, $this->output);  	  
	}
	
	function loop_hv($array,$tagv,$tagh){
	   $Template=$this->output;
		// TEMPLATE HORIZONTAL VERTICAL LOOP
		//ereg("<loop $tagv>(.*)</loop $tagv>",$Template, $tloop);
		preg_match("/<fort:loop\s*?name\s*=\s*\"$tagv\s*\"\s*>(.*<\/fort\s*>.*)<\/fort\s*>/imsU",$Template, $tloop);
		//ereg("<loop $tagh>(.*)</loop $tagh>",$tloop[1], $tlooph);
		preg_match("/<fort:loop\s*?name\s*=\s*\"$tagh\s*\"\s*>(.*)<\/fort\s*>/imsU",$tloop[1], $tlooph);
		
		$ca=count($array);
		
		if (is_array($array)) {
		
			for($i=1; $i<=$ca; $i++){
				$reth="";
				foreach($array[$i] as $key => $arr) {
					$temp=$tlooph[1];
					foreach ($arr as $arkey => $arval) {		
						$temp = str_replace("{".$arkey."}", $arval, $temp);
					}
					$reth .= $temp;
				}
				$retv.=str_replace($tlooph[0], $reth, $tloop[1]);
			}		
			$this->output= str_replace($tloop[0], $retv, $Template);  		
		}		 	  
	}

	function loop_section($array,$tagml,$tagsec,$tagv){
		// TEMPLATE LOOP WITH NESTED SECTION
		//ereg("<loop $tagml>(.*)</loop $tagml>",$this->output, $tloopm);
		$Template=$this->output; 
		preg_match("/<fort:loop\s*?name\s*=\s*\"$tagml\s*\"\s*>(.*<\/fort\s*>.*<\/fort\s*>.*)<\/fort\s*>/imsU",$Template, $tloopm);
		
		preg_match("/<fort:section\s*?name\s*=\s*\"$tagsec\s*\"\s*>(.*<\/fort\s*>.*)<\/fort\s*>/imsU",$tloopm[0], $tsecm);
		//ereg("<section $tagsec>(.*)</section $tagsec>",$tloopm[0], $tsecm);
		
		//ereg("<loop $tagv>(.*)</loop $tagv>",$this->output, $tloopv);
		preg_match("/<fort:loop\s*?name\s*=\s*\"$tagv\s*\"\s*>(.*)<\/fort\s*>/imsU",$Template, $tloopv);	
		
		if (is_array($array)) {
			foreach($array as $key => $arr) {
				$temp = $tloopm[1];	
				foreach ($arr as $arkey => $arval) {
					
					if(!is_array($arval)){
						$temp = str_replace("{".$arkey."}", $arval, $temp);
					} else {
							$retv="";
							$ca=count($arval);
							for($i=0; $i<$ca; $i++){
								$temp2=$tloopv[1];
								
								foreach($arval[$i] as $arrvkey => $arrv) {
										$temp2=str_replace("{".$arrvkey."}", $arrv, $temp2);

								}
								$retv.=$temp2;
							}
						$temp = str_replace($tsecm[0],str_replace($tloopv[0],$retv,$tsecm[1]),$temp);			
					}
				}
				$ret .= $temp;
			}
		}
		$this->output=str_replace($tloopm[0], $ret, $Template);  		 	  
	}
	
	function template_loop_dynamic($array,$tagml,$tagsec,$tagv){
		// TEMPLATE DURING THE LOOP
		ereg("<loop $tagml>(.*)</loop $tagml>",$this->output, $tloopm);
		ereg("<section $tagsec>(.*)</section $tagsec>",$tloopm[0], $tsecm);
		
		ereg("<loop $tagv>(.*)</loop $tagv>",$this->output, $tloopv);	

		if (is_array($array)) {
			foreach($array as $key => $arr) {
				$temp = $tloopm[1];	
				foreach ($arr as $arkey => $arval) {

					if(!is_array($arval)){
						$temp = str_replace("{".$arkey."}", $arval, $temp);
					} else {
							$retv=""; 
							$ca=count($arval);
							for($i=0; $i<$ca; $i++){
								$temp2=$tloopv[1];
								
								foreach($arval[$i] as $arrvkey => $arrv) {
										$temp2=str_replace("{".$arrvkey."}", $arrv, $temp2);

								}
								$retv.=$temp2; 
							}
						$temp = str_replace($tsecm[0],str_replace($tloopv[0],$retv,$tsecm[0]),$temp);			
					}
				}
				$ret .= $temp;
			}
		}
		$this->output=str_replace($tloopm[0], $ret, $this->output);  		 	  
	}

	## SINGLE LOOP PROCESS
	function t_loop($array,$tag,$ads=""){
		// TEMPLATE DURING THE LOOP
		ereg("<loop $tag>(.*)</loop $tag>",$this->output, $tloop);
		if (is_array($array)) {
			foreach($array as $key => $arr) {
				$temp = $tloop[1];	
				foreach ($arr as $arkey => $arval) {
					$temp = str_replace("{".$arkey."}", $arval, $temp);
				}//END foreach ($arr as $arkey => $arval)
				$ret .= $temp;
				//	INSERT ADS
				if($ads){
					$i++;
					$d=(count($array)/2);
					if($i==$d||$i==($d+0.5)){
						$ret.=$ads;
					}//END if($i==$d||$i==($d+0.5))
				}//END if($ads)
			}//END foreach($array as $key => $arr)
		} else {
			$ret=$ads;
		}			
		$this->output=str_replace($tloop[0], $ret, $this->output);  	  
	}//end function pfi_loop($array,$tag,$ads="")



//	FORM HANDLERS
############################################################
//	FORM HANDLERS
	function form_listmenu($anames,$avalues,$curval,$fname,$param,$fstyle="",$fothers="",$errmsg=""){
		if($fstyle){
			$style='style="'.$fstyle.'"';
		}

		$req='<span style="font-family:Tahoma; color:RED; font-size:11px">'.$errmsg.'</span>';

		$form='<select name="'.$fname.'" id="'.$fname.'" '.$style.' '.$fothers.'>';
		$count=count($anames);
		switch ($param){
		default;
			for($i=0; $i<=($count-1); $i++){
				if($curval==$avalues[$i]){
				$options.='<option value="'.$avalues[$i].'" selected>'.$anames[$i].'</option>';
				} else {
				$options.='<option value="'.$avalues[$i].'">'.$anames[$i].'</option>';
				}
				
			}
		break;
		case NUMVALUES;
			for($i=1; $i<=$count; $i++){
				if($curval==$i){
				$options.='<option value="'.$i.'" selected>'.$anames[($i-1)].'</option>';
				} else {
				$options.='<option value="'.$i.'">'.$anames[($i-1)].'</option>';
				}
			}		
		break;		
		case ALLNUM;
			
			for($i=$anames; $i<=$avalues; $i++){
				if($curval==$i){
				$options.='<option value="'.$i.'" selected>'.$i.'</option>';
				} else {
				$options.='<option value="'.$i.'">'.$i.'</option>';
				}
			}		
		break;	
		}
		return $form.$options.'</select> '.$req;		
	}
	
	var $bdaystyle = 'f1';
	function form_date($date,$required=false){
		$date=explode("-",$date);
		$curval = '';
		if($date[0] == 0 || $date[0] == ""){$curval = 'all';}
		if($date[1] == 0 || $date[1] == ""){$curval = 'all';}
		if($date[2] == 0 || $date[2] == ""){$curval = 'all';}
		
		if($required){
			if($curval == 'all'){
				$req='<font face="Tahoma" size="-1" color="RED"> * </font><span style="font-family:Tahoma; color:RED; font-size:11px">'.$errmsg.'</span>';
			}
		}

	
		$mn=array("Month","January","February","March","April","May","June","July","August","September","October","November","December");
		$mv=array(0,1,2,3,4,5,6,7,8,9,10,11,12);
		
		$dn[] = "Day&nbsp;";
		$dv[] = "0";
		for($x=1;$x<=31;$x++){
			$dn[] = $x;
			$dv[] = $x;
		}

		$yn[] = "Year&nbsp;";
		$yv[] = "0";
		for($y=(date("Y")-65);$y<=date("Y");$y++){
			$yn[] = $y;
			$yv[] = $y;
		}
		
		// get day
		$d.=$this->form_listmenu($dn,$dv,$date[2],"day",NULL,"","class=\"".$this->bdaystyle."\"");	
		// get month	
		$d.=$this->form_listmenu($mn,$mv,$date[1],"month",NULL,"","class=\"".$this->bdaystyle."\"");	
		// get year	
		$d.=$this->form_listmenu($yn,$yv,$date[0],"year",NULL,"","class=\"".$this->bdaystyle."\"");
		
		return $d.$req;
	}
	
	########################################
	########################################
	function form_month($curval,$name){

	$mn=array("January","February","March","April","May","June","July","August","September","October","November","December");
	$mv=array(1,2,3,4,5,6,7,8,9,10,11,12);
		// get month	
		$d=$this->form_listmenu($mn,$mv,$curval,$name,NULL);	
		return $d;
	}
	
	function form_day($curval,$name){
	$dn=array("-- Day --","01","02","03","04","05","06","07","08","09","10","11","12","13","14","15","16","17","18","19","20","21","22","23","24","25","26","27","28","29","30","31");
	$dv=array(0,1,2,3,4,5,6,7,8,9,10,11,12,13,14,15,16,17,18,19,20,21,22,23,24,25,26,27,28,29,30,31);
		// get day	
		$d=$this->form_listmenu($dn,$dv,$curval,$name,NULL);	
		return $d;
	}
	
	function form_year($curval,$name){
		// get year
		$coma='';
		for($i=(2000); $i<=date("Y"); $i++){
			$mnames.=$coma.$i;
			$mvalues.=$coma.$i;
			$coma=",";
		}
		//$mnames="-- Year --,".$mnames;
		//$mvalues="0,".$mvalues;

		$mnames=explode(",",$mnames);
		$mvalues=explode(",",$mvalues);
			
		$d=$this->form_listmenu($mnames,$mvalues,$curval,$name,NULL);
		return $d;
	}
	########################################
	########################################

//	FORM TEXT FIELD
	function formfield($curval,$fname,$param="text",$fstyle="",$fothers="",$required=false,$errmsg=""){
		if($fstyle){
			$style='style="'.$fstyle.'"';
		}
		if($required){
			if($errmsg){
				$req='<font face="Tahoma" size="-1" color="RED"> * </font>&nbsp;<span style="font-family:Tahoma; color:RED; font-size:11px">'.$errmsg.'</span>';
			}else{
				$req='<font face="Tahoma" size="-1" color="RED"> * </font>';
			}
		}else{
			$req='<span style="font-family:Tahoma; color:RED; font-size:11px">'.$errmsg.'</span>';
		}
		switch ($param){
			default;
			
				$output='<input name="'.$fname.'" id="'.$fname.'" type="'.$param.'" '.$style.' '.$fothers.' value="'.$curval.'"/> ';
				if(!$curval){
					$output.="$req";
				}
				return $output;
			break;
			
			case TEXTAREA;
				$output='<textarea name="'.$fname.'" id="'.$fname.'" '.$style.' '.$fothers.'>'.$curval.'</textarea> ';
				if(!$curval){
					$output.="$req";
				}
				return $output;
			break;
		}
	
	}

//	Redefine Paths of bg, images, css , js
	function fix_html_module_paths($html){

		// parse image source
		preg_match_all("/\< *img[^\>]*src *= *[\"\']{0,1}([^\"\'\ >]*)/i",$html,$images);
		// parse bg source
		preg_match_all("/\< *[td][^\>]*background *= *[\"\']{0,1}([^\"\'\ >]*)/i",$html, $bg);
		// parse JS source
		preg_match_all("/\< *script[^\>]*src *= *[\"\']{0,1}([^\"\'\ >]*)/i",$html, $js);
		// parse css source
		preg_match_all("/\< *link[^\>]*href *= *[\"\']{0,1}([^\"\'\ >]*)/i",$html, $css);

		//	redefine image paths
		for($i=0; $i<=count($images[1]); $i++){
		preg_match_all("/\< *img[^\>]*src *= *[\"\']{0,1}([^\"\'\ >]*)/i",$html,$img);
			if(!ereg("UserFiles",$images[1][$i])&&!ereg("templates",$img[1][$i])&& !ereg("http",$img[1][$i])){
					$html=str_replace($images[1][$i],ROOT_PATH.MODULE_PATH."templates/".$images[1][$i],$html);
			}
		}

		//	redefine image bg paths`
		for($i=0; $i<=(count($bg[1])-1); $i++){
		preg_match_all("/\< *[td][^\>]*background *= *[\"\']{0,1}([^\"\'\ >]*)/i",$html, $bg_);
			if(!ereg("UserFiles",$bg[1][$i])&&!ereg("templates",$bg_[1][$i])&& !ereg("http",$bg_[1][$i])){	
				   $html=str_replace($bg[1][$i],ROOT_PATH.MODULE_PATH."templates/".$bg[1][$i],$html);
			}	
		}

		//	redefine js paths`
		for($i=0; $i<=(count($js[1])-1); $i++){
			if(!ereg("templates",$js[1][$i]) && !ereg("http",$js[1][$i])){
			   $html=str_replace($js[1][$i],ROOT_PATH.MODULE_PATH."templates/".$js[1][$i],$html);
			}
		}
		//	redefine csss paths
		for($i=0; $i<=(count($css[1])-1); $i++){
			if(!ereg("templates",$css[1][$i]) && !ereg("http",$css[1][$i])){
			   $html=str_replace($css[1][$i],ROOT_PATH.MODULE_PATH."templates/".$css[1][$i],$html);
			}
		}	
		return $html; 
	}

//	IMAGE HANDLERS
############################################################
	function image($src,$w="",$h="",$alt="",$align="absmiddle",$border=0,$fothers=""){
		if($w){
			$w='width="'.$w.'"';
		}
		if($h){
			$h='height="'.$h.'"';
		}
		return '<img src="'.$src.'" title="'.$alt.'" alt="'.$alt.'" '.$w.$h.' align="'.$align.'" border="'.$border.'"/>';
	}	
#################	EDITOR	######################
// GENERATE HTML AREA
	//	CONFIGURATIONS
	//	SET SKIN : VALUES : "default" , "office2003", "silver"
	var $skin='office2003';
	//	SET DIRECTORY PATH
	var $sBasePath='/editor/';
	//	SET TOOLBAR : CAN BE "Default" or "Basic"
	var $toolbarset='Default';
	
	function html_editor($value,$name,$height=400){	
		$this->FCKeditor($name);
		$this->BasePath = $this->get_editor_path().$this->sBasePath ;
		$this->Height=$height;
		$this->Config['SkinPath'] = $this->get_editor_path().$this->sBasePath . 'editor/skins/'.htmlspecialchars($this->skin).'/';
		$this->Config['ForcePasteAsPlainText'] = true;
		$this->Config['AutoDetectPasteFromWord'] = true;
		$this->ToolbarSet = htmlspecialchars($this->toolbarset);  
		$this->Value = $this->quotes($value);
		return $this->Create_editor();
	}	
	function quotes($data){
		if (get_magic_quotes_gpc()){
			return stripslashes($data);
		} else {
			return $data;
		}
	}
	function get_editor_path(){
		$dir=(explode("/",$_SERVER['PHP_SELF']));
		$num_dir= count($dir)-3;
		for($i=1; $i<=$num_dir; $i++){
		$root_dir.="/".$dir[$i];
		}
			return $root_dir.'/admin/lib';

	}
	
	
	//	Itemid for application synergy 
	var $itemid;
	function get_itemid(){
		return $this->itemid;
	}	
}

$template = new templatefunctions;
?>