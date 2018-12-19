<?php
	session_start();
	require "./scripts/conn.php";
	
	$st1='';
	
	
	if(!empty($_GET['section']) AND !empty($_GET['image_no']))
	{
		
		$query = 'select id from images x inner join section_list y on x.theme_id=y.section_id where y.section_code="'.$_GET['section'].'"';
		$result = $mysqldb->query($query);
		$list = [];
		
		while($row = $result->fetch_array())
		{
			$list[] = $row['id'];
		}
		
		$total_img = count($list);
		
		$glist = [];
		
		if (false !== $key = array_search($_GET['image_no'], $list)) {
			$glist[] = array ( 'total_img'=>$total_img, 'present_no'=>$key );
		} else {
		    $glist[] = array ( 'total_img'=>0, 'present_no'=>0 );
		}
		
		$st1 = json_encode($glist);
	}
	
	echo $st1;
	
	
?>