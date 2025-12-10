<?php
/**
 * Excel Export 디버깅 및 테스트 파일
 */

error_reporting(E_ALL);
ini_set('display_errors', 1);

echo "<h2>Excel Export 디버깅</h2>";
echo "현재 시간: " . date('Y-m-d H:i:s') . "<br><br>";

// 1. 기본 환경 체크
echo "<h3>1. 기본 환경 체크</h3>";
echo "PHP 버전: " . phpversion() . "<br>";
echo "현재 디렉토리: " . __DIR__ . "<br>";

// 2. 파일 존재 확인
echo "<h3>2. 파일 존재 확인</h3>";
$files = [
    'SimpleXLSXGen' => __DIR__ . '/lib/src/SimpleXLSXGen.php',
    'DB Config' => __DIR__ . '/../../../api/config/db_config.php',
    'Encryption' => __DIR__ . '/../../../kj/api/kjDaeri/php/encryption.php'
];

foreach ($files as $name => $path) {
    echo "{$name}: {$path} - " . (file_exists($path) ? '✅ 존재' : '❌ 없음') . "<br>";
}

// 3. SimpleXLSXGen 테스트
echo "<h3>3. SimpleXLSXGen 라이브러리 테스트</h3>";
if (file_exists($files['SimpleXLSXGen'])) {
    try {
        require_once $files['SimpleXLSXGen'];
        echo "✅ SimpleXLSXGen 로드 성공<br>";
        
        // 클래스 존재 확인
        if (class_exists('Shuchkin\SimpleXLSXGen')) {
            echo "✅ Shuchkin\SimpleXLSXGen 클래스 존재<br>";
            
            // 간단한 Excel 생성 테스트
            $testData = [
                ['이름', '나이', '직업'],
                ['홍길동', 30, '개발자'],
                ['김철수', 25, '디자이너']
            ];
            
            $xlsx = \Shuchkin\SimpleXLSXGen::fromArray($testData);
            echo "✅ Excel 객체 생성 성공<br>";
            
            // 메모리에서 Excel 내용 생성 테스트
            $content = $xlsx->output();
            echo "✅ Excel 내용 생성 성공 (크기: " . strlen($content) . " bytes)<br>";
            
        } else {
            echo "❌ Shuchkin\SimpleXLSXGen 클래스를 찾을 수 없음<br>";
        }
        
    } catch (Exception $e) {
        echo "❌ SimpleXLSXGen 오류: " . $e->getMessage() . "<br>";
        echo "파일: " . $e->getFile() . " 라인: " . $e->getLine() . "<br>";
    }
} else {
    echo "❌ SimpleXLSXGen 파일 없음<br>";
}

// 4. 세션 테스트
echo "<h3>4. 세션 테스트</h3>";
session_start();
echo "세션 상태: " . (session_status() == PHP_SESSION_ACTIVE ? '✅ 활성' : '❌ 비활성') . "<br>";
echo "세션 ID: " . session_id() . "<br>";
echo "client_id 세션: " . (isset($_SESSION['client_id']) ? '✅ 존재 (' . $_SESSION['client_id'] . ')' : '❌ 없음') . "<br>";

// 5. GET 파라미터 테스트
echo "<h3>5. GET 파라미터 테스트</h3>";
$params = ['searchType', 'searchInput', 'startDate', 'endDate'];
foreach ($params as $param) {
    $value = $_GET[$param] ?? '';
    echo "{$param}: " . ($value ? $value : '(비어있음)') . "<br>";
}

// 6. 데이터베이스 연결 테스트
echo "<h3>6. 데이터베이스 연결 테스트</h3>";
if (file_exists($files['DB Config'])) {
    try {
        require_once $files['DB Config'];
        
        if (function_exists('getDbConnection')) {
            $pdo = getDbConnection();
            echo "✅ 데이터베이스 연결 성공<br>";
            
            // 간단한 쿼리 테스트
            $stmt = $pdo->query("SELECT 1 as test");
            $result = $stmt->fetch();
            echo "✅ 쿼리 테스트 성공: " . $result['test'] . "<br>";
            
        } else {
            echo "❌ getDbConnection 함수를 찾을 수 없음<br>";
        }
        
    } catch (Exception $e) {
        echo "❌ 데이터베이스 오류: " . $e->getMessage() . "<br>";
    }
} else {
    echo "❌ DB Config 파일 없음<br>";
}

// 7. 암호화 모듈 테스트
echo "<h3>7. 암호화 모듈 테스트</h3>";
if (file_exists($files['Encryption'])) {
    try {
        require_once $files['Encryption'];
        echo "✅ 암호화 모듈 로드 성공<br>";
        
        // 상수 확인
        if (defined('ENCRYPT_KEY') && defined('CIPHER_ALGO')) {
            echo "✅ 암호화 상수 정의됨<br>";
            echo "CIPHER_ALGO: " . CIPHER_ALGO . "<br>";
        } else {
            echo "❌ 암호화 상수가 정의되지 않음<br>";
        }
        
    } catch (Exception $e) {
        echo "❌ 암호화 모듈 오류: " . $e->getMessage() . "<br>";
    }
} else {
    echo "❌ 암호화 모듈 파일 없음<br>";
}

// 8. 실제 Excel 다운로드 테스트 버튼
echo "<h3>8. 실제 Excel 다운로드 테스트</h3>";
?>

<form method="get" action="test_excel_download.php" target="_blank">
    <p>테스트용 Excel 다운로드:</p>
    <button type="submit">Excel 다운로드 테스트</button>
</form>

<script>
// JavaScript로 현재 export 함수 호출 테스트
function testExportCall() {
    const params = new URLSearchParams({
        searchType: 'all',
        searchInput: '',
        startDate: '2025-01-01',
        endDate: '2025-01-31'
    });
    
    const url = 'export_applications.php?' + params.toString();
    console.log('Export URL:', url);
    
    // 새 창에서 열어서 오류 확인
    window.open(url, '_blank');
}
</script>

<button onclick="testExportCall()">실제 Export 함수 테스트</button>

<?php
echo "<br><br><strong>위 정보를 확인한 후 어떤 부분에서 오류가 발생하는지 알려주세요.</strong>";
?>