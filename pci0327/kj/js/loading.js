// 로딩 표시 함수
function showLoading() {
  // 이미 로딩 표시기가 있는지 확인
  let loader = document.getElementById('pageLoader');
  
  if (!loader) {
    // 로딩 표시기 생성
    loader = document.createElement('div');
    loader.id = 'pageLoader';
    loader.innerHTML = `
      <div class="spinner-border text-primary" role="status">
        <span class="visually-hidden">로딩 중...</span>
      </div>
    `;
    loader.style.position = 'fixed';
    loader.style.top = '50%';
    loader.style.left = '50%';
    loader.style.transform = 'translate(-50%, -50%)';
    loader.style.zIndex = '9999';
    document.body.appendChild(loader);
  }
  
  loader.style.display = 'block';
}

// 로딩 숨기기 함수
function hideLoading() {
  const loader = document.getElementById('pageLoader');
  if (loader) {
    loader.style.display = 'none';
  }
}// 로딩 인디케이터 함수
