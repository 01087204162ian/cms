// ===== 고객 목록 관련 페이지네이션 변수 =====
let currentPageId = 1;
const rowsPerPageId = 10;                 // API 응답의 perPage와 일치
let totalPagesId = 0;
let totalRecordsId = 0;
let currentDataId = [];

// ===== 고객 목록 관련 상태 변수 =====
let isIdLoadDataCalled = false;

// ===== 고객 목록 데이터 로드 함수 =====
function loadIdData(page = 1) {
  if (isIdLoadDataCalled) return;
  isIdLoadDataCalled = true;
  
  showLoading();
  
  const cNum = getCookie('cNum');
  
  if (!cNum) {
    hideLoading();
    console.error('cNum 값이 없습니다.');
    isIdLoadDataCalled = false;
    return;
  }
  
  const formData = new FormData();
  formData.append('cNum', cNum);
  formData.append('page', page);
  
  fetch('./api/customer/readIdList.php', {  
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
    updateIdUI(data);
    hideLoading();
  })
  .catch(error => {
    console.error('데이터 로드 중 오류 발생:', error);
    showErrorMessage('데이터를 불러오는 중 오류가 발생했습니다.');
    hideLoading();
  })
  .finally(() => {
    isIdLoadDataCalled = false;
  });
}

// ===== 고객 목록 UI 업데이트 함수 =====
function updateIdUI(data) {
  console.log('고객 목록 데이터:', data);
  
  const totalCountElem = document.getElementById('id-total-count');
  
  if (!data || !data.success || !data.data || data.data.length === 0) {
    const tableBody = document.getElementById('id-list');
    const cardsContainer = document.getElementById('id-cards-container');
    
    if (tableBody) {
      tableBody.innerHTML = '<tr><td colspan="7" class="text-center">데이터가 없습니다.</td></tr>';
    }
    if (cardsContainer) {
      cardsContainer.innerHTML = '<div class="alert alert-info mt-3 mb-3">데이터가 없습니다.</div>';
    }
    
    hideIdPagination();
    return;
  }
  
  currentPageId = data.page || 1;
  totalPagesId = data.totalPages || 1;
  totalRecordsId = data.totalRecords || 0;
  
  currentDataId = data.data;
  
  if (totalCountElem) {
    totalCountElem.textContent = totalRecordsId;
  }
  
  updateIdTableView();
  updateIdMobileCardView();
  
  updateIdPagination();
  updateIdMobilePagination();
}

// ===== 고객 목록 날짜 포맷 함수 =====
function formatIdDate(dateStr) {
  if (!dateStr) return '-';
  return dateStr;
}

// ===== 고객 목록 읽기전용 텍스트 변환 함수 =====
function getIdReadOnlyText(readIs) {
  return readIs === '1' ? '읽기전용' : '읽기쓰기';
}

// ===== 고객 목록 허용여부 텍스트 변환 함수 =====
function getIdPermitText(permit) {
  return permit === '1' ? '허용' : '차단';
}

// ===== 고객 목록 허용여부 뱃지 클래스 =====
function getIdPermitBadgeClass(permit) {
  return permit === '1' ? 'bg-success' : 'bg-danger';
}

// ===== 비밀번호 업데이트 함수 (수정됨: idNum 추가) =====
function updateIdPassword(memId, newPassword, idNum) {
  if (newPassword.length < 8) {
    showErrorMessage('비밀번호는 8자리 이상이어야 합니다.');
    return;
  }
  
  const cNum = getCookie('cNum');
  if (!cNum) {
    showErrorMessage('cNum 값이 없습니다.');
    return;
  }
  
  const formData = new FormData();
  formData.append('cNum', cNum);
  formData.append('mem_id', memId);
  formData.append('password', newPassword);
  formData.append('idNum', idNum);  // num 값을 idNum으로 추가
  
  fetch('./api/kjDaeri/updatePassword.php', {
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
      showSuccessMessage('비밀번호가 성공적으로 변경되었습니다.');
    } else {
      showErrorMessage(data.message || '비밀번호 변경에 실패했습니다.');
    }
  })
  .catch(error => {
    console.error('비밀번호 업데이트 중 오류 발생:', error);
    showErrorMessage('비밀번호 변경 중 오류가 발생했습니다.');
  });
}

// ===== 허용여부 업데이트 함수 (수정됨: idNum 추가) =====
function updateIdPermit(memId, currentPermit, idNum) {
  const newPermit = currentPermit === '1' ? '2' : '1';
  
  const formData = new FormData();
  formData.append('permit', newPermit);
  formData.append('idNum', idNum);  // num 값을 idNum으로 전송
  
  fetch('./api/kjDaeri/updatePermit.php', {
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
      showSuccessMessage('허용여부가 성공적으로 변경되었습니다.');
      // 현재 페이지 다시 로드하여 변경사항 반영
      loadIdData(currentPageId);
    } else {
      showErrorMessage(data.message || '허용여부 변경에 실패했습니다.');
    }
  })
  .catch(error => {
    console.error('허용여부 업데이트 중 오류 발생:', error);
    showErrorMessage('허용여부 변경 중 오류가 발생했습니다.');
  });
}

// ===== 아이디 중복 검사 상태 변수 =====
let isIdCheckInProgress = false;
let isIdAvailable = false;
let lastCheckedId = '';

// ===== 아이디 중복 검사 함수 =====
function checkIdDuplicate(memId, callback) {
  if (!memId || memId.trim() === '') {
    callback(false, '아이디를 입력해주세요.');
    return;
  }
  
  // 영문, 숫자만 허용 (3~20자)
  const idPattern = /^[a-zA-Z0-9]{3,20}$/;
  if (!idPattern.test(memId)) {
    callback(false, '아이디는 영문, 숫자 3~20자로 입력해주세요.');
    return;
  }
  
  if (isIdCheckInProgress) {
    return;
  }
  
  isIdCheckInProgress = true;
  
  const cNum = getCookie('cNum');
  if (!cNum) {
    callback(false, 'cNum 값이 없습니다.');
    isIdCheckInProgress = false;
    return;
  }
  
  const formData = new FormData();
  formData.append('cNum', cNum);
  formData.append('userId', memId);
  
  fetch('./api/kjDaeri/checkUserId.php', {
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
    isIdCheckInProgress = false;
    if (data.success) {
      // available 값이 숫자로 오는 경우를 처리 (1: 사용불가, 2: 사용가능)
      const isAvailable = data.available === 2;
      
      if (isAvailable) {
        lastCheckedId = memId;
        isIdAvailable = true;
        callback(true, '사용 가능한 아이디입니다.');
      } else {
        isIdAvailable = false;
        callback(false, data.message || '이미 사용중인 아이디입니다.');
      }
    } else {
      isIdAvailable = false;
      callback(false, data.message || '아이디 검사 중 오류가 발생했습니다.');
    }
  })
  .catch(error => {
    console.error('아이디 중복 검사 중 오류 발생:', error);
    isIdCheckInProgress = false;
    isIdAvailable = false;
    callback(false, '아이디 검사 중 오류가 발생했습니다.');
  });
}

// ===== 아이디 입력 필드 상태 업데이트 =====
function updateIdInputStatus(inputElement, messageElement, isValid, message) {
  if (isValid) {
    inputElement.classList.remove('is-invalid');
    inputElement.classList.add('is-valid');
    messageElement.className = 'text-success small';
    messageElement.textContent = message;
  } else {
    inputElement.classList.remove('is-valid');
    inputElement.classList.add('is-invalid');
    messageElement.className = 'text-danger small';
    messageElement.textContent = message;
  }
}

// ===== 아이디 검사 상태 초기화 함수 =====
function resetIdCheckStatus() {
  isIdAvailable = false;
  lastCheckedId = '';
}

// ===== 데스크탑용 신규 ID 생성 함수 =====
function createNewId() {
  const newIdInput = document.getElementById('new-id-input');
  const newUserInput = document.getElementById('new-user-input');
  const newPhoneInput = document.getElementById('new-phone-input');
  const newPasswordInput = document.getElementById('new-password-input');
  const company = getCookie('company');
  const memId = newIdInput.value.trim();
  const user = newUserInput.value.trim();
  const phone = newPhoneInput.value.trim();
  const password = newPasswordInput.value.trim();
  
  // 유효성 검사
  if (!memId) {
    showErrorMessage('ID를 입력해주세요.');
    newIdInput.focus();
    return;
  }
  
  // 아이디 중복 검사 확인
  if (!isIdAvailable || lastCheckedId !== memId) {
    showErrorMessage('아이디 중복 검사를 완료해주세요.');
    newIdInput.focus();
    return;
  }
  
  if (!user) {
    showErrorMessage('사용자명을 입력해주세요.');
    newUserInput.focus();
    return;
  }
  
  if (!phone) {
    showErrorMessage('핸드폰번호를 입력해주세요.');
    newPhoneInput.focus();
    return;
  }
  
  if (password.length < 8) {
    showErrorMessage('비밀번호는 8자리 이상이어야 합니다.');
    newPasswordInput.focus();
    return;
  }
  
  const cNum = getCookie('cNum');
  if (!cNum) {
    showErrorMessage('cNum 값이 없습니다.');
    return;
  }
  
  const formData = new FormData();
  formData.append('dNum', cNum);  // 데스크탑 버전에서는 dNum 사용
  formData.append('mem_id', memId);
  formData.append('user', user);
  formData.append('phone', phone);
  formData.append('password', password);
  formData.append('company', company);
  
  showLoading();
  
  fetch('./api/kjDaeri/id_save.php', {  // 데스크탑 버전 API 엔드포인트
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
    hideLoading();
    
    if (data.success) {
      showSuccessMessage('새 ID가 성공적으로 생성되었습니다.');
      // 입력 필드 초기화
      clearNewIdInputs();
      // 아이디 중복 검사 상태 초기화
      resetIdCheckStatus();
      
      // 새로 생성된 데이터 반영을 위한 페이지 다시 로드
      // setTimeout을 사용하여 성공 메시지가 표시된 후 로드
      setTimeout(() => {
        loadIdData(currentPageId);
      }, 500);
      
    } else {
      showErrorMessage(data.message || 'ID 생성에 실패했습니다.');
    }
  })
  .catch(error => {
    hideLoading();
    console.error('ID 생성 중 오류 발생:', error);
    showErrorMessage('ID 생성 중 오류가 발생했습니다.');
  });
}

// ===== 모바일용 신규 ID 생성 함수 =====
function createMobileNewId() {
  const newIdInput = document.getElementById('mobile-new-id-input');
  const newUserInput = document.getElementById('mobile-new-user-input');
  const newPhoneInput = document.getElementById('mobile-new-phone-input');
  const newPasswordInput = document.getElementById('mobile-new-password-input');
  const company = getCookie('company');
  const memId = newIdInput.value.trim();
  const user = newUserInput.value.trim();
  const phone = newPhoneInput.value.trim();
  const password = newPasswordInput.value.trim();
  
  // 유효성 검사
  if (!memId) {
    showErrorMessage('ID를 입력해주세요.');
    newIdInput.focus();
    return;
  }
  
  // 아이디 중복 검사 확인
  if (!isIdAvailable || lastCheckedId !== memId) {
    showErrorMessage('아이디 중복 검사를 완료해주세요.');
    newIdInput.focus();
    return;
  }
  
  if (!user) {
    showErrorMessage('사용자명을 입력해주세요.');
    newUserInput.focus();
    return;
  }
  
  if (!phone) {
    showErrorMessage('핸드폰번호를 입력해주세요.');
    newPhoneInput.focus();
    return;
  }
  
  if (password.length < 8) {
    showErrorMessage('비밀번호는 8자리 이상이어야 합니다.');
    newPasswordInput.focus();
    return;
  }
  
  const cNum = getCookie('cNum');
  if (!cNum) {
    showErrorMessage('cNum 값이 없습니다.');
    return;
  }
  
  const formData = new FormData();
  formData.append('dNum', cNum);  // 모바일도 데스크탑과 동일하게 dNum 사용
  formData.append('mem_id', memId);
  formData.append('user', user);
  formData.append('phone', phone);
  formData.append('password', password);
  formData.append('company', company);
  
  showLoading();
  
  fetch('./api/kjDaeri/id_save.php', {  // 모바일도 데스크탑과 동일한 API 엔드포인트 사용
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
    hideLoading();
    if (data.success) {
      showSuccessMessage('새 ID가 성공적으로 생성되었습니다.');
      // 모바일 입력 필드 초기화
      clearMobileNewIdInputs();
      // 아이디 검사 상태 초기화
      resetIdCheckStatus();
      
      // 새로 생성된 데이터 반영을 위한 페이지 다시 로드
      setTimeout(() => {
        loadIdData(currentPageId);
      }, 500);
    } else {
      showErrorMessage(data.message || 'ID 생성에 실패했습니다.');
    }
  })
  .catch(error => {
    hideLoading();
    console.error('ID 생성 중 오류 발생:', error);
    showErrorMessage('ID 생성 중 오류가 발생했습니다.');
  });
}

// ===== 신규 ID 입력 필드 초기화 =====
function clearNewIdInputs() {
  document.getElementById('new-id-input').value = '';
  document.getElementById('new-user-input').value = '';
  document.getElementById('new-phone-input').value = '';
  document.getElementById('new-password-input').value = '';
  
  // 아이디 검사 상태도 초기화
  resetIdCheckStatus();
}

// ===== 모바일 신규 ID 입력 필드 초기화 =====
function clearMobileNewIdInputs() {
  document.getElementById('mobile-new-id-input').value = '';
  document.getElementById('mobile-new-user-input').value = '';
  document.getElementById('mobile-new-phone-input').value = '';
  document.getElementById('mobile-new-password-input').value = '';
  
  // 아이디 검사 상태도 초기화
  resetIdCheckStatus();
}

// ===== 고객 목록 테이블 뷰 업데이트 (수정됨: data-num 속성 추가) =====
function updateIdTableView() {
  const tableBody = document.getElementById('id-list');
  if (!tableBody) {
    console.warn('테이블 요소 id-list를 찾을 수 없습니다.');
    return;
  }
  
  tableBody.innerHTML = '';
  
  // 첫 번째 행: 신규 ID 생성 입력 행
  const newRow = document.createElement('tr');
  newRow.className = 'table-warning';
  newRow.innerHTML = `
    <td class="fw-bold">신규</td>
    <td>
      <div>
        <input type="text" 
               id="new-id-input"
               class="form-control form-control-sm" 
               placeholder="새 ID 입력 (영문,숫자 3~20자)"
               style="min-width: 120px;">
        <div id="id-status-message" class="mt-1"></div>
      </div>
    </td>
    <td>
      <input type="text" 
             id="new-user-input"
             class="form-control form-control-sm" 
             placeholder="사용자명 입력"
             style="min-width: 100px;">
    </td>
    <td>
      <input type="text" 
             id="new-phone-input"
             class="form-control form-control-sm" 
             placeholder="핸드폰번호 입력"
             style="min-width: 100px;">
    </td>
    <td>-</td>
    <td>
      <input type="text" 
             id="new-password-input"
             class="form-control form-control-sm" 
             placeholder="8자리 이상 입력"
             style="min-width: 120px;">
    </td>
    <td>
      <button type="button" 
              id="create-id-btn"
              class="btn btn-sm btn-primary">
        생성
      </button>
    </td>
  `;
  tableBody.appendChild(newRow);
  
  // 기존 데이터 행들
  const startIndex = (currentPageId - 1) * rowsPerPageId;
  
  currentDataId.forEach((item, index) => {
    const row = document.createElement('tr');
    
    row.innerHTML = `
      <td>${startIndex + index + 1}</td>
      <td>${item.mem_id || '-'}</td>
      <td>${item.user || '-'}</td>
      <td>${item.hphone || '-'}</td>
      <td>${getIdReadOnlyText(item.readIs)}</td>
      <td>
        <input type="text" 
               class="form-control form-control-sm password-input" 
               placeholder="8자리 이상 입력" 
               data-mem-id="${item.mem_id}"
               data-num="${item.num}"
               style="min-width: 120px;">
      </td>
      <td>
        <button type="button" 
                class="btn btn-sm ${item.permit === '1' ? 'btn-success' : 'btn-danger'} permit-btn" 
                data-mem-id="${item.mem_id}" 
                data-permit="${item.permit}"
                data-num="${item.num}">
          ${getIdPermitText(item.permit)}
        </button>
      </td>
    `;
    
    tableBody.appendChild(row);
  });
  
  // 신규 ID 생성 버튼 이벤트 리스너
  document.getElementById('create-id-btn').addEventListener('click', createNewId);
  
  // 아이디 입력 필드에 실시간 중복 검사 이벤트 리스너 추가
  const newIdInput = document.getElementById('new-id-input');
  const idStatusMessage = document.getElementById('id-status-message');
  
  let idCheckTimeout;
  newIdInput.addEventListener('input', function() {
    const memId = this.value.trim();
    
    // 이전 타이머 제거
    if (idCheckTimeout) {
      clearTimeout(idCheckTimeout);
    }
    
    // 아이디 검사 상태 초기화
    isIdAvailable = false;
    lastCheckedId = '';
    this.classList.remove('is-valid', 'is-invalid');
    idStatusMessage.textContent = '';
    idStatusMessage.className = '';
    
    if (memId === '') {
      return;
    }
    
    // 500ms 후에 중복 검사 실행 (디바운싱)
    idCheckTimeout = setTimeout(() => {
      checkIdDuplicate(memId, (isValid, message) => {
        updateIdInputStatus(newIdInput, idStatusMessage, isValid, message);
      });
    }, 500);
  });
  
  // 신규 ID 입력 필드들에 엔터 이벤트 리스너 추가
  const newInputs = [
    newIdInput,
    document.getElementById('new-user-input'),
    document.getElementById('new-phone-input'),
    document.getElementById('new-password-input')
  ];
  
  newInputs.forEach(input => {
    input.addEventListener('keypress', function(e) {
      if (e.key === 'Enter') {
        createNewId();
      }
    });
  });
  
  // 기존 비밀번호 입력 필드에 엔터 이벤트 리스너 추가 (수정됨: idNum 전달)
  const passwordInputs = tableBody.querySelectorAll('.password-input');
  passwordInputs.forEach(input => {
    input.addEventListener('keypress', function(e) {
      if (e.key === 'Enter') {
        const memId = this.getAttribute('data-mem-id');
        const idNum = this.getAttribute('data-num');  // num 값 가져오기
        const password = this.value.trim();
        
        if (password.length >= 8) {
          updateIdPassword(memId, password, idNum);  // idNum 전달
          this.value = ''; // 입력 필드 초기화
        } else {
          showErrorMessage('비밀번호는 8자리 이상이어야 합니다.');
          this.focus();
        }
      }
    });
  });
  
  // 허용여부 버튼에 클릭 이벤트 리스너 추가
  const permitButtons = tableBody.querySelectorAll('.permit-btn');
  permitButtons.forEach(button => {
    button.addEventListener('click', function() {
      const memId = this.getAttribute('data-mem-id');
      const currentPermit = this.getAttribute('data-permit');
      const idNum = this.getAttribute('data-num');  // num 값 가져오기
      updateIdPermit(memId, currentPermit, idNum);  // idNum 전달
    });
  });
}

// ===== 고객 목록 모바일 카드 뷰 업데이트 (수정됨: data-num 속성 추가) =====
function updateIdMobileCardView() {
  const cardsContainer = document.getElementById('id-cards-container');
  if (!cardsContainer) {
    console.warn('카드 컨테이너 요소 id-cards-container를 찾을 수 없습니다.');
    return;
  }
  
  cardsContainer.innerHTML = '';
  
  // 첫 번째 카드: 신규 ID 생성 카드
  const newCard = document.createElement('div');
  newCard.className = 'card mb-3 border-warning';
  
  newCard.innerHTML = `
    <div class="card-body bg-light">
      <div class="d-flex justify-content-between align-items-start mb-2">
        <h6 class="card-title mb-0 text-warning fw-bold">신규 ID 생성</h6>
        <button type="button" 
                id="mobile-create-id-btn"
                class="btn btn-sm btn-primary">
          생성
        </button>
      </div>
      
      <div class="row g-2">
        <div class="col-6">
          <small class="text-muted">ID:</small><br>
          <div>
            <input type="text" 
                   id="mobile-new-id-input"
                   class="form-control form-control-sm" 
                   placeholder="새 ID 입력 (영문,숫자 3~20자)">
            <div id="mobile-id-status-message" class="mt-1"></div>
          </div>
        </div>
        <div class="col-6">
          <small class="text-muted">사용자명:</small><br>
          <input type="text" 
                 id="mobile-new-user-input"
                 class="form-control form-control-sm" 
                 placeholder="사용자명 입력">
        </div>
        <div class="col-6">
          <small class="text-muted">핸드폰:</small><br>
          <input type="text" 
                 id="mobile-new-phone-input"
                 class="form-control form-control-sm" 
                 placeholder="핸드폰번호 입력">
        </div>
        <div class="col-6">
          <small class="text-muted">비밀번호:</small><br>
          <input type="text" 
                 id="mobile-new-password-input"
                 class="form-control form-control-sm" 
                 placeholder="8자리 이상 입력">
        </div>
      </div>
    </div>
  `;
  
  cardsContainer.appendChild(newCard);
  
  // 기존 데이터 카드들
  const startIndex = (currentPageId - 1) * rowsPerPageId;
  
  currentDataId.forEach((item, index) => {
    const card = document.createElement('div');
    card.className = 'card mb-3';
    
    card.innerHTML = `
      <div class="card-body">
        <div class="d-flex justify-content-between align-items-start mb-2">
          <h6 class="card-title mb-0">${item.user || '-'} <small class="text-muted">(${startIndex + index + 1}번)</small></h6>
          <button type="button" 
                  class="btn btn-sm ${item.permit === '1' ? 'btn-success' : 'btn-danger'} permit-btn" 
                  data-mem-id="${item.mem_id}" 
                  data-permit="${item.permit}"
                  data-num="${item.num}">
            ${getIdPermitText(item.permit)}
          </button>
        </div>
        
        <div class="row g-2">
          <div class="col-6">
            <small class="text-muted">핸드폰:</small><br>
            <span>${item.hphone || '-'}</span>
          </div>
          <div class="col-12">
            <small class="text-muted">읽기전용:</small><br>
            <span>${getIdReadOnlyText(item.readIs)}</span>
          </div>
          <div class="col-12">
            <small class="text-muted">비밀번호 변경:</small><br>
            <input type="text" 
                   class="form-control form-control-sm password-input" 
                   placeholder="8자리 이상 입력 후 엔터" 
                   data-mem-id="${item.mem_id}"
                   data-num="${item.num}">
          </div>
        </div>
      </div>
    `;
    
    cardsContainer.appendChild(card);
  });
  
  // 모바일 신규 ID 생성 버튼 이벤트 리스너
  document.getElementById('mobile-create-id-btn').addEventListener('click', createMobileNewId);
  
  // 모바일 아이디 입력 필드에 실시간 중복 검사 이벤트 리스너 추가
  const mobileNewIdInput = document.getElementById('mobile-new-id-input');
  const mobileIdStatusMessage = document.getElementById('mobile-id-status-message');
  
  let mobileIdCheckTimeout;
  mobileNewIdInput.addEventListener('input', function() {
    const memId = this.value.trim();
    
    // 이전 타이머 제거
    if (mobileIdCheckTimeout) {
      clearTimeout(mobileIdCheckTimeout);
    }
    
    // 아이디 검사 상태 초기화
    isIdAvailable = false;
    lastCheckedId = '';
    this.classList.remove('is-valid', 'is-invalid');
    mobileIdStatusMessage.textContent = '';
    mobileIdStatusMessage.className = '';
    
    if (memId === '') {
      return;
    }
    
    // 500ms 후에 중복 검사 실행 (디바운싱)
    mobileIdCheckTimeout = setTimeout(() => {
      checkIdDuplicate(memId, (isValid, message) => {
        updateIdInputStatus(mobileNewIdInput, mobileIdStatusMessage, isValid, message);
      });
    }, 500);
  });
  
  // 모바일 신규 ID 입력 필드들에 엔터 이벤트 리스너 추가
  const mobileNewInputs = [
    mobileNewIdInput,
    document.getElementById('mobile-new-user-input'),
    document.getElementById('mobile-new-phone-input'),
    document.getElementById('mobile-new-password-input')
  ];
  
  mobileNewInputs.forEach(input => {
    input.addEventListener('keypress', function(e) {
      if (e.key === 'Enter') {
        createMobileNewId();
      }
    });
  });
  
  // 모바일 카드뷰의 비밀번호 입력 필드에 엔터 이벤트 리스너 추가 (수정됨: idNum 전달)
  const passwordInputs = cardsContainer.querySelectorAll('.password-input');
  passwordInputs.forEach(input => {
    input.addEventListener('keypress', function(e) {
      if (e.key === 'Enter') {
        const memId = this.getAttribute('data-mem-id');
        const idNum = this.getAttribute('data-num');  // num 값 가져오기
        const password = this.value.trim();
        
        if (password.length >= 8) {
          updateIdPassword(memId, password, idNum);  // idNum 전달
          this.value = ''; // 입력 필드 초기화
        } else {
          showErrorMessage('비밀번호는 8자리 이상이어야 합니다.');
          this.focus();
        }
      }
    });
  });
  
  // 모바일 카드뷰의 허용여부 버튼에 클릭 이벤트 리스너 추가
  const permitButtons = cardsContainer.querySelectorAll('.permit-btn');
  permitButtons.forEach(button => {
    button.addEventListener('click', function() {
      const memId = this.getAttribute('data-mem-id');
      const currentPermit = this.getAttribute('data-permit');
      const idNum = this.getAttribute('data-num');  // num 값 가져오기
      updateIdPermit(memId, currentPermit, idNum);  // idNum 전달
    });
  });
}

// ===== 고객 목록 페이지네이션 업데이트 =====
function updateIdPagination() {
  const paginationElement = document.getElementById('pagination-id');
  if (!paginationElement) {
    console.warn('페이지네이션 요소 pagination-id를 찾을 수 없습니다.');
    return;
  }
  
  if (totalPagesId <= 1) {
    paginationElement.style.display = 'none';
    return;
  }
  
  paginationElement.style.display = 'flex';
  paginationElement.innerHTML = '';
  
  createIdPaginationComponent(paginationElement, false);
}

// ===== 고객 목록 모바일 페이지네이션 업데이트 =====
function updateIdMobilePagination() {
  const mobilePaginationElement = document.getElementById('mobile-pagination-id');
  if (!mobilePaginationElement) {
    console.warn('모바일 페이지네이션 요소 mobile-pagination-id를 찾을 수 없습니다.');
    return;
  }
  
  if (totalPagesId <= 1) {
    mobilePaginationElement.style.display = 'none';
    return;
  }
  
  mobilePaginationElement.style.display = 'flex';
  mobilePaginationElement.innerHTML = '';
  
  createIdPaginationComponent(mobilePaginationElement, true);
}

// ===== 고객 목록 페이지네이션 컴포넌트 생성 =====
function createIdPaginationComponent(paginationElement, isMobile) {
  // 이전 버튼
  const prevButton = document.createElement('li');
  prevButton.className = `page-item ${currentPageId <= 1 ? 'disabled' : ''}`;
  prevButton.innerHTML = '<a class="page-link" href="#" aria-label="Previous"><span aria-hidden="true">&laquo;</span></a>';
  prevButton.addEventListener('click', (e) => {
    e.preventDefault();
    if (currentPageId > 1) {
      loadIdData(currentPageId - 1);
    }
  });
  paginationElement.appendChild(prevButton);
  
  // 페이지 번호 버튼
  const maxPageButtons = isMobile ? 3 : 5;
  const halfMaxButtons = Math.floor(maxPageButtons / 2);
  let startPage = Math.max(1, currentPageId - halfMaxButtons);
  let endPage = Math.min(totalPagesId, startPage + maxPageButtons - 1);
  
  if (endPage - startPage + 1 < maxPageButtons) {
    startPage = Math.max(1, endPage - maxPageButtons + 1);
  }
  
  for (let i = startPage; i <= endPage; i++) {
    const pageButton = document.createElement('li');
    pageButton.className = `page-item ${i === currentPageId ? 'active' : ''}`;
    pageButton.innerHTML = `<a class="page-link" href="#">${i}</a>`;
    pageButton.addEventListener('click', (e) => {
      e.preventDefault();
      if (i !== currentPageId) {
        loadIdData(i);
      }
    });
    paginationElement.appendChild(pageButton);
  }
  
  // 다음 버튼
  const nextButton = document.createElement('li');
  nextButton.className = `page-item ${currentPageId >= totalPagesId ? 'disabled' : ''}`;
  nextButton.innerHTML = '<a class="page-link" href="#" aria-label="Next"><span aria-hidden="true">&raquo;</span></a>';
  nextButton.addEventListener('click', (e) => {
    e.preventDefault();
    if (currentPageId < totalPagesId) {
      loadIdData(currentPageId + 1);
    }
  });
  paginationElement.appendChild(nextButton);
}

// ===== 고객 목록 페이지네이션 숨기기 =====
function hideIdPagination() {
  const paginationContainer = document.getElementById('pagination-container-id');
  if (paginationContainer) {
    paginationContainer.style.display = 'none';
  }
  
  const mobilePaginationContainer = document.getElementById('mobile-pagination-container-id');
  if (mobilePaginationContainer) {
    mobilePaginationContainer.style.display = 'none';
  }
}

// ===== ID 추가 모달 열기 함수 =====
function openIdModal() {
  // ID 추가 모달을 여는 함수
  console.log('ID 추가 모달 열기');
  // 여기에 모달 열기 코드 구현
}

// ===== 메시지 표시 함수들 =====
function showSuccessMessage(message) {
  // 성공 메시지 표시 함수
  console.log('성공:', message);
  // 실제 구현에서는 토스트 메시지나 알림창 표시
  alert(message);
}

function showErrorMessage(message) {
  // 오류 메시지 표시 함수
  console.log('오류:', message);
  // 실제 구현에서는 토스트 메시지나 알림창 표시
  alert(message);
}

function showLoading() {
  // 로딩 표시 함수
  console.log('로딩 중...');
}

function hideLoading() {
  // 로딩 숨기기 함수
  console.log('로딩 완료');
}

function getCookie(name) {
  // 쿠키 값 가져오기 함수
  const value = `; ${document.cookie}`;
  const parts = value.split(`; ${name}=`);
  if (parts.length === 2) return parts.pop().split(';').shift();
  return null;
}

// ===== 페이지 로드 시 초기화 =====
document.addEventListener('DOMContentLoaded', function() {
  // 초기 데이터 로드
  loadIdData(1);
});

// ===== 탭 초기화 함수 (전역 스코프에 등록) =====
window.initializeIdManagementTab = function() {
  console.log('ID 관리 탭이 초기화되었습니다.');
  // 필요한 경우 여기서 추가 초기화 작업 수행
  loadIdData(1);
};

// ===== 명명 규칙 가이드라인 =====
/*
고객 목록 관리 관련 명명 규칙:

1. 변수명: camelCase 사용, 'Id' 접두사
   - currentPageId, totalPagesId, totalRecordsId, currentDataId
   - isIdLoadDataCalled

2. 함수명: camelCase 사용, 'Id' 접두사, 동사로 시작
   - loadIdData(), updateIdUI(), formatIdDate()
   - getIdReadOnlyText(), getIdPermitText(), getIdPermitBadgeClass()
   - updateIdTableView(), updateIdMobileCardView()
   - updateIdPagination(), updateIdMobilePagination()
   - createIdPaginationComponent(), hideIdPagination()
   - checkIdDuplicate(), updateIdInputStatus(), resetIdCheckStatus() (아이디 중복 검사)
   - clearNewIdInputs(), clearMobileNewIdInputs() (입력 필드 초기화)
   - createNewId(), createMobileNewId() (신규 ID 생성)

3. HTML ID 매핑:
   - id-total-count: 총 개수 표시
   - id-list: 테이블 바디
   - id-cards-container: 모바일 카드 컨테이너
   - pagination-id: 데스크톱 페이지네이션
   - mobile-pagination-id: 모바일 페이지네이션
   - pagination-container-id: 페이지네이션 컨테이너
   - mobile-pagination-container-id: 모바일 페이지네이션 컨테이너

4. 테이블 컬럼 구성: 7개 컬럼
   - 순번, ID, 사용자명, 핸드폰, 읽기전용, 비번, 허용여부

5. API 경로:
   - ./api/customer/readIdList.php (목록 조회)
   - ./api/kjDaeri/checkUserId.php (아이디 중복 검사)
   - ./api/kjDaeri/id_save.php (신규 ID 생성 - 데스크탑/모바일 통일)
   - ./api/kjDaeri/pdatePassword.php (비밀번호 변경) - idNum 추가
   - ./api/customer/updateIdPermit.php (허용여부 변경)

6. 데이터 필드:
   - mem_id: 멤버 ID
   - user: 사용자명
   - hphone: 핸드폰 번호 (API 응답 필드명)
   - readIs: 읽기전용 여부 ('1': 읽기전용, 기타: 읽기쓰기)
   - permit: 허용여부 ('1': 허용, '2': 차단)
   - num: 데이터베이스 고유 번호 (비밀번호 업데이트 시 idNum으로 전송)

7. 주요 수정사항:
   - 비밀번호 업데이트 함수에 idNum 매개변수 추가
   - HTML 요소에 data-num 속성 추가 (테이블뷰, 모바일뷰)
   - 비밀번호 입력 필드 이벤트 리스너에서 idNum 값 전달
   - API 응답의 hphone 필드명 반영
   - FormData에 idNum 추가로 서버에 $_POST['idNum'] 전송

이 명명 규칙을 따르면 코드의 일관성과 가독성이 크게 향상됩니다.
*/