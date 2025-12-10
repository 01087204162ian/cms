<?include '../../2012/lib/lib_auth.php';


//대리운전회사 기사 해지용 
/*
$ssangCnum=312;
//처리한것 275(와우) 312 와우//
$sql="SELECT company FROM ssang_c WHERE num='$ssangCnum'";
$rs=mysql_query($sql,$connect);
$row=mysql_fetch_array($rs);

 //일괄적으로 해지 할 때 필요한 모줄
echo " $row[company]; ";

$sql="SELECT num,push,name FROM ssang_drive WHERE ssang_c_num='$ssangCnum' and push='4'";
$rs=mysql_query($sql,$connect);

$num=mysql_num_rows($rs);

echo "num $num <bR>";

for($j=0;$j<$num;$j++){

	$row=mysql_fetch_array($rs);

	echo " $j || $row[num] || $row[name] || $row[push] <br>";


	$update="UPDATE ssang_drive SET push='2' WHERE num='$row[num]'";

	//mysql_query($update);
	
	//echo "update $update <Br>";
}

*/

//흥국화재 대리명단 정리
for($_k=1;$_k<5;$_k++){
	

	//if($_k!=3){continue;};
	switch($_k){
		case 1 :
				$company='흥국';
				$dbName="ssang_c";
				$dbDrive="ssang_drive";
				$ssNum="ssang_c_num";
				$produce="ssang_produce";
				$pmember="new_p_member";
			break;
		case 2 :
				$company='동부';
				$dbName="dongbu2_c";
				$dbDrive="dongbu2_drive";
				$ssNum="ssang_c_num";
				$produce="dongbu2_produce";
				$pmember="new_p_member2";
			break;
		case 3 :
				$company='lig';
				$dbName="lig_c";
				$dbDrive="lig_drive";
				$ssNum="lig_c_num";

			break;
		case 4 :
				$company='현대';
				$dbName="hyundai_c";
				$dbDrive="hyundai_drive";
				$ssNum="hyundai_c_num";
				$produce="hyundai_produce";
				$pmember="new_p_Hyundaei";
			break;
	}
	echo "_k $_k  $company <br>";
$sql="SELECT * FROM $dbName WHERE  start<='2012-03-16'";
//$sql="SELECT * FROM $dbName WHERE  start<='2011-08-30'";
$rs=mysql_query($sql,$connect);
$rNum=mysql_num_rows($rs);
	echo "rNum $rNum <br>";
for($j=0;$j<$rNum;$j++){

	$row=mysql_fetch_array($rs);

		$usql="SELECT * FROM $dbDrive WHERE $ssNum='$row[num]' and push='4'";
		
		$urs=mysql_query($usql,$connect);
		$uNum=mysql_num_rows($urs);
	echo "$j   $row[start]   $row[certi_number] $row[company]    $uNum <br>";  

		for($k=0;$k<$uNum;$k++){
			//echo " usql $usql <Br>";
			$uRow=mysql_fetch_array($urs);

			//echo "$j $k $uRow[name] <br>";

			//echo "urowNum $uRow[num] $uRow[name] $uRow[push]<br>";

			//$kupdate="UPDATE  $dbDrive  SET push='2' WHERE $ssNum='$row[num]' and push='4'";
			$kupdate="UPDATE  $dbDrive  SET push='2' WHERE num='$uRow[num]'";

			//mysql_query($kupdate,$connect);
			//echo "kupdate $kupdate <br>";
			$count++;

		}

}

echo " _k $_k $count <br>" ;
}
/* lig_c  주민번호 없는 명단 정리를 위해
$sql="SELECT * FROM lig_c ";
$rs=mysql_query($sql,$connect);
$Num=mysql_num_rows($rs);

for($_k=0;$_k<$Num;$_k++){

	$row=mysql_fetch_array($rs);

	//echo "$_k company $row[company] $row[start] $row[certi] $row[mother] $row[motherNum] <br>";


	$psql="SELECT * FROM ssang_c WHERE num='$row[motherNum]'";
	$prs=mysql_query($psql,$connect);
	$prow=mysql_fetch_array($prs);

if(!$row[con_jumin1]){
	echo "$_k $row[num] $row[c_name] $row[company]  $row[con_jumin1] ";//$row[start] $row[certi] $row[mother] $row[motherNum] $prow[start] $prow[certi] ";


	$lsql="SELECT * FROM lig_drive WHERE lig_c_num='$row[num]'";
	//echo "lsql $lsql <bR>";
	$lrs=mysql_query($lsql,$connect);

	$lnum=mysql_num_rows($lrs);

	echo "lnum $lnum <br>";
	for($_l=0;$_l<$lnum;$_l++){
		$lrow=mysql_fetch_array($lrs);

		//echo "l $_l $lrow[name] $lrow[push] <br>";
	}


}

}*/


//월별로 받는 경우에 그 시작일을 기록하기 위해 


/*
$sSql="SELECT * FROM $dbName ";

		//echo "sSql $sSql <br>";
		$rs=mysql_query($sSql,$connect);

		$sNum=mysql_num_rows($rs);

		for($_h=0;$_h<$sNum;$_h++){

			$row=mysql_fetch_array($rs);
			$a3[$_h]=$row[con_jumin1]."-".$row[con_jumin2];
			$a34[$_h]=$row[MonthStart];//월별로 받는 것을 의미 일자

			$m=explode("-",$a34[$_h]);
			$a33[$_h]=$m[2];

			$Dsql="SELECT * FROM 2012DaeriCompany WHERE jumin='$a3[$_h]'";
	
			//echo "Dsql $Dsql <Br>";
			$drs=mysql_query($Dsql,$connect);
			$drow=mysql_fetch_array($drs);



			if(
			$update="UPDATE 2012DaeriCompany SET FirstStartDay='$a34[$_h]',FirstStart='$a33[$_h]' ";
			$update.="WHERE num='$drow[num]'";

			echo "update $update <br>";
			mysql_query($update);

		}


}
*/
?>