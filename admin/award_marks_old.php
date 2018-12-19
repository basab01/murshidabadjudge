<?php
require "../scripts/conn.php";

$opt=$_REQUEST['sub'];
$circuit=$_REQUEST['circuit'];	
$section=$_REQUEST['section'];	
$marks=$_REQUEST['marks'];
$cname=strtolower($_REQUEST['cname']);
 		
?>
<html>
<head>
<style>
	#remp{margin:auto; width:100%; height:300px; font-size:18pt; font-weight:bold; color:blue; text-align:center; margin:300px 0 0 0;}
</style>
</head>

<body>
<?php 
if($opt==1 && isset($_POST['submit']) && !empty($marks))
{
 $sql="truncate table temp_award";
 $mysqldb->query($sql);
 
 /*
 $sql="insert into temp_award SELECT  
 `id` as tid,`circuit_id` as tcrctid,`section_id` as tsecid,judge_id as tjdgid,`image_srl_no`,`image_id_actual` as timgid,
 sum(`selection_marks`)  as marks FROM selection_marks_table where circuit_id=$circuit and `section_id`=$section group by  
 `image_srl_no` having marks>=".$marks;
 */
 
 
 $sql="insert into temp_award SELECT  
 `id` as tid,`circuit_id` as tcrctid,`section_id` as tsecid,judge_id as tjdgid,`image_srl_no`,`image_id_actual` as timgid,
 sum(`selection_marks`)  as marks FROM selection_marks_table where circuit_id=$circuit and `section_id`=$section group by  
 `image_srl_no` having marks>=".$marks;
 $mysqldb->query($sql);
 
 /*$sql="update temp_award set marks=0";
 $mysqldb->query($sql);*/
/* 
 $sql="delete from award_marks where circuit_id=$circuit and `section_id`=$section";
 $mysqldb->query($sql);
*/ 

 $sql="insert into images_award(id,theme_id,user_id,name,new_name,title,active,status,modified,circuit_id)(select * from images where id in (select timgid from temp_award where tcrctid = '$circuit' and tsecid = '$section'),'$circuit')";
 $mysqldb->query($sql);
  

?>
<div id=remp>
<h3>Process Completed Successfully</h3>
<a href="award_menu.php">Back</a>
</div>
<?php } ?>
</body>
</html> 