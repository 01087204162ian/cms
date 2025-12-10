<?php
error_reporting(E_ALL);
ini_set('display_errors', 1);
echo "PHP 버전: " . phpversion() . "<br>";
echo "현재 디렉토리: " . __DIR__ . "<br>";

// SimpleXLSXGen 파일 존재 확인
$filePath = __DIR__ . '/lib/src/SimpleXLSXGen.php';
echo "SimpleXLSXGen.php 경로: " . $filePath . "<br>";
echo "파일 존재: " . (file_exists($filePath) ? '예' : '아니오') . "<br>";

if (file_exists($filePath)) {
    try {
        require_once $filePath;
        echo "SimpleXLSXGen 로드 성공<br>";
        
        // 네임스페이스 포함하여 테스트
        $data = [['테스트', '데이터'], ['1', '2']];
        $xlsx = \Shuchkin\SimpleXLSXGen::fromArray($data);
        echo "Excel 생성 테스트 성공<br>";
        
        // 추가 테스트: 실제 파일 저장
        $filename = __DIR__ . '/test_output.xlsx';
        $xlsx->saveAs($filename);
        echo "파일 저장 테스트: " . (file_exists($filename) ? '성공' : '실패') . "<br>";
        
    } catch (Exception $e) {
        echo "오류: " . $e->getMessage() . "<br>";
        echo "오류 파일: " . $e->getFile() . " 라인: " . $e->getLine() . "<br>";
        echo "스택 트레이스:<br>";
        echo nl2br($e->getTraceAsString()) . "<br>";
    }
} else {
    echo "SimpleXLSXGen.php 파일을 찾을 수 없습니다.<br>";
    echo "lib 디렉토리 구조를 확인하세요.<br>";
    
    // 디렉토리 구조 확인
    $libDir = __DIR__ . '/lib';
    if (is_dir($libDir)) {
        echo "lib 디렉토리 내용:<br>";
        $files = scandir($libDir);
        foreach ($files as $file) {
            if ($file != '.' && $file != '..') {
                echo "- " . $file . "<br>";
            }
        }
    }
}
?>