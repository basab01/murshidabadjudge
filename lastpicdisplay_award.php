<?php
	session_start();
	require "./scripts/conn.php";
	require "./get_row_no.php";
	include_once ( './image_width_chk.php' );
	if(empty($_GET['section'])) $_GET['section'] = 'C';
	$ses=$_GET['section'];
	$jrid=$_SESSION['judge'];
	
	$newmarks='';
	$str='';
	$lastpic = array();
	
	
	
	if(!empty($ses)){
		//$sql="select * from imgref where section='".$ses."' order by section,photo_no,rgt_id";
		$sql = "select image_srl_no, image_id_actual, award_marks 
					from award_marks_view 
					where ccode = '".$_SESSION['circuit']."'
					and section_code = '".$_GET['section']."'
					and judge_code = '".$_SESSION['judge']."' and award_marks > 0 order by id";
				//echo $sql;
		$result = $mysqldb->query($sql);
		$num_rows = $result->num_rows;
		
		if($result->num_rows > 0)
		{
			while($row = $result->fetch_array())
			{
				$foto_no = $row['image_srl_no'];
				$image_id_actual = $row['image_id_actual'];
				$award_marks = $row['award_marks'];
			}
			
			
			//echo 'Last image : '.$foto_no;
		}
		
			$query = 'select section_id from section_list where section_code = "'.$_GET['section'].'"';
			$result = $mysqldb->query($query);
			list($section_id) = $result->fetch_row();
	 	
			$query = 'select ccode, section_name, start_no, end_no, 
				images_award.id as imageid, images_award.name,images_award.new_name 
				from section_circuit_foto_view, images_award 
				where ccode = "'.$_SESSION['circuit'].'" 
				and images_award.theme_id = "'.$section_id.'"  
				and section_code = "'.$_GET['section'].'"';
				//echo $query;
			$result = $mysqldb->query($query);
			if($result->num_rows > 0)
			{
				while($row = $result->fetch_array())
				{
					//$image_list[] = array($row['ccode'],$row['section_name'],$row['start_no'],$row['end_no'],$row['image_srl_no'],$row['image_id']);
					
					$image_list[] = array($row['ccode'],$row['section_name'],$row['start_no'],$row['end_no'],$row['imageid'],$row['name'], $row['new_name']);
				}
			}
			//$result->free();
			//print_r($image_list);
			$temp = 0;
			foreach($image_list as $key=>$value)
			{
				//echo 'foto_no : '.$foto_no.' and value4 : '.$value[4].'<br>';
				if($foto_no == $value[4])
				{
					$temp = $key;
					$chunk[] = $value;
					$present_actual = $value[5];
					
				}
			}
			
			$new_name = $chunk[0][6];
			$imflag = '';
			$imflag = check_imgwidth ( $chunk[0],$section_id,$mysqldb );
			
			if($temp < count($image_list) - 1)
			{
				$next_chunk = $image_list[$temp + 1];
				//print_r($next_chunk);
			}
			else
			{
				$rr = count($image_list) - 1;
				$next_chunk = $image_list[$rr];
			}
			//print_r($next_chunk);
			if($temp != 0) $prev_chunk = $image_list[$temp - 1];
			else $prev_chunk = $image_list[$temp];
			//print_r($prev_chunk);
			$prev = $prev_chunk[4];
			$prev_actual = $prev_chunk[5];
			
			$next = $next_chunk[4];
			$next_actual = $next_chunk[5];			
			$mflag = 1;
	}
	
	if(!empty($foto_no) AND $mflag == 1)
	{
	
			$flag = 0;
			$judge_marks = array();
		
			$rows = get_rows_no('award_marks_view','ccode',$mysqldb);
			if(!$rows > 0)
			{
				$query = 'create view award_marks_view as 
					select ccode, section_code, judge_code, image_srl_no, image_id_actual, award_marks 
					from award_marks_table inner join circuit_master on circuit_master.id = award_marks_table.circuit_id 
					inner join section_list on section_list.section_id = award_marks_table.section_id 
					inner join judge_master on judge_master.id = award_marks_table.judge_id';
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
				$query = 'select award_marks,image_id_actual from award_marks_view 
						where ccode = "'.$_SESSION['circuit'].'" and section_code = "'.$_GET['section'].'" 
						and judge_code = "'.$_SESSION['judge'].'" and image_srl_no = "'.$foto_no.'"';
					//echo $query;
				$result = $mysqldb->query($query);
				if($result->num_rows > 0)
				{
					list($award_marks,$actual_imgid) = $result->fetch_row();
				}
				else
				{
					$award_marks = 0;
				}
				
			}
			$judge_marks[] = array('judge_code'=>$_SESSION['judge'],'award_marks'=>$award_marks,'prev_image'=>$prev,'next_image'=>$next,'present_image'=>$foto_no,'actual_imgid'=>$present_actual, 'new_name'=>$new_name, 'flag'=>"$imflag");
			
				$str = json_encode($judge_marks);
	}
	echo $str;
?>