<?php
header("Content-Type: application/json; charset=utf-8");
include '../cors.php'; // CORS 헤더 설정 파일
include '../json4version.php'; // PHP 4 JSON 변환 함수
include '../dbcon.php'; // 데이터베이스 연결 설정
include "./php/monthlyFee.php"; // 월보험료 산출 함수 
include "./php/calculatePersonalRate.php"; 
include "./php/encryption.php";
// MySQL 연결 시 UTF-8 강제 적용
mysql_query("SET NAMES utf8", $conn);

// GET 파라미터 가져오기
$endorse_day = isset($_GET['endorse_day']) ? $_GET['endorse_day'] : ''; 
$dNum = isset($_GET['dNum']) ? $_GET['dNum'] : '';  
$cNum = isset($_GET['cNum']) ? $_GET['cNum'] : '';  
$pNum = isset($_GET['pNum']) ? $_GET['pNum'] : '';  

$data = array();
$main_info = array();
//배서일 기준일으로 월 보험료 산출, 보험사 보험료 산출하자
		//1. 월보험료 산출 
		//   대리업체 정기결제일
		//   배서발생일 (배서기준일) $endorse_day
		//   나이  $age
		//   월보험료  $monthly_premium1,$monthly_premium2,$monthly_premium_total
		//   보험회사  $InsuraneCompany
		//  

if (!empty($cNum) && !empty($pNum)) {
    // 첫 번째 쿼리: 최상위 정보 가져오기
    $sql_main = "
        SELECT 
            dc.company, dc.Pname, dc.hphone, dc.MemberNum,dc.FirstStart,
            ct.startyDay AS startyDay, ct.nabang,ct.nabang_1, ct.divi, ct.policyNum, ct.InsuraneCompany
        FROM DaeriMember dm
        INNER JOIN 2012DaeriCompany dc ON dm.2012DaeriCompanyNum = dc.num
        INNER JOIN 2012CertiTable ct ON dm.CertiTableNum = ct.num
        WHERE dm.CertiTableNum = '$cNum'
        AND dm.EndorsePnum = '$pNum'
        LIMIT 1
    ";

    $result_main = mysql_query($sql_main, $conn);
    if ($result_main && mysql_num_rows($result_main) > 0) {
        $main_info = mysql_fetch_assoc($result_main); // 한 개의 행만 가져옴
        foreach ($main_info as $key => $value) {
            if (!is_numeric($value) && !empty($value)) { // NULL, 숫자는 변환하지 않음
                $converted = @iconv("EUC-KR", "UTF-8//IGNORE", $value);
                $main_info[$key] = ($converted !== false) ? $converted : utf8_encode($value);
            }
        }
    }

//$startDay=$main_info['startyDay']; //보험시작일
//$nabang=$main_info['nabang'];
//$nabang_1=$main_info['nabang_1'];
    // 두 번째 쿼리: data 배열에 들어갈 정보 가져오기
    $sql_data = "
        SELECT 
			m.num,m.Name,m.Jumin,m.nai,m.push,m.ch,m.etag,m.dongbuCerti,m.InsuranceCompany,m.reasion,m.manager
			
        FROM DaeriMember m
        WHERE m.CertiTableNum = '$cNum'
        AND m.EndorsePnum = '$pNum'
		AND m.sangtae='1'
		order by m.Jumin desc
    ";

//echo  $sql_data;
    $result_data = mysql_query($sql_data, $conn);
    if ($result_data && mysql_num_rows($result_data) > 0) {
        while ($row = mysql_fetch_assoc($result_data)) {

			   $age=$row['nai'];
				$InsuraneCompany=$row['InsuranceCompany'];
				$etag=$row['etag']; // "1": "대리", "2": "탁송", "3": "대리렌트", "4": "탁송렌트" 

				$policyNum=$row['dongbuCerti'];
				include "./php/decrptJuminHphone.php"; 
				$jumin=$row['Jumin'];
				$ju=explode('-',$jumin); 
			    //$personRate = $row['rate'];

				//기준일 $m 
				//(보험증권의 시작일 또는 배서기준일(DB손보))
				//$Crow['startyDay']=$startDay //nai.php 사용하기 위함 변수

				$Csql =  "SELECT * FROM 2012Certi WHERE certi='$policyNum' ";

				$Crs = mysql_query($Csql, $conn); // $conn는 dbcon.php에서 포함됨
				$Crow = mysql_fetch_array($Crs);
				$startDay=$Crow['sigi']; //보험시작일
				$nabang=10;     //분납횟수
				$nabang_1=$Crow['nab']; //분납회차
				$Crow['startyDay']=$startDay;
				include "./php/nai.php";
				

				$personInfo = calculateAge($ju[0], $ju[1], $m); 
				$age=$personInfo['age'];

				include "./php/rateSearch.php";//($jumin,$policyNum 사용함)
				$personRate = $row['rate'];
			//	include "./php/personRate.php"; //$personRate2 리턴함
			    $discountRate = calculatePersonalRate($row['rate']);
			
				// 대리업체 정기결제일 $dueDay 
				// calculateProRatedFee 함수에서 사용
				include "./php/dNum_search.php"; 
				
				// 배서발생일 calculateProRatedFee 함수에서 사용
				list($eYear, $eMonth, $eDay) = explode("-", $endorse_day, 3);// 배서발생일
	
				// 보험료부터 찾기
					// $cNum, $age 로 kj_premium_data Table 보험료 찾기
					// 월 기본보험료 $monthly_premium1
					// 월 렌트보험료 $monthly_premium2, 
					// 합계 보험료  $monthly_premium_total
					//  calculateProRatedFee 함수에서 사용
					// (1/10) 정상 기본보험료 $payment10_premium1
					// (1/10) 정상 렌트보험료$payment10_premium2
					// (1/10) 합계 $payment10_premium_total
					//calculateEndorsePremium 함수에서 사용
				include "./php/premiumSearch.php";

			
				 $insurancePremiumData=calculateProRatedFee($monthly_premium1,$monthly_premium2,$monthly_premium_total,$InsuraneCompany, $dueDay,$eDay, $eMonth, $eYear,$discountRate['rate'],$etag);
				  $row['Endorsement_insurance_premium'] = (int)$insurancePremiumData['proRatedFee'];

				 $EndorsePremiumYpremium=calculateEndorsePremium($startDay, $endorse_day, $nabang, $nabang_1,$payment10_premium1, $payment10_premium2,$payment10_premium_total,$InsuraneCompany,$discountRate['rate'], $etag);
				$row['Endorsement_insurance_company_premium']= (int)$EndorsePremiumYpremium['i_endorese_premium'];
			
				// 상세 계산 정보를 Debug 
			   include "./php/endorseDebug.php";

            foreach ($row as $key => $value) {
                if (!is_numeric($value) && !empty($value)) { // NULL, 숫자는 변환하지 않음
                    $converted = @iconv("EUC-KR", "UTF-8//IGNORE", $value);
                    $row[$key] = ($converted !== false) ? $converted : utf8_encode($value);
                }
            }
            $data[] = $row;
        }
    }
}

// 응답 데이터 구성
$response = array(
    "success" => true
);

// 최상위 데이터를 추가
if (!empty($main_info)) {
    $response = array_merge($response, $main_info);
}

// 배열 데이터를 추가
$response["data"] = $data;

// JSON 변환 후 출력 (PHP 4.4 대응)
echo json_encode_php4($response);

// 데이터베이스 연결 닫기
mysql_close($conn);
?>
