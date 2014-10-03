<?php
	/* * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * *
	 * Easy set variables
	 */
	
	/* Array of database columns which should be read and sent back to DataTables. Use a space where
	 * you want to insert a non-database field (for example a counter or static image)
	 */
	$aColumns = array( 'id','name','price');
	
	/* Indexed column (used for fast and accurate table cardinality) */
	$sIndexColumn = "id";
	
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
		$sWhere = " WHERE (";
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
				$sWhere = " WHERE ";
			}
			else
			{
				$sWhere .= " AND ";
			}
			$sWhere .= $aColumns[$i]." LIKE '%".mysql_real_escape_string($_GET['sSearch_'.$i])."%' ";
		}
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
	$sQuery_main = "SELECT SQL_CALC_FOUND_ROWS ".str_replace(" , ", " ", implode(", ", $aColumns))." FROM ".DBPREFIX."packages  
	 $sWhere
	 $sOrder
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
		SELECT COUNT(".$sIndexColumn.")
		FROM ".DBPREFIX."packages
		$sWhere
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
	$outputs="";
	while ( $data = $sql->fetch_array( $sQuery_main ) )
	{
		
		$edit = "";
		$delete = "";
		
		
		$edit = '<a class="table-actions" href="inventory-add_package.html?id='.$data["id"].'"><i class="icon-pencil"></i></a>';
		$delete = '<a class="table-actions" href="inventory-packages.html?del='.$data["id"].'"><i class="icon-trash"></i></a>';
		
		
		$outputs[] = array( 
			$data["name"],
			"Php ".$data["price"],
			$edit." ".$delete
		); 
		
		
		
	}
	$output["sEcho"]= intval($sEcho);
	$output["iTotalRecords"] = $iTotal;
	$output["iTotalDisplayRecords"] = $iTotal;
	$output["aaData"] = $outputs;

	echo json_encode( $output );
	exit;
?>