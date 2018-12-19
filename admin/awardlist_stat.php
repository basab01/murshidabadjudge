<?php
require "../scripts/conn.php";

$opt=$_REQUEST['sub'];
$circuit=$_REQUEST['circuit'];	
$section=$_REQUEST['section'];	

	$sql = "select id,cname from circuit_master where id=$circuit";

	$result = $mysqldb->query($sql);
	while($row = $result->fetch_array(MYSQLI_ASSOC))
	{
		$cname=strtolower($row['cname']);
	}


 

		
?>
<html>
<head>
<style>
	#remp{margin:auto; width:100%; height:300px; font-size:18pt; font-weight:bold; color:blue; text-align:center; margin:300px 0 0 0;}
</style>
</head>

<body>
<form method='post' action='awardlist_marks.php' name='frm'>
<div align='center'>
<table border='2' width='20%'>
<tr><th colspan='2'><?php  echo ucwords($cname);?> Award List Stats</th></tr>
<tr><th>Marks</th><th>Number of photos</th></tr>
<?php 
if($opt==2 && isset($_POST['submit']))
{
 for($i=30;$i<=60;$i++)
 {
   $sql="truncate table temp_no_stat";
   $mysqldb->query($sql);
 
   $sql="insert into temp_no_stat SELECT  
   `id`,`circuit_id`,`section_id`,judge_id,`image_srl_no`,`image_id_actual`,
    sum(`award_marks`)  as marks FROM award_marks where `section_id`=$section and circuit_id=$circuit
    group by `image_srl_no` having marks>=".$i;
   $mysqldb->query($sql);
 
   $sql="select count(*) as num from temp_no_stat";
   $result=$mysqldb->query($sql);
    while($row = $result->fetch_array(MYSQLI_ASSOC))
    {
     $num=$row['num'];
    }
?>

<tr><td><?php  echo $i;?></td><td><?php  echo $num;?></td></tr>

<?php
}
?>
<tr><th colspan='2'>Enter Cut off Marks</th>
<tr><td>Cut Off Marks</td><td><input type='text' name='marks' size='15'/></td></tr>
<tr><td align='center' colspan='2'><input type='submit' name='submit' value='Submit' />
<a href='award_menu.php?sub=2'><input type='button' name='back' value='Back' /></a></td>
</tr>
 </table>
 <input type='hidden' name='sub' value='2' />
 <input type='hidden' name='circuit' value='<?php  echo $circuit;?>' />
 <input type='hidden' name='section' value='<?php  echo $section;?>' />
  <input type='hidden' name='cname' value='<?php  echo $cname;?>' />
</div>
</form>    

<?php } ?>
</body>
</html> 