/**
 * 로그인 정보 관리 시스템
 * 서버에서 로그인 정보를 불러오고 편집할 수 있는 기능을 제공합니다.
 */

class LoginManager {
    constructor() {
        this.baseUrl = '/api/login-info'; // 실제 서버 API 엔드포인트로 변경 필요
        this.isEditing = false;
        this.originalData = {};
        
        this.init();
    }

    /**
     * 초기화
     */
    init() {
        this.bindEvents();
        this.loadLoginInfo();
    }

    /**
     * 이벤트 바인딩
     */
    bindEvents() {
        const editBtn = document.getElementById('editLoginBtn');
        const saveBtn = document.getElementById('saveLoginBtn');
        const cancelBtn = document.getElementById('cancelLoginBtn');

        if (editBtn) {
            editBtn.addEventListener('click', () => this.startEditing());
        }

        if (saveBtn) {
            saveBtn.addEventListener('click', () => this.saveChanges());
        }

        if (cancelBtn) {
            cancelBtn.addEventListener('click', () => this.cancelEditing());
        }

        // 엔터 키로 저장
        document.addEventListener('keydown', (e) => {
            if (e.key === 'Enter' && this.isEditing) {
                e.preventDefault();
                this.saveChanges();
            }
            // ESC 키로 취소
            if (e.key === 'Escape' && this.isEditing) {
                e.preventDefault();
                this.cancelEditing();
            }
        });
    }

    /**
     * 서버에서 로그인 정보 불러오기
     */
    async loadLoginInfo() {
        try {
            // 실제 서버 호출 (현재는 localStorage 또는 기본값 사용)
            const response = await this.fetchLoginInfo();
            
            if (response.success) {
                this.displayLoginInfo(response.data);
                this.originalData = { ...response.data };
            } else {
                this.showMessage('로그인 정보를 불러오는데 실패했습니다.', 'error');
                // 기본값 표시
                this.displayDefaultLoginInfo();
            }
        } catch (error) {
            console.error('로그인 정보 로드 오류:', error);
            this.showMessage('로그인 정보를 불러오는데 실패했습니다.', 'error');
            // 기본값 표시
            this.displayDefaultLoginInfo();
        }
    }

    /**
     * 서버에서 로그인 정보 가져오기 (API 호출)
     */
    async fetchLoginInfo() {
        // 실제 서버가 없는 경우 localStorage에서 불러오거나 기본값 반환
        const storedData = localStorage.getItem('loginInfo');
        
        if (storedData) {
            return {
                success: true,
                data: JSON.parse(storedData)
            };
        }

        // 기본값 반환
        return {
            success: true,
            data: {
                loginId: '3456877 (이투엘 유은진)',
                password: 'sj716671@',
                authCode: '7898101455'
            }
        };

        // 실제 서버 호출 코드 (주석 처리)
        /*
        const response = await fetch(this.baseUrl, {
            method: 'GET',
            headers: {
                'Content-Type': 'application/json',
            }
        });

        if (!response.ok) {
            throw new Error(`HTTP error! status: ${response.status}`);
        }

        return await response.json();
        */
    }

    /**
     * 기본 로그인 정보 표시
     */
    displayDefaultLoginInfo() {
        const defaultData = {
            loginId: '3456877 (이투엘 유은진)',
            password: 'sj716671@',
            authCode: '7898101455'
        };
        
        this.displayLoginInfo(defaultData);
        this.originalData = { ...defaultData };
    }

    /**
     * 로그인 정보 화면에 표시
     */
    displayLoginInfo(data) {
        const loginIdEl = document.getElementById('loginId');
        const passwordEl = document.getElementById('loginPassword');
        const authCodeEl = document.getElementById('loginAuthCode');

        if (loginIdEl) loginIdEl.textContent = data.loginId;
        if (passwordEl) passwordEl.textContent = data.password;
        if (authCodeEl) authCodeEl.textContent = data.authCode;
    }

    /**
     * 편집 모드 시작
     */
    startEditing() {
        if (this.isEditing) return;

        this.isEditing = true;

        // 현재 값들을 입력 필드에 설정
        const loginIdEl = document.getElementById('loginId');
        const passwordEl = document.getElementById('loginPassword');
        const authCodeEl = document.getElementById('loginAuthCode');
        
        const editLoginIdEl = document.getElementById('editLoginId');
        const editPasswordEl = document.getElementById('editLoginPassword');
        const editAuthCodeEl = document.getElementById('editLoginAuthCode');

        if (editLoginIdEl && loginIdEl) {
            editLoginIdEl.value = loginIdEl.textContent;
        }
        if (editPasswordEl && passwordEl) {
            editPasswordEl.value = passwordEl.textContent;
        }
        if (editAuthCodeEl && authCodeEl) {
            editAuthCodeEl.value = authCodeEl.textContent;
        }

        // UI 변경
        this.toggleEditMode(true);
        
        // 첫 번째 입력 필드에 포커스
        if (editLoginIdEl) {
            editLoginIdEl.focus();
            editLoginIdEl.select();
        }
    }

    /**
     * 변경사항 저장
     */
    async saveChanges() {
        if (!this.isEditing) return;

        const editLoginIdEl = document.getElementById('editLoginId');
        const editPasswordEl = document.getElementById('editLoginPassword');
        const editAuthCodeEl = document.getElementById('editLoginAuthCode');

        // 입력값 검증
        if (!editLoginIdEl.value.trim()) {
            this.showMessage('아이디를 입력해주세요.', 'error');
            editLoginIdEl.focus();
            return;
        }

        if (!editPasswordEl.value.trim()) {
            this.showMessage('비밀번호를 입력해주세요.', 'error');
            editPasswordEl.focus();
            return;
        }

        if (!editAuthCodeEl.value.trim()) {
            this.showMessage('인증번호를 입력해주세요.', 'error');
            editAuthCodeEl.focus();
            return;
        }

        // 로딩 표시
        this.showLoading(true);

        try {
            const newData = {
                loginId: editLoginIdEl.value.trim(),
                password: editPasswordEl.value.trim(),
                authCode: editAuthCodeEl.value.trim()
            };

            const response = await this.saveLoginInfo(newData);
            
            if (response.success) {
                // 성공시 화면 업데이트
                this.displayLoginInfo(newData);
                this.originalData = { ...newData };
                this.toggleEditMode(false);
                this.isEditing = false;
                this.showMessage('로그인 정보가 성공적으로 저장되었습니다.', 'success');
            } else {
                this.showMessage(response.message || '저장에 실패했습니다.', 'error');
            }
        } catch (error) {
            console.error('저장 오류:', error);
            this.showMessage('저장 중 오류가 발생했습니다.', 'error');
        } finally {
            this.showLoading(false);
        }
    }

    /**
     * 서버에 로그인 정보 저장
     */
    async saveLoginInfo(data) {
        // localStorage에 저장 (실제 서버가 없는 경우)
        localStorage.setItem('loginInfo', JSON.stringify(data));
        
        // 성공 응답 시뮬레이션
        return new Promise((resolve) => {
            setTimeout(() => {
                resolve({
                    success: true,
                    message: '저장되었습니다.'
                });
            }, 1000); // 1초 지연으로 로딩 효과 시뮬레이션
        });

        // 실제 서버 호출 코드 (주석 처리)
        /*
        const response = await fetch(this.baseUrl, {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
            },
            body: JSON.stringify(data)
        });

        if (!response.ok) {
            throw new Error(`HTTP error! status: ${response.status}`);
        }

        return await response.json();
        */
    }

    /**
     * 편집 취소
     */
    cancelEditing() {
        if (!this.isEditing) return;

        this.isEditing = false;
        this.toggleEditMode(false);
        this.hideMessage();
    }

    /**
     * 편집 모드 UI 토글
     */
    toggleEditMode(isEditing) {
        const valueElements = ['loginId', 'loginPassword', 'loginAuthCode'];
        const inputElements = ['editLoginId', 'editLoginPassword', 'editLoginAuthCode'];
        
        valueElements.forEach(id => {
            const el = document.getElementById(id);
            if (el) el.style.display = isEditing ? 'none' : 'inline';
        });

        inputElements.forEach(id => {
            const el = document.getElementById(id);
            if (el) el.style.display = isEditing ? 'block' : 'none';
        });

        const editBtn = document.getElementById('editLoginBtn');
        const editActions = document.getElementById('loginEditActions');
        
        if (editBtn) editBtn.style.display = isEditing ? 'none' : 'inline-block';
        if (editActions) editActions.style.display = isEditing ? 'flex' : 'none';
    }

    /**
     * 로딩 표시/숨김
     */
    showLoading(show) {
        const loadingEl = document.getElementById('loginLoading');
        if (loadingEl) {
            loadingEl.style.display = show ? 'block' : 'none';
        }

        const saveBtn = document.getElementById('saveLoginBtn');
        const cancelBtn = document.getElementById('cancelLoginBtn');
        
        if (saveBtn) saveBtn.disabled = show;
        if (cancelBtn) cancelBtn.disabled = show;
    }

    /**
     * 메시지 표시
     */
    showMessage(message, type = 'info') {
        this.hideMessage();

        const messageEl = document.createElement('div');
        messageEl.className = `message ${type}`;
        messageEl.innerHTML = `
            <i class="fas ${type === 'error' ? 'fa-exclamation-circle' : 'fa-check-circle'}"></i>
            ${message}
        `;
        messageEl.id = 'loginMessage';

        const card = document.getElementById('loginInfoCard');
        if (card) {
            card.appendChild(messageEl);
        }

        // 3초 후 자동 숨김
        setTimeout(() => {
            this.hideMessage();
        }, 3000);
    }

    /**
     * 메시지 숨김
     */
    hideMessage() {
        const messageEl = document.getElementById('loginMessage');
        if (messageEl) {
            messageEl.remove();
        }
    }
}

// DOM이 로드되면 LoginManager 초기화
document.addEventListener('DOMContentLoaded', () => {
    const loginManager = new LoginManager();
    
    // 전역 접근을 위해 window 객체에 할당
    window.loginManager = loginManager;
}); 