<?php
require "../scripts/conn.php";
if(isset($_REQUEST['sub']))
{
$opt=$_REQUEST['sub'];	
}  
  $circuit="<select name='circuit'>";
		$sql = "select id,cname from circuit_master ";
		$result = $mysqldb->query($sql);
		while($row = $result->fetch_array(MYSQLI_ASSOC))
		{
			$circuit.="<option value=".$row['id'].">".$row['cname']."</option>";
		}

   $circuit.="</select>"; 


  $section="<select name='section'>";
		$sql = "select section_id,section_name from section_list ";
		$result = $mysqldb->query($sql);
		while($row = $result->fetch_array(MYSQLI_ASSOC))
		{
			$section.="<option value=".$row['section_id'].">".$row['section_name']."</option>";
		}

   $section.="</select>"; 
   
   
   if ( empty ( $opt ) )
	{
		$height = '260px';
	}
	else if ( $opt == 1 ) $height = '160px';
	else if ( $opt == 2 ) $height = '160px';
	else if ( $opt == 3 ) $height = '160px';
	else if ( $opt == 4 ) $height = '160px';
	else $height = '110px';

?>
<html>
<head>
<style>
	#remp{margin:auto; width:100%; font-size:12pt; font-weight:normal; color:blue; text-align:center; margin:<?php echo $height; ?> 0 0 0;}
	#remp a { font-family:verdana,sans-serif;text-decoration:none;}
	#remp a:hover { color:crimson; }
	#remp h1 {font-size:19pt; font-weight:normal; font-family:verdana,sans-serif; color:green;}
	.brder {margin:auto; border:1px solid white; padding:1%; width:70%; box-shadow:0px 0px 4px #999;}
	
	#showtab {width:90%; margin:5% auto; font-family:arial,verdana, sans-serif;}
	#remp table { border:none; border-spacing:0px; font-size:.9em; border-collapse:collapse;}
	#remp td { border:1px solid #aaa; padding:4px 15px; vertical-align:middle; }
	#remp tr:nth-of-type(odd) {background:#f0f0f0}
	#remp tr:nth-of-type(even) {background:#ccc;}
</style>
</head>

<body>

<?php
if(empty($opt)){
?>
<div id=remp>
<h1><?php echo $header; ?></h1>
<div class="brder">
<a href="award_menu.php?sub=1">Create Award Table</a> | 
<a href="award_menu.php?sub=2">Create Award List</a> |
<a href="award_menu.php?sub=4">View & Select Award List</a> |
<a href="award_menu.php?sub=3">Print Award List</a> |
 <a href="../index.php">Back to Home</a>
</div>
</div>
<?php
}
else if($opt==1){
?>
<div id=remp>
<h1><?php echo $header; ?></h1>
<form method='post' action='award_stat.php' name='frm'>
<div align='center'>
<table border='2' width=20%>
<tr><th colspan='2'>Award Judging Process</th></tr>
<tr><td>Select Circuit</td><td><?php echo "$circuit";?></td></tr>
<tr><td>Select Section</td><td><?php  echo "$section";?></td></tr>
<!--<tr><td>Cut Off Marks</td><td><input type='text' name='marks' size='15'/></td></tr>-->
<tr><td align='center' colspan='2'><input type='submit' name='submit' value='Submit' />
<a href='award_menu.php'><input type='button' name='back' value='Back' /></a></td>
</tr>
 </table>
 <input type='hidden' name='sub' value='1' />
</div>
</form>
</div>
<?php
}else if($opt==2){
?>
<div id=remp>
<h1><?php echo $header; ?></h1>
<form method='post' action='awardlist_marks.php' name='frm'>
<div align='center'>
<table border='2' width=20%>
<tr><th colspan='2'>Award List Creation</th></tr>
<tr><td>Select Circuit</td><td><?php echo "$circuit";?></td></tr>
<tr><td>Select Section</td><td><?php  echo "$section";?></td></tr>
<tr><td align='center' colspan='2'><input type='submit' name='submit' value='Submit' />
<a href='award_menu.php'><input type='button' name='back' value='Back' /></a></td>
</tr>
 </table>
 <input type='hidden' name='sub' value='2' />
</div>
</form>
</div>
<?php
}else if($opt==4){
?>
<div id=remp>
<h1><?php echo $header; ?></h1>
<form method='post' action='viewawardlist.php' name='frm'>
<div align='center'>
<table border='2' width=20%>
<tr><th colspan='2'>Award List Creation</th></tr>
<tr><td>Select Circuit</td><td><?php echo "$circuit";?></td></tr>
<tr><td>Select Section</td><td><?php  echo "$section";?></td></tr>
<tr><td align='center' colspan='2'><input type='submit' name='submit' value='Submit' />
<a href='award_menu.php'><input type='button' name='back' value='Back' /></a></td>
</tr>
 </table>
 <input type='hidden' name='sub' value='4' />
</div>
</form>
</div>
<?php }else if($opt==3){?>
<div id=remp>
<h1><?php echo $header; ?></h1>
<form method='post' action='awardlist_print.php' name='frm' target='_blank'>
<div align='center'>
<table border='2' width=20%>
<tr><th colspan='2'>Award List Print</th></tr>
<tr><td>Select Circuit</td><td><?php echo "$circuit";?></td></tr>
<tr><td>Select Section</td><td><?php  echo "$section";?></td></tr>
<!--<tr><td>Cut Off Marks</td><td><input type='text' name='marks' size='15'/></td></tr>-->
<tr><td align='center' colspan='2'><input type='submit' name='submit' value='Submit' />
<a href='award_menu.php'><input type='button' name='back' value='Back' /></a></td>
</tr>
 </table>
 <input type='hidden' name='sub' value='3' />
</div>
</form>
</div>
<?php }?>
</body>
</html> 