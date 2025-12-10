<?php
	header("Content-Type: text/xml; charset=euc-kr");
	echo("<?xml version=\"1.0\" encoding=\"euc-kr\"?>\n");

	$appNumber=iconv("utf-8","euc-kr",$_GET['appNumber']);
	$driverNum=iconv("utf-8","euc-kr",$_GET['driverNum']);

	$bunho=iconv("utf-8","euc-kr",$_GET['bunho']);

$m=explode("-",$appNumber);
if($m[1]=='0000000' || $m[1]=='7000000'){$appNumber='';}
include '../../../dbcon.php';




	$psql="SELECT * FROM 2012DaeriMember WHERE num='$driverNum' ";
	//echo "psql $psql";


	$rs=mysql_query($psql,$connect);

	$row=mysql_fetch_array($rs);

	$oldNabang=$row[nabang_1];

if($rs){

	//신규 추가 되고 있는 대리운전회사의 최종 설계번호를 찾기위해 


		$msql="SELECT * FROM 2012DaeriMember WHERE num='$driverNum'";
		$mRs=mysql_query($msql,$connect);
		$mRow=mysql_fetch_array($mRs);
	
	//대리운전 회사를 잧아서 
		$DaeriCompanyNum=$mRow['2012DaeriCompanyNum'];



		//대리회사의 증권번호 certi를 찾아야지

		$cSql="SELECT * FROM 2012CertiTable WHERE 2012DaeriCompanyNum='$DaeriCompanyNum' and 	policyNum='$appNumber' and startyDay>='$yearbefore'";
		$crs=mysql_query($cSql,$connect);
		$crow=mysql_fetch_array($crs);


//해당 증권이 있을 경우에만 변경 
		if($crow[num]){
		

					//2012EndorseList 배서신청건을 찾아햐한다 배서 신청건수가 3건이면 1건을 줄리고 새로운 배서를 만들고 ,1건이면 update 한다

					$e2_sql="SELECT max(num) as max_num FROM 2012EndorseList WHERE 2012DaeriCompanyNum='$DaeriCompanyNum' and policyNum='$mRow[dongbuCerti]' ";

					//echo "e2_Sql  $e2_sql";
					$e2_rs=mysql_query($e2_sql,$connect);
					$er_row=mysql_fetch_array($e2_rs);

					$oldmaxNum=$er_row[max_num];


					$o_sql="SELECT pnum,sangtae,endorse_day,e_count FROM 2012EndorseList WHERE num='$oldmaxNum'";
					//echo "p_sql $p_sql <br>";
					$o_rs=mysql_query($o_sql,$connect);
					$osh=mysql_fetch_array($o_rs);	

					//echo "ecount $osh[e_count] ";

				$CertiTableNum=$crow[num];

				include "../../pop_up/php/endorseNumSerch.php";

				//echo "p_sql $p_sql <br>";
				
				if($osh[e_count]==1){ 

					$en_sql="SELECT max(num) as max_num FROM 2012EndorseList WHERE 2012DaeriCompanyNum='$DaeriCompanyNum' ";
					$en_sql.="and CertiTableNum='$CertiTableNum'";
						//echo "en $en_sql <br>";
						$sjrs=mysql_query($en_sql,$connect);
						$sj_row=mysql_fetch_array($sjrs);

						$maxNum=$sj_row[max_num];
						$p_sql_1="SELECT pnum,sangtae,endorse_day,e_count FROM 2012EndorseList WHERE num='$maxNum'";
						//echo "p_sql $p_sql <br>";
						$p_rs_1=mysql_query($p_sql_1,$connect);
						$ksh_1=mysql_fetch_array($p_rs_1);	
						$endorse_num=(int)$ksh_1['pnum']+1;
						
				}

				
				
							$lsql="SELECT * FROM 2012DaeriMember WHERE 2012DaeriCompanyNum='$DaeriCompanyNum' and InsuranceCompany='2' ";
							$lsql.="and push='4' order by num desc ";
							$lRs=mysql_query($lsql,$connect);


								$lRow=mysql_fetch_array($lRs);

							//가장 최근 설계번호를 찾는다 


								$update="UPDATE 2012DaeriMember  SET CertiTableNum='$CertiTableNum',dongbuCerti='$appNumber',nabang_1='$oldNabang',  ";
								
								$update.="dongbuSelNumber='$lRow[dongbuSelNumber]',EndorsePnum='$endorse_num'  ";
								$update.="WHERE num='$driverNum'";
								mysql_query($update,$connect);
				$policyNum=$appNumber;
				$endorse_day=$row[InPutDay];

				//echo "endorse_day  $endorse_day ";
				$e_count=1;
				$InsuranceCompany=$row[InsuranceCompany];

				//echo "end $endorse_num <br>";
				//echo "InsuranceCompany $InsuranceCompany";

				if($osh[e_count]==1){ // 신규 추가가 하나 일 때 2012EndorseList update 한대
					//변경되는 증권의 endorselist 의 pnum 값을 찾아야 한다
					


					$e_update = "UPDATE 2012EndorseList SET pnum='$endorse_num',CertiTableNum='$CertiTableNum',policyNum='$policyNum'  WHERE num='$oldmaxNum'";

					//echo "e_update $e_update <br>";
					mysql_query($e_update,$connect);
				}else{
					  $ne_count=$ksh[e_count]-1;
					  $e_update = "UPDATE 2012EndorseList SET e_count='$ne_count'  WHERE num='$oldmaxNum'";
					  mysql_query($e_update,$connect);
				      include "../../pop_up/php/endorseNumStore.php"; 
				}
				$mchange=2;
								$message='증권번호 입력 완료!!!!+';
		}else{
			$mchange=1;
			$message="업체에 ".$appNumber."증권번호가 없습니다 먼제 setting 부터 하시고 변경 하셔요";


		}

}else{

	$mchange=2;
	$update="UPDATE 2012DaeriMember  SET dongbuCerti='$appNumber'  ";
		
		$update.=" WHERE num='$driverNum'";
		mysql_query($update,$connect);
		$message='증권번호 입력 완료!!';

}
	

  echo"<data>\n";
		//echo "<phone>".$esql."</phone>\n";
		//echo "<sms>".$ssangCnum."</sms>\n";
    
		//echo "<msg>".$cerNum."</msg>\n";
		echo "<mchange>".$mchange."</mchange>\n";
		echo "<CertiTableNum>".$CertiTableNum."</CertiTableNum>\n";
		echo "<bunho>".$bunho."</bunho>\n";
		echo "<msg3>".$lsql."</msg3>\n";
		echo "<message>".$message."</message>\n";
		
	
 echo"</data>";
?>