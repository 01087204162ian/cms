// 부트스트랩 탭 초기화 함수 수정
function initializeBootstrapTabs() {
  // 모든 탭 링크 선택 (data-bs-toggle="tab" 또는 href="#"로 시작하는 링크)
  const tabLinks = document.querySelectorAll('[data-bs-toggle="tab"], a[href^="#"]:not([href="#"])');
  
  // 각 탭 링크에 클릭 이벤트 리스너 설정
  tabLinks.forEach(tabLink => {
    // 이미 이벤트 리스너가 등록되어 있는지 확인
    if (!tabLink.dataset.listenerAttached) {
      attachTabEventListener(tabLink);
      tabLink.dataset.listenerAttached = 'true'; // 플래그 설정
    }
  });
  
  console.log('탭 초기화 완료. 총 탭 수:', tabLinks.length);
}

/**
 * 개별 탭 링크에 이벤트 리스너 추가 함수
 * 동적으로 추가되는 탭에도 같은 이벤트 리스너를 쉽게 연결할 수 있도록 분리
 */
function attachTabEventListener(tabLink) {
  // 이미 이벤트 리스너가 등록되어 있다면 중복 등록 방지
  if (tabLink.dataset.listenerAttached === 'true') {
    return;
  }

  tabLink.addEventListener('click', function(e) {
    e.preventDefault();
    
    // 부트스트랩 탭 객체 생성
    const bsTab = new bootstrap.Tab(this);
    
    // 탭 활성화
    bsTab.show();
    
    // 페이지 제목 업데이트
    const pageTitle = this.textContent.trim();
    const titleElement = document.getElementById('currentPageTitle');
    if (titleElement) {
      titleElement.textContent = pageTitle;
    }
    
    // 모바일에서 사이드바 닫기
    if (window.innerWidth < 992) {
      const sidebar = document.getElementById('sidebar');
      if (sidebar) {
        sidebar.classList.remove('show');
      }
    }
    
    // 탭 ID 가져오기
    const tabId = this.getAttribute('href').substring(1);
    console.log('tabId', tabId);
    
    // 탭별 데이터 로드 처리 - 함수 존재 여부 확인 후 호출
    try {
      if (tabId === 'home') {
        // 홈 탭인 경우 홈 데이터 로드 함수 호출
        if (typeof loadHomeData === 'function') {
          loadHomeData();
        }
      } else if (tabId === 'driver-search') {
        // 기사찾기 탭인 경우 driverSearch 함수 호출
        console.log('기사찾기 탭 클릭됨');
        if (typeof driverSearch === 'function') {
          driverSearch();
        }
      } else if (tabId === 'sms-list') {
        // 문자리스트 탭인 경우 sms 함수 호출
        console.log('문자리스트 탭 클릭됨');
        if (typeof sms === 'function') {
          sms();
        }
      } else if (tabId === 'read-only') {
        // 사고관리 탭인 경우 관련 데이터 로드 함수 호출
        console.log('사고관리 탭 클릭됨');
        if (typeof loadAccidentData === 'function') {
          loadAccidentData();
        } else {
          console.warn('loadAccidentData 함수가 정의되지 않았습니다.');
        }
      } else if (tabId === 'id-management') {
        // 읽기전용 ID 탭인 경우 관련 데이터 로드 함수 호출
        console.log('읽기전용 ID 탭 클릭됨');
        if (typeof loadIdData === 'function') {
          loadIdData();
        } else {
          console.warn('loadIdData 함수가 정의되지 않았습니다.');
        }
      }
    } catch (error) {
      console.error('탭 데이터 로드 중 오류 발생:', error);
    }

    // 다른 탭들에 대한 처리는 여기에 추가 가능
  });

  // 이벤트 리스너 등록 완료 플래그 설정
  tabLink.dataset.listenerAttached = 'true';
}

// 사고관리 메뉴 추가 후 이벤트 리스너 연결을 위한 함수
function initializeAccidentManagementTab() {
  // 사고관리 탭 링크 선택
  const accidentTabLink = document.querySelector('a[href="#read-only"]');
  if (accidentTabLink && !accidentTabLink.dataset.listenerAttached) {
    // 탭 이벤트 리스너 연결
    attachTabEventListener(accidentTabLink);
    console.log('사고관리 탭 이벤트 리스너 연결됨');
  }
}

// 읽기전용 ID 메뉴 추가 후 이벤트 리스너 연결을 위한 함수 추가
function initializeIdManagementTab() {
  // 읽기전용 ID 탭 링크 선택
  const idTabLink = document.querySelector('a[href="#id-management"]');
  if (idTabLink && !idTabLink.dataset.listenerAttached) {
    // 탭 이벤트 리스너 연결
    attachTabEventListener(idTabLink);
    console.log('읽기전용 ID 탭 이벤트 리스너 연결됨');
  } else if (!idTabLink) {
    console.error('읽기전용 ID 탭 링크를 찾을 수 없습니다');
  }
}

// 전역 함수로 노출하여 다른 스크립트에서 호출할 수 있도록 함
window.initializeAccidentManagementTab = initializeAccidentManagementTab;
window.initializeIdManagementTab = initializeIdManagementTab;

// 페이지 로드 시 초기화
document.addEventListener('DOMContentLoaded', function() {
  // 부트스트랩 탭 초기화
  initializeBootstrapTabs();
  
  // 동적 탭들을 위한 지연된 초기화
  setTimeout(() => {
    // id-management 탭 수동 초기화 (동적 추가된 탭 처리)
    initializeIdManagementTab();
    
    // 혹시 놓친 탭들이 있다면 다시 한번 체크
    const unregisteredTabs = document.querySelectorAll('a[href^="#"]:not([href="#"])');
    unregisteredTabs.forEach(tab => {
      if (!tab.dataset.listenerAttached && tab.getAttribute('href') !== '#') {
        attachTabEventListener(tab);
        console.log('추가 탭 등록:', tab.getAttribute('href'));
      }
    });
  }, 500); // 500ms 지연으로 동적 탭 로드 대기
  
  // 서브메뉴 토글 수정
  const subMenuToggleButtons = document.querySelectorAll('.nav-link[onclick]');
  subMenuToggleButtons.forEach(button => {
    const originalOnclick = button.getAttribute('onclick');
    
    if (originalOnclick && !button.dataset.onclickProcessed) {
      button.removeAttribute('onclick');
      button.dataset.onclickProcessed = 'true'; // 중복 처리 방지
      
      button.addEventListener('click', function(e) {
        e.preventDefault();
        e.stopPropagation();
        
        // 함수 호출 형식 처리 - logout()와 toggleSubmenu('id') 모두 처리
        try {
          if (originalOnclick.includes('toggleSubmenu')) {
            // toggleSubmenu('id') 형식 처리
            const match = originalOnclick.match(/'([^']+)'/);
            if (match && match[1]) {
              if (typeof toggleSubmenu === 'function') {
                toggleSubmenu(match[1]);
              }
            } else {
              console.error('서브메뉴 ID를 찾을 수 없습니다:', originalOnclick);
            }
          } else if (originalOnclick.includes('logout')) {
            // logout() 형식 처리
            if (typeof logout === 'function') {
              logout();
            } else {
              console.error('logout 함수를 찾을 수 없습니다.');
            }
          } else {
            // 그 외 다른 onclick 함수 처리
            const functionName = originalOnclick.replace(/\([^)]*\)$/, '');
            if (typeof window[functionName] === 'function') {
              window[functionName]();
            } else {
              console.error('함수를 찾을 수 없습니다:', functionName);
            }
          }
        } catch (error) {
          console.error('onclick 처리 중 오류 발생:', error);
        }
      });
    }
  });
  
  // 모든 서브메뉴 항목에 이벤트 리스너 추가
  const submenuItems = document.querySelectorAll('.submenu .nav-link');
  submenuItems.forEach(item => {
    if (!item.dataset.submenuProcessed) {
      item.dataset.submenuProcessed = 'true';
      
      item.addEventListener('click', function(e) {
        // 기본 탭 이벤트는 유지하고 추가 로직 실행
        
        // 모든 메뉴 항목에서 active 클래스 제거
        document.querySelectorAll('.nav-link').forEach(link => {
          link.classList.remove('active');
        });
        
        // 클릭한 항목에 active 클래스 추가
        this.classList.add('active');
        
        // 부모 메뉴도 active로 표시
        const parentMenu = this.closest('.submenu').previousElementSibling;
        if (parentMenu) {
          parentMenu.classList.add('active');
        }
      });
    }
  });
});

// 서브메뉴 토글 함수 수정
function toggleSubmenu(id) {
  // 다른 모든 서브메뉴 닫기
  document.querySelectorAll('.submenu.show').forEach(submenu => {
    if (submenu.id !== id) {
      submenu.classList.remove('show');
    }
  });
  
  // 선택한 서브메뉴 토글
  const submenu = document.getElementById(id);
  if (submenu) {
    submenu.classList.toggle('show');
  } else {
    console.error('ID가 ' + id + '인 서브메뉴를 찾을 수 없습니다.');
  }
}