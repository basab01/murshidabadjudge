<?php
	//session_start();
	include_once './scripts/conn.php';
	include_once './get_row_no.php';
	$flag = 0;
	$sflag = 0;
	
	$no_of_rows = get_rows_no('circuit_master','id',$mysqldb);
	if($no_of_rows > 0)
	{
		$flag = 1;
	}
	$no_of_rows = get_rows_no('judge_master','id',$mysqldb);
	if($no_of_rows > 0 AND $flag == 1)
	{
		$flag = 2;
		
	}
	$no_of_rows = get_rows_no('circuit_judge','id',$mysqldb);
	if($no_of_rows > 0 AND $flag == 2)
	{
		$flag = 3;
	}
	if( $flag == 3 AND empty($_SESSION['circuit']))
	{
		
		$query = 'select * from circuit_judge_view';
		$result = $mysqldb->query($query);
		$no_rows = $result->num_rows;
		if($no_rows == 0)
		{
			$query = 'create view circuit_judge_view as select x.cname, x.ccode, y.judge_name, y.judge_code from circuit_master x, judge_master y, circuit_judge z where z.judge_id = y.id and z.circuit_id = x.id';
			$result = $mysqldb->query($query);
			if(!$result)
			{
				//echo 'Can not create view';
			}
			else
			{
				//echo 'View successfully created.';
			}
		}
		else
		{
			//echo 'View already exists.';
		}
	}
	else
	{
		if(empty($_SESSION['circuit']))
		{
			$query = 'drop view if exists circuit_judge_view';
			$result = $mysqldb->query($query);
		}
	}
	
	
	/*************** Creating circuit-section-photoNo view ********************/
	
	
	$no_of_rows = get_rows_no('section_list','section_id',$mysqldb);
	if($no_of_rows > 0)
	{
		$sflag = 1;
	}
	$no_of_rows = get_rows_no('section_circuit_foto','id',$mysqldb);
	if($no_of_rows > 0 AND $sflag == 1 )
	{
		$sflag = 2;
	}
	if($sflag == 2 AND empty($_SESSION['circuit']))
	{
		$query = 'select * from section_circuit_foto_view';
		$result = $mysqldb->query($query);
		$no_rows = $result->num_rows;
		if($no_rows == 0)
		{
			$query = 'create view section_circuit_foto_view as select cname,ccode,section_name,section_code,start_no,end_no from section_circuit_foto inner join circuit_master on circuit_master.id = section_circuit_foto.circuit_id inner join section_list on section_list.section_id = section_circuit_foto.section_id';
			$result = $mysqldb->query($query);
			if(!$result)
			{
				//echo 'Can not create view';
			}
			else
			{
				//echo 'View successfully created.';
			}
		}
		else
		{
			//echo 'View already exists.';
		}
	}
	else
	{
		if(empty($_SESSION['circuit']))
		{
			$query = 'drop view if exists section_circuit_foto_view';
			$result = $mysqldb->query($query);
		}
	}
	
	
	