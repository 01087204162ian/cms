<?php
/**
 * 파일 정리 실행 스크립트
 * 파일명: file_cleanup.php
 * 실행 예: php file_cleanup.php --orphans
 */

require_once '../../../../api/config/db_config.php';
require_once 'file_utilities.php';

function showHelp() {
    echo "홀인원 파일 정리 스크립트\n\n";
    echo "사용법: php file_cleanup.php [옵션]\n\n";
    echo "옵션:\n";
    echo "  --orphans           고아 파일 삭제\n";
    echo "  --old-files         오래된 파일 정리 (7년 경과)\n";
    echo "  --verify-all        전체 파일 무결성 검사\n";
    echo "  --dry-run           실제 삭제 없이 미리보기만\n";
    echo "  --help              이 도움말 표시\n\n";
    echo "예시:\n";
    echo "  php file_cleanup.php --orphans --dry-run    # 고아 파일 미리보기\n";
    echo "  php file_cleanup.php --orphans              # 고아 파일 실제 삭제\n";
    echo "  php file_cleanup.php --old-files            # 오래된 파일 정리\n";
}

function formatBytes($size, $precision = 2) {
    $units = array('B', 'KB', 'MB', 'GB', 'TB');
    for ($i = 0; $size > 1024 && $i < count($units) - 1; $i++) {
        $size /= 1024;
    }
    return round($size, $precision) . ' ' . $units[$i];
}

function confirmAction($message) {
    echo $message . " (y/N): ";
    $handle = fopen("php://stdin", "r");
    $line = fgets($handle);
    fclose($handle);
    return strtolower(trim($line)) === 'y';
}

// 명령행 인수 파싱
$options = getopt('', ['orphans', 'old-files', 'verify-all', 'dry-run', 'help']);

if (isset($options['help']) || empty($options)) {
    showHelp();
    exit(0);
}

$dryRun = isset($options['dry-run']);

try {
    $conn = getDbConnection();
    
    echo "홀인원 파일 정리 스크립트\n";
    echo "실행 시간: " . date('Y-m-d H:i:s') . "\n";
    echo "모드: " . ($dryRun ? "미리보기 (실제 삭제 안함)" : "실제 실행") . "\n";
    echo str_repeat('-', 50) . "\n";
    
    // 고아 파일 정리
    if (isset($options['orphans'])) {
        echo "\n🧹 고아 파일 정리를 시작합니다...\n";
        
        $result = findOrphanFiles($conn, $dryRun);
        
        echo "발견된 고아 파일: " . $result['count'] . "개\n";
        echo "총 크기: " . formatBytes($result['total_size']) . "\n";
        
        if ($result['count'] > 0) {
            if ($dryRun) {
                echo "\n삭제될 파일 목록 (최대 10개):\n";
                $sample = array_slice($result['files'], 0, 10);
                foreach ($sample as $file) {
                    echo "  " . $file['filename'] . " (" . formatBytes($file['size']) . ")\n";
                }
                if ($result['count'] > 10) {
                    echo "  ... 그리고 " . ($result['count'] - 10) . "개 더\n";
                }
            } else {
                if (confirmAction("\n{$result['count']}개 파일을 삭제하시겠습니까?")) {
                    $deleted = findOrphanFiles($conn, false);
                    echo "✅ " . $deleted['count'] . "개 파일을 삭제했습니다.\n";
                    echo "💾 " . formatBytes($deleted['total_size']) . " 절약되었습니다.\n";
                } else {
                    echo "❌ 삭제가 취소되었습니다.\n";
                }
            }
        } else {
            echo "✅ 고아 파일이 없습니다.\n";
        }
    }
    
    // 오래된 파일 정리
    if (isset($options['old-files'])) {
        echo "\n📅 오래된 파일 정리를 시작합니다...\n";
        echo "기준: 7년(2555일) 경과한 완료/거절된 보상 건의 파일\n";
        
        $result = cleanupOldFiles($conn, 2555, $dryRun);
        
        echo "발견된 오래된 파일: " . $result['files_found'] . "개\n";
        
        if ($result['files_found'] > 0) {
            $totalSize = 0;
            foreach ($result['files'] as $file) {
                $totalSize += $file['file_size'];
            }
            echo "총 크기: " . formatBytes($totalSize) . "\n";
            
            if ($dryRun) {
                echo "\n삭제될 파일 목록 (최대 5개):\n";
                $sample = array_slice($result['files'], 0, 5);
                foreach ($sample as $file) {
                    echo "  " . $file['stored_filename'] . " (보상번호: " . $file['claim_number'] . ", " . formatBytes($file['file_size']) . ")\n";
                }
                if ($result['files_found'] > 5) {
                    echo "  ... 그리고 " . ($result['files_found'] - 5) . "개 더\n";
                }
            } else {
                if (confirmAction("\n{$result['files_found']}개 오래된 파일을 삭제하시겠습니까?")) {
                    $cleaned = cleanupOldFiles($conn, 2555, false);
                    echo "✅ " . $cleaned['files_deleted'] . "개 파일을 삭제했습니다.\n";
                    echo "💾 " . formatBytes($cleaned['space_freed']) . " 절약되었습니다.\n";
                } else {
                    echo "❌ 삭제가 취소되었습니다.\n";
                }
            }
        } else {
            echo "✅ 정리할 오래된 파일이 없습니다.\n";
        }
    }
    
    // 전체 파일 무결성 검사
    if (isset($options['verify-all'])) {
        echo "\n🔍 전체 파일 무결성 검사를 시작합니다...\n";
        echo "이 작업은 시간이 오래 걸릴 수 있습니다.\n";
        
        if (!$dryRun && !confirmAction("계속하시겠습니까?")) {
            echo "❌ 검사가 취소되었습니다.\n";
        } else {
            $result = verifyFileIntegrity($conn);
            
            echo "\n검사 결과:\n";
            echo "  총 검사 파일: " . $result['total_checked'] . "개\n";
            echo "  정상 파일: " . $result['valid_files'] . "개\n";
            echo "  누락 파일: " . $result['missing_files'] . "개\n";
            echo "  손상 파일: " . $result['corrupted_files'] . "개\n";
            
            if (!empty($result['issues'])) {
                echo "\n문제가 발견된 파일들:\n";
                foreach ($result['issues'] as $issue) {
                    echo "  - " . $issue['type'] . ": " . $issue['filename'] . " (보상ID: " . $issue['claim_id'] . ")\n";
                }
                
                echo "\n⚠️ 관리자에게 문의하여 문제 파일들을 복구하거나 재업로드 요청하세요.\n";
            } else {
                echo "✅ 모든 파일이 정상입니다.\n";
            }
        }
    }
    
    echo "\n" . str_repeat('-', 50) . "\n";
    echo "작업 완료: " . date('Y-m-d H:i:s') . "\n";
    
} catch (Exception $e) {
    echo "❌ 오류 발생: " . $e->getMessage() . "\n";
    exit(1);
}

// 추가 도우미 함수들
function getDirectorySize($directory) {
    $size = 0;
    if (is_dir($directory)) {
        foreach (new RecursiveIteratorIterator(new RecursiveDirectoryIterator($directory)) as $file) {
            $size += $file->getSize();
        }
    }
    return $size;
}

function createMaintenanceReport($conn) {
    $report = [
        'timestamp' => date('Y-m-d H:i:s'),
        'storage_stats' => getStorageStatistics($conn),
        'orphan_files' => findOrphanFiles($conn, true),
        'duplicate_files' => findDuplicateFiles($conn),
        'integrity_check' => verifyFileIntegrity($conn)
    ];
    
    $reportFile = '../../../logs/file_maintenance_' . date('Y-m-d') . '.json';
    file_put_contents($reportFile, json_encode($report, JSON_PRETTY_PRINT));
    
    return $reportFile;
}
?>