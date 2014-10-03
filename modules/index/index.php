<?php

$billout = $form->POST("billid");
$subtotal = $form->POST("subtotal");
$vat = $form->POST("vat");
$total = $form->POST("total");
$discount = $form->POST("discount");
$discount_name = $form->POST("discount_name","ARRAY");
$cost = $form->POST("cost");
$cancel = $form->GET("c");
$cancel_order = $form->GET("co");

if($cancel_order){
	$update = "Update ".DBPREFIX."orders SET status='2' WHERE id='$cancel_order'";
	$sql->query($update);
	
	// Clear Orders on Billout
	//$update = "Update ".DBPREFIX."order_details SET status='2' WHERE oid='$cancel_order'";
	//$sql->query($update);	
	
	header("Location: ./");
	exit;
}

if($cancel){
	$cids = explode(",",$cancel);
	$glue="";
	$c="";
	foreach($cids as $k => $ids){
		$c .= $glue."id='$ids'";
		$glue = " OR ";
	}
	$update = "Update ".DBPREFIX."order_details SET status='2' WHERE $c";
	$sql->query($update);
	
	header("Location: ./");
	exit;
}

if($billout){
	$select = "SELECT id FROM ".DBPREFIX."revenue WHERE oid='$billout'";
	$sql->query($select);
	$data = $sql->fetch_array();
	if(!$data["id"]){
		// Deduct Stocks on Ingredients and Products
		$inventory->orderid=$billout;
		$select = "SELECT pid,oid FROM ".DBPREFIX."order_details WHERE oid='$billout'";
		$sql->query($select,$select);
		while($data = $sql->fetch_array($select)){
			if($data["pid"]){
				$inventory->deduct_product_stocks($data["pid"],1,'SOLD');
			}
		}
	
		// Save Revenue
		if($discount){
			$amount = ($amount-str_replace("-","",$discount));
		}
		$disc_name="";
		if($discount_name){
			$disc_name = implode(",",$discount_name);
		}
		
		$insert = "INSERT INTO ".DBPREFIX."revenue (oid,cost,subtotal,vat,total,discount,discount_name) VALUES ('$billout','$cost','$subtotal','$vat','$total','$discount','$disc_name')";
		$sql->query($insert,$insert);
		
		$update = "Update ".DBPREFIX."orders SET status='1' WHERE id='$billout'";
		$sql->query($update);
		
		$update = "Update ".DBPREFIX."order_details SET status='1' WHERE oid='$billout' AND status='0'";
		$sql->query($update);
	}
	header("Location: ./");
	exit;
}


// Tables / DINE-IN
$tables = $config["TABLES"];
$takeout = 0;
$n=0;
for($i=1; $i<=($tables+$takeout); $i++){
	
	if($i<=$tables){
		$title = "Table ".$i;
		$tquery = "t".$i;		
	}else{
		$n++;
		$title = "Take Out ".$n;
		$tquery = "g".$n;	
	}
	
	// Products
	$select = "SELECT P.*,OD.oid,OD.id as odid FROM ".DBPREFIX."orders AS O JOIN ".DBPREFIX."order_details AS OD ON O.id=OD.oid JOIN ".DBPREFIX."products AS P ON P.id=OD.pid WHERE (OD.status!='2' AND OD.status!='3') AND O.tbl='$tquery' AND O.status='0' AND OD.type='1'";
	$sql->query($select);
	$o="";
	$oid="";
	while($data = $sql->fetch_array()){
		$o .= '<li><label><span>'.$data["name"].'</span> <button class="btn btn-xs btn-danger-outline pull-right" onclick="cancel(\''.$data["odid"].'\')">Cancel</button><span style="display:block;clear:both"></span></label></li>';
		$oid=$data["oid"];
	}
	// Packages
	$select = "SELECT P.*,OD.oid,OD.id as odid,OD.pid,OD.grp FROM ".DBPREFIX."orders AS O JOIN ".DBPREFIX."order_details AS OD ON O.id=OD.oid JOIN ".DBPREFIX."packages AS P ON P.id=OD.pid WHERE (OD.status!='2' AND OD.status!='3') AND O.tbl='$tquery' AND O.status='0' AND OD.type='2'";
	$sql->query($select,$select);
	while($data = $sql->fetch_array($select)){
		
		// Products under pages
		$select2 = "SELECT P.name,OD.id FROM ".DBPREFIX."order_details AS OD JOIN ".DBPREFIX."package_details AS PD ON PD.pid=OD.pid JOIN ".DBPREFIX."products AS P ON P.id=PD.pid WHERE OD.oid='".$data["oid"]."' AND OD.type='3' AND PD.pkid='".$data["pid"]."' AND OD.grp='".$data["grp"]."' GROUP BY OD.seq";
		$sql->query($select2,$select2);
		$pkg_prod = "";
		$comma = "";
		$coids = array();
		while($data2 = $sql->fetch_array($select2)){
			$pkg_prod .= $comma .$data2["name"];
			array_push($coids,$data2["id"]);
			$comma = ", ";
		}
		$o .= '<li><label><span>'.$data["name"].'</span> <button class="btn btn-xs btn-danger-outline pull-right" onclick="cancel(\''.$data["odid"].",".implode(",",$coids).'\')">Cancel</button><br><span style="font-size:10px;">'.$pkg_prod.'</span></label></li>';
		$oid=$data["oid"];
		
	}
	if(!$oid){
		$oid = $global->gen_id();
	}
	if(!$o){
		//$o = '<li><label>This table is available</label></li>';	
	}
	
	
	$array_tables[] = array(
		"table" => $title,
		"tquery" => $tquery,
		"order" => $o,
		"oid" => $oid
	);
}
$template->loop($array_tables,"orders");

$fptbl=1;
$totbl=1;
$dltbl=1;
$array_table_others="";

$select = "SELECT id,tbl,customer_name,phone,address,pickup_time FROM ".DBPREFIX."orders WHERE tbl NOT LIKE 't%' AND status='0'";
$sql->query($select,$select);
while($data = $sql->fetch_array($select)){
	// Products
	$select2 = "SELECT P.*,OD.oid,OD.id as odid,O.tbl FROM ".DBPREFIX."orders AS O JOIN ".DBPREFIX."order_details AS OD ON O.id=OD.oid JOIN ".DBPREFIX."products AS P ON P.id=OD.pid WHERE (OD.status!='2' AND OD.status!='3') AND O.id='".$data["id"]."' AND O.status='0' AND OD.type='1'";
	$sql->query($select2,$select2);
	
	$o="";
	$oid="";
	while($data2 = $sql->fetch_array($select2)){
		$o .= '<li><label><span>'.$data2["name"].'</span> <button class="btn btn-xs btn-danger-outline pull-right" onclick="cancel(\''.$data2["odid"].'\')">Cancel</button><span style="display:block;clear:both"></span></label></li>';
		$oid=$data2["oid"];
	}
	
	// Packages
	$select2 = "SELECT P.*,OD.oid,OD.id as odid,OD.pid,OD.grp FROM ".DBPREFIX."orders AS O JOIN ".DBPREFIX."order_details AS OD ON O.id=OD.oid JOIN ".DBPREFIX."packages AS P ON P.id=OD.pid WHERE (OD.status!='2' AND OD.status!='3') AND O.id='".$data["id"]."' AND O.status='0' AND OD.type='2'";
	$sql->query($select2,$select2);
	while($data2 = $sql->fetch_array($select2)){
		
		// Products under pages
		$select3 = "SELECT P.name,OD.id FROM ".DBPREFIX."order_details AS OD JOIN ".DBPREFIX."package_details AS PD ON PD.pid=OD.pid JOIN ".DBPREFIX."products AS P ON P.id=PD.pid WHERE OD.oid='".$data2["oid"]."' AND OD.type='3' AND PD.pkid='".$data2["pid"]."' AND OD.grp='".$data2["grp"]."' GROUP BY OD.seq";
		$sql->query($select3,$select3);
		$pkg_prod = "";
		$comma = "";
		$coids = array();
		while($data3 = $sql->fetch_array($select3)){
			$pkg_prod .= $comma .$data3["name"];
			array_push($coids,$data3["id"]);
			$comma = ", ";
		}
		$o .= '<li><label><span>'.$data2["name"].'</span> <button class="btn btn-xs btn-danger-outline pull-right" onclick="cancel(\''.$data2["odid"].",".implode(",",$coids).'\')">Cancel</button><br><span style="font-size:10px;">'.$pkg_prod.'</span></label></li>';
		$oid=$data2["oid"];
		
	}
	
	// Get Table Que
	if(preg_match("/d/i",$data["tbl"])){
		$title = "Delivery ".$dltbl;
		$dltbl++;
	}
	if(preg_match("/p/i",$data["tbl"])){
		$title = "Pick-up ".$fptbl;
		$fptbl++;
	}
	if(preg_match("/g/i",$data["tbl"])){
		$title = "Take Out ".$totbl;
		$totbl++;
	}
	$customer="";
	if($data["customer_name"]){
		$customer = 'Customer: <a data-content="&lt;div&gt;&lt;b&gt;Phone:&lt;/b&gt;'.$data["phone"].'&lt;br&gt;&lt;b&gt;Address:&lt;/b&gt;'.$data["address"].'&lt;br&gt;&lt;b&gt;Pickup Time:&lt;/b&gt;'.$data["pickup_time"].'&lt;/div&gt;" data-original-title="'.$data["customer_name"].'" data-toggle="popover-top" class="popover-top" style="width:45%;cursor:pointer;" title="">'.$data["customer_name"].' &nbsp;<i class="icon-comment"></i></a> ';	
	}
	$array_table_others[] = array(
		"table" => $title,
		"tquery" => $data["tbl"],
		"order" => $o,
		"oid" => $data["id"],
		"customer" => $customer
	);
	
}
$template->loop($array_table_others,"orders-other");

// Discounts

$select = "SELECT * FROM ".DBPREFIX."discounts";
$sql->query($select);
$discount = '<option value="0">Select Discount</option>';
while($data = $sql->fetch_array()){	
   $discount .= '<option value="'.$data["discount"].'">'.$data["name"].'</option>';
}

$array = array(
	"to-oid" => $global->gen_id(),
	"fp-oid" => $global->gen_id(),
	"dl-oid" => $global->gen_id(),
	"fp-tbl" => "p".$fptbl,
	"dl-tbl" => "d".$dltbl,
	"to-tbl" => "g".$totbl,
	"discount" => $discount
);
$template->merge($array);

?>