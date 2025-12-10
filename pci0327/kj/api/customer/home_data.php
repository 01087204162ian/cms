<?php
session_start(); // 세션은 항상 가장 먼저 시작해야 함
header("Content-Type: application/json; charset=utf-8");
require_once '../../../api/config/db_config.php';

// 데이터 배열 초기화
$data = array();
$response = array(
    "success" => true,
    "data" => $data
);

try {
    // PDO 연결 - db_config.php에서 정의된 getDbConnection() 함수 사용 가정
    $pdo = getDbConnection();
    
    // POST 데이터 검증 및 필터링
    $dNum = '';
    if (isset($_POST['cNum'])) {
        $dNum = trim($_POST['cNum']);
    }
    
    // 대리운전회사 증권 찾기 - 최근 1년 내 증권 조회 (prepared statement 사용)
    $sql_data = "SELECT num, policyNum, gita, startyDay, InsuraneCompany
                FROM 2012CertiTable 
                WHERE 2012DaeriCompanyNum = :dNum 
                AND startyDay BETWEEN DATE_SUB(CURDATE(), INTERVAL 1 YEAR) AND CURDATE()";
    
    $stmt = $pdo->prepare($sql_data);
    $stmt->bindParam(':dNum', $dNum, PDO::PARAM_STR);
    $stmt->execute();
    
    if ($stmt->rowCount() > 0) {
        while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
            // 각 증권별 인원 수 조회 (prepared statement 사용)
            $mSql = "SELECT COUNT(*) as cnt FROM `2012DaeriMember` 
                    WHERE `CertiTableNum` = :certNum 
                    AND push = '4'";
            
            $mStmt = $pdo->prepare($mSql);
            $mStmt->bindParam(':certNum', $row['num'], PDO::PARAM_STR);
            $mStmt->execute();
            
            $inwonRow = $mStmt->fetch(PDO::FETCH_ASSOC);
            $row['inwon'] = $inwonRow['cnt'];
            
            // 인원이 0보다 큰 경우에만 데이터 배열에 추가
            if ($row['inwon'] > 0) {
                $data[] = $row;
            }
        }
    }
    
    // 응답 데이터 업데이트
    $response['data'] = $data;
    
} catch (PDOException $e) {
    // 오류 발생 시 오류 응답
    $response = array(
        "success" => false,
        "message" => "데이터베이스 오류: " . $e->getMessage()
    );
} finally {
    // PDO 연결 종료 (변수가 설정된 경우에만)
    if (isset($pdo)) {
        $pdo = null;
    }
}

// JSON 응답 출력 (PHP 8.2에서는 json_encode 사용)
echo json_encode($response, JSON_UNESCAPED_UNICODE);
?>