<?php
	/* * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * *
	 * Easy set variables
	 */
	
	/* Array of database columns which should be read and sent back to DataTables. Use a space where
	 * you want to insert a non-database field (for example a counter or static image)
	 */
	$aColumns = array( 'O.id','O.order_number','R.cost','R.subtotal','R.total','R.discount','O.date','O.status');
	
	/* Indexed column (used for fast and accurate table cardinality) */
	$sIndexColumn = "O.order_number";
	
//	/* DB table to use */
//	$sTable = "ajax";
//	
//	/* Database connection information */
//	$gaSql['user']       = "root";
//	$gaSql['password']   = "";
//	$gaSql['db']         = "lis";
//	$gaSql['server']     = "localhost";
//	
//	/* REMOVE THIS LINE (it just includes my SQL connection user/pass) */
//	//include( $_SERVER['DOCUMENT_ROOT']."/datatables/mysql.php" );
//	
//	
//	/* * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * *
//	 * If you just want to use the basic configuration for DataTables with PHP server-side, there is
//	 * no need to edit below this line
//	 */
//	
//	/* 
//	 * MySQL connection
//	 */
//	$gaSql['link'] =  mysql_pconnect( $gaSql['server'], $gaSql['user'], $gaSql['password']  ) or
//		die( 'Could not open connection to server' );
//	
//	mysql_select_db( $gaSql['db'], $gaSql['link'] ) or 
//		die( 'Could not select database '. $gaSql['db'] );
	









	
	/* 
	 * Paging
	 */
	$sLimit = "";
	if ( isset( $_GET['iDisplayStart'] ) && $_GET['iDisplayLength'] != '-1' )
	{
		$sLimit = "LIMIT ".intval( $_GET['iDisplayStart'] ).", ".
			intval( $_GET['iDisplayLength'] );
	}
	
	
	/*
	 * Ordering
	 */
	$sOrder = "";
	if ( isset( $_GET['iSortCol_0'] ) )
	{
		$sOrder = "ORDER BY  ";
		for ( $i=0 ; $i<intval( $_GET['iSortingCols'] ) ; $i++ )
		{
			if ( $_GET[ 'bSortable_'.intval($_GET['iSortCol_'.$i]) ] == "true" )
			{
				$sOrder .= $aColumns[ intval( $_GET['iSortCol_'.$i] ) ]." ".
					($_GET['sSortDir_'.$i]==='asc' ? 'asc' : 'desc') .", ";
			}
		}
		
		$sOrder = substr_replace( $sOrder, "", -2 );
		if ( $sOrder == "ORDER BY" )
		{
			$sOrder = "";
		}
	}
	
	
	/* 
	 * Filtering
	 * NOTE this does not match the built-in DataTables filtering which does it
	 * word by word on any field. It's possible to do here, but concerned about efficiency
	 * on very large tables, and MySQL's regex functionality is very limited
	 */
	$sWhere = "";
	if ( isset($_GET['sSearch']) && $_GET['sSearch'] != "" )
	{
		$sWhere = " where (";
		for ( $i=0 ; $i<count($aColumns) ; $i++ )
		{
			$sWhere .= $aColumns[$i]." LIKE '%".mysql_real_escape_string( $_GET['sSearch'] )."%' OR ";
		}
		$sWhere = substr_replace( $sWhere, "", -3 );
		$sWhere .= ')';
	}
	
	/* Individual column filtering */
	for ( $i=0 ; $i<count($aColumns) ; $i++ )
	{
		if ( isset($_GET['bSearchable_'.$i]) && $_GET['bSearchable_'.$i] == "true" && $_GET['sSearch_'.$i] != '' )
		{
			if ( $sWhere == "" )
			{
				$sWhere = " AND ";
			}
			else
			{
				$sWhere .= " AND ";
			}
			$sWhere .= $aColumns[$i]." LIKE '%".mysql_real_escape_string($_GET['sSearch_'.$i])."%' ";
		}
	}
	$datefrom = $_GET['datefrom'];
	$dateto = $_GET['dateto'];
	$Where="";
	if($datefrom&&$dateto){
		if($sWhere){
			$Where .= " AND ";	
		}else{
			$Where .= " WHERE ";
		}	
		
		$Where.="DATE_FORMAT(O.date,'%Y-%m-%d') BETWEEN '$datefrom' AND '$dateto'";
	}
	
	
	/*
	 * SQL queries
	 * Get data to display
	 */
	/*$sQuery = "
		SELECT SQL_CALC_FOUND_ROWS `".str_replace(" , ", " ", implode("`, `", $aColumns))."`
		FROM   $sTable
		$sWhere
		$sOrder
		$sLimit
		";*/
	$status_code="<2";
	if(isset($GET["alias"])){
		$status_code="='".$GET["alias"]."'";
	}
	$sQuery_main = "SELECT SQL_CALC_FOUND_ROWS ".str_replace(" , ", " ", implode(", ", $aColumns))." FROM ".DBPREFIX."orders AS O LEFT JOIN  ".DBPREFIX."revenue AS R ON R.oid=O.id
	 $sWhere
	 $Where
	 ORDER BY O.order_number DESC
	 $sLimit
	 ";	
	
	
	/* Data set length after filtering */
	$sQuery = "
		SELECT FOUND_ROWS()
	";
	$sql->query( $sQuery );
	$aResultFilterTotal = $sql->fetch_array();
	$iFilteredTotal = $aResultFilterTotal[0];
	
	/* Total data set length */
	$sQuery = "
		SELECT COUNT(".$sIndexColumn.") FROM
		".DBPREFIX."orders AS O LEFT JOIN  ".DBPREFIX."revenue AS R ON R.oid=O.id
		$Where
	";
	$sql->query( $sQuery);
	$aResultTotal = $sql->fetch_array();
	$iTotal = $aResultTotal[0];
	
	
	/*
	 * Output
	 */
	$sEcho = "";
	if(isset($_GET['sEcho'])){
		$sEcho = $_GET['sEcho'];	
	} 
	
	$sql->query( $sQuery_main,$sQuery_main );
	
	while ( $data = $sql->fetch_array( $sQuery_main ) )
	{
		
		$edit = "";
		$delete = "";
		$status = "";
		
		
		$edit = '<a class="table-actions" href="orders-add_record.html?id='.$data["id"].'"><i class="icon-pencil"></i></a>';
		$delete = '<a class="table-actions" href="Javascript:void(0);del(\''.$data["id"].'\');"><i class="icon-trash"></i></a>';
		
		switch($data["status"]){
			case "2" :
				$status = '<div class="label label-danger pull-center">Cancelled</div>';
			break;
			case "1" :
				$status = '<div class="label label-success pull-center">Billed</div>';
			break;
			case "0" :
				$status = '<div class="label label-info pull-center">Unbilled</div>';
			break;
		}
		$outputs[] = array( 
			$data["order_number"],
			"Php ".number_format(floatval($data["cost"]), 2, '.', ','),
			"Php ".number_format(floatval($data["subtotal"]), 2, '.', ','),
			"Php ".number_format(floatval($data["discount"]), 2, '.', ','),
			"Php ".number_format(floatval($data["total"]), 2, '.', ','),
			$status,
			$data["date"],
			$edit."&nbsp;".$delete
		); 
		
		
		
	}
	$output["sEcho"]= intval($sEcho);
	$output["iTotalRecords"] = $iTotal;
	$output["iTotalDisplayRecords"] = $iTotal;
	$output["aaData"] = $outputs;

	echo json_encode( $output );
	exit;
?>