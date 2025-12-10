// 페이지네이션 관련 변수
let smsCurrentPage = 1;
const smsRowsPerPage = 20; // 한 페이지에 표시할 행 수
let smsTotalPages = 0;
let smsCurrentData = [];



// 홈 페이지 데이터 로드 함수
// 함수가 호출되었는지 추적하는 플래그 변수
let issmsSearchCalled = false;

// smsSearch 함수 수정 (smsSearch.js 파일에 적용)
function smsSearch() {
  // 강제 초기화 로직 확인

  issmsSearchCalled=true;
 
  
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
  formData.append('cNum', cNum);
  if (searchName) {
    formData.append('name', searchName);
  }
  
  fetch('https://kjstation.kr/api/customer/driver_data.php', {
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
    updateDriverUI(data);
	document.getElementById('driver-search-name').value='';
    hideLoading();
  })
  .catch(error => {
    console.error('데이터 로드 중 오류 발생:', error);
    showErrorMessage('데이터를 불러오는 중 오류가 발생했습니다.');
    hideLoading();
  })
   .finally(() => {
      // 실행 완료 후 상태 변경
      issmsSearchCalled = false;
    });
}





// 운전자 테이블 UI 업데이트 함수
function updateDriverUI(data) {
  console.log('data', data);
  
  // 검색 결과 카운트 업데이트
  const searchCountElem = document.getElementById('driver-search-count');
  const totalCountElem = document.getElementById('driver-total-count');
  
  if (!data || !data.success || !data.data || data.data.length === 0) {
    document.getElementById('driver_list_01').innerHTML = '<tr><td colspan="9" class="text-center">데이터가 없습니다.</td></tr>';
    document.getElementById('driver_cards_container').innerHTML = '<div class="alert alert-info mt-3 mb-3">데이터가 없습니다.</div>';
    
    if (searchCountElem) searchCountElem.style.display = 'none';
    hidePagenation();
    return;
  }
  
  // 전역 데이터 저장
  smsCurrentData = data.data;
  
  // 검색 결과 개수 표시
  if (searchCountElem && totalCountElem) {
    totalCountElem.textContent = smsCurrentData.length;
    searchCountElem.style.display = 'block';
  }
  
  // 총 페이지 수 계산
  smsTotalPages = Math.ceil(smsCurrentData.length / smsRowsPerPage);
  
  // 현재 페이지 데이터 표시
  displayCurrentPageData();
  
  // 페이지네이션 업데이트
  updatePagination();
}

// 보험사 매핑 함수 - 전역으로 이동하여 모바일 카드에서도 사용 가능하게 함


// 상태 표시 함수 - 로직 재사용을 위해 분리
function getPushStatusDisplay(item, isDesktop = true) {
  let pushOptions = "";
  
  if (item.push == 4) { 
    if (item.cancel === '42') {
      pushOptions = '<span class="status-pending">해지중</span>';
    } else {
      // 테이블 버전과 모바일 버전에 대해 다른 ID 사용
      const elementId = isDesktop ? `desk-pushP-${item.num}` : `mob-pushP-${item.num}`;
      
      pushOptions = `
        <select class="kje-status-ch" data-id="${item.num}" id='${elementId}' 
              data-original-value="4"
              onchange="updateHaeji(this, ${item.num}, ${item['2012DaeriCompanyNum'] || 0}, '${item.CertiTableNum || ''}', '${item.dongbuCerti || ''}', '${item.InsuranceCompany || ''}')">                           
          <option value="4" ${item.push == 4 ? "selected" : ""}>정상</option> 
          <option value="2" ${item.push == 2 ? "selected" : ""}>해지</option> 
        </select>
      `;
    }
  } else if (item.push == 2) {
    pushOptions = '<span class="status-canceled">해지</span>';
  } else if (item.push == 3) {
    pushOptions = '<span class="status-canceled">해지</span>';
  } else if (item.push == 1) {
    if (item.cancel === '12') {
      pushOptions = '<span class="status-canceled">해지</span>';
    } else if (item.cancel === '13') {
      pushOptions = '<span class="status-canceled">청약거절</span>';
    } else {
      pushOptions = '<span class="status-pending">청약</span>';
    }
  } else {
    pushOptions = '<span class="status-canceled">미가입</span>';
  }
  
  return pushOptions;
}

// 현재 페이지 데이터 표시
function displayCurrentPageData() {
  // 테이블 업데이트
  updateTableView();
  
  // 모바일 카드 뷰 업데이트
  updateMobileCardView();
}

// 테이블 뷰 업데이트
function updateTableView() {
  const tableBody = document.getElementById('driver_list_01');
  tableBody.innerHTML = '';
  
  // 현재 페이지에 해당하는 데이터 범위 계산
  const startIndex = (smsCurrentPage - 1) * smsRowsPerPage;
  const endIndex = Math.min(startIndex + smsRowsPerPage, smsCurrentData.length);
  
  // 데이터 행 생성
  for (let i = startIndex; i < endIndex; i++) {
    const item = smsCurrentData[i];
    const row = document.createElement('tr');
    
    const certiType = certificateTypeMap[item.etag] || '-';
    const pushStatusHtml = getPushStatusDisplay(item, true); // true for desktop version
    
    // 행 내용 설정
    row.innerHTML = `
      <td>${i + 1}</td>
      <td>${item.Name || '-'}</td>
      <td>${item.JuminDecrypted || '-'}</td>
      <td>${pushStatusHtml}</td>
      <td>${certiType}</td>
      <td>${insuranceCompanyMap[item.InsuranceCompany] || '-'}</td>
      <td>${item.dongbuCerti || '-'}</td>
      <td>${item.personRateName || '-'}</td>
      <td>${item.HphoneDecrypted || '-'}</td>
    `;
    
    tableBody.appendChild(row);
  }
}

// 모바일 카드 뷰 업데이트
function updateMobileCardView() {
  const cardsContainer = document.getElementById('driver_cards_container');
  cardsContainer.innerHTML = '';
  
  // 현재 페이지에 해당하는 데이터 범위 계산
  const startIndex = (smsCurrentPage - 1) * smsRowsPerPage;
  const endIndex = Math.min(startIndex + smsRowsPerPage, smsCurrentData.length);
  
  // 데이터 카드 생성
  for (let i = startIndex; i < endIndex; i++) {
    const item = smsCurrentData[i];
    const card = document.createElement('div');
    card.className = 'driver-card';
    
    const certiType = certificateTypeMap[item.etag] || '-';
    const pushStatusHtml = getPushStatusDisplay(item, false); // false for mobile version
    
    // 카드 내용 설정
    card.innerHTML = `
      <h6>${item.Name || '-'} <small>(${i + 1}번)</small></h6>
      
      <div class="info-row">
        <div class="info-label">주민번호</div>
        <div class="info-value">${item.JuminDecrypted || '-'}</div>
      </div>
      
      <div class="info-row">
        <div class="info-label">상태</div>
        <div class="info-value">${pushStatusHtml}</div>
      </div>
      
      <div class="info-row">
        <div class="info-label">증권종류</div>
        <div class="info-value">${certiType}</div>
      </div>
      
      <div class="info-row">
        <div class="info-label">보험사</div>
        <div class="info-value">${insuranceCompanyMap[item.InsuranceCompany] || '-'}</div>
      </div>
      
      <div class="info-row">
        <div class="info-label">증권번호</div>
        <div class="info-value">${item.dongbuCerti || '-'}</div>
      </div>
      
      <div class="info-row">
        <div class="info-label">할인할증</div>
        <div class="info-value">${item.personRateName || '-'}</div>
      </div>
      
      <div class="info-row">
        <div class="info-label">핸드폰</div>
        <div class="info-value">${item.HphoneDecrypted || '-'}</div>
      </div>
    `;
    
    cardsContainer.appendChild(card);
  }
  
  // 모바일 페이지네이션 업데이트
  updateMobilePagination();
}

// 페이지네이션 업데이트
function updatePagination() {
  const paginationElement = document.getElementById('pagination');
  if (!paginationElement) {
    createPaginationElement();
    return;
  }
  
  if (smsTotalPages <= 1) {
    paginationElement.style.display = 'none';
    return;
  }
  
  paginationElement.style.display = 'flex';
  paginationElement.innerHTML = '';
  
  // 페이지네이션 컴포넌트 생성
  createPaginationComponent(paginationElement, false);
}

// 모바일 페이지네이션 업데이트
function updateMobilePagination() {
  const mobilePaginationElement = document.getElementById('mobile-pagination');
  if (!mobilePaginationElement) {
    createMobilePaginationElement();
    return;
  }
  
  if (smsTotalPages <= 1) {
    mobilePaginationElement.style.display = 'none';
    return;
  }
  
  mobilePaginationElement.style.display = 'flex';
  mobilePaginationElement.innerHTML = '';
  
  // 페이지네이션 컴포넌트 생성 (모바일용)
  createPaginationComponent(mobilePaginationElement, true);
}

// 페이지네이션 컴포넌트 생성 (재사용 가능 함수)
function createPaginationComponent(paginationElement, isMobile) {
  // 이전 버튼
  const prevButton = document.createElement('li');
  prevButton.className = `page-item ${smsCurrentPage === 1 ? 'disabled' : ''}`;
  prevButton.innerHTML = '<a class="page-link" href="#" aria-label="Previous"><span aria-hidden="true">&laquo;</span></a>';
  prevButton.addEventListener('click', (e) => {
    e.preventDefault();
    if (smsCurrentPage > 1) {
      smsCurrentPage--;
      displayCurrentPageData();
      updatePagination();
      updateMobilePagination();
    }
  });
  paginationElement.appendChild(prevButton);
  
  // 페이지 번호 버튼
  const maxPageButtons = isMobile ? 3 : 5; // 모바일에서는 버튼 수 줄임
  const halfMaxButtons = Math.floor(maxPageButtons / 2);
  let startPage = Math.max(1, smsCurrentPage - halfMaxButtons);
  let endPage = Math.min(smsTotalPages, startPage + maxPageButtons - 1);
  
  // 시작 페이지 조정
  if (endPage - startPage + 1 < maxPageButtons) {
    startPage = Math.max(1, endPage - maxPageButtons + 1);
  }
  
  for (let i = startPage; i <= endPage; i++) {
    const pageButton = document.createElement('li');
    pageButton.className = `page-item ${i === smsCurrentPage ? 'active' : ''}`;
    pageButton.innerHTML = `<a class="page-link" href="#">${i}</a>`;
    pageButton.addEventListener('click', (e) => {
      e.preventDefault();
      smsCurrentPage = i;
      displayCurrentPageData();
      updatePagination();
      updateMobilePagination();
    });
    paginationElement.appendChild(pageButton);
  }
  
  // 다음 버튼
  const nextButton = document.createElement('li');
  nextButton.className = `page-item ${smsCurrentPage === smsTotalPages ? 'disabled' : ''}`;
  nextButton.innerHTML = '<a class="page-link" href="#" aria-label="Next"><span aria-hidden="true">&raquo;</span></a>';
  nextButton.addEventListener('click', (e) => {
    e.preventDefault();
    if (smsCurrentPage < smsTotalPages) {
      smsCurrentPage++;
      displayCurrentPageData();
      updatePagination();
      updateMobilePagination();
    }
  });
  paginationElement.appendChild(nextButton);
}

// 페이지네이션 요소 생성
function createPaginationElement() {
  const paginationContainer = document.getElementById('pagination-container');
  if (!paginationContainer) return;
  
  // 페이지네이션 요소 생성
  const paginationElement = document.createElement('ul');
  paginationElement.id = 'pagination';
  paginationElement.className = 'pagination';
  paginationContainer.appendChild(paginationElement);
  
  // 페이지네이션 업데이트
  updatePagination();
}

// 모바일 페이지네이션 요소 생성
function createMobilePaginationElement() {
  const mobilePaginationContainer = document.getElementById('mobile-pagination-container');
  if (!mobilePaginationContainer) return;
  
  // 페이지네이션 요소 생성
  const mobilePaginationElement = document.createElement('ul');
  mobilePaginationElement.id = 'mobile-pagination';
  mobilePaginationElement.className = 'pagination pagination-sm';
  mobilePaginationContainer.appendChild(mobilePaginationElement);
  
  // 페이지네이션 업데이트
  updateMobilePagination();
}

// 페이지네이션 숨기기
function hidePagenation() {
  const paginationContainer = document.getElementById('pagination-container');
  if (paginationContainer) {
    paginationContainer.style.display = 'none';
  }
  
  const mobilePaginationContainer = document.getElementById('mobile-pagination-container');
  if (mobilePaginationContainer) {
    mobilePaginationContainer.style.display = 'none';
  }
}

/**
 * 해지 상태 업데이트 함수
 * @param {HTMLElement} selectElement - 상태 변경된 셀렉트 박스 요소
 * @param {number} num - 데이터 고유 번호
 * @param {number} daeriCompanyNum - 대리점 번호
 * @param {string} certiTableNum - 증권 번호
 * @param {string} dongbuCerti - 동부 증권 번호
 * @param {string} insuranceCompany - 보험사 코드
 */
function updateHaeji(selectElement, num, daeriCompanyNum, certiTableNum, dongbuCerti, insuranceCompany) {
  // 현재 선택된 값 (2: 해지, 4: 정상)
  const selectedValue = selectElement.value;
  
  // 선택된 값이 변경되지 않았으면 처리하지 않음
  if (selectElement.getAttribute('data-original-value') === selectedValue) {
    return;
  }
  
  // 사용자 확인
  let confirmMessage = '';
  if (selectedValue === '2') {
    confirmMessage = '정말로 해지 처리하시겠습니까?';
  } else if (selectedValue === '4') {
    confirmMessage = '정말로 정상 처리하시겠습니까?';
  }
  
  if (!confirm(confirmMessage)) {
    // 사용자가 취소한 경우 원래 값으로 되돌림
    selectElement.value = selectElement.getAttribute('data-original-value');
    return;
  }
  
  // 로딩 인디케이터 표시
  showLoading();
  
  // cNum 쿠키 가져오기
  const cNum = getCookie('cNum');
  
  if (!cNum) {
    hideLoading();
    alert('로그인 정보가 없습니다. 다시 로그인해주세요.');
    return;
  }
  const endorsementDate = calculateEndorsementDate();
  // 서버에 데이터 전송
  const formData = new FormData();
  formData.append('dNum', cNum);
  formData.append('DariMemberNum', num);
  formData.append('push', selectedValue);
  formData.append('cNum',certiTableNum);
  formData.append('policyNum',dongbuCerti);
  formData.append('endorseDay',endorsementDate);
  formData.append('InsuranceCompany', insuranceCompany);
  formData.append('userName',getCookie('nAme'));


  fetch('https://kjstation.kr/api/kjDaeri/updateHaeji.php', {
    method: 'POST',
    body: formData
  })
  .then(response => {
    if (!response.ok) {
      throw new Error('서버 응답 오류: ' + response.status);
    }
    return response.json();
  })
  .then(result => {
    hideLoading();
    
    if (result.success) {
      // 상태 알림 (토스트 메시지로 표시)
      showToast('상태가 성공적으로 변경되었습니다.', 'success');
      
      // 상태 변경 성공 시 원래 값 업데이트
      selectElement.setAttribute('data-original-value', selectedValue);
      
      // 데이터 다시 로드
      smsSearch();
    } else {
      showToast('상태 변경에 실패했습니다: ' + (result.message || '알 수 없는 오류'), 'error');
      
      // 실패 시 원래 값으로 되돌림
      selectElement.value = selectElement.getAttribute('data-original-value');
    }
  })
  .catch(error => {
    hideLoading();
    console.error('상태 업데이트 중 오류 발생:', error);
    showToast('상태 업데이트 중 오류가 발생했습니다.', 'error');
    
    // 오류 시 원래 값으로 되돌림
    selectElement.value = selectElement.getAttribute('data-original-value');
  });
}

// 토스트 메시지 표시 함수
function showToast(message, type = 'info') {
  // 기존 토스트 제거
  const existingToast = document.getElementById('toast-notification');
  if (existingToast) {
    existingToast.remove();
  }
  
  // 새 토스트 생성
  const toast = document.createElement('div');
  toast.id = 'toast-notification';
  toast.className = `toast-notification toast-${type}`;
  toast.textContent = message;
  
  // 바디에 추가
  document.body.appendChild(toast);
  
  // 애니메이션 적용
  setTimeout(() => {
    toast.classList.add('show');
  }, 10);
  
  // 3초 후 제거
  setTimeout(() => {
    toast.classList.remove('show');
    setTimeout(() => {
      toast.remove();
    }, 300);
  }, 3000);
}

// 에러 메시지 표시 함수
function showErrorMessage(message) {
  showToast(message, 'error');
}

// 쿠키 가져오기 함수
function getCookie(name) {
  const value = `; ${document.cookie}`;
  const parts = value.split(`; ${name}=`);
  if (parts.length === 2) return parts.pop().split(';').shift();
}

// 검색 폼 제출 이벤트 리스너
document.addEventListener('DOMContentLoaded', () => {
  // 폼 제출 이벤트 처리
  const searchForm = document.querySelector('#driver-search-form');
  if (searchForm) {
    searchForm.addEventListener('submit', function(e) {
      e.preventDefault();
      smsSearch();
    });
  }
  
  // 초기 데이터 로드
 // smsSearch();
  
  // 토스트 스타일 추가
  
});