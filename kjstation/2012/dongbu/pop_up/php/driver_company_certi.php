<?
// 피보험자 성명,회사이름,전화번호,사업자번호,법인번호를 관리하기 위해
//echo "p_con_jumin1 $p_con_jumin1 <br>";
//echo "p_con_jumin2 $p_con_jumin2 <br>";
//echo "p_con_name $p_con_name <br>";
//echo "p_con_phone $p_con_phone <br>";
//echo "company_full $company_full <br>";
//echo "company_num_ber $company_num_ber <br>";

//echo "l_number $l_number <br>";


if($p_con_jumin1 && $p_con_jumin2){
	$sql="SELECT num  FROM driver_company_certi ";
	$sql.="WHERE p_con_jumin1='$p_con_jumin1' and p_con_jumin2='$p_con_jumin2'";
	//echo "sql $sql <Br>";


	$rs=mysql_query($sql,$connect);

	$row=mysql_fetch_array($rs);
	$num			=$row['num']; //driver_company_certi의 num 값
	//주민번호에 해당하는 사업자번호등이 있으면 update 하고 없으면 insert 한다
	if($num){

		$update_sql="UPDATE driver_company_certi SET ";
		$update_sql.="p_con_name='$p_con_name',p_con_phone='$p_con_phone', ";
		$update_sql.="company_full='$company_full',company_num_ber='$company_num_ber' ";
		$update_sql.="l_number='$l_number',postnum='$postnum',address1='$address1', ";
		$update_sql.="address2='$address2' ";
		$update_sql.="WHERE num='$num'";
		//echo "update_sql $update_sql <br>";
		$rs_1=mysql_query($update_sql,$connect);
	}else{

		$insert_sql="INSERT INTO driver_company_certi ( ";
		
		$insert_sql.="p_con_jumin1,p_con_jumin2,p_con_name,p_con_phone, ";
		$insert_sql.="company_full,company_num_ber,l_number,postnum, ";
		$insert_sql.="address1,address2)";
		$insert_sql.="values ('$p_con_jumin1','$p_con_jumin2','$p_con_name','$p_con_phone', ";
		$insert_sql.="'$company_full','$company_num_ber','$l_number','$postnum', ";
		$insert_sql.="'$address1','$address2')";
		//echo "insert_sql $insert_sql <br>";
		$rs_1=mysql_query($insert_sql,$connect);



	}
}


/*
$num			=$row['num']; //driver_company_certi의 num 값
$p_con_name		=$row['p_con_name']; //피보험자 성명
$p_con_phone	=$row['p_con_phone'];//피보험자 회사 전화 번호
$company_full	=$row['company_full'];//회사 정식이름
$company_num_ber=$row['company_num_ber'];//사업자등록번호
$l_number		=$row['l_number'];//법인등록번호
$postnum		=$row['postnum'];//우편번호
$address1		=$row['address1'];//주소1
$address2		=$row['address2'];//주소2*/
?>