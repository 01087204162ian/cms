/** kjstation.kr  대리운전 배서**/
let isFetching = false;  // fetch 중복 실행 방지 변수
let isFetching2 = false;  // fetch 중복 실행 방지 변수

let fetchCallCount2 = 0;  // API 호출 횟수 추적 변수
function kj_endorse_search(){

	const pageContent = document.getElementById('page-content');
		pageContent.innerHTML = "";
		pageContent.innerHTML = '<tr><td colspan="14" class="loading">데이터 로드 중...</td></tr>';
        
		const fieldContents= `<div class="kje-list-container">
										<!-- 검색 영역 -->
										<div class="kje-list-header">
											<div class="kje-left-area">
												<div class="kje-search-area">
													<div class="select-container">
														<select id="pushSangtaeList" class="styled-select" onChange="f_kjEndorseloadTable()">
															<option value='-1'>선택</option>
															<option value='1'>청약</option>
															 <option value='4'>해지</option>
														 </select>
													</div>
													<div class="select-container">
														<select id="certiList" class="styled-select"> </select>
													</div>
													<div class="select-container">
														<select id="daeriCompanyList" class="styled-select"> </select>
													</div>
													
													<div id='currentSituation'></div>
												</div>
											</div>
											<div class="kje-right-area">
												<button class="kje-stats-button" onclick="EndorseCurrentSituation()">배서현황</button> 
												<button class="kje-stats-button" onclick="dailyEndorse_List('','','2')">일별배서리스트</button> 
												<button class="kje-stats-button" onclick="sms_List('','','2')">문자리스트</button>
											</div>
										</div>

										<!-- 리스트 영역 -->
										<div class="kje-list-content">
											<div class="kje-data-table-container">
												<table class="kje-data-table">
													<thead>
														<tr>
															<th class="col-num">No</th>
															<th class="col-business-num">담당자</th>
											                <th class="col-action">대리운전회사명</th>
															<th class="col-school">성명</th>
															<th class="col-students">주민번호</th>
															<th class="col-insurance">핸드폰</th>
															<th class="col-insurance2">진행단계</th>
															<th class="col-insurance2">manger</th>
															<th class="col-status">기준일</th>
															<th class="col-manager">신청일</th>
															<th class="col-date">증권번호</th>
															<th class="col-action">증권종류</th>
															<th class="col-phone">요율</th>
															<th class="col-contact">상태</th>
															<th class="col-policy">배서처리</th>
															<th class="col-memo">보험사</th>	
															<th class="col-manager">보험료</th>
															<th class="col-premium">c보험료</th>
															<th class="col-manager">중복여부</th>
														</tr>
													</thead>
													<tbody id="kje-applicationList">
														<!-- 데이터가 여기에 동적으로 로드됨 -->
													</tbody>
												</table>
											</div>
										</div>

										
										<!-- 페이지네이션 -->
										<div class="kje-pagination"></div>
									</div>
										
									`
						 pageContent.innerHTML= fieldContents;
						// 날짜 기본값 설정 (이제 시기가 DOM에 추가된 후 실행됨)
						//setDefaultDate();
						// 테이블 데이터 로드

						// 약간의 지연 후 한 번만 호출
						setTimeout(() => {
							kjEndorseloadSearchTable();  // 증권번호

						}, 100);
						
						//kjEndorseloadSearchTable();


}

async function EndorseCurrentSituation(){


 // 오늘 날짜 가져오기
	const today = new Date();
	const todayStr = today.getFullYear() + '-' + String(today.getMonth() + 1).padStart(2, '0') + '-' + String(today.getDate()).padStart(2, '0');

	// 1개월 전 첫날 계산하기
  const prevMonth = new Date(today);
  prevMonth.setMonth(today.getMonth() - 1);
  prevMonth.setDate(1); // 첫날로 설정
  const prevMonthFirstDayStr = prevMonth.getFullYear() + '-' + String(prevMonth.getMonth() + 1).padStart(2, '0') + '-' + String(prevMonth.getDate()).padStart(2, '0');
   const modal = document.getElementById("kj-endorseSituation-modal");
   modal.style.display = "block";
  // 날짜 선택 필드
  searchField2 = `
	<div class="kje-list-header">
		<div class="kje-left-area">
			<div class="kje-search-area">
			    <input type='date' id='fromDate' class='date-range-field' value='${prevMonthFirstDayStr}' onchange='dailyEndorseRequest("",this.value,"","",1)'>
	            <input type='date' id='toDate' class='date-range-field' value='${todayStr}' onchange='dailyEndorseRequest("",this.value,"","",1)'>
				
				<button class="sms-stats-button" onclick="requestDailySearch()">검색</button>
			  <div id='daily_currentSituation'></div>
		  </div>
	  </div>
	 <div class="kje-right-area">
		
	 </div>
  </div>
	     
  `;

  // 검색 영역 HTML 내용 삽입
  document.getElementById("endorseSituation_daeriCompany").innerHTML = searchField2;
	showLoading();
  
  // FormData 객체 생성
  const formData = new FormData();
 formData.append('fromDate', prevMonthFirstDayStr);
 formData.append('toDate',todayStr);
  
  try {
    // API 요청 전송
    const response = await fetch(`https://kjstation.kr/api/kjDaeri/EndorseCurrentSituation.php`, {
      method: "POST",
      body: formData,
    });
    
    // 응답 상태 확인
    if (!response.ok) {
      throw new Error(`서버 응답 오류: ${response.status}`);
    }
    
    // JSON 응답 파싱
    const result = await response.json();
    console.log('result', result);
    
    // 응답 데이터 처리
    processCurrentSituation(result);
    
    // 모달 표시
   
    
  } catch (error) {
    console.error('데이터 조회 오류:', error);
    alert('데이터 조회 중 오류가 발생했습니다: ' + error.message);
  } finally {
    // 로딩 표시 제거
    hideLoading();
  }
    

}


function processCurrentSituation(data) {
    // 현재 월과 이전 월 구분
    const today = new Date();
    const currentMonth = today.getMonth() + 1;
    const prevMonth = currentMonth === 1 ? 12 : currentMonth - 1;
    const currentYear = today.getFullYear();
    const prevYear = prevMonth === 12 ? currentYear - 1 : currentYear;
    
    // 월별 데이터 분리
    const prevMonthData = [];
    const currentMonthData = [];
    
    data.data.forEach(item => {
        const itemDate = new Date(item.date);
        const itemMonth = itemDate.getMonth() + 1;
        const itemYear = itemDate.getFullYear();
        
        if ((itemYear === prevYear && itemMonth === prevMonth)) {
            prevMonthData.push(item);
        } else if ((itemYear === currentYear && itemMonth === currentMonth)) {
            currentMonthData.push(item);
        }
    });
    
    // 요일별 데이터 정리 (월~일: 1~7)
    const weekdayNames = ['월', '화', '수', '목', '금', '토', '일'];
    const prevMonthByWeekday = {};
    const currentMonthByWeekday = {};
    
    // 초기화
    weekdayNames.forEach((name, index) => {
        const weekdayIndex = index + 1;
        prevMonthByWeekday[weekdayIndex] = {
            name: name,
            subscription: 0,
            termination: 0,
            subscriptionReject: 0,
            subscriptionCancel: 0,
            terminationCancel: 0,
            total: 0,
            count: 0
        };
        
        currentMonthByWeekday[weekdayIndex] = {
            name: name,
            subscription: 0,
            termination: 0,
            subscriptionReject: 0,
            subscriptionCancel: 0,
            terminationCancel: 0,
            total: 0,
            count: 0
        };
    });
    
    // 이전 달 데이터 요일별 집계
    prevMonthData.forEach(item => {
        const date = new Date(item.date);
        // getDay()는 0(일)~6(토)을 반환하므로 요일 계산을 1(월)~7(일)로 변환
        let weekday = date.getDay();
        weekday = weekday === 0 ? 7 : weekday; // 일요일은 7로 변환
        
        prevMonthByWeekday[weekday].subscription += (item.subscription || 0);
        prevMonthByWeekday[weekday].termination += (item.termination || 0);
        prevMonthByWeekday[weekday].subscriptionReject += (item.subscriptionReject || 0);
        prevMonthByWeekday[weekday].subscriptionCancel += (item.subscriptionCancel || 0);
        prevMonthByWeekday[weekday].terminationCancel += (item.terminationCancel || 0);
        prevMonthByWeekday[weekday].total += (item.total || 0);
        prevMonthByWeekday[weekday].count++;
    });
    
    // 현재 달 데이터 요일별 집계
    currentMonthData.forEach(item => {
        const date = new Date(item.date);
        let weekday = date.getDay();
        weekday = weekday === 0 ? 7 : weekday;
        
        currentMonthByWeekday[weekday].subscription += (item.subscription || 0);
        currentMonthByWeekday[weekday].termination += (item.termination || 0);
        currentMonthByWeekday[weekday].subscriptionReject += (item.subscriptionReject || 0);
        currentMonthByWeekday[weekday].subscriptionCancel += (item.subscriptionCancel || 0);
        currentMonthByWeekday[weekday].terminationCancel += (item.terminationCancel || 0);
        currentMonthByWeekday[weekday].total += (item.total || 0);
        currentMonthByWeekday[weekday].count++;
    });
    
    // 평균 계산 (요일별로 해당 요일이 몇 번 나왔는지로 나눔)
    for (let i = 1; i <= 7; i++) {
        if (prevMonthByWeekday[i].count > 0) {
            prevMonthByWeekday[i].subscription = Math.round(prevMonthByWeekday[i].subscription / prevMonthByWeekday[i].count);
            prevMonthByWeekday[i].termination = Math.round(prevMonthByWeekday[i].termination / prevMonthByWeekday[i].count);
            prevMonthByWeekday[i].subscriptionReject = Math.round(prevMonthByWeekday[i].subscriptionReject / prevMonthByWeekday[i].count);
            prevMonthByWeekday[i].subscriptionCancel = Math.round(prevMonthByWeekday[i].subscriptionCancel / prevMonthByWeekday[i].count);
            prevMonthByWeekday[i].terminationCancel = Math.round(prevMonthByWeekday[i].terminationCancel / prevMonthByWeekday[i].count);
            prevMonthByWeekday[i].total = Math.round(prevMonthByWeekday[i].total / prevMonthByWeekday[i].count);
        }
        
        if (currentMonthByWeekday[i].count > 0) {
            currentMonthByWeekday[i].subscription = Math.round(currentMonthByWeekday[i].subscription / currentMonthByWeekday[i].count);
            currentMonthByWeekday[i].termination = Math.round(currentMonthByWeekday[i].termination / currentMonthByWeekday[i].count);
            currentMonthByWeekday[i].subscriptionReject = Math.round(currentMonthByWeekday[i].subscriptionReject / currentMonthByWeekday[i].count);
            currentMonthByWeekday[i].subscriptionCancel = Math.round(currentMonthByWeekday[i].subscriptionCancel / currentMonthByWeekday[i].count);
            currentMonthByWeekday[i].terminationCancel = Math.round(currentMonthByWeekday[i].terminationCancel / currentMonthByWeekday[i].count);
            currentMonthByWeekday[i].total = Math.round(currentMonthByWeekday[i].total / currentMonthByWeekday[i].count);
        }
    }
    
    // 테이블 1: 요일별 평균 데이터
    let d_list = '';
    d_list = ` <table class='sjTable'>
                <thead>
                    <tr>
                        <th colspan="7">${prevYear}년 ${prevMonth}월 요일별 평균</th>
                        <th colspan="7">${currentYear}년 ${currentMonth}월 요일별 평균</th>
                    </tr>
                    <tr>
                        <th>요일</th>
                        <th>정상</th>
                        <th>해지</th>
                        <th>청약거절</th>
                        <th>청약취소</th>
                        <th>해지취소</th>
                        <th>소계</th>
                        <th>요일</th>
                        <th>정상</th>
                        <th>해지</th>
                        <th>청약거절</th>
                        <th>청약취소</th>
                        <th>해지취소</th>
                        <th>소계</th>
                    </tr>
                </thead>`;
    
    d_list += `<tbody>`;
    
    // 월요일부터 일요일까지 순서대로 데이터 표시
    for (let i = 1; i <= 7; i++) {
        d_list += `
            <tr>
                <td>${prevMonthByWeekday[i].name}</td>
                <td>${prevMonthByWeekday[i].subscription}</td>
                <td>${prevMonthByWeekday[i].termination}</td>
                <td>${prevMonthByWeekday[i].subscriptionReject}</td>
                <td>${prevMonthByWeekday[i].subscriptionCancel}</td>
                <td>${prevMonthByWeekday[i].terminationCancel}</td>
                <td>${prevMonthByWeekday[i].total}</td>
                <td>${currentMonthByWeekday[i].name}</td>
                <td>${currentMonthByWeekday[i].subscription}</td>
                <td>${currentMonthByWeekday[i].termination}</td>
                <td>${currentMonthByWeekday[i].subscriptionReject}</td>
                <td>${currentMonthByWeekday[i].subscriptionCancel}</td>
                <td>${currentMonthByWeekday[i].terminationCancel}</td>
                <td>${currentMonthByWeekday[i].total}</td>
            </tr>`;
    }
    
    // 월별 총 평균 계산
    const prevMonthAvg = {
        subscription: 0,
        termination: 0,
        subscriptionReject: 0,
        subscriptionCancel: 0,
        terminationCancel: 0,
        total: 0
    };
    
    const currentMonthAvg = {
        subscription: 0,
        termination: 0,
        subscriptionReject: 0,
        subscriptionCancel: 0,
        terminationCancel: 0,
        total: 0
    };
    
    // 이전 달 평균 계산
    if (prevMonthData.length > 0) {
        prevMonthData.forEach(item => {
            prevMonthAvg.subscription += (item.subscription || 0);
            prevMonthAvg.termination += (item.termination || 0);
            prevMonthAvg.subscriptionReject += (item.subscriptionReject || 0);
            prevMonthAvg.subscriptionCancel += (item.subscriptionCancel || 0);
            prevMonthAvg.terminationCancel += (item.terminationCancel || 0);
            prevMonthAvg.total += (item.total || 0);
        });
        
        prevMonthAvg.subscription = Math.round(prevMonthAvg.subscription / prevMonthData.length);
        prevMonthAvg.termination = Math.round(prevMonthAvg.termination / prevMonthData.length);
        prevMonthAvg.subscriptionReject = Math.round(prevMonthAvg.subscriptionReject / prevMonthData.length);
        prevMonthAvg.subscriptionCancel = Math.round(prevMonthAvg.subscriptionCancel / prevMonthData.length);
        prevMonthAvg.terminationCancel = Math.round(prevMonthAvg.terminationCancel / prevMonthData.length);
        prevMonthAvg.total = Math.round(prevMonthAvg.total / prevMonthData.length);
    }
    
    // 현재 달 평균 계산
    if (currentMonthData.length > 0) {
        currentMonthData.forEach(item => {
            currentMonthAvg.subscription += (item.subscription || 0);
            currentMonthAvg.termination += (item.termination || 0);
            currentMonthAvg.subscriptionReject += (item.subscriptionReject || 0);
            currentMonthAvg.subscriptionCancel += (item.subscriptionCancel || 0);
            currentMonthAvg.terminationCancel += (item.terminationCancel || 0);
            currentMonthAvg.total += (item.total || 0);
        });
        
        currentMonthAvg.subscription = Math.round(currentMonthAvg.subscription / currentMonthData.length);
        currentMonthAvg.termination = Math.round(currentMonthAvg.termination / currentMonthData.length);
        currentMonthAvg.subscriptionReject = Math.round(currentMonthAvg.subscriptionReject / currentMonthData.length);
        currentMonthAvg.subscriptionCancel = Math.round(currentMonthAvg.subscriptionCancel / currentMonthData.length);
        currentMonthAvg.terminationCancel = Math.round(currentMonthAvg.terminationCancel / currentMonthData.length);
        currentMonthAvg.total = Math.round(currentMonthAvg.total / currentMonthData.length);
    }
    
    // 월별 총 평균 행 추가
    d_list += `
        <tr class="total-row">
            <td><strong>월평균</strong></td>
            <td><strong>${prevMonthAvg.subscription}</strong></td>
            <td><strong>${prevMonthAvg.termination}</strong></td>
            <td><strong>${prevMonthAvg.subscriptionReject}</strong></td>
            <td><strong>${prevMonthAvg.subscriptionCancel}</strong></td>
            <td><strong>${prevMonthAvg.terminationCancel}</strong></td>
            <td><strong>${prevMonthAvg.total}</strong></td>
            <td><strong>월평균</strong></td>
            <td><strong>${currentMonthAvg.subscription}</strong></td>
            <td><strong>${currentMonthAvg.termination}</strong></td>
            <td><strong>${currentMonthAvg.subscriptionReject}</strong></td>
            <td><strong>${currentMonthAvg.subscriptionCancel}</strong></td>
            <td><strong>${currentMonthAvg.terminationCancel}</strong></td>
            <td><strong>${currentMonthAvg.total}</strong></td>
        </tr>`;
    
    d_list += `</tbody>
              </table>`;
    
    // 테이블 2: 원본 데이터를 두 열로 표시
    d_list += `<br><h3>일별 상세 데이터</h3>`;
    d_list += `<table class='sjTable'>
                <thead>
                    <tr>
                        <th>날짜</th>
                        <th>정상</th>
                        <th>해지</th>
                        <th>청약거절</th>
                        <th>청약취소</th>
                        <th>해지취소</th>
                        <th>소계</th>
                        <th>날짜</th>
                        <th>정상</th>
                        <th>해지</th>
                        <th>청약거절</th>
                        <th>청약취소</th>
                        <th>해지취소</th>
                        <th>소계</th>
                    </tr>
                </thead>`;
    
    d_list += `<tbody>`;
    
    // 요일 이름 배열
    const weekdays = ['일', '월', '화', '수', '목', '금', '토'];
    
    // 데이터를 두 열로 표시하기 위한 처리
    const halfLength = Math.ceil(data.data.length / 2);
    
    for (let i = 0; i < halfLength; i++) {
        const firstItem = data.data[i];
        const secondItem = (i + halfLength < data.data.length) ? data.data[i + halfLength] : { 
            date: "", 
            subscription: 0, 
            subscriptionReject: 0, 
            subscriptionCancel: 0,
            termination: 0,
            terminationCancel: 0,
            total: 0
        };
        
        // 첫 번째 항목의 요일 계산
        let firstDayOfWeek = "";
        if (firstItem.date) {
            const firstDate = new Date(firstItem.date);
            firstDayOfWeek = weekdays[firstDate.getDay()];
        }
        
        // 두 번째 항목의 요일 계산
        let secondDayOfWeek = "";
        if (secondItem.date) {
            const secondDate = new Date(secondItem.date);
            secondDayOfWeek = weekdays[secondDate.getDay()];
        }
        
        d_list += `
            <tr>
                <td>${firstItem.date ? `${firstItem.date}(${firstDayOfWeek})` : ""}</td>
                <td>${firstItem.subscription || 0}</td>
                <td>${firstItem.termination || 0}</td>
                <td>${firstItem.subscriptionReject || 0}</td>
                <td>${firstItem.subscriptionCancel || 0}</td>
                <td>${firstItem.terminationCancel || 0}</td>
                <td>${firstItem.total || 0}</td>
                <td>${secondItem.date ? `${secondItem.date}(${secondDayOfWeek})` : ""}</td>
                <td>${secondItem.subscription || 0}</td>
                <td>${secondItem.termination || 0}</td>
                <td>${secondItem.subscriptionReject || 0}</td>
                <td>${secondItem.subscriptionCancel || 0}</td>
                <td>${secondItem.terminationCancel || 0}</td>
                <td>${secondItem.total || 0}</td>
            </tr>`;
    }
    
    d_list += `</tbody>
              </table>`;
    
    document.getElementById("m_endorseSituation").innerHTML = d_list;
}
// 일자별 배서 리스트

async function dailyEndorse_List() {
  // 오늘 날짜 계산
  const today = new Date();
  const todayStr = today.getFullYear() + '-' + String(today.getMonth() + 1).padStart(2, '0') + '-' +String(today.getDate()).padStart(2, '0');

  // 날짜 선택 필드
  searchField = `
	<div class="kje-list-header">
		<div class="kje-left-area">
			<div class="kje-search-area">
			    <input type='date' id='dailyDate' class='date-range-field' value='${todayStr}' onchange='dailyEndorseRequest("",this.value,"","",1)'>
				<div class="select-container">
					<select id="daily_endorse_dNumList" class="styled-select"> </select>
				</div>
				<div class="select-container">
					<select id="daily_endorse_certiList" class="styled-select"> </select>
				</div>
				<button class="sms-stats-button" onclick="requestDailySearch()">검색</button>
			  <div id='daily_currentSituation'></div>
		  </div>
	  </div>
	 <div class="kje-right-area">
		<button class="kje-stats-button" onclick="dailyCheck()">검토</button> 
	 </div>
  </div>
	     
  `;

  // 검색 영역 HTML 내용 삽입
  document.getElementById("dailyEndose_daeriCompany").innerHTML = searchField;
  
  // 모달 표시
  document.getElementById("kj-dailyEndose-modal").style.display = "block";
  
 
	setTimeout(() => {
		todayEndorsedNumloadSearchTable(`${todayStr}`);  // 대리운전회사별 
		todayEndorseloadSearchTable(`${todayStr}`,'','',1);  // 일자별증권번호
		
	}, 100);


  // CSS 스타일 추가 (한 번만 추가되도록)
  if (!document.getElementById('loading-spinner-style')) {
    const style = document.createElement('style');
    style.id = 'loading-spinner-style';
    style.textContent = `
      .loading-spinner {
        display: flex;
        flex-direction: column;
        align-items: center;
        justify-content: center;
        width: 100%;
      }
      .spinner {
        border: 4px solid rgba(0, 0, 0, 0.1);
        width: 36px;
        height: 36px;
        border-radius: 50%;
        border-left-color: #8E6C9D;;
        animation: spin 1s linear infinite;
        margin-bottom: 10px;
      }
      @keyframes spin {
        0% { transform: rotate(0deg); }
        100% { transform: rotate(360deg); }
      }
      .pagination {
        display: flex;
        justify-content: center;
        margin-top: 20px;
        gap: 5px;
      }
      .pagination button {
        padding: 5px 10px;
        border: 1px solid #ddd;
        background-color: #f8f8f8;
        cursor: pointer;
        border-radius: 3px;
      }
      .pagination button.active {
        background-color: #8E6C9D;;
        color: white;
        border-color: #8E6C9D;;
      }
      .pagination button:hover:not(.active) {
        background-color: #e9e9e9;
      }
      .pagination button:disabled {
        opacity: 0.5;
        cursor: not-allowed;
      }
    `;
    document.head.appendChild(style);
  }
  
  // 초기 데이터 로드
  await dailyEndorseRequest("","","","",1);
}

//일자별 배서 증권 찾기 
function todayEndorseloadSearchTable(endorseDay,dNum,policyNum,sort){

	
  
  const currentSituation = document.getElementById('daily_currentSituation');
  currentSituation.innerHTML='';
  
  // 실행 상태로 변경
  isFetching = true;
 
  // API 요청
  fetch(`https://kjstation.kr/api/kjDaeri/todayEendorseCertiSearch.php?endorseDay=${endorseDay}&dNum=${dNum}&policyNum=${policyNum}&sort=${sort}`)
    .then(response => {
      if (!response.ok) {
        throw new Error(`HTTP error! Status: ${response.status}`);
      }
      return response.json();
    })
    .then(data => {
      // 성공적으로 데이터를 받았을 때 드롭다운 채우기
      todayPopulateCertiList(data);
      
    })
    .catch((error) => {
      console.error("Error details:", error);
      alert("데이터를 불러오는 중 오류가 발생했습니다.");
      
    })
    .finally(() => {
      // 실행 완료 후 상태 변경
      isFetching = false;
    });
}
// 로딩바 표시 함수
async function dailyCheck() {
  console.log('체크');
  const dailyDate = document.getElementById('dailyDate').value;
  const dNumElement = document.getElementById('daily_endorse_dNumList');
  const dNum = dNumElement.value;
  
  if (!dNum) {
    alert('대리운전회사 선택부터 하세요!!');
    dNumElement.focus();
    return;
  }
  
  // 로딩 표시
  showLoading();
  
  // FormData 객체 생성
  const formData = new FormData();
  formData.append('todayStr', dailyDate);
  formData.append('dNum', dNum);
  
  try {
    // API 요청 전송
    const response = await fetch(`https://kjstation.kr/api/kjDaeri/dailyEndorseSearch2.php`, {
      method: "POST",
      body: formData,
    });
    
    // 응답 상태 확인
    if (!response.ok) {
      throw new Error(`서버 응답 오류: ${response.status}`);
    }
    
    // JSON 응답 파싱
    const result = await response.json();
    console.log('result', result);
    
    // 응답 데이터 처리
    processEndorseData(result, dailyDate);
    
    // 모달 표시
    const modal = document.getElementById("kj-endorseCheck-modal");
	modal.style.display = "block";
    
  } catch (error) {
    console.error('데이터 조회 오류:', error);
    alert('데이터 조회 중 오류가 발생했습니다: ' + error.message);
  } finally {
    // 로딩 표시 제거
    hideLoading();
  }
}

// 데이터 처리 및 보고서 생성 함수
function processEndorseData(result, dateStr) {
  if (!result.success || !result.data || result.data.length === 0) {
    const reportElement = document.getElementById("m_endorseCheck");
    if (reportElement) {
      reportElement.innerHTML = "조회된 데이터가 없습니다.";
    }
    return;
  }
  
  // 회사명 표시
  if (result.data[0] && result.data[0].company) {
    document.getElementById("endorseCheck_daeriCompany").innerHTML = result.data[0].company;
  }
  
  // 데이터 분류
  const daeriRegistrations = []; // 대리 가입자
  const daeriTerminations = []; // 대리 해지자
  const taksongRegistrations = []; // 탁송 가입자
  const taksongTerminations = []; // 탁송 해지자
  
  // 보험료 합계 계산
  let daeriRegPremium = 0;
  let daeriTermPremium = 0;
  let taksongRegPremium = 0;
  let taksongTermPremium = 0;
  
  // 할증자 카운트
  let haldungCount = 0;
  
  // 데이터 분류 처리
  result.data.forEach(item => {
	  console.log(item);
    // 보험료 숫자로 변환 - 수정: c_preminum 값을 우선 사용
    const premium = parseInt(item.c_preminum || item.preminum || 0);
    
    // etag 값으로 대리/탁송 구분 (1,3 = 대리, 그 외 = 탁송)
    const isDaeri = (item.etag === "1" || item.etag === "3");
    const isTaksong = !isDaeri;
    
    // push 값으로 가입/해지 구분 (4 = 가입, 2 = 해지)
    const isRegistration = (item.push === "4");
    const isTermination = (item.push === "2");
    
    if (isDaeri && isRegistration) {
      daeriRegistrations.push(item);
      daeriRegPremium += premium; // 가입은 +
    } else if (isDaeri && isTermination) {
      daeriTerminations.push(item);
      daeriTermPremium -= premium; // 해지는 -
    } else if (isTaksong && isRegistration) {
      taksongRegistrations.push(item);
      taksongRegPremium += premium; // 가입은 +
    } else if (isTaksong && isTermination) {
      taksongTerminations.push(item);
      taksongTermPremium -= premium; // 해지는 -
    }
    
    // 할증자 체크 (rate > 1 인 경우)
    if (isRegistration && item.rate && parseInt(item.rate) > 1) {
      haldungCount++;
    }
  });
  
  // 숫자를 통화 형식으로 변환하는 함수
  function formatCurrency(number) {
    // 양수/음수 기호를 제거하고 숫자만 포맷팅
    return new Intl.NumberFormat('ko-KR').format(Math.abs(number));
  }
  
  // 보고서 텍스트 생성
  const reportDate = new Date(dateStr || result.todayStr);
  const formattedDate = `${reportDate.getFullYear()}.${String(reportDate.getMonth() + 1).padStart(2, '0')}.${String(reportDate.getDate()).padStart(2, '0')}`;
  const dayOfWeek = ['일', '월', '화', '수', '목', '금', '토'][reportDate.getDay()];
  
  // 총 보험료 계산
  const daeriTotal = daeriRegPremium + daeriTermPremium;
  const taksongTotal = taksongRegPremium + taksongTermPremium;
  const overallTotal = daeriTotal + taksongTotal;
  
  let reportHTML = `<div class="report-container">
    <h3>${formattedDate} (${dayOfWeek}) 배서현황</h3>
    <div class="report-section">
      <h4>*대리 가입자</h4>
      <ul>`;
  
  // 대리 가입자 목록
  daeriRegistrations.forEach(item => {
    reportHTML += `<li>${item.name}</li>`;
  });
  
  reportHTML += `</ul>
      <p>총 ${daeriRegistrations.length}명</p>
    </div>
    
    <div class="report-section">
      <h4>*대리 해지자</h4>
      <ul>`;
      
  // 대리 해지자 목록
  daeriTerminations.forEach(item => {
    reportHTML += `<li>${item.name}</li>`;
  });
      
  reportHTML += `</ul>
      <p>총 ${daeriTerminations.length}명</p>
    </div>
    
    <div class="report-section">
      <h4>*탁송 가입자</h4>
      <ul>`;
  
  // 탁송 가입자 목록
  taksongRegistrations.forEach(item => {
    reportHTML += `<li>${item.name}</li>`;
  });
  
  reportHTML += `</ul>
      <p>총 ${taksongRegistrations.length}명</p>
    </div>
    
    <div class="report-section">
      <h4>*탁송 해지자</h4>
      <ul>`;
      
  // 탁송 해지자 목록
  taksongTerminations.forEach(item => {
    reportHTML += `<li>${item.name}</li>`;
  });
      
  reportHTML += `</ul>
      <p>총 ${taksongTerminations.length}명</p>
    </div>
    
    <div class="report-section">
      <h4>영수보험료 (+추징/-환급)</h4>
      <p>대리: ${formatCurrency(daeriTotal)}원 ${daeriTotal >= 0 ? '추징' : '환급'}</p>
      <p>탁송: ${formatCurrency(taksongTotal)}원 ${taksongTotal >= 0 ? '추징' : '환급'}</p>
      <p>합계: ${formatCurrency(overallTotal)}원 ${overallTotal >= 0 ? '추징' : '환급'}</p>
    </div>
    
    <div class="report-section">
      <p>금일 가입자 중 할증자는 ${haldungCount} 명입니다.</p>
      <p>보험료 파일은 정리하여 메일로 발송하겠습니다.</p>
    </div>
  </div>`;
  
  // 보고서 결과 표시
  const endorseReport = document.getElementById("m_endorseCheck");
  if (endorseReport) {
    endorseReport.innerHTML = reportHTML;
  } else {
    console.error("m_endorseCheck 요소를 찾을 수 없습니다.");
  }
}

// 금액 형식화 함수 (1,000 단위 콤마)
function formatCurrency(amount) {
  return amount.toString().replace(/\B(?=(\d{3})+(?!\d))/g, ",");
}

// 로딩 표시/숨김 함수
function showLoading() {
  const loadingElement = document.getElementById('loading');
  if (loadingElement) {
    loadingElement.style.display = 'block';
  }
}

function hideLoading() {
  const loadingElement = document.getElementById('loading');
  if (loadingElement) {
    loadingElement.style.display = 'none';
  }
}



function todayPopulateCertiList(data) {
  const selectElement = document.getElementById('daily_endorse_certiList');
  
  // 요소가 존재하는지 확인
  if (!selectElement) {
    console.error("Element with ID 'daily_endorse_certiList' not found");
    return; // 요소가 없으면 함수 종료
  }
  
  // 기존 이벤트 리스너 제거 (중요!)
  const newSelectElement = selectElement.cloneNode(false);
  selectElement.parentNode.replaceChild(newSelectElement, selectElement);
  
  // 기본 옵션 추가
  const defaultOption = document.createElement('option');
  defaultOption.value = '';
  defaultOption.textContent = '--  증권 선택 --';
  newSelectElement.appendChild(defaultOption);
  
  const defaultOption2 = document.createElement('option');
  defaultOption2.value = '1';
  defaultOption2.textContent = '--  모든 증권 --';
  newSelectElement.appendChild(defaultOption2);

  // 각 항목을 옵션으로 추가
  data.data.forEach(item => {
    const periods = { "1": "흥국", "2": "DB", "3": "kb", "4": "현대", "5": "현대", "6": "롯데", "7": "MG", "8": "삼성" };
    const InsuranceCompany = periods[item.insuranceCom] || "알 수 없음";
    
    const option = document.createElement('option');
    option.value = item.policyNum;
    option.textContent = `${InsuranceCompany}[${item.policyNum}]`;
    newSelectElement.appendChild(option);
  });

  // 현재 상황 업데이트
  const currentSituation = document.getElementById('daily_currentSituation');

	let subscription = ""; 
	let subscriptionCancel = "";
	let subscriptionReject = "";
	let termination = "";
	let terminationCancel = "";
	let total = "";

	if(data.pushCounts.subscription){
		subscription = "청약:" + data.pushCounts.subscription;
	}
	if(data.pushCounts.subscriptionCancel){
		subscriptionCancel = "청약취소:" + data.pushCounts.subscriptionCancel;
	}
	if(data.pushCounts.subscriptionReject){
		subscriptionReject = "청약거절:" + data.pushCounts.subscriptionReject;
	}
	if(data.pushCounts.termination){
		termination = "해지:" + data.pushCounts.termination;
	}
	if(data.pushCounts.terminationCancel){
		terminationCancel = "해지취소:" + data.pushCounts.terminationCancel;
	}
	if(data.pushCounts.total){
		total = "계:" + data.pushCounts.total;
	}
  currentSituation.innerHTML = `
					${subscription}
					${subscriptionCancel}
					${subscriptionReject}
					${termination}
					${terminationCancel}
					${total}
				`;

  // change 이벤트 한 번만 추가
  newSelectElement.addEventListener('change', function() {
    const selectedValue = this.value;
    const todayStr = document.getElementById('dailyDate').value;
    const dNum = document.getElementById('daily_endorse_dNumList').value;
    
    // 항상 페이지 번호는 1로 시작
    if (selectedValue === '') {
      console.log('증권이 선택되지 않았습니다.');
    } else if (selectedValue === '1') {
      console.log('증권 전체가 선택되었습니다.');
      dailyEndorseRequest(1, todayStr, dNum, selectedValue, 3);
    } else {
      console.log('특정 증권이 선택되었습니다:', selectedValue);
      dailyEndorseRequest(1, todayStr, dNum, selectedValue, 3);
    }
  });
}


//일자별 배서 대리운전회사 찾기
function todayEndorsedNumloadSearchTable(endorseDay){

  // 실행 상태로 변경
  isFetching = true;
 
  // API 요청
  fetch(`https://kjstation.kr/api/kjDaeri/todayEendorseCompanySerarch.php?endorseDay=${endorseDay}`)
    .then(response => {
      if (!response.ok) {
        throw new Error(`HTTP error! Status: ${response.status}`);
      }
      return response.json();
    })
    .then(data => {
      // 성공적으로 데이터를 받았을 때 드롭다운 채우기
      todayPopulatedNumList(data);
      
    })
    .catch((error) => {
      console.error("Error details:", error);
      alert("데이터를 불러오는 중 오류가 발생했습니다.");
      
    })
    .finally(() => {
      // 실행 완료 후 상태 변경
      isFetching = false;
    });
}

//일별 
function todayPopulatedNumList(data) {
	console.log("data", data);
	const selectElement = document.getElementById('daily_endorse_dNumList');
  
	if (!selectElement) {
		console.error("Element with ID 'daily_endorse_dNumList' not found");
		return;
	}

	// 기존 옵션 제거
	selectElement.innerHTML = '';

	// 기본 옵션 추가
	const defaultOption = document.createElement('option');
	defaultOption.value = '';
	defaultOption.textContent = '-- 대리운전회사 선택 --';
	selectElement.appendChild(defaultOption);

	// 옵션 리스트 추가
	data.data.forEach(item => {
		const option = document.createElement('option');
		option.value = item.dNum;
		option.textContent = item.company;
		selectElement.appendChild(option);
	});

	// 이벤트 리스너 중복 방지를 위해 이전 리스너 제거
	selectElement.onchange = function () {
		const selectedValue = this.value;
		console.log('선택된 대리운전회사:', selectedValue);

		if (selectedValue === '') {
			console.log('대리운전회사가 선택되지 않았습니다.');
		} else {
			console.log('특정 대리운전회사가 선택되었습니다:', selectedValue);
			dailyEndorseRequest("", '', selectedValue, "", 2);
			const todayStr=document.getElementById('dailyDate').value
			todayEndorseloadSearchTable(todayStr,selectedValue,'',2); // 대리운전회사 증권 찾기  날자,대리운전회사
		}
	};
}

// 검색 요청 함수
function requestDailySearch() {
  // 선택된 날짜 가져오기
  const selectedDate = document.getElementById('dailyDate').value;
  
  if (!selectedDate) {
    alert('날짜를 선택해주세요.');
    return;
  }
  
  // 선택된 날짜로 데이터 요청
  dailyEndorseRequest(1, selectedDate);
}

// 일자별 배서 데이터 요청 함수
async function dailyEndorseRequest(page = 1, selectedDate = null,dNum,policyNum,sort) {

	console.log('selectedDate',selectedDate,"dNum",dNum,"policyNum",policyNum,"sort",sort)
  // 로딩 표시 추가
  const m_dailyEndoseElement = document.getElementById("m_dailyEndose");
  if (!m_dailyEndoseElement) {
    console.error("m_dailyEndose 요소를 찾을 수 없습니다.");
    return;
  }
  
  m_dailyEndoseElement.innerHTML = `
    <tr>
      <td colspan='15' style="text-align: center; padding: 20px;">
        <div class="loading-spinner">
          <div class="spinner"></div>
          <p>데이터를 불러오는 중입니다...</p>
        </div>
      </td>
    </tr>
  `;
  
  try {
    // FormData 객체 생성
	const today = new Date();
    const formData = new FormData();
    
    // 날짜 처리
    let selectedDay; let  todayIs;
    
    if (selectedDate) {
      // 선택된 날짜가 있으면 그 날짜만 사용
      selectedDay = selectedDate;
    } else {
      // 달력에서 선택한 날짜 가져오기
      const dailyDateElement = document.getElementById("dailyDate");
      
      if (dailyDateElement && dailyDateElement.value) {
        selectedDay = dailyDateElement.value;
      } else {
        // 입력 필드가 없거나 값이 없으면 오늘 날짜 사용
        //selectedDay = today.toISOString().split('T')[0]; 
		selectedDay=today.getFullYear() + '-' + String(today.getMonth() + 1).padStart(2, '0') + '-' + String(today.getDate()).padStart(2, '0'); //한극표준시
      }
    }
	// 오늘 날자가 아닌 배서리스트 조회하는 경우엔 대리운전회삽별,일자별증권번호를 조회한다
	 todayIs=today.getFullYear() + '-' + String(today.getMonth() + 1).padStart(2, '0') + '-' + String(today.getDate()).padStart(2, '0'); //한극표준시
	if(selectedDay!=todayIs && sort==1){
	   todayEndorsedNumloadSearchTable(`${selectedDay}`);  // 대리운전회사별 
	   todayEndorseloadSearchTable(`${selectedDay}`,'','',1);  // 일자별증권번호
	}
    // API 요청 파라미터 설정
    formData.append('todayStr', selectedDay);
    formData.append('page', page);
	formData.append('sort', sort); //1: 날자 조회하는 경우 , 2 날자와 dNum, 3 날자와 dNum (대리운전회사), policyNum 증권번호
	formData.append('dNum', dNum);
	formData.append('policyNum',policyNum);
    
    // API 요청 전송
    const response = await fetch(`https://kjstation.kr/api/kjDaeri/dailyEndorseSearch.php`, {
      method: "POST",
      body: formData,
    });
    
    // 응답 상태 확인
    if (!response.ok) {
      throw new Error(`서버 응답 오류: ${response.status}`);
    }
    
    // JSON 응답 파싱
    const result = await response.json();
    
    // 데이터 처리
    let m_smsList = '';
    
    if (result.success && result.data && result.data.length > 0) {
      console.log('데이터:', result);
      
      // 페이지네이션 설정
      const itemsPerPage = 20; // 페이지당 표시할 항목 수
      const totalItems = result.data.length;
      const totalPages = Math.ceil(totalItems / itemsPerPage);
      
      // 현재 페이지가 범위를 벗어나면 조정
      const currentPage = Math.max(1, Math.min(page, totalPages));
      
      // 현재 페이지에 표시할 항목 계산
      const startIndex = (currentPage - 1) * itemsPerPage;
      const endIndex = Math.min(startIndex + itemsPerPage, totalItems);
      const currentPageItems = result.data.slice(startIndex, endIndex);
      
      // 현재 페이지 데이터 행 추가
      currentPageItems.forEach((item, index) => {
        // 날짜 형식 변환 (YYYYMMDDHHMMSS -> YYYY-MM-DD HH:MM:SS)
        const lastTime = item.LastTime || '';
        let formattedDate = '';
        
        if (lastTime.length === 14) {
          formattedDate = `${lastTime.substring(0, 4)}-${lastTime.substring(4, 6)}-${lastTime.substring(6, 8)} ${lastTime.substring(8, 10)}:${lastTime.substring(10, 12)}:${lastTime.substring(12, 14)}`;
        } else {
          formattedDate = lastTime;
        }
         const formattedPreminum = item.preminum ? parseFloat(item.preminum).toLocaleString("en-US") : "0";
		 const formattedC_preminum = item.c_preminum ? parseFloat(item.c_preminum).toLocaleString("en-US") : "0";

		 const iType={"1":"대리","2":"탁송","3":"대리렌트","4":"탁송렌트"};
		 const certiType= iType[item.etag] || "알 수 없음";
		// 보험사
		 const periods = { "1": "흥국", "2": "DB", "3": "kb", "4": "현대","5": "현대","6": "롯데","7": "MG" ,"8":"삼성"};
		 const InsuranceCompany = periods[item.insuranceCom] || "알 수 없음";

		// const pushType={"1":"청약","2":"해지","3":"해지취소","4":"정상","5":"청약취소","6":"청약거절"}
		 const pushType={"1":"청약","2":"해지","3":"청약거절","4":"정상","5":"해지취소","6":"청약취소"}
		 
		 // 상태에 따른 색상 설정
		 let statusStyle = '';
		 if (item.push === '2') {
		   statusStyle = 'color: red; font-weight: bold;'; // 해지 - 빨간색
		 } else if (item.push === '4') {
		   statusStyle = 'color: green; font-weight: bold;'; // 정상 - 초록색
		 }
		 
		 const pushName = pushType[item.push];
		
        // 행 추가 (실제 순번은 전체 데이터 기준)
        m_smsList += `
          <tr>
            <td>${startIndex + index + 1}</td>
            <td>${item.name || ''}</td>
            <td>${item.Jumin || ''}</td>
            <td>${item.hphone}</td>
			<td style="${statusStyle}">${pushName}</td>
            <td>${item.policyNum || ''}</td>
            <td>${certiType || ''}</td>
			<td>${InsuranceCompany || ''}</td>
            <td>${item.company || ''}</td>
			<td>${item.Rphone1 || ''}-${item.Rphone2 || ''}-${item.Rphone3 || ''}</td>
            <td>${item.rate || ''}</td>
            <td class="kje-preiminum"><input type='text' id='mothly-${item.SeqNo}' value="${formattedPreminum}" class='memoInput'   
				onkeypress="if(event.key === 'Enter') { mothlyPremiumUpdate(this, ${item.SeqNo}); return false; }" autocomplete="off"></td>
            <td class="kje-preiminum"><input type='text' id='mothlyC-${item.SeqNo}' value="${formattedC_preminum}" class='memoInput'   
				onkeypress="if(event.key === 'Enter') { mothlyC_PremiumUpdate(this, ${item.SeqNo}); return false; }" autocomplete="off"></td>
            <td>${item.manager || ''}</td>
            <td>${formattedDate}</td>
          </tr>
        `;
      });
      
      // 테이블에 데이터 삽입
      m_dailyEndoseElement.innerHTML = m_smsList;
      
      // 페이징 UI 생성 및 추가
      createEPagination(totalPages, currentPage, selectedDay);
    } else {
      m_smsList = `
        <tr>
          <td colspan='15' style="text-align: center; padding: 20px;">
            조회 결과가 없습니다.
          </td>
        </tr>
      `;
      console.log('결과 없음:', result);
      
      // 데이터가 없을 때 페이지네이션 제거
      const existingPagination = document.getElementById("sms-pagination");
      if (existingPagination) {
        existingPagination.remove();
      }
      
      // 테이블에 데이터 삽입
      m_dailyEndoseElement.innerHTML = m_smsList;
    }
  } catch (error) {
    console.error("데이터 로딩 실패:", error);
    
    // 오류 메시지 테이블에 표시
    m_dailyEndoseElement.innerHTML = `
      <tr>
        <td colspan='14'>
          <div style="text-align: center; padding: 20px; color: #d9534f;">
            <i style="font-size: 24px; margin-bottom: 10px;">⚠️</i>
            <p>데이터 로딩 실패: ${error.message}</p>
          </div>
        </td>
      </tr>
    `;
    
    // 오류 발생시 페이지네이션 제거
    const existingPagination = document.getElementById("sms-pagination");
    if (existingPagination) {
      existingPagination.remove();
    }
    
    if (error.name === 'AbortError') {
      alert('요청 시간이 초과되었습니다. 나중에 다시 시도해주세요.');
    } else {
      alert(`데이터 로딩 실패: ${error.message}`);
    }
  }
}
//배서 후 보험료 미세조정
function mothlyPremiumUpdate(inputElement,smsDataNum){
	const premium= inputElement.value.trim();
	console.log('premium',premium,'smsDataNum',smsDataNum);
}
//배서 후 보험료 미세조정
function mothlyC_PremiumUpdate(inputElement,smsDataNum){
	const premium= inputElement.value.trim();
	console.log('premium',premium,'smsDataNum',smsDataNum);
}

// 페이지네이션 UI 생성 함수
function createEPagination(totalPages, currentPage, selectedDay) {
  // 기존 페이지네이션 제거
  const existingPagination = document.getElementById("sms-pagination");
  if (existingPagination) {
    existingPagination.remove();
  }
  
  // 페이지네이션 컨테이너 생성
  const paginationContainer = document.createElement("div");
  paginationContainer.id = "sms-pagination";
  paginationContainer.className = "pagination";
  
  // 처음 페이지 버튼
  const firstPageBtn = document.createElement("button");
  firstPageBtn.innerHTML = "<<";
  firstPageBtn.disabled = currentPage === 1;
  firstPageBtn.onclick = () => {
    // 현재 선택된 대리운전회사와 증권 정보 가져오기
    const dNum = document.getElementById('daily_endorse_dNumList').value;
    const policyNum = document.getElementById('daily_endorse_certiList').value;
    // 정렬 기준 결정
    let sort = 1;
    if (dNum && policyNum) sort = 3;
    else if (dNum) sort = 2;
    
    dailyEndorseRequest(1, selectedDay, dNum, policyNum, sort);
  };
  paginationContainer.appendChild(firstPageBtn);
  
  // 이전 페이지 버튼
  const prevPageBtn = document.createElement("button");
  prevPageBtn.innerHTML = "<";
  prevPageBtn.disabled = currentPage === 1;
  prevPageBtn.onclick = () => {
    // 현재 선택된 대리운전회사와 증권 정보 가져오기
    const dNum = document.getElementById('daily_endorse_dNumList').value;
    const policyNum = document.getElementById('daily_endorse_certiList').value;
    // 정렬 기준 결정
    let sort = 1;
    if (dNum && policyNum) sort = 3;
    else if (dNum) sort = 2;
    
    dailyEndorseRequest(currentPage - 1, selectedDay, dNum, policyNum, sort);
  };
  paginationContainer.appendChild(prevPageBtn);
  
  // 페이지 번호 버튼
  // 최대 5개의 페이지 번호 표시 (현재 페이지 중심)
  const maxPageButtons = 5;
  let startPage = Math.max(1, currentPage - Math.floor(maxPageButtons / 2));
  let endPage = Math.min(totalPages, startPage + maxPageButtons - 1);
  
  // 시작 페이지 조정 (5개 표시 유지)
  if (endPage - startPage + 1 < maxPageButtons && startPage > 1) {
    startPage = Math.max(1, endPage - maxPageButtons + 1);
  }
  
  for (let i = startPage; i <= endPage; i++) {
    const pageBtn = document.createElement("button");
    pageBtn.textContent = i;
    pageBtn.className = i === currentPage ? "active" : "";
    pageBtn.onclick = () => {
      // 현재 선택된 대리운전회사와 증권 정보 가져오기
      const dNum = document.getElementById('daily_endorse_dNumList').value;
      const policyNum = document.getElementById('daily_endorse_certiList').value;
      // 정렬 기준 결정
      let sort = 1;
      if (dNum && policyNum) sort = 3;
      else if (dNum) sort = 2;
      
      dailyEndorseRequest(i, selectedDay, dNum, policyNum, sort);
    };
    paginationContainer.appendChild(pageBtn);
  }
  
  // 다음 페이지 버튼
  const nextPageBtn = document.createElement("button");
  nextPageBtn.innerHTML = ">";
  nextPageBtn.disabled = currentPage === totalPages;
  nextPageBtn.onclick = () => {
    // 현재 선택된 대리운전회사와 증권 정보 가져오기
    const dNum = document.getElementById('daily_endorse_dNumList').value;
    const policyNum = document.getElementById('daily_endorse_certiList').value;
    // 정렬 기준 결정
    let sort = 1;
    if (dNum && policyNum) sort = 3;
    else if (dNum) sort = 2;
    
    dailyEndorseRequest(currentPage + 1, selectedDay, dNum, policyNum, sort);
  };
  paginationContainer.appendChild(nextPageBtn);
  
  // 마지막 페이지 버튼
  const lastPageBtn = document.createElement("button");
  lastPageBtn.innerHTML = ">>";
  lastPageBtn.disabled = currentPage === totalPages;
  lastPageBtn.onclick = () => {
    // 현재 선택된 대리운전회사와 증권 정보 가져오기
    const dNum = document.getElementById('daily_endorse_dNumList').value;
    const policyNum = document.getElementById('daily_endorse_certiList').value;
    // 정렬 기준 결정
    let sort = 1;
    if (dNum && policyNum) sort = 3;
    else if (dNum) sort = 2;
    
    dailyEndorseRequest(totalPages, selectedDay, dNum, policyNum, sort);
  };
  paginationContainer.appendChild(lastPageBtn);
  
  // 페이지네이션 UI를 모달 바디에 추가
  const modalBody = document.getElementById("dailyEndoseModal-body");
  if (modalBody) {
    modalBody.appendChild(paginationContainer);
  } else {
    // 모달 바디가 없는 경우 테이블 아래에 추가
    const table = document.querySelector(".sjTable");
    if (table && table.parentNode) {
      table.parentNode.insertBefore(paginationContainer, table.nextSibling);
    }
  }
}

// 검색 요청 함수 수정
function requestDailySearch() {
  // 선택된 날짜 가져오기
  const selectedDate = document.getElementById('dailyDate').value;
  
  if (!selectedDate) {
    alert('날짜를 선택해주세요.');
    return;
  }
  
  // 현재 선택된 대리운전회사와 증권 정보 가져오기
  const dNum = document.getElementById('daily_endorse_dNumList').value;
  const policyNum = document.getElementById('daily_endorse_certiList').value;
  
  // 정렬 기준 결정
  let sort = 1;
  if (dNum && policyNum) sort = 3;
  else if (dNum) sort = 2;
  
  // 선택된 날짜로 데이터 요청
  dailyEndorseRequest(1, selectedDate, dNum, policyNum, sort);
  
  // 증권 목록 업데이트
  todayEndorseloadSearchTable(selectedDate, dNum, policyNum, sort);
}
// 청약 ,해지 선택 
function f_kjEndorseloadTable(){
	
	const pushSangtaeList=document.getElementById('pushSangtaeList').value;
	//console.log('pushSangtaeList',pushSangtaeList);
	kjEndorseloadTable(1,'','',pushSangtaeList);
}
// 증권번호 리스트 시작
function populateCertiList(data) {
  // select 요소 가져오기
  const selectElement = document.getElementById('certiList');
  
  // 요소가 존재하는지 확인
  if (!selectElement) {
    console.error("Element with ID 'certiList' not found");
    return; // 요소가 없으면 함수 종료
  }
  
  // 기존 옵션 제거
  selectElement.innerHTML = '';
  
  // 기본 옵션 추가
  const defaultOption = document.createElement('option');
	defaultOption.value = '';
	defaultOption.textContent = '--  증권 선택 --';
	selectElement.appendChild(defaultOption);

	const defaultOption2 = document.createElement('option');
	defaultOption2.value = '1';
	defaultOption2.textContent = '--  모든 증권 --';
	selectElement.appendChild(defaultOption2);

// 각 항목을 옵션으로 추가
	data.data.forEach(item => {
		// 증권의 성격
			const iType={"1":"대리","2":"탁송","3":"대리렌트","4":"탁송렌트"};
			const certiType= iType[item.gita ] || "알 수 없음";
			// 보험사
			const periods = { "1": "흥국", "2": "DB", "3": "kb", "4": "현대","5": "현대","6": "롯데","7": "MG" ,"8":"삼성"};
			const InsuranceCompany = periods[item.InsuraneCompany] || "알 수 없음";
	  const option = document.createElement('option');
	  option.value = item.dongbuCerti;
	  option.textContent = `${InsuranceCompany }[${item.dongbuCerti}]${certiType}`;
	  selectElement.appendChild(option);
	});

console.log('data.pushCounts.subscription',data.pushCounts.subscription);
console.log('data.pushCounts.termination',data.pushCounts.termination);
console.log('data.pushCounts.total',data.pushCounts.total);

const currentSituation = document.getElementById('currentSituation');
currentSituation.innerHTML=`청약:${data.pushCounts.subscription},해지:${data.pushCounts.termination},계:${data.pushCounts.total}`;
// change 이벤트 추가
		selectElement.addEventListener('change', function() {
		  const selectedValue = this.value;
		  console.log('선택된 증권 번호:', selectedValue);
		  
		  // 선택된 값에 따라 다른 동작 수행
		  if (selectedValue === '') {
			console.log('증권이 선택되지 않았습니다.');
		  } else if (selectedValue === '1') {
			console.log('증권 전체가 선택되었습니다.');
			kjEndorseloadTable(1,selectedValue,'',''); // 전체 증권 데이터 로드 함수 호출
		  } else {
			console.log('특정 증권이 선택되었습니다:', selectedValue);
			kjEndorseloadTable(1,selectedValue,""); // 특정 증권 데이터 로드 함수 호출
		  }
		  
		  // 필요에 따라 검색 결과 갱신
			//searchList();
		});

	setTimeout(() => {
		SearchDaeriCompany();  // 대리운전회사
	}, 200);
}
function kjEndorseloadSearchTable(){
  // 여기에 fetch 코드 넣기
	fetchCallCount2++;
	console.log(`Fetch Call Count: ${fetchCallCount2}`);

	// 이미 실행 중이면 중복 호출 방지
	if (isFetching) return;
	
	// 실행 상태로 변경
	isFetching = true;
	const sj='sj_'
	// API 요청
	fetch(`https://kjstation.kr/api/kjDaeri/endorseCertiSerarch.php?sj=${sj}`)
    .then(response => {
      if (!response.ok) {
        throw new Error(`HTTP error! Status: ${response.status}`);
      }
      return response.json();
    })
    .then(data => {
      // 성공적으로 데이터를 받았을 때 드롭다운 채우기
      populateCertiList(data);
    })
    .catch((error) => {
      console.error("Error details:", error);
      alert("데이터를 불러오는 중 오류가 발생했습니다.");
    })
    .finally(() => {
      // 중앙 로딩 숨김 (필요한 경우)
      if (typeof hideKjeLoading === 'function') {
        hideKjeLoading();
      }
      // 실행 완료 후 상태 변경 (필요한 경우)
      if (typeof isFetching !== 'undefined') {
        isFetching = false;
      }
    });
}

// 증권번호 작업 끝
// 대리운전회사 명 찾기
function CompanyList(data) {
  // select 요소 가져오기
  const selectElement2 = document.getElementById('daeriCompanyList');
  
  // 요소가 존재하는지 확인
  if (!selectElement2) {
    console.error("Element with ID 'daeriCompanyList' not found");
    return; // 요소가 없으면 함수 종료
  }
  
  // 기존 옵션 제거
  selectElement2.innerHTML = '';
  
  // 기본 옵션 추가
  const defaultOption = document.createElement('option');
  defaultOption.value = '';
  defaultOption.textContent = '-- 대리운전회사 선택 --';
  selectElement2.appendChild(defaultOption);
  
  // 각 항목을 옵션으로 추가
  data.data.forEach(item => {
    const option = document.createElement('option');
    option.value = item.num;
    option.textContent = `${item.company}`;
    selectElement2.appendChild(option);
  });

	 selectElement2.addEventListener('change', function() {
		  const selectedValue = this.value;
		  console.log('선택된 대리운전회사:', selectedValue);
		  
		  // 선택된 값에 따라 다른 동작 수행
		  if (selectedValue === '') {
			console.log('대리운전회사가  선택되지 않았습니다.');
		  } else {
			console.log('특정 대리운전회사가  선택되었습니다:', selectedValue);
			kjEndorseloadTable(1,'',selectedValue,''); // 특정 증권 데이터 로드 함수 호출
		  }
		  
		  // 필요에 따라 검색 결과 갱신
			//searchList();
		});
}
function SearchDaeriCompany(){
  // 여기에 fetch 코드 넣기
	fetchCallCount2++;
	console.log(`Fetch Call Count: ${fetchCallCount2}`);

	// 이미 실행 중이면 중복 호출 방지
	if (isFetching2) return;
	
	// 실행 상태로 변경
	isFetching2 = true;
	const sj='sj_'
	// API 요청
	fetch(`https://kjstation.kr/api/kjDaeri/endorseCompanySerarch.php?sj=${sj}`)
    .then(response => {
      if (!response.ok) {
        throw new Error(`HTTP error! Status: ${response.status}`);
      }
      return response.json();
    })
    .then(data => {
      // 성공적으로 데이터를 받았을 때 드롭다운 채우기
      CompanyList(data);
    })
    .catch((error) => {
      console.error("Error details:", error);
      alert("데이터를 불러오는 중 오류가 발생했습니다.");
    })
    .finally(() => {
      // 중앙 로딩 숨김 (필요한 경우)
      if (typeof hideKjeLoading === 'function') {
        hideKjeLoading();
      }
      // 실행 완료 후 상태 변경 (필요한 경우)
      if (typeof isFetching !== 'undefined') {
        isFetching2 = false;
      }
    });
}

// 대리운전회사 명 끝
// 로딩 스타일 추가
function addKjeLoadingStyles() {
  if (!document.getElementById('kje-loading-styles')) {
    const styleSheet = document.createElement('style');
    styleSheet.id = 'kje-loading-styles';
    styleSheet.textContent = `
      /* 컨테이너 중앙 로딩 스타일 */
      .kje-loading-container {
        display: flex;
        flex-direction: column;
        justify-content: center;
        align-items: center;
        position: absolute;
        top: 0;
        left: 0;
        width: 100%;
        height: 100%;
        background-color: rgba(255, 255, 255, 0.8);
        z-index: 1000;
      }

      /* 기본 스피너 애니메이션 */
      .kje-spinner {
        width: 50px;
        height: 50px;
        border: 5px solid rgba(0, 0, 0, 0.1);
        border-radius: 50%;
        border-top-color: #3498db;
        animation: kje-spin 1s linear infinite;
      }

	  .pagination {
        display: flex;
        justify-content: center;
        margin-top: 20px;
        gap: 5px;
      }
      .pagination button {
        padding: 5px 10px;
        border: 1px solid #ddd;
        background-color: #f8f8f8;
        cursor: pointer;
        border-radius: 3px;
      }
      .pagination button.active {
        background-color: #8E6C9D;;
        color: white;
        border-color: #8E6C9D;;
      }
      .pagination button:hover:not(.active) {
        background-color: #e9e9e9;
      }
      .pagination button:disabled {
        opacity: 0.5;
        cursor: not-allowed;
      }

      @keyframes kje-spin {
        to { transform: rotate(360deg); }
      }

      /* 로딩 텍스트 스타일 */
      .kje-loading-text {
        margin-top: 15px;
        font-size: 16px;
        font-weight: 500;
        color: #333;
      }

      /* 점 애니메이션 */
      .kje-loading-dots {
        display: inline-block;
        position: relative;
        width: 20px;
      }

      .kje-loading-dots:after {
        content: '...';
        animation: kje-loading-dots 1.5s infinite steps(4, end);
        display: inline-block;
        width: 0;
        overflow: hidden;
      }

      @keyframes kje-loading-dots {
        0% { width: 0; }
        20% { width: 5px; }
        40% { width: 10px; }
        60% { width: 15px; }
        80% { width: 20px; }
      }

      /* 컨테이너 스타일 */
      .kje-list-container {
        position: relative;
        min-height: 300px;
      }
    `;
    document.head.appendChild(styleSheet);
  }
}


let fetchCallCount = 0;

// 전역 변수로 현재 필터 값 저장
let currentCertiValue = "";  //증권
let currentDaeriValue = "";  //대리운전회사
let currentPushSangtaeValue =""; //청약,해지

//function kjEndorseloadTable(page = 1, certiValue = currentCertiValue, daeriValue = currentDaeriValue,pushValue=currentPushSangtaeValue) {
function kjEndorseloadTable(page = 1, certiValue = currentCertiValue, daeriValue = currentDaeriValue,pushValue=currentPushSangtaeValue) {

	// 이전에 생성된 로더가 있다면 제거
    if (LoadingSystem.instances['myLoader']) {
        LoadingSystem.remove('myLoader');
    }
    
    // 새 로더 생성
    const myLoader = LoadingSystem.create('myLoader', {
        text: '데이터를 불러오는 중입니다...',
        autoShow: true
    });
	 // 이부분은 나중에 생각하고 
	console.log('certiValue',certiValue);
	if(certiValue){ //증권 선택되면 대리운전회사 선택없음
		document.getElementById('daeriCompanyList').value='';
	}
	console.log('daeriValue',daeriValue);
	if(daeriValue){ //대리운전 선택되면 증권은 선택하지 않은 
		document.getElementById('certiList').value='';
	}
    // 현재 필터 값 저장
    currentCertiValue = certiValue;
    currentDaeriValue = daeriValue;
    currentPushSangtaeValue=pushValue;
    fetchCallCount++;
    console.log(`Fetch Call Count: ${fetchCallCount}`);
    if (isFetching) return;  // 이미 실행 중이면 종료
    isFetching = true;  // 실행 상태로 변경

    // 중앙 로딩 표시
    //showKjeLoading();

    const itemsPerPage = 20;
    const tableBody = document.querySelector("#kje-applicationList");
    const pagination = document.querySelector(".kje-pagination");
	const currentSituation = document.getElementById('currentSituation');

    // 테이블 내 로딩 표시
   tableBody.innerHTML = '<tr><td colspan="17" class="loading"></td></tr>';
    
    console.log(`페이지 로드 요청: ${page}페이지, 인증값: ${certiValue}, 대리값: ${daeriValue}`);

    fetch(`https://kjstation.kr/api/kjDaeri/endorse.php?page=${page}&limit=${itemsPerPage}&certi=${certiValue}&dNum=${daeriValue}&push=${pushValue}`)
        .then(response => response.json())
        .then(response => {
            let rows = "";
            // 데이터 존재 여부 확인
            if (!response.data || response.data.length === 0) {
                rows = `<tr><td colspan="13" style="text-align: center;">검색 결과가 없습니다.</td></tr>`;
            } else {
				//console.log(response);
                response.data.forEach((item, index) => {
                    // 기존 코드와 동일한 부분 유지...
                    const formattedInsurancePremium = item.Endorsement_insurance_company_premium ? parseFloat(item.Endorsement_insurance_company_premium).toLocaleString("en-US") : "";
					const formattedMothlyPremium = item.Endorsement_insurance_premium ? parseFloat(item.Endorsement_insurance_premium).toLocaleString("en-US") : "";

                    const rateOptions=`
                        <select class="kje-status-rate" data-id="${item.num}" id='rate-${item.num}' onchange="handleRateChangeSj(this, '${item.num}','${item.Jumin}','${item.dongbuCerti}')">
                            <option value="-1" >선택</option>                            
                            <option value="1" ${item.rate == 1 ? "selected" : ""}>1</option> //가입경력 1년 미만 3년간 사고건수 1년간 사고건수 무사고 경역
                            <option value="2" ${item.rate == 2 ? "selected" : ""}>0.9</option>
                            <option value="3" ${item.rate == 3 ? "selected" : ""}>0.925</option>//가입경력 1년 이상 3년간 사고건수 0건 1년간 사고건수 0건 무사고  1년 이상
                            <option value="4" ${item.rate == 4 ? "selected" : ""}>0.898</option>//가입경력 1년 이상 3년간 사고건수 0건 1년간 사고건수 0건 무사고  2년 이상
                            <option value="5" ${item.rate == 5 ? "selected" : ""}>0.889</option>////가입경력 1년 이상 3년간 사고건수 0건 1년간 사고건수 0건 무사고  3년 이상
                            <option value="6" ${item.rate == 6 ? "selected" : ""}>1.074</option>//가입경력 1년 이상 3년간 사고건수 1건 1년간 사고건수 0건
                            <option value="7" ${item.rate == 7 ? "selected" : ""}>1.085</option>//가입경력 1년 이상 3년간 사고건수 1건 1년간 사고건수 1건
                            <option value="8" ${item.rate == 8 ? "selected" : ""}>1.242</option>//가입경력 1년 이상 3년간 사고건수 2건 1년간 사고건수 0건
                            <option value="9" ${item.rate == 9 ? "selected" : ""}>1.253</option>//가입경력 1년 이상 3년간 사고건수 2건 1년간 사고건수 1건
                            <option value="10" ${item.rate == 10 ? "selected" : ""}>1.314</option>//가입경력 1년 이상 3년간 사고건수 2건 1년간 사고건수 2건
                            <option value="11" ${item.rate == 11 ? "selected" : ""}>1.428</option>////가입경력 1년 이상 3년간 사고건수 3건이상 1년간 사고건수 0건
                            <option value="12" ${item.rate == 12 ? "selected" : ""}>1.435</option>////가입경력 1년 이상 3년간 사고건수 3건이상 1년간 사고건수 1건
                            <option value="13" ${item.rate == 13 ? "selected" : ""}>1.447</option>////가입경력 1년 이상 3년간 사고건수 3건이상 1년간 사고건수 2건
                            <option value="14" ${item.rate == 14 ? "selected" : ""}>1.459</option>////가입경력 1년 이상 3년간 사고건수 3건이상 1년간 사고건수 3건 이상 
                        </select>
                    `
                    //담당자 
                    const MemberNumType={"1":"sj","2":"kj"};
                    const Member=MemberNumType[item.MemberNum]||"알 수 없 음";
					const statusOptions = `
						<select class="kje-status-select" data-id="${item.num}" onchange="handleStatusChangeSj(this, '${item.num}','${item.push}')">
							<option value="1" ${item.ch == 1 ? "selected" : ""}>미처리</option>
							<option value="2" ${item.ch == 2 ? "selected" : ""}>처리</option>
						</select>
					`;

                    // 증권의 성격
                    const iType={"1":"대리","2":"탁송","3":"대리렌트","4":"탁송렌트"};
                    const certiType= iType[item.etag ] || "알 수 없음";
                    // 보험사
                    const periods = { "1": "흥국", "2": "DB", "3": "kb", "4": "현대","5": "현대","6": "롯데","7": "MG" ,"8":"삼성"};
                    const InsuranceCompany = periods[item.InsuranceCompany] || "알 수 없음";
                    //청약 1 해지 4
                    const pType={"1":"청약","4":"해지"}
                    const pushText=pType[item.push]||"알 수 없음";
					
					const progressStage=`
                        <select class="kje-status-rate" data-id="${item.num}" id='progress-${item.num}' onchange="handleProgress(this, '${item.num}')">
                            <option value="-1" >선택</option>                            
                            <option value="1" ${item.progress == 1 ? "selected" : ""}>프린트</option> 
                            <option value="2" ${item.progress == 2 ? "selected" : ""}>스캔</option>
                            <option value="3" ${item.progress == 3 ? "selected" : ""}>고객등록</option>
                            <option value="4" ${item.progress == 4 ? "selected" : ""}>심사중</option>
                            <option value="5" ${item.progress == 5 ? "selected" : ""}>입금대기</option>
                            <option value="6" ${item.progress == 6 ? "selected" : ""}>카드승인</option>
                            <option value="7" ${item.progress == 7 ? "selected" : ""}>수납중</option>
                            <option value="8" ${item.progress == 8 ? "selected" : ""}>확정중</option>
                        </select>
                    `
                    // response.data.forEach 부분 내에서 다음과 같이 수정

					// 오늘 날짜 가져오기
					const today = new Date();
					const todayStr = `${today.getFullYear()}-${String(today.getMonth() + 1).padStart(2, '0')}-${String(today.getDate()).padStart(2, '0')}`;

					// 날짜 비교 및 스타일 적용
					let endorseDayStyle = '';
					if (item.endorse_day && item.endorse_day !== todayStr) {
						endorseDayStyle = 'color: red; font-weight: bold;';
					}

					// 테이블 행에 날짜 표시 부분 수정
					rows += `<tr>
						<td>
							<a href="#" class="kje-btn-link_1 open-kjEndose-modal"
							   onClick="kjEndorse('${item.endorse_day || ''}', '${item['2012DaeriCompanyNum'] || ''}', '${item.CertiTableNum}', '${item.EndorsePnum || ''}')">
							   ${index + 1}
							</a>
						</td>
						<td><a href="#" class="kje-btn-link_1 open-modal" data-num="${item.num}">${Member || ""}</a></td>
						<td><span class="kje-btn-link_1" style="cursor:pointer;" onclick="kjDaeriCompany('${item['2012DaeriCompanyNum'] || ''}')">${item.company}</span> </td>
						<td>${item.Name || ""}[${item.nai || ""}]</td>
						<td>${item.jumin2 || ""}</td>
						<td>${item.Hphone || ""}
						  <button type="button" class="sms-button sms-icon-button" title="문자 발송" onClick="sms('${item.Hphone}','${item.Name}',event);">
						  <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="currentColor">
							<path d="M20 2H4c-1.1 0-1.99.9-1.99 2L2 22l4-4h14c1.1 0 2-.9 2-2V4c0-1.1-.9-2-2-2zm-2 12H6v-2h12v2zm0-3H6V9h12v2zm0-3H6V6h12v2z"/>
						  </svg>
						</button>
						</td>
						<td>${progressStage}</td>
						<td><span id='p_manager-${item.num}' >${item.manager}</span></td>
						<td style="${endorseDayStyle}">${item.endorse_day || ""}</td>
						<td>${item.wdate || ""}</td>
						<td>${item.dongbuCerti || ""}</td>
						<td>${certiType || ""}</td>
						<td>${rateOptions}</td>
						<td>${pushText|| ""}</td>
						<td>${statusOptions}</td>
						<td>${InsuranceCompany || ""}</td>
						<td class="kje-preiminum">${formattedMothlyPremium}</td>
						<td class="kje-preiminum">${formattedInsurancePremium}</td>
						<td><span class="kje-btn-link_1" style="cursor:pointer;" onclick="kjMemberList('${item.Jumin || ''}',2)">${item.duplNum || ""}</span>
							</a></td>
					</tr>`;
                });
            }
            tableBody.innerHTML = rows;

			console.log('response.pushCounts.subscription',response.pushCounts.subscription);
			console.log('response.pushCounts.termination',response.pushCounts.termination);
			console.log('response.pushCounts.total',response.pushCounts.total);

			
			currentSituation.innerHTML=`청약:${response.pushCounts.subscription},해지:${response.pushCounts.termination},계:${response.pushCounts.total}`;
						
            // 페이지네이션 생성
            console.log('totalRecords', response.totalRecords);
            const pagination = document.querySelector(".kje-pagination");
            
            if (pagination) {
                // 페이지내에션 렌더링 시 현재 필터 값도 함께 전달
                kjRenderPagination(page, Math.ceil(response.totalRecords / itemsPerPage));
            } else {
                console.error("Pagination element not found");
            }
        })
        .catch((error) => {
            console.error("Error details:", error);
            alert("데이터를 불러오는 중 오류가 발생했습니다.");
        })
        .finally(() => {
            // 중앙 로딩 숨김
            // 로딩 인디케이터 숨김
			 myLoader.hide();
             isFetching = false;  // 실행 완료 후 상태 변경
         });
}

function kjRenderPagination(currentPage, totalPages) {
    console.log('currentPage', currentPage);
    console.log('totalPages', totalPages);
    
    const pagination = document.querySelector(".kje-pagination");
    if (!pagination) {
        console.error("페이지네이션 요소를 찾을 수 없습니다.");
        return;
    }
    
    pagination.innerHTML = ""; // 기존 버튼 삭제
    console.log('Rendering pagination - Current page:', currentPage, 'Total pages:', totalPages);

    // 이전 버튼 추가
    if (currentPage > 1) {
        pagination.innerHTML += `<a href="#" class="kje-page-link" data-page="${currentPage - 1}">이전</a>`;
    } else {
        pagination.innerHTML += `<a href="#" class="kje-disabled">이전</a>`;
    }

    // 숫자 버튼 추가 (최대 5개 표시)
    const maxPagesToShow = 5;
    let startPage = Math.max(1, currentPage - Math.floor(maxPagesToShow / 2));
    let endPage = Math.min(totalPages, startPage + maxPagesToShow - 1);
    
    // 페이지 수가 5개 미만일 경우, 시작 페이지 조정
    if (endPage - startPage + 1 < maxPagesToShow && startPage > 1) {
        startPage = Math.max(1, endPage - maxPagesToShow + 1);
    }

    for (let i = startPage; i <= endPage; i++) {
        pagination.innerHTML += `<a href="#" class="kje-page-link ${i === parseInt(currentPage) ? "active" : ""}" data-page="${i}">${i}</a>`;
    }

    // 다음 버튼 추가
    if (currentPage < totalPages) {
        pagination.innerHTML += `<a href="#" class="kje-page-link" data-page="${currentPage + 1}">다음</a>`;
    } else {
        pagination.innerHTML += `<a href="#" class="kje-disabled">다음</a>`;
    }

    // 이벤트 리스너 추가 - 기존 이벤트 리스너 제거 후 다시 추가
    const pageLinks = document.querySelectorAll(".kje-page-link");
    console.log(`페이지 링크 개수: ${pageLinks.length}`);
    
    pageLinks.forEach(link => {
        // 이전 이벤트 리스너 제거 (중복 방지)
        link.removeEventListener("click", pageClickHandler);
        // 새 이벤트 리스너 추가
        link.addEventListener("click", pageClickHandler);
    });
}

// 페이지 클릭 이벤트 핸들러 수정
function pageClickHandler(e) {
    e.preventDefault();
    const page = parseInt(this.dataset.page);
    console.log(`페이지 이동 요청: ${page}페이지, 필터값: ${currentCertiValue}, ${currentDaeriValue}`);
    // 저장된 필터 값을 함께 전달
    kjEndorseloadTable(page, currentCertiValue, currentDaeriValue);
}

// 검색 버튼 이벤트 핸들러 예시 (검색 기능이 있는 경우)
function searchHandler() {
    // 예: 폼에서 값 가져오기
    const certiValue = document.getElementById('certiInput').value || '';
    const daeriValue = document.getElementById('daeriInput').value || '';
    
    // 페이지는 1로 리셋하고 검색 실행
    kjEndorseloadTable(1, certiValue, daeriValue);
}

// 검색 버튼에 이벤트 리스너 추가 (검색 기능이 있는 경우)
document.addEventListener('DOMContentLoaded', function() {
    const searchButton = document.getElementById('kje-search-button');
    if (searchButton) {
        searchButton.addEventListener('click', searchHandler);
    }
});
  // 날짜 기본값 설정 함수
function setDefaultDate() {
    const sigiInput = document.getElementById("sigi");
	const endInput = document.getElementById("end");
	const today = new Date();
	
	// 1주일 전 날짜 계산
	const pastDate = new Date();
	pastDate.setDate(today.getDate() - 7);

	// YYYY-MM-DD 형식으로 변환
	const formattedPastDate = pastDate.toISOString().split("T")[0];
	const formattedToday = today.toISOString().split("T")[0];

	// input 요소에 기본값 설정
	if (sigiInput) sigiInput.value = formattedPastDate;
	if (endInput) endInput.value = formattedToday;
}
document.addEventListener("change", function (e) {
    // 변경된 요소가kje-status-select2 클래스인지 확인
    if (e.target.classList.contains("kje-status-rate")) {
        const num = e.target.dataset.id;  // data-id 속성에서 num 값 가져오기
        const selectedValue = e.target.value;  // 선택된 옵션 값 가져오기
        
        // 상태 변경 함수 호출
        handleRateChange(num, selectedValue);
    }
	// 변경된 요소가 status-select 클래스인지 확인
  /*  if (e.target.classList.contains("f-status-select")) {
        const num = e.target.dataset.id;  // data-id 속성에서 num 값 가져오기
        const selectedValue = e.target.value;  // 선택된 옵션 값 가져오기
        
        // 상태 변경 함수 호출
        handleStatusChange(num, selectedValue);
    }*/
});
//요율 변경
function handleRateChange(num, selectedValue){
	console.log('num',num, 'selectedValue',selectedValue);
}
/* 검색 버튼*/
function searchList() {
    const searchType = document.querySelector("#kje-searchType").value;
    const searchKeyword = document.querySelector("#kje-searchKeyword").value.trim();

    if (!searchKeyword) {
        alert("검색어를 입력하세요.");
        return;
    }

    kjEndorseloadTable(1, searchKeyword, searchType);
}


async function handleDamdangjarClick(dNum, memberNum){
  console.log('dNum', dNum, 'memberNum', memberNum);
  try {
    // API 호출을 위한 FormData 준비
    const formData = new FormData();
    formData.append('dNum', dNum);
    
    // 서버 API 호출
    const response = await fetch('https://kjstation.kr/api/kjDaeri/memberList.php', {
      method: "POST",
      body: formData // FormData 자동으로 Content-Type 설정
    });
    
    // 응답 처리
    const result = await response.json();
    if (result.success) {
      console.log('영업 담당자 리스트:', result);
       // 회사명 표시
      document.getElementById('damdangja_daeriCompany').textContent = result.company;
      // 테이블 본문 요소 가져오기
      const tableBody = document.getElementById('damdangja_list');
      
      // 이전 데이터 삭제
      tableBody.innerHTML = '';
      
     
      
      
      
      // 데이터 행 추가
      result.data.forEach((member, index) => {
        const row = document.createElement('tr');
        
        // 순번 열
        const numCell = document.createElement('td');
        numCell.textContent = member.num;
        row.appendChild(numCell);
        
        // 성명 열
        const nameCell = document.createElement('td');
        nameCell.textContent = member.name;
        row.appendChild(nameCell);
        
        // id 열
        const idCell = document.createElement('td');
        idCell.textContent = member.mem_id;
        row.appendChild(idCell);
        
        // 선택 열 (라디오 버튼)
        const selectCell = document.createElement('td');
        const radioBtn = document.createElement('input');
        radioBtn.type = 'radio';
        radioBtn.name = 'damdangja';
        radioBtn.value = member.mem_id;
        radioBtn.dataset.name = member.name;
        radioBtn.dataset.num = member.num;
        
        // memberNum과 일치하면 라디오 버튼 체크

		console.log('member.num',member.num) ;
		console.log('memberNum',memberNum);
        // memberNum과 일치하면 라디오 버튼 체크 (문자열로 변환하여 비교)
        if (memberNum && String(member.num) === String(memberNum)) {
          radioBtn.checked = true;
          console.log('체크됨: member.num=', member.num, 'memberNum=', memberNum);
        }
        
        // 선택 시 함수 호출
        radioBtn.addEventListener('change', function() {
          if (this.checked) {
            seleSS("순번", dNum, this.dataset.num);
          }
        });
        
        selectCell.appendChild(radioBtn);
        row.appendChild(selectCell);
        
        // 통장 열 (체크박스)
        const accountCell = document.createElement('td');
        const checkbox = document.createElement('input');
        checkbox.type = 'checkbox';
        checkbox.name = 'account';
        checkbox.value = member.mem_id;
        checkbox.dataset.num = member.num;
        
        // 체크박스 클릭 이벤트 추가
        checkbox.addEventListener('change', function() {
          seleSS("순번", dNum, this.dataset.num);
        });
        
        accountCell.appendChild(checkbox);
        row.appendChild(accountCell);
        
        // 행을 테이블에 추가
        tableBody.appendChild(row);
      });
      
    } else {
      console.log('결과 없음:', result);
      // 결과가 없는 경우 처리
      const tableBody = document.getElementById('damdangja_list');
      tableBody.innerHTML = '<tr><td colspan="5">데이터가 없습니다.</td></tr>';
    }
  } catch (error) {
    console.error("상태 업데이트 실패:", error);
    alert("상태 업데이트 실패.");
  }
  
  // 모달 표시
  document.getElementById("kj-damdangja-modal").style.display = "block";
  
  
 
}

/**
 * 담당자 선택 처리 함수
 * @param {string} type - 처리 유형 (현재는 "순번"만 사용)
 * @param {string} dNum - 대리회사 번호
 * @param {string} memberNum - 회원 번호
 */
function seleSS(type, dNum, memberNum) {
  console.log(`담당자 변경: ${type}, 대리사 번호: ${dNum}, 회원 번호: ${memberNum}`);
  
  // 여기에 선택된 담당자 정보를 서버에 전송하는 코드를 추가할 수 있습니다
  // 예시:

  const formData = new FormData();
  formData.append('type', type);
  formData.append('dNum', dNum);
  formData.append('memberNum', memberNum);
  
  fetch('https://kjstation.kr/api/kjDaeri/updateDamdangja.php', {
    method: "POST",
    body: formData
  })
  .then(response => response.json())
  .then(result => {
    if (result.success) {
      alert('담당자가 변경되었습니다.');
		document.getElementById('kj-damdangja-modal').style.display = "none";  // 담당자 선택 화면 닫기
		document.getElementById('kj-DaeriCompany-modal').style.display = "none"; // 대리운전 회사 화면 닫기 
			kjDaeriCompany(dNum) ;

    } else {
      alert('담당자 변경에 실패했습니다.');
    }
  })
  .catch(error => {
    console.error("담당자 변경 오류:", error);
    alert("담당자 변경 중 오류가 발생했습니다.");
  });
 
}
/**
 * 대리점 ID 데이터를 가져와서 처리하는 함수
 * @param {string} dNum - 대리점 번호
 */
async function handleIdClick(dNum) {
  try {
    const formData = new FormData();
    // API 호출을 위한 FormData 준비
    formData.append('dNum', dNum);
    
    // 서버 API 호출
    const response = await fetch('https://kjstation.kr/api/kjDaeri/id_.php', {
      method: "POST",
      body: formData
    });
    
    // 응답 처리
    const result = await response.json();
    
    // 회사명 표시 영역 초기화
    document.getElementById('id_daeriCompany').textContent = "";
    
    // 테이블에 데이터 표시할 변수 초기화
    let id_list = "";
    
    if (result.success && result.data.length > 0) {
      console.log('대리점 데이터:', result);
      
      // 회사명 표시
      document.getElementById('id_daeriCompany').textContent = result.data[0].company;
      
      // 데이터가 배열 형태로 반환되므로 반복문으로 처리
      result.data.forEach(item => {
        if(item.readIs==null){
          item.readIs="2";
        }
        id_list += `<tr>
          <input type='hidden' id='idNum-${item.num}' value='${item.num}'>
          <td><input type='text' id='newDamdang-${item.num}' value="${item.user}" class='geInput2'   
            onkeypress="if(event.key === 'Enter') { validateAndUpdateUser(this, ${item.num}); return false; }" autocomplete="off"></td>
          <td><input type='text' id='newId-${item.num}' value="${item.mem_id}" class='geInput2'  ></td>
          <td><input type='text' id='phone-${item.num}' value="${item.hphone}" class='geInput2' oninput="formatPhoneNumber(this)" autocomplete="off"
            onkeypress="if(event.key === 'Enter') { validateAndUpdatePhone(this, ${item.num}); return false; }"  ></td>
          <td><select id='readIs-${item.num}' class='geInput2' onChange="updateReadStatus(this, ${item.num})">
              <option value="-1" >선택</option>
              <option value="1" ${item.readIs === "1" ? "selected" : ""}>읽기전용</option>
              <option value="2" ${item.readIs === "2" ? "selected" : ""}>모든권한</option>
            </select></td>
          <td><input type='text' id='newPassword-${item.num}' placeholder="비밀번호 입력" class='geInput2'
                onkeypress="if(event.key === 'Enter') { validateAndUpdatePassword(this, ${item.num}); return false; }"
                oninput="validatePasswordLength(this)"></td>
          <td>
            <select id='newPermit-${item.num}' class='geInput2' onChange="updatePermitStatus(this, ${item.num})">
              <option value="-1" >선택</option>
              <option value="1" ${item.permit === "1" ? "selected" : ""}>허용</option>
              <option value="2" ${item.permit === "2" ? "selected" : ""}>차단</option>
            </select>
          </td>
        </tr>`;
      });
    } else {
      console.log("데이터가 없거나 API 응답 실패:", result);
      // result.success가 false인 경우에도 dNum은 사용 가능
    }
    
    // 새 항목 추가를 위한 행은 항상 추가 (데이터가 없는 경우에도)
    // company와 hphone 정보는 데이터가 있을 때만 사용, 없으면 빈 문자열 사용
    const companyValue = (result.success && result.data.length > 0) ? result.data[0].company : '';
    const hphoneValue = (result.success && result.data.length > 0) ? result.data[0].hphone : '';
    
    id_list += `<tr>
      <td><input type='text' id='newDamdang'  class='geInput2' placeholder="담당자성명" autocomplete="off"></td>
      <td><input type='text' id='newId' class='geInput2' placeholder="아이디"
             onkeypress="if(event.key === 'Enter') { validateAndCheckId(this); return false; }"
             oninput="validateIdLength(this)" autocomplete="off"></td>
      <td><input type='text' id='phone-new'  class='geInput2' oninput="formatPhoneNumber(this)" autocomplete="off"></td>
      <td><input type='text' id='newPassword' placeholder="비밀번호 입력" class='geInput2' 
              oninput="validatePasswordLength(this)" autocomplete="off"></td>
      <td colspan='2'><button class="kje-daeri-button" onclick="id_store('${dNum}','${hphoneValue}','${companyValue}')" >신규아이디 생성</button></td>
    </tr>`;
    
    // 테이블 본문 업데이트
    document.getElementById("id_list").innerHTML = id_list;
    
    // 모달 표시
    document.getElementById("kj-id-modal").style.display = "block";
    
  } catch (error) {
    console.error("데이터 불러오기 실패:", error);
    alert("데이터를 불러오는 중 오류가 발생했습니다.");
  }
}
//문자보내기
function smsE_send() {
  console.log('smsSend', document.getElementById('d_hphone').value);
  const d_hphone = document.getElementById('d_hphone').value;
  const smsContents = document.getElementById('smsContents').value;
  
  // 입력 유효성 검사
  if (!smsContents) {
    alert('내용을 입력해주세요.');
    document.getElementById('smsContents').focus();
    return;
  }
  
  // FormData 객체 생성
  const formData = new FormData();
  
  // 데이터 추가
  formData.append('d_hphone', d_hphone);
  formData.append('smsContents', smsContents);
  
  // 서버로 데이터 전송 (fetch 사용)
  fetch('https://kjstation.kr/api/kjDaeri/smsSend.php', {
    method: 'POST',
    body: formData
  })
  .then(response => response.json())
  .then(data => {
    console.log('성공:', data);
    alert('메시지가 성공적으로 전송되었습니다.');
	document.getElementById('smsContents').value='';
  })
  .catch(error => {
    console.error('에러:', error);
    alert('메시지 전송 중 오류가 발생했습니다.');
  });
}
  // 전화번호 입력 형식 자동 변환 이벤트 설정
function formatPhoneNumber(input) {
  // 숫자만 추출
  let phoneNumber = input.value.replace(/[^0-9]/g, '');
  
  // 하이픈 추가
  if (phoneNumber.length >= 3 && phoneNumber.length <= 7) {
    // 010-1234
    phoneNumber = phoneNumber.substring(0, 3) + '-' + phoneNumber.substring(3);
  } else if (phoneNumber.length > 7) {
    // 010-1234-5678
    phoneNumber = phoneNumber.substring(0, 3) + '-' + 
                  phoneNumber.substring(3, 7) + '-' + 
                  phoneNumber.substring(7, 11);
  }
  
  // 값 업데이트
  input.value = phoneNumber;
}
// 아이디 길이 유효성 검사 및 메시지 표시 함수
function validateIdLength(inputElement) {
    const statusElement = document.getElementById('idExplain');
    const id = inputElement.value.trim();
    
    if (id.length > 0 && id.length < 4) {
        statusElement.textContent = '아이디는 4자리 이상 입력해주세요.';
        statusElement.style.color = 'red';
    } else if (id.length >= 4) {
        statusElement.textContent = '유효한 아이디 형식입니다.';
        statusElement.style.color = 'green';
    } else {
        statusElement.textContent = '';
    }
}

// 아이디 유효성 검사 및 중복 확인 함수
function validateAndCheckId(inputElement) {
    const statusElement = document.getElementById('idExplain');
    const id = inputElement.value.trim();
    
    // 아이디가 비어있는 경우
    if (id === '') {
        statusElement.textContent = '아이디를 입력해주세요.';
        statusElement.style.color = 'red';
        inputElement.focus();
        return;
    }
    
    // 아이디가 4자 미만인 경우
    if (id.length < 4) {
        statusElement.textContent = '아이디는 4자리 이상 입력해주세요.';
        statusElement.style.color = 'red';
        inputElement.focus();
        return;
    }
    
    // 영문자, 숫자, 특수문자(_ 또는 -)만 허용하는 정규식
    const validIdPattern = /^[a-zA-Z0-9_-]+$/;
    if (!validIdPattern.test(id)) {
        statusElement.textContent = '아이디는 영문자, 숫자, 특수문자(_,-)만 사용 가능합니다.';
        statusElement.style.color = 'red';
        inputElement.focus();
        return;
    }
    
    // 상태 메시지 업데이트 (확인 중)
    statusElement.textContent = '아이디 확인 중...';
    statusElement.style.color = 'blue';
    
    // FormData 객체 생성
    const formData = new FormData();
    formData.append('userId', id);
    
    // 서버로 아이디 중복 확인 요청 전송
    fetch('https://kjstation.kr/api/kjDaeri/checkUserId.php', {
        method: 'POST',
        body: formData
    })
    .then(response => response.json())
    .then(data => {
        if (data.success) {
            // available이 2인 경우 사용 가능한 아이디
            if (data.available === 2) {
                statusElement.textContent = data.message || '사용 가능한 ID 입니다';
                statusElement.style.color = 'green';
                // 여기에 ID를 사용할 수 있음을 나타내는 플래그 설정 가능
            } else {
                statusElement.textContent = data.message || '사용할 수 없는 ID 입니다';
                statusElement.style.color = 'red';
                inputElement.focus();
            }
        } else {
            statusElement.textContent = '아이디 확인 중 오류가 발생했습니다.';
            statusElement.style.color = 'red';
        }
    })
    .catch(error => {
        console.error('아이디 확인 중 오류가 발생했습니다:', error);
        statusElement.textContent = '아이디 확인 중 오류가 발생했습니다. 다시 시도해주세요.';
        statusElement.style.color = 'red';
    });
}
//읽기 전용 1 ,
function updateReadStatus(selectElement, num){
	// 선택된 값 가져오기
  const readValue = selectElement.value;
  const idNum = document.getElementById(`idNum-${num}`);
  const statusElement = document.createElement('div');
  statusElement.id = `status-message-${num}`;
  statusElement.style.marginTop = '5px';

  if(readValue<0){
		alert('읽기전용 또는 모든권한을 선택하세요');
		document.getElementById(`newPermit-${num}`).focus();
		return false;
  }
  
  // 상태 메시지 요소가 이미 있는지 확인하고 없으면 추가
  let existingStatus = document.getElementById(`status-message-${num}`);
  if (!existingStatus) {
    selectElement.parentNode.appendChild(statusElement);
    existingStatus = statusElement;
  }
  
  // idNum 요소가 없거나 값이 없으면 처리하지 않음
  if (!idNum || !idNum.value) {
    console.log('idNum 요소가 없거나 값이 없습니다.');
    existingStatus.textContent = 'ID 번호를 찾을 수 없습니다.';
    existingStatus.style.color = 'red';
    return;
  }
  
  // 상태 메시지 표시 (업데이트 중)
  existingStatus.textContent = '권한 설정 업데이트 중...';
  existingStatus.style.color = 'blue';
  
  // FormData 객체 생성
  const formData = new FormData();
  formData.append('read', readValue);
  formData.append('idNum', idNum.value);
  
  // 서버로 권한 업데이트 요청 전송
  fetch('https://kjstation.kr/api/kjDaeri/updateReadIs.php', {
    method: 'POST',
    body: formData
  })
  .then(response => response.json())
  .then(data => {
    if (data.success) {
      existingStatus.textContent = '권한 설정이 성공적으로 업데이트되었습니다.';
      existingStatus.style.color = 'green';
      
      // 3초 후 성공 메시지 제거
      setTimeout(() => {
        existingStatus.textContent = '';
      }, 3000);
    } else {
      existingStatus.textContent = '권한 설정 업데이트에 실패했습니다: ' + (data.message || '알 수 없는 오류');
      existingStatus.style.color = 'red';
    }
  })
  .catch(error => {
    console.error('권한 설정 업데이트 중 오류가 발생했습니다:', error);
    existingStatus.textContent = '권한 설정 업데이트 중 오류가 발생했습니다. 다시 시도해주세요.';
    existingStatus.style.color = 'red';
  });

}
// ,허용,차단 상태 업데이트 함수
function updatePermitStatus(selectElement, num) {
  // 선택된 값 가져오기
  const permitValue = selectElement.value;
  const idNum = document.getElementById(`idNum-${num}`);
  const statusElement = document.createElement('div');
  statusElement.id = `status-message-${num}`;
  statusElement.style.marginTop = '5px';

  if(permitValue<0){
		alert('허용 또는 차단을 선택하세요');
		document.getElementById(`newPermit-${num}`).focus();
		return false;
  }
  
  // 상태 메시지 요소가 이미 있는지 확인하고 없으면 추가
  let existingStatus = document.getElementById(`status-message-${num}`);
  if (!existingStatus) {
    selectElement.parentNode.appendChild(statusElement);
    existingStatus = statusElement;
  }
  
  // idNum 요소가 없거나 값이 없으면 처리하지 않음
  if (!idNum || !idNum.value) {
    console.log('idNum 요소가 없거나 값이 없습니다.');
    existingStatus.textContent = 'ID 번호를 찾을 수 없습니다.';
    existingStatus.style.color = 'red';
    return;
  }
  
  // 상태 메시지 표시 (업데이트 중)
  existingStatus.textContent = '권한 설정 업데이트 중...';
  existingStatus.style.color = 'blue';
  
  // FormData 객체 생성
  const formData = new FormData();
  formData.append('permit', permitValue);
  formData.append('idNum', idNum.value);
  
  // 서버로 권한 업데이트 요청 전송
  fetch('https://kjstation.kr/api/kjDaeri/updatePermit.php', {
    method: 'POST',
    body: formData
  })
  .then(response => response.json())
  .then(data => {
    if (data.success) {
      existingStatus.textContent = '권한 설정이 성공적으로 업데이트되었습니다.';
      existingStatus.style.color = 'green';
      
      // 3초 후 성공 메시지 제거
      setTimeout(() => {
        existingStatus.textContent = '';
      }, 3000);
    } else {
      existingStatus.textContent = '권한 설정 업데이트에 실패했습니다: ' + (data.message || '알 수 없는 오류');
      existingStatus.style.color = 'red';
    }
  })
  .catch(error => {
    console.error('권한 설정 업데이트 중 오류가 발생했습니다:', error);
    existingStatus.textContent = '권한 설정 업데이트 중 오류가 발생했습니다. 다시 시도해주세요.';
    existingStatus.style.color = 'red';
  });
}
/**
 * 새로운 대리점 ID 정보를 저장하는 함수
 * @param {string} dNum - 대리점 번호
 */
async function id_store(dNum,phone,company) {
  try {
    // 입력값 가져오기
    const newId = document.getElementById('newId').value.trim();
    const newPassword = document.getElementById('newPassword').value.trim();
	const user=document.getElementById('newDamdang').value.trim(); 
	const phone=document.getElementById('phone-new').value.trim(); 
    
    // 입력 유효성 검사
	if (!user) {
      alert('사용자를 입력해주세요.');
      document.getElementById('newDamdang').focus();
      return;
    }
	if (!phone) {
      alert('사용자 핸드폰번호를 입력해주세요.');
      document.getElementById('phone-new').focus();
      return;
    }
    if (!newId) {
      alert('ID를 입력해주세요.');
      document.getElementById('newId').focus();
      return;
    }

	if(document.getElementById('idExplain').textContent != '사용할 수 있는 ID 입니다'){
		alert('ID 중복검사하세요!!.');
      document.getElementById('newId').focus();
      return;
    }
	
    
    if (!newPassword) {
      alert('비밀번호를 입력해주세요.');
      document.getElementById('newPassword').focus();
      return;
    }
    
    // 비밀번호 길이 검사 (최소 4자 이상)
    if (newPassword.length < 8) {
      alert('비밀번호는 최소 4자 이상이어야 합니다.');
      document.getElementById('newPassword').focus();
      return;
    }

	if(document.getElementById('idExplain2').textContent != '유효한 비밀번호 형식입니다.'){
		alert('비밀번호 확인하세요!!.');
      document.getElementById('newPassword').focus();
      return;
    }

    
    // 저장 전 사용자 확인
    if (!confirm('새로운 ID 정보를 저장하시겠습니까?')) {
      return;
    }
    
    // FormData 객체 생성 및 데이터 추가
    const formData = new FormData();
	newDamdang
    formData.append('dNum', dNum);
    formData.append('mem_id', newId);
    formData.append('password', newPassword);
	formData.append('phone', phone);
	formData.append('company', company);
	formData.append('user', user);
    
    
    // API 호출
    const response = await fetch('https://kjstation.kr/api/kjDaeri/id_save.php', {
      method: 'POST',
      body: formData
    });
    
    const result = await response.json();
    
    if (result.success) {
      alert('ID 정보가 성공적으로 저장되었습니다.');
	  document.getElementById('idExplain').textContent='';
	  document.getElementById('idExplain2').textContent='';
      // 저장 후 목록 새로고침
      handleIdClick(dNum);
      
      // 입력 필드 초기화
      //document.getElementById('newId').value = '';
     // document.getElementById('newPassword').value = '';
    } else {
      alert('저장 실패: ' + (result.message || '알 수 없는 오류가 발생했습니다.'));
    }
  } catch (error) {
    console.error('ID 저장 중 오류 발생:', error);
    alert('ID 정보 저장 중 오류가 발생했습니다.');
  }
}
//사용자 핸드폰 함수

function validateAndUpdatePhone(inputElement, num) {
    const explainElement = document.getElementById('idExplain');
    const phone = inputElement.value.trim();
    const idNum = document.getElementById(`idNum-${num}`);
    console.log('num', num);
    console.log('idNum', idNum ? idNum.value : '요소 없음');
    
    // idNum 요소가 없거나 값이 없으면 처리하지 않음
    if (!idNum || !idNum.value) {
        console.log('idNum 요소가 없거나 값이 없습니다.');
        explainElement.textContent = 'ID 번호를 찾을 수 없습니다.';
        explainElement.style.color = 'red';
        return;
    }
    
    // 비밀번호가 비어있는 경우
    if (phone === '') {
        explainElement.textContent = '핸드폰번호를 입력해주세요.';
        explainElement.style.color = 'red';
        inputElement.focus();
        return;
    }
    
    // FormData 객체 생성
    const formData = new FormData();
    formData.append('phone', phone);
    formData.append('idNum', idNum.value);
    
    // 서버로 비밀번호 업데이트 요청 전송
    fetch('https://kjstation.kr/api/kjDaeri/updatePhone.php', {
        method: 'POST',
        body: formData
    })
    .then(response => response.json())
    .then(data => {
        if (data.success) {
            explainElement.textContent = '사용자 핸드폰 번호가 성공적으로 업데이트되었습니다.';
            explainElement.style.color = 'green';
           
            
            // 1초 후 성공 메시지 제거
            setTimeout(() => {
                explainElement.textContent = '';
				document.getElementById('idExplain').textContent='';
				document.getElementById('idExplain2').textContent='';
            }, 1000);
        } else {
            explainElement.textContent = '사용자 핸드폰 번호 업데이트에 실패했습니다: ' + (data.message || '알 수 없는 오류');
            explainElement.style.color = 'red';
        }
    })
    .catch(error => {
        console.error('사용자 핸드폰 번호 업데이트 오류가 발생했습니다:', error);
        explainElement.textContent = '사용자 핸드폰 번호 업데이트 중 오류가 발생했습니다. 다시 시도해주세요.';
        explainElement.style.color = 'red';
    });
}
// 사용자 업데이트 함수
function validateAndUpdateUser(inputElement, num) {
    const explainElement = document.getElementById('idExplain');
    const user = inputElement.value.trim();
    const idNum = document.getElementById(`idNum-${num}`);
    console.log('num', num);
    console.log('idNum', idNum ? idNum.value : '요소 없음');
    
    // idNum 요소가 없거나 값이 없으면 처리하지 않음
    if (!idNum || !idNum.value) {
        console.log('idNum 요소가 없거나 값이 없습니다.');
        explainElement.textContent = 'ID 번호를 찾을 수 없습니다.';
        explainElement.style.color = 'red';
        return;
    }
    
    // 비밀번호가 비어있는 경우
    if (user === '') {
        explainElement.textContent = '사용자번호를 입력해주세요.';
        explainElement.style.color = 'red';
        inputElement.focus();
        return;
    }
    
    // FormData 객체 생성
    const formData = new FormData();
    formData.append('user', user);
    formData.append('idNum', idNum.value);
    
    // 서버로 비밀번호 업데이트 요청 전송
    fetch('https://kjstation.kr/api/kjDaeri/updateUser.php', {
        method: 'POST',
        body: formData
    })
    .then(response => response.json())
    .then(data => {
        if (data.success) {
            explainElement.textContent = '사용자가 성공적으로 업데이트되었습니다.';
            explainElement.style.color = 'green';
           
            
            // 1초 후 성공 메시지 제거
            setTimeout(() => {
                explainElement.textContent = '';
				document.getElementById('idExplain').textContent='';
				document.getElementById('idExplain2').textContent='';
            }, 1000);
        } else {
            explainElement.textContent = '사용자 업데이트에 실패했습니다: ' + (data.message || '알 수 없는 오류');
            explainElement.style.color = 'red';
        }
    })
    .catch(error => {
        console.error('사용자 업데이트 오류가 발생했습니다:', error);
        explainElement.textContent = '사용자 업데이트 중 오류가 발생했습니다. 다시 시도해주세요.';
        explainElement.style.color = 'red';
    });
}
// 비밀번호 길이 유효성 검사 및 메시지 표시 함수
function validatePasswordLength(inputElement) {
    const explainElement = document.getElementById('idExplain2');
    const password = inputElement.value.trim();
    
    if (password.length > 0 && password.length < 8) {
        explainElement.textContent = '비밀번호는 8자리 이상 입력해주세요.';
        explainElement.style.color = 'red';
    } else if (password.length >= 8) {
        explainElement.textContent = '유효한 비밀번호 형식입니다.';
        explainElement.style.color = 'green';
    } else {
        explainElement.textContent = '';
    }
}

// 비밀번호 유효성 검사 및 업데이트 함수
function validateAndUpdatePassword(inputElement, num) {
    const explainElement = document.getElementById('idExplain');
    const password = inputElement.value.trim();
    const idNum = document.getElementById(`idNum-${num}`);
    console.log('num', num);
    console.log('idNum', idNum ? idNum.value : '요소 없음');
    
    // idNum 요소가 없거나 값이 없으면 처리하지 않음
    if (!idNum || !idNum.value) {
        console.log('idNum 요소가 없거나 값이 없습니다.');
        explainElement.textContent = 'ID 번호를 찾을 수 없습니다.';
        explainElement.style.color = 'red';
        return;
    }
    
    // 비밀번호가 비어있는 경우
    if (password === '') {
        explainElement.textContent = '비밀번호를 입력해주세요.';
        explainElement.style.color = 'red';
        inputElement.focus();
        return;
    }
    
    // 비밀번호가 8자 미만인 경우
    if (password.length < 8) {
        explainElement.textContent = '비밀번호는 8자리 이상 입력해주세요.';
        explainElement.style.color = 'red';
        inputElement.focus();
        return;
    }


    
    // FormData 객체 생성
    const formData = new FormData();
    formData.append('password', password);
    formData.append('idNum', idNum.value);
    
    // 서버로 비밀번호 업데이트 요청 전송
    fetch('https://kjstation.kr/api/kjDaeri/updatePassword.php', {
        method: 'POST',
        body: formData
    })
    .then(response => response.json())
    .then(data => {
        if (data.success) {
            explainElement.textContent = '비밀번호가 성공적으로 업데이트되었습니다.';
            explainElement.style.color = 'green';
            inputElement.value = ''; // 입력 필드 초기화
            
            // 1초 후 성공 메시지 제거
            setTimeout(() => {
                explainElement.textContent = '';
				document.getElementById('idExplain').textContent='';
				document.getElementById('idExplain2').textContent='';
            }, 1000);
        } else {
            explainElement.textContent = '비밀번호 업데이트에 실패했습니다: ' + (data.message || '알 수 없는 오류');
            explainElement.style.color = 'red';
        }
    })
    .catch(error => {
        console.error('비밀번호 업데이트 중 오류가 발생했습니다:', error);
        explainElement.textContent = '비밀번호 업데이트 중 오류가 발생했습니다. 다시 시도해주세요.';
        explainElement.style.color = 'red';
    });
}
//문자 발송함수 
async function sms(phone, gisaName) {
  // 이벤트 기본 동작 방지
  if (event) {
    event.preventDefault();  // 기본 a태그 링크 이동 차단
    event.stopPropagation(); // 부모 이벤트 전파 차단
  }
  
  // 모달 초기 설정
  document.getElementById("gisa").textContent = gisaName;
  document.getElementById("smsReceiver").value = phone;
  document.getElementById("kj-sms-modal").style.display = "block";
  
  try {
    // API 호출을 위한 FormData 준비
    const formData = new FormData();
    formData.append('phone', phone);
    
    // 서버 API 호출
    const response = await fetch('https://kjstation.kr/api/kjDaeri/smsSearch.php', {
      method: "POST",
      body: formData // FormData 자동으로 Content-Type 설정
    });
    
    // 응답 처리
    const result = await response.json();
    if (result.success && result.data && result.data.length > 0) {
      console.log('SMS 데이터:', result);
      
      // 메시지 텍스트 설정
      //document.getElementById("sendList").textContent = result.data[0].Msg;
      document.getElementById('smsMessage').value= result.data[0].Msg;
      // LastTime 포맷팅 (20250318130230 → 2025-03-18 13:02:30)
      const lastTime = result.data[0].LastTime;
      if (lastTime && lastTime.length === 14) {
        const formattedTime = `${lastTime.slice(0, 4)}-${lastTime.slice(4, 6)}-${lastTime.slice(6, 8)} ${lastTime.slice(8, 10)}:${lastTime.slice(10, 12)}:${lastTime.slice(12, 14)}`;
        document.getElementById("sendDate").textContent = "발송일자"+formattedTime;
      }
    } else {
      document.getElementById("sendList").textContent = "메시지가 없습니다.";
      document.getElementById("lastTimeSpan").textContent = "";
      console.log('결과 없음:', result);
    }
  } catch (error) {
    console.error("상태 업데이트 실패:", error);
    alert("상태 업데이트 실패.");
  }
  
  // 전화번호 입력 형식 자동 변환 이벤트 설정
  const phoneInput = document.getElementById("smsReceiver");
  phoneInput.addEventListener('input', function(e) {
    let value = e.target.value.replace(/[^0-9]/g, '');
    
    // 전화번호 형식(000-0000-0000) 자동 변환
    if (value.length > 3 && value.length <= 7) {
      value = value.substring(0, 3) + '-' + value.substring(3);
    } else if (value.length > 7) {
      value = value.substring(0, 3) + '-' + value.substring(3, 7) + '-' + value.substring(7);
    }
    
    e.target.value = value;
  });
}
/**
 * smskind 선택에 따라 smsMessage 내용을 자동으로 채우는 함수
 */
function updateSmsMessage() {
    // 선택된 SMS 종류 값 가져오기
    const smsKind = document.getElementById('smskind').value;
    
    // 메시지 내용을 입력할 텍스트 영역 가져오기
    const messageField = document.getElementById('smsMessage');
    
    // 선택된 값에 따라 다른 메시지 설정
    switch(smsKind) {
        case '1': // 주민번호 입력요청
            messageField.value = '[안내] 고객님의 주민등록번호를 입력해 주시기 바랍니다. 본인 확인 후 서비스 이용이 가능합니다.';
            break;
        case '2': // 다른 종류의 메시지 1
            messageField.value = '[알림] 고객님의 인증이 필요합니다. 아래 링크를 통해 인증을 완료해 주세요.';
            break;
        case '3': // 다른 종류의 메시지 2
            messageField.value = '[공지] 시스템 점검으로 인해 오늘 밤 11시부터 내일 오전 2시까지 서비스 이용이 제한됩니다. 양해 부탁드립니다.';
            break;
        case '4': // 다른 종류의 메시지 3
            messageField.value = '[이벤트] 고객님을 위한 특별 할인 이벤트가 진행 중입니다. 자세한 내용은 홈페이지를 확인해 주세요.';
            break;
        case '5': // 다른 종류의 메시지 4
            messageField.value = '[결제 완료] 고객님의 결제가 정상적으로 처리되었습니다. 이용해 주셔서 감사합니다.';
            break;
        default: // 선택되지 않은 경우
            messageField.value = ''; // 빈 문자열로 초기화
            break;
    }
}

// 페이지 로드 시 이벤트 리스너 등록
document.addEventListener('DOMContentLoaded', function() {
    // smskind 선택 요소에 변경 이벤트 리스너 추가
    const smsKindSelect = document.getElementById('smskind');
    if (smsKindSelect) {
        smsKindSelect.addEventListener('change', updateSmsMessage);
    }
    
    // 초기 로드 시 기본 메시지 설정 (선택된 값이 있는 경우)
    updateSmsMessage();
});
async function sendSms() {
    const receiver = document.getElementById('smsReceiver').value.trim();
    const message = document.getElementById('smsMessage').value.trim();

    if (receiver === '' ) {
        alert('수신번호 입력해주세요.');
        return;
    }

	if ( message === '') {
        alert(' 메시지를  입력해주세요.');
        return;
    }

    // 실제 전송 로직 (Ajax 등) 추가
   try {
        // FormData 객체 생성
        const formData = new FormData();
        formData.append('receiver', receiver);
        formData.append('message', message);
        formData.append('userName', SessionManager.getUserInfo().name);
        
        const response = await fetch(`https://kjstation.kr/api/kjDaeri/smsSend.php`, {
            method: "POST",
            // Content-Type 헤더를 지정하지 않음 - FormData가 자동으로 설정함
            body: formData // JSON 대신 FormData 사용
        });
        
        const result = await response.json();
        if (result.success) {
            alert("상태가 변경되었습니다.");
            // kjEndorse(); // 상태 변경 후 목록 새로고침
        } else {
            alert(result.error || "상태 변경 중 오류 발생.");
        }
    } catch (error) {
        console.error("상태 업데이트 실패:", error);
        alert("상태 업데이트 실패.");
    }

    
}

async function kjEndorse(endorse_day, dNum, cNum, pNum) { 
    const loadingElement = document.getElementById("loading-spinner");
    if (loadingElement) loadingElement.style.display = "block";
	document.getElementById('smsContents').value='';
    try {
        const response = await fetch(`https://kjstation.kr/api/kjDaeri/get_endorse_details.php?endorse_day=${endorse_day}&dNum=${dNum}&cNum=${cNum}&pNum=${pNum}`);
        const data = await response.json();

        if (!data.success) {
            alert(data.error);
            return;
        }

        // 데이터 업데이트
        document.getElementById("daeri_1").textContent = data.company;
        document.getElementById("daeri_2").textContent = data.Pname;
        document.getElementById("daeri_3").textContent = data.hphone;

		document.getElementById("d_hphone").value=data.hphone; // 문자 보내기 위해
		
        const damdangaType = { "1": "오성준", "2": "이근재" };
        document.getElementById("daeri_4").textContent = damdangaType[data.MemberNum] || "";

        document.getElementById("daeri_9").textContent = data.policyNum;
        document.getElementById("daeri_10").textContent = data.startyDay;

        const diviType = { "1": "10회분납", "2": "월납" };
        document.getElementById("daeri_11").textContent = diviType[data.divi];

        const icompanyType = { "1": "흥국", "2": "DB", "3": "KB", "4": "현대", "5": "현대", "6": "롯데", "7": "MG", "8": "삼성" };
		 document.getElementById("daeri_12").textContent = icompanyType[data.InsuraneCompany] ;

        document.getElementById("daeri_13").textContent = data.FirstStart ;

		

		//daeri_13
        console.log(data);
		
        // 배서인원 목록 생성
		//<select class="kje-status-rate" data-id="${item.num}" id='rateP-${item.num}' onchange="handleRateChangeSj(this, '${item.num}','${item.Jumin}','${item.dongbuCerti}')">
        let e_list = ""; 
        data.data.forEach((item, index) => {
            console.log('item.push', item.Name, item.push);
              const rateOptions=`
                        <select class="kje-status-rate" data-id="${item.num}" id='rateP-${item.num}' >//바탕화면에서만 요율 변동 가능
                            <option value="-1" >선택</option>                            
                            <option value="1" ${item.rate == 1 ? "selected" : ""}>1</option> //가입경력 1년 미만 3년간 사고건수 1년간 사고건수 무사고 경역
                            <option value="2" ${item.rate == 2 ? "selected" : ""}>0.9</option>
                            <option value="3" ${item.rate == 3 ? "selected" : ""}>0.925</option>//가입경력 1년 이상 3년간 사고건수 0건 1년간 사고건수 0건 무사고  1년 이상
                            <option value="4" ${item.rate == 4 ? "selected" : ""}>0.898</option>//가입경력 1년 이상 3년간 사고건수 0건 1년간 사고건수 0건 무사고  2년 이상
                            <option value="5" ${item.rate == 5 ? "selected" : ""}>0.889</option>////가입경력 1년 이상 3년간 사고건수 0건 1년간 사고건수 0건 무사고  3년 이상
                            <option value="6" ${item.rate == 6 ? "selected" : ""}>1.074</option>//가입경력 1년 이상 3년간 사고건수 1건 1년간 사고건수 0건
                            <option value="7" ${item.rate == 7 ? "selected" : ""}>1.085</option>//가입경력 1년 이상 3년간 사고건수 1건 1년간 사고건수 1건
                            <option value="8" ${item.rate == 8 ? "selected" : ""}>1.242</option>//가입경력 1년 이상 3년간 사고건수 2건 1년간 사고건수 0건
                            <option value="9" ${item.rate == 9 ? "selected" : ""}>1.253</option>//가입경력 1년 이상 3년간 사고건수 2건 1년간 사고건수 1건
                            <option value="10" ${item.rate == 10 ? "selected" : ""}>1.314</option>//가입경력 1년 이상 3년간 사고건수 2건 1년간 사고건수 2건
                            <option value="11" ${item.rate == 11 ? "selected" : ""}>1.428</option>////가입경력 1년 이상 3년간 사고건수 3건이상 1년간 사고건수 0건
                            <option value="12" ${item.rate == 12 ? "selected" : ""}>1.435</option>////가입경력 1년 이상 3년간 사고건수 3건이상 1년간 사고건수 1건
                            <option value="13" ${item.rate == 13 ? "selected" : ""}>1.447</option>////가입경력 1년 이상 3년간 사고건수 3건이상 1년간 사고건수 2건
                            <option value="14" ${item.rate == 14 ? "selected" : ""}>1.459</option>////가입경력 1년 이상 3년간 사고건수 3건이상 1년간 사고건수 3건 이상 
                        </select>
                    `
            let pushOptions = ""; 
 //const pushType={"1":"청약","2":"해지","3":"청약거절","4":"정상","5":"해지취소","6":"청약취소"}
            // 푸시 상태 변경 셀렉트 박스 클래스명 변경
				if (item.push == 4) { // 해지
					pushOptions = `
						<select class="kje-push-status" data-id="${item.num}" id='pushP1-${item.num}' 
								onchange="updateSmsContent(this, '${item.Name}', ${item.num})">                           
							<option value="4" ${item.push == 4 ? "selected" : ""}>해지</option> 
							<option value="5" ${item.push == 5 ? "selected" : ""}>취소</option> 
						</select>
					`;
				} else if (item.push == 1) { // 청약
					pushOptions = `
						<select class="kje-push-status" data-id="${item.num}" id='pushP1-${item.num}' 
								onchange="updateSmsContent(this, '${item.Name}', ${item.num})">                           
							<option value="1" ${item.push == 1 ? "selected" : ""}>청약</option> 
							<option value="6" ${item.push == 6 ? "selected" : ""}>취소</option> 
							<option value="3" ${item.push == 3 ? "selected" : ""}>거절</option>
						</select>
					`;
				}



            // 증권의 성격 매핑
            const iType = { "1": "대리", "2": "탁송", "3": "대리렌트", "4": "탁송렌트" };
            const certiType = iType[item.etag] || "알 수 없음";

            // pushOptions 값을 안전하게 인코딩
            const encodedPushOptions = encodeURIComponent(pushOptions);

            // 처리 상태 변경 드롭다운 (handleStatusChangeKj 함수에 안전하게 값 전달)
           // HTML 생성 부분 수정
					// 처리 상태 변경 셀렉트 박스
					const chOptions = `
						<select class="kje-status-ch" data-id="${item.num}" data-original="${item.ch}">                           
							<option value="1" ${item.ch == 1 ? "selected" : ""}>미처리</option> 
							<option value="2" ${item.ch == 2 ? "selected" : ""}>처리</option> 
						</select>
					`;

					
			const formattedPreiminum = item.Endorsement_insurance_premium ? parseFloat(item.Endorsement_insurance_premium).toLocaleString("en-US") : "0";
			const formattedPreiminum2 = item.Endorsement_insurance_company_premium ? parseFloat(item.Endorsement_insurance_company_premium).toLocaleString("en-US") : "0";
            e_list += `<tr>
                <td>${index + 1}</td>
                <td>${item.Name}(${item.nai})</td>
                <td>${item.Jumin}</td>
                <td>${certiType}</td>
				<td><input type='text' id='status-reasion-${item.num}' value="${item.reasion}" class='geInput2'></td>
				<td>${rateOptions}</td>
                <td>${pushOptions}</td>
				<td>${chOptions}</td>
				<td class="kje-preiminum">${formattedPreiminum}</td>
				<td class="kje-preiminum">${formattedPreiminum2}</td>
            </tr>`;
        });

        document.getElementById("e_list").innerHTML = e_list;
		document.getElementById("gijun2").innerHTML="배서일"+ endorse_day;
		let gijun_list = ""; 
		gijun_list =` <button class="kje-endorse-button" onclick="endorseDateChange('${endorse_day}', '${dNum}', '${cNum}', '${pNum}')" id="endorseGijunDay">배서기준일 변경
					 </button> <button class="kje-endorse-button" onclick="sms_List('${dNum}','${data.hphone}','1')" >문자리스트</button>
					`;

			 document.getElementById("gijun").innerHTML = gijun_list;
		
        // 모달 표시
        document.getElementById("kj-endose1-modal").style.display = "block";

    } catch (error) {
        console.error("데이터 로드 실패:", error);
        alert("데이터 로드 실패.");
    } finally {
        if (loadingElement) loadingElement.style.display = "none";
    }
}
document.addEventListener('DOMContentLoaded', function() {
    // 처리 상태 변경 이벤트 리스너
    document.body.addEventListener('change', function(event) {
        const selectElement = event.target;
        
        // 상태 변경 셀렉트 박스인지 확인
        if (selectElement.classList.contains('kje-status-ch')) {
            // 데이터 속성에서 ID 가져오기
            const num = selectElement.dataset.id;
            
            // 안전하게 비동기 처리 시작
            handleStatusChangeKj(selectElement, num);
        }
        
        // 푸시 상태는 이미 updateSmsContent 함수가 처리하고 있으므로 추가 처리 필요 없음
    });
});
// 청약 취소 또는 해지 취소  변경에 따른 SMS 내용 업데이트 함수
function updateSmsContent(selectElement, itemName, itemNum) {
	console.log('itemNum',itemNum);
    const selectedValue = selectElement.value;
    const smsInput = document.getElementById('smsContents');
    
    // 선택된 값에 따라 메시지 설정
    if (selectedValue == '5') {
        smsInput.value = `${itemName} 님 해지 취소 요청으로 해지를 취소합니다..`;
    } else if (selectedValue == '6') {
        smsInput.value = `${itemName} 님 청약 취소 요청으로 청약을 취소합니다.`;
    } else if (selectedValue == '3') {
        const reasonElement = document.getElementById(`status-reasion-${itemNum}`);
		
        const reason = reasonElement ? reasonElement.value : '불명확한 사유';
        smsInput.value = `${itemName}님 ${reason} 사유로 거절되어 취소합니다. 죄송합니다.`;
    } else if (selectedValue == '1') {
		
		smsInput.value = ``;
	}
}


/**
 * 진행단계 처리 함수
 * @param {HTMLElement} selectElement - 진행단계
 * @param {string}  - 2012DaeriMember  num
 */
async function handleProgress(selectElement, num){

	 // UI 즉시 응답을 위한 처리
    selectElement.disabled = true;
    
    // 로딩 표시를 위한 요소 추가
    const row = selectElement.closest('tr');
    const loadingIndicator = document.createElement('span');
    loadingIndicator.className = 'loading-indicator';
    loadingIndicator.textContent = ' 처리 중...';
    selectElement.parentNode.appendChild(loadingIndicator);
    
    // UI 정리 함수
    function cleanupUI() {
        selectElement.disabled = false;
        if (loadingIndicator && loadingIndicator.parentNode) {
            loadingIndicator.parentNode.removeChild(loadingIndicator);
        }
    }
    
    // 요율 검증
    const progressElement = document.getElementById(`progress-${num}`);
    if (!progressElement) {
        alert('요율 요소를 찾을 수 없습니다.');
        cleanupUI();
        return false;
    }
    
    const progress = progressElement.value;
    if (progress < 0) {
        alert('개인 요율부터 입력하세요');
        progressElement.focus();
        cleanupUI();
        return false;
    }
    
    // 사용자 확인
    if (!confirm(`진행단계가 맞습니까?`)) {
        cleanupUI();
        return;
    }
    
    // API 요청에 타임아웃 설정
    const controller = new AbortController();
    const timeoutId = setTimeout(() => controller.abort(), 10000); // 10초 후 중단
    
    try {
        // FormData 객체 생성
        const formData = new FormData();
        formData.append('num', num);
        formData.append('progress', progress);
        formData.append('userName', SessionManager.getUserInfo().name);
        
        const response = await fetch(`https://kjstation.kr/api/kjDaeri/changeProgress.php`, {
            method: "POST",
            body: formData,
            signal: controller.signal
        });
        
        clearTimeout(timeoutId); // 타임아웃 해제
        
        // 응답 처리
        if (!response.ok) {
            throw new Error(`서버 응답 오류: ${response.status}`);
        }
        
        const result = await response.json();
        
        if (result.success) {
            // 성공 시 UI 업데이트
           /* const statusCell = row.querySelector('.status-cell');
            if (statusCell) {
                statusCell.textContent = selectElement.value == 2 ? "처리됨" : "미처리";
                
            }*/
			 alert(result.message);
			const p_managerElement = document.getElementById(`p_manager-${num}`);
			p_managerElement.textContent = result.manager;
           
			
        } else {
            alert(result.error || "상태 변경 중 오류가 발생했습니다.");
        }
    } catch (error) {
        if (error.name === 'AbortError') {
            alert('요청 시간이 초과되었습니다. 나중에 다시 시도해주세요.');
        } else {
            console.error("상태 업데이트 실패:", error);
            alert(`상태 업데이트 실패: ${error.message}`);
        }
    } finally {
        cleanupUI(); // 항상 UI 정리
    }
}
// 배서기준일 변경 모달 표시 함수
async function endorseDateChange(endorse_day, dNum, cNum, pNum) {
    console.log("endorse_day", endorse_day, dNum, cNum, pNum);
    
    try {
        // GET 요청에는 body 대신 URL 쿼리 파라미터 사용
        const response = await fetch(`https://kjstation.kr/api/kjDaeri/get_2012CertiTable_details2.php?cNum=${cNum}`);
        const data = await response.json();
        if (!data.success) {
            alert(data.error);
            return;
        }
        console.log(data.data);

        if (data.success) {
			document.getElementById("date_daeriCompany").innerHTML=data.company+"["+data.policyNum+"]";
            // 현재 배서일 설정
            document.getElementById("before_endorseDay").value = endorse_day;
            
            // 배서기준일 변경 버튼 생성
            let eButton = `<button class="kje-endorse-button" 
                          onclick="endorseDateSend('${endorse_day}', ${dNum}, ${cNum}, ${pNum})">배서기준일 변경</button>`;
            
            document.getElementById("endorseChange").innerHTML = eButton;
            document.getElementById("kj-date-modal").style.display = "block";
        } else {
            // 더 자세한 오류 메시지 제공
            const errorMessage = data.data.error || "알 수 없는 오류가 발생했습니다.";
            alert(`배서기준일 변경 처리 중 오류가 발생했습니다: ${errorMessage}`);
        }
    } catch (error) {
        console.error("배서 날짜 변경 중 오류 발생:", error);
        alert(`배서기준일 변경 처리 중 예외가 발생했습니다: ${error.message}`);
    }
}

// 배서기준일 변경 전송 함수
async function endorseDateSend(endorse_day, dNum, cNum, pNum) {
    // 여기에 배서기준일 변경 로직 구현
    const after_day = document.getElementById("after_endorseDay").value;
   
    if (!after_day) {
        alert('변경 후 배서기준일 선택하세요.');
        document.getElementById("after_endorseDay").focus();
        return false;
    }
     try {
        // FormData 객체 생성
        const formData = new FormData();
		formData.append('after_day',after_day);
        formData.append('pNum',pNum);
		formData.append('cNum',cNum);
        formData.append('userName', SessionManager.getUserInfo().name);
        
        const response = await fetch(`https://kjstation.kr/api/kjDaeri/endoreDateChange.php`, {
            method: "POST",
            body: formData,
          });
        
        
        
        // 응답 처리
        if (!response.ok) {
            throw new Error(`서버 응답 오류: ${response.status}`);
        }
        
        const result = await response.json();
        
        if (result.success) {
           
			
			
        } else {
            alert(result.error || "상태 변경 중 오류가 발생했습니다.");
        }
    } catch (error) {
        if (error.name === 'AbortError') {
            alert('요청 시간이 초과되었습니다. 나중에 다시 시도해주세요.');
        } else {
            console.error("상태 업데이트 실패:", error);
            alert(`상태 업데이트 실패: ${error.message}`);
        }
    }
    // 모달 닫기
    document.getElementById("kj-date-modal").style.display = "none";
	kjEndorse(after_day, dNum, cNum, pNum)
}

/**
 * SMS 목록을 조회하고 표시하는 함수
 * @param {string} dNum - 대리점 번호
 * @param {string} hphone - 핸드폰 번호
 */
async function sms_List(dNum, hphone, sort) {
  //1 이면 핸드폰 번호로
  //2 이면 현재일자부터
  //3 이면 대리운전회사 dnum
  // SMS 목록 컨테이너 HTML 구성
  console.log('정렬', sort);
  console.log('대리운전회사 정보', dNum);
  
  // dNum에 콤마가 있는지 확인 (번호,회사명 형식)
  let companyNum = dNum;
  let companyName = '';
  
  if (dNum && dNum.includes(',')) {
    const dNumParts = dNum.split(',');
    companyNum = dNumParts[0];
    companyName = dNumParts[1] || '';
  }
  
  // 오늘 날짜와 7일 전 날짜 계산
  const today = new Date();
  const todayStr = today.getFullYear() + '-' + String(today.getMonth() + 1).padStart(2, '0') + '-' + String(today.getDate()).padStart(2, '0');
  const weekAgo = new Date();
  weekAgo.setDate(today.getDate() - 7);
  const weekAgoStr = weekAgo.toISOString().split('T')[0];
  
  // sort 값에 따라 다른 검색 필드 표시
  let searchField = '';
  if (sort == 1 || sort == 3 || !sort) {
    // 핸드폰 번호나 회사명 입력 필드
    const placeholder = sort == 3 ? '회사명' : '핸드폰번호';
    const oninput = sort == 1 ? 'oninput="formatPhoneNumber(this)"' : '';
    let defaultValue = '';
    
    // sort가 3이고 회사명이 있으면 회사명을 기본값으로 설정
    if (sort == 3 && companyName) {
      defaultValue = companyName;
    } else {
      defaultValue = hphone || "";
    }
    
    searchField = `<input type='text' id='smsBunho' placeholder='${placeholder}' ${oninput} autocomplete="off" value='${defaultValue}'>`;
  } else if (sort == 2) {
    // 날짜 구간 선택 필드
    searchField = `
      <input type='date' id='smsStartDate' class='date-range-field' value='${weekAgoStr}'>
      <input type='date' id='smsEndDate' class='date-range-field' value='${todayStr}'>
    `;
  }
  
  let smsList_Contents = `
    <div class="sms-list-container">
      <!-- 검색 영역 -->
      <div class="sms-list-header">
        <div class="sms-left-area">
          <div class="sms-search-area">
            <select id='smsSort' onChange='smsSort()'>
              <option value="선택">선택</option>
              <option value="1" ${sort == 1 ? "selected" : ""}>핸드폰</option>
              <option value="2" ${sort == 2 ? "selected" : ""}>기간선택</option>
              <option value="3" ${sort == 3 ? "selected" : ""}>회사명</option>
            </select>
            ${searchField}
            <button class="sms-stats-button" onclick="requestSmsBySort(document.getElementById('smsSort').value, '${companyNum}')">검색</button>
          </div>
        </div>
      </div>
    </div>
  `;
  
  // HTML 내용 삽입
  document.getElementById("smsList_daeriCompany").innerHTML = smsList_Contents;
  
  // 모달 표시
  document.getElementById("kj-smsList-modal").style.display = "block";
  
  // 초기 데이터 로드
  if (sort == 2) {
    await smsRequest(`${weekAgoStr},${todayStr}`, sort, 1, companyNum);
  } else if (sort == 3) {
    // 회사명으로 검색할 때는 companyNum을 dnum 파라미터로 전달
    await smsRequest(companyName || hphone, sort, 1, companyNum);
  } else {
    await smsRequest(hphone, sort, 1, companyNum);
  }
}

/**
 * SMS 정렬 방식이 변경되었을 때 호출되는 함수
 */
function smsSort() {
  // 선택된 정렬 방식 가져오기
  const sortValue = document.getElementById('smsSort').value;
  
  // 검색 영역 컨테이너 참조
  const searchArea = document.querySelector('.sms-search-area');
  
  // 기존 입력 필드 제거
  const existingInput = document.getElementById('smsBunho');
  if (existingInput) existingInput.remove();
  
  const existingDateFields = document.querySelectorAll('.date-range-field');
  existingDateFields.forEach(field => field.remove());
  
  // 오늘 날짜와 7일 전 날짜 계산
  const today = new Date();
  const todayStr = today.getFullYear() + '-' + String(today.getMonth() + 1).padStart(2, '0') + '-' + String(today.getDate()).padStart(2, '0');
  const weekAgo = new Date();
  weekAgo.setDate(today.getDate() - 7);
  const weekAgoStr = weekAgo.toISOString().split('T')[0];
  
  // 정렬 방식에 따라 UI 변경 및 데이터 요청
  if (sortValue === "1") {
    // 핸드폰 번호로 검색 - 핸드폰 입력란 추가
    const phoneInput = document.createElement('input');
    phoneInput.type = 'text';
    phoneInput.id = 'smsBunho';
    phoneInput.placeholder = '핸드폰번호';
    phoneInput.setAttribute('oninput', 'formatPhoneNumber(this)');
    phoneInput.setAttribute('autocomplete', 'off');
    
    // 검색 버튼 앞에 추가
    searchArea.insertBefore(phoneInput, searchArea.querySelector('button'));
    
    // 핸드폰 번호로 검색 실행
    smsRequest("", 1);
  } 
  else if (sortValue === "2") {
    // 기간으로 검색 - 날짜 선택 UI 추가
    // 시작 날짜 필드 생성
    const startDateField = document.createElement('input');
    startDateField.type = 'date';
    startDateField.id = 'smsStartDate';
    startDateField.className = 'date-range-field';
    startDateField.value = weekAgoStr;
    
    // 종료 날짜 필드 생성
    const endDateField = document.createElement('input');
    endDateField.type = 'date';
    endDateField.id = 'smsEndDate';
    endDateField.className = 'date-range-field';
    endDateField.value = todayStr;
    
    // 날짜 선택 필드를 검색 버튼 앞에 추가
    searchArea.insertBefore(startDateField, searchArea.querySelector('button'));
    searchArea.insertBefore(endDateField, searchArea.querySelector('button'));
    
    // 날짜 범위로 검색 실행
    smsRequest(`${weekAgoStr},${todayStr}`, 2,"","");
  } 
  else if (sortValue === "3") {
    // 회사명으로 검색 - 회사명 입력란 추가
    const companyInput = document.createElement('input');
    companyInput.type = 'text';
    companyInput.id = 'smsBunho';
    companyInput.placeholder = '회사명';
    companyInput.setAttribute('autocomplete', 'off');
    
    // 검색 버튼 앞에 추가
    searchArea.insertBefore(companyInput, searchArea.querySelector('button'));
    
    // 회사명으로 검색 실행
    smsRequest("", 3);
  }
}

async function smsRequest(hphone, sort = '1', page = 1, dnum = '') {
  console.log('정렬', sort);
  console.log('핸드폰', hphone);
  console.log('회사번호', dnum);
  
  // 로딩 표시 추가
  const smsListElement = document.getElementById("m_smsList");
  if (!smsListElement) {
    console.error("m_smsList 요소를 찾을 수 없습니다.");
    return;
  }
  
  smsListElement.innerHTML = `
    <tr>
      <td colspan='4' style="text-align: center; padding: 20px;">
        <div class="loading-spinner">
          <div class="spinner"></div>
          <p>데이터를 불러오는 중입니다...</p>
        </div>
      </td>
    </tr>
  `;
  
  // CSS 스타일 추가 (한 번만 추가되도록)
  if (!document.getElementById('loading-spinner-style')) {
    const style = document.createElement('style');
    style.id = 'loading-spinner-style';
    style.textContent = `
      .loading-spinner {
        display: flex;
        flex-direction: column;
        align-items: center;
        justify-content: center;
        width: 100%;
      }
      .spinner {
        border: 4px solid rgba(0, 0, 0, 0.1);
        width: 36px;
        height: 36px;
        border-radius: 50%;
        border-left-color: #8E6C9D;;
        animation: spin 1s linear infinite;
        margin-bottom: 10px;
      }
	  .pagination {
        display: flex;
        justify-content: center;
        margin-top: 20px;
        gap: 5px;
      }
      .pagination button {
        padding: 5px 10px;
        border: 1px solid #ddd;
        background-color: #f8f8f8;
        cursor: pointer;
        border-radius: 3px;
      }
      .pagination button.active {
        background-color: #8E6C9D;;
        color: white;
        border-color: #8E6C9D;;
      }
      .pagination button:hover:not(.active) {
        background-color: #e9e9e9;
      }
      .pagination button:disabled {
        opacity: 0.5;
        cursor: not-allowed;
      }
      @keyframes spin {
        0% { transform: rotate(0deg); }
        100% { transform: rotate(360deg); }
      }
      
    `;
    document.head.appendChild(style);
  }
  
  try {
    // FormData 객체 생성
    const formData = new FormData();
    
    // sort 값에 따라 다른 파라미터 추가
    if (sort === '1') {
      // 핸드폰 번호 가져오기 (파라미터가 없는 경우 입력 필드에서 가져옴)
      let phoneNumber = hphone || '';
      
      if (!phoneNumber) {
        const phoneElement = document.getElementById("smsBunho");
        if (phoneElement) {
          phoneNumber = phoneElement.value;
        }
      }
      
      // sort가 1인 경우에만 핸드폰 번호 체크
      if (!phoneNumber || phoneNumber.trim() === '') {
        smsListElement.innerHTML = `
          <tr>
            <td colspan='4' style="text-align: center; padding: 20px;">
              핸드폰 번호를 입력해주세요.
            </td>
          </tr>
        `;
        return;
      }
      
      formData.append('phone', phoneNumber);
    } else if (sort === '2') {
      // 날짜 구간 선택 처리
      let dateRange;
      
      if (hphone && hphone.includes(',')) {
        // 날짜 범위가 파라미터로 전달된 경우 (시작일,종료일 형식)
        dateRange = hphone;
      } else {
        // 입력 필드가 있으면 값 가져오기
        const startDateElement = document.getElementById("smsStartDate");
        const endDateElement = document.getElementById("smsEndDate");
        
        if (startDateElement && endDateElement) {
          const startDate = startDateElement.value;
          const endDate = endDateElement.value;
          
          // 날짜 입력 확인
          if (!startDate || !endDate) {
            smsListElement.innerHTML = `
              <tr>
                <td colspan='4' style="text-align: center; padding: 20px;">
                  시작일과 종료일을 모두 선택해주세요.
                </td>
              </tr>
            `;
            return;
          }
          
          dateRange = `${startDate},${endDate}`;
        } else {
          // 입력 필드가 없으면 기본값 사용
          const today = new Date();
          const todayStr = today.getFullYear() + '-' + 
 String(today.getMonth() + 1).padStart(2, '0') + '-' +String(today.getDate()).padStart(2, '0');
          const weekAgo = new Date();
          weekAgo.setDate(today.getDate() - 7);
          const weekAgoStr = weekAgo.toISOString().split('T')[0];
          
          dateRange = `${weekAgoStr},${todayStr}`;
        }
      }
      
      formData.append('dateRange', dateRange);
    } else if (sort === '3') {
      // 회사명/대리운전회사 번호 확인
      let companyValue = hphone || '';
      let companyNum = dnum || '';
      
      // FormData에 적절한 파라미터 추가
      if (companyValue && companyValue.trim() !== '') {
        // 회사명이 있으면 company 파라미터로 전달
        formData.append('company', companyValue);
      } else if (companyNum && companyNum.trim() !== '') {
        // 회사명이 없고 회사번호가 있으면 dnum 파라미터로 전달
        formData.append('dnum', companyNum);
      } else {
        // 둘 다 없는 경우 오류 메시지 표시
        smsListElement.innerHTML = `
          <tr>
            <td colspan='4' style="text-align: center; padding: 20px;">
              회사명을 입력해주세요.
            </td>
          </tr>
        `;
        return;
      }
    }
    
    // 공통 파라미터
    formData.append('sort', sort);
    formData.append('page', page);
    
    // API 요청 전송
    const response = await fetch(`https://kjstation.kr/api/kjDaeri/smsSearch.php`, {
      method: "POST",
      body: formData,
    });
    
    // 응답 상태 확인
    if (!response.ok) {
      throw new Error(`서버 응답 오류: ${response.status}`);
    }
    
    // JSON 응답 파싱
    const result = await response.json();
    
    // 데이터 처리
    let m_smsList = '';
    
    if (result.success && result.data && result.data.length > 0) {
      console.log('SMS 데이터:', result);
      
      // 페이지네이션 설정
      const itemsPerPage = 10; // 페이지당 표시할 항목 수
      const totalItems = result.data.length;
      const totalPages = Math.ceil(totalItems / itemsPerPage);
      
      // 현재 페이지가 범위를 벗어나면 조정
      const currentPage = Math.max(1, Math.min(page, totalPages));
      
      // 현재 페이지에 표시할 항목 계산
      const startIndex = (currentPage - 1) * itemsPerPage;
      const endIndex = Math.min(startIndex + itemsPerPage, totalItems);
      const currentPageItems = result.data.slice(startIndex, endIndex);
      
      // 현재 페이지 데이터 행 추가
      currentPageItems.forEach((item, index) => {
        // 날짜 형식 변환 (YYYYMMDDHHMMSS -> YYYY-MM-DD HH:MM:SS)
        const lastTime = item.LastTime || '';
        let formattedDate = '';
        
        if (lastTime.length === 14) {
          formattedDate = `${lastTime.substring(0, 4)}-${lastTime.substring(4, 6)}-${lastTime.substring(6, 8)} ${lastTime.substring(8, 10)}:${lastTime.substring(10, 12)}:${lastTime.substring(12, 14)}`;
        } else {
          formattedDate = lastTime;
        }
        
        // 전화번호 조합
        const phone = `${item.Rphone1}-${item.Rphone2}-${item.Rphone3}`;
        
        // 행 추가 (실제 순번은 전체 데이터 기준)
        m_smsList += `
          <tr>
            <td>${startIndex + index + 1}</td>
            <td>${formattedDate}</td>
            <td>${item.Msg}</td>
            <td>${phone}</td>
          </tr>
        `;
      });
      
      // 테이블에 데이터 삽입
      smsListElement.innerHTML = m_smsList;
      
      // 인수 전달 수정 - sort 값에 따라 다른 파라미터 전달
      let searchParam;
      if (sort === '1') {
        searchParam = hphone;
        if (!searchParam) {
          const phoneElement = document.getElementById("smsBunho");
          if (phoneElement) {
            searchParam = phoneElement.value;
          }
        }
      } else if (sort === '2') {
        if (hphone && hphone.includes(',')) {
          searchParam = hphone;
        } else {
          const startDateElement = document.getElementById("smsStartDate");
          const endDateElement = document.getElementById("smsEndDate");
          
          if (startDateElement && endDateElement && 
              startDateElement.value && endDateElement.value) {
            searchParam = `${startDateElement.value},${endDateElement.value}`;
          } else {
            // 입력 필드가 없으면 기본값 사용
            const today = new Date();
            const todayStr = today.getFullYear() + '-' +  String(today.getMonth() + 1).padStart(2, '0') + '-' + 
String(today.getDate()).padStart(2, '0');
            const weekAgo = new Date();
            weekAgo.setDate(today.getDate() - 7);
            const weekAgoStr = weekAgo.toISOString().split('T')[0];
            
            searchParam = `${weekAgoStr},${todayStr}`;
          }
        }
      } else if (sort === '3') {
        searchParam = hphone || dnum;
        if (!searchParam) {
          const companyElement = document.getElementById("smsBunho");
          if (companyElement) {
            searchParam = companyElement.value;
          }
        }
      }
      
      // 페이징 UI 생성 및 추가 - smsCreatePagination 함수 호출
      if (typeof smsCreatePagination === 'function') {
        smsCreatePagination(totalPages, currentPage, searchParam, sort, dnum);
      } else {
        console.error("smsCreatePagination 함수가 정의되지 않았습니다.");
      }
    } else {
      m_smsList = `
        <tr>
          <td colspan='4' style="text-align: center; padding: 20px;">
            발송결과가 없습니다.
          </td>
        </tr>
      `;
      console.log('결과 없음:', result);
      
      // 데이터가 없을 때 페이지네이션 제거
      const existingPagination = document.getElementById("sms-pagination");
      if (existingPagination) {
        existingPagination.remove();
      }
      
      // 테이블에 데이터 삽입
      smsListElement.innerHTML = m_smsList;
    }
  } catch (error) {
    console.error("상태 업데이트 실패:", error);
    
    // 오류 메시지 테이블에 표시
    const smsListElement = document.getElementById("m_smsList");
    if (smsListElement) {
      smsListElement.innerHTML = `
        <tr>
          <td colspan='4'>
            <div style="text-align: center; padding: 20px; color: #d9534f;">
              <i style="font-size: 24px; margin-bottom: 10px;">⚠️</i>
              <p>데이터 로딩 실패: ${error.message}</p>
            </div>
          </td>
        </tr>
      `;
    }
    
    // 오류 발생시 페이지네이션 제거
    const existingPagination = document.getElementById("sms-pagination");
    if (existingPagination) {
      existingPagination.remove();
    }
    
    if (error.name === 'AbortError') {
      alert('요청 시간이 초과되었습니다. 나중에 다시 시도해주세요.');
    } else {
      alert(`상태 업데이트 실패: ${error.message}`);
    }
  }
}

// 페이지네이션 UI 생성 함수
function smsCreatePagination(totalPages, currentPage, searchParam, sort, dnum) {
  // 기존 페이지네이션 제거
  const existingPagination = document.getElementById("sms-pagination");
  if (existingPagination) {
    existingPagination.remove();
  }
  
  // 페이지네이션 컨테이너 생성
  const paginationContainer = document.createElement("div");
  paginationContainer.id = "sms-pagination";
  paginationContainer.className = "pagination";
  
  // 처음 페이지 버튼
  const firstPageBtn = document.createElement("button");
  firstPageBtn.innerHTML = "<<";
  firstPageBtn.disabled = currentPage === 1;
  firstPageBtn.onclick = () => smsRequest(searchParam, sort, 1, dnum);
  paginationContainer.appendChild(firstPageBtn);
  
  // 이전 페이지 버튼
  const prevPageBtn = document.createElement("button");
  prevPageBtn.innerHTML = "<";
  prevPageBtn.disabled = currentPage === 1;
  prevPageBtn.onclick = () => smsRequest(searchParam, sort, currentPage - 1, dnum);
  paginationContainer.appendChild(prevPageBtn);
  
  // 페이지 번호 버튼
  // 최대 5개의 페이지 번호 표시 (현재 페이지 중심)
  const maxPageButtons = 5;
  let startPage = Math.max(1, currentPage - Math.floor(maxPageButtons / 2));
  let endPage = Math.min(totalPages, startPage + maxPageButtons - 1);
  
  // 시작 페이지 조정 (5개 표시 유지)
  if (endPage - startPage + 1 < maxPageButtons && startPage > 1) {
    startPage = Math.max(1, endPage - maxPageButtons + 1);
  }
  
  for (let i = startPage; i <= endPage; i++) {
    const pageBtn = document.createElement("button");
    pageBtn.textContent = i;
    pageBtn.className = i === currentPage ? "active" : "";
    pageBtn.onclick = () => smsRequest(searchParam, sort, i, dnum);
    paginationContainer.appendChild(pageBtn);
  }
  
  // 다음 페이지 버튼
  const nextPageBtn = document.createElement("button");
  nextPageBtn.innerHTML = ">";
  nextPageBtn.disabled = currentPage === totalPages;
  nextPageBtn.onclick = () => smsRequest(searchParam, sort, currentPage + 1, dnum);
  paginationContainer.appendChild(nextPageBtn);
  
  // 마지막 페이지 버튼
  const lastPageBtn = document.createElement("button");
  lastPageBtn.innerHTML = ">>";
  lastPageBtn.disabled = currentPage === totalPages;
  lastPageBtn.onclick = () => smsRequest(searchParam, sort, totalPages, dnum);
  paginationContainer.appendChild(lastPageBtn);
  
  // 페이지네이션 UI를 모달 내부에 추가
  const modalBody = document.getElementById("smsListModal-body");
  if (modalBody) {
    modalBody.appendChild(paginationContainer);
  } else {
    // smsListModal-body가 없는 경우 대체 위치 찾기
    const container = document.querySelector(".sms-list-container");
    if (container) {
      container.appendChild(paginationContainer);
    } else {
      // 그래도 없으면 테이블 아래에 추가
      const table = document.querySelector("table");
      if (table && table.parentNode) {
        table.parentNode.insertBefore(paginationContainer, table.nextSibling);
      }
    }
  }
}

// 정렬 옵션에 따라 SMS 요청 함수 호출
function requestSmsBySort(sort, dnum) {
  // 기존 페이지네이션 초기화
  const existingPagination = document.getElementById("sms-pagination");
  if (existingPagination) {
    existingPagination.remove();
  }
  
  if (sort === '1') {
    // 핸드폰 번호로 검색
    const phoneNumber = document.getElementById("smsBunho").value;
    
    // sort가 1인 경우에만 핸드폰 번호 체크
    if (!phoneNumber || phoneNumber.trim() === '') {
      document.getElementById("m_smsList").innerHTML = `
        <tr>
          <td colspan='4' style="text-align: center; padding: 20px;">
            핸드폰 번호를 입력해주세요.
          </td>
        </tr>
      `;
      return;
    }
    
    smsRequest(phoneNumber, sort, 1, dnum);
  } else if (sort === '2') {
    // 날짜 구간 선택으로 검색
    const startDateElement = document.getElementById("smsStartDate");
    const endDateElement = document.getElementById("smsEndDate");
    
    if (startDateElement && endDateElement) {
      const startDate = startDateElement.value;
      const endDate = endDateElement.value;
      
      // 날짜 입력 확인
      if (!startDate || !endDate) {
        document.getElementById("m_smsList").innerHTML = `
          <tr>
            <td colspan='4' style="text-align: center; padding: 20px;">
              시작일과 종료일을 모두 선택해주세요.
            </td>
          </tr>
        `;
        return;
      }
      
      smsRequest(`${startDate},${endDate}`, sort, 1, dnum);
    } else {
      // 날짜 요소가 없는 경우 기본값 설정
      const today = new Date();
      const todayStr = today.getFullYear() + '-' + 
String(today.getMonth() + 1).padStart(2, '0') + '-' + 
String(today.getDate()).padStart(2, '0');
      const weekAgo = new Date();
      weekAgo.setDate(today.getDate() - 7);
      const weekAgoStr = weekAgo.toISOString().split('T')[0];
      
      smsRequest(`${weekAgoStr},${todayStr}`, sort, 1, dnum);
    }
  } else if (sort === '3') {
    // 회사명으로 검색
    const companyValue = document.getElementById("smsBunho").value;
    
    // 입력된 회사명이 있으면 company로 검색, 없으면 dnum을 사용
    if (companyValue && companyValue.trim() !== '') {
      smsRequest(companyValue, sort, 1, dnum);
    } else if (dnum && dnum.trim() !== '') {
      smsRequest("", sort, 1, dnum);
    } else {
      // 둘 다 없는 경우 오류 메시지
      document.getElementById("m_smsList").innerHTML = `
        <tr>
          <td colspan='4' style="text-align: center; padding: 20px;">
            회사명을 입력해주세요.
          </td>
        </tr>
      `;
    }
  }
}
/**
 * 요율 변경 처리 함수
 * @param {HTMLElement} selectElement - 선택된 HTML 요소
 * @param {string} num - 항목 번호
 * @param {string} Jumin - 주민번호
 */
async function handleRateChangeSj(selectElement, num, Jumin,policyNum) {
    // UI 즉시 응답을 위한 처리
    selectElement.disabled = true;
    
    // 로딩 표시를 위한 요소 추가
    const row = selectElement.closest('tr');
    const loadingIndicator = document.createElement('span');
    loadingIndicator.className = 'loading-indicator';
    loadingIndicator.textContent = ' 처리 중...';
    selectElement.parentNode.appendChild(loadingIndicator);
    
    // UI 정리 함수
    function cleanupUI() {
        selectElement.disabled = false;
        if (loadingIndicator && loadingIndicator.parentNode) {
            loadingIndicator.parentNode.removeChild(loadingIndicator);
        }
    }
    
    // 요율 검증
    const rateElement = document.getElementById(`rate-${num}`);
    if (!rateElement) {
        alert('요율 요소를 찾을 수 없습니다.');
        cleanupUI();
        return false;
    }
    
    const rate = rateElement.value;
    if (rate < 0) {
        alert('개인 요율부터 입력하세요');
        rateElement.focus();
        cleanupUI();
        return false;
    }
    
    // 사용자 확인
    if (!confirm(`요율 변경값이 맞습니까?`)) {
        cleanupUI();
        return;
    }
    
    // API 요청에 타임아웃 설정
    const controller = new AbortController();
    const timeoutId = setTimeout(() => controller.abort(), 10000); // 10초 후 중단
    
    try {
        // FormData 객체 생성
        const formData = new FormData();
        formData.append('num', num);
        formData.append('Jumin', Jumin);
        formData.append('rate', rate);
		formData.append('policyNum', policyNum);
        formData.append('userName', SessionManager.getUserInfo().name);
        
        const response = await fetch(`https://kjstation.kr/api/kjDaeri/changeRate.php`, {
            method: "POST",
            body: formData,
            signal: controller.signal
        });
        
        clearTimeout(timeoutId); // 타임아웃 해제
        
        // 응답 처리
        if (!response.ok) {
            throw new Error(`서버 응답 오류: ${response.status}`);
        }
        
        const result = await response.json();
        
        if (result.success) {
            // 성공 시 UI 업데이트
            const statusCell = row.querySelector('.status-cell');
            if (statusCell) {
                statusCell.textContent = selectElement.value == 2 ? "처리됨" : "미처리";
                row.classList.toggle('processed-row', selectElement.value == 2);
            }
            alert(result.message);
        } else {
            alert(result.error || "상태 변경 중 오류가 발생했습니다.");
        }
    } catch (error) {
        if (error.name === 'AbortError') {
            alert('요청 시간이 초과되었습니다. 나중에 다시 시도해주세요.');
        } else {
            console.error("상태 업데이트 실패:", error);
            alert(`상태 업데이트 실패: ${error.message}`);
        }
    } finally {
        cleanupUI(); // 항상 UI 정리
    }
}
async function handleStatusChangeSj(selectElement, num, push) {
    // UI 즉시 응답을 위한 처리
    selectElement.disabled = true;
    
    // 로딩 표시를 위한 요소 추가
    const row = selectElement.closest('tr');
    const loadingIndicator = document.createElement('span');
    loadingIndicator.className = 'loading-indicator';
    loadingIndicator.textContent = ' 처리 중...';
    selectElement.parentNode.appendChild(loadingIndicator);
    
    // 요율 검증
    const rateElement = document.getElementById(`rate-${num}`);
    if (!rateElement) {
        alert('요율 요소를 찾을 수 없습니다.');
        cleanupUI();
        return false;
    }
    
    const rate = rateElement.value;
    if (rate < 0) {
        alert('개인 요율부터 입력하세요');
        rateElement.focus();
        cleanupUI();
        return false;
    }
    
    // 사용자 확인
    if (!confirm(`정말로 상태를 ${selectElement.value == 2 ? "처리됨" : "미처리"}로 변경하시겠습니까?`)) {
        cleanupUI();
        return;
    }
    
    // API 요청에 타임아웃 설정
    const controller = new AbortController();
    const timeoutId = setTimeout(() => controller.abort(), 10000); // 10초 후 중단
    
    try {
        // FormData 객체 생성
        const formData = new FormData();
        formData.append('num', num);
        formData.append('status', selectElement.value);
        formData.append('push', push);
        formData.append('rate', rate);
        formData.append('userName', SessionManager.getUserInfo().name);
        
        const response = await fetch(`https://kjstation.kr/api/kjDaeri/changeEndorse.php`, {
            method: "POST",
            body: formData,
            signal: controller.signal
        });
        
        clearTimeout(timeoutId); // 타임아웃 해제
        
        // 응답 처리
        if (!response.ok) {
            throw new Error(`서버 응답 오류: ${response.status}`);
        }
        
        const result = await response.json();
        
        if (result.success) {
            // 성공 시 UI 업데이트
            const statusCell = row.querySelector('.status-cell');
            if (statusCell) {
                statusCell.textContent = selectElement.value == 2 ? "처리됨" : "미처리";
                row.classList.toggle('processed-row', selectElement.value == 2);
            }
            alert("상태가 변경되었습니다.");
        } else {
            alert(result.error || "상태 변경 중 오류가 발생했습니다.");
        }
    } catch (error) {
        if (error.name === 'AbortError') {
            alert('요청 시간이 초과되었습니다. 나중에 다시 시도해주세요.');
        } else {
            console.error("상태 업데이트 실패:", error);
            alert(`상태 업데이트 실패: ${error.message}`);
        }
    } finally {
        cleanupUI(); // 항상 UI 정리
    }
    
    // UI 정리 함수
    function cleanupUI() {
        selectElement.disabled = false;
        if (loadingIndicator && loadingIndicator.parentNode) {
            loadingIndicator.parentNode.removeChild(loadingIndicator);
        }
    }
}
// 상태 변경 처리 함수 - 메인 이벤트 핸들러
function handleStatusChangeKj(selectElement, num) {
    // 이미 처리 중인지 확인
    if (selectElement.dataset.processing === 'true') {
        return;
    }
    
    // 원본 값 저장
    const originalValue = selectElement.dataset.original || selectElement.value;
    const newValue = selectElement.value;
    const statusText = newValue == 2 ? "처리" : "미처리";
    
    // 변경 사항이 없으면 처리하지 않음
    if (originalValue === newValue) {
        return;
    }
    
    // 처리 중 상태로 설정
    selectElement.dataset.processing = 'true';
    
    // 요율 검증 - UI 차단 전에 먼저 수행
    const rateElement = document.getElementById(`rateP-${num}`);
    if (!rateElement) {
        alert('요율 요소를 찾을 수 없습니다.');
        resetSelect();
        return;
    }
    
    const rate = rateElement.value;
    if (rate < 0) {
        alert('개인 요율부터 입력하세요');
        rateElement.focus();
        resetSelect();
        return;
    }
    
    // 확인 대화상자를 마이크로태스크로 분리
    // 이렇게 하면 confirm 대화상자가 표시되기 전에 change 이벤트가 완전히 종료됨
    Promise.resolve().then(() => {
        if (confirm(`정말로 상태를 ${statusText}로 변경하시겠습니까?`)) {
            startProcessing();
        } else {
            resetSelect();
        }
    });
    
    // 선택 요소 리셋 
    function resetSelect() {
        selectElement.value = originalValue;
        selectElement.dataset.processing = 'false';
    }
    
    // 실제 처리 시작
    function startProcessing() {
        // UI 업데이트
        selectElement.disabled = true;
        const loadingIndicator = document.createElement('span');
        loadingIndicator.className = 'loading-indicator';
        loadingIndicator.textContent = ' 처리 중...';
        selectElement.parentNode.appendChild(loadingIndicator);
        
        // 데이터 수집
        const push = document.getElementById(`pushP-${num}`)?.value || '';
        const reasion = document.getElementById(`status-reasion-${num}`)?.value || '';
        const smsContents = document.getElementById('smsContents')?.value || '';
        
        // 네트워크 요청 처리를 위한 Promise 생성
        submitStatusChangeToServer(num, newValue, push, reasion, smsContents)
            .then(success => {
                if (success) {
                    // 성공 시 원본 값 업데이트
                    selectElement.dataset.original = newValue;
                } else {
                    // 실패 시 원래 값으로 되돌림
                    selectElement.value = originalValue;
                }
            })
            .catch(error => {
                console.error('상태 변경 처리 오류:', error);
                selectElement.value = originalValue;
            })
            .finally(() => {
                // UI 정리
                selectElement.disabled = false;
                if (loadingIndicator && loadingIndicator.parentNode) {
                    loadingIndicator.parentNode.removeChild(loadingIndicator);
                }
                selectElement.dataset.processing = 'false';
            });
    }
}

// 서버에 데이터 제출하는 순수 비동기 함수
async function submitStatusChangeToServer(num, status, push, reasion, smsContents) {
    const controller = new AbortController();
    const timeoutId = setTimeout(() => controller.abort(), 30000);
    
    try {
        const formData = new FormData();
        formData.append('num', num);
        formData.append('status', status);
        formData.append('push', push);
        formData.append('reasion', reasion);
        formData.append('userName', SessionManager.getUserInfo()?.name || '');
        formData.append('smsContents', smsContents);
        
        const response = await fetch(`https://kjstation.kr/api/kjDaeri/changeEndorse.php`, {
            method: "POST",
            body: formData,
            signal: controller.signal
        });
        
        clearTimeout(timeoutId);
        
        const result = await response.json();
        if (result.success) {
            alert("상태가 변경되었습니다.");
            return true;
        } else {
            alert(result.error || "상태 변경 중 오류 발생.");
            return false;
        }
    } catch (error) {
        clearTimeout(timeoutId);
        
        if (error.name === 'AbortError') {
            alert('요청 시간이 초과되었습니다. 나중에 다시 시도해주세요.');
        } else {
            console.error("상태 업데이트 실패:", error);
            alert(`상태 업데이트 실패: ${error.message || '알 수 없는 오류'}`);
        }
        return false;
    }
}

// 대리운전회사 정보 
async function kjDaeriCompany(dNum) {
    try {
        // dNum이 없는 경우 신규 등록 폼 표시
   
        // 주민번호로 직접 접근하는 경우 처리 (주민번호 형식인 경우)
        if (typeof dNum === 'string' && /^\d{6}-\d{7}$/.test(dNum)) {
            // 주민번호로 회사 정보 조회
            const checkResponse = await fetch(`https://kjstation.kr/api/kjDaeri/dNum_check_jumin.php?jumin=${encodeURIComponent(dNum)}`);
            const checkData = await checkResponse.json();
            
            if (checkData.exists) {
                // 기존 회사 정보 불러오기
                dNum = checkData.dNum;
            } else {
                // 신규 등록 폼 표시
                showNewCompanyForm();
                // 주민번호 자동 입력
                document.getElementById('d_Jumin').value = dNum;
                return;
            }
        }

        // 기존 코드: dNum이 있는 경우 회사 정보 조회
        const response = await fetch(`https://kjstation.kr/api/kjDaeri/get_DaeriCompany_details.php?dNum=${dNum}`);
        const data = await response.json();

        if (!data.success) {
            alert(data.error);
            return;
        }
        if(data.company){
			document.getElementById("daeri_company").innerHTML = data.company;  
		}else{
			document.getElementById("daeri_company").innerHTML = "대리운전회사 신규 등록";  
		}
       // console.log('data', data);
        const premitType = {"1":"허용", "2":"차단"};
        
        // 이하 기존 코드 생략...
        // (기존 코드를 그대로 유지)
        let d_info=''; //대리운전회사 정보 

		 d_info=`
			 <table  class='kjTable'>
				<tr> 
				    <th width='12%'>주민번호</th>
                    <th width='16%'>대리운전회사</th>
                    <th width='12%'>대표자</th>
					<th width='12%'>연락처</th>
					<th width='12%'>연락처</th>
					<th width='12%' ><span style="cursor:pointer;" onclick="handleDamdangjarClick(${data.num},${data.MemberNum})">담당자</span></th>
					<th width='12%'><span style="cursor:pointer;" onclick="handleIdClick(${data.num})">업체I.D</span></th>
					<th width='12%'>담당자</th>
                </tr>
                <tr> 
				    <td><input type='text' class='geInput2' id='d_Jumin' value="${data.jumin || ''}" placeholder="660327-1069017" oninput="formatJuminNumber(event)" onkeyup="checkJuminFormat(event)" autocomplete="off"></td>
                    <td><input type='text' class='geInput2' id='d_company' value="${data.company || ''}" autocomplete="off"></td>
					<td><input type='text' class='geInput2' id='d_Pname' value="${data.Pname || ''}" autocomplete="off"> </td>
					<td><input type='text' class='geInput2' id='daeri_hphone' value="${data.hphone || ''}" oninput="formatPhoneNumber(this)" autocomplete="off"></td>
					<td><input type='text' class='geInput2' id='d_cphone' value="${data.cphone|| ''}" oninput="formatPhoneNumber(this)" autocomplete="off"></td>
					<td><input type='text' class='geInput2' id='d_damdangja' value="${data.name || ''}" autocomplete="off"></td>
					<td><input type='text' class='geInput2' id='d_id' value="${data.mem_id|| '' }${premitType[data.permit] || ''}"></td>
					<td><input type='text' class='geInput2' id='d_8'></td>
                </tr>
				<tr> 
                    <th>사업자번호</th>
                    <th>법인번호</th>
					<th>최초거래일</th>
					<th>보험회사</th>
					<th>담당자</th>
					<th>담당자</th>
					<th>담당자</th>
					<th>담당자</th>
                </tr>
				<tr> 
                    <td><input type='text' class='geInput2' id='d_cNumber' value="${data.cNumber || ''}" autocomplete="off"></td>
                    <td><input type='text' class='geInput2' id='d_lNumber' value="${data.lNumber || ''}" autocomplete="off"></td>
					<td><input type='date' class='geInput2' id='d_a11' value="${data.FirstStart || ''}" autocomplete="off" onchange="dateChanged('${data.num}')"></td>
                    <td><input type='text' class='geInput2' id='d_12'></td>
					<td><input type='text' class='geInput2' id='d_13'></td>
                    <td><input type='text' class='geInput2' id='d_14'></td>
					<td><input type='text' class='geInput2' id='d_15'></td>
                    <td><input type='text' class='geInput2' id='d_16'></td>
                </tr>
                <tr>
					<td colspan='8' style="text-align: right;"> 
						<button class="kje-daeri-button" onclick="daeriCompany_store(${data?.num || ''})">
							${data?.num ? '대리운전회사 정보 수정' : '대리운전회사 정보 저장'}
						</button>
						${data?.num ? `<button class="kje-daeri-button" onclick="sms_List('${data.num},${data.company}','','3')">문자리스트</button>` : ''}
					</td>
				</tr>
            </table>
		 `;
		document.getElementById("d_companyInfo").innerHTML = d_info;
		
        // 증권 리스트 표시 로직 (기존 코드 유지)
        let e_list='';
         e_list = `
			<table class='kjTable'>
			 <thead>
				<tr>
					<th width='6%'>No</th>
					<th width='8%'>보험사</th>
					<th width='10%'>시작일</th>
					<th width='10%'>증권번호</th>
					<th width='5%'>분납</th>
					<th width='6%'>저장</th>
					<th width='8%'>회차</th>
					<th width='5%'>상태</th>
					<th width='5%'>인원</th>
					<th width='6%'>신규</th>
					<th width='6%'>배서</th>
					<th width='7%'>결제방식</th>
					<th width='7%'>월보험료</th>
					<th width='10%'>증권성격</th>
				</tr>
			 </thead>
             <tbody>`;

        data.data.forEach((item, index) => {
            const InsuraneCompanyOptions = `
                <select class="kje-status-ch" id='insurance-${item.num}' data-id="${item.num}" >
					<option value="-1">선택</option>
                    <option value="1" ${item.InsuraneCompany == 1 ? "selected" : ""}>흥국</option> 
                    <option value="2" ${item.InsuraneCompany == 2 ? "selected" : ""}>DB</option> 
                    <option value="3" ${item.InsuraneCompany == 3 ? "selected" : ""}>KB</option>
                    <option value="4" ${item.InsuraneCompany == 4 ? "selected" : ""}>현대</option>
                    <option value="5" ${item.InsuraneCompany == 5 ? "selected" : ""}>한화</option>
                    <option value="6" ${item.InsuraneCompany == 6 ? "selected" : ""}>롯데</option>
                    <option value="7" ${item.InsuraneCompany == 7 ? "selected" : ""}>MG</option>
                    <option value="8" ${item.InsuraneCompany == 8 ? "selected" : ""}>삼성</option>
                </select>
            `;

            const policyTypeOptions = `
                <select class="kje-status-ch" onChange="gitaChange(${item.num})">
                    <option value="1" ${item.gita == 1 ? "selected" : ""}>대리</option> 
                    <option value="2" ${item.gita == 2 ? "selected" : ""}>탁송</option> 
                    <option value="3" ${item.gita == 3 ? "selected" : ""}>대리/렌트</option>
                    <option value="4" ${item.gita == 4 ? "selected" : ""}>탁송/렌트</option>
                    <option value="5" ${item.gita == 5 ? "selected" : ""}>확대탁송</option>
                </select>
            `;
			const nabang_1TypeOptions = `
                <select class="kje-status-ch" onChange="nabangChange(${item.num})">
                    <option value="1" ${item.nabang_1 == 1 ? "selected" : ""}>1회차</option> 
                    <option value="2" ${item.nabang_1 == 2 ? "selected" : ""}>2회차</option> 
                    <option value="3" ${item.nabang_1 == 3 ? "selected" : ""}>3회차</option>
                    <option value="4" ${item.nabang_1 == 4 ? "selected" : ""}>4회차</option>
                    <option value="5" ${item.nabang_1 == 5 ? "selected" : ""}>5회차</option>
					<option value="6" ${item.nabang_1 == 6 ? "selected" : ""}>6회차</option>
					<option value="7" ${item.nabang_1 == 7 ? "selected" : ""}>7회차</option>
					<option value="8" ${item.nabang_1 == 8 ? "selected" : ""}>8회차</option>
					<option value="9" ${item.nabang_1 == 9 ? "selected" : ""}>9회차</option>
					<option value="10" ${item.nabang_1 == 10 ? "selected" : ""}>10회차</option>
                </select>
            `;
			console.log("item.divi",item.divi);
			const diviType={"1":"정상납","2":"월납"};
		    const diviName=diviType[item.divi];

            e_list += `<tr>
                <td>${index + 1}</td>
                <td>${InsuraneCompanyOptions}</td>
                <td><input type='date' id='startDay-${item.num}' value="${item.startyDay}"></td>
                <td><input type='text' id='policyNum-${item.num}' class='geInput2' placeholder="증권번호 입력" value="${item.policyNum}"></td>
                <td><input type='text' id='nabang-${item.num}' class='geInput2' placeholder="분납" value="${item.nabang}"></td>
                <td><button class="kje-daeri-button" onclick="certi_store(${item.num},${dNum})">수정</button></td>
                <td>${nabang_1TypeOptions}</td>
                <td>${item.status || ''}</td>
                <td>${item.inwon || ''}</td>
                <td><button class="kje-daeri-button" onclick="newEntry(${item.num})">신규</button></td>
                <td><button class="kje-daeri-button" onclick="endorsementEntry(${item.num})">배서</button></td>
                <td><button class="kje-daeri-button" onclick="togglePaymentMethod(event,${item.num})" id="payment-${item.num}">${diviName}</button></td>
                <td><button class="kje-daeri-button" onclick="setMonthlyPremium(${item.num})">설정</button></td>
                <td>${policyTypeOptions}</td>
            </tr>`;
        });
//대리운전회사 data.num 있는 경우 
		if(data.num){
				// 새 행 추가 (입력 가능한 행)
				const newRowIndex = data.data.length + 1;
				const InsuraneCompanyOptions = `
					<select class="kje-status-ch" data-id="new" id="insuranceSelect" onChange="chChange(event,${dNum})"> 
						<option value="-1">선택</option> 
						<option value="1">흥국</option> 
						<option value="2">DB</option> 
						<option value="3">KB</option>
						<option value="4">현대</option>
						<option value="5">한화</option>
						<option value="6">롯데</option>
						<option value="7">MG</option>
						<option value="8">삼성</option>
					</select>
				`;

				const policyTypeOptions = `
					<select class="kje-status-ch">
						<option value="1">대리</option> 
						<option value="2">탁송</option> 
						<option value="3">대리/렌트</option>
						<option value="4">탁송/렌트</option>
						<option value="5">확대탁송</option>
					</select>
				`;
				const nabang_1TypeOptions = `
						<select class="kje-status-ch" >
							<option value="1">1회차</option> 
							<option value="2">2회차</option> 
							<option value="3">3회차</option>
							<option value="4">4회차</option>
							<option value="5">5회차</option>
							<option value="6">6회차</option>
							<option value="7">7회차</option>
							<option value="8">8회차</option>
							<option value="9">9회차</option>
							<option value="10">10회차</option>
						</select>
					`;
				e_list += `<tr class="new-row">
					<td>${newRowIndex}</td>
					<td>${InsuraneCompanyOptions}</td>
					<td><input type='date' id='startDay-new' class='geInput'></td>
					<td><input type='text' id='policyNum-new'class='geInput2' placeholder="증권번호 입력" autocomplete="off"></td>
					<td><input type='text' id='nabang-new' class='geInput2' placeholder="분납" autocomplete="off"></td>
					<td></td>
					<td></td>
					<td></td>
					<td></td>
					<td></td>
					<td></td>
					<td></td>
					<td></td>
					<td></td>
				</tr>`;

				e_list += `</tbody></table>`;
		}else{
			
			  e_list = `
					<table class='kjTable'>
						<thead>
							<tr>
								<th width='6%'>No</th>
								<th width='8%'>보험사</th>
								<th width='10%'>시작일</th>
								<th width='10%'>증권번호</th>
								<th width='5%'>분납</th>
								<th width='6%'>저장</th>
								<th width='8%'>회차</th>
								<th width='5%'>상태</th>
								<th width='5%'>인원</th>
								<th width='6%'>신규</th>
								<th width='6%'>배서</th>
								<th width='7%'>결제방식</th>
								<th width='7%'>월보험료</th>
								<th width='10%'>증권성격</th>
							</tr>
						</thead>
						<tbody>
							<tr>
								<td colspan="14">신규 회사를 먼저 등록하신 후 증권 정보를 입력할 수 있습니다.</td>
							</tr>
						</tbody>
					</table>
				`;

		}
        document.getElementById("e_CeritList").innerHTML = e_list;
        document.getElementById("kj-DaeriCompany-modal").style.display = "block";

        // 이벤트 리스너 설정
        setupPolicyNumberFormatter();
    } catch (error) {
        console.error("데이터 로드 실패:", error);
        alert("데이터 로드 실패.");
    }
}
// 최초거래일 등록 
// 날짜 변경 시 실행되는 함수
function dateChanged(dNum) {
    // 선택된 날짜 가져오기
    const selectedDate = document.getElementById('d_a11').value;
    
    // 선택된 날짜와 대리운전회사 정보 출력 (테스트용)
    console.log('선택된 날짜:', selectedDate);
    console.log('대리운전회사:', dNum);
    
    // FormData 객체 생성
    const formData = new FormData();
    
    // FormData에 데이터 추가
    formData.append('dNum', dNum);
    formData.append('firstDate', selectedDate);
    
    // fetch API를 사용하여 데이터 전송
    fetch('https://kjstation.kr/api/kjDaeri/firstDate.php', {
        method: 'POST',
        body: formData
    })
    .then(response => {
        if (!response.ok) {
            throw new Error('네트워크 응답이 정상이 아닙니다');
        }
        return response.json();
    })
    .then(data => {
        console.log('API 응답:', data);
        // 성공 시 처리할 코드 
        if (data.success) {
            alert('날짜가 성공적으로 업데이트되었습니다.');
        } else {
            alert('날짜 업데이트 실패: ' + (data.error || '알 수 없는 오류'));
        }
    })
    .catch(error => {
        console.error('API 요청 중 오류 발생:', error);
        alert('날짜 업데이트 중 오류가 발생했습니다.');
    });
}

// 주민번호 자동 형식화 (6자리 입력 후 자동으로 하이픈 추가)
function formatJuminNumber(event) {
    const input = event.target;
    let value = input.value.replace(/[^0-9]/g, ''); // 숫자만 남김
    
    if (value.length > 6) {
        // 앞 6자리 뒤에 하이픈 추가
        value = value.substring(0, 6) + '-' + value.substring(6);
    }
    
    // 최대 14자 (XXXXXX-XXXXXXX)
    if (value.length > 14) {
        value = value.substring(0, 14);
    }
    
    input.value = value;
}
// 주민번호 검사 결과를 저장할 전역 변수
let juminCheckResult = {
    checked: false,
    isValid: false,
    exists: false,
    dNum: null
};

// 주민번호 형식 확인 및 중복 체크 함수
async function checkJuminFormat(event) {
    const juminInput = event.target;
    const juminValue = juminInput.value;
    
    // 주민번호 형식 검증 정규식 (YYMMDD-NNNNNNN)
    const juminRegex = /^\d{6}-\d{7}$/;
    
    // 형식 검증 (매 입력마다)
    juminCheckResult.isValid = juminRegex.test(juminValue);
    
    // 엔터키 눌렀을 때만 서버 검증 수행
    if (event.key === 'Enter') {
        // 형식 검증 실패 시 알림
        if (!juminCheckResult.isValid) {
            alert("주민번호 형식이 올바르지 않습니다. 예: 660327-1069017");
            return;
        }
        
        try {
            // 로딩 상태 표시 (선택적)
            // document.getElementById('d_Jumin').classList.add('loading');
            
            // 서버에 주민번호로 기존 회사 조회
            const response = await fetch(`https://kjstation.kr/api/kjDaeri/dNum_check_jumin.php?jumin=${encodeURIComponent(juminValue)}`);
            const data = await response.json();
            
            // 검증 결과 저장
            juminCheckResult.checked = true;
            juminCheckResult.exists = data.exists;
            juminCheckResult.dNum = data.dNum || null;
            
            // 로딩 상태 제거 (선택적)
            // document.getElementById('d_Jumin').classList.remove('loading');
            
            // 이미 등록된 회사가 있는 경우
            if (data.exists) {
                alert("이미 등록된 주민번호입니다. 기존 회사 정보를 불러옵니다.");
                // 기존 회사 정보 불러오기
                kjDaeriCompany(data.dNum);
            } else {
                // 신규 등록 가능
                alert("신규 등록 가능한 주민번호입니다.");
				document.getElementById('d_company').focus();
            }
        } catch (error) {
            // 로딩 상태 제거 (선택적)
            // document.getElementById('d_Jumin').classList.remove('loading');
            
            juminCheckResult.checked = false;
            console.error("주민번호 확인 중 오류 발생:", error);
            alert("주민번호 확인 중 오류가 발생했습니다.");
        }
    } else {
        // 사용자가 주민번호를 변경하면 검증 결과 초기화
        if (juminCheckResult.checked) {
            juminCheckResult.checked = false;
            juminCheckResult.exists = false;
            juminCheckResult.dNum = null;
        }
    }
}

// 신규 대리운전회사 등록 함수
function daeriCompany_store(dNum) {
    // 로딩 인디케이터 표시
    showLoadingIndicator();
    
    try {
        // 각 요소 개별적으로 확인하면서 디버깅
        const juminElement = document.getElementById('d_Jumin');
        console.log("주민번호 요소:", juminElement);
        
        const companyElement = document.getElementById('d_company');
        console.log("회사 요소:", companyElement);
        
        // 나머지 요소들도 비슷하게 확인
        const pnameElement = document.getElementById('d_Pname');
        const hphoneElement = document.getElementById('daeri_hphone');
        const cphoneElement = document.getElementById('d_cphone');
        const cNumberElement = document.getElementById('d_cNumber');
        const lNumberElement = document.getElementById('d_lNumber');
        
        // DOM 요소들을 객체에 할당
        const elements = {
            jumin: juminElement,
            company: companyElement,
            pname: pnameElement,
            hphone: hphoneElement,
            cphone: cphoneElement,
            cNumber: cNumberElement,
            lNumber: lNumberElement,
        };
        
        // elements 객체의 모든 키 확인
        console.log("요소 확인:", Object.keys(elements).map(key => ({
            key: key,
            element: elements[key],
            exists: !!elements[key],
            value: elements[key]?.value
        })));
        
        // 필수 입력 필드 검증 - null 체크 추가
        if (!elements.jumin || !elements.jumin.value) {
            hideLoadingIndicator();
            alert("주민번호는 필수 입력 항목입니다.");
            elements.jumin?.focus();
            return;
        }
        
        if (!elements.company || !elements.company.value) {
            hideLoadingIndicator();
            alert("대리운전회사명은 필수 입력 항목입니다.");
            elements.company?.focus();
            return;
        }
        
        if (!elements.pname || !elements.pname.value) {
            hideLoadingIndicator();
            alert("대표자는 필수 입력 항목입니다.");
            elements.pname?.focus();
            return;
        }
        
        // FormData 객체 생성
        const formData = new FormData();
        
        // 데이터 추가
        formData.append('jumin', elements.jumin?.value || "");
        formData.append('company', elements.company?.value || "");
        formData.append('Pname', elements.pname?.value || "");
        formData.append('hphone', elements.hphone?.value || "");
        formData.append('cphone', elements.cphone?.value || "");
        formData.append('cNumber', elements.cNumber?.value || "");
        formData.append('lNumber', elements.lNumber?.value || "");
		formData.append('dNum', dNum || "");
        
        // URLSearchParams로 변환
        const urlEncodedData = new URLSearchParams();
        for (const [key, value] of formData) {
            urlEncodedData.append(key, value);
        }
        
        console.log("전송할 데이터:", Object.fromEntries(urlEncodedData));
        
        // API 요청
        fetch('https://kjstation.kr/api/kjDaeri/dNumStore.php', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/x-www-form-urlencoded',
            },
            body: urlEncodedData
        })
        .then(response => {
            if (!response.ok) {
                throw new Error('등록 과정에서 오류가 발생했습니다');
            }
            return response.json();
        })
        .then(result => {
            hideLoadingIndicator();
            if (result.success) {
                
                // 등록 후 새로 생성된 회사 정보로 페이지 로드
				if(dNum){
					kjDaeriCompany(dNum);
					alert("대리운전회사 정보가 수정되었습니다.");
				}else{
					alert("대리운전회사  신규로 저장되었습니다.");
					kjDaeriCompany(result.dNum);
				}
            } else {
                alert("등록 실패: " + (result.error || "알 수 없는 오류"));
            }
        })
        .catch(error => {
            hideLoadingIndicator();
            console.error("등록 실패:", error);
            alert("데이터 등록 중 오류가 발생했습니다.");
        });
    } catch (error) {
        hideLoadingIndicator();
        console.error("함수 실행 중 오류 발생:", error);
        alert("처리 중 오류가 발생했습니다.");
    }
}

// 로딩 인디케이터 함수들 (별도로 구현 필요)
function showLoadingIndicator() {
    // 로딩 UI 표시 코드
    // 예: document.getElementById('loadingSpinner').style.display = 'block';
}

function hideLoadingIndicator() {
    // 로딩 UI 숨김 코드
    // 예: document.getElementById('loadingSpinner').style.display = 'none';
}

// 증권번호 형식 설정을 위한 함수들 (기존 코드 그대로 유지)
function setupPolicyNumberFormatter() {
    // 새 행의 증권번호 입력 필드에 이벤트 리스너 추가
    const policyNumInput = document.getElementById('policyNum-new');
    if (policyNumInput) {
        policyNumInput.addEventListener('input', formatPolicyNumber);
        policyNumInput.addEventListener('click', restoreOriginalNumber);
    }

    // 기존 행들의 증권번호 입력 필드에도 이벤트 리스너 추가
    document.querySelectorAll('input[id^="policyNum-"]').forEach(input => {
        input.addEventListener('input', formatPolicyNumber);
        input.addEventListener('click', restoreOriginalNumber);
    });
}

function formatPolicyNumber(event) {
    const input = event.target;
    // 숫자만 제거하는 정규식을 제거하고 그대로 값을 사용
    const value = input.value;
    
    // 입력값이 7자 이상인 경우만 처리 (문자 포함이므로 정확히 7자가 아닐 수 있음)
    if (value.length >= 7) {
        const rowId = input.id.split('-')[1];
        const dateInput = document.getElementById(`startDay-${rowId}`);
        
        if (dateInput && dateInput.value) {
            const year = dateInput.value.split('-')[0];
            // 기존 값에 연도를 추가하되, 이미 연도-값 형태인지 확인
            if (!value.includes('-')) {
                input.value = `${year}-${value}`;
            }
            // 원본 값을 데이터 속성에 저장
            input.setAttribute('data-original', value);
        }
    }
}

function restoreOriginalNumber(event) {
    const input = event.target;
    // 형식이 'YYYY-1234567'인지 확인
    if (input.value.includes('-')) {
        // 데이터 속성에 저장된 원본 값이 있는지 확인
        const originalValue = input.getAttribute('data-original');
        if (originalValue) {
            input.value = originalValue;
        } else {
            // 데이터 속성이 없으면 하이픈 이후의 숫자만 표시
            input.value = input.value.split('-')[1] || input.value;
        }
    }
}


async function chChange(event,dNum) {
    // 현재 선택된 드롭다운 요소 가져오기
    const selectElement = event.target;
    
    // data-id 값 가져오기 (여기서는 'new')
    const dataId = selectElement.getAttribute('data-id');
    
    // 선택된 옵션의 값 가져오기
    const selectedValue = selectElement.value;
    
    // 값 확인 (콘솔에 출력)
    console.log(`data-id: ${dataId}, 선택된 값: ${selectedValue}`);
    
    // 현재 행 찾기
    const currentRow = selectElement.closest('tr');
    
    // 버튼이 표시될 td 찾기 (6번째 td)
    const buttonCell = currentRow.cells[5];
    
    // 선택된 값에 따라 버튼 표시/숨김 처리
    if (parseInt(selectedValue) >= 1) {
        // 선택된 값이 1보다 크면 버튼 추가
        buttonCell.innerHTML = `<button class="kje-daeri-button" onclick="certi_store('${dataId}','${dNum}')" >저장</button>`;
    } else {
        // 선택된 값이 1보다 작거나 같으면 버튼 제거

		document.getElementById(`startDay-new`).value = "";
		document.getElementById(`policyNum-new`).value = "";
		document.getElementById(`nabang-new`).value = "";
    
    // 추가 버튼 제거 (보험사 선택이 초기화되었으므로)
    
        buttonCell.innerHTML = '';
    }
    
    // 선택된 텍스트 가져오기
    const selectedText = selectElement.options[selectElement.selectedIndex].text;
    console.log(`선택된 텍스트: ${selectedText}`);
    
    
}
//보험회사//보험기간/증권번호 저장 
async function certi_store(cNum,dNum){

	console.log("cNum",cNum);

	if(cNum=='new'){//신규인 경우

		const insurance = document.getElementById(`insuranceSelect`).value;
		const startDay = document.getElementById(`startDay-new`).value;
		const policyNum = document.getElementById(`policyNum-new`).value;
		const nabang = document.getElementById(`nabang-new`).value;

		console.log('insurance', insurance, 'startDay', startDay, 'policyNum', policyNum, 'nabang', nabang);
        
        // 입력값 검증
        if (!startDay || startDay.trim() === '') {
            alert('날짜를 선택해주세요.');
            document.getElementById(`startDay-new`).focus();
            return;
        }
        
        if (!policyNum || policyNum.trim() === '') {
            alert('증권번호를 입력해주세요.');
            document.getElementById(`policyNum-new`).focus();
            return;
        }
        
        if (!nabang || nabang.trim() === '') {
            alert('분납을 입력해주세요.');
            document.getElementById(`nabang-new`).focus();
            return;
        }
		try {
			// API 호출
			const response = await fetch(`https://kjstation.kr/api/kjDaeri/sujeong.php?cNum=${cNum}&dNum=${dNum}&insurance=${insurance}&startDay=${startDay}&policyNum=${policyNum}&nabang=${nabang}`);
			const data = await response.json();
			
			// 응답 확인
			if (!data.success) {
				alert(data.error);
				return;
			} else {
				alert('신규 증권 생성  화면이 다시 로드됩니다.'); //
				document.getElementById("kj-DaeriCompany-modal").style.display = "none";
				kjDaeriCompany(dNum);
			}
			
			console.log(data.data);
		} catch (error) {
			console.error("❌ 데이터 로드 실패:", error);
			alert("데이터 로드 실패.");
		}

	}else{

		const insurance = document.getElementById(`insurance-${cNum}`).value;
		const startDay = document.getElementById(`startDay-${cNum}`).value;
		const policyNum = document.getElementById(`policyNum-${cNum}`).value;
		const nabang = document.getElementById(`nabang-${cNum}`).value;
		 // 입력값 검증
        if (!startDay || startDay.trim() === '') {
            alert('날짜를 선택해주세요.');
            document.getElementById(`startDay-${cNum}`).focus();
            return;
        }
        
        if (!policyNum || policyNum.trim() === '') {
            alert('증권번호를 입력해주세요.');
            document.getElementById(`insurance-${cNum}`).focus();
            return;
        }
        
        if (!nabang || nabang.trim() === '') {
            alert('분납을 입력해주세요.');
            document.getElementById(`nabang-${cNum}`).focus();
            return;
        }
		console.log('insurance',insurance,'startDay',startDay,'policyNum',policyNum);
		try {
			// API 호출
			const response = await fetch(`https://kjstation.kr/api/kjDaeri/sujeong.php?cNum=${cNum}&insurance=${insurance}&startDay=${startDay}&policyNum=${policyNum}&nabang=${nabang}`);
			const data = await response.json();
			
			// 응답 확인
			if (!data.success) {
				alert(data.error);
				return;
			} else {
				alert('변경 되었습니다');
			}
			
			console.log(data.data);
		} catch (error) {
			console.error("❌ 데이터 로드 실패:", error);
			alert("데이터 로드 실패.");
		}
	}

}
//증권의 성격 변경
async function gitaChange(cNum) {
    // select 요소의 값 가져오기
    const selectElement = event.target; // 이벤트가 발생한 select 요소
    const selectedValue = selectElement.value; // 선택된 회차 값
    
    try {
        // API 호출
        const response = await fetch(`https://kjstation.kr/api/kjDaeri/gitaChange.php?cNum=${cNum}&gita=${selectedValue}`);
        const data = await response.json();
        
        // 응답 확인
        if (!data.success) {
            alert(data.error);
            return;
        } else {
            alert('증권성격 변경');
        }
        
        console.log(data.data);
    } catch (error) {
        console.error("❌ 데이터 로드 실패:", error);
        alert("데이터 로드 실패.");
    }
}
//회차 변경
async function nabangChange(cNum) {
    // select 요소의 값 가져오기
    const selectElement = event.target; // 이벤트가 발생한 select 요소
    const selectedValue = selectElement.value; // 선택된 회차 값
    
    try {
        // API 호출
        const response = await fetch(`https://kjstation.kr/api/kjDaeri/nabangChange.php?cNum=${cNum}&nabang=${selectedValue}`);
        const data = await response.json();
        
        // 응답 확인
        if (!data.success) {
            alert(data.error);
            return;
        } else {
            alert('회차 변경 완료');
        }
        
        console.log(data.data);
    } catch (error) {
        console.error("❌ 데이터 로드 실패:", error);
        alert("데이터 로드 실패.");
    }
}
async function newEntry(cNum){

	   const modal = document.getElementById("kj-new-modal");

		modal.style.display = "block";

	// 오늘 날짜를 가져옵니다
	const today = new Date();
	
	// YYYY-MM-DD 형식으로 변환합니다
	const year = today.getFullYear();
	// getMonth()는 0부터 시작하므로 1을 더합니다
	let month = today.getMonth() + 1;
	let day = today.getDate();
	
	// 한 자리수인 경우 앞에 0을 붙입니다
	month = month < 10 ? '0' + month : month;
	day = day < 10 ? '0' + day : day;
	
	const formattedDate = `${year}-${month}-${day}`;
	
	// 날짜 입력 필드의 값을 오늘 날짜로 설정합니다
	document.getElementById('newDay').value = formattedDate;
    try {
        const response = await fetch(`https://kjstation.kr/api/kjDaeri/get_2012CertiTable_details2.php?cNum=${cNum}`);
        const data = await response.json();
        if (!data.success) {
            alert(data.error);
            return;
        }
        console.log(data.data);
        // ✅ p_Cnum 요소가 있는지 확인 후 값 설정
        let inputElement = document.getElementById("new_Cnum");
        if (!inputElement) {
            inputElement = document.createElement("input");
            inputElement.type = "text";
            inputElement.id = "new_Cnum";
            modal.appendChild(inputElement);
        }
        inputElement.value = cNum;
        // ✅ ceti_daeriCompany 요소가 있는지 확인 후 값 설정
        const companyInfoElement = document.getElementById("new_ceti_daeriCompany");
        if (companyInfoElement) {
            companyInfoElement.innerHTML = `${data.company} 증권번호 ${data.policyNum}`;
        } else {
            console.warn("⚠️ 'new_ceti_daeriCompany' 요소를 찾을 수 없습니다.");
        }
        // ✅ kj_endorseList tbody 요소 가져오기 및 초기화
        const tbody = document.getElementById('kj_newList');
        if (!tbody) {
            console.error("❌ 'kj_newList' 요소를 찾을 수 없습니다.");
            return;
        }
        // data.gita 값 확인 및 변환 (문자열일 경우 숫자로 변환)
        const gitaValue = parseInt(data.gita);
        let gitaName='';
        switch(gitaValue){
            case 1 :
                gitaName='대리';
                break;
            case 2 :
                gitaName='탁송';
                break;
            case 3 :
                gitaName='대리/렌트';
                break;
            case 4 :
                gitaName='탁송/렌트';
                break;
        }
            
        console.log("data.gita",data.gita,'gitaName',gitaName);
        tbody.innerHTML = '';
        // ✅ 6줄의 입력 필드 생성 (성명, 주민번호, 핸드폰 번호)
        for (let i = 1; i <= 6; i++) {
            const row = document.createElement('tr');
            row.innerHTML = `
                <td>${i}</td>
                <td><input type='text' class='geInput' id='n_${i}_1' data-row='${i}' data-col='1' placeholder='성명' autocomplete="off"></td>
                <td><input type='text' class='geInput' id='n_${i}_2' data-row='${i}' data-col='2' placeholder='주민번호' maxlength='14' autocomplete="off"></td>
                <td><input type='text' class='geInput' id='n_${i}_3' data-row='${i}' data-col='3' placeholder='핸드폰 번호' maxlength='13' autocomplete="off"></td>
                <td>${gitaName}</td>
            `;
            tbody.appendChild(row);
        }
        // ✅ 저장 버튼이 있는 행 추가
        const saveButtonRow = document.createElement('tr');
        saveButtonRow.innerHTML = `
            <td colspan="9" style="text-align: center; padding: 15px;">
                <button id="saveEndorseButton2" class="save-button" style="padding: 8px 20px; background-color: #4CAF50; color: white; border: none; border-radius: 4px; cursor: pointer; font-size: 14px;">저장</button>
            </td>
        `;
        tbody.appendChild(saveButtonRow);
        // ✅ 저장 버튼 이벤트 리스너 등록
        document.getElementById('saveEndorseButton2').addEventListener('click', function() {
            saveNew(cNum,data.dNum,data.gita,data.InsuraneCompany,document.getElementById('endorseDay').value,data.policyNum); // 여기서는 saveNew 함수를 호출해야 합니다
			//certiTable Num,daeriCompany Num, 증권의 성격, 보험회사 , 배서기준일
        });
        
        // 성명 필드 - 한글 입력 처리 개선
        for (let i = 1; i <= 6; i++) {
            const nameInput = document.getElementById(`n_${i}_1`);
            
            // 입력 중인 상태를 추적하는 변수
            let isComposing = false;
            
            // 조합 시작 시 플래그 설정
            nameInput.addEventListener('compositionstart', function() {
                isComposing = true;
            });
            
            // 조합 완료 시 검증 및 플래그 해제
            nameInput.addEventListener('compositionend', function() {
                isComposing = false;
                validateKoreanName(this);
            });
            
            // 필드를 떠날 때 최종 검증
            nameInput.addEventListener('blur', function() {
                validateKoreanName(this);
            });
            
            // 키 입력 시 검증 (조합중이 아닐 때만)
            nameInput.addEventListener('input', function() {
                if (!isComposing) {
                    validateKoreanName(this);
                }
            });
        }
        
        // 한글 이름 검증 함수
        function validateKoreanName(inputElement) {
            // 영어, 숫자, 특수문자 제거 (한글과 공백만 남김)
            setTimeout(() => {
                const originalValue = inputElement.value;
                const koreanOnly = originalValue.replace(/[a-zA-Z0-9~!@#$%^&*()_+|<>?:{}]/g, '');
                
                if (originalValue !== koreanOnly) {
                    inputElement.value = koreanOnly;
                }
            }, 10);
        }
        
        // 주민등록번호 입력 형식 설정 (XXXXXX-XXXXXXX) 및 유효성 검사
        for (let i = 1; i <= 6; i++) {
            const juminInput = document.getElementById(`n_${i}_2`);
            juminInput.addEventListener('input', function(e) {
                let value = e.target.value.replace(/[^0-9-]/g, '');
                if (value.length > 6 && !value.includes('-')) {
                    value = value.substring(0, 6) + '-' + value.substring(6);
                }
                e.target.value = value;
            });
            
            // 주민번호 유효성 검사 (포커스 아웃 시)
            juminInput.addEventListener('blur', function(e) {
                const juminNo = e.target.value.replace(/-/g, '');
                // 비어있는 경우 검사하지 않음
                if (juminNo.length === 0) {
                    return;
                }
                
                // 불완전한 주민번호는 경고만 표시
                if (juminNo.length !== 13) {
                    alert('주민등록번호 13자리를 모두 입력해주세요.');
                    return;
                }
                
                // 유효성 검사 실패 시 시각적 표시만 제공 (포커스 재설정 없음)
                if (!validateJuminNo(juminNo)) {
                    alert('유효하지 않은 주민등록번호입니다.');
                    e.target.style.borderColor = 'red'; // 시각적 표시 추가
                } else {
                    e.target.style.borderColor = 'green'; // 유효한 경우 시각적 표시
                }
            });
        }
        
        // 핸드폰 번호 입력 형식 설정 (010-XXXX-XXXX)
        for (let i = 1; i <= 6; i++) {
            const phoneInput = document.getElementById(`n_${i}_3`);
            phoneInput.addEventListener('input', function(e) {
                let value = e.target.value.replace(/[^0-9]/g, '');
                if (value.length > 3 && value.length <= 7) {
                    value = value.substring(0, 3) + '-' + value.substring(3);
                } else if (value.length > 7) {
                    value = value.substring(0, 3) + '-' + value.substring(3, 7) + '-' + value.substring(7);
                }
                e.target.value = value;
            });
        }
        
        // 주민등록번호 유효성 검사 함수
        function validateJuminNo(juminNo) {
            // 주민번호 유효성 검사 알고리즘
            const checkSum = [2, 3, 4, 5, 6, 7, 8, 9, 2, 3, 4, 5];
            let sum = 0;
            
            for (let i = 0; i < 12; i++) {
                sum += parseInt(juminNo.charAt(i)) * checkSum[i];
            }
            
            const reminder = sum % 11;
            const checkDigit = 11 - reminder;
            const lastDigit = checkDigit % 10;
            
            return parseInt(juminNo.charAt(12)) === lastDigit;
        }
        
    } catch (error) {
        console.error("❌ 데이터 로드 실패:", error);
        alert("데이터 로드 실패.");
    }
}
function saveNew(cNum,dNum,gita,InsuraneCompany,endorseDay,policyNum) {

	console.log(cNum,dNum,gita,InsuraneCompany,endorseDay);
	
    // 저장할 데이터 수집
    const endorseData = [];
    let hasData = false;
    
    for (let i = 1; i <= 6; i++) {
        // 입력 필드 값 가져오기
        const name = document.getElementById(`n_${i}_1`).value.trim();
        const juminNo = document.getElementById(`n_${i}_2`).value.trim();
        const phoneNo = document.getElementById(`n_${i}_3`).value.trim();
        
        // 빈 행 건너뛰기 (이름이 없는 경우)
        if (!name) {
            continue;
        }
        
        hasData = true;
        
        const rowData = {
            rowNum: i,
            name: name,
            juminNo: juminNo,
            phoneNo: phoneNo
        };
        
        endorseData.push(rowData);
    }
    
    // 저장할 데이터가 있는지 확인
    if (!hasData) {
        alert("저장할 데이터가 없습니다. 최소한 성명을 입력해주세요.");
        return;
    }
    
    // 누락된 정보 확인 및 유효성 검사
    for (const item of endorseData) {
        // 성명은 있지만 주민번호가 없는 경우
        if (!item.juminNo) {
            alert(`${item.rowNum}번 행의 주민등록번호가 누락되었습니다.`);
            return;
        }
        
        // 성명은 있지만 핸드폰 번호가 없는 경우
        if (!item.phoneNo) {
            alert(`${item.rowNum}번 행의 핸드폰 번호가 누락되었습니다.`);
            return;
        }
        
        // 주민번호 유효성 확인
        if (!validateJuminNo(item.juminNo.replace(/-/g, ''))) {
            alert(`${item.rowNum}번 행의 주민등록번호가 유효하지 않습니다.`);
            return;
        }
    }
    
    // 오래된 PHP 버전 호환을 위해 FormData 사용
    const form = new FormData();
    
    // 각 행 데이터를 개별 폼 필드로 변환
    for (let i = 0; i < endorseData.length; i++) {
        const item = endorseData[i];
        for (const key in item) {
            form.append(`data[${i}][${key}]`, item[key]);
        }
    }
	form.append('cNum',cNum);
	form.append('dNum',dNum);
	form.append('gita',gita);
	form.append('InsuraneCompany',InsuraneCompany);
	form.append('endorseDay',endorseDay);
	form.append('policyNum',policyNum);
	form.append('userName',SessionManager.getUserInfo().name);
    // Form 데이터로 API 호출
    fetch("https://kjstation.kr/api/kjDaeri/save_new_data_encryption.php", {
        method: "POST",
        body: form
    })
    .then(response => {
        // 텍스트로 응답 받기 (오래된 PHP가 JSON 형식을 제대로 반환하지 못할 수 있음)
        return response.text();
    })
    .then(text => {
        // 응답 텍스트 처리
        let result;
        try {
            // JSON 파싱 시도
            result = JSON.parse(text);
        } catch (e) {
            // 파싱 실패 시 텍스트 응답 그대로 사용
            console.log("응답 텍스트:", text);
            
            // 성공 여부 추정
            if (text.indexOf("성공") !== -1) {
                alert("데이터가 저장되었습니다.");
               // document.getElementById('kj-endorse-modal').style.display = "none";
                return;
            } else {
                alert("저장 실패: 서버 응답을 처리할 수 없습니다.");
                return;
            }
        }
        
        // 정상적인 JSON 응답 처리
        if (result.success) {
            alert("신규로 입력되었습니다. ");
            for (let i = 1; i <= 6; i++) {
				// 입력 필드 값 초기화
				document.getElementById(`n_${i}_1`).value='';
				document.getElementById(`n_${i}_2`).value='';
				document.getElementById(`n_${i}_3`).value='';
			}
        } else {
            alert("저장 실패: " + (result.error || "알 수 없는 오류"));
        }
    })
    .catch(error => {
        console.error("❌ 저장 중 오류 발생:", error);
        alert("데이터 저장 중 오류가 발생했습니다.");
    });
}
// 배서 입력
async function endorsementEntry(cNum) {
    const modal = document.getElementById("kj-endorse-modal");

    modal.style.display = "block";
	// 오늘 날짜를 가져옵니다
	const today = new Date();
	
	// YYYY-MM-DD 형식으로 변환합니다
	const year = today.getFullYear();
	// getMonth()는 0부터 시작하므로 1을 더합니다
	let month = today.getMonth() + 1;
	let day = today.getDate();
	
	// 한 자리수인 경우 앞에 0을 붙입니다
	month = month < 10 ? '0' + month : month;
	day = day < 10 ? '0' + day : day;
	
	const formattedDate = `${year}-${month}-${day}`;
	
	// 날짜 입력 필드의 값을 오늘 날짜로 설정합니다
	document.getElementById('endorseDay').value = formattedDate;
    try {
        const response = await fetch(`https://kjstation.kr/api/kjDaeri/get_2012CertiTable_details2.php?cNum=${cNum}`);
        const data = await response.json();
        if (!data.success) {
            alert(data.error);
            return;
        }
        console.log(data.data);
        // ✅ p_Cnum 요소가 있는지 확인 후 값 설정
        let inputElement = document.getElementById("e_Cnum");
        if (!inputElement) {
            inputElement = document.createElement("input");
            inputElement.type = "text";
            inputElement.id = "e_Cnum";
            modal.appendChild(inputElement);
        }
        inputElement.value = cNum;
        // ✅ ceti_daeriCompany 요소가 있는지 확인 후 값 설정
        const companyInfoElement = document.getElementById("e_ceti_daeriCompany");
        if (companyInfoElement) {
            companyInfoElement.innerHTML = `${data.company} 증권번호 ${data.policyNum}`;
        } else {
            console.warn("⚠️ 'e_ceti_daeriCompany' 요소를 찾을 수 없습니다.");
        }
        // ✅ kj_endorseList tbody 요소 가져오기 및 초기화
        const tbody = document.getElementById('kj_endorseList');
        if (!tbody) {
            console.error("❌ 'kj_endorseList' 요소를 찾을 수 없습니다.");
            return;
        }
        // data.gita 값 확인 및 변환 (문자열일 경우 숫자로 변환)
        const gitaValue = parseInt(data.gita);
        let gitaName='';
        switch(gitaValue){
            case 1 :
                gitaName='대리';
                break;
            case 2 :
                gitaName='탁송';
                break;
            case 3 :
                gitaName='대리/렌트';
                break;
            case 4 :
                gitaName='탁송/렌트';
                break;
        }
            
        console.log("data.gita",data.gita,'gitaName',gitaName);
        tbody.innerHTML = '';
        // ✅ 6줄의 입력 필드 생성 (성명, 주민번호, 핸드폰 번호)
        for (let i = 1; i <= 6; i++) {
            const row = document.createElement('tr');
            row.innerHTML = `
                <td>${i}</td>
                <td><input type='text' class='geInput' id='p_${i}_1' data-row='${i}' data-col='1' placeholder='성명' autocomplete="off"></td>
                <td><input type='text' class='geInput' id='p_${i}_2' data-row='${i}' data-col='2' placeholder='주민번호' maxlength='14' autocomplete="off"></td>
                <td><input type='text' class='geInput' id='p_${i}_3' data-row='${i}' data-col='3' placeholder='핸드폰 번호' maxlength='13' autocomplete="off"></td>
                <td>${gitaName}</td>
            `;
            tbody.appendChild(row);
        }
        // ✅ 저장 버튼이 있는 행 추가
        const saveButtonRow = document.createElement('tr');
        saveButtonRow.innerHTML = `
            <td colspan="9" style="text-align: center; padding: 15px;">
                <button id="saveEndorseButton" class="save-button" style="padding: 8px 20px; background-color: #4CAF50; color: white; border: none; border-radius: 4px; cursor: pointer; font-size: 14px;">저장</button>
            </td>
        `;
        tbody.appendChild(saveButtonRow);
        // ✅ 저장 버튼 이벤트 리스너 등록
        document.getElementById('saveEndorseButton').addEventListener('click', function() {
            saveEndorse(cNum,data.dNum,data.gita,data.InsuraneCompany,document.getElementById('endorseDay').value,data.policyNum); // 여기서는 saveEndorse 함수를 호출해야 합니다
			//certiTable Num,daeriCompany Num, 증권의 성격, 보험회사 , 배서기준일
        });
        
        // 성명 필드 - 한글 입력 처리 개선
        for (let i = 1; i <= 6; i++) {
            const nameInput = document.getElementById(`p_${i}_1`);
            
            // 입력 중인 상태를 추적하는 변수
            let isComposing = false;
            
            // 조합 시작 시 플래그 설정
            nameInput.addEventListener('compositionstart', function() {
                isComposing = true;
            });
            
            // 조합 완료 시 검증 및 플래그 해제
            nameInput.addEventListener('compositionend', function() {
                isComposing = false;
                validateKoreanName(this);
            });
            
            // 필드를 떠날 때 최종 검증
            nameInput.addEventListener('blur', function() {
                validateKoreanName(this);
            });
            
            // 키 입력 시 검증 (조합중이 아닐 때만)
            nameInput.addEventListener('input', function() {
                if (!isComposing) {
                    validateKoreanName(this);
                }
            });
        }
        
        // 한글 이름 검증 함수
        function validateKoreanName(inputElement) {
            // 영어, 숫자, 특수문자 제거 (한글과 공백만 남김)
            setTimeout(() => {
                const originalValue = inputElement.value;
                const koreanOnly = originalValue.replace(/[a-zA-Z0-9~!@#$%^&*()_+|<>?:{}]/g, '');
                
                if (originalValue !== koreanOnly) {
                    inputElement.value = koreanOnly;
                }
            }, 10);
        }
        
        // 주민등록번호 입력 형식 설정 (XXXXXX-XXXXXXX) 및 유효성 검사
        for (let i = 1; i <= 6; i++) {
            const juminInput = document.getElementById(`p_${i}_2`);
            juminInput.addEventListener('input', function(e) {
                let value = e.target.value.replace(/[^0-9-]/g, '');
                if (value.length > 6 && !value.includes('-')) {
                    value = value.substring(0, 6) + '-' + value.substring(6);
                }
                e.target.value = value;
            });
            
            // 주민번호 유효성 검사 (포커스 아웃 시)
            juminInput.addEventListener('blur', function(e) {
                const juminNo = e.target.value.replace(/-/g, '');
                // 비어있는 경우 검사하지 않음
                if (juminNo.length === 0) {
                    return;
                }
                
                // 불완전한 주민번호는 경고만 표시
                if (juminNo.length !== 13) {
                    alert('주민등록번호 13자리를 모두 입력해주세요.');
                    return;
                }
                
                // 유효성 검사 실패 시 시각적 표시만 제공 (포커스 재설정 없음)
                if (!validateJuminNo(juminNo)) {
                    alert('유효하지 않은 주민등록번호입니다.');
                    e.target.style.borderColor = 'red'; // 시각적 표시 추가
                } else {
                    e.target.style.borderColor = 'green'; // 유효한 경우 시각적 표시
                }
            });
        }
        
        // 핸드폰 번호 입력 형식 설정 (010-XXXX-XXXX)
        for (let i = 1; i <= 6; i++) {
            const phoneInput = document.getElementById(`p_${i}_3`);
            phoneInput.addEventListener('input', function(e) {
                let value = e.target.value.replace(/[^0-9]/g, '');
                if (value.length > 3 && value.length <= 7) {
                    value = value.substring(0, 3) + '-' + value.substring(3);
                } else if (value.length > 7) {
                    value = value.substring(0, 3) + '-' + value.substring(3, 7) + '-' + value.substring(7);
                }
                e.target.value = value;
            });
        }
        
        // 주민등록번호 유효성 검사 함수
        function validateJuminNo(juminNo) {
            // 주민번호 유효성 검사 알고리즘
            const checkSum = [2, 3, 4, 5, 6, 7, 8, 9, 2, 3, 4, 5];
            let sum = 0;
            
            for (let i = 0; i < 12; i++) {
                sum += parseInt(juminNo.charAt(i)) * checkSum[i];
            }
            
            const reminder = sum % 11;
            const checkDigit = 11 - reminder;
            const lastDigit = checkDigit % 10;
            
            return parseInt(juminNo.charAt(12)) === lastDigit;
        }
        
    } catch (error) {
        console.error("❌ 데이터 로드 실패:", error);
        alert("데이터 로드 실패.");
    }
}

function saveEndorse(cNum,dNum,gita,InsuraneCompany,endorseDay,policyNum) {

	console.log(cNum,dNum,gita,InsuraneCompany,endorseDay);
	
    // 저장할 데이터 수집
    const endorseData = [];
    let hasData = false;
    
    for (let i = 1; i <= 6; i++) {
        // 입력 필드 값 가져오기
        const name = document.getElementById(`p_${i}_1`).value.trim();
        const juminNo = document.getElementById(`p_${i}_2`).value.trim();
        const phoneNo = document.getElementById(`p_${i}_3`).value.trim();
        
        // 빈 행 건너뛰기 (이름이 없는 경우)
        if (!name) {
            continue;
        }
        
        hasData = true;
        
        const rowData = {
            rowNum: i,
            name: name,
            juminNo: juminNo,
            phoneNo: phoneNo
        };
        
        endorseData.push(rowData);
    }
    
    // 저장할 데이터가 있는지 확인
    if (!hasData) {
        alert("저장할 데이터가 없습니다. 최소한 성명을 입력해주세요.");
        return;
    }
    
    // 누락된 정보 확인 및 유효성 검사
    for (const item of endorseData) {
        // 성명은 있지만 주민번호가 없는 경우
        if (!item.juminNo) {
            alert(`${item.rowNum}번 행의 주민등록번호가 누락되었습니다.`);
            return;
        }
        
        // 성명은 있지만 핸드폰 번호가 없는 경우
        if (!item.phoneNo) {
            alert(`${item.rowNum}번 행의 핸드폰 번호가 누락되었습니다.`);
            return;
        }
        
        // 주민번호 유효성 확인
        if (!validateJuminNo(item.juminNo.replace(/-/g, ''))) {
            alert(`${item.rowNum}번 행의 주민등록번호가 유효하지 않습니다.`);
            return;
        }
    }
    
    // 오래된 PHP 버전 호환을 위해 FormData 사용
    const form = new FormData();
    
    // 각 행 데이터를 개별 폼 필드로 변환
    for (let i = 0; i < endorseData.length; i++) {
        const item = endorseData[i];
        for (const key in item) {
            form.append(`data[${i}][${key}]`, item[key]);
        }
    }
	form.append('cNum',cNum);
	form.append('dNum',dNum);
	form.append('gita',gita);
	form.append('InsuraneCompany',InsuraneCompany);
	form.append('endorseDay',endorseDay);
	form.append('policyNum',policyNum);
	form.append('userName',SessionManager.getUserInfo().name);
    // Form 데이터로 API 호출
    fetch("https://kjstation.kr/api/kjDaeri/save_endorse_data_encryption.php", {
        method: "POST",
        body: form
    })
    .then(response => {
        // 텍스트로 응답 받기 (오래된 PHP가 JSON 형식을 제대로 반환하지 못할 수 있음)
        return response.text();
    })
    .then(text => {
        // 응답 텍스트 처리
        let result;
        try {
            // JSON 파싱 시도
            result = JSON.parse(text);
        } catch (e) {
            // 파싱 실패 시 텍스트 응답 그대로 사용
            console.log("응답 텍스트:", text);
            
            // 성공 여부 추정
            if (text.indexOf("성공") !== -1) {
                alert("데이터가 저장되었습니다.");
               // document.getElementById('kj-endorse-modal').style.display = "none";
                return;
            } else {
                alert("저장 실패: 서버 응답을 처리할 수 없습니다.");
                return;
            }
        }
        
        // 정상적인 JSON 응답 처리
        if (result.success) {
            alert("데이터가 성공적으로 저장되었습니다.");
			// 입력 필드 초기화
				for (let i = 1; i <= 6; i++) {
					document.getElementById(`p_${i}_1`).value = ''; // 이름 초기화
					document.getElementById(`p_${i}_2`).value = ''; // 주민번호 초기화
					document.getElementById(`p_${i}_3`).value = ''; // 핸드폰 번호 초기화
				}
           // document.getElementById('kj-endorse-modal').style.display = "none";  // 배서 신청 화면 닫기
			//document.getElementById('kj-DaeriCompany-modal').style.display = "none"; // 대리운전 회사 화면 닫기 
			//kjEndorseloadTable(1, policyNum, ''); //배서신청자 리스트 
        } else {
            alert("저장 실패: " + (result.error || "알 수 없는 오류"));
        }
    })
    .catch(error => {
        console.error("❌ 저장 중 오류 발생:", error);
        alert("데이터 저장 중 오류가 발생했습니다.");
    });
}

// 주민등록번호 유효성 검사 함수
function validateJuminNo(juminNo) {
    // 주민번호 길이 확인
    if (juminNo.length !== 13) {
        return false;
    }
    
    // 주민번호 유효성 검사 알고리즘
    const checkSum = [2, 3, 4, 5, 6, 7, 8, 9, 2, 3, 4, 5];
    let sum = 0;
    
    for (let i = 0; i < 12; i++) {
        sum += parseInt(juminNo.charAt(i)) * checkSum[i];
    }
    
    const reminder = sum % 11;
    const checkDigit = 11 - reminder;
    const lastDigit = checkDigit % 10;
    
    return parseInt(juminNo.charAt(12)) === lastDigit;
}



//보험료  입력
async function setMonthlyPremium(cNum) {
    const modal = document.getElementById("kj-premium-modal");
    modal.style.display = "block";

    try {
        const response = await fetch(`https://kjstation.kr/api/kjDaeri/get_2012CertiTable_details.php?cNum=${cNum}`);
        const data = await response.json();

        if (!data.success) {
            alert(data.error);
            return;
        }

        console.log(data.data);

        // ✅ p_Cnum 요소가 있는지 확인 후 값 설정
        let inputElement = document.getElementById("p_Cnum");
        if (!inputElement) {
            inputElement = document.createElement("input");
            inputElement.type = "text";
            inputElement.id = "p_Cnum";
            modal.appendChild(inputElement);
        }
        inputElement.value = cNum;

        // ✅ ceti_daeriCompany 요소가 있는지 확인 후 값 설정
        const companyInfoElement = document.getElementById("ceti_daeriCompany");
        if (companyInfoElement) {
            companyInfoElement.innerHTML = `${data.company} 증권번호 ${data.policyNum}`;
        } else {
            console.warn("⚠️ 'ceti_daeriCompany' 요소를 찾을 수 없습니다.");
        }

        // ✅ premiumList tbody 요소 가져오기 및 초기화
        const tbody = document.getElementById('premiumList');
        if (!tbody) {
            console.error("❌ 'premiumList' 요소를 찾을 수 없습니다.");
            return;
        }
        tbody.innerHTML = '';

        // ✅ 6줄의 입력 필드 생성
        for (let i = 1; i <= 7; i++) {
            // 배열 인덱스는 0부터 시작하므로 i-1을 사용
            const rowData = data.data[i-1] || {}; // 해당 인덱스의 데이터가 없으면 빈 객체 사용
            
            const row = document.createElement('tr');
            row.innerHTML = `
                <td>${i}</td>
                <td><input type='text' class='geInput_p' id='p_${i}_1' data-row='${i}' data-col='1' value='${rowData.start_month || "" }' autocomplete="off"></td>
                <td><input type='text' class='geInput_p' id='p_${i}_2' data-row='${i}' data-col='2' value='${rowData.end_month || ""}' autocomplete="off"></td>
                <td><input type='text' class='geInput_p' id='p_${i}_3' data-row='${i}' data-col='3' value='${rowData.monthly_premium1 || ""}' autocomplete="off"></td>
                <td><input type='text' class='geInput_p' id='p_${i}_4' data-row='${i}' data-col='4' value='${rowData.monthly_premium2 || ""}' autocomplete="off"></td>
                <td><input type='text' class='geInput_p' id='p_${i}_5' data-row='${i}' data-col='5' value='${rowData.monthly_premium_total || ""}' readonly></td>
                <td><input type='text' class='geInput_p' id='p_${i}_6' data-row='${i}' data-col='6' value='${rowData.payment10_premium1 || ""}' autocomplete="off"></td>
                <td><input type='text' class='geInput_p' id='p_${i}_7' data-row='${i}' data-col='7' value='${rowData.payment10_premium2 || ""}' autocomplete="off"></td>
                <td><input type='text' class='geInput_p' id='p_${i}_8' data-row='${i}' data-col='8' value='${rowData.payment10_premium_total || ""}' readonly></td>
            `;
            tbody.appendChild(row);
        }

        // ✅ 저장 버튼이 있는 행 추가
        const saveButtonRow = document.createElement('tr');
        saveButtonRow.innerHTML = `
            <td colspan="9" style="text-align: center; padding: 15px;">
                <button id="savePremiumButton" class="save-button" style="padding: 8px 20px; background-color: #4CAF50; color: white; border: none; border-radius: 4px; cursor: pointer; font-size: 14px;">저장</button>
            </td>
        `;
        tbody.appendChild(saveButtonRow);

        // ✅ 저장 버튼 이벤트 리스너 등록
        document.getElementById('savePremiumButton').addEventListener('click', function() {
            savePremiumData(cNum);
        });

        // ✅ 이벤트 리스너 등록 (자동 입력 및 콤마 추가)
        setTimeout(() => {
            for (let i = 1; i <= 7; i++) {
                // 2번째 열 값 입력 시 다음 행 자동 입력
                document.getElementById(`p_${i}_2`).addEventListener("input", () => autoFillNextRow(i));

                // 3, 4번째 값 입력 시 5번째 자동 계산 (월납보험료)
                document.getElementById(`p_${i}_3`).addEventListener("input", () => autoCalculateSum(i, 3, 4, 5));
                document.getElementById(`p_${i}_4`).addEventListener("input", () => autoCalculateSum(i, 3, 4, 5));

                // 6, 7번째 값 입력 시 8번째 자동 계산 (10회납 보험료)
                document.getElementById(`p_${i}_6`).addEventListener("input", () => autoCalculateSum(i, 6, 7, 8));
                document.getElementById(`p_${i}_7`).addEventListener("input", () => autoCalculateSum(i, 6, 7, 8));

                // ✅ 3자리 콤마 자동 추가 기능 적용
                addCommaListener(`p_${i}_1`);
                addCommaListener(`p_${i}_2`);
                addCommaListener(`p_${i}_3`);
                addCommaListener(`p_${i}_4`);
                addCommaListener(`p_${i}_5`);
                addCommaListener(`p_${i}_6`);
                addCommaListener(`p_${i}_7`);
                addCommaListener(`p_${i}_8`);
            }
        }, 100);

    } catch (error) {
        console.error("❌ 데이터 로드 실패:", error);
        alert("데이터 로드 실패.");
    }
}

// 천 단위 콤마 추가 및 포커스 시 제거 기능
function addCommaListener(elementId) {
    const element = document.getElementById(elementId);
    if (!element) return;
    
    // 초기 로딩 시 콤마 추가
    if (element.value) {
        const rawValue = element.value.replace(/,/g, '');
        if (!isNaN(rawValue) && rawValue !== '') {
            element.dataset.rawValue = rawValue; // 원본 값 저장
            element.value = Number(rawValue).toLocaleString('ko-KR');
        }
    }
    
    // 포커스 얻으면 콤마 제거
    element.addEventListener('focus', function() {
        // 저장된 원본 값이 있으면 그 값을 사용, 없으면 콤마 제거
        if (this.dataset.rawValue) {
            this.value = this.dataset.rawValue;
        } else {
            this.value = this.value.replace(/,/g, '');
        }
    });
    
    // 포커스 잃으면 콤마 추가
    element.addEventListener('blur', function() {
        const rawValue = this.value.replace(/,/g, '');
        if (!isNaN(rawValue) && rawValue !== '') {
            this.dataset.rawValue = rawValue; // 원본 값 업데이트
            this.value = Number(rawValue).toLocaleString('ko-KR');
        }
    });
}

// 자동 계산 함수 (콤마 처리 포함)
function autoCalculateSum(row, col1, col2, resultCol) {
    const value1Element = document.getElementById(`p_${row}_${col1}`);
    const value2Element = document.getElementById(`p_${row}_${col2}`);
    const resultElement = document.getElementById(`p_${row}_${resultCol}`);
    
    if (!value1Element || !value2Element || !resultElement) return;
    
    // 콤마 제거 후 숫자 변환
    const value1 = parseInt(value1Element.value.replace(/,/g, '')) || 0;
    const value2 = parseInt(value2Element.value.replace(/,/g, '')) || 0;
    
    // 합계 계산
    const sum = value1 + value2;
    
    // 원본 값 저장 및 콤마 형식으로 표시
    resultElement.dataset.rawValue = sum.toString();
    resultElement.value = sum.toLocaleString('ko-KR');
}

// 다음 행 자동 입력 함수 (콤마 처리 포함)
function autoFillNextRow(row) {
    if (row >= 7) return; // 6번째 행이면 더 이상 다음 행이 없음
    
    const currentEndMonth = document.getElementById(`p_${row}_2`);
    const nextStartMonth = document.getElementById(`p_${row+1}_1`);
    
    if (!currentEndMonth || !nextStartMonth) return;
    
    // 콤마 제거 후 숫자 변환
    const endMonthValue = parseInt(currentEndMonth.value.replace(/,/g, '')) || 0;
    
    if (endMonthValue > 0) {
        // 다음 행의 시작월은 현재 행의 종료월 + 1
        const nextStartValue = endMonthValue + 1;
        
        // 원본 값 저장 및 콤마 형식으로 표시
        nextStartMonth.dataset.rawValue = nextStartValue.toString();
        nextStartMonth.value = nextStartValue.toLocaleString('ko-KR');
    }
}


function savePremiumData(cNum) {
    // 저장할 데이터 수집
    const premiumData = [];
    
    for (let i = 1; i <= 7; i++) {
        // 빈 행 건너뛰기 (시작 월이나 종료 월이 없는 경우)
        const startMonth = document.getElementById(`p_${i}_1`).value.replace(/,/g, "");
        const endMonth = document.getElementById(`p_${i}_2`).value.replace(/,/g, "");
        
        if (!startMonth && !endMonth) {
            continue;
        }
        
        const rowData = {
            cNum: cNum,
            rowNum: i,
            start_month: startMonth,
            end_month: endMonth,
            monthly_premium1: document.getElementById(`p_${i}_3`).value.replace(/,/g, ""),
            monthly_premium2: document.getElementById(`p_${i}_4`).value.replace(/,/g, ""),
            monthly_premium_total: document.getElementById(`p_${i}_5`).value.replace(/,/g, ""),
            payment10_premium1: document.getElementById(`p_${i}_6`).value.replace(/,/g, ""),
            payment10_premium2: document.getElementById(`p_${i}_7`).value.replace(/,/g, ""),
            payment10_premium_total: document.getElementById(`p_${i}_8`).value.replace(/,/g, "")
        };
        
        premiumData.push(rowData);
    }
    
    // 저장할 데이터가 있는지 확인
    if (premiumData.length === 0) {
        alert("저장할 데이터가 없습니다.");
        return;
    }
    
    // 오래된 PHP 버전 호환을 위해 FormData 사용
    const form = new FormData();
    
    // 각 행 데이터를 개별 폼 필드로 변환
    for (let i = 0; i < premiumData.length; i++) {
        const item = premiumData[i];
        for (const key in item) {
            form.append(`data[${i}][${key}]`, item[key]);
        }
    }
    
    // Form 데이터로 API 호출
    fetch("https://kjstation.kr/api/kjDaeri/save_premium_data.php", {
        method: "POST",
        body: form
    })
    .then(response => {
        // 텍스트로 응답 받기 (오래된 PHP가 JSON 형식을 제대로 반환하지 못할 수 있음)
        return response.text();
    })
    .then(text => {
        // 응답 텍스트 처리
        let result;
        try {
            // JSON 파싱 시도
            result = JSON.parse(text);
        } catch (e) {
            // 파싱 실패 시 텍스트 응답 그대로 사용
            console.log("응답 텍스트:", text);
            
            // 성공 여부 추정
            if (text.indexOf("성공") !== -1) {
                alert("데이터가 저장되었습니다.");
                document.getElementById('kj-premium-modal').style.display = "none";
                return;
            } else {
                alert("저장 실패: 서버 응답을 처리할 수 없습니다.");
                return;
            }
        }
        
        // 정상적인 JSON 응답 처리
        if (result.success) {
            alert("데이터가 성공적으로 저장되었습니다.");
           // document.getElementById('kj-premium-modal').style.display = "none";
        } else {
            alert("저장 실패: " + (result.error || "알 수 없는 오류"));
        }
    })
    .catch(error => {
        console.error("❌ 저장 중 오류 발생:", error);
        alert("데이터 저장 중 오류가 발생했습니다.");
    });
}



/**
 * 납입방식을 전환하는 함수
 * @param {Event} event - 클릭 이벤트 객체
 * @param {number} cNum - 전환할 항목의 번호
 */
async function togglePaymentMethod(event, cNum) {
    event.preventDefault(); // 기본 이벤트 방지
    
   let divi='';
 
	const paymenContent = document.getElementById(`payment-${cNum}`).textContent;
	console.log('paymenContent',paymenContent);
	if(paymenContent=='정상납'){
		 divi=1;
	}else{
		divi=2;
	}

    try {
        // API 호출
        const response = await fetch(`https://kjstation.kr/api/kjDaeri/diviChange.php?cNum=${cNum}&divi=${divi}`);
        const data = await response.json();
        
        // 응답 확인
        if (!data.success) {
            alert(data.error);
            return;
        } else {
            alert('납입방식 변경 완료');
            
            // API 응답에서 새로운 납입방식 이름을 가져와서 버튼 텍스트 업데이트
            if ( data.diviName) {
               document.getElementById(`payment-${cNum}`).textContent = data.diviName;
            }
        }
        
        console.log(data.diviName);
    } catch (error) {
        console.error("❌ 데이터 로드 실패:", error);
        alert("데이터 로드 실패.");
    }
}