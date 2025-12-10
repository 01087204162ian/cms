<?php
header("Content-Type: application/json; charset=utf-8");
require_once '../../../api/config/db_config.php';

// PDO 연결 가져오기 (db_config.php에서 정의된 함수 사용)
$pdo = getDbConnection();

// POST 파라미터 가져오기 (널 병합 연산자 사용)
$sort = $_POST['sort'] ?? '3'; // 기본값은 1 (핸드폰 번호로 검색)
$phone = $_POST['phone'] ?? '';
$dateRange = $_POST['dateRange'] ?? '';
$company = $_POST['company'] ?? '';
$dnum = $_POST['cNum'] ?? '';
$page = (int)($_POST['page'] ?? 1);
$perPage = 10; // 페이지당 결과 수를 10으로 변경 (기존 50에서 변경)

$data = [];
$totalRecords = 0;
$sql = "";
$countSql = "";
$params = [];

// sort=1: 핸드폰 번호로 검색
if ($sort == '1' && !empty($phone)) {
    $Rphone = explode('-', $phone);
    
    if (count($Rphone) == 3) {
        // 쿼리 작성 (파라미터 바인딩 적용)
        $sql = "SELECT Rphone1, Rphone2, Rphone3, Msg, LastTime 
                FROM SMSData 
                WHERE Rphone1 = :phone1 
                AND Rphone2 = :phone2 
                AND Rphone3 = :phone3 
                AND dagun = '1' 
                ORDER BY SeqNo DESC
                LIMIT :offset, :limit";
                
        $countSql = "SELECT COUNT(*) 
                    FROM SMSData 
                    WHERE Rphone1 = :phone1 
                    AND Rphone2 = :phone2 
                    AND Rphone3 = :phone3 
                    AND dagun = '1'";
                    
        $params = [
            ':phone1' => $Rphone[0],
            ':phone2' => $Rphone[1],
            ':phone3' => $Rphone[2]
        ];
    }
}
// sort=2: 날짜 구간 선택
else if ($sort == '2') {
    if (!empty($dateRange)) {
        // 날짜 범위 분리 (시작일,종료일 형식)
        $dates = explode(',', $dateRange);
        
        if (count($dates) == 2) {
            $startDate = str_replace('-', '', $dates[0]); // YYYY-MM-DD -> YYYYMMDD
            $endDate = str_replace('-', '', $dates[1]);
            
            // 시작일과 종료일 범위로 검색 (YYYYMMDDHHMMSS 형식)
            $sql = "SELECT Rphone1, Rphone2, Rphone3, Msg, LastTime 
                    FROM SMSData 
                    WHERE LastTime >= :startDateTime 
                    AND LastTime <= :endDateTime 
                    AND dagun = '1' 
                    ORDER BY SeqNo DESC
                    LIMIT :offset, :limit";
                    
            $countSql = "SELECT COUNT(*) 
                        FROM SMSData 
                        WHERE LastTime >= :startDateTime 
                        AND LastTime <= :endDateTime 
                        AND dagun = '1'";
                        
            $params = [
                ':startDateTime' => $startDate . '000000',
                ':endDateTime' => $endDate . '235959'
            ];
        }
    } else {
        // 기존 방식: 한 달 전 날짜부터 오늘까지 (dateRange가 없는 경우)
        $oneMonthAgo = date('Ymd', strtotime('-1 month'));
        $today = date('Ymd');
        
        $sql = "SELECT Rphone1, Rphone2, Rphone3, Msg, LastTime 
                FROM SMSData 
                WHERE LastTime >= :startDateTime 
                AND LastTime <= :endDateTime 
                AND dagun = '1' 
                ORDER BY SeqNo DESC
                LIMIT :offset, :limit";
                
        $countSql = "SELECT COUNT(*) 
                    FROM SMSData 
                    WHERE LastTime >= :startDateTime 
                    AND LastTime <= :endDateTime 
                    AND dagun = '1'";
                    
        $params = [
            ':startDateTime' => $oneMonthAgo . '000000',
            ':endDateTime' => $today . '235959'
        ];
    }
}
// sort=3: 회사명 또는 대리운전회사 번호로 검색
else if ($sort == '3') {
    // 회사명이 전달된 경우
    if (!empty($company)) {
        // 쿼리 작성 (파라미터 바인딩 적용)
        $sql = "SELECT a.Rphone1, a.Rphone2, a.Rphone3, a.Msg, a.LastTime 
                FROM SMSData a
                INNER JOIN `2012DaeriCompany` dc ON a.`2012DaeriCompanyNum` = dc.num
                WHERE dc.company LIKE :companySearch 
                AND a.dagun = '1' 
                ORDER BY a.SeqNo DESC
                LIMIT :offset, :limit";
                
        $countSql = "SELECT COUNT(*) 
                    FROM SMSData a
                    INNER JOIN `2012DaeriCompany` dc ON a.`2012DaeriCompanyNum` = dc.num
                    WHERE dc.company LIKE :companySearch 
                    AND a.dagun = '1'";
                    
        $params = [
            ':companySearch' => '%' . $company . '%'
        ];
    }
    // 회사 번호가 전달된 경우 (company 없을 때만 dnum 사용)
    else if (!empty($dnum)) {
        $sql = "SELECT Rphone1, Rphone2, Rphone3, Msg, LastTime 
                FROM SMSData 
                WHERE `2012DaeriCompanyNum` = :dnum 
                AND dagun = '1' 
                ORDER BY SeqNo DESC
                LIMIT :offset, :limit";
                
        $countSql = "SELECT COUNT(*) 
                    FROM SMSData 
                    WHERE `2012DaeriCompanyNum` = :dnum 
                    AND dagun = '1'";
                    
        $params = [
            ':dnum' => $dnum
        ];
    }
}

// 결과 처리
$data = [];
$totalRecords = 0;

if (!empty($sql) && !empty($countSql)) {
    try {
        // 전체 레코드 수 조회
        $countStmt = $pdo->prepare($countSql);
        foreach ($params as $key => $value) {
            $countStmt->bindValue($key, $value);
        }
        $countStmt->execute();
        $totalRecords = (int)$countStmt->fetchColumn();
        
        // 페이지네이션 계산
        $offset = ($page - 1) * $perPage;
        
        // 데이터 조회
        $stmt = $pdo->prepare($sql);
        foreach ($params as $key => $value) {
            $stmt->bindValue($key, $value);
        }
        $stmt->bindValue(':offset', $offset, PDO::PARAM_INT);
        $stmt->bindValue(':limit', $perPage, PDO::PARAM_INT);
        $stmt->execute();
        
        while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
            $data[] = $row;
        }
    } catch (PDOException $e) {
        // 오류 처리
        $data = [];
        $errorResponse = [
            "success" => false,
            "error" => "데이터베이스 오류가 발생했습니다: " . $e->getMessage()
        ];
        echo json_encode($errorResponse, JSON_UNESCAPED_UNICODE);
        exit;
    }
}

// 응답 데이터 구성
$response = [
    "success" => true,
	"sql"=>$sql,
    "sort" => $sort,
    "phone" => $phone,
    "dateRange" => $dateRange,
    "company" => $company,
    "dnum" => $dnum,
    "page" => $page,
    "totalRecords" => $totalRecords,
    "totalPages" => ceil($totalRecords / $perPage),
    "perPage" => $perPage,
    "data" => $data
];

// JSON 출력 (UTF-8 인코딩)
echo json_encode($response, JSON_UNESCAPED_UNICODE);
?>