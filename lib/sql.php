<?php
class sqlfunctions {
	var $resource;
	var $conn;
	var $key;
	var $table;
	var $debugQuery=false;
	var $queryID=false;
	
	function __construct(){
		$this->conn[DBHOST] = mysql_connect (DBHOST, DBUSER, DBPASS) or die ("Cannot connect to Database!");
		mysql_select_db (DBNAME) or die ("Cannot Database not available"); 
	}
	
	function query($query,$queryID=0,$queryDebugTag="QUERY STATEMENT"){
		
		$this->debug($queryDebugTag,$query);
		
		if($this->resource[$queryID] = mysql_query($query,$this->conn[DBHOST])){
			return true;
		}
	}
	
	// Insert function will Check if record with given keys does not exist then insert
	function insert($array,$queryDebugTag="INSERT STATEMENT"){

		if(!$this->table){
			$this->error("\$sql->table Must be defined first before \$sql->insert() method call");
		}
		if(!$this->key){
			$this->error("\$sql->key Must be declared with atleast 1 key pairs, before \$sql->insert() method call e.g. array(\"field_name\"=>\$value)");
		}
		if(is_array($array)){
			foreach($array as $key => $val){
				$keys .= $key_comma."$key";
				$vals .= $key_comma."'$val'";
				$key_comma = ",";
			}
			// Get key value pairs
			reset($this->key);
			while( key($this->key) !== NULL ){
				$wherekeys .= $and.key($this->key)." = '".current($this->key)."'";
				$and = " and ";
				next($this->key);
			}
			// Check if Record id exist
			$select = "SELECT count(1) as count FROM ".DBPREFIX."{$this->table} WHERE $wherekeys";
			$this->select($select); 
			$data = $this->fetch_array();
			if(!$data["count"]){				
				$sql = "insert into ".DBPREFIX."{$this->table} ($keys) VALUES ($vals)";
				
				$this->debug($queryDebugTag,$sql);
				
				if(@mysql_query($sql,$this->conn[DBHOST])){
					return true;
				} else {
					$this->error("[SQL STATEMENT ERROR] $sql",1);
				}
			} else {
				return false;
			}
		} else {
			$this->error("\$sql->insert() values must be in array format e.g. array( \"field\" => \$value )");
		}
	}
	
	
	// Select function almost same as query function but only accept select statements
	function select($query,$queryID=0,$queryDebugTag="SELECT STATEMENT"){
		if(preg_match("/select/i",$query)){
		
			if($this->query($query,$queryID,$queryDebugTag)){
				return true;
			}
		} else {
			$this->error("\$sql->select() accepts on Select Statements");
		}
	}
	
	function update($array,$queryDebugTag="UPDATE STATEMENT"){
		if(!$this->table){
			$this->error("\$sql->table Must be defined first before \$sql->insert() method call");
		}
		if(!$this->key){
			$this->error("\$sql->key Must be declared with atleast 1 key pairs, before \$sql->insert() method call e.g. array(\"field_name\"=>\$value)");
		}	
		if(is_array($array)){
			foreach($array as $key => $val){
				$updatekeyvals .= $updatekey_comma."$key='$val'";
				$updatekey_comma = ",";
			}
			// Get key value pairs
			reset($this->key);
			while( key($this->key) !== NULL ){
				$wherekeys .= $and.key($this->key)." = '".current($this->key)."'";
				$and = " and ";
				next($this->key);
			}
			// Check if Record id exist
			$sql = "UPDATE ".DBPREFIX."{$this->table} SET $updatekeyvals WHERE $wherekeys";
			
			$this->debug($queryDebugTag,$sql);
			
			if(@mysql_query($sql,$this->conn[DBHOST])){
				return true;
			}
		} else {
			$this->error("\$sql->update(), values must be in array format e.g. array( \"field\" => \$value )");
		}
	}

	function fetch_rows($queryID=0){
		return @mysql_num_rows($this->resource[$queryID]);
	}

	function fetch_array($queryID=0){
		return @mysql_fetch_array($this->resource[$queryID]);
	}
	
	function free_result($queryID=0){
		return @mysql_free_result($this->resource[$queryID]);
	}
	
	function error($message,$type=0){
		if($type){
			print "WARNING: $message";
		} else {
			print "WARNING: $message " . mysql_error();
		}
		exit;
	}
	
	function debug($title,$query){
		if($this->debugQuery){
			print "QUERY DEBUG: [$title] $query\n<br>";
		}
		
	}
	
	function debugClose(){
		$this->debugQuery=false;
	}					
}

$sql = new sqlfunctions;

?>