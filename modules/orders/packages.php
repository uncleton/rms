<?php
//	Set page permission
#################################################################################
if(!$global->set_permission(PERMIT_VIEW,true,$MODULEFOLDER)){
	header("Location: index.php");
};

	//	Get Post Request
	$id = $global->numeric($form->REQUEST("id"));
	
	
	$select = "SELECT sid,qty FROM ".DBPREFIX."package_group_details WHERE pkid='$id' ORDER BY sid";
	$sql->query($select,$select);
	while($data = $sql->fetch_array($select)){
		
		$output .= '<div style="clear:both; display:inline-block"><h4> Step #'.$data["sid"].': Select '.$data["qty"].' Products</h4><ul rel="'.$data["qty"].'">';	
		
		//	Add / Edit process
		$select_dets = "SELECT PD.steps,P.id,P.name,P.image FROM ".DBPREFIX."package_details AS PD JOIN ".DBPREFIX."products AS P ON PD.pid=P.id WHERE PD.pkid = '$id' AND PD.steps='".$data["sid"]."'  ORDER BY P.name";
		$sql->query($select_dets,$select_dets);
		while($data2 = $sql->fetch_array($select_dets)){
			$image = "no-image.jpg";
			if($data2["image"]){
				$image = $data2["image"];	
			}
			$output .= '<li id="'.$data2["id"].'" class="inactive"> <img src="UserFiles/image/'.$image.'" style="width:100px; height:100px;"  />
                                    <span class="name">'.$data2["name"].'</span></li>';	
		}
		$output .= '</ul></div>';
	}
echo $output;
exit;
?>