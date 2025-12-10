<?php
// 배서 후 데이터 확보
// 현재일 배서 데이터 확인하기 위해 
session_start();
header("Content-Type: application/json; charset=utf-8");
require_once '../../../api/config/db_config.php';
include "../kjDaeri/php/encryption.php";

// 데이터 배열 초기화
$data = [];
$response = [
    "success" => false,
    "data" => []
];

try {
    // PDO 연결 가져오기 (db_config.php에서 정의된 함수 사용)
    $conn = getDbConnection();
    
    // POST 데이터 검증 및 필터링
    $dNum = "";
    if (isset($_POST['cNum'])) {
        $dNum = trim($_POST['cNum']);
        // PDO의 prepared statement 사용으로 별도 이스케이프 처리 불필요
    }
    
    // 현재 날짜 설정
    $now_time = date("Y-m-d");
    
    // SQL 쿼리 - prepared statement 사용
    $sql_data = "SELECT dm.Name, dm.Jumin, Hphone, dm.nai, dm.dongbuCerti, dm.endorse_day, 
                    dm.InsuranceCompany, dm.etag, dm.CertiTableNum,
                    sd.preminum, sd.c_preminum, sd.push 
                FROM `DaeriMember` dm
                JOIN `SMSData` sd ON dm.`2012DaeriCompanyNum` = sd.`2012DaeriCompanyNum` 
                    AND dm.num = sd.`2012DaeriMemberNum`
                WHERE sd.endorse_day = :now_time 
                    AND dm.`2012DaeriCompanyNum` = :dNum 
                    AND sd.dagun = '1'
                    AND sd.get = '2'"; // 미정산건만
    
    // prepared statement 준비 및 실행
    $stmt = $conn->prepare($sql_data);
    $stmt->bindParam(':now_time', $now_time, PDO::PARAM_STR);
    $stmt->bindParam(':dNum', $dNum, PDO::PARAM_STR);
    $stmt->execute();
    
    // 결과 처리
    if ($stmt->rowCount() > 0) {
        while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
            
            // 주민번호와 휴대폰 번호 복호화
            include "../kjDaeri/php/decrptJuminHphone.php";
            
            // 데이터 배열에 추가
            $data[] = $row;
        }
    }
    
    // 성공 응답 설정
    $response["success"] = true;
    $response["data"] = $data;
    
    // PDO 연결 닫기 (명시적으로 닫을 필요 없음 - PHP가 자동으로 처리)
    $conn = null;
    
} catch (PDOException $e) {
    // 오류 처리
    $response["success"] = false;
    $response["error"] = "데이터베이스 오류: " . $e->getMessage();
} catch (Exception $e) {
    // 일반 오류 처리
    $response["success"] = false;
    $response["error"] = "오류 발생: " . $e->getMessage();
}

// JSON 응답 출력
echo json_encode($response, JSON_UNESCAPED_UNICODE | JSON_PRETTY_PRINT);
?>