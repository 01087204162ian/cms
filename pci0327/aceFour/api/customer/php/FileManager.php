<?php
/**
 * 홀인원 보상 신청 파일 관리 클래스
 * 파일명: FileManager.php
 * 경로: ./php/FileManager.php (또는 적절한 위치)
 */

class FileManager {
    private $baseUploadDir;
    private $allowedTypes;
    private $maxFileSize;
    private $uploadedFiles = []; // 업로드된 파일 추적
    
    public function __construct() {
        $this->baseUploadDir = '../../../ace/uploads/claims/';
        
        // 허용되는 파일 타입 정의
        $this->allowedTypes = [
            'photo' => ['image/jpeg', 'image/png', 'image/gif', 'image/jpg'],
            'certificate' => ['application/pdf', 'image/jpeg', 'image/png', 'image/jpg'],
            'additional' => [
                'application/pdf', 
                'image/jpeg', 
                'image/png', 
                'image/jpg',
                'application/msword', 
                'application/vnd.openxmlformats-officedocument.wordprocessingml.document'
            ]
        ];
        
        $this->maxFileSize = 10 * 1024 * 1024; // 10MB
    }
    
    /**
     * 연도/월/일 기반 업로드 디렉토리 생성
     */
    private function createUploadDirectory($date = null) {
        $date = $date ?: date('Y-m-d');
        $dateParts = explode('-', $date);
        $year = $dateParts[0];
        $month = $dateParts[1];
        $day = $dateParts[2];
        
        $fullPath = $this->baseUploadDir . $year . '/' . $month . '/' . $day . '/';
        
        if (!is_dir($fullPath)) {
            if (!mkdir($fullPath, 0755, true)) {
                throw new Exception("디렉토리 생성에 실패했습니다: " . $fullPath);
            }
        }
        
        return $fullPath;
    }
    
    /**
     * 체계적인 파일명 생성
     */
    private function generateFileName($claimId, $fileType, $originalName) {
        $extension = strtolower(pathinfo($originalName, PATHINFO_EXTENSION));
        $timestamp = time();
        $random = mt_rand(1000, 9999);
        
        return "claim_{$claimId}_{$fileType}_{$timestamp}_{$random}.{$extension}";
    }
    
    /**
     * 파일 해시 생성 (중복 검사용)
     */
    private function generateFileHash($filePath) {
        if (!file_exists($filePath)) {
            throw new Exception("파일이 존재하지 않습니다: " . $filePath);
        }
        return hash_file('sha256', $filePath);
    }
    
    /**
     * 파일 유효성 검사
     */
    private function validateFile($file, $fileType) {
        // 업로드 오류 검사
        if (!isset($file) || $file['error'] !== UPLOAD_ERR_OK) {
            throw new Exception("파일 업로드 오류: " . ($file['error'] ?? 'Unknown error'));
        }
        
        // 파일 크기 검사
        if ($file['size'] > $this->maxFileSize) {
            $maxSizeMB = $this->maxFileSize / 1024 / 1024;
            throw new Exception("파일 크기가 너무 큽니다. 최대 {$maxSizeMB}MB까지 가능합니다.");
        }
        
        // 빈 파일 검사
        if ($file['size'] == 0) {
            throw new Exception("빈 파일은 업로드할 수 없습니다.");
        }
        
        // MIME 타입 검사
        $mimeType = mime_content_type($file['tmp_name']);
        if (!in_array($mimeType, $this->allowedTypes[$fileType])) {
            throw new Exception("허용되지 않은 파일 형식입니다. ({$mimeType})");
        }
        
        return true;
    }
    
    /**
     * 메인 파일 업로드 함수
     */
    public function uploadFile($file, $claimId, $fileType, $conn, $clientId, $couponNumber) {
        try {
            // 파일 유효성 검사
            $this->validateFile($file, $fileType);
            
            // 업로드 디렉토리 생성
            $uploadDir = $this->createUploadDirectory();
            
            // 파일명 생성
            $storedFileName = $this->generateFileName($claimId, $fileType, $file['name']);
            $fullPath = $uploadDir . $storedFileName;
            
            // 파일 이동
            if (!move_uploaded_file($file['tmp_name'], $fullPath)) {
                throw new Exception("파일 저장에 실패했습니다.");
            }
            
            // 파일 해시 생성
            $fileHash = $this->generateFileHash($fullPath);
            
            // 파일 정보 객체 생성
            $fileInfo = [
                'file_type' => $fileType,
                'original_filename' => $file['name'],
                'stored_filename' => $storedFileName,
                'file_size' => $file['size'],
                'mime_type' => mime_content_type($fullPath),
                'file_path' => $uploadDir,
                'file_hash' => $fileHash,
                'full_path' => $fullPath
            ];
            
            // 업로드된 파일 추적 목록에 추가
            $this->uploadedFiles[] = $fileInfo;
            
            // 데이터베이스에 파일 정보 저장
            $attachmentId = $this->saveFileInfo($conn, $claimId, $clientId, $couponNumber, $fileInfo);
            $fileInfo['attachment_id'] = $attachmentId;
            
            return $fileInfo;
            
        } catch (Exception $e) {
            // 업로드 실패 시 파일 삭제
            if (isset($fullPath) && file_exists($fullPath)) {
                unlink($fullPath);
            }
            throw $e;
        }
    }
    
    /**
     * 파일 정보를 holeinone_claim_attachments 테이블에 저장
     */
    private function saveFileInfo($conn, $claimId, $clientId, $couponNumber, $fileInfo) {
        $stmt = $conn->prepare("
            INSERT INTO holeinone_claim_attachments (
                claim_id, client_id, coupon_number, file_type,
                original_filename, stored_filename, file_size, mime_type,
                file_path, file_hash, upload_ip, created_at
            ) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, NOW())
        ");
        
        $uploadIp = $_SERVER['REMOTE_ADDR'] ?? null;
        
        $result = $stmt->execute([
            $claimId,
            $clientId,
            $couponNumber,
            $fileInfo['file_type'],
            $fileInfo['original_filename'],
            $fileInfo['stored_filename'],
            $fileInfo['file_size'],
            $fileInfo['mime_type'],
            $fileInfo['file_path'],
            $fileInfo['file_hash'],
            $uploadIp
        ]);
        
        if (!$result) {
            throw new Exception("파일 정보 저장에 실패했습니다.");
        }
        
        return $conn->lastInsertId();
    }
    
    /**
     * 트랜잭션 실패 시 업로드된 모든 파일 정리
     */
    public function cleanupUploadedFiles() {
        foreach ($this->uploadedFiles as $fileInfo) {
            if (isset($fileInfo['full_path']) && file_exists($fileInfo['full_path'])) {
                unlink($fileInfo['full_path']);
            }
        }
        $this->uploadedFiles = [];
    }
    
    /**
     * 업로드된 파일 목록 반환
     */
    public function getUploadedFiles() {
        return $this->uploadedFiles;
    }
    
    /**
     * 업로드된 파일 수 반환
     */
    public function getUploadedFileCount() {
        return count($this->uploadedFiles);
    }
    
    /**
     * 특정 타입의 파일이 업로드되었는지 확인
     */
    public function hasFileType($fileType) {
        foreach ($this->uploadedFiles as $fileInfo) {
            if ($fileInfo['file_type'] === $fileType) {
                return true;
            }
        }
        return false;
    }
}
?>