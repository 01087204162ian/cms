<?php
header("Content-Type: application/json; charset=utf-8");
require_once '../../../api/config/db_config.php';
// PDO 연결 가져오기 (db_config.php에서 정의된 함수 사용)
$pdo = getDbConnection();
// POST 파라미터 가져오기 (널 병합 연산자 사용)
$dnum = $_POST['cNum'] ?? '';
$page = (int)($_POST['page'] ?? 1);
$perPage = 10; // 페이지당 결과 수를 10으로 변경 (기존 50에서 변경)
$data = [];
$totalRecords = 0;
$folder = null;

// folder 값 가져오기
$sql = "SELECT folder FROM `2012DaeriCompany` WHERE num = :dnum";
$stmt = $pdo->prepare($sql);
$stmt->bindParam(':dnum', $dnum, PDO::PARAM_INT);
$stmt->execute();
$result = $stmt->fetch(PDO::FETCH_ASSOC);

if ($result) {
    $folder = $result['folder'];
}

// folder로 accident_data 테이블 조회하기
if (!empty($folder)) {
    // 총 레코드 수 계산을 위한 쿼리
    $countSql = "SELECT COUNT(*) FROM accident_data WHERE folder = :folder";
    $countStmt = $pdo->prepare($countSql);
    $countStmt->bindParam(':folder', $folder, PDO::PARAM_INT);
    $countStmt->execute();
    $totalRecords = $countStmt->fetchColumn();
    
    // 페이지네이션을 위한 오프셋 계산
    $offset = ($page - 1) * $perPage;
    
    // 실제 데이터를 가져오는 쿼리
    $sql = "SELECT * FROM accident_data WHERE folder = :folder ORDER BY accident_date DESC LIMIT :offset, :perPage";
    $stmt = $pdo->prepare($sql);
    $stmt->bindParam(':folder', $folder, PDO::PARAM_INT);
    $stmt->bindParam(':offset', $offset, PDO::PARAM_INT);
    $stmt->bindParam(':perPage', $perPage, PDO::PARAM_INT);
    $stmt->execute();
    
    // fetchAll() 대신 fetch()를 사용하여 데이터 가져오기
    $data = [];
    while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
        $data[] = $row;
    }
}

// 응답 데이터 구성
$response = [
    "success" => true,
    "sql" => $sql ?? '',
    "dnum" => $dnum,
    "folder" => $folder,
    "page" => $page,
    "totalRecords" => $totalRecords,
    "totalPages" => ceil($totalRecords / $perPage),
    "perPage" => $perPage,
    "data" => $data
];

// JSON 출력 (UTF-8 인코딩)
echo json_encode($response, JSON_UNESCAPED_UNICODE);
?>