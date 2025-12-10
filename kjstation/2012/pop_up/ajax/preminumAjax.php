<?php
include '../../../dbcon.php';
	header("Content-Type: text/xml; charset=euc-kr");
	echo("<?xml version=\"1.0\" encoding=\"euc-kr\"?>\n");

	
	
	$num=iconv("utf-8","euc-kr",$_GET['num']);//
	$yearP=iconv("utf-8","euc-kr",$_GET['yearP']);//
	$sunso=iconv("utf-8","euc-kr",$_GET['sunso']);

	$gi=iconv("utf-8","euc-kr",$_GET['gi']);//gi 가 1이면 일반 //gi가 2이면 탁송

	$ayP=explode(",",$yearP);
	$yearP=$ayP[0].$ayP[1].$ayP[2];//년간보험료


	$year1P=round($yearP*0.2,-1); //1회차
	$year2P=round($yearP*0.1,-1);  //2회차 ~8회차 보험료
	$year3P=round($yearP*0.05,-1); //9회차~10회차


$aSql="SELECT * FROM 2012CertiTable  WHERE num='$num'";
$aSql=mysql_query($aSql,$connect);
$aRow=mysql_fetch_array($aSql);

$InsuraneCompany=$aRow[InsuraneCompany];
include "../php/naiinai.php";
if($gi==1){

	switch($sunso){
		case 0 :
				$y="yearP1='$yearP'";
				$p=$Bnai[0];
			break;
		case 1 :
				$y="yearP2='$yearP'";
				$p=$Bnai[1];
			break;
		case 2 :
				$y="yearP3='$yearP'";
				$p=$Bnai[2];
			break;
		case 3 :
				$y="yearP4='$yearP'";
				$p=$Bnai[3];
			break;
		case 4 :
				$y="yearP5='$yearP'";
				$p=$Bnai[4];
			break;
	}
}else{
	switch($sunso){
		case 0 :
				$y="yearP1='$yearP'";
				$p=$Bnai[0];
			break;
		case 1 :
				$y="yearP2='$yearP'";
				$p=$Bnai[1];
			break;
		case 2 :
				$y="yearP3='$yearP'";
				$p=$Bnai[2];
			break;
		case 3 :
				$y="yearP4='$yearP'";
				$p=$Bnai[3];
			break;
		case 4 :
				$y="yearP5='$yearP'";
				$p=$Bnai[4];
			break;
	}




}

	
	
	$Sql="UPDATE 2012CertiTable  SET $y WHERE num='$num'";
	$rs=mysql_query($Sql,$connect);
	
	$message=$p.'보험료 저장 완료!';


		echo"<data>\n";
			echo "<cname>".$yearP."</cname>\n";
			echo "<message>".$message."</message>\n";
			echo "<yearP>".number_format($yearP)."</yearP>\n";
			echo "<year1P>".number_format($year1P)."</year1P>\n";//1회차
			echo "<year2P>".number_format($year2P)."</year2P>\n";//2회차~7회차
			echo "<year3P>".number_format($year3P)."</year3P>\n";//9회차 ~10회
			echo "<sunso>".$sunso."</sunso>\n";
			echo "<gi>".$gi."</gi>\n";
			
		echo"</data>";



	?>