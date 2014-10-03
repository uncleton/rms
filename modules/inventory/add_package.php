<?php
//	Set page permission
#################################################################################
if(!$global->set_permission(PERMIT_VIEW,true,$MODULEFOLDER)){
	header("Location: index.php");
};

	//	Get Post Request
	$id = $global->numeric($form->REQUEST("id"));
	if(!$id){$id = $global->gen_id();}
	$name = $form->POST("name");
	$cid = $form->POST("categories");
	$price = $form->POST("price");
	$cost = $form->POST("cost");
	$packages = $form->POST("packages","ARRAY");
	$steps = $form->POST("steps","ARRAY");
	$photo = "";
	if(isset($_FILES["photo"]["name"])){
		$photo = $form->FILE("photo",IMAGE);
	}
	
	// Delete Photo
	if($form->GET("img_del")){
		$query="SELECT SQL_BUFFER_RESULT SQL_CACHE image FROM ".DBPREFIX."packages WHERE id='$id'";
		$sql->select($query);
		$data=$sql->fetch_array();
		if($data["image"]){
			$query="UPDATE ".DBPREFIX."packages SET image='' WHERE id='$id'";
			$sql->query($query);	
			@unlink("UserFiles/image/".$data["image"]);
			@unlink("UserFiles/image_thumb/".$data["image"]);
		}
		header("Location: inventory-add_package.html?id=".$id);
		exit;	
	}
	
	//	Add / Edit process
	if($name){
		
		// Upload Photo
		if($photo["name"]){
			$image_id = $global->gen_id();
			$image_name = $photo["name"];
			$image_filename_array = explode(".",$photo["name"]);
			$image_filename = $image_id.".".$image_filename_array[(count($image_filename_array)-1)];
			// Thumbnail
			
			$global->image_resize_upload($photo["tmp_name"],$image_filename,150,150,"UserFiles/image_thumb/");
			$global->image_resize_upload($photo["tmp_name"],$image_filename,300,300,"UserFiles/image/");
		}
		
		$query="SELECT SQL_BUFFER_RESULT SQL_CACHE id FROM ".DBPREFIX."packages WHERE id='$id'";
		$sql->select($query);
		$data=$sql->fetch_array();
		if($data["id"]){
			
			// Edit Account
			$query="UPDATE ".DBPREFIX."packages SET name='$name', price='$price', cid='$cid' WHERE id='$id'";
			if($sql->query($query)){
				
				// Update Image
				if($image_filename){
					$query="UPDATE ".DBPREFIX."packages SET image='$image_filename' WHERE id='$id'";
					$sql->query($query);
				}
				
				
				// Update Ingredients
				$delete = "DELETE FROM ".DBPREFIX."package_details WHERE pkid='$id'";
				$sql->query($delete);
				
				// Package Details
				if($packages){
					$coma = "";
					$ings = "";
					foreach($packages as $key => $val){
						$ing = explode(':',$val);
						
						$ings .= $coma."('$id','".$ing[0]."','".$ing[1]."')";
						$coma = ",";
					}
					
					
					$insert = "INSERT INTO ".DBPREFIX."package_details (pkid,pid,steps) VALUES ".$ings;
					$sql->query($insert);
					
					if($stck){
						$inventory->deduct_ingredient_stocks($id,$stck,"PREP");
					}
				}
				// Update Ingredients
				$delete = "DELETE FROM ".DBPREFIX."package_group_details WHERE pkid='$id'";
				$sql->query($delete);
				
				// Package step Details
				if($steps){
					$coma = "";
					$ings = "";
					foreach($steps as $key => $val){
						$ing = explode(':',$val);
						
						$ings .= $coma."('$id','".$ing[0]."','".$ing[1]."')";
						$coma = ",";
					}
					
					
					$insert = "INSERT INTO ".DBPREFIX."package_group_details (pkid,qty,sid) VALUES ".$ings;
					$sql->query($insert);
				}
				
			}
			
			header("Location: inventory-add_package.html?id=".$id);
			exit;
		} else {
			$query="INSERT INTO ".DBPREFIX."packages (
			id,name,price,cost,image,cid) VALUES (
			'$id','$name','$price','$cost','$image_filename','$cid')";
			if($sql->query($query)){
				// Update Ingredients
				$delete = "DELETE FROM ".DBPREFIX."package_details WHERE pid='$id'";
				$sql->query($delete);
				
				// Package Details
				if($packages){
					$coma = "";
					$ings = "";
					foreach($packages as $key => $val){
						$ing = explode(':',$val);
						
						$ings .= $coma."('$id','".$ing[0]."','".$ing[1]."')";
						$coma = ",";	
					}
					
					$insert = "INSERT INTO ".DBPREFIX."package_details (pkid,pid,steps) VALUES ".$ings;
					$sql->query($insert);
				}
				
				// Update Ingredients
				$delete = "DELETE FROM ".DBPREFIX."package_group_details WHERE pid='$id'";
				$sql->query($delete);
				
				// Package step Details
				if($steps){
					$coma = "";
					$ings = "";
					foreach($steps as $key => $val){
						$ing = explode(':',$val);
						
						$ings .= $coma."('$id','".$ing[0]."','".$ing[1]."')";
						$coma = ",";
					}
					
					
					$insert = "INSERT INTO ".DBPREFIX."package_group_details (pkid,qty,sid) VALUES ".$ings;
					$sql->query($insert);
				}
			}
	
			header("Location: inventory-add_package.html?id=".$id);
			exit;
		}
	} else {
		$query="SELECT SQL_BUFFER_RESULT SQL_CACHE * FROM ".DBPREFIX."packages WHERE id='$id'";
		$sql->select($query);
		$data=$sql->fetch_array();
		if($data["id"]){
			$id=$data["id"];
			$name=$data["name"];
			$price=$data["price"];
			$cost = $data["cost"];
			$photo = $data["image"];
			$cid = $data["cid"];
		}
	}
	
	// Stocks
	if(!$stocks){
		$stocks = '0';	
	}
	
	
	if($addon){
		$addon = 'checked="checked"';	
	}
	
	$cost = 0;
	
	//	Products
	$query="SELECT SQL_BUFFER_RESULT SQL_CACHE P.id,P.name,PD.steps FROM ".DBPREFIX."package_details AS PD JOIN ".DBPREFIX."products AS P ON P.id=PD.pid WHERE PD.pkid='$id' AND P.status='1' ORDER BY steps";
	$sql->select($query);
	$data_num=$sql->fetch_rows();
	$ing_list = "";
	$i = 1;
	while($data=$sql->fetch_array()){
		$ing_list .= '<div class="form-group"><label class="control-label col-md-2"></label><div class="col-md-3">'
	    .($i++).'. '.$data["qty"].' '.$data["name"].' - Step '.$data["steps"].' <input type="hidden" value="'.$data["id"].':'.$data["steps"].'" name="packages[]">'
        .'</div><div class="col-md-4"><a href="Javascript:void(0);" onclick="del(this)"><i class="icon-trash"></i> Delete</a></div></div>';
		
		
	}
	
	//	Steps
	$query="SELECT SQL_BUFFER_RESULT SQL_CACHE PD.qty,PD.sid FROM ".DBPREFIX."package_group_details AS PD JOIN ".DBPREFIX."packages AS P ON P.id=PD.pkid WHERE PD.pkid='$id' AND P.status='1' ORDER BY sid";
	$sql->select($query);
	$data_num=$sql->fetch_rows();
	$steps = "";
	$groups = "";
	$i = 1;
	while($data=$sql->fetch_array()){
		$steps .= '<div class="form-group"><label class="control-label col-md-2"></label><div class="col-md-3">'
	    .($i++).'. Step '.$data["sid"].' - '.$data["qty"].' pc/s <input type="hidden" value="'.$data["qty"].':'.$data["sid"].'" name="steps[]">'
        .'</div><div class="col-md-4"><a href="Javascript:void(0);" onclick="del_steps(this,\''.$data["sid"].'\')"><i class="icon-trash"></i> Delete</a></div></div>';
		
		$groups .= '<option value="'.$data["sid"].'">Step '.$data["sid"].'</option>';
	}
	
	

	//	Products
	$query="SELECT SQL_BUFFER_RESULT SQL_CACHE id,name,cost FROM ".DBPREFIX."products WHERE cid!='9999' and status='1' ORDER BY name";
	$sql->select($query);
	$data_num=$sql->fetch_rows();
	while($data=$sql->fetch_array()){
		$packages_array[$data["name"]] = $data["id"].":".$data["cost"];
	}
	$package_options = '<option value="0">Select a Product</option>';
	foreach($packages_array as $k => $v){
		$package_options .= '<option value="'.$v.'">'.$k.'</option>';
	}

	if(!isset($error)){
		$error = "";	
	}else{
		$error = "<ul style='padding: 0 15px;'>".$error."</ul>";	
	}
	
	if($photo){
		$photo = '<div class="col-md-4"><img src="./UserFiles/image_thumb/'.$photo.'" /> [ <a href="inventory-add_package.html?id='.$id.'&img_del=true">Delete</a> ]</div>';	
	}else{
		$photo = '<div class="col-md-4">
              <div class="fileupload fileupload-new" data-provides="fileupload">
                <div class="input-group">
                  <div class="form-control">
                    <i class="icon-file fileupload-exists"></i><span class="fileupload-preview"></span>
                  </div>
                  <div class="input-group-btn">
                    <a class="btn btn-default fileupload-exists" data-dismiss="fileupload" href="#">Remove</a><span class="btn btn-default btn-file"><span class="fileupload-new">Select file</span><span class="fileupload-exists">Change</span><input type="file" name="photo"></span>
                  </div>
                </div>
              </div>
            </div>';	
	}
	
	// Category
	$query="SELECT SQL_BUFFER_RESULT SQL_CACHE * FROM ".DBPREFIX."category WHERE section='1'";
	$sql->select($query);
	$data_num=$sql->fetch_rows();
	while($data=$sql->fetch_array()){
		$category_array[$data["category"]] = $data["id"];
	}
	
	$categories = '<option value="0">Select a Category</option>';
	foreach($category_array as $k => $v){
		if($v==$cid){
			$categories .= '<option value="'.$v.'" selected="selected">'.$k.'</option>';
		}else{
			$categories .= '<option value="'.$v.'">'.$k.'</option>';	
		}
	}
	
	
	$array=array(
	"id" => $id,
	"error" => $error,
	"photo" =>$photo,
	"cost" => $cost,
	"steps" => $steps,
	"groups" => $groups,
	"price" => $price,
	"addon" => $addon,
	"ing_list" => $ing_list,
	"name" => $name,
	"categories" => $categories,
	"packages" => $package_options,
	"url" => $_SERVER['REQUEST_URI'],
	);
	$template->merge($array);

?>