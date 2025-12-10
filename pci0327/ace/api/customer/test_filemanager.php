<?php
// test_filemanager.php 생성하여 테스트
require_once "./php/FileManager.php";

try {
    $fileManager = new FileManager();
    echo "FileManager 클래스 로딩 성공!\n";
} catch (Exception $e) {
    echo "오류: " . $e->getMessage() . "\n";
}

$fileManager = new FileManager();
// 실제로 디렉토리가 생성되는지 확인
$reflection = new ReflectionClass($fileManager);
$method = $reflection->getMethod('createUploadDirectory');
$method->setAccessible(true);
$result = $method->invoke($fileManager);
echo "생성된 디렉토리: " . $result . "\n";
?>