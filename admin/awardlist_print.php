<?php
require "../scripts/conn.php";
require('mc_table.php');

$opt=$_REQUEST['sub'];
$circuit=$_REQUEST['circuit'];	
$section=$_REQUEST['section'];	

	$sql = "select section_id,section_name from section_list where section_id=$section";
	$result = $mysqldb->query($sql);
	while($row = $result->fetch_array(MYSQLI_ASSOC))
	{
		$sec=$row['section_name'];
	}

	$sql = "select id,cname from circuit_master where id=$circuit";

	$result = $mysqldb->query($sql);
	while($row = $result->fetch_array(MYSQLI_ASSOC))
	{
		//$cname=strtolower($row['cname']);
		$cname=$row['cname'];
	}

	/*$sql = "select * from control";

	$result = $mysqldb->query($sql);
	while($row = $result->fetch_array(MYSQLI_ASSOC))
	{
		$heading1=$row['heading1'];
		$heading2=$row['heading2'];
		$year=$row['yr'];
	}*/

$heading1 = "$header";
$year = "$year";
$heading2 = "";


$j=0;


$sql= "SELECT award_det.id,award_det.title,award_det.marks,award_det.regname,award_det.country,awardlist_master.award_name from award_det,awardlist_master where award_det.award_id >0 and award_det.award_id = awardlist_master.award_id and award_det.circuit_id = '$circuit' and award_det.theme_id = '$section' order by award_det.marks desc";
$result=$mysqldb->query($sql);
	while($userow55 = $result->fetch_array(MYSQLI_ASSOC))
	{
		 $tmp55= array( 0    => $userow55['id'], 
		                1    => $userow55['title'],
				2    => $userow55['marks'],
				3    => $userow55['regname'],
				4    => $userow55['country'],
				5    => $userow55['award_name'],
				);
					
												 
     	$strmst55[$j++] = $tmp55;
				  	
 	}









$ocount=count($strmst55);


  
  
	  $tcnt  =count($strmst55);
	  $npg   =(int)($tcnt /15);
	  $exp   =(int)($tcnt%15);
	  if($exp !=0)
	  {
	  $tpg = $npg +1 ;
	  }
	  else
	  {
	  $tpg = $npg;
	  }
   
$cpg=1;


  


$header = array('Sl.No.','Author','Country','Image Serial','Title','Marks','Award');


$pdf=new PDF_MC_Table();

$pdf->AddPage('L');

$textColour = array( 0, 0, 0 );
$headerColour = array( 0,0,0 );
$reportName =array($heading1,$heading2,$year,'CLUB :'.'   '. $cname); //ucwords($cname)
$reportName1=array('Award List of Section:'.'   '.$sec.' ');

$reportNameYPos = 160;



$pdf->SetTextColor( $headerColour[0], $headerColour[1], $headerColour[2] );
$pdf->SetFont( 'Arial', 'B', 12);
$pdf->Cell( 0, 15, $reportName[0], 0, 0, 'C' );
$pdf->Ln( 5 );
$pdf->Cell( 0, 15, $reportName[1], 0, 0, 'C' );
$pdf->Ln( 8 );
$pdf->Cell( 0, 15, $reportName[2], 0, 0, 'C' );
$pdf->Ln( 8 );
$pdf->Cell( 0, 15, $reportName[3], 0, 0, 'C' );

$pdf->Ln( 8 );
$pdf->Cell( 0, 15, $reportName1[0], 0, 0, 'C' );



$pdf->Ln( 15 );
$pdf->SetTextColor( $headerColour[0], $headerColour[1], $headerColour[2] );


$pdf->SetFont('Arial','B',10);

$pdf->SetWidths(array(20,50,50,40,40,30,50));


$pdf->Row(array($header[0],$header[1],$header[2],$header[3],$header[4],$header[5],$header[6]));


$pdf->SetFont('Arial','',10);

$count=0;

for($i=0;$i<$ocount;$i++)
{

$pdf->Row(array($i+1,$strmst55[$i][3],$strmst55[$i][4],$strmst55[$i][0],$strmst55[$i][1],$strmst55[$i][2],$strmst55[$i][5]));

$count++;

if($count==15 && $i<$ocount-1)
{
$pdf->Ln(10);
$pdf->Cell(80,6,'Signature of Judge 1','',0,'L');
$pdf->Cell(80,6,'Signature of Judge 2','',0,'L');
$pdf->Cell(80,6,'Signature of Judge 3','',0,'L');


if($cpg != $tpg)
		{
		$pdf->Ln(4);
	
		$pdf->Cell( 235, 5, 'Page', 0, 0, 'R' );
		$pdf->Cell( 10, 5, $cpg, 0, 0, 'R' );
		$pdf->Cell( 15, 5, 'of', 0, 0, 'R' );
		$pdf->Cell( 10, 5, $tpg, 0, 0, 'R' );
		
		
		}



$count=0;
$cpg++;

$pdf->AddPage('L');

$textColour = array( 0, 0, 0 );
$headerColour = array( 0,0,0 );
$reportName =array($heading1,$heading2,$year,'Circuit:'.'   '. ucwords($cname));
$reportName1=array('Award List of Section:'.'   '.$sec.' ');
$reportNameYPos = 160;



$pdf->SetTextColor( $headerColour[0], $headerColour[1], $headerColour[2] );
$pdf->SetFont( 'Arial', 'B', 12);
$pdf->Cell( 0, 15, $reportName[0], 0, 0, 'C' );
$pdf->Ln( 5 );
$pdf->Cell( 0, 15, $reportName[1], 0, 0, 'C' );
$pdf->Ln( 8 );
$pdf->Cell( 0, 15, $reportName[2], 0, 0, 'C' );

$pdf->Ln( 8 );
$pdf->Cell( 0, 15, $reportName[3], 0, 0, 'C' );

$pdf->Ln( 8 );
$pdf->Cell( 0, 15, $reportName1[0], 0, 0, 'C' );



$pdf->Ln( 15 );
$pdf->SetTextColor( $headerColour[0], $headerColour[1], $headerColour[2] );


$pdf->SetFont('Arial','B',10);

$pdf->SetWidths(array(20,50,50,40,40,30,50));


$pdf->Row(array($header[0],$header[1],$header[2],$header[3],$header[4],$header[5],$header[6]));



$pdf->SetFont('Arial','',10);
}

}



$pdf->Ln(10);
$pdf->Cell(80,6,'Signature of Judge 1','',0,'L');
$pdf->Cell(80,6,'Signature of Judge 2','',0,'L');
$pdf->Cell(80,6,'Signature of Judge 3','',0,'L');

if($cpg == $tpg)
		{
		$pdf->Ln(4);
	
		$pdf->Cell( 235, 5, 'Page', 0, 0, 'R' );
		$pdf->Cell( 10, 5, $cpg, 0, 0, 'R' );
		$pdf->Cell( 15, 5, 'of', 0, 0, 'R' );
		$pdf->Cell( 10, 5, $tpg, 0, 0, 'R' );
		
		}

$fname = $circuit."_Award_List_".$sec.".pdf";

$pdf->Output( "$fname", "I" );






?>