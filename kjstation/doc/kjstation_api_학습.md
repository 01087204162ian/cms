# kjstation API 스키마/테이블 학습 노트

## 개요
- 환경: PHP 4.4.x + MySQL 4.x + EUC-KR (응답은 종종 UTF-8로 iconv)
- 공통 유틸: `dbcon.php`(mysql_connect), `json4version.php`(EUC-KRUTF-8 변환 + JSON), `JSON.php`(PEAR Services_JSON), `cors.php`(모든 Origin 허용)
- 저장 엔진/문자셋: 대부분 `ENGINE=MyISAM DEFAULT CHARSET=utf8` 이지만 실데이터는 EUC-KR 입출력. 마이그레이션 시 utf8mb4 + InnoDB로 전환 필요.
- 보안 현황: 모든 쿼리 mysql_* 직접 조립, 바인딩 없음  SQLi 위험. 개인정보(Jumin 등) 평문 컬럼 다수, 일부 Hash 컬럼 존재.

---

## 주요 테이블 의미

### 2012DaeriCompany (대리점/단체 기본 정보)
- 회사/대표자: `company`, `Pname`, `jumin`, `hphone`, `cphone`, `fax`
- 사업자/라이선스: `cNumber`, `lNumber`, `divi`
- 주소: `postNum`, `address1`, `address2`
- 계약 메타: `FirstStartDay`, `FirstStart`, `damdanga`(담당자 ID), `MemberNum`, `pBankNum`
- 기타: `union_`(조합 여부 추정), `notJumin`(주민 미보유 플래그)

### 2012Costomer (대리점 소속 고객/사용자 계정)
- 계정: `mem_id`, `passwd`
- 신원: `name`, `jumin1`, `jumin2`, `hphone`, `email`
- 소속/상태: `2012DaeriCompanyNum`, `level`, `permit`, `readIs`, `kind`, `user`
- 보안키: `pAssKey`, `pAssKeyoPen`
- 이력: `wdate`, `inclu`

### 2012Member (이전 회원 스키마)
- 계정/신원: `mem_id`, `passwd`, `name`, `jumin1`, `jumin2`, `hphone`, `email`
- 상태: `level`, `mail_check`, `kind`, `inclu`
- 보안키: `pAssKey`, `pAssKeyoPen`
- 이력: `wdate`
- 대리점 FK 없음(더 옛 버전)

### DaeriMember (단체 피보험자 명단)
- 관계: `2012DaeriCompanyNum`, `CertiTableNum`, `InsuranceCompany`, `EndorsePnum`
- 식별: `Name`, `Jumin`(원본), `JuminHash`(해시), `Hphone`, `nai`
- 상태/진행: `progress`, `state`, `cancel`, `sangtae`, `ch`, `changeCom`, `sago`
- 일정/증권: `FirstStart`, `endorse_day`, `InputDay`, `OutPutDay`
- 보험사별: `dongbuCerti`, `dongbuSelNumber`, `dongbusigi`, `dongbujeongi`
- 금액/보장: `sPrem`, `preminum1`, `a6b/a7b/a8b`, `rate`
- 메모/담당: `reasion`, `manager`

### 2012EndorseList (배서/특약 리스트)
- 연결: `2012DaeriCompanyNum`, `InsuranceCompany`, `CertiTableNum`, `policyNum`
- 카운트: `e_count`, `e_count_2`
- 상태: `sangtae`, `ch`
- 일정/식별: `wdate`, `endorse_day`, `enNum`, `userid`

### 2019rate (증권별 요율)
- 키: `policy`(증권/계약 번호), `jumin`(식별), `rate`(요율 코드)

### SMSData (문자 발송 로그)
- 발신/수신: `SendId/SendName`, `Rphone1~3`, `RecvName`, `Sphone1~3`
- 내용/링크: `Msg`, `Url`
- 예약/결과: `Rdate/Rtime`, `Result`, `Kind`, `ErrCode`, `Retry1/2`, `LastTime`
- 연관: `company`, `ssang_c_num`, `endorse_num`, `2012DaeriMemberNum`, `2012DaeriCompanyNum`, `policyNum`
- 금액/정산: `preminum`, `preminum2`, `c_preminum`, `jeongsan`, `joong`
- 기타: `juso`, `qboard`, `dagun`, `manager`

### kj_premium_data (월납/10회납 보험료 테이블)
- 키: `cNum`(계약/증권), `rowNum`
- 기간: `start_month`, `end_month`
- 월납: `monthly_premium1/2/total`
- 10회납: `payment10_premium1/2/total`
- 이력: `created_at`, `updated_at`

### kj_insurance_premium_data (10회납 보험료 단순형)
- 키: `policyNum`, `rowNum`
- 10회납 금액: `payment10_premium1/2/total`
- 기간/이력: `start_month`, `end_month`, `created_at`, `updated_at`

### Certi / CertiTable (증권/상품 마스터)
- 상세 필드는 별도 파일에 있으나, 여러 FK에서 `CertiTableNum` 사용  보험 상품/보장 테이블로 추정.

### ssang_c_memo
- 상담/메모 기록 테이블 (CREATE TABLE 정의 파일에서 필드 확인 필요)

---

## 테이블 관계 개략
- `2012DaeriCompany` 1:N `DaeriMember` (피보험자)
- `2012DaeriCompany` 1:N `2012Costomer` (고객 계정)
- `DaeriMember` N:1 `2012CertiTable` / `policyNum` / `EndorsePnum`
- `2012DaeriCompany` 1:N `2012EndorseList`
- `SMSData`  `2012DaeriMemberNum` / `2012DaeriCompanyNum` / `policyNum`
- 보험료 마스터: `kj_premium_data`, `kj_insurance_premium_data`  `cNum`/`policyNum`
- 요율: `2019rate`  `policy`

---

## 마이그레이션 체크포인트 (MariaDB 10.x + PHP 8.2 + UTF-8)
1) 엔진/문자셋: MyISAMInnoDB, utf8mb4 통일, Collation 재설정
2) PK/UK/FK: 숫자 시작 컬럼명  새 이름/백틱, FK 명시적 추가
3) 날짜 기본값: `0000-00-00` 제거, `NULL`/유효 기본값, `sql_mode` 대응
4) 개인정보: 주민/전화/이메일 암호화마스킹, Hash 재설계, 키 관리
5) SQL 보안: mysql_* 제거, PDO/Prepared, 입력 검증
6) 코드 정리: 커스텀 JSON 제거, iconv 축소(UTF-8 일원화)
7) 인덱스: TEXT/VARCHAR prefix, 조회 패턴 맞춰 재설계
8) 데이터 이행: EUC-KRUTF-8 재인코딩, 덤프 시 문자셋 주의

---

## 다음에 볼 파일 (우선)
- `dbCerti.php`, `dbCertitable.php` (상품/보장 정의)
- `dbBack.php`, `dbSsang_c_memo.php` (백업/메모)
- `kjDaeri/php/*` (암복호화, 보험료 계산, SMS 연동)
- `RestApi/daeriMember.php` (REST 신규 버전)



---

## 추가 테이블/유틸 요약 (추가 학습)

### 2012Certi (증권/증서 마스터)
- 계약/증권 기본: `certi`(번호), `company`(회사), `name`, `jumin`, `insurance`(보험사 코드), `sigi`(시기), `nab`(납입구분)
- 인증/접속: `cord`, `cordPass`, `cordCerti`
- 연락: `phone`, `fax`
- 요율/보험료 필드 다수: `yearRate`, `harinRate`, `preminun25/44/49/50/60/61/66`, `maxInwon`
- 사용처: 상품/증권 템플릿으로 보이며 `CertiTableNum` FK와 연결되는 사례 다수

### 2012CertiTable (상품/보장 테이블, 가장 중요)
- FK: `2012DaeriCompanyNum`, `policyNum`, `InsuraneCompany`
- 계약/일정: `startyDay`, `FirstStart`, `FirstStartDay`
- 납입/구분: `nabang`, `nabang_1`, `divi`, `state`, `gita`
- 가상계좌: `vbank`, `v_number`
- 상품 설명: `content`
- 보험료 슬롯: `preminum1~10`, `preminumE1~E10` (특약/연령별로 추정)
- 연납 슬롯: `yearP1~P6`, `yearPE1~PE6`
- 요율/구분: `moValue`, `moNum`, `moRate`, `jagi`, `control`, `personal`
- 인덱스: `2012DaeriCompanyNum`, `policyNum`

### ssang_c_memo (메모)
- `c_number`(계약/증권 번호), `memo`(텍스트), `memokind`, `wdate`, `userid`
- 메모 텍스트는 EUC-KR  UTF-8 변환 주의

### 2012DaeriMember (백업/이전 스키마)
- 현행 `DaeriMember`와 거의 동일 구조, 인덱스만 다소 단순 (`progress` 없음, `JuminHash` 없음)

### 암복호화 유틸 (kjDaeri/php)
- `encryption.php`: mcrypt(RIJNDAEL-256, CBC, 랜덤 IV) 기반 AES 유사 암호화, KEY 상수 `ENCRYPT_KEY` 필요. PHP 8.2에서 mcrypt 제거  openssl로 재구현 필요.
- `decrptJuminHphone.php`: `decryptData()`로 주민번호/휴대폰을 복호화하여 행에 다시 세팅.

### 보험료 계산 유틸 (premium_calculator.php)
- `calculateProRatedFee(...)`: 월납 보험료를 납입일/보험 시작일 기준 일할 계산, `personRate2` 적용, 만기일 계산.
- `calculateEndorsePremium(...)`: 배서 시 미경과보험료/경과보험료 계산(10회납, 연납 기준), 일할/차기분 공제 후 잔액 산출.

### REST API 시도 (RestApi/daeriMember.php)
- `2012DaeriMember`에서 `push='4'` 500건 조회 후 EUC-KR  UTF-8 변환해 JSON 반환.
- 주석: 외부 API 엔드포인트로 POST하려던 코드 있음(비활성화). 현재는 로컬 JSON만 출력.

---

## 마이그레이션 시 추가 고려
- `2012CertiTable`/`2012Certi`의 수많은 보험료 슬롯을 정규화(보장 항목 테이블)하는 방안 검토.
- mcrypt  openssl(AES-256-CBC)로 교체, IV 저장 방식 결정(Base64 + IV prefix 유지 가능).
- `ssang_c_memo` 등 TEXT 컬럼 EUC-KR 재인코딩 필요.
- `0000-00-00` 기본값 제거, InnoDB + FK 추가로 무결성 확보.
- `push`/`progress` 상태 코드 정의 문서화 필요.

