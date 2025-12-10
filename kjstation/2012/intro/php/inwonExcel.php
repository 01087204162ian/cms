<?php
include '../../../dbcon.php';




$output_file_name="牢盔沥府";
//echo $eCount;
//echo $sql;
//return false;
//echo $output_file_name;
header( "Content-type: application/vnd.ms-excel" );   
header( "Content-type: application/vnd.ms-excel; charset=euc-kr");  
header( "Content-Disposition: attachment; filename = {$output_file_name}.xls" );  
   

header( "Content-Description: PHP4 Generated Data" );
$EXCEL_STR = "  
<table border='1'>  
<tr style=\"text-align:center;mso-number-format:'\@';\">  
   <td>备盒</td>  
   <td>唱捞</td>  
   <td>牢盔</td>
   <td>殴价</td>
   <td>老馆</td>
	
</tr>";
$j=0;
for($k=26;$k<77;$k++){
	$j++;
		/*switch($k){
			case 0:
				$m5="28技捞窍";
				$where="(nai>='19' and nai<='28'  ) and";
				$preminum=$a[13];
				break;
			case 1:
				$m5="29技~34技";
				$where="(nai>='29' and nai<='34' ) and";
				$preminum=$a[14];

				break;
			case 2:
				$m5="35技~44技";
				$where="(nai>='35' and nai<='44') and";
				$preminum=$a[15];
				break;
			case 3:
				$m5="45技~49技";
				$where="(nai>='49' and nai<='49') and";
				$preminum=$a[16];
				break;
			case 4:
				$m5="50技~55技";
				$where="(nai>='50' and nai<='55') and";
				$preminum=$a[16];
				break;
			case 5:
				$m5="56技~65技";
				$where="(nai>='56' and nai<='65') and";
				$preminum=$a[17];
				break;
			case 6:
				$m5="66技捞惑技";
				$where="(nai>='56') and";
				$preminum=$a[16];
				break;

		}*/
	
	$sql="SELECT * FROM 2012DaeriMember WHERE nai='$k' and push='4'";

	//echo $sql;
	$trs=mysql_query($sql,$connect);
	$tNum=mysql_num_rows($trs);

	$total1+=$tNum;

	$sql2="SELECT * FROM 2012DaeriMember WHERE nai='$k' and push='4' and etag='2'";

	//echo $sql;
	$trs2=mysql_query($sql2,$connect);
	$tNum2=mysql_num_rows($trs2);
	$total2+=$tNum2;

	$ilban=$tNum-$tNum2;

	$total3+=$ilban;
	$EXCEL_STR .= "  
   <tr >  
       <td style=\"text-align:center;mso-number-format:'\@';\">".$j."</td>  
       <td style=\"text-align:center;mso-number-format:'\@';\">".$k."技"."</td>  
       <td style=\"text-align:center;mso-number-format:'\@';\">".$tNum."</td>
	   <td style=\"text-align:center;mso-number-format:'\@';\">".$tNum2."</td>
	   <td style=\"text-align:center;mso-number-format:'\@';\">".$ilban."</td>
  
   </tr>  
   ";  

   $m2='';
	
 
}  
   $EXCEL_STR .= "  
   <tr >  
       <td colspan='2' style=\"text-align:center;mso-number-format:'\@';\">"."钦拌"."</td>  
       <td  style=\"text-align:center;mso-number-format:'\@';\">".$total1."</td> 
	    <td  style=\"text-align:center;mso-number-format:'\@';\">".$total2."</td>
		<td  style=\"text-align:center;mso-number-format:'\@';\">".$total3."</td>
   </tr>  
   ";  
   
$EXCEL_STR .= "</table>";  
 



//$totalPrice
echo "<meta content=\"application/vnd.ms-excel; charset=euc-kr\" name=\"Content-type\"> ";  
echo $EXCEL_STR;  

 



?>




