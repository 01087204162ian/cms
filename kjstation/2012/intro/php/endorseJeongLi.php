<?php
include '../../../dbcon.php';

$insuranceComNum=$_GET['insuranceComNum'];
$s_contents=$_GET['s_contents'];
$push=$_GET['push'];
$sigi=$_GET['sigi'];


$output_file_name=$_GET['sigi']."정산";
//echo $output_file_name;
header( "Content-type: application/vnd.ms-excel" );   
header( "Content-type: application/vnd.ms-excel; charset=euc-kr");  
header( "Content-Disposition: attachment; filename = {$output_file_name}.xls" );  
   

header( "Content-Description: PHP4 Generated Data" );
  //print("<meta http-equiv=\"Content-Type\" content=\"application/vnd.ms-excel;charset=euc-kr\">");

//echo "ins $insuranceComNum ";

		if($insuranceComNum!=99){
			$where=" and company='$insuranceComNum'  ";
		}else{

		}


		if($s_contents){ //증권번호가 있는 경우

			$where3=" and b.dongbuCerti='$s_contents' ";
		}else{

			$where3='';
		}
		if($push!=99){
			$where2="a.push='$push'  ";
		}else{
			$where2="a.push>='1' ";
		}
		//$where="WHERE $where2 $where $where3 $where4 and a.endorse_day='$sigi'  order by a.damdanga desc ";
		$where="WHERE $where2 $where $where3 $where4 and a.endorse_day>='$sigi'  and a.endorse_day<='$end' order by a.damdanga desc ";

		//$sql="SELECT  *  ";
		//$sql.= "FROM SMSData a left join 2012DaeriMember  b ";
		//$sql.="ON a.2012DaeriMemberNum = b.num $where ";
		//$sql="Select * FROM SMSData      $where ";	

		$sql="SELECT  *  FROM SMSData WHERE endorse_day>='$sigi'  and endorse_day<='$end' order by damdanga desc";
		
	

//echo "sql $sql <br></br>";

	$result2 = mysql_query($sql,$connect);


$sNUM=mysql_num_rows($result2);

// Add some data
$EXCEL_STR = "  
<table border='1'>  
<tr style=\"text-align:center;mso-number-format:'\@';\"> 
	  <td>번호</td>  
   <td>운전자</td>  
   <td>주민번호</td>
   <td>나이</td>
   <td>대리운전회사</td>
   <td>증권번호</td>
   
   <td>유형</td>
   <td>배서보험료</td>
   
	<td>정산보험료</td>
	<td>담당자</td>
   <td>증권소유</td>
   <td>모증권</td>
</tr>";
for ($i = 0; $i <$sNUM; $i++) {
	
	
	$sRow=mysql_fetch_array($result2);

	$a[6]=$sRow['push'];
	$a[23]=$sRow[damdanga];


	//증권의 소유자 즉, 보험료를 누가 책임 지는가

	// 예)마도로스는 오성준, 
	//이근재 거래처에서 해지가 들어오면 오이근재에게 환급을 주고, 청약이면 보험료를 오성준에게 보험

	//
	$dareriMemberNum=$sRow['2012DaeriMemberNum'];
	$msql="SELECT * FROM 2012DaeriMember WHERE num='$dareriMemberNum'";
	$mrs=mysql_query($msql,$connect);
	$mRow=mysql_fetch_array($mrs);



	$oSql="SELECT * FROM  2012Certi WHERE certi='$mRow[dongbuCerti]'";
	//echo $oSql; echo "<Br>";
	$oRs=mysql_query($oSql,$connect);
	$oRow=mysql_fetch_array($oRs);

	

	$qSql="SELECT * FROM 2012Member  WHERE num='$a[23]'";
			$qRs=mysql_query($qSql,$connect);
			$qRow=mysql_fetch_array($qRs);

			//$a[23]=$qRow[name];
	$a[21]=$sRow[c_preminum];



	switch($a[6]){
		   case 2 :
			   $a[44]="해지";
				$haeji_total++;
			   $a[21]=-$a[21];
			
			   break;
			case 4 :
				$push_total++;
				$a[44]="청약";
			  $a[21]=$a[21];
				break;
	   }


	   //담당자와 소유자
	   if($a[23]==$oRow[owner]){

			$jonsa='';
	   }else{
		  if($a[23]==1){
			$jonsa=	-$a[21];	
		  }else{
			$jonsa=	$a[21];	
		  }
		}

		$totlaDailyPrice2+=$jonsa;
		$daeComNum=$sRow['2012DaeriCompanyNum'];
		$sqC="SELECT * FROM 2012DaeriCompany  WHERE num='$daeComNum'";
		$sRC=mysql_query($sqC,$connect);
		$sRow2=mysql_fetch_array($sRC);


	$j=$i+1;


		//
	$EXCEL_STR .= "  
   <tr >  
       <td style=\"text-align:center;mso-number-format:'\@';\">".$j."</td>  
       <td style=\"text-align:center;mso-number-format:'\@';\">".$mRow['Name']."</td>  
       <td style=\"text-align:center;mso-number-format:'\@';\">".$mRow['Jumin']."</td> 
	   <td style=\"text-align:center;mso-number-format:'\@';\">".$mRow['nai']."</td>
	   <td style=\"text-align:center;mso-number-format:'\@';\">".$sRow2['company']."</td>
	   
	   <td style=\"text-align:center;mso-number-format:'\@';\">".$mRow['dongbuCerti']."</td>
	   
	   
	   <td style=\"text-align:center;mso-number-format:'\@';\">".$a[44]."</td>
	   <td style=\"text-align:right;mso-number-format:'\@';\">".number_format($a[21])."</td>
	   
	   <td style=\"text-align:right;mso-number-format:'\@';\">".number_format($jonsa)."</td>
		<td style=\"text-align:center;mso-number-format:'\@';\">".$qRow[name]."</td> 
	   <td style=\"text-align:center;mso-number-format:'\@';\">".$a[23]."|".$oRow[owner]."</td>
	   <td style=\"text-align:center;mso-number-format:'\@';\">".$oRow[company]."</td>
	  
	  
   </tr>  
   ";  


	
  
   
}  
  
$EXCEL_STR .= "</table>";  
 
$EXCEL_STR .= "  
<table border='1'>  
<tr>  
   <td colspan='8' style=\"text-align:center;mso-number-format:'\@';\"> 보험료 소계</td>
  
   <td style=\"text-align:right;mso-number-format:'\@';\">".number_format($totlaDailyPrice2)."</td>
   <td>&nbsp;</td>
   <td style=\"text-align:right;mso-number-format:'\@';\">".number_format($totlaDailyPrice3)."</td>
    <td style=\"text-align:right;mso-number-format:'\@';\">".number_format($totlaDailyPrice3)."</td>
 </tr>";





			

 

//$totalPrice
echo "<meta content=\"application/vnd.ms-excel; charset=euc-kr\" name=\"Content-type\"> ";  
echo $EXCEL_STR;  




?>




