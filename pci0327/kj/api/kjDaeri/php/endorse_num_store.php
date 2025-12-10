<?php
/**
 * endorse_num_store.php - PDO 방식으로 변환
 * 배서 개수를 관리하는 기능
 */

try {
    logError("endorse_num_store.php 실행 시작");
    
    // 필수 변수 확인
    if (!isset($pdo)) {
        throw new Exception("데이터베이스 연결이 없거나 유효하지 않습니다");
    }
    
    if (!isset($ak) || empty($ak)) {
        throw new Exception("처리 코드(ak)가 없습니다");
    }
    
    if (!isset($endorse_num) || empty($endorse_num)) {
        throw new Exception("배서번호(endorse_num)가 없습니다");
    }
    
    if (!isset($dNum) || empty($dNum)) {
        throw new Exception("대리점번호(dNum)가 없습니다");
    }
    
    if (!isset($cNum) || empty($cNum)) {
        throw new Exception("증권번호(cNum)가 없습니다");
    }
    
    // 가입자 수를 배서 건수로 사용
    $e_count = count($endorseData);
    logError("배서 건수(가입자 수): $e_count");
    
    /***********************************************************/
    /* 배서 개수를 찾기 위해                                     */
    /* 배서 상태가 3이면 신청-->1이면 접수-->2이면-->처리완료    */
    /* 배서 상태가 3이면 상태이면 배서번호는 그대로 사용하고 ,   */
    /* 2또는 1이면 새로운 번호 사용합니다                        */
    /***********************************************************/
    
    // 배서 정보 처리
    if ($ak == '1') {
        logError("기존 배서 정보 업데이트 (ak=1)");
        
        // 배서건수 찾기
        $k_sql = "SELECT e_count FROM `2012EndorseList` WHERE pnum = ? AND `2012DaeriCompanyNum` = ? AND CertiTableNum = ?";
        logError("배서 건수 조회 SQL: $k_sql");
        
        $stmt = $pdo->prepare($k_sql);
        if (!$stmt) {
            throw new Exception("쿼리 준비 실패: " . implode(", ", $pdo->errorInfo()));
        }
        
        $stmt->execute([$endorse_num, $dNum, $cNum]);
        $k_row = $stmt->fetch(PDO::FETCH_ASSOC);
        
        if (!$k_row) {
            logError("배서 정보를 찾을 수 없습니다. 새로 생성합니다.");
            
            // 배서 정보가 없는 경우 삽입
            $esql = "INSERT INTO 2012EndorseList (pnum, 2012DaeriCompanyNum, InsuranceCompany, CertiTableNum, 
                    policyNum, e_count, wdate, endorse_day, userid, sangtae) 
                    VALUES (?, ?, ?, ?, ?, ?, NOW(), ?, ?, '3')";
            
            $stmt = $pdo->prepare($esql);
            if (!$stmt) {
                throw new Exception("쿼리 준비 실패: " . implode(", ", $pdo->errorInfo()));
            }
            
            // userid 변수가 정의되지 않은 경우 처리
            $userId = $userId ?? 'system';
            
            $stmt->execute([
                $endorse_num, 
                $dNum, 
                $InsuraneCompany, 
                $cNum, 
                $policyNum, 
                $e_count, 
                $endorse_day, 
                $userId
            ]);
            
            $rs_1 = $stmt->rowCount() > 0;
            logError("배서 정보 신규 생성 결과: " . ($rs_1 ? "성공" : "실패"));
        } else {
            // 기존 배서 건수에 새로운 배서 건수 추가
            $old_count = $k_row['e_count']; // 기존 배서 건수
            $new_count = $old_count + $e_count; // 기존 배서 건수 + 새로운 배서건수
            
            logError("기존 배서 건수: $old_count, 새 배서 건수: $e_count, 합계: $new_count");
            
            // 배서 정보 업데이트
            $e_update = "UPDATE `2012EndorseList` 
                        SET e_count = ? 
                        WHERE pnum = ? AND `2012DaeriCompanyNum` = ? AND CertiTableNum = ?";
            
            logError("배서 정보 업데이트 SQL: $e_update");
            
            $stmt = $pdo->prepare($e_update);
            if (!$stmt) {
                throw new Exception("쿼리 준비 실패: " . implode(", ", $pdo->errorInfo()));
            }
            
            $stmt->execute([$new_count, $endorse_num, $dNum, $cNum]);
            $rs_1 = $stmt->rowCount() > 0;
            logError("배서 정보 업데이트 결과: " . ($rs_1 ? "성공" : "실패"));
        }
    } else {
        logError("신규 배서 정보 생성 (ak=$ak)");
        
        // 새로운 배서 정보 삽입
        $esql = "INSERT INTO 2012EndorseList (pnum, `2012DaeriCompanyNum`, InsuranceCompany, CertiTableNum, 
                policyNum, e_count, wdate, endorse_day, userid, sangtae) 
                VALUES (?, ?, ?, ?, ?, ?, NOW(), ?, ?, '3')";
        
        logError("신규 배서 정보 SQL: $esql");
        
        $stmt = $pdo->prepare($esql);
        if (!$stmt) {
            throw new Exception("쿼리 준비 실패: " . implode(", ", $pdo->errorInfo()));
        }
        
        // userid 변수가 정의되지 않은 경우 처리
        $userId = $userId ?? 'system';
        
        $stmt->execute([
            $endorse_num, 
            $dNum, 
            $InsuraneCompany, 
            $cNum, 
            $policyNum, 
            $e_count, 
            $endorse_day, 
            $userId
        ]);
        
        $rs_1 = $stmt->rowCount() > 0;
        logError("신규 배서 정보 생성 결과: " . ($rs_1 ? "성공" : "실패"));
    }
    
    // 결과 확인
    if (!$rs_1) {
        throw new Exception("배서 정보 처리 실패");
    }
    
    logError("endorse_num_store.php 실행 완료");
    
} catch (PDOException $e) {
    logError("endorse_num_store.php PDO 오류: " . $e->getMessage());
    throw new Exception("배서 정보 처리 중 데이터베이스 오류 발생: " . $e->getMessage());
} catch (Exception $e) {
    logError("endorse_num_store.php 실행 오류: " . $e->getMessage());
    throw $e;
}
?>