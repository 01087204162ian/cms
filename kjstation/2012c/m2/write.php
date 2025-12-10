<?
	header('Content-Type: text/html; charset=euc-kr');
	//include("common.php");


include '../../dbcon.php';
include '../php/customer_endorse_day_lig2.php';//배서기준일

$id=iconv("utf-8","euc-kr",$_GET['id']);

switch($id){
	case 1 :
		$inSom='흥국추가';
		break;
	case 2 :
		$inSom='동부추가';
		break;
	case 3 :
		$inSom='LiG추가';
		break;
	case 4 :
		$inSom='현대추가';
		break;
	case 5 :
		$inSom='한화추가';
		break;
	case 6 :
		$inSom='더케이추가';
		break;

 }


 include "./php/sele1.php";//write.php seleCerti.php 공통사용

?>
