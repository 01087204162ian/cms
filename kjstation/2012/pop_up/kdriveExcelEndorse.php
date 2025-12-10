<?php
include '../../dbcon.php';
$mstart=$_GET['mstart'];
$estart=$_GET['estart'];

$date =$_GET['date'];
$daeriCompanyNum=$_GET['num'];
$normalT =$_GET['normalT']; //2022-03-05 값이 1이면 정상 분납 보험료가 표시됨


$sigi=$_GET['sigi'];
$sql="SELECT * FROM 2012DaeriCompany WHERE num='$daeriCompanyNum'";
//echo $sql;
$rs=mysql_query($sql,$connect);
$row=mysql_fetch_array($rs);


//memo값을 찾기 위해 // 
$sqlm="SELECT * FROM ssang_c_memo WHERE c_number='$row[jumin]' and memokind='2'";
$rsm=$rs=mysql_query($sqlm,$connect);
$rowM=mysql_fetch_array($rsm);

$damdanga=$row[MemberNum];

		$dsSql="SELECT * FROM  2012Member WHERE num='$damdanga'";
		$dsrs=mysql_query($dsSql,$connect);
		$dsrow=mysql_fetch_array($dsrs);


		//$damName=$dsrow[name];
$output_file_name = $row['company'].$date;



//echo $output_file_name;
header( "Content-type: application/vnd.ms-excel" );   
header( "Content-type: application/vnd.ms-excel; charset=euc-kr");  
header( "Content-Disposition: attachment; filename = {$output_file_name}.xls" );  
   

header( "Content-Description: PHP4 Generated Data" );
  //print("<meta http-equiv=\"Content-Type\" content=\"application/vnd.ms-excel;charset=euc-kr\">");



  $EXCEL_STR2 = "  
<table border='1'>  
<tr>  
   <td colspan='10' style=\"text-align:center;mso-number-format:'\@';\">배서리스트".$date."</td></tr>";
$EXCEL_STR2 .= "  
 
<tr style=\"text-align:center;mso-number-format:'\@';\">  
   <td>구분</td>
   <td>일자</td>
   <td>성명</td>  
   <td>주민번호</td>
   <td>나이</td>
   <td>보험회사</td>
   <td>증권번호</td>
   <td>일/탁</td>
    <td>배서종류</td>
	<td>배서보험료</td>
	
</tr>";


//탁송 및 대리 추가 해지건 count  2024-05-08

$etag_count_1=0; $list_1=array(); $daeriPr=0;//일반 추가 
$etag_count_2=0; $list_2=array();//일반 해지
$etag_count_3=0; $list_3=array(); $tarsoPr=0;//탁송추가
$etag_count_4=0;  $list_4=array();//탁송해지

				
			
//증권별로  배서 기록을 찾아서 추가자 인원수 찾기
			$e_sql="SELECT  *  FROM SMSData a left join 2012DaeriCompany  b ";
			$e_sql.="ON a.2012DaeriCompanyNum = b.num WHERE  b.num='$num' and push>='1' ";
			$e_sql.="and (endorse_day='$date') and a.dagun='1' order by SeqNo desc";

				//echo $j;echo $e_sql;
				
				$e_rs=mysql_query($e_sql,$connect);

				$e_num=mysql_num_rows($e_rs);
				

				
				for($k=0;$k<$e_num;$k++){
					$j_=$k+1;


				
					$e_row=mysql_fetch_array($e_rs);
					if($e_row['qboard']==2){ //킥보드는 제외하기 위해
						continue;
					}
					//echo $e_row['SeqNo'];
					$a[50]=$e_row[preminum2];
					
					$daeriMemberNum=$e_row['2012DaeriMemberNum'];
							$e2_sql="SELECT * FROM 2012DaeriMember WHERE num='$daeriMemberNum'";
							$e2_rs=mysql_query($e2_sql,$connect);
							$e2_row=mysql_fetch_array($e2_rs);
					$jumin=explode("-",$e2_row['Jumin']);
							//echo $e2_sql;
					

					switch($e2_row[etag]){
						case 1 : 
							$metat="일반";
								switch($e_row['push']){
									case 2 :
										$daeriPr-=$e_row[preminum];
										$pushName='해지';
										$e_row[preminum]=-$e_row[preminum];
										$a[50]=-$a[50];
										$etag_count_2++;
										array_push($list_2, $e2_row['Name']); 
										
										break;
									case 4 :
										$daeriPr+=$e_row[preminum];
										$pushName='추가';
										$e_row[preminum]=$e_row[preminum];
										$a[50]=$a[50];
										$etag_count_1++;
										 array_push($list_1, $e2_row['Name']); 
										
										break;
								}
							break;
						case 2 :
							$metat="탁송";
								switch($e_row['push']){
									case 2 :
										$tarsoPr-=$e_row[preminum];
										$pushName='해지';
										$e_row[preminum]=-$e_row[preminum];
										$a[50]=-$a[50];
										$etag_count_4++;
										array_push($list_4, $e2_row['Name']); 
										
										break;
									case 4 :
										$tarsoPr+=$e_row[preminum];
										$pushName='추가';
										$e_row[preminum]=$e_row[preminum];
										$a[50]=$a[50];

										$etag_count_3++;
										array_push($list_3, $e2_row['Name']); 
										
										break;
								}
							break;
						default:
							$metat="전탁송";
								switch($e_row['push']){
										case 2 :
											$pushName='해지';
											$e_row[preminum]=-$e_row[preminum];
											$a[50]=-$a[50];

											break;
										case 4 :
											$pushName='추가';
											$e_row[preminum]=$e_row[preminum];
											$a[50]=$a[50];
											break;
									}
							break;
					}
					
			switch($e_row[insuranceCom]){
			case 1 :
				$inName='흥국';
			
				break;
			case 2 :
				$inName='DB';
			

				$sRow[dongbuCerti]="017-".$sRow[dongbuCerti]."-000";
				break;
			case 3 :
				$inName='KB';
			
				break;
			case 4 :
				$inName='현대';
			
				break;
			case 5 :
				$inName='한화';
			
				break;
			case 6 :
				$inName='더케이';
			
				break;
			case 7 :
				$inName='MG';
			
				break;
			case 8 :
				$inName='삼성';
			
				break;


		}
		$pSql="SELECT * FROM 2012CertiTable WHERE num='$e2_row[CertiTableNum]'";
				$pRs=mysql_query($pSql,$connect);
				$pRow=mysql_fetch_array($pRs);


			$policy[$j]=$pRow[policyNum];
			$divi[$j]=$pRow[divi];

			switch($divi[$j]){
				case 1 :
					$divi_name[$j]='직접';
					
					break;
				case 2 :
					$divi_name[$j]='1/12';
					
					break;
				default:
					$divi_name[$j]='직접';
				    
					break;
			}
			
					$ju__=explode('-',$e_row[endorse_day]);
					if($row[FirstStartDay]==$ju__[2]){
					
						$e_row[preminum]=0;
						$a[50]=0;
					}


					$totalPrice+=$e_row[preminum];//배서보험료 소계
					$totalPrice2+=$a[50];//배서보험료 소계(지사로 부터 받은 금액)
			//배서일과 받는일이 같은 경우 배서보험료 0

			// 2022-03-05 각 운전자의 증권번호를 $e2_row['dongbuCerti'] 에서 $e_row['policyNum']로 교체함
			// 배서 당시의 증권번호로 표기 하기함
				$EXCEL_STR2 .= "  
				   <tr>  
					   <td style=\"text-align:center;mso-number-format:'\@';\">".$j_."</td>  
					   <td style=\"text-align:center;mso-number-format:'\@';\">".$e_row[endorse_day]."</td>  
					   <td style=\"text-align:center;mso-number-format:'\@';\">".$e2_row['Name']."</td> 
					   <td style=\"text-align:center;mso-number-format:'\@';\">".$jumin[0]."</td>
					   <td style=\"text-align:center;mso-number-format:'\@';\">".$e2_row['nai']."</td>
					   <td style=\"text-align:center;mso-number-format:'\@';\">".$inName."</td>

					   <td style=\"text-align:center;mso-number-format:'\@';\">".$e_row['policyNum']."</td>
					  
					   <td style=\"text-align:center;mso-number-format:'\@';\">".$metat."</td>
					   <td style=\"text-align:center;mso-number-format:'\@';\">".$pushName."</td>
					   <td style=\"text-align:right;mso-number-format:'\@';\">".number_format($e_row[preminum])."</td>
					    
						
				   </tr>  
				   ";  
				}




			

 $EXCEL_STR2 .= "   
<tr>  
   <td colspan='9' style=\"text-align:center;mso-number-format:'\@';\">배서 보험료 소계".$sigi."</td>
   <td style=\"text-align:right;mso-number-format:'\@';\">".number_format($totalPrice)."</td>
 
</tr>";
$EXCEL_STR2 .= "</table>";  

$week = array("일" , "월"  , "화" , "수" , "목" , "금" ,"토") ;



$weekday = $week[ date('w'  , strtotime($date)  ) ] ;

 $EXCEL_STR2 .= "   
 <table > 
 <tr>  
   <td colspan='4' style=\"text-align:left;mso-number-format:'\@';\">".$date." (".$weekday.") 배서현황</td>
</tr>
<tr>  
		   <td colspan='4' style=\"text-align:left;mso-number-format:'\@';\"></td>

</tr>
<tr>  
   <td colspan='4' style=\"text-align:left;mso-number-format:'\@';\">대리가입자 ".count($list_1)." 명</td>
    
 
</tr>";

for($i=0;$i<count($list_1);$i++){

	 $EXCEL_STR2 .= "   
		<tr>  
		   <td colspan='4' style=\"text-align:left;mso-number-format:'\@';\">".$list_1[$i]."</td>

		</tr>";
}
 $EXCEL_STR2 .= "   
		<tr>  
		   <td colspan='4' style=\"text-align:left;mso-number-format:'\@';\"></td>

		</tr>";
 $EXCEL_STR2 .= "   
<tr>  
   <td colspan='4' style=\"text-align:left;mso-number-format:'\@';\">대리해지 ".count($list_2)." 명</td>
    
 
</tr>";

for($i=0;$i<count($list_2);$i++){

	 $EXCEL_STR2 .= "   
		<tr>  
		   <td colspan='4' style=\"text-align:left;mso-number-format:'\@';\">".$list_2[$i]."</td>

		</tr>";
}
 $EXCEL_STR2 .= "   
		<tr>  
		   <td colspan='4' style=\"text-align:left;mso-number-format:'\@';\"></td>

		</tr>";
 $EXCEL_STR2 .= "   
<tr>  
   <td colspan='4' style=\"text-align:left;mso-number-format:'\@';\">탁송가입자 ".count($list_3)."명</td>
    
</tr>";

for($i=0;$i<count($list_3);$i++){

	 $EXCEL_STR2 .= "   
		<tr>  
		   <td colspan='4' style=\"text-align:left;mso-number-format:'\@';\">".$list_3[$i]."</td>

		</tr>";
}

 $EXCEL_STR2 .= "   
		<tr>  
		   <td colspan='4' style=\"text-align:left;mso-number-format:'\@';\"></td>

		</tr>";
 $EXCEL_STR2 .= "   
<tr>  
   <td colspan='4' style=\"text-align:left;mso-number-format:'\@';\">탁송해지 ".count($list_4)."명</td>
   
 
</tr>";

for($i=0;$i<count($list_4);$i++){

	 $EXCEL_STR2 .= "   
		<tr>  
		   <td colspan='4' style=\"text-align:left;mso-number-format:'\@';\">".$list_4[$i]."</td>

		</tr>";
}
$EXCEL_STR2 .= "   
		<tr>  
		   <td colspan='4' style=\"text-align:left;mso-number-format:'\@';\"></td>

		</tr>";
if($daeriPr>0){
	$daeriPrName="추징";
}else{
	$daeriPrName="환급";
}
 $EXCEL_STR2 .= "   
<tr>  
   <td colspan='4' style=\"text-align:left;mso-number-format:'\@';\">대리보험료 ".number_format($daeriPr)." 원 ".$daeriPrName."</td>
   
</tr>";

if($tarsoPr>0){
	$tarsoPrName="추징";
}else{
	$tarsoPrName="환급";
}
 $EXCEL_STR2 .= "   
<tr>  
   <td colspan='4' style=\"text-align:left;mso-number-format:'\@';\">탁송보험료 ".number_format($tarsoPr)." 원 ".$tarsoPrName."</td>
   
</tr>";


$EXCEL_STR2 .= "   
		<tr>  
		   <td colspan='4' style=\"text-align:left;mso-number-format:'\@';\"></td>

		</tr>";

$totalPr=$daeriPr+$tarsoPr;
if($totalPr>0){
	$totalPrName="추징";
}else{
	$totalPrName="환급";
}
 $EXCEL_STR2 .= "   
<tr>  
   <td colspan='4' style=\"text-align:left;mso-number-format:'\@';\">합계보험료 ".number_format($totalPr)." 원 ".$totalPrName."</td>
   
</tr>";


$EXCEL_STR2 .= "   
		<tr>  
		   <td colspan='4' style=\"text-align:left;mso-number-format:'\@';\"></td>

		</tr>";


 $EXCEL_STR2 .= "   
<tr>  
   <td colspan='4' style=\"text-align:left;mso-number-format:'\@';\">보험료 파일은 정리하여 메일로 발송하겠습니다. </td>
   
</tr>";
$EXCEL_STR2 .= "</table>";  

//$totalPrice
echo "<meta content=\"application/vnd.ms-excel; charset=euc-kr\" name=\"Content-type\"> ";  
echo $EXCEL_STR;  

echo $EXCEL_STR2;  



?>




