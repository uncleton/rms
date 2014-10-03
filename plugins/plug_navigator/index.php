<?php
//	Set page permission
#################################################################################
//print_r($userdata);

	if(!$PLUGINREFID){
		$PLUGINREFID = 1;
	}
	
	switch($userdata["gender"]){
		case "M" :
			$gender = "male";
		break;
		case "F" :
			$gender = "female";
		break;	
	}
	
	
	// Permissions
	$orders = "";
	$inventory = "";
	$system = "";
	$userman = "";
	$usergroup = "";
	$users = "";
	$perms = $global->set_permission(PERMIT_VIEW,true,"orders,inventory,system,userman,usergroup");
	foreach($perms as $k => $perm){
		switch ($k){
			case "orders" :
				if(!$perm){
					$orders = 'class="remove"';
				}
			break;
			case "inventory" :
				if(!$perm){
					$inventory = 'class="remove"';
				}
			break;
			case "system" :
				if(!$perm){
					$system = 'class="remove"';
				}
			break;
			case "userman" :
				if(!$perm){
					$userman = 'class="remove"';
				}
			break;
			case "usergroup" :
				if(!$perm){
					$usergroup = 'class="remove"';
				}
			break;	
		}	
	}
	if($usergroup && $userman){
		$users = 'class="remove"';
	}
	
	// Current Page
	$tab1 = "";
	$tab2 = "";
	$tab3 = "";
	$tab4 = "";
	$tab5 = "";
	$tab6 = "";
	switch($MODULEFOLDER){
		default :
			$tab1 = 'class="current"';
		break;
		case "orders" :
			switch($MODULEFILE){
				default :
					$tab3 = 'class="current"';
				break;
			}
		break;
		case "inventory" :
			switch($MODULEFILE){
				default :
					$tab4 = 'class="current"';
				break;
				case "products" :
					$tab4 = 'class="current"';
				break;
			}
		break;
		case "system" :
			switch($MODULEFILE){
				default :
					$tab5 = 'class="current"';
				break;
				case "expenses" :
					$tab7 = 'class="current"';
				break;
				case "add_expenses" :
					$tab7 = 'class="current"';
				break;
				
			}
		break;
		case "userman" :
			$tab5 = 'class="current"';
		break;
		case "usergroup" :
			$tab5 = 'class="current"';
		break;
		case "userpermission" :
			$tab5 = 'class="current"';
		break;
		case "index" :
			switch($MODULEFILE){
				default :
					$tab1 = 'class="current"';
				break;
				case "sales_report" :
					$tab6 = 'class="current"';
				break;
			}
		break;
	}
	
	
	
	$nav_array = array(
		"navigator" => '<ul class="nav">'.$nav.'</ul>',
		"lname" => $userdata["lname"],
		"fname" => $userdata["fname"],
		"id" => $navid,
		"gender" => $gender,
		"prefix" => $prefix,
		"urlSearch" => $global->anti_xss_high(ABSOLUTE_PATH."search/search.html"),
		"orders" => $orders,
		"system" => $system,
		"inventory" => $inventory,
		"userman" => $userman,
		"usergroup" => $usergroup,
		"users" => $users,
		"tab1" => $tab1,
		"tab2" => $tab2,
		"tab3" => $tab3,
		"tab4" => $tab4,
		"tab5" => $tab5,
		"tab6" => $tab6,
		"tab7" => $tab7,  
	);
	$template->merge($nav_array);
	
	
	
	?>