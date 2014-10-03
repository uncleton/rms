<?php
session_start();
if($userdata["uid"]){
		$id			= $global->numeric($userdata["uid"]);
		$email		= $global->email($global->POST("email"));
		$pass		= $global->password($global->POST("pass"));
		$pass2		= $global->POST("pass2");
		$fname		= $global->POST("fname");
		$mname		= $global->POST("mname");
		$lname		= $global->POST("lname");
		$year		= $global->POST("year");
		$month		= $global->POST("month");
		$day		= $global->POST("day");
		$captcha	= $global->POST("captcha");

		if($year&&$month&&$day){
			$birth="$year-$month-$day";
		}

		if($pass=="INVALID"){
			$err_pass="Password must be at least 5 characters long.";
			$pass="";
			$pass2="";
		}
		
		if($pass=="INVALID_DATA"){
			$err_pass="Please enter valid password.";
			$pass="";
			$pass2="";
		}
		
		if($pass&&$pass2){
			if($pass!=$pass2){
				$err_pass="Password did not match.";
				$pass="";
				$pass2="";
			}
		}
		
		if($email=="INVALID"){
			$err_email="Invalid email format.";
			$email="";
		}
		
		if($email){
			$query="SELECT SQL_BUFFER_RESULT SQL_CACHE uid FROM ".DBPREFIX."users WHERE email='$email'";
			$sql->query($query);
			$data=$sql->fetch_array($query);
			if($data["uid"]!=$uid&&$data["uid"]){
				$err_email="Email address already registered.";
				$email="";
			}
		}

		if($_SESSION["code"]==$global->POST("captcha")){
			$captcha=true;
		} else {
			$captcha='';
			if($global->POST("captcha")){
				$error="Code mismatch! Please enter code again.";
			}
		}

		// Process starts here
		if($id&&$fname&&$mname&&$lname&&$captcha){
			// Update User Profile
			$query="UPDATE ".DBPREFIX."profile SET
				 fname='".$global->anti_inject_low($fname)."'
				,mname='".$global->anti_inject_low($mname)."'
				,lname='".$global->anti_inject_low($lname)."'
				,bday='$birth'
				WHERE uid='".$userdata["uid"]."'";
			$sql->query($query);
			
			// Update Email
			if($email){
				$query="UPDATE ".DBPREFIX."users SET email='".$email."' WHERE uid='".$userdata["uid"]."'";
				$sql->query($query);
			}
			// Update Password
			if($pass&&$pass2){
				$query="UPDATE ".DBPREFIX."users SET password='".md5($pass)."' WHERE uid='".$userdata["uid"]."'";
				$sql->query($query);
			}

			if(MODREWRITE){
				header("Location: ".DOMAIN_NAME."LS/registration~edit/mode-sent/Edit_Account.html");
			}else{
				header("Location: module.php?LM=registration.edit&mode=sent");
			}

		}else{
			$query="SELECT tblb.uid, tblb.username, tblb.email, tbla.fname, tbla.mname, tbla.lname, tbla.bday
					FROM ".DBPREFIX."profile tbla
					LEFT JOIN ".DBPREFIX."users tblb ON tbla.uid = tblb.uid
					WHERE tblb.activate =1
					AND tblb.user_group =3
					AND tblb.uid='".$userdata["uid"]."'";
			$sql->query($query);
			$data=$sql->fetch_array($query);
			if($data["uid"]){
				$id=$data["uid"];
				$fname=$data["fname"];
				$mname=$data["mname"];
				$lname=$data["lname"];
				$birth=$data["bday"];
				$email=$data["email"];
			}
		}

	$array = array(
		"url"	=> $global->anti_xss_high($_SERVER['REQUEST_URI']),
		"uname" => $userdata["username"],
		"pass" => $template->formfield($pass,"pass",PASSWORD,"width:250px;",0,FALSE,$err_pass),
		"pass2" => $template->formfield($pass2,"pass2",PASSWORD,"width:250px;",0,FALSE),
		"email" => $template->formfield($email,"email",TEXT,"width:250px;",0,TRUE,$err_email),
		"lname" => $template->formfield($lname,"lname",TEXT,"width:250px;",0,TRUE),
		"fname" => $template->formfield($fname,"fname",TEXT,"width:250px;",0,TRUE),
		"mname" => $template->formfield($mname,"mname",TEXT,"width:250px;",0,TRUE),
		"bday" => $template->form_date($birth),
		"captcha" =>  $template->formfield("","captcha",TEXT,"width:80px;",0,TRUE,$error),
		"sbutton" => $template->formfield("Submit","submit_editreg",SUBMIT,"width:80px;"),
		"cbutton" => $template->formfield("Cancel","cancel_editreg",BUTTON,"width:80px;",'onclick="window.location.href=\''.DOMAIN_NAME.'global/index.php\'"'),
		"dbutton" => $template->formfield("Done","done_editreg",BUTTON,"width:50px;",'onclick="window.location.href=\''.DOMAIN_NAME.'global/index.php\'"'),
	);

	if($global->GET("mode")){
		$template->merge_section_switch($array,"form","note",2);
	} else {
		$template->merge_section_switch($array,"form","note");
	}
}else{
	header("Location: ".DOMAIN_NAME."/index.php");
}
?>