function login() {
  const form = document.getElementById('loginForm');
  const formData = new FormData(form);
  
  // 사용자 입력 확인 부분 생략...
  
  fetch('./api/customer/login.php', {
    method: 'POST',
    body: formData,
    credentials: 'include' // 중요: include로 변경
  })
  .then(response => response.json())
  .then(data => {
    if (data.success) {
      // 중요: 수동으로 쿠키 설정
      const secure = window.location.protocol === 'https:' ? '; secure' : '';
      document.cookie = `userId=${data.userId || data.user_id}; path=/; samesite=lax${secure}`;
      document.cookie = `userseq=${data.userseq}; path=/; samesite=lax${secure}`;
      document.cookie = `nAme=${data.nAme || data.name || ''}; path=/; samesite=lax${secure}`;
      document.cookie = `cNum=${data.cNum || ''}; path=/; samesite=lax${secure}`;
      document.cookie = `company=${data.company || ''}; path=/; samesite=lax${secure}`;
      document.cookie = `lastActivity=${new Date().getTime()}; path=/; samesite=lax${secure}`;
      
      console.log('설정된 쿠키:', document.cookie);
      
      // 성공 메시지 표시
      alert('로그인 성공');
      
      // 쿠키 설정 확인 후 이동
      setTimeout(() => {
        if(getCookie('userId') && getCookie('userseq')) {
          window.location.href = 'dashboard.html';
        } else {
          alert('로그인은 성공했지만 쿠키가 설정되지 않았습니다. 브라우저 설정을 확인해주세요.');
        }
      }, 500);
    } else {
      // 실패 메시지 표시
      alert(data.message || '로그인 실패. 아이디 또는 비밀번호를 확인하세요.');
    }
  })
  .catch(error => {
    console.error('Error:', error);
    alert('서버 통신 오류');
  });
}

// 페이지 로드 시 이미 로그인 상태인지 확인
document.addEventListener('DOMContentLoaded', checkAlreadyLoggedIn);

// 이미 로그인 상태인지 확인하는 함수
function checkAlreadyLoggedIn() {
  // 쿠키에서 사용자 ID와 세션 확인
  const userId = getCookie('userId');
  const userseq = getCookie('userseq');
  
  if (userId && userseq) {
    // 이미 로그인 된 상태라면 서버에 세션 유효성 확인
    fetch('./api/customer/check_session.php')
    .then(response => response.json())
    .then(data => {
      if (data.valid) {
        // 유효한 세션이면 바로 대시보드로 이동
        window.location.href = 'dashboard.html';
      } else {
        // 세션이 유효하지 않으면 쿠키 삭제
        clearAllCookies();
      }
    })
    .catch(error => {
      console.error('세션 확인 중 오류:', error);
      // 오류 발생 시 로그인 페이지 유지
    });
  }
}

// 쿠키 가져오기 함수
function getCookie(name) {
  const cookies = document.cookie.split(';');
  for (let i = 0; i < cookies.length; i++) {
    let cookie = cookies[i].trim();
    if (cookie.indexOf(name + '=') === 0) {
      return cookie.substring(name.length + 1);
    }
  }
  return null;
}

// 모든 쿠키 삭제
function clearAllCookies() {
  const cookies = document.cookie.split(';');
  for (let i = 0; i < cookies.length; i++) {
    const cookie = cookies[i];
    const eqPos = cookie.indexOf('=');
    const name = eqPos > -1 ? cookie.substr(0, eqPos).trim() : cookie.trim();
    document.cookie = name + '=;expires=Thu, 01 Jan 1970 00:00:00 GMT;path=/';
  }
}