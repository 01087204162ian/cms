<?php
/**
 * 응답 유틸리티 함수
 * 
 * API 응답을 표준화하는 유틸리티 함수들을 포함합니다.
 */

/**
 * JSON 응답을 클라이언트에 전송하는 함수
 * 
 * @param bool $success 요청 성공 여부
 * @param string $message 응답 메시지
 * @param mixed $data 응답 데이터 (기본값: null)
 * @param int $status_code HTTP 상태 코드 (기본값: 200)
 * @return void
 */
function sendResponse($success, $message, $data = null, $status_code = 200) {
    // HTTP 상태 코드 설정
    http_response_code($status_code);
    
    // JSON 응답 생성
    $response = [
        'success' => $success,
        'message' => $message
    ];
    
    // 데이터가 있는 경우 응답에 추가
    if ($data !== null) {
        $response['data'] = $data;
    }
    
    // JSON 헤더 설정 및 응답 출력
    header('Content-Type: application/json; charset=UTF-8');
    echo json_encode($response, JSON_UNESCAPED_UNICODE);
    exit;
}

/**
 * 성공 응답을 클라이언트에 전송하는 함수
 * 
 * @param string $message 성공 메시지
 * @param mixed $data 응답 데이터 (기본값: null)
 * @param int $status_code HTTP 상태 코드 (기본값: 200)
 * @return void
 */
function sendSuccessResponse($message, $data = null, $status_code = 200) {
    sendResponse(true, $message, $data, $status_code);
}

/**
 * 오류 응답을 클라이언트에 전송하는 함수
 * 
 * @param string $message 오류 메시지
 * @param mixed $data 응답 데이터 (기본값: null)
 * @param int $status_code HTTP 상태 코드 (기본값: 400)
 * @return void
 */
function sendErrorResponse($message, $data = null, $status_code = 400) {
    sendResponse(false, $message, $data, $status_code);
}
?>