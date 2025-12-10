<?php

// 응답 헤더 설정
header('Content-Type: application/json; charset=UTF-8');
include "../config/cors.php";

// 에러 리포팅 설정
error_reporting(E_ALL);
ini_set('display_errors', 1);

// 데이터베이스 연결 정보
include "../config/_db.php";
if (!$conn) {
    $response = array(
        'success' => false,
        'error' => 'Database connection failed: ' . mysqli_connect_error()
    );
    echo json_encode($response);
    exit;
}

// 임시접근 권한 확인
require '../config/Util.php';
Util::checkHeader();

// 예시: POST로 accidents 배열을 받는다고 가정
$data = json_decode(file_get_contents('php://input'), true);
$accidents = isset($data['accidents']) ? $data['accidents'] : [];

if (empty($accidents) || !is_array($accidents)) {
    $response = array(
        'success' => false,
        'error' => 'No accident data provided'
    );
    echo json_encode($response);
    exit;
}

// 입력전 이전데이터 삭제
// $sql = "DELETE FROM inSync_accidents_data";
// if (!mysqli_query($conn, $sql)) {
//     $response = array(
//         'success' => false,
//         'error' => 'Failed to clear previous data: ' . mysqli_error($conn)
//     );
//     echo json_encode($response);
//     exit;
// }

// 다건 INSERT 쿼리 
$values = [];
foreach ($accidents as $accident) {
    // 예시 컬럼: accident_date, location, description
    $application_number = mysqli_real_escape_string($conn, $accident['application_number']);
    $accident_day = mysqli_real_escape_string($conn, $accident['accident_day']);
    $accident_time = mysqli_real_escape_string($conn, $accident['accident_time']);
    $contract_number = mysqli_real_escape_string($conn, $accident['contract_number']);
    $insured_person = mysqli_real_escape_string($conn, $accident['insured_person']);
    $insured_car = mysqli_real_escape_string($conn, $accident['insured_car']);
    $driver = mysqli_real_escape_string($conn, $accident['driver']);
    $contract_start_day = mysqli_real_escape_string($conn, $accident['contract_start_day']);
    $contract_end_day = mysqli_real_escape_string($conn, $accident['contract_end_day']);
    $is_end = mysqli_real_escape_string($conn, $accident['is_end']);
    $total_premium = mysqli_real_escape_string($conn, $accident['total_premium']);
    $contract_manager = mysqli_real_escape_string($conn, $accident['contract_manager']);
    $contract_store = mysqli_real_escape_string($conn, $accident['contract_store']);
    $accident_spot = mysqli_real_escape_string($conn, $accident['accident_spot']);
    $accident_txt = mysqli_real_escape_string($conn, $accident['accident_txt']);
    $dividend_date = mysqli_real_escape_string($conn, $accident['dividend_date']);
    $dividend_time = mysqli_real_escape_string($conn, $accident['dividend_time']);
    $driver_birthday = mysqli_real_escape_string($conn, $accident['driver_birthday']);
    $driver_phone = mysqli_real_escape_string($conn, $accident['driver_phone']);
    $accident_type = mysqli_real_escape_string($conn, $accident['accident_type']);
    $accident_detail = mysqli_real_escape_string($conn, $accident['accident_detail']);
    $dambo = mysqli_real_escape_string($conn, $accident['dambo']);
    $gwasil_yul = mysqli_real_escape_string($conn, $accident['gwasil_yul']);
    $jungdaegwasil = mysqli_real_escape_string($conn, $accident['jungdaegwasil']);
    $values[] = "(
                '$application_number', 
                '$accident_day', 
                '$accident_time',
                '$contract_number',
                '$insured_person',
                '$insured_car',
                '$driver',
                '$contract_start_day',
                '$contract_end_day',
                '$is_end',
                 $total_premium,
                '$contract_manager',
                '$contract_store',
                '$accident_spot',
                '$accident_txt',
                '$dividend_date',
                '$dividend_time',
                '$driver_birthday',
                '$driver_phone',
                '$accident_type',
                '$accident_detail',
                '$dambo',
                 $gwasil_yul,
                '$jungdaegwasil'
                )";
}

$sql = "INSERT INTO inSync_accidents_data (
                    application_number, 
                    accident_day, 
                    accident_time,
                    contract_number,
                    insured_person,
                    insured_car,
                    driver,
                    contract_start_day,
                    contract_end_day,
                    is_end,
                    total_premium,
                    contract_manager,
                    contract_store,
                    accident_spot,
                    accident_txt,
                    dividend_date,
                    dividend_time,
                    driver_birthday,
                    driver_phone,
                    accident_type,
                    accident_detail,
                    dambo,
                    gwasil_yul,
                    jungdaegwasil
                    ) VALUES " . implode(',', $values);

if (mysqli_query($conn, $sql)) {
    $response = array(
        'success' => true,
        'message' => count($accidents) . ' InSync accident(s) inserted successfully'
    );
} else {
    $response = array(
        'success' => false,
        'error' => 'Insert failed: ' . mysqli_error($conn)
    );
}

echo json_encode($response);
mysqli_close($conn);
?>