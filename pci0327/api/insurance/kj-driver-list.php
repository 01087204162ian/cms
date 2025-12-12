<?php
/**
 * KJ 대리운전 기사 목록 API (JSON, UTF-8)
 * 경로: /pci0327/api/insurance/kj-driver-list.php
 * 호출: GET /api/insurance/kj-driver/list?page=&limit=&name=&jumin=&status=
 * - 필터: 이름(name), 주민번호(jumin), 상태(status: 정상 | 전체)
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
    $page   = isset($_GET['page']) ? max(1, (int)$_GET['page']) : 1;
    $limit  = isset($_GET['limit']) ? max(1, min(100, (int)$_GET['limit'])) : 20;
    $name   = isset($_GET['name']) ? trim($_GET['name']) : '';
    $jumin  = isset($_GET['jumin']) ? trim($_GET['jumin']) : '';
    $status = isset($_GET['status']) ? trim($_GET['status']) : ''; // "정상" | "전체" | ""

    $offset = ($page - 1) * $limit;

    $where  = [];
    $params = [];

    // 이름 검색
    if ($name !== '') {
        $where[] = 'm.`Name` LIKE :name';
        $params[':name'] = '%' . $name . '%';
    }

    // 주민번호 검색 (부분 검색 허용)
    if ($jumin !== '') {
        $where[] = 'm.`Jumin` LIKE :jumin';
        $params[':jumin'] = '%' . $jumin . '%';
    }

    // 상태 필터
    // 기존 코드 기준: "정상만" → push = 4
    if ($status !== '' && $status !== '전체') {
        if ($status === '정상') {
            $where[] = 'm.`push` = :push';
            $params[':push'] = 4;
        }
        // 필요하면 여기서 다른 상태 확장 가능 (청약중, 해지 등)
    }

    $whereSql = $where ? ('WHERE ' . implode(' AND ', $where)) : '';

    // ////////////////////////////////
    // 2. 총 개수 조회
    // ////////////////////////////////
    $countSql = "SELECT COUNT(*) 
                 FROM `2012DaeriMember` AS m
                 {$whereSql}";
    $countStmt = $pdo->prepare($countSql);
    $countStmt->execute($params);
    $total = (int)$countStmt->fetchColumn();

    // ////////////////////////////////
    // 3. 실제 데이터 조회
    //  - 회사명: 2012DaeriCompany 조인
    //  - 기본 증권번호: 2012CertiTable.policyNum 조인
    // ////////////////////////////////
    $dataSql = "
        SELECT 
            m.`num`,
            m.`Name`,
            m.`Jumin`,
            m.`nai` AS age,
            m.`push`,
            m.`etag`,
            m.`InsuranceCompany`,
            m.`CertiTableNum`,
            m.`dongbuCerti`,
            m.`InputDay`,
            m.`OutPutDay`,
            m.`2012DaeriCompanyNum` AS companyNum,
            m.`ch` AS status,
            m.`progress`,
            m.`sago`,
            m.`Hphone`,
            c.`company` AS companyName,
            ct.`policyNum` AS basePolicyNum
        FROM `2012DaeriMember` AS m
        LEFT JOIN `2012DaeriCompany` AS c
            ON m.`2012DaeriCompanyNum` = c.`num`
        LEFT JOIN `2012CertiTable` AS ct
            ON m.`CertiTableNum` = ct.`num`
        {$whereSql}
        ORDER BY m.`num` DESC
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
    // 4. 개인 손해율(할인/할증) 조회 준비
    //    2019rate: policy + jumin → rate 코드
    // ////////////////////////////////
    $rateStmt = $pdo->prepare("
        SELECT `rate` 
        FROM `2019rate` 
        WHERE `policy` = :policy AND `jumin` = :jumin
        LIMIT 1
    ");

    // rate 코드 → 계수/설명 매핑 함수
    $mapRate = function (?string $rateCode): array {
        // default
        $result = [
            'code'   => null,
            'factor' => 1.0,
            'name'   => '기본',
        ];

        if ($rateCode === null) {
            return $result;
        }

        $code = (int)$rateCode;
        $result['code'] = $code;

        switch ($code) {
            case 1:
                $result['factor'] = 1.0;
                $result['name']   = '기본';
                break;
            case 2:
                $result['factor'] = 0.9;
                $result['name']   = '할인';
                break;
            case 3:
                $result['factor'] = 0.925;
                $result['name']   = '3년간 사고 0건, 1년간 사고 0건 (무사고 1년 이상)';
                break;
            case 4:
                $result['factor'] = 0.898;
                $result['name']   = '3년간 사고 0건, 1년간 사고 0건 (무사고 2년 이상)';
                break;
            case 5:
                $result['factor'] = 0.889;
                $result['name']   = '3년간 사고 0건, 1년간 사고 0건 (무사고 3년 이상)';
                break;
            case 6:
                $result['factor'] = 1.074;
                $result['name']   = '3년간 사고 1건, 1년간 사고 0건';
                break;
            case 7:
                $result['factor'] = 1.085;
                $result['name']   = '3년간 사고 1건, 1년간 사고 1건';
                break;
            case 8:
                $result['factor'] = 1.242;
                $result['name']   = '3년간 사고 2건, 1년간 사고 0건';
                break;
            case 9:
                $result['factor'] = 1.253;
                $result['name']   = '3년간 사고 2건, 1년간 사고 1건';
                break;
            case 10:
                $result['factor'] = 1.314;
                $result['name']   = '3년간 사고 2건, 1년간 사고 2건';
                break;
            case 11:
                $result['factor'] = 1.428;
                $result['name']   = '3년간 사고 3건 이상, 1년간 사고 0건';
                break;
            case 12:
                $result['factor'] = 1.435;
                $result['name']   = '3년간 사고 3건 이상, 1년간 사고 1건';
                break;
            case 13:
                $result['factor'] = 1.447;
                $result['name']   = '3년간 사고 3건 이상, 1년간 사고 2건';
                break;
            case 14:
                $result['factor'] = 1.459;
                $result['name']   = '3년간 사고 3건 이상, 1년간 사고 3건 이상';
                break;
            default:
                $result['factor'] = 1.0;
                $result['name']   = '기본';
                break;
        }

        return $result;
    };

    // 보험사 코드 → 이름 매핑
    $mapCompanyName = function (?string $code): ?string {
        if ($code === null || $code === '') {
            return null;
        }
        switch ((int)$code) {
            case 1: return '흥국';
            case 2: return 'DB';
            case 3: return 'KB';
            case 4: return '현대';
            case 5: return '한화';
            case 6: return '더케이';
            case 7: return 'MG';
            case 8: return '삼성';
            case 9: return '메리츠';
            default: return null;
        }
    };

    // ////////////////////////////////
    // 5. 후처리: policyNum, 날짜, 손해율 등
    // ////////////////////////////////
    foreach ($rows as &$row) {
        // 5-1. 최종 policyNum 결정
        $basePolicyNum = $row['basePolicyNum'] ?? null;
        $dongbuCerti   = $row['dongbuCerti'] ?? null;

        if ($dongbuCerti !== null && $dongbuCerti !== '') {
            $policyNum = $dongbuCerti;
        } else {
            $policyNum = $basePolicyNum;
        }

        $row['policyNum'] = $policyNum;

        // 필요 없어진 내부 필드 제거 가능
        unset($row['basePolicyNum'], $row['dongbuCerti']);

        // 5-2. 0000-00-00 처리
        if ($row['OutPutDay'] === '0000-00-00') {
            $row['OutPutDay'] = null;
        }

        // 5-3. 보험사명
        $row['insuranceCompanyName'] = $mapCompanyName($row['InsuranceCompany']);

        // 5-4. 개인 손해율 조회 (2019rate)
        $personRate = [
            'code'   => null,
            'factor' => 1.0,
            'name'   => '기본',
        ];

        if ($policyNum && $row['Jumin']) {
            $rateStmt->execute([
                ':policy' => $policyNum,
                ':jumin'  => $row['Jumin'],
            ]);
            $rateRow = $rateStmt->fetch(PDO::FETCH_ASSOC);
            $rateCode = $rateRow['rate'] ?? null;
            $personRate = $mapRate($rateCode);
        }

        $row['personRateCode']   = $personRate['code'];
        $row['personRateFactor'] = $personRate['factor'];
        $row['personRateName']   = $personRate['name'];

        // 5-5. 대리운전회사명 (이미 companyName 있음)
        //      필요하면 필드 이름 변경/추가 가능.
        
        // 5-6. 핸드폰 번호 보장 (Hphone 필드가 응답에 포함되도록)
        // Hphone은 이미 SELECT에 포함되어 있지만, null 처리
        if (!isset($row['Hphone']) || $row['Hphone'] === null) {
            $row['Hphone'] = '';
        }
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
