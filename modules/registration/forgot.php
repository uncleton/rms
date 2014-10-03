<?php
	$email = $global->POST("email");
	
	if($email){
		if(!$global->email($email)){
			$error="<b>ERROR:</b> Invalid e-mail format.";
			$email="";
		}
	}

	// Validate E-mail
	$query="SELECT SQL_BUFFER_RESULT SQL_CACHE uid,email,username FROM ".DBPREFIX."users WHERE email='$email' ";
	$sql->query($query);
	$data=$sql->fetch_array();
	$uname = $global->get_FullName($data["uid"]);
	if(!$data["uid"]&&$email){
		$error="<b>ERROR:</b> Invalid e-mail address.";
		$email="";
	}

	//	process
	if($email){
		$new_password = $global->generatePassword(6);
		if ($new_password){
			// create token, validation purpose only
			$token=md5(time());

			// update users table
			$query="UPDATE ".DBPREFIX."users set password='".md5($new_password)."', vid='".$token."', activate=0 WHERE email='".$email."'";
			
			if( $sql->query($query) ){
				//	send mail
				$subject="Password Request!";
				$message="Hi <strong>".$uname."</strong>,<br /><br />
				You are receiving this email because a new password was requested for your account.<br />
				Please refer to the following details:<br /><br />
				E-mail: ".$email."<br />
				Password: ".$new_password."<br /><br />
				and click this <a href=\"".ABSOLUTE_PATH."login/vid-{$token}/activation-link.html\">link.</a>
				<br /><br />
				Purefoods Premiere Club";
				$global->mail_html($email,"Purefoods Premiere Club","no-reply@premiereclub.com",$subject,$message);

				header("Location: ".ABSOLUTE_PATH."registration-forgot/mode-set/forgot_password.html");
				exit();
			}
		}
	}

	$array = array(
		"url" => $global->anti_xss_high($_SERVER['REQUEST_URI']),
		"email" => $email,
		"error" => $error,
		"cancel_url" => ABSOLUTE_PATH,
	);

	if(!$GET['mode']){
		$template->merge_section_switch($array,"form","thankyou",1);
	}else{
		$template->merge_section_switch($array,"form","thankyou",2);
	}
	
	$template->merge($array);
?>