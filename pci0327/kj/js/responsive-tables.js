// 페이지 로드 시 반응형 테이블 초기화 스크립트

document.addEventListener('DOMContentLoaded', function() {
  // 배서 처리 테이블을 자동으로 반응형으로 설정
  initializeResponsiveTables();
  
  // 윈도우 리사이즈 이벤트 처리
  window.addEventListener('resize', function() {
    initializeResponsiveTables();
  });
  
  // 기존 함수 확장
  extendExistingFunctions();
});

// 반응형 테이블 초기화 함수
function initializeResponsiveTables() {
  // 모바일 환경인지 확인
  const isMobile = window.innerWidth <= 767;
  
  // 배서 테이블 찾기
  const endorseTables = document.querySelectorAll('table');
  
  // 모든 테이블에 반응형 클래스 추가
  endorseTables.forEach(table => {
    if (isMobile) {
      table.classList.add('table-mobile-stack');
    } else {
      table.classList.remove('table-mobile-stack');
    }
    
    // 테이블에 data-label 속성 추가
    addDataLabelsToTable(table);
  });
}

// 테이블에 data-label 속성 추가 함수
function addDataLabelsToTable(table) {
  // 헤더 셀이 있는지 확인
  const headerCells = table.querySelectorAll('thead th');
  
  // 헤더셀이 없으면 기본값 사용
  const defaultLabels = ['번호', '성명', '주민번호', '핸드폰', '증권번호', '상태'];
  
  // 모든 행에 대해 처리
  const rows = table.querySelectorAll('tbody tr');
  
  rows.forEach(row => {
    const cells = row.querySelectorAll('td');
    
    cells.forEach((cell, index) => {
      // 이미 data-label 속성이 있는지 확인
      if (!cell.hasAttribute('data-label')) {
        if (headerCells.length > 0 && headerCells[index]) {
          // 헤더 셀의 텍스트를 data-label로 사용
          cell.setAttribute('data-label', headerCells[index].textContent.trim());
        } else if (index < defaultLabels.length) {
          // 기본 라벨 사용
          cell.setAttribute('data-label', defaultLabels[index]);
        }
      }
    });
  });
}

// 기존 함수 확장하기
function extendExistingFunctions() {
  // 원래 toDayEndorse 함수 저장
  if (typeof window.toDayEndorse === 'function') {
    const originalToDayEndorse = window.toDayEndorse;
    
    // 함수 재정의
    window.toDayEndorse = function(cNum) {
      // 원래 함수 호출
      originalToDayEndorse(cNum);
      
      // API 응답 시간을 고려한 지연 후 반응형 처리
      setTimeout(function() {
        initializeResponsiveTables();
      }, 500);
    };
  }
  
  // 원래 toDayEndorseAfter 함수 저장
  if (typeof window.toDayEndorseAfter === 'function') {
    const originalToDayEndorseAfter = window.toDayEndorseAfter;
    
    // 함수 재정의
    window.toDayEndorseAfter = function(cNum) {
      // 원래 함수 호출
      originalToDayEndorseAfter(cNum);
      
      // API 응답 시간을 고려한 지연 후 반응형 처리
      setTimeout(function() {
        initializeResponsiveTables();
      }, 500);
    };
  }
  
  // 원래 loadHomeData 함수 저장
  if (typeof window.loadHomeData === 'function') {
    const originalLoadHomeData = window.loadHomeData;
    
    // 함수 재정의
    window.loadHomeData = function() {
      // 원래 함수 호출
      originalLoadHomeData();
      
      // API 응답 시간을 고려한 지연 후 반응형 처리
      setTimeout(function() {
        initializeResponsiveTables();
      }, 800);
    };
  }
}

// 테이블이 보이지 않는 문제 해결을 위한 스타일 태그 추가
function addFixStyleTag() {
  const styleTag = document.createElement('style');
  styleTag.innerHTML = `
    @media (max-width: 767px) {
      /* 모바일에서 테이블을 블록으로 표시하여 강제 가시성 확보 */
      table.table-mobile-stack {
        display: block !important;
        width: 100% !important;
        margin-bottom: 1rem !important;
      }
      
      /* 테이블 내용 강제 가시성 확보 */
      table.table-mobile-stack tbody {
        display: block !important;
        width: 100% !important;
      }
      
      /* 테이블 행 강제 가시성 확보 */
      table.table-mobile-stack tr {
        display: block !important;
        margin-bottom: 1rem !important;
      }
      
      /* 테이블 셀 강제 가시성 확보 */
      table.table-mobile-stack td,
      table.table-mobile-stack th {
        display: flex !important;
        width: 100% !important;
        text-align: left !important;
        align-items: center !important;
        padding: 8px 12px !important;
      }
      
      /* data-label 속성 표시 강제 */
      table.table-mobile-stack td:before,
      table.table-mobile-stack th:before {
        content: attr(data-label) !important;
        width: 40% !important;
        max-width: 120px !important;
        font-weight: 600 !important;
        color: #495057 !important;
        margin-right: 1rem !important;
        flex-shrink: 0 !important;
      }
      
      /* 특별히 배서 테이블을 위한 고급 스타일링 */
      #enderose_list_01, #enderose_list_02 {
        display: block !important;
      }
      
      #enderose_list_01 tr, #enderose_list_02 tr {
        background-color: #fff !important;
        border-radius: 8px !important;
        box-shadow: 0 3px 8px rgba(0,0,0,0.08) !important;
        margin-bottom: 12px !important;
        position: relative !important;
        border: 1px solid #e9ecef !important;
        animation: fadeIn 0.3s, slideUp 0.3s !important;
        overflow: hidden !important;
        display: block !important;
      }
      
      #enderose_list_01 td:first-child, #enderose_list_02 td:first-child {
        position: absolute !important;
        top: -8px !important;
        right: -8px !important;
        background: #f8f9fa !important;
        border-radius: 50% !important;
        width: 28px !important;
        height: 28px !important;
        display: flex !important;
        align-items: center !important;
        justify-content: center !important;
        font-size: 0.75rem !important;
        font-weight: bold !important;
        border: 2px solid white !important;
        padding: 0 !important;
        box-shadow: 0 2px 4px rgba(0,0,0,0.1) !important;
        z-index: 1 !important;
      }
      
      #enderose_list_01 td:first-child:before, 
      #enderose_list_02 td:first-child:before {
        display: none !important;
      }
      
      #enderose_list_01 td:nth-child(2),
      #enderose_list_02 td:nth-child(2) {
        font-weight: 600 !important;
        font-size: 1rem !important;
        padding-top: 12px !important;
        border-bottom: 1px dashed #e9ecef !important;
      }
      
      #enderose_list_01 td:last-child,
      #enderose_list_02 td:last-child {
        justify-content: flex-end !important;
        padding-bottom: 12px !important;
      }
    }
  `;
  
  // 스타일 태그를 문서에 추가
  document.head.appendChild(styleTag);
}

// 페이지 로드 시 스타일 태그 추가
document.addEventListener('DOMContentLoaded', function() {
  addFixStyleTag();
});

// 모바일 환경에서 테이블이 보이지 않는 문제를 해결하기 위한 MutationObserver 설정
function setupTableObserver() {
  // DOM 변경을 감시하는 MutationObserver 생성
  const observer = new MutationObserver(function(mutations) {
    mutations.forEach(function(mutation) {
      if (mutation.type === 'childList') {
        // 배서 테이블이 추가되었는지 확인
        const tables = document.querySelectorAll('table');
        if (tables.length > 0) {
          // 테이블 초기화
          initializeResponsiveTables();
        }
      }
    });
  });
  
  // 전체 문서 변경 관찰 시작
  observer.observe(document.body, { childList: true, subtree: true });
}

// 페이지 로드 시 관찰자 설정
document.addEventListener('DOMContentLoaded', function() {
  setupTableObserver();
});