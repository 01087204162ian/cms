/**
 * 쿠폰 번호 사전 검증 함수 (형식 제약 최소화)
 * @param {string} couponNumber - 검증할 쿠폰 번호
 * @param {function} onSuccess - 검증 성공 시 실행될 콜백 함수
 * @param {function} onError - 검증 실패 시 실행될 콜백 함수
 * @param {function} onLoading - 로딩 상태 변경 시 실행될 콜백 함수
 */
function validateCouponNumber(couponNumber, onSuccess, onError, onLoading) {
    // 입력값 검증
    if (!couponNumber || typeof couponNumber !== 'string') {
        onError('유효하지 않은 쿠폰 번호입니다.');
        return;
    }

    // 기본적인 클라이언트 사이드 검증 (최소한의 검증만)
    const trimmedCoupon = couponNumber.trim();
    
    // 1. 빈 값 체크
    if (!trimmedCoupon) {
        onError('쿠폰 번호를 입력해주세요.');
        return;
    }
    
    // 2. 길이 체크 (너무 짧거나 긴 것만 제외)
    if (trimmedCoupon.length < 3 || trimmedCoupon.length > 50) {
        onError('쿠폰 번호 길이가 올바르지 않습니다.');
        return;
    }
    
    // 3. 위험한 문자 체크 (보안)
    if (/['";\\]/.test(trimmedCoupon)) {
        onError('유효하지 않은 문자가 포함되어 있습니다.');
        return;
    }
    
    // 4. 기본적인 문자 체크 (영문, 숫자, 일부 특수문자만)
    if (!/^[A-Za-z0-9\-_#@]+$/.test(trimmedCoupon)) {
        onError('쿠폰 번호에 사용할 수 없는 문자가 있습니다.');
        return;
    }

    // 로딩 시작
    if (onLoading) onLoading(true);

    // 서버에 쿠폰 검증 요청
    fetch('api/customer/validateCoupon.php', {
        method: 'POST',
        headers: {
            'Content-Type': 'application/json',
        },
        body: JSON.stringify({
            couponNumber: trimmedCoupon
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
        if (onLoading) onLoading(false);

        if (data.success) {
            // 검증 성공
            const responseData = data.data || data;
            
            onSuccess({
                couponNumber: responseData.couponNumber || data.couponNumber,
                inputCoupon: responseData.inputCoupon || trimmedCoupon,
                customerInfo: responseData.customerInfo || {},
                expiryDate: responseData.expiryDate,
                isVipCustomer: responseData.customerInfo?.isVipCustomer || responseData.isVipCustomer || false,
                availableSlots: responseData.availableSlots || 1,
                restrictions: responseData.restrictions || {}
            });
        } else {
            // 검증 실패
            const errorMessage = getErrorMessage(data.errorCode, data.message);
            onError(errorMessage);
        }
    })
    .catch(error => {
        // 로딩 종료
        if (onLoading) onLoading(false);
        
        console.error('쿠폰 검증 중 오류 발생:', error);
        onError('서버와의 통신 중 문제가 발생했습니다. 잠시 후 다시 시도해주세요.');
    });
}

/**
 * 에러 코드에 따른 사용자 친화적 메시지 반환
 */
function getErrorMessage(errorCode, defaultMessage) {
    const errorMessages = {
        'COUPON_NOT_FOUND': '존재하지 않는 쿠폰 번호입니다.',
        'COUPON_EXPIRED': '만료된 쿠폰입니다.',
        'COUPON_ALREADY_USED': '이미 사용된 쿠폰입니다.',
        'NOT_VIP_CUSTOMER': 'VIP 고객 전용 쿠폰입니다.',
        'COUPON_SUSPENDED': '일시적으로 사용이 중단된 쿠폰입니다.',
        'INVALID_FORMAT': '유효하지 않은 쿠폰 번호입니다.',
        'SERVER_ERROR': '서버 오류가 발생했습니다. 고객센터로 문의해주세요.',
        'MISSING_COUPON': '쿠폰 번호가 필요합니다.',
        'DATABASE_ERROR': '데이터베이스 오류가 발생했습니다.'
    };

    return errorMessages[errorCode] || defaultMessage || '알 수 없는 오류가 발생했습니다.';
}

/**
 * 쿠폰 검증 상태를 UI에 표시하는 헬퍼 함수들
 */
const CouponUI = {
    showLoading: function(isLoading) {
        const couponField = document.getElementById('coupon-number');
        const loadingElement = document.getElementById('coupon-loading') || this.createLoadingElement();
        
        if (isLoading) {
            couponField.style.borderColor = '#1974E8';
            couponField.style.backgroundColor = '#f8f9fa';
            loadingElement.style.display = 'inline-block';
        } else {
            loadingElement.style.display = 'none';
        }
    },

    showSuccess: function(couponData) {
        const couponField = document.getElementById('coupon-number');
        const messageElement = this.getMessageElement();
        
        couponField.style.borderColor = '#28a745';
        couponField.style.backgroundColor = '#f8fff9';
        
        let successMessage = '✅ 유효한 쿠폰입니다.';
        if (couponData.customerInfo && couponData.customerInfo.name) {
            successMessage += ` (${couponData.customerInfo.name}님)`;
        }
        
        messageElement.textContent = successMessage;
        messageElement.style.color = '#28a745';
        messageElement.style.display = 'block';
    },

    showError: function(errorMessage) {
        const couponField = document.getElementById('coupon-number');
        const messageElement = this.getMessageElement();
        
        couponField.style.borderColor = '#dc3545';
        couponField.style.backgroundColor = '#fff8f8';
        
        messageElement.textContent = '❌ ' + errorMessage;
        messageElement.style.color = '#dc3545';
        messageElement.style.display = 'block';
    },

    reset: function() {
        const couponField = document.getElementById('coupon-number');
        const messageElement = this.getMessageElement();
        const loadingElement = document.getElementById('coupon-loading');
        
        couponField.style.borderColor = '#ddd';
        couponField.style.backgroundColor = '#f9f9f9';
        messageElement.style.display = 'none';
        if (loadingElement) loadingElement.style.display = 'none';
    },

    getMessageElement: function() {
        let messageElement = document.getElementById('coupon-message');
        if (!messageElement) {
            messageElement = document.createElement('div');
            messageElement.id = 'coupon-message';
            messageElement.style.fontSize = '12px';
            messageElement.style.marginTop = '5px';
            messageElement.style.display = 'none';
            
            const couponField = document.getElementById('coupon-number');
            if (couponField && couponField.parentNode) {
                couponField.parentNode.appendChild(messageElement);
            }
        }
        return messageElement;
    },

    createLoadingElement: function() {
        const loadingElement = document.createElement('span');
        loadingElement.id = 'coupon-loading';
        loadingElement.innerHTML = '🔄 검증 중...';
        loadingElement.style.fontSize = '12px';
        loadingElement.style.color = '#1974E8';
        loadingElement.style.marginLeft = '10px';
        loadingElement.style.display = 'none';
        
        const couponField = document.getElementById('coupon-number');
        if (couponField && couponField.parentNode) {
            couponField.parentNode.appendChild(loadingElement);
        }
        
        return loadingElement;
    }
};

/**
 * 폼 비활성화/활성화 함수들
 */
function disableForm() {
    const formElements = ['name', 'phone', 'golf-course', 'tee-time', 'terms-checkbox', 'signup-button'];
    formElements.forEach(id => {
        const element = document.getElementById(id);
        if (element) element.disabled = true;
    });
    
    const addCompanionBtn = document.getElementById('add-companion');
    if (addCompanionBtn) {
        addCompanionBtn.disabled = true;
        addCompanionBtn.style.opacity = '0.5';
        addCompanionBtn.style.cursor = 'not-allowed';
    }
}

function enableForm() {
    const formElements = ['name', 'phone', 'golf-course', 'tee-time', 'terms-checkbox', 'signup-button'];
    formElements.forEach(id => {
        const element = document.getElementById(id);
        if (element) element.disabled = false;
    });
    
    const addCompanionBtn = document.getElementById('add-companion');
    if (addCompanionBtn) {
        addCompanionBtn.disabled = false;
        addCompanionBtn.style.opacity = '1';
        addCompanionBtn.style.cursor = 'pointer';
    }
}

/**
 * 중복 실행 방지를 위한 플래그
 */
let couponValidationInitialized = false;

/**
 * 쿠폰 검증 초기화 함수
 */
function initializeCouponValidation() {
    // 중복 실행 방지
    if (couponValidationInitialized) {
        console.log('쿠폰 검증이 이미 초기화되었습니다.');
        return;
    }
    
    console.log('쿠폰 검증 초기화 시작...');
    couponValidationInitialized = true;
    
    // URL에서 쿠폰 번호 가져오기
    const urlParams = new URLSearchParams(window.location.search);
    const couponFromUrl = urlParams.get('coupon');
    
    if (couponFromUrl) {
        const couponField = document.getElementById('coupon-number');
        if (couponField) {
            couponField.value = couponFromUrl;
        }
        
        console.log('쿠폰 검증 시작 - 쿠폰:', couponFromUrl);
        
        validateCouponNumber(
            couponFromUrl,
            function(couponData) {
                CouponUI.showSuccess(couponData);
                enableForm();
                console.log('쿠폰 검증 성공:', couponData);
            },
            function(errorMessage) {
                CouponUI.showError(errorMessage);
                disableForm();
                console.error('쿠폰 검증 실패:', errorMessage);
            },
            function(isLoading) {
                CouponUI.showLoading(isLoading);
            }
        );
    }
}

// 페이지 로드 시 자동 실행 비활성화 (HTML에서 직접 호출하는 경우)
// document.addEventListener('DOMContentLoaded', initializeCouponValidation);