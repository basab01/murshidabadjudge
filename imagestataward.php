<?php
	session_start();
	require "./scripts/conn.php";
	
	$st1='';
	
	
	if(!empty($_GET['section']) AND !empty($_GET['image_no']) AND !empty ($_GET['circuitid']))
	{
		
		$query = 'select x.id from images_award x inner join 
					section_list y on x.theme_id=y.section_id inner join circuit_master z on x.circuit_id=z.id 
					where y.section_code="'.$_GET['section'].'" and 
					z.ccode="'.$_GET['circuitid'].'"';
					
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
		    $glist[] = array ( 'Nothing selected' );
		}
		
		$st1 = json_encode($glist);
	}
	else
	{
		$st1=json_encode('Nothing');
	}
	
	echo $st1;
	
	
?>