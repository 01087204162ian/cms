// 가입내역 페이지 전용 JavaScript

// 가입내역 조회 함수
function searchHistory(phone) {
    const cleanPhone = phone.replace(/[^\d]/g, '');
    
    if (cleanPhone.length < 10 || cleanPhone.length > 11) {
        CommonUtils.showPopup('입력 오류', '올바른 휴대폰 번호를 입력해주세요.');
        return;
    }
    
    const searchButton = document.getElementById('search-button');
    CommonUtils.showLoading(searchButton, '조회 중...');
    
    // 결과 영역 초기화
    hideAllResults();
    
    // 서버에 가입내역 조회 요청
    fetch('api/customer/getSignupHistory.php', {
        method: 'POST',
        headers: {
            'Content-Type': 'application/json',
        },
        body: JSON.stringify({
            phone: cleanPhone
        })
    })
    .then(response => {
        if (!response.ok) {
            throw new Error(`HTTP error! status: ${response.status}`);
        }
        return response.json();
    })
    .then(data => {
        CommonUtils.hideLoading(searchButton);
        
        if (data.success) {
            const history = data.data || [];
            
            if (history.length > 0) {
                displayHistory(history);
            } else {
                showNoHistory();
            }
        } else {
            const errorMessage = getHistoryErrorMessage(data.errorCode, data.message);
            CommonUtils.showPopup('조회 실패', errorMessage);
            showNoHistory();
        }
    })
    .catch(error => {
        CommonUtils.hideLoading(searchButton);
        console.error('가입내역 조회 중 오류 발생:', error);
        CommonUtils.showPopup('통신 오류', '서버와의 통신 중 문제가 발생했습니다. 잠시 후 다시 시도해주세요.');
        showNoHistory();
    });
}

// 모든 결과 영역 숨기기
function hideAllResults() {
    const historyResults = document.getElementById('history-results');
    const noHistory = document.getElementById('no-history');
    
    if (historyResults) historyResults.style.display = 'none';
    if (noHistory) noHistory.style.display = 'none';
}

// 가입내역 표시 함수
function displayHistory(history) {
    const historyResults = document.getElementById('history-results');
    const historyList = document.getElementById('history-list');
    const historyCountNumber = document.getElementById('history-count-number');
    const noHistory = document.getElementById('no-history');
    
    if (!historyResults || !historyList) {
        console.error('가입내역 표시 요소들을 찾을 수 없습니다.');
        CommonUtils.showPopup('오류', '가입내역 페이지가 올바르게 설정되지 않았습니다.');
        return;
    }
    
    // 가입내역 개수 업데이트
    if (historyCountNumber) {
        historyCountNumber.textContent = history.length;
    }
    
    historyList.innerHTML = '';
    
    history.forEach(item => {
        const historyItem = document.createElement('div');
        historyItem.className = 'history-item';
        
        const statusClass = getStatusClass(item.status);
        const statusText = getStatusText(item.status);
        
        historyItem.innerHTML = `
            <div class="history-header">
                <div class="history-id">가입번호: ${item.signupId || item.id || 'N/A'}</div>
                <div class="history-status ${statusClass}">${statusText}</div>
            </div>
            <div class="history-details">
                <div class="history-detail">
                    <span class="label">이름:</span>
                    <span class="value">${item.customerName || item.name || 'N/A'}</span>
                </div>
                <div class="history-detail">
                    <span class="label">골프장:</span>
                    <span class="value">${item.golfCourseName || item.golfCourse || 'N/A'}</span>
                </div>
                <div class="history-detail">
                    <span class="label">티오프 시간:</span>
                    <span class="value">${CommonUtils.formatDateTime(item.teeOffTime || item.teeTime)}</span>
                </div>
                <div class="history-detail">
                    <span class="label">가입일시:</span>
                    <span class="value">${CommonUtils.formatDateTime(item.createdAt || item.signupDate)}</span>
                </div>
                ${item.couponNumber ? `
                <div class="history-detail">
                    <span class="label">쿠폰번호:</span>
                    <span class="value">${item.couponNumber}</span>
                </div>
                ` : ''}
                ${item.phoneNumber && item.phoneNumber !== item.customerPhone ? `
                <div class="history-detail">
                    <span class="label">연락처:</span>
                    <span class="value">${CommonUtils.formatPhoneNumber(item.phoneNumber)}</span>
                </div>
                ` : ''}
            </div>
            ${item.status === 'ACTIVE' ? `
            <div class="history-actions">
                <button class="next-step-button" onclick="goToClaim()">
                    보상 신청하기
                </button>
            </div>
            ` : ''}
        `;
        
        historyList.appendChild(historyItem);
    });
    
    historyResults.style.display = 'block';
    if (noHistory) noHistory.style.display = 'none';
}

// 가입내역 없음 표시
function showNoHistory() {
    const historyResults = document.getElementById('history-results');
    const noHistory = document.getElementById('no-history');
    
    if (historyResults) historyResults.style.display = 'none';
    if (noHistory) noHistory.style.display = 'block';
}

// 상태에 따른 CSS 클래스 반환
function getStatusClass(status) {
    const statusClasses = {
        'ACTIVE': 'status-active',
        'COMPLETED': 'status-completed', 
        'CANCELLED': 'status-cancelled',
        'EXPIRED': 'status-expired',
        'PENDING': 'status-pending'
    };
    
    return statusClasses[status] || 'status-default';
}

// 상태 텍스트 변환
function getStatusText(status) {
    const statusTexts = {
        'ACTIVE': '가입완료',
        'COMPLETED': '이용완료',
        'CANCELLED': '취소됨',
        'EXPIRED': '만료됨',
        'PENDING': '처리중'
    };
    
    return statusTexts[status] || status || '알 수 없음';
}

// 에러 메시지 처리
function getHistoryErrorMessage(errorCode, defaultMessage) {
    const errorMessages = {
        'PHONE_NOT_FOUND': '해당 휴대폰 번호로 가입된 내역이 없습니다.',
        'INVALID_PHONE': '올바르지 않은 휴대폰 번호입니다.',
        'DATABASE_ERROR': '데이터베이스 오류가 발생했습니다.',
        'SERVER_ERROR': '서버 오류가 발생했습니다. 고객센터로 문의해주세요.',
        'ACCESS_DENIED': '조회 권한이 없습니다.'
    };
    
    return errorMessages[errorCode] || defaultMessage || '알 수 없는 오류가 발생했습니다.';
}

// 보상 신청 페이지로 이동
function goToClaim() {
    window.location.href = 'claim.html';
}

// 이벤트 리스너 설정
function setupEventListeners() {
    const searchButton = document.getElementById('search-button');
    const searchPhone = document.getElementById('search-phone');
    const goToSignupBtn = document.getElementById('go-to-signup-btn');
    const tryAgainSearchBtn = document.getElementById('try-again-search');

    if (searchButton && searchPhone) {
        searchButton.addEventListener('click', function() {
            const phone = searchPhone.value.trim();
            
            if (!phone) {
                CommonUtils.showPopup('입력 오류', '휴대폰번호를 입력해주세요.');
                return;
            }
            
            searchHistory(phone);
        });

        // 엔터키로도 조회 가능
        searchPhone.addEventListener('keypress', function(e) {
            if (e.key === 'Enter') {
                searchButton.click();
            }
        });
    }
    
    // 보험 가입하기 버튼
    if (goToSignupBtn) {
        goToSignupBtn.addEventListener('click', function() {
            window.location.href = 'index.html';
        });
    }
    
    // 다시 조회하기 버튼
    if (tryAgainSearchBtn) {
        tryAgainSearchBtn.addEventListener('click', function() {
            hideAllResults();
            const searchPhone = document.getElementById('search-phone');
            if (searchPhone) {
                searchPhone.value = '';
                searchPhone.focus();
            }
        });
    }
}

// 페이지 초기화
document.addEventListener('DOMContentLoaded', function() {
    console.log('가입내역 페이지 로드 완료');
    
    // 이벤트 리스너 설정
    setupEventListeners();
    
    // 검색 입력 필드에 포커스
    const searchPhone = document.getElementById('search-phone');
    if (searchPhone) {
        searchPhone.focus();
    }
});