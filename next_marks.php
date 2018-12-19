<?php
	session_start();
	require "./scripts/conn.php";
	require "./get_row_no.php";
	include_once ( './image_width_chk.php' );
	
	$section = $_GET['section'];
	$foto_no = $_GET['foto_no'];

	
	$judge = $_SESSION['judge'];
	$circuit = $_SESSION['circuit'];
	$image_list = array();
	$mflag = 0;
	
	//if(empty($_GET['section'])) $_GET['section'] = 'C';
	//if(empty($_GET['foto_no'])) $_GET['foto_no'] = 604;
	
	 if(!empty($_GET['section']) AND !empty($_SESSION['circuit']))
	 {
	 		$query = 'select section_id from section_list where section_code = "'.$_GET['section'].'"';
			$result = $mysqldb->query($query);
			list($section_id) = $result->fetch_row();
	 	
			//$query = 'select ccode, section_name, start_no, end_no, image_link.image_srl_no, image_link.image_id from section_circuit_foto_view, image_link where ccode = "'.$_SESSION['circuit'].'" and image_link.section_id = "'.$section_id.'"  and section_code = "'.$_GET['section'].'"';
			
			
			$query = 'select ccode, section_name, start_no, end_no, images.id as imageid, images.name, images.new_name from section_circuit_foto_view, images where ccode = "'.$_SESSION['circuit'].'" and images.theme_id = "'.$section_id.'"  and section_code = "'.$_GET['section'].'"';
			//echo $query;
			$result = $mysqldb->query($query);
			if($result->num_rows > 0)
			{
				while($row = $result->fetch_array())
				{
					$image_list[] = array($row['ccode'],$row['section_name'],$row['start_no'],$row['end_no'],$row['imageid'],$row['name'], $row['new_name']);
				}
			}
			$result->free();
			
			$temp = 0;
			foreach($image_list as $key=>$value)
			{
				if($foto_no == $value[4])
				{
					$temp = $key;
					$chunk[] = $value;
					$present_actual = $value[5];
				}
			}	
			$new_name = $chunk[0][6];
			
			
			
			$imflag = '';
			//$imflag = check_imgwidth ( $chunk[0] );
			$imflag = check_imgwidth ( $chunk[0],$section_id,$mysqldb );
			//echo $imflag;
			
			if($temp < count($image_list) - 1)
			{
				$next_chunk = $image_list[$temp + 1];
			}
			else
			{
				$rr = count($image_list) - 1;
				$next_chunk = $image_list[$rr];
			}
				
			if($temp != 0) $prev_chunk = $image_list[$temp - 1];
			else $prev_chunk = $image_list[$temp];

			$prev = $prev_chunk[4];
			$prev_actual = $prev_chunk[5];
			
			$next = $next_chunk[4];
			$next_actual = $next_chunk[5];			
			$mflag = 1;
	 }
	
	if(!empty($_GET['foto_no']) AND $mflag == 1)
	{
	
			$flag = 0;
			$judge_marks = array();
		
			$rows = get_rows_no('selection_marks_view','ccode',$mysqldb);
			if(!$rows > 0)
			{
				$query = 'create view selection_marks_view as select ccode, section_code, judge_code, image_srl_no, image_id_actual, selection_marks from selection_marks_table inner join circuit_master on circuit_master.id = selection_marks_table.circuit_id inner join section_list on section_list.section_id = selection_marks_table.section_id inner join judge_master on judge_master.id = selection_marks_table.judge_id';
				//echo $query;
				$result = $mysqldb->query($query);
				$flag = 1;
			}
			else
			{
				//view exists
				$flag = 1;
			}
			
			if($flag == 1)
			{
				$query = 'select selection_marks,image_id_actual from selection_marks_view where ccode = "'.$circuit.'" and section_code = "'.$_GET['section'].'" and judge_code = "'.$judge.'" and image_srl_no = "'.$foto_no.'"';
				//echo $query;
				$result = $mysqldb->query($query);
				if($result->num_rows > 0)
				{
					list($selection_marks,$actual_imgid) = $result->fetch_row();
				}
				else
				{
					$selection_marks = 0;
				}
				
			}
			$judge_marks[] = array('judge_code'=>$judge,'selection_marks'=>$selection_marks,'prev_image'=>$prev,'next_image'=>$next,'present_image'=>$foto_no,'actual_imgid'=>$present_actual, 'new_name'=>$new_name,'flag'=>"$imflag");
				$str = json_encode($judge_marks);
	}
	echo $str;
?>