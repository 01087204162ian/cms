/** kjstation.kr  개인 리스트 **/
let isFetchingPerson = false;  // fetch 중복 실행 방지 변수
let isFetchingPersonDamdanga = false;  // fetch 중복 실행 방지 변수
let fetchCallCountPerson = 0;  // API 호출 횟수 추적 변수
let currentPagePerson = 1;  // 현재 페이지
let itemsPerPagePerson = 15;  // 페이지당 표시할 항목 수
let personData = null;  // 전체 데이터 저장 변수

function kj_person(){
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
						<select id="currentPersonInwon" class="styled-select" onChange="requestPersonSearch()">
							<option value='1'>정상</option>
							<option value='2'>전체</option>
						 </select>
					</div>
		            <div class="select-container">
							<input type='text' id='personName' class='date-range-field' placeholder='개인명' 
								onkeypress="if(event.key === 'Enter') { requestPersonSearch(); return false; }" autocomplete="off" >
					</div>

					<div class="select-container">
						  <button class="sms-stats-button" onclick="requestPersonSearch()">검색</button>
					</div>
		            <div class="select-container">
						<select id="personDamdanga" class="styled-select"></select>
					</div>
					<div class="select-container">
						<select id='personDuDay'>
						</select>
						
					</div>
					
					
					
					
					<div id='currentPersonSituation'></div>
				</div>
			</div>
			<div class="kje-right-area">
						
				<button class="kje-stats-button" onclick="kjPersonRegister()">개인 신규</button> 
			</div>
		</div>
		
		<!-- 리스트 영역 -->
		<div class="kje-list-content">
			<div class="kje-data-table-container">
				<table class="kje-data-table">
					<thead>
						<tr>
							<th class="col-1">No</th>
							<th class="col-2">운전자</th>
							<th class="col-3">주민번호</th>
							<th class="col-4">핸드폰번호</th>
							<th class="col-5">증권번호</th>
							<th class="col-6">보험료</th>
							<th class="col-7">시작일</th>
							<th class="col-8">설계번호</th>
							<th class="col-9">회사</th>
							<th class="col-10">상품</th>
							<th class="col-11">상태</th>
							<th class="col-12">문자</th>
							<th class="col-13">메모</th>
							<th class="col-14">처리자</th>
							
						</tr>
					</thead>
					<tbody id="personList">
						<!-- 데이터가 여기에 동적으로 로드됨 -->
					</tbody>
				</table>
			</div>
		</div>
		<!-- 페이지네이션 -->
		<div class="policy-pagination" id="person-pagination"></div>
	</div>`;
	
	// 먼저 HTML 내용을 페이지에 삽입
	pageContent.innerHTML = fieldContents;
	
	// HTML이 삽입된 후 selectElement를 찾음
	
 
	// 페이지네이션 컨테이너가 제대로 생성되었는지 확인
	console.log("페이지네이션 컨테이너:", document.getElementById('person-pagination'));
	
	personDaeriList(1,'','','')
	
}

// 함수 수정
// 함수 수정
function personDaeriList(page = 1, damdangja = 'all', duDay = '', personName = '') {
    showKjeLoading();
    console.log('page', page, 'damdangja', damdangja, 'duDay', duDay, 'personName', personName);

    if (page) {
        currentPagePerson = page;
    }

    if (!damdangja || damdangja === '') {
        const selectElement = document.getElementById('personDamdanga');
        damdangja = selectElement ? selectElement.value : 'all';
    }

    if (!duDay || duDay === '') {
        const duDayElement = document.getElementById('personDuDay');
        duDay = duDayElement ? duDayElement.value : '';
    }

    if (!personName || personName === '') {
        const personNameElement = document.getElementById('personName');
        personName = personNameElement ? personNameElement.value : '';
    }

    const currentPersonInwonElement = document.getElementById('currentPersonInwon')?.value || '1';
    console.log(`조회 조건: 페이지=${page}, 담당자=${damdangja}, 정기납입일=${duDay}, 개인명=${personName}, 상태=${currentPersonInwonElement}`);

    if (isFetchingPerson) return;

    isFetchingPerson = true;
    fetchCallCountPerson++;
    console.log(`Fetch Call Count: ${fetchCallCountPerson}`);

    const formData = new FormData();
    formData.append('duDay_', duDay);
    formData.append('damdangja', damdangja);
    formData.append('currentPersonInwonElement', currentPersonInwonElement);
    formData.append('personName', personName);

    fetch('../kj/api/kjDaeri/personList.php', {
        method: 'POST',
        body: formData,
    })
        .then(response => {
            if (!response.ok) {
                throw new Error(`HTTP error! Status: ${response.status}`);
            }
            return response.json();
        })
        .then(data => {
            personList(data);
        })
        .catch(error => {
            console.error("Error details:", error);
            alert("데이터를 불러오는 중 오류가 발생했습니다.");
        })
        .finally(() => {
            hideKjeLoading();
            isFetchingPerson = false;
        });
}

// 행 번호 클릭 시 실행되는 함수
function onRowNumberClick(personNum) {
    console.log('선택된 개인고객 번호:', personNum);
    
    // 여기에 필요한 로직을 추가하세요
    // 예시: 상세 정보 모달 열기, 페이지 이동 등
    
    // 예시 1: 상세 정보 함수 호출
    // kjPersonDetail(personNum);
    
    // 예시 2: 확인 메시지
    alert(`개인고객 번호 ${personNum}을(를) 선택했습니다.`);
    
    // 예시 3: 다른 함수 호출
    // showPersonDetails(personNum);
}

// 수정된 personList 함수의 일부분
function personList(data) {
    console.log(data);
    
    // 전역 변수에 데이터 저장
    personData = data;
    
    // 테이블 본문 요소 가져오기
    const tableBody = document.getElementById('personList');
    
    // 데이터가 없는 경우 처리
    if (!data || !data.success || !data.data || data.data.length === 0) {
        tableBody.innerHTML = '<tr><td colspan="14" class="no-data">데이터가 없습니다.</td></tr>';
        // 페이지네이션 숨기기
        const paginationContainer = document.getElementById('person-pagination');
        if (paginationContainer) {
            paginationContainer.innerHTML = '';
        }
        return;
    }
    
    // 전체 데이터
    const allPersons = data.data;
    
    // 전체 페이지 수 계산
    const totalPages = Math.ceil(allPersons.length / itemsPerPagePerson);
    
    // 현재 페이지에 표시할 데이터 추출
    const startIndex = (currentPagePerson - 1) * itemsPerPagePerson;
    const endIndex = Math.min(startIndex + itemsPerPagePerson, allPersons.length);
    const currentPageData = allPersons.slice(startIndex, endIndex);
    
    // 테이블 내용 초기화
    tableBody.innerHTML = '';
    
    // 현재 페이지 데이터로 테이블 행 생성
    currentPageData.forEach((person, index) => {
        const rowNumber = startIndex + index + 1;
        const row = document.createElement('tr');
        
        // 빈 값 처리 함수
        const emptyCheck = (value) => value ? value : '-';
        
        // 날짜 형식 처리 함수
        const formatDate = (dateStr) => {
            if (!dateStr || dateStr === '0000-00-00') return '-';
            return dateStr;
        };
        
        // 주민번호 조합 함수
        const formatJumin = (jumin1, jumin2) => {
            if (!jumin1 || !jumin2) return '-';
            return `${jumin1}-${jumin2}`;
        };
        
        // 숫자 콤마 처리 함수
        const formatNumber = (value) => {
            if (!value || value === '0' || value === '') return '-';
            return parseInt(value).toLocaleString();
        };
        
        // 상품 타입 변환 함수
        const getProductType = (etage) => {
            switch(etage) {
                case '1': return '대리';
                case '2': return '탁송';
                case '3': return '확대';
                default: return emptyCheck(etage);
            }
        };
        
        // 상태 Select 생성 함수
        const createStatusSelect = (sangtae, num) => {
            const options = [
                { value: '1', text: '당월납입' },
                { value: '2', text: '당월미납' },
                { value: '3', text: '유예' },
                { value: '4', text: '실효' },
                { value: '5', text: '해지' },
                { value: '6', text: '승인' },
                { value: '7', text: '인증' },
                { value: '8', text: '팩스' },
                { value: '9', text: '가상' },
                { value: '10', text: '접수' },
                { value: '11', text: '거절' },
                { value: '12', text: '취소' },
                { value: '13', text: '갱신청약' }
            ];
            
            let selectHtml = `<select class="kje-status-rate" data-num="${num}" onchange="updatePersonStatus(this)">`;
            
            options.forEach(option => {
                const selected = sangtae === option.value ? 'selected' : '';
                selectHtml += `<option value="${option.value}" ${selected}>${option.text}</option>`;
            });
            
            selectHtml += '</select>';
            return selectHtml;
        };
        
        // 행 내용 설정 (행 번호에 클릭 이벤트 추가)
        row.innerHTML = `
            <td><span class="kje-btn-link_1" style="cursor:pointer;" onclick="onRowNumberClick('${person.num || ''}')">${rowNumber}</span></td>
            <td><span class="kje-btn-link_1" style="cursor:pointer;" onclick="kjPersonDetail('${person.num || ''}')">${emptyCheck(person.oun_name)}</span></td>
            <td>${formatJumin(person.oun_jumin1, person.oun_jumin2)}</td>
            <td>${emptyCheck(person.oun_phone)}</td>
            <td>${emptyCheck(person.certi_number)}</td>
            <td class="kje-preiminum">${formatNumber(person.preminum)}</td>
            <td>${formatDate(person.start)}</td>
            <td>${emptyCheck(person.design_num)}</td>
            <td>${emptyCheck(person.company)}</td>
            <td>${getProductType(person.etage)}</td>
            <td>${createStatusSelect(person.sangtae, person.num)}</td>
            <td><span class="kje-btn-link_1" style="cursor:pointer;" onclick="sendSms('${person.num || ''}')">문자</span></td>
            <td><span class="kje-btn-link_1" style="cursor:pointer;" onclick="showMemo('${person.num || ''}')">메모</span></td>
            <td>${emptyCheck(person.damdanga)}</td>
        `;
        
        tableBody.appendChild(row);
    });
    
    // 현재 상황 정보 표시
    const currentSituation = document.getElementById('currentPersonSituation');
    if (currentSituation) {
        currentSituation.innerHTML = `<div class="situation-info">총 ${allPersons.length}명의 개인 고객이 있습니다.</div>`;
    }
    
    // 페이지네이션 생성
    createPersonPagination(data);
}

// 페이지네이션 생성 함수
function createPersonPagination(data) {
    // 페이지네이션 컨테이너 찾기
    const paginationContainer = document.getElementById('person-pagination');
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

    const allPersons = data.data;
    
    // 전체 페이지 수 계산
    const totalPages = Math.ceil(allPersons.length / itemsPerPagePerson);

    // 한 페이지만 있는 경우 페이지네이션 표시하지 않음
    if (totalPages <= 1) {
        return;
    }

    // 이전 버튼
    const prevButton = document.createElement('a');
    prevButton.href = 'javascript:void(0);';
    prevButton.className = 'pagination-btn prev-btn' + (currentPagePerson === 1 ? ' disabled' : '');
    prevButton.innerHTML = '&laquo;';
    if (currentPagePerson > 1) {
        prevButton.onclick = function() {
            goToPersonPage(currentPagePerson - 1);
        };
    }
    paginationContainer.appendChild(prevButton);

    // 페이지 번호 버튼들
    const maxPageButtons = 5;
    let startPage = Math.max(1, currentPagePerson - Math.floor(maxPageButtons / 2));
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
            goToPersonPage(1);
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
        pageBtn.className = 'pagination-btn' + (i === currentPagePerson ? ' active' : '');
        pageBtn.textContent = i;
        
        if (i !== currentPagePerson) {
            pageBtn.onclick = function() {
                goToPersonPage(i);
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
            goToPersonPage(totalPages);
        };
        paginationContainer.appendChild(lastPageBtn);
    }

    // 다음 버튼
    const nextButton = document.createElement('a');
    nextButton.href = 'javascript:void(0);';
    nextButton.className = 'pagination-btn next-btn' + (currentPagePerson === totalPages ? ' disabled' : '');
    nextButton.innerHTML = '&raquo;';
    if (currentPagePerson < totalPages) {
        nextButton.onclick = function() {
            goToPersonPage(currentPagePerson + 1);
        };
    }
    paginationContainer.appendChild(nextButton);
}

// 페이지 이동 함수
function goToPersonPage(page) {
    if (page < 1 || !personData || !personData.data) return;
    
    const totalPages = Math.ceil(personData.data.length / itemsPerPagePerson);
    if (page > totalPages) return;
    
    currentPagePerson = page;
    personList(personData);
    
    // 스크롤을 테이블 상단으로 이동
    const tableContainer = document.querySelector('.kje-data-table-container');
    if (tableContainer) {
        tableContainer.scrollIntoView({ behavior: 'smooth' });
    }
}

// 상태 업데이트 함수
function updatePersonStatus(selectElement) {
    const num = selectElement.dataset.num;
    const newStatus = selectElement.value;
    const statusText = selectElement.options[selectElement.selectedIndex].text;
    
    console.log(`번호 ${num}의 상태를 ${statusText}(${newStatus})로 변경`);
    
    // 실제 서버 업데이트 요청
    // 여기에 AJAX 요청 코드를 추가하세요
    /*
    fetch('/update-person-status', {
        method: 'POST',
        headers: {
            'Content-Type': 'application/json',
        },
        body: JSON.stringify({
            num: num,
            sangtae: newStatus
        })
    })
    .then(response => response.json())
    .then(data => {
        if (data.success) {
            console.log('상태 업데이트 성공');
        } else {
            console.error('상태 업데이트 실패');
            // 실패시 원래 값으로 되돌리기
            selectElement.value = data.originalStatus;
        }
    })
    .catch(error => {
        console.error('에러:', error);
        // 에러시 원래 값으로 되돌리기 (필요한 경우)
    });
    */
}

// 검색 요청 함수
function requestPersonSearch() {
    const damdangja = document.getElementById('personDamdanga')?.value || 'all';
    const duDay = document.getElementById('personDuDay')?.value || '';
    const personName = document.getElementById('personName')?.value || '';
    
    // 첫 페이지로 리셋하고 검색 실행
    currentPagePerson = 1;
    personDaeriList(1, damdangja, duDay, personName);
}