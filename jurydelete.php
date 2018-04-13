<?php
	session_start();
	require "./scripts/conn.php";
	$juryid=$_GET['jury_session_id'];
	$circuit_id = $_GET['circuit_session_id'];
	
	if(!empty($juryid) AND !empty($circuit_id))
	{
		$sql = 'update jurylogin set logout = 1, logout_time = now() where circuit_id = "'.$circuit_id.'" and judge_code = "'.$juryid.'"';
		//echo $sql;
		$result = $mysqldb->query($sql);
	}
	unset($_SESSION['judge']);
	unset($_SESSION['circuit']);
	unset($_SESSION['country']);
	session_destroy();
	echo $result;
	
?>