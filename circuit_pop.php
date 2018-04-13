<?php
	include './scripts/conn.php';
	$query = 'select id,cname,ccode from circuit_master';
	$result = $mysqldb->query($query);
	
	$circuit_array = array();
	while(list($id,$circuit,$circuit_code) = $result->fetch_row())
	{
		$circuit_array[] = array('circuit_code'=>$circuit_code, 'circuit'=>$circuit);
	}
	
	$str = '';
	$str = json_encode($circuit_array);
	echo $str;
	