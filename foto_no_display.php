<?php
	session_start();
	error_reporting(E_ALL & ~E_NOTICE);
	require "./scripts/conn.php";
	require "./get_row_no.php";
	include_once ( './image_width_chk.php' );
	$image_list = array();
	
	
	
	if(!empty($_GET['section']) AND !empty($_SESSION['circuit']))
	{
		$query = 'select section_id from section_list where section_code = "'.$_GET['section'].'"';
		$result = $mysqldb->query($query);
		list($section_id) = $result->fetch_row();
		
		$rows = get_rows_no('selection_marks_view','ccode',$mysqldb);
		if(!$rows > 0)
		{
			$query = 'create view selection_marks_view as select selection_marks_table.id, ccode, section_code, judge_code, image_srl_no, image_id_actual, selection_marks from selection_marks_table inner join circuit_master on circuit_master.id = selection_marks_table.circuit_id inner join section_list on section_list.section_id = selection_marks_table.section_id inner join judge_master on judge_master.id = selection_marks_table.judge_id';
			$result = $mysqldb->query($query);
			//$flag = 1;
		}
		else
		{
			//view exists
			//$flag = 1;
		}
		
		
		$foto_count = array();
		$str = '';
		//$query = 'select ccode, section_name, start_no, end_no, image_link.image_srl_no, image_link.image_id from section_circuit_foto_view, image_link where ccode = "'.$_SESSION['circuit'].'" and image_link.section_id = "'.$section_id.'"  and section_code = "'.$_GET['section'].'"';
		
		$query = 'select 
					ccode, section_name, 
					start_no, end_no, 
					images.id as imageid, images.name, images.new_name 
					from section_circuit_foto_view, images 
					where ccode = "'.$_SESSION['circuit'].'" 
					and images.theme_id = "'.$section_id.'"  
					and section_code = "'.$_GET['section'].'"';
					
					//echo $query;

		$result = $mysqldb->query($query);
		if($result->num_rows > 0)
		{
			while($row = $result->fetch_array())
			{
				//$image_list[] = array($row['ccode'],$row['section_name'],$row['start_no'],$row['end_no'],$row['image_srl_no'],$row['image_id']);
				
				$image_list[] = array($row['ccode'],$row['section_name'],$row['start_no'],$row['end_no'],$row['imageid'],$row['name'],$row['new_name']);
			}
			
			$temp = 0;
			$chunk = $image_list[0];
			
			$iid = $chunk[4];
			$flag = '';
			$flag = check_imgwidth ( $chunk,$section_id,$mysqldb );
			//echo $flag;
			
			foreach($image_list as $key=>$value)
			{
				if($iid == $value[4])
				{
					$temp = $key;
				}
			}
			//print_r($chunk);
			//print $temp;
			
			$next_chunk = $image_list[$temp + 1];
			//print_r($next_chunk);
			
			if($temp < count($image_list) - 1)
			{
				$next = $next_chunk[4];
				$next_actual = $next_chunk[5];
			}
			else
			{
				$rr = count($image_list) - 1;
				$next = $next_chunk[4];
				$next_actual = $next_chunk[5];
			}
			

			//echo 'chunk4 : '.$chunk[4];
			if(!empty($_SESSION['judge']))
			{
				//$query = 'select selection_marks,image_link.image_id from selection_marks_view, image_link where ccode = "'.$_SESSION['circuit'].'" and section_code = "'.$_GET['section'].'" and judge_code = "'.$_SESSION['judge'].'" and selection_marks_view.image_srl_no = "'.$chunk[4].'"';
				
				$query = 'select selection_marks,image_srl_no from selection_marks_view where ccode = "'.$_SESSION['circuit'].'" and section_code = "'.$_GET['section'].'" and judge_code = "'.$_SESSION['judge'].'" and image_srl_no = "'.$chunk[4].'"';
				//echo $query.'<br>';
				$result = $mysqldb->query($query);
				if($result->num_rows > 0)
				{
					list($selection_marks,$image_srl_no) = $result->fetch_row();
					/*$query = 'select image_id from image_link where image_srl_no = "'.$image_srl_no.'"';
					echo $query.'<br>';
					$result = $mysqldb->query($query);
					if($result->num_rows > 0)
					{
						list($actual_imgid) = $result->fetch_row();
						$chunk[5] = $actual_imgid;
					}
					*/
				}
				else
				{
					$selection_marks = 0;
				}
			}
			
			$foto_count[] = array('section_code'=>$_GET['section'],'section_name'=>$chunk[1],'start_no'=>$chunk[2],'end_no'=>$chunk[3], 'selection_marks'=>$selection_marks, 'next_image'=>$next,'actual_imgid'=>$chunk[5], 'image_srl_no'=>$chunk[4],'new_name'=>$chunk[6], 'flag'=>"$flag");
			$str = json_encode($foto_count);
		}
		else
		{
			$str = 'Can not get Photo No.';
		}
	}
	echo $str;
?>