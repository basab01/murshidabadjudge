<?php
require "../scripts/conn.php";

	$circuits = array ( 1,2,3 );
	$themes = array ( 3,4,5 );
	$circuit_id=''; $theme_id = '';
	
	$list = [];
	
	foreach ( $circuits as $circuit )
	{
		//$idtot = [];
		foreach ( $themes as $theme )
		{
		
			for ( $i=3; $i<=15; $i++)
			{
				$idtot = [];
				$no = 0;
				$query = "SELECT  group_concat(id) as IDS, image_srl_no, circuit_id, section_id, sum(`selection_marks`)  as marks FROM  selection_marks_table where circuit_id='$circuit' and `section_id`='$theme' group by image_srl_no having marks=".$i;
				
				$result=$mysqldb->query($query);
				while($row = $result->fetch_array(MYSQLI_ASSOC))
				{
					 $ids = $row['IDS'];
					 $image_id = $row['image_srl_no'];
					 $idtot[] = $image_id;
					 $marks = $row['marks'];
					 $circuit = $row['circuit_id'];
					 $theme = $row['section_id'];
					 $no++;
				}
				$list[] = array ( 'ids'=>$idtot, 'marks'=>$marks, 'theme'=>$theme, 'circuit'=>$circuit, 'total_number'=>$no);
			}
		}
	}
	
	print '<table border=1 width=90%>';
	
	foreach ( $list as $ls )
	{
		print '<tr>';
		//$sql = 'insert into selection_stat values (';
		foreach ( $ls as $k=>$v )
		{
			if ( is_array($v) )
			{
				$v = implode(',',$v);
			}
			print '<td>'.$v.'</td>';
			if ( $k == 'total_number' )
				$sql .= $v.')';
			else
				//$sql .= $v.',';
		}
		//$sql .= '),';
		print '</tr>';
	}
	print '</table>';
	//echo $sql;
?>