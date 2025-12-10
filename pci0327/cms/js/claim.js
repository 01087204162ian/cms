function initializeClaimScripts() {
    console.log("📌 claim.js 초기화 시작");
    
    const itemsPerPage = 15;
    let searchSchool = ''; 
    let searchMode = 1;

    // 테이블 로드 함수
    function loadTable(page = 1, searchSchool = '', searchMode = 1) {
        const tableBody = document.querySelector("#questionnaire-table tbody");
        const pagination = document.querySelector(".pagination");

        // 로딩 표시
        tableBody.innerHTML = '<tr><td colspan="15" class="loading">데이터 로드 중...</td></tr>';
        pagination.innerHTML = "";

        fetch(`https://lincinsu.kr/2025/api/claim/fetch_claim.php?page=${page}&limit=${itemsPerPage}&search_school=${searchSchool}&search_mode=${searchMode}`)
            .then(response => response.json())
            .then(response => {
                let rows = "";

                if (!response.data || response.data.length === 0) {
                    rows = `<tr><td colspan="15" style="text-align: center;">검색 결과가 없습니다.</td></tr>`;
                } else {
                    response.data.forEach((item, index) => {
                        const formattedClaimAmout = item.claimAmout ? parseInt(item.claimAmout).toLocaleString() : "";
                        const formattedAccidentDescription = item.accidentDescription ? item.accidentDescription.substring(0, 30) : "";

                        const statusOptions = `
                            <select class="status-select" data-id="${item.num}">
                                <option value="1" ${item.ch == 1 ? "selected" : ""}>접수</option>
                                <option value="2" ${item.ch == 2 ? "selected" : ""}>미결</option>
                                <option value="3" ${item.ch == 3 ? "selected" : ""}>종결</option>
                                <option value="4" ${item.ch == 4 ? "selected" : ""}>면책</option>
                                <option value="5" ${item.ch == 5 ? "selected" : ""}>취소</option>
                            </select>
                        `;

                        rows += `
                            <tr>
                                <td><a href="#" class="btn-link_1 open-claim-modal" data-num="${item.num}">${(page - 1) * itemsPerPage + index + 1}</a></td>
                                <td>${item.wdate}</td>
                                <td>${item.school1}</td>
                                <td>${item.certi}</td>
                                <td>${item.claimNumber}</td>
                                <td>${statusOptions}</td>
                                <td>${item.wdate_2}</td>
                                <td class="preiminum">${formattedClaimAmout}</td>
                                <td>${item.student}</td>
                                <td>${item.wdate_3}</td>
                                <td>${formattedAccidentDescription}</td>
                                <td><a href="#" class="btn-link_1 upload-modal" data-num="${item.num}">업로드</a></td>
                                <td></td>
                                <td><input class='mText' type='text' value='${item.memo}' data-num="${item.num}"></td>
                                <td>${item.manager}</td>
                            </tr>
                        `;
                    });
                }

                tableBody.innerHTML = rows;
                renderPagination(page, Math.ceil(response.total / itemsPerPage));

                // 이벤트 리스너 추가
                addEventListeners();
            })
            .catch(() => {
                alert("데이터를 불러오는 중 오류가 발생했습니다.");
            });
    }

    // 페이지네이션 렌더링
    function renderPagination(currentPage, totalPages) {
        const pagination = document.querySelector(".pagination");
        let html = '';

        if (currentPage > 1) {
            html += `<a href="#" class="page-link" data-page="${currentPage - 1}">이전</a>`;
        }

        const maxPages = 5;
        let startPage = Math.max(1, currentPage - Math.floor(maxPages / 2));
        let endPage = Math.min(totalPages, startPage + maxPages - 1);

        for (let i = startPage; i <= endPage; i++) {
            html += `<a href="#" class="page-link ${i === currentPage ? 'active' : ''}" data-page="${i}">${i}</a>`;
        }

        if (currentPage < totalPages) {
            html += `<a href="#" class="page-link" data-page="${currentPage + 1}">다음</a>`;
        }

        pagination.innerHTML = html;
    }

    // 이벤트 리스너 추가
    function addEventListeners() {
        // 검색 버튼 클릭
        document.getElementById("search-btn").addEventListener("click", function(e) {
            e.preventDefault();
            searchSchool = document.getElementById("search-school").value.trim();
            searchMode = parseInt(document.getElementById("cSelect").value);
            
            if (!searchSchool) {
                alert("학교명을 입력하세요");
                document.getElementById("search-school").focus();
                return;
            }
            
            loadTable(1, searchSchool, searchMode);
        });

        // 페이지네이션 클릭
        document.querySelectorAll(".page-link").forEach(link => {
            link.addEventListener("click", function(e) {
                e.preventDefault();
                loadTable(parseInt(this.dataset.page), searchSchool, searchMode);
            });
        });

        // 실적 버튼 클릭
        document.getElementById("claimPerformance").addEventListener("click", function(e) {
            e.preventDefault();
            document.getElementById("sjModal").style.display = "block";
            perFormance();
        });
    }

    // 초기 테이블 로드
    loadTable();
    
    console.log("✅ claim.js 초기화 완료");
}

// 실적 관련 함수들 추가
function perFormance() {
    console.log("📌 모달 오픈 & 데이터 요청");
    
    // 연도 선택 드롭다운 생성
    showSelectedYear();
    // 데이터 가져오기
    fetchData();
    // 모달 푸터 버튼 삽입
    insertFooterButtons();
}

function showSelectedYear() {
    const yearContainer1 = document.getElementById("yearContainer1");
    if (!yearContainer1) {
        console.warn("🚨 'yearContainer1' 요소를 찾을 수 없습니다.");
        return;
    }

    yearContainer1.innerHTML = "";
    const currentYear = new Date().getFullYear();

    const yearSelect = document.createElement("select");
    yearSelect.id = "yearSelect";
    yearSelect.onchange = fetchData;

    // 최근 5년 옵션 추가
    for (let i = currentYear; i >= currentYear - 4; i--) {
        const option = document.createElement("option");
        option.value = i;
        option.textContent = i + "년";
        yearSelect.appendChild(option);
    }

    yearContainer1.appendChild(yearSelect);
}

function fetchData() {
    const selectedYear = document.getElementById("yearSelect").value;
    
    fetch(`https://lincinsu.kr/2025/api/claim/get_claim_summary.php?year=${selectedYear}`)
        .then(response => response.json())
        .then(data => updateTable(data))
        .catch(error => console.error("데이터 로드 오류:", error));
}

function updateTable(jsonData) {
    let claimData = {};
    const selectedYear = document.getElementById("yearSelect").value;
    
    // 12개월 기본 구조 생성
    for (let i = 1; i <= 12; i++) {
        let month = `${selectedYear}-${String(i).padStart(2, '0')}`;
        claimData[month] = {
            received: 0, pending: 0, completed: 0, exempted: 0, canceled: 0,
            total: 0, claimAmount: 0, totalPremium: 0, lossRatio: 0
        };
    }

    // 데이터 처리
    if (jsonData.claims) {
        jsonData.claims.forEach(item => {
            let month = item.yearMonth;
            if (!claimData[month]) return;

            switch (parseInt(item.ch)) {
                case 1: claimData[month].received += parseInt(item.count); break;
                case 2: claimData[month].pending += parseInt(item.count); break;
                case 3:
                    claimData[month].completed += parseInt(item.count);
                    claimData[month].claimAmount += parseInt(item.total_claim_amount || 0);
                    break;
                case 4: claimData[month].exempted += parseInt(item.count); break;
                case 5: claimData[month].canceled += parseInt(item.count); break;
            }
            claimData[month].total += parseInt(item.count);
        });
    }

    // 보험료 데이터 처리
    if (jsonData.premiums) {
        jsonData.premiums.forEach(item => {
            let month = item.yearMonth;
            if (!claimData[month]) return;
            claimData[month].totalPremium += parseInt(item.total_premium || 0);
        });
    }

    // 테이블 업데이트
    updateTableContent(claimData);
}

function updateTableContent(claimData) {
    const tbody = document.querySelector("#claimTable tbody");
    tbody.innerHTML = "";
    
    let totals = {
        received: 0, pending: 0, completed: 0, exempted: 0, canceled: 0,
        total: 0, claimAmount: 0, totalPremium: 0
    };

    Object.entries(claimData).forEach(([month, data]) => {
        // 손해율 계산
        const lossRatio = data.totalPremium > 0 ? 
            ((data.claimAmount / data.totalPremium) * 100).toFixed(2) + "%" : "";

        tbody.innerHTML += `
            <tr>
                <th>${month}</th>
                <td>${data.received || ""}</td>
                <td>${data.pending || ""}</td>
                <td>${data.completed || ""}</td>
                <td>${data.exempted || ""}</td>
                <td>${data.canceled || ""}</td>
                <td>${data.total || ""}</td>
                <td>${data.claimAmount ? data.claimAmount.toLocaleString() : ""}</td>
                <td>${data.totalPremium ? data.totalPremium.toLocaleString() : ""}</td>
                <td>${lossRatio}</td>
            </tr>
        `;

        // 합계 계산
        Object.keys(totals).forEach(key => {
            totals[key] += data[key] || 0;
        });
    });

    // 합계 행 업데이트
    updateTotalRow(totals);
}

function updateTotalRow(totals) {
    const totalLossRatio = totals.totalPremium > 0 ? 
        ((totals.claimAmount / totals.totalPremium) * 100).toFixed(2) + "%" : "";

    document.getElementById("totalReceived").textContent = totals.received || "";
    document.getElementById("totalPending").textContent = totals.pending || "";
    document.getElementById("totalCompleted").textContent = totals.completed || "";
    document.getElementById("totalExempted").textContent = totals.exempted || "";
    document.getElementById("totalCanceled").textContent = totals.canceled || "";
    document.getElementById("totalAll").textContent = totals.total || "";
    document.getElementById("totalClaimAmount").textContent = totals.claimAmount ? 
        totals.claimAmount.toLocaleString() : "";
    document.getElementById("totalPremiumAmount").textContent = totals.totalPremium ? 
        totals.totalPremium.toLocaleString() : "";
    document.getElementById("totalLossRatio").textContent = totalLossRatio;
} 