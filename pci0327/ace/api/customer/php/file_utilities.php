<?php
/**
 * 파일 관리 유틸리티 함수들
 * 파일명: file_utilities.php
 */

/**
 * 특정 보상 신청의 첨부파일 목록 조회
 */
function getClaimAttachments($conn, $claimId) {
    $stmt = $conn->prepare("
        SELECT * FROM holeinone_claim_attachments 
        WHERE claim_id = ? AND is_deleted = 0 
        ORDER BY file_type, created_at
    ");
    $stmt->execute([$claimId]);
    return $stmt->fetchAll(PDO::FETCH_ASSOC);
}

/**
 * 파일 다운로드 처리 (다운로드 횟수 증가)
 */
function downloadAttachmentFile($conn, $attachmentId) {
    $stmt = $conn->prepare("
        SELECT * FROM holeinone_claim_attachments 
        WHERE id = ? AND is_deleted = 0
    ");
    $stmt->execute([$attachmentId]);
    $file = $stmt->fetch(PDO::FETCH_ASSOC);
    
    if (!$file) {
        throw new Exception("파일을 찾을 수 없습니다.");
    }
    
    $fullPath = $file['file_path'] . $file['stored_filename'];
    
    if (!file_exists($fullPath)) {
        throw new Exception("물리적 파일이 존재하지 않습니다.");
    }
    
    // 다운로드 횟수 업데이트
    $updateStmt = $conn->prepare("
        UPDATE holeinone_claim_attachments 
        SET download_count = download_count + 1, 
            last_accessed_at = NOW() 
        WHERE id = ?
    ");
    $updateStmt->execute([$attachmentId]);
    
    // 파일 다운로드 헤더 설정
    header('Content-Type: ' . $file['mime_type']);
    header('Content-Disposition: attachment; filename="' . $file['original_filename'] . '"');
    header('Content-Length: ' . $file['file_size']);
    header('Cache-Control: no-cache, must-revalidate');
    
    // 파일 출력
    readfile($fullPath);
    exit;
}

/**
 * 파일 논리적 삭제 (실제 파일은 유지)
 */
function softDeleteAttachment($conn, $attachmentId, $deletedBy = null) {
    $stmt = $conn->prepare("
        UPDATE holeinone_claim_attachments 
        SET is_deleted = 1, 
            deleted_at = NOW(), 
            deleted_by = ? 
        WHERE id = ? AND is_deleted = 0
    ");
    
    return $stmt->execute([$deletedBy, $attachmentId]);
}

/**
 * 고아 파일 찾기 (DB에는 없지만 물리적으로 존재하는 파일)
 */
function findOrphanFiles($conn, $dryRun = true) {
    $baseDir = '../../../uploads/claims/';
    $orphanFiles = [];
    $totalSize = 0;
    
    if (!is_dir($baseDir)) {
        return ['count' => 0, 'total_size' => 0, 'files' => []];
    }
    
    // 재귀적으로 모든 파일 검색
    $iterator = new RecursiveIteratorIterator(
        new RecursiveDirectoryIterator($baseDir, RecursiveDirectoryIterator::SKIP_DOTS)
    );
    
    foreach ($iterator as $file) {
        if ($file->isFile()) {
            $fileName = $file->getFilename();
            $filePath = dirname($file->getPathname()) . '/';
            
            // DB에서 해당 파일 검색
            $stmt = $conn->prepare("
                SELECT id FROM holeinone_claim_attachments 
                WHERE stored_filename = ? AND file_path = ?
            ");
            $stmt->execute([$fileName, $filePath]);
            
            if (!$stmt->fetch()) {
                $fileSize = $file->getSize();
                $orphanFiles[] = [
                    'path' => $file->getPathname(),
                    'filename' => $fileName,
                    'size' => $fileSize,
                    'modified' => date('Y-m-d H:i:s', $file->getMTime())
                ];
                $totalSize += $fileSize;
                
                // 실제 삭제 (dryRun이 false일 때만)
                if (!$dryRun) {
                    unlink($file->getPathname());
                }
            }
        }
    }
    
    return [
        'count' => count($orphanFiles),
        'total_size' => $totalSize,
        'files' => $orphanFiles
    ];
}

/**
 * 중복 파일 검사 (파일 해시 기반)
 */
function findDuplicateFiles($conn, $fileHash = null) {
    if ($fileHash) {
        // 특정 해시의 중복 파일 검색
        $stmt = $conn->prepare("
            SELECT c.claim_number, a.* 
            FROM holeinone_claim_attachments a
            JOIN holeinone_claims c ON a.claim_id = c.id
            WHERE a.file_hash = ? AND a.is_deleted = 0
            ORDER BY a.created_at
        ");
        $stmt->execute([$fileHash]);
    } else {
        // 모든 중복 파일 검색
        $stmt = $conn->query("
            SELECT file_hash, COUNT(*) as duplicate_count,
                   GROUP_CONCAT(id) as attachment_ids,
                   SUM(file_size) as total_size
            FROM holeinone_claim_attachments 
            WHERE is_deleted = 0 AND file_hash IS NOT NULL
            GROUP BY file_hash 
            HAVING COUNT(*) > 1
            ORDER BY duplicate_count DESC
        ");
    }
    
    return $stmt->fetchAll(PDO::FETCH_ASSOC);
}

/**
 * 디스크 사용량 통계
 */
function getStorageStatistics($conn) {
    $stmt = $conn->query("
        SELECT 
            file_type,
            COUNT(*) as file_count,
            SUM(file_size) as total_size,
            AVG(file_size) as avg_size,
            MAX(file_size) as max_size,
            MIN(file_size) as min_size
        FROM holeinone_claim_attachments 
        WHERE is_deleted = 0
        GROUP BY file_type
        
        UNION ALL
        
        SELECT 
            'TOTAL' as file_type,
            COUNT(*) as file_count,
            SUM(file_size) as total_size,
            AVG(file_size) as avg_size,
            MAX(file_size) as max_size,
            MIN(file_size) as min_size
        FROM holeinone_claim_attachments 
        WHERE is_deleted = 0
    ");
    
    return $stmt->fetchAll(PDO::FETCH_ASSOC);
}

/**
 * 오래된 파일 정리 (법정 보관기간 경과)
 */
function cleanupOldFiles($conn, $retentionDays = 2555, $dryRun = true) { // 7년 = 2555일
    $stmt = $conn->prepare("
        SELECT a.*, c.claim_number, c.status as claim_status
        FROM holeinone_claim_attachments a
        JOIN holeinone_claims c ON a.claim_id = c.id
        WHERE a.created_at < DATE_SUB(NOW(), INTERVAL ? DAY)
        AND a.is_deleted = 0
        AND c.status IN ('completed', 'rejected')
    ");
    $stmt->execute([$retentionDays]);
    $oldFiles = $stmt->fetchAll(PDO::FETCH_ASSOC);
    
    $deletedCount = 0;
    $freedSpace = 0;
    
    if (!$dryRun) {
        foreach ($oldFiles as $file) {
            // 물리적 파일 삭제
            $fullPath = $file['file_path'] . $file['stored_filename'];
            if (file_exists($fullPath)) {
                unlink($fullPath);
                $freedSpace += $file['file_size'];
            }
            
            // DB에서 논리적 삭제
            softDeleteAttachment($conn, $file['id'], 'system_cleanup');
            $deletedCount++;
        }
    }
    
    return [
        'files_found' => count($oldFiles),
        'files_deleted' => $deletedCount,
        'space_freed' => $freedSpace,
        'files' => $oldFiles
    ];
}

/**
 * 파일 무결성 검사 (해시 검증)
 */
function verifyFileIntegrity($conn, $claimId = null) {
    $whereClause = $claimId ? "WHERE claim_id = ? AND is_deleted = 0" : "WHERE is_deleted = 0";
    $params = $claimId ? [$claimId] : [];
    
    $stmt = $conn->prepare("
        SELECT id, claim_id, stored_filename, file_path, file_hash, file_size
        FROM holeinone_claim_attachments 
        $whereClause
    ");
    $stmt->execute($params);
    $files = $stmt->fetchAll(PDO::FETCH_ASSOC);
    
    $results = [
        'total_checked' => count($files),
        'valid_files' => 0,
        'missing_files' => 0,
        'corrupted_files' => 0,
        'issues' => []
    ];
    
    foreach ($files as $file) {
        $fullPath = $file['file_path'] . $file['stored_filename'];
        
        if (!file_exists($fullPath)) {
            $results['missing_files']++;
            $results['issues'][] = [
                'type' => 'missing',
                'attachment_id' => $file['id'],
                'claim_id' => $file['claim_id'],
                'filename' => $file['stored_filename']
            ];
            continue;
        }
        
        // 파일 크기 검증
        $actualSize = filesize($fullPath);
        if ($actualSize != $file['file_size']) {
            $results['corrupted_files']++;
            $results['issues'][] = [
                'type' => 'size_mismatch',
                'attachment_id' => $file['id'],
                'claim_id' => $file['claim_id'],
                'filename' => $file['stored_filename'],
                'expected_size' => $file['file_size'],
                'actual_size' => $actualSize
            ];
            continue;
        }
        
        // 파일 해시 검증
        if ($file['file_hash']) {
            $actualHash = hash_file('sha256', $fullPath);
            if ($actualHash !== $file['file_hash']) {
                $results['corrupted_files']++;
                $results['issues'][] = [
                    'type' => 'hash_mismatch',
                    'attachment_id' => $file['id'],
                    'claim_id' => $file['claim_id'],
                    'filename' => $file['stored_filename']
                ];
                continue;
            }
        }
        
        $results['valid_files']++;
    }
    
    return $results;
}

/**
 * 특정 보상 신청의 파일 통계
 */
function getClaimFileStats($conn, $claimId) {
    $stmt = $conn->prepare("
        SELECT 
            file_type,
            COUNT(*) as count,
            SUM(file_size) as total_size,
            GROUP_CONCAT(original_filename) as filenames
        FROM holeinone_claim_attachments 
        WHERE claim_id = ? AND is_deleted = 0
        GROUP BY file_type
    ");
    $stmt->execute([$claimId]);
    
    return $stmt->fetchAll(PDO::FETCH_ASSOC);
}

/**
 * 월별 업로드 통계
 */
function getMonthlyUploadStats($conn, $year = null) {
    $year = $year ?: date('Y');
    
    $stmt = $conn->prepare("
        SELECT 
            MONTH(created_at) as month,
            file_type,
            COUNT(*) as file_count,
            SUM(file_size) as total_size
        FROM holeinone_claim_attachments 
        WHERE YEAR(created_at) = ? AND is_deleted = 0
        GROUP BY MONTH(created_at), file_type
        ORDER BY month, file_type
    ");
    $stmt->execute([$year]);
    
    return $stmt->fetchAll(PDO::FETCH_ASSOC);
}
?>