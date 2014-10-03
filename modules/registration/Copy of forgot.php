<?php
	$username=$global->username($global->POST("username"));
	$email=$global->email($global->POST("email"));

	//	validations
	$error="<b>Note:</b> All fields marked with asterisk ( * ) are required!";

	// Validate E-mail and Username
	$query="SELECT SQL_BUFFER_RESULT SQL_CACHE uid,email,username FROM ".DBPREFIX."users WHERE email='$email' and username='$username'";
	$sql->query($query);
	$data=$sql->fetch_array();
	if(!$data["uid"]&&$email&&$username){
		$error="<b>ERROR:</b> Invalid username and/or email address.";
		$email="";
		$username="";
	}

	//	process
	if($username&&$email){
		$new_password = $global->generatePassword(6);
		if ($new_password){
			// create token, validation purpose only
			$token=md5(time());

			// update users table
			$query="UPDATE ".DBPREFIX."users set password='".md5($new_password)."', vid='".$token."', activate=0 WHERE username='".$username."'";
			
			if( $sql->query($query) ){
				//	send mail
				$subject="Password Request!";
				$message="Hi <strong>".$username."</strong>,<br /><br />
				You are receiving this email because a new password was requested for your account.<br />
				Please refer to the following details:<br /><br />
				Username: ".$username."<br />
				Password: ".$new_password."<br /><br />
				and click this <a href=\"".DOMAIN_NAME."/login.php?vid=".$token."\">link.</a>
				<br /><br />
				- Your Team";
				$global->mail_html($email,"Company Name","no-reply@companymail.com",$subject,$message);

				if(MODREWRITE){
					header("Location: ".DOMAIN_NAME."/registration-forgot/mode-sent/Forgot_Password.html");
				}else{
					header("Location: ".DOMAIN_NAME."/index.php/registration-forgot/mode-sent/Forgot_Password.html");
				}
			}
		}
	}

	$array=array(
	"url" => $global->anti_xss_high($_SERVER['REQUEST_URI']),
	"username" => $username,
	"email" => $email,
	"error" => $error,
	"dbutton" => $template->formfield("Done","done_forgot",BUTTON,"width:50px;",'onclick="window.location.href=\''.DOMAIN_NAME.'index.php\'"'),
	);

	if($global->GET("mode")){
		$template->merge_section_switch($array,"form","note",2);
	} else {
		$template->merge_section_switch($array,"form","note");
	}
?>