<?php
header("Content-Type: application/json; charset=utf-8");
require_once '../../../api/config/db_config.php';

// GET 파라미터 가져오기
$dNum = $_GET['dNum'] ?? '';
$data = [];
$main_info = [];

if (!empty($dNum)) {
    try {
        // PDO 연결 가져오기 (db_config.php에서 정의된 함수 사용)
        $conn = getDbConnection();
        
        // 첫 번째 쿼리: 대리점 회사 정보 가져오기
        $stmt_company = $conn->prepare("SELECT * FROM `2012DaeriCompany` WHERE num = :dNum");
        $stmt_company->bindParam(':dNum', $dNum, PDO::PARAM_STR);
        $stmt_company->execute();
        
        if ($company_info = $stmt_company->fetch()) {
            // 대리점 회사 정보를 main_info에 복사
            foreach ($company_info as $key => $value) {
                $main_info[$key] = $value;
            }
            
            // 두 번째 쿼리: 회원 정보 가져오기
            if (isset($company_info['MemberNum']) && !empty($company_info['MemberNum'])) {
                $memberNum = $company_info['MemberNum'];
                $stmt_member = $conn->prepare("SELECT name FROM `2012Member` WHERE num = :memberNum");
                $stmt_member->bindParam(':memberNum', $memberNum, PDO::PARAM_STR);
                $stmt_member->execute();
                
                if ($member_info = $stmt_member->fetch()) {
                    $main_info['name'] = $member_info['name'] ?? '';
                }
            }
            
            // 세 번째 쿼리: 고객 정보 가져오기
            $stmt_customer = $conn->prepare("SELECT mem_id, permit FROM `2012Costomer` WHERE `2012DaeriCompanyNum` = :dNum");
            $stmt_customer->bindParam(':dNum', $dNum, PDO::PARAM_STR);
            $stmt_customer->execute();
            
            if ($customer_info = $stmt_customer->fetch()) {
                $main_info['mem_id'] = $customer_info['mem_id'] ?? '';
                $main_info['permit'] = $customer_info['permit'] ?? '';
            }
        }
        
        // 네 번째 쿼리: 인증서 정보 가져오기
        $stmt_data = $conn->prepare("
            SELECT * 
            FROM 2012CertiTable 
            WHERE 2012DaeriCompanyNum = :dNum 
            AND startyDay BETWEEN DATE_SUB(CURDATE(), INTERVAL 1 YEAR) AND CURDATE()
        ");
        $stmt_data->bindParam(':dNum', $dNum, PDO::PARAM_STR);
        $stmt_data->execute();
        
        while ($row = $stmt_data->fetch()) {
            // 각 증권별 인원을 구하기 위해 인원 수 가져오기
            $certiTableNum = $row['num'];
            $stmt_inwon = $conn->prepare("
                SELECT COUNT(*) as cnt 
                FROM `DaeriMember` 
                WHERE `CertiTableNum` = :certiTableNum AND push = '4'
            ");
            $stmt_inwon->bindParam(':certiTableNum', $certiTableNum, PDO::PARAM_STR);
            $stmt_inwon->execute();
            
            $inwonRow = $stmt_inwon->fetch();
            $row['inwon'] = $inwonRow['cnt'] ?? 0;
            
            $data[] = $row;
        }
    } catch (PDOException $e) {
        http_response_code(500);
        echo json_encode(['success' => false, 'message' => '데이터베이스 쿼리 오류: ' . $e->getMessage()], JSON_UNESCAPED_UNICODE);
        exit;
    }
}

// 응답 데이터 구성
$response = ["success" => true];

// 최상위 데이터를 추가
if (!empty($main_info)) {
    $response = array_merge($response, $main_info);
}

// 배열 데이터를 추가
$response["data"] = $data;

// JSON 출력
echo json_encode($response, JSON_UNESCAPED_UNICODE);

// PDO 연결 닫기 (명시적으로 닫을 필요 없음, 스크립트 종료 시 자동으로 닫힘)
$conn = null;
?>