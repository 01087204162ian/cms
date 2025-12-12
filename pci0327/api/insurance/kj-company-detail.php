<?php
/**
 * KJ 대리운전 업체 상세 정보 API (JSON, UTF-8)
 * 경로: /pci0327/api/insurance/kj-company-detail.php
 * 호출: GET /api/insurance/kj-company/{companyNum}
 * - 업체 기본 정보, 담당자 정보, 증권 정보 조회
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
    $dNum = isset($_GET['dNum']) ? trim($_GET['dNum']) : '';

    if (empty($dNum)) {
        throw new Exception('업체 번호가 필요합니다.');
    }

    $main_info = [];
    $data = [];

    // ////////////////////////////////
    // 2. 업체 기본 정보 조회
    // ////////////////////////////////
    $companySql = "SELECT * FROM `2012DaeriCompany` WHERE `num` = :dNum";
    $companyStmt = $pdo->prepare($companySql);
    $companyStmt->execute([':dNum' => $dNum]);
    $company_info = $companyStmt->fetch(PDO::FETCH_ASSOC);

    if (!$company_info) {
        throw new Exception('업체 정보를 찾을 수 없습니다.');
    }

    // 업체 정보를 main_info에 복사
    foreach ($company_info as $key => $value) {
        $main_info[$key] = $value;
    }

    // ////////////////////////////////
    // 3. 담당자 정보 조회 (2012Member)
    // ////////////////////////////////
    if (!empty($company_info['MemberNum'])) {
        $memberSql = "SELECT `name` FROM `2012Member` WHERE `num` = :memberNum";
        $memberStmt = $pdo->prepare($memberSql);
        $memberStmt->execute([':memberNum' => $company_info['MemberNum']]);
        $member_info = $memberStmt->fetch(PDO::FETCH_ASSOC);
        
        if ($member_info) {
            $main_info['name'] = $member_info['name'] ?? '';
        }
    }

    // ////////////////////////////////
    // 4. 고객 정보 조회 (2012Costomer)
    // ////////////////////////////////
    $customerSql = "SELECT `mem_id`, `permit` FROM `2012Costomer` WHERE `2012DaeriCompanyNum` = :dNum";
    $customerStmt = $pdo->prepare($customerSql);
    $customerStmt->execute([':dNum' => $dNum]);
    $customer_info = $customerStmt->fetch(PDO::FETCH_ASSOC);
    
    if ($customer_info) {
        $main_info['mem_id'] = $customer_info['mem_id'] ?? '';
        $main_info['permit'] = $customer_info['permit'] ?? '';
    }

    // ////////////////////////////////
    // 5. 증권 정보 조회 (최근 1년)
    //    - 2012CertiTable에서 조회
    //    - 각 증권별 인원 수 계산 (2012DaeriMember에서 push='4')
    //    - 납입 상태 계산 (nabState 로직)
    // ////////////////////////////////
    $yearbefore = date('Y-m-d', strtotime('-1 year'));
    $certiSql = "
        SELECT * 
        FROM `2012CertiTable` 
        WHERE `2012DaeriCompanyNum` = :dNum 
          AND `startyDay` >= :yearbefore
        ORDER BY `InsuraneCompany` DESC, `startyDay` DESC
    ";
    $certiStmt = $pdo->prepare($certiSql);
    $certiStmt->execute([':dNum' => $dNum, ':yearbefore' => $yearbefore]);
    $certiRows = $certiStmt->fetchAll(PDO::FETCH_ASSOC);

    // 각 증권별 인원 수 계산 및 납입 상태 계산
    $inwonStmt = $pdo->prepare("
        SELECT COUNT(*) as cnt 
        FROM `2012DaeriMember` 
        WHERE `CertiTableNum` = :certiTableNum 
          AND `push` = '4'
    ");

    $inWonTotal = 0;
    $now_time = date('Y-m-d');

    foreach ($certiRows as $certiRow) {
        $certiTableNum = $certiRow['num'];
        
        // 인원 수 계산
        $inwonStmt->execute([':certiTableNum' => $certiTableNum]);
        $inwonRow = $inwonStmt->fetch(PDO::FETCH_ASSOC);
        $inwon = (int)($inwonRow['cnt'] ?? 0);
        $certiRow['inwon'] = $inwon;
        $inWonTotal += $inwon;
        
        // 받는 날짜 계산 (FirstStartDay 또는 fstartyDay)
        $fstart = explode('-', $company_info['FirstStart'] ?? '');
        $f_date = $certiRow['fstartyDay'] ?? ($fstart[2] ?? '');
        $certiRow['f_date'] = $f_date;
        
        // 납입 상태 계산 (nabState 로직)
        $naBang = (int)($certiRow['nabang_1'] ?? 1);
        $sigiStart = $certiRow['startyDay'];
        $inPnum = (int)($certiRow['InsuraneCompany'] ?? 0);
        
        $naState = '';
        $naColor = 1;
        $gigan = '';
        
        if ($naBang != 10) {
            $daum = $naBang + 1;
            for ($i = 1; $i < 10; $i++) {
                $j = $i + 1;
                $nex = date('Y-m-d', strtotime("$sigiStart + $i month"));
                
                if ($inPnum == 1 || $inPnum == 3 || $inPnum == 7) {
                    $silhy = date('Y-m-d', strtotime("$nex + 30 day"));
                } else {
                    $dd = date('t', strtotime("$nex +1 month"));
                    $dd2 = date('Y-m', strtotime("$nex +1 month"));
                    $silhy = "$dd2-$dd";
                }
                
                if ($j == $daum) {
                    if (strtotime($now_time) <= strtotime($nex)) {
                        $naState = '납';
                        $gigan = (strtotime($nex) - strtotime($now_time)) / (60 * 60 * 24);
                        $naColor = 1;
                    } else if (strtotime($now_time) > strtotime($nex) && strtotime($now_time) <= strtotime($silhy)) {
                        $naState = '유';
                        $gigan = (strtotime($silhy) - strtotime($now_time)) / (60 * 60 * 24);
                        $naColor = 2;
                    } else {
                        $naState = '실';
                        $naColor = 3;
                        $gigan = '';
                    }
                    break;
                }
            }
        } else {
            $naState = '완';
            $naColor = 1;
            $gigan = '';
        }
        
        $certiRow['naState'] = $naState;
        $certiRow['naColor'] = $naColor;
        $certiRow['gigan'] = $gigan;
        
        // 결제 방식 (divi)
        $divi = (int)($certiRow['divi'] ?? 1);
        $diviName = ($divi == 1) ? '정상' : (($divi == 2) ? '1/12씩' : '정상');
        $certiRow['diviName'] = $diviName;
        
        // 성격 (gita)
        $gita = (int)($certiRow['gita'] ?? 1);
        $gitaNames = [1 => '일반', 2 => '탁송', 3 => '일반/렌트', 4 => '탁송/렌트', 5 => '전차량'];
        $certiRow['gitaName'] = $gitaNames[$gita] ?? '일반';
        
        $data[] = $certiRow;
    }
    
    $main_info['inWonTotal'] = $inWonTotal;

    // ////////////////////////////////
    // 6. 메모 목록 조회 (ssang_c_memo)
    // ////////////////////////////////
    $memoData = [];
    $c_number = $company_info['cNumber'] ?? $company_info['jumin'] ?? '';
    
    if ($c_number) {
        $memoSql = "SELECT * FROM `ssang_c_memo` WHERE `c_number` = :c_number ORDER BY `num`";
        $memoStmt = $pdo->prepare($memoSql);
        $memoStmt->execute([':c_number' => $c_number]);
        $memoRows = $memoStmt->fetchAll(PDO::FETCH_ASSOC);
        
        foreach ($memoRows as $memoRow) {
            $memokind = (int)($memoRow['memokind'] ?? 1);
            $memokindNames = [1 => '일반', 2 => '결재', 3 => '가상', 4 => '환급'];
            $memoRow['memokindName'] = $memokindNames[$memokind] ?? '일반';
            $memoData[] = $memoRow;
        }
    }
    
    // ////////////////////////////////
    // 7. SMS 목록 조회 (SMSData)
    // ////////////////////////////////
    $smsData = [];
    $hphone = $company_info['hphone'] ?? '';
    
    if ($hphone) {
        list($hphone1, $hphone2, $hphone3) = explode('-', $hphone);
        
        if ($hphone1 && $hphone2 && $hphone3) {
            $smsSql = "
                SELECT * FROM `SMSData` 
                WHERE `Rphone1` = :hphone1 
                  AND `Rphone2` = :hphone2 
                  AND `Rphone3` = :hphone3 
                  AND `dagun` = '1'
                ORDER BY `SeqNo` DESC 
                LIMIT 10
            ";
            $smsStmt = $pdo->prepare($smsSql);
            $smsStmt->execute([
                ':hphone1' => $hphone1,
                ':hphone2' => $hphone2,
                ':hphone3' => $hphone3
            ]);
            $smsRows = $smsStmt->fetchAll(PDO::FETCH_ASSOC);
            
            foreach ($smsRows as $smsRow) {
                $LastTime = $smsRow['LastTime'] ?? '';
                $ysear = substr($LastTime, 0, 4);
                $month = substr($LastTime, 4, 2);
                $day = substr($LastTime, 6, 2);
                $_time = substr($LastTime, 8, 2);
                $_minute = substr($LastTime, 10, 2);
                $dates = "$ysear-$month-$day $_time:$_minute";
                
                $com = (int)($smsRow['company'] ?? 0);
                $comNames = [1 => '흥국', 2 => 'DB', 3 => 'KB', 4 => '현대', 5 => '한화', 6 => '더케이', 10 => '보험료'];
                $comName = $comNames[$com] ?? '';
                
                $smsRow['dates'] = $dates;
                $smsRow['comName'] = $comName;
                $smsRow['get'] = (int)($smsRow['get'] ?? 0);
                
                $smsData[] = $smsRow;
            }
        }
    }
    
    // ////////////////////////////////
    // 8. 증권별 메모 내용 (content)
    // ////////////////////////////////
    $contentData = [];
    foreach ($certiRows as $certiRow) {
        if (!empty($certiRow['content'])) {
            $contentData[] = $certiRow['content'];
        }
    }
    
    // ////////////////////////////////
    // 9. 응답 데이터 구성
    // ////////////////////////////////
    $response = ['success' => true];
    
    // 최상위 데이터를 추가
    $response = array_merge($response, $main_info);
    
    // 배열 데이터를 추가
    $response['data'] = $data;
    $response['memoData'] = $memoData;
    $response['smsData'] = $smsData;
    $response['content'] = $contentData;
    $response['Rnum'] = count($data);
    $response['sNum'] = min(count($memoData), 10);
    $response['gaesu'] = min(count($smsData), 10);

    echo json_encode($response, JSON_UNESCAPED_UNICODE);
    exit;
} catch (Throwable $e) {
    http_response_code(500);
    echo json_encode([
        'success' => false,
        'error' => $e->getMessage(),
    ], JSON_UNESCAPED_UNICODE);
    exit;
}

