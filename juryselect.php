<?php
	session_start();
	require "./scripts/conn.php";
	
	$str='';
	$circuit_judge = array();
	if(isset($_SESSION['judge'])) unset($_SESSION['judge']);
	$_SESSION['judge']=$_GET['judge_code'];
	$chkid=$_SESSION['judge'];
	
	if(isset($_SESSION['country'])) unset($_SESSION['country']);
	$_SESSION['country'] = $_GET['country_code'];
	$country_code = $_SESSION['country'];
	
	//echo $chkid.'<br>';
	
	if(isset($_SESSION['circuit'])) unset($_SESSION['circuit']);
	$_SESSION['circuit'] = $_GET['ccode'];
	$circuit = $_SESSION['circuit'];
	
		$sql="select * from jurylogin where circuit_id = '".$circuit."' and judge_code = '".$chkid."'";
		$result = $mysqldb->query($sql);
		$vals=$result->num_rows;
		if($vals==0){
			$sql="insert into jurylogin values(Null, '".$circuit."', '".$chkid."', '', now(), '')";
			//echo $sql.'<br>';
			$result=$mysqldb->query($sql);
			if(!$result OR $mysqldb->affected_rows != 1){
				$str.="Can't Insert";
			}else{
				$circuit_judge[] = array('circuit_code'=>$circuit,'judge_code'=>$chkid);
			}
			
		}else if($vals > 0){
			$sql="update jurylogin set logout = 0, login_time=now(), logout_time='' where judge_code='".$chkid."' ";
			//echo $sql.'<br>';
			$result=$mysqldb->query($sql);
			if(!$result OR $mysqldb->affected_rows != 1){
				$str.="Can't Update";
			}else{
				$circuit_judge[] = array('circuit_code'=>$circuit,'judge_code'=>$chkid);
			}
		}
	
	$str = json_encode($circuit_judge);
	echo $str;
	
?>