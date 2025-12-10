/**
 * validateCoupon.js
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
            // 디버깅: 받은 데이터 구조 확인
            console.log('받은 API 응답:', data);
            console.log('data.data:', data.data);
            console.log('couponStats:', data.data?.couponStats);
            
            // 검증 성공 - couponStats 포함하여 데이터 전달
            const responseData = data.data || data;
            
            const couponData = {
                couponNumber: responseData.couponNumber || data.couponNumber,
                inputCoupon: responseData.inputCoupon || trimmedCoupon,
                clientName: responseData.clientName || data.clientName,
                clientId: responseData.clientId || data.clientId,
                customerInfo: responseData.customerInfo || {},
                couponStats: responseData.couponStats || {}, // 새로 추가
                expiryDate: responseData.expiryDate,
                isVipCustomer: responseData.customerInfo?.isVipCustomer || responseData.isVipCustomer || false,
                availableSlots: responseData.availableSlots || 1,
                usedCount: responseData.usedCount || 0,
                maxUsageCount: responseData.maxUsageCount || 1,
                remainingUsage: responseData.remainingUsage || 1,
                restrictions: responseData.restrictions || {}
            };
            
            console.log('전달할 couponData:', couponData);
            onSuccess(couponData);
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
/**
 * 쿠폰 검증 상태를 UI에 표시하는 헬퍼 함수들
 */
const CouponUI = {
    showLoading: function(isLoading) {
        const loadingEl = document.getElementById('verification-loading');
        const successEl = document.getElementById('verification-success');
        const errorEl = document.getElementById('verification-error');
        
        if (isLoading) {
            if (loadingEl) loadingEl.style.display = 'flex';
            if (successEl) successEl.style.display = 'none';
            if (errorEl) errorEl.style.display = 'none';
            
            console.log('🔄 쿠폰 검증 중...');
        }
    },

    showSuccess: function(couponData) {
        const loadingEl = document.getElementById('verification-loading');
        const successEl = document.getElementById('verification-success');
        const errorEl = document.getElementById('verification-error');
        
        if (loadingEl) loadingEl.style.display = 'none';
        if (successEl) successEl.style.display = 'flex';
        if (errorEl) errorEl.style.display = 'none';
        
        // 디버깅: 받은 couponData 확인
        console.log('CouponUI.showSuccess에서 받은 데이터:', couponData);
        
        // 1. 배너 제목 업데이트
        const bannerTitle = document.getElementById('banner-title');
        if (bannerTitle && couponData.clientName) {
            bannerTitle.textContent = `${couponData.clientName} VIP 고객님을 위한`;
            console.log('배너 제목 업데이트:', `${couponData.clientName} VIP 고객님을 위한`);
        }
        
        // 2. 잔여수량 업데이트 (새로 추가)
        const couponStatsEl = document.getElementById('coupon-stats');
        if (couponStatsEl && couponData.couponStats) {
            const { unusedCoupons, totalCoupons } = couponData.couponStats;
            
            if (unusedCoupons !== undefined && totalCoupons !== undefined) {
                couponStatsEl.textContent = `${unusedCoupons}/${totalCoupons}`;
                console.log('잔여수량 업데이트:', `${unusedCoupons}/${totalCoupons}`);
            } else {
                console.log('couponStats 데이터가 불완전합니다:', couponData.couponStats);
            }
        } else {
            console.log('coupon-stats 요소를 찾을 수 없거나 couponStats 데이터가 없습니다');
        }
        
        // 3. 쿠폰 입력 필드 스타일링
        const couponField = document.getElementById('coupon-number');
        if (couponField) {
            couponField.style.borderColor = '#4caf50';
            couponField.style.backgroundColor = '#f8fff8';
        }
        
        console.log('✅ 쿠폰 검증 성공');
    },

    showError: function(errorMessage) {
        const loadingEl = document.getElementById('verification-loading');
        const successEl = document.getElementById('verification-success');
        const errorEl = document.getElementById('verification-error');
        const errorMessageEl = document.getElementById('error-message');
        
        if (loadingEl) loadingEl.style.display = 'none';
        if (successEl) successEl.style.display = 'none';
        if (errorEl) errorEl.style.display = 'flex';
        
        // 오류 메시지 설정
        if (errorMessageEl) {
            errorMessageEl.textContent = errorMessage || '쿠폰을 확인할 수 없습니다';
        }
        
        // 쿠폰 입력 필드 스타일링
        const couponField = document.getElementById('coupon-number');
        if (couponField) {
            couponField.style.borderColor = '#f44336';
            couponField.style.backgroundColor = '#fff8f8';
        }
        
        console.log('❌ 쿠폰 검증 실패:', errorMessage);
    },

    reset: function() {
        const loadingEl = document.getElementById('verification-loading');
        const successEl = document.getElementById('verification-success');
        const errorEl = document.getElementById('verification-error');
        
        if (loadingEl) loadingEl.style.display = 'none';
        if (successEl) successEl.style.display = 'none';
        if (errorEl) errorEl.style.display = 'none';
        
        // 배너 제목을 기본값으로 리셋
        const bannerTitle = document.getElementById('banner-title');
        if (bannerTitle) {
            bannerTitle.textContent = 'VIP 고객님을 위한';
        }
        
        // 잔여수량을 기본값으로 리셋 (새로 추가)
        const couponStatsEl = document.getElementById('coupon-stats');
        if (couponStatsEl) {
            couponStatsEl.textContent = '2000/10000';
        }
        
        // 쿠폰 입력 필드 초기화
        const couponField = document.getElementById('coupon-number');
        if (couponField) {
            couponField.style.borderColor = '#ddd';
            couponField.style.backgroundColor = '#f9f9f9';
        }
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