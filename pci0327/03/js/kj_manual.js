/** kjstation.kr 대리운전 배서 **/

/**
 * 메뉴얼 페이지 초기화 함수
 * 호출 시 page-content 요소에 메뉴 및 컨텐츠 생성
 */
function kj_manual() {
    // DOM 요소 존재 여부 확인
    if (!document.getElementById('page-content')) {
        console.error('페이지 컨텐츠 요소가 존재하지 않습니다.');
        return;
    }
    // 페이지 컨텐츠 초기화
    const pageContent = document.getElementById('page-content');
    pageContent.innerHTML = "";
    
    // 메인 레이아웃 구성
    const fieldContents = `<div class="kje-list-container">
        <div class="kje-list-header">
            <div class="kje-left-area">
                <div class="kje-search-area">
                    <!-- 메뉴 구성 -->
                    <div class="kje-menu">
                        <button class="kje-menu-btn active" data-menu="article">기사찾기</button>
                        <button class="kje-menu-btn" data-menu="endorsement">배서리스트</button>
                        <button class="kje-menu-btn" data-menu="company">대리운전회사</button>
                        <button class="kje-menu-btn" data-menu="code">증권별코드</button>
                    </div>
                </div>
            </div>
            <div class="kje-right-area">
                
            </div>
        </div>
        <!-- 리스트 영역 -->
        <div class="kje-list-content">
            <div class="kje-data-table-container">
                <table class="kje-data-table">
                    <tbody id="manualList">
                        <!-- 데이터가 여기에 동적으로 로드됨 -->
                    </tbody>
                </table>
            </div>
        </div>
    </div>`;

    // 페이지 컨텐츠 업데이트
    pageContent.innerHTML = fieldContents;
    
    // 메뉴 이벤트 리스너 등록
    setupMenuListeners();
    
    // CSS 스타일 추가
    //addStyles();
    
    // DOM이 완전히 업데이트된 후 초기 콘텐츠 로드 (기사찾기)
    setTimeout(() => {
        loadArticleContent();
    }, 300);
}

/**
 * 메뉴 선택 이벤트 리스너 설정
 */
function setupMenuListeners() {
    // 메뉴 버튼이 존재하는지 확인
    const menuBtns = document.querySelectorAll('.kje-menu-btn');
    if (menuBtns.length === 0) {
        console.error('메뉴 버튼이 존재하지 않습니다.');
        return;
    }
    
    menuBtns.forEach(button => {
        button.addEventListener('click', function() {
            // 활성화된 버튼 클래스 전환
            document.querySelectorAll('.kje-menu-btn').forEach(btn => {
                btn.classList.remove('active');
            });
            this.classList.add('active');
            
            // 선택된 메뉴에 따라 컨텐츠 로드
            const menuType = this.dataset.menu;
            loadContent(menuType);
        });
    });
}

/**
 * 선택된 메뉴 유형에 따라 콘텐츠 로드
 * @param {string} menuType - 메뉴 유형 (article, endorsement, company, code)
 */
function loadContent(menuType) {
    const manualList = document.getElementById('manualList');
    
    // manualList 요소가 존재하는지 확인
    if (!manualList) {
        console.error('테이블 요소(manualList)가 존재하지 않습니다.');
        return;
    }
    
    // 로딩 메시지 표시
    manualList.innerHTML = '<tr><td colspan="5" class="loading">데이터 로드 중...</td></tr>';
    
    // 메뉴 유형에 따라 다른 컨텐츠 로드
    setTimeout(() => {
        switch(menuType) {
            case 'article':
                loadArticleContent();
                break;
            case 'endorsement':
                loadEndorsementList();
                break;
            case 'company':
                loadCompanyList();
                break;
            case 'code':
                loadCodeList();
                break;
            default:
                loadArticleContent();
        }
    }, 300);
}

/**
 * 기사찾기 컨텐츠 로드
 */
function loadArticleContent() {
    const manualList = document.getElementById('manualList');
    // 요소가 존재하는지 확인
    if (!manualList) {
        console.error('기사찾기: manualList 요소가 존재하지 않습니다.');
        return;
    }
    manualList.innerHTML = `
        <tr>
            <th>번호</th>
            <th>제목</th>
            <th>작성자</th>
            <th>날짜</th>
            <th>조회수</th>
        </tr>
        <tr>
            <td>1</td>
            <td>자동차보험 약관 개정 안내</td>
            <td>관리자</td>
            <td>2025-04-10</td>
            <td>235</td>
        </tr>
        <tr>
            <td>2</td>
            <td>대리운전 특약 신설 안내</td>
            <td>관리자</td>
            <td>2025-04-08</td>
            <td>189</td>
        </tr>
        <tr>
            <td>3</td>
            <td>보험금 청구 절차 간소화 안내</td>
            <td>관리자</td>
            <td>2025-04-05</td>
            <td>312</td>
        </tr>
    `;
}

/**
 * 배서리스트 컨텐츠 로드
 */
function loadEndorsementList() {
    const manualList = document.getElementById('manualList');
    // 요소가 존재하는지 확인
    if (!manualList) {
        console.error('배서리스트: manualList 요소가 존재하지 않습니다.');
        return;
    }
    manualList.innerHTML = `
        <tr>
            <th>배서번호</th>
            <th>배서명</th>
            <th>적용일자</th>
            <th>상태</th>
            <th>관리</th>
        </tr>
        <tr>
            <td>END-2025-001</td>
            <td>운전자 변경</td>
            <td>2025-04-11</td>
            <td>적용중</td>
            <td><button class="view-btn">상세보기</button></td>
        </tr>
        <tr>
            <td>END-2025-002</td>
            <td>차량 변경</td>
            <td>2025-04-10</td>
            <td>적용중</td>
            <td><button class="view-btn">상세보기</button></td>
        </tr>
        <tr>
            <td>END-2025-003</td>
            <td>특약 추가</td>
            <td>2025-04-07</td>
            <td>적용중</td>
            <td><button class="view-btn">상세보기</button></td>
        </tr>
    `;
}

/**
 * 대리운전회사 컨텐츠 로드
 */
function loadCompanyList() {
    const manualList = document.getElementById('manualList');
    // 요소가 존재하는지 확인
    if (!manualList) {
        console.error('대리운전회사: manualList 요소가 존재하지 않습니다.');
        return;
    }
    manualList.innerHTML = `
        <tr>
            <th>회사코드</th>
            <th>회사명</th>
            <th>연락처</th>
            <th>계약상태</th>
            <th>관리</th>
        </tr>
        <tr>
            <td>DC-0001</td>
            <td>안전 대리운전</td>
            <td>02-123-4567</td>
            <td>계약중</td>
            <td><button class="edit-btn">수정</button></td>
        </tr>
        <tr>
            <td>DC-0002</td>
            <td>행복 대리운전</td>
            <td>02-234-5678</td>
            <td>계약중</td>
            <td><button class="edit-btn">수정</button></td>
        </tr>
        <tr>
            <td>DC-0003</td>
            <td>스마트 대리운전</td>
            <td>02-345-6789</td>
            <td>계약중</td>
            <td><button class="edit-btn">수정</button></td>
        </tr>
    `;
}

/**
 * 증권별코드 컨텐츠 로드
 */
function loadCodeList() {
    const manualList = document.getElementById('manualList');
    // 요소가 존재하는지 확인
    if (!manualList) {
        console.error('증권별코드: manualList 요소가 존재하지 않습니다.');
        return;
    }
    manualList.innerHTML = `
        <tr>
            <th>증권코드</th>
            <th>증권명</th>
            <th>코드유형</th>
            <th>사용여부</th>
            <th>관리</th>
        </tr>
        <tr>
            <td>PC-001</td>
            <td>자동차보험</td>
            <td>기본</td>
            <td>사용중</td>
            <td><button class="detail-btn">세부코드</button></td>
        </tr>
        <tr>
            <td>PC-002</td>
            <td>운전자보험</td>
            <td>기본</td>
            <td>사용중</td>
            <td><button class="detail-btn">세부코드</button></td>
        </tr>
        <tr>
            <td>PC-003</td>
            <td>대리운전보험</td>
            <td>특약</td>
            <td>사용중</td>
            <td><button class="detail-btn">세부코드</button></td>
        </tr>
    `;
}


// 페이지 로드 후 kj_manual 함수 실행
// document.addEventListener('DOMContentLoaded', function() {
//     // 페이지가 준비되면 메뉴얼 페이지 초기화
//     kj_manual();
// });

// 기존 코드와의 호환성을 위해 자동 실행 코드는 주석 처리
// kj_manual() 함수는 외부에서 직접 호출해야 합니다.