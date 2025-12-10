<?php
declare(strict_types=1);

header("Content-Type: application/json; charset=utf-8");

require_once '../../../api/config/db_config.php';
include "./php/monthlyFee.php"; // 월보험료 산출 함수
include "../kjDaeri/php/encryption.php"; // 암호화/복호화 함수 포함

// PDO 연결 가져오기
$conn = getDbConnection();

// 에러 보고 설정
error_reporting(E_ALL & ~E_NOTICE);

// POST 데이터 검증
if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    $response = [
        "success" => false,
        "message" => "잘못된 요청 방식입니다."
    ];
    echo json_encode($response);
    exit();
}

// POST 데이터 수집
$receivedData = [
    "cNum" => $_POST['cNum'] ?? null,
    "dNum" => $_POST['dNum'] ?? null,
    "gita" => $_POST['gita'] ?? null,
    "InsuranceCompany" => $_POST['InsuraneCompany'] ?? null,
    "endorseDay" => $_POST['endorseDay'] ?? null,
    "policyNum" => $_POST['policyNum'] ?? null,
    "userName" => $_POST['userName'] ?? null
];

// 파라미터 변수로 설정
$cNum = $receivedData["cNum"];
$dNum = $receivedData["dNum"];
$gita = $receivedData["gita"];
$InsuranceCompany = $receivedData["InsuranceCompany"];
$endorseDay = $receivedData["endorseDay"];
$policyNum = $receivedData["policyNum"];
$userName = $receivedData["userName"];

try {
    // 첫 번째 쿼리: 2012CertiTable에서 정보 가져오기
    $certiTableStmt = $conn->prepare("SELECT divi FROM 2012CertiTable WHERE num = :cNum");
    $certiTableStmt->bindParam(':cNum', $cNum, PDO::PARAM_INT);
    $certiTableStmt->execute();
    $Crow2 = $certiTableStmt->fetch(PDO::FETCH_ASSOC);

    // divi 값 가져오기 (1정상납, 2월납)
    $divi = $Crow2['divi'] ?? null;

    // 두 번째 쿼리: 2012Certi에서 정보 가져오기
    $certiStmt = $conn->prepare("SELECT sigi, nab FROM 2012Certi WHERE certi = :policyNum");
    $certiStmt->bindParam(':policyNum', $policyNum, PDO::PARAM_STR);
    $certiStmt->execute();
    $Crow = $certiStmt->fetch(PDO::FETCH_ASSOC);

    // 결과 저장
    $startDay = $Crow['sigi'] ?? null;
    $nabang = 10;
    $nabang_1 = $Crow['nab'] ?? null;

    // 보험회사별 기준일 결정
    $dateString = match((int)$InsuranceCompany) {
        1, 2 => $endorseDay,
        3, 4, 5, 7 => $Crow['sigi'] ?? $endorseDay,
        default => $endorseDay
    };

    // 날짜 형식 검증
    if (empty($dateString) || !strtotime($dateString)) {
        $dateString = date('Y-m-d');
    }

    // 보험 가입자 데이터 추출 및 나이 계산
    $endorseData = [];
    for ($i = 0; $i < 6; $i++) {
        if (isset($_POST['data'][$i]['name']) && !empty($_POST['data'][$i]['name'])) {
            // 주민번호 분리 (하이픈 제거)
            $juminNo = str_replace('-', '', $_POST['data'][$i]['juminNo']);
            $jumin1 = substr($juminNo, 0, 6);
            $jumin2 = substr($juminNo, 6, 7);
            
            // 만나이 및 성별 계산
            $personInfo = calculateAge($jumin1, $jumin2, $dateString);
            
            $rowData = [
                'rowNum' => $_POST['data'][$i]['rowNum'],
                'name' => $_POST['data'][$i]['name'],
                'juminNo' => $_POST['data'][$i]['juminNo'],
                'phoneNo' => $_POST['data'][$i]['phoneNo'],
                'age' => $personInfo['age'],
                'sex' => $personInfo['sex'],
                'birthday' => $personInfo['birthday'],
                'to_day' => $personInfo['to_day']
            ];
            
            $endorseData[] = $rowData;
        }
    }

    // 각 가입자별로 INSERT 처리
    $insertStmt = $conn->prepare("
        INSERT INTO DaeriMember (
            2012DaeriCompanyNum, InsuranceCompany, CertiTableNum, 
            Name, Jumin, nai, push, etag, Hphone, 
            InPutDay, wdate, dongbuCerti, JuminHash
        ) VALUES (
            :dNum, :InsuranceCompany, :cNum,
            :name, :jumin, :age, 4, :gita, :phone,
            NOW(), NOW(), :policyNum, :juminHash
        )
    ");

    $successCount = 0;
    $errorMessages = [];

    foreach ($endorseData as $member) {
        try {
            // 데이터 준비
            $name = $member['name'];
            $age = $member['age'];
            
            // 주민번호와 휴대전화번호 암호화
            $encryptedJumin = encryptData($member['juminNo']);
            $encryptedPhone = encryptData($member['phoneNo']);
            
            // 주민번호 해시 생성
            $plainJumin = decryptData($encryptedJumin);
            $juminHash = '';
            
            if (!empty($plainJumin)) {
                $juminHash = sha1($plainJumin);
            } else {
                throw new Exception("주민번호 복호화 실패: {$member['name']}");
            }

            // 파라미터 바인딩 및 실행
            $insertStmt->bindParam(':dNum', $dNum);
            $insertStmt->bindParam(':InsuranceCompany', $InsuranceCompany);
            $insertStmt->bindParam(':cNum', $cNum);
            $insertStmt->bindParam(':name', $name);
            $insertStmt->bindParam(':jumin', $encryptedJumin);
            $insertStmt->bindParam(':age', $age);
            $insertStmt->bindParam(':gita', $gita);
            $insertStmt->bindParam(':phone', $encryptedPhone);
            $insertStmt->bindParam(':policyNum', $policyNum);
            $insertStmt->bindParam(':juminHash', $juminHash);
            
            if ($insertStmt->execute()) {
                $successCount++;
            }
        } catch (Exception $e) {
            $errorMessages[] = $e->getMessage();
        }
    }

    // 응답 생성
    $response = [
        "success" => $successCount > 0,
        "message" => $successCount > 0 
            ? "데이터가 성공적으로 저장되었습니다" 
            : "데이터 저장에 실패했습니다",
        "data" => [
            "mainData" => $receivedData,
            "endorseData" => $endorseData,
            "baseDate" => $dateString,
            "successCount" => $successCount,
            "errorMessages" => $errorMessages
        ]
    ];

} catch (Exception $e) {
    $response = [
        "success" => false,
        "message" => "오류가 발생했습니다: " . $e->getMessage()
    ];
}

// 응답 반환
echo json_encode($response, JSON_UNESCAPED_UNICODE);
exit();