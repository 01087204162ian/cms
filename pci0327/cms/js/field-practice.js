// 가입설계번호 엔터 이벤트 핸들러를 파일 최상단으로 이동
window.handleGabunhoEnter = async function(event) {
    if (event.key === 'Enter') {
        const gabunho = event.target.value.trim();
        const num = document.getElementById('questionwareNum_').value;
        // 세션스토리지에서 사용자 이름 가져오기
        const userName = sessionStorage.getItem('userName') || localStorage.getItem('userName');

        if (!gabunho) {
            alert('가입 설계번호를 입력하세요.');
            return;
        }

        if (!userName) {
            alert('사용자 정보를 찾을 수 없습니다. 다시 로그인해주세요.');
            return;
        }

        try {
            const formData = new URLSearchParams();
            formData.append('gabunho', gabunho);
            formData.append('num', num);
            formData.append('userName', userName);

            // 프록시를 통한 API 호출
            const apiUrl = encodeURIComponent(`https://lincinsu.kr/2025/api/question/update_gabunho.php`);
            const response = await fetch(`/cms/api/proxy.php?url=${apiUrl}`, {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/x-www-form-urlencoded',
                },
                body: formData
            });

            const data = await response.json();
            if (data.success) {
                alert('가입 설계번호가 성공적으로 저장되었습니다.');
                
                // 리스트 데이터 새로고침
                const searchInput = document.getElementById('searchKeyword');
                if (searchInput && searchInput.value) {
                    await searchList(); // 검색 결과 새로고침
                } else {
                    await fieldPracticeAPI.fetchApplications()
                        .then(result => fieldPracticeAPI.renderApplicationList(result.data));
                }
            } else {
                alert('저장 실패: ' + (data.error || '알 수 없는 오류'));
            }
        } catch (error) {
            console.error('가입 설계번호 저장 실패:', error);
            alert('가입 설계번호 저장 중 오류가 발생했습니다.');
        }
    }
};

// 증권번호 엔터 이벤트 핸들러 수정
window.handleCertiEnter = async function(event) {
    if (event.key === 'Enter') {
        const certi_ = event.target.value.trim();
        const num = document.getElementById('questionwareNum_').value;
        const userName = sessionStorage.getItem('userName') || localStorage.getItem('userName');

        if (!certi_) {
            alert('증권번호를 입력하세요.');
            return;
        }

        if (!userName) {
            alert('사용자 정보를 찾을 수 없습니다. 다시 로그인해주세요.');
            return;
        }

        try {
            const formData = new URLSearchParams();
            formData.append('certi_', certi_);
            formData.append('num', num);
            formData.append('userName', userName);

            const apiUrl = encodeURIComponent('https://lincinsu.kr/2025/api/question/update_certi_.php');
            const response = await fetch(`/cms/api/proxy.php?url=${apiUrl}`, {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/x-www-form-urlencoded',
                },
                body: formData
            });

            const data = await response.json();
            if (data.success) {
                alert('증권번호가 성공적으로 저장되었습니다.');
                
                // 리스트 데이터 새로고침
                const searchInput = document.getElementById('searchKeyword');
                if (searchInput && searchInput.value) {
                    await searchList();
                } else {
                    await fieldPracticeAPI.fetchApplications()
                        .then(result => fieldPracticeAPI.renderApplicationList(result.data));
                }
            } else {
                alert('저장 실패: ' + (data.error || '알 수 없는 오류'));
            }
        } catch (error) {
            console.error('증권번호 저장 실패:', error);
            alert('증권번호 저장 중 오류가 발생했습니다.');
        }
    }
};

// 은행명 엔터 이벤트 핸들러
window.handleBankNameEnter = async function(event) {
    if (event.key === 'Enter') {
        const bankName = event.target.value.trim();
        const cNum = document.getElementById('cNum_').value;
        const userName = sessionStorage.getItem('userName') || localStorage.getItem('userName');

        // 필수 값 검증
        if (!bankName) {
            alert('은행명을 입력하세요.');
            return;
        }

        if (!cNum) {
            alert('고객번호(cNum)가 없습니다.');
            return;
        }

        if (!userName) {
            alert('사용자 정보를 찾을 수 없습니다. 다시 로그인해주세요.');
            return;
        }

        try {
            const formData = new URLSearchParams();
            formData.append('num', cNum);
            formData.append('bankName', bankName);
            formData.append('userName', userName);

            const apiUrl = encodeURIComponent('https://lincinsu.kr/2025/api/question/update_bank_name.php');
            const response = await fetch(`/cms/api/proxy.php?url=${apiUrl}`, {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/x-www-form-urlencoded',
                },
                body: formData
            });
            
            const responseText = await response.text();

            if (responseText.includes('성공')) {
                alert('은행명이 성공적으로 저장되었습니다.');
                
                // 리스트 데이터 새로고침
                const searchInput = document.getElementById('searchKeyword');
                if (searchInput && searchInput.value) {
                    await searchList();
                } else {
                    await fieldPracticeAPI.fetchApplications()
                        .then(result => fieldPracticeAPI.renderApplicationList(result.data));
                }
            } else {
                alert('저장 실패: ' + responseText);
            }
        } catch (error) {
            console.error('은행명 저장 실패:', error);
            alert('은행명 저장 중 오류가 발생했습니다.');
        }
    }
};

// 카드번호 엔터 이벤트 핸들러
window.handleCardNumberEnter = async function(event) {
    if (event.key === 'Enter') {
        const cardNumber = event.target.value.trim();
        const cNum = document.getElementById('cNum_').value;
        const userName = sessionStorage.getItem('userName') || localStorage.getItem('userName');

        if (!cardNumber) {
            alert('카드번호를 입력하세요.');
            return;
        }

        if (!cNum) {
            alert('고객번호(cNum)가 없습니다.');
            return;
        }

        try {
            const formData = new URLSearchParams();
            formData.append('num', cNum);
            formData.append('cardnum', cardNumber);
            formData.append('userName', userName);

            const apiUrl = encodeURIComponent('https://lincinsu.kr/2025/api/question/update_cardnum.php');
            const response = await fetch(`/cms/api/proxy.php?url=${apiUrl}`, {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/x-www-form-urlencoded',
                },
                body: formData
            });
            
            const responseText = await response.text();

            if (responseText.includes('성공')) {
                alert('카드번호가 성공적으로 저장되었습니다.');
                
                // 리스트 데이터 새로고침
                const searchInput = document.getElementById('searchKeyword');
                if (searchInput && searchInput.value) {
                    await searchList();
                } else {
                    await fieldPracticeAPI.fetchApplications()
                        .then(result => fieldPracticeAPI.renderApplicationList(result.data));
                }
            } else {
                alert('저장 실패: ' + responseText);
            }
        } catch (error) {
            console.error('카드번호 저장 실패:', error);
            alert('카드번호 저장 중 오류가 발생했습니다.');
        }
    }
};

// 카드 유효기간 엔터 이벤트 핸들러 수정
window.handleCardExpiryEnter = async function(event) {
    if (event.key === 'Enter') {
        const cardExpiry = event.target.value.trim();
        const cNum = document.getElementById('cNum_').value;
        // 세션스토리지에서 사용자 이름 가져오기
        const userName = sessionStorage.getItem('userName') || localStorage.getItem('userName');

        // 필수 값 검증
        if (!cardExpiry) {
            alert('카드 유효기간을 입력하세요.');
            return;
        }

        if (!cNum) {
            alert('고객번호(cNum)가 없습니다.');
            return;
        }

        // MM/YY 형식 검증
        const expiryPattern = /^(0[1-9]|1[0-2])\/([0-9]{2})$/;
        if (!expiryPattern.test(cardExpiry)) {
            alert('유효기간을 MM/YY 형식으로 입력해주세요.');
            return;
        }

        try {
            // 디버깅을 위한 로그
            console.log('전송할 데이터:', {
                cNum: cNum,
                yymm: cardExpiry,
                userName: userName
            });

            const formData = new URLSearchParams();
            formData.append('num', cNum);
            formData.append('yymm', cardExpiry);
            formData.append('userName', userName);

            // 실제 전송되는 formData 확인
            console.log('FormData 내용:', formData.toString());

            // 프록시를 통한 API 호출
            const apiUrl = encodeURIComponent('https://lincinsu.kr/2025/api/question/update_yymm.php');
            const response = await fetch(`/cms/api/proxy.php?url=${apiUrl}`, {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/x-www-form-urlencoded',
                },
                body: formData
            });

            // 응답 상태 코드 확인
            console.log('응답 상태:', response.status);
            
            const responseText = await response.text();
            console.log('서버 응답 전문:', responseText);

            // 성공 여부 확인
            if (responseText.includes('성공')) {
                alert('카드 유효기간이 성공적으로 저장되었습니다.');
                
                // 리스트 데이터 새로고침
                const searchInput = document.getElementById('searchKeyword');
                if (searchInput && searchInput.value) {
                    await searchList();
                } else {
                    await fieldPracticeAPI.fetchApplications()
                        .then(result => fieldPracticeAPI.renderApplicationList(result.data));
                }
            } else {
                alert('저장 실패: ' + responseText);
            }
        } catch (error) {
            console.error('카드 유효기간 저장 실패:', error);
            console.error('에러 상세:', {
                name: error.name,
                message: error.message,
                stack: error.stack
            });
            alert('카드 유효기간 저장 중 오류가 발생했습니다.');
        }
    }
};

// 은행계좌 엔터 이벤트 핸들러
window.handleBankAccountEnter = async function(event) {
    if (event.key === 'Enter') {
        const bankAccount = event.target.value.trim();
        const cNum = document.getElementById('cNum_').value;
        // 세션스토리지에서 사용자 이름 가져오기
        const userName = sessionStorage.getItem('userName') || localStorage.getItem('userName');

        // 필수 값 검증
        if (!bankAccount) {
            alert('은행계좌를 입력하세요.');
            return;
        }

        if (!cNum) {
            alert('고객번호(cNum)가 없습니다.');
            return;
        }

        if (!userName) {
            alert('사용자 정보를 찾을 수 없습니다. 다시 로그인해주세요.');
            return;
        }

        try {
            // 디버깅을 위한 로그
            console.log('전송할 데이터:', {
                cNum: cNum,
                bank: bankAccount,
                userName: userName
            });

            const formData = new URLSearchParams();
            formData.append('num', cNum);
            formData.append('bank', bankAccount);
            formData.append('userName', userName);

            // 실제 전송되는 formData 확인
            console.log('FormData 내용:', formData.toString());

            // 프록시를 통한 API 호출
            const apiUrl = encodeURIComponent('https://lincinsu.kr/2025/api/question/update_bank_account.php');
            const response = await fetch(`/cms/api/proxy.php?url=${apiUrl}`, {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/x-www-form-urlencoded',
                },
                body: formData
            });

            // 응답 상태 코드 확인
            console.log('응답 상태:', response.status);
            
            const responseText = await response.text();
            console.log('서버 응답 전문:', responseText);

            // 성공 여부 확인
            if (responseText.includes('성공')) {
                alert('은행계좌가 성공적으로 저장되었습니다.');
                
                // 리스트 데이터 새로고침
                const searchInput = document.getElementById('searchKeyword');
                if (searchInput && searchInput.value) {
                    await searchList();
                } else {
                    await fieldPracticeAPI.fetchApplications()
                        .then(result => fieldPracticeAPI.renderApplicationList(result.data));
                }
            } else {
                alert('저장 실패: ' + responseText);
            }
        } catch (error) {
            console.error('은행계좌 저장 실패:', error);
            console.error('에러 상세:', {
                name: error.name,
                message: error.message,
                stack: error.stack
            });
            alert('은행계좌 저장 중 오류가 발생했습니다.');
        }
    }
};

// 현장실습보험 관련 API 호출 함수들
const fieldPracticeAPI = {
    // 신청 리스트 조회 (검색 기능 포함)
    fetchApplications: async (params = {}) => {
        try {
            const queryParams = new URLSearchParams({
                page: params.page || 1,
                limit: params.limit || 15,
                timestamp: new Date().getTime() // 캐시 방지
            });

            if (params.searchKeyword) {
                queryParams.append('search_school', params.searchKeyword);
                queryParams.append('search_mode', params.searchMode || '2');
            }

            const url = `/cms/api/field-practice/applications.php?${queryParams}`;
            console.log('API 요청 URL:', url);

            const response = await fetch(url, {
                method: 'GET',
                headers: {
                    'Cache-Control': 'no-cache, no-store, must-revalidate',
                    'Pragma': 'no-cache',
                    'Expires': '0'
                }
            });
            
            if (!response.ok) {
                throw new Error(`API 호출 실패: ${response.status} ${response.statusText}`);
            }

            const result = await response.json();
            console.log('API 응답:', result);

            // 응답 데이터 구조 검증
            if (!result) {
                throw new Error('유효하지 않은 응답 데이터');
            }

            // 데이터가 없는 경우 빈 배열 반환
            if (!result.data) {
                return { 
                    data: [], 
                    total: 0,
                    pages: 0 
                };
            }

            // 데이터가 배열이 아닌 경우 배열로 변환
            result.data = Array.isArray(result.data) ? result.data : [];

            // API 응답 그대로 반환 (pages 포함)
            return {
                data: result.data,
                total: result.total,
                pages: result.pages,
                page: result.page,
                limit: result.limit
            };

        } catch (error) {
            console.error('API 호출 상세 오류:', error);
            throw new Error(`데이터를 불러올 수 없습니다: ${error.message}`);
        }
    },

    // 페이지네이션 업데이트
    updatePagination: (total) => {
		console.log('total',total);
        const totalPages = Math.ceil(total / 15);
        document.getElementById('totalPages').textContent = totalPages;
        
        const currentPage = document.getElementById('currentPage');
        if (parseInt(currentPage.textContent) > totalPages) {
            currentPage.textContent = totalPages;
        }
    },

    // 리스트 렌더링
    renderApplicationList: (result) => {
        const tbody = document.getElementById('applicationList');
        if (!Array.isArray(result.data)) {
            tbody.innerHTML = '<tr><td colspan="15">데이터를 불러올 수 없습니다.</td></tr>';
            return;
        }

        if (result.data.length === 0) {
            tbody.innerHTML = '<tr><td colspan="15">데이터가 없습니다.</td></tr>';
            return;
        }

        tbody.innerHTML = result.data.map((item, index) => `
            <tr>
                <td class="clickable" onclick="showDetailModal('${item.num}')">${index + 1}</td>
                <td class="clickable" onclick="showBusinessModal('${item.num}')">${item.school2 || ''}</td>
                <td>${item.school1 || ''}</td>
                <td class="text-right">${item.week_total || ''}</td>
                <td>${item.school4 || ''}</td>
                <td>${item.wdate?.split(' ')[0] || ''}</td>
                <td>${item.certi || item.gabunho || ''}</td>
                <td class="text-right">${Number(item.preiminum || 0).toLocaleString()}</td>
                <td>
                    <select class="insurance-select" data-id="${item.num}" onchange="handleInsuranceChange(this)">
                        <option value="">선택</option>
                        <option value="1" ${item.inscompany === '1' ? 'selected' : ''}>한화</option>
                        <option value="2" ${item.inscompany === '2' ? 'selected' : ''}>Meritz</option>
                    </select>
                </td>
                <td>
                    <select class="status-select" data-id="${item.num}" onchange="handleStatusChange(this)">
                        <option value="1" ${item.ch == 1 ? "selected" : ""}>접수</option>
                        <option value="2" ${item.ch == 2 ? "selected" : ""}>보험료 안내중</option>
                        <option value="3" ${item.ch == 3 ? "selected" : ""}>청약서</option>
                        <option value="4" ${item.ch == 4 ? "selected" : ""}>입금대기중</option>
                        <option value="5" ${item.ch == 5 ? "selected" : ""}>입금확인</option>
                        <option value="6" ${item.ch == 6 ? "selected" : ""}>증권 발급</option>
                        <option value="7" ${item.ch == 7 ? "selected" : ""}>보류</option>
                        <option value="12" ${item.ch == 12 ? "selected" : ""}>수정요청</option>
                    </select>
                </td>
                <td>${item.school5 || ''}</td>
                
				<td class="clickable" onclick="handleUpload('${item.num}')">업로드</td>
                <td class="clickable" onclick="handleClaim('${item.num}')">클레임</td>
                <td>
                    <input 
                        type="text" 
                        class="memo-input" 
                        data-num="${item.num}"
                        value="${item.memo || ''}"
                        onkeyup="handleMemoEnter(event)"
                        placeholder="메모 입력"
                    >
                </td>
                <td>${item.manager || ''}</td>
            </tr>
        `).join('');

        // pages 값 사용
        document.getElementById('totalPages').textContent = result.pages || 1;
        
        const currentPage = document.getElementById('currentPage');
        if (parseInt(currentPage.textContent) > (result.pages || 1)) {
            currentPage.textContent = result.pages || 1;
        }
    }
};

// 현재 상세 정보 번호 저장용 변수
let currentDetailNum = null;

// 상세 정보 조회 API 호출
async function fetchDetailData(num) {
    try {
        const response = await fetch(`/cms/api/field-practice/detail.php?num=${num}`);
        if (!response.ok) {
            throw new Error(`HTTP error! status: ${response.status}`);
        }
        const data = await response.json();
        if (data.error) {
            throw new Error(data.message || '데이터를 불러오는데 실패했습니다.');
        }
        return data;
    } catch (error) {
        console.error('상세 정보 조회 실패:', error);
        throw error;
    }
}

// 카드번호 마스킹 함수 추가
function maskCardNumber(cardNumber) {
    if (!cardNumber) return '';
    // 앞 6자리와 뒤 4자리만 보이고 나머지는 '*' 처리
    return cardNumber.replace(/(\d{6})\d+(\d{4})/, '$1********$2');
}

// 계좌번호 마스킹 함수 추가
function maskAccountNumber(accountNumber) {
    if (!accountNumber) return '';
    // 앞 4자리와 뒤 4자리만 보이고 나머지는 '*' 처리
    return accountNumber.replace(/(\d{4})\d+(\d{4})/, '$1*****$2');
}

// 상세 정보 모달 표시 함수
async function showDetailModal(num) {
    try {
        if (!num) {
            throw new Error('문서 번호가 없습니다.');
        }

        // userName 가져오기
        const userName = sessionStorage.getItem('userName') || localStorage.getItem('userName');
        if (!userName) {
            throw new Error('사용자 정보를 찾을 수 없습니다. 다시 로그인해주세요.');
        }

        // URL 파라미터 구성
        const params = new URLSearchParams({
            id: num,
            userName: encodeURIComponent(userName)
        });

        const targetUrl = `https://lincinsu.kr/2025/api/question/get_questionnaire_details.php?${params.toString()}`;
        console.log('Requesting URL:', targetUrl); // 디버깅용 로그

        const response = await fetch(`/cms/api/proxy.php?url=${encodeURIComponent(targetUrl)}`);
        
        if (!response.ok) {
            const errorText = await response.text();
            console.error('API 응답 에러:', errorText);
            throw new Error(`HTTP error! status: ${response.status}`);
        }
        
        const responseText = await response.text();
        let data;
        
        try {
            data = JSON.parse(responseText);
        } catch (e) {
            console.error('JSON 파싱 실패:', responseText);
            throw new Error('서버 응답을 파싱할 수 없습니다.');
        }
        
        if (!data || !data.success || !data.data) {
            throw new Error(data?.error || '데이터를 불러올 수 없습니다.');
        }

        // 모달 컨테이너 가져오기
        const modalContainer = document.getElementById('modal-container');
        const detailModal = document.getElementById('detail-modal');
        
        if (!modalContainer || !detailModal) {
            throw new Error('모달 요소를 찾을 수 없습니다.');
        }

        // 클레임 모달이 열려있다면 닫기
        const claimModal = document.querySelector('.claimModal');
        if (claimModal) {
            claimModal.style.display = 'none';
        }
        
        // 히든 필드 값 설정
        const questionwareNum = document.getElementById("questionwareNum_");
        const school9 = document.getElementById("school9_");
        const cNum = document.getElementById("cNum_");

        if (questionwareNum) questionwareNum.value = data.data.num || '';
        if (school9) school9.value = data.data.school9 || '';
        if (cNum) cNum.value = data.data.cNum || '';

        // 전 설계번호 설정
        const beforegabunho = document.getElementById("beforegabunho");
        if (beforegabunho) {
            beforegabunho.textContent = data.beforeGabunho ? 
            `전 설계번호: ${data.beforeGabunho}` : "신규";
        }

        // 계약자 정보 설정
        const fields = ["school1", "school2", "school3", "school4", "school5", "school7", "school8"];
        fields.forEach(field => {
            document.getElementById(`school_${field.slice(-1)}`).textContent = data.data[field];
        });

        // 현장실습 시기
        const periods = { "1": "1학기", "2": "하계", "3": "2학기", "4": "동계" };
        document.getElementById("school_6").textContent = periods[data.data.school6] || "알 수 없음";

        // 가입유형
        document.getElementById("school_9").textContent = data.data.school9 == 1 ? "가입유형 A" : "가입유형 B";

        // 대인/대물 설정
        const limits = data.data.directory == 2 ? 
            { A: "2 억", B: "3 억" } : 
            { A: "2 억", B: "3 억" };
        document.getElementById("daein1_").textContent = limits[data.data.school9 == 1 ? "A" : "B"];
        document.getElementById("daein2_").textContent = limits[data.data.school9 == 1 ? "A" : "B"];

        // 보험료 정보 설정
        document.getElementById("daein_").textContent = data.daeinP;
        document.getElementById("daemool_").textContent = data.daemoolP;
        document.getElementById("totalP_").textContent = data.preiminum;

        // 참여인원 정보 설정
        let inwons = "";
        for (let i = 4; i <= 26; i++) {
            if (data.data[`week${i}`] != 0) {
                inwons += `<span id="week_${i}">${i} 주</span> <span id="week_inwon${i}">${data.data[`week${i}`]} </span> 명, `;
            }
        }
        inwons += `총인원 : <span id="week_total_">${data.data.week_total}</span>`;
        document.getElementById("inwon").innerHTML = inwons;

        // 기타 정보 입력
        document.getElementById("gabunho_").value = data.data.gabunho;
        document.getElementById("certi_").value = data.data.certi;
        document.getElementById("cardNum_").value = data.cardnum;
        document.getElementById("cardExpiry_").value = data.yymm;
        document.getElementById("bank_").value = data.bankname;
        document.getElementById("bankAccount_").value = data.bank;
        document.getElementById("manager_").value = data.damdanga;
        document.getElementById("managerPhone_").value = data.damdangat;

        // mem_id 동적 로드
        const apiUrl = encodeURIComponent('https://lincinsu.kr/2025/api/question/get_idList.php');
        fetch(`/cms/api/proxy.php?url=${apiUrl}`)
            .then(response => response.json())
            .then(memData => {
                const select = detailModal.querySelector("#mem-id-select");
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
                select.value = data.data.cNum;
            })
            .catch(() => alert("mem_id 데이터를 가져오는 데 실패했습니다."));

        // 모달 표시
        detailModal.style.display = 'block';
        modalContainer.style.display = 'block';
        modalContainer.classList.add('active');
        
        // 날짜 입력 초기화
        setupDateInputs();

    } catch (error) {
        console.error('상세 정보 조회 실패:', error);
        alert('상세 정보를 불러오는 중 오류가 발생했습니다: ' + error.message);
    }
}

// 모달 닫기 함수 수정
function closeModal() {
    const modalContainer = document.getElementById('modal-container');
    const allModals = document.querySelectorAll('#detail-modal, #business-modal, #claim-modal, #stats-modal');
    
    // 모든 모달 숨기기
    allModals.forEach(modal => {
        if (modal) {
            modal.style.display = 'none';
        }
    });
    
    if (modalContainer) {
        modalContainer.style.display = 'none';
        modalContainer.classList.remove('active');
    }
}

// 모달 변경사항 저장
async function saveChanges() {
    try {
        const modalData = {
            num: currentDetailNum,
            gabunho: document.getElementById('modalGabunho').value,
            inscompany: document.getElementById('modalInsurance').value,
            ch: document.getElementById('modalStatus').value,
            memo: document.getElementById('modalMemo').value
        };

        const response = await fetch('/cms/api/field-practice/update.php', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json'
            },
            body: JSON.stringify(modalData)
        });

        if (!response.ok) {
            throw new Error(`HTTP error! status: ${response.status}`);
        }

        const result = await response.json();
        
        if (result.error) {
            throw new Error(result.message || '저장에 실패했습니다.');
        }

        alert('변경사항이 저장되었습니다.');
        
        // 리스트 새로고침
        await fieldPracticeAPI.fetchApplications({
            page: document.getElementById('currentPage').textContent
        });

        closeModal();

    } catch (error) {
        console.error('저장 실패:', error);
        alert('저장에 실패했습니다: ' + error.message);
    }
}

// ESC 키로 모달 닫기
document.addEventListener('keydown', (event) => {
    if (event.key === 'Escape') {
        closeModal();
    }
});

// 전역 함수들을 먼저 정의 (IIFE 밖으로 이동)
window.searchList = async function() {
    const searchType = document.getElementById('searchType').value;
    const searchKeyword = document.getElementById('searchKeyword').value;

    if (!searchKeyword.trim()) {
        alert('검색어를 입력해주세요.');
        return;
    }

    try {
        const result = await fieldPracticeAPI.fetchApplications({
            searchKeyword,
            searchMode: searchType,
            page: 1
        });
        
        if (!result.data) {
            throw new Error('유효하지 않은 응답 데이터');
        }

        fieldPracticeAPI.renderApplicationList(result);
        fieldPracticeAPI.updatePagination(result.total);

    } catch (error) {
        console.error('검색 실패:', error);
        alert('검색 중 오류가 발생했습니다.');
    }
};

// 페이지 변경 함수도 전역으로 이동
window.changePage = async function(direction) {
    const currentPage = parseInt(document.getElementById('currentPage').textContent);
    const totalPages = parseInt(document.getElementById('totalPages').textContent);
    
    let newPage = direction === 'next' ? currentPage + 1 : currentPage - 1;
    
    if (newPage < 1 || newPage > totalPages) return;
    
    try {
        const searchType = document.getElementById('searchType').value;
        const searchKeyword = document.getElementById('searchKeyword').value;
        
        const result = await fieldPracticeAPI.fetchApplications({
            page: newPage,
            searchMode: searchType,
            searchKeyword
        });
        
        fieldPracticeAPI.renderApplicationList(result);
        document.getElementById('currentPage').textContent = newPage;
    } catch (error) {
        console.error('페이지 변경 실패:', error);
    }
};

// 문서 처리 함수
window.handleDocument = function(type) {
    const questionwareNum = document.getElementById('questionwareNum_').value;
    
    if (!questionwareNum) {
        alert('문서를 열 수 없습니다. 필요한 정보가 없습니다.');
        return;
    }

    switch(type) {
        case 'question':
            // 질문서 프린트
            window.open(
                `https://lincinsu.kr/2014/_pages/php/downExcel/claim2.php?claimNum=${encodeURIComponent(questionwareNum)}`,
                '_blank'
            );
            break;
            
        case 'contract':
            // 청약서 프린트
            window.open(
                `https://lincinsu.kr/2014/_pages/php/downExcel/claim3.php?claimNum=${encodeURIComponent(questionwareNum)}`,
                '_blank'
            );
            break;

        case 'resetIdPw':
            // 아이디/비번 초기화 메일 발송
            const apiUrl = encodeURIComponent('https://lincinsu.kr/2025/api/email_send.php');
            fetch(`/cms/api/proxy.php?url=${apiUrl}`, {
                method: "POST",
                headers: { 
                    "Content-Type": "application/x-www-form-urlencoded" 
                },
                body: `num=${encodeURIComponent(questionwareNum)}`,
            })
            .then(response => response.json())
            .then(data => {
                alert(data.success ? "성공적 발송 완료!" : "메일 발송 중 오류가 발생했습니다.");
            })
            .catch(() => alert("메일 전송 요청 실패."));
            break;

        case 'safetyMail':
            // 무사고 확인서 프린트
            window.open(
                `https://lincinsu.kr/2014/_pages/php/downExcel/claim7.php?claimNum=${encodeURIComponent(questionwareNum)}`,
                '_blank'
            );
            break;

        case 'guide':
            // 가입안내문 프린트
            window.open(
                `https://lincinsu.kr/2014/_pages/php/downExcel/claim9.php?claimNum=${encodeURIComponent(questionwareNum)}`,
                '_blank'
            );
            break;
            
        // 다른 문서 타입들에 대한 처리...
        default:
            alert('지원하지 않는 문서 타입입니다.');
    }
};

// 아이디/비번 초기화 메일 발송
async function sendResetEmail(num) {
    try {
        const response = await fetch('/api/send_reset_email.php', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
            },
            body: JSON.stringify({ num })
        });
        
        const result = await response.json();
        if (result.success) {
            alert('초기화 메일이 발송되었습니다.');
        } else {
            throw new Error(result.message);
        }
    } catch (error) {
        console.error('메일 발송 실패:', error);
        alert('메일 발송에 실패했습니다.');
    }
}

// 보험사 변경 처리 함수
window.handleInsuranceChange = function(selectElement) {
    const selectedValue = selectElement.value;
    const num = selectElement.getAttribute('data-id');

    if (!num) {
        alert('문서 번호가 없습니다.');
        return;
    }

    // 프록시를 통한 API 호출
    const apiUrl = encodeURIComponent('https://lincinsu.kr/2025/api/question/update_insurance.php');
    fetch(`/cms/api/proxy.php?url=${apiUrl}`, {
        method: "POST",
        headers: { 
            "Content-Type": "application/x-www-form-urlencoded" 
        },
        body: `id=${encodeURIComponent(num)}&inscompany=${encodeURIComponent(selectedValue)}`,
    })
    .then(response => response.json())
    .then(data => {
        if (data.success) {
            alert("보험사가 성공적으로 변경되었습니다.");
            
            // 리스트 새로고침
            const searchInput = document.getElementById('searchKeyword');
            if (searchInput && searchInput.value) {
                searchList();
            } else {
                fieldPracticeAPI.fetchApplications()
                    .then(result => fieldPracticeAPI.renderApplicationList(result.data));
            }
        } else {
            alert("보험사 변경 중 오류가 발생했습니다.");
        }
    })
    .catch(error => {
        console.error("보험사 변경 오류:", error);
        alert("서버 오류로 인해 보험사를 변경할 수 없습니다.");
    });
};

// 상태 변경 처리 함수를 IIFE 밖으로 이동
window.handleStatusChange = function(selectElement) {
    const selectedValue = selectElement.value;
    const num = selectElement.getAttribute('data-id');

    if (!num) {
        alert('문서 번호가 없습니다.');
        return;
    }

    // 프록시를 통한 API 호출
    const apiUrl = encodeURIComponent('https://lincinsu.kr/2025/api/question/update_status.php');
    fetch(`/cms/api/proxy.php?url=${apiUrl}`, {
        method: "POST",
        headers: { 
            "Content-Type": "application/x-www-form-urlencoded" 
        },
        body: `id=${encodeURIComponent(num)}&ch=${encodeURIComponent(selectedValue)}`,
    })
    .then(response => response.json())
    .then(data => {
        if (data.success) {
            alert("상태가 성공적으로 업데이트되었습니다.");
            
            // 리스트 새로고침
            const searchInput = document.getElementById('searchKeyword');
            if (searchInput && searchInput.value) {
                searchList();
            } else {
                fieldPracticeAPI.fetchApplications()
                    .then(result => fieldPracticeAPI.renderApplicationList(result.data));
            }
        } else {
            alert("상태 업데이트 중 오류가 발생했습니다.");
        }
    })
    .catch(error => {
        console.error("상태 업데이트 오류:", error);
        alert("서버 오류로 인해 상태를 변경할 수 없습니다.");
    });
};

// IIFE를 사용하여 스코프 격리
(function() {
    // 전역 변수 중복 선언 방지
    if (typeof window.fieldPracticeAPI === 'undefined') {
        window.fieldPracticeAPI = {
            // 신청 리스트 조회 (검색 기능 포함)
            fetchApplications: async (params = {}) => {
                try {
                    const queryParams = new URLSearchParams({
                        page: params.page || 1,
                        limit: params.limit || 15
                    });

                    if (params.searchKeyword) {
                        queryParams.append('search_school', params.searchKeyword);
                        queryParams.append('search_mode', params.searchMode || '2');
                    }

                    const url = `/cms/api/field-practice/applications.php?${queryParams}`;
                    const response = await fetch(url);
                    
                    if (!response.ok) {
                        throw new Error(`API 호출 실패: ${response.status}`);
                    }

                    const result = await response.json();
                    if (result.error) {
                        throw new Error(result.message || '알 수 없는 오류 발생');
                    }

                    return result;
            } catch (error) {
                    console.error('API 호출 상세 오류:', error);
                    throw error;
                }
            },

            // 페이지네이션 업데이트
            updatePagination: (total) => {
                const totalPages = Math.ceil(total / 15);
                document.getElementById('totalPages').textContent = totalPages;
                
                const currentPage = document.getElementById('currentPage');
                if (parseInt(currentPage.textContent) > totalPages) {
                    currentPage.textContent = totalPages;
                }
            },

            // 리스트 렌더링
            renderApplicationList: (result) => {
                const tbody = document.getElementById('applicationList');
                if (!Array.isArray(result.data)) {
                    tbody.innerHTML = '<tr><td colspan="15">데이터를 불러올 수 없습니다.</td></tr>';
                    return;
                }

                if (result.data.length === 0) {
                    tbody.innerHTML = '<tr><td colspan="15">데이터가 없습니다.</td></tr>';
                    return;
                }

                tbody.innerHTML = result.data.map((item, index) => `
                    <tr>
                        <td class="clickable" onclick="showDetailModal('${item.num}')">${index + 1}</td>
                        <td class="clickable" onclick="showBusinessModal('${item.num}')">${item.school2 || ''}</td>
                        <td>${item.school1 || ''}</td>
                        <td class="text-right">${item.week_total || ''}</td>
                        <td>${item.school4 || ''}</td>
                        <td>${item.wdate?.split(' ')[0] || ''}</td>
                        <td>${item.certi || item.gabunho || ''}</td>
                        <td class="text-right">${Number(item.preiminum || 0).toLocaleString()}</td>
                        <td>
                            <select class="insurance-select" data-id="${item.num}" onchange="handleInsuranceChange(this)">
                                <option value="">선택</option>
                                <option value="1" ${item.inscompany === '1' ? 'selected' : ''}>한화</option>
                                <option value="2" ${item.inscompany === '2' ? 'selected' : ''}>Meritz</option>
                            </select>
                        </td>
                        <td>
                            <select class="status-select" data-id="${item.num}" onchange="handleStatusChange(this)">
                                <option value="1" ${item.ch == 1 ? "selected" : ""}>접수</option>
                                <option value="2" ${item.ch == 2 ? "selected" : ""}>보험료 안내중</option>
                                <option value="3" ${item.ch == 3 ? "selected" : ""}>청약서</option>
                                <option value="4" ${item.ch == 4 ? "selected" : ""}>입금대기중</option>
                                <option value="5" ${item.ch == 5 ? "selected" : ""}>입금확인</option>
                                <option value="6" ${item.ch == 6 ? "selected" : ""}>증권 발급</option>
                                <option value="7" ${item.ch == 7 ? "selected" : ""}>보류</option>
                                <option value="12" ${item.ch == 12 ? "selected" : ""}>수정요청</option>
                            </select>
                        </td>
                        <td>${item.school5 || ''}</td>
                        
                        <td class="clickable" onclick="handleUpload('${item.num}')">업로드</td>
                        <td class="clickable" onclick="handleClaim('${item.num}')">클레임</td>
                        <td>
                            <input 
                                type="text" 
                                class="memo-input" 
                                data-num="${item.num}"
                                value="${item.memo || ''}"
                                onkeyup="handleMemoEnter(event)"
                                placeholder="메모 입력"
                            >
                        </td>
                        <td>${item.manager || ''}</td>
                    </tr>
                `).join('');

                // pages 값 사용
                document.getElementById('totalPages').textContent = result.pages || 1;
                
                const currentPage = document.getElementById('currentPage');
                if (parseInt(currentPage.textContent) > (result.pages || 1)) {
                    currentPage.textContent = result.pages || 1;
                }
            }
        };
    }
})();

// 입력 필드 엔터 이벤트 핸들러
function setupInputHandlers() {
    // 가입설계번호 엔터 이벤트
    document.getElementById('gabunho_').addEventListener('keyup', function(event) {
        if (event.key === 'Enter') {
            const gabunho = event.target.value.trim();
            const num = document.getElementById('questionwareNum_').value;
            const userName = document.getElementById('userName').value;

            if (!gabunho) {
                alert('가입 설계번호를 입력하세요.');
                return;
            }

            updateField('gabunho', gabunho, num, userName);
        }
    });

    // 증권번호 엔터 이벤트
    document.getElementById('certi_').addEventListener('keyup', function(event) {
        if (event.key === 'Enter') {
            const certi = event.target.value.trim();
            const num = document.getElementById('questionwareNum_').value;
            const userName = document.getElementById('userName').value;

            if (!certi) {
                alert('증권번호를 입력하세요.');
                return;
            }

            updateField('certi', certi, num, userName);
        }
    });

    // 카드번호 엔터 이벤트
    document.getElementById('cardNum_').addEventListener('keyup', function(event) {
        if (event.key === 'Enter') {
            const cardnum = event.target.value.trim().replace(/[^0-9]/g, ''); // 숫자만 추출
            if (cardnum.length !== 16) {
                alert('유효한 카드번호 16자리를 입력해주세요.');
                return;
            }
            const num = document.getElementById('cNum_').value;
            const userName = document.getElementById('userName').value;

            if (!userName) {
                alert('사용자 정보를 찾을 수 없습니다. 다시 로그인해주세요.');
                return;
            }

            updateField('cardnum', cardnum, num, userName);
        }
    });

    // 카드 유효기간 엔터 이벤트
    document.getElementById('cardExpiry_').addEventListener('keyup', function(event) {
        if (event.key === 'Enter') {
            const yymm = event.target.value.trim();
            const num = document.getElementById('cNum_').value;

            if (!yymm) {
                alert('유효기간을 입력하세요.');
                return;
            }

            updateField('yymm', yymm, num);
        }
    });

    // 은행계좌 엔터 이벤트
    document.getElementById('bankAccount_').addEventListener('keyup', function(event) {
        if (event.key === 'Enter') {
            const bank = event.target.value.trim().replace(/[^0-9]/g, ''); // 숫자만 추출
            if (bank.length < 10 || bank.length > 14) {
                alert('유효한 계좌번호를 입력해주세요.');
                return;
            }
            const num = document.getElementById('cNum_').value;

            if (!num) {
                alert('카드번호를 입력하세요.');
                return;
            }

            updateField('bank', bank, num);
        }
    });

    // 담당자 연락처 입력 포맷팅
    document.getElementById('managerPhone_').addEventListener('input', function(event) {
        let input = event.target.value.replace(/\D/g, '');
        
        if (input.length === 11) {
            input = input.replace(/(\d{3})(\d{4})(\d{4})/, '$1-$2-$3');
        } else if (input.length === 10) {
            input = input.replace(/(\d{3})(\d{3})(\d{4})/, '$1-$2-$3');
        } else if (input.length === 9) {
            input = input.replace(/(\d{2})(\d{3})(\d{4})/, '$1-$2-$3');
        }
        
        event.target.value = input;
    });
	 document.getElementById('damdangat_').addEventListener('input', function(event) {
        let input = event.target.value.replace(/\D/g, '');
        
        if (input.length === 11) {
            input = input.replace(/(\d{3})(\d{4})(\d{4})/, '$1-$2-$3');
        } else if (input.length === 10) {
            input = input.replace(/(\d{3})(\d{3})(\d{4})/, '$1-$2-$3');
        } else if (input.length === 9) {
            input = input.replace(/(\d{2})(\d{3})(\d{4})/, '$1-$2-$3');
        }
        
        event.target.value = input;
    });

    // 담당자 연락처 엔터 이벤트
    document.getElementById('managerPhone_').addEventListener('keyup', function(event) {
        if (event.key === 'Enter') {
            const inputField = event.target.value.trim();
            const cNum_ = document.getElementById('cNum_').value;

            if (!inputField || !cNum_) {
                alert("연락처와 Num 값을 입력하세요.");
                return;
            }

            updateField('damdangat', inputField, cNum_);
        }
    });
}

// API 호출 함수
async function updateField(field, value, num, userName = '') {
    const apiEndpoints = {
        'gabunho': 'update_gabunho.php',
        'certi': 'update_certi_.php',
        'cardnum': 'update_cardnum.php',
        'yymm': 'update_yymm.php',
        'bank': 'update_bank_account.php',
        'damdanga': 'update_damdanga.php',
        'damdangat': 'update_damdangat.php'
    };

    try {
        const formData = new URLSearchParams();
        formData.append('num', num);
        formData.append(field, value);
        if (userName) formData.append('userName', userName);

        const response = await fetch(`https://lincinsu.kr/2025/api/question/${apiEndpoints[field]}`, {
            method: 'POST',
            headers: {
                'Content-Type': 'application/x-www-form-urlencoded',
            },
            body: formData
        });

        const data = await response.text();
        alert(data.includes('success') ? '저장되었습니다.' : data);
    } catch (error) {
        console.error(`${field} 저장 실패:`, error);
        alert(`${field} 저장 중 오류가 발생했습니다.`);
    }
}

// 모달이 열릴 때 이벤트 핸들러 설정
document.addEventListener('DOMContentLoaded', setupInputHandlers);

// 담당자 엔터 이벤트 핸들러
window.handleManagerEnter = function(event) {
    if (event.key === 'Enter') {
        const damdanga = event.target.value.trim();
        const cNum_ = document.getElementById('cNum_').value;

        // 필수 값 검증
        if (!damdanga || !cNum_) {
            alert("담당자 정보와 Num 값을 입력하세요.");
            return;
        }

        // 프록시를 통한 API 호출
        const apiUrl = encodeURIComponent('https://lincinsu.kr/2025/api/question/update_damdanga.php');
        fetch(`/cms/api/proxy.php?url=${apiUrl}`, {
            method: "POST",
            headers: { 
                "Content-Type": "application/x-www-form-urlencoded" 
            },
            body: `num=${encodeURIComponent(cNum_)}&damdanga=${encodeURIComponent(damdanga)}`,
        })
        .then(response => response.text())
        .then(data => {
            alert(data);
            
            // 성공 시 리스트 새로고침
            if (data.includes('성공')) {
                const searchInput = document.getElementById('searchKeyword');
                if (searchInput && searchInput.value) {
                    searchList();
                } else {
                    fieldPracticeAPI.fetchApplications()
                        .then(result => fieldPracticeAPI.renderApplicationList(result.data));
                }
            }
        })
        .catch(() => alert("담당자 정보 저장 중 오류가 발생했습니다."));
    }
};

// 전화번호 포맷팅 함수
window.formatPhoneNumber = function(event) {
    let input = event.target.value.replace(/\D/g, ""); // 숫자만 남기기

    if (input.length === 11) {
        input = input.replace(/(\d{3})(\d{4})(\d{4})/, "$1-$2-$3");
    } else if (input.length === 10) {
        input = input.replace(/(\d{3})(\d{3})(\d{4})/, "$1-$2-$3");
    } else if (input.length === 9) {
        input = input.replace(/(\d{2})(\d{3})(\d{4})/, "$1-$2-$3");
    }

    event.target.value = input;
};

// 담당자 연락처 엔터 이벤트 핸들러
window.handleManagerPhoneEnter = function(event) {
    if (event.key === 'Enter') {
        const inputField = event.target.value.trim();
        const cNum_ = document.getElementById('cNum_').value;

        if (!inputField || !cNum_) {
            alert("연락처와 Num 값을 입력하세요.");
            return;
        }

        // 프록시를 통한 API 호출
        const apiUrl = encodeURIComponent('https://lincinsu.kr/2025/api/question/update_damdangat.php');
        fetch(`/cms/api/proxy.php?url=${apiUrl}`, {
            method: "POST",
            headers: { 
                "Content-Type": "application/x-www-form-urlencoded" 
            },
            body: `num=${encodeURIComponent(cNum_)}&damdangat=${encodeURIComponent(inputField)}`,
        })
        .then(response => response.text())
        .then(data => {
            console.log("서버 응답:", data);
            alert("담당자 연락처가 성공적으로 저장되었습니다.");
            
            // 성공 시 리스트 새로고침
            if (data.includes('성공')) {
                const searchInput = document.getElementById('searchKeyword');
                if (searchInput && searchInput.value) {
                    searchList();
                } else {
                    fieldPracticeAPI.fetchApplications()
                        .then(result => fieldPracticeAPI.renderApplicationList(result.data));
                }
            }
        })
        .catch(() => alert("담당자 연락처 저장 중 오류가 발생했습니다."));
    }
};

// 무사고 확인서 URL 생성 함수
function question7_mail() {
    const claimNum = document.getElementById("questionwareNum_")?.value;
    return `http://lincinsu.kr/2014/_pages/php/downExcel/claim7.php?claimNum=${encodeURIComponent(claimNum)}`;
}

// 공지사항 선택 처리
document.addEventListener("change", function (event) {
    if (event.target.id === "noticeSelect") {
        const noticeSelect = event.target.value;
        const emailElement = document.getElementById("school_5");
        const email = emailElement ? emailElement.innerText.trim() : "";

        console.log("선택된 공지사항 값:", noticeSelect);
        console.log("이메일 값:", email);

        if (!email || noticeSelect === "-1") {
            alert("이메일과 공지사항을 올바르게 선택하세요.");
            return;
        }

        // globalThis.confirm 사용
       /* if (!globalThis.confirm(`[${email}] 으로 해당 이메일을 발송하시겠습니까?`)) {
            return;
        }*/

        const templates = {
            "1": {
                title: "[한화 현장실습보험] 보험금 청구시 필요서류 안내",
                content: `<div>안녕하십니까.<br><br>
                            현장실습보험 문의에 깊이 감사드립니다.<br><br>
                            1. 보험금 청구서(+필수 동의서) 및 문답서 (첨부파일 참고)<br>
                            * 보험금 청구 기간은 최대 1년까지 가능합니다.<br><br>
                            2. 신분증 및 통장사본<br><br>
                            3. 진단서 또는 초진차트<br><br>
                            4. 병원치료비 영수증(계산서)_치료비세부내역서, 약제비 영수증<br><br>
                            5. 실습기관의 현장실습 출석부 사본 또는 실습일지<br><br>
                            6. 학생 학적을 확인할 수 있는 학교 전산 캡처본<br><br>
                            7. 보험금 청구서 밑의 법정대리인의 서명, 가족관계증명서, 보호자 신분증 및 통장사본<br>
                            (고등학생 현장 실습 사고 접수 경우만 해당)<br><br>
                            위 서류들을 구비하셔서 메일 답장으로 부탁드립니다.<br><br>
                            자세한 사항은 현장실습 홈페이지(<a href='http://lincinsu.kr/'>http://lincinsu.kr/</a>)의 보상안내, 공지사항에서도 확인하실 수 있습니다.
                            <br><br>감사합니다.<br><br><hr>
                            <p style='font-size: 8px; color: #00A000;'>이투엘보험대리점</p>
                            <p style='font-size: 8px; color: #00A000;'>현장실습보험지원팀</p>
                            <p style='font-size: 8px; color: #00A000;'>1533-5013</p><br>
                            현장실습보험은 <span style='color: #FB2C10;'>한화손해보험</span>에서 제공합니다.</div>`,
                attachfile: "./static/lib/attachfile/보험금 청구서,동의서,문답서_2023.pdf",
            },
            "2": {
                title: "[이용안내문] 한화 현장실습 보험 이용 안내문",
                content: `<div>안녕하십니까.<br><br>
                            현장실습보험 문의에 깊이 감사드립니다.<br><br>
                            현장실습 이용방법이 담긴 안내문 첨부파일로 전달드립니다.<br><br>
                            <a href="http://lincinsu.kr/">현장실습 홈페이지 바로가기</a><br><br>
                            감사합니다.<br><br><hr>
                            <p style='font-size: 8px; color: #00A000;'>이투엘보험대리점</p>
                            <p style='font-size: 8px; color: #00A000;'>현장실습보험지원팀</p>
                            <p style='font-size: 8px; color: #00A000;'>1533-5013</p><br>
                            현장실습보험은 <span style='color: #FB2C10;'>한화손해보험</span>에서 제공합니다.</div>`,
                attachfile: "./static/lib/attachfile/한화 현장실습 보험 안내 팜플렛.pdf",
            },
            "3": {
                title: "[한화 현장실습보험] 무사고 확인서 요청",
                content: (() => {
                    var musagourl = question7_mail();
                    console.log("무사고 확인서 링크:", musagourl);
                    return `<div>
                                안녕하십니까.<br><br>
                                보험 시작일이 설계일보다 앞서 무사고 확인서를 전달드립니다.<br><br>
                                첨부된 파일의 입금일에 입금 또는 카드결제하실 날짜 기입 후<br><br>
                                하단에 명판직인 날인하여 회신 주시면 청약서 발급 후 전달드리겠습니다.<br><br>
                                하기 링크 확인 부탁드립니다.<br><br>
                                <a href='https://www.lincinsu.kr/${musagourl}'>무사고 확인서 링크</a><br><br>
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
            return;
        }

        // 프록시를 통한 API 호출
        const formData = new FormData();
        formData.append("email", email);
        formData.append("title", selectedTemplate.title);
        formData.append("content", selectedTemplate.content);
        formData.append("attachfile", selectedTemplate.attachfile);

        const url = noticeSelect === "3"
            ? "https://lincinsu.kr/2025/api/musagoNotice.php"
            : "https://lincinsu.kr/2025/api/notice.php";

        const apiUrl = encodeURIComponent(url);
        fetch(`/cms/api/proxy.php?url=${apiUrl}`, {
            method: "POST",
            body: formData,
        })
        .then(response => response.text())
        .then(data => {
            console.log("서버 응답:", data);
            alert("메일이 성공적으로 발송되었습니다.");
        })
        .catch(error => {
            console.error("메일 전송 오류:", error);
            alert("메일 전송 중 오류가 발생했습니다.");
        });
    }
});

// 비즈니스 모달 닫기 함수 추가
function closeBusinessModal() {
    const modal = document.getElementById('business-modal');
    if (modal) {
        modal.style.display = 'none';
    }
}



// showBusinessModal 함수 수정
window.showBusinessModal = async function(num) {
    try {
        // 모달 요소 가져오기
        const modalContainer = document.getElementById('modal-container');
        const businessModal = document.getElementById('business-modal');
        
        if (!modalContainer || !businessModal) {
            throw new Error('모달 요소를 찾을 수 없습니다.');
        }

        // 다른 모달들 닫기
        const detailModal = document.getElementById('detail-modal');
        const claimModal = document.querySelector('.claimModal');
        if (detailModal) detailModal.style.display = 'none';
        if (claimModal) claimModal.style.display = 'none';

        // userName 가져오기
        const userName = sessionStorage.getItem('userName') || localStorage.getItem('userName');
        if (!userName) {
            throw new Error('사용자 정보를 찾을 수 없습니다. 다시 로그인해주세요.');
        }

        // API 호출을 통해 데이터 가져오기
        const params = new URLSearchParams({
            id: num,
            userName: encodeURIComponent(userName)
        });

        const targetUrl = `https://lincinsu.kr/2025/api/question/get_questionnaire_details.php?${params.toString()}`;
        const response = await fetch(`/cms/api/proxy.php?url=${encodeURIComponent(targetUrl)}`);
        
        if (!response.ok) {
            throw new Error(`HTTP error! status: ${response.status}`);
        }

        const data = await response.json();
        if (!data.success || !data.data) {
            throw new Error(data.error || '데이터를 불러올 수 없습니다.');
        }

        // write_ 버튼 설정
        const writeButton = document.getElementById('write_');
        if (writeButton) {
            writeButton.textContent = data.data.num ? "수정" : "작성";
            writeButton.onclick = handleWriteClick;
        }

        // 데이터 바인딩 함수
        const setFieldValue = (id, value) => {
            const element = document.getElementById(id);
            if (element) {
                if (element.tagName === 'SPAN') {
                    element.textContent = value || '';
                } else {
                    element.value = value || '';
                }
            }
        };

        // 히든 필드 설정
        setFieldValue("questionwareNum", data.data.num);
        setFieldValue("school9", data.data.school9);
        setFieldValue("inscompany", data.data.inscompany);

        // 계약자 정보 설정
        setFieldValue("school1", data.data.school1);
        setFieldValue("school2", data.data.school2);
        setFieldValue("school3", data.data.school3);
        setFieldValue("school4", data.data.school4);
        setFieldValue("school5", data.data.school5);

        // 현장실습 시기 라디오 버튼 설정
        const school6Radio = document.querySelector(`input[name='school6'][value="${data.data.school6}"]`);
        if (school6Radio) school6Radio.checked = true;

        // 실습기간 설정
        setFieldValue("school7", data.data.school7);
        setFieldValue("school8", data.data.school8);

        // 가입유형 라디오 버튼 설정
        const planRadio = document.querySelector(`input[name='plan'][value="${data.data.school9}"]`);
        if (planRadio) planRadio.checked = true;

        // 주차별 참여인원 행 생성 및 설정
        const weekRowsContainer = document.getElementById('weekRows');
        if (weekRowsContainer) {
            let weekRowsHTML = '';
            for (let i = 4; i <= 15; i++) {
                weekRowsHTML += `
                    <tr>
                        <th class='walign'>${i}주</th>
                        <td><input type="text" class="week-input" id="week${i}" value="${data.data[`week${i}`] || '0'}" onchange="calculateTotal()"></td>
                        <th class='walign'>${i + 12}주</th>
                        <td><input type="text" class="week-input" id="week${i + 12}" value="${data.data[`week${i + 12}`] || '0'}" onchange="calculateTotal()"></td>
                    </tr>
                `;
            }
            weekRowsContainer.innerHTML = weekRowsHTML;
        }

        // 초기 합계 계산
        calculateTotal();
        setFieldValue("daein", data.daeinP);
        setFieldValue("daemool", data.daemoolP);
        setFieldValue("totalP", data.preiminum);

        // 모달 표시
        businessModal.style.display = 'block';
        modalContainer.style.display = 'block';
        modalContainer.classList.add('active');
        
        // 날짜 입력 초기화
        setupDateInputs();
        
    } catch (error) {
        console.error('상세 정보 조회 실패:', error);
        alert('상세 정보를 불러오는 중 오류가 발생했습니다: ' + error.message);
    }
};

// 모달 외부 클릭 시 닫기
document.addEventListener('click', function(event) {
    const modal = document.getElementById('business-modal');
    if (event.target === modal) {
        closeBusinessModal();
    }
});

// ESC 키로 모달 닫기
document.addEventListener('keydown', function(event) {
    if (event.key === 'Escape') {
        closeBusinessModal();
    }
});

// 파일 상단에 API URL 상수 추가
const API_BASE_URL = 'https://lincinsu.kr/2025/api';
const API_ENDPOINTS = {
    QUESTIONNAIRE_DETAILS: `${API_BASE_URL}/question/get_questionnaire_details.php`,
    UPDATE_INSURANCE: `${API_BASE_URL}/question/update_insurance.php`,
    UPDATE_STATUS: `${API_BASE_URL}/question/update_status.php`,
    // ... 다른 엔드포인트들
};

// 이벤트 핸들러 모듈
const eventHandlers = {
    handleGabunhoEnter: async function(event) {
        // 기존 코드
    },
    
    handleCardNumberEnter: async function(event) {
        // 기존 코드
    },
    
    // ... 다른 핸들러들
};

// 이벤트 리스너 등록
function setupEventListeners() {
    document.getElementById('gabunho_')?.addEventListener('keyup', eventHandlers.handleGabunhoEnter);
    document.getElementById('cardNum_')?.addEventListener('keyup', eventHandlers.handleCardNumberEnter);
    // ... 다른 리스너들
}

// 초기화 시 이벤트 리스너 설정
document.addEventListener('DOMContentLoaded', setupEventListeners);

// 입력값 유효성 검사를 위한 이벤트 핸들러 추가
document.addEventListener('input', function(event) {
    if (event.target.classList.contains('week-input')) {
        // 숫자만 입력 가능하도록
        event.target.value = event.target.value.replace(/[^0-9]/g, '');
        // 합계 재계산
        calculateTotal();
    }
});

// write_ 버튼 클릭 이벤트 핸들러
window.handleWriteClick = async function(e) {
    e.preventDefault();
    
    // 결과값 초기화
    document.getElementById("daein").textContent = "";
    document.getElementById("daemool").textContent = "";
    document.getElementById("week_total").textContent = "";
    document.getElementById("totalP").textContent = "";

    try {
        // 폼 데이터 수집
        const formData = {
            id: document.getElementById("questionwareNum").value,
            school1: document.getElementById("school1").value,
            school2: document.getElementById("school2").value,
            school3: document.getElementById("school3").value,
            school4: document.getElementById("school4").value,
            school5: document.getElementById("school5").value,
            school6: document.querySelector("input[name='school6']:checked")?.value,
            school7: document.getElementById("school7").value,
            school8: document.getElementById("school8").value,
            school9: document.getElementById("school9").value,
            plan: document.querySelector("input[name='plan']:checked")?.value,
            totalP: document.getElementById("totalP").textContent.replace(/,/g, "")
        };

        // 주차별 데이터 추가
        for (let i = 4; i <= 26; i++) {
            formData[`week${i}`] = document.getElementById(`week${i}`).value.replace(/,/g, "");
        }

        // 필수 값 검증
        if (!formData.school6) {
            alert("현장실습시기를 선택해주세요.");
            return;
        }
        if (!formData.plan) {
            alert("가입유형을 선택해주세요.");
            return;
        }

        // API 호출
        const apiUrl = encodeURIComponent('https://lincinsu.kr/2025/api/question/update_questionnaire.php');
        const response = await fetch(`/cms/api/proxy.php?url=${apiUrl}`, {
            method: "POST",
            headers: { 
                "Content-Type": "application/x-www-form-urlencoded" 
            },
            body: new URLSearchParams(formData)
        });

        const data = await response.json();
        
        if (data.success) {
            alert("수정되었습니다.");
            // 결과값 업데이트
            document.getElementById("daein").textContent = data.daeinP;
            document.getElementById("daemool").textContent = data.daemoolP;
            document.getElementById("week_total").textContent = data.week_total;
            document.getElementById("totalP").textContent = data.Preminum;
        } else {
            throw new Error(data.error || "수정에 실패했습니다.");
        }
    } catch (error) {
        console.error('수정 실패:', error);
        alert(error.message || "수정 요청 중 오류가 발생했습니다.");
    }
};

// 주차별 행 생성 함수 추가
function generateWeekRows() {
    let rows = '';
    for (let i = 4; i <= 15; i++) {
        rows += `
            <tr>
                <th class='walign'>${i}주</th>
                <td><input type="text" class="week-input" id="week${i}" onchange="calculateTotal()"></td>
                <th class='walign'>${i + 12}주</th>
                <td><input type="text" class="week-input" id="week${i + 12}" onchange="calculateTotal()"></td>
            </tr>
        `;
    }
    return rows;
}

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

// 스타일 요소 수정
    const styleElement = document.createElement('style');
    styleElement.textContent = `
        .modal {
            display: none;
            position: fixed;
            z-index: 1000;
            left: 0;
            top: 0;
            width: 100%;
            height: 100%;
            background-color: rgba(0,0,0,0.5);
            overflow-y: auto;
        }

        .modal-content {
            background-color: #fefefe;
            margin: 1% auto;
            padding: 20px;
            border: 1px solid #888;
            width: 90%;
            max-width: 800px;
            border-radius: 5px;
        }

        .modal-header {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-bottom: 0px;
        }

    .modal h2 {
        margin: 0;
        color: #333;
        font-size: 18px;
        }

        .close {
            color: #aaa;
            float: right;
            font-size: 28px;
            font-weight: bold;
            cursor: pointer;
        }

        .close:hover {
            color: #000;
        }

        .modal h5 {
            color: #333;
            margin: 20px 0 10px;
        font-size: 14px;
            font-weight: bold;
        }

        .modal table {
            width: 100%;
            border-collapse: collapse;
            margin-bottom: 10px;
        }

        .modal th, .modal td {
            border: 1px solid #ddd;
        padding: 6px 8px;
        font-size: 12px;
        }

        .modal th {
            background-color: #f5f5f5;
            font-weight: bold;
        text-align: center;
        vertical-align: middle;
        color: #333;
    }

    .modal input[type="text"],
    .modal input[type="number"],
    .modal textarea {
        width: 100%;
        padding: 3px 6px;
        border: 1px solid #fff;
        border-radius: 3px;
        font-size: 12px;
        }

        .etc-input {
            width: 100%;
        padding: 3px 6px;
            border: 1px solid #fff;
            border-radius: 3px;
        font-size: 12px;
    }

    .week-input {
        width: 100%;
        padding: 3px 6px;
        border: 1px solid #fff;
        text-align: right;
        border-radius: 3px;
        font-size: 12px;
        }

        .radio-group {
            display: flex;
            gap: 15px;
        justify-content: center;
        align-items: center;
        height: 24px;
        }

        .radio-label {
            display: flex;
            align-items: center;
        gap: 4px;
        font-size: 12px;
            cursor: pointer;
        }

        .right-align {
            text-align: right;
        padding-right: 15px;
        }

    .btn-primary {
        background-color: #007bff;
        color: white;
            border: none;
        padding: 6px 12px;
            border-radius: 4px;
            cursor: pointer;
        font-size: 12px;
        }

        .btn-primary:hover {
            background-color: #0056b3;
        }

        .walign {
            text-align: center;
        vertical-align: middle;
    }

    .info-table th {
            width: 200px;
    }
`;

// 기존 스타일 제거 후 새로운 스타일 추가
    const existingStyle = document.getElementById('business-modal-style');
    if (existingStyle) {
        existingStyle.remove();
    }
    styleElement.id = 'business-modal-style';
    document.head.appendChild(styleElement);

// 날짜 입력 이벤트 핸들러 추가
function setupDateInputs() {
    const school7Input = document.getElementById('school7');
    const school8Input = document.getElementById('school8');

    if (school7Input && school8Input) {
        // 시작일이 변경되면 종료일의 최소값을 설정
        school7Input.addEventListener('change', function() {
            school8Input.min = this.value;
        });

        // 종료일이 변경되면 시작일의 최대값을 설정
        school8Input.addEventListener('change', function() {
            school7Input.max = this.value;
        });
    }
}

// 메모 엔터 이벤트 핸들러
window.handleMemoEnter = async function(event) {
    if (event.key === 'Enter') {
        const memo = event.target.value.trim();
        const num = event.target.dataset.num;

        if (!memo) {
            alert("메모를 입력해주세요.");
            return;
        }

        try {
            // 프록시를 통한 API 호출
            const apiUrl = encodeURIComponent('https://lincinsu.kr/2025/api/question/update_memo.php');
            const response = await fetch(`/cms/api/proxy.php?url=${apiUrl}`, {
                method: "POST",
                headers: { 
                    "Content-Type": "application/x-www-form-urlencoded" 
                },
                body: `num=${encodeURIComponent(num)}&memo=${encodeURIComponent(memo)}`
            });

            const data = await response.json();
            
            if (data.success) {
                alert("메모가 성공적으로 수정되었습니다.");
                
                // 리스트 새로고침
                const searchInput = document.getElementById('searchKeyword');
                if (searchInput && searchInput.value) {
                    await searchList();
                } else {
                    await fieldPracticeAPI.fetchApplications()
                        .then(result => fieldPracticeAPI.renderApplicationList(result.data));
                }
            } else {
                throw new Error(data.error || "메모 수정 중 오류가 발생했습니다.");
            }
        } catch (error) {
            console.error('메모 업데이트 실패:', error);
            alert("메모 업데이트 중 오류가 발생했습니다.");
        }
    }
};

// mem_id 동적 로드 부분
function loadMemIds() {
    // apiUrl을 다른 이름으로 변경
    const memApiUrl = encodeURIComponent('https://lincinsu.kr/2025/api/question/get_idList.php');
    fetch(`/cms/api/proxy.php?url=${memApiUrl}`)
        .then(response => response.json())
        .then(memData => {
            const select = document.getElementById('mem-id-select');
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
            select.value = document.getElementById('cNum_').value;
        })
        .catch(() => alert("mem_id 데이터를 가져오는 데 실패했습니다."));
}

// menu.js에서 사용할 수 있도록 전역으로 노출
window.fieldPracticeAPI = fieldPracticeAPI;

// 초기 로드 함수
window.loadFieldPracticeList = async function() {
    try {
        // 로딩 상태 표시
        const tbody = document.getElementById('applicationList');
        if (tbody) {
            tbody.innerHTML = '<tr><td colspan="15">데이터를 불러오는 중...</td></tr>';
        }

        const result = await fieldPracticeAPI.fetchApplications();
        
        // 응답 데이터 검증
        if (!result) {
            throw new Error('서버 응답이 없습니다.');
        }

        // 데이터가 없는 경우도 정상적으로 처리
        if (!result.data || !Array.isArray(result.data)) {
            console.log('데이터가 없거나 올바르지 않은 형식:', result);
            fieldPracticeAPI.renderApplicationList([]);
            return;
        }
        
        fieldPracticeAPI.renderApplicationList(result);
        
    } catch (error) {
        console.error('현장실습보험 리스트 로드 실패:', error);
        const tbody = document.getElementById('applicationList');
        if (tbody) {
            tbody.innerHTML = `<tr><td colspan="15">데이터를 불러오는 중 오류가 발생했습니다.<br>${error.message}</td></tr>`;
        }
    }
};

// 클레임 처리 함수
window.handleClaim = async function(num) {
    try {
        // 클레임 모달 가져오기
        const claimModal = document.getElementById('claim-modal');
        if (!claimModal) {
            throw new Error('클레임 모달을 찾을 수 없습니다.');
        }

        // 다른 모달들 닫기
        const detailModal = document.getElementById('detail-modal');
        const businessModal = document.getElementById('business-modal');
        if (detailModal) detailModal.style.display = 'none';
        if (businessModal) businessModal.style.display = 'none';

        // userName 가져오기
        const userName = sessionStorage.getItem('userName') || localStorage.getItem('userName');
        if (!userName) {
            throw new Error('사용자 정보를 찾을 수 없습니다. 다시 로그인해주세요.');
        }

        // API 호출을 통해 데이터 가져오기
        const params = new URLSearchParams({
            id: num,
            userName: encodeURIComponent(userName)
        });

        const targetUrl = `https://lincinsu.kr/2025/api/question/get_questionnaire_details.php?${params.toString()}`;
        const response = await fetch(`/cms/api/proxy.php?url=${encodeURIComponent(targetUrl)}`);
        
        if (!response.ok) {
            throw new Error(`HTTP error! status: ${response.status}`);
        }

        const data = await response.json();
        if (!data.success || !data.data) {
            throw new Error(data.error || '데이터를 불러올 수 없습니다.');
        }

        const responseData = data.data;

        // 데이터 바인딩
        document.getElementById("questionNum__").value = num;
        document.getElementById("cNum__").value = responseData.cNum || '';
        document.getElementById("certi__").textContent = responseData.certi || '';
        document.getElementById("school_1_").textContent = responseData.school1 || '';
        document.getElementById("school_2_").textContent = responseData.school2 || '';
        document.getElementById("school_3_").textContent = responseData.school3 || '';
        document.getElementById("school_4_").textContent = responseData.school4 || '';
        document.getElementById("school_5_").textContent = responseData.school5 || '';

        // 현장실습 시기 설정
        const periods = { "1": "1학기", "2": "하계", "3": "2학기", "4": "동계" };
        document.getElementById("school_6_").textContent = periods[responseData.school6] || "알 수 없음";

        // 실습기간 설정
        document.getElementById("school_7_").textContent = responseData.school7 || '';
        document.getElementById("school_8_").textContent = responseData.school8 || '';

        // 가입유형 설정
        document.getElementById("school_9_").textContent = 
            responseData.school9 == 1 ? "가입유형 A" : "가입유형 B";

        // 대인대물 한도 설정
        const limit = responseData.school9 == 1 ? "2 억" : "3 억";
        document.getElementById("daein1__").textContent = limit;
        document.getElementById("daein2__").textContent = limit;

        // 모달 표시
        claimModal.style.display = 'block';
        claimModal.style.zIndex = '9999';

        // 모달 컨테이너도 표시
        const modalContainer = document.getElementById('modal-container');
        if (modalContainer) {
            modalContainer.style.display = 'block';
            modalContainer.classList.add('active');
        }

		// 초기화 
		document.getElementById("student").value='';
		document.getElementById("wdate_3").value='';
		document.getElementById("claimNumber").value='';
		document.getElementById("wdate_2").value='';
		document.getElementById("claimAmout").value='';
		document.getElementById("accidentDescription").value='';
		document.getElementById("damdanga_").value='';
		document.getElementById("damdangat_").value='';

    } catch (error) {
        console.error('클레임 데이터 로드 실패:', error);
        alert(error.message || "클레임 데이터를 불러오는데 실패했습니다.");
    }
};


// 업로드 처리 함수 수정
window.handleUpload = function(num, event) {
    if (event) {
        event.preventDefault();
        event.stopPropagation();
    }

    try {
        // 다른 모달들 모두 닫기
        const allModals = document.querySelectorAll('.modal');
        allModals.forEach(modal => {
            if (modal) {
                modal.style.display = 'none';
            }
        });

        const uploadModal = document.getElementById("uploadModal");
        const modalContainer = document.getElementById('modal-container');
			//초기화 
        document.getElementById("cName").innerHTML ='';
		document.getElementById("bunho").innerHTML = '';
	
		document.getElementById("file_list1").innerHTML = ''; // 머리말
        document.getElementById("file_list").innerHTML ='';


        if (uploadModal && modalContainer) {
            document.getElementById("qNum").value = num;
            
            // 학교 정보 로드
            fetch(`https://lincinsu.kr/2025/api/question/get_questionnaire_details.php?id=${num}`)
                .then(response => response.json())
                .then(data => {
                    if (data.success) {
        uploadModal.style.display = 'block';
                        modalContainer.style.display = 'block';
                        modalContainer.classList.add('active');
                        
                        document.getElementById("cName").innerHTML = data.data.school1;
						document.getElementById("bunho").innerHTML = data.data.gabunho;
                        
                        // 파일 업로드 UI 초기화
                        dynamiFileUpload();
                        // 파일 목록 로드
                        fileSearch(num);
                    } else {
                        alert(data.error);
                    }
                })
                .catch(() => {
                    alert("데이터 로드 실패.");
                });
        }
    } catch (error) {
        console.error('업로드 모달 표시 실패:', error);
        alert('업로드 모달을 표시하는데 실패했습니다.');
    }
};

// 업로드 확인 함수
window.confirmUpload = async function() {
    try {
        const num = window.currentUploadNum;
        if (!num) {
            throw new Error('문서 번호가 없습니다.');
        }

        // 파일 선택 input 생성
        const fileInput = document.createElement('input');
        fileInput.type = 'file';
        fileInput.accept = '.pdf,.doc,.docx,.xls,.xlsx'; // 허용할 파일 형식 지정
        
        fileInput.onchange = async function(e) {
            const file = e.target.files[0];
            if (!file) return;

            const formData = new FormData();
            formData.append('file', file);
            formData.append('num', num);

            try {
                const apiUrl = encodeURIComponent('https://lincinsu.kr/2025/api/upload_file.php');
                const response = await fetch(`/cms/api/proxy.php?url=${apiUrl}`, {
                    method: 'POST',
                    body: formData
                });

                const result = await response.json();
                if (result.success) {
                    alert('파일이 성공적으로 업로드되었습니다.');
                    // 리스트 새로고침
                    if (typeof fieldPracticeAPI !== 'undefined' && fieldPracticeAPI.fetchApplications) {
                        const result = await fieldPracticeAPI.fetchApplications();
                        fieldPracticeAPI.renderApplicationList(result.data);
                    }
                } else {
                    throw new Error(result.message || '업로드 실패');
                }
            } catch (error) {
                console.error('업로드 오류:', error);
                alert('파일 업로드 중 오류가 발생했습니다.');
            }

            Modal.hide('upload-modal');
        };

        fileInput.click();
    } catch (error) {
        console.error('업로드 실패:', error);
        alert(error.message || '업로드 중 오류가 발생했습니다.');
    }
};

// Modal 객체 수정
window.Modal = {
    hide: function(modalId) {
        const modal = document.getElementById(modalId);
        if (modal) {
            if (modalId === 'stats-modal') {
                // 통계 모달은 숨기기만 함
                modal.style.display = 'none';
            } else {
                // 다른 모달은 제거
            modal.remove();
            }
        }
        
        // 모달 컨테이너 숨기기 (다른 열린 모달이 없는 경우)
        const modalContainer = document.getElementById('modal-container');
        const otherOpenModals = document.querySelectorAll('.modal[style*="display: block"]');
        if (modalContainer && otherOpenModals.length === 0) {
            modalContainer.style.display = 'none';
            modalContainer.classList.remove('active');
        }
    },
    show: function(modalId) {
        const modal = document.getElementById(modalId);
        if (modal) {
            modal.style.display = 'block';
            const modalContainer = document.getElementById('modal-container');
            if (modalContainer) {
                modalContainer.style.display = 'block';
                modalContainer.classList.add('active');
            }

            // 통계 모달인 경우 이벤트 리스너 재설정
            if (modalId === 'stats-modal') {
                const yearSelect = document.getElementById("yearSelect");
                const monthSelect = document.getElementById("monthSelect");
                if (yearSelect && monthSelect) {
                    yearSelect.addEventListener("change", fetchSelectedPerformanceData);
                    monthSelect.addEventListener("change", fetchSelectedPerformanceData);
                }
            }
        }
    }
};

// 통계 모달 표시 함수 수정
window.showStats = async function() {
    try {
        // 다른 모달들 모두 닫기
        Modal.hide('detail-modal');
        Modal.hide('business-modal');
        Modal.hide('claim-modal');
        
        // 통계 모달 표시
        Modal.show('stats-modal');  // Modal.show() 사용
        
        daily();
        

    } catch (error) {
        console.error('통계 데이터 로드 실패:', error);
        document.querySelectorAll('.stats-value').forEach(el => {
            el.innerHTML = '데이터 로드 실패';
        });
    }
};

function daily(){
	// 연도 및 월 선택 드롭다운 생성
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

        // change 이벤트 리스너 추가
        yearDropdown.addEventListener('change', fetchSelectedPerformanceData);
        monthDropdown.addEventListener('change', fetchSelectedPerformanceData);

        // 초기 데이터 로드
        fetchSelectedPerformanceData();

        // 드롭다운 이벤트 리스너 추가
        yearDropdown.addEventListener('mousedown', (e) => {
            e.stopPropagation();
        });

        monthDropdown.addEventListener('mousedown', (e) => {
            e.stopPropagation();
        });

        // change 이벤트도 추가
        yearDropdown.addEventListener('change', (e) => {
            e.stopPropagation();
            fetchSelectedPerformanceData();
        });

        monthDropdown.addEventListener('change', (e) => {
            e.stopPropagation();
            fetchSelectedPerformanceData();
        });

        // 모달 하단 버튼 추가
        const footerContainer = document.getElementById("changeP");
        footerContainer.innerHTML = ""; // 기존 내용 초기화

        let ptr = "";
        ptr += `<button id="downloadExcel" class="p-btn">최근 1년 실적 다운로드</button>`;
        ptr += `<button id="monthsBtn" class="p-btn" onclick="yearPerFormance()">월별 실적</button>`;
		footerContainer.innerHTML = ptr;
}


// 테이블 렌더링 함수 수정
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
                <div>${item.day_}(${weekDays[dayOfWeek]})</div> <!-- 날짜 옆에 요일 추가 -->
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

// 통계 모달 닫기 함수 수정
window.closeStatsModal = function() {
    Modal.hide('stats-modal');  // Modal.hide() 사용
};

// ESC 키 이벤트 리스너 수정
document.addEventListener('keydown', function(event) {
    if (event.key === 'Escape') {
        closeModal(); // 하나의 closeModal 함수로 통합
    }
});

// 모달 외부 클릭 이벤트 리스너 수정
document.addEventListener('click', function(event) {
    const modalContainer = document.getElementById('modal-container');
    const statsModal = document.getElementById('stats-modal');
    const filterContainer = document.querySelector('.filter-container');
    
    // 모달 내부 클릭은 무시
    if (statsModal && statsModal.contains(event.target)) {
        return;
    }
    
    // 필터 컨테이너 클릭은 무시
    if (filterContainer && filterContainer.contains(event.target)) {
        return;
    }
    
    // 모달 컨테이너 외부 클릭 시에만 닫기
    if (event.target === modalContainer) {
        Modal.hide('stats-modal');
    }
});

// fetchSelectedPerformanceData 함수 추가
function fetchSelectedPerformanceData() {
    const selectedYear = document.getElementById("yearSelect").value;
    const selectedMonth = document.getElementById("monthSelect").value;

    if (selectedYear === "-1" || selectedMonth === "-1") {
        return; // 연도 또는 월이 선택되지 않으면 실행 X
    }

    // 선택한 연도 및 월의 시작일과 종료일 계산
    const startDate = `${selectedYear}-${selectedMonth}-01`;
    const endDate = new Date(selectedYear, selectedMonth, 0).toISOString().split("T")[0];

    fetch(`https://lincinsu.kr/2025/api/question/performance_1.php?start=${startDate}&end=${endDate}`)
        .then(response => response.json())
        .then(data => {
            renderTable(data, startDate, endDate);
        })
        .catch(error => {
            console.error("🚨 데이터 로드 오류:", error);
        });
}

// 연간 실적 함수 추가
window.yearPerFormance = function() {
    // 기존 모달 내용 초기화
    document.getElementById("changeP").innerHTML = "";
    document.querySelector("#performanceTable tbody").innerHTML = "";
    document.querySelector("#performanceSummary").innerHTML = "";

    // 연도 및 월 선택 초기화
    const yearContainer = document.getElementById("yearSelect_");
    const monthContainer = document.getElementById("monthSelect_");
    if (yearContainer) yearContainer.innerHTML = "";
    if (monthContainer) monthContainer.innerHTML = "";

    // 현재 날짜 기준 연도 설정
    const today = new Date();
    const currentYear = today.getFullYear();

    // 연도 선택 드롭다운 생성
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
            option.selected = true;
        }
        yearDropdown.appendChild(option);
    }

    // 드롭다운 추가 및 이벤트 설정
    if (yearContainer) {
        yearContainer.appendChild(yearDropdown);
        yearDropdown.addEventListener("change", fetchYearlyPerformance);
    }

    // 초기 데이터 로드
    fetchYearlyPerformance();
};

// 연간 데이터 조회 함수
function fetchYearlyPerformance() {
    const selectedYear = document.getElementById("yearSelect").value;
    console.log(`📌 ${selectedYear}년 & ${selectedYear - 1}년 데이터 조회`);

    fetch(`https://lincinsu.kr/2025/api/question/performance_yearly.php?year=${selectedYear}`)
        .then(response => response.json())
        .then(data => {
            renderYearlyTable(data, selectedYear);
        })
        .catch(error => {
            console.error("🚨 연간 데이터 로드 오류:", error);
        });
}

// 연간 데이터 테이블 렌더링 함수
function renderYearlyTable(data, year) {
	document.querySelector("#performanceSummary").innerHTML ="연간 실적";
    const tableBody = document.querySelector("#performanceTable tbody");
    tableBody.innerHTML = "";

    console.log("📊 조회된 월별 보험료 데이터:", data);

    let totalGunsuYear = 0;
    let totalSumYear = 0;
    let totalGunsuPrevYear = 0;
    let totalSumPrevYear = 0;

    let yearData = data.filter(item => item.year && item.month && parseInt(item.year) === parseInt(year));
    let prevYearData = data.filter(item => item.year && item.month && parseInt(item.year) === parseInt(year) - 1);

    let mergedData = [];

    for (let month = 1; month <= 12; month++) {
        let monthFormatted = month < 10 ? `0${month}` : `${month}`;

        let yearItem = yearData.find(item => parseInt(item.month) === parseInt(monthFormatted)) || { gunsu: 0, total_sum: 0 };
        let prevYearItem = prevYearData.find(item => parseInt(item.month) === parseInt(monthFormatted)) || { gunsu: 0, total_sum: 0 };

        mergedData.push({
            month: monthFormatted,
            yearMonth: `${year}-${monthFormatted}`,
            prevYearMonth: `${year - 1}-${monthFormatted}`,
            yearGunsu: Number(yearItem.gunsu) === 0 ? "" : yearItem.gunsu,
            prevYearGunsu: Number(prevYearItem.gunsu) === 0 ? "" : prevYearItem.gunsu,
            yearTotal: Number(yearItem.total_sum) > 0 ? Number(yearItem.total_sum).toLocaleString() + " 원" : "",
            prevYearTotal: Number(prevYearItem.total_sum) > 0 ? Number(prevYearItem.total_sum).toLocaleString() + " 원" : ""
        });

        totalGunsuYear += parseInt(yearItem.gunsu) || 0;
        totalSumYear += parseInt(yearItem.total_sum) || 0;
        totalGunsuPrevYear += parseInt(prevYearItem.gunsu) || 0;
        totalSumPrevYear += parseInt(prevYearItem.total_sum) || 0;
    }

    // 데이터 테이블에 추가
	 const thRow = document.createElement("tr");
	 thRow.innerHTML = `
            <th width='25%'>년월</th>
            <th width='25%'>보험료(건수)</th>
           <th width='25%'>년월</th>
           <th width='25%'>보험료(건수)</th>
        `;
	tableBody.appendChild(thRow);
    mergedData.forEach(item => {
        const row = document.createElement("tr");
        row.innerHTML = `
            <th><div class='y_month_'>${item.yearMonth}</div></th>
            <td><div class='y_month_2'>${item.yearTotal} (${item.yearGunsu}건)</div></td>
            <th><div class='y_month_'>${item.prevYearMonth}</div></th>
            <td><div class='y_month_2'>${item.prevYearTotal} (${item.prevYearGunsu}건)</div></td>
        `;
        tableBody.appendChild(row);
    });

    // 합계 행 추가
    const totalRow = document.createElement("tr");
    totalRow.innerHTML = `
        <th><div class='y_month_'><strong>📊 ${year}년 총합계</strong></div></th>
        <td><div class='y_month_2'><strong>${totalSumYear ? totalSumYear.toLocaleString() + " 원" : ""} (${totalGunsuYear}건)</strong></div></td>
        <th><div class='y_month_'><strong>📊 ${year - 1}년 총합계</strong></div></th>
        <td><div class='y_month_2'><strong>${totalSumPrevYear ? totalSumPrevYear.toLocaleString() + " 원" : ""} (${totalGunsuPrevYear}건)</strong></div></td>
    `;
    tableBody.appendChild(totalRow);

    insertFooterButtons2();
}

// 푸터 버튼 추가 함수
function insertFooterButtons2() {
    const footerContainer = document.getElementById("changeP");
    footerContainer.innerHTML = "";

    let ptr = "";
    ptr += `<button id="downloadExcel" class="p-btn">최근 1년 실적 다운로드</button>`;
    ptr += `<button id="monthsBtn" class="p-btn" onclick="daily()">일별 실적</button>`;
    footerContainer.innerHTML = ptr;
}



// 통계 모달 스타일 정의 - 하나로 통합
const statsModalStyle = document.createElement('style');
statsModalStyle.id = 'stats-modal-style';
statsModalStyle.textContent = `
    .y_month_ {
        padding: 0px;
        font-weight: normal;
    }
    
    .y_month_2 {
        text-align: right;
        padding: 0px;
    }
    
    .stats-modal td {
        padding-right: 0px;
    }
    
    #performanceTable th {
        background-color: #f8f9fa;
        border: 1px solid #dee2e6;
        padding: 8px;
    }
    
    #performanceTable td {
        border: 1px solid #dee2e6;
        padding: 8px;
    }

    #performanceTable {
        width: 100%;
        border-collapse: collapse;
    }
`;

// 기존 스타일 제거 후 새로운 스타일 추가
const existingStatsStyle = document.getElementById('stats-modal-style');
if (existingStatsStyle) {
    existingStatsStyle.remove();
}
document.head.appendChild(statsModalStyle);

// 모달 공통 스타일 정의
const modalStyles = document.createElement('style');
modalStyles.id = 'modal-common-styles';
modalStyles.textContent = `
    .modal {
        display: none;
        position: fixed;
        z-index: 9999;
        left: 0;
        top: 0;
        width: 100%;
        height: 100%;
        background-color: rgba(0,0,0,0.5);
        overflow-y: auto;
    }

    .modal .modal-content {
        background-color: #fefefe;
        margin: 5% auto;
        padding: 20px;
        border: 1px solid #888;
        width: 90%;
        max-width: 1000px;
        border-radius: 5px;
        position: relative;
    }

    /* 버튼 스타일 추가 */
    .p-btn {
        padding: 8px 16px;
        margin: 5px;
        background-color: #007bff;
        color: white;
        border: none;
        border-radius: 4px;
        cursor: pointer;
    }

    .p-btn:hover {
        background-color: #0056b3;
    }
`;

// 기존 스타일 제거 후 새로운 스타일 추가
document.head.appendChild(modalStyles);

// 모달 관리 클래스
class ModalManager {
    constructor() {
        this.setupEventListeners();
    }

    setupEventListeners() {
        // ESC 키 이벤트
        document.addEventListener('keydown', (event) => {
            if (event.key === 'Escape') {
                this.closeAllModals();
            }
        });

        // 외부 클릭 이벤트
        document.addEventListener('click', (event) => {
            const modalContainer = document.getElementById('modal-container');
            if (event.target === modalContainer) {
                this.closeAllModals();
            }
        });
    }

    showModal(modalId) {
        const modal = document.getElementById(modalId);
        const modalContainer = document.getElementById('modal-container');
        
        if (modal && modalContainer) {
            modal.style.display = 'block';
            modalContainer.style.display = 'block';
            modalContainer.classList.add('active');
        }
    }

    closeModal(modalId) {
        const modal = document.getElementById(modalId);
        if (modal) {
            modal.style.display = 'none';
        }
        this.checkAndCloseContainer();
    }

    closeAllModals() {
        const modals = document.querySelectorAll('.modal');
        modals.forEach(modal => {
            modal.style.display = 'none';
        });
        this.checkAndCloseContainer();
    }

    checkAndCloseContainer() {
        const modalContainer = document.getElementById('modal-container');
        const openModals = document.querySelectorAll('.modal[style*="display: block"]');
        
        if (modalContainer && openModals.length === 0) {
            modalContainer.style.display = 'none';
            modalContainer.classList.remove('active');
        }
    }
}

// 모달 매니저 인스턴스 생성
const modalManager = new ModalManager();

class DropdownManager {
    constructor() {
        this.setupDropdowns();
    }

    setupDropdowns() {
        const yearDropdown = document.getElementById('yearSelect');
        const monthDropdown = document.getElementById('monthSelect');

        if (yearDropdown && monthDropdown) {
            [yearDropdown, monthDropdown].forEach(dropdown => {
                dropdown.addEventListener('mousedown', (e) => e.stopPropagation());
                dropdown.addEventListener('change', (e) => {
                    e.stopPropagation();
                    this.handleDropdownChange();
                });
            });
        }
    }

    handleDropdownChange() {
        fetchSelectedPerformanceData();
    }
}

// 드롭다운 매니저 인스턴스 생성
const dropdownManager = new DropdownManager();

// 클레임 모달 닫기 함수
window.closeClaimModal = function() {
    const claimModal = document.getElementById('claim-modal');
    if (claimModal) {
        claimModal.style.display = 'none';
    }
    const modalContainer = document.getElementById('modal-container');
    if (modalContainer) {
        modalContainer.style.display = 'none';
        modalContainer.classList.remove('active');
    }
};

// 클레임 모달 표시 함수
window.showClaimModal = async function(num) {
    try {
        // 다른 모달들 모두 닫기
        const allModals = document.querySelectorAll('#detail-modal, #business-modal, #stats-modal');
        allModals.forEach(modal => {
            if (modal) {
                modal.style.display = 'none';
            }
        });

        const claimModal = document.getElementById('claim-modal');
        const modalContainer = document.getElementById('modal-container');
        
        // API 호출을 위한 URL 인코딩
        const apiUrl = encodeURIComponent(`https://lincinsu.kr/2025/api/question/get_claim.php?num=${num}`);
        
        // API 호출
        const response = await fetch(`/cms/api/proxy.php?url=${apiUrl}`);
        if (!response.ok) {
            throw new Error('API 호출 실패');
        }
        
        const responseData = await response.json();
        if (!responseData) {
            throw new Error('데이터가 없습니다.');
        }

        // 데이터 바인딩
        document.getElementById("questionNum__").value = num;
        document.getElementById("cNum__").value = responseData.cNum || '';
        document.getElementById("certi__").textContent = responseData.certi || '';
        document.getElementById("school_1_").textContent = responseData.school1 || '';
        document.getElementById("school_2_").textContent = responseData.school2 || '';
        document.getElementById("school_3_").textContent = responseData.school3 || '';
        document.getElementById("school_4_").textContent = responseData.school4 || '';
        document.getElementById("school_5_").textContent = responseData.school5 || '';

        // 현장실습 시기 설정
        const periods = { "1": "1학기", "2": "하계", "3": "2학기", "4": "동계" };
        document.getElementById("school_6_").textContent = periods[responseData.school6] || "알 수 없음";

        // 실습기간 설정
        document.getElementById("school_7_").textContent = responseData.school7 || '';
        document.getElementById("school_8_").textContent = responseData.school8 || '';

        // 가입유형 설정
        document.getElementById("school_9_").textContent = 
            responseData.school9 == 1 ? "가입유형 A" : "가입유형 B";

        // 대인대물 한도 설정
        const limit = responseData.school9 == 1 ? "2 억" : "3 억";
        document.getElementById("daein1__").textContent = limit;
        document.getElementById("daein2__").textContent = limit;

        // 모달 및 컨테이너 표시
        claimModal.style.display = 'block';
        if (modalContainer) {
            modalContainer.style.display = 'block';
            modalContainer.classList.add('active');
        }

        // 이벤트 리스너 설정
        setupClaimModalEvents();

    } catch (error) {
        console.error('클레임 데이터 로드 실패:', error);
        alert(error.message || "클레임 데이터를 불러오는데 실패했습니다.");
    }
};

// 클레임 모달 관련 이벤트 리스너 설정 함수
function setupClaimModalEvents() {
    // 보험금 입력 필드 포맷팅
    const claimAmountInput = document.getElementById('claimAmout');
    if (claimAmountInput) {
        claimAmountInput.addEventListener('input', function(event) {
            let value = event.target.value.replace(/[^\d]/g, '');  // 숫자만 추출
            if (value) {
                value = parseInt(value).toLocaleString();  // 천단위 콤마 추가
            }
            event.target.value = value;
        });
    }

    // 날짜 입력 필드 포맷팅
    ['wdate_2', 'wdate_3'].forEach(id => {
        const dateInput = document.getElementById(id);
        if (dateInput) {
            dateInput.addEventListener('input', function(event) {
                let value = event.target.value.replace(/[^\d]/g, '');
                if (value.length >= 8) {
                    value = value.substr(0, 8);  // 8자리로 제한
                    value = value.replace(/(\d{4})(\d{2})(\d{2})/, '$1-$2-$3');
                }
                event.target.value = value;
            });
        }
    });
}

// 모달 공통 스타일에 클레임 모달 스타일 추가
modalStyles.textContent += `
    .modal-table {
        width: 100%;
        border-collapse: collapse;
        margin-bottom: 20px;
    }

    .modal-table th,
    .modal-table td {
        border: 1px solid #ddd;
        padding: 8px;
        text-align: left;
    }

    .modal-table th {
        background-color: #f5f5f5;
        font-weight: bold;
        width: 20%;
    }

    .modal-table input[readonly] {
        border: none;
        background: transparent;
        width: 100%;
    }
`;

// 모달 공통 스타일에 클레임 모달 스타일 추가
modalStyles.textContent += `
    .styled-input {
        width: 100%;
        padding: 8px;
        border: 1px solid #ddd;
        border-radius: 4px;
    }

    .styled-button {
        padding: 8px 16px;
        background-color: #007bff;
        color: white;
        border: none;
        border-radius: 4px;
        cursor: pointer;
    }

    .styled-button:hover {
        background-color: #0056b3;
    }

    .right-align {
        text-align: right;
    }

    .claim-button-container {
        text-align: center;
        padding: 10px 0;
    }

    .btd {
        background-color: #f8f9fa;
    }

    textarea.styled-input {
        resize: vertical;
        min-height: 100px;
    }
`;



    // 클레임 저장 버튼 이벤트 리스너
    document.addEventListener('click', function(event) {
        if (event.target.id === "claimStore") {
            event.preventDefault();
            
            // 증권번호 검증
            const certiElement = document.getElementById("certi__");
            const certi = certiElement ? certiElement.textContent.trim() : "";
            
            if (!certi) {
                alert("증권번호가 없습니다. 저장할 수 없습니다.");
                return;
            }

            // 필수 입력값 검증
            const requiredFields = {
                'student': '학생명',
                'wdate_3': '사고일자',
                'claimNumber': '사고접수번호',
                'accidentDescription': '사고경위'
            };

            for (let [id, label] of Object.entries(requiredFields)) {
                const value = document.getElementById(id)?.value.trim();
                if (!value) {
                    alert(`${label}을(를) 입력해주세요.`);
                    document.getElementById(id)?.focus();
                    return;
                }
            }

            // 세션스토리지에서 사용자 이름 가져오기
            const userName = sessionStorage.getItem('userName') || localStorage.getItem('userName');

            // FormData 생성 및 데이터 추가
            const claimData = new FormData();
            claimData.append("school1", document.getElementById("school_1_").textContent);
            claimData.append("qNum", document.getElementById("questionNum__").value);
            claimData.append("cNum", document.getElementById("cNum__").value);
            claimData.append("claimNum__", document.getElementById("claimNum__").value);
            claimData.append("certi", certi);
            claimData.append("claimNumber", document.getElementById("claimNumber").value);
            claimData.append("wdate_2", document.getElementById("wdate_2").value);
            claimData.append("wdate_3", document.getElementById("wdate_3").value);
            claimData.append("claimAmout", document.getElementById("claimAmout").value.replace(/,/g, ""));
            claimData.append("student", document.getElementById("student").value);
            claimData.append("accidentDescription", document.getElementById("accidentDescription").value.trim());
            claimData.append("manager", userName);
            claimData.append("damdanga", document.getElementById("damdanga_").value);
            claimData.append("damdangat", document.getElementById("damdangat_").value);

            // FormData를 URLSearchParams로 변환
            const formDataParams = new URLSearchParams();
            for (let [key, value] of claimData.entries()) {
                formDataParams.append(key, value);
            }

            console.log('전송할 데이터:', Object.fromEntries(formDataParams));

            // API URL 인코딩
            const apiUrl = encodeURIComponent('https://lincinsu.kr/2025/api/claim/claim_store.php');
            
            // API 호출
            fetch('/cms/api/proxy.php?url=' + apiUrl, {  // 상대 경로 사용
                method: "POST",
                headers: {
                    'Content-Type': 'application/x-www-form-urlencoded'
                },
                body: formDataParams.toString()
            })
            .then(async response => {
                console.log('응답 상태:', response.status);
                const text = await response.text();
                console.log('응답 텍스트:', text);
                try {
                    return JSON.parse(text);
                } catch (e) {
                    throw new Error('Invalid JSON response: ' + text);
                }
            })
            .then(data => {
                console.log('API 응답:', data);
                if (data.success) {
                    document.getElementById("claimNum__").value = data.num;
                    document.getElementById("claimStore").textContent = "클레임수정";
                    alert(data.message);
                } else {
                    alert("오류 발생: " + (data.error || "알 수 없는 오류"));
                }
            })
            .catch((error) => {
                console.error('API 호출 실패:', error);
                alert("데이터 저장에 실패했습니다.");
            });
        }
    });

// 통계 모달 표시 함수
window.showStatsModal = async function() {
    try {
        // 다른 모달들 모두 닫기
        const allModals = document.querySelectorAll('#detail-modal, #business-modal, #claim-modal');
        allModals.forEach(modal => {
            if (modal) {
                modal.style.display = 'none';
            }
        });

        const statsModal = document.getElementById('stats-modal');
        const modalContainer = document.getElementById('modal-container');
        
        if (statsModal && modalContainer) {
            statsModal.style.display = 'block';
            modalContainer.style.display = 'block';
            modalContainer.classList.add('active');

            // 연도/월 선택기 초기화
            setupDateSelectors();
            
            // 데이터 로드
            await fetchSelectedPerformanceData();
            
            // 푸터 버튼 추가
             // 모달 하단 버튼 추가
        const footerContainer = document.getElementById("changeP");
        footerContainer.innerHTML = ""; // 기존 내용 초기화

        let ptr = "";
        ptr += `<button id="downloadExcel" class="p-btn">최근 1년 실적 다운로드</button>`;
        ptr += `<button id="monthsBtn" class="p-btn" onclick="yearPerFormance()">월별 실적</button>`;
		footerContainer.innerHTML = ptr;
        }
    } catch (error) {
        console.error('통계 모달 표시 실패:', error);
        alert('통계 데이터를 불러오는데 실패했습니다.');
    }
};

// 날짜 선택기 설정
function setupDateSelectors() {
    const yearSpan = document.getElementById('yearSelect_');
    const monthSpan = document.getElementById('monthSelect_');
    
    if (!yearSpan || !monthSpan) return;

    // 기존 선택기 제거
    yearSpan.innerHTML = '';
    monthSpan.innerHTML = '';

    // 연도 선택기 생성
    const yearSelect = document.createElement('select');
    yearSelect.id = 'yearSelect';
    const currentYear = new Date().getFullYear();
    for (let year = currentYear; year >= currentYear - 5; year--) {
        const option = new Option(year + '년', year);
        yearSelect.add(option);
    }
    yearSpan.appendChild(yearSelect);

    // 월 선택기 생성
    const monthSelect = document.createElement('select');
    monthSelect.id = 'monthSelect';
    for (let month = 1; month <= 12; month++) {
        const option = new Option(month + '월', month.toString().padStart(2, '0'));
        monthSelect.add(option);
    }
    monthSpan.appendChild(monthSelect);

    // 현재 월 선택
    const currentMonth = (new Date().getMonth() + 1).toString().padStart(2, '0');
    monthSelect.value = currentMonth;

    // 이벤트 리스너 추가
    [yearSelect, monthSelect].forEach(select => {
        select.addEventListener('change', fetchSelectedPerformanceData);
    });
}



// 모달 닫기 이벤트 통합
document.addEventListener("click", function(event) {
    if (event.target.classList.contains("close-upmodal") || 
        (event.target.classList.contains("upModal") && event.target === event.currentTarget)) {
        const uploadModal = document.getElementById("uploadModal");
        if (uploadModal) {
            uploadModal.style.display = "none";
        }
    }
});

// 파일 검색 함수
function fileSearch(qnum) {
    fetch(`https://lincinsu.kr/2025/api/question/get_filelist.php?id=${qnum}`)
        .then(response => response.json())
        .then(fileData => {
            console.log('//',fileData);
			let rows1 = "";
            let rows = "";
			
			if(fileData!=''){
				rows1 +=`
					 <tr>
						<th>순번</th>
						<th>파일의종류</th>
						<th>(설계/증권)번호</th>
						<th>파일명</th>
						<th>입력일자</th>
						<th>기타</th>
					</tr>
					`;
			}
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
			document.getElementById("file_list1").innerHTML = rows1; // 머리말
            document.getElementById("file_list").innerHTML = rows;

            // 삭제 버튼 이벤트 리스너
            document.querySelectorAll(".dButton").forEach(button => {
                button.addEventListener("click", function() {
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
   if(confirm("삭제하시겠습니까?")){

    fetch(`https://lincinsu.kr/2025/api/question/delete_file.php?id=${fileNum}`)
        .then(response => response.json())
        .then(result => {
            if (result.success) {
                alert("파일이 삭제되었습니다.");
                fileSearch(document.getElementById("qNum").value);
            } else {
                alert("파일 삭제 실패: " + result.error);
            }
        })
        .catch(error => {
            alert("파일 삭제 요청 실패");
            console.error("파일 삭제 오류:", error);
        });
   }else{

	   return

   }
}

// 파일 업로드 함수
function uploadFile() {
    const fileInput = document.getElementById('uploadedFile');
    const fileType = document.getElementById('fileType').value;
    const qNum = document.getElementById('qNum').value;
    const dynamicInput = document.getElementById('dynamicInput') ? document.getElementById('dynamicInput').value : '';
    const userName = sessionStorage.getItem('userName') || localStorage.getItem('userName');

    if (!userName) {
        alert('사용자 정보를 찾을 수 없습니다. 다시 로그인해주세요.');
        return;
    }
    
    if (fileInput.files.length === 0) {
        alert('파일을 선택해주세요.');
        return;
    }

    if ((fileType === '4' || fileType === '7') && dynamicInput.trim() === '') {
        alert(fileType === '4' ? '설계번호를 입력해주세요.' : '증권번호를 입력해주세요.');
        return;
    }

    const formData = new FormData();
    formData.append('file', fileInput.files[0]);
    formData.append('fileType', fileType);
    formData.append('qNum', qNum);
    formData.append('userName', userName);

    if (fileType === '4') {
        formData.append('designNumber', dynamicInput);
    } else if (fileType === '7') {
        formData.append('certificateNumber', dynamicInput);
    }

    fetch(`https://lincinsu.kr/2025/api/question/upload.php`, {
        method: 'POST',
        body: formData
    })
    .then(response => response.text())
    .then(result => {
        alert('업로드 완료: ' + result);
        fileSearch(qNum);
    })
    .catch(error => {
        alert('업로드 실패.');
        console.error('파일 업로드 오류:', error);
    });
}

// 동적 파일 업로드 UI 생성 함수
function dynamiFileUpload() {
    const fileTypes = [
        { value: "4", text: "청약서" },
        { value: "1", text: "카드전표" },
        { value: "2", text: "영수증" },
        { value: "7", text: "보험증권" },
        { value: "5", text: "과별인원현황" },
        { value: "6", text: "보험사사업자등록증" },
        { value: "3", text: "기타" }
    ];

    const fileTypeSelect = document.createElement("select");
    fileTypeSelect.id = "fileType";
    fileTypeSelect.classList.add("u_select");
    fileTypeSelect.name = "fileType";

    fileTypes.forEach(optionData => {
        const option = document.createElement("option");
        option.value = optionData.value;
        option.textContent = optionData.text;
        fileTypeSelect.appendChild(option);
    });

    const dynamicField = document.createElement("div");
    dynamicField.id = "dynamicField";
    dynamicField.style.display = "none";

    const dynamicInput = document.createElement("input");
    dynamicInput.type = "text";
    dynamicInput.id = "dynamicInput";
    dynamicInput.name = "dynamicInput";
    dynamicField.appendChild(dynamicInput);

    const fileInput = document.createElement("input");
    fileInput.type = "file";
    fileInput.id = "uploadedFile";
    fileInput.name = "uploadedFile";
    fileInput.classList.add("uploadedFile");

    const uploadButton = document.createElement("button");
    uploadButton.type = "button";
    uploadButton.textContent = "업로드";
	uploadButton.classList.add("uButton");
    uploadButton.addEventListener("click", function(event) {
        event.preventDefault();
        uploadFile();
    });

    const uploadContainer = document.querySelector(".upload-container");
    uploadContainer.innerHTML = "";
    uploadContainer.appendChild(fileTypeSelect);
    uploadContainer.appendChild(dynamicField);
    uploadContainer.appendChild(fileInput);
    uploadContainer.appendChild(uploadButton);

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
            dynamicInput.value = "";
        }
    }

    fileTypeSelect.addEventListener("change", toggleInputField);
    toggleInputField();
}






