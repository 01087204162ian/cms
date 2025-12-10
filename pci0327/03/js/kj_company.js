/** kjstation.kr  대리운전회사 리스트 **/
let isFetchingCompany = false;  // fetch 중복 실행 방지 변수
let isFetchingDamdanga2 = false;  // fetch 중복 실행 방지 변수
let fetchCallCount5 = 0;  // API 호출 횟수 추적 변수
let currentPage3 = 1;  // 현재 페이지
let itemsPerPage3 = 15;  // 페이지당 표시할 항목 수
let companyData = null;  // 전체 데이터 저장 변수 (policyData2를 companyData로 변경)

function kj_company(){
	// 오늘 날짜 가져오기
	const today = new Date();
	const todayStr = today.getFullYear() + '-' + String(today.getMonth() + 1).padStart(2, '0') + '-' + String(today.getDate()).padStart(2, '0');
	
	const pageContent = document.getElementById('page-content');
	pageContent.innerHTML = "";
	
	const fieldContents= `<div class="kje-list-container">
		<!-- 검색 영역 -->
		<div class="kje-list-header">
			<div class="kje-left-area">
				<div class="kje-search-area">
					<div class="select-container">
						<select id="currentInwon" class="styled-select" onChange="requestDnumSearch()">
							<option value='1'>정상</option>
							<option value='2'>전체</option>
						 </select>
					</div>
		            <div class="select-container">
							<input type='text' id='dNumCompany' class='date-range-field' placeholder='대리운전회사명' 
								onkeypress="if(event.key === 'Enter') { requestDnumSearch(); return false; }" autocomplete="off" >
					</div>

					<div class="select-container">
						  <button class="sms-stats-button" onclick="requestDnumSearch()">검색</button>
					</div>
		            <div class="select-container">
						<select id="damdanga2" class="styled-select"></select>
					</div>
					<div class="select-container">
						<select id='duDay_'>
						</select>
						
					</div>
					
					
					
					
					<div id='currentSituation'></div>
				</div>
			</div>
			<div class="kje-right-area">
						
				<button class="kje-stats-button" onclick="kjDaeriCompany()">대리운전회사 신규</button> 
			</div>
		</div>
		<!-- 로딩 인디케이터 -->
		<div id="kjLoadingIndicator" class="kj-loading-indicator" style="display: none;">
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
							<th class="col-2">대리운전회사명</th>
							<th class="col-3">대표자</th>
							<th class="col-4">주민번호</th>
							<th class="col-5">핸드폰</th>
							<th class="col-6">연락처</th>
							<th class="col-7">담당자</th>
							<th class="col-8">정기납입일</th>
							<th class="col-9">인원</th>
							<th class="col-10">할인할증</th>
							<th class="col-11">등록일</th>
							<th class="col-12">해지일</th>
							<th class="col-13">사고</th>
							<th class="col-14">인증번호</th>
							
						</tr>
					</thead>
					<tbody id="dNumList_">
						<!-- 데이터가 여기에 동적으로 로드됨 -->
					</tbody>
				</table>
			</div>
		</div>
		<!-- 페이지네이션 -->
		<div class="policy-pagination" id="dNum-pagination"></div>
	</div>`;
	
	// 먼저 HTML 내용을 페이지에 삽입
	pageContent.innerHTML = fieldContents;
	
	// HTML이 삽입된 후 selectElement를 찾음
	const selectElement = document.getElementById('duDay_');
    
    // 오늘 날짜 구하기
    const currentDay = String(today.getDate()).padStart(2, '0');
    
    // 1부터 31까지 옵션 생성하기
    for (let i = 1; i <= 31; i++) {
        const day = String(i).padStart(2, '0');
        const option = document.createElement('option');
        option.value = day;
        option.textContent = day;
        
        // 오늘 날짜와 일치하는 옵션이면 selected 속성 추가
        if (day === currentDay) {
            option.selected = true;
        }
        
        selectElement.appendChild(option);
    }
	selectElement.addEventListener('change', function() {
			dNumCompanyTable(1,'', this.value,'');
		});
	// 페이지네이션 컨테이너가 제대로 생성되었는지 확인
	console.log("페이지네이션 컨테이너:", document.getElementById('dNum-pagination'));
	addCompanyCustomStyles();
	// 로딩 및 페이지네이션 기능을 위한 스타일 추가

	// 약간의 지연 후 한 번만 호출
		setTimeout(() => {
				// 상태 초기화
				isFetchingCompany = false;
				
				// 담당자 로드
				damdangaLoadSearchTable();
				
				// 현재 날짜를 기본값으로 사용
				const currentDay = String(today.getDate()).padStart(2, '0');
				
				// 잠시 대기 후 회사 데이터 로드 (담당자 로드 완료 후)
				setTimeout(() => {
					dNumCompanyTable(1, 'all', currentDay,''); // 페이지, 담당자, 납입일 명시적 전달
				}, 300);
		}, 100);
		
	
}

// 로딩 표시 함수
function showKjeLoading() {
	const loadingIndicator = document.getElementById('kjLoadingIndicator');
	if (loadingIndicator) {
		loadingIndicator.style.display = 'flex';
	}
}
// 로딩 표시 함수
function showKjeLoading2() {
	const loadingIndicator = document.getElementById('kjLoadingIndicator2');
	if (loadingIndicator) {
		loadingIndicator.style.display = 'flex';
	}
}
// 로딩 숨김 함수
function hideKjeLoading() {
	const loadingIndicator = document.getElementById('kjLoadingIndicator');
	if (loadingIndicator) {
		loadingIndicator.style.display = 'none';
	}
}
function hideKjeLoading2() {
	const loadingIndicator = document.getElementById('kjLoadingIndicator2');
	if (loadingIndicator) {
		loadingIndicator.style.display = 'none';
	}
}







// 스타일 추가 함수
function addCompanyCustomStyles() {
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
        
        .loading-spinner:before {
            content: '';
            width: 50px;
            height: 50px;
            border-radius: 50%;
            border: 6px solid #8E6C9D;
            border-top-color: transparent;
            animation: spin 1s linear infinite;
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
        
        /* 테이블 내 데이터 없음 스타일 */
        .no-data {
            text-align: center;
            padding: 20px;
            color: #666;
        }
    `;
    
    document.head.appendChild(style);
}




function damdangaLoadSearchTable(){

	fetchCallCount5++;
	console.log(`Fetch Call Count: ${fetchCallCount5}`);

	// 이미 실행 중이면 중복 호출 방지
	if (isFetchingDamdanga2) return;
	
	// 실행 상태로 변경
	isFetchingDamdanga2 = true;
	const sj='sj_'
	// API 요청
	fetch(`https://kjstation.kr/api/kjDaeri/damdangaList.php?sj=${sj}`)
    .then(response => {
      if (!response.ok) {
        throw new Error(`HTTP error! Status: ${response.status}`);
      }
      return response.json();
    })
    .then(data => {
      // 성공적으로 데이터를 받았을 때 드롭다운 채우기
      populateDamdangaList(data);
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
      if (typeof isFetchingDamdanga2 !== 'undefined') {
        isFetchingDamdanga2 = false;
      }
    });
}

function populateDamdangaList(data){

	console.log(data);
	const selectElement = document.getElementById('damdanga2');
  
  // 요소가 존재하는지 확인
  if (!selectElement) {
    console.error("Element with ID 'damdanga2' not found");
    return; // 요소가 없으면 함수 종료
  }
  
  // 기존 옵션 제거
  selectElement.innerHTML = '';
  
  // 기본 옵션 추가
  const defaultOption = document.createElement('option');
	defaultOption.value = 'all';
	defaultOption.textContent = '--  담당자 선택 --';
	selectElement.appendChild(defaultOption);



// 각 항목을 옵션으로 추가
	data.data.forEach(item => {
		
	  const option = document.createElement('option');
	  option.value = item.num;
	  option.textContent = `[${item.name}]`;
	  selectElement.appendChild(option);
	});


// change 이벤트 추가
		selectElement.addEventListener('change', function() {
		  const selectedValue = this.value;
		  console.log('선택된 담당자 :', selectedValue);
		  
		  // 선택된 값에 따라 다른 동작 수행
		  if (selectedValue === 'all') {
			console.log('담당자가  선택되지 않았습니다.');
			dNumCompanyTable(1,selectedValue,"",""); // 특정 증권 데이터 로드 함수 호출
		  }  else {
			console.log('특정 담당자가 선택되었습니다:', selectedValue);
			dNumCompanyTable(1,selectedValue,"",""); // 특정 증권 데이터 로드 함수 호출
		  }
		  
		});

	
}

// 함수 수정
function dNumCompanyTable(page = 1, damdangja = 'all', duDay = '',dNumCompany ='') {

	showKjeLoading();
	console.log('page',page,'damdangja',damdangja,'duDay',duDay);
    // 페이지가 전달되면 현재 페이지 업데이트
    if (page) {
        currentPage3 = page;
    }
    
    // 담당자 값이 없으면 select 요소에서 가져오기
    if (!damdangja || damdangja === '') {
        const selectElement = document.getElementById('damdanga2');
        if (selectElement) {
            damdangja = selectElement.value;
        } else {
            damdangja = 'all';  // 기본값
        }
    }
    
    // 정기납입일 값이 없으면 select 요소에서 가져오기
    if (!duDay || duDay === '') {
        const duDayElement = document.getElementById('duDay_');
        if (duDayElement) {
            duDay = duDayElement.value;
        }
    }
	//dNumCompany 

	if (!dNumCompany || dNumCompany === '') {
        const dNumCompanyElement = document.getElementById('dNumCompany');
        if (dNumCompanyElement) {
            dNumCompany = dNumCompanyElement.value;
        }
    }
    const currentInwonElement = document.getElementById('currentInwon').value;
    console.log(`조회 조건: 페이지=${page}, 담당자=${damdangja}, 정기납입일=${duDay},대리운전회사명=${dNumCompany}`);
    
    // 이미 실행 중이면 중복 호출 방지
    if (isFetchingCompany) return;
    
    // 로딩 표시
   
    
    // 실행 상태로 변경
   isFetchingCompany = true;
    fetchCallCount5++;
    console.log(`Fetch Call Count: ${fetchCallCount5}`);
				const url = new URL('https://kjstation.kr/api/kjDaeri/dNumList.php');
			url.searchParams.append('duDay_', duDay);
			url.searchParams.append('damdangja', damdangja);
			url.searchParams.append('currentInwonElement', currentInwonElement);
			url.searchParams.append('dNumCompany', dNumCompany);


    // API 요청
		fetch(url)
        .then(response => {
            if (!response.ok) {
                throw new Error(`HTTP error! Status: ${response.status}`);
            }
            return response.json();
        })
        .then(data => {
            // 성공적으로 데이터를 받았을 때 리스트 표시
            dNumList(data);
        })
        .catch((error) => {
            console.error("Error details:", error);
            alert("데이터를 불러오는 중 오류가 발생했습니다.");
        })
        .finally(() => {
            // 로딩 숨김
            hideKjeLoading();
            // 실행 완료 후 상태 변경
            isFetchingCompany = false;
        });
}


function dNumList(data) {
    console.log(data);
    
    // 전역 변수에 데이터 저장
    companyData = data;
    
    // 테이블 본문 요소 가져오기
    const tableBody = document.getElementById('dNumList_');
    
    // 데이터가 없는 경우 처리
    if (!data || !data.success || !data.data || data.data.length === 0) {
        tableBody.innerHTML = '<tr><td colspan="14" class="no-data">데이터가 없습니다.</td></tr>';
        // 페이지네이션 숨기기
        const paginationContainer = document.getElementById('dNum-pagination');
        if (paginationContainer) {
            paginationContainer.innerHTML = '';
        }
        return;
    }
    
    // 전체 데이터
    const allCompanies = data.data;
    
    // 전체 페이지 수 계산
    const totalPages = Math.ceil(allCompanies.length / itemsPerPage3);
    
    // 현재 페이지에 표시할 데이터 추출
    const startIndex = (currentPage3 - 1) * itemsPerPage3;
    const endIndex = Math.min(startIndex + itemsPerPage3, allCompanies.length);
    const currentPageData = allCompanies.slice(startIndex, endIndex);
    
    // 테이블 내용 초기화
    tableBody.innerHTML = '';
    
    // 현재 페이지 데이터로 테이블 행 생성
    currentPageData.forEach((company, index) => {
        const rowNumber = startIndex + index + 1;
        const row = document.createElement('tr');
        
        // 빈 값 처리 함수
        const emptyCheck = (value) => value ? value : '-';
        
        // 날짜 형식 처리 함수
        const formatDate = (dateStr) => {
            if (!dateStr) return '-';
            return dateStr;
        };
        
        // 행 내용 설정
        row.innerHTML = `
            <td>${rowNumber}</td>
            <td><span class="kje-btn-link_1" style="cursor:pointer;" onclick="kjDaeriCompany('${company.num || ''}')">${emptyCheck(company.company)}</span></td>
            <td>${emptyCheck(company.Pname)}</td>
            <td>${emptyCheck(company.jumin)}</td>
            <td>${emptyCheck(company.hphone)}</td>
            <td>${emptyCheck(company.cphone)}</td>
            <td>${emptyCheck(company.damdanga)}</td>
            <td>${emptyCheck(company.FirstStartDay)}</td>
            <td class="kje-preiminum"><span class="kje-btn-link_1" style="cursor:pointer;" onclick="kjMemberList('${company.num || ''}',1)">${emptyCheck(company.inwon)}</span></td>
            <td><span class="kje-btn-link_1" style="cursor:pointer;" onclick="adjustment('${company.num || ''}')">정산</span></td>
            <td>${formatDate(company.a11)}</td>
            <td>${formatDate(company.a12)}</td>
            <td>${company.a13 ? '있음' : '-'}</td>
            <td>${emptyCheck(company.cNumber)}</td>
        `;
        
        tableBody.appendChild(row);
    });
    
    // 현재 상황 정보 표시
    const currentSituation = document.getElementById('currentSituation');
    if (currentSituation) {
        currentSituation.innerHTML = `<div class="situation-info">총 ${allCompanies.length}개의 대리운전회사가 있습니다.</div>`;
    }
    
    // 페이지네이션 생성
    createDNumPagination(data);
}

// 페이지네이션 생성 함수
function createDNumPagination(data) {
    // 페이지네이션 컨테이너 찾기
    const paginationContainer = document.getElementById('dNum-pagination');
    if (!paginationContainer) {
        console.error('페이지네이션 컨테이너를 찾을 수 없습니다.');
        return;
    }

    // 이전 페이지네이션 내용 삭제
    paginationContainer.innerHTML = '';

    // 데이터가 없거나 유효하지 않은 경우 처리
    if (!data || !data.success || !data.data || data.data.length === 0) {
        return;
    }

    const allCompanies = data.data;
    
    // 전체 페이지 수 계산
    const totalPages = Math.ceil(allCompanies.length / itemsPerPage3);

    // 한 페이지만 있는 경우 페이지네이션 표시하지 않음
    if (totalPages <= 1) {
        return;
    }

    // 이전 버튼
    const prevButton = document.createElement('a');
    prevButton.href = 'javascript:void(0);';
    prevButton.className = 'pagination-btn prev-btn' + (currentPage3 === 1 ? ' disabled' : '');
    prevButton.innerHTML = '&laquo;';
    if (currentPage3 > 1) {
        prevButton.onclick = function() {
            goToDNumPage(currentPage3 - 1);
        };
    }
    paginationContainer.appendChild(prevButton);

    // 페이지 번호 버튼들
    const maxPageButtons = 5;
    let startPage = Math.max(1, currentPage3 - Math.floor(maxPageButtons / 2));
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
            goToDNumPage(1);
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
        pageBtn.className = 'pagination-btn' + (i === currentPage3 ? ' active' : '');
        pageBtn.textContent = i;
        
        if (i !== currentPage3) {
            pageBtn.onclick = function() {
                goToDNumPage(i);
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
            goToDNumPage(totalPages);
        };
        paginationContainer.appendChild(lastPageBtn);
    }

    // 다음 버튼
    const nextButton = document.createElement('a');
    nextButton.href = 'javascript:void(0);';
    nextButton.className = 'pagination-btn next-btn' + (currentPage3 === totalPages ? ' disabled' : '');
    nextButton.innerHTML = '&raquo;';
    if (currentPage3 < totalPages) {
        nextButton.onclick = function() {
            goToDNumPage(currentPage3 + 1);
        };
    }
    paginationContainer.appendChild(nextButton);
}

// 페이지 이동 함수
function goToDNumPage(page) {
    if (page < 1 || !companyData || !companyData.data) return;
    
    const totalPages = Math.ceil(companyData.data.length / itemsPerPage3);
    if (page > totalPages) return;
    
    currentPage3 = page;
    dNumList(companyData);
    
    // 스크롤을 테이블 상단으로 이동
    const tableContainer = document.querySelector('.kje-data-table-container');
    if (tableContainer) {
        tableContainer.scrollIntoView({ behavior: 'smooth' });
    }
}

// 검색 기능 추가
function requestDnumSearch() {
    const selectElement = document.getElementById('damdanga2');
    const damdangja = selectElement.value;
    const duDay_ = document.getElementById('duDay_').value;
    const dNumCompany = document.getElementById('dNumCompany').value;
    // 페이지 초기화
    currentPage3 = 1;
    
    // 회사 데이터 로드
    dNumCompanyTable(1, damdangja, duDay_,dNumCompany);
}

let isFetchingDaeriGisa = false;  // fetch 중복 실행 방지 변수
let fetchCallCount14 = 0;  // API 호출 횟수 추적 변수
let currentPage12 = 1;  // 현재 페이지
let itemsPerPage12 = 15;  // 페이지당 표시할 항목 수
let deriGisaData = null;  // 전체 데이터 저장 변수 (policyData2를 deriGisaData로 변경)
// 대리기사 명단 조회 및 페이지네이션 기능 구현
function kjMemberList(dNum,sort) {
	//alert('sort'+ sort)
	const today = new Date();
	const todayStr = today.getFullYear() + '-' + String(today.getMonth() + 1).padStart(2, '0') + '-' + String(today.getDate()).padStart(2, '0');
	const m_gisaList = document.getElementById('m_gisaList');
	m_gisaList.innerHTML = "";
	const fieldContents= `<div class="kje-list-container">
		<!-- 검색 영역 -->
		<div class="kje-list-header">
			<div class="kje-left-area">
				<div class="kje-search-area">
					<div class="select-container">
					  <div id='gisaCerti'></div>
					</div>
					<div id='gisaCurrentSituation'></div>
				</div>
			</div>
			<div class="kje-right-area">
		        <div class="kje-search-area">
					    <div class="select-container">
							<input type='date' id='gijunDay2' class='date-range-field' value='${todayStr}'>
							<input type='text' id='gisa_name2' class='date-range-field' placeholder='성명' 
								onkeypress="if(event.key === 'Enter') { gisaSearch2(); return false; }" autocomplete="off" >
						</div>
						<div class="select-container">
							<select id="gisaPushSangtae2" class="styled-select" >
								<option value='1'>정상</option>
								<option value='2'>전체</option>
							 </select>
						</div>
						<div class="select-container">
							<button class="sms-stats-button" onclick="gisaSearch2()">검색</button>
						</div>
				  </div>
		       </div>
		</div>

		<!-- 로딩 인디케이터 -->
		<div id="kjLoadingIndicator2" class="kj-loading-indicator" style="display: none;">
			<div class="loading-spinner"></div>
			<div class="loading-text">데이터를 불러오는 중...</div>
		</div>

		<!-- 리스트 영역 -->
		<div class="kje-list-content">
			<div class="kje-data-table-container">
				<table class="kje-data-table">
					<thead>
						<tr>
							<th width='3%'>No</th>
							<th width='7%'>성명</th>
							<th width='3%'>나이</th>
							<th width='10%'>주민번호</th>
							<th width='10%'>핸드폰번호</th>
							<th width='5%'>상태</th>
							<th width='8%'>증권성격</th>
							<th width='10%'>대리운전회사</th>
							<th width='5%'>보험회사</th>
							<th width='10%'>증권번호</th>
							<th width='5%'>월보험료</th>
							<th width='5%'>환산보험료</th>
							<th width='5%'>1/10</th>
							<th width='15%'>할인할증</th>
						</tr>
					</thead>
					<tbody id="daeriGisaList">
						<!-- 데이터가 여기에 동적으로 로드됨 -->
					</tbody>
				</table>
			</div>
		</div>

		<!-- 페이지네이션 -->
		<div class="policy-pagination" id="daeriGisa-pagination"></div>
	</div>`
	
	m_gisaList.innerHTML = fieldContents;
    // 모달 표시
    document.getElementById("gisaList-modal").style.display = "block";
    
    
    if(sort==1){ // 대리운전회사 화면에서 만 증권리스트가 
    //증권목록 
		giSaCertiRoda(dNum);
	}
    
    // 서버에서 데이터 가져오기
    gisaList(dNum,'',sort);
}
let isFetchingGisaList = false;  // fetch 중복 실행 방지 변수
let fetchCallCount25 = 0;  // API 호출 횟수 추적 변수



function  giSaCertiRoda(dNum){

	fetchCallCount25++;
	console.log(`Fetch Call Count: ${fetchCallCount25}`);

	// 이미 실행 중이면 중복 호출 방지
	if (isFetchingGisaList) return;
	
	// 실행 상태로 변경
	isFetchingGisaList = true;

	// API 요청
	fetch(`https://kjstation.kr/api/kjDaeri/giSaCertiList.php?dNum=${dNum}`)
    .then(response => {
      if (!response.ok) {
        throw new Error(`HTTP error! Status: ${response.status}`);
      }
      return response.json();
    })
    .then(data => {
      // 성공적으로 데이터를 받았을 때 드롭다운 채우기
      populateGisaCeritList(data,dNum);
    })
    .catch((error) => {
      console.error("Error details:", error);
      alert("데이터를 불러오는 중 오류가 발생했습니다.");
    })
    .finally(() => {
      // 중앙 로딩 숨김 (필요한 경우)
      
      // 실행 완료 후 상태 변경 (필요한 경우)
      if (typeof isFetching !== 'undefined') {
        isFetchingGisaList = false;
      }
    });
}
function gisaSearch2() {
	// 여기에 fetch 코드 넣기
	fetchCallCount14++;
	console.log(`Fetch Call Count: ${fetchCallCount14}`);

	// 이미 실행 중이면 중복 호출 방지
	if (isFetchingDaeriGisa) return; // isFetchingPolicy에서 isFetchingDaeriGisa로 변경
	
	// 실행 상태로 변경
	isFetchingDaeriGisa = true; // isFetchingPolicy에서 isFetchingDaeriGisa로 변경
	const sj = 'gisa_';

	// 로딩 표시
	showKjeLoading2();

	const gijunDay = document.getElementById("gijunDay2").value;
	const gisa_name = document.getElementById("gisa_name2").value;
	const gisaPushSangtae = document.getElementById("gisaPushSangtae2").value;
	
	// API 요청
	fetch(`https://kjstation.kr/api/kjDaeri/gisaSearch.php?sj=${sj}&gijunDay=${gijunDay}&gisa_name=${gisa_name}&gisaPushSangtae=${gisaPushSangtae}`)
		.then(response => {
			if (!response.ok) {
				throw new Error(`HTTP error! Status: ${response.status}`);
			}
			return response.json();
		})
		.then(data => {
			// 전체 데이터 저장
			deriGisaData = data;
            console.log("Fetched data:", data); // 데이터 확인 로그 추가
			
			// 페이지 초기화 및 데이터 표시
			currentPage12 = 1; // currentPage에서 currentPage12로 변경
			renderDaeriGisaTable(data);
			
			// 직접 DOM에 페이지네이션 생성
			createDaeriGisaPagination(data);
		})
		.catch((error) => {
			console.error("Error details:", error);
			alert("데이터를 불러오는 중 오류가 발생했습니다.");
		})
		.finally(() => {
			// 로딩 숨김
			hideKjeLoading2();
			
			// 실행 완료 후 상태 변경
			isFetchingDaeriGisa = false; // isFetchingPolicy에서 isFetchingDaeriGisa로 변경
		});
}
function populateGisaCeritList(data, dNum) {
  console.log(data);
  const divElement = document.getElementById('gisaCerti');
  
  // 요소가 존재하는지 확인
  if (!divElement) {
    console.error("Element with ID 'gisaCerti' not found");
    return; // 요소가 없으면 함수 종료
  }
  
  // 기존 내용 제거
  divElement.innerHTML = '';
  
  // select 요소 생성
  const selectElement = document.createElement('select');
  selectElement.className = 'styled-select';
  selectElement.id = 'gisaCertiSelect';
  
  // 기본 옵션 추가
  const defaultOption = document.createElement('option');
  defaultOption.value = 'all';
  defaultOption.text = '증권 선택';
  selectElement.appendChild(defaultOption);
  
  // 각 항목을 옵션으로 추가
  data.data.forEach(item => {
    const option = document.createElement('option');
    option.value = item.dongbuCerti;
    
    // InsuranceCompany 정보가 없어도 오류 없이 작동하도록 수정
    let insuranceCompany = "";
    if (item.InsuranceCompany) {
      const icompanyType = { "1": "흥국", "2": "DB", "3": "KB", "4": "현대", "5": "현대", "6": "롯데", "7": "MG", "8": "삼성" };
      insuranceCompany = icompanyType[item.InsuranceCompany] || "";
    }
    
    // 안전한 문자열 처리
    let displayText = String(item.dongbuCerti || "");
    if (insuranceCompany) {
      displayText += "[" + insuranceCompany + "]";
    }
    displayText += "[" + (item.count || 0) + "명]";
    option.text = displayText;
    
    selectElement.appendChild(option);
  });
  
  // div에 select 요소 추가
  divElement.appendChild(selectElement);
  
  // 보험회사별 현황을 gisaCurrentSituation에 표시
  displayCompanyData(data.companyData);
  
  // change 이벤트 추가
  selectElement.addEventListener('change', function() {
    const selectedValue = this.value;
    console.log('선택된 담당자 :', selectedValue);
    
    // 선택된 값에 따라 다른 동작 수행
    if (selectedValue === 'all') {
      console.log('담당자가 선택되지 않았습니다.');
      gisaList(dNum, selectedValue, ''); // 특정 증권 데이터 로드 함수 호출
    } else {
      console.log('특정 담당자가 선택되었습니다:', selectedValue);
      gisaList(dNum, selectedValue, ''); // 특정 증권 데이터 로드 함수 호출
    }
  });
}
// 보험회사별 현황을 표시하는 새 함수
function displayCompanyData(companyData) {
  const situationElement = document.getElementById('gisaCurrentSituation');
  
  // 요소가 존재하는지 확인
  if (!situationElement) {
    console.error("Element with ID 'gisaCurrentSituation' not found");
    return; // 요소가 없으면 함수 종료
  }
  
  // HTML 생성
		let html = '<div class="company-situation">';
		
		html += '<div class="company-list" style="display: flex; flex-wrap: wrap; gap: 10px;">'; // flex 사용하여 수평 배치

		// 보험회사 이름 매핑
		const icompanyType = { "1": "흥국", "2": "DB", "3": "KB", "4": "현대", "5": "현대", "6": "롯데", "7": "MG", "8": "삼성" };

		// 각 회사별 데이터 추가
		companyData.forEach(item => {
		  const company = icompanyType[item.InsuranceCompany] || `회사코드 ${item.InsuranceCompany}`;
		  html += `<div class="company-item" style="border: 1px solid #ddd; padding: 3px 12px; border-radius: 4px;">
			${company}: ${item.count}명
		  </div>`;
		});

		html += '</div></div>'; // div 닫기
  
  // HTML 삽입
  situationElement.innerHTML = html;
}





function gisaList(dNum, dongbuCerti,sort) {

    
    
     // sort 값 저장 (페이지 이동 시 유지하기 위해)
    window.currentSort = sort;
    
    // dongbuCerti 값 저장
    if (dongbuCerti && deriGisaData) {
        deriGisaData.currentDongbuCerti = dongbuCerti;
    }
    
    // 나머지 기존 코드 유지
    document.getElementById("gisaList_daeriCompany").textContent = '';
    
    // 이미 실행 중이면 중복 호출 방지
    if (isFetchingDaeriGisa) return;
    
    // 실행 상태로 변경
    isFetchingDaeriGisa = true;
    
    // 로딩 표시
    showKjeLoading2();
    
    // 페이지 초기화 (리스트 새로 불러올 때)
    if (window.initialLoad !== false) {
        currentPage12 = 1;
        window.initialLoad = false;
    }
    
    // API 요청 URL 구성 (페이지네이션 포함)
    const url = new URL(`https://kjstation.kr/api/kjDaeri/gisaList.php`);
    url.searchParams.append('dNum', dNum);
    url.searchParams.append('sort', sort); //sort 1이면 대리운전 회사 dNum, sort2 이면 주민번호로 조회한다
    
    // 증권번호 값이 있으면 파라미터로 추가
    if (dongbuCerti) {
        url.searchParams.append('dongbuCerti', dongbuCerti);
        
        // 증권번호로 조회 중인 경우 데이터에 증권번호 정보 저장
        if (deriGisaData) {
            deriGisaData.currentDongbuCerti = dongbuCerti;
        }
    }
    
    // 페이지 번호 유효성 검사 (데이터가 있는 경우)
    if (deriGisaData && deriGisaData.totalCount && deriGisaData.totalPages) {
        // 현재 페이지가 총 페이지 수보다 크면 마지막 페이지로 조정
        if (currentPage12 > deriGisaData.totalPages) {
            currentPage12 = deriGisaData.totalPages;
        }
    }
    
    url.searchParams.append('page', currentPage12);
    url.searchParams.append('itemsPerPage', itemsPerPage12);
    
    console.log(`요청 URL: ${url.toString()}`);
    
    // 요청 timeout 설정 (10초)
    const controller = new AbortController();
    const timeoutId = setTimeout(() => controller.abort(), 30000);
    
    // API 요청
    fetch(url, { signal: controller.signal })
        .then(response => {
            clearTimeout(timeoutId);
            if (!response.ok) {
                throw new Error(`HTTP error! Status: ${response.status}`);
            }
            return response.json();
        })
        .then(data => {
            // 전체 데이터 저장
            deriGisaData = data;
            console.log("Fetched data:", data);
            
            // 총 페이지 수 저장
            totalGisaPages = data.totalPages || Math.ceil((data.data?.length || 0) / itemsPerPage12);
            
            // 데이터 없는 경우 처리
            if (data.data && data.data.length === 0 && data.totalCount > 0 && currentPage12 > 1) {
                console.warn(`페이지 ${currentPage12}에 데이터가 없습니다. 1페이지로 이동합니다.`);
                currentPage12 = 1;
                // 재귀 호출하지만 무한 루프 방지
                setTimeout(() => gisaList(dNum, dongbuCerti), 0);
                return;
            }
            
            // 테이블 렌더링
            renderDaeriGisaTable(data);
            
            // 페이지네이션 생성
            createDaeriGisaPagination(data);
        })
        .catch((error) => {
            console.error("Error details:", error);
            if (error.name === 'AbortError') {
                alert("요청 시간이 초과되었습니다. 네트워크 연결을 확인하거나 나중에 다시 시도해주세요.");
            } else {
                alert("데이터를 불러오는 중 오류가 발생했습니다.");
            }
        })
        .finally(() => {
            // 로딩 숨김
            hideKjeLoading2();
            
            // 실행 완료 후 상태 변경
            isFetchingDaeriGisa = false;
        });
}

// 테이블에 기사 데이터를 렌더링하는 함수
function renderDaeriGisaTable(data) {
    // 실제 데이터가 있는지 확인
    if (!data || !data.data || data.data.length === 0) {
        const daeriGisaList = document.getElementById('daeriGisaList');
        daeriGisaList.innerHTML = '<tr><td colspan="16" class="no-data">검색 결과가 없습니다.</td></tr>';
        
        // 페이지네이션 영역 비우기
        document.getElementById('daeriGisa-pagination').innerHTML = '';
        return;
    }
    
    // 실제 데이터 배열
    const deriGisaData = data.data;
    
    // 회사명 표시 (첫 번째 데이터의 회사명을 사용)
    if (deriGisaData.length > 0 && deriGisaData[0].company) {
        document.getElementById("gisaList_daeriCompany").textContent = deriGisaData[0].company;
    }
    
    // 페이지 데이터 - 서버에서 이미 페이지네이션이 처리됨
    const currentPageData = deriGisaData;
    
    // 테이블 본문 선택
    const daeriGisaList = document.getElementById('daeriGisaList');
    
    // 데이터 표시를 위한 HTML 생성
    let tableHTML = '';
    
    // 현재 페이지 데이터 순회하며 행 생성
    for (let i = 0; i < currentPageData.length; i++) {
        const item = currentPageData[i];
        // 서버에서 페이지네이션된 경우 시작 인덱스를 계산
        const startIndex = (currentPage12 - 1) * itemsPerPage12;
        const rowNumber = startIndex + i + 1; // 일련번호 계산
        
        // 상태 표시 설정
        let pushOptions = ""; 
        //const pushType={"1":"청약","2":"해지","3":"청약거절","4":"정상","5":"해지취소","6":"청약취소"}

        if (item.push == 4) { 
            if(item.cancel==='42'){
                pushOptions=`해지중`;
            
            }else{
                pushOptions = `
                    <select class="kje-status-ch" data-id="${item.num}" id='pushl-${item.num}' 
                            onchange="updateHaeji(this, ${item.num}, ${item['2012DaeriCompanyNum']}, '${item.CertiTableNum}', '${item.dongbuCerti}', '${item.InsuranceCompany}')">                           
                        <option value="4" ${item.push == 4 ? "selected" : ""}>정상</option> 
                        <option value="2" ${item.push == 2 ? "selected" : ""}>해지</option> 
                    </select>
                `;	
            }

        }else if(item.push==2){
             pushOptions='해지';
         }else if(item.push==3){
             pushOptions='해지';
        }else if(item.push==1){
             if (item.cancel === '12') {
                pushOptions = '해지'+item.cancel ;
            } else if (item.cancel === '13') {
                pushOptions = '청약거절'+item.cancel ;
            } else {
                pushOptions = '청약';
            }
        }
    
        let certiType='';
        // 증권의 성격 매핑
        const iType = { "1": "대리", "2": "탁송", "3": "대리렌트", "4": "탁송렌트" };
        if (item.push == 4) { 
            certiType = `
                    <select class="kje-status-ch" data-id="${item.num}" id='pushQ-${item.num}' 
                            onchange="updateSmsContent(this, '${item.Name}', ${item.num})">                           
                        <option value="1" ${item.etag == 1 ? "selected" : ""}>대리</option> 
                        <option value="2" ${item.etag == 2 ? "selected" : ""}>탁송</option> 
                        <option value="3" ${item.etag == 3 ? "selected" : ""}>대리렌트</option> 
                        <option value="4" ${item.etag == 4 ? "selected" : ""}>탁송렌트</option> 
                        <option value="5" ${item.etag == 5 ? "selected" : ""}>확대탁송</option> 
                    </select>
                `;	
        } else {
             certiType = iType[item.etag] || "알 수 없음";
        }
                
        const icompanyType = { "1": "흥국", "2": "DB", "3": "KB", "4": "현대", "5": "현대", "6": "롯데", "7": "MG", "8": "삼성" };
        const insuranceCompany = icompanyType[item.InsuranceCompany];
        
        // 보험회사 표시 설정
        let personRateName = '';
        let personRate2 = 0;
        
        // 문자열을 숫자로 변환
        const rateValue = parseInt(item.rate, 10);
        const resultRate = calculatePersonRate(rateValue);
		const formattedMothlyPremium = item.AdjustedInsuranceMothlyPremium ? parseFloat(item.AdjustedInsuranceMothlyPremium).toLocaleString("en-US") : "0";
		const formattedCompanyPremium = item.AdjustedInsuranceCompanyPremium ? parseFloat(item.AdjustedInsuranceCompanyPremium).toLocaleString("en-US") : "0";
		const formattedConversionPremium = item.ConversionPremium ? parseFloat(item.ConversionPremium).toLocaleString("en-US") : "0";
		
        tableHTML += `
        <tr>
            <td>${rowNumber}</td>
            <td><input type='text' id='Name-${item.num}' value="${item.Name}" class='geInput2' autocomplete="off"
                onkeypress="if(event.key === 'Enter') { validateGisaUpdateName(this, ${item.num}); return false; }"</td>
			<td>${item.nai}</td>
            <td>${item.Jumin || ''}</td>
            <td><input type='text' id='Hphone-${item.num}' value="${item.Hphone}" class='geInput2' oninput="formatPhoneNumber(this)" autocomplete="off"
                onkeypress="if(event.key === 'Enter') { validateGisaUpdatePhone(this, ${item.num}); return false; }"</td>
            <td>${pushOptions|| item.push}</td>
            <td>${certiType}</td>
            <td>${item.company || ''}</td>
            <td>${insuranceCompany}</td>
            <td>${item.dongbuCerti || ''}</td>
			<td class="kje-preiminum">${formattedMothlyPremium || ''}</td>
			<td class="kje-preiminum">${formattedConversionPremium || ''}</td>
			<td class="kje-preiminum">${formattedCompanyPremium || ''}</td>

            <td>${resultRate.personRate2 || ''} : ${resultRate.personRateName || ''}</td>
        </tr>`;
    }
    
    // 테이블에 HTML 적용
    daeriGisaList.innerHTML = tableHTML;
    
    // 전체 검색 결과 개수 표시
    const currentSituation = document.getElementById('gisaCurrentSituation');
    if (currentSituation) {
        // 서버에서 받은 총 레코드 수 표시
        const totalCount = data.totalCount || deriGisaData.length;
        currentSituation.innerHTML = `<span>전체 ${totalCount}건</span>`;
    }
}

// 페이지네이션을 생성하는 함수
function createDaeriGisaPagination(data) {
   const paginationContainer = document.getElementById('daeriGisa-pagination');
    if (!paginationContainer) return;
    
    paginationContainer.innerHTML = '';
    
    // 데이터 유효성 확인을 간소화
    if (!data?.success || !data?.data || 
        (data.data.length === 0 && (!data.totalCount || parseInt(data.totalCount) === 0))) {
        return;
    }
    
    // 페이지 계산 로직 간소화
    const totalPages = data.totalPages ? parseInt(data.totalPages) : 
                       data.totalCount ? Math.ceil(parseInt(data.totalCount) / itemsPerPage12) : 1;
    
    if (totalPages <= 1) return;

    // 한 페이지만 있는 경우 페이지네이션 표시하지 않음
    if (totalPages <= 1) {
        return;
    }

    // 현재 페이지가 유효한지 확인
    if (currentPage12 > totalPages) {
        currentPage12 = totalPages;
    }

    // 이전 버튼
    const prevButton = document.createElement('a');
    prevButton.href = 'javascript:void(0);';
    prevButton.className = 'pagination-btn prev-btn' + (currentPage12 === 1 ? ' disabled' : '');
    prevButton.innerHTML = '&laquo;';
    if (currentPage12 > 1) {
        prevButton.onclick = function() {
            goToDaeriGisaPage(currentPage12 - 1);
        };
    }
    paginationContainer.appendChild(prevButton);

    // 페이지 번호 버튼들
    const maxPageButtons = 5;
    let startPage = Math.max(1, currentPage12 - Math.floor(maxPageButtons / 2));
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
            goToDaeriGisaPage(1);
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
        pageBtn.className = 'pagination-btn' + (i === currentPage12 ? ' active' : '');
        pageBtn.textContent = i;
        
        if (i !== currentPage12) {
            pageBtn.onclick = function() {
                goToDaeriGisaPage(i);
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
            goToDaeriGisaPage(totalPages);
        };
        paginationContainer.appendChild(lastPageBtn);
    }

    // 다음 버튼
    const nextButton = document.createElement('a');
    nextButton.href = 'javascript:void(0);';
    nextButton.className = 'pagination-btn next-btn' + (currentPage12 === totalPages ? ' disabled' : '');
    nextButton.innerHTML = '&raquo;';
    if (currentPage12 < totalPages) {
        nextButton.onclick = function() {
            goToDaeriGisaPage(currentPage12 + 1);
        };
    }
    paginationContainer.appendChild(nextButton);
}

// 페이지 이동 함수
/**
 * 대리기사 목록의 특정 페이지로 이동하는 함수
 * @param {number} page - 이동할 페이지 번호
 */
// 페이지 이동 함수
function goToDaeriGisaPage(page) {
    if (page < 1) return;
    
    // 페이지 번호 업데이트
    currentPage12 = page;
    
    // 서버에서 페이지네이션을 사용하는 경우
    if (deriGisaData && deriGisaData.totalPages) {
        // 현재 표시된 dNum과 dongbuCerti 가져오기
        let dNum = null;
        let dongbuCerti = null;
        let sort = '1'; // 기본값
        
        // gisaCertiSelect 요소에서 증권번호 가져오기
        const certiSelect = document.querySelector('#gisaCertiSelect');
        if (certiSelect && certiSelect.value && certiSelect.value !== 'all') {
            dongbuCerti = certiSelect.value;
        }
        
        // gisaDaeriNumSelect 요소 찾기 시도
        const daeriNumSelect = document.querySelector('#gisaDaeriNumSelect');
        if (daeriNumSelect) {
            dNum = daeriNumSelect.value;
        }
        
        // 다른 가능한 선택자 시도
        if (!dNum) {
            const daeriNumElement = document.querySelector('[name="daeriNum"]') || 
                                    document.querySelector('[id*="daeriNum"]') ||
                                    document.querySelector('[data-company-id]');
            if (daeriNumElement) {
                dNum = daeriNumElement.value || daeriNumElement.getAttribute('data-company-id');
            }
        }
        
        // 최후의 수단으로 현재 선택된 회사 번호 추출 시도
        if (!dNum && deriGisaData.data && deriGisaData.data.length > 0) {
            dNum = deriGisaData.data[0]['2012DaeriCompanyNum'];
        }
        
        // 증권번호가 없는 경우 현재 데이터에서 확인
        if (!dongbuCerti && deriGisaData.currentDongbuCerti) {
            dongbuCerti = deriGisaData.currentDongbuCerti;
        }
        
        // 현재 sort 값 가져오기
        if (window.currentSort) {
            sort = window.currentSort;
        }
        
        if (!dNum) {
            console.warn('대리운전 회사 번호를 찾을 수 없습니다. 기본 데이터를 사용합니다.');
            // 기본 페이지 렌더링 시도
            if (deriGisaData) {
                renderDaeriGisaTable(deriGisaData);
                createDaeriGisaPagination(deriGisaData);
            }
            return;
        }
        
        // 해당 페이지의 데이터 다시 요청 (sort 파라미터 추가)
        gisaList(dNum, dongbuCerti, sort);
    } else {
        // 클라이언트 측 페이지네이션을 사용하는 경우
        if (deriGisaData) {
            renderDaeriGisaTable(deriGisaData);
            createDaeriGisaPagination(deriGisaData);
        }
    }
    
    // 스크롤을 테이블 상단으로 이동
    const tableContainer = document.querySelector('.kje-data-table-container');
    if (tableContainer) {
        tableContainer.scrollIntoView({ behavior: 'smooth' });
    }
}

//정산 

//대리운전회사 정산//
function adjustment(dNum) {
    const m_adjustment = document.getElementById('m_adjustment');
    m_adjustment.innerHTML = "";
    const fieldContents = `<div class="kje-list-container">
        <!-- 검색 영역 -->
        <div class="kje-list-header">
            <div class="kje-left-area">
                <div class="kje-search-area">
                    <div class="select-container">
                        <input type='date' id='lastMonthDueDate' class='date-range-field'>
                    </div>
                    <div class="select-container">
                        
                        <input type='date' id='thisMonthDueDate' class='date-range-field'>
                    </div>
                    <div class="select-container">
                        <button class="sms-stats-button" onclick="MonthlyEndorseSearch('${dNum}')">배서리스트 조회</button>
                    </div>
                </div>
            </div>
            <div class="kje-right-area">
                <button class="sms-stats-button" onclick="premiumExcel('${dNum}')">엑셀 다운로드</button>
            </div>
        </div>
    
        <!-- 리스트 영역 -->
        <div class="kje-list-content">
            <table class="sjTable">
                <thead>
                    <tr>
                        <th width='15%'>증권번호</th>
                        <th width='10%'>납부방식(divi)</th>
						<th width='8%'>인원</th>
                        <th width='13%'>월보험료</th>
                        <th width='15%'>보험회사보험료</th>
                        <th width='13%'>배서보험료</th>
                        <th width='13%'>계</th>
                        <th width='13%'>환산보험료</th>
                    </tr>
                </thead>
                <tbody id="premiumStatistics">
                    <!-- 데이터가 여기에 동적으로 로드됨 -->
                </tbody>
            </table>
        </div>
		<div class="select-container">
            <input type='text' id='memo-${dNum}'  class='memoInput' placeholder='메모 입력하고 엔터'  
				onkeypress="if(event.key === 'Enter') { adjustMentMemo(this, ${dNum}); return false; }" autocomplete="off">
        </div>
		<div class="select-container">
            <div id='memoList'></div>
        </div>
		
    </div>`;
    
    m_adjustment.innerHTML = fieldContents;
    
    // 현재 날짜 기준으로 기본 날짜 설정 (한 달 범위)
    const today = new Date();
    const lastMonth = new Date(today);
    lastMonth.setMonth(today.getMonth() - 1);
    
    // 날짜 형식 변환 함수
    function formatDate(date) {
        const year = date.getFullYear();
        const month = String(date.getMonth() + 1).padStart(2, '0');
        const day = String(date.getDate()).padStart(2, '0');
        return `${year}-${month}-${day}`;
    }
    
    // 기본 날짜 설정
    document.getElementById('lastMonthDueDate').value = formatDate(lastMonth);
    document.getElementById('thisMonthDueDate').value = formatDate(today);
    
    // 모달 표시
    document.getElementById("adjustment-modal").style.display = "block";
    endorseDay(dNum); // 배서기간 조회
}
//정산 관련 메모
function adjustMentMemo(memo,dNum){
		console.log(memo, dNum);
    const memoValue = document.getElementById(`memo-${dNum}`).value;
    // 여기서 memoValue로 무언가를 수행
    // 예: 데이터베이스에 저장, 화면에 표시 등
    console.log('memoValue', memoValue); // 또는 필요한 반환 값
}
let fetchCallCount26 = 0;
let isFetchingAdjustment = false;

/* 정기납입일 찾어서 현재월보다 1개월 전 */
function endorseDay(dNum) {
  fetchCallCount26++;
  console.log(`Fetch Call Count: ${fetchCallCount26}`);
  
  // 이미 실행 중이면 중복 호출 방지
  if (isFetchingAdjustment) return;
  
  // 실행 상태로 변경
  isFetchingAdjustment = true;
  
  // API 요청
  fetch(`https://kjstation.kr/api/kjDaeri/endorseDaySearch.php?dNum=${dNum}`)
    .then(response => {
      if (!response.ok) {
        throw new Error(`HTTP error! Status: ${response.status}`);
      }
      return response.json();
    })
    .then(data => {
      // 서버로부터 받은 데이터 처리
      if (data && data.success) {
        // lastMonthDueDate 설정
        if (data.lastMonthDueDate) {
          document.getElementById('lastMonthDueDate').value = data.lastMonthDueDate;
        }
        
        // thisMonthDueDate 설정
        if (data.thisMonthDueDate) {
          document.getElementById('thisMonthDueDate').value = data.thisMonthDueDate;
        }
        document.getElementById('adjustment_daeriCompany').innerHTML=data.company;
        console.log("날짜 데이터 설정 완료:", {
          "1개월 전 정기결제일": data.lastMonthDueDate,
          "이번 달 정기결제일": data.thisMonthDueDate,
		  "대리운전회사":data.company
        });

		  premiumSearch(dNum,data.lastMonthDueDate,data.thisMonthDueDate);// 보험료 조회 및 배서 보험료 조회 
		  memoPremiumSearch(data.cNumber||data.jumin);
      } else {
        console.error("API 응답에 필요한 데이터가 없습니다:", data);
      }
    })
    .catch((error) => {
      console.error("Error details:", error);
      alert("데이터를 불러오는 중 오류가 발생했습니다.");
    })
    .finally(() => {
      // 중앙 로딩 숨김 (필요한 경우)
      
      // 실행 완료 후 상태 변경
      isFetchingAdjustment = false;
    });
}

let fetchCallCount27 = 0;
let isFetchingSearch = false;
function MonthlyEndorseSearch(dNum) {
    const lastMonthDueDate = document.getElementById('lastMonthDueDate').value;
    const thisMonthDueDate = document.getElementById('thisMonthDueDate').value;
    console.log('dNum', dNum, 'lastMonthDueDate', lastMonthDueDate, 'thisMonthDueDate', thisMonthDueDate);
    document.getElementById('adjustmentMothly_daeriCompany').innerHTML
        = document.getElementById('adjustment_daeriCompany').innerHTML
        + lastMonthDueDate + "~" + thisMonthDueDate + "현황"; 
    
    // 이전에 생성된 로더가 있다면 제거
    if (LoadingSystem.instances['myLoader']) {
        LoadingSystem.remove('myLoader');
    }
    
    // 새 로더 생성
    const myLoader = LoadingSystem.create('myLoader', {
        text: '데이터를 불러오는 중입니다...',
        autoShow: true
    });
    
    fetchCallCount27++;
    console.log(`Fetch Call Count: ${fetchCallCount27}`);
    
    // 테이블 본문 선택
    const m_adjustmentMothly = document.getElementById('m_adjustmentMothly');
    
    // 이미 실행 중이면 중복 호출 방지
    if (isFetchingSearch) return;
    
    // 실행 상태로 변경
    isFetchingSearch = true;
    
    // 기본 테이블 구조 생성
    let tableHTML = `
    <div class="kje-list-content">
        <table class="sjTable">
            <thead>
                <tr>
                    <th width='5%'>번호</th>
                    <th width='8%'>성명</th>
                    <th width='15%'>주민번호</th>
                    <th width='15%'>증권번호</th>
                    <th width='12%'>배서일</th>
                    <th width='10%'>월보험료</th>
                    <th width='11%'>c보험료</th>
                    <th width='10%'>배서종류</th>
                    <th width='6%'>처리</th>
                    <th width='8%'>처리자</th>
                </tr>
            </thead>
            <tbody>`;
    
    // API 요청
    fetch(`https://kjstation.kr/api/kjDaeri/gisaMonthlyEndorseSearch.php?dNum=${dNum}&lastMonthDueDate=${lastMonthDueDate}&thisMonthDueDate=${thisMonthDueDate}`)
        .then(response => {
            if (!response.ok) {
                throw new Error(`HTTP error! Status: ${response.status}`);
            }
            return response.json();
        })
        .then(data => {
            // 서버로부터 받은 데이터 처리
            if (data && data.success) {
                // 데이터 처리 로직
                console.log('data', data);
                
                // 합계 계산을 위한 변수 초기화
                let totalMonthlyPremium = 0;
                let totalCPremium = 0;
                
                // 테이블에 데이터 표시 - smsData 배열 확인
                if (data.smsData && data.smsData.length > 0) {
                    data.smsData.forEach((item, index) => {
                        // Determine endorsement type based on 'push' field
                        let endorseType = '';
                        if (item.push === '2') {
                            endorseType = '해지';
                        }else if (item.push === '3') {
                            endorseType = '청약 거절';
							item.preminum='';
							item.c_preminum='';
                        }else if (item.push === '4') {
                            endorseType = '청약';
                        }else if (item.push === '5') {
                            endorseType = '해지 취소';
							item.preminum='';
							item.c_preminum=''
                        }else if (item.push === '5') {
                            endorseType = '청약 취소';
							item.preminum='';
							item.c_preminum='';
                        }  
                        
                        // Format Jumin number with mask for privacy
                        const maskedJumin = item.Jumin ? `${item.Jumin.substring(0, 8)}******` : '-';
                        
                        // Format premium with thousands separator and handle push=2 (negative)
                        let formattedPremium = '0';
                        let formattedCPremium = '0';
                        let premiumValue = 0;
                        let cPremiumValue = 0;
                        
                        if (item.preminum) {
                            premiumValue = Number(item.preminum);
                            // push=2이면 마이너스 표시
                            if (item.push === '2') {
                                formattedPremium = `-${premiumValue.toLocaleString()}`;
                                // 합계 계산 시 마이너스 값으로 처리
                                premiumValue = -premiumValue;
                            } else {
                                formattedPremium = premiumValue.toLocaleString();
                            }
                        }
                        
                        if (item.c_preminum) {
                            cPremiumValue = Number(item.c_preminum);
                            // push=2이면 마이너스 표시 (c_preminum도 동일하게 처리)
                            if (item.push === '2') {
                                formattedCPremium = `-${cPremiumValue.toLocaleString()}`;
                                // 합계 계산 시 마이너스 값으로 처리
                                cPremiumValue = -cPremiumValue;
                            } else {
                                formattedCPremium = cPremiumValue.toLocaleString();
                            }
                        }
                        
                        // divi에 따라 월보험료/c보험료 표시 및 합계 계산
                        let monthlyPremium = '-';
                        let cPremium = '-';
                        
                        if (item.divi === '2') {
                            monthlyPremium = formattedPremium;
                            cPremium = '-';
                            
                            // get 값이 1이 아닌 경우에만 월보험료 합계에 추가 (미처리 항목만 합계에 포함)
                            if (item.get !== '1') {
                                totalMonthlyPremium += premiumValue;
                            }
                        } else if (item.divi === '1') {
                            monthlyPremium = '-';
                            cPremium = formattedCPremium;
                            
                            // get 값이 1이 아닌 경우에만 c보험료 합계에 추가 (미처리 항목만 합계에 포함)
                            if (item.get !== '1') {
                                totalCPremium += cPremiumValue;
                            }
                        }
                        
                        // 정산 상태 select 추가 - 각 항목마다 고유 ID를 부여
                        const selectId = `settlement-select-${item.SeqNo || index}`;
                        
                        // 현재 정산 상태 확인 (get 1: 처리, get 2: 미처리)
                        const currentStatus = item.get === '1' ? '1' : '2';
                        
                        tableHTML += `<tr>
                            <td>${index + 1}</td>
                            <td>${item.Name || '-'}</td>
                            <td>${maskedJumin}</td>
                            <td>${item.dongbuCerti || '-'}</td>
                            <td>${item.endorse_day || '-'}</td>
                            <td class="kje-preiminum">${monthlyPremium}</td>
                            <td class="kje-preiminum">${cPremium}</td>
                            <td>${endorseType}</td>
                            <td>
                                <select id="${selectId}" class="kje-status-ch" 
                                        onchange="updateSettlement('${item.SeqNo}', this.value, '${item.Name || '-'}')">
                                    <option value="1" ${currentStatus === '1' ? 'selected' : ''}>정산</option>
                                    <option value="2" ${currentStatus === '2' ? 'selected' : ''}>미정산</option>
                                </select>
                            </td>
                            <td id="manager-${item.SeqNo}">${item.manager||""}</td>
                        </tr>`;
                    });
                    
                    // 합계 행 추가
                    const totalSum = totalMonthlyPremium + totalCPremium;
                    tableHTML += `<tr style="background-color: #f5f5f5; font-weight: bold;">
                        <td colspan="5" style="text-align: center;">합계</td>
                        <td class="kje-preiminum">${totalMonthlyPremium.toLocaleString()}</td>
                        <td class="kje-preiminum">${totalCPremium.toLocaleString()}</td>
                        <td>계</td>
                        <td colspan="2" class="kje-preiminum">${totalSum.toLocaleString()}</td>
                    </tr>`;


                    
                } else {
                    // 데이터가 없는 경우 (colspan을 10로 수정 - 테이블 헤더가 10개 컬럼임)
                    tableHTML += `<tr><td colspan="10" style="text-align: center;">데이터가 없습니다</td></tr>`;
                }
            } else {
                console.error("API 응답에 필요한 데이터가 없습니다:", data);
                // 오류 메시지도 colspan을 10로 수정
                tableHTML += `<tr><td colspan="10" style="text-align: center;">데이터를 불러올 수 없습니다</td></tr>`;
            }
            
            // 테이블 닫기
            tableHTML += `</tbody>
                </table>
            </div>`;
            
            // 테이블 본문에 데이터 삽입
            if (m_adjustmentMothly) {
                m_adjustmentMothly.innerHTML = tableHTML;
            }
        })
        .catch((error) => {
            console.error("Error details:", error);
            alert("데이터를 불러오는 중 오류가 발생했습니다.");
            
            // 오류 발생 시 메시지 표시 (colspan을 10로 수정)
            tableHTML += `<tr><td colspan="10" style="text-align: center;">오류가 발생했습니다</td></tr>`;
            tableHTML += `</tbody>
                </table>
            </div>`;
            
            if (m_adjustmentMothly) {
                m_adjustmentMothly.innerHTML = tableHTML;
            }
        })
        .finally(() => {
            // 로딩 인디케이터 숨김
            myLoader.hide();
            // 실행 완료 후 상태 변경
            isFetchingSearch = false;
        });
        
    // 모달 표시
    document.getElementById("adjustmentMothly-modal").style.display = "block";
}
// 처리 상태 업데이트 함수
function updateSettlement(seqNo, status, customerName) {
    if (!seqNo) {
        console.error('SeqNo가 없습니다.');
        return;
    }
    
    // 상태 텍스트 설정
    const statusText = status === '1' ? '처리' : '미처리';
    
    console.log(`SeqNo: ${seqNo}, 고객명: ${customerName}, 상태: ${statusText} 업데이트 중...`);
    
    // 로딩 표시
    const settleLoader = LoadingSystem.create('settleLoader', {
        text: `${statusText} 상태로 업데이트 중입니다...`,
        autoShow: true
    });
    
    // 현재 로그인한 사용자 이름 가져오기
    const userName = SessionManager.getUserInfo().name;
    
    // FormData 객체 생성
    const formData = new FormData();
    formData.append('seqNo', seqNo);
    formData.append('status', status);
    formData.append('userName', userName);
    
    // 서버에 정산 상태 업데이트 요청 (FormData 방식)
    fetch('https://kjstation.kr/api/kjDaeri/updateSettlement.php', {
        method: 'POST',
        body: formData
    })
    .then(response => {
        if (!response.ok) {
            throw new Error(`HTTP error! Status: ${response.status}`);
        }
        return response.json();
    })
    .then(data => {
        if (data && data.success) {
            // 성공 메시지 표시
            console.log(`${statusText} 상태로 업데이트 완료`);
            
            // 처리자 정보 업데이트 (처리인 경우에만 표시, 미처리인 경우 비움)
            const managerCell = document.getElementById(`manager-${seqNo}`);
            if (managerCell) {
                managerCell.innerText = userName;
            }
            
            // 성공 알림 (선택적)
            // alert(`${customerName} 고객의 ${statusText} 처리가 완료되었습니다.`);
        } else {
            // 실패 시 select 값 되돌림
            const select = document.getElementById(`settlement-select-${seqNo}`);
            if (select) {
                // 상태 반전 (1→2, 2→1)
                select.value = status === '1' ? '2' : '1';
            }
            alert(`상태 업데이트 중 오류가 발생했습니다: ${data.message || '알 수 없는 오류'}`);
        }
    })
    .catch(error => {
        // 오류 처리
        console.error("Error details:", error);
        // select 값 되돌림
        const select = document.getElementById(`settlement-select-${seqNo}`);
        if (select) {
            // 상태 반전
            select.value = status === '1' ? '2' : '1';
        }
        alert(`상태 업데이트 중 오류가 발생했습니다.`);
    })
    .finally(() => {
        // 로딩 인디케이터 숨김
        if (LoadingSystem.instances['settleLoader']) {
            LoadingSystem.remove('settleLoader');
        }
    });
}
function premiumExcel(dNum) {
    const lastMonthDueDate = document.getElementById('lastMonthDueDate').value;
    const thisMonthDueDate = document.getElementById('thisMonthDueDate').value;
    console.log('dNum', dNum, 'lastMonthDueDate', lastMonthDueDate, 'thisMonthDueDate', thisMonthDueDate);
    
    // 이전에 생성된 로더가 있다면 제거
    if (LoadingSystem.instances['myLoader']) {
        LoadingSystem.remove('myLoader');
    }
    
    // 새 로더 생성
    const myLoader = LoadingSystem.create('myLoader', {
        text: '데이터를 불러오는 중입니다...',
        autoShow: true
    });
    
    fetchCallCount27++;
    console.log(`Fetch Call Count: ${fetchCallCount27}`);
    
    // 새 폼 생성
    var newForm = document.createElement('form');
    newForm.name = 'newForm';
    newForm.method = 'post';
    newForm.action = 'https://kjstation.kr/api/kjDaeri/gisaListExcel.php';
    
    // dNum 파라미터 추가
    var input1 = document.createElement('input');
    input1.setAttribute('type', "hidden");
    input1.setAttribute('name', "dNum");
    input1.setAttribute('value', dNum);
    
    // lastMonthDueDate 파라미터 추가
    var input2 = document.createElement('input');
    input2.setAttribute('type', "hidden");
    input2.setAttribute('name', "lastMonthDueDate");
    input2.setAttribute('value', lastMonthDueDate);
    
    // thisMonthDueDate 파라미터 추가
    var input3 = document.createElement('input');
    input3.setAttribute('type', "hidden");
    input3.setAttribute('name', "thisMonthDueDate");
    input3.setAttribute('value', thisMonthDueDate);
    
    // 폼에 입력 요소들 추가
    newForm.appendChild(input1);
    newForm.appendChild(input2);
    newForm.appendChild(input3);
    
    // 폼을 body에 추가하고 제출
    document.body.appendChild(newForm);
    newForm.submit();
    
    // 폼 제출 후 폼 제거
    setTimeout(function() {
        document.body.removeChild(newForm);
        // 로더 숨기기
        myLoader.hide();
    }, 1000);
}
// 검색 결과를 표시하는 함수
function displaySearchResult(data) {
    const memoListDiv = document.getElementById('memoList');
    
    // 기존 내용 초기화
    memoListDiv.innerHTML = '';
    
    if (data.status === 'success' && data.data && data.data.length > 0) {
        // 결과 개수 표시
        const countInfo = document.createElement('div');
        countInfo.className = 'result-count';
        countInfo.textContent = `총 ${data.count}개의 메모가 있습니다.`;
        memoListDiv.appendChild(countInfo);
        
        // 테이블 생성
        const table = document.createElement('table');
        table.className = 'sjTable';
        
        // 테이블 헤더 생성
        const thead = document.createElement('thead');
        const headerRow = document.createElement('tr');
        const headers = ['번호', '메모내용', '메모유형', '작성일자', '작성자'];
        
        headers.forEach(headerText => {
            const th = document.createElement('th');
            th.textContent = headerText;
            headerRow.appendChild(th);
        });
        
        thead.appendChild(headerRow);
        table.appendChild(thead);
        
        // 테이블 본문 생성
        const tbody = document.createElement('tbody');
        
        // 메모 유형 표시를 위한 매핑
        const memoKindMap = {
            '1': '일반',
            '2': '납부',
            '3': '체납',
            '4': '계좌'
        };
        
        data.data.forEach(memo => {
            const row = document.createElement('tr');
            
            // 각 셀 생성
            const numCell = document.createElement('td');
            numCell.textContent = memo.num;
            row.appendChild(numCell);
            
           
            
            const memoCell = document.createElement('td');
            memoCell.textContent = memo.memo;
            memoCell.className = 'memo-content';
            row.appendChild(memoCell);
            
            const memoKindCell = document.createElement('td');
            memoKindCell.textContent = memoKindMap[memo.memokind] || memo.memokind;
            row.appendChild(memoKindCell);
            
            const wdateCell = document.createElement('td');
            wdateCell.textContent = memo.wdate;
            row.appendChild(wdateCell);
            
            const userIdCell = document.createElement('td');
            userIdCell.textContent = memo.userid || '-';
            row.appendChild(userIdCell);
            
            tbody.appendChild(row);
        });
        
        table.appendChild(tbody);
        memoListDiv.appendChild(table);
        
        // 스타일 추가
        
    } else {
        // 데이터가 없거나 오류가 발생했을 경우
        const noDataMsg = document.createElement('div');
        noDataMsg.className = 'no-data';
        noDataMsg.textContent = '조회된 메모가 없습니다.';
        memoListDiv.appendChild(noDataMsg);
    }
}



// memoPremiumSearch 함수를 기존 코드와 함께 사용
function memoPremiumSearch(jumin) {
    // FormData 객체 생성
    const formData = new FormData();
    
    // 데이터 추가
    formData.append('jumin', jumin);
    
    // AJAX를 이용하여 서버에 데이터 전송
    fetch('https://kjstation.kr/api/kjDaeri/memoSearch.php', {
        method: 'POST',
        body: formData
    })
    .then(response => {
        if (!response.ok) {
            throw new Error('Network response was not ok');
        }
        return response.json();
    })
    .then(data => {
        console.log('Success:', data);
        // 성공 시 처리할 로직
        displaySearchResult(data);
    })
    .catch(error => {
        console.error('Error:', error);
        // 오류 발생 시 처리할 로직
        alert('조회 중 오류가 발생했습니다.');
    });
}
function premiumSearch(dNum,lastMonthDueDate,thisMonthDueDate){

	// 이전에 생성된 로더가 있다면 제거
    if (LoadingSystem.instances['myLoader']) {
        LoadingSystem.remove('myLoader');
    }
    
    // 새 로더 생성
    const myLoader = LoadingSystem.create('myLoader', {
        text: '데이터를 불러오는 중입니다...',
        autoShow: true
    });

	 fetchCallCount27++;
  console.log(`Fetch Call Count: ${fetchCallCount27}`);
  // 테이블 본문 선택
    const premiumStatistics = document.getElementById('premiumStatistics');
  // 이미 실행 중이면 중복 호출 방지
  if (isFetchingSearch) return;
  
  // 실행 상태로 변경
  isFetchingSearch = true;
  
  // API 요청
  fetch(`https://kjstation.kr/api/kjDaeri/gisaListAdjustment.php?dNum=${dNum}&lastMonthDueDate=${lastMonthDueDate}&thisMonthDueDate=${thisMonthDueDate}`)
    .then(response => {
      if (!response.ok) {
        throw new Error(`HTTP error! Status: ${response.status}`);
      }
      return response.json();
    })
    .then(data => {
      // 서버로부터 받은 데이터 처리
			if (data && data.success) {
				  // 데이터가 배열 형태로 오므로 반복 처리
				  let statisticsHTML = '';
				  
				  if (data.data && data.data.length > 0) {
					// 각 증권번호별 데이터 처리
					data.data.forEach(policy => {
					  // 숫자 포맷팅
					  //월보험료
					  const formattedMothlyPremium = policy.total_AdjustedInsuranceMothlyPremium ? 
						parseFloat(policy.total_AdjustedInsuranceMothlyPremium).toLocaleString("en-US") : "0";
					  //보험회사 납입보험료
					  const formattedCompanyPremium = policy.total_AdjustedInsuranceCompanyPremium ? 
						parseFloat(policy.total_AdjustedInsuranceCompanyPremium).toLocaleString("en-US") : "0";
					  //환산보험료
					  const formattedConversionCompanyPremium = policy.Conversion_AdjustedInsuranceCompanyPremium ? 
						parseFloat(policy.Conversion_AdjustedInsuranceCompanyPremium).toLocaleString("en-US") : "0";
					  //월 배서보험료
					  const formattedETotalMonthPremium = policy.eTotalMonthPremium ? 
						parseFloat(policy.eTotalMonthPremium).toLocaleString("en-US") : "0";
					  //보험회사 배서보험료
					  const formattedETotalCompanyPremium = policy.eTotalCompanyPremium ? 
						parseFloat(policy.eTotalCompanyPremium).toLocaleString("en-US") : "0";

					  // divi에 따라 계 금액 계산
					  let totalAmount = 0;
					  if (policy.divi === '2') {
						// divi가 2일 경우: 월보험료 + 월 배서보험료
						totalAmount = (parseFloat(policy.total_AdjustedInsuranceMothlyPremium) || 0) + 
									 (parseFloat(policy.eTotalMonthPremium) || 0);
					  } else {
						// divi가 1일 경우: 보험회사보험료 + 보험회사 배서보험료
						totalAmount = (parseFloat(policy.total_AdjustedInsuranceCompanyPremium) || 0) + 
									 (parseFloat(policy.eTotalCompanyPremium) || 0);
					  }
					  const formattedTotalAmount = totalAmount.toLocaleString("en-US");
					  const iDiviType = { "1": "정상납", "2": "월납"};
					  const diviType = iDiviType[policy.divi];
					  
					  // 각 정책에 대한 행 생성
					  statisticsHTML += `
						<tr>
						  <td>${policy.policyNum || '-'}</td>
						  <td>${diviType || '-'}</td>
						  <td class="kje-preiminum">${policy.drivers_count || '-'}</td>
						  <td class="kje-preiminum">${policy.divi === '2' ? formattedMothlyPremium : '-'}</td>
						  <td class="kje-preiminum">${policy.divi === '1' ? formattedCompanyPremium : '-'}</td>
						  <td class="kje-preiminum">${policy.divi === '2' ? formattedETotalMonthPremium : formattedETotalCompanyPremium}</td>
						  <td class="kje-preiminum">${formattedTotalAmount}</td>
						  <td class="kje-preiminum">${policy.divi === '2' ? formattedConversionCompanyPremium : '-'}</td>
						</tr>
					  `;
					});
					
					


					// 합계 계산 (divi 별로 분리)
					const divi1Policies = data.data.filter(policy => policy.divi === '1');
					const divi2Policies = data.data.filter(policy => policy.divi === '2');
					
					// divi=1인 경우만 합산 (보험회사 보험료)
					const totalCompanyPremium = divi1Policies.reduce((sum, policy) => 
					  sum + (parseFloat(policy.total_AdjustedInsuranceCompanyPremium) || 0), 0).toLocaleString("en-US");
					
					// divi=2인 경우만 합산 (월보험료)
					const totalMothlyPremium = divi2Policies.reduce((sum, policy) => 
					  sum + (parseFloat(policy.total_AdjustedInsuranceMothlyPremium) || 0), 0).toLocaleString("en-US");
					
					// 환산보험료는 divi=2인 경우만 합산
					const totalConversionPremium = divi2Policies.reduce((sum, policy) => 
					  sum + (parseFloat(policy.Conversion_AdjustedInsuranceCompanyPremium) || 0), 0).toLocaleString("en-US");
					
					// 배서보험료 합계도 divi에 따라 분리 계산
					const totalETotalMonthPremium = divi2Policies.reduce((sum, policy) => 
					  sum + (parseFloat(policy.eTotalMonthPremium) || 0), 0).toLocaleString("en-US");
					
					const totalETotalCompanyPremium = divi1Policies.reduce((sum, policy) => 
					  sum + (parseFloat(policy.eTotalCompanyPremium) || 0), 0).toLocaleString("en-US");
					
					// 전체 계 금액 계산 (정확한 계산을 위해 숫자로 계산 후 포맷팅)
					const overallTotal = data.data.reduce((sum, policy) => {
					  let policyTotal = 0;
					  if (policy.divi === '2') {
						policyTotal = (parseFloat(policy.total_AdjustedInsuranceMothlyPremium) || 0) + 
									 (parseFloat(policy.eTotalMonthPremium) || 0);
					  } else {
						policyTotal = (parseFloat(policy.total_AdjustedInsuranceCompanyPremium) || 0) + 
									 (parseFloat(policy.eTotalCompanyPremium) || 0);
					  }
					  return sum + policyTotal;
					}, 0).toLocaleString("en-US");
					
					// 전체 배서보험료 합계 계산 (divi 값에 상관없이)
					const totalEndorsementPremium = data.data.reduce((sum, policy) => {
					  // divi에 따라 적절한 배서보험료 필드 선택
					  const endorsementValue = policy.divi === '2' 
						? (parseFloat(policy.eTotalMonthPremium) || 0)
						: (parseFloat(policy.eTotalCompanyPremium) || 0);
					  return sum + endorsementValue;
					}, 0).toLocaleString("en-US");
					
					// 대리기사 인원수 합계 계산
					const totalDriversCount = data.data.reduce((sum, policy) => 
					  sum + (parseInt(policy.drivers_count) || 0), 0);

					// 합계 행에 적용
					statisticsHTML += `
					  <tr class="total-row">
						<td colspan="2" style="text-align: center;">합계</td>
						<td class="kje-preiminum">${totalDriversCount}</td>
						<td class="kje-preiminum">${totalMothlyPremium}</td>
						<td class="kje-preiminum">${totalCompanyPremium}</td>
						<td class="kje-preiminum">${totalEndorsementPremium}</td>
						<td class="kje-preiminum">${overallTotal}</td>
						<td class="kje-preiminum">${totalConversionPremium}</td>
					  </tr>
					`;

					// 합계 행에 적용
					statisticsHTML += `
					  <tr class="total-row">
						<td colspan="6" style="text-align: center; ">업체에 통보할 보험료</td>
						<td><input type='text' id='Adjustment-${dNum}' class='memoInput' placeholder='확정보험료 입력하고 엔터'  
onkeypress="if(event.key === 'Enter') { adjustmentPremium(this, ${dNum},'${thisMonthDueDate}'); return false; }" autocomplete="off"></td>
						<td><button class="sms-stats-button" onclick="SettlementList(2)">정산리스트</button></td>
						
					  </tr>
					`;

				  } else {
					// 데이터가 없는 경우
					statisticsHTML = `
					  <tr>
						<td colspan="8" style="text-align: center;">데이터가 없습니다.</td>
					  </tr>
					`;
				  }
				  
				  // 테이블에 HTML 적용
				  premiumStatistics.innerHTML = statisticsHTML;
				  
				} else {
				  console.error("API 응답에 필요한 데이터가 없습니다:", data);
				  premiumStatistics.innerHTML = `
					<tr>
					  <td colspan="7" style="text-align: center;">데이터를 불러오는 중 오류가 발생했습니다.</td>
					</tr>
				  `;
				}
	}).catch((error) => {
      console.error("Error details:", error);
      alert("데이터를 불러오는 중 오류가 발생했습니다.");
    })
    .finally(() => {
      // 중앙 로딩 숨김 (필요한 경우)
      myLoader.hide();
      // 실행 완료 후 상태 변경
      isFetchingSearch = false;
    });


}
//확정보험료 입력
// 전역 스코프에 별도로 함수 정의
function adjustmentPremium(inputElement, dNum, thisMonthDueDate) {
    // 입력 값 가져오기
    const enteredValue = inputElement.value;
    
    // 입력값이 비어있거나 숫자가 아닌 경우 처리
    if (!enteredValue || isNaN(enteredValue)) {
        alert('유효한 숫자를 입력해주세요.');
        return;
    }
    
    console.log('입력된 값:', enteredValue);
    console.log('dNum:', dNum);
    console.log('thisMonthDueDate:', thisMonthDueDate);
    
    // 현재 로그인한 사용자 이름 가져오기
    const userName = SessionManager.getUserInfo().name;
    
    // FormData 객체 생성
    const formData = new FormData();
    
    // FormData에 필요한 데이터 추가
    formData.append('adjustmentAmount', enteredValue);
    formData.append('dNum', dNum);
    formData.append('thisMonthDueDate', thisMonthDueDate);
    formData.append('userName', userName);
    
    // 로딩 표시 생성
    const myLoader = LoadingSystem.create('adjustmentLoader', {
        text: '확정보험료를 처리 중입니다...',
        autoShow: true
    });
    
    // 서버에 데이터 전송
    fetch('https://kjstation.kr/api/kjDaeri/AdjustmentPremiumSave.php', {
        method: 'POST',
        body: formData
    })
    .then(response => {
        if (!response.ok) {
            throw new Error(`HTTP error! Status: ${response.status}`);
        }
        return response.json();
    })
    .then(data => {
        if (data.success) {
           // alert('확정보험료가 성공적으로 저장되었습니다.');
            // 성공 후 입력 필드 초기화
            inputElement.value = '';

			SettlementList();
            // 필요한 경우 페이지 새로고침 또는 데이터 다시 로드
            // location.reload(); 또는 다른 갱신 함수 호출
        } else {
            alert('오류가 발생했습니다: ' + (data.message || '알 수 없는 오류'));
        }
    })
    .catch(error => {
        console.error('Error details:', error);
        alert('확정보험료 저장 중 오류가 발생했습니다.');
    })
    .finally(() => {
        // 로딩 숨기기
        myLoader.hide();
    });
}
function SettlementList(attempted) {
    console.log('attempted', attempted);
    // attempted 값의 타입을 확인
    console.log('attempted 타입:', typeof attempted);
    
    document.getElementById('settlementMothly_daeriCompany').innerHTML = "현황"; 
     
    const m_settlementMothly = document.getElementById('m_settlementMothly');
    m_settlementMothly.innerHTML = "";
    
    // 문자열로 변환하여 비교하도록 수정
    attempted = String(attempted);
    
    const fieldContents = `<div class="kje-list-container">
        <!-- 검색 영역 -->
        <div class="kje-list-header">
            <div class="kje-left-area">
                <div class="kje-search-area">
                    <div class="select-container">
                        <input type='date' id='lastDate' class='date-range-field'>
                    </div>
                    <div class="select-container">
                        <input type='date' id='thisDate' class='date-range-field'>
                    </div>
                    <div class="select-container">
                        <select id="damdanga3" class="styled-select"></select>
                    </div>
                    <div class="select-container">
                        <select id="attempted" class="styled-select">
                            <option value='1' ${attempted === '1' ? 'selected' : ''}>전체</option>
                            <option value='2' ${attempted === '2' ? 'selected' : ''}>미수</option>
                        </select>
                    </div>
                    <div class="select-container">
                        <button class="sms-stats-button">검색</button>
                    </div>
                </div>
            </div>
            <div class="kje-right-area">
               
            </div>
        </div>
    
        <!-- 리스트 영역 -->
        <div class="kje-list-content">
            <table class="kjTable">
                <thead>
                    <tr>
                        <th width='5%'>No</th>
                        <th width='10%'>정산일</th>
                        <th width='16%'>대리운전회사</th>
                        <th width='8%'>보험료</th>
                        <th width='8%'>manager</th>
                        <th width='8%'>담당자</th>
                        <th width='8%'>보험료</th>
                        <th width='8%'>입력자</th>
                        <th width='8%'>차액</th>
                        <th width='21%'>메모</th>
                    </tr>
                </thead>
                <tbody id="settleList">
                    <!-- 데이터가 여기에 동적으로 로드됨 -->
                </tbody>
            </table>
        </div>
    </div>`;
    
    m_settlementMothly.innerHTML = fieldContents;
    
    // HTML이 렌더링된 후 옵션 상태 다시 확인
    setTimeout(() => {
        const attemptedSelect = document.getElementById('attempted');
        console.log('현재 선택된 값:', attemptedSelect.value);
        
        // 만약 템플릿 방식이 작동하지 않는다면 직접 설정
        if (attempted === '2' && attemptedSelect.value !== '2') {
            attemptedSelect.value = '2';
            console.log('값을 직접 설정함:', attemptedSelect.value);
        }
    }, 100);
    
    // 현재 날짜 기준으로 기본 날짜 설정 (한 달 범위)
    const today = new Date();
    const lastMonth = new Date(today);
    lastMonth.setMonth(today.getMonth() - 1);
    
    // 날짜 형식 변환 함수
    function formatDate(date) {
        const year = date.getFullYear();
        const month = String(date.getMonth() + 1).padStart(2, '0');
        const day = String(date.getDate()).padStart(2, '0');
        return `${year}-${month}-${day}`;
    }
    
    // 기본 날짜 설정
    document.getElementById('lastDate').value = formatDate(lastMonth);
    document.getElementById('thisDate').value = formatDate(today);
    
    // 모달 표시
    document.getElementById("settlementMothly-modal").style.display = "block";
    settleSearch(attempted);
    damdangaLoadSearchTable2(); //담당자 조회 하기
}

function settleSearch(attempted){
    const formData = new FormData();
    
    // FormData에 필요한 데이터 추가
    formData.append('lastDate', document.getElementById('lastDate').value);
    formData.append('thisDate', document.getElementById('thisDate').value);
	formData.append('attempted',attempted); //1은 전체, 2미수만 
	formData.append('damdanga',SessionManager.getUserInfo().name); //1은 전체, 2미수만 ;

    
    // 로딩 표시 생성
    const myLoader = LoadingSystem.create('adjustmentLoader', {
        text: '정산리스트 조회중...',
        autoShow: true
    });
    
    // 서버에 데이터 전송
    fetch('https://kjstation.kr/api/kjDaeri/AdjustmentSettleSearch.php', {
        method: 'POST',
        body: formData
    })
    .then(response => {
        if (!response.ok) {
            throw new Error(`HTTP error! Status: ${response.status}`);
        }
        return response.json();
    })
    .then(data => {
        if (data.success) {
            // 데이터를 테이블에 표시
            displaySettlementData(data);
            
        } else {
            alert('오류가 발생했습니다: ' + (data.message || '알 수 없는 오류'));
        }
    })
    .catch(error => {
        console.error('Error details:', error);
        alert('정산리스트 조회중 에러발생.');
    })
    .finally(() => {
        // 로딩 숨기기
        myLoader.hide();
    });
}

// 정산 데이터를 테이블에 표시하는 함수
function displaySettlementData(data) {
    console.log('data', data);
    const settleList = document.getElementById('settleList');
    
    // 테이블 내용 초기화
    settleList.innerHTML = '';
    
    // 데이터가 없는 경우 처리
    if (data.count === 0 || !data.data || data.data.length === 0) {
        const emptyRow = document.createElement('tr');
        emptyRow.innerHTML = '<td colspan="7" class="text-center">조회된 정산 데이터가 없습니다.</td>';
        settleList.appendChild(emptyRow);
        return;
    }
    
    let tableContent = '';
    
    // 데이터 행 추가
    data.data.forEach((item, index) => {
        // 금액 포맷팅 함수
        const formatAmount = (amount) => {
            // 숫자로 변환 후 천 단위 콤마 추가
            const numAmount = parseFloat(amount);
            return numAmount.toLocaleString('ko-KR');
        };
        
        // 금액 클래스 설정
        const amountClass = parseFloat(item.adjustmentAmount) < 0 ? 'text-danger' : '';
        
        // 차이 금액 계산 (receivedAmount가 있을 경우에만)
        let differenceAmount = '';
        if (item.receivedAmount) {
            const difference = parseFloat(item.adjustmentAmount) - parseFloat(item.receivedAmount);
            differenceAmount = formatAmount(difference);
        }
        
        // HTML 행 구성
        tableContent += `
            <tr>
                <td>${index + 1}</td>
                <td>${item.thisMonthDueDate}</td>
                <td>${item.company || ''}</td>
                <td class="kje-preiminum">${formatAmount(item.adjustmentAmount)}</td>
                <td>${item.createUser}</td>
                <td></td>
                <td>
                    <input type='text' 
                        id='getPrinum_${item.id}' 
                        class="memoInput2"
                        placeholder='받을 보험료' 
                        value='${formatAmount(item.receivedAmount)}'
                        onkeypress="if(event.key === 'Enter') { getPremium(this, ${item.id}); return false; }" autocomplete="off"
                    >
                </td>
                <td>${item.receiveUSer}</td>
                <td class="kje-preiminum">
                    <span id='chai-${item.id}'>${differenceAmount}</span> 
                </td>
                <td>
                    <input type='text' 
                        id='memo_${item.id}' 
                        class="memoInput2"
                        placeholder='메모 입력' 
                        value='${item.memo || ''}'
                        onkeypress="if(event.key === 'Enter') { getPremiumMemo(this, ${item.id}); return false; }" autocomplete="off"
                    >
                </td>
            </tr>
        `;
    });
    
    // 테이블에 모든 행 추가
    settleList.innerHTML = tableContent;
}

function getPremium(inputElement, id) {
    // 콤마 제거 후 숫자 검증
    const cleanValue = inputElement.value.replace(/,/g, '');
    
    // 입력값이 비어있거나 숫자가 아닌 경우 처리
    if (!cleanValue || isNaN(cleanValue)) {
        alert('유효한 숫자를 입력해주세요.');
        inputElement.focus();
        return;
    }
    
    console.log('입력된 값(숫자만):', cleanValue);
    console.log('id:', id);
    const userName = SessionManager.getUserInfo().name;
    
    // FormData 객체 생성
  
   
    const formData = new FormData();
    
  
    formData.append('cleanValue',cleanValue);
	formData.append('userName', userName);
    formData.append('id',id);
    
    // 로딩 표시 생성
    const myLoader = LoadingSystem.create('adjustmentLoader', {
        text: '받은 보험료 입력중...',
        autoShow: true
    });
    
    // 서버에 데이터 전송
    fetch('https://kjstation.kr/api/kjDaeri/AdjustmentGetPreium.php', {
        method: 'POST',
        body: formData
    })
    .then(response => {
        if (!response.ok) {
            throw new Error(`HTTP error! Status: ${response.status}`);
        }
        return response.json();
    })
    .then(data => {
        if (data.success) {
			 alert(data.message);
			// 데이터를 테이블에 표시
			const userNameSpan = document.getElementById(`getDamdanga-${id}`);
			if (userNameSpan) {
				userNameSpan.textContent = data.data.userName;
			}
			
			const chaiSpan = document.getElementById(`chai-${id}`);
			if (chaiSpan) {
				chaiSpan.textContent = data.data.chai;
			}
			
			// 성공 메시지 표시 (선택사항)
			console.log(data.message);
    
            
            
        } else {
            alert('오류가 발생했습니다: ' + (data.message || '알 수 없는 오류'));
        }
    })
    .catch(error => {
        console.error('Error details:', error);
        alert('받은 보험료 입력중  에러발생.');
    })
    .finally(() => {
        // 로딩 숨기기
        myLoader.hide();
    });
    
    // 숫자 포맷팅 적용
    inputElement.value = parseFloat(cleanValue).toLocaleString('ko-KR');
    
    // 다음 필드(메모 필드)로 포커스 이동
    const memoField = document.getElementById(`memo_${id}`);
    if (memoField) {
        memoField.focus();
    }
}

function getPremiumMemo(inputElement, id) {
    // 입력 값 가져오기
    const enteredValue = inputElement.value;
    
    // 메모 필수 입력 체크 (필수가 아니라면 이 부분 제거)
    if (!enteredValue) {
        alert('메모를 입력해주세요.');
        inputElement.focus();
        return;
    }
    
    console.log('입력된 메모:', enteredValue);
    console.log('id:', id);
    
    // 여기에 서버 저장 로직 추가
    // saveToServer(id, 'memo', enteredValue);
    
    // 저장 완료 후 시각적 피드백 (선택사항)
    showSaveFeedback(inputElement);
    
    // 다음 행의 금액 필드로 포커스 이동 (선택사항)
    moveToNextRow(id);
}

// 저장 성공 시각적 피드백 (선택적 기능)
function showSaveFeedback(element) {
    const originalBg = element.style.backgroundColor;
    element.style.backgroundColor = '#d4edda';
    setTimeout(() => {
        element.style.backgroundColor = originalBg;
    }, 1000);
}

// 다음 행으로 포커스 이동 (선택적 기능)
function moveToNextRow(currentId) {
    // 테이블의 모든 행 요소 가져오기
    const rows = document.querySelectorAll('#settleList tr');
    let found = false;
    let nextId = null;
    
    // 현재 ID를 가진 행 다음의 행 찾기
    for (let i = 0; i < rows.length; i++) {
        const inputElement = rows[i].querySelector(`input[id^="getPrinum_"]`);
        if (!inputElement) continue;
        
        const rowId = inputElement.id.split('_')[1];
        
        if (found) {
            nextId = rowId;
            break;
        }
        
        if (rowId == currentId) {
            found = true;
        }
    }
    
    // 다음 행의 금액 입력 필드로 포커스 이동
    if (nextId) {
        const nextInput = document.getElementById(`getPrinum_${nextId}`);
        if (nextInput) {
            nextInput.focus();
        }
    }
}

// 정산 모델에서 담당자 선택하게 
function damdangaLoadSearchTable2(){

	fetchCallCount5++;
	console.log(`Fetch Call Count: ${fetchCallCount5}`);

	// 이미 실행 중이면 중복 호출 방지
	if (isFetchingDamdanga2) return;
	
	// 실행 상태로 변경
	isFetchingDamdanga2 = true;
	const sj='sj_'
	// API 요청
	fetch(`https://kjstation.kr/api/kjDaeri/damdangaList.php?sj=${sj}`)
    .then(response => {
      if (!response.ok) {
        throw new Error(`HTTP error! Status: ${response.status}`);
      }
      return response.json();
    })
    .then(data => {
      // 성공적으로 데이터를 받았을 때 드롭다운 채우기
      populateDamdangaList2(data);
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
      if (typeof isFetchingDamdanga2 !== 'undefined') {
        isFetchingDamdanga2 = false;
      }
    });
}

function populateDamdangaList2(data) {
  console.log(data);
  const selectElement = document.getElementById('damdanga3');
  
  // 요소가 존재하는지 확인
  if (!selectElement) {
    console.error("Element with ID 'damdanga3' not found");
    return; // 요소가 없으면 함수 종료
  }
  
  // 현재 로그인한 사용자 정보 가져오기
  const currentUserName = SessionManager.getUserInfo().name;
  
  // 기존 옵션 제거
  selectElement.innerHTML = '';
  
  // 기본 옵션 추가
  const defaultOption = document.createElement('option');
  defaultOption.value = 'all';
  defaultOption.textContent = '--  담당자 선택 --';
  selectElement.appendChild(defaultOption);
  
  // 현재 사용자와 일치하는 옵션을 저장할 변수
  let userOption = null;
  
  // 각 항목을 옵션으로 추가
  data.data.forEach(item => {
    const option = document.createElement('option');
    option.value = item.num;
    option.textContent = `[${item.name}]`;
    selectElement.appendChild(option);
    
    // 현재 사용자 이름과 옵션의 이름이 일치하는지 확인
    // "[홍길동]"과 "홍길동"을 비교하기 위해 대괄호 제거
    const optionName = item.name;
    if (optionName === currentUserName) {
      userOption = option;
    }
  });
  
  // 일치하는 사용자가 있으면 해당 옵션 선택
  if (userOption) {
    userOption.selected = true;
    
    // 선택된 값에 따라 테이블 로드 함수 호출 (change 이벤트를 직접 발생시키지 않고)
    const selectedValue = userOption.value;
    console.log('자동 선택된 담당자:', selectedValue);
    dNumCompanyTable2(selectedValue, "", "");
  }
  
  // change 이벤트 추가
  selectElement.addEventListener('change', function() {
    const selectedValue = this.value;
    console.log('선택된 담당자:', selectedValue);
    
    // 선택된 값에 따라 다른 동작 수행
    if (selectedValue === 'all') {
      console.log('담당자가 선택되지 않았습니다.');
      dNumCompanyTable2( selectedValue, "", ""); // 특정 증권 데이터 로드 함수 호출
    } else {
      console.log('특정 담당자가 선택되었습니다:', selectedValue);
      dNumCompanyTable2(selectedValue, "", ""); // 특정 증권 데이터 로드 함수 호출
    }
  });
}

// 담당자 별 받은 보험료 조회 
function dNumCompanyTable2(selectedValue){
		console.log('selectedValue',selectedValue);

}