<?php

//	PROCESS
################################################
//	DB TABLE CONFIG
$dbtable=DBPREFIX.'users';

//	ACTIVATE ACCOUNT
if($GET["vid"]){
	$vid=$GET["vid"];
	$query="UPDATE $dbtable SET activate='1' WHERE vid='$vid'";
	$sql->query($query);
}

// LOGOUT
if($GET["mode"]=="logout"){
	$auth->logout(DOMAIN_NAME);
}

//	GET REDIRECTION
if($global->GET("redirect")){
	$url=$global->redirect_to($global->specialchar($global->GET("redirect")));
	$target='_self';
}else{
	$url=DOMAIN_NAME;
	$target='_parent';
}

// CHECK IF ALREADY LOGGED IN
if(SESSION_TYPE=="cookies"){
	if($_COOKIE[SESSION_PREFIX."_said"]&&$_COOKIE[SESSION_PREFIX."_user"]){
	header("Location: $url");
	exit;
	}
}else{
	if($_SESSION[SESSION_PREFIX."said"]&&$_SESSION[SESSION_PREFIX."user"]){
	header("Location: $url");
	exit;
	}
}

//	LOGIN AUTHENTICATION PROCESS
 $said=$global->POST("said");
 $username=$global->username($global->POST("username"));
 $email=$global->POST("email");
 //$password=md5($global->POST("password"));
 $password=$global->POST("password");
 $alert="";

// WHAT AUTHENTICATION USER TO USE
 if(!$username && $email){
 	$username=$email;
 }
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
		//	AUTHENTICATE USERNAME AND PASSWORD
		$query="SELECT SQL_BUFFER_RESULT SQL_CACHE salt FROM $dbtable WHERE ".$AUTHUSE."='$username'";
		$sql->select($query);
		$data=$sql->fetch_array();
		
		$salt=$data["salt"];
		$hashed_pass = $global->get_hashed_password($salt,$password);
		
		if(USER_ACTIVATION){
			$user_activation=" AND activate='1'";
		}

		$SQL_AUTH="SELECT SQL_BUFFER_RESULT SQL_CACHE uid,username,email FROM $dbtable WHERE ".$AUTHUSE."='$username' AND password='$hashed_pass' $user_activation";
		$sql->select($SQL_AUTH);
		$AUTH=$sql->fetch_array();
		$AUTH_ID=$AUTH["uid"];
		if($AUTH_ID){
			$SQL_AUTH_UPDATE="UPDATE $dbtable SET said='$said', last_login='".time()."' WHERE uid='".$AUTH_ID."'";
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
			}
		}else{
			@session_destroy();
			setcookie(SESSION_PREFIX."_user","",time()+SESSION_TIMEOUT,"/");
			setcookie(SESSION_PREFIX."_said","",time()+SESSION_TIMEOUT,"/");
			if($username){
				$alert="<strong>ERROR:</strong> Invalid username and/or password!";
			} else {
				$alert="";
			}
			if($AUTH_ID){
				$alert="Please activate your account.";
			}
		}
		$sql->free_result();

}

$said=md5(time().rand(100,999));
$array=array(
//	CONTENT BODY TEMPLATE
	'username' 	=> $userdata["username"],
	'target' 	=> $target,
	'url' 		=> $url,
	'domain' 	=> urlencode(DOMAIN_PATH),
	'said' 		=> $said,
	'password' 	=> $password,
	'id' 		=> $id,
	'alert' 	=> $alert,
	'urlLogin' => ABSOLUTE_PATH."login",
	'urlForgot' => ABSOLUTE_PATH."registration-forgot/forgot_password.html",
	'urlRegister' => ABSOLUTE_PATH."registration/registration.html",
);
$template->merge($array);

?>