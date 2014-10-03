<?php
	$array = array(
		"year" => date("Y")
	);
	$template->merge($array);
	echo $template->output;
	exit;
?>