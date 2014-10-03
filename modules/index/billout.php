<?php

$billout = $form->GET("id");
// Product
$select = "SELECT O.customer_name,O.phone,O.address,O.pickup_time,P.name,P.price,P.cost,O.tbl,O.order_number FROM ".DBPREFIX."orders AS O JOIN ".DBPREFIX."order_details AS OD ON O.id=OD.oid JOIN ".DBPREFIX."products AS P ON P.id=OD.pid WHERE O.id='$billout' AND OD.type='1' AND OD.status!='2'";
$sql->query($select);
$orders = "";
$customer_name = "";
$order_number = "";
$phone = "";
$address = "";
$pickup_time = "";
$total=0;
$cost=0;
$tbl = "";
while($data = $sql->fetch_array()){
	$orders .= '<li><span class="pull-left">'.$data["name"].'</span><span class="pull-right" style="width:100px;">Php '.less_vat($data["price"]).'</span><span style="clear:both; display:block;"></span></li>'; 
	$customer_name = $data["customer_name"];
	$phone = $data["phone"];
	$address = $data["address"];
	$pickup_time = $data["pickup_time"];
	$total = $total+$data["price"];
	$cost = $cost+$data["cost"];
	$tbl = $data["tbl"];
	$order_number = $data["order_number"];
}

// Package
$pkg = false;
$select = "SELECT P.price,P.name,O.tbl,O.order_number FROM ".DBPREFIX."orders AS O JOIN ".DBPREFIX."order_details AS OD ON O.id=OD.oid JOIN ".DBPREFIX."packages AS P ON P.id=OD.pid WHERE OD.oid='$billout' AND OD.type='2' AND OD.status!='2'";
$sql->query($select);
while($data = $sql->fetch_array()){
	$orders .= '<li><span class="pull-left">'.$data["name"].'</span><span class="pull-right" style="width:100px;">Php '.less_vat($data["price"]).'</span><span style="clear:both; display:block;"></span></li>'; 	
	$total = $total+$data["price"];
	$pkg = true;
	$tbl = $data["tbl"];
	$order_number = $data["order_number"];
}
// Get Cost of Package Products
if($pkg){
	$select = "SELECT sum(P.cost) as cost FROM ".DBPREFIX."order_details AS OD JOIN pfi_products AS P ON P.id=OD.pid WHERE type ='3' and oid='$billout'";
	$sql->query($select);
	$data = $sql->fetch_array();
	$cost = $cost+$data["cost"];
}


if($order_number){

	$title="";
	if($customer_name){
		print '<h1>'.$customer_name.'</h1>';
		print '<p><b>Phone:</b> '.$phone.'<br><b>Address:</b> '.$address.'<br><b>Pickup Time:</b> '.$pickup_time.'</p>';
	}else{
		if(preg_match("/d/i",$tbl)){
			$title = str_replace("d","Delivery ",$tbl);
		}
		if(preg_match("/p/i",$tbl)){
			$title = str_replace("p","Pick-up ",$tbl);
		}
		if(preg_match("/g/i",$tbl)){
			$title = str_replace("g","Take Out ",$tbl);
		}
		if(preg_match("/t/i",$tbl)){
			$title = str_replace("t","Table ",$tbl);
		}
		print '<span style="float:right; font-weight:bold;">Order No. #'.$order_number.'</span><h1>'.$title.'</h1>';
	}
	print '<p><b>Order Summary</b><ul style="list-style: none outside none;padding:0 20px;">'.$orders.'</ul>
			
			';
			// Discounts
	print	  '
			<hr>
			<div style="padding:0 20px 0 0; clear:both"><span class="pull-left"><b>Sub Total</b></span><span class="pull-right">Php '.$total.'</span></div>
			
			<div style="padding:5px 20px 5px 0px; clear:both">	
			  <span class="pull-left"><b>Less discounts</b> &nbsp; &nbsp; <a href="#fancybox-example" class="icon-plus-sign-alt fancybox" onclick="hide_modal();"></a></span>
			  <span id="discount" class="pull-right"><span id="np">Php 0.00</span></span><div style="clear:both"></div></div>
			
			<div style="padding:0 20px 0 0; clear:both"><span class="pull-left"><b>VAT 12%</b></span><span class="pull-right" id="vat">Php '.vat($total).'</span></div>
			
			<div style="clear:both"></div>
			<hr>
			<div style="padding:0 20px  0 0; clear:both"><span class="pull-left"><b>Total Payment</b></span><span class="pull-right" id="total">Php '.$total.'</span></div>
			</p>';
			
	print '<input value="'.vat($total).'" name="vat" type="hidden" id="vat-form"><input value="'.less_vat($total).'" name="subtotal" type="hidden" id="subtotal-form"><input value="'.$total.'" name="total" type="hidden" id="total-form"><input value="0" name="discount" type="hidden" id="discount-form"><input value="'.$billout.'" name="billid" type="hidden" id="billid"><input value="'.round($cost,2).'" name="cost" type="hidden" id="cost">';

}else{
	print '<p>No existing orders found!</p>';
}


function less_vat($amt){
	return $amt;
	//return round(($amt/1.12),2);
}
function vat($amt){
	return 0;
	//return ($amt-less_vat($amt));
}

exit;
?>
