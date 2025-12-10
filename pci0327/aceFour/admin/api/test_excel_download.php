<?php
/**
 * 간단한 Excel 다운로드 테스트
 */

error_reporting(E_ALL);
ini_set('display_errors', 1);

try {
    // SimpleXLSXGen 라이브러리 로드
    require_once __DIR__ . '/lib/src/SimpleXLSXGen.php';
    
    // 테스트 데이터
    $data = [
        ['PCI Korea 홀인원보험 관리시스템'],
        ['테스트 Excel 파일'],
        ['생성일시: ' . date('Y-m-d H:i:s')],
        [''],
        ['순번', '이름', '전화번호', '골프장', '신청일시'],
        [1, '홍길동', '010-1234-5678', '테스트골프장', '2025-01-15 10:30:00'],
        [2, '김철수', '010-9876-5432', '샘플컨트리클럽', '2025-01-15 11:00:00'],
        [3, '이영희', '010-5555-1234', '드림골프장', '2025-01-15 14:20:00']
    ];
    
    // Excel 생성
    $xlsx = \Shuchkin\SimpleXLSXGen::fromArray($data);
    
    // 파일명 설정
    $filename = '테스트_가입신청내역_' . date('Y-m-d') . '.xlsx';
    
    // 다운로드 헤더 설정
    header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
    header('Content-Disposition: attachment; filename="' . $filename . '"');
    header('Cache-Control: max-age=0');
    header('Pragma: public');
    header('Expires: 0');
    
    // Excel 파일 출력
    echo $xlsx->output();
    
} catch (Exception $e) {
    // 오류 발생시 HTML로 오류 메시지 표시
    header('Content-Type: text/html; charset=utf-8');
    echo "<h2>Excel 생성 오류</h2>";
    echo "<p><strong>오류 메시지:</strong> " . htmlspecialchars($e->getMessage()) . "</p>";
    echo "<p><strong>파일:</strong> " . htmlspecialchars($e->getFile()) . "</p>";
    echo "<p><strong>라인:</strong> " . $e->getLine() . "</p>";
    echo "<h3>스택 트레이스:</h3>";
    echo "<pre>" . htmlspecialchars($e->getTraceAsString()) . "</pre>";
    echo "<br><a href='javascript:history.back()'>뒤로 가기</a>";
}
?>