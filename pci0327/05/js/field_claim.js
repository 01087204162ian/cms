/** 현장실습보험 클레임관련 js 파일 - 개선된 버전 **/

// 로딩 상태를 추적하는 플래그 변수
let isClaimLoading = false;
let currentClaimPage = 1; // 현재 페이지 추가
let currentClaimSearchKeyword = ''; // 현재 검색어 추가
let currentClaimSearchMode = 1; // 현재 검색 모드 추가

function fieldClaim(){
    const pageContent = document.getElementById('page-content');
    
    const claimContents = `
        <div class="c-list-container">
            <!-- 검색 영역 -->
            <div class="c-list-header">
                <!-- 데스크탑 검색 영역 -->
                <div class="c-left-area desktop-only">
                    <div class="c-search-area">
                        <select id="c-searchType" class='c-searchType' onChange='c_searchTypeChange()'>
                            <option value="1">증권번호</option>
                            <option value="2">사고접수번호</option>
                            <option value="3">학생</option>
                            <option value="4">계약자</option>
                        </select>
                        <input type="text" id="c-searchKeyword" class="c-searchKeyword" placeholder="증권번호를 입력하세요" onkeypress="if(event.key === 'Enter') c_searchList()">
                        <button class="c-search-button" onclick="c_searchList()">검색</button>
                    </div>
                </div>
                
                <!-- 모바일 검색 영역 -->
                <div class="c-mobile-search-area mobile-only">
                    <div class="mobile-filter-row">
                        <select id="c-searchType-mobile" class='c-searchType-mobile' onChange='c_searchTypeChange()'>
                            <option value="1">증권번호</option>
                            <option value="2">사고접수번호</option>
                            <option value="3">학생</option>
                            <option value="4">계약자</option>
                        </select>
                    </div>
                    <div class="mobile-filter-row">
                        <input type="text" id="c-searchKeyword-mobile" class="c-searchKeyword-mobile" placeholder="증권번호를 입력하세요" onkeypress="if(event.key === 'Enter') c_searchList()">
                    </div>
                    <div class="mobile-filter-row">
                        <button class="c-search-button-mobile" onclick="c_searchList()">검색</button>
                    </div>
                </div>
                
                <div class="c-right-area">
                    <button class="c-stats-button" onclick="c_showStatsModal()">통계</button>
                </div>
            </div>

            <!-- 리스트 영역 -->
            <div class="c-list-content">
                <!-- 데스크탑 테이블 -->
                <div class="c-data-table-container desktop-only">
                    <table class="c-data-table">
                        <thead>
                            <tr>
                                <th class="col-num">No</th>
                                <th class="col-business-num">사업자번호</th>
                                <th class="col-school">계약자</th>
                                <th class="col-students">증권번호</th>
                                <th class="col-phone">접수번호</th>
                                <th class="col-date">상태</th>
                                <th class="col-policy">보험금지급일</th>
                                <th class="col-premium">보험금</th>
                                <th class="col-insurance">학생</th>
                                <th class="col-status">사고일자</th>
                                <th class="col-contact">사고경위</th>
                                <th class="col-action">이메일</th>
                                <th class="col-memo">메모</th>
                                <th class="col-manager">담당자</th>
                            </tr>
                        </thead>
                        <tbody id="c-applicationList">
                            <tr><td colspan="14" class="loading">데이터 로드 중...</td></tr>
                        </tbody>
                    </table>
                </div>
                
                <!-- 모바일 카드 뷰 -->
                <div class="c-mobile-card-view mobile-only">
                    <div id="c-mobile-cards-container">
                        <!-- 모바일 카드가 여기에 동적으로 추가됨 -->
                    </div>
                </div>
            </div>

            <!-- 데스크탑 페이지네이션 -->
            <div class="c-pagination desktop-only"></div>
            
            <!-- 모바일 페이지네이션 -->
            <div id="c-mobile-pagination-container" class="mobile-pagination-container mobile-only">
                <ul id="c-mobile-pagination" class="mobile-pagination"></ul>
            </div>
        </div>`;

    pageContent.innerHTML = claimContents;
    
    // DOM 요소가 완전히 렌더링된 후에 데이터 로드
    setTimeout(() => {
        // 초기값 설정 후 첫 페이지 로드
        currentClaimPage = 1;
        currentClaimSearchKeyword = '';
        currentClaimSearchMode = 1;
        loadTable2(1, '', 1);
        
        // 모바일 검색 동기화 이벤트 리스너 추가
        setupClaimMobileSearchSync();
    }, 0);
}

// 모바일과 데스크탑 검색 동기화
function setupClaimMobileSearchSync() {
    const desktopSearchType = document.getElementById('c-searchType');
    const mobileSearchType = document.getElementById('c-searchType-mobile');
    const desktopSearchKeyword = document.getElementById('c-searchKeyword');
    const mobileSearchKeyword = document.getElementById('c-searchKeyword-mobile');
    
    if (desktopSearchType && mobileSearchType) {
        desktopSearchType.addEventListener('change', function() {
            mobileSearchType.value = this.value;
        });
        
        mobileSearchType.addEventListener('change', function() {
            desktopSearchType.value = this.value;
        });
    }
    
    if (desktopSearchKeyword && mobileSearchKeyword) {
        desktopSearchKeyword.addEventListener('input', function() {
            mobileSearchKeyword.value = this.value;
        });
        
        mobileSearchKeyword.addEventListener('input', function() {
            desktopSearchKeyword.value = this.value;
        });
    }
}

// 검색 타입 변경 시 placeholder 업데이트
function c_searchTypeChange() {
    const searchType = document.getElementById('c-searchType').value;
    const searchKeyword = document.getElementById('c-searchKeyword');
    const searchKeywordMobile = document.getElementById('c-searchKeyword-mobile');
    
    let placeholder = '';
    switch(searchType) {
        case '1': placeholder = '증권번호를 입력하세요'; break;
        case '2': placeholder = '사고접수번호를 입력하세요'; break;
        case '3': placeholder = '학생명을 입력하세요'; break;
        case '4': placeholder = '계약자명을 입력하세요'; break;
        default: placeholder = '검색어를 입력하세요';
    }
    
    if (searchKeyword) searchKeyword.placeholder = placeholder;
    if (searchKeywordMobile) searchKeywordMobile.placeholder = placeholder;
}

// 검색 함수
function c_searchList() {
    const searchType = document.getElementById('c-searchType').value;
    const searchKeyword = document.getElementById('c-searchKeyword').value;
    
    currentClaimPage = 1; // 검색 시 첫 페이지로 이동
    currentClaimSearchKeyword = searchKeyword;
    currentClaimSearchMode = parseInt(searchType);
    
    loadTable2(1, searchKeyword, parseInt(searchType));
}

function loadTable2(page = 1, searchSchool = '', searchMode = 1) {
    // 이미 로딩 중이면 중복 실행 방지
    if (isClaimLoading) {
        console.log('이미 클레임 데이터를 로드하고 있습니다.');
        return;
    }
    
    isClaimLoading = true; // 로딩 시작
    
    // 현재 상태 업데이트
    currentClaimPage = page;
    currentClaimSearchKeyword = searchSchool;
    currentClaimSearchMode = searchMode;
    
    const itemsPerPage = 15;
    const tableBody = document.querySelector("#c-applicationList");
    const mobileContainer = document.querySelector("#c-mobile-cards-container");
    const pagination = document.querySelector(".c-pagination");
    const mobilePagination = document.querySelector("#c-mobile-pagination");

    // 로딩 표시
    if (tableBody) {
        tableBody.innerHTML = '<tr><td colspan="14" class="loading">데이터 로드 중...</td></tr>';
    }
    if (mobileContainer) {
        mobileContainer.innerHTML = '<div class="mobile-loading">데이터 로드 중...</div>';
    }
    if (pagination) pagination.innerHTML = "";
    if (mobilePagination) mobilePagination.innerHTML = "";

    fetch(`https://silbo.kr/2025/api/claim/fetch_claim.php?page=${page}&limit=${itemsPerPage}&search_school=${searchSchool}&search_mode=${searchMode}`)
        .then(response => {
            // HTTP 상태 확인
            if (!response.ok) {
                throw new Error(`HTTP Error: ${response.status} - ${response.statusText}`);
            }
            
            // 응답 텍스트를 먼저 확인
            return response.text();
        })
        .then(text => {
            console.log('클레임 서버 응답 원본:', text); // 디버깅용
            
            // 빈 응답 체크
            if (!text || text.trim() === '') {
                throw new Error('서버에서 빈 응답을 받았습니다.');
            }
            
            // JSON 파싱 시도
            let response;
            try {
                response = JSON.parse(text);
            } catch (jsonError) {
                console.error('JSON 파싱 실패:', jsonError);
                console.error('응답 내용:', text);
                throw new Error(`JSON 파싱 오류: ${jsonError.message}`);
            }
            
            // 데스크탑 테이블용 HTML 생성
            let rows = "";
            // 모바일 카드용 HTML 생성
            let mobileCards = "";

            // 데이터 존재 여부 확인
            if (!response.data || response.data.length === 0) {
                rows = `<tr><td colspan="14" style="text-align: center;">검색 결과가 없습니다.</td></tr>`;
                mobileCards = `<div class="mobile-no-data">검색 결과가 없습니다.</div>`;
            } else {
                response.data.forEach((item, index) => {
                    // 현재 페이지의 실제 순번 계산
                    const actualIndex = (page - 1) * itemsPerPage + index + 1;
                    const formattedClaimAmout = item.claimAmout && !isNaN(item.claimAmout) ? parseFloat(item.claimAmout).toLocaleString("en-US") : "";
                    const formattedAccidentDescription = item.accidentDescription ? item.accidentDescription.substring(0, 30) : "";

                    const statusOptions = `
                        <select class="c-status-select" data-id="${item.num}">
                            <option value="1" ${item.ch == 1 ? "selected" : ""}>접수</option>
                            <option value="2" ${item.ch == 2 ? "selected" : ""}>미결</option>
                            <option value="3" ${item.ch == 3 ? "selected" : ""}>종결</option>
                            <option value="4" ${item.ch == 4 ? "selected" : ""}>면책</option>
                            <option value="5" ${item.ch == 5 ? "selected" : ""}>취소</option>
                        </select>
                    `;

                    // 데스크탑 테이블 행
                    rows += `<tr>
                        <td><a href="#" class="c-btn-link_1 c_1_open-claim-modal" data-num="${item.num}">${actualIndex}</a></td>
                        <td>${item.wdate}</td>
                        <td>${item.school1}</td>
                        <td>${item.certi}</td>
                        <td>${item.claimNumber}</td>
                        <td class='c-status-cell'>${statusOptions}</td>
                        <td>${item.wdate_2}</td>
                        <td class="c-preiminum">${formattedClaimAmout}</td>
                        <td>${item.student}</td>
                        <td>${item.wdate_3}</td>
                        <td>${formattedAccidentDescription}</td>
                        <td><a href="#" class="c-btn-link_1 upload-modal" data-num="${item.num}">업로드</a></td>
                        <td><input class='c-mText' type='text' value='${item.memo}' data-num="${item.num}"></td>
                        <td>${item.manager}</td>
                    </tr>`;

                    // 모바일 카드
                    mobileCards += `
                        <div class="c-mobile-card" data-num="${item.num}">
                            <div class="c-card-header">
                                <div class="c-card-number">${actualIndex}</div>
                                <div class="c-card-title">${item.school1}</div>
                            </div>
                            <div class="c-card-body">
                                <div class="c-card-row">
                                    <span class="c-card-label">사업자번호:</span>
                                    <span class="c-card-value">${item.wdate}</span>
                                </div>
                                <div class="c-card-row">
                                    <span class="c-card-label">계약자:</span>
                                    <span class="c-card-value">${item.school1}</span>
                                </div>
                                <div class="c-card-row">
                                    <span class="c-card-label">증권번호:</span>
                                    <span class="c-card-value">${item.certi}</span>
                                </div>
                                <div class="c-card-row">
                                    <span class="c-card-label">접수번호:</span>
                                    <span class="c-card-value">${item.claimNumber}</span>
                                </div>
                                <div class="c-card-row">
                                    <span class="c-card-label">상태:</span>
                                    <span class="c-card-value">${statusOptions}</span>
                                </div>
                                <div class="c-card-row">
                                    <span class="c-card-label">보험금지급일:</span>
                                    <span class="c-card-value">${item.wdate_2}</span>
                                </div>
                                <div class="c-card-row">
                                    <span class="c-card-label">보험금:</span>
                                    <span class="c-card-value">${formattedClaimAmout}</span>
                                </div>
                                <div class="c-card-row">
                                    <span class="c-card-label">학생:</span>
                                    <span class="c-card-value">${item.student}</span>
                                </div>
                                <div class="c-card-row">
                                    <span class="c-card-label">사고일자:</span>
                                    <span class="c-card-value">${item.wdate_3}</span>
                                </div>
                                <div class="c-card-row">
                                    <span class="c-card-label">사고경위:</span>
                                    <span class="c-card-value">${formattedAccidentDescription}</span>
                                </div>
                                <div class="c-card-row">
                                    <span class="c-card-label">담당자:</span>
                                    <span class="c-card-value">${item.manager}</span>
                                </div>
                                <div class="c-card-row">
                                    <span class="c-card-label">메모:</span>
                                    <span class="c-card-value">
                                        <input class='c-mText-mobile' type='text' value='${item.memo}' data-num="${item.num}">
                                    </span>
                                </div>
                            </div>
                            <div class="c-card-actions">
                                <button class="c-card-action-btn upload-modal" data-num="${item.num}">업로드</button>
                            </div>
                        </div>
                    `;
                });
            }

            if (tableBody) {
                tableBody.innerHTML = rows;
            }
            if (mobileContainer) {
                mobileContainer.innerHTML = mobileCards;
            }

            // 페이지네이션 생성 (데스크탑과 모바일 모두)
            const totalPages = Math.ceil(response.total / itemsPerPage);
            console.log('총 클레임 데이터 수:', response.total, '총 페이지 수:', totalPages, '현재 페이지:', page);
            renderClaimPagination(page, totalPages);
            renderClaimMobilePagination(page, totalPages);

        })
        .catch((error) => {
            console.error('클레임 데이터 로드 오류:', error);
            
            // 사용자에게 구체적인 오류 메시지 표시
            let errorMessage = "클레임 데이터를 불러오는 중 오류가 발생했습니다.";
            
            if (error.message.includes('JSON 파싱 오류')) {
                errorMessage = "서버 응답 형식에 문제가 있습니다. 관리자에게 문의하세요.";
            } else if (error.message.includes('HTTP Error')) {
                errorMessage = `서버 연결 오류: ${error.message}`;
            } else if (error.message.includes('빈 응답')) {
                errorMessage = "서버에서 응답을 받지 못했습니다.";
            }
            
            if (tableBody) {
                tableBody.innerHTML = `<tr><td colspan="14" style="text-align: center; color: red;">${errorMessage}</td></tr>`;
            }
            if (mobileContainer) {
                mobileContainer.innerHTML = `<div class="mobile-error">${errorMessage}</div>`;
            }
        })
        .finally(() => {
            isClaimLoading = false; // 로딩 완료
        });
}

// 데스크탑 페이지네이션 (완전히 새로 구현)
function renderClaimPagination(currentPage, totalPages) {
    const pagination = document.querySelector(".c-pagination");
    if (!pagination) return;
    
    // 전역 변수 업데이트
    currentClaimPage = currentPage;
    
    pagination.innerHTML = ""; // 기존 버튼 삭제

    // 페이지 범위 계산
    const maxPagesToShow = 5;
    let startPage = Math.max(1, currentPage - Math.floor(maxPagesToShow / 2));
    let endPage = Math.min(totalPages, startPage + maxPagesToShow - 1);

    // startPage 재조정 (끝에서 5개를 보여주기 위해)
    if (endPage - startPage + 1 < maxPagesToShow) {
        startPage = Math.max(1, endPage - maxPagesToShow + 1);
    }

    let paginationHTML = "";

    // 첫 페이지로 이동 버튼 (현재 페이지가 1보다 클 때만)
    if (currentPage > 1) {
        paginationHTML += `<a href="#" class="c-page-link" data-page="1">처음</a>`;
    }

    // 이전 버튼
    if (currentPage > 1) {
        paginationHTML += `<a href="#" class="c-page-link" data-page="${currentPage - 1}">이전</a>`;
    } else {
        paginationHTML += `<span class="c-disabled">이전</span>`;
    }

    // 숫자 페이지 버튼들
    for (let i = startPage; i <= endPage; i++) {
        if (i === currentPage) {
            paginationHTML += `<a href="#" class="c-page-link active" data-page="${i}">${i}</a>`;
        } else {
            paginationHTML += `<a href="#" class="c-page-link" data-page="${i}">${i}</a>`;
        }
    }

    // 다음 버튼
    if (currentPage < totalPages) {
        paginationHTML += `<a href="#" class="c-page-link" data-page="${currentPage + 1}">다음</a>`;
    } else {
        paginationHTML += `<span class="c-disabled">다음</span>`;
    }

    // 마지막 페이지로 이동 버튼 (현재 페이지가 마지막 페이지보다 작을 때만)
    if (currentPage < totalPages) {
        paginationHTML += `<a href="#" class="c-page-link" data-page="${totalPages}">마지막</a>`;
    }

    pagination.innerHTML = paginationHTML;

    // 이벤트 리스너 추가 (기존 이벤트 리스너 제거 후 새로 추가)
    pagination.querySelectorAll(".c-page-link").forEach(link => {
        link.addEventListener("click", function (e) {
            e.preventDefault();
            const targetPage = parseInt(this.dataset.page);
            if (targetPage && !isNaN(targetPage) && targetPage !== currentPage) {
                console.log(`클레임 페이지 이동: ${currentPage} → ${targetPage}`);
                loadTable2(targetPage, currentClaimSearchKeyword, currentClaimSearchMode);
            }
        });
    });
}

// 모바일 페이지네이션 (개선된 버전)
function renderClaimMobilePagination(currentPage, totalPages) {
    const mobilePagination = document.querySelector("#c-mobile-pagination");
    if (!mobilePagination) return;
    
    mobilePagination.innerHTML = ""; // 기존 버튼 삭제

    let mobileHTML = "";

    // 처음 페이지 버튼 (현재 페이지가 1보다 클 때만)
    if (currentPage > 1) {
        mobileHTML += `<li><a href="#" class="c-mobile-page-link" data-page="1">≪</a></li>`;
    }

    // 이전 버튼
    if (currentPage > 1) {
        mobileHTML += `<li><a href="#" class="c-mobile-page-link" data-page="${currentPage - 1}">‹</a></li>`;
    }

    // 현재 페이지 정보 표시
    mobileHTML += `<li class="c-mobile-page-info">${currentPage} / ${totalPages}</li>`;

    // 다음 버튼
    if (currentPage < totalPages) {
        mobileHTML += `<li><a href="#" class="c-mobile-page-link" data-page="${currentPage + 1}">›</a></li>`;
    }

    // 마지막 페이지 버튼 (현재 페이지가 마지막 페이지보다 작을 때만)
    if (currentPage < totalPages) {
        mobileHTML += `<li><a href="#" class="c-mobile-page-link" data-page="${totalPages}">≫</a></li>`;
    }

    mobilePagination.innerHTML = mobileHTML;

    // 모바일 페이지 이동 이벤트 추가
    mobilePagination.querySelectorAll(".c-mobile-page-link").forEach(link => {
        link.addEventListener("click", function (e) {
            e.preventDefault();
            const targetPage = parseInt(this.dataset.page);
            if (targetPage && !isNaN(targetPage) && targetPage !== currentPage) {
                console.log(`클레임 모바일 페이지 이동: ${currentPage} → ${targetPage}`);
                loadTable2(targetPage, currentClaimSearchKeyword, currentClaimSearchMode);
            }
        });
    });
}

// 기존 함수들과의 호환성을 위한 별칭
function renderPagination2(currentPage, totalPages) {
    renderClaimPagination(currentPage, totalPages);
}
function c_searchTypeChange(){
	 const searchType = document.getElementById("c-searchType");
    const searchKeyword = document.getElementById("c-searchKeyword");

    // 요소가 존재하지 않을 경우 에러 방지
    if (!searchType || !searchKeyword) {
        console.error("오류: 검색 타입 또는 검색어 입력 요소를 찾을 수 없습니다.");
        return;
    }

    // 옵션에 따른 placeholder 설정
    const placeholderMap = {
        "1": "증권번호를 입력하세요",
        "2": "사고접수번호를 입력하세요",
        "3": "학생 이름을 입력하세요",
        "4": "계약자명을 입력하세요"
    };
	 searchKeyword.placeholder = placeholderMap[searchType.value] || "검색어를 입력하세요";

}


/* 검색 버튼*/
function c_searchList() {
    const searchType = document.querySelector("#c-searchType").value;
    const searchKeyword = document.querySelector("#c-searchKeyword").value.trim();

    if (!searchKeyword) {
        alert("검색어를 입력하세요.");
        return;
    }

    loadTable2(1, searchKeyword, searchType);
}

document.addEventListener("DOMContentLoaded", function () {
    // 페이지 로드 시 자동으로 모달을 열지 않도록 함
    const modal = document.getElementById("sjModal");
    if (modal) {
        modal.style.display = "none"; // 필요시 강제로 숨김
    }
});

function c_showStatsModal(){
    document.getElementById("c_changeP").innerHTML = "";
    c_perFormance(); // 실적 조회 함수 실행
    document.getElementById("sjModal").style.display = "block";
}

// 모달 외부 클릭 시 닫기 기능 추가
document.addEventListener("DOMContentLoaded", function () {
    const modal = document.getElementById("sjModal");
    if (modal) {
        modal.addEventListener("click", function(event) {
            if (event.target === modal) {
                modal.style.display = "none";
            }
        });
    }
});



function c_perFormance() {
    console.log("📌 모달 오픈 & 데이터 요청");

    const modal = document.getElementById("sjModal");
   // modal.style.display = "flex"; // 모달 표시

    
    // 연도 선택 드롭다운 동적 생성 (최근 5년)
		showSelectedYear();
		// 페이지 로딩 시 자동 실행 서버데이터 가져오기 
        fetchData();
		//updateButtons(); // 버튼 정의 
		c_insertFooterButtons(); // ✅ 모달 푸터 버튼 삽입
    
}
function showSelectedYear() {
    const yearContainer1 = document.getElementById("yearContainer1");

    if (!yearContainer1) {
        console.warn("🚨 'yearContainer1' 요소를 찾을 수 없습니다. 실행을 중단합니다.");
        return; // 요소가 없으면 함수 실행 중단
    }

    yearContainer1.innerHTML = ""; // 기존 내용 초기화

    const currentYear = new Date().getFullYear();

    // <select> 요소 동적 생성
    const c_yearSelect = document.createElement("select");
    c_yearSelect.id = "c_yearSelect";
    c_yearSelect.onchange = function() {
        fetchData(); // 데이터 로드 함수 호출
    };

    // 연도 옵션 추가 (최근 5년)
    for (let i = currentYear; i >= currentYear - 4; i--) {
        let option = document.createElement("option");
        option.value = i;
        option.textContent = i + "년"; // "2025년" 형식으로 표시
        c_yearSelect.appendChild(option);
    }

    yearContainer1.appendChild(c_yearSelect);
}

function c_insertFooterButtons() {
    const footerContainer = document.getElementById("c_changeP");

    // 기존 내용 초기화
    footerContainer.innerHTML = ""; 

    let ptr = "";
    ptr += `<button id="conPerformanceBtn" class="c-btn">계약자별 실적</button>`;
    ptr += `<button id="c_yearPerformanceBtn" class="c-btn">년도별 실적</button>`;

    footerContainer.innerHTML = ptr; // HTML 동적 삽입

    // ✅ DOM이 업데이트될 시간을 주기 위해 setTimeout 사용
    setTimeout(() => {
        const c_yearPerformanceBtn = document.getElementById("c_yearPerformanceBtn");
        if (c_yearPerformanceBtn) {
            c_yearPerformanceBtn.addEventListener("click", c__yearPerFormance);
            console.log("📌 '년별 실적' 버튼 이벤트 바인딩 완료!");
        } else {
            console.error("🚨 '년별 실적' 버튼을 찾을 수 없습니다!");
        }
    }, 50); // 50ms 딜레이 후 실행

	// ✅ DOM이 업데이트될 시간을 주기 위해 setTimeout 사용
    setTimeout(() => {
        const conPerformanceBtn = document.getElementById("conPerformanceBtn");
        if (conPerformanceBtn) {
            conPerformanceBtn.addEventListener("click",ContractorPerformance);
            console.log("📌 '계약자별 실적' 버튼 이벤트 바인딩 완료!");
        } else {
            console.error("🚨 '계약자별 실적' 버튼을 찾을 수 없습니다!");
        }
    }, 50); // 50ms 딜레이 후 실행
}
// 서버에서 연도별 데이터를 가져오기
function fetchData() {
		
	let selectedYear = document.getElementById("c_yearSelect").value;
	fetch(`https://silbo.kr/2025/api/claim/get_claim_summary.php?year=${selectedYear}`)
		.then(response => response.json())
		.then(data => updateTable(data))
		.catch(error => console.error("데이터 로드 오류:", error));
}

function updateTable(jsonData) {
    let claimData = {};
    
    // 12개월 기본 구조 생성
    for (let i = 1; i <= 12; i++) {
        let month = `${c_yearSelect.value}-${String(i).padStart(2, '0')}`;
        claimData[month] = { 
            received: 0, pending: 0, completed: 0, exempted: 0, canceled: 0, 
            total: 0, claimAmount: 0, totalPremium: 0, lossRatio: 0 
        };
    }

    // "claims" 데이터 처리
    jsonData.claims.forEach(item => {
        let month = item.yearMonth;
        if (!claimData[month]) return;

        switch (parseInt(item.ch)) {
            case 1: claimData[month].received += parseInt(item.count); break;
            case 2: claimData[month].pending += parseInt(item.count); break;
            case 3:
                claimData[month].completed += parseInt(item.count);
                claimData[month].claimAmount += parseInt(item.total_claim_amount || 0); // 종결된 보험금 합산
                break;
            case 4: claimData[month].exempted += parseInt(item.count); break;
            case 5: claimData[month].canceled += parseInt(item.count); break;
        }
        claimData[month].total += parseInt(item.count);
    });

    // "premiums" 데이터 처리 (보험료 합산)
    jsonData.premiums.forEach(item => {
        let month = item.yearMonth;
        if (!claimData[month]) return;
        claimData[month].totalPremium += parseInt(item.total_premium || 0);
    });

    // 손해율 계산 (보험금 / 보험료 * 100)
    Object.keys(claimData).forEach(month => {
        let row = claimData[month];
        row.lossRatio = row.totalPremium > 0 ? ((row.claimAmount / row.totalPremium) * 100).toFixed(2) : "";
    });

    // 테이블 업데이트
    let tbody = document.querySelector("#claimTable tbody");
    tbody.innerHTML = "";
    let totalReceived = 0, totalPending = 0, totalCompleted = 0, totalExempted = 0, 
        totalCanceled = 0, totalAll = 0, totalClaimAmount = 0, totalPremiumAmount = 0, totalLossRatio = 0;
    tbody.innerHTML += `<thead>
									<tr>
										<th>년 월</th>
										<th>접수</th>
										<th>미결</th>
										<th>종결</th>
										<th>면책</th>
										<th>취소</th>
										<th>계</th>
										<th>종결 보험금 합계</th>
										<th>보험료 합계</th>
										<th>손해율</th>
									</tr>
								</thead>`
    Object.keys(claimData).forEach(month => {
        let row = claimData[month];

        tbody.innerHTML += `
            <tr>
                <th>${month}</th>
                <td>${row.received > 0 ? row.received : ""}</td>
                <td>${row.pending > 0 ? row.pending : ""}</td>
                <td>${row.completed > 0 ? row.completed : ""}</td>
                <td>${row.exempted > 0 ? row.exempted : ""}</td>
                <td>${row.canceled > 0 ? row.canceled : ""}</td>
                <td>${row.total > 0 ? row.total : ""}</td>
                <td>${row.claimAmount > 0 ? row.claimAmount.toLocaleString() : ""}</td> <!-- 종결된 보험금 -->
                <td>${row.totalPremium > 0 ? row.totalPremium.toLocaleString() : ""}</td> <!-- 보험료 -->
                <td>${row.lossRatio ? row.lossRatio + "%" : ""}</td> <!-- 손해율 -->
            </tr>
        `;
		
        totalReceived += row.received;
        totalPending += row.pending;
        totalCompleted += row.completed;
        totalExempted += row.exempted;
        totalCanceled += row.canceled;
        totalAll += row.total;
        totalClaimAmount += row.claimAmount;
        totalPremiumAmount += row.totalPremium;
    });
	tbody.innerHTML += `<tfoot>
										<tr>
											<th>소계</th>
											<td id="totalReceived">0</td>
											<td id="totalPending">0</td>
											<td id="totalCompleted">0</td>
											<td id="totalExempted">0</td>
											<td id="totalCanceled">0</td>
											<td id="totalAll">0</td>
											<td id="totalClaimAmount">0</td>
											<td id="totalPremiumAmount">0</td> <!-- 보험료 합계 추가 -->
											<td id="totalLossRatio">0</td> <!-- 손해율 -->
										</tr>
									</tfoot>`
    // 전체 손해율 계산 (총 보험금 / 총 보험료 * 100)
    totalLossRatio = totalPremiumAmount > 0 ? ((totalClaimAmount / totalPremiumAmount) * 100).toFixed(2) : "";

    // 소계 업데이트
    document.getElementById("totalReceived").textContent = totalReceived > 0 ? totalReceived : "";
    document.getElementById("totalPending").textContent = totalPending > 0 ? totalPending : "";
    document.getElementById("totalCompleted").textContent = totalCompleted > 0 ? totalCompleted : "";
    document.getElementById("totalExempted").textContent = totalExempted > 0 ? totalExempted : "";
    document.getElementById("totalCanceled").textContent = totalCanceled > 0 ? totalCanceled : "";
    document.getElementById("totalAll").textContent = totalAll > 0 ? totalAll : "";
    document.getElementById("totalClaimAmount").textContent = totalClaimAmount > 0 ? totalClaimAmount.toLocaleString() : "";
    document.getElementById("totalPremiumAmount").textContent = totalPremiumAmount > 0 ? totalPremiumAmount.toLocaleString() : "";
    document.getElementById("totalLossRatio").textContent = totalLossRatio ? totalLossRatio + "%" : ""; // 손해율 표시
}






	


//년별 실적 //
function c__yearPerFormance(){
	showSelectedYear2()
	c_insertFooterButtons2(); // ✅ 모달 푸터 버튼 삽입updateButtonsYear();  
	
	TableInit(); //소계부분 초기 
	fetchYearlyData();
}

function c_insertFooterButtons2() {
    const footerContainer = document.getElementById("c_changeP");

    // 기존 내용 초기화
    footerContainer.innerHTML = ""; 

    let ptr = "";
    ptr += `<button id="conPerformanceBtn" class="c-btn">계약자별 실적</button>`;
    ptr += `<button id="c_performanceBtn" class="c-btn">월별 실적</button>`;

    footerContainer.innerHTML = ptr; // HTML 동적 삽입

    // ✅ DOM이 업데이트될 시간을 주기 위해 setTimeout 사용
    setTimeout(() => {
        const c_performanceBtn = document.getElementById("c_performanceBtn");
        if (c_performanceBtn) {
            c_performanceBtn.addEventListener("click", c_perFormance);
            console.log("📌 '월별 실적' 버튼 이벤트 바인딩 완료!");
        } else {
            console.error("🚨 '월년별 실적' 버튼을 찾을 수 없습니다!");
        }
    }, 50); // 50ms 딜레이 후 실행

	// ✅ DOM이 업데이트될 시간을 주기 위해 setTimeout 사용
    setTimeout(() => {
        const conPerformanceBtn = document.getElementById("conPerformanceBtn");
        if (conPerformanceBtn) {
            conPerformanceBtn.addEventListener("click",ContractorPerformance);
            console.log("📌 '계약자별 실적' 버튼 이벤트 바인딩 완료!");
        } else {
            console.error("🚨 '계약자별 실적' 버튼을 찾을 수 없습니다!");
        }
    }, 50); // 50ms 딜레이 후 실행
}
function showSelectedYear2(){

	   document.getElementById("yearContainer1").innerHTML = "";

		const currentYear = new Date().getFullYear();
		const yearContainer1 = document.getElementById("yearContainer1");
		

		// <select> 요소 동적 생성
		const c_yearSelect = document.createElement("select");
		c_yearSelect.id = "c_yearSelect";
		c_yearSelect.onchange = function() {
			fetchYearlyData(); // 데이터 로드 함수 호출
			
		};

		// 연도 옵션 추가 (최근 5년)
		for (let i = currentYear; i >= currentYear - 4; i--) {
			let option = document.createElement("option");
			option.value = i;
			option.textContent = i + "년"; // "2025년" 형식으로 표시
			 c_yearSelect.appendChild(option);
		}

		

		// 생성한 <select> 요소를 #yearContainer 안에 추가
		yearContainer1.appendChild(c_yearSelect);
}



function TableInit(){
	let tbody = document.querySelector("#claimTable tbody");
	tbody.innerHTML = "";

}
function fetchYearlyData() {
    let selectedYear = document.getElementById("c_yearSelect").value; // 선택된 연도 가져오기

	
    fetch(`https://silbo.kr/2025/api/claim/get_yearly_summary.php?year=${selectedYear}`)
        .then(response => response.json())
        .then(data => updateYearlyTable(data))
        .catch(error => console.error("데이터 로드 오류:", error));

}

function updateYearlyTable(jsonData) {
    let yearData = {};
    let startYear = parseInt(document.getElementById("c_yearSelect").value) - 9; // 최근 10년

    // 소계 변수 초기화
    let totalReceived = 0, totalPending = 0, totalCompleted = 0, totalExempted = 0, totalCanceled = 0;
    let totalClaimAmount = 0, totalPremiumAmount = 0, totalLossRatio = 0, yearCount = 0;

    // 최근 10년 초기화
    for (let i = startYear; i <= parseInt(document.getElementById("c_yearSelect").value); i++) {
        yearData[i] = { 
            received: 0, pending: 0, completed: 0, exempted: 0, canceled: 0, 
            claimAmount: 0, totalPremium: 0, lossRatio: 0 
        };
    }

    // "claims" 데이터 처리
    jsonData.claims.forEach(item => {
        let year = item.claimYear;
        if (!yearData[year]) return;

        switch (parseInt(item.ch)) {
            case 1: yearData[year].received += parseInt(item.count); break;
            case 2: yearData[year].pending += parseInt(item.count); break;
            case 3:
                yearData[year].completed += parseInt(item.count);
                yearData[year].claimAmount += parseInt(item.total_claim_amount || 0);
                break;
            case 4: yearData[year].exempted += parseInt(item.count); break;
            case 5: yearData[year].canceled += parseInt(item.count); break;
        }
    });

    // "premiums" 데이터 처리
    jsonData.premiums.forEach(item => {
        let year = item.premiumYear;
        if (!yearData[year]) return;
        yearData[year].totalPremium += parseInt(item.total_premium || 0);
    });

    // 손해율 계산 (보험금 / 보험료 * 100)
    Object.keys(yearData).forEach(year => {
        let row = yearData[year];
        row.lossRatio = row.totalPremium > 0 ? ((row.claimAmount / row.totalPremium) * 100).toFixed(2) : "";

        // 소계 계산
        totalReceived += row.received;
        totalPending += row.pending;
        totalCompleted += row.completed;
        totalExempted += row.exempted;
        totalCanceled += row.canceled;
        totalClaimAmount += row.claimAmount;
        totalPremiumAmount += row.totalPremium;
        yearCount++;
    });

    // 전체 손해율 계산 (총 보험금 / 총 보험료 * 100)
    totalLossRatio = totalPremiumAmount > 0 ? ((totalClaimAmount / totalPremiumAmount) * 100).toFixed(2) : "";
    let totalAll = totalReceived + totalPending + totalCompleted + totalExempted + totalCanceled; // 총합

    // 테이블 업데이트
    let tbody = document.querySelector("#claimTable tbody");
    tbody.innerHTML = "";
	tbody.innerHTML += `<thead>
									<tr>
										<th>년 </th>
										<th>접수</th>
										<th>미결</th>
										<th>종결</th>
										<th>면책</th>
										<th>취소</th>
										<th>계</th>
										<th>종결 보험금 합계</th>
										<th>보험료 합계</th>
										<th>손해율</th>
									</tr>
								</thead>`
    Object.keys(yearData).forEach(year => {
        let row = yearData[year];

        tbody.innerHTML += `
            <tr>
                <th>${year}</th>
                <td>${row.received > 0 ? row.received : ""}</td>
                <td>${row.pending > 0 ? row.pending : ""}</td>
                <td>${row.completed > 0 ? row.completed : ""}</td>
                <td>${row.exempted > 0 ? row.exempted : ""}</td>
                <td>${row.canceled > 0 ? row.canceled : ""}</td>
                <td>${(row.received + row.pending + row.completed + row.exempted + row.canceled) > 0 ? (row.received + row.pending + row.completed + row.exempted + row.canceled) : ""}</td>
                <td>${row.claimAmount > 0 ? row.claimAmount.toLocaleString() : ""}</td>
                <td>${row.totalPremium > 0 ? row.totalPremium.toLocaleString() : ""}</td>
                <td>${row.lossRatio ? row.lossRatio + "%" : ""}</td>
            </tr>
        `;
    });
	tbody.innerHTML += `<tfoot>
										<tr>
											<th>소계</th>
											<td id="totalReceived" >0</td>
											<td id="totalPending">0</td>
											<td id="totalCompleted">0</td>
											<td id="totalExempted">0</td>
											<td id="totalCanceled">0</td>
											<td id="totalAll" >0</td>
											<td id="totalClaimAmount">0</td>
											<td id="totalPremiumAmount" >0</td> <!-- 보험료 합계 추가 -->
											<td id="totalLossRatio" >0</td> <!-- 손해율 -->
										</tr>
									</tfoot>`
    // 합계 업데이트
    document.getElementById("totalReceived").textContent = totalReceived > 0 ? totalReceived : "";
    document.getElementById("totalPending").textContent = totalPending > 0 ? totalPending : "";
    document.getElementById("totalCompleted").textContent = totalCompleted > 0 ? totalCompleted : "";
    document.getElementById("totalExempted").textContent = totalExempted > 0 ? totalExempted : "";
    document.getElementById("totalCanceled").textContent = totalCanceled > 0 ? totalCanceled : "";
    document.getElementById("totalAll").textContent = totalAll > 0 ? totalAll : "";
    document.getElementById("totalClaimAmount").textContent = totalClaimAmount > 0 ? totalClaimAmount.toLocaleString() : "";
    document.getElementById("totalPremiumAmount").textContent = totalPremiumAmount > 0 ? totalPremiumAmount.toLocaleString() : "";
    document.getElementById("totalLossRatio").textContent = totalLossRatio ? totalLossRatio + "%" : ""; // 손해율 표시
}



//계약자별 실적

function ContractorPerformance(){
	
	showSelectedYear3()
	c_insertFooterButtons3();
	
	TableInit(); //소계부분 초기 
	fetchContractorData();
}

function showSelectedYear3() {

	document.getElementById("yearContainer1").innerHTML = "";

		const currentYear = new Date().getFullYear();
		const yearContainer1 = document.getElementById("yearContainer1");
		

		// <select> 요소 동적 생성
		const c_yearSelect = document.createElement("select");
		c_yearSelect.id = "c_yearSelect";
		c_yearSelect.onchange = function() {
			fetchContractorData(); // 데이터 로드 함수 호출
			
		};

		// 연도 옵션 추가 (최근 5년)
		for (let i = currentYear; i >= currentYear - 4; i--) {
			let option = document.createElement("option");
			option.value = i;
			option.textContent = i + "년"; // "2025년" 형식으로 표시
			 c_yearSelect.appendChild(option);
		}

		

		// 생성한 <select> 요소를 #yearContainer 안에 추가
		yearContainer1.appendChild(c_yearSelect);
}
function c_insertFooterButtons3() {
    const footerContainer = document.getElementById("c_changeP");

    // 기존 내용 초기화
    footerContainer.innerHTML = ""; 

    let ptr = "";
    ptr += `<button id="c_yearPerformanceBtn" class="c-btn">년도별실적</button>`;
    ptr += `<button id="c_performanceBtn" class="c-btn">월별 실적</button>`;

    footerContainer.innerHTML = ptr; // HTML 동적 삽입

    // ✅ DOM이 업데이트될 시간을 주기 위해 setTimeout 사용
    setTimeout(() => {
        const c_performanceBtn = document.getElementById("c_performanceBtn");
        if (c_performanceBtn) {
            c_performanceBtn.addEventListener("click", c_perFormance);
            console.log("📌 '월별 실적' 버튼 이벤트 바인딩 완료!");
        } else {
            console.error("🚨 '월별 실적' 버튼을 찾을 수 없습니다!");
        }
    }, 50); // 50ms 딜레이 후 실행

	// ✅ DOM이 업데이트될 시간을 주기 위해 setTimeout 사용
    setTimeout(() => {
        const c_yearPerformanceBtn = document.getElementById("c_yearPerformanceBtn");
        if (c_yearPerformanceBtn) {
            c_yearPerformanceBtn.addEventListener("click", c__yearPerFormance);
            console.log("📌 '년도별 실적' 버튼 이벤트 바인딩 완료!");
        } else {
            console.error("🚨 '년도별 실적' 버튼을 찾을 수 없습니다!");
        }
    }, 50); // 50ms 딜레이 후 실행
}
function fetchContractorData() {
    let selectedYear = document.getElementById("c_yearSelect").value; // 선택된 연도 가져오기

    
	fetch(`https://silbo.kr/2025/api/claim/get_contractor_summary.php?year=${selectedYear}`)
    .then(response => response.json())
    .then(data => {
        if (!Array.isArray(data)) {
            console.warn("🚨 서버 응답이 배열이 아닙니다. 빈 배열을 사용합니다.");
            data = []; // 배열이 아닌 경우 빈 배열로 설정
        }
        updateContractorPerformance(data);
    })
    .catch(error => {
        console.error("🚨 데이터 로드 오류:", error);
        updateContractorPerformance([]); // 오류 발생 시 빈 배열로 초기화
    });
}



function updateContractorPerformance(jsonData) {
    if (!Array.isArray(jsonData)) {
        console.warn("🚨 서버 응답이 배열이 아닙니다. 빈 배열을 사용합니다.");
        jsonData = []; // 배열이 아닌 경우 빈 배열로 설정
    }

    let tableBody = document.getElementById("claimTable").querySelector("tbody");
    tableBody.innerHTML = "";

    // 소계 변수 초기화
    let totalReceived = 0, totalPending = 0, totalCompleted = 0, totalExempted = 0, totalCanceled = 0;
    let totalClaimAmount = 0, totalPremiumAmount = 0, totalLossRatio = 0;
	tableBody.innerHTML += `<thead>
									<tr>
										<th>계약자</th>
										<th>접수</th>
										<th>미결</th>
										<th>종결</th>
										<th>면책</th>
										<th>취소</th>
										<th>계</th>
										<th>종결 보험금 합계</th>
										<th>보험료 합계</th>
										<th>손해율</th>
									</tr>
								</thead>`
    jsonData.forEach(item => {
        let schoolName = item.school1 && item.school1.trim() !== "" ? item.school1 : "N/A"; // 빈 값 처리
        let received = parseInt(item.received) || 0;
        let pending = parseInt(item.pending) || 0;
        let completed = parseInt(item.completed) || 0;
        let exempted = parseInt(item.exempted) || 0;
        let canceled = parseInt(item.canceled) || 0;
        let totalClaimAmountValue = parseInt(item.total_claim_amount) || 0;
        let totalPremiumValue = parseInt(item.total_premium) || 0;
        let totalCases = received + pending + completed + exempted + canceled; // 총 건수 계산

        // 손해율 계산 (보험금 / 보험료 * 100)
        let lossRatio = totalPremiumValue > 0 ? ((totalClaimAmountValue / totalPremiumValue) * 100).toFixed(2) + "%" : "";

        // 소계 누적
        totalReceived += received;
        totalPending += pending;
        totalCompleted += completed;
        totalExempted += exempted;
        totalCanceled += canceled;
        totalClaimAmount += totalClaimAmountValue;
        totalPremiumAmount += totalPremiumValue;

        let row = `
            <tr>
                <td>${schoolName}</td>
                <td>${received > 0 ? received : ""}</td>
                <td>${pending > 0 ? pending : ""}</td>
                <td>${completed > 0 ? completed : ""}</td>
                <td>${exempted > 0 ? exempted : ""}</td>
                <td>${canceled > 0 ? canceled : ""}</td>
                <td>${totalCases > 0 ? totalCases : ""}</td>
                <td>${totalClaimAmountValue > 0 ? totalClaimAmountValue.toLocaleString() : ""}</td>
                <td>${totalPremiumValue > 0 ? totalPremiumValue.toLocaleString() : ""}</td>
                <td>${lossRatio}</td>
            </tr>
        `;
        tableBody.innerHTML += row;
    });
	tableBody.innerHTML += `<tfoot>
										<tr>
											<th>소계</th>
											<td id="totalReceived">0</td>
											<td id="totalPending">0</td>
											<td id="totalCompleted">0</td>
											<td id="totalExempted">0</td>
											<td id="totalCanceled">0</td>
											<td id="totalAll">0</td>
											<td id="totalClaimAmount">0</td>
											<td id="totalPremiumAmount">0</td> <!-- 보험료 합계 추가 -->
											<td id="totalLossRatio">0</td> <!-- 손해율 -->
										</tr>
									</tfoot>`
    // 전체 손해율 계산
    totalLossRatio = totalPremiumAmount > 0 ? ((totalClaimAmount / totalPremiumAmount) * 100).toFixed(2) + "%" : "";
    let totalAll = totalReceived + totalPending + totalCompleted + totalExempted + totalCanceled; // 전체 총합

    // 합계 업데이트
    document.getElementById("totalReceived").textContent = totalReceived > 0 ? totalReceived : "";
    document.getElementById("totalPending").textContent = totalPending > 0 ? totalPending : "";
    document.getElementById("totalCompleted").textContent = totalCompleted > 0 ? totalCompleted : "";
    document.getElementById("totalExempted").textContent = totalExempted > 0 ? totalExempted : "";
    document.getElementById("totalCanceled").textContent = totalCanceled > 0 ? totalCanceled : "";
    document.getElementById("totalAll").textContent = totalAll > 0 ? totalAll : "";
    document.getElementById("totalClaimAmount").textContent = totalClaimAmount > 0 ? totalClaimAmount.toLocaleString() : "";
    document.getElementById("totalPremiumAmount").textContent = totalPremiumAmount > 0 ? totalPremiumAmount.toLocaleString() : "";
    document.getElementById("totalLossRatio").textContent = totalLossRatio; // 손해율 추가
}
// 연도 표현 함수

//  클레임 상태 변경함수 
document.addEventListener("change", function (e) {
   
	// 변경된 요소가 status-select 클래스인지 확인
    if (e.target.classList.contains("c-status-select")) {
        const num = e.target.dataset.id;  // data-id 속성에서 num 값 가져오기
        const selectedValue = e.target.value;  // 선택된 옵션 값 가져오기
        
        // 상태 변경 함수 호출
        claimHandleStatusChange(num, selectedValue);
    }
});


// 상태 변경 함수 (num, 선택값 받아서 처리)
function claimHandleStatusChange(num, selectedValue) {
    fetch(`https://silbo.kr/2025/api/claim/claim_update_status.php`, {
        method: "POST",
        headers: {
            "Content-Type": "application/x-www-form-urlencoded",
        },
        body: `id=${num}&ch=${selectedValue}`,
    })
    .then(response => response.json())
    .then(data => {
        if (data.success) {
            alert("상태가 성공적으로 업데이트되었습니다.");
        } else {
            alert("상태 업데이트 중 오류가 발생했습니다.");
        }
    })
    .catch(error => {
        console.error("상태 업데이트 오류:", error);
        alert("서버 오류로 인해 상태를 변경할 수 없습니다.");
    });
}

//메모 
// 메모 업데이트 (blur 이벤트)
document.addEventListener("keypress", function (e) {
    if (e.target.classList.contains("c-mText") && e.key === "Enter") {
        e.preventDefault(); // 기본 엔터 동작 방지 (폼 제출 방지)

        const memo = e.target.value.trim();
        const num = e.target.dataset.num;

        if (!memo) {
            alert("메모를 입력해주세요.");
            return;
        }

        fetch(`https://silbo.kr/2025/api/claim/update_memo.php`, {
            method: "POST",
            headers: { "Content-Type": "application/x-www-form-urlencoded" },
            body: `num=${num}&memo=${encodeURIComponent(memo)}`
        })
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                alert("메모가 성공적으로 수정되었습니다.");
            } else {
                alert("메모 수정 중 오류가 발생했습니다.");
            }
        })
        .catch(() => {
            alert("메모 업데이트 요청 실패.");
        });
    }
});