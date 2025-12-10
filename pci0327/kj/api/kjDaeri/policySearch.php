<?php
header("Content-Type: application/json; charset=utf-8");
require_once '../../../api/config/db_config.php';

// PDO 연결 가져오기 (db_config.php에서 정의된 함수 사용)
$pdo = getDbConnection();

// GET 파라미터 가져오기 및 정수로 변환
$sj = isset($_GET['sj']) ? $_GET['sj'] : '';
$fromDate = isset($_GET['fromDate']) ? $_GET['fromDate'] : '';

// 접근 권한 확인
if ($sj != 'policy_') {
   // 잘못된 접근일 경우 오류 응답 반환 후 종료
   $response = array(
       "success" => false,
       "message" => "잘못된 접근"
   );
   echo json_encode($response, JSON_UNESCAPED_UNICODE);
   exit; // 중요: 이후 코드 실행 방지
}

// 증권 목록 조회 쿼리 - 준비된 구문 사용
$sql = "SELECT * FROM 2012Certi WHERE sigi >= :fromDate ORDER BY insurance DESC";
$stmt = $pdo->prepare($sql);
$stmt->bindParam(':fromDate', $fromDate, PDO::PARAM_STR);
$stmt->execute();

$data = array();

// 결과 처리
while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
    // 문자열 인코딩 변환 (EUC-KR -> UTF-8)
    foreach ($row as $key => $value) {
        if (!is_numeric($value) && !empty($value)) {
            $converted = @iconv("EUC-KR", "UTF-8", $value);
            $row[$key] = ($converted !== false) ? $converted : $value;
        }
    }
    
    // 인원 수 가져오기 - 준비된 구문 사용
    $mSql = "SELECT COUNT(*) as cnt FROM `2012DaeriMember` WHERE `dongbuCerti` = :certi AND push = '4'";
    $nStmt = $pdo->prepare($mSql);
    $nStmt->bindParam(':certi', $row['certi'], PDO::PARAM_STR);
    $nStmt->execute();
    
    if ($nStmt->rowCount() > 0) {
        $inwonRow = $nStmt->fetch(PDO::FETCH_ASSOC);
        $row['inwon'] = $inwonRow['cnt'];
    } else {
        $row['inwon'] = 0;
    }
    
    $data[] = $row;
}

// 응답 데이터 구성
$response = array(
    "success" => true,
    "data" => $data
);

// JSON 인코딩으로 출력 (PHP 8.2 기본 함수 사용)
echo json_encode($response, JSON_UNESCAPED_UNICODE);

// PDO 연결은 스크립트 종료 시 자동으로 닫히므로 명시적으로 닫을 필요 없음
?>