<?php


// Load Libraries
require_once("lib/config.php");
require_once("lib/sql.php");
require_once("lib/settings.php");
require_once("lib/template.php");
require_once("lib/global.php");
require_once("lib/forms.php");

//	PROCESS
################################################
//	DB TABLE CONFIG
$dbtable=DBPREFIX.'users';

//	GET REDIRECTION
if($_GET["redirect"]){
	$url=$global->redirect_to($_GET["redirect"]);
	$target='_self';
}else{
	$url='index.html';
	$target='_parent';
}

// CHECK IF ALREADY LOGED IN
if(SESSION_TYPE==1){
	if($_COOKIE[SESSION_PREFIX."_said"]&&$_COOKIE[SESSION_PREFIX."_user"]){
		//header("Location: $url");
		exit;
	}
}else{
	if($_SESSION[SESSION_PREFIX."said"]&&$_SESSION[SESSION_PREFIX."user"]){
		//header("Location: $url");
		exit;
	}
}

//	LOGIN AUTHENTICATION PROCESS
$said=$form->POST("said");
$username=$global->username($form->POST(SESSION_PREFIX."username"));
if($username=="INVALID" || $username=="INVALID_DATA"){
	$username="";
}

//$password=md5($form->POST(SESSION_PREFIX."password"));
$password=$form->POST(SESSION_PREFIX."password");
$alert="";

// WHAT AUTHENTICATION USER TO USE
// IF MIXED
if(AUTHUSE=='mixed'){
	// CHECK IF EMAIL
	if($global->email($username)){
		$AUTHUSE = 'email';
	}else{
		$AUTHUSE = 'username';	
	}
}else{
	$AUTHUSE = AUTHUSE;
}


if($username&&$password){
	//	Login Log Attemps
	$query="SELECT SQL_BUFFER_RESULT SQL_CACHE login_attemps,login_time,salt FROM $dbtable WHERE ".$AUTHUSE."='$username'";
	$sql->select($query);
	$data=$sql->fetch_array();
	$login_attemps=$data["login_attemps"];
	$login_time=$data["login_time"];

	$salt=$data["salt"];
	$hashed_pass = $global->get_hashed_password($salt,$password);
	
	
	//print $global->gen_hashed_password($salt,$password);
	//exit;
	if($login_attemps<2){
		$attemps=3;
	} else {
		$attemps=6;
	}
	
	if($login_attemps<3){
		$time_current = (time()+(60*5));
	} else {
		$time_current=time();
	}
	
	if($login_attemps<6){
		if($login_attemps<=$attemps&&$login_time<$time_current){
			//	AUTHENTICATE USERNAME AND PASSWORD
			if(USER_ACTIVATION){
				$user_activation=" AND activate='1'";
				
				
				$SQL_AUTH_UA="SELECT SQL_BUFFER_RESULT SQL_CACHE uid FROM $dbtable WHERE ".$AUTHUSE."='$username' AND password='$hashed_pass' AND activate='0'";
				$sql->select($SQL_AUTH_UA);
				$AUTH_UA=$sql->fetch_array();
				$AUTH_ID_UA=$AUTH_UA["uid"];
			}
			$SQL_AUTH="SELECT SQL_BUFFER_RESULT SQL_CACHE uid,username,email FROM $dbtable WHERE ".$AUTHUSE."='$username' AND password='$hashed_pass' $user_activation";
			$sql->select($SQL_AUTH);
			$AUTH=$sql->fetch_array();
			$AUTH_ID=$AUTH["uid"];
			
			if($AUTH_ID){
			
				$SQL_AUTH_UPDATE="UPDATE $dbtable SET said='$said', last_login='".time()."', login_attemps = '0', login_time = '0' Where uid='".$AUTH_ID."'";
				if($sql->query($SQL_AUTH_UPDATE)){
				
				
					//IF AUTHENTICATED START SESSION AND COOKIES
					if(SESSION_TYPE=="cookies"){

						setcookie(SESSION_PREFIX."_user",$AUTH[$AUTHUSE],time()+SESSION_TIMEOUT,"/");
						setcookie(SESSION_PREFIX."_said",$said,time()+SESSION_TIMEOUT,"/");
					}else{
						$_SESSION[SESSION_PREFIX."said"]=$said;
						$_SESSION[SESSION_PREFIX."user"]=$AUTH[$AUTHUSE];			
					}
					
					header("Location: ".$global->redirect_to($url));
					exit;
				}
							
			}else{
				$query="UPDATE $dbtable SET login_attemps='".($login_attemps+1)."', login_time='".$time_current."' WHERE ".$AUTHUSE."='$username'";
				$sql->query($query);
				
				@session_destroy();
				setcookie(SESSION_PREFIX."_user","",time()+SESSION_TIMEOUT,"/");
				setcookie(SESSION_PREFIX."_said","",time()+SESSION_TIMEOUT,"/");
				if($username){
					$alert=$alert["login_1"];
				} else {
					$alert="";
				}
				if($AUTH_ID_UA){
					$alert="Please activate your account";
				}else{
					$alert="Invalid username or password";	
				}
			}
		} else {
			$alert="You're account has been temporarily blocked! You may try again after ".SESSION_TIMEOUT." minutes.";	
		}	
	} else {
		$query="UPDATE $dbtable SET activate='0', login_attemps='7', login_time='0' WHERE ".$AUTHUSE."='$username'";
		$sql->query($query);
		$alert="You're account has been blocked! Please contact your administrator to reactivate your account!";
	}
}
$said=md5(time().rand(100,999));

// IF TEMPLATE IS BLANK LOAD DEFAULT TEMPLATE
if(!$TEMPLATE){
	$TEMPLATEDEFAULTQUERY = "SELECT name,folder FROM ".DBPREFIX."templates WHERE isdefault='1'";
	$sql->query($TEMPLATEDEFAULTQUERY);
	if($TEMPLATEDEFAULTRESULT = $sql->fetch_array()){
		$TEMPLATE = $TEMPLATEDEFAULTRESULT["folder"];	
	}	
}

// CALL FUNCTION TO GENERATE TEMPLATE
$template->output=@$global->file_get_content("templates/".$TEMPLATE."/login1.html");
//	SECTION Templates
// Template Properties
$array['asset']['path']['stylesheets'] = DOMAIN_NAME."templates/".$TEMPLATE."/stylesheets/";
$array['asset']['path']['javascripts'] = DOMAIN_NAME."templates/".$TEMPLATE."/javascripts/";
$array['asset']['path']['image'] = DOMAIN_NAME."templates/".$TEMPLATE."/images/";
$array['asset']['path']['template'] = DOMAIN_NAME."templates/".$TEMPLATE."/";
$template->fort_join($array);

$array2=array(
	'username' => $userdata["username"],
	'username_varname' => SESSION_PREFIX."username",
	'password_varname' => SESSION_PREFIX."password",
	'target' => $target,
	'url' => $url,
	'said' => $said,
	'password' => $password,
	'id' => $id,
	'alert' => $alert,
	'dynamic_yr' => date("Y"),
);

$template->merge($array2);

$template->template_combine();


//	DISPLAY OUTPUT
########################################################
$template->create();

?>