<?php
/* 증권번호 또는 대리운전회사 별 배서리트스 
    // certi 처리 (certi=1은 모든 증권을 의미)
    월보험료 계산, 정상납 보험료 계산
*/
header("Content-Type: application/json; charset=utf-8");
require_once '../../../api/config/db_config.php';

// PDO 연결 가져오기 (db_config.php에서 정의된 함수 사용)
$conn = getDbConnection();
include "./php/monthlyFee.php"; // 월보험료 산출 함수 
include "./php/calculatePersonalRate.php"; // 함수를 먼저 include
include "./php/encryption.php";

// GET 파라미터 가져오기
$page = $_GET['page'] ?? 1;
$limit = $_GET['limit'] ?? 15;
$certi = $_GET['certi'] ?? '';
$dNum = $_GET['dNum'] ?? '';
$push = $_GET['push'] ?? ''; // 1 은청약 2해지
$offset = ($page - 1) * $limit;

// WHERE 조건 구성
$whereConditions = ["sangtae = '1'"];
$params = [];

// certi 처리 (certi=1은 모든 증권을 의미)
if ($certi != '' && $certi != '1') {
    $whereConditions[] = "dongbuCerti = :certi";
    $params[':certi'] = $certi;
}

// dNum 처리
if ($dNum != '') {
    $whereConditions[] = "2012DaeriCompanyNum = :dNum";
    $params[':dNum'] = $dNum;
}

if ($push != '') {
    $whereConditions[] = "push = :push";
    $params[':push'] = $push;
}

// WHERE 조건을 문자열로 결합
$whereClause = implode(' AND ', $whereConditions);

try {
    // 전체 레코드 수 조회
    $sqlCount = "SELECT COUNT(*) AS total FROM DaeriMember WHERE $whereClause";
    $stmtCount = $conn->prepare($sqlCount);
    foreach ($params as $key => $value) {
        $stmtCount->bindValue($key, $value);
    }
    $stmtCount->execute();
    $totalRecords = (int)$stmtCount->fetchColumn();
    $totalPages = ceil($totalRecords / $limit);

    // *** PUSH 값에 따른 개수 조회 ***
    $pushCounts = [];

    // 청약(push=1) 개수 조회
    $sqlPush1 = "SELECT COUNT(*) AS count FROM DaeriMember WHERE $whereClause AND push = '1'";
    $stmtPush1 = $conn->prepare($sqlPush1);
    foreach ($params as $key => $value) {
        $stmtPush1->bindValue($key, $value);
    }
    $stmtPush1->execute();
    $pushCounts['subscription'] = (int)$stmtPush1->fetchColumn();

    // 해지(push=4) 개수 조회
    $sqlPush4 = "SELECT COUNT(*) AS count FROM DaeriMember WHERE $whereClause AND push = '4'";
    $stmtPush4 = $conn->prepare($sqlPush4);
    foreach ($params as $key => $value) {
        $stmtPush4->bindValue($key, $value);
    }
    $stmtPush4->execute();
    $pushCounts['termination'] = (int)$stmtPush4->fetchColumn();

    // 전체 개수 조회
    $sqlPushOther = "SELECT COUNT(*) AS count FROM DaeriMember WHERE $whereClause";
    $stmtPushOther = $conn->prepare($sqlPushOther);
    foreach ($params as $key => $value) {
        $stmtPushOther->bindValue($key, $value);
    }
    $stmtPushOther->execute();
    $pushCounts['total'] = (int)$stmtPushOther->fetchColumn();

    // JOIN을 사용하여 필요한 데이터 가져오기
    $sql = "
        SELECT 
            num, Name, nai, dongbuCerti, Jumin, push, wdate, InPutDay, OutPutDay, `2012DaeriCompanyNum`,
            CertiTableNum, EndorsePnum, InsuranceCompany, endorse_day, etag, manager, Hphone, progress, JuminHash
        FROM DaeriMember 
        WHERE $whereClause
        ORDER BY Jumin ASC, dongbuCerti DESC, push DESC, wdate ASC, InPutDay ASC, OutPutDay ASC
        LIMIT :offset, :limit";
    
    $stmt = $conn->prepare($sql);
    foreach ($params as $key => $value) {
        $stmt->bindValue($key, $value);
    }
    $stmt->bindValue(':offset', $offset, PDO::PARAM_INT);
    $stmt->bindValue(':limit', $limit, PDO::PARAM_INT);
    $stmt->execute();
    
    $data = [];

    while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
        // 보험료 산출을 하기 위해 
        $dNum = $row['2012DaeriCompanyNum'];  // 
        $cNum = $row['CertiTableNum'];  // 
        $pNum = $row['EndorsePnum'];
        $policyNum = $row['dongbuCerti'];
        
        include "./php/decrptJuminHphone.php"; 
        $jumin = $row['Jumin'];

        $ju = explode('-', $jumin); 
        $row['jumin2'] = $ju[0] . $ju[1]; // 하이푼 없는 주민번호
        $InsuraneCompany = $row['InsuranceCompany'];  // 보험회사 
        $etag = $row['etag']; // "1": "대리", "2": "탁송", "3": "대리렌트", "4": "탁송렌트" 
        $endorse_day = $row['endorse_day'];
        $push = $row['push'];


      
        
  

        $data[] = $row;
    }

    // 응답 구성
    $response = [
        "success" => true,
        "currentPage" => $page,
        "totalPages" => $totalPages,
        "totalRecords" => $totalRecords,
        "pushCounts" => $pushCounts,
        "data" => $data
    ];

    // JSON으로 출력 (UTF-8 지원)
    echo json_encode($response, JSON_UNESCAPED_UNICODE);

} catch (PDOException $e) {
    // 오류 발생 시
    $response = [
        "success" => false,
        "message" => "데이터베이스 오류: " . $e->getMessage()
    ];
    
    echo json_encode($response, JSON_UNESCAPED_UNICODE);
} finally {
    // 연결 종료
    $conn = null;
}
?>