<?$sql="SELECT * FROM 2012DaeriCompany WHERE num='$DaeriCompanyNum'";
//echo "Sql $sql <bR>";
$ks=mysql_query($sql,$connect);
$krow=mysql_fetch_array($ks);


$company=$krow[company];

$Csql="SELECT * FROM 2012CertiTable WHERE num='$CertiTableNum'";
$Crs=mysql_query($Csql,$connect);
$Crow=mysql_fetch_array($Crs);

$start=$Crow[startyDay];

		$nai_26	 = date("Y-m-d ",strtotime("$start -26 year"));	//
		$nai_35	 = date("Y-m-d ",strtotime("$start -35 year"));
		$nai_34=	date("Y-m-d ",strtotime("$nai_35 - 1day"))	;		
		$nai_48	 = date("Y-m-d ",strtotime("$start -48 year"));
		$nai_47=	date("Y-m-d ",strtotime("$nai_48 - 1day"))	;
		//$nai_55=	date("Y-m-d ",strtotime("$start -55 year"))	;
		//$nai_54=	date("Y-m-d ",strtotime("$nai_55 - 1day"))	;		


		$nai[0]=$nai_26; $nai2[0]=$nai_34."~";
		$nai[1]=$nai_35; $nai2[1]=$nai_47."~";
		$nai[2]=$nai_48; $nai2[2]=$nai_54."~";

		$nai[3]=$nai_55."捞傈";

		$Realnai[0]="26技何磐";$Realnai2[0]="34技";
		
		$Realnai[1]="35技何磐";$Realnai2[1]="47技";

		$Realnai[2]="48技捞惑";//$Realnai2[2]="54技";
		
		//$Realnai[3]="55技捞惑";
	

?>