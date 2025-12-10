// 연령별 보험료 데이터를 가져오는 함수
async function fetchPremiumData() {
    try {
        // 실제 서버 엔드포인트로 변경해야 합니다
        const response = await fetch('https://api.example.com/premium-data');
        const data = await response.json();
        updatePremiumTable(data);
    } catch (error) {
        console.error('보험료 데이터를 가져오는데 실패했습니다:', error);
        showErrorMessage();
    }
}

// 보험료 테이블 업데이트 함수
function updatePremiumTable(data) {
    const ageRanges = [
        '26-30', '31-45', '46-50', '51-55', '56-60', '61'
    ];

    ageRanges.forEach(range => {
        const driverElement = document.getElementById(`premium-${range}-driver`);
        const deliveryElement = document.getElementById(`premium-${range}-delivery`);

        if (driverElement && deliveryElement) {
            driverElement.textContent = formatPremium(data[range]?.driver || '-');
            deliveryElement.textContent = formatPremium(data[range]?.delivery || '-');
        }
    });
}

// 보험료 포맷팅 함수
function formatPremium(premium) {
    if (premium === '-') return premium;
    return new Intl.NumberFormat('ko-KR').format(premium) + '원';
}

// 에러 메시지 표시 함수
function showErrorMessage() {
    const premiumTable = document.querySelector('.premium-table');
    const errorMessage = document.createElement('div');
    errorMessage.className = 'error-message';
    errorMessage.textContent = '보험료 정보를 불러오는데 실패했습니다. 잠시 후 다시 시도해주세요.';
    premiumTable.appendChild(errorMessage);
}

// 가입신청 폼 관련 기능
document.addEventListener('DOMContentLoaded', () => {
    // 기존 코드 유지
    fetchPremiumData();

    const mobileMenuBtn = document.querySelector('.mobile-menu-btn');
    const navMenu = document.querySelector('.nav-menu');

    // 폼 관련 요소
    const sameContractorCheckbox = document.getElementById('same-contractor');
    const contractorInfo = document.getElementById('contractor-info');
    const cardPaymentCheckbox = document.getElementById('card-payment');
    const cardInfo = document.getElementById('card-info');
    const applicationForm = document.getElementById('application-form');

    // 계약자 동일 여부 체크박스 이벤트
    sameContractorCheckbox.addEventListener('change', () => {
        contractorInfo.style.display = sameContractorCheckbox.checked ? 'none' : 'block';
        const contractorInputs = contractorInfo.querySelectorAll('input');
        contractorInputs.forEach(input => {
            input.required = !sameContractorCheckbox.checked;
        });
    });

    // 카드결제 체크박스 이벤트
    cardPaymentCheckbox.addEventListener('change', () => {
        cardInfo.style.display = cardPaymentCheckbox.checked ? 'block' : 'none';
        const cardInputs = cardInfo.querySelectorAll('input');
        cardInputs.forEach(input => {
            input.required = cardPaymentCheckbox.checked;
        });
    });

    // 폼 제출 이벤트
    applicationForm.addEventListener('submit', async (e) => {
        e.preventDefault();
        
        // 폼 데이터 수집
        const formData = new FormData(applicationForm);
        const data = Object.fromEntries(formData.entries());

        try {
            // 실제 API 엔드포인트로 변경해야 합니다
            const response = await fetch('https://api.example.com/insurance-application', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                },
                body: JSON.stringify(data)
            });

            if (response.ok) {
                alert('가입신청이 완료되었습니다.');
                applicationForm.reset();
            } else {
                throw new Error('가입신청에 실패했습니다.');
            }
        } catch (error) {
            alert(error.message);
        }
    });

    // 모바일 메뉴 토글
    mobileMenuBtn.addEventListener('click', () => {
        navMenu.classList.toggle('active');
    });

    // 메뉴 항목 클릭 시 모바일 메뉴 닫기
    document.querySelectorAll('.nav-menu a').forEach(link => {
        link.addEventListener('click', () => {
            navMenu.classList.remove('active');
        });
    });

    // 스크롤 시 네비게이션 바 스타일 변경
    window.addEventListener('scroll', () => {
        const navbar = document.querySelector('.navbar');
        if (window.scrollY > 50) {
            navbar.style.backgroundColor = 'rgba(255, 255, 255, 0.95)';
            navbar.style.boxShadow = '0 2px 5px rgba(0,0,0,0.1)';
        } else {
            navbar.style.backgroundColor = '#fff';
            navbar.style.boxShadow = '0 2px 5px rgba(0,0,0,0.1)';
        }
    });

    // 아코디언 기능
    const accordionItems = document.querySelectorAll('.accordion-item');
    
    accordionItems.forEach(item => {
        const header = item.querySelector('.accordion-header');
        
        header.addEventListener('click', () => {
            // 현재 클릭된 아이템이 활성화되어 있는지 확인
            const isActive = item.classList.contains('active');
            
            // 모든 아코디언 아이템 닫기
            accordionItems.forEach(otherItem => {
                otherItem.classList.remove('active');
            });
            
            // 클릭된 아이템이 활성화되어 있지 않았다면 열기
            if (!isActive) {
                item.classList.add('active');
            }
        });
    });
}); 