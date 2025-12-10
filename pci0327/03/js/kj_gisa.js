/** kjstation.kr  증권별코드**/
let isFetchingGisa = false;  // fetch 중복 실행 방지 변수
let fetchCallCount4 = 0;  // API 호출 횟수 추적 변수
let currentPage2 = 1;  // 현재 페이지
let itemsPerPage2 = 15;  // 페이지당 표시할 항목 수
let gisaData = null;  // 전체 데이터 저장 변수 (policyData2를 gisaData로 변경)

function kjSearch(){
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
						<input type='date' id='gijunDay' class='date-range-field' value='${todayStr}'>
						<input type='text' id='gisa_name' class='date-range-field' placeholder='성명' 
							onkeypress="if(event.key === 'Enter') { gisaSearch(); return false; }" autocomplete="off" >
					</div>
					<div class="select-container">
						<select id="gisaPushSangtae" class="styled-select" >
							<option value='1'>정상</option>
							<option value='2'>전체</option>
						 </select>
					</div>
					<div class="select-container">
						<button class="sms-stats-button" onclick="gisaSearch()">검색</button>
					</div>
					
					<div id='currentSituation'></div>
				</div>
			</div>
			<div class="kje-right-area">
				
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
							<th class="col-2">성명</th>
							<th class="col-3">주민번호</th>
							<th class="col-4">핸드폰번호</th>
							<th class="col-5">상태</th>
							<th class="col-6">증권성격</th>
							<th class="col-7">대리운전회사</th>
							<th class="col-8">보험회사</th>
							<th class="col-9">증권번호</th>
							<th class="col-10">할인할증</th>
							<th class="col-11">등록일</th>
							<th class="col-12">해지일</th>
							<th class="col-13">사고</th>
							<th class="col-14">인증번호</th>
							

						</tr>
					</thead>
					<tbody id="gisaList">
						<!-- 데이터가 여기에 동적으로 로드됨 -->
					</tbody>
				</table>
			</div>
		</div>

		<!-- 페이지네이션 -->
		<div class="policy-pagination" id="gisa-pagination"></div>
	</div>`
	
	pageContent.innerHTML = fieldContents;
	
	// 페이지네이션 컨테이너가 제대로 생성되었는지 확인
	console.log("페이지네이션 컨테이너:", document.getElementById('gisa-pagination'));
	
	
	
	// 로딩 및 페이지네이션 기능을 위한 스타일 추가
	addGisaCustomStyles();
}

// 로딩 표시 함수
function showKjeLoading() {
	const loadingIndicator = document.getElementById('kjLoadingIndicator');
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

function gisaSearch() {
	// 여기에 fetch 코드 넣기
	fetchCallCount4++;
	console.log(`Fetch Call Count: ${fetchCallCount4}`);

	// 이미 실행 중이면 중복 호출 방지
	if (isFetchingGisa) return; // isFetchingPolicy에서 isFetchingGisa로 변경
	
	// 실행 상태로 변경
	isFetchingGisa = true; // isFetchingPolicy에서 isFetchingGisa로 변경
	const sj = 'gisa_';

	// 로딩 표시
	showKjeLoading();

	const gijunDay = document.getElementById("gijunDay").value;
	const gisa_name = document.getElementById("gisa_name").value;
	const gisaPushSangtae = document.getElementById("gisaPushSangtae").value;
	
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
			gisaData = data;
            console.log("Fetched data:", data); // 데이터 확인 로그 추가
			
			// 페이지 초기화 및 데이터 표시
			currentPage2 = 1; // currentPage에서 currentPage2로 변경
			renderGisaTable(data);
			
			// 직접 DOM에 페이지네이션 생성
			createGisaPagination(data);
		})
		.catch((error) => {
			console.error("Error details:", error);
			alert("데이터를 불러오는 중 오류가 발생했습니다.");
		})
		.finally(() => {
			// 로딩 숨김
			hideKjeLoading();
			
			// 실행 완료 후 상태 변경
			isFetchingGisa = false; // isFetchingPolicy에서 isFetchingGisa로 변경
		});
}

// 테이블에 기사 데이터를 렌더링하는 함수
function renderGisaTable(data) {
    // 실제 데이터가 있는지 확인
    if (!data || !data.data || data.data.length === 0) {
        const gisaList = document.getElementById('gisaList');
        gisaList.innerHTML = '<tr><td colspan="16" class="no-data">검색 결과가 없습니다.</td></tr>';
        
        // 페이지네이션 영역 비우기
        document.getElementById('gisa-pagination').innerHTML = '';
        return;
    }
    
    // 실제 데이터 배열
    const gisaData = data.data;
    
    // 현재 페이지에 표시할 데이터 계산
    const startIndex = (currentPage2 - 1) * itemsPerPage2;
    const endIndex = Math.min(startIndex + itemsPerPage2, gisaData.length);
    const currentPageData = gisaData.slice(startIndex, endIndex);
    
    // 테이블 본문 선택
    const gisaList = document.getElementById('gisaList');
    
    // 데이터 표시를 위한 HTML 생성
    let tableHTML = '';
    
    // 현재 페이지 데이터 순회하며 행 생성
    for (let i = 0; i < currentPageData.length; i++) {
        const item = currentPageData[i];
        const rowNumber = startIndex + i + 1; // 일련번호 계산
        
        // 상태 표시 설정

          let pushOptions = ""; 
 //const pushType={"1":"청약","2":"해지","3":"청약거절","4":"정상","5":"해지취소","6":"청약취소"}

            if (item.push == 4) { 
				if(item.cancel==='42'){
					pushOptions=`해지중`;
				
				}else{
					pushOptions = `
						<select class="kje-status-ch" data-id="${item.num}" id='push1P-${item.num}' 
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
				certiType = `
						<select class="kje-status-ch" data-id="${item.num}" id='pushP-${item.num}' 
								  onchange="updateSmsContent(this, '${item.Name}', ${item.num})">                           
							<option value="1" ${item.etag == 1 ? "selected" : ""}>대리</option> 
							<option value="2" ${item.etag  == 2 ? "selected" : ""}>탁송</option> 
							<option value="3" ${item.etag  == 3 ? "selected" : ""}>대리렌트</option> 
							<option value="4" ${item.etag  == 4 ? "selected" : ""}>탁송렌트</option> 
							<option value="5" ${item.etag  == 5 ? "selected" : ""}>확대탁송</option> 
						</select>
					`;	
			
					
        const icompanyType = { "1": "흥국", "2": "DB", "3": "KB", "4": "현대", "5": "현대", "6": "롯데", "7": "MG", "8": "삼성" };
		 const insuranceCompany=icompanyType[item.InsuranceCompany] ;
        // 보험회사 표시 설정
	let personRateName = '';
    let personRate2 = 0; // 변수를 선언해줍니다.
// 문자열을 숫자로 변환
const rateValue = parseInt(item.rate, 10);

const resultRate = calculatePersonRate(rateValue);


// 결과 확인
console.log('선택된 case:', rateValue);
console.log('personRate2:', resultRate.personRate2);
console.log('personRateName:', resultRate.personRateName);

        tableHTML += `
        <tr>
            <td >${rowNumber}</td>
            <td ><input type='text' id='Name-${item.num}' value="${item.Name}" class='geInput2'  autocomplete="off"
				onkeypress="if(event.key === 'Enter') { validateGisaUpdateName(this, ${item.num}); return false; }"</td>
            <td>${item.Jumin || ''}</td>
			<td><input type='text' id='Hphone-${item.num}' value="${item.Hphone}" class='geInput2' oninput="formatPhoneNumber(this)" autocomplete="off"
				onkeypress="if(event.key === 'Enter') { validateGisaUpdatePhone(this, ${item.num}); return false; }"  </td>
            <td >${pushOptions|| item.push}</td>
            <td>${certiType}</td>
            <td><span class="kje-btn-link_1" style="cursor:pointer;" onclick="kjDaeriCompany('${item['2012DaeriCompanyNum'] || ''}')">${item.company}</span></td>
            <td>${insuranceCompany}</td>
            <td>${item.dongbuCerti || ''}</td>
            <td>${resultRate.personRate2 || ''} : ${resultRate.personRateName || ''}</td>
            <td>${item.InputDay ? item.InputDay.split(' ')[0] : ''}</td>
            <td>${item.OutPutDay || ''}</td>
            <td>${item.sago === '1' ? '무' : '유'}</td>
            <td>${item.p_buho || ''}</td>
        </tr>`;
    }
    
    // 테이블에 HTML 적용
    gisaList.innerHTML = tableHTML;
    
    // 전체 검색 결과 개수 표시
    const currentSituation = document.getElementById('currentSituation');
    if (currentSituation) {
        currentSituation.innerHTML = `<span>전체 ${gisaData.length}건</span>`;
    }
}

// 페이지네이션을 생성하는 함수
function createGisaPagination(data) {
    // 페이지네이션 컨테이너 찾기
    const paginationContainer = document.getElementById('gisa-pagination');
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

    const gisaData = data.data;
    
    // 전체 페이지 수 계산
    const totalPages = Math.ceil(gisaData.length / itemsPerPage2);

    // 한 페이지만 있는 경우 페이지네이션 표시하지 않음
    if (totalPages <= 1) {
        return;
    }

    // 이전 버튼
    const prevButton = document.createElement('a');
    prevButton.href = 'javascript:void(0);';
    prevButton.className = 'pagination-btn prev-btn' + (currentPage2 === 1 ? ' disabled' : '');
    prevButton.innerHTML = '&laquo;';
    if (currentPage2 > 1) {
        prevButton.onclick = function() {
            goToGisaPage(currentPage2 - 1);
        };
    }
    paginationContainer.appendChild(prevButton);

    // 페이지 번호 버튼들
    const maxPageButtons = 5;
    let startPage = Math.max(1, currentPage2 - Math.floor(maxPageButtons / 2));
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
            goToGisaPage(1);
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
        pageBtn.className = 'pagination-btn' + (i === currentPage2 ? ' active' : '');
        pageBtn.textContent = i;
        
        if (i !== currentPage2) {
            pageBtn.onclick = function() {
                goToGisaPage(i);
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
            goToGisaPage(totalPages);
        };
        paginationContainer.appendChild(lastPageBtn);
    }

    // 다음 버튼
    const nextButton = document.createElement('a');
    nextButton.href = 'javascript:void(0);';
    nextButton.className = 'pagination-btn next-btn' + (currentPage2 === totalPages ? ' disabled' : '');
    nextButton.innerHTML = '&raquo;';
    if (currentPage2 < totalPages) {
        nextButton.onclick = function() {
            goToGisaPage(currentPage2 + 1);
        };
    }
    paginationContainer.appendChild(nextButton);
}

// 페이지 이동 함수
function goToGisaPage(page) {
    if (page < 1 || !gisaData || !gisaData.data) return;
    
    const totalPages = Math.ceil(gisaData.data.length / itemsPerPage2);
    if (page > totalPages) return;
    
    currentPage2 = page;
    renderGisaTable(gisaData);
    createGisaPagination(gisaData);
    
    // 스크롤을 테이블 상단으로 이동
    const tableContainer = document.querySelector('.kje-data-table-container');
    if (tableContainer) {
        tableContainer.scrollIntoView({ behavior: 'smooth' });
    }
}

// 스타일 추가 함수
function addGisaCustomStyles() {
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

//성명 
function validateGisaUpdateName(inputElement, num) {
   
    const Name = inputElement.value.trim();
    
    console.log('num', num);

    // FormData 객체 생성
    const formData = new FormData();
    formData.append('Name', Name);
    formData.append('num',num);
    
    // 서버로 핸드폰 업데이트 요청 전송
    fetch('https://kjstation.kr/api/kjDaeri/updateGisaName.php', {
        method: 'POST',
        body: formData
    })
    .then(response => response.json())
    .then(data => {
        if (data.success) {
            alert( '사용자 성명이 성공적으로 업데이트되었습니다.');
        } else {
            alert('사용자 성명 업데이트에 실패했습니다: ' + (data.message || '알 수 없는 오류'));
        }
    })
    .catch(error => {
        console.error('사용자 성명 업데이트 중 오류가 발생했습니다:', error);
        alert( '사용자 성명 업데이트 중 오류가 발생했습니다. 다시 시도해주세요.');
        
    });
}
//전화번호 업데이트 
function validateGisaUpdatePhone(inputElement, num) {
   
    const phone = inputElement.value.trim();
    
    console.log('num', num);

    // FormData 객체 생성
    const formData = new FormData();
    formData.append('phone', phone);
    formData.append('num',num);
    
    // 서버로 핸드폰 업데이트 요청 전송
    fetch('https://kjstation.kr/api/kjDaeri/updateGisaPhone.php', {
        method: 'POST',
        body: formData
    })
    .then(response => response.json())
    .then(data => {
        if (data.success) {
            alert( '사용자 핸드폰 번호가 성공적으로 업데이트되었습니다.');
        } else {
            alert('사용자 핸드폰 번호 업데이트에 실패했습니다: ' + (data.message || '알 수 없는 오류'));
        }
    })
    .catch(error => {
        console.error('사용자 핸드폰 번호 업데이트 오류가 발생했습니다:', error);
        alert( '사용자 핸드폰 번호 업데이트 중 오류가 발생했습니다. 다시 시도해주세요.');
        
    });
}

async function updateHaeji(element, DariMemberNum, dNum, cNum, policyNum, InsuranceCompany) {
    const push = element.value;
    const endorse_day = document.getElementById("gijunDay").value;
    
    // 로딩 표시를 위한 요소 추가
    const loadingIndicator = document.createElement('span');
    loadingIndicator.className = 'loading-indicator';
    loadingIndicator.textContent = ' 처리 중...';
    element.parentNode.appendChild(loadingIndicator);
    


	if (push!=2) {
        alert('해지일 때만 처리 가능합니다..');
        cleanupUI();
        return false;
    }
    // 입력값 검증
    if (!endorse_day) {
        alert('기준일을 입력해주세요.');
        cleanupUI();
        return false;
    }
    
    if (!confirm(`정말로 해지 처리를 진행하시겠습니까?`)) {
        cleanupUI();
        return;
    }
    
    try {
        // FormData 객체 생성
        const formData = new FormData();
        formData.append('push', push);
        formData.append('DariMemberNum', DariMemberNum);
        formData.append('dNum', dNum);
        formData.append('cNum', cNum);
        formData.append('policyNum', policyNum);
        formData.append('endorseDay', endorse_day);
        formData.append('InsuranceCompany', InsuranceCompany);
        formData.append('userName', SessionManager.getUserInfo().name);
        
        const response = await fetch(`https://kjstation.kr/api/kjDaeri/updateHaeji.php`, {
            method: "POST",
            body: formData
        });
        
        const result = await response.json();
        if (result.success) {
            alert("해지 처리가 완료되었습니다.");
            // 필요한 경우 목록 새로고침 함수 호출
            // refreshList();
        } else {
            alert(result.error || "해지 처리 중 오류가 발생했습니다.");
        }
    } catch (error) {
        if (error.name === 'AbortError') {
            alert('요청 시간이 초과되었습니다. 나중에 다시 시도해주세요.');
        } else {
            console.error("해지 처리 실패:", error);
            alert(`해지 처리 실패: ${error.message}`);
        }
    } finally {
        cleanupUI(); // 항상 UI 정리
    }
    
    // UI 정리 함수
    function cleanupUI() {
        if (loadingIndicator && loadingIndicator.parentNode) {
            loadingIndicator.parentNode.removeChild(loadingIndicator);
        }
    }
}
//대리운전회사 기사 할인할증,
function calculatePersonRate(rateValue) {

  
  switch(rateValue) {
    case 1:
      personRate2 = 1;
      personRateName = '기본';
      break;
    case 2:
      personRate2 = 0.9;
      personRateName = '할인';
      break;
    case 3:
      personRate2 = 0.925;
      personRateName = '3년간 사고건수 0건 1년간 사고건수 0건 무사고 1년 이상';
      break;
    case 4:
      personRate2 = 0.898;
      personRateName = '3년간 사고건수 0건 1년간 사고건수 0건 무사고 2년 이상';
      break;
    case 5:
      personRate2 = 0.889;
      personRateName = '3년간 사고건수 0건 1년간 사고건수 0건 무사고 3년 이상';
      break;
    case 6:
      personRate2 = 1.074;
      personRateName = '3년간 사고건수 1건 1년간 사고건수 0건';
      break;
    case 7:
      personRate2 = 1.085;
      personRateName = '3년간 사고건수 1건 1년간 사고건수 1건';
      break;
    case 8:
      personRate2 = 1.242;
      personRateName = '3년간 사고건수 2건 1년간 사고건수 0건';
      break;
    case 9:
      personRate2 = 1.253;
      personRateName = '3년간 사고건수 2건 1년간 사고건수 1건';
      break;
    case 10:
      personRate2 = 1.314;
      personRateName = '3년간 사고건수 2건 1년간 사고건수 2건';
      break;
    case 11:
      personRate2 = 1.428;
      personRateName = '3년간 사고건수 3건이상 1년간 사고건수 0건';
      break;
    case 12:
      personRate2 = 1.435;
      personRateName = '3년간 사고건수 3건이상 1년간 사고건수 1건';
      break;
    case 13:
      personRate2 = 1.447;
      personRateName = '3년간 사고건수 3건이상 1년간 사고건수 2건';
      break;
    case 14:
      personRate2 = 1.459;
      personRateName = '3년간 사고건수 3건이상 1년간 사고건수 3건이상';
      break;
    default:
      personRate2 = 1;
      personRateName = '기본';
      break;
  }
  
  return {
    personRate2: personRate2,
    personRateName: personRateName
  };
}