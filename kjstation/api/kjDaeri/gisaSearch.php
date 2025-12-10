<?php
header("Content-Type: application/json; charset=utf-8");
include '../cors.php'; // CORS 헤더 설정 파일
include '../json4version.php'; // PHP 4 JSON 변환 함수
include '../dbcon.php'; // 데이터베이스 연결 설정
include "./php/encryption.php";
include "./php/calculatePersonalRate.php"; // 함수를 먼저 include
// GET 파라미터 가져오기 및 정수로 변환
$sj = isset($_GET['sj']) ? $_GET['sj'] : '';
$gisa_name = isset($_GET['gisa_name']) ? $_GET['gisa_name'] : '';
$gisa_name=iconv("UTF-8","EUC-KR", $gisa_name);
$gisaPushSangtae = isset($_GET['gisaPushSangtae']) ? $_GET['gisaPushSangtae'] : '';

// 접근 권한 확인
if ($sj != 'gisa_') {
   // 잘못된 접근일 경우 오류 응답 반환 후 종료
   $response = array(
       "success" => false,
       "message" => "잘못된 접근"
   );
   echo json_encode_php4($response);
   exit; // 중요: 이후 코드 실행 방지
}

// where절 조건 초기화
$where2 = "";
if($gisaPushSangtae == 1){
    $where2 = "and m.push='4'";
}

//LEFT JOIN `2019rate` r ON m.dongbuCerti = r.policy AND m.Jumin = r.jumin

// 회원 목록 조회 쿼리
$sql = "SELECT  m.num,m.Name,m.nai,m.dongbuCerti, m.Jumin, m.push, m.wdate, m.InPutDay, m.OutPutDay,m.2012DaeriCompanyNum,
		m.CertiTableNum,m.EndorsePnum,m.InsuranceCompany,m.endorse_day,m.etag,m.manager,m.Hphone,m.cancel,
       c.company 
FROM `DaeriMember` m
LEFT JOIN `2012DaeriCompany` c ON m.`2012DaeriCompanyNum` = c.num
		 WHERE m.Name like '%".addslashes($gisa_name)."%' $where2 order by Jumin asc";
//echo $sql;
// 쿼리 실행
$result = mysql_query($sql, $conn);
$data = array();

// 결과 처리
if ($result) {
   while ($row = mysql_fetch_assoc($result)) {
       // 문자열 인코딩 변환 (EUC-KR -> UTF-8)
       foreach ($row as $key => $value) {
           if (!is_numeric($value) && !empty($value)) {
               $converted = @iconv("EUC-KR", "UTF-8", $value);
               $row[$key] = ($converted !== false) ? $converted : $value;
           }
       }
		include "./php/decrptJuminHphone.php"; 
		$jumin = $row['Jumin'];
        $policyNum = $row['dongbuCerti'];
		 if (!empty($jumin) && !empty($policyNum)) {
            $rateSql = "SELECT rate FROM 2019rate WHERE policy='$policyNum' AND jumin='$jumin' LIMIT 1";
            $rateResult = mysql_query($rateSql, $conn);
            if ($rateResult && $rateRow = mysql_fetch_assoc($rateResult)) {
                $row['rate'] = $rateRow['rate'];
            } else {
                $row['rate'] = null;
            }
        } else {
            $row['rate'] = null;
        }


		// 개인 할인율 계산
        $discountRate = calculatePersonalRate($row['rate']);
        $row['discountRate'] = $discountRate['rate'];
        $cNum = $row['CertiTableNum'];
		$age = $row['nai'];

		//보험료 계산 // 보험료 계산
        include "./php/premiumSearch.php";
        $row['monthly_premium_total'] = $monthly_premium_total;  //월별 보험료
		$AdjustedInsuranceMothlyPremium=floor(($monthly_premium_total*$discountRate['rate'])/10)*10;
		$row['AdjustedInsuranceMothlyPremium']=$AdjustedInsuranceMothlyPremium;
		//$total_AdjustedInsuranceMothlyPremium+=$AdjustedInsuranceMothlyPremium;


        $row['payment10_premium_total'] = $payment10_premium_total; // 보험회사 내는 보험료 1/10;
		$AdjustedInsuranceCompanyPremium=floor(($payment10_premium_total*$discountRate['rate'])/10)*10;
		$row['AdjustedInsuranceCompanyPremium']=$AdjustedInsuranceCompanyPremium;
		//$total_AdjustedInsuranceCompanyPremium+=$AdjustedInsuranceCompanyPremium;
        
		$row['ConversionPremium']=floor(($AdjustedInsuranceCompanyPremium*10/12)/10)*10; //보험회사 보험료를 월 환산
       $data[] = $row;
   }
}

// 응답 데이터 구성
$response = array(
   "success" => true,
  //  "sql" => $sql,
   "data" => $data
);

// PHP 4.4 호환 JSON 인코딩으로 출력
echo json_encode_php4($response);

// 데이터베이스 연결 닫기
mysql_close($conn);
?>