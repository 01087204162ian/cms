/**
 * 데이터 분석 모달 관련 JavaScript 함수들
 * PCI Korea 홀인원보험 관리 시스템
 */

// 전역 변수
let analysisData = {
    pastTeeTime: {},
    golfCourses: [],
    fullUsedCoupons: []
};

// 네임스페이스: 모달 전용 함수들
window.DataAnalysisModal = {
    /**
     * 쿠폰 상세보기 및 데이터 정리 함수 (모달 전용)
     */
    viewCouponDetail: async function(id, coupon_number) {
        const userConfirmed = await this.showConfirmModal(
            '데이터 정리 확인',
            `쿠폰번호 ${coupon_number}의 데이터를 정리하시겠습니까?<br><small class="text-muted">이 작업은 되돌릴 수 없습니다.</small>`,
            '정리하기',
            '취소'
        );
        
        if (!userConfirmed) return;
        
        try {
            this.showToast('데이터 정리 중입니다...', 'info');
            
            const response = await fetch('api/coupon_number_summary.php', {
                method: 'POST',
                headers: { 'Content-Type': 'application/json' },
                body: JSON.stringify({
                    id: id,
                    coupon_number: coupon_number,
                    action: 'cleanup'
                })
            });
            
            if (!response.ok) {
                if (response.status === 401) {
                    window.location.href = 'index.html';
                    return;
                }
                throw new Error(`HTTP ${response.status}: ${response.statusText}`);
            }
            
            const data = await response.json();
            
            if (data.success) {
                this.showToast(data.message || '데이터 정리가 완료되었습니다.', 'success');
                
                if (data.refresh) {
                    setTimeout(() => {
                        if (typeof loadFullUsedCoupons === 'function') {
                            loadFullUsedCoupons();
                        }
                    }, 1000);
                }
            } else {
                throw new Error(data.message || '데이터 정리에 실패했습니다.');
            }
            
        } catch (error) {
            console.error('Modal coupon cleanup error:', error);
            this.showToast('데이터 정리 중 오류가 발생했습니다: ' + error.message, 'error');
        }
    },

    /**
     * 쿠폰 신청 복구 함수 (모달 전용)
     */
    restoreCouponApplication: async function(id, coupon_number) {
        const confirmed = await this.showConfirmModal(
            '데이터 복구 확인',
            `쿠폰번호 ${coupon_number}의 데이터를 복구하시겠습니까?<br><small class="text-muted">취소된 신청을 다시 활성화합니다.</small>`,
            '복구하기',
            '취소'
        );
        
        if (!confirmed) return;
        
        try {
            this.showToast('데이터 복구 중입니다...', 'info');
            
            const response = await fetch('api/coupon_number_summary.php', {
                method: 'POST',
                headers: { 'Content-Type': 'application/json' },
                body: JSON.stringify({
                    id: id,
                    coupon_number: coupon_number,
                    action: 'restore'
                })
            });
            
            if (!response.ok) {
                if (response.status === 401) {
                    window.location.href = 'index.html';
                    return;
                }
                throw new Error(`HTTP ${response.status}: ${response.statusText}`);
            }
            
            const data = await response.json();
            
            if (data.success) {
                this.showToast(data.message || '데이터 복구가 완료되었습니다.', 'success');
                
                setTimeout(() => {
                    loadFullUsedCoupons(); // 모달 데이터 새로고침
                }, 1000);
            } else {
                throw new Error(data.message || '데이터 복구에 실패했습니다.');
            }
            
        } catch (error) {
            console.error('Modal coupon restore error:', error);
            this.showToast('데이터 복구 중 오류가 발생했습니다: ' + error.message, 'error');
        }
    },

    /**
     * Bootstrap 모달을 사용한 확인 대화상자 (모달 전용)
     */
    showConfirmModal: function(title, message, confirmText = '확인', cancelText = '취소') {
        return new Promise((resolve) => {
            const existingModal = document.getElementById('confirmModal');
            if (existingModal) {
                existingModal.remove();
            }
            
            const modalHTML = `
                <div class="modal fade" id="confirmModal" tabindex="-1" aria-hidden="true">
                    <div class="modal-dialog modal-dialog-centered">
                        <div class="modal-content">
                            <div class="modal-header">
                                <h5 class="modal-title">${title}</h5>
                                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                            </div>
                            <div class="modal-body">
                                ${message}
                            </div>
                            <div class="modal-footer">
                                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">${cancelText}</button>
                                <button type="button" class="btn btn-primary" id="confirmButton">${confirmText}</button>
                            </div>
                        </div>
                    </div>
                </div>
            `;
            
            document.body.insertAdjacentHTML('beforeend', modalHTML);
            
            const modal = new bootstrap.Modal(document.getElementById('confirmModal'));
            const confirmButton = document.getElementById('confirmButton');
            
            confirmButton.addEventListener('click', () => {
                modal.hide();
                resolve(true);
            });
            
            document.getElementById('confirmModal').addEventListener('hidden.bs.modal', (e) => {
                if (!e.target.contains(confirmButton)) {
                    resolve(false);
                }
                e.target.remove();
            });
            
            modal.show();
        });
    },

    /**
     * 토스트 메시지 표시 (모달 전용)
     */
    showToast: function(message, type = 'success') {
        const bgClass = type === 'success' ? 'bg-success' : 
                       type === 'error' ? 'bg-danger' : 
                       type === 'warning' ? 'bg-warning' : 'bg-info';
        const icon = type === 'success' ? 'bi-check-circle' : 
                    type === 'error' ? 'bi-exclamation-triangle' : 
                    type === 'warning' ? 'bi-exclamation-triangle' : 'bi-info-circle';
        
        const toast = document.createElement('div');
        toast.className = `toast align-items-center text-white ${bgClass} border-0 position-fixed`;
        toast.style.cssText = 'top: 20px; right: 20px; z-index: 9999;';
        toast.innerHTML = `
            <div class="d-flex">
                <div class="toast-body">
                    <i class="bi ${icon} me-2"></i>${message}
                </div>
                <button type="button" class="btn-close btn-close-white me-2 m-auto" data-bs-dismiss="toast"></button>
            </div>
        `;
        
        document.body.appendChild(toast);
        
        const bsToast = new bootstrap.Toast(toast, {
            autohide: true,
            delay: type === 'success' ? 3000 : 5000
        });
        bsToast.show();
        
        toast.addEventListener('hidden.bs.toast', function() {
            if (document.body.contains(toast)) {
                document.body.removeChild(toast);
            }
        });
    }
};

/**
 * 데이터 분석 모달 열기 (dashboard.html에서 호출)
 */
function openDataAnalysisModal() {
    // 모달이 존재하지 않으면 생성
    if (!document.getElementById('dataAnalysisModal')) {
        createDataAnalysisModal();
    }
    
    // 모달 표시
    const modal = new bootstrap.Modal(document.getElementById('dataAnalysisModal'));
    modal.show();
    
    // 모달이 완전히 표시된 후 데이터 로드
    document.getElementById('dataAnalysisModal').addEventListener('shown.bs.modal', function() {
        loadAnalysisData();
    }, { once: true }); // once: true로 한 번만 실행
}

/**
 * 데이터 분석 모달 HTML 생성
 */
function createDataAnalysisModal() {
    const modalHTML = `
        <div class="modal fade" id="dataAnalysisModal" tabindex="-1" aria-labelledby="dataAnalysisModalLabel" aria-hidden="true">
            <div class="modal-dialog modal-xl">
                <div class="modal-content">
                    <div class="modal-header bg-info text-white">
                        <h5 class="modal-title" id="dataAnalysisModalLabel">
                            <i class="bi bi-graph-up me-2"></i>데이터 분석
                        </h5>
                        <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>
                    
                    <div class="modal-body p-0">
                        <!-- 탭 네비게이션 -->
                        <ul class="nav nav-tabs nav-fill" id="analysisTab" role="tablist" style="background: #f8f9fa; border-bottom: 1px solid #dee2e6;">
                            <li class="nav-item" role="presentation">
                                <button class="nav-link active" id="full-used-coupon-tab" data-bs-toggle="tab" 
                                        data-bs-target="#full-used-coupons" type="button" role="tab"
                                        style="color: #495057; font-weight: 500; border: none; background: transparent;">
                                    <i class="bi bi-ticket-perforated me-2" style="color: #6c757d;"></i>2회 사용 쿠폰
                                </button>
                            </li>
                            <li class="nav-item" role="presentation">
                                <button class="nav-link" id="past-tee-tab" data-bs-toggle="tab" 
                                        data-bs-target="#past-tee-time" type="button" role="tab"
                                        style="color: #495057; font-weight: 500; border: none; background: transparent;">
                                    <i class="bi bi-clock-history me-2" style="color: #6c757d;"></i>지난 티업시간
                                </button>
                            </li>
                            <li class="nav-item" role="presentation">
                                <button class="nav-link" id="golf-course-tab" data-bs-toggle="tab" 
                                        data-bs-target="#golf-course-stats" type="button" role="tab"
                                        style="color: #495057; font-weight: 500; border: none; background: transparent;">
                                    <i class="bi bi-geo-alt me-2" style="color: #6c757d;"></i>골프장별 통계
                                </button>
                            </li>
                        </ul>

                        <!-- 탭 컨텐츠 -->
                        <div class="tab-content" id="analysisTabContent">
                            <!-- 2회 사용 쿠폰 탭 -->
                            <div class="tab-pane fade show active" id="full-used-coupons" role="tabpanel">
                                <div class="p-4">
                                    <div class="card">
                                        <div class="card-header d-flex justify-content-between align-items-center">
                                            <h6 class="mb-0">
                                                <i class="bi bi-list-check me-1"></i>2회 사용 완료 쿠폰 상세 목록
                                            </h6>
                                            <button class="btn btn-outline-success btn-sm" onclick="exportFullUsedCoupons()">
                                                <i class="bi bi-download me-1"></i>Excel
                                            </button>
                                        </div>
                                        <div class="card-body p-0">
                                            <div id="fullUsedCouponsContainer" style="max-height: 400px; overflow-y: auto;">
                                                <div class="analysis-loading">
                                                    <div class="spinner-border" role="status">
                                                        <span class="visually-hidden">로딩중...</span>
                                                    </div>
                                                    <p class="mt-2">데이터를 불러오는 중...</p>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <!-- 지난 티업시간 분석 탭 -->
                            <div class="tab-pane fade" id="past-tee-time" role="tabpanel">
                                <div class="p-4">
                                    <div class="d-flex justify-content-between align-items-center mb-4">
                                        <h6 class="mb-0">
                                            <i class="bi bi-clock-history me-2"></i>티업시간이 현재일 기준 지난 건수
                                        </h6>
                                        <button class="btn btn-outline-success btn-sm" onclick="exportPastTeeTimeData()">
                                            <i class="bi bi-download me-1"></i>Excel
                                        </button>
                                    </div>
                                    
                                    <!-- 요약 카드 -->
                                    <div class="row mb-4">
                                        <div class="col-md-3">
                                            <div class="card border-primary analysis-card">
                                                <div class="card-body text-center">
                                                    <div class="text-primary stat-icon bg-primary mx-auto">
                                                        <i class="bi bi-check-circle"></i>
                                                    </div>
                                                    <h4 class="mt-2 mb-0" id="pastTeeTimeCount">0</h4>
                                                    <small class="text-muted">완료된 경기</small>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="col-md-3">
                                            <div class="card border-warning analysis-card">
                                                <div class="card-body text-center">
                                                    <div class="text-warning stat-icon bg-warning mx-auto">
                                                        <i class="bi bi-clock"></i>
                                                    </div>
                                                    <h4 class="mt-2 mb-0" id="futureTeeTimeCount">0</h4>
                                                    <small class="text-muted">예정된 경기</small>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="col-md-3">
                                            <div class="card border-success analysis-card">
                                                <div class="card-body text-center">
                                                    <div class="text-success stat-icon bg-success mx-auto">
                                                        <i class="bi bi-percent"></i>
                                                    </div>
                                                    <h4 class="mt-2 mb-0" id="completionRate">0%</h4>
                                                    <small class="text-muted">완료율</small>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="col-md-3">
                                            <div class="card border-info analysis-card">
                                                <div class="card-body text-center">
                                                    <div class="text-info stat-icon bg-info mx-auto">
                                                        <i class="bi bi-calendar-day"></i>
                                                    </div>
                                                    <h4 class="mt-2 mb-0" id="avgDaysAfter">0</h4>
                                                    <small class="text-muted">평균 경과일</small>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    
                                    <!-- 데이터 테이블 -->
                                    <div class="card">
                                        <div class="card-header">
                                            <h6 class="mb-0">
                                                <i class="bi bi-table me-1"></i>완료된 경기 목록
                                            </h6>
                                        </div>
                                        <div class="card-body p-0">
                                            <div id="pastTeeTimeContainer" style="max-height: 400px; overflow-y: auto;">
                                                <div class="analysis-loading">
                                                    <div class="spinner-border" role="status">
                                                        <span class="visually-hidden">로딩중...</span>
                                                    </div>
                                                    <p class="mt-2">데이터를 불러오는 중...</p>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <!-- 골프장별 통계 탭 -->
                            <div class="tab-pane fade" id="golf-course-stats" role="tabpanel">
                                <div class="p-4">
                                    <div class="d-flex justify-content-between align-items-center mb-4">
                                        <h6 class="mb-0">
                                            <i class="bi bi-geo-alt me-2"></i>골프장별 신청 건수 통계
                                        </h6>
                                        <button class="btn btn-outline-success btn-sm" onclick="exportGolfCourseStats()">
                                            <i class="bi bi-download me-1"></i>Excel
                                        </button>
                                    </div>
                                    
                                    <!-- 통계 요약 -->
                                    <div class="row mb-4">
                                        <div class="col-md-3">
                                            <div class="card border-primary analysis-card">
                                                <div class="card-body text-center">
                                                    <div class="text-primary stat-icon bg-primary mx-auto">
                                                        <i class="bi bi-flag"></i>
                                                    </div>
                                                    <h4 class="mt-2 mb-0" id="totalGolfCourses">0</h4>
                                                    <small class="text-muted">총 골프장 수</small>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="col-md-3">
                                            <div class="card border-success analysis-card">
                                                <div class="card-body text-center">
                                                    <div class="text-success stat-icon bg-success mx-auto">
                                                        <i class="bi bi-trophy"></i>
                                                    </div>
                                                    <h4 class="mt-2 mb-0" id="topGolfCourseCount">0</h4>
                                                    <small class="text-muted">최다 신청 건수</small>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="col-md-3">
                                            <div class="card border-info analysis-card">
                                                <div class="card-body text-center">
                                                    <div class="text-info stat-icon bg-info mx-auto">
                                                        <i class="bi bi-calculator"></i>
                                                    </div>
                                                    <h4 class="mt-2 mb-0" id="avgPerGolfCourse">0</h4>
                                                    <small class="text-muted">골프장당 평균</small>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="col-md-3">
                                            <div class="card border-warning analysis-card">
                                                <div class="card-body text-center">
                                                    <div class="text-warning stat-icon bg-warning mx-auto">
                                                        <i class="bi bi-star"></i>
                                                    </div>
                                                    <h4 class="mt-2 mb-0" id="top5Percentage">0%</h4>
                                                    <small class="text-muted">상위 5곳 비율</small>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    
                                    <!-- 골프장 순위 테이블 -->
                                    <div class="card">
                                        <div class="card-header">
                                            <h6 class="mb-0">
                                                <i class="bi bi-list-ol me-1"></i>골프장별 신청 건수 순위
                                            </h6>
                                        </div>
                                        <div class="card-body p-0">
                                            <div id="golfCourseStatsContainer" style="max-height: 400px; overflow-y: auto;">
                                                <div class="analysis-loading">
                                                    <div class="spinner-border" role="status">
                                                        <span class="visually-hidden">로딩중...</span>
                                                    </div>
                                                    <p class="mt-2">데이터를 불러오는 중...</p>
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
    
    // 탭 전환 이벤트 리스너 추가
    setupAnalysisTabEvents();
}

/**
 * 분석 탭 이벤트 설정
 */
function setupAnalysisTabEvents() {
    // 탭 클릭 시 스타일 업데이트
    const tabButtons = document.querySelectorAll('#analysisTab .nav-link');
    tabButtons.forEach(button => {
        button.addEventListener('click', function() {
            // 모든 탭 버튼 스타일 초기화
            tabButtons.forEach(btn => {
                btn.style.color = '#495057';
                btn.style.backgroundColor = 'transparent';
                btn.style.borderBottom = 'none';
            });
            
            // 활성 탭 스타일 적용
            this.style.color = '#0d6efd';
            this.style.backgroundColor = '#ffffff';
            this.style.borderBottom = '2px solid #0d6efd';
        });
    });
    
    // 지난 티업시간 탭 활성화시 데이터 로드
    document.getElementById('past-tee-tab').addEventListener('shown.bs.tab', function() {
        if (!analysisData.pastTeeTime || Object.keys(analysisData.pastTeeTime).length === 0) {
            loadPastTeeTimeData();
        }
    });
    
    // 골프장 통계 탭 활성화시 데이터 로드
    document.getElementById('golf-course-tab').addEventListener('shown.bs.tab', function() {
        if (!analysisData.golfCourses || analysisData.golfCourses.length === 0) {
            loadGolfCourseStats();
        }
    });
}

/**
 * 모든 분석 데이터 로드
 */
async function loadAnalysisData() {
    await loadFullUsedCoupons(); // 2회 사용 쿠폰을 첫 번째로 로드
}

/**
 * 지난 티업시간 데이터 로드
 */
async function loadPastTeeTimeData() {
    try {
        const response = await fetch('api/analysis_data.php?type=past_tee_time');
        
        if (!response.ok) {
            if (response.status === 401) {
                window.location.href = 'index.html';
                return;
            }
            throw new Error(`HTTP ${response.status}: ${response.statusText}`);
        }
        
        const data = await response.json();
        
        if (data.success) {
            analysisData.pastTeeTime = data.data;
            renderPastTeeTimeData(data.data);
        } else {
            throw new Error(data.message || '데이터를 불러오는데 실패했습니다.');
        }
    } catch (error) {
        console.error('Past tee time data load error:', error);
        showAnalysisError('pastTeeTimeContainer', '지난 티업시간 데이터를 불러올 수 없습니다: ' + error.message);
    }
}

/**
 * 골프장별 통계 데이터 로드
 */
async function loadGolfCourseStats() {
    try {
        const response = await fetch('api/analysis_data.php?type=golf_course_stats');
        
        if (!response.ok) {
            throw new Error(`HTTP ${response.status}: ${response.statusText}`);
        }
        
        const data = await response.json();
        
        if (data.success) {
            analysisData.golfCourses = data.data;
            renderGolfCourseStats(data.data);
        } else {
            throw new Error(data.message || '데이터를 불러오는데 실패했습니다.');
        }
    } catch (error) {
        console.error('Golf course stats load error:', error);
        showAnalysisError('golfCourseStatsContainer', '골프장 통계 데이터를 불러올 수 없습니다: ' + error.message);
    }
}

/**
 * 2회 사용 쿠폰 데이터 로드 (안전성 강화)
 */
async function loadFullUsedCoupons() {
    try {
        // DOM 요소 존재 확인
        const container = document.getElementById('fullUsedCouponsContainer');
        if (!container) {
            console.warn('fullUsedCouponsContainer 요소를 찾을 수 없습니다.');
            return;
        }
        
        // 로딩 상태 표시
        container.innerHTML = `
            <div class="analysis-loading text-center py-4">
                <div class="spinner-border" role="status">
                    <span class="visually-hidden">로딩중...</span>
                </div>
                <p class="mt-2">데이터를 불러오는 중...</p>
            </div>
        `;
        
        const response = await fetch('api/analysis_data.php?type=full_used_coupons');
        
        if (!response.ok) {
            throw new Error(`HTTP ${response.status}: ${response.statusText}`);
        }
        
        const data = await response.json();
        
        if (data.success) {
            analysisData.fullUsedCoupons = data.data;
            renderFullUsedCoupons(data.data);
        } else {
            throw new Error(data.message || '데이터를 불러오는데 실패했습니다.');
        }
    } catch (error) {
        console.error('Full used coupons load error:', error);
        showAnalysisError('fullUsedCouponsContainer', '2회 사용 쿠폰 데이터를 불러올 수 없습니다: ' + error.message);
    }
}

/**
 * 지난 티업시간 데이터 렌더링
 */
function renderPastTeeTimeData(data) {
    // 요약 정보 업데이트
    document.getElementById('pastTeeTimeCount').textContent = numberFormat(data.summary.past_count);
    document.getElementById('futureTeeTimeCount').textContent = numberFormat(data.summary.future_count);
    document.getElementById('completionRate').textContent = data.summary.completion_rate + '%';
    document.getElementById('avgDaysAfter').textContent = data.summary.avg_days_after + '일';
    
    // 테이블 렌더링
    const container = document.getElementById('pastTeeTimeContainer');
    
    if (!data.applications || data.applications.length === 0) {
        container.innerHTML = `
            <div class="analysis-empty-state">
                <i class="bi bi-info-circle"></i>
                <h5>완료된 경기가 없습니다</h5>
                <p>티업시간이 현재 시간보다 이전인 신청이 없습니다.</p>
            </div>
        `;
        return;
    }
    
    let tableHTML = `
        <table class="table table-striped table-hover mb-0">
            <thead class="table-dark sticky-top">
                <tr>
                    <th width="80">순번</th>
                    <th>신청자</th>
                    <th>골프장</th>
                    <th>티업시간</th>
                    <th>신청일</th>
                    <th width="100">경과일</th>
                </tr>
            </thead>
            <tbody>
    `;
    
    data.applications.forEach((app, index) => {
        tableHTML += `
            <tr>
                <td class="text-center">${index + 1}</td>
                <td class="fw-medium">${escapeHtml(app.applicant_name)}</td>
                <td class="text-success">${escapeHtml(app.golf_course)}</td>
                <td>${formatDateTime(app.tee_time)}</td>
                <td>${formatDateTime(app.created_at)}</td>
                <td class="text-center">
                    <span class="badge bg-primary">${app.days_after}일</span>
                </td>
            </tr>
        `;
    });
    
    tableHTML += `
            </tbody>
        </table>
    `;
    
    container.innerHTML = tableHTML;
}

/**
 * 골프장별 통계 렌더링
 */
function renderGolfCourseStats(data) {
    // 요약 정보 업데이트
    document.getElementById('totalGolfCourses').textContent = numberFormat(data.summary.total_courses);
    document.getElementById('topGolfCourseCount').textContent = numberFormat(data.summary.max_count);
    document.getElementById('avgPerGolfCourse').textContent = data.summary.avg_per_course.toFixed(1);
    document.getElementById('top5Percentage').textContent = data.summary.top5_percentage + '%';
    
    // 테이블 렌더링
    const container = document.getElementById('golfCourseStatsContainer');
    
    if (!data.courses || data.courses.length === 0) {
        container.innerHTML = `
            <div class="analysis-empty-state">
                <i class="bi bi-info-circle"></i>
                <h5>골프장 데이터가 없습니다</h5>
                <p>신청된 골프장 정보가 없습니다.</p>
            </div>
        `;
        return;
    }
    
    let tableHTML = `
        <table class="table table-striped table-hover mb-0">
            <thead class="table-dark sticky-top">
                <tr>
                    <th width="80">순위</th>
                    <th>골프장명</th>
                    <th width="100">신청건수</th>
                    <th width="100">비율</th>
                    <th>점유율</th>
                </tr>
            </thead>
            <tbody>
    `;
    
    data.courses.forEach((course, index) => {
        const rankClassValue = index < 3 ? 'text-warning fw-bold' : '';
        const percentageWidth = data.summary.max_count > 0 ? (course.count / data.summary.max_count * 100) : 0;
        
        tableHTML += `
            <tr>
                <td class="text-center ${rankClassValue}">
                    ${index < 3 ? `<i class="bi bi-trophy-fill me-1"></i>` : ''}${index + 1}
                </td>
                <td class="fw-medium">${escapeHtml(course.golf_course)}</td>
                <td class="text-center fw-bold text-primary">${numberFormat(course.count)}</td>
                <td class="text-center">${course.percentage}%</td>
                <td>
                    <div class="progress" style="height: 20px;">
                        <div class="progress-bar bg-success" role="progressbar" 
                             style="width: ${percentageWidth}%" 
                             aria-valuenow="${course.count}" aria-valuemin="0" aria-valuemax="${data.summary.max_count}">
                        </div>
                    </div>
                </td>
            </tr>
        `;
    });
    
    tableHTML += `
            </tbody>
        </table>
    `;
    
    container.innerHTML = tableHTML;
}

/**
 * 2회 사용 쿠폰의 개별 신청 목록 렌더링 (네임스페이스 함수 호출로 수정)
 */
function renderFullUsedCoupons(data) {
    const container = document.getElementById('fullUsedCouponsContainer');
    
    // 컨테이너 존재 확인
    if (!container) {
        console.error('fullUsedCouponsContainer를 찾을 수 없습니다.');
        return;
    }
    
    if (!data.applications || data.applications.length === 0) {
        container.innerHTML = `
            <div class="empty-state text-center py-5">
                <i class="bi bi-inbox text-muted" style="font-size: 3rem;"></i>
                <p class="text-muted mt-3">검토가 필요한 의심 케이스가 없습니다.</p>
                <small class="text-muted">모든 2회 사용 쿠폰이 정상적인 별도 이용입니다.</small>
            </div>
        `;
        return;
    }

    let html = `
        <!-- 상단 컨트롤 바 -->
        <div class="d-flex justify-content-between align-items-center mb-3 px-3 pt-3">
            <div class="text-muted">
                <i class="bi bi-exclamation-triangle text-warning me-1"></i>
                검토 필요: ${numberFormat(data.applications.length)}건의 의심 케이스
                ${data.summary ? `<span class="text-success ms-2"><i class="bi bi-check-circle"></i> 정상 ${data.summary.normal_count}건</span>` : ''}
            </div>
            <div>
                <div class="btn-group me-2" role="group">
                    <button class="btn btn-outline-secondary btn-sm active" onclick="filterSuspicious('all')" id="filterAll">
                        전체
                    </button>
                    <button class="btn btn-outline-danger btn-sm" onclick="filterSuspicious('critical')" id="filterCritical">
                        심각
                    </button>
                    <button class="btn btn-outline-warning btn-sm" onclick="filterSuspicious('suspicious')" id="filterSuspicious">
                        의심
                    </button>
                    <button class="btn btn-outline-info btn-sm" onclick="filterSuspicious('review')" id="filterReview">
                        검토
                    </button>
                </div>
            </div>
        </div>
        
        <!-- 데스크톱 테이블 -->
        <div class="d-none d-lg-block">
            <div class="desktop-table-container">
                <table class="table table-striped table-hover mb-0">
                    <thead class="table-dark sticky-top">
                        <tr>
                            <th>신청일시</th>
                            <th>신청자</th>
                            <th>연락처</th>
                            <th>골프장</th>
                            <th>티오프</th>
                            <th>쿠폰번호</th>
                            <th width="80">상태</th>
                            <th width="80">액션</th>
                        </tr>
                    </thead>
                    <tbody>
    `;

    // 데스크톱 테이블 행
    data.applications.forEach((app, index) => {
        const rowNumber = index + 1;
        let rowClass = app.usage_order === 1 ? 'table-info' : 'table-success';
        
        // 서버에서 분석된 의심 패턴 정보 사용
        if (app.suspicious_pattern) {
            rowClass = getClassBySeverity(app.suspicious_pattern.severity);
        }
        
        const tooltip = app.suspicious_pattern ? 
            `title="${app.suspicious_pattern.description}"` : '';
        
        // 상태 아이콘 결정
        let statusIcon;
        if (app.summary === "2") {
            statusIcon = '<i class="bi bi-x-circle-fill text-secondary" title="취소됨"></i>';
        } else {
            statusIcon = '<i class="bi bi-check-circle-fill text-success" title="정상"></i>';
        }
        
        if (app.suspicious_pattern) {
            switch (app.suspicious_pattern.severity) {
                case 'critical':
                    statusIcon = '<i class="bi bi-exclamation-triangle-fill text-danger" title="심각"></i>';
                    break;
                case 'suspicious':
                    statusIcon = '<i class="bi bi-exclamation-circle-fill text-warning" title="의심"></i>';
                    break;
                case 'review':
                    statusIcon = '<i class="bi bi-info-circle-fill text-info" title="검토"></i>';
                    break;
            }
        }
        
        html += `
            <tr class="${rowClass}" ${tooltip}>
                <td>
                    <span class="badge bg-secondary me-2">${rowNumber}</span>
                    ${formatDateTime(app.created_at)}
                </td>
                <td class="fw-medium">${escapeHtml(app.applicant_name)}</td>
                <td><code class="small">${escapeHtml(app.applicant_phone)}</code></td>
                <td class="text-success">${escapeHtml(app.golf_course)}</td>
                <td>${formatDateTime(app.tee_time)}</td>
                <td><code class="small">${escapeHtml(app.coupon_number)}</code></td>
                <td class="text-center">
                    ${statusIcon}
                </td>
                <td class="text-center">
                    ${app.summary === "2" ? 
                        `<button class="btn btn-outline-success btn-sm" 
                                onclick="event.stopPropagation(); DataAnalysisModal.restoreCouponApplication('${app.id}', '${app.coupon_number}')">
                            복구
                        </button>` :
                        `<button class="btn btn-outline-danger btn-sm" 
                                onclick="event.stopPropagation(); DataAnalysisModal.viewCouponDetail('${app.id}', '${app.coupon_number}')">
                            정리
                        </button>`
                    }
                </td>
            </tr>
        `;
    });

    html += `
                    </tbody>
                </table>
            </div>
        </div>
        
        <!-- 모바일 리스트 -->
        <div class="d-block d-lg-none">
            <div class="two-row-container">
    `;

    // 모바일 리스트
    data.applications.forEach((app, index) => {
        const rowNumber = index + 1;
        let cardBorderClass = app.usage_order === 1 ? 'border-primary' : 'border-success';
        const numberBadgeColor = app.usage_order === 1 ? '#667eea, #764ba2' : '#28a745, #20c997';
        
        // 의심 패턴에 따른 카드 테두리 색상 변경
        if (app.suspicious_pattern) {
            switch (app.suspicious_pattern.severity) {
                case 'critical':
                    cardBorderClass = 'border-danger';
                    break;
                case 'suspicious':
                    cardBorderClass = 'border-warning';
                    break;
                case 'review':
                    cardBorderClass = 'border-info';
                    break;
            }
        }
        
        // 상태 아이콘
        let statusIcon = '<i class="bi bi-check-circle-fill text-success" title="정상"></i>';
        if (app.suspicious_pattern) {
            switch (app.suspicious_pattern.severity) {
                case 'critical':
                    statusIcon = '<i class="bi bi-exclamation-triangle-fill text-danger" title="심각"></i>';
                    break;
                case 'suspicious':
                    statusIcon = '<i class="bi bi-exclamation-circle-fill text-warning" title="의심"></i>';
                    break;
                case 'review':
                    statusIcon = '<i class="bi bi-info-circle-fill text-info" title="검토"></i>';
                    break;
            }
        }
        
        html += `
            <div class="two-row-item ${cardBorderClass}">
                <div class="row-primary">
                    <div class="number-name-section">
                        <span class="number-badge" style="background: linear-gradient(135deg, ${numberBadgeColor});">${rowNumber}</span>
                        <div class="name-usage-info">
                            <h6 class="applicant-name">${escapeHtml(app.applicant_name)}</h6>
                            <span class="status-icon">${statusIcon}</span>
                            ${app.summary === "2" ? 
                                `<button class="btn btn-outline-success btn-sm" 
                                        onclick="event.stopPropagation(); DataAnalysisModal.restoreCouponApplication('${app.id}', '${app.coupon_number}')">
                                    복구
                                </button>` :
                                `<button class="btn btn-outline-danger btn-sm" 
                                        onclick="event.stopPropagation(); DataAnalysisModal.viewCouponDetail('${app.id}', '${app.coupon_number}')">
                                    정리
                                </button>`
                            }
                        </div>
                    </div>
                    <div class="golf-section">
                        <i class="bi bi-geo-alt-fill text-success"></i>
                        <span class="golf-name">${escapeHtml(app.golf_course)}</span>
                    </div>
                </div>
                
                <div class="row-secondary" style="margin-left: 30px; background: transparent; border-top: none;">
                    <span>연락처 ${formatPhone(app.applicant_phone)}</span>
                    <i class="bi bi-ticket-perforated-fill"></i>
                    <span>${escapeHtml(app.coupon_number)}</span>
                </div>
                
                <div class="row-secondary" style="margin-left: 30px; background: transparent; border-top: none;">
                    <span>티오프 ${formatDateTime(app.tee_time)}</span>
                </div>
                
                <div class="row-secondary" style="margin-left: 30px; background: transparent; border-top: none;">
                    <span>신청일 ${formatDateTime(app.created_at)}</span>
                    ${app.suspicious_pattern ? `<span class="text-danger ms-2">(${app.suspicious_pattern.description})</span>` : ''}
                </div>
            </div>
        `;
    });

    html += `
            </div>
        </div>
    `;

    container.innerHTML = html;
}

/**
 * Excel 다운로드 함수들
 */
async function exportPastTeeTimeData() {
    try {
        const response = await fetch('api/analysis_export.php?type=past_tee_time');
        
        if (response.ok) {
            const blob = await response.blob();
            const url = window.URL.createObjectURL(blob);
            const a = document.createElement('a');
            a.href = url;
            a.download = `지난티업시간_${new Date().toISOString().split('T')[0]}.xlsx`;
            document.body.appendChild(a);
            a.click();
            window.URL.revokeObjectURL(url);
            document.body.removeChild(a);
            DataAnalysisModal.showToast('Excel 다운로드가 완료되었습니다.', 'success');
        } else {
            throw new Error('다운로드에 실패했습니다.');
        }
    } catch (error) {
        console.error('Export error:', error);
        DataAnalysisModal.showToast('Excel 다운로드에 실패했습니다.', 'error');
    }
}

async function exportGolfCourseStats() {
    try {
        const response = await fetch('api/analysis_export.php?type=golf_course_stats');
        
        if (response.ok) {
            const blob = await response.blob();
            const url = window.URL.createObjectURL(blob);
            const a = document.createElement('a');
            a.href = url;
            a.download = `골프장별통계_${new Date().toISOString().split('T')[0]}.xlsx`;
            document.body.appendChild(a);
            a.click();
            window.URL.revokeObjectURL(url);
            document.body.removeChild(a);
            DataAnalysisModal.showToast('Excel 다운로드가 완료되었습니다.', 'success');
        } else {
            throw new Error('다운로드에 실패했습니다.');
        }
    } catch (error) {
        console.error('Export error:', error);
        DataAnalysisModal.showToast('Excel 다운로드에 실패했습니다.', 'error');
    }
}

async function exportFullUsedCoupons() {
    try {
        const response = await fetch('api/analysis_export.php?type=full_used_coupons');
        
        if (response.ok) {
            const blob = await response.blob();
            const url = window.URL.createObjectURL(blob);
            const a = document.createElement('a');
            a.href = url;
            a.download = `2회사용쿠폰_${new Date().toISOString().split('T')[0]}.xlsx`;
            document.body.appendChild(a);
            a.click();
            window.URL.revokeObjectURL(url);
            document.body.removeChild(a);
            DataAnalysisModal.showToast('Excel 다운로드가 완료되었습니다.', 'success');
        } else {
            throw new Error('다운로드에 실패했습니다.');
        }
    } catch (error) {
        console.error('Export error:', error);
        DataAnalysisModal.showToast('Excel 다운로드에 실패했습니다.', 'error');
    }
}

/**
 * 의심 패턴별 필터링
 */
function filterSuspicious(severity) {
    const rows = document.querySelectorAll('#fullUsedCouponsContainer tbody tr');
    const mobileItems = document.querySelectorAll('#fullUsedCouponsContainer .two-row-item');
    const buttons = document.querySelectorAll('[id^="filter"]');
    
    // 모든 버튼 비활성화
    buttons.forEach(btn => btn.classList.remove('active'));
    
    // 선택된 버튼 활성화
    const targetButton = document.getElementById(`filter${severity.charAt(0).toUpperCase() + severity.slice(1)}`);
    if (targetButton) {
        targetButton.classList.add('active');
    }
    
    // 데스크톱 테이블 필터링
    rows.forEach(row => {
        if (severity === 'all') {
            row.style.display = '';
        } else {
            const hasClass = row.classList.contains(getClassBySeverity(severity));
            row.style.display = hasClass ? '' : 'none';
        }
    });
    
    // 모바일 리스트 필터링
    mobileItems.forEach(item => {
        if (severity === 'all') {
            item.style.display = '';
        } else {
            const hasBorderClass = item.classList.contains(getBorderClassBySeverity(severity));
            item.style.display = hasBorderClass ? '' : 'none';
        }
    });
}

function getClassBySeverity(severity) {
    switch (severity) {
        case 'critical': return 'table-danger';
        case 'suspicious': return 'table-warning';
        case 'review': return 'table-info';
        default: return '';
    }
}

function getBorderClassBySeverity(severity) {
    switch (severity) {
        case 'critical': return 'border-danger';
        case 'suspicious': return 'border-warning';
        case 'review': return 'border-info';
        default: return '';
    }
}

/**
 * 에러 상태 표시
 */
function showAnalysisError(containerId, message) {
    const container = document.getElementById(containerId);
    if (container) {
        container.innerHTML = `
            <div class="analysis-empty-state text-center py-5">
                <i class="bi bi-exclamation-triangle text-danger"></i>
                <h5>오류가 발생했습니다</h5>
                <p>${escapeHtml(message)}</p>
                <button class="btn btn-primary" onclick="loadAnalysisData()">다시 시도</button>
            </div>
        `;
    }
}

/**
 * 유틸리티 함수들
 */
function numberFormat(number) {
    if (typeof number !== 'number') return '0';
    return new Intl.NumberFormat('ko-KR').format(number);
}

function formatDate(dateString) {
    if (!dateString) return '-';
    
    try {
        const date = new Date(dateString);
        if (isNaN(date.getTime())) return '-';
        
        return date.toLocaleDateString('ko-KR', {
            year: 'numeric',
            month: '2-digit',
            day: '2-digit'
        });
    } catch (error) {
        return '-';
    }
}

function formatDateTime(dateString) {
    if (!dateString) return '-';
    
    try {
        const date = new Date(dateString);
        if (isNaN(date.getTime())) return '-';
        
        return date.toLocaleDateString('ko-KR', {
            year: '2-digit',
            month: '2-digit',
            day: '2-digit',
            hour: '2-digit',
            minute: '2-digit'
        });
    } catch (error) {
        return '-';
    }
}

function formatPhone(phone) {
    if (!phone) return '';
    const cleaned = phone.replace(/\D/g, '');
    if (cleaned.length === 11) {
        return cleaned.replace(/(\d{3})(\d{4})(\d{4})/, '$1-$2-$3');
    }
    return phone;
}

function escapeHtml(text) {
    if (!text) return '';
    
    const div = document.createElement('div');
    div.textContent = text;
    return div.innerHTML;
}

/**
 * 초기화
 */
document.addEventListener('DOMContentLoaded', function() {
    console.log('data-analysis.js 네임스페이스 버전 로드 완료');
});

// 전역에 함수 노출
window.openDataAnalysisModal = openDataAnalysisModal;
window.exportPastTeeTimeData = exportPastTeeTimeData;
window.exportGolfCourseStats = exportGolfCourseStats;
window.exportFullUsedCoupons = exportFullUsedCoupons;
window.filterSuspicious = filterSuspicious;