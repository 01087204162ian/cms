<?php 
session_start(); // 제일 위에 위치해야 함
header("Content-Type: application/json; charset=utf-8");
require_once '../../../api/config/db_config.php';

// PDO 연결 가져오기 (db_config.php에서 정의된 함수 사용)
$pdo = getDbConnection();

// POST 파라미터 가져오기 (널 병합 연산자 사용)
$cNum = $_POST['cNum'] ?? '';
$page = (int)($_POST['page'] ?? 1);
$perPage = 10; // 페이지당 결과 수

$data = [];
$totalRecords = 0;

// cNum이 비어있는지 확인
if (empty($cNum)) {
    $response = [
        "success" => false,
        "message" => "cNum 파라미터가 필요합니다.",
        "data" => []
    ];
    echo json_encode($response, JSON_UNESCAPED_UNICODE);
    exit;
}

try {
    // 총 레코드 수 계산을 위한 쿼리
    $countSql = "SELECT COUNT(*) FROM `2012Costomer` WHERE `2012DaeriCompanyNum` = :cNum AND `readIs` = '1'";
    $countStmt = $pdo->prepare($countSql);
    $countStmt->bindParam(':cNum', $cNum, PDO::PARAM_INT);
    $countStmt->execute();
    $totalRecords = $countStmt->fetchColumn();
    
    // 페이지네이션을 위한 오프셋 계산
    $offset = ($page - 1) * $perPage;
    
    // 실제 데이터를 가져오는 쿼리
    $sql = "SELECT * FROM `2012Costomer` WHERE `2012DaeriCompanyNum` = :cNum AND `readIs` = '1' ORDER BY `wdate` DESC LIMIT :offset, :perPage";
    $stmt = $pdo->prepare($sql);
    $stmt->bindParam(':cNum', $cNum, PDO::PARAM_INT);
    $stmt->bindParam(':offset', $offset, PDO::PARAM_INT);
    $stmt->bindParam(':perPage', $perPage, PDO::PARAM_INT);
    $stmt->execute();
    
    // 데이터 가져오기
    $data = [];
    while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
        // 민감한 정보 제거 (주민번호, 비밀번호 등)
        unset($row['passwd']);
        unset($row['jumin1']);
        unset($row['jumin2']);
        unset($row['pAssKey']);
        unset($row['pAssKeyoPen']);
        
        $data[] = $row;
    }
    
    // 응답 데이터 구성
    $response = [
        "success" => true,
        "message" => "데이터 조회 성공",
        "sql" => $sql,
        "cNum" => $cNum,
        "page" => $page,
        "totalRecords" => $totalRecords,
        "totalPages" => ceil($totalRecords / $perPage),
        "perPage" => $perPage,
        "data" => $data
    ];
    
} catch (PDOException $e) {
    // 데이터베이스 오류 처리
    $response = [
        "success" => false,
        "message" => "데이터베이스 오류가 발생했습니다.",
        "error" => $e->getMessage(),
        "data" => []
    ];
} catch (Exception $e) {
    // 일반 오류 처리
    $response = [
        "success" => false,
        "message" => "오류가 발생했습니다.",
        "error" => $e->getMessage(),
        "data" => []
    ];
}

// JSON 출력 (UTF-8 인코딩)
echo json_encode($response, JSON_UNESCAPED_UNICODE);
?>