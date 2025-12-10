<?include '../../../dbcon.php';
define('FPDF_FONTPATH','font/');
require('../../../kibs_admin/pdf/fpdf153/mc_table_2.php');

$sql="SELECT * FROM 2012DaeriMember  WHERE num='$driverNum'";
//echo "sql $sql <br>";
$rs=mysql_query($sql,$connect);
$row=mysql_fetch_array($rs);
$oun_jumin1	=$row['Jumin'];
//$oun_jumin2	=$row['oun_jumin2'];
$oun_name	=$row['Name'];
$start		=$row['OutPutDay'];
$nex =date("Y-m-d ", strtotime("$wdate")-60*60*24*30);

list($nex_01,$nex_02,$nex_03)=explode("-",$nex);
$today=date("Y-m-d");
list($today_01,$today_02,$today_03)=explode("-",$today);


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

$pdf->SetFont('UHC','',24);
$pdf->Cell(150);




$pdf->Ln();


$pdf->SetLineWidth(0.2);
$pdf->Rect(15,15,181,253);
$txet_1='퇴 직 증 명 서';

$pdf->SetXY(85,30);


$txt=$pdf->MultiCell(60,5,$txet_1,0,'J',0,8);


$pdf->Ln();
$pdf->Ln();
$pdf->Ln();
$pdf->Ln();
$pdf->SetFont('UHC','',14);
$pdf->Cell(50,10,'성        명',2,1,'C'); 
$pdf->SetXY(53,57);
$txt=$pdf->MultiCell(60,5,':',0,'J',0,8);
$pdf->SetXY(58,58);
$txt=$pdf->MultiCell(60,5,$oun_name,0,'J',0,8);


$pdf->Cell(56,10,'주민등록번호',2,1,'C');
$pdf->SetXY(53,65);

$txt=$pdf->MultiCell(60,5,':',0,'J',0,8);
$pdf->SetXY(59,65.5);
$txt=$pdf->MultiCell(60,5,$oun_jumin1,0,'J',0,8);
$pdf->SetXY(76,65.5);
$txt=$pdf->MultiCell(60,5,'-',0,'J',0,8);
$pdf->SetXY(79,65.5);
$txt=$pdf->MultiCell(60,5,$oun_jumin2,0,'J',0,8);
$pdf->Ln();
$pdf->Ln();
$pdf->Ln();
$pdf->Ln();
$pdf->Cell(51,21,'근 무 기 간',2,1,'C'); 
$pdf->SetXY(53,97.5);
$txt=$pdf->MultiCell(60,5,':',0,'J',0,8);
$pdf->SetXY(58,97.8);
$txt=$pdf->MultiCell(60,5,$nex_01,0,'J',0,8);
$pdf->SetXY(71,97.8);
$txt=$pdf->MultiCell(60,5,'년',0,'J',0,8);
$pdf->SetXY(76,97.8);
$txt=$pdf->MultiCell(60,5,$nex_02,0,'J',0,8);
$pdf->SetXY(82,97.8);
$txt=$pdf->MultiCell(60,5,'월',0,'J',0,8);
$pdf->SetXY(87,97.8);
$txt=$pdf->MultiCell(60,5,$nex_03,0,'J',0,8);
$pdf->SetXY(93,97.8);
$txt=$pdf->MultiCell(60,5,'일',0,'J',0,8);
$pdf->SetXY(98,97.8);
$txt=$pdf->MultiCell(60,5,'~',0,'J',0,8);

$pdf->SetXY(103,97.8);
$txt=$pdf->MultiCell(60,5,$today_01,0,'J',0,8);
$pdf->SetXY(115,97.8);
$txt=$pdf->MultiCell(60,5,'년',0,'J',0,8);
$pdf->SetXY(120,97.8);
$txt=$pdf->MultiCell(60,5,$today_02,0,'J',0,8);
$pdf->SetXY(126,97.8);
$txt=$pdf->MultiCell(60,5,'월',0,'J',0,8);
$pdf->SetXY(130,97.8);
$txt=$pdf->MultiCell(60,5,$today_03,0,'J',0,8);
$pdf->SetXY(136,97.8);
$txt=$pdf->MultiCell(60,5,'일',0,'J',0,8);


$pdf->Ln();

$pdf->Ln();

$pdf->Ln();
$pdf->Ln();
$pdf->Cell(51,21,'퇴 직 날 자',2,1,'C'); 
$pdf->SetXY(53,130);
$txt=$pdf->MultiCell(60,5,':',0,'J',0,8);
$pdf->SetXY(58,130);
$txt=$pdf->MultiCell(60,5,$today_01,0,'J',0,8);
$pdf->SetXY(71,130);
$txt=$pdf->MultiCell(60,5,'년',0,'J',0,8);
$pdf->SetXY(76,130);
$txt=$pdf->MultiCell(60,5,$today_02,0,'J',0,8);
$pdf->SetXY(82,130);
$txt=$pdf->MultiCell(60,5,'월',0,'J',0,8);
$pdf->SetXY(87,130);
$txt=$pdf->MultiCell(60,5,$today_03,0,'J',0,8);
$pdf->SetXY(93,130);
$txt=$pdf->MultiCell(60,5,'일',0,'J',0,8);
$pdf->SetXY(98,130);
$txt=$pdf->MultiCell(60,5,'에 퇴직 함',0,'J',0,8);

$pdf->Ln();

$pdf->Ln();

$pdf->SetXY(85,180);
$txt=$pdf->MultiCell(60,5,'위        사항을      확인함',0,'J',0,8);


$pdf->Ln();

$pdf->Ln();

$pdf->SetXY(130,200);
$txt=$pdf->MultiCell(60,5,$today_01,0,'J',0,8);
$pdf->SetXY(142,200);
$txt=$pdf->MultiCell(60,5,'년',0,'J',0,8);
$pdf->SetXY(146,200);
$txt=$pdf->MultiCell(60,5,$today_02,0,'J',0,8);
$pdf->SetXY(152,200);
$txt=$pdf->MultiCell(60,5,'월',0,'J',0,8);
$pdf->SetXY(157,200);
$txt=$pdf->MultiCell(60,5,$today_03,0,'J',0,8);
$pdf->SetXY(163,200);
$txt=$pdf->MultiCell(60,5,'일',0,'J',0,8);
$pdf->SetLineWidth(0.2);

$pdf->SetFont('UHC','',9);







/////////////////////////////////////////////////////////////////////////
/////////////////////////////////////////////////////////////////////////

$pdf->Ln();







$pdf->Rect(100,228,96,40);

$pdf->Rect(102,230,92,36);

$pdf->SetFont('UHC','',14);
$pdf->SetXY(102,232);
$txt_2='서울특별시 강남구 논현로 140길22';
$txt=$pdf->MultiCell(100,5,$txt_2,0,'J',0,4);

//$pdf->SetXY(102,240);
//$txt_3='두산아파트 103동102호';
//$txt=$pdf->MultiCell(100,5,$txt_3,0,'J',0,4);
$pdf->SetXY(102,248);
$txt_4='사업자등록번호:513-17-65680';
$txt=$pdf->MultiCell(100,5,$txt_4,0,'J',0,4);
$pdf->SetXY(110,256);
$txt_5='좋은친구들대리 대표 이 근재';
$txt=$pdf->MultiCell(100,5,$txt_5,0,'J',0,4);
$pdf->Image('../../../kibs_admin/cargo/image/sign.png',168,250,33);//이미지 부분
/////////////////////////////////////////////////////////////////////////
$pdf->SetFont('UHC','',8);
$pdf->SetXY(152,268);
$txt_6='명판과 직인이 없으면 무효입니다';
$txt=$pdf->MultiCell(100,5,$txt_6,0,'J',0,4);

$pdf->SetXY(132,271);
$txt_7='{구비서류 신분(계약자,피보험자)사업자등록증}';
$txt=$pdf->MultiCell(120,5,$txt_7,0,'J',0,4);


$pdf->SetFont('UHC','',8);
$pdf->SetXY(15,195);
$txt=$pdf->MultiCell(60,5,$txet_3,0,'J',0,8);


$pdf->SetXY(15,215);
$txt=$pdf->MultiCell(160,5,$txet_4,0,'J',0,8);


$pdf->Ln();
$pdf->SetFont('UHC','',8);
/*
$pdf->Cell(50,8,'운전자명 : 이 근재  (인)',2,1,'L'); 
$pdf->Cell(10);

$jagi_explan_1=' 주민등록번호 : 661201-1056615 ';
$jagi_explan_2=' 본인은 업체에 입사하면서 동부화재 대리운전 보험을 가입하였으나,';
$pdf->SetXY(10,205);
$txt=$pdf->MultiCell(60,5,$today_01,0,'J',0,8);
$pdf->SetXY(15,205);
$txt=$pdf->MultiCell(60,5,'년',0,'J',0,8);
$pdf->SetXY(20,205);
$txt=$pdf->MultiCell(60,5,$today_02,0,'J',0,8);
$pdf->SetXY(25,205);
$txt=$pdf->MultiCell(60,5,'월',0,'J',0,8);
$pdf->SetXY(30,205);
$txt=$pdf->MultiCell(60,5,$today_03,0,'J',0,8);
$pdf->SetXY(35,205);
$txt=$pdf->MultiCell(60,5,'일',0,'J',0,8);
$jagi_explan_3=' 에 퇴사하게 되었으므로 취급업자 다동차 보험을 퇴사일자로 해지함에 동의합니다 ';
$pdf->Cell(50,5,$jagi_explan_1,2,1,'L');
$pdf->Cell(10);
$pdf->Cell(50,5,$jagi_explan_2,2,1,'L'); 
$pdf->Cell(140);
$pdf->Cell(50,5,$jagi_explan_3,2,1,'R'); 
/////////////////////////////////////////////////////////////////////////
$pdf->Ln();
$pdf->SetWidths(array(15,30,15,30,15,40,15,30));
$pdf->SetAligns(array('C','L','C','L','C','L'));
$pdf->Row(array('소속','','취급자',$cord,'연락처',$cord_phone,'발행일',$print_day));*/
$pdf->Output();
?>
