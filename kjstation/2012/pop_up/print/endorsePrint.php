<?include '../../../dbcon.php';
define('FPDF_FONTPATH','font/');
require('../../../kibs_admin/pdf/fpdf153/mc_table_2.php');




include "./endorseSerch.php";
function GenerateWord()
{
	//Get a random word
	$nb=rand(3,10);
	$w='';
	for($i=1;$i<=$nb;$i++)
		$w.=chr(rand(ord('a'),ord('z')));
	return $w;
}

function GenerateSentence()
{
	//Get a random sentence
	$nb=rand(1,10);
	$s='';
	for($i=1;$i<=$nb;$i++)
		$s.=GenerateWord().' ';
	return substr($s,0,-1);
}

$pdf=new PDF_MC_Table();
$pdf->AddUHCFont();
$pdf->Open();
$pdf->AddPage();
//$pdf->SetFont('Arial','',14);
//Line(float x1, float y1, float x2, float y2) 
$pdf->SetFont('UHC','',14);
$pdf->Cell(150);
//20*10mm 크기의 셀에 중간정렬된 텍스트를 출력하고 줄 바꿈
$pdf->Cell(42,4,$a[8],2,1,'C'); 
//$pdf->Write(4,'Ace Fire');

$pdf->SetLineWidth(0.8);
$pdf->Line(10, 15,200, 15);

//$pdf->Ln();
//$pdf->Ln();
//$pdf->SetFont('UHC','',14);
//$pdf->Cell(70);

//$pdf->Cell(50,10,'배서 신청서',2,1,'C'); 
$pdf->Ln();
$pdf->Ln();

$pdf->SetFont('UHC','',12);
$deising_num ="";
$pdf->Write(6,'수  신  :  ');
$pdf->Write(6,$coTitle);
$pdf->SetLineWidth(0.1);

$pdf->Ln();
$pdf->Write(6,'발  신  :  ');
$pdf->Write(6,$urow[name]);

//$pdf->SetLineWidth(0.1);
//$pdf->Line(10,40,100, 40);
$title_1=" 배서 신청 ";
$title=$cRow[company].$title_1;
$pdf->Ln();
$pdf->Write(6,'제  목  :  ');
$pdf->Write(6,$title);

$pdf->Ln();
$pdf->Write(6,'문서 번호    :  ');
$pdf->Write(6,$CertiTableNum."-".$eNum.$LigeNum);

$pdf->Ln();
$pdf->Ln();

$pdf->Cell(48,7,'계약자 사항',1,1,'C','');
$pdf->Ln();
$pdf->Write(6,'업   체   명   :  ');
$pdf->Write(6,$cRow[company]);

$pdf->Ln();
$pdf->Write(6,'증 권 번 호	  :   ');
$pdf->Write(6,$prow[policyNum]);

$pdf->Ln();
$pdf->Write(6,'사업자번호   :  ');
$pdf->Write(6,$c_number);

$pdf->Ln();
$pdf->Write(6,'배서기준일   :  ');
$pdf->Write(6,$eRow[endorse_day]);
$pdf->Ln();




$pdf->Ln();
$pdf->SetLineWidth(0.2);





$pdf->Ln();

$pdf->Ln();

$he=98;
$pdf->Rect(25,$he,15,7);//번호 사각형
$pdf->Rect(40,$he,75,7);//퇴직
$pdf->Rect(115,$he,85,7);//입사


$he_2=$he+1;
$txet_1='번호';
$pdf->SetXY(28,$he_2);
$txt=$pdf->MultiCell(60,5,$txet_1,0,'J',0,8);
$txet_2='퇴                직';
$pdf->SetXY(59,$he_2);
$txt=$pdf->MultiCell(60,5,$txet_2,0,'J',0,8);

$txet_3='입                사';
$pdf->SetXY(134,$he_2);
$txt=$pdf->MultiCell(60,5,$txet_3,0,'J',0,8);
$height=$he_2+6;
$bunho=0;


for($j=0;$j<$DNum;$j++){

	
	$push=$driverPush[$j];
	
	
	$name=$driverName[$j]."[".$etag[$j]."]";
	$jumin=$driverJumin[$j]."[".$driverNai[$j]."]";
	$bun=$j+1;
	$bunho++;
	//echo "bunho $bunho <bR>";
	if($bunho=='1'){
		$height=$height;
	}else{
	  $height=$height+7;
	}
	//echo "height $height <br>";
	$pdf->Rect(25,$height,15,7);//번호 사각형
	$pdf->Rect(40,$height,75,7);//퇴직
	$pdf->Rect(115,$height,85,7);//입사
	//echo "bunho_2 $bunho_2 <bR>";
switch($push){

	case '1'://신규
		 
			$txet_4=$bunho;
			$height_2=$height+1;
			$pdf->SetXY(30,$height_2);
			$txt=$pdf->MultiCell(60,5,$txet_4,0,'J',0,8);
			$txet_5='';
			$pdf->SetXY(40,$height_2);
			$txt=$pdf->MultiCell(60,5,$txet_5,0,'J',0,8);

			$txet_6=$name." :".$jumin;
			$pdf->SetXY(120,$height_2);
			$txt=$pdf->MultiCell(80,5,$txet_6,0,'J',0,8);
		$count_1++;//신규또는 교체
		break;

	

	case '2'://해지
			$txet_4=$bunho;
			$height_2=$height+1;
			$pdf->SetXY(30,$height_2);
			$txt=$pdf->MultiCell(60,5,$txet_4,0,'J',0,8);
			$txet_5=$name.":".$jumin;
			$pdf->SetXY(40,$height_2);
			$txt=$pdf->MultiCell(85,5,$txet_5,0,'J',0,8);

			$txet_6='';
			$pdf->SetXY(120,$height_2);
			$txt=$pdf->MultiCell(85,5,$txet_6,0,'J',0,8);
		$count_2++;//해지 건수
		break;
	case '3'://교체
			$txet_4=$bunho;
			$height_2=$height+1;
			$pdf->SetXY(30,$height_2);
			$txt=$pdf->MultiCell(60,5,$txet_4,0,'J',0,8);
			$txet_5=$name.":".$jumin;
			$pdf->SetXY(45,$height_2);
			$txt=$pdf->MultiCell(60,5,$txet_5,0,'J',0,8);
			
			$txet_6=$new_name.":".$new_jumin;
			
			$pdf->SetXY(120,$height_2);
			$txt=$pdf->MultiCell(60,5,$txet_6,0,'J',0,8);

		break;

	}
//*********************************************************************************/
	/* 해지 하는자가 동명 이인인 경우를 경고 표시 하기 위해                            */
	/***********************************************************************************/
	if($push==2){
		$sql_10="SELECT jumin FROM ssang_drive WHERE name='$name' and ssang_c_num='$num'";
		//echo "sql $sql <br>";
		$rs_10=mysql_query($sql_10,$connect);
		$duNum=mysql_num_rows($rs_10);
			if($duNum>=2){

			$duplicatName="\"".$name."\" 님은 동명이인 입니다 주민번호 꼭 확인 해주세요";
			
		}
	}	
}
//*****************************************************************/
/*동명 이인인 경우                                                */
/********************************************************************/
if($duNum>=2){
		$txet_1=$duplicatName;
		$pdf->SetXY(10,235);
		$txt=$pdf->MultiCell(120,5,$txet_1,0,'J',0,8);

}
//echo "해지 건수 $count_2 <br>";
//echo "신규 건수 $count_1 <br>";

/**************************************************************/
/*해지 신청이 있어 환급금을 입금 해주어야 할 경우              */
/* 미리 신청이 되어 있는 경우에만                              */
/***************************************************************/

if($count_2>$count_1){

	 $endorse = $row_2[endorse];
	 if($endorse==2){
		$txet_1='해지환급금 입금 첨부: 사업자등록증,신분증,통장사본';
		$pdf->SetXY(10,255);
		$txt=$pdf->MultiCell(120,5,$txet_1,0,'J',0,8);
	 }
}


$pdf->Rect(130,260,15,7);//번호 사각형
$pdf->Rect(145,260,25,7);//퇴직
$pdf->Rect(170,260,15,7);//입사
$txet_1='팩스';
$pdf->SetXY(132,261);
$txt=$pdf->MultiCell(60,5,$txet_1,0,'J',0,8);
$txet_2='입금/환급';
$pdf->SetXY(147,261);
$txt=$pdf->MultiCell(60,5,$txet_2,0,'J',0,8);

$txet_3='증권';
$pdf->SetXY(172,261);
$txt=$pdf->MultiCell(60,5,$txet_3,0,'J',0,8);
$height=$he_2+6;
$pdf->Rect(130,267,15,7);//번호 사각형
$pdf->Rect(145,267,25,7);//퇴직
$pdf->Rect(170,267,15,7);//입사
$pdf->Write(6,'프린트시간   :  ');
$pdf->Write(6,$todayfullTime);


$pdf->Output();


?>