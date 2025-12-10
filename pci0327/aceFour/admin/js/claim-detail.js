/**
 * 홀인원 보상신청 상세보기 관련 JavaScript 함수들
 * PCI Korea 홀인원보험 관리 시스템
 * 개선된 파일 표시 및 이미지 뷰어 기능
 */

// 전역 변수
let currentClaimDetail = null;
let currentClaimId = null;

/**
 * 보상신청 상세보기 모달 열기
 */
async function viewClaimDetail(claimId) {
    currentClaimId = claimId;
    
    // 모달 열기
    const modal = new bootstrap.Modal(document.getElementById('claimDetailModal'));
    modal.show();
    
    // 상세 정보 로드
    await loadClaimDetail(claimId);
}

/**
 * 보상신청 상세 정보 로드
 */
async function loadClaimDetail(claimId) {
    try {
        showClaimDetailLoading();
        
        const response = await fetch(`api/claim_detail.php?id=${claimId}`);
        
        if (!response.ok) {
            if (response.status === 401) {
                window.location.href = 'index.html';
                return;
            }
            throw new Error(`HTTP ${response.status}: ${response.statusText}`);
        }

        const data = await response.json();
        console.log('Claim Detail API Response:', data);
        
        if (data.success) {
            currentClaimDetail = data.data;
            renderClaimDetail(currentClaimDetail);
        } else {
            showClaimDetailError(data.message || '상세 정보를 불러오는데 실패했습니다.');
        }
    } catch (error) {
        console.error('Claim detail load error:', error);
        showClaimDetailError('서버 연결에 실패했습니다. 잠시 후 다시 시도해주세요.');
    }
}

/**
 * 보상신청 상세 정보 렌더링
 */
function renderClaimDetail(claim) {
    // 로딩 숨기고 컨텐츠 표시
    document.getElementById('claimDetailLoading').style.display = 'none';
    document.getElementById('claimDetailError').style.display = 'none';
    document.getElementById('claimDetailContent').style.display = 'block';
    
    // 기본 정보 설정
    const statusInfo = getClaimStatusInfo(claim.status);
    
    // 상단 요약 정보
    document.getElementById('claimCustomerName').textContent = claim.customer_name || '-';
    document.getElementById('claimStatusBadge').textContent = statusInfo.text;
    document.getElementById('claimStatusBadge').className = `badge ${statusInfo.class}`;
    document.getElementById('claimNumber').textContent = claim.claim_number || '-';
    document.getElementById('claimCreatedAt').textContent = formatDateTime(claim.created_at);
    document.getElementById('claimGolfCourse').textContent = claim.golf_course || '-';
    document.getElementById('claimPlayDate').textContent = formatDate(claim.play_date);
    document.getElementById('claimHoleInfo').textContent = 
        `${claim.hole_number || '-'}번홀 (${claim.yardage || '-'}야드)`;
    document.getElementById('claimCustomerPhone').textContent = claim.customer_phone || '-';
    
    // 기본정보 탭
    document.getElementById('detailCustomerName').textContent = claim.customer_name || '-';
    document.getElementById('detailCustomerPhone').textContent = claim.customer_phone || '-';
    document.getElementById('detailCustomerEmail').textContent = claim.customer_email || '-';
    document.getElementById('detailCustomerAddress').textContent = claim.customer_address || '-';
    document.getElementById('detailGolfCourse').textContent = claim.golf_course || '-';
    document.getElementById('detailPlayDate').textContent = formatDate(claim.play_date);
    document.getElementById('detailHoleNumber').textContent = `${claim.hole_number || '-'}번홀`;
    document.getElementById('detailYardage').textContent = `${claim.yardage || '-'}야드`;
    document.getElementById('detailUsedClub').textContent = claim.used_club || '-';
    document.getElementById('detailCaddyName').textContent = claim.caddy_name || '-';
    document.getElementById('detailDescription').textContent = claim.description || '추가 설명이 없습니다.';
    
    // 증명사진 렌더링
    renderClaimPhotos(claim.photos || []);
    
    // 스코어카드 렌더링
    renderScorecard(claim.scorecard);
    
    // 처리이력 렌더링
    renderClaimHistory(claim.history || []);
    
    // 액션 버튼 렌더링
    renderClaimActionButtons(claim);
}

/**
 * 증명사진 갤러리 렌더링 (개선된 버전)
 */
function renderClaimPhotos(photos) {
    const photosGallery = document.getElementById('photosGallery');
    const noPhotos = document.getElementById('noPhotos');
    const photosCount = document.getElementById('photosCount');
    
    photosCount.textContent = photos.length;
    
    if (photos.length === 0) {
        photosGallery.innerHTML = '';
        noPhotos.style.display = 'block';
        return;
    }
    
    noPhotos.style.display = 'none';
    
    let photosHtml = '';
    photos.forEach((photo, index) => {
        const fileExistsClass = photo.exists ? 'file-exists' : 'file-missing';
        const fileIcon = photo.exists ? 'bi-image' : 'bi-exclamation-triangle';
        
        photosHtml += `
            <div class="col-md-6 col-lg-4 mb-4">
                <div class="card photo-card ${fileExistsClass}" style="border-radius: 12px; overflow: hidden; box-shadow: 0 2px 10px rgba(0,0,0,0.1);">
                    <div class="photo-container" style="position: relative; height: 200px; background: #f8f9fa;">
                        ${photo.exists ? `
                            <img src="${photo.url}" 
                                 alt="${photo.title}" 
                                 class="card-img-top photo-thumbnail" 
                                 style="height: 200px; object-fit: cover; cursor: pointer;"
                                 onclick="enlargeImage('${photo.url}', '${photo.title}')"
                                 onerror="this.onerror=null; this.src='data:image/svg+xml,%3Csvg xmlns=\\'http://www.w3.org/2000/svg\\' fill=\\'%23666\\' viewBox=\\'0 0 16 16\\'%3E%3Cpath d=\\'M6.002 5.5a1.5 1.5 0 1 1-3 0 1.5 1.5 0 0 1 3 0z\\'//%3Cpath d=\\'M2.002 1a2 2 0 0 0-2 2v10a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2V3a2 2 0 0 0-2-2h-12zm12 1a1 1 0 0 1 1 1v6.5l-3.777-1.947a.5.5 0 0 0-.577.093l-3.71 3.71-2.66-1.772a.5.5 0 0 0-.63.062L1.002 12V3a1 1 0 0 1 1-1h12z\\'//%3C/svg%3E'; this.style.padding='60px';"/>
                            <div class="photo-overlay" style="position: absolute; top: 8px; right: 8px;">
                                <span class="badge bg-success">
                                    <i class="bi bi-check-circle me-1"></i>파일 존재
                                </span>
                            </div>
                        ` : `
                            <div class="d-flex align-items-center justify-content-center h-100 text-danger">
                                <div class="text-center">
                                    <i class="bi bi-exclamation-triangle" style="font-size: 2rem;"></i>
                                    <p class="mt-2 mb-0 small">파일을 찾을 수 없음</p>
                                </div>
                            </div>
                            <div class="photo-overlay" style="position: absolute; top: 8px; right: 8px;">
                                <span class="badge bg-danger">
                                    <i class="bi bi-x-circle me-1"></i>파일 없음
                                </span>
                            </div>
                        `}
                    </div>
                    <div class="card-body">
                        <h6 class="card-title mb-1">${escapeHtml(photo.title)}</h6>
                        <div class="d-flex justify-content-between align-items-center">
                            <small class="text-muted">${formatDateTime(photo.uploaded_at)}</small>
                            ${photo.size_formatted ? `<small class="text-muted">${photo.size_formatted}</small>` : ''}
                        </div>
                        <div class="mt-2">
                            <small class="text-secondary d-block" style="word-break: break-all;">
                                ${escapeHtml(photo.filename)}
                            </small>
                        </div>
                        ${photo.exists ? `
                            <div class="mt-2 d-flex gap-1">
                                <button class="btn btn-sm btn-outline-primary flex-fill" 
                                        onclick="enlargeImage('${photo.url}', '${photo.title}')">
                                    <i class="bi bi-eye me-1"></i>보기
                                </button>
                                <button class="btn btn-sm btn-outline-success" 
                                        onclick="downloadFile('${photo.url}', '${photo.filename}')">
                                    <i class="bi bi-download"></i>
                                </button>
                            </div>
                        ` : ''}
                    </div>
                </div>
            </div>
        `;
    });
    
    photosGallery.innerHTML = photosHtml;
}

/**
 * 스코어카드 렌더링 (개선된 버전)
 */
function renderScorecard(scorecard) {
    const scorecardImage = document.getElementById('scorecardImage');
    const noScorecard = document.getElementById('noScorecard');
    
    if (!scorecard || !scorecard.url) {
        scorecardImage.innerHTML = '';
        noScorecard.style.display = 'block';
        return;
    }
    
    noScorecard.style.display = 'none';
    
    const fileExistsClass = scorecard.exists ? 'file-exists' : 'file-missing';
    
    scorecardImage.innerHTML = `
        <div class="card scorecard-card ${fileExistsClass}" style="max-width: 600px; margin: 0 auto; border-radius: 12px; overflow: hidden; box-shadow: 0 2px 10px rgba(0,0,0,0.1);">
            <div class="scorecard-container" style="position: relative; background: #f8f9fa;">
                ${scorecard.exists ? `
                    <img src="${scorecard.url}" 
                         alt="스코어카드" 
                         class="card-img-top scorecard-thumbnail" 
                         style="max-height: 400px; object-fit: contain; cursor: pointer;"
                         onclick="enlargeImage('${scorecard.url}', '스코어카드')"
                         onerror="this.onerror=null; this.src='data:image/svg+xml,%3Csvg xmlns=\\'http://www.w3.org/2000/svg\\' fill=\\'%23666\\' viewBox=\\'0 0 16 16\\'%3E%3Cpath d=\\'M14 14V4.5L9.5 0H4a2 2 0 0 0-2 2v12a2 2 0 0 0 2 2h8a2 2 0 0 0 2-2zM9.5 3A1.5 1.5 0 0 0 11 4.5h2V14a1 1 0 0 1-1 1H4a1 1 0 0 1-1-1V2a1 1 0 0 1 1-1h5.5v2z\\'//%3C/svg%3E'; this.style.padding='60px';"/>
                    <div class="scorecard-overlay" style="position: absolute; top: 8px; right: 8px;">
                        <span class="badge bg-success">
                            <i class="bi bi-check-circle me-1"></i>파일 존재
                        </span>
                    </div>
                ` : `
                    <div class="d-flex align-items-center justify-content-center text-danger" style="height: 200px;">
                        <div class="text-center">
                            <i class="bi bi-exclamation-triangle" style="font-size: 3rem;"></i>
                            <p class="mt-2 mb-0">스코어카드 파일을 찾을 수 없습니다</p>
                        </div>
                    </div>
                    <div class="scorecard-overlay" style="position: absolute; top: 8px; right: 8px;">
                        <span class="badge bg-danger">
                            <i class="bi bi-x-circle me-1"></i>파일 없음
                        </span>
                    </div>
                `}
            </div>
            <div class="card-body">
                <h6 class="card-title">${escapeHtml(scorecard.title)}</h6>
                <div class="d-flex justify-content-between align-items-center mb-2">
                    <small class="text-muted">업로드: ${formatDateTime(scorecard.uploaded_at)}</small>
                    ${scorecard.size_formatted ? `<small class="text-muted">${scorecard.size_formatted}</small>` : ''}
                </div>
                <small class="text-secondary d-block mb-3" style="word-break: break-all;">
                    ${escapeHtml(scorecard.filename)}
                </small>
                ${scorecard.exists ? `
                    <div class="d-flex gap-2">
                        <button class="btn btn-sm btn-outline-primary flex-fill" 
                                onclick="enlargeImage('${scorecard.url}', '스코어카드')">
                            <i class="bi bi-eye me-1"></i>크게 보기
                        </button>
                        <button class="btn btn-sm btn-outline-success" 
                                onclick="downloadFile('${scorecard.url}', '${scorecard.filename}')">
                            <i class="bi bi-download me-1"></i>다운로드
                        </button>
                    </div>
                ` : ''}
            </div>
        </div>
    `;
}

/**
 * 처리이력 렌더링
 */
function renderClaimHistory(history) {
    const historyContainer = document.getElementById('claimHistory');
    
    if (history.length === 0) {
        historyContainer.innerHTML = `
            <div class="text-center py-4">
                <i class="bi bi-clock-history text-muted" style="font-size: 2rem;"></i>
                <p class="text-muted mt-2">처리 이력이 없습니다.</p>
            </div>
        `;
        return;
    }
    
    let historyHtml = '<div class="timeline">';
    history.forEach((item, index) => {
        const statusClass = `status-${item.status}`;
        const statusInfo = getClaimStatusInfo(item.status);
        const isLast = index === history.length - 1;
        
        historyHtml += `
            <div class="timeline-item ${statusClass} ${isLast ? 'timeline-last' : ''}" style="position: relative; padding-left: 40px; padding-bottom: 20px;">
                <div class="timeline-marker" style="position: absolute; left: 0; top: 0; width: 24px; height: 24px; border-radius: 50%; display: flex; align-items: center; justify-content: center;">
                    <div class="marker-inner ${statusInfo.class}" style="width: 16px; height: 16px; border-radius: 50%;"></div>
                </div>
                ${!isLast ? '<div class="timeline-line" style="position: absolute; left: 11px; top: 24px; width: 2px; height: calc(100% - 4px); background: #e9ecef;"></div>' : ''}
                <div class="timeline-content">
                    <div class="d-flex justify-content-between align-items-start mb-1">
                        <h6 class="mb-0">${escapeHtml(item.action_title)}</h6>
                        <span class="badge ${statusInfo.class} badge-sm">
                            ${statusInfo.text}
                        </span>
                    </div>
                    <p class="text-muted mb-1 small">${escapeHtml(item.comment || '비고 없음')}</p>
                    <small class="text-secondary">
                        <i class="bi bi-person me-1"></i>${escapeHtml(item.admin_name || '관리자')} | 
                        <i class="bi bi-calendar3 me-1"></i>${formatDateTime(item.created_at)}
                    </small>
                </div>
            </div>
        `;
    });
    historyHtml += '</div>';
    
    historyContainer.innerHTML = historyHtml;
}


/**
 * 액션 버튼 렌더링 (통합 버전 - 상태변경 포함)
 */
function renderClaimActionButtons(claim) {
    const actionButtons = document.getElementById('claimActionButtons');
    
    let buttonsHtml = '';
    
    // 상태별 주요 액션 버튼
    switch(claim.status) {
        case 'pending':
            buttonsHtml += `
                <button type="button" class="btn btn-sm btn-success" onclick="approveClaimFromDetail()">
                    <i class="bi bi-check-lg me-1"></i>승인
                </button>
                <button type="button" class="btn btn-sm btn-danger" onclick="rejectClaimFromDetail()">
                    <i class="bi bi-x-lg me-1"></i>거절
                </button>
            `;
            break;
            
        case 'approved':
            buttonsHtml += `
                <button type="button" class="btn btn-sm btn-primary" onclick="markClaimCompleted()">
                    <i class="bi bi-check-circle me-1"></i>지급완료
                </button>
            `;
            break;
            
        case 'reviewing':
            buttonsHtml += `
                <button type="button" class="btn btn-sm btn-success" onclick="approveClaimFromDetail()">
                    <i class="bi bi-check-lg me-1"></i>승인
                </button>
                <button type="button" class="btn btn-sm btn-danger" onclick="rejectClaimFromDetail()">
                    <i class="bi bi-x-lg me-1"></i>거절
                </button>
            `;
            break;
            
        case 'investigating':
            buttonsHtml += `
                <button type="button" class="btn btn-sm btn-success" onclick="approveClaimFromDetail()">
                    <i class="bi bi-check-lg me-1"></i>승인
                </button>
                <button type="button" class="btn btn-sm btn-danger" onclick="rejectClaimFromDetail()">
                    <i class="bi bi-x-lg me-1"></i>거절
                </button>
            `;
            break;
            
        case 'rejected':
            buttonsHtml += `
                <button type="button" class="btn btn-sm btn-outline-success" onclick="changeClaimStatus('pending')">
                    <i class="bi bi-arrow-counterclockwise me-1"></i>재검토
                </button>
            `;
            break;
            
        case 'completed':
            buttonsHtml += `
                <span class="badge bg-success fs-6 px-3 py-2">
                    <i class="bi bi-check-circle-fill me-1"></i>처리완료
                </span>
            `;
            break;
    }
    
    // 모든 상태에서 상태변경 드롭다운 추가 (완료 상태 제외)
    if (claim.status !== 'completed') {
        buttonsHtml += `
            <div class="dropdown">
                <button class="btn btn-sm btn-outline-secondary dropdown-toggle" type="button" 
                        data-bs-toggle="dropdown" aria-expanded="false">
                    <i class="bi bi-gear me-1"></i>상태변경
                </button>
                <ul class="dropdown-menu dropdown-menu-end">
                    ${claim.status !== 'pending' ? '<li><a class="dropdown-item" href="#" onclick="changeClaimStatus(\'pending\')"><i class="bi bi-clock me-2"></i>검토대기</a></li>' : ''}
                    ${claim.status !== 'reviewing' ? '<li><a class="dropdown-item" href="#" onclick="changeClaimStatus(\'reviewing\')"><i class="bi bi-eye me-2"></i>검토중</a></li>' : ''}
                    ${claim.status !== 'investigating' ? '<li><a class="dropdown-item" href="#" onclick="changeClaimStatus(\'investigating\')"><i class="bi bi-search me-2"></i>조사중</a></li>' : ''}
                    ${claim.status !== 'approved' ? '<li><a class="dropdown-item text-success" href="#" onclick="changeClaimStatus(\'approved\')"><i class="bi bi-check-circle me-2"></i>승인완료</a></li>' : ''}
                    <li><hr class="dropdown-divider"></li>
                    <li><a class="dropdown-item text-danger" href="#" onclick="changeClaimStatus('rejected')">
                        <i class="bi bi-x-circle me-2"></i>거절
                    </a></li>
                </ul>
            </div>
        `;
    }
    
    actionButtons.innerHTML = buttonsHtml;
}

/**
 * 상태 변경 (개선된 버전 - 드롭다운에서 호출)
 */
async function changeClaimStatus(newStatus) {
    if (!currentClaimId) return;
    
    const statusTexts = {
        'pending': '검토대기',
        'reviewing': '검토중',
        'investigating': '조사중',
        'approved': '승인완료',
        'rejected': '거절',
        'completed': '지급완료'
    };
    
    const statusText = statusTexts[newStatus] || newStatus;
    
    let confirmMessage = `상태를 "${statusText}"로 변경하시겠습니까?`;
    let comment = '';
    
    // 거절의 경우 사유 입력 필요
    if (newStatus === 'rejected') {
        comment = prompt('거절 사유를 입력해주세요:');
        if (!comment || !comment.trim()) return;
        comment = comment.trim();
    } else {
        // 다른 상태 변경의 경우 선택적 코멘트
        comment = prompt(`${statusText}로 변경하는 사유를 입력해주세요 (선택사항):`) || '';
        comment = comment.trim();
    }
    
    if (!confirm(confirmMessage)) return;
    
    try {
        const response = await fetch('api/update_claim_status.php', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
            },
            body: JSON.stringify({
                claimId: currentClaimId,
                status: newStatus,
                comment: comment || `상태를 ${statusText}로 변경`
            })
        });

        const data = await response.json();
        
        if (data.success) {
            showSuccessToast(`상태가 "${statusText}"로 변경되었습니다.`);
            await loadClaimDetail(currentClaimId);
            
            // 목록 새로고침
            if (typeof loadAllClaims === 'function') {
                loadAllClaims();
            }
        } else {
            showErrorToast('상태 변경 중 오류가 발생했습니다: ' + data.message);
        }
    } catch (error) {
        console.error('Change status error:', error);
        showErrorToast('상태 변경 중 오류가 발생했습니다.');
    }
}

/**
 * 이미지 확대보기 (개선된 버전)
 */
function enlargeImage(imageUrl, title) {
    document.getElementById('imageViewTitle').textContent = title;
    const enlargedImage = document.getElementById('enlargedImage');
    
    // 로딩 스피너 표시
    enlargedImage.src = '';
    enlargedImage.style.display = 'none';
    
    const loadingDiv = document.createElement('div');
    loadingDiv.className = 'text-center p-5';
    loadingDiv.innerHTML = `
        <div class="spinner-border text-primary" role="status">
            <span class="visually-hidden">이미지 로딩중...</span>
        </div>
        <p class="text-muted mt-2">이미지를 불러오는 중...</p>
    `;
    enlargedImage.parentNode.appendChild(loadingDiv);
    
    // 이미지 로드
    enlargedImage.onload = function() {
        enlargedImage.style.display = 'block';
        if (loadingDiv.parentNode) {
            loadingDiv.parentNode.removeChild(loadingDiv);
        }
    };
    
    enlargedImage.onerror = function() {
        if (loadingDiv.parentNode) {
            loadingDiv.parentNode.removeChild(loadingDiv);
        }
        this.parentNode.innerHTML = `
            <div class="text-center p-5 text-danger">
                <i class="bi bi-exclamation-triangle" style="font-size: 3rem;"></i>
                <p class="mt-2">이미지를 불러올 수 없습니다.</p>
                <small class="text-muted">파일이 존재하지 않거나 손상되었을 수 있습니다.</small>
            </div>
        `;
    };
    
    enlargedImage.src = imageUrl;
    
    const imageModal = new bootstrap.Modal(document.getElementById('imageViewModal'));
    imageModal.show();
}

/**
 * 파일 다운로드 (새로 추가된 기능)
 */
async function downloadFile(fileUrl, filename) {
    try {
        const response = await fetch(fileUrl);
        
        if (!response.ok) {
            throw new Error('파일 다운로드에 실패했습니다.');
        }
        
        const blob = await response.blob();
        const url = window.URL.createObjectURL(blob);
        const a = document.createElement('a');
        a.href = url;
        a.download = filename;
        document.body.appendChild(a);
        a.click();
        window.URL.revokeObjectURL(url);
        document.body.removeChild(a);
        
        showSuccessToast('파일 다운로드가 완료되었습니다.');
        
    } catch (error) {
        console.error('File download error:', error);
        showErrorToast('파일 다운로드 중 오류가 발생했습니다.');
    }
}

/**
 * 상세보기에서 승인 처리
 */
async function approveClaimFromDetail() {
    if (!currentClaimId) return;
    
    if (!confirm('이 보상신청을 승인하시겠습니까?')) return;
    
    try {
        const response = await fetch('api/update_claim_status.php', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
            },
            body: JSON.stringify({
                claimId: currentClaimId,
                status: 'approved',
                comment: '관리자 승인 (상세보기에서 처리)'
            })
        });

        const data = await response.json();
        
        if (data.success) {
            showSuccessToast('보상신청이 승인되었습니다.');
            await loadClaimDetail(currentClaimId);
            
            if (typeof loadAllClaims === 'function') {
                loadAllClaims();
            }
        } else {
            alert('승인 처리 중 오류가 발생했습니다: ' + data.message);
        }
    } catch (error) {
        console.error('Approve claim error:', error);
        alert('승인 처리 중 오류가 발생했습니다.');
    }
}

/**
 * 상세보기에서 거절 처리
 */
async function rejectClaimFromDetail() {
    if (!currentClaimId) return;
    
    const reason = prompt('거절 사유를 입력해주세요:');
    if (!reason || !reason.trim()) return;
    
    try {
        const response = await fetch('api/update_claim_status.php', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
            },
            body: JSON.stringify({
                claimId: currentClaimId,
                status: 'rejected',
                comment: reason.trim()
            })
        });

        const data = await response.json();
        
        if (data.success) {
            showSuccessToast('보상신청이 거절되었습니다.');
            await loadClaimDetail(currentClaimId);
            
            if (typeof loadAllClaims === 'function') {
                loadAllClaims();
            }
        } else {
            alert('거절 처리 중 오류가 발생했습니다: ' + data.message);
        }
    } catch (error) {
        console.error('Reject claim error:', error);
        alert('거절 처리 중 오류가 발생했습니다.');
    }
}

/**
 * 지급완료 처리
 */
async function markClaimCompleted() {
    if (!currentClaimId) return;
    
    if (!confirm('이 보상신청을 지급완료로 처리하시겠습니까?')) return;
    
    try {
        const response = await fetch('api/update_claim_status.php', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
            },
            body: JSON.stringify({
                claimId: currentClaimId,
                status: 'completed',
                comment: '보상금 지급 완료'
            })
        });

        const data = await response.json();
        
        if (data.success) {
            showSuccessToast('지급완료 처리되었습니다.');
            await loadClaimDetail(currentClaimId);
            
            if (typeof loadAllClaims === 'function') {
                loadAllClaims();
            }
        } else {
            alert('지급완료 처리 중 오류가 발생했습니다: ' + data.message);
        }
    } catch (error) {
        console.error('Complete claim error:', error);
        alert('지급완료 처리 중 오류가 발생했습니다.');
    }
}

/**
 * 상태 변경
 */
async function changeClaimStatus(newStatus) {
    if (!currentClaimId) return;
    
    const statusText = getClaimStatusInfo(newStatus).text;
    const comment = prompt(`${statusText}로 변경하는 사유를 입력해주세요:`);
    if (!comment || !comment.trim()) return;
    
    try {
        const response = await fetch('api/update_claim_status.php', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
            },
            body: JSON.stringify({
                claimId: currentClaimId,
                status: newStatus,
                comment: comment.trim()
            })
        });

        const data = await response.json();
        
        if (data.success) {
            showSuccessToast(`상태가 "${statusText}"로 변경되었습니다.`);
            await loadClaimDetail(currentClaimId);
            
            if (typeof loadAllClaims === 'function') {
                loadAllClaims();
            }
        } else {
            alert('상태 변경 중 오류가 발생했습니다: ' + data.message);
        }
    } catch (error) {
        console.error('Change status error:', error);
        alert('상태 변경 중 오류가 발생했습니다.');
    }
}

/**
 * 보상신청서 인쇄
 */
function printClaimDetail() {
    if (!currentClaimId) return;
    
    const printWindow = window.open(`api/print_claim.php?id=${currentClaimId}`, '_blank');
    if (printWindow) {
        printWindow.onload = function() {
            printWindow.print();
        };
    }
}

/**
 * PDF 다운로드
 */
async function downloadClaimPDF() {
    if (!currentClaimId) {
        showErrorToast('신청서 정보를 찾을 수 없습니다.');
        return;
    }
    
    try {
        // PDF 파일이 준비되지 않았으므로 새창에서 인쇄용 페이지 열기
        const printUrl = `api/export_claim_pdf.php?id=${currentClaimId}`;
        const printWindow = window.open(printUrl, '_blank', 'width=800,height=600');
        
        if (!printWindow) {
            // 팝업이 차단된 경우
            showErrorToast('팝업이 차단되었습니다. 팝업을 허용해주세요.');
            return;
        }
        
        // 새창 로딩 완료 후 인쇄 대화상자 자동 열기는 PHP에서 처리
        showSuccessToast('인쇄 페이지를 새창에서 엽니다.');
        
    } catch (error) {
        console.error('PDF export error:', error);
        
        // API가 없으면 임시로 현재 상세내용을 인쇄
        printClaimDetail();
        showErrorToast('PDF 생성 기능이 준비 중입니다. 임시로 화면 인쇄를 사용하세요.');
    }
}
/**
 * 현재 모달 내용 인쇄 (임시 방법)
 */
function printClaimDetail() {
    if (!currentClaimDetail) {
        showErrorToast('인쇄할 내용이 없습니다.');
        return;
    }
    
    // 모달 내용을 새창에서 인쇄용으로 정리
    const printContent = generatePrintContent(currentClaimDetail);
    const printWindow = window.open('', '_blank', 'width=800,height=600');
    
    if (!printWindow) {
        showErrorToast('팝업이 차단되었습니다. 팝업을 허용해주세요.');
        return;
    }
    
    printWindow.document.write(printContent);
    printWindow.document.close();
    
    // 인쇄 대화상자 열기
    printWindow.onload = function() {
        printWindow.print();
    };
    
    showSuccessToast('인쇄 대화상자를 엽니다.');
}

/**
 * 인쇄용 HTML 생성
 */
function generatePrintContent(claim) {
    const statusInfo = getClaimStatusInfo(claim.status);
    
    return `
<!DOCTYPE html>
<html lang="ko">
<head>
    <meta charset="UTF-8">
    <title>홀인원 보상신청서 - ${claim.claim_number}</title>
    <style>
        @media print { .no-print { display: none !important; } }
        body { font-family: "Malgun Gothic", Arial, sans-serif; margin: 20px; line-height: 1.6; }
        .header { text-align: center; border-bottom: 2px solid #2c5aa0; padding-bottom: 20px; margin-bottom: 30px; }
        .header h1 { color: #2c5aa0; margin: 0; }
        .info-section { margin-bottom: 20px; border: 1px solid #ddd; border-radius: 8px; }
        .section-title { background: #f8f9fa; padding: 10px 15px; margin: 0; font-weight: bold; color: #2c5aa0; }
        .info-table { width: 100%; border-collapse: collapse; }
        .info-table th, .info-table td { padding: 8px; border: 1px solid #ddd; }
        .info-table th { background: #f8f9fa; font-weight: bold; width: 100px; }
        .status-badge { padding: 4px 12px; border-radius: 12px; font-size: 12px; font-weight: bold; }
        .print-button { background: #2c5aa0; color: white; border: none; padding: 10px 20px; border-radius: 4px; cursor: pointer; margin: 10px 0; }
    </style>
</head>
<body>
    <div class="no-print">
        <button class="print-button" onclick="window.print()">🖨️ 인쇄하기</button>
        <button class="print-button" onclick="window.close()" style="background: #6c757d;">❌ 닫기</button>
    </div>
    
    <div class="header">
        <h1>홀인원 보상신청서</h1>
        <div>신청번호: <strong>${claim.claim_number}</strong></div>
        <div><span class="status-badge ${statusInfo.class}">${statusInfo.text}</span></div>
    </div>
    
    <div class="info-section">
        <h3 class="section-title">🏌️ 신청자 정보</h3>
        <table class="info-table">
            <tr><th>성명</th><td>${claim.customer_name || '-'}</td><th>연락처</th><td>${claim.customer_phone || '-'}</td></tr>
            <tr><th>이메일</th><td>${claim.customer_email || '-'}</td><th>신청일</th><td>${formatDate(claim.created_at)}</td></tr>
            <tr><th>주소</th><td colspan="3">${claim.customer_address || '-'}</td></tr>
        </table>
    </div>
    
    <div class="info-section">
        <h3 class="section-title">⛳ 홀인원 정보</h3>
        <table class="info-table">
            <tr><th>골프장</th><td>${claim.golf_course || '-'}</td><th>경기일</th><td>${formatDate(claim.play_date)}</td></tr>
            <tr><th>홀번호</th><td><strong>${claim.hole_number}번홀</strong></td><th>거리</th><td><strong>${claim.yardage}야드</strong></td></tr>
            <tr><th>사용클럽</th><td>${claim.used_club || '-'}</td><th>캐디명</th><td>${claim.caddy_name || '-'}</td></tr>
            <tr><th>설명</th><td colspan="3">${claim.description || '-'}</td></tr>
        </table>
    </div>
    
    <div style="margin-top: 40px; text-align: center; font-size: 12px; color: #666;">
        <p>출력일: ${new Date().toLocaleString('ko-KR')}</p>
        <p>PCI Korea 홀인원보험 관리시스템</p>
    </div>
</body>
</html>`;
}
/**
 * 상세 정보 다시 로드
 */
function reloadClaimDetail() {
    if (currentClaimId) {
        loadClaimDetail(currentClaimId);
    }
}

/**
 * 로딩 상태 표시
 */
function showClaimDetailLoading() {
    document.getElementById('claimDetailLoading').style.display = 'block';
    document.getElementById('claimDetailContent').style.display = 'none';
    document.getElementById('claimDetailError').style.display = 'none';
}

/**
 * 에러 상태 표시
 */
function showClaimDetailError(message) {
    document.getElementById('claimDetailLoading').style.display = 'none';
    document.getElementById('claimDetailContent').style.display = 'none';
    document.getElementById('claimDetailError').style.display = 'block';
    document.getElementById('claimDetailErrorMessage').textContent = message;
}

/**
 * 성공 토스트 표시
 */
function showSuccessToast(message) {
    showToast(message, 'success');
}

/**
 * 에러 토스트 표시
 */
function showErrorToast(message) {
    showToast(message, 'error');
}

/**
 * 토스트 메시지 표시 (개선된 버전)
 */
function showToast(message, type = 'success') {
    const bgClass = type === 'success' ? 'bg-success' : 'bg-danger';
    const icon = type === 'success' ? 'bi-check-circle' : 'bi-exclamation-triangle';
    
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
 * 유틸리티 함수들
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

function escapeHtml(text) {
    if (!text) return '';
    
    const div = document.createElement('div');
    div.textContent = text;
    return div.innerHTML;
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
 * 초기화 함수
 */
document.addEventListener('DOMContentLoaded', function() {
    console.log('claim-detail.js (개선 버전) 로드 완료');
    
    // 모달 이벤트 리스너
    const claimDetailModal = document.getElementById('claimDetailModal');
    if (claimDetailModal) {
        claimDetailModal.addEventListener('hidden.bs.modal', function() {
            currentClaimDetail = null;
            currentClaimId = null;
        });
    }
});

// 전역에 함수 노출
window.viewClaimDetail = viewClaimDetail;
window.loadClaimDetail = loadClaimDetail;
window.reloadClaimDetail = reloadClaimDetail;
window.enlargeImage = enlargeImage;
window.downloadFile = downloadFile;
window.approveClaimFromDetail = approveClaimFromDetail;
window.rejectClaimFromDetail = rejectClaimFromDetail;
window.markClaimCompleted = markClaimCompleted;
window.changeClaimStatus = changeClaimStatus;
window.printClaimDetail = printClaimDetail;
window.downloadClaimPDF = downloadClaimPDF;