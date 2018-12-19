<?php
require "../scripts/conn.php";
error_reporting(0);
$fotoid=$_REQUEST['fotoid'];
$section=$_REQUEST['section'];
$circuit=$_REQUEST['circuit'];	
//$marks=$_REQUEST['marks'];
//$cname=strtolower($_REQUEST['cname']);

$edit=$_GET['edit'];
 	
if($_POST){
		//echo "Hello, ".$_POST['award']." selected";
		$qq="update award_det set award_id='".$_POST['award']."' where id='".$_POST['fotoid']."' and circuit_id=$circuit";
		$rq=$mysqldb->query($qq);
		if($rq)
			header("Location:./viewawardlist.php?section=".$section."&circuit=".$circuit."&view=awrd&sub=4");
		}	
?>
<html>
<head>
<style>
	#remp{margin:auto; width:100%; height:300px; font-size:18pt; font-weight:bold; color:blue; text-align:center; margin:300px 0 0 0;}
	body { background:gray; font-family:verdana;}
</style>
</head>

<body>
<?php 

if(empty($edit)){
 $sql="select award_id,award_name from awardlist_master where award_id not in (select award_id from award_det) and section_id = '$section' and circuit_id = '$circuit' and award_id order by award_id";
 }
 else{
	$sql="select * from awardlist_master where section_id='".$section."' and circuit_id='".$circuit."' order by award_id";
	}
 $result=$mysqldb->query($sql);
 $adata = [];
    while($row = $result->fetch_array(MYSQLI_ASSOC))
    {
		
		$adata[] = array ('aid'=>$row['award_id'],'aname'=>$row['award_name']);
		
		
	}
	$str='';
	foreach ( $adata as $data )
	{
		$str.="<option value='".$data['aid']."'>".$data['aname']."</option>";
	}
 
 $sql = "select award_det.title,award_det.name,award_det.regname,award_det.award_id,award_det.country,award_det.user_id
 from award_det where id = '$fotoid' and theme_id = '$section' ";
 $result=$mysqldb->query($sql);
 $mdata = [];
    while($row = $result->fetch_array(MYSQLI_ASSOC))
    {
		
		$title = strtoupper( $row['title'] );
		$photo = $row['name'];
		$author= $row['regname'];
		$country=$row['country'];
		$award  = $row['award_id'];
		$user   = $row['user_id'];
		
	}
	$flag = 0;
	
	if($edit!=1)
	{
	$sql = "select id from award_det where user_id = '$user' and theme_id = '$section' and circuit_id=$circuit and award_id >0";
	$bresult=$mysqldb->query($sql);
	$maxno  = mysqli_num_rows($bresult);
	
	if($maxno>0)
	{
	$flag = 1;
	}
	else
	{
	$flag = 0;
	}
	}
	?>
	
<body style="background:#0e0e0e;">
<center>
<form action="<?php echo $_SERVER['PHP_SELF'] ?>" method="post">
<!--<center><div id="toptxt" class="headertxt">Society of Photographers<br /><span class="howrahTxt">howrah (founded 1964)</span></div></center>-->

<div class="howrahTxt1">Salon Image List</div><div id=cover>
	
<div id=showtab>
	<table border=0 align=center width=95% cellspacing=0 cellpadding=5 style=\"color:white;margin-top:24px;">
	<thead><tr bgcolor=#666666><th colspan=6><a href="./viewawardlist.php?section=<?php echo $section?>&circuit=<?php echo $circuit?>&view=awrd&sub=4" style='color:yellow;'>Back</a></th></tr>
	</thead>
	<tr bgcolor=#999999><td align=center>
	<?php
		$path='../files/Digital/R-'.$user.'/'.$photo.'';
		$ww=0;$hh=0;
		list($width,$height)=getimagesize($path);
		if($width > $height){
			$ratio=$height/$width;
			$ww=400; 
			$hh=(int)$ww*$ratio;
		}else if($width < $height){
			$ratio=$width/$height;
			$hh=300;
			$ww=$hh*$ratio;
		}else{
			$ww=400;
			$hh=400;
		}
	?>
	<img src='../files/Digital/R-<?php echo $user?>/<?php echo $photo ?>' border=0 width='<?php echo $ww ?>' height='<?php echo $hh ?>' >
	</td></tr>
	<tr bgcolor="#333333">
		<td align=center>
			<?php
			if($flag == 1)
			{
			?>
			<p><font color="red"><h3>Author ID - <?php echo $user; //$author ?> of <?php echo$country?> Already Received An Award !!</h3></font></p>
			
		
		<?php
			}
			else
			{
			?>
			<p><font color="yellow">Title : <?php echo $title; ?><br /><!--Author : <font color="pink"><?php //echo $author ?></font>, Country : <font color="pink"><?php echo$country?></font></font>--></p>
			<?php
			}
			?>
			<select id='award' name='award'>
			<option value=''>Select Award</option>
			<?php echo $str ?>
			</select>
			
			
		</td>
	</tr>
	<tr bgcolor="#333333">
		<td align=center>
		<input type="submit" name="sub" value="Submit">
		<input type="hidden" name="fotoid" value="<?php echo $fotoid ?>">
		<input type="hidden" name="section" value="<?php echo $section?>">
		<input type="hidden" name="circuit" value="<?php echo $circuit?>">
		</td>
	</tr>
	</table>
	<br><br>
</div>
</div>
</form>
</center>

</body>
</html>