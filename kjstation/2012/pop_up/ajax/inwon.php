<?
//대리운전회사 기사 인원을 구하기 위해 

switch($k){
	case 1:
		$wNai="nai>='26' and nai<='30'";
	break;
	case 2:
		$wNai="nai>='31' and nai<='45'";
	break;
	case 3:
		$wNai="nai>='46' and nai<='50'";
	break;
	case 4:
		$wNai="nai>='51' and nai<='55'";
	break;
	case 5:
		$wNai="nai>='56' and nai<='60'";
	break;
	case 6:
		$wNai="nai>='61'";
	break;
}
if($etage==1){

	$eWhere="and (etag='$etage' or etag='')";
	$eWhere="";
}else{

	$eWhere="and etag='$etage'";

}
$sql="SELECT * FROM 2012DaeriMember  WHERE $wNai and  push='4' and CertiTableNum='$CertiTableNum'     $eWhere";

$rs=mysql_query($sql,$connect);

$inwon[$i]=mysql_num_rows($rs);



//donbuBuPreminum에서 넘어온 변수
if($coDongbuP=1){
		 $qSql="SELECT * FROM 2012CertiTable  WHERE num='$CertiTableNum'";
		$qRs=mysql_query($qSql,$connect);
		$qRow=mysql_fetch_array($qRs);
		$giPrem[0]=$qRow[preminum1];$gi2Prem[0]=$qRow[preminumE1];
		$giPrem[1]=$qRow[preminum2];$gi2Prem[1]=$qRow[preminumE2];
		$giPrem[2]=$qRow[preminum3];$gi2Prem[2]=$qRow[preminumE3];
		$giPrem[3]=$qRow[preminum4];$gi2Prem[3]=$qRow[preminumE4];
		$giPrem[4]=$qRow[preminum5];$gi2Prem[4]=$qRow[preminumE5];
		$giPrem[5]=$qRow[preminum6];$gi2Prem[5]=$qRow[preminumE6];

}

