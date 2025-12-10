// 세션 타임아웃 설정 (30분)
const SESSION_TIMEOUT = 30 * 60 * 1000;
let sessionTimer;

// 세션 체크
function checkSession() {
    const userName = sessionStorage.getItem('userName');
    if (!userName || !sessionStorage.getItem('loggedIn')) {
        window.location.href = '/login.html';
        return;
    }
    resetSessionTimer();
}

// 세션 타이머 리셋
function resetSessionTimer() {
    clearTimeout(sessionTimer);
    sessionTimer = setTimeout(logout, SESSION_TIMEOUT);
}

// 로그아웃 함수
function logout() {
    // 로컬스토리지 클리어
    localStorage.removeItem('userName');
    localStorage.removeItem('userId');
    localStorage.removeItem('userPhone');
    
    // 세션스토리지 클리어
    sessionStorage.clear();
    
    // 타이머 클리어
    clearTimeout(sessionTimer);
    
    // PHP 세션 종료를 위한 요청
    fetch('/logout.php')
        .then(() => {
            window.location.href = '/login.html';
        })
        .catch(error => {
            console.error('Logout error:', error);
            window.location.href = '/login.html';
        });
}

// 사용자 활동 감지
document.addEventListener('mousemove', resetSessionTimer);
document.addEventListener('keypress', resetSessionTimer);
document.addEventListener('click', resetSessionTimer);

// 페이지 로드 시 실행
document.addEventListener('DOMContentLoaded', async function() {
    console.log('DOM Content Loaded');
    
    // 컴포넌트가 모두 로드될 때까지 대기
    await waitForElement('#userName');
    
    // 세션 체크
    checkSession();
    
    // 사용자 정보 표시
    updateUserInfo();
});

// 요소가 로드될 때까지 대기하는 함수
function waitForElement(selector) {
    return new Promise(resolve => {
        if (document.querySelector(selector)) {
            return resolve();
        }

        const observer = new MutationObserver(mutations => {
            if (document.querySelector(selector)) {
                observer.disconnect();
                resolve();
            }
        });

        observer.observe(document.body, {
            childList: true,
            subtree: true
        });
    });
}

// 사용자 정보 업데이트 함수
function updateUserInfo() {
    const userName = sessionStorage.getItem('userName') || localStorage.getItem('userName');
    console.log('Retrieved userName:', userName);

    if (!userName) {
        console.log('No userName found, redirecting to login');
        window.location.href = '/login.html';
        return;
    }

    const userNameElement = document.getElementById('userName');
    if (userNameElement) {
        console.log('Setting userName element text to:', userName);
        userNameElement.textContent = userName;
    } else {
        console.error('userName element not found');
    }
}

// 세션 스토리지 헬퍼 함수들
const SessionStorage = {
    setUserInfo(userInfo) {
        sessionStorage.setItem('loggedIn', 'true');
        sessionStorage.setItem('userName', userInfo.name);
        sessionStorage.setItem('userId', userInfo.userid);
        sessionStorage.setItem('userPhone', userInfo.phone);
    },

    getUserInfo() {
        return {
            name: sessionStorage.getItem('userName'),
            userid: sessionStorage.getItem('userId'),
            phone: sessionStorage.getItem('userPhone')
        };
    },

    clear() {
        sessionStorage.clear();
    }
};

// 로그인 성공 시 세션 설정
function setLoginSession(userData) {
    console.log('Setting login session with user data:', userData); // 세션 설정 데이터 확인

    if (!userData || !userData.name) {
        console.error('Invalid user data:', userData);
        return;
    }

    try {
        // 세션스토리지에 저장
        sessionStorage.setItem('loggedIn', 'true');
        sessionStorage.setItem('userName', userData.name);
        sessionStorage.setItem('userId', userData.userid);
        sessionStorage.setItem('userPhone', userData.phone);

        // 로컬스토리지에도 저장
        localStorage.setItem('userName', userData.name);
        localStorage.setItem('userId', userData.userid);
        localStorage.setItem('userPhone', userData.phone);

        // 사용자 이름 표시 업데이트
        const userNameElement = document.getElementById('userName');
        if (userNameElement) {
            console.log('Updating userName element with:', userData.name);
            userNameElement.textContent = userData.name;
        } else {
            console.error('userName element not found in DOM');
        }

        // 저장된 데이터 확인
        console.log('Stored session data:', {
            userName: sessionStorage.getItem('userName'),
            userId: sessionStorage.getItem('userId'),
            userPhone: sessionStorage.getItem('userPhone')
        });
    } catch (error) {
        console.error('Error setting login session:', error);
    }
}

// 현재 시간 표시 및 업데이트
function updateDateTime() {
    const now = new Date();
    const formattedDateTime = now.toLocaleString();
    const dateTimeElement = document.getElementById('dateTime');
    if (dateTimeElement) {
        dateTimeElement.textContent = formattedDateTime;
    }
} 