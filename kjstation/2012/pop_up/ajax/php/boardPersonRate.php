<?


	//개인별 손해율 조회하자 증권번호와 주민번호 조회 하자
	//2019-10-17

	$sqlr="SELECT * FROM 2021rate WHERE policy='$e[12]' and jumin='$e[5]'";

					//	echo $sqlr ;

	$rsr=mysql_query($sqlr,$connect);

	$rowr=mysql_fetch_array($rsr);


	$personRate=$rowr[rate];


	//echo $e[30]; echo "//"; echo $personRate;

	//$personRate2 : 월 보험료 정산 인 경우
	//$personRate3 : 보험회사 내는 보험료 인 경우
	//스마트콜 대리운전 업체처럼 개인별 할인할증 적용없이 누구나 보험료가 일정한 경우

	//$e[50]  대리 컴퍼니 num 값으로 2012DaeriCompany Table에셔 union_ 값을 찾아서 1이면 

	//$personRate2 값이 1
	
	if($e[30]==3){ //kb 화재인 경우
		switch($personRate){


			case 1 :
				$personRate2=1;
				$personRate3=1;// 
				break;
			case 2 :
				$personRate2=0.9;
				$personRate3=0.9;
				break;
			default:
				
				$personRate2=1;
				$personRate3=1;
				break;

		}
	}else{

			switch($personRate){


			case 1 :
				$personRate2=1;
				$personRate3=1;// 
				break;
			case 2 :
				$personRate2=0.9;
				$personRate3=0.9;
				break;
			default:
				
				$personRate2=1;
				$personRate3=1;
				break;

		}
	}



$d_sql="SELECT * FROM 2012DaeriCompany WHERE num='$e[50]'"; 

//echo $d_sql;
$d_rs=mysql_query($d_sql,$connect);
$d_row=mysql_fetch_array($d_rs);

//echo $d_row[union_]; echo "//";

//스마트콜인 경우만 2020년11월1일 정상적으로 받기로 해서 
/*if($d_row[union_]==1){
	$personRate2=1;
}(*/

//echo $personRate2; echo "//";echo $personRate3; echo "//";
?>