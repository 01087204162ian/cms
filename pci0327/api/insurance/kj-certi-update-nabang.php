<?php
/**
 * KJ 대리운전 증권 회차 변경 API
 * 경로: /pci0327/api/insurance/kj-certi-update-nabang.php
 * 호출: GET /api/insurance/kj-certi/update-nabang?nabsunso={회차}&certiTableNum={증권번호}&sunso={순서}
 * - 납입 회차를 업데이트하고 납입 상태를 계산하여 반환
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

    // 입력 파라미터
    $nabsunso = isset($_GET['nabsunso']) ? intval($_GET['nabsunso']) : 0;
    $certiTableNum = isset($_GET['certiTableNum']) ? intval($_GET['certiTableNum']) : 0;
    $sunso = isset($_GET['sunso']) ? intval($_GET['sunso']) : 0;

    if ($nabsunso < 1 || $nabsunso > 10) {
        throw new Exception('회차는 1~10 사이의 값이어야 합니다.');
    }

    if (empty($certiTableNum)) {
        throw new Exception('증권 번호가 필요합니다.');
    }

    // 1. 해당 증권의 회차 업데이트
    $updateSql = "UPDATE 2012CertiTable SET nabang_1 = :nabsunso WHERE num = :certiTableNum";
    $updateStmt = $pdo->prepare($updateSql);
    $updateStmt->execute([
        ':nabsunso' => $nabsunso,
        ':certiTableNum' => $certiTableNum
    ]);

    // 2. 증권 정보 조회
    $selectSql = "SELECT * FROM 2012CertiTable WHERE num = :certiTableNum";
    $selectStmt = $pdo->prepare($selectSql);
    $selectStmt->execute([':certiTableNum' => $certiTableNum]);
    $nRow = $selectStmt->fetch(PDO::FETCH_ASSOC);

    if (!$nRow) {
        throw new Exception('증권 정보를 찾을 수 없습니다.');
    }

    $sigiStart = $nRow['startyDay'];
    $naBang = $nabsunso;
    $inPnum = $nRow['InsuraneCompany'];
    $policyNum = $nRow['policyNum'];

    // 3. 납입 상태 계산 (nabState.php 로직)
    $now_time = date('Y-m-d');
    $naState = '';
    $naColor = 1;
    $gigan = '';

    if ($naBang != 10) {
        for ($i = 1; $i < 10; $i++) {
            $j = $i + 1;
            $nex[$j] = date("Y-m-d", strtotime("$sigiStart + $i month"));

            if ($inPnum == 1 || $inPnum == 3 || $inPnum == 7) { // 흥국, KB, MG
                $silhy[$j] = date("Y-m-d", strtotime("$nex[$j] + 30 day"));
            } else {
                $du[$j] = date("Y-m-d", strtotime("$nex[$j] +1 month")); // 응당일 다음달
                $dd = date("t", strtotime("$nex[$j] +1 month")); // 말일
                $dd2 = date("Y-m", strtotime("$nex[$j] +1 month"));
                $silhy[$j] = $dd2 . "-" . $dd;
            }

            $daum = $naBang + 1;

            if ($j == $daum) {
                if (strtotime($now_time) <= strtotime($nex[$j])) {
                    $naState = '납';
                    $gigan = floor((strtotime($nex[$j]) - strtotime($now_time)) / (60 * 60 * 24));
                    $naColor = 1;
                } else if (strtotime($now_time) > strtotime($nex[$j]) && strtotime($now_time) <= strtotime($silhy[$j])) {
                    $naState = '유';
                    $naColor = 2;
                    $gigan = floor((strtotime($silhy[$j]) - strtotime($now_time)) / (60 * 60 * 24));
                } else {
                    $naState = '실';
                    $naColor = 3;
                    $gigan = '';
                }
            }
        }
    } else {
        $naState = '완';
        $naColor = 1;
        $gigan = '';
    }

    // 4. 동일한 증권번호와 보험사를 가진 모든 증권의 회차도 업데이트
    $updateAllSql = "UPDATE 2012CertiTable SET nabang_1 = :nabsunso 
                     WHERE policyNum = :policyNum AND InsuraneCompany = :inPnum";
    $updateAllStmt = $pdo->prepare($updateAllSql);
    $updateAllStmt->execute([
        ':nabsunso' => $nabsunso,
        ':policyNum' => $policyNum,
        ':inPnum' => $inPnum
    ]);

    $message = $nabsunso . "회차를 납입하였습니다";
    $naStateText = $naState . ($gigan ? "(" . $gigan . "일)" : '');

    // JSON 응답
    $response = [
        'success' => true,
        'naColor' => (int)$naColor,
        'naState' => $naStateText,
        'val' => (int)$sunso,
        'message' => $message
    ];

    echo json_encode($response, JSON_UNESCAPED_UNICODE);

} catch (Exception $e) {
    http_response_code(400);
    $response = [
        'success' => false,
        'error' => $e->getMessage()
    ];
    echo json_encode($response, JSON_UNESCAPED_UNICODE);
}

