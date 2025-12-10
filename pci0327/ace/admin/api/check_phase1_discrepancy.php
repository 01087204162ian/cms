<?php
// check_phase1_discrepancy.php
session_start();
require_once '../../../api/config/db_config.php';

$client_id = $_SESSION['client_id'];

try {
    $pdo = getDbConnection();
    
    echo "=== 1차 쿠폰 데이터 분석 ===\n\n";
    
    // 1. 쿠폰 기준 신청 건수
    $stmt = $pdo->prepare("
        SELECT 
            COUNT(*) as coupon_count,
            SUM(used_count) as total_applications
        FROM holeinone_coupons 
        WHERE client_id = ? AND testIs IN ('1', '2')
    ");
    $stmt->execute([$client_id]);
    $coupon_data = $stmt->fetch();
    
    echo "1️⃣ 쿠폰 테이블:\n";
    echo "   쿠폰 개수: " . $coupon_data['coupon_count'] . "\n";
    echo "   총 신청: " . $coupon_data['total_applications'] . "회\n\n";
    
    // 2. 신청서 기준
    $stmt = $pdo->prepare("
        SELECT COUNT(*) as application_count
        FROM holeinone_applications
        WHERE client_id = ? AND testIs IN ('1', '2')
    ");
    $stmt->execute([$client_id]);
    $app_count = $stmt->fetch()['application_count'];
    
    echo "2️⃣ 신청서 테이블:\n";
    echo "   신청서 개수: " . $app_count . "개\n\n";
    
    // 3. 동반자 확인
    $stmt = $pdo->prepare("
        SELECT COUNT(*) as companion_count
        FROM holeinone_companions hcomp
        INNER JOIN holeinone_applications ha ON hcomp.application_id = ha.id
        WHERE ha.client_id = ? AND ha.testIs IN ('1', '2')
    ");
    $stmt->execute([$client_id]);
    $companion_count = $stmt->fetch()['companion_count'];
    
    echo "3️⃣ 동반자 데이터:\n";
    echo "   동반자 수: " . $companion_count . "명\n";
    
    if ($companion_count > 0) {
        echo "   ⚠️ 경고: 1차 쿠폰에 동반자가 있습니다!\n\n";
        
        // 상세 내역 조회
        $stmt = $pdo->prepare("
            SELECT 
                ha.id,
                ha.coupon_number,
                ha.testIs,
                COUNT(hcomp.id) as companion_count
            FROM holeinone_applications ha
            LEFT JOIN holeinone_companions hcomp ON hcomp.application_id = ha.id
            WHERE ha.client_id = ? AND ha.testIs IN ('1', '2')
            GROUP BY ha.id
            HAVING companion_count > 0
            LIMIT 10
        ");
        $stmt->execute([$client_id]);
        $invalid_apps = $stmt->fetchAll();
        
        echo "   잘못된 신청서 예시:\n";
        foreach ($invalid_apps as $app) {
            echo "   - ID: {$app['id']}, 쿠폰: {$app['coupon_number']}, 동반자: {$app['companion_count']}명\n";
        }
        echo "\n";
    } else {
        echo "   ✅ 정상: 동반자 없음\n\n";
    }
    
    // 4. 계산
    $total_persons = $app_count + $companion_count;
    
    echo "4️⃣ 총 가입 인원 계산:\n";
    echo "   본인: " . $app_count . "명\n";
    echo "   동반자: " . $companion_count . "명\n";
    echo "   합계: " . $total_persons . "명\n\n";
    
    // 5. 불일치 확인
    echo "5️⃣ 불일치 분석:\n";
    echo "   신청 건수(쿠폰): " . $coupon_data['total_applications'] . "회\n";
    echo "   가입 인원(계산): " . $total_persons . "명\n";
    echo "   차이: " . ($total_persons - $coupon_data['total_applications']) . "명\n\n";
    
    if ($companion_count > 0) {
        echo "✅ 결론: 1차 쿠폰에 {$companion_count}명의 동반자가 잘못 등록되어 있습니다.\n";
        echo "\n해결 방법:\n";
        echo "DELETE hcomp FROM holeinone_companions hcomp\n";
        echo "INNER JOIN holeinone_applications ha ON hcomp.application_id = ha.id\n";
        echo "WHERE ha.client_id = {$client_id} AND ha.testIs IN ('1', '2');\n";
    }
    
} catch (Exception $e) {
    echo "오류: " . $e->getMessage() . "\n";
}
?>