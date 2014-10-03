<?php


class configuration {
	var $config;
	
	function __construct(){
		global $sql;
		
		// Get Global Configuration
		$query = "SELECT varid,value FROM ".DBPREFIX."settings";
		$sql->query($query);
		while($data = $sql->fetch_array()){
			if($data["varid"]){
				$this->config[$data["varid"]] = $data["value"];
			}
		}	
	}
}

$config = new configuration();
$config = $config->config;


//	SESSION CONFIGURATION
//	Set Authentication system to use. values: cookies or session  
define("SESSION_TYPE",$config["SESSION_TYPE"]);
//	Set Session Prefix to use.   
define("SESSION_PREFIX",$config["SESSION_PREFIX"]);
//	SESSION EXPIRY
define("SESSION_TIMEOUT",$config["SESSION_TIMEOUT"]);

//	Authentication to use: username, email or mixed
define("AUTHUSE",$config["AUTHUSE"]);
//	require Activation
define("USER_ACTIVATION",$config["USER_ACTIVATION"]);
//	PATHS

//	Domain Name (e.g. http://localhost/plum/)
$protocol = (!empty($_SERVER['HTTPS']) && $_SERVER['HTTPS'] !== 'off' || $_SERVER['SERVER_PORT'] == 443) ? "https://" : "http://";
$domainName = $_SERVER['HTTP_HOST'];
define("DOMAIN_NAME",$protocol.$domainName.$config["ROOT_DIR"]."/");
//	Set UserFile path (e.g. /ycms/global/)
define("USERFILE_PATH",$config["ROOT_DIR"]);
//	Set ModRewrite
define("MODREWRITE",$config["MODREWRITE"]);
//	Domain Root (e.g. localhost)
define("DOMAIN_ROOT",$_SERVER['HTTP_HOST']);

if(MODREWRITE){
	define("ABSOLUTE_PATH",DOMAIN_NAME);
}else{
	define("ABSOLUTE_PATH",DOMAIN_NAME."index.php/");
}

// META DATA GLOBAL
define("META_TITLE",$config["META_TITLE"]);
define("META_KEYWORDS",$config["META_KEYWORDS"]);
define("META_DESCRIPTION",$config["META_DESCRIPTION"]);




// OTHER CONFUG NOT DEFINED IN DB
//	CACHE
define("USE_CACHE",$config["CACHE_ENABLED"]);
define("CACHE_TIME",$config["CACHE_TIME"]);
define("CACHE_PATH",$config["CACHE_PATH"]);

//	LOGS
define("USE_LOGS",true);

//	DEBUGGING
//	Set to true to debug code
define("DEBUG",true);

//	INTRO PAGE
//	ENABLE DISABLE INDEX PAGE AS INTRO PAGE
define("INTRO_PAGE",false);
//	Set Plugin path
define("PLUGIN_PATH","plugins");
// REMOVE RECORD ID IN URL FOR SEO
define("NOIDURL",true);

?>
