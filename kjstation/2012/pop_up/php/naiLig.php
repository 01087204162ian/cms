<?$sql="SELECT * FROM 2012DaeriCompany WHERE num='$DaeriCompanyNum'";
// echo "Sql $sql <bR>";
$ks=mysql_query($sql,$connect);
$krow=mysql_fetch_array($ks);

//증권별 보험시작일을 찾기 위해 


$Csql="SELECT * FROM 2012CertiTable WHERE num='$CertiTableNum'";
$Crs=mysql_query($Csql,$connect);
$Crow=mysql_fetch_array($Crs);

$start=$Crow[startyDay];


$company=$krow[company];
		$nai_25= date("Y-m-d ",strtotime("$start -25 year"));	//
		$nai_26= date("Y-m-d ",strtotime("$nai_25 +1 day"));

		$nai_30= date("Y-m-d ",strtotime("$start -30 year"));	//
		$nai_31= date("Y-m-d ",strtotime("$nai_30 +1 day"));

		$nai_45	 = date("Y-m-d ",strtotime("$start -45 year"));	//
		$nai_44	 = date("Y-m-d ",strtotime("$nai_45 +1 day"));
		
		
		//echo "nai 49  $nai_49";
		$nai_50	 = date("Y-m-d ",strtotime("$start -50 year"));
		//$nai_47=	date("Y-m-d ",strtotime("$nai_48 - 1day"))	;
		$nai_49=	date("Y-m-d ",strtotime("$nai_50 +1 day"))	;
		
//echo "nai 45 $nai_44 <br>";

		$nai[0]=$nai_25."이후"; 
		$nai2[0]='';
		$nai[1]=$nai_30."~";; $nai2[1]=$nai_26;
		$nai[2]=$nai_44."~";; $nai2[2]=$nai_31;
		$nai[3]=$nai_49."~";; $nai2[3]=$nai_45;
		$nai[4]="이전"; $nai2[4]=$nai_50;

		$Realnai[0]="26세이하";
		
		$Realnai[1]="26세부터30세";
		
		$Realnai[2]="31세부터44세";
		$Realnai[3]="45세부터49세";
		$Realnai[4]="50세이상";


	

?>