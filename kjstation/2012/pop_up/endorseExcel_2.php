<?php
include '../../dbcon.php';
$sigi=$_GET['sigi'];
$end=$_GET['end'];
$certi=$_GET['certi'];

$output_file_name = $certi."[".$sigi."]";


$sql="SELECT  *  FROM 2012DaeriMember a left join 2012DaeriCompany  b ON a.2012DaeriCompanyNum = b.num  ";	
$sql.="WHERE a.sangtae='1' ";
///$sql.="WHERE a.sangtae='1'     and a.dongbuCerti='$certi'  and a.push='1' ";
$sql.="order by a.dongbuCerti asc,a.Jumin asc";   

$rs= mysql_query($sql,$connect);

$eCount=mysql_num_rows($rs);

//echo $eCount;
//echo $sql;
//return false;
//echo $output_file_name;
header( "Content-type: application/vnd.ms-excel" );   
header( "Content-type: application/vnd.ms-excel; charset=euc-kr");  
header( "Content-Disposition: attachment; filename = {$output_file_name}.xls" );  
   

header( "Content-Description: PHP4 Generated Data" );
 

// Add some data
$EXCEL_STR = "  
<table border='1'>  
<tr style=\"text-align:center;mso-number-format:'\@';\">  
   <td>구분</td>  
   <td>성명</td>  
   <td>주민번호</td>
   <td>나이</td>
   <td>보험회사</td>
   <td>증권번호</td>
   <td>청/해</td>
   <td>탁/일</td>
   <td>보험료</td>
    <td>담당자</td>
	
</tr>";
for ($i = 0; $i <$eCount; $i++) {
	
	
	$sRow=mysql_fetch_array($rs);

	$ju_=explode('-',$sRow['Jumin']);
	$jumin[$i]=$ju_[0].$ju_[1];
	$j=$i+1;
		switch($sRow[push]){
			
			case 1 :
				$push[$i]="청약";	
				break;
			case 4 :
				$push[$i]="해지";	
				break;
		}
		switch($sRow[etag]){
			default:

				$etage[$i]="일반";
				break;
			case 2 :
				$etage[$i]="탁송";	
				break;
			case 3 :
				$etage[$i]="전탁송";	
				break;
		}
		switch($sRow[InsuranceCompany]){
			case 1 :
				$InsuranceCompany='흥국';
			
				break;
			case 2 :
				$InsuranceCompany='DB';
			
				break;
			case 3 :
				$InsuranceCompany='KB';
				break;
			case 4 :
				$InsuranceCompany='현대';
				break;
		}


		//대리운전회사를 찾기위해 

		$dnum[$j]=$sRow['2012DaeriCompanyNum'];

		$dSql="SELECT * FROM 2012DaeriCompany WHERE num='$dnum[$j]'";
		$drs=mysql_query($dSql,$connect);
		$drow=mysql_fetch_array($drs);


		//담당자MemberNum 

		$mSql="SELECT * FROM 2012Member WHERE num='$sRow[MemberNum]'";
		$mRs=mysql_query($mSql,$connect);
		$mRow=mysql_fetch_array($mRs);

		//
	$EXCEL_STR .= "  
   <tr >  
       <td style=\"text-align:center;mso-number-format:'\@';\">".$j."</td>  
       <td style=\"text-align:center;mso-number-format:'\@';\">".$sRow['Name']."</td>  
       <td style=\"text-align:center;mso-number-format:'\@';\">".$jumin[$i]."</td> 
	   <td style=\"text-align:center;mso-number-format:'\@';\">".$sRow['nai']."</td>
	   <td style=\"text-align:center;mso-number-format:'\@';\">".$InsuranceCompany."</td>   
	   <td style=\"text-align:center;mso-number-format:'\@';\">".$sRow['dongbuCerti']."</td>
	   <td style=\"text-align:center;mso-number-format:'\@';\">".$push[$i]."</td>
	   <td style=\"text-align:center;mso-number-format:'\@';\">".$etage[$i]."</td>
	   <td style=\"text-align:center;mso-number-format:'\@';\">".$drow[company]."</td>
	   <td style=\"text-align:right;mso-number-format:'\@';\">".$mRow[name]."</td> 
  
   </tr>  
   ";  

   $m2='';
	
 
}  
   $EXCEL_STR .= "  
   <tr >  
       <td colspan='10' style=\"text-align:center;mso-number-format:'\@';\">"."청약리스트 입니다"."</td>  
       
   </tr>  
   ";  
   
$EXCEL_STR .= "</table>";  
 



//$totalPrice
echo "<meta content=\"application/vnd.ms-excel; charset=euc-kr\" name=\"Content-type\"> ";  
echo $EXCEL_STR;  

 



?>




