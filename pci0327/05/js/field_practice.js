/** 현장실습보험 관련 js 파일 - 반응형 개선 버전 **/

// 로딩 상태를 추적하는 플래그 변수
let isLoading = false;
let currentFiedPracticePage = 1; // 현재 페이지 추가
let currentSearchKeyword = ''; // 현재 검색어 추가
let currentSearchMode = 1; // 현재 검색 모드 추가

function fieldList(){
    const pageContent = document.getElementById('page-content');
    
    const fieldContents = `
        <div class="f-list-container">
            <!-- 검색 영역 -->
            <div class="f-list-header">
                <!-- 데스크탑 검색 영역 -->
                <div class="f-left-area desktop-only">
                    <div class="f-search-area">
                        <select id="f-searchType" class='f-searchType'>
                            <option value="1">정확</option>
                            <option value="2">포함</option>
                            <option value="3">증권번호</option>
                        </select>
                        <input type="text" id="f-searchKeyword" class="f-searchKeyword" placeholder="검색어를 입력하세요" onkeypress="if(event.key === 'Enter') fieldSearchList()">
                        <button class="f-search-button" onclick="fieldSearchList()">검색</button>
                    </div>
                </div>
                
                <!-- 모바일 검색 영역 -->
                <div class="f-mobile-search-area mobile-only">
                    <div class="mobile-filter-row">
                        <select id="f-searchType-mobile" class='f-searchType-mobile'>
                            <option value="1">정확</option>
                            <option value="2">포함</option>
                            <option value="3">증권번호</option>
                        </select>
                    </div>
                    <div class="mobile-filter-row">
                        <input type="text" id="f-searchKeyword-mobile" class="f-searchKeyword-mobile" placeholder="검색어를 입력하세요" onkeypress="if(event.key === 'Enter') fieldSearchList()">
                    </div>
                    <div class="mobile-filter-row">
                        <button class="f-search-button-mobile" onclick="fieldSearchList()">검색</button>
                    </div>
                </div>
                
                <div class="f-right-area">
                    <button class="f-stats-button" onclick="f_showStatsModal()">통계</button>
                </div>
            </div>

            <!-- 리스트 영역 -->
            <div class="f-list-content">
                <!-- 데스크탑 테이블 -->
                <div class="f-data-table-container desktop-only">
                    <table class="f-data-table">
                        <thead>
                            <tr>
                                <th class="col-num">No</th>
                                <th class="col-business-num">사업자번호</th>
                                <th class="col-school">학교명</th>
                                <th class="col-students">학생수</th>
                                <th class="col-phone">연락처</th>
                                <th class="col-date">신청일</th>
                                <th class="col-policy">증권번호</th>
                                <th class="col-premium">보험료</th>
                                <th class="col-insurance">보험사</th>
                                <th class="col-status">상태</th>
                                <th class="col-contact">담당자</th>
                                <th class="col-action">증권</th>
                                <th class="col-action">클레임</th>
                                <th class="col-memo">메모</th>
                                <th class="col-manager">관리자</th>
                            </tr>
                        </thead>
                        <tbody id="f-applicationList">
                            <tr><td colspan="15" class="loading">데이터 로드 중...</td></tr>
                        </tbody>
                    </table>
                </div>
                
                <!-- 모바일 카드 뷰 -->
                <div class="f-mobile-card-view mobile-only">
                    <div id="f-mobile-cards-container">
                        <!-- 모바일 카드가 여기에 동적으로 추가됨 -->
                    </div>
                </div>
            </div>

            <!-- 데스크탑 페이지네이션 -->
            <div class="f-pagination desktop-only"></div>
            
            <!-- 모바일 페이지네이션 -->
            <div id="f-mobile-pagination-container" class="mobile-pagination-container mobile-only">
                <ul id="f-mobile-pagination" class="mobile-pagination"></ul>
            </div>
        </div>`;
                            
    pageContent.innerHTML = fieldContents;
    
    // DOM 요소가 완전히 렌더링된 후에 데이터 로드
    setTimeout(() => {
        // 초기값 설정 후 첫 페이지 로드
        currentFiedPracticePage = 1;
        currentSearchKeyword = '';
        currentSearchMode = 1;
        loadTable(1, '', 1);
        
        // 모바일 검색 동기화 이벤트 리스너 추가
        setupMobileSearchSync();
    }, 0);
}

// 모바일과 데스크탑 검색 동기화
function setupMobileSearchSync() {
    const desktopSearchType = document.getElementById('f-searchType');
    const mobileSearchType = document.getElementById('f-searchType-mobile');
    const desktopSearchKeyword = document.getElementById('f-searchKeyword');
    const mobileSearchKeyword = document.getElementById('f-searchKeyword-mobile');
    
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



function loadTable(page = 1, searchSchool = '', searchMode = 1) {
    // 이미 로딩 중이면 중복 실행 방지
    if (isLoading) {
        console.log('이미 데이터를 로드하고 있습니다.');
        return;
    }
    
    isLoading = true; // 로딩 시작
    
    // 현재 상태 업데이트
    currentFiedPracticePage = page;
    currentSearchKeyword = searchSchool;
    currentSearchMode = searchMode;
    
    const itemsPerPage = 15;
    const tableBody = document.querySelector("#f-applicationList");
    const mobileContainer = document.querySelector("#f-mobile-cards-container");
    const pagination = document.querySelector(".f-pagination");
    const mobilePagination = document.querySelector("#f-mobile-pagination");

    // 로딩 표시
    if (tableBody) {
        tableBody.innerHTML = '<tr><td colspan="15" class="loading">데이터 로드 중...</td></tr>';
    }
    if (mobileContainer) {
        mobileContainer.innerHTML = '<div class="mobile-loading">데이터 로드 중...</div>';
    }
    if (pagination) pagination.innerHTML = "";
    if (mobilePagination) mobilePagination.innerHTML = "";

    fetch(`https://silbo.kr/2025/api/question/fetch_questionnaire.php?page=${page}&limit=${itemsPerPage}&search_school=${searchSchool}&search_mode=${searchMode}`)
        .then(response => {
            // 🔥 1단계: HTTP 상태 확인
            if (!response.ok) {
                throw new Error(`HTTP Error: ${response.status} - ${response.statusText}`);
            }
            
            // 🔥 2단계: 응답 텍스트를 먼저 확인
            return response.text();
        })
        .then(text => {
           // console.log('서버 응답 원본:', text); // 디버깅용
            
            // 🔥 3단계: 빈 응답 체크
            if (!text || text.trim() === '') {
                throw new Error('서버에서 빈 응답을 받았습니다.');
            }
            
            // 🔥 4단계: JSON 파싱 시도
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
                rows = `<tr><td colspan="15" style="text-align: center;">검색 결과가 없습니다.</td></tr>`;
                mobileCards = `<div class="mobile-no-data">검색 결과가 없습니다.</div>`;
            } else {
                response.data.forEach((item, index) => {
                    // 현재 페이지의 실제 순번 계산
                    const actualIndex = (page - 1) * itemsPerPage + index + 1;
                    const formattedPreiminum = item.preiminum ? parseFloat(item.preiminum).toLocaleString("en-US") : "0";

                    const insuranceOptions = `
                        <select class="f-insurance-select" data-id="${item.num}">
                            <option value="-1" ${item.inscompany == -1 ? "selected" : ""}>선택</option>
                            <option value="1" ${item.inscompany == 1 ? "selected" : ""}>한화</option>
                            <option value="2" ${item.inscompany == 2 ? "selected" : ""}>Meritz</option>
                        </select>
                    `;

                    const statusOptions = `
                        <select class="f-status-select" data-id="${item.num}" >
                            <option value="1" ${item.ch == 1 ? "selected" : ""}>접수</option>
                            <option value="2" ${item.ch == 2 ? "selected" : ""}>보험료 안내중</option>
                            <option value="3" ${item.ch == 3 ? "selected" : ""}>청약서</option>
                            <option value="4" ${item.ch == 4 ? "selected" : ""}>입금대기중</option>
                            <option value="5" ${item.ch == 5 ? "selected" : ""}>입금확인</option>
                            <option value="6" ${item.ch == 6 ? "selected" : ""}>증권 발급</option>
						    <option value="7" ${item.ch == 7 ? "selected" : ""}>보류</option>
                            <option value="12" ${item.ch == 12 ? "selected" : ""}>수정요청</option>
						    <option value="38" ${item.ch == 38 ? "selected" : ""}>청약서날인</option>
						    <option value="39" ${item.ch == 39 ? "selected" : ""}>질문서날인</option>
							<option value="40" ${item.ch == 40 ? "selected" : ""}>과별인원</option>
                        </select>
                    `;

                    // 데스크탑 테이블 행
                    rows += `<tr>
                        <td><a href="#" class="f-btn-link_1 open-second-modal" data-num="${item.num}">${actualIndex}</a></td>
                        <td><a href="#" class="f-btn-link_1 open-modal" data-num="${item.num}">${item.school2}</a></td>
                        <td>${item.school1}</td>
                        <td class="f-preiminum">${item.week_total}</td>
                        <td>${item.school4}</td>
                        <td>${item.wdate}</td>
                        <td>${item.certi || item.gabunho || ""}</td>
                        <td class="f-preiminum">${formattedPreiminum}</td>
                        <td class='f-status-cell '>${insuranceOptions}</td>
                        <td class='f-status-cell '>${statusOptions}</td>
                        <td>${item.school5}</td>
                        <td><a href="#" class="f-btn-link_1 upload-modal" data-num="${item.num}">업로드</a></td>
                        <td>${item.certi ? '<a href="#" class="f-btn-link_1 open-claim-modal" data-num="' + item.num + '">클레임</a>' : ''}</td>
                        <td><input class='f-mText' type='text' value='${item.memo}' data-num="${item.num}"></td>
                        <td>${item.manager}</td>
                    </tr>`;

                    // 모바일 카드
                    mobileCards += `
                        <div class="f-mobile-card" data-num="${item.num}">
                            <div class="f-card-header">
                                <div class="f-card-number">${actualIndex}</div>
                                <div class="f-card-school-name">${item.school1}</div>
                            </div>
                            <div class="f-card-body">
                                <div class="f-card-row">
                                    <span class="f-card-label">사업자번호:</span>
                                    <span class="f-card-value">
                                        <a href="#" class="f-btn-link_1 open-modal" data-num="${item.num}">${item.school2}</a>
                                    </span>
                                </div>
                                <div class="f-card-row">
                                    <span class="f-card-label">학생수:</span>
                                    <span class="f-card-value">${item.week_total}</span>
                                </div>
                                <div class="f-card-row">
                                    <span class="f-card-label">연락처:</span>
                                    <span class="f-card-value">${item.school4}</span>
                                </div>
                                <div class="f-card-row">
                                    <span class="f-card-label">신청일:</span>
                                    <span class="f-card-value">${item.wdate}</span>
                                </div>
                                <div class="f-card-row">
                                    <span class="f-card-label">증권번호:</span>
                                    <span class="f-card-value">${item.certi || item.gabunho || ""}</span>
                                </div>
                                <div class="f-card-row">
                                    <span class="f-card-label">보험료:</span>
                                    <span class="f-card-value">${formattedPreiminum}</span>
                                </div>
                                <div class="f-card-row">
                                    <span class="f-card-label">보험사:</span>
                                    <span class="f-card-value">${insuranceOptions}</span>
                                </div>
                                <div class="f-card-row">
                                    <span class="f-card-label">상태:</span>
                                    <span class="f-card-value">${statusOptions}</span>
                                </div>
                                <div class="f-card-row">
                                    <span class="f-card-label">담당자:</span>
                                    <span class="f-card-value">${item.school5}</span>
                                </div>
                                <div class="f-card-row">
                                    <span class="f-card-label">관리자:</span>
                                    <span class="f-card-value">${item.manager}</span>
                                </div>
                                <div class="f-card-row">
                                    <span class="f-card-label">메모:</span>
                                    <span class="f-card-value">
                                        <input class='f-mText-mobile' type='text' value='${item.memo}' data-num="${item.num}">
                                    </span>
                                </div>
                            </div>
                            <div class="f-card-actions">
                                <button class="f-card-action-btn upload-modal" data-num="${item.num}">업로드</button>
                                ${item.certi ? '<button class="f-card-action-btn open-claim-modal" data-num="' + item.num + '">클레임</button>' : ''}
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
            const totalPages = Math.ceil(response.pagination.total / itemsPerPage);
			console.log('총 데이터 수:', response.pagination.total, '총 페이지 수:', totalPages, '현재 페이지:', page);
			renderPagination(page, totalPages);
			renderMobilePagination(page, totalPages);
						

        })
        .catch((error) => {
            console.error('데이터 로드 오류:', error);
            
            // 🔥 사용자에게 구체적인 오류 메시지 표시
            let errorMessage = "데이터를 불러오는 중 오류가 발생했습니다.";
            
            if (error.message.includes('JSON 파싱 오류')) {
                errorMessage = "서버 응답 형식에 문제가 있습니다. 관리자에게 문의하세요.";
            } else if (error.message.includes('HTTP Error')) {
                errorMessage = `서버 연결 오류: ${error.message}`;
            } else if (error.message.includes('빈 응답')) {
                errorMessage = "서버에서 응답을 받지 못했습니다.";
            }
            
            if (tableBody) {
                tableBody.innerHTML = `<tr><td colspan="15" style="text-align: center; color: red;">${errorMessage}</td></tr>`;
            }
            if (mobileContainer) {
                mobileContainer.innerHTML = `<div class="mobile-error">${errorMessage}</div>`;
            }
        })
        .finally(() => {
            isLoading = false; // 로딩 완료
        });
}

// 데스크탑 페이지네이션 (완전히 새로 구현)
function renderPagination(currentPage, totalPages) {
    const pagination = document.querySelector(".f-pagination");
    if (!pagination) return;
    
    // 전역 변수 업데이트
    currentFiedPracticePage = currentPage;
    
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
        paginationHTML += `<a href="#" class="f-page-link" data-page="1">처음</a>`;
    }

    // 이전 버튼
    if (currentPage > 1) {
        paginationHTML += `<a href="#" class="f-page-link" data-page="${currentPage - 1}">이전</a>`;
    } else {
        paginationHTML += `<span class="f-disabled">이전</span>`;
    }

    // 숫자 페이지 버튼들
    for (let i = startPage; i <= endPage; i++) {
        if (i === currentPage) {
            paginationHTML += `<a href="#" class="f-page-link active" data-page="${i}">${i}</a>`;
        } else {
            paginationHTML += `<a href="#" class="f-page-link" data-page="${i}">${i}</a>`;
        }
    }

    // 다음 버튼
    if (currentPage < totalPages) {
        paginationHTML += `<a href="#" class="f-page-link" data-page="${currentPage + 1}">다음</a>`;
    } else {
        paginationHTML += `<span class="f-disabled">다음</span>`;
    }

    // 마지막 페이지로 이동 버튼 (현재 페이지가 마지막 페이지보다 작을 때만)
    if (currentPage < totalPages) {
        paginationHTML += `<a href="#" class="f-page-link" data-page="${totalPages}">마지막</a>`;
    }

    pagination.innerHTML = paginationHTML;

    // 이벤트 리스너 추가 (기존 이벤트 리스너 제거 후 새로 추가)
    pagination.querySelectorAll(".f-page-link").forEach(link => {
        link.addEventListener("click", function (e) {
            e.preventDefault();
            const targetPage = parseInt(this.dataset.page);
            if (targetPage && !isNaN(targetPage) && targetPage !== currentPage) {
                console.log(`페이지 이동: ${currentPage} → ${targetPage}`);
                loadTable(targetPage, currentSearchKeyword, currentSearchMode);
            }
        });
    });
}

// 모바일 페이지네이션 (개선된 버전)
function renderMobilePagination(currentPage, totalPages) {
    const mobilePagination = document.querySelector("#f-mobile-pagination");
    if (!mobilePagination) return;
    
    mobilePagination.innerHTML = ""; // 기존 버튼 삭제

    let mobileHTML = "";

    // 처음 페이지 버튼 (현재 페이지가 1보다 클 때만)
    if (currentPage > 1) {
        mobileHTML += `<li><a href="#" class="f-mobile-page-link" data-page="1">≪</a></li>`;
    }

    // 이전 버튼
    if (currentPage > 1) {
        mobileHTML += `<li><a href="#" class="f-mobile-page-link" data-page="${currentPage - 1}">‹</a></li>`;
    }

    // 현재 페이지 정보 표시
    mobileHTML += `<li class="f-mobile-page-info">${currentPage} / ${totalPages}</li>`;

    // 다음 버튼
    if (currentPage < totalPages) {
        mobileHTML += `<li><a href="#" class="f-mobile-page-link" data-page="${currentPage + 1}">›</a></li>`;
    }

    // 마지막 페이지 버튼 (현재 페이지가 마지막 페이지보다 작을 때만)
    if (currentPage < totalPages) {
        mobileHTML += `<li><a href="#" class="f-mobile-page-link" data-page="${totalPages}">≫</a></li>`;
    }

    mobilePagination.innerHTML = mobileHTML;

    // 모바일 페이지 이동 이벤트 추가
    mobilePagination.querySelectorAll(".f-mobile-page-link").forEach(link => {
        link.addEventListener("click", function (e) {
            e.preventDefault();
            const targetPage = parseInt(this.dataset.page);
            if (targetPage && !isNaN(targetPage) && targetPage !== currentPage) {
                console.log(`모바일 페이지 이동: ${currentPage} → ${targetPage}`);
                loadTable(targetPage, currentSearchKeyword, currentSearchMode);
            }
        });
    });
}


// 검색 함수 수정 (모바일/데스크탑 동기화 고려)
function fieldSearchList() {
    const desktopSearchType = document.getElementById('f-searchType');
    const mobileSearchType = document.getElementById('f-searchType-mobile');
    const desktopSearchKeyword = document.getElementById('f-searchKeyword');
    const mobileSearchKeyword = document.getElementById('f-searchKeyword-mobile');
    
    // 현재 활성화된 검색 옵션 가져오기
    const searchType = desktopSearchType ? desktopSearchType.value : (mobileSearchType ? mobileSearchType.value : '1');
    const searchKeyword = desktopSearchKeyword ? desktopSearchKeyword.value : (mobileSearchKeyword ? mobileSearchKeyword.value : '');
    
    currentFiedPracticePage = 1; // 검색 시 첫 페이지로 이동
    loadTable(1, searchKeyword, parseInt(searchType));
}




function f_showStatsModal(){
    document.getElementById("changeP").innerHTML = "";
    perFormance(); // 실적 조회 함수 실행
    document.getElementById("perModal").style.display = "block";
}
function perFormance() {
    console.log("📌 모달 오픈 & 데이터 요청");

    const modal = document.getElementById("perModal");
   

    createYearMonthSelectors();
    fetchPerformanceData(); // 현재일 기준 한 달간 실적 조회
	insertFooterButtons(); // ✅ 모달 푸터 버튼 삽입

    // 이벤트 리스너 추가 (연도 & 월 변경 시 데이터 재조회)
    document.getElementById("yearSelect").addEventListener("change", fetchSelectedPerformanceData);
    document.getElementById("monthSelect").addEventListener("change", fetchSelectedPerformanceData);
}

function insertFooterButtons() {
    const footerContainer = document.getElementById("changeP");

    // 기존 내용 초기화
    footerContainer.innerHTML = ""; 

    let ptr = "";
    ptr += `<button id="downloadExcel" class="p-btn">최근 1년 실적 다운로드</button>`;
    ptr += `<button id="yearPerformanceBtn" class="p-btn">년별 실적</button>`;

    footerContainer.innerHTML = ptr; // HTML 동적 삽입

    // ✅ DOM이 업데이트될 시간을 주기 위해 setTimeout 사용
    setTimeout(() => {
        const yearPerformanceBtn = document.getElementById("yearPerformanceBtn");
        if (yearPerformanceBtn) {
            yearPerformanceBtn.addEventListener("click", yearPerFormance_);
            console.log("📌 '년별 실적' 버튼 이벤트 바인딩 완료!");
        } else {
            console.error("🚨 '년별 실적' 버튼을 찾을 수 없습니다!");
        }
    }, 50); // 50ms 딜레이 후 실행
}




// ✅ 연도 및 월 선택 박스 생성
function createYearMonthSelectors() {

    const yearContainer = document.getElementById("yearSelect_");
    const monthContainer = document.getElementById("monthSelect_");

    // 현재 날짜 가져오기
    const today = new Date();
    const currentYear = today.getFullYear();
    const currentMonth = today.getMonth() + 1; // JavaScript에서 월은 0부터 시작하므로 +1 필요

    // 연도 선택 동적 생성
    let yearDropdown = document.createElement("select");
    yearDropdown.id = "yearSelect";
    yearDropdown.className = "form-control";
    yearDropdown.innerHTML = `<option value="-1">년도 선택</option>`;

    for (let i = 0; i < 5; i++) { // 현재 연도부터 5년 전까지
        let year = currentYear - i;
        let option = document.createElement("option");
        option.value = year;
        option.textContent = year;
        if (year === currentYear) {
            option.selected = true; // 현재 연도 기본 선택
        }
        yearDropdown.appendChild(option);
    }

    yearContainer.innerHTML = "";
    yearContainer.appendChild(yearDropdown);

    // 월 선택 동적 생성
    let monthDropdown = document.createElement("select");
    monthDropdown.id = "monthSelect";
    monthDropdown.className = "form-control";
    monthDropdown.innerHTML = `<option value="-1">월 선택</option>`;

    for (let i = 1; i <= 12; i++) {
        let option = document.createElement("option");
        let monthValue = i < 10 ? `0${i}` : i; // 01, 02 ... 형식 유지
        option.value = monthValue;
        option.textContent = `${i}월`;
        if (i === currentMonth) {
            option.selected = true; // 현재 월 기본 선택
        }
        monthDropdown.appendChild(option);
    }

    monthContainer.innerHTML = "";
    monthContainer.appendChild(monthDropdown);
}

// ✅ 현재일 기준 한 달간 실적 조회
function fetchPerformanceData() {
    const today = new Date();
    const endDate = today.toISOString().split("T")[0]; // 오늘 날짜
    const startDate = new Date(today.setMonth(today.getMonth() - 1)).toISOString().split("T")[0]; // 1개월 전 날짜

    fetch(`https://silbo.kr/2025/api/question/performance_1.php?start=${startDate}&end=${endDate}`)
        .then(response => response.json())
        .then(data => {
            renderTable(data, startDate, endDate); // 데이터와 기간 전달
        })
        .catch(error => {
            console.error("🚨 데이터 로드 오류:", error);
        });
}

// ✅ 선택한 연도 및 월 기준 실적 조회
function fetchSelectedPerformanceData() {
    const selectedYear = document.getElementById("yearSelect").value;
    const selectedMonth = document.getElementById("monthSelect").value;

    if (selectedYear === "-1" || selectedMonth === "-1") {
        return; // 연도 또는 월이 선택되지 않으면 실행 X
    }

    // 선택한 연도 및 월의 시작일과 종료일 계산
    const startDate = `${selectedYear}-${selectedMonth}-01`;
    const endDate = new Date(selectedYear, selectedMonth, 0).toISOString().split("T")[0]; // 선택한 월의 마지막 날짜

    fetch(`https://silbo.kr/2025/api/question/performance_1.php?start=${startDate}&end=${endDate}`)
        .then(response => response.json())
        .then(data => {
            renderTable(data, startDate, endDate); // 데이터와 기간 전달
        })
        .catch(error => {
            console.error("🚨 데이터 로드 오류:", error);
        });
}

// ✅ 받은 데이터를 테이블에 표시하는 함수
function renderTable(data, startDate, endDate) {
    const tableBody = document.querySelector("#performanceTable tbody");
    const summaryContainer = document.querySelector("#performanceSummary"); // 상단 요약 정보를 표시할 컨테이너
    tableBody.innerHTML = ""; // 기존 데이터 초기화

    let html = "<tr>"; // 시작 행
    let totalSum = 0; // 보험료 합계 변수 초기화

    data.forEach((item, index) => {
        // 날짜와 요일 계산
        const dayOfWeek = new Date(item.day_).getDay();
        const weekDays = ["일", "월", "화", "수", "목", "금", "토"];

        let color = "";
        if (dayOfWeek === 0) color = "style='color:red;'"; // 일요일 빨간색
        if (dayOfWeek === 6) color = "style='color:blue;'"; // 토요일 파란색

       
       
		// 보험료와 건수 표시
		const daySum = Number(item.day_sum) === 0 ? "" : item.day_sum; // 숫자로 변환 후 비교
		const gunSu = Number(item.gunsu) === 0 ? "()" : `(${item.gunsu})`; // 숫자로 변환 후 비교

		// 합계 계산
		if (Number(item.day_sum) !== 0) {
			totalSum += parseInt(String(item.day_sum).replace(/,/g, ""), 10); // 숫자로 변환 후 합계
		}

		// 셀 추가
		html += `
			<td ${color}>
				<div>${item.day_} (${weekDays[dayOfWeek]})</div>
				<div style="text-align: right;">${daySum} ${gunSu}</div>
			</td>
		`;

        // 7개 셀이 채워지면 줄 바꿈
        if ((index + 1) % 7 === 0) {
            html += "</tr><tr>";
        }
    });

    // 마지막 줄의 빈 셀 채우기
    const remainingCells = data.length % 7;
    if (remainingCells > 0) {
        for (let i = 0; i < 7 - remainingCells; i++) {
            html += "<td></td>";
        }
    }

    html += "</tr>"; // 끝 행
    tableBody.innerHTML = html;

    // 상단 요약 정보 표시
    summaryContainer.innerHTML = `
        <div style="text-align: left; font-weight: bold; margin-bottom: 10px;">
            기간: ${startDate} ~ ${endDate}<br>
            총 보험료 합계: ${totalSum.toLocaleString()} 원
        </div>
    `;


}

// ✅ 페이지 로드 시 실행
/*window.onload = function() {
    createYearMonthSelectors();
};*/


function yearPerFormance_() {
    console.log("📌 연간 실적 모드 실행");
    
    //const modal = document.getElementById("perModal");
    //modal.style.display = "flex"; // 모달 표시

    // 기존 모달 내용 초기화
	document.getElementById("changeP").innerHTML = "";
    document.querySelector("#performanceTable tbody").innerHTML = "";
    document.querySelector("#performanceSummary").innerHTML = "";

    // ✅ 연도 및 월 선택 초기화
    const yearContainer = document.getElementById("yearSelect_");
    const monthContainer = document.getElementById("monthSelect_");
    if (yearContainer) yearContainer.innerHTML = "";
    if (monthContainer) monthContainer.innerHTML = "";

    // ✅ 현재 날짜 가져오기
    const today = new Date();
    const currentYear = today.getFullYear();

    // ✅ 연도 선택 드롭다운 생성
    let yearDropdown = document.createElement("select");
    yearDropdown.id = "yearSelect";
    yearDropdown.className = "form-control";

    // 기본 옵션 추가
    let defaultOption = document.createElement("option");
    defaultOption.value = "-1";
    defaultOption.textContent = "년도 선택";
    yearDropdown.appendChild(defaultOption);

    // 최근 5년 데이터 생성
    for (let i = 0; i < 5; i++) {
        let year = currentYear - i;
        let option = document.createElement("option");
        option.value = year;
        option.textContent = `${year}년`;
        if (year === currentYear) {
            option.selected = true; // 현재 연도 기본 선택
        }
        yearDropdown.appendChild(option);
    }

    // ✅ 생성된 드롭다운을 연도 컨테이너에 추가
    if (yearContainer) yearContainer.appendChild(yearDropdown);

    // ✅ 연도 선택 변경 시 데이터 갱신
    yearDropdown.addEventListener("change", fetchYearlyPerformance);

    // ✅ 기본적으로 현재 연도 데이터 조회
    fetchYearlyPerformance();
}
function fetchYearlyPerformance() {
    const selectedYear = document.getElementById("yearSelect").value;
    console.log(`📌 ${selectedYear}년 & ${selectedYear - 1}년 데이터 조회`);

    fetch(`https://silbo.kr/2025/api/question/performance_yearly.php?year=${selectedYear}`)
        .then(response => response.json())
        .then(data => {
         //   console.log("📊 조회된 월별 보험료 데이터:", data); // ✅ 데이터 출력
            renderYearlyTable(data, selectedYear);
        })
        .catch(error => {
            console.error("🚨 연간 데이터 로드 오류:", error);
        });
}


function renderYearlyTable(data, year) {
    const tableBody = document.querySelector("#performanceTable tbody");
	const summaryContainer = document.querySelector("#performanceSummary"); // 상단 요약 정보를 표시할 컨테이너
    tableBody.innerHTML = ""; // 기존 데이터 초기화

    console.log("📊 조회된 월별 보험료 데이터:", data); // ✅ 원본 데이터 출력

    let totalGunsuYear = 0; // 기준년도 총 건수
    let totalSumYear = 0; // 기준년도 총 보험료 합계
    let totalGunsuPrevYear = 0; // 이전년도 총 건수
    let totalSumPrevYear = 0; // 이전년도 총 보험료 합계

    // ✅ 데이터 필터링 (year 및 month 값이 존재하는 데이터만 사용)
    let yearData = data.filter(item => item.year && item.month && parseInt(item.year) === parseInt(year));
    let prevYearData = data.filter(item => item.year && item.month && parseInt(item.year) === parseInt(year) - 1);

    console.log(`📌 ${year}년 필터링된 데이터:`, yearData);
    console.log(`📌 ${year - 1}년 필터링된 데이터:`, prevYearData);

    let mergedData = [];

    for (let month = 1; month <= 12; month++) {
        let monthFormatted = month < 10 ? `0${month}` : `${month}`; // "01" ~ "12" 변환 (문자열)

       let yearItem = yearData.find(item => parseInt(item.month) === parseInt(monthFormatted)) || { gunsu: 0, total_sum: 0 };
			let prevYearItem = prevYearData.find(item => parseInt(item.month) === parseInt(monthFormatted)) || { gunsu: 0, total_sum: 0 };

			console.log(`📌 ${year}-${monthFormatted} 데이터:`, yearItem);
			console.log(`📌 ${year - 1}-${monthFormatted} 데이터:`, prevYearItem);

			mergedData.push({
				month: monthFormatted,
				yearMonth: `${year}-${monthFormatted}`,
				prevYearMonth: `${year - 1}-${monthFormatted}`,
				
				// 건수가 0이면 공백, 그렇지 않으면 숫자로 변환
				yearGunsu: Number(yearItem.gunsu) === 0 ? "" : yearItem.gunsu, 
				prevYearGunsu: Number(prevYearItem.gunsu) === 0 ? "" : prevYearItem.gunsu,

				// 보험료가 0이면 공백, 그렇지 않으면 원화 표시
				yearTotal: Number(yearItem.total_sum) > 0 ? Number(yearItem.total_sum).toLocaleString() + " 원" : "", 
				prevYearTotal: Number(prevYearItem.total_sum) > 0 ? Number(prevYearItem.total_sum).toLocaleString() + " 원" : ""
			});

        // ✅ 총합 계산
        totalGunsuYear += parseInt(yearItem.gunsu) || 0;
        totalSumYear += parseInt(yearItem.total_sum) || 0;
        totalGunsuPrevYear += parseInt(prevYearItem.gunsu) || 0;
        totalSumPrevYear += parseInt(prevYearItem.total_sum) || 0;
    }

    console.log("📊 최종 mergedData:", mergedData);
    console.log(`📊 ${year}년 총 건수: ${totalGunsuYear}, 총 보험료: ${totalSumYear.toLocaleString()} 원`);
    console.log(`📊 ${year - 1}년 총 건수: ${totalGunsuPrevYear}, 총 보험료: ${totalSumPrevYear.toLocaleString()} 원`);

    // ✅ 데이터 테이블에 추가
	const row = document.createElement("tr");
        row.innerHTML = `
            <th >년월</th>
            <th >보험료(건수)</th>
            <th >년월</th>
            <th >보험료(건수)</th>
        `;
        tableBody.appendChild(row);
    mergedData.forEach(item => {
        const row = document.createElement("tr");
        row.innerHTML = `
            <th >${item.yearMonth}</th>
            <td >${item.yearTotal} (${item.yearGunsu}건)</td>
            <th >${item.prevYearMonth}</th>
            <td >${item.prevYearTotal} (${item.prevYearGunsu}건)</td>
        `;
        tableBody.appendChild(row);
    });

    // ✅ 최종 합계 행 추가
    const totalRow = document.createElement("tr");
    totalRow.innerHTML = `
        <th><strong>📊 ${year}년 총합계</strong></div></th>
        <td ><strong>${totalSumYear ? totalSumYear.toLocaleString() + " 원" : ""} (${totalGunsuYear}건)</strong></td>
        <th><strong>📊 ${year - 1}년 총합계</strong></div></th>
        <td><strong>${totalSumPrevYear ? totalSumPrevYear.toLocaleString() + " 원" : ""} (${totalGunsuPrevYear}건)</strong></td>
    `;
    tableBody.appendChild(totalRow);
	insertFooterButtons2();
	const selectedYear = document.getElementById("yearSelect").value;
	// 상단 요약 정보 표시
    summaryContainer.innerHTML = `
        <div style="text-align: left; font-weight: bold; margin-bottom: 10px;">
            ${year}년 총합계 : ${totalSumYear ? totalSumYear.toLocaleString() + " 원" : ""} (${totalGunsuYear}건) <br>
			${year - 1}년 총합계 :${totalSumPrevYear ? totalSumPrevYear.toLocaleString() + " 원" : ""} (${totalGunsuPrevYear}건)
        </div>
    `;
}




function insertFooterButtons2() {
    const footerContainer = document.getElementById("changeP");

    // 기존 내용 초기화
    footerContainer.innerHTML = ""; 

    let ptr = "";
    ptr += `<button id="downloadExcel" class="p-btn">최근 1년 실적 다운로드</button>`;
    ptr += `<button id="monthsBtn" class="p-btn">월별 실적</button>`;

    footerContainer.innerHTML = ptr; // HTML 동적 삽입

    // ✅ DOM이 업데이트될 시간을 주기 위해 setTimeout 사용
    setTimeout(() => {
        const monthsBtn = document.getElementById("monthsBtn");  // ✅ 올바른 변수명
        if (monthsBtn) {  // ✅ 변수명 수정
            monthsBtn.addEventListener("click", perFormance);
            console.log("📌 '월별 실적' 버튼 이벤트 바인딩 완료!");
        } else {
            console.error("🚨 '월별 실적' 버튼을 찾을 수 없습니다!");
        }
    }, 50); // 50ms 딜레이 후 실행
}

//보험사 상태 변경 

document.addEventListener("change", function (e) {
    // 변경된 요소가insurance-select 클래스인지 확인
    if (e.target.classList.contains("f-insurance-select")) {
        const num = e.target.dataset.id;  // data-id 속성에서 num 값 가져오기
        const selectedValue = e.target.value;  // 선택된 옵션 값 가져오기
        
        // 상태 변경 함수 호출
        handleInsuranceChange(num, selectedValue);
    }
	// 변경된 요소가 status-select 클래스인지 확인
    if (e.target.classList.contains("f-status-select")) {
        const num = e.target.dataset.id;  // data-id 속성에서 num 값 가져오기
        const selectedValue = e.target.value;  // 선택된 옵션 값 가져오기
        
        // 상태 변경 함수 호출
        handleStatusChange(num, selectedValue);
    }
});

// 상태 변경 함수 (num, 선택값 받아서 처리)
function handleInsuranceChange(num, selectedValue) {
    fetch(`https://silbo.kr/2025/api/question/update_insurance.php`, {
        method: "POST",
        headers: {
            "Content-Type": "application/x-www-form-urlencoded",
        },
        body: `id=${num}&inscompany=${selectedValue}`,
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

// 상태 변경 함수 (num, 선택값 받아서 처리)
function handleStatusChange(num, selectedValue) {
    fetch(`https://silbo.kr/2025/api/question/update_status.php`, {
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
    if (e.target.classList.contains("f-mText") && e.key === "Enter") {
        e.preventDefault(); // 기본 엔터 동작 방지 (폼 제출 방지)

        const memo = e.target.value.trim();
        const num = e.target.dataset.num;

        if (!memo) {
            alert("메모를 입력해주세요.");
            return;
        }

        fetch(`https://silbo.kr/2025/api/question/update_memo.php`, {
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


//첫번째 모달  번호를 클릭할 경우 시작//


document.addEventListener("click", function (event) {
if (event.target.classList.contains("open-second-modal")) {
	event.preventDefault();
	const num = event.target.dataset.num;

	fetch(`https://silbo.kr/2025/api/question/get_questionnaire_details.php?id=${num}`)
		.then(response => response.json())
		.then(response => {
			if (response.success) {
				//const modal = document.getElementById("second-modal");
				document.getElementById("questionwareNum_").value = response.data.num;
				document.getElementById("school9_").value = response.data.school9;
				document.getElementById("cNum_").value = response.data.cNum;

				// 전 설계번호 설정
				document.getElementById("beforegabunho").textContent = response.beforeGabunho ? `전 설계번호: ${response.beforeGabunho}` : "신규";

				// 계약자 정보 설정
				const fields = ["school1", "school2", "school3", "school4", "school5", "school7", "school8"];
				fields.forEach(field => {
					document.getElementById(`school_${field.slice(-1)}`).textContent = response.data[field];
				});

				// 현장실습 시기
				const periods = { "1": "1학기", "2": "하계", "3": "2학기", "4": "동계" };
				document.getElementById("school_6").textContent = periods[response.data.school6] || "알 수 없음";

				// 가입유형
				document.getElementById("school_9").textContent = response.data.school9 == 1 ? "가입유형 A" : "가입유형 B";

				// 대인/대물 설정
				const limits = response.data.directory == 2 ? { A: "2 억", B: "3 억" } : { A: "2 억", B: "3 억" };
				document.getElementById("daein1_").textContent = limits[response.data.school9 == 1 ? "A" : "B"];
				document.getElementById("daein2_").textContent = limits[response.data.school9 == 1 ? "A" : "B"];

				// 보험료 정보 설정
				document.getElementById("daein_").textContent = response.daeinP;
				document.getElementById("daemool_").textContent = response.daemoolP;
				document.getElementById("totalP_").textContent = response.preiminum;

				// 참여인원 정보 설정
				let inwons = "";
				for (let i = 4; i <= 26; i++) {
					if (response.data[`week${i}`] != 0) {
						inwons += `<span id="week_${i}">${i} 주</span> <span id="week_inwon${i}">${response.data[`week${i}`]} </span> 명, `;
					}
				}
				inwons += `총인원 : <span id="week_total_"></span>`;
				document.getElementById("inwon").innerHTML = inwons;
				document.getElementById("week_total_").textContent = response.data.week_total;

				// 기타 정보 입력
				document.getElementById("gabunho-input").value = response.data.gabunho;
				document.getElementById("certi_").value = response.data.certi;
				document.getElementById("card-number").value = response.cardnum;
				document.getElementById("card-expiry").value = response.yymm;
				document.getElementById("bank-name").value = response.bankname;
				document.getElementById("bank-account").value = response.bank;
				document.getElementById("damdanga").value = response.damdanga;
				document.getElementById("damdangat").value = response.damdangat;

				// mem_id 동적 로드
				fetch(`https://silbo.kr/2025/api/question/get_idList.php`)
					.then(response => response.json())
					.then(memData => {
						const select = document.getElementById("mem-id-select");
						select.innerHTML = "";
						memData.forEach(item => {
							const option = document.createElement("option");
							option.value = item.num;
							option.textContent = item.mem_id;
							select.appendChild(option);
						});
						const newOption = document.createElement("option");
						newOption.value = "신규 id";
						newOption.textContent = "신규ID";
						select.appendChild(newOption);
						select.value = response.data.cNum;
					})
					.catch(() => alert("mem_id 데이터를 가져오는 데 실패했습니다."));

				// 모달 열기 (fadeIn 효과 적용)
				document.getElementById("second-modal").style.display = "block";//fadeIn(modal);
			} else {
				alert(response.error);
			}
		})
		.catch(() => alert("두 번째 데이터 로드 실패."));
  }

    // 모달 닫기
    if (event.target.classList.contains("close-modal")) {
        fadeOut(event.target.closest(".modal"));
    }

    // 모달 외부 클릭 시 닫기
    if (event.target.classList.contains("modal")) {
        fadeOut(event.target);
    }
});

// ✅ `fadeIn` 함수 (부드럽게 모달 표시)
function fadeIn(element) {
    element.style.opacity = 0;
    element.style.display = "block";
    let opacity = 0;

    const fadeInterval = setInterval(function () {
        if (opacity < 1) {
            opacity += 0.05;
            element.style.opacity = opacity;
        } else {
            clearInterval(fadeInterval);
        }
    }, 20);
}

// ✅ `fadeOut` 함수 (부드럽게 모달 닫기)
function fadeOut(element) {
    let opacity = 1;

    const fadeInterval = setInterval(function () {
        if (opacity > 0) {
            opacity -= 0.05;
            element.style.opacity = opacity;
        } else {
            clearInterval(fadeInterval);
            element.style.display = "none";
        }
    }, 20);
}


//첫번째 모달  번호를 클릭할 경우 끝//


function gabunhoInput() {
    const gabunho = document.getElementById("gabunho-input").value.trim();
    const num = document.getElementById("questionwareNum_").value;
    const userName = document.getElementById("userName").value;
    
    if (!gabunho) {
        alert("가입 설계번호를 입력하세요.");
        return;
    }
    
    fetch(`https://silbo.kr/2025/api/question/update_gabunho.php`, {
        method: "POST",
        headers: {
            "Content-Type": "application/x-www-form-urlencoded",
        },
        body: `gabunho=${encodeURIComponent(gabunho)}&num=${encodeURIComponent(num)}&userName=${encodeURIComponent(userName)}`,
    })
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                alert("가입 설계번호가 성공적으로 저장되었습니다.");
            } else {
                alert("저장 실패: " + (data.error || "알 수 없는 오류"));
            }
        })
        .catch(() => {
            alert("가입 설계번호 저장 중 오류가 발생했습니다.");
        });
}


function saveCerti() {
    const certi_ = document.getElementById("certi_").value.trim(); // 입력 값
    const num = document.getElementById("questionwareNum_").value; // questionware num 값
    const userName = document.getElementById("userName").value;

    if (!certi_) {
        alert("증권번호를 입력하세요.");
        return;
    }

    fetch(`https://silbo.kr/2025/api/question/update_certi_.php`, {
        method: "POST",
        headers: {
            "Content-Type": "application/x-www-form-urlencoded",
        },
        body: `certi_=${encodeURIComponent(certi_)}&num=${encodeURIComponent(num)}&userName=${encodeURIComponent(userName)}`
    })
    .then(response => response.json())
    .then(data => {
        if (data.success) {
            alert("증권번호가 성공적으로 저장되었습니다.");
        } else {
            alert("저장 실패: " + (data.error || "알 수 없는 오류"));
        }
    })
    .catch(() => {
        alert("증권번호 저장 중 오류가 발생했습니다.");
    });
}

// 카드번호 저장


function saveCardNumber() {
    const cardNumberInput = document.getElementById("card-number");
    if (!cardNumberInput) return; // 요소가 존재하지 않으면 중단

    const cardNumber = cardNumberInput.value.trim();
    const cNum_ = document.getElementById("cNum_").value;

    if (!cardNumber || !cNum_) {
        alert("카드 번호와 Num 값을 입력하세요.");
        return;
    }

    fetch(`https://silbo.kr/2025/api/question/update_cardnum.php`, {
        method: "POST",
        headers: {
            "Content-Type": "application/x-www-form-urlencoded",
        },
        body: `num=${encodeURIComponent(cNum_)}&cardnum=${encodeURIComponent(cardNumber)}`,
    })
    .then(response => response.text())
    .then(data => {
        alert(data); // 서버 응답 메시지 출력
    })
    .catch(() => {
        alert("카드 번호 저장 중 오류가 발생했습니다.");
    });
}
//유효기간 


function saveCardExpiry() {
    const cardExpiryInput = document.getElementById("card-expiry");
    if (!cardExpiryInput) return; // 요소가 존재하지 않으면 중단

    const card_expiry = cardExpiryInput.value.trim();
    const cNum_ = document.getElementById("cNum_").value;

    if (!card_expiry || !cNum_) {
        alert("카드 유효기간과 Num 값을 입력하세요.");
        return;
    }

    fetch(`https://silbo.kr/2025/api/question/update_yymm.php`, {
        method: "POST",
        headers: {
            "Content-Type": "application/x-www-form-urlencoded",
        },
        body: `num=${encodeURIComponent(cNum_)}&yymm=${encodeURIComponent(card_expiry)}`,
    })
    .then(response => response.text())
    .then(data => {
        alert(data); // 서버 응답 메시지 출력
    })
    .catch(() => {
        alert("카드 유효기간 저장 중 오류가 발생했습니다.");
    });
}
//은행


function saveBankName() {
    const bankNameInput = document.getElementById("bank-name");
    if (!bankNameInput) return; // 요소가 존재하지 않으면 중단

    const bank_name = bankNameInput.value.trim();
    const cNum_ = document.getElementById("cNum_").value;

    if (!bank_name || !cNum_) {
        alert("은행명과 Num 값을 입력하세요.");
        return;
    }

    fetch(`https://silbo.kr/2025/api/question/update_bank_name.php`, {
        method: "POST",
        headers: {
            "Content-Type": "application/x-www-form-urlencoded",
        },
        body: `num=${encodeURIComponent(cNum_)}&bankName=${encodeURIComponent(bank_name)}`,
    })
    .then(response => response.text())
    .then(data => {
        alert(data); // 서버 응답 메시지 출력
    })
    .catch(() => {
        alert("은행명 저장 중 오류가 발생했습니다.");
    });
}




// 🏦 은행계좌 저장
function saveBankAccount() {
    const bankAccount = document.getElementById("bank-account").value.trim();
    const cNum_ = document.getElementById("cNum_").value;

    if (!bankAccount || !cNum_) {
        alert("은행계좌와 Num 값을 입력하세요.");
        return;
    }

    fetch(`https://silbo.kr/2025/api/question/update_bank_account.php`, {
        method: "POST",
        headers: { "Content-Type": "application/x-www-form-urlencoded" },
        body: `num=${encodeURIComponent(cNum_)}&bank=${encodeURIComponent(bankAccount)}`,
    })
    .then(response => response.text())
    .then(data => alert(data))
    .catch(() => alert("은행계좌 저장 중 오류가 발생했습니다."));
}

// 👤 담당자 저장
function saveDamdanga() {
    const damdanga = document.getElementById("damdanga").value.trim();
    const cNum_ = document.getElementById("cNum_").value;

    if (!damdanga || !cNum_) {
        alert("담당자 정보와 Num 값을 입력하세요.");
        return;
    }

    fetch(`https://silbo.kr/2025/api/question/update_damdanga.php`, {
        method: "POST",
        headers: { "Content-Type": "application/x-www-form-urlencoded" },
        body: `num=${encodeURIComponent(cNum_)}&damdanga=${encodeURIComponent(damdanga)}`,
    })
    .then(response => response.text())
    .then(data => alert(data))
    .catch(() => alert("담당자 정보 저장 중 오류가 발생했습니다."));
}

// 📞 담당자 연락처 저장


// 📞 담당자 연락처 저장 (alert 중복 해결)

// 전화번호 입력 시 자동 하이픈(-) 추가
document.addEventListener("input", function (event) {
    if (event.target.id === "damdangat") {
        let input = event.target.value.replace(/\D/g, ""); // 숫자만 남기기

        if (input.length === 11) {
            input = input.replace(/(\d{3})(\d{4})(\d{4})/, "$1-$2-$3");
        } else if (input.length === 10) {
            input = input.replace(/(\d{3})(\d{3})(\d{4})/, "$1-$2-$3");
        } else if (input.length === 9) {
            input = input.replace(/(\d{2})(\d{3})(\d{4})/, "$1-$2-$3");
        }

        event.target.value = input;
    }
});
function saveDamdangat() {
    const inputField = document.getElementById("damdangat").value;
    

    const cNum_ = document.getElementById("cNum_").value;

  /*  if (!formattedNumber || !cNum_) {
        alert("연락처와 Num 값을 입력하세요.");
        return;
    }*/

    // fetch 요청 중복 실행 방지
    fetch(`https://silbo.kr/2025/api/question/update_damdangat.php`, {
        method: "POST",
        headers: { "Content-Type": "application/x-www-form-urlencoded" },
        body: `num=${encodeURIComponent(cNum_)}&damdangat=${encodeURIComponent(inputField)}`,
    })
    .then(response => response.text())
    .then(data => {
        console.log("서버 응답:", data); // 콘솔에서 확인 가능
        alert("담당자 연락처가 성공적으로 저장되었습니다."); // alert 1번만 실행
    })
    .catch(() => alert("담당자 연락처 저장 중 오류가 발생했습니다."));
}

document.addEventListener("click", function (event) {
    const target = event.target;
    const questionwareNum = document.getElementById("questionwareNum_")?.value;

    // 질문서 프린트
    if (target.id === "print-questionnaire") {
        if (!questionwareNum) {
            alert("질문서 번호가 없습니다.");
            return;
        }
        window.open(`https://silbo.kr/2014/_pages/php/downExcel/claim2.php?claimNum=${encodeURIComponent(questionwareNum)}`, "_blank");
    }

    // 청약서 프린트
    if (target.id === "print-application") {
        if (!questionwareNum) {
            alert("질문서 번호가 없습니다.");
            return;
        }
        window.open(`https://silbo.kr/2014/_pages/php/downExcel/claim3.php?claimNum=${encodeURIComponent(questionwareNum)}`, "_blank");
    }

    // 무사고 확인서
    if (target.id === "no-accident-check") {
        if (!questionwareNum) {
            alert("질문서 번호가 없습니다.");
            return;
        }
        window.open(`https://silbo.kr/2014/_pages/php/downExcel/claim7.php?claimNum=${encodeURIComponent(questionwareNum)}`, "_blank");
    }

    // 가입 안내문
    if (target.id === "send-guide") {
        if (!questionwareNum) {
            alert("질문서 번호가 없습니다.");
            return;
        }
        window.open(`https://silbo.kr/2014/_pages/php/downExcel/claim9.php?claimNum=${encodeURIComponent(questionwareNum)}`, "_blank");
    }

    // 아이디, 비번 초기화 메일 전송
    if (target.id === "send-id-email") {
        if (!questionwareNum) {
            alert("질문서 번호가 없습니다.");
            return;
        }

        fetch("https://silbo.kr/2025/api/email_send.php", {
            method: "POST",
            headers: { "Content-Type": "application/x-www-form-urlencoded" },
            body: `num=${encodeURIComponent(questionwareNum)}`,
        })
        .then(response => response.json())
        .then(data => {
            alert(data.success ? "성공적 발송 완료!" : "메일 발송 중 오류가 발생했습니다.");
        })
        .catch(() => alert("메일 전송 요청 실패."));
    }

   
});

// 무사고 확인서 URL 생성 함수
function question7_mail() {
    const claimNum = document.getElementById("questionwareNum_")?.value;
    return `http://silbo.kr/2014/_pages/php/downExcel/claim7.php?claimNum=${encodeURIComponent(claimNum)}`;
}
document.addEventListener("change", function (event) {
    if (event.target.id === "noticeSelect" || event.target.id === "noticeSelect2") {
        const noticeSelect = event.target.value; // 선택된 공지사항 값

        // school_5 또는 school_5_ 중 하나 선택
        let emailElement = null; // 변수 초기화

        if (event.target.id === "noticeSelect") {
            emailElement = document.getElementById("school_5");
        } else if (event.target.id === "noticeSelect2") { 
            emailElement = document.getElementById("school_5_");
        }

        if (emailElement) {
            console.log("선택된 요소:", emailElement);
        } else {
            console.log("두 요소 모두 존재하지 않습니다.");
        }
        const email = emailElement ? emailElement.innerText.trim() : ""; // null 체크 및 공백 제거

        console.log("선택된 공지사항 값:", noticeSelect); // 디버깅용
        console.log("이메일 값:", email); // 디버깅용

        if (!email || noticeSelect === "-1" ) {
            alert("이메일과 공지사항을 올바르게 선택하세요.");
            return;
        }

        if (!confirm(`[${email}] 으로 해당 이메일을 발송하시겠습니까?`)) {
            return;
        }

        // 로딩 표시 시작
        const loadingAlert = "메일을 발송 중입니다. 잠시만 기다려주세요...";
        
        // 로딩 알림 표시 (모달이나 알림창 대신 콘솔로도 확인 가능)
        console.log(loadingAlert);
        
        // 선택 요소들 비활성화 (중복 전송 방지)
        const selectElements = document.querySelectorAll('#noticeSelect, #noticeSelect2');
        selectElements.forEach(element => {
            element.disabled = true;
        });

        // 로딩 상태 표시를 위한 임시 알림 (실제로는 UI 요소로 대체하는 것이 좋습니다)
        const originalText = event.target.options[event.target.selectedIndex].text;
        event.target.options[event.target.selectedIndex].text = "발송 중...";

        const templates = {
            "1": {
                title: "[한화 현장실습보험] 보험금 청구시 필요서류 안내",
                content: `<div>안녕하십니까.<br><br>
                         현장실습보험 문의에 깊이 감사드립니다.<br><br>
                        1. 보험금 청구서(+필수 동의서) 및 문답서<br>
                        * 보험금 청구 기간은 최대 1년까지 가능합니다.<br>
                        * <div style="text-align: center; margin: 20px 0;">
                            <a href='https://silbo.kr/static/lib/attachfile/보험금 청구서,동의서,문답서_2023.pdf' 
                               target='_blank'
                               style='display: inline-block; background: linear-gradient(135deg, #FF6B35 0%, #F7931E 100%); 
                                      color: white; padding: 12px 25px; text-decoration: none; border-radius: 8px; 
                                      font-weight: bold; box-shadow: 0 2px 10px rgba(255,107,53,0.3);'>
                                💰 보험금 청구서류 다운로드
                            </a>
                          </div><br>
                        2. 신분증 및 통장사본<br><br>
                        3. 진단서 또는 초진차트<br><br>
                        4. 병원치료비 영수증(계산서)_치료비세부내역서, 약제비 영수증<br><br>
                        5. 실습기관의 현장실습 출석부 사본 또는 실습일지<br><br>
                        6. 학생 학적을 확인할 수 있는 학교 전산 캡처본<br><br>
                        7. 보험금 청구서 밑의 법정대리인의 서명, 가족관계증명서, 보호자 신분증 및 통장사본<br>
                        (고등학생 현장 실습 사고 접수 경우만 해당)<br><br>
                        위 서류들을 구비하셔서 메일 답장으로 부탁드립니다.<br><br>
                        자세한 사항은 현장실습 홈페이지(<a href='http://silbo.kr/'>http://silbo.kr/</a>)의 보상안내, 공지사항에서도 확인하실 수 있습니다.
                        <br><br>감사합니다.<br><br><hr>
                        <p style='font-size: 8px; color: #00A000;'>이투엘보험대리점</p>
                        <p style='font-size: 8px; color: #00A000;'>현장실습보험지원팀</p>
                        <p style='font-size: 8px; color: #00A000;'>1533-5013</p><br>
                        현장실습보험은 <span style='color: #FB2C10;'>한화손해보험</span>에서 제공합니다.</div>`,
                attachfile: ".",
            },
            "2": {
                title: "[이용안내문] 한화 현장실습 보험 이용 안내문",
                content: `<div>안녕하십니까.<br><br>
                        현장실습보험 문의에 깊이 감사드립니다.<br><br>
                        현장실습 이용방법이 담긴 안내문 첨부파일로 전달드립니다.<br><br>
                        <a href="http://silbo.kr/">현장실습 홈페이지 바로가기</a><br><br>
                        감사합니다.<br><br><hr>
                        <p style='font-size: 8px; color: #00A000;'>이투엘보험대리점</p>
                        <p style='font-size: 8px; color: #00A000;'>현장실습보험지원팀</p>
                        <p style='font-size: 8px; color: #00A000;'>1533-5013</p><br>
                        현장실습보험은 <span style='color: #FB2C10;'>한화손해보험</span>에서 제공합니다.</div>`,
                attachfile: "/static/lib/attachfile/한화 현장실습 보험 안내 팜플렛.pdf",
            },
            "3": {
                title: "[한화 현장실습보험] 무사고 확인서 요청",
                content: (() => {
                    var musagourl = question7_mail();
                    console.log("무사고 확인서 링크:", musagourl); // 디버깅용
                    return `<div>
                            안녕하십니까.<br><br>
                            보험 시작일이 설계일보다 앞서 무사고 확인서를 전달드립니다.<br><br>
                            첨부된 파일의 입금일에 입금 또는 카드결제하실 날짜 기입 후<br><br>
                            하단에 명판직인 날인하여 회신 주시면 청약서 발급 후 전달드리겠습니다.<br><br>
                            하기 링크 확인 부탁드립니다.<br><br>
                            <a href='https://www.silbo.kr/${musagourl}'>무사고 확인서 링크</a><br><br>
                            감사합니다.<br><br><hr>
                            <p style='font-size: 8px; color: #00A000;'>이투엘보험대리점</p>
                            <p style='font-size: 8px; color: #00A000;'>현장실습보험지원팀</p>
                            <p style='font-size: 8px; color: #00A000;'>1533-5013</p><br>
                            현장실습보험은 <span style='color: #FB2C10;'>한화손해보험</span>에서 제공합니다.
                        </div>`;
                })(),
                attachfile: ".",
            }
        };

        const selectedTemplate = templates[noticeSelect];

        if (!selectedTemplate) {
            alert("유효하지 않은 공지사항입니다.");
            // 원상복구
            selectElements.forEach(element => {
                element.disabled = false;
            });
            event.target.options[event.target.selectedIndex].text = originalText;
            return;
        }

        const formData = new FormData();
        formData.append("email", email);
        formData.append("title", selectedTemplate.title);
        formData.append("content", selectedTemplate.content);
        formData.append("attachfile", selectedTemplate.attachfile);

        const url = noticeSelect === "3"
            ? "https://silbo.kr/2025/api/musagoNotice.php"
            : "https://silbo.kr/2025/api/notice.php";

        fetch(url, {
            method: "POST",
            body: formData,
        })
        .then(response => response.text())
        .then(data => {
            console.log("서버 응답:", data); // 디버깅용
            alert("메일이 성공적으로 발송되었습니다.");
        })
        .catch(error => {
            console.error("메일 전송 오류:", error);
            alert("메일 전송 중 오류가 발생했습니다. 다시 시도해주세요.");
        })
        .finally(() => {
            // 로딩 상태 해제
            selectElements.forEach(element => {
                element.disabled = false;
            });
            event.target.options[event.target.selectedIndex].text = originalText;
            console.log("메일 전송 프로세스 완료");
        });
    }
});


///사업자 번호 시작 모달
document.addEventListener("click", function (e) {
// 모달 열기
if (e.target.classList.contains("open-modal")) {
	e.preventDefault();
	const num = e.target.dataset.num;

	fetch(`https://silbo.kr/2025/api/question/get_questionnaire_details.php?id=${num}`)
		.then(response => response.json())
		.then(response => {
			if (response.success) {
				document.getElementById("questionwareNum").value = response.data.num;
				if (response.data.num) {
					
					document.getElementById("write_").textContent = "수정";
				}
				document.getElementById("school1").value = response.data.school1;
				document.getElementById("school2").value = response.data.school2;
				document.getElementById("school3").value = response.data.school3;
				document.getElementById("school4").value = response.data.school4;
				document.getElementById("school5").value = response.data.school5;
				document.querySelector(`input[name="school6"][value="${response.data.school6}"]`).checked = true;
				document.getElementById("school7").value = response.data.school7;
				document.getElementById("school8").value = response.data.school8;
				document.getElementById("school9").value = response.data.school9;
				document.querySelector(`input[name="plan"][value="${response.data.school9}"]`).checked = true;

				if (response.data.directory == 2) {
					document.getElementById("daein1_").textContent = "1";
					document.getElementById("daein2_").textContent = "2";
				} else {
					document.getElementById("daein1").textContent = "2";
					document.getElementById("daein2").textContent = "3";
				}

				document.getElementById("daein").textContent = response.daeinP;
				document.getElementById("daemool").textContent = response.daemoolP;
				document.getElementById("week_total").textContent = response.data.week_total;
				document.getElementById("totalP").textContent = response.preiminum;

				for (let i = 4; i <= 26; i++) {
					document.getElementById(`week${i}`).value = response.data[`week${i}`] || "0";
				}

				document.getElementById("q_3_Modal").style.display = "block";
			} else {
				alert(response.error);
			}
		})
		.catch(() => {
			alert("데이터 로드 실패.");
		});
}




	// 수정 버튼 클릭
	if (e.target.id === "write_") {
		e.preventDefault();
		document.getElementById("daein").textContent = "";
		document.getElementById("daemool").textContent = "";
		document.getElementById("week_total").textContent = "";
		document.getElementById("totalP").textContent = "";

		const formData = {
			id: document.getElementById("questionwareNum").value,
			school1: document.getElementById("school1").value,
			school2: document.getElementById("school2").value,
			school3: document.getElementById("school3").value,
			school4: document.getElementById("school4").value,
			school5: document.getElementById("school5").value,
			school6: document.querySelector("input[name='school6']:checked").value,
			school7: document.getElementById("school7").value,
			school8: document.getElementById("school8").value,
			school9: document.getElementById("school9").value,
			plan: document.querySelector("input[name='plan']:checked").value,
			totalP: document.getElementById("totalP").textContent.replace(/,/g, ""),
		};

		for (let i = 4; i <= 26; i++) {
			formData[`week${i}`] = document.getElementById(`week${i}`).value.replace(/,/g, "");
		}

		fetch(`https://silbo.kr/2025/api/question/update_questionnaire.php`, {
			method: "POST",
			headers: { "Content-Type": "application/x-www-form-urlencoded" },
			body: new URLSearchParams(formData),
		})
			.then(response => response.json())
			.then(response => {
				if (response.success) {
					alert("수정되었습니다.");
					document.getElementById("daein").textContent = response.daeinP;
					document.getElementById("daemool").textContent = response.daemoolP;
					document.getElementById("week_total").textContent = response.week_total;
					document.getElementById("totalP").textContent = response.Preminum;
				} else {
					alert(response.error || "수정에 실패했습니다.");
				}
			})
			.catch(() => {
				alert("수정 요청 중 오류가 발생했습니다.");
			});
	}

	
});
// calculateTotal 함수도 추가
function calculateTotal() {
	 document.getElementById("daein").textContent = '';
	document.getElementById("daemool").textContent = '';
	document.getElementById("week_total").textContent ='';
	document.getElementById("totalP").textContent = '';
    let total = 0;
    // 4주차부터 26주차까지의 입력값을 합산
    for (let i = 4; i <= 26; i++) {
        const input = document.getElementById(`week${i}`);
        if (input) {
            // 입력값이 숫자가 아닌 경우 0으로 처리
            const value = parseInt(input.value) || 0;
            total += value;
        }
    }
    
    // 결과를 week_total span에 표시
    const totalSpan = document.getElementById('week_total');
    if (totalSpan) {
        totalSpan.textContent = total;
    }
}
// 사업자번호 모달 끝

//업로드 시작
document.addEventListener("click", function (event) {
if (event.target.classList.contains("upload-modal")) {
	event.preventDefault();
	const num = event.target.dataset.num;
	document.getElementById("qNum").value=num;
	fetch(`https://silbo.kr/2025/api/question/get_questionnaire_details.php?id=${num}`)
		.then(response => response.json())
		 .then(data => {
                if (data.success) {
                    // 데이터를 채움
                    document.getElementById("uploadModal").style.display = "block";
                    document.getElementById("cName").innerHTML = data.data.school1;
                } else {
                    alert(data.error);
                }
            })
            .catch(() => {
                alert("데이터 로드 실패.");
            });
        
        // 파일 검색 실행
        const qnum = document.getElementById("qNum").value;
		dynamiFileUpload();//파일업로드 동적생성
        fileSearch(qnum);
      
  }

    
	
}); 
function fileSearch(qnum) {
    fetch(`https://silbo.kr/2025/api/question/get_filelist.php?id=${qnum}`)
        .then(response => response.json())
        .then(fileData => {
            console.log(fileData);
            let rows2 = "";
            row2 = `<tr>
                        <th>순번</th>
                        <th>파일의종류</th>
                        <th>(설계/증권)번호</th>
                        <th>파일명</th>
                        <th>입력일자</th>
                        <th>기타</th>
                    </tr>`;
            document.getElementById("fileThead").innerHTML = row2;
            let rows = "";
            let i = 1;
            const kindMapping = {
                1: '카드전표',
                2: '영수증',
                3: '기타',
                4: '청약서',
                5: '과별인원',
                6: '보험사사업자등록증',
                7: '보험증권',
				8: '청약서날인본',
				9: '질문서날인본',
				10: '과별인원날인본'
            };
            
            fileData.forEach((item) => {
                const filePath = item.description2;
                const fileName = filePath.split('/').pop();
                const kind = kindMapping[item.kind] || '알 수 없음';
                
                // 전체 URL 생성 - 중복 슬래시 방지
                const fullUrl = `https://www.silbo.kr${filePath}`;
                
                console.log('filePath', filePath);
                console.log('fullUrl', fullUrl);
                
                rows += `
                    <tr>
                        <td>${i}</td>
                        <td>${kind}</td>
                        <td>${item.bunho}</td>
                        <td><a href="${fullUrl}" download target="_blank" class="file-link">${fileName}</a></td>
                        <td>${item.wdate}</td>
                        <td><button class="dButton" data-num="${item.num}">삭제</button></td>
                    </tr>
                `;
                i++;
            });
            
            // 테이블 내용 업데이트
            document.getElementById("file_list").innerHTML = rows;
            
            // 동적으로 생성된 삭제 버튼에 이벤트 리스너 추가
            document.querySelectorAll(".dButton").forEach(button => {
                button.addEventListener("click", function () {
                    const fileNum = this.getAttribute("data-num");
                    deleteFile(fileNum);
                });
            });
        })
        .catch(error => {
            alert('파일 데이터를 가져오는 데 실패했습니다.');
            console.error('Fetch 호출 실패:', error);
        });
}

// 파일 삭제 함수
function deleteFile(fileNum) {
    if (!confirm("정말 삭제하시겠습니까?")) return;

    fetch(`https://silbo.kr/2025/api/question/delete_file.php?id=${fileNum}`)
        .then(response => response.json())
        .then(result => {
            if (result.success) {
                alert("파일이 삭제되었습니다.");
                fileSearch(document.getElementById("qNum").value); // 파일 목록 새로고침
            } else {
                alert("파일 삭제 실패: " + result.error);
            }
        })
        .catch(error => {
            alert("파일 삭제 요청 실패");
            console.error("파일 삭제 오류:", error);
        });
}

   
function uploadFile() {
    // 중복 실행 방지를 위한 플래그
    if (window.isUploading) {
        console.log('업로드가 이미 진행 중입니다.');
        return;
    }
    
    const fileInput = document.getElementById('uploadedFile');
    const fileType = document.getElementById('fileType').value;
    const qNum = document.getElementById('qNum').value;
    const dynamicInput = document.getElementById('dynamicInput') ? document.getElementById('dynamicInput').value : '';
    
    // userName을 SessionManager에서 가져오기
    const userName = SessionManager.getUserInfo().name;
    
    // 디버깅을 위한 로그
    console.log('Upload data:', {
        fileType,
        qNum,
        dynamicInput,
        userName,
        fileSelected: fileInput.files.length > 0
    });
    
    // 파일 선택 확인
    if (fileInput.files.length === 0) {
        showUploadToast('파일을 선택해주세요.', 'warning');
        return;
    }
    
    // 청약서(4) 또는 보험증권(7) 업로드 시 번호 입력 필수
    if ((fileType === '4' || fileType === '7') && dynamicInput.trim() === '') {
        showUploadToast(fileType === '4' ? '설계번호를 입력해주세요.' : '증권번호를 입력해주세요.', 'warning');
        return;
    }
    
    // SessionManager에서 사용자 정보 확인
    if (!userName) {
        console.warn('SessionManager에서 사용자 이름을 가져올 수 없습니다.');
        showUploadToast('로그인 정보를 확인할 수 없습니다. 다시 로그인해주세요.', 'error');
        return;
    }
    
    // 업로드 시작 - 진행 상황 표시
    startUploadProgress();
    
    // 업로드 진행 플래그 설정
    window.isUploading = true;
    
    // 업로드 버튼 비활성화 (중복 클릭 방지)
    const uploadButton = document.querySelector('button[onclick="uploadFile()"]');
    if (uploadButton) {
        uploadButton.disabled = true;
        uploadButton.innerHTML = '<span class="spinner"></span> 업로드 중...';
    }
    
    const formData = new FormData();
    formData.append('file', fileInput.files[0]);
    formData.append('fileType', fileType);
    formData.append('qNum', qNum);
    formData.append('userName', SessionManager.getUserInfo().name);
    
    // 파일 타입이 청약서(4) 또는 보험증권(7)일 경우 번호 추가
    if (fileType === '4') {
        formData.append('designNumber', dynamicInput.trim()); // 설계번호 추가 (앞뒤 공백 제거)
    } else if (fileType === '7') {
        formData.append('certificateNumber', dynamicInput.trim()); // 증권번호 추가 (앞뒤 공백 제거)
    }
    
    // FormData 내용 확인 (디버깅 용도)
    console.log('FormData contents:');
    for (let [key, value] of formData.entries()) {
        console.log(key, value);
    }
    
    // 파일 업로드 단계별 피드백
    updateUploadProgress('📤 파일 업로드 준비 중...', 10);
    
    // XMLHttpRequest를 사용하여 진행률 추적
    const xhr = new XMLHttpRequest();
    
    // 업로드 진행률 추적
    xhr.upload.addEventListener('progress', function(e) {
        if (e.lengthComputable) {
            const percentComplete = Math.round((e.loaded / e.total) * 80) + 10; // 10-90%
            updateUploadProgress(`📤 파일 업로드 중... ${percentComplete}%`, percentComplete);
        }
    });
    
    // 응답 처리
    xhr.addEventListener('load', function() {
        updateUploadProgress('⚙️ 서버에서 처리 중...', 95);
        
        if (xhr.status >= 200 && xhr.status < 300) {
            const result = xhr.responseText;
            console.log('Upload result:', result);
            
            // JSON 응답인지 확인
            try {
                const jsonResult = JSON.parse(result);
                if (jsonResult.status === 'success') {
                    // 성공 처리
                    updateUploadProgress('✅ 업로드 완료!', 100);
                    
                    setTimeout(() => {
                        showUploadToast('업로드 완료: ' + jsonResult.message, 'success');
                        hideUploadProgress();
                        fileSearch(qNum); // 파일 목록 갱신
                        
                        // 폼 초기화
                        fileInput.value = '';
                        if (document.getElementById('dynamicInput')) {
                            document.getElementById('dynamicInput').value = '';
                        }
                        
                        // 이메일 발송 여부 확인 (청약서/증권의 경우)
                        if (fileType === '4' || fileType === '7') {
                            const emailType = fileType === '4' ? '청약서' : '증권';
                            showUploadToast(`${emailType} 발급 안내 이메일이 발송됩니다. 📧`, 'info', 5000);
                        }
                    }, 500);
                } else {
                    updateUploadProgress('❌ 업로드 실패', 100);
                    setTimeout(() => {
                        showUploadToast('업로드 실패: ' + jsonResult.message, 'error');
                        hideUploadProgress();
                    }, 500);
                    console.error('Upload error:', jsonResult);
                }
            } catch (e) {
                // JSON이 아닌 경우 (기존 방식)
                updateUploadProgress('✅ 업로드 완료!', 100);
                setTimeout(() => {
                    showUploadToast('업로드 완료: ' + result, 'success');
                    hideUploadProgress();
                    fileSearch(qNum); // 파일 목록 갱신
                }, 500);
            }
        } else {
            updateUploadProgress('❌ 서버 오류', 100);
            setTimeout(() => {
                showUploadToast(`서버 오류 (${xhr.status}): 관리자에게 문의하세요.`, 'error');
                hideUploadProgress();
            }, 500);
        }
    });
    
    // 네트워크 오류 처리
    xhr.addEventListener('error', function() {
        updateUploadProgress('❌ 네트워크 오류', 100);
        setTimeout(() => {
            showUploadToast('네트워크 오류가 발생했습니다. 연결 상태를 확인해주세요.', 'error');
            hideUploadProgress();
        }, 500);
        console.error('네트워크 오류 발생');
    });
    
    // 타임아웃 처리
    xhr.addEventListener('timeout', function() {
        updateUploadProgress('❌ 업로드 시간 초과', 100);
        setTimeout(() => {
            showUploadToast('업로드 시간이 초과되었습니다. 파일 크기를 확인하거나 다시 시도해주세요.', 'error');
            hideUploadProgress();
        }, 500);
    });
    
    // 업로드 중단 처리
    xhr.addEventListener('abort', function() {
        updateUploadProgress('❌ 업로드 중단됨', 100);
        setTimeout(() => {
            showUploadToast('업로드가 중단되었습니다.', 'warning');
            hideUploadProgress();
        }, 500);
    });
    
    // 요청 설정 및 전송
    xhr.timeout = 60000; // 60초 타임아웃
    xhr.open('POST', 'https://silbo.kr/2025/api/question/upload.php');
    xhr.send(formData);
    
    // 업로드 완료 후 정리는 각 이벤트 핸들러에서 처리
    xhr.addEventListener('loadend', function() {
        // 업로드 완료 후 플래그 해제 및 버튼 복원
        window.isUploading = false;
        
        if (uploadButton) {
            uploadButton.disabled = false;
            uploadButton.innerHTML = '업로드'; // 원래 텍스트로 복원
        }
    });
}

// 업로드 진행 상황 표시 함수들
function startUploadProgress() {
    // 진행 상황 모달이 없으면 생성
    if (!document.getElementById('uploadProgressModal')) {
        const modal = document.createElement('div');
        modal.id = 'uploadProgressModal';
        modal.innerHTML = `
            <div class="upload-progress-overlay">
                <div class="upload-progress-modal">
                    <div class="upload-progress-header">
                        <h3>📤 파일 업로드</h3>
                    </div>
                    <div class="upload-progress-body">
                        <div class="upload-progress-bar-container">
                            <div class="upload-progress-bar" id="uploadProgressBar"></div>
                        </div>
                        <div class="upload-progress-text" id="uploadProgressText">업로드 준비 중...</div>
                        <div class="upload-progress-percentage" id="uploadProgressPercentage">0%</div>
                    </div>
                </div>
            </div>
        `;
        document.body.appendChild(modal);
        
        // 스타일 추가
        if (!document.getElementById('uploadProgressStyles')) {
            const styles = document.createElement('style');
            styles.id = 'uploadProgressStyles';
            styles.textContent = `
                .upload-progress-overlay {
                    position: fixed;
                    top: 0;
                    left: 0;
                    width: 100%;
                    height: 100%;
                    background: rgba(0, 0, 0, 0.5);
                    display: flex;
                    justify-content: center;
                    align-items: center;
                    z-index: 10000;
                }
                
                .upload-progress-modal {
                    background: white;
                    border-radius: 12px;
                    padding: 24px;
                    min-width: 400px;
                    max-width: 90vw;
                    box-shadow: 0 4px 20px rgba(0, 0, 0, 0.3);
                }
                
                .upload-progress-header h3 {
                    margin: 0 0 20px 0;
                    color: #333;
                    text-align: center;
                    font-size: 18px;
                }
                
                .upload-progress-bar-container {
                    width: 100%;
                    height: 8px;
                    background: #e9ecef;
                    border-radius: 4px;
                    overflow: hidden;
                    margin-bottom: 16px;
                }
                
                .upload-progress-bar {
                    height: 100%;
                    background: linear-gradient(90deg, #009E25, #00B82F);
                    border-radius: 4px;
                    transition: width 0.3s ease;
                    width: 0%;
                }
                
                .upload-progress-text {
                    text-align: center;
                    color: #666;
                    margin-bottom: 8px;
                    font-size: 14px;
                }
                
                .upload-progress-percentage {
                    text-align: center;
                    font-weight: bold;
                    font-size: 16px;
                    color: #009E25;
                }
                
                .spinner {
                    display: inline-block;
                    width: 12px;
                    height: 12px;
                    border: 2px solid #f3f3f3;
                    border-top: 2px solid #009E25;
                    border-radius: 50%;
                    animation: spin 1s linear infinite;
                    margin-right: 8px;
                }
                
                @keyframes spin {
                    0% { transform: rotate(0deg); }
                    100% { transform: rotate(360deg); }
                }
            `;
            document.head.appendChild(styles);
        }
    }
    
    // 모달 표시
    document.getElementById('uploadProgressModal').style.display = 'block';
}

function updateUploadProgress(text, percentage) {
    const progressBar = document.getElementById('uploadProgressBar');
    const progressText = document.getElementById('uploadProgressText');
    const progressPercentage = document.getElementById('uploadProgressPercentage');
    
    if (progressBar) progressBar.style.width = percentage + '%';
    if (progressText) progressText.textContent = text;
    if (progressPercentage) progressPercentage.textContent = percentage + '%';
}

function hideUploadProgress() {
    const modal = document.getElementById('uploadProgressModal');
    if (modal) {
        modal.style.display = 'none';
    }
}

// 메시지 표시 함수 (토스트 스타일) - 업로드 전용
function showUploadToast(message, type = 'info', duration = 3000) {
    // 기존 토스트 제거
    const existingToast = document.querySelector('.upload-toast-message');
    if (existingToast) {
        existingToast.remove();
    }
    
    const toast = document.createElement('div');
    toast.className = `upload-toast-message upload-toast-${type}`;
    
    const icons = {
        success: '✅',
        error: '❌',
        warning: '⚠️',
        info: 'ℹ️'
    };
    
    toast.innerHTML = `
        <span class="upload-toast-icon">${icons[type] || icons.info}</span>
        <span class="upload-toast-text">${message}</span>
    `;
    
    // 토스트 스타일이 없으면 추가
    if (!document.getElementById('uploadToastStyles')) {
        const styles = document.createElement('style');
        styles.id = 'uploadToastStyles';
        styles.textContent = `
            .upload-toast-message {
                position: fixed;
                top: 20px;
                right: 20px;
                background: white;
                border-radius: 8px;
                padding: 12px 16px;
                box-shadow: 0 4px 12px rgba(0, 0, 0, 0.2);
                display: flex;
                align-items: center;
                gap: 8px;
                z-index: 10001;
                min-width: 300px;
                max-width: 500px;
                animation: uploadSlideIn 0.3s ease;
                border-left: 4px solid;
            }
            
            .upload-toast-success { border-left-color: #28a745; }
            .upload-toast-error { border-left-color: #dc3545; }
            .upload-toast-warning { border-left-color: #ffc107; }
            .upload-toast-info { border-left-color: #17a2b8; }
            
            .upload-toast-icon {
                font-size: 18px;
                flex-shrink: 0;
            }
            
            .upload-toast-text {
                flex: 1;
                font-size: 14px;
                color: #333;
            }
            
            @keyframes uploadSlideIn {
                from {
                    transform: translateX(100%);
                    opacity: 0;
                }
                to {
                    transform: translateX(0);
                    opacity: 1;
                }
            }
            
            @keyframes uploadSlideOut {
                from {
                    transform: translateX(0);
                    opacity: 1;
                }
                to {
                    transform: translateX(100%);
                    opacity: 0;
                }
            }
        `;
        document.head.appendChild(styles);
    }
    
    document.body.appendChild(toast);
    
    // 자동 제거
    setTimeout(() => {
        toast.style.animation = 'uploadSlideOut 0.3s ease';
        setTimeout(() => {
            if (toast.parentNode) {
                toast.parentNode.removeChild(toast);
            }
        }, 300);
    }, duration);
}




function dynamiFileUpload() {
    // 파일 타입 옵션 (동적 데이터)
    const fileTypes = [
        { value: "4", text: "청약서" },
        { value: "1", text: "카드전표" },
        { value: "2", text: "영수증" },
        { value: "7", text: "보험증권" },
        { value: "5", text: "과별인원현황" },
        { value: "6", text: "보험사사업자등록증" },
        { value: "3", text: "기타" }
    ];

    // 1️⃣ 동적으로 `<select>` 요소 생성
    const fileTypeSelect = document.createElement("select");
    fileTypeSelect.id = "fileType";
    fileTypeSelect.classList.add("u_select");
    fileTypeSelect.name = "fileType";

    // 옵션 추가
    fileTypes.forEach(optionData => {
        const option = document.createElement("option");
        option.value = optionData.value;
        option.textContent = optionData.text;
        fileTypeSelect.appendChild(option);
    });

    // 2️⃣ 동적 입력 필드 컨테이너 생성
    const dynamicField = document.createElement("div");
    dynamicField.id = "dynamicField";
    dynamicField.style.display = "none"; // 기본적으로 숨김

    const dynamicInput = document.createElement("input");
    dynamicInput.type = "text";
    dynamicInput.id = "dynamicInput";
    dynamicInput.name = "dynamicInput";
    dynamicField.appendChild(dynamicInput);

    // 3️⃣ 파일 업로드 필드 및 버튼 생성
    const fileInput = document.createElement("input");
    fileInput.type = "file";
    fileInput.id = "uploadedFile";
    fileInput.name = "uploadedFile";
    fileInput.classList.add("uploadedFile");

    const uploadButton = document.createElement("button");
	uploadButton.classList.add("uButton");
    uploadButton.type = "button"; // ✅ 버튼 기본 타입 변경 (submit → button)
    uploadButton.textContent = "업로드";

    // ✅ `uploadFile()` 실행 추가
    uploadButton.addEventListener("click", function (event) {
        event.preventDefault(); // 기본 동작 방지
        uploadFile(); // 업로드 실행
    });

    // 4️⃣ 업로드 컨테이너에 추가
    const uploadContainer = document.querySelector(".upload-container");
    uploadContainer.innerHTML = ""; // 기존 내용 제거
    uploadContainer.appendChild(fileTypeSelect);
    uploadContainer.appendChild(dynamicField);
    uploadContainer.appendChild(fileInput);
    uploadContainer.appendChild(uploadButton);

    // 5️⃣ `toggleInputField()` 함수 정의 및 적용
    function toggleInputField() {
        const fileType = fileTypeSelect.value;

        if (fileType === "4") {
            dynamicField.style.display = "block";
            dynamicInput.placeholder = "설계번호를 입력하세요";
        } else if (fileType === "7") {
            dynamicField.style.display = "block";
            dynamicInput.placeholder = "증권번호를 입력하세요";
        } else {
            dynamicField.style.display = "none";
            dynamicInput.value = ""; // 입력 값 초기화
        }
    }

    // 이벤트 리스너 추가
    fileTypeSelect.addEventListener("change", toggleInputField);

    // 초기 실행
    toggleInputField();
}

//업로드 끝

//클레임 모달 


document.addEventListener("click", function (event) {
	//질문서에 클레임 요청하는  경우 question
	//클레임 리스트에서 
    if (event.target.classList.contains("open-claim-modal") || event.target.classList.contains("c_1_open-claim-modal") ) {
        event.preventDefault();
			if (event.target.classList.contains("open-claim-modal")) {// 현장실습보험 질문서 신청리스트 // 클레임버튼을  클릭할 경우 
				// "open-claim-modal" 클래스가 클릭된 경우 실행할 로직
				console.log("open-claim-modal 버튼 클릭됨");
				const num = event.target.dataset.num;
				console.log(num);
				
				const modal = document.querySelector(".claimModal"); // ✅ claimModal로 변경

				if (!modal) {
					console.error("🚨 claimModal이 존재하지 않습니다.");
					return;
				}
				
				// 기존 데이터 초기화 (모달을 닫았다가 다시 열 때 문제 방지)
				modal.querySelectorAll("input, textarea").forEach(input => input.value = "");
				modal.querySelectorAll("span").forEach(span => span.innerText = "");

				fetch(`https://silbo.kr/2025/api/question/get_questionnaire_details.php?id=${num}`)
					.then(response => response.json())
					.then(response => {
						if (response.success) {
							// 데이터 입력
							document.getElementById("certi__").innerText = response.data.certi;
							document.getElementById("school_1_").innerText = response.data.school1;
							document.getElementById("school_2_").innerText = response.data.school2;
							document.getElementById("school_3_").innerText = response.data.school3;
							document.getElementById("school_4_").innerText = response.data.school4;
							document.getElementById("school_5_").innerText = response.data.school5;
							document.getElementById("school_7_").innerText = response.data.school7;
							document.getElementById("school_8_").innerText = response.data.school8;

							// 현장실습 시기 설정
							const periods = { "1": "1학기", "2": "하계", "3": "2학기", "4": "동계" };
							document.getElementById("school_6_").innerText = periods[response.data.school6] || "알 수 없음";

							// 가입유형 설정
							document.getElementById("school_9_").innerText = response.data.school9 == 1 ? "가입유형 A" : "가입유형 B";

							// 대인대물 설정
							const limits = response.data.directory == 2 ? { A: "2 억", B: "3 억" } : { A: "2 억", B: "3 억" };
							document.getElementById("daein1__").innerText = limits[response.data.school9 == 1 ? "A" : "B"];
							document.getElementById("daein2__").innerText = limits[response.data.school9 == 1 ? "A" : "B"];
							
							document.getElementById("cNum__").value = response.data.cNum;
							document.getElementById("questionNum__").value = num;
							// ✅ 모달이 여러 번 열리는 문제 해결: 항상 처음부터 다시 표시
							modal.style.display = "flex";

						} else {
							alert(response.error);
						}
					})
					.catch(() => {
						alert("Claim 로드 실패.");
					});
				}else if (event.target.classList.contains("c_1_open-claim-modal")) {  // 현장실습보험 // 클레임 리스트에서  번호를 클릭할 경우 

						const num = event.target.dataset.num;
					
					const modal = document.querySelector(".claimModal"); // ✅ claimModal로 변경

					if (!modal) {
						console.error("🚨 k_1_claimModal이 존재하지 않습니다.");
						return;
					}

					// 기존 데이터 초기화 (모달을 닫았다가 다시 열 때 문제 방지)
					modal.querySelectorAll("input, textarea").forEach(input => input.value = "");
					modal.querySelectorAll("span").forEach(span => span.innerText = "");

					fetch(`https://silbo.kr/2025/api/claim/get_claim_details.php?id=${num}`)
						.then(response => response.json())
						.then(response => {
							if (response.success) {
								// 데이터 입력
								
								document.getElementById("claimNum__").value=num;//claimList Table 의 num 
								document.getElementById("certi__").innerText = response.data.certi;
								document.getElementById("school_1_").innerText = response.school1;
								document.getElementById("school_2_").innerText = response.school2;
								document.getElementById("school_3_").innerText = response.school3;
								document.getElementById("school_4_").innerText = response.school4;
								document.getElementById("school_5_").innerText = response.school5;
								document.getElementById("school_7_").innerText = response.school7;
								document.getElementById("school_8_").innerText = response.school8;

								// 현장실습 시기 설정
								const periods = { "1": "1학기", "2": "하계", "3": "2학기", "4": "동계" };
								document.getElementById("school_6_").innerText = periods[response.school6] || "알 수 없음";

								// 가입유형 설정
								document.getElementById("school_9_").innerText = response.school9 == 1 ? "가입유형 A" : "가입유형 B";

								// 대인대물 설정
								const limits = response.directory == 2 ? { A: "2 억", B: "3 억" } : { A: "2 억", B: "3 억" };
								document.getElementById("daein1__").innerText = limits[response.school9 == 1 ? "A" : "B"];
								document.getElementById("daein2__").innerText = limits[response.school9 == 1 ? "A" : "B"];

								document.getElementById("cNum__").value = response.data.cNum;

								document.getElementById('claimStore').textContent = '클레임수정';

								// 사고일자
								document.getElementById('wdate_3').value = response.data.wdate_3;

								// 사고접수번호
								document.getElementById('claimNumber').value = response.data.claimNumber;

								// 보험금 지급일
								document.getElementById('wdate_2').value = response.data.wdate_2;

								// 보험금 (NaN 또는 빈 값 처리)
								const formattedPreiminum = response.data.claimAmout && !isNaN(parseFloat(response.data.claimAmout))
									? parseFloat(response.data.claimAmout).toLocaleString("en-US")
									: "";
								document.getElementById('claimAmout').value = formattedPreiminum;

								// 피해학생
								document.getElementById('student').value = response.data.student;

								// 사고경위 (100자 제한)
								document.getElementById('accidentDescription').value = response.data.accidentDescription;

								// 담당자 정보 (NULL 값 처리)
								document.getElementById('damdanga_').value = response.data.damdanga === "NULL" ? "" : response.data.damdanga;
								document.getElementById('damdangat_').value = response.data.damdangat === "NULL" ? "" : response.data.damdangat;

								document.getElementById("questionNum__").value = response.data.qNum;
								// ✅ 모달이 여러 번 열리는 문제 해결: 항상 처음부터 다시 표시
								modal.style.display = "flex";

							} else {
								alert(response.error);
							}
						})
						.catch(() => {
							alert("Claim 로드 실패.");
						});


				}
			
			}  //1790 시작에서 끝

    
});



//클레임 저장 

document.addEventListener("click", function (event) {
    if (event.target.id === "claimStore") {
        event.preventDefault();
        const certiElement = document.getElementById("certi__");
        const certi = certiElement ? certiElement.innerHTML.trim() : "";
        
        if (!certi) {
            alert("증권번호가 없습니다. 저장할 수 없습니다.");
            return;
        }

        const accidentDescription = document.getElementById("accidentDescription").value.trim();
        if (!accidentDescription) {
            alert("사고경위는 필수 입력입니다.");
            return;
        }

        // 데이터 수집
        const claimData = new FormData();
        claimData.append("school1", document.getElementById("school_1_").innerHTML);
        claimData.append("qNum", document.getElementById("questionNum__").value);
        claimData.append("cNum", document.getElementById("cNum__").value);
        claimData.append("claimNum__", document.getElementById("claimNum__").value);
        claimData.append("certi", certi);
        claimData.append("claimNumber", document.getElementById("claimNumber").value);
        claimData.append("wdate_2", document.getElementById("wdate_2").value);
        claimData.append("wdate_3", document.getElementById("wdate_3").value);
        claimData.append("claimAmout", document.getElementById("claimAmout").value.replace(/,/g, ""));
        claimData.append("student", document.getElementById("student").value);
        claimData.append("accidentDescription", accidentDescription);
        claimData.append("manager", SessionManager.getUserInfo().name);
        claimData.append("damdanga", document.getElementById("damdanga_").value);
        claimData.append("damdangat", document.getElementById("damdangat_").value);

        // 데이터 전송
        fetch(`https://silbo.kr/2025/api/claim/claim_store.php`, {
            method: "POST",
            body: claimData,
        })
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    document.getElementById("claimNum__").value = data.num;
                    document.getElementById("claimStore").textContent = "클레임수정";
                    alert(data.message);
                } else {
                    alert("오류 발생: " + (data.error || "알 수 없는 오류"));
                }
            })
            .catch(() => {
                alert("데이터 저장에 실패했습니다.");
            });
    }
});

// 전화번호 입력 시 자동 하이픈(-) 추가
document.addEventListener("input", function (event) {
    if (event.target.id === "damdangat_") {
        let input = event.target.value.replace(/\D/g, ""); // 숫자만 남기기

        if (input.length === 11) {
            input = input.replace(/(\d{3})(\d{4})(\d{4})/, "$1-$2-$3");
        } else if (input.length === 10) {
            input = input.replace(/(\d{3})(\d{3})(\d{4})/, "$1-$2-$3");
        } else if (input.length === 9) {
            input = input.replace(/(\d{2})(\d{3})(\d{4})/, "$1-$2-$3");
        }

        event.target.value = input;
    }
});

// 클릭하면 하이픈 제거
document.addEventListener("focus", function (event) {
    if (event.target.id === "damdangat_") {
        event.target.value = event.target.value.replace(/-/g, "");
    }
}, true); // 캡처 단계에서 이벤트 감지

// Flatpickr 적용 (날짜 입력)
/*
document.addEventListener("DOMContentLoaded", function () {
    flatpickr("#wdate_2", { dateFormat: "Y-m-d", allowInput: true });
    flatpickr("#wdate_3", { dateFormat: "Y-m-d", allowInput: true });
});*/
/*
document.addEventListener('DOMContentLoaded', function () {
    // wdate_2 요소에 flatpickr 적용
    if (document.getElementById("wdate_2")) {
        flatpickr("#wdate_2", {
            dateFormat: "Y-m-d", // 날짜 형식 (YYYY-MM-DD)
            allowInput: true // 직접 입력 허용
        });
    }
	// wdate_2 요소에 flatpickr 적용
    if (document.getElementById("wdate_3")) {
        flatpickr("#wdate_3", {
            dateFormat: "Y-m-d", // 날짜 형식 (YYYY-MM-DD)
            allowInput: true // 직접 입력 허용
        });
    }
	
});*/