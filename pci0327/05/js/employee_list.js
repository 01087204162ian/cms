/** 현장실습보험  클레임관련 js  파일 **/

function employeeList(){
	 //document.getElementById('page-content');
	 const pageContent = document.getElementById('page-content');

	
		pageContent.innerHTML = '<tr><td colspan="14" class="loading">데이터 로드 중...</td></tr>';
        pageContent.innerHTML = "";
	 const claimContents= `<div class="e1-list-container">
    <!-- 검색 영역 -->
    <div class="e1-list-header">
        <div class="e1-left-area">
            <div class="e1-search-area">
                <select id="e1-searchType">
                    <option value="1">성명</option>
						<option value="2">아이디</option>
						<option value="3">전화번호</option>
						<option value="4">담당자</option>
						<option value="5">팀</option>
						<option value="6">역할</option>
						<option value="7">level</option>
                </select>
                <input type="text" id="e1-searchKeyword" placeholder="검색어를 입력하세요" onkeypress="if(event.key === 'Enter') e1_searchList()">
                <button class="e1-search-button" onclick="e1_searchList()">검색</button>
            </div>
        </div>
        <div class="right-area">
            <button class="e1-stats-button" onclick="showStatsModal()">통계</button>
        </div>
    </div>

    <!-- 리스트 영역 -->
    <div class="e1-list-content">
        <div class="e1-data-table-container">
            <table class="e1-data-table">
                <thead>
                    <tr>
                        <th class="col-num">No</th>
                        <th class="col-business-num">아이디</th>
                        <th class="col-school">성명</th>
                        <th class="col-students">이메일</th>
                        <th class="col-phone">연락처</th>
                        <th class="col-date">등록일</th>
                        <th class="col-policy">레벨</th>
                        <th class="col-premium">담당자</th>
                        <th class="col-insurance">팀</th>
                        <th class="col-status">역할</th>
		                <th class="col-contact">기타</th>
						<th class="col-action">로그인시간</th>
						<th class="col-action">클레임</th>
						<th class="col-memo">메모</th>
						<th class="col-manager">관리자</th>
                    </tr>
                </thead>
                <tbody id="e1-applicationList">
                    <!-- 데이터가 여기에 동적으로 로드됨 -->
                </tbody>
            </table>
        </div>
    </div>

    
    <!-- 페이지네이션 -->
	<div class="e1-pagination"></div>
    </div>`;
	 

	 pageContent.innerHTML= claimContents;

	loadTable3();
}


function loadTable3(page = 1, searchSchool = '', searchMode = 1) {
		
		const itemsPerPage = 15;
        const tableBody = document.querySelector("#e1-applicationList");
        const pagination = document.querySelector(".e1-pagination");

        // 로딩 표시
        // 로딩 표시
        tableBody.innerHTML = '<tr><td colspan="14" class="loading">데이터 로드 중...</td></tr>';
        pagination.innerHTML = "";

        fetch(`/simg/api/employee/fetch_employee.php?page=${page}&limit=${itemsPerPage}&search_school=${searchSchool}&search_mode=${searchMode}`)
            .then(response => response.json())
            .then(response => {
                let rows = "";

                // 데이터 존재 여부 확인
                if (!response.data || response.data.length === 0) {
                    rows = `<tr><td colspan="13" style="text-align: center;">검색 결과가 없습니다.</td></tr>`;
                } else {
                    response.data.forEach((item, index) => {
                        const formattedPreiminum = item.preiminum ? parseFloat(item.preiminum).toLocaleString("en-US") : "0";

						//console.log('item.level',item.level);

                    

                        rows += `<tr>
                            <td><a href="#" class="btn-link_1 k_2_open-second-modal" data-num="${item.id}">${(page - 1) * itemsPerPage + index + 1}</a></td>
                            <td>${item.userid}</td>
                            <td>${item.name}</td>
                            <td>${item.email}</td>
                            <td>${item.phone}</td>
                            <td>${item.created_at}</td>
                            <td></td>         
                            <td></td>
							<td></td>
							<td></td>
                        </tr>`;
                    });
                }

                tableBody.innerHTML = rows;

                // 페이지네이션 생성
                renderPagination3(page, Math.ceil(response.total / itemsPerPage));
            })
            .catch(() => {
                alert("데이터를 불러오는 중 오류가 발생했습니다.");
            });
    }

 function renderPagination3(currentPage, totalPages) {
    const pagination = document.querySelector(".e1-pagination");
    pagination.innerHTML = ""; // 기존 버튼 삭제

    // 이전 버튼 추가
    if (currentPage > 1) {
        pagination.innerHTML += `<a href="#" class="e1-page-link" data-page="${currentPage - 1}">이전</a>`;
    } else {
        pagination.innerHTML += `<a href="#" class="e1-disabled">이전</a>`;
    }

    // 숫자 버튼 추가 (최대 5개 표시)
    const maxPagesToShow = 5;
    let startPage = Math.max(1, currentPage - Math.floor(maxPagesToShow / 2));
    let endPage = Math.min(totalPages, startPage + maxPagesToShow - 1);

    for (let i = startPage; i <= endPage; i++) {
        pagination.innerHTML += `<a href="#" class="e1-page-link ${i === currentPage ? "active" : ""}" data-page="${i}">${i}</a>`;
    }

    // 다음 버튼 추가
    if (currentPage < totalPages) {
        pagination.innerHTML += `<a href="#" class="e1-page-link" data-page="${currentPage + 1}">다음</a>`;
    } else {
        pagination.innerHTML += `<a href="#" class="e1-disabled">다음</a>`;
    }

    // 페이지 이동 이벤트 추가
    document.querySelectorAll(".e1-page-link").forEach(link => {
        link.addEventListener("click", function (e) {
            e.preventDefault();
            loadTable3(parseInt(this.dataset.page));
        });
    });
}

/* 검색 버튼*/
function e1_searchList() {
    const searchType = document.querySelector("#e1-searchType").value;
    const searchKeyword = document.querySelector("#e1-searchKeyword").value.trim();

    if (!searchKeyword) {
        alert("검색어를 입력하세요.");
        return;
    }

    loadTable3(1, searchKeyword, searchType);
}