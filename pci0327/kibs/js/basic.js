// 페이지 로드시 실행
document.addEventListener('DOMContentLoaded', function() {
  // 로그인 검증
  checkLoginStatus();
  
  // 사용자 정보 설정
  loadUserInfo();
  
  // 현재 날짜 설정
  setCurrentDate();
  
  // 모바일 사이드바 토글
  document.getElementById('sidebarToggle').addEventListener('click', function() {
    document.getElementById('sidebar').classList.toggle('show');
    // 사용자 활동 감지 - 세션 타이머 리셋
    resetSessionTimer();
  });
  
  // 탭 초기화는 tab-init.js에서 처리하므로 이 부분 제거
  
  // 메시지 글자 수 카운트
  const messageContent = document.getElementById('messageContent');
  const charCount = document.getElementById('charCount');
  
  if (messageContent && charCount) {
    messageContent.addEventListener('input', function() {
      const length = this.value.length;
      charCount.textContent = `${length}/90자`;
      
      if (length > 90) {
        charCount.classList.add('text-danger');
      } else {
        charCount.classList.remove('text-danger');
      }
      
      // 사용자 활동 감지 - 세션 타이머 리셋
      resetSessionTimer();
    });
  }
  
  // 문서 전체에 사용자 활동 감지 이벤트 추가
  document.addEventListener('click', resetSessionTimer);
  document.addEventListener('keypress', resetSessionTimer);
  document.addEventListener('scroll', resetSessionTimer);
  document.addEventListener('mousemove', throttle(resetSessionTimer, 60000)); // 1분마다 실행 제한
  
  // 5분마다 서버 세션 확인 (300000ms)
  setInterval(checkServerSession, 300000);
  
  // 페이지 로드시 홈 데이터 불러오기
  loadHomeData();
  
  // 신규 문자 발송 폼 제출 이벤트 리스너 등록
  const messageForm = document.querySelector('#home form');
  if (messageForm) {
    messageForm.addEventListener('submit', function(e) {
      e.preventDefault();
      sendNewMessage();
    });
  }
  
  // 서브메뉴 토글 이벤트 리스너 추가
  setupSubmenuToggleListeners();
});

// 서브메뉴 토글 이벤트 설정
function setupSubmenuToggleListeners() {
  // 서브메뉴를 가진 모든 메뉴 항목에 이벤트 추가
  const menuItemsWithSubmenu = document.querySelectorAll('.nav-item > .nav-link[onclick]');
  menuItemsWithSubmenu.forEach(item => {
    item.addEventListener('click', function(e) {
      e.preventDefault(); // 기본 이벤트 방지
      // toggleSubmenu 함수는 그대로 사용
    });
  });
}

// 서버 세션 확인 함수
function checkServerSession() {
  fetch('https://kjstation.kr/api/customer/check_session.php', {
    // CORS 정책을 위한 credentials 설정 (필요한 경우 주석 해제)
    // credentials: 'include'
  })
    .then(response => {
      // 응답이 JSON 형식인지 확인합니다
      if (!response.ok) {
        throw new Error(`서버 응답 오류: ${response.status}`);
      }
      
      const contentType = response.headers.get('content-type');
      if (contentType && contentType.includes('application/json')) {
        return response.json();
      } else {
        throw new Error('서버가 JSON 형식이 아닌 응답을 반환했습니다');
      }
    })
    .then(data => {
      if (!data.valid) {
        // 세션이 유효하지 않은 경우
        alert(data.message || '세션이 만료되었습니다. 다시 로그인해주세요.');
        window.location.href = 'index.html'; // 로그인 페이지로 리디렉션
      }
    })
    .catch(error => {
      console.error('세션 확인 중 오류 발생:', error);
      
      // 서버 응답 오류가 반복되면 일정 횟수 이후 로그인 페이지로 이동 (선택적)
      // retryOrRedirect();
    });
}

// 선택적: 오류 발생 시 재시도 또는 리디렉션 로직
let sessionCheckErrorCount = 0;
function retryOrRedirect() {
  sessionCheckErrorCount++;
  
  // 오류가 3회 이상 발생하면 로그인 페이지로 이동
  if (sessionCheckErrorCount > 3) {
    console.log('세션 확인 실패 횟수 초과, 로그인 페이지로 이동합니다');
    alert('서버 연결에 문제가 있습니다. 다시 로그인해주세요.');
    window.location.href = 'index.html';
  }
}

// 세션 타이머 변수
let sessionTimer;
let sessionTimeDisplay;
let sessionTimeoutDuration = 1800000; // 30분 = 1800000 밀리초
let sessionWarningTime = 300000; // 5분 전 경고 = 300000 밀리초

// 세션 타이머 리셋 함수
function resetSessionTimer() {
  // 기존 타이머 제거
  clearTimeout(sessionTimer);
  clearInterval(sessionTimeDisplay);
  
  // 타이머 표시 숨기기
  document.getElementById('sessionTimerDisplay').style.display = 'none';
  
  // 새 타이머 설정
  sessionTimer = setTimeout(function() {
    // 세션 만료시 자동 로그아웃
    alert('장시간 활동이 없어 보안을 위해 자동 로그아웃됩니다.');
    logout();
  }, sessionTimeoutDuration);
  
  // 경고 타이머 설정
  setTimeout(function() {
    // 만료 5분 전 경고 표시
    showSessionWarning();
  }, sessionTimeoutDuration - sessionWarningTime);
  
  // 마지막 활동 시간 업데이트
  document.cookie = `lastActivity=${new Date().getTime()};path=/`;
  
  // 1분마다 서버 세션도 갱신 (쓰로틀링)
  updateServerSessionThrottled();
}

// 세션 경고 표시 함수
function showSessionWarning() {
  // 타이머 표시 요소
  const timerElement = document.getElementById('sessionTimerDisplay');
  const timeRemainingElement = document.getElementById('sessionTimeRemaining');
  
  // 남은 시간 계산 (처음엔 5분 = 300초)
  let timeRemaining = sessionWarningTime / 1000;
  
  // 타이머 표시
  timerElement.style.display = 'block';
  
  // 1초마다 남은 시간 업데이트
  sessionTimeDisplay = setInterval(function() {
    timeRemaining--;
    
    if (timeRemaining <= 0) {
      clearInterval(sessionTimeDisplay);
      return;
    }
    
    // 분:초 형식으로 표시
    const minutes = Math.floor(timeRemaining / 60);
    const seconds = Math.floor(timeRemaining % 60);
    timeRemainingElement.textContent = `${minutes}:${seconds < 10 ? '0' : ''}${seconds}`;
  }, 1000);
}

// 쓰로틀링 함수 (이벤트 빈도 제한)
function throttle(func, delay) {
  let lastCall = 0;
  return function(...args) {
    const now = new Date().getTime();
    if (now - lastCall >= delay) {
      lastCall = now;
      return func.apply(this, args);
    }
  };
}

// 사용자 정보 로드
function loadUserInfo() {
  // 쿠키에서 사용자 정보 가져오기
  const userName = getCookie('nAme') || '사용자';
  const userCompany = getCookie('company') || '회사정보 없음';
  
  // 사용자 정보 표시
  document.getElementById('userName').textContent = userName;
  document.getElementById('userCompany').textContent = userCompany;
  
  // 이니셜 설정 (userInitial 요소가 있는 경우에만)
  const userInitial = document.getElementById('userInitial');
  if (userName && userInitial) {
    userInitial.textContent = userName.charAt(0);
  }
}

// 현재 날짜 설정
function setCurrentDate() {
  const now = new Date();
  const options = { year: 'numeric', month: 'long', day: 'numeric', weekday: 'long' };
  //document.getElementById('currentDate').textContent = now.toLocaleDateString('ko-KR', options);
}

// 쿠키 가져오기 함수
function getCookie(name) {
  const value = `; ${document.cookie}`;
  const parts = value.split(`; ${name}=`);
  if (parts.length === 2) return parts.pop().split(';').shift();
}

// 로그인 상태 확인 함수
function checkLoginStatus() {
  // 쿠키에서 사용자 ID와 시퀀스 가져오기
  const userId = getCookie('userId');
  const userseq = getCookie('userseq');
  const lastActivity = getCookie('lastActivity');
  
  console.log("로그인 상태 확인:", userId, userseq);
  
  // 로그인 정보가 없으면 로그인 페이지로 리디렉션
  if (!userId || !userseq) {
    console.log("로그인 정보 없음, 로그인 페이지로 이동");
    window.location.href = 'index.html';
    return;
  }
  
  // 세션 타임아웃 확인 (30분 = 1800000 밀리초)
  const sessionTimeout = 1800000; // 30분
  const currentTime = new Date().getTime();
  
  if (lastActivity && (currentTime - parseInt(lastActivity)) > sessionTimeout) {
    // 세션 만료, 로그아웃 처리
    console.log("세션 만료됨");
    alert('세션이 만료되었습니다. 다시 로그인해주세요.');
    logout();
    return;
  }
  
  // 마지막 활동 시간 업데이트
  document.cookie = `lastActivity=${currentTime};path=/`;
  
  // 세션 타임아웃 자동 갱신 (사용자 활동 감지)
  resetSessionTimer();
}

// 서버 세션 갱신 쓰로틀 변수
let lastServerUpdate = 0;

// 서버 세션 갱신 함수 (쓰로틀링 적용)
function updateServerSessionThrottled() {
  const now = new Date().getTime();
  // 1분(60000ms)마다 한 번만 서버 세션 갱신
  if (now - lastServerUpdate >= 60000) {
    lastServerUpdate = now;
    updateServerSession();
  }
}

// 서버 세션 갱신 함수
function updateServerSession() {
  fetch('https://kjstation.kr/api/customer/check_session.php', {
    // CORS 정책을 위한 credentials 설정 (필요한 경우 주석 해제)
    // credentials: 'include'
  })
    .then(response => {
      // 응답 상태 확인
      if (!response.ok) {
        throw new Error(`서버 응답 오류: ${response.status}`);
      }
      return response;
    })
    .catch(error => {
      console.error('세션 갱신 중 오류 발생:', error);
      // 오류 발생 시 조용히 실패 (사용자 경험 방해 없음)
    });
}

// 로그아웃 함수
function logout() {
  // 서버 세션 제거
  fetch('https://kjstation.kr/api/customer/logout.php', {
    // CORS 정책 문제로 credentials 옵션 제거
    // credentials: 'include'
  })
    .then(response => response.json())
    .then(data => {
      // 쿠키 삭제
      const cookies = document.cookie.split(';');
      for (let i = 0; i < cookies.length; i++) {
        const cookie = cookies[i];
        const eqPos = cookie.indexOf('=');
        const name = eqPos > -1 ? cookie.substr(0, eqPos) : cookie;
        document.cookie = name + '=;expires=Thu, 01 Jan 1970 00:00:00 GMT;path=/';
      }
      
      // 로그인 페이지로 리디렉션
      window.location.href = 'index.html';
    })
    .catch(error => {
      console.error('로그아웃 중 오류 발생:', error);
      // 오류가 발생해도 로그인 페이지로 이동
      window.location.href = 'index.html';
    });
}