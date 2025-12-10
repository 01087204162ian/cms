/**
 * 가입신청 상세보기 모달 관련 함수들
 */

let currentApplicationId = null;

/**
 * 가입신청 상세보기 모달 열기
 */
async function openApplicationDetail(applicationId) {
    currentApplicationId = applicationId;
    
    // 모달 열기
    const modal = new bootstrap.Modal(document.getElementById('applicationDetailModal'));
    modal.show();
    
    // 초기 상태 설정
    document.getElementById('applicationDetailLoading').style.display = 'block';
    document.getElementById('applicationDetailContent').style.display = 'none';
    document.getElementById('applicationDetailError').style.display = 'none';
    
    try {
        // 신청 정보 조회 (applicationsData에서)
        const application = applicationsData.find(app => app.id == applicationId);
        
        if (!application) {
            throw new Error('신청 정보를 찾을 수 없습니다.');
        }
        
        // 디버깅: 실제 데이터 구조 확인
        console.log('=== Application Detail Data ===');
        console.log('Full object:', application);
        console.log('All keys:', Object.keys(application));
        console.log('Golf course field:', {
            golf_course: application.golf_course,
            golfCourse: application.golfCourse,
            golf_name: application.golf_name,
            golfName: application.golfName
        });
        
        // 기본 정보 표시
        displayApplicationDetail(application);
        
        // 동반자 정보가 있으면 조회
        if (application.hasCompanions) {
            await loadCompanions(applicationId);
        } else {
            showNoCompanions();
        }
        
        // 로딩 숨기고 컨텐츠 표시
        document.getElementById('applicationDetailLoading').classList.add('d-none');
		document.getElementById('applicationDetailContent').classList.remove('d-none');
		document.getElementById('applicationDetailContent').style.display = '';
        
    } catch (error) {
        console.error('Application detail error:', error);
        document.getElementById('applicationDetailLoading').style.display = 'none';
        document.getElementById('applicationDetailError').style.display = 'block';
        document.getElementById('applicationDetailErrorMessage').textContent = error.message;
    }
}

/**
 * 안전한 데이터 가져오기 헬퍼 함수
 */
function getFieldValue(app, ...fieldNames) {
    for (let fieldName of fieldNames) {
        if (app[fieldName] !== undefined && app[fieldName] !== null && app[fieldName] !== '') {
            return app[fieldName];
        }
    }
    return '-';
}

/**
 * 기본 정보 표시
 */
function displayApplicationDetail(app) {
    // 디버깅용 로그
    console.log('Displaying detail for:', {
        id: app.id,
        name: app.applicant_name || app.applicantName,
        golf: app.golf_course || app.golfCourse,
        allKeys: Object.keys(app)
    });
    
    // 상단 요약
    document.getElementById('detailApplicantName').textContent = 
        getFieldValue(app, 'applicant_name', 'applicantName');
    
    document.getElementById('detailSignupId').textContent = 
        app.signupId || `ACE${String(app.id).padStart(7, '0')}`;
    
    document.getElementById('detailCreatedAt').textContent = 
        formatDateTime(getFieldValue(app, 'created_at', 'createdAt'));
    
    // 상태 배지
    const statusBadge = document.getElementById('detailStatusBadge');
    if (app.canEdit) {
        statusBadge.className = 'badge bg-success';
        statusBadge.textContent = '정상';
    } else {
        statusBadge.className = 'badge bg-secondary';
        statusBadge.textContent = '취소';
    }
    
    // 신청자 정보
    document.getElementById('detailName').textContent = 
        getFieldValue(app, 'applicant_name', 'applicantName');
    
    document.getElementById('detailPhone').textContent = 
        getFieldValue(app, 'applicant_phone', 'applicantPhone');
    
    document.getElementById('detailCouponNumber').textContent = 
        getFieldValue(app, 'couponNumber', 'coupon_number');
    
    // 라운딩 정보 - 골프장명 (여러 가능한 필드명 체크)
    document.getElementById('detailGolfCourse2').textContent = getFieldValue(app, 'golf_course', 'golfCourse');
   
    

    
    document.getElementById('detailTeeTime').textContent = 
        formatDateTime(getFieldValue(app, 'tee_time', 'teeTime'));
    
    // 쿠폰 차수 - testIs가 '3' 또는 '4'면 2차, '1' 또는 '2'면 1차
    const testIs = app.testIs || app.test_is || '1';
    const is2ndPhase = (testIs === '3' || testIs === '4');
    const testIsText = is2ndPhase ? '2차 쿠폰' : '1차 쿠폰';
    const testIsBadge = is2ndPhase ? 
        '<span class="badge bg-purple">2차</span>' : 
        '<span class="badge bg-info">1차</span>';
    document.getElementById('detailTestIs').innerHTML = testIsBadge + ' ' + testIsText;
    
    // 동반자 카운트
    document.getElementById('companionsCount').textContent = 
        app.companionCount || app.companion_count || 0;
}

/**
 * 동반자 정보 조회
 */
async function loadCompanions(applicationId) {
    try {
        const response = await fetch(`api/get_companions.php?application_id=${applicationId}`);
        
        if (!response.ok) {
            throw new Error('동반자 정보 조회 실패');
        }
        
        const data = await response.json();
        
        if (data.success && data.data.companions && data.data.companions.length > 0) {
            displayCompanions(data.data.companions);
        } else {
            showNoCompanions();
        }
        
    } catch (error) {
        console.error('Companions load error:', error);
        showNoCompanions();
    }
}

/**
 * 동반자 목록 표시
 */
function displayCompanions(companions) {
    const container = document.getElementById('companionsList');
    const noCompanionsDiv = document.getElementById('noCompanions');
    
    let html = '';
    
   companions.forEach((companion, index) => {
        html += `
            <div class="companion-card mb-3" style="border: 1px solid #e9ecef; border-radius: 8px; padding: 16px; background: #f8f9fa;">
                <div class="d-flex align-items-start gap-3">
                    <div class="companion-number-badge" style="background: linear-gradient(135deg, #667eea 0%, #764ba2 100%); 
                         color: white; width: 40px; height: 40px; border-radius: 50%; display: flex; 
                         align-items: center; justify-content: center; font-weight: bold; font-size: 18px; flex-shrink: 0;">
                        ${companion.number || (index + 1)}
                    </div>
                    <div class="flex-grow-1">
                        <div class="row g-3">
                            <div class="col-md-4">
                                <div class="d-flex align-items-center gap-2">
                                    <i class="bi bi-person-fill text-primary"></i>
                                    <span class="text-primary fw-bold">${escapeHtml(companion.companion_name || companion.name || '-')}</span>
                                </div>
                            </div>
                            <div class="col-md-4">
                                <div class="d-flex align-items-center gap-2">
                                    <i class="bi bi-telephone-fill text-success"></i>
                                    <code style="background: white; padding: 4px 8px; border-radius: 4px; font-size: 14px;">${escapeHtml(companion.companion_phone || companion.phone || '-')}</code>
                                </div>
                            </div>
                            <div class="col-md-4">
                                <div class="d-flex align-items-center gap-2">
                                    <i class="bi bi-calendar-check text-muted"></i>
                                    <span class="text-muted small">${formatDateTime(companion.created_at || companion.createdAt)}</span>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        `;
    });
    
    container.innerHTML = html;
    container.style.display = 'block';
    noCompanionsDiv.style.display = 'none';
}

/**
 * 동반자 없음 표시
 */
function showNoCompanions() {
    document.getElementById('companionsList').style.display = 'none';
    document.getElementById('noCompanions').style.display = 'block';
}

/**
 * 유틸리티: HTML 이스케이프
 */
function escapeHtml(text) {
    if (!text) return '';
    const div = document.createElement('div');
    div.textContent = text;
    return div.innerHTML;
}

/**
 * 유틸리티: 날짜 포맷
 */
function formatDateTime(dateString) {
    if (!dateString || dateString === '-') return '-';
    
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

// 전역 함수 노출
window.openApplicationDetail = openApplicationDetail;

// 로드 확인
console.log('application-detail.js (수정본) 로드 완료 ✓');