
// 함수 호출 상태 추적을 위한 플래그
let isHomeDataLoading = false;
let isEndorseLoading = false;
let isEndorseAfterLoading = false;

// 날짜 포맷팅 함수
function formatDate(dateStr) {
  if (!dateStr) return '';
  
  // 날짜 형식이 이미 포맷팅되어 있는지 확인
  if (typeof dateStr === 'string' && dateStr.includes('-') && dateStr.split('-').length === 3) {
    return dateStr;
  }
  
  // 타임스탬프나 Date 객체로 변환 가능한 형식인지 확인
  const date = new Date(dateStr);
  if (isNaN(date.getTime())) {
    console.warn('유효하지 않은 날짜 형식:', dateStr);
    return dateStr; // 변환할 수 없는 경우 원본 반환
  }
  
  // YYYY-MM-DD 형식으로 변환
  const year = date.getFullYear();
  const month = String(date.getMonth() + 1).padStart(2, '0');
  const day = String(date.getDate()).padStart(2, '0');
  
  return `${year}-${month}-${day}`;
}

// 홈 페이지 데이터 로드 함수
function loadHomeData() {
  // 이미 로딩 중이면 중복 호출 방지
  if (isHomeDataLoading) {
    console.log('홈 데이터가 이미 로드 중입니다.');
    return;
  }
  
  isHomeDataLoading = true;

  // 기존 로직
  // 로딩 인디케이터 표시
  showLoading();
  
  // cNum 쿠키 가져오기
  const cNum = getCookie('cNum');
  
  if (!cNum) {
    hideLoading();
    console.error('cNum 값이 없습니다.');
    isHomeDataLoading = false; // 플래그 리셋
    return;
  }
  
  // 서버에 데이터 요청 (FormData 사용)
  const formData = new FormData();
  formData.append('cNum', cNum);
  
  fetch('./api/customer/home_data.php', {
    method: 'POST',
    body: formData
  })
  .then(response => {
    if (!response.ok) {
      throw new Error('서버 응답 오류: ' + response.status);
    }
    return response.json();
  })
  .then(data => {
    // 데이터 성공적으로 받아온 경우 UI 업데이트
    updateHomeUI(data);
    hideLoading();
    isHomeDataLoading = false; // 플래그 리셋
  })
  .catch(error => {
    console.error('데이터 로드 중 오류 발생:', error);
    showErrorMessage('데이터를 불러오는 중 오류가 발생했습니다.');
    hideLoading();
    isHomeDataLoading = false; // 플래그 리셋
  });

  //배서리스트 조회 하기 위해 
  toDayEndorse(cNum);

  //배서 완료후 조회
  toDayEndorseAfter(cNum);
}

// 홈 페이지 UI 업데이트 함수
function updateHomeUI(data) {
  console.log('data.data', data.data);
  // 데이터가 올바른 형식인지 확인
  if (!data || !data.success || !data.data || !Array.isArray(data.data)) {
    console.error('올바른 데이터 형식이 아닙니다.');
    return;
  }
  
  // 동적 카드 컨테이너 가져오기
  const cardContainer = document.getElementById('dynamicCardContainer');
  
  // 컨테이너 내용 비우기
  cardContainer.innerHTML = '';
  
  // 데이터가 없는 경우 메시지 표시
  if (data.data.length === 0) {
    const noDataMsg = document.createElement('div');
    noDataMsg.className = 'col-12 text-center py-5';
    noDataMsg.innerHTML = `
      <div class="alert alert-info">
        <i class="fas fa-info-circle me-2"></i>
        표시할 증권 정보가 없습니다.
      </div>
    `;
    cardContainer.appendChild(noDataMsg);
    return;
  }
  
 
  
  // 데이터 각 항목에 대해 카드 생성
  data.data.forEach(policy => {
    // 서비스 유형 결정
    let serviceType = '';
    let badgeClass = 'bg-primary';
    let iconClass = 'fa-question-circle';
    
    switch(policy.gita) {
      case "1": 
        serviceType = "대리"; 
        badgeClass = 'bg-success';
        iconClass = 'fa-car';
        break;
      case "2": 
        serviceType = "탁송"; 
        badgeClass = 'bg-info';
        iconClass = 'fa-truck';
        break;
      case "3": 
        serviceType = "대리+렌트"; 
        badgeClass = 'bg-warning';
        iconClass = 'fa-car-side';
        break;
      case "4": 
        serviceType = "탁송+렌트"; 
        badgeClass = 'bg-danger';
        iconClass = 'fa-truck-moving';
        break;
      default: 
        serviceType = "미지정";
        badgeClass = 'bg-secondary';
    }
    
    // alert 제거 (디버깅용이었을 가능성이 높음)
    // alert(policy.InsuraneCompany);
    
    let InsuraneCompany = "";
    if (policy.InsuraneCompany) { // 
      const icompanyType = { "1": "흥국", "2": "DB", "3": "KB", "4": "현대", "5": "현대", "6": "롯데", "7": "MG", "8": "삼성" };
      InsuranceCompany = icompanyType[policy.InsuraneCompany] || ""; // 
    }
    
    // 카드 생성 - 통계 카드와 동일한 크기로 설정
    const card = document.createElement('div');
    card.className = 'col-xl-3 col-md-6 mb-4';
    
    // 카드 내용
    card.innerHTML = `
      <div class="dashboard-card h-100 stats-card">
        <div class="d-flex justify-content-between align-items-start mb-3">
          <h5><i class="fas ${iconClass} me-2"></i> ${policy.policyNum || '번호 미지정'}</h5>
          <span class="badge ${badgeClass}">${serviceType}</span>
        </div>
        <ul class="list-group list-group-flush">
          <li class="list-group-item d-flex justify-content-between">
            <span>보험회사:</span>
            <strong>${InsuranceCompany || '미지정'}</strong>
          </li>
          <li class="list-group-item d-flex justify-content-between">
            <span>시작일:</span>
            <strong>${formatDate(policy.startyDay) || '미지정'}</strong>
          </li>
          <li class="list-group-item d-flex justify-content-between">
            <span>인원수:</span>
            <strong>${policy.inwon || '0'}명</strong>
          </li>
        </ul>
        <div class="mt-3 text-end">
          <button class="btn btn-sm btn-outline-primary" onclick="viewPolicyDetail('${policy.num}','${InsuranceCompany}','${policy.InsuraneCompany}')">신규청약</button>
        </div>
      </div>
    `;
    
    // 컨테이너에 카드 추가
    cardContainer.appendChild(card);
  });
}


// 카드 상세보기 함수
function viewPolicyDetail(policyNum,InsuranceCompany,InsuraneCompany) { //증권번호,//보험회사 kb,현대// 보험회사 코드 3,4,...
    // 모달 요소 가져오기
    const modal = document.getElementById("subscription-modal");
    const modalContent = document.querySelector(".subscriptionModal-content");
    
    // 모달 중앙 배치 (화면 크기에 따라 자동 조정)
    function centerModal() {
        const windowHeight = window.innerHeight;
        const modalHeight = modalContent.offsetHeight;
        const topMargin = Math.max(20, (windowHeight - modalHeight) / 2);
        modalContent.style.marginTop = topMargin + "px";
    }

    // 모달 표시
    modal.style.display = "block";
    
    // 테이블 템플릿 생성 (Bootstrap 스타일 적용)
    let eTable = `
        <table class="kjTable kjTable-compact table table-bordered kjTable-mobile-stack">
            <thead class="bg-light">
                <tr> 
                    <th colspan="2" class="text-center align-middle">배서기준일</th>
                    <th colspan="2"><span id="endorseDay"></span></td>
                </tr>
                <tr>
                    <th width="5%">순번</th>
                    <th width="20%">성명</th>
					<th width="30%">핸드폰번호</th>
                    <th width="45%">주민번호</th>
                    
                </tr>
            </thead>
            <tbody id="kj_endorseList">
                <!-- 여기에 데이터 행이 추가될 것입니다 -->
            </tbody>
        </table>`;

    document.getElementById('m_subscription').innerHTML = eTable;

    // 모달이 DOM에 완전히 추가된 후 중앙 정렬
    setTimeout(centerModal, 10);
    
    // 창 크기 조정 시 모달 중앙 배치 유지
    window.addEventListener('resize', centerModal);
    
    // 닫기 버튼 이벤트 리스너
    const closeBtn = document.querySelector('.close-subscriptionModal');
    if (closeBtn) {
        closeBtn.addEventListener('click', function() {
            modal.style.display = "none";
            window.removeEventListener('resize', centerModal);
        });
    }
    
    // 모달 바깥 클릭 시 닫기
    window.addEventListener('click', function(event) {
        if (event.target === modal) {
            modal.style.display = "none";
            window.removeEventListener('resize', centerModal);
        }
    });
	
    // 데이터 로드 함수 호출
    endorseT(policyNum, modal,InsuraneCompany);
}
// 배서기준일 계산 함수
function calculateEndorsementDate() {
    const now = new Date();
    const currentHour = now.getHours();
    const currentMinute = now.getMinutes();
    
    // 16:30(16시 30분) 이후인지 확인
    const isAfterCutoffTime = (currentHour > 16) || (currentHour === 16 && currentMinute >= 30);
    
    let endorsementDate = new Date();
    
    // 16:30 이후라면 다음 날짜로 설정
    if (isAfterCutoffTime) {
        endorsementDate.setDate(endorsementDate.getDate() + 1);
    }
    
    // 요일 확인 (0: 일요일, 6: 토요일)
    const dayOfWeek = endorsementDate.getDay();
    
    // 토요일인 경우 월요일(+2일)로 설정
    if (dayOfWeek === 6) {
        endorsementDate.setDate(endorsementDate.getDate() + 2);
    } 
    // 일요일인 경우 월요일(+1일)로 설정
    else if (dayOfWeek === 0) {
        endorsementDate.setDate(endorsementDate.getDate() + 1);
    }
    
    // 날짜 포맷팅
    const year = endorsementDate.getFullYear();
    let month = endorsementDate.getMonth() + 1;
    let day = endorsementDate.getDate();
    
    // 월과 일이 10보다 작으면 앞에 0 추가
    month = month < 10 ? '0' + month : month;
    day = day < 10 ? '0' + day : day;
    
    return `${year}-${month}-${day}`;
}

// 현재 배서기준일 계산하여 화면에 표시
function updateEndorsementDate() {
    const formattedDate = calculateEndorsementDate();
    
    const dateInput = document.getElementById('endorseDay');
    if (dateInput) {
        dateInput.textContent = formattedDate;
    }
}

// 페이지 로드 시 실행


// 테스트용 코드 - 현재 시간과 계산된 배서기준일 출력
function testEndorsementDate() {
    const now = new Date();
    const hours = now.getHours();
    const minutes = now.getMinutes();
    const currentTime = `${hours}:${minutes < 10 ? '0' + minutes : minutes}`;
    
    console.log(`현재 시간: ${currentTime}`);
    console.log(`배서기준일: ${calculateEndorsementDate()}`);
    console.log(`현재 요일: ${now.getDay()}`); // 0(일요일)부터 6(토요일)
}

// 테스트 함수 호출
// testEndorsementDate();
async function endorseT(cNum, modal,InsuraneCompany) {
    console.log('증권번호:', cNum);
    
    try {
        updateEndorsementDate();

        // API 호출
        const response = await fetch(`./api/kjDaeri/get_2012CertiTable_details2.php?cNum=${cNum}`);
        const data = await response.json();
        
        if (!data.success) {
            throw new Error(data.message || '데이터를 불러오는데 실패했습니다.');
        }
        
        // 증권 유형 확인
        const gitaValue = parseInt(data.gita);
        let gitaName = '';
        let badgeClass = '';
        let iconClass = '';

        switch(gitaValue) {
            case 1:
                gitaName = '대리';
                badgeClass = 'bg-primary';
                iconClass = 'fa-car';
                break;
            case 2:
                gitaName = '탁송';
                badgeClass = 'bg-info';
                iconClass = 'fa-truck';
                break;
            case 3:
                gitaName = '대리/렌트';
                badgeClass = 'bg-warning';
                iconClass = 'fa-car-side';
                break;
            case 4:
                gitaName = '탁송/렌트';
                badgeClass = 'bg-danger';
                iconClass = 'fa-truck-moving';
                break;
            default:
                gitaName = '기타';
                badgeClass = 'bg-secondary';
                iconClass = 'fa-question-circle';
        }
        
        console.log('데이터 로드 성공:', data);
        
        // 증권번호 정보 표시
        const companyInfoElement = document.getElementById("subscription_daeriCompany");
        if (companyInfoElement) {
            companyInfoElement.innerHTML = `
                ${InsuranceCompany} <span class="badge ${badgeClass} ms-2">${gitaName}</span><span class="badge bg-primary">${data.policyNum}</span>
                
            `;
        } else {
            console.warn("⚠️ 'subscription_daeriCompany' 요소를 찾을 수 없습니다.");
        }
        
        // 증권번호 히든 필드 설정
        let inputElement = document.getElementById("e_Cnum");
        if (!inputElement) {
            inputElement = document.createElement("input");
            inputElement.type = "hidden";
            inputElement.id = "e_Cnum";
            modal.appendChild(inputElement);
        }
        inputElement.value = cNum;
        
        // 테이블 본문 요소
        const tbody = document.getElementById('kj_endorseList');
        if (!tbody) {
            console.error("❌ 'kj_endorseList' 요소를 찾을 수 없습니다.");
            return;
        }
        
        // 테이블 본문 비우기
        tbody.innerHTML = '';
        
        // 모바일 환경인지 확인
        const isMobile = window.innerWidth <= 575;
        
        // 모바일 환경에서는 테이블에 모바일 스타일 클래스 추가
        const tableElement = document.querySelector('.kjTable');
        if (tableElement && isMobile) {
            tableElement.classList.add('kjTable-mobile-stack');
        }
        
        // 입력 행 6개 추가
        for (let i = 1; i <= 6; i++) {
            const row = document.createElement('tr');
            
            // 모바일 대응을 위한 data-label 속성 추가
            row.innerHTML = `
                <th class="text-center align-middle" data-label="순번">${i}</th>
                <td class="text-center align-middle" data-label="성명">
                    <input type="text" class="form-control geInput" id="p_${i}_1" 
                        data-row="${i}" data-col="1" placeholder="성명" autocomplete="off">
                </td>
				<td class="text-center align-middle" data-label="핸드폰번호">
                    <input type="text" class="form-control geInput" id="p_${i}_3" 
                        data-row="${i}" data-col="3" placeholder="핸드폰 번호" maxlength="13" autocomplete="off">
                </td>
                <td class="text-center align-middle" data-label="주민번호">
                    <input type="text" class="form-control geInput" id="p_${i}_2" 
                        data-row="${i}" data-col="2" placeholder="주민번호" maxlength="14" autocomplete="off">
                </td>
                
            `;
            tbody.appendChild(row);
        }
        
        // 저장 버튼 행 추가
        const saveButtonRow = document.createElement('tr');
			saveButtonRow.className = 'save-button-row'; // 클래스 추가
			saveButtonRow.innerHTML = `
				<td colspan="4" class="text-center py-4">
					<button id="saveEndorseButton" class="save-button btn btn-success  py-3">
						<i class="fas fa-save me-2"></i>저장하기
					</button>
				</td>
			`;
			tbody.appendChild(saveButtonRow);

			// 스타일 추가
			const saveButtonRowStyle = document.createElement('style');
			saveButtonRowStyle.textContent = `
				.save-button-row {
					height: 40px; /* 행 높이 직접 지정 */
				}
				
				.save-button-row td {
					padding-top: 20px !important;
					padding-bottom: 20px !important;
				}
				
				@media (max-width: 768px) {
					.save-button-row {
						height: 100px; /* 모바일에서는 더 높게 설정 */
					}
					
					.save-button-row td {
						padding-top: 5px !important;
						padding-bottom: 10px !important;
					}
					
					#saveEndorseButton {
						height: 40px;
						font-size: 18px;
						font-weight: 500;
					}
				}
			`;
			document.head.appendChild(saveButtonRowStyle);
        
        // 저장 버튼 이벤트 리스너
        document.getElementById('saveEndorseButton').addEventListener('click', function() {
            saveEndorse(cNum, data.dNum, data.gita, InsuraneCompany, document.getElementById('endorseDay').textContent, data.policyNum);
        });
        
        // 화면 크기에 따라 반응형 클래스 추가
        adjustResponsive();
        
        // 성명 필드 설정 (한글만 입력 가능)
        setupNameInputs();
        
        // 주민등록번호 입력 형식 설정
        setupJuminInputs();
        
        // 핸드폰 번호 입력 형식 설정
        setupPhoneInputs();
        
        // 창 중앙 정렬 다시 실행
        centerModal();
        
    } catch (error) {
        console.error("❌ 데이터 로드 실패:", error);
        toast('데이터를 불러오는데 실패했습니다: ' + error.message, 'danger');
    }
}

// 반응형 조정 함수
function adjustResponsive() {
    const tableEl = document.querySelector('.kjTable');
    if (!tableEl) return;
    
    const isMobile = window.innerWidth <= 575;
    
    if (isMobile) {
        tableEl.classList.add('kjTable-mobile-stack');
        document.querySelectorAll('.input-group').forEach(group => {
            group.classList.add('mobile-hide-icon');
        });
    } else {
        tableEl.classList.remove('kjTable-mobile-stack');
        document.querySelectorAll('.input-group').forEach(group => {
            group.classList.remove('mobile-hide-icon');
        });
    }
    
    // 모바일 환경에서 입력 필드 최적화
    document.querySelectorAll('input[type="text"]').forEach(input => {
        if (input.id.includes('_2')) { // 주민번호 필드
            if (isMobile) {
                input.setAttribute('inputmode', 'numeric');
                input.setAttribute('pattern', '[0-9]*');
            } else {
                input.removeAttribute('inputmode');
                input.removeAttribute('pattern');
            }
        } else if (input.id.includes('_3')) { // 전화번호 필드
            if (isMobile) {
                input.setAttribute('inputmode', 'tel');
            } else {
                input.removeAttribute('inputmode');
            }
        }
    });
}

// 모달 중앙 정렬 함수
function centerModal() {
    setTimeout(() => {
        const modalContent = document.querySelector(".subscriptionModal-content");
        if (!modalContent) return;
        
        const windowHeight = window.innerHeight;
        const modalHeight = modalContent.offsetHeight;
        
        // 모바일에서는 상단에 붙이고, 데스크탑에서는 중앙 정렬
        const isMobile = window.innerWidth <= 575;
        const topMargin = isMobile 
            ? Math.min(20, windowHeight * 0.05) // 모바일에서는 상단에 가깝게
            : Math.max(20, (windowHeight - modalHeight) / 2); // 데스크탑에서는 중앙
            
        modalContent.style.marginTop = topMargin + "px";
    }, 100);
}

function setupNameInputs() {
    for (let i = 1; i <= 6; i++) {
        const nameInput = document.getElementById(`p_${i}_1`);
        if (nameInput) {
            let isComposing = false;
            let lastInputTime = 0;
            
            // 한글 입력 시작
            nameInput.addEventListener('compositionstart', function() {
                isComposing = true;
                // 입력 중에는 검증 표시 제거
                this.classList.remove('is-valid', 'is-invalid');
            });
            
            // 한글 입력 완료
            nameInput.addEventListener('compositionend', function() {
                isComposing = false;
                lastInputTime = Date.now();
                
                // 입력 완료 후 더 긴 지연을 두고 검증 (모바일용)
                setTimeout(() => {
                    // 다른 이벤트에 의해 이미 검증되지 않았다면 검증 실행
                    if (Date.now() - lastInputTime >= 200) {
                        validateKoreanName(this, true); // 최종 검증 모드
                    }
                }, 300);
            });
            
            // 일반 입력 (한글 입력 중이 아닐 때)
            nameInput.addEventListener('input', function() {
                if (!isComposing) {
                    lastInputTime = Date.now();
                    // 입력 중에는 가벼운 검증만 (모바일에서는 건너뜀)
                    if (window.innerWidth > 575) {
                        validateKoreanName(this, false); // 중간 검증 모드
                    }
                }
            });
            
            // 필드에서 포커스 벗어날 때 최종 검증
            nameInput.addEventListener('blur', function() {
                // 입력 중이 아니면 즉시 검증, 입력 중이면 지연 후 검증
                if (!isComposing) {
                    validateKoreanName(this, true); // 최종 검증 모드
                } else {
                    // 한글 입력 중일 때 blur 발생하면 약간 기다렸다가 검증
                    setTimeout(() => {
                        validateKoreanName(this, true); // 최종 검증 모드
                    }, 300);
                }
            });
            
            // 필드 클릭 시에도 재검증
            nameInput.addEventListener('focus', function() {
                // 값이 있으면 포커스 시 재검증 (이전 오류 상태를 정리)
                if (this.value.trim()) {
                    setTimeout(() => {
                        validateKoreanName(this, false); // 중간 검증 모드
                    }, 100);
                }
            });
        }
    }
}

// 개선된 한글 이름 검증 함수
function validateKoreanName(input, isFinalCheck) {
    const value = input.value.trim();
    
    // 입력이 없으면 검증 표시 제거
    if (!value) {
        input.classList.remove('is-valid', 'is-invalid');
        return;
    }

    // 한글 이름 정규식 - 완성된 한글만 2-10자
    const koreanNameRegex = /^[가-힣]{2,10}$/;
    
    // 정규식 테스트
    const isValid = koreanNameRegex.test(value);
    
    // 모바일 환경에서는 최종 검증일 때만 상태 변경
    if (window.innerWidth <= 575 && !isFinalCheck) {
        return; // 모바일에서 중간 검증은 생략
    }
    
    if (isValid) {
        // 유효한 이름
        input.classList.add('is-valid');
        input.classList.remove('is-invalid');
    } else {
        // 유효하지 않은 이름
        input.classList.add('is-invalid');
        input.classList.remove('is-valid');
        
        // 토스트 메시지는 최종 검증에서만 표시
        if (isFinalCheck && window.innerWidth <= 575) {
            toast('한글 이름(2-10자)만 입력 가능합니다.', 'warning');
        }
    }
}
// 주민번호 입력 필드 설정
function setupJuminInputs() {
    for (let i = 1; i <= 6; i++) {
        const juminInput = document.getElementById(`p_${i}_2`);
        if (juminInput) {
            juminInput.addEventListener('input', function(e) {
                let value = e.target.value.replace(/[^0-9-]/g, '');
                
                // 하이픈 자동 삽입 (6자리 이후)
                if (value.length > 6 && !value.includes('-')) {
                    value = value.substring(0, 6) + '-' + value.substring(6);
                }
                
                // 최대 13자리 숫자 + 하이픈으로 제한
                if (value.replace(/-/g, '').length > 13) {
                    value = value.substring(0, value.length);
                }
                
                e.target.value = value;
                
                // 입력 중 시각적 표시 제거
                this.classList.remove('is-valid', 'is-invalid');
            });
            
            juminInput.addEventListener('blur', function() {
                const juminNo = this.value.replace(/-/g, '');
                
                if (juminNo.length === 0) {
                    // 입력이 없는 경우 스타일 초기화
                    this.classList.remove('is-valid', 'is-invalid');
                    return;
                }
                
                if (juminNo.length !== 13) {
                    // 불완전한 주민번호 경고
                    this.classList.add('is-invalid');
                    this.classList.remove('is-valid');
                    
                    // 모바일 대응 토스트 메시지
                    toast('주민번호 13자리를 모두 입력해주세요.', 'warning');
                    return;
                }
                
                if (!validateJuminNo(juminNo)) {
                    this.classList.add('is-invalid');
                    this.classList.remove('is-valid');
                    toast('유효하지 않은 주민등록번호입니다.', 'danger');
                } else {
                    this.classList.add('is-valid');
                    this.classList.remove('is-invalid');
                }
            });
        }
    }
}

// 핸드폰 번호 입력 필드 설정 - 누락된 함수 추가
function setupPhoneInputs() {
    for (let i = 1; i <= 6; i++) {
        const phoneInput = document.getElementById(`p_${i}_3`);
        if (phoneInput) {
            phoneInput.addEventListener('input', function(e) {
                let value = e.target.value.replace(/[^0-9-]/g, '');
                
                // 하이픈 자동 삽입
                if (value.length > 3 && !value.includes('-')) {
                    value = value.substring(0, 3) + '-' + value.substring(3);
                }
                if (value.length > 8 && value.indexOf('-', 4) === -1) {
                    value = value.substring(0, 8) + '-' + value.substring(8);
                }
                
                // 최대 길이 제한 (13자: 010-1234-5678 형식)
                if (value.length > 13) {
                    value = value.substring(0, 13);
                }
                
                e.target.value = value;
                
                // 입력 중 시각적 표시 제거
                this.classList.remove('is-valid', 'is-invalid');
            });
            
            phoneInput.addEventListener('blur', function() {
                const phoneNo = this.value.replace(/-/g, '');
                
                if (phoneNo.length === 0) {
                    // 입력이 없는 경우 스타일 초기화
                    this.classList.remove('is-valid', 'is-invalid');
                    return;
                }
                
                // 전화번호 유효성 검사 (10-11자리)
                if (phoneNo.length < 10 || phoneNo.length > 11) {
                    this.classList.add('is-invalid');
                    this.classList.remove('is-valid');
                    toast('유효한 전화번호를 입력해주세요.', 'warning');
                } else {
                    this.classList.add('is-valid');
                    this.classList.remove('is-invalid');
                }
            });
        }
    }
}

// 주민등록번호 유효성 검사
function validateJuminNo(juminNo) {
    // 기본 형식 검사 (숫자 13자리)
    if (!/^\d{13}$/.test(juminNo)) {
        return false;
    }
    
    // 성별 코드 확인 (1,2: 1900년대 출생, 3,4: 2000년대 출생)
    const genderCode = parseInt(juminNo.charAt(6));
    if (![1, 2, 3, 4].includes(genderCode)) {
        return false;
    }
    
    // 생년월일 확인
    const year = (genderCode <= 2) ? 
        parseInt('19' + juminNo.substring(0, 2)) : 
        parseInt('20' + juminNo.substring(0, 2));
    
    const month = parseInt(juminNo.substring(2, 4));
    const day = parseInt(juminNo.substring(4, 6));
    
    // 날짜 유효성 확인
    if (month < 1 || month > 12) return false;
    
    const lastDayOfMonth = new Date(year, month, 0).getDate();
    if (day < 1 || day > lastDayOfMonth) return false;
    
    // 체크 알고리즘
    const checkSum = 
        (juminNo.charAt(0) * 2) +
        (juminNo.charAt(1) * 3) +
        (juminNo.charAt(2) * 4) +
        (juminNo.charAt(3) * 5) +
        (juminNo.charAt(4) * 6) +
        (juminNo.charAt(5) * 7) +
        (juminNo.charAt(6) * 8) +
        (juminNo.charAt(7) * 9) +
        (juminNo.charAt(8) * 2) +
        (juminNo.charAt(9) * 3) +
        (juminNo.charAt(10) * 4) +
        (juminNo.charAt(11) * 5);
    
    const checkDigit = (11 - (checkSum % 11)) % 10;
    
    return checkDigit === parseInt(juminNo.charAt(12));
}

// 토스트 메시지 표시 함수
function toast(message, type = 'info') {
    // 기존 토스트 제거
    const existingToasts = document.querySelectorAll('.toast-container');
    existingToasts.forEach(t => t.remove());
    
    // 새 토스트 생성
    const toastContainer = document.createElement('div');
    
    // 모바일 환경에서는 중앙 상단에 표시
    if (window.innerWidth <= 768) {
        toastContainer.className = 'toast-container position-fixed top-0 start-50 translate-middle-x p-3';
    } else {
        toastContainer.className = 'toast-container position-fixed bottom-0 end-0 p-3';
    }
    
    toastContainer.style.zIndex = '5000';
    
    const toastEl = document.createElement('div');
    toastEl.className = `toast show bg-${type} text-white`;
    
    // 모바일 환경에서 토스트 크기 및 스타일 조정
    if (window.innerWidth <= 768) {
        toastEl.style.minWidth = '300px';
        toastEl.style.maxWidth = '90vw';
        toastEl.style.fontSize = '14px';
        toastEl.classList.add('toast-mobile');
    }
    
    toastEl.setAttribute('role', 'alert');
    toastEl.setAttribute('aria-live', 'assertive');
    toastEl.setAttribute('aria-atomic', 'true');
    
    const toastBody = document.createElement('div');
    toastBody.className = 'toast-body d-flex align-items-center';
    
    // 아이콘 추가
    let icon = 'info-circle';
    if (type === 'warning') icon = 'exclamation-triangle';
    if (type === 'danger') icon = 'exclamation-circle';
    if (type === 'success') icon = 'check-circle';
    
    toastBody.innerHTML = `
        <div class="me-2">
            <i class="fas fa-${icon}"></i>
        </div>
        <div class="flex-grow-1">
            ${message}
        </div>
        <div class="ms-auto">
            <button type="button" class="btn-close btn-close-white" data-bs-dismiss="toast" aria-label="Close"></button>
        </div>
    `;
    
    toastEl.appendChild(toastBody);
    toastContainer.appendChild(toastEl);
    document.body.appendChild(toastContainer);
    
    // 모바일 환경에서는 토스트 메시지를 더 오래 표시
    const duration = window.innerWidth <= 768 ? 4000 : 3000;
    
    // 지정된 시간 후 토스트 자동 제거
    setTimeout(() => {
        // 부드러운 제거를 위한 페이드아웃 효과
        toastEl.style.transition = 'opacity 0.5s ease';
        toastEl.style.opacity = '0';
        
        setTimeout(() => {
            toastContainer.remove();
        }, 500);
    }, duration);
    
    // 닫기 버튼 이벤트
    const closeBtn = toastEl.querySelector('.btn-close');
    if (closeBtn) {
        closeBtn.addEventListener('click', () => {
            // 부드러운 제거를 위한 페이드아웃 효과
            toastEl.style.transition = 'opacity 0.3s ease';
            toastEl.style.opacity = '0';
            
            setTimeout(() => {
                toastContainer.remove();
            }, 300);
        });
    }
}



function saveEndorse(cNum, dNum, gita, InsuraneCompany, endorseDay, policyNum) {
    console.log('cNum', cNum, 'dNum', dNum, 'gita', gita, 'InsuraneCompany', InsuraneCompany, 'endorseDay', endorseDay, 'policyNum', policyNum);
    
    // 저장할 데이터 수집
    const endorseData = [];
    let hasData = false;
    
    for (let i = 1; i <= 6; i++) {
        // 입력 필드 값 가져오기
        const name = document.getElementById(`p_${i}_1`).value.trim();
        const juminNo = document.getElementById(`p_${i}_2`).value.trim();
        const phoneNo = document.getElementById(`p_${i}_3`).value.trim();
        
        // 빈 행 건너뛰기 (이름이 없는 경우)
        if (!name) {
            continue;
        }
        
        hasData = true;
        
        const rowData = {
            rowNum: i,
            name: name,
            juminNo: juminNo,
            phoneNo: phoneNo
        };
        
        endorseData.push(rowData);
    }
    
    // 저장할 데이터가 있는지 확인
    if (!hasData) {
        alert("저장할 데이터가 없습니다. 최소한 성명을 입력해주세요.");
        return;
    }
    
    // dNum이 653이 아닌 경우에만 누락된 정보 확인 및 유효성 검사 수행
    if (dNum !== '653' && dNum !== 653) {
        for (const item of endorseData) {
            // 성명은 있지만 주민번호가 없는 경우
            if (!item.juminNo) {
                alert(`${item.rowNum}번 행의 주민등록번호가 누락되었습니다.`);
                return;
            }
            
            // 성명은 있지만 핸드폰 번호가 없는 경우
            if (!item.phoneNo) {
                alert(`${item.rowNum}번 행의 핸드폰 번호가 누락되었습니다.`);
                return;
            }
            
            // 주민번호 유효성 확인
            if (!validateJuminNo(item.juminNo.replace(/-/g, ''))) {
                alert(`${item.rowNum}번 행의 주민등록번호가 유효하지 않습니다.`);
                return;
            }
        }
    } else {
        // dNum이 653인 경우에도 핸드폰 번호 누락 확인은 계속 수행
        for (const item of endorseData) {
            if (!item.phoneNo) {
                alert(`${item.rowNum}번 행의 핸드폰 번호가 누락되었습니다.`);
                return;
            }
        }
    }
    
    // 오래된 PHP 버전 호환을 위해 FormData 사용
    const form = new FormData();
    
    // 각 행 데이터를 개별 폼 필드로 변환
    for (let i = 0; i < endorseData.length; i++) {
        const item = endorseData[i];
        for (const key in item) {
            form.append(`data[${i}][${key}]`, item[key]);
        }
    }
    
    // 기본 데이터 추가
    form.append('cNum', cNum);
    form.append('dNum', dNum);
    form.append('gita', gita);
    form.append('InsuraneCompany', InsuraneCompany);
    form.append('endorseDay', endorseDay);
    form.append('policyNum', policyNum);
    
    // 쿠키에서 사용자 이름 가져오기
    const userName = getCookie('nAme') || '사용자';
    form.append('userName', userName);
    
    // Form 데이터로 API 호출
    fetch("./api/kjDaeri/save_endorse_data_encryption.php", {
        method: "POST",
        body: form
    })
    .then(response => {
        // 텍스트로 응답 받기 (오래된 PHP가 JSON 형식을 제대로 반환하지 못할 수 있음)
        return response.text();
    })
    .then(text => {
        // 응답 텍스트 처리
        let result;
        try {
            // JSON 파싱 시도
            result = JSON.parse(text);
        } catch (e) {
            // 파싱 실패 시 텍스트 응답 그대로 사용
            console.log("응답 텍스트:", text);
            
            // 성공 여부 추정
            if (text.indexOf("성공") !== -1) {
                alert("데이터가 저장되었습니다.");
                // document.getElementById('kj-endorse-modal').style.display = "none";
                return;
            } else {
                alert("저장 실패: 서버 응답을 처리할 수 없습니다.");
                return;
            }
        }
        
        // 정상적인 JSON 응답 처리
        if (result.success) {
            alert("데이터가 성공적으로 저장되었습니다.");
            // 입력 필드 초기화
            for (let i = 1; i <= 6; i++) {
                document.getElementById(`p_${i}_1`).value = ''; // 이름 초기화
                document.getElementById(`p_${i}_2`).value = ''; // 주민번호 초기화
                document.getElementById(`p_${i}_3`).value = ''; // 핸드폰 번호 초기화
            }

            loadHomeData();
            document.getElementById('subscription-modal').style.display = "none";  // 배서 신청 화면 닫기
            // document.getElementById('kj-DaeriCompany-modal').style.display = "none"; // 대리운전 회사 화면 닫기 
            // kjEndorseloadTable(1, policyNum, ''); //배서신청자 리스트 
        } else {
            alert("저장 실패: " + (result.error || "알 수 없는 오류"));
        }
    })
    .catch(error => {
        console.error("❌ 저장 중 오류 발생:", error);
        alert("데이터 저장 중 오류가 발생했습니다.");
    });
}

// 오늘의 배서 리스트 조회하기 
function toDayEndorse(cNum) {
  // 이미 로딩 중이면 중복 호출 방지
  if (isEndorseLoading) {
    console.log('배서 데이터가 이미 로드 중입니다.');
    return;
  }
  
  isEndorseLoading = true;

  // 기존 로직
  // 로딩 인디케이터 표시
  showLoading();
  
  if (!cNum) {
    hideLoading();
    console.error('cNum 값이 없습니다.');
    isEndorseLoading = false; // 플래그 리셋
    return;
  }
  
  // 서버에 데이터 요청 (FormData 사용)
  const formData = new FormData();
  formData.append('cNum', cNum);
  
  fetch('./api/customer/home_data_endorse.php', {
    method: 'POST',
    body: formData
  })
  .then(response => {
    if (!response.ok) {
      throw new Error('서버 응답 오류: ' + response.status);
    }
    return response.json();
  })
  .then(data => {
    // 데이터 성공적으로 받아온 경우 UI 업데이트
    updateHomeEndorseUI(data);
    // 모바일 카드 뷰 업데이트 추가
    updateHomeEndorseMobileView(data);
    hideLoading();
    isEndorseLoading = false; // 플래그 리셋
  })
  .catch(error => {
    console.error('데이터 로드 중 오류 발생:', error);
    showErrorMessage('데이터를 불러오는 중 오류가 발생했습니다.');
    hideLoading();
    isEndorseLoading = false; // 플래그 리셋
  });
}

// 배서 목록 UI 업데이트 함수 수정
// 처리 중인 배서 리스트 업데이트 함수
function updateHomeEndorseUI(data) {
  console.log("배서 데이터:", data);
  
  // 테이블 본문 요소 가져오기
  const tableBody = document.getElementById('enderose_list_01');
  
  // 테이블이 존재하지 않는 경우
  if (!tableBody) {
    console.error('enderose_list_01 요소를 찾을 수 없습니다.');
    return;
  }
  
  // 테이블과 컨테이너 요소
  const tableEl = tableBody.closest('table');
  const tableContainer = tableEl ? tableEl.parentNode : null;
  
  // 기존 "데이터 없음" 메시지 제거
  const existingEmptyState = document.querySelector('.empty-state-container');
  if (existingEmptyState) {
    existingEmptyState.remove();
  }
  
  // 진행 중인 배서 카운트 업데이트
  const ongoingCountElem = document.getElementById('ongoing-count');
  if (ongoingCountElem) {
    ongoingCountElem.textContent = data && data.data ? `${data.data.length}건` : '0건';
  }
  
  // 데이터가 유효한지 확인 
  if (!data || !data.success || !Array.isArray(data.data) || data.data.length === 0) {
    // 테이블 숨기기
    if (tableEl) {
      tableEl.style.display = 'none';
    }
    
    // 데이터 없음 상태 표시
    const emptyState = document.createElement('div');
    emptyState.className = 'empty-state-container my-4 p-4 text-center';
    emptyState.innerHTML = `
      <div class="empty-state-icon mb-3">
        <i class="fas fa-clipboard-check" style="font-size: 2.5rem; color: #007bff;"></i>
      </div>
      <h5 class="mb-2">배서 신청 내역이 없습니다</h5>
      <p class="text-muted">현재 처리 중인 배서 신청 건이 없습니다.</p>
    `;
    
    if (tableContainer) {
      tableContainer.appendChild(emptyState);
    }
    return;
  }
  
  // 데이터가 있는 경우, 테이블 표시
  if (tableEl) {
    tableEl.style.display = ''; // 기본 display 값 사용
    tableEl.classList.add('table-mobile-stack'); // 모바일 스택 클래스 추가
  }
  
  // 테이블 본문 초기화
  tableBody.innerHTML = '';
  
  // 각 데이터 행에 대해 처리
  data.data.forEach((item, index) => {
    const row = document.createElement('tr');
    
    const maskedJumin = item.Jumin ? 
      // 하이픈 포함 형식 처리
      `${item.Jumin.substring(0, 8)}******` : 
      '정보 없음';
    
    
    switch(item.etag) {
      case "1": 
        serviceType = "대리"; 
        badgeClass = 'bg-success';
        iconClass = 'fa-car';
        break;
      case "2": 
        serviceType = "탁송"; 
        badgeClass = 'bg-info';
        iconClass = 'fa-truck';
        break;
      case "3": 
        serviceType = "대리+렌트"; 
        badgeClass = 'bg-warning';
        iconClass = 'fa-car-side';
        break;
      case "4": 
        serviceType = "탁송+렌트"; 
        badgeClass = 'bg-danger';
        iconClass = 'fa-truck-moving';
        break;
      default: 
        serviceType = "미지정";
        badgeClass = 'bg-secondary';
    }
    
    // alert 제거 (디버깅용이었을 가능성이 높음)
    // alert(item.InsuraneCompany);
    
    let InsuranceCompany = "";
    if (item.InsuranceCompany) { // 
      const icompanyType = { "1": "흥국", "2": "DB", "3": "KB", "4": "현대", "5": "현대", "6": "롯데", "7": "MG", "8": "삼성" };
      InsuranceCompany = icompanyType[item.InsuranceCompany] || ""; // 
    }
	//청약 1 해지 4
	const pType={"1":"청약중","4":"해지중"}
		const pushText=pType[item.push]||"알 수 없음";

		// badgeColor 변수를 수정하고 기본 클래스 추가
		const badgeColor = item.push === "4" ? "bg-warning" : (item.push === "1" ? "bg-primary" : "bg-secondary");

		// HTML 내용 설정 - badge 클래스를 먼저 적용하고 배경색 클래스 적용
		row.innerHTML = `
		  <td data-label="번호">${index + 1}</td>
		  <td data-label="성명">${item.Name || '정보 없음'}</td>
		  <td data-label="주민번호">${maskedJumin}</td>
		  <td data-label="핸드폰">${item.Hphone}</td>
		  <td data-label="보험회사">${InsuranceCompany}</td>
		  <td data-label="증권번호">${item.dongbuCerti || '정보 없음'}</td>
		  <td data-label="증권성격">${serviceType || '정보 없음'}</td>
		  <td data-label="상태"><span class="badge ${badgeColor}">${pushText}</span></td>
		`;
    
    // 테이블에 행 추가
    tableBody.appendChild(row);
  });

   // 테이블 스타일 업데이트 함수 호출 (함수 끝 부분에 추가)
  updateTableStyles();
}
// 스타일 요소 추가 또는 업데이트
function updateTableStyles() {
  const styleElement = document.getElementById('table-custom-styles');
  if (!styleElement) {
    const style = document.createElement('style');
    style.id = 'table-custom-styles';
    style.textContent = `
      /* 테이블 폰트 크기 조정 */
      #enderose_list_01,
      #enderose_list_01 td,
      #enderose_list_01 th,
      .table-hover thead th {
        font-size: 0.85rem !important; /* 폰트 크기 줄임 */
      }
      
      /* 헤더 폰트 크기 조정 */
      .table-hover thead th {
        font-size: 0.8rem !important;
        font-weight: 600;
      }
      
      /* 테이블 여백 조정 */
      .table-hover td, 
      .table-hover th {
        padding: 0.5rem !important; /* 셀 패딩 줄임 */
      }
      
      /* 모바일에서 더 작은 폰트 */
      @media (max-width: 768px) {
        #enderose_list_01,
        #enderose_list_01 td,
        #enderose_list_01 th {
          font-size: 0.75rem !important;
        }
      }
    `;
    document.head.appendChild(style);
  } else {
    // 기존 스타일 요소가 있다면 내용 업데이트
    styleElement.textContent += `
      /* 테이블 폰트 크기 조정 */
      #enderose_list_01,
      #enderose_list_01 td,
      #enderose_list_01 th,
      .table-hover thead th {
        font-size: 0.85rem !important; /* 폰트 크기 줄임 */
      }
      
      /* 헤더 폰트 크기 조정 */
      .table-hover thead th {
        font-size: 0.8rem !important;
        font-weight: 600;
      }
      
      /* 테이블 여백 조정 */
      .table-hover td, 
      .table-hover th {
        padding: 0.5rem !important; /* 셀 패딩 줄임 */
      }
      
      /* 모바일에서 더 작은 폰트 */
      @media (max-width: 768px) {
        #enderose_list_01,
        #enderose_list_01 td,
        #enderose_list_01 th {
          font-size: 0.75rem !important;
        }
      }
    `;
  }
}


// 모바일 카드 뷰 업데이트 함수 (수정)
function updateHomeEndorseMobileView(data) {
  // 모바일 카드 컨테이너 가져오기
  const cardsContainer = document.getElementById('driver_cards_enderose_list_01_container');
  
  // 컨테이너가 존재하지 않는 경우
  if (!cardsContainer) {
    console.error('driver_cards_enderose_list_01_container 요소를 찾을 수 없습니다.');
    return;
  }
  
  // 컨테이너 초기화 및 스타일 설정
  cardsContainer.innerHTML = '';
  
  // 진행 중인 배서 카운트 계산
  const endorseCount = data && data.success && Array.isArray(data.data) ? data.data.length : 0;
  
  // 모바일 헤더 카드 생성
  const headerCard = document.createElement('div');
  headerCard.className = 'card endorse-header-card mobile-only-header';
  headerCard.innerHTML = `
    <div class="card-body py-2">
      <div class="d-flex justify-content-between align-items-center">
        <h5 class="mb-0">진행중인 배서</h5>
        <span class="badge bg-primary">${endorseCount}건</span>
      </div>
    </div>
  `;
  
  // 컨테이너에 헤더 카드 추가
  cardsContainer.appendChild(headerCard);
  
  // 데이터가 유효한지 확인 
  if (!data || !data.success || !Array.isArray(data.data) || data.data.length === 0) {
    // 데이터 없음 상태 표시
    const emptyCard = document.createElement('div');
    emptyCard.className = 'card endorse-empty-card';
    emptyCard.innerHTML = `
      <div class="card-body py-3 text-center">
        <div class="mb-2">
          <i class="fas fa-clipboard-check" style="font-size: 1.5rem; color: #6c757d;"></i>
        </div>
        <h6 class="mb-1">배서 신청 내역이 없습니다</h6>
        <p class="small text-muted mb-0">현재 처리 중인 배서 신청 건이 없습니다.</p>
      </div>
    `;
    cardsContainer.appendChild(emptyCard);
    return;
  }
  
  // 카드 컨테이너 생성 - 모든 카드를 감싸는 부모 컨테이너
  const cardsWrapper = document.createElement('div');
  cardsWrapper.className = 'endorse-cards-wrapper';
  
  // 각 데이터 항목에 대해 카드 생성
  data.data.forEach((item, index) => {
    // 주민번호 마스킹 처리
   const maskedJumin = item.Jumin ? 
      // 하이픈 포함 형식 처리
      `${item.Jumin.substring(0, 8)}******` : 
      '정보 없음';
    
   
    
    // 서비스 타입 및 아이콘 설정
    let serviceType, badgeClass, iconClass;
    switch(item.etag) {
      case "1": 
        serviceType = "대리"; 
        badgeClass = 'bg-success';
        iconClass = 'fa-car';
        break;
      case "2": 
        serviceType = "탁송"; 
        badgeClass = 'bg-info';
        iconClass = 'fa-truck';
        break;
      case "3": 
        serviceType = "대리+렌트"; 
        badgeClass = 'bg-warning';
        iconClass = 'fa-car-side';
        break;
      case "4": 
        serviceType = "탁송+렌트"; 
        badgeClass = 'bg-danger';
        iconClass = 'fa-truck-moving';
        break;
      default: 
        serviceType = "미지정";
        badgeClass = 'bg-secondary';
        iconClass = 'fa-question-circle';
    }
    
    // 보험회사 정보 처리
    let InsuranceCompany = "";
    if (item.InsuranceCompany) {
      const icompanyType = { "1": "흥국", "2": "DB", "3": "KB", "4": "현대", "5": "현대", "6": "롯데", "7": "MG", "8": "삼성" };
      InsuranceCompany = icompanyType[item.InsuranceCompany] || "";
    }
    
    // 카드 요소 생성
    const card = document.createElement('div');
    card.className = 'card endorse-card';
    card.style.marginBottom = '10px'; // 카드 간 간격 설정
    //청약 1 해지 4
	const pType={"1":"청약중","4":"해지중"}
	const pushText=pType[item.push]||"알 수 없음";

	// badgeColor 변수를 수정하고 기본 클래스 추가
		const badgeColor = item.push === "4" ? "bg-warning" : (item.push === "1" ? "bg-primary" : "bg-secondary");

    // 카드 내용 설정
    card.innerHTML = `
      <div class="card-body py-2">
        <h6 class="card-title d-flex justify-content-between align-items-center mb-2">
          ${item.Name || '정보 없음'} <small>(${index + 1}번)</small>
          <span class="badge ${badgeColor} ">${pushText}</span>
        </h6>
        
        <div class="info-row mb-1">
          <div class="row">
            <div class="col-5 info-label">주민번호</div>
            <div class="col-7 info-value">${maskedJumin}</div>
          </div>
        </div>
        
        <div class="info-row mb-1">
          <div class="row">
            <div class="col-5 info-label">핸드폰</div>
            <div class="col-7 info-value">${item.Hphone}</div>
          </div>
        </div>
        
        <div class="info-row mb-1">
          <div class="row">
            <div class="col-5 info-label">보험사</div>
            <div class="col-7 info-value">${InsuranceCompany || '정보 없음'}</div>
          </div>
        </div>
        
        <div class="info-row mb-1">
          <div class="row">
            <div class="col-5 info-label">증권번호</div>
            <div class="col-7 info-value">${item.dongbuCerti || '정보 없음'}</div>
          </div>
        </div>
        
        <div class="info-row">
          <div class="row">
            <div class="col-5 info-label">증권성격</div>
            <div class="col-7 info-value">
              <span class="badge ${badgeClass}">
                <i class="fas ${iconClass} me-1"></i>${serviceType || '정보 없음'}
              </span>
            </div>
          </div>
        </div>
      </div>
    `;
    
    // 카드 래퍼에 카드 추가
    cardsWrapper.appendChild(card);
  });
  
  // 카드 래퍼를 컨테이너에 추가
  cardsContainer.appendChild(cardsWrapper);
  
  // 카드 스타일링 - CSS 업데이트
  const styleElement = document.getElementById('endorse-mobile-styles');
  if (!styleElement) {
    const style = document.createElement('style');
    style.id = 'endorse-mobile-styles';
    style.textContent = `
      /* 모바일 헤더 카드 스타일 */
      .endorse-header-card {
        border-radius: 8px 8px 0 0;
        box-shadow: 0 1px 3px rgba(0,0,0,0.05);
        margin-bottom: 0 !important;
        border-bottom: none;
      }
      .endorse-header-card .card-body {
        padding-top: 12px;
        padding-bottom: 12px;
        background-color: #f8f9fa;
      }
      .endorse-header-card h5 {
        font-size: 1.1rem;
        font-weight: 600;
        color: #495057;
      }
      
      /* 카드 래퍼 스타일 */
      .endorse-cards-wrapper {
        border: 1px solid rgba(0,0,0,.125);
        border-top: none;
        border-radius: 0 0 8px 8px;
        padding: 10px;
        background-color: #fff;
        margin-bottom: 10px;
      }
      
      /* 빈 상태 카드 스타일 */
      .endorse-empty-card {
        border-radius: 0 0 8px 8px;
        border-top: none;
        box-shadow: 0 1px 3px rgba(0,0,0,0.05);
      }
      
      /* 개별 배서 카드 스타일 */
      .endorse-card {
        border-radius: 8px;
        box-shadow: 0 1px 3px rgba(0,0,0,0.05);
        transition: all 0.3s ease;
        margin-bottom: 10px;
      }
      .endorse-card:last-child {
        margin-bottom: 0;
      }
      .endorse-card:hover {
        box-shadow: 0 2px 5px rgba(0,0,0,0.08);
      }
      .endorse-card .card-body {
        padding-top: 10px;
        padding-bottom: 10px;
      }
      .endorse-card .info-label {
        color: #6c757d;
        font-weight: 500;
        font-size: 0.85rem;
      }
      .endorse-card .info-value {
        font-size: 0.85rem;
      }
      .endorse-card .card-title {
        font-size: 1rem;
        border-bottom: 1px solid #f0f0f0;
        padding-bottom: 5px;
        margin-bottom: 8px;
      }
      
      /* 상태 뱃지 스타일 */
      .endorse-card .badge {
        font-size: 0.75rem;
        padding: 4px 8px;
      }
      
      /* 모바일 뷰 여백 조정 */
      #driver_cards_enderose_list_01_container {
        margin-top: 10px !important;
		margin-bottom: 30px !important; /* 아래쪽 여백 추가 */
      }
      
      /* 반응형 표시 설정 */
      /* 데스크톱에서 */
      @media (min-width: 769px) {
        /* 모바일 전용 헤더 숨기기 */
        .mobile-only-header {
          display: none !important;
        }
      }
      
      /* 모바일에서 */
      @media (max-width: 768px) {
        /* 기존 데스크톱 헤더 숨기기 */
        .enderose-header-desktop, 
        #enderose-header-desktop,
        .row.mb-3 h5,
        .dashboard-card h5:not(.mobile-only-header h5) {
          display: none !important;
        }
        
        /* 모바일용 헤더 표시 */
        .mobile-only-header {
          display: block !important;
        }
      }
    `;
    document.head.appendChild(style);
  } else {
    // 기존 스타일 요소의 내용 업데이트
    styleElement.textContent = `
      /* 모바일 헤더 카드 스타일 */
      .endorse-header-card {
        border-radius: 8px 8px 0 0;
        box-shadow: 0 1px 3px rgba(0,0,0,0.05);
        margin-bottom: 0 !important;
        border-bottom: none;
      }
      .endorse-header-card .card-body {
        padding-top: 12px;
        padding-bottom: 12px;
        background-color: #f8f9fa;
      }
      .endorse-header-card h5 {
        font-size: 1.1rem;
        font-weight: 600;
        color: #495057;
      }
      
      /* 카드 래퍼 스타일 */
      .endorse-cards-wrapper {
        border: 1px solid rgba(0,0,0,.125);
        border-top: none;
        border-radius: 0 0 8px 8px;
        padding: 10px;
        background-color: #fff;
        margin-bottom: 10px;
      }
      
      /* 빈 상태 카드 스타일 */
      .endorse-empty-card {
        border-radius: 0 0 8px 8px;
        border-top: none;
        box-shadow: 0 1px 3px rgba(0,0,0,0.05);
      }
      
      /* 개별 배서 카드 스타일 */
      .endorse-card {
        border-radius: 8px;
        box-shadow: 0 1px 3px rgba(0,0,0,0.05);
        transition: all 0.3s ease;
        margin-bottom: 10px;
      }
      .endorse-card:last-child {
        margin-bottom: 0;
      }
      .endorse-card:hover {
        box-shadow: 0 2px 5px rgba(0,0,0,0.08);
      }
      .endorse-card .card-body {
        padding-top: 10px;
        padding-bottom: 10px;
      }
      .endorse-card .info-label {
        color: #6c757d;
        font-weight: 500;
        font-size: 0.85rem;
      }
      .endorse-card .info-value {
        font-size: 0.85rem;
      }
      .endorse-card .card-title {
        font-size: 1rem;
        border-bottom: 1px solid #f0f0f0;
        padding-bottom: 5px;
        margin-bottom: 8px;
      }
      
      /* 상태 뱃지 스타일 */
      .endorse-card .badge {
        font-size: 0.75rem;
        padding: 4px 8px;
      }
      
      /* 모바일 뷰 여백 조정 */
      #driver_cards_enderose_list_01_container {
        margin-top: 10px !important;
      }
      
      /* 반응형 표시 설정 */
      /* 데스크톱에서 */
      @media (min-width: 769px) {
        /* 모바일 전용 헤더 숨기기 */
        .mobile-only-header {
          display: none !important;
        }
      }
      
      /* 모바일에서 */
      @media (max-width: 768px) {
        /* 기존 데스크톱 헤더 숨기기 */
        .enderose-header-desktop, 
        #enderose-header-desktop,
        .row.mb-3 h5,
        .dashboard-card h5:not(.mobile-only-header h5) {
          display: none !important;
        }
        
        /* 모바일용 헤더 표시 */
        .mobile-only-header {
          display: block !important;
        }
      }
    `;
  }
  
  // 데스크톱 헤더에 클래스 추가 (이 코드 추가)
  const desktopHeader = document.querySelector('.dashboard-card h5');
  if (desktopHeader) {
    desktopHeader.classList.add('enderose-header-desktop');
  }
}
// 오늘의 배서 리스트 조회하기 (완료된 배서)
function toDayEndorseAfter(cNum) {
  // 이미 로딩 중이면 중복 호출 방지
  if (isEndorseAfterLoading) {
    console.log('완료된 배서 데이터가 이미 로드 중입니다.');
    return;
  }
  
  isEndorseAfterLoading = true;

  // 로딩 인디케이터 표시
  showLoading();
  
  if (!cNum) {
    hideLoading();
    console.error('cNum 값이 없습니다.');
    isEndorseAfterLoading = false; // 플래그 리셋
    return;
  }
  
  // 서버에 데이터 요청 (FormData 사용)
  const formData = new FormData();
  formData.append('cNum', cNum);
  formData.append('endroseDay', calculateEndorsementDate());
  
  fetch('./api/customer/home_data_endorse_after.php', {
    method: 'POST',
    body: formData
  })
  .then(response => {
    if (!response.ok) {
      throw new Error('서버 응답 오류: ' + response.status);
    }
    return response.json();
  })
  .then(data => {
    // 데이터 성공적으로 받아온 경우 UI 업데이트
    updateHomeEndorseAfterUI(data);
    // 모바일 카드 뷰 업데이트 추가
    updateHomeEndorseAfterMobileView(data);
    hideLoading();
    isEndorseAfterLoading = false; // 플래그 리셋
  })
  .catch(error => {
    console.error('데이터 로드 중 오류 발생:', error);
    showErrorMessage('데이터를 불러오는 중 오류가 발생했습니다.');
    hideLoading();
    isEndorseAfterLoading = false; // 플래그 리셋
  });
}

// 배서 리스트 
function updateHomeEndorseAfterUI(data) {
  console.log("완료된 배서 데이터:", data);
  
  // 테이블 본문 요소 가져오기
  const tableBody = document.getElementById('enderose_list_02');
  
  // 테이블이 존재하지 않는 경우
  if (!tableBody) {
    console.error('enderose_list_02 요소를 찾을 수 없습니다.');
    return;
  }
  
  // 테이블과 컨테이너 요소
  const tableEl = tableBody.closest('table');
  const tableContainer = tableEl ? tableEl.parentNode : null;
  
  // 기존 "데이터 없음" 메시지 제거
  const existingEmptyState = document.querySelector('.empty-state-container-after');
  if (existingEmptyState) {
    existingEmptyState.remove();
  }
  
  // 완료된 배서 카운트 업데이트
  const completedCountElem = document.getElementById('completed-count');
  if (completedCountElem) {
    completedCountElem.textContent = data && data.data ? `${data.data.length}건` : '0건';
  }
  
  // 데이터가 유효한지 확인 
  if (!data || !data.success || !Array.isArray(data.data) || data.data.length === 0) {
    // 테이블 숨기기
    if (tableEl) {
      tableEl.style.display = 'none';
    }
    
    // 데이터 없음 상태 표시
    const emptyState = document.createElement('div');
    emptyState.className = 'empty-state-container-after my-4 p-4 text-center';
    emptyState.innerHTML = `
      <div class="empty-state-icon mb-3">
        <i class="fas fa-clipboard-check" style="font-size: 2.5rem; color: #28a745;"></i>
      </div>
      <h5 class="mb-2">완료된 배서 내역이 없습니다</h5>
      <p class="text-muted">현재 완료된 배서 신청 건이 없습니다.</p>
    `;
    
    if (tableContainer) {
      tableContainer.appendChild(emptyState);
    }
    return;
  }
  
  // 데이터가 있는 경우, 테이블 표시
  if (tableEl) {
    tableEl.style.display = ''; // 기본 display 값 사용
    tableEl.classList.add('table-mobile-stack'); // 모바일 스택 클래스 추가
  }
  
  // 테이블 본문 초기화
  tableBody.innerHTML = '';
  
  // 각 데이터 행에 대해 처리
  data.data.forEach((item, index) => {
    const row = document.createElement('tr');
    
    // 주민번호 마스킹 처리
   const maskedJumin = item.Jumin ? 
      // 하이픈 포함 형식 처리
      `${item.Jumin.substring(0, 8)}******` : 
      '정보 없음';
    
    
    
    // 보험회사 정보 처리
    let InsuranceCompany = "";
    if (item.InsuranceCompany) {
      const icompanyType = { "1": "흥국", "2": "DB", "3": "KB", "4": "현대", "5": "현대", "6": "롯데", "7": "MG", "8": "삼성" };
      InsuranceCompany = icompanyType[item.InsuranceCompany] || "";
    }
    
    // 서비스 타입 및 아이콘 설정
    let serviceType, badgeClass, iconClass;
    switch(item.etag) {
      case "1": 
        serviceType = "대리"; 
        badgeClass = 'bg-success';
        iconClass = 'fa-car';
        break;
      case "2": 
        serviceType = "탁송"; 
        badgeClass = 'bg-info';
        iconClass = 'fa-truck';
        break;
      case "3": 
        serviceType = "대리+렌트"; 
        badgeClass = 'bg-warning';
        iconClass = 'fa-car-side';
        break;
      case "4": 
        serviceType = "탁송+렌트"; 
        badgeClass = 'bg-danger';
        iconClass = 'fa-truck-moving';
        break;
      default: 
        serviceType = "미지정";
        badgeClass = 'bg-secondary';
        iconClass = 'fa-question-circle';
    }
    
    // HTML 내용 설정 - data-label 속성 추가
    row.innerHTML = `
      <td data-label="번호">${index + 1}</td>
      <td data-label="성명">${item.Name || '정보 없음'}</td>
      <td data-label="주민번호">${maskedJumin}</td>
      <td data-label="핸드폰">${item.Hphone}</td>
      <td data-label="보험회사">${InsuranceCompany}</td>
      <td data-label="증권번호">${item.dongbuCerti || '정보 없음'}</td>
      <td data-label="증권성격">${serviceType || '정보 없음'}</td>
      <td data-label="상태"><span class="badge bg-success small">완료</span></td>
    `;
    
    // 테이블에 행 추가
    tableBody.appendChild(row);
  });

  // 테이블 스타일 업데이트 함수 호출
  updateAfterTableStyles();
}

// 스타일 요소 추가 또는 업데이트
function updateAfterTableStyles() {
  const styleElement = document.getElementById('table-after-custom-styles');
  if (!styleElement) {
    const style = document.createElement('style');
    style.id = 'table-after-custom-styles';
    style.textContent = `
      /* 테이블 폰트 크기 조정 */
      #enderose_list_02,
      #enderose_list_02 td,
      #enderose_list_02 th,
      .table-hover thead th {
        font-size: 0.85rem !important; /* 폰트 크기 줄임 */
      }
      
      /* 헤더 폰트 크기 조정 */
      .table-hover thead th {
        font-size: 0.8rem !important;
        font-weight: 600;
      }
      
      /* 테이블 여백 조정 */
      .table-hover td, 
      .table-hover th {
        padding: 0.5rem !important; /* 셀 패딩 줄임 */
      }
      
      /* 모바일에서 더 작은 폰트 */
      @media (max-width: 768px) {
        #enderose_list_02,
        #enderose_list_02 td,
        #enderose_list_02 th {
          font-size: 0.75rem !important;
        }
      }
    `;
    document.head.appendChild(style);
  } else {
    // 기존 스타일 요소가 있다면 내용 업데이트
    styleElement.textContent += `
      /* 테이블 폰트 크기 조정 */
      #enderose_list_02,
      #enderose_list_02 td,
      #enderose_list_02 th,
      .table-hover thead th {
        font-size: 0.85rem !important; /* 폰트 크기 줄임 */
      }
      
      /* 헤더 폰트 크기 조정 */
      .table-hover thead th {
        font-size: 0.8rem !important;
        font-weight: 600;
      }
      
      /* 테이블 여백 조정 */
      .table-hover td, 
      .table-hover th {
        padding: 0.5rem !important; /* 셀 패딩 줄임 */
      }
      
      /* 모바일에서 더 작은 폰트 */
      @media (max-width: 768px) {
        #enderose_list_02,
        #enderose_list_02 td,
        #enderose_list_02 th {
          font-size: 0.75rem !important;
        }
      }
    `;
  }
}

// 모바일 카드 뷰 업데이트 함수
function updateHomeEndorseAfterMobileView(data) {
  // 모바일 카드 컨테이너 가져오기
  const cardsContainer = document.getElementById('driver_cards_enderose_list_02_container');
  
  // 컨테이너가 존재하지 않는 경우
  if (!cardsContainer) {
    console.error('driver_cards_enderose_list_02_container 요소를 찾을 수 없습니다.');
    return;
  }
  
  // 컨테이너 초기화 및 스타일 설정
  cardsContainer.innerHTML = '';
  
  // 완료된 배서 카운트 계산
  const endorseCount = data && data.success && Array.isArray(data.data) ? data.data.length : 0;
  
  // 모바일 헤더 카드 생성
  const headerCard = document.createElement('div');
  headerCard.className = 'card endorse-after-header-card mobile-only-header';
  headerCard.innerHTML = `
    <div class="card-body py-2">
      <div class="d-flex justify-content-between align-items-center">
        <h5 class="mb-0">완료된 배서</h5>
        <span class="badge bg-success">${endorseCount}건</span>
      </div>
    </div>
  `;
  
  // 컨테이너에 헤더 카드 추가
  cardsContainer.appendChild(headerCard);
  
  // 데이터가 유효한지 확인 
  if (!data || !data.success || !Array.isArray(data.data) || data.data.length === 0) {
    // 데이터 없음 상태 표시
    const emptyCard = document.createElement('div');
    emptyCard.className = 'card endorse-after-empty-card';
    emptyCard.innerHTML = `
      <div class="card-body py-3 text-center">
        <div class="mb-2">
          <i class="fas fa-clipboard-check" style="font-size: 1.5rem; color: #28a745;"></i>
        </div>
        <h6 class="mb-1">완료된 배서 내역이 없습니다</h6>
        <p class="small text-muted mb-0">현재 완료된 배서 신청 건이 없습니다.</p>
      </div>
    `;
    cardsContainer.appendChild(emptyCard);
    return;
  }
  
  // 카드 컨테이너 생성 - 모든 카드를 감싸는 부모 컨테이너
  const cardsWrapper = document.createElement('div');
  cardsWrapper.className = 'endorse-after-cards-wrapper';
  
  // 각 데이터 항목에 대해 카드 생성
  data.data.forEach((item, index) => {
    // 주민번호 마스킹 처리
    const maskedJumin = item.Jumin ? 
      // 하이픈 포함 형식 처리
      `${item.Jumin.substring(0, 8)}******` : 
      '정보 없음';
    
    
    
    // 서비스 타입 및 아이콘 설정
    let serviceType, badgeClass, iconClass;
    switch(item.etag) {
      case "1": 
        serviceType = "대리"; 
        badgeClass = 'bg-success';
        iconClass = 'fa-car';
        break;
      case "2": 
        serviceType = "탁송"; 
        badgeClass = 'bg-info';
        iconClass = 'fa-truck';
        break;
      case "3": 
        serviceType = "대리+렌트"; 
        badgeClass = 'bg-warning';
        iconClass = 'fa-car-side';
        break;
      case "4": 
        serviceType = "탁송+렌트"; 
        badgeClass = 'bg-danger';
        iconClass = 'fa-truck-moving';
        break;
      default: 
        serviceType = "미지정";
        badgeClass = 'bg-secondary';
        iconClass = 'fa-question-circle';
    }
    
    // 보험회사 정보 처리
    let InsuranceCompany = "";
    if (item.InsuranceCompany) {
      const icompanyType = { "1": "흥국", "2": "DB", "3": "KB", "4": "현대", "5": "현대", "6": "롯데", "7": "MG", "8": "삼성" };
      InsuranceCompany = icompanyType[item.InsuranceCompany] || "";
    }
    
    // 카드 요소 생성
    const card = document.createElement('div');
    card.className = 'card endorse-after-card';
    card.style.marginBottom = '10px'; // 카드 간 간격 설정
    
    // 카드 내용 설정
    card.innerHTML = `
      <div class="card-body py-2">
        <h6 class="card-title d-flex justify-content-between align-items-center mb-2">
          ${item.Name || '정보 없음'} <small>(${index + 1}번)</small>
          <span class="badge bg-success">완료</span>
        </h6>
        
        <div class="info-row mb-1">
          <div class="row">
            <div class="col-5 info-label">주민번호</div>
            <div class="col-7 info-value">${maskedJumin}</div>
          </div>
        </div>
        
        <div class="info-row mb-1">
          <div class="row">
            <div class="col-5 info-label">핸드폰</div>
            <div class="col-7 info-value">${item.Hphone}</div>
          </div>
        </div>
        
        <div class="info-row mb-1">
          <div class="row">
            <div class="col-5 info-label">보험사</div>
            <div class="col-7 info-value">${InsuranceCompany || '정보 없음'}</div>
          </div>
        </div>
        
        <div class="info-row mb-1">
          <div class="row">
            <div class="col-5 info-label">증권번호</div>
            <div class="col-7 info-value">${item.dongbuCerti || '정보 없음'}</div>
          </div>
        </div>
        
        <div class="info-row">
          <div class="row">
            <div class="col-5 info-label">증권성격</div>
            <div class="col-7 info-value">
              <span class="badge ${badgeClass}">
                <i class="fas ${iconClass} me-1"></i>${serviceType || '정보 없음'}
              </span>
            </div>
          </div>
        </div>
      </div>
    `;
    
    // 카드 래퍼에 카드 추가
    cardsWrapper.appendChild(card);
  });
  
  // 카드 래퍼를 컨테이너에 추가
  cardsContainer.appendChild(cardsWrapper);
  
  // 카드 스타일링 - CSS 업데이트
  const styleElement = document.getElementById('endorse-after-mobile-styles');
  if (!styleElement) {
    const style = document.createElement('style');
    style.id = 'endorse-after-mobile-styles';
    style.textContent = `
      /* 모바일 헤더 카드 스타일 */
      .endorse-after-header-card {
        border-radius: 8px 8px 0 0;
        box-shadow: 0 1px 3px rgba(0,0,0,0.05);
        margin-bottom: 0 !important;
        border-bottom: none;
      }
      .endorse-after-header-card .card-body {
        padding-top: 12px;
        padding-bottom: 12px;
        background-color: #f8f9fa;
      }
      .endorse-after-header-card h5 {
        font-size: 1.1rem;
        font-weight: 600;
        color: #495057;
      }
      
      /* 카드 래퍼 스타일 */
      .endorse-after-cards-wrapper {
        border: 1px solid rgba(0,0,0,.125);
        border-top: none;
        border-radius: 0 0 8px 8px;
        padding: 10px;
        background-color: #fff;
        margin-bottom: 10px;
      }
      
      /* 빈 상태 카드 스타일 */
      .endorse-after-empty-card {
        border-radius: 0 0 8px 8px;
        border-top: none;
        box-shadow: 0 1px 3px rgba(0,0,0,0.05);
      }
      
      /* 개별 배서 카드 스타일 */
      .endorse-after-card {
        border-radius: 8px;
        box-shadow: 0 1px 3px rgba(0,0,0,0.05);
        transition: all 0.3s ease;
        margin-bottom: 10px;
      }
      .endorse-after-card:last-child {
        margin-bottom: 0;
      }
      .endorse-after-card:hover {
        box-shadow: 0 2px 5px rgba(0,0,0,0.08);
      }
      .endorse-after-card .card-body {
        padding-top: 10px;
        padding-bottom: 10px;
      }
      .endorse-after-card .info-label {
        color: #6c757d;
        font-weight: 500;
        font-size: 0.85rem;
      }
      .endorse-after-card .info-value {
        font-size: 0.85rem;
      }
      .endorse-after-card .card-title {
        font-size: 1rem;
        border-bottom: 1px solid #f0f0f0;
        padding-bottom: 5px;
        margin-bottom: 8px;
      }
      
      /* 상태 뱃지 스타일 */
      .endorse-after-card .badge {
        font-size: 0.75rem;
        padding: 4px 8px;
      }
      
      /* 모바일 뷰 여백 조정 */
      #driver_cards_enderose_list_02_container {
        margin-top: 30px !important; /* 더 큰 위쪽 여백 추가 */
      }
      
      /* 반응형 표시 설정 */
      /* 데스크톱에서 */
      @media (min-width: 769px) {
        /* 모바일 전용 헤더 숨기기 */
        .mobile-only-header {
          display: none !important;
        }
      }
      
      /* 모바일에서 */
      @media (max-width: 768px) {
        /* 기존 데스크톱 헤더 숨기기 */
        .enderose-after-header-desktop, 
        #enderose-after-header-desktop,
        .row.mb-3 h5:not(.endorse-after-header-card h5),
        .dashboard-card h5:not(.endorse-after-header-card h5) {
          display: none !important;
        }
        
        /* 모바일용 헤더 표시 */
        .mobile-only-header {
          display: block !important;
        }
      }
    `;
    document.head.appendChild(style);
  } else {
    // 기존 스타일 요소의 내용 업데이트
    styleElement.textContent = style.textContent;
  }
  
  // 데스크톱 헤더에 클래스 추가
  const desktopHeader = document.querySelector('.dashboard-card h5');
  if (desktopHeader) {
    desktopHeader.classList.add('enderose-after-header-desktop');
  }
}



