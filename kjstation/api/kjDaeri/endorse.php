<?php
/* 증권번호 또는 대리운전회사 별 배서리트스 
    // certi 처리 (certi=1은 모든 증권을 의미)
	월보험료 계산, 정상납  보험료 계산
*/
header("Content-Type: application/json; charset=utf-8");
include '../cors.php';
include '../json4version.php'; // PHP 4에서 JSON 변환을 위한 함수 포함
include '../dbcon.php'; // DB 연결 정보 포함
include "./php/monthlyFee.php"; // 월보험료 산출 함수 
include "./php/calculatePersonalRate.php"; // 함수를 먼저 include

include "./php/encryption.php";
// GET 파라미터 가져오기
$page = isset($_GET['page']) ? (int)$_GET['page'] : 1;
$limit = isset($_GET['limit']) ? (int)$_GET['limit'] : 15;
$certi = isset($_GET['certi']) ? $_GET['certi'] : '';
$dNum = isset($_GET['dNum']) ? $_GET['dNum'] : '';
$push = isset($_GET['push']) ? $_GET['push'] : ''; // 1 은청약 2해지
$offset = ($page - 1) * $limit;

// WHERE 조건 구성
$whereConditions = array("sangtae = '1'");

// certi 처리 (certi=1은 모든 증권을 의미)
if ($certi != '' && $certi != '1') {
    $certi = mysql_real_escape_string($certi);
    $whereConditions[] = "dongbuCerti = '$certi'";
}

// dNum 처리
if ($dNum != '') {
    $dNum = mysql_real_escape_string($dNum);
    $whereConditions[] = "2012DaeriCompanyNum = '$dNum'";
}
if ($push != '') {
    $push = mysql_real_escape_string($push);
    $whereConditions[] = "push = '$push'";
}
// WHERE 조건을 문자열로 결합
$whereClause = implode(' AND ', $whereConditions);

// 전체 레코드 수 조회 (PHP 4.4 호환 방식)
$sqlCount = "SELECT COUNT(*) AS total 
             FROM DaeriMember 
             WHERE $whereClause";
$countResult = mysql_query($sqlCount, $conn);
$rowCount = mysql_fetch_array($countResult);
$totalRecords = (int)$rowCount['total'];
$totalPages = ceil($totalRecords / $limit);

// *** PUSH 값에 따른 개수 조회 ***
$pushCounts = array();

// 청약(push=1) 개수 조회
$sqlPush1 = "SELECT COUNT(*) AS count 
             FROM DaeriMember 
             WHERE $whereClause AND push = '1'";
$resultPush1 = mysql_query($sqlPush1, $conn);
if ($resultPush1 && $rowPush1 = mysql_fetch_assoc($resultPush1)) {
    $pushCounts['subscription'] = intval($rowPush1['count']);
}

// 해지(push=4) 개수 조회
$sqlPush4 = "SELECT COUNT(*) AS count 
             FROM DaeriMember 
             WHERE $whereClause AND push = '4'";
$resultPush4 = mysql_query($sqlPush4, $conn);
if ($resultPush4 && $rowPush4 = mysql_fetch_assoc($resultPush4)) {
    $pushCounts['termination'] = intval($rowPush4['count']);
}

// 기타 push 값 개수 조회 (1과 4가 아닌 모든 값)
$sqlPushOther = "SELECT COUNT(*) AS count 
                FROM DaeriMember
                WHERE $whereClause ";
$resultPushOther = mysql_query($sqlPushOther, $conn);
if ($resultPushOther && $rowPushOther = mysql_fetch_assoc($resultPushOther)) {
    $pushCounts['total'] = intval($rowPushOther['count']);
}



// JOIN을 사용하여 필요한 데이터 가져오기
$sql = "
    SELECT 
        num,Name,nai,dongbuCerti, Jumin, push, wdate, InPutDay, OutPutDay,2012DaeriCompanyNum,
		CertiTableNum,EndorsePnum,InsuranceCompany,endorse_day,etag,manager,Hphone,progress,JuminHash
    FROM DaeriMember 
    WHERE $whereClause
    ORDER BY  Jumin ASC,dongbuCerti DESC,push DESC,wdate ASC, InPutDay ASC, OutPutDay ASC
    LIMIT $offset, $limit";
//echo $sql;
$result = mysql_query($sql, $conn);
$data = array();

if ($result) {
    while ($row = mysql_fetch_assoc($result)) {


		//보험료 산출을 하기 위해 
			$dNum=$row['2012DaeriCompanyNum'];  // 
			$cNum=$row['CertiTableNum'];  // 
			$pNum=$row['EndorsePnum'];
			$policyNum=$row['dongbuCerti'];
			
			
			include "./php/decrptJuminHphone.php"; 
			$jumin=$row['Jumin'];

			$ju=explode('-',$jumin); 
			$row['jumin2']=$ju[0].$ju[1]; //하이푼 없는 주민번호
			$InsuraneCompany=$row['InsuranceCompany'];  //보험회사 
			$etag=$row['etag']; // "1": "대리", "2": "탁송", "3": "대리렌트", "4": "탁송렌트" 
			$endorse_day=$row['endorse_day'];
			$push = $row['push'];
			include "./php/rateSearch.php";

			
			$personRate = $row['rate'];
		//	include "./php/personRate.php"; //$personRate2 리턴함
			$discountRate = calculatePersonalRate($row['rate']);
			// CertiTableNum  조회하여 증권의 
			// 보험시작일 $startDay     
			// 분납횟수    $nabang  
			// 분납회차    $nabang_1 
			// calculateProRatedFee 함수에서 사용
			include "./php/certi_search.php"; 

			//기준일 $m 
			//(보험증권의 시작일 또는 배서기준일(DB손보))
			include "./php/nai.php"; 
			//echo "m"; echo $m[0];
		    $personInfo = calculateAge($ju[0], $ju[1], $m); 
			$age=$personInfo['age'];
	
			//echo "age"; echo $age;

			if($push==1){ //나이 계산
				//echo "age"; echo $age;
				$update ="UPDATE `DaeriMember` SET nai='$age' WHERE num='".$row['num']."' ";
				mysql_query($update,$conn);

				$row['nai']=$age;
			}

			 
			// calculateProRatedFee 함수에서 사용
			include "./php/dNum_search.php";  // 대리업체 정기결제일 $dueDay
			$row['company']=$Drow['company'];
			$row['MemberNum']=$Drow['MemberNum'];
			//echo "dueDay"; echo $dueDay;
			// 배서발생일 calculateProRatedFee 함수에서 사용
			list($eYear, $eMonth, $eDay) = explode("-", $endorse_day, 3);
			
			// 보험료부터 찾기
			// $cNum, $age 로 kj_premium_data Table 보험료 찾기
			// 월 기본보험료 $monthly_premium1
			// 월 렌트보험료 $monthly_premium2, 
			// 합계 보험료  $monthly_premium_total
			//  calculateProRatedFee 함수에서 사용
			// (1/10) 정상 기본보험료 $payment10_premium1
			// (1/10) 정상 렌트보험료$payment10_premium2
			// (1/10) 합계 $payment10_premium_total
			//
			//echo "cNum"; echo $cNum; echo "policyNum";echo $policyNum;

			/* 보험료 산출은  $row['rate'] 입력된 경우만 산출하자*/
			if($row['rate']){
					include "./php/premiumSearch.php";
						//echo "dueDay"; echo $dueDay;

					//월보험료 산출
					$insurancePremiumData = calculateProRatedFee($monthly_premium1, $monthly_premium2, $monthly_premium_total, $InsuraneCompany, $dueDay, $eDay, $eMonth, $eYear, $discountRate['rate'], $etag);
					$row['Endorsement_insurance_premium'] = (int)$insurancePremiumData['proRatedFee'];


					//$row['discountRate']=$discountRate['rate'];
					//보험회사 보험료 산출
					$EndorsePremiumYpremium=calculateEndorsePremium($startDay, $endorse_day, $nabang, $nabang_1,$payment10_premium1, $payment10_premium2,$payment10_premium_total,$InsuraneCompany,$discountRate['rate'], $etag);
					$row['Endorsement_insurance_company_premium']= (int)$EndorsePremiumYpremium['i_endorese_premium'];
			}
			// 상세 계산 정보를 Debug 
	//include "./php/endorseDebug.php";

		//중복여부 판단
		//if($push==1){
			//$jumin을 조회할려면 해시값으로 조회한다

			$hashJumin=$row['JuminHash'];
			$dSql="SELECT num from  DaeriMember  WHERE JuminHash='$hashJumin' and push='4'  ";
		    $dSql.=" and CertiTableNum!='$cNum' ";

			//$dSql="SELECT num from  `DaeriMember`  WHERE Jumin='$jumin' and push='4'  AND num!=".$row['num'];
			//$dSql.=" and CertiTableNum!='$cNum' ";

			//echo $dSql; echo "<br>";
			$drs=mysql_query($dSql,$conn);
			$drow=mysql_fetch_array($drs);

			//$row['dSql']=$dSql;
			$row['duplNum']=$drow['num'];//중복
		//}

        // 문자열 인코딩 변환 (PHP 4.4 호환 방식)
        foreach ($row as $key => $value) {
            if (!is_numeric($value) && !empty($value)) { // NULL, 숫자는 변환하지 않음
                $converted = @iconv("EUC-KR", "UTF-8", $value);
                $row[$key] = ($converted !== false) ? $converted : $value;
            }
        }
        $data[] = $row;
    }
}

// 응답 구성
$response = array(
    "success" => true,
    "currentPage" => $page,
    "totalPages" => $totalPages,
    "totalRecords" => $totalRecords,
    "pushCounts" => $pushCounts,
    "data" => $data
);

echo json_encode_php4($response);
mysql_close($conn);
?>