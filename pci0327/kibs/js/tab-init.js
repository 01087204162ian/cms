// 부트스트랩 탭 초기화 함수
function initializeBootstrapTabs() {
  // 모든 탭 링크 선택
  const tabLinks = document.querySelectorAll('[data-bs-toggle="tab"]');
  
  // 각 탭 링크에 클릭 이벤트 리스너 설정
  tabLinks.forEach(tabLink => {
    tabLink.addEventListener('click', function(e) {
      e.preventDefault();
      
      // 부트스트랩 탭 객체 생성
      const bsTab = new bootstrap.Tab(this);
      
      // 탭 활성화
      bsTab.show();
      
      // 페이지 제목 업데이트
      const pageTitle = this.textContent.trim();
      document.getElementById('currentPageTitle').textContent = pageTitle;
      
      // 모바일에서 사이드바 닫기
      if (window.innerWidth < 992) {
        document.getElementById('sidebar').classList.remove('show');
      }
      
      // 탭 ID 가져오기
      const tabId = this.getAttribute('href').substring(1);
      
      // 탭별 데이터 로드 처리
      if (tabId === 'home') {
        // 홈 탭인 경우 홈 데이터 로드 함수 호출
       
          loadHomeData();
        
      }else if (tabId === 'driver-search' ) {
        // 기사찾기 탭인 경우 driverSearch 함수 호출 및 상태 초기화
        console.log('기사찾기 탭 클릭됨');
       
          driverSearch();

      }else if (tabId === 'sms-list' ) {
        // 기사찾기 탭인 경우 driverSearch 함수 호출 및 상태 초기화
        console.log('기사찾기 탭 클릭됨');
       
          smsSearch();

      }
      // 다른 탭들에 대한 처리는 여기에 추가 가능
    });
  });
}

// 페이지 로드 시 초기화
document.addEventListener('DOMContentLoaded', function() {
  // 부트스트랩 탭 초기화
  initializeBootstrapTabs();
  
  // 서브메뉴 토글 수정
  const subMenuToggleButtons = document.querySelectorAll('.nav-link[onclick]');
  subMenuToggleButtons.forEach(button => {
    const originalOnclick = button.getAttribute('onclick');
    
    button.removeAttribute('onclick');
    
    button.addEventListener('click', function(e) {
      e.preventDefault();
      e.stopPropagation();
      
      // 함수 호출 형식 처리 - logout()와 toggleSubmenu('id') 모두 처리
      if (originalOnclick.includes('toggleSubmenu')) {
        // toggleSubmenu('id') 형식 처리
        const match = originalOnclick.match(/'([^']+)'/);
        if (match && match[1]) {
          toggleSubmenu(match[1]);
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
        try {
          // 안전하게 함수 실행 시도
          const functionName = originalOnclick.replace(/\([^)]*\)$/, '');
          if (typeof window[functionName] === 'function') {
            window[functionName]();
          } else {
            console.error('함수를 찾을 수 없습니다:', functionName);
          }
        } catch (error) {
          console.error('onclick 처리 중 오류 발생:', error);
        }
      }
    });
  });
  
  // 모든 서브메뉴 항목에 이벤트 리스너 추가
  const submenuItems = document.querySelectorAll('.submenu .nav-link');
  submenuItems.forEach(item => {
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
  if (submenu) { // null 체크 추가
    submenu.classList.toggle('show');
  } else {
    console.error('ID가 ' + id + '인 서브메뉴를 찾을 수 없습니다.');
  }
}

