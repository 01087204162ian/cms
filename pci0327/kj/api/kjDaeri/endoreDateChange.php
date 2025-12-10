<?php
header("Content-Type: application/json; charset=utf-8");
require_once '../../../api/config/db_config.php';

// PDO 연결 가져오기 (db_config.php에서 정의된 함수 사용)
$conn = getDbConnection();

// POST 파라미터 가져오기
$endorse_day = isset($_POST['endorse_day']) ? $_POST['endorse_day'] : ''; 
$after_day = isset($_POST['after_day']) ? $_POST['after_day'] : ''; 
$userName = isset($_POST['userName']) ? $_POST['userName'] : '';  
$cNum = isset($_POST['cNum']) ? $_POST['cNum'] : '';
$pNum = isset($_POST['pNum']) ? $_POST['pNum'] : ''; // pNum 변수 추가 (원본 코드에서 사용되나 정의되지 않음)

$data = array();

if (!empty($cNum) && !empty($pNum)) {
    // 두 번째 쿼리: data 배열에 들어갈 정보 가져오기
    $sql_data = "
        SELECT num 
        FROM DaeriMember
        WHERE CertiTableNum = :cNum
        AND sangtae = '1'
        ORDER BY Jumin DESC
    ";
    
    $stmt = $conn->prepare($sql_data);
    $stmt->bindParam(':cNum', $cNum, PDO::PARAM_STR);
    $stmt->execute();
    
    if ($stmt->rowCount() > 0) {
        while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
            $memberNum = $row['num'];
            
            // 2012DaeriMember 테이블 업데이트
            $update = "UPDATE `2012DaeriMember` 
                      SET endorse_day = :after_day, manager = :userName
                      WHERE num = :memberNum";
            
            $updateStmt = $conn->prepare($update);
            $updateStmt->bindParam(':after_day', $after_day, PDO::PARAM_STR);
            $updateStmt->bindParam(':userName', $userName, PDO::PARAM_STR);
            $updateStmt->bindParam(':memberNum', $memberNum, PDO::PARAM_STR);
            
            try {
                $updateStmt->execute();
            } catch (PDOException $e) {
                $data[] = ["error" => "Error updating 2012DaeriMember: " . $e->getMessage()];
            }
            
            // DaeriMember 테이블 업데이트
            $update2 = "UPDATE `DaeriMember` 
                      SET endorse_day = :after_day, manager = :userName
                      WHERE num = :memberNum";
            
            $updateStmt2 = $conn->prepare($update2);
            $updateStmt2->bindParam(':after_day', $after_day, PDO::PARAM_STR);
            $updateStmt2->bindParam(':userName', $userName, PDO::PARAM_STR);
            $updateStmt2->bindParam(':memberNum', $memberNum, PDO::PARAM_STR);
            
            try {
                $updateStmt2->execute();
            } catch (PDOException $e) {
                $data[] = ["error" => "Error updating DaeriMember: " . $e->getMessage()];
            }
            
            $data[] = ["num" => $memberNum]; // 업데이트된 회원 번호 추가
        }
    }
}

// 응답 데이터 구성
$response = array(
    "success" => true,
    "endorse_day" => $endorse_day,
    "after_day" => $after_day,
    "userName" => $userName,
    "cNum" => $cNum,
    "pNum" => $pNum
);

// 배열 데이터를 추가
$response["data"] = $data;

// JSON 변환 후 출력
echo json_encode($response);

// PDO 연결은 스크립트 종료 시 자동으로 닫힘
// 명시적으로 닫으려면: $conn = null;
?>