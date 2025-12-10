/** manual2-manual.js 1차 메뉴 기반 동적 로딩 시스템 **/

// 전역 함수로 노출
window.manual2Manual = manual2Manual;

function manual2Manual() {
    const pageContent = document.getElementById('page-content');
    pageContent.innerHTML = "";
    pageContent.innerHTML = '<tr><td colspan="14" class="loading">데이터 로드 중...</td></tr>';
    
    // 매뉴얼 시스템 초기화 (page-content에 직접 구현)
    setTimeout(() => {
        initializeManualSystem();
    }, 100);
}

// 매뉴얼 시스템 초기화 함수
function initializeManualSystem() {
    // 1차 메뉴 목록 로드
    fetchFirstLevelMenus();
}

// 메뉴 상태 관리
let menuState = {
    firstLevelMenus: [],
    selectedMenu1st: null,
    selectedSubMenus: new Map(),
    expandedSections: new Set(),
    subMenuData: new Map() // 2차 메뉴 데이터 저장용
};

// 아이콘 매핑
const MENU_ICON_MAP = {
    '대리운전': 'car',
    '시간제': 'clock',
    '공유모빌리티': 'share',
    '여행자보험': 'travel',
    '영업배상': 'shield',
    '일반보험': 'insurance',
    '자동차': 'vehicle',
    '화재보험': 'fire',
    '주차장': 'parking',
    '적재물': 'cargo'
};

// 아이콘 팩토리 함수들
const IconFactory = {
    car: () => `<svg class="icon" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
        <circle cx="7" cy="17" r="2"/>
        <circle cx="17" cy="17" r="2"/>
        <path d="M5 17h-2v-6l2-5h9l4 5h1a2 2 0 0 1 2 2v4"/>
    </svg>`,
    
    clock: () => `<svg class="icon" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
        <circle cx="12" cy="12" r="10"/>
        <polyline points="12 6 12 12 16 14"/>
    </svg>`,
    
    share: () => `<svg class="icon" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
        <circle cx="18" cy="5" r="3"/>
        <circle cx="6" cy="12" r="3"/>
        <circle cx="18" cy="19" r="3"/>
        <line x1="8.59" y1="13.51" x2="15.42" y2="17.49"/>
        <line x1="15.41" y1="6.51" x2="8.59" y2="10.49"/>
    </svg>`,
    
    travel: () => `<svg class="icon" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
        <path d="M21 10c0 7-9 13-9 13s-9-6-9-13a9 9 0 0 1 18 0z"/>
        <circle cx="12" cy="10" r="3"/>
    </svg>`,
    
    shield: () => `<svg class="icon" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
        <path d="M12 22s8-4 8-10V5l-8-3-8 3v7c0 6 8 10 8 10z"/>
        <path d="m9 12 2 2 4-4"/>
    </svg>`,
    
    insurance: () => `<svg class="icon" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
        <rect x="3" y="3" width="18" height="18" rx="2" ry="2"/>
        <circle cx="9" cy="9" r="2"/>
        <path d="m21 15-3.086-3.086a2 2 0 0 0-2.828 0L6 21"/>
    </svg>`,
    
    vehicle: () => `<svg class="icon" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
        <path d="M14 16H9m10 0h3v-3.15a1 1 0 0 0-.84-.99L16 11V7a1 1 0 0 0-1-1H5a1 1 0 0 0-1 1v4l-5.16 1.86A1 1 0 0 0 2 14.85V16h3"/>
        <circle cx="6.5" cy="16" r="2"/>
        <circle cx="16.5" cy="16" r="2"/>
    </svg>`,
    
    fire: () => `<svg class="icon" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
        <path d="M8.5 14.5A2.5 2.5 0 0 0 11 12c0-1.38-.5-2-1-3-1.072-2.143-.224-4.054 2-6 .5 2.5 2 4.9 4 6.5 2 1.6 3 3.5 3 5.5a7 7 0 1 1-14 0c0-1.153.433-2.294 1-3a2.5 2.5 0 0 0 2.5 2.5z"/>
    </svg>`,
    
    parking: () => `<svg class="icon" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
        <rect x="3" y="3" width="18" height="18" rx="2"/>
        <path d="M9 8h3a3 3 0 1 1 0 6H9"/>
        <path d="M9 8v8"/>
    </svg>`,
    
    cargo: () => `<svg class="icon" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
        <path d="M21 8a2 2 0 0 0-1-1.73l-7-4a2 2 0 0 0-2 0l-7 4A2 2 0 0 0 3 8v8a2 2 0 0 0 1 1.73l7 4a2 2 0 0 0 2 0l7-4A2 2 0 0 0 21 16Z"/>
        <path d="m3.29 7 8.71 5 8.71-5"/>
        <path d="M12 22V12"/>
    </svg>`,
    
    default: (name) => {
        const firstChar = name.charAt(0);
        const charCode = firstChar.charCodeAt(0);
        const patterns = [
            `<svg class="icon" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                <rect x="3" y="3" width="18" height="18" rx="2" ry="2"/>
                <path d="M9 9h6v6H9z"/>
            </svg>`,
            `<svg class="icon" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                <circle cx="12" cy="12" r="10"/>
                <circle cx="12" cy="12" r="4"/>
            </svg>`,
            `<svg class="icon" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                <polygon points="12,2 22,12 12,22 2,12"/>
                <circle cx="12" cy="12" r="3"/>
            </svg>`
        ];
        return patterns[charCode % patterns.length];
    }
};

// 메뉴 아이콘 생성
function getMenuIcon(menu1st) {
    let iconType = MENU_ICON_MAP[menu1st];
    
    if (!iconType) {
        const menuName = menu1st.toLowerCase().replace(/\s+/g, '');
        
        if (menuName.includes('대리') || menuName.includes('운전')) {
            iconType = 'car';
        } else if (menuName.includes('시간') || menuName.includes('time')) {
            iconType = 'clock';
        } else if (menuName.includes('공유') || menuName.includes('모빌리티')) {
            iconType = 'share';
        } else if (menuName.includes('여행') || menuName.includes('travel')) {
            iconType = 'travel';
        } else if (menuName.includes('영업') || menuName.includes('배상')) {
            iconType = 'shield';
        } else if (menuName.includes('보험') || menuName.includes('insurance')) {
            iconType = 'insurance';
        } else if (menuName.includes('자동차') || menuName.includes('차량')) {
            iconType = 'vehicle';
        } else if (menuName.includes('화재')) {
            iconType = 'fire';
        } else if (menuName.includes('주차')) {
            iconType = 'parking';
        } else if (menuName.includes('적재물')) {
            iconType = 'cargo';
        } else {
            iconType = 'default';
        }
    }
    
    return iconType === 'default' ? IconFactory.default(menu1st) : IconFactory[iconType]();
}

// 1차 메뉴 목록 조회 (새로운 API 구조에 맞게 수정)
async function fetchFirstLevelMenus() {
    try {
        const pageContent = document.getElementById('page-content');
        if (!pageContent) {
            console.error('page-content 요소를 찾을 수 없습니다.');
            return null;
        }
        
        pageContent.innerHTML = '<div class="loading">1차 메뉴 목록을 불러오는 중...</div>';

        const response = await fetch('/api/manual/menuList.php', {
            method: 'GET',
            headers: {
                'Content-Type': 'application/json'
            },
            credentials: 'include'
        });

        if (!response.ok) {
            throw new Error('서버 응답 실패: ' + response.status);
        }

        const data = await response.json();
        console.log('1차 메뉴 목록:', data);

        if (!data.success || !data.data) {
            throw new Error(data.message || '데이터 로드 실패');
        }

        // 새로운 API 구조에 맞게 데이터 처리
        let menuList = [];
        if (Array.isArray(data.data)) {
            // 기본 flat 구조의 경우 - menu_1st 값들만 추출
            if (data.data.length > 0 && typeof data.data[0] === 'string') {
                menuList = data.data;
            } else if (data.data.length > 0 && typeof data.data[0] === 'object') {
                // 객체 배열인 경우 menu_1st 필드 추출
                menuList = data.data.map(item => item.menu_1st).filter(Boolean);
            }
        }

        // 1차 메뉴 목록 저장
        menuState.firstLevelMenus = menuList;
        
        // 1차 메뉴 목록 표시
        displayFirstLevelMenus(menuList);

        return data;
    } catch (error) {
        console.error('1차 메뉴 목록 조회 중 오류 발생:', error);
        const pageContent = document.getElementById('page-content');
        if (pageContent) {
            pageContent.innerHTML = `
                <div class="error">
                    1차 메뉴 목록을 불러오는 중 오류가 발생했습니다.<br>
                    ${error.message}
                </div>
            `;
        }
        return null;
    }
}

// 특정 1차 메뉴의 2차 메뉴 조회 (새로운 API 구조에 맞게 수정)
async function fetchSecondLevelMenus(menu1st) {
    try {
        const response = await fetch(`/api/manual/menuList.php?structure_type=hierarchical&menu_1st=${encodeURIComponent(menu1st)}`, {
            method: 'GET',
            headers: {
                'Content-Type': 'application/json'
            },
            credentials: 'include'
        });

        if (!response.ok) {
            throw new Error('서버 응답 실패: ' + response.status);
        }

        const data = await response.json();
        console.log(`${menu1st}의 2차 메뉴:`, data);

        if (data.success && data.data && data.data.categories && data.data.categories.length > 0) {
            const category = data.data.categories[0];
            if (category.subcategories) {
                // 2차 메뉴 데이터를 상태에 저장 (id 정보 포함)
                menuState.subMenuData.set(menu1st, category.subcategories);
                return category.subcategories;
            }
        }
        
        return [];
    } catch (error) {
        console.error(`${menu1st}의 2차 메뉴 조회 중 오류:`, error);
        return [];
    }
}

// 1차 메뉴 목록 표시
function displayFirstLevelMenus(menuList) {
    const pageContent = document.getElementById('page-content');
    
    let menuHTML = `
        <style>
            :root {
                --primary-color: #007bff;
                --secondary-color: #6c757d;
                --success-color: #28a745;
                --danger-color: #dc3545;
                --light-color: #f8f9fa;
                --dark-color: #343a40;
                --transition-speed: 0.3s;
                --detail-btn-color: #28a745;
                --detail-btn-hover: #218838;
            }

            .manual-container {
                padding: 20px;
                background-color: #f5f7fa;
                min-height: 100vh;
            }

            .menu-header {
                background: white;
                padding: 20px;
                border-radius: 12px;
                margin-bottom: 20px;
                box-shadow: 0 2px 8px rgba(0,0,0,0.1);
            }

            .menu-controls {
                display: flex;
                align-items: center;
                gap: 12px;
                flex-wrap: wrap;
            }

            .control-btn {
                display: flex;
                align-items: center;
                gap: 8px;
                padding: 10px 16px;
                background: #f5f5f5;
                border: 1px solid #ddd;
                border-radius: 8px;
                cursor: pointer;
                font-size: 14px;
                transition: all 0.2s ease;
                text-decoration: none;
                color: #333;
                font-weight: 500;
            }

            .control-btn:hover {
                background: #e8e8e8;
                border-color: #bbb;
                transform: translateY(-1px);
            }

            .control-btn.add-menu {
                background: #007bff;
                color: white;
                border-color: #0056b3;
            }

            .control-btn.add-menu:hover {
                background: #0056b3;
            }

            .search-box {
                position: relative;
                flex: 1;
                max-width: 300px;
            }

            .search-box input {
                width: 100%;
                padding: 10px 40px 10px 16px;
                border: 1px solid #ddd;
                border-radius: 8px;
                font-size: 14px;
            }

            .search-box svg {
                position: absolute;
                right: 12px;
                top: 50%;
                transform: translateY(-50%);
                color: #999;
            }

            .menu-grid {
                display: grid;
                grid-template-columns: repeat(4, 1fr);
                gap: 20px;
                margin-top: 20px;
                justify-content: start;
            }

            .menu-card {
                background: white;
                border: 1px solid #e0e0e0;
                border-radius: 12px;
                overflow: hidden;
                transition: all 0.3s ease;
                box-shadow: 0 2px 8px rgba(0,0,0,0.1);
                cursor: pointer;
                animation: fadeIn 0.6s ease-out forwards;
                opacity: 0;
            }

            .menu-card:hover {
                box-shadow: 0 4px 16px rgba(0,0,0,0.15);
                transform: translateY(-4px);
                border-color: var(--primary-color);
            }

            .menu-card.selected {
                border-color: var(--primary-color);
                background: #f0f8ff;
                box-shadow: 0 4px 16px rgba(0,123,255,0.2);
            }

            .menu-card-header {
                padding: 24px;
                display: flex;
                align-items: center;
                gap: 16px;
                background: #fafafa;
                border-bottom: 1px solid #e0e0e0;
                position: relative;
            }

            .menu-icon {
                width: 48px;
                height: 48px;
                border-radius: 50%;
                background: var(--primary-color);
                display: flex;
                align-items: center;
                justify-content: center;
                color: white;
                flex-shrink: 0;
                transition: all 0.3s ease;
                position: relative;
                overflow: hidden;
            }

            .menu-icon:hover {
                background: #0056b3;
                transform: scale(1.05);
                box-shadow: 0 4px 12px rgba(0, 123, 255, 0.3);
            }

            .menu-icon::before {
                content: '';
                position: absolute;
                top: 0;
                left: 0;
                right: 0;
                bottom: 0;
                background: rgba(255, 255, 255, 0.1);
                border-radius: 50%;
                transform: scale(0);
                transition: transform 0.3s ease;
            }

            .menu-icon:hover::before {
                transform: scale(1);
            }

            .menu-icon .icon {
                width: 24px;
                height: 24px;
                z-index: 1;
                position: relative;
            }

            .menu-info {
                flex: 1;
                display: flex;
                flex-direction: column;
                justify-content: center;
            }

            .menu-title {
                font-size: 1.25rem;
                font-weight: 600;
                color: var(--dark-color);
                margin: 0;
            }

            .submenu-container {
                max-height: 0;
                overflow: hidden;
                transition: max-height 0.4s ease, padding 0.4s ease;
                background: #f8f9fa;
                border-top: 1px solid #e0e0e0;
            }

            .submenu-container.expanded {
                max-height: 2000px;
                padding: 20px;
            }

            .submenu-grid {
                display: grid;
                grid-template-columns: repeat(auto-fit, minmax(250px, 1fr));
                gap: 12px;
            }

            .submenu-card {
                background: white;
                border: 1px solid #e0e0e0;
                border-radius: 8px;
                padding: 16px;
                cursor: pointer;
                transition: all 0.2s ease;
                animation: slideIn 0.4s ease-out forwards;
                opacity: 0;
                transform: translateY(20px);
                position: relative;
            }

            .submenu-card:hover {
                border-color: var(--primary-color);
                box-shadow: 0 2px 8px rgba(0,123,255,0.15);
                transform: translateY(-2px);
            }

            .submenu-header {
                display: flex;
                justify-content: space-between;
                align-items: center;
                margin-bottom: 8px;
                cursor: pointer;
            }

            .submenu-title {
                font-size: 1rem;
                font-weight: 600;
                color: var(--primary-color);
                text-decoration: underline;
                cursor: pointer;
                transition: color 0.2s ease;
            }

            .submenu-title:hover {
                color: #0056b3;
            }

            .submenu-manager {
                font-size: 0.875rem;
                color: var(--secondary-color);
                font-weight: 500;
            }

            .submenu-meta {
                font-size: 0.875rem;
                color: var(--secondary-color);
                display: flex;
                justify-content: space-between;
                margin-bottom: 12px;
                cursor: pointer;
            }

            .manager-info {
                display: flex;
                flex-direction: column;
                gap: 4px;
                margin-bottom: 8px;
                padding: 8px;
                background: #f8f9fa;
                border-radius: 4px;
                font-size: 0.8rem;
            }

            .manager-primary {
                color: var(--primary-color);
                font-weight: 600;
            }

            .manager-count {
                color: var(--secondary-color);
                font-style: italic;
            }

            .submenu-actions {
                display: flex;
                gap: 8px;
                margin-top: 12px;
            }

            .submenu-btn {
                flex: 1;
                padding: 6px 12px;
                font-size: 0.75rem;
                border: 1px solid #ddd;
                border-radius: 4px;
                background: white;
                cursor: pointer;
                transition: all 0.2s ease;
                display: flex;
                align-items: center;
                justify-content: center;
                gap: 4px;
            }

            .submenu-btn:hover {
                background: #f8f9fa;
                border-color: #bbb;
            }

            .submenu-btn.add-client-btn {
                background: #FF9800;
                color: white;
                border-color: #F57C00;
            }

            .submenu-btn.add-client-btn:hover {
                background: #F57C00;
            }

            .submenu-btn.client-list-btn {
                background: #9C27B0;
                color: white;
                border-color: #7B1FA2;
            }

            .submenu-btn.client-list-btn:hover {
                background: #7B1FA2;
            }

			.submenu-btn.process-btn {
				background: #17a2b8;
				color: white;
				border-color: #138496;
			}

			.submenu-btn.process-btn:hover {
				background: #138496;
			}

            .loading {
                text-align: center;
                padding: 40px;
                color: var(--secondary-color);
                font-size: 1.1rem;
            }

            .error {
                text-align: center;
                padding: 40px;
                color: var(--danger-color);
                background: #f8d7da;
                border: 1px solid #f5c6cb;
                border-radius: 8px;
                margin: 20px;
            }

            .no-data {
                text-align: center;
                padding: 40px;
                color: var(--secondary-color);
                background: #f8f9fa;
                border-radius: 8px;
                font-size: 1.1rem;
            }

            @keyframes fadeIn {
                from {
                    opacity: 0;
                    transform: translateY(20px);
                }
                to {
                    opacity: 1;
                    transform: translateY(0);
                }
            }

            @keyframes slideIn {
                from {
                    opacity: 0;
                    transform: translateY(20px);
                }
                to {
                    opacity: 1;
                    transform: translateY(0);
                }
            }

            @media (max-width: 1200px) {
                .menu-grid {
                    grid-template-columns: repeat(2, 1fr);
                }
            }

            @media (max-width: 768px) {
                .menu-grid {
                    grid-template-columns: 1fr;
                }
                
                .menu-controls {
                    flex-direction: column;
                    align-items: stretch;
                }
                
                .control-btn {
                    justify-content: center;
                }

                .submenu-grid {
                    grid-template-columns: 1fr;
                }
            }
        </style>
        
        <div class="manual-container">
            <div class="menu-header">
                <div class="menu-controls">
                    <button class="control-btn add-menu" onclick="openAddMenuModal()">
                        <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                            <path d="M12 5v14"></path>
                            <path d="M5 12h14"></path>
                        </svg>
                        메뉴등록
                    </button>
                    <div class="search-box">
                        <input type="text" id="menuSearch" placeholder="메뉴 검색..." onkeyup="searchMenus(this.value)">
                        <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                            <circle cx="11" cy="11" r="8"></circle>
                            <path d="m21 21-4.35-4.35"></path>
                        </svg>
                    </div>
                </div>
            </div>
            
            <div class="menu-grid">
    `;
    
    // 1차 메뉴 카드들 생성 (아이콘 클릭 이벤트 제거)
    menuList.forEach((menu1st, index) => {
        const icon = getMenuIcon(menu1st);
        
        menuHTML += `
            <div class="menu-card" data-menu="${menu1st}" style="animation-delay: ${index * 0.1}s" onclick="handleFirstLevelMenuClick('${menu1st}')">
                <div class="menu-card-header">
                    <div class="menu-icon">
                        ${icon}
                    </div>
                    <div class="menu-info">
                        <h2 class="menu-title">${menu1st}</h2>
                    </div>
                </div>
                <!-- 2차 메뉴 컨테이너 -->
                <div class="submenu-container" id="submenu-${menu1st}">
                    <div class="submenu-grid" id="submenu-grid-${menu1st}">
                        <!-- 2차 메뉴들이 여기에 동적으로 로드됩니다 -->
                    </div>
                </div>
            </div>
        `;
    });
    
    menuHTML += `
            </div>
        </div>
    `;
    
    pageContent.innerHTML = menuHTML;
    
    // 애니메이션 초기화
    initMenuAnimations();
}

// 1차 메뉴 클릭 처리
async function handleFirstLevelMenuClick(menu1st) {
    console.log(`1차 메뉴 클릭: ${menu1st}`);
    
    const menuCard = document.querySelector(`[data-menu="${menu1st}"]`);
    const submenuContainer = document.getElementById(`submenu-${menu1st}`);
    const submenuGrid = document.getElementById(`submenu-grid-${menu1st}`);
    
    if (!menuCard || !submenuContainer || !submenuGrid) {
        console.error('메뉴 요소를 찾을 수 없습니다.');
        return;
    }
    
    // 이미 선택된 메뉴인지 확인
    if (menuState.selectedMenu1st === menu1st && submenuContainer.classList.contains('expanded')) {
        // 접기
        menuState.selectedMenu1st = null;
        menuCard.classList.remove('selected');
        submenuContainer.classList.remove('expanded');
        return;
    }
    
    // 다른 메뉴들 접기
    document.querySelectorAll('.menu-card').forEach(card => {
        card.classList.remove('selected');
    });
    document.querySelectorAll('.submenu-container').forEach(container => {
        container.classList.remove('expanded');
    });
    
    // 현재 메뉴 선택 표시
    menuCard.classList.add('selected');
    menuState.selectedMenu1st = menu1st;
    
    // 로딩 표시
    submenuGrid.innerHTML = '<div class="loading">2차 메뉴를 불러오는 중...</div>';
    submenuContainer.classList.add('expanded');
    
    try {
        // 2차 메뉴 조회
        const subMenus = await fetchSecondLevelMenus(menu1st);
        
        if (subMenus.length === 0) {
            submenuGrid.innerHTML = '<div class="no-data">등록된 2차 메뉴가 없습니다.</div>';
            return;
        }
        
        // 2차 메뉴 표시
        displaySecondLevelMenus(submenuGrid, menu1st, subMenus);
        
    } catch (error) {
        console.error(`${menu1st}의 2차 메뉴 로드 중 오류:`, error);
        submenuGrid.innerHTML = `<div class="error">2차 메뉴를 불러오는 중 오류가 발생했습니다.<br>${error.message}</div>`;
    }
}

// 2차 메뉴 표시 함수에서 버튼 부분 수정
function displaySecondLevelMenus(container, menu1st, subMenus) {
    let submenuHTML = '';
    
    subMenus.forEach((submenu, index) => {
        const menu2nd = submenu.subcategory_name;
        const itemCount = submenu.total_items || 0;
        const managerCount = submenu.total_managers || 0;
        
        // items 배열에서 첫 번째 아이템의 ID를 가져오기
        let submenuId = '';
        if (submenu.items && submenu.items.length > 0 && submenu.items[0].id) {
            submenuId = submenu.items[0].id.toString();
        } else {
            // ID가 없는 경우 임시로 menu1st + menu2nd 조합을 사용
            submenuId = `${menu1st}:${menu2nd}`;
            console.warn(`2차 메뉴 "${menu2nd}"에 ID가 없어서 임시 ID "${submenuId}"를 사용합니다.`);
        }
        
        console.log(`2차 메뉴 "${menu2nd}" ID: ${submenuId}`);
        
        // folder 정보 추출 (items 배열에서 folder 찾기)
        let folderName = menu2nd;
        if (submenu.items && submenu.items.length > 0) {
            const itemWithFolder = submenu.items.find(item => item.folder && item.folder.trim() !== '');
            if (itemWithFolder) {
                folderName = itemWithFolder.folder;
            }
        }
        
        // 담당자 정보 추출 및 정리
        let primaryManager = '';
        let allManagers = [];
        let managerInfo = '';
        
        if (submenu.items && submenu.items.length > 0) {
            // 각 아이템의 담당자 정보 수집
            const managerSet = new Set();
            let foundPrimary = false;
            
            submenu.items.forEach(item => {
                // manual_writer 필드 확인
                if (item.manual_writer && item.manual_writer.trim() !== '') {
                    const writer = item.manual_writer.trim();
                    managerSet.add(writer);
                    if (!foundPrimary) {
                        primaryManager = writer;
                        foundPrimary = true;
                    }
                }
                
                // 기존 managers 배열 확인
                if (item.managers && Array.isArray(item.managers)) {
                    item.managers.forEach(manager => {
                        if (manager.name && manager.name.trim() !== '') {
                            managerSet.add(manager.name.trim());
                            if (manager.is_primary && !foundPrimary) {
                                primaryManager = manager.name.trim();
                                foundPrimary = true;
                            }
                        }
                    });
                } else if (item.primary_manager && item.primary_manager.trim() !== '') {
                    // primary_manager 필드가 있는 경우
                    const pm = item.primary_manager.trim();
                    primaryManager = pm;
                    managerSet.add(pm);
                    foundPrimary = true;
                } else if (item.all_managers && item.all_managers.trim() !== '') {
                    // all_managers 필드가 있는 경우
                    const managers = item.all_managers.split(',').map(m => m.trim()).filter(m => m);
                    managers.forEach(m => managerSet.add(m));
                    if (!foundPrimary && managers.length > 0) {
                        primaryManager = managers[0];
                        foundPrimary = true;
                    }
                }
            });
            
            allManagers = Array.from(managerSet);
        }
        
        // 담당자 표시 텍스트 생성
        if (allManagers.length === 0) {
            managerInfo = '담당자 없음';
        } else if (allManagers.length === 1) {
            managerInfo = `<span class="manager-primary">${allManagers[0]}</span>`;
        } else {
            const otherCount = allManagers.length - 1;
            managerInfo = `<span class="manager-primary">${primaryManager || allManagers[0]}</span>`;
            if (otherCount > 0) {
                managerInfo += ` <span class="manager-count">외 ${otherCount}명</span>`;
            }
        }
        
        submenuHTML += `
            <div class="submenu-card" data-menu1st="${menu1st}" data-menu2nd="${menu2nd}" data-folder="${folderName}" data-id="${submenuId}"
                 style="animation-delay: ${index * 0.1}s" onclick="handleSubMenuClick('${menu1st}', '${menu2nd}', '${folderName}');">
                <div class="submenu-header">
                    <div class="submenu-title" onclick="event.stopPropagation(); openAddMenuModal('${submenuId}');">
                        ${menu2nd}
                    </div>
                    <div class="submenu-manager">${managerInfo}</div>
                </div>
                <div class="submenu-meta">
                    <span>메뉴얼보기</span>
                    <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                        <polyline points="9 18 15 12 9 6"></polyline>
                    </svg>
                </div>
        `;
        
        // 담당자 정보가 있으면 상세 정보 표시
        if (allManagers.length > 1) {
            submenuHTML += `
                <div class="manager-info">
                    <strong>담당자:</strong> ${allManagers.join(', ')}
                </div>
            `;
        }
        
        // 수정된 부분: 각 버튼에 submenuId를 매개변수로 전달
        submenuHTML += `
                <div class="submenu-actions">
                    <button class="submenu-btn add-client-btn" onclick="event.preventDefault(); event.stopPropagation(); handleAddClientButton('${submenuId}');">
                        <svg width="12" height="12" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                            <path d="M16 21v-2a4 4 0 0 0-4-4H6a4 4 0 0 0-4 4v2"></path>
                            <circle cx="9" cy="7" r="4"></circle>
                            <path d="M22 21v-2a4 4 0 0 0-3-3.87"></path>
                            <path d="M16 3.13a4 4 0 0 1 0 7.75"></path>
                        </svg>
                        거래처 등록
                    </button>
                    <!-- 새로운 프로세스 버튼 추가 -->
                    <button class="submenu-btn process-btn" onclick="event.preventDefault(); event.stopPropagation(); handleProcessButton('${submenuId}');">
                        <svg width="12" height="12" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                            <path d="M12 2v20"></path>
                            <path d="M17 5H9.5a3.5 3.5 0 0 0 0 7h5a3.5 3.5 0 0 1 0 7H6"></path>
                        </svg>
                        프로세스
                    </button>
                    <button class="submenu-btn client-list-btn" onclick="event.preventDefault(); event.stopPropagation(); handleClientListButton('${submenuId}');">
                        <svg width="12" height="12" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                            <path d="M8 6h13"></path>
                            <path d="M8 12h13"></path>
                            <path d="M8 18h13"></path>
                            <path d="M3 6h.01"></path>
                            <path d="M3 12h.01"></path>
                            <path d="M3 18h.01"></path>
                        </svg>
                        거래처 리스트
                    </button>
                </div>
            </div>
        `;
    });
    
    container.innerHTML = submenuHTML;
}

// 2차 메뉴 클릭 처리 (폴더 이동용)
function handleSubMenuClick(menu1st, menu2nd, folderName) {
    console.log(`2차 메뉴 클릭: ${menu1st} > ${menu2nd} (folder: ${folderName})`);
    
    // folder가 비어있거나 없으면 menu2nd 사용
    const targetFolder = folderName && folderName.trim() !== '' ? folderName : menu2nd;
    
    // folder 또는 menu2nd를 URL에 사용
    const targetUrl = `/05/manual/${targetFolder}/index.html`;
    
    console.log(`Target URL: ${targetUrl}`);
    
    // 새 창에서 페이지 열기
    window.open(targetUrl, '_blank');
    
    // 선택 상태 표시 (시각적 피드백) - 아코디언 상태는 유지
    const card = document.querySelector(`[data-menu1st="${menu1st}"][data-menu2nd="${menu2nd}"]`);
    
    if (card) {
        // 잠시 선택 상태로 표시 후 해제 (아코디언은 그대로 유지)
        const originalTransform = card.style.transform;
        const originalBoxShadow = card.style.boxShadow;
        
        card.style.transform = 'scale(0.98)';
        card.style.boxShadow = '0 2px 8px rgba(0,123,255,0.3)';
        
        setTimeout(() => {
            card.style.transform = originalTransform;
            card.style.boxShadow = originalBoxShadow;
        }, 300);
    }
    
    // 이벤트 전파 중단으로 아코디언이 닫히지 않도록 함
    if (event) {
        event.stopPropagation();
    }
}


// 검색 기능
function searchMenus(searchTerm) {
    const menuCards = document.querySelectorAll('.menu-card');
    const term = searchTerm.toLowerCase().trim();
    
    menuCards.forEach(card => {
        const menuText = card.textContent.toLowerCase();
        const shouldShow = term === '' || menuText.includes(term);
        
        if (shouldShow) {
            card.style.display = 'block';
            card.style.opacity = '1';
        } else {
            card.style.display = 'none';
            card.style.opacity = '0.5';
        }
    });
}

// 애니메이션 초기화
function initMenuAnimations() {
    const menuCards = document.querySelectorAll('.menu-card');
    menuCards.forEach((card, index) => {
        card.style.animationDelay = `${index * 0.1}s`;
    });
}