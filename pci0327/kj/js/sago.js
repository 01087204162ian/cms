// 페이지네이션 관련 변수
let currentPageSagoSago = 1;
const rowsPerPageSago = 20; // 한 페이지에 표시할 행 수
let totalPagesSago = 0;
let totalRecordsSago = 0;
let currentDataSago = [];

// 함수가 호출되었는지 추적하는 플래그 변수
let isloadAccidentDataCalled = false;

// loadAccidentData() 함수 수정 (sago.js 파일에 적용)
function loadAccidentData(page = 1) {
  // 플래그 설정
  isloadAccidentDataCalled = true;
 
  // 검색 조건을 가져오기
  const searchName = document.getElementById('driver-search-name').value.trim() || '';
  
  // 로딩 인디케이터 표시
  showLoading();
  
  // cNum 쿠키 가져오기
  const cNum = getCookie('cNum');
  
  if (!cNum) {
    hideLoading();
    console.error('cNum 값이 없습니다.');
    return;
  }
  
  // 서버에 데이터 요청 (FormData 사용)
  const formData = new FormData();
  formData.append('cNum', cNum);        // 회사 번호
  formData.append('page', page);        // 페이지 번호
  
  if (searchName) {
    formData.append('name', searchName); // 검색 이름이 있는 경우
  }
  
  fetch('./api/customer/sago_data.php', {
    method: 'POST',
    body: formData
  })
  .then(response => {
    if (!response.ok) {
      throw new Error('서버 응답 오류: ' + response.status);
    }
    return response.json();
  })
  .then(data => {
    // 데이터 성공적으로 받아온 경우 UI 업데이트
    updateSagoUI(data);
    
    // 검색 후 검색창 초기화 (첫 페이지 요청 시에만)
    if (page === 1 && searchName) {
      document.getElementById('driver-search-name').value = '';
    }
    
    hideLoading();
  })
  .catch(error => {
    console.error('데이터 로드 중 오류 발생:', error);
    showErrorMessage('데이터를 불러오는 중 오류가 발생했습니다.');
    hideLoading();
  })
  .finally(() => {
    // 실행 완료 후 상태 변경
    isloadAccidentDataCalled = false;
  });
}

// 사고 테이블 UI 업데이트 함수
function updateSagoUI(data) {
  console.log('사고 데이터:', data);
  
  // 검색 결과 카운트 업데이트
  const searchCountElem = document.getElementById('sago-search-count');
  const totalCountElem = document.getElementById('sago-total-count');
  
  if (!data || !data.success || !data.data || data.data.length === 0) {
    document.getElementById('sago_list_01').innerHTML = '<tr><td colspan="7" class="text-center">데이터가 없습니다.</td></tr>';
    document.getElementById('sago_cards_container').innerHTML = '<div class="alert alert-info mt-3 mb-3">데이터가 없습니다.</div>';
    
    if (searchCountElem) searchCountElem.style.display = 'none';
    hideSagoPagination();
    return;
  }
  
  // 서버에서 받은 페이지 정보 저장
  currentPageSagoSago = data.page || 1;
  totalPagesSago = data.totalPages || 1;
  totalRecordsSago = data.totalRecords || 0;
  
  // 현재 페이지 데이터 저장
  currentDataSago = data.data;
  
  // 검색 결과 개수 표시
  if (searchCountElem && totalCountElem) {
    totalCountElem.textContent = totalRecordsSago;
    searchCountElem.style.display = 'block';
  }
  
  // 테이블 및 카드 뷰 업데이트
  updateSagoTableView();
  updateSagoMobileCardView();
  
  // 페이지네이션 업데이트
  updateSagoPagination();
  updateSagoMobilePagination();
}

// 날짜 포맷 함수
function formatSagoDate(dateStr, timeStr) {
  if (!dateStr) return '-';
  if (!timeStr) timeStr = '00:00:00';
  
  return `${dateStr} ${timeStr}`;
}

// 테이블 뷰 업데이트
// 테이블 뷰 업데이트
function updateSagoTableView() {
  const tableBody = document.getElementById('sago_list_01');
  tableBody.innerHTML = '';
  
  // 서버에서 이미 페이징된 결과를 받아오므로 현재 데이터를 모두 표시
  const startIndex = (currentPageSagoSago - 1) * rowsPerPageSago; // 전체 데이터 기준 시작 인덱스 계산
  
  // 데이터 행 생성
  currentDataSago.forEach((item, index) => {
    const row = document.createElement('tr');
    
    // 행 내용 설정 - 제공된 테이블 헤더 구조와 필드에 맞게 조정
    row.innerHTML = `
      <td>${startIndex + index + 1}</td>
      <td>${item.customer_name || '-'}</td>
      <td>${item.customer_contact || '-'}</td>
      <td>${item.customer_email || '-'}</td>
      <td>${item.usageDate || '-'}</td>
      <td>${item.start_location || '-'}</td>
      <td>${item.destination || '-'}</td>
      <td>${item.claimDetails || '-'}</td>
      <td>${item.claimRequest || '-'}</td>
      <td><span class="badge ${item.claimUrgency === '긴급' ? 'bg-danger' : 'bg-primary'}">${item.claimUrgency || '일반'}</span></td>
      <td>
        <button type="button" class="btn btn-sm btn-outline-primary view-details" data-accident-id="${item.accident_id}">
          상세보기
        </button>
      </td>
    `;
    
    tableBody.appendChild(row);
  });
  
  // 상세보기 버튼에 이벤트 리스너 추가
  addViewDetailsEventListeners();
}

// 상세보기 버튼에 이벤트 리스너 추가
function addViewDetailsEventListeners() {
  const viewDetailsButtons = document.querySelectorAll('.view-details');
  viewDetailsButtons.forEach(button => {
    button.addEventListener('click', function() {
      const accidentId = this.getAttribute('data-accident-id');
      viewAccidentDetails(accidentId);
    });
  });
}

// 사고 상세 정보 보기 함수
function viewAccidentDetails(accidentId) {
  // 해당 ID의 사고 데이터 찾기
  const accidentData = currentDataSago.find(item => item.accident_id == accidentId);
  
  if (!accidentData) {
    showErrorMessage('사고 상세 정보를 찾을 수 없습니다.');
    return;
  }
  
  // 모달에 데이터 채우기
  const modalTitle = document.getElementById('accidentModalLabel');
  if (modalTitle) modalTitle.textContent = `사고 상세 정보 (#${accidentId})`;
  
  // 모달 내용 채우기
  const modalBody = document.querySelector('#accidentDetailsModal .modal-body');
  if (!modalBody) return;
  
  modalBody.innerHTML = `
    <div class="row mb-3">
      <div class="col-md-6">
        <p><strong>고객명:</strong> ${accidentData.customer_name || '-'}</p>
        <p><strong>연락처:</strong> ${accidentData.customer_contact || '-'}</p>
        <p><strong>이메일:</strong> ${accidentData.customer_email || '-'}</p>
        <p><strong>이용일:</strong> ${accidentData.usageDate || '-'}</p>
      </div>
      <div class="col-md-6">
        <p><strong>기사명:</strong> ${accidentData.gisa_name || '-'}</p>
        <p><strong>기사 연락처:</strong> ${accidentData.gisa_contact || '-'}</p>
        <p><strong>접수번호:</strong> ${accidentData.receipt_number || '-'}</p>
        <p><strong>처리자:</strong> ${accidentData.handler || '-'}</p>
      </div>
    </div>
    
    <div class="row mb-3">
      <div class="col-12">
        <p><strong>출발지:</strong> ${accidentData.start_location || '-'}</p>
        <p><strong>도착지:</strong> ${accidentData.destination || '-'}</p>
        <p><strong>사고 장소:</strong> ${accidentData.accident_location || '-'}</p>
        <p><strong>사고 일시:</strong> ${formatSagoDate(accidentData.accident_date, accidentData.accident_time)}</p>
      </div>
    </div>
    
    <div class="row">
      <div class="col-md-6">
        <p><strong>접수 유형:</strong> ${accidentData.claimType || '-'}</p>
        <p><strong>긴급도:</strong> <span class="badge ${accidentData.claimUrgency === '긴급' ? 'bg-danger' : 'bg-primary'}">${accidentData.claimUrgency || '일반'}</span></p>
      </div>
      <div class="col-md-6">
        <p><strong>접수자:</strong> ${accidentData.receiver || '-'}</p>
        <p><strong>접수 시간:</strong> ${accidentData.received_datetime || '-'}</p>
      </div>
    </div>
    
    <div class="row mt-3">
      <div class="col-12">
        <div class="form-group">
          <label><strong>불편사항:</strong></label>
          <div class="p-2 border rounded bg-light">${accidentData.claimDetails || '-'}</div>
        </div>
      </div>
    </div>
    
    <div class="row mt-3">
      <div class="col-12">
        <div class="form-group">
          <label><strong>요청사항:</strong></label>
          <div class="p-2 border rounded bg-light">${accidentData.claimRequest || '-'}</div>
        </div>
      </div>
    </div>
    
    <div class="row mt-3">
      <div class="col-12">
        <div class="form-group">
          <label><strong>메모:</strong></label>
          <div class="p-2 border rounded bg-light">${accidentData.notes || '-'}</div>
        </div>
      </div>
    </div>
  `;
  
  // 모달 표시
  const modal = new bootstrap.Modal(document.getElementById('accidentDetailsModal'));
  modal.show();
}

// 모바일 카드 뷰 업데이트
function updateSagoMobileCardView() {
  const cardsContainer = document.getElementById('sago_cards_container');
  cardsContainer.innerHTML = '';
  
  // 서버에서 이미 페이징된 결과를 받아오므로 현재 데이터를 모두 표시
  const startIndex = (currentPageSagoSago - 1) * rowsPerPageSago; // 전체 데이터 기준 시작 인덱스 계산
  
  // 데이터 카드 생성
  currentDataSago.forEach((item, index) => {
    const card = document.createElement('div');
    card.className = 'sago-card';
    
    // 카드 내용 설정 - 제공된 필드에 맞게 조정
    card.innerHTML = `
      <div class="card mb-3">
        <div class="card-body">
          <h6>사고 정보 <small>(${startIndex + index + 1}번)</small> 
            <span class="badge float-end ${item.claimUrgency === '긴급' ? 'bg-danger' : 'bg-primary'}">${item.claimUrgency || '일반'}</span>
          </h6>
          
          <div class="info-row">
            <div class="info-label">고객명</div>
            <div class="info-value">${item.customer_name || '-'}</div>
          </div>
          
          <div class="info-row">
            <div class="info-label">연락처</div>
            <div class="info-value">${item.customer_contact || '-'}</div>
          </div>
          
          <div class="info-row">
            <div class="info-label">이메일</div>
            <div class="info-value">${item.customer_email || '-'}</div>
          </div>
          
          <div class="info-row">
            <div class="info-label">이용일</div>
            <div class="info-value">${item.usageDate || '-'}</div>
          </div>
          
          <div class="info-row">
            <div class="info-label">경로</div>
            <div class="info-value">${item.start_location || '-'} → ${item.destination || '-'}</div>
          </div>
          
          <div class="info-row">
            <div class="info-label">상세내용</div>
            <div class="info-value text-wrap">${item.claimDetails || '-'}</div>
          </div>
          
          <div class="info-row">
            <div class="info-label">요청사항</div>
            <div class="info-value text-wrap">${item.claimRequest || '-'}</div>
          </div>
          
          <button type="button" class="btn btn-sm btn-outline-primary w-100 mt-2 view-details" data-accident-id="${item.accident_id}">
            상세보기
          </button>
        </div>
      </div>
    `;
    
    cardsContainer.appendChild(card);
  });
  
  // 상세보기 버튼에 이벤트 리스너 추가
  addViewDetailsEventListeners();
}

// 페이지네이션 업데이트
function updateSagoPagination() {
  const paginationElement = document.getElementById('pagination-sago');
  if (!paginationElement) {
    createSagoPaginationElement();
    return;
  }
  
  if (totalPagesSago <= 1) {
    paginationElement.style.display = 'none';
    return;
  }
  
  paginationElement.style.display = 'flex';
  paginationElement.innerHTML = '';
  
  // 페이지네이션 컴포넌트 생성
  createSagoPaginationComponent(paginationElement, false);
}

// 모바일 페이지네이션 업데이트
function updateSagoMobilePagination() {
  const mobilePaginationElement = document.getElementById('mobile-pagination-sago');
  if (!mobilePaginationElement) {
    createSagoMobilePaginationElement();
    return;
  }
  
  if (totalPagesSago <= 1) {
    mobilePaginationElement.style.display = 'none';
    return;
  }
  
  mobilePaginationElement.style.display = 'flex';
  mobilePaginationElement.innerHTML = '';
  
  // 페이지네이션 컴포넌트 생성 (모바일용)
  createSagoPaginationComponent(mobilePaginationElement, true);
}

// 페이지네이션 컴포넌트 생성 (재사용 가능 함수)
function createSagoPaginationComponent(paginationElement, isMobile) {
  // 이전 버튼
  const prevButton = document.createElement('li');
  prevButton.className = `page-item ${currentPageSagoSago <= 1 ? 'disabled' : ''}`;
  prevButton.innerHTML = '<a class="page-link" href="#" aria-label="Previous"><span aria-hidden="true">&laquo;</span></a>';
  prevButton.addEventListener('click', (e) => {
    e.preventDefault();
    if (currentPageSagoSago > 1) {
      loadAccidentData(currentPageSagoSago - 1);
    }
  });
  paginationElement.appendChild(prevButton);
  
  // 페이지 번호 버튼
  const maxPageButtons = isMobile ? 3 : 5; // 모바일에서는 버튼 수 줄임
  const halfMaxButtons = Math.floor(maxPageButtons / 2);
  let startPage = Math.max(1, currentPageSagoSago - halfMaxButtons);
  let endPage = Math.min(totalPagesSago, startPage + maxPageButtons - 1);
  
  // 시작 페이지 조정
  if (endPage - startPage + 1 < maxPageButtons) {
    startPage = Math.max(1, endPage - maxPageButtons + 1);
  }
  
  for (let i = startPage; i <= endPage; i++) {
    const pageButton = document.createElement('li');
    pageButton.className = `page-item ${i === currentPageSagoSago ? 'active' : ''}`;
    pageButton.innerHTML = `<a class="page-link" href="#">${i}</a>`;
    pageButton.addEventListener('click', (e) => {
      e.preventDefault();
      if (i !== currentPageSagoSago) {
        loadAccidentData(i);
      }
    });
    paginationElement.appendChild(pageButton);
  }
  
  // 다음 버튼
  const nextButton = document.createElement('li');
  nextButton.className = `page-item ${currentPageSagoSago >= totalPagesSago ? 'disabled' : ''}`;
  nextButton.innerHTML = '<a class="page-link" href="#" aria-label="Next"><span aria-hidden="true">&raquo;</span></a>';
  nextButton.addEventListener('click', (e) => {
    e.preventDefault();
    if (currentPageSagoSago < totalPagesSago) {
      loadAccidentData(currentPageSagoSago + 1);
    }
  });
  paginationElement.appendChild(nextButton);
}

// 페이지네이션 요소 생성
function createSagoPaginationElement() {
  const paginationContainer = document.getElementById('pagination-container-sago');
  if (!paginationContainer) return;
  
  // 페이지네이션 요소 생성
  const paginationElement = document.createElement('ul');
  paginationElement.id = 'pagination-sago';
  paginationElement.className = 'pagination';
  paginationContainer.appendChild(paginationElement);
  
  // 페이지네이션 업데이트
  updateSagoPagination();
}

// 모바일 페이지네이션 요소 생성
function createSagoMobilePaginationElement() {
  const mobilePaginationContainer = document.getElementById('mobile-pagination-container-sago');
  if (!mobilePaginationContainer) return;
  
  // 페이지네이션 요소 생성
  const mobilePaginationElement = document.createElement('ul');
  mobilePaginationElement.id = 'mobile-pagination-sago';
  mobilePaginationElement.className = 'pagination pagination-sm';
  mobilePaginationContainer.appendChild(mobilePaginationElement);
  
  // 페이지네이션 업데이트
  updateSagoMobilePagination();
}

// 페이지네이션 숨기기
function hideSagoPagination() {
  const paginationContainer = document.getElementById('pagination-container-sago');
  if (paginationContainer) {
    paginationContainer.style.display = 'none';
  }
  
  const mobilePaginationContainer = document.getElementById('mobile-pagination-container-sago');
  if (mobilePaginationContainer) {
    mobilePaginationContainer.style.display = 'none';
  }
}


//사고 접수 

function sagoRegister() { //증권번호,//보험회사 kb,현대// 보험회사 코드 3,4,...
    // 모달 요소 가져오기
    const modal = document.getElementById("subscription-modal");
    const modalContent = document.querySelector(".subscriptionModal-content");
    
    // 모달 중앙 배치 (화면 크기에 따라 자동 조정)
    function centerModal2() {
        const windowHeight = window.innerHeight;
        const modalHeight = modalContent.offsetHeight;
        const topMargin = Math.max(20, (windowHeight - modalHeight) / 2);
        modalContent.style.marginTop = topMargin + "px";
    }

    // 모달 표시
    modal.style.display = "block";
    
    // 테이블 템플릿 생성 (Bootstrap 스타일 적용)
    let eTable = `
        <table class="kjTable kjTable-compact table table-bordered kjTable-mobile-stack">
            <thead class="bg-light">
                <tr> 
                    <th colspan="2" class="text-center align-middle">배서기준일</th>
                    <th colspan="2"><span id="endorseDay"></span></td>
                </tr>
                <tr>
                    <th width="5%">순번</th>
                    <th width="20%">성명</th>
                    <th width="38%">주민번호</th>
                    <th width="37%">핸드폰번호</th>
                </tr>
            </thead>
            <tbody id="kj_sagoList">
                <!-- 여기에 데이터 행이 추가될 것입니다 -->
            </tbody>
        </table>`;

    document.getElementById('m_subscription').innerHTML = eTable;

    // 모달이 DOM에 완전히 추가된 후 중앙 정렬
    setTimeout(centerModal2, 10);
    
    // 창 크기 조정 시 모달 중앙 배치 유지
    window.addEventListener('resize', centerModal2);
    
    // 닫기 버튼 이벤트 리스너
    const closeBtn = document.querySelector('.close-subscriptionModal');
    if (closeBtn) {
        closeBtn.addEventListener('click', function() {
            modal.style.display = "none";
            window.removeEventListener('resize', centerModal2);
        });
    }
    
    // 모달 바깥 클릭 시 닫기
    window.addEventListener('click', function(event) {
        if (event.target === modal) {
            modal.style.display = "none";
            window.removeEventListener('resize', centerModal2);
        }
    });
	 const cNum = getCookie('cNum');
    // 데이터 로드 함수 호출
    dCompanySearch(modal); // 대리운전회사명 찾기 
}
async function dCompanySearch(modal) {
	const dNum = getCookie('cNum');
    console.log('대리운전회사 :', dNum);
   
    try {
        

        // API 호출
        const response = await fetch(`./api/kjDaeri/get_DaeriCompany_details2.php?dNum=${dNum}`);
        const data = await response.json();
        
        if (!data.success) {
            throw new Error(data.message || '데이터를 불러오는데 실패했습니다.');
        }
        
       

      
        
        console.log('데이터 로드 성공:', data);
        
        // 증권번호 정보 표시
        const companyInfoElement = document.getElementById("subscription_daeriCompany");
        if (companyInfoElement) {
            companyInfoElement.innerHTML = `
                ${data.company} 
                
            `;
        } else {
            console.warn("⚠️ 'subscription_daeriCompany' 요소를 찾을 수 없습니다.");
        }
        
      sagoRegister();
        
        
       
        
        
        
    } catch (error) {
        console.error("❌ 데이터 로드 실패:", error);
        toast('데이터를 불러오는데 실패했습니다: ' + error.message, 'danger');
    }
}


// 사고 접수 함수 수정
function sagoRegister() {
    // 모달 요소 가져오기
    const modal = document.getElementById("subscription-modal");
    const modalContent = document.querySelector(".subscriptionModal-content");
    
    // 모달 중앙 배치 (화면 크기에 따라 자동 조정)
    function centerModal2() {
        const windowHeight = window.innerHeight;
        const modalHeight = modalContent.offsetHeight;
        const topMargin = Math.max(20, (windowHeight - modalHeight) / 2);
        modalContent.style.marginTop = topMargin + "px";
    }

    // 모달 표시
    modal.style.display = "block";
    
    // 현재 날짜와 시간 가져오기
    const now = new Date();
    const formattedDate = now.toISOString().slice(0, 10); // YYYY-MM-DD
    const formattedTime = now.toTimeString().slice(0, 8); // HH:MM:SS
    
    // 테이블 템플릿 생성 (Bootstrap 스타일 적용)
    let eTable = `
        <style>
            /* 폼 요소 크기 조정 */
            .form-floating > input,
            .form-floating > .form-control {
                height: calc(3rem + 2px) !important;  /* 높이 증가 */
                padding: 1rem 0.75rem 0.5rem 0.75rem !important; /* 상단 패딩 증가 */
                font-size: 0.875rem !important;
            }
            
            .form-floating > textarea.form-control {
                height: 100px !important;
                padding-top: 1.5rem !important;
            }
            
            .form-floating > label {
                padding: 0.75rem 0.75rem !important;
                font-size: 0.875rem !important;
                transform-origin: 0 0;
                height: auto;
                transform: scale(0.85) translateY(-0.5rem) translateX(0.15rem);
            }
            
            /* 포커스되지 않았을 때 라벨 위치 조정 */
            .form-floating>.form-control:not(:placeholder-shown)~label, 
            .form-floating>.form-control:focus~label {
                opacity: 0.65;
                transform: scale(0.85) translateY(-0.5rem) translateX(0.15rem);
            }
            
            /* 포커스되지 않았을 때의 라벨 위치 */
            .form-floating>.form-control-plaintext~label, 
            .form-floating>.form-control:not(:focus)~label {
                color: rgba(0,0,0,.6);
            }
            
            /* 헤더 폰트 크기 조정 */
            .card-title {
                font-size: 1.25rem !important;
            }
            
            h5 {
                font-size: 1rem !important;
                margin-bottom: 0.5rem !important;
            }
            
            /* 버튼 크기 조정 */
            .btn-lg {
                padding: 0.5rem 1rem !important;
                font-size: 1rem !important;
            }
            
            /* 여백 조정 */
            .mb-2 {
                margin-bottom: 0.5rem !important;
            }
            
            .mb-3 {
                margin-bottom: 0.75rem !important;
            }
            
            .g-3 {
                --bs-gutter-y: 0.5rem !important;
            }
            
            /* 카드 패딩 조정 */
            .card-body {
                padding: 1rem !important;
            }
            
            .card-header {
                padding: 0.75rem 1rem !important;
            }
            
            /* 포커스 시 placeholder 숨김 */
            .form-control:focus::placeholder {
                opacity: 0;
                transition: opacity 0.2s;
            }
        </style>
        <div class="container p-3">
            <div class="card shadow border-0">
                <div class="card-header bg-primary text-white py-2">
                    <h4 class="mb-0 text-center fw-bold card-title"><i class="fas fa-car-crash me-2"></i>사고 접수 등록</h4>
                </div>
                <div class="card-body p-3">
                    <form id="accidentForm" class="needs-validation" novalidate>
                        <!-- 고객 정보 섹션 -->
                        <div class="row g-3 mb-3">
                            <div class="col-12">
                                <h5 class="border-bottom pb-2 text-primary"><i class="fas fa-user me-2"></i>고객 정보</h5>
                            </div>
                            <div class="col-md-6">
                                <div class="form-floating mb-2">
                                    <input type="text" class="form-control" id="customer_name" name="customer_name" placeholder="고객성명" required>
                                    <label for="customer_name">고객성명 <span class="text-danger">*</span></label>
                                    <div class="invalid-feedback">고객 성명을 입력해주세요.</div>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-floating mb-2">
                                    <input type="text" class="form-control" id="customer_contact" name="customer_contact" placeholder="고객연락처" required>
                                    <label for="customer_contact">고객연락처 <span class="text-danger">*</span></label>
                                    <div class="invalid-feedback">고객 연락처를 입력해주세요.</div>
                                </div>
                            </div>
                        </div>
                        
                        <!-- 사고 정보 섹션 -->
                        <div class="row g-3 mb-3">
                            <div class="col-12">
                                <h5 class="border-bottom pb-2 text-primary"><i class="fas fa-exclamation-triangle me-2"></i>사고 정보</h5>
                            </div>
                            <div class="col-md-12">
                                <div class="form-floating mb-2">
                                    <input type="text" class="form-control" id="accident_location" name="accident_location" placeholder="사고발생장소">
                                    <label for="accident_location">사고발생장소</label>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-floating mb-2">
                                    <input type="date" class="form-control" id="accident_date" name="accident_date" placeholder="사고일자" value="${formattedDate}">
                                    <label for="accident_date">사고일자</label>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-floating mb-2">
                                    <input type="time" class="form-control" id="accident_time" name="accident_time" placeholder="사고시간" value="${formattedTime}">
                                    <label for="accident_time">사고시간</label>
                                </div>
                            </div>
                            <div class="col-12">
                                <div class="form-floating mb-2">
                                    <textarea class="form-control" id="accident_details" name="accident_details" placeholder="사고내용" style="height: 100px"></textarea>
                                    <label for="accident_details">사고내용</label>
                                </div>
                            </div>
                        </div>
                        
                        <!-- 기사 정보 섹션 -->
                        <div class="row g-3 mb-3">
                            <div class="col-12">
                                <h5 class="border-bottom pb-2 text-primary"><i class="fas fa-id-card me-2"></i>기사 정보</h5>
                            </div>
                            <div class="col-md-6">
                                <div class="form-floating mb-2">
                                    <input type="text" class="form-control" id="gisa_name" name="gisa_name" placeholder="기사성명">
                                    <label for="gisa_name">기사성명</label>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-floating mb-2">
                                    <input type="text" class="form-control" id="gisa_contact" name="gisa_contact" placeholder="기사 연락처">
                                    <label for="gisa_contact">기사 연락처</label>
                                </div>
                            </div>
                        </div>
                        
                        <!-- 수주사 정보 섹션 -->
                        <div class="row g-3 mb-3">
                            <div class="col-12">
                                <h5 class="border-bottom pb-2 text-primary"><i class="fas fa-building me-2"></i>수주사 정보</h5>
                            </div>
                            <div class="col-md-6">
                                <div class="form-floating mb-2">
                                    <input type="text" class="form-control" id="contractor_name" name="contractor_name" placeholder="수주사명">
                                    <label for="contractor_name">수주사명</label>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-floating mb-2">
                                    <input type="text" class="form-control" id="contractor_contact" name="contractor_contact" placeholder="수주사연락처">
                                    <label for="contractor_contact">수주사연락처</label>
                                </div>
                            </div>
                        </div>
                        
                        <!-- 접수 정보 섹션 -->
                        <div class="row g-3 mb-3">
                            <div class="col-12">
                                <h5 class="border-bottom pb-2 text-primary"><i class="fas fa-clipboard-check me-2"></i>접수 정보</h5>
                            </div>
                            <div class="col-md-6">
                                <div class="form-floating mb-2">
                                    <input type="text" class="form-control" id="receiver" name="receiver" placeholder="접수담당자">
                                    <label for="receiver">접수담당자</label>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-floating mb-2">
                                    <input type="datetime-local" class="form-control" id="received_datetime" name="received_datetime" placeholder="접수시간" value="${formattedDate}T${formattedTime}">
                                    <label for="received_datetime">접수시간</label>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-floating mb-2">
                                    <input type="text" class="form-control" id="handler" name="handler" placeholder="처리자">
                                    <label for="handler">처리자</label>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-floating mb-2">
                                    <textarea class="form-control" id="notes" name="notes" placeholder="기타 메모" style="height: 70px"></textarea>
                                    <label for="notes">기타 메모</label>
                                </div>
                            </div>
                        </div>
                        
                        <!-- 히든 필드 -->
                        <input type="hidden" id="dNum" name="dNum" value="">
                        <input type="hidden" id="e_Cnum" name="e_Cnum" value="">
                        
                        <!-- 버튼 영역 -->
                        <div class="row mt-3">
                            <div class="col-12 d-grid">
                                <button type="submit" id="saveAccidentButton" class="btn btn-primary btn-lg py-2">
                                    <i class="fas fa-save me-2"></i>사고 접수 저장하기
                                </button>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>`;

    document.getElementById('m_subscription').innerHTML = eTable;

    // 모달이 DOM에 완전히 추가된 후 중앙 정렬
    setTimeout(centerModal2, 10);
    
    // 창 크기 조정 시 모달 중앙 배치 유지
    window.addEventListener('resize', centerModal2);
    
    // 닫기 버튼 이벤트 리스너
    const closeBtn = document.querySelector('.close-subscriptionModal');
    if (closeBtn) {
        closeBtn.addEventListener('click', function() {
            modal.style.display = "none";
            window.removeEventListener('resize', centerModal2);
        });
    }
    
    // 모달 바깥 클릭 시 닫기
    window.addEventListener('click', function(event) {
        if (event.target === modal) {
            modal.style.display = "none";
            window.removeEventListener('resize', centerModal2);
        }
    });
    
    const cNum = getCookie('cNum');
    
    // 폼 제출 이벤트 리스너
    document.getElementById('accidentForm').addEventListener('submit', function(event) {
        event.preventDefault();
        
        // 폼 검증
        if (!this.checkValidity()) {
            event.stopPropagation();
            this.classList.add('was-validated');
            return;
        }
        
        // 데이터 저장 함수 호출
        saveAccidentData();
    });
    
    // 연락처 입력 형식 설정
    setupPhoneInputs2();
    
    // 대리운전회사 정보 가져오기
    //dCompanySearch(modal);
}

// 연락처 입력 형식 설정 함수
function setupPhoneInputs2() {
    // 핸드폰 번호 입력 형식 설정
    const phoneInputs = document.querySelectorAll('#gisa_contact, #customer_contact, #contractor_contact');
    phoneInputs.forEach(input => {
        input.addEventListener('input', function(e) {
            let value = e.target.value.replace(/\D/g, ''); // 숫자만 추출
            
            // 최대 11자리로 제한
            if (value.length > 11) {
                value = value.slice(0, 11);
            }
            
            // 형식 적용 (3-4-4 또는 2-4-4)
            if (value.length > 7) {
                if (value.startsWith('02')) { // 서울 지역번호
                    e.target.value = value.slice(0, 2) + '-' + value.slice(2, 6) + '-' + value.slice(6);
                } else {
                    e.target.value = value.slice(0, 3) + '-' + value.slice(3, 7) + '-' + value.slice(7);
                }
            } else if (value.length > 3) {
                if (value.startsWith('02')) { // 서울 지역번호
                    e.target.value = value.slice(0, 2) + '-' + value.slice(2);
                } else {
                    e.target.value = value.slice(0, 3) + '-' + value.slice(3);
                }
            } else {
                e.target.value = value;
            }
        });
    });
}

// 사고 데이터 저장 함수
function saveAccidentData() {
    // 로딩 인디케이터 표시
    showLoading();
    
    // 필수 필드 검증
    const customerName = document.getElementById('customer_name').value.trim();
    const customerContact = document.getElementById('customer_contact').value.trim();
    
    if (!customerName) {
        hideLoading();
        Swal.fire({
            icon: 'warning',
            title: '입력 오류',
            text: '고객 성명은 필수 입력 항목입니다.',
            confirmButtonColor: '#3085d6'
        });
        document.getElementById('customer_name').focus();
        return;
    }
    
    if (!customerContact) {
        hideLoading();
        Swal.fire({
            icon: 'warning',
            title: '입력 오류',
            text: '고객 연락처는 필수 입력 항목입니다.',
            confirmButtonColor: '#3085d6'
        });
        document.getElementById('customer_contact').focus();
        return;
    }
    
    // 폼 데이터 가져오기
    const formData = new FormData(document.getElementById('accidentForm'));
    
    // API 요청
    fetch('./api/customer/save_accident_data.php', {
        method: 'POST',
        body: formData
    })
    .then(response => {
        if (!response.ok) {
            throw new Error('서버 응답 오류: ' + response.status);
        }
        return response.json();
    })
    .then(data => {
        if (data.success) {
            // 성공 메시지 표시
            Swal.fire({
                icon: 'success',
                title: '저장 완료',
                text: '사고 접수가 성공적으로 등록되었습니다.',
                confirmButtonColor: '#3085d6'
            });
            
            // 모달 닫기
            document.getElementById('subscription-modal').style.display = "none";
            
            // 데이터 다시 로드
            loadAccidentData();
        } else {
            // 오류 메시지 표시
            Swal.fire({
                icon: 'error',
                title: '오류 발생',
                text: data.message || '사고 접수 등록 중 오류가 발생했습니다.',
                confirmButtonColor: '#d33'
            });
        }
        
        hideLoading();
    })
    .catch(error => {
        console.error('사고 접수 등록 중 오류 발생:', error);
        Swal.fire({
            icon: 'error',
            title: '오류 발생',
            text: '사고 접수 등록 중 오류가 발생했습니다.',
            confirmButtonColor: '#d33'
        });
        hideLoading();
    });
}