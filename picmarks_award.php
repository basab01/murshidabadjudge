<?php
	session_start();
	require "./scripts/conn.php";
	
	$newmarks='';
	$st='';
	$judge = $_SESSION['judge'];
	$circuit = $_SESSION['circuit'];
	
	if(!empty($judge) AND !empty($circuit) AND !empty($_GET['image_no']))
	{
		$section = $_GET['section'];
		$marks = $_GET['marks'];
		$present_actual_imgid=$_GET['image_no'];
		$image_info = array();
		
		$query = 'select id from circuit_master where ccode = "'.$circuit.'"';
		$result = $mysqldb->query($query);
		list($circuit_id) = $result->fetch_row();
		$result->free();

		
		$query = 'select id from judge_master where judge_code = "'.$judge.'"';
		$result = $mysqldb->query($query);
		list($judge_id) = $result->fetch_row();
		//$result->free();
		
		$query = 'select section_id from section_list where section_code = "'.$section.'"';
		$result = $mysqldb->query($query);
		list($section_id) = $result->fetch_row();
		//$result->free();
		
		$query = 'select id as image_srl_no from images_award where id = "'.$present_actual_imgid.'" and theme_id = "'.$section_id.'"';
		$result = $mysqldb->query($query);
		list($image_srl_no) = $result->fetch_row();
		//$result->free();

		if($circuit_id > 0 AND $judge_id > 0)
		{
			$query = 'select id from award_marks_table where circuit_id = "'.$circuit_id.'" and section_id = "'.$section_id.'" and judge_id = "'.$judge_id.'" and image_id_actual = "'.$present_actual_imgid.'"';
			$result = $mysqldb->query($query);
			if($result->num_rows > 0)
			{
				//$result->free();
				$query = 'update award_marks_table set award_marks = "'.$marks.'" where circuit_id = "'.$circuit_id.'" and section_id = "'.$section_id.'" and judge_id = "'.$judge_id.'" and image_id_actual = "'.$present_actual_imgid.'"';
				$result = $mysqldb->query($query);
				
				if($result OR $mysqldb->affected_rows == 1)
				{
					//$result->free();
					$image_info[] = array('image_id'=>$present_actual_imgid, 'marks'=>$marks);
					$st = json_encode($image_info);
				} 
			}
			else
			{
				$query = "insert into award_marks_table values(Null,$circuit_id, $section_id, $judge_id, $image_srl_no, $present_actual_imgid, $marks)";
				$result = $mysqldb->query($query);
				$last_id = $mysqldb->insert_id;
				if($result OR $mysqldb->affected_rows == 1)
				{
					$image_info[] = array('image_id'=>$present_actual_imgid, 'marks'=> $marks);
					$st = json_encode($image_info);
					$result->free();
				}
			}
		}
	}
	
	echo $st;
	
	
?>