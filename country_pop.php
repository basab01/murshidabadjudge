<?php
	include './scripts/conn.php';
	$query = 'select country_id,country from country_master';
	$result = $mysqldb->query($query);
	
	$country_array = array();
	while(list($country_id,$country_name) = $result->fetch_row())
	{
		$country_array[] = array('country_code'=>$country_id, 'country_name'=>htmlentities($country_name,ENT_QUOTES));
	}
	//print_r($country_array);
	$str = '';
	$str = json_encode($country_array);
	echo $str;
	