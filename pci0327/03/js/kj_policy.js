/** kjstation.kr  증권별코드**/
let isFetchingPolicy = false;  // fetch 중복 실행 방지 변수
let fetchCallCount3 = 0;  // API 호출 횟수 추적 변수
let currentPage = 1;  // 현재 페이지
let itemsPerPage = 15;  // 페이지당 표시할 항목 수
let policyData = [];  // 전체 데이터 저장 변수

function kj_policy(){
	// 오늘 날짜 가져오기
	const today = new Date();
	const todayStr = today.getFullYear() + '-' + String(today.getMonth() + 1).padStart(2, '0') + '-' + String(today.getDate()).padStart(2, '0');

	// 1년 전 날짜 계산하기
	const oneYearAgo = new Date(today);
	oneYearAgo.setFullYear(today.getFullYear() - 1);
	const oneYearAgoStr = oneYearAgo.getFullYear() + '-' + String(oneYearAgo.getMonth() + 1).padStart(2, '0') + '-' + String(oneYearAgo.getDate()).padStart(2, '0');

	// 이 문자열들(todayStr, oneYearAgoStr)을 사용하여 폼이나 API에 전달
	console.log('오늘 날짜 (yyyy-MM-dd 형식):', todayStr); // 예: "2025-03-28"
	console.log('1년 전 날짜 (yyyy-MM-dd 형식):', oneYearAgoStr); // 예: "2024-03-28"
	
	const pageContent = document.getElementById('page-content');
	pageContent.innerHTML = "";
	
	const fieldContents= `<div class="kje-list-container">
		<!-- 검색 영역 -->
		<div class="kje-list-header">
			<div class="kje-left-area">
				<div class="kje-search-area">
					<div class="select-container">
						<input type='date' id='fromDate' class='date-range-field' value='${oneYearAgoStr}' >
						<input type='date' id='toDate' class='date-range-field' value='${todayStr}' >

						<button class="sms-stats-button" onclick="requestDailySearch()">검색</button>
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
				
			</div>
		</div>

		<!-- 로딩 인디케이터 -->
		<div id="kjPoLoadingIndicator" class="kj-loading-indicator" style="display: none;">
			<div class="loading-spinner"></div>
			<div class="loading-text">데이터를 불러오는 중...</div>
		</div>

		<!-- 리스트 영역 -->
		<div class="kje-list-content">
			<div class="kje-data-table-container">
				<table class="kje-data-table">
					<thead>
						<tr>
							<th class="col-1">No</th>
							<th class="col-business-num">증권번호</th>
							<th class="col-school">회사명</th>
							<th class="col-students">계약자</th>
							<th class="col-insurance">소유자</th>
							<th class="col-insurance2">주민번호</th>
							<th class="col-insurance2">보험회사</th>
							<th class="col-date">계약일</th>
							<th class="col-action">회차</th>
							<th class="col-phone">인원</th>
							<th class="col-contact">max</th>
							<th class="col-policy">코드</th>
							<th class="col-status">비밀번호</th>
							<th class="col-manager">인증번호</th>
							<th class="col-memo">단체율</th>	
							<th class="col-manager">할인율</th>
						</tr>
					</thead>
					<tbody id="kje-policyList">
						<!-- 데이터가 여기에 동적으로 로드됨 -->
					</tbody>
				</table>
			</div>
		</div>

		<!-- 페이지네이션 -->
		<div class="policy-pagination" id="policy-pagination"></div>
	</div>`
	
	pageContent.innerHTML = fieldContents;
	
	// 페이지네이션 컨테이너가 제대로 생성되었는지 확인
	console.log("페이지네이션 컨테이너:", document.querySelector('.policy-pagination'));
	
	// 약간의 지연 후 한 번만 호출
	setTimeout(() => {
		kjPolicyloadSearchTable();  // 증권번호
	}, 300);
	
	// 로딩 및 페이지네이션 기능을 위한 스타일 추가
	addCustomStyles();
}

// 로딩 표시 함수
function showPoLoading() {
	const loadingIndicator = document.getElementById('kjPoLoadingIndicator');
	if (loadingIndicator) {
		loadingIndicator.style.display = 'flex';
	}
}

// 로딩 숨김 함수
function hidePoLoading() {
	const loadingIndicator = document.getElementById('kjPoLoadingIndicator');
	if (loadingIndicator) {
		loadingIndicator.style.display = 'none';
	}
}

function kjPolicyloadSearchTable() {
	// 여기에 fetch 코드 넣기
	fetchCallCount3++;
	console.log(`Fetch Call Count: ${fetchCallCount3}`);

	// 이미 실행 중이면 중복 호출 방지
	if (isFetchingPolicy) return;
	
	// 실행 상태로 변경
	isFetchingPolicy = true;
	const sj = 'policy_';

	// 로딩 표시
	showPoLoading();

	const fromDate = document.getElementById("fromDate").value;
	const toDate = document.getElementById("toDate").value;
	
	// API 요청
	fetch(`https://kjstation.kr/api/kjDaeri/policySearch.php?sj=${sj}&fromDate=${fromDate}&toDate=${toDate}`)
		.then(response => {
			if (!response.ok) {
				throw new Error(`HTTP error! Status: ${response.status}`);
			}
			return response.json();
		})
		.then(data => {
			// 전체 데이터 저장
			policyData = data;
			
			// 페이지 초기화 및 데이터 표시
			currentPage = 1;
			renderPolicyTable(data);
			
			// 직접 DOM에 페이지네이션 생성
			createPagination(data);
		})
		.catch((error) => {
			console.error("Error details:", error);
			alert("데이터를 불러오는 중 오류가 발생했습니다.");
		})
		.finally(() => {
			// 로딩 숨김
			hidePoLoading();
			
			// 실행 완료 후 상태 변경
			isFetchingPolicy = false;
		});
}

// 증권 테이블 렌더링 함수
function renderPolicyTable(responseData) {
	const tableBody = document.getElementById('kje-policyList');
	
	// 테이블 초기화
	tableBody.innerHTML = '';
	
	// 데이터가 없거나 유효하지 않은 경우 처리
	if (!responseData || !responseData.success || !responseData.data || responseData.data.length === 0) {
		const noDataRow = document.createElement('tr');
		noDataRow.innerHTML = '<td colspan="16" style="text-align: center;">데이터가 없습니다.</td>';
		tableBody.appendChild(noDataRow);
		return;
	}
	
	// 현재 페이지에 표시할 데이터 계산
	const startIndex = (currentPage - 1) * itemsPerPage;
	const endIndex = Math.min(startIndex + itemsPerPage, responseData.data.length);
	const currentPageData = responseData.data.slice(startIndex, endIndex);
	
	// 인원 합계 계산
	let totalInwon = 0;
	currentPageData.forEach(item => {
		const inwon = parseInt(item.inwon || 0);
		if (!isNaN(inwon)) {
			totalInwon += inwon;
		}
	});
	
	// 각 데이터 항목에 대해 행 생성
	currentPageData.forEach((item, index) => {
		const row = document.createElement('tr');
		const icompanyType = { "1": "흥국", "2": "DB", "3": "KB", "4": "현대", "5": "현대", "6": "롯데", "7": "MG", "8": "삼성" };
		const insuranceCompany = icompanyType[item.insurance];
		
		// 각 열에 데이터 설정
		row.innerHTML = `
			<td><a href="#" class="kje-btn-link_1"
			   onClick="policyNumDetail('${item.certi}')">
			   ${index + 1}
			</a></td>
			<td>${item.certi || ''}</td>
			<td>${item.company || ''}</td>
			<td>${item.name || ''}</td>
			<td>${item.owner || ''}</td>
			<td>${item.jumin || ''}</td>
			<td>${insuranceCompany || ''}</td>
			<td>${item.sigi || ''}</td>
			<td>${item.nab || ''}</td>
			<td class="kje-preiminum">${item.inwon || ''}</td>
			<td class="kje-preiminum">${item.maxInwon || ''}</td>
			<td>${item.cord || ''}</td>
			<td>${item.cordPass || ''}</td>
			<td>${item.cordCerti || ''}</td>
			<td>${item.yearRate || '0'}%</td>
			<td>${item.harinRate || '0'}%</td>
		`;
		
		// 행을 테이블에 추가
		tableBody.appendChild(row);
	});
	
	// 합계 행 추가
	const totalRow = document.createElement('tr');
	totalRow.className = 'kje-total-row';
	totalRow.innerHTML = `
		<td colspan="9" style="text-align: right; font-weight: bold;">인원 합계:</td>
		<td class="kje-preiminum" style="font-weight: bold;">${totalInwon}</td>
		<td colspan="6"></td>
	`;
	tableBody.appendChild(totalRow);
	
	// 결과 표시 (필요한 경우)
	const currentSituation = document.getElementById('currentSituation');
	if (currentSituation) {
		currentSituation.textContent = `총 ${responseData.data.length}개의 증권이 검색되었습니다. (${startIndex + 1}-${endIndex}/${responseData.data.length})`;
	}
}

// 직접 DOM 조작으로 페이지네이션 생성
function createPagination(responseData) {
	// 페이지네이션 컨테이너 찾기
	const paginationContainer = document.querySelector('.policy-pagination');
	if (!paginationContainer) {
		console.error('페이지네이션 컨테이너를 찾을 수 없습니다.');
		return;
	}
	
	// 이전 페이지네이션 내용 삭제
	paginationContainer.innerHTML = '';
	
	// 데이터가 없거나 유효하지 않은 경우 처리
	if (!responseData || !responseData.success || !responseData.data || responseData.data.length === 0) {
		return;
	}
	
	// 전체 페이지 수 계산
	const totalPages = Math.ceil(responseData.data.length / itemsPerPage);
	
	// 한 페이지만 있는 경우 페이지네이션 표시하지 않음
	if (totalPages <= 1) {
		return;
	}
	
	// 이전 버튼
	const prevButton = document.createElement('a');
	prevButton.href = 'javascript:void(0);';
	prevButton.className = 'pagination-btn prev-btn' + (currentPage === 1 ? ' disabled' : '');
	prevButton.innerHTML = '&laquo;';
	if (currentPage > 1) {
		prevButton.onclick = function() {
			goToPage(currentPage - 1);
		};
	}
	paginationContainer.appendChild(prevButton);
	
	// 페이지 번호 버튼들
	const maxPageButtons = 5;
	let startPage = Math.max(1, currentPage - Math.floor(maxPageButtons / 2));
	let endPage = Math.min(totalPages, startPage + maxPageButtons - 1);
	
	// 시작 페이지 조정
	if (endPage - startPage + 1 < maxPageButtons) {
		startPage = Math.max(1, endPage - maxPageButtons + 1);
	}
	
	// 첫 페이지 버튼
	if (startPage > 1) {
		const firstPageBtn = document.createElement('a');
		firstPageBtn.href = 'javascript:void(0);';
		firstPageBtn.className = 'pagination-btn';
		firstPageBtn.textContent = '1';
		firstPageBtn.onclick = function() {
			goToPage(1);
		};
		paginationContainer.appendChild(firstPageBtn);
		
		// 생략 부호 추가
		if (startPage > 2) {
			const ellipsis = document.createElement('span');
			ellipsis.className = 'pagination-ellipsis';
			ellipsis.textContent = '...';
			paginationContainer.appendChild(ellipsis);
		}
	}
	
	// 페이지 번호 버튼 생성
	for (let i = startPage; i <= endPage; i++) {
		const pageBtn = document.createElement('a');
		pageBtn.href = 'javascript:void(0);';
		pageBtn.className = 'pagination-btn' + (i === currentPage ? ' active' : '');
		pageBtn.textContent = i;
		
		if (i !== currentPage) {
			pageBtn.onclick = function() {
				goToPage(i);
			};
		}
		
		paginationContainer.appendChild(pageBtn);
	}
	
	// 마지막 페이지 버튼
	if (endPage < totalPages) {
		// 생략 부호 추가
		if (endPage < totalPages - 1) {
			const ellipsis = document.createElement('span');
			ellipsis.className = 'pagination-ellipsis';
			ellipsis.textContent = '...';
			paginationContainer.appendChild(ellipsis);
		}
		
		const lastPageBtn = document.createElement('a');
		lastPageBtn.href = 'javascript:void(0);';
		lastPageBtn.className = 'pagination-btn';
		lastPageBtn.textContent = totalPages;
		lastPageBtn.onclick = function() {
			goToPage(totalPages);
		};
		paginationContainer.appendChild(lastPageBtn);
	}
	
	// 다음 버튼
	const nextButton = document.createElement('a');
	nextButton.href = 'javascript:void(0);';
	nextButton.className = 'pagination-btn next-btn' + (currentPage === totalPages ? ' disabled' : '');
	nextButton.innerHTML = '&raquo;';
	if (currentPage < totalPages) {
		nextButton.onclick = function() {
			goToPage(currentPage + 1);
		};
	}
	paginationContainer.appendChild(nextButton);
}

// 페이지 이동 함수
function goToPage(page) {
	if (page < 1 || !policyData || !policyData.data) return;
	
	const totalPages = Math.ceil(policyData.data.length / itemsPerPage);
	if (page > totalPages) return;
	
	currentPage = page;
	renderPolicyTable(policyData);
	createPagination(policyData);
	
	// 스크롤을 테이블 상단으로 이동
	const tableContainer = document.querySelector('.kje-data-table-container');
	if (tableContainer) {
		tableContainer.scrollIntoView({ behavior: 'smooth' });
	}
}

// 날짜 검색 함수
function requestDailySearch() {
	currentPage = 1; // 검색 시 첫 페이지로 초기화
	kjPolicyloadSearchTable();
}

// 스타일 추가 함수
function addCustomStyles() {
	const style = document.createElement('style');
	style.textContent = `
		/* 로딩 인디케이터 스타일 */
		.kj-loading-indicator {
			position: fixed;
			top: 0;
			left: 0;
			right: 0;
			bottom: 0;
			background-color: rgba(255, 255, 255, 0.7);
			display: flex;
			flex-direction: column;
			justify-content: center;
			align-items: center;
			z-index: 9999;
		}
		
		.loading-spinner {
			display: flex;
			flex-direction: column;
			align-items: center;
			justify-content: center;
			width: 100%;
		}
		
		.loading-text {
			margin-top: 10px;
			font-weight: bold;
		}
		
		@keyframes spin {
			0% { transform: rotate(0deg); }
			100% { transform: rotate(360deg); }
		}
		
		/* 페이지네이션 스타일 */
		.policy-pagination {
			display: flex;
			justify-content: center;
			align-items: center;
			margin: 20px 0;
			flex-wrap: wrap;
		}
		
		.pagination-btn {
			display: inline-block;
			padding: 2px 12px;
			margin: 0 4px;
			border: 1px solid #ddd;
			color: #333;
			text-decoration: none;
			border-radius: 6px;
			cursor: pointer;
			background-color: #fff;
		}
		
		.pagination-btn:hover {
			background-color: #f5f5f5;
		}
		
		.pagination-btn.active {
			background-color: #8E6C9D; /* pagenation 통일, 통계 버튼  모달 버튼*/
			color: white;
			border-color: #8E6C9D; /* pagenation 통일, 통계 버튼  모달 버튼*/
			cursor: default;
		}
		
		.pagination-btn.disabled {
			color: #aaa;
			cursor: not-allowed;
			pointer-events: none;
		}
		
		.pagination-ellipsis {
			margin: 0 8px;
			color: #666;
		}
		
		
	`;
	
	document.head.appendChild(style);
}

//증권번호 디테일을 찾기  2012Certi num
async function policyNumDetail(num){ 

	// 오늘 날짜 가져오기
	const today = new Date();
	const todayStr = today.getFullYear() + '-' + String(today.getMonth() + 1).padStart(2, '0') + '-' + String(today.getDate()).padStart(2, '0');

	// 1년 전 오늘 날짜 계산하기
	const oneYearAgo = new Date(today);
	oneYearAgo.setFullYear(today.getFullYear() - 1);
	const oneYearAgoStr = oneYearAgo.getFullYear() + '-' + String(oneYearAgo.getMonth() + 1).padStart(2, '0') + '-' + String(oneYearAgo.getDate()).padStart(2, '0');

	console.log('오늘 날짜:', todayStr);
	console.log('1년 전 오늘 날짜:', oneYearAgoStr);

   const modal = document.getElementById("policyNum-modal");
   modal.style.display = "block";
  // 날짜 선택 필드
  searchField2 = `
	<div class="kje-list-header">
		<div class="kje-left-area">
			<div class="kje-search-area">
			    <input type='date' id='fromDate' class='date-range-field' value='${oneYearAgoStr}' onchange='dailyEndorseRequest("",this.value,"","",1)'>
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
  document.getElementById("policyNum_daeriCompany").innerHTML = searchField2;

   // 서버와 통신 

   showLoading();
  
  // FormData 객체 생성
  const formData = new FormData();
 formData.append('num', num);
 
  
  try {
    // API 요청 전송
    const response = await fetch(`https://kjstation.kr/api/kjDaeri/policyNumDetail.php`, {
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
    processPolicyNum(result);
    
    // 모달 표시
   
    
  } catch (error) {
    console.error('데이터 조회 오류:', error);
    alert('데이터 조회 중 오류가 발생했습니다: ' + error.message);
  } finally {
    // 로딩 표시 제거
    hideLoading();
  }
}

//증권상세 정리

function processPolicyNum(result){

	 let c_list = '';
    c_list = ` <table class='pjTable'>
                <thead>
                    
                    <tr>
                        <th width='15%'>증권번호</th>
                        <th width='15%'>회사명</th>
                        <th width='15%'>계약자</th>
                        <th width='15%'>주민번호</th>
                        <th width='10%'>계약일</th>
                        <th width='5%'>회차</th>
                        <th width='5%'>인원</th>
                        <th width='10%'>단체</th>
                        <th width='10%'>할인율</th>
                    </tr>
                </thead>`;
    
    c_list += `<tbody>`;


	c_list += `
            <tr>
                <td><input type='text' id='p-certi' class='geInput2' autocomplete="off" value="${result.data[0].certi}"</td>
				<td><input type='text' id='p-company' class='geInput2' autocomplete="off" value="${result.data[0].company}"</td>
				<td><input type='text' id='p-name' class='geInput2' autocomplete="off" value="${result.data[0].name}"</td>
				<td><input type='text' id='p-jumin' class='geInput2' autocomplete="off" value="${result.data[0].jumin}"</td>
				<td><input type='date' id='p-sigi' class='geInput2' autocomplete="off" value="${result.data[0].sigi}"</td>
				<td><input type='text' id='p-nab' class='geInput2' autocomplete="off" value="${result.data[0].nab}"</td>
				<td class='center-align'>${result.data[0].inwon}</td>
				<td><input type='text' id='p-yearRate' class='geInput2' autocomplete="off" value="${result.data[0].yearRate}"</td>
				<td><input type='text' id='p-harinRate' class='geInput2' autocomplete="off" value="${result.data[0].harinRate}"</td> 
            </tr>
			<tr>
                <td colspan='9' class='right-align'>
		         <button id="saveEndorseButton2" onclick="p_preminumInsert('${result.data[0].certi}')" class="save-button" style="padding: 4px 10px; background-color: #4CAF50; color: white; border: none; border-radius: 4px; cursor: pointer; font-size: 14px;">보험료 입력</button>
		         <button id="saveEndorseButton2" class="save-button" style="padding: 4px 10px; background-color: #4CAF50; color: white; border: none; border-radius: 4px; cursor: pointer; font-size: 14px;">저장</button>
	           </td>
				
            </tr>
		`;
    c_list += `</tbody>
              </table>`;

		document.getElementById('m_policyNum').innerHTML = c_list;

		if (result.data[0].p_preminum == 1) {
			alert(result.data[0].certi + " 연령별 보험료 입력하세요");
			p_preminumInsert(result.data[0].certi);
		}


		// 통계 // 
		InsurancePremiumStatistics(result.data[0].certi);
}
async function InsurancePremiumStatistics(certi) {

	document.getElementById('Insurance_premium_statistics').innerHTML='';
  // 로딩바 표시
  const loadingElement = document.createElement('div');
  loadingElement.id = 'loading-spinner';
  loadingElement.style.cssText = `
    position: fixed;
    top: 0;
    left: 0;
    width: 100%;
    height: 100%;
    background-color: rgba(0, 0, 0, 0.5);
    display: flex;
    flex-direction: column;
    justify-content: center;
    align-items: center;
    z-index: 9999;
  `;
  
  const spinnerElement = document.createElement('div');
  spinnerElement.style.cssText = `
    border: 5px solid #f3f3f3;
    border-top: 5px solid #3498db;
    border-radius: 50%;
    width: 50px;
    height: 50px;
    animation: spin 2s linear infinite;
  `;
  
  const textElement = document.createElement('div');
  textElement.textContent = '데이터를 불러오는 중입니다...';
  textElement.style.cssText = `
    color: white;
    margin-top: 15px;
    font-size: 16px;
  `;
  
  // 애니메이션 스타일 추가
  const styleElement = document.createElement('style');
  styleElement.textContent = `
    @keyframes spin {
      0% { transform: rotate(0deg); }
      100% { transform: rotate(360deg); }
    }
  `;
  document.head.appendChild(styleElement);
  
  // 로딩 요소 추가
  loadingElement.appendChild(spinnerElement);
  loadingElement.appendChild(textElement);
  document.body.appendChild(loadingElement);
  
  try {
    const response = await fetch(`https://kjstation.kr/api/kjDaeri/PolicyNumInsurancePremiumStatistics.php?certi=${certi}`);
    const data = await response.json();
    
    // 로딩바 제거
    document.body.removeChild(loadingElement);
    
    if (!data.success) {
      alert(data.error);
      return;
    }
    
    // 성공 시 결과 데이터 반환 (나중에 처리)
    
	ageInsurancePremiums(data);
    
  } catch (error) {
    // 로딩바 제거
    document.body.removeChild(loadingElement);
    
    console.error("❌ 데이터 로드 실패:", error);
    alert("데이터 로드 실패.");
  }
}
//연령별 보험료 표현 
function ageInsurancePremiums(data) {
	
  // 테이블 헤더 준비
  let premiumStatistics = `
    <table class='pjTable'>
      <thead>
        <tr>
          <th width='15%' class='center-align'>연령</th>
          <th width='15%' class='center-align'>인원</th>
          <th width='17.5%'  class='center-align'>1/10 보험료</th>
          <th width='17.5%' class='center-align'>회사보험료</th>
          <th  width='17.5%' class='center-align'>환산</th>
          <th width='17.5%'  class='center-align'>월보험료</th>
        </tr>
      </thead>
      <tbody>
  `;
  
  // 연령 구간별 데이터 행 추가
  const ageRanges = data.age_range_premiums;
  
  // 객체의 키를 가져와서 정렬 (연령 구간을 오름차순으로 정렬)
  const sortedRangeKeys = Object.keys(ageRanges).sort((a, b) => {
    const aStart = parseInt(a.split('-')[0]);
    const bStart = parseInt(b.split('-')[0]);
    return aStart - bStart;
  });
  
  // 정렬된 키를 기준으로 행 추가
  sortedRangeKeys.forEach(range => {
    const rangeData = ageRanges[range];
    
    premiumStatistics += `
      <tr>
        <td class='center-align'>${rangeData.start_month}~${rangeData.end_month}세</td>
        <td class='center-align'>${rangeData.driver_count}명</td>
        <td class='right-align'>${rangeData.premium_total.toLocaleString()}원</td>
        <td class='right-align'>${rangeData.total_adjusted_premium.toLocaleString()}원</td>
        <td class='right-align'>${rangeData.total_adjusted_premium_monthly.toLocaleString()}원</td>
        <td class='right-align'>${rangeData.total_month_adjusted_premium.toLocaleString()}원</td>
      </tr>
    `;
  });
  
  // 요약 정보 추가 (전체 합계)
  premiumStatistics += `
      </tbody>
      <tfoot>
        <tr>
          <th class='center-align'>합계</th>
          <th class='center-align'>${data.summary.total_drivers}명</th>
          <th class='right-align'>${data.summary.total_premium.toLocaleString()}원</th>
          <th class='right-align'>${data.summary.total_adjusted_premium.toLocaleString()}원</th>
          <th class='right-align'>${data.summary.total_adjusted_premium_monthly.toLocaleString()}원</th>
          <th class='right-align'>${data.summary.total_month_adjusted_premium.toLocaleString()}원</th>
        </tr>
      </tfoot>
    </table>
    
    <div class="summary-stats">
      <p><strong>총 회원 수:</strong> ${data.member_count}명</p>
      <p><strong>1인당 평균 보험료:</strong> <span class='right-align'>${data.summary.average_premium_per_driver.toLocaleString()}원</span></p>
      <p><strong>1인당 평균 할인할증 적용 보험료:</strong> <span class='right-align'>${data.summary.average_adjusted_premium_per_driver.toLocaleString()}원</span></p>
    </div>
  `;
  
  // 결과 HTML 삽입
  document.getElementById('Insurance_premium_statistics').innerHTML = premiumStatistics;
  
 
}
//증권별 보험회사 납입하는 보험료 입력 
async function p_preminumInsert(certi){

	  console.log('certi',certi);


	
	const modal = document.getElementById("po-premium-modal");
    modal.style.display = "block";

	try {
        const response = await fetch(`https://kjstation.kr/api/kjDaeri/get_kj_insurance_premium_data.php?certi=${certi}`);
        const data = await response.json();

        if (!data.success) {
            alert(data.error);
            return;
        }

        console.log(data.data);

        // ✅ p_Cnum 요소가 있는지 확인 후 값 설정
        let inputElement = document.getElementById("po_Cnum");
        if (!inputElement) {
            inputElement = document.createElement("input");
            inputElement.type = "text";
            inputElement.id = "po_Cnum";
            modal.appendChild(inputElement);
        }
        inputElement.value = certi;

        // ✅ po_ceti_daeriCompany 요소가 있는지 확인 후 값 설정
        const companyInfoElement = document.getElementById("po_ceti_daeriCompany");
        if (companyInfoElement) {
            companyInfoElement.innerHTML = `증권번호 ${certi}`;
        } else {
            console.warn("⚠️ 'po_ceti_daeriCompany' 요소를 찾을 수 없습니다.");
        }

        // ✅ policyPremiumList tbody 요소 가져오기 및 초기화
        const tbody = document.getElementById('policyPremiumList');
        if (!tbody) {
            console.error("❌ 'policyPremiumList' 요소를 찾을 수 없습니다.");
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
                <td><input type='text' class='geInput_p' id='po_${i}_1' data-row='${i}' data-col='1' value='${rowData.start_month || "" }' autocomplete="off"></td>
                <td><input type='text' class='geInput_p' id='po_${i}_2' data-row='${i}' data-col='2' value='${rowData.end_month || ""}' autocomplete="off"></td>
                <td><input type='text' class='geInput_p' id='po_${i}_6' data-row='${i}' data-col='6' value='${rowData.payment10_premium1 || ""}' autocomplete="off"></td>
                <td><input type='text' class='geInput_p' id='po_${i}_7' data-row='${i}' data-col='7' value='${rowData.payment10_premium2 || ""}' autocomplete="off"></td>
                <td><input type='text' class='geInput_p' id='po_${i}_8' data-row='${i}' data-col='8' value='${rowData.payment10_premium_total || ""}' readonly></td>
            `;
            tbody.appendChild(row);
        }

        // ✅ 저장 버튼이 있는 행 추가
        const saveButtonRow = document.createElement('tr');
        saveButtonRow.innerHTML = `
            <td colspan="9" style="text-align: center; padding: 15px;">
                <button id="saveIPremiumButton" class="save-button" style="padding: 8px 20px; background-color: #4CAF50; color: white; border: none; border-radius: 4px; cursor: pointer; font-size: 14px;">저장</button>
            </td>
        `;
        tbody.appendChild(saveButtonRow);

        // ✅ 저장 버튼 이벤트 리스너 등록
        document.getElementById('saveIPremiumButton').addEventListener('click', function() {
            saveIPremiumData(certi);
        });

        // ✅ 이벤트 리스너 등록 (자동 입력 및 콤마 추가)
        setTimeout(() => {
            for (let i = 1; i <= 7; i++) {
                // 2번째 열 값 입력 시 다음 행 자동 입력
                document.getElementById(`po_${i}_2`).addEventListener("input", () => po_autoFillNextRow(i));

              

                // 6, 7번째 값 입력 시 8번째 자동 계산 (10회납 보험료)
                document.getElementById(`po_${i}_6`).addEventListener("input", () => po_autoCalculateSum(i, 6, 7, 8));
                document.getElementById(`po_${i}_7`).addEventListener("input", () => po_autoCalculateSum(i, 6, 7, 8));

                // ✅ 3자리 콤마 자동 추가 기능 적용
                addCommaListener(`po_${i}_1`);
                addCommaListener(`po_${i}_2`);
                addCommaListener(`po_${i}_3`);
                addCommaListener(`po_${i}_4`);
                addCommaListener(`po_${i}_5`);
                addCommaListener(`po_${i}_6`);
                addCommaListener(`po_${i}_7`);
                addCommaListener(`po_${i}_8`);
            }
        }, 100);

    } catch (error) {
        console.error("❌ 데이터 로드 실패:", error);
        alert("데이터 로드 실패.");
    }
	  
}

// 자동 계산 함수 (콤마 처리 포함)
function po_autoCalculateSum(row, col1, col2, resultCol) {
    const value1Element = document.getElementById(`po_${row}_${col1}`);
    const value2Element = document.getElementById(`po_${row}_${col2}`);
    const resultElement = document.getElementById(`po_${row}_${resultCol}`);
    
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
function po_autoFillNextRow(row) {
    if (row >= 7) return; // 6번째 행이면 더 이상 다음 행이 없음
    
    const currentEndMonth = document.getElementById(`po_${row}_2`);
    const nextStartMonth = document.getElementById(`po_${row+1}_1`);
    
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


function saveIPremiumData(certi) {
    // 저장할 데이터 수집
    const premiumData = [];
    
    for (let i = 1; i <= 7; i++) {
        // 빈 행 건너뛰기 (시작 월이나 종료 월이 없는 경우)
        const startMonth = document.getElementById(`po_${i}_1`).value.replace(/,/g, "");
        const endMonth = document.getElementById(`po_${i}_2`).value.replace(/,/g, "");
        
        if (!startMonth && !endMonth) {
            continue;
        }
        
        const rowData = {
            certi: certi,
            rowNum: i,
            start_month: startMonth,
            end_month: endMonth,

            payment10_premium1: document.getElementById(`po_${i}_6`).value.replace(/,/g, ""),
            payment10_premium2: document.getElementById(`po_${i}_7`).value.replace(/,/g, ""),
            payment10_premium_total: document.getElementById(`po_${i}_8`).value.replace(/,/g, "")
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
    fetch("https://kjstation.kr/api/kjDaeri/save_Ipremium_data.php", {
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
               // document.getElementById('kj-premium-modal').style.display = "none";
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