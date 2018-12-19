<?php
require "../scripts/conn.php";

$opt=$_REQUEST['sub'];
$circuit=$_REQUEST['circuit'];	
$section=$_REQUEST['section'];	
//$marks=$_REQUEST['marks'];
//$cname=strtolower($_REQUEST['cname']);

$sql = "select cname from circuit_master where id = '$circuit'";
$result=$mysqldb->query($sql);
    while($row = $result->fetch_array(MYSQLI_ASSOC))
    {
     $cname=$row['cname'];
    }
	
	$sql = "select section_name from section_list where section_id = '$section'";
$result=$mysqldb->query($sql);
    while($row = $result->fetch_array(MYSQLI_ASSOC))
    {
     $sname=$row['section_name'];
    }
 		//echo 'circuit : '.$circuit.' and section :'.$section;
?>
<html>
<head>
<style>
	#remp{margin:auto; width:100%; height:300px; font-size:18pt; font-weight:bold; color:blue; text-align:center; margin:300px 0 0 0;}
	body { background:white; }
	#showtab {width:90%; margin:5% auto; font-family:arial,verdana, sans-serif;}
	#showtab table { border:none; border-spacing:0px; font-size:.9em; border-collapse:collapse;}
	#showtab td { border:1px solid #aaa; padding:4px 15px; vertical-align:middle; }
	#showtab tr:nth-of-type(odd) {background:#f0f0f0}
	#showtab tr:nth-of-type(even) {background:#ccc;}
	
	#showtab th { background:#999; color:white; padding:10px 7px;}
	.spl { width:120px; }
	
	#cover h1 {font-size:130%; color:blue; font-family:verdana; font-weight:normal; padding:0; margin:0;}
	#cover h4 {font-size:100%; color:purple; font-family:verdana; font-weight:normal; padding:0; margin:0;}
	#cover h2 {font-size:115%; color:purple; font-family:verdana; font-weight:normal; padding:0; margin:0;}
	
</style>
</head>

<body>
<?php 
if($opt==4 )
{

 $sql="select award_id,award_name from awardlist_master where section_id = '$section' and circuit_id='$circuit' order by award_id";
 $result=$mysqldb->query($sql);
 $adata = [];
    while($row = $result->fetch_array(MYSQLI_ASSOC))
    {
		
		$adata[] = array ('aid'=>$row['award_id'],'aname'=>$row['award_name']);
		
		
	}
 
 $sql = "select award_det.id,award_det.title,award_det.name,award_det.new_name,award_det.marks,award_det.regname,
 award_det.country,award_det.user_id,award_det.award_id
 from award_det where circuit_id = '$circuit' and theme_id = '$section' order by marks desc";
 
 $result=$mysqldb->query($sql);
 $mdata = [];
    while($row = $result->fetch_array(MYSQLI_ASSOC))
    {
		
		$mdata[] = array ('id'=>$row['id'],'title'=>$row['title'],'name'=>$row['name'],'newname'=>$row['new_name'],'marks'=>$row['marks'],'regname'=>$row['regname'],'country'=>$row['country'],'user'=>$row['user_id'],'award_id'=>$row['award_id']);
		
		
	}
	$str='';
	$no=1;
	$str.="<table border=0 align=center width=100%>";
	
	$str.="<tr><th>No.</th><th>Image</th><th class=spl>File Name</th><th>Title</th><th>Author</th><th>Country</th><th>Section</th><th>Total Marks</th><th>Award</th><th>Edit</th></tr>";
	
	foreach ( $mdata as $data )
	{
		$str.="<tr align=center><td>".$no."</td><td>
				<a href=\"../files/Digital/R-".$data['user']."/".$data['name']."\" target=\"_blank\" >
			<img src=\"../files/Digital/R-".$data['user']."/".$data['name']."\" width=100 height=75></a></td>
			<td class=spl>".$data['newname']."</td>
			<td>".ucwords(strtolower($data['title']))."</td>
			<td>".ucwords(strtolower($data['user']))."</td>  
			<td>".$data['country']."</td>
			<td>".$section."</td>
			<td>".$data['marks']."</td>";
			
			
			if($data['award_id'] > 0){
				$sql="select award_name from awardlist_master where award_id = '".$data['award_id']."'";
				$result=$mysqldb->query($sql);
				while($row = $result->fetch_array(MYSQLI_ASSOC))
				{
				$award = $row['award_name'];
				}
				$str.="<td>".$award."</td>";
				$str.="<td><a href=\"awardlist_edit.php?fotoid=".$data['id']."&section=".$section."&circuit=".$circuit."&edit=1\">Edit</a></td>";
			}else{
			$str.="<td><a href=\"awardlist_edit.php?fotoid=".$data['id']."&section=".$section."&circuit=".$circuit."\">Select</a></td><td>Edit</td>";
			}
			$str.="</tr>";
			$no++;
	}
	
	$str.="<tr><td colspan=10 align=center><a style=\"color:#006;\" href=\"award_menu.php?sub=4\">Back</a></td></tr>";
		$str.="</table>";
 ?>
<center>
<div id=cover>
<?php $toplink = strtoupper($header);?>
	<h4><?php echo $toplink; ?><h4>

	<h1><?php echo $cname ?></h1>
	
	<h2>Section - <?php echo $sname ?></h2>

<div id=showtab>
	<?php echo $str;?>
	<br><br>
</div>
</div>
</center>
<?php
}
?>
</body>
</html> 