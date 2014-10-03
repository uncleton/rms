<?php
//	Set page permission
#################################################################################
if(!$global->set_permission(PERMIT_VIEW,true,$MODULEFOLDER)){
	header("Location: index.php");
};

	//	Get Post Request
	$uid = $global->numeric($form->REQUEST("uid"));
	if(!$uid){$uid = $global->gen_id();}
	$intro_msg = "<strong>Note:</strong> All fields are required!";
	$group = $form->POST("group");
	$password1 = $global->password($form->POST("password1"));
	$password2 = $form->POST("password2");
	$lname = $form->POST("lname");
	$fname = $form->POST("fname");
	$mname = $form->POST("mname");
	$gender = $form->POST("gender");
	$username = $form->POST("username");
	$email = $global->email($form->POST("email"));
	$error="";
	if($username){
		$query="SELECT SQL_BUFFER_RESULT SQL_CACHE uid FROM ".DBPREFIX."users WHERE username='$username'";
		$sql->select($query);
		$data=$sql->fetch_array();
		if($data["uid"]!=$uid&&$data["uid"]){
			$error='<li><b>Oopps!</b> Username already registered.</li>';
			$username="";
		}
	}
	
	if($password1=="INVALID"){
		$error.="<li>Password must be at least 5 characters long.</li>";
		$password1="";
		$password2="";
	}
	
	if($password1=="INVALID_DATA"){
		$error.="<li>Please enter a valid password.</li>";
		$password1="";
		$password2="";
	}
	
	if($password1&&$password2){
		if($password1!=$password2){
			$error.="<li>Password did not match.</li>";
			$password1="";
			$password2="";
		}
	}
	
	if($email=="INVALID"){
		$error.="<li>Invalid email format.</li>";
		$email="";
	}
	
	if($email){
		$query="SELECT SQL_BUFFER_RESULT SQL_CACHE uid FROM ".DBPREFIX."users WHERE email='$email'";
		$sql->select($query);
		$data=$sql->fetch_array();
		if($data["uid"]!=$uid&&$data["uid"]){
			$error.="<li>Email address is already registered.</li>";
			$email="";
		}
	}
	
	//print $error;

	//	Add / Edit process
	if($username&&$email&&$password1&&$group&&$fname&&$lname){
		
		$salt = $global->gen_salt();
		$hashed_password = $global->gen_hashed_password($salt,$password1);
		
		$query="SELECT SQL_BUFFER_RESULT SQL_CACHE uid,password FROM ".DBPREFIX."users WHERE uid='$uid'";
		$sql->select($query);
		$data=$sql->fetch_array();
		if($data["uid"]){
			if($data["uid"]!=1){
				// Change Password
				if($data["password"]!=$password1){
					$query="UPDATE ".DBPREFIX."users SET salt = '".$salt."', password='".$hashed_password."' WHERE uid='$uid'";
					$sql->query($query);	
				}

				// Edit Account
				$query="UPDATE ".DBPREFIX."users SET username='$username', email='$email', user_group='$group' WHERE uid='$uid'";
				if($sql->query($query)){
					// Edit Profile
					$query="UPDATE ".DBPREFIX."profile SET 
					fname='".$global->anti_inject_low($fname)."',
					mname='".$global->anti_inject_low($mname)."',
					gender='".$global->anti_inject_low($gender)."',
					lname='".$global->anti_inject_low($lname)."' WHERE uid='$uid'";
					$sql->query($query);
				}
			}
			header("Location: userman.html");
			exit;
		} else {
			
			$salt = $global->gen_salt();
			$hashed_password = $global->gen_hashed_password($salt,$password1);
			
			$query="INSERT INTO ".DBPREFIX."users (
			uid,username,user_group,salt,password,email,activate,vid,date_create) VALUES (
			'$uid','$username','$group','".$salt."','".$hashed_password."','$email','1','".md5($uid)."','".time()."')";
			if($sql->query($query)){
				$query="INSERT INTO ".DBPREFIX."profile (
				uid
				,fname
				,mname
				,lname,gender) VALUES (
				'".$uid."'
				,'".$global->anti_inject_low($fname)."'
				,'".$global->anti_inject_low($mname)."'
				,'".$global->anti_inject_low($lname)."'
				,'".$global->anti_inject_low($gender)."')";
				$sql->query($query);
			}
	
			header("Location: userman.html");
			exit;
		}
	} else {
		$query="SELECT SQL_BUFFER_RESULT SQL_CACHE a.uid,a.username,a.password,a.user_group,a.email,b.gender,
					b.fname,b.mname,b.lname FROM ".DBPREFIX."users a 
					LEFT JOIN ".DBPREFIX."profile b
					ON a.uid=b.uid
					WHERE a.uid='$uid'";
		$sql->select($query);
		$data=$sql->fetch_array();
		if($data["uid"]){
			$uid=$data["uid"];
			$username=$data["username"];
			$group=$data["user_group"];
			$password1=$data["password"];
			$password2=$data["password"];
			$email=$data["email"];
			$fname=$data["fname"];
			$lname=$data["lname"];
			$mname=$data["mname"];
			$gender=$data["gender"];
		}
	}

	//	get values for the parent links:
		$query="SELECT SQL_BUFFER_RESULT SQL_CACHE id,group_name FROM ".DBPREFIX."usergroup";
		$sql->select($query);
		$data_num=$sql->fetch_rows();
		for($i=1; $i<=$data_num;$i++){
			$data=$sql->fetch_array();
			if($userdata["group"]!='1'){
				if($data["id"]!='1'){
					
					
					$group_array[$data["group_name"]] = $data["id"];
				}
			}else{
				$group_array[$data["group_name"]] = $data["id"];
			}
		}
	$groups="";
	foreach($group_array as $k => $v){
		if($group==$v){
				$groups .= '<option value="'.$v.'" selected="selected">'.$k.'</option>';
		}else{
			$groups .= '<option value="'.$v.'">'.$k.'</option>';
		}
	}
	
	$gender_array = array(
		"Male" => "M",
		"Female" => "F",
	);
	$genders="";
	foreach($gender_array as $k => $v){
		if($gender==$v){
				$genders .= '<option value="'.$v.'" selected="selected">'.$k.'</option>';
		}else{
			$genders .= '<option value="'.$v.'">'.$k.'</option>';
		}
	}
	
	

	if(!$error){
		$error = $intro_msg;	
	}else{
		$error = "<ul style='padding: 0 15px;'>".$error."</ul>";	
	}

	$array=array(
	"uid" => $uid,
	"error" => $error,
	"username" => $username,
	"username" => $username,
	"lname" => $lname,
	"fname" => $fname,
	"mname" => $mname,
	"email" => $email,
	"group" => $groups,
	"gender" => $genders,
	"password1" => $password1,
	"password2" => $password2,
	"intro_msg" => $intro_msg,
	"url" => $_SERVER['REQUEST_URI'],
	);
	$template->merge($array);

?>