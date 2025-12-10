
	 /**
 * 보험대리점 CMS 시스템 스크립트
 * 사이드바 메뉴 관리, 세션 처리, 사용자 인터페이스 등의 기능 제공
 */

// 상수 정의
const CONFIG = {
  SESSION_TIMEOUT: 30 * 60 * 1000, // 30분 세션 타임아웃
  MENU_GROUPS: {
    'level1': ['submenu-kj', 'submenu-das', 'submenu-travel', 'submenu-foreign', 'submenu-field', 'submenu-employee']
  },
  CLOCK_UPDATE_INTERVAL: 60000, // 1분마다 시계 업데이트
  MAX_RETRIES: 5
};

// 전역 상태 관리
const STATE = {
  sessionTimer: null,
  activeMenus: {},
  activeLink: null,
  retryCount: 0,
  clockInterval: null
};

// DOM 로드 완료 시 앱 초기화
document.addEventListener('DOMContentLoaded', function() {
  initializeApp();
});

/**
 * 유틸리티 함수 모음
 */
const Utils = {
    /**
     * 디바운스 함수
     * 연속된 이벤트 발생 시 마지막 이벤트만 실행
     */
    debounce: function(func, wait = 300) {
        let timeout;
        return function executedFunction(...args) {
            const later = () => {
                clearTimeout(timeout);
                func(...args);
            };
            clearTimeout(timeout);
            timeout = setTimeout(later, wait);
        };
    },

    /**
     * 쓰로틀 함수
     * 일정 시간 간격으로 이벤트 실행을 제한
     */
    throttle: function(func, limit = 300) {
        let inThrottle;
        return function executedFunction(...args) {
            if (!inThrottle) {
                func(...args);
                inThrottle = true;
                setTimeout(() => {
                    inThrottle = false;
                }, limit);
            }
        };
    }
};

/**
 * 기존 initializeApp 함수를 이것으로 교체하세요
 * (존재하지 않는 함수 호출 제거)
 */
async function initializeApp() {
    try {
        console.log('앱 초기화 시작...');
        
        // DOM이 완전히 업데이트될 때까지 대기
        await waitForDomReady();
        
        // 세션 체크 (수정된 함수 사용)
        const sessionValid = await checkSession();
        if (!sessionValid) {
            console.log('세션 체크 실패로 초기화 중단');
            return;
        }
        
        // 사용자 정보 표시
        updateUserInfo();
        
        // 시계 초기화
        initClock();
        
        // 메뉴 초기화
        initMenuHandlers();
        
        // 사용자 활동 이벤트 리스너 설정
        setupUserActivityListeners();
        
        // 최적화된 이벤트 리스너 설정
        setupOptimizedEventListeners();
        
        console.log('✅ 앱 초기화 완료!');
        
    } catch (error) {
        console.error('💥 앱 초기화 실패:', error);
        console.error('오류 상세:', error.stack);
        alert(`초기화 오류: ${error.message}`);
        window.location.href = '/sj.html';
    }
}

/**
 * DOM이 준비될 때까지 대기
 */
function waitForDomReady() {
  return new Promise(resolve => setTimeout(resolve, 100));
}

/**
 * 특정 DOM 요소가 로드될 때까지 대기하는 함수
 */
function waitForElement(selector) {
  return new Promise(resolve => {
    if (document.querySelector(selector)) {
      return resolve(document.querySelector(selector));
    }

    const observer = new MutationObserver(mutations => {
      if (document.querySelector(selector)) {
        observer.disconnect();
        resolve(document.querySelector(selector));
      }
    });

    observer.observe(document.body, {
      childList: true,
      subtree: true
    });
  });
}

/**
 * 에러 처리를 위한 커스텀 에러 클래스
 */
class AppError extends Error {
    constructor(message, code) {
        super(message);
        this.name = 'AppError';
        this.code = code;
    }
}

/**
 * 전역 에러 핸들러
 */
window.onerror = function(msg, url, lineNo, columnNo, error) {
    console.error('Global error:', {
        message: msg,
        url: url,
        lineNo: lineNo,
        columnNo: columnNo,
        error: error
    });
    
    // 사용자에게 에러 알림
    showErrorMessage('시스템 오류가 발생했습니다. 잠시 후 다시 시도해주세요.');
    return false;
};

/**
 * 비동기 작업을 위한 에러 핸들러
 */
window.addEventListener('unhandledrejection', function(event) {
    console.error('Unhandled promise rejection:', event.reason);
    showErrorMessage('작업 처리 중 오류가 발생했습니다.');
});

/**
 * 에러 메시지 표시 함수
 */
function showErrorMessage(message, type = 'error') {
    // 이미 표시된 에러 메시지가 있다면 제거
    const existingError = document.querySelector('.error-message');
    if (existingError) {
        existingError.remove();
    }
    
    const errorDiv = document.createElement('div');
    errorDiv.className = `error-message ${type}`;
    errorDiv.textContent = message;
    
    document.body.appendChild(errorDiv);
    
    // 3초 후 메시지 자동 제거
    setTimeout(() => {
        errorDiv.remove();
    }, 3000);
}

/**
 * API 요청 래퍼 함수
 */
async function apiRequest(url, options = {}) {
    try {
        const response = await fetch(url, {
            ...options,
            headers: {
                'Content-Type': 'application/json',
                ...options.headers
            }
        });
        
        if (!response.ok) {
            throw new AppError(`HTTP error! status: ${response.status}`, response.status);
        }
        
        const data = await response.json();
        return data;
    } catch (error) {
        if (error instanceof AppError) {
            handleApiError(error);
        } else {
            console.error('API request failed:', error);
            throw new AppError('서버 연결에 실패했습니다.', 500);
        }
    }
}

/**
 * API 에러 처리 함수
 */
function handleApiError(error) {
    const errorMessages = {
        400: '잘못된 요청입니다.',
        401: '로그인이 필요합니다.',
        403: '접근 권한이 없습니다.',
        404: '요청한 리소스를 찾을 수 없습니다.',
        500: '서버 오류가 발생했습니다.',
        default: '알 수 없는 오류가 발생했습니다.'
    };
    
    const message = errorMessages[error.code] || errorMessages.default;
    showErrorMessage(message);
    
    // 401 에러의 경우 로그인 페이지로 리다이렉트
    if (error.code === 401) {
        SessionManager.logout();
    }
}

/**
 * 기존 index.html에서 checkSession 함수만 이것으로 교체하세요
 * (다른 함수들은 건드리지 마세요)
 */
/**
 * 정리된 checkSession 함수 (운영 환경용)
 */
async function checkSession() {
    try {
        // 브라우저 세션 정보 확인
        const sessionLoggedIn = sessionStorage.getItem('loggedIn');
        const localUserId = localStorage.getItem('userId');
        const localUserName = localStorage.getItem('userName');
        
        // 브라우저에 로그인 정보가 없으면
        if (!sessionLoggedIn || sessionLoggedIn !== 'true' || !localUserId) {
            throw new Error('로그인 정보가 없습니다.');
        }
        
        // 서버 세션 확인
        const response = await fetch('/05/sjSessionCheck.php', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
            },
            credentials: 'include'
        });
        
        if (!response.ok) {
            throw new Error(`서버 응답 오류: ${response.status}`);
        }
        
        const data = await response.json();
        
        if (!data.isValid || !data.loggedIn) {
            throw new Error(data.message || '세션이 유효하지 않습니다.');
        }
        
        return true;
        
    } catch (error) {
        console.error('세션 확인 실패:', error);
        alert(`세션 오류: ${error.message}`);
        window.location.href = '/sj.html';
        return false;
    }
}

/**
 * 사용자 정보 업데이트 함수 개선
 */
async function updateUserInfo() {
    try {
        const userInfo = SessionManager.getUserInfo();
        if (!userInfo.name) {
            throw new AppError('사용자 정보를 찾을 수 없습니다.', 401);
        }
        
        const userNameElement = document.getElementById('userName');
        if (!userNameElement) {
            throw new AppError('사용자 정보 표시 요소를 찾을 수 없습니다.', 500);
        }
        
        userNameElement.textContent = userInfo.name;
    } catch (error) {
        handleApiError(error);
    }
}

/**
 * 시계 초기화 함수
 */
function initClock() {
  const dateTimeElement = document.getElementById('currentDateTime');
  if (dateTimeElement) {
    console.log('Starting clock...');
    ClockManager.element = dateTimeElement;
    ClockManager.start();
  } else {
    console.error('Clock element not found');
  }
}

/**
 * 시계 관리 객체
 */
const ClockManager = {
    element: null,
    timer: null,

    /**
     * 시간 문자열 생성
     */
    getTimeString: function() {
        const now = new Date();
        const days = ['일', '월', '화', '수', '목', '금', '토'];
        
        return [
            now.getFullYear(),
            '-',
            String(now.getMonth() + 1).padStart(2, '0'),
            '-',
            String(now.getDate()).padStart(2, '0'),
            '(',
            days[now.getDay()],
            ') ',
            String(now.getHours()).padStart(2, '0'),
            ':',
            String(now.getMinutes()).padStart(2, '0')
        ].join('');
    },

    /**
     * 시간 업데이트
     */
    update: function() {
        if (!this.element) {
            this.element = document.getElementById('currentDateTime');
        }
        if (this.element) {
            this.element.textContent = this.getTimeString();
        }
    },

    /**
     * 시계 시작
     */
    start: function() {
        this.stop();
        this.update();
        this.timer = setInterval(() => this.update(), CONFIG.CLOCK_UPDATE_INTERVAL);
    },

    /**
     * 시계 정지
     */
    stop: function() {
        if (this.timer) {
            clearInterval(this.timer);
            this.timer = null;
        }
    }
};

/**
 * 메뉴 관리 객체
 */
const MenuManager = {
    /**
     * 메뉴 핸들러 초기화
     */
    init: function() {
        // 메뉴 요소 선택
        const menuButtons = document.querySelectorAll('.menu-button');
        const submenuLinks = document.querySelectorAll('.submenu-link');
        const sidebarToggle = document.querySelector('.sidebar-toggle');
        const mobileMenuToggle = document.querySelector('.mobile-menu-toggle');
        const sidebarOverlay = document.querySelector('.sidebar-overlay');
        
        // 사이드바 접기/펼치기
        if (sidebarToggle) {
            sidebarToggle.addEventListener('click', this.toggleSidebar);
        }
        
        // 모바일 메뉴 토글
        if (mobileMenuToggle) {
            mobileMenuToggle.addEventListener('click', this.toggleMobileMenu);
        }
        
        // 오버레이 클릭 처리
        if (sidebarOverlay) {
            sidebarOverlay.addEventListener('click', this.closeMobileMenu);
        }
        
        // 메뉴 버튼 클릭 이벤트
        menuButtons.forEach(button => {
            button.addEventListener('click', (e) => this.handleMenuButtonClick(e));
        });
        
        // 서브메뉴 링크 클릭 이벤트
        submenuLinks.forEach(link => {
            link.addEventListener('click', (e) => this.handleSubmenuLinkClick(e));
        });
        
        // 저장된 메뉴 상태 복원
        this.loadMenuState();
    },

    /**
     * 사이드바 접기/펼치기
     */
    toggleSidebar: function() {
        document.body.classList.toggle('sidebar-collapsed');
        localStorage.setItem('sidebarCollapsed', document.body.classList.contains('sidebar-collapsed'));
    },
    
    /**
     * 모바일 메뉴 열기/닫기
     */
    toggleMobileMenu: function() {
        document.body.classList.toggle('sidebar-open');
    },
    
    /**
     * 모바일 메뉴 닫기
     */
    closeMobileMenu: function() {
        document.body.classList.remove('sidebar-open');
    },

    /**
     * 메뉴 상태 저장
     */
    saveMenuState: function() {
        const state = {
            activeMenus: STATE.activeMenus,
            activeLink: STATE.activeLink
        };
        sessionStorage.setItem('menuState', JSON.stringify(state));
    },

    /**
     * 메뉴 상태 복원
     */
    loadMenuState: function() {
        const savedState = sessionStorage.getItem('menuState');
        if (!savedState) return;

        const state = JSON.parse(savedState);
        
        // 활성화된 메뉴 복원
        if (state.activeMenus) {
            Object.entries(state.activeMenus).forEach(([group, menuId]) => {
                if (menuId) {
                    const button = document.querySelector(`[data-target="${menuId}"]`);
                    if (button) {
                        this.handleMenuButtonClick({ currentTarget: button });
                    }
                }
            });
        }
        
        // 활성화된 링크 복원
        if (state.activeLink) {
            const link = document.querySelector(`.submenu-link[data-name="${state.activeLink}"]`);
            if (link) {
                this.handleSubmenuLinkClick({ currentTarget: link, preventDefault: () => {} });
            }
        }
    },

    /**
     * 메뉴 버튼 클릭 처리
     */
    handleMenuButtonClick: function(e) {
        const button = e.currentTarget;
        const targetId = button.getAttribute('data-target');
        const submenu = document.getElementById(targetId);
        const chevron = button.querySelector('.chevron');

        if (!submenu) return;

        // 다른 활성화된 메뉴들 닫기
        document.querySelectorAll('.menu-button.active').forEach(activeButton => {
            if (activeButton !== button) {
                const activeSubmenuId = activeButton.getAttribute('data-target');
                const activeSubmenu = document.getElementById(activeSubmenuId);
                if (activeSubmenu) {
                    activeSubmenu.classList.remove('active');
                    activeSubmenu.style.height = '0';
                    activeButton.classList.remove('active');
                    activeButton.setAttribute('aria-expanded', 'false');
                    const activeChevron = activeButton.querySelector('.chevron');
                    activeChevron?.classList.remove('rotate');
                }
            }
        });

        // 현재 메뉴의 상태 토글
        const isExpanded = submenu.classList.contains('active');
        button.setAttribute('aria-expanded', !isExpanded);

        // 서브메뉴 토글
        if (isExpanded) {
            submenu.classList.remove('active');
            submenu.style.height = '0';
            button.classList.remove('active');
            chevron?.classList.remove('rotate');
        } else {
            submenu.classList.add('active');
            submenu.style.height = submenu.scrollHeight + 'px';
            button.classList.add('active');
            chevron?.classList.add('rotate');
        }

        // 메뉴 상태 저장
        this.saveMenuState();
    },

    /**
     * 서브메뉴 링크 클릭 처리
     */
    handleSubmenuLinkClick: function(e) {

		e.preventDefault();
    const link = e.currentTarget;
    
    // 이전 활성화 링크 비활성화
    document.querySelectorAll('.submenu-link.active').forEach(activeLink => {
        activeLink.classList.remove('active');
        activeLink.setAttribute('aria-current', 'false');
    });
    
    // 현재 링크 활성화
    link.classList.add('active');
    link.setAttribute('aria-current', 'page');
    
    STATE.activeLink = link.getAttribute('data-name');
    
    // 부모 메뉴 버튼 찾기
    const submenuItem = link.closest('.submenu-item');
    const submenu = submenuItem.closest('.submenu');
    const menuButton = document.querySelector(`[data-target="${submenu.id}"]`);
    
    // 세션 스토리지에 현재 선택된 메뉴와 서브메뉴 저장
    const menuText = menuButton.querySelector('.menu-button-text').textContent;
    const submenuText = link.textContent;
    
    sessionStorage.setItem("selectedMenu", menuText);
    sessionStorage.setItem("selectedSubmenu", submenuText);
    
    // 페이지 제목 및 콘텐츠 업데이트
    this.updatePageContent(STATE.activeLink);
        
        
      
        // 이전 활성화 링크 비활성화
        document.querySelectorAll('.submenu-link.active').forEach(activeLink => {
            activeLink.classList.remove('active');
            activeLink.setAttribute('aria-current', 'false');
        });
        
        // 현재 링크 활성화
        link.classList.add('active');
        link.setAttribute('aria-current', 'page');

		
        STATE.activeLink = link.getAttribute('data-name');
        
        // 페이지 제목 및 콘텐츠 업데이트
       // this.updatePageContent(STATE.activeLink);
        
        // 모바일에서 메뉴 선택 시 사이드바 닫기
        if (window.innerWidth <= 767) {
            this.closeMobileMenu();
        }
        
        // 메뉴 상태 저장
        this.saveMenuState();
    },

    /**
     * 페이지 콘텐츠 업데이트

	 <div class="content" role="main" aria-label="메인 콘텐츠">
        <!-- 메인 콘텐츠 영역 -->
        <h1 id="page-title" tabindex="-1">보험대리점 CMS</h1>
        <div id="page-content">
            <p>좌측 메뉴에서 원하는 항목을 선택하세요.</p>
        </div>
    </div>
     */
    updatePageContent: function(linkName) {
		
        
		const currentMenu = document.getElementById("current-menu");
		const currentSubmenu = document.getElementById("current-submenu");
		currentMenu.textContent ='';
    // 세션 스토리지에서 마지막 선택된 메뉴 불러오기 (없으면 기본값 설정)
    const savedMenu = sessionStorage.getItem("selectedMenu") || "외국인유학생보험";
    const savedSubmenu = sessionStorage.getItem("selectedSubmenu") || "신청리스트";
		
		if (currentMenu) currentMenu.textContent = savedMenu;
        if (currentSubmenu) currentSubmenu.textContent = savedSubmenu ;
        this.getPageDataByLinkName(linkName);
      /*  const pageData = this.getPageDataByLinkName(linkName);
        
        if (pageTitle) pageTitle.textContent = pageData.title;
        if (pageContent) pageContent.innerHTML = pageData.content;
        if (mobileHeader) mobileHeader.textContent = pageData.title;*/


	
    },

    /**
     * 링크 이름에 따른 페이지 데이터 반환
     */

	 getPageDataByLinkName: function(linkName) {
		console.log( '1426 linkName',linkName);
		

		switch(linkName){

			case 'kj-search': 
				kjSearch(); //js/kj_gisa.js
			break;
			case 'kj-list':
				kj_endorse_search();
				//kjList();  //js/kj_endorsList.js
			break;
			case 'kj-company':
				kj_company(); //js/kj_company.js
			break;
			case 'kj-policy':
				kj_policy();    //js/kj_policy.js
			break;
			case 'kj-person':
				kj_person();    //js/kj_person.js
			break;
			case 'field-list':
				fieldList();  //js/field_practice.js
			break;
			
			case 'field-claim':
				fieldClaim(); //js/field_claim.js
			break;
			
			
			case 'employee-list':
			   employeeList(); //js/employee_list.js
			break;

			case 'cord-list':
				 cordList(); //js/cord_list.js
			break;
			case 'holeinone-list':
			   holeinoneList(); //js/holeinone_list.js
			break;

			case 'manual2-manual':
			   manual2Manual(); //js/manual2-manual.js
			break;
			

		}
     
    }
 
};

/**
 * 메뉴 핸들러 초기화 함수
 */
function initMenuHandlers() {
    MenuManager.init();
}

/**
 * 사용자 활동 이벤트 리스너 설정
 */
function setupUserActivityListeners() {
    // 세션 타이머 리셋을 위한 사용자 활동 감지
    document.addEventListener('mousemove', () => SessionManager.resetTimer());
    document.addEventListener('keypress', () => SessionManager.resetTimer());
    document.addEventListener('click', () => SessionManager.resetTimer());
    
    // 로그아웃 버튼 이벤트 추가
    const logoutButton = document.querySelector('.logout-button');
    if (logoutButton) {
        logoutButton.addEventListener('click', () => SessionManager.logout());
    }
}

/**
 * 성능 최적화된 이벤트 리스너 설정
 */
function setupOptimizedEventListeners() {
    // 스크롤 이벤트 쓰로틀링 (100ms 간격으로 제한)
    const throttledScroll = Utils.throttle(() => {
        // 스크롤 관련 처리
        const sidebarContent = document.querySelector('.sidebar-content');
        if (sidebarContent) {
            // 스크롤 위치에 따른 처리
            handleScroll(sidebarContent);
        }
    }, 100);
    
    document.querySelector('.sidebar-content')?.addEventListener('scroll', throttledScroll);

    // 리사이즈 이벤트 디바운싱 (300ms 대기 후 실행)
    const debouncedResize = Utils.debounce(() => {
        // 화면 크기 변경 시 처리
        handleResize();
    }, 300);
    
    window.addEventListener('resize', debouncedResize);

    // 마우스 이동 이벤트 쓰로틀링 (200ms 간격으로 제한)
    const throttledMouseMove = Utils.throttle(() => {
        SessionManager.resetTimer();
    }, 200);
    
    document.addEventListener('mousemove', throttledMouseMove);

    // 키보드 입력 이벤트 디바운싱 (200ms 대기 후 실행)
    const debouncedKeyPress = Utils.debounce(() => {
        SessionManager.resetTimer();
    }, 200);
    
    document.addEventListener('keypress', debouncedKeyPress);
}

/**
 * 세션 관리 객체
 */
const SessionManager = {
    /**
     * 세션 타이머 초기화
     */
    resetTimer: function() {
        clearTimeout(STATE.sessionTimer);
        STATE.sessionTimer = setTimeout(() => this.logout(), CONFIG.SESSION_TIMEOUT);
    },

    /**
     * 로그아웃 처리
     */
    logout: function() {
        // 스토리지 클리어
        localStorage.removeItem('userName');
        localStorage.removeItem('userId');
        localStorage.removeItem('userPhone');
        sessionStorage.clear();
        
        // 타이머 클리어
        clearTimeout(STATE.sessionTimer);
        
        // PHP 세션 종료를 위한 요청
        fetch('/sjLogout.php')
            .then(() => {
                window.location.href = '/sj.html';
            })
            .catch(error => {
                console.error('Logout error:', error);
                window.location.href = '/sj.html';
            });
    },

    /**
     * 사용자 정보 설정
     */
    setUserInfo: function(userInfo) {
        if (!userInfo || !userInfo.name) {
            console.error('Invalid user data:', userInfo);
            return;
        }

        try {
            // 세션스토리지에 저장
            sessionStorage.setItem('loggedIn', 'true');
            sessionStorage.setItem('userName', userInfo.name);
            sessionStorage.setItem('userId', userInfo.userid);
            sessionStorage.setItem('userPhone', userInfo.phone);

            // 로컬스토리지에도 저장
            localStorage.setItem('userName', userInfo.name);
            localStorage.setItem('userId', userInfo.userid);
            localStorage.setItem('userPhone', userInfo.phone);

            console.log('Session data stored successfully');
        } catch (error) {
            console.error('Error setting login session:', error);
        }
    },

    /**
     * 사용자 정보 가져오기
     */
    getUserInfo: function() {
        return {
            name: sessionStorage.getItem('userName') || localStorage.getItem('userName'),
            userid: sessionStorage.getItem('userId') || localStorage.getItem('userId'),
            phone: sessionStorage.getItem('userPhone') || localStorage.getItem('userPhone')
        };
    }
};

/**
 * 스크롤 핸들러
 */
function handleScroll(element) {
    const { scrollTop, scrollHeight, clientHeight } = element;
    
    // 스크롤이 하단에 도달했는지 체크
    if (scrollTop + clientHeight >= scrollHeight - 50) {
        // 필요한 경우 추가 콘텐츠 로드
        console.log('Bottom reached, loading more content if needed');
    }
}

/**
 * 리사이즈 핸들러
 */
function handleResize() {
    // 모바일 메뉴 상태 처리
    if (window.innerWidth > 767) {
        document.body.classList.remove('sidebar-open');
    }
    
    // 사이드바 높이 조정
    adjustSidebarHeight();
}

/**
 * 사이드바 높이 조정
 */
function adjustSidebarHeight() {
    const sidebar = document.querySelector('.sidebar');
    if (sidebar) {
        sidebar.style.height = `${window.innerHeight}px`;
    }
}

// 초기 로드 시 사이드바 높이 조정
window.addEventListener('load', adjustSidebarHeight);
