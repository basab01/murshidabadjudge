<?php

session_start();
	require_once "./scripts/conn.php";
	
	function admin_marks ( $circuit_code = '', $section_code = '', $photoid = '', $type='', $mysqldb )
	{
		if ( $type == 'Selection' )
		{
		$marks = [];
		
			$sql = 'select * from selection_marks_view 
				where ccode= "'.$circuit_code.'" and 
				section_code = "'.$section_code.'" and 
				image_srl_no = '.$photoid;
				//echo $sql;
				
			$result = $mysqldb->query($sql);
		
			while ( $row = $result->fetch_array ( MYSQLI_ASSOC ))
			{
				$marks[] = array('judge'=> $row['judge_code'],'marks'=>$row['selection_marks'] );
			}
		}
		else if ( $type == 'Award' )
		{
			$marks = [];
		
			$sql = 'select * from award_marks_view 
				where ccode= "'.$circuit_code.'" and 
				section_code = "'.$section_code.'" and 
				image_srl_no = '.$photoid;
				
			$result = $mysqldb->query($sql);
		
			while ( $row = $result->fetch_array ( MYSQLI_ASSOC ))
			{
				$marks[] = array('judge'=> $row['judge_code'],'marks'=>$row['award_marks'] );
			}
		}
		
		
		return $marks;
	}
	
	
	
	
	if ( !empty ( $_GET['circuitcode'] ) AND !empty ( $_GET['sectioncode'] ) AND !empty ( $_GET['imageid'] ) )
	{
		$admarks = []; $mmk = [];
		$admarks = admin_marks ( $_GET['circuitcode'], $_GET['sectioncode'], $_GET['imageid'], $_GET['type'], $mysqldb );
		if ( count($admarks) > 0 )
		{
			$mmk[] = array ( 'count'=> count($admarks), 'data' => $admarks );
		}
		$mmk = json_encode ( $mmk );
		
	}
	
	
	
	echo $mmk;
	
?>