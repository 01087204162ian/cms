/**
 * 홀인원 보상신청 관련 JavaScript 함수들
 * PCI Korea 홀인원보험 관리 시스템
 */

// 전역 변수
let claimsData = [];
let currentClaimsPage = 1;
let totalClaimsPages = 1;
let claimsItemsPerPage = 10;
let currentClaimsFilters = {
    search: '',
    status: '',
    startDate: '',
    endDate: '',
    sortBy: 'created_at',
    sortOrder: 'desc'
};

/**
 * 전체 홀인원 보상신청 데이터 로드
 */
async function loadAllClaims() {
    try {
        showClaimsLoadingState();
        
        const response = await fetch('api/claims_data.php', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
            },
            body: JSON.stringify({
                page: currentClaimsPage,
                limit: claimsItemsPerPage,
                ...currentClaimsFilters
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
        console.log('Claims API Response:', data);
        
        if (data.success) {
            const responseData = data.data || {};
            claimsData = responseData.claims || [];
            totalClaimsPages = responseData.pagination?.totalPages || 1;
            
            renderClaimsTable(claimsData);
            renderClaimsPagination(responseData.pagination || {});
            
            const totalCount = responseData.pagination?.totalItems || responseData.total || 0;
            updateClaimsCount(totalCount);
            
            // 탭 카운트 업데이트
            updateClaimsTabCount(totalCount);
            
        } else {
            showClaimsErrorState(data.message || '데이터를 불러오는데 실패했습니다.');
            console.error('Claims API Error:', data);
        }
    } catch (error) {
        console.error('Claims load error:', error);
        showClaimsErrorState('서버 연결에 실패했습니다. 잠시 후 다시 시도해주세요.');
    }
}

/**
 * 홀인원 보상신청 테이블 렌더링
 */
function renderClaimsTable(claims) {
    const container = document.getElementById('claimsTableContainer');
    
    if (!claims || claims.length === 0) {
        container.innerHTML = `
            <div class="text-center py-5">
                <i class="bi bi-trophy text-muted" style="font-size: 3rem;"></i>
                <p class="text-muted mt-3">홀인원 보상신청 내역이 없습니다.</p>
                <small class="text-muted">고객이 홀인원을 달성하고 보상 신청을 하면 여기에 표시됩니다.</small>
            </div>
        `;
        return;
    }

    let tableHtml = `
        <div class="table-responsive">
            <table class="table table-hover table-striped">
                <thead class="table-dark">
                    <tr>
                        <th scope="col" style="cursor: pointer;" onclick="sortClaimsTable('created_at')">
                            신청일 
                            <i class="bi bi-arrow-down-up ms-1"></i>
                        </th>
                        <th scope="col" style="cursor: pointer;" onclick="sortClaimsTable('customer_name')">
                            신청자명 
                            <i class="bi bi-arrow-down-up ms-1"></i>
                        </th>
                        <th scope="col">연락처</th>
                        <th scope="col" style="cursor: pointer;" onclick="sortClaimsTable('golf_course')">
                            골프장 
                            <i class="bi bi-arrow-down-up ms-1"></i>
                        </th>
                        <th scope="col" style="cursor: pointer;" onclick="sortClaimsTable('play_date')">
                            경기일 & 홀 
                            <i class="bi bi-arrow-down-up ms-1"></i>
                        </th>
                        <th scope="col">상태</th>
                        <th scope="col">액션</th>
                    </tr>
                </thead>
                <tbody>
    `;

    claims.forEach((claim, index) => {
        const rowNumber = (currentClaimsPage - 1) * claimsItemsPerPage + index + 1;
        const statusInfo = getClaimStatusInfo(claim.status);
        
        tableHtml += `
            <tr>
                <td>
                    <div class="d-flex align-items-center">
                        <span class="badge bg-secondary me-2">${rowNumber}</span>
                        <div class="fw-medium">${formatDateTime(claim.created_at)}</div>
                    </div>
                </td>
                <td>
                    <div class="fw-medium">${escapeHtml(claim.customer_name || '')}</div>
                    <small class="text-muted">신청번호: ${escapeHtml(claim.claim_number || '')}</small>
                </td>
                <td>
                    <span class="text-muted">${maskPhoneNumber(claim.customer_phone || '')}</span>
                </td>
                <td>
                    <div class="fw-medium">${escapeHtml(claim.golf_course || '')}</div>
                </td>
                <td>
                    <div class="fw-medium">${formatDate(claim.play_date)}</div>
                    <small class="text-success fw-bold">${claim.hole_number}번홀</small>
                    ${claim.yardage ? `<small class="text-muted d-block">${claim.yardage}야드</small>` : ''}
                </td>
                <td>
                    <span class="badge ${statusInfo.class}">${statusInfo.text}</span>
                </td>
                <td>
                    <div class="btn-group btn-group-sm">
                        <button class="btn btn-outline-primary" onclick="viewClaimDetail('${claim.id}')" 
                                title="상세보기">
                            <i class="bi bi-eye"></i>
                        </button>
                        ${claim.status === 'pending' ? `
                        <button class="btn btn-outline-success" onclick="approveClaimStatus('${claim.id}')" 
                                title="승인">
                            <i class="bi bi-check-lg"></i>
                        </button>
                        <button class="btn btn-outline-danger" onclick="rejectClaimStatus('${claim.id}')" 
                                title="거절">
                            <i class="bi bi-x-lg"></i>
                        </button>
                        ` : ''}
                    </div>
                </td>
            </tr>
        `;
    });

    tableHtml += `
                </tbody>
            </table>
        </div>
    `;

    container.innerHTML = tableHtml;
}

/**
 * 보상신청 상태 정보 반환
 */
function getClaimStatusInfo(status) {
    const statusMap = {
        'pending': { class: 'bg-warning text-dark', text: '검토대기' },
        'reviewing': { class: 'bg-info text-white', text: '검토중' },
        'investigating': { class: 'bg-primary text-white', text: '조사중' },
        'approved': { class: 'bg-success text-white', text: '승인완료' },
        'rejected': { class: 'bg-danger text-white', text: '거절' },
        'completed': { class: 'bg-dark text-white', text: '지급완료' }
    };
    return statusMap[status] || { class: 'bg-secondary text-white', text: status };
}

/**
 * 전화번호 마스킹
 */
function maskPhoneNumber(phone) {
    if (!phone) return '';
    if (phone.length === 11) {
        return phone.substring(0, 3) + '****' + phone.substring(7);
    }
    return phone.substring(0, 3) + '****' + phone.substring(phone.length - 4);
}

/**
 * 보상신청 페이지네이션 렌더링
 */
function renderClaimsPagination(paginationData) {
    const pagination = document.getElementById('claimsPagination');
    
    if (!paginationData || paginationData.totalPages <= 1) {
        pagination.innerHTML = '';
        return;
    }

    const totalPages = paginationData.totalPages || 1;
    const currentPageNum = paginationData.currentPage || currentClaimsPage;
    
    let paginationHtml = '';
    
    // 이전 페이지
    if (currentPageNum > 1) {
        paginationHtml += `
            <li class="page-item">
                <a class="page-link" href="#" onclick="changeClaimsPage(${currentPageNum - 1})">
                    <i class="bi bi-chevron-left"></i>
                </a>
            </li>
        `;
    }

    // 페이지 번호들
    const startPage = Math.max(1, currentPageNum - 2);
    const endPage = Math.min(totalPages, currentPageNum + 2);

    if (startPage > 1) {
        paginationHtml += `
            <li class="page-item">
                <a class="page-link" href="#" onclick="changeClaimsPage(1)">1</a>
            </li>
        `;
        if (startPage > 2) {
            paginationHtml += `<li class="page-item disabled"><span class="page-link">...</span></li>`;
        }
    }

    for (let i = startPage; i <= endPage; i++) {
        paginationHtml += `
            <li class="page-item ${i === currentPageNum ? 'active' : ''}">
                <a class="page-link" href="#" onclick="changeClaimsPage(${i})">${i}</a>
            </li>
        `;
    }

    if (endPage < totalPages) {
        if (endPage < totalPages - 1) {
            paginationHtml += `<li class="page-item disabled"><span class="page-link">...</span></li>`;
        }
        paginationHtml += `
            <li class="page-item">
                <a class="page-link" href="#" onclick="changeClaimsPage(${totalPages})">${totalPages}</a>
            </li>
        `;
    }

    // 다음 페이지
    if (currentPageNum < totalPages) {
        paginationHtml += `
            <li class="page-item">
                <a class="page-link" href="#" onclick="changeClaimsPage(${currentPageNum + 1})">
                    <i class="bi bi-chevron-right"></i>
                </a>
            </li>
        `;
    }

    pagination.innerHTML = paginationHtml;
}

/**
 * 보상신청 페이지 변경
 */
function changeClaimsPage(page) {
    if (page < 1 || page > totalClaimsPages || page === currentClaimsPage) return;
    
    currentClaimsPage = page;
    loadAllClaims();
}

/**
 * 보상신청 검색 및 필터 적용
 */
function filterClaims() {
    const searchInput = document.getElementById('claimsSearchInput');
    const statusSelect = document.getElementById('claimsStatusFilter');
    const startDate = document.getElementById('claimsStartDate');
    const endDate = document.getElementById('claimsEndDate');

    currentClaimsFilters = {
        ...currentClaimsFilters,
        search: searchInput ? searchInput.value.trim() : '',
        status: statusSelect ? statusSelect.value : '',
        startDate: startDate ? startDate.value : '',
        endDate: endDate ? endDate.value : ''
    };

    console.log('🔍 보상신청 검색 필터:', currentClaimsFilters);
    
    currentClaimsPage = 1;
    loadAllClaims();
}

/**
 * 보상신청 테이블 정렬
 */
function sortClaimsTable(column) {
    if (currentClaimsFilters.sortBy === column) {
        currentClaimsFilters.sortOrder = currentClaimsFilters.sortOrder === 'asc' ? 'desc' : 'asc';
    } else {
        currentClaimsFilters.sortBy = column;
        currentClaimsFilters.sortOrder = 'desc';
    }

    loadAllClaims();
}

/**
 * 보상신청 상세보기
 */
function viewClaimDetail(claimId) {
     if (typeof window.viewClaimDetail === 'function') {
        window.viewClaimDetail(claimId);
    }
}

/**
 * 보상신청 승인
 */
async function approveClaimStatus(claimId) {
    if (!confirm('이 보상신청을 승인하시겠습니까?')) return;
    
    try {
        const response = await fetch('api/update_claim_status.php', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
            },
            body: JSON.stringify({
                claimId: claimId,
                status: 'approved',
                comment: '관리자 승인'
            })
        });

        const data = await response.json();
        
        if (data.success) {
            alert('보상신청이 승인되었습니다.');
            loadAllClaims(); // 목록 새로고침
        } else {
            alert('승인 처리 중 오류가 발생했습니다: ' + data.message);
        }
    } catch (error) {
        console.error('Approve claim error:', error);
        alert('승인 처리 중 오류가 발생했습니다.');
    }
}

/**
 * 보상신청 거절
 */
async function rejectClaimStatus(claimId) {
    const reason = prompt('거절 사유를 입력해주세요:');
    if (!reason || !reason.trim()) return;
    
    try {
        const response = await fetch('api/update_claim_status.php', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
            },
            body: JSON.stringify({
                claimId: claimId,
                status: 'rejected',
                comment: reason.trim()
            })
        });

        const data = await response.json();
        
        if (data.success) {
            alert('보상신청이 거절되었습니다.');
            loadAllClaims(); // 목록 새로고침
        } else {
            alert('거절 처리 중 오류가 발생했습니다: ' + data.message);
        }
    } catch (error) {
        console.error('Reject claim error:', error);
        alert('거절 처리 중 오류가 발생했습니다.');
    }
}

/**
 * 보상신청 Excel 다운로드
 */
async function exportClaims() {
    try {
        const response = await fetch('api/export_claims.php', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
            },
            body: JSON.stringify(currentClaimsFilters)
        });

        if (response.ok) {
            const blob = await response.blob();
            const url = window.URL.createObjectURL(blob);
            const a = document.createElement('a');
            a.href = url;
            a.download = `홀인원보상신청내역_${new Date().toISOString().split('T')[0]}.xlsx`;
            document.body.appendChild(a);
            a.click();
            window.URL.revokeObjectURL(url);
            document.body.removeChild(a);
        } else {
            alert('Excel 다운로드에 실패했습니다.');
        }
    } catch (error) {
        console.error('Export claims error:', error);
        alert('Excel 다운로드 중 오류가 발생했습니다.');
    }
}

/**
 * 보상신청 새로고침
 */
function refreshClaims() {
    loadAllClaims();
}

/**
 * 보상신청 총 건수 업데이트
 */
function updateClaimsCount(total) {
    const element = document.getElementById('claimsCount');
    if (element) {
        const count = (typeof total === 'number' && !isNaN(total)) ? total : 0;
        element.textContent = `총 ${count.toLocaleString()}건`;
    }
}

/**
 * 보상신청 탭 카운트 업데이트
 */
function updateClaimsTabCount(total) {
    const element = document.getElementById('claimsTabCount');
    if (element) {
        const count = (typeof total === 'number' && !isNaN(total)) ? total : 0;
        element.textContent = count.toLocaleString();
    }
}

/**
 * 보상신청 로딩 상태 표시
 */
function showClaimsLoadingState() {
    const container = document.getElementById('claimsTableContainer');
    if (container) {
        container.innerHTML = `
            <div class="text-center py-5">
                <div class="spinner-border text-danger" role="status">
                    <span class="visually-hidden">로딩중...</span>
                </div>
                <p class="text-muted mt-2">보상신청 데이터를 불러오는 중...</p>
            </div>
        `;
    }
}

/**
 * 보상신청 에러 상태 표시
 */
function showClaimsErrorState(message) {
    const container = document.getElementById('claimsTableContainer');
    if (container) {
        container.innerHTML = `
            <div class="text-center py-5">
                <i class="bi bi-exclamation-triangle text-danger" style="font-size: 3rem;"></i>
                <p class="text-danger mt-3">${escapeHtml(message)}</p>
                <button class="btn btn-danger" onclick="loadAllClaims()">다시 시도</button>
            </div>
        `;
    }
}

/**
 * 날짜 포맷팅 (기존 함수 재사용)
 */
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

/**
 * 보상신청 이벤트 리스너 등록
 */
document.addEventListener('DOMContentLoaded', function() {
    console.log('claims.js 로드 완료');
    
    // Enter 키로 검색
    const claimsSearchInput = document.getElementById('claimsSearchInput');
    if (claimsSearchInput) {
        claimsSearchInput.addEventListener('keypress', function(e) {
            if (e.key === 'Enter') {
                e.preventDefault();
                filterClaims();
            }
        });
    }

    // 상태 필터 변경시 자동 적용
    const claimsStatusFilter = document.getElementById('claimsStatusFilter');
    if (claimsStatusFilter) {
        claimsStatusFilter.addEventListener('change', filterClaims);
    }

    // 날짜 필터 자동 적용
    const claimsStartDate = document.getElementById('claimsStartDate');
    const claimsEndDate = document.getElementById('claimsEndDate');
    
    if (claimsStartDate) {
        claimsStartDate.addEventListener('change', function() {
            if (claimsStartDate.value && claimsEndDate && claimsEndDate.value) {
                filterClaims();
            }
        });
    }
    
    if (claimsEndDate) {
        claimsEndDate.addEventListener('change', function() {
            if (claimsStartDate && claimsStartDate.value && claimsEndDate.value) {
                filterClaims();
            }
        });
    }

    // 탭 전환 이벤트
    const claimsTabBtn = document.getElementById('claims-tab-btn');
    if (claimsTabBtn) {
        claimsTabBtn.addEventListener('shown.bs.tab', function() {
            // 보상신청 탭이 활성화될 때 데이터 로드
            loadAllClaims();
        });
    }
});

// 전역에 함수 노출
window.loadAllClaims = loadAllClaims;
window.filterClaims = filterClaims;
window.changeClaimsPage = changeClaimsPage;
window.sortClaimsTable = sortClaimsTable;
window.viewClaimDetail = viewClaimDetail;
window.approveClaimStatus = approveClaimStatus;
window.rejectClaimStatus = rejectClaimStatus;
window.exportClaims = exportClaims;
window.refreshClaims = refreshClaims;