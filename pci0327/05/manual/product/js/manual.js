// 매뉴얼 콘텐츠 관리 클래스
class ManualManager {
    constructor() {
        this.menuButtons = document.querySelectorAll('.menu-card');
        this.initializeEventListeners();
    }

    // 이벤트 리스너 초기화
    initializeEventListeners() {
        this.menuButtons.forEach(button => {
            button.addEventListener('click', (e) => {
                const menuText = e.currentTarget.querySelector('.menu-title').textContent;
                this.navigateToPage(menuText);
            });
        });
    }

    // 페이지 이동
    navigateToPage(menuText) {
        const pageMap = {
            '대리운전': '/05/manual/driver/index.html',
            '시간제보험': '/05/manual/time/index.html',
            '현장실습보험': '/05/manual/field/index.html',
            '여행자보험': '/05/manual/travel/index.html'
        };

        const targetUrl = pageMap[menuText];
        if (targetUrl) {
            window.location.href = targetUrl;
        } else {
            console.error('알 수 없는 메뉴:', menuText);
        }
    }
}

// 페이지 로드 시 매뉴얼 매니저 초기화
document.addEventListener('DOMContentLoaded', () => {
    const manualManager = new ManualManager();
}); 