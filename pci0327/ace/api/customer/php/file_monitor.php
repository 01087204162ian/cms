<?php
/**
 * 파일 시스템 모니터링 및 관리 스크립트
 * 파일명: file_monitor.php
 * 실행: php file_monitor.php
 */

require_once '../../../../api/config/db_config.php';
require_once 'file_utilities.php';

function formatBytes($size, $precision = 2) {
    $units = array('B', 'KB', 'MB', 'GB', 'TB');
    for ($i = 0; $size > 1024 && $i < count($units) - 1; $i++) {
        $size /= 1024;
    }
    return round($size, $precision) . ' ' . $units[$i];
}

function printSeparator($title = '') {
    echo "\n" . str_repeat('=', 60) . "\n";
    if ($title) {
        echo "  " . $title . "\n";
        echo str_repeat('=', 60) . "\n";
    }
}

try {
    $conn = getDbConnection();
    
    printSeparator('홀인원 파일 시스템 모니터링 리포트');
    echo "생성일시: " . date('Y-m-d H:i:s') . "\n";
    
    // 1. 전체 저장소 통계
    printSeparator('1. 저장소 사용량 통계');
    $stats = getStorageStatistics($conn);
    
    foreach ($stats as $stat) {
        echo sprintf("%-12s: %6d개 파일, %12s, 평균 %s\n",
            $stat['file_type'],
            $stat['file_count'],
            formatBytes($stat['total_size']),
            formatBytes($stat['avg_size'])
        );
    }
    
    // 2. 고아 파일 검사
    printSeparator('2. 고아 파일 검사 (미리보기)');
    $orphans = findOrphanFiles($conn, true); // dryRun = true
    
    echo "고아 파일 수: " . $orphans['count'] . "개\n";
    echo "고아 파일 총 크기: " . formatBytes($orphans['total_size']) . "\n";
    
    if ($orphans['count'] > 0) {
        echo "\n최근 고아 파일 (최대 5개):\n";
        $recent = array_slice($orphans['files'], 0, 5);
        foreach ($recent as $file) {
            echo "  " . $file['filename'] . " (" . formatBytes($file['size']) . ") - " . $file['modified'] . "\n";
        }
        
        if ($orphans['count'] > 5) {
            echo "  ... 그리고 " . ($orphans['count'] - 5) . "개 더\n";
        }
    }
    
    // 3. 중복 파일 검사
    printSeparator('3. 중복 파일 검사');
    $duplicates = findDuplicateFiles($conn);
    
    echo "중복 파일 그룹 수: " . count($duplicates) . "개\n";
    
    $totalDuplicateSize = 0;
    foreach ($duplicates as $dup) {
        $wastedSpace = $dup['total_size'] - ($dup['total_size'] / $dup['duplicate_count']);
        $totalDuplicateSize += $wastedSpace;
    }
    
    echo "중복으로 인한 낭비 공간: " . formatBytes($totalDuplicateSize) . "\n";
    
    if (count($duplicates) > 0) {
        echo "\n상위 중복 파일 (최대 3개):\n";
        $topDuplicates = array_slice($duplicates, 0, 3);
        foreach ($topDuplicates as $dup) {
            echo "  해시: " . substr($dup['file_hash'], 0, 16) . "... (" . $dup['duplicate_count'] . "개 중복, " . formatBytes($dup['total_size']) . ")\n";
        }
    }
    
    // 4. 파일 무결성 검사 (최근 100개 파일만)
    printSeparator('4. 파일 무결성 검사 (최근 파일 샘플)');
    
    // 최근 파일 ID 가져오기
    $recentStmt = $conn->query("
        SELECT id FROM holeinone_claim_attachments 
        WHERE is_deleted = 0 
        ORDER BY created_at DESC 
        LIMIT 100
    ");
    $recentFiles = $recentStmt->fetchAll(PDO::FETCH_COLUMN);
    
    $integrityIssues = 0;
    foreach ($recentFiles as $fileId) {
        $result = verifyFileIntegrity($conn, null); // 전체 검사는 부하가 클 수 있음
        if (count($result['issues']) > 0) {
            $integrityIssues += count($result['issues']);
        }
        break; // 샘플만 검사
    }
    
    echo "무결성 문제 파일: " . $integrityIssues . "개\n";
    
    // 5. 월별 업로드 통계
    printSeparator('5. 월별 업로드 통계 (올해)');
    $monthlyStats = getMonthlyUploadStats($conn);
    
    $currentMonth = 0;
    foreach ($monthlyStats as $stat) {
        if ($stat['month'] != $currentMonth) {
            if ($currentMonth > 0) echo "\n";
            echo sprintf("%2d월:\n", $stat['month']);
            $currentMonth = $stat['month'];
        }
        echo sprintf("  %-12s: %3d개 (%s)\n",
            $stat['file_type'],
            $stat['file_count'],
            formatBytes($stat['total_size'])
        );
    }
    
    // 6. 권장 사항
    printSeparator('6. 권장 사항');
    
    $recommendations = [];
    
    if ($orphans['count'] > 0) {
        $recommendations[] = "고아 파일 " . $orphans['count'] . "개를 정리하여 " . formatBytes($orphans['total_size']) . " 절약 가능";
    }
    
    if ($totalDuplicateSize > 1024 * 1024) { // 1MB 이상
        $recommendations[] = "중복 파일 정리로 " . formatBytes($totalDuplicateSize) . " 절약 가능";
    }
    
    if ($integrityIssues > 0) {
        $recommendations[] = "파일 무결성 문제 " . $integrityIssues . "개 확인 필요";
    }
    
    // 전체 사용량 경고
    $totalUsage = 0;
    foreach ($stats as $stat) {
        if ($stat['file_type'] === 'TOTAL') {
            $totalUsage = $stat['total_size'];
            break;
        }
    }
    
    if ($totalUsage > 1024 * 1024 * 1024) { // 1GB 이상
        $recommendations[] = "전체 사용량이 " . formatBytes($totalUsage) . "입니다. 정기적인 정리를 권장합니다.";
    }
    
    if (empty($recommendations)) {
        echo "현재 파일 시스템 상태가 양호합니다.\n";
    } else {
        foreach ($recommendations as $i => $rec) {
            echo ($i + 1) . ". " . $rec . "\n";
        }
    }
    
    // 7. 실행 가능한 명령어
    printSeparator('7. 관리 명령어');
    echo "고아 파일 실제 삭제: php file_cleanup.php --orphans\n";
    echo "오래된 파일 정리: php file_cleanup.php --old-files\n";
    echo "상세 무결성 검사: php file_cleanup.php --verify-all\n";
    
    printSeparator();
    
} catch (Exception $e) {
    echo "오류 발생: " . $e->getMessage() . "\n";
    exit(1);
}
?>