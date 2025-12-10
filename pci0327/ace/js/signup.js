let isFormDisabled = true;
let companionCount = 0; // 동반자 수 카운터 추가
const MAX_COMPANIONS = 3; // 최대 동반자 수



// 전역 SignupModule 객체 정의
window.SignupModule = window.SignupModule || {};

// validateCoupon.js와의 연동을 위한 콜백 함수들
window.SignupModule.onCouponValidationSuccess = function(couponData) {
    console.log('signup.js: 쿠폰 검증 성공 후 처리', couponData);
    
    // 고객 정보 자동 입력
    if (couponData.customerInfo && couponData.customerInfo.name) {
        const nameField = document.getElementById('name');
        if (nameField && !nameField.value) { // 기존 값이 없을 때만 자동 입력
            nameField.value = couponData.customerInfo.name;
            console.log('고객명 자동 입력:', couponData.customerInfo.name);
        }
    }
    
    // 폼 활성화 (validateCoupon.js의 FormControl 사용)
    if (window.CouponValidation && window.CouponValidation.FormControl) {
        window.CouponValidation.FormControl.enable();
    }
    isFormDisabled = false;
    
    console.log('signup.js: 폼 활성화 완료');
};

window.SignupModule.onCouponValidationError = function(errorMessage) {
    console.log('signup.js: 쿠폰 검증 실패 후 처리', errorMessage);
    
    // 폼 비활성화 (validateCoupon.js의 FormControl 사용)
    if (window.CouponValidation && window.CouponValidation.FormControl) {
        window.CouponValidation.FormControl.disable();
    }
    isFormDisabled = true;
    
    console.log('signup.js: 폼 비활성화 완료');
};

// 하위 호환성을 위한 함수들 (validateCoupon.js와 중복 방지)
function disableForm() {
    if (window.CouponValidation && window.CouponValidation.FormControl) {
        window.CouponValidation.FormControl.disable();
    }
}

function enableForm() {
    if (window.CouponValidation && window.CouponValidation.FormControl) {
        window.CouponValidation.FormControl.enable();
    }
}

// 확인 모달 표시 함수
// 확인 모달 표시 함수
function showConfirmModal(formData) {
    const confirmPopupOverlay = document.getElementById('confirm-popup-overlay');
    const confirmDetails = document.getElementById('confirm-details');
    
    if (!confirmPopupOverlay || !confirmDetails) return;
    
    let companionsHTML = '';
    if (formData.companions && formData.companions.length > 0) {
        companionsHTML = '<div class="detail-item"><span class="detail-label">동반자:</span></div>';
        formData.companions.forEach((companion, index) => {
            companionsHTML += `
                <div class="detail-item" style="padding-left: 20px;">
                    <span class="detail-label">${index + 1}. ${companion.name}</span>
                    <span class="detail-value">${companion.phone}</span>
                </div>
            `;
        });
    }
    
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
            <span class="detail-value">${CommonUtils.formatDateTime(formData.teeTime)}</span>
        </div>
        ${companionsHTML}
        <div class="detail-item">
            <span class="detail-label">쿠폰번호:</span>
            <span class="detail-value">${formData.couponNumber}</span>
        </div>
    `;
    
    confirmDetails.innerHTML = details;
    confirmPopupOverlay.classList.add('show');
}

// 폼 초기화 함수
// 폼 초기화 함수
function resetForm() {
    const fields = ['name', 'phone', 'golf-course', 'tee-time'];
    fields.forEach(id => {
        const field = document.getElementById(id);
        if (field) field.value = '';
    });
    
    const checkbox = document.getElementById('terms-checkbox');
    if (checkbox) checkbox.checked = false;
    
    // 동반자 필드 모두 제거
    const companionsContainer = document.getElementById('companions-container');
    if (companionsContainer) {
        companionsContainer.innerHTML = '';
    }
    companionCount = 0;
    
    // 동반자 추가 버튼 활성화
    const addBtn = document.getElementById('add-companion');
    if (addBtn) {
        addBtn.disabled = false;
        addBtn.style.opacity = '1';
        addBtn.style.cursor = 'pointer';
    }
}

// 실제 가입 처리 함수
// 실제 가입 처리 함수
function processSignup(formData) {
    const submitData = new FormData();
    submitData.append('couponNumber', formData.couponNumber);
    submitData.append('name', formData.name);
    submitData.append('phone', formData.phone);
    submitData.append('golfCourse', formData.golfCourse);
    submitData.append('teeTime', formData.teeTime);
    submitData.append('termsAgreed', formData.termsAgreed);
    
    // 동반자 정보 추가
    if (formData.companions && formData.companions.length > 0) {
        submitData.append('companions', JSON.stringify(formData.companions));
    }
    
    // 서버 주소: api/customer/sinupAce.php
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
        const confirmPopupOverlay = document.getElementById('confirm-popup-overlay');
        if (confirmPopupOverlay) {
            confirmPopupOverlay.classList.remove('show');
        }
        
        // 성공 팝업 표시
        showSuccessPopup('가입 완료', '홀인원 보험 가입이 완료되었습니다. 즐거운 라운딩 되세요!');
    })
    .catch(error => {
        // 확인 모달 닫기
        const confirmPopupOverlay = document.getElementById('confirm-popup-overlay');
        if (confirmPopupOverlay) {
            confirmPopupOverlay.classList.remove('show');
        }
        
        console.error('데이터 전송 중 오류가 발생했습니다:', error);
        CommonUtils.showPopup('오류 발생', '서버와의 통신 중 문제가 발생했습니다. 다시 시도해 주세요.');
    });
}

// 성공 팝업 (가입내역확인 버튼 포함)
function showSuccessPopup(title, message) {
    const popupOverlay = document.getElementById('popup-overlay');
    const popupTitle = document.getElementById('popup-title');
    const popupMessage = document.getElementById('popup-message');
    const popupButton = document.getElementById('popup-button');
    
    if (!popupOverlay || !popupTitle || !popupMessage) return;
    
    popupTitle.textContent = title;
    popupMessage.innerHTML = message + '<br><br>가입내역을 확인하시겠습니까?';
    
    // 기존 버튼 숨기고 새 버튼들 추가
    if (popupButton) popupButton.style.display = 'none';
    
    const buttonContainer = document.createElement('div');
    buttonContainer.className = 'confirm-buttons';
    buttonContainer.innerHTML = `
        <button class="popup-button secondary" id="success-close">닫기</button>
        <button class="popup-button primary" id="success-history">가입내역확인</button>
    `;
    
    popupMessage.parentNode.appendChild(buttonContainer);
    popupOverlay.classList.add('show');
    
    // 버튼 이벤트 등록 (중복 방지)
    const successCloseBtn = document.getElementById('success-close');
    const successHistoryBtn = document.getElementById('success-history');
    
    if (successCloseBtn && !successCloseBtn.dataset.eventAdded) {
        successCloseBtn.addEventListener('click', function() {
            closeSuccessPopup();
            resetForm();
        });
        successCloseBtn.dataset.eventAdded = 'true';
    }
    
    if (successHistoryBtn && !successHistoryBtn.dataset.eventAdded) {
        successHistoryBtn.addEventListener('click', function() {
            closeSuccessPopup();
            resetForm();
            // 가입내역 페이지로 이동
            window.location.href = 'history.html';
        });
        successHistoryBtn.dataset.eventAdded = 'true';
    }
}

// 성공 팝업 닫기 함수
function closeSuccessPopup() {
    const popupOverlay = document.getElementById('popup-overlay');
    const popupButton = document.getElementById('popup-button');
    
    if (popupOverlay) popupOverlay.classList.remove('show');
    
    // 추가된 버튼 제거하고 원래 버튼 복원
    const buttonContainer = document.querySelector('.confirm-buttons');
    if (buttonContainer) {
        buttonContainer.remove();
    }
    if (popupButton) popupButton.style.display = 'block';
}

// 폼 검증 함수
// 폼 검증 함수
function validateForm() {
    const couponNumber = document.getElementById('coupon-number').value.trim();
    const name = document.getElementById('name').value.trim();
    const phone = document.getElementById('phone').value.trim();
    const golfCourse = document.getElementById('golf-course').value.trim();
    const teeTime = document.getElementById('tee-time').value;
    const termsCheckbox = document.getElementById('terms-checkbox').checked;
    
    // 필수 입력 검증
    if (!name || !phone || !golfCourse || !teeTime) {
        CommonUtils.showPopup('입력 오류', '모든 필수 항목을 입력해주세요.');
        return null;
    }
    
    if (!termsCheckbox) {
        CommonUtils.showPopup('약관 동의', '약관 및 개인정보 활용 동의가 필요합니다.');
        return null;
    }
    
    if (!couponNumber) {
        CommonUtils.showPopup('쿠폰 번호 오류', '유효한 쿠폰 번호가 필요합니다.');
        return null;
    }
    
    // 동반자 정보 수집 추가
    const companions = [];
    const companionItems = document.querySelectorAll('.companion-item');
    
    for (let i = 0; i < companionItems.length; i++) {
        const companionId = i + 1;
        const companionName = document.getElementById(`companion-name-${companionId}`)?.value.trim();
        const companionPhone = document.getElementById(`companion-phone-${companionId}`)?.value.trim();
        
        if (companionName && companionPhone) {
            companions.push({
                name: companionName,
                phone: companionPhone
            });
        } else if (companionName || companionPhone) {
            // 하나만 입력된 경우
            CommonUtils.showPopup('입력 오류', `동반자 ${companionId}의 정보를 모두 입력해주세요.`);
            return null;
        }
    }
    
    return {
        couponNumber,
        name,
        phone,
        golfCourse,
        teeTime,
        termsAgreed: termsCheckbox,
        companions: companions // 동반자 정보 추가
    };
}
// 동반자 추가 함수
function addCompanion() {
    if (companionCount >= MAX_COMPANIONS) {
        CommonUtils.showPopup('알림', `최대 ${MAX_COMPANIONS}명의 동반자만 추가할 수 있습니다.`);
        return;
    }
    
    companionCount++;
    const companionsContainer = document.getElementById('companions-container');
    
    const companionItem = document.createElement('div');
    companionItem.className = 'companion-item';
    companionItem.dataset.companionId = companionCount;
    
    companionItem.innerHTML = `
        <div class="companion-header">
            <span class="companion-title">동반자 ${companionCount}</span>
            <button type="button" class="remove-companion" onclick="removeCompanion(${companionCount})">✕</button>
        </div>
        <div class="companion-fields">
            <input type="text" class="form-control" placeholder="이름" id="companion-name-${companionCount}" required>
            <input type="tel" class="form-control" placeholder="휴대폰번호" id="companion-phone-${companionCount}" required>
        </div>
    `;
    
    companionsContainer.appendChild(companionItem);
    
    // 휴대폰 번호 포맷팅 설정
    const phoneInput = companionItem.querySelector(`#companion-phone-${companionCount}`);
    if (phoneInput) {
        phoneInput.addEventListener('input', function(e) {
            let value = this.value.replace(/[^\d]/g, '');
            
            if (value.length <= 3) {
                this.value = value;
            } else if (value.length <= 7) {
                this.value = value.slice(0, 3) + '-' + value.slice(3);
            } else {
                this.value = value.slice(0, 3) + '-' + value.slice(3, 7) + '-' + value.slice(7, 11);
            }
        });
    }
    
    // 최대 동반자 수 도달 시 추가 버튼 비활성화
    if (companionCount >= MAX_COMPANIONS) {
        const addBtn = document.getElementById('add-companion');
        if (addBtn) {
            addBtn.disabled = true;
            addBtn.style.opacity = '0.5';
            addBtn.style.cursor = 'not-allowed';
        }
    }
    
    console.log(`동반자 ${companionCount} 추가됨`);
}

// 동반자 제거 함수
function removeCompanion(companionId) {
    const companionItem = document.querySelector(`.companion-item[data-companion-id="${companionId}"]`);
    if (companionItem) {
        companionItem.remove();
        companionCount--;
        
        // 동반자 번호 재정렬
        const remainingCompanions = document.querySelectorAll('.companion-item');
        remainingCompanions.forEach((item, index) => {
            const newId = index + 1;
            item.dataset.companionId = newId;
            
            const title = item.querySelector('.companion-title');
            if (title) title.textContent = `동반자 ${newId}`;
            
            const nameInput = item.querySelector('input[type="text"]');
            const phoneInput = item.querySelector('input[type="tel"]');
            
            if (nameInput) nameInput.id = `companion-name-${newId}`;
            if (phoneInput) phoneInput.id = `companion-phone-${newId}`;
            
            const removeBtn = item.querySelector('.remove-companion');
            if (removeBtn) removeBtn.setAttribute('onclick', `removeCompanion(${newId})`);
        });
        
        companionCount = remainingCompanions.length;
        
        // 추가 버튼 다시 활성화
        const addBtn = document.getElementById('add-companion');
        if (addBtn && companionCount < MAX_COMPANIONS) {
            addBtn.disabled = false;
            addBtn.style.opacity = '1';
            addBtn.style.cursor = 'pointer';
        }
        
        console.log(`동반자 제거됨. 현재 동반자 수: ${companionCount}`);
    }
}
// 이벤트 리스너 설정 (중복 방지)
function setupEventListeners() {
    // 가입 신청 버튼
    const signupButton = document.getElementById('signup-button');
    if (signupButton && !signupButton.dataset.eventAdded) {
        signupButton.addEventListener('click', function() {
            if (isFormDisabled) {
                CommonUtils.showPopup('오류', '쿠폰 검증이 완료되지 않았습니다.');
                return;
            }
            
            const formData = validateForm();
            if (formData) {
                showConfirmModal(formData);
            }
        });
        signupButton.dataset.eventAdded = 'true';
        console.log('가입 신청 버튼 이벤트 리스너 등록 완료');
    }
    
    // 확인 모달 취소 버튼
    const confirmCancelButton = document.getElementById('confirm-cancel-button');
    if (confirmCancelButton && !confirmCancelButton.dataset.eventAdded) {
        confirmCancelButton.addEventListener('click', function() {
            const confirmPopupOverlay = document.getElementById('confirm-popup-overlay');
            if (confirmPopupOverlay) {
                confirmPopupOverlay.classList.remove('show');
            }
        });
        confirmCancelButton.dataset.eventAdded = 'true';
        console.log('확인 모달 취소 버튼 이벤트 리스너 등록 완료');
    }
    
    // 확인 모달 확인 버튼
    const confirmSubmitButton = document.getElementById('confirm-submit-button');
    if (confirmSubmitButton && !confirmSubmitButton.dataset.eventAdded) {
        confirmSubmitButton.addEventListener('click', function() {
            const formData = validateForm();
            if (formData) {
                processSignup(formData);
            }
        });
        confirmSubmitButton.dataset.eventAdded = 'true';
        console.log('확인 모달 확인 버튼 이벤트 리스너 등록 완료');
    }
	
	// 동반자 추가 버튼 이벤트 추가
    const addCompanionBtn = document.getElementById('add-companion');
    if (addCompanionBtn && !addCompanionBtn.dataset.eventAdded) {
        addCompanionBtn.addEventListener('click', addCompanion);
        addCompanionBtn.dataset.eventAdded = 'true';
        console.log('동반자 추가 버튼 이벤트 리스너 등록 완료');
    }
}

// 페이지 초기화
document.addEventListener('DOMContentLoaded', function() {
    console.log('signup.js: 가입 신청 페이지 로드 완료');
    
    // 초기 상태: 폼 비활성화
    disableForm();
    
    // 이벤트 리스너 설정 (중복 방지)
    setupEventListeners();
    
    // validateCoupon.js와의 연동 확인
    if (window.CouponValidation) {
        console.log('signup.js: validateCoupon.js와 연동 확인됨');
        
        // 추가 콜백 등록 (선택사항)
        window.CouponValidation.onSuccess(function(couponData) {
            console.log('signup.js: 추가 성공 콜백 실행', couponData);
        });
        
        window.CouponValidation.onError(function(errorMessage) {
            console.log('signup.js: 추가 오류 콜백 실행', errorMessage);
        });
    } else {
        console.warn('signup.js: validateCoupon.js가 로드되지 않았거나 CouponValidation 객체를 찾을 수 없습니다.');
    }
    
    console.log('signup.js: 쿠폰 검증은 validateCoupon.js에서 처리합니다.');
});