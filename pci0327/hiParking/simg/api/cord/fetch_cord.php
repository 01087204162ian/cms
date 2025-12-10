<?php
header('Content-Type: application/json; charset=utf-8');


// OPTIONS 요청 처리 (CORS preflight)
if ($_SERVER['REQUEST_METHOD'] == 'OPTIONS') {
    exit(0);
}

// 보험회사 코드 매핑
function getInsuranceCompanyName($code) {
    $companies = [
        1 => '흥국',
        2 => 'DB',
        3 => 'KB',
        4 => '현대',
        5 => '한화',
        6 => '메리츠',
        7 => 'MG',
        8 => '삼성',
        9 => '하나',
		10 => '신한',
        21 => 'chubb',
        22 => '기타'
    ];
    
    return isset($companies[$code]) ? $companies[$code] : '알 수 없음';
}

// 보험회사명으로 코드 찾기
function getInsuranceCompanyCode($name) {
    $companies = [
        '흥국' => 1,
        'DB' => 2,
        'KB' => 3,
        '현대' => 4,
        '한화' => 5,
        '메리츠' => 6,
        'MG' => 7,
        '삼성' => 8,
        '하나' => 9,
		'신한' => 10,
        'chubb' => 21,
        '기타' => 22
    ];
    
    return isset($companies[$name]) ? $companies[$name] : null;
}

try {
    // 데이터베이스 연결
    include '../db.php';
    
    // GET 파라미터 받기
    $page = isset($_GET['page']) ? (int)$_GET['page'] : 1;
    $limit = isset($_GET['limit']) ? (int)$_GET['limit'] : 15;
    $searchSchool = isset($_GET['search_school']) ? trim($_GET['search_school']) : '';
    $searchMode = isset($_GET['search_mode']) ? trim($_GET['search_mode']) : '';
    
    // 페이지 번호 유효성 검사
    if ($page < 1) $page = 1;
    if ($limit < 1) $limit = 15;
    if ($limit > 100) $limit = 100; // 최대 제한
    
    // OFFSET 계산
    $offset = ($page - 1) * $limit;
    
    // 기본 쿼리
    $whereConditions = [];
    $params = [];
    
    // 검색 조건 추가
    if (!empty($searchSchool)) {
        switch ($searchMode) {
            case '1': // 보험회사
                // 검색어가 보험회사명인 경우 코드로 변환
                $companyCode = getInsuranceCompanyCode($searchSchool);
                if ($companyCode !== null) {
                    $whereConditions[] = "insuCompany = :search";
                    $params[':search'] = $companyCode;
                } else {
                    // 숫자로 직접 검색하는 경우
                    if (is_numeric($searchSchool)) {
                        $whereConditions[] = "insuCompany = :search";
                        $params[':search'] = (int)$searchSchool;
                    }
                }
                break;
            case '2': // 대리점명
                $whereConditions[] = "agent LIKE :search";
                $params[':search'] = '%' . $searchSchool . '%';
                break;
            case '3': // 특이사항
                $whereConditions[] = "gita LIKE :search";
                $params[':search'] = '%' . $searchSchool . '%';
                break;
            case 'name':
                $whereConditions[] = "name LIKE :search";
                $params[':search'] = '%' . $searchSchool . '%';
                break;
            case 'cord':
                $whereConditions[] = "cord LIKE :search";
                $params[':search'] = '%' . $searchSchool . '%';
                break;
            case 'agent':
                $whereConditions[] = "agent LIKE :search";
                $params[':search'] = '%' . $searchSchool . '%';
                break;
            case 'phone':
                $whereConditions[] = "phone LIKE :search";
                $params[':search'] = '%' . $searchSchool . '%';
                break;
            case 'jijem':
                $whereConditions[] = "jijem LIKE :search";
                $params[':search'] = '%' . $searchSchool . '%';
                break;
            case 'insuCompany':
                $whereConditions[] = "insuCompany = :search";
                $params[':search'] = $searchSchool;
                break;
            case 'gita':
                $whereConditions[] = "gita LIKE :search";
                $params[':search'] = '%' . $searchSchool . '%';
                break;
            default:
                // 전체 검색 (이름, 코드, 대리점에서 검색)
                $whereConditions[] = "(name LIKE :search OR cord LIKE :search OR agent LIKE :search)";
                $params[':search'] = '%' . $searchSchool . '%';
                break;
        }
    }
    
    // WHERE 절 구성
    $whereClause = '';
    if (!empty($whereConditions)) {
        $whereClause = 'WHERE ' . implode(' AND ', $whereConditions);
    }
    
    // 전체 레코드 수 조회
    $countSql = "SELECT COUNT(*) as total FROM `2015cord` $whereClause";
    $countStmt = $pdo->prepare($countSql);
    $countStmt->execute($params);
    $totalRecords = $countStmt->fetch(PDO::FETCH_ASSOC)['total'];
    
    // 데이터 조회
    $sql = "SELECT 
                num,
                insuCompany,
                agent,
                name,
                cord,
                cord2,
                verify,
                password,
                jijem,
                jijemLady,
                phone,
                fax,
                wdate,
                gita
            FROM `2015cord` 
            $whereClause 
            ORDER BY agent DESC 
            LIMIT :limit OFFSET :offset";
    
    $stmt = $pdo->prepare($sql);
    
    // 파라미터 바인딩
    foreach ($params as $key => $value) {
        $stmt->bindValue($key, $value, PDO::PARAM_STR);
    }
    $stmt->bindValue(':limit', $limit, PDO::PARAM_INT);
    $stmt->bindValue(':offset', $offset, PDO::PARAM_INT);
    
    $stmt->execute();
    $records = $stmt->fetchAll(PDO::FETCH_ASSOC);
    
    // 페이지네이션 정보 계산
    $totalPages = ceil($totalRecords / $limit);
    $hasNextPage = $page < $totalPages;
    $hasPrevPage = $page > 1;
    
    // 데이터 포맷팅 (보험회사 코드를 한글로 변환)
    $formattedRecords = array_map(function($record) {
        return [
            'num' => (int)$record['num'],
            'insuCompany' => (int)$record['insuCompany'],
            'insuCompanyName' => getInsuranceCompanyName((int)$record['insuCompany']),
            'agent' => $record['agent'] ?: '',
            'name' => $record['name'] ?: '',
            'cord' => $record['cord'],
            'cord2' => $record['cord2'],
            'verify' => $record['verify'],
            'password' => $record['password'],
            'jijem' => $record['jijem'] ?: '',
            'jijemLady' => $record['jijemLady'],
            'phone' => $record['phone'],
            'fax' => $record['fax'] ?: '',
            'wdate' => $record['wdate'],
            'gita' => $record['gita'] ?: ''
        ];
    }, $records);
    
    // 응답 데이터 구성
    $response = [
        'success' => true,
        'data' => $formattedRecords,
        'pagination' => [
            'currentPage' => $page,
            'totalPages' => $totalPages,
            'totalRecords' => (int)$totalRecords,
            'recordsPerPage' => $limit,
            'hasNextPage' => $hasNextPage,
            'hasPrevPage' => $hasPrevPage,
            'startRecord' => $offset + 1,
            'endRecord' => min($offset + $limit, $totalRecords)
        ],
        'search' => [
            'keyword' => $searchSchool,
            'mode' => $searchMode
        ]
    ];
    
    echo json_encode($response, JSON_UNESCAPED_UNICODE | JSON_PRETTY_PRINT);
    
} catch (PDOException $e) {
    // 데이터베이스 오류
    http_response_code(500);
    echo json_encode([
        'success' => false,
        'error' => '데이터베이스 오류가 발생했습니다.',
        'message' => $e->getMessage()
    ], JSON_UNESCAPED_UNICODE);
    
} catch (Exception $e) {
    // 기타 오류
    http_response_code(500);
    echo json_encode([
        'success' => false,
        'error' => '서버 오류가 발생했습니다.',
        'message' => $e->getMessage()
    ], JSON_UNESCAPED_UNICODE);
}
?>