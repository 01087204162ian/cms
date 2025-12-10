<?php
header("Content-Type: application/json; charset=utf-8");
require_once '../../../api/config/db_config.php';

try {
    // PDO 연결 가져오기 (db_config.php에서 정의된 함수 사용)
    $pdo = getDbConnection();
    
    // POST 데이터 받기
    $lastDate = $_POST['lastDate'] ?? '';
    $thisDate = $_POST['thisDate'] ?? '';
    $userName = $_POST['userName'] ?? '';
    
    // 필수 매개변수 검증
    if (empty($lastDate) || empty($thisDate)) {
        $response = [
            "success" => false,
            "message" => "필수 매개변수가 누락되었습니다."
        ];
        header('Content-Type: application/json; charset=utf-8');
        echo json_encode($response, JSON_UNESCAPED_UNICODE);
        exit;
    }
    
    // 받을 보험료 조회 - SQL 인젝션 방지를 위해 prepared statement 사용
    $sql = "SELECT * FROM AdjustmentPremium 
            WHERE thisMonthDueDate >= :lastDate AND thisMonthDueDate <= :thisDate";
    
    $stmt = $pdo->prepare($sql);
    $stmt->bindParam(':lastDate', $lastDate);
    $stmt->bindParam(':thisDate', $thisDate);
    $stmt->execute();
    
    // 결과 데이터 수집
    $data = [];
    while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
       
        
        $dNum = $row['dNum'];
        
        // dNum_search.php 파일 포함 대신 함수나 클래스로 변경하는 것을 권장
         // 대리운전 회사 정보 조회 - PDO 사용
        $Dsql = "SELECT company, FirstStart, MemberNum, jumin, hphone, cNumber FROM 2012DaeriCompany WHERE num = :dNum";
        $companyStmt = $conn->prepare($Dsql);
        $companyStmt->bindValue(':dNum', $dNum, PDO::PARAM_STR);
        $companyStmt->execute();
        $Drow = $companyStmt->fetch(PDO::FETCH_ASSOC);
        $row['company'] = $Drow['company'] ?? '';
        
      
        
        $data[] = $row;
    }
    
    // 응답 생성
    $response = [
        "success" => true,
        "count" => count($data),
        "data" => $data,
        "debug" => [
            "lastDate" => $lastDate,
            "thisDate" => $thisDate,
            "dNum" => $dNum ?? null,
            "userName" => $userName
        ]
    ];
    
    // JSON 출력
    header('Content-Type: application/json; charset=utf-8');
    echo json_encode($response, JSON_UNESCAPED_UNICODE);
    
} catch (PDOException $e) {
    $response = [
        "success" => false,
        "message" => "데이터베이스 오류: " . $e->getMessage()
    ];
    header('Content-Type: application/json; charset=utf-8');
    echo json_encode($response, JSON_UNESCAPED_UNICODE);
} catch (Exception $e) {
    $response = [
        "success" => false,
        "message" => "일반 오류: " . $e->getMessage()
    ];
    header('Content-Type: application/json; charset=utf-8');
    echo json_encode($response, JSON_UNESCAPED_UNICODE);
}
?>