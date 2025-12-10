/** kjstation.kr  증권별코드**/
let isFetchingHole = false;  // fetch 중복 실행 방지 변수
let fetchHollCount3 = 0;  // API 호출 횟수 추적 변수
let currentHollPage = 1;  // 현재 페이지
let itemsHollPerPage = 15;  // 페이지당 표시할 항목 수
let HoleData = [];  // 전체 데이터 저장 변수

function holeinoneList(){
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
	
	const fieldContents= `<div class="holl-list-container">
		<!-- 검색 영역 -->
		<div class="holl-list-header">
			<div class="holl-left-area">
				<div class="holl-search-area">
					<div class="select-container">
						<input type='date' id='fromDate' class='date-range-field' value='${oneYearAgoStr}' >
						<input type='date' id='toDate' class='date-range-field' value='${todayStr}' >
				
						
					</div>
					 <div class="select-container">
							<input type='text' id='cNumCompany' class='date-range-field' placeholder='업체명' 
								onkeypress="if(event.key === 'Enter') { kjHoleloadSearchTable(); return false; }" autocomplete="off" >
						   <button class="holl-stats-button" onclick="kjHoleloadSearchTable()">검색</button>
					</div>
					
					
					
				</div>
			</div>
			<div class="holl-right-area">
				<button class="holl-stats-button" onclick="holeiCompany('')">신규등록</button> 
			</div>
		</div>

		

		<!-- 리스트 영역 -->
		<div class="holl-list-content">
			<div class="holl-data-table-container">
				<table class="holl-data-table">
					<thead>
						<tr>
							<th class="col-1">No</th>
							<th class="col-business-num">업체명</th>
							<th class="col-school">담당자이름</th>
							<th class="col-students">연락처</th>
							<th class="col-insurance">이메일</th>
							<th class="col-insurance2">증권</th>
							<th class="col-insurance2">지점등록</th>
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
					<tbody id="holl-policyList">
						<!-- 데이터가 여기에 동적으로 로드됨 -->
					</tbody>
				</table>
			</div>
		</div>

		<!-- 페이지네이션 -->
		<div class="Hole-pagination" id="Hole-pagination"></div>
	</div>`
	
	pageContent.innerHTML = fieldContents;
	
	// 페이지네이션 컨테이너가 제대로 생성되었는지 확인
	console.log("페이지네이션 컨테이너:", document.querySelector('.Hole-pagination'));
	
	// 약간의 지연 후 한 번만 호출
	setTimeout(() => {
		kjHoleloadSearchTable();  // 증권번호
	}, 300);
	
	// 로딩 및 페이지네이션 기능을 위한 스타일 추가
	
}




function holeiCompany(id) {
  let new_account = '';
  let buttonText = '등록하기';
  let hollTitleText = '고객사 등록';
  
  if (id) {
    // id가 있는 경우: 서버에서 데이터를 가져와 폼을 채움
    const formData = new FormData();
    formData.append('id', id);
    
    fetch('/api/holeinone/clientCompanySearch.php', {
      method: 'POST',
      body: formData
    })
      .then(response => response.json())
      .then(response => {
        if (response.success) {
          fillFormWithData(response.data);
          // 버튼 텍스트를 '수정하기'로 변경
          document.getElementById('hinsert').innerText = '수정하기';
        } else {
          console.error('데이터 조회 실패:', response.message);
        }
      })
      .catch(error => {
        console.error('데이터를 가져오는 중 오류가 발생했습니다:', error);
      });
      
    buttonText = '수정하기';
    hollTitleText = '고객사 정보 수정';
  }
  
  new_account = `
    <div class="holl-container">
      <h2 class="holl-title">${hollTitleText}</h2>
      
      <div class="holl-group">
        <label class="holl-label">고객사명<span class="required">*</span></label>
        <div class="holl-input-container">
          <input type="text" name="name" required class="holl-control" placeholder="고객사 이름을 입력하세요" autocomplete="off" />
        </div>
      </div>
      <div class="holl-group">
        <label class="holl-label">담당자 이름</label>
        <div class="holl-input-container">
          <input type="text" name="manager_name" class="holl-control" placeholder="담당자 이름을 입력하세요" autocomplete="off" />
        </div>
      </div>
      <div class="holl-group">
        <label class="holl-label">담당자 연락처</label>
        <div class="holl-input-container">
          <input type="text" name="manager_phone" class="holl-control" placeholder="숫자만" autocomplete="off" oninput="formatPhoneNumber(this)"/>
        </div>
      </div>
      <div class="holl-group">
        <label class="holl-label">담당자 이메일</label>
        <div class="holl-input-container">
          <input type="email" name="manager_email" class="holl-control" 
                placeholder="예: name@company.com" autocomplete="off" 
                oninput="validateEmail(this)" onblur="validateEmail(this)" />
        </div>
      </div>
      <div class="holl-group">
        <label class="holl-label">메모</label>
        <div class="holl-input-container">
          <textarea name="note" rows="3" class="holl-textarea" placeholder="추가 정보를 입력하세요" autocomplete="off"></textarea>
        </div>
      </div>
      <div class="holl-submit">
        <button id='hinsert' class="btn" onClick="hollinWonRegister(${id ? `'${id}'` : 'null'})">${buttonText}</button>
      </div>
    </div>
  `;
  
  document.getElementById("m_hollinwon").innerHTML = new_account;
  document.getElementById("hollinwon-modal").style.display = "block";
  
  // id가 있는 경우에 서버에서 데이터를 가져와 폼에 채우는 함수
  function fillFormWithData(data) {
    console.log('data', data);
    document.querySelector('input[name="name"]').value = data.name || '';
    document.querySelector('input[name="manager_name"]').value = data.manager_name || '';
    document.querySelector('input[name="manager_phone"]').value = data.manager_phone || '';
    document.querySelector('input[name="manager_email"]').value = data.manager_email || '';
    document.querySelector('textarea[name="note"]').value = data.note || '';
  }
}

function validateEmail(input) {
  // 입력값에서 공백 제거
  let value = input.value.trim();
  
  // 정규식을 사용한 이메일 형식 검사
  const emailPattern = /^[a-zA-Z0-9._-]+@[a-zA-Z0-9.-]+\.[a-zA-Z]{2,}$/;
  
  if (value && !emailPattern.test(value)) {
    // 유효하지 않은 이메일 형식인 경우 시각적 피드백
    input.classList.add('invalid-input');
  } else {
    // 유효한 이메일 형식이거나 빈 값인 경우
    input.classList.remove('invalid-input');
  }
}

/**
 * 폼 데이터를 서버에 제출하는 함수
 * @param {HTMLFormElement} form - 제출할 폼 요소
 */
async function hollinWonRegister(id) {
  try {
    // 폼 요소 가져오기
    const nameInput = document.querySelector('input[name="name"]');
    const managerNameInput = document.querySelector('input[name="manager_name"]');
    const managerPhoneInput = document.querySelector('input[name="manager_phone"]');
    const managerEmailInput = document.querySelector('input[name="manager_email"]');
    const noteTextarea = document.querySelector('textarea[name="note"]');
    const submitButton = document.getElementById('hinsert'); // 버튼 요소 가져오기
    const titleElement = document.querySelector('.holl-title'); // 제목 요소 가져오기
    
    // 고객사명 필수 검증
    if (!nameInput.value.trim()) {
      showMessage('error', '고객사명은 필수 입력 항목입니다.');
      nameInput.focus();
      return;
    }
    
    // LoadingSystem을 사용하여 로딩 표시
    const loader = LoadingSystem.create('formSubmit', {
      text: id ? '고객사 정보를 수정 중입니다...' : '고객사 정보를 등록 중입니다...',
      autoShow: true
    });
    
    // FormData 객체 생성
    const formData = new FormData();
    
    // ID가 있는 경우 추가
    if (id) {
      formData.append('id', id);
    }
    
    // 필수 데이터 추가
    formData.append('name', nameInput.value.trim());
    
    // 선택 데이터 추가 (값이 있는 경우에만)
    if (managerNameInput.value.trim()) {
      formData.append('manager_name', managerNameInput.value.trim());
    }
    
    if (managerPhoneInput.value.trim()) {
      formData.append('manager_phone', managerPhoneInput.value.trim());
    }
    
    if (managerEmailInput.value.trim()) {
      formData.append('manager_email', managerEmailInput.value.trim());
    }
    
    if (noteTextarea.value.trim()) {
      formData.append('note', noteTextarea.value.trim());
    }
    
    // 항상 동일한 엔드포인트 사용
    const endpoint = '/api/holeinone/client_insert.php';
    
    // fetch API를 사용하여 서버에 데이터 전송
    const response = await fetch(endpoint, {
      method: 'POST',
      body: formData
    });
    
    // 응답 확인
    if (!response.ok) {
      throw new Error(`HTTP 오류! 상태: ${response.status}`);
    }
    
    // 로딩 메시지 업데이트
    loader.setText(id ? '수정 완료! 처리 중...' : '등록 완료! 처리 중...');
    
    // 응답 데이터 처리 (JSON 형태로 응답이 온다고 가정)
    const result = await response.json();
    
    // 로딩 숨기기
    loader.hide();
    
    // 성공 시 처리
    if (result.success) {
      // 새로운 ID 가져오기 (등록 모드인 경우) 또는 기존 ID 사용
      const clientId = id || result.data.client_id;
      
      // 타이틀 변경
      if (titleElement) {
        titleElement.textContent = '고객사 정보 수정';
      }
      
      // 버튼 텍스트 변경
      if (submitButton) {
        submitButton.textContent = '수정하기';
        // 버튼의 onClick 이벤트 핸들러 업데이트
        submitButton.onclick = function() {
          hollinWonRegister(clientId);
        };
      }
      
      // 성공 메시지 표시
      showMessage('success', id ? '고객 정보가 성공적으로 수정되었습니다.' : '고객 정보가 성공적으로 등록되었습니다. 이제 정보를 수정할 수 있습니다.');
      
      // 새 ID를 hidden input에 저장 (등록 모드였던 경우)
      if (!id) {
        let clientIdInput = document.querySelector('input[name="client_id"]');
        
        if (!clientIdInput) {
          clientIdInput = document.createElement('input');
          clientIdInput.type = 'hidden';
          clientIdInput.name = 'client_id';
          document.querySelector('.holl-container').appendChild(clientIdInput);
        }
        
        clientIdInput.value = clientId;
      }
    } else {
      // 서버에서 오류 메시지를 보낸 경우
      showMessage('error', result.message || (id ? '고객 정보 수정에 실패했습니다.' : '고객 정보 등록에 실패했습니다.'));
    }
  } catch (error) {
    // 로딩 숨기기
    LoadingSystem.hide('formSubmit');
    
    // 오류 처리
    console.error('제출 오류:', error);
    showMessage('error', '고객 정보 제출 중 오류가 발생했습니다. 다시 시도해 주세요.');
  }
}


/**
 * 메시지를 표시하는 함수
 * @param {string} type - 메시지 유형 ('success' 또는 'error')
 * @param {string} text - 표시할 메시지 텍스트
 */
function showMessage(type, text) {
  // 이미 존재하는 메시지 요소가 있으면 제거
  const existingMessage = document.querySelector('.message-container');
  if (existingMessage) {
    existingMessage.remove();
  }
  
  // 새 메시지 요소 생성
  const messageContainer = document.createElement('div');
  messageContainer.className = `message-container ${type}-message`;
  messageContainer.textContent = text;
  
  // 폼 컨테이너 앞에 메시지 삽입
  const formContainer = document.querySelector('.holl-container');
  formContainer.insertAdjacentElement('afterbegin', messageContainer);
  
  // 일정 시간 후 메시지 자동 제거 (5초)
  setTimeout(() => {
    messageContainer.remove();
  }, 5000);
}
function kjHoleloadSearchTable() {
	// 여기에 fetch 코드 넣기
	fetchHollCount3++;
	console.log(`Fetch Call Count: ${fetchHollCount3}`);
	// 이미 실행 중이면 중복 호출 방지
	if (isFetchingHole) return;
	
	// 실행 상태로 변경
	isFetchingHole = true;
	//const sj = 'Hole_';
	// 로딩 표시
	const loader = LoadingSystem.create('formSubmit', {
      text: '고객사 정보 조회 중입니다...',
      autoShow: true
    });

	const sj= document.getElementById("cNumCompany").value;
	const fromDate = document.getElementById("fromDate").value;
	const toDate = document.getElementById("toDate").value;
	
	// API 요청
	fetch(`/api/holeinone/clientSearch.php?sj=${sj}&fromDate=${fromDate}&toDate=${toDate}`)
		.then(response => {
			if (!response.ok) {
				throw new Error(`HTTP error! Status: ${response.status}`);
			}
			return response.json();
		})
		.then(data => {
			// 전체 데이터 저장
			HoleData = data;
			
			// 페이지 초기화 및 데이터 표시
			currentHollPage = 1;
			renderHoleTable(data);
			
			// 직접 DOM에 페이지네이션 생성
			createPagination(data);
		})
		.catch((error) => {
			console.error("Error details:", error);
			alert("데이터를 불러오는 중 오류가 발생했습니다.");
		})
		.finally(() => {
			// 로딩 숨김
			  loader.hide();
			
			// 실행 완료 후 상태 변경
			isFetchingHole = false;
		});
}

// 테이블 데이터 렌더링 함수
function renderHoleTable(data) {
    // 데이터가 없거나 유효하지 않은 경우 처리
    if (!data || !data.data || !Array.isArray(data.data)) {
        document.getElementById('holl-policyList').innerHTML = '<tr><td colspan="16" class="no-data">데이터가 없습니다.</td></tr>';
        return;
    }

    // 현재 페이지 계산을 위한 설정
    const itemsPerPage = 10; // 페이지당 항목 수
    const startIndex = (currentHollPage - 1) * itemsPerPage;
    const endIndex = startIndex + itemsPerPage;
    const currentPageData = data.data.slice(startIndex, endIndex);

    // 테이블 HTML 생성
    let tableHtml = '';
    
    currentPageData.forEach((item, index) => {
        tableHtml += `
           <tr>
			<td>${startIndex + index + 1}</td>
			<td><span class="kje-btn-link_1" style="cursor:pointer;" onclick="holeiCompany('${item.id || ''}')">${item.name || '-'}</span></td>
			<td>${item.manager_name || '-'}</td>
			<td>${item.manager_phone || '-'}</td>
			<td>${item.manager_email || '-'}</td>
			<td><button class="holl-btn" onclick="createCerti('${item.id}','', '${item.name}')" style="cursor:pointer;">증권발급</button></td>
			<td><button class="holl-btn" onclick="createBranch('${item.id}','', '${item.name}')" style="cursor:pointer;">지점등록</button></td>
			<td>${formatDate(item.created_at)}</td>
			<td>-</td>
			<td>-</td>
			<td>-</td>
			<td>-</td>
			<td>-</td>
			<td>-</td>
			<td>-</td>
			<td>-</td>
		</tr>
        `;
    });

    // 데이터가 없는 경우
    if (currentPageData.length === 0) {
        tableHtml = '<tr><td colspan="16" class="no-data">데이터가 없습니다.</td></tr>';
    }

    // 테이블 본문에 HTML 삽입
    document.getElementById('holl-policyList').innerHTML = tableHtml;
}

// 날짜 포맷 함수
function formatDate(dateString) {
    if (!dateString) return '-';
    
    // YYYY-MM-DD HH:MM:SS 형식의 날짜를 YYYY-MM-DD 형식으로 변환
    const date = new Date(dateString);
    if (isNaN(date.getTime())) return dateString; // 유효하지 않은 날짜인 경우 원본 반환
    
    const year = date.getFullYear();
    const month = String(date.getMonth() + 1).padStart(2, '0');
    const day = String(date.getDate()).padStart(2, '0');
    
    return `${year}-${month}-${day}`;
}

// 페이지네이션 생성 함수
function createPagination(data) {
    if (!data || !data.total) return;
    
    const itemsPerPage = 10; // 페이지당 항목 수
    const totalPages = Math.ceil(data.total / itemsPerPage);
    
    // 페이지네이션 컨테이너 CSS 추가
    const paginationStyle = `
        <style>
            .hole-pagination-container {
                display: flex;
                justify-content: center;
                margin: 20px 0;
                font-family: 'Noto Sans KR', sans-serif;
            }
            .hole-pagination {
                display: flex;
                list-style: none;
                padding: 0;
                margin: 0;
                border-radius: 4px;
                overflow: hidden;
                box-shadow: 0 2px 5px rgba(0,0,0,0.1);
            }
            .hole-pagination li {
                margin: 0;
            }
            .hole-pagination li a {
                display: flex;
                align-items: center;
                justify-content: center;
                min-width: 32px;
                height: 32px;
                padding: 0 10px;
                text-decoration: none;
                color: #666;
                background-color: #fff;
                border: 1px solid #ddd;
                border-right: none;
                transition: all 0.2s ease;
                font-size: 12px;
            }
            .hole-pagination li:last-child a {
                border-right: 1px solid #ddd;
            }
            .hole-pagination li.active a {
                background-color: #228B22;
                color: white;
                border-color: #228B22;
                font-weight: 600;
                z-index: 1;
            }
            .hole-pagination li:not(.active):not(.disabled):hover a {
                background-color: #f5f8ff;
                color: #228B22;
                z-index: 1;
            }
            .hole-pagination li.disabled a {
                color: #ccc;
                cursor: not-allowed;
                background-color: #f9f9f9;
            }
            .hole-pagination-info {
                display: flex;
                justify-content: center;
                font-size: 13px;
                color: #888;
                margin-top: 8px;
            }
            .hole-pagination-arrow {
                font-size: 18px;
                line-height: 18px;
            }
            .hole-pagination-jump {
                margin: 0 5px;
                font-size: 12px;
            }
            .hole-pagination-jump a {
                color: #228B22;
                text-decoration: none;
            }
        </style>
    `;
    
    let paginationHtml = paginationStyle + '<div class="hole-pagination-container"><ul class="hole-pagination">';
    
    // 첫 페이지 버튼
    paginationHtml += `
        <li class="${currentHollPage === 1 ? 'disabled' : ''}">
            <a href="#" onclick="changePage(1); return false;" title="첫 페이지">
                <span class="hole-pagination-arrow">«</span>
            </a>
        </li>
    `;
    
    // 이전 페이지 버튼
    paginationHtml += `
        <li class="${currentHollPage === 1 ? 'disabled' : ''}">
            <a href="#" onclick="changePage(${currentHollPage - 1}); return false;" title="이전 페이지">
                <span class="hole-pagination-arrow">‹</span>
            </a>
        </li>
    `;
    
    // 페이지 숫자 버튼
    const maxPageButtons = 5;
    let startPage = Math.max(1, currentHollPage - Math.floor(maxPageButtons / 2));
    const endPage = Math.min(totalPages, startPage + maxPageButtons - 1);
    
    // 시작 페이지 조정 (항상 5개 버튼이 보이도록)
    if (endPage - startPage + 1 < maxPageButtons) {
        startPage = Math.max(1, endPage - maxPageButtons + 1);
    }
    
    // 처음에 줄임표 표시 (많은 페이지가 있을 경우)
    if (startPage > 1) {
        paginationHtml += `
            <li class="hole-pagination-jump">
                <a href="#" onclick="changePage(1); return false;" title="첫 페이지로 이동">1</a>
            </li>
        `;
        
        if (startPage > 2) {
            paginationHtml += `
                <li class="disabled">
                    <a href="#" onclick="return false;">…</a>
                </li>
            `;
        }
    }
    
    // 페이지 번호 버튼
    for (let i = startPage; i <= endPage; i++) {
        paginationHtml += `
            <li class="${i === currentHollPage ? 'active' : ''}">
                <a href="#" onclick="changePage(${i}); return false;">${i}</a>
            </li>
        `;
    }
    
    // 마지막에 줄임표 표시 (많은 페이지가 있을 경우)
    if (endPage < totalPages) {
        if (endPage < totalPages - 1) {
            paginationHtml += `
                <li class="disabled">
                    <a href="#" onclick="return false;">…</a>
                </li>
            `;
        }
        
        paginationHtml += `
            <li class="hole-pagination-jump">
                <a href="#" onclick="changePage(${totalPages}); return false;" title="마지막 페이지로 이동">${totalPages}</a>
            </li>
        `;
    }
    
    // 다음 페이지 버튼
    paginationHtml += `
        <li class="${currentHollPage === totalPages ? 'disabled' : ''}">
            <a href="#" onclick="changePage(${currentHollPage + 1}); return false;" title="다음 페이지">
                <span class="hole-pagination-arrow">›</span>
            </a>
        </li>
    `;
    
    // 마지막 페이지 버튼
    paginationHtml += `
        <li class="${currentHollPage === totalPages ? 'disabled' : ''}">
            <a href="#" onclick="changePage(${totalPages}); return false;" title="마지막 페이지">
                <span class="hole-pagination-arrow">»</span>
            </a>
        </li>
    `;
    
    paginationHtml += '</ul></div>';
    
    // 페이지 정보 표시
    const startItem = (currentHollPage - 1) * itemsPerPage + 1;
    const endItem = Math.min(currentHollPage * itemsPerPage, data.total);
    
    paginationHtml += `
        <div class="hole-pagination-info">
            전체 ${data.total}개 중 ${startItem}-${endItem}개 표시 (${currentHollPage}/${totalPages} 페이지)
        </div>
    `;
    
    document.getElementById('Hole-pagination').innerHTML = paginationHtml;
}

// 페이지 변경 함수
function changePage(pageNum) {
    // 페이지 번호 유효성 검사
    if (pageNum < 1 || pageNum > Math.ceil(HoleData.total / 10)) return;
    
    // 현재 페이지 업데이트
    currentHollPage = pageNum;
    
    // 데이터 다시 렌더링
    renderHoleTable(HoleData);
    
    // 페이지네이션 다시 생성
    createPagination(HoleData);
    
    // 페이지 상단으로 스크롤
    window.scrollTo(0, document.querySelector('.holl-data-table').offsetTop - 50);
}

/**
 * 상품 선택 상자를 생성하는 함수
 * @param {Array} data - 상품 데이터 배열
 * @param {string} elementId - 선택 상자를 추가할 요소의 ID (기본값: 'product')
 * @param {string} selectedValue - 선택할 상품 ID 값 (기본값: '')
 * @returns {HTMLSelectElement} - 생성된 선택 상자 요소
 */
function createSelectBox(data, elementId = 'product', selectedValue = '') {
  // select 태그 생성
  const selectElement = document.createElement('select');
  selectElement.id = elementId + 'Select';
  selectElement.className = 'kje-status-ch';
  
  // 기본 옵션 추가
  const defaultOption = document.createElement('option');
  defaultOption.value = '';
  defaultOption.textContent = '상품을 선택하세요';
  defaultOption.selected = !selectedValue; // selectedValue가 있으면 기본 옵션은 선택되지 않음
  selectElement.appendChild(defaultOption);
  
  // 데이터를 기반으로 옵션 추가
  data.forEach(item => {
    const option = document.createElement('option');
    option.value = item.id;
    option.textContent = item.product;
    
    // 선택된 값과 현재 옵션 값이 일치하면 선택 상태로 설정
    // product_id는 숫자로 저장되지만 item.id는 문자열일 수 있으므로 == 연산자 사용
    if (item.id == selectedValue) {
      option.selected = true;
    }
    
    selectElement.appendChild(option);
  });
  
  // 대상 요소 확인 후 처리
  const targetElement = document.getElementById(elementId);
  if (targetElement) {
    targetElement.innerHTML = '';
    targetElement.appendChild(selectElement);
  } else {
    console.error(`ID가 "${elementId}"인 요소를 찾을 수 없습니다.`);
  }

  return selectElement; // 필요한 경우 생성된 요소 반환
}

/**
 * 모든 상품 선택 상자를 업데이트하는 함수
 * @param {Array} data - 상품 데이터 배열
 */
function updateAllProductSelects(data) {
  // 1. 새 증권 추가 행의 상품 선택 상자 업데이트
  createSelectBox(data, 'product');
  
  // 2. 기존 증권 행의 상품 선택 상자 업데이트
  document.querySelectorAll('[id^="product-"]').forEach(element => {
    const productId = element.getAttribute('data-product-id') || '';
    createSelectBox(data, element.id, productId);
  });
}

/**
 * 서버에서 상품 목록을 가져오는 함수
 * @param {Function} callback - 데이터 로드 후 실행할 콜백 함수 (옵션)
 */
function productList(callback) {
  const formData = new FormData();
  
  fetch('/api/holeinone/productList.php', {
    method: 'POST',
    body: formData
  })
    .then(response => response.json())
    .then(response => {
      if (response.success) {
        console.log(response.data);
        // 상품 데이터 캐싱 (전역 변수에 저장)
        window.productData = response.data;
        
        // 현재 보이는 모든 상품 선택 상자 업데이트
        updateAllProductSelects(response.data);
        
        // 콜백 함수가 제공된 경우 실행
        if (typeof callback === 'function') {
          callback(response.data);
        }
      } else {
        console.error('데이터 조회 실패:', response.message);
      }
    })
    .catch(error => {
      console.error('데이터를 가져오는 중 오류가 발생했습니다:', error);
    });
}

/**
 * 증권 목록을 생성하여 모달에 표시하는 함수
 * @param {Array} data - 증권 데이터 배열
 * @param {string} clientId - 고객사 ID
 * @param {string} clientName - 고객사 이름
 */
function createCertiList(data, clientId, clientName) {
  // 증권 리스트 행 생성
  let rows = '';
  
  // 기존 증권 데이터 행 추가
  if (data && data.length > 0) {
    data.forEach((item, index) => {
      const InsuraneCompanyOptions = `
        <select class="kje-status-ch" data-id="new" id="icomSelect-${item.cert_id}" onChange="icomChange(event, '${clientId}')"> 
          <option value="-1">선택</option> 
          <option value="삼성" ${item.company_id == "삼성" ? "selected" : ""}>삼성</option> 
          <option value="DB" ${item.company_id == "DB" ? "selected" : ""}>DB</option> 
          <option value="현대" ${item.company_id == "현대" ? "selected" : ""}>현대</option>
          <option value="KB" ${item.company_id == "KB" ? "selected" : ""}>KB</option>
          <option value="Mertiz" ${item.company_id == "Mertiz" ? "selected" : ""}>Mertiz</option>
          <option value="한화" ${item.company_id == "한화" ? "selected" : ""}>한화</option>
          <option value="롯데" ${item.company_id == "롯데" ? "selected" : ""}>롯데</option>
          <option value="흥국" ${item.company_id == "흥국" ? "selected" : ""}>흥국</option>
          <option value="하나" ${item.company_id == "하나" ? "selected" : ""}>하나</option>
        </select>
      `;

      rows += `
        <tr>
          <td>${index + 1}</td>
          <td>${InsuraneCompanyOptions}</td>
          <td><span id='product-${item.cert_id}' data-product-id='${item.product_id}'></span></td>
          <td><input type='date' id='hstartDay-${item.cert_id}' class='geInput' value='${item.issue_date}'></td>
          <td><input type='text' id='hpolicyNum-${item.cert_id}' class='geInput2' placeholder="증권번호 입력" autocomplete="off" value='${item.cert_number}'></td>
          <td><button class="holl-btn2 small" onClick="certiRegister('${clientId}','${item.cert_id}','${clientName}')">증권수정</button></td>
          <td><button class="holl-btn2 small" onClick="damboRegister('${clientId}','${item.cert_id}','${clientName}','${item.cert_number}')">담보</button></td>
          <td>예치보험료</td>
        </tr>
      `;
    });
  }
  
  const InsuraneCompanyOptions = `
    <select class="kje-status-ch" data-id="new" id="icomSelect" onChange="icomChange(event, '${clientId}')"> 
      <option value="-1">선택</option> 
      <option value="삼성">삼성</option> 
      <option value="DB">DB</option> 
      <option value="현대">현대</option>
      <option value="KB">KB</option>
      <option value="Mertiz">Mertiz</option>
      <option value="한화">한화</option>
      <option value="롯데">롯데</option>
      <option value="흥국">흥국</option>
      <option value="하나">하나</option>
    </select>
  `;
  
  // 새로운 증권 추가 행
  rows += `
    <tr>
      <td>${data.length > 0 ? data.length + 1 : 1}</td>
      <td>${InsuraneCompanyOptions}</td>
      <td><span id='product'></span></td>
      <td><input type='date' id='hstartDay' class='geInput'></td>
      <td><input type='text' id='hpolicyNum' class='geInput2' placeholder="증권번호 입력" autocomplete="off"></td>
      <td><button id='insurance-submit' class="holl-btn2" onClick="certiRegister('${clientId}','','${clientName}')">증권저장</button></td>
      <td>담보</td>
      <td>예치보험료</td>
    </tr>
  `;
  
  // 증권 생성 모달 HTML
  let insuranceModal = `
    <div class="holl-container2">
      <h2 class="holl-title">${clientName} 증권 리스트</h2>
      
      <table class='hjTable'>
        <thead>
          <tr>
            <th width='5%'>No</th>
            <th width='10%'>보험회사</th>
            <th width='10%'>상품</th>
            <th width='10%'>시기</th>
            <th width='15%'>증권번호</th>
            <th width='15%'>발급</th>
            <th width='15%'>담보</th>
            <th width='20%'>예치보험료</th>
          </tr>
        </thead>
        <tbody>
          ${rows}
        </tbody>
      </table>
    </div>
  `;
  
  // 모달에 컨텐츠 삽입 및 표시
  document.getElementById("m_hollinwon").innerHTML = insuranceModal;
  document.getElementById("hollinwon-modal").style.display = "block";
  
  // 상품 데이터가 있으면 사용하고, 없으면 새로 가져오기
  if (window.productData) {
    // 기본 상품 선택 상자 업데이트
    updateAllProductSelects(window.productData);
    
    // 각 기존 증권의 product_id 값을 직접 반복하며 설정
    if (data && data.length > 0) {
      data.forEach(item => {
        if (item.product_id) {
          // 여기서 직접 createSelectBox 함수를 호출하여 각 증권의 product_id 선택
          createSelectBox(window.productData, `product-${item.cert_id}`, item.product_id);
        }
      });
    }
  } else {
    // 상품 데이터가 없는 경우 가져오기
    productList();
    
    // 데이터 로드 완료 후 product_id 설정을 위한 추가 작업
    if (data && data.length > 0) {
      // productList는 비동기 함수이므로, 데이터가 로드된 후 실행될 콜백 함수 등록
      const checkDataAndApplySelection = setInterval(() => {
        if (window.productData) {
          clearInterval(checkDataAndApplySelection);
          
          // 데이터가 로드되면 각 증권의 product_id 설정
          data.forEach(item => {
            if (item.product_id) {
              createSelectBox(window.productData, `product-${item.cert_id}`, item.product_id);
            }
          });
        }
      }, 100);
    }
  }
}

/**
 * 고객사의 증권 정보를 가져와 증권 목록을 표시하는 함수
 * @param {string} clientId - 고객사 ID
 * @param {string} cert_id - 증권 ID (사용되지 않음)
 * @param {string} clientName - 고객사 이름
 */
function createCerti(clientId, cert_id, clientName) {
  if (!clientId) {
    showMessage('error', '고객사 ID가 필요합니다.');
    return;
  }
  
  // LoadingSystem을 사용하여 로딩 표시
  const loader = LoadingSystem.create('insuranceCreation', {
    text: '증권 리스트 준비 중...',
    autoShow: true
  });
  
  // id가 있는 경우: 서버에서 데이터를 가져와 폼을 채움
  const formData = new FormData();
  formData.append('id', clientId);
  
  fetch('/api/holeinone/clientCertiSearch.php', {
    method: 'POST',
    body: formData
  })
    .then(response => response.json())
    .then(response => {
      if (response.success) {
        const certData = response.data;
        
        // 1. 먼저 증권 목록 UI를 생성
        createCertiList(certData, clientId, clientName);
        
        // 2. 상품 목록을 가져오고 콜백으로 product_id 적용
        productList(function(productData) {
          // 상품 데이터를 가져온 후 각 증권의 product_id를 설정
          if (certData && certData.length > 0) {
            certData.forEach(item => {
              if (item.product_id) {
                // 각 증권 행의 상품 선택 상자 생성 및 product_id 값 선택
                createSelectBox(productData, `product-${item.cert_id}`, item.product_id);
              }
            });
          }
        });
        
        // 여기서 로딩 숨기기
        loader.hide();
      } else {
        console.error('데이터 조회 실패:', response.message);
        loader.hide();
      }
    })
    .catch(error => {
      console.error('데이터를 가져오는 중 오류가 발생했습니다:', error);
      loader.hide();
    });
}

/**
 * 보험 회사 선택 변경 이벤트 핸들러
 * @param {Event} i - 이벤트 객체
 * @param {string} m - 고객사 ID
 */
function icomChange(i, m) {
  // 함수 내용을 구현할 수 있습니다
}

/**
 * 보험 증권 정보를 서버에 제출하는 함수
 * @param {string} client_id - 고객사 ID
 * @param {string} cert_id - 증권 ID (수정할 경우에만 값이 있음)
 * @param {string} clientName - 고객사 이름
 * @returns {Promise<void>}
 */
async function certiRegister(client_id, cert_id, clientName) {
  // 변수 선언
  let loader;
  let icomSelect, productSelect, hstartDay, insuranceNumberInput;
  
  try {
    // 폼 요소 가져오기 - 신규 등록인지 수정인지에 따라 다른 요소 선택
    if (cert_id) {
      // 기존 증권 수정 시
      icomSelect = document.getElementById(`icomSelect-${cert_id}`);
      productSelect = document.getElementById(`product-${cert_id}Select`); // 수정된 ID 형식 사용
      hstartDay = document.getElementById(`hstartDay-${cert_id}`);
      insuranceNumberInput = document.getElementById(`hpolicyNum-${cert_id}`);
    } else {
      // 신규 증권 등록 시
      icomSelect = document.getElementById('icomSelect');
      productSelect = document.getElementById('productSelect'); // 기본 ID 사용
      hstartDay = document.getElementById('hstartDay');
      insuranceNumberInput = document.getElementById('hpolicyNum');
    }
    
    // 필수 필드 검증
    if (icomSelect.value === "-1") {
      showMessage2('error', '보험회사를 선택하세요.');
      icomSelect.focus();
      return;
    }
    
    // 상품 선택 검증
    if (!productSelect || productSelect.value === "") {
      showMessage2('error', '상품을 선택하세요.');
      if (productSelect) productSelect.focus();
      return;
    }
    
    if (!hstartDay.value.trim()) {
      showMessage2('error', '시작일을 선택하세요.');
      hstartDay.focus();
      return;
    }
    
    if (!insuranceNumberInput.value.trim()) {
      showMessage2('error', '증권 번호는 필수 입력 항목입니다.');
      insuranceNumberInput.focus();
      return;
    }
    
    // 로딩 표시 생성
    loader = LoadingSystem.create('formSubmit', {
      text: '증권 정보를 등록 중입니다...',
      autoShow: true
    });
    
    // FormData 객체 생성 및 데이터 추가
    const formData = new FormData();
    formData.append('icomSelect', icomSelect.value.trim());
    formData.append('productSelect', productSelect.value.trim()); // 상품 ID 값 추가
    formData.append('hstartDay', hstartDay.value.trim());
    formData.append('insurance_number', insuranceNumberInput.value.trim());
    formData.append('client_id', client_id);
    formData.append('cert_id', cert_id || ''); // cert_id가 없으면 빈 문자열 전송
    
    // 디버깅을 위한 로그 (필요 시 사용)
    console.log('전송할 데이터:', {
      보험회사: icomSelect.value.trim(),
      상품: productSelect.value.trim(),
      시작일: hstartDay.value.trim(),
      증권번호: insuranceNumberInput.value.trim(),
      고객ID: client_id,
      증권ID: cert_id || '새 증권'
    });
    
    // 서버에 데이터 전송
    const response = await fetch('/api/holeinone/certi_insert.php', {
      method: 'POST',
      body: formData
    });
    
    // 응답 확인
    if (!response.ok) {
      throw new Error(`HTTP 오류! 상태: ${response.status}`);
    }
    
    // 응답 데이터 처리
    const result = await response.json();
    
    // 로딩 메시지 업데이트 및 숨기기
    loader.setText('처리 완료!');
    loader.hide();
    
    // 결과 처리
    if (result.success) {
      showMessage2('success', cert_id ? '증권이 성공적으로 수정되었습니다.' : '증권이 성공적으로 등록되었습니다.');
      
      // 증권 목록 새로고침
      createCerti(client_id, cert_id, clientName);
    } else {
      // 서버에서 오류 메시지가 있는 경우 해당 메시지 표시, 없으면 기본 메시지
      showMessage2('error', result.message || '증권 처리에 실패했습니다.');
    }
  } catch (error) {
    // 오류 발생 시 로딩 숨기기
    if (loader && typeof loader.hide === 'function') {
      loader.hide();
    } else {
      // 로더가 제대로 생성되지 않았을 경우 대비
      LoadingSystem.hide && LoadingSystem.hide('formSubmit');
    }
    
    // 오류 로깅 및 사용자에게 메시지 표시
    console.error('증권 처리 오류:', error);
    showMessage2('error', '증권 정보 처리 중 오류가 발생했습니다. 다시 시도해 주세요.');
  }
}

/**
 * 메시지를 화면에 표시하는 함수
 * @param {string} type - 메시지 유형 ('error' 또는 'success')
 * @param {string} text - 표시할 메시지 내용
 */
function showMessage2(type, text) {
  // 이미 존재하는 메시지 요소가 있으면 제거
  const existingMessage = document.querySelector('.message-container');
  if (existingMessage) {
    existingMessage.remove();
  }
  
  // 새 메시지 요소 생성
  const messageContainer = document.createElement('div');
  messageContainer.className = `message-container ${type}-message`;
  messageContainer.textContent = text;
  
  // 스타일 추가
  messageContainer.style.padding = "10px";
  messageContainer.style.margin = "10px 0";
  messageContainer.style.borderRadius = "5px";
  
  if (type === 'error') {
    messageContainer.style.backgroundColor = "#ffeded";
    messageContainer.style.color = "#d32f2f";
    messageContainer.style.border = "1px solid #f5c6cb";
  } else if (type === 'success') {
    messageContainer.style.backgroundColor = "#e8f5e9";
    messageContainer.style.color = "#388e3c";
    messageContainer.style.border = "1px solid #c8e6c9";
  }
  
  // 폼 컨테이너 찾기 (없으면 모달 콘텐츠나 body에 추가)
  const formContainer = document.querySelector('.holl-container2');
  if (formContainer) {
    formContainer.insertAdjacentElement('afterbegin', messageContainer);
  } else {
    // 대체 위치 찾기
    const modalContent = document.querySelector('.hollinwonModal-content');
    if (modalContent) {
      modalContent.insertAdjacentElement('afterbegin', messageContainer);
    } else {
      // 최후의 방법: body에 추가
      document.body.insertAdjacentElement('afterbegin', messageContainer);
    }
  }
  
  // 메시지를 화면 상단에 고정
  messageContainer.style.position = "fixed";
  messageContainer.style.top = "20px";
  messageContainer.style.left = "50%";
  messageContainer.style.transform = "translateX(-50%)";
  messageContainer.style.zIndex = "9999";
  messageContainer.style.boxShadow = "0 2px 10px rgba(0,0,0,0.2)";
  
  // 일정 시간 후 메시지 자동 제거 (5초)
  setTimeout(() => {
    messageContainer.remove();
  }, 5000);
}

// DOM이 로드된 후 필요에 따라 초기화 작업을 수행하는 함수
document.addEventListener('DOMContentLoaded', function() {
  // 페이지 로드 시 전역 상품 데이터를 미리 가져오지 않고,
  // createCerti() 함수 내에서만 필요할 때 호출하도록 변경
  // productList() 호출 제거
});

/**
 * 담보저장하는 함수
 * @param {string} client_id - 고객사 ID
 * @param {string} cert_id - 증권 ID 
 * @param {string} clientName - 고객사명 
 * @param {string} cert_number - 증권 번호 
 * @returns {Promise<void>}
 */
async function damboRegister(client_id, cert_id,clientName,cert_number) {

	console.log("client_id","cert_id",client_id, cert_id,'clientName',clientName,'cert_number',cert_number);

   if (!cert_number) {
    showMessage('error', '증권번호가 필요합니다..');
    return;
  }
  // LoadingSystem을 사용하여 로딩 표시
  const loader = LoadingSystem.create('insuranceCreation', {
    text: '증권 리스트 준비 중...',
    autoShow: true
  });
  
  // id가 있는 경우: 서버에서 데이터를 가져와 폼을 채움
  const formData = new FormData();
  formData.append('id', client_id);
  //상품 리스트 조회 하기 

  fetch('/api/holeinone/damboSearch.php', {
    method: 'POST',
    body: formData
  })
    .then(response => response.json())
    .then(response => {
      if (response.success) {
        damboList(response.data, client_id, cert_id,clientName,cert_number);
        // 여기서 로딩 숨기기
        loader.hide();
      } else {
        console.error('데이터 조회 실패:', response.message);
        loader.hide();
      }
    })
    .catch(error => {
      console.error('데이터를 가져오는 중 오류가 발생했습니다:', error);
      loader.hide();
    });
}


function damboList(data, clientId,cert_id,clientName,cert_number) {
  // 증권 리스트 행 생성
  let rows = '';
  
  // 기존 증권 데이터 행 추가
  if (data && data.length > 0) {
    data.forEach((item, index) => {
		
		const formattedCoverage_limit = item.coverage_limit ? parseFloat(item.coverage_limit).toLocaleString("en-US") : "0";
		const formattedDeductible = item.deductible ? parseFloat(item.deductible).toLocaleString("en-US") : "0";

      rows += `
        <tr>
          <td>${index + 1}</td>
          <td><input type='text' id='collateral-${item.id}' class='memoInput'  placeholder="담보" autocomplete="off" value='${item.collateral}'></td>
          <td><input type='text' id='coverage_limit-${item.id}' class='memoInput' placeholder="보상한도" autocomplete="off" value='${formattedCoverage_limit}'></td>
          <td><input type='text' id='deductible-${item.id}' class='memoInput' placeholder="자기부담금" autocomplete="off" value='${formattedDeductible}'></td>
          <td><input type='text' id='description-${item.id}' class='memoInput' placeholder="설명" autocomplete="off" value='${item.description}'></td>
          <td><button id='dambo-submit-${item.id}' class="holl-btn2" onClick="dmboSetting('${clientId}','${cert_id}','${cert_number}','${clientName}','${item.id}',)">수정</button></td>
        </tr>
      `;
    });
  }
  
  // 새로운 담보 추가 행
  rows += `
    <tr>
      <td>${data.length > 0 ? data.length + 1 : 1}</td>
      <td><input type='text' id='collateral' class='memoInput' placeholder='담보' autocomplete='off'></td>
      <td><input type='text' id='coverage_limit' class='memoInput' placeholder='보상한도' autocomplete='off'></td>
      <td><input type='text' id='deductible' class='memoInput' placeholder='자기부담금' autocomplete='off'></td>
      <td><input type='text' id='description' class='memoInput' placeholder='설명' autocomplete='off'></td>
      <td><button id='dambo-submit' class="holl-btn2" onClick="dmboSetting('${clientId}','${cert_id}','${cert_number}','${clientName}','')">저장</button></td>
    </tr>
  `;
  
  // 담보생성 모달 HTML
  let insuranceModal = `
    <div class="holl-container2">
      <h2 class="holl-title">${clientName} ${cert_number} 증권 담보 및 보상한도 설정</h2>
      
      <table class='hjTable'>
        <thead>
          <tr>
            <th width='5%'>No</th>
            <th width='22%'>담보</th>
            <th width='13%'>보상한도</th>
            <th width='10%'>자기부담금</th>
            <th width='42%'>설명</th>
		    <th width='8%'>저장</th>
          </tr>
        </thead>
        <tbody>
          ${rows}
        </tbody>
      </table>
    </div>
  `;
  
  // 모달에 컨텐츠 삽입 및 표시
  document.getElementById("m_dambo").innerHTML = insuranceModal;
  document.getElementById("dambo-modal").style.display = "block";
}
//담보 설정//


// 담보 설정 함수
function dmboSetting(client_id,cert_id,cert_number,clientName, dambo_id) {
  console.log('cert_number', 'clientName', 'dambo_id', cert_number, clientName, dambo_id);
  
  // 값 가져오기
  let collateral, coverage_limit, deductible, description;
  
  // dambo_id가 있으면 수정, 없으면 새로 추가
  if(dambo_id) {
    collateral = document.getElementById(`collateral-${dambo_id}`).value;
    coverage_limit = document.getElementById(`coverage_limit-${dambo_id}`).value;
    deductible = document.getElementById(`deductible-${dambo_id}`).value;
    description = document.getElementById(`description-${dambo_id}`).value;
  } else {
    collateral = document.getElementById('collateral').value;
    coverage_limit = document.getElementById('coverage_limit').value;
    deductible = document.getElementById('deductible').value;
    description = document.getElementById('description').value;
  }
  
  // 필수 입력 검증
  if(!collateral || !coverage_limit) {
    alert('담보와 보상한도는 필수 입력사항입니다.');
    return;
  }
  
  // FormData 객체 생성 및 데이터 추가
  const formData = new FormData();
  formData.append('cert_number', cert_number);

  formData.append('collateral', collateral);
  formData.append('coverage_limit', coverage_limit);
  formData.append('deductible', deductible || '');
  formData.append('description', description || '');
  
  // 기존 담보 ID가 있는 경우 추가
  if(dambo_id) {
    formData.append('dambo_id', dambo_id);
  }
  
  // 로딩 표시
  const buttonId = dambo_id ? `dambo-submit-${dambo_id}` : 'dambo-submit';
  const button = document.getElementById(buttonId);
  const originalText = button.innerHTML;
  button.innerHTML = '저장 중...';
  button.disabled = true;
  
  // fetch API를 사용하여 서버에 데이터 전송
  fetch('/api/holeinone/damboSetting.php', {
    method: 'POST',
    body: formData
  })
  .then(response => {
    if (!response.ok) {
      throw new Error('서버 응답 오류');
    }
    return response.json();
  })
  .then(data => {
    if(data.success) {
      alert('담보 정보가 성공적으로 저장되었습니다.');
      // 성공 시 모달 새로고침 또는 닫기
			damboRegister(client_id, cert_id,clientName,cert_number);
    } else {
      alert('저장 실패: ' + (data.message || '알 수 없는 오류'));
    }
  })
  .catch(error => {
    console.error('Error:', error);
    alert('저장 중 오류가 발생했습니다.');
  })
  .finally(() => {
    // 버튼 상태 복원
    button.innerHTML = originalText;
    button.disabled = false;
  });
}


/**
 * 고객사 ID에 해당하는 지점 목록을 가져와 표시하는 함수
 * @param {string} clientId - 고객사 ID
 * @param {string} cert_id - 증권 ID (수정인 경우 사용)
 * @param {string} clientName - 고객사 이름
 */
function createBranch(clientId, cert_id, clientName) {
  if (!clientId) {
    showMessage('error', '고객사 ID가 필요합니다.');
    return;
  }
  
  // LoadingSystem을 사용하여 로딩 표시
  const loader = LoadingSystem.create('createBranch', {
    text: '지점 리스트 준비 중...',
    autoShow: true
  });
  
  // 서버에서 데이터를 가져오기 위한 준비
  const formData = new FormData();
  formData.append('id', clientId);
  
  fetch('/api/holeinone/branchList.php', {
    method: 'POST',
    body: formData
  })
    .then(response => response.json())
    .then(response => {
      if (response.success) {
        const branchData = response.data;
        
        // 지점 목록 UI를 생성
        branchList(branchData, clientId, clientName);
        
        // 로딩 숨기기
        loader.hide();
      } else {
        console.error('데이터 조회 실패:', response.message);
        loader.hide();
      }
    })
    .catch(error => {
      console.error('데이터를 가져오는 중 오류가 발생했습니다:', error);
      loader.hide();
    });
}

/**
 * 지점 목록을 생성하여 모달에 표시하는 함수
 * @param {Array} data - 지점 데이터 배열
 * @param {string} clientId - 고객사 ID
 * @param {string} clientName - 고객사 이름
 */
function branchList(data, clientId, clientName) {
  // 지점 리스트 행 생성
  let rows = '';
  
  // 기존 지점 데이터 행 추가
  if (data && data.length > 0) {
    data.forEach((item, index) => {
      rows += `
        <tr>
          <td>${index + 1}</td>
          <td><input type='text' id='branchName-${item.branch_id}' class='geInput2' value="${item.branchName || ''}" placeholder="지점명" autocomplete="off"></td>
          <td><input type='text' id='branchDamdangName-${item.branch_id}' class='geInput2' value="${item.branchDamdangName || ''}" placeholder="담당자" autocomplete="off"></td>
          <td><input type='text' id='branchDamdangPhone-${item.branch_id}' class='geInput2' value="${item.branchDamdangPhone || ''}" placeholder="연락처" autocomplete="off" oninput="formatPhoneNumber(this)"></td>
          <td><input type='text' id='branchDamdangEmail-${item.branch_id}' class='geInput2' value="${item.branchDamdangEmail || ''}" placeholder="이메일" autocomplete="off" oninput="validateEmail(this)" onblur="validateEmail(this)"></td>
          <td><button class="holl-btn2 small" onClick="branchRegister('${clientId}','${item.branch_id}','${clientName}')">지점수정</button></td>
          <td>${item.allocated_quota || '0'}</td>
        </tr>
      `;
    });
  }
  
  // 새로운 지점 추가 행
  rows += `
    <tr>
      <td>${data.length > 0 ? data.length + 1 : 1}</td>
      <td><input type='text' id='branchName' class='geInput2' placeholder="지점명" autocomplete="off"></td>
      <td><input type='text' id='branchDamdangName' class='geInput2' placeholder="담당자" autocomplete="off"></td>
      <td><input type='text' id='branchDamdangPhone' class='geInput2' placeholder="연락처" autocomplete="off" oninput="formatPhoneNumber(this)"></td>
      <td><input type='text' id='branchDamdangEmail' class='geInput2' placeholder="이메일" autocomplete="off" oninput="validateEmail(this)" onblur="validateEmail(this)"></td>
      <td><button id='branch-submit' class="holl-btn2" onClick="branchRegister('${clientId}','','${clientName}')">지점저장</button></td>
      <td>0</td>
    </tr>
  `;
  
  // 지점 생성 모달 HTML
  let branchModal = `
    <div class="holl-container2">
      <h2 class="holl-title">${clientName} 지점 리스트</h2>
      
      <table class='hjTable'>
        <thead>
          <tr>
            <th width='5%'>No</th>
            <th width='20%'>지점명</th>
            <th width='15%'>담당자</th>
            <th width='17%'>연락처</th>
            <th width='20%'>이메일</th>
            <th width='11%'>지점등록</th>
            <th width='12%'>수량</th>
          </tr>
        </thead>
        <tbody>
          ${rows}
        </tbody>
      </table>
    </div>
  `;
  
  // 모달에 컨텐츠 삽입 및 표시
  document.getElementById("m_hollinwon").innerHTML = branchModal;
  document.getElementById("hollinwon-modal").style.display = "block";
}

/**
 * 지점 정보 등록/수정 처리 함수
 * @param {string} client_id - 고객사 ID
 * @param {string} branch_id - 지점 ID (수정인 경우)
 * @param {string} clientName - 고객사 이름
 */
async function branchRegister(client_id, branch_id, clientName) {
  // 변수 선언
  alert('1');
  let loader;
  let branchName, branchDamdangName, branchDamdangPhone, branchDamdangEmail;
  
  try {
    // 폼 요소 가져오기 - 신규 등록인지 수정인지에 따라 다른 요소 선택
    if (branch_id) {
      // 기존 지점 수정 시
      branchName = document.getElementById(`branchName-${branch_id}`);
      branchDamdangName = document.getElementById(`branchDamdangName-${branch_id}`);
      branchDamdangPhone = document.getElementById(`branchDamdangPhone-${branch_id}`);
      branchDamdangEmail = document.getElementById(`branchDamdangEmail-${branch_id}`);
    } else {
      // 신규 지점 등록 시
      branchName = document.getElementById('branchName');
      branchDamdangName = document.getElementById('branchDamdangName');
      branchDamdangPhone = document.getElementById('branchDamdangPhone');
      branchDamdangEmail = document.getElementById('branchDamdangEmail');
    }
    
    if (!branchName.value.trim()) {
      showMessage2('error', '지점명은 필수 입력 항목입니다');
      branchName.focus();
      return;
    }
    
    // 로딩 표시 생성
    loader = LoadingSystem.create('formSubmit', {
      text: '지점 정보를 등록 중입니다...',
      autoShow: true
    });
    
    // FormData 객체 생성 및 데이터 추가
    const formData = new FormData();
    formData.append('branchName', branchName.value.trim());
    formData.append('branchDamdangName', branchDamdangName.value.trim());
    formData.append('branchDamdangPhone', branchDamdangPhone.value.trim());
    formData.append('branchDamdangEmail', branchDamdangEmail.value.trim());
    formData.append('client_id', client_id);
    formData.append('branch_id', branch_id || ''); // branch_id가 없으면 빈 문자열 전송
    
    // 디버깅을 위한 로그
    console.log('전송할 데이터:', {
      지점명: branchName.value.trim(),
      담당자: branchDamdangName.value.trim(),
      연락처: branchDamdangPhone.value.trim(),
      이메일: branchDamdangEmail.value.trim(),
      고객ID: client_id,
      지점ID: branch_id || '새 지점'
    });
    
    // 서버에 데이터 전송
    const response = await fetch('/api/holeinone/branch_insert_new.php', {
      method: 'POST',
      body: formData
    });
    
    // 응답 확인
    if (!response.ok) {
      throw new Error(`HTTP 오류! 상태: ${response.status}`);
    }
    
    // 응답 데이터 처리
    const result = await response.json();
    
    // 로딩 메시지 업데이트 및 숨기기
    loader.setText('처리 완료!');
    loader.hide();
    
    // 결과 처리
    if (result.success) {
      showMessage2('success', cert_id ? '지점이 성공적으로 수정되었습니다.' : '지점이 성공적으로 등록되었습니다.');
      
      // 지점 목록 새로고침
      createBranch(client_id, cert_id, clientName);
    } else {
      // 서버에서 오류 메시지가 있는 경우 해당 메시지 표시, 없으면 기본 메시지
      showMessage2('error', result.message || '지점 등록에 실패했습니다.');
    }
  } catch (error) {
    // 오류 발생 시 로딩 숨기기
    if (loader && typeof loader.hide === 'function') {
      loader.hide();
    } else {
      // 로더가 제대로 생성되지 않았을 경우 대비
      LoadingSystem.hide && LoadingSystem.hide('formSubmit');
    }
    
    // 오류 로깅 및 사용자에게 메시지 표시
    console.error('지점 등록 중 오류:', error);
    showMessage2('error', '지점 등록 중 오류가 발생했습니다. 다시 시도해 주세요.');
  }
}


