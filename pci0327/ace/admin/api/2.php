<?php
session_start();
require_once '../../../api/config/db_config.php';

$client_id = $_SESSION['client_id'];
$pdo = getDbConnection();

echo "=== 수정 후 검증 ===\n\n";

// 1차 리얼
$stmt = $pdo->prepare("
    SELECT 
        (SELECT SUM(used_count) 
         FROM holeinone_coupons 
         WHERE client_id = ? AND testIs = '1') as coupon_count,
        
        (SELECT COUNT(*) 
         FROM holeinone_applications 
         WHERE client_id = ? AND testIs = '1' AND summary = '1') as app_count
");
$stmt->execute([$client_id, $client_id]);
$result = $stmt->fetch();

echo "1️⃣ 1차 리얼:\n";
echo "   쿠폰 used_count: {$result['coupon_count']}회\n";
echo "   정상 신청서: {$result['app_count']}개\n";
echo "   차이: " . ($result['app_count'] - $result['coupon_count']) . "개\n\n";

if ($result['app_count'] == $result['coupon_count']) {
    echo "✅ 완벽히 일치!\n\n";
} else {
    echo "⚠️ 여전히 차이 발생\n\n";
}

// 1차 테스트
$stmt = $pdo->prepare("
    SELECT 
        (SELECT SUM(used_count) 
         FROM holeinone_coupons 
         WHERE client_id = ? AND testIs = '2') as coupon_count,
        
        (SELECT COUNT(*) 
         FROM holeinone_applications 
         WHERE client_id = ? AND testIs = '2' AND summary = '1') as app_count
");
$stmt->execute([$client_id, $client_id]);
$test_result = $stmt->fetch();

echo "2️⃣ 1차 테스트:\n";
echo "   쿠폰 used_count: {$test_result['coupon_count']}회\n";
echo "   정상 신청서: {$test_result['app_count']}개\n";
echo "   차이: " . ($test_result['app_count'] - $test_result['coupon_count']) . "개\n";
?>