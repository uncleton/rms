<?php
//	LANG
define("ERROR_FILE_SRC","404 File not found! please check specified path or file if correct!");

//	DATE PARAMETERS
define("FULL_TEXT","FT");
define("FULL_TEXT_TIME","FTT");
define("SHORT_TEXT","ST");
define("SHORT_TEXT_TIME","STT");
define("TIMESTAMP","TST");
define("NUM","N");
define("NUM_TIME","NT");

//	FILE PARAMETERS
define("DEFAULT","default");
define("TYPE","type");
define("SIZE","size");

//	PERMISSION PARAMETERS
define("PERMIT_ADD","add");
define("PERMIT_EDIT","edit");
define("PERMIT_DELETE","delete");
define("PERMIT_VIEW","view");
define("PERMIT_APPROVE","approve");

require_once("curl.php");

class globalfunctions {
	var $globalsql;
	
	// Generate New ID
	function __construct(){
		global $sql;
		$this->globalsql = $sql;	
	}
	function gen_id(){
		return time().rand(100,999);
	}

	// Generate New Password
	function generatePassword($length){
	  // start with a blank password
	  $password = "";
	  // define possible characters
	  $possible = "0123456789abcdfghjkmnpqrstvwxyz";
	  // set up a counter
	  $i = 0; 
	  // add random characters to $password until $length is reached
	  while ($i < $length) { 
		// pick a random character from the possible ones
		$char = substr($possible, mt_rand(0, strlen($possible)-1), 1);   
		// we don't want this character if it's already in the password
		if (!strstr($password, $char)) { 
		  $password .= $char;
		  $i++;
		}
	  }
	  return $password;
	}

//	DATA VALIDATIONS
############################################################
//	Common functions used for data validations and filtering.

   //	TO CHECK IF ALPHANUMERIC , "." AND "_" ONLY
   function alphanumeric($data) {
		if(ereg("(^[a-zA-Z0-9]+([a-zA-Z\_0-9\.]*))$",$data)){
			return $data;
		}
   }

   function specialnumeric($data) {
		if(ereg("(^[0-9]+([0-9]*))$",$data)){
			return $data;
		}
   }

   function username(&$data) {
     if($data){
   	    if(strlen($data) >= 5 && strlen($data) < 255){
			if(ereg("(^[a-zA-Z0-9]+([a-zA-Z_0-9\_\.@]*))$",$data)){
				return $data;
			}else{
				return "INVALID";
			}
		}else{
			return "invalid_lenght";
		}
	 }
   }
   
   function mobile(&$number){
	   if($this->stringlen($number,7)){
			if(is_numeric($number)){
				return $number;	
			}   
	    }
   }
   function generate_username($data) {
     if($data){
	 	if(strlen($data) >= 5 && strlen($data) < 255){
			$keyword=preg_replace("/[^a-zA-Z]/","",$data);
			return $keyword;
		}
	 }
   }

    function password($data){
	   if($data){
			if(strlen($data) >= 5 && strlen($data) < 255){
				return $data;
			}else{
				return "INVALID";
			}
		}
   } 

   //	TO CHECK IF VALID EMAIL
   function email($data){
		if($data){
			if(preg_match("/^[a-zA-Z0-9_.-]+@[a-zA-Z0-9\-]+\.[a-zA-Z0-9\-\.]+/i", $data)){
				return $data;
			}
		}
   }

   //	TO CHECK IF NUMERIC ONLY
   function numeric($data,$limit=20) {
		if(is_numeric($data)){
			if($limit||0<$limit){
				if(strlen($data)<=$limit){
					return $data;
				}
			} else {
				return $data;
			}
		}
   }


	//	TO CHECK IF ALPHA ONLY
	function alpha($data) {
		if(ereg("(^[a-z A-Z]*)$",$data)){
			return $data;
		}else{
			return "INVALID";
		}
	}
	
	function alpha2($data) {
	if($data){
		if(ereg("(^[a-zA-Z]+([a-zA-Z\.\ \-]*))$",$data)){
			return $data;
		}else{
			return "INVALID";
		}
	 }
	}
	
	
	//	TO CHECK IF PHONE NUMBER
	function phone($data) {
		if(ereg("(^[0-9 +()-]*)$",$data)){
			return $data;
		}else{
			return "INVALID";
		}
	}
	
	// TO CHECK SPECIAL CHARACTERS
	function specialchar($data) {
		if($data){
			if (!preg_match ("/[&<>%\*\,\.]/i", $data)){
				return $data;
			}
		}	
	}
	
	
	//	TO STRING LENGTH
	function stringlen($data,$len){
		if($data&&$len){
			if(strlen($data)<$len){
				return true;
			}else{
				return false;
			}
		}else{
			return false;
		}
	}

	//	Get Data
	function GET($data){
		return $this->anti_xss_high($_GET[$data]);
	}

	//	POST Data
	function POST($data){
		return $this->anti_xss_high($_POST[$data]);
	}

	//	REQUEST Data
	function REQUEST($data){
		return $this->anti_xss_high($_REQUEST[$data]);
	}
	
	//	Get RTF
	function RTF($data){
		return $this->anti_xss_low($_POST[$data]);
	}

	//	HANDLING URL REDIRECTIONS
	############################################################   
   //	encode urlencoded string for redirection		
   function redirect_from($data){
		return urlencode($data);
	}
   //	decode srting for redirection
   function redirect_to($data){
		return urldecode($data);
	}

	//    HANDLING XSS
	############################################################   
	// Filter data from XSS
	// Applicable for RTF forms
	function anti_xss_low($data){
		if (get_magic_quotes_gpc()){
			$data=stripslashes($data);
		}
	
		// Clean Links
		preg_match_all('@<a[^>]*?>.*?</a>@si',$data , $links);
		for($i=0; $i<(count($links[0])); $i++){
			preg_match("/\< *a[^\>]*href *= *[\"\']{0,1}([^\"\'\ >]*)/i",$links[0][$i],$href);
			preg_match("@<a[^>]*?>@si",$links[0][$i],$fullhref);

			if(!eregi("javascript",$href[1])&&!eregi("#",$href[1])&&!eregi("\(",$href[1])&&!eregi("\)",$href[1])){
				$data=str_replace($fullhref,"[a href=\"".$href[1]."\"]",$data);
			} else {
				$data=str_replace($links[0][$i]," ",$data);
			}
		}
		$data=str_replace("</a>","[/a]",$data);
		$data=str_replace("</A>","[/A]",$data);

		// Clean Font
		preg_match_all('@<font([^>]+)>@si',$data , $font);
		for($f=0; ($f<=count($font[0])-1); $f++){
			$data = str_replace($font[0][$f], "[font".$font[1][$f]."]", $data);
		}
		$data=str_replace("</font>","[/font]",$data);
		$data=str_replace("</FONT>","[/FONT]",$data);

		// Clean Image
		preg_match_all('@<img([^>]+)>@si',$data , $image);
		for($p=0; ($p<=count($image[0])-1); $p++){
			$data = str_replace($image[0][$p], "[img".$image[1][$p]."]", $data);
		}

		// Clean data and Strip Tags
		// PARSE CONENT REMOVE JAVASCRIPT AND CSS
		$search = array(
			'@<script[^>]*?>.*?</script>@si',  // Strip out javascript
			'@<style[^>]*?>.*?</style>@siU',    // Strip style tags properly
			'@<![\s\S]*?--[ \t\n\r]*>@',        // Strip multi-line comments including CDATA            
			'@<link[^>]*?>@si'
		);

		//    allowed tags
		$allowed = array(
			'@<img.*?/>@si',
			'@<font.*?>@si',
			'@<br.*?/>@si','@<br.*?>@si',
			'@<hr.*?/>@si','@<hr.*?>@si',
			'@<ul.*?>@si','@</ul>@si',
			'@<li.*?>@si','@</li>@si',
			'@<ol.*?>@si','@</ol>@si',
			'@<a.*?>@si','@</a>@si',
			'@<b.*?>@si','@</b>@si',
			'@<center.*?>@si','@</center>@si',
			'@<em.*?>@si','@</em>@si',
			'@<h1.*?>@si','@</h1>@si',
			'@<h2.*?>@si','@</h2>@si',
			'@<h3.*?>@si','@</h3>@si',
			'@<h4.*?>@si','@</h4>@si',
			'@<h5.*?>@si','@</h5>@si',
			'@<h6.*?>@si','@</h6>@si',
			'@<i.*?>@si','@</i>@si',
			'@<p.*?>@si','@</p>@si',
			'@<q.*?>@si','@</q>@si',
			'@<small.*?>@si','@</small>@si',
			'@<strike.*?>@si','@</strike>@si',
			'@<strong.*?>@si','@</strong>@si',
			'@<sub.*?>@si','@</sub>@si',
			'@<sup.*?>@si','@</sup>@si',
			'@<u.*?>@si','@</u>@si',
		);
		$areplace = array(
			'[img /]',
			'[font]',
			'[br /]','[br]',
			'[hr /]','[hr]',
			'[ul]','[/ul]',
			'[li]','[/li]',
			'[ol]','[/ol]',
			'[a]','[/a]',
			'[b]','[/b]',
			'[center]','[/center]',
			'[em]','[/em]',
			'[h1]','[/h1]',
			'[h2]','[/h2]',
			'[h3]','[/h3]',
			'[h4]','[/h4]',
			'[h5]','[/h5]',
			'[h6]','[/h6]',
			'[i]','[/i]',
			'[p]','[/p]',
			'[q]','[/q]',
			'[small]','[/small]',
			'[strike]','[/strike]',
			'[strong]','[/strong]',
			'[sub]','[/sub]',
			'[sup]','[/sup]',
			'[u]','[/u]',		
			);
		$rallowed = array(
			'@\[img /\]@si',
			'@\[font\]@si',
			'@\[br /\]@si','@\[br\]@si',
			'@\[hr /\]@si','@\[hr\]@si',
			'@\[ul\]@si','@\[/ul\]@si',
			'@\[li\]@si','@\[/li\]@si',
			'@\[ol\]@si','@\[/ol\]@si',
			'@\[a\]@si','@\[/a\]@si',
			'@\[b\]@si','@\[/b\]@si',
			'@\[center\]@si','@\[/center\]@si',
			'@\[em\]@si','@\[/em\]@si',
			'@\[h1\]@si','@\[/h1\]@si',
			'@\[h2\]@si','@\[/h2\]@si',
			'@\[h3\]@si','@\[/h3\]@si',
			'@\[h4\]@si','@\[/h4\]@si',
			'@\[h5\]@si','@\[/h5\]@si',
			'@\[h6\]@si','@\[/h6\]@si',
			'@\[i\]@si','@\[/i\]@si',
			'@\[p\]@si','@\[/p\]@si',
			'@\[q\]@si','@\[/q\]@si',
			'@\[small\]@si','@\[/small\]@si',
			'@\[strike\]@si','@\[/strike\]@si',
			'@\[strong\]@si','@\[/strong\]@si',
			'@\[sub\]@si','@\[/sub\]@si',
			'@\[sup\]@si','@\[/sup\]@si',
			'@\[u\]@si','@\[/u\]@si',
		);
		$rreplace = array(
			'<img />',
			'<font>',
			'<br />','<br>',
			'<hr />','<hr>',
			'<ul>','</ul>',
			'<li>','</li>',
			'<ol>','</ol>',
			'<a>','</a>',
			'<b>','</b>',
			'<center>','</center>',
			'<em>','</em>',
			'<h1>','</h1>',
			'<h2>','</h2>',
			'<h3>','</h3>',
			'<h4>','</h4>',
			'<h5>','</h5>',
			'<h6>','</h6>',
			'<i>','</i>',
			'<p>','</p>',
			'<q>','</q>',
			'<small>','</small>',
			'<strike>','</strike>',
			'<strong>','</strong>',
			'<sub>','</sub>',
			'<sup>','</sup>',
			'<u>','</u>',
		);

		$data=preg_replace($rallowed,$rreplace,strip_tags(preg_replace($search,' ',preg_replace($allowed,$areplace,$data))));

		//    Restore Links
		preg_match_all('@\[a[^>]*?\].*?@si',$data,$links);
		for($i=0; $i<(count($links[0])); $i++){
			preg_match("/\[ *a[^\>]*href *= *[\"\']{0,1}([^\"\'\ \]]*)/i",$links[0][$i],$href);
			$data=str_replace($links[0][$i],"<a href=\"".$href[1]."\" >",$data);
		}

		//	Restore Font
		preg_match_all('@\[font([^\]]+)\]@si',$data , $fonts);
		for($f2=0; $f2<count($fonts[0]); $f2++){
			$data = str_replace($fonts[0][$f2], "<font".$fonts[1][$f2].">", $data);
		}
		
		//	Restore Image
		preg_match_all('@\[img([^\]]+)\]@si',$data , $images);
		for($p2=0; $p2<count($images[0]); $p2++){
			$data = str_replace($images[0][$p2], "<img".$images[1][$p2].">", $data);
		}

		$data = str_replace("[/font]","</font>",$data);
		$data = str_replace("[/a]","</a>",$data);
		return $data;
}

//    Applicable for all forms except RTF
	function anti_xss_high($data){
		if (get_magic_quotes_gpc()){
			return htmlentities(strip_tags(stripslashes($data)));
		} else {
			return htmlentities(strip_tags($data));
		}
	}
   
//	HANDLING SQL INJECTIONS 
############################################################   
// Filter data from SQL injection, HTML and Other scripts to protect database.
	
    //    High level of Anti sql injection.
    function anti_inject_high($data){
        $data=str_replace("&Atilde;&plusmn;","&ntilde;",str_replace('"',"&quot;",$data));
        if (get_magic_quotes_gpc()){
        return mysql_real_escape_string(stripslashes($data));
        } else {
        return mysql_real_escape_string($data);
        }
    }
    //    Low level of anti sql injection
    function anti_inject_low($data){
        $data=str_replace("&Atilde;&plusmn;","&ntilde;",str_replace('&amp;',"&",$data));
        if (get_magic_quotes_gpc()){
        return addslashes(stripslashes($data));
        } else {
        return addslashes($data);
        }
    }
    //    No anti sql injection
    function anti_inject_no($data){
        $data=str_replace("&Atilde;&plusmn;","&ntilde;",str_replace('"',"&quot;",$data));
        if (get_magic_quotes_gpc()){
            return stripslashes($data);
        } else {
            return $data;
        }
    }


//	HANDLING HTML DATAS AND BREAKS
############################################################   
// This handles HTML formated datas and breaks into string.
var $htmlbr=1;

	//	CONVERT HTML to STRING
	function htmltostring($data){
		if (get_magic_quotes_gpc()){
			$data= htmlentities(stripslashes($data));
		} else {
			$data= htmlentities($data);
		}
		
		if($this->htmlbr==1){
			return $this->brtostring($data);
		} else {
			return $data;
		}
	}
	
	//	Break
	function brtostring($data){
		return nl2br($data);
	}


//	HANDLING TEXT (WORDS, CHARCTERS)
############################################################   
// Handling text strings.


function ShortenText($text,$chars='150') {

$length = strlen($text);

$text = strip_tags($text);
$text = substr($text,0,$chars);

if($length > $chars) { $text = $text."..."; }

return $text;

}

function reduce_text($string,$len=300){
	$chars = $len;
	$text = $string."";
	$countchars = strlen($text);
	if($countchars > $chars) {
		$text = substr($text,0,$chars);
		$text = substr($text,0,strrpos($text,' '));
		$text = $text."...";
	}
	return $text;
}

function teaser($string,$len=200){
	//Search the end of a word after [, int offset] and set the result as limit
	if($string){
		$limit=strip_tags($string);
		$source_char=strlen(strip_tags($string));
		$maxchar = $len;
		
		if($source_char >= $maxchar){
			$limit = strpos(strip_tags($string)," ",$maxchar);
			
			if($limit){ // if string is larger than [, int offset]
					//Use $limit to replace with ...Text
					return substr_replace(strip_tags($string), "...",$limit);
			}
			else{ //just return text without html tags
				return strip_tags($string);
			}
		}else{
			return strip_tags($string);
		}
	}
}

function reduce_email($string,$len){
	//Search the end of a word after [, int offset] and set the result as limit

	$source_char=strlen($string);
	$maxchar = $len;

	if($source_char>=$maxchar){
		return substr(strip_tags($string),0,$len)."...";
	}else{
		return strip_tags($string);
	}
}

var $padding="...";
var $maxchar=350;
	function generate_teaser($data){

		//GET LENGHT OF SOURCE
		$source=strip_tags($data);
		$source_char=strlen($data);
	
		//CHECK IF THE NUMBER OF CHARACTERS OF THE SOURCE IS LESS OR GREATER
		//THAN THE DEFINED MAXIMUN CHARACTERS
		if ($source_char <= $this->maxchar){
			$maxchar = $source_char;
		}else{
			$maxchar = $this->maxchar;
		}
	
		//(substr) FUNCTION TO TRIM LENGHT TO MAXIMUN NUMBER OF CHARACTERS
		$data=substr($source, 0, $maxchar);
		
		//CHECK FOR BROKEN WORDS
		$wl=substr($source,0, ($maxchar+2));
		$wl=substr($wl,-1);
		if($wl){
			$w=explode(" ",$data);
			$cw=count($w);
			for($i=0;$i<($cw-1);$i++){
				$dataf.=$w[$i]." "; 
			}	
		}
		
		//(str_pad) FUNCTION TO ADD PADDING AT THE END OF STRING AND PRINT OUTPUT
		//str_pad($TrimedSource, $num_of_characters, $padding);
		$num_of_characters=strlen($dataf.$this->padding);
		if($num_of_characters>$maxchar){
			$data=str_pad($dataf, $num_of_characters, $this->padding);
		}
	return $data;
	}

//	HANDLING DATE AND TIME FORMATS
############################################################   
// Formating DATE and TIME from 000-00-00 FORMAT AND TIMESTAMP.

var $timezone=0;
	function format_date($data, $param){
		if($this->numeric($data,0)){
			switch ($param){
				case FULL_TEXT;
					return date("F d, Y", ($data+$this->timezone));
				break;
				case FULL_TEXT_TIME;
					return date("F d, Y H:i A", ($data+$this->timezone));
				break;				
				case SHORT_TEXT;
					return date("M d, y", ($data+$this->timezone));
				break;
				case SHORT_TEXT_TIME;
					return date("M d, y H:i a", ($data+$this->timezone));
				break;
				case NUM;
					return date("Y-m-d", ($data+$this->timezone));
				break;
				case NUM_TIME;
					return date("Y-m-d H:i:s", ($data+$this->timezone));
				break;				
			} 	
		} else {
			$t=explode("-",$data);
			$data=@mktime(0,0,0,$t[1],$t[2],$t[0]);
			switch ($param){
				case FULL_TEXT;
					return date("F d, Y", ($data+$this->timezone));
				break;			
				case FULL_TEXT_TIME;
					return date("F d, Y H:i A", ($data+$this->timezone));
				break;
				case SHORT_TEXT_TIME;
					return date("M d, y H:i a", ($data+$this->timezone));
				break;								
				case SHORT_TEXT;
					return date("M d, y", ($data+$this->timezone));
				break;
				case TIMESTAMP;
					return ($data+$this->timezone);
				break;			
			}
		}
	}

//	FILE HANDLERS
############################################################   

//	Rsize image: returns array of w and h
var $image_size=100;

	function image_resize_wh($file){
		// GET IMAGE PROPERTIES : WIDTH, HEIGHT, TYPE
		list($width_orig, $height_orig, $type) = getimagesize($file) or die ("Error: ".ERROR_FILE_SRC);
	
			// CALCULATE IMAGE DIMENSION
			if ($height_orig >= $this->image_size || $width_orig >= $this->image_size)
			{
				if ($height_orig <= $width_orig)
				{
					$aspect_ratio = $width_orig/$height_orig;
					$new_w = $this->image_size;
					$new_h = abs($new_w / $aspect_ratio);
				}else{
					$aspect_ratio = $height_orig/$width_orig;
					$new_h = $this->image_size;
					$new_w = abs($new_h / $aspect_ratio);
				}
			}else{
				$new_h = $height_orig;
				$new_w = $width_orig;
			}			
		return array("width"=>$new_w, "height"=>$new_h);
	}
	
//	To validate file types and file size. Returns bool
	function file_validate($file,$propteries,$param){
		$p=explode(",",$propteries);
		$f=explode(".",$file);
		//	if resource file
			if(!$f[1]){
				$f=explode(".",$file["name"]);
			}
		$count=count($p);
		switch($param){
			default;
				$file;
			break;
			case TYPE;
				for($i=0; $i<=$count; $i++){
					if ($f[1]==$p[$i]&&$f[1]){
						$type=1;
					}
				}
				if($type){
					return true;
				}else{
					return false;
				}
			break;
			case SIZE;

				if($file["size"]<=(($propteries*1000)+500)){
					return true;
				} else {
					return false;
				};
			break;
		}
	}
	
//	MAIL HANDLERS
############################################################
	function mail_html($to,$nfrom,$efrom,$subject,$message){
	$eheaders = "From: $nfrom <$efrom>\r\n";
	$eheaders .= "Content-Type: text/html; charset=ISO-8859-1";
	$eheaders .= "MIME-Version: 1.0 ";
	
		if(@mail($to,$subject,$message,$eheaders)){
			return true;
		}
	}

	function mail_text($to,$nfrom,$efrom,$subject,$message){
	$headers = "From: $nfrom <$efrom>\r\n";
		if(@mail($to,$subject,$message,$headers)){
			return true;
		}
	}	

	function mail_attachment( $files, $mailto, $from_mail, $from_name, $replyto, $subject, $message) {
	
		$file     = $files['tmp_name'];
		$fileatt_type = $files['type'];
		$filename = $files['name'];
		
		$file_size = filesize($file);
		$handle = fopen($file, "r");
		$content = fread($handle, $file_size);
		fclose($handle);
		$content = chunk_split(base64_encode($content));
		$uid = md5(uniqid(time()));
		$name = basename($file);
		$header = "From: ".$from_name." <".$from_mail.">\r\n";
		$header .= "Reply-To: ".$replyto."\r\n";
		$header .= "MIME-Version: 1.0\r\n";
		$header .= "Content-Type: multipart/mixed; boundary=\"".$uid."\"\r\n\r\n";
		$header .= "This is a multi-part message in MIME format.\r\n";
		$header .= "--".$uid."\r\n";
		$header .= "Content-type:text/html; charset=iso-8859-1\r\n";
		$header .= "Content-Transfer-Encoding: 7bit\r\n\r\n";
		$header .= $message."\r\n\r\n";
		$header .= "--".$uid."\r\n";
		$header .= "Content-Type: application/octet-stream; name=\"".$filename."\"\r\n"; // use different content types here
		$header .= "Content-Transfer-Encoding: base64\r\n";
		$header .= "Content-Disposition: attachment; filename=\"".$filename."\"\r\n\r\n";
		$header .= $content."\r\n\r\n";
		$header .= "--".$uid."--";
		@mail($mailto, $subject, "", $header);
		return true;
	}

	function get_email_super_admin(){
			$query="SELECT SQL_BUFFER_RESULT SQL_CACHE email FROM ".DBPREFIX."users WHERE user_group=1 LIMIT 1";
			$this->globalsql->select($query,0,"QUERY GET SUPER ADMIN");
			$data_num=$this->globalsql->fetch_rows();
			if($data_num>0){
				$data=$this->globalsql->fetch_array();
				return $data["email"];
			}
	}

	####### Email Blast #######
	###########################
	function send_email($to,$subject,$message,$headers){
		//$curl = new CURL;
		//$curl->post(DOMAIN_EMAIL,"to=".$to."&subject=".$subject."&message=".$message."&headers=".$headers);
	}

//  FILE UPLOAD
############################################################
	function upload_file($file_filename,$file_savefilename,$file_destination="../UserFiles/file/"){
			$file_destination = $file_destination.$this->seourl($file_savefilename);
			if (move_uploaded_file($file_filename, $file_destination)){
				return $this->seourl($file_savefilename);
			}
		}
		
//	IMAGE UPLOAD
############################################################
	function resize_upload_image($filename,$savefilename,$size=300,$image_dir=''){
	//GET IMAGE PROPERTIES : WIDTH, HEIGHT, TYPE
		if(!$image_dir){
			$image_dir=USERFILE_PATH."/UserFiles/image/";
		}
		//print $image_dir.$savefilename;
		list($width_orig, $height_orig, $type) = getimagesize($filename);
	
			//OPTIMIZE/RESIZE IMAGE
			if ($height_orig >= $size || $width_orig >= $size)
			{
				if ($height_orig <= $width_orig)
				{
					$aspect_ratio = $width_orig/$height_orig;
					$new_w = $size;
					$new_h = abs($new_w / $aspect_ratio);
				}else{
					$aspect_ratio = $height_orig/$width_orig;
					$new_h = $size;
					$new_w = abs($new_h / $aspect_ratio);
				}
			}else{
				$new_h = $height_orig;
				$new_w = $width_orig;
			}
			
		//RENDER NEW WIDTH AND HEIGHT

		$image_p = imagecreatetruecolor($new_w , $new_h );
	
		//SELECT IMAGE TYPE
		switch ($type){
			case "1";
			//RECREATE IMAGE
			$image = @imagecreatefromgif($filename);
			//Render Image
			@imagecopyresampled($image_p, $image, 0, 0, 0, 0, $new_w, $new_h, $width_orig, $height_orig);
			// Save Image
			@imagegif($image_p, $image_dir.$savefilename, 80);
		break;
	 
		case "2";
			//RECREATE IMAGE
			$image = imagecreatefromjpeg($filename);
			//Render Image
			imagecopyresampled($image_p, $image, 0, 0, 0, 0, $new_w, $new_h, $width_orig, $height_orig);
			// Save Image
			imagejpeg($image_p, $image_dir.$savefilename, 80);
		break;
	
		case "3";
            //RECREATE IMAGE
            $image = @imagecreatefrompng($filename);
            imagealphablending($image, false);
            imagesavealpha($image, true);
            $transparent = imagecolorallocatealpha($image, 255, 255, 255, 127);
            imagefilledrectangle($image, 0, 0, $width, $height, $transparent);
            //Render Image
            @imagecopyresampled($image_p, $image, 0, 0, 0, 0, $new_w, $new_h, $width_orig, $height_orig);
            // Save Image
            @imagepng($image_p, $image_dir.$savefilename);
        break;
		default;
			return $lang["error_invalid_file"];
		break;
		}
	
		//CLEAR TEMP IMAGE
		imagedestroy($image_p);
	}


 function image_resize_upload($file,$savefilename,$height=100,$width=100,$image_dir="") {
	 	ini_set("memory_limit","100M"); 
		if(!$image_dir){$image_dir="../UserFiles/image/";}
		$info = getimagesize($file);
		$image = '';
		
		$final_width = $width;
		$final_height = $height;
		
		list($width_old, $height_old) = $info;
		
		//$ratio_old = $width_old/$height_old;
		
		if($width_old <= $height_old){
			
			$ratio_old = $height_old/$width_old;		
			//if ($width/$height > $ratio_old) {
			//$final_width = $height*$ratio_old;
			//} else {
			$final_height = $width*$ratio_old;
			//}
			
			if($final_height<$height){
				// Adjust Size
				$inc_percentage = (1-($final_height/$height));
			 	$final_height = $final_height +($height * $inc_percentage);
				$final_width = $final_width + ($final_width * $inc_percentage);
			}
		}else{
			$ratio_old = $width_old/$height_old;
			//if ($height/$width > $ratio_old) {
			//$final_height = abs($width)*$ratio_old;
			//} else {
				
			$final_width = $height*$ratio_old;
			
			if($final_width<$width){
				// Adjust Size
				$inc_percentage = (1-($final_width/$width));
			 	$final_width = $final_width +($width * $inc_percentage);
				$final_height = $final_height + ($final_height * $inc_percentage);
			}
			//}
		}
		
		//print $final_width;		
				
		# Loading image to memory according to type
		switch ( $info[2] ) {
		 case IMAGETYPE_GIF: $image = imagecreatefromgif($file); break;
		 case IMAGETYPE_JPEG: $image = imagecreatefromjpeg($file); break;
		 case IMAGETYPE_PNG: $image = imagecreatefrompng($file); break;
		 default: return false;
		}
		
		# This is the resizing/resampling/transparency-preserving magic
		$image_resized = imagecreatetruecolor( $final_width, $final_height );
		if ( ($info[2] == IMAGETYPE_GIF) || ($info[2] == IMAGETYPE_PNG) ) {
		 $transparency = imagecolortransparent($image);
		 if ($transparency >= 0) {
			$transparent_color = imagecolorsforindex($image, $trnprt_indx);
			$transparency = imagecolorallocate($image_resized, $trnprt_color['red'], $trnprt_color['green'], $trnprt_color['blue']);
			imagefill($image_resized, 0, 0, $transparency);
			imagecolortransparent($image_resized, $transparency);
		 }elseif ($info[2] == IMAGETYPE_PNG) {
			imagealphablending($image_resized, false);
			$color = imagecolorallocatealpha($image_resized, 0, 0, 0, 127);
			imagefill($image_resized, 0, 0, $color);
			imagesavealpha($image_resized, true);
		 }
		}

		imagecopyresampled($image_resized, $image, 0, 0, 0, 0, $final_width, $final_height, $width_old, $height_old);
		
		# Writing image according to type to the output destination
		switch ( $info[2] ) {
		 case IMAGETYPE_GIF: imagegif($image_resized, $image_dir.$savefilename); break;
		 case IMAGETYPE_JPEG: imagejpeg($image_resized,$image_dir.$savefilename); break;
		 case IMAGETYPE_PNG: imagepng($image_resized, $image_dir.$savefilename); break;
		 default: return false;
		}
		@imagedestroy($image_resized);
		
	// CROP IMAGE BASE ON 0 XY axis (Top-left).	
		# Loading image to memory according to type
		switch ( $info[2] ) {
		 case IMAGETYPE_GIF: $image = imagecreatefromgif($image_dir.$savefilename); break;
		 case IMAGETYPE_JPEG: $image = imagecreatefromjpeg($image_dir.$savefilename); break;
		 case IMAGETYPE_PNG: $image = imagecreatefrompng($image_dir.$savefilename); break;
		 default: return false;
		}
		$image_resized = imagecreatetruecolor( $width, $height );
		if ( ($info[2] == IMAGETYPE_GIF) || ($info[2] == IMAGETYPE_PNG) ) {
		 $transparency = imagecolortransparent($image);
		 if ($transparency >= 0) {
			$transparent_color = imagecolorsforindex($image, $trnprt_indx);
			$transparency = imagecolorallocate($image_resized, $trnprt_color['red'], $trnprt_color['green'], $trnprt_color['blue']);
			imagefill($image_resized, 0, 0, $transparency);
			imagecolortransparent($image_resized, $transparency);
		 }elseif ($info[2] == IMAGETYPE_PNG) {
			imagealphablending($image_resized, false);
			$color = imagecolorallocatealpha($image_resized, 0, 0, 0, 127);
			imagefill($image_resized, 0, 0, $color);
			imagesavealpha($image_resized, true);
		 }
		}
		imagecopyresampled($image_resized, $image, 0, 0, 0, 0, $width, $height, $width, $height);
		
		# Writing image according to type to the output destination
		switch ( $info[2] ) {
		 case IMAGETYPE_GIF: imagegif($image_resized, $image_dir.$savefilename); break;
		 case IMAGETYPE_JPEG: imagejpeg($image_resized,$image_dir.$savefilename); break;
		 case IMAGETYPE_PNG: imagepng($image_resized, $image_dir.$savefilename); break;
		 default: return false;
		}
		
		return true;
		@imagedestroy($image_resized);
		
	 }

	function image_crop_uploaded($file,$savefilename,$x=0,$y=0,$height=100,$width=100,$image_dir="") {
	 	ini_set("memory_limit","100M"); 
		if(!$image_dir){$image_dir="../UserFiles/image/";}
		$info = getimagesize($file);
		$image = '';
				
		# Loading image to memory according to type
		switch ( $info[2] ) {
		 case IMAGETYPE_GIF: $image = imagecreatefromgif($file); break;
		 case IMAGETYPE_JPEG: $image = imagecreatefromjpeg($file); break;
		 case IMAGETYPE_PNG: $image = imagecreatefrompng($file); break;
		 default: return false;
		}
		
		# This is the resizing/resampling/transparency-preserving magic
		$image_resized = imagecreatetruecolor( $width, $height );
		if ( ($info[2] == IMAGETYPE_GIF) || ($info[2] == IMAGETYPE_PNG) ) {
		 $transparency = imagecolortransparent($image);
		 if ($transparency >= 0) {
			$transparent_color = imagecolorsforindex($image, $trnprt_indx);
			$transparency = imagecolorallocate($image_resized, $trnprt_color['red'], $trnprt_color['green'], $trnprt_color['blue']);
			imagefill($image_resized, 0, 0, $transparency);
			imagecolortransparent($image_resized, $transparency);
		 }elseif ($info[2] == IMAGETYPE_PNG) {
			imagealphablending($image_resized, false);
			$color = imagecolorallocatealpha($image_resized, 0, 0, 0, 127);
			imagefill($image_resized, 0, 0, $color);
			imagesavealpha($image_resized, true);
		 }
		}
		
		// CROP IMAGE BASE ON 0 XY axis (Top-left).	
		
		imagecopyresampled($image_resized, $image, 0, 0, $x, $y, $width, $height, $width, $height);
		
		# Writing image according to type to the output destination
		switch ( $info[2] ) {
		 case IMAGETYPE_GIF: imagegif($image_resized, $image_dir.$savefilename); break;
		 case IMAGETYPE_JPEG: imagejpeg($image_resized,$image_dir.$savefilename); break;
		 case IMAGETYPE_PNG: imagepng($image_resized, $image_dir.$savefilename); break;
		 default: return false;
		}
		
		return true;
		@imagedestroy($image_resized);
		
		
	 }	 
	 
	 function resize_image_blob($file,$tempfilename,$height=100,$width=100) {
	 	ini_set("memory_limit","100M"); 
		// Temporary Image folder Path
		if(!$image_dir){$image_dir="../UserFiles/temp/";}
		$info = getimagesize($file);
		$image = '';
		
		$final_width = $width;
		$final_height = $height;
		
		list($width_old, $height_old) = $info;
		
		
		$ratio_old = $width_old/$height_old;
		
		if ($width/$height > $ratio_old) {
		   $final_width = $height*$ratio_old;
		} else {
		   $final_height = $width/$ratio_old;
		}
		
				
		# Loading image to memory according to type
		switch ( $info[2] ) {
		 case IMAGETYPE_GIF: $image = imagecreatefromgif($file); break;
		 case IMAGETYPE_JPEG: $image = imagecreatefromjpeg($file); break;
		 case IMAGETYPE_PNG: $image = imagecreatefrompng($file); break;
		 default: return false;
		}
		
		# This is the resizing/resampling/transparency-preserving magic
		$image_resized = imagecreatetruecolor( $final_width, $final_height );
		if ( ($info[2] == IMAGETYPE_GIF) || ($info[2] == IMAGETYPE_PNG) ) {
		 $transparency = imagecolortransparent($image);
		 if ($transparency >= 0) {
			$transparent_color = imagecolorsforindex($image, $trnprt_indx);
			$transparency = imagecolorallocate($image_resized, $trnprt_color['red'], $trnprt_color['green'], $trnprt_color['blue']);
			imagefill($image_resized, 0, 0, $transparency);
			imagecolortransparent($image_resized, $transparency);
		 }elseif ($info[2] == IMAGETYPE_PNG) {
			imagealphablending($image_resized, false);
			$color = imagecolorallocatealpha($image_resized, 0, 0, 0, 127);
			imagefill($image_resized, 0, 0, $color);
			imagesavealpha($image_resized, true);
		 }
		}
		imagecopyresampled($image_resized, $image, 0, 0, 0, 0, $final_width, $final_height, $width_old, $height_old);
		
		# Writing image according to type to the output destination
		switch ( $info[2] ) {
		 case IMAGETYPE_GIF: imagegif($image_resized, $image_dir.$tempfilename); break;
		 case IMAGETYPE_JPEG: imagejpeg($image_resized,$image_dir.$tempfilename); break;
		 case IMAGETYPE_PNG: imagepng($image_resized, $image_dir.$tempfilename); break;
		 default: return false;
		}
		
		// NOW LETS GET THE RESIZED IMAGE AND SAZE IT IN DATABASE AS BLOB
		$instr = fopen($image_dir.$tempfilename,"rb");
		$image = addslashes(fread($instr,filesize($image_dir.$tempfilename)));
		// Clean Temporary Folder
		@unlink($image_dir.$tempfilename);
		
		return $image;
		@imagedestroy($image_resized);
	 }
	 
	 
	 
	 
//	IMAGE RESIZE
	function image_resize($avatar,$size=100,$dir="UserFiles/image/"){
		$filename=$dir.$avatar;
	// GET IMAGE PROPERTIES : WIDTH, HEIGHT, TYPE
	if(!@file($filename)){
		return $lang["error_404"];
	} else {
		@list($width_orig, $height_orig, $type) = @getimagesize($filename);
	
			// CALCULATE IMAGE DIMENSION
			if ($height_orig >= $size || $width_orig >= $size)
			{
				if ($height_orig <= $width_orig)
				{
					$aspect_ratio = $width_orig/$height_orig;
					$new_w = $size;
					$new_h = abs($new_w / $aspect_ratio);
				}else{
					$aspect_ratio = $height_orig/$width_orig;
					$new_h = $size;
					$new_w = abs($new_h / $aspect_ratio);
				}
			}else{
				$new_h = $height_orig;
				$new_w = $width_orig;
			}
			
			
			 return array("h" => $new_h, "w" => $new_w);
		} 
	}

//FUNCTION FOR DYNAMIC LINK OF SUCCEDING PAGES : PAGE NAVIGATOR
	var $sql;
	var $class_link="f2";
	var $class_font="f2";
	var $set="";
	function start_page($max_num){
		return (abs($_GET["p"])*$max_num);
	}



	function pagination($max_row,$max_num=10){
		//Get total pages
		$this->globalsql->select($this->sql,0,"PAGINATION");
		$total=$this->globalsql->fetch_rows();
		$total_pages=($total/$max_row);
		$total_pages = ceil($total_pages); 
		
		//get current page
		$page=$this->numeric(abs($_GET["p".$this->set]));
		$batch=$this->numeric(abs($_GET["b".$this->set]));

		//URL handling
		$url=str_replace("//","/",$_SERVER['REQUEST_URI']);
		ereg("&p".$this->set."=+[0-9]*",$url,$up);
		ereg("\?p".$this->set."=+[0-9]*",$url,$ub);
		$url=str_replace($up,"",$url);	
		$url=str_replace($ub,"",$url);
		
		
		//previous
		if(	ereg("\?",$url)	){
			$url_prev = $url."&p=".(abs($page)-1);
		}else{
			$url_prev = $url."?p=".(abs($page)-1);
		}
		
		if ($page > 0) {	
			$paging.= '<a href="http://'.DOMAIN_ROOT.$url_prev.'" >&laquo; Previous</a>&nbsp; ';
		}else{
			$paging.= '<a>&laquo; Previous</>&nbsp; ';
		}
			

		//numbering
		if(	ereg("\?",$url)	){
			$url_num = $url."&p".$this->set."=";
		}else{
			$url_num = $url."?p".$this->set."=";
		}
				
		if($page>=round($max_num/2)){
			if($total_pages>$max_num){
				$n=($page-round($max_num/2));
			}
		}
		if($total_pages<=($page+round($max_num/2))){
			if($total_pages>$max_num){
				$n=($total_pages-$max_num);
			}
		}
		for ($i=1; $i<=$max_num; $i++) {
			$n++;
			if($n<=$total_pages){
				if ($page==($n-1)){
					$paging.=' <a>'.$n.'</a>&nbsp;';
				}else{
					$paging.='<a href="http://'.DOMAIN_ROOT.$url_num.($n-1).'" >'.$n.'</a>&nbsp;';
				}	
			}	
		}
		
		
		//next
		if(ereg("\?",$url)){
			$url_next = $url."&p".$this->set."=".(abs($page)+1);
		} else {
			$url_next = $url."?p".$this->set."=".(abs($page)+1);
		}		
		if ($page < ($total_pages-1)) {
			$paging.= ' <a href="http://'.DOMAIN_ROOT.$url_next.'" >Next &raquo;</a>&nbsp;';
		}else{
			$paging.= ' <a>Next &raquo;</a>';
		}
		// total pages		
		//return $paging.='<span class="'.$this->class_font.'">&nbsp;of&nbsp;'.$total_pages.'</span> ';
		return $paging;
	}


	function loop_pages($max_row,$max_num=10){
		//Get total pages
		$this->globalsql->select($this->sql,0,"PAGINATION");
		$total=$this->globalsql->fetch_rows();
		$total_pages=($total/$max_row);
		$total_pages = ceil($total_pages);
		
		//get current page
		$page=$this->numeric(abs($_GET["p".$this->set]));
		$batch=$this->numeric(abs($_GET["b".$this->set]));

		//URL handling
		if(MODREWRITE&&!ereg("/admin/",$_SERVER['PHP_SELF'])){
			$url =$_SERVER['REQUEST_URI'];
			ereg("/p[0-9]*/",$url,$up);
			ereg("/b[0-9]*/",$url,$ub);
			$url=str_replace($up,"/p#/",$url);	
			$url=str_replace($ub,"/p#/",$url);
			$url=str_replace("LP/".$up,"LP/p#/",str_replace("LS/".$up,"LS/p#/",str_replace("LM/".$up,"LM/p#/",$url)));
		} else {
			$url=str_replace("//","/",$_SERVER['REQUEST_URI']);
			ereg("&p".$this->set."=+[0-9]*",$url,$up);
			ereg("&b".$this->set."=+[0-9]*",$url,$ub);
			$url=str_replace($up,"",$url);	
			$url=str_replace($ub,"",$url);
		}

		//previous
		if(MODREWRITE&&!ereg("/admin/",$_SERVER['PHP_SELF'])){
			$url_prev = str_replace("/p#","/p".(abs($page)-1),$url);
		} else {
			$url_prev = $url."&p=".(abs($page)-1);
		}
		if ($page > 0) {	
			$paging.= '<a href="http://'.DOMAIN_ROOT.$url_prev.'" class="'.$this->class_link.'" >Prev</a>&nbsp;|&nbsp;';
		}else{
			$paging.= '<span class="'.$this->class_font.'">Prev</span>&nbsp;|';
		}
			

		//numbering
		$url_num = $url."&p".$this->set."=";
		if($page>=round($max_num/2)){
			if($total_pages>$max_num){
				$n=($page-round($max_num/2));
			}
		}
		if($total_pages<=($page+round($max_num/2))){
			if($total_pages>$max_num){
				$n=($total_pages-$max_num);
			}
		}
		for ($i=1; $i<=$max_num; $i++) {
			$n++;
			if($n<=$total_pages){
				if ($page==($n-1)){
					$paging.=' <span class="'.$this->class_font.'">'.$n.'&nbsp;</span>';
				}else{
					if(MODREWRITE&&!ereg("/admin/",$_SERVER['PHP_SELF'])){
						$paging.='<a href="http://'.DOMAIN_ROOT.str_replace("p#","p".($n-1),$url).'" class="'.$this->class_link.'">'.$n.'</a> ';
					} else {
						$paging.='<a href="http://'.DOMAIN_ROOT.$url_num.($n-1).'" class="'.$this->class_link.'">'.$n.'</a> ';
					}
				}	
			}	
		}

		//next
		if(MODREWRITE&&!ereg("/admin/",$_SERVER['PHP_SELF'])){
			$url_next = str_replace("/p#","/p".(abs($page)+1),$url);
		} else {
			$url_next = $url."&p".$this->set."=".(abs($page)+1);
		}		
		if ($page < ($total_pages-1)) {
			$paging.= '|&nbsp;<a href="http://'.DOMAIN_ROOT.$url_next.'" class="'.$this->class_link.'" >Next</a>';
		}else{
			$paging.= '|&nbsp;<span class="'.$this->class_font.'">Next</span>';
		}
		// total pages		
	return $paging.='<span class="'.$this->class_font.'">&nbsp;of&nbsp;'.$total_pages.'</span> ';
	}
	
// Pagination Array

	function pagination_array($max_row,$max_num=10){
		//Get total pages
		$this->globalsql->select($this->sql,0,"PAGINATION");
		$total=$this->globalsql->fetch_rows();
		$total_pages=($total/$max_row);
		$total_pages = ceil($total_pages);
		
		$array_parigation = array();
		
		//get current page
		$page=$this->numeric(abs($_GET["p".$this->set]));
		// Previous 
		if($total_pages){
			if((abs($page)-1)<1){
				$array_parigation["previous"] = 1;
			}else{
				$array_parigation["previous"] = (abs($page)-1);
			}
		}
		
		// Page Numbers		
		if($page>=round($max_num/2)){
			if($total_pages>$max_num){
				$n=($page-round($max_num/2));
			}
		}
		if($total_pages<=($page+round($max_num/2))){
			if($total_pages>$max_num){
				$n=($total_pages-$max_num);
			}
		}
		for ($i=1; $i<=$max_num; $i++) {
			$n++;
			if($n<=$total_pages){
				
				$array_parigation[$n] = abs($n);
			}	
		}
		
		// Next
		if($total_pages){
			if($total_pages <= (abs($page)+1) ){
				$array_parigation["next"] = $total_pages;	
			}else{
				$array_parigation["next"] = (abs($page)+1);
			}
		}
		// Output
		return $array_parigation;
	}

//	Page permissions
	var $p_group;
	var $permission_type=false;
	function set_permission($mode="view",$root="",$folder=""){
		if(!$root){
		$mpath=explode(".",$this->alphanumeric($_GET["LM"].$_GET["LP"].$_GET["LS"]));
			if(!$folder){
				$folder=$mpath[0];
			}
		}
		// Multiple Folders
		$fldrs = explode(",",$folder);
		$folders = "";
		$or = "";
		$multi = false;
		if(count($fldrs)>1){
			foreach($fldrs as $k => $f){
				$folders .= $or." folder='$f' ";	
				$or = "OR";
			}
			$multi=true;
		}else{
			$folders = "folder='$folder'";	
		}
		
		if($this->permission_type){
			//$where=" AND type='".$this->permission_type."'";
		}
		$query="SELECT SQL_BUFFER_RESULT SQL_CACHE id,padd,pedit,pdelete,pview,papprove,folder FROM ".DBPREFIX."sections WHERE $folders AND type!='links' AND type!='staticpages'";// $where";
		$this->globalsql->select($query,0,"QUERY PERMISSIONS");
		
		$multi_out = "";
		while($data=$this->globalsql->fetch_array()){
			if($data["id"]){
				//	Get group permissions
				$permission = "";
				switch ($mode){
					case "edit";
						//	EDIT PAGE
						$perid=explode(",",$data["pedit"]);
						$permission = false;
						foreach($perid as $key => $val){
							if($val!=""&&$val==$this->p_group){
								$permission=true;
							}
						}
						if($multi){
							$multi_out[$data["folder"]]=$permission;
						}else{
							return $permission;
						}
					break;
					case "add";			
						//	DELETE PAGE
						
						$perid=explode(",",$data["padd"]);
						$permission = false;
						foreach($perid as $key => $val){
							if($val!=""&&$val==$this->p_group){
								$permission=true;
							}
						}
						if($multi){
							$multi_out[$data["folder"]]=$permission;
						}else{
							return $permission;
						}
					break;
					case "view";
			
						//	VIEW PAGE
						$perid=explode(",",$data["pview"]);
						$permission = false;
						foreach($perid as $key => $val){
							if($val!=""&&$val==$this->p_group){
								$permission=true;
							}
						}
						if($multi){
							$multi_out[$data["folder"]]=$permission;
						}else{
							return $permission;
						}
					break;
					case "delete";			
						//	VIEW PAGE
						$perid=explode(",",$data["pdelete"]);
						$permission = false;
						foreach($perid as $key => $val){
							if($val!=""&&$val==$this->p_group){
								$permission=true;
							}
						}
						if($multi){
							$multi_out[$data["folder"]]=$permission;
						}else{
							return $permission;
						}
					break;				
					case "approve";			
						//	VIEW PAGE
						$perid=explode(",",$data["papprove"]);
						$permission = false;
						foreach($perid as $key => $val){
							if($val!=""&&$val==$this->p_group){
								$permission=true;
							}
						}
						if($multi){
							$multi_out[$data["folder"]]=$permission;
						}else{
							return $permission;
						}
					break;						
				}
			}
		}
		
		if($multi){
			return $multi_out;
		}
		 
	}	
	
	function set_permission_section($folder){
		$query="SELECT SQL_BUFFER_RESULT SQL_CACHE id,pview FROM ".DBPREFIX."sections WHERE folder='".$folder."'";
		$this->globalsql->select($query,0,"QUERY PERMISSION SECTIONS");
		$data=$this->globalsql->fetch_array();
		if($data["id"]){
			//	Get group permissions		
			//	VIEW SECTION
			$perid=explode(",",$data["pview"]);
			foreach($perid as $key => $val){
				if($val&&$val==$this->p_group){
					return true;
				}
			}
		} 
	}
	
	function image_crop($html){
		preg_match_all("/\< *img[^\>]*src *= *[\"\']{0,1}([^\"\'\ >]*)/i",$html,$images);
		if(count($images)>0){
		 return $images[1][0];
		}
	}

	function is_duplicate($query){
		if ($query){
			$this->globalsql->select($query,0,"CHECK FOR DUPLICATE RECORD");
			$count	= $this->globalsql->fetch_rows();
			if ($count>=1){return true;}
		}
	}

	//	Drop down navigator
	var $dropdown_template="";
	function dropdown_nav($parentid,$linkgroup){
		$query="SELECT SQL_BUFFER_RESULT SQL_CACHE id,title,folder,button,url,type,parent_link FROM ".DBPREFIX."sections WHERE type!='plugins' AND link_group='".$linkgroup."' AND type!='systems' AND parent_link='".$parentid."' AND status=1 ORDER BY sequence ASC";
		$this->globalsql->select($query,0,"DROP DOWN NAV");
		$data_num=$this->globalsql->fetch_rows();
			for($i=0;$i<$data_num;$i++){
			$data=$this->globalsql->fetch_array();

				if($data["folder"]&&!$data["url"]){

					if($data["type"]=="staticpages"){
						$link="system.php?LM=staticpages&id=".$data["id"];
					} else {
						$link="system.php?LM=".$data["folder"];
					}
				} else {
					if(ereg("http",$data["url"])||ereg("HTTP",$data["url"])){
						$link=$data["url"];
					} else {
						$link=$data["url"];
					}
				}

				if($data["id"]){
					$raw_link=$this->dropdown_nav($data["id"],$linkgroup);
					$link_array[$i]=array(
						"link_title" => $data["button"],
						"link" => $link,
						"id" => $data["id"],
						"sub_link" => $raw_link,
					);
				}
			}
			return $link_array;
	}

	function is_child($parentid,$linkgroup){
		$query="SELECT SQL_BUFFER_RESULT SQL_CACHE id FROM ".DBPREFIX."sections WHERE type!='plugins' AND link_group='".$linkgroup."' AND type!='systems' AND parent_link='".$parentid."' AND status=1 ORDER BY sequence ASC";
		$this->globalsql->select($query,0,"NAVIGATOR CHILD");
		$data_num=$this->globalsql->fetch_rows();
		if($data_num>0){
			return true;
		}
	}

	function getErrUrl(){
		header("Location: error.php");
		exit;
	}
	
	function seourl($text){
		$text = preg_replace('/([^0-9a-zA-Z \- \.\/]*)/i','',$this->reduce_text($text,100));
		return strtolower(str_replace(" ","-",$text));
	}


	function params(&$params){

		global $sql;

		

		// REMOVE WWW
		$hhtp_host = str_replace('www.','',strtolower($this->anti_xss_high($_SERVER['HTTP_HOST'])));
		$abs_path = str_replace('www.','',strtolower($this->anti_xss_high(ABSOLUTE_PATH)));

		

	 	$root = str_replace($hhtp_host,'',str_replace('http://','',$abs_path));

		if($root && $root !="/"){
			if(MODREWRITE){
				$uri = str_replace( $root,'', str_replace('index.php/','',strtolower($this->anti_xss_high($_SERVER['REQUEST_URI']))) );
			}else{
				$uri = str_replace( $root,'', $this->anti_xss_high($_SERVER['REQUEST_URI']) );
			}
			
		} else {

			// REMOVE INDEX.PHP
			if(MODREWRITE){
				$uri = str_replace('/index.php/','',$this->anti_xss_high($_SERVER['REQUEST_URI']));
			}else{
				$uri = $this->anti_xss_high($_SERVER['REQUEST_URI']);
			}

			// IF NO INDEX.PHP
		 	$uri . "-".$this->anti_xss_high($_SERVER['REQUEST_URI']);
		
			if($uri==$this->anti_xss_high($_SERVER['REQUEST_URI'])){
				$uri = $this->anti_xss_high($_SERVER['REQUEST_URI']);
				// REMOVE THE first /
				$uri=substr($uri, 1);
			}

		}

		$uri = preg_replace("/.html.*/i",'',$uri);
		$uri = explode("/",str_replace('//','/',$uri));


		

		// GET ALIAS

		foreach($uri as $key => $val){

			if( $key < (count($uri)-1) || !$val){

				$chunk = explode("-",$val);

				if(isset($chunk[1])){

					$params[$chunk[0]]= $chunk[1];

				}else{

					if(is_numeric($chunk[0])){

						$params["id"]= $chunk[0];

					}

				}

			}else{

				$params["alias"]= $val;	

			}

		}

		

		// GET FOLDER, FILE FROM URL
		//print_r($uri);
		if($uri[0]){

			$this->folder($params,$uri,$params["alias"]);

		//print_r($params);
		} else {

			$query = "SELECT l.id,l.plugins,l.custom,t.folder as template,l.refid FROM ".DBPREFIX."links as l, ".DBPREFIX."templates as t WHERE l.folder='index' AND l.aliasurl='' AND l.seourl='' AND l.plugins != '' AND t.id = l.template";
			$sql->query($query);
			if($plugins = $sql->fetch_array()){
				$params["plugins"] = $plugins["plugins"];
				$params["custom_plugins"] = $plugins["custom"];	
				$params["template"] = $plugins["template"];
				$params["lid"] = $plugins["id"];
				$params["id"] = $plugins["refid"];	
			}

			$params["folder"] = 'index';
			$params["file"] = 'index';	

		}

		//print_r($params);
		//exit();

		return $params;

		

	}

	
	// GET FOLDER AND FILE
	function folder(&$params,$uri,$alias){
		global $sql;
	//print_r($uri); 
	
		$params["path"]=$uri;
		// TRY MODULE
		$muri = $uri;
		if(preg_match('/-/',$muri[0])){
				$muri = explode('-',$muri[0]);
		} else {
				$muri[1] = 'index';	
		}
		// If module
		if(is_file('modules/'.$muri[0].'/'.$muri[1].'.php') ){
				$params["folder"]=$muri[0]; 
				$params["file"] = $muri[1];
				$params["page_level"] = count($uri);
				
				// TRY LINKS FOR INDEX
				/*if($alias){
					$query = "SELECT sid as mid  FROM ".DBPREFIX."modules WHERE folder='".$muri[0]."'";
					$sql->query($query);
					$module =  $sql->fetch_array();
					$params["mid"] = $module["mid"];
						
					$query = "SELECT l.id,l.plugins,l.custom,t.folder as template, l.folder, l.refid  FROM ".DBPREFIX."links  as l, ".DBPREFIX."templates as t WHERE l.refid='".$module["mid"]."' AND l.type='module' AND t.id = l.template ORDER BY l.plugins DESC";
					$sql->query($query);
					
					if( $index =  $sql->fetch_array() ){
						$params["folder"] = $index['folder'];
						if(!$params["file"]){
							$params["file"] = 'index';
						}
						if($index["folder"]=='article' || !$params["id"]){
							$params["id"]=$index["refid"];	
						}
						$params["plugins"] = $index["plugins"];
						$params["custom_plugins"] = $index["custom"];
						$params["template"] = $index["template"];
						$params["lid"] = $index["id"];	
					}
				}*/
		}else{
			
			// GET CATEGORY ONLY
			if($uri[0]&&!isset($uri[1])){
				$query = "SELECT id,category FROM ".DBPREFIX."category WHERE LOWER(alias)='".$uri[0]."'";
				$sql->query($query);
				if(  $category = $sql->fetch_array() ){
					$params["folder"] = 'category';
					$params["file"] = 'index';
					$params["cid"] = $category["id"];
					$params["cname"] = $category["category"];
				}
			}
	
			// TRY SECTION + CATEGORY
			/*if($uri[0]){
				$query = "SELECT s.id FROM ".DBPREFIX."section as s, ".DBPREFIX."category WHERE s.id=c.section 
						AND ( LOWER(s.title) = '".$uri[0]."' AND LOWER(c.category) = '".$uri[1]."')";
				$sql->query($query);
				if( $category = $sql->fetch_rows() ){
					$params["folder"] = 'category';
					$params["file"] = 'index';
				}
			}*/
			
			// TRY SECTION + CATEGORY + ARTICLE
			$uri_arr_last = count($uri)-1;
			if($uri[0]&&$uri[$uri_arr_last]){
				if(preg_match("/^[0-9]*-/i",$uri[$uri_arr_last])){
					$id = explode('-',$uri[$uri_arr_last]);
					$id = $id[0];
						
					// query article based on ID
					$wherearticle = " a.id = '$id'";
					$params["id"] = $id;
				} elseif( is_numeric($uri[$uri_arr_last]) ) {
					// query article based on ID
					$wherearticle = " a.id = '".$uri[$uri_arr_last]."' ";
				} else {
					$wherearticle = " a.alias = '".$uri[$uri_arr_last]."' ";
				}
					
				$query = "SELECT a.id, a.catid, a.section as secid,s.title as sname FROM  ".DBPREFIX."section as s, ".DBPREFIX."content as a WHERE a.section=s.id 
						AND LOWER(s.title) = '".$uri[0]."'  
						AND $wherearticle ";
				$sql->query($query);
				if( $article = $sql->fetch_array() ){
					//print_r($article);
					$params["folder"] = 'article';
					$params["file"] = 'index';
					$params["cid"] = $article["catid"];
					$params["cname"] = $article["cname"];
					$params["sname"] = $article["sname"];
					$params["sid"] = $article["secid"];
					$params["id"] = $article["id"];
				}	
			}
			
			// TRY LINKS FOR INDEX
			if($alias){
				$query = "SELECT l.id,l.plugins,l.custom,t.folder as template, l.folder, l.refid  FROM ".DBPREFIX."links  as l, ".DBPREFIX."templates as t WHERE l.aliasurl='".$alias."' AND t.id = l.template ORDER BY l.plugins DESC";
				
				$sql->query($query);
				if( $index =  $sql->fetch_array() ){
					$params["folder"] = $index['folder'];
					$params["file"] = 'index';
					if($index["folder"]=='article' || !$params["id"]){
						$params["id"]=$index["refid"];	
					}
					$params["plugins"] = $index["plugins"];
					$params["custom_plugins"] = $index["custom"];
					$params["template"] = $index["template"];
					$params["lid"] = $index["id"];	
				}
			}
		}
		
		// IF NONE THROW 404	
		if(!isset($params['folder'])){
			$params['folder'] = 'notfound';
		}
		if(!isset($params["file"])){
			$params["file"] = 'index';	
		}
		
		if(!isset($params["plugins"]) && !$alias ){
			$query = "SELECT l.id,l.plugins,l.custom,t.folder as template FROM ".DBPREFIX."links as l, ".DBPREFIX."templates as t WHERE l.seourl='".implode("/",$uri).".html"."' AND l.plugins != '' AND t.id = l.template";
			
			$sql->query($query);
			if($plugins = $sql->fetch_array()){
				$params["plugins"] = $plugins["plugins"];
				$params["custom_plugins"] = $plugins["custom"];
				$params["template"] = $plugins["template"];	
				$params["lid"] = $plugins["id"];	
			}
		}
	}
	
	
	
	function getsublinks($id,$sub=""){
		global $sql;
		$query="SELECT l.id, l.parent_link as parent, l.type, l.button, l.seourl, l.aliasurl FROM ".DBPREFIX."links as l, ".DBPREFIX."nav as n WHERE n.id=l.navid AND l.parent_link = '".$id."' AND l.status='1' AND n.status='1' ORDER BY n.id, l.sequence";
		//$query="SELECT l.id, l.parent_link as parent, l.type, l.button, l.seourl, l.aliasurl FROM ".DBPREFIX."links as l, ".DBPREFIX."nav as n WHERE n.id=l.navid AND l.parent_link LIKE '%|".$id."' ORDER BY n.id, l.sequence";
		$sql->select($query,$query);
		$l=1;
		
		$nav="";
		
		while($navdata = $sql->fetch_array($query)){
			$subpage=false;
			$pages[$navdata["button"]] = $navdata["id"];
			
			
			
			
			if(preg_match("/http:\/\//i",$navdata["seourl"])){
				$nav .= '<li class="link-li-'.$sub.'-'.$l.'"><a href="'.$navdata["seourl"].'">'.$navdata["button"].'</a>'."\n";
			}else{
				$nav .= '<li class="link-li-'.$sub.'-'.$l.'"><a href="'.ABSOLUTE_PATH.$navdata["seourl"].'">'.$navdata["button"].'</a>'."\n";
			}
				
			
			$subpage = $this->getsublinks($navdata["id"],$sub.'-'.$l);
			if($subpage){
				$nav .= '<ul class="link-sub-ul-'.$sub.'">'.$subpage.'</ul>'."\n";
			}
			$nav .= '</li>';
			
			$l++;
		}
		
		return $nav;
	}

	function xml_to_array( $file )
	{
		$parser = xml_parser_create();
/*		xml_parser_set_option( $parser, XML_OPTION_CASE_FOLDING, 0 );
		xml_parser_set_option( $parser, XML_OPTION_SKIP_WHITE, 1 );*/
		xml_parse_into_struct( $parser, $this->file_get_content($file), $tags );
		xml_parser_free( $parser );
	   
		$elements = array();
		$stack = array();
		foreach ( $tags as $tag )
		{
			$index = count( $elements );
			if ( $tag['type'] == "complete" || $tag['type'] == "open" )
			{
				$elements[$index] = array();
				$elements[$index]['NAME'] = $tag['tag'];
				$elements[$index]['ATTRIBUTES'] = $tag['attributes'];
				$elements[$index]['CONTENT'] = $tag['value'];
			   
				if ( $tag['type'] == "open" )
				{    # push
					$elements[$index]['CHILDREN'] = array();
					$stack[count($stack)] = &$elements;
					$elements = &$elements[$index]['CHILDREN'];
				}
			}
		   
			if ( $tag['type'] == "close" )
			{    # pop
				$elements = &$stack[count($stack) - 1];
				unset($stack[count($stack) - 1]);
			}
		}
		return $elements[0];
	}
	
	function file_get_content($file){
		$handle = @fopen($file, "r");
		$buffer="";
		if ($handle) {
			while (!feof($handle)) {
				if(!isset($buffer)){
					$buffer="";
				}
				$buffer .= fgets($handle, 4096);
				
			}
			fclose($handle);
		}
		return $buffer;
	}
    
    function delete_directory($dirname) {
        if (is_dir($dirname))
            $dir_handle = opendir($dirname);
        if (!$dir_handle)
            return false;
        while($file = readdir($dir_handle)) {
            if ($file != "." && $file != "..") {
                if (!is_dir($dirname."/".$file))
                    unlink($dirname."/".$file);
                else
                    $this->delete_directory($dirname.'/'.$file);    
            }
        }
        closedir($dir_handle);
        rmdir($dirname);
        return true;
    }
	
	function get_parent_link($id){
		global $sql;
		$select = "SELECT aliasurl, button,title FROM ".DBPREFIX."links WHERE id='$id'";
		$sql->select($select);
		return  $sql->fetch_array();
	}
	
	// New Added
	function get_FullName($id){
		global $sql;
		$select = "SELECT uid, fname, lname FROM ".DBPREFIX."profile WHERE uid = '$id' ";
		$sql->query($select);
		$data = $sql->fetch_array();
		if($data["uid"]){
			if($data["uid"] == '1'){
				return 'Administrator';
			}else{
				return $data["fname"]." ".$data["lname"];
			}
		}else{
			return 'UNKNOWN';
		}
	}
	
	function get_FirstName($id){
		global $sql;
		$select = "SELECT uid, fname FROM ".DBPREFIX."profile WHERE uid = '$id' ";
		$sql->query($select);
		$data = $sql->fetch_array();
		if($data["uid"]){
			if($data["uid"] == '1'){
				return 'Administrator';
			}else{
				return $data["fname"];
			}
		}else{
			return 'UNKNOWN';
		}
	}
	
	function CleanWordHTML($html){
	//alert(html);
		$html = str_replace('/<o:p>\s*<\/o:p>/g', '', $html) ;
		$html = str_replace('/<o:p>[\s\S]*?<\/o:p>/g', '&nbsp;', $html) ;
	 
		// Remove mso-xxx styles.
		$html = str_replace(' /\s*mso-[^:]+:[^;"]+;?/gi', '', $html ) ;
	 
		// Remove margin styles.
		$html = str_replace(' /\s*MARGIN: 0cm 0cm 0pt\s*;/gi', '', $html ) ;
		$html = str_replace(' /\s*MARGIN: 0cm 0cm 0pt\s*"/gi', "\"", $html ) ;	 
		$html = str_replace(' /\s*TEXT-INDENT: 0cm\s*;/gi', '', $html ) ;
		$html = str_replace(' /\s*TEXT-INDENT: 0cm\s*"/gi', "\"", $html ) ; 
		$html = str_replace(' /\s*TEXT-ALIGN: [^\s;]+;?"/gi', "\"", $html ) ;
		$html = str_replace(' /\s*PAGE-BREAK-BEFORE: [^\s;]+;?"/gi', "\"", $html ) ;
		$html = str_replace(' /\s*FONT-VARIANT: [^\s;]+;?"/gi', "\"", $html ) ;	 
		$html = str_replace(' /\s*tab-stops:[^;"]*;?/gi', '', $html ) ;
		$html = str_replace(' /\s*tab-stops:[^"]*/gi', '', $html ) ;
			 
		// Remove FONT face attributes.
		$html = str_replace(' /\s*face="[^"]*"/gi', '', $html ) ;
		$html = str_replace(' /\s*face=[^ >]*/gi', '', $html ) ;	 
		$html = str_replace(' /\s*FONT-FAMILY:[^;"]*;?/gi', '', $html ) ;

		// Remove Class attributes
		$html = str_replace('/<(\w[^>]*) class=([^ |>]*)([^>]*)/gi', "<$1$3", $html) ;
	 
		// Remove style, meta and link tags
		$html = str_replace(' /<STYLE[^>]*>[\s\S]*?<\/STYLE[^>]*>/gi', '', $html ) ;
		$html = str_replace(' /<(?:META|LINK)[^>]*>\s*/gi', '', $html ) ;
	 
		// Remove empty styles.
		$html =  str_replace(' /\s*style="\s*"/gi', '', $html ) ;	
		$html = str_replace(' /<SPAN\s*[^>]*>\s*&nbsp;\s*<\/SPAN>/gi', '&nbsp;', $html ) ;	 
		$html = str_replace(' /<SPAN\s*[^>]*><\/SPAN>/gi', '', $html ) ;
	 
		// Remove Lang attributes
		$html = str_replace('/<(\w[^>]*) lang=([^ |>]*)([^>]*)/gi', "<$1$3", $html) ;	 
		$html = str_replace(' /<SPAN\s*>([\s\S]*?)<\/SPAN>/gi', '$1', $html ) ;	 
		$html = str_replace(' /<FONT\s*>([\s\S]*?)<\/FONT>/gi', '$1', $html ) ;
	 
		// Remove XML elements and declarations
		$html = str_replace('/<\\?\?xml[^>]*>/gi', '', $html ) ;
	 
		// Remove w: tags with contents.
		$html = str_replace(' /<w:[^>]*>[\s\S]*?<\/w:[^>]*>/gi', '', $html ) ;
	 
		// Remove Tags with XML namespace declarations: <o:p><\/o:p>
		$html = str_replace('/<\/?\w+:[^>]*>/gi', '', $html ) ;
	 
		// Remove comments [SF BUG-1481861].
		$html = str_replace('/<\!--[\s\S]*?-->/g', '', $html ) ;	 
		$html = str_replace(' /<(U|I|STRIKE)>&nbsp;<\/\1>/g', '&nbsp;', $html ) ;	 
		$html = str_replace(' /<H\d>\s*<\/H\d>/gi', '', $html ) ;
	 
		// Remove "display:none" tags.
		$html = str_replace(' /<(\w+)[^>]*\sstyle="[^"]*DISPLAY\s?:\s?none[\s\S]*?<\/\1>/gi', '', $html ) ;
	 
		// Remove language tags
		$html = str_replace(' /<(\w[^>]*) language=([^ |>]*)([^>]*)/gi', "<$1$3", $html) ;
	 
		// Remove onmouseover and onmouseout events (from MS Word comments effect)
		$html = str_replace(' /<(\w[^>]*) onmouseover="([^\"]*)"([^>]*)/gi', "<$1$3", $html) ;
		$html = str_replace(' /<(\w[^>]*) onmouseout="([^\"]*)"([^>]*)/gi', "<$1$3", $html) ;	 

		// The original <Hn> tag send from Word is something like this: <Hn style="margin-top:0px;margin-bottom:0px">
		$html = str_replace(' /<H(\d)([^>]*)>/gi', '<h$1>', $html ) ;
 
		// Word likes to insert extra <font> tags, when using MSIE. (Wierd).
		$html = str_replace(' /<(H\d)><FONT[^>]*>([\s\S]*?)<\/FONT><\/\1>/gi', '<$1>$2<\/$1>', $html );
		$html = str_replace(' /<(H\d)><EM>([\s\S]*?)<\/EM><\/\1>/gi', '<$1>$2<\/$1>', $html );
					
		return $html ;
	}
	
	
	## 
	#	CALL FUNCTION gen_salt TO GENERATE SALT CHARACTERS FOR HASHED PASSWORD
	#
	#		$salt = $global->gen_salt();
	#
	#	FOLLOWED BY 
	#
	#		$hashed_pass = $global->gen_hashed_password($salt,$password);
	#
	#	SAVE $salt VARIABLE TO SALT FIELD UNDER USER TABLE
	#	SAVE $hashed_pass VARIABLE TO PASSWORD FIELD UNDER USER TABLE
	##
	function gen_salt(){
		//This string tells crypt to use blowfish for 5 rounds.
		$_blowfish_strt = '$2a$05$';
		$_blowfish_end = '$';
		
		$_allowed_chars = 'ABCDEFGHIJKLMNOPQRSTUVWXYZabcdefghijklmnopqrstuvwxyz0123456789./';
		$_chars_len = 63;
		
		// 18 would be secure as well.
		$_salt_length = 21;
		
		$salt = "";
		
		for($i=0; $i<$_salt_length; $i++)
		{
			$salt .= $_allowed_chars[mt_rand(0,$_chars_len)];
		}
		return $_blowfish_strt . $salt . $_blowfish_end;
	}//end function gen_salt()
	
	function gen_hashed_password($salt='',$password=''){
		return md5($password.$salt);
	}//end function hashed_password($salt='',$password='')
	
	## 
	#	CALL FUNCTION get_hashed_password TO GET HASHED PASSWORD
	#
	#		$hashed_pass = $global->get_hashed_password($salt,$password);
	#		if($your_password === $hashed_pass)
	#		{
	#			your codes here
	#		}
	#
	##
	function get_hashed_password($salt='',$password=''){
		//This string tells crypt to use blowfish for 5 rounds.
		//$_blowfish_strt = '$2a$05$';
		//$_blowfish_end = '$';
		//print md5($password.$salt);
		return md5($password.$salt);
	}
	
	
	function plugin_info($folder){
		global $sql;
		$query = "SELECT title,tag,content_src_id,content_src,position,sid FROM ".DBPREFIX."plugins WHERE folder='$folder'";
		$sql->query($query);
		return $sql->fetch_array();	
	}
	
	
	function base_conversion($qty,$unit){
		// length
		switch($unit){
			case "cm" :
				return $qty;
			break;
			case "in" :
				return $qty * 2.54;
			break;
			case "m" :
				return $qty * 100;
			break;
			case "ft" :
				return $qty * 30.48;
			break;
			case "yd" :
				return $qty * 91.44;
			break;
			case "g" :
				return $qty;
			break;
			case "mg" :
				return $qty * 0.001;
			break;
			case "kg" :
				return $qty * 1000;
			break;
			case "oz" :
				return $qty * 28.3495;
			break;
			case "lb" :
				return $qty * 453.592;
			break;
			case "ml" :
				return $qty;
			break;
			case "l" :
				return $qty * 1000;
			break;
			case "fl" :
				return $qty * 29.5735;
			break;
			case "qt" :
				return $qty * 946.353;
			break;
			case "gal" :
				return $qty * 3785.41;
			break;
			case "pt" :
				return $qty * 473.176;
			break;
			case "pc" :
				return $qty;
			break;	
		}
	}
	
	function unit_conversion($qty,$unit,$base_unit){
		$qty = $this->base_conversion($qty,$unit);
		switch($base_unit){
			case "cm" :
				return $qty;
			break;
			case "in" :
				return $qty * 0.393701;
			break;
			case "m" :
				return $qty * 0.01;
			break;
			case "ft" :
				return $qty * 0.0328084;
			break;
			case "yd" :
				return $qty * 0.0109361;
			break;
			case "g" :
				return $qty;
			break;
			case "mg" :
				return $qty * 1000;
			break;
			case "kg" :
				return $qty * 0.001;
			break;
			case "oz" :
				return $qty * 0.035274;
			break;
			case "lb" :
				return $qty * 0.00220462;
			break;
			case "ml" :
				return $qty;
			break;
			case "l" :
				return $qty * 0.001;
			break;
			case "fl" :
				return $qty * 0.033814;
			break;
			case "qt" :
				return $qty * 0.00105669;
			break;
			case "gal" :
				return $qty * 0.000264172;
			break;
			case "pt" :
				return $qty * 0.00211338;
			break;
			case "pc" :
				return $qty;
			break;	
		}
		
	}
}

$global = new globalfunctions;
?>