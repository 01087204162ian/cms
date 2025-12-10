<?php
/**
 * certi_search.php
 * 보험 증권 정보 조회
 */

// 디버깅용 로그 추가
logError("certi_search.php 실행 시작");

try {
    // 입력 파라미터 확인
    if (empty($cNum)) {
        throw new Exception("증권번호(cNum)가 입력되지 않았습니다.");
    }

    logError("첫 번째 쿼리 실행 준비: cNum = " . $cNum);
    
    // 첫 번째 쿼리 - 증권 정보 조회
    $query1 = "SELECT * FROM `2012Certi` WHERE num = :cNum";
    $stmt1 = $conn->prepare($query1);
    $stmt1->execute([':cNum' => $cNum]);
    
    if (!$stmt1) {
        throw new Exception("증권 정보 조회 쿼리 실패: " . implode(", ", $conn->errorInfo()));
    }
    
    $Crow = $stmt1->fetch(PDO::FETCH_ASSOC);
    if (!$Crow) {
        // 증권번호로 조회 실패한 경우 정책번호로 시도
        logError("증권번호로 조회 실패, 정책번호로 시도: " . $policyNum);
        
        $divi = 2; // 디비전 값 설정
        logError("divi 값 설정됨: " . $divi);
        
        if (!empty($policyNum)) {
            logError("두 번째 쿼리 실행 준비: policyNum = " . $policyNum);
            
            // 두 번째 쿼리 - 정책번호로 조회
            $query2 = "SELECT * FROM `2012Certi` WHERE certi = :policyNum";
            $stmt2 = $conn->prepare($query2);
            $stmt2->execute([':policyNum' => $policyNum]);
            
            if (!$stmt2) {
                throw new Exception("정책번호 조회 쿼리 실패: " . implode(", ", $conn->errorInfo()));
            }
            
            $Crow = $stmt2->fetch(PDO::FETCH_ASSOC);
            if (!$Crow) {
                throw new Exception("증권번호(" . $cNum . ")와 정책번호(" . $policyNum . ")로 조회된 정보가 없습니다.");
            }
        } else {
            throw new Exception("증권번호(" . $cNum . ")로 조회된 정보가 없고, 정책번호도 입력되지 않았습니다.");
        }
    }
    
    // 결과 데이터 로깅
    logError("조회 결과: " . json_encode(array_keys($Crow)));
    logError("certi_search.php 실행 완료");
    
} catch (PDOException $e) {
    logError("certi_search.php PDO 오류: " . $e->getMessage());
    throw new Exception("인증 정보 조회 중 데이터베이스 오류 발생: " . $e->getMessage());
} catch (Exception $e) {
    logError("certi_search.php 실행 오류: " . $e->getMessage());
    throw $e;
}
?>