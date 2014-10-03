<?php

$message = $form->POST("message");

if($message){
	$insert = "INSERT INTO ".DBPREFIX."announcement (uid,message) VALUES ('".$userdata["uid"]."','$message')";
	$sql->query($insert);
	
	
	
	switch($userdata["gender"]){
		case "M" :
			$avatar = "male";
		break ;
		case "F" :
			$avatar = "female";	
		break;
		case "U" :
			$avatar = "female";	
		break;
	}
	
	print '<li class="current-user">
                        <img width="30" height="30" src="'.DOMAIN_NAME.'templates/default/images/avatar-'.$avatar.'.jpg" />
                        <div class="bubble">
                          <a class="user-name" href="">'.$userdata["fname"].' '.$userdata["lname"].'</a>
                          <p class="message">
                            '.$message.'
                          </p>
                          <p class="time">
                            <strong>'.date('M j, Y g:i A', time()).'</strong>
                          </p>
                        </div>
                      </li>';
}



exit;

?>