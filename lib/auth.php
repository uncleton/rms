<?php
class authfunctions {
	var $userdata=array();
	
	function __construct(){
		global $global,$sql;
		
		$alert=array();
		$alert["login_1"]="<strong>ERROR:</strong> Invalid username and password.";
		$alert["login_2"]="You are not authorized to view this page!";
		$alert["login_3"]="Warning: Illegal Hack Atempt! Your IP Will Be Blocked in this session.";
		$alert["login4"]="Please Activate your account.";

		// PAGE AUTHENTICATION PROCESS
		if(SESSION_TYPE=="cookies"){
			if(isset($_COOKIE[SESSION_PREFIX."_said"]) && isset($_COOKIE[SESSION_PREFIX."_user"])){
				$said=$global->anti_xss_high(mysql_escape_string($_COOKIE[SESSION_PREFIX."_said"]));
				$user=$global->anti_xss_high(mysql_escape_string($_COOKIE[SESSION_PREFIX."_user"]));
			}
		}else{
			if(isset($_SESSION[SESSION_PREFIX."_said"]) && isset($_SESSION[SESSION_PREFIX."_user"])){
				$said=$global->anti_xss_high(mysql_escape_string($_SESSION[SESSION_PREFIX."said"]));
				$user=$global->anti_xss_high(mysql_escape_string($_SESSION[SESSION_PREFIX."user"]));
			}
		}
		
		// CHECK WHAT AUTHETICATION USED
		if(AUTHUSE=='mixed'){
			// CHECK IF EMAIL
			if(isset($user)){
				if($global->email($user)){
					$AUTHUSE = 'email';
				}else{
					$AUTHUSE = 'username';	
				}
			}
		}else{
			$AUTHUSE = AUTHUSE;
		}
		
		
		if(isset($said)&&$user){
			if(USER_ACTIVATION){
					$user_activation=" AND U.activate='1'";
			}
			$SQL_AUTH="SELECT U.uid,U.username,U.email,U.said,U.user_group,U.browser,U.ip,P.lname,P.fname,P.gender FROM ".DBPREFIX."users AS U JOIN ".DBPREFIX."profile AS P ON P.uid=U.uid WHERE U.said='$said' AND U.".$AUTHUSE."='$user' $user_activation";
			$sql->select($SQL_AUTH);
			$AUTH=$sql->fetch_array();
			
			if($AUTH["uid"]){
				//	IF EXIST THEN COLLECT USER DATA
				$userdata = array();
				$userdata["browser"]=strtolower($_SERVER['HTTP_USER_AGENT']);
				$userdata["ip"]=$_SERVER['REMOTE_ADDR'];
				$userdata["username"]=$AUTH["username"];
				$userdata["lname"]=$AUTH["lname"];
				$userdata["fname"]=$AUTH["fname"];
				$userdata["email"]=$AUTH["email"];	
				$userdata["uid"]=$AUTH["uid"];
				$userdata["said"]=$AUTH["said"];
				$userdata["gender"]=$AUTH["gender"];
				$userdata["group"]=$AUTH["user_group"];
				
				

				$query="UPDATE ".DBPREFIX."users SET last_login='".time()."',browser='".$userdata["browser"]."',ip='".$userdata["ip"]."' WHERE uid='".$AUTH["uid"]."'";
				$sql->query($query);

				if($AUTH["browser"]!=strtolower($_SERVER['HTTP_USER_AGENT']) && $AUTH["ip"]!=$_SERVER['REMOTE_ADDR']){
					@session_destroy();
					setcookie(SESSION_PREFIX."_user","",time()+SESSION_TIMEOUT,"/");
					setcookie(SESSION_PREFIX."_said","",time()+SESSION_TIMEOUT,"/");
				}

				if(SESSION_TYPE=="cookies"){
					setcookie(SESSION_PREFIX."_user",$AUTH[$AUTHUSE],time()+SESSION_TIMEOUT,"/");
					setcookie(SESSION_PREFIX."_said",$said,time()+SESSION_TIMEOUT,"/");
				}else{
					$_SESSION[SESSION_PREFIX."said"]=$said;
					$_SESSION[SESSION_PREFIX."user"]=$AUTH[$AUTHUSE];
				}
				
			} else {
				@session_destroy();
				setcookie(SESSION_PREFIX."_user","",time()+SESSION_TIMEOUT,"/");
				setcookie(SESSION_PREFIX."_said","",time()+SESSION_TIMEOUT,"/");
				$userdata["username"]='Guest';
				$userdata["group"]=0;
			//exit;
			}
		}else{
			//########	USERS DATA ########///////
			$userdata=array();
			$userdata["username"]='Guest';
			$userdata["group"]=0;
		}
		
		$this->userdata =  $userdata;
	}

	function logout($url=false){
		global $global;

		@session_destroy();
		setcookie(SESSION_PREFIX."_user","",time()+SESSION_TIMEOUT,"/");
		setcookie(SESSION_PREFIX."_said","",time()+SESSION_TIMEOUT,"/");
		if($url){
			header("location: index.html");
		}else{
			$url=$global->redirect_from($_SERVER['REQUEST_URI']);
			header("location: login.php?redirect=index.html");
		}
		exit;
	}

}
$auth = new authfunctions;
$userdata = $auth->userdata;
//	set permission user group
$global->p_group=$userdata["group"];
?>