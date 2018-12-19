<?php
	session_start();
	require "./scripts/conn.php";
	require "./get_row_no.php";
	include_once ( './image_width_chk.php' );
	$image_list = array();
	$str = '';
	
	//if(empty($_GET['section'])) $_GET['section'] = 'C';
	
	if(!empty($_GET['section']) AND !empty($_SESSION['circuit']))
	{
		$query = 'select section_id from section_list where section_code = "'.$_GET['section'].'"';
		$result = $mysqldb->query($query);
		list($section_id) = $result->fetch_row();
		
		$rows = get_rows_no('award_marks_view','ccode',$mysqldb);
		if(!$rows > 0)
		{
			$query = 'create view award_marks_view as 
				select award_marks_table.id, ccode, section_code, judge_code, 
				image_srl_no, image_id_actual, award_marks 
				from award_marks_table 
				inner join circuit_master 
				on circuit_master.id = award_marks_table.circuit_id 
				inner join section_list 
				on section_list.section_id = award_marks_table.section_id 
				inner join judge_master 
				on judge_master.id = award_marks_table.judge_id';
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
		
		$sql = 'select id from circuit_master where ccode = "'.$_SESSION['circuit'].'"';
		$result=$mysqldb->query($sql);
		while ( $row=$result->fetch_array())
		{
			$id = $row['id'];
		}
		
		
		$query = 'select ccode, section_name, start_no, end_no, 
		images_award.id as imageid, images_award.name, images_award.new_name, images_award.circuit_id
		from section_circuit_foto_view, images_award
		where ccode = "'.$_SESSION['circuit'].'" and 
		images_award.theme_id = "'.$section_id.'"  
		and section_code = "'.$_GET['section'].'" and images_award.circuit_id='.$id;
		
		//echo $query;

		$result = $mysqldb->query($query);
		if($result->num_rows > 0)
		{
			while($row = $result->fetch_array())
			{
				//$image_list[] = array($row['ccode'],$row['section_name'],$row['start_no'],$row['end_no'],$row['image_srl_no'],$row['image_id']);
				
				
				
				$image_list[] = array($row['ccode'],$row['section_name'],$row['start_no'],$row['end_no'],$row['imageid'],$row['name'],$row['new_name']);
			}
			
			//print_r($image_list);
			
			$temp = 0;
			$chunk = $image_list[0];

			$iid = $chunk[4];
			$flag = '';
			//$flag = check_imgwidth ( $chunk );
			$flag = check_imgwidth ( $chunk,$section_id,$mysqldb );
			
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
			

			
			if(!empty($_SESSION['judge']))
			{
				//$query = 'select selection_marks,image_link_award.image_id from selection_marks_view, image_link_award where ccode = "'.$_SESSION['circuit'].'" and section_code = "'.$_GET['section'].'" and judge_code = "'.$_SESSION['judge'].'" and selection_marks_view.image_srl_no = "'.$chunk[4].'"';
				
				$query = 'select award_marks,image_srl_no from award_marks_view where ccode = "'.$_SESSION['circuit'].'" and section_code = "'.$_GET['section'].'" and judge_code = "'.$_SESSION['judge'].'" and image_srl_no = "'.$chunk[4].'"';
				
				$result = $mysqldb->query($query);
				if($result->num_rows > 0)
				{
					list($award_marks,$image_srl_no) = $result->fetch_row();
					/*$query = 'select image_id from images_award where image_srl_no = "'.$image_srl_no.'"';
					$result = $mysqldb->query($query);
					if($result->num_rows > 0)
					{
						list($actual_imgid) = $result->fetch_row();
						$chunk[5] = $actual_imgid;
					}*/
				}
				else
				{
					$award_marks = 0;
				}
			}
			
			//$foto_count[] = array('section_code'=>$_GET['section'],'section_name'=>$chunk[1],'start_no'=>$chunk[2],'end_no'=>$chunk[3], 'award_marks'=>$award_marks, 'next_image'=>$next,'actual_imgid'=>$chunk[5], 'image_srl_no'=>$chunk[4]);
			
			$foto_count[] = array('section_code'=>$_GET['section'],'section_name'=>$chunk[1],'start_no'=>$chunk[2],'end_no'=>$chunk[3], 'award_marks'=>$award_marks, 'next_image'=>$next,'actual_imgid'=>$chunk[5], 'image_srl_no'=>$chunk[4],'new_name'=>$chunk[6], 'flag'=>"$flag");
			$str = json_encode($foto_count);
		}
		else
		{
			$str = 'Can not get Photo No.';
		}
	}
	echo $str;
?>