<?php
header("Content-Type: application/json; charset=utf-8");
require_once '../../../api/config/db_config.php';

// PDO 연결 가져오기 (db_config.php에서 정의된 함수 사용)
$pdo = getDbConnection();

// POST 
$certi = isset($_POST['num']) ? $_POST['num'] : '';

// 증권 목록 조회 쿼리 - 준비된 구문 사용
$sql = "SELECT * FROM 2012Certi WHERE certi = :certi";
$stmt = $pdo->prepare($sql);
$stmt->bindParam(':certi', $certi, PDO::PARAM_STR);
$stmt->execute();

$data = array();

// 결과 처리
while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
    
    
    // 인원 수 가져오기 - 준비된 구문 사용
    $mSql = "SELECT COUNT(*) as cnt FROM `2012DaeriMember` WHERE `dongbuCerti` = :certi AND push = '4'";
    $nStmt = $pdo->prepare($mSql);
    $nStmt->bindParam(':certi', $row['certi'], PDO::PARAM_STR);
    $nStmt->execute();
    
    $inwonRow = $nStmt->fetch(PDO::FETCH_ASSOC);
    $row['inwon'] = $inwonRow ? $inwonRow['cnt'] : 0;
    
    $data[] = $row;
}

// 응답 데이터 구성
$response = array(
    "success" => true,
    "data" => $data
);

// JSON 인코딩으로 출력 (PHP 8.2 내장 함수 사용)
echo json_encode($response, JSON_UNESCAPED_UNICODE);

// PDO 연결은 스크립트 종료 시 자동으로 닫히므로 명시적으로 닫을 필요 없음
?>