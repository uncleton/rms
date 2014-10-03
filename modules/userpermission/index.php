<?php
if(!$global->set_permission(PERMIT_VIEW,true,$MODULEFOLDER)){
	header("Location: index.php");
	exit;
};

//	Set page permission
#################################################################################
$groupid=$global->numeric($form->GET("id"));

$form->POST("Submit");


	if($form->POST("Submit")){
		$query="SELECT SQL_BUFFER_RESULT SQL_CACHE id,title,padd,pedit,pdelete,pview,papprove FROM ".DBPREFIX."sections WHERE type!='links' AND type!='staticpages'  ORDER BY type ASC, title ASC";
		$sql->select($query);
		$data_num=$sql->fetch_rows();
		for($i=0;$i<$data_num;$i++){
			$data=$sql->fetch_array();
			//	add permission
			
			$add=$form->POST("add_".$data["id"]);
			$view=$form->POST("view_".$data["id"]);
			$edit=$form->POST("edit_".$data["id"]);
			$delete=$form->POST("delete_".$data["id"]);
			$approve=$form->POST("approve_".$data["id"]);
				
			//	add permission
			$padd=$data["padd"];
			$padd=explode(",",$padd);
			$peradd='';
			$coma="";		
			foreach($padd as $key => $val){		  
				if($val!=$groupid&&$val){
					$peradd.=$coma.$val;
					$coma=",";
				}	
			}
			if($add){
				$peradd.=$coma.$add;
			}
		
			//	edit permission
			$pedit=$data["pedit"];
			$pedit=explode(",",$pedit);
			$peredit='';
			$coma="";		
			foreach($pedit as $key => $val){		  
				if($val!=$groupid&&$val){
					$peredit.=$coma.$val;
					$coma=",";
				}	
			}
			if($edit){
				$peredit.=$coma.$edit;
			}
	
			//	delete permission
			$pdelete=$data["pdelete"];
			$pdelete=explode(",",$pdelete);
			$perdelete='';
			$coma="";		
			foreach($pdelete as $key => $val){		  
				if($val!=$groupid&&$val){
					$perdelete.=$coma.$val;
					$coma=",";
				}	
			}
			if($delete){
				$perdelete.=$coma.$delete;
			}
	
			//	view permission
			$pview=$data["pview"];
			$pview=explode(",",$pview);
			$perview='';
			$coma="";		
			foreach($pview as $key => $val){		  
				if($val!=$groupid&&$val){
					$perview.=$coma.$val;
					$coma=",";
				}	
			}
			if($view){
				$perview.=$coma.$view;
			}
	
			//	approve permission
			$papprove=$data["papprove"];
			$papprove=explode(",",$papprove);
			$perapprove='';
			$coma="";		
			foreach($papprove as $key => $val){		  
				if($val!=$groupid&&$val){
					$perapprove.=$coma.$val;
					$coma=",";
				}	
			}
			if($approve){
				$perapprove.=$coma.$approve;
			}
			
											
			//	UPDATE DB
			$query="UPDATE ".DBPREFIX."sections SET 
				padd='$peradd',
				pedit='$peredit',
				pdelete='$perdelete',
				pview='$perview',
				papprove='$perapprove' WHERE id='".$data["id"]."'";
			$sql->query($query,1);
			//$logs->userpermission_logs($query,LOGS_QRY_EDIT,$data["title"]."-".$data["id"],$groupid);
		}
		$sql->free_result();
		header("Location: userpermission.html?id={$groupid}");
	}	
		//	SET DISPLAY
	$query="SELECT SQL_BUFFER_RESULT SQL_CACHE id,title,padd,pedit,pdelete,pview,papprove,title,type FROM ".DBPREFIX."sections WHERE type!='links' AND type!='staticpages' ORDER BY type ASC, title ASC";
	$sql->select($query);
	$data_num=$sql->fetch_rows();
	for($i=0;$i<$data_num;$i++){
		$data=$sql->fetch_array();
		if($data["padd"]||$data["pedit"]||$data["pdelete"]||$data["pview"]||$data["papprove"]){
			
			$perapprove = "";
			$perview = "";
			$peradd = "";
			$peredit = "";
			$perdelete = "";
				
			//	add permission
			$padd=$data["padd"];		
			$padd=explode(",",$padd);
			if($data["padd"]){
				foreach($padd as $key => $val){
					if($val==$groupid){
						$peradd='checked="checked"';
					}
				}
			} else {
				$peradd='disabled="disabled"';		
			}		
			//	edit permission
			$pedit=$data["pedit"];		
			$pedit=explode(",",$pedit);
			if($data["pedit"]){
				foreach($pedit as $key => $val){
					if($val==$groupid){
						$peredit='checked="checked"';
					}
				}
			} else {
				$peredit='disabled="disabled"';		
			}		
			//	delete permission
			$pdelete=$data["pdelete"];		
			$pdelete=explode(",",$pdelete);
			if($data["pdelete"]){
				foreach($pdelete as $key => $val){
					if($val==$groupid){
						$perdelete='checked="checked"';
					}
				}
			} else {
				$perdelete='disabled="disabled"';		
			}		
			//	view permission
			$pview=$data["pview"];			
			$pview=explode(",",$pview);
			if($data["pview"]){
				foreach($pview as $key => $val){
					if($val==$groupid){
						$perview='checked="checked"';
					}
				}
			} else {
				$perview='disabled="disabled"';		
			}
	
			//	approve permission
			$papprove=$data["papprove"];			
			$papprove=explode(",",$papprove);
			if($data["papprove"]){
				foreach($papprove as $key => $val){
					if($val==$groupid){
						$perapprove='checked="checked"';
					}
				}
			} else {
				$perapprove='disabled="disabled"';		
			}
							
			$arrayl[$i]=array(
			"id" => $data["id"],
			"ap_check" => $perapprove,
			"v_check" => $perview,
			"a_check" => $peradd,
			"e_check" => $peredit,
			"d_check" => $perdelete,
			"groupid" => $groupid,
			"title" => $data["title"],
			"type" => $data["type"],
			);
			
			$perapprove='';
			$perview='';
			$peradd='';
			$peredit='';
			$perdelete='';
		}	
	}
	$sql->free_result();

	// Generate Templates	
	$template->loop($arrayl,"records");
	
	$array=array(
	"gid" => $groupid,
	);
	$template->merge($array);
?>