function login() {
  const form = document.getElementById('loginForm');
  const formData = new FormData(form);
  
  // 사용자 입력 확인
  const userId = document.getElementById('user_id').value;
  const password = document.getElementById('pass_word').value;
  
  if (!userId || !password) {
    alert('아이디와 비밀번호를 모두 입력해주세요.');
    return;
  }
  
  // 로그인 처리 중 표시
  const loginButton = document.querySelector('button[onclick="login()"]');
  const originalButtonText = loginButton.innerHTML;
  loginButton.disabled = true;
  loginButton.innerHTML = '로그인 중...';
  
  fetch('https://www.kjstation.kr/api/customer/login.php', {
    method: 'POST',
    body: formData,
  })
  .then(response => response.json())
  .then(data => {


    // 로그인 버튼 복원
    loginButton.disabled = false;
    loginButton.innerHTML = originalButtonText;
    
    if (data.success) {
		console.log("사용자 데이터:", 
      "userId:", data.userId || data.user_id, 
      "userseq:", data.userseq, 
      "name:", data.nAme || data.name,
	  "company",data.company);
      // API 응답에서 받은 사용자 데이터를 쿠키에 저장
      // 서버에서 반환된 사용자 ID와 세션 정보 저장
      document.cookie = `userId=${data.userId || data.user_id};path=/`;
      document.cookie = `userseq=${data.userseq};path=/`;
      document.cookie = `nAme=${data.nAme || data.name || data.userName};path=/`;
      document.cookie = `cNum=${data.cNum || ''};path=/`;
	  document.cookie = `company=${data.company || ''};path=/`;

      
      // 현재 시간을 저장하여 클라이언트 측 세션 타임아웃 시작
      const now = new Date().getTime();
      document.cookie = `lastActivity=${now};path=/`;
      
      // 디버깅을 위한 쿠키 설정 확인
      console.log('쿠키 설정:', document.cookie);
      
      // 성공 메시지 표시
      alert('로그인 성공');
      
      // 약간의 지연 후 대시보드로 이동 (쿠키 설정 시간 확보)
      setTimeout(() => {
        console.log('대시보드로 이동 시도...');
        // 절대 경로 대신 상대 경로 사용
        window.location.href = 'dashboard.html';
      }, 500);
    } else {
      // 실패 메시지 표시
      alert(data.message || '로그인 실패. 아이디 또는 비밀번호를 확인하세요.');
    }
  })
  .catch(error => {
    // 로그인 버튼 복원
    loginButton.disabled = false;
    loginButton.innerHTML = originalButtonText;
    
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
    fetch('https://www.kjstation.kr/api/customer/check_session.php')
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
  const value = `; ${document.cookie}`;
  const parts = value.split(`; ${name}=`);
  if (parts.length === 2) return parts.pop().split(';').shift();
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