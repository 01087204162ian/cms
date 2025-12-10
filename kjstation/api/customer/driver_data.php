<?php
session_start(); // 세션은 항상 가장 먼저 시작해야 함
header("Content-Type: application/json; charset=utf-8");
include '../cors.php';
include '../json4version.php';
include '../dbcon.php';
include "../kjDaeri/php/calculatePersonalRate.php"; // 함수를 먼저 include
include "../kjDaeri/php/encryption.php";
// 데이터 배열 초기화
$data = array();
// POST 데이터 검증 및 필터링
$dNum = '';
if (isset($_POST['cNum'])) {
    $dNum = trim($_POST['cNum']);
    $dNum = mysql_escape_string($dNum); // PHP 4.4에서는 mysql_real_escape_string 대신 이 함수 사용
}

// 이름 검색 조건 처리
$where2 = "";
$where3 = "";
if (isset($_POST['name']) && !empty($_POST['name'])) {
    $searchName = @iconv( "UTF-8//IGNORE","EUC-KR", trim($_POST['name']));
    $searchName = mysql_escape_string($searchName);
    $where2 = " AND Name LIKE '%$searchName%'";
}

$sql_data = "SELECT num,`2012DaeriCompanyNum`,InsuranceCompany,CertiTableNum,Name,Jumin, ";
$sql_data .="nai,push,etag,Hphone,dongbuCerti,cancel";
$sql_data .=" FROM DaeriMember WHERE 2012DaeriCompanyNum='$dNum'  and (push ='4' or (push='1' and sangtae='1')) $where2 $where3 order by Jumin asc";
            
$result_data = mysql_query($sql_data, $conn);
if ($result_data && mysql_num_rows($result_data) > 0) {
    while ($row = mysql_fetch_assoc($result_data)) {
        // 문자 인코딩 변환 (EUC-KR → UTF-8)
        foreach ($row as $key => $value) {
            if (!is_numeric($value) && !empty($value)) { // NULL, 숫자는 변환하지 않음
                $converted = @iconv("EUC-KR", "UTF-8//IGNORE", $value);
                if ($converted !== false) {
                    $row[$key] = $converted;
                } else {
                    $row[$key] = utf8_encode($value);
                }
            }
        }
        
        // 복호화 시도 방법 1: 기본 알고리즘 (RIJNDAEL_256, CBC 모드)
        $row['JuminDecrypted'] = "";
        $row['HphoneDecrypted'] = "";
		if ($row['Jumin'] != "") {
            // 오류 표시 방지
            $oldErrorLevel = error_reporting(0);
            
            // 방법 1: 일반 복호화
            $decrypted = @decryptData($row['Jumin']);
            if ($decrypted != "") {
                $row['JuminDecrypted'] = $decrypted;
				$row['Jumin'] = $decrypted;
            }
		}
		if ($row['Hphone'] != "") {
            // 오류 표시 방지
            $oldErrorLevel = error_reporting(0);
            
            // 방법 1: 일반 복호화
            $decrypted = @decryptData($row['Hphone']);
            if ($decrypted != "") {
                $row['HphoneDecrypted'] = $decrypted;
				$row['Hphone'] = $decrypted;
            } 
		}
		$sqlr="SELECT * FROM 2019rate WHERE policy='".$row['dongbuCerti']."' and jumin='".$row['Jumin']."'";

		$rsr=mysql_query($sqlr,$conn);

		$rowr=mysql_fetch_array($rsr);
		//$personRate=$rowr['rate'];
		$discountRate = calculatePersonalRate($rowr['rate']);
		

		$row['personRateName']=iconv("EUC-KR", "UTF-8//IGNORE", $discountRate['name']);

		// 데이터 배열에 추가
        $data[] = $row;
    }
}

// 응답 데이터 구성
$response = array(
    "success" => true,
    //"sql_data"=>$sql_data,
    "data" => $data
);

// JSON 응답 출력 (PHP 4.4 버전용 함수 사용)
echo json_encode_php4($response);
mysql_close($conn);
?>