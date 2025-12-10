<?php
// "2012DaeriCompany Table hphone 과 2012Costomer Table 의 연락처 일치 여부 확인하는 작업"
// "/2012/pop_up/ajax/php/coSms.php 에서 읽기전용이 아닌 아이디 개수 2개 이상인 경우는 2012Costomer Table 의 연락처로 문자 통보하고"
// "1개인 경우는 2012DaeriCompany Table 연락처로 문자 발송으로 2025-03-27 수정함"

if ($divi == 1) { // 정상분납인 경우
    $endorsePreminum = '';
} else {
    $endorsePreminum = $row2['Endorsement_insurance_premium'];
}

$c_premiun = $row2['Endorsement_insurance_company_premium'];

// 2022-10-10
// 케이드라이브처럼 관리자가 다수인 경우 각 관리자에게 문자가 전송될 수 있도록 수정함.
// 그 경우 1명만 배서리스트에 표기될 수 있도록 SMSData
$LastTime=date('YmdHis');
try {
    // 읽기전용이 아닌 아이디 개수 조회
    $id_stmt = $pdo->prepare("SELECT * FROM 2012Costomer WHERE 2012DaeriCompanyNum = :dnum AND readIs != '1'");
    $id_stmt->execute(['dnum' => $dNum]);
    $id_rows = $id_stmt->fetchAll(PDO::FETCH_ASSOC);
    $idCount = count($id_rows);

    if ($idCount >= 2) {
        foreach ($id_rows as $index => $id_row) {
            if ($index >= 1) {
                $dagun = 2; // 배서 보험료 카운트할 때는 dagun 1만 적용하고, 2는 적용하지 않음
            } else {
                $dagun = 1;
            }

            list($sphone1, $sphone2, $sphone3) = explode("-", $company_tel); // 회사번호
            list($hphone1, $hphone2, $hphone3) = explode("-", $id_row['hphone']);

            if ($endorseCh == 1) {
                $send_name = 'drive';
                $company = '';
            } else {
                $dong_db = "preminum";
                $send_name = 'preminum';
                $InsuraneCompany = 10;
            }

            // 휴대폰 번호 유효성 체크
            $validPrefixes = ['011', '016', '017', '018', '019', '010'];
            if (in_array($hphone1, $validPrefixes)) {
                // 문자발송 고객
                $insert_stmt = $pdo->prepare("INSERT INTO SMSData (
                    SendId, Rphone1, Rphone2, Rphone3, Sphone1, Sphone2, Sphone3, 
                    SendName, RecvName, Msg, url, Rdate, Rtime, Result, kind, 
                    ErrCode, Retry1, Retry2, company, ssang_c_num, endorse_num, 
                    userid, preminum, preminum2, c_preminum, 2012DaeriMemberNum, 
                    2012DaeriCompanyNum, policyNum, endorse_day, damdanga, push, 
                    insuranceCom, qboard, dagun,LastTime
                ) VALUES (
                    :sendid, :rphone1, :rphone2, :rphone3, :sphone1, :sphone2, :sphone3,
                    :send_name, :recv_name, :msg, :url, :rdate, :rtime, :result, :kind,
                    :errcode, :retry1, :retry2, :company, :ssang_c_num, :endorse_num,
                    :userid, :preminum, :preminum2, :c_preminum, :daeri_member_num,
                    :daeri_company_num, :policy_num, :endorse_day, :damdanga, :push,
                    :insurance_com, :qboard, :dagun,:LastTime
                )");

                $params = [
                    'sendid' => 'csdrive',
                    'rphone1' => $hphone1,
                    'rphone2' => $hphone2,
                    'rphone3' => $hphone3,
                    'sphone1' => $sphone1,
                    'sphone2' => $sphone2,
                    'sphone3' => $sphone3,
                    'send_name' => $send_name,
                    'recv_name' => 'CS',
                    'msg' => $msg,
                    'url' => '',
                    'rdate' => '',
                    'rtime' => '',
                    'result' => '0',
                    'kind' => 's',
                    'errcode' => 0,
                    'retry1' => 0,
                    'retry2' => 0,
                    'company' => $InsuraneCompany,
                    'ssang_c_num' => $cNum,
                    'endorse_num' => $pNum,
                    'userid' => $userId,
                    'preminum' => $endorsePreminum,
                    'preminum2' => $endorsePreminum2 ?? '',
                    'c_preminum' => $c_premiun,
                    'daeri_member_num' => $num,
                    'daeri_company_num' => $dNum,
                    'policy_num' => $policyNum,
                    'endorse_day' => $endorse_day,
                    'damdanga' => $row['MemberNum'],
                    'push' => $push_2,
                    'insurance_com' => $InsuraneCompany,
                    'qboard' => $qboard,
                    'dagun' => $dagun,
					'LastTime'=>$LastTime
                ];

                if ($insert_stmt->execute($params)) {
                    $message = '문자 발송 완료';
                }

				$receiver=$hphone1.$hphone2.$hphone3;
				$sendData = [
					"receiver" => $receiver,
					"msg" => $msg,
					"testmode_yn" => "N"
				];

// 함수 호출
				$result = sendAligoSms($sendData);
            }
            $a[4] = $id_row['hphone'];
        }
    } else {
        // 아이디가 1개인 경우
        list($sphone1, $sphone2, $sphone3) = explode("-", $company_tel); // 회사번호
        list($hphone1, $hphone2, $hphone3) = explode("-", $con_phone1);

        if ($endorseCh == 1) {
            $send_name = 'drive';
            $company = '';
        } else {
            $dong_db = "preminum";
            $send_name = 'preminum';
            $InsuraneCompany = 10;
        }
        $dagun = 1;

        // 휴대폰 번호 유효성 체크
        $validPrefixes = ['011', '016', '017', '018', '019', '010'];
        if (in_array($hphone1, $validPrefixes)) {
            // 문자발송 고객
            $insert_stmt = $pdo->prepare("INSERT INTO SMSData (
                SendId, Rphone1, Rphone2, Rphone3, Sphone1, Sphone2, Sphone3, 
                SendName, RecvName, Msg, url, Rdate, Rtime, Result, kind, 
                ErrCode, Retry1, Retry2, company, ssang_c_num, endorse_num, 
                userid, preminum, preminum2, c_preminum, 2012DaeriMemberNum, 
                2012DaeriCompanyNum, policyNum, endorse_day, damdanga, push, 
                insuranceCom, qboard, dagun,LastTime
            ) VALUES (
                :sendid, :rphone1, :rphone2, :rphone3, :sphone1, :sphone2, :sphone3,
                :send_name, :recv_name, :msg, :url, :rdate, :rtime, :result, :kind,
                :errcode, :retry1, :retry2, :company, :ssang_c_num, :endorse_num,
                :userid, :preminum, :preminum2, :c_preminum, :daeri_member_num,
                :daeri_company_num, :policy_num, :endorse_day, :damdanga, :push,
                :insurance_com, :qboard, :dagun,:LastTime
            )");

            $params = [
                'sendid' => 'csdrive',
                'rphone1' => $hphone1,
                'rphone2' => $hphone2,
                'rphone3' => $hphone3,
                'sphone1' => $sphone1,
                'sphone2' => $sphone2,
                'sphone3' => $sphone3,
                'send_name' => $send_name,
                'recv_name' => 'CS',
                'msg' => $msg,
                'url' => '',
                'rdate' => '',
                'rtime' => '',
                'result' => '0',
                'kind' => 's',
                'errcode' => 0,
                'retry1' => 0,
                'retry2' => 0,
                'company' => $InsuraneCompany,
                'ssang_c_num' => $cNum,
                'endorse_num' => $pNum,
                'userid' => $userId,
                'preminum' => $endorsePreminum,
                'preminum2' => $endorsePreminum2 ?? '',
                'c_preminum' => $c_premiun,
                'daeri_member_num' => $num,
                'daeri_company_num' => $dNum,
                'policy_num' => $policyNum,
                'endorse_day' => $endorse_day,
                'damdanga' => $row['MemberNum'],
                'push' => $push_2,
                'insurance_com' => $InsuraneCompany,
                'qboard' => $qboard,
                'dagun' => $dagun,
				'LastTime'=>$LastTime
            ];

            if ($insert_stmt->execute($params)) {
                $message = '문자 발송 완료';
            }

				$receiver=$hphone1.$hphone2.$hphone3;
			$sendData = [
					"receiver" => $receiver,
					"msg" => $msg,
					"testmode_yn" => "N"
				];

// 함수 호출
			$result = sendAligoSms($sendData);
        }
        $a[4] = $con_phone1;
    }
} catch (PDOException $e) {
    error_log("SMS 발송 오류: " . $e->getMessage());
    $message = '문자 발송 실패: ' . $e->getMessage();
}