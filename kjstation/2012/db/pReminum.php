<?
if($_k==1){
	$mPreminum[0]=$a15[$j];
	$mPreminum[1]=$a15[$j];
	$mPreminum[2]=$a15[$j];
	$mPreminum[3]=$a15[$j];
	
}else{
	$mPreminum[0]=$a15[$j];
	$mPreminum[1]=$a16[$j];
	$mPreminum[2]=$a17[$j];
	$mPreminum[3]=$a18[$j];
	

}
switch($_k){
	case 1 : //흥국
	$sunso=4;
			$spr[0]=19;$epr[0]=34;
			$spr[1]=35;$epr[1]=44;
			$spr[2]=45;$epr[2]=47;
			$spr[3]=48;$epr[3]=999;

			$mPreminum[0]=$a15[$j];
			$mPreminum[1]=$a16[$j];
			$mPreminum[2]=$a17[$j];
			$mPreminum[3]=$a17[$j];

			$yearp1[0]=$yearp1[$j];
			$yearp1[1]=$yearp1[$j];
			$yearp1[2]=$yearp1[$j];
			$yearp1[3]=$yearp1[$j];
		break;
	case 2 : //동부
	$sunso=7;
			$spr[0]=19;$epr[0]=25;
			$spr[1]=26;$epr[1]=30;
			$spr[2]=31;$epr[2]=45;
			$spr[3]=46;$epr[3]=49;
			$spr[4]=50;$epr[4]=54;
			$spr[5]=55;$epr[5]=60;
			$spr[6]=61;$epr[6]=999;


			$mPreminum[0]='';
			$mPreminum[1]=$a15[$j];
			$mPreminum[2]=$a16[$j];
			$mPreminum[3]=$a17[$j];
			$mPreminum[4]=$a18[$j];
			$mPreminum[5]=$a19[$j];
			$mPreminum[6]=$a20[$j];

			$yearp1[0]='';
			$yearp1[1]='';
			$yearp1[2]='';
			$yearp1[3]='';
			$yearp1[4]='';
			$yearp1[5]='';
			$yearp1[6]='';

		break;
	case 3 : //lig
	$sunso=4;
			$spr[0]=19;$epr[0]=25;
			$spr[1]=26;$epr[1]=44;
			$spr[2]=45;$epr[2]=49;
			$spr[3]=50;$epr[3]=999;

		/*	$mPreminum[0]=$a15[$j];
			$mPreminum[1]=$a16[$j];
			$mPreminum[2]=$a17[$j];
			$mPreminum[3]=$a18[$j];*/

			$yearp1[0]='';
			$yearp1[1]='';
			$yearp1[2]='';
			$yearp1[3]='';
			$mPreminum[0]=50000;
			$mPreminum[1]=50000;
			$mPreminum[2]=60000;
			$mPreminum[3]=85000;
		

		break;
	case 4 : //현대
	$sunso=4;
			$spr[0]=19;$epr[0]=34;
			$spr[1]=35;$epr[1]=44;
			$spr[2]=45;$epr[2]=47;
			$spr[3]=48;$epr[3]=999;

		/*	$mPreminum[0]=$a15[$j];
			$mPreminum[1]=$a16[$j];
			$mPreminum[2]=$a16[$j];
			$mPreminum[3]=$a18[$j];*/
			$yearp1[0]='';
			$yearp1[1]='';
			$yearp1[2]='';
			$yearp1[3]='';
			$mPreminum[0]=60000;
			$mPreminum[1]=52000;
			$mPreminum[2]=52000;
			$mPreminum[3]=70000;
		
		break;
}


//$sunso=sizeof($spr);

for($_t=0;$_t<$sunso;$_t++){

	$pSQl="SELECT * FROM 2012Cpreminum WHERE CertiTableNum='$CertiTableNum' and sunso='$_t'";
	//echo "pSQl $pSQl <Br>";
	$pRs=mysql_query($pSQl,$connect);
	$pRow=mysql_fetch_array($pRs);
	
	if($pRow[num]){

		$update="UPDATE 2012Cpreminum SET ";
		$update.="InsuraneCompany='$_k',CertiTableNum='$CertiTableNum', ";
		$update.="DaeriCompanyNum='$drow[num]',sPreminum='$spr[$_t]',ePreminum='$epr[$_t]',sunso='$_t', ";
		$update.="yPreminum='$yearp1[$_t]',mPreminum='$mPreminum[$_t]' ";
		$update.="WHERE num='$row[num]'";
		mysql_query($update,$connect);

		echo "$_k | $a1[$j] || $CertiTableNum up $update <br>";

	}else{

		$insert="INSERT into 2012Cpreminum (InsuraneCompany,CertiTableNum,DaeriCompanyNum,sPreminum,ePreminum,sunso, ";
		$insert.="yPreminum,mPreminum) ";
		$insert.="values ('$_k','$CertiTableNum','$drow[num]','$spr[$_t]','$epr[$_t]','$_t', ";
		$insert.="'$yearp1[$_t]','$mPreminum[$_t]')";

		echo "$_k  | $a1[$j] | $CertiTableNum ins $insert <Br>";
		mysql_query($insert,$connect);
	}
	
	$spr[$_t]='';
	$epr[$_t]='';
}
	
	
?>