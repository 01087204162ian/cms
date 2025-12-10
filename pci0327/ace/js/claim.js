// claim.js - 향상된 단계별 보상 신청 스크립트 (수정된 버전)

// 전역 변수
let currentStep = 1;
let verifiedSignupData = null;
let uploadedFiles = {
    photo: null,
    certificate: null,
    additional: []
};
let isSubmitting = false;

// DOM 로드 완료 시 초기화
document.addEventListener('DOMContentLoaded', function() {
    initializeEnhancedClaim();
});

// 향상된 보상 신청 초기화
function initializeEnhancedClaim() {
    setupStepNavigation();
    setupStep1Events();
    setupStep2Events();
    setupStep3Events();
    setupPhoneFormatting();
}

// 단계 네비게이션 설정
function setupStepNavigation() {
    updateStepIndicator(1);
}

// 1단계 이벤트 설정
function setupStep1Events() {
    const verifyButton = document.getElementById('verify-signup-button');
    const verifyPhone = document.getElementById('verify-phone');
    
    if (verifyButton) {
        verifyButton.addEventListener('click', function() {
            handleSignupVerification();
        });
    }
    
    if (verifyPhone) {
        verifyPhone.addEventListener('keypress', function(e) {
            if (e.key === 'Enter') {
                handleSignupVerification();
            }
        });
    }
    
    // 다시 시도 버튼 (동적으로 생성되는 버튼들을 위한 이벤트 위임)
    document.addEventListener('click', function(e) {
        if (e.target.id === 'try-again-button-enhanced') {
            resetStep1();
            document.getElementById('verify-phone').focus();
        }
        
        if (e.target.id === 'go-to-signup-enhanced') {
            if (typeof showPage === 'function' && typeof setActiveTab === 'function') {
                const signupPage = document.getElementById('signup-page');
                const navSignup = document.getElementById('nav-signup');
                if (signupPage && navSignup) {
                    showPage(signupPage);
                    setActiveTab(navSignup);
                }
            }
        }
    });
}

// 2단계 이벤트 설정
function setupStep2Events() {
    const backToStep1 = document.getElementById('back-to-step-1');
    const goToStep3 = document.getElementById('go-to-step-3');
    
    if (backToStep1) {
        backToStep1.addEventListener('click', function() {
            showStep(1);
        });
    }
    
    if (goToStep3) {
        goToStep3.addEventListener('click', function() {
            showStep(3);
        });
    }
}

// 3단계 이벤트 설정
function setupStep3Events() {
    const backToStep2 = document.getElementById('back-to-step-2');
    const submitButton = document.getElementById('submit-claim-button');
    
    if (backToStep2) {
        backToStep2.addEventListener('click', function() {
            showStep(2);
        });
    }
    
    if (submitButton) {
        submitButton.addEventListener('click', function() {
            handleFinalSubmission();
        });
    }
    
    setupFileUpload();
    setupAdditionalFiles();
    setupFormValidation();
}

// 가입 내역 확인 처리
function handleSignupVerification() {
    const phoneInput = document.getElementById('verify-phone');
    const phone = phoneInput.value.trim();
    
    if (!phone) {
        showFieldError(phoneInput, '휴대폰번호를 입력해주세요.');
        return;
    }
    
    const phonePattern = /^010-\d{4}-\d{4}$/;
    if (!phonePattern.test(phone)) {
        showFieldError(phoneInput, '올바른 휴대폰번호 형식으로 입력해주세요. (010-0000-0000)');
        return;
    }
    
    hideVerificationResult();
    showVerificationLoading(true);
    
    const cleanPhone = phone.replace(/[^\d]/g, '');
    
    fetch('api/customer/getSignupHistory.php', {
        method: 'POST',
        headers: {
            'Content-Type': 'application/json',
        },
        body: JSON.stringify({
            phone: cleanPhone
        })
    })
    .then(response => {
        if (!response.ok) {
            throw new Error(`HTTP error! status: ${response.status}`);
        }
        return response.json();
    })
    .then(data => {
        showVerificationLoading(false);
        
        if (data.success && data.data && data.data.length > 0) {
            // 🆕 수정: 여러 가입 내역 모두 처리
            showVerificationSuccessEnhanced(data.data); // 전체 배열 전달
        } else {
            showVerificationFailedEnhanced();
        }
    })
    .catch(error => {
        showVerificationLoading(false);
        console.error('가입 내역 조회 중 오류 발생:', error);
        showVerificationErrorEnhanced('서버와의 통신 중 문제가 발생했습니다. 잠시 후 다시 시도해주세요.');
    });
}

// 개선된 가입 확인 성공 표시
function showVerificationSuccessEnhanced(signupDataArray) {
    const resultContainer = document.getElementById('signup-verification-result');
    const successContent = document.getElementById('verification-success-content');
    
    // 단일 데이터인 경우 배열로 변환
    const dataArray = Array.isArray(signupDataArray) ? signupDataArray : [signupDataArray];
    
    let signupDetailsHtml = '';
    
    if (dataArray.length > 1) {
        // 여러 가입 내역이 있는 경우
        signupDetailsHtml = `
            <div class="multiple-signup-notice">
                <div class="notice-icon">ℹ️</div>
                <div class="notice-text">총 ${dataArray.length}개의 가입 내역이 확인되었습니다. 해당하는 가입 내역을 선택해주세요.</div>
            </div>
            <div class="signup-selection-container">
                ${dataArray.map((signup, index) => createSignupSelectionItem(signup, index)).join('')}
            </div>
        `;
    } else {
        // 단일 가입 내역인 경우
        const signup = dataArray[0];
        verifiedSignupData = signup;
        signupDetailsHtml = createSingleSignupDisplay(signup);
    }
    
    successContent.innerHTML = `
        <div id="signup-details" class="signup-details">
            ${signupDetailsHtml}
        </div>
        
        ${dataArray.length === 1 ? `
        <button class="next-step-button" id="go-to-step-2">
            다음 단계로 진행 →
        </button>
        ` : ''}
    `;
    
    resultContainer.style.display = 'block';
    successContent.style.display = 'block';
    
    // 이벤트 리스너 등록
    if (dataArray.length === 1) {
        // 단일 가입 내역인 경우 다음 단계 버튼
        const goToStep2 = document.getElementById('go-to-step-2');
        if (goToStep2) {
            goToStep2.addEventListener('click', function() {
                showStep(2);
            });
        }
    } else {
        // 여러 가입 내역인 경우 선택 버튼들
        dataArray.forEach((signup, index) => {
            const selectButton = document.getElementById(`select-signup-${index}`);
            if (selectButton) {
                selectButton.addEventListener('click', function() {
                    selectSignupData(signup, index);
                });
            }
        });
    }
    
    playNotificationSound('success');
    showCelebrationMessage();
    
    // 첫 번째 가입 내역으로 초기 정보 설정 (여러 개인 경우)
    if (dataArray.length === 1) {
        populateConfirmedInfo(dataArray[0]);
    }
}
// 가입 내역 선택 아이템 HTML 생성
function createSignupSelectionItem(signup, index) {
    const isActive = signup.status !== 'CANCELLED';
    const statusText = isActive ? '활성' : '비활성';
    const statusClass = isActive ? 'status-active' : 'status-inactive';
    
    return `
        <div class="signup-selection-item ${isActive ? 'selectable' : 'disabled'}" data-index="${index}">
            <div class="signup-header">
                <div class="signup-title">
                    <span class="signup-id">${signup.signupId || signup.id}</span>
                    <span class="signup-status ${statusClass}">${statusText}</span>
                </div>
                <div class="signup-date">${formatDateTime(signup.createdAt || signup.signupDate)}</div>
            </div>
            
            <div class="signup-details-grid">
                <div class="detail-row">
                    <span class="detail-label">가입자:</span>
                    <span class="detail-value">${signup.customerName || signup.name || 'N/A'}</span>
                </div>
                ${signup.golfCourseName || signup.golfCourse ? `
                <div class="detail-row">
                    <span class="detail-label">골프장:</span>
                    <span class="detail-value">${signup.golfCourseName || signup.golfCourse}</span>
                </div>
                ` : ''}
                ${signup.teeOffTime || signup.teeTime ? `
                <div class="detail-row">
                    <span class="detail-label">티오프:</span>
                    <span class="detail-value">${formatDateTime(signup.teeOffTime || signup.teeTime)}</span>
                </div>
                ` : ''}
            </div>
            
            ${isActive ? `
            <button class="select-signup-button" id="select-signup-${index}">
                이 가입 내역으로 신청하기
            </button>
            ` : `
            <div class="inactive-notice">
                ⚠️ 비활성 상태로 보상 신청이 불가능합니다.
            </div>
            `}
        </div>
    `;
}

// 가입 데이터 선택 처리
function selectSignupData(selectedSignup, index) {
    verifiedSignupData = selectedSignup;
    
    // 선택된 아이템 강조
    document.querySelectorAll('.signup-selection-item').forEach((item, idx) => {
        if (idx === index) {
            item.classList.add('selected');
        } else {
            item.classList.remove('selected');
        }
    });
    
    // 다음 단계 버튼 추가
    const signupDetails = document.getElementById('signup-details');
    const existingButton = document.getElementById('go-to-step-2');
    
    if (!existingButton) {
        const buttonHtml = `
            <div class="selected-signup-actions">
                <div class="selection-confirmation">
                    ✅ <strong>${selectedSignup.signupId || selectedSignup.id}</strong> 가입 내역이 선택되었습니다.
                </div>
                <button class="next-step-button" id="go-to-step-2">
                    다음 단계로 진행 →
                </button>
            </div>
        `;
        signupDetails.insertAdjacentHTML('beforeend', buttonHtml);
        
        // 버튼 이벤트 등록
        const goToStep2 = document.getElementById('go-to-step-2');
        if (goToStep2) {
            goToStep2.addEventListener('click', function() {
                showStep(2);
            });
        }
    } else {
        // 이미 버튼이 있으면 확인 메시지만 업데이트
        const confirmationDiv = document.querySelector('.selection-confirmation');
        if (confirmationDiv) {
            confirmationDiv.innerHTML = `✅ <strong>${selectedSignup.signupId || selectedSignup.id}</strong> 가입 내역이 선택되었습니다.`;
        }
    }
    
    // 선택된 정보로 확인 정보 업데이트
    populateConfirmedInfo(selectedSignup);
    
    // 스크롤을 다음 단계 버튼으로 이동
    setTimeout(() => {
        const button = document.getElementById('go-to-step-2');
        if (button) {
            button.scrollIntoView({ behavior: 'smooth', block: 'center' });
        }
    }, 300);
}
// 단일 가입 내역 표시 HTML 생성
function createSingleSignupDisplay(signup) {
    return `
        <div class="signup-detail-item">
            <span class="detail-label">가입자명:</span>
            <span class="detail-value">${signup.customerName || signup.name || 'N/A'}</span>
        </div>
        <div class="signup-detail-item">
            <span class="detail-label">가입일시:</span>
            <span class="detail-value">${formatDateTime(signup.createdAt || signup.signupDate)}</span>
        </div>
        <div class="signup-detail-item">
            <span class="detail-label">가입번호:</span>
            <span class="detail-value">${signup.signupId || signup.id || 'N/A'}</span>
        </div>
        ${signup.golfCourseName || signup.golfCourse ? `
        <div class="signup-detail-item">
            <span class="detail-label">골프장:</span>
            <span class="detail-value">${signup.golfCourseName || signup.golfCourse}</span>
        </div>
        ` : ''}
        ${signup.teeOffTime || signup.teeTime ? `
        <div class="signup-detail-item">
            <span class="detail-label">티오프:</span>
            <span class="detail-value">${formatDateTime(signup.teeOffTime || signup.teeTime)}</span>
        </div>
        ` : ''}
    `;
}

// 개선된 가입 확인 실패 표시
function showVerificationFailedEnhanced() {
    const resultContainer = document.getElementById('signup-verification-result');
    const failedContent = document.getElementById('verification-failed-content');
    
    failedContent.innerHTML = `
        <div class="verification-result-header">
            
            <div class="result-content">
                <div class="result-title">❌ 가입 내역을 찾을 수 없습니다</div>
                <div class="result-message">입력하신 휴대폰번호로 가입된 홀인원 보험이 없습니다.</div>
            </div>
        </div>
        
        <div class="failed-details">
            <div class="failed-reasons">
                <h4>🤔 확인해주세요</h4>
                <ul class="reason-list">
                    <li>입력하신 휴대폰번호가 정확한지 확인해주세요</li>
                    <li>가입 시 사용한 번호와 동일한지 확인해주세요</li>
                    <li>최근에 번호를 변경하셨다면 이전 번호로 시도해보세요</li>
                </ul>
            </div>
        </div>
        
        
        
        <div class="contact-info">
            <div class="contact-notice">
                <div class="contact-icon">📞</div>
                <div class="contact-text">
                    문의사항이 있으시면 고객센터(1533-5013/1588-0100)로 연락주세요.
                </div>
            </div>
        </div>
    `;
    
    resultContainer.style.display = 'block';
    failedContent.style.display = 'block';
    
    // 애니메이션 효과
    setTimeout(() => {
        failedContent.classList.add('show');
    }, 100);
}

// 가입 확인 오류 표시 (개선된 버전)
function showVerificationErrorEnhanced(message) {
    if (typeof showPopup === 'function') {
        showPopup('확인 오류', message);
    } else {
        alert('확인 오류: ' + message);
    }
}

// 가입 확인 로딩 표시
function showVerificationLoading(isLoading) {
    const verifyButton = document.getElementById('verify-signup-button');
    
    if (isLoading) {
        verifyButton.disabled = true;
        verifyButton.innerHTML = `
            <span class="button-text">확인 중...</span>
            <span class="button-icon">⏳</span>
        `;
    } else {
        verifyButton.disabled = false;
        verifyButton.innerHTML = `
            <span class="button-text">가입 내역 확인</span>
            <span class="button-icon">🔍</span>
        `;
    }
}

// 확인 결과 숨기기
function hideVerificationResult() {
    const resultContainer = document.getElementById('signup-verification-result');
    const successContent = document.getElementById('verification-success-content');
    const failedContent = document.getElementById('verification-failed-content');
    
    if (resultContainer) resultContainer.style.display = 'none';
    if (successContent) {
        successContent.style.display = 'none';
        successContent.classList.remove('show');
    }
    if (failedContent) {
        failedContent.style.display = 'none';
        failedContent.classList.remove('show');
    }
}

// 1단계 초기화
function resetStep1() {
    const phoneInput = document.getElementById('verify-phone');
    if (phoneInput) {
        phoneInput.value = '';
        clearFieldError(phoneInput);
    }
    
    hideVerificationResult();
    verifiedSignupData = null;
}

// 단계 표시
function showStep(stepNumber) {
    const currentStepContent = document.getElementById(`claim-step-${currentStep}`);
    if (currentStepContent) {
        currentStepContent.style.display = 'none';
        currentStepContent.classList.remove('active');
    }
    
    const newStepContent = document.getElementById(`claim-step-${stepNumber}`);
    if (newStepContent) {
        newStepContent.style.display = 'block';
        newStepContent.classList.add('active');
    }
    
    currentStep = stepNumber;
    updateStepIndicator(stepNumber);
    
    if (stepNumber === 3 && verifiedSignupData) {
        populateStep3WithVerifiedData();
    }
}

// 단계 표시기 업데이트
function updateStepIndicator(activeStep) {
    for (let i = 1; i <= 3; i++) {
        const stepItem = document.getElementById(`step-${i}`);
        if (stepItem) {
            if (i <= activeStep) {
                stepItem.classList.add('active');
                if (i < activeStep) {
                    stepItem.classList.add('completed');
                }
            } else {
                stepItem.classList.remove('active', 'completed');
            }
        }
    }
}

// 3단계에 확인된 정보 표시
function populateConfirmedInfo(signupData) {
    const confirmedInfo = document.getElementById('confirmed-signup-info');
    if (confirmedInfo) {
        confirmedInfo.innerHTML = `
            <div class="info-header">
                <h3>✅ 확인된 가입 정보</h3>
            </div>
            <div class="info-grid">
                <div class="info-item">
                    <span class="info-label">가입자명:</span>
                    <span class="info-value">${signupData.customerName || signupData.name || 'N/A'}</span>
                </div>
                <div class="info-item">
                    <span class="info-label">연락처:</span>
                    <span class="info-value">${formatPhoneNumber(signupData.phone || signupData.phoneNumber)}</span>
                </div>
                <div class="info-item">
                    <span class="info-label">가입번호:</span>
                    <span class="info-value">${signupData.signupId || signupData.id || 'N/A'}</span>
                </div>
                <div class="info-item">
                    <span class="info-label">가입일시:</span>
                    <span class="info-value">${formatDateTime(signupData.createdAt || signupData.signupDate)}</span>
                </div>
            </div>
        `;
    }
}



// 3단계 데이터 설정 (티업 시간 기반 날짜 자동 설정 추가)
function populateStep3WithVerifiedData() {
    if (!verifiedSignupData) return;
    
    // 골프장명이 있으면 미리 입력
    const golfCourseInput = document.getElementById('claim-golf-course');
    if (golfCourseInput && (verifiedSignupData.golfCourseName || verifiedSignupData.golfCourse)) {
        golfCourseInput.value = verifiedSignupData.golfCourseName || verifiedSignupData.golfCourse;
    }
    
    // 티업 시간이 있으면 홀인원 발생 날짜로 미리 설정
    const claimDateInput = document.getElementById('claim-date');
    if (claimDateInput && (verifiedSignupData.teeOffTime || verifiedSignupData.teeTime)) {
        const teeOffDateTime = verifiedSignupData.teeOffTime || verifiedSignupData.teeTime;
        const teeOffDate = new Date(teeOffDateTime);
        
        // 유효한 날짜인지 확인
        if (!isNaN(teeOffDate.getTime())) {
            // 현재 날짜와 비교해서 과거 날짜인 경우에만 설정
            const today = new Date();
            today.setHours(0, 0, 0, 0); // 시간을 00:00:00으로 설정
            
            if (teeOffDate <= today) {
                // YYYY-MM-DD 형식으로 변환
                const year = teeOffDate.getFullYear();
                const month = String(teeOffDate.getMonth() + 1).padStart(2, '0');
                const day = String(teeOffDate.getDate()).padStart(2, '0');
                const dateString = `${year}-${month}-${day}`;
                
                claimDateInput.value = dateString;
                
                // 날짜 설정 후 유효성 검사
                validateField(claimDateInput);
            }
        }
    }
}

// 파일 업로드 설정
function setupFileUpload() {
    const uploadPhoto = document.getElementById('upload-photo');
    const uploadCertificate = document.getElementById('upload-certificate');
    const photoInput = document.getElementById('photo-input');
    const certificateInput = document.getElementById('certificate-input');

    if (uploadPhoto && photoInput) {
        uploadPhoto.addEventListener('click', function() {
            photoInput.click();
        });

        photoInput.addEventListener('change', function(e) {
            handleFileUpload(e, 'photo', uploadPhoto);
        });
    }

    if (uploadCertificate && certificateInput) {
        uploadCertificate.addEventListener('click', function() {
            certificateInput.click();
        });

        certificateInput.addEventListener('change', function(e) {
            handleFileUpload(e, 'certificate', uploadCertificate);
        });
    }
}

// 추가 파일 업로드 설정
function setupAdditionalFiles() {
    const addFileButton = document.getElementById('add-additional-file');
    
    if (addFileButton) {
        addFileButton.addEventListener('click', function() {
            addAdditionalFileUpload();
        });
    }
}

// 추가 파일 업로드 추가
function addAdditionalFileUpload() {
    const container = document.getElementById('additional-files-container');
    if (!container) return;
    
    const fileIndex = uploadedFiles.additional.length;
    const fileUploadHtml = `
        <div class="additional-file-item" data-index="${fileIndex}">
            <div class="file-upload-box additional" id="upload-additional-${fileIndex}">
                <svg width="20" height="20" viewBox="0 0 24 24" fill="none">
                    <path d="M21 15V19C21 19.5304 20.7893 20.0391 20.4142 20.4142C20.0391 20.7893 19.5304 21 19 21H5C4.46957 21 3.96086 20.7893 3.58579 20.4142C3.21071 20.0391 3 19.5304 3 19V15" stroke="#999" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/>
                    <path d="M17 8L12 3L7 8" stroke="#999" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/>
                    <path d="M12 3V15" stroke="#999" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/>
                </svg>
                <div class="file-upload-text">추가 증빙 자료</div>
                <div class="file-upload-hint">클릭하여 업로드</div>
            </div>
            <button type="button" class="remove-file-button" onclick="removeAdditionalFile(${fileIndex})">×</button>
            <input type="file" id="additional-input-${fileIndex}" style="display:none;" accept="image/*">
        </div>
    `;
    
    container.insertAdjacentHTML('beforeend', fileUploadHtml);
    
    const uploadBox = document.getElementById(`upload-additional-${fileIndex}`);
    const fileInput = document.getElementById(`additional-input-${fileIndex}`);
    
    uploadBox.addEventListener('click', function() {
        fileInput.click();
    });
    
    fileInput.addEventListener('change', function(e) {
        handleAdditionalFileUpload(e, fileIndex, uploadBox);
    });
    
    uploadedFiles.additional.push(null);
}

// 추가 파일 제거
function removeAdditionalFile(index) {
    const fileItem = document.querySelector(`.additional-file-item[data-index="${index}"]`);
    if (fileItem) {
        fileItem.remove();
        uploadedFiles.additional[index] = null;
    }
}

// 파일 업로드 처리
function handleFileUpload(event, type, uploadBox) {
    const file = event.target.files[0];
    
    if (!file) return;

    const validationResult = validateFile(file, type);
    if (!validationResult.isValid) {
        if (typeof showPopup === 'function') {
            showPopup('파일 오류', validationResult.message);
        } else {
            alert('파일 오류: ' + validationResult.message);
        }
        event.target.value = '';
        return;
    }

    uploadedFiles[type] = file;
    updateUploadBox(uploadBox, file, type);
    createFilePreview(file, uploadBox, type);
}

// 추가 파일 업로드 처리
function handleAdditionalFileUpload(event, index, uploadBox) {
    const file = event.target.files[0];
    
    if (!file) return;

    const validationResult = validateFile(file, 'additional');
    if (!validationResult.isValid) {
        if (typeof showPopup === 'function') {
            showPopup('파일 오류', validationResult.message);
        } else {
            alert('파일 오류: ' + validationResult.message);
        }
        event.target.value = '';
        return;
    }

    uploadedFiles.additional[index] = file;
    updateUploadBox(uploadBox, file, 'additional');
    createFilePreview(file, uploadBox, 'additional');
}

// 파일 유효성 검사
function validateFile(file, type) {
    const maxSize = 10 * 1024 * 1024; // 10MB
    const allowedTypes = ['image/jpeg', 'image/jpg', 'image/png'];

    if (file.size > maxSize) {
        return {
            isValid: false,
            message: '파일 크기는 10MB 이하로 업로드해주세요.'
        };
    }

    if (!allowedTypes.includes(file.type)) {
        return {
            isValid: false,
            message: 'JPG, PNG 형식의 이미지만 업로드 가능합니다.'
        };
    }

    return { isValid: true };
}

// 업로드 박스 UI 업데이트
function updateUploadBox(uploadBox, file, type) {
    const textElement = uploadBox.querySelector('.file-upload-text');
    const fileName = file.name.length > 20 ? file.name.substring(0, 20) + '...' : file.name;
    
    textElement.textContent = fileName;
    uploadBox.style.borderColor = '#4CAF50';
    uploadBox.style.backgroundColor = '#f0f8f0';
    uploadBox.classList.add('uploaded');
    
    const existingIcon = uploadBox.querySelector('.upload-success-icon');
    if (!existingIcon) {
        const successIcon = document.createElement('div');
        successIcon.className = 'upload-success-icon';
        successIcon.innerHTML = '✓';
        successIcon.style.cssText = `
            position: absolute;
            top: 8px;
            right: 8px;
            background: #4CAF50;
            color: white;
            width: 20px;
            height: 20px;
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 12px;
            font-weight: bold;
            box-shadow: 0 2px 4px rgba(0, 0, 0, 0.2);
        `;
        uploadBox.appendChild(successIcon);
    }
}

// 파일 미리보기 생성
function createFilePreview(file, uploadBox, type) {
    const reader = new FileReader();
    
    reader.onload = function(e) {
        const existingPreview = uploadBox.querySelector('.file-preview');
        if (existingPreview) {
            existingPreview.remove();
        }

        const preview = document.createElement('div');
        preview.className = 'file-preview';
        preview.innerHTML = `
            <img src="${e.target.result}" alt="미리보기" style="
                position: absolute;
                bottom: 8px;
                right: 8px;
                width: 30px;
                height: 30px;
                object-fit: cover;
                border-radius: 4px;
                border: 1px solid #ddd;
                background: white;
                box-shadow: 0 1px 3px rgba(0, 0, 0, 0.2);
            ">
        `;
        uploadBox.style.position = 'relative';
        uploadBox.appendChild(preview);
    };
    
    reader.readAsDataURL(file);
}

// 폼 유효성 검사 설정
function setupFormValidation() {
    const formFields = ['claim-date', 'claim-golf-course', 'claim-hole',
						 'bank-name', 'account-number', 'account-holder'];

    formFields.forEach(fieldId => {
        const field = document.getElementById(fieldId);
        if (field) {
            field.addEventListener('blur', function() {
                validateField(this);
            });
            
            field.addEventListener('input', function() {
                clearFieldError(this);
            });
        }
    });

    const holeField = document.getElementById('claim-hole');
    if (holeField) {
        holeField.addEventListener('input', function() {
            formatHoleNumber(this);
        });
    }

    const dateField = document.getElementById('claim-date');
    if (dateField) {
        const today = new Date();
        const maxDate = today.toISOString().split('T')[0];
        const minDate = new Date(today.setMonth(today.getMonth() - 6)).toISOString().split('T')[0];
        
        dateField.max = maxDate;
        dateField.min = minDate;
    }
}

// 개별 필드 유효성 검사
function validateField(field) {
    const value = field.value.trim();
    let errorMessage = '';

    switch (field.id) {
        case 'claim-date':
            if (!value) {
                errorMessage = '홀인원 발생 날짜를 선택해주세요.';
            } else {
                const selectedDate = new Date(value);
                const today = new Date();
                const sixMonthsAgo = new Date(today.setMonth(today.getMonth() - 6));
                
                if (selectedDate > new Date()) {
                    errorMessage = '미래 날짜는 선택할 수 없습니다.';
                } else if (selectedDate < sixMonthsAgo) {
                    errorMessage = '6개월 이전의 날짜는 선택할 수 없습니다.';
                }
            }
            break;

        case 'claim-golf-course':
            if (!value) {
                errorMessage = '골프장명을 입력해주세요.';
            } else if (value.length < 2) {
                errorMessage = '골프장명은 2글자 이상 입력해주세요.';
            }
            break;

        case 'claim-hole':
            const holePattern = /^\d+번?홀?$/;
            if (!value) {
                errorMessage = '홀인원 홀 번호를 입력해주세요.';
            } else if (!holePattern.test(value)) {
                errorMessage = '올바른 홀 번호를 입력해주세요. (예: 3번홀, 7홀)';
            }
            break;
		 // validateField 함수의 switch문에 추가
		case 'bank-name':
			if (!value) {
				errorMessage = '은행명을 선택해주세요.';
			}
			break;

		case 'account-number':
			if (!value) {
				errorMessage = '계좌번호를 입력해주세요.';
			} else if (value.length < 8 || value.length > 20) {
				errorMessage = '계좌번호는 8~20자리로 입력해주세요.';
			} else if (!/^\d+$/.test(value)) {
				errorMessage = '계좌번호는 숫자만 입력 가능합니다.';
			}
			break;

		case 'account-holder':
			if (!value) {
				errorMessage = '예금주명을 입력해주세요.';
			} else if (value.length < 2) {
				errorMessage = '예금주명은 2글자 이상 입력해주세요.';
			}
			// 정규식 검사 제거 - 모든 문자 허용
			break;
	}

    if (errorMessage) {
        showFieldError(field, errorMessage);
        return false;
    } else {
        clearFieldError(field);
        return true;
    }
}

// 필드 오류 표시
function showFieldError(field, message) {
    clearFieldError(field);
    
    field.style.borderColor = '#f44336';
    field.classList.add('error');
    
    const errorDiv = document.createElement('div');
    errorDiv.className = 'field-error';
    errorDiv.style.cssText = `
        color: #f44336;
        font-size: 12px;
        margin-top: 5px;
        margin-bottom: 10px;
        display: flex;
        align-items: center;
        gap: 4px;
    `;
    errorDiv.innerHTML = `⚠ ${message}`;
    
    field.parentNode.insertBefore(errorDiv, field.nextSibling);
}

// 필드 오류 제거
function clearFieldError(field) {
    field.style.borderColor = '#ddd';
    field.classList.remove('error');
    
    const errorDiv = field.parentNode.querySelector('.field-error');
    if (errorDiv) {
        errorDiv.remove();
    }
}

// 홀 번호 포맷팅
function formatHoleNumber(field) {
    let value = field.value.replace(/[^\d]/g, '');
    if (value) {
        if (value > 18) {
            value = '18';
        }
        field.value = value + '번홀';
    }
}

// 휴대폰 번호 포맷팅 설정
function setupPhoneFormatting() {
    const phoneInput = document.getElementById('verify-phone');
    
    if (phoneInput) {
        phoneInput.addEventListener('input', function(e) {
            let value = this.value.replace(/[^\d]/g, '');
            
            if (value.length <= 3) {
                this.value = value;
            } else if (value.length <= 7) {
                this.value = value.slice(0, 3) + '-' + value.slice(3);
            } else {
                this.value = value.slice(0, 3) + '-' + value.slice(3, 7) + '-' + value.slice(7, 11);
            }
        });
    }
}

// 최종 제출 처리
function handleFinalSubmission() {
    if (isSubmitting) {
        return;
    }

    const formData = validateFinalForm();
    if (!formData) {
        return;
    }

    if (!validateFileUploads()) {
        return;
    }

    showFinalConfirmation(formData);
}

// 최종 폼 유효성 검사
function validateFinalForm() {
    const formElements = {
        date: document.getElementById('claim-date'),
        golfCourse: document.getElementById('claim-golf-course'),
        hole: document.getElementById('claim-hole'),
        distance: document.getElementById('claim-distance'),
        witnesses: document.getElementById('witnesses'),
        description: document.getElementById('description'),
		bankName: document.getElementById('bank-name'),        // 🆕 추가
		accountNumber: document.getElementById('account-number'), // 🆕 추가
		accountHolder: document.getElementById('account-holder'), // 🆕 추가
        termsCheckbox: document.getElementById('claim-terms-checkbox')
    };

    let isValid = true;
    const formData = {};

    // 필수 필드 배열에 계좌 정보 추가
	['date', 'golfCourse', 'hole', 'bankName', 'accountNumber', 'accountHolder'].forEach(key => {
		const element = formElements[key];
		if (element) {
			if (!validateField(element)) {
				isValid = false;
			} else {
				formData[key] = element.value.trim();
			}
		}
	});

    ['distance', 'witnesses', 'description'].forEach(key => {
        const element = formElements[key];
        if (element && element.value.trim()) {
            formData[key] = element.value.trim();
        }
    });

    if (!formElements.termsCheckbox.checked) {
        if (typeof showPopup === 'function') {
            showPopup('약관 동의', '개인정보 활용 동의가 필요합니다.');
        }
        isValid = false;
    } else {
        formData.termsAgreed = true;
    }

    if (verifiedSignupData) {
        formData.signupData = verifiedSignupData;
    }

    return isValid ? formData : null;
}

// 파일 업로드 유효성 검사
function validateFileUploads() {
    if (!uploadedFiles.photo) {
        if (typeof showPopup === 'function') {
            showPopup('파일 업로드', '홀인원 증명 사진을 업로드해주세요.');
        }
        return false;
    }

    if (!uploadedFiles.certificate) {
        if (typeof showPopup === 'function') {
            showPopup('파일 업로드', '스코어카드 또는 확인서를 업로드해주세요.');
        }
        return false;
    }

    return true;
}

// 최종 확인 모달
function showFinalConfirmation(formData) {
    const confirmationHtml = `
        <div class="final-confirmation">
            <h3>보상 신청 내용을 확인해주세요</h3>
            
            <div class="confirmation-section">
                <h4>가입자 정보</h4>
                <div class="confirmation-item">
                    <span class="label">이름:</span>
                    <span class="value">${verifiedSignupData.customerName || verifiedSignupData.name}</span>
                </div>
                <div class="confirmation-item">
                    <span class="label">연락처:</span>
                    <span class="value">${formatPhoneNumber(verifiedSignupData.phone || verifiedSignupData.phoneNumber)}</span>
                </div>
            </div>
            
            <div class="confirmation-section">
                <h4>홀인원 정보</h4>
                <div class="confirmation-item">
                    <span class="label">발생일:</span>
                    <span class="value">${formData.date}</span>
                </div>
                <div class="confirmation-item">
                    <span class="label">골프장:</span>
                    <span class="value">${formData.golfCourse}</span>
                </div>
                <div class="confirmation-item">
                    <span class="label">홀 번호:</span>
                    <span class="value">${formData.hole}</span>
                </div>
                ${formData.distance ? `
                <div class="confirmation-item">
                    <span class="label">홀 거리:</span>
                    <span class="value">${formData.distance}</span>
                </div>
                ` : ''}
            </div>
				
			<div class="confirmation-section">
				<h4>입금 계좌 정보</h4>
				<div class="confirmation-item">
					<span class="label">은행명:</span>
					<span class="value">${formData.bankName}</span>
				</div>
				<div class="confirmation-item">
					<span class="label">계좌번호:</span>
					<span class="value">${formData.accountNumber}</span>
				</div>
				<div class="confirmation-item">
					<span class="label">예금주:</span>
					<span class="value">${formData.accountHolder}</span>
				</div>
			</div>
            
            <div class="confirmation-section">
                <h4>업로드된 파일</h4>
                <div class="confirmation-item">
                    <span class="label">홀인원 사진:</span>
                    <span class="value">✓ ${uploadedFiles.photo.name}</span>
                </div>
                <div class="confirmation-item">
                    <span class="label">스코어카드:</span>
                    <span class="value">✓ ${uploadedFiles.certificate.name}</span>
                </div>
                ${uploadedFiles.additional.filter(f => f).length > 0 ? `
                <div class="confirmation-item">
                    <span class="label">추가 파일:</span>
                    <span class="value">✓ ${uploadedFiles.additional.filter(f => f).length}개 파일</span>
                </div>
                ` : ''}
            </div>
        </div>
    `;

    if (typeof showConfirmDialog === 'function') {
        showConfirmDialog(
            '보상 신청 확인',
            confirmationHtml,
            function() {
                proceedWithFinalSubmission(formData);
            }
        );
    } else {
        if (confirm('입력하신 내용으로 보상 신청을 진행하시겠습니까?')) {
            proceedWithFinalSubmission(formData);
        }
    }
}

// 최종 제출 진행
function proceedWithFinalSubmission(formData) {
    isSubmitting = true;
    
    const submitButton = document.getElementById('submit-claim-button');
    const originalButtonText = submitButton.innerHTML;
    submitButton.disabled = true;
    submitButton.innerHTML = `
        <span class="button-text">신청 중...</span>
        <span class="button-icon">⏳</span>
    `;

    const submitData = new FormData();
    
    // 가입자 정보 (signupData에서 추출)
    if (formData.signupData) {
        submitData.append('signupId', formData.signupData.signupId || formData.signupData.id);
        submitData.append('customerName', formData.signupData.customerName || formData.signupData.name);
        submitData.append('customerPhone', formData.signupData.phone || formData.signupData.phoneNumber);
    }
    
    // 홀인원 정보 - PHP 필드명에 맞춰 매핑
    if (formData.date) {
        submitData.append('playDate', formData.date);  // date → playDate
    }
    if (formData.golfCourse) {
        submitData.append('golfCourse', formData.golfCourse);
    }
    if (formData.hole) {
        submitData.append('holeNumber', formData.hole);  // hole → holeNumber
    }
    if (formData.distance) {
        submitData.append('yardage', formData.distance);  // distance → yardage
    }
    
    // 목격자 정보 (없으면 기본값 설정)
    if (formData.witnesses && formData.witnesses.trim()) {
        const witnessLines = formData.witnesses.split('\n');
        if (witnessLines.length > 0) {
            submitData.append('witnessName', witnessLines[0].trim());
            if (witnessLines.length > 1) {
                submitData.append('witnessPhone', witnessLines[1].trim());
            }
        }
    }
    
    // 기타 정보
    if (formData.description) {
        submitData.append('additionalNotes', formData.description);
    }
    
    // 계좌 정보 (없으면 빈 값)
    // 계좌 정보 추가 (기존 빈 값 대신 실제 값 전송)
	if (formData.bankName) {
		submitData.append('bankName', formData.bankName);
	} else {
		submitData.append('bankName', '');
	}

	if (formData.accountNumber) {
		submitData.append('accountNumber', formData.accountNumber);
	} else {
		submitData.append('accountNumber', '');
	}

	if (formData.accountHolder) {
		submitData.append('accountHolder', formData.accountHolder);
	} else {
		submitData.append('accountHolder', '');
	}
    
    // 사용 클럽 정보
    submitData.append('club', '');
    
    // 약관 동의
    submitData.append('termsAgreed', formData.termsAgreed ? 'true' : 'false');

    // 파일 업로드
    if (uploadedFiles.photo) {
        submitData.append('photoFile', uploadedFiles.photo);
    }
    if (uploadedFiles.certificate) {
        submitData.append('certificateFile', uploadedFiles.certificate);
    }
    
    // 추가 파일들
    uploadedFiles.additional.forEach((file, index) => {
        if (file) {
            submitData.append(`additionalFile_${index}`, file);
        }
    });
    
    // 디버깅용 로그
    console.log('=== 전송할 데이터 ===');
    for (let [key, value] of submitData.entries()) {
        if (value instanceof File) {
            console.log(`${key}: [File] ${value.name} (${value.size} bytes)`);
        } else {
            console.log(`${key}: ${value}`);
        }
    }

    fetch('api/customer/submitClaim.php', {
        method: 'POST',
        body: submitData
    })
    .then(response => {
        if (!response.ok) {
            throw new Error(`HTTP error! status: ${response.status}`);
        }
        return response.json();
    })
    .then(data => {
        handleSubmissionResult(data);
    })
    .catch(error => {
        console.error('보상 신청 중 오류 발생:', error);
        if (typeof showPopup === 'function') {
            showPopup('전송 오류', '서버와의 통신 중 문제가 발생했습니다.\n잠시 후 다시 시도해주세요.');
        }
    })
    .finally(() => {
        isSubmitting = false;
        submitButton.disabled = false;
        submitButton.innerHTML = originalButtonText;
    });
}

// 제출 결과 처리
// 제출 결과 처리
function handleSubmissionResult(data) {
    if (data.success) {
        // claimNumber 우선 사용, 없으면 claimId 사용
        const claimNumber = data.data?.claimNumber || data.claimNumber || data.data?.claimId || data.claimId || 'N/A';
        
        if (typeof showSuccessPopup === 'function') {
            showSuccessPopup(
                '신청 완료',
                `보상 신청이 완료되었습니다.\n\n신청번호: ${claimNumber}\n\n검토 후 영업일 기준 3-5일 내에 결과를 알려드리겠습니다.`,
                function() {
                    resetClaimProcess();
                }
            );
        } else {
            alert(`보상 신청이 완료되었습니다.\n신청번호: ${claimNumber}`);
            resetClaimProcess();
        }
    } else {
        const errorMessage = getClaimErrorMessage(data.errorCode, data.message);
        if (typeof showPopup === 'function') {
            showPopup('신청 실패', errorMessage);
        } else {
            alert('신청 실패: ' + errorMessage);
        }
    }
}


// 보상 신청 오류 메시지 처리
function getClaimErrorMessage(errorCode, defaultMessage) {
    const errorMessages = {
        'DUPLICATE_CLAIM': '이미 신청된 내역이 있습니다.',
        'INVALID_FILE': '업로드된 파일이 올바르지 않습니다.',
        'FILE_UPLOAD_ERROR': '파일 업로드 중 오류가 발생했습니다.',
        'DATABASE_ERROR': '데이터베이스 오류가 발생했습니다.',
        'VALIDATION_ERROR': '입력 정보를 다시 확인해주세요.',
        'SERVER_ERROR': '서버 오류가 발생했습니다. 고객센터로 문의해주세요.',
        'SIGNUP_NOT_FOUND': '가입 내역을 찾을 수 없습니다.',
        'INVALID_CLAIM_DATE': '유효하지 않은 홀인원 발생 날짜입니다.'
    };
    
    return errorMessages[errorCode] || defaultMessage || '알 수 없는 오류가 발생했습니다.';
}

// 보상 신청 프로세스 초기화
function resetClaimProcess() {
    showStep(1);
    resetStep1();
    
    uploadedFiles = {
        photo: null,
        certificate: null,
        additional: []
    };
    
    const formFields = [
        'claim-date', 'claim-golf-course', 'claim-hole', 
        'claim-distance', 'witnesses', 'description',
		'bank-name', 'account-number', 'account-holder'  // 🆕 추가
    ];

    formFields.forEach(fieldId => {
        const field = document.getElementById(fieldId);
        if (field) {
            field.value = '';
            clearFieldError(field);
        }
    });

    const termsCheckbox = document.getElementById('claim-terms-checkbox');
    if (termsCheckbox) {
        termsCheckbox.checked = false;
    }

    resetUploadBox('upload-photo', '홀인원 증명 사진');
    resetUploadBox('upload-certificate', '스코어카드/확인서');
    
    const additionalContainer = document.getElementById('additional-files-container');
    if (additionalContainer) {
        additionalContainer.innerHTML = '';
    }
    
    verifiedSignupData = null;
    const confirmedInfo = document.getElementById('confirmed-signup-info');
    if (confirmedInfo) {
        confirmedInfo.innerHTML = '';
    }
}

// 업로드 박스 초기화
function resetUploadBox(boxId, originalText) {
    const uploadBox = document.getElementById(boxId);
    if (uploadBox) {
        const textElement = uploadBox.querySelector('.file-upload-text');
        if (textElement) {
            textElement.textContent = originalText;
        }
        
        uploadBox.style.borderColor = '#ddd';
        uploadBox.style.backgroundColor = '#fafafa';
        uploadBox.classList.remove('uploaded');
        
        const successIcon = uploadBox.querySelector('.upload-success-icon');
        const preview = uploadBox.querySelector('.file-preview');
        if (successIcon) successIcon.remove();
        if (preview) preview.remove();
    }
}

// 유틸리티 함수들

// 날짜/시간 포맷팅
function formatDateTime(dateTimeString) {
    if (!dateTimeString) return 'N/A';
    
    const date = new Date(dateTimeString);
    if (isNaN(date.getTime())) return 'N/A';
    
    const year = date.getFullYear();
    const month = String(date.getMonth() + 1).padStart(2, '0');
    const day = String(date.getDate()).padStart(2, '0');
    const hours = String(date.getHours()).padStart(2, '0');
    const minutes = String(date.getMinutes()).padStart(2, '0');
    
    return `${year}년 ${month}월 ${day}일 ${hours}:${minutes}`;
}

// 휴대폰 번호 포맷팅
function formatPhoneNumber(phone) {
    if (!phone) return 'N/A';
    
    const cleaned = phone.replace(/[^\d]/g, '');
    
    if (cleaned.length === 11) {
        return cleaned.replace(/(\d{3})(\d{4})(\d{4})/, '$1-$2-$3');
    } else if (cleaned.length === 10) {
        return cleaned.replace(/(\d{3})(\d{3})(\d{4})/, '$1-$2-$3');
    }
    
    return phone;
}

// 통화 형식 포맷팅
function formatCurrency(amount) {
    if (!amount) return '0';
    
    const numAmount = typeof amount === 'string' ? parseInt(amount) : amount;
    return numAmount.toLocaleString('ko-KR');
}

// 알림 사운드 재생
function playNotificationSound(type) {
    try {
        const audioContext = new (window.AudioContext || window.webkitAudioContext)();
        
        if (type === 'success') {
            const oscillator = audioContext.createOscillator();
            const gainNode = audioContext.createGain();
            
            oscillator.connect(gainNode);
            gainNode.connect(audioContext.destination);
            
            oscillator.frequency.setValueAtTime(800, audioContext.currentTime);
            oscillator.frequency.setValueAtTime(1000, audioContext.currentTime + 0.1);
            
            gainNode.gain.setValueAtTime(0.3, audioContext.currentTime);
            gainNode.gain.exponentialRampToValueAtTime(0.01, audioContext.currentTime + 0.3);
            
            oscillator.start(audioContext.currentTime);
            oscillator.stop(audioContext.currentTime + 0.3);
        }
    } catch (error) {
        console.log('Audio notification not available');
    }
}

// 축하 메시지 표시
function showCelebrationMessage() {
    const celebrationDiv = document.createElement('div');
    celebrationDiv.className = 'celebration-message';
    celebrationDiv.innerHTML = `
        <div class="celebration-content">
            <div class="celebration-icon">🎊</div>
            <div class="celebration-text">가입 확인 완료!</div>
        </div>
    `;
    
    document.body.appendChild(celebrationDiv);
    
    // 애니메이션
    setTimeout(() => {
        celebrationDiv.style.transform = 'translateX(0)';
    }, 100);
    
    // 3초 후 제거
    setTimeout(() => {
        celebrationDiv.style.transform = 'translateX(100%)';
        setTimeout(() => {
            if (celebrationDiv.parentNode) {
                celebrationDiv.parentNode.removeChild(celebrationDiv);
            }
        }, 300);
    }, 3000);
}

// 확인 대화상자 표시
function showConfirmDialog(title, message, onConfirm, onCancel) {
    const popupOverlay = document.getElementById('popup-overlay');
    const popupTitle = document.getElementById('popup-title');
    const popupMessage = document.getElementById('popup-message');
    const popupButton = document.getElementById('popup-button');

    if (!popupOverlay || !popupTitle || !popupMessage || !popupButton) {
        if (confirm(title + '\n\n' + message.replace(/<[^>]*>/g, ''))) {
            if (onConfirm) onConfirm();
        } else {
            if (onCancel) onCancel();
        }
        return;
    }

    popupTitle.textContent = title;
    popupMessage.innerHTML = message;

    popupButton.style.display = 'none';

    const buttonContainer = document.createElement('div');
    buttonContainer.className = 'confirm-buttons';
    buttonContainer.innerHTML = `
        <button class="popup-button secondary" id="enhanced-confirm-cancel">취소</button>
        <button class="popup-button primary" id="enhanced-confirm-ok">확인</button>
    `;

    popupMessage.parentNode.appendChild(buttonContainer);
    popupOverlay.classList.add('show');

    const handleCancel = function() {
        closeEnhancedConfirmDialog();
        if (onCancel) onCancel();
    };

    const handleConfirm = function() {
        closeEnhancedConfirmDialog();
        if (onConfirm) onConfirm();
    };

    document.getElementById('enhanced-confirm-cancel').addEventListener('click', handleCancel);
    document.getElementById('enhanced-confirm-ok').addEventListener('click', handleConfirm);
}

// 확인 대화상자 닫기
function closeEnhancedConfirmDialog() {
    const popupOverlay = document.getElementById('popup-overlay');
    if (popupOverlay) {
        popupOverlay.classList.remove('show');
    }

    const buttonContainer = document.querySelector('.confirm-buttons');
    if (buttonContainer) {
        buttonContainer.remove();
    }
    
    const popupButton = document.getElementById('popup-button');
    if (popupButton) {
        popupButton.style.display = 'block';
    }
}

// 성공 팝업 표시
function showSuccessPopup(title, message, onClose) {
    const popupOverlay = document.getElementById('popup-overlay');
    const popupTitle = document.getElementById('popup-title');
    const popupMessage = document.getElementById('popup-message');
    const popupButton = document.getElementById('popup-button');

    if (!popupOverlay || !popupTitle || !popupMessage || !popupButton) {
        alert(title + '\n\n' + message);
        if (onClose) onClose();
        return;
    }

    popupTitle.textContent = title;
    popupMessage.innerHTML = message.replace(/\n/g, '<br>');
    popupOverlay.classList.add('show');

    const handleClose = function() {
        popupOverlay.classList.remove('show');
        popupButton.removeEventListener('click', handleClose);
        if (onClose) onClose();
    };

    popupButton.addEventListener('click', handleClose);
}

// 팝업 표시 함수
function showPopup(title, message) {
    const popupOverlay = document.getElementById('popup-overlay');
    const popupTitle = document.getElementById('popup-title');
    const popupMessage = document.getElementById('popup-message');
    const popupButton = document.getElementById('popup-button');

    if (popupOverlay && popupTitle && popupMessage && popupButton) {
        popupTitle.textContent = title;
        popupMessage.textContent = message;
        popupOverlay.classList.add('show');

        const handleClose = function() {
            popupOverlay.classList.remove('show');
            popupButton.removeEventListener('click', handleClose);
        };

        popupButton.addEventListener('click', handleClose);
    } else {
        alert(title + '\n\n' + message);
    }
}

// 전역 함수로 내보내기
window.removeAdditionalFile = removeAdditionalFile;