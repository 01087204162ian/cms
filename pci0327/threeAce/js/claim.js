// enhanced-claim.js - 향상된 단계별 보상 신청 스크립트

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
    // 단계 표시기 업데이트
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
    
    // 다음 단계 버튼
    const goToStep2 = document.getElementById('go-to-step-2');
    if (goToStep2) {
        goToStep2.addEventListener('click', function() {
            showStep(2);
        });
    }
    
    // 다시 시도 버튼
    const tryAgainButton = document.getElementById('try-again-button');
    if (tryAgainButton) {
        tryAgainButton.addEventListener('click', function() {
            resetStep1();
        });
    }
    
    // 가입하기 버튼
    const goToSignup = document.getElementById('go-to-signup');
    if (goToSignup) {
        goToSignup.addEventListener('click', function() {
            // 가입 신청 페이지로 이동
            if (typeof showPage === 'function' && typeof setActiveTab === 'function') {
                const signupPage = document.getElementById('signup-page');
                const navSignup = document.getElementById('nav-signup');
                if (signupPage && navSignup) {
                    showPage(signupPage);
                    setActiveTab(navSignup);
                }
            }
        });
    }
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
    
    // 파일 업로드 설정
    setupFileUpload();
    
    // 추가 파일 업로드
    setupAdditionalFiles();
    
    // 폼 검증 설정
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
    
    // 휴대폰 번호 형식 검증
    const phonePattern = /^010-\d{4}-\d{4}$/;
    if (!phonePattern.test(phone)) {
        showFieldError(phoneInput, '올바른 휴대폰번호 형식으로 입력해주세요. (010-0000-0000)');
        return;
    }
    
    // 기존 결과 숨기기
    hideVerificationResult();
    
    // 로딩 시작
    showVerificationLoading(true);
    
    // 서버에 가입 내역 조회 요청
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
        // 로딩 종료
        showVerificationLoading(false);
        
        if (data.success && data.data && data.data.length > 0) {
            // 가입 내역 있음
            verifiedSignupData = data.data[0]; // 가장 최근 가입 내역 사용
            showVerificationSuccess(verifiedSignupData);
        } else {
            // 가입 내역 없음
            showVerificationFailed();
        }
    })
    .catch(error => {
        // 로딩 종료
        showVerificationLoading(false);
        
        console.error('가입 내역 조회 중 오류 발생:', error);
        showVerificationError('서버와의 통신 중 문제가 발생했습니다. 잠시 후 다시 시도해주세요.');
    });
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

// 가입 확인 성공 표시
function showVerificationSuccess(signupData) {
    const resultContainer = document.getElementById('signup-verification-result');
    const successContent = document.getElementById('verification-success-content');
    const signupDetails = document.getElementById('signup-details');
    
    // 가입 정보 표시
    signupDetails.innerHTML = `
        <div class="signup-detail-item">
            <span class="detail-label">가입자명:</span>
            <span class="detail-value">${signupData.customerName || signupData.name || 'N/A'}</span>
        </div>
        <div class="signup-detail-item">
            <span class="detail-label">가입일시:</span>
            <span class="detail-value">${formatDateTime(signupData.createdAt || signupData.signupDate)}</span>
        </div>
        <div class="signup-detail-item">
            <span class="detail-label">가입번호:</span>
            <span class="detail-value">${signupData.signupId || signupData.id || 'N/A'}</span>
        </div>
        ${signupData.golfCourseName || signupData.golfCourse ? `
        <div class="signup-detail-item">
            <span class="detail-label">예정 골프장:</span>
            <span class="detail-value">${signupData.golfCourseName || signupData.golfCourse}</span>
        </div>
        ` : ''}
        ${signupData.teeOffTime || signupData.teeTime ? `
        <div class="signup-detail-item">
            <span class="detail-label">예정 티오프:</span>
            <span class="detail-value">${formatDateTime(signupData.teeOffTime || signupData.teeTime)}</span>
        </div>
        ` : ''}
    `;
    
    resultContainer.style.display = 'block';
    successContent.style.display = 'block';
    
    // 3단계에서 사용할 정보 미리 설정
    populateConfirmedInfo(signupData);
}

// 가입 확인 실패 표시
function showVerificationFailed() {
    const resultContainer = document.getElementById('signup-verification-result');
    const failedContent = document.getElementById('verification-failed-content');
    
    resultContainer.style.display = 'block';
    failedContent.style.display = 'block';
}

// 가입 확인 오류 표시
function showVerificationError(message) {
    if (typeof showPopup === 'function') {
        showPopup('확인 오류', message);
    } else {
        alert('확인 오류: ' + message);
    }
}

// 확인 결과 숨기기
function hideVerificationResult() {
    const resultContainer = document.getElementById('signup-verification-result');
    const successContent = document.getElementById('verification-success-content');
    const failedContent = document.getElementById('verification-failed-content');
    
    if (resultContainer) resultContainer.style.display = 'none';
    if (successContent) successContent.style.display = 'none';
    if (failedContent) failedContent.style.display = 'none';
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
    // 현재 단계 숨기기
    const currentStepContent = document.getElementById(`claim-step-${currentStep}`);
    if (currentStepContent) {
        currentStepContent.style.display = 'none';
        currentStepContent.classList.remove('active');
    }
    
    // 새 단계 표시
    const newStepContent = document.getElementById(`claim-step-${stepNumber}`);
    if (newStepContent) {
        newStepContent.style.display = 'block';
        newStepContent.classList.add('active');
    }
    
    // 단계 업데이트
    currentStep = stepNumber;
    updateStepIndicator(stepNumber);
    
    // 3단계로 진입 시 정보 설정
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

// 3단계 데이터 설정
function populateStep3WithVerifiedData() {
    if (!verifiedSignupData) return;
    
    // 골프장명이 있으면 미리 입력
    const golfCourseInput = document.getElementById('claim-golf-course');
    if (golfCourseInput && (verifiedSignupData.golfCourseName || verifiedSignupData.golfCourse)) {
        golfCourseInput.value = verifiedSignupData.golfCourseName || verifiedSignupData.golfCourse;
    }
}

// 파일 업로드 설정
function setupFileUpload() {
    const uploadPhoto = document.getElementById('upload-photo');
    const uploadCertificate = document.getElementById('upload-certificate');
    const photoInput = document.getElementById('photo-input');
    const certificateInput = document.getElementById('certificate-input');

    // 홀인원 사진 업로드
    if (uploadPhoto && photoInput) {
        uploadPhoto.addEventListener('click', function() {
            photoInput.click();
        });

        photoInput.addEventListener('change', function(e) {
            handleFileUpload(e, 'photo', uploadPhoto);
        });
    }

    // 스코어카드 업로드
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
    
    // 이벤트 리스너 추가
    const uploadBox = document.getElementById(`upload-additional-${fileIndex}`);
    const fileInput = document.getElementById(`additional-input-${fileIndex}`);
    
    uploadBox.addEventListener('click', function() {
        fileInput.click();
    });
    
    fileInput.addEventListener('change', function(e) {
        handleAdditionalFileUpload(e, fileIndex, uploadBox);
    });
    
    // 배열에 placeholder 추가
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

    // 파일 유효성 검사
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

    // 파일 저장
    uploadedFiles[type] = file;

    // UI 업데이트
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
    
    // 성공 아이콘 추가
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
    const formFields = [
        'claim-date', 'claim-golf-course', 'claim-hole'
    ];

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

    // 홀 번호 특별 처리
    const holeField = document.getElementById('claim-hole');
    if (holeField) {
        holeField.addEventListener('input', function() {
            formatHoleNumber(this);
        });
    }

    // 날짜 제한 설정
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

    // 1단계: 폼 유효성 검사
    const formData = validateFinalForm();
    if (!formData) {
        return;
    }

    // 2단계: 파일 업로드 확인
    if (!validateFileUploads()) {
        return;
    }

    // 3단계: 최종 확인 모달 표시
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
        termsCheckbox: document.getElementById('claim-terms-checkbox')
    };

    let isValid = true;
    const formData = {};

    // 필수 필드 검사
    ['date', 'golfCourse', 'hole'].forEach(key => {
        const element = formElements[key];
        if (element) {
            if (!validateField(element)) {
                isValid = false;
            } else {
                formData[key] = element.value.trim();
            }
        }
    });

    // 선택 필드 추가
    ['distance', 'witnesses', 'description'].forEach(key => {
        const element = formElements[key];
        if (element && element.value.trim()) {
            formData[key] = element.value.trim();
        }
    });

    // 약관 동의 확인
    if (!formElements.termsCheckbox.checked) {
        if (typeof showPopup === 'function') {
            showPopup('약관 동의', '개인정보 활용 동의가 필요합니다.');
        }
        isValid = false;
    } else {
        formData.termsAgreed = true;
    }

    // 가입 정보 추가
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
        // 기본 confirm 사용
        if (confirm('입력하신 내용으로 보상 신청을 진행하시겠습니까?')) {
            proceedWithFinalSubmission(formData);
        }
    }
}

// 최종 제출 진행
function proceedWithFinalSubmission(formData) {
    isSubmitting = true;
    
    // 제출 버튼 비활성화 및 로딩 표시
    const submitButton = document.getElementById('submit-claim-button');
    const originalButtonText = submitButton.innerHTML;
    submitButton.disabled = true;
    submitButton.innerHTML = `
        <span class="button-text">신청 중...</span>
        <span class="button-icon">⏳</span>
    `;

    // FormData 객체 생성
    const submitData = new FormData();
    
    // 기본 정보 추가
    Object.keys(formData).forEach(key => {
        if (key !== 'signupData' && key !== 'termsAgreed') {
            submitData.append(key, formData[key]);
        }
    });
    submitData.append('termsAgreed', formData.termsAgreed);

    // 가입 정보 추가
    if (formData.signupData) {
        submitData.append('signupId', formData.signupData.signupId || formData.signupData.id);
        submitData.append('customerName', formData.signupData.customerName || formData.signupData.name);
        submitData.append('customerPhone', formData.signupData.phone || formData.signupData.phoneNumber);
    }

    // 파일 추가
    submitData.append('photoFile', uploadedFiles.photo);
    submitData.append('certificateFile', uploadedFiles.certificate);
    
    // 추가 파일들
    uploadedFiles.additional.forEach((file, index) => {
        if (file) {
            submitData.append(`additionalFile_${index}`, file);
        }
    });

    // 서버로 전송
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
        // 버튼 상태 복원
        isSubmitting = false;
        submitButton.disabled = false;
        submitButton.innerHTML = originalButtonText;
    });
}

// 제출 결과 처리
function handleSubmissionResult(data) {
    if (data.success) {
        // 성공 시
        const claimId = data.claimId || 'N/A';
        if (typeof showSuccessPopup === 'function') {
            showSuccessPopup(
                '신청 완료',
                `보상 신청이 완료되었습니다.\n\n신청번호: ${claimId}\n\n검토 후 영업일 기준 3-5일 내에 결과를 알려드리겠습니다.`,
                function() {
                    resetClaimProcess();
                }
            );
        } else {
            alert(`보상 신청이 완료되었습니다.\n신청번호: ${claimId}`);
            resetClaimProcess();
        }
    } else {
        // 실패 시
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
    // 1단계로 돌아가기
    showStep(1);
    
    // 모든 입력 필드 초기화
    resetStep1();
    
    // 파일 업로드 초기화
    uploadedFiles = {
        photo: null,
        certificate: null,
        additional: []
    };
    
    // 3단계 폼 초기화
    const formFields = [
        'claim-date', 'claim-golf-course', 'claim-hole', 
        'claim-distance', 'witnesses', 'description'
    ];

    formFields.forEach(fieldId => {
        const field = document.getElementById(fieldId);
        if (field) {
            field.value = '';
            clearFieldError(field);
        }
    });

    // 체크박스 초기화
    const termsCheckbox = document.getElementById('claim-terms-checkbox');
    if (termsCheckbox) {
        termsCheckbox.checked = false;
    }

    // 업로드 박스 초기화
    resetUploadBox('upload-photo', '홀인원 증명 사진');
    resetUploadBox('upload-certificate', '스코어카드/확인서');
    
    // 추가 파일들 제거
    const additionalContainer = document.getElementById('additional-files-container');
    if (additionalContainer) {
        additionalContainer.innerHTML = '';
    }
    
    // 확인된 정보 초기화
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
        
        // 추가된 요소들 제거
        const successIcon = uploadBox.querySelector('.upload-success-icon');
        const preview = uploadBox.querySelector('.file-preview');
        if (successIcon) successIcon.remove();
        if (preview) preview.remove();
    }
}

// 날짜/시간 포맷팅 함수 (basic.js와 호환)
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

// 휴대폰 번호 포맷팅 (basic.js와 호환)
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

// 확인 대화상자 표시 (basic.js와 호환성을 위해)
function showConfirmDialog(title, message, onConfirm, onCancel) {
    // 기존 팝업 시스템 사용
    const popupOverlay = document.getElementById('popup-overlay');
    const popupTitle = document.getElementById('popup-title');
    const popupMessage = document.getElementById('popup-message');
    const popupButton = document.getElementById('popup-button');

    if (!popupOverlay || !popupTitle || !popupMessage || !popupButton) {
        // 팝업 요소가 없으면 기본 confirm 사용
        if (confirm(title + '\n\n' + message.replace(/<[^>]*>/g, ''))) {
            if (onConfirm) onConfirm();
        } else {
            if (onCancel) onCancel();
        }
        return;
    }

    popupTitle.textContent = title;
    popupMessage.innerHTML = message;

    // 기존 버튼 숨기고 새 버튼들 추가
    popupButton.style.display = 'none';

    const buttonContainer = document.createElement('div');
    buttonContainer.className = 'confirm-buttons';
    buttonContainer.innerHTML = `
        <button class="popup-button secondary" id="enhanced-confirm-cancel">취소</button>
        <button class="popup-button primary" id="enhanced-confirm-ok">확인</button>
    `;

    popupMessage.parentNode.appendChild(buttonContainer);
    popupOverlay.classList.add('show');

    // 버튼 이벤트 등록 (일회성)
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

    // 추가된 버튼 제거하고 원래 버튼 복원
    const buttonContainer = document.querySelector('.confirm-buttons');
    if (buttonContainer) {
        buttonContainer.remove();
    }
    
    const popupButton = document.getElementById('popup-button');
    if (popupButton) {
        popupButton.style.display = 'block';
    }
}

// 성공 팝업 표시 (basic.js와 호환성을 위해)
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

    // 버튼 이벤트 (일회성)
    const handleClose = function() {
        popupOverlay.classList.remove('show');
        popupButton.removeEventListener('click', handleClose);
        if (onClose) onClose();
    };

    popupButton.addEventListener('click', handleClose);
}

// 팝업 표시 함수 (basic.js와의 호환성을 위해)
function showPopup(title, message) {
    const popupOverlay = document.getElementById('popup-overlay');
    const popupTitle = document.getElementById('popup-title');
    const popupMessage = document.getElementById('popup-message');
    const popupButton = document.getElementById('popup-button');

    if (popupOverlay && popupTitle && popupMessage && popupButton) {
        popupTitle.textContent = title;
        popupMessage.textContent = message;
        popupOverlay.classList.add('show');

        // 버튼 이벤트 (일회성)
        const handleClose = function() {
            popupOverlay.classList.remove('show');
            popupButton.removeEventListener('click', handleClose);
        };

        popupButton.addEventListener('click', handleClose);
    } else {
        // 팝업 요소가 없으면 기본 alert 사용
        alert(title + '\n\n' + message);
    }
}

// 전역 함수로 내보내기 (다른 스크립트에서 사용 가능하도록)
window.removeAdditionalFile = removeAdditionalFile;