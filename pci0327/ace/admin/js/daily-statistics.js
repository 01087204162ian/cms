/**
 * 일일현황 및 월별현황 관리 스크립트
 * PCI Korea 홀인원보험 관리 시스템
 */

// 전역 변수
let statisticsData = {
    daily: {},
    monthly: {}
};

// 일일현황 모달 열기
function dailyApplications() {
    // 모달이 존재하지 않으면 생성
    if (!document.getElementById('statisticsModal')) {
        createStatisticsModal();
    }
    
    // 현재 월로 초기화
    const now = new Date();
    const currentYear = now.getFullYear();
    const currentMonth = now.getMonth() + 1;
    
    // 일일현황 탭을 기본으로 설정
    document.getElementById('daily-tab').click();
    
    // 년/월 선택 초기화
    document.getElementById('dailyYear').value = currentYear;
    document.getElementById('dailyMonth').value = currentMonth;
    document.getElementById('monthlyYear').value = currentYear;
    
    // 모달 표시
    const modal = new bootstrap.Modal(document.getElementById('statisticsModal'));
    modal.show();
    
    // 초기 데이터 로드
    loadDailyStatistics(currentYear, currentMonth);
}

// 통계 모달 HTML 생성
function createStatisticsModal() {
    const modalHTML = `
        <div class="modal fade" id="statisticsModal" tabindex="-1" aria-labelledby="statisticsModalLabel" aria-hidden="true">
            <div class="modal-dialog modal-xl">
                <div class="modal-content">
                    <div class="modal-header bg-primary text-white">
                        <h5 class="modal-title" id="statisticsModalLabel">
                            <i class="bi bi-graph-up me-2"></i>신청 현황 통계
                        </h5>
                        <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>
                    
                    <div class="modal-body p-0">
                        <!-- 탭 네비게이션 -->
                        <ul class="nav nav-tabs nav-fill" id="statisticsTabs" role="tablist">
                            <li class="nav-item" role="presentation">
                                <button class="nav-link active" id="daily-tab" data-bs-toggle="tab" 
                                        data-bs-target="#daily-statistics" type="button" role="tab">
                                    <i class="bi bi-calendar-day me-1"></i>일일현황
                                </button>
                            </li>
                            <li class="nav-item" role="presentation">
                                <button class="nav-link" id="monthly-tab" data-bs-toggle="tab" 
                                        data-bs-target="#monthly-statistics" type="button" role="tab">
                                    <i class="bi bi-calendar-month me-1"></i>월별현황
                                </button>
                            </li>
                        </ul>

                        <!-- 탭 컨텐츠 -->
                        <div class="tab-content" id="statisticsTabContent">
                            <!-- 일일현황 탭 -->
                            <div class="tab-pane fade show active" id="daily-statistics" role="tabpanel">
                                <div class="p-4">
                                    <!-- 조건 선택 영역 -->
                                    <div class="row mb-4">
                                        <div class="col-md-3">
                                            <label class="form-label fw-medium">조회년도</label>
                                            <select class="form-select" id="dailyYear" onchange="onDailyConditionChange()">
                                                <!-- JavaScript로 동적 생성 -->
                                            </select>
                                        </div>
                                        <div class="col-md-3">
                                            <label class="form-label fw-medium">조회월</label>
                                            <select class="form-select" id="dailyMonth" onchange="onDailyConditionChange()">
                                                <option value="1">1월</option>
                                                <option value="2">2월</option>
                                                <option value="3">3월</option>
                                                <option value="4">4월</option>
                                                <option value="5">5월</option>
                                                <option value="6">6월</option>
                                                <option value="7">7월</option>
                                                <option value="8">8월</option>
                                                <option value="9">9월</option>
                                                <option value="10">10월</option>
                                                <option value="11">11월</option>
                                                <option value="12">12월</option>
                                            </select>
                                        </div>
                                        <div class="col-md-6 d-flex align-items-end">
                                            <div class="ms-auto">
                                                <button class="btn btn-success btn-sm me-2" onclick="exportDailyStatistics()">
                                                    <i class="bi bi-download me-1"></i>Excel 다운로드
                                                </button>
                                            </div>
                                        </div>
                                    </div>

                                    <!-- 월간 요약 정보 -->
                                    <div class="row mb-4">
                                        <div class="col-md-3">
                                            <div class="card border-primary">
                                                <div class="card-body text-center">
                                                    <div class="text-primary">
                                                        <i class="bi bi-calendar-check" style="font-size: 2rem;"></i>
                                                    </div>
                                                    <h4 class="mt-2 mb-0" id="monthlyTotalCount">0</h4>
                                                    <small class="text-muted">총 신청건수</small>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="col-md-3">
                                            <div class="card border-success">
                                                <div class="card-body text-center">
                                                    <div class="text-success">
                                                        <i class="bi bi-calendar-day" style="font-size: 2rem;"></i>
                                                    </div>
                                                    <h4 class="mt-2 mb-0" id="dailyAverage">0</h4>
                                                    <small class="text-muted">일평균</small>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="col-md-3">
                                            <div class="card border-warning">
                                                <div class="card-body text-center">
                                                    <div class="text-warning">
                                                        <i class="bi bi-trophy" style="font-size: 2rem;"></i>
                                                    </div>
                                                    <h4 class="mt-2 mb-0" id="maxDayCount">0</h4>
                                                    <small class="text-muted">최대 신청일</small>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="col-md-3">
                                            <div class="card border-info">
                                                <div class="card-body text-center">
                                                    <div class="text-info">
                                                        <i class="bi bi-calendar-week" style="font-size: 2rem;"></i>
                                                    </div>
                                                    <h4 class="mt-2 mb-0" id="activeDays">0</h4>
                                                    <small class="text-muted">신청 있는 날</small>
                                                </div>
                                            </div>
                                        </div>
                                    </div>

                                    <!-- 일일 데이터 테이블 -->
                                    <div class="card">
                                        <div class="card-header">
                                            <h6 class="mb-0">
                                                <i class="bi bi-table me-1"></i>
                                                <span id="dailyTableTitle">일일 신청 현황</span>
                                            </h6>
                                        </div>
                                        <div class="card-body p-0">
                                            <div id="dailyTableContainer" style="max-height: 400px; overflow-y: auto;">
                                                <!-- 로딩 상태 -->
                                                <div id="dailyLoading" class="text-center py-5">
                                                    <div class="spinner-border text-primary" role="status">
                                                        <span class="visually-hidden">로딩중...</span>
                                                    </div>
                                                    <p class="text-muted mt-2">데이터를 불러오는 중...</p>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <!-- 월별현황 탭 -->
                            <div class="tab-pane fade" id="monthly-statistics" role="tabpanel">
                                <div class="p-4">
                                    <!-- 조건 선택 영역 -->
                                    <div class="row mb-4">
                                        <div class="col-md-3">
                                            <label class="form-label fw-medium">조회년도</label>
                                            <select class="form-select" id="monthlyYear" onchange="onMonthlyConditionChange()">
                                                <!-- JavaScript로 동적 생성 -->
                                            </select>
                                        </div>
                                        <div class="col-md-9 d-flex align-items-end">
                                            <div class="ms-auto">
                                                <button class="btn btn-success btn-sm me-2" onclick="exportMonthlyStatistics()">
                                                    <i class="bi bi-download me-1"></i>Excel 다운로드
                                                </button>
                                            </div>
                                        </div>
                                    </div>

                                    <!-- 연간 요약 정보 -->
                                    <div class="row mb-4">
                                        <div class="col-md-3">
                                            <div class="card border-primary">
                                                <div class="card-body text-center">
                                                    <div class="text-primary">
                                                        <i class="bi bi-calendar-check" style="font-size: 2rem;"></i>
                                                    </div>
                                                    <h4 class="mt-2 mb-0" id="yearlyTotalCount">0</h4>
                                                    <small class="text-muted">연간 총 신청</small>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="col-md-3">
                                            <div class="card border-success">
                                                <div class="card-body text-center">
                                                    <div class="text-success">
                                                        <i class="bi bi-calendar-month" style="font-size: 2rem;"></i>
                                                    </div>
                                                    <h4 class="mt-2 mb-0" id="monthlyAverage">0</h4>
                                                    <small class="text-muted">월평균</small>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="col-md-3">
                                            <div class="card border-warning">
                                                <div class="card-body text-center">
                                                    <div class="text-warning">
                                                        <i class="bi bi-trophy" style="font-size: 2rem;"></i>
                                                    </div>
                                                    <h4 class="mt-2 mb-0" id="maxMonthCount">0</h4>
                                                    <small class="text-muted">최대 신청월</small>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="col-md-3">
                                            <div class="card border-info">
                                                <div class="card-body text-center">
                                                    <div class="text-info">
                                                        <i class="bi bi-activity" style="font-size: 2rem;"></i>
                                                    </div>
                                                    <h4 class="mt-2 mb-0" id="activeMonths">0</h4>
                                                    <small class="text-muted">신청 있는 월</small>
                                                </div>
                                            </div>
                                        </div>
                                    </div>

                                    <!-- 월별 데이터 테이블 -->
                                    <div class="card">
                                        <div class="card-header">
                                            <h6 class="mb-0">
                                                <i class="bi bi-bar-chart me-1"></i>
                                                <span id="monthlyTableTitle">월별 신청 현황</span>
                                            </h6>
                                        </div>
                                        <div class="card-body p-0">
                                            <div id="monthlyTableContainer">
                                                <!-- 로딩 상태 -->
                                                <div id="monthlyLoading" class="text-center py-5">
                                                    <div class="spinner-border text-primary" role="status">
                                                        <span class="visually-hidden">로딩중...</span>
                                                    </div>
                                                    <p class="text-muted mt-2">데이터를 불러오는 중...</p>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">
                            <i class="bi bi-x-lg me-1"></i>닫기
                        </button>
                    </div>
                </div>
            </div>
        </div>
    `;
    
    // 모달을 body에 추가
    document.body.insertAdjacentHTML('beforeend', modalHTML);
    
    // 년도 옵션 생성
    initYearOptions();
    
    // 탭 전환 이벤트 리스너 추가
    document.getElementById('monthly-tab').addEventListener('shown.bs.tab', function() {
        const year = document.getElementById('monthlyYear').value;
        if (year) {
            loadMonthlyStatistics(year);
        }
    });
}

// 년도 옵션 초기화
function initYearOptions() {
    const currentYear = new Date().getFullYear();
    const startYear = 2025; // 데이터 시작 년도: 2025년 8월 11일부터
    
    const dailyYearSelect = document.getElementById('dailyYear');
    const monthlyYearSelect = document.getElementById('monthlyYear');
    
    // 기존 옵션 제거
    dailyYearSelect.innerHTML = '';
    monthlyYearSelect.innerHTML = '';
    
    // 년도 옵션 추가 (현재 년도부터 시작 년도까지 역순)
    for (let year = currentYear; year >= startYear; year--) {
        const option1 = new Option(`${year}년`, year);
        const option2 = new Option(`${year}년`, year);
        
        dailyYearSelect.appendChild(option1);
        monthlyYearSelect.appendChild(option2);
    }
}

// 일일현황 조건 변경시
function onDailyConditionChange() {
    const year = document.getElementById('dailyYear').value;
    const month = document.getElementById('dailyMonth').value;
    
    if (year && month) {
        loadDailyStatistics(year, month);
    }
}

// 월별현황 조건 변경시
function onMonthlyConditionChange() {
    const year = document.getElementById('monthlyYear').value;
    
    if (year) {
        loadMonthlyStatistics(year);
    }
}

// 일일 통계 데이터 로드
async function loadDailyStatistics(year, month) {
    try {
        console.log('loadDailyStatistics 시작:', year, month);
        
        // 로딩 표시
        document.getElementById('dailyTableContainer').innerHTML = `
            <div id="dailyLoading" class="text-center py-5">
                <div class="spinner-border text-primary" role="status">
                    <span class="visually-hidden">로딩중...</span>
                </div>
                <p class="text-muted mt-2">데이터를 불러오는 중...</p>
            </div>
        `;
        
        const url = `api/statistics.php?type=daily&year=${year}&month=${month}`;
        console.log('요청 URL:', url);
        
        const response = await fetch(url);
        console.log('응답 상태:', response.status, response.statusText);
        
        if (!response.ok) {
            console.error('응답 에러:', response.status, response.statusText);
            if (response.status === 401) {
                console.log('인증 실패 - 로그인 페이지로 이동');
                alert('세션이 만료되었습니다. 다시 로그인해주세요.');
                window.location.href = 'index.html';
                return;
            }
            throw new Error(`HTTP ${response.status}: ${response.statusText}`);
        }
        
        const data = await response.json();
        console.log('응답 데이터:', data);
        
        if (data.success) {
            console.log('데이터 로드 성공');
            statisticsData.daily = data.data;
            renderDailyStatistics(year, month, data.data);
        } else {
            console.error('API 에러:', data.message);
            throw new Error(data.message || '데이터를 불러오는데 실패했습니다.');
        }
    } catch (error) {
        console.error('Daily statistics load error:', error);
        
        // 네트워크 오류인지 확인
        if (error.name === 'TypeError' && error.message.includes('fetch')) {
            document.getElementById('dailyTableContainer').innerHTML = `
                <div class="text-center py-5">
                    <i class="bi bi-exclamation-triangle text-warning" style="font-size: 3rem;"></i>
                    <p class="text-warning mt-3">서버에 연결할 수 없습니다.</p>
                    <p class="text-muted">네트워크 연결을 확인해주세요.</p>
                    <button class="btn btn-primary" onclick="loadDailyStatistics(${year}, ${month})">다시 시도</button>
                </div>
            `;
        } else {
            document.getElementById('dailyTableContainer').innerHTML = `
                <div class="text-center py-5">
                    <i class="bi bi-exclamation-triangle text-danger" style="font-size: 3rem;"></i>
                    <p class="text-danger mt-3">데이터를 불러올 수 없습니다.</p>
                    <p class="text-muted">${error.message}</p>
                    <button class="btn btn-primary" onclick="loadDailyStatistics(${year}, ${month})">다시 시도</button>
                </div>
            `;
        }
    }
}

// 월별 통계 데이터 로드
async function loadMonthlyStatistics(year) {
    try {
        // 로딩 표시
        document.getElementById('monthlyTableContainer').innerHTML = `
            <div id="monthlyLoading" class="text-center py-5">
                <div class="spinner-border text-primary" role="status">
                    <span class="visually-hidden">로딩중...</span>
                </div>
                <p class="text-muted mt-2">데이터를 불러오는 중...</p>
            </div>
        `;
        
        const response = await fetch(`api/statistics.php?type=monthly&year=${year}`);
        
        if (!response.ok) {
            if (response.status === 401) {
                window.location.href = 'index.html';
                return;
            }
            throw new Error('Network response was not ok');
        }
        
        const data = await response.json();
        
        if (data.success) {
            statisticsData.monthly = data.data;
            renderMonthlyStatistics(year, data.data);
        } else {
            throw new Error(data.message || '데이터를 불러오는데 실패했습니다.');
        }
    } catch (error) {
        console.error('Monthly statistics load error:', error);
        document.getElementById('monthlyTableContainer').innerHTML = `
            <div class="text-center py-5">
                <i class="bi bi-exclamation-triangle text-danger" style="font-size: 3rem;"></i>
                <p class="text-danger mt-3">데이터를 불러올 수 없습니다.</p>
                <button class="btn btn-primary" onclick="loadMonthlyStatistics(${year})">다시 시도</button>
            </div>
        `;
    }
}

// 일일 통계 렌더링
function renderDailyStatistics(year, month, data) {
    // 제목 업데이트
    document.getElementById('dailyTableTitle').textContent = `${year}년 ${month}월 일일 신청 현황`;
    
    // 요약 정보 업데이트
    const totalCount = data.summary.total_count;
    const dailyAvg = data.summary.daily_average;
    const maxCount = data.summary.max_count;
    const activeDays = data.summary.active_days;
    
    document.getElementById('monthlyTotalCount').textContent = numberFormat(totalCount);
    document.getElementById('dailyAverage').textContent = dailyAvg.toFixed(1);
    document.getElementById('maxDayCount').textContent = numberFormat(maxCount);
    document.getElementById('activeDays').textContent = `${activeDays}일`;
    
    // 데이터가 없는 경우 안내 메시지
    if (totalCount === 0 && (year < 2025 || (year == 2025 && month < 8))) {
        document.getElementById('dailyTableContainer').innerHTML = `
            <div class="text-center py-5">
                <i class="bi bi-info-circle text-info" style="font-size: 3rem;"></i>
                <p class="text-info mt-3">해당 기간에는 신청 데이터가 없습니다.</p>
                <p class="text-muted">시스템 데이터는 2025년 8월 11일부터 시작됩니다.</p>
            </div>
        `;
        return;
    }
    
    // 테이블 생성
    let tableHTML = `
        <table class="table table-striped table-hover mb-0">
            <thead class="table-dark sticky-top">
                <tr>
                    <th width="80">일자</th>
                    <th width="80">요일</th>
                    <th width="100">신청건수</th>
                    <th width="80">비율</th>
                    <th>그래프</th>
                </tr>
            </thead>
            <tbody>
    `;
    
    // 해당 월의 마지막 날 계산
    const lastDay = new Date(year, month, 0).getDate();
    
    // 각 일자별 데이터 생성
    for (let day = 1; day <= lastDay; day++) {
        const date = new Date(year, month - 1, day);
        const dayOfWeek = ['일', '월', '화', '수', '목', '금', '토'][date.getDay()];
        const dayData = data.daily_data.find(d => parseInt(d.day) === day);
        const count = dayData ? parseInt(dayData.count) : 0;
        const percentage = totalCount > 0 ? (count / totalCount * 100) : 0;
        
        // 2025년 8월 11일 이전은 회색 처리
        let isBeforeData = false;
        if (year == 2025 && month == 8 && day < 11) {
            isBeforeData = true;
        } else if (year < 2025 || (year == 2025 && month < 8)) {
            isBeforeData = true;
        }
        
        // 요일별 스타일
        const dayClass = date.getDay() === 0 ? 'text-danger' : (date.getDay() === 6 ? 'text-primary' : '');
        const countClass = count > 0 ? 'fw-bold text-success' : (isBeforeData ? 'text-muted' : 'text-muted');
        const rowClass = isBeforeData ? 'table-secondary' : '';
        
        tableHTML += `
            <tr class="${rowClass}">
                <td class="${dayClass} ${isBeforeData ? 'text-muted' : ''}">${day}일</td>
                <td class="${dayClass} ${isBeforeData ? 'text-muted' : ''}">${dayOfWeek}</td>
                <td class="${countClass}">${isBeforeData ? '-' : numberFormat(count)}</td>
                <td class="small text-muted">${isBeforeData ? '-' : percentage.toFixed(1) + '%'}</td>
                <td>
                    ${isBeforeData ? 
                        '<span class="text-muted small">데이터 없음</span>' : 
                        `<div class="progress" style="height: 20px;">
                            <div class="progress-bar bg-success" role="progressbar" 
                                 style="width: ${percentage}%" 
                                 aria-valuenow="${count}" aria-valuemin="0" aria-valuemax="${totalCount}">
                            </div>
                        </div>`
                    }
                </td>
            </tr>
        `;
    }
    
    tableHTML += `
            </tbody>
        </table>
    `;
    
    // 2025년 8월인 경우 안내 메시지 추가
    if (year == 2025 && month == 8) {
        tableHTML += `
            <div class="alert alert-info mt-3 mb-0">
                <i class="bi bi-info-circle me-2"></i>
                <small>시스템 데이터는 2025년 8월 11일부터 수집되기 시작했습니다.</small>
            </div>
        `;
    }
    
    document.getElementById('dailyTableContainer').innerHTML = tableHTML;
}

// 월별 통계 렌더링
function renderMonthlyStatistics(year, data) {
    // 제목 업데이트
    document.getElementById('monthlyTableTitle').textContent = `${year}년 월별 신청 현황`;
    
    // 요약 정보 업데이트
    const totalCount = data.summary.total_count;
    const monthlyAvg = data.summary.monthly_average;
    const maxCount = data.summary.max_count;
    const activeMonths = data.summary.active_months;
    
    document.getElementById('yearlyTotalCount').textContent = numberFormat(totalCount);
    document.getElementById('monthlyAverage').textContent = monthlyAvg.toFixed(1);
    document.getElementById('maxMonthCount').textContent = numberFormat(maxCount);
    document.getElementById('activeMonths').textContent = `${activeMonths}개월`;
    
    // 데이터가 없는 경우 안내 메시지
    if (totalCount === 0 && year < 2025) {
        document.getElementById('monthlyTableContainer').innerHTML = `
            <div class="text-center py-5">
                <i class="bi bi-info-circle text-info" style="font-size: 3rem;"></i>
                <p class="text-info mt-3">해당 연도에는 신청 데이터가 없습니다.</p>
                <p class="text-muted">시스템 데이터는 2025년 8월부터 시작됩니다.</p>
            </div>
        `;
        return;
    }
    
    // 테이블 생성
    let tableHTML = `
        <table class="table table-striped table-hover mb-0">
            <thead class="table-dark">
                <tr>
                    <th width="100">월</th>
                    <th width="120">신청건수</th>
                    <th width="100">비율</th>
                    <th>그래프</th>
                    <th width="120">전월대비</th>
                </tr>
            </thead>
            <tbody>
    `;
    
    const monthNames = ['1월', '2월', '3월', '4월', '5월', '6월', 
                       '7월', '8월', '9월', '10월', '11월', '12월'];
    
    let previousCount = 0;
    
    // 각 월별 데이터 생성
    for (let month = 1; month <= 12; month++) {
        const monthData = data.monthly_data.find(d => parseInt(d.month) === month);
        const count = monthData ? parseInt(monthData.count) : 0;
        const percentage = totalCount > 0 ? (count / totalCount * 100) : 0;
        
        // 2025년 8월 이전은 데이터 없음 표시
        let isBeforeData = false;
        if (year == 2025 && month < 8) {
            isBeforeData = true;
        } else if (year < 2025) {
            isBeforeData = true;
        }
        
        // 전월 대비 계산 (데이터가 있는 월만)
        let compareHTML = '-';
        if (!isBeforeData && month > 1) {
            // 이전 월 중에서 데이터가 있는 가장 최근 월 찾기
            let prevMonth = month - 1;
            let prevMonthData = null;
            
            while (prevMonth >= 1) {
                if (year == 2025 && prevMonth >= 8) {
                    prevMonthData = data.monthly_data.find(d => parseInt(d.month) === prevMonth);
                    if (prevMonthData || prevMonth == 8) break; // 8월이면 무조건 기준점
                } else if (year > 2025) {
                    prevMonthData = data.monthly_data.find(d => parseInt(d.month) === prevMonth);
                    if (prevMonthData || prevMonth == 1) break; // 1월이면 무조건 기준점
                }
                prevMonth--;
            }
            
            const prevCount = prevMonthData ? parseInt(prevMonthData.count) : 0;
            
            if (prevCount > 0) {
                const diff = count - prevCount;
                const diffPercentage = (diff / prevCount * 100);
                if (diff > 0) {
                    compareHTML = `<span class="text-success"><i class="bi bi-arrow-up"></i> +${numberFormat(diff)} (+${diffPercentage.toFixed(1)}%)</span>`;
                } else if (diff < 0) {
                    compareHTML = `<span class="text-danger"><i class="bi bi-arrow-down"></i> ${numberFormat(diff)} (${diffPercentage.toFixed(1)}%)</span>`;
                } else {
                    compareHTML = `<span class="text-muted"><i class="bi bi-dash"></i> 변화없음</span>`;
                }
            } else if (count > 0) {
                compareHTML = `<span class="text-success"><i class="bi bi-arrow-up"></i> 신규</span>`;
            }
        }
        
        const countClass = count > 0 ? 'fw-bold text-success' : (isBeforeData ? 'text-muted' : 'text-muted');
        const rowClass = isBeforeData ? 'table-secondary' : '';
        
        tableHTML += `
            <tr class="${rowClass}">
                <td class="fw-medium ${isBeforeData ? 'text-muted' : ''}">${monthNames[month - 1]}</td>
                <td class="${countClass}">${isBeforeData ? '-' : numberFormat(count)}</td>
                <td class="small text-muted">${isBeforeData ? '-' : percentage.toFixed(1) + '%'}</td>
                <td>
                    ${isBeforeData ? 
                        '<span class="text-muted small">데이터 없음</span>' : 
                        `<div class="progress" style="height: 25px;">
                            <div class="progress-bar bg-primary" role="progressbar" 
                                 style="width: ${percentage}%" 
                                 aria-valuenow="${count}" aria-valuemin="0" aria-valuemax="${totalCount}">
                            </div>
                        </div>`
                    }
                </td>
                <td class="small">${isBeforeData ? '-' : compareHTML}</td>
            </tr>
        `;
        
        if (!isBeforeData) {
            previousCount = count;
        }
    }
    
    tableHTML += `
            </tbody>
        </table>
    `;
    
    // 2025년인 경우 안내 메시지 추가
    if (year == 2025) {
        tableHTML += `
            <div class="alert alert-info mt-3 mb-0">
                <i class="bi bi-info-circle me-2"></i>
                <small>시스템 데이터는 2025년 8월부터 수집되기 시작했습니다.</small>
            </div>
        `;
    }
    
    document.getElementById('monthlyTableContainer').innerHTML = tableHTML;
}

// 일일 통계 엑셀 다운로드
async function exportDailyStatistics() {
    const year = document.getElementById('dailyYear').value;
    const month = document.getElementById('dailyMonth').value;
    
    if (!year || !month) {
        alert('년도와 월을 선택해주세요.');
        return;
    }
    
    try {
        const response = await fetch(`api/statistics_export.php?type=daily&year=${year}&month=${month}`, {
            method: 'GET'
        });
        
        if (response.ok) {
            const blob = await response.blob();
            const url = window.URL.createObjectURL(blob);
            const a = document.createElement('a');
            a.href = url;
            a.download = `일일현황_${year}년${month}월.xlsx`;
            document.body.appendChild(a);
            a.click();
            window.URL.revokeObjectURL(url);
            document.body.removeChild(a);
        } else {
            throw new Error('다운로드에 실패했습니다.');
        }
    } catch (error) {
        console.error('Export error:', error);
        alert('엑셀 다운로드에 실패했습니다.');
    }
}

// 월별 통계 엑셀 다운로드
async function exportMonthlyStatistics() {
    const year = document.getElementById('monthlyYear').value;
    
    if (!year) {
        alert('년도를 선택해주세요.');
        return;
    }
    
    try {
        const response = await fetch(`api/statistics_export.php?type=monthly&year=${year}`, {
            method: 'GET'
        });
        
        if (response.ok) {
            const blob = await response.blob();
            const url = window.URL.createObjectURL(blob);
            const a = document.createElement('a');
            a.href = url;
            a.download = `월별현황_${year}년.xlsx`;
            document.body.appendChild(a);
            a.click();
            window.URL.revokeObjectURL(url);
            document.body.removeChild(a);
        } else {
            throw new Error('다운로드에 실패했습니다.');
        }
    } catch (error) {
        console.error('Export error:', error);
        alert('엑셀 다운로드에 실패했습니다.');
    }
}

// 숫자 포맷팅 함수
function numberFormat(number) {
    return new Intl.NumberFormat('ko-KR').format(number);
}

// 파일 로드 완료 알림
console.log('daily-statistics.js 로드 완료');