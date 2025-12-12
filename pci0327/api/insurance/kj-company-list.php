<?php
/**
 * KJ 대리운전 업체 목록 API (JSON, UTF-8)
 * 경로: /pci0327/api/insurance/kj-company-list.php
 * 호출: GET /api/insurance/kj-company/list?page=&limit=&getDay=&damdanja=&s_contents=
 * - 필터: 날짜(getDay: 01-31), 담당자(damdanja: MemberNum 또는 ''), 검색어(s_contents: 업체명)
 */

header('Content-Type: application/json; charset=utf-8');

// CORS(필요 시 도메인 제한 가능)
if ($_SERVER['REQUEST_METHOD'] === 'OPTIONS') {
    http_response_code(200);
    exit;
}

try {
    require_once __DIR__ . '/../config/db_config.php';
    $pdo = getDbConnection();

    // ////////////////////////////////
    // 1. 입력 파라미터
    // ////////////////////////////////
    $page      = isset($_GET['page']) ? max(1, (int)$_GET['page']) : 1;
    $limit     = isset($_GET['limit']) ? max(1, min(100, (int)$_GET['limit'])) : 20;
    $getDay    = isset($_GET['getDay']) ? trim($_GET['getDay']) : '';
    $damdanja  = isset($_GET['damdanja']) ? trim($_GET['damdanja']) : '';
    $s_contents = isset($_GET['s_contents']) ? trim($_GET['s_contents']) : '';

    $offset = ($page - 1) * $limit;

    $where  = [];
    $params = [];

    // 검색어 필터 (업체명)
    // 구버전: 검색어가 있으면 날짜 필터 무시하고 검색어만으로 필터링
    if ($s_contents !== '') {
        $where[] = 'c.`company` LIKE :s_contents';
        $params[':s_contents'] = '%' . $s_contents . '%';
        
        // 검색어가 있을 때는 담당자 필터만 적용 (날짜 필터는 무시)
        if ($damdanja !== '' && $damdanja !== '9999') {
            $where[] = 'c.`MemberNum` = :damdanja';
            $params[':damdanja'] = (int)$damdanja;
        }
    } else {
        // 검색어가 없을 때만 날짜 필터 적용
        // 날짜 필터 (FirstStartDay: 01-31 또는 '9999'는 전체)
        // 구버전: getDay='9999' 또는 없으면 FirstStartDay>='01' (모든 날짜)
        //         getDay가 있으면 FirstStartDay='$getDay' (특정 날짜)
        if ($getDay === '' || $getDay === '9999') {
            // 전체 날짜: FirstStartDay >= '01' (모든 날짜 포함)
            $where[] = "c.`FirstStartDay` >= '01'";
        } else {
            // 특정 날짜: 2자리 문자열로 변환 (01, 02, ..., 31)
            $dayStr = str_pad($getDay, 2, '0', STR_PAD_LEFT);
            $where[] = 'c.`FirstStartDay` = :getDay';
            $params[':getDay'] = $dayStr;
        }

        // 담당자 필터 (MemberNum)
        if ($damdanja !== '' && $damdanja !== '9999') {
            $where[] = 'c.`MemberNum` = :damdanja';
            $params[':damdanja'] = (int)$damdanja;
        }
    }

    $whereSql = $where ? ('WHERE ' . implode(' AND ', $where)) : '';

    // ////////////////////////////////
    // 2. 총 개수 조회
    // ////////////////////////////////
    $countSql = "SELECT COUNT(*) 
                 FROM `2012DaeriCompany` AS c
                 {$whereSql}";
    $countStmt = $pdo->prepare($countSql);
    $countStmt->execute($params);
    $total = (int)$countStmt->fetchColumn();

    // ////////////////////////////////
    // 3. 실제 데이터 조회
    //  - 담당자명: 2012Member 조인
    // ////////////////////////////////
    $dataSql = "
        SELECT 
            c.`num`,
            c.`company`,
            c.`Pname`,
            c.`jumin`,
            c.`hphone`,
            c.`cphone`,
            c.`MemberNum`,
            c.`FirstStartDay`,
            m.`name` AS managerName
        FROM `2012DaeriCompany` AS c
        LEFT JOIN `2012Member` AS m
            ON c.`MemberNum` = m.`num`
        {$whereSql}
        ORDER BY c.`FirstStartDay` DESC, c.`num` DESC
        LIMIT :limit OFFSET :offset
    ";
    $dataStmt = $pdo->prepare($dataSql);
    foreach ($params as $key => $value) {
        $dataStmt->bindValue($key, $value);
    }
    $dataStmt->bindValue(':limit', $limit, PDO::PARAM_INT);
    $dataStmt->bindValue(':offset', $offset, PDO::PARAM_INT);
    $dataStmt->execute();

    $rows = $dataStmt->fetchAll(PDO::FETCH_ASSOC);

    // ////////////////////////////////
    // 4. 각 업체별 인원 수 계산
    //    (2012DaeriMember에서 push='4'인 기사 수)
    // ////////////////////////////////
    $memberCountStmt = $pdo->prepare("
        SELECT COUNT(*) 
        FROM `2012DaeriMember` 
        WHERE `2012DaeriCompanyNum` = :companyNum 
          AND `push` = 4
    ");

    foreach ($rows as &$row) {
        $companyNum = $row['num'];
        
        // 인원 수 조회
        $memberCountStmt->execute([':companyNum' => $companyNum]);
        $memberCount = (int)$memberCountStmt->fetchColumn();
        $row['memberCount'] = $memberCount;
        $row['mRnum'] = $memberCount; // 구버전 호환

        // 날짜 필드 정리
        $row['date'] = $row['FirstStartDay'];
        
        // 연락처 우선순위: hphone > cphone
        $row['phone'] = $row['hphone'] ?: $row['cphone'];
        
        // 담당자명 (구버전 호환)
        $row['damdanga'] = $row['managerName'];
    }
    unset($row);

    $totalPages = (int)ceil($total / $limit);

    echo json_encode([
        'success' => true,
        'data' => $rows,
        'pagination' => [
            'page'       => $page,
            'limit'      => $limit,
            'total'      => $total,
            'totalPages' => $totalPages,
        ],
    ], JSON_UNESCAPED_UNICODE);
    exit;
} catch (Throwable $e) {
    http_response_code(500);
    echo json_encode([
        'success' => false,
        'error' => $e->getMessage(),
    ], JSON_UNESCAPED_UNICODE);
    exit;
}

