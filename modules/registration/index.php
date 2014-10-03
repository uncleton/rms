<?php
if(!$userdata["uid"]){
	$id=$global->numeric($_REQUEST["id"]);
	if(!$id){
		$id=$global->gen_id();
	}

	$email		= $global->POST("email");
	$pass		= $global->password($global->POST("pass"));
	$pass2		= $global->POST("pass2");
	$fname		= $global->alpha2($global->POST("fname"));
	$lname		= $global->alpha2($global->POST("lname"));
	$contact	= $global->phone($global->POST("contact"));
	$gender		= $global->POST("gender");
	$notification		= $global->POST("notification");
	$year		= $global->POST("year");
	$month		= $global->POST("month");
	$day		= $global->POST("day");
	$vid		= md5($id);

	// VALIDATION
	if($year&&$month&&$day){
		$birth="$year-$month-$day";
	}

	if($email){
		if(!$global->email($email)){
			$err_email="Invalid email format.";
			$email="";
		}
	}
		
	if($email){
		$query="SELECT SQL_BUFFER_RESULT SQL_CACHE uid FROM ".DBPREFIX."users WHERE email='$email'";
		$sql->query($query);
		$data = $sql->fetch_array();
		if($data["uid"]!=$uid&&$data["uid"]){
			$err_email="Email address already registered.";
			$email="";
		}
	}
	
	if($fname && $fname=="INVALID"){
		$err_fname = "Invalid character format";
		$fname = "";
	}
	
	if($lname && $lname=="INVALID"){
		$err_lname = "Invalid character format";
		$lname = "";
	}
	
	if($contact && $contact=="INVALID"){
		$err_contact = "Invalid contact format";
		$contact = "";
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

	$req_gender = '<font face="Tahoma" size="-1" color="RED"> * </font>';
	$chk_male = '';
	$chk_fmale = '';
	if($gender){
		$req_gender = '';
		if($gender == 'M'){
			$chk_male = 'checked="checked"';
		}else{
			$chk_fmale = 'checked="checked"';
		}
	}
	
	$chk_notice = '';
	if($notification){
		$chk_notice = 'checked="checked"';
	}
	
	
	// Process
	if($id&&$pass&&$email&&$fname&&$lname&&$gender&&$contact&&$birth){
		$query="SELECT uid FROM ".DBPREFIX."users WHERE uid='$id'";
		$sql->select($query);
		$data=$sql->fetch_array();
		if(!$data["uid"]){
			$uname = strtolower(str_replace(" ","",$fname)."_".str_replace(" ","",$lname));
			$query="INSERT INTO ".DBPREFIX."users (uid, vid, username, password, email, activate, user_group, date_create) 
					VALUES (
						'".$id."'
						,'".$vid."'
						,'".$uname."'
						,'".md5($pass)."'
						,'".$email."'
						,'0'
						,'3'
						,'".(time()+(60*60*15))."'
					)";
			if($sql->query($query)){
				$query2="INSERT INTO ".DBPREFIX."profile (uid, fname, lname, contact, bday, gender, notification) 
						VALUES (
							'".$id."'
							,'".$global->anti_inject_low($fname)."'
							,'".$global->anti_inject_low($lname)."'
							,'".$global->anti_inject_low($contact)."'
							,'$birth'
							,'".$global->anti_inject_low($gender)."'
							,'".$global->anti_inject_low($notification)."'
						)";
				if($sql->query($query2)){
					// Send e-mail
					$subject="Welcome to Purefoods Premiere Club";
					$message="<br><br>
					Thank you for signing up with Purefoods Premiere Club!You are just a click away from enjoying these great privileges as a member.<br>
					To complete your registration, please <a href=\"".ABSOLUTE_PATH."login/vid-{$vid}/activation-link.html\">click here.</a><br><br>
					Best regards,<br><br>
					Purefoods Premiere Club";
					$global->mail_html($email,"Purefoods Premiere Club","no-reply@premiereclub.com",$subject,$message);
				}
			}
		}

		header("Location: ".ABSOLUTE_PATH."registration/mode-set/registration.html");
		exit();
	}
	
	$array = array(
		"url" =>  ABSOLUTE_PATH."registration",
		"id" => $template->formfield($id,"id",HIDDEN),
		"email" => $template->formfield($email,"email",TEXT,'','',TRUE,$err_email),
		"pass" => $template->formfield($pass,"pass",PASSWORD,'','',TRUE,$err_pass),
		"pass2" => $template->formfield($pass2,"pass2",PASSWORD,'','',TRUE),
		"fname" => $template->formfield($fname,"fname",TEXT,'','',TRUE,$err_fname),
		"lname" => $template->formfield($lname,"lname",TEXT,'','',TRUE,$err_lname),
		"contact" => $template->formfield($contact,"contact",TEXT,'','',TRUE,$err_contact),
		"bday" => $template->form_date($birth,TRUE),
		"req_gender"  => $req_gender,
		"chk_male" => $chk_male,
		"chk_fmale"  => $chk_fmale,
		"chk_notice"  => $chk_notice,
		"cancel_url" => ABSOLUTE_PATH,
	);

	if(!$GET['mode']){
		$template->merge_section_switch($array,"form","thankyou",1);
	}else{
		$template->merge_section_switch($array,"form","thankyou",2);
	}
	
	
	$template->merge($array);
}else{
	header("Location: ".DOMAIN_NAME);
}
?>