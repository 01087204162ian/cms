/**
 * 가입신청 모달 관련 JavaScript 함수들 (필터 기능 추가)
 * PCI Korea 홀인원보험 관리 시스템
 */

// 전역 변수
let applicationsData = [];
let currentPage = 1;
let totalPages = 1;
let itemsPerPage = 20;
let currentFilters = {}; // 현재 적용된 필터 저장

/**
 * 전체 가입신청 데이터 로드 (dashboard_data.php 전용)
 */
async function loadAllApplications() {
    try {
        showLoadingState();
        
        // dashboard_data.php만 호출 (쿠폰 기준 데이터)
        const response = await fetch(`api/dashboard_data.php?limit=${itemsPerPage}&page=${currentPage}`);

        if (!response.ok) {
            if (response.status === 401) {
                window.location.href = 'index.html';
                return;
            }
            throw new Error(`HTTP ${response.status}: ${response.statusText}`);
        }

        const data = await response.json();
        console.log('Dashboard API Response:', data);
        
        if (data.success) {
            const responseData = data.data || {};
            applicationsData = responseData.recent_applications || [];
            
            renderApplicationsTable(applicationsData);
            
            // 페이징 정보가 있으면 사용, 없으면 기본값
            if (responseData.pagination) {
                totalPages = responseData.pagination.totalPages || 1;
                renderPagination(responseData.pagination);
            }
            
            // 쿠폰 기준 총 건수 사용 (303)
            const totalCount = responseData.stats?.total_applications || 0;
            updateApplicationsCount(totalCount);
            
            // 탭 카운트 업데이트
            const tabCountElement = document.getElementById('applicationsTabCount');
            if (tabCountElement) {
                tabCountElement.textContent = totalCount;
            }
            
        } else {
            showErrorState(data.message || '데이터를 불러오는데 실패했습니다.');
            console.error('API Error:', data);
        }
    } catch (error) {
        console.error('Applications load error:', error);
        showErrorState('서버 연결에 실패했습니다. 잠시 후 다시 시도해주세요.');
    }
}

/**
 * 필터와 함께 가입신청 데이터 로드 (새로 추가)
 */
async function loadApplicationsWithFilter(filters = {}) {
    try {
        showLoadingState();
        
        // 필터 파라미터 구성
        const params = new URLSearchParams({
            page: currentPage,
            limit: itemsPerPage,
            searchType: filters.searchType || 'all',
            searchInput: filters.searchInput || '',
            startDate: filters.startDate || '',
            endDate: filters.endDate || ''
        });
        
        // 빈 값 제거
        for (let [key, value] of params.entries()) {
            if (!value) {
                params.delete(key);
            }
        }
        
        console.log('Filter parameters:', Object.fromEntries(params));
        
        const response = await fetch(`api/applications_filtered.php?${params}`);

        if (!response.ok) {
            if (response.status === 401) {
                window.location.href = 'index.html';
                return;
            }
            throw new Error(`HTTP ${response.status}: ${response.statusText}`);
        }

        const data = await response.json();
        console.log('Filtered API Response:', data);
        
        if (data.success) {
            const responseData = data.data || {};
            applicationsData = responseData.applications || [];
            
            renderApplicationsTable(applicationsData);
            
            // 페이징 정보 업데이트
            if (responseData.pagination) {
                totalPages = responseData.pagination.totalPages || 1;
                renderPagination(responseData.pagination);
                updateApplicationsCount(responseData.pagination.totalCount || 0);
            }
            
            // 현재 필터 저장
            currentFilters = filters;
            
        } else {
            showErrorState(data.message || '데이터를 불러오는데 실패했습니다.');
            console.error('Filtered API Error:', data);
        }
    } catch (error) {
        console.error('Filtered applications load error:', error);
        showErrorState('서버 연결에 실패했습니다. 잠시 후 다시 시도해주세요.');
    }
}

/**
 * 필터 적용 함수 (수정됨)
 */
function filterApplications() {
    // 필터 조건들 수집
    const searchType = document.getElementById('searchType').value;
    const searchInput = document.getElementById('searchInput').value.trim();
    const startDate = document.getElementById('startDate').value;
    const endDate = document.getElementById('endDate').value;
    
    // 검색어 유효성 검사
    if ((searchType === 'name' || searchType === 'phone') && searchInput.length > 0 && searchInput.length < 2) {
        alert('이름이나 전화번호 검색시 최소 2자 이상 입력해주세요.');
        return;
    }
    
    // 날짜 유효성 검사
    if (startDate && endDate && startDate > endDate) {
        alert('시작일이 종료일보다 늦을 수 없습니다.');
        return;
    }
    
    currentPage = 1; // 첫 페이지로 리셋
    
    // 필터 조건과 함께 데이터 로드
    loadApplicationsWithFilter({
        searchType,
        searchInput,
        startDate,
        endDate
    });
    
    console.log('필터 적용:', { searchType, searchInput, startDate, endDate });
}

/**
 * 필터 초기화 함수 (새로 추가)
 */
function resetFilters() {
    // 폼 필드 초기화
    document.getElementById('searchType').value = 'all';
    document.getElementById('searchInput').value = '';
    document.getElementById('startDate').value = '';
    document.getElementById('endDate').value = '';
    
    // 필터 초기화하고 전체 데이터 로드
    currentFilters = {};
    currentPage = 1;
    loadAllApplications();
}

/**
 * 간소화된 테이블 렌더링
 */
function renderApplicationsTable(applications) {
    const container = document.getElementById('applicationsTableContainer');
    
    if (!applications || applications.length === 0) {
        container.innerHTML = `
            <div class="empty-state">
                <i class="bi bi-inbox text-muted" style="font-size: 3rem;"></i>
                <p class="text-muted mt-3">가입신청 내역이 없습니다.</p>
                ${Object.keys(currentFilters).length > 0 ? 
                    '<button class="btn btn-outline-primary" onclick="resetFilters()">필터 초기화</button>' : 
                    ''
                }
            </div>
        `;
        return;
    }

    let html = `
        <!-- 데스크톱 테이블 -->
        <div class="d-none d-lg-block">
            <div class="desktop-table-container">
                <table class="table table-hover">
                    <thead class="table-dark">
                        <tr>
                            <th>신청일시</th>
                            <th>신청자</th>
                            <th>연락처</th>
                            <th>골프장</th>
                            <th>티오프</th>
                            <th>쿠폰번호</th>
							<th>동반자</th>  <!-- 추가 -->
                            <th>상태</th>
                            <th width="80">액션</th>
                        </tr>
                    </thead>
                    <tbody>
    `;

    // 데스크톱 테이블 행
    applications.forEach((app, index) => {
        const rowNumber = (currentPage - 1) * itemsPerPage + index + 1;
        const statusBadge = app.canEdit ? 
            '<span class="badge bg-success">정상</span>' : 
            '<span class="badge bg-secondary">취소</span>';
            
        html += `
            <tr onclick="openApplicationDetail(${app.id})" style="cursor: pointer;">
                <td>
                    <span class="badge bg-secondary me-2">${rowNumber}</span>
                    ${formatDateTime(app.created_at)}
                </td>
                <td class="fw-medium">${escapeHtml(app.applicant_name)}</td>
                <td><code class="small">${escapeHtml(app.applicant_phone)}</code></td>
                <td class="text-success">${escapeHtml(app.golf_course)}</td>
                <td>${formatDateTime(app.tee_time)}</td>
                <td><code class="small">${escapeHtml(app.couponNumber || app.coupon_number || '')}</code></td>
				<td>
					${app.hasCompanions ? 
						`<span class="badge bg-info">
							<i class="bi bi-people-fill me-1"></i>${app.companionCount}명
						</span>` : 
						'<span class="text-muted">-</span>'
					}
				</td>  <!-- 동반자 컬럼 추가 -->
                <td>${statusBadge}</td>
                <td class="text-center">
                    ${app.canEdit ? 
                        `<button class="btn btn-outline-danger btn-sm" 
                                onclick="event.stopPropagation(); viewCouponDetailWithDetailedConfirm('${app.id}', '${app.couponNumber || app.coupon_number || ''}')">
                            정리
                        </button>` :
                        `<button class="btn btn-outline-success btn-sm" 
                                onclick="event.stopPropagation(); restoreCouponApplication('${app.id}', '${app.couponNumber || app.coupon_number || ''}')">
                            복구
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
        
        <!-- 모바일/태블릿 리스트 -->
        <div class="d-block d-lg-none">
            <div class="two-row-container">
    `;

    // 모바일 리스트
    applications.forEach((app, index) => {
        const rowNumber = (currentPage - 1) * itemsPerPage + index + 1;
        const statusBadge = app.canEdit ? 
            '<span class="badge bg-success">정상</span>' : 
            '<span class="badge bg-secondary">취소</span>';
        
        html += `
			<div class="two-row-item" onclick="openApplicationDetail(${app.id})" style="cursor: pointer;">
                <div class="row-primary">
                    <div class="number-name-section">
                        <span class="number-badge">${rowNumber}</span>
                        <h6 class="applicant-name">${escapeHtml(app.applicant_name)}</h6>
                       ${app.hasCompanions ? 
						`<span class="badge bg-info" style="font-size: 0.65rem; padding: 0.2rem 0.4rem;">
							<i class="bi bi-people-fill"></i> ${app.companionCount}
						</span>` : 
						''
					}  <!-- 동반자 배지 추가 -->
                    </div>
                    <div class="golf-section">
                        <i class="bi bi-geo-alt-fill text-success"></i>
                        <span class="golf-name">${escapeHtml(app.golf_course)}</span>
                    </div>
					<div class="d-flex align-items-center gap-1">		
					${app.canEdit ? 
                                `<button class="btn btn-outline-danger btn-sm" 
                                        onclick="event.stopPropagation(); viewCouponDetailWithDetailedConfirm('${app.id}', '${app.couponNumber || app.coupon_number || ''}')">
                                    정리
                                </button>` :
                                `<button class="btn btn-outline-success btn-sm" 
                                        onclick="event.stopPropagation(); restoreCouponApplication('${app.id}', '${app.couponNumber || app.coupon_number || ''}')">
                                    복구
                                </button>`
                            }
					</div>
                </div>
                <div class="row-secondary">
                    <span>연락처: ${formatPhone(app.applicant_phone)}</span>
                    <span>티오프: ${formatDateTime(app.tee_time)}</span>
                </div>
                <div class="row-secondary">
                    <span>신청일: ${formatDateTime(app.created_at)}</span>
                    <span>쿠폰: ${escapeHtml(app.couponNumber || app.coupon_number || '')}</span>
					<div class="d-flex align-items-center gap-2">
                            ${statusBadge}
                        </div>	
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
 * 페이지네이션 렌더링
 */
function renderPagination(paginationData) {
    const pagination = document.getElementById('applicationsPagination');
    
    if (!paginationData || paginationData.totalPages <= 1) {
        pagination.innerHTML = '';
        return;
    }

    const totalPages = paginationData.totalPages || 1;
    const currentPageNum = paginationData.currentPage || currentPage;
    
    let paginationHtml = '';
    
    // 이전 페이지
    if (currentPageNum > 1) {
        paginationHtml += `
            <li class="page-item">
                <a class="page-link" href="#" onclick="changePage(${currentPageNum - 1})">
                    <i class="bi bi-chevron-left"></i>
                </a>
            </li>
        `;
    }

    // 페이지 번호들
    const startPage = Math.max(1, currentPageNum - 2);
    const endPage = Math.min(totalPages, currentPageNum + 2);

    for (let i = startPage; i <= endPage; i++) {
        paginationHtml += `
            <li class="page-item ${i === currentPageNum ? 'active' : ''}">
                <a class="page-link" href="#" onclick="changePage(${i})">${i}</a>
            </li>
        `;
    }

    // 다음 페이지
    if (currentPageNum < totalPages) {
        paginationHtml += `
            <li class="page-item">
                <a class="page-link" href="#" onclick="changePage(${currentPageNum + 1})">
                    <i class="bi bi-chevron-right"></i>
                </a>
            </li>
        `;
    }

    pagination.innerHTML = paginationHtml;
}

/**
 * 페이지 변경 (수정됨)
 */
function changePage(page) {
    if (page < 1 || page > totalPages || page === currentPage) return;
    
    currentPage = page;
    
    // 현재 필터가 적용된 상태인지 확인
    if (Object.keys(currentFilters).length > 0) {
        loadApplicationsWithFilter(currentFilters);
    } else {
        loadAllApplications();
    }
}

/**
 * Excel 다운로드 (수정됨 - 필터 조건 포함)
 */
async function exportApplications() {
    try {
        // 현재 적용된 필터 조건들을 파라미터로 전달
        const params = new URLSearchParams();
        
        if (Object.keys(currentFilters).length > 0) {
            Object.entries(currentFilters).forEach(([key, value]) => {
                if (value) params.append(key, value);
            });
        }
        
        const url = params.toString() ? 
            `api/export_applications.php?${params}` : 
            'api/export_applications.php';
            
        const response = await fetch(url);
        
        if (response.ok) {
            const blob = await response.blob();
            const downloadUrl = window.URL.createObjectURL(blob);
            const a = document.createElement('a');
            a.href = downloadUrl;
            a.download = `가입신청내역_${new Date().toISOString().split('T')[0]}.xlsx`;
            document.body.appendChild(a);
            a.click();
            window.URL.revokeObjectURL(downloadUrl);
            document.body.removeChild(a);
        } else {
            alert('Excel 다운로드에 실패했습니다.');
        }
    } catch (error) {
        console.error('Export error:', error);
        alert('Excel 다운로드 중 오류가 발생했습니다.');
    }
}

/**
 * 쿠폰 신청 정리 함수 (Applications 전용)
 */
async function viewCouponDetailWithDetailedConfirm(id, coupon_number) {
    const confirmed = await showConfirmModal(
        '데이터 정리 확인',
        `쿠폰번호 ${coupon_number}의 데이터를 정리하시겠습니까?<br><small class="text-muted">이 작업은 되돌릴 수 없습니다.</small>`,
        '정리하기',
        '취소'
    );
    
    if (!confirmed) return;
    
    try {
        showToast('데이터 정리 중입니다...', 'info');
        
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
            showToast(data.message || '데이터 정리가 완료되었습니다.', 'success');
            
            setTimeout(() => {
                if (Object.keys(currentFilters).length > 0) {
                    loadApplicationsWithFilter(currentFilters);
                } else {
                    loadAllApplications();
                }
            }, 1000);
        } else {
            throw new Error(data.message || '데이터 정리에 실패했습니다.');
        }
        
    } catch (error) {
        console.error('Coupon cleanup error:', error);
        showToast('데이터 정리 중 오류가 발생했습니다: ' + error.message, 'error');
    }
}

/**
 * 쿠폰 신청 복구 함수 (Applications 전용)
 */
async function restoreCouponApplication(id, coupon_number) {
    const confirmed = await showConfirmModal(
        '데이터 복구 확인',
        `쿠폰번호 ${coupon_number}의 데이터를 복구하시겠습니까?<br><small class="text-muted">취소된 신청을 다시 활성화합니다.</small>`,
        '복구하기',
        '취소'
    );
    
    if (!confirmed) return;
    
    try {
        showToast('데이터 복구 중입니다...', 'info');
        
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
            showToast(data.message || '데이터 복구가 완료되었습니다.', 'success');
            
            setTimeout(() => {
                if (Object.keys(currentFilters).length > 0) {
                    loadApplicationsWithFilter(currentFilters);
                } else {
                    loadAllApplications();
                }
            }, 1000);
        } else {
            throw new Error(data.message || '데이터 복구에 실패했습니다.');
        }
        
    } catch (error) {
        console.error('Coupon restore error:', error);
        showToast('데이터 복구 중 오류가 발생했습니다: ' + error.message, 'error');
    }
}

/**
 * Bootstrap 모달을 사용한 확인 대화상자
 */
function showConfirmModal(title, message, confirmText = '확인', cancelText = '취소') {
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
}

/**
 * 토스트 메시지 표시
 */
function showToast(message, type = 'success') {
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

/**
 * 총 건수 업데이트
 */
function updateApplicationsCount(total) {
    const element = document.getElementById('applicationsCount');
    if (element) {
        const count = (typeof total === 'number' && !isNaN(total)) ? total : 0;
        element.textContent = `총 ${count.toLocaleString()}건`;
    }
}

/**
 * 로딩 상태 표시
 */
function showLoadingState() {
    const container = document.getElementById('applicationsTableContainer');
    if (container) {
        container.innerHTML = `
            <div class="text-center py-5">
                <div class="spinner-border text-primary" role="status">
                    <span class="visually-hidden">로딩중...</span>
                </div>
                <p class="text-muted mt-2">데이터를 불러오는 중...</p>
            </div>
        `;
    }
}

/**
 * 에러 상태 표시
 */
function showErrorState(message) {
    const container = document.getElementById('applicationsTableContainer');
    if (container) {
        container.innerHTML = `
            <div class="text-center py-5">
                <i class="bi bi-exclamation-triangle text-danger" style="font-size: 3rem;"></i>
                <p class="text-danger mt-3">${escapeHtml(message)}</p>
                <button class="btn btn-primary" onclick="loadAllApplications()">다시 시도</button>
            </div>
        `;
    }
}

/**
 * 유틸리티 함수들
 */
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
 * 검색 힌트 업데이트 (새로 추가)
 */
function updateSearchHint() {
    const searchType = document.getElementById('searchType').value;
    const hintElement = document.getElementById('searchHint');
    
    if (!hintElement) return;
    
    const hints = {
        'all': '💡 <strong>전체 검색</strong>에서는 이름, 골프장, 쿠폰번호에서 부분 검색됩니다.',
        'name': '💡 <strong>이름</strong> 검색시 정확한 이름을 입력해주세요.',
        'phone': '💡 <strong>전화번호</strong> 검색시 하이픈(-) 없이 정확한 번호를 입력해주세요.',
        'golf_course': '💡 <strong>골프장</strong>은 부분 검색이 가능합니다. (예: "강남" 입력시 "강남골프장" 검색)',
        'coupon': '💡 <strong>쿠폰번호</strong> 검색시 정확한 번호를 입력해주세요.'
    };
    
    hintElement.innerHTML = hints[searchType] || hints['all'];
}

/**
 * 이벤트 리스너 설정
 */
document.addEventListener('DOMContentLoaded', function() {
    console.log('modified applications.js 로드 완료');
    
    // 검색 타입 변경시 힌트 업데이트
    const searchTypeSelect = document.getElementById('searchType');
    if (searchTypeSelect) {
        searchTypeSelect.addEventListener('change', updateSearchHint);
        updateSearchHint(); // 초기 힌트 설정
    }
    
    // Enter 키로 검색 실행
    const searchInput = document.getElementById('searchInput');
    if (searchInput) {
        searchInput.addEventListener('keypress', function(e) {
            if (e.key === 'Enter') {
                filterApplications();
            }
        });
    }
});

// 전역 함수 노출
window.loadAllApplications = loadAllApplications;
window.loadApplicationsWithFilter = loadApplicationsWithFilter;
window.filterApplications = filterApplications;
window.resetFilters = resetFilters;
window.changePage = changePage;
window.exportApplications = exportApplications;
window.viewCouponDetailWithDetailedConfirm = viewCouponDetailWithDetailedConfirm;
window.restoreCouponApplication = restoreCouponApplication;