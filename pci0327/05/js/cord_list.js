/** 코드  js 파일 - 반응형 개선 버전 + 로딩 시스템 적용 **/

// 로딩 상태를 추적하는 플래그 변수
let isCordLoading = false;
let currentCordPage = 1; // 현재 페이지 추가
let currentCordSearchKeyword = ''; // 현재 검색어 추가
let currentCordSearchMode = 1; // 현재 검색 모드 추가

function cordList(){
	 console.log('cordList 함수 시작');
    const pageContent = document.getElementById('page-content');
  
    const cordFieldContents = `
        <div class="cord-list-container">
            <!-- 검색 영역 -->
            <div class="cord-list-header">
                <!-- 데스크탑 검색 영역 -->
                <div class="cord-left-area desktop-only">
                    <div class="cord-search-area">
                        <select id="cord-searchType" class='cord-searchType'>
                            <option value="1">보험회사</option>
                            <option value="2">대리점명</option>
                            <option value="3">특이사항</option>
                        </select>
                        <input type="text" id="cord-searchKeyword" class="cord-searchKeyword" placeholder="검색어를 입력하세요" onkeypress="if(event.key === 'Enter') cordSearchList()">
                        <button class="cord-search-button" onclick="cordSearchList()">검색</button>
                    </div>
                </div>
                
                <!-- 모바일 검색 영역 -->
                <div class="cord-mobile-search-area mobile-only">
                    <div class="mobile-filter-row">
                        <select id="cord-searchType-mobile" class='cord-searchType-mobile'>
                           <option value="1">보험회사</option>
                           <option value="2">대리점명</option>
                            <option value="3">특이사항</option>
                        </select>
                    </div>
                    <div class="mobile-filter-row">
                        <input type="text" id="cord-searchKeyword-mobile" class="cord-searchKeyword-mobile" placeholder="검색어를 입력하세요" onkeypress="if(event.key === 'Enter') cordSearchList()">
                    </div>
                    <div class="mobile-filter-row">
                        <button class="cord-search-button-mobile" onclick="cordSearchList()">검색</button>
                    </div>
                </div>
                
                <div class="cord-right-area">
                    <button class="cord-stats-button" onclick="cordOpenDetailModal()">신규등록</button>
                </div>
            </div>

            <!-- 리스트 영역 -->
            <div class="cord-list-content">
                <!-- 데스크탑 테이블 -->
                <div class="cord-data-table-container desktop-only">
                    <table class="cord-data-table">
                        <thead>
                            <tr>
                                <th class="col-num">No</th>
                                <th class="col-business-num">보험회사</th>
                                <th class="col-school">대리점명</th>
                                <th class="col-students">사용인</th>
                                <th class="col-phone">대리점코드</th>
                                <th class="col-date">사용인코드</th>
                                <th class="col-policy"> 인증</th>
                                <th class="col-premium">비밀번호</th>
                                <th class="col-insurance">update</th>
                                <th class="col-status">지점장</th>
                                <th class="col-contact">여직원</th>
                                <th class="col-action">지점번호</th>
                                <th class="col-action">지점팩스</th>
                                <th class="col-memo">특이사항</th>
                            </tr>
                        </thead>
                        <tbody id="cord-applicationList">
                            <tr><td colspan="14" class="loading">데이터 로드 중...</td></tr>
                        </tbody>
                    </table>
                </div>
                
                <!-- 모바일 카드 뷰 -->
                <div class="cord-mobile-card-view mobile-only">
                    <div id="cord-mobile-cards-container">
                        <!-- 모바일 카드가 여기에 동적으로 추가됨 -->
                    </div>
                </div>
            </div>

            <!-- 데스크탑 페이지네이션 -->
            <div class="cord-pagination desktop-only"></div>
            
            <!-- 모바일 페이지네이션 -->
            <div id="cord-mobile-pagination-container" class="mobile-pagination-container mobile-only">
                <ul id="cord-mobile-pagination" class="mobile-pagination"></ul>
            </div>
        </div>`;
                            
    pageContent.innerHTML = cordFieldContents;
    
    // DOM 요소가 완전히 렌더링된 후에 데이터 로드
    setTimeout(() => {
        // 초기값 설정 후 첫 페이지 로드
        currentCordPage = 1;
        currentCordSearchKeyword = '';
        currentCordSearchMode = 1;
        cordLoadTable(1, '', 1);
        
        // 모바일 검색 동기화 이벤트 리스너 추가
        cordSetupMobileSearchSync();
    }, 0);
}

// 모바일과 데스크탑 검색 동기화
function cordSetupMobileSearchSync() {
    const cordDesktopSearchType = document.getElementById('cord-searchType');
    const cordMobileSearchType = document.getElementById('cord-searchType-mobile');
    const cordDesktopSearchKeyword = document.getElementById('cord-searchKeyword');
    const cordMobileSearchKeyword = document.getElementById('cord-searchKeyword-mobile');
    
    if (cordDesktopSearchType && cordMobileSearchType) {
        cordDesktopSearchType.addEventListener('change', function() {
            cordMobileSearchType.value = this.value;
        });
        
        cordMobileSearchType.addEventListener('change', function() {
            cordDesktopSearchType.value = this.value;
        });
    }
    
    if (cordDesktopSearchKeyword && cordMobileSearchKeyword) {
        cordDesktopSearchKeyword.addEventListener('input', function() {
            cordMobileSearchKeyword.value = this.value;
        });
        
        cordMobileSearchKeyword.addEventListener('input', function() {
            cordDesktopSearchKeyword.value = this.value;
        });
    }
}

function cordLoadTable(cordPage = 1, cordSearchSchool = '', cordSearchMode = 1) {
    // 이미 로딩 중이면 중복 실행 방지
    if (isCordLoading) {
        console.log('이미 데이터를 로드하고 있습니다.');
        return;
    }
    
    isCordLoading = true; // 로딩 시작
    
    // 검색 중인지 여부 확인
    const isSearching = cordSearchSchool && cordSearchSchool.trim() !== '';
    
    // 로딩 메시지 설정
    let loadingMessage = '데이터를 불러오는 중...';
    if (isSearching) {
        loadingMessage = `"${cordSearchSchool}" 검색 중...`;
    }
    
    // 로딩 인디케이터 생성 및 표시
    if (!LoadingSystem.instances['cord-search']) {
        LoadingSystem.create('cord-search', {
            text: loadingMessage,
            autoShow: true
        });
    } else {
        LoadingSystem.setText('cord-search', loadingMessage);
        LoadingSystem.show('cord-search');
    }
    
    // 현재 상태 업데이트
    currentCordPage = cordPage;
    currentCordSearchKeyword = cordSearchSchool;
    currentCordSearchMode = cordSearchMode;
    
    const cordItemsPerPage = 15;
    const cordTableBody = document.querySelector("#cord-applicationList");
    const cordMobileContainer = document.querySelector("#cord-mobile-cards-container");
    const cordPagination = document.querySelector(".cord-pagination");
    const cordMobilePagination = document.querySelector("#cord-mobile-pagination");

    // 테이블과 모바일 컨테이너 로딩 표시
    if (cordTableBody) {
        cordTableBody.innerHTML = `<tr><td colspan="14" class="loading">${loadingMessage}</td></tr>`;
    }
    if (cordMobileContainer) {
        cordMobileContainer.innerHTML = `<div class="mobile-loading">${loadingMessage}</div>`;
    }
    if (cordPagination) cordPagination.innerHTML = "";
    if (cordMobilePagination) cordMobilePagination.innerHTML = "";

    fetch(`../simg/api/cord/fetch_cord.php?page=${cordPage}&limit=${cordItemsPerPage}&search_school=${cordSearchSchool}&search_mode=${cordSearchMode}`)
        .then(cordResponse => {
            // 1단계: HTTP 상태 확인
            if (!cordResponse.ok) {
                throw new Error(`HTTP Error: ${cordResponse.status} - ${cordResponse.statusText}`);
            }
            
            // 2단계: 응답 텍스트를 먼저 확인
            return cordResponse.text();
        })
        .then(cordText => {
           //console.log('서버 응답 원본:', cordText); // 디버깅용
            
            // 3단계: 빈 응답 체크
            if (!cordText || cordText.trim() === '') {
                throw new Error('서버에서 빈 응답을 받았습니다.');
            }
            
            // 4단계: JSON 파싱 시도
            let cordResponseData;
            try {
                cordResponseData = JSON.parse(cordText);
            } catch (cordJsonError) {
                console.error('JSON 파싱 실패:', cordJsonError);
                console.error('응답 내용:', cordText);
                throw new Error(`JSON 파싱 오류: ${cordJsonError.message}`);
            }
            
            // 데스크탑 테이블용 HTML 생성
            let cordRows = "";
            // 모바일 카드용 HTML 생성
            let cordMobileCards = "";

            // 데이터 존재 여부 확인
            if (!cordResponseData.data || cordResponseData.data.length === 0) {
                const noDataMessage = isSearching ? 
                    `"${cordSearchSchool}" 검색 결과가 없습니다.` : 
                    '검색 결과가 없습니다.';
                cordRows = `<tr><td colspan="14" style="text-align: center;">${noDataMessage}</td></tr>`;
                cordMobileCards = `<div class="mobile-no-data">${noDataMessage}</div>`;
            } else {
                //console.log('cordResponseData.data.', cordResponseData.data);
                cordResponseData.data.forEach((cordItem, cordIndex) => {
                    // 현재 페이지의 실제 순번 계산
                    const cordActualIndex = (cordPage - 1) * cordItemsPerPage + cordIndex + 1;
                    
                    // 보험사 선택 옵션
                    const cordInsuranceOptions = `
                        <select class="cord-insurance-select" data-id="${cordItem.num}" onchange="cordUpdateInsuranceCompany(${cordItem.num}, this.value)">
                            <option value="-1" ${cordItem.insuCompany == -1 ? "selected" : ""}>선택</option>
                            <option value="1" ${cordItem.insuCompany == 1 ? "selected" : ""}>흥국</option>
                            <option value="2" ${cordItem.insuCompany == 2 ? "selected" : ""}>DB</option>
                            <option value="3" ${cordItem.insuCompany == 3 ? "selected" : ""}>KB</option>
                            <option value="4" ${cordItem.insuCompany == 4 ? "selected" : ""}>현대</option>
                            <option value="5" ${cordItem.insuCompany == 5 ? "selected" : ""}>한화</option>
                            <option value="6" ${cordItem.insuCompany == 6 ? "selected" : ""}>메리츠</option>
                            <option value="7" ${cordItem.insuCompany == 7? "selected" : ""}>MG</option>
                            <option value="8" ${cordItem.insuCompany == 8 ? "selected" : ""}>삼성</option>
						    <option value="9" ${cordItem.insuCompany == 9 ? "selected" : ""}>하나</option>
						    <option value="10" ${cordItem.insuCompany == 10 ? "selected" : ""}>신한</option>
                            <option value="21" ${cordItem.insuCompany == 21 ? "selected" : ""}>chubb</option>
                            <option value="22" ${cordItem.insuCompany == 22 ? "selected" : ""}>기타</option>
                            
                        </select>
                    `;

                    // 데스크탑 테이블 행
                    cordRows += `<tr>
                        <td><a href="#" class="cord-btn-link_1 " data-num="${cordItem.num}" onclick="cordOpenDetailModal(${cordItem.num})">${cordActualIndex}</a></td>
                        <td class='cord-status-cell'>${cordInsuranceOptions}</td>
                        <td>${cordItem.agent || ''}</td>
                        <td class="cord-name">${cordItem.name || ''}</td>
                        <td>${cordItem.cord || ''}</td>
                        <td>${cordItem.cord2 || ''}</td>
                        <td>${cordItem.verify || ''}</td>
                        <td>${cordItem.password || ''}</td>
                        <td>${cordItem.wdate || ''}</td>
                        <td>${cordItem.jijem || ''}</td>
                        <td>${cordItem.jijemLady || ''}</td>
                        <td>${cordItem.phone || ''}</td>
                        <td>${cordItem.fax || ''}</td>
                        <td><input class='cord-mText' type='text' value='${cordItem.gita || ''}' data-num="${cordItem.num}" onblur="cordUpdateMemo(${cordItem.num}, this.value)"></td>
                    </tr>`;

                    // 모바일 카드
                    cordMobileCards += `
                        <div class="cord-mobile-card" data-num="${cordItem.num}">
                            <div class="cord-card-header">
                                <div class="cord-card-number">${cordActualIndex}</div>
                                <div class="cord-card-name">${cordItem.name || ''}</div>
                            </div>
                            <div class="cord-card-body">
                                <div class="cord-card-row">
                                    <span class="cord-card-label">보험회사:</span>
                                    <span class="cord-card-value">
                                        <select class="cord-insurance-select-mobile" data-id="${cordItem.num}" onchange="cordUpdateInsuranceCompany(${cordItem.num}, this.value)">
                                            <option value="-1" ${cordItem.insuCompany == -1 ? "selected" : ""}>선택</option>
                                            <option value="1" ${cordItem.insuCompany == 1 ? "selected" : ""}>흥국</option>
                                            <option value="2" ${cordItem.insuCompany == 2 ? "selected" : ""}>DB</option>
                                            <option value="3" ${cordItem.insuCompany == 3 ? "selected" : ""}>KB</option>
                                            <option value="4" ${cordItem.insuCompany == 4 ? "selected" : ""}>현대</option>
                                            <option value="5" ${cordItem.insuCompany == 5 ? "selected" : ""}>한화</option>
                                            <option value="6" ${cordItem.insuCompany == 6 ? "selected" : ""}>메리츠</option>
                                            <option value="7" ${cordItem.insuCompany == 7? "selected" : ""}>MG</option>
                                            <option value="8" ${cordItem.insuCompany == 8 ? "selected" : ""}>삼성</option>
                                            <option value="9" ${cordItem.insuCompany == 9 ? "selected" : ""}>하나</option>
											<option value="10" ${cordItem.insuCompany == 10 ? "selected" : ""}>신한</option>
											<option value="21" ${cordItem.insuCompany == 21 ? "selected" : ""}>chubb</option>
											<option value="22" ${cordItem.insuCompany == 22 ? "selected" : ""}>기타</option>
                                        </select>
                                    </span>
                                </div>
                                <div class="cord-card-row">
                                    <span class="cord-card-label">대리점:</span>
                                    <span class="cord-card-value">${cordItem.agent || ''}</span>
                                </div>
                                <div class="cord-card-row">
                                    <span class="cord-card-label">코드:</span>
                                    <span class="cord-card-value">
                                        <a href="#" class="cord-btn-link_1 open-modal" data-num="${cordItem.num}" onclick="cordOpenDetailModal(${cordItem.num})">${cordItem.cord || ''}</a>
                                    </span>
                                </div>
                                <div class="cord-card-row">
                                    <span class="cord-card-label">코드2:</span>
                                    <span class="cord-card-value">${cordItem.cord2 || ''}</span>
                                </div>
                                <div class="cord-card-row">
                                    <span class="cord-card-label">사용인:</span>
                                    <span class="cord-card-value">${cordItem.name || ''}</span>
                                </div>
                                <div class="cord-card-row">
                                    <span class="cord-card-label">비밀번호:</span>
                                    <span class="cord-card-value">${cordItem.password || ''}</span>
                                </div>
                                <div class="cord-card-row">
                                    <span class="cord-card-label">인증:</span>
                                    <span class="cord-card-value">${cordItem.verify || ''}</span>
                                </div>
                                <div class="cord-card-row">
                                    <span class="cord-card-label">등록일:</span>
                                    <span class="cord-card-value">${cordItem.wdate || ''}</span>
                                </div>
                                <div class="cord-card-row">
                                    <span class="cord-card-label">지점장:</span>
                                    <span class="cord-card-value">${cordItem.jijem || ''}</span>
                                </div>
                                <div class="cord-card-row">
                                    <span class="cord-card-label">여직원:</span>
                                    <span class="cord-card-value">${cordItem.jijemLady || ''}</span>
                                </div>
                                <div class="cord-card-row">
                                    <span class="cord-card-label">지점번호:</span>
                                    <span class="cord-card-value">${cordItem.phone || ''}</span>
                                </div>
                                <div class="cord-card-row">
                                    <span class="cord-card-label">지점팩스:</span>
                                    <span class="cord-card-value">${cordItem.fax || ''}</span>
                                </div>
                                <div class="cord-card-row">
                                    <span class="cord-card-label">특이사항:</span>
                                    <span class="cord-card-value">
                                        <input class='cord-mText-mobile' type='text' value='${cordItem.gita || ''}' data-num="${cordItem.num}" onblur="cordUpdateMemo(${cordItem.num}, this.value)">
                                    </span>
                                </div>
                            </div>
                            
                        </div>
                    `;
                });
            }

            if (cordTableBody) {
                cordTableBody.innerHTML = cordRows;
            }
            if (cordMobileContainer) {
                cordMobileContainer.innerHTML = cordMobileCards;
            }

            // 페이지네이션 생성 (수정된 부분 - totalRecords 사용)
            const cordTotalRecords = cordResponseData.pagination?.totalRecords || 0;
            const cordTotalPages = Math.ceil(cordTotalRecords / cordItemsPerPage);
            
            //console.log('총 데이터 수:', cordTotalRecords, '총 페이지 수:', cordTotalPages, '현재 페이지:', cordPage);
            //console.log('페이지네이션 정보:', cordResponseData.pagination);
            
            cordRenderPagination(cordPage, cordTotalPages);
            cordRenderMobilePagination(cordPage, cordTotalPages);

        })
        .catch((cordError) => {
            console.error('데이터 로드 오류:', cordError);
            
            // 사용자에게 구체적인 오류 메시지 표시
            let cordErrorMessage = "데이터를 불러오는 중 오류가 발생했습니다.";
            
            if (cordError.message.includes('JSON 파싱 오류')) {
                cordErrorMessage = "서버 응답 형식에 문제가 있습니다. 관리자에게 문의하세요.";
            } else if (cordError.message.includes('HTTP Error')) {
                cordErrorMessage = `서버 연결 오류: ${cordError.message}`;
            } else if (cordError.message.includes('빈 응답')) {
                cordErrorMessage = "서버에서 응답을 받지 못했습니다.";
            }
            
            if (cordTableBody) {
                cordTableBody.innerHTML = `<tr><td colspan="14" style="text-align: center; color: red;">${cordErrorMessage}</td></tr>`;
            }
            if (cordMobileContainer) {
                cordMobileContainer.innerHTML = `<div class="mobile-error">${cordErrorMessage}</div>`;
            }
        })
        .finally(() => {
            // 로딩 완료 후 로딩 인디케이터 숨김
            LoadingSystem.hide('cord-search');
            isCordLoading = false; // 로딩 완료
        });
}

// 데스크탑 페이지네이션 (원래 구조 유지하면서 개선)
function cordRenderPagination(cordCurrentPage, cordTotalPages) {
    const cordPagination = document.querySelector(".cord-pagination");
    if (!cordPagination) return;
    
    // 전역 변수 업데이트
    currentCordPage = cordCurrentPage;
    
    cordPagination.innerHTML = ""; // 기존 버튼 삭제

    if (cordTotalPages <= 1) return; // 페이지가 1개 이하면 페이지네이션 숨김

    // 페이지 범위 계산
    const cordMaxPagesToShow = 5;
    let cordStartPage = Math.max(1, cordCurrentPage - Math.floor(cordMaxPagesToShow / 2));
    let cordEndPage = Math.min(cordTotalPages, cordStartPage + cordMaxPagesToShow - 1);

    // startPage 재조정 (끝에서 5개를 보여주기 위해)
    if (cordEndPage - cordStartPage + 1 < cordMaxPagesToShow) {
        cordStartPage = Math.max(1, cordEndPage - cordMaxPagesToShow + 1);
    }

    let cordPaginationHTML = "";

    // 첫 페이지로 이동 버튼 (현재 페이지가 1보다 클 때만)
    if (cordCurrentPage > 1) {
        cordPaginationHTML += `<a href="#" class="cord-page-link" data-page="1" title="첫 페이지">처음</a>`;
    }

    // 이전 버튼
    if (cordCurrentPage > 1) {
        cordPaginationHTML += `<a href="#" class="cord-page-link" data-page="${cordCurrentPage - 1}" title="이전 페이지">이전</a>`;
    } else {
        cordPaginationHTML += `<span class="cord-disabled">이전</span>`;
    }

    // 숫자 페이지 버튼들
    for (let i = cordStartPage; i <= cordEndPage; i++) {
        if (i === cordCurrentPage) {
            cordPaginationHTML += `<a href="#" class="cord-page-link active" data-page="${i}">${i}</a>`;
        } else {
            cordPaginationHTML += `<a href="#" class="cord-page-link" data-page="${i}">${i}</a>`;
        }
    }

    // 다음 버튼
    if (cordCurrentPage < cordTotalPages) {
        cordPaginationHTML += `<a href="#" class="cord-page-link" data-page="${cordCurrentPage + 1}" title="다음 페이지">다음</a>`;
    } else {
        cordPaginationHTML += `<span class="cord-disabled">다음</span>`;
    }

    // 마지막 페이지로 이동 버튼 (현재 페이지가 마지막 페이지보다 작을 때만)
    if (cordCurrentPage < cordTotalPages) {
        cordPaginationHTML += `<a href="#" class="cord-page-link" data-page="${cordTotalPages}" title="마지막 페이지">마지막</a>`;
    }

    cordPagination.innerHTML = cordPaginationHTML;

    // 이벤트 리스너 추가 (새로운 이벤트 리스너만 추가)
    cordPagination.querySelectorAll(".cord-page-link:not(.active)").forEach(cordLink => {
        cordLink.addEventListener("click", function (e) {
            e.preventDefault();
            const cordTargetPage = parseInt(this.dataset.page);
            if (cordTargetPage && !isNaN(cordTargetPage) && cordTargetPage !== cordCurrentPage) {
                console.log(`데스크탑 페이지 이동: ${cordCurrentPage} → ${cordTargetPage}`);
                cordLoadTable(cordTargetPage, currentCordSearchKeyword, currentCordSearchMode);
            }
        });
    });
}

// 모바일 페이지네이션 (원래 구조 유지하면서 개선)
function cordRenderMobilePagination(cordCurrentPage, cordTotalPages) {
    const cordMobilePagination = document.querySelector("#cord-mobile-pagination");
    if (!cordMobilePagination) return;
    
    cordMobilePagination.innerHTML = ""; // 기존 버튼 삭제

    if (cordTotalPages <= 1) return; // 페이지가 1개 이하면 페이지네이션 숨김

    let cordMobileHTML = "";

    // 처음 페이지 버튼 (현재 페이지가 1보다 클 때만)
    if (cordCurrentPage > 1) {
        cordMobileHTML += `<li><a href="#" class="cord-mobile-page-link" data-page="1" title="첫 페이지">≪</a></li>`;
    }

    // 이전 버튼
    if (cordCurrentPage > 1) {
        cordMobileHTML += `<li><a href="#" class="cord-mobile-page-link" data-page="${cordCurrentPage - 1}" title="이전 페이지">‹</a></li>`;
    }

    // 현재 페이지 정보 표시
    cordMobileHTML += `<li class="cord-mobile-page-info">${cordCurrentPage} / ${cordTotalPages}</li>`;

    // 다음 버튼
    if (cordCurrentPage < cordTotalPages) {
        cordMobileHTML += `<li><a href="#" class="cord-mobile-page-link" data-page="${cordCurrentPage + 1}" title="다음 페이지">›</a></li>`;
    }

    // 마지막 페이지 버튼 (현재 페이지가 마지막 페이지보다 작을 때만)
    if (cordCurrentPage < cordTotalPages) {
        cordMobileHTML += `<li><a href="#" class="cord-mobile-page-link" data-page="${cordTotalPages}" title="마지막 페이지">≫</a></li>`;
    }

    cordMobilePagination.innerHTML = cordMobileHTML;

    // 모바일 페이지 이동 이벤트 추가
    cordMobilePagination.querySelectorAll(".cord-mobile-page-link").forEach(cordLink => {
        cordLink.addEventListener("click", function (e) {
            e.preventDefault();
            const cordTargetPage = parseInt(this.dataset.page);
            if (cordTargetPage && !isNaN(cordTargetPage) && cordTargetPage !== cordCurrentPage) {
                console.log(`모바일 페이지 이동: ${cordCurrentPage} → ${cordTargetPage}`);
                cordLoadTable(cordTargetPage, currentCordSearchKeyword, currentCordSearchMode);
            }
        });
    });
}

// 검색 함수 수정 (모바일/데스크탑 동기화 고려 + 로딩 적용)
function cordSearchList() {
    const cordDesktopSearchType = document.getElementById('cord-searchType');
    const cordMobileSearchType = document.getElementById('cord-searchType-mobile');
    const cordDesktopSearchKeyword = document.getElementById('cord-searchKeyword');
    const cordMobileSearchKeyword = document.getElementById('cord-searchKeyword-mobile');
    
    // 현재 활성화된 검색 옵션 가져오기
    const cordSearchType = cordDesktopSearchType ? cordDesktopSearchType.value : (cordMobileSearchType ? cordMobileSearchType.value : '1');
    const cordSearchKeyword = cordDesktopSearchKeyword ? cordDesktopSearchKeyword.value : (cordMobileSearchKeyword ? cordMobileSearchKeyword.value : '');
    
    // 검색어가 비어있으면 알림
    if (!cordSearchKeyword || cordSearchKeyword.trim() === '') {
        alert('검색어를 입력해주세요.');
        return;
    }
    
    currentCordPage = 1; // 검색 시 첫 페이지로 이동
    cordLoadTable(1, cordSearchKeyword.trim(), parseInt(cordSearchType));
}

// 검색 초기화 함수 (전체 목록 보기)
function cordResetSearch() {
    // 검색 필드 초기화
    const cordDesktopSearchKeyword = document.getElementById('cord-searchKeyword');
    const cordMobileSearchKeyword = document.getElementById('cord-searchKeyword-mobile');
    const cordDesktopSearchType = document.getElementById('cord-searchType');
    const cordMobileSearchType = document.getElementById('cord-searchType-mobile');
    
    if (cordDesktopSearchKeyword) cordDesktopSearchKeyword.value = '';
    if (cordMobileSearchKeyword) cordMobileSearchKeyword.value = '';
    if (cordDesktopSearchType) cordDesktopSearchType.value = '1';
    if (cordMobileSearchType) cordMobileSearchType.value = '1';
    
    // 전체 목록 로드
    currentCordPage = 1;
    currentCordSearchKeyword = '';
    currentCordSearchMode = 1;
    cordLoadTable(1, '', 1);
}

// 보험회사 업데이트 함수
function cordUpdateInsuranceCompany(cordNum, cordValue) {
    console.log(`보험회사 업데이트: ID=${cordNum}, 값=${cordValue}`);
    
    // 업데이트 로딩 표시
    if (!LoadingSystem.instances['cord-update']) {
        LoadingSystem.create('cord-update', {
            text: '보험회사 정보를 업데이트 중...',
            autoShow: true
        });
    } else {
        LoadingSystem.show('cord-update');
    }
    
    // 실제 업데이트 로직을 여기에 구현
    // fetch로 서버에 업데이트 요청 보내기
    fetch('../simg/api/cord/update_insurance.php', {
        method: 'POST',
        headers: {
            'Content-Type': 'application/json',
        },
        body: JSON.stringify({
            num: cordNum,
            insuCompany: cordValue
        })
    })
    .then(response => response.json())
    .then(data => {
        if (data.success) {
            console.log('보험회사 업데이트 성공');
        } else {
            console.error('보험회사 업데이트 실패:', data.message);
            alert('업데이트에 실패했습니다: ' + data.message);
        }
    })
    .catch(error => {
        console.error('보험회사 업데이트 오류:', error);
        alert('업데이트 중 오류가 발생했습니다.');
    })
    .finally(() => {
        LoadingSystem.hide('cord-update');
    });
}

// 메모 업데이트 함수
function cordUpdateMemo(cordNum, cordValue) {
    console.log(`메모 업데이트: ID=${cordNum}, 값=${cordValue}`);
    
    // 업데이트 로딩 표시
    if (!LoadingSystem.instances['cord-memo-update']) {
        LoadingSystem.create('cord-memo-update', {
            text: '특이사항을 업데이트 중...',
            autoShow: true
        });
    } else {
        LoadingSystem.show('cord-memo-update');
    }
    
    // 실제 업데이트 로직을 여기에 구현
    // fetch로 서버에 업데이트 요청 보내기
    fetch('../simg/api/cord/update_memo.php', {
        method: 'POST',
        headers: {
            'Content-Type': 'application/json',
        },
        body: JSON.stringify({
            num: cordNum,
            gita: cordValue
        })
    })
    .then(response => response.json())
    .then(data => {
        if (data.success) {
            console.log('메모 업데이트 성공');
        } else {
            console.error('메모 업데이트 실패:', data.message);
            alert('업데이트에 실패했습니다: ' + data.message);
        }
    })
    .catch(error => {
        console.error('메모 업데이트 오류:', error);
        alert('업데이트 중 오류가 발생했습니다.');
    })
    .finally(() => {
        LoadingSystem.hide('cord-memo-update');
    });
}

// 상세보기 모달 열기 함수 (신규/수정 통합)
function cordOpenDetailModal(cordNum) {
    const isNewRecord = cordNum === null || cordNum === undefined;
    console.log(isNewRecord ? '신규 등록 모달 열기' : `상세보기 모달 열기: ID=${cordNum}`);
    
    if (isNewRecord) {
        // 신규 등록의 경우 빈 데이터로 모달 표시
        const emptyData = {
            num: '',
            insuCompany: '-1',
            agent: '',
            name: '',
            cord: '',
            cord2: '',
            password: '',
            verify: '',
            wdate: new Date().toISOString().split('T')[0], // 오늘 날짜로 기본 설정
            jijem: '',
            jijemLady: '',
            phone: '',
            fax: '',
            gita: ''
        };
        cordShowDetailModal(emptyData, cordNum);
    } else {
        // 기존 데이터 수정의 경우 API 호출
        // 상세 정보 로딩 표시
        if (!LoadingSystem.instances['cord-detail']) {
            LoadingSystem.create('cord-detail', {
                text: '상세 정보를 불러오는 중...',
                autoShow: true
            });
        } else {
            LoadingSystem.show('cord-detail');
        }
        
        // 상세정보 API 호출
        fetch(`../simg/api/cord/detail.php?num=${cordNum}`)
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                // 상세보기 모달 표시
                cordShowDetailModal(data.data, cordNum);
                console.log('상세정보:', data.data);
            } else {
                alert('상세정보를 불러올 수 없습니다: ' + data.message);
            }
        })
        .catch(error => {
            console.error('상세정보 로드 오류:', error);
            alert('상세정보를 불러오는 중 오류가 발생했습니다.');
        })
        .finally(() => {
            LoadingSystem.hide('cord-detail');
        });
    }
}

// 상세보기 모달 표시 함수
function cordShowDetailModal(detailData, cordNum) {
    const modal = document.getElementById('hollinwon-modal');
    const modalHeader = document.getElementById('hollinwon_daeriCompany');
    const modalBody = document.getElementById('m_hollinwon');
    
    if (!modal || !modalBody) {
        console.error('모달 요소를 찾을 수 없습니다.');
        return;
    }
    
    const isNewRecord = cordNum === null || cordNum === undefined;
    
    // 보험회사 이름 변환 함수
    function getInsuranceCompanyName(companyCode) {
        const companies = {
            '-1': '선택',
            '1': '흥국',
            '2': 'DB',
            '3': 'KB',
            '4': '현대',
            '5': '한화',
            '6': '메리츠',
            '7': 'MG',
            '8': '삼성',
            '9': '하나',
            '10': '신한',
            '21': 'chubb',
            '22': '기타'
        };
        return companies[companyCode] || '알 수 없음';
    }
    
    // 모달 헤더 설정
    if (modalHeader) {
        if (isNewRecord) {
            modalHeader.textContent = '신규 등록';
        } else {
            const companyName = getInsuranceCompanyName(detailData.insuCompany);
            modalHeader.textContent = `${companyName} - ${detailData.agent || '대리점 정보 없음'}`;
        }
    }
    
    // 보험회사 옵션 생성
    const insuranceOptions = `
        <option value="-1" ${detailData.insuCompany == -1 ? 'selected' : ''}>선택</option>
        <option value="1" ${detailData.insuCompany == 1 ? 'selected' : ''}>흥국</option>
        <option value="2" ${detailData.insuCompany == 2 ? 'selected' : ''}>DB</option>
        <option value="3" ${detailData.insuCompany == 3 ? 'selected' : ''}>KB</option>
        <option value="4" ${detailData.insuCompany == 4 ? 'selected' : ''}>현대</option>
        <option value="5" ${detailData.insuCompany == 5 ? 'selected' : ''}>한화</option>
        <option value="6" ${detailData.insuCompany == 6 ? 'selected' : ''}>메리츠</option>
        <option value="7" ${detailData.insuCompany == 7 ? 'selected' : ''}>MG</option>
        <option value="8" ${detailData.insuCompany == 8 ? 'selected' : ''}>삼성</option>
        <option value="9" ${detailData.insuCompany == 9 ? 'selected' : ''}>하나</option>
        <option value="10" ${detailData.insuCompany == 10 ? 'selected' : ''}>신한</option>
        <option value="21" ${detailData.insuCompany == 21 ? 'selected' : ''}>chubb</option>
        <option value="22" ${detailData.insuCompany == 22 ? 'selected' : ''}>기타</option>
    `;
    
    // 모달 내용 구성 (2행 2열 레이아웃)
    const modalContent = `
        <div class="cord-detail-form">
            <!-- 2행 2열 그리드 섹션 -->
            <div class="cord-detail-grid">
                <!-- 첫 번째 행 -->
                <div class="cord-detail-grid-row">
                    <div class="cord-detail-grid-item">
                        <label class="cord-detail-label">번호:</label>
                        <input type="text" class="cord-detail-input" id="modal-num" value="${detailData.num || ''}" ${isNewRecord ? '' : 'readonly'} data-field="num">
                    </div>
                    <div class="cord-detail-grid-item">
                        <label class="cord-detail-label">보험회사:</label>
                        <select class="cord-detail-select" id="modal-insuCompany" data-field="insuCompany">
                            ${insuranceOptions}
                        </select>
                    </div>
                </div>
                
                <!-- 두 번째 행 -->
                <div class="cord-detail-grid-row">
                    <div class="cord-detail-grid-item">
                        <label class="cord-detail-label">대리점명:</label>
                        <input type="text" class="cord-detail-input" id="modal-agent" value="${detailData.agent || ''}" data-field="agent">
                    </div>
                    <div class="cord-detail-grid-item">
                        <label class="cord-detail-label">사용인:</label>
                        <input type="text" class="cord-detail-input" id="modal-name" value="${detailData.name || ''}" data-field="name">
                    </div>
                </div>
                
                <!-- 세 번째 행 -->
                <div class="cord-detail-grid-row">
                    <div class="cord-detail-grid-item">
                        <label class="cord-detail-label">대리점코드:</label>
                        <input type="text" class="cord-detail-input" id="modal-cord" value="${detailData.cord || ''}" data-field="cord">
                    </div>
                    <div class="cord-detail-grid-item">
                        <label class="cord-detail-label">사용인코드:</label>
                        <input type="text" class="cord-detail-input" id="modal-cord2" value="${detailData.cord2 || ''}" data-field="cord2">
                    </div>
                </div>
                
                <!-- 네 번째 행 -->
                <div class="cord-detail-grid-row">
                    <div class="cord-detail-grid-item">
                        <label class="cord-detail-label">비밀번호:</label>
                        <input type="text" class="cord-detail-input" id="modal-password" value="${detailData.password || ''}" data-field="password">
                    </div>
                    <div class="cord-detail-grid-item">
                        <label class="cord-detail-label">인증:</label>
                        <input type="text" class="cord-detail-input" id="modal-verify" value="${detailData.verify || ''}" data-field="verify">
                    </div>
                </div>
                
                <!-- 다섯 번째 행 -->
                <div class="cord-detail-grid-row">
                    <div class="cord-detail-grid-item">
                        <label class="cord-detail-label">등록일:</label>
                        <input type="date" class="cord-detail-input" id="modal-wdate" value="${detailData.wdate || ''}" data-field="wdate">
                    </div>
                    <div class="cord-detail-grid-item">
                        <label class="cord-detail-label">지점장:</label>
                        <input type="text" class="cord-detail-input" id="modal-jijem" value="${detailData.jijem || ''}" data-field="jijem">
                    </div>
                </div>
                
                <!-- 여섯 번째 행 -->
                <div class="cord-detail-grid-row">
                    <div class="cord-detail-grid-item">
                        <label class="cord-detail-label">여직원:</label>
                        <input type="text" class="cord-detail-input" id="modal-jijemLady" value="${detailData.jijemLady || ''}" data-field="jijemLady">
                    </div>
                    <div class="cord-detail-grid-item">
                        <label class="cord-detail-label">지점번호:</label>
                        <input type="text" class="cord-detail-input" id="modal-phone" value="${detailData.phone || ''}" data-field="phone">
                    </div>
                </div>
                
                <!-- 일곱 번째 행 -->
                <div class="cord-detail-grid-row">
                    <div class="cord-detail-grid-item">
                        <label class="cord-detail-label">지점팩스:</label>
                        <input type="text" class="cord-detail-input" id="modal-fax" value="${detailData.fax || ''}" data-field="fax">
                    </div>
                    <div class="cord-detail-grid-item">
                        <!-- 빈 공간 -->
                    </div>
                </div>
            </div>
            
            <!-- 특이사항 전체 행 -->
            <div class="cord-detail-full-row">
                <label class="cord-detail-label">특이사항:</label>
                <textarea class="cord-detail-textarea" id="modal-gita" data-field="gita" rows="3">${detailData.gita || ''}</textarea>
            </div>
            
            <div class="cord-detail-actions">
                <button type="button" class="cord-detail-save-btn" onclick="cordSaveDetailChanges(${isNewRecord ? 'null' : cordNum})">
                    ${isNewRecord ? '등록' : '저장'}
                </button>
                
            </div>
        </div>
    `;
    
    // 모달 내용 설정
    modalBody.innerHTML = modalContent;
    
    // 모달 표시
    modal.style.display = 'block';
    
    // 모달 닫기 이벤트 설정
    const closeBtn = modal.querySelector('.close-hollinwonModal');
    if (closeBtn) {
        closeBtn.onclick = cordCloseDetailModal;
    }
    
    // 모달 외부 클릭 시 닫기
    modal.onclick = function(event) {
        if (event.target === modal) {
            cordCloseDetailModal();
        }
    };
}

// 상세정보 저장 함수 (신규/수정 통합)
function cordSaveDetailChanges(cordNum) {
    const isNewRecord = cordNum === null || cordNum === undefined;
    
    // 폼 데이터 수집
    const formData = {
        insuCompany: document.getElementById('modal-insuCompany').value,
        agent: document.getElementById('modal-agent').value,
        name: document.getElementById('modal-name').value,
        cord: document.getElementById('modal-cord').value,
        cord2: document.getElementById('modal-cord2').value,
        password: document.getElementById('modal-password').value,
        verify: document.getElementById('modal-verify').value,
        wdate: document.getElementById('modal-wdate').value,
        jijem: document.getElementById('modal-jijem').value,
        jijemLady: document.getElementById('modal-jijemLady').value,
        phone: document.getElementById('modal-phone').value,
        fax: document.getElementById('modal-fax').value,
        gita: document.getElementById('modal-gita').value
    };
    
    // 필수 항목 검증
    if (!formData.agent.trim()) {
        alert('대리점명을 입력해주세요.');
        document.getElementById('modal-agent').focus();
        return;
    }
    
    if (formData.insuCompany === '-1') {
        alert('보험회사를 선택해주세요.');
        document.getElementById('modal-insuCompany').focus();
        return;
    }
    
    // 로딩 표시
    if (!LoadingSystem.instances['cord-save']) {
        LoadingSystem.create('cord-save', {
            text: isNewRecord ? '신규 등록 중...' : '저장 중...',
            autoShow: true
        });
    } else {
        LoadingSystem.show('cord-save');
    }
    
    // API 엔드포인트 결정
    const apiUrl = isNewRecord ? '../simg/api/cord/create.php' : '../simg/api/cord/update.php';
    
    // 신규가 아닌 경우 ID 추가
    if (!isNewRecord) {
        formData.num = cordNum;
    }
    
    // API 호출
    fetch(apiUrl, {
        method: 'POST',
        headers: {
            'Content-Type': 'application/json',
        },
        body: JSON.stringify(formData)
    })
    .then(response => response.json())
    .then(data => {
        if (data.success) {
            alert(isNewRecord ? '신규 등록이 완료되었습니다.' : '저장이 완료되었습니다.');
            cordCloseDetailModal();
            
            // 목록 새로고침 (목록 새로고침 함수가 있다면)
            if (typeof cordRefreshList === 'function') {
                cordRefreshList();
            }
        } else {
            alert((isNewRecord ? '등록' : '저장') + ' 실패: ' + data.message);
        }
    })
    .catch(error => {
        console.error('저장 오류:', error);
        alert((isNewRecord ? '등록' : '저장') + ' 중 오류가 발생했습니다.');
    })
    .finally(() => {
        LoadingSystem.hide('cord-save');
    });
}



// 모달 닫기 함수
function cordCloseDetailModal() {
    const modal = document.getElementById('hollinwon-modal');
    if (modal) {
        modal.style.display = 'none';
    }
}

// 사용 예시:
// 기존 레코드 수정: cordOpenDetailModal(123);
// 신규 등록: cordOpenDetailModal(); 또는 cordOpenDetailModal(null);

// 상세보기 모달 닫기 함수


// 상세정보 저장 함수
function cordSaveDetailChanges(cordNum) {
    console.log(`상세정보 저장: ID=${cordNum}`);
    
    // 등록인지 수정인지 판단
    const isNewRecord = !cordNum;
    
    // 폼 데이터 수집
    const formData = {
        num: cordNum,
        insuCompany: document.getElementById('modal-insuCompany')?.value || '',
        agent: document.getElementById('modal-agent')?.value || '',
        name: document.getElementById('modal-name')?.value || '',
        cord: document.getElementById('modal-cord')?.value || '',
        cord2: document.getElementById('modal-cord2')?.value || '',
        password: document.getElementById('modal-password')?.value || '',
        verify: document.getElementById('modal-verify')?.value || '',
        wdate: document.getElementById('modal-wdate')?.value || '',
        jijem: document.getElementById('modal-jijem')?.value || '',
        jijemLady: document.getElementById('modal-jijemLady')?.value || '',
        phone: document.getElementById('modal-phone')?.value || '',
        fax: document.getElementById('modal-fax')?.value || '',
        gita: document.getElementById('modal-gita')?.value || ''
    };
    
    console.log('저장할 데이터:', formData);
    
    // 저장 로딩 표시
    if (!LoadingSystem.instances['cord-save']) {
        LoadingSystem.create('cord-save', {
            text: '상세정보를 저장하는 중...',
            autoShow: true
        });
    } else {
        LoadingSystem.show('cord-save');
    }
    
    // 서버에 저장 요청
    fetch('../simg/api/cord/update_detail.php', {
        method: 'POST',
        headers: {
            'Content-Type': 'application/json',
        },
        body: JSON.stringify(formData)
    })
    .then(response => response.json())
    .then(data => {
        if (data.success) {
            
            
            // 등록인 경우만 모달 닫기
            if (isNewRecord) {
                cordCloseDetailModal();
				alert('상세정보가 성공적으로 저장되었습니다. 조회하여 확인하세요');
            }else{
				alert('상세정보가 성공적으로  업데이트 되었습니다. ');
			}
            // 수정인 경우는 모달을 열어둠
            
            // 현재 테이블 새로고침 (검색 조건 유지)
            cordLoadTable(currentCordPage, currentCordSearchKeyword, currentCordSearchMode);
        } else {
            alert('저장에 실패했습니다: ' + (data.message || '알 수 없는 오류'));
        }
    })
    .catch(error => {
        console.error('저장 오류:', error);
        alert('저장 중 오류가 발생했습니다: ' + error.message);
    })
    .finally(() => {
        LoadingSystem.hide('cord-save');
    });
}

// ESC 키로 모달 닫기
document.addEventListener('keydown', function(event) {
    if (event.key === 'Escape') {
        const modal = document.getElementById('hollinwon-modal');
        if (modal && modal.style.display === 'block') {
            cordCloseDetailModal();
        }
    }
});

// CSS 스타일 추가 (2행 2열 그리드 레이아웃)
const cordDetailModalStyles = `
<style>
.cord-detail-form {
    padding: 20px;
    max-width: 700px;
    margin: 0 auto;
}

/* 2행 2열 그리드 컨테이너 */
.cord-detail-grid {
    margin-bottom: 20px;
}

.cord-detail-grid-row {
    display: flex;
    gap: 20px;
    margin-bottom: 15px;
}

.cord-detail-grid-item {
    flex: 1;
    display: flex;
    align-items: center;
    gap: 10px;
}

.cord-detail-label {
    font-weight: bold;
    color: #333;
    font-size: 14px;
    white-space: nowrap;
    min-width: 80px;
    flex-shrink: 0;
}

/* 특이사항 전체 행 */
.cord-detail-full-row {
    display: flex;
    align-items: flex-start;
    gap: 10px;
    margin-bottom: 20px;
}

.cord-detail-full-row .cord-detail-label {
    min-width: 80px;
    margin-top: 8px;
}

.cord-detail-input,
.cord-detail-select,
.cord-detail-textarea {
    padding: 8px 12px;
    border: 1px solid #ddd;
    border-radius: 4px;
    font-size: 14px;
    flex: 1;
    box-sizing: border-box;
}

.cord-detail-input:focus,
.cord-detail-select:focus,
.cord-detail-textarea:focus {
    outline: none;
    border-color: #4CAF50;
    box-shadow: 0 0 5px rgba(76, 175, 80, 0.3);
}

.cord-detail-textarea {
    resize: vertical;
    min-height: 80px;
    font-family: inherit;
}

.cord-detail-actions {
    display: flex;
    justify-content: center;
    gap: 15px;
    margin-top: 30px;
    padding-top: 20px;
    border-top: 1px solid #eee;
}

.cord-detail-save-btn,
.cord-detail-cancel-btn {
    padding: 12px 25px;
    border: none;
    border-radius: 4px;
    cursor: pointer;
    font-size: 14px;
    font-weight: bold;
    min-width: 100px;
    transition: background-color 0.3s ease;
}

.cord-detail-save-btn {
    background-color: #4CAF50;
    color: white;
}

.cord-detail-save-btn:hover {
    background-color: #45a049;
}

.cord-detail-cancel-btn {
    background-color: #f44336;
    color: white;
}

.cord-detail-cancel-btn:hover {
    background-color: #da190b;
}

/* 읽기 전용 필드 스타일 */
.cord-detail-input[readonly] {
    background-color: #f5f5f5;
    color: #666;
}

/* 모바일 반응형 */
@media (max-width: 768px) {
    .cord-detail-form {
        padding: 15px;
        max-width: 100%;
    }
    
    .cord-detail-grid-row {
        flex-direction: column;
        gap: 15px;
        margin-bottom: 20px;
    }
    
    .cord-detail-grid-item {
        flex-direction: column;
        align-items: stretch;
        margin-bottom: 15px;
        gap: 5px;
    }
    
    .cord-detail-full-row {
        flex-direction: column;
        align-items: stretch;
        gap: 5px;
    }
    
    .cord-detail-full-row .cord-detail-label {
        margin-top: 0;
        min-width: auto;
    }
    
    .cord-detail-actions {
        flex-direction: column;
        gap: 10px;
    }
    
    .cord-detail-save-btn,
    .cord-detail-cancel-btn {
        width: 100%;
        padding: 15px;
    }
}

/* 작은 모바일 화면 */
@media (max-width: 480px) {
    .cord-detail-form {
        padding: 10px;
    }
    
    .cord-detail-label {
        font-size: 13px;
    }
    
    .cord-detail-input,
    .cord-detail-select,
    .cord-detail-textarea {
        font-size: 13px;
        padding: 10px;
    }
}
</style>
`;

// 스타일을 head에 추가 (한 번만 실행)
if (!document.getElementById('cord-detail-modal-styles')) {
    const styleElement = document.createElement('div');
    styleElement.id = 'cord-detail-modal-styles';
    styleElement.innerHTML = cordDetailModalStyles;
    document.head.appendChild(styleElement);
}

