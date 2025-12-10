/**
 * 통합 로딩 시스템
 * 여러 로딩 인디케이터를 통합하여 일관된 스타일과 동작을 제공합니다.
 */

// 로딩 인디케이터 관리를 위한 전역 객체
const LoadingSystem = {
  // 로딩 인디케이터 인스턴스 저장
  instances: {},
  
  // 스타일 초기화 여부
  stylesInitialized: false,
  
  // 로딩 시스템 초기화
  init() {
    if (!this.stylesInitialized) {
      this.addStyles();
      this.stylesInitialized = true;
    }
  },
  
  // 로딩 인디케이터 스타일 추가
  addStyles() {
    const style = document.createElement('style');
    style.textContent = `
      /* 로딩 인디케이터 기본 스타일 */
      .kj-loading-container {
        position: fixed;
        top: 0;
        left: 0;
        right: 0;
        bottom: 0;
        background-color: rgba(255, 255, 255, 0.8);
        display: flex;
        flex-direction: column;
        justify-content: center;
        align-items: center;
        z-index: 9999;
        transition: opacity 0.3s ease;
      }
      
      .kj-loading-wrapper {
        display: flex;
        flex-direction: column;
        align-items: center;
        justify-content: center;
        background-color: white;
        padding: 30px;
        border-radius: 12px;
        box-shadow: 0 4px 16px rgba(0, 0, 0, 0.1);
      }
      
      .kj-loading-spinner {
        position: relative;
        width: 60px;
        height: 60px;
      }
      
      .kj-loading-spinner:before {
        content: '';
        box-sizing: border-box;
        position: absolute;
        width: 100%;
        height: 100%;
        border-radius: 50%;
        border: 4px solid #f3f3f3;
        border-top-color: #8E6C9D;
        animation: kj-spin 1s linear infinite;
      }
      
      .kj-loading-text {
        margin-top: 16px;
        font-size: 16px;
        font-weight: 500;
        color: #333;
      }
      
      @keyframes kj-spin {
        0% { transform: rotate(0deg); }
        100% { transform: rotate(360deg); }
      }
      
      /* 오버레이 효과를 원하지 않는 경우를 위한 인라인 스타일 */
      .kj-loading-inline {
        position: relative;
        display: inline-block;
        min-height: 120px;
        background-color: transparent;
      }
      
      /* 애니메이션 효과 */
      .kj-loading-fadeIn {
        animation: kj-fadeIn 0.3s ease forwards;
      }
      
      .kj-loading-fadeOut {
        animation: kj-fadeOut 0.3s ease forwards;
      }
      
      @keyframes kj-fadeIn {
        from { opacity: 0; }
        to { opacity: 1; }
      }
      
      @keyframes kj-fadeOut {
        from { opacity: 1; }
        to { opacity: 0; }
      }
    `;
    
    document.head.appendChild(style);
  },
  
  // 로딩 인디케이터 생성
  create(id, options = {}) {
    // 이미 존재하는 인스턴스가 있다면 재사용
    if (this.instances[id]) {
      return this.instances[id];
    }
    
    // 기본 옵션
    const defaultOptions = {
      text: '데이터를 불러오는 중입니다...',
      parent: document.body,
      overlay: true,
      autoShow: false
    };
    
    // 옵션 병합
    const mergedOptions = {...defaultOptions, ...options};
    
    // 컨테이너 생성
    const container = document.createElement('div');
    container.id = `kj-loading-${id}`;
    container.className = `kj-loading-container ${mergedOptions.overlay ? '' : 'kj-loading-inline'}`;
    container.style.display = 'none';
    
    // 내부 요소 생성
    const wrapper = document.createElement('div');
    wrapper.className = 'kj-loading-wrapper';
    
    const spinner = document.createElement('div');
    spinner.className = 'kj-loading-spinner';
    
    const text = document.createElement('div');
    text.className = 'kj-loading-text';
    text.textContent = mergedOptions.text;
    
    // 요소 조립
    wrapper.appendChild(spinner);
    wrapper.appendChild(text);
    container.appendChild(wrapper);
    
    // 부모 요소에 추가
    mergedOptions.parent.appendChild(container);
    
    // 인스턴스 정보 저장
    this.instances[id] = {
      element: container,
      textElement: text,
      show: () => this.show(id),
      hide: () => this.hide(id),
      setText: (newText) => this.setText(id, newText),
      remove: () => this.remove(id)
    };
    
    // 자동 표시가 설정되어 있으면 바로 표시
    if (mergedOptions.autoShow) {
      this.show(id);
    }
    
    return this.instances[id];
  },
  
  // 로딩 인디케이터 표시
  show(id) {
    const instance = this.instances[id];
    if (!instance) return false;
    
    instance.element.classList.add('kj-loading-fadeIn');
    instance.element.style.display = 'flex';
    
    return true;
  },
  
  // 로딩 인디케이터 숨김
  hide(id) {
    const instance = this.instances[id];
    if (!instance) return false;
    
    // 애니메이션 후 실제로 숨김
    instance.element.classList.remove('kj-loading-fadeIn');
    instance.element.classList.add('kj-loading-fadeOut');
    
    setTimeout(() => {
      instance.element.style.display = 'none';
      instance.element.classList.remove('kj-loading-fadeOut');
    }, 300);
    
    return true;
  },
  
  // 로딩 텍스트 변경
  setText(id, text) {
    const instance = this.instances[id];
    if (!instance) return false;
    
    instance.textElement.textContent = text;
    return true;
  },
  
  // 로딩 인디케이터 제거
  remove(id) {
    const instance = this.instances[id];
    if (!instance) return false;
    
    instance.element.remove();
    delete this.instances[id];
    return true;
  },
  
  // 모든 로딩 인디케이터 표시
  showAll() {
    Object.keys(this.instances).forEach(id => this.show(id));
  },
  
  // 모든 로딩 인디케이터 숨김
  hideAll() {
    Object.keys(this.instances).forEach(id => this.hide(id));
  }
};

// 시스템 초기화
LoadingSystem.init();

/**
 * 레거시 호환성 함수들
 * 기존 코드와의 호환성을 위한 함수들
 */

// 증권별 코드 로딩 (policy)
function showPoLoading() {
  if (!LoadingSystem.instances['policy']) {
    LoadingSystem.create('policy', {
      text: '증권 데이터를 불러오는 중...',
      autoShow: true
    });
  } else {
    LoadingSystem.show('policy');
  }
}

function hidePoLoading() {
  LoadingSystem.hide('policy');
}

// 대리운전회사 로딩 (company)
function showKjeLoading() {
  if (!LoadingSystem.instances['company']) {
    LoadingSystem.create('company', {
      text: '대리운전회사 데이터를 불러오는 중...',
      autoShow: true
    });
  } else {
    LoadingSystem.show('company');
  }
}

function hideKjeLoading() {
  LoadingSystem.hide('company');
}

// 대리기사 목록 로딩 (driver)
function showKjeLoading2() {
  if (!LoadingSystem.instances['driver']) {
    LoadingSystem.create('driver', {
      text: '대리기사 목록을 불러오는 중...',
      autoShow: true
    });
  } else {
    LoadingSystem.show('driver');
  }
}

function hideKjeLoading2() {
  LoadingSystem.hide('driver');
}

// 증권상세 정보 로딩 (증권상세 모듈에서 사용)
function showLoading() {
  if (!LoadingSystem.instances['detail']) {
    LoadingSystem.create('detail', {
      text: '증권 상세정보를 불러오는 중...',
      autoShow: true
    });
  } else {
    LoadingSystem.show('detail');
  }
}

function hideLoading() {
  LoadingSystem.hide('detail');
}

// 인라인 로딩 인디케이터 (특정 컨테이너 내부에 표시)
function showInlineLoading(containerId, text = '로딩 중...') {
  const container = document.getElementById(containerId);
  if (!container) return;
  
  const loadingId = `inline-${containerId}`;
  
  if (!LoadingSystem.instances[loadingId]) {
    LoadingSystem.create(loadingId, {
      text: text,
      parent: container,
      overlay: false,
      autoShow: true
    });
  } else {
    LoadingSystem.setText(loadingId, text);
    LoadingSystem.show(loadingId);
  }
}

function hideInlineLoading(containerId) {
  LoadingSystem.hide(`inline-${containerId}`);
}

// 새로운 사용 방법 예시:
/*
// 기본 사용법
const myLoader = LoadingSystem.create('myLoader', {
  text: '커스텀 로딩 메시지',
  autoShow: true
});

// 3초 후 텍스트 변경 및 숨김
setTimeout(() => {
  myLoader.setText('거의 완료되었습니다...');
  
  setTimeout(() => {
    myLoader.hide();
  }, 2000);
}, 3000);

// 인라인 로딩 (컨테이너 내부)
showInlineLoading('myContainer', '데이터 처리 중...');
*/