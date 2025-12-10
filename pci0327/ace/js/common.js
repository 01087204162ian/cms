// 공통 JavaScript 함수들

// 현재 페이지 확인
function getCurrentPage() {
    const path = window.location.pathname;
    if (path.includes('claim.html')) return 'claim';
    if (path.includes('history.html')) return 'history';
    if (path.includes('faq.html')) return 'faq';
    if (path.includes('terms.html')) return 'terms';
    return 'signup';
}

// 네비게이션 활성화
function setActiveNavigation() {
    const currentPage = getCurrentPage();
    const navButtons = document.querySelectorAll('.nav-tabs button');
    
    navButtons.forEach(button => {
        button.classList.remove('active');
        const buttonId = button.id;
        
        if ((currentPage === 'signup' && buttonId === 'nav-signup') ||
            (currentPage === 'claim' && buttonId === 'nav-claim') ||
            (currentPage === 'history' && buttonId === 'nav-history') ||
            (currentPage === 'faq' && buttonId === 'nav-faq') ||
            (currentPage === 'terms' && buttonId === 'nav-terms')) {
            button.classList.add('active');
        }
    });
}

// 네비게이션 이벤트 설정
function setupNavigation() {
    const baseUrl = window.location.origin + window.location.pathname.split('/').slice(0, -1).join('/') + '/';
    const currentParams = new URLSearchParams(window.location.search);
    
    document.getElementById('nav-signup')?.addEventListener('click', function() {
        const url = currentParams.get('coupon') ? 
            `${baseUrl}index.html?coupon=${currentParams.get('coupon')}` : 
            `${baseUrl}index.html`;
        window.location.href = url;
    });

    document.getElementById('nav-claim')?.addEventListener('click', function() {
        window.location.href = `${baseUrl}claim.html`;
    });

    document.getElementById('nav-history')?.addEventListener('click', function() {
        window.location.href = `${baseUrl}history.html`;
    });

    document.getElementById('nav-faq')?.addEventListener('click', function() {
        window.location.href = `${baseUrl}faq.html`;
    });

    document.getElementById('nav-terms')?.addEventListener('click', function() {
        window.location.href = `${baseUrl}terms.html`;
    });
}

// 팝업 기능
const popupOverlay = document.getElementById('popup-overlay');
const popupTitle = document.getElementById('popup-title');
const popupMessage = document.getElementById('popup-message');
const popupButton = document.getElementById('popup-button');

function showPopup(title, message) {
    if (popupTitle && popupMessage && popupOverlay) {
        popupTitle.textContent = title;
        popupMessage.textContent = message;
        popupOverlay.classList.add('show');
    }
}

function hidePopup() {
    if (popupOverlay) {
        popupOverlay.classList.remove('show');
    }
}

// 팝업 닫기 이벤트
if (popupButton) {
    popupButton.addEventListener('click', hidePopup);
}

// ESC 키로 팝업 닫기
document.addEventListener('keydown', function(e) {
    if (e.key === 'Escape') {
        hidePopup();
    }
});

// 휴대폰 번호 포맷팅
function formatPhoneNumber(phone) {
    if (!phone) return '';
    
    const cleaned = phone.replace(/[^\d]/g, '');
    
    if (cleaned.length === 11) {
        return cleaned.replace(/(\d{3})(\d{4})(\d{4})/, '$1-$2-$3');
    } else if (cleaned.length === 10) {
        return cleaned.replace(/(\d{3})(\d{3})(\d{4})/, '$1-$2-$3');
    }
    
    return phone;
}

// 휴대폰 번호 입력 필드 자동 포맷팅
function setupPhoneFormatting() {
    const phoneInputs = document.querySelectorAll('input[type="tel"]');
    
    phoneInputs.forEach(input => {
        input.addEventListener('input', function(e) {
            let value = this.value.replace(/[^\d]/g, '');
            
            if (value.length <= 3) {
                this.value = value;
            } else if (value.length <= 7) {
                this.value = value.slice(0, 3) + '-' + value.slice(3);
            } else {
                this.value = value.slice(0, 3) + '-' + value.slice(3, 7) + '-' + value.slice(7, 11);
            }
        });
    });
}

// 날짜/시간 포맷팅
function formatDateTime(dateTimeString) {
    if (!dateTimeString) return 'N/A';
    
    const date = new Date(dateTimeString);
    if (isNaN(date.getTime())) return 'N/A';
    
    const year = date.getFullYear();
    const month = String(date.getMonth() + 1).padStart(2, '0');
    const day = String(date.getDate()).padStart(2, '0');
    const hours = String(date.getHours()).padStart(2, '0');
    const minutes = String(date.getMinutes()).padStart(2, '0');
    
    return `${year}년 ${month}월 ${day}일 ${hours}:${minutes}`;
}

// URL 파라미터 가져오기
function getURLParameter(name) {
    const urlParams = new URLSearchParams(window.location.search);
    return urlParams.get(name);
}

// 로딩 표시
function showLoading(element, text = '처리 중...') {
    if (element) {
        element.disabled = true;
        element.dataset.originalText = element.textContent;
        element.innerHTML = `<span class="loading-spinner"></span> ${text}`;
    }
}

function hideLoading(element) {
    if (element && element.dataset.originalText) {
        element.disabled = false;
        element.textContent = element.dataset.originalText;
        delete element.dataset.originalText;
    }
}

// 공통 초기화
document.addEventListener('DOMContentLoaded', function() {
    console.log('공통 스크립트 로드 완료');
    
    // 네비게이션 설정
    setupNavigation();
    setActiveNavigation();
    
    // 휴대폰 번호 포맷팅 설정
    setupPhoneFormatting();
    
    // 팝업 오버레이 클릭 시 닫기
    if (popupOverlay) {
        popupOverlay.addEventListener('click', function(e) {
            if (e.target === popupOverlay) {
                hidePopup();
            }
        });
    }
});

// 전역 함수로 노출
window.CommonUtils = {
    getCurrentPage,
    setActiveNavigation,
    setupNavigation,
    showPopup,
    hidePopup,
    formatPhoneNumber,
    formatDateTime,
    getURLParameter,
    showLoading,
    hideLoading
};