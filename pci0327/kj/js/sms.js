// 페이지네이션 관련 변수
let currentPageSms = 1;      // 현재 페이지
let perPageSms = 10;         // 페이지당 항목 수 (서버에서 기본값 10으로 설정됨)
let totalPagesSms = 0;       // 총 페이지 수
let totalRecordsSms = 0;     // 총 레코드 수
let currentDataSms = [];     // 현재 페이지의 데이터

// 함수가 호출되었는지 추적하는 플래그 변수
let isSmsCalled = false;

// SMS 데이터 로드 함수 - 서버 사이드 페이징 활용
function sms(page = 1) {
  // 플래그 설정
  isSmsCalled = true;
 
  // 검색 조건을 가져오기
  const searchName = document.getElementById('sms-search-name').value.trim() || '';
  
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
  formData.append('sort', '3');         // 회사 번호로 검색 (기본값)
  formData.append('page', page);        // 페이지 번호
  
  if (searchName) {
    formData.append('name', searchName); // 검색 이름이 있는 경우
  }
  
  fetch('./api/customer/sms_data.php', {
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
    updateSmsUI(data);
    
    // 검색 후 검색창 초기화 (첫 페이지 요청 시에만)
    if (page === 1 && searchName) {
      document.getElementById('sms-search-name').value = '';
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
    isSmsCalled = false;
  });
}

// SMS 테이블 UI 업데이트 함수
function updateSmsUI(data) {
  console.log('SMS 데이터:', data);
  
  // 검색 결과 카운트 업데이트
  const searchCountElem = document.getElementById('driver-search-count');
  const totalCountElem = document.getElementById('sms-total-count');
  
  if (!data || !data.success || !data.data || data.data.length === 0) {
    document.getElementById('sms_list_01').innerHTML = '<tr><td colspan="5" class="text-center">데이터가 없습니다.</td></tr>';
    document.getElementById('sms_cards_container').innerHTML = '<div class="alert alert-info mt-3 mb-3">데이터가 없습니다.</div>';
    
    if (searchCountElem) searchCountElem.style.display = 'none';
    hideSmsPagenation();
    return;
  }
  
  // 서버에서 받은 페이지 정보 저장
  currentPageSms = data.page || 1;
  totalPagesSms = data.totalPages || 1;
  totalRecordsSms = data.totalRecords || 0;
  perPageSms = data.perPage || 10;
  
  // 현재 페이지 데이터 저장
  currentDataSms = data.data;
  
  // 검색 결과 개수 표시
  if (searchCountElem && totalCountElem) {
    totalCountElem.textContent = totalRecordsSms;
    searchCountElem.style.display = 'block';
  }
  
  // 테이블 및 카드 뷰 업데이트
  updateSmsTableView();
  updateSmsMobileCardView();
  
  // 페이지네이션 업데이트
  updateSmsPagination();
  updateSmsMobilePagination();
}

// 날짜 포맷 함수
function formatSmsDate(dateStr) {
  if (!dateStr || dateStr.length < 14) return '-';
  
  const year = dateStr.substring(0, 4);
  const month = dateStr.substring(4, 6);
  const day = dateStr.substring(6, 8);
  const hour = dateStr.substring(8, 10);
  const minute = dateStr.substring(10, 12);
  const second = dateStr.substring(12, 14);
  
  return `${year}-${month}-${day} ${hour}:${minute}:${second}`;
}

// 전화번호 포맷 함수
function formatPhoneNumber(phone1, phone2, phone3) {
  if (!phone1 || !phone2 || !phone3) return '-';
  return `${phone1}-${phone2}-${phone3}`;
}

// 테이블 뷰 업데이트
function updateSmsTableView() {
  const tableBody = document.getElementById('sms_list_01');
  tableBody.innerHTML = '';
  
  // 서버에서 이미 페이징된 결과를 받아오므로 현재 데이터를 모두 표시
  const startIndex = (currentPageSms - 1) * perPageSms; // 전체 데이터 기준 시작 인덱스 계산
  
  // 데이터 행 생성
  currentDataSms.forEach((item, index) => {
    const row = document.createElement('tr');
    
    // 행 내용 설정 (재전송 버튼 제거)
    row.innerHTML = `
      <td>${startIndex + index + 1}</td>
      <td>${formatPhoneNumber(item.Rphone1, item.Rphone2, item.Rphone3)}</td>
      <td>${item.Msg || '-'}</td>
      <td>${formatSmsDate(item.LastTime)}</td>
    `;
    
    tableBody.appendChild(row);
  });
}

// 재전송 관련 함수들 제거

// 모바일 카드 뷰 업데이트
function updateSmsMobileCardView() {
  const cardsContainer = document.getElementById('sms_cards_container');
  cardsContainer.innerHTML = '';
  
  // 서버에서 이미 페이징된 결과를 받아오므로 현재 데이터를 모두 표시
  const startIndex = (currentPageSms - 1) * perPageSms; // 전체 데이터 기준 시작 인덱스 계산
  
  // 데이터 카드 생성
  currentDataSms.forEach((item, index) => {
    const card = document.createElement('div');
    card.className = 'sms-card';
    
    // 카드 내용 설정 (재전송 버튼 제거)
    card.innerHTML = `
      <div class="card mb-3">
        <div class="card-body">
          <h6>SMS 메시지 <small>(${startIndex + index + 1}번)</small></h6>
          
          <div class="info-row">
            <div class="info-label">수신번호</div>
            <div class="info-value">${formatPhoneNumber(item.Rphone1, item.Rphone2, item.Rphone3)}</div>
          </div>
          
          <div class="info-row">
            <div class="info-label">메시지</div>
            <div class="info-value">${item.Msg || '-'}</div>
          </div>
          
          <div class="info-row">
            <div class="info-label">발송시간</div>
            <div class="info-value">${formatSmsDate(item.LastTime)}</div>
          </div>
        </div>
      </div>
    `;
    
    cardsContainer.appendChild(card);
  });
}

// 페이지네이션 업데이트
function updateSmsPagination() {
  const paginationElement = document.getElementById('pagination-sms');
  if (!paginationElement) {
    createSmsPaginationElement();
    return;
  }
  
  if (totalPagesSms <= 1) {
    paginationElement.style.display = 'none';
    return;
  }
  
  paginationElement.style.display = 'flex';
  paginationElement.innerHTML = '';
  
  // 페이지네이션 컴포넌트 생성
  createSmsPaginationComponent(paginationElement, false);
}

// 모바일 페이지네이션 업데이트
function updateSmsMobilePagination() {
  const mobilePaginationElement = document.getElementById('mobile-pagination-sms');
  if (!mobilePaginationElement) {
    createSmsMobilePaginationElement();
    return;
  }
  
  if (totalPagesSms <= 1) {
    mobilePaginationElement.style.display = 'none';
    return;
  }
  
  mobilePaginationElement.style.display = 'flex';
  mobilePaginationElement.innerHTML = '';
  
  // 페이지네이션 컴포넌트 생성 (모바일용)
  createSmsPaginationComponent(mobilePaginationElement, true);
}

// 페이지네이션 컴포넌트 생성 (재사용 가능 함수)
function createSmsPaginationComponent(paginationElement, isMobile) {
  // 이전 버튼
  const prevButton = document.createElement('li');
  prevButton.className = `page-item ${currentPageSms <= 1 ? 'disabled' : ''}`;
  prevButton.innerHTML = '<a class="page-link" href="#" aria-label="Previous"><span aria-hidden="true">&laquo;</span></a>';
  prevButton.addEventListener('click', (e) => {
    e.preventDefault();
    if (currentPageSms > 1) {
      sms(currentPageSms - 1);
    }
  });
  paginationElement.appendChild(prevButton);
  
  // 페이지 번호 버튼
  const maxPageButtons = isMobile ? 3 : 5; // 모바일에서는 버튼 수 줄임
  const halfMaxButtons = Math.floor(maxPageButtons / 2);
  let startPage = Math.max(1, currentPageSms - halfMaxButtons);
  let endPage = Math.min(totalPagesSms, startPage + maxPageButtons - 1);
  
  // 시작 페이지 조정
  if (endPage - startPage + 1 < maxPageButtons) {
    startPage = Math.max(1, endPage - maxPageButtons + 1);
  }
  
  for (let i = startPage; i <= endPage; i++) {
    const pageButton = document.createElement('li');
    pageButton.className = `page-item ${i === currentPageSms ? 'active' : ''}`;
    pageButton.innerHTML = `<a class="page-link" href="#">${i}</a>`;
    pageButton.addEventListener('click', (e) => {
      e.preventDefault();
      if (i !== currentPageSms) {
        sms(i);
      }
    });
    paginationElement.appendChild(pageButton);
  }
  
  // 다음 버튼
  const nextButton = document.createElement('li');
  nextButton.className = `page-item ${currentPageSms >= totalPagesSms ? 'disabled' : ''}`;
  nextButton.innerHTML = '<a class="page-link" href="#" aria-label="Next"><span aria-hidden="true">&raquo;</span></a>';
  nextButton.addEventListener('click', (e) => {
    e.preventDefault();
    if (currentPageSms < totalPagesSms) {
      sms(currentPageSms + 1);
    }
  });
  paginationElement.appendChild(nextButton);
}

// 페이지네이션 요소 생성
function createSmsPaginationElement() {
  const paginationContainer = document.getElementById('pagination-container-sms');
  if (!paginationContainer) return;
  
  // 페이지네이션 요소 생성
  const paginationElement = document.createElement('ul');
  paginationElement.id = 'pagination-sms';
  paginationElement.className = 'pagination';
  paginationContainer.appendChild(paginationElement);
  
  // 페이지네이션 업데이트
  updateSmsPagination();
}

// 모바일 페이지네이션 요소 생성
function createSmsMobilePaginationElement() {
  const mobilePaginationContainer = document.getElementById('mobile-pagination-container-sms');
  if (!mobilePaginationContainer) return;
  
  // 페이지네이션 요소 생성
  const mobilePaginationElement = document.createElement('ul');
  mobilePaginationElement.id = 'mobile-pagination-sms';
  mobilePaginationElement.className = 'pagination pagination-sm';
  mobilePaginationContainer.appendChild(mobilePaginationElement);
  
  // 페이지네이션 업데이트
  updateSmsMobilePagination();
}

// 페이지네이션 숨기기
function hideSmsPagenation() {
  const paginationContainer = document.getElementById('pagination-container-sms');
  if (paginationContainer) {
    paginationContainer.style.display = 'none';
  }
  
  const mobilePaginationContainer = document.getElementById('mobile-pagination-container-sms');
  if (mobilePaginationContainer) {
    mobilePaginationContainer.style.display = 'none';
  }
}

// 검색 폼 제출 이벤트 리스너
document.addEventListener('DOMContentLoaded', () => {
  // 폼 제출 이벤트 처리
  const searchForm = document.querySelector('#sms-search-form');
  if (searchForm) {
    searchForm.addEventListener('submit', function(e) {
      e.preventDefault();
      sms(1); // 검색 시 항상 1페이지부터 시작
    });
  }
  
  // 초기 데이터 로드
  // sms(1); // 페이지 로드 시 자동 호출하지 않음
});