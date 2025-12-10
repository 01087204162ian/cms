// 전역 변수
let contactCounter = 0;
let currentMenuClientId = null;
let currentClientInfoId = null; // client_info_id 저장용 추가
let currentIdGubun = null;

function openAddClientModal(itemId, idGubun) {
    // menu_client_id와 idGubun 저장
    currentMenuClientId = itemId;
    currentIdGubun = idGubun;
    currentClientInfoId = null; // 초기화
    
    // 서버에서 정보 조회
    fetchMenuClientInfo(itemId, idGubun);
}

function fetchMenuClientInfo(itemId, idGubun) {
    console.log('idGubun:', idGubun);
    
    // FormData 생성
    const formData = new FormData();
    formData.append('itemId', itemId);
    formData.append('idGubun', idGubun);
    
    // 서버와 통신하여 정보 조회
    fetch('/api/manual/menu_client_serarch.php', {
        method: 'POST',
        headers: {
            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]')?.getAttribute('content') || ''
        },
        body: formData
    })
    .then(response => response.json())
    .then(result => {
        if (result.success && result.data) {
            if (idGubun == 1) {
                // 신규 등록용 - 빈 폼 생성
                const menuTitle = result.data.menu_2nd || '거래처';
                renderClientModal(menuTitle, null, idGubun);
            } else if (idGubun == 2) {
                // 조회/수정용 - 기존 데이터로 폼 채우기
                const menuTitle = result.data.client_info?.menu_2nd || '거래처';
                
                // **중요: client_info_id를 전역 변수에 저장**
                if (result.data.client_info && result.data.client_info.client_info_id) {
                    currentClientInfoId = result.data.client_info.client_info_id;
                    console.log('client_info_id 저장됨:', currentClientInfoId);
                }
                
                renderClientModal(menuTitle, result.data, idGubun);
            }
        } else {
            // 데이터가 없거나 오류인 경우 기본값 사용
            console.warn('정보를 가져올 수 없습니다:', result.message);
            renderClientModal('거래처', null, idGubun);
        }
    })
    .catch(error => {
        console.error('정보 조회 중 오류:', error);
        renderClientModal('거래처', null, idGubun);
    });
}

function renderClientModal(menuTitle, existingData = null, idGubun = 1) {
    const isNewRegistration = (idGubun == 1 && !existingData); // 신규 등록 모드
    const isViewEditMode = (idGubun == 2 || (idGubun == 1 && existingData)); // 조회/수정 통합 모드
    
    let modalTitle;
    if (isNewRegistration) {
        modalTitle = `${menuTitle} 거래처 등록`;
    } else {
        modalTitle = `${menuTitle} 거래처 정보`;
    }
    
    // 헤더 설정
    document.getElementById('dambo_daeriCompany').textContent = modalTitle;
    
    const modalHTML = `
        <div class="modal-body">
            <style>
                .modal-body {
                    max-height: 70vh;
                    overflow-y: auto;
                    padding: 20px;
                }
                
                .m-client-form {
                    font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
                }
                
                .m-section {
                    margin-bottom: 30px;
                }
                
                .m-section-title {
                    font-size: 18px;
                    color: #333;
                    margin-bottom: 15px;
                    padding-bottom: 8px;
                    border-bottom: 2px solid #228B22;
                    display: flex;
                    align-items: center;
                    justify-content: space-between;
                }
                
                .m-section-title-left {
                    display: flex;
                    align-items: center;
                    gap: 8px;
                }
                
                .m-section-title-left::before {
                    content: '';
                    width: 3px;
                    height: 16px;
                    background: #228B22;
                    border-radius: 2px;
                }
                
                .m-form-group {
                    display: flex;
                    align-items: center;
                    margin-bottom: 15px;
                    gap: 10px;
                }
                
                .m-form-row {
                    display: flex;
                    gap: 15px;
                    margin-bottom: 15px;
                }
                
                .m-form-row .m-form-group {
                    flex: 1;
                    margin-bottom: 0;
                }
                
                .m-form-label {
                    display: block;
                    min-width: 100px;
                    width: 100px;
                    margin-bottom: 0;
                    font-weight: 600;
                    color: #555;
                    font-size: 14px;
                    flex-shrink: 0;
                }
                
                .m-required {
                    color: #e74c3c;
                }
                
                .m-form-input {
                    flex: 1;
                    padding: 8px 12px;
                    border: 1px solid #ddd;
                    border-radius: 4px;
                    font-size: 14px;
                    transition: border-color 0.3s ease;
                }
                
                .m-form-input:focus {
                    outline: none;
                    border-color: #228B22;
                    box-shadow: 0 0 0 2px rgba(34, 139, 34, 0.1);
                }
                
                .m-form-input:disabled {
                    background-color: #f8f9fa;
                    color: #6c757d;
                }
                
                .m-form-textarea {
                    flex: 1;
                    padding: 8px 12px;
                    border: 1px solid #ddd;
                    border-radius: 4px;
                    font-size: 14px;
                    resize: vertical;
                    min-height: 60px;
                    transition: border-color 0.3s ease;
                }
                
                .m-form-textarea:focus {
                    outline: none;
                    border-color: #228B22;
                    box-shadow: 0 0 0 2px rgba(34, 139, 34, 0.1);
                }
                
                .m-form-textarea:disabled {
                    background-color: #f8f9fa;
                    color: #6c757d;
                }
                
                .m-checkbox-group {
                    display: flex;
                    align-items: center;
                    gap: 8px;
                    margin-top: 8px;
                }
                
                .m-checkbox-group .m-form-label {
                    min-width: auto;
                    width: auto;
                }
                
                .m-checkbox-group input[type="checkbox"] {
                    width: auto;
                    margin: 0;
                }
                
                .m-contact-section {
                    background: #f8f9fa;
                    padding: 15px;
                    border-radius: 6px;
                    margin-top: 15px;
                }
                
                .m-contact-item {
                    background: white;
                    padding: 15px;
                    border-radius: 4px;
                    margin-bottom: 10px;
                    border: 1px solid #e0e0e0;
                }
                
                .m-contact-header {
                    display: flex;
                    justify-content: space-between;
                    align-items: center;
                    margin-bottom: 10px;
                }
                
                .m-contact-number {
                    font-weight: bold;
                    color: #228B22;
                    font-size: 14px;
                }
                
                .m-btn-add {
                    background: #3498db;
                    color: white;
                    border: none;
                    padding: 8px 16px;
                    border-radius: 4px;
                    cursor: pointer;
                    font-size: 13px;
                    transition: background 0.3s ease;
                }
                
                .m-btn-add:hover {
                    background: #2980b9;
                }
                
                .m-btn-remove {
                    background: #e74c3c;
                    color: white;
                    border: none;
                    padding: 5px 10px;
                    border-radius: 3px;
                    cursor: pointer;
                    font-size: 11px;
                    transition: background 0.3s ease;
                }
                
                .m-btn-remove:hover {
                    background: #c0392b;
                }
                
                .m-alert {
                    padding: 10px 15px;
                    margin-bottom: 15px;
                    border-radius: 4px;
                    display: none;
                }
                
                .m-alert-success {
                    background: #d4edda;
                    color: #155724;
                    border: 1px solid #c3e6cb;
                }
                
                .m-alert-error {
                    background: #f8d7da;
                    color: #721c24;
                    border: 1px solid #f5c6cb;
                }
                
                .m-form-actions {
                    margin-top: 20px;
                    padding-top: 20px;
                    border-top: 1px solid #eee;
                    display: flex;
                    justify-content: center;
                    gap: 15px;
                }
                
                .m-btn-primary {
                    background: #228B22;
                    color: white;
                    border: none;
                    padding: 10px 25px;
                    border-radius: 4px;
                    cursor: pointer;
                    font-size: 14px;
                    font-weight: 600;
                    transition: background 0.3s ease;
                }
                
                .m-btn-primary:hover {
                    background: #1e7d1e;
                }
                
                .m-btn-secondary {
                    background: #6c757d;
                    color: white;
                    border: none;
                    padding: 10px 25px;
                    border-radius: 4px;
                    cursor: pointer;
                    font-size: 14px;
                    font-weight: 600;
                    transition: background 0.3s ease;
                }
                
                .m-btn-secondary:hover {
                    background: #5a6268;
                }
                
                .m-btn-edit {
                    background: #17a2b8;
                    color: white;
                    border: none;
                    padding: 10px 25px;
                    border-radius: 4px;
                    cursor: pointer;
                    font-size: 14px;
                    font-weight: 600;
                    transition: background 0.3s ease;
                }
                
                .m-btn-edit:hover {
                    background: #138496;
                }
                
                @media (max-width: 768px) {
                    .m-form-row {
                        flex-direction: column;
                        gap: 0;
                    }
                    
                    .m-form-group {
                        flex-direction: column;
                        align-items: flex-start;
                        gap: 5px;
                    }
                    
                    .m-form-label {
                        min-width: auto;
                        width: auto;
                    }
                    
                    .m-form-actions {
                        flex-direction: column;
                        align-items: stretch;
                    }
                }
            </style>
            
            <div class="m-client-form">
                
                
                <form id="m-clientForm">
                    <!-- 기본 정보 섹션 -->
                    <div class="m-section">
                        <div class="m-section-title">
                            <div class="m-section-title-left">기본 정보</div>
                        </div>
                        
                        <div class="m-form-group">
                            <label class="m-form-label" for="m-client_name">거래처명 ${isNewRegistration ? '<span class="m-required">*</span>' : ''}</label>
                            <input type="text" id="m-client_name" name="client_name" class="m-form-input" ${isNewRegistration ? 'required' : ''}>
                        </div>

                        <div class="m-form-row">
                            <div class="m-form-group">
                                <label class="m-form-label" for="m-business_number">사업자번호</label>
                                <input type="text" id="m-business_number" name="business_number" class="m-form-input" placeholder="000-00-00000" oninput="formatBusinessNumber(this)">
                            </div>
                            <div class="m-form-group">
                                <label class="m-form-label" for="m-corporate_number">법인번호</label>
                                <input type="text" id="m-corporate_number" name="corporate_number" class="m-form-input" placeholder="000000-0000000" oninput="formatCorporateNumber(this)">
                            </div>
                        </div>

                        <div class="m-form-group">
                            <label class="m-form-label" for="m-our_manager">당사 담당자</label>
                            <input type="text" id="m-our_manager" name="our_manager" class="m-form-input">
                        </div>

                        <div class="m-form-group">
                            <label class="m-form-label" for="m-description">설명</label>
                            <textarea id="m-description" name="description" class="m-form-textarea" placeholder="${isNewRegistration ? menuTitle + '에 대한 추가 설명을 입력해주세요' : ''}"></textarea>
                        </div>

                        <div class="m-checkbox-group">
                            <input type="checkbox" id="m-is_active" name="is_active" checked>
                            <label class="m-form-label" for="m-is_active">활성화 상태</label>
                        </div>
                    </div>
					<div id="m-alert-container"></div>
                    <!-- 담당자 정보 섹션 -->
                    <div class="m-section">
                        <div class="m-section-title">
                            <div class="m-section-title-left">담당자 정보</div>
                            <button type="button" class="m-btn-add" onclick="addContactInModal()">+ 거래처 담당자 추가</button>
                        </div>
                        
                        <div class="m-contact-section">
                            <div id="m-contacts-container">
                                <!-- 담당자 정보가 여기에 동적으로 추가됩니다 -->
                            </div>
                        </div>
                    </div>

                    <!-- 버튼 섹션 -->
                    <div class="m-form-actions">
                        ${isNewRegistration ? 
                            `<button type="submit" class="m-btn-primary">등록</button>
                             <button type="button" class="m-btn-secondary" onclick="resetClientForm()">초기화</button>` :
                            `<button type="submit" class="m-btn-primary">수정</button>
                             <button type="button" class="m-btn-secondary" onclick="closeDamboModal()">닫기</button>`
                        }
                    </div>
                </form>
            </div>
        </div>
    `;
    
    // 담보 모달 내용 설정
    document.getElementById('m_dambo').innerHTML = modalHTML;
    
    // 담보 모달 표시
    showDamboModal();
    
    // 초기화
    contactCounter = 0;
    
    // 기존 데이터가 있으면 폼에 채우기
    if (existingData && isViewEditMode) {
        populateFormWithData(existingData);
    } else {
        // 신규 등록 모드에서는 빈 담당자 추가
        addContactInModal();
    }
    
    // 폼 이벤트 리스너 등록
    setupClientFormEvents();
}

function populateFormWithData(data) {
    console.log('populateFormWithData 호출됨:', data);
    
    // idGubun=2인 경우 데이터 구조가 다름
    let clientInfo, contacts;
    
    if (data.client_info) {
        // idGubun=2인 경우: {client_info: {...}, contacts: [...]}
        clientInfo = data.client_info;
        contacts = data.contacts || [];
    } else {
        // idGubun=1인 경우: 직접 client 정보
        clientInfo = data;
        contacts = data.contacts || [];
    }
    
    console.log('clientInfo:', clientInfo);
    console.log('contacts:', contacts);
    
    // 기본 정보 채우기
    if (clientInfo) {
        const clientNameField = document.getElementById('m-client_name');
        const businessNumberField = document.getElementById('m-business_number');
        const corporateNumberField = document.getElementById('m-corporate_number');
        const ourManagerField = document.getElementById('m-our_manager');
        const descriptionField = document.getElementById('m-description');
        const isActiveField = document.getElementById('m-is_active');
        
        if (clientNameField) clientNameField.value = clientInfo.client_name || '';
        if (businessNumberField) businessNumberField.value = clientInfo.business_number || '';
        if (corporateNumberField) corporateNumberField.value = clientInfo.corporate_number || '';
        if (ourManagerField) ourManagerField.value = clientInfo.our_manager || '';
        if (descriptionField) descriptionField.value = clientInfo.client_description || clientInfo.description || '';
        if (isActiveField) isActiveField.checked = (clientInfo.client_active == 1) || (clientInfo.is_active == 1);
        
        console.log('기본 정보 채우기 완료');
    }
    
    // 담당자 정보 채우기
    if (contacts && contacts.length > 0) {
        console.log('담당자 정보 채우기 시작, 담당자 수:', contacts.length);
        // 기존 담당자들을 모두 추가
        contacts.forEach((contact, index) => {
            console.log(`담당자 ${index + 1} 추가:`, contact);
            addContactInModal(contact, index + 1);
        });
    } else {
        // 담당자가 없으면 빈 담당자 하나 추가
        console.log('담당자가 없어서 빈 담당자 추가');
        addContactInModal();
    }
}

// switchToEditMode 함수는 더 이상 필요하지 않으므로 제거

// 담보 모달 표시 함수
function showDamboModal() {
    const modal = document.getElementById('dambo-modal');
    if (modal) {
        modal.style.display = 'block';
    }
}

// 담보 모달 닫기 함수
function closeDamboModal() {
    const modal = document.getElementById('dambo-modal');
    if (modal) {
        modal.style.display = 'none';
    }
}

function addContactInModal(contactData = null, contactNumber = null) {
    console.log('addContactInModal 호출됨:', contactData, contactNumber);
    
    // contactNumber가 제공되지 않으면 자동으로 증가
    if (contactNumber === null) {
        contactCounter++;
        contactNumber = contactCounter;
    } else {
        // 제공된 번호가 현재 카운터보다 크면 카운터 업데이트
        if (contactNumber > contactCounter) {
            contactCounter = contactNumber;
        }
    }
    
    const container = document.getElementById('m-contacts-container');
    if (!container) {
        console.error('m-contacts-container를 찾을 수 없습니다');
        return;
    }
    
    // 현재 폼에 데이터가 있는지 확인하여 신규 등록인지 판단
    const clientNameField = document.getElementById('m-client_name');
    const isNewRegistration = !clientNameField || !clientNameField.value.trim();
    
    console.log('isNewRegistration:', isNewRegistration);
    
    const contactHTML = `
        <div class="m-contact-item" id="m-contact-${contactNumber}">
            <div class="m-contact-header">
                <span class="m-contact-number">담당자 ${contactNumber}</span>
                <button type="button" class="m-btn-remove" onclick="removeContactInModal(${contactNumber})">삭제</button>
            </div>
            <div class="m-form-row">
                <div class="m-form-group">
                    <label class="m-form-label" for="m-contact_name_${contactNumber}">담당자 성명 ${isNewRegistration ? '<span class="m-required">*</span>' : ''}</label>
                    <input type="text" id="m-contact_name_${contactNumber}" name="contacts[${contactNumber}][contact_name]" class="m-form-input" value="${contactData?.contact_name || ''}" ${isNewRegistration ? 'required' : ''}>
                </div>
                <div class="m-form-group">
                    <label class="m-form-label" for="m-phone_${contactNumber}">연락처</label>
                    <input type="tel" id="m-phone_${contactNumber}" name="contacts[${contactNumber}][phone]" class="m-form-input" placeholder="010-0000-0000" value="${contactData?.phone || ''}" oninput="formatPhoneNumber(this)">
                </div>
            </div>
            <div class="m-form-row">
                <div class="m-form-group">
                    <label class="m-form-label" for="m-email_${contactNumber}">이메일</label>
                    <input type="email" id="m-email_${contactNumber}" name="contacts[${contactNumber}][email]" class="m-form-input" placeholder="example@company.com" value="${contactData?.email || ''}" >
                </div>
                <div class="m-form-group">
                    <div class="m-checkbox-group">
                        <input type="checkbox" id="m-contact_active_${contactNumber}" name="contacts[${contactNumber}][is_active]" ${(contactData?.is_active == 1) ? 'checked' : 'checked'}>
                        <label class="m-form-label" for="m-contact_active_${contactNumber}">활성화 상태</label>
                    </div>
                </div>
            </div>
            <div class="m-form-group">
                <label class="m-form-label" for="m-contact_description_${contactNumber}">설명</label>
                <textarea id="m-contact_description_${contactNumber}" name="contacts[${contactNumber}][description]" class="m-form-textarea" placeholder="담당자에 대한 추가 설명">${contactData?.description || ''}</textarea>
            </div>
        </div>
    `;
    
    container.insertAdjacentHTML('beforeend', contactHTML);
    updateContactNumbers();
    
    console.log(`담당자 ${contactNumber} 추가 완료`);
}

function removeContactInModal(contactId) {
    const contactElement = document.getElementById(`m-contact-${contactId}`);
    if (contactElement) {
        // 최소 1개의 담당자는 유지
        const totalContacts = document.querySelectorAll('.m-contact-item').length;
        if (totalContacts <= 1) {
            showAlertInModal('최소 1명의 담당자는 등록해야 합니다.', 'error');
            return;
        }
        
        contactElement.remove();
        updateContactNumbers();
    }
}

function updateContactNumbers() {
    const contactItems = document.querySelectorAll('.m-contact-item');
    contactItems.forEach((item, index) => {
        const numberSpan = item.querySelector('.m-contact-number');
        if (numberSpan) {
            numberSpan.textContent = `담당자 ${index + 1}`;
        }
    });
}

function showAlertInModal(message, type) {
    const alertContainer = document.getElementById('m-alert-container');
    const alertClass = type === 'error' ? 'm-alert-error' : 'm-alert-success';
    
    alertContainer.innerHTML = `
        <div class="m-alert ${alertClass}">
            ${message}
        </div>
    `;
    
    alertContainer.querySelector('.m-alert').style.display = 'block';
    
    // 3초 후 자동으로 숨기기
    setTimeout(() => {
        alertContainer.innerHTML = '';
    }, 3000);
}

function resetClientForm() {
    if (confirm('모든 입력 내용이 초기화됩니다. 계속하시겠습니까?')) {
        document.getElementById('m-clientForm').reset();
        document.getElementById('m-contacts-container').innerHTML = '';
        contactCounter = 0;
        addContactInModal();
        document.getElementById('m-alert-container').innerHTML = '';
    }
}

function validateClientForm() {
    const clientName = document.getElementById('m-client_name').value.trim();
    if (!clientName) {
        showAlertInModal('거래처명을 입력해주세요.', 'error');
        return false;
    }

    // 사업자번호 형식 검증
    const businessNumber = document.getElementById('m-business_number').value.trim();
    if (businessNumber && !/^\d{3}-\d{2}-\d{5}$/.test(businessNumber)) {
        showAlertInModal('사업자번호 형식이 올바르지 않습니다. (000-00-00000)', 'error');
        return false;
    }

    // 법인번호 형식 검증
    const corporateNumber = document.getElementById('m-corporate_number').value.trim();
    if (corporateNumber && !/^\d{6}-\d{7}$/.test(corporateNumber)) {
        showAlertInModal('법인번호 형식이 올바르지 않습니다. (000000-0000000)', 'error');
        return false;
    }

    // 담당자 정보 검증
    const contactItems = document.querySelectorAll('.m-contact-item');
    let hasValidContact = false;
    
    for (let i = 0; i < contactItems.length; i++) {
        const contactName = contactItems[i].querySelector('[name*="contact_name"]').value.trim();
        if (contactName) {
            hasValidContact = true;
            break;
        }
    }

    if (!hasValidContact) {
        showAlertInModal('최소 1명의 담당자 성명을 입력해주세요.', 'error');
        return false;
    }

    return true;
}

function setupClientFormEvents() {
    // 폼 제출 이벤트 처리
    document.getElementById('m-clientForm').addEventListener('submit', function(e) {
        e.preventDefault();
        
        if (!validateClientForm()) {
            return;
        }

        // 폼 데이터 수집
        const formData = new FormData(this);
        
        // 폼 데이터를 객체로 변환
        const clientData = {
            menu_client_id: currentMenuClientId,
            client_name: formData.get('client_name'),
            business_number: formData.get('business_number'),
            corporate_number: formData.get('corporate_number'),
            description: formData.get('description'),
            our_manager: formData.get('our_manager'),
            is_active: formData.get('is_active') ? 1 : 0,
            contacts: []
        };

        // 담당자 정보 수집
        const contactItems = document.querySelectorAll('.m-contact-item');
        contactItems.forEach((item, index) => {
            const contactName = item.querySelector('[name*="contact_name"]').value.trim();
            if (contactName) {
                clientData.contacts.push({
                    contact_name: contactName,
                    phone: item.querySelector('[name*="phone"]').value.trim(),
                    email: item.querySelector('[name*="email"]').value.trim(),
                    description: item.querySelector('[name*="description"]').value.trim(),
                    is_active: item.querySelector('[name*="is_active"]').checked ? 1 : 0
                });
            }
        });

        console.log('등록할 거래처 데이터:', clientData);
        
        // 서버로 전송
        submitClientToServer(clientData);
    });

    // 담보 모달 닫기 버튼 이벤트 처리
    const closeButton = document.querySelector('.close-damboModal');
    if (closeButton) {
        closeButton.addEventListener('click', closeDamboModal);
    }

    // 모달 배경 클릭 시 닫기
    const modal = document.getElementById('dambo-modal');
    if (modal) {
        modal.addEventListener('click', function(e) {
            if (e.target === modal) {
                closeDamboModal();
            }
        });
    }
}

function submitClientToServer(data) {
    // **수정 모드인 경우 client_info_id 추가**
    if (currentIdGubun == 2 && currentClientInfoId) {
        data.client_info_id = currentClientInfoId;
        data.action = 'update'; // 수정 액션 명시
        console.log('수정 모드 - client_info_id 포함:', currentClientInfoId);
    } else {
        data.action = 'create'; // 등록 액션 명시
        console.log('등록 모드 - 새로운 거래처 생성');
    }
    
    console.log('전송될 데이터:', data);
    
    fetch('/api/manual/client_contacts.php', {
        method: 'POST',
        headers: {
            'Content-Type': 'application/json',
        },
        body: JSON.stringify(data)
    })
    .then(response => response.json())
    .then(result => {
        console.log('서버 응답:', result);
        
        if (result.success) {
            if (currentIdGubun == 1) {
                // 신규 등록 성공 시
                showAlertInModal('등록이 성공적으로 완료되었습니다!', 'success');
                
                // **중요: 서버에서 반환된 client_info_id 저장**
                if (result.client_info_id) {
                    currentClientInfoId = result.client_info_id;
                    currentIdGubun = 2; // 이제 조회/수정 모드로 변경
                    
                    console.log('=== 신규 등록 후 전역 변수 업데이트 ===');
                    console.log('새로 생성된 client_info_id:', currentClientInfoId);
                    console.log('currentMenuClientId:', currentMenuClientId);
                    console.log('currentIdGubun:', currentIdGubun);
                    console.log('=======================================');
                    
                    // 모달 UI를 등록 모드에서 수정 모드로 변경
                    updateModalToEditMode();
                } else {
                    console.warn('서버에서 client_info_id를 반환하지 않았습니다.');
                    console.warn('서버 응답 전체:', result);
                }
            } else if (currentIdGubun == 2) {
                // 수정 성공 시
                showAlertInModal('수정이 성공적으로 완료되었습니다!', 'success');
                
                console.log('=== 수정 완료 ===');
                console.log('수정된 client_info_id:', currentClientInfoId);
                console.log('================');
            }
            
            setTimeout(() => {
                // 필요에 따라 모달 닫기 또는 페이지 새로고침
                // closeDamboModal();
                // location.reload();
            }, 1500);
        } else {
            const errorMessage = currentIdGubun == 1 ? '등록 중 오류가 발생했습니다.' : '수정 중 오류가 발생했습니다.';
            showAlertInModal(result.message || errorMessage, 'error');
            console.error('서버 오류:', result.message);
        }
    })
    .catch(error => {
        console.error('네트워크 오류:', error);
        showAlertInModal('서버 오류가 발생했습니다.', 'error');
    });
}


// 모달을 등록 모드에서 수정 모드로 변경하는 함수
// 모달을 등록 모드에서 수정 모드로 변경하는 함수 (삭제 버튼 추가)
function updateModalToEditMode() {
    // 모달 제목 변경
    const modalTitle = document.getElementById('dambo_daeriCompany');
    if (modalTitle) {
        const currentTitle = modalTitle.textContent;
        const newTitle = currentTitle.replace('등록', '수정');
        modalTitle.textContent = newTitle;
    }
    
    // 버튼 텍스트 변경 (삭제 버튼 추가)
    const formActions = document.querySelector('.m-form-actions');
    if (formActions) {
        formActions.innerHTML = `
            <button type="submit" class="m-btn-primary">저장</button>
            <button type="button" class="m-btn-remove" onclick="deleteClientFromServer()">삭제</button>
            <button type="button" class="m-btn-secondary" onclick="closeDamboModal()">닫기</button>
        `;
    }
    
    // 필수 표시(*) 제거
    const requiredSpans = document.querySelectorAll('.m-required');
    requiredSpans.forEach(span => {
        span.remove();
    });
    
    // required 속성 제거
    const requiredInputs = document.querySelectorAll('input[required], textarea[required]');
    requiredInputs.forEach(input => {
        input.removeAttribute('required');
    });
    
    console.log('모달이 수정 모드로 변경되었습니다.');
}

// 디버깅을 위한 현재 상태 확인 함수
function getCurrentState() {
    console.log('=== 현재 상태 ===');
    console.log('currentMenuClientId:', currentMenuClientId);
    console.log('currentClientInfoId:', currentClientInfoId);
    console.log('currentIdGubun:', currentIdGubun);
    console.log('모드:', currentIdGubun == 1 ? '신규 등록' : currentIdGubun == 2 ? '조회/수정' : '알 수 없음');
    console.log('================');
}

// 전역 변수 초기화 함수
function resetGlobalVariables() {
    currentMenuClientId = null;
    currentClientInfoId = null;
    currentIdGubun = null;
    contactCounter = 0;
    console.log('전역 변수가 초기화되었습니다.');
}

// 모달 열기 전 상태 확인
function openAddClientModal(itemId, idGubun) {
    console.log('=== 모달 열기 시작 ===');
    console.log('전달받은 itemId:', itemId);
    console.log('전달받은 idGubun:', idGubun);
    
    // 전역 변수 초기화 및 설정
    currentMenuClientId = itemId;
    currentIdGubun = idGubun;
    currentClientInfoId = null; // 신규 등록 시에는 null로 초기화
    
    console.log('초기 전역 변수 설정 완료');
    getCurrentState();
    
    // 서버에서 정보 조회
    fetchMenuClientInfo(itemId, idGubun);
}

// 전화번호 자동 포맷팅 함수
function formatPhoneNumber(input) {
    let value = input.value.replace(/[^0-9]/g, ''); // 숫자만 추출
    
    if (value.length <= 3) {
        input.value = value;
    } else if (value.length <= 7) {
        input.value = value.slice(0, 3) + '-' + value.slice(3);
    } else if (value.length <= 11) {
        input.value = value.slice(0, 3) + '-' + value.slice(3, 7) + '-' + value.slice(7);
    } else {
        input.value = value.slice(0, 3) + '-' + value.slice(3, 7) + '-' + value.slice(7, 11);
    }
}

// 사업자번호 자동 포맷팅 함수
function formatBusinessNumber(input) {
    let value = input.value.replace(/[^0-9]/g, ''); // 숫자만 추출
    
    if (value.length <= 3) {
        input.value = value;
    } else if (value.length <= 5) {
        input.value = value.slice(0, 3) + '-' + value.slice(3);
    } else if (value.length <= 10) {
        input.value = value.slice(0, 3) + '-' + value.slice(3, 5) + '-' + value.slice(5);
    } else {
        input.value = value.slice(0, 3) + '-' + value.slice(3, 5) + '-' + value.slice(5, 10);
    }
}

// 법인번호 자동 포맷팅 함수
function formatCorporateNumber(input) {
    let value = input.value.replace(/[^0-9]/g, ''); // 숫자만 추출
    
    if (value.length <= 6) {
        input.value = value;
    } else if (value.length <= 13) {
        input.value = value.slice(0, 6) + '-' + value.slice(6);
    } else {
        input.value = value.slice(0, 6) + '-' + value.slice(6, 13);
    }
}


//거래처 리스트
//거래처 리스트 (hollinwon-modal 버전)
// 거래처 리스트 모달 열기
function openClientListModal(itemId) {
    console.log('거래처 리스트 모달 열기 - Item ID:', itemId);
    
    // 로딩 상태 표시
    const loadingHTML = `
        <div class="modal-header">
            <span class="modal-title">거래처 리스트 (ID: ${itemId})</span>
        </div>
        <div style="padding: 40px; text-align: center;">
            <div style="font-size: 18px; color: #666; margin-bottom: 20px;">
                ⏳ 거래처 정보를 불러오는 중...
            </div>
        </div>
    `;
    
    document.getElementById('m_hollinwon').innerHTML = loadingHTML;
    showHollinwonModal();
    
    // API 호출하여 거래처 리스트 조회
    fetchClientList(itemId);
}

// hollinwon 모달 표시 함수
function showHollinwonModal() {
    const modal = document.getElementById('hollinwon-modal');
    if (modal) {
        modal.style.display = 'flex';
        document.body.style.overflow = 'hidden'; // 배경 스크롤 방지
    }
}

// hollinwon 모달 닫기 함수
function closeHollinwonModal() {
    const modal = document.getElementById('hollinwon-modal');
    if (modal) {
        modal.style.display = 'none';
        document.body.style.overflow = 'auto'; // 배경 스크롤 복원
    }
}

// 거래처 리스트 API 호출
async function fetchClientList(menuClientId) {
    try {
        const response = await fetch(`/api/manual/get_client_list.php?menu_client_id=${menuClientId}`);
        const result = await response.json();
        
        if (result.success) {
            renderClientList(result.data, menuClientId);
        } else {
            showErrorMessage(result.message || '거래처 정보를 불러오는데 실패했습니다.');
        }
    } catch (error) {
        console.error('API 호출 오류:', error);
        showErrorMessage('서버와의 연결에 문제가 발생했습니다.');
    }
}

// 연락처 정보 렌더링 함수
function renderContactInfo(client) {
    const contactCount = client.contact_count || 0;
    
    if (contactCount === 0) {
        return '<span style="color: #95a5a6; font-style: italic;">등록된 연락처가 없습니다</span>';
    } else if (contactCount === 1) {
        // 1개일 때는 바로 표시
        const contact = client.contacts[0];
        return `
            <div style="
                background: #f8f9fa;
                padding: 12px;
                border-radius: 6px;
                border-left: 4px solid #3498db;
            ">
                <div style="font-weight: 600; color: #2c3e50; margin-bottom: 5px;">
                    👤 ${contact.contact_name}
                </div>
                <div style="font-size: 13px; color: #7f8c8d; margin-bottom: 3px;">
                    📞 ${contact.phone}
                </div>
                <div style="font-size: 13px; color: #7f8c8d; margin-bottom: 3px;">
                    ✉️ ${contact.email}
                </div>
                ${contact.description ? `
                    <div style="font-size: 12px; color: #95a5a6; margin-top: 5px;">
                        ${contact.description}
                    </div>
                ` : ''}
                <div style="
                    font-size: 11px; 
                    color: ${contact.is_active ? '#27ae60' : '#e74c3c'};
                    margin-top: 5px;
                    font-weight: 500;
                ">
                    ${contact.is_active ? '✅ 활성' : '❌ 비활성'}
                </div>
            </div>
        `;
    } else {
        // 2개 이상일 때는 버튼으로 표시
        return `
            <div style="text-align: center;">
                <button type="button" 
                        onclick="showContactListModal(${client.id}, '${client.client_name.replace(/'/g, "\\'")}', ${JSON.stringify(client.contacts).replace(/"/g, '&quot;')})"
                        style="
                            padding: 10px 20px;
                            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
                            color: white;
                            border: none;
                            border-radius: 20px;
                            cursor: pointer;
                            font-size: 13px;
                            font-weight: 500;
                            transition: all 0.3s ease;
                            box-shadow: 0 2px 8px rgba(102, 126, 234, 0.3);
                        "
                        onmouseover="this.style.transform='translateY(-2px)'; this.style.boxShadow='0 4px 12px rgba(102, 126, 234, 0.4)'"
                        onmouseout="this.style.transform='translateY(0)'; this.style.boxShadow='0 2px 8px rgba(102, 126, 234, 0.3)'">
                    👥 연락처 ${contactCount}개 보기
                </button>
            </div>
        `;
    }
}

// 연락처 리스트 모달 표시
function showContactListModal(clientId, clientName, contacts) {
    const contactsHTML = contacts.map(contact => `
        <div style="
            border: 1px solid #e9ecef;
            border-radius: 8px;
            padding: 15px;
            margin-bottom: 10px;
            background: ${contact.is_active ? '#ffffff' : '#f8f9fa'};
            transition: all 0.3s ease;
        " onmouseover="this.style.boxShadow='0 2px 8px rgba(0,0,0,0.1)'" 
           onmouseout="this.style.boxShadow='none'">
            <div style="
                display: flex;
                justify-content: space-between;
                align-items: center;
                margin-bottom: 10px;
            ">
                <h4 style="
                    margin: 0;
                    color: #2c3e50;
                    font-size: 16px;
                    font-weight: 600;
                ">👤 ${contact.contact_name}</h4>
                <span style="
                    font-size: 11px;
                    color: ${contact.is_active ? '#27ae60' : '#e74c3c'};
                    background: ${contact.is_active ? '#d5f4e6' : '#fadbd8'};
                    padding: 3px 8px;
                    border-radius: 10px;
                    font-weight: 500;
                ">${contact.is_active ? '✅ 활성' : '❌ 비활성'}</span>
            </div>
            <div style="font-size: 14px; color: #34495e; line-height: 1.5;">
                <div style="margin-bottom: 5px;">
                    <strong>📞 연락처:</strong> ${contact.phone}
                </div>
                <div style="margin-bottom: 5px;">
                    <strong>✉️ 이메일:</strong> ${contact.email}
                </div>
                ${contact.description ? `
                    <div style="margin-bottom: 5px;">
                        <strong>📝 설명:</strong> ${contact.description}
                    </div>
                ` : ''}
                <div style="font-size: 12px; color: #95a5a6; margin-top: 8px;">
                    등록일: ${formatDate(contact.created_at)}
                </div>
            </div>
        </div>
    `).join('');

    const modalHTML = `
        <div style="
            position: fixed;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            background: rgba(0, 0, 0, 0.5);
            display: flex;
            justify-content: center;
            align-items: center;
            z-index: 10000;
        " id="contact-list-modal" onclick="closeContactListModal()">
            <div style="
                background: white;
                border-radius: 12px;
                width: 90%;
                max-width: 600px;
                max-height: 80vh;
                overflow: hidden;
                box-shadow: 0 10px 30px rgba(0, 0, 0, 0.3);
                font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            " onclick="event.stopPropagation()">
                <div style="
                    background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
                    color: white;
                    padding: 20px;
                    border-radius: 12px 12px 0 0;
                ">
                    <h3 style="margin: 0; font-size: 18px; font-weight: 600;">
                        👥 ${clientName} 연락처 목록
                    </h3>
                    <p style="margin: 5px 0 0 0; font-size: 14px; opacity: 0.9;">
                        총 ${contacts.length}개의 연락처
                    </p>
                </div>
                <div style="
                    padding: 20px;
                    max-height: 50vh;
                    overflow-y: auto;
                ">
                    ${contactsHTML}
                </div>
                <div style="
                    padding: 15px 20px;
                    border-top: 1px solid #e9ecef;
                    text-align: right;
                    background: #f8f9fa;
                    border-radius: 0 0 12px 12px;
                ">
                    <button type="button" 
                            onclick="closeContactListModal()"
                            style="
                                padding: 10px 20px;
                                background: #6c757d;
                                color: white;
                                border: none;
                                border-radius: 6px;
                                cursor: pointer;
                                font-size: 14px;
                                font-weight: 500;
                                transition: all 0.3s ease;
                            "
                            onmouseover="this.style.background='#5a6268'"
                            onmouseout="this.style.background='#6c757d'">
                        닫기
                    </button>
                </div>
            </div>
        </div>
    `;

    document.body.insertAdjacentHTML('beforeend', modalHTML);
    document.body.style.overflow = 'hidden';
}

// 연락처 리스트 모달 닫기
function closeContactListModal() {
    const modal = document.getElementById('contact-list-modal');
    if (modal) {
        modal.remove();
        document.body.style.overflow = 'auto';
    }
}

// 거래처 리스트 렌더링
// 거래처 리스트 렌더링 (테이블 버전)
function renderClientList(data, menuClientId) {
    const { menu_info, clients, total_count } = data;
    
    // 테이블 헤더 및 바디 생성
    const tableRowsHTML = clients.map((client, index) => {
        // 연락처 정보 처리
        const contactInfo = client.contact_count > 0 ? client.contacts[0] : null;
        const contactDisplay = contactInfo ? {
            name: contactInfo.contact_name || '-',
            phone: contactInfo.phone || '-',
            email: contactInfo.email || '-',
            description: contactInfo.description || '-',
            isActive: contactInfo.is_active ? '활성' : '비활성'
        } : {
            name: '-',
            phone: '-',
            email: '-',
            description: '-',
            isActive: '-'
        };

        return `
            <tr class="client-row" data-client-id="${client.id}">
                <td class="text-center">${index + 1}</td>
                <td class="client-name">
                    <div class="company-info">
                        <strong>${client.client_name}</strong>
                        <small class="text-muted d-block">${formatDate(client.created_at)}</small>
                    </div>
                </td>
                
                <td class="text-center">${client.our_manager || '-'}</td>
                <td class="description-cell">
                    <div class="description-content" title="${client.description || ''}">
                        ${client.description ? (client.description.length > 30 ? client.description.substring(0, 30) + '...' : client.description) : '-'}
                    </div>
                </td>
                <td class="text-center">${contactDisplay.name}</td>
                <td class="text-center">
                    <a href="tel:${contactDisplay.phone}" class="contact-link">${contactDisplay.phone}</a>
                </td>
                <td class="text-center">
                    <a href="mailto:${contactDisplay.email}" class="contact-link">${contactDisplay.email}</a>
                </td>
                
                
                <td class="text-center">
                    <div class="action-buttons">
                        ${client.contact_count > 1 ? `
                            <button type="button" class="btn btn-sm btn-info me-1" 
                                    onclick="showContactListModal(${client.id}, '${client.client_name.replace(/'/g, "\\'")}', ${JSON.stringify(client.contacts).replace(/"/g, '&quot;')})"
                                    title="연락처 ${client.contact_count}개 보기">
                                <i class="fas fa-users"></i>
                            </button>
                        ` : ''}
                        <button type="button" class="btn btn-sm btn-primary me-1" 
                                onclick="openAddClientModal(${client.id},2)"
                                title="상세보기">
                            <i class="fas fa-eye">상세보기</i>
                        </button>
                        <button type="button" class="btn btn-sm btn-primary me-1" 
                                onclick="viewClientDetail(${client.id})"
                                title="메모">
                            <i class="fas fa-eye">메모</i>
                        </button>
                    </div>
                </td>
            </tr>
        `;
    }).join('');

    const modalHTML = `
        <style>
            .client-table-container {
                font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
                max-width: 100%;
                background: white;
                border-radius: 12px;
                overflow: hidden;
                box-shadow: 0 10px 30px rgba(0,0,0,0.1);
            }
            
            .table-header {
                background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
                color: white;
                padding: 25px 30px;
                text-align: center;
            }
            
            .table-title {
                font-size: 24px;
                font-weight: 700;
                margin: 0 0 8px 0;
                display: flex;
                align-items: center;
                justify-content: center;
                gap: 12px;
            }
            
            .table-subtitle {
                font-size: 14px;
                opacity: 0.9;
                margin: 0;
            }
            
            .table-wrapper {
                max-height: 60vh;
                overflow-y: auto;
                overflow-x: auto;
                border: 1px solid #e9ecef;
            }
            
            .client-table {
                width: 100%;
                border-collapse: collapse;
                background: white;
                font-size: 13px;
            }
            
            .client-table th {
                background: linear-gradient(135deg, #f8f9fa 0%, #e9ecef 100%);
                color: #2c3e50;
                font-weight: 600;
                padding: 15px 12px;
                text-align: center;
                border-bottom: 2px solid #dee2e6;
                border-right: 1px solid #dee2e6;
                position: sticky;
                top: 0;
                z-index: 10;
                white-space: nowrap;
                font-size: 12px;
            }
            
            .client-table th:last-child {
                border-right: none;
            }
            
            .client-table td {
                padding: 12px 10px;
                border-bottom: 1px solid #f8f9fa;
                border-right: 1px solid #f8f9fa;
                vertical-align: middle;
            }
            
            .client-table td:last-child {
                border-right: none;
            }
            
            .client-row:hover {
                background: linear-gradient(135deg, #f8f9ff 0%, #f0f4ff 100%);
            }
            
            .client-row:nth-child(even) {
                background: #fafbfc;
            }
            
            .client-row:nth-child(even):hover {
                background: linear-gradient(135deg, #f8f9ff 0%, #f0f4ff 100%);
            }
            
            .company-info strong {
                color: #2c3e50;
                font-size: 14px;
                font-weight: 600;
            }
            
            .text-muted {
                color: #6c757d !important;
                font-size: 11px;
            }
            
            .business-number {
                background: #f8f9fa;
                color: #495057;
                padding: 4px 8px;
                border-radius: 4px;
                font-family: 'Courier New', monospace;
                font-size: 12px;
                border: 1px solid #e9ecef;
            }
            
            .description-cell {
                max-width: 150px;
            }
            
            .description-content {
                overflow: hidden;
                text-overflow: ellipsis;
                white-space: nowrap;
                cursor: help;
            }
            
            .contact-description {
                overflow: hidden;
                text-overflow: ellipsis;
                white-space: nowrap;
                cursor: help;
                max-width: 100px;
            }
            
            .contact-link {
                color: #007bff;
                text-decoration: none;
                font-weight: 500;
            }
            
            .contact-link:hover {
                color: #0056b3;
                text-decoration: underline;
            }
            
            .status-badge {
                padding: 4px 10px;
                border-radius: 15px;
                font-size: 11px;
                font-weight: 600;
                text-transform: uppercase;
                letter-spacing: 0.5px;
            }
            
            .status-badge.active {
                background: #d4edda;
                color: #155724;
                border: 1px solid #c3e6cb;
            }
            
            .status-badge.inactive {
                background: #f8d7da;
                color: #721c24;
                border: 1px solid #f5c6cb;
            }
            
            .action-buttons {
                display: flex;
                gap: 4px;
                justify-content: center;
                align-items: center;
            }
            
            .btn {
                padding: 6px 10px;
                border: none;
                border-radius: 4px;
                cursor: pointer;
                font-size: 12px;
                font-weight: 500;
                transition: all 0.3s ease;
                text-decoration: none;
                display: inline-flex;
                align-items: center;
                justify-content: center;
            }
            
            .btn-sm {
                padding: 4px 8px;
                font-size: 11px;
            }
            
            .btn-info {
                background: #17a2b8;
                color: white;
            }
            
            .btn-info:hover {
                background: #138496;
                transform: translateY(-1px);
                box-shadow: 0 2px 4px rgba(23, 162, 184, 0.3);
            }
            
            .btn-primary {
                background: #007bff;
                color: white;
            }
            
            .btn-primary:hover {
                background: #0056b3;
                transform: translateY(-1px);
                box-shadow: 0 2px 4px rgba(0, 123, 255, 0.3);
            }
            
            .btn-success {
                background: #28a745;
                color: white;
            }
            
            .btn-success:hover {
                background: #1e7e34;
                transform: translateY(-1px);
                box-shadow: 0 2px 4px rgba(40, 167, 69, 0.3);
            }
            
            .me-1 {
                margin-right: 4px;
            }
            
            .text-center {
                text-align: center;
            }
            
            .d-block {
                display: block;
            }
            
            .no-clients-message {
                text-align: center;
                padding: 60px 20px;
                color: #6c757d;
                background: #f8f9fa;
            }
            
            .no-clients-icon {
                font-size: 64px;
                margin-bottom: 20px;
                opacity: 0.5;
            }
            
            .no-clients-text {
                font-size: 18px;
                font-weight: 600;
                margin-bottom: 10px;
                color: #495057;
            }
            
            .no-clients-subtext {
                font-size: 14px;
                color: #6c757d;
            }
            
            .table-footer {
                background: #f8f9fa;
                padding: 20px;
                border-top: 1px solid #dee2e6;
                text-align: center;
            }
            
            .table-info {
                font-size: 14px;
                color: #6c757d;
                margin-bottom: 15px;
            }
            
            .btn-close-table {
                background: #6c757d;
                color: white;
                padding: 10px 24px;
                border: none;
                border-radius: 6px;
                cursor: pointer;
                font-size: 14px;
                font-weight: 500;
                transition: all 0.3s ease;
            }
            
            .btn-close-table:hover {
                background: #5a6268;
                transform: translateY(-1px);
                box-shadow: 0 4px 8px rgba(0,0,0,0.1);
            }
            
            /* 스크롤바 스타일링 */
            .table-wrapper::-webkit-scrollbar {
                width: 8px;
                height: 8px;
            }
            
            .table-wrapper::-webkit-scrollbar-track {
                background: #f1f1f1;
                border-radius: 4px;
            }
            
            .table-wrapper::-webkit-scrollbar-thumb {
                background: #c1c1c1;
                border-radius: 4px;
            }
            
            .table-wrapper::-webkit-scrollbar-thumb:hover {
                background: #a8a8a8;
            }
            
            .table-wrapper::-webkit-scrollbar-corner {
                background: #f1f1f1;
            }
            
            /* 반응형 디자인 */
            @media (max-width: 768px) {
                .client-table {
                    font-size: 11px;
                }
                
                .client-table th,
                .client-table td {
                    padding: 8px 6px;
                }
                
                .table-title {
                    font-size: 18px;
                }
                
                .action-buttons {
                    flex-direction: column;
                    gap: 2px;
                }
            }
        </style>
        
        <div class="client-table-container">
            <div class="table-header">
                <h2 class="table-title">
                    🏢 거래처 현황
                </h2>
                <p class="table-subtitle">
                    ${menu_info.menu_1st || ''}${menu_info.menu_2nd ? ` > ${menu_info.menu_2nd}` : ''}${menu_info.menu_3rd ? ` > ${menu_info.menu_3rd}` : ''}
                </p>
            </div>
            
            ${total_count > 0 ? `
                <div class="table-wrapper">
                    <table class="client-table">
                        <thead>
                            <tr>
                                <th style="width: 60px;">순번</th>
                                <th style="width: 180px;">업체명</th>
                                
                                <th style="width: 100px;">당사담당자</th>
                                <th style="width: 150px;">설명</th>
                                <th style="width: 100px;">성명</th>
                                <th style="width: 120px;">연락처</th>
                                <th style="width: 150px;">이메일</th>
                                
                                
                                <th style="width: 120px;">관리</th>
                            </tr>
                        </thead>
                        <tbody>
                            ${tableRowsHTML}
                        </tbody>
                    </table>
                </div>
            ` : `
                <div class="no-clients-message">
                    <div class="no-clients-icon">📭</div>
                    <div class="no-clients-text">등록된 거래처가 없습니다</div>
                    <div class="no-clients-subtext">새로운 거래처를 등록해보세요</div>
                </div>
            `}
            
            
        </div>
    `;
    
    document.getElementById('m_hollinwon').innerHTML = modalHTML;
}

// 오류 메시지 표시
function showErrorMessage(message) {
    const errorHTML = `
        <style>
            .error-container {
                font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
                text-align: center;
                padding: 60px 40px;
            }
            
            .error-icon {
                font-size: 64px;
                color: #e74c3c;
                margin-bottom: 20px;
            }
            
            .error-title {
                font-size: 24px;
                color: #2c3e50;
                font-weight: 600;
                margin-bottom: 15px;
            }
            
            .error-message {
                font-size: 16px;
                color: #7f8c8d;
                margin-bottom: 30px;
                line-height: 1.5;
            }
            
            .btn-error-close {
                padding: 12px 30px;
                background: #e74c3c;
                color: white;
                border: none;
                border-radius: 6px;
                cursor: pointer;
                font-size: 14px;
                font-weight: 500;
                transition: all 0.3s ease;
            }
            
            .btn-error-close:hover {
                background: #c0392b;
                transform: translateY(-1px);
                box-shadow: 0 4px 12px rgba(231, 76, 60, 0.3);
            }
        </style>
        
        <div class="error-container">
            <div class="error-icon">⚠️</div>
            <div class="error-title">오류가 발생했습니다</div>
            <div class="error-message">${message}</div>
            <button type="button" class="btn-error-close" onclick="closeHollinwonModal()">
                확인
            </button>
        </div>
    `;
    
    document.getElementById('m_hollinwon').innerHTML = errorHTML;
}

// 날짜 포맷팅
function formatDate(dateString) {
    const date = new Date(dateString);
    return date.toLocaleDateString('ko-KR', {
        year: 'numeric',
        month: '2-digit',
        day: '2-digit'
    });
}

// 메모
function viewClientDetail(clientId) {
    console.log('메모:', clientId);
    // 여기에 상세보기 모달 또는 페이지 이동 로직 추가
    alert(`메모 (ID: ${clientId})`);
}

// 거래처 수정
function editClient(clientId) {
    console.log('거래처 수정:', clientId);
    // 여기에 수정 모달 또는 페이지 이동 로직 추가
    alert(`거래처 수정 기능 (ID: ${clientId})`);
}

// hollinwon 모달 초기화 (페이지 로드 시 실행)
document.addEventListener('DOMContentLoaded', function() {
    // 모달 배경 클릭 시 닫기
    const modal = document.getElementById('hollinwon-modal');
    if (modal) {
        modal.addEventListener('click', function(e) {
            if (e.target === modal) {
                closeHollinwonModal();
            }
        });
    }
    
    // 닫기 버튼 이벤트
    const closeBtn = document.querySelector('.close-hollinwonModal');
    if (closeBtn) {
        closeBtn.addEventListener('click', closeHollinwonModal);
    }
    
    // ESC 키로 모달 닫기
    document.addEventListener('keydown', function(e) {
        if (e.key === 'Escape') {
            // 연락처 모달이 열려있으면 먼저 닫기
            if (document.getElementById('contact-list-modal')) {
                closeContactListModal();
            }
            // 메인 모달이 열려있으면 닫기
            else if (modal && modal.style.display === 'flex') {
                closeHollinwonModal();
            }
        }
    });
});

