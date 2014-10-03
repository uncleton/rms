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
	$cid = '9999';//$form->POST("categories");
	$scid = $form->POST("subcategories");
	$price = $form->POST("price");
	$addon = $form->POST("addon");
	$cost = $form->POST("cost");
	$stck = $stock = $form->POST("stock");
	$ingredients = $form->POST("ingredients","ARRAY");
	$addons = $form->POST("addons","ARRAY");
	$photo = "";
	if(isset($_FILES["photo"]["name"])){
		$photo = $form->FILE("photo",IMAGE);
	}
	
	// Delete Photo
	if($form->GET("img_del")){
		$query="SELECT SQL_BUFFER_RESULT SQL_CACHE image FROM ".DBPREFIX."products WHERE id='$id'";
		$sql->select($query);
		$data=$sql->fetch_array();
		if($data["image"]){
			$query="UPDATE ".DBPREFIX."products SET image='' WHERE id='$id'";
			$sql->query($query);	
			@unlink("UserFiles/image/".$data["image"]);
			@unlink("UserFiles/image_thumb/".$data["image"]);
		}
		header("Location: inventory-add_prepared_ingredient.html?id=".$id);
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
		
		$query="SELECT SQL_BUFFER_RESULT SQL_CACHE id,stocks FROM ".DBPREFIX."products WHERE id='$id'";
		$sql->select($query);
		$data=$sql->fetch_array();
		if($data["id"]){
			
			// Edit Account
			$stock = ($stock + $data["stocks"]);
			$query="UPDATE ".DBPREFIX."products SET name='$name', cid='$cid', scid='$scid', price='$price', addon='$addon', cost='$cost', stocks='$stock' WHERE id='$id'";
			if($sql->query($query)){
				
				// Update Image
				if($image_filename){
					$query="UPDATE ".DBPREFIX."products SET image='$image_filename' WHERE id='$id'";
					$sql->query($query);
				}
				
				
				// Update Ingredients
				$delete = "DELETE FROM ".DBPREFIX."product_details WHERE pid='$id'";
				$sql->query($delete);
				
				// Ingredients
				if($ingredients){
					$coma = "";
					$ings = "";
					foreach($ingredients as $key => $val){
						$ing = explode(':',$val);
						
						$ings .= $coma."('$id','".$ing[0]."','".$ing[1]."','".$ing[2]."')";
						$coma = ",";
						
						
						// Deduct If Stocks added
							
					}
					
					
					$insert = "INSERT INTO ".DBPREFIX."product_details (pid,iid,qty,cost) VALUES ".$ings;
					$sql->query($insert);
					
					if($stck){
						$inventory->deduct_ingredient_stocks($id,$stck,"PREP");
					}
				}
				
				// Update Addons
				$delete = "DELETE FROM ".DBPREFIX."product_addons WHERE pid='$id'";
				$sql->query($delete);
				//Addons
				if($addons){
					$coma = "";
					$adds = "";
					foreach($addons as $key => $val){
						$add = explode(':',$val);
						
						$adds .= $coma."('$id','".$add[0]."','".$add[1]."')";
						$coma = ",";
						
						// Update Addon product Inventory
						if($stck){
							$inventory->deduct_product_stocks($add[0],$stck,"PREP");
						}
					}
					
					$insert = "INSERT INTO ".DBPREFIX."product_addons (pid,aid,cost) VALUES ".$adds;
					$sql->query($insert);
				}
			}
			
			header("Location: inventory-prepared_ingredient.html?id=".$id);
			exit;
		} else {
			$query="INSERT INTO ".DBPREFIX."products (
			id,name,cid,scid,price,addon,cost,image,stocks) VALUES (
			'$id','$name','$cid','$scid','$price','$addon','$cost','$image_filename','$stock' )";
			if($sql->query($query)){
				// Update Ingredients
				$delete = "DELETE FROM ".DBPREFIX."product_details WHERE pid='$id'";
				$sql->query($delete);
				
				// Ingredients
				if($ingredients){
					$coma = "";
					$ings = "";
					foreach($ingredients as $key => $val){
						$ing = explode(':',$val);
						
						$ings .= $coma."('$id','".$ing[0]."','".$ing[1]."','".$ing[2]."')";
						$coma = ",";	
					}
					
					$insert = "INSERT INTO ".DBPREFIX."product_details (pid,iid,qty,cost) VALUES ".$ings;
					$sql->query($insert);
					
					// Update Ingredeint Inventory
					if($stck){
						$inventory->deduct_ingredient_stocks($id,$stck,"PREP");
					}
				}
				
				// Update Addons
				$delete = "DELETE FROM ".DBPREFIX."product_addons WHERE pid='$id'";
				$sql->query($delete);
				//Addons
				if($addons){
					$coma = "";
					$adds = "";
					foreach($addons as $key => $val){
						$add = explode(':',$val);
						
						$adds .= $coma."('$id','".$add[0]."','".$add[1]."')";
						$coma = ",";
						
						// Update Addon product Inventory
						if($stck){
							$inventory->deduct_product_stocks($add[0],$stck,"PREP");
						}	
					}
					
					$insert = "INSERT INTO ".DBPREFIX."product_addons (pid,aid,cost) VALUES ".$adds;
					$sql->query($insert);
				}
			}
	
			header("Location: inventory-prepared_ingredient.html?id=".$id);
			exit;
		}
	} else {
		$query="SELECT SQL_BUFFER_RESULT SQL_CACHE * FROM ".DBPREFIX."products WHERE id='$id'";
		$sql->select($query);
		$data=$sql->fetch_array();
		if($data["id"]){
			$id=$data["id"];
			$name=$data["name"];
			$cid=$data["cid"];
			$scid=$data["scid"];
			$price=$data["price"];
			$addon = $data["addon"];
			$cost = $data["cost"];
			$photo = $data["image"];
			$stocks = $data["stocks"]." pcs";
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
	
	//	Ingredients
	$query="SELECT SQL_BUFFER_RESULT SQL_CACHE I.*,PD.qty,I.unit_price FROM ".DBPREFIX."product_details AS PD JOIN ".DBPREFIX."ingredients AS I ON I.id=PD.iid WHERE PD.pid='$id'";
	$sql->select($query);
	$data_num=$sql->fetch_rows();
	$ing_list = "";
	$i = 1;
	while($data=$sql->fetch_array()){
		$c = ($data["qty"]*$data["unit_price"]);
		$cost = $cost + $c;
		$ing_list .= '<div class="form-group"><label class="control-label col-md-2"></label><div class="col-md-3">'
	    .($i++).'. '.$data["qty"].' '.$data["unit"].' '.$data["name"].' (Php '.$c.') <input type="hidden" value="'.$data["id"].':'.$data["qty"].':'.$c.'" name="ingredients[]">'
        .'</div><div class="col-md-4"><a href="Javascript:void(0);" onclick="del(this,\''.$c.'\')"><i class="icon-trash"></i> Delete</a></div></div>';
	}
	
	//	Ingredients
	$query="SELECT SQL_BUFFER_RESULT SQL_CACHE P.* FROM ".DBPREFIX."product_addons AS PD JOIN ".DBPREFIX."products AS P ON P.id=PD.aid WHERE PD.pid='$id'";
	$sql->select($query);
	$data_num=$sql->fetch_rows();
	$add_list = "";
	$i = 1;
	while($data=$sql->fetch_array()){
		$cost = $cost + $data["cost"];
		$add_list .= '<div class="form-group"><label class="control-label col-md-2"></label><div class="col-md-3">'
	    .($i++).'. '.$data["name"].' (Php '.$data["cost"].') <input type="hidden" value="'.$data["id"].':'.$data["cost"].'" name="addons[]">'
        .'</div><div class="col-md-4"><a href="Javascript:void(0);" onclick="del(this,\''.$data["cost"].'\')"><i class="icon-trash"></i> Delete</a></div></div>';
	}
	

	//	Ingredients
	$query="SELECT SQL_BUFFER_RESULT SQL_CACHE * FROM ".DBPREFIX."ingredients";
	$sql->select($query);
	$data_num=$sql->fetch_rows();
	while($data=$sql->fetch_array()){
		$ingredients_array[$data["name"]] = $data["id"].":".$data["unit"].":".$data["unit_price"];
	}
	
	$ingredients = '<option value="0">Select Ingredient</option>';
	foreach($ingredients_array as $k => $v){
		$ingredients .= '<option value="'.$v.'">'.$k.'</option>';
	}
	//	Addons
	$addons_array = "";
	$query="SELECT SQL_BUFFER_RESULT SQL_CACHE * FROM ".DBPREFIX."products WHERE cid='9999'";
	$sql->select($query);
	$data_num=$sql->fetch_rows();
	while($data=$sql->fetch_array()){
		$addons_array[$data["name"]] = $data["id"].":".$data["cost"];
	}
	
	$addons = '<option value="0">Select Product</option>';
	if(is_array($addons_array)){
		foreach($addons_array as $k => $v){
			$addons .= '<option value="'.$v.'">'.$k.'</option>';
		}
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
	
	// Sub-Category
/*	$query="SELECT SQL_BUFFER_RESULT SQL_CACHE * FROM ".DBPREFIX."category WHERE section='2'";
	$sql->select($query);
	$data_num=$sql->fetch_rows();
	while($data=$sql->fetch_array()){
		$subcategory_array[$data["category"]] = $data["id"];
	}
	
	$subcategories = '<option value="0">Select a Sub-category</option>';
	foreach($subcategory_array as $k => $v){
		if($scid==$v){
			$subcategories .= '<option value="'.$v.'" selected="selected">'.$k.'</option>';
		}else{
			$subcategories .= '<option value="'.$v.'">'.$k.'</option>';
		}
	}*/

	if(!isset($error)){
		$error = "";	
	}else{
		$error = "<ul style='padding: 0 15px;'>".$error."</ul>";	
	}
	
	if($photo){
		$photo = '<div class="col-md-4"><img src="./UserFiles/image_thumb/'.$photo.'" /> [ <a href="inventory-add_prepared_ingredient.html?id='.$id.'&img_del=true">Delete</a> ]</div>';	
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
	
	$array=array(
	"id" => $id,
	"error" => $error,
	"photo" =>$photo,
	"cost" => $cost,
	"subcategories" => $subcategories,
	"categories" => $categories,
	"price" => $price,
	"stock" => $stock,
	"stocks" => $stocks,
	"addon" => $addon,
	"ing_list" => $ing_list,
	"add_list" => $add_list,
	"addons" => $addons,
	"name" => $name,
	"ingredients" => $ingredients,
	"url" => $_SERVER['REQUEST_URI'],
	);
	$template->merge($array);

?>