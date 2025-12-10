<?include '../../2012/lib/lib_auth.php';?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
	<meta http-equiv="Content-Type" content="text/html; charset=euc-kr" />
	<title><?echo "년령별구성";?></title>
	<link href="../css/member.css" rel="stylesheet" type="text/css" />
	<link href="../css/sj.css" rel="stylesheet" type="text/css" />
	 <script src="../../me/js/jquery-1.7.1.min.js" type="text/javascript"></script>
	 <script src="../js/pop.js" type="text/javascript"></script>
	<link href="../../sj/calendar/dhtmlgoodies_calendar/dhtmlgoodies_calendar.css" rel="stylesheet" type="text/css" />
	<script src="../../sj/calendar/dhtmlgoodies_calendar/dhtmlgoodies_calendar.js" type="text/javascript"></script>
	<script src="../../sj/js/smsAjax.js" type="text/javascript"></script><!--SmsAjax-->
	<script src="../js/create.js" type="text/javascript"></script><!--ajax-->
	<script src="./js/basicAjax.js" type="text/javascript"></script><!--ajaxloading-->
	<script src="./js/MemberList.js" type="text/javascript"></script><!--ajax-->
	<script language="JavaScript" src="/kibs_admin/jsfile/lib_numcheck.js"></script>
</head>
<?$redirectURL='DMM_System'; ?>
<form>
<body id="popUp">




<? 
echo "2022년 11월 1일 kb 만기 증권 2021-5807368 갱신대상 업체 파악";echo "<BR>";
echo "  1. 현재 인원을 파악";echo "<BR>";
echo "  1. 증권번호 변경 및 만기일 변경 ";echo "<BR>";
echo "     만기일 :20211101->20221101";echo "<BR>";
echo "	 증권번호 :2021-5807368->신규 증권번호로 변경";echo "<BR>";
echo "	 보험사 변경 ?";echo "<BR>";
echo "  2. 업체로부터 받는 보험료 변경 및 연령구분 변경";echo "<BR>";
echo "  3. 대리기사 증권번호 변경 및 보험사 변경";echo "<BR>";
  
 
echo " 2022년 11월 1일 kb 만기 증권 2021-L632433/2021-L630013/2021-L628551 갱신대상 업체 파악";echo "<BR>";
echo "  1. 증권번호 변경 및 만기일 변경 ";echo "<BR>";
echo "     만기일 :20211101->20221101";echo "<BR>";
echo "	 증권번호 :2021-5807368->신규 증권번호 변경";echo "<BR>";
echo "	 보험사 변경 ?";echo "<BR>";

echo "  2. 업체로부터 받는 보험료 변경 및 연령구분 변경";echo "<BR>";
$dongbuCerti ='2021-5807368'; //찾고자하는 증권번호 //
echo "갱신 전 증권번호 :"; echo $dongbuCerti;echo "<BR>";

$dongbuCerti_2='2022-K326027';//변경하고자 하는 증권번호 //

echo "갱신 후 증권번호 :"; echo $dongbuCerti_2;echo "<BR>";echo "<BR>";
 
$daumPr=array('',71760,71760,99140,125090,125090);// 새로 증권의 연령별 보험료
$daumsPreminum=array(21,29,41,50,56,66);
$daumePreminum=array(28,40,49,55,65,99);

$InsuraneCompany='3';// 2kb,3현대


$sql="SELECT * FROM 2012CertiTable WHERE policyNum='2024-J882139' and startyDay='2024-11-01'";  //77번으로 가세요



$rs=mysql_query($sql,$connect);
	$Rnum=mysql_num_rows($rs);
	echo "대리업체 개수"; echo $Rnum;echo "<BR>";echo "<BR>";
		for($j=0;$j<$Rnum;$j++){

	    $Row=mysql_fetch_array($rs);

			 $dNum=$Row['2012DaeriCompanyNum'];
			 $cNum=$Row['num'];


			 $sql_2 ="SELECT * FROM 2012DaeriCompany WHERE num='$dNum'";
			 $rs_2 = mysql_query($sql_2,$connect);
			 $row_2= mysql_fetch_array($rs_2);

			 $sql3="SELECT * FROM 2012DaeriMember WHERE  CertiTableNum='$cNum' and push='4'";

				 $rs3=mysql_query($sql3,$connect);
				 $Rnum3=mysql_num_rows($rs3);

				
				echo "대리업체 "; echo $j+1; echo $row_2[company]; echo "기사수"; echo "$Rnum3"; echo "명";
				echo "<br>"; echo "<br>";

			
			//$update="update  2012CertiTable set startyDay='2025-11-01',policyNum='2025-N654290' WHERE num='$cNum'";

			//mysql_query($update,$connect);
		}

$sql="SELECT * FROM 2012DaeriMember WHERE  dongbuCerti='$dongbuCerti' and push='4' order by Jumin asc";
$rs=mysql_query($sql,$connect);
$Rnum=mysql_num_rows($rs);


echo "대리기사 인원";	echo $Rnum;echo "<BR>";echo "<BR>";


/*
$sql="SELECT * FROM 2012CertiTable WHERE policyNum='2022-H668526' and startyDay='2022-10-01'";  //77번으로 가세요



	$rs=mysql_query($sql,$connect);
	$Rnum=mysql_num_rows($rs);
	echo "대리업체 개수"; echo $Rnum;echo "<BR>";echo "<BR>";
	$k=1;
	
	for($j=0;$j<$Rnum;$j++){

	    $Row=mysql_fetch_array($rs);
		 
		 //echo $Row['2012DaeriCompanyNum'];echo "<br>"; 
		 $dNum=$Row['2012DaeriCompanyNum'];
		 $cNum=$Row['num'];
		 $sql_2 ="SELECT * FROM 2012DaeriCompany WHERE num='$dNum'";
		 $rs_2 = mysql_query($sql_2,$connect);
		 $row_2= mysql_fetch_array($rs_2);
		 
		 // 소속 기사 찾기
		 $sql3="SELECT * FROM 2012DaeriMember WHERE  CertiTableNum='$cNum' and push='4'";

         $rs3=mysql_query($sql3,$connect);
         $Rnum3=mysql_num_rows($rs3);


		 echo "각 증권의 시작일과 그리고 증권번호를 변경"; echo "<BR>";echo "<BR>";
		 if($Rnum3){
			//각 증권의 시작일과 그리고 증권번호를 변경
			echo "대리업체 "; echo $j+1; echo $row_2[company]; echo "기사수"; echo "$Rnum3"; echo "명"; echo "<br>"; echo "<br>";

			$update="update  2012CertiTable set startyDay='2022-11-01',policyNum='$dongbuCerti_2',InsuraneCompany='$InsuraneCompany' WHERE num='$cNum'";
			
			echo $update;echo "<br>";
			//mysql_query($update,$connect);

		 }else{// 거래가 없는 업체는 보이지 않게 처리함
			$update="update  2012CertiTable set startyDay='2020-11-01' WHERE num='$cNum'";
			echo $update;echo "<BR>";

			mysql_query($update,$connect);
		 }

		  echo " 대리기사의 증권번호와 보험회사를 변경한다";  echo "<BR>";

		 for($m=0;$m<$Rnum3;$m++){

			$Row3=mysql_fetch_array($rs3);
			$DaeriMemberNum=$Row3['num'];
			$update2 ="UPDAET 2012DaeriMember SET dongbuCerti='$dongbuCerti_2',InsuranceCompany='$InsuraneCompany' WHERE num='$DaeriMemberNum'";

			echo "증권";echo $k; echo"//"; echo $update2; echo"<br>";
			//mysql_query($update2,$connect);
			$k++;
		 }

		echo "각 증권의 연령 상대도에 따른 보험료 적용"; echo "<BR>";
		echo "상대도가 맞는지 확인한다"; echo "<BR>";

		$nSql="SELECT * FROM 2012Cpreminum WHERE   CertiTableNum='$cNum'  order by sunso  asc";
		echo $nSql;echo"<br>";
		$nrs=mysql_query($nSql,$connect);
		$nRnum=mysql_num_rows($nrs);
		
		for($t=0;$t<$nRnum;$t++){

			$nRow=mysql_fetch_array($nrs);
			$cpNum=$nRow['num'];
			echo "대리업체로부터 받은 보험료 조정"; echo $nRow['sunso']; echo "시작나이"; echo $nRow[sPreminum]; echo "끝나이"; echo $nRow[ePreminum];echo "월보험료"; echo $nRow[mPreminum];echo"<br>";
			//kb
			//$update3="UPDATE 2012Cpreminum SET mPreminum='$daumPr[$t]' WHERE num='$cpNum' ";
			//현대
			$update3="UPDATE 2012Cpreminum SET mPreminum='$daumPr[$t]',sPreminum='$daumsPreminum[$t]',ePreminum='$daumePreminum[$t]'; WHERE num='$cpNum' ";
			echo $update3; echo"<br>";
			//mysql_query($update3,$connect);

		}

			
	}
//		$total+=$Rnum3;
		
	
	
/*
	$sql="SELECT * FROM 2012CertiTable WHERE policyNum='2021-L628551'";  //77번으로 가세요

	$rs=mysql_query($sql,$connect);
	$Rnum=mysql_num_rows($rs);
	echo "대리업체 11개수"; echo $Rnum;echo "<BR>";echo "<BR>";
	
	
	for($j=0;$j<$Rnum;$j++){

	    $Row=mysql_fetch_array($rs);
		$cNum=$Row['num'];
		$update="update  2012CertiTable set policyNum='2022-K326027',InsuraneCompany='4' WHERE num='$cNum'";
		mysql_query($update,$connect);

	}*/




	//$sql="SELECT * FROM 2012DaeriMember WHERE  dongbuCerti='2022-k326027' and push='4'  order by Jumin asc";
/*
$sql="SELECT * FROM 2012DaeriMember WHERE  InsuranceCompany='1' and push='4'  order by Jumin asc";
	echo $sql;
$rs=mysql_query($sql,$connect);
$Rnum3=mysql_num_rows($rs);


echo "대리기사 인원";	echo $Rnum3;echo "<BR>";echo "<BR>";


 for($m=0;$m<$Rnum3;$m++){

			$Row3=mysql_fetch_array($rs);

			
			$DaeriMemberNum=$Row3['num'];
			//$update2 ="UPDAET 2012DaeriMember SET InsuranceCompany='4' WHERE num='$DaeriMemberNum'";
			$update3="UPDATE 2012DaeriMember SET push='2' WHERE num='$DaeriMemberNum' ";
			echo $update3; echo"<br>";
			//mysql_query($update3,$connect);
			echo "증권";echo $k; echo"//"; echo $update2; echo"<br>";
			//mysql_query($update3,$connect);
			//$k++;
		 }*/


/*
$sql="SELECT * FROM 2012CertiTable WHERE policyNum='2022-K326027' ";  //77번으로 가세요

	$rs=mysql_query($sql,$connect);
	$Rnum=mysql_num_rows($rs);
	echo "대리업체 11개수"; echo $Rnum;echo "<BR>";echo "<BR>";
	
	
	for($j=0;$j<$Rnum;$j++){

	    $Row=mysql_fetch_array($rs);
		$cNum=$Row['num'];
		$dNum=$Row['2012DaeriCompanyNum'];
		
		 $sql_2 ="SELECT * FROM 2012DaeriCompany WHERE num='$dNum'";
		 $rs_2 = mysql_query($sql_2,$connect);
		 $row_2= mysql_fetch_array($rs_2);
		echo "대리업체 "; echo $j+1; echo $row_2[company]; echo "기사수"; echo "$Rnum3"; echo "명"; echo "<br>"; echo "<br>";
		$nSql="SELECT * FROM 2012Cpreminum WHERE   CertiTableNum='$cNum'  order by sunso  asc";
		echo $nSql;echo"<br>";
		$nrs=mysql_query($nSql,$connect);
		$nRnum=mysql_num_rows($nrs);
		
		for($t=0;$t<$nRnum;$t++){

			$nRow=mysql_fetch_array($nrs);
			$cpNum=$nRow['num'];
			echo "대리업체로부터 받은 보험료 조정"; echo $nRow['sunso']; echo "시작나이"; echo $nRow[sPreminum]; echo "끝나이"; echo $nRow[ePreminum];echo "월보험료"; echo $nRow[mPreminum];echo"<br>";
			//kb
			//$update3="UPDATE 2012Cpreminum SET mPreminum='$daumPr[$t]' WHERE num='$cpNum' ";
			//현대
			$update3="UPDATE 2012Cpreminum SET mPreminum='$daumPr[$t]',sPreminum='$daumsPreminum[$t]',ePreminum='$daumePreminum[$t]',InsuraneCompany='4' WHERE num='$cpNum' ";
			echo $update3; echo"<br>";
			//mysql_query($update3,$connect);

		}

	}*/

		
/*echo "To 인하 <bR>";
echo "   테스트 대리운전에 있는  264명 일괄 해지";echo "<BR>";
	echo "    1-1. 2012DaeriMember Table에서  CertiTableNum=2479 이고 push가 4  즉 정상인 기사를 찾어서";echo "<BR>";
	echo "     1-2. push =2 update 하면 해지됨";echo "<BR>";
   
$sql3="SELECT * FROM 2012DaeriMember WHERE  CertiTableNum='2479' and push='4' order by Jumin desc"; // 테스트증권에 등록된 인원 중 정상인 인원들을 뽑고

         $rs3=mysql_query($sql3,$connect); 
         $Rnum3=mysql_num_rows($rs3);

		echo "테스트 대리운전에 등록된 인원  "; echo  $Rnum3;  echo "명 <br>"; // 인원 수를 띄워라

		for($i=0;$i<$Rnum3;$i++){
			$row=mysql_fetch_array($rs3);
			echo $i; echo "번째 [성명]" ; echo $row[Name]; echo $row[num]; echo "<br>"; // 인원 수를 띄우는 방법 (for문으로 하나하나씩)

			$update ="UPDATE 2012DaeriMember SET push='2' WHERE num='$row[num]' "; // 그 인원수의 상태를 push 2 로 변경하는 업데이트 문

				echo $update ; echo "<br>";
			//mysql_query($update,$connect); // 실제 실행하는 쿼리


		}*/


/*$sql="SELECT * FROM 2012DaeriMember WHERE CertiTableNum='3064' and push='4' order by  OutPutDay desc";  //

	$rs=mysql_query($sql,$connect);
	$Rnum=mysql_num_rows($rs);
	echo "대리기사 인원"; echo $Rnum;echo "<BR>";echo "<BR>";
	
	
	for($j=0;$j<$Rnum;$j++){

	    $Row=mysql_fetch_array($rs);
		$cNum=$Row['num'];
		$dNum=$Row['2012DaeriCompanyNum'];
		
		 
		$k=$j+1;
				$eSql="UPDATE 2012DaeriMember SET etag='3' WHERE num='$Row[num]' ";
				
		//		mysql_query($eSql,$connect);
		echo $k; echo $Row[Name]; echo "||";  echo $Row[Jumin]; echo "||"; echo $Row[Hphone];  echo "||"; echo $Row[etag]; echo "<br>";

	}*/
	
//대리운전 업체 //아이디개수가 2개 이상인 업체	
/*	echo "2012DaeriCompany Table  hphone 과 2012Costomer Table 의 연락처 일치 여부 확인하는 작업"; echo "<BR>";
	echo "/2012/pop_up/ajax/php/coSms.php 에서   읽기전용이 아닌 아이디 개수  2개 이상인 경우는  2012Costomer Table 의 연락처로 문자 통보하고 "; echo "<BR>";
	echo "1개인 경우는 2012DaeriCompany Table  연락처로 문자 발송으로 2025-03-27 수정함 ";echo "<BR>";
	$Csql="SELECT * FROM 2012DaeriCompany ";
	//$Csql="SELECT * FROM 2012DaeriCompany where num='622'";
	$Crs=mysql_query($Csql,$connect);

	$Rnum3=mysql_num_rows($Crs);
	echo "대리운전회사 수"; echo $Rnum3;echo "<BR>";
	for($i=0;$i<$Rnum3;$i++){	
		$Crow=mysql_fetch_array($Crs);


		$a[1]=$Crow[company];
		$dNum=$Crow[num];
		

		$id_sql="SELECT * FROM 2012Costomer WHERE 2012DaeriCompanyNum='$dNum' and readIs!='1'"; //읽기전용이 아닌 아이디 개수 

		//echo $id_sql;

		$id_rs=mysql_query($id_sql,$connect);

		$idCount=mysql_num_rows($id_rs);

		for($m_=0;$m_<$idCount;$m_++){
			$id_rows=mysql_fetch_array($id_rs);
			$costomePhone=$id_rows['hphone'];

			if($Crow[hphone]!=$costomePhone){	

				$corectPhone='다름';
				echo $dNum; echo "||"; echo $a[1]; echo $Crow[hphone]; echo "||"; echo $idCount; echo "///"; echo $costomePhone;echo"//"; echo $corectPhone;  echo "<BR>";
			}
				
			$corectPhone='';
		}
	}
	*/

/*
	echo "2012CertiTable 에서 startyDay 2024-03-31  ";echo "<BR>";

	$now = date('Y-m-d H:i:s');  // 현재 날짜/시간 정의*/
/*
$sql = "SELECT * FROM `2012CertiTable` WHERE startyDay>='2024-03-31'";
$rs = mysql_query($sql, $connect);
$rNum = mysql_num_rows($rs);

for($i = 0; $i < $rNum; $i++) {
    $row = mysql_fetch_array($rs);
    $dNum = $row['2012DaeriCompanyNum'];
    $cNum = $row['num'];
    
    echo $i; echo "/"; echo $dNum; echo "|"; echo $cNum; echo "||"; 
    echo $row['InsuraneCompany']; echo "||"; echo $row['startyDay']; echo "<BR>";
    
    $cSql = "SELECT * FROM `2012Cpreminum` WHERE CertiTablenum='$cNum'";
   // echo $cSql;
    
    $cRs = mysql_query($cSql, $connect);
    $c_Num = mysql_num_rows($cRs);
    
    for($j = 0; $j < $c_Num; $j++) {
        $cRow = mysql_fetch_array($cRs);
        $rowNum = $j + 1;
        
        echo $cNum; echo "시작"; echo $cRow['sPreminum']; echo "끝"; 
        echo $cRow['ePreminum']; echo "보험료"; echo $cRow['mPreminum']; echo "<BR>";
        $start_month=$cRow['sPreminum'];
		$end_month=$cRow['ePreminum'];
        $monthly_premium1 = $cRow['mPreminum'] ? $cRow['mPreminum'] : 'NULL';
		$monthly_premium2 = 'NULL';
		$monthly_premium_total = $cRow['mPreminum'] ? $cRow['mPreminum'] : 'NULL';
		$payment10_premium1 = 'NULL';
		$payment10_premium2 = 'NULL';
		$payment10_premium_total = 'NULL';

		$insertQuery = "INSERT INTO kj_premium_data (
			cNum, rowNum, start_month, end_month,
			monthly_premium1, monthly_premium2, monthly_premium_total,
			payment10_premium1, payment10_premium2, payment10_premium_total,
			created_at, updated_at
		) VALUES (
			'$cNum', $rowNum, $start_month, $end_month,
			" . ($monthly_premium1 != 'NULL' ? $monthly_premium1 : "NULL") . ", 
			NULL, 
			" . ($monthly_premium_total != 'NULL' ? $monthly_premium_total : "NULL") . ", 
			NULL, NULL, NULL,
			'$now', '$now'
		)";
        //echo  $insertQuery; echo "<br>";
        $insertResult = mysql_query($insertQuery);
        
        // 쿼리 실행 결과 확인을 위한 추가 코드
        if (!$insertResult) {
            echo "Error: " . mysql_error() . "<BR>";
        }
    }
}*/




/*
// 현재 시간 설정
$now = date("Y-m-d H:i:s");

// 데이터베이스 연결 (이 부분은 이미 연결이 되어 있다고 가정)
// $connect = mysql_connect("호스트명", "사용자명", "비밀번호");
// mysql_select_db("데이터베이스명", $connect);

// 기존 데이터 삭제 (선택사항)
// $truncateQuery = "TRUNCATE TABLE kj_insurance_premium_data";
// mysql_query($truncateQuery, $connect);

$sql = "SELECT * FROM 2012Certi WHERE sigi>='2024-03-31'";
// 쿼리 실행
$result = mysql_query($sql, $connect);

// 결과 처리
if ($result) {
    $i = 0; // 카운터 초기화
    while ($row = mysql_fetch_assoc($result)) {
        // 문자열 인코딩 변환 (EUC-KR -> UTF-8)
        foreach ($row as $key => $value) {
            if (!is_numeric($value) && !empty($value)) {
                $converted = @iconv("EUC-KR", "UTF-8", $value);
                $row[$key] = ($converted !== false) ? $converted : $value;
            }
        }
        
        $i++;
        echo $i; echo "번 ";  echo $row['certi']; echo " 증권번호 ";
        echo "25세 "; echo $row['preminun25']; echo " 44세 "; echo $row['preminun44']; 
        echo " 49세 "; echo $row['preminun49']; echo " 50세 "; echo $row['preminun50']; 
        echo " 60세 "; echo $row['preminun60']; echo " 66세 "; echo $row['preminun66']; 
        echo " 61세 "; echo $row['preminun61']; echo "//"; echo "<BR>";
        
        // 각 연령대별 보험료 정보 삽입 (6개 케이스를 루프로 처리)
        for ($j = 0; $j < 6; $j++) {
            $rowNum = $j + 1;
            switch($rowNum) {
                case 1:
                    $start_month = 19;
                    $end_month = 28;
                    $premium_field = 'preminun25';
                    break;
                case 2:
                    $start_month = 29;
                    $end_month = 40;
                    $premium_field = 'preminun44';
                    break;
                case 3:
                    $start_month = 41;
                    $end_month = 49;
                    $premium_field = 'preminun49';
                    break;
                case 4:
                    $start_month = 50;
                    $end_month = 55;
                    $premium_field = 'preminun50';
                    break;
                case 5:
                    $start_month = 56;
                    $end_month = 65;
                    $premium_field = 'preminun60';
                    break;
                case 6:
                    $start_month = 66;
                    $end_month = 99;
                    $premium_field = 'preminun66';
                    break;
            }
            
            // 보험료가 존재하는 경우에만 데이터 삽입
            if (!empty($row[$premium_field])) {
                // 숫자만 추출 (쉼표 제거 및 숫자가 아닌 문자 제거)
                $premium_value = preg_replace('/[^0-9]/', '', $row[$premium_field]);
                
                // 값이 비어있으면 NULL로 처리
                $payment10_premium1 = !empty($premium_value) ? $premium_value : "NULL";
                $payment10_premium_total = $payment10_premium1;
                
                $certi = mysql_real_escape_string($row['certi']);
                $insertQuery = "INSERT INTO kj_insurance_premium_data (
                    policyNum, rowNum, start_month, end_month,
                    payment10_premium1, payment10_premium2, payment10_premium_total,
                    created_at, updated_at
                ) VALUES (
                    '$certi', $rowNum, $start_month, $end_month,
                    $payment10_premium1, NULL, $payment10_premium_total,
                    '$now', '$now'
                )";
                
                echo $insertQuery; echo "<BR>";
                
                // 쿼리 실행
              //  $insertResult = mysql_query($insertQuery, $connect);
                
                // 오류가 발생하면 출력
                if(!$insertResult) {
                    echo "오류: " . mysql_error() . "<br>";
                }
            }
        }
    }
    
   // echo "데이터 처리가 완료되었습니다. 총 " . $i . "개의 보험증권이 처리되었습니다.";
} else {
    echo "쿼리 실행 실패: " . mysql_error();
} 

// 연결 종료
mysql_close($connect);
?>








// 응답 데이터 구성
$response = array(
   "success" => true,
   "data" => $data,
  
   
);
//echo $response;
// PHP 4.4 호환 JSON 인코딩으로 출력
//echo json_encode_php4($response);*/
	?>
</body>
</html>