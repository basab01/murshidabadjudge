<?php
require "../scripts/conn.php";

$opt=$_REQUEST['sub'];
$circuit=$_REQUEST['circuit'];	
$section=$_REQUEST['section'];	
//$marks=$_REQUEST['marks'];
//$cname=strtolower($_REQUEST['cname']);
 		
?>
<html>
<head>
<style>
	#remp{margin:auto; width:100%; height:300px; font-size:18pt; font-weight:bold; color:blue; text-align:center; margin:300px 0 0 0;}
</style>
</head>

<body>
<?php 
if($opt==2 && isset($_POST['submit']))
{
 $sql="truncate table temp_award";
 $mysqldb->query($sql);
 
 $sql="insert into temp_award SELECT  
 `id` as tid,`circuit_id` as tcrctid,`section_id` as tsecid,judge_id as tjdgid,`image_srl_no`,`image_id_actual` as timgid,
 sum(`award_marks`)  as marks FROM award_marks_table where circuit_id=$circuit and `section_id`=$section group by  
 `image_srl_no`";
 $mysqldb->query($sql);


 $sql="delete from award_list where `section_id`=$section and circuit_id=$circuit";
 $mysqldb->query($sql);
 
 $sql="insert into award_list ( `id`,`circuit_id`,`section_id`,`image_srl_no`,`image_id_actual`,award_marks) 
 SELECT tid,`tcrctid`,`tsecid`,`image_srl_no`,`timgid`,marks FROM temp_award
  where `tsecid`=$section and tcrctid=$circuit group by `image_srl_no`";
 $mysqldb->query($sql);
  
  $sql="insert into award_det(id,theme_id,user_id,name,new_name,title,active,status,modified)(select * from images where id in (select timgid from temp_award where tcrctid = '$circuit' and tsecid = '$section'))";
 $mysqldb->query($sql);
 
 
$sql = "update award_det,registrant,country_master set award_det.circuit_id = '$circuit',award_det.regname = concat(registrant.salutation,' ',registrant.fname,' ',registrant.mname,' ',registrant.lname), award_det.country = country_master.country where country_master.country_id = registrant.country and registrant.rgt_id  = award_det.user_id and award_det.circuit_id = 0";
$mysqldb->query($sql);

 $sql = "update award_det,award_list set award_det.marks = award_list.award_marks where award_list.image_srl_no = award_det.id and award_list.section_id = award_det.theme_id and award_det.circuit_id = award_list.circuit_id";
 $mysqldb->query($sql);
 
?>
<div id=remp>
<h3>Process Completed Successfully</h3>
<a href="award_menu.php">Back</a>
</div>
<?php } ?>
</body>
</html> 