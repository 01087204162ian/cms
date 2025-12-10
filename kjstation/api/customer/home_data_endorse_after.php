<?php
//배서 후 데이타 확보
//현재일 배서 데이터 확이하기 위해 
session_start();
header("Content-Type: application/json; charset=utf-8");
include '../cors.php';
include '../json4version.php';
include '../dbcon.php';
include "../kjDaeri/php/encryption.php";

// 데이터 배열 초기화
$data = array();

// POST 데이터 검증 및 필터링
$dNum = "";
if (isset($_POST['cNum'])) {
    $dNum = trim($_POST['cNum']);
    $dNum = mysql_escape_string($dNum);
}
$sql_data = "SELECT dm.Name,dm.Jumin,Hphone,dm.nai,dm.dongbuCerti,dm.endorse_day,dm.InsuranceCompany,dm.etag,dm.CertiTableNum,
                sd.preminum,sd.c_preminum,sd.push 
                FROM `DaeriMember` dm
                JOIN `SMSData` sd ON dm.`2012DaeriCompanyNum` = sd.`2012DaeriCompanyNum` 
                    AND dm.num = sd.`2012DaeriMemberNum`
                WHERE sd.endorse_day = '$now_time' 
                      AND dm.`2012DaeriCompanyNum` = '$dNum' 
                    AND sd.dagun = '1'
					AND sd.get='2'"; //미정산건만 


$result_data = mysql_query($sql_data, $conn);

// 결과 처리
if ($result_data && mysql_num_rows($result_data) > 0) {
    while ($row = mysql_fetch_assoc($result_data)) {
        // 인코딩 변환
        foreach ($row as $key => $value) {
            if (!is_numeric($value) && $value != "") {
                $converted = @iconv("EUC-KR", "UTF-8//IGNORE", $value);
                if ($converted !== false) {
                    $row[$key] = $converted;
                } else {
                    $row[$key] = utf8_encode($value);
                }
            }
        }
        
        include "../kjDaeri/php/decrptJuminHphone.php";
        
        // 데이터 배열에 추가
        $data[] = $row;
    }
}

// 응답 데이터 구성
$response = array(
    "success" => true,
   // "cNum" => $dNum,
    //"sql_data" => $sql_data,
    "data" => $data
);

// JSON 응답 출력
echo json_encode_php4($response);
mysql_close($conn);
?>