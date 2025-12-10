<?php
/**
 * 고객사 정보 검증 유틸리티
 * 
 * 고객사 정보 관련 입력값 검증 함수를 포함합니다.
 */

/**
 * 이메일 주소의 유효성을 검사하는 함수
 * 
 * @param string $email 검사할 이메일 주소
 * @return bool 유효한 이메일이면 true, 아니면 false 반환
 */
function validateEmail($email) {
    if (empty($email)) {
        return true; // 이메일이 비어있으면 선택 입력으로 간주하고 통과
    }
    
    return filter_var($email, FILTER_VALIDATE_EMAIL) !== false;
}

/**
 * 전화번호의 형식을 표준화하는 함수
 * 
 * @param string $phone 처리할 전화번호
 * @return string 표준화된 전화번호
 */
function formatPhoneNumber($phone) {
    if (empty($phone)) {
        return null;
    }
    
    // 숫자만 남기고 모든 문자 제거
    $phone = preg_replace('/[^0-9]/', '', $phone);
    
    // 길이에 따라 형식 지정
    $length = strlen($phone);
    
    if ($length === 10) {
        // 일반 전화번호 (10자리)
        return preg_replace('/(\d{3})(\d{3})(\d{4})/', '$1-$2-$3', $phone);
    } elseif ($length === 11) {
        // 휴대폰 번호 (11자리)
        return preg_replace('/(\d{3})(\d{4})(\d{4})/', '$1-$2-$3', $phone);
    }
    
    // 다른 길이는 원래 형식 반환
    return $phone;
}

/**
 * 고객사 입력 데이터를 정리하고 검증하는 함수
 * 
 * @param array $data 입력 데이터 배열
 * @return array 정리된 데이터와 오류 메시지
 */
function sanitizeClientData($data) {
    $result = [
        'valid' => true,
        'errors' => [],
        'data' => []
    ];
    
    // 고객사명 (필수)
    if (empty($data['name'])) {
        $result['valid'] = false;
        $result['errors'][] = '고객사명은 필수 입력 항목입니다.';
    } else {
        $result['data']['name'] = trim($data['name']);
    }
    
    // 담당자 이름 (선택)
    $result['data']['manager_name'] = isset($data['manager_name']) ? trim($data['manager_name']) : null;
    
    // 담당자 연락처 (선택)
    if (!empty($data['manager_phone'])) {
        $result['data']['manager_phone'] = formatPhoneNumber($data['manager_phone']);
    } else {
        $result['data']['manager_phone'] = null;
    }
    
    // 담당자 이메일 (선택)
    if (!empty($data['manager_email'])) {
        if (validateEmail($data['manager_email'])) {
            $result['data']['manager_email'] = trim($data['manager_email']);
        } else {
            $result['valid'] = false;
            $result['errors'][] = '유효하지 않은 이메일 형식입니다.';
        }
    } else {
        $result['data']['manager_email'] = null;
    }
    
    // 메모 (선택)
    $result['data']['note'] = isset($data['note']) ? trim($data['note']) : null;
    
    // 생성 시간
    $result['data']['created_at'] = date('Y-m-d H:i:s');
    
    return $result;
}

/**
 * 지점 입력 데이터를 정리하고 검증하는 함수
 * 
 * @param array $data 입력 데이터 배열
 * @return array 정리된 데이터와 오류 메시지
 */
function sanitizeBranchData($data) {
    $result = [
        'valid' => true,
        'errors' => [],
        'data' => []
    ];
    
    // 고객사 ID (필수)
    if (empty($data['client_id'])) {
        $result['valid'] = false;
        $result['errors'][] = '고객사 ID는 필수 입력 항목입니다.';
    } else {
        $result['data']['client_id'] = filter_var($data['client_id'], FILTER_SANITIZE_NUMBER_INT);
        
        if (!filter_var($result['data']['client_id'], FILTER_VALIDATE_INT)) {
            $result['valid'] = false;
            $result['errors'][] = '고객사 ID가 유효하지 않습니다.';
        }
    }
    
    // 지점명 (필수)
    if (empty($data['branchName'])) {
        $result['valid'] = false;
        $result['errors'][] = '지점명은 필수 입력 항목입니다.';
    } else {
        $result['data']['branchName'] = htmlspecialchars(trim($data['branchName']), ENT_QUOTES, 'UTF-8');
        
        if (strlen($result['data']['branchName']) > 100) {
            $result['valid'] = false;
            $result['errors'][] = '지점명은 100자를 초과할 수 없습니다.';
        }
    }
    
    // 지점 담당자 이름 (선택)
    if (!empty($data['branchDamdangName'])) {
        $result['data']['branchDamdangName'] = htmlspecialchars(trim($data['branchDamdangName']), ENT_QUOTES, 'UTF-8');
        
        if (strlen($result['data']['branchDamdangName']) > 50) {
            $result['valid'] = false;
            $result['errors'][] = '담당자명은 50자를 초과할 수 없습니다.';
        }
    } else {
        $result['data']['branchDamdangName'] = null;
    }
    
    // 지점 담당자 연락처 (선택)
    if (!empty($data['branchDamdangPhone'])) {
        // 전화번호 형식 정리 (하이픈 등 특수문자 제거)
        $phone = preg_replace('/[^0-9]/', '', $data['branchDamdangPhone']);
        $result['data']['branchDamdangPhone'] = $phone;
    } else {
        $result['data']['branchDamdangPhone'] = null;
    }
    
    // 지점 담당자 이메일 (선택)
    if (!empty($data['branchDamdangEmail'])) {
        $email = filter_var(trim($data['branchDamdangEmail']), FILTER_SANITIZE_EMAIL);
        $result['data']['branchDamdangEmail'] = $email;
        
        if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
            $result['valid'] = false;
            $result['errors'][] = '유효하지 않은 이메일 주소입니다.';
        }
    } else {
        $result['data']['branchDamdangEmail'] = null;
    }
    
    return $result;
}
?>