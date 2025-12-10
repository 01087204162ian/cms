// 전역 변수
let companionCount = 0;
let isFormDisabled = false;

// 페이지 전환 기능
const navSignup = document.getElementById('nav-signup');
const navClaim = document.getElementById('nav-claim');
const navFaq = document.getElementById('nav-faq');
const navTerms = document.getElementById('nav-terms');
// ✅ 가입내역 탭 추가 (HTML에 nav-history가 있으면 사용)
const navHistory = document.getElementById('nav-history');

const signupPage = document.getElementById('signup-page');
const claimPage = document.getElementById('claim-page');
const faqPage = document.getElementById('faq-page');
const termsPage = document.getElementById('terms-page');
// ✅ 가입내역 페이지 추가 (HTML에 history-page가 있으면 사용)
const historyPage = document.getElementById('history-page');

// ✅ 확인 모달 관련 요소들 추가
const confirmPopupOverlay = document.getElementById('confirm-popup-overlay');
const confirmDetails = document.getElementById('confirm-details');
const confirmCancelButton = document.getElementById('confirm-cancel-button');
const confirmSubmitButton = document.getElementById('confirm-submit-button');

// 네비게이션 탭 클릭 이벤트
navSignup.addEventListener('click', function() {
    showPage(signupPage);
    setActiveTab(navSignup);
});

navClaim.addEventListener('click', function() {
    showPage(claimPage);
    setActiveTab(navClaim);
});

// ✅ 가입내역 탭 이벤트 추가 (요소가 존재할 때만)
if (navHistory && historyPage) {
    navHistory.addEventListener('click', function() {
        showPage(historyPage);
        setActiveTab(navHistory);
    });
}

navFaq.addEventListener('click', function() {
    showPage(faqPage);
    setActiveTab(navFaq);
});

navTerms.addEventListener('click', function() {
    showPage(termsPage);
    setActiveTab(navTerms);
});

// 페이지 표시 함수 (historyPage 추가)
function showPage(page) {
    // 모든 페이지 숨기기
    signupPage.style.display = 'none';
    claimPage.style.display = 'none';
    faqPage.style.display = 'none';
    termsPage.style.display = 'none';
    if (historyPage) historyPage.style.display = 'none';  // ✅ 추가
    
    // 선택한 페이지 표시
    page.style.display = 'block';
}

// 탭 활성화 함수 (navHistory 추가)
function setActiveTab(tab) {
    // 모든 탭 비활성화
    navSignup.classList.remove('active');
    navClaim.classList.remove('active');
    navFaq.classList.remove('active');
    navTerms.classList.remove('active');
    if (navHistory) navHistory.classList.remove('active');  // ✅ 추가
    
    // 선택한 탭 활성화
    tab.classList.add('active');
}

// 폼 비활성화 함수 (validateCoupon.js와 연동)
function disableForm() {
    isFormDisabled = true;
    const formElements = [
        'name', 'phone', 'golf-course', 'tee-time', 
        'terms-checkbox', 'signup-button'
    ];
    
    formElements.forEach(id => {
        const element = document.getElementById(id);
        if (element) {
            element.disabled = true;
        }
    });
    
    // 동반자 추가 버튼도 비활성화
    const addCompanionBtn = document.getElementById('add-companion');
    if (addCompanionBtn) {
        addCompanionBtn.disabled = true;
        addCompanionBtn.style.opacity = '0.5';
        addCompanionBtn.style.cursor = 'not-allowed';
    }
}

// 폼 활성화 함수 (validateCoupon.js와 연동)
function enableForm() {
    isFormDisabled = false;
    const formElements = [
        'name', 'phone', 'golf-course', 'tee-time', 
        'terms-checkbox', 'signup-button'
    ];
    
    formElements.forEach(id => {
        const element = document.getElementById(id);
        if (element) {
            element.disabled = false;
        }
    });
    
    // 동반자 추가 버튼도 활성화
    const addCompanionBtn = document.getElementById('add-companion');
    if (addCompanionBtn) {
        addCompanionBtn.disabled = false;
        addCompanionBtn.style.opacity = '1';
        addCompanionBtn.style.cursor = 'pointer';
    }
}

// ✅ 확인 모달 표시 함수 - 새로 추가
function showConfirmModal(formData) {
    const details = `
        <div class="detail-item">
            <span class="detail-label">이름:</span>
            <span class="detail-value">${formData.name}</span>
        </div>
        <div class="detail-item">
            <span class="detail-label">휴대폰번호:</span>
            <span class="detail-value">${formData.phone}</span>
        </div>
        <div class="detail-item">
            <span class="detail-label">골프장명:</span>
            <span class="detail-value">${formData.golfCourse}</span>
        </div>
        <div class="detail-item">
            <span class="detail-label">티오프 시간:</span>
            <span class="detail-value">${formatDateTime(formData.teeTime)}</span>
        </div>
        <div class="detail-item">
            <span class="detail-label">쿠폰번호:</span>
            <span class="detail-value">${formData.couponNumber}</span>
        </div>
    `;
    
    confirmDetails.innerHTML = details;
    confirmPopupOverlay.classList.add('show');
}

// ✅ 날짜/시간 포맷팅 함수 - 새로 추가
function formatDateTime(dateTimeString) {
    const date = new Date(dateTimeString);
    const year = date.getFullYear();
    const month = String(date.getMonth() + 1).padStart(2, '0');
    const day = String(date.getDate()).padStart(2, '0');
    const hours = String(date.getHours()).padStart(2, '0');
    const minutes = String(date.getMinutes()).padStart(2, '0');
    
    return `${year}년 ${month}월 ${day}일 ${hours}:${minutes}`;
}

// ✅ 폼 초기화 함수 - 새로 추가
function resetForm() {
    document.getElementById('name').value = '';
    document.getElementById('phone').value = '';
    document.getElementById('golf-course').value = '';
    document.getElementById('tee-time').value = '';
    document.getElementById('terms-checkbox').checked = false;
    
    // 동반자 정보 초기화 (동반자 기능이 있다면)
    companionCount = 0;
}

// ✅ 확인 모달 취소 버튼 이벤트 - 새로 추가
confirmCancelButton.addEventListener('click', function() {
    confirmPopupOverlay.classList.remove('show');
});

// ✅ 확인 모달의 확인 버튼 이벤트 - 새로 추가
confirmSubmitButton.addEventListener('click', function() {
    // 현재 표시된 정보로 가입 처리
    const formData = {
        couponNumber: document.getElementById('coupon-number').value,
        name: document.getElementById('name').value,
        phone: document.getElementById('phone').value,
        golfCourse: document.getElementById('golf-course').value,
        teeTime: document.getElementById('tee-time').value,
        termsAgreed: document.getElementById('terms-checkbox').checked,
        companions: []
    };
    
    processSignup(formData);
});

// 페이지 로드 시 쿠폰 검증 실행 (validateCoupon.js 연동)
window.addEventListener('load', function() {
    console.log('페이지 로드 완료');
    
    // URL 파라미터에서 쿠폰 번호를 가져옵니다
    const urlParams = new URLSearchParams(window.location.search);
    const couponFromUrl = urlParams.get('coupon') || 'VIP-2025-1234';
    
    console.log('쿠폰 번호:', couponFromUrl);
    
    // 쿠폰 번호 필드에 값 설정
    const couponNumberField = document.getElementById('coupon-number');
    if (couponNumberField) {
        couponNumberField.value = couponFromUrl;
    }
    
    // validateCouponNumber 함수 존재 확인 (validateCoupon.js에서 로드됨)
    if (typeof validateCouponNumber !== 'function') {
        console.error('validateCouponNumber 함수가 정의되지 않았습니다!');
        console.log('validateCoupon.js 파일이 로드되지 않았을 수 있습니다.');
        disableForm();
        return;
    }
    
    console.log('쿠폰 검증 시작...');
    
    // 쿠폰 사전 검증 실행 (validateCoupon.js의 함수 사용)
    validateCouponNumber(
        couponFromUrl,
        // 성공 시
        function(couponData) {
            console.log('✅ 쿠폰 검증 성공:', couponData);
            if (typeof CouponUI !== 'undefined') {
                CouponUI.showSuccess(couponData);
            }
            enableForm(); // 폼 활성화
            // 필요시 고객 정보 자동 입력
            if (couponData.customerInfo && couponData.customerInfo.name) {
                const nameField = document.getElementById('name');
                if (nameField) {
                    nameField.value = couponData.customerInfo.name;
                }
            }
        },
        // 실패 시  
        function(errorMessage) {
            console.error('❌ 쿠폰 검증 실패:', errorMessage);
            if (typeof CouponUI !== 'undefined') {
                CouponUI.showError(errorMessage);
            }
            disableForm(); // 입력 폼 비활성화
        },
        // 로딩 중
        function(isLoading) {
            console.log('로딩 상태:', isLoading);
            if (typeof CouponUI !== 'undefined') {
                CouponUI.showLoading(isLoading);
            }
        }
    );

    // ✅ 가입내역 기능 초기화 추가
    setTimeout(function() {
        initializeHistoryFeature();
    }, 100);
});

// FAQ 아코디언 기능 (기존 코드 유지)
const faqQuestions = document.querySelectorAll('.faq-question');

faqQuestions.forEach(question => {
    question.addEventListener('click', function() {
        // 현재 질문 활성화/비활성화 토글
        this.classList.toggle('active');
        
        // 답변 표시/숨김 토글
        const answer = this.nextElementSibling;
        answer.classList.toggle('show');
    });
});

// 휴대폰 번호 포맷팅 (기존 코드 유지)
const phoneInputs = document.querySelectorAll('input[type="tel"]');

phoneInputs.forEach(input => {
    input.addEventListener('input', function(e) {
        // 숫자만 추출
        let value = this.value.replace(/[^\d]/g, '');
        
        // 포맷팅
        if (value.length <= 3) {
            this.value = value;
        } else if (value.length <= 7) {
            this.value = value.slice(0, 3) + '-' + value.slice(3);
        } else {
            this.value = value.slice(0, 3) + '-' + value.slice(3, 7) + '-' + value.slice(7, 11);
        }
    });
});

// 파일 업로드 기능 (기존 코드 유지)
const uploadPhoto = document.getElementById('upload-photo');
const uploadCertificate = document.getElementById('upload-certificate');
const photoInput = document.getElementById('photo-input');
const certificateInput = document.getElementById('certificate-input');

if (uploadPhoto && photoInput) {
    uploadPhoto.addEventListener('click', function() {
        photoInput.click();
    });
}

if (uploadCertificate && certificateInput) {
    uploadCertificate.addEventListener('click', function() {
        certificateInput.click();
    });
}

if (photoInput) {
    photoInput.addEventListener('change', function() {
        if (this.files.length > 0) {
            const fileName = this.files[0].name;
            uploadPhoto.querySelector('.file-upload-text').textContent = fileName;
            uploadPhoto.style.borderColor = '#4CAF50';
        }
    });
}

if (certificateInput) {
    certificateInput.addEventListener('change', function() {
        if (this.files.length > 0) {
            const fileName = this.files[0].name;
            uploadCertificate.querySelector('.file-upload-text').textContent = fileName;
            uploadCertificate.style.borderColor = '#4CAF50';
        }
    });
}

// 팝업 기능 (기존 코드 유지)
const popupOverlay = document.getElementById('popup-overlay');
const popupTitle = document.getElementById('popup-title');
const popupMessage = document.getElementById('popup-message');
const popupButton = document.getElementById('popup-button');

function showPopup(title, message) {
    popupTitle.textContent = title;
    popupMessage.textContent = message;
    popupOverlay.classList.add('show');
}

popupButton.addEventListener('click', function() {
    popupOverlay.classList.remove('show');
});

// 가입 신청 제출 (수정된 버전)
const signupButton = document.getElementById('signup-button');

// ✅ 새로운 가입 신청 제출 이벤트 핸들러 (확인 모달 추가)
signupButton.addEventListener('click', function() {
    if (isFormDisabled) {
        showPopup('오류', '쿠폰 검증이 완료되지 않았습니다.');
        return;
    }
    
    const couponNumber = document.getElementById('coupon-number').value;
    const name = document.getElementById('name').value;
    const phone = document.getElementById('phone').value;
    const golfCourse = document.getElementById('golf-course').value;
    const teeTime = document.getElementById('tee-time').value;
    const termsCheckbox = document.getElementById('terms-checkbox').checked;
    
    // 필수 입력 검증
    if (!name || !phone || !golfCourse || !teeTime) {
        showPopup('입력 오류', '모든 필수 항목을 입력해주세요.');
        return;
    }
    
    if (!termsCheckbox) {
        showPopup('약관 동의', '약관 및 개인정보 활용 동의가 필요합니다.');
        return;
    }
    
    if (!couponNumber) {
        showPopup('쿠폰 번호 오류', '유효한 쿠폰 번호가 필요합니다.');
        return;
    }
    
    // 동반자 정보 수집 (동반자 기능이 있다면)
    const companions = [];
    document.querySelectorAll('.companion-item').forEach(item => {
        const fields = item.querySelectorAll('.form-control');
        const companionName = fields[0].value;
        const companionPhone = fields[1].value;
        
        if (companionName && companionPhone) {
            companions.push({
                name: companionName,
                phone: companionPhone
            });
        }
    });
    
    // 폼 데이터 수집
    const formData = {
        couponNumber,
        name,
        phone,
        golfCourse,
        teeTime,
        termsAgreed: termsCheckbox,
        companions
    };
    
    // ✅ 여기가 핵심! 바로 서버 전송하지 않고 확인 모달 표시
    showConfirmModal(formData);
});

// ✅ 실제 가입 처리 함수 - 새로 추가
function processSignup(formData) {
    // FormData 객체 생성 및 데이터 추가
    const submitData = new FormData();
    submitData.append('couponNumber', formData.couponNumber);
    submitData.append('name', formData.name);
    submitData.append('phone', formData.phone);
    submitData.append('golfCourse', formData.golfCourse);
    submitData.append('teeTime', formData.teeTime);
    submitData.append('termsAgreed', formData.termsAgreed);
    submitData.append('companions', JSON.stringify(formData.companions));
    
    // fetch를 사용하여 서버로 데이터 전송
    fetch('api/customer/sinupAce.php', {
        method: 'POST',
        body: submitData
    })
    .then(response => {
        if (!response.ok) {
            throw new Error('서버 응답이 올바르지 않습니다.');
        }
        return response.json();
    })
    .then(data => {
        // 확인 모달 닫기
        confirmPopupOverlay.classList.remove('show');
        
        // 성공 팝업 표시 (가입내역확인 버튼 포함)
        showSuccessPopup('가입 완료', '홀인원 보험 가입이 완료되었습니다. 즐거운 라운딩 되세요!');
    })
    .catch(error => {
        // 확인 모달 닫기
        confirmPopupOverlay.classList.remove('show');
        
        // 오류 발생 시 처리
        console.error('데이터 전송 중 오류가 발생했습니다:', error);
        showPopup('오류 발생', '서버와의 통신 중 문제가 발생했습니다. 다시 시도해 주세요.');
    });
}

// ✅ 성공 팝업 (가입내역확인 버튼 포함) - 새로 추가
function showSuccessPopup(title, message) {
    popupTitle.textContent = title;
    popupMessage.innerHTML = message + '<br><br>가입내역을 확인하시겠습니까?';
    
    // 기존 버튼 숨기고 새 버튼들 추가
    popupButton.style.display = 'none';
    
    const buttonContainer = document.createElement('div');
    buttonContainer.className = 'confirm-buttons';
    buttonContainer.innerHTML = `
        <button class="popup-button secondary" id="success-close">닫기</button>
        <button class="popup-button primary" id="success-history">가입내역확인</button>
    `;
    
    popupMessage.parentNode.appendChild(buttonContainer);
    popupOverlay.classList.add('show');
    
    // 버튼 이벤트 등록
    document.getElementById('success-close').addEventListener('click', function() {
        closeSuccessPopup();
        resetForm();
    });
    
    document.getElementById('success-history').addEventListener('click', function() {
        closeSuccessPopup();
        resetForm();
        if (historyPage && navHistory) {
            showPage(historyPage);
            setActiveTab(navHistory);
        }
    });
}

// ✅ 성공 팝업 닫기 함수 - 새로 추가
function closeSuccessPopup() {
    popupOverlay.classList.remove('show');
    
    // 추가된 버튼 제거하고 원래 버튼 복원
    const buttonContainer = document.querySelector('.confirm-buttons');
    if (buttonContainer) {
        buttonContainer.remove();
    }
    popupButton.style.display = 'block';
}

// ✅ 가입내역 조회 기능 추가
function initializeHistoryFeature() {
    const searchButton = document.getElementById('search-button');
    const searchPhone = document.getElementById('search-phone');
    const historyResults = document.getElementById('history-results');
    const historyList = document.getElementById('history-list');
    const noHistory = document.getElementById('no-history');

    // 요소들이 존재하는 경우에만 이벤트 추가
    if (searchButton && searchPhone) {
        searchButton.addEventListener('click', function() {
            const phone = searchPhone.value;
            
            if (!phone) {
                showPopup('입력 오류', '휴대폰번호를 입력해주세요.');
                return;
            }
            
            // 가입내역 조회
            searchHistory(phone);
        });

        // 엔터키로도 조회 가능하게
        searchPhone.addEventListener('keypress', function(e) {
            if (e.key === 'Enter') {
                searchButton.click();
            }
        });
    }
}

// 가입내역 조회 함수 (서버 통신)
function searchHistory(phone) {
    // 휴대폰 번호 포맷팅 (하이픈 제거)
    const cleanPhone = phone.replace(/[^\d]/g, '');
    
    // 기본 검증
    if (cleanPhone.length < 10 || cleanPhone.length > 11) {
        showPopup('입력 오류', '올바른 휴대폰 번호를 입력해주세요.');
        return;
    }
    
    // 로딩 표시
    showHistoryLoading(true);
    
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
        // 로딩 종료
        showHistoryLoading(false);
        
        if (data.success) {
            const history = data.data || [];
            
            if (history.length > 0) {
                displayHistory(history);
            } else {
                showNoHistory();
            }
        } else {
            // 조회 실패
            const errorMessage = getHistoryErrorMessage(data.errorCode, data.message);
            showPopup('조회 실패', errorMessage);
            showNoHistory();
        }
    })
    .catch(error => {
        // 로딩 종료
        showHistoryLoading(false);
        
        console.error('가입내역 조회 중 오류 발생:', error);
        showPopup('통신 오류', '서버와의 통신 중 문제가 발생했습니다. 잠시 후 다시 시도해주세요.');
        showNoHistory();
    });
}

// 가입내역 조회 에러 메시지 처리
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

// 가입내역 조회 로딩 표시
function showHistoryLoading(isLoading) {
    const historyResults = document.getElementById('history-results');
    const noHistory = document.getElementById('no-history');
    const searchButton = document.getElementById('search-button');
    
    if (isLoading) {
        // 로딩 중일 때
        if (historyResults) historyResults.style.display = 'none';
        if (noHistory) noHistory.style.display = 'none';
        if (searchButton) {
            searchButton.disabled = true;
            searchButton.textContent = '조회 중...';
        }
        
        // 로딩 메시지 표시 (임시)
        const searchSection = document.querySelector('.search-section');
        if (searchSection) {
            let loadingMsg = document.getElementById('loading-message');
            if (!loadingMsg) {
                loadingMsg = document.createElement('div');
                loadingMsg.id = 'loading-message';
                loadingMsg.style.cssText = 'text-align: center; padding: 20px; color: #666; font-size: 14px;';
                loadingMsg.textContent = '가입내역을 조회하고 있습니다...';
                searchSection.appendChild(loadingMsg);
            }
            loadingMsg.style.display = 'block';
        }
    } else {
        // 로딩 종료
        if (searchButton) {
            searchButton.disabled = false;
            searchButton.textContent = '조회하기';
        }
        
        // 로딩 메시지 숨기기
        const loadingMsg = document.getElementById('loading-message');
        if (loadingMsg) {
            loadingMsg.style.display = 'none';
        }
    }
}

// 가입내역 표시 함수 (서버 데이터 처리)
function displayHistory(history) {
    const historyResults = document.getElementById('history-results');
    const historyList = document.getElementById('history-list');
    const noHistory = document.getElementById('no-history');
    
    if (!historyResults || !historyList || !noHistory) {
        console.error('가입내역 표시 요소들을 찾을 수 없습니다.');
        showPopup('오류', '가입내역 페이지가 올바르게 설정되지 않았습니다.');
        return;
    }
    
    historyList.innerHTML = '';
    
    history.forEach(item => {
        // 서버에서 받은 데이터 구조에 맞춰 처리
        const historyItem = document.createElement('div');
        historyItem.className = 'history-item';
        
        // 상태에 따른 스타일 클래스
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
                    <span class="value">${formatDateTime(item.teeOffTime || item.teeTime)}</span>
                </div>
                <div class="history-detail">
                    <span class="label">가입일시:</span>
                    <span class="value">${formatDateTime(item.createdAt || item.signupDate)}</span>
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
                    <span class="value">${formatPhoneNumber(item.phoneNumber)}</span>
                </div>
                ` : ''}
            </div>
        `;
        
        historyList.appendChild(historyItem);
    });
    
    historyResults.style.display = 'block';
    noHistory.style.display = 'none';
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

// 휴대폰 번호 포맷팅
function formatPhoneNumber(phone) {
    if (!phone) return 'N/A';
    
    const cleaned = phone.replace(/[^\d]/g, '');
    
    if (cleaned.length === 11) {
        return cleaned.replace(/(\d{3})(\d{4})(\d{4})/, '$1-$2-$3');
    } else if (cleaned.length === 10) {
        return cleaned.replace(/(\d{3})(\d{3})(\d{4})/, '$1-$2-$3');
    }
    
    return phone;
}

// 가입내역 없음 표시 함수
function showNoHistory() {
    const historyResults = document.getElementById('history-results');
    const noHistory = document.getElementById('no-history');
    
    if (historyResults && noHistory) {
        historyResults.style.display = 'none';
        noHistory.style.display = 'block';
    } else {
        showPopup('조회 결과', '가입 내역이 없습니다.');
    }
}