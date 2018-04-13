<?php
	session_start();
	require "./scripts/conn.php";
	
	
	
	if(!empty($_GET['section']))
	{
		$foto_count = array();
		$str = '';
		$query = 'select section_name, start_foto_no, end_foto_no from section_list where section_code = "'.$_GET['section'].'"';
		$result = $mysqldb->query($query);
		if($result->num_rows > 0)
		{
			list($section_name,$start_foto_no,$end_foto_no) = $result->fetch_row();
			$foto_count[] = array('section_code'=>$_GET['section'],'section_name'=>$section_name,'start_no'=>$start_foto_no,'end_no'=>$end_foto_no);
			$str = json_encode($foto_count);
		}
		else
		{
			$str = 'Can not get Photo No.';
		}
	}
	echo $str;
?>