<?php
/**
 * holeinone_companions 테이블 마이그레이션
 * 기존 데이터에 companion_phone_hash 값 추가
 * 
 * 실행 방법: 브라우저에서 직접 접속
 * 예: https://yourdomain.com/api/customer/migration_add_companion_phone_hash.php
 */

session_start();
header("Content-Type: text/html; charset=utf-8");

require_once '../../../api/config/db_config.php';
require_once "../../../kj/api/kjDaeri/php/encryption.php";

// 디버깅 활성화
ini_set('display_errors', 1);
error_reporting(E_ALL);

echo "<!DOCTYPE html>";
echo "<html lang='ko'>";
echo "<head>";
echo "<meta charset='UTF-8'>";
echo "<meta name='viewport' content='width=device-width, initial-scale=1.0'>";
echo "<title>동반자 해시 마이그레이션</title>";
echo "<style>";
echo "body { font-family: 'Noto Sans KR', sans-serif; max-width: 1200px; margin: 50px auto; padding: 20px; background: #f5f5f5; }";
echo ".container { background: white; padding: 30px; border-radius: 10px; box-shadow: 0 2px 10px rgba(0,0,0,0.1); }";
echo "h1 { color: #333; border-bottom: 3px solid #667eea; padding-bottom: 10px; }";
echo ".status { padding: 10px; margin: 10px 0; border-radius: 5px; }";
echo ".success { background: #d4edda; border-left: 4px solid #28a745; color: #155724; }";
echo ".error { background: #f8d7da; border-left: 4px solid #dc3545; color: #721c24; }";
echo ".info { background: #d1ecf1; border-left: 4px solid #17a2b8; color: #0c5460; }";
echo ".warning { background: #fff3cd; border-left: 4px solid #ffc107; color: #856404; }";
echo ".summary { background: #e7f3ff; padding: 20px; border-radius: 8px; margin-top: 20px; }";
echo ".summary h2 { margin-top: 0; color: #667eea; }";
echo ".stat { display: inline-block; margin: 10px 20px 10px 0; font-size: 18px; }";
echo ".stat strong { color: #667eea; font-size: 24px; }";
echo "table { width: 100%; border-collapse: collapse; margin-top: 20px; }";
echo "th, td { padding: 12px; text-align: left; border-bottom: 1px solid #ddd; }";
echo "th { background: #667eea; color: white; }";
echo "tr:hover { background: #f5f5f5; }";
echo ".btn { display: inline-block; padding: 10px 20px; margin-top: 20px; background: #667eea; color: white; text-decoration: none; border-radius: 5px; }";
echo ".btn:hover { background: #5568d3; }";
echo "</style>";
echo "</head>";
echo "<body>";
echo "<div class='container'>";

echo "<h1>🔄 동반자 전화번호 해시 마이그레이션</h1>";

try {
    // 데이터베이스 연결
    $conn = getDbConnection();
    
    echo "<div class='status info'>";
    echo "✅ 데이터베이스 연결 성공";
    echo "</div>";
    
    // companion_phone_hash가 NULL인 레코드 조회
    $stmt = $conn->prepare("
        SELECT id, companion_phone 
        FROM holeinone_companions 
        WHERE companion_phone_hash IS NULL
        ORDER BY id ASC
    ");
    $stmt->execute();
    $companions = $stmt->fetchAll(PDO::FETCH_ASSOC);
    
    $totalCount = count($companions);
    
    echo "<div class='status info'>";
    echo "📊 처리 대상: <strong>{$totalCount}건</strong>";
    echo "</div>";
    
    if ($totalCount === 0) {
        echo "<div class='status success'>";
        echo "✅ 모든 데이터에 이미 해시가 존재합니다. 마이그레이션이 필요하지 않습니다.";
        echo "</div>";
        echo "</div></body></html>";
        exit;
    }
    
    // 업데이트 준비
    $updateStmt = $conn->prepare("
        UPDATE holeinone_companions 
        SET companion_phone_hash = ? 
        WHERE id = ?
    ");
    
    $successCount = 0;
    $failCount = 0;
    $results = [];
    
    echo "<h2>📝 처리 진행 상황</h2>";
    echo "<table>";
    echo "<tr>";
    echo "<th style='width: 80px;'>ID</th>";
    echo "<th style='width: 150px;'>전화번호</th>";
    echo "<th>해시값 (앞 20자)</th>";
    echo "<th style='width: 100px;'>상태</th>";
    echo "</tr>";
    
    foreach ($companions as $companion) {
        $id = $companion['id'];
        $encryptedPhone = $companion['companion_phone'];
        
        try {
            // 1. 전화번호 복호화
            $decryptedPhone = decryptData($encryptedPhone);
            
            if ($decryptedPhone === false || empty($decryptedPhone)) {
                throw new Exception("복호화 실패");
            }
            
            // 2. 전화번호 정리 (하이픈 제거, 숫자만)
            $cleanPhone = preg_replace('/[^0-9]/', '', $decryptedPhone);
            
            if (strlen($cleanPhone) < 10 || strlen($cleanPhone) > 11) {
                throw new Exception("잘못된 전화번호 형식");
            }
            
            // 3. SHA-256 해시 생성
            $phoneHash = hash('sha256', $cleanPhone);
            
            // 4. 데이터베이스 업데이트
            $updateStmt->execute([$phoneHash, $id]);
            
            // 성공
            $successCount++;
            $results[] = [
                'id' => $id,
                'phone' => $cleanPhone,
                'hash' => substr($phoneHash, 0, 20) . '...',
                'status' => 'success'
            ];
            
            echo "<tr>";
            echo "<td>{$id}</td>";
            echo "<td>{$cleanPhone}</td>";
            echo "<td style='font-family: monospace;'>{$results[count($results)-1]['hash']}</td>";
            echo "<td style='color: #28a745; font-weight: bold;'>✅ 성공</td>";
            echo "</tr>";
            
        } catch (Exception $e) {
            // 실패
            $failCount++;
            $results[] = [
                'id' => $id,
                'phone' => 'N/A',
                'hash' => 'N/A',
                'status' => 'fail',
                'error' => $e->getMessage()
            ];
            
            echo "<tr style='background: #fff5f5;'>";
            echo "<td>{$id}</td>";
            echo "<td colspan='2' style='color: #dc3545;'>오류: {$e->getMessage()}</td>";
            echo "<td style='color: #dc3545; font-weight: bold;'>❌ 실패</td>";
            echo "</tr>";
        }
    }
    
    echo "</table>";
    
    // 최종 요약
    echo "<div class='summary'>";
    echo "<h2>📊 마이그레이션 완료</h2>";
    echo "<div class='stat'>총 처리: <strong>{$totalCount}건</strong></div>";
    echo "<div class='stat' style='color: #28a745;'>성공: <strong>{$successCount}건</strong></div>";
    echo "<div class='stat' style='color: #dc3545;'>실패: <strong>{$failCount}건</strong></div>";
    echo "</div>";
    
    // 검증 쿼리
    echo "<h2>🔍 검증</h2>";
    $verifyStmt = $conn->prepare("
        SELECT 
            COUNT(*) as total,
            SUM(CASE WHEN companion_phone_hash IS NOT NULL THEN 1 ELSE 0 END) as with_hash,
            SUM(CASE WHEN companion_phone_hash IS NULL THEN 1 ELSE 0 END) as without_hash
        FROM holeinone_companions
    ");
    $verifyStmt->execute();
    $verify = $verifyStmt->fetch(PDO::FETCH_ASSOC);
    
    echo "<div class='status " . ($verify['without_hash'] == 0 ? 'success' : 'warning') . "'>";
    echo "전체 레코드: {$verify['total']}건<br>";
    echo "해시 있음: {$verify['with_hash']}건<br>";
    echo "해시 없음: {$verify['without_hash']}건<br>";
    
    if ($verify['without_hash'] == 0) {
        echo "<br>✅ <strong>모든 레코드에 해시가 생성되었습니다!</strong>";
    } else {
        echo "<br>⚠️ <strong>아직 {$verify['without_hash']}건의 레코드에 해시가 없습니다.</strong>";
    }
    echo "</div>";
    
    // 다음 단계 안내
    if ($successCount > 0) {
        echo "<div class='status success'>";
        echo "<h3>✅ 다음 단계</h3>";
        echo "<ol>";
        echo "<li><strong>sinupAce.php</strong> 파일 수정 - 신규 가입 시 해시 자동 생성</li>";
        echo "<li><strong>getSignupHistory.php</strong> 파일 수정 - 동반자 검색 기능 추가</li>";
        echo "<li><strong>history.js</strong> 파일 수정 - UI에 동반자 정보 표시</li>";
        echo "</ol>";
        echo "</div>";
    }
    
    echo "<a href='../../history.html' class='btn'>🔙 가입내역 페이지로 돌아가기</a>";
    
} catch (PDOException $e) {
    echo "<div class='status error'>";
    echo "❌ 데이터베이스 오류: " . htmlspecialchars($e->getMessage());
    echo "</div>";
    error_log("마이그레이션 오류: " . $e->getMessage());
} catch (Exception $e) {
    echo "<div class='status error'>";
    echo "❌ 처리 중 오류: " . htmlspecialchars($e->getMessage());
    echo "</div>";
    error_log("마이그레이션 처리 오류: " . $e->getMessage());
} finally {
    $conn = null;
}

echo "</div>";
echo "</body>";
echo "</html>";
?>