<?php

class globalfunctions {
	
	function gen_id(){
		return time().rand(100,999);
	}
	
	function format_date($date){
		if($date){
			$d = str_split($date,2);
			$time = '';
			if($d[4] && $d[6] && $d[5]){
				$time = "{$d[4]}:{$d[5]}:{$d[6]}";	
			}else{
				$time = "00:00:00";		
			}
			return "{$d[0]}{$d[1]}-{$d[2]}-{$d[3]} ".$time; 
		}
	}
}

$global = new globalfunctions();
?>