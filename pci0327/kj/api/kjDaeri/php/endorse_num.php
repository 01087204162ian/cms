<?php
/**
 * endorse_num.php - PDO 방식으로 변환
 * 배서 번호를 찾아 처리하는 기능
 */

try {
    // 시작 로깅
    logError("endorse_num.php 실행 시작");
    
    // 필수 변수 확인
    if (!isset($pdo) ) {
        throw new Exception("데이터베이스 연결이 없거나 유효하지 않습니다");
    }
    
    if (!isset($cNum) || empty($cNum)) {
        throw new Exception("증권번호(cNum)가 없습니다");
    }
    
    if (!isset($dNum) || empty($dNum)) {
        throw new Exception("대리점번호(dNum)가 없습니다");
    }
    
    if (!isset($endorse_day) || empty($endorse_day)) {
        throw new Exception("보험 시작일(endorse_day)이 없습니다");
    }
    
    // 변수 값 로깅
    logError("변수 확인: cNum = $cNum, dNum = $dNum, endorse_day = $endorse_day");
    
    // 배서 번호를 찾기 위해
    $en_sql = "SELECT max(num) as max_num FROM `2012EndorseList` WHERE 2012DaeriCompanyNum = ? AND CertiTableNum = ?";
    logError("SQL 쿼리 준비: $en_sql");
    
    $stmt = $pdo->prepare($en_sql);
    if (!$stmt) {
        throw new Exception("쿼리 준비 실패: " . implode(", ", $pdo->errorInfo()));
    }
    
    $stmt->execute([$dNum, $cNum]);
    $k_row = $stmt->fetch(PDO::FETCH_ASSOC);
    $maxnum = $k_row['max_num'];
    
    logError("최대 번호 조회 결과: " . ($maxnum ? $maxnum : "없음"));
    
    if ($maxnum) {
        // 배서 정보 조회
        $p_sql = "SELECT pnum, sangtae, endorse_day, e_count FROM `2012EndorseList` WHERE num = ?";
        logError("배서 정보 조회 SQL: $p_sql");
        
        $stmt = $pdo->prepare($p_sql);
        if (!$stmt) {
            throw new Exception("쿼리 준비 실패: " . implode(", ", $pdo->errorInfo()));
        }
        
        $stmt->execute([$maxnum]);
        $ksh = $stmt->fetch(PDO::FETCH_ASSOC);
        
        if ($ksh) {
            logError("배서 정보 조회 결과: " . json_encode($ksh));
            
            $endorse_num = (int)$ksh['pnum'];
            $e_sangtae = $ksh['sangtae'];
            $old_endorse_day = $ksh['endorse_day'];   // DB에 배서기준일 
            $endorse_day = trim($endorse_day);         // 새로운 배서기준일
            
            /****************************************************************/
            /* 배서 상태가 3이면 신청-->1이면 접수-->2이면-->처리완료        */
            /* 배서 상태가 3이면 상태이면 배서번호는 그대로 사용하고,        */
            /* 2또는 1이면 새로운 번호 사용합니다                            */
            /****************************************************************/
            logError("기존 배서일: $old_endorse_day, 새 배서일: $endorse_day");
            
            if ($endorse_day == $old_endorse_day) { // 원래있던 배서기준일과 새로운기준일이 같으면 배서번호는 그대로 사용하고
                logError("배서일이 동일: 상태에 따라 처리");
                
                // 배서 상태가 3이면 상태이면 배서번호는 그대로 사용하고, 2또는 1이면 새로운 번호 사용합니다
                switch ($e_sangtae) {
                    case '3':
                        $endorse_num = $endorse_num;
                        $ak = 1;
                        logError("상태 3(신청): 배서번호 유지 - $endorse_num");
                        break;
                    default:
                        $endorse_num = $endorse_num + 1;
                        $ak = 2;
                        logError("상태 기타(접수/완료): 배서번호 증가 - $endorse_num");
                        break;
                }
            } else {
                logError("배서일이 다름: 배서번호 증가");
                $endorse_num = $endorse_num + 1;
                $ak = 3;
            }
        } else {
            logError("배서 정보를 찾을 수 없음: 새 번호 설정");
            // 배서 정보가 없는 경우 기본값 설정
            $endorse_num = 1; // 첫 번호로 시작
            $e_sangtae = "최초등록";
            $old_endorse_day = null;
            $ak = "신규";
        }
    } else {
        logError("배서 목록 정보가 없음: 새 번호 설정");
        // 배서 목록이 없는 경우 기본값 설정
        $endorse_num = 1; // 첫 번호로 시작
        $e_sangtae = "최초등록";
        $old_endorse_day = null;
        $ak = "신규";
    }
    
    // 결과 로깅
    logError("최종 배서번호: $endorse_num, 상태: $e_sangtae, 코드: $ak");
    logError("endorse_num.php 실행 완료");
    
} catch (PDOException $e) {
    logError("endorse_num.php PDO 오류: " . $e->getMessage());
    throw new Exception("배서번호 처리 중 데이터베이스 오류 발생: " . $e->getMessage());
} catch (Exception $e) {
    logError("endorse_num.php 실행 오류: " . $e->getMessage());
    throw $e;
}
?>