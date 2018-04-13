<?php
	session_start();
	require "./scripts/conn.php";
	$ses=$_GET['session'];
	$imgid=$_GET['gid'];
	$jyid=$_SESSION['juryid'];
	
	//select * from imgref a, imgtally b where a.irf_id = b.rgt_id and a.section='A' and b.submited = 1 order by a.section,a.photo_no,a.rgt_id
	
	
	//$sql="select * from imgref where section='".$ses."' and active = 1 order by section,photo_no,rgt_id";
	$sql="select * from imgref a, imgtally b where a.rgt_id = b.rgt_id and a.active = 1 and a.section='".$ses."' and b.submited = 1 order by a.section,a.photo_no,a.rgt_id";
	$result=mysql_query($sql,$conn);
	$num_rows=mysql_num_rows($result);

	$str='';
	$imageid=array();
	$img_descrip=array();
	if($num_rows > 0){
		while($row=mysql_fetch_array($result)){
			$img_id=$row['irf_id'];
			$imageid[]=$img_id;
			$img_descrip[$img_id]=array($row['img_flnm'],$row['title']);
		}

		if(empty($imgid)){
				$imgid=$imageid[0];
				$prev='';
				$prevtext='';
				$next=$imageid[1];
				$chunk=$img_descrip[$imgid];
			
		}else{
			foreach($img_descrip as $picid=>$picval){
				if($imgid == $picid){
					$chunk=$img_descrip[$picid];
				}
			}
			$temp=0;
			for($i=0;$i<count($imageid);$i++){
				if($imgid == $imageid[$i]) $temp=$i;
			}
			if($imgid >= 1){
				$prev=$imageid[$temp-1];
			}
			else{
				$prev='';
				$prevtext=' ';
			}
			if($temp < count($imageid)-1){
				$next=$imageid[$temp+1];
			}
			else{
				$rr=count($imageid)-1;
				$next=$imageid[$rr];
			}
		}
		$getmarks='';
		$sql="select * from image_marks where image_id='".$imgid."'";
		$result=mysql_query($sql,$conn);
		while($row=mysql_fetch_array($result)){
			$getmarks=$row['judge_'.$jyid];
		}
		if(empty($getmarks)) $getmarks=0;
		$chunk[1]=ucwords(strtolower($chunk[1]));
		$path='./uploads/section_'.$ses.'1/'.$chunk[0].'';
		
		list($width, $height) = getimagesize($path);
		//$orig_width=ImageSx($sc);
		//$orig_height=ImageSy($sc);
			
			
		$str.="$prev|$chunk[0]-$width-$height|$chunk[1]|$next|$imgid|$getmarks";
	}
	else{
		$str.="Unable to process request";
	}
		
	echo $str;
?>