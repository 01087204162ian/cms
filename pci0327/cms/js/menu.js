// 메뉴 관련 함수들

// 메뉴 초기화
async function initMenuHandlers() {
    // 메뉴 토글 기능
    document.querySelectorAll('.menu-item').forEach(item => {
        item.addEventListener('click', function() {
            const menuId = this.getAttribute('data-menu');
            if (menuId) {
                const submenu = document.getElementById(menuId + '-submenu');
                document.querySelectorAll('.submenu').forEach(menu => {
                    if (menu !== submenu) menu.classList.remove('show');
                });
                submenu.classList.toggle('show');
                updateMenuState(this);
            }
        });
    });

    // 서브메뉴 클릭 처리
    document.querySelectorAll('.submenu a').forEach(link => {
        link.addEventListener('click', async function(e) {
            e.preventDefault();
            const menuItem = this.closest('.submenu').previousElementSibling;
            const menuId = menuItem.getAttribute('data-menu');
            const type = this.getAttribute('data-type');
            
            updateMenuState(menuItem, this);
            
            if (menuId === 'field-practice' && type === 'apply') {
                await loadFieldPracticeList();
            } else {
                loadList(type);
            }
        });
    });

    // 첫 번째 메뉴 자동 선택 (지연 추가)
    setTimeout(async () => {
        const firstMenu = document.querySelector('.menu-item');
        if (firstMenu) {
            firstMenu.click();
            const firstSubmenu = firstMenu.nextElementSibling?.querySelector('a');
            if (firstSubmenu) {
                firstSubmenu.click();
            }
        }
    }, 100);
}

// 리스트 로드 함수
function loadList(type) {
    const currentMenu = document.getElementById('current-menu').textContent;
    const listContent = document.getElementById('list-content');
    
    // 메뉴 ID 가져오기
    const menuId = document.querySelector('.menu-item.active').getAttribute('data-menu');
    
    // 현장실습보험 메뉴 특별 처리
    if (menuId === 'field-practice') {
        loadFieldPracticeList();
        return;
    }
    
    // 나머지 메뉴들은 기존 방식대로 처리
    const pagePath = `/cms/pages/${menuId}/${type}.html`;
    
    listContent.innerHTML = '<div class="loading">로딩 중...</div>';

    fetch(pagePath)
        .then(response => response.text())
        .then(html => {
            listContent.innerHTML = html;
            loadPageScript(menuId, type);
        })
        .catch(error => {
            listContent.innerHTML = '<div class="error">페이지를 불러오는데 실패했습니다.</div>';
            console.error('Error:', error);
        });
}

// 페이지별 스크립트 로드
function loadPageScript(menuId, type) {
    const script = document.createElement('script');
    script.src = `/cms/js/pages/${menuId}/${type}.js`;
    document.body.appendChild(script);
}

// 메뉴 상태 저장
function saveMenuState() {
    const activeMenu = document.querySelector('.menu-item.active');
    const activeSubmenu = document.querySelector('.submenu.show');
    if (activeMenu && activeSubmenu) {
        sessionStorage.setItem('activeMenuId', activeMenu.getAttribute('data-menu'));
        sessionStorage.setItem('activeSubmenuType', activeSubmenu.querySelector('a.active')?.getAttribute('data-type'));
    }
}

// 메뉴 상태 복원
function restoreMenuState() {
    const menuId = sessionStorage.getItem('activeMenuId');
    const submenuType = sessionStorage.getItem('activeSubmenuType');
    
    if (menuId) {
        const menuItem = document.querySelector(`.menu-item[data-menu="${menuId}"]`);
        if (menuItem) {
            menuItem.click();
            if (submenuType) {
                const submenuLink = document.querySelector(`#${menuId}-submenu a[data-type="${submenuType}"]`);
                if (submenuLink) {
                    submenuLink.click();
                }
            }
        }
    }
}

// 페이지 이동 시 메뉴 상태 저장
window.addEventListener('beforeunload', saveMenuState);

// 페이지 로드 시 메뉴 상태 복원
document.addEventListener('DOMContentLoaded', function() {
    initMenuHandlers().catch(console.error);
});

// 현재 메뉴 상태 업데이트
function updateMenuState(menuItem, submenuLink) {
    // 모든 메뉴 비활성화
    document.querySelectorAll('.menu-item').forEach(item => {
        item.classList.remove('active');
    });
    document.querySelectorAll('.submenu a').forEach(link => {
        link.classList.remove('active');
    });

    // 선택된 메뉴 활성화
    menuItem.classList.add('active');
    if (submenuLink) {
        submenuLink.classList.add('active');
    }

    // 브레드크럼 업데이트
    const currentMenu = document.getElementById('current-menu');
    const currentSubmenu = document.getElementById('current-submenu');
    
    currentMenu.textContent = menuItem.textContent;
    if (submenuLink) {
        currentSubmenu.textContent = submenuLink.textContent;
    }

    // 상태 저장
    sessionStorage.setItem('currentMenuId', menuItem.getAttribute('data-menu'));
    if (submenuLink) {
        sessionStorage.setItem('currentSubmenuType', submenuLink.getAttribute('data-type'));
    }
}

// 메뉴 클릭 이벤트 핸들러
function handleMenuClick(menuId, type) {
    if (menuId === 'field-practice' && type === 'apply') {
        // 약간의 지연을 주어 DOM이 완전히 로드된 후 실행
        setTimeout(() => {
            if (typeof window.loadFieldPracticeList === 'function') {
                window.loadFieldPracticeList();
            } else {
                console.error('loadFieldPracticeList function not found');
            }
        }, 100);
    }
}

// 스크립트 동적 로드 함수
function loadScript(src) {
    return new Promise((resolve, reject) => {
        // 이미 로드된 스크립트인지 확인
        if (document.querySelector(`script[src="${src}"]`)) {
            resolve();
            return;
        }

        const script = document.createElement('script');
        script.src = src;
        script.onload = resolve;
        script.onerror = reject;
        document.head.appendChild(script);
    });
}

// 현장실습보험 리스트 로드
async function loadFieldPracticeList() {
    const listContent = document.getElementById('list-content');
    
    try {
        // 세련된 로딩 화면 표시
        listContent.innerHTML = `
            <div class="loading-overlay" style="
                position: fixed;
                top: 0;
                left: 0;
                right: 0;
                bottom: 0;
                background: rgba(255, 255, 255, 0.9);
                display: flex;
                justify-content: center;
                align-items: center;
                z-index: 9999;
            ">
                <div class="loading-container" style="
                    display: flex;
                    flex-direction: column;
                    align-items: center;
                    justify-content: center;
                    background: white;
                    padding: 40px;
                    border-radius: 16px;
                    box-shadow: 0 10px 25px rgba(0,0,0,0.1);
                    transform: scale(0.9);
                    animation: loadingPop 0.3s ease forwards;
                ">
                    <div class="loading-spinner" style="
                        width: 50px;
                        height: 50px;
                        margin-bottom: 24px;
                        border: 4px solid #f3f3f3;
                        border-top: 4px solid #3498db;
                        border-radius: 50%;
                        animation: spin 1s cubic-bezier(0.68, -0.55, 0.265, 1.55) infinite;
                    "></div>
                    <div style="
                        color: #333;
                        font-size: 18px;
                        font-weight: 600;
                        margin-bottom: 12px;
                        animation: fadeInUp 0.5s ease forwards;
                    ">데이터를 불러오는 중입니다</div>
                    <div style="
                        color: #666;
                        font-size: 14px;
                        animation: fadeInUp 0.5s ease 0.2s forwards;
                        opacity: 0;
                    ">잠시만 기다려주세요...</div>
                </div>
            </div>
            <style>
                @keyframes spin {
                    0% { transform: rotate(0deg); }
                    100% { transform: rotate(360deg); }
                }
                @keyframes loadingPop {
                    0% { transform: scale(0.9); opacity: 0; }
                    100% { transform: scale(1); opacity: 1; }
                }
                @keyframes fadeInUp {
                    0% { transform: translateY(10px); opacity: 0; }
                    100% { transform: translateY(0); opacity: 1; }
                }
                .loading-container {
                    transition: all 0.3s ease;
                }
                .loading-overlay {
                    transition: opacity 0.3s ease;
                }
            </style>
        `;

        // field-practice.js 스크립트 로드
        await loadScript('/cms/js/field-practice.js');
        
        // 페이지 로드
        const response = await fetch('/cms/pages/field-practice/apply.html');
        const html = await response.text();
        
        // HTML에서 스크립트 태그 제거
        const cleanHtml = html.replace(/<script\b[^<]*(?:(?!<\/script>)<[^<]*)*<\/script>/gi, '');
        
        // 페이드 아웃 효과
        const loadingOverlay = document.querySelector('.loading-overlay');
        if (loadingOverlay) {
            loadingOverlay.style.opacity = '0';
            await new Promise(resolve => setTimeout(resolve, 300));
        }
        
        listContent.innerHTML = cleanHtml;
        
        // 데이터 로드
        const result = await fieldPracticeAPI.fetchApplications();
        fieldPracticeAPI.renderApplicationList(result);
        
    } catch (error) {
        console.error('현장실습보험 리스트 로드 실패:', error);
        listContent.innerHTML = `
            <div class="error-container" style="
                text-align: center;
                padding: 40px;
                background: #fff5f5;
                border-radius: 8px;
                border: 1px solid #feb2b2;
                color: #c53030;
                margin: 20px;
                animation: fadeIn 0.3s ease;
            ">
                <div style="font-weight: 600; margin-bottom: 8px;">데이터를 불러오는데 실패했습니다</div>
                <div style="font-size: 14px; color: #666;">${error.message}</div>
            </div>
            <style>
                @keyframes fadeIn {
                    from { opacity: 0; }
                    to { opacity: 1; }
                }
            </style>
        `;
    }
}

// fieldPracticeAPI 객체 정의
window.fieldPracticeAPI = {
    async fetchApplications() {
        try {
            const apiUrl = encodeURIComponent('https://lincinsu.kr/2025/api/question/list.php');
            const response = await fetch(`/cms/api/proxy.php?url=${apiUrl}`);
            return await response.json();
        } catch (error) {
            console.error('데이터 로드 실패:', error);
            throw error;
        }
    },

    renderApplicationList(data) {
        const listContent = document.getElementById('applicationList');
        if (!listContent) return;

        // 리스트 렌더링 로직...
        listContent.innerHTML = data.map(item => `
            <tr>
                <td>${item.num}</td>
                <!-- 나머지 데이터 표시 -->
            </tr>
        `).join('');
    }
}; 