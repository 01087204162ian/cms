/** 현장실습보험  클레임관련 js  파일 **/

function fieldClaim(){
	 //document.getElementById('page-content');
	 const pageContent = document.getElementById('page-content');

	 console.log('hi');
		pageContent.innerHTML = '<tr><td colspan="14" class="loading">데이터 로드 중...</td></tr>';
        pageContent.innerHTML = "";
	 const claimContents= `<div class="c-list-container">
    <!-- 검색 영역 -->
    <div class="c-list-header">
        <div class="c-left-area">
            <div class="c-search-area">
                <select id="c-searchType"  onChange='c_searchTypeChange()'>
                    <option value="1">증권번호</option>
                    <option value="2">사고접수번호</option>
                    <option value="3">학생</option>
				    <option value="4">계약자</option>
                </select>
                <input type="text" id="c-searchKeyword" placeholder="증권번호를 입력하세요" onkeypress="if(event.key === 'Enter') c_searchList()">
                <button class="c-search-button" onclick="c_searchList()">검색</button>
            </div>
        </div>
        <div class="right-area">
            <button class="c-stats-button" onclick="c_showStatsModal()">통계</button>
        </div>
    </div>

    <!-- 리스트 영역 -->
    <div class="c-list-content">
        <div class="c-data-table-container">
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
                    <!-- 데이터가 여기에 동적으로 로드됨 -->
                </tbody>
            </table>
        </div>
    </div>

    
    <!-- 페이지네이션 -->
	<div class="c-pagination"></div>
    </div>`;
	 

	 pageContent.innerHTML= claimContents;

	loadTable2();
}


function loadTable2(page = 1, searchSchool = '', searchMode = 1) {
		
		const itemsPerPage = 15;
        const tableBody = document.querySelector("#c-applicationList");
        const pagination = document.querySelector(".c-pagination");

        // 로딩 표시
        tableBody.innerHTML = '<tr><td colspan="14" class="loading">데이터 로드 중...</td></tr>';
        pagination.innerHTML = "";

        fetch(`https://lincinsu.kr/2025/api/claim/fetch_claim.php?page=${page}&limit=${itemsPerPage}&search_school=${searchSchool}&search_mode=${searchMode}`)
            .then(response => response.json())
            .then(response => {
                let rows = "";

                // 데이터 존재 여부 확인
                if (!response.data || response.data.length === 0) {
                    rows = `<tr><td colspan="13" style="text-align: center;">검색 결과가 없습니다.</td></tr>`;
                } else {
                    response.data.forEach((item, index) => {
                        const formattedClaimAmout = item.claimAmout && !isNaN(item.claimAmout) ? parseFloat(item.claimAmout).toLocaleString("en-US") : "";
						const formattedAccidentDescription = item.accidentDescription ? item.accidentDescription.substring(0, 30) : "";

                     

                       const statusOptions = `
						<select class="c-status-select" data-id="${item.num}" >
							<option value="1" ${item.ch == 1 ? "selected" : ""}>접수</option>
							<option value="2" ${item.ch == 2 ? "selected" : ""}>미결</option>
							<option value="3" ${item.ch == 3 ? "selected" : ""}>종결</option>
							<option value="4" ${item.ch == 4 ? "selected" : ""}>면책</option>
							<option value="5" ${item.ch == 5 ? "selected" : ""}>취소</option>
						</select>
					`;

                        rows += `<tr>
                            <td><a href="#" class="c-btn-link_1 c_1_open-claim-modal" data-num="${item.num}">${(page - 1) * itemsPerPage + index + 1}</a></td>
                            <td>${item.wdate}</td>
                            <td>${item.school1}</td>
                            <td>${item.certi}</td>
                            <td>${item.claimNumber}</td>
                            <td>${statusOptions}</td>
                            <td>${item.wdate_2}</td>
                            <td class="c-preiminum">${formattedClaimAmout}</td>
                            <td>${item.student}</td>
                            <td>${item.wdate_3}</td>
                            <td>${formattedAccidentDescription}</td>
                            <td><a href="#" class="c-btn-link_1 upload-modal" data-num="${item.num}">업로드</a></td>
                            <td><input class='c-mText' type='text' value='${item.memo}' data-num="${item.num}"></td>
                            <td>${item.manager}</td>
                        </tr>`;
                    });
                }

                tableBody.innerHTML = rows;

                // 페이지네이션 생성
                renderPagination2(page, Math.ceil(response.total / itemsPerPage));
            })
            .catch(() => {
                alert("데이터를 불러오는 중 오류가 발생했습니다.");
            });
    }

 function renderPagination2(currentPage, totalPages) {
    const pagination = document.querySelector(".c-pagination");
    pagination.innerHTML = ""; // 기존 버튼 삭제

    // 이전 버튼 추가
    if (currentPage > 1) {
        pagination.innerHTML += `<a href="#" class="c-page-link" data-page="${currentPage - 1}">이전</a>`;
    } else {
        pagination.innerHTML += `<a href="#" class="c-disabled">이전</a>`;
    }

    // 숫자 버튼 추가 (최대 5개 표시)
    const maxPagesToShow = 5;
    let startPage = Math.max(1, currentPage - Math.floor(maxPagesToShow / 2));
    let endPage = Math.min(totalPages, startPage + maxPagesToShow - 1);

    for (let i = startPage; i <= endPage; i++) {
        pagination.innerHTML += `<a href="#" class="c-page-link ${i === currentPage ? "active" : ""}" data-page="${i}">${i}</a>`;
    }

    // 다음 버튼 추가
    if (currentPage < totalPages) {
        pagination.innerHTML += `<a href="#" class="c-page-link" data-page="${currentPage + 1}">다음</a>`;
    } else {
        pagination.innerHTML += `<a href="#" class="c-disabled">다음</a>`;
    }

    // 페이지 이동 이벤트 추가
    document.querySelectorAll(".c-page-link").forEach(link => {
        link.addEventListener("click", function (e) {
            e.preventDefault();
            loadTable2(parseInt(this.dataset.page));
        });
    });
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
	fetch(`https://lincinsu.kr/2025/api/claim/get_claim_summary.php?year=${selectedYear}`)
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

	
    fetch(`https://lincinsu.kr/2025/api/claim/get_yearly_summary.php?year=${selectedYear}`)
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

    
	fetch(`https://lincinsu.kr/2025/api/claim/get_contractor_summary.php?year=${selectedYear}`)
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
    fetch(`https://lincinsu.kr/2025/api/claim/claim_update_status.php`, {
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

        fetch(`https://lincinsu.kr/2025/api/claim/update_memo.php`, {
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