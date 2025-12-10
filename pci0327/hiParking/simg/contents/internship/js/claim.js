
function initializeClaimScripts() {
	removeExistingEventListeners();
    console.log("📌 claim.js 초기화 시작");
	
    const itemsPerPage = 15;
    let searchSchool = ''; 
    let searchMode = 1;

    function loadTable(page = 1, searchSchool = '', searchMode = 1) {
        const tableBody = document.querySelector("#questionnaire-table tbody");
        const pagination = document.querySelector(".pagination");

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
						<select class="status-select" data-id="${item.num}" >
							<option value="1" ${item.ch == 1 ? "selected" : ""}>접수</option>
							<option value="2" ${item.ch == 2 ? "selected" : ""}>미결</option>
							<option value="3" ${item.ch == 3 ? "selected" : ""}>종결</option>
							<option value="4" ${item.ch == 4 ? "selected" : ""}>면책</option>
							<option value="5" ${item.ch == 5 ? "selected" : ""}>취소</option>
						</select>
					`;

                        rows += `<tr>
                            <td><a href="#" class="btn-link_1 k_1_open-claim-modal" data-num="${item.num}">${(page - 1) * itemsPerPage + index + 1}</a></td>
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
                        </tr>`;
                    });
                }

                tableBody.innerHTML = rows;

                // 페이지네이션 생성
                renderPagination(page, Math.ceil(response.total / itemsPerPage));
            })
            .catch(() => {
                alert("데이터를 불러오는 중 오류가 발생했습니다.");
            });
    }

 function renderPagination(currentPage, totalPages) {
    const pagination = document.querySelector(".pagination");
    pagination.innerHTML = ""; // 기존 버튼 삭제

    // 이전 버튼 추가
    if (currentPage > 1) {
        pagination.innerHTML += `<a href="#" class="page-link" data-page="${currentPage - 1}">이전</a>`;
    } else {
        pagination.innerHTML += `<a href="#" class="disabled">이전</a>`;
    }

    // 숫자 버튼 추가 (최대 5개 표시)
    const maxPagesToShow = 5;
    let startPage = Math.max(1, currentPage - Math.floor(maxPagesToShow / 2));
    let endPage = Math.min(totalPages, startPage + maxPagesToShow - 1);

    for (let i = startPage; i <= endPage; i++) {
        pagination.innerHTML += `<a href="#" class="page-link ${i === currentPage ? "active" : ""}" data-page="${i}">${i}</a>`;
    }

    // 다음 버튼 추가
    if (currentPage < totalPages) {
        pagination.innerHTML += `<a href="#" class="page-link" data-page="${currentPage + 1}">다음</a>`;
    } else {
        pagination.innerHTML += `<a href="#" class="disabled">다음</a>`;
    }

    // 페이지 이동 이벤트 추가
    document.querySelectorAll(".page-link").forEach(link => {
        link.addEventListener("click", function (e) {
            e.preventDefault();
            loadTable(parseInt(this.dataset.page));
        });
    });
}


    // 검색 버튼 클릭 이벤트
document.getElementById("search-btn").addEventListener("click", function (e) {
	e.preventDefault();
	searchSchool = document.getElementById("search-school").value.trim();
	const searchMode = parseInt(document.getElementById("cSelect").value, 10);

	if (!searchSchool) {
		alert("학교명을 입력하세요");
		document.getElementById("search-school").focus();
		return;
	}

	loadTable(1, searchSchool, searchMode);
});

// 검색 필드 Enter 및 blur 이벤트
document.getElementById("search-school").addEventListener("keyup", function (e) {
	if (e.key === "Enter") {
		searchSchool = this.value.trim();
		const searchMode = parseInt(document.getElementById("cSelect").value, 10);
		if (!searchSchool) {
			alert("학교명을 입력하세요");
			this.focus();
			return;
		}
		loadTable(1, searchSchool, searchMode);
	}
});

// 메모 업데이트 (blur 이벤트)
document.addEventListener("keypress", function (e) {
    if (e.target.classList.contains("mText") && e.key === "Enter") {
        e.preventDefault(); // 기본 엔터 동작 방지 (폼 제출 방지)

        const memo = e.target.value.trim();
        const num = e.target.dataset.num;

        if (!memo) {
            alert("메모를 입력해주세요.");
            return;
        }

        fetch(`https://lincinsu.kr/2025/api/question/update_memo.php`, {
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


// 초기 테이블 로드
loadTable();

//보험사 변동
document.addEventListener("change", function (e) {
    // 변경된 요소가insurance-select 클래스인지 확인
    if (e.target.classList.contains("insurance-select")) {
        const num = e.target.dataset.id;  // data-id 속성에서 num 값 가져오기
        const selectedValue = e.target.value;  // 선택된 옵션 값 가져오기
        
        // 상태 변경 함수 호출
        handleInsuranceChange(num, selectedValue);
    }
});

// 상태 변경 함수 (num, 선택값 받아서 처리)
function handleInsuranceChange(num, selectedValue) {
    fetch(`https://lincinsu.kr/2025/api/question/update_insurance.php`, {
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


















document.addEventListener("click", function (event) {
    if (event.target.classList.contains("k_1_open-claim-modal")) {
        event.preventDefault();
        const num = event.target.dataset.num;
        document.getElementById("questionNum__").value = num;
        const modal = document.querySelector(".k_1_claimModal"); // ✅ claimModal로 변경

        if (!modal) {
            console.error("🚨 k_1_claimModal이 존재하지 않습니다.");
            return;
        }

        // 기존 데이터 초기화 (모달을 닫았다가 다시 열 때 문제 방지)
        modal.querySelectorAll("input, textarea").forEach(input => input.value = "");
        modal.querySelectorAll("span").forEach(span => span.innerText = "");

        fetch(`https://lincinsu.kr/2025/api/claim/get_claim_details.php?id=${num}`)
            .then(response => response.json())
            .then(response => {
                if (response.success) {
                    // 데이터 입력
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

    // 모달 닫기
    if (event.target.classList.contains("close-modal")) {
        const modal = event.target.closest(".k_1_claimModal"); // ✅ 수정: k_1_claimModal 기반으로 닫기
        if (modal) {
            modal.style.display = "none";
        }
    }

    // 모달 외부 클릭 시 닫기
    if (event.target.classList.contains("k_1_claimModal")) { // ✅ k_1_claimModal 기준으로 변경
        event.target.style.display = "none";
    }
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
        claimData.append("manager", document.getElementById("userName__").value);
        claimData.append("damdanga", document.getElementById("damdanga_").value);
        claimData.append("damdangat", document.getElementById("damdangat_").value);

        // 데이터 전송
        fetch(`https://lincinsu.kr/2025/api/claim/claim_store.php`, {
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
	
});

// 모달이 열릴 때마다 새롭게 flatpickr 적용
document.addEventListener("click", function (event) {
    if (event.target.classList.contains("k_1_open-claim-modal")) {
        setTimeout(() => {
            if (document.getElementById("wdate_2")) {
                flatpickr("#wdate_2", {
                    dateFormat: "Y-m-d",
                    allowInput: true
                });
            }
			if (document.getElementById("wdate_3")) {
                flatpickr("#wdate_3", {
                    dateFormat: "Y-m-d",
                    allowInput: true
                });
            }
        }, 100);
    }
});


function perFormance() {
    console.log("📌 모달 오픈 & 데이터 요청");

    const modal = document.getElementById("sjModal");
   // modal.style.display = "flex"; // 모달 표시

    
    // 연도 선택 드롭다운 동적 생성 (최근 5년)
		showSelectedYear();
		// 페이지 로딩 시 자동 실행 서버데이터 가져오기 
        fetchData();
		//updateButtons(); // 버튼 정의 
		insertFooterButtons(); // ✅ 모달 푸터 버튼 삽입
    
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
    const yearSelect = document.createElement("select");
    yearSelect.id = "yearSelect";
    yearSelect.onchange = function() {
        fetchData(); // 데이터 로드 함수 호출
    };

    // 연도 옵션 추가 (최근 5년)
    for (let i = currentYear; i >= currentYear - 4; i--) {
        let option = document.createElement("option");
        option.value = i;
        option.textContent = i + "년"; // "2025년" 형식으로 표시
        yearSelect.appendChild(option);
    }

    yearContainer1.appendChild(yearSelect);
}

function insertFooterButtons() {
    const footerContainer = document.getElementById("changeP");

    // 기존 내용 초기화
    footerContainer.innerHTML = ""; 

    let ptr = "";
    ptr += `<button id="conPerformanceBtn" class="p-btn">계약자별 실적</button>`;
    ptr += `<button id="yearPerformanceBtn" class="p-btn">년도별 실적</button>`;

    footerContainer.innerHTML = ptr; // HTML 동적 삽입

    // ✅ DOM이 업데이트될 시간을 주기 위해 setTimeout 사용
    setTimeout(() => {
        const yearPerformanceBtn = document.getElementById("yearPerformanceBtn");
        if (yearPerformanceBtn) {
            yearPerformanceBtn.addEventListener("click", yearPerFormance);
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
		
	let selectedYear = document.getElementById("yearSelect").value;
	fetch(`https://lincinsu.kr/2025/api/claim/get_claim_summary.php?year=${selectedYear}`)
		.then(response => response.json())
		.then(data => updateTable(data))
		.catch(error => console.error("데이터 로드 오류:", error));
}

function updateTable(jsonData) {
    let claimData = {};
    
    // 12개월 기본 구조 생성
    for (let i = 1; i <= 12; i++) {
        let month = `${yearSelect.value}-${String(i).padStart(2, '0')}`;
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
function yearPerFormance(){
	showSelectedYear2()
	insertFooterButtons2(); // ✅ 모달 푸터 버튼 삽입updateButtonsYear();  
	
	TableInit(); //소계부분 초기 
	fetchYearlyData();
}

function insertFooterButtons2() {
    const footerContainer = document.getElementById("changeP");

    // 기존 내용 초기화
    footerContainer.innerHTML = ""; 

    let ptr = "";
    ptr += `<button id="conPerformanceBtn" class="p-btn">계약자별 실적</button>`;
    ptr += `<button id="performanceBtn" class="p-btn">월별 실적</button>`;

    footerContainer.innerHTML = ptr; // HTML 동적 삽입

    // ✅ DOM이 업데이트될 시간을 주기 위해 setTimeout 사용
    setTimeout(() => {
        const performanceBtn = document.getElementById("performanceBtn");
        if (performanceBtn) {
            performanceBtn.addEventListener("click", perFormance);
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
		const yearSelect = document.createElement("select");
		yearSelect.id = "yearSelect";
		yearSelect.onchange = function() {
			fetchYearlyData(); // 데이터 로드 함수 호출
			
		};

		// 연도 옵션 추가 (최근 5년)
		for (let i = currentYear; i >= currentYear - 4; i--) {
			let option = document.createElement("option");
			option.value = i;
			option.textContent = i + "년"; // "2025년" 형식으로 표시
			 yearSelect.appendChild(option);
		}

		

		// 생성한 <select> 요소를 #yearContainer 안에 추가
		yearContainer1.appendChild(yearSelect);
}



function TableInit(){
	let tbody = document.querySelector("#claimTable tbody");
	tbody.innerHTML = "";
	document.getElementById("totalReceived").textContent = "";
	document.getElementById("totalPending").textContent = "";
	document.getElementById("totalCompleted").textContent = "";
	document.getElementById("totalExempted").textContent = "";
	document.getElementById("totalCanceled").textContent = "";
	document.getElementById("totalAll").textContent = "";;
	document.getElementById("totalClaimAmount").textContent = "";
	document.getElementById("totalPremiumAmount").textContent = "";;
	document.getElementById("totalLossRatio").textContent ="";; // 손해율 표시
}
function fetchYearlyData() {
    let selectedYear = document.getElementById("yearSelect").value; // 선택된 연도 가져오기

	
    fetch(`https://lincinsu.kr/2025/api/claim/get_yearly_summary.php?year=${selectedYear}`)
        .then(response => response.json())
        .then(data => updateYearlyTable(data))
        .catch(error => console.error("데이터 로드 오류:", error));

}

function updateYearlyTable(jsonData) {
    let yearData = {};
    let startYear = parseInt(document.getElementById("yearSelect").value) - 9; // 최근 10년

    // 소계 변수 초기화
    let totalReceived = 0, totalPending = 0, totalCompleted = 0, totalExempted = 0, totalCanceled = 0;
    let totalClaimAmount = 0, totalPremiumAmount = 0, totalLossRatio = 0, yearCount = 0;

    // 최근 10년 초기화
    for (let i = startYear; i <= parseInt(document.getElementById("yearSelect").value); i++) {
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
	insertFooterButtons3();
	
	TableInit(); //소계부분 초기 
	fetchContractorData();
}

function showSelectedYear3() {

	document.getElementById("yearContainer1").innerHTML = "";

		const currentYear = new Date().getFullYear();
		const yearContainer1 = document.getElementById("yearContainer1");
		

		// <select> 요소 동적 생성
		const yearSelect = document.createElement("select");
		yearSelect.id = "yearSelect";
		yearSelect.onchange = function() {
			fetchContractorData(); // 데이터 로드 함수 호출
			
		};

		// 연도 옵션 추가 (최근 5년)
		for (let i = currentYear; i >= currentYear - 4; i--) {
			let option = document.createElement("option");
			option.value = i;
			option.textContent = i + "년"; // "2025년" 형식으로 표시
			 yearSelect.appendChild(option);
		}

		

		// 생성한 <select> 요소를 #yearContainer 안에 추가
		yearContainer1.appendChild(yearSelect);
}
function insertFooterButtons3() {
    const footerContainer = document.getElementById("changeP");

    // 기존 내용 초기화
    footerContainer.innerHTML = ""; 

    let ptr = "";
    ptr += `<button id="yearPerformanceBtn" class="p-btn">년도별실적</button>`;
    ptr += `<button id="performanceBtn" class="p-btn">월별 실적</button>`;

    footerContainer.innerHTML = ptr; // HTML 동적 삽입

    // ✅ DOM이 업데이트될 시간을 주기 위해 setTimeout 사용
    setTimeout(() => {
        const performanceBtn = document.getElementById("performanceBtn");
        if (performanceBtn) {
            performanceBtn.addEventListener("click", perFormance);
            console.log("📌 '월별 실적' 버튼 이벤트 바인딩 완료!");
        } else {
            console.error("🚨 '월별 실적' 버튼을 찾을 수 없습니다!");
        }
    }, 50); // 50ms 딜레이 후 실행

	// ✅ DOM이 업데이트될 시간을 주기 위해 setTimeout 사용
    setTimeout(() => {
        const yearPerformanceBtn = document.getElementById("yearPerformanceBtn");
        if (yearPerformanceBtn) {
            yearPerformanceBtn.addEventListener("click", yearPerFormance);
            console.log("📌 '년도별 실적' 버튼 이벤트 바인딩 완료!");
        } else {
            console.error("🚨 '년도별 실적' 버튼을 찾을 수 없습니다!");
        }
    }, 50); // 50ms 딜레이 후 실행
}
function fetchContractorData() {
    let selectedYear = document.getElementById("yearSelect").value; // 선택된 연도 가져오기

    
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















document.addEventListener("click", function (event) {
if (event.target.classList.contains("upload-modal")) {
	event.preventDefault();
	const num = event.target.dataset.num;
	document.getElementById("qNum").value=num;
	fetch(`https://lincinsu.kr/2025/api/question/get_questionnaire_details.php?id=${num}`)
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

    // 모달 닫기
    document.querySelectorAll(".close-upmodal").forEach(function (element) {
        element.addEventListener("click", function () {
            document.getElementById("uploadModal").style.display = "none";
        });
    });

    // 모달 외부를 클릭하면 닫기

	if (event.target.classList.contains("upModal")) { // ✅ upModal 기준으로 변경
        event.target.style.display = "none";
    }

	
	
}); 

function fileSearch(qnum) {
    fetch(`https://lincinsu.kr/2025/api/question/get_filelist.php?id=${qnum}`)
        .then(response => response.json())
        .then(fileData => {
            console.log(fileData);

            let rows = "";
            let i = 1;

            const kindMapping = {
                1: '카드전표',
                2: '영수증',
                3: '기타',
                4: '청약서',
                5: '과별인원',
                6: '보험사사업자등록증',
                7: '보험증권'
            };

            fileData.forEach((item) => {
                const filePath = item.description2;
                const fileName = filePath.split('/').pop();
                const kind = kindMapping[item.kind] || '알 수 없음';

                rows += `
                    <tr>
                        <td>${i}</td>
                        <td>${kind}</td>
                        <td>${item.bunho}</td>
                        <td><a href="${filePath}" download target="_blank" class="file-link">${fileName}</a></td>
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

    fetch(`https://lincinsu.kr/2025/api/question/delete_file.php?id=${fileNum}`)
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
    const fileInput = document.getElementById('uploadedFile');
    const fileType = document.getElementById('fileType').value;
    const qNum = document.getElementById('qNum').value;
    const dynamicInput = document.getElementById('dynamicInput') ? document.getElementById('dynamicInput').value : '';
	const userName = document.getElementById("userName").value;
    // 파일 선택 확인
    if (fileInput.files.length === 0) {
        alert('파일을 선택해주세요.');
        return;
    }

    // 청약서(4) 또는 보험증권(7) 업로드 시 번호 입력 필수
    if ((fileType === '4' || fileType === '7') && dynamicInput.trim() === '') {
        alert(fileType === '4' ? '설계번호를 입력해주세요.' : '증권번호를 입력해주세요.');
        return;
    }

    const formData = new FormData();
    formData.append('file', fileInput.files[0]);
    formData.append('fileType', fileType);
    formData.append('qNum', qNum);
	formData.append('userName', userName);

    // 파일 타입이 청약서(4) 또는 보험증권(7)일 경우 번호 추가
    if (fileType === '4') {
        formData.append('designNumber', dynamicInput); // 설계번호 추가
    } else if (fileType === '7') {
        formData.append('certificateNumber', dynamicInput); // 증권번호 추가
    }

    // 파일 업로드 요청
    fetch(`https://lincinsu.kr/2025/api/question/upload.php`, {
        method: 'POST',
        body: formData
    })
    .then(response => response.text())
    .then(result => {
        alert('업로드 완료: ' + result);
        fileSearch(qNum); // 파일 목록 갱신
    })
    .catch(error => {
        alert('업로드 실패.');
        console.error('파일 업로드 오류:', error);
    });
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





    console.log("✅ claim.js 초기화 완료");
}
