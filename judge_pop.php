<?php
	include './scripts/conn.php';
	$get_circuit_code = $_GET['ccode'];
	//$query = 'select x.cname, y.judge_name, y.judge_code from circuit_master x, judge_master y, circuit_judge z where z.judge_id = y.id and z.circuit_id = x.id and z.circuit_id = '.$get_judgeid.'';
	$query = 'select * from circuit_judge_view where ccode = "'.$get_circuit_code.'"';
	$result = $mysqldb->query($query);
	
	$circuit_array = array();
	while(list($circuit,$ccode,$jname,$jcode) = $result->fetch_row())
	{
		$judge_array[] = array('circuit'=>$circuit, 'ccode'=>$ccode, 'judge'=>$jname, 'judge_code'=>$jcode);
	}
	
	$str = '';
	$str = json_encode($judge_array);
	echo $str;