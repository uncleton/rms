<?php
//error_reporting(E_ALL);
//ini_set("display_errors", 1);
 
 
// Load Libraries
require_once("lib/config.php");
require_once("lib/sql.php");
require_once("lib/settings.php");
require_once("lib/template.php");
require_once("lib/global.php");
require_once("lib/auth.php");
require_once("lib/forms.php");
require_once("lib/auth.php");
require_once("lib/inventory.php");



// GET PARAMS AND URL CRUMS
########################################################
$global->params($GET);
//print_r($GET);
require_once("lib/forms.php");
require_once("lib/auth.php");

//	MAIN HTML TEMPLATES
########################################################
// GET FOLDER AND FILE TO LOAD
$MODULEFILE=$GET["file"];
$MODULEFOLDER=$GET["folder"];

//	Set page permission
#################################################################################
//print_r($userdata);
//exit;

if(!$global->set_permission(PERMIT_VIEW,true,$MODULEFOLDER)){
	$auth->logout();
};

if(isset($_GET["logout"])){
	$auth->logout();
}

// GET MODULE DATA
$MODULEQUERY="SELECT SQL_BUFFER_RESULT SQL_CACHE m.id,m.pages,m.file,m.folder,m.custom,t.folder as template FROM ".DBPREFIX."modules as m,".DBPREFIX."templates as t WHERE m.folder='$MODULEFOLDER' AND m.file='$MODULEFILE' AND t.id=m.template";
$PGIDS = "";
$CUSTOM_LINK_PLUGIN = "";
$CUSTOM_PLUGIN = "";

$sql->query($MODULEQUERY);
if($MODULEQUERYDATA=$sql->fetch_array()){
	
	// CUSTOMIZE
	$CUSTOM_PLUGIN = ($MODULEQUERYDATA["custom"] == 'Y') ? true : false;
	if(!isset($GET["custom_plugins"])){
		$GET["custom_plugins"]="";	
	}
	$CUSTOM_LINK_PLUGIN = ($GET["custom_plugins"] == 'Y') ? true : false;	
	
	if($CUSTOM_LINK_PLUGIN){
		$PLUGINIDS = explode("|",$GET["plugins"]);
	}else{
		$PLUGINIDS = explode("|",$MODULEQUERYDATA["pages"]);
	}
	$PGIDS="";
	$PLUGOR="";
	foreach($PLUGINIDS as $PLUGINKEYS => $PLUGINIDVALS){
		if($PLUGINIDVALS){
			$PGIDS .= $PLUGOR." p.id = '".$PLUGINIDVALS."' ";
			$PLUGOR = " OR ";
		}
	}
	
	if($PGIDS){
		$PGIDS = " AND ( $PGIDS )";
	}
	
	// TEMPLATE
	if(!$CUSTOM_LINK_PLUGIN){
		$TEMPLATE = $MODULEQUERYDATA["template"];
	}else{
		$TEMPLATE = $GET["template"];	
	}
}

// IF TEMPLATE IS BLANK LOAD DEFAULT TEMPLATE
if(!isset($TEMPLATE)){
	$TEMPLATEDEFAULTQUERY = "SELECT SQL_BUFFER_RESULT SQL_CACHE name,folder FROM ".DBPREFIX."templates WHERE isdefault='1'";
	$sql->query($TEMPLATEDEFAULTQUERY);
	if($TEMPLATEDEFAULTRESULT = $sql->fetch_array()){
		$TEMPLATE = $TEMPLATEDEFAULTRESULT["folder"];	
	}	
}
	
//	SECTION Templates
$template->output=$global->file_get_content("templates/".$TEMPLATE."/index.html");
// Template Properties
$array['asset']['path']['stylesheets'] = DOMAIN_NAME."templates/".$TEMPLATE."/stylesheets/";
$array['asset']['path']['javascripts'] = DOMAIN_NAME."templates/".$TEMPLATE."/javascripts/";
$array['asset']['path']['image'] = DOMAIN_NAME."templates/".$TEMPLATE."/images/";
$array['asset']['path']['template'] = DOMAIN_NAME."templates/".$TEMPLATE."/";

$template->fort_join($array);
$template->template_combine();

// META DATA
########################################################
$template->body_title = META_TITLE;
$template->body_keywords = META_KEYWORDS;
$template->body_description = META_DESCRIPTION;
$template->template_url=$array['asset']['path']['template'];


//	MODULES
########################################################
//	HTML Module Template
//	Set Module and file path
$MODULE_PATH="modules/".$MODULEFOLDER."/"; 

if($MODULEFILE){
	$MODULE_TEMPLATE=$MODULEFILE;	
} else {
	$MODULE_TEMPLATE="index";	
}


	$global->permission_type="modules"; 

	if( !is_file($MODULE_PATH.$MODULE_TEMPLATE.".php") ){
		// IF FILE NOT EXIST THE THROW 404 PAGE
		$MODULE_PATH="modules/notfound/";
		$MODULE_TEMPLATE="index";
		
		// DONT LOAD PLUGINS
		$PLUGIN__OFF = true;	
	}
	
//	Module template
$template->output=$global->file_get_content($MODULE_PATH."templates/".$MODULE_TEMPLATE.".html");
//	Module Process		 

require_once($MODULE_PATH.$MODULE_TEMPLATE.".php"); 
$MODULEDATA_ARRAY['module']['position']['content'] = $template->output;
// Template Properties
$MODULEDATA_ARRAY['asset']['path']['stylesheets'] = DOMAIN_NAME."templates/".$TEMPLATE."/stylesheets/";
$MODULEDATA_ARRAY['asset']['path']['javascripts'] = DOMAIN_NAME."templates/".$TEMPLATE."/javascripts/";
$MODULEDATA_ARRAY['asset']['path']['image'] = DOMAIN_NAME."templates/".$TEMPLATE."/images/";
$MODULEDATA_ARRAY['asset']['path']['fancybox'] = DOMAIN_NAME."templates/".$TEMPLATE."/fancybox/";
$MODULEDATA_ARRAY['asset']['path']['template'] = DOMAIN_NAME."templates/".$TEMPLATE."/";
$MODULEDATA_ARRAY['asset']['path']['userfiles'] = DOMAIN_NAME."UserFiles/";



$template->merge_elements($MODULEDATA_ARRAY);

// IF LINK ID DOES NOT EXIST GET MODULE ID

// GET PLUGINS
#######################################################
// TURN ON / OFF PLUGINS
if(!isset($PLUGIN__OFF)){
	//print $CUSTOM_PLUGIN;
		if($CUSTOM_LINK_PLUGIN){
			 $PLUGINQUERY="SELECT SQL_BUFFER_RESULT SQL_CACHE pos.position, p.folder, p.refid FROM ".DBPREFIX."order o LEFT JOIN ".DBPREFIX."plugins p ON o.plugin_id = p.id LEFT JOIN ".DBPREFIX."positions pos ON o.position_id = pos.id WHERE o.module_id = '".$GET['lid']."' AND p.status='1' ORDER BY o.position_id,o.order ASC";
		}else{
			if($CUSTOM_PLUGIN){
				$PLUGINQUERY="SELECT SQL_BUFFER_RESULT SQL_CACHE pos.position, p.folder, p.refid FROM ".DBPREFIX."order o LEFT JOIN ".DBPREFIX."plugins p ON o.plugin_id = p.id LEFT JOIN ".DBPREFIX."positions pos ON o.position_id = pos.id WHERE o.module_id = '".$MODULEQUERYDATA['id']."' AND p.status='1' ORDER BY o.position_id,o.order ASC";
			}else{
				$PLUGINQUERY="SELECT SQL_BUFFER_RESULT SQL_CACHE pos.position, p.folder, p.refid  FROM ".DBPREFIX."plugins as p, ".DBPREFIX."positions as pos WHERE pos.id=p.position AND p.status='1' $PGIDS ";
			}
		}
		$sql->query($PLUGINQUERY,md5("UN!QU3PLUGINQUERYID"));
		
		$PLUGINDATA_ARRAY = array();
		
		while($PLUGINDATA = $sql->fetch_array(md5("UN!QU3PLUGINQUERYID"))){
				$PLUGINREFID = $PLUGINDATA["refid"];
				@is_file('plugins/'.$PLUGINDATA["folder"].'/index.php') or die ($global->getErrUrl());
				$template->output=@$global->file_get_content('plugins/'.$PLUGINDATA["folder"].'/templates/index.html') or die ($global->getErrUrl());
				include('plugins/'.$PLUGINDATA["folder"].'/index.php');
				$PLUGINDATA_ARRAY['plugin']['position'][$PLUGINDATA["position"]] = $template->output;
				$query=false;
				$data=false;		
		}
	$PLUGINDATA_ARRAY['asset']['path']['stylesheets'] = DOMAIN_NAME."templates/".$TEMPLATE."/stylesheets/";
	$PLUGINDATA_ARRAY['asset']['path']['javascripts'] = DOMAIN_NAME."templates/".$TEMPLATE."/javascripts/";
	$PLUGINDATA_ARRAY['asset']['path']['image'] = DOMAIN_NAME."templates/".$TEMPLATE."/images/";
	$PLUGINDATA_ARRAY['asset']['path']['template'] = DOMAIN_NAME."templates/".$TEMPLATE."/";
	$template->merge_elements($PLUGINDATA_ARRAY);
}


$sql->free_result();


//	DISPLAY OUTPUT
###########################
$template->create();


?>